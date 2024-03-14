<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2017-2024 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoRarFiltersMessages' ) ) {

	class WpssoRarFiltersMessages {

		private $p;	// Wpsso class object.
		private $a;	// WpssoRar class object.

		/*
		 * Instantiated by WpssoRarFilters->__construct().
		 */
		public function __construct( &$plugin, &$addon ) {

			$this->p =& $plugin;
			$this->a =& $addon;

			$this->p->util->add_plugin_filters( $this, array(
				'messages_tooltip' => 2,
			) );
		}

		public function filter_messages_tooltip( $text, $msg_key ) {

			if ( 0 !== strpos( $msg_key, 'tooltip-rar_' ) ) {

				return $text;
			}

			switch ( $msg_key ) {

				case 'tooltip-rar_star_color_selected':	// Selected Star Rating Color.

					$text = __( 'The color for selected stars.', 'wpsso-ratings-and-reviews' );

					break;

				case 'tooltip-rar_star_color_default':	// Unselected Star Rating Color.

					$text = __( 'The border color for unselected stars.', 'wpsso-ratings-and-reviews' );

					break;

				case 'tooltip-rar_add_to':		// Rating Form for Post Types.

					$text = __( 'Enable or disable the ratings feature by public post type.', 'wpsso-ratings-and-reviews' ) . ' ';

					break;

				case 'tooltip-rar_rating_required':	// Rating Required to Submit Review.

					$text = __( 'A rating value must be selected to submit a review (enabled by default).', 'wpsso-ratings-and-reviews' );

					break;
			}

			return $text;
		}
	}
}
