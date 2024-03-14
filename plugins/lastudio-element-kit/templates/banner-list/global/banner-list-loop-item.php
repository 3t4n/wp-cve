<?php
/**
 * Images list item template
 */

$layout             = $this->get_settings_for_display('layout_type');
$preset             = $this->get_settings_for_display('preset_' . $layout);
$title_tag          = $this->get_settings_for_display('title_tag');
if(empty($title_tag)){
    $title_tag = 'div';
}

$_processed_item = $this->_processed_item;

$col_class = [];
$col_class[] = 'elementor-repeater-item-' . $_processed_item['_id'];
$col_class[] = 'lakit-bannerlist__item';
$el_class = $this->_loop_item( array( 'el_class' ), '%s' );
if(!empty($el_class)){
    $col_class[] = $el_class;
}

$enable_carousel    = filter_var($this->get_settings_for_display('enable_carousel'), FILTER_VALIDATE_BOOLEAN);

if($enable_carousel){
    $col_class[] = 'swiper-slide';
}
else{
	$col_class[] = lastudio_kit_helper()->col_new_classes('columns', $this->get_settings_for_display());
}

$link_tag = 'a';
$btn_tag = 'a';

$link_instance = 'link_' . $this->_processed_index;
$btn_instance = 'btn_' . $this->_processed_index;

if(!empty($_processed_item['link']['url'])){
    if($_processed_item['link_click'] == 'button'){
        $this->_add_link_attributes( $btn_instance, $_processed_item['link'] );
    }
    else{
        $this->_add_link_attributes( $link_instance, $_processed_item['link'] );
    }
}
else{
  $link_tag = 'div';
}

$this->add_render_attribute( $btn_instance, 'class', array(
    'elementor-button lakit-bannerlist__btn'
) );

$this->add_render_attribute( $link_instance, 'class', array(
    'lakit-bannerlist__link'
) );

if($_processed_item['link_click'] == 'button'){
    $link_tag = 'div';
}
else{
    $btn_tag = 'span';
}

$item_instance = 'item-instance-' . $this->_processed_index;

$this->add_render_attribute( $item_instance, 'class', $col_class );
$div_c_attributes = [];
if(!empty($_processed_item['custom_attributes'])){
    $div_c_attributes = \Elementor\Utils::parse_custom_attributes( $_processed_item['custom_attributes'] );
}
if(!empty($div_c_attributes)){
    $this->add_render_attribute( $item_instance, $div_c_attributes );
}

$btn_icon =  $this->_get_icon_setting( $this->get_settings_for_display('selected_btn_icon'), '<span class="btn-icon">%s</span>' );

$__image_html = $this->render_loop_image_item('<div class="lakit-bannerlist__image">%1$s</div>', false);
$__content_html = '';
$subtitle = $this->_loop_item( ['subtitle'] );
if(!empty($subtitle)){
    $__content_html .= sprintf('<div class="lakit-bannerlist__subtitle" data-title="%2$s">%1$s</div>', $subtitle, esc_attr(wp_strip_all_tags($subtitle)));
}
$title = $this->_loop_item( ['title'] );
if(!empty($title)){
    $__content_html .= sprintf('<%3$s class="lakit-bannerlist__title" data-title="%2$s">%1$s</%3$s>', $title, esc_attr(wp_strip_all_tags($title)), $title_tag);
}
$__content_html .= $this->_loop_item( array( 'description' ), '<div class="lakit-bannerlist__desc">%1$s</div>' );
$__content_html .= $this->_loop_item( array( 'subdescription' ), '<div class="lakit-bannerlist__subdesc">%1$s</div>' );

$button_html = '';
if(!empty($_processed_item['button_text']) || !empty($btn_icon)){
    $button_html = sprintf('<div class="lakit-bannerlist__btn_wrap"><%1$s %2$s>%3$s%4$s</%1$s></div>', $btn_tag, $this->get_render_attribute_string($btn_instance), $_processed_item['button_text'], $btn_icon);
}

if('flat02' !== $preset){
	$__content_html .= $button_html;
}
$tmp__content_html = '';
if(!empty($__content_html)){
	$tmp__content_html = sprintf('<div class="lakit-bannerlist__content"><div class="lakit-bannerlist__content-inner">%1$s</div></div>', $__content_html);
}
if('flat02' === $preset){
	$tmp__content_html .= $button_html;
}
echo sprintf(
    '<div %1$s><div class="lakit-bannerlist__inner"><%2$s %3$s>%4$s%5$s</%2$s></div></div>',
    $this->get_render_attribute_string( $item_instance ),
    $link_tag,
    $this->get_render_attribute_string( $link_instance ),
    $__image_html,
	$tmp__content_html
);