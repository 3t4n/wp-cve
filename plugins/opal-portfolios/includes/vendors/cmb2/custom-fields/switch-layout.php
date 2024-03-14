<?php

if (!defined( 'ABSPATH' )){
    exit; // Exit if accessed directly
}

class OSF_CMB2_Field_Switch_Layout {

    /**
     * Current version number
     */
    const VERSION = '1.0.0';

    /**
     * Initialize the plugin by hooking into CMB2
     */
    public function __construct() {
        add_filter( 'cmb2_render_opal_switch_layout', array( $this, 'render' ), 10, 5 );
        //        add_filter( 'cmb2_sanitize_opal_switch', 'cmb2_sanitize_text_email_callback', 10, 2 );
    }

    /**
     * Render field
     */
    public function render($field, $field_escaped_value, $field_object_id, $field_object_type, $field_type_object) {
        $path_imgae = OCBEE_CORE_PLUGIN_URL . 'assets/images/customize/';
        echo $field_type_object->input( array( 'type' => 'hidden' ) );
        echo <<<HTML
<ul class="select-list-image" otf-page-layout>
    <li class="{$this->check_active( '2cl', $field_escaped_value )}">
        <div class="box">
            <img src="{$path_imgae}2cl.png" alt="2cl">
        </div>
    </li>
    <li class="{$this->check_active( '1c', $field_escaped_value )}">
        <div class="box">
            <img src="{$path_imgae}1col.png" alt="1c">
        </div>
    </li>
    <li class="{$this->check_active( '2cr', $field_escaped_value )}">
        <div class="box">
            <img src="{$path_imgae}2cr.png" alt="2cr">
        </div>
    </li>
</ul>
HTML;

    }

    private function check_active($v1, $v2) {
        if ($v1 === $v2){
            return 'active';
        } else{
            return '';
        }
    }


}

new OSF_CMB2_Field_Switch_Layout();
