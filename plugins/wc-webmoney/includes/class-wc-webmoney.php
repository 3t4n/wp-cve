<?php
/*
  +----------------------------------------------------------+
  | Author: Mofsy <support@mofsy.ru>                         |
  | Author website: https://mofsy.ru                         |
  +----------------------------------------------------------+
*/

final class WC_Webmoney
{
	/**
	 * The single instance of the class.
	 *
	 * @var WC_Webmoney
	 */
	protected static $_instance = null;

	/**
	 * Logger
	 *
	 * @var WC_Gatework_Logger
	 */
	public $logger;

	/**
	 * Current WooCommerce version
	 *
	 * @var
	 */
	public $wc_version;

	/**
	 * Current currency
	 *
	 * @var string
	 */
	public $wc_currency;

	/**
     * Result url
     *
	 * @var string
	 */
	private $result_url;

	/**
     * Fail url
     *
	 * @var string
	 */
	private $fail_url;

	/**
     * Success url
     *
	 * @var string
	 */
	private $success_url;

	/**
	 * WC_Webmoney constructor
     *
     * @action wc_webmoney_loading
     * @action wc_webmoney_loaded
	 */
	public function __construct()
	{
		// hook
		do_action( 'wc_webmoney_loading' );

		$this->includes();
		$this->hooks();

		// hook
		do_action( 'wc_webmoney_loaded' );
	}

	/**
	 * Main WC_Webmoney Instance.
	 *
	 * Ensures only one instance is loaded or can be loaded.
	 *
	 * @static
	 *
	 * @return WC_Webmoney - Main instance.
	 */
	public static function instance()
	{
		if ( is_null( self::$_instance ) )
		{
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Cloning instances is forbidden due to singleton pattern.
	 *
	 * @since 2.0.0.1
	 */
	public function __clone()
    {
		_doing_it_wrong( __FUNCTION__, sprintf( 'You cannot clone instances of %s.', get_class( $this ) ), '1.0.0.1' );
	}

	/**
	 * Un-serializing instances is forbidden due to singleton pattern.
	 *
	 * @since 2.0.0.1
	 */
	public function __wakeup()
    {
		_doing_it_wrong( __FUNCTION__, sprintf( 'You cannot unserialize instances of %s.', get_class( $this ) ), '1.0.0.1' );
	}
	
	/**
	 * Include required files.
     *
     * @action wc_webmoney_includes_start
     * @action wc_webmoney_includes_end
	 */
	public function includes()
	{
		// hook
		do_action('wc_webmoney_includes_start');

		include_once WC_WEBMONEY_PLUGIN_DIR . '/includes/class-wc-webmoney-method.php';

		// hook
		do_action('wc_webmoney_includes_end');
	}

	/**
     * Get current currency
     *
	 * @return string
	 */
	public function get_wc_currency()
    {
        return $this->wc_currency;
    }

	/**
     * Set current currency
     *
	 * @param $wc_currency
	 */
    public function set_wc_currency($wc_currency)
    {
        $this->wc_currency = $wc_currency;
    }

	/**
     * Get current WooCommerce version installed
     *
	 * @return mixed
	 */
	public function get_wc_version()
    {
		return $this->wc_version;
	}

	/**
     * Set current WooCommerce version installed
     *
	 * @param mixed $wc_version
	 */
	public function set_wc_version($wc_version)
    {
		$this->wc_version = $wc_version;
	}

	/**
	 * Hook into actions and filters
	 */
	private function hooks()
	{
		/**
		 * Init
		 */
		add_action('woocommerce_init', array($this, 'init'), 0);

		/**
		 * Add payment method
		 */
		add_filter('woocommerce_payment_gateways', array($this, 'wc_gateway_method_add'));

		/**
		 * Admin
		 */
		if(is_admin())
		{
			/**
			 * Admin styles
			 */
			add_action('admin_enqueue_scripts', array($this, 'wc_webmoney_admin_styles'));

			/**
			 * Show admin notices
			 */
			add_action( 'admin_notices', array( $this, 'wc_webmoney_admin_notices' ), 10 );

			/**
			 * Copyright & links
			 */
			add_filter('plugin_action_links_' . WC_WEBMONEY_PLUGIN_NAME, array($this, 'links_left'));
			add_filter('plugin_row_meta', array( $this, 'links_right' ), 10, 2);

			/**
			 * Explode admin pages
			 */
			$this->page_explode();
		}
	}

	/**
	 * Initialization
	 */
	public function init()
	{
		/**
		 * Load logger
		 */
	    $this->load_logger();

		/**
		 * Load languages
		 */
		$this->load_plugin_text_domain();

		/**
		 * Load URLs
		 */
		$this->load_urls();

		/**
		 * Load WooCommerce version
		 */
		$this->load_wc_version();

		/**
		 * Load WooCommerce currency
		 */
		$this->load_currency();
	}

	/**
	 * Load WooCommerce current currency
	 */
	public function load_currency()
    {
	    /**
	     * WooCommerce Currency Switcher
	     */
	    if (class_exists('WOOCS'))
	    {
		    global $WOOCS;

		    /**
		     * Set current WooCommerce Currency Switcher currency
		     */
		    $this->set_wc_currency(strtoupper($WOOCS->storage->get_val('woocs_current_currency')));
	    }
	    else
	    {
		    /**
		     * Set current WooCommerce currency
		     */
		    $this->set_wc_currency(gatework_get_wc_currency());
	    }
    }

	/**
	 * Load current WC version
	 */
	public function load_wc_version()
    {
	    /**
	     * Set current WooCommerce version
	     */
	    $this->set_wc_version(gatework_wc_get_version_active());
    }

	/**
	 * Load localisation files
	 */
	public function load_plugin_text_domain()
	{
		$locale = is_admin() && function_exists( 'get_user_locale' ) ? get_user_locale() : get_locale();
		$locale = apply_filters( 'plugin_locale', $locale, 'wc-webmoney' );

		unload_textdomain( 'wc-webmoney' );
		load_textdomain( 'wc-webmoney', WP_LANG_DIR . '/wc-webmoney/wc-webmoney-' . $locale . '.mo' );
		load_textdomain( 'wc-webmoney', WC_WEBMONEY_PLUGIN_DIR. '/languages/wc-webmoney-' . $locale . '.mo' );
	}

	/**
	 * Add the gateway to WooCommerce
	 *
	 * @param $methods
     *
     * @filter wc_webmoney_method_class_name_add
	 *
	 * @return array
	 */
	public function wc_gateway_method_add($methods)
	{
		$webmoney_method_class_name = apply_filters('wc_webmoney_method_class_name_add', 'Wc_Webmoney_Method');

		if(!class_exists($webmoney_method_class_name))
		{
			$webmoney_method_class_name = 'Wc_Webmoney_Method';
		}

		$methods[] = $webmoney_method_class_name;

		return $methods;
	}

	/**
	 * Load logger
	 */
	public function load_logger()
	{
		if(function_exists('wp_upload_dir'))
		{
			$wp_dir = wp_upload_dir();

			$options = get_option('woocommerce_webmoney_settings', array());
			$level = 400;

			if(isset($options['logger']) && $options['logger'] !== '')
			{
				$level = $options['logger'];
			}

			$this->set_logger(new WC_Gatework_Logger( $wp_dir['basedir'] . '/wc-webmoney.txt', $level));
		}
	}

	/**
	 * @param $logger
	 *
	 * @return $this
	 */
	public function set_logger($logger)
	{
		$this->logger = $logger;

		return $this;
	}

	/**
	 * Get logger
	 *
	 * @return WC_Gatework_Logger|null
	 */
	public function get_logger()
	{
		return $this->logger;
	}

	/**
	 * Setup left links
	 *
	 * @param $links
	 *
	 * @return array
	 */
	public function links_left($links)
	{
		return array_merge(array('settings' => '<a href="https://mofsy.ru/projects/wc-webmoney-premium">' . __('Buy Premium add-on', 'wc-webmoney') . '</a>'), $links);
	}

	/**
	 * Setup right links
	 *
	 * @param $links
	 * @param $file
	 *
	 * @return array
	 */
	public function links_right($links, $file)
	{
		if ( $file === WC_WEBMONEY_PLUGIN_NAME )
		{
			$links[] = '<a href="'.admin_url('admin.php?page=wc-settings&tab=checkout&section=webmoney').'">' . __('Settings') . '</a>';
		}

		return $links;
	}

	/**
	 * Add admin css styles
	 */
	public function wc_webmoney_admin_styles()
    {
        if(isset($_GET['section']) && $_GET['section'] === 'webmoney')
        {
            wp_enqueue_style('webmoney-admin-styles', WC_WEBMONEY_URL . 'assets/css/main.css');
        }
	}

	/**
	 * Show admin notices
	 */
	public function wc_webmoney_admin_notices()
    {
        /**
         * Global notice: Require update settings
         */
        if(get_option('wc_webmoney_last_settings_update_version') !== false && get_option('wc_webmoney_last_settings_update_version') != '2.0' && $_GET['section'] !== 'webmoney')
        {
	        ?>
            <div class="notice notice-warning" style="font-size: 16px;padding-top: 10px; padding-bottom: 10px; line-height: 170%;">
		        <?php
                echo __('The plugin for accepting payments through Webmoney for WooCommerce has been updated to a version that requires additional configuration.', 'wc-webmoney');
                echo '<br />';
                $link = '<a href="'. admin_url('admin.php?page=wc-settings&tab=checkout&section=webmoney') .'">'.__('here', 'wc-webmoney').'</a>';
		        echo sprintf( __( 'Press %s (to go to payment gateway settings).', 'wc-webmoney' ), $link ) ?>
            </div>
	        <?php
        }
    }

	/**
	 *  Add page explode actions
	 */
	public function page_explode()
	{
		add_action('wc_webmoney_admin_options_form_before_show', array($this, 'page_explode_table_before'));
		add_action('wc_webmoney_admin_options_form_after_show', array($this, 'page_explode_table_after'));
		add_action('wc_webmoney_admin_options_form_right_column_show', array($this, 'admin_right_widget_one'));
		add_action('wc_webmoney_admin_options_form_right_column_show', array($this, 'admin_right_widget_two'));
	}

	/**
	 * Page explode before table
	 */
	public function page_explode_table_before()
	{
		echo '<div class="row"><div class="col-17">';
	}

	/**
	 * Load urls:
     * - result
     * - fail
     * - success
	 */
	public function load_urls()
    {
	    $this->set_result_url(get_site_url( null, '/?wc-api=wc_webmoney&action=result'));
	    $this->set_fail_url(get_site_url( null, '/?wc-api=wc_webmoney&action=fail'));
	    $this->set_success_url(get_site_url( null, '/?wc-api=wc_webmoney&action=success'));
    }

	/**
	 * @return string
	 */
	public function get_result_url()
    {
		return $this->result_url;
	}

	/**
	 * @param string $result_url
	 */
	public function set_result_url($result_url)
    {
		$this->result_url = $result_url;
	}

	/**
	 * @return string
	 */
	public function get_fail_url()
    {
		return $this->fail_url;
	}

	/**
	 * @param string $fail_url
	 */
	public function set_fail_url($fail_url)
    {
		$this->fail_url = $fail_url;
	}

	/**
	 * @return string
	 */
	public function get_success_url()
    {
		return $this->success_url;
	}

	/**
	 * @param string $success_url
	 */
	public function set_success_url($success_url)
    {
		$this->success_url = $success_url;
	}

	/**
	 * Page explode after table
	 */
	public function page_explode_table_after()
	{
		echo '</div><div class="col-6">';

		do_action('wc_webmoney_admin_options_form_right_column_show');

		echo '</div></div>';
	}

	/**
	 * Widget one
	 */
	public function admin_right_widget_one()
	{
		echo '<div class="card border-light" style="margin-top: 0;padding: 0;">
  <div class="card-header" style="padding: 10px;">
    <h5 style="margin: 0;padding: 0;">' . __('Useful information', 'wc-webmoney') . '</h5>
  </div>
  <ul class="list-group list-group-flush" style="margin: 0;">
    <li class="list-group-item"><a href="https://mofsy.ru/projects/wc-webmoney">' . __('Official plugin page', 'wc-webmoney') . '</a></li>
    <li class="list-group-item"><a href="https://mofsy.ru/tag/webmoney">' . __('Related news: webmoney', 'wc-webmoney') . '</a></li>
    <li class="list-group-item"><a href="https://mofsy.ru/projects/tag/woocommerce">' . __('Plugins for WooCommerce', 'wc-webmoney') . '</a></li>
    <li class="list-group-item"><a href="https://mofsy.ru/others/feedback">' . __('Feedback to author', 'wc-webmoney') . '</a></li>
  </ul>
</div>';
	}

	/**
	 * Widget two
	 */
	public function admin_right_widget_two()
	{
		echo '<div class="card text-white border-light bg-dark" style="margin-top: 10px;padding: 0;">
  <div class="card-header" style="padding: 10px;">
    <h5 style="margin: 0;padding: 0;">' . __('Paid supplement', 'wc-webmoney') . '</h5>
  </div> <a href="https://mofsy.ru/projects/wc-webmoney-premium">
   	<img src="' . WC_WEBMONEY_URL . 'assets/img/wc-webmoney-premium-icon.png" class="card-img-top">
   </a>
  <div class="card-body text-center">
    ' . __('Even more opportunities to accept payments. Increase conversion.', 'wc-webmoney') . '
    <p>
    <a href="https://mofsy.ru/projects/wc-webmoney-premium" class="btn btn-secondary">' . __('Official plugin page', 'wc-webmoney') . '</a>
    </p>
  </div></div>';
	}
}