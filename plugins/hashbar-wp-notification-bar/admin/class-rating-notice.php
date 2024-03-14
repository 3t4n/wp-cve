<?php 

// If this file is accessed directly, exit.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Hashbar_Rating_Notice' ) ) {
    class Hashbar_Rating_Notice {
        private $previous_date;
        private $plugin_slug = 'hashbar-wp-notification-bar';
        private $plugin_name = 'HashBar';
        private $logo_url = HASHBAR_WPNB_URI . "/assets/images/logo.png";
        private $after_click_maybe_later_days = '-20 days';
        private $after_installed_days = '-14 days';
        private $installed_date_option_key = 'hashbar_installed';

        /**
         * Instance.
         */
        public static $_instance = null;

		/**
		 * Get instance.
		 */
		public static function get_instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

        public function __construct() {
            $this->previous_date = false == get_option('hashbar_maybe_later_time') ? strtotime( $this->after_installed_days ) : strtotime( $this->after_click_maybe_later_days );
            if ( current_user_can('administrator') ) {
                if ( empty( get_option('hashbar_rating_already_rated', false ) ) ) {
                    add_action( 'admin_init', [$this, 'check_plugin_install_time'] );
                }
            }

            if ( is_admin() ) {
                add_action( 'admin_head', [$this, 'enqueue_scripts' ] );
            }

            add_action( 'wp_ajax_hashbar_rating_maybe_later', [ $this, 'hashbar_rating_maybe_later' ] );
            add_action( 'wp_ajax_hashbar_rating_already_rated', [ $this, 'hashbar_rating_already_rated' ] );
        }

        public function check_plugin_install_time() {
            $installed_date = get_option( $this->installed_date_option_key );

            if ( false == get_option( 'hashbar_maybe_later_time' ) && false !== $installed_date && $this->previous_date >= $installed_date ) {
                add_action( 'admin_notices', [ $this, 'rating_notice_content' ] );

            } else if ( false != get_option( 'hashbar_maybe_later_time' ) && $this->previous_date >= get_option( 'hashbar_maybe_later_time' ) ) {
                add_action( 'admin_notices', [ $this, 'rating_notice_content' ] );

            }
        }

        public function hashbar_rating_maybe_later() {
            $nonce = $_POST['nonce'];

            if ( ! wp_verify_nonce( $nonce, 'hashbar-plugin-notice-nonce')  || ! current_user_can( 'manage_options' ) ) {
            exit;
            }

            update_option( 'hashbar_maybe_later_time', strtotime('now') );
        }

        function hashbar_rating_already_rated() {
            $nonce = $_POST['nonce'];

            if ( ! wp_verify_nonce( $nonce, 'hashbar-plugin-notice-nonce')  || ! current_user_can( 'manage_options' ) ) {
            exit; 
            }

            update_option( 'hashbar_rating_already_rated' , true );
        }
        
        public function rating_notice_content() {
            if ( is_admin() ) {
                echo '<div class="notice hashbar-rating-notice is-dismissible" style="border-left-color: #2271b1!important; display: flex; align-items: center;">
                            <div class="hashbar-rating-notice-logo">
                                <img src="' . $this->logo_url . '">
                            </div>
                            <div>
                                <h3>Thank you for choosing '. $this->plugin_name .' to show notifications to your audience!</h3>
                                <p style="">Would you mind doing us a huge favor by providing your feedback on WordPress? Your support helps us spread the word and greatly boosts our motivation.</p>
                                <p>
                                    <a href="https://wordpress.org/support/plugin/'. $this->plugin_slug .'/reviews/?filter=5#new-post" target="_blank" class="hashbar-you-deserve-it button button-primary">OK, you deserve it!</a>
                                    <a class="hashbar-maybe-later"><span class="dashicons dashicons-clock"></span> Maybe Later</a>
                                    <a class="hashbar-already-rated"><span class="dashicons dashicons-yes"></span> I Already did</a>
                                </p>
                            </div>
                    </div>';
            }
        }

        public static function enqueue_scripts() {
            echo "<style>
                .hashbar-rating-notice {
                padding: 10px 20px;
                border-top: 0;
                border-bottom: 0;
                }
                .hashbar-rating-notice-logo {
                    margin-right: 20px;
                    width: 100px;
                    height: 100px;
                }
                .hashbar-rating-notice-logo img {
                    max-width: 100%;
                }
                .hashbar-rating-notice h3 {
                margin-bottom: 0;
                }
                .hashbar-rating-notice p {
                margin-top: 3px;
                margin-bottom: 15px;
                display:flex;
                }
                .hashbar-maybe-later,
                .hashbar-already-rated {
                    text-decoration: none;
                    margin-left: 12px;
                    font-size: 14px;
                    cursor: pointer;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                }
                .hashbar-already-rated .dashicons,
                .hashbar-maybe-later .dashicons {
                vertical-align: middle;
                }
                .hashbar-rating-notice .notice-dismiss {
                    display: none;
                }
            </style>";
            $ajax_url = admin_url('admin-ajax.php');
            $notice_admin_nonce = wp_create_nonce('hashbar-plugin-notice-nonce');
            ?>

            <script type="text/javascript">
                (function ($) {
                    $(document).on( 'click', '.hashbar-maybe-later', function() {
                        $('.hashbar-rating-notice').slideUp();
                        jQuery.post({
                            url: <?php echo json_encode( $ajax_url ); ?>,
                            data: {
                                nonce: <?php echo json_encode( $notice_admin_nonce ); ?>,
                                action: 'hashbar_rating_maybe_later'
                            }
                        });
                    });

                    $(document).on( 'click', '.hashbar-already-rated', function() {
                        $('.hashbar-rating-notice').slideUp();
                        jQuery.post({
                            url: <?php echo json_encode( $ajax_url ); ?>,
                            data: {
                                nonce: <?php echo json_encode( $notice_admin_nonce ); ?>,
                                action: 'hashbar_rating_already_rated'
                            }
                        });
                    });
                })(jQuery);
            </script>

            <?php
        }

    }

    Hashbar_Rating_Notice::get_instance();
}