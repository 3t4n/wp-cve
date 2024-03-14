<?php
namespace Enteraddons\Classes;

/**
 * Enteraddons Post Type Meta Base class
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

abstract class Meta_Base {
    use \Enteraddons\Core\Fields_Flag;

    public function initHook() {
        add_action( 'add_meta_boxes', [ $this, '_registerMeta' ] );

        if( !empty( $this->getPostTypeName() ) ) {
            foreach( $this->getPostTypeName() as $postType ) {
                add_action( 'save_post_'.$postType, [ $this, 'save_meta_postdata' ] );
            }
            
        }
        
    }

    abstract public function getPostTypeName();

    public function _registerMeta() {
        $args = $this->metaBox;

        add_meta_box(
            $args['unique_id'], // Unique ID
            $args['title'], // Box title
            [$this, 'meta_callback'], // Content callback, must be of type callable
            [ $this->getPostTypeName() ], // Post type
            $args['context'], // context 
            $args['priority'] // priority
        );
    }

    public function meta_callback() {
        $this->meta_view();
    }

    public function save_meta_postdata( $post_id ) {

        $fields = $this->metaFields;

        if( !empty( $fields ) && is_array( $fields ) ) {

            foreach( $fields as $val ) {
                $key = $val['name'];
                $getVal = '';
                if( !empty( $_POST[$key] ) ) {
                    $getVal = $_POST[$key];
                }
                update_post_meta( absint( $post_id ), $key, $getVal );
            }

        }

    }


}