<?php


/**
 *
 * ------------------------------------
 * Backoffice Routes
 * ------------------------------------
 *
 */
Route::group(['middleware' => "backoffice.guest", 'as' => "auth." ], function(){
    Route::get('login',['as' => "login", 'uses' => "LoginController@login"]);
    Route::post('login',['uses' => "LoginController@authenticate"]);
    Route::get('register',['as' => "register", 'uses' => "RegisterController@register"]);
    Route::post('register',['uses' => "RegisterController@authenticate"]);
    Route::get('{username}/verify',['as' => "verify", 'uses' => "RegisterController@verify"]);

    Route::get('forgot-password',['as' => "forgotPass", 'uses' => "ForgotPasswordController@forgotPass"]);
    Route::post('forgot-password',['uses' => "ForgotPasswordController@resetLink"]);

    Route::get('reset-password/{token}',['as' => "resetPass", 'uses' => "ForgotPasswordController@resetPass"]);
    Route::post('reset-password/{token}',['uses' => "ForgotPasswordController@updatePass"]);

    Route::any('auto-reply',['as' => "autoReply",'uses' => "ChatBotController@autoReply"]);
    
});

Route::group(['middleware' => ["backoffice.auth"
// , "backoffice.verifiedOnly"
]], function(){
    Route::get('logout',['as' => "logout",'uses' => "LoginController@logout"]);
    Route::get('/',['as' => "index",'uses' => "DashboardController@index"]);
    Route::any('download/{start?}/{end?}',['as' => "download",'uses' => "DashboardController@downloadReport"]);
    Route::any('viewReport',['as' => "viewReport",'uses' => "DashboardController@viewReport"]);

    Route::group(['as' => "participants.", 'prefix' => "participants"], function(){
        Route::get('/',['as' => "index", 'uses' => "ParticipantsController@index"]);
        Route::get('create',['as' => "create", 'uses' => "ParticipantsController@create"]);
        Route::post('create',['middleware' => "backoffice.superUserOnly", 'uses' => "ParticipantsController@store"]);
        Route::get('view/{id}',['as' => "view",'uses' => "ParticipantsController@view"]);
        Route::get('edit/{id}',['as' => "edit",'uses' => "ParticipantsController@edit"]);
        Route::post('edit/{id}',['as' => "update",'uses' => "ParticipantsController@update"]);
    });

    Route::group(['as' => "staffs.", 'prefix' => "staffs"], function(){
        Route::get('/',['as' => "index", 'middleware' => "backoffice.superUserOnly", 'uses' => "StaffsController@index"]);
        Route::get('create',['as' => "create", 'middleware' => "backoffice.superUserOnly", 'uses' => "StaffsController@create"]);
        Route::post('create',['middleware' => "backoffice.superUserOnly", 'uses' => "StaffsController@store"]);
        Route::get('view/{id}',['as' => "view", 'middleware' => "backoffice.superUserOnly",'uses' => "StaffsController@view"]);
        Route::get('edit/{id}',['as' => "edit", 'middleware' => "backoffice.superUserOnly",'uses' => "StaffsController@edit"]);
        Route::post('edit/{id}',['as' => "update", 'middleware' => "backoffice.superUserOnly",'uses' => "StaffsController@update"]);
        Route::get('delete/{id}',['as' => "delete", 'middleware' => "backoffice.superUserOnly",'uses' => "StaffsController@delete"]);
    });

    Route::group(['as' => "feedbacks.", 'prefix' => "feedbacks"], function(){
        Route::get('/',['as' => "index", 'uses' => "FeedbacksController@index"]);
        Route::get('create',['as' => "create", 'uses' => "FeedbacksController@create"]);
        Route::post('create',['uses' => "FeedbacksController@store"]);
        Route::get('view/{id}',['as' => "view",'uses' => "FeedbacksController@view"]);
        Route::get('edit/{id}',['as' => "edit",'uses' => "FeedbacksController@edit"]);
        Route::post('edit/{id}',['as' => "update",'uses' => "FeedbacksController@update"]);

        Route::post('add',['as' => "add",'uses' => "FeedbacksController@add"]);
    });

    Route::group(['as' => "attendance.", 'prefix' => "attendance"], function(){
        Route::get('/',['as' => "index", 'uses' => "AttendanceController@index"]);
        Route::get('participants/{id}',['as' => "participants", 'uses' => "AttendanceController@participants"]);
    });

    
    Route::group(['as' => "certificates.", 'prefix' => "certificates"], function(){
        Route::any('view/{id}',['as' => "view",'uses' => "CertificateController@view"]);
    });

    Route::group(['as' => "events.", 'prefix' => "events"], function(){
        Route::get('/',['as' => "index", 'uses' => "EventsController@index"]);
        Route::get('create',['as' => "create", 'uses' => "EventsController@create"]);
        Route::post('create',['uses' => "EventsController@store"]);
        Route::get('cancel/{id}',['as' => "cancel",'uses' => "EventsController@cancel"]);
        Route::get('view/{id}',['as' => "view",'uses' => "EventsController@view"]);
        Route::get('edit/{id}',['as' => "edit",'uses' => "EventsController@edit"]);
        Route::post('edit/{id}',['as' => "update",'uses' => "EventsController@update"]);

        Route::any('attend/{id}',['as' => "attend",'uses' => "EventsController@attend"]);

        Route::get('list',['as' => "list",'uses' => "EventsController@list"]);
        Route::get('completed',['as' => "completed",'uses' => "EventsController@completed"]);

        Route::any('update-status/{id}/{status}',['as' => "update_status",'uses' => "EventsController@updateStatus"]);

        Route::any('generate-certificate/{id}',['as' => "generate_certificate",'uses' => "CertificateController@genCert"]);
    });

    Route::group(['as' => "account.", 'prefix' => "account"], function(){
        Route::get('/',['as' => "index", 'uses' => "AccountController@index"]);
        Route::post('/',['as' => "save", 'uses' => "AccountController@save"]);
        Route::post('update-password',['as' => "update_password", 'uses' => "AccountController@updatePassword"]);
    });

});
