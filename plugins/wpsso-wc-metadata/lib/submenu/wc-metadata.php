<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2020-2024 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoWcmdSubmenuWcMetadata' ) && class_exists( 'WpssoAdmin' ) ) {

	class WpssoWcmdSubmenuWcMetadata extends WpssoAdmin {

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
				'settings' => _x( 'WooCommerce Metadata', 'metabox title', 'wpsso-wc-metadata' ),
			);
		}

		protected function get_table_rows( $page_id, $metabox_id, $tab_key = '', $args = array() ) {

			$table_rows = array();
			$match_rows = trim( $page_id . '-' . $metabox_id . '-' . $tab_key, '-' );

			switch ( $match_rows ) {

				case 'wc-metadata-settings':

					$table_rows[] = '<td colspan="6">' . $this->p->msgs->get( 'info-wcmd-custom-fields' ) . '</td>';

					$table_rows[] = '' .
						'<th></th>' .
						'<th class="checkbox option_col"><h3>' . __( 'Edit', 'wpsso-wc-metadata' ) . '</h3></th>' .
						'<th class="wcmd_edit_label option_col"><h3>' . __( 'Label', 'wpsso-wc-metadata' ) . '</h3></th>' .
						'<th class="wcmd_edit_holder option_col"><h3>' . __( 'Placeholder', 'wpsso-wc-metadata' ) . '</h3></th>' .
						'<th class="checkbox option_col"><h3>' . __( 'Show', 'wpsso-wc-metadata' ) . '</h3></th>' .
						'<th class="wide option_col"><h3>' . __( 'Additional Information Label', 'wpsso-wc-metadata' ) . '</h3></th>';

					$md_config = WpssoWcmdConfig::get_md_config();

					foreach ( $md_config as $md_suffix => $cfg ) {

						$html = $this->form->get_th_html_locale( _x( $cfg[ 'label' ], 'option label', 'wpsso-wc-metadata' ),
							$css_class = '', $css_id = 'wcmd_edit_' . $md_suffix );

						if ( WpssoWcmdConfig::is_editable( $md_suffix ) ) {

							$html .= '<td class="checkbox">' . $this->form->get_checkbox( 'wcmd_edit_' . $md_suffix ) . '</td>';

							$html .= '<td class="wcmd_edit_label">' . $this->form->get_input_locale( 'wcmd_edit_label_' . $md_suffix,
								$css_class = 'wcmd_edit_label' ) . '</td>';

							$html .= '<td class="wcmd_edit_holder">' . $this->form->get_input_locale( 'wcmd_edit_holder_' . $md_suffix,
								$css_class = 'wcmd_edit_holder' ) . '</td>';

						} else $html .= '<td colspan="3"></td>';

						if ( WpssoWcmdConfig::is_showable( $md_suffix ) ) {

							$html .= '<td class="checkbox">' . $this->form->get_checkbox( 'wcmd_show_' . $md_suffix ) . '</td>';

							$html .= '<td class="wide">' . $this->form->get_input_locale( 'wcmd_show_label_' . $md_suffix,
								$css_class = 'wcmd_show_label' ) . '</td>';

						} else $html .= '<td colspan="2"></td>';

						$table_rows[ 'wcmd_edit_' . $md_suffix ] = $html;
					}

					break;
			}

			return $table_rows;
		}
	}
}
