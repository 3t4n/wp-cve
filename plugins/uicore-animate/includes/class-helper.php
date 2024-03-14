<?php
namespace UiCoreAnimate;

defined('ABSPATH') || exit();
/**
 * UiCore Utils Functions
 */
class Helper
{
    static function get_split_animations_list(){
        $animations = [
            'fadeInUp' => __('Fade In Up', 'uicore-animate'),
            'fadeInUp blur' => __('Fade In Up Blur', 'uicore-animate'),
            'fadeInUp cut' => __('Fade In Up Cut', 'uicore-animate'),
            'fadeInDown' => __('Fade In Down', 'uicore-animate'),
            'fadeInDown cut' => __('Fade In Down Cut', 'uicore-animate'),
            'fadeInLeft' => __('Fade In Left', 'uicore-animate'),
            'fadeInLeft cut' => __('Fade In Left Cut', 'uicore-animate'),
            'fadeInRight' => __('Fade In Right', 'uicore-animate'),
            'fadeInRight cut' => __('Fade In Right Cut', 'uicore-animate'),
            'fadeInUpAlt' => __('Fade In Up Alt', 'uicore-animate'),
            'fadeInUpAlt cut' => __('Fade In Up Alt Cut', 'uicore-animate'),
            'fadeIn' => __('Fade In', 'uicore-animate'),
            'zoomIn' => __('Zoom In', 'uicore-animate'),
            'scaleIn' => __('Scale In', 'uicore-animate'),
            'rollIn' => __('Roll In', 'uicore-animate'),
            'zoomOut' => __('Zoom Out', 'uicore-animate'),
            'zoomOutDown' => __('Zoom Out Down', 'uicore-animate'), 
            'zoomOutLeft' => __('Zoom Out Left', 'uicore-animate'),
            'zoomOutRight' => __('Zoom Out Right', 'uicore-animate'),
            'zoomOutUp' => __('Zoom Out Up', 'uicore-animate')
        ];
        $new_animations = apply_filters('uicore_split_animations_list', []);
        return array_merge($animations, $new_animations);
    }

}