<?php
if (!defined('ABSPATH')) exit;
add_shortcode('Flipbox', 'flipbox_builder_Flipbox_ShortCode');
function flipbox_builder_Flipbox_ShortCode($Id)
{
    ob_start();
    if (!isset($Id['id']))
    {
        $flipbox_ID = "";
    }
    else
    {
        $flipbox_ID = $Id['id'];
    }
?>	

	<?php
    $args = array(
        'post_type' => 'fb',
        'p' => $flipbox_ID,
        'orderby' => 'ASC'
    );
    $wp_query = new WP_Query($args);
    query_posts($wp_query);
    while ($wp_query->have_posts()):
        $wp_query->the_post();
        $PostId = get_the_ID();
        $All_data = unserialize(get_post_meta($PostId, 'flipbox_builder_Flipbox_data', true));
        $TotalCount = get_post_meta($PostId, 'flipbox_builder_Flipbox_count', true);
        $Flipbox_Settings = unserialize(get_post_meta($PostId, 'flipbox_builder_Flipbox_Settings', true));
        $Default_Settings = unserialize(get_option('flipbox_builder_Flipbox_default_Settings'));
        $option_names1 = array(
            "flip_fliptype" => $Default_Settings["flip_fliptype"],
            "flip_itemperrow" => $Default_Settings["flip_itemperrow"],
			"flip_linkopen" => $Default_Settings["flip_linkopen"],
			"flip_icon_size" => $Default_Settings["flip_icon_size"],
            "flipfrontcolor" => $Default_Settings["flipfrontcolor"],
            "flipbackgcolor" => $Default_Settings["flipbackgcolor"],           
            "flip_title_font" => $Default_Settings["flip_title_font"],
            "fliptitlecolor" => $Default_Settings["fliptitlecolor"],
            "flip_title_fontfamily" => $Default_Settings["flip_title_fontfamily"],
            "flip_desc_font_size" => $Default_Settings["flip_desc_font_size"],
            "flipdesccolor" => $Default_Settings["flipdesccolor"],
            "flip_desc_font" => $Default_Settings["flip_desc_font"],
            "flip_custom_css" => $Default_Settings["flip_custom_css"],
            "flipbuttoncolor" => $Default_Settings["flipbuttoncolor"],
            "flipbuttonbackccolor" => $Default_Settings["flipbuttonbackccolor"],
            "templates" => $Default_Settings["templates"],
            "flipbuttonbackhcolor" => $Default_Settings["flipbuttonbackhcolor"],
            "flipbuttonhcolor" => $Default_Settings["flipbuttonhcolor"],
            "flipiconcolor" => $Default_Settings["flipiconcolor"],
            "flipbackcolor" => $Default_Settings["flipbackcolor"],
            "flip_textalign" => $Default_Settings["flip_textalign"],
			"flipbuttonborderccolor" => $Default_Settings["flipbuttonborderccolor"],
			"flipbuttonhbordercolor" => $Default_Settings["flipbuttonhbordercolor"],
        );
        foreach ($option_names1 as $option_name1 => $default_value1)
        {
            if (isset($Flipbox_Settings[$option_name1])) $
            {
                "" . $option_name1
            } = $Flipbox_Settings[$option_name1];
            else $
            {
                "" . $option_name1
            } = $default_value1;
        }
?>

<?php 
		require (FLIPBOXBUILDER_DIR_PATH . "template-front/design-" . $templates . ".php");
		wp_enqueue_style('flipbox_builder_flip_design-' . $templates . '');
    endwhile;
    wp_reset_query();
    return ob_get_clean();
?>

<?php
}
?>