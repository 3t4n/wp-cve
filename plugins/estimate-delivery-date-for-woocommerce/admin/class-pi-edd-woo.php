<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       piwebsolution.com
 * @since      1.0.0
 *
 * @package    Pi_Edd
 * @subpackage Pi_Edd/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Pi_Edd
 * @subpackage Pi_Edd/admin
 * @author     PI Websolution <rajeshsingh520@gmail.com>
 */
class Pi_Edd_Woo {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( ) {

		add_action( 'woocommerce_init', array($this,'shipping_methods'));
		

		add_action( 'woocommerce_product_data_tabs', array($this,'productTab') );
		/** Adding order preparation days */
		add_action( 'woocommerce_product_data_panels', array($this,'order_preparation_days') );

		add_action( 'woocommerce_process_product_meta', array($this,'order_preparation_days_save') );
	}
	

	public function shipping_methods($array){
		$shipping_methods = @WC()->shipping()->load_shipping_methods();
		if(is_array($shipping_methods)):
		foreach($shipping_methods as $method){
			add_filter( 'woocommerce_shipping_instance_form_fields_'.$method->id, array($this,'pi_test'),1);
		}
		endif;
	}
	
	function pi_test($field){
		$field['min_days'] = array('title'=>'Minimum Shipping days', 'type'=>'number','default'=>1, 'custom_attributes' => ['min'=>0, 'step'=>1]);
		$field['max_days'] = array('title'=>'Maximum Shipping days', 'type'=>'number','default'=>1, 'custom_attributes' => ['min'=>0, 'step'=>1]);
		return $field;
	}

	function productTab($tabs){
        $tabs['pisol_mmq_edd'] = array(
            'label'    => 'Preparation Time',
            'target'   => 'pisol_edd',
            'priority' => 21,
        );
        return $tabs;
	}
	

	function order_preparation_days() {
		echo '<div id="pisol_edd" class="panel woocommerce_options_panel hidden free-version">';

		
		
		woocommerce_wp_checkbox( array(
            'label' => __("Disable estimate for this product", 'pi-edd'), 
            'id' => 'pisol_edd_disable_estimate', 
            'name' => 'pisol_edd_disable_estimate', 
            'description' => __("Check this if you don't want to show estimate date for this particular product", 'pi-edd')
					) );
					echo '<hr>';

		echo '<div id="pisol-product-preparation-days" class=" bootstrap-wrapper">';
		echo '<strong style="background:#000; color:#fff; padding:5px; display:block; ">Product preparation time (this makes delivery date different for each product)</strong>';
			$args = array(
			'id' => 'product_preparation_time',
			'label' => __( 'Min Product preparation days', 'pi-edd' ),
			'type' => 'number',
			'custom_attributes' => array(
				'step' 	=> '1',
				'min'	=> '0'
			) ,
			'placeholder'=>0,
			'class' => 'form-control',
			'desc_tip' => true,
			'description' => __( 'Enter the number of days it take to prepare this product' , 'pi-edd'),
			);
			woocommerce_wp_text_input( $args );

			
			echo '<strong style="background:#F00; color:#fff; padding:15px; display:block;cursor:pointer;" onClick="jQuery(\'#pro-features-pi-edd\').toggle();">'.__('Click to see other pro features','pi-edd').'</strong>';
			echo '<div id="pro-features-pi-edd" class="free-version" style="display:none;">';
			$args_max = array(
				'id' => 'product_preparation_time_max',
				'label' => __( 'Max Product preparation days (PRO)', 'pi-edd' ),
				'type' => 'number',
				'custom_attributes' => array(
					'step' 	=> '1',
					'min'	=> '0'
				) ,
				'placeholder'=>0,
				'class' => 'form-control',
				'desc_tip' => true,
				'description' => __( 'Enter the max number of days it take to prepare this product' , 'pi-edd'),
				);
				woocommerce_wp_text_input( $args_max );
			echo '<strong style="background:#000; color:#fff; padding:5px; display:block;">'.__('Exact arrival date of product','pi-edd').'</strong>';
			$args_exact_date = array(
					'id' => 'pisol_exact_availability_date',
					'label' => __( 'Exact Product availability date (Preparation time will be added to this date) (PRO)', 'pi-edd' ),
					'type' => 'text',
					
					'class' => 'form-control pisol_edd_date_picker',
					'desc_tip' => true,
					'description' => __( 'Select a date when this product will be available with you for dispatch, based on that it will show the estimate date, once you add date in this it plugin will use it for estimate calculation and ignore the above "preparation time" and "out of stock time"' , 'pi-edd'),
					);
				woocommerce_wp_text_input( $args_exact_date );
			echo '<strong style="background:#000; color:#fff; padding:5px; display:block;">'.__('Back Order product: extra preparation days','pi-edd').'</strong>';
			$out_of_stock_min = array(
				'id' => 'out_of_stock_product_preparation_time_min',
				'label' => __( 'Min Extra days (PRO)', 'pi-edd' ),
				'type' => 'number',
				'custom_attributes' => array(
					'step' 	=> '1',
					'min'	=> '0'
				) ,
				'placeholder'=>'If left blank 0 will be considered',
				'class' => 'form-control',
				'desc_tip' => true,
				'description' => __( 'This will be added in the normal product preparation time when product is out of stock and you are allowing back-order ' , 'pi-edd'),
				);
				woocommerce_wp_text_input( $out_of_stock_min );
				$out_of_stock_max = array(
					'id' => 'out_of_stock_product_preparation_time_max',
					'label' => __( 'Max Extra days (PRO)', 'pi-edd' ),
					'type' => 'number',
					'custom_attributes' => array(
						'step' 	=> '1',
						'min'	=> '0'
					) ,
					'placeholder'=>'If left blank 0 will be considered',
					'class' => 'form-control',
					'desc_tip' => true,
					'description' => __( 'This will be added in the normal product preparation time when product is out of stock and you are allowing back-order ' , 'pi-edd'),
					);
					woocommerce_wp_text_input( $out_of_stock_max );
					echo '<a href="'.PI_EDD_BUY_URL.'" class="button" target="_blank" style="margin:10px; ">Buy Now !!</a>';
			echo '</div>';
			echo '<div style="background:#000; color:#fff; padding:5px; display:block; ">In PRO version, you can customize this setting for each variation of a Variable product.</div>';

		echo '</div>';
		?>
		
		<?php
		echo '</div>';
		wp_nonce_field( 'pi-edd-save-product-nonce', 'pi-edd-save-product-nonce' );
		
	}

	function order_preparation_days_save( $post_id ) {

		$nonce_value = wc_get_var( $_REQUEST['pi-edd-save-product-nonce'], wc_get_var( $_REQUEST['_wpnonce'], '' ) );
		
		if (!wp_verify_nonce($nonce_value, 'pi-edd-save-product-nonce')){
			return;
		}
		

		$product = wc_get_product( $post_id );

		$disable_estimate = isset($_POST['pisol_edd_disable_estimate']) ? $_POST['pisol_edd_disable_estimate'] : '';
		$product->update_meta_data( 'pisol_edd_disable_estimate', $disable_estimate );

		if ( isset( $_REQUEST['product_preparation_time'] ) &&  $_REQUEST['product_preparation_time'] !== '') {
            $min_time = $_REQUEST['product_preparation_time'];
            $product->update_meta_data( 'product_preparation_time', wc_clean( $min_time ) );
        }

		$product->save();
	}

}

new Pi_Edd_Woo();