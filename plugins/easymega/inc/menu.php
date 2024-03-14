<?php

class MegaMenu_WP_Menu {
	public $special_themes;

	function __construct() {
		/**
		 * @see Walker_Nav_Menu
		 */
		add_filter( 'walker_nav_menu_start_el', array( $this, 'walker_nav_menu_start_el' ), 35, 4 );
		add_filter( 'nav_menu_css_class', array( $this, 'nav_menu_css_class' ), 95, 4 );
		add_filter( 'pre_wp_nav_menu', array( $this, 'wp_nav_menu' ), 95, 2 );

		$this->special_themes = array(
			'wp-coupon',
		);
	}

	function nav_menu_css_class( $classes, $item, $args = array(), $depth = 0 ) {
		if ( $item->mega_enable ) {
			$classes[] = 'mega-item';
			$current_theme = get_option( 'stylesheet' );
			if ( in_array( $current_theme, $this->special_themes ) ) {
				$classes[] = 'megamenu-dropdown';
			} else {
				$classes[] = 'dropdown';
			}

			$classes[] = 'menu-item-has-children';
			if ( $item->mega_menu_settings['layout'] == 'full' ) {
				$classes[] = 'mega-full';

				if ( $item->mega_menu_settings['content_layout'] == 'full' ) {
					$classes[] = 'mega-content-full';
				} else {
					$classes[] = 'mega-content-boxed';
				}
			} else {
				$classes[] = 'mega-boxed';
			}
		}
		return $classes;
	}



	/**
	 *
	 * @see wp_nav_menu
	 *
	 * @param array $args
	 * @return bool|mixed|string|void
	 */
	function wp_nav_menu( $nav_menu, $args ) {

		// Get the nav menu based on the requested menu
		$menu = wp_get_nav_menu_object( $args->menu );

		// Get the nav menu based on the theme_location
		if ( ! $menu && $args->theme_location && ( $locations = get_nav_menu_locations() ) && isset( $locations[ $args->theme_location ] ) ) {
			$menu = wp_get_nav_menu_object( $locations[ $args->theme_location ] );
		}

		// get the first menu that has items if we still can't find a menu
		if ( ! $menu && ! $args->theme_location ) {
			$menus = wp_get_nav_menus();
			foreach ( $menus as $menu_maybe ) {
				if ( $menu_items = wp_get_nav_menu_items( $menu_maybe->term_id, array( 'update_post_term_cache' => false ) ) ) {
					$menu = $menu_maybe;
					break;
				}
			}
		}

		if ( empty( $args->menu ) ) {
			$args->menu = $menu;
		}

		// If the menu exists, get its items.
		if ( $menu && ! is_wp_error( $menu ) && ! isset( $menu_items ) ) {
			$menu_items = wp_get_nav_menu_items( $menu->term_id, array( 'update_post_term_cache' => false ) );
		}

		// Previewing menu
		if ( isset( $menu->term_id ) ) {
			if ( ! MegaMenu_WP::is_mega_nav_active( $menu->term_id ) ) {
				return null;
			}
		} else {
			return null;
		}

		$args->menu_class = 'megamenu-wp ' . $args->menu_class;

		/*
		 * If no menu was found:
		 *  - Fall back (if one was specified), or bail.
		 *
		 * If no menu items were found:
		 *  - Fall back, but only if no theme location was specified.
		 *  - Otherwise, bail.
		 */
		if ( ( ! $menu || is_wp_error( $menu ) || ( isset( $menu_items ) && empty( $menu_items ) && ! $args->theme_location ) )
			&& isset( $args->fallback_cb ) && $args->fallback_cb && is_callable( $args->fallback_cb ) ) {
			return call_user_func( $args->fallback_cb, (array) $args );
		}

		if ( ! $menu || is_wp_error( $menu ) ) {
			return false;
		}

		$nav_menu = $items = '';

		$show_container = false;
		if ( $args->container ) {
			/**
			 * Filters the list of HTML tags that are valid for use as menu containers.
			 *
			 * @since 3.0.0
			 *
			 * @param array $tags The acceptable HTML tags for use as menu containers.
			 *                    Default is array containing 'div' and 'nav'.
			 */
			$allowed_tags = apply_filters( 'wp_nav_menu_container_allowedtags', array( 'div', 'nav' ) );
			if ( is_string( $args->container ) && in_array( $args->container, $allowed_tags ) ) {
				$show_container = true;
				$class = $args->container_class ? ' class="' . esc_attr( $args->container_class ) . '"' : ' class="menu-' . $menu->slug . '-container"';
				$id = $args->container_id ? ' id="' . esc_attr( $args->container_id ) . '"' : '';
				$nav_menu .= '<' . $args->container . $id . $class . '>';
			}
		}

		// Set up the $menu_item variables
		_wp_menu_item_classes_by_context( $menu_items );

		$sorted_menu_items = $menu_items_with_children = array();
		foreach ( (array) $menu_items as $menu_item ) {
			$sorted_menu_items[ $menu_item->menu_order ] = $menu_item;
			if ( $menu_item->menu_item_parent ) {
				$menu_items_with_children[ $menu_item->menu_item_parent ] = true;
			}
		}

		// Add the menu-item-has-children class where applicable
		if ( $menu_items_with_children ) {
			foreach ( $sorted_menu_items as &$menu_item ) {
				if ( isset( $menu_items_with_children[ $menu_item->ID ] ) ) {
					$menu_item->classes[] = 'menu-item-has-children';
				}
			}
		}

		unset( $menu_items, $menu_item );

		/**
		 * Filters the sorted list of menu item objects before generating the menu's HTML.
		 *
		 * @since 3.1.0
		 *
		 * @param array    $sorted_menu_items The menu items, sorted by each menu item's menu order.
		 * @param stdClass $args              An object containing wp_nav_menu() arguments.
		 */
		$sorted_menu_items = apply_filters( 'wp_nav_menu_objects', $sorted_menu_items, $args );

		$items .= walk_nav_menu_tree( $sorted_menu_items, $args->depth, $args );
		unset( $sorted_menu_items );

		// Attributes
		$wrap_id = $args->menu_id;
		$menu_id_slugs[] = $args->menu_id;

		$wrap_class = 'megamenu-wp-wrapper ' . $args->menu_class ? $args->menu_class : '';

		/**
		 * Filters the HTML list content for navigation menus.
		 *
		 * @since 3.0.0
		 *
		 * @see wp_nav_menu()
		 *
		 * @param string   $items The HTML list content for the menu items.
		 * @param stdClass $args  An object containing wp_nav_menu() arguments.
		 */
		$items = apply_filters( 'wp_nav_menu_items', $items, $args );
		/**
		 * Filters the HTML list content for a specific navigation menu.
		 *
		 * @since 3.0.0
		 *
		 * @see wp_nav_menu()
		 *
		 * @param string   $items The HTML list content for the menu items.
		 * @param stdClass $args  An object containing wp_nav_menu() arguments.
		 */
		$items = apply_filters( "wp_nav_menu_{$menu->slug}_items", $items, $args );

		// Don't print any markup if there are no items at this point.
		if ( empty( $items ) ) {
			return false;
		}

		$nav_menu .= sprintf( $args->items_wrap, esc_attr( $wrap_id ), esc_attr( $wrap_class ), $items );
		unset( $items );

		if ( $show_container ) {
			$nav_menu .= '</' . $args->container . '>';
		}

		/**
		 * Filters the HTML content for navigation menus.
		 *
		 * @since 3.0.0
		 *
		 * @see wp_nav_menu()
		 *
		 * @param string   $nav_menu The HTML content for the navigation menu.
		 * @param stdClass $args     An object containing wp_nav_menu() arguments.
		 */
		$nav_menu = apply_filters( 'wp_nav_menu', $nav_menu, $args );

		return $nav_menu;
	}

	function valid_css_unit( $string ) {
		$string = trim( $string );
		$r = preg_match( '/^(auto|0)$|^[+-]?[0-9]+.?([0-9]+)?(px|em|ex|%|in|cm|mm|pt|pc)?$/', $string, $matches );
		if ( ! $r && $string ) {
			return '0px';
		} elseif ( $r ) {
			$n = $matches[0];
			// if is number only
			if ( preg_match( '/^[+-]?[0-9]+.?([0-9]?)$/', $n ) ) {
				return $n . 'px';
			}
			return $n;
		}
		return false;
	}

	function setup_item_css( $args = array(), $settings = array() ) {
	
			return false;
		
	}

	function walker_nav_menu_start_el( $item_output, $item, $depth, $args ) {

		if ( $depth != 0 ) {
			return $item_output;
		}

		if ( isset( $args->menu ) && is_object( $args->menu ) ) {
			if ( ! MegaMenu_WP::is_mega_nav_active( $args->menu->term_id ) ) {
				return $item_output;
			}
		}

		if ( ! $item->mega_enable ) {
			return $item_output;
		}

		$mega = new MegaMenu_WP_Menu_Item( $item );
		$mega_content = $mega->render();

		if ( ! $mega_content ) {
			return $item_output;
		}

		$item_output = '';

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
		$atts['href']   = ! empty( $item->url ) ? $item->url : '';
		$atts['class']  = 'mega-link';

		/**
		 * Filters the HTML attributes applied to a menu item's anchor element.
		 *
		 * @since 3.6.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array $atts {
		 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
		 *
		 *     @type string $title  Title attribute.
		 *     @type string $target Target attribute.
		 *     @type string $rel    The rel attribute.
		 *     @type string $href   The href attribute.
		 * }
		 * @param WP_Post  $item  The current menu item.
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );
		$title = apply_filters( 'megamenu_nav_item_title', $item->title, $item->ID );

		/**
		 * Filters a menu item's title.
		 *
		 * @see Walker_Nav_Menu
		 *
		 * @since 4.4.0
		 *
		 * @param string   $title The menu item's title.
		 * @param WP_Post  $item  The current menu item.
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$item->classes[] = 'menu-item-has-children';
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		$item_output .= $args->before;
		$item_output .= '<a' . $attributes . '>';
		$item_output .= $args->link_before . $title . $args->link_after;
		// $item_output .= '<span class="mega-top-span"></span>';
		$item_output .= '</a>';

		$item_output .= $args->after;

		$atts = array();
		if ( $mega->mega_menu_settings['layout'] != 'full' ) {
			$cw = absint( $mega->mega_menu_settings['content_width'] );
			if ( $cw > 0 ) {
				$atts['data-width'] = $cw;
			}

			$atts['data-position'] = $mega->mega_menu_settings['content_position'];

		}

		// Custom CSS
		if ( isset( $mega->mega_menu_settings['style'] ) ) {
			$css = $this->setup_item_css( $mega->mega_menu_settings['style'], $mega->mega_menu_settings );
			if ( $css ) {
				$atts['data-style'] = $css;
			}
		}
		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$ul = $end_url = '';
		if ( $args->walker ) {
			$args->walker->start_lvl( $ul, 2 );
			$args->walker->end_lvl( $end_url, 2 );
		} else {
			$ul = '<ul class="sub-menu">';
			$end_url = '</ul>';
		}

		preg_match_all( '/<ul[^>]+class=([\'"])(.+?)\1[^>]*>/i', $ul, $result );

		if ( ! empty( $result ) ) {
			// Found class.
			$ul = '<ul class="mega-content ' . esc_attr( $result[2][0] ) . '"' . $attributes . '>';
		} else {
			$ul = '<ul class="mega-content"' . $attributes . '>';
		}

		$item_output .= $ul;
			$item_output .= '<li class="mega-content-li">';
				$item_output .= '<div class="mega-content-inner">';
					$item_output .= '<div class="mega-inner">';
						$item_output .= $mega_content;
					$item_output .= '</div>';
				$item_output .= '</div>';
			$item_output .= '</li>';
		$item_output .= $end_url;

		return $item_output;
	}


}


new MegaMenu_WP_Menu();
