<?php

// No direct access allowed.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPIMShortcodes {
	private static $args = [
		'before_widget' => '<div class="wpinventory-shortcode wpinventory-shortcode-%s">',
		'before_title'  => '<h2>',
		'after_title'   => '</h2>',
		'after_widget'  => '</div>'
	];

	protected static function args( $name ) {
		$args                  = self::$args;
		$args['before_widget'] = sprintf( $args['before_widget'], $name );

		return $args;
	}

	public static function init() {
		add_shortcode( 'wpinventory_categories', [ __CLASS__, 'category' ] );
		add_shortcode( 'wpinventory_latest_items', [ __CLASS__, 'latest_items' ] );
	}

	/**
	 * @param $instance   - an array of optional values:
	 *                    [ 'title' => string - default 'Inventory Categories',
	 *                    'page_id' => number - ID of page outbound links should go to,
	 *                    'sort_order' => string - column to sort categories by - default is 'category_name'
	 *                    'display_as' => string - 'list' or 'dropdown',
	 *                    'include_counts' => number - 0 or 1 - whether to include the count of items in the category
	 *
	 * @return string
	 */
	public static function category( $instance ) {
		$default = [
			'title'          => WPIMCore::__( 'Inventory Categories' ),
			'page_id'        => '',
			'sort_order'     => 'category_name',
			'display_as'     => 'list',
			'include_counts' => '0'
		];

		$instance = wp_parse_args( (array) $instance, $default );


		return wpinventory_category_widget_shortcode( self::args( 'category' ), $instance );
	}

	/**
	 * @param $instance   - an array of optional values:
	 *                    [ 'title' => string - default 'Inventory Categories',
	 *                    'page_id' => number - ID of page outbound links should go to,
	 *                    'category_id' => number - ID of category to list items for, if desired
	 *                    'number' => number - number of latest items to show - default is 4
	 *
	 * @return string
	 */
	public static function latest_items( $instance ) {
		$default = [
			'title'       => WPIMCore::__( 'Latest Items' ),
			'page_id'     => '',
			'category_id' => '',
			'number'      => '4'
		];

		$instance = wp_parse_args( (array) $instance, $default );

		return wpinventory_latest_items_widget_shortcode( self::args( 'latest-items' ), $instance );
	}
}


/**
 * Function responsible for rendering either a shortcode or widget of "Categories"
 * @used by WPInventory_Categories_Widget
 * @used by wpinventory_categories shortcode
 *
 * @param $args
 * @param $instance
 *
 * @return string|void
 */
function wpinventory_category_widget_shortcode( $args, $instance ) {
    $wpim_widget_page_id = ( ! empty( $instance['page_id'] ) ) ? $instance['page_id'] : NULL;
	$content             = '';

	if ( ! $wpim_widget_page_id ) {
		$content .= '<!-- Page not set in widget.  Defaulting to current page / post -->';
		global $post;
		if ( $post ) {
			$wpim_widget_page_id = $post->ID;
		} else {
			return;
		}
	}

	$content .= $args['before_widget'];
	if ( $instance['title'] ) {
		$content .= $args['before_title'] . esc_attr($instance['title']) . $args['after_title'];
	}

	$wpim_categories = new WPIMCategory();
	$categories      = $wpim_categories->get_all( [ 'order' => $instance['sort_order'] ] );

	$list = ( $instance['display_as'] != 'list' ) ? FALSE : TRUE;

	$content .= ( $list ) ? '<ol>' : '<select name="inventory_category_list" onchange="if (this.value) window.location.href=this.value"><option value="">' . WPIMCore::__( 'Choose Category...' ) . '</option>';

	foreach ( $categories AS $category ) {
		$category_link = $wpim_categories->get_category_permalink( $wpim_widget_page_id, $category->category_id, $category->category_name );
		if ( $list ) {
			$content .= '<li class="category_' . $category->category_id . ' category_' . esc_attr($wpim_categories->get_class( $category->category_name )) . '">';
			$content .= '<a href="' . esc_url($category_link) . '">' . esc_attr($category->category_name) . '</a>';
			$content .= '</li>';
		} else {
			$content .= '<option value="' . esc_url($category_link) . '">' . esc_attr($category->category_name) . '</option>';
		}
	}

	$content .= ( $list ) ? '</ol>' : '</select>';
	$content .= $args['after_widget'];

	return $content;
}

/**
 * Function responsible for rendering either a shortcode or widget of "Latest Items"
 * @used by WPInventory_Latest_Items_Widget
 * @used by wpinventory_latst_items shortcode
 *
 * @param $args
 * @param $instance
 *
 * @return string|void
 */
function wpinventory_latest_items_widget_shortcode( $args, $instance ) {
	global $wpim_widget_page_id;
	$wpim_widget_page_id = ( ! empty( $instance['page_id'] ) ) ? $instance['page_id'] : NULL;

	// Due to "do_actions" inside this space, we need to keep "echos" and use output buffer
	ob_start();
	if ( ! $wpim_widget_page_id ) {
		echo '<!-- Page not set in widget.  Defaulting to current page / post -->';
		$wpim_widget_page_id = get_queried_object_id();
	}

	do_action( 'wpim_before_latest_items' );

	echo wp_kses( $args['before_widget'], 'post' );
	if ( $instance['title'] ) {
		echo esc_attr( $args['before_title'] ) . esc_attr( $instance['title'] ) . esc_attr( $args['after_title'] );
	}

	$number = (int) $instance['number'];
	$number = max( 1, min( 10, $number ) );

	$loop_args = [
		'page_size' => $number,
		'order'     => 'inventory_date_added DESC'
	];

	$loop_args = apply_filters( 'wpim_latest_items_filter_args', $loop_args );

	if ( $instance['category_id'] ) {
		$loop_args['category_id'] = $instance['category_id'];
	}

	$custom_loop = new WPIMLoop();
	$custom_loop->set_single( TRUE );
	$custom_loop->load_items( $loop_args );

	global $WPIMLoop;
	$old_loop = $WPIMLoop;
	wpinventory_set_loop( $custom_loop );

	wpinventory_get_template_part( 'widget-latest-items-loop' );

	$WPIMLoop = $old_loop;
	do_action( 'wpim_after_latest_items' );
	echo wp_kses( $args['after_widget'], 'post' );

	return ob_get_clean();
}

WPIMShortcodes::init();
