<?php
namespace Enteraddons\Classes;

/**
 * Enteraddons Post Type Base class
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

abstract class Post_Type_Base {

    abstract public function getPostTypeName();

    public function getArgs() {
        return [];
    }

    public function getLabels() {
        return [];
    }
    public function addTaxonomy() {
        return [];
    }
    public function regPostType() {

        $labels = $this->getLabels();

        $default = array(
            'labels'              => $labels,
            'public'              => true,
            'rewrite'             => false,
            'show_ui'             => true,
            'show_in_menu'        => false,
            'show_in_nav_menus'   => false,
            'exclude_from_search' => true,
            'capability_type'     => 'post',
            'hierarchical'        => false,
            'supports'            => array( 'title', 'editor' ),
        );

        $args = wp_parse_args( $this->getArgs(), $default );

        register_post_type( $this->getPostTypeName(), $args );
        
        // Remove post type support
        if( !empty( $this->getremoveSupport() ) ) {
            foreach( $this->getremoveSupport() as $name ) {
                if( !empty( $name ) ) {
                    $this->removePostTypeSupport($name);
                }
            }
        }
        
    }

    public function removePostTypeSupport( $name ) {
        remove_post_type_support( $this->getPostTypeName(), $name );
    }

    public function registeTaxonomy() {

        if( $this->addTaxonomy() ) {
            foreach( $this->addTaxonomy() as $taxonomyinfo  ) {
                if( !empty( $taxonomyinfo['taxonomy_name'] ) ) {
                    register_taxonomy( $taxonomyinfo['taxonomy_name'], $this->getPostTypeName(), $taxonomyinfo['args'] );
                }
            }
        }
    }

    function __construct() {
        $this->regPostType();
        $this->registeTaxonomy();
    }

}