<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2017-2024 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoRarSubmenuRatingsReviews' ) && class_exists( 'WpssoAdmin' ) ) {

	class WpssoRarSubmenuRatingsReviews extends WpssoAdmin {

		public function __construct( &$plugin, $id, $name, $lib, $ext ) {

			$this->p =& $plugin;

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			$this->menu_id   = $id;
			$this->menu_name = $name;
			$this->menu_lib  = $lib;
			$this->menu_ext  = $ext;

			$this->menu_metaboxes = array(
				'settings' => _x( 'Ratings and Reviews', 'metabox title', 'wpsso-ratings-and-reviews' ),
			);
		}

		protected function get_table_rows( $page_id, $metabox_id, $tab_key = '', $args = array() ) {

			$table_rows = array();
			$match_rows = trim( $page_id . '-' . $metabox_id . '-' . $tab_key, '-' );

			switch ( $match_rows ) {

				case 'ratings-reviews-settings':

					$table_rows[ 'rar_star_color_selected' ] = '' .
						$this->form->get_th_html( _x( 'Selected Star Rating Color', 'option label', 'wpsso-ratings-and-reviews' ),
							$css_class = '', $css_id = 'rar_star_color_selected' ) .
						'<td>' . $this->form->get_input_color( 'rar_star_color_selected' ) . '</td>';

					$table_rows[ 'rar_star_color_default' ] = '' .
						$this->form->get_th_html( _x( 'Unselected Star Rating Color', 'option label', 'wpsso-ratings-and-reviews' ),
							$css_class = '', $css_id = 'rar_star_color_default' ) .
						'<td>' . $this->form->get_input_color( 'rar_star_color_default' ) . '</td>';

					$table_rows[ 'rar_add_to' ] = '' .
						$this->form->get_th_html( _x( 'Show Rating Form for Post Types', 'option label', 'wpsso-ratings-and-reviews' ),
							$css_class = '', $css_id = 'rar_add_to' ) .
						'<td>' . $this->form->get_checklist_post_types( $name_prefix = 'rar_add_to' ) . '</td>';

					$table_rows[ 'rar_rating_required' ] = '' .
						$this->form->get_th_html( _x( 'Rating Required to Submit Review', 'option label', 'wpsso-ratings-and-reviews' ),
							$css_class = '', $css_id = 'rar_rating_required' ) .
						'<td>' . $this->form->get_checkbox( 'rar_rating_required' ) . '</td>';

					break;
			}

			return $table_rows;
		}
	}
}
