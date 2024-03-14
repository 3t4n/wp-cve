<?php
if ( ! class_exists( 'Thepack_Marketing_Notice' ) && ! class_exists( 'thepack_elementor_addon_widget' ) ) {

    class Thepack_Marketing_Notice {

        public static function init() {
            $current      = time();
            register_activation_hook( ICONELEMENT_ROOT_FILE__, [__CLASS__, 'activation_time'] );
            add_action( 'admin_init', [__CLASS__, 'install_time'] );
            add_action( 'admin_init', [__CLASS__, 'donot_disturb'], 5 );
        }

        public static function activation_time() {
            $get_activation_time = strtotime( "now" );
            add_option( 'plugin_activation_time', $get_activation_time ); 
        }

        public static function install_time() {

            $nobug = get_option( 'donot_disturb', "0"); 

            if ($nobug == "1" || $nobug == "3") {
                return;
            }

            $install_date = get_option( 'plugin_activation_time' );
            $past_date    = strtotime( '-10 days' );

            $remind_time = get_option( 'thepack_remind_me' );
            $remind_due  = strtotime( '+15 days', $remind_time );
            $now         = strtotime( "now" );

            if ( $now >= $remind_due ) {
                add_action( 'admin_notices', [__CLASS__, 'show_notice']);
            } else if (($past_date >= $install_date) &&  $nobug !== "2") {
                add_action( 'admin_notices', [__CLASS__, 'show_notice']);
            }
        }

        public static function show_notice() {
    
            global $pagenow;

            $exclude = [];

            if ( ! in_array( $pagenow, $exclude ) ) {

                $dont_disturb = esc_url( add_query_arg( 'donot_disturb', '1', self::current_admin_url() ) );
                $remind_me    = esc_url( add_query_arg( 'thepack_remind_me', '1', self::current_admin_url() ) );
                $plugin  = 'the-pack-addon/index.php';
                if ( file_exists( WP_PLUGIN_DIR . '/the-pack-addon/index.php' ) ) {

                    $action_url   = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );
                    $button_label = __( 'Activate The Pack', 'icon-element' );
    
                } else {
    
                    $action_url   = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=the-pack-addon' ), 'install-plugin_the-pack-addon' );
                    $button_label = __( 'Install The Pack Addon', 'icon-element' );
                }

               echo '<div class="notice tp-marketing-wrapper"> 
                    <div class="tp-marketing-wrapper_content">
                        <h3>The Biggest Elementor Library</h3>
                        <p>Top notch elementor addon for your site ! 74+ free widgets, header & footer builder, 30+ pages and more. Grab it today !</p>
                        <div class="tp-marketing-wrapper_actions">
                            <a href="'.$action_url.'" class="rtrs-review-button"><span>'.$button_label.'</span></a>
                            <a href="'.$remind_me.'" class="rtrs-review-button"><span>Remind Me Later</span></a>
                            <a href="'.$dont_disturb.'" class="rtrs-review-button"><span>No Thanks</span></a>
                        </div>
                    </div> 
                </div>';

                echo '<style> 
                .tp-marketing-wrapper_content {
                    position: relative;
                    background-position: center center;
                    background-size: cover;
                    background-image:url('.ICON_ELEM_URL.'includes/img/banner.png'.');
                }
                .tp-marketing-wrapper.notice {
                    padding: 0;
                    position:relative;
                }
                .tp-marketing-wrapper:before {
                    position: absolute;
                    top: -1px;
                    bottom: -1px;
                    left: -4px;
                    display: block;
                    width: 4px;
                    background: #000;
                    content: "";
                } 
                .tp-marketing-wrapper_content {
                    padding: 20px;
                } 
                .tp-marketing-wrapper_actions > * + * {
                    margin-inline-start: 8px;
                    -webkit-margin-start: 8px;
                    -moz-margin-start: 8px;
                } 
                .tp-marketing-wrapper p {
                    margin: 0;
                    padding: 0;
                    line-height: 1.5;
                }
                p + .tp-marketing-wrapper_actions {
                    margin-top: 1rem;
                }
                .tp-marketing-wrapper h3 {
                    margin: 0;
                    font-size: 1.0625rem;
                    line-height: 1.2;
                }
                .tp-marketing-wrapper h3 + p {
                    margin-top: 8px;
                } 
                .rtrs-review-button {
                    display: inline-block;
                    padding: 0.4375rem 0.75rem;
                    border: 0;
                    border-radius: 3px;;
                    background: #000;
                    border:1px solid #000;
                    color: #fff;
                    vertical-align: middle;
                    text-align: center;
                    text-decoration: none;
                    white-space: nowrap; 
                }
                .rtrs-review-button:hover{
                    color:#000;
                    background:#fff;
                    border:1px solid #000;
                }
                </style>';
            }
        }

        // remove the notice for the user if review already done or if the user does not want to
        public static function donot_disturb() {
            if ( isset( $_GET['donot_disturb'] ) && ! empty( $_GET['donot_disturb'] ) ) {
                $spare_me = $_GET['donot_disturb'];
                if ( 1 == $spare_me ) {
                    update_option( 'donot_disturb', "1" );
                }
            }

            if ( isset( $_GET['thepack_remind_me'] ) && ! empty( $_GET['thepack_remind_me'] ) ) {
                $remind_me = $_GET['thepack_remind_me'];
                if ( 1 == $remind_me ) {
                    $get_activation_time = strtotime( "now" );
                    update_option( 'thepack_remind_me', $get_activation_time );
                    update_option( 'donot_disturb', "2" );
                }
            }

        }

        protected static function current_admin_url() {
            $uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
            $uri = preg_replace( '|^.*/wp-admin/|i', '', $uri );

            if ( ! $uri ) {
                return '';
            }
            return remove_query_arg( [ '_wpnonce', '_wc_notice_nonce', 'wc_db_update', 'wc_db_update_nonce', 'wc-hide-notice' ], admin_url( $uri ) );
        }
    } 

    Thepack_Marketing_Notice::init();

}