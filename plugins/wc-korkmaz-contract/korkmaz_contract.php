<?php
	
	/**
	 *
	 * @link              http://yemlihakorkmaz.com
	 * @since             1.3.2
	 * @package           Korkmaz_contract
	 *
	 * @wordpress-plugin
	 * Plugin Name:       WooCommerce Sipariş Sözleşmeleri Pdf
	 * Plugin URI:        http://www.yemlihakorkmaz.com/
	 * Description:       Woocommerce eklentisi için ödeme sayfasında sözleşme göstermeye ve oluşturmaya, pdf olarak mail göndermeye yarayan eklenti.
	 * Version:           1.3.2
	 * Author:            yemlihakorkmaz
	 * Author URI:        http://yemlihakorkmaz.com
	 * License:           GPL-2.0+
	 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
	 * Text Domain:       korkmaz_contract
	 * Domain Path:       /languages
	 */
	
	if ( !defined ( 'WPINC' ) ) {
		die;
	}
	
	define ( 'KORKMAZ_CONTRACT_VERSION','1.3.2' );
	
	if ( !defined ( "korkmaz_contract_dir" ) ) {
		define ( "korkmaz_contract_dir",plugin_dir_path ( __FILE__ ) );
	}
	
	if ( !defined ( "korkmaz_contract_url" ) ) {
		define (
			"korkmaz_contract_url",
			plugins_url ( '/',dirname ( __FILE__ ) )
		);
	}
	
	function activate_korkmaz_contract() {
		require_once plugin_dir_path ( __FILE__ )
		             . 'includes/class-korkmaz_contract-activator.php';
		Korkmaz_contract_Activator::activate ();
	}
	
	function deactivate_korkmaz_woo_sales_contract() {
		require_once plugin_dir_path ( __FILE__ )
		             . 'includes/class-korkmaz_contract-deactivator.php';
		Korkmaz_contract_Deactivator::deactivate ();
	}
	
	register_activation_hook ( __FILE__,'activate_korkmaz_contract' );
	register_deactivation_hook ( __FILE__,'deactivate_korkmaz_woo_sales_contract' );
	
	require plugin_dir_path ( __FILE__ )
	        . 'includes/class-korkmaz_contract.php';
	
	/**
	 * Begins execution of the plugin.
	 *
	 * Since everything within the plugin is registered via hooks,
	 * then kicking off the plugin from this point in the file does
	 * not affect the page life cycle.
	 *
	 * @since    1.1.1
	 */
	function run_korkmaz_contract() {
		
		$plugin = new Korkmaz_contract();
		
		$plugin->run ();
	}
	
	run_korkmaz_contract ();
