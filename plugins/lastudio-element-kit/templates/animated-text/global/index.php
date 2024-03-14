<?php
/**
 * Animated text list template
 */
$data_settings = $this->generate_setting_json();
$animation_effect = $this->get_settings_for_display('animation_effect');
$split_type = ( 'fx12' === $animation_effect ) ? 'symbol' : $this->get_settings_for_display('split_type');

$classes[] = 'lakit-animated-text';
$classes[] = 'lat-ef-' . $animation_effect;
$classes[] = 'lakit-animated-text--' . $split_type;

$html_tag = $this->get_settings_for_display('html_tag');

echo sprintf('<%1$s class="%2$s"%3$s>', $html_tag, implode( ' ', $classes ), $data_settings);
$this->_glob_inc_if( 'before-text', array( 'before_text_content' ) );
$this->_get_global_looped_template( 'animated-text', 'animated_text_list' );
$this->_glob_inc_if( 'after-text', array( 'after_text_content' ) );
echo sprintf('</%1$s>', $html_tag);
