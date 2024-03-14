<?php

if (!defined('ABSPATH')) {
    exit;
}

use FormInteg\IZCRMEF\Core\Util\Hooks;
use FormInteg\IZCRMEF\Triggers\Elementor\ElementorController;

Hooks::add('elementor_pro/forms/new_record', [ElementorController::class, 'handle_elementor_submit']);
