<?php
// Do not use namespace to keep this in the global space and maintain singleton initialization
if (!class_exists('Openexchange_api_settings')) {

    /**
     * Main class for creating dashboard addon page and all submenu items
     * Do not call or initialize this class directly; instead, use the function mentioned at the bottom of this file
     */
    class Openexchange_api_settings
    {
        /**
         * Private static instance variable
         */
        private static $instance;

        /**
         * Initialize the class and create the dashboard page only once
         */
        public static function init()
        {
            if (empty(self::$instance)) {
                return self::$instance = new self();
            }
            return self::$instance;
        }

        /**
         * Initialize the dashboard with specific plugins as per plugin tag
         */
        public function cool_init_hooks()
        {
            add_action('admin_notices', array($this, 'openexchange_api_key_notice'));
            add_action('admin_menu', array($this, 'openexchange_add_submenu'), 100);
            add_action('cmb2_admin_init', array($this, 'openexchange_settings_callback'));
            add_action('admin_enqueue_scripts', array($this, 'openexchange_custom_javascript_for_cmb2'));
        }

        /**
         * Enqueue custom JavaScript for CMB2
         */
        public function openexchange_custom_javascript_for_cmb2()
        {
            $custom_script_inline = "
            jQuery(document).ready(function ($) {

                    var url = window.location.href;
                    if (url.indexOf('?page=openexchange-api-settings') > 0) {
                        $('[href=\"admin.php?page=openexchange-api-settings\"]').parent('li').addClass('current');
                    }
                    var data = $('#adminmenu #toplevel_page_cool-crypto-plugins ul li a[href=\"admin.php?page=openexchange-api-settings\"]')
                    data.each(function (e) {
                        if ($(this).is(':empty')) {
                            $(this).hide();
                        }
                    });

                });
            ";
            wp_enqueue_script('jquery');
            wp_add_inline_script('jquery', $custom_script_inline);
        }

        /**
         * Add submenu page for API settings
         */
        public function openexchange_add_submenu()
        {
            add_submenu_page('cool-crypto-plugins', 'API Settings', 'API Settings', 'manage_options', 'admin.php?page=openexchange-api-settings', false, 100);
        }

        /**
         * Render and create the HTML display of dashboard page
         */
        public function openexchange_settings_callback()
        {
            // Register options page menu item and form
            $cool_options = new_cmb2_box(
                array(
                    'id' => 'ccpw_settings_page',
                    'title' => esc_html__('API Settings', 'celp1'),
                    'object_types' => array('options-page'),
                    'option_key' => 'openexchange-api-settings', // Option key and admin menu page slug
                    'menu_title' => false, // Falls back to 'title' (above)
                    'parent_slug' => 'cool-crypto-plugins', // Make options page a submenu item of the themes menu
                    'capability' => 'manage_options', // Cap required to view options-page
                    'position' => 44, // Menu position
                )
            );

            // Add fields
            $cool_options->add_field(
                array(
                    'name' => __('Enter OpenExchangeRates.org API Key', 'ccpw1'),
                    'id' => 'ccpw_openexchangerate_api_title',
                    'type' => 'title',
                )
            );

            $cool_options->add_field(
                array(
                    'name' => __('Enter API Key', 'ccpw1'),
                    'desc' => __('Display cryptocurrency prices in over <b>30 fiat currencies</b>.<br/>
					>>  <a href="https://openexchangerates.org/signup/free" target="blank">Get OpenExchangeRates.org Free API Key</a>', 'ccpw1'),
                    'id' => 'openexchangerate_api',
                    'type' => 'text',
                )
            );

            $cool_options->add_field(array(
                'name' => __('Coingecko Api Settings', 'ccpw1'),
                'id' => 'ccpw_coingecko_api_title',
                'type' => 'title',
            ));

            $cool_options->add_field(array(
                'name' => __('Enter API Key', 'ccpw1'),
                'desc' => __('Check <a href="https://support.coingecko.com/hc/en-us/articles/21880397454233-User-Guide-How-to-use-Demo-plan-API-key-" target="blank">-How to retrieve Coingecko Free API Key ?</a>', 'ccpw1'),
                'id' => 'coingecko_api',
                'type' => 'text',

            ));
            $cool_options->add_field(
                array(
                    'name' => 'Select API Cache Time',
                    'id' => 'select_cache_time',
                    'desc' => 'Trigger the API after that interval to load the most recent prices.',
                    'type' => 'select',
                    'default' => '10',
                    'options' => array(
                        '5' => __('5 Minutes', 'cmb2'),
                        '10' => __('10 Minutes', 'cmb2'),
                        '15' => __('15 Minutes', 'cmb2'),
                    ),
                    'desc' => 'Approximately 18,000 monthly API calls can be handled with a 5-minute API cache.<br>
                    Approximately 9,000 API calls per month can be managed with a 10-minute API cache.<br>
                    With a 15-minute API cache, you can support approximately 6,000 monthly API calls.',
                )
            );

            $total_hits = (get_option('cmc_coingecko_api_hits')) ? get_option('cmc_coingecko_api_hits') : 0;
            $cool_options->add_field(array(
                'name' => 'API Usage Report',
                'id' => 'ccpw_api_hit_title',
                'type' => 'title',
                'desc' => '<div class="cmb-th"></div><div class="cmb-td"><table><tr><td>Total Monthly API Calls:-</td><td> <b>10000</b></td></tr><tr><td>Used API Calls:- </td><td><b>' . ($total_hits ? $total_hits : '') . '</b></td><tr><tr><td>Remaining monthly API Calls:- </td><td><b>' . ($total_hits ? (10000 - $total_hits) : '') . '</b></td><tr><td><a href="https://www.coingecko.com/en/developers/dashboard" target="blank">Click here to view API usage details</a></td><td></td></tr></table></div>',

            ));

        }

        /**
         * Admin notice for OpenExchangeRates.org API key
         */
        public function openexchange_api_key_notice()
        {
            // Check API options
            $api_option = get_option("openexchange-api-settings");
            $openexchange_api = (!empty($api_option['openexchangerate_api'])) ? $api_option['openexchangerate_api'] : "";
            $coin_gecko_api = (!empty($api_option['coingecko_api'])) ? $api_option['coingecko_api'] : "";
            $selected_api = get_option("ccpw_options");

            // Check user capabilities
            if (!current_user_can('delete_posts')) {
                return;
            }

            // Get current user
            $current_user = wp_get_current_user();
            $user_name = $current_user->display_name;

            // Check if OpenExchange API key is missing
            if (empty($openexchange_api)) {
                ?>
				<div  class="license-warning notice notice-error is-dismissible">
					<p>Hi, <strong><?php echo ucwords($user_name); ?></strong>! Please <strong><a href="<?php echo esc_url(get_admin_url(null, 'admin.php?page=openexchange-api-settings')); ?>">enter</a></strong> Openexchangerates.org free API key for crypto to fiat price conversions.</p>
				</div>
				<?php
}

            // Check if CoinGecko API key is missing
            if (isset($selected_api['select_api']) && $selected_api['select_api'] === 'coin_gecko') {
                if (empty($coin_gecko_api)) {
                    ?>
					<div  class="license-warning notice notice-error is-dismissible">
						<p>Hi, <strong><?php echo ucwords($user_name); ?></strong>! Please <strong><a href="<?php echo esc_url(get_admin_url(null, 'admin.php?page=openexchange-api-settings')); ?>">enter</a></strong> Coingecko free API key to work with this plugin.</p>
					</div>
					<?php
}
            } elseif (!$selected_api && empty($coin_gecko_api)) {
                // Handle the case where $selected_api is false
                ?>
				<div  class="license-warning notice notice-error is-dismissible">
					<p>Hi, <strong><?php echo ucwords($user_name); ?></strong>! Please <strong><a href="<?php echo esc_url(get_admin_url(null, 'admin.php?page=openexchange-api-settings')); ?>">enter</a></strong> Coingecko free API key to work with this plugin.</p>
				</div>
				<?php
}
        }
    }

    // Initialize the main dashboard class with all required parameters
    $Openexchange = Openexchange_api_settings::init();
    $Openexchange->cool_init_hooks();
}
