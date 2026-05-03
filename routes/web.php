<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\ComplaintController;
use App\Http\Controllers\Doctor\DoctorController;
use App\Http\Controllers\Doctor\ChatController as DoctorChatController;
use App\Http\Controllers\Receptionist\PatientController;
use App\Http\Controllers\Receptionist\AppointmentController;
use App\Http\Controllers\Receptionist\ReportController;
use App\Http\Controllers\Receptionist\EmergencyNotificationController;
use App\Http\Controllers\Patient\AuthController as PatientAuthController;
use App\Http\Controllers\Patient\DashboardController as PatientDashboardController;
use App\Http\Controllers\Patient\ProfileController as PatientProfileController;
use App\Http\Controllers\Patient\DoctorController as PatientDoctorController;
use App\Http\Controllers\Patient\AppointmentController as PatientAppointmentController;
use App\Http\Controllers\Patient\NotificationController as PatientNotificationController;
use App\Http\Controllers\Patient\ChatController as PatientChatController;
use App\Http\Controllers\Patient\RatingController as PatientRatingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::view('/landing', 'public.home');
Route::get('/', function () {
    return redirect()->route('login');
});

// Patient registration (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/patient/register', [PatientAuthController::class, 'showRegistrationForm'])->name('patient.register');
    Route::post('/patient/register', [PatientAuthController::class, 'register']);
});

// Doctor routes
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

    // Doctor chat
    Route::get('/chat', [DoctorChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{conversation}', [DoctorChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{conversation}/reply', [DoctorChatController::class, 'reply'])->name('chat.reply');
    Route::post('/chat/{conversation}/file', [DoctorChatController::class, 'sendFile'])->name('chat.file');
    Route::get('/chat/{conversation}/poll', [DoctorChatController::class, 'pollMessages'])->name('chat.poll');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
    Route::get('/staff/create', [StaffController::class, 'create'])->name('staff.create');
    Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
    Route::get('/staff/{staff}/edit', [StaffController::class, 'edit'])->name('staff.edit');
    Route::put('/staff/{staff}', [StaffController::class, 'update'])->name('staff.update');
    Route::delete('/staff/{staff}', [StaffController::class, 'destroy'])->name('staff.destroy');

    // Complaints
    Route::get('/complaints', [ComplaintController::class, 'index'])->name('complaints.index');
    Route::get('/complaints/{rating}', [ComplaintController::class, 'show'])->name('complaints.show');
});

// Receptionist routes
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

// Patient portal routes
Route::middleware(['auth', 'role:patient'])->prefix('patient')->name('patient.')->group(function () {
    Route::get('/dashboard', [PatientDashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [PatientProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [PatientProfileController::class, 'update'])->name('profile.update');

    // Doctors & availability
    Route::get('/doctors', [PatientDoctorController::class, 'index'])->name('doctors.index');
    Route::get('/doctors/{doctor}', [PatientDoctorController::class, 'show'])->name('doctors.show');
    Route::get('/doctors/{doctor}/slots', [PatientDoctorController::class, 'slots'])->name('doctors.slots');

    // Appointments
    Route::get('/appointments', [PatientAppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create/{doctor}', [PatientAppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [PatientAppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/appointments/{appointment}/edit', [PatientAppointmentController::class, 'edit'])->name('appointments.edit');
    Route::put('/appointments/{appointment}', [PatientAppointmentController::class, 'update'])->name('appointments.update');
    Route::delete('/appointments/{appointment}', [PatientAppointmentController::class, 'cancel'])->name('appointments.cancel');

    // Notifications
    Route::get('/notifications', [PatientNotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{notification}/read', [PatientNotificationController::class, 'markAsRead'])->name('notifications.read');

    // Chat
    Route::get('/chat', [PatientChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{conversation}', [PatientChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/start/{doctor}', [PatientChatController::class, 'startChat'])->name('chat.start');
    Route::post('/chat/{conversation}/message', [PatientChatController::class, 'sendMessage'])->name('chat.message');
    Route::post('/chat/{conversation}/file', [PatientChatController::class, 'sendFile'])->name('chat.file');
    Route::get('/chat/{conversation}/poll', [PatientChatController::class, 'pollMessages'])->name('chat.poll');

    // Ratings & complaints
    Route::get('/ratings/create', [PatientRatingController::class, 'create'])->name('ratings.create');
    Route::post('/ratings', [PatientRatingController::class, 'store'])->name('ratings.store');
});

// Dashboard redirect based on role
Route::get('/dashboard', function () {
    $user = auth()->user();

    switch ($user->role) {
        case 'admin':
            return redirect()->route('admin.staff.index');
        case 'doctor':
            return redirect()->route('doctor.dashboard');
        case 'receptionist':
            return redirect()->route('receptionist.patients.index');
        case 'patient':
            return redirect()->route('patient.dashboard');
        default:
            return view('dashboard');
    }
})->middleware(['auth'])->name('dashboard');

// Staff profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
