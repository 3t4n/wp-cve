<?php
/**
 * Generate custom taxonomy on request
 */

namespace Reuse\Builder;

use Doctrine\Common\Inflector\Inflector;

class Reuse_Generate_Taxonomy {

    public function __construct( $taxonomies ) {
        $this->generate_custom_taxonomy( $taxonomies );
    }


    /**
     * Generate Custom taxonomy
     *
     * @param array $taxonomies
     *
     * @return void
     *
     */

    public function generate_custom_taxonomy( $taxonomies ) {
        $post_type_supports = array();

        if( ! empty( $taxonomies ) ) {
            foreach ( $taxonomies as $taxonomy ) {

                $plural_name = Inflector::pluralize( $taxonomy['showName'] );
                $singular_name = Inflector::singularize( $taxonomy['showName'] );

                $labels = array(
                    'name'              => _x( $plural_name, 'taxonomy general name' ),
                    'singular_name'     => _x( $singular_name, 'taxonomy singular name' ),
                    'search_items'      => __( 'Search ' . $plural_name ),
                    'all_items'         => __( 'All ' . $plural_name ),
                    'parent_item'       => __( 'Parent ' . $singular_name ),
                    'parent_item_colon' => __( 'Parent ' . $singular_name . ':' ),
                    'edit_item'         => __( 'Edit ' . $singular_name ),
                    'update_item'       => __( 'Update ' . $singular_name ),
                    'add_new_item'      => __( 'Add New ' . $singular_name ),
                    'new_item_name'     => __( 'New ' . $singular_name . 'Name' ),
                    'menu_name'         => __( $singular_name ),
                );

                $args = array(
                    'hierarchical'      => $taxonomy['hierarchy'],
                    'labels'            => $labels,
                    'show_ui'           => true,
                    'show_in_rest'      => $taxonomy['show_in_rest'],
                    'show_admin_column' => true,
                    'query_var'         => true,
                    'rewrite'           => array( 'slug' => $taxonomy['name'] ),
								);
                if($taxonomy['postType'] == 'attachment'){
                  $args['update_count_callback'] = '_update_generic_term_count';
                }

                register_taxonomy( $taxonomy['name'], array( $taxonomy['postType'] ), $args );

            }
        }
    }
}
