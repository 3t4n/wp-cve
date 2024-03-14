<?php
use Automattic\WooCommerce\Blocks\Integrations\IntegrationInterface;
use Automattic\WooCommerce\StoreApi\Exceptions\InvalidCartException;

/**
 * Class for integrating with WooCommerce Blocks
 */
    class WC_Szamlazz_VAT_Number_Block_Integration implements IntegrationInterface {

	/**
	 * The name of the integration.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'wc-szamlazz-vat-number';
	}

	//When called invokes any initialization/setup for the integration.
	public function initialize() {
		$this->register_block_frontend_scripts();
		$this->register_block_editor_scripts();
		$this->register_block_editor_styles();
		$this->register_main_integration();
		$this->add_attributes_to_frontend_blocks();
	}

	//Customizable text and labels
	public function add_attributes_to_frontend_blocks() {
		add_filter( '__experimental_woocommerce_blocks_add_data_attributes_to_block', function($allowed_blocks){
			if (!is_array($allowed_blocks)) {
				$allowed_blocks = (array) $allowed_blocks;
			}
			$allowed_blocks[] = 'wc-szamlazz/vat-number-block';
			return $allowed_blocks;	
		});
	}

	//Registers the main JS file required to add filters and Slot/Fills.
	private function register_main_integration() {
		$script_path = 'build/index.js';
		$style_path  = 'build/style-index.css';

		$script_url = WC_Szamlazz()::$plugin_url.$script_path;
		$style_url = WC_Szamlazz()::$plugin_url.$style_path;
		$script_asset_path = WC_Szamlazz()::$plugin_path . '/build/index.asset.php';

		$script_asset      = file_exists( $script_asset_path )
			? require $script_asset_path
			: [
				'dependencies' => [],
				'version'      => $this->get_file_version( $script_path ),
			];

		/*
		wp_enqueue_style(
			'wc-szamlazz-vat-number-block-integration',
			$style_url,
			[],
			$this->get_file_version( $style_path )
		);
		*/

		wp_register_script(
			'wc-szamlazz-vat-number-block-integration',
			$script_url,
			$script_asset['dependencies'],
			$script_asset['version'],
			true
		);
		wp_set_script_translations(
			'wc-szamlazz-vat-number-block-integration',
			'wc-szamlazz',
			WC_Szamlazz()::$plugin_path . '/languages'
		);

	}

	//Returns an array of script handles to enqueue in the frontend context.
	public function get_script_handles() {
		return [ 'wc-szamlazz-vat-number-block-integration', 'vat-number-block-frontend' ];
	}

	//Returns an array of script handles to enqueue in the editor context.
	public function get_editor_script_handles() {
		return [ 'wc-szamlazz-vat-number-block-integration', 'vat-number-block-editor' ];
	}

	//An array of key, value pairs of data made available to the block on the client side, easy to translate
	public function get_script_data() {
		$data = [
			'defaultText' => __('Are you buying as a private individual or a company?', 'wc-szamlazz'),
			'defaultIndividualLabel' =>_x('Individual', 'customer type', 'wc-szamlazz'),
			'defaultCompanyLabel' =>_x('Company', 'customer type', 'wc-szamlazz'),
			'defaultVatNumberLabel' => __('VAT Number', 'wc-szamlazz'),
			'textRequired' => __('Please enter your VAT number', 'wc-szamlazz'),
			'textInvalid' => apply_filters('wc_szamlazz_tax_validation_nav_message', esc_html__( 'The VAT number is not valid.', 'wc-szamlazz')),
		];

		return $data;
	}

	//Load editor styles
	public function register_block_editor_styles() {
		$style_path = 'build/style-vat-number-block.css';
		$style_url = WC_Szamlazz()::$plugin_url.$style_path;

		wp_enqueue_style(
			'vat-number-block',
			$style_url,
			[],
			$this->get_file_version( $style_path )
		);
	}

	//Load editor assets
	public function register_block_editor_scripts() {
		$script_path       = 'build/vat-number-block.js';
		$script_url        = WC_Szamlazz()::$plugin_url.$script_path;

		$script_asset_path = WC_Szamlazz()::$plugin_path . '/build/vat-number-block.asset.php';
		$script_asset      = file_exists( $script_asset_path )
			? require $script_asset_path
			: [
				'dependencies' => [],
				'version'      => $this->get_file_version( $script_asset_path ),
			];

		wp_register_script(
			'vat-number-block-editor',
			$script_url,
			$script_asset['dependencies'],
			$script_asset['version'],
			true
		);

		wp_set_script_translations(
			'vat-number-block-editor',
			'wc-szamlazz',
			WC_Szamlazz()::$plugin_path . '/languages'
		);

	}

	//Load frontend assets
	public function register_block_frontend_scripts() {
		$script_path       = 'build/vat-number-block-frontend.js';
		$script_url        = WC_Szamlazz()::$plugin_url.$script_path;
		$script_asset_path = WC_Szamlazz()::$plugin_path . '/build/vat-number-block-frontend.asset.php';
		$script_asset      = file_exists( $script_asset_path )
			? require $script_asset_path
			: [
				'dependencies' => [],
				'version'      => $this->get_file_version( $script_asset_path ),
			];

		wp_register_script(
			'vat-number-block-frontend',
			$script_url,
			$script_asset['dependencies'],
			$script_asset['version'],
			true
		);

		$test = wp_set_script_translations(
			'vat-number-block-frontend',
			'wc-szamlazz',
			WC_Szamlazz()::$plugin_path . '/languages'
		);

	}

	//Get the file modified time as a cache buster if we're in dev mode
	protected function get_file_version( $file ) {
		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG && file_exists( $file ) ) {
			return filemtime( $file );
		}
		return WC_Szamlazz()::$version;
	}

}