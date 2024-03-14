<?php

namespace LIBRARY;

class WXRImporter extends \AwesomeMotive\WPContentImporter2\WXRImporter {

	public function __construct( $options = array() ) {
		parent::__construct( $options );
		$current_user_obj = wp_get_current_user();
		$this->mapping['user_slug'][ $current_user_obj->user_login ] = $current_user_obj->ID;

		if ( class_exists( 'WooCommerce' ) ) {
			add_filter( 'wxr_importer.pre_process.term', array( $this, 'woocommerce_product_attributes_registration' ), 10, 1 );
		}
	}

	public function get_importer_data() {
		return array(
			'mapping'            => $this->mapping,
			'requires_remapping' => $this->requires_remapping,
			'exists'             => $this->exists,
			'user_slug_override' => $this->user_slug_override,
			'url_remap'          => $this->url_remap,
			'featured_images'    => $this->featured_images,
		);
	}

	public function set_importer_data( $data ) {
		$this->mapping            = empty( $data['mapping'] ) ? array() : $data['mapping'];
		$this->requires_remapping = empty( $data['requires_remapping'] ) ? array() : $data['requires_remapping'];
		$this->exists             = empty( $data['exists'] ) ? array() : $data['exists'];
		$this->user_slug_override = empty( $data['user_slug_override'] ) ? array() : $data['user_slug_override'];
		$this->url_remap          = empty( $data['url_remap'] ) ? array() : $data['url_remap'];
		$this->featured_images    = empty( $data['featured_images'] ) ? array() : $data['featured_images'];
	}
	
	public function woocommerce_product_attributes_registration( $data ) {
		global $wpdb;

		if ( strstr( $data['taxonomy'], 'pa_' ) ) {
			if ( ! taxonomy_exists( $data['taxonomy'] ) ) {
				$attribute_name = wc_sanitize_taxonomy_name( str_replace( 'pa_', '', $data['taxonomy'] ) );

				if ( ! in_array( $attribute_name, wc_get_attribute_taxonomies() ) ) {
					$attribute = array(
						'attribute_label'   => $attribute_name,
						'attribute_name'    => $attribute_name,
						'attribute_type'    => 'select',
						'attribute_orderby' => 'menu_order',
						'attribute_public'  => 0
					);
					$wpdb->insert( $wpdb->prefix . 'woocommerce_attribute_taxonomies', $attribute );
					delete_transient( 'wc_attribute_taxonomies' );
				}

				register_taxonomy(
					$data['taxonomy'],
					apply_filters( 'woocommerce_taxonomy_objects_' . $data['taxonomy'], array( 'product' ) ),
					apply_filters( 'woocommerce_taxonomy_args_' . $data['taxonomy'], array(
						'hierarchical' => true,
						'show_ui'      => false,
						'query_var'    => true,
						'rewrite'      => false,
					) )
				);
			}
		}

		return $data;
	}
}
