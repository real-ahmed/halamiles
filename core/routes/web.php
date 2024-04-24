<?php

use App\Lib\Router;
use Illuminate\Support\Facades\Route;

Route::get('/clear', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});

// User Support Ticket
Route::controller('TicketController')->prefix('ticket')->group(function () {
    Route::get('/', 'supportTicket')->name('ticket');
    Route::get('/new', 'openSupportTicket')->name('ticket.open');
    Route::post('/create', 'storeSupportTicket')->name('ticket.store');
    Route::get('/view/{ticket}', 'viewTicket')->name('ticket.view');
    Route::post('/reply/{ticket}', 'replyTicket')->name('ticket.reply');
    Route::post('/close/{ticket}', 'closeTicket')->name('ticket.close');
    Route::get('/download/{ticket}', 'ticketDownload')->name('ticket.download');
});

Route::get('app/deposit/confirm/{hash}', 'Gateway\PaymentController@appDepositConfirm')->name('deposit.app.confirm');


Route::controller('SiteController')->group(function () {
    Route::get('coupons/', 'search')->name('coupon.search');
    Route::get('coupons/{type?}/{id?}', 'search')->name('coupon.filter.type');

    Route::post('coupon/click', 'saveCouponView')->name('coupon.view.save');
    Route::post('coupon/copy', 'saveCouponCopy')->name('coupon.copy.save');
    Route::get('/redirect-to-coupon/{couponId}', 'redirectToCoupon')->name('redirect.coupon');
    Route::get('/get-stores/{category_id}', 'getStoresByCategory')->name('category.get.stores');


    Route::get('products/{filterType?}', 'products')->name('products');
    Route::get('/redirect-to-product/{productId}', 'redirectToProduct')->name('redirect.product');

    Route::get('popular-stores', 'popularStore')->name('popular.stores');
    Route::get('stores/', 'stores')->name('stores');
    Route::get('stores/category/{id}', 'stores')->name('stores.category');
    Route::get('store/{id}', 'store')->name('store');

    Route::post('saveFavorite', 'savefavorite')->name('saveFavorite');

    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'contactSubmit');
    Route::get('/change/{lang?}', 'changeLanguage')->name('lang');
    Route::get('adRedirect/{id}', 'adRedirect')->name('adRedirect');
    Route::get('cookie-policy', 'cookiePolicy')->name('cookie.policy');

    Route::get('/cookie/accept', 'cookieAccept')->name('cookie.accept');

    Route::get('/blogs', 'blogs')->name('blog');
    Route::get('blog/{slug}/{id}', 'blogDetails')->name('blog.details');

    Route::get('policy/{slug}/{id}', 'policyPages')->name('policy.pages');

    Route::get('placeholder-image/{size}', 'placeholderImage')->name('placeholder.image');

    Route::get('/redirect-to-store/{storeId}', 'redirectToStore')->name('redirect.store');

    Route::get('/redirect-to-store-category/{storeId}', 'redirectToCategory')->name('redirect.category');

    Route::get('/{slug}', 'pages')->name('pages');
    Route::get('/', 'index')->name('home');
});
