<?php

class PMCS_Shortocde {
	public function __construct() {
		add_shortcode( 'pmcs', array( $this, 'shortcode' ) );
	}

	public function shortcode( $atts ) {
		$args = array(
			'before_widget' => '<div class="pmcs-shortcode">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="pmcs-shortcode-heading">',
			'after_title' => '</h3>',
		);
		return $this->init( $atts, $args );
	}

	public function init( $atts, $args = array() ) {

		$instance  = wp_parse_args(
			$atts,
			array(
				'title'        => esc_html__( 'Currency Swicther', 'pmcs' ),
				'display_type' => '',
				'show_flag'    => '',
				'show_name'    => '',
			)
		);

		global $wp;
		$change                 = pmcs()->switcher->will_change();
		$active_currencies      = pmcs()->switcher->get_currencies();
		$current_currency       = pmcs()->switcher->get_current_currency();
		$woocommerce_currencies = pmcs()->switcher->get_woocommerce_currencies();
		$woocommerce_currency   = pmcs()->switcher->get_woocommerce_currency();
		$link                   = trailingslashit( home_url( $wp->request ) );
		$ul_list_html           = '';
		$parent_classes         = array( 'pmcs-top-level pmcs-item' );
		$items                  = '';
		$style                  = $instance['display_type'];
		$show_flag              = $instance['show_flag'];
		$name_type              = $instance['show_name'];

		if ( 'dropdown' == $style ) {
			$ul_list = pmcs()->switcher->get_currencies_li( true, $show_flag, $name_type );
			if ( $change ) {
				if ( 'code' != $name_type ) {
					$top_name = isset( $active_currencies[ $current_currency ] ) ? $active_currencies[ $current_currency ]['display_text'] : $woocommerce_currencies[ $current_currency ];
				} else {
					$top_name = $current_currency;
				}

				if ( $show_flag ) {
					$top_name = pmcs()->get_flag( $current_currency ) . $top_name;
				}
			} else {

				if ( 'code' != $name_type ) {
					$top_name = $woocommerce_currencies[ $woocommerce_currency ];
				} else {
					$top_name = $woocommerce_currency;
				}

				if ( $show_flag ) {
					$top_name = pmcs()->get_flag( $woocommerce_currency ) . $top_name;
				}
			}

			if ( $change ) {
				$parent_classes['parent'] = 'pmsc-has-children';
				if ( ! empty( $ul_list ) ) {
					$ul_list_html = '<ul class="pmcs-dropdown-currency">' . join( '', $ul_list ) . '</ul>';
				}
			} else {
				$parent_classes['parent'] = ' menu-item-no-children';
			}

			$items = '<div class="pmcs-dropdown"><span class="' . esc_attr( join( ' ', $parent_classes ) ) . '">' . $top_name . '</span>' . $ul_list_html . '';
			$items .= '</div>';
		} else {
			$ul_list = pmcs()->switcher->get_currencies_li( false, $show_flag, $name_type );
			if ( ! empty( $ul_list ) ) {
				$items = '<ul class="pmcs-list-currency">' . join( '', $ul_list ) . '</ul>';
			}
		}

		$html = '';

		$html .= $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			$html .= $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
		$html .= $items; // WPCS: XSS ok.
		$html .= $args['after_widget'];

		return $html;
	}

}
