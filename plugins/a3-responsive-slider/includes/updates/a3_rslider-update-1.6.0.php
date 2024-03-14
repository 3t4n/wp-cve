<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$a3_rslider_template1_control_settings = get_option( 'a3_rslider_template1_control_settings' );
$a3_rslider_template1_control_settings['pauseplay_icon_transition'] = ! empty( $a3_rslider_template1_control_settings['slider_control_transition'] ) ? $a3_rslider_template1_control_settings['slider_control_transition'] : 'hover';
$a3_rslider_template1_control_settings['pauseplay_icon_size'] = ! empty( $a3_rslider_template1_control_settings['slider_control_icons_size'] ) ? $a3_rslider_template1_control_settings['slider_control_icons_size'] : 30;
$a3_rslider_template1_control_settings['pauseplay_icon_color'] = ! empty( $a3_rslider_template1_control_settings['slider_control_icons_color'] ) ? $a3_rslider_template1_control_settings['slider_control_icons_color'] : '#000000';
$a3_rslider_template1_control_settings['pauseplay_icon_opacity'] = ! empty( $a3_rslider_template1_control_settings['slider_control_icons_opacity'] ) ? $a3_rslider_template1_control_settings['slider_control_icons_opacity'] : 60;

update_option( 'a3_rslider_template1_control_settings', $a3_rslider_template1_control_settings );