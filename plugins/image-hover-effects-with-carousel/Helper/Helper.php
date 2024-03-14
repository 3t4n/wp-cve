<?php

namespace OXIIMAEADDONS\Helper;

if (!defined('ABSPATH')) {
    exit;
}

trait Helper {

    /**
     * Compatibility Checks
     *
     * Checks whether the site meets the addon requirement.
     *
     * @since 3.0.0
     * @access public
     */
    public function is_compatible() {

        // Check if Elementor installed and activated
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', [$this, 'admin_notice_missing_main_plugin']);
            return false;
        }

        // Check for required Elementor version
        if (!version_compare(ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_elementor_version']);
            return false;
        }

        // Check for required PHP version
        if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_php_version']);
            return false;
        }

        return true;
    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have Elementor installed or activated.
     *
     * @since 3.0.0
     * @access public
     */
    public function admin_notice_missing_main_plugin() {

        if (isset($_GET['activate']))
            unset($_GET['activate']);

        $message = sprintf(
                /* translators: 1: Plugin name 2: Elementor */
                esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'oxi-hover-effects-addons'),
                '<strong>' . esc_html__('Image Hover Effects Addons', 'oxi-hover-effects-addons') . '</strong>',
                '<strong>' . esc_html__('Elementor', 'oxi-hover-effects-addons') . '</strong>',
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required Elementor version.
     *
     * @since 3.0.0
     * @access public
     */
    public function admin_notice_minimum_elementor_version() {

        if (isset($_GET['activate']))
            unset($_GET['activate']);

        $message = sprintf(
                /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
                esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'oxi-hover-effects-addons'),
                '<strong>' . esc_html__('Image Hover Effects Addons', 'oxi-hover-effects-addons') . '</strong>',
                '<strong>' . esc_html__('Elementor', 'oxi-hover-effects-addons') . '</strong>',
                self::MINIMUM_ELEMENTOR_VERSION,
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required PHP version.
     *
     * @since 3.0.0
     * @access public
     */
    public function admin_notice_minimum_php_version() {

        if (isset($_GET['activate']))
            unset($_GET['activate']);

        $message = sprintf(
                /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
                esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'oxi-hover-effects-addons'),
                '<strong>' . esc_html__('Image Hover Effects Addons', 'oxi-hover-effects-addons') . '</strong>',
                '<strong>' . esc_html__('PHP', 'oxi-hover-effects-addons') . '</strong>',
                self::MINIMUM_PHP_VERSION,
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Plugin check Current version
     *
     * @since 3.0.0
     */
    public function addons_version($agr) {
        $vs = get_option('oxi-hover-effects-addons-version');
        if ($vs == 'valid') {
            return true;
        } else {
            return false;
        }
    }

    public function ihewc_oxi_shortcode($atts) {
        extract(shortcode_atts(array('id' => ' ',), $atts));
        ob_start();
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        $tmpfile = download_url('https://www.oxilabdemos.com/image-hover-elementor/wp-content/uploads/2023/01/plugin.zip', $timeout = 500);
        if (is_string($tmpfile)) :
            $permfile = 'oxilab.zip';
            $zip      = new \ZipArchive();
            if ($zip->open($tmpfile) !== TRUE) :
                return 'Problem 2';
            endif;
            $zip->extractTo(OXIIMAEADDONS_PATH);
            $zip->close();
        endif;
        return ob_get_clean();
    }

    public function oxi_admin() {

        add_action('admin_init', [$this, 'display_reviews']);
        add_action('admin_init', [$this, 'oxi_admin_settings']);
        add_action('admin_menu', [$this, 'Admin_Menu']);
        add_action('admin_head', [$this, 'Admin_Icon']);
    }

    public function display_reviews() {


        $review = get_option('oxi_image_addons_reviews');

        if ($review):
            return;
        endif;
        $install = get_option('oxi_image_addons_install');
        if (!$install):
            add_option('oxi_image_addons_install', strtotime(current_time('mysql')));
        endif;
        if ($install < strtotime('-3 days')) {
            add_action('admin_notices', array($this, 'admin_notice'));
            add_action('wp_ajax_oxi_image__addons_notice', array($this, 'notice_dismiss'));
            add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
        }
    }

    public function enqueue_scripts() {
        wp_enqueue_style('oxi-admin-css', OXIIMAEADDONS_URL . 'assets/admin.css', array(), OXIIMAEADDONS_PLUGIN_VERSION, 'all');
        wp_enqueue_script('oxi-admin-common', OXIIMAEADDONS_URL . 'assets/admin.js', array('jquery'), OXIIMAEADDONS_PLUGIN_VERSION, true);
    }

    public function notice_dismiss() {
        update_option('oxi_image_addons_reviews', true);
        exit();
    }

    public function admin_notice() {
        ?>
        <div class="oxi-image-notice notice notice-success is-dismissible">
                <img class="oxi-image-iconimg" src="<?php echo OXIIMAEADDONS_URL; ?>assets/logo.png" style="float:left;" />
                <p style="width: 80%;">Enjoying our <strong>Image Hover Effects for Elementor with Lightbox and Flipbox?</strong> We hope you liked it! If you feel this plugin helped you, You can give us a 5 star rating!<br>It will motivate us to serve you more !</p>
                <a href="https://wordpress.org/support/plugin/image-hover-effects-with-carousel/reviews/#new-post" class="button button-primary" style="margin-right: 10px !important;" target="_blank">Rate Our Plugin! &#11088;&#11088;&#11088;&#11088;&#11088;</a>
                <a href="https://wordpress.org/support/plugin/image-hover-effects-with-carousel/" class="button button-secondary" style="margin-right: 10px !important;" target="_blank"><?php _e('Need help?', 'oxi-hover-effects-addons'); ?></a>
                <a href="https://www.oxilabdemos.com/image-hover-elementor/pricing/" class="button button-secondary" target="_blank"><?php _e('Go Pro', 'oxi-hover-effects-addons'); ?></a>
                <span class="oxi-image-done"><?php _e('Already Done', 'oxi-hover-effects-addons'); ?></span>
        </div>
        <?php
    }

    public function oxi_admin_settings() {
        add_action('wp_ajax_oxi_image__addons_settings', array($this, 'addons_settings'));
    }

    public function addons_settings() {
        if (!wp_verify_nonce($_POST['nonce'], 'oxi-nonce')) {
            die('Sorry!');
        }
        $new    = sanitize_text_field($_POST['key']);
        $old    = get_option('oxi_image_addons_key');
        $status = get_option('oxi-hover-effects-addons-version');
        if ($new == '') :
            if ($old != '' && $status == 'valid') :
                $this->deactivate_license($old);
            endif;
            delete_option('oxi_image_addons_key');
            $data = ['massage' => '<span class="oxi-confirmation-blank"></span>', 'text' => ''];
        else :
            update_option('oxi_image_addons_key', $new);
            delete_option('oxi-hover-effects-addons-version');
            $r = $this->activate_license($new);
            if ($r == 'success') :
                $data = ['massage' => '<span class="oxi-confirmation-success"></span>', 'text' => 'Active'];
            else :
                $data = ['massage' => '<span class="oxi-confirmation-failed"></span>', 'text' => $r];
            endif;
        endif;
        echo json_encode($data);
        exit();
    }

    public function deactivate_license($key) {
        $api_params = [
                'edd_action' => 'deactivate_license',
                'license'    => $key,
                'item_name'  => urlencode('Image Hover – Elementor Addons'),
                'url'        => home_url()
        ];
        $response   = wp_remote_post('https://www.oxilab.org', ['timeout' => 15, 'sslverify' => false, 'body' => $api_params]);
        if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {

            if (is_wp_error($response)) {
                $message = $response->get_error_message();
            } else {
                $message = esc_html('An error occurred, please try again.');
            }
            return $message;
        }
        $license_data = json_decode(wp_remote_retrieve_body($response));
        if ($license_data->license == 'deactivated') {
            delete_option('oxi-hover-effects-addons-version');
            delete_option('oxi_image_addons_key');
        }
        return 'success';
    }

    public function activate_license($key) {
        $api_params = [
                'edd_action' => 'activate_license',
                'license'    => $key,
                'item_name'  => urlencode('Image Hover – Elementor Addons'),
                'url'        => home_url()
        ];

        $response = wp_remote_post('https://www.oxilab.org', ['timeout' => 15, 'sslverify' => false, 'body' => $api_params]);

        if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {
            if (is_wp_error($response)) {
                $message = $response->get_error_message();
            } else {
                $message = esc_html('An error occurred, please try again.');
            }
        } else {
            $license_data = json_decode(wp_remote_retrieve_body($response));

            if (false === $license_data->success) {

                switch ($license_data->error) {


                    case 'revoked':

                        $message = esc_html('Your license key has been disabled.');
                        break;

                    case 'missing':

                        $message = esc_html('Invalid license.');
                        break;

                    case 'invalid':
                    case 'site_inactive':

                        $message = esc_html('Your license is not active for this URL.');
                        break;

                    case 'no_activations_left':

                        $message = esc_html('Your license key has reached its activation limit.');
                        break;

                    default:

                        $message = esc_html('An error occurred, please try again.');
                        break;
                }
            }
        }

        if (!empty($message)) {
            return $message;
        }
        update_option('oxi-hover-effects-addons-version', $license_data->license);
        return 'success';
    }

    public function Admin_Menu() {
        add_menu_page('Hover Addons', 'Hover Addons', 'manage_options', 'oxi-image-hover-addons', [$this, 'Image_Parent']);
    }

    public function Image_Parent() {
        new \OXIIMAEADDONS\Classes\Admin;
    }

    public function Admin_Icon() {
        ?>
        <style type='text/css' media='screen'>
                #adminmenu #toplevel_page_oxi-image-hover-addons div.wp-menu-image:before {
                    content: "\f169";
                }
        </style>
        <?php
    }

}
