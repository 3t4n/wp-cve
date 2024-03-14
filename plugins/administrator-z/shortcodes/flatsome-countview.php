<?php 

use Adminz\Admin\Adminz as Adminz;
use Adminz\Admin\ADMINZ_DefaultOptions as ADMINZ_DefaultOptions;

if(!isset(ADMINZ_DefaultOptions::$options['adminz_enable_countview']) or !ADMINZ_DefaultOptions::$options['adminz_enable_countview'] == "on") {
    return;
}

add_action('ux_builder_setup', 'ux_adminz_add_viewcount');
function ux_adminz_add_viewcount(){
    $options = [];
    $options[]  = '--Select--';
    foreach (Adminz::get_support_icons() as $icon) {
        $options[str_replace(".svg", "", $icon)] = $icon;
    }
    add_ux_builder_shortcode('adminz_countviews', array(
        'name'      => __('Number Count Views','administrator-z'),
        'category'  => Adminz::get_adminz_menu_title(),
        'thumbnail' =>  get_template_directory_uri() . '/inc/builder/shortcodes/thumbnails/' . 'countdown' . '.svg',
        'options' => array(
            'post_id' => array(
                'type' => 'select',
                'heading' => 'Custom Posts',
                'param_name' => 'ids',
                'config' => array(
                    'multiple' => false,
                    'placeholder' => 'Select..',
                    'postSelect' => array(
                        'post_type' => array()
                    ),
                )
            ),
            'icon'=>array(
                'type' => 'select',                
                'heading'   =>'Use icon',
                'description' => "Tools/ ".Adminz::get_adminz_menu_title()."/ icons",
                'default' => 'eye',
                'options'=> $options
            ),
            'textbefore' => array(
                'type'       => 'textfield',
                'heading'   => __('Text before','administrator-z'),
                'default'    => '',
            ),
            'textafter' => array(
                'type'       => 'textfield',
                'heading'   => __('Text after','administrator-z'),
                'default'    => '',
            ),
            'class' => array(
                'type'       => 'textfield',
                'heading'   => __('Class','administrator-z'),
                'default'    => '',
            ),
        ),
    ));
}