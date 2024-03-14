<?php

namespace Hurrytimer;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://nabillemsieh.com
 * @since      1.0.0
 *
 * @package    Hurrytimer
 * @subpackage Hurrytimer/public
 */
class Frontend {
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of the plugin.
     * @param string $version The version of this plugin.
     *
     * @since    1.0.0
     *
     */
    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version     = $version;
    }

    function init() {

        // Enqueue CSS and JS.
        add_action('wp_enqueue_scripts', [$this, 'enqueue_styles']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);

        // Display campaign in sticky bar.
        add_action('wp_footer', [$this, 'render_sticky_bar']);
        add_action('wp_footer', [$this, 'run_in_background']);

        // Display campaign on product page.
        add_action('wp', [$this, 'render_on_product_page']);

        // Change stock status for recurring/onetime campaign via Ajax.
        add_action('wp_ajax_change_stock_status', [$this, 'ajax_change_stock_status']);
        add_action('wp_ajax_nopriv_change_stock_status', [$this, 'ajax_change_stock_status']);

        // Log evergreen campaign start time for each visitor.
        add_action('wp_ajax_hurryt/update_timestamp', [$this, 'ajax_save_evergreen_start_time']);
        add_action('wp_ajax_nopriv_hurryt/update_timestamp', [$this, 'ajax_save_evergreen_start_time']);

        // Return next recurrence.
        add_action('wp_ajax_next_recurrence', [$this, 'ajax_next_recurrence']);
        add_action('wp_ajax_nopriv_next_recurrence', [$this, 'ajax_next_recurrence']);

        // Check before rendering campaign.
        add_action('hurryt_pre_render', [$this, 'pre_render_shortcode']);

        // Check actions for expired recurring and onetime campaigns
        // This only concerns shortcodes inserted in post editor.
        // Fallback to shortcode callback
        add_action('wp', [$this, 'check_post_shortcode']);
    }

    function run_in_background() {

        // At this time, all campagins with the "Expire coupon" action 
        // should run in background.
        $campaigns = get_posts([
            'post_type'   => HURRYT_POST_TYPE,
            'numberposts' => -1,
            'post_status' => 'publish',
        ]);

        foreach ($campaigns as $campaign) {
            $campaign = new Campaign($campaign->ID);

            $campaign->loadSettings();
            $action_expire_coupon = $campaign->get_action(C::ACTION_EXPIRE_COUPON);
            // $action_hide_atc_button = $campaign->get_action(C::ACTION_HIDE_ADD_TO_CART_BUTTON);

            $run_in_background = !empty($action_expire_coupon);
            if ($run_in_background) {
                echo do_shortcode('[hurrytimer id=' . $campaign->get_id() . ' run_in_background=true]');
            }
        }
    }

    /**
     * Check if there is shortcode in the current post.
     */
    function check_post_shortcode() {
        global $post;
        if (
            is_singular() && is_a($post, 'WP_Post')
            && has_shortcode($post->post_content, 'hurrytimer') && !(defined('DOING_AJAX') && DOING_AJAX)
        ) {
            $campaigns_ids = hurryt_parse_campaigns($post->post_content);
            foreach ($campaigns_ids as $id) {
                do_action('hurryt_pre_render', hurryt_get_campaign($id));
            }
        }
    }

    /**
     * @param $campaign Campaign
     */
    function pre_render_shortcode($campaign) {
        if ($campaign->is_running() && $campaign->is_expired()) {
            (new ActionManager($campaign))->run();
        }
    }


    public function ajax_next_recurrence() {
        // check_ajax_referer('hurryt', 'nonce');
        $campaign_id = absint($_GET['id']);
        if (
            !get_post($campaign_id)
            || get_post_type($campaign_id) !== HURRYT_POST_TYPE
        ) {
            die(-1);
        }

        $campaign = new Campaign($campaign_id);
        $endDate  = $campaign->getRecurrenceEndDate();
        
        wp_send_json_success([
            'endTimestamp' => $endDate ? $endDate->getBrowserTimestamp() : null,
        ]);
    }

    /**
     * Uses ajax to save start time for the given visitor.
     * This used to bypass cookie cache.
     */
    public function ajax_save_evergreen_start_time() {
        check_ajax_referer('hurryt', 'nonce');
        if (!isset($_POST['timestamp']) || !isset($_POST['cid'])) {
            wp_die();
        }

        $evergreen_campaign = new EvergreenCampaign(intval($_POST['cid']));
        $evergreen_campaign->setEndDate(filter_input(INPUT_POST, 'timestamp'));
        wp_die('Success!');
    }

    public function render_sticky_bar() {
        $args = [
            'post_type'        => HURRYT_POST_TYPE,
            'numberposts'      => -1,
            'post_status'      => 'publish',
            'meta_key'         => 'enable_sticky',
            'meta_value'       => C::YES,
        ];

        if(defined( 'ICL_SITEPRESS_VERSION' )){
            $args['suppress_filters'] = false;
        }
        
        $campaigns = get_posts($args);

        foreach ($campaigns as $post) {
            echo do_shortcode(sprintf('[hurrytimer id="%d" sticky="true" ]', $post->ID));
        }
    }

    /**
     * Apply change stock status
     */
    public function ajax_change_stock_status() {
        check_ajax_referer('hurryt', 'nonce');
        if (!isset($_POST['campaign_id'], $_POST['status'])) {
            die(-1);
        }
        $id          = intval($_POST['campaign_id']);
        $status      = sanitize_key($_POST['status']);
        $wc_campaign = new WCCampaign();
        $campaign    = new Campaign($id);
        $wc_campaign->change_stock_status($campaign, $status);
        die();
    }

    /**
     * Maybe display campaign on current product page.
     */
    public function render_on_product_page() {
        global $post;
        if (hurryt_is_woocommerce_activated() && is_product()) {
            $wc_campaign = new WCCampaign();
            $wc_campaign->run($post->ID);
        }
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        wp_enqueue_style( 'hurrytimer', CSS_Builder::get_instance()->get_css_url());
    }

    function get_custom_css(){
        $custom_css = get_option('hurrytimer_custom_css');
        if($custom_css){
            return $custom_css;
        }
        ob_start();
        // TODO: Minify CSS
        include __DIR__ . '/includes/css_template.php';
        $custom_css =  ob_get_clean();
        update_option('hurrytimer_custom_css', $custom_css);
        return $custom_css;
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        wp_enqueue_script('jquery');

        $deps = ['jquery', 'hurryt-countdown'];

        // Use woocommerce js cookie if already enqueued.
        // TODO: remove this dep in the future
        // if(! wp_script_is('js-cookie') ){
            wp_enqueue_script(
                'hurryt-cookie',
                HURRYT_URL . 'assets/js/cookie.min.js',
                [],
                '3.14.1',
                true
            );
            $deps[] = 'hurryt-cookie';
        // }
       
        wp_enqueue_script(
            'hurryt-countdown',
            HURRYT_URL . 'assets/js/jquery.countdown.min.js',
            ['jquery'],
            '2.2.0',
            true
        );

        wp_enqueue_script(
            $this->plugin_name,
            HURRYT_URL . 'assets/js/hurrytimer.js',
            $deps,
            defined('WP_DEBUG') && WP_DEBUG ? time() : $this->version,
            true
        );


        $disable_actions = hurryt_is_admin_area() && hurryt_settings()['disable_actions'];
        $disable_actions = filter_var(
            apply_filters('hurryt_disable_actions', $disable_actions),
            FILTER_VALIDATE_BOOLEAN
        );

        wp_localize_script($this->plugin_name, 'hurrytimer_ajax_object', [
            'ajax_url'              => admin_url('admin-ajax.php'),
            'ajax_nonce'            => wp_create_nonce('hurryt'),
            'disable_actions'       => $disable_actions,
            'methods'               => [
                'COOKIE'       => C::DETECTION_METHOD_COOKIE,
                'IP'           => C::DETECTION_METHOD_IP,
                'USER_SESSION' => C::DETECTION_METHOD_USER_SESSION,
            ],
            'actionsOptions'        => [
                'none'                => C::ACTION_NONE,
                'hide'                => C::ACTION_HIDE,
                'redirect'            => C::ACTION_REDIRECT,
                'stockStatus'         => C::ACTION_CHANGE_STOCK_STATUS,
                'hideAddToCartButton' => C::ACTION_HIDE_ADD_TO_CART_BUTTON,
                'displayMessage'      => C::ACTION_DISPLAY_MESSAGE,
                'expire_coupon'       => C::ACTION_EXPIRE_COUPON,
            ],
            'restartOptions'        => [
                'none'        => C::RESTART_NONE,
                'immediately' => C::RESTART_IMMEDIATELY,
                'afterReload' => C::RESTART_AFTER_RELOAD,
                'after_duration' => C::RESTART_AFTER_DURATION,
            ],
            'COOKIEPATH'            => defined('COOKIEPATH') ? COOKIEPATH : '',
            'COOKIE_DOMAIN'         => defined('COOKIE_DOMAIN') ? COOKIE_DOMAIN : '',
            'redirect_no_back'      => apply_filters('hurryt_redirect_no_back', true),
            'expire_coupon_message' => $this->get_coupon_expired_message(),
            'invalid_checkout_coupon_message'=> $this->get_checkout_invalid_coupon_message()
        ]);
    }


    /**
     * Get WooCommerce coupon expired message.
     *
     *
     * TODO: move to /integration/woocommerce
     *
     * @return string
     * @since 2.3
     */
    function get_coupon_expired_message() {

        if (!hurryt_is_woocommerce_activated()) {
            return '';
        }

        try {
            return (new \WC_Coupon())->get_coupon_error(\WC_Coupon::E_WC_COUPON_EXPIRED);
        } catch (\Exception $e) {
            return '';
        }
    }
     function get_checkout_invalid_coupon_message() {

        if (!hurryt_is_woocommerce_activated()) {
            return '';
        }

        try {
            return (new \WC_Coupon())->get_coupon_error(\WC_Coupon::E_WC_COUPON_INVALID_REMOVED);
        } catch (\Exception $e) {
            return '';
        }
    }
}
