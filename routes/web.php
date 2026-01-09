<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\StatisticController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\LeaveRequestController as AdminLeaveRequestController;
use App\Http\Controllers\Staff\DashboardController as StaffDashboardController;
use App\Http\Controllers\Staff\AppointmentController as StaffAppointmentController;
use App\Http\Controllers\Staff\HairstyleController;
use App\Http\Controllers\Staff\LeaveRequestController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\AppointmentController as UserAppointmentController;
use App\Http\Controllers\User\ProductController as UserProductController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\OrderController as UserOrderController;
use App\Http\Controllers\User\ReviewController;
use App\Http\Controllers\User\StaffController as UserStaffController;
use App\Http\Controllers\User\ProfileController as UserProfileController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Staff\ProfileController as StaffProfileController;
use App\Http\Controllers\Admin\RevenueController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// User routes
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    // Appointments
    Route::get('/appointments', [UserAppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create', [UserAppointmentController::class, 'create'])->name('appointments.create');
    Route::get('/appointments/available-staffs', [UserAppointmentController::class, 'getAvailableStaffs'])->name('appointments.available-staffs');
    Route::get('/appointments/booked-times', [UserAppointmentController::class, 'getBookedTimeSlots'])->name('appointments.booked-times');
    Route::post('/appointments', [UserAppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/appointments/{appointment}', [UserAppointmentController::class, 'show'])->name('appointments.show');
    Route::put('/appointments/{appointment}', [UserAppointmentController::class, 'update'])->name('appointments.update');
    Route::delete('/appointments/{appointment}', [UserAppointmentController::class, 'destroy'])->name('appointments.destroy');
    
    // Products
    Route::get('/products', [UserProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [UserProductController::class, 'show'])->name('products.show');
    
    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    
    // Orders
    Route::get('/orders', [UserOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [UserOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders', [UserOrderController::class, 'store'])->name('orders.store');
    Route::post('/orders/{order}/cancel', [UserOrderController::class, 'cancel'])->name('orders.cancel');
    
    // Reviews
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    
    // Staff details
    Route::get('/staffs/{staff}', [UserStaffController::class, 'show'])->name('staffs.show');
    
    // Profile
    Route::get('/profile', [UserProfileController::class, 'index'])->name('profile.index');
});

// Staff routes
Route::middleware(['auth', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');
    Route::get('/appointments', [StaffAppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/{appointment}', [StaffAppointmentController::class, 'show'])->name('appointments.show');
    Route::put('/appointments/{appointment}/complete', [StaffAppointmentController::class, 'complete'])->name('appointments.complete');
    
    // Hairstyles
    Route::resource('hairstyles', HairstyleController::class)->names([
        'index' => 'hairstyles.index',
        'create' => 'hairstyles.create',
        'store' => 'hairstyles.store',
        'edit' => 'hairstyles.edit',
        'update' => 'hairstyles.update',
        'destroy' => 'hairstyles.destroy',
    ]);
    
    // Leave Requests
    Route::resource('leave-requests', LeaveRequestController::class)->names([
        'index' => 'leave-requests.index',
        'create' => 'leave-requests.create',
        'store' => 'leave-requests.store',
        'show' => 'leave-requests.show',
        'destroy' => 'leave-requests.destroy',
    ]);
    
    // Profile
    Route::get('/profile', [StaffProfileController::class, 'index'])->name('profile.index');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Services
    Route::resource('services', ServiceController::class);
    
    // Staff
    Route::resource('staffs', StaffController::class);
    Route::post('/staffs/{staff}/services', [StaffController::class, 'assignServices'])->name('staffs.assign-services');
    Route::get('/staffs/{staff}/schedule', [StaffController::class, 'schedule'])->name('staffs.schedule');
    Route::post('/staffs/{staff}/schedule', [StaffController::class, 'storeSchedule'])->name('staffs.store-schedule');
    
    // Appointments
    Route::get('/appointments', [AdminAppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/{appointment}', [AdminAppointmentController::class, 'show'])->name('appointments.show');
    Route::put('/appointments/{appointment}/confirm', [AdminAppointmentController::class, 'confirm'])->name('appointments.confirm');
    Route::put('/appointments/{appointment}/cancel', [AdminAppointmentController::class, 'cancel'])->name('appointments.cancel');
    Route::delete('/appointments/{appointment}', [AdminAppointmentController::class, 'destroy'])->name('appointments.destroy');
    
    // Invoices
    Route::get('/appointments/{appointment}/invoice', [InvoiceController::class, 'show'])->name('appointments.invoice');
    Route::post('/appointments/{appointment}/invoice/use-loyalty-points', [InvoiceController::class, 'useLoyaltyPoints'])->name('appointments.invoice.use-loyalty-points');
    Route::get('/appointments/{appointment}/invoice/download', [InvoiceController::class, 'download'])->name('appointments.invoice.download');
    
    // Products
    Route::resource('products', ProductController::class);
    Route::post('/products/import', [ProductController::class, 'import'])->name('products.import');
    
    // Orders
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/update-status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    
    // Statistics
    Route::get('/statistics', [StatisticController::class, 'index'])->name('statistics.index');
    
    // Revenue
    Route::get('/revenues', [RevenueController::class, 'index'])->name('revenues.index');
    Route::get('/revenues/export', [RevenueController::class, 'export'])->name('revenues.export');
    Route::post('/revenues/close-month', [RevenueController::class, 'closeMonth'])->name('revenues.close-month');
    
    // Profile
    Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile.index');
    
    // Leave Requests
    Route::get('/leave-requests', [AdminLeaveRequestController::class, 'index'])->name('leave-requests.index');
    Route::get('/leave-requests/{leaveRequest}', [AdminLeaveRequestController::class, 'show'])->name('leave-requests.show');
    Route::put('/leave-requests/{leaveRequest}/approve', [AdminLeaveRequestController::class, 'approve'])->name('leave-requests.approve');
    Route::put('/leave-requests/{leaveRequest}/reject', [AdminLeaveRequestController::class, 'reject'])->name('leave-requests.reject');
});
