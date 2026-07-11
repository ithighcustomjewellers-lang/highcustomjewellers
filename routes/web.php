<?php
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminMasterController;
use App\Http\Controllers\Admin\AdminSocialLinksController;
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
use App\Http\Controllers\ReportController;

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
    Route::get('/dashboard/chart-data', [DashboardController::class, 'chartData'])->name('dashboard-chart-data');
    Route::get('/dashboard/platform-click-chart', [DashboardController::class, 'platformClickChart'])->name('dashboard-platform-click-chart');

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
    Route::post('/sequence/delete', [SocialLinksController::class, 'deleteSequence'])->name('sequence-delete');


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
    Route::post('social/update/{id}', [SocialLinksController::class, 'update'])->name('user-social-links-update');
    Route::post('social/quick/update', [SocialLinksController::class, 'updateQuickLink'])->name('user.social.quick.update');
    Route::post('social/update-secondary', [SocialLinksController::class, 'updateSecondary'])->name('user-social-links-update-secondary');
    Route::get('social/print', [SocialLinksController::class, 'printBusinessCard'])->name('user.social.print');

    //tracking QR generation
    Route::post('save-multiple-qr', [SocialLinksController::class, 'saveMultipleQR'])->name('save.multiple.qr');
    Route::post('update-multi-qr', [SocialLinksController::class, 'updateMultiQR'])->name('update-multi-qr');
    Route::post('multi-qr/update-title', [SocialLinksController::class, 'updateMultiQRTitle'])->name('update-multi-qr-title');
    Route::delete('multi-qr/{id}', [SocialLinksController::class, 'multiQrDestroy'])->name('multi-qr-destroy');
    Route::get('/multi-qr/list', [SocialLinksController::class, 'getMultiQrCodes'])->name('get-multi-qr-codes');
    Route::delete('user-social-links-destroy/{id}', [SocialLinksController::class, 'userSocialLinksDestroy'])->name('user-social-links-destroy');
    Route::get('/reports/campaign', [ReportController::class, 'index'])->name('report.campaign');
    Route::post('/reports/campaign-data', [ReportController::class, 'getCampaignLogsData'])->name('report.campaign.data');

    Route::get('/report/campaign/export-csv', [ReportController::class, 'exportCsv'])->name('report.campaign.export.csv');

    // Check admin updates (polling)
    Route::get('check-admin-updates', [MasterController::class, 'checkAdminUpdates'])->name('check-admin-sequences-updates');
    Route::get('/user/sequences', [MasterController::class, 'getUserSequences']);

});

Route::get('multi-qr/{user}/{qrId}', [SocialLinksController::class, 'showMultiQR'])->name('show-multi-qr');
Route::get('track-multi-qr-click', [SocialLinksController::class, 'trackMultiQRClick'])->name('track-multi-qr-click');
Route::get('/track/click/{token}', [ReportController::class, 'trackClick'])->name('track.click');

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

    // Route::post('/social/update/{id}', [SocialLinksController::class, 'update'])->name('social-links-update');
    Route::get('/social-links', [SocialLinksController::class, 'index'])->name('social.links');
    Route::post('social/links', [SocialLinksController::class, 'store'])->name('admin.social.links.store');
    Route::post('/social/quick/update', [SocialLinksController::class, 'updateQuickLink'])->name('admin.social.quick.update');
    Route::post('social/update-secondary', [SocialLinksController::class, 'updateSecondary'])->name('admin-social-links-update-secondary');
    Route::get('/social/print', [SocialLinksController::class, 'printBusinessCard'])->name('social.print');

    //tracking QR generation
    Route::delete('multi-qr/{id}', [SocialLinksController::class, 'multiQrDestroy'])->name('admin-multi-qr-destroy');
    Route::post('admin-update-multi-qr', [SocialLinksController::class, 'updateMultiQR'])->name('admin-update-multi-qr');
    Route::post('admin-save-multiple-qr', [SocialLinksController::class, 'saveMultipleQR'])->name('admin.save.multiple.qr');
    Route::get('admin-multi-qr/list', [SocialLinksController::class, 'getMultiQrCodes'])->name('admin-get-multi-qr-codes');
    Route::post('multi-qr/update-title', [SocialLinksController::class, 'updateMultiQRTitle'])->name('admin-update-multi-qr-title');
    Route::delete('user-social-links-destroy/{id}', [SocialLinksController::class, 'userSocialLinksDestroy'])->name('admin-social-links-destroy');

    Route::get('userSequenceList', [AdminMasterController::class, 'userSequenceList'])->name('user-sequence-list');
    Route::post('userSequenceData', [AdminMasterController::class, 'userSequenceData'])->name('user-sequence-data-list');

    // user master
    Route::get('master-List', [AdminMasterController::class, 'userMasterList'])->name('admin-usersMaster-index');
    Route::post('masterDataList', [AdminMasterController::class, 'userMasterDataList'])->name('admin-master-data-list');
    Route::post('sequences/inline-update', [AdminMasterController::class, 'userMasterinlineUpdate'])->name('user-master-list-sequences-inlineUpdate');
    Route::get('userMastersequencesListEdit/edit/{id}', [AdminMasterController::class, 'userMastersequencesListEdit'])->name('user-master-sequences-list-edit');
    Route::post('sequences/{id}/update', [AdminMasterController::class, 'userMasterSequencesListUpdate'])->name('user-master-sequences-list-update');
    Route::post('userMasterSequence/delete', [AdminMasterController::class, 'userMasterSequenceDelete'])->name('user-master-sequence-delete');

    // admin BusinessLinks page
    Route::get('Link', [MasterController::class, 'masterLinkDocument'])->name('admin-master-link-document');
    Route::post('BusinessLinks', [MasterController::class, 'submitBusinessLinks'])->name('admin-submit-business-links');

    // master
    Route::get('MasterSequence', [AdminMasterController::class, 'MasterSequence'])->name('admin-Master-index');
    Route::get('admin/business-links', [AdminMasterController::class, 'getAdminBusinessLinks'])->name('admin-user-business-links');
    Route::post('sequences/store', [AdminMasterController::class, 'adminSequencesStore'])->name('admin-user-sequences-store');

    // master list
    Route::get('masterList', [MasterController::class, 'masterDataList'])->name('admin-sequenceTable-index');
    Route::post('sequences/data', [AdminMasterController::class, 'adminaGetSequencesList'])->name('admin-getSequences-data');
    Route::post('master-sequences/inline-update', [MasterController::class, 'inlineUpdate'])->name('admin-master-list-sequences-inlineUpdate');
    Route::post('/sequence/delete', [SocialLinksController::class, 'deleteSequence'])->name('admin-sequence-delete');
    Route::get('sequenceslist/edit/{id}', [MasterController::class, 'sequencesListEdit'])->name('admin-sequences-list-edit');
    Route::post('master-sequences/{id}/update', [MasterController::class, 'sequencesListUpdate'])->name('admin-sequences-list-update');

    // admin leads
    Route::get('Leads', [LeadsController::class, 'index'])->name('admin-leads-index');
    Route::post('leads/store', [LeadsController::class, 'leadStore'])->name('admin-lead-store');
    Route::PUT('leads/{id}', [LeadsController::class, 'leadsUpdate'])->name('admin-leads-update');
    Route::get('leads/list', [LeadsController::class, 'leadList'])->name('admin-lead-list');
    Route::delete('leads/{id}', [LeadsController::class, 'destroy'])->name('admin-leads-destroy');

    // admin tracking
    Route::get('/reports/campaign', [ReportController::class, 'index'])->name('admin-report.campaign');
    Route::post('/reports/campaign-data', [ReportController::class, 'getCampaignLogsData'])->name('admin-report.campaign.data');
});

Route::get('lead-response/{log}/{status}',[CampaignController::class, 'leadResponse'])->name('lead-response');
Route::get('track-open/{logId}', [CampaignController::class, 'trackOpen'])->name('track-open');

Route::get('/profile/{slug}', [ProfileController::class, 'show'])->name('profile.show');
Route::post('/profile/{slug}/track-click', [ProfileController::class, 'trackClick'])->name('profile.track.click');

Route::post('logout', [AuthLoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::get('privacy-policy', [privacyPolicyController::class, 'privacyPolicy'])->name('privacyPolicy');
Route::get('terms', [privacyPolicyController::class, 'terms'])->name('terms');
Route::get('landing-page', [privacyPolicyController::class, 'landingPage'])->name('landingPage');








