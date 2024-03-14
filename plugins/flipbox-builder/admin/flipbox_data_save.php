<?php
if (!defined('ABSPATH')) exit;
$PostID = $postid;
if (isset($PostID) && isset($_POST['flipbox_builder_Flipbox_save_data_action']))
{
    if (!wp_verify_nonce($_POST['wptexture_flipbox_security'], 'wptexture_flipbox_nonce_save_settings_values')) {
        die();
    }
    $TotalCount = count($_POST['front_title']);
    $All_data = array();
    if ($TotalCount)
    {
        for ($i = 0;$i < $TotalCount;$i++)
        {
            $front_title = sanitize_text_field($_POST['front_title'][$i]);
            $front_icon = sanitize_text_field($_POST['front_icon'][$i]);
            $back_description = (sanitize_textarea_field($_POST['back_description'][$i]));
            $btntext = sanitize_text_field($_POST['btntext'][$i]);
            $back_link = sanitize_text_field($_POST['back_link'][$i]);
            $flip_image_field = sanitize_text_field($_POST['flip_image_field'][$i]);
			$flip_image_id = sanitize_text_field($_POST['flip_image_id'][$i]);
            $All_data[] = array(
                'front_title' => $front_title,
                'front_icon' => $front_icon,
                'back_description' => $back_description,
                'btntext' => $btntext,
                'back_link' => $back_link,
                'flip_image_field' => $flip_image_field,
				'flip_image_id' => $flip_image_id,

            );
        }

        update_post_meta($PostID, 'flipbox_builder_Flipbox_data', serialize($All_data));
        update_post_meta($PostID, 'flipbox_builder_Flipbox_count', $TotalCount);
    }
    else
    {
        $TotalCount = - 1;
        update_post_meta($PostID, 'flipbox_builder_Flipbox_count', $TotalCount);
        $All_data = array();
        update_post_meta($PostID, 'flipbox_builder_Flipbox_data', serialize($All_data));
    }
}

if (isset($postid) && isset($_POST['flipbox_builder_flipbox_setting_save_action']))
{
    if (!wp_verify_nonce($_POST['wptexture_flipbox_security'], 'wptexture_flipbox_nonce_save_settings_values')) {
        die();
    }

    $flip_fliptype = sanitize_text_field($_POST['flip_fliptype']);
    $flip_itemperrow = sanitize_text_field($_POST['flip_itemperrow']);
	$flip_linkopen = sanitize_text_field($_POST['flip_linkopen']);
	$flip_icon_size = sanitize_text_field($_POST['flip_icon_size']);
    $flipfrontcolor = sanitize_text_field($_POST['flipfrontcolor']);
    $flipbackgcolor = sanitize_text_field($_POST['flipbackgcolor']);    
    $flip_title_font = sanitize_text_field($_POST['flip_title_font']);
    $fliptitlecolor = sanitize_text_field($_POST['fliptitlecolor']);
    $flip_title_fontfamily = sanitize_text_field($_POST['flip_title_fontfamily']);
    $flip_desc_font_size = sanitize_text_field($_POST['flip_desc_font_size']);
    $flipdesccolor = sanitize_text_field($_POST['flipdesccolor']);
    $flip_desc_font = sanitize_text_field($_POST['flip_desc_font']);
    $flipbuttoncolor = sanitize_text_field($_POST['flipbuttoncolor']);
    $flipbuttonbackccolor = sanitize_text_field($_POST['flipbuttonbackccolor']);
    $flip_custom_css = sanitize_text_field($_POST['flip_custom_css']);
    $templates = sanitize_text_field($_POST['templates']);
    $flipbuttonbackhcolor = sanitize_text_field($_POST['flipbuttonbackhcolor']);
    $flipbuttonhcolor = sanitize_text_field($_POST['flipbuttonhcolor']);
    $flipiconcolor = sanitize_text_field($_POST['flipiconcolor']);
    $flipbackcolor = sanitize_text_field($_POST['flipbackcolor']);
    $flip_textalign = sanitize_text_field($_POST['flip_textalign']);
	$flipbuttonborderccolor = sanitize_text_field($_POST['flipbuttonborderccolor']);
	$flipbuttonhbordercolor = sanitize_text_field($_POST['flipbuttonhbordercolor']);

    $Flipbox_Settings_Array = serialize(array(
        'flip_fliptype' => $flip_fliptype,
		'flip_itemperrow'=>$flip_itemperrow,
		'flip_linkopen'=>$flip_linkopen,
		'flip_icon_size'=>$flip_icon_size,
        'flipfrontcolor' => $flipfrontcolor,
        'flipbackgcolor' => $flipbackgcolor,       
        'flip_title_font' => $flip_title_font,
        'fliptitlecolor' => $fliptitlecolor,
        'flip_title_fontfamily' => $flip_title_fontfamily,
        'flip_desc_font_size' => $flip_desc_font_size,
        'flipdesccolor' => $flipdesccolor,
        'flip_desc_font' => $flip_desc_font,
        'flipbuttoncolor' => $flipbuttoncolor,
        'flipbuttonbackccolor' => $flipbuttonbackccolor,
        'flip_custom_css' => $flip_custom_css,
        'templates' => $templates,
        'flipbuttonbackhcolor' => $flipbuttonbackhcolor,
        'flipbuttonhcolor' => $flipbuttonhcolor,
        'flipiconcolor' => $flipiconcolor,
        'flipbackcolor' => $flipbackcolor,
        'flip_textalign' => $flip_textalign,
		'flipbuttonborderccolor' => $flipbuttonborderccolor,
		'flipbuttonhbordercolor' => $flipbuttonhbordercolor,
    ));
    update_post_meta($postid, 'flipbox_builder_Flipbox_Settings', $Flipbox_Settings_Array);
}

?>
