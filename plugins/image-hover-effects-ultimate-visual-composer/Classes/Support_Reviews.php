<?php

namespace OXI_FLIP_BOX_PLUGINS\Classes;

/**
 * Description of Support_Reviews
 *
 * @author biplo
 */
class Support_Reviews
{


    /**
     * Admin Notice JS file loader
     * @return void
     */
    public function dismiss_button_scripts()
    {
        wp_enqueue_script('oxilab_flip-admin-notice', OXI_FLIP_BOX_URL . 'asset/backend/js/admin-notice.js', false, OXI_FLIP_BOX_PLUGIN_VERSION);
        wp_localize_script('oxilab_flip-admin-notice', 'oxilab_flip_notice_dissmiss', array('ajaxurl' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('oxilab_flip_notice_dissmiss')));
    }

   

    /**
     * Admin Notice Ajax  loader
     * @return void
     */
    public function notice_dissmiss()
    {
        if (isset($_POST['_wpnonce']) && wp_verify_nonce(sanitize_key(wp_unslash($_POST['_wpnonce'])), 'oxilab_flip_notice_dissmiss')) :
            $notice = isset($_POST['notice']) ? sanitize_text_field($_POST['notice']) : '';
            if ($notice == 'maybe') :
                $data = strtotime("now");
                update_option('oxilab_flip_box_activation_date', $data);
            else :
                update_option('oxilab_flip_box_nobug', $notice);
            endif;
            echo 'Its Complete';
        else :
            return;
        endif;

        die();
    }

    /**
     * First Installation Track
     * @return void
     */
    public function first_install()
    {
        if (!current_user_can('manage_options')) {
            return;
        }
        $image = OXI_FLIP_BOX_URL . 'image/logo.png';
?>
        <div class="notice notice-info put-dismiss-noticenotice-has-thumbnail shortcode-addons-review-notice oxilab-flipbox-review-notice">
            <div class="shortcode-addons-notice-thumbnail">
                <img src="<?php echo esc_url($image); ?>" alt="">
            </div>
            <div class="shortcode-addons--notice-message">
                <p>Hey, You’ve using <strong>Flipbox - Awesomes Flip Boxes Image Overlay</strong> more than 1 week – that’s awesome! Could you please do me a BIG favor and give it a 5-star rating on WordPress? Just to help us spread the word and boost our motivation.!</p>
                <ul class="shortcode-addons--notice-link">
                    <li>
                        <a href="https://wordpress.org/support/plugin/image-hover-effects-ultimate-visual-composer/reviews/" target="_blank">
                            <span class="dashicons dashicons-external"></span>Ok, you deserve it!
                        </a>
                    </li>
                    <li>
                        <a class="oxi-flip-support-reviews" sup-data="success" href="#">
                            <span class="dashicons dashicons-smiley"></span>I already did
                        </a>
                    </li>
                    <li>
                        <a class="oxi-flip-support-reviews" sup-data="maybe" href="#">
                            <span class="dashicons dashicons-calendar-alt"></span>Maybe Later
                        </a>
                    </li>
                    <li>
                        <a href="https://wordpress.org/support/plugin/image-hover-effects-ultimate-visual-composer/">
                            <span class="dashicons dashicons-sos"></span>I need help
                        </a>
                    </li>
                    <li>
                        <a class="oxi-flip-support-reviews" sup-data="never" href="#">
                            <span class="dashicons dashicons-dismiss"></span>Never show again
                        </a>
                    </li>
                </ul>
            </div>
        </div>
<?php
    }
     /**
     * Revoke this function when the object is created.
     *
     */
    public function __construct()
    {
        add_action('admin_notices', array($this, 'first_install'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        add_action('wp_ajax_oxilab_flip_notice_dissmiss', array($this, 'notice_dissmiss'));
        add_action('admin_notices', array($this, 'dismiss_button_scripts'));
    }

    /**
     * Admin Notice CSS file loader
     * @return void
     */
    public function admin_enqueue_scripts()
    {
        wp_enqueue_script("jquery");
        wp_enqueue_style('oxilab_flip-admin-notice-css', OXI_FLIP_BOX_URL . 'asset/backend/css/notice.css', false, OXI_FLIP_BOX_PLUGIN_VERSION);
        $this->dismiss_button_scripts();
    }
}
