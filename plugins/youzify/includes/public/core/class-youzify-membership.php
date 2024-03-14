<?php

class Youzify_Membership {

    public $form;
    public $login;
    public $social;
    public $styling;
    public $register;
    public $template_message;

    public function __construct() {

        // Load Functions.
        add_action( 'init', array( $this, 'init' ) );

        // Add Widgets.
        add_action( 'widgets_init', array( $this, 'load_widgets' ) );

    }

    /**
     * Register & Load Widgets.
     */
    function load_widgets() {

        // Include Widgets.
        require YOUZIFY_CORE . 'membership/class-youzify-widgets.php';

        // Register Login Widget
        register_widget( 'Youzify_Login_Widget' );

        // Register Registration Widget
        register_widget( 'Youzify_Register_Widget' );

        // Reset Password Widget
        register_widget( 'Youzify_Reset_Password_Widget' );

    }

    /**
     * Init Files
     */
    function init() {

        // Global Functions.
        require YOUZIFY_CORE . 'membership/general/youzify-membership-general-functions.php';

        if ( ! is_user_logged_in() ) {

            // General Functions
            include YOUZIFY_CORE . 'membership/functions/youzify-membership-general-functions.php';
            include YOUZIFY_CORE . 'membership/functions/youzify-membership-social-functions.php';
            include YOUZIFY_CORE . 'membership/functions/youzify-membership-bp-functions.php';

            // Classes
            include YOUZIFY_CORE . 'membership/class-youzify-form.php';
            include YOUZIFY_CORE . 'membership/class-youzify-social.php';
            include YOUZIFY_CORE . 'membership/class-youzify-rewrite.php';
            include YOUZIFY_CORE . 'membership/class-youzify-styling.php';

            if ( youzify_is_limit_login_enabled() ) {
                include YOUZIFY_CORE . 'membership/class-youzify-limit.php';
            }

            // Include Main Pages
            include YOUZIFY_CORE . 'membership/class-youzify-login.php';
            include YOUZIFY_CORE . 'membership/class-youzify-register.php';
            include YOUZIFY_CORE . 'membership/class-youzify-lost-password.php';

            // Init Classes
            $this->login    = new Youzify_Membership_Login();
            $this->form     = new Youzify_Membership_Form();
            $this->social   = new Youzify_Membership_Social();
            $this->styling  = new Youzify_Membership_Styling();
            $this->register = new Youzify_Membership_Register();

        } else {

            // Hide Dashboard
            $this->hide_dashboard();

        }

    }

    /**
     * Hide Dashboard Admin Bar For Non Admins.
     */
    function hide_dashboard() {

        if ( is_super_admin() ) {
            return;
        }

        if ( is_multisite() ) {

            global $blog_id;

            if ( ! current_user_can_for_blog( $blog_id, 'subscriber' ) ) {
                return;
            }

        } else {

            if ( ! current_user_can( 'subscriber' ) ) {
                return;
            }

        }

        if ( 'on' != youzify_option( 'youzify_hide_subscribers_dash', 'off' ) ) {
            return;
        }

        // Hide Admin Bar.
        if ( ! is_admin() ) {
            show_admin_bar( false );
        }

        // Hide Admin Dashboard.
        if ( is_admin() && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
            wp_redirect( home_url() );
            exit;
        }

    }
}

global $Youzify_Membership;

// Init Class
$Youzify_Membership = new Youzify_Membership();