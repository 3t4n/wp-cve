<?php

if (!defined('WC_VAT_COMPLIANCE_DIR')) die('No direct access');

// Purpose: provide a central location where all relevant features can be accessed

/*
Components:

- Readiness tests
- Link to reports
- Link to settings (eventually: move settings)
- Link to tax rates
- Link to Premium
- Link to GeoIP settings, if needed + GeoIP status
*/

class WC_EU_VAT_Compliance_Control_Centre {

	private $compliance;
	
	/**
	 * Plugin constructor
	 */
	public function __construct() {
		add_action('admin_menu', array($this, 'admin_menu'));
		add_filter('woocommerce_screen_ids', array($this, 'woocommerce_screen_ids'));
		add_filter('woocommerce_reports_screen_ids', array($this, 'woocommerce_screen_ids'));
		add_action('wp_ajax_wc_eu_vat_cc', array($this, 'ajax'));
		add_action('wceuvat_background_tests', array($this, 'wceuvat_background_tests'));
		add_action('woocommerce_admin_field_wcvat_tax_classes', array($this, 'woocommerce_admin_field_wcvat_tax_classes'));
		add_action('woocommerce_admin_field_wc_vat_forbid_vatable_checkout', array($this, 'woocommerce_admin_field_wc_vat_forbid_vatable_checkout'));
		add_action('woocommerce_admin_field_wc_vat_regions', array($this, 'woocommerce_admin_field_wc_vat_regions'));

		add_action('woocommerce_admin_field_wcvat_tax_class_translations', array($this, 'woocommerce_admin_field_wcvat_tax_class_translations'));
	}
	
	/**
	 * Runs upon the WP action woocommerce_admin_field_wcvat_tax_class_translations
	 */
	public function woocommerce_admin_field_wcvat_tax_class_translations() {
	
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
			<label><?php _e('Tax class translations', 'woocommerce-eu-vat-compliance');?></label>
			</th>
			<td>
				<p><?php echo __('To deal with sales thresholds into different territories below which you can tax goods differently (e.g. sales within the EU in the year-to-date below 10,000 euros) , you can add rules here.', 'woocommerce-eu-vat-compliance').' '.__('Sales totals are calculated twice-daily and exclude tax.', 'woocommerce-eu-vat-compliance').' '.__('To use this facility, you need to set up alternative tax classes and tables in your WooCommerce settings to place relevant products in.', 'woocommerce-eu-vat-compliance').' <a href="'.admin_url('admin.php?page=wc_eu_vat_compliance_cc').'&country_mode=taxation&tab=reports&range=year_to_date&order_statuses%5B0%5D=processing&order_statuses%5B1%5D=completed&order_statuses%5B2%5D=on-hold">'.__('You can see your year-to-date sales in the "Reports" tab.', 'woocommerce-eu-vat-compliance').'</a> <a href="https://www.simbahosting.co.uk/s3/faqs/please-explain-the-tax-class-translations-feature-to-me/">'.__("Go here to see this feature's documentation.", 'woocommerce-eu-vat-compliance').'</a>';?></p>
					<?php
						if (has_action('wcvat_tax_class_translations_print_admin_ui')) {
							do_action('wcvat_tax_class_translations_print_admin_ui');
						} else {
							echo '<p><em>'.sprintf(__('This is a feature of %s.', 'woocommerce-eu-vat-compliance'), '<a href="https://www.simbahosting.co.uk/s3/product/woocommerce-eu-vat-compliance/">'.__('the premium version of this plugin', 'woocommerce-eu-vat-compliance').'</a>').'</em></p>';
						}
					?>
			</td>
		</tr>
		<?php
	}
	
	/**
	 * Runs upon the WP action woocommerce_admin_field_wcvat_tax_classes
	 */
	public function woocommerce_admin_field_wcvat_tax_classes() {

		$compliance = WooCommerce_EU_VAT_Compliance();
	
		$tax_classes = $compliance->get_tax_classes();
		$opts_classes = $compliance->get_region_vat_tax_classes(array_diff(array_keys($tax_classes), array('zero-rate')));

		$settings_link = admin_url('admin.php?page=wc-settings&tab=tax');

		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
			<label><?php _e('Tax classes used for customer place-of-supply goods', 'woocommerce-eu-vat-compliance');?></label>
			</th>
			<td>
				<p><?php echo __('Indicate all the WooCommerce tax classes for which variable-by-country (based on customer location) VAT is charged (i.e. tax classes for which the place of supply is deemed to be the customer location).', 'woocommerce-eu-vat-compliance').' <a href="'.esc_attr($settings_link).'">'.__('To create additional tax classes, go to the WooCommerce tax settings.', 'woocommerce-eu-vat-compliance').'</a> '.__('This setting allows different kinds of VAT based on different place-of-supply rules to be totalled and reported separately (the purpose of this setting is to make it easier to have a shop selling mixed goods).', 'woocommerce-eu-vat-compliance');?></p>
					<?php
						foreach ($tax_classes as $slug => $label) {
							if ('zero-rate' == $slug) continue;
							$checked = (in_array($slug, $opts_classes) || in_array('all$all', $opts_classes)) ? ' checked="checked"' : '';
							echo '<input type="checkbox"'.$checked.' id="woocommerce_vat_compliance_tax_classes_'.esc_attr($slug).'" name="woocommerce_eu_vat_compliance_tax_classes[]" value="'.$slug.'"> <label for="woocommerce_vat_compliance_tax_classes_'.esc_attr($slug).'">'.htmlspecialchars($label).'</label><br>';
						}
					?>
			</td>
		</tr>
		<?php
	}
	
	/**
	 * Runs upon the WP action woocommerce_admin_field_wc_vat_forbid_vatable_checkout
	 */
	public function woocommerce_admin_field_wc_vat_forbid_vatable_checkout() {
		
		$compliance = WooCommerce_EU_VAT_Compliance();
		
		$region_codes_and_titles = $compliance->get_vat_region_codes_and_titles();
		
		$forbid_regions = $compliance->forbid_vat_checkout_to_which_regions();
		
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label><?php _e('Forbid VAT checkout', 'woocommerce-eu-vat-compliance');?></label>
			</th>
			<td>
				<p><?php echo __("For each VAT region selected here, <strong>all</strong> check-outs by customers (whether consumer or business) in those VAT regions for orders which contain goods subject to variable-by-country VAT (whether the customer or order is otherwise exempt or not) will be forbidden.", 'woocommerce-eu-vat-compliance'); ?></p>
				<?php
				foreach ($region_codes_and_titles as $code => $title) {
					$checked = in_array($code, $forbid_regions) ? ' checked="checked"' : '';
					echo '<input type="checkbox"'.$checked.' id="woocommerce_eu_vat_compliance_forbid_vatable_checkout_'.esc_attr($code).'" name="woocommerce_eu_vat_compliance_forbid_vatable_checkout[]" value="'.esc_attr($code).'"> <label for="woocommerce_eu_vat_compliance_forbid_vatable_checkout_'.esc_attr($code).'">'.htmlspecialchars($title).'</label><br>';
				}
				?>
			</td>
		</tr>
		<?php
	}
	
	/**
	 * Runs upon the WP action woocommerce_admin_field_wc_vat_regions
	 */
	public function woocommerce_admin_field_wc_vat_regions() {

		$compliance = WooCommerce_EU_VAT_Compliance();
		
		$region_codes_and_titles = $compliance->get_vat_region_codes_and_titles();
	
		$regions = $compliance->get_vat_regions();
	
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
			<label><?php _e('VAT region(s)', 'woocommerce-eu-vat-compliance');?></label>
			</th>
			<td>
				<p><?php echo __('Choose when to count customers as being ones inside a VAT region (based on their taxable address) to which other relevant settings apply. i.e. Customers for whom your shop potentially charges or accounts for VAT.').' '.__('N.B. The EU VAT region (since 1st January 2021) does not include the UK.', 'woocommerce-eu-vat-compliance');?></p>
					<?php
						foreach ($region_codes_and_titles as $code => $title) {
							$checked = in_array($code, $regions) ? ' checked="checked"' : '';
							echo '<input type="checkbox"'.$checked.' id="woocommerce_eu_vat_compliance_vat_region_'.esc_attr($code).'" name="woocommerce_eu_vat_compliance_vat_region[]" value="'.esc_attr($code).'"> <label for="woocommerce_eu_vat_compliance_vat_region_'.esc_attr($code).'">'.htmlspecialchars($title).'</label><br>';
						}
					?>
			</td>
		</tr>
		<?php
	}
	
	/**
	 * Runs upon the WP action wp_ajax_wc_eu_vat_cc
	 */
	public function ajax() {

		if (empty($_POST) || empty($_POST['subaction']) || !isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'wc_eu_vat_nonce')) die('Security check');

		if (!current_user_can('manage_woocommerce')) die('Security check');
		
		if ('savesettings' == $_POST['subaction'] || 'savereadiness' == $_POST['subaction']) {

			if (empty($_POST['settings']) || !is_string($_POST['settings'])) die;

			parse_str($_POST['settings'], $posted_settings);
			
			$posted_settings = stripslashes_deep($posted_settings);

			if ('savereadiness' == $_POST['subaction']) {

				$save_email = empty($posted_settings['wceuvat_compliance_readiness_report_emails']) ? '' : $posted_settings['wceuvat_compliance_readiness_report_emails'];

				$tests = array();
				foreach ($posted_settings as $key => $val) {
					if (0 === strpos($key, 'wceuvat_test_')) {
						$test = substr($key, 13);
						$tests[$test] = !empty($val);
					}
				}

				update_option('wceuvat_background_tests', array(
					'email' => $save_email,
					'tests' => $tests
				));
				
				wp_clear_scheduled_hook('wceuvat_background_tests');

				if ($save_email) {
					$time_now = time();
					$day_start = $time_now - ($time_now % 86400);
					// 2:15 am. Choose a fixed time so that the event doesn't run lots of times when the settings are saved.
					$next_time = $day_start + 8100 + rand(0, 3600);
					if ($next_time < $time_now) $next_time += 86400;
					wp_schedule_event($next_time, 'daily', 'wceuvat_background_tests');
				}

				echo json_encode(array('result' => 'ok'));
				die;
			}

			$all_settings = $this->get_all_settings();

			$any_found = false;

			// Save settings
			foreach ($all_settings as $setting) {
				
				if (!is_array($setting) || empty($setting['id'])) continue;
				
				if ('euvat_tax_options_section' == $setting['type'] || 'sectionend' == $setting['type']) continue;

				if (!isset($posted_settings[$setting['id']])) {
					if (!isset($setting['value_when_missing'])) {
						//error_log("NOT FOUND: ".$setting['id']);
						continue;
					} else {
						//error_log("FOUND DEFAULT: ".$setting['id']);
						$posted_settings[$setting['id']] = $setting['value_when_missing'];
					}
				}

				$value = null;

				switch ($setting['type']) {
					case 'text';
					case 'number';
					case 'radio';
					case 'select';
					case 'multi_select_countries';
					case 'wceuvat_store_vat_number';
					case 'wcvat_vat_number_entry_overrides';
					case 'wcvat_tax_class_translations';
					$value = $posted_settings[$setting['id']];
					break;
					case 'multiselect';
					$value = $posted_settings[$setting['id']];
					if ('' === $value) $value = array();
					break;
					case 'wcvat_tax_classes';
					case 'wc_vat_regions';
					case 'wc_vat_forbid_vatable_checkout';
					case 'wc_vat_exempt_tax_classes';
					$value = array_diff($posted_settings[$setting['id']], array('0'));
					break;
					case 'textarea';
					$value = wp_kses_post(trim($posted_settings[$setting['id']]));
					break;
					case 'checkbox';
					$value = empty($posted_settings[$setting['id']]) ? 'no' : 'yes';
					break;
					default;
					error_log("Setting type not recognised/supported: ".$setting['type']);
				}

				if (!is_null($value)) {
					$any_found = true;
					update_option($setting['id'], $value);
				}

			}

			if (!$any_found) {
				echo json_encode(array('result' => 'no options found'));
				die;
			}

			echo json_encode(array('result' => 'ok'));
		} elseif ('testprovider' == $_POST['subaction'] && !empty($_POST['key']) && !empty($_POST['tocurrencies'])) {

			$providers = WooCommerce_EU_VAT_Compliance()->get_rate_providers();

			$to_currencies = $_POST['tocurrencies'];
			// Base currency
			$from_currency = get_option('woocommerce_currency');

			if (!is_array($providers) || empty($providers[$_POST['key']])) {
				echo json_encode(array('response' => 'Error: provider not found'));
				die;
			}

			$provider = $providers[$_POST['key']];

			$currency_code_options = get_woocommerce_currencies();

			$from_currency_label = $from_currency;
			if (isset($currency_code_options[$from_currency])) $from_currency_label = $currency_code_options[$from_currency]." - $from_currency";

			$response = '';
			
			foreach ($to_currencies as $to_currency) {
			
				$result = $provider->convert($from_currency, $to_currency, 10);
				$to_currency_label = $to_currency;
				if (isset($currency_code_options[$to_currency])) $to_currency_label = $currency_code_options[$to_currency]." - $to_currency";
				
				if (false === $result) {
					$response .= sprintf(__('Failed: The currency conversion (%s to %s) failed. Please check the settings, that the chosen provider provides exchange rates for your chosen currencies, and the outgoing network connectivity from your webserver.', 'woocommerce-eu-vat-compliance'), $from_currency, $to_currency)."<br>\n";
				} else {
					$response .= sprintf(__('Success: %s currency units in your shop base currency (%s) are worth %s currency units in the VAT reporting currency (%s)', 'woocommerce-eu-vat-compliance'), '10.00', $from_currency_label, $result, $to_currency_label)."<br>\n";
				}
				
			}

			echo json_encode(array('response' => $response));

		} elseif ('load_reports_tab' == $_POST['subaction']) {
			ob_start();
			do_action('wc_eu_vat_compliance_cc_tab_reports', true);
			$contents = @ob_get_contents();
			@ob_end_clean();

			echo json_encode(array(
				'result' => 'ok',
				'content' => $contents
			));
		} elseif ('export_settings' == $_POST['subaction']) {
		
			$plugin_version = WooCommerce_EU_VAT_Compliance()->get_version();

			include(ABSPATH.WPINC.'/version.php');

			$settings = $this->get_all_settings();
			
			$options = array();
			
			foreach ($settings as $setting) {
				$id = $setting['id'];
				$options[$id] = get_option($id);
			}
			
			$options['woocommerce_default_country'] = WC()->countries->get_base_country();
			
			$results = array(
				'options' => $options,
				'versions' => array(
					'wc' => defined('WOOCOMMERCE_VERSION') ? WOOCOMMERCE_VERSION : '?',
					'wc_eu_vat_compliance' => '?',
					'wp' => $wp_version
				),
			);
			
			if (!empty($plugin_version)) $results['versions']['wc_eu_vat_compliance'] = $plugin_version;
			
			echo json_encode($results);
		}

		die;

	}
	
	/**
	 * Used for deciding what settings to export, and for what settings can be saved
	 *
	 * @return Array
	 */
	private function get_all_settings() {
		$vat_settings = $this->get_settings_vat();
		$tax_settings = $this->get_settings_tax();

		$exchange_rate_providers = WooCommerce_EU_VAT_Compliance()->get_rate_providers();

		$exchange_rate_settings = $this->get_currency_settings();

		if (!empty($exchange_rate_providers) && is_array($exchange_rate_providers)) {
			foreach ($exchange_rate_providers as $key => $provider) {
				$settings = method_exists($provider, 'settings_fields') ? $provider->settings_fields() : false;
				if (is_array($settings)) {
					$exchange_rate_settings[] = $settings;
				}
			}
		}
		
		$lookup_service_settings = array();
		$number_lookup_services = WooCommerce_EU_VAT_Compliance()->get_vat_number_lookup_services();
		foreach ($number_lookup_services as $lookup_service) {
			$lookup_service_settings = array_merge($lookup_service_settings, $lookup_service->user_editable_settings_fields());
		}

		$bespoke_settings = array(
			array('type' => 'select', 'desc' => '', 'id' => 'woocommerce_eu_vat_compliance_reporting_override', 'value_when_missing' => array()),
			array('type' => 'text', 'desc' => '', 'id' => 'woocommerce_eu_vat_cart_vat_exempt_above'),
			array('type' => 'select', 'desc' => '', 'id' => 'woocommerce_eu_vat_cart_vat_exempt_above_currency'),
			array('type' => 'select', 'desc' => '', 'id' => 'woocommerce_eu_vat_cart_vat_exempt_above_countries'),
			array('type' => 'select', 'desc' => '', 'id' => 'woocommerce_eu_vat_cart_vat_exempt_based_upon'),
			array('type' => 'wcvat_tax_class_translations', 'desc' => '', 'id' => 'woocommerce_vat_compliance_tax_class_translations', 'value_when_missing' => array()),
		);
		
		return array_merge($vat_settings, $tax_settings, $exchange_rate_settings, $bespoke_settings, $lookup_service_settings);
	}
	
	public function woocommerce_settings_euvat_vat_options_end() {
		?><tr valign="top">
			<th scope="row" class="titledesc">
				<?php _e('Export settings', 'woocommerce-eu-vat-compliance');?>
			</th>
			<td class="forminp">
				<button class="button" id="vat-compliance-export-settings"><?php _e('Export settings', 'woocommerce-eu-vat-compliance');?></button>
				<img id="euvatcompliance_export_spinner" src="<?php echo esc_attr(admin_url('images/spinner.gif'));?>" style="width:18px; height: 18px;padding-left: 18px;display:none;">
				<br>
				<p><?php _e('The main use of this button is for debugging purposes - it allows a third party who does not have access to your WP dashboard to easily see/analyse/reproduce your settings.', 'woocommerce-eu-vat-compliance');?></p>
			</td>
		</tr>
		<?php
	}

	public function wceuvat_background_tests() {
		$opts = get_option('wceuvat_background_tests');

		if (!is_array($opts) || empty($opts['email']) || empty($opts['tests']) || !is_array($opts['tests'])) return;

		if (!class_exists('WC_EU_VAT_Compliance_Readiness_Tests')) require_once(WC_VAT_COMPLIANCE_DIR.'/readiness-tests.php');
		$test = new WC_EU_VAT_Compliance_Readiness_Tests();

		$results = $test->get_results($opts['tests']);

		$result_descriptions = $test->result_descriptions();

		$any_failed = false;

		$mail_body = site_url()."\r\n\r\n".__('The following readiness tests failed.', 'woocommerce-eu-vat-compliance').' '.__('For more information, or to change your configuration visit the "Readiness Tests" tab in your VAT Compliance control centre in your WordPress dashboard.', 'woocommerce-eu-vat-compliance')."\r\n\r\n";

		foreach ($results as $id => $res) {
			if (!is_array($res)) continue;
			// fail|pass|warning|?
			if ($res['result'] != 'fail') continue;
			$any_failed = true;
			$mail_body .= $res['label'].': '.$res['info']."\r\n\r\n";
		}

		if (!$any_failed) return;

		foreach (explode(',', $opts['email']) as $sendmail_addr) {

			$subject = sprintf(__('Failed VAT compliance readiness tests on %s.', 'woocommerce-eu-vat-compliance'), site_url());

			$sent = wp_mail(trim($sendmail_addr), $subject, $mail_body);

		}

	}

	/**
	 * Register our page as a WooCommerce screen. Runs on the WP filter woocommerce_screen_ids.
	 *
	 * @param Array $screen_ids
	 *
	 * @return Array
	 */
	public function woocommerce_screen_ids($screen_ids) {
		if (!in_array('woocommerce_page_wc_eu_vat_compliance_cc', $screen_ids)) $screen_ids[] = 'woocommerce_page_wc_eu_vat_compliance_cc';
		return $screen_ids;
	}

	/**
	 * Runs upon the WP action admin_menu
	 */
	public function admin_menu() {
		add_submenu_page(
			'woocommerce',
			__('VAT Compliance', 'woocommerce-eu-vat-compliance'),
			__('VAT Compliance', 'woocommerce-eu-vat-compliance'),
			'manage_woocommerce',
			'wc_eu_vat_compliance_cc',
			array($this, 'settings_page')
		);
	}

	/**
	 * Directly outputs settings page content (and registers other related hooks if necessary)
	 */
	public function settings_page() {

		$tabs = apply_filters('wc_eu_vat_compliance_cc_tabs', array(
			'settings' => __('Settings', 'woocommerce-eu-vat-compliance'),
			'readiness' => __('Readiness Report', 'woocommerce-eu-vat-compliance'),
			'reports' => __('VAT Reports', 'woocommerce-eu-vat-compliance'),
			'premium' => __('Premium', 'woocommerce-eu-vat-compliance')
		));

		$active_tab = !empty($_REQUEST['tab']) ? $_REQUEST['tab'] : 'settings';
		if ('taxes' == $active_tab || !empty($_GET['range'])) $active_tab = 'reports';

		$this->compliance = WooCommerce_EU_VAT_Compliance();

		$version = $this->compliance->get_version();
		$premium = false;

		if ($this->compliance->is_premium()) {
			$premium = true;
			$version .= ' '.__('(premium)', 'woocommerce-eu-vat-compliance');
		}

// .' - '.sprintf(__('version %s', 'woocommerce-eu-vat-compliance'), $version);
		?>
		<h1><?php echo __('European VAT Compliance', 'woocommerce-eu-vat-compliance').' '.__('for WooCommerce', 'woocommerce-eu-vat-compliance');?></h1>
		<a href="<?php echo apply_filters('wceuvat_support_url', 'https://wordpress.org/support/plugin/woocommerce-eu-vat-compliance/');?>"><?php _e('Support', 'woocommerce-eu-vat-compliance');?></a> | 
		<?php if (!$premium) {
			?><a href="https://www.simbahosting.co.uk/s3/product/woocommerce-eu-vat-compliance/"><?php _e("Premium", 'woocommerce-eu-vat-compliance');?></a> |
		<?php } ?>
		<a href="https://www.simbahosting.co.uk/s3/shop/"><?php _e('More plugins', 'woocommerce-eu-vat-compliance');?></a> |
		<a href="https://www.simbahosting.co.uk/s3/woocommerce-vat-compliance-frequently-asked-questions/"><?php _e('FAQs', 'woocommerce-eu-vat-compliance');?></a> |
		<a href="https://updraftplus.com">UpdraftPlus WordPress Backups</a> | 
		<a href="https://david.dw-perspective.org.uk"><?php _e("Lead developer's homepage",'woocommerce-eu-vat-compliance');?></a>
		- <?php _e('Version','woocommerce-eu-vat-compliance');?>: <?php echo $version; ?>
		<br>

		<?php
		// Legacy filter check (May 2023)
		if ('' !== apply_filters('wc_eu_vat_compliance_report_meta_fields', '', true) || '' !== apply_filters('wc_eu_vat_compliance_report_meta_fields', '', false)) {
			echo '<div id="message" class="error" style="max-width: 860px;"><strong>'.__('Deprecation: changes needed to your custom code', 'woocommerce-eu-vat-compliance').'</strong><br>';
			echo sprintf(__('Your site has some customised PHP code using the filter %s.', 'woocommerce-eu-vat-compliance'), '<em>wc_eu_vat_compliance_report_meta_fields</em>').' '.__('This has been deprecated. You must consult the plugin changelog and code, and update it - it will be removed in a future release, causing your customised code to stop working.', 'woocommerce-eu-vat-compliance');
			echo '</div>';
		}
		
		?>
		<h2 class="nav-tab-wrapper" id="wceuvat_tabs" style="margin: 14px 0px;">
		<?php

		foreach ($tabs as $slug => $title) {
			?>
				<a class="nav-tab <?php if($slug == $active_tab) echo 'nav-tab-active'; ?>" href="#wceuvat-navtab-<?php echo $slug;?>-content" id="wceuvat-navtab-<?php echo $slug;?>"><?php echo $title;?></a>
			<?php
		}

		echo '</h2>';

		foreach ($tabs as $slug => $title) {
			echo "<div class=\"wceuvat-navtab-content\" id=\"wceuvat-navtab-".$slug."-content\"";
			if ($slug != $active_tab) echo ' style="display:none;"';
			echo ">";

			if (method_exists($this, 'render_tab_'.$slug)) call_user_func(array($this, 'render_tab_'.$slug));

			do_action('wc_eu_vat_compliance_cc_tab_'.$slug);

			echo "</div>";
		}

		add_action('admin_footer', array($this, 'admin_footer'));
		
	}

	/**
	 * Print the HTML output for the "Premium" tab (which appears in the free version)
	 */
	private function render_tab_premium() {
		echo '<h2>'.__('Premium version', 'woocommerce-eu-vat-compliance').'</h2>';

			$tick = WC_VAT_COMPLIANCE_URL.'/images/tick.png';
			$cross = WC_VAT_COMPLIANCE_URL.'/images/cross.png';
			
			?>
			<div>
				<p>
					<span style="font-size: 115%;"><?php _e('You are currently using the free version of WooCommerce EU/UK VAT Compliance from wordpress.org.', 'woocommerce-eu-vat-compliance');?> <a href="https://www.simbahosting.co.uk/s3/product/woocommerce-eu-vat-compliance/"><?php _e('A premium version of this plugin is available at this link.', 'woocommerce-eu-vat-compliance');?></a></span>
				</p>
			</div>
			<div>
				<div style="margin-top:30px;">
				<table class="wceuvat_feat_table">
					<tr>
						<th class="wceuvat_feat_th" style="text-align:left;"></th>
						<th class="wceuvat_feat_th"><?php _e('Free version', 'woocommerce-eu-vat-compliance');?></th>
						<th class="wceuvat_feat_th"><?php _e('Premium version', 'woocommerce-eu-vat-compliance');?></th>
					</tr>
					<tr>
						<td class="wceuvat_feature_cell"><?php _e('Get it from', 'woocommerce-eu-vat-compliance');?></td>
						<td class="wceuvat_tick_cell" style="vertical-align:top; line-height: 120%; margin-top:6px; padding-top:6px;">WordPress.Org</td>
						<td class="wceuvat_tick_cell" style="padding: 6px; line-height: 120%;">
							<a href="https://www.simbahosting.co.uk/s3/product/woocommerce-eu-vat-compliance/"><strong><?php _e('Follow this link', 'woocommerce-eu-vat-compliance');?></strong></a><br>
							</td>
					</tr>
					<tr>
						<td class="wceuvat_feature_cell"><?php _e("Identify your customers' locations", 'woocommerce-eu-vat-compliance');?></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $tick;?>"></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $tick;?>"></td>
					</tr>
					<tr>
						<td class="wceuvat_feature_cell"><?php _e('Evidence is recorded in detail, ready for audit', 'woocommerce-eu-vat-compliance');?></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $tick;?>"></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $tick;?>"></td>
					</tr>
					<tr>
						<td class="wceuvat_feature_cell"><?php _e('Display prices including correct geographical VAT from the first page', 'woocommerce-eu-vat-compliance');?></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $tick;?>"></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $tick;?>"></td>
					</tr>
					<tr>
						<td class="wceuvat_feature_cell"><?php _e('Currency conversions into all required reporting currencies', 'woocommerce-eu-vat-compliance');?></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $tick;?>"></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $tick;?>"></td>
					</tr>
					<tr>
						<td class="wceuvat_feature_cell"><?php _e('Live exchange rates', 'woocommerce-eu-vat-compliance');?></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $tick;?>"></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $tick;?>"></td>
					</tr>
					<tr>
						<td class="wceuvat_feature_cell"><?php _e("Quick entering of each country's VAT rates", 'woocommerce-eu-vat-compliance');?></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $tick;?>"></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $tick;?>"></td>
					</tr>
					<tr>
						<td class="wceuvat_feature_cell"><?php _e('Advanced dashboard reports', 'woocommerce-eu-vat-compliance');?></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $tick;?>"></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $tick;?>"></td>
					</tr>
					<tr>
						<td class="wceuvat_feature_cell"><?php _e('Option to forbid EU and/or UK sales if VAT is chargeable', 'woocommerce-eu-vat-compliance');?></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $tick;?>"></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $tick;?>"></td>
					</tr>
					<tr>
						<td class="wceuvat_feature_cell"><?php _e('Shop can trade to and from any combination of EU/UK/outside', 'woocommerce-eu-vat-compliance');?></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $tick;?>"></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $tick;?>"></td>
					</tr>
					<tr>
						<td class="wceuvat_feature_cell"><?php _e('Central control panel', 'woocommerce-eu-vat-compliance');?></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $tick;?>"></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $tick;?>"></td>
					</tr>
					<tr>
						<td class="wceuvat_feature_cell"><?php _e('Mixed shops (i.e. handle goods under traditional supplier rules also)', 'woocommerce-eu-vat-compliance');?></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $tick;?>"></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $tick;?>"></td>
					</tr>
					<tr>
						<td class="wceuvat_feature_cell"><?php _e('Extra text on invoices (e.g. VAT notices for business customers)', 'woocommerce-eu-vat-compliance');?></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $tick;?>"></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $tick;?>"></td>
					</tr>
					<tr>
						<td class="wceuvat_feature_cell"><?php _e('Refund support', 'woocommerce-eu-vat-compliance');?></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $tick;?>"></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $tick;?>"></td>
					</tr>
					<tr>
						<td class="wceuvat_feature_cell"><?php _e('Exempt business customers (i.e. B2B) from VAT (validation via VIES/HMRC)', 'woocommerce-eu-vat-compliance');?></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $cross;?>"></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $tick;?>"></td>
					</tr>
					<tr>
						<td class="wceuvat_feature_cell"><?php _e('Add B2B VAT numbers to invoices', 'woocommerce-eu-vat-compliance');?></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $cross;?>"></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $tick;?>"></td>
					</tr>
					<tr>
						<td class="wceuvat_feature_cell"><?php _e('Option to allow B2B sales only', 'woocommerce-eu-vat-compliance');?></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $cross;?>"></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $tick;?>"></td>
					</tr>
					<tr>
						<td class="wceuvat_feature_cell"><?php _e('Option to collect VAT numbers from all customers', 'woocommerce-eu-vat-compliance');?></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $cross;?>"></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $tick;?>"></td>
					</tr>
					<tr>
						<td class="wceuvat_feature_cell"><?php _e('Multiple VAT numbers in different regions with dynamic replacement on invoices', 'woocommerce-eu-vat-compliance');?></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $cross;?>"></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $tick;?>"></td>
					</tr>
					<tr>
						<td class="wceuvat_feature_cell"><?php _e('Different VAT number/exemption/requirement policies in different regions', 'woocommerce-eu-vat-compliance');?></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $cross;?>"></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $tick;?>"></td>
					</tr>
					<tr>
						<td class="wceuvat_feature_cell"><?php _e('Partial VAT exemption - chosen tax classes only', 'woocommerce-eu-vat-compliance');?></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $cross;?>"></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $tick;?>"></td>
					</tr>
					<tr>
						<td class="wceuvat_feature_cell"><?php _e('Manually mark chosen customers as VAT-exempt', 'woocommerce-eu-vat-compliance');?></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $cross;?>"></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $tick;?>"></td>
					</tr>
					<tr>
						<td class="wceuvat_feature_cell"><?php _e('Order VAT exemptions based on countries and values (supporting 2021- UK/EU import rules)', 'woocommerce-eu-vat-compliance');?></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $cross;?>"></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $tick;?>"></td>
					</tr>
					<tr>
						<td class="wceuvat_feature_cell"><?php _e('Change product taxation based on year-to-date sales (supporting July 2021- EU cross-border threshold rules)', 'woocommerce-eu-vat-compliance');?></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $cross;?>"></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $tick;?>"></td>
					</tr>
					<tr>
						<td class="wceuvat_feature_cell"><?php _e('CSV (i.e. spreadsheet) download of comprehensive information on all orders', 'woocommerce-eu-vat-compliance');?></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $cross;?>"></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $tick;?>"></td>
					</tr>
					<tr>
						<td class="wceuvat_feature_cell"><?php _e('Optionally resolve location conflicts via self-certification', 'woocommerce-eu-vat-compliance');?></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $cross;?>"></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $tick;?>"></td>
					</tr>
					<tr>
						<td class="wceuvat_feature_cell"><?php _e('Show VAT in multiple currencies upon invoices', 'woocommerce-eu-vat-compliance');?></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $cross;?>"></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $tick;?>"></td>
					</tr>
					<tr>
						<td class="wceuvat_feature_cell"><?php _e('Support for the official WooCommerce Subscriptions extension', 'woocommerce-eu-vat-compliance');?></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $cross;?>"></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $tick;?>"></td>
					</tr>
					<tr>
						<td class="wceuvat_feature_cell"><?php _e('Support for the Subscriptio/Subscriben extensions', 'woocommerce-eu-vat-compliance');?></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $cross;?>"></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $tick;?>"></td>
					</tr>
					<tr>
						<td class="wceuvat_feature_cell"><?php _e('Helps to fund continued development', 'woocommerce-eu-vat-compliance');?></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $cross;?>"></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $tick;?>"></td>
					</tr>
					<tr>
						<td class="wceuvat_feature_cell"><?php _e('Personal support', 'woocommerce-eu-vat-compliance');?></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $cross;?>"></td>
						<td class="wceuvat_tick_cell"><img src="<?php echo $tick;?>"></td>
					</tr>
				</table>
				<p><em><?php echo __('All invoicing features are in conjunction with the free WooCommerce PDF invoices and packing slips plugin (its Premium counterpart is also supported).', 'woocommerce-eu-vat-compliance');?> - <a href="https://wordpress.org/plugins/woocommerce-pdf-invoices-packing-slips/"><?php _e('link', 'woocommerce-eu-vat-compliance');?></a></em></p>
				</div>
			</div>
			<?php
			
		add_action('admin_footer', array($this, 'admin_footer_premiumcss'));

	}

	/**
	 * Called by the WP action admin_footer by the free/Premium comparison table
	 */
	public function admin_footer_premiumcss() {
		?>
		<style type="text/css">
			ul.wceuvat_premium_description_list {
				list-style: disc inside;
			}
			ul.wceuvat_premium_description_list li {
				display: inline;
			}
			ul.wceuvat_premium_description_list li::after {
				content: " | ";
			}
			ul.wceuvat_premium_description_list li.last::after {
				content: "";
			}
			.wceuvat_feature_cell{
					background-color: #F7D9C9 !important;
					padding: 5px 10px 5px 10px;
			}
			.wceuvat_feat_table, .wceuvat_feat_th, .wceuvat_feat_table td{
					border: 1px solid black;
					border-collapse: collapse;
					font-size: 120%;
					background-color: white;
			}
			.wceuvat_feat_th {
				padding: 6px;
			}
			.wceuvat_tick_cell{
					padding: 4px;
					text-align: center;
			}
			.wceuvat_tick_cell img{
					margin: 4px 0;
					height: 24px;
			}
		</style>
		<?php
	}

	/**
	 * Runs upon the WP action woocommerce_admin_field_euvat_tax_options_section
	 *
	 * @param Array $value
	 */
	public function woocommerce_admin_field_euvat_tax_options_section($value) {
		if (!empty($value['title'])) {
			echo '<h3>'.esc_html($value['title']).'</h3>';
		}
		echo '<div>';
		if (!empty($value['desc'])) {
			echo wpautop(wptexturize(wp_kses_post($value['desc'])));
		}
		echo '<table class="form-table">'."\n\n";
		if (!empty($value['id'])) {
			do_action('woocommerce_settings_'.sanitize_title($value['id']));
		}
	}

	public function woocommerce_settings_euvat_vat_options_after() {
		echo '</div>';
	}

	public function woocommerce_settings_euvat_tax_options_after() {
		echo '</div>';
	}

	public function get_settings_vat() {
		$vat_settings = array(
			array(
				'title' => __('WooCommerce VAT settings (new settings from the VAT compliance plugin)', 'woocommerce-eu-vat-compliance'),
				'type' => 'euvat_tax_options_section',
				'desc' => __('', 'woocommerce-eu-vat-compliance'),
				'id' => 'euvat_vat_options'),
		);

		$get_from = array('WC_EU_VAT_Compliance', 'WC_EU_VAT_Compliance_VAT_Number');

		foreach ($get_from as $name) {
			if (false == ($class = WooCommerce_EU_VAT_Compliance($name))) continue;
			if (empty($class->settings)) continue;
			$vat_settings = array_merge($vat_settings, $class->settings);
		}
		
		$vat_settings[] = array('type' => 'sectionend', 'id' => 'euvat_vat_options' );

		static $action_added = false;
		if (!$action_added) {
			add_action('woocommerce_settings_euvat_vat_options_end', array($this, 'woocommerce_settings_euvat_vat_options_end'));
			$action_added = true;
		}
		
		return $vat_settings;
	}

	private function get_settings_vat_number_lookups() {
	
		return array(
			array('title' => __('Settings regarding how to look up VAT numbers.', 'woocommerce-eu-vat-compliance'), 'type' => 'euvat_vat_number_lookup_options_section','desc' => '', 'id' => 'euvat_vat_number_lookup_options' )
		);

	
	}
	
	/**
	 * Get settings for the 'Other WooCommerce tax options' section
	 *
	 * @return Array
	 */
	public function get_settings_tax() {
		// From class-wc-settings-tax.php
		$tax_settings = array(

			array('title' => __('Other WooCommerce tax options potentially relevant for VAT compliance', 'woocommerce-eu-vat-compliance'), 'type' => 'euvat_tax_options_section', 'desc' => '<em>'.__('These settings can be viewed here, but to change them you must go to the WooCommerce settings page, because these are options from the WooCommerce core plugin.', 'woocommerce-eu-vat-compliance').'</em>', 'id' => 'euvat_tax_options'),

			array(
				'title'   => __('Enable Taxes', 'woocommerce-eu-vat-compliance'),
				'desc'    => __('Enable taxes and tax calculations', 'woocommerce-eu-vat-compliance'),
				'id'      => 'woocommerce_calc_taxes',
				'default' => 'no',
				'type'    => 'checkbox'
			),

			array(
				'title'    => __('Prices Entered With Tax', 'woocommerce-eu-vat-compliance'),
				'id'       => 'woocommerce_prices_include_tax',
				'default'  => 'no',
				'type'     => 'radio',
				'desc_tip' =>  __('This option is important as it will affect how you input prices. Changing it will not update existing products.', 'woocommerce-eu-vat-compliance'),
				'options'  => array(
					'yes' => __('Yes, I will enter prices inclusive of tax', 'woocommerce-eu-vat-compliance'),
					'no'  => __('No, I will enter prices exclusive of tax', 'woocommerce-eu-vat-compliance')
				),
			),

			array(
				'title'    => __('Calculate Tax Based On:', 'woocommerce-eu-vat-compliance'),
				'id'       => 'woocommerce_tax_based_on',
				'desc_tip' =>  __('This option determines which address is used to calculate tax.', 'woocommerce-eu-vat-compliance'),
				'default'  => 'shipping',
				'type'     => 'select',
				'options'  => array(
					'shipping' => __('Customer shipping address', 'woocommerce-eu-vat-compliance'),
					'billing'  => __('Customer billing address', 'woocommerce-eu-vat-compliance'),
					'base'     => __('Shop base address', 'woocommerce-eu-vat-compliance')
				),
			),
		);

		$tax_settings[] = array(
			'title'    => __('Default Customer Address:', 'woocommerce-eu-vat-compliance'),
			'id'       => 'woocommerce_default_customer_address',
			'desc_tip' =>  __('This option determines the customers default address (before they input their details).', 'woocommerce-eu-vat-compliance'),
			'default'  => 'geolocation',
			'type'     => 'select',
			'class'    => 'wc-enhanced-select',
			'options'  => array(
				''            => __('No address', 'woocommerce-eu-vat-compliance'),
				'base'        => __('Shop base address', 'woocommerce-eu-vat-compliance'),
				'geolocation' => __('Geolocate address', 'woocommerce-eu-vat-compliance'),
			),
		);

		$tax_settings = array_merge($tax_settings, array(
			array(
				'title'   => __('Display prices in the shop:', 'woocommerce-eu-vat-compliance'),
				'id'      => 'woocommerce_tax_display_shop',
				'default' => 'excl',
				'type'    => 'select',
				'options' => array(
					'incl'   => __('Including tax', 'woocommerce-eu-vat-compliance'),
					'excl'   => __('Excluding tax', 'woocommerce-eu-vat-compliance'),
				)
			),

			array(
				'title'   => __('Price display suffix:', 'woocommerce-eu-vat-compliance'),
				'id'      => 'woocommerce_price_display_suffix',
				'default' => '',
				'class' => 'widefat',
				'type'    => 'text',
				'desc'    => __('Define text to show after your product prices. This could be, for example, "inc. Vat" to explain your pricing. You can also have prices substituted here using one of the following: <code>{price_including_tax}, {price_excluding_tax}</code>. Content wrapped in-between <code>{iftax}</code> and <code>{/iftax}</code> will display only if there was tax; within that, <code>{country}</code> will be replaced by the name of the country used to calculate tax.', 'woocommerce-eu-vat-compliance' ).' '.__('Use <code>{country_with_brackets}</code> to show the country only if the item had per-country varying VAT, and to show brackets around the country.', 'woocommerce-eu-vat-compliance'),
			),

			array(
				'title'   => __('Display prices during cart/checkout:', 'woocommerce-eu-vat-compliance'),
				'id'      => 'woocommerce_tax_display_cart',
				'default' => 'excl',
				'type'    => 'select',
				'options' => array(
					'incl'   => __('Including tax', 'woocommerce-eu-vat-compliance'),
					'excl'   => __('Excluding tax', 'woocommerce-eu-vat-compliance'),
				),
				'autoload'      => false
			),

			array(
				'title'   => __('Display tax totals:', 'woocommerce-eu-vat-compliance'),
				'id'      => 'woocommerce_tax_total_display',
				'default' => 'itemized',
				'type'    => 'select',
				'options' => array(
					'single'     => __('As a single total', 'woocommerce-eu-vat-compliance'),
					'itemized'   => __('Itemized', 'woocommerce-eu-vat-compliance'),
				),
				'autoload' => false
			),

			array('type' => 'sectionend', 'id' => 'euvat_tax_options'),

		));

		return $tax_settings;
	}

	/**
	 * Called internally to render the 'Settings' tab
	 */
	private function render_tab_settings() {
		// echo '<h2>'.__('Settings', 'woocommerce-eu-vat-compliance').'</h2>';

		// echo '<p><em>'.__('Many settings below can also be found in other parts of your WordPress dashboard; they are brought together here also for convenience.', 'woocommerce-eu-vat-compliance').'</em></p>';

		$tax_settings_link = admin_url('admin.php?page=wc-settings&tab=tax');

		// echo '<h3>'.__('Tax settings', 'woocommerce-eu-vat-compliance').'</h3><p><a href="'.$tax_settings_link.'">'.__('Find these in the "Tax" section of the WooCommerce settings.', 'woocommerce-eu-vat-compliance').'</a></p>';

		$register_actions = array('woocommerce_admin_field_euvat_tax_options_section', 'woocommerce_settings_euvat_tax_options_after', 'woocommerce_settings_euvat_vat_options_after');
		foreach ($register_actions as $action) {
			add_action($action, array($this, $action));
		}

		// __('Find these in the "Tax" section of the WooCommerce settings.', 'woocommerce-eu-vat-compliance')

		$vat_settings = $this->get_settings_vat();
		$tax_settings = $this->get_settings_tax();

		wp_enqueue_script('jquery-ui-accordion');

		echo '<div style="width:960px; margin-bottom: 8px;" id="wceuvat_settings_accordion">';

		// VAT settings
		woocommerce_admin_fields($vat_settings);
		
		// Currency conversion
		echo '<h3>'.__('VAT reporting currencies', 'woocommerce-eu-vat-compliance').'</h3><div>';
		$this->currency_conversion_section();
		echo '</div>';

		// VAT number lookups. The DOM ID is to help locate the section after.
		echo '<h3>'.__('VAT number lookups', 'woocommerce-eu-vat-compliance').'</h3><div>';
		$this->vat_number_lookups_section();
		echo '</div>';
		
		woocommerce_admin_fields($tax_settings);

		// Tax tables
		echo '<h3>'.__('Tax tables (set up tax rates for each country)', 'woocommerce-eu-vat-compliance').'</h3>';

		echo '<div>';

		echo '<p>'.__('N.B. Tax tables and calculation of taxes is part of WooCommerce core. If you think that WooCommerce is calculating taxes incorrectly, then you will need to raise an issue in a WooCommerce core plugin support forum (rather than via support for this plugin, which can only cover plugin issues).', 'woocommerce-eu-vat-compliance').'</p>';
		
		echo '<p><a href="https://ec.europa.eu/taxation_customs/business/vat/telecommunications-broadcasting-electronic-services/vat-rates_en">'.__('Official EU documentation on current VAT rates (link last confirmed September 2022).', 'woocommerce-eu-vat-compliance').'</a></p>';
		
		echo '<p><a href="https://www.gov.uk/browse/tax/vat">'.__('Official UK (HMRC) documentation on VAT.', 'woocommerce-eu-vat-compliance').'</a></p>';
		
		// Domain is WooCommerce, since this string is used there
		$tax_rates = array(
			'standard' => __('Standard', 'woocommerce'),
		);
		
		// Get tax classes and display as links.
		$tax_classes = array_merge($tax_rates, WC_Tax::get_tax_classes());
		
		foreach ($tax_classes as $class) {
		
			echo '<p><strong>'.sprintf(__('%s tax table', 'woocommerce-eu-vat-compliance'), $class).'</strong> - <a href="'.$tax_settings_link.'&section='.sanitize_title($class).'">'.__('Follow this link to see or edit this tax table', 'woocommerce-eu-vat-compliance').'</a></p>';
		
		}

		echo '</div></div>';

		?>
		<button style="margin-left: 4px;" id="wc_euvat_cc_settings_save" class="button button-primary"><?php _e('Save Settings', 'woocommerce-eu-vat-compliance');?></button>
		<script>

			var wceuvat_query_leaving = false;

			window.onbeforeunload = function(e) {
				if (wceuvat_query_leaving) {
					var ask = "<?php echo esc_js(__('You have unsaved settings.', 'woocommerce-eu-vat-compliance'));?>";
					e.returnValue = ask;
					return ask;
				}
			}
			
			jQuery(function($) {
			
				$("#wceuvat_settings_accordion").accordion({collapsible: true, active: false, animate: 100, heightStyle: "content" });
				$("#wceuvat_settings_accordion input, #wceuvat_settings_accordion textarea, #wceuvat_settings_accordion select").on('change', function(info) {
					if (!info.target || 'wc_eu_vat_vat_number_test' != $(info.target).attr('id')) {
						wceuvat_query_leaving = true;
					}
				});
				
				$('#wc_euvat_cc_settings_save').on('click', function() {
					wceuvat_savesettings('savesettings');
				});
				
				<?php
					$wc_countries = new WC_Countries;
					echo 'var country_list = '.json_encode($wc_countries->get_countries()).";\n";
				?>
				
				var reporting_currency_index = 0;
				
				/**
				 * Get the HTML for a table row with a country selector in it 
				 *
				 * @param Integer index
				 * @param String  selected_value
				 *
				 * @return String
				 */
				function country_selector_row(index, selected_value) {
				
					index = index.toString();
				
					var dom_id = 'wc_euvat_country_selector_'+index;
				
					var html = '<tr class="titledesc" scope="row"><th class="titledesc" scope="row"><label for="'+dom_id+'"><?php _e('Country', 'woocommerce-eu-vat-compliance');?></label></th><td class="forminp forminp-select">'+"\n";
				
					html += '<select id="'+dom_id+'" name="woocommerce_eu_vat_compliance_reporting_override['+index+'][country]" class="wc_euvat_country_selector">';
				
					var countries = country_list;
					
					for (const code in countries) {
						var selected = (code == selected_value) ? ' selected="selected"' : '';
						html += '<option value="'+code+'"'+selected+'>'+countries[code]+'</option>';
					}
					
					html += '</select>';
				
					html += "\n</td></tr>";
				
					return html;
				
				}
				
				// On init, add the currency selector for the "exemption based on value" box and other JavaScript functions.
				<?php WooCommerce_EU_VAT_Compliance()->enqueue_admin_js(); ?>
				
				/**
				 * Get the HTML for a table row with a currency selector in it 
				 *
				 * @param Integer index
				 * @param String  selected_value
				 *
				 * @return String
				 */
				function currency_selector_row(index, selected_value) {
				
					index = index.toString();
				
					var dom_id = 'wc_euvat_currency_selector_'+index;
				
					var html = '<tr class="titledesc" scope="row"><th class="titledesc" scope="row"><label for="'+dom_id+'"><?php _e('Currency', 'woocommerce-eu-vat-compliance');?></label></th><td class="forminp forminp-select">'+"\n";
				
					html += wc_vat_compliance_currency_selector_dropdown(dom_id, 'woocommerce_eu_vat_compliance_reporting_override['+index+'][currency]', selected_value);
				
					html += "</td></tr>\n";
				
					return html;
				
				}
			
				<?php
					$exchange_rate_providers = WooCommerce_EU_VAT_Compliance()->get_rate_providers();

					$exchange_rate_options = array();
					foreach ($exchange_rate_providers as $key => $provider) {
						$info = $provider->info();
						$exchange_rate_options[$key] = $info['title'];
					}
					
					echo 'var exchange_rate_provider_list = '.json_encode($exchange_rate_options).";\n";
				?>
				
				/**
				 * Get the HTML for a table row with an exchange rate provider selector in it 
				 *
				 * @param Integer index
				 * @param String  selected_value
				 *
				 * @return String
				 */
				function exchange_rate_provider_row(index, selected_value) {
				
					index = index.toString();
				
					var dom_id = 'wc_euvat_provider_selector_'+index;
				
					var html = '<tr class="titledesc" scope="row"><th class="titledesc" scope="row"><label for="'+dom_id+'"><?php _e('Exchange rate provider', 'woocommerce-eu-vat-compliance');?></label></th><td class="forminp forminp-select">'+"\n";
				
					html += '<select id="'+dom_id+'" name="woocommerce_eu_vat_compliance_reporting_override['+index+'][provider]" class="wc_euvat_provider_selector">';
				
					var providers = exchange_rate_provider_list;
					
					for (const provider_id in providers) {
						var selected = (provider_id == selected_value) ? ' selected="selected"' : '';
						html += '<option value="'+provider_id+'"'+selected+'>'+providers[provider_id]+'</option>';
					}
					
					html += '</select>';
				
					html += "\n</td></tr>";
				
					return html;
				
				}
				
				/**
				 * Get the HTML for a table row for deleting the row
				 *
				 * @return String
				 */
				function delete_row() {
				
					var html = '<tr class="titledesc" scope="row"><td></td><td><a href="#" class="wcvat_delete_table_row"><?php _e('Delete this over-ride...', 'woocommerce-eu-vat-compliance');?></td></tr>'+"\n";
					
					return html;
				
				}
				
				$('#wceuvat-reporting-currency-overrides').on('click', '.wcvat_delete_table_row', function() {
					$(this).parents('.wceuvat-reporting-currency-override').first().slideUp(function() { $(this).remove(); wceuvat_show_correct_providers(); });
					return false;
				});
				
				var existing_overrides = $('#wceuvat-new-reporting-override').data('existing-overrides');
				if ('object' == typeof existing_overrides) {
					for (const i in existing_overrides) {
					
						var new_currency = '<table class="form-table wceuvat-reporting-currency-override">' + country_selector_row(reporting_currency_index, existing_overrides[i].country) + currency_selector_row(reporting_currency_index, existing_overrides[i].currency) + exchange_rate_provider_row(reporting_currency_index, existing_overrides[i].provider) + delete_row() + '</table>';
						
						$('#wceuvat-reporting-currency-overrides').append(new_currency);
						}
						
						reporting_currency_index++;
				}
				
				$('#wceuvat-new-reporting-override').on('click', function() {
				
					reporting_currency_index++;
					
					var new_currency = '<table class="form-table wceuvat-reporting-currency-override">' + country_selector_row(reporting_currency_index) + currency_selector_row(reporting_currency_index) + exchange_rate_provider_row(reporting_currency_index) + delete_row() + '</table>';
					
					$('#wceuvat-reporting-currency-overrides').append(new_currency);
					
					wceuvat_show_correct_providers();
				
					return false;
				});
				
				$('#wc_eu_vat_vat_number_settings button.vat-number-test-go').on('click', function() {
				
					$('#euvatcompliance_vat_number_spinner').show();
				
					var lookup_service = $(this).data('service');
					
					var vat_number = $('#wc_eu_vat_vat_number_test').val();
					
					if ('' == vat_number) { return false; }
					
					var lookup_info = {
						action: 'wceuvat_cc_vat_number',
						_wpnonce: '<?php echo wp_create_nonce('wc_eu_vat_cc_nonce'); ?>',
						lookup_service: lookup_service,
						vat_number: vat_number
					};
					
					$.post(ajaxurl, lookup_info, function(response) {
					
						$('#euvatcompliance_vat_number_spinner').hide();
					
						var result = 'unknown';

						try {
							var resp = JSON.parse(response);

							if (resp.hasOwnProperty('result')) {
								$('#wc_eu_vat_vat_number_test_result').html('<hr>'+resp.result);
							} else {
								alert("<?php esc_js(__('An unexpected error occurred; please check the browser console for more information.', 'woocommerce-eu-vat-compliance')) ?>");
								console.log('Validation result not understood; response dump follows');
								console.log(resp);
							}
						} catch (err) {
							alert("<?php esc_js(__('An unexpected error occurred; please check the browser console for more information.', 'woocommerce-eu-vat-compliance')) ?>");
							console.log('Exception occurred when parsing result (follows)');
							console.log(err);
							console.log(response);
						}
						
					});
				
					return false;
				
				});
				
				$('#vat-compliance-export-settings').on('click', function(e) {
					e.preventDefault();
					$('#euvatcompliance_export_spinner').show();
					$.post(ajaxurl, {
						action: 'wc_eu_vat_cc',
						subaction: 'export_settings',
						_wpnonce: '<?php echo esc_js(wp_create_nonce('wc_eu_vat_nonce'));?>',
					}, function(response) {
						$('#euvatcompliance_export_spinner').hide();
						try {
							resp = JSON.parse(response);
							
							console.log("euvatcompliance: export_settings: result follows");
							console.log(resp);
							
							mime_type = 'application/json';
							var stuff = response;
							var link = document.body.appendChild(document.createElement('a'));
							link.setAttribute('download', 'vat-compliance-export-settings.json');
							link.setAttribute('style', "display:none;");
							link.setAttribute('href', 'data:' + mime_type  +  ';charset=utf-8,' + encodeURIComponent(stuff));
							link.click(); 

						} catch(err) {
							console.log("Unexpected response (export_settings 2): "+response);
							console.log(err);
						}
					});
				});
				
			});
		</script>
		<style type="text/css">
			#wceuvat_settings_accordion .ui-accordion-content, #wceuvat_settings_accordion .ui-widget-content, #wceuvat_settings_accordion h3 { background: transparent !important; }
			.ui-widget {font-family: inherit !important; }
			#wceuvat_settings_accordion .select_all.button, #wceuvat_settings_accordion .select_none.button { display: none; }
			button.wc_eu_vat_test_provider_button { margin: 0px 10px !important; position: relative; top: -4px;}
			.wceuvat-reporting-currency-override { border: 1px dotted; margin: 8px 0; }
			.wceuvat-reporting-currency-override th { padding-left: 8px; }
			.wcvat-number-entry-override, .wcvat-value-based-exemption, .wc_vat_tax_class_translation { border: 1px dotted; margin: 8px 0; padding: 4px;}
			.wc_vat_tax_class_translation input.threshold_value { width: 92px; }
			select.exempt_based_upon { width: 200px; }
		</style>

		<?php
	}
	
	/**
	 * Get information on the currency / exchange rate settings
	 *
	 * @return Array
	 */
	private function get_currency_settings() {

		$base_currency = get_option('woocommerce_currency');
		$base_currency_symbol = get_woocommerce_currency_symbol($base_currency);

		$currency_code_options = WooCommerce_EU_VAT_Compliance()->get_currency_code_options();

		$exchange_rate_providers = WooCommerce_EU_VAT_Compliance()->get_rate_providers();

		$exchange_rate_options = array();
		foreach ($exchange_rate_providers as $key => $provider) {
			$info = $provider->info();
			$exchange_rate_options[$key] = $info['title'];
		}

		$settings = apply_filters('wc_euvat_compliance_exchange_settings', array(
			array(
				'title'    => __('Default reporting currency', 'woocommerce-eu-vat-compliance'),
				'desc'     => __("When an order is made with a taxation country for which you have not provided a specific override below, exchange rate information will be added to the order, allowing all amounts to be converted into the currency chosen here. This is necessary if orders may be made in a different currency than a currency which you are required to report VAT in.", 'woocommerce-eu-vat-compliance'),
				'id'       => 'woocommerce_eu_vat_compliance_vat_recording_currency',
				'css'      => 'min-width:350px;',
				'default'  => $base_currency,
				'type'     => 'select',
				'class'    => 'chosen_select',
				'desc_tip' =>  true,
				'options'  => $currency_code_options
			),

			array(
				'title'    => __('Exchange rate provider', 'woocommerce-eu-vat-compliance'),
				'id'       => 'woocommerce_eu_vat_compliance_exchange_rate_provider',
				'css'      => 'min-width:350px;',
				'default'  => 'ecb',
				'type'     => 'select',
				'class'    => 'chosen_select',
				'desc_tip' =>  true,
				'options'  => $exchange_rate_options
			),
		));
		
		return $settings;
	}
	
	/**
	 * Output the VAT number lookups section of the settings page
	 */
	private function vat_number_lookups_section() {

		//echo '<p>'.sprintf(__('', 'woocommerce-eu-vat-compliance'), $currency_label).' '.__('If using a currency other than your base currency, then you must configure an exchange rate provider.', 'woocommerce-eu-vat-compliance').'</p>';

		//echo '<table class="form-table">'. "\n\n";

		//woocommerce_admin_fields($this->get_settings_vat_number_lookups());

		//echo '</table>';
		
		if (has_action('wc_vat_compliance_vat_number_settings')) {
			do_action('wc_vat_compliance_vat_number_settings');
		} else {
			echo '<p>'.sprintf(__('VAT number lookups are a feature of %s.', 'woocommerce-eu-vat-compliance'), '<a href="https://www.simbahosting.co.uk/s3/product/woocommerce-eu-vat-compliance/">'.__('the premium version of this plugin', 'woocommerce-eu-vat-compliance').'</a>').'</p>';
		}
		
	}
	
	/**
	 * Output the currency conversion section of the settings page
	 */
	public function currency_conversion_section() {

		$base_currency = get_option('woocommerce_currency');
		$base_currency_symbol = get_woocommerce_currency_symbol($base_currency);
		$currency_code_options = get_woocommerce_currencies();
		$currency_label = $base_currency;
		if (isset($currency_code_options[$base_currency])) $currency_label = $currency_code_options[$base_currency]." ($base_currency)";

		echo '<p>'.sprintf(__('Set the currencies that you have to use when making VAT reports. If any are not the same as your base currency (%s), then when orders are placed, the exchange rate will be recorded as part of the order information, allowing accurate VAT reports to be made.', 'woocommerce-eu-vat-compliance'), $currency_label).' '.__('If using a currency other than your base currency, then you must configure an exchange rate provider.', 'woocommerce-eu-vat-compliance').'</p>';

		echo '<p>'.__('N.B. If you have a need for a specific provider, then please let us know.', 'woocommerce-eu-vat-compliance').'</p>';

		echo '<table class="form-table">'. "\n\n";

		$currency_settings = $this->get_currency_settings();

		woocommerce_admin_fields($currency_settings);
		
		$existing_overrides = get_option('woocommerce_eu_vat_compliance_reporting_override', array());
		// Multiple recordings for the same country is not supported
		$already_seen = array();
		foreach ($existing_overrides as $i => $override) {
			if (in_array($override['country'], $already_seen)) {
				error_log("Having multiple recording-currency over-rides for the same country (".$override['country'].") is not supported");
				unset($existing_overrides[$i]);
			} else {
				$already_seen[] = $override['country'];
			}
		}
		
		echo '<tr><td colspan="2"><a id="wceuvat-new-reporting-override" href="#" data-existing-overrides="'.esc_attr(json_encode($existing_overrides)).'">'.__('Add a reporting currency over-ride for purchases with a specified country of supply...', 'woocommerce-eu-vat-compliance').'</a></td></tr>';

		echo '</table>';

		echo '<div class="form-table" id="wceuvat-reporting-currency-overrides"></div>';

		$exchange_rate_providers = WooCommerce_EU_VAT_Compliance()->get_rate_providers();

		foreach ($exchange_rate_providers as $key => $provider) {
			$settings = method_exists($provider, 'settings_fields') ? $provider->settings_fields() : false;
			if (!is_string($settings) && !is_array($settings)) continue;
			$info = $provider->info();
			echo '<div id="wceuvat-rate-provider_container_'.$key.'" class="wceuvat-rate-provider_container wceuvat-rate-provider_container_'.$key.'">';
			echo '<h4 style="padding-bottom:0px; margin-bottom:0px;">'.__('Exchange rate provider', 'woocommerce-eu-vat-compliance').': '.htmlspecialchars($info['title']).'</h4>';
			echo '<p style="padding-top:0px; margin-top:0px;">'.htmlspecialchars($info['description']);
			if (!empty($info['url'])) echo ' <a href="'.$info['url'].'">'.__('Follow this link for more information.', 'woocommerce-eu-vat-compliance').'</a>';
			
			echo "<button id=\"wc_eu_vat_test_provider_button_$key\" onclick=\"test_provider('".$key."')\" class=\"button wc_eu_vat_test_provider_button\">".__('Test Provider', 'woocommerce-eu-vat-compliance')."</button>";
			
			echo '</p>';
			if (!empty($settings)) {
				echo '<table class="form-table" style="">'. "\n\n";
				if (is_string($settings)) {
					echo "<tr><td>$settings</td></tr>";
				} elseif (is_array($settings)) {
					woocommerce_admin_fields($settings);
				}
				echo '</table>';
			}
			echo "<div id=\"wc_eu_vat_test_provider_$key\"></div>";
			echo '</div>';
		}

	}
	
	/**
	 * Used internally to render the 'Readiness tests' tab
	 */
	private function render_tab_readiness() {
		echo '<h2>'.__('VAT Compliance Readiness', 'woocommerce-eu-vat-compliance').'</h2>';

		echo '<div style="width:960px;">';

		echo '<p>'.__('N.B. Items listed below are listed as suggestions only, and it is not claimed that all apply to every situation.', 'woocommerce-eu-vat-compliance').' '.__('Items listed do not constitute legal or financial advice. For all decisions as to which settings are relevant or right for your location and setup, responsibility is yours.', 'woocommerce-eu-vat-compliance').'</p>';
		
		echo '<p>'.__("N.B. If you are not selling goods for which the \"place of supply\" is deemed to be the customer's location (rather than the seller's; e.g. electronically supplied goods), then the tests for the presence of up-to-date VAT per-country rates are not relevant and you should not use them.", 'woocommerce-eu-vat-compliance').'</p>';

		if (!class_exists('WC_EU_VAT_Compliance_Readiness_Tests')) require_once(WC_VAT_COMPLIANCE_DIR.'/readiness-tests.php');
		$test = new WC_EU_VAT_Compliance_Readiness_Tests();
		$results = $test->get_results();

		$result_descriptions = $test->result_descriptions();

		?>
		<table>
		<thead>
			<tr>
				<th></th>
				<th style="text-align:left; min-width: 140px;"><?php _e('Test', 'woocommerce-eu-vat-compliance');?></th>
				<th style="text-align:left; min-width:60px;"><?php _e('Result', 'woocommerce-eu-vat-compliance');?></th>
				<th style="text-align:left;"><?php _e('Futher information', 'woocommerce-eu-vat-compliance');?></th>
			</tr>
		</thead>
		<tbody>
		<?php

		$opts = get_option('wceuvat_background_tests');
		$email = empty($opts['email']) ? '' : (string)$opts['email'];

		$default_bottom_blurb = '<p><a href="https://www.simbahosting.co.uk/s3/product/woocommerce-eu-vat-compliance/">'.__('To automatically run these tests daily, and notify yourself of any failed tests by email, use our Premium version.', 'woocommerce-eu-vat-compliance').'</a></p>';
		$bottom_blurb = apply_filters('wceuvat_readinesstests_bottom_section', $default_bottom_blurb, $email);
		$premium_present = ($bottom_blurb == $default_bottom_blurb) ? false : true;

		foreach ($results as $id => $res) {
			if (!is_array($res)) continue;
			// result, label, info
			switch ($res['result']) {
				case 'fail':
					$col = 'red';
					break;
				case 'pass':
					$col = 'green';
					break;
				case 'warning':
					$col = 'orange';
					break;
				default:
					$col = 'orange';
					break;
			}
			$row_bg = 'color:'.$col;

			$checked = (is_array($opts) && empty($opts['tests'][$id])) ? false : true;

			?>

			<tr style="<?php echo $row_bg;?>">
				<td style="vertical-align:top;"><?php
				if ($premium_present) { ?>
					<input type="checkbox" id="wceuvat_test_<?php echo esc_attr($id);?>" name="wceuvat_test_<?php echo esc_attr($id);?>" value="1" <?php if ($checked) echo 'checked="checked"'; ?>>
				<?php } ?>
				</td>
				<td style="vertical-align:top;"><label for="wceuvat_test_<?php echo esc_attr($id);?>"><?php echo $res['label'];?></label></td>
				<td style="vertical-align:top;"><?php echo $result_descriptions[$res['result']];?></td>
				<td style="vertical-align:top;"><?php echo $res['info'];?></td>
			</tr>
			<?php
		}

		?>
		</tbody>
		</table>
		<?php

		echo $bottom_blurb;

		echo '</div>';

	}

	/**
	 * Called by the WP action admin_footer when on our settings page
	 */
	public function admin_footer() {
		$text = esc_attr(__('N.B. The final country used may be modified according to your VAT settings.', 'woocommerce-eu-vat-compliance'));
		$testing = esc_js(__('Testing...', 'woocommerce-eu-vat-compliance'));
		$test = esc_js(__('Test Provider', 'woocommerce-eu-vat-compliance'));
		$nonce = wp_create_nonce("wc_eu_vat_nonce");
		$response = esc_js(__('Response:', 'woocommerce-eu-vat-compliance'));
		$loading = esc_js(__('Loading...', 'woocommerce-eu-vat-compliance'));
		$error = esc_js(__('Error', 'woocommerce-eu-vat-compliance'));

		echo '
		<script>
			function wceuvat_savesettings(subaction) {

				jQuery.blockUI({ message: "<h1>'.__('Saving...', 'woocommerce-eu-vat-compliance').'</h1>" });

				// https://stackoverflow.com/questions/10147149/how-can-i-override-jquerys-serialize-to-include-unchecked-checkboxes

				var formData;
				var which_checkboxes;

				if ("savereadiness" == subaction) {
					formData = jQuery("#wceuvat-navtab-readiness-content input, #wceuvat-navtab-readiness-content textarea, #wceuvat-navtab-readiness-content select").serialize();
					which_checkboxes = "#wceuvat-navtab-readiness-content";
				} else {
					formData = jQuery("#wceuvat_settings_accordion input, #wceuvat_settings_accordion textarea, #wceuvat_settings_accordion select").serialize();
					which_checkboxes = "#wceuvat_settings_accordion";
				}

				// include unchecked checkboxes. Use filter to only include unchecked boxes.
				jQuery.each(jQuery(which_checkboxes+" input[type=checkbox]")
				.filter(function(idx){
					return jQuery(this).prop("checked") === false
				}), function(idx, el) {
					var element_type = jQuery(el).prop("nodeName");
					console.log(jQuery(el));
					// attach matched element names to the formData with a chosen value.
					var emptyVal = "0";
					formData += "&" + jQuery(el).attr("name") + "=" + emptyVal;
				});
				
				// Include multiselects with no choice made
				jQuery.each(jQuery(which_checkboxes+" select[multiple]")
				.filter(function(idx){
					return jQuery(this).find(":selected").length === 0
				}), function(idx, el) {
					// attach matched element names to the formData with a chosen value.
					var var_name = jQuery(el).attr("name").slice(0, -2);
					formData += "&" + var_name + "=";
				});

				jQuery.post(ajaxurl, {
					action: "wc_eu_vat_cc",
					subaction: subaction,
					settings: formData,
					_wpnonce: "'.$nonce.'"
				}, function(response) {
					try {
						resp = JSON.parse(response);
						if (resp.result == "ok") {
							// alert("'.esc_js(__('Settings Saved.', 'woocommerce-eu-vat-compliance')).'");
							wceuvat_query_leaving = false;
						} else {
							alert("'.esc_js(__('Response:', 'woocommerce-eu-vat-compliance')).' "+resp.result);
						}
					} catch(err) {
						alert("'.esc_js(__('Response:', 'woocommerce-eu-vat-compliance')).' "+response);
						console.log(response);
						console.log(err);
					}
					jQuery.unblockUI();
				});
			}';

		echo <<<ENDHERE
			function test_provider(key) {
				jQuery('#wc_eu_vat_test_provider_button_'+key).html('$testing');
				
				var currencies = [ jQuery('#woocommerce_eu_vat_compliance_vat_recording_currency').val() ];
				
				jQuery('#wceuvat-reporting-currency-overrides select.wc_euvat_currency_selector').each(function() {
					var currency = jQuery(this).val();
					currencies.push(currency); 
				
				});
				
				jQuery.post(ajaxurl, {
					action: "wc_eu_vat_cc",
					subaction: "testprovider",
					tocurrencies: currencies,
					key: key,
					_wpnonce: "$nonce"
				}, function(response) {
					jQuery('#wc_eu_vat_test_provider_button_'+key).html('$test');
					try {
						resp = JSON.parse(response);
						jQuery('#wc_eu_vat_test_provider_'+key).html('<p>'+resp.response+'</p>');
					} catch(err) {
						alert('$response '+response);
						console.log(response);
						console.log(err);
					}
				});
			}
			
			function wceuvat_show_correct_providers() {
				var $ = jQuery;
				var provider = $('#woocommerce_eu_vat_compliance_exchange_rate_provider').val();
				$('.wceuvat-rate-provider_container').hide();
				$('#wceuvat-rate-provider_container_'+provider).show();
				$('#wceuvat-reporting-currency-overrides select.wc_euvat_provider_selector').each(function() {
				
					var provider = $(this).val();
					$('#wceuvat-rate-provider_container_'+provider).show();
				
				});
			}
			
			jQuery(function($) {

				$("#wc_euvat_cc_readiness_save").on('click', function() {
					wceuvat_savesettings("savereadiness");
				});

				wceuvat_show_correct_providers();
				
				$('#woocommerce_eu_vat_compliance_exchange_rate_provider').on('change', function() {
					wceuvat_show_correct_providers();
				});
				
				$('#wceuvat-reporting-currency-overrides select.wc_euvat_provider_selector').on('change', function() {
					wceuvat_show_correct_providers();
				});
				
				$('#woocommerce_tax_based_on').after('<br><em>$text</em>');
				
				$('#wceuvat_tabs a.nav-tab').on('click', function() {
					$('#wceuvat_tabs a.nav-tab').removeClass('nav-tab-active');
					$(this).addClass('nav-tab-active');
					var id = $(this).attr('id');
					if ('wceuvat-navtab-' == id.substring(0, 15)) {
						$('div.wceuvat-navtab-content').hide();
						$('#wceuvat-navtab-'+id.substring(15)+'-content').show();
						// This is not yet ready
// 						$('#wceuvat_tabs').trigger('show_'+id.substring(15));
					}
					return false;
				});
				
				var content_loaded = false;
				$('#wceuvat_tabs').on('show_reports', function() {
					if (content_loaded) return;
					content_loaded = true;
					$('#wceuvat-navtab-reports-content').html('$loading');
					$.post(ajaxurl, {
						action: "wc_eu_vat_cc",
						subaction: 'load_reports_tab',
						_wpnonce: '$nonce'
					}, function(response) {
						resp = JSON.parse(response);
						if (resp.result == 'ok') {
							$('#wceuvat-navtab-reports-content').html(resp.content);
						} else {
							$('#wceuvat-navtab-reports-content').html('$error');
							console.log(resp);
						}
					});
				});
				
			});
		</script>
ENDHERE;
	}

}
