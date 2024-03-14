<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
};

/**
 * Class to render or change HTML elements on login page
 * @since 1.0.0
**/

class CLP_Render_HTML {
    /**
     * Add video scripts to login page footer
     * @since 1.0.0
    **/
    public static function footer_video_script() {
        $html = '';
        include dirname( __FILE__) . '/render-html/footer-video-scripts.php';

        return $html;
    }

    /**
     * Add/remove/change logo on login page
     * @since 1.0.0
    **/
    public static function logo() {
        $html = '';
        include dirname( __FILE__) . '/render-html/logo.php';

        return $html;
    }

    /**
     * Add/remove/change logo on login page
     * @since 1.4.0
    **/
    public static function page_footer() {
        $html = '';
        include dirname( __FILE__) . '/render-html/page-footer.php';

        return $html;
    }

    /**
     * Various JavaScript to manipulate HTML on login page
     * @since 1.0.0
    **/
    public static function dom_elements_manipulation() {
        $html = '';
        include dirname( __FILE__) . '/render-html/dom-manipulations.php';

        return $html;
    }
 
    /**
     * Form width fix on half content, prevent to form to overflow
     * @since 1.4.6
    **/
    public static function form_width_fix() {
        $html = '';
        include dirname( __FILE__) . '/render-html/form-width-fix.php';

        return $html;
    }
}