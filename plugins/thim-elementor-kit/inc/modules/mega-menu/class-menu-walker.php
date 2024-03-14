<?php
namespace Thim_EL_Kit\Modules\MegaMenu;

class Main_Walker extends \Walker_Nav_Menu {

	public function thim_is_mega_menu( $menu_id ) : bool {
		if ( ! class_exists( 'Elementor\Plugin' ) ) {
			return false;
		}

		$enable = get_term_meta( $menu_id, Rest_API::ENABLE_MEGA_MENU, true );

		if ( ! absint( $enable ) ) {
			return false;
		}

		return true;
	}

	public function thim_is_mega_menu_item( $menu_id, $menu_item_id ) : bool {
		if ( ! $this->thim_is_mega_menu( $menu_id ) ) {
			return false;
		}

		$options = $this->thim_get_options( $menu_item_id );

		return $options['enableMegaMenu'] ?? false;
	}

	public function thim_get_options( $menu_item_id ) {
		static $output;

		if ( ! isset( $output[ $menu_item_id ] ) ) {
			$options       = Rest_API::DEFAULT_OPTIONS;
			$option_values = get_post_meta( $menu_item_id, Rest_API::META_KEY_OPTIONS, true );

			if ( ! empty( $option_values ) ) {
				$options = wp_parse_args( json_decode( $option_values, true ), Rest_API::DEFAULT_OPTIONS );
			}

			$output[ $menu_item_id ] = $options;
		}

		return $output[ $menu_item_id ];
	}

	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}

		$indent      = str_repeat( $t, $depth );
		$classes     = array( 'thim-ekits-menu__dropdown', 'sub-menu' );
		$class_names = implode( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$output .= "{$n}{$indent}<ul$class_names>{$n}";
	}

	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent  = str_repeat( $t, $depth );
		$output .= "$indent</ul>{$n}";
	}

	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent    = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . esc_attr( $item->ID );

		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		$class_names  = implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names .= ' nav-item';

		$options           = $this->thim_get_options( $item->ID );
		$menu_obj = wp_get_nav_menu_object($args->menu);
		$menu_id  = $menu_obj ? $menu_obj->term_id : 0;
		$is_mega_menu_item = $this->thim_is_mega_menu_item( absint( $menu_id), $item->ID );

		if ( in_array( 'menu-item-has-children', $classes ) || $is_mega_menu_item ) {
			$class_names .= ' thim-ekits-menu__has-dropdown';
		}

		if ( $is_mega_menu_item ) {
			$class_names .= ' thim-ekits-menu__mega-menu';
		}

		if ( in_array( 'current-menu-item', $classes ) ) {
			$class_names .= ' active';
		}

		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names . '>';

		$atts           = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';

		if ( '_blank' === $item->target && empty( $item->xfn ) ) {
			$atts['rel'] = 'noopener';
		} else {
			$atts['rel'] = $item->xfn;
		}

		$atts['href']         = ! empty( $item->url ) ? $item->url : '';
		$atts['aria-current'] = $item->current ? 'page' : '';

		$suffix = '';

		if ( $depth === 0 ) {
			$atts['class'] = 'thim-ekits-menu__nav-link';
		}

		// if ( $depth === 0 && in_array( 'menu-item-has-children', $classes ) ) {
		// 	$atts['class'] .= ' thim-ekits-menu__dropdown-toggle';
		// }

		if ( in_array( 'menu-item-has-children', $classes ) || $is_mega_menu_item ) {
			$suffix .= '<span class="thim-ekits-menu__icon"></span>';
		}

		if ( $depth > 0 ) {
			$manual_class   = array_values( $classes )[0] . ' thim-ekits-menu__nav-link ' . 'thim-ekits-menu__dropdown-item';
			$atts ['class'] = $manual_class;
		}

		if ( in_array( 'current-menu-item', $item->classes ) ) {
			$atts['class'] .= ' active';
		}

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( is_scalar( $value ) && '' !== $value && false !== $value ) {
				$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$title = apply_filters( 'the_title', $item->title, $item->ID );

		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		$item_output  = $args->before;
		$item_output .= '<a' . wp_kses_post( $attributes ) . '>';

		if ( $options['enableIcon'] ) {
			$item_output .= '<span class="thim-ekits-menu__has-icon">';

			if ( $options['iconType'] === 'icon' && ! empty( $options['icon'] ) ) {
				$icon_style = '';

				if ( ! empty( $options['iconColor'] ) ) {
					$icon_style .= 'color: ' . esc_attr( $options['iconColor'] ) . ';';
				}

				if ( ! empty( $options['iconSize'] ) ) {
					$icon_style .= 'font-size: ' . esc_attr( empty( preg_replace( '/[0-9]+/', '', $options['iconSize'] ) ) ? absint( $options['iconSize'] ) . 'px' : $options['iconSize'] ) . ';';
				}

				$item_output .= '<i class="' . esc_attr( $options['icon'] ) . '" style="' . esc_attr( $icon_style ) . '"></i>';
			} elseif ( $options['iconType'] === 'upload' && ! empty( $options['iconUpload']['url'] ) ) {
				$icon_style = '';

				if ( ! empty( $options['iconWidth'] ) ) {
					$icon_style .= 'width:' . esc_attr( empty( preg_replace( '/[0-9]+/', '', $options['iconWidth'] ) ) ? absint( $options['iconWidth'] ) . 'px' : $options['iconWidth'] ) . ';';
				}

				if ( ! empty( $options['iconHeight'] ) ) {
					$icon_style .= 'height:' . esc_attr( empty( preg_replace( '/[0-9]+/', '', $options['iconHeight'] ) ) ? absint( $options['iconHeight'] ) . 'px' : $options['iconHeight'] ) . ';';
				}

				$item_output .= '<img src="' . esc_url( $options['iconUpload']['url'] ) . '" alt="" style="' . esc_attr( $icon_style ) . '" />';
			}

			$item_output .= '</span>';
		}

		$item_output .= $args->link_before . $title . $args->link_after;

		if ( $options['enableBadge'] && ! empty( $options['badgeText'] ) ) {
			$badge_style = '';

			if ( ! empty( $options['badgeBgColor'] ) ) {
				$badge_style .= 'background-color: ' . esc_attr( $options['badgeBgColor'] ) . ';';
			}

			if ( ! empty( $options['badgeColor'] ) ) {
				$badge_style .= 'color: ' . esc_attr( $options['badgeColor'] ) . ';';
			}

			if ( ! empty( $options['badgeSize'] ) ) {
				$badge_style .= 'font-size: ' . esc_attr( empty( preg_replace( '/[0-9]+/', '', $options['badgeSize'] ) ) ? absint( $options['badgeSize'] ) . 'px' : $options['badgeSize'] ) . ';';
			}

			$item_output .= ' <span class="thim-ekits-menu__badge" style="' . esc_attr( $badge_style ) . '">' . esc_html( $options['badgeText'] ) . '</span>';
		}

		$item_output .= '</a>' . wp_kses_post( $suffix );
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	public function end_el( &$output, $item, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}

		if ( $depth === 0 ) {
			$menu_obj = wp_get_nav_menu_object($args->menu);
			$menu_id  = $menu_obj ? $menu_obj->term_id : 0;
			if ( $this->thim_is_mega_menu_item( absint( $menu_id), $item->ID ) && class_exists( 'Elementor\Plugin' ) ) {
				$options      = $this->thim_get_options( $item->ID );
				$mega_menu_id = get_post_meta( $item->ID, Rest_API::META_KEY, true );

				$style          = '';
				$classes        = '';
				$data_container = '';

				if ( ! empty( $options['widthMenu'] ) ) {
					$width = is_numeric( $options['widthMenu'] ) ? absint( $options['widthMenu'] ) . 'px' : $options['widthMenu'];

					$style .= 'width: ' . esc_attr( $width ) . '; min-width: ' . esc_attr( $width ) . ';';
				}

				if ( ! empty( $options['menuPosition'] ) ) {
					if ( $options['menuPosition'] === 'left' ) {
						$classes .= ' thim-ekits-menu__content--left';
						$style   .= ' left: auto; right: 0;';
					} elseif ( $options['menuPosition'] === 'right' ) {
						$classes .= ' thim-ekits-menu__content--right';
						$style   .= ' left: 0; right: auto;';
					} elseif ( $options['menuPosition'] === 'center' ) {
						$classes .= ' thim-ekits-menu__content--center';
						$style   .= ' left: 50%; transform: translateX(-50%);';
					}
				}

				if ( ! empty( $options['menuType'] ) ) {
					$classes       .= ' thim-ekits-menu__content--' . esc_attr( $options['menuType'] );
					$data_container = apply_filters( 'thim_ekit/mega_menu/menu_container/class', '' );
				}

				if ( $mega_menu_id ) {
					$output .= '<ul class="thim-ekits-menu__content ' . esc_attr( $classes ) . '" style="' . esc_attr( $style ) . '"  data-container="' . esc_attr( $data_container ) . '">';
					$output .= \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $mega_menu_id );
					$output .= '</ul>';
				}
			}
			$output .= "</li>{$n}";
		}
	}
}
