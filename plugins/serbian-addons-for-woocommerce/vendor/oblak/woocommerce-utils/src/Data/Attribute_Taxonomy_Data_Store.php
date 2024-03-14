<?php // phpcs:disable Squiz.Commenting.VariableComment.MissingVar
/**
 * Attribute_Data_Store class file
 *
 * @package  WooCommerce Utils
 */

namespace Oblak\WooCommerce\Data;

use Exception;
use Oblak\WooCommerce\Data\Extended_Data_Store;
use Throwable;
use WC_Product_Attribute;

/**
 * Attribute data store for searching and getting data from the database.
 */
class Attribute_Taxonomy_Data_Store extends Extended_Data_Store {
    /**
     * {@inheritDoc}
     */
    protected $meta_type = 'attribute_taxonomy';

    /**
     * {@inheritDoc}
     *
     * @var string
     */
    protected $object_id_field = 'attribute_id';

    /**
     * {@inheritDoc}
     */
    protected $object_id_field_for_meta = 'attribute_taxonomy_id';

    /**
     * {@inheritDoc}
     */
    protected $internal_meta_keys = array(
        '_image_id',
        '_gallery_image_ids',
        '_technical_label',
    );

    /**
     * {@inheritDoc}
     */
    protected $must_exist_meta_keys = array(
        '_image_id',
        '_gallery_image_ids',
        '_technical_label',
    );

    /**
     * {@inheritDoc}
     */
    protected $meta_key_to_props = array(
        '_image_id'          => 'image_id',
        '_gallery_image_ids' => 'gallery_image_ids',
        '_technical_label'   => 'technical_label',
    );

    /**
     * {@inheritDoc}
     */
    protected function get_table() {
        global $wpdb;
        return $wpdb->prefix . 'woocommerce_attribute_taxonomies';
    }

    /**
     * {@inheritDoc}
     */
    protected function get_entity_name() {
        return 'attribute_taxonomy';
    }

    /**
     * {@inheritDoc}
     */
    protected function get_searchable_columns() {
        return array(
            'attribute_name',
            'attribute_label',
            'attribute_type',
            'attribute_orderby',
            'attribute_public',
        );
    }

    /**
     * Get global attributes.
     *
     * Global attributes are not used for variations.
     *
     * @return string[]
     */
    public function get_global_attributes() {
        $global_atts = array();

        /**
         * Filters the global attribute taxonomy names.
         *
         * @since 3.0.0
         *
         * @param  string[]                      $global_atts Global attribute taxonomy names.
         * @param  Attribute_Taxonomy_Data_Store $this        Data store.
         *
         * @return string[]
         */
        return apply_filters( 'woocommerce_global_attributes', $global_atts, $this );
    }

    /**
     * Create an Attribute Taxonomy in the database.
     *
     * @param Attribute_Taxonomy $data Data to create.
     *
     * @throws Exception If cannot create attribute taxonomy.
     */
    public function create( &$data ) {
        $id = wc_create_attribute(
            array(
				'id'           => $data->get_id(),
				'name'         => $data->get_name(),
                'slug'         => $data->get_slug(),
                'type'         => $data->get_type(),
                'order_by'     => $data->get_orderby(),
                'has_archives' => $data->get_public(),
            )
        );

        if ( ! $id || is_wp_error( $id ) ) {
            throw new Exception( esc_html( $id->get_error_message() ) );

        }
            $data->set_id( $id );
            $this->update_entity_meta( $data );
            $data->save_meta_data();
            $data->apply_changes();

            $this->create_taxonomy( $data );
    }

    /**
     * Create a taxonomy for the attribute.
     *
     * @param  Attribute_Taxonomy $data Data to create.
     */
    public function create_taxonomy( &$data ) {
        if ( taxonomy_exists( $data->get_taxonomy_name() ) ) {
            return;
        }

        $taxonomy_name = wc_attribute_taxonomy_name( $data->get_slug() );

        register_taxonomy(
			$taxonomy_name,
            // Documented in woocommerce.
			apply_filters( 'woocommerce_taxonomy_objects_' . $taxonomy_name, array( 'product' ) ),
            // Documented in woocommerce.
			apply_filters(
				'woocommerce_taxonomy_args_' . $taxonomy_name,
				array(
					'labels'       => array(
						'name' => $data->get_name(),
					),
					'hierarchical' => true,
					'show_ui'      => false,
					'query_var'    => true,
					'rewrite'      => false,
				)
			)
		);
    }

    /**
     * Read an Attribute Taxonomy from the database.
     *
     * @param Attribute_Taxonomy $data Data to read.
     *
     * @throws Exception If invalid attribute taxonomy.
     */
    public function read( &$data ) {
        $data->set_defaults();

        $att_tax = wc_get_attribute( $data->get_id() );
        $att_id  = $data->get_id();

        if ( is_null( $att_tax ) || 0 === ! $att_id ) {
            throw new Exception( 'Invalid attribute taxonomy.' );
        }

        $data->set_props(
            array(
				'name'    => $att_tax->name,
				'slug'    => str_replace( 'pa_', '', $att_tax->slug ),
				'type'    => $att_tax->type,
				'orderby' => $att_tax->order_by,
				'public'  => $att_tax->has_archives,
            )
        );

        $this->read_entity_data( $data );
        $data->set_object_read( true );
    }

    /**
     * Update an Attribute Taxonomy in the database.
     *
     * @param Attribute_Taxonomy $data Data to update.
     */
    public function update( &$data ) {
        $data->save_meta_data();
        wc_update_attribute(
            $data->get_id(),
            array(
				'id'           => $data->get_id(),
				'name'         => $data->get_name(),
				'slug'         => $data->get_slug(),
				'type'         => $data->get_type(),
				'order_by'     => $data->get_orderby(),
				'has_archives' => $data->get_public(),
            )
        );

        $this->update_entity_meta( $data );
        $data->apply_changes();
    }

    /**
     * Delete an Attribute Taxonomy from the database.
     *
     * @param  Attribute_Taxonomy $data Data to delete.
     * @param  array              $args Not used.
     * @return bool
     */
    public function delete( &$data, $args = array() ) {
        if ( 0 === $data->get_id() ) {
            return false;
        }
        return wc_delete_attribute( $data->get_id() );
    }

    /**
     * Sanitizes the attribute slug.
     *
     * @param  string $slug Attribute name or slug.
     * @return string       Sanitized slug.
     */
    public function sanitize_attribute_slug( $slug ) {
        $slug = mb_substr( wc_sanitize_taxonomy_name( $slug ), 0, 28 );
        $slug = preg_replace( '/[^a-zA-Z0-9\-_]/', '', $slug );

        return $slug;
    }

    /**
     * Get the attribute Taxonomy by name or slug
     *
     * Providing the create argument will create the attribute if it doesn't exist.
     *
     * @param  string               $attribute_name Attribute name.
     * @param  Array<string, mixed> $args           Arguments.
     * @return Attribute_Taxonomy|null              Attribute taxonomy, or null if not found.
     */
    public function get_by_name_or_slug( $attribute_name, $args = array() ) {
        if ( str_starts_with( $attribute_name, 'pa_' ) ) {
            $attribute_name = str_replace( 'pa_', '', $attribute_name );
        }

        $args = wp_parse_args(
            $args,
            array(
				'create'    => false,
				'overwrite' => false,
            )
        );
        $att  = $this->get_entities(
            array(
				'attribute_label' => $attribute_name,
                'attribute_name'  => $this->sanitize_attribute_slug( $attribute_name ),
				'per_page'        => 1,
            ),
            'OR'
        );

        return $this->get_or_create( $att, $args, $attribute_name );
    }

    /**
     * Get the attribute Taxonomy by name
     *
     * Providing the create argument will create the attribute if it doesn't exist.
     *
     * @param  string               $attribute_name Attribute name.
     * @param  Array<string, mixed> $args           Arguments.
     * @return Attribute_Taxonomy|null              Attribute taxonomy, or null if not found.
     */
    public function get_by_name( $attribute_name, $args = array() ) {
        $args = wp_parse_args(
            $args,
            array(
				'create'    => false,
				'overwrite' => false,
            )
        );
        $att  = $this->get_entities(
            array(
				'attribute_label' => $attribute_name,
				'per_page'        => 1,
            )
        );

        return $this->get_or_create( $att, $args, $attribute_name );
    }

    /**
     * Get the attribute Taxonomy by name
     *
     * Providing the create argument will create the attribute if it doesn't exist.
     *
     * @param  Attribute_Taxonomy|null $att            Attribute taxonomy.
     * @param  Array<string, mixed>    $args           Arguments.
     * @param  string                  $attribute_name Attribute name.
     * @return Attribute_Taxonomy|null                 Attribute taxonomy, or null if not found.
     */
    public function get_or_create( $att, $args, $attribute_name ) {
        if ( ! empty( $att ) && ! $args['overwrite'] ) {
            return new Attribute_Taxonomy( $att );
        }

        if ( ! $args['create'] ) {
            return null;
        }

        /**
         * Filters the arguments used to create an attribute taxonomy.
         *
         * @since 3.0.0
         *
         * @param  Array<string, mixed>          $args           Arguments.
         * @param  string                        $attribute_name Attribute name.
         * @param  Attribute_Taxonomy_Data_Store $this           Data store.
         * @return Array<string, mixed>                          Arguments.
         */
        $args = apply_filters( 'woocommerce_attribute_taxonomy_create_args', $args, $attribute_name, $this );

        $att = new Attribute_Taxonomy( $att );
        $att->set_name( $attribute_name );
        $att->set_slug( $args['slug'] ?? $attribute_name );
        $att->set_type( $args['type'] ?? 'select' );
        $att->set_orderby( $args['orderby'] ?? 'menu_order' );
        $att->set_public( $args['public'] ?? false );
        $att->save();

        return $att;
    }

    /**
     * Creates the formated attribute array to be used as a product prop.
     *
     * @param  Array<string, string|string[]> $raw_atts       Raw attribute array.
     * @param  string                         $product_type   Product type.
     * @param  string[]                       $variation_atts Attributes which are used for variation.
     * @param  int                            $pos            Position of the attribute.
     * @param  bool                           $overwrite      Whether to overwrite existing attributes.
     * @return Array<string,WC_Product_Attribute>             Formated attribute array.
     *
     * @throws Exception If cannot create taxonomy for attribute.
     */
    public function create_attributes_for_product( $raw_atts, $product_type = 'simple', $variation_atts = array(), $pos = 0, $overwrite = false ) {
        $return_arr = array();

        foreach ( $raw_atts as $att_name => $att_values ) {
            try {
				$attribute     = $this->get_by_name_or_slug(
                    $att_name,
                    array(
						'create'    => true,
						'overwrite' => $overwrite,
                    )
				);
				$for_variation = in_array( $att_name, $variation_atts, true ) && ! in_array( $att_name, $this->get_global_attributes(), true );

				if ( 0 === $attribute->get_id() ) {
					continue;
				}

				$return_arr[ $attribute->get_taxonomy_name() ] = $attribute->create_object( $att_values, $product_type, $for_variation, $pos );
				++$pos;
            } catch ( Throwable $e ) {
                throw new Exception(
                    sprintf(
                        'Error creating taxonomy for attribute: %1$s with values: %2$s - %3$s',
                        esc_html( $att_name ),
                        esc_html( implode( ', ', $att_values ) ),
                        esc_html( $e->getMessage() )
                    )
                );
            }
        }

        return $return_arr;
    }
}
