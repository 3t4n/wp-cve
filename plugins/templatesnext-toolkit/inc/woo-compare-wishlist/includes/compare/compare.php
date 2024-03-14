<?php

if ( is_admin() ) {
	require_once TX_WC_COMPARE_WISHLIST_PATH . 'includes/compare/settings.php';
}

if ( 'yes' !== get_option( 'tx_woocompare_enable' ) ) {
	return;
}
require_once 'buttons.php';

if ( ! is_admin() ) {

	require_once 'shortcode.php';
}
require_once 'widget.php';

if ( ! session_id() ) {
	session_start();
}
/*	
if (version_compare(PHP_VERSION, '7.0.0', '>=')) {
    if ( ! session_id() ) {
		//session_start();
            
		session_start(array(
            'cache_limiter' => 'private_no_expire',
            'read_and_close' => false,
        ));
		session_write_close();			
    }
} else {
	if ( ! session_id() ) {
		session_start();
	}
}
*/
	

// register action hooks
add_action( 'wp_enqueue_scripts', 'tx_woocompare_setup_plugin' );

add_action( 'wp_ajax_tx_woocompare_add_to_list', 'tx_woocompare_process_button_action' );
add_action( 'wp_ajax_nopriv_tx_woocompare_add_to_list', 'tx_woocompare_process_button_action' );

add_action( 'wp_ajax_tx_woocompare_remove', 'tx_woocompare_process_remove_button_action' );
add_action( 'wp_ajax_nopriv_tx_woocompare_remove', 'tx_woocompare_process_remove_button_action' );

add_action( 'wp_ajax_tx_woocompare_empty', 'tx_woocompare_process_empty_button_action' );
add_action( 'wp_ajax_nopriv_tx_woocompare_empty', 'tx_woocompare_process_empty_button_action' );

add_action( 'wp_ajax_tx_woocompare_update', 'tx_woocompare_process_ajax' );
add_action( 'wp_ajax_nopriv_tx_woocompare_update', 'tx_woocompare_process_ajax' );

/**
 * Registers scripts, styles and page endpoint.
 *
 * @since 1.0.0
 * @action init
 */
function tx_woocompare_setup_plugin() {

	wp_enqueue_style( 'tm-woocompare' );
	wp_enqueue_script( 'tm-woocompare' );
}

/**
 * Returns compare list.
 *
 * @sicne 1.0.0
 *
 * @return array The array of product ids to compare.
 */
function tx_woocompare_get_list() {

	$list = ! empty( $_SESSION['tm-woocompare-list'] ) ? $_SESSION['tm-woocompare-list'] : array();

	if ( ! empty( $list ) ) {
		$list  = explode( ':', $list );
	}
	return $list;
}

/**
 * Sets new list of products to compare.
 *
 * @since 1.0.0
 *
 * @param array $list The new array of products to compare.
 */
function tx_woocompare_set_list( $list ) {
	$value = implode( ':', $list );
	$_SESSION['tm-woocompare-list'] = $value;
}

/**
 * Returns compare page link.
 *
 * @since 1.0.0
 *
 * @return string The compare pare link on success, otherwise FALSE.
 */
function tx_woocompare_get_page_link() {

	$page_id = intval( get_option( 'tx_woocompare_page' ) );

	if ( ! $page_id ) {

		return false;
	}
	$page_link = get_permalink( $page_id );

	if ( ! $page_link ) {

		return false;
	}
	return trailingslashit( $page_link );
}

/**
 * Processes buttons actions.
 *
 * @since 1.0.0
 *
 * @action wp_ajax_tx_woocompare_add_to_list
 */
function tx_woocompare_process_button_action() {

	$id     = filter_input( INPUT_POST, 'pid', FILTER_SANITIZE_NUMBER_INT );
	$list   = tx_woocompare_get_list();
	$key    = array_search( $id, $list );
	$button = false;

	if ( false !== $key ) {

		$action = 'remove';

		tx_woocompare_remove( $id );

	} else {

		$action = 'add';
		$button = json_decode( filter_input( INPUT_POST, 'single' ) ) ? tx_woocompare_page_button() : false;

		tx_woocompare_add( $id );
	}

	wp_send_json_success( array(
		'action'         => $action,
		'comparePageBtn' => $button,
		'counts'         => tx_woocompare_get_counts_data(),
	) );
}

/**
 * Returns message when is no products in compare.
 *
 * @since 1.0.2
 *
 * @return string The message
 */
function tx_woocompare_empty_message() {

	$empty_text = get_option( 'tx_woocompare_empty_text', __( 'No products found to compare.', 'tx' ) );
	$html       = sprintf( '<p class="tm-woocompare-empty">%s</p>', $empty_text );

	return apply_filters( 'tx_woocompare_empty_message', $html, $empty_text );
}

/**
 * Processes main ajax handler.
 *
 * @since 1.0.0
 *
 * @action wp_ajax_tx_woocompare_update
 */
function tx_woocompare_process_ajax() {

	$is_page   = json_decode( filter_input( INPUT_POST, 'isComparePage' ) );
	$is_widget = json_decode( filter_input( INPUT_POST, 'isWidget' ) );
	$json      = array();

	if ( $is_page ) {

		$json['compareList'] = tx_woocompare_list_render_table();
	}
	if ( $is_widget ) {

		$json['widget'] = tx_woocompare_list_render_widget();
	}

	$json['counts'] = tx_woocompare_get_counts_data();

	wp_send_json_success( $json );
}

/**
 * Processes remove button action.
 *
 * @since 1.0.0
 *
 * @action wp_ajax_tx_woocompare_remove
 */
function tx_woocompare_process_remove_button_action() {
	$product_id = filter_input( INPUT_POST, 'pid', FILTER_SANITIZE_NUMBER_INT );
	tx_woocompare_remove( $product_id );
	tx_woocompare_process_ajax();
}

/**
 * Processes empty button action.
 *
 * @since 1.0.0
 *
 * @action wp_ajax_tx_woocompare_empty
 */
function tx_woocompare_process_empty_button_action() {

	tx_woocompare_set_list( array() );
	tx_woocompare_process_ajax();
}

/**
 * Adds product to compare list.
 *
 * @since 1.0.0
 *
 * @param int $id The product id to add to the compare list.
 */
function tx_woocompare_add( $id ) {

	$list   = tx_woocompare_get_list();
	$list[] = $id;

	tx_woocompare_set_list( $list );
}

/**
 * Removes product from compare list.
 *
 * @since 1.0.0
 *
 * @param int $id The product id to remove from compare list.
 */
function tx_woocompare_remove( $id ) {

	$list = tx_woocompare_get_list();

	foreach ( wp_parse_id_list( $id ) as $id ) {

		$key = array_search( $id, $list );

		if ( false !== $key ) {

			unset( $list[$key] );
		}
	}
	tx_woocompare_set_list( $list );
}

/**
 * Get products added to compare.
 *
 * @since 1.0.0
 *
 * @param array $list The array of products ids.
 * @return object The list of products
 */
function tx_woocompare_get_products( $list ) {

	$args = array(
		'post_type'      => 'product',
		'post__in'       => $list,
		'orderby'        => 'post__in',
		'posts_per_page' => -1
	);
	$products = new WP_Query( $args );

	return $products;
}

/**
 * Renders compare list.
 *
 * @since 1.0.0
 *
 * @param array $atts The array of attributes to show in the table.
 * @return string Compare table HTML.
 */
function tx_woocompare_list_render( $atts = array() ) {

	$tx_wc_compare_wishlist = tx_wc_compare_wishlist();
	$content                = array();
	$content[]              = '<div class="woocommerce tm-woocompare-list">';
	$content[]              = '<div class="woocommerce tm-woocompare-wrapper">';
	$content[]              = tx_woocompare_list_render_table( $atts );
	$content[]              = '</div>';
	$content[]              = $tx_wc_compare_wishlist->get_loader();
	$content[]              = '</div>';

	return implode( "\n", $content );
}

/**
 * Renders compare widget.
 *
 * @since 1.0.0
 *
 * @return string Wishlist widget HTML.
 */
function tx_woocompare_list_render_widget() {

	$list = tx_woocompare_get_list();

	if ( empty( $list ) ) {

		return tx_woocompare_empty_message();
	}
	$templater = tx_wc_compare_wishlist_templater();
	$products  = tx_woocompare_get_products( $list );
	$template  = get_option( 'tx_woocompare_widget_template' );
	$template  = $templater->get_template_by_name( $template, 'tm-woocompare' );

	if( ! $template ) {

		$template = $templater->get_template_by_name( 'widget.tmpl', 'tm-woocompare' );
	}
	$content                = array();
	$tx_wc_compare_wishlist = tx_wc_compare_wishlist();

	if ( $products->have_posts() ) {

		$content[] = '<div class="tm-woocompare-widget-products">' . "\n";

		while ( $products->have_posts() ) {

			$products->the_post();

			global $product;

			if ( empty( $product ) ) {

				continue;
			}
			$pid          = method_exists( $product, 'get_id' ) ? $product->get_id() : get_the_id();
			$pid          = $tx_wc_compare_wishlist->get_original_product_id( $pid );
			$dismiss_icon = apply_filters( 'tx_woocompare_dismiss_icon', '<span class="dashicons dashicons-dismiss"></span>' );
			$content[]    = '<div class="tm-woocompare-widget-product">' . "\n";
			$content[]    = '<span class="tm-woocompare-remove" data-id="' . $pid . '">' . $dismiss_icon . '</span>';
			$content[]    = $templater->parse_template( $template );
			$content[]    = '</div>';
		}
		$content[] = '</div>';
		$content[] = '<a href="' . tx_woocompare_get_page_link() . '" class="button btn btn-default compare_link_btn">' . get_option( 'tx_woocompare_page_btn_text', __( 'Compare products' , 'tx' ) ) . '</a>';
		$content[] = '<button type="button" class="button btn btn-primary btn-danger tm-woocompare-empty">' . get_option( 'tx_woocompare_empty_btn_text', __( 'Empty compare' , 'tx' ) ) . '</button>';
		$content[] = $tx_wc_compare_wishlist->get_loader();
	}
	wp_reset_query();

	return implode( "\n", $content );
}

/**
 * Renders compare table.
 *
 * @since 1.0.0
 *
 * @param array $selected_attributes Coming soon.
 *
 * @return string Wishlist table HTML.
 */
function tx_woocompare_list_render_table( $selected_attributes = array() ) {

	$list = tx_woocompare_get_list();

	if ( empty( $list ) ) {

		return tx_woocompare_empty_message();
	}

	$templater        = tx_wc_compare_wishlist_templater();
	$products         = tx_woocompare_get_products( $list );
	$products_content = array();
	$template         = get_option( 'tx_woocompare_page_template' );
	$template         = $templater->get_template_by_name( $template, 'tm-woocompare' );

	if( ! $template ) {

		$template = $templater->get_template_by_name( 'page.tmpl', 'tm-woocompare' );
	}

	$replace_data = $templater->get_replace_data();

	while ( $products->have_posts() ) {

		$products->the_post();

		global $product;

		if ( empty( $product ) ) {

			continue;
		}
		$pid = method_exists( $product, 'get_id' ) ? $product->get_id() : get_the_id();
		$pid = tx_wc_compare_wishlist()->get_original_product_id( $pid );
		preg_match_all( $templater->macros_regex(), $template, $matches );

		if( ! empty( $matches[1] ) ) {

			foreach ( $matches[1] as $match ) {

				$macros   = array_filter( explode( ' ', $match, 2 ) );
				$callback = strtolower( $macros[0] );
				$attr     = isset( $macros[1] ) ? shortcode_parse_atts( $macros[1] ) : array();

				if ( ! isset( $replace_data[ $callback ] ) ) {

					continue;
				}
				$callback_func = $replace_data[ $callback ];

				if ( ! is_callable( $callback_func ) ) {

					continue;
				}
				$content = call_user_func( $callback_func, $attr );

				if( 'attributes' == $callback ) {

					$products_content[$pid][$callback] = $content;

				} else {

					$products_content[$pid][] = $content;
				}
			}
		}
	}
	wp_reset_query();

	$parsed_products = tx_woocompare_parse_products( $products_content );

	return tx_woocompare_compare_list_get_table( $parsed_products, $products );
}

/**
 * Get compare table.
 *
 * @since 1.0.1
 *
 * @param array $content The parsed products content.
 * @param object $products The products.
 *
 * @return string Wishlist table HTML.
 */
function tx_woocompare_compare_list_get_table( $content, $products ) {

	$html = array();
	$i    = 0;

	foreach ( $content as $key => $row ) {

		$row = array_filter( $row );

		$i++;

		if( empty( $row ) ) {

			continue;
		}
		if ( 1 == $i ) {

			$html[] = '<table class="tm-woocompare-table tablesaw" data-tablesaw-mode="swipe">';
			$html[] = '<thead>';
		}
		if ( 2 == $i ) {

			$html[] = '<tbody>';
		}
		$html[] = '<tr class="tm-woocompare-row">';

		if ( 1 == $i ) {

			$tag    = 'th';
			$html[] = '<th class="tm-woocompare-heading-cell title" data-tablesaw-priority="persist" scope="col" data-tablesaw-sortable-col>';

		} else {

			$tag    = 'td';
			$html[] = '<th class="tm-woocompare-heading-cell title">';
		}
		if ( 'string' === gettype( $key ) ) {

			$html[] = $key;
		}
		$html[] = '</th>';

		while ( $products->have_posts() ) {

			$products->the_post();

			global $product;

			$atts = '';

			if ( 1 == $i ) {
				$atts = ' scope="col" data-tablesaw-sortable-col';
			}

			$html[] = '<' . $tag . ' class="tm-woocompare-cell"' . $atts . '>';
			$pid    = method_exists( $product, 'get_id' ) ? $product->get_id() : get_the_id();
			$pid    = tx_wc_compare_wishlist()->get_original_product_id( $pid );

			if ( 1 == $i ) {

				$dismiss_icon = apply_filters( 'tx_woocompare_dismiss_icon', '<span class="dashicons dashicons-dismiss"></span>' );
				$html[]       = '<div class="tm-woocompare-remove" data-id="' . $pid . '">' . $dismiss_icon . '</div>';
			}
			$html[] = $row[$pid];
			$html[] = '</' . $tag . '>';
		}
		$html[] = '</tr>';

		if ( 1 == $i ) {

			$html[] = '</thead>';
		}
		if ( $i == count( $content ) ) {

			$html[] = '</tbody>';
			$html[] = '</table>';
		}
	}
	wp_reset_query();

	return implode( "\n", $html );
}

/**
 * Parse products attributes.
 *
 * @since 1.0.0
 *
 * @param array $attributes The products attributes.
 *
 * @return array parsed products attributes.
 */
function tx_woocompare_parse_products_attributes( $attributes ) {

	$rebuilded_attributes = array();

	foreach ( $attributes as $id => $attribute ) {

		foreach ( $attribute as $attr_name => $attribute_value ) {

			$rebuilded_attributes[$attr_name][$id] = $attribute_value;
		}
	}
	foreach ( $rebuilded_attributes as $attr_name => $attr_products ) {

		foreach ( $attributes as $id => $attribute ) {

			if ( ! array_key_exists( $id, $attr_products ) ) {

				$rebuilded_attributes[$attr_name][$id] = '&#8212;';
			}
		}
	}
	return $rebuilded_attributes;
}

/**
 * Parse products.
 *
 * @since 1.0.0
 *
 * @param array $products_content The products content.
 *
 * @return array parsed products content.
 */
function tx_woocompare_parse_products( $products_content ) {

	$parsed_products = array();

	foreach ( $products_content as $product_id => $product_content_arr ) {

		foreach ( $product_content_arr as $key => $product_content ) {

			$parsed_products[$key][$product_id] = $product_content;
		}
	}
	if( array_key_exists( 'attributes', $parsed_products ) && ! empty( $parsed_products['attributes'] ) ) {

		$attributes      = tx_woocompare_parse_products_attributes( $parsed_products['attributes'] );
		$key             = array_search( 'attributes', array_keys( $parsed_products ), true );
		$before          = array_slice( $parsed_products, 0, $key, true );
		$after           = array_slice( $parsed_products, ( $key + 1 ), null, true );
		$parsed_products = array_merge( $before, $attributes, $after );
	}
	return $parsed_products;
}

add_action( 'wp_ajax_tx_compare_get_fragments',        'tx_woocompare_update_fragments' );
add_action( 'wp_ajax_nopriv_tx_compare_get_fragments', 'tx_woocompare_update_fragments' );

/**
 * Update compare counts on page load and products status change
 *
 * @since  1.1.0
 * @return void
 */
function tx_woocompare_update_fragments() {
	wp_send_json_success( tx_woocompare_get_counts_data() );
}

/**
 * Returns cart counts
 *
 * @since  1.1.0
 * @return array
 */
function tx_woocompare_get_counts_data() {

	$count   = sprintf( '%d', count( tx_woocompare_get_list() ) );
	$default = apply_filters( 'tx_compare_default_count', array( '.menu-compare > a' => $count ) );

	return array(
		'defaultFragments' => $default,
		'customFragments'  => apply_filters( 'tx_compare_refreshed_fragments', array() )
	);
}
