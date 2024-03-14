<?php

use function MailOptin\Core\moVar;

if ( ! class_exists('\MoBFnote')) {

    class MoBFnote
    {
        private $this_year;
        private $last_year;
        private $start;
        private $end;

        public function __construct()
        {
            add_action('mailoptin_admin_notices', function () {
                add_action('admin_notices', array($this, 'admin_notice'));
            });
            add_action('network_admin_notices', array($this, 'admin_notice'));

            add_action('admin_init', array($this, 'dismiss_admin_notice'));

            // Intentional: using manually written string instead of gmdate( 'Y' ).
            $this->this_year = '2023';
            $this->last_year = $this->this_year - 1;
            $this->start     = strtotime('november 23rd, ' . $this->this_year);
            $this->end       = strtotime('december 1st, ' . $this->this_year);
        }

        public function dismiss_admin_notice()
        {
            if ( ! isset($_GET['mobfnote-adaction']) || $_GET['mobfnote-adaction'] != 'mobfnote_dismiss_adnotice') {
                return;
            }

            if ( ! current_user_can('manage_options')) return;

            $url = admin_url();
            update_option('mobfnote_dismiss_adnotice_' . $this->this_year, 'true');

            wp_safe_redirect($url);
            exit;
        }

        public function admin_notice()
        {
            global $pagenow;

            if ($pagenow != 'index.php' && strpos(moVar($_GET, 'page'), 'mailoptin-') === false) return;

            if (defined('MAILOPTIN_DETACH_LIBSODIUM')) return;

            if ( ! current_user_can('manage_options')) return;

            $now = time();

            if ($now < $this->start || $now > $this->end) return;

            if ( ! empty(get_option('mobfnote_dismiss_adnotice', 0))) {
                delete_option('mobfnote_dismiss_adnotice');
            }

            if ( ! empty(get_option('mobfnote_dismiss_adnotice_' . $this->last_year, 0))) {
                delete_option('mobfnote_dismiss_adnotice_' . $this->last_year);
            }

            if (get_option('mobfnote_dismiss_adnotice_' . $this->this_year, 'false') == 'true') {
                return;
            }

            $dismiss_url = esc_url_raw(
                add_query_arg(
                    array(
                        'mobfnote-adaction' => 'mobfnote_dismiss_adnotice'
                    ),
                    admin_url()
                )
            );
            $this->notice_css();

            $bf_url = 'https://mailoptin.io/pricing/?utm_source=wp-admin&utm_medium=admin-notice&utm_campaign=bf' . $this->this_year

            ?>
            <div class="mobfnote-admin-notice notice notice-success">
                <div class="mobfnote-notice-first-half">
                    <p>
                        <?php
                        printf(
                            __('%1$sHuge Black Friday Sale%2$s: Get 40%% off your MailOptin plugin upgrade today with the coupon %3$sBFCM%4$s', 'mailoptin'),
                            '<span class="mobfnote-stylize"><strong>', '</strong></span>', '<code>', $this->this_year . '</code>');
                        ?>
                    </p>
                    <p style="text-decoration: underline;font-size: 12px;">Hurry as the deal is expiring soon.</p>

                </div>
                <div class="mobfnote-notice-other-half">
                    <a target="_blank" class="button button-primary button-hero" id="mobfnote-install-mailoptin-plugin" href="<?php echo $bf_url; ?>">
                        <?php _e('Save 40% Now!', 'mailoptin'); ?>
                    </a>
                    <div class="mobfnote-notice-learn-more">
                        <a target="_blank" href="<?php echo $bf_url; ?>">Learn more</a>
                    </div>
                </div>
                <a href="<?php echo $dismiss_url; ?>">
                    <button type="button" class="notice-dismiss">
                        <span class="screen-reader-text"><?php _e('Dismiss this notice', 'mailoptin'); ?>.</span>
                    </button>
                </a>
            </div>
            <?php
        }

        public function notice_css()
        {
            ?>
            <style type="text/css">
                .mobfnote-admin-notice {
                    background: #fff;
                    color: #000;
                    border-left-color: #46b450;
                    position: relative;
                }

                .mobfnote-admin-notice .notice-dismiss:before {
                    color: #72777c;
                }

                .mobfnote-admin-notice .mobfnote-stylize {
                    line-height: 2;
                    font-size: 16px;
                }

                .mobfnote-admin-notice .button-primary {
                    background: #006799;
                    text-shadow: none;
                    border: 0;
                    box-shadow: none;
                }

                .mobfnote-notice-first-half {
                    width: 66%;
                    display: inline-block;
                    margin: 10px 0 20px;
                }

                .mobfnote-notice-other-half {
                    width: 33%;
                    display: inline-block;
                    padding: 20px 0;
                    position: absolute;
                    text-align: center;
                }

                .mobfnote-notice-first-half p {
                    font-size: 14px;
                }

                .mobfnote-notice-learn-more a {
                    margin: 10px;
                }

                .mobfnote-notice-learn-more {
                    margin-top: 10px;
                }
            </style>
            <?php
        }

        public static function instance()
        {
            static $instance = null;

            if (is_null($instance)) {
                $instance = new self();
            }

            return $instance;
        }
    }
}