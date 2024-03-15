<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
require_once 'class-sf-resource.php';

class SF_Attribute extends SF_Resource
{
    public function __construct()
    {
    }

    /**
     * Get all the attributes in the system
     *
     */
    public function get_attributes() {

        //get system attricutes
        $wc_product_attributes = array();

        if ( $attribute_taxonomies = wc_get_attribute_taxonomies() ) {
            foreach ( $attribute_taxonomies as $tax ) {
                if ( $name = wc_attribute_taxonomy_name( $tax->attribute_name ) ) {
                    $wc_product_attributes[ $name ] = $tax->attribute_name;
                }
            }
        }

        //get all product attributes
        global $wpdb;

        $product_attribute_rows = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "postmeta WHERE meta_key = %s", '_product_attributes' ) );

        $product_attributes = array();
        if ( $product_attribute_rows && !empty( $product_attribute_rows ) )
        {
            foreach ( $product_attribute_rows as $product_attribute_row )
            {
                $unserialized = unserialize( $product_attribute_row->meta_value );
                foreach ( $unserialized as $attribute_code => $attribute )
                {
                    $product_attributes[ $attribute_code ] = $attribute[ 'name' ];
                }
            }

        }

	    //Support for Advanced Custom Fields
	    $acf_attributes = array();
	    if (function_exists( "acf_get_field_groups" ) )
	    {
	    	$acf_groups = acf_get_field_groups();

		    if ( !empty( $acf_groups) )
		    {
		    	foreach ( $acf_groups as $acf_group )
			    {
				    $acf_meta_fields = acf_get_fields( $acf_group );
				    if ( !empty($acf_meta_fields) )
				    {
					    foreach ( $acf_meta_fields as $acf_meta_field )
					    {
						    $acf_attributes[$acf_group['key'] . '::' . $acf_meta_field['key']] = 'ACF::' . $acf_meta_field['name'];
					    }
				    }
			    }
		    }
	    }

        $shipping_classes = get_terms( array('taxonomy' => 'product_shipping_class', 'hide_empty' => false ) );

	    if(!is_null($shipping_classes) && !empty($shipping_classes)) {
            $product_attributes['shipping_class'] = 'shipping_class';
        }

        return array_merge( $wc_product_attributes, $product_attributes, $acf_attributes );
    }
}