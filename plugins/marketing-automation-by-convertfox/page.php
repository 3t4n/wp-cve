<?php
add_action( 'wp_head', array('InsertConvertFox', 'insert_tracker' ));

Class InsertConvertFox {
    /**
    * Adds the Javascript necessary to start tracking via Gist.
    * this gets added to the <head> section usually.
    *
    * @return [type] [description]
    */
    public static function insert_tracker() {
        $settings = (array) get_option( 'convertfox_settings' );
        if ( isset($settings['is_enabled']) && $settings['is_enabled'] != "" ) {
            if ( $settings['disable_for_admin'] && current_user_can('administrator') ) {
                echo '<!-- Gist: Tracking has been disabled for Admin users -->';
            } else {
                if ( isset($settings['project_id']) && $settings['project_id'] != "" ) {
                    require_once dirname(__FILE__) . '/convertfoxjs.php';
                } else {
                    echo '<!-- Gist: Set your project ID to begin tracking -->';
                }
            }
        }
    }
}
?>