<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       silvasoft.nl
 * @since      1.0.0
 *
 * @package    Silvasoft
 * @subpackage Silvasoft/admin
 */


class Silvasoft_Admin {

	private $plugin_name;
	private $version;
	private $settings;
	//private $silvasoftlog;
	private $loader;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $loader) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->loader = $loader;
		
		
	  	/* construct admin pages */  
	   	if (is_admin()) {
			// Load settings class
			if (!class_exists("Silvasoft_Settings"))
				require(SILVAPLUGINDIR . 'admin/class-silvasoft-settings.php');
			$this->settings = new Silvasoft_Settings();
			
			//Load log class
			if (!class_exists("Silvasoft_Log"))
				require(SILVAPLUGINDIR . 'admin/class-silvasoft-log.php');

			//$this->silvasoftlog = new Silvasoft_Log();
		}

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/silvasoft-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/silvasoft-admin.js', array( 'jquery' ), $this->version, false );

	}
	
	
	/**
	 * Admin menu
	 */
	public function admin_menu() {
		//Silvasoft main menu item
		add_menu_page('', 'Silvasoft', 'manage_options', 'silvasoft', 'function', null);            
		
		//Silvasoft settings page
       	add_submenu_page('silvasoft', 'Instellingen', 'Instellingen', 'manage_options', __FILE__ . '/settings', array($this->settings,'silva_options_page'));
    
		//Silvasoft log page
    	add_submenu_page('silvasoft', 'Log', 'Log', 'manage_options',  __FILE__ . '/log', array($this,'silva_log_page'));
	
    	//remove main silva item added automatically
		remove_submenu_page( 'silvasoft', 'silvasoft');
	 }
	 
	 /* The log backend page rendering */
	 public	function silva_log_page() {
		  $logclass = new Silvasoft_Log();
		  
		  if(isset($_REQUEST['grid_action']) && $_REQUEST['grid_action'] != '') {
			if(isset($_REQUEST['woo_order_id']) && $_REQUEST['woo_order_id'] != '') {  					
				switch($_REQUEST['grid_action']) {
					case 'resend' :						
						$logclass->resendOrderToSilvasoft($_REQUEST['woo_order_id'],$_REQUEST['creditorder'] === 1 || $_REQUEST['creditorder'] == 1,$_REQUEST['partialrefund'] === 1 || $_REQUEST['partialrefund'] == 1);
						break;
					default : 
						break;						
				}					
			}
			  
			if($_REQUEST['grid_action'] === 'resetStatusAllOrders') {
				$query = new WC_Order_Query( array(
					'limit' => -1,
					'orderby' => 'date',
					'order' => 'DESC',
					'return' => 'ids',
				) );
				$orders = $query->get_orders();
				foreach( $orders as $i => $ai ) {
				   	delete_post_meta( $ai, 'silva_send_creditnota');
					delete_post_meta( $ai, 'silva_send_sale');
				}
				wp_reset_postdata();
				
				echo '<div class="notice notice-success is-dismissible"><p>';
				echo _e( 'Actie afgerond. De Silvasoft verstuurd status van alle orders is gereset. Orders kunnen nu opnieuw verstuurd worden.', 'my_plugin_textdomain' ); 
				echo '</p></div>';
				
			}
			  
		}
		
		echo '<div class="wrap"><h2>Silvasoft WooCommerce Connector - LOG</h2>'; 
		//get all the items for the table
		$logclass->prepare_items(); 
	
		//display the table, informatin text and search box
		echo '<div class="notice notice-info"><p>';
		echo 'Hieronder ziet u een log van alle orders die naar uw Silvasoft boekhouding verstuurd zijn. 
				Ook ziet u in dit overzicht een log van eventueel opgetreden fouten. ';
		echo '</div>';
	?>	
		<form method="post" style="float:left">
		<input type="hidden" name="page" value="ttest_list_table">
		<?php
		$logclass->search_box( 'search', 'search_id' );
		$logclass->display(); 
		echo '</form></div>'; 
		 
		 
		 
		$actions = array();
		$actions['resend'] = array(
				'url'       => wp_nonce_url(add_query_arg( 
									array(
										'grid_action'=> 'resetStatusAllOrders'									
									)
								)),
				'name'      => __( 'Reset verstuurd naar Silvasoft status voor alle orders', 'woocommerce' ),
				'action'    => "link",
			);				
		
		
		 
		 /* Print grid row actions */
		foreach ( $actions as $action ) {
			$var = sprintf(
				'Wilt u alle orders resetten qua status voor of de order is verstuurd naar Silvasoft of niet? Dit kan bijvoorbeeld gebruikt worden indien u orders opnieuw wilt versturen naar Silvasoft, naar een andere administratie of omdat u ze heeft verwijderd in Silvasoft en opnieuw wilt sturen. <a class="tips %1$s" href="%2$s" data-tip="%3$s">%4$s</a>',
				esc_attr( isset($action['action']) ?  $action['action'] : ''),
				esc_url( isset($action['url']) ? $action['url'] : '' ),
				esc_url( isset($action['name']) ? $action['name'] : '' ),
				esc_html( isset($action['name']) ? $action['name'] : '' )
			);
			echo '<div style="clear:both;display:block"></div><br/><div style="clear:both;border: 1px solid #ccc; background: #fff;padding:5px;margin-top: 15px;max-width: 95%;display:block;" class="notice-info"><p>'.$var.'</p></div>';
		}
		 
	}	
}