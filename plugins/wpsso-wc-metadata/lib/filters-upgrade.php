<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2020-2024 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoWcmdFiltersUpgrade' ) ) {

	class WpssoWcmdFiltersUpgrade {

		private $p;	// Wpsso class object.
		private $a;	// WpssoWcmd class object.

		/*
		 * Instantiated by WpssoWcmdFilters->__construct().
		 */
		public function __construct( &$plugin, &$addon ) {

			$this->p =& $plugin;
			$this->a =& $addon;

			$this->p->util->add_plugin_filters( $this, array(
				'rename_options_keys' => 1,
				'upgraded_options'    => 2,
				'upgraded_md_options' => 2,
			) );
		}

		public function filter_rename_options_keys( $rename_options ) {

			$rename_options[ 'wpssowcmd' ] = array(
				21 => array(	// Renamed for WPSSO WCMD v3.0.0.
					'wcmd_enable_product_mfr_part_no'              => 'wcmd_edit_product_mfr_part_no',
					'wcmd_enable_product_isbn'                     => 'wcmd_edit_product_isbn',
					'wcmd_enable_product_gtin14'                   => 'wcmd_edit_product_gtin14',
					'wcmd_enable_product_gtin13'                   => 'wcmd_edit_product_gtin13',
					'wcmd_enable_product_gtin12'                   => 'wcmd_edit_product_gtin12',
					'wcmd_enable_product_gtin8'                    => 'wcmd_edit_product_gtin8',
					'wcmd_enable_product_gtin'                     => 'wcmd_edit_product_gtin',
					'wcmd_enable_product_length_value'             => 'wcmd_edit_product_length_value',
					'wcmd_enable_product_width_value'              => 'wcmd_edit_product_width_value',
					'wcmd_enable_product_height_value'             => 'wcmd_edit_product_height_value',
					'wcmd_enable_product_weight_value'             => 'wcmd_edit_product_weight_value',
					'wcmd_enable_product_fluid_volume_value'       => 'wcmd_edit_product_fluid_volume_value',
					'wcmd_input_label_product_mfr_part_no'         => 'wcmd_edit_label_product_mfr_part_no',
					'wcmd_input_label_product_isbn'                => 'wcmd_edit_label_product_isbn',
					'wcmd_input_label_product_gtin14'              => 'wcmd_edit_label_product_gtin14',
					'wcmd_input_label_product_gtin13'              => 'wcmd_edit_label_product_gtin13',
					'wcmd_input_label_product_gtin12'              => 'wcmd_edit_label_product_gtin12',
					'wcmd_input_label_product_gtin8'               => 'wcmd_edit_label_product_gtin8',
					'wcmd_input_label_product_gtin'                => 'wcmd_edit_label_product_gtin',
					'wcmd_input_label_product_length_value'        => 'wcmd_edit_label_product_length_value',
					'wcmd_input_label_product_width_value'         => 'wcmd_edit_label_product_width_value',
					'wcmd_input_label_product_height_value'        => 'wcmd_edit_label_product_height_value',
					'wcmd_input_label_product_weight_value'        => 'wcmd_edit_label_product_weight_value',
					'wcmd_input_label_product_fluid_volume_value'  => 'wcmd_edit_label_product_fluid_volume_value',
					'wcmd_input_holder_product_mfr_part_no'        => 'wcmd_edit_holder_product_mfr_part_no',
					'wcmd_input_holder_product_isbn'               => 'wcmd_edit_holder_product_isbn',
					'wcmd_input_holder_product_gtin14'             => 'wcmd_edit_holder_product_gtin14',
					'wcmd_input_holder_product_gtin13'             => 'wcmd_edit_holder_product_gtin13',
					'wcmd_input_holder_product_gtin12'             => 'wcmd_edit_holder_product_gtin12',
					'wcmd_input_holder_product_gtin8'              => 'wcmd_edit_holder_product_gtin8',
					'wcmd_input_holder_product_gtin'               => 'wcmd_edit_holder_product_gtin',
					'wcmd_input_holder_product_length_value'       => 'wcmd_edit_holder_product_length_value',
					'wcmd_input_holder_product_width_value'        => 'wcmd_edit_holder_product_width_value',
					'wcmd_input_holder_product_height_value'       => 'wcmd_edit_holder_product_height_value',
					'wcmd_input_holder_product_weight_value'       => 'wcmd_edit_holder_product_weight_value',
					'wcmd_input_holder_product_fluid_volume_value' => 'wcmd_edit_holder_product_fluid_volume_value',
					'wcmd_info_label_product_mfr_part_no'          => 'wcmd_show_label_product_mfr_part_no',
					'wcmd_info_label_product_isbn'                 => 'wcmd_show_label_product_isbn',
					'wcmd_info_label_product_gtin14'               => 'wcmd_show_label_product_gtin14',
					'wcmd_info_label_product_gtin13'               => 'wcmd_show_label_product_gtin13',
					'wcmd_info_label_product_gtin12'               => 'wcmd_show_label_product_gtin12',
					'wcmd_info_label_product_gtin8'                => 'wcmd_show_label_product_gtin8',
					'wcmd_info_label_product_gtin'                 => 'wcmd_show_label_product_gtin',
					'wcmd_info_label_product_length_value'         => 'wcmd_show_label_product_length_value',
					'wcmd_info_label_product_width_value'          => 'wcmd_show_label_product_width_value',
					'wcmd_info_label_product_height_value'         => 'wcmd_show_label_product_height_value',
					'wcmd_info_label_product_weight_value'         => 'wcmd_show_label_product_weight_value',
					'wcmd_info_label_product_fluid_volume_value'   => 'wcmd_show_label_product_fluid_volume_value',
				),
			);

			return $rename_options;
		}

		public function filter_upgraded_options( $opts, $defs ) {

			$prev_version = $this->p->opt->get_version( $opts, 'wpssowcmd' );

			/*
			 * Create new "Show" options for WPSSO WCMD v3.0.0 based on the existing "Edit" options.
			 */
			if ( $prev_version > 0 && $prev_version <= 21 ) {

				foreach ( array(
					'wcmd_edit_product_mfr_part_no'              => 'wcmd_show_product_mfr_part_no',
					'wcmd_edit_product_isbn'                     => 'wcmd_show_product_isbn',
					'wcmd_edit_product_gtin14'                   => 'wcmd_show_product_gtin14',
					'wcmd_edit_product_gtin13'                   => 'wcmd_show_product_gtin13',
					'wcmd_edit_product_gtin12'                   => 'wcmd_show_product_gtin12',
					'wcmd_edit_product_gtin8'                    => 'wcmd_show_product_gtin8',
					'wcmd_edit_product_gtin'                     => 'wcmd_show_product_gtin',
					'wcmd_edit_product_length_value'             => 'wcmd_show_product_length_value',
					'wcmd_edit_product_width_value'              => 'wcmd_show_product_width_value',
					'wcmd_edit_product_height_value'             => 'wcmd_show_product_height_value',
					'wcmd_edit_product_weight_value'             => 'wcmd_show_product_weight_value',
					'wcmd_edit_product_fluid_volume_value'       => 'wcmd_show_product_fluid_volume_value',
				) as $opt_edit_key => $opt_show_key ) {

					if ( isset( $opts[ $opt_edit_key ] ) ) {

						$opts[ $opt_show_key ] = $opts[ $opt_edit_key ];
					}
				}
			}

			return $opts;
		}

		public function filter_upgraded_md_options( $md_opts, $mod ) {

			/*
			 * Remove old meta data.
			 */
			if ( ! empty( $mod[ 'id' ] ) && ! empty( $mod[ 'obj' ] ) ) {	// Just in case.

				$prev_version = $this->p->opt->get_version( $md_opts, 'wpssowcmd' );

				if ( $prev_version > 0 && $prev_version <= 17 ) {

					$md_config = WpssoWcmdConfig::get_md_config();

					foreach ( $md_config as $md_key => $cfg ) {

						$opt_key = 'plugin_cf_' . $md_key;

						if ( ! empty( $this->p->options[ $opt_key ] ) ) {

							$meta_value_key   = $this->p->options[ $opt_key ];
							$meta_units_key   = preg_replace( '/_value$/', '', $meta_value_key ) . '_units';
							$meta_unit_wc_key = $meta_value_key . '_unit_wc';

							$mod[ 'obj' ]->delete_meta( $mod[ 'id' ], $meta_units_key );
							$mod[ 'obj' ]->delete_meta( $mod[ 'id' ], $meta_unit_wc_key );
						}
					}
				}
			}

			return $md_opts;
		}
	}
}
