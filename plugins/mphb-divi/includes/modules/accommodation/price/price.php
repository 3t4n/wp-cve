<?php

use MPHB\Views\SingleRoomTypeView;

class MPHB_Divi_Accommodation_Type_Price_Module extends MPHB_Divi_Abstract_Accommodation_Module {

    public $slug = 'mphb-divi-accommodation-type-price';

    public function init() {
        $this->name = esc_html__( 'HB Acc. Type Price', 'mphb-divi' );
    }

    public static function get_html($attrs = array()) {

        $current_room_type = MPHB()->getCurrentRoomType();

        $id = isset( $attrs['accommodation_id'] ) && $attrs['accommodation_id'] !== 'current' ? (int) $attrs['accommodation_id'] : self::get_current_post_id();

        if ( 'mphb_room_type' != get_post_type( $id ) ) {
            return '';
        }

        MPHB()->setCurrentRoomType( $id );

        $price_HTML = '';
        ob_start();

        SingleRoomTypeView::renderDefaultOrForDatesPrice();

        $price_HTML = ob_get_clean();

        MPHB()->setCurrentRoomType($current_room_type ? $current_room_type->getId() : self::get_current_post_id());

        return $price_HTML;
    }

}

new MPHB_Divi_Accommodation_Type_Price_Module();

