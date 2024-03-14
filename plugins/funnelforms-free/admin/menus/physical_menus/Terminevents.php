<?php

require_once FNSF_AF2_MENU_PARENTS_CLASS;
class Fnsf_Af2Terminevent extends Fnsf_Af2MenuTable {

    protected function fnsf_get_heading() { return __('Appointment events', 'funnelforms-free'); }

    protected function fnsf_get_menu_blur_option_() { return true; }

    protected function fnsf_get_post_type_constant() { return null; }

    protected function fnsf_get_table_columns() {
        return array(
            array( 'lable' => 'ID', 'translate' => false, 'highlight' => false,                         'width' => '110px', 'flex' => '',  'max-width' => '', 'min-width' => '', 'button' => false, 'uid' => true),
            array( 'lable' => 'Title', 'translate' => false, 'highlight' => false,                      'width' => '',      'flex' => '1', 'max-width' => '', 'min-width' => '', 'button' => false, 'url' => true, 'uid' => false),
            array( 'lable' => 'Date', 'translate' => false, 'highlight' => false,                      'width' => '', 'flex' => '0.4',  'max-width' => '', 'min-width' => '', 'button' => false, 'uid' => false),
            array( 'lable' => 'Time', 'translate' => false, 'highlight' => false,                       'width' => '', 'flex' => '0.4',  'max-width' => '', 'min-width' => '', 'button' => false, 'uid' => false),
            array( 'lable' => 'Duration', 'translate' => false, 'highlight' => false,                      'width' => '', 'flex' => '0.4',  'max-width' => '', 'min-width' => '', 'button' => false, 'uid' => false),
            array( 'lable' => __('Details' , 'funnelforms-free' ), 'translate' => true, 'highlight' => false,                     'width' => '160px', 'flex' => '',  'max-width' => '', 'min-width' => '', 'url' => true, 'button' => true, 'buttonclass' => 'primary', 'uid' => false),
            array( 'lable' => 'lead_id', 'hidden' => true, 'translate' => false, 'highlight' => false,  'width' => '160px', 'flex' => '',  'max-width' => '', 'min-width' => '', 'button' => false, 'uid' => false),
        ); 
    }
    protected function fnsf_edit_posts_for_table($posts) {
        return array();
    }

}