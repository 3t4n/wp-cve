<?php 
namespace HTMega\RatingNotice;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

class HTMEGA_Rating_Notice {
    private $previous_date;
    private $plugin_slug = 'ht-mega-for-elementor';
    private $plugin_name = 'HT Mega Elementor Addons';
    private $logo_url = HTMEGA_ADDONS_PL_URL . "admin/assets/images/logo.png";
    private $after_click_maybe_later_days = '-20 days';
    private $after_installed_days = '-14 days';
    private $installed_date_option_key = 'htmega_elementor_addons_activation_time';

    public function __construct() {
        $this->previous_date = false == get_option('htmega_maybe_later_time') ? strtotime( $this->after_installed_days ) : strtotime( $this->after_click_maybe_later_days );
        if ( current_user_can('administrator') ) {
            if ( empty( get_option('htmega_rating_already_rated', false ) ) ) {
                add_action( 'admin_init', [$this, 'check_plugin_install_time'] );
            }
        }

        if ( is_admin() ) {
            add_action( 'admin_head', [$this, 'enqueue_scripts' ] );
        }

        add_action( 'wp_ajax_htmega_rating_maybe_later', [ $this, 'htmega_rating_maybe_later' ] );
        add_action( 'wp_ajax_htmega_rating_already_rated', [ $this, 'htmega_rating_already_rated' ] );
    }

    public function check_plugin_install_time() {
        $installed_date = get_option( $this->installed_date_option_key );

        if ( false == get_option( 'htmega_maybe_later_time' ) && false !== $installed_date && $this->previous_date >= $installed_date ) {
            add_action( 'admin_notices', [ $this, 'rating_notice_content' ] );

        } else if ( false != get_option( 'htmega_maybe_later_time' ) && $this->previous_date >= get_option( 'htmega_maybe_later_time' ) ) {
            add_action( 'admin_notices', [ $this, 'rating_notice_content' ] );

        }
    }

    public function htmega_rating_maybe_later() {
		$nonce = $_POST['nonce'];

		if ( ! wp_verify_nonce( $nonce, 'htmega-plugin-notice-nonce')  || ! current_user_can( 'manage_options' ) ) {
		  exit;
		}

        update_option( 'htmega_maybe_later_time', strtotime('now') );
    }

    function htmega_rating_already_rated() {
		$nonce = $_POST['nonce'];

		if ( ! wp_verify_nonce( $nonce, 'htmega-plugin-notice-nonce')  || ! current_user_can( 'manage_options' ) ) {
		  exit; 
		}

        update_option( 'htmega_rating_already_rated' , true );
    }
    
    public function rating_notice_content() {
        if ( is_admin() ) {
            echo '<div class="notice htmega-rating-notice is-dismissible" style="border-left-color: #2271b1!important; display: flex; align-items: center;">
                        <div class="htmega-rating-notice-logo">
                            <img src="' . $this->logo_url . '">
                        </div>
                        <div>
                            <h3>Thank you for choosing '. $this->plugin_name .' to design your website!</h3>
                            <p style="">Would you mind doing us a huge favor by providing your feedback on WordPress? Your support helps us spread the word and greatly boosts our motivation.</p>
                            <p>
                                <a href="https://wordpress.org/support/plugin/'. $this->plugin_slug .'/reviews/?filter=5#new-post" target="_blank" class="htmega-you-deserve-it button button-primary"> OK, you deserve it!</a>
                                <a class="htmega-maybe-later"><span class="dashicons dashicons-clock"></span> Maybe Later</a>
                                <a class="htmega-already-rated"><span class="dashicons dashicons-yes"></span> I Already did</a>
                            </p>
                        </div>
                </div>';
        }
    }

    public static function enqueue_scripts() {
        echo "<style>
            .htmega-rating-notice {
              padding: 10px 20px;
              border-top: 0;
              border-bottom: 0;
            }
            .htmega-rating-notice-logo {
                margin-right: 20px;
                width: 100px;
                height: 100px;
            }
            .htmega-rating-notice-logo img {
                max-width: 100%;
            }
            .htmega-rating-notice h3 {
              margin-bottom: 0;
            }
            .htmega-rating-notice p {
              margin-top: 3px;
              margin-bottom: 15px;
              display:flex;
            }
            .htmega-maybe-later,
            .htmega-already-rated {
                text-decoration: none;
                margin-left: 12px;
                font-size: 14px;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
            .htmega-already-rated .dashicons,
            .htmega-maybe-later .dashicons {
              vertical-align: middle;
            }
            .htmega-rating-notice .notice-dismiss {
                display: none;
            }
        </style>";
        $notice_admin_nonce = wp_create_nonce('htmega-plugin-notice-nonce');
        ?>

        <script type="text/javascript">
            (function ($) {
                $(document).on( 'click', '.htmega-maybe-later', function() {
                    $('.htmega-rating-notice').slideUp();
                    jQuery.post({
                        url: ajaxurl,
                        data: {
                            nonce: <?php echo json_encode( $notice_admin_nonce ); ?>,
                            action: 'htmega_rating_maybe_later'
                        }
                    });
                });

                $(document).on( 'click', '.htmega-already-rated', function() {
                    $('.htmega-rating-notice').slideUp();
                    jQuery.post({
                        url: ajaxurl,
                        data: {
                            nonce: <?php echo json_encode( $notice_admin_nonce ); ?>,
                            action: 'htmega_rating_already_rated'
                        }
                    });
                });
            })(jQuery);
        </script>

        <?php
    }

}

new HTMEGA_Rating_Notice();