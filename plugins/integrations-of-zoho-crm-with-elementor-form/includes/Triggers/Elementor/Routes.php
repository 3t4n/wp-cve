<?php

if (!defined('ABSPATH')) {
    exit;
}

use FormInteg\IZCRMEF\Core\Util\Route;
use FormInteg\IZCRMEF\Triggers\Elementor\ElementorController;

Route::get('elementor/get', [ElementorController::class, 'getAllForms']);
Route::post('elementor/get/form', [ElementorController::class, 'getFormFields']);
