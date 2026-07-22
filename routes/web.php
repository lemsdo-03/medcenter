<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\ComplaintController;
use App\Http\Controllers\Admin\RatingController;
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
use App\Http\Controllers\Patient\ChatbotController as PatientChatbotController;
use App\Http\Controllers\Patient\RatingController as PatientRatingController;
use App\Http\Controllers\Pharmacist\DashboardController as PharmacistDashboardController;
use App\Http\Controllers\Pharmacist\CategoryController as PharmacistCategoryController;
use App\Http\Controllers\Pharmacist\MedicineController as PharmacistMedicineController;
use App\Http\Controllers\Pharmacist\PrescriptionController as PharmacistPrescriptionController;
use App\Http\Controllers\Pharmacist\DispenseController as PharmacistDispenseController;
use App\Http\Controllers\Pharmacist\MedicineAdjustmentController as PharmacistAdjustmentController;
use App\Http\Controllers\Pharmacist\ReportController as PharmacistReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/


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

    // Complaints (FR-69: managed separately from ratings)
    Route::get('/complaints', [ComplaintController::class, 'index'])->name('complaints.index');
    Route::get('/complaints/{rating}', [ComplaintController::class, 'show'])->name('complaints.show');

    // Ratings (FR-69: managed separately from complaints)
    Route::get('/ratings', [RatingController::class, 'index'])->name('ratings.index');
    Route::get('/ratings/{rating}', [RatingController::class, 'show'])->name('ratings.show');
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

// Pharmacist routes
Route::middleware(['auth', 'role:pharmacist'])->prefix('pharmacist')->name('pharmacist.')->group(function () {
    Route::get('/dashboard', [PharmacistDashboardController::class, 'index'])->name('dashboard');

    // Categories (FR-37, FR-39, FR-50)
    Route::get('/categories', [PharmacistCategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [PharmacistCategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [PharmacistCategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [PharmacistCategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [PharmacistCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [PharmacistCategoryController::class, 'destroy'])->name('categories.destroy');

    // Medicines / inventory (FR-38, FR-40, FR-41, FR-45, FR-50)
    Route::get('/medicines', [PharmacistMedicineController::class, 'index'])->name('medicines.index');
    Route::get('/medicines/create', [PharmacistMedicineController::class, 'create'])->name('medicines.create');
    Route::post('/medicines', [PharmacistMedicineController::class, 'store'])->name('medicines.store');
    Route::get('/medicines/{medicine}/edit', [PharmacistMedicineController::class, 'edit'])->name('medicines.edit');
    Route::put('/medicines/{medicine}', [PharmacistMedicineController::class, 'update'])->name('medicines.update');

    // Prescriptions received from doctors (FR-42, FR-47)
    Route::get('/prescriptions', [PharmacistPrescriptionController::class, 'index'])->name('prescriptions.index');
    Route::get('/prescriptions/{prescription}', [PharmacistPrescriptionController::class, 'show'])->name('prescriptions.show');

    // Dispense + invoice (FR-43, FR-48)
    Route::get('/prescriptions/{prescription}/dispense', [PharmacistDispenseController::class, 'create'])->name('dispenses.create');
    Route::post('/prescriptions/{prescription}/dispense', [PharmacistDispenseController::class, 'store'])->name('dispenses.store');
    Route::get('/invoices/{dispense}', [PharmacistDispenseController::class, 'invoice'])->name('dispenses.invoice');

    // Damaged / returned medicines (FR-49)
    Route::get('/adjustments', [PharmacistAdjustmentController::class, 'index'])->name('adjustments.index');
    Route::get('/adjustments/create', [PharmacistAdjustmentController::class, 'create'])->name('adjustments.create');
    Route::post('/adjustments', [PharmacistAdjustmentController::class, 'store'])->name('adjustments.store');

    // Reports (FR-46, FR-51)
    Route::get('/reports/daily', [PharmacistReportController::class, 'daily'])->name('reports.daily');
    Route::get('/reports/monthly', [PharmacistReportController::class, 'monthly'])->name('reports.monthly');
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

    // Chatbot assistant (FR-52..FR-61)
    Route::get('/chatbot', [PatientChatbotController::class, 'index'])->name('chatbot.index');
    Route::post('/chatbot/reply', [PatientChatbotController::class, 'reply'])->name('chatbot.reply');
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
        case 'pharmacist':
            return redirect()->route('pharmacist.dashboard');
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
