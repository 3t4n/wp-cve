<?php

defined('ABSPATH') || exit;

class RKMW_Classes_Error extends RKMW_Classes_FrontController {

    /** @var array */
    private static $errors = array();
    private static $switch_off = array();

    public function __construct() {
        parent::__construct();

        add_action('rkmw_notices', array('RKMW_Classes_Error', 'hookNotices'));
    }

    /**
     * Get the error message
     * @return int
     */
    public static function getError() {
        if (count(self::$errors) > 0) {
            return self::$errors[0]['text'];
        }

        return false;
    }

    /**
     * Clear all the Errors from Plugin
     */
    public static function clearErrors() {
        self::$errors = array();
    }

    /**
     * Show the error in wrodpress
     *
     * @param string $error
     * @param string $type
     * @param string $id
     */
    public static function setError($error = '', $type = 'notice', $id = '') {
        self::$errors[] = array(
            'id' => $id,
            'type' => $type,
            'text' => $error);
    }

    /**
     * Check if there is a Error triggered
     * @return bool
     */
    public static function isError() {
        if(!empty(self::$errors)){
            foreach (self::$errors as $error){
                if($error['type'] <> 'success' ){
                    return true;
                }
            }
        }
    }

    /**
     * Set a success message
     * @param string $message
     * @param string $id
     */
    public static function setMessage($message = '', $id = '') {
        self::$errors[] = array(
            'id' => $id,
            'type' => 'success',
            'text' => $message);
    }

    /**
     * This hook will show the error in WP header
     */
    public static function hookNotices() {
        if (is_array(self::$errors))
            foreach (self::$errors as $error) {

                switch ($error['type']) {
                    case 'fatal':
                        self::showError(ucfirst(RKMW_PLUGIN_NAME . " " . $error['type']) . ': ' . $error['text'], $error['id']);
                        die();
                        break;
                    case 'settings':
                        /* switch off option for notifications */
                        self::showError($error['text'] . " ", $error['id']);
                        break;

                    case 'success':
                        self::showError($error['text'] . " ", $error['id'], 'rkmw_success');
                        break;

                    default:

                        self::showError($error['text'], $error['id']);
                }
            }
    }

    /**
     * Show the notices to WP
     *
     * @param $message
     * @param string $type
     * @return string
     */
    public static function showNotices($message, $type = 'rkmw_notices') {
        if (file_exists(RKMW_THEME_DIR . 'Notices.php')) {
            ob_start();
            include(RKMW_THEME_DIR . 'Notices.php');
            $message = ob_get_contents();
            ob_end_clean();
        }

        return (string)$message;
    }

    /**
     * Show the notices to WP
     *
     * @param $message
     * @param string $id
     * @param string $type
     *
     * return void
     */
    public static function showError($message, $id = '', $type = 'rkmw_error') {
        if (file_exists(RKMW_THEME_DIR . 'Notices.php')) {
            include(RKMW_THEME_DIR . 'Notices.php');
        }
    }


}
