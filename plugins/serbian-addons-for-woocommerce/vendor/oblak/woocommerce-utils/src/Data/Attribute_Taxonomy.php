<?php // phpcs:disable Squiz.Commenting.VariableComment.MissingVar
/**
 * Attribute_Taxonomy class file
 *
 * @package  WooCommerce Utils
 */

namespace Oblak\WooCommerce\Data;

use WC_Data;
use WC_Product_Attribute;

/**
 * Attribute Taxonomy object
 */
class Attribute_Taxonomy extends WC_Data {
    /**
     * {@inheritDoc}
     */
    protected $data = array(
        'attribute_id'      => 0,
        'attribute_name'    => '',
        'attribute_label'   => '',
        'attribute_type'    => 'select',
        'attribute_orderby' => 'menu_order',
        'attribute_public'  => 0,
        'image_id'          => 0,
        'gallery_image_ids' => array(),
        'technical_label'   => '',
    );

    /**
     * {@inheritDoc}
     */
    protected $object_type = 'attribute_taxonomy';

    /**
     * Meta type. This should match up with
     * the types available at https://developer.wordpress.org/reference/functions/add_metadata/.
     * WP defines 'post', 'user', 'comment', and 'term'.
     * We define attribute_taxonomy for our custom meta table
     *
     * @var string
     */
    protected $meta_type = 'attribute_taxonomy';

    /**
     * Constructor.
     *
     * @param int|object|array $data ID to load from the db or data object.
     */
    public function __construct( $data = '' ) {
        parent::__construct( $data );

        if ( $data instanceof Attribute_Taxonomy ) {
            $this->set_id( absint( $data->get_id() ) );
        } elseif ( is_numeric( $data ) ) {
            $this->set_id( absint( $data ) );
        } elseif ( is_object( $data ) && ! empty( $data->attribute_id ) ) {
            $this->set_id( absint( $data->attribute_id ) );
            $this->set_props( (array) $data, 'set' );
            $this->set_object_read( true );
            $this->maybe_read_meta_data();
        } else {
            $this->set_object_read( true );
        }

        $this->data_store = wc_atds();

        if ( $this->get_id() > 0 ) {
            $this->data_store->read( $this );
        }
    }

    /**
     * --------------------------------------------------------------------------
     * Getters
     * --------------------------------------------------------------------------
     */

    /**
     * Get the attribute ID.
     *
     * @return int
     */
    public function get_attribute_id() {
        return $this->get_id();
    }

    /**
     * Get the attribute name.
     *
     * @param string $context What the value is for. Valid values are 'view' and 'edit'.
     * @return string
     */
    public function get_slug( $context = 'view' ) {
        return $this->get_prop( 'attribute_name', $context );
    }

    /**
     * Get the attribute label.
     *
     * @param string $context What the value is for. Valid values are 'view' and 'edit'.
     * @return string
     */
    public function get_name( $context = 'view' ) {
        return $this->get_prop( 'attribute_label', $context );
    }

    /**
     * Get the attribute type.
     *
     * @param string $context What the value is for. Valid values are 'view' and 'edit'.
     * @return string
     */
    public function get_type( $context = 'view' ) {
        return $this->get_prop( 'attribute_type', $context );
    }

    /**
     * Get the attribute orderby.
     *
     * @param string $context What the value is for. Valid values are 'view' and 'edit'.
     * @return string
     */
    public function get_orderby( $context = 'view' ) {
        return $this->get_prop( 'attribute_orderby', $context );
    }

    /**
     * Get the attribute public.
     *
     * @param  string $context What the value is for. Valid values are 'view' and 'edit'.
     * @return bool
     */
    public function get_public( $context = 'view' ) {
        return $this->get_prop( 'attribute_public', $context );
    }

    /**
     * Get the attribute image ID.
     *
     * @param  string $context What the value is for. Valid values are 'view' and 'edit'.
     * @return int
     */
    public function get_image_id( $context = 'view' ) {
        return $this->get_prop( 'image_id', $context );
    }

    /**
     * Get the attribute gallery image IDs.
     *
     * @param  string $context What the value is for. Valid values are 'view' and 'edit'.
     * @return array
     */
    public function get_gallery_image_ids( $context = 'view' ) {
        return $this->get_prop( 'gallery_image_ids', $context );
    }

    /**
     * Get the attribute technical label.
     *
     * @param  string $context What the value is for. Valid values are 'view' and 'edit'.
     * @return string
     */
    public function get_technical_label( $context = 'view' ) {
        return $this->get_prop( 'technical_label', $context );
    }

    /**
     * --------------------------------------------------------------------------
     * Setters
     * --------------------------------------------------------------------------
     */

    /**
     * Set the attribute ID.
     *
     * @param int $id Attribute ID.
     */
    public function set_attribute_id( $id ) {
        $this->set_id( $id );
    }

    /**
     * Set the attribute name.
     *
     * @param string $name Attribute name.
     */
    public function set_slug( $name ) {
        $this->set_prop( 'attribute_name', $this->data_store->sanitize_attribute_slug( $name ) );
    }

    /**
     * Set the attribute label.
     *
     * @param string $label Attribute label.
     */
    public function set_name( $label ) {
        $this->set_prop( 'attribute_label', $label );
    }

    /**
     * Set the attribute type.
     *
     * @param string $type Attribute type.
     */
    public function set_type( $type ) {
        $this->set_prop( 'attribute_type', $type );
    }

    /**
     * Set the attribute orderby.
     *
     * @param string $orderby Attribute orderby.
     */
    public function set_orderby( $orderby ) {
        $this->set_prop( 'attribute_orderby', $orderby );
    }

    /**
     * Set the attribute public.
     *
     * @param bool $is_public Attribute public.
     */
    public function set_public( $is_public ) {
        $this->set_prop( 'attribute_public', (int) wc_string_to_bool( $is_public ) );
    }

    /**
     * Set the attribute image ID.
     *
     * @param int $image_id Attribute image ID.
     */
    public function set_image_id( $image_id ) {
        $this->set_prop( 'image_id', $image_id );
    }

    /**
     * Set the attribute gallery image IDs.
     *
     * @param array $gallery_image_ids Attribute gallery image IDs.
     */
    public function set_gallery_image_ids( $gallery_image_ids ) {
        $this->set_prop( 'gallery_image_ids', $gallery_image_ids );
    }

    /**
     * Set the attribute technical label.
     *
     * @param string $technical_label Attribute technical label.
     */
    public function set_technical_label( $technical_label ) {
        $this->set_prop( 'technical_label', $technical_label );
    }

    /**
     * --------------------------------------------------------------------------
     * Custom functionalities
     * --------------------------------------------------------------------------
     */

    /**
     * Get the attribute taxonomy name.
     *
     * @return string
     */
    public function get_taxonomy_name() {
        return wc_attribute_taxonomy_name( $this->get_slug() );
    }

    /**
     * Get the terms for this Attribute Taxonomy.
     *
     * @param  string|string[] $term_names Array of term names to get, or a single term name.
     * @param  bool            $create     Whether to create the terms if they don't exist.
     * @param  string          $fields     What to return. Valid values are `ids`, `slugs`, `objects`.
     */
    public function get_terms( $term_names = array(), $create = true, $fields = 'ids' ) {
        $terms    = array();
        $taxonomy = $this->get_taxonomy_name();
        $order    = 1;

        if ( empty( $term_names ) ) {
            return get_terms(
                array(
                    'taxonomy'   => $taxonomy,
                    'fields'     => $fields,
                    'hide_empty' => false,
                )
            );
        }

        if ( ! is_array( $term_names ) ) {
            $term_names = array( $term_names );
        }

        foreach ( $term_names as $term_name ) {
            $term_check = get_term_by( 'name', $term_name, $taxonomy );

            if ( $term_check || ! $create ) {
                $terms[] = $term_check;
                continue;
            }

            $term = wp_insert_term( $term_name, $taxonomy );
            update_term_meta( $term['term_id'], 'order', $order++ );

            $terms[] = get_term( $term['term_id'], $taxonomy );
		}

        $terms = array_filter( $terms );

        switch ( $fields ) {
            case 'ids':
                return wp_list_pluck( $terms, 'term_id' );
            case 'slugs':
                return wp_list_pluck( $terms, 'slug' );
            case 'objects':
                return $terms;
            default:
                return wp_list_pluck( $terms, 'term_id' );
        }
    }

    /**
     * Creates a standard WooCommerce Attribute object for the given terms
     *
     * @param  string|string[] $term_names    Array of term names to get, or a single term name.
     * @param  string          $product_type  Product type.
     * @param  bool            $for_variation Whether the attribute is for variation.
     * @param  int             $position      Position of the attribute.
     * @return WC_Product_Attribute           Formated attribute array.
     */
    public function create_object( $term_names, $product_type, $for_variation, $position ) {
        if ( 'variation' === $product_type ) {
            return $this->get_terms( $term_names, true, 'slugs' )[0];
        }

        $att = new WC_Product_Attribute();
        $att->set_id( $this->get_id() );
        $att->set_name( $this->get_taxonomy_name() );
        $att->set_options( $this->get_terms( $term_names, true, 'ids' ) );
        $att->set_position( $position );
        $att->set_visible( true );
        $att->set_variation( $for_variation );

        return $att;
    }
}
