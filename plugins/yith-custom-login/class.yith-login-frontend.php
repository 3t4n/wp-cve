<?php
/**
 * Main class
 *
 * @author YITH <plugins@yithemes.com>
 * @package YITH Custom Login
 * @version 1.0.2
 */

if ( !defined( 'YITH_LOGIN' ) ) { exit; } // Exit if accessed directly

if( !class_exists( 'YITH_Login_Frontend' ) ) {
    /**
     * YITH Custom Login Frontend
     *
     * @since 0.9.0
     */
    class YITH_Login_Frontend {
        /**
         * Plugin version
         *
         * @var string
         * @since 0.9.0
         */
        public $version;

        /**
         * Constructor
         *
         * @return YITH_Login_Frontend
         * @since 0.9.0
         */
        public function __construct( $version ) {
            $this->version = $version;

            if( yith_login_is_enabled() ) {
                add_action( 'login_enqueue_scripts', array( $this, 'login_enqueue'), 15 );
                add_action( 'login_headerurl', array( $this, 'change_logo_url' ) );
                add_action( 'login_headertext', array( $this, 'change_logo_title' ) );
                add_action( 'login_head', array( $this, 'add_login_style'), 15 );
                add_action( 'login_head', array( $this, 'add_login_script'), 15 );
                add_action( 'login_form', array( $this, 'add_mascotte' ) );
            }

            return $this;
        }

        /**
         * Change the logo URL to the home URL.
         * Useful for multisite logins
         *
         * @return string|void
         */
        public function change_logo_url() {
            return home_url();
        }

        /**
         * Change the logo Title.
         * Useful for multisite logins
         *
         * @return string
         */
        public function change_logo_title() {
            return get_bloginfo( 'name' );
        }

        /**
         * Add the mascotte image
         */
        public function add_mascotte() {
            if ( ! get_option( 'yith_login_mascotte', true ) ) return;
            ?><img src="<?php echo get_option( 'yith_login_mascotte_url', YITH_LOGIN_URL . 'assets/images/mascotte.png' ) ?>" alt="Mascotte" class="mascotte" /><?php
        }

        /**
         * Add custom style to login screen
         *
         * @return void
         * @since 0.9.0
         */
        public function add_login_style() {
            $bg_color = get_option('yith_login_background_color');
            $bg_image = get_option('yith_login_background_image');
            $bg_repeat = get_option('yith_login_background_repeat');
            $bg_position = get_option('yith_login_background_position');
            $bg_attachment = get_option('yith_login_background_attachment');

            $logo_img = get_option('yith_login_logo_image');
            $logo_color = get_option('yith_login_logo_color');
            $logo_width = get_option('yith_login_logo_width');
            $logo_height = get_option('yith_login_logo_height');
            $container_width = get_option('yith_login_container_width');

            /* typography */
            $typo_input_text = yit_typo_option_to_css( get_option('yith_login_typo_input') );
            $typo_general_text = yit_typo_option_to_css( get_option('yith_login_typo_text') );
            $typo_submit_text = yit_typo_option_to_css( get_option('yith_login_typo_submit') );

            $custom_style = get_option('yith_login_custom_style');
            ?>
            <?php if ( yith_google_fonts_url() != '' ) : ?><link rel="stylesheet" media="all" href="<?php echo yith_google_fonts_url() ?>" /><?php endif; ?>

            <style type="text/css">
                <?php if( $bg_color || $bg_image ): ?>
                body.login, html {
                    background: <?php echo $bg_color ?> <?php if($bg_image): ?>url('<?php echo $bg_image ?>') <?php echo $bg_repeat ?> <?php echo $bg_position ?> <?php echo $bg_attachment ?><?php endif ?>;
                }
                <?php endif ?>

                <?php if( $container_width ): ?>
                #login {
                    width: <?php echo $container_width ?>px;
                }
                <?php endif ?>

                <?php if( $logo_img ): ?>
                .login h1 a {
                    background-image: url('<?php echo $logo_img ?>');
                    <?php if ( $logo_color ) : ?>background-color: <?php echo $logo_color ?>;<?php endif; ?>
                    background-size: <?php echo $logo_width ?>px <?php echo $logo_height ?>px;
                    width: 100%;
                    height: <?php echo $logo_height ?>px;
                    max-width: 100%;
                }
                <?php endif ?>

                .mobile #login h1 a { width: 100% }
                .mobile #login form, .mobile #login .message, .mobile #login_error { margin-left: auto }

                /* Typography */
                .login form .input, .login input[type="text"] {
                    <?php echo $typo_input_text ?>
                    background-color: <?php echo get_option('yith_login_color_input') ?>;
                    border-color: <?php echo get_option('yith_login_border_input') ?>;
                }

                .login form .input:focus, .login input[type="text"]:focus {
                    background-color: <?php echo get_option('yith_login_color_input_focus') ?>;
                    border-color: <?php echo get_option('yith_login_border_input_focus') ?>;
                }

                .login form .forgetmenot label {
                    <?php echo $typo_general_text ?>
                }

                input#wp-submit {
                    <?php echo $typo_submit_text ?>
                    background-color: <?php echo get_option('yith_login_submit_color') ?>;
                    border-color: <?php echo get_option('yith_login_submit_border_color') ?>;
                }

                input#wp-submit:hover {
                    color: <?php echo get_option('yith_login_typo_submit_hover') ?>;
                    background-color: <?php echo get_option('yith_login_submit_color_hover') ?>;
                    border-color: <?php echo get_option('yith_login_submit_border_color_hover') ?>;
                }

                <?php echo $custom_style ?>
            </style>

            <!--[if lte IE 8]>
            <style type="text/css">
                #rememberme { display: inline-block; }
                #rememberme + span { display: none }
            </style>
            <![endif]-->
        <?php
        }

        /**
         * Enqueue scripts and styles
         *
         * @return void
         * @since 0.9.0
         */
        public function login_enqueue() {
            $filename = 'custom-login.css';
            $plugin_path   = array( 'path' => plugin_dir_path(__FILE__) . 'assets/css/style.css', 'url' => YITH_LOGIN_URL . 'assets/css/style.css' );
            $template_path = array( 'path' => get_template_directory() . '/' . $filename,         'url' => get_template_directory_uri() . '/' . $filename );
            $child_path    = array( 'path' => get_stylesheet_directory() . '/' . $filename,       'url' => get_stylesheet_directory_uri() . '/' . $filename );

            foreach ( array( 'child_path', 'template_path', 'plugin_path' ) as $var ) {
                if ( file_exists( ${$var}['path'] ) ) {
                    $filename = ${$var}['url'];
                    break;
                }
            }
            ?>
            <link rel="stylesheet" media="all" href="<?php echo $filename ?>" />
            <?php
            wp_enqueue_script( 'jquery' );
        }

        /**
         * Add custom script to login screen
         *
         * @return void
         * @since 0.9.0
         */
        public function add_login_script() {
            ?>
            <script type="text/javascript">
                jQuery(function($){
                    $('input#rememberme').after('<span>&nbsp;</span>');
                    $('input#user_login').attr('placeholder', '<?php echo get_option('yith_login_username_label') ?>');
                    $('input#user_pass').attr('placeholder', '<?php echo get_option('yith_login_password_label') ?>');
                });
            </script>
        <?php
        }
    }
}