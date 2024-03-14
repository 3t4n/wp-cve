<?php

namespace ACFWF\Traits;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * This trait contains all the legacy methods that are used in the plugin constants.
 * This methods should not be used anymore, instead just call the properties directly.
 * This methods are retained here for backwards compatibility.
 */
trait Plugin_Constants_Legacy_Methods {

    public function MAIN_PLUGIN_FILE_PATH() { // phpcs:ignore
        return $this->MAIN_PLUGIN_FILE_PATH;
    }

    public function PLUGIN_DIR_PATH() { // phpcs:ignore
        return $this->PLUGIN_DIR_PATH;
    }

    public function PLUGIN_DIR_URL() { // phpcs:ignore
        return $this->PLUGIN_DIR_URL;
    }

    public function PLUGIN_DIRNAME() { // phpcs:ignore
        return $this->PLUGIN_DIRNAME;
    }

    public function PLUGIN_BASENAME() { // phpcs:ignore
        return $this->PLUGIN_BASENAME;
    }

    public function CSS_ROOT_URL() { // phpcs:ignore
        return $this->CSS_ROOT_URL;
    }

    public function IMAGES_ROOT_URL() { // phpcs:ignore
        return $this->IMAGES_ROOT_URL;
    }

    public function JS_ROOT_URL() { // phpcs:ignore
        return $this->JS_ROOT_URL;
    }

    public function JS_ROOT_PATH() { // phpcs:ignore
        return $this->JS_ROOT_PATH;
    }

    public function VIEWS_ROOT_PATH() { // phpcs:ignore
        return $this->VIEWS_ROOT_PATH;
    }

    public function TEMPLATES_ROOT_PATH() { // phpcs:ignore
        return $this->TEMPLATES_ROOT_PATH;
    }

    public function LOGS_ROOT_PATH() { // phpcs:ignore
        return $this->LOGS_ROOT_PATH;
    }

    public function THIRD_PARTY_PATH() { // phpcs:ignore
        return $this->THIRD_PARTY_PATH;
    }

    public function DIST_ROOT_PATH() { // phpcs:ignore
        return $this->PLUGIN_DIR_PATH . 'dist/';
    }

    public function THIRD_PARTY_URL() { // phpcs:ignore
        return $this->THIRD_PARTY_URL;
    }

    public function PREMIUM_PLUGIN_BASENAME() { // phpcs:ignore
        return $this->PREMIUM_PLUGIN_BASENAME;
    }

    public function DIST_ROOT_URL() { // phpcs:ignore
        return $this->PLUGIN_DIR_URL . 'dist/';
    }
}
