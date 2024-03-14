<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Woo Set Price Note
 *
 * Allows user to get WooCommerce Set Price Note.
 *
 * @class   Woo_Set_Price_Note_Backend 
 */


class Woo_Set_Price_Note_Backend {

	/**
	 * Init and hook in the integration.
	 *
	 * @return void
	 */


	public function __construct() {
		$this->id                 = 'Woo_Set_Price_Note_Backend';
		$this->method_title       = __( 'WooCommerce Set Price Note', 'woo-set-price-note' );
		$this->method_description = __( 'WooCommerce Set Price Note', 'woo-set-price-note' );

	
		// Actions
		// Display Fields
		add_action( 'woocommerce_product_options_general_product_data', array( $this, 'awspn_add_custom_general_field') );	
		
		// Save Fields
		add_action( 'woocommerce_process_product_meta', array( $this, 'awspn_add_custom_general_field_save') );	

		// First Register the Tab by hooking into the 'woocommerce_product_data_tabs' filter
		add_filter( 'woocommerce_product_data_tabs', array($this, 'awspn_add_price_note_product_data_tab'), 100 );	
		// functions you can call to output text boxes, select boxes, etc.
		
		
		add_filter( 'woocommerce_product_data_panels', array($this,'add_my_custom_product_data_fields') ); // WC 2.6 and up

		// Load small styling
		add_action('admin_footer', array( $this, 'awspn_print_price_note_style'));

		//load price note toggle scripts for order and emails
		add_action('admin_enqueue_scripts', array( $this, 'awspn_enqueue_backend_scripts'));
	}

	public function awspn_add_price_note_product_data_tab( $original_tabs ) {
			$new_tab['awspn-woo-price-note'] = array(
					'label' => __( 'Price Note', 'woo-set-price-note' ),
					'target' => 'my_custom_product_data',
				);

			$insert_at_position = 1; // This can be changed
			$tabs = array_slice( $original_tabs, 0, $insert_at_position, true ); // First part of original tabs
			$tabs = array_merge( $tabs, $new_tab ); // Add new
			$tabs = array_merge( $tabs, array_slice( $original_tabs, $insert_at_position, null, true ) ); // Glue the second part of original

			return $tabs;
	}

	
	public function add_my_custom_product_data_fields() {
	
	global $post;
	
	// Note the 'id' attribute needs to match the 'target' parameter set above
	echo '<div id="my_custom_product_data" class="panel woocommerce_options_panel">';
		echo '<div class="options_group">';

			// Product per note separator 
			woocommerce_wp_text_input( 
				array( 
					'id'          => 'awspn_product_price_note_separator', 
					'label'       => __( 'Note Separator', 'woo-set-price-note' ), 
					'placeholder' => '/',
					'desc_tip'    => 'true',
					'description' => __( 'Enter separator between price and note, like, "/", "-", "per", etc', 'woo-set-price-note' ) 
				)
			);


			// Product per note text
			woocommerce_wp_text_input( 
				array( 
					'id'          => 'awspn_product_price_note', 
					'label'       => __( 'Price Note', 'woo-set-price-note' ), 
					'placeholder' => 'Piece',
					'desc_tip'    => 'true',
					'description' => __( 'Enter price note that you want to display with product price, like, Units, Offers, Editions, etc.', 'woo-set-price-note' ) 
				)
			);

	  	echo '</div>';
	  	echo '<div class=" options_group ">';

			// Show on Order and Emails
			woocommerce_wp_checkbox( 
				array( 
					'id'          => 'awspn_show_on_order_and_email', 
					'label'       => __( 'Also include price note on Order and Emails', 'woo-set-price-note' ), 
					'placeholder' => 'Piece',
					'desc_tip'    => 'true',
					'description' => __( 'Check if you want to include the price note on Order details and Emails also.', 'woo-set-price-note' ) 
				)
			);	

	  		$awspn_show_on_oe = get_post_meta( $post->ID, 'awspn_show_on_order_and_email', true );
	  		$oe_box_class = !empty($awspn_show_on_oe) ? 'show' : 'hide';

	  		echo '<div class="awspn-price-note-preview awspn_show_on_order_and_email_box '.$oe_box_class.'">';
	  		if(isset($post)){
	  			$_product = new WC_Product( $post->ID );
				$product_price = $_product->get_price();
				$product_price = wc_price($product_price);

				$awspn_separator = esc_attr( get_post_meta( $post->ID, 'awspn_product_price_note_separator', true ) );
				$awspn_separator = !empty($awspn_separator) ? $awspn_separator : '/';

				$awspn_text = esc_attr( get_post_meta( $post->ID, 'awspn_product_price_note', true ) ); 
				$awspn_text = !empty($awspn_text) ? $awspn_text : 'Piece';


				$awspn_excl_price_on_oe = esc_attr( get_post_meta( $post->ID, 'awspn_excl_price_on_order_and_email', true ) );
				$oe_price_class 		= empty($awspn_excl_price_on_oe) ? 'show' : 'hide';

				$awspn_excl_sep_on_oe	= esc_attr( get_post_meta( $post->ID, 'awspn_excl_sep_on_order_and_email', true ) );
				$oe_sep_class 			= empty($awspn_excl_sep_on_oe) ? 'show' : 'hide';

				$awspn_clabel	= esc_attr( get_post_meta( $post->ID, 'awspn_product_price_note_oe_label', true ) );
				$awspn_clabel 	= !empty($awspn_clabel) ? $awspn_clabel : 'Price note';

				$awspn_ctexts 	= esc_attr( get_post_meta( $post->ID, 'awspn_product_price_note_oe_texts', true ) );
				$awspn_ctexts 	= !empty($awspn_ctexts) ? $awspn_ctexts : $awspn_text;

				
				echo '<table class="awspn-table">';
					echo '<thead>';
					echo '<tr class="awspn-table__line-item">';
						echo '<td>';
							echo '<strong>'.__('Preview - Order details and Emails', 'woo-set-price-note').'</strong>';
						echo '</td>';
					echo '</tr>';				
					echo '</thead>';				
					echo '<tbody>';				
					echo '<tr class="awspn-table__line-item order_item">';
						echo '<td class="awspn-table__product-name">';
						echo '<a href="'.get_permalink($post->ID).'">Flying Ninja</a> <strong class="product-quantity">× 1</strong>';
						echo '<ul class="wc-item-meta">';
							echo '<li>';
								echo '<strong class="awspn-label-wrap"><span class="awspn-oe-label">'.$awspn_clabel.'</span>:</strong>';
								echo '<p>';
									echo '<span class="awspn-oe-price '.$oe_price_class.'">'.$product_price.'&nbsp;</span>';
									echo '<span class="awspn-oe-sep '.$oe_sep_class.'">'.$awspn_separator.'&nbsp;</span>';
								echo '<span class="awspn-oe-texts">'.$awspn_ctexts.'</span>';
								echo '</p>';
							echo '</li>';							
						echo '</ul>';
					echo "</td>";
					echo "</tr>";
					echo "</tbody>";
				echo "</table>";
				
				// Show on Order and Emails
				woocommerce_wp_checkbox( 
					array( 
						'id'          => 'awspn_excl_price_on_order_and_email', 
						'label'       => __( 'Exclude price', 'woo-set-price-note' ), 
						'placeholder' => 'Piece',
						'desc_tip'    => 'true',
						'description' => __( 'Check if you want to remove "price" from the price note for Order details and Emails.', 'woo-set-price-note' ) 
					)
				);

				// Show on Order and Emails
				woocommerce_wp_checkbox( 
					array( 
						'id'          => 'awspn_excl_sep_on_order_and_email', 
						'label'       => __( 'Exclude separator', 'woo-set-price-note' ), 
						'placeholder' => 'Piece',
						'desc_tip'    => 'true',
						'description' => __( 'Check if you want to remove "separator" from the price note for Order details and Emails.', 'woo-set-price-note' ) 
					)
				);

				// Product per note text
				woocommerce_wp_text_input( 
					array( 
						'id'          => 'awspn_product_price_note_oe_label', 
						'label'       => __( 'Custom Label', 'woo-set-price-note' ), 
						'placeholder' => 'Price note:',
						'desc_tip'    => 'true',
						'description' => __( 'Enter price note that you want to display with product price, like, Offer, Unit, Edition, etc.', 'woo-set-price-note' ) 
					)
				);

				// Product per note text
				woocommerce_wp_text_input( 
					array( 
						'id'          => 'awspn_product_price_note_oe_texts', 
						'label'       => __( 'Custom Texts', 'woo-set-price-note' ), 
						'placeholder' => 'Price texts',
						'desc_tip'    => 'true',
						'description' => __( 'Enter price note that you want to display with product price, like, Offer, Unit, Edition, etc.', 'woo-set-price-note' ) 
					)
				);

	  		}
	  		echo '</div>';
	  	echo '</div>';
	 echo '</div>';

	
}

	

	
	/**
	 * Adding price note options on WooCommerce single product general section.
	 *
	 * @return void
	 */

	public static function awspn_add_custom_general_field() {
	 
	  
	  echo '<div class="options_group">';
		  echo '<p class="awspn-open-panel-wapper">';
		  	echo '<a href="javascript:void(0);" id="awspn-open-panel">'.__('Set Price Note', 'woo-set-price-note').'</a>';
		  echo '</p>';	  
	  echo '</div>';
		
	}


	/**
	 * Saving price note options on WooCommerce single product general section.
	 *
	 * @return void
	 */

	public static function awspn_add_custom_general_field_save( $post_id ){		
		
		$awspn_text 		= sanitize_text_field($_POST['awspn_product_price_note']);
		$awspn_separator 	= sanitize_text_field($_POST['awspn_product_price_note_separator']);		

		$awspn_show_on_oe		= sanitize_text_field($_POST['awspn_show_on_order_and_email']);
		$awspn_excl_price_on_oe = sanitize_text_field($_POST['awspn_excl_price_on_order_and_email']);
		$awspn_excl_sep_on_oe	= sanitize_text_field($_POST['awspn_excl_sep_on_order_and_email']);
		$awspn_clabel			= sanitize_text_field($_POST['awspn_product_price_note_oe_label']);
		$awspn_ctexts 			= sanitize_text_field($_POST['awspn_product_price_note_oe_texts']);

		// Product per note text
		if( isset( $awspn_text ) ){
			update_post_meta( $post_id, 'awspn_product_price_note',  $awspn_text  );		
		}

		// Product per note separator
		if( isset( $awspn_separator )  ){
			update_post_meta( $post_id, 'awspn_product_price_note_separator',  $awspn_separator  );		
		}

		// Product per note separator
		if( isset( $awspn_show_on_oe )  ){
			update_post_meta( $post_id, 'awspn_show_on_order_and_email',  $awspn_show_on_oe  );		
		}

		// Product per note separator
		if( isset( $awspn_excl_price_on_oe )  ){
			update_post_meta( $post_id, 'awspn_excl_price_on_order_and_email',  $awspn_excl_price_on_oe  );		
		}

		// Product per note separator
		if( isset( $awspn_excl_sep_on_oe )  ){
			update_post_meta( $post_id, 'awspn_excl_sep_on_order_and_email',  $awspn_excl_sep_on_oe  );		
		}

		// Product per note separator
		if( isset( $awspn_clabel )  ){
			update_post_meta( $post_id, 'awspn_product_price_note_oe_label',  $awspn_clabel  );		
		}

		// Product per note separator
		if( isset( $awspn_ctexts )  ){
			update_post_meta( $post_id, 'awspn_product_price_note_oe_texts',  $awspn_ctexts  );		
		}
		
	}

	public function awspn_print_price_note_style(){
			echo "<style type='text/css'>";
				echo '.awspn_show_on_order_and_email_box.hide{display:none;}';
				echo '.awspn-oe-price.hide{display:none;}';
				echo '.awspn-oe-sep.hide{display:none;}';
				echo '.awspn-table{ width:100%; padding:10px;}';
				echo '.awspn-table tbody{background-color:#eee;}';
				echo '.awspn-table tbody td{padding:20px 10px;}';
				echo '.awspn-table tbody td ul{margin:0;}';
				echo '.awspn-table tbody td li strong{float:left;}';
				echo '.awspn-table tbody td li p{width:50%; float:left; margin:0; padding:0px 5px; line-height: inherit;}';		
			echo '</style>';
		}	

	public function awspn_enqueue_backend_scripts(){
		wp_register_script( 'awspn-backend-scripts', plugins_url( 'woo-set-price-note/assets/js/awspn-backend-scripts.js' ), array('jquery'), null, true );
		wp_enqueue_script( 'awspn-backend-scripts' );
	}	

}

$awspn_backend = new Woo_Set_Price_Note_Backend();