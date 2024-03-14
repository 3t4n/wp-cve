<?php

require_once FNSF_AF2_MENU_PARENTS_CLASS;
class Fnsf_Af2Kalkulation extends Fnsf_Af2MenuTable {

    protected function fnsf_get_heading() { return __('Calculations', 'funnelforms-free'); }

    protected function fnsf_get_menu_blur_option_() { return true; }

    protected function fnsf_get_post_type_constant() { return null; }

    protected function fnsf_get_table_columns() {
        return array(
            array( 'lable' => 'ID', 'translate' => false, 'highlight' => true,                  'width' => '110px', 'flex' => '',  'max-width' => '', 'min-width' => '', 'button' => false, 'uid' => true, 'url' => true),
            array( 'lable' => 'Name', 'translate' => true, 'highlight' => false,                'width' => '',      'flex' => '1', 'max-width' => '', 'min-width' => '', 'button' => false, 'uid' => false, 'url' => true),
            array( 'lable' => 'Author', 'translate' => false, 'highlight' => false,      'width' => '',      'flex' => '0.5',  'min-width' => '', 'max-width'=> '', 'button' => false, 'uid' => false),
        ); 
    }
    protected function fnsf_edit_posts_for_table($posts) {
        return array();
    }

}