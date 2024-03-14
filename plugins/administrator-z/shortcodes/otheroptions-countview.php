<?php 
use Adminz\Admin\Adminz as Adminz;
use Adminz\Admin\ADMINZ_DefaultOptions as ADMINZ_DefaultOptions;


add_action('wp_footer','adminz_add_viewcount');
add_shortcode('adminz_countviews', 'adminz_countview_function');

function adminz_add_viewcount(){
    if(!isset(ADMINZ_DefaultOptions::$options['adminz_enable_countview']) or !ADMINZ_DefaultOptions::$options['adminz_enable_countview'] == "on") {
        return;
    }
    $key = 'adminz_countview';
    $post_id = get_the_ID();
    $count = (int) get_post_meta( $post_id, $key, true );
    $count++;
    update_post_meta( $post_id, $key, $count );
}

function adminz_countview_function($atts){
    if(!isset(ADMINZ_DefaultOptions::$options['adminz_enable_countview']) or !ADMINZ_DefaultOptions::$options['adminz_enable_countview'] == "on") {
        return "<mark>Enable tool-> administrator z -> default option ->  Countview ->  Enable count view function</mark>";
    }
    extract(shortcode_atts(array(
        'post_id'    => get_the_ID(),
        'icon' => 'eye',
        'textbefore' => '',
        'textafter' => '',
        'class' => 'adminz_count_view'
    ), $atts));
    ob_start();
    echo '<div class="'.esc_attr($class).'">';
    echo esc_attr($textbefore);
    if($icon) {
        echo Adminz::get_icon_html($icon, ["style"=>["width"=>"1em", "display"=>"inline-block"], ] );
    }
    echo " ". get_post_meta( $post_id, 'adminz_countview', true );
    echo " ".esc_attr($textafter);    
    echo "</div>";
    return ob_get_clean();
}