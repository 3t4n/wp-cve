<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class WFFN_Compatibility_Enfold_Theme
 */
if ( ! class_exists( 'WFFN_Compatibility_Enfold_Theme' ) ) {
	class WFFN_Compatibility_Enfold_Theme {

		public function __construct() {

			add_filter( 'avf_builder_boxes', array( $this, 'allow_avia_builder_bwf_cpt' ) );
			add_filter( 'avf_alb_supported_post_types', array( $this, 'allow_avia_builder_bwf_cpt_mod' ), 10, 1 );
			add_filter( 'avf_metabox_layout_post_types', array( $this, 'allow_avia_layout_bwf_cpt_mod' ), 10, 1 );
		}

		public function is_enabled() {
			if ( class_exists( 'AviaBuilder' ) ) {
				return true;
			}

			return false;
		}

		public function allow_avia_builder_bwf_cpt( $metabox ) {
			foreach ( $metabox as &$meta ) {
				if ( $meta['id'] === 'avia_builder' || $meta['id'] === 'layout' ) {
					$meta['page'][] = 'wfacp_checkout';
					$meta['page'][] = 'wfocu_offer';
					$meta['page'][] = 'wfch_cart';
					$meta['page'][] = 'wffn_landing';
					$meta['page'][] = 'wffn_ty';
					$meta['page'][] = 'wffn_optin';
					$meta['page'][] = 'wffn_oty';
				}
			}

			return $metabox;
		}

		public function allow_avia_builder_bwf_cpt_mod( array $supported_post_types ) {
			$supported_post_types[] = 'wfacp_checkout';
			$supported_post_types[] = 'wfocu_offer';
			$supported_post_types[] = 'wfch_cart';
			$supported_post_types[] = 'wffn_landing';
			$supported_post_types[] = 'wffn_ty';
			$supported_post_types[] = 'wffn_optin';
			$supported_post_types[] = 'wffn_oty';

			return $supported_post_types;
		}

		public function allow_avia_layout_bwf_cpt_mod( array $supported_post_types ) {
			$supported_post_types[] = 'wfacp_checkout';
			$supported_post_types[] = 'wfocu_offer';
			$supported_post_types[] = 'wfch_cart';
			$supported_post_types[] = 'wffn_landing';
			$supported_post_types[] = 'wffn_ty';
			$supported_post_types[] = 'wffn_optin';
			$supported_post_types[] = 'wffn_oty';

			return $supported_post_types;
		}

	}

	WFFN_Plugin_Compatibilities::register( new WFFN_Compatibility_Enfold_Theme(), 'enfold_theme' );
}
