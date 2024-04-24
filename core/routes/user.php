<?php



use Illuminate\Support\Facades\Route;



Route::namespace('User\Auth')->name('user.')->group(function () {



    Route::controller('LoginController')->group(function(){

        Route::get('/login', 'showLoginForm')->name('login');

        Route::post('/login', 'login');

        Route::get('logout', 'logout')->name('logout');

    });



    Route::controller('RegisterController')->group(function(){

        Route::get('register', 'showRegistrationForm')->name('register');

        Route::post('register', 'register')->middleware('registration.status');

        Route::post('check-mail', 'checkUser')->name('checkUser');

    });



    Route::controller('ForgotPasswordController')->group(function(){

        Route::get('password/reset', 'showLinkRequestForm')->name('password.request');

        Route::post('password/email', 'sendResetCodeEmail')->name('password.email');

        Route::get('password/code-verify', 'codeVerify')->name('password.code.verify');

        Route::post('password/verify-code', 'verifyCode')->name('password.verify.code');

    });

    Route::controller('ResetPasswordController')->group(function(){

        Route::post('password/reset', 'reset')->name('password.update');

        Route::get('password/reset/{token}', 'showResetForm')->name('password.reset');

    });

});



Route::middleware('auth')->name('user.')->group(function () {

    //authorization

    Route::namespace('User')->controller('AuthorizationController')->group(function(){

        Route::get('authorization', 'authorizeForm')->name('authorization');

        Route::get('resend-verify/{type}', 'sendVerifyCode')->name('send.verify.code');

        Route::post('verify-email', 'emailVerification')->name('verify.email');

        Route::post('verify-mobile', 'mobileVerification')->name('verify.mobile');

    });



    Route::middleware(['check.status'])->group(function () {



        Route::get('user-data', 'User\UserController@userData')->name('data');

        Route::post('user-data-submit', 'User\UserController@userDataSubmit')->name('data.submit');



        Route::middleware('registration.complete')->namespace('User')->group(function () {



            Route::controller('UserController')->group(function(){

                Route::get('dashboard', 'home')->name('home');

                Route::get('referrals', 'referrals')->name('referrals');

                Route::get('favorite/stores', 'favoriteStores')->name('favorite.stores');



                //Report

                // Route::any('payment/history', 'depositHistory')->name('deposit.history');


                Route::any('withdraw/history', 'withdrawHistory')->name('withdraw.history');




                Route::get('attachment-download/{fil_hash}','attachmentDownload')->name('attachment.download');

            });



            //Profile setting

            Route::controller('ProfileController')->group(function(){

                Route::get('profile-setting', 'profile')->name('profile.setting');

                Route::post('profile-setting', 'submitProfile');

                Route::get('change-password', 'changePassword')->name('change.password');

                Route::post('change-password', 'submitPassword');

            });



            //Clicks
            Route::controller('Clicks')->prefix('clicks')->name('clicks.')->group(function(){

                Route::get('history', 'history')->name('history');
                Route::get('claim/{id}', 'claim')->name('claim');
                Route::post('claim/confirm', 'claimConfirm')->name('claim.confirm');
                Route::post('claim/save', 'saveClaim')->name('claim.save');
                Route::get('claims', 'claims')->name('claims');



            });



            //CouponController

            Route::controller('CashbackController')->prefix('cashbacks')->name('cashbacks.')->group(function(){

                Route::get('all', 'allCashbacks')->name('all');

                Route::get('pending', 'pendingCashbacks')->name('pending');

                Route::get('confirmed', 'confirmedCashbacks')->name('confirmed');

                Route::get('cancelled', 'cancelledCashbacks')->name('cancelled');


                Route::get('points/pending', 'pendingPoints')->name('points.pending');

                Route::get('points/confirmed', 'confirmedPoints')->name('points.confirmed');

                Route::get('points/cancelled', 'cancelledPoints')->name('points.cancelled');


            });




            Route::controller('ReferralController')->prefix('referral')->name('referral.')->group(function(){

                Route::get('all', 'allReferral')->name('all');

                Route::get('pending', 'pendingReferral')->name('pending');

                Route::get('confirmed', 'confirmedReferral')->name('confirmed');

                Route::get('cancelled', 'cancelledReferral')->name('cancelled');
            });

            Route::controller('CouponController')->prefix('store')->name('store.')->group(function(){

                Route::get('all', 'stores')->name('index');

                Route::post('save/{id?}', 'saveStore')->name('save');

            });

        });



        // Payment

        Route::middleware('registration.complete')->controller('Gateway\PaymentController')->group(function(){

            Route::any('/payment', 'deposit')->name('deposit');

            Route::post('payment/insert', 'depositInsert')->name('deposit.insert');

            Route::get('payment/confirm', 'depositConfirm')->name('deposit.confirm');

            Route::get('payment/manual', 'manualDepositConfirm')->name('deposit.manual.confirm');

            Route::post('payment/manual', 'manualDepositUpdate')->name('deposit.manual.update');

        });

        // withdrawal

        Route::middleware('registration.complete')->controller('User\WithdrawController')->group(function(){

            Route::any('/withdraw', 'withdraw')->name('withdraw');

            Route::any('withdraw/info', 'withdrawInfo')->name('withdraw.insert');

            Route::any('withdraw/verification', 'withdrawVerification')->name('withdraw.verification');

            Route::any('withdraw/resend-code', 'sendVerifyCode')->name('withdraw.sendVerifyCode');

            Route::any('withdraw/confirm', 'withdrawConfirm')->name('withdraw.confirm');


        });

    });

});

