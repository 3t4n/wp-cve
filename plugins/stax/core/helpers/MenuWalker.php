<?php

namespace Stax;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MenuWalker extends \Walker_Nav_Menu {
	/**
	 * @param $output
	 * @param int $depth
	 * @param array $args
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$output .= '<ul class="submenu">';
	}

	/**
	 * @param string $output
	 * @param int $depth
	 * @param array $args
	 */
	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		$output .= '</ul>';
	}

	/**
	 * @param string $output
	 * @param \WP_Post $item
	 * @param int $depth
	 * @param array $args
	 * @param int $id
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
		} else {
			$t = "\t";
		}
		$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;

		if ( in_array( 'menu-item-has-children', $classes ) ) {
			$classes[] = 'has-submenu';
		}

		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names . '>';

		$atts           = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
		$atts['href']   = ! empty( $item->url ) ? $item->url : '';
		$atts['class']  = 'item';

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value      = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$title = apply_filters( 'the_title', $item->title, $item->ID );

		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		$data = ! empty( $item->title ) ? ' data-hover="' . esc_attr( $item->title ) . '"' : '';

		$item_output = isset( $args->before ) ? $args->before : '';
		$item_output .= '<a' . $attributes . $data . '>';
		$item_output .= isset( $args->link_before ) ? $args->link_before : '';
		$item_output .= $title;
		$item_output .= isset( $args->link_after ) ? $args->link_after : '';
		$item_output .= '</a>';
		$item_output .= isset( $args->after ) ? $args->after : '';

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	/**
	 * @param string $output
	 * @param \WP_Post $item
	 * @param int $depth
	 * @param array $args
	 */
	public function end_el( &$output, $item, $depth = 0, $args = array() ) {
		$output .= '</li>';
	}

	/**
	 * Fallback when empty menu
	 *
	 * @return string
	 */
	public static function fallback() {
		return __( 'Empty Menu', 'stax' );
	}
}
