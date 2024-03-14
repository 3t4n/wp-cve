<?php
/*********************************************************************/
/*  PROGRAM          FlexRC                                          */
/*  PROPERTY         604-1097 View St                                 */
/*  OF               Victoria BC   V8V 0G9                          */
/*  				 Voice 604 800-7879                              */
/*                                                                   */
/*  Any usage / copying / extension or modification without          */
/*  prior authorization is prohibited                                */
/*********************************************************************/

//declare(strict_types=1);

namespace OneTeamSoftware\WooCommerce\Shipping;

defined('ABSPATH') || exit;

if (!class_exists(__NAMESPACE__ . '\\ShippingMethod')):

class ShippingMethod extends AbstractShippingMethod
{
	protected $adapter;
	protected $services;
	protected $weightUnit;
	protected $dimensionUnit;
	protected $cacheExpirationInSecs;
	protected $ratesFinder;
	protected $parcelPacker;
	protected $productTaxonomy;
	protected $cartProxy;
	protected $countriesProxy;
	protected $customerProxy;
	protected $sessionProxy;

	public function __construct($id, Adapter\AbstractAdapter $adapter, $instance_id = 0, $title = '', $description = '')
	{
		parent::__construct($instance_id);

		$this->id = $id;
		$this->adapter = $adapter;
		$this->instance_id = $instance_id;
		$this->services = array();
		
		if (!empty($title)) {
			$this->method_title = __($title, $this->id);
		} else {
			$this->method_title = __('Shipping Method', $this->id);
		}

		if (!empty($description)) {
			$this->method_description = __($description, $this->id);
		} else {
			$this->method_description = __('Real-time shipping rates, shipping label creation and tracking of the shipments.', $this->id);
		}

		$this->weightUnit = get_option('woocommerce_weight_unit');
		$this->dimensionUnit = get_option('woocommerce_dimension_unit');

		$this->parcelPacker = null;

		$this->supports = array(
			'settings',
		);

		$this->cartProxy = new \OneTeamSoftware\Proxies\LazyClassProxy('WC_Cart', WC()->cart);
		$this->countriesProxy = new \OneTeamSoftware\Proxies\LazyClassProxy('WC_Countries', WC()->countries);
		$this->customerProxy = new \OneTeamSoftware\Proxies\LazyClassProxy('WC_Customer', WC()->customer);
		$this->sessionProxy = new \OneTeamSoftware\Proxies\LazyClassProxy(apply_filters('woocommerce_session_handler', 'WC_Session_Handler'), WC()->session);
		
		$this->init();
	}

	protected function getProFeatureSuffix()
	{
		$proFeatureSuffix = sprintf(' <strong>(%s <a href="%s" target="_blank">%s</a>)</strong>', 
			__('Requires', $this->id), 
			'https://1teamsoftware.com/product/' . preg_replace('/wc/', 'woocommerce', $this->id) . '-pro/',
			__('PRO Version', $this->id)
		);

		return $proFeatureSuffix;
	}

	protected function getProFeatureAttributes()
	{
		$proFeatureAttributes = array(
			'disabled' => 'yes'
		);

		return $proFeatureAttributes;
	}

	protected function init()
	{
		$this->initProductMatchingRule();

		do_action($this->id . '_setInstanceId', $this->instance_id);

		// Load the settings API
		$this->initFormFields(); // This is part of the settings API. Override the method to add your own settings
		$this->init_settings(); // This is part of the settings API. Loads settings you previously init.

		// Save settings in admin if you have any defined
		remove_all_actions('woocommerce_update_options_shipping_' . $this->id);
		add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));

		$this->title = $this->get_option('title', $this->method_title);
		$this->enabled = $this->get_option('enabled', 'yes');
		$this->cacheExpirationInSecs = $this->get_option('cacheExpirationInSecs', 12 * 60 * 60); //12 hours

		if ('yes' == $this->get_option('shippingZones', 'no')) {
			$this->supports[] = 'shipping-zones';
		}

		$this->initLogger();
		$this->initAdapter();

		// re-initialize fields so they will be generated according to loaded settings
		$this->initFormFields();

		$this->initParcelPacker();
	}

	public function init_settings()
	{
		$this->settings = apply_filters($this->id . '_getPluginSettings', array());
		if (empty($this->settings)) {
			parent::init_settings();
		}
	}

	public function init_instance_settings()
	{
		if (isset($this->settings['shippingZones']) && 'yes' == $this->settings['shippingZones']) {
			parent::init_instance_settings();
		} else {
			$this->instance_settings = array();
		}
	}
	
	protected function initLogger()
	{
		$this->logger = &\OneTeamSoftware\WooCommerce\Logger\LoggerInstance::getInstance($this->id);
	}

	protected function initAdapter()
	{
		$this->adapter->setSettings($this->settings);
		$this->services = $this->adapter->getServices();
		if (empty($this->services)) {
			$this->services = array();
		}

		$this->ratesFinder = new ApiRatesFinder($this->id, $this->adapter, $this->get_settings());
	}

	protected function initProductMatchingRule()
	{
		$this->productTaxonomy = new \OneTeamSoftware\WooCommerce\Condition\ProductTaxonomy();

		$this->productMatchingRule = new \OneTeamSoftware\WooCommerce\Condition\Rule();
		$this->productMatchingRule->addCondition($this->productTaxonomy);
	}

	protected function initParcelPacker()
	{
		$this->createParcelPacker();
		$this->parcelPacker->setSettings($this->get_settings());
	}

	protected function createParcelPacker()
	{
		$this->parcelPacker = new BaseParcelPacker($this->id);
	}

	public function is_enabled()
	{
		return apply_filters($this->id . '_is_enabled', false);
	}

	// Defines shipping method settings in ADMIN ui
	protected function initFormFields()
	{
		if (!function_exists('is_plugin_active')) {
			require_once(ABSPATH . '/wp-admin/includes/plugin.php');
		}

		$proFeatureSuffix = $this->getProFeatureSuffix();
		$proFeatureAttributes = $this->getProFeatureAttributes();

		$this->instance_form_fields = array();

		$this->form_fields = array(
			'troubleshootingTips_title' => array(
				'description' => sprintf('
					<div class="notice notice-warning inline">
					<h3>%s</h3>
					<p>%s</p>
					<ol>
						<li>%s</li>
						<li>%s</li>
						<li>%s</li>
						<li>%s</li>
						<li>%s</li>
						<li>%s</li>
						<li>%s</li>
						<li>%s</li>
						<li>%s</li>
					</ol>
					<p>%s</p>
					</div>
					',
					__('Troubleshooting Tips', $this->id),
					__('If you do not see shipping rates in the cart or have issues creating shipping labels then:', $this->id),
					__('Save settings and look at the top for any possible validation errors displayed in the red container above', $this->id),
					__('Enable Validate Products and save the settings, then check for validation errors', $this->id),
					__('Enable Debug Mode, uncheck Use Cache and perform operation at question, then look for error messages in the cart and generated log file', $this->id),
					__('Verify that all API/Token keys you have used are correct and active', $this->id),
					__('Verify that all products have dimensions and weight set', $this->id),
					__('To see rates in the cart make sure that Live Shipping Rates feature of the plugin is enabled', $this->id),
					__('Try unchecking Signature Service and Include Insurance', $this->id),
					__('If you\'ve enabled Shipping Zones feature of the plugin then make sure to add plugin to the relevant Shipping Zones', $this->id),
					__('Disable other shipping plugins and observe if it will make any difference', $this->id),
					__('If after all that issue still persists, then send us screenshots of the issue along with the contents of the generated log file, when debug was enabled, and we will help you to resolve the issue.', $this->id)
				),
				'type' => 'title',
			),

			'documentation_title' => array(
				'description' => sprintf(
					'<div class="notice notice-info inline"><p><strong>%s <a href="https://1teamsoftware.com/documentation/%s/" target="_blank">%s</a></strong></p></div>',
					__('Do you want to learn how to configure and use this plugin?', $this->id),
					preg_replace('/wc-|-pro/', '', $this->id),
					__('Click to read documentation', $this->id)
				),
				'type' => 'title'
			),

			'enabled' => array(
				'title' => __('Enable / Disable', $this->id),
				'label' => __('Enable this shipping plugin', $this->id),
				'type' => 'checkbox',
				'default' => 'yes',
			),
		);

		$this->form_fields += $this->getWcfmPromoFormFields();
		$this->form_fields += $this->getDokanPromoFormFields();
		$this->form_fields += $this->getIntegrationFormFields();

		$this->form_fields += array(
			'general_settings_title' => array(
				'title' => __('General Settings', $this->id),
				'type' => 'title',
			),
			'sandbox' => array(
				'title' => __('Sandbox Mode', $this->id),
				'label' => __('Test plugin settings without buying postage', $this->id),
				'type' => 'checkbox',
				'default' => 'no',
				'description' => __('Allows to test plugin without the need to pay real money for postage.', $this->id),
			),
			'validateProducts' => array(
				'title' => __('Validate Products', $this->id),
				'label' => __('Validate Products Weight and Dimensions', $this->id),
				'type' => 'checkbox',
				'default' => 'no',
				'description' => sprintf('<span style="color: red">%s</span>', __('Products must have weight and dimensions set for live shipping rates to work. Enable this option and plugin will display IDs of the products that do not meet this requirement.', $this->id)),
			),
			'debug' => array(
				'title' => __('Debug Mode', $this->id),
				'label' => __('Log plugin activities', $this->id),
				'description' => sprintf('%s <a href="%s" target="_blank">%s</a> %s <strong>%s</strong><br/>%s <strong>%s</strong>',
									__('Log can be found in', $this->id), 
									admin_url('admin.php?page=wc-status&tab=logs'),
									__('WooCommerce -> Status -> Logs', $this->id),
									__(' with the name'),
									basename(\WC_Log_Handler_File::get_log_file_path($this->id)),
									__('It can also be found in: ', $this->id),
									\WC_Log_Handler_File::get_log_file_path($this->id)
							),
				'type' => 'checkbox',
				'default' => 'yes',
			),
			'cache' => array(
				'title' => __('Use Cache', $this->id),
				'label' => __('Enable caching of API responses', $this->id),
				'type' => 'checkbox',
				'default' => 'yes',
				'description' => __('Caching improves performance and helps to reduce API usage by preventing duplicate requests to the service. You can try to disable it for debug purposes.', $this->id),
			),
			'cacheExpirationInSecs' => array(
				'title' => __('Cache Expiration (secs)', $this->id),
				'type' => 'number',
				'description' => __('Found rates, addresses, parcels, customs info will cached for a given amount of seconds and help to reduce number of required API requests. For production we recommend to set this value for at least a few days.', $this->id),
			),
			'timeout' => array(
				'title' => __('Request Timeout (secs)', $this->id),
				'type' => 'number',
				'description' => __('Defines for how long plugin should wait for a response after API request has been sent.', $this->id),
			),
		);

		$this->form_fields += $this->getOriginFormFields();
		
		$this->form_fields += array(
			'common_title' => array(
				'title' => __('Common Shipping Settings', $this->id),
				'type' => 'title',
				'description' => __('Configure default settings that will be used to request a quote, create shipment and purchase postage.', $this->id),
			),
		);

		if ($this->adapter->hasUseSellerAddressFeature()) {
			$this->form_fields += array(
				'useSellerAddress' => array(
					'title' => __('Use Seller Address', $this->id),
					'type' => 'checkbox',
					'label' => __('Use Vendor\'s address as a FROM address for shipping rate quoting', $this->id) . $proFeatureSuffix,
					'custom_attributes' => $proFeatureAttributes
				),
			);

			if (!is_plugin_active('wc-shipping-packages/wc-shipping-packages.php')) {
				$this->form_fields += array(
					'wc-shipping-packages_link' => array(
						'type' => 'title',
						'description' => sprintf(
							'<div class="notice notice-info inline"><p><strong>%s</strong><br/><strong><a href="%s" target="_blank">%s</a></strong> %s</p></div>',
							__('Do you want to charge separate shipping for each vendor?', $this->id),
							'https://1teamsoftware.com/product/woocommerce-shipping-packages/',
							__('Shipping Packages', $this->id),
							__('plugin will arrange products, based on Group By condition into packages and each package will have its own shipping method selection in the cart and checkout pages.', $this->id)
						)
					)
				);
			}			
		}
		
		if ($this->adapter->hasAddressValidationFeature()) {
			$this->form_fields += array(
				'validateAddress' => array(
					'title' => __('Validate Address', $this->id),
					'label' => __('Require shipping address validation before accepting an order (additional fees may apply). Note: 1TeamSoftware is not responsible for any fees incurred.', $this->id),
					'type' => 'checkbox',
					'default' => 'no',
					'custom_attributes' => [
						'onchange' => sprintf(
							'if (this.checked && !confirm(\'%s\n\n%s\n\n%s\n\n%s\')) { this.checked = false; }',
							__('WARNING!!!', $this->id),
                            // @codingStandardsIgnoreLine
                            __('You might be charged by a shipping provider for each address validation.', $this->id),
                            // @codingStandardsIgnoreLine
                            __('In cases when you do not have billing properly setup, live shipping rates might not be returned.', $this->id),
							__('Do you still want to enable it?', $this->id)
						),
					],
				),
			);	
		}

		if (!empty($proFeatureAttributes)) {
			$this->form_fields += array(
				'combineBoxes' => array(
					'title' => __('Combine All Products', $this->id),
					'label' => __('Combine all the products and ship together. Product dimensions and weight will be summed and shipping rate will be quoted only once.', $this->id),
					'type' => 'checkbox',
					'custom_attributes' => array(
						'onchange' => 'if (!this.checked) { jQuery("[id*=useCubeDimensions]").prop("checked", false); }', 
					),
				),	
				'useCubeDimensions' => array(
					'title' => __('Use Cube Dimensions', $this->id),
					'label' => __('Parcel dimensions will be converted into cube with equally long sides. It can only be used when Combine All Products is enabled.', $this->id),
					'type' => 'checkbox',
					'custom_attributes' => array(
						'onchange' => sprintf(
							'if (this.checked && !jQuery("[id*=combineBoxes]").prop("checked")) { this.checked = false; alert("%s\n\n%s"); }', 
							__('WARNING!!!', $this->id), 
							__('Combine All Products must be enabled before Use Cube Dimensions can be used.', $this->id)
						)
					),
				),
			);
		}

		if ($this->adapter->hasCodFeature()) {
			$this->form_fields += array(
				'cod' => array(
					'title' => __('Collect On Delivery', $this->id) . $proFeatureSuffix,
					'label' => __('Request collection of the payment on delivery', $this->id),
					'type' => 'checkbox',
					'default' => 'no',
					'custom_attributes' => $proFeatureAttributes
				),
			);			
		}

		if ($this->adapter->hasInsuranceFeature()) {
			$this->form_fields += array(
				'insurance' => array(
					'title' => __('Include Insurance', $this->id),
					'label' => __('Add insurance fee to the shipping rate', $this->id),
					'type' => 'checkbox',
					'default' => 'no',
				),
			);			
		}

		if ($this->adapter->hasSignatureFeature()) {
			$this->form_fields += array(
				'signature' => array(
					'title' => __('Signature Required', $this->id),
					'label' => __('Display only shipping methods that support signature service', $this->id),
					'type' => 'checkbox',
					'default' => 'no',
				),
			);
		}

		$this->form_fields += array(
			'requireCompanyName' => array(
				'title' => __('Require Company Name', $this->id),
				'label' => __('Plugin will not allow to checkout unless company name is filled', $this->id),
				'description' => __('It can be useful when your carrier requires that recipient will have a company name before it will return any shipping rates.', $this->id),
				'type' => 'checkbox',
				'default' => 'no',
			),
		);

		if ($this->adapter->hasTariffFeature()) {
			$this->form_fields += array(			
				'defaultTariff' => array(
					'title' => __('Default Tariff #', $this->id),
					'type' => 'text',
					'description' => sprintf(__('%sTariff number%s is required by some carriers for international shipments. It will be used to get shipping quotes unless overwritten during shipping label creation.', $this->id), '<a href="https://hts.usitc.gov/" target="_blank">', '</a>'),
				),
			);
		}

		$this->form_fields += array(
			'displaySettings_title' => array(
				'title' => __('Live Shipping Rates', $this->id),
				'type' => 'title',
				'description' => __('Configure live shipping rate settings that will be displayed on the cart and checkout pages.', $this->id),
			),
			'enableLiveShippingRates' => array(
				'title' => __('Enable / Disable', $this->id),
				'type' => 'checkbox',
				'label' => __('Display live shipping rates on cart and checkout pages'),
				'description' => __('Please note that having it enabled can affect your API limits', $this->id),
			),
			'shippingZones' => array(
				'title' => __('Shipping Zones', $this->id),
				'label' => __('Control availability of this shipping method with Shipping Zones', $this->id),
				'type' => 'checkbox',
				'default' => 'no',
				'custom_attributes' => array(
					'onchange' => sprintf(
						'if (this.checked && !confirm(\'%s\n\n%s\n\n%s\')) { this.checked = false; }', 
						__('WARNING!!!', $this->id), 
						__('No shipping rates will be displayed until you will add plugin to desired Shipping Zones.', $this->id), 
						__('Do you still want to enable it?', $this->id)
					)
				),
				'description' => sprintf('<span style="color: red">%s</span>', __('If this option is enabled, then live shipping rates will not be displayed until you will add plugin to the relevant shipping zones. Please note that WooCommerce does not support overlapping shipping zones.', $this->id)),
			),
			'shippingZoneFields' => array(
				'title' => __('Shipping Zone Fields', $this->id) . $proFeatureSuffix,
				'type' => 'multiselect',
				'class' => 'wc-enhanced-select',
				'options' => array(
					'origin' => __('From Address', $this->id),
					'boxes' => __('Parcel Packing', $this->id),
					'services' => __('Services', $this->id)
				),
				'description' => __('Choose what fields from the plugin settings you would like to overwrite in shipping zone settings', $this->id),
				'custom_attributes' => $proFeatureAttributes
			),
			'fetchRatesPageCondition' => array(
				'title' => __('Page Condition', $this->id),
				'type' => 'select',
				'class' => 'wc-enhanced-select',
				'options' => array(
					'' => __('Any', $this->id),
					'cart' => __('Cart and Checkout', $this->id),
					'checkout' => __('Checkout', $this->id)
				),
				'default' => '',
				'description' => __('Plugin will attempt to fetch live shipping rates only on pages that meet specified condition', $this->id),
			),
			'minRateCost' => array(
				'title' => __('Min Shipping Rate Cost', $this->id),
				'type' => 'number',
				'custom_attributes' => array('step' => 1, 'min' => 0),
				'description' => __('Only shipping rates that are higher or equal than a given amount will be displayed', $this->id),
			),
			'maxRateCost' => array(
				'title' => __('Max Shipping Rate Cost', $this->id),
				'type' => 'number',
				'custom_attributes' => array('step' => 1, 'min' => 0),
				'description' => __('Only shipping rates that are lower or equal than a given amount will be displayed', $this->id),
			),
			'maxShippingRates' => array(
				'title' => __('Max Displayed Rates', $this->id),
				'type' => 'number',
				'custom_attributes' => array('step' => 1, 'min' => 1),
				'description' => __('Limit number of shipping rates displayed at once', $this->id),
			),
		);

		if ($this->adapter->hasDisplayDeliveryTimeFeature()) {
			$this->form_fields += array(
				'displayDeliveryTime' => array(
					'title' => __('Display Delivery Time', $this->id),
					'label' => __('Display delivery time next to the name of a shipping method', $this->id) . $proFeatureSuffix,
					'type' => 'checkbox',
					'default' => 'no',
					'custom_attributes' => $proFeatureAttributes
				),
			);
		}

		if ($this->adapter->hasDisplayTrackingTypeFeature()) {
			$this->form_fields += array(
				'displayTrackingType' => array(
					'title' => __('Display Tracking Type', $this->id),
					'label' => __('Display type of the tracking included next to the name of a shipping method', $this->id) . $proFeatureSuffix,
					'type' => 'checkbox',
					'default' => 'no',
					'custom_attributes' => $proFeatureAttributes
				),
			);				
		}

		if ($this->adapter->hasCreateManifestsFeature() && !is_plugin_active('wc-shipping-manifests-pro/wc-shipping-manifests-pro.php')) {
			$this->form_fields += array(
				'shipping_manifests_link' => array(
					'type' => 'title',
					'description' => sprintf(
						'<div class="notice notice-info inline"><p><strong>%s</strong><br/><strong><a href="%s" target="_blank">%s</a></strong> %s</p></div>',
						__('Need Manifests?', $this->id),
						'https://1teamsoftware.com/product/woocommerce-shipping-manifests-pro/',
						__('Shipping Manifests PRO', $this->id), 
						__('plugin will group shipments into batches and let you print manifests for them.', $this->id)
					),
				),
			);
		};

		if ($this->adapter->hasMediaMailFeature()) {
			$this->form_fields += array(
				'mediaMailSettings_title' => array(
					'title' => __('Media Mail Settings', $this->id),
					'type' => 'title',
					'description' => __('Configure whenever media mail shipping should be offered and under which conditions.', $this->id),
				),
				'mediaMail' => array(
					'title' => __('Media Mail', $this->id),
					'type' => 'select',
					'options' => array(
						'exclude' => __('Do not offer it', $this->id),
						'only' => __('Show only Media Mail shipping method', $this->id),
						'include' => __('Show it together with other shipping methods', $this->id),
					),
					'default' => 'exclude',
				),
				'mediaMailCondition' => array(
					'title' => __('Condition', $this->id),
					'type' => 'multiselect',
					'class' => 'wc-enhanced-select',
					'options' => $this->productTaxonomy->getAvailableOptions(),
					'description' => __('If all products in the cart or a shipping package exactly match this condition, then Medial Mail shipping will be offered. It is ignored when empty.', $this->id),
				),
			);
		}

		if ($this->adapter->hasAlcoholFeature()) {
			$this->form_fields += array(
				'alcoholSettings_title' => array(
					'title' => __('Alcohol Settings', $this->id) . $proFeatureSuffix,
					'type' => 'title',
					'description' => __('Configure what conditions products have to match to be considered as an alcohol.', $this->id),
				),
				'alcoholCondition' => array(
					'title' => __('Condition', $this->id),
					'type' => 'multiselect',
					'class' => 'wc-enhanced-select',
					'options' => $this->productTaxonomy->getAvailableOptions(),
					'description' => __('If any of the products in the cart or a shipping package match all of these conditions, then shipment will be marked as containing alcohol.', $this->id),
					'custom_attributes' => $proFeatureAttributes
				),
			);
		}

		if ($this->adapter->hasDryIceFeature()) {
			$this->form_fields += array(
				'dryIceSettings_title' => array(
					'title' => __('Dry Ice Settings', $this->id) . $proFeatureSuffix,
					'type' => 'title',
					'description' => __('Configure what conditions products have to match to be considered as a dry ice.', $this->id),
				),
				'dryIceCondition' => array(
					'title' => __('Condition', $this->id),
					'type' => 'multiselect',
					'class' => 'wc-enhanced-select',
					'options' => $this->productTaxonomy->getAvailableOptions(),
					'description' => __('If any of the products in the cart or a shipping package match all of these conditions, then shipment will be marked as dry ice.', $this->id),
					'custom_attributes' => $proFeatureAttributes
				),
			);
		}

		$this->form_fields += array(
			'parcelAdjustments_title' => array(
				'title' => __('Parcel Adjustments', $this->id),
				'type' => 'title',
				'description' => sprintf('%s<br/><span style="color:red">%s</span>', 
					__('Configure how final weight and dimensions of the parcel should be adjusted before requesting a quote.', $this->id),
					__('Do not change unless you understand what you are doing!', $this->id)
				),
			),
			'minLength' => array(
				'title' => sprintf(__('Min Length (%s)', $this->id), $this->dimensionUnit),
				'type' => 'number',
				'custom_attributes' => array('step' => 0.01, 'min' => 0),
				'description' => __('This value will be used when package length is less than the min length', $this->id),
				'suffix' => $this->dimensionUnit,
			),
			'minWidth' => array(
				'title' => sprintf(__('Min Width (%s)', $this->id), $this->dimensionUnit),
				'type' => 'number',
				'custom_attributes' => array('step' => 0.01, 'min' => 0),
				'description' => __('This value will be used when package width is less than the min width', $this->id),
				'suffix' => $this->dimensionUnit,
			),
			'minHeight' => array(
				'title' => sprintf(__('Min Height (%s)', $this->id), $this->dimensionUnit),
				'type' => 'number',
				'custom_attributes' => array('step' => 0.01, 'min' => 0),
				'description' => __('This value will be used when package height is less than the min height', $this->id),
				'suffix' => $this->dimensionUnit,
			),
			'minWeight' => array(
				'title' => sprintf(__('Min Weight (%s)', $this->id), $this->weightUnit),
				'type' => 'number',
				'custom_attributes' => array('step' => 0.01, 'min' => 0),
				'description' => __('This value will be used when package weight is less than the min weight', $this->id),
				'suffix' => $this->weightUnit,
			),
			'weightAdjustment' => array(
				'title' => sprintf(__('Add to Weight (%s)', $this->id), $this->weightUnit),
				'type' => 'number',
				'custom_attributes' => array('step' => 0.01),
				'description' => __('This fixed value will be added to weight', $this->id),
				'suffix' => $this->weightUnit,
			),
			'weightAdjustmentPercent' => array(
				'title' => __('Multiply Weight By', $this->id),
				'type' => 'number',
				'custom_attributes' => array('step' => 0.01),
				'description' => __('Weight will be multiplied by this value', $this->id),
			),
			'priceAdjustments_title' => array(
				'title' => __('Shipping Rate Adjustments', $this->id),
				'type' => 'title',
				'description' => __('Configure how quoted shipping rates should be adjusted.', $this->id),
			),
			'priceAdjustment' => array(
				'title' => __('Add to Rate', $this->id),
				'type' => 'number',
				'custom_attributes' => array('step' => 0.01),
				'description' => __('This value will be added to all quoted shipping rates', $this->id),
			),
			'priceAdjustmentPercent' => array(
				'title' => __('Multiply Rate By', $this->id),
				'type' => 'number',
				'custom_attributes' => array('step' => 0.01),
				'description' => __('All quoted shipping rates will be multiplied by this value', $this->id),
			),
		);

		if (!is_plugin_active('wc-free-shipping-per-package-pro/wc-free-shipping-per-package-pro.php')) {
			$this->form_fields += array(
				'free_shipping_per_package_link' => array(
					'type' => 'title',
					'description' => sprintf(
						'<div class="notice notice-info inline"><p><strong>%s</strong><br/><strong><a href="%s" target="_blank">%s</a></strong> %s</p></div>',
						__('Need a Better Free Shipping Solution?', $this->id),
						'https://1teamsoftware.com/product/woocommerce-free-shipping-per-package-pro/',
						__('Free Shipping Per Package PRO', $this->id), 
						__('plugin will allow you to define advanced Free Shipping scenarios.', $this->id)
					),
				),
			);
		};

		if (!is_plugin_active('wc-flexible-shipping-per-package-pro/wc-flexible-shipping-per-package-pro.php')) {
			$this->form_fields += array(
				'flexible_shipping_per_package_link' => array(
					'type' => 'title',
					'description' => sprintf(
						'<div class="notice notice-info inline"><p><strong>%s</strong><br/><strong><a href="%s" target="_blank">%s</a></strong> %s</p></div>',
						__('Need Fallback Shipping Methods Solution?', $this->id),
						'https://1teamsoftware.com/product/woocommerce-flexible-shipping-per-package-pro/',
						__('Flexible Shipping Per Package PRO', $this->id),
						__('plugin will allow you to define fallback shipping methods when API service is down.', $this->id)
					),
				),
			);
		};

		$this->form_fields += $this->getBoxesFormFields();

		$services = $this->get_option('services', array());
		if (!empty($this->services) || !empty($services)) {
			$this->form_fields += $this->getServicesFormFields();
		}

		if ($this->adapter->hasUpdateShipmentsFeature()) {
			$this->form_fields += array(
				'updateShipmentsSettings_title' => array(
					'title' => __('Auto Tracking Settings', $this->id) . $proFeatureSuffix,
					'type' => 'title',
					'description' => __('Configure how often plugin should attempt to look for tracking updates. Plugin will use order email template with the added customer note containing current status of the shipment.', $this->id),
				),
				'updateShipmentsEnabled' => array(
					'title' => __('Enable / Disable', $this->id),
					'label' => __('Enable auto tracking feature', $this->id),
					'type' => 'checkbox',
					'default' => 'no',
					'custom_attributes' => $proFeatureAttributes
				),
				'updateShipmentsCronTaskInterval' => array(
					'title' => __('Run Auto Tracking Interval (secs)', $this->id),
					'type' => 'number',
					'default' => 30 * 60,
					'description' => __('How often in seconds auto tracking job should be executed.', $this->id),
					'custom_attributes' => $proFeatureAttributes
				),
				'updateShipmentsFetchInterval' => array(
					'title' => __('Update Shipments Interval (secs)', $this->id),
					'type' => 'number',
					'default' => 6 * 60 * 60,
					'description' => __('How often in seconds tracking of shipments should be updated.', $this->id),
					'custom_attributes' => $proFeatureAttributes
				),
				'updateShipmentsStatusChangeTimeout' => array(
					'title' => __('Status Change Timeout (secs)', $this->id),
					'type' => 'number',
					'default' => 10 * 24 * 60 * 60,
					'description' => __('Stop checking for shipment status update when it does not change within specified period.', $this->id),
					'custom_attributes' => $proFeatureAttributes
				),
				'updateShipmentsMaxLimit' => array(
					'title' => __('Max Shipments At Once', $this->id),
					'type' => 'number',
					'default' => 100,
					'description' => __('Tracking of how many shipments we should update at once.', $this->id),
					'custom_attributes' => $proFeatureAttributes
				),
			);
		}
		
		$this->form_fields += array(
			'emailNotifications_title' => array(
				'title' => __('Email Notifications', $this->id) . $proFeatureSuffix,
				'type' => 'title',
				'description' => __('Configure when shipment status updates should be emailed to the customers.', $this->id),
			),
			'notifyForStatus' => array(
				'title' => __('Notify for Statuses'),
				'type' => 'multiselect',
				'class' => 'wc-enhanced-select',
				'options' => array_merge(array('any' => __('Any', $this->id)), $this->adapter->getStatuses()),
				'description' => __('Email with tracking status update will be sent to the customers when shipment status will change to one of the listed values.', $this->id),
				'custom_attributes' => $proFeatureAttributes
			),
			'autoCompleteOrders_title' => array(
				'title' => __('Orders Auto Completion', $this->id) . $proFeatureSuffix,
				'type' => 'title',
				'description' => __('Configure for what shipment statuses order should be automatically marked as completed.', $this->id),
			),
			'completeOrderForStatuses' => array(
				'title' => __('Complete Order for Statuses'),
				'type' => 'multiselect',
				'class' => 'wc-enhanced-select',
				'options' => $this->adapter->getStatuses(),
				'description' => __('Order will be marked as completed when shipment status will change to one of the listed values. By default order will not automatically change its status to completed.', $this->id),
				'custom_attributes' => $proFeatureAttributes
			),
		);

		if ($this->adapter->hasCreateOrderFeature()) {
			$this->form_fields += array(
				'createOrderSettings_title' => array(
					'title' => __('Export Orders', $this->id) . $proFeatureSuffix,
					'type' => 'title',
					'description' => __('Configure settings for auto exporting of the new WooCommerce Orders into the service provider here.', $this->id),
				),
				'createOrderEnabled' => array(
					'title' => __('Enable / Disable', $this->id),
					'label' => __('Enable auto export of new orders feature', $this->id),
					'type' => 'checkbox',
					'default' => 'no',
					'custom_attributes' => $proFeatureAttributes
				),
				'createOrderForStatuses' => array(
					'title' => __('Export Order Statuses', $this->id),
					'type' => 'multiselect',
					'options' => $this->getOrderStatuses(),
					'class' => 'wc-enhanced-select',
					'description' => __( 'Define the order statuses you wish to export.', $this->id),
					'custom_attributes' => array(
						'data-placeholder' => __('Select Order Statuses', $this->id),
					),
				),
				'createOrderCronTaskInterval' => array(
					'title' => __('Run Export Orders Interval (secs)', $this->id),
					'type' => 'number',
					'default' => 24 * 60 * 60,
					'description' => __('How often in seconds export orders job should be executed. It usually depends on how many orders your store receives.', $this->id),
					'custom_attributes' => $proFeatureAttributes
				),
				'createOrderDelayBetweenAttempts' => array(
					'title' => __('Delay Between Attemps (secs)', $this->id),
					'type' => 'number',
					'default' => 48 * 60 * 60,
					'description' => __('The time plugin should wait before attempting to export the same order when last attempt has failed. It is recommended for it to be greater than Run Export Orders Interval.', $this->id),
					'custom_attributes' => $proFeatureAttributes
				),
				'createOrderMaxLimit' => array(
					'title' => __('Max Orders At Once', $this->id),
					'type' => 'number',
					'default' => 100,
					'description' => __('How many orders should be exported at once?', $this->id),
					'custom_attributes' => array_merge(array('step' => 1, 'min' => 1, 'max' => 1000), $proFeatureAttributes)
				),
			);
		}

		if ($this->adapter->hasImportShipmentsFeature()) {
			$this->form_fields += array(
				'importShipmentsSettings_title' => array(
					'title' => __('Import Shipments', $this->id) . $proFeatureSuffix,
					'type' => 'title',
					'description' => __('If you create shipping labels in the Dashboard of the service provider, then you can configure settings of auto importing of these shipments into WooCommerce Orders here.', $this->id),
				),
				'importShipmentsEnabled' => array(
					'title' => __('Enable / Disable', $this->id),
					'label' => __('Enable auto import of shipments feature', $this->id),
					'type' => 'checkbox',
					'default' => 'no',
					'custom_attributes' => $proFeatureAttributes
				),
				'importShipmentsCronTaskInterval' => array(
					'title' => __('Run Import Shipments Interval (secs)', $this->id),
					'type' => 'number',
					'default' => 24 * 60 * 60,
					'description' => __('How often in seconds import shipments job should be executed. It usually depends on how often you create shipments.', $this->id),
					'custom_attributes' => $proFeatureAttributes
				),
				'importShipmentsMaxLimit' => array(
					'title' => __('Max Shipments At Once', $this->id),
					'type' => 'number',
					'default' => 100,
					'description' => __('How many shipments do you usually create in the inverval configured above?', $this->id),
					'custom_attributes' => array_merge(array('step' => 1, 'min' => 1, 'max' => 1000), $proFeatureAttributes)
				),
			);
		}

		$this->form_fields += array(
			'pdf_title' => array(
				'title' => __('PDF Settings', $this->id) . $proFeatureSuffix,
				'type' => 'title',
				'description' => __('Define settings that will be applied to generated PDF documents.', $this->id),
			),
			'pdfAutoPrint' => array(
				'title' => __('Auto Print', $this->id),
				'type' => 'checkbox',
				'default' => 'no',
				'label' => __('Add script to generated PDF documents, which will trigger printing upon opening with supported viewers', $this->id),
				'custom_attributes' => $proFeatureAttributes
			),
			'pdfAutoPrintDialog' => array(
				'title' => __('Printer Dialog', $this->id),
				'type' => 'checkbox',
				'default' => 'yes',
				'label' => __('Display printer settings dialog before submiting a printer job', $this->id),
				'custom_attributes' => $proFeatureAttributes
			),
		);

		$this->form_fields += array(
			'purchasePostageDefault_title' => array(
				'title' => __('Purchase Postage Defaults', $this->id) . $proFeatureSuffix,
				'type' => 'title',
				'description' => __('Configure default values that will be used when purchasing shipping labels in bulk or individually.', $this->id),
			),
			'defaultShipmentDescription' => array(
				'title' => __('Shipment Description', $this->id),
				'type' => 'text',
				'default' => __('Merchandise', $this->id),
				'description' => __('This description will be used by default, but can be overwritten.', $this->id),
				'custom_attributes' => $proFeatureAttributes
			),
			'defaultContents' => array(
				'title' => __('Contents', $this->id),
				'type' => 'select',
				'options' => $this->adapter->getContentTypes(),
				'description' => __('Selected contents will be used in customs declaration', $this->id),
				'custom_attributes' => $proFeatureAttributes
			),
			'defaultCountryOfOrigin' => array(
				'title' => __('Country of Origin', $this->id),
				'type' => 'select',
				'options' => $this->countriesProxy->get_countries(),
				'custom_attributes' => $proFeatureAttributes
			),
			'defaultDomesticService' => array(
				'title' => __('Domestic Service', $this->id),
				'description' => __('By default use this service for Domestic Shipments', $this->id),
				'type' => 'select',
				'options' => array_merge(array('' => __('No default service', $this->id)), $this->services),
				'default' => '',
				'custom_attributes' => $proFeatureAttributes
			),
			'defaultInternationService' => array(
				'title' => __('International Service', $this->id),
				'description' => __('By default use this service for International Shipments', $this->id),
				'type' => 'select',
				'options' => array_merge(array('' => __('No default service', $this->id)), $this->services),
				'default' => '',
				'custom_attributes' => $proFeatureAttributes
			),
		);

		$this->form_fields += array(
			'purchasePostageWorkflow_title' => array(
				'title' => __('Purchase Postage Workflow', $this->id) . $proFeatureSuffix,
				'type' => 'title',
				'description' => __('Configure required manual steps before we will attempt to purchase postage for the shipment.', $this->id),
			),
			'allowMultipleShipments' => array(
				'title' => __('Allow Multiple Shipments', $this->id),
				'label' => __('Allow creation of the multiple shipments for the same order', $this->id),
				'type' => 'checkbox',
				'default' => 'yes',
				'custom_attributes' => $proFeatureAttributes
			),
			'requireToFetchShippingRates' => array(
				'title' => __('Require to Get A Quote', $this->id),
				'label' => __('Request shipping rates quote before allowing to create a shipment', $this->id),
				'type' => 'checkbox',
				'default' => 'yes',
				'custom_attributes' => $proFeatureAttributes
			),
		);

		if ($this->adapter->hasCreateShipmentFeature()) {
			$this->form_fields += array(
				'requireToCreateShipment' => array(
					'title' => __('Require to Create Shipment', $this->id),
					'label' => __('Create shipment before allowing to purchase postage (create shipping label)', $this->id),
					'type' => 'checkbox',
					'default' => 'yes',
					'custom_attributes' => $proFeatureAttributes
				),
			);	
		}

		$this->form_fields += array(
			'requireToConfirmShipmentDetails' => array(
				'title' => __('Require to Confirm Shipment Details', $this->id),
				'label' => __('Display confirmation dialog with the shipment details before allowing to create shipment (purchase postage)', $this->id),
				'type' => 'checkbox',
				'default' => 'yes',
				'custom_attributes' => $proFeatureAttributes
			),
			'bulkPurchasePostageWorkflow_title' => array(
				'title' => __('Bulk Purchase Postage Workflow', $this->id) . $proFeatureSuffix,
				'type' => 'title',
				'description' => __('Configure ability to create shipments and purchase postage in bulk from Admin Orders. In this case, all products are expected to be shipped in one parcel.', $this->id),
			),
			'enableBulkShipmentCreation' => array(
				'title' => __('Enable / Disable', $this->id),
				'label' => __('Enable bulk shipment creation and postage purchase', $this->id),
				'type' => 'checkbox',
				'default' => 'no',
				'custom_attributes' => $proFeatureAttributes
			),
			'useServiceSettingsForBulkShipmentCreation' => array(
				'title' => __('Use Service Settings', $this->id),
				'label' => __('Limit service selection based on Service Settings of the plugin', $this->id),
				'type' => 'checkbox',
				'default' => 'no',
				'custom_attributes' => $proFeatureAttributes
			),
		);

		if ($this->adapter->hasCreateShipmentFeature()) {
			$this->form_fields += array(
				'requireToBulkCreateShipments' => array(
					'title' => __('Require to Create Shipment', $this->id),
					'label' => __('Create shipment before allowing to purchase postage (create shipping label)', $this->id),
					'type' => 'checkbox',
					'default' => 'yes',
					'custom_attributes' => $proFeatureAttributes
				),
			);
		}

		$this->form_fields += array(
			'requireToBulkPurchasePostage' => array(
				'title' => __('Require to Purchase Postage', $this->id),
				'label' => __('Purchase postage before allowing to download shipping label and forms', $this->id),
				'type' => 'checkbox',
				'default' => 'yes',
				'custom_attributes' => $proFeatureAttributes
			),
		);

		$this->form_fields = apply_filters($this->id . '_init_form_fields', $this->form_fields);
	}

	protected function getOriginFormFields()
	{
		if (!$this->adapter->hasOriginFeature()) {
			return array();
		}

		$states = null;
		if (empty($this->instance_id)) {
			if (isset($this->settings['origin']['country'])) {
				$states = $this->countriesProxy->get_states($this->settings['origin']['country']);
			}
		} else {
			if (isset($this->instance_settings['origin']['country'])) {
				$states = $this->countriesProxy->get_states($this->instance_settings['origin']['country']);
			}
		}

		$formFields = array(
			'origin_title' => array(
				'title' => __('From Address', $this->id),
				'type' => 'title',
				'description' =>__('What is the address of the place from where parcels are going to be shipped?', $this->id),
			),
			'origin[name]' => array(
				'title' => __('Name', $this->id),
				'type' => 'text',
			),
			'origin[company]' => array(
				'title' => __('Company', $this->id),
				'type' => 'text',
			),
			'origin[email]' => array(
				'title' => __('Email', $this->id),
				'type' => 'email',
			),
			'origin[phone]' => array(
				'title' => __('Phone', $this->id),
				'type' => 'text',
			),
			'origin[country]' => array(
				'title' => __('Country', $this->id),
				'type' => 'select',
				'options' => $this->countriesProxy->get_countries(),
				'custom_attributes' => array('onchange' => 'jQuery("[name=save]").click()'),
			),
			'origin[state]' => array(
				'title' => __('State', $this->id),
				'type' => empty($states) ? 'text' : 'select',
				'options' => $states,
			),
			'origin[city]' => array(
				'title' => __('City', $this->id),
				'type' => 'text',
			),
			'origin[postcode]' => array(
				'title' => __('Zip / Postal Code', $this->id),
				'type' => 'text',
			),
			'origin[address]' => array(
				'title' => __('Address 1', $this->id),
				'type' => 'text',
			),
			'origin[address_2]' => array(
				'title' => __('Address 2', $this->id),
				'type' => 'text',
			),
		);
		
		return $formFields;
	}

	protected function getIntegrationFormFields()
	{
		$formFields = $this->adapter->getIntegrationFormFields();
		if (empty($formFields)) {
			return array();
		}

		$formFields = array(
			'integration_title' => array(
				'title' => __('Integration Settings', $this->id),
				'type' => 'title',
				'description' => __('Configure settings required for plugin to properly work with the service.', $this->id),
			),
		) + $formFields;

		return $formFields;
	}

	protected function getBoxesFormFields()
	{
		return apply_filters($this->id . '_getBoxesFormFields', array());
	}

	protected function getServicesFormFields()
	{
		return apply_filters($this->id . '_getServicesFormFields', array());
	}

	protected function getWcfmPromoFormFields()
	{
		if (!is_plugin_active('wc-multivendor-marketplace/wc-multivendor-marketplace.php')) {
			return array();
		}

		if (is_plugin_active('wc-shipping-labels-for-wcfm-pro/wc-shipping-labels-for-wcfm-pro.php')) {
			return array();
		}

		$formFields = array(
			'shipping_labels_for_wcfm_pro_link' => array(
				'type' => 'title',
				'description' => sprintf(
					'<div class="notice notice-info inline"><p><strong>%s</strong><br/><strong><a href="%s" target="_blank">%s</a></strong> %s</p></div>',
					__('Do you want Vendors to be able to Print Shipping Labels?', $this->id),
					'https://1teamsoftware.com/product/woocommerce-shipping-labels-for-wcfm-pro/',
					__('Shipping Labels for WCFM PRO', $this->id), 
					__('plugin is required, to add an ability to Vendors to print Shipping Labels in WCFM Frontend.', $this->id)
				),
			),
		);

		return $formFields;
	}

	protected function getDokanPromoFormFields()
	{
		if (!function_exists('dokan_get_store_info')) {
			return array();
		}

		if (is_plugin_active('wc-shipping-labels-for-dokan-pro/wc-shipping-labels-for-dokan-pro.php')) {
			return array();
		}

		$formFields = array(
			'shipping_labels_for_dokan_pro_link' => array(
				'type' => 'title',
				'description' => sprintf(
					'<div class="notice notice-info inline"><p><strong>%s</strong><br/><strong><a href="%s" target="_blank">%s</a></strong> %s</p></div>',
					__('Do you want Vendors to be able to Print Shipping Labels?', $this->id),
					'https://1teamsoftware.com/product/woocommerce-shipping-labels-for-dokan-pro/',
					__('Shipping Labels for Dokan PRO', $this->id), 
					__('plugin is required, to add an ability to Vendors to print Shipping Labels in Dokan Dashboard.', $this->id)
				),
			),
		);

		return $formFields;
	}

	protected function canCalculateShipping($package)
	{
		$canCalculateShipping = false;
		if ('yes' == $this->get_option('enableLiveShippingRates', 'yes') || 'yes' == $this->get_option('validateAddress', 'no')) {
			$canCalculateShipping = true;
		}
		
		$pageCondition = $this->get_option('fetchRatesPageCondition', '');
		if ($canCalculateShipping && !empty($pageCondition)) {
			$this->logger->debug(__FILE__, __LINE__, 'Page Condition: ' . print_r($pageCondition, true));

			if ($pageCondition == 'cart') {
				$canCalculateShipping = apply_filters($this->id . '_isCart', false) || apply_filters($this->id . '_isCheckout', false);
			} else if ($pageCondition == 'checkout') {
				$canCalculateShipping = apply_filters($this->id . '_isCheckout', false);
			}
		}

		$this->logger->debug(__FILE__, __LINE__, 'Can calculate shipping? ' . ($canCalculateShipping ? 'yes' : 'no'));

		return $canCalculateShipping;
	}

	protected function syncSettings()
	{
		$this->logger->debug(__FILE__, __LINE__, 'syncSettings');

		$this->logger->debug(__FILE__, __LINE__, 'Global Settings: ' . print_r($this->settings, true));
		if (!empty($this->instance_id) && is_array($this->instance_settings)) {
			$this->logger->debug(__FILE__, __LINE__, 'Instance Settings: ' . print_r($this->instance_settings, true));
		}

		$settings = $this->get_settings();

		$this->logger->debug(__FILE__, __LINE__, 'Consolidated Settings: ' . print_r($settings, true));

		$this->adapter->setSettings($settings);
		$this->parcelPacker->setSettings($settings);
		$this->ratesFinder->setSettings($settings);
	}

	public function calculate_shipping($package = array())
	{
		$this->logger->debug(__FILE__, __LINE__, $this->method_title . ' - Calculate Shipping, Instance ID ' . $this->instance_id . ', weight unit: ' . $this->weightUnit . ', dimension unit: ' . $this->dimensionUnit);

		if (!$this->is_enabled()) {
			$this->logger->debug(__FILE__, __LINE__, 'Shipping Method is not enabled');
			return;
		}

		if (!$this->canCalculateShipping($package)) {
			$this->logger->debug(__FILE__, __LINE__, 'Live shipping rates nor Address Validation are enabled');

			return;
		}

		if (empty($package) || empty($package['contents'])) {
			$this->logger->debug(__FILE__, __LINE__, 'Package contents is empty, lets try to use contents of the cart');

			if (empty($package)) {
				$package = array();
			}
	
			$package['contents'] = $this->cartProxy->get_cart();	

			if (empty($package['contents'])) {
				$this->logger->debug(__FILE__, __LINE__, 'Package contents is still empty, so we can not continue');

				return;
			}
		}

		$this->syncSettings();

		$package = $this->addPackageProperties($package);
		
		$this->logger->debug(__FILE__, __LINE__, 'Destination of the package: ' . print_r($package['destination'], true));

		$packageTypes = $this->adapter->getPackageTypes($package['destination']);
		$this->logger->debug(__FILE__, __LINE__, 'Supported package types: ' . print_r($packageTypes, true));

		$this->parcelPacker->setPackageTypes($packageTypes);
		$parcels = $this->parcelPacker->pack($package['contents']);

		$this->logger->debug(__FILE__, __LINE__, 'Number of parcels: ' . count($parcels));

		$rates = $this->findShippingRatesForParcels($package, $parcels);

		foreach ($rates as $rate) {
			$this->logger->debug(__FILE__, __LINE__, "Add rate: " . print_r($rate, true));
			$this->add_rate($rate);
		}
	}

	protected function displayValidationErrors($validationErrors)
	{
		$this->logger->debug(__FILE__, __LINE__, "Display Validation Errors");

		if (empty($validationErrors)) {
			return;
		}

		foreach ($validationErrors as $fieldKey => $errors) {
			$fieldLabel = '';
			if ($fieldKey == 'origin') {
				$fieldLabel = __('From Address: ', $this->id);
			} else if ($fieldKey == 'destination') {
				$fieldLabel = __('To Address: ', $this->id);
			}

			foreach ($errors as $error) {
				wc_add_notice($fieldLabel . $error, 'error');
			}
		}
	}

	protected function addPackageProperties(array $package)
	{
		$productsMatchingRules = $this->getPackagePropertiesMatchingRules();
		$this->logger->debug(__FILE__, __LINE__, 'Products matching rules: ' . print_r($productsMatchingRules, true));

		$packageProperties = $this->productMatchingRule->match($package['contents'], $productsMatchingRules);
		$this->logger->debug(__FILE__, __LINE__, 'Package properties: ' . print_r($packageProperties, true));

		$package += $packageProperties;
		$package['mediaMail'] = $this->getPackageMediaMailOption($packageProperties);

		return $package;
	}

	protected function getPackageMediaMailOption(array $package)
	{
		if (!$this->adapter->hasMediaMailFeature()) {
			return 'exclude';
		}

		$this->logger->debug(__FILE__, __LINE__, "getPackageMediaMailOption");

		$mediaMail = $this->get_option('mediaMail', 'exclude');
		if ($mediaMail != 'exclude' && empty($package['mediaMail'])) {
			$mediaMail = 'exclude';
		}

		$this->logger->debug(__FILE__, __LINE__, "Media Mail: " . $mediaMail);

		return $mediaMail;
	}
	
	protected function getPackagePropertiesMatchingRules()
	{
		$this->logger->debug(__FILE__, __LINE__, 'getPackagePropertiesMatchingRules');

		$rules = array();

		if ($this->adapter->hasMediaMailFeature()) {
			$options = $this->get_option('mediaMailCondition', array());
			if (!empty($options)) {
				$rules['mediaMail'] = array(
					'product_taxonomy',
					$options,
					'and',
					'and'
				);
			}
		}
		
		return $rules;
	}

	protected function findShippingRatesForParcels(array $package, array $parcels)
	{
		$this->logger->debug(__FILE__, __LINE__, 'findShippingRatesForParcels');

		$rates = array();
		foreach ($parcels as $parcelIdx => $parcel) {
			$this->logger->debug(__FILE__, __LINE__, "Find shipping rate for parcel #" . $parcelIdx);

			$parcel = $this->prepareParcel($package, $parcel);
			$parcelRates = $this->findShippingRates($package, $parcel);
			$rates = $this->combineRates($rates, $parcelRates, $parcel);
		}

		return $rates;
	}

	protected function findShippingRates($package, $parcel)
	{
		$this->logger->debug(__FILE__, __LINE__, 'findShippingRates: ' . print_r($parcel, true));

		$cacheKey = $this->adapter->getCacheKey($parcel);

		$rates = $this->adapter->getCacheValue($cacheKey);
		if (empty($rates)) {
			$rates = $this->ratesFinder->findShippingRates($parcel);

			$errorMessage = $this->ratesFinder->getError();
			if (!empty($errorMessage)) {
				$this->debug($errorMessage);
			}

			$validationErrors = $this->ratesFinder->getValidationErrors();
			$this->logger->debug(__FILE__, __LINE__, 'Validation Errors: ' . print_r($validationErrors, true));
	
			$this->sessionProxy->set($this->id . '_validationErrors', $validationErrors);

			if (empty($rates)) {
				$this->logger->debug(__FILE__, __LINE__, 'no shipping rates have been found');
				return array();
			}

			$this->logger->debug(__FILE__, __LINE__, 'We will cache result for ' . $this->cacheExpirationInSecs . ' secs');

			$this->adapter->setCacheValue($cacheKey, $rates, $this->cacheExpirationInSecs);	
		} else {
			$this->logger->debug(__FILE__, __LINE__, 'Cached shipping rates have been found');
		}

		// we might get here when validateAddress is enabled, but can display rates only when they are enabled
		if ('yes' == $this->get_option('enableLiveShippingRates', 'yes')) {
			$rates = $this->filterShippingRates($rates, $package, $parcel, $parcel['destination']);
			$rates = $this->sortShippingRates($rates);
			$rates = $this->limitMinMaxRateCost($rates);
			$rates = $this->limitNumberOfShippingRates($rates);

			$this->logger->debug(__FILE__, __LINE__, 'Rates: ' . print_r($rates, true));
		} else {
			$rates = array();
		}

		return $rates;
	}

	protected function getCustomerAddress()
	{
		$address = array();
		$data = array();

		// try to get AJAX update order review on checkout,
		// so we can use full customer's address for precise rate estimates
		if (!empty($_POST['post_data'])) {
			parse_str(wp_unslash($_POST['post_data']), $data);

			// make sure that we have sanitized the input
			if (empty($data)) {
				$data = array();
			} else {
				$data = wc_clean($data);
			}

			$this->logger->debug(__FILE__, __LINE__, 'Posted data: ' . print_r($data, true));
		}

		$fieldsMap = array(
			'first_name' => 'name',
			'last_name' => 'name',
			'company' => 'company',
			'address_1' => 'address',
			'address_2' => 'address_2',
			'city' => 'city',
			'postcode' => 'postcode',
			'state' => 'state',
			'country' => 'country',
			'phone' => 'phone',
			'email' => 'email'
		);

		foreach ($fieldsMap as $field => $toKey) {
			$value = null;

			if (empty($value) && !empty($data["shipping_{$field}"])) {
				$value = sanitize_text_field($data["shipping_{$field}"]);
			}
			
			if (empty($value) && !empty($data["billing_{$field}"])) {
				$value = sanitize_text_field($data["billing_{$field}"]);
			}
			
			if (empty($value)) {
				$value = $this->customerProxy->{"get_shipping_{$field}"}();
			}
			
			if (empty($value)) {
				$value = $this->customerProxy->{"get_billing_{$field}"}();
			}

			if (!empty($value)) {
				if (empty($address[$toKey])) {
					$address[$toKey] = '';
				} else {
					$address[$toKey] .= ' ';
				}

				$address[$toKey] .= $value;
			}
		}

		if (empty($address['name'])) {
			$address['name'] = 'N/A';
		}

		if (empty($address['phone'])) {
			$address['phone'] = '10000000000';
		}

		$this->logger->debug(__FILE__, __LINE__, 'getCustomerAddress -> ' . print_r($address, true));

		return $address;
	}

	protected function prepareParcel($package, $parcel)
	{
		$vendorId = 0;
		if (!empty($package['seller_id'])) {
			$vendorId = $package['seller_id'];
		} else if (!empty($package['vendor_id'])) {
			$vendorId = $package['vendor_id'];
		}

		$parcel['vendor_id'] = $parcel['seller_id'] = $vendorId;
		$parcel['signature'] = $this->get_option('signature', 'no');
		$parcel['insurance'] = $this->get_option('insurance', 'no');
		$parcel['mediaMail'] = !empty($package['mediaMail']) ? $package['mediaMail'] : 'exclude';
		$parcel['destination'] = !empty($package['destination']) ? $package['destination'] : array();

		$customerAddress = $this->getCustomerAddress();
		foreach ($parcel['destination'] as $key => $value) {
			if (!empty($value)) {
				$customerAddress[$key] = $value;
			}
		}
		$parcel['destination'] = $customerAddress;
		$parcel['currency'] = $this->adapter->getDefaultCurrency();

		return $parcel;
	}
	
	protected function combineRates(array $rates, array $parcelRates, array $parcel)
	{
		$this->logger->debug(__FILE__, __LINE__, 'combineRates');

		foreach ($rates as $service => $rate) {
			if (empty($parcelRates[$service])) {
				$this->logger->debug(__FILE__, __LINE__, $service . ' is not present in parcel rates, so remove it');

				unset($rates[$service]);
			}
		}

		foreach ($parcelRates as $service => $parcelRate) {
			$rate = array();

			if (empty($rates[$service])) {
				$rate = $parcelRate;
				$rate['meta_data']['parcels'] = array();
			} else {
				$rate = $rates[$service];
				$rate['cost'] += $parcelRate['cost'];
			}

			if (isset($parcel['seller_id'])) {
				$rate['meta_data']['seller_id'] = $parcel['seller_id'];
			}

			if (isset($parcel['vendor_id'])) {
				$rate['meta_data']['vendor_id'] = $parcel['vendor_id'];
			}

			$rate['meta_data']['parcels'][] = $parcel;
			$rate['meta_data']['service'] = $service;

			$rates[$service] = $rate;
		}

		$this->logger->debug(__FILE__, __LINE__, 'Combined Rates: ' . print_r($rates, true));

		return $rates;
	}

	protected function filterShippingRates($rates, $package, $parcel, $destination)
	{
		$this->logger->debug(__FILE__, __LINE__, 'filterShippingRates');

		$newRates = array();
		foreach ($rates as $rate) {
			$rate['cost'] = $this->adjustShippingRateCost($rate['id'], $parcel['weight'], $rate['cost']);
			$service = $rate['id'];
			$newRates[$service] = $rate;
		}

		return $newRates;
	}

	protected function adjustShippingRateCost($service, $weight, $cost)
	{
		$this->logger->debug(__FILE__, __LINE__, "adjustShippingRateCost, service: $service, weight: $weight, cost: $cost");

		$priceAdjustmentPercent = floatval($this->get_option('priceAdjustmentPercent', 0));
		if (!empty($priceAdjustmentPercent)) {
			$this->logger->debug(__FILE__, __LINE__, 'Base rate multiplier: ' . $priceAdjustmentPercent);

			$cost *= $priceAdjustmentPercent;
		}

		$priceAdjustment = floatval($this->get_option('priceAdjustment', 0));
		if (!empty($priceAdjustment)) {
			$this->logger->debug(__FILE__, __LINE__, 'Fixed rate adjustment: ' . $priceAdjustment);

			$cost += $priceAdjustment;
		}

		if ($cost < 0) {
			$cost = 0;
		}

		$this->logger->debug(__FILE__, __LINE__, "Final Shipping Cost: $cost");

		return $cost;
	}

	protected function sortShippingRates($rates)
	{
		if (!empty($rates) && is_array($rates)) {
			$this->logger->debug(__FILE__, __LINE__, 'sortShippingRatess');
			
			$rates = $this->adapter->sortRates($rates);
		}

		return $rates;
	}

	protected function limitMinMaxRateCost($rates)
	{
		$this->logger->debug(__FILE__, __LINE__, 'limitMinMaxRateCost');

		$minRateCost = $this->get_option('minRateCost', 0);
		$maxRateCost = $this->get_option('maxRateCost', 0);

		if (empty($minRateCost) && empty($maxRateCost)) {
			return $rates;
		}

		$newRates = array();
		foreach ($rates as $service => $rate) {
			if ($minRateCost > 0 && $rate['cost'] < $minRateCost) {
				$this->logger->debug(__FILE__, __LINE__, 'Rate is cheaper than ' . $minRateCost . ', so skip it: ' . print_r($rate, true));

				continue;
			}

			if ($maxRateCost > 0 && $rate['cost'] > $maxRateCost) {
				$this->logger->debug(__FILE__, __LINE__, 'Rate is more expensive than ' . $maxRateCost . ', so skip it: ' . print_r($rate, true));

				continue;
			}

			$newRates[$service] = $rate;
		}

		return $newRates;
	}

	protected function limitNumberOfShippingRates($rates)
	{
		$this->logger->debug(__FILE__, __LINE__, 'limitNumberOfShippingRates');

		$maxShippingRates = $this->get_option('maxShippingRates', 0);
		$this->logger->debug(__FILE__, __LINE__, 'Max Shipping Rates: ' . $maxShippingRates);

		if (is_numeric($maxShippingRates) && $maxShippingRates > 0 && count($rates) >= $maxShippingRates) {
			$rates = array_splice($rates, 0, $maxShippingRates);

			$this->logger->debug(__FILE__, __LINE__, 'We have got more than ' . $maxShippingRates . ' rates, so trim the list');
		}
		
		return $rates;
	}

	protected function validate(array $settings)
	{
		if (empty($this->instance_id)) {
			$this->validateAdapterRequirements($settings);
			$this->validateCacheRequirements($settings);
			$this->validateOriginRequirements($settings);
			$this->validateShippingZones($settings);
			$this->validateProductShippingRequirements($settings);	
		}

		if (!empty($this->errors)) {
			$this->errors = array_merge(
				array(sprintf('<strong>%s %s</strong>', $this->adapter->getName(), __('Validation Errors', $this->id))),
				$this->errors
			);
		}

		return true;
	}

	protected function validateAdapterRequirements(array $settings)
	{
		$errors = $this->adapter->validate($settings);
		if (!empty($errors) && is_array($errors)) {
			foreach ($errors as $error) {
				$this->add_error($error);
			}
		}
	}

	protected function validateCacheRequirements(array $settings)
	{
		if (empty($settings['cache']) || $settings['cache'] != 'yes') {
			$error = sprintf('<strong>%s:</strong> %s', __('Use Cache', $this->id), __('is disabled, please use it only for debugging purposes', $this->id));
			$this->add_error($error);
		}

		if (empty($settings['cacheExpirationInSecs']) || $settings['cacheExpirationInSecs'] < 60) {
			$error = sprintf('<strong>%s:</strong> %s', __('Cache Expiration (sec)', $this->id), __('is too short, please set it to 60 or more seconds', $this->id));
			$this->add_error($error);
		}
	}

	protected function validateOriginRequirements(array $settings)
	{
		if (!$this->adapter->hasOriginFeature() || (!empty($settings['useSellerAddress']) && $settings['useSellerAddress'] == 'yes')) {
			return;
		}

		if (empty($settings['origin']['name']) && empty($settings['origin']['company'])) {
			$error = sprintf('<strong>%s:</strong> %s', __('From Address', $this->id), __('Name or Company should be filled', $this->id));
			$this->add_error($error);
		}

		if (empty($settings['origin']['phone'])) {
			$error = sprintf('<strong>%s:</strong> %s', __('From Address', $this->id), __('Phone is required', $this->id));
			$this->add_error($error);
		}

		if (empty($settings['origin']['country'])) {
			$error = sprintf('<strong>%s:</strong> %s', __('From Address', $this->id), __('Country is required', $this->id));
			$this->add_error($error);
		}

		if (empty($settings['origin']['city'])) {
			$error = sprintf('<strong>%s:</strong> %s', __('From Address', $this->id), __('City is required', $this->id));
			$this->add_error($error);
		}

		if (empty($settings['origin']['postcode'])) {
			$error = sprintf('<strong>%s:</strong> %s', __('From Address', $this->id), __('Zip / Postal Code is required to be able to get shipping rates', $this->id));
			$this->add_error($error);
		}
	
		if (empty($settings['origin']['address'])) {
			$error = sprintf('<strong>%s:</strong> %s', __('From Address', $this->id), __('Address 1 is required', $this->id));
			$this->add_error($error);
		}
	}

	protected function validateProductShippingRequirements(array $settings)
	{
		if (empty($settings['validateProducts']) || $settings['validateProducts'] == 'no') {
			return;
		}

		$cacheKey = $this->adapter->getCacheKey(array('invalid_product_ids'));
		$invalidProductIds = $this->adapter->getCacheValue($cacheKey);
		if (!is_array($invalidProductIds)) {
			$invalidProductIds = $this->getInvalidProductIds();
			$this->adapter->setCacheValue($cacheKey, $invalidProductIds, 360);
		}

		if (is_array($invalidProductIds) && !empty($invalidProductIds[0])) {
			$error = sprintf(
				'%s: <strong>%s</strong>', 
				__('The following products do not have Weight set', $this->id), 
				implode(', ', $invalidProductIds[0])
			);

			$this->add_error($error);	
		}

		if (empty($this->settings['boxes']) && is_array($invalidProductIds) && !empty($invalidProductIds[1])) {
			$error = sprintf(
				'%s: <strong>%s</strong>', 
				__('The following products do not have Length, Width or Height set', $this->id), 
				implode(', ', $invalidProductIds[1])
			);

			$this->add_error($error);
		}
	}

	protected function validateShippingZones(array $settings)
	{
		if (empty($settings['shippingZones']) ||  $settings['shippingZones'] == 'no' || !class_exists('\WC_Shipping_Zones')) {
			return;
		}

		$isNotFound = true;
		$zones = \WC_Shipping_Zones::get_zones();
		foreach ($zones as $zone) {
			if (!empty($zone['shipping_methods']) && is_array($zone['shipping_methods'])) {
				foreach ($zone['shipping_methods'] as $instance_id => $shippingMethod) {
					if ($shippingMethod->id == $this->id) {
						$isNotFound = false;
						break;
					}
				}	
			}
		}

		if ($isNotFound) {
			$this->add_error(__('Plugin is not added to any Shipping Zone', $this->id));
		}
	}

	protected function hasProductWeight($product)
	{
		if (!$product->get_weight() && !get_post_meta($product->get_id(), '_weight', true)) {
			return false;
		}

		return true;
	}

	protected function hasProductDimensions($product)
	{
		if (!$product->get_length() && !get_post_meta($product->get_id(), '_length', true)) {
			return false;
		}

		if (!$product->get_width() && !get_post_meta($product->get_id(), '_width', true)) {
			return false;
		}

		if (!$product->get_height() && !get_post_meta($product->get_id(), '_height', true)) {
			return false;
		}

		return true;
	}

	protected function getInvalidProductIds()
	{
		$args = array(
			'type' => array('simple', 'variation'),
			'status' => 'publish',
			'visibility' => 'visible',
			'limit' => 10,
			'page' => 1
		);

		$noWeightProductIds = array();
		$noDimensionsProductIds = array();

		do {
			$products = wc_get_products($args);
	
			foreach ($products as $product) {
				if (is_object($product) && $product->needs_shipping()) {
					$productId = $product->get_id();
	
					if ($product->get_type() == 'variation') {
						if (wc_get_product($product->get_parent_id())) {
							$productId = $product->get_parent_id();
						} else {
							continue;
						}
					}
	
					if (!$this->hasProductWeight($product)) {
						$noWeightProductIds[$productId] = $productId;
					}
		
					if (!$this->hasProductDimensions($product)) {
						$noDimensionsProductIds[$productId] = $productId;
					}
				}
			}

			$args['page']++;
		} while(!empty($products));
		
		return array($noWeightProductIds, $noDimensionsProductIds);
	}

	protected function getOrderStatuses()
	{
		return array();
	}

	protected function debug($message, $type = 'notice')
	{
		if (!empty($this->settings['debug']) && $this->settings['debug'] == 'yes' && current_user_can('administrator')) {
			wc_add_notice(sprintf('%s: %s', $this->method_title, $message), $type);
		}
	}
}

endif;
