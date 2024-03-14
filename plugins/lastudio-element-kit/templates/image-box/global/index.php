<?php
/**
 * Image Box template
 */

$settings = $this->get_settings_for_display();
$box_style_simple = $this->get_settings_for_display('box_style_simple');
$box_border_hover_background_direction = $this->get_settings_for_display('box_border_hover_background_direction');

$box_content_text_align = $this->get_settings_for_display('box_content_text_align');
$box_enable_btn = $this->get_settings_for_display('box_enable_btn');
$box_icon_align = $this->get_settings_for_display('box_icon_align');
$box_front_title_icons = $this->get_settings_for_display('box_front_title_icons');
$box_front_title_icon_position = $this->get_settings_for_display('box_front_title_icon_position');
$box_title_text = $this->get_settings_for_display('box_title_text');
$box_btn_url = $this->get_settings_for_display('box_btn_url');
$box_enable_link = $this->get_settings_for_display('box_enable_link');
$box_website_link = $this->get_settings_for_display('box_website_link');
$title_tag = lastudio_kit_helper()->validate_html_tag( $this->get_settings_for_display('box_title_size') );
$body_icon_hover_animation = sprintf('elementor-animation-%s', $this->get_settings_for_display('body_icon_hover_animation'));

$box_top_icons__pos = $this->get_settings_for_display('box_top_icons__pos');
if(empty($box_top_icons__pos)){
    $box_top_icons__pos = 'top';
}

$box_classes = ['lakit-imagebox'];
$box_classes[] = 'text-' . $box_content_text_align;
$box_classes[] = 'lakit-imagebox__content-align-' . $box_content_text_align;
$box_classes[] = $box_style_simple;

if ($box_style_simple == 'hover-border-bottom') {
    $box_classes[] = $box_border_hover_background_direction;
}

$this->add_render_attribute('wrapper', 'class', $box_classes);


// Image  wrapper
$link_wrapper_start = '';
$link_wrapper_end = '';

if(filter_var($box_enable_btn, FILTER_VALIDATE_BOOLEAN)){
    $this->add_link_attributes('link', $box_btn_url );
    $link_wrapper_start .= '<a ' . $this->get_render_attribute_string('link') . '>';
    $link_wrapper_end .= '</a>';
}

$title_open_tag = '<'.$title_tag.' class="lakit-imagebox__title">' . $link_wrapper_start;
$title_close_tag = $link_wrapper_end . '</'.$title_tag.'>';

echo sprintf('<div %1$s>', $this->get_render_attribute_string('wrapper'));
    if(filter_var($box_enable_link, FILTER_VALIDATE_BOOLEAN)){
        $this->add_link_attributes( 'box_link', $box_website_link);
        echo sprintf('<a %1$s>', $this->get_render_attribute_string( 'box_link' ));
    }
    echo $this->get_main_image('<div class="lakit-imagebox__header figure__object_fit">%s</div>');
    if(filter_var($box_enable_link, FILTER_VALIDATE_BOOLEAN)){
        echo '</a>';
    }
    echo '<div class="lakit-imagebox__body">';
        echo '<div class="lakit-imagebox__body_inner">';
            if($box_top_icons__pos === 'top'){
                echo $this->get_main_icon('<div class="lakit-imagebox__top_icon '.$body_icon_hover_animation.'"><span class="lakit-imagebox__top_icon_inner">%s</span></div>');
            }
            if($box_front_title_icon_position === 'left'){
                $title_open_tag .= $this->get_title_icon();
            }
            elseif ($box_front_title_icon_position === 'right'){
                $title_close_tag = $this->get_title_icon() . $title_close_tag;
            }
            $this->_html( 'box_title_text', $title_open_tag . '<span class="lakit-imagebox__title_text">%s</span>' . $title_close_tag );
            if($box_top_icons__pos === 'bottom'){
                echo $this->get_main_icon('<div class="lakit-imagebox__top_icon '.$body_icon_hover_animation.'"><span class="lakit-imagebox__top_icon_inner">%s</span></div>');
            }
            $this->_html( 'box_description_text', '<div class="lakit-imagebox__desc">%s</div>' );
        echo '</div>';
        if(filter_var($box_enable_btn, FILTER_VALIDATE_BOOLEAN)){
            echo '<div class="lakit-iconbox__button_wrapper">';
            $this->add_link_attributes( 'button', $box_btn_url);
            $this->add_render_attribute('button', 'class', 'elementor-button-link elementor-button elementor-btn-align-icon-'. $box_icon_align );
            $btn_text = $this->get_button_icon('<span class="elementor-button-icon">%s</span>');
            $btn_text .= sprintf('<span class="elementor-button-text">%s</span>', $this->get_settings_for_display('box_btn_text'));
            echo sprintf('<a %1$s><span class="elementor-button-content-wrapper e-icon-align-%3$s">%2$s</span></a>', $this->get_render_attribute_string('button'), $btn_text, $box_icon_align);
            echo '</div>';
        }
    echo '</div>';
echo '</div>';