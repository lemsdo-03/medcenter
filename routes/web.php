<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Doctor\DoctorController;
use App\Http\Controllers\Receptionist\PatientController;
use App\Http\Controllers\Receptionist\AppointmentController;
use App\Http\Controllers\Receptionist\ReportController;
use App\Http\Controllers\Receptionist\EmergencyNotificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Redirect root URL to login page
Route::view('/landing', 'public.home');
Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'role:doctor'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/dashboard', [DoctorController::class, 'dashboard'])->name('dashboard');
    Route::get('/appointments', [DoctorController::class, 'appointments'])->name('appointments');
    Route::get('/appointments/{id}', [DoctorController::class, 'showAppointment'])->name('appointments.show');
    Route::get('/emergency/latest', [DoctorController::class, 'latestEmergency'])->name('emergency.latest');
    Route::get('/patients/{patient}', [DoctorController::class, 'viewPatient'])->name('patient.view');
    Route::get('/availability', [DoctorController::class, 'availability'])->name('availability');
    Route::post('/availability', [DoctorController::class, 'storeAvailability'])->name('availability.store');
    Route::get('/patients/{patient}/notes/create', [DoctorController::class, 'addMedicalNote'])->name('notes.create');
    Route::post('/patients/{patient}/notes', [DoctorController::class, 'storeMedicalNote'])->name('notes.store');
});


Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
    Route::get('/staff/create', [StaffController::class, 'create'])->name('staff.create');
    Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
    Route::get('/staff/{staff}/edit', [StaffController::class, 'edit'])->name('staff.edit');
    Route::put('/staff/{staff}', [StaffController::class, 'update'])->name('staff.update');
    Route::delete('/staff/{staff}', [StaffController::class, 'destroy'])->name('staff.destroy');
});

Route::middleware(['auth', 'role:receptionist'])->prefix('receptionist')->name('receptionist.')->group(function () {
    Route::resource('patients', PatientController::class);
    Route::resource('appointments', AppointmentController::class);
    Route::delete('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
    Route::get('/appointments/availability/check', [AppointmentController::class, 'availability'])->name('appointments.availability');
    Route::get('/appointments/availability/slots', [AppointmentController::class, 'getAvailableSlots'])->name('appointments.availability.slots');
    Route::get('/reports/monthly', [ReportController::class, 'monthly'])->name('reports.monthly');
    Route::get('/emergency/create', [EmergencyNotificationController::class, 'create'])->name('emergency.create');
    Route::post('/emergency', [EmergencyNotificationController::class, 'store'])->name('emergency.store');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    
    switch ($user->role) {
        case 'admin':
            return redirect()->route('admin.staff.index');
        case 'doctor':
            return redirect()->route('doctor.dashboard');
        case 'receptionist':
            return redirect()->route('receptionist.patients.index');
        default:
            return view('dashboard');
    }
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';