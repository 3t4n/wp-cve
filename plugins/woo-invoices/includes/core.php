<?php
// Exit if accessed directly
if ( ! defined('ABSPATH') ) { exit;
}
    
    add_action( 'admin_enqueue_scripts', 'sliced_woocommerce_enqueue_scripts' );
    add_filter( 'woocommerce_payment_gateways', 'sliced_add_gateway_to_woocommerce' );
    add_filter( 'admin_notices', 'sliced_woocommerce_custom_admin_notices' );

    // register new statuses
    add_filter( 'init', 'sliced_woocommerce_register_post_statuses', 1 );
    add_filter( 'wc_order_statuses', 'sliced_woocommerce_add_order_statuses', 1 );
    add_filter( 'wc_order_is_editable', 'sliced_woocommerce_make_our_statuses_editable' );

    // add the buttons to front end and admin
    add_action( 'woocommerce_admin_order_actions_end', 'sliced_woocommerce_admin_view_invoice_button' );
    add_action( 'woocommerce_order_details_after_order_table', 'sliced_woocommerce_front_view_invoice_button' );

    // add the meta box to woocommerce orders
    add_action( 'add_meta_boxes', 'sliced_add_meta_box_to_woocomerce_order' );


    /**
     * Register the JavaScript for the admin area.
     *
     * @since   1.0
     */
    function sliced_woocommerce_enqueue_scripts() {
        wp_enqueue_style( 'sliced-woocommerce', plugin_dir_url( __FILE__ ) . '/css/admin.css' );
    }

    /**
     * Add the Gateway to WooCommerce
     * @since  1.0
     */ 
    function sliced_add_gateway_to_woocommerce($methods) {
        $methods[] = 'WC_Sliced_Invoices';
        return $methods;
    }

	/**
	 * Admin notices
	 *
	 * @version 1.2.2
	 * @since   1.0.0
	 */
	function sliced_woocommerce_custom_admin_notices() {
		
		global $pagenow;
		
		/*
		 * Woocommerce order notice on invoice/quote
		 */
		if ( $pagenow === 'post.php' && sliced_get_the_type() && sliced_woocommerce_get_order_id( $_GET['post'] ) ) {
			echo '<div class="notice notice-info is-dismissible woo">
				<span class="woo-logo"></span>
				<p>' . sprintf( __( 'This %1s is tied to a Woocommerce order. If you are wanting to edit prices and products, you should be editing the order itself and not this %2s.', 'sliced-invoices' ), sliced_get_label(), sliced_get_label() ) . '<br>
				 ' . sprintf( __( 'You should only be editing the Terms & Conditions, the Status, Invoice Number and Dates in here.', 'sliced-invoices' ), sliced_get_label(), sliced_get_label() ) . '</p>
			</div>';
		}
		
	}


    /**
     * Register New Order Statuses
     * @since  1.0
     */ 
    function sliced_woocommerce_register_post_statuses() {
        register_post_status( 'wc-quote', array(
            'label'                     => sprintf( _x( '%s', 'WooCommerce Order status', 'woo-invoices' ), sliced_get_quote_label() ),
            'public'                    => true,
            'exclude_from_search'       => true,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop( 'Quote (%s)', 'Quote (%s)', 'woo-invoices' )
        ) );        
        register_post_status( 'wc-invoice', array(
            'label'                     => sprintf( _x( '%s', 'WooCommerce Order status', 'woo-invoices' ), sliced_get_invoice_label() ),
            'public'                    => true,
            'exclude_from_search'       => true,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop( 'Invoice (%s)', 'Invoice (%s)', 'woo-invoices' )
        ) );
    }


    /**
     * Add New Order Statuses to WooCommerce
     * @since  1.0
     */ 
    function sliced_woocommerce_add_order_statuses( $order_statuses ) {
        $order_statuses['wc-quote'] = sprintf( _x( '%s', 'WooCommerce Order status', 'woo-invoices' ), sliced_get_quote_label() );
        $order_statuses['wc-invoice'] = sprintf( _x( '%s', 'WooCommerce Order status', 'woo-invoices' ), sliced_get_invoice_label() );
        return $order_statuses;
    }


    /**
     * Make quote and invoice line items still editable
     * @since  1.0
     */ 
    function sliced_woocommerce_make_our_statuses_editable( $in_array ) {

        if( isset( $_GET['post'] ) || isset( $_POST['order_id'] ) ) {

            $order_id = isset( $_GET['post'] ) ? $_GET['post'] : $_POST['order_id'];
            
            if ( get_post_status( $order_id ) == 'wc-quote' ) {
                return true; 
            }

            if ( get_post_status( $order_id ) == 'wc-invoice' ) {
                $id = sliced_woocommerce_get_invoice_id( $order_id );
                if( ! has_term( 'paid', 'invoice_status', $id ) ) {
                   return true; 
                }
            }

        }
		
		return $in_array;

    }


    /**
     * Add a link/button for the user to view the invoice. Inserted after the order table
     * @since   1.0
     */
    function sliced_woocommerce_front_view_invoice_button( $order ) {
        $id = sliced_woocommerce_get_invoice_id( $order->get_id() ); 

        if( $id ) {
        ?>

            <a class="button btn btn-lg" href="<?php echo esc_url( sliced_get_the_link( $id ) ) ?>"><?php printf( esc_html__( 'View the %s', 'woo-invoices' ), sliced_get_label( $id ) ) ?></a>

        <?php 
        }
    }
    

    /**
     * Add view invoice action button to the orders listing in the admin
     * @since   1.0
     */
    function sliced_woocommerce_admin_view_invoice_button( $order ) {
        
        // do not show buttons for trashed orders
        if ( $order->get_status() == 'trash' ) {
            return;
        }

        $id = sliced_woocommerce_get_invoice_id( $order->get_id() );
        if( $id ) {

        ?>
            <a href="<?php echo esc_url( sliced_get_the_link( $id ) ) ?>" class="button tips sliced_woo" target="_blank" alt="<?php printf( esc_html__( 'View the %s', 'woo-invoices' ), sliced_get_label( $id ) ) ?>" data-tip="<?php printf( esc_html__( 'View the %s', 'woo-invoices' ), sliced_get_label( $id ) ) ?>"><span class="dashicons dashicons-media-default"></span></a>
         <?php

        }

    }


    /**
     * Adds the sliced invoices meta box container.
     *
     * @since 1.0.0
     */
    function sliced_add_meta_box_to_woocomerce_order() {

		if ( ! isset( $_GET['post'] ) ) {
			return;
		}
		
        // check if we have a published invoice
        $id = (int) $_GET['post'];
        if ( $id == null || $id == 0 ) {
            return;
		}

        $sliced_id = sliced_woocommerce_get_invoice_id( $id );
        if( $sliced_id ) {

            // add the meta box
            add_meta_box( 
                'sliced_invoices', 
                __( 'Sliced Invoices', 'woo-invoices' ), 'sliced_woocommerce_render_meta_box_content', 
                'shop_order', 
                'side', 
                'high'
            );

        }

    }



    /**
     * Render Meta Box content,
     *
     * @since 1.0.0
     */
    function sliced_woocommerce_render_meta_box_content() {
        
        $id = (int) $_GET['post'];
        $sliced_id = (int) sliced_woocommerce_get_invoice_id( $id );
        echo '<p>';
        printf( __( 'This order is tied to %1s %2s%3s.', 'sliced-invoices' ), ucwords( sliced_get_the_type( $sliced_id ) ), sliced_get_prefix( $sliced_id ), sliced_get_number( $sliced_id ) );
        echo '</p>';
        ?>

        <a href="<?php echo esc_url( sliced_get_the_link( $sliced_id ) ) ?>" class="button tips" target="_blank" alt="<?php printf( esc_html__( 'View the %s', 'woo-invoices' ), sliced_get_label( $sliced_id ) ) ?>" data-tip="<?php printf( esc_html__( 'View the %s', 'woo-invoices' ), sliced_get_label( $sliced_id ) ) ?>"><?php printf( esc_html__( 'View the %s', 'woo-invoices' ), sliced_get_label( $sliced_id ) ) ?></a>

        <?php

    }
	
	
	/**
	 * Get object properties according to the version of WooCommerce being used.
	 * Maintains backwards compatibility with older WooCommerce versions.
	 * 
	 * @since 1.0.6
	 */
	function sliced_woocommerce_get_object_property( $object, $object_type, $property ) {
	
		$WC = WooCommerce::instance();
			
		switch ( $object_type ) {
		
			case 'order':
				
				switch ( $property ) {
					
					case 'id':
						if ( version_compare( $WC->version, '3.0.0', '>=' ) ) {
							return $object->get_id();
						} else {
							return $object->id;
						}
						break;
						
					case 'payment_method':
						if ( version_compare( $WC->version, '3.0.0', '>=' ) ) {
							return $object->get_payment_method();
						} else {
							return $object->payment_method;
						}
						break;
					
				}
				
				break;
				
			case 'product':
				
				switch ( $property ) {
				}
				
				break;
		}
		
	}
