<?php

/*

 * Plugin Name: Recapture for Easy Digital Downloads
 * Plugin URI: https://recapture.io/
 * Description: Recapture helps you increase revenue by automatically recovering abandoned carts.
 * Version: 1.0.35
 * Author: Recapture
 * Text Domain: recapture
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if (!class_exists('Recapture')) {

    include(dirname(__FILE__) . '/class-recapture-utils.php');
    include(dirname(__FILE__) . '/platforms/class-base-platform.php');
    include(dirname(__FILE__) . '/platforms/class-edd.php');
    include(dirname(__FILE__) . '/platforms/class-woocommerce.php');

    define('RECAPTURE_VERSION', '1.0.35');
    define('RECAPTURE_TEXT_DOMAIN', 'recapture');
    define('RECAPTURE_TARGET_PLATFORM', 'Easy Digital Downloads');

    class Recapture
    {
        private $platform = null;

        /**
         * Constructor for the plugin.
         *
         * @access        public
         */
        public function __construct()
        {
            add_action('init', [$this, 'load_plugin_textdomain']);

            // setup the platform
            if (RecaptureEDD::is_ready()) {
                $this->platform = new RecaptureEDD();
            } else {
                add_action('admin_init', [$this, 'recapture_nag_ignore']);
                add_action('admin_notices', [$this, 'plugin_missing_notice']);
                return;
            }

            // Add the plugin page Settings and Docs links
            add_filter('plugin_action_links_' . plugin_basename(__FILE__),
                [$this, 'recapture_plugin_links']);

            // standard wordpress actions
            add_action('init', [$this, 'check_version']);
            add_action('init', [$this, 'finish_recapture_connection']);
            add_action('init', [$this, 'create_unique_discount_code']);
            add_action('init', [$this, 'delete_unique_discount_code']);
            add_action('init', [$this, 'find_products']);
            add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);
            add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);

            // set the cart id, overwrite the current cart if any
            add_action('init', [$this, 'set_cart_id_from_Url']);
            add_action('wp_loaded', [$this, 'regenerate_cart_from_url']);
            add_action('wp_loaded', [$this, 'render_review_page']);
            add_action('admin_notices', [$this, 'admin_notices']);
            add_action('admin_menu', [$this, 'admin_page']);

            // ajax handlers
            add_action('wp_ajax_recapture_connection_status', [$this, 'ajax_is_connected']);

            // submit reviews
            add_action('wp_ajax_recapture_submit_reviews', [$this, 'ajax_submit_reviews']);
            add_action('wp_ajax_nopriv_recapture_submit_reviews', [$this, 'ajax_submit_reviews']);

            // Recapture connection actions
            add_action('admin_post_recapture_disconnect',
                [$this, 'disconnect_recapture']);

            add_action('admin_post_recapture_connect',
                [$this, 'connect_recapture']);

            add_action('admin_post_recapture_confirm_disconnect',
                [$this, 'confirm_disconnect_recapture']);

            add_filter('allowed_redirect_hosts',  [$this, 'allowed_redirect_hosts']);

            // setup the actions for this provider;
            $this->platform->add_actions();
        }

        function allowed_redirect_hosts($hosts) {
            $full_hosts = [
                'recapture.io',
                'app.recapture.io',
                'localhost'
            ];
            return array_merge($hosts, $full_hosts);
        }

        function load_plugin_textdomain() {
            $location = basename(dirname(__FILE__)).'/languages/';
            load_plugin_textdomain(RECAPTURE_TEXT_DOMAIN, FALSE, $location);
        }

        function enqueue_scripts() {
            $api_key = RecaptureUtils::get_api_key();

            if (!$api_key || empty($api_key)) {
                return;
            }

            $cache_bust = round((time() / (60 * 10)));
            $url = RecaptureUtils::get_loader_url() . '?v=' . $cache_bust;

            // enqueue the recapture loader
            wp_register_script(
                'recapture_frontend_script',
                $url,
                [],
                RECAPTURE_VERSION);

            // setup the load script
            $api_key = sanitize_text_field($api_key);

            $script = "
                if (!window.ra) {
                    window.ra = function() { window.ra.q.push([].slice.call(arguments)); };
                    window.ra.q = [];
                }

                ra('init', ['$api_key']);
                ra('initCartId');
                ra('email');
            ";

            $script .= $this->platform->is_product_page()
                ? "ra('product', [{}]);"
                : "ra('page');";

            wp_add_inline_script('recapture_frontend_script', $script);
            wp_enqueue_script('recapture_frontend_script');

            $this->platform->enqueue_scripts();
        }

        public function regenerate_cart_from_url()
        {
            // Ignoring wpecs warning because we receive this URL from Recapture
            // so we can't add/check a nonce
            // phpcs:ignore
            $req = (object)$_GET;

            if (!isset($req->racart) || !isset($req->contents)) {
                return;
            }

            $this->platform->regenerate_cart_from_url(
                sanitize_text_field($req->racart),
                sanitize_text_field($req->contents)
            );
        }

        public function render_review_page()
        {
            // Ignoring wpecs warning because we receive this URL from Recapture
            // so we can't add/check a nonce
            // phpcs:ignore
            $req = (object)$_GET;

            /*
            if (!$this->platform->supports_reviews()) {
                return;
            }
            */

            if (!isset($req->recapture_review) || !isset($req->hash)) {
                return;
            }

            $rb = RecaptureUtils::get_order_for_reviews($req->hash);

            if ($rb == null) {
                wp_safe_redirect(home_url());
                die();
            }

            $order = $rb->order;

            if (!isset($order->first_name) || strlen($order->first_name) == 0) {
                $order->first_name = $this->platform->get_customer_first_name_from_order(
                    $order->external_id
                );
            }

            $page_text = $rb->page_text;
            $user_logo = $rb->user_logo;

            $has_title = $this->platform->reviews_have_title();

            wp_enqueue_style(
                'review-styles',
                plugins_url('./css/reviews.css' , __FILE__),
                array(),
                plugins_url('./css/reviews.css' , __FILE__),
                false
            );

            wp_enqueue_script(
                'review-scripts',
                plugins_url('./js/reviews.js' , __FILE__),
                array(),
                false,
                true
            );

            wp_enqueue_script('jquery');

            wp_localize_script(
                'review-scripts',
                '___recapture',
                ['ajax' => admin_url('admin-ajax.php')]
            );

            include(dirname(__FILE__) . '/templates/reviews.php');
            die();
        }

        function admin_enqueue_scripts() {
            // add our style
            wp_register_style(
                'recapture_styles',
                plugins_url('/css/styles.css', __FILE__),
                false,
                RECAPTURE_VERSION,
                'all'
            );

            wp_enqueue_style('recapture_styles');

            // Add script
            wp_register_script(
                'recapture_script',
                plugins_url('/js/admin.js', __FILE__),
                RECAPTURE_VERSION,
                'all');

            wp_localize_script(
                'recapture_script',
                '___recapture',
                ['ajax' => admin_url('admin-ajax.php')]
            );

            wp_enqueue_script('recapture_script');
        }

        public function set_cart_id_from_Url()
        {
            // Ignoring wpecs warning because we receive this URL from Recapture
            // so we can't add/check a nonce
            // phpcs:ignore
            $req = (object)$_GET;

            if (isset($req->racart)) {
                RecaptureUtils::set_cart_id(sanitize_text_field($req->racart));
            }
        }

        public function ajax_is_connected() {
            wp_send_json_success(RecaptureUtils::is_connected());
        }

        public function ajax_submit_reviews() {
            // Security check
            $nonce = isset($_POST['recapture_submit_reviews'])
                ? sanitize_text_field(wp_unslash($_POST['recapture_submit_reviews']))
                : null;

            if ($nonce == null || !wp_verify_nonce($nonce, 'recapture_submit_reviews')) {
                wp_send_json_error(new WP_Error('001', 'Invalid', 'Invalid nonce'));
            } 

            // Make sure we have the required data
            if (!isset($_POST['products']) || !isset($_POST['external_id'])
            ) {
                wp_send_json_error(new WP_Error('002', 'Invalid', 'Missing required data'));
            }

            $products = array_values(array_map(
                function ($product) {
                    $product_id = sanitize_text_field($product['product_id']);
                    $external_id = sanitize_text_field($product['external_id']);
                    return (object) [
                        'skip' => $product['skip'] != '0',
                        'sku' => sanitize_text_field(wp_unslash($product['sku'])),
                        'name' => sanitize_text_field(wp_unslash($product['name'])),
                        'product_id' => $product_id,
                        'external_id' => $external_id,
                        'rating' => sanitize_text_field($product['rating']),
                        'title' => sanitize_text_field(wp_unslash($product['title'])),
                        'detail' => sanitize_textarea_field(wp_unslash($product['detail'])),
                        'product_url' => $this->platform->get_product_url($external_id)
                    ];
                },
                // already sanitized in array_map
                // phpcs:ignore
                $_POST['products']
            ));

            $external_id = sanitize_text_field(wp_unslash($_POST['external_id']));

            // get the name and email from the platform
            $email = $this->platform->get_customer_email_from_order($external_id);
            $author = $this->platform->get_customer_name_from_order($external_id);

            // send the reviews to Recapture
            RecaptureUtils::send_reviews($external_id, $author, $email, $products);

            // Record the reviews in the platform
            $this->platform->save_reviews($external_id, $author, $email, $products);

            wp_send_json_success(true);
        }

        /**
         * Set up admin notices
         *
         * @access        public
         * @return        void
         */
        public function admin_notices()
        {
            $screen = get_current_screen();

            // If the Merchant ID field is empty
            if (get_option('recapture_api_key') || $screen->base == 'toplevel_page_recapture') {
                return;
            }

            ?>
            <div class="updated">
                <p>
                    <?php esc_html_e('Please connect website to Recapture', RECAPTURE_TEXT_DOMAIN); ?>
                    <a href="<?php echo esc_html(admin_url('admin.php?page=recapture')) ?>">
                        <?php esc_html_e('here', RECAPTURE_TEXT_DOMAIN); ?>
                    </a>
                </p>
            </div>
            <?php
        }

        /**
         * Initialize the Recapture menu
         *
         * @access        public
         * @return        void
         */
        public function admin_page()
        {
            add_menu_page(
                'Recapture',
                'Recapture',
                'manage_options',
                'recapture',
                [$this, 'admin_options'],
                plugins_url('images/recapture.png', __FILE__),
                58);

            add_submenu_page(
                null,
                'Disconnect Recapture',
                'Disconnect Recapture',
                'manage_options',
                'recapture-confirm-disconnect',
                [$this, 'confirm_disconnect_recapture']
            );

            add_action('admin_init', [$this, 'register_settings']);
        }

        /**
         * Register settings for Recapture
         *
         * @access        public
         * @return        void
         */
        public function register_settings()
        {
            register_setting('recapture-settings-group',
                'recapture_api_key');

            register_setting('recapture-settings-group',
                'recapture_custom_loader_url');

            register_setting('recapture-settings-group',
                'recapture_custom_recapture_host');
        }

        /**
         * Add options to the Recapture menu
         *
         * @access        public
         * @return        void
         */
        public function admin_options()
        {
            $discount = RecaptureUtils::get_discount_details();

            ?>
            <div class="wrap">
                <h2>Recapture</h2>

                <form method="post" action="<?php echo esc_html(admin_url('admin-post.php')); ?>" target="_blank">
                    <?php if (RecaptureUtils::is_connected()) { ?>
                        <p>
                            <?php esc_html_e('Recapture has been successfully connected.', RECAPTURE_TEXT_DOMAIN) ?>
                        </p>
                        <p>
                            <a href="https://app.recapture.io/account/abandoned-carts" target="_blank">
                                Campaign Analytics
                            </a>
                            &nbsp;|&nbsp;
                            <a href="https://app.recapture.io/account/abandoned-carts/campaigns" target="_blank">
                                Manage Campaigns
                            </a>
                            &nbsp;|&nbsp;
                            <a href="https://docs.recapture.io/" target="_blank">
                                Docs and Support
                            </a>
                        </p>
                        <h3 class="recapture-disconnect">Disconnect this site</h3>
                        <p>
                            <a href="<?php echo esc_html(admin_url('admin.php?page=recapture-confirm-disconnect')) ?>" class="button">
                                <?php esc_html_e('Disconnect from Recapture', RECAPTURE_TEXT_DOMAIN) ?>
                            </a>
                        </p>

                    <?php } else { ?>
                        <?php
                        if ($discount) {
                        ?>
                            <div class="recapture-discount">
                                <?php echo esc_html($discount->description) ?>
                                <?php submit_button(__('Get Started', RECAPTURE_TEXT_DOMAIN), 'large', 'submit', false); ?>
                            </div>
                        <?php
                        }
                        ?>
                        <h2>Welcome to Recapture!</h2>
                        <p>
                            The best way to recover abandoned carts for <?php echo esc_html(RECAPTURE_TARGET_PLATFORM) ?>
                        </p>
                        <p>
                            To start increasing your store's revenue, you'll need to connect your site to <a href="https://recapture.io">Recapture</a>
                        </p>
                        <p>
                            <input type="hidden" name="action" value="recapture_connect">
                            <?php submit_button(__('Connect to Recapture', RECAPTURE_TEXT_DOMAIN), 'primary', 'submit', false); ?>
                        </p>
                        <p>
                            If you're having issues connecting, don't hesitate to <a href="https://recapture.io">contact us</a>
                        </p>
                        <script>
                        window.RecaptureAdmin.monitorAccountConnection();
                        </script>
                    <?php } ?>
                </form>
            </div>
            <?php
        }

        function connect_recapture()
        {
            // redirect to the connection url
            $url = RecaptureUtils::get_connect_url(
                $this->platform->get_name(),
                admin_url('admin.php?page=recapture'),
                admin_url('admin.php?page=recapture')
            );
            wp_safe_redirect($url);
            exit();
        }

        function disconnect_recapture()
        {
            RecaptureUtils::delete_api_key();
            wp_safe_redirect(admin_url('admin.php?page=recapture'));
            exit();
        }

        function confirm_disconnect_recapture()
        {
            ?>
            <div class="wrap">
                <h2>Recapture</h2>
                <form method="post" action="<?php echo esc_html(admin_url('admin-post.php')); ?>">
                    <p>
                        <?php esc_html_e('Disconnecting will stop new carts from being added and orders from being tracked', RECAPTURE_TEXT_DOMAIN) ?>
                        <input type="hidden" name="action" value="recapture_disconnect">
                    <p>
                        <?php submit_button(__('I understand, disconnect my store', RECAPTURE_TEXT_DOMAIN), 'primary', 'submit', false); ?>
                        &nbsp;
                        <a href="<?php echo esc_html(admin_url('admin.php?page=recapture')) ?>" class="button">
                            <?php esc_html_e('Cancel', RECAPTURE_TEXT_DOMAIN) ?>
                        </a>
                    </p>
                </form>
            </div>
            <?php
        }

        function finish_recapture_connection()
        {
            // Ignoring wpecs warning because this URL is called from Recapture via the backend, i.e. the user
            // session is not valid

            // phpcs:ignore
            if (empty($_GET['connect_recapture']) || empty($_GET['api_key']) || empty($_GET['token'])) {
                return;
            }

            // phpcs:ignore
            $token = sanitize_text_field(wp_unslash($_GET['token']));

            // phpcs:ignore
            $api_key = sanitize_text_field(wp_unslash($_GET['api_key']));

            if ($token != RecaptureUtils::get_authenticator_token()) {
                return;
            }

            RecaptureUtils::set_api_key($api_key);

            // clear any discount code??
            RecaptureUtils::clear_discount_details();

            // phpcs:ignore
            if (isset($_GET['redirect_settings'])) {
                wp_safe_redirect(admin_url('admin.php?page=recapture'));
                die();
            }

            die('Connected!');
        }

        function create_unique_discount_code() {
            // phpcs:ignore
            if (empty($_GET['generate_unique_discount']) || empty($_GET['token'])) {
                return;
            }

            // phpcs:ignore
            $token = sanitize_text_field(wp_unslash($_GET['token']));

            if ($token != RecaptureUtils::get_authenticator_token()) {
                return;
            }

            $spec = json_decode(file_get_contents('php://input'), true);

            if ($spec == null) {
                wp_send_json([], 422);
                return;
            }

            $discount = $this->platform->create_unique_discount_code((object)$spec);
            wp_send_json(['discount' => $discount]);
        }

        function delete_unique_discount_code() {
            // phpcs:ignore
            if (empty($_GET['delete_unique_discount']) || empty($_GET['token']) || empty($_GET['code'])) {
                return;
            }

            // phpcs:ignore
            $token = sanitize_text_field(wp_unslash($_GET['token']));
            $code = sanitize_text_field(wp_unslash($_GET['code']));

            if ($token != RecaptureUtils::get_authenticator_token()) {
                return;
            }

            $ok = $this->platform->delete_unique_discount_code($code);
            wp_send_json(['ok' => $ok]);
        }

        function find_products() {
            // phpcs:ignore
            if (empty($_GET['recapture_find_products']) || empty($_GET['token'])) {
                return;
            }

            // phpcs:ignore
            $token = sanitize_text_field(wp_unslash($_GET['token']));
            $filter = isset($_GET['filter'])
                ? sanitize_text_field(wp_unslash($_GET['filter']))
                : '';

            if ($token != RecaptureUtils::get_authenticator_token()) {
                return;
            }

            $products = $this->platform->find_products($filter);
            wp_send_json($products);
        }

        function check_version()
        {
            // Called from Recapture to check the current plugin version, nonce check not possible
            // phpcs:ignore
            if (isset($_GET['get_recapture_version'])) {
                $res = [
                    'version' => RECAPTURE_VERSION,
                    'platform' => $this->platform->get_name()
                ];
                echo json_encode($res);
                die();
            }
        }

        /**
         * Plugin page links
         *
         * @param array $links
         * @return array
         */
        function recapture_plugin_links($links)
        {
            $links['settings'] = '<a href="' . esc_html(admin_url('admin.php?page=recapture&settings-updated=true')) . '">' . __('Settings', 'Recapture') . '</a>';
            return $links;
        }


        /**
         * Easy Digital Downloads plugin missing notice.
         *
         * @return string
         */
        public function plugin_missing_notice()
        {
            global $current_user;
            $user_id = $current_user->ID;
            if (!get_user_meta($user_id, 'recapture_missing_plugin_nag')) {
                $message = sprintf(
                    __('Recapture needs %s to be installed and active.', RECAPTURE_TEXT_DOMAIN),
                    RECAPTURE_TARGET_PLATFORM);

                $hide_message = __('Hide Notice', RECAPTURE_TEXT_DOMAIN);
                $remove_nag_url = wp_nonce_url('?recapture_missing_plugin_nag=0', 'remove_recapture_nag');

                ?>
                    <div class="error">
                        <p>
                            <?php echo esc_html($message) ?>
                            <a href="<?php echo esc_html($remove_nag_url) ?>">
                                <?php echo esc_html($hide_message) ?>
                            </a>
                        </p>
                    </div> 
                <?php
            }

            return null;
        }

        /**
         *  Remove the nag if user chooses
         */
        function recapture_nag_ignore()
        {
            global $current_user;
            $user_id = $current_user->ID;

            if (isset($_GET['recapture_missing_plugin_nag'])) {
                check_admin_referer('remove_recapture_nag');
                add_user_meta($user_id, 'recapture_missing_plugin_nag', 'true', true);
            }
        }
    }

    function recapture_plugins_loaded() {
        new Recapture();
    }
    add_action('plugins_loaded', 'recapture_plugins_loaded');

    function recapture_plugin_activated($plugin) {
        $is_ready = RecaptureEDD::is_ready();

        if ($plugin == plugin_basename(__FILE__) && !RecaptureUtils::is_connected() && $is_ready) {
            wp_safe_redirect(admin_url('admin.php?page=recapture'));
            exit();
        }
    }
    add_action('activated_plugin', 'recapture_plugin_activated');

} // End if class_exists check.
