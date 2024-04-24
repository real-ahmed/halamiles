<?php

use Illuminate\Support\Facades\Route;


Route::namespace('Auth')->controller('LoginController')->group(function () {
    Route::get('/', 'showLoginForm')->name('login');
    Route::post('/', 'login')->name('login');
    Route::get('logout', 'logout')->name('logout');

    // Admin Password Reset
    Route::controller('ForgotPasswordController')->group(function () {
        Route::get('password/reset', 'showLinkRequestForm')->name('password.reset');
        Route::post('password/reset', 'sendResetCodeEmail');
        Route::get('password/code-verify', 'codeVerify')->name('password.code.verify');
        Route::post('password/verify-code', 'verifyCode')->name('password.verify.code');
    });

    Route::controller('ResetPasswordController')->group(function () {
        Route::get('password/reset/{token}', 'showResetForm')->name('password.reset.form');
        Route::post('password/reset/change', 'reset')->name('password.change');
    });
});

Route::middleware('admin')->group(function () {
    Route::controller('AdminController')->group(function () {
        Route::get('dashboard', 'dashboard')->name('dashboard');
        Route::get('profile', 'profile')->name('profile');
        Route::post('profile', 'profileUpdate')->name('profile.update');
        Route::get('password', 'password')->name('password');
        Route::post('password', 'passwordUpdate')->name('password.update');

        //Notification
        Route::get('notifications', 'notifications')->name('notifications');
        Route::get('notification/read/{id}', 'notificationRead')->name('notification.read');
        Route::get('notifications/read-all', 'readAll')->name('notifications.readAll');

        //Report Bugs
        Route::get('request-report', 'requestReport')->name('request.report');
        Route::post('request-report', 'reportSubmit');

        Route::get('download-attachments/{file_hash}', 'downloadAttachment')->name('download.attachment');

        Route::get('/download-csv/{table}/{columns}', 'download')->name('download.csv');
    });

    //Manage Category
    Route::controller('CategoryController')->name('category.')->prefix('category')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('save/{id?}', 'save')->name('save');
    });

    //Manage Coupon
    Route::controller('ManageCouponController')->name('coupon.')->prefix('coupon')->group(function () {
        Route::get('/all', 'allCoupons')->name('all');
        Route::get('/active', 'activeCoupons')->name('active');
        Route::get('/pending', 'pendingCoupons')->name('pending');
        Route::get('/expired', 'expiredCoupons')->name('expired');
        Route::get('/rejected', 'rejectedCoupons')->name('rejected');
        Route::get('/store/{store}', 'allCoupons')->name('store');
        Route::get('/today', 'todayDeal')->name('today');
        Route::get('/top_deal', 'topDeal')->name('topdeal');
        Route::get('save/{id?}', 'couponForm')->name('form');
        Route::post('save/{id?}', 'saveCoupon')->name('save');
        Route::post('/status', 'changeStatus')->name('status');
    });



    //Manage Product
    Route::controller('ManageCouponController')->name('product.')->prefix('product')->group(function () {
        Route::get('/all', 'allProducts')->name('all');
        Route::get('/active', 'activeProducts')->name('active');
        Route::get('/expired', 'expiredProducts')->name('expired');
        Route::get('/store/{store}', 'allProducts')->name('store');
        Route::get('/trend', 'trendProducts')->name('trend');
        Route::get('save/{id?}', 'productForm')->name('form');
        Route::post('save/{id?}', 'saveProduct')->name('save');
        Route::post('/status', 'changeStatus')->name('status');
    });

    //Manage Store
    Route::controller('ManageCouponController')->name('store.')->prefix('store')->group(function () {
        Route::get('/all', 'stores')->name('all');
        Route::get('/active', 'active')->name('active');
        Route::get('/featured', 'featured')->name('featured');
        Route::get('/list', 'storeList')->name('list');
        Route::post('/save/{id?}', 'saveStore')->name('save');
        Route::get('save/{id?}', 'storeForm')->name('form');
        Route::get('/favorite/{id}', 'userFavorite')->name('user.favorite');
    });

    //Manage Store Categories
    Route::controller('ManageCouponController')->name('store-category.')->prefix('store-category')->group(function () {
        Route::get('/all', 'categories')->name('all');
        Route::post('/save/{id?}', 'saveCategory')->name('save');
    });

    //Manage Package
    Route::controller('ManageCouponController')->name('package.')->prefix('package')->group(function () {
        Route::get('/packages', 'packages')->name('index');
        Route::post('package/save/{id?}', 'savePackage')->name('save');
    });

    //Manage Advertisement
    Route::controller('AdvertisementController')->name('advertisement.')->prefix('advertisement')->group(function () {
        Route::get('/', 'AdvertisementController@index')->name('index');
        Route::get('/create', 'AdvertisementController@create')->name('create');
        Route::post('/store', 'AdvertisementController@store')->name('store');
        Route::post('/update/{id}', 'AdvertisementController@update')->name('update');
        Route::post('/delete', 'AdvertisementController@delete')->name('delete');
    });


    // Users Manager
    Route::controller('ManageUsersController')->name('users.')->prefix('users')->group(function () {
        Route::get('/', 'allUsers')->name('all');
        Route::get('store/{storeId}', 'usersByStore')->name('by_store');
        Route::get('active', 'activeUsers')->name('active');
        Route::get('banned', 'bannedUsers')->name('banned');
        Route::get('email-verified', 'emailVerifiedUsers')->name('email.verified');
        Route::get('email-unverified', 'emailUnverifiedUsers')->name('email.unverified');
        Route::get('mobile-unverified', 'mobileUnverifiedUsers')->name('mobile.unverified');
        Route::get('mobile-verified', 'mobileVerifiedUsers')->name('mobile.verified');
        Route::get('mobile-verified', 'mobileVerifiedUsers')->name('mobile.verified');
        Route::post('reset-password/{id}', 'resetPassword')->name('reset.password');


        Route::get('detail/{id}', 'detail')->name('detail');
        Route::post('update/{id}', 'update')->name('update');
        Route::post('create', 'create')->name('create');
        Route::post('add-balance/{id}', 'addBalance')->name('add.balance');
        Route::get('send-notification/{id}', 'showNotificationSingleForm')->name('notification.single');
        Route::post('send-notification/{id}', 'sendNotificationSingle')->name('notification.single');
        Route::get('login/{id}', 'login')->name('login');
        Route::post('status/{id}', 'status')->name('status');

        Route::get('resend-verify/{type}/{id}', 'sendVerifyCode')->name('send.verify.code');

        Route::get('send-notification', 'showNotificationAllForm')->name('notification.all');
        Route::post('send-notification', 'sendNotificationAll')->name('notification.all.send');
        Route::get('notification-log/{id}', 'notificationLog')->name('notification.log');


        Route::post('/admin/users/notification/selected/send', 'sendToSelected')->name('notification.selected.send');
        Route::post('/admin/users/notification/latest-news/send', 'sendToLatestNews')->name('notification.latest-news.send');
    });


    // Referrals System
    Route::controller('ManageReferralsController')->name('referrals.')->prefix('referrals')->group(function () {

        Route::get('/', 'all')->name('all');
        Route::get('confirmed', 'confirmed')->name('confirmed');
        Route::get('pending', 'pending')->name('pending');
        Route::get('cancelled', 'cancelled')->name('cancelled');
    });



    Route::controller('ManageTransactionsController')->name('transactions.')->prefix('transactions')->group(function () {

        Route::get('/', 'all')->name('all');
        Route::get('confirmed', 'confirmed')->name('confirmed');
        Route::get('pending', 'pending')->name('pending');
        Route::get('cancelled', 'cancelled')->name('cancelled');
        Route::get('details/{id}', 'details')->name('details');
    });


    // Deposit Gateway
    Route::name('gateway.')->prefix('gateway')->group(function () {

        // Automatic Gateway
        Route::controller('AutomaticGatewayController')->group(function () {
            Route::get('automatic', 'index')->name('automatic.index');
            Route::get('automatic/edit/{alias}', 'edit')->name('automatic.edit');
            Route::post('automatic/update/{code}', 'update')->name('automatic.update');
            Route::post('automatic/remove/{id}', 'remove')->name('automatic.remove');
            Route::post('automatic/activate/{code}', 'activate')->name('automatic.activate');
            Route::post('automatic/deactivate/{code}', 'deactivate')->name('automatic.deactivate');
        });


        // Manual Methods
        Route::controller('ManualGatewayController')->group(function () {
            Route::get('manual', 'index')->name('manual.index');
            Route::get('manual/new', 'create')->name('manual.create');
            Route::post('manual/new', 'store')->name('manual.store');
            Route::get('manual/edit/{alias}', 'edit')->name('manual.edit');
            Route::post('manual/update/{id}', 'update')->name('manual.update');
            Route::post('manual/activate/{code}', 'activate')->name('manual.activate');
            Route::post('manual/deactivate/{code}', 'deactivate')->name('manual.deactivate');
        });
    });


    Route::name('withdraw-method.')->controller('WithdrawMethodController')->prefix('withdraw-method')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('new', 'create')->name('create');
        Route::post('new', 'store')->name('store');
        Route::get('edit/{alias}', 'edit')->name('edit');
        Route::post('update/{id}', 'update')->name('update');
        Route::post('activate/{code}', 'activate')->name('activate');
        Route::post('deactivate/{code}', 'deactivate')->name('deactivate');
    });

    // Withdraw SYSTEM
    Route::name('withdraw.')->controller('WithdrawalController')->prefix('withdraw')->group(function () {
        Route::get('/', 'withdraw')->name('list');
        Route::get('pending', 'pending')->name('pending');
        Route::get('rejected', 'rejected')->name('rejected');
        Route::get('approved', 'approved')->name('approved');
        Route::get('successful', 'successful')->name('successful');
        Route::get('initiated', 'initiated')->name('initiated');
        Route::get('details/{id}', 'details')->name('details');

        Route::post('reject', 'reject')->name('reject');
        Route::post('approve/{id}', 'approve')->name('approve');
    });


    Route::name('claims.')->controller('ManageClaimController')->prefix('claims')->group(function () {
        Route::get('/', 'claims')->name('list');
        Route::get('pending', 'pending')->name('pending');
        Route::get('rejected', 'rejected')->name('rejected');
        Route::get('approved', 'approved')->name('approved');
        Route::get('successful', 'successful')->name('successful');
        Route::get('initiated', 'initiated')->name('initiated');
        Route::get('details/{id}', 'details')->name('details');

        Route::post('reject', 'reject')->name('reject');
        Route::post('approve/{id}', 'approve')->name('approve');
    });


    Route::name('deposit.')->controller('DepositController')->prefix('deposit')->group(function () {
        Route::get('/', 'deposit')->name('list');
        Route::get('pending', 'pending')->name('pending');
        Route::get('rejected', 'rejected')->name('rejected');
        Route::get('approved', 'approved')->name('approved');
        Route::get('successful', 'successful')->name('successful');
        Route::get('initiated', 'initiated')->name('initiated');
        Route::get('details/{id}', 'details')->name('details');

        Route::post('reject', 'reject')->name('reject');
        Route::post('approve/{id}', 'approve')->name('approve');
    });

    // Report
    Route::controller('ReportController')->group(function () {
        Route::get('report/login/history', 'loginHistory')->name('report.login.history');
        Route::get('report/login/ipHistory/{ip}', 'loginIpHistory')->name('report.login.ipHistory');
        Route::get('report/notification/history', 'notificationHistory')->name('report.notification.history');
        Route::get('report/email/detail/{id}', 'emailDetails')->name('report.email.details');
    });


    // Admin Support
    Route::controller('SupportTicketController')->group(function () {
        Route::get('tickets', 'tickets')->name('ticket');
        Route::get('tickets/pending', 'pendingTicket')->name('ticket.pending');
        Route::get('tickets/closed', 'closedTicket')->name('ticket.closed');
        Route::get('tickets/answered', 'answeredTicket')->name('ticket.answered');
        Route::get('tickets/view/{id}', 'ticketReply')->name('ticket.view');
        Route::post('ticket/reply/{id}', 'replyTicket')->name('ticket.reply');
        Route::post('ticket/close/{id}', 'closeTicket')->name('ticket.close');
        Route::get('ticket/download/{ticket}', 'ticketDownload')->name('ticket.download');
        Route::post('ticket/delete/{id}', 'ticketDelete')->name('ticket.delete');
    });


    // Language Manager
    Route::controller('LanguageController')->group(function () {
        Route::get('/language', 'langManage')->name('language.manage');
        Route::post('/language', 'langStore')->name('language.manage.store');
        Route::post('/language/delete/{id}', 'langDelete')->name('language.manage.delete');
        Route::post('/language/update/{id}', 'langUpdate')->name('language.manage.update');
        Route::get('/language/edit/{id}', 'langEdit')->name('language.key');
        Route::post('/language/import', 'langImport')->name('language.import.lang');
        Route::post('language/store/key/{id}', 'storeLanguageJson')->name('language.store.key');
        Route::post('language/delete/key/{id}', 'deleteLanguageJson')->name('language.delete.key');
        Route::post('language/update/key/{id}', 'updateLanguageJson')->name('language.update.key');
    });

    Route::controller('GeneralSettingController')->group(function () {
        // General Setting
        Route::get('general-setting', 'index')->name('setting.index');
        Route::post('general-setting', 'update')->name('setting.update');

        // Logo-Icon
        Route::get('setting/logo-icon', 'logoIcon')->name('setting.logo.icon');
        Route::post('setting/logo-icon', 'logoIconUpdate')->name('setting.logo.icon');

        //Custom CSS
        Route::get('custom-css', 'customCss')->name('setting.custom.css');
        Route::post('custom-css', 'customCssSubmit');


        //Cookie
        Route::get('cookie', 'cookie')->name('setting.cookie');
        Route::post('cookie', 'cookieSubmit');


        //maintenance_mode
        Route::get('maintenance-mode', 'maintenanceMode')->name('maintenance.mode');
        Route::post('maintenance-mode', 'maintenanceModeSubmit');
    });

    //Notification Setting
    Route::name('setting.notification.')->controller('NotificationController')->prefix('notification')->group(function () {
        //Template Setting
        Route::get('global', 'global')->name('global');
        Route::post('global/update', 'globalUpdate')->name('global.update');
        Route::get('templates', 'templates')->name('templates');
        Route::get('template/edit/{id}', 'templateEdit')->name('template.edit');
        Route::post('template/update/{id}', 'templateUpdate')->name('template.update');

        //Email Setting
        Route::get('email/setting', 'emailSetting')->name('email');
        Route::post('email/setting', 'emailSettingUpdate');
        Route::post('email/test', 'emailTest')->name('email.test');

        //SMS Setting
        Route::get('sms/setting', 'smsSetting')->name('sms');
        Route::post('sms/setting', 'smsSettingUpdate');
        Route::post('sms/test', 'smsTest')->name('sms.test');
    });

    // Plugin
    Route::controller('ExtensionController')->group(function () {
        Route::get('extensions', 'index')->name('extensions.index');
        Route::post('extensions/update/{id}', 'update')->name('extensions.update');
        Route::post('extensions/status/{id}', 'status')->name('extensions.status');
    });


    //System Information
    Route::controller('SystemController')->name('system.')->prefix('system')->group(function () {
        Route::get('info', 'systemInfo')->name('info');
        Route::get('server-info', 'systemServerInfo')->name('server.info');
        Route::get('optimize', 'optimize')->name('optimize');
        Route::get('optimize-clear', 'optimizeClear')->name('optimize.clear');
    });


    // SEO
    Route::get('seo', 'FrontendController@seoEdit')->name('seo');


    // Frontend
    Route::name('frontend.')->prefix('frontend')->group(function () {

        Route::controller('FrontendController')->group(function () {
            Route::get('templates', 'templates')->name('templates');
            Route::post('templates', 'templatesActive')->name('templates.active');
            Route::get('frontend-sections/{key}', 'frontendSections')->name('sections');
            Route::get('banners', 'banners')->name('banners');
            Route::post('banners/save/{id?}', 'saveBanner')->name('banners.save');
            Route::get('banners/delete/{id?}', 'deleteBanner')->name('banners.delete');
            Route::post('frontend-content/{key}', 'frontendContent')->name('sections.content');
            Route::get('frontend-element/{key}/{id?}', 'frontendElement')->name('sections.element');
            Route::post('remove/{id}', 'remove')->name('remove');
        });

        // Page Builder
        Route::controller('PageBuilderController')->group(function () {
            Route::get('manage-pages', 'managePages')->name('manage.pages');
            Route::post('manage-pages', 'managePagesSave')->name('manage.pages.save');
            Route::post('manage-pages/update', 'managePagesUpdate')->name('manage.pages.update');
            Route::post('manage-pages/delete/{id}', 'managePagesDelete')->name('manage.pages.delete');
            Route::get('manage-section/{id}', 'manageSection')->name('manage.section');
            Route::post('manage-section/{id}', 'manageSectionUpdate')->name('manage.section.update');
        });
    });
});
