<?php
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\DashboardController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController as AuthLoginController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\LeadsController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\privacyPolicyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QrController;
use App\Http\Controllers\SocialLinksController;
use App\Http\Controllers\SocialQrController;
use App\Http\Controllers\Users\MasterController;
use Illuminate\Support\Facades\Route;

// user info
Route::middleware('guest')->group(function () {
    Route::get('register', [AuthLoginController::class, 'Register'])->name('register-data');
    Route::post('register', [AuthLoginController::class, 'SubmitRegister'])->name('submit_register');
    Route::get('/', [AuthLoginController::class, 'Login'])->name('login-data');
    Route::post('/', [AuthLoginController::class, 'SubmitLogin'])->name('login');
    Route::get('forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
    Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');
});

Route::middleware(['auth', 'user'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'Dashboard'])->name('dashboard');

    // profile page
    Route::get('user-profile', [DashboardController::class, 'UserProfile'])->name('user-profile');
    Route::post('submit-profile-update', [DashboardController::class, 'submitProfileUpdate'])->name('submit-profile-update');
    Route::get('submit-profile', [DashboardController::class, 'profile'])->name('profile-remove-image');

    // user google mail send
    Route::get('connect-gmail', [GoogleController::class, 'redirect']);
    Route::get('google/callback', [GoogleController::class, 'callback']);

    // master page
    Route::get('masterList', [MasterController::class, 'masterDataList'])->name('master-data-list');
    Route::post('sequences/inline-update', [MasterController::class, 'inlineUpdate'])->name('master-list-sequences-inlineUpdate');
    Route::post('sequences/data', [MasterController::class, 'getSequencesList'])->name('getSequences-data');
    Route::get('master', [MasterController::class, 'masterViewPage'])->name('master-view-page');
    Route::get('sequenceslist/edit/{id}', [MasterController::class, 'sequencesListEdit'])->name('sequences-list-edit');
    Route::post('sequences/{id}/update', [MasterController::class, 'sequencesListUpdate'])->name('sequences-list-update');

    // BusinessLinks page
    Route::get('Link', [MasterController::class, 'masterLinkDocument'])->name('master-link-document');
    Route::post('BusinessLinks', [MasterController::class, 'submitBusinessLinks'])->name('submit-business-links');
    Route::get('admin/business-links', [MasterController::class, 'getBusinessLinks'])->name('user-business-links');
    Route::post('sequences/store', [MasterController::class, 'sequencesStore'])->name('user-sequences-store');

    Route::get('Leads', [LeadsController::class, 'index'])->name('leads-index');
    Route::post('leads/store', [LeadsController::class, 'leadStore'])->name('lead-store');
    Route::get('leads/list', [LeadsController::class, 'leadList'])->name('lead-list');
    Route::PUT('leads/{id}', [LeadsController::class, 'leadsUpdate'])->name('leads-update');
    Route::post('leads/bulk-upload', [LeadsController::class, 'bulkLeadsUpload'])->name('bulk-leads-upload');
    Route::get('download-leads-excel',[LeadsController::class, 'downloadDemo'])->name('download-leads-demo');
    Route::delete('leads/{id}', [LeadsController::class, 'destroy'])->name('leads-destroy');

    // social links
    Route::post('social/links', [SocialLinksController::class, 'store'])->name('user.social.links.store');
    Route::get('user-social-links', [SocialLinksController::class, 'index'])->name('user-social-links');


    Route::post('social/update/{id}', [SocialLinksController::class, 'update'])->name('user.social.update');
    Route::post('social/quick/update', [SocialLinksController::class, 'updateQuickLink'])->name('user.social.quick.update');



    Route::get('social/print', [SocialLinksController::class, 'printBusinessCard'])->name('user.social.print');
    // Route::delete('/social/destroy/{id}',[SocialLinksController::class, 'destroy'])->name('user-social-links-destroy');


    Route::post('/social/update/{id}', [SocialLinksController::class, 'update'])->name('user.social.update');
    Route::delete('/social/delete/{id}', [SocialLinksController::class, 'destroy'])->name('user.social.delete');


    //tracking QR generation
    Route::post('save-multiple-qr', [SocialLinksController::class, 'saveMultipleQR'])->name('save.multiple.qr');
    Route::post('update-multi-qr', [SocialLinksController::class, 'updateMultiQR'])->name('update-multi-qr');
    Route::post('multi-qr/update-title', [SocialLinksController::class, 'updateMultiQRTitle'])->name('update-multi-qr-title');
});

    Route::get('multi-qr/{user}/{qrId}', [SocialLinksController::class, 'showMultiQR'])->name('show.multi.qr');
    Route::get('track-multi-qr-click', [SocialLinksController::class, 'trackMultiQRClick'])->name('track-multi-qr-click');
// admin info
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('dashboard', [AdminDashboardController::class, 'AdminDashboard'])->name('admin-dashboard');
    Route::get('profile', [AdminDashboardController::class, 'adminProfile'])->name('admin-profile');
    Route::post('admin-submit', [AdminDashboardController::class, 'adminUpdateDataUpdate'])->name('admin-submit-profile-update');
    Route::post('admin-users-data', [UserController::class, 'data'])->name('admin-users-data');
    Route::post('admin-users-store', [UserController::class, 'store'])->name('admin-users-store');
    Route::get('admin-users-total', [UserController::class, 'totalUsers'])->name('admin-users-total');
    Route::put('admin-users-update', [UserController::class, 'update'])->name('admin-users-update');
    Route::get('admin-users-edit/{id}', [UserController::class, 'editUserData'])->name('admin-users-edit');
    Route::post('admin-update-users', [UserController::class, 'updateUserData'])->name('admin-update-user-data');
    Route::post('admin-users-toggle-status', [UserController::class, 'toggleStatus'])->name('admin-users-toggle-status');
    Route::delete('users-destroy', [UserController::class, 'destroy'])->name('admin-users-destroy');
    Route::get('users-export', [UserController::class, 'export'])->name('admin-users-export');
    Route::get('users-print', [UserController::class, 'printCard'])->name('admin-users-print');
    Route::get('users', [UserController::class, 'index'])->name('admin-users-index');

    Route::get('/social-links', [SocialLinksController::class, 'index'])->name('social.links');
    Route::post('/social/links', [SocialLinksController::class, 'store'])->name('social.links.store');
    Route::post('/social/update/{id}', [SocialLinksController::class, 'update'])->name('social-links-update');
    Route::post('/social/quick/update', [SocialLinksController::class, 'updateQuickLink'])->name('social.quick.update');
    Route::get('/social/print', [SocialLinksController::class, 'printBusinessCard'])->name('social.print');

});

Route::get('lead-response/{log}/{status}',[CampaignController::class, 'leadResponse'])->name('lead-response');
Route::get('track-open/{logId}', [CampaignController::class, 'trackOpen'])->name('track-open');

Route::get('/profile/{slug}', [ProfileController::class, 'show'])->name('profile.show');
Route::post('/profile/{slug}/track-click', [ProfileController::class, 'trackClick'])->name('profile.track.click');

Route::post('logout', [AuthLoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::get('privacy-policy', [privacyPolicyController::class, 'privacyPolicy']);
Route::get('terms', [privacyPolicyController::class, 'terms']);
Route::get('landing-page', [privacyPolicyController::class, 'landingPage']);








