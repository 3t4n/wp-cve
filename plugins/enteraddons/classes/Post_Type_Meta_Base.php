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

class Post_Type_Meta_Base extends Meta_Base {

    protected $metaBox;
    protected $metaFields;

    public function getPostTypeName() {
        $metaBox = $this->metaBox;
        return !empty( $metaBox['type'] ) ? $metaBox['type'] : '';
    }

    public function add_metabox( $args ) {
        $this->metaBox = $args;
        $this->initHook();
    }
    public function add_field( $args ) {
        $this->metaFields[] = $args;
    }

    protected function meta_view() {

        if( !empty( $this->metaFields ) ) {
            foreach( $this->metaFields as $metaField ) {
                        
                switch( $metaField['type'] ) {
                    case 'text':
                        $this->text_meta( $metaField );
                        break;
                    case 'number':
                        $this->selectbox_meta( $metaField );
                        break;
                    case 'select':
                        $this->selectbox_meta( $metaField );
                        break;
                    case 'checkbox':
                        $this->checkbox_meta( $metaField );
                        break;
                    case 'media':
                        $this->media_meta( $metaField );
                        break;
                    case 'multiple_select':
                        $this->multiple_select_meta( $metaField );
                        break;
                    case 'color':
                        $this->colorpicker_meta( $metaField );
                        break;
                    case 'textarea':
                        $this->textarea_meta( $metaField );
                        break;
                    case 'heading':
                        $this->heading_meta( $metaField );
                        break;
                }
            
            }
        }

    }    

}