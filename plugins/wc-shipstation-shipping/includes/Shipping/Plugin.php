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

namespace OneTeamSoftware\WooCommerce\Shipping;

defined('ABSPATH') || exit;

if (!class_exists(__NAMESPACE__ . '\\Plugin')):

class Plugin
{
	protected $id;
	protected $mainMenuId;
	protected $adapterName;
	protected $title;
	protected $description;
	protected $optionKey;
	protected $settings;
	protected $pluginSettings;
	protected $pluginPath;
	protected $version;
	protected $adapter;
	protected $settingsFormHooks;
	protected $logger;
	protected $pageDetector;
	protected $cartProxy;
	protected $sessionProxy;

    public function __construct($pluginPath, $adapterName, $description = '', $version = null) 
    {
		$this->id = str_replace('-pro', '', basename($pluginPath, '.php'));
		$this->pluginPath = $pluginPath;
		$this->adapterName = $adapterName;
		$this->description = $description;
		$this->version = $version;
		$this->optionKey = sprintf('woocommerce_%s_settings', $this->id);
		$this->settings = array();
		$this->pluginSettings = array();

		$this->mainMenuId = 'oneteamsoftware';
		$this->adapter = null;
		$this->settingsFormHooks = null;
		$this->logger = &\OneTeamSoftware\WooCommerce\Logger\LoggerInstance::getInstance($this->id);
		$this->pageDetector = new \OneTeamSoftware\WooCommerce\Utils\PageDetector();
		// initialize proxies so we will always have something to work with
		$this->cartProxy = new \OneTeamSoftware\Proxies\LazyClassProxy('stdClass');
		$this->sessionProxy = new \OneTeamSoftware\Proxies\LazyClassProxy('stdClass');
	}

	protected function getBaseShippingMethodClassName()
	{
		return 'ShippingMethod';
	}

	protected function initAdapter()
	{
		$adapterClassName = '\\OneTeamSoftware\\WooCommerce\\Shipping\\Adapter\\' . $this->adapterName;

		$this->adapter = new $adapterClassName($this->id);
		
		$this->title = sprintf(__('%s Shipping', $this->id), $this->adapter->getName());
	}

	public function register()
	{
		if (!function_exists('is_plugin_active')) {
			require_once(ABSPATH . 'wp-admin/includes/plugin.php');
		}

		// do not register when WooCommerce is not enabled
		if (!is_plugin_active('woocommerce/woocommerce.php')) {
			return;
		}

		$proPluginName = preg_replace('/(\.php|\/)/i', '-pro\\1', plugin_basename($this->pluginPath));
		if (is_plugin_active($proPluginName)) {
			return;
		}
		
		$this->initAdapter();
		$this->loadSettings();

		if (is_admin()) {
			\OneTeamSoftware\WooCommerce\Admin\OneTeamSoftware::instance()->register();
			
			add_action('admin_menu', array($this, 'onAdminMenu'));
		}
		
		add_filter('plugin_action_links_' . plugin_basename($this->pluginPath), array($this, 'onPluginActionLinks'), 1, 1);
		add_filter('woocommerce_shipping_methods', array($this, 'addShippingMethod'));
		add_filter($this->id . '_getPluginSettings', array($this, 'getPluginSettings'), 1, 0);
		add_filter($this->id . '_is_enabled', array($this, 'onIsEnabled'), 10, 1);
		add_filter($this->id . '_init_form_fields', array($this, 'updateFormFields'), 1, 1);
		add_filter($this->id . '_service_name', array($this, 'getDefaultServiceName'), 1, 2);
		add_filter($this->id . '_isCart', array($this->pageDetector, 'isCart'), 1, 0);
		add_filter($this->id . '_isCheckout', array($this->pageDetector, 'isCheckout'), 1, 0);
		add_action('plugins_loaded', array($this, 'initLazyClassProxies'), 1, 0);
		// we need to use plugins_loaded so other plugins will be able to integrate with it before it is too late
		add_action('plugins_loaded', array($this, 'registerFeatures'), 10, 0);
		add_action('wp_loaded', array($this, 'calculateShippingOnCheckout'), 1, 0);
		add_action('woocommerce_after_checkout_validation', array($this, 'onCheckoutValidation'), PHP_INT_MAX, 2);
		add_filter('woocommerce_billing_fields', array($this, 'setRequiredFields'), 10, 1);
		add_filter('woocommerce_shipping_fields', array($this, 'setRequiredFields'), 10, 1);
	}

	public function setRequiredFields($fields)
	{
		if ('yes' !== $this->settings['requireCompanyName']) {
			return $fields;
		}
		
		if (isset($fields['billing_company'])) {
			$fields['billing_company']['required'] = true;
		}

		if (isset($fields['shipping_company'])) {
			$fields['shipping_company']['required'] = true;
		}

		return $fields;
	}

	public function onCheckoutValidation($postedData, $checkoutErrors)
	{		
		if ($this->settings['validateAddress'] != 'yes') {
			return;
		}
		
		$validationErrors = $this->sessionProxy->get($this->id . '_validationErrors');
		if (empty($validationErrors)) {
			$validationErrors = array();
		}

		$this->logger->debug(__FILE__, __LINE__, 'onCheckoutValidation: ' . print_r($validationErrors, true));

		foreach ($validationErrors as $fieldKey => $errors) {
			$errorPrefix = '';
			if ($fieldKey == 'origin') {
				$errorPrefix = __('From Address:', $this->id);
			} else if ($fieldKey == 'destination') {
				$errorPrefix = __('Shipping Address:', $this->id);
			}

			foreach ($errors as $idx => $error) {
				$checkoutErrors->add($this->id . '_validation_error_' . $idx, sprintf('<strong>%s</strong> %s', $errorPrefix, $error));
			}
		}
	}

	public function onAdminMenu()
	{
		add_submenu_page($this->mainMenuId, $this->title, $this->title, 'manage_options', 'admin.php?page=wc-settings&tab=shipping&section=' . $this->id);
	}

	public function onPluginActionLinks($links)
	{
		$link = sprintf('<a href="%s">%s</a>', admin_url('admin.php?page=wc-settings&tab=shipping&section=' . $this->id), __('Settings', $this->id));
		array_unshift($links, $link);
		return $links;
	}

	public function getPluginSettings()
	{
		return $this->pluginSettings;
	}

	public function updateFormFields($formFields)
	{
		return $this->adapter->updateFormFields($formFields);
	}

	public function getDefaultServiceName($name, $service)
	{
		if (empty($name)) {
			$services = $this->adapter->getServices();
			if (!empty($services[$service])) {
				$name = $services[$service];
			}
		}

		return $name;
	}

	public function initLazyClassProxies()
	{
		// it can't work in the ADMIN or when WC is undefined
		if (is_admin() || !function_exists('WC')) {
			return;
		}

		$this->cartProxy = new \OneTeamSoftware\Proxies\LazyClassProxy('WC_Cart', WC()->cart);
		$this->sessionProxy = new \OneTeamSoftware\Proxies\LazyClassProxy(apply_filters('woocommerce_session_handler', 'WC_Session_Handler'), WC()->session);
	}

	public function registerFeatures()
	{
		$this->loadSettings();

		$this->settingsFormHooks = new Hooks\SettingsForm($this->id, $this->settings);
		$this->settingsFormHooks->register();
	}

	public function calculateShippingOnCheckout()
	{
		if ($this->settings['fetchRatesPageCondition'] != 'checkout') {
			return;
		}

		if (!apply_filters($this->id . '_isCheckout', false)) {
			$this->sessionProxy->set($this->id . '_' . __FUNCTION__, false);
			return;
		}

		$this->logger->debug(__FILE__, __LINE__,  __FUNCTION__);

		$packages = $this->cartProxy->get_shipping_packages();
		if (is_array($packages) && !empty($packages) && !$this->sessionProxy->get($this->id . '_' . __FUNCTION__)) {
			foreach ($packages as $packageKey => $package) {
				$sessionKey = 'shipping_for_package_' . $packageKey;
				$this->sessionProxy->set($sessionKey, null);
			}
	
			$this->cartProxy->calculate_shipping();
			$this->cartProxy->calculate_totals();	

			$this->sessionProxy->set($this->id . '_' . __FUNCTION__, true);
		}
	}

    public function addShippingMethod($methods)
	{
		$this->defineShippingMethodClass();
		$methods[$this->id] = $this->getShippingMethodClassName();

		return $methods;
	}

	public function onIsEnabled($enabled = false)
	{
		if ($this->settings['enabled'] == 'yes') {
			$enabled = true;
		}

		return $enabled;
	}
	
	protected function initSettings()
	{
		$countryState = explode(':', get_option('woocommerce_default_country', ''));

		$defaultCountry = '';
		if (count($countryState) > 0) {
			$defaultCountry = $countryState[0];
		}

		$defaultState = '';
		if (count($countryState) > 1) {
			$defaultState = $countryState[1];
		}

		$this->settings = array(
			'licenseKey' => '',
			'enabled' => 'yes',
			'debug' => 'yes',
			'cache' => 'yes',
			'cacheExpirationInSecs' => 12 * 60 * 60,
			'timeout' => 45,
			'sandbox' => 'yes',
			'combineBoxes' => 'yes',
			'useCubeDimensions' => 'no',
			'enableLiveShippingRates' => 'yes',
			'validateAddress' => 'no',
			'requireCompanyName' => 'no',
			'notifyForStatus' => array(),
			'completeOrderForStatuses' => array(),
			'fetchRatesPageCondition' => 'any',
			'fetchRatesOnPages' => array('any', 'cart', 'checkout'),
			'defaultCountryOfOrigin' => $defaultCountry,
			'origin' => array(
				'company' => get_option('blogname'),
				'email' => get_option('admin_email'),
				'phone' => '18008888888',
				'address' => get_option('woocommerce_store_address', ''),
				'address_2' => get_option('woocommerce_store_address_2', ''),
				'postcode' => get_option('woocommerce_store_postcode', ''),
				'city' => get_option('woocommerce_store_city', ''),
				'state' => $defaultState,
				'country' => $defaultCountry
			)
		);

		if (function_exists('wc_get_weight')) {
			$this->settings['minWeight'] = round(wc_get_weight(100, get_option('woocommerce_weight_unit'), 'g'), 2);
		}

		if (function_exists('wc_get_dimension')) {
			$this->settings['minLength'] = round(wc_get_dimension(200, get_option('woocommerce_dimension_unit'), 'mm'), 2);
			$this->settings['minWidth'] = $this->settings['minLength'];
			$this->settings['minHeight'] = round($this->settings['minLength'] / 10, 2);
		}
	}

	protected function loadSettings()
	{		
		$this->initSettings();
		
		$this->settings = array_merge($this->settings, (array)get_option($this->optionKey, array()));
		// plugin settings should never be overwritten by outside forces
		$this->pluginSettings = $this->settings;

		$this->logger->setEnabled($this->settings['debug'] == 'yes');

		$this->adapter->setSettings($this->settings);
	}

	protected function getShippingMethodClassName()
	{
		return 'OneTeamSoftware_WooCommerce_Shipping_' . $this->adapterName . '_' . $this->getBaseShippingMethodClassName();
	}

	protected function defineShippingMethodClass()
	{
		$className = $this->getShippingMethodClassName();
		if (class_exists($className)) {
			return;
		}

		$baseShippingMethodClassName = sprintf('\\OneTeamSoftware\\WooCommerce\\Shipping\\%s', $this->getBaseShippingMethodClassName());
		if (!class_exists($baseShippingMethodClassName)) {
			return;
		}

		$adapterInstanceName = $className . '_instance';
		$GLOBALS[$adapterInstanceName] = $this->adapter;
		
		// THIS IS A SAVE CODE, EVAL IS REQUIRED BECAUSE OF WOOCOMMERCE SHIPPING METHOD MECHANISM LIMITATIONS THAT DOES NOT ALLOW TO PASS EXTRA PARAMETERS
		// TO REDUCE CODE DUPLICATION WE NEED TO PASS AN INSTANCE OF AN ADAPTER, WHICH IS ACHIEVED WITH THIS CODE
		$classDefinition = sprintf('class %s extends %s
		{
			public function __construct($instance_id = 0) 
			{
				parent::__construct(
					\'%s\',
					$GLOBALS[\'%s\'],
					$instance_id,
					\'%s\',
					\'%s\'
				);
			}
		};', $className, $baseShippingMethodClassName, $this->id, $adapterInstanceName, addcslashes($this->title, '\''), addcslashes($this->description, '\''));
		
		eval($classDefinition);
	}
};

endif;
