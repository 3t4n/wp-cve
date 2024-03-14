<?php
/**
 * Composer.
 *
 * @package Stax
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since 1.0
 */

namespace Stax;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use Stax\Underscore as _;

class Composer extends Base_Model {

    /**
     * @var
     */
    private $element;

    /**
     * @var null
     */
    public static $instance = null;

    /**
     * @return null|Composer
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param \WP_REST_Request $request
     *
     * @return mixed
     */
    public function buildTemplate( \WP_REST_Request $request ) {
        $r = $request->get_params();

        $viewport      = $r['viewport'];
        $editorElement = json_decode( $r['element'] );

        $originalElementClass = '\Stax\Element' . ucfirst( $editorElement->slug );
        $this->element        = new $originalElementClass;

        $this->element->parent        = ( isset( $editorElement->parent ) ) ? $editorElement->parent : '';
        $this->element->uuid          = $editorElement->uuid;
        $this->element->auth          = $editorElement->auth;
        $this->element->editor        = $editorElement->editor;
        $this->element->desktop       = $editorElement->desktop;
        $this->element->tablet        = $editorElement->tablet;
        $this->element->mobile        = $editorElement->mobile;
        $this->element->customDesktop = $editorElement->customDesktop;
        $this->element->customTablet  = $editorElement->customTablet;
        $this->element->customMobile  = $editorElement->customMobile;

        $data                  = [];
        $data['uuid']          = $this->element->uuid;
        $data['container_for'] = ( $this->element->parent ) ? $this->element->parent : '';

        $duplicateElement = json_decode( $r['element'] );
        $editor           = $duplicateElement->editor;

        if ( $viewport !== 'desktop' ) {
            $viewportData = $duplicateElement->{$viewport};
            foreach ( $editor as $item ) {
                foreach ( $item->fields as $x => $key ) {
                    foreach ( $viewportData as $viewportItem ) {
                        if ( $key->name !== $viewportItem->name ) {
                            continue;
                        }

                        $item->fields[ $x ]->value = $viewportItem->value;
                        $item->fields[ $x ]->units = $viewportItem->units;
                    }
                }
            }
        }

        foreach ( $editor as $key => $item ) {
            if ( ! $item->visibility ) {
                continue;
            }

            foreach ( $item->fields as $field ) {
                if ( $field->visibility ) {
                    if ( is_array( $field->value ) ) {
                        foreach ( $field->value as $value ) {
                            if ( $field->type === 'dropdown' || $field->type === 'buttongroup' ) {
                                if ( $value->selected ) {
                                    $data[ $field->name ] = $value->value;
                                }
                            } else if ( $field->type === 'radio' || $field->type === 'checkbox' || $field->type === 'switcher' ) {
                                if ( $value->checked ) {
                                    $data[ $field->name ] = $value->value;
                                } else {
                                    $data[ $field->name ] = '';
                                }
                            } else if ( $field->type === 'verticaltabs' ) {
                                $data[ $field->name ]           = [];
                                $data[ $field->name ]['name']   = $value->name;
                                $data[ $field->name ]['value']  = $value->value;
                                $data[ $field->name ]['active'] = $value->active;
                            } else if ( $field->type === 'advancedtabs' ) {
                                $data[ $field->name ]           = [];
                                $data[ $field->name ]['name']   = $value->name;
                                $data[ $field->name ]['active'] = $value->active;
                            } else if ( $field->type === 'switcher' ) {
                                $data[ $field->name ] = $value->checked;
                            }
                        }
                    } else {
                        $data[ $field->name ] = $field->value;
                    }
                }
            }
        }

        $data['stax_adv_classes'] = [];
        $data['stax_adv_id']      = [];

        if ( isset( $data['full_height_advanced'] ) && $data['full_height_advanced'] ) {
            $data['stax_adv_classes'][] = $data['full_height_advanced'];
        }

        if ( isset( $data['full_width_advanced'] ) && $data['full_width_advanced'] ) {
            $data['stax_adv_classes'][] = $data['full_width_advanced'];
        }

        if ( isset( $data['css_class_advanced'] ) && $data['css_class_advanced'] ) {
            $data['stax_adv_classes'][] = $data['css_class_advanced'];
        }

        if ( isset( $data['css_id_advanced'] ) && $data['css_id_advanced'] ) {
            $data['stax_adv_id'][] = $data['css_id_advanced'];
        }

        $data['stax_adv_classes'] = implode( " ", $data['stax_adv_classes'] );
        $data['stax_adv_id']      = implode( " ", $data['stax_adv_id'] );

        $this->element->template = $this->convertToPhpTemplate( $this->element->template );
        $this->element->template = do_shortcode( _::template( $this->element->template, $data ) );

        return $this->response( self::STATUS_OK, [ $this->element ] );
    }

    private function convertToPhpTemplate( $string = '' ) {
        $string = str_replace( 'data.', '$', $string );

        return $string;
    }
}
