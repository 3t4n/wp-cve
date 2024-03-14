<?php
/**
 * Plugin Name: NS Category Widget
 * Plugin URI: https://www.nilambar.net/2013/12/ns-category-widget-wordpress-plugin.html
 * Description: A widget plugin for listing categories and taxonomies in the way you want.
 * Version: 3.1.7
 * Author: Nilambar Sharma
 * Author URI: https://www.nilambar.net
 * Text Domain: ns-category-widget
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package NS_Category_Widget
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'NS_CATEGORY_WIDGET_VERSION', '3.1.7' );
define( 'NS_CATEGORY_WIDGET_SLUG', 'ns-category-widget' );
define( 'NS_CATEGORY_WIDGET_BASENAME', basename( dirname( __FILE__ ) ) );
define( 'NS_CATEGORY_WIDGET_DIR', rtrim( plugin_dir_path( __FILE__ ), '/' ) );
define( 'NS_CATEGORY_WIDGET_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );

// Init autoload.
require_once NS_CATEGORY_WIDGET_DIR . '/vendor/autoload.php';
require_once NS_CATEGORY_WIDGET_DIR . '/vendor/ernilambar/optioner/optioner.php';

// Public-Facing Functionality.
require_once NS_CATEGORY_WIDGET_DIR . '/public/class-ns-category-widget.php';
require_once NS_CATEGORY_WIDGET_DIR . '/widgets/nscw-widget.php';

register_activation_hook( __FILE__, array( 'NS_Category_Widget', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'NS_Category_Widget', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'NS_Category_Widget', 'get_instance' ) );

// Dashboard and Administrative Functionality.
if ( is_admin() ) {
	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-ns-category-widget-admin.php' );
	add_action( 'plugins_loaded', array( 'NS_Category_Widget_Admin', 'get_instance' ) );
}

/**
 * Register plugin widgets.
 *
 * @since 1.0.0
 */
function nscw_register_plugin_widgets() {
	$obj_nscw = NS_Category_Widget::get_instance();

	$nscw_field_enable_ns_category_widget = $obj_nscw->get_option( 'nscw_field_enable_ns_category_widget' );

	if ( true === rest_sanitize_boolean( $nscw_field_enable_ns_category_widget ) ) {
		register_widget( 'NSCW_Widget' );
	}
}

add_action( 'widgets_init', 'nscw_register_plugin_widgets' );

class NSCW_Walker_Category extends Walker_Category {

    /**
     * Starts the element output.
     *
     * @since 2.1.0
     *
     * @see Walker::start_el()
     *
     * @param string $output   Used to append additional content (passed by reference).
     * @param object $category Category data object.
     * @param int    $depth    Optional. Depth of category in reference to parents. Default 0.
     * @param array  $args     Optional. An array of arguments. See wp_list_categories(). Default empty array.
     * @param int    $id       Optional. ID of the current category. Default 0.
     */
    public function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
    	/** This filter is documented in wp-includes/category-template.php */
    	$cat_name = apply_filters( 'list_cats', esc_attr( $category->name ), $category );

    	// Don't generate an element if the category name is empty.
    	if ( '' === $cat_name ) {
    		return;
    	}

    	$atts         = array();
    	$atts['href'] = get_term_link( $category );

    	if ( $args['use_desc_for_title'] && ! empty( $category->description ) ) {
    		/**
    		 * Filters the category description for display.
    		 *
    		 * @since 1.2.0
    		 *
    		 * @param string $description Category description.
    		 * @param object $category    Category object.
    		 */
    		$atts['title'] = strip_tags( apply_filters( 'category_description', $category->description, $category ) );
    	}

    	/**
    	 * Filters the HTML attributes applied to a category list item's anchor element.
    	 *
    	 * @since 5.2.0
    	 *
    	 * @param array   $atts {
    	 *     The HTML attributes applied to the list item's `<a>` element, empty strings are ignored.
    	 *
    	 *     @type string $href  The href attribute.
    	 *     @type string $title The title attribute.
    	 * }
    	 * @param WP_Term $category Term data object.
    	 * @param int     $depth    Depth of category, used for padding.
    	 * @param array   $args     An array of arguments.
    	 * @param int     $id       ID of the current category.
    	 */
    	$atts = apply_filters( 'category_list_link_attributes', $atts, $category, $depth, $args, $id );

    	$attributes = '';
    	foreach ( $atts as $attr => $value ) {
    		if ( is_scalar( $value ) && '' !== $value && false !== $value ) {
    			$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
    			$attributes .= ' ' . $attr . '="' . $value . '"';
    		}
    	}

    	if ( ! empty( $args['show_count'] ) ) {
    		$cat_name .= ' (' . number_format_i18n( $category->count ) . ')';
    	}

    	$link = sprintf(
    		'<a%s>%s</a>',
    		$attributes,
    		$cat_name
    	);

    	if ( ! empty( $args['feed_image'] ) || ! empty( $args['feed'] ) ) {
    		$link .= ' ';

    		if ( empty( $args['feed_image'] ) ) {
    			$link .= '(';
    		}

    		$link .= '<a href="' . esc_url( get_term_feed_link( $category->term_id, $category->taxonomy, $args['feed_type'] ) ) . '"';

    		if ( empty( $args['feed'] ) ) {
    			/* translators: %s: Category name. */
    			$alt = ' alt="' . sprintf( __( 'Feed for all posts filed under %s', 'ns-category-widget' ), $cat_name ) . '"';
    		} else {
    			$alt   = ' alt="' . $args['feed'] . '"';
    			$name  = $args['feed'];
    			$link .= empty( $args['title'] ) ? '' : $args['title'];
    		}

    		$link .= '>';

    		if ( empty( $args['feed_image'] ) ) {
    			$link .= $name;
    		} else {
    			$link .= "<img src='" . esc_url( $args['feed_image'] ) . "'$alt" . ' />';
    		}

    		$link .= '</a>';

    		if ( empty( $args['feed_image'] ) ) {
    			$link .= ')';
    		}
    	}

    	if ( 'list' == $args['style'] ) {
    		$output     .= "\t<li";
    		$css_classes = array(
    			'cat-item',
    			'cat-item-' . $category->term_id,
    		);

    		if ( ! empty( $args['current_category'] ) ) {
    			// 'current_category' can be an array, so we use `get_terms()`.
    			$_current_terms = get_terms(
    				array(
    					'taxonomy'   => $category->taxonomy,
    					'include'    => $args['current_category'],
    					'hide_empty' => false,
    				)
    			);

    			foreach ( $_current_terms as $_current_term ) {
    				if ( $category->term_id == $_current_term->term_id ) {
    					$css_classes[] = 'current-cat';
    					$link          = str_replace( '<a', '<a aria-current="page"', $link );
    				} elseif ( $category->term_id == $_current_term->parent ) {
    					$css_classes[] = 'current-cat-parent';
    				}
    				while ( $_current_term->parent ) {
    					if ( $category->term_id == $_current_term->parent ) {
    						$css_classes[] = 'current-cat-ancestor';
    						break;
    					}
    					$_current_term = get_term( $_current_term->parent, $category->taxonomy );
    				}
    			}
    		}

    		/**
    		 * Filters the list of CSS classes to include with each category in the list.
    		 *
    		 * @since 4.2.0
    		 *
    		 * @see wp_list_categories()
    		 *
    		 * @param array  $css_classes An array of CSS classes to be applied to each list item.
    		 * @param object $category    Category data object.
    		 * @param int    $depth       Depth of page, used for padding.
    		 * @param array  $args        An array of wp_list_categories() arguments.
    		 */
    		$css_classes = implode( ' ', apply_filters( 'category_css_class', $css_classes, $category, $depth, $args ) );
    		$css_classes = $css_classes ? ' class="' . esc_attr( $css_classes ) . '"' : '';

    		$output .= $css_classes;
    		$output .= ">$link\n";
    	} elseif ( isset( $args['separator'] ) ) {
    		$output .= "\t$link" . $args['separator'] . "\n";
    	} else {
    		$output .= "\t$link<br />\n";
    	}
    }
}
