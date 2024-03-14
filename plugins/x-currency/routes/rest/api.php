<?php

use XCurrency\App\Http\Controllers\CurrencyController;
use XCurrency\App\Http\Controllers\GeoIpController;
use XCurrency\App\Http\Controllers\NoticeController;
use XCurrency\App\Http\Controllers\RateController;
use XCurrency\App\Http\Controllers\SettingController;
use XCurrency\App\Http\Controllers\SwitcherController;
use XCurrency\WpMVC\Routing\Route;

Route::group(
    '', function() {
        Route::get( 'currencies', [CurrencyController::class, 'index'] );
        Route::post( 'currency_organizer', [CurrencyController::class, 'organizer'] );
        Route::get( 'currency_input_fields', [CurrencyController::class, 'input_fields'] );
        Route::get( 'demo_currencies', [CurrencyController::class, 'demo_currencies'] );
        Route::post( 'create_currency', [CurrencyController::class, 'create'] );
        Route::post( 'update_currency', [CurrencyController::class, 'update'] );
        Route::get( 'attachment/{id}', [CurrencyController ::class, 'attachment'] );
        Route::get( 'geo_input_fields', [GeoIpController::class, 'input_fields'] );
        Route::post( 'save_currency_geo_locations', [GeoIpController::class, 'save_currency_geo_location'] );
        Route::get( 'exchange_all', [RateController::class, 'exchange_all'] );
        Route::post( 'exchange_single', [RateController::class, 'exchange_single'] );
        Route::get( 'setting_inputs', [SettingController::class, 'setting_inputs'] );
        Route::post( 'save_settings', [SettingController::class, 'save_settings'] );
        Route::get( 'switcher_list', [SwitcherController::class, 'switcher_list'] );
        Route::get( 'pages', [SwitcherController::class, 'pages'] );
        Route::post( 'switcher_organizer', [SwitcherController::class, 'organizer'] );
        Route::post( 'create_switcher', [SwitcherController::class, 'create'] );
        Route::post( 'update_switcher', [SwitcherController::class, 'update'] );
        Route::get( 'notice_maybe_latter', [NoticeController::class, 'maybe_latter'] );
    }, ['admin']
);