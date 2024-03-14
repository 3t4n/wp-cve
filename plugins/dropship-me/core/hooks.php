<?php
/**
 * Author: Vitaly Kukin
 * Date: 13.05.2018
 * Time: 9:20
 */

add_action( 'admin_init', function() {
	
	if( DM_PLUGIN == 'woocommerce' && ! function_exists( 'adsw_move_to_ali' ) ) {
		
		dm_redirect_to_ali();
		
		add_action( 'woocommerce_product_write_panel_tabs', 'dm_product_data_tabs' );
		add_action( 'woocommerce_product_data_panels', 'dm_product_data_panels' );
		add_action( 'woocommerce_after_order_itemmeta', 'dm_before_order_itemmeta', 10, 3 );
		add_action( 'admin_head', 'dm_init_product_scripts' );
	}
} );

function dm_prepare_original_price( $row ) {
	
	$layout = '<div class="priceInfo">';
	
	if ( isset( $row['origPrice'] ) && $row['origPrice'] != $row['origSalePrice'] && $row['origPriceMax'] == 0 ) {
		
		$discount = $row['origPrice'] > 0 ? ( $row['origPrice'] - $row['origSalePrice'] ) * 100 / $row['origPrice'] : 0;
		
		$layout .= sprintf( '<div class="salePrice">US $%s</div>', $row['origSalePrice'] );
		$layout .= sprintf( '<div class="price"><strike>US $%s</strike> <span class="discount">(%s&#37; %s)</span></div>',
			$row['origPrice'], round( $discount ), __( 'off', 'dm' ) );
	} elseif ( isset( $row['origPrice'] ) && $row['origPriceMax'] != 0 && $row['origPriceMax'] != $row['origPrice'] &&
	           $row['origPrice'] == $row['origSalePrice']
	) {
		$layout .= sprintf( '<div class="salePrice">US $%s - US $%s</div>', $row['origPrice'], $row['origPriceMax'] );
	} elseif ( isset( $row['origPrice'] ) && $row['origPriceMax'] != 0 && $row['origPriceMax'] != $row['origPrice'] ) {
		
		$discount = ( $row['origPriceMax'] - $row['origSalePriceMax'] ) * 100 / $row['origPriceMax'];
		
		$layout .= sprintf( '<div class="salePrice">US $%s - US $%s</div>', $row['origSalePrice'], $row['origSalePriceMax'] );
		$layout .= sprintf( '<div class="price"><strike>US $%s - US $%s</strike> <span class="discount">(%s&#37; %s)</span></div>',
			$row['origPrice'], $row['origPriceMax'], round($discount), __( 'off', 'dm' ) );
	} elseif ( isset( $row['origPrice'] ) ) {
		$layout .= sprintf( '<div class="salePrice">US $%s</div>', $row['origPrice'] );
	}
	
	$layout .= '</div>';
	
	return $layout;
}

function dm_redirect_to_ali() {
	
	if ( ! isset( $_GET[ 'ads-move' ] ) || $_GET[ 'ads-move' ] == '' ) {
		return false;
	}
	
	$post_id = intval( $_GET[ 'ads-move' ] );
	
	global $wpdb;
	
	$row = $wpdb->get_row(
		$wpdb->prepare( "SELECT product_id FROM {$wpdb->prefix}adsw_ali_meta WHERE post_id = %d LIMIT 1", $post_id )
	);
	
	if ( empty( $row ) ) {
		return false;
	}
	
	$obj = new \dm\dmApi();
	$url = $obj->getRedirectLink( $row->product_id );

	if ( isset( $url[ 'redirect' ] ) && filter_var( $url[ 'redirect' ], FILTER_VALIDATE_URL ) ) {
		wp_redirect( $url[ 'redirect' ] );
		exit;
	}
	
	return false;
}
function dm_product_data_tabs() {
	
	$args = [
		'adswsupplier' => [
			'label'  => __( 'Supplier Info', 'dm' ),
			'target' => 'adswsupplier_product_data',
			'class'  => [ 'hide_if_grouped', 'hide_if_virtual', 'hide_if_external' ],
		],
	];
	
	foreach ( $args as $key => $tab ) { ?>
		
		<li class="<?php echo $key; ?>_options <?php echo $key; ?>_tab <?php echo implode( ' ' , (array) $tab[ 'class' ] ); ?>">
		<a href="#<?php echo $tab[ 'target' ]; ?>"><span><?php echo esc_html( $tab[ 'label' ] ); ?></span></a>
		</li><?php
	}
	
}

function dm_product_data_panels() {
	
	if( DM_PLUGIN == 'woocommerce' && ! function_exists( 'adsw_product_data_panels' ) ) {
		
		global $post, $wpdb;
		
		$row = $wpdb->get_row(
			$wpdb->prepare( "SELECT * FROM {$wpdb->prefix}adsw_ali_meta WHERE post_id = %d", $post->ID ), ARRAY_A
		);
		
		$args = [
			'product_id'       => '',
			'productUrl'       => '',
			'storeUrl'         => '',
			'storeName'        => '',
			'storeRate'        => '',
			'origPrice'        => '',
			'origPriceMax'     => '',
			'origSalePrice'    => '',
			'origSalePriceMax' => '',
			'needUpdate'       => 1,
			'feedbackUrl'      => ''
		];
		
		if( ! empty( $row ) ) {
			
			$args = dm_parse_args( $row, $args );
		}
		
		?>
		<div id="adswsupplier_product_data" class="panel woocommerce_options_panel hidden" style="padding:10px">
			<div id="review_animate_loader" class="fade-cover"><div class="loader"></div></div>
			
			<?php
			
			if( ! empty( $row[ 'productUrl' ] ) ) {
				
				printf(
					'<div class="supplier-info table-container">
	                    <div class="row table-item">
	                        <div class="col-md-4"><div class="store-link"><a href="%s" target="_blank">%s</a></div></div>
	                        <div class="col-md-4">%s</div>
	                        <div class="col-md-4">%s</div>
	                    </div>
	                </div>',
					$args[ 'storeUrl' ], $args[ 'storeName' ],
					! empty( $args[ 'storeRate' ] ) ? sprintf( '<img src="%s">', $args[ 'storeRate' ] ) : '',
					dm_prepare_original_price( $row )
				);
			}
			
			// Product ID
			woocommerce_wp_text_input( [
				'id'          => '_product_id',
				'label'       => __( 'Product ID', 'dm' ),
				'value'       => $args[ 'product_id' ],
				'placeholder' => '',
				'desc_tip'    => true,
				'description' => __( 'Product ID on AliExpress', 'dm' )
			] );
			
			// Product URL
			woocommerce_wp_text_input( [
				'id'          => '_productUrl',
				'label'       => __( 'Product URL', 'dm' ),
				'value'       => $args[ 'productUrl' ],
				'placeholder' => '',
				'description' => dm_is_url( $args[ 'productUrl' ] ) ?
					sprintf( '<a href="%s" target="_blank">%s</a>', $args[ 'productUrl' ], __( 'View Product', 'dm' ) ) :
					__( 'Enter url to AliExpress Product', 'dm' )
			] );
			
			// Store URL
			woocommerce_wp_text_input( [
				'id'          => '_storeUrl',
				'label'       => __( 'Store URL', 'dm' ),
				'value'       => $args[ 'storeUrl' ],
				'placeholder' => '',
				'description' => ''
			] );
			
			// Store Name
			woocommerce_wp_text_input( [
				'id'          => '_storeName',
				'label'       => __( 'Store Name', 'dm' ),
				'value'       => $args[ 'storeName' ],
				'placeholder' => '',
				'description' => ''
			] );
			
			?>
			<div class="adsw-toolbar">
				<button type="button" class="button save_adswsupplier button-primary"><?php _e( 'Save Supplier Info', 'dm' ); ?></button>
			</div>
		</div>
		
		<?php
	}
}

/**
 * @param integer $item_id
 * @param object $item
 * @param object $product
 *
 * @return null
 */
function dm_before_order_itemmeta( $item_id, $item, $product ) {
	
	if( empty( $product ) ) return;
	
	$order_id   = isset( $_GET['post'] ) ? intval( $_GET['post'] ) : 0;
	$set_class  = get_class($item);
	$product_id = ( $set_class != 'WC_Order_Item_Shipping' && $set_class != 'WC_Order_Refund' ) ? $item->get_product_id() : false;
	
	?>
	<div class="item-row-actions item-inline" data-item_id="<?php echo $item_id ?>">
		<span class="view">
            <strong><?php _e( 'Actions', 'dm' ) ?>:</strong>
        </span>
		
		<span class="view-affiliate">
            <a href="<?php echo admin_url( 'post.php?post=' . $order_id . '&action=edit&ads-move=' . $product_id ) ?>"
               class="color-green js-placeorder-manually"
               target="_blank"><?php _e( 'Place Order Manually', 'dm' ) ?></a>
        </span>
	</div>
	
	<?php
}

function dm_init_product_scripts() {
	
	$screen = get_current_screen();
	if ( isset( $screen->id ) && $screen->id == 'product' ) {
		
		wp_enqueue_script( 'productPost' );
		wp_enqueue_style( 'bootstrap' );
		wp_enqueue_style( 'dm-product' );
	}
}

function dm_save_adswsupplier() {
	
	if( ! current_user_can( 'level_9' ) ) die();
	
	global $wpdb;
	
	$data = $_POST[ 'data' ];
	
	$row = $wpdb->get_row(
		$wpdb->prepare( "SELECT * FROM {$wpdb->prefix}adsw_ali_meta WHERE post_id = %d", $data[ 'ID' ] ), ARRAY_A
	);
	
	$url = $data[ 'productUrl' ];
	
	$product_id = '';
	
	if( dm_is_url( $url ) ) {
		
		if( preg_match( '/\/(\d+_)?(\d+)\.html/', $url, $match ) ) {
			$product_id = $match[2];
		}
	}
	
	if( empty( $row ) ) {
		
		$wpdb->insert( $wpdb->prefix . 'adsw_ali_meta', [
			'post_id'    => $data[ 'ID' ],
			'product_id' => $product_id,
			'productUrl' => esc_url( $url ),
			'storeUrl'   => esc_url( $data[ 'storeUrl' ] ),
			'storeName'  => $data[ 'storeName' ],
		] );
		
		_e( 'Supplier Info has been saved!', 'dm' );
	} else {
		
		$wpdb->update( $wpdb->prefix . 'adsw_ali_meta', [
			'product_id' => $product_id,
			'productUrl' => esc_url( $url ),
			'storeUrl'   => esc_url( $data[ 'storeUrl' ] ),
			'storeName'  => $data[ 'storeName' ]
		], [
			'post_id'    => $data[ 'ID' ],
		] );
		
		_e( 'Supplier Info has been updated!', 'dm' );
	}
	
	die();
}
add_action( 'wp_ajax_dm_save_adswsupplier', 'dm_save_adswsupplier' );