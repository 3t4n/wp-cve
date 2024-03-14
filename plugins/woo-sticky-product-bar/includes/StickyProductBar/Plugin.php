<?php

namespace OneTeamSoftware\WooCommerce\StickyProductBar;

defined('ABSPATH') || exit;

if (!class_exists(__NAMESPACE__ . '\\Plugin')):

class Plugin
{
	protected $id;
	protected $pluginPath;
	protected $version;
	protected $mainMenuId;
    protected $templatePath;
	protected $settings;
	protected $feedbackNotices;

    /**
     * Class constructor.
     */
    public function __construct($pluginPath, $version)
    {
		$this->id = 'wc-sticky-product-bar';
		$this->pluginPath = $pluginPath;
		$this->version = $version;
		$this->mainMenuId = 'oneteamsoftware';
		$this->templatePath = dirname($this->pluginPath) . '/templates/';
		$this->settings = array();
    }

    /**
     * Returns current plugin settings with default settings applied
     */
	public function onSettings($settings)
	{
		return array_replace_recursive(array(
			'enable' => 'yes', 
			'enableDesktop' => 'yes',
			'enableMobile' => 'yes',
			'locationDesktop' => 'bottom',
			'locationMobile' => 'bottom',
			'enableForProduct' => 'yes',
			'enableForCart' => 'yes',
			'enableForCheckout' => 'yes',
			'enableForOutOfStock' => 'no',
			'alwaysVisible' => 'yes',
			'rtl' => is_rtl() ? 'yes' : 'no',
			'displayImage' => 'yes',
			'displayName' => 'yes',
			'displayRating' => 'yes',
			'displayQuantity' => 'yes',
			'displayPrice' => 'yes',
			'displayPriceRange' => 'yes',
			'displayTotal' => 'yes',
			'displayTerms' => 'yes',
			'displayButton' => 'yes',
			'scrollAnimationDuration' => 2000,
			'addViewport' => 'no',
		), $settings);
	}

    /**
     * Registers itself in the world.
     */
    public function register()
    {
		if (!$this->checkDependencies()) {
			return;
		}

		add_filter($this->id . '_settings', array($this, 'onSettings'), 1);
		$this->settings = apply_filters($this->id . '_settings', get_option($this->id, array()));

		if (is_admin()) {
			add_filter('plugin_action_links_' . plugin_basename($this->pluginPath), array($this, 'onPluginActionLinks'), 1, 1);

			//Add sticky bar tab to woocommerce settings
			add_filter('woocommerce_get_settings_pages', array($this, 'onGetSettingsPage'));

			require_once(realpath(__DIR__ . '/../Admin/OneTeamSoftware.php'));
			\OneTeamSoftware\WooCommerce\Admin\OneTeamSoftware::instance()->register();
			
			add_action('admin_menu', array($this, 'onAdminMenu'));
		}

        //Add actionHead function to frontend
		add_action('wp_footer', array($this, 'onWpFooter'), PHP_INT_MAX);

        //Add all js script and css to sticky bar
		add_action('wp_enqueue_scripts', array($this, 'onEnqueScripts'), 1);

		add_action('wp_head', array($this, 'addViewport'), PHP_INT_MAX, 1);
    }

    /**
     * Adds link to the plugin in our submenu
     */
	public function onAdminMenu()
	{
		add_submenu_page($this->mainMenuId, __('Sticky Product Bar', $this->id), __('Sticky Product Bar', $this->id), 'manage_options', 'admin.php?page=wc-settings&tab=wc-sticky-product-bar');
	}

    /**
     * Adding viewport fixes issue in cases when bar disappears on scroll up
     */
	public function addViewport()
	{
		if ($this->settings['addViewport'] != 'yes') {
			return;
		}

		echo '<meta name="viewport" content="height=device-height, 
		width=device-width, initial-scale=1.0, 
		minimum-scale=1.0, maximum-scale=1.0, 
		user-scalable=no, target-densitydpi=device-dpi">';
	}

    /**
     * Adds link to plugin settions
     */
	public function onPluginActionLinks($links)
	{
		$link = sprintf('<a href="%s">%s</a>', admin_url('admin.php?page=wc-settings&tab=' . $this->id), __('Settings', $this->id));
		array_unshift($links, $link);

		return $links;
	}

    /**
     *
     * Helper to display template
     *
     */
    protected function displayTemplate($fileName, $parameters = array())
    {
        $filePath = $this->id . '/' . $fileName;

        wc_get_template($filePath, $parameters, '', $this->templatePath);
    }

	 /**
     * Check that all dependencies are installed and activated
     */
	protected function checkDependencies()
	{
		if (!function_exists('is_plugin_active')) {
			require_once(ABSPATH . '/wp-admin/includes/plugin.php');
		}

		$hasAllDependencies = true;
		if (!is_plugin_active('woocommerce/woocommerce.php')) {
			$hasAllDependencies = false;
		}

		if (!$hasAllDependencies) {
			add_action('admin_notices', array($this, 'displayDependenciesNotice'));

			//deactivate_plugins('woo-sticky-product-bar/wc-sticky-product-bar.php');
		}

		return $hasAllDependencies;
	}

    /**
     * Display dependencies notice
     */
    public function displayDependenciesNotice()
    {
?>
		<div id="message" class="error">
			<p><strong>WooCommerce Sticky Product Bar</strong> requires the following plugins to be installed and activated, before it can be used:</p> 
			<p>- <a href="<?php echo admin_url('/plugin-install.php?tab=search&amp;type=term&amp;s=WooCommerce'); ?>" target="">WooCommerce</a></p>
		</div>
<?php 
    }

    /**
     * Add new admin setting page for woocommerce sticky add to cart settings.
     *
     * @param array $settings an array of existing setting pages.
     * @return array of setting pages along with sticky bar settings page.
     *
     */
    public function onGetSettingsPage($settings)
    {
		require_once __DIR__ . '/Settings.php';
		$settings[] = new Settings($this->id);

        return $settings;
    }

    /**
     * Displayed sticky bar when enabled
     */
    public function onWpFooter()
    {
		if (empty($this->settings) || $this->settings['enable'] != 'yes') {
			return;
		}

		$this->displayProductBar();
        $this->displayCartBar();
        $this->displayCheckoutBar();
    }

	/**
     * Returns current product
     */
	protected function getCurrentProduct()
	{
		global $wp_the_query;

		$product = null;

		if (isset($wp_the_query) && is_object($wp_the_query)) {
			$posts = $wp_the_query->get_posts();
			if (count($posts) == 1) {
				$product = wc_get_product($posts[0]->ID);
			}	
		}

		return $product;
	}

    /**
     * Displays product bar
     */
    protected function displayProductBar()
    {
		$product = $this->getCurrentProduct();

        if ($this->settings['enableForProduct'] != 'yes' || !is_product() || !is_object($product)) {
            return;
		}
		$isInStock = $product->is_in_stock();
		if (!$isInStock && $product->get_type() == 'variable') {
			$variations = $product->get_available_variations();
			if (!empty($variations)) {
				$isInStock = true;
			}
		}
		
        if ($isInStock || $this->settings['enableForOutOfStock'] == 'yes') {
            $this->displayTemplate('product-bar.php', array('id' => $this->id, 'product' => $product, 'isInStock' => $isInStock, 'options' => $this->settings));
        }
    }

    /**
     *
     * Displays cart bar
     *
     */
    protected function displayCartBar()
    {
        if ($this->settings['enableForCart'] != 'yes' || !is_cart()) {
            return;
        }

        $cart = WC()->cart->get_cart();
        if (count($cart) > 0) {
            $this->displayTemplate('cart-bar.php', array('id' => $this->id, 'options' => $this->settings));
        }
    }

    /**
     *
     * Displays cart bar
     *
     */
    protected function displayCheckoutBar()
    {
        if ($this->settings['enableForCheckout'] != 'yes' || is_order_received_page() || !is_checkout()) {
            return;
        }

        $this->displayTemplate('checkout-bar.php', array('id' => $this->id, 'options' => $this->settings));
	}
	
    /**
     *
     * Add necessary js and css files for sticky bar
     *
     */
    public function onEnqueScripts()
    {
        // Get all admin settings value
        if (empty($this->settings) || $this->settings['enable'] != 'yes') {
            return;
        }

        if (!is_product() && !is_cart() && !is_checkout()) {
            return;
		}
		
		// we need to update settings because not all parts might have been loaded when plugin was called the for first time
		$this->settings = apply_filters($this->id . '_settings', $this->settings);

		$settings = $this->settings;
		$settings['id'] = $this->id;
        $settings['siteUrl'] = get_site_url();
		$settings['termsQuestions'] = __('Do you accept our terms and conditions?', $this->id);
		$settings['isMobile'] = wp_is_mobile() ? 'yes' : 'no';

        // Load custom js
        wp_register_script($this->id . '-js', plugins_url('assets/js/StickyProductBar.min.js', $this->pluginPath), array('jquery'), $this->version);
		wp_enqueue_script($this->id . '-js');

		//Load jquery visible
        wp_register_script('jquery-visible', plugins_url('assets/js/jquery.visible.min.js', $this->pluginPath), array('jquery'), $this->version);
		wp_enqueue_script('jquery-visible');
		
		//Load rateyo jstext_color
		wp_register_script('rateyo-js', plugins_url('assets/js/jquery.rateyo.min.js', $this->pluginPath), array('jquery'), $this->version);
		wp_enqueue_script('rateyo-js');
		
		//Load rateyo css
		wp_register_style('jquery-reteyo-css', plugins_url('assets/css/jquery.rateyo.min.css', $this->pluginPath), array(), $this->version);
		wp_enqueue_style('jquery-reteyo-css');
		
		//Load custom css
		wp_register_style($this->id . '-css', plugins_url('assets/css/StickyProductBar.min.css', $this->pluginPath), array(), $this->version);
		wp_enqueue_style($this->id . '-css');

		//Load RTL css
		if ($settings['rtl'] == 'yes') {
			wp_register_style($this->id . '-rtl-css', plugins_url('assets/css/StickyProductBar-rtl.min.css', $this->pluginPath), array(), $this->version);	
	        wp_enqueue_style($this->id . '-rtl-css');	
		}

        //Add inline css to custom css
        if (!empty($settings['css'])) {
            wp_add_inline_style($this->id . '-css', $settings['css']);
		}

		if (isset($settings['licenseKey'])) {
			unset($settings['licenseKey']);
		}

        // pass options to our js file
        wp_localize_script($this->id . '-js', 'WCStickyProductBarSettings', $settings);
	}
}

endif;