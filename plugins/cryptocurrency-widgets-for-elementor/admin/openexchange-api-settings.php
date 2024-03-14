<?php

if (!class_exists('Openexchange_api_settings')) {

    class Openexchange_api_settings
    {

        private static $instance;

        public static function init()
        {
            if (empty(self::$instance)) {
                return self::$instance = new self();
            }
            return self::$instance;
        }

        public function init_hooks()
        {
            add_action('admin_notices', array($this, 'openexchange_api_key_notice'));
            add_action('admin_menu', array($this, 'openexchange_add_submenu'), 100);
            add_action('admin_init', array($this, 'openexchange_settings'));
            add_action('admin_head', array($this, 'openexchange_custom_js'));

        }

        public function openexchange_custom_js()
        {
            wp_enqueue_script('jquery');

            $script = "
            <script>
                jQuery(document).ready(function($){
                    var url = window.location.href;
                    if (url.indexOf('?page=openexchange-api-settings') > 0) {
                        $('[href=\"admin.php?page=openexchange-api-settings\"]').parent('li').addClass('current');
                    }
                    var data = $('#adminmenu #toplevel_page_cool-crypto-plugins ul li a[href=\"admin.php?page=openexchange-api-settings\"]');
                    data.each(function(e){
                        if ($(this).is(':empty')) {
                            $(this).hide();
                        }
                    });
                });
            </script>
            ";

            echo $script;
        }

        public function openexchange_add_submenu()
        {
            add_submenu_page('cool-crypto-plugins', 'API Settings', 'API Settings', 'manage_options', 'openexchange-api-settings', array($this, 'openexchange_settings_callback'), 100);
        }

        public function openexchange_settings()
        {
            register_setting('Openexchange_option_group', 'openexchange-api-settings');

            // Add a section for Open Exchange Rates API
            add_settings_section(
                'openexchange_section_id',
                'API Settings',
                function () {
                    echo '<div class="ccew_api_setting_section" style="
						padding: 1em;
						margin-top: 1px;
						margin-right: 350px;
						background: #fafafa;
						font-weight: bold;
						border: 1px solid #e9e9e9;
						">ENTER OPENEXCHANGE RATES API KEY</div>';
                },
                'openexchange-api-settings'
            );

            // Add fields for Open Exchange Rates API
            add_settings_field(
                'ccpw_openexchangerate_api_title',
                'Enter API Key',
                function () {
                    $api_option = get_option('openexchange-api-settings');
                    $apikey = !empty($api_option['openexchangerate_api']) ? $api_option['openexchangerate_api'] : '';

                    echo '<input type="text" name="openexchange-api-settings[openexchangerate_api]" size="35" value="' . esc_attr($apikey) . '"><br><br>Click Here To <a href="https://openexchangerates.org/signup/free" target="_blank">Get OpenExchangeRates.org Free API Key</a>';
                },
                'openexchange-api-settings',
                'openexchange_section_id'
            );
            // Add a section for Coingecko API
            add_settings_section(
                'coingecko_section_id',
                '',
                function () {
                    echo '<div class="ccew_api_setting_section" style="
						padding: 1em;
						margin-top: 1px;
						margin-right: 350px;
						background: #fafafa;
						font-weight: bold;
						border: 1px solid #e9e9e9;
						">ENTER COINGECKO API KEY</div>';
                },
                'openexchange-api-settings'
            );

            // Add fields for Coingecko API
            add_settings_field(
                'coingecko_api',
                'Enter Coingecko API Key',
                function () {
                    $api_option = get_option('openexchange-api-settings');
                    $coingecko_api = !empty($api_option['coingecko_api']) ? $api_option['coingecko_api'] : '';

                    echo '<input type="text" name="openexchange-api-settings[coingecko_api]" size="35" value="' . esc_attr($coingecko_api) . '"><br><br>Check- <a href="https://support.coingecko.com/hc/en-us/articles/21880397454233-User-Guide-How-to-use-Demo-plan-API-key-" target="_blank">How to retrieve Coingecko Free API Key?</a>';
                },
                'openexchange-api-settings',
                'coingecko_section_id'
            );

            // Add a section for cache time
            add_settings_section(
                'cache_section_id',
                '',
                '',
                'openexchange-api-settings'
            );

            add_settings_field(
                'select_cache_time',
                'Select API Cache Time',
                function () {
                    $api_option = get_option('openexchange-api-settings');
                    //$api_option = get_option( 'ccew-api-settings' );
                    $cache_time = !empty($api_option['select_cache_time']) ? $api_option['select_cache_time'] : '10';

                    echo '<select name="openexchange-api-settings[select_cache_time]">
							<option value="5" ' . selected($cache_time, '5', false) . '>5 Minutes</option>
							<option value="10" ' . selected($cache_time, '10', false) . '>10 Minutes</option>
							<option value="15" ' . selected($cache_time, '15', false) . '>15 Minutes</option>
						</select>';
                    echo '<br><br>Approximately 18,000 monthly API calls can be handled with a 5-minute API cache.
						<br>Approximately 9,000 API calls per month can be managed with a 10-minute API cache.
						<br>With a 15-minute API cache, you can support approximately 6,000 monthly API calls.';
                },
                'openexchange-api-settings',
                'cache_section_id'
            );

            add_settings_section(
                'api_usage_section_id',
                '',
                function () {
                    echo '<div class="ccew_api_setting_section" style="
						padding: 1em;
						margin-top: 1px;
						margin-right: 350px;
						background: #fafafa;
						font-weight: bold;
						border: 1px solid #e9e9e9;
						">API Usage Report</div>';
                },
                'openexchange-api-settings'
            );

            add_settings_field(
                'ccpw_api_hit_title',
                'API Usage Report',
                function () {
                    $total_hits = (get_option('cmc_coingecko_api_hits')) ? get_option('cmc_coingecko_api_hits') : 0;

                    echo '<div class="cmb-th"></div><div class="cmb-td"><table>
							<tr><td>Total Monthly API Calls:-</td><td> <b>10000</b></td></tr>
							<tr><td>Used API Calls:- </td><td><b>' . ($total_hits ? $total_hits : '') . '</b></td><tr>
							<tr><td>Remaining monthly API Calls:- </td><td><b>' . ($total_hits <= 10000 ? (10000 - $total_hits) : 0) . '</b></td><tr>
							<td>Click here to <a href="https://www.coingecko.com/en/developers/dashboard" target="_blank">view API usage details</a></td>
						</table></div>';
                },
                'openexchange-api-settings',
                'api_usage_section_id'
            );

        } //end of openexchange_settings

        public function openexchange_settings_callback()
        {?>
			<?php settings_errors();?>
			<form method="post" action="options.php">
				<?php settings_fields('Openexchange_option_group');?>
				<?php do_settings_sections('openexchange-api-settings');?>
				<?php submit_button();?>
			</form>
			<?php
}

        public function openexchange_api_key_notice()
        {
            $api_option = get_option('openexchange-api-settings');
            $openexchange_api = (!empty($api_option['openexchangerate_api'])) ? $api_option['openexchangerate_api'] : "";
            $coin_gecko_api = (!empty($api_option['coingecko_api'])) ? $api_option['coingecko_api'] : "";
            $selected_api = get_option("ccew-api-settings");

            if (!current_user_can('delete_posts') || !empty($api)) {
                return;
            }

            $current_user = wp_get_current_user();
            $user_name = $current_user->display_name;
            $ajax_url = admin_url('admin-ajax.php');

            if (empty($openexchange_api)) {
                ?>
				<div  class="license-warning notice notice-error is-dismissible">
					<p>Hi, <strong><?php echo ucwords($user_name); ?></strong>! Please <strong><a href="<?php echo esc_url(get_admin_url(null, 'admin.php?page=openexchange-api-settings')); ?>">enter</a></strong> Openexchangerates.org free API key for crypto to fiat price conversions.</p>

				</div>
				<?php
}

            // Check if "select_api" key exists in the array
            if (isset($selected_api['select_api'])) {
                // Check the value of "select_api"
                if ($selected_api['select_api'] === 'coin_gecko') {
                    if (empty($coin_gecko_api)) {
                        ?>
							<div  class="license-warning notice notice-error is-dismissible">
								<p>Hi, <strong><?php echo ucwords($user_name); ?></strong>! Please <strong><a href="<?php echo esc_url(get_admin_url(null, 'admin.php?page=openexchange-api-settings')); ?>">enter</a></strong> Coingecko free API key to work this plugin.</p>

							</div>
							<?php
}
                }
            } elseif (!$selected_api) {
                // Handle the case where $selected_api is false
                // You might want to add additional handling for this case
                if (empty($coin_gecko_api)) {
                    ?>
						<div  class="license-warning notice notice-error is-dismissible">
							<p>Hi, <strong><?php echo ucwords($user_name); ?></strong>! Please <strong><a href="<?php echo esc_url(get_admin_url(null, 'admin.php?page=openexchange-api-settings')); ?>">enter</a></strong> Coingecko free API key to work this plugin.</p>

						</div>
						<?php
}
            }
        } //end of function

    }

    $openexchange = Openexchange_api_settings::init();
    $openexchange->init_hooks();
}
