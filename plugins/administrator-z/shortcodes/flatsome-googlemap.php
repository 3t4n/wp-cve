<?php 

use Adminz\Admin\Adminz as Adminz;
add_action('ux_builder_setup', 'adminz_googlemap');
add_shortcode('adminz_googlemap', 'adminz_googlemap_function');
function adminz_googlemap(){
    add_ux_builder_shortcode('adminz_googlemap', array(
        'name'      => __('Google map Iframe','administrator-z'),
        'category'  => Adminz::get_adminz_menu_title(),
        'thumbnail' =>  get_template_directory_uri() . '/inc/builder/shortcodes/thumbnails/' . 'map' . '.svg',
        'options' => array(
            'address' => array(
                'type'       => 'textfield',
                'heading'    => 'Address or latlong',
                'default' => '21.028232792016798, 105.83566338846242',
            ),
            'height' => array(
                'type'       => 'textfield',
                'heading'    => 'Height',
                'default' => '300px',
            ),
            'hl' => array(
                'type'       => 'textfield',
                'heading'    => 'Language',
                'default' => 'vn',
            ),
        ),
    ));
}
function adminz_googlemap_function($atts){  
    extract(shortcode_atts(array(
        'address'=> '21.028232792016798, 105.83566338846242',
        'height'=>'300px',
        'hl'=>"vn"
    ), $atts));
    ob_start(); 
    ?>
    <iframe 
        style="
            margin-bottom: -7px;
            border: none; 
            height: <?php echo esc_attr($height) ?>; 
            width: 100%" 
        src = "https://maps.google.com/maps?q=<?php echo esc_attr($address); ?>&hl=<?php echo esc_attr($hl) ?>;z=14&amp;output=embed">
    </iframe>
    <?php
      
    return ob_get_clean();
}