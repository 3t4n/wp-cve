<?php
if (!defined('WPINC')) {
    die;
}

// Flex Value

$lgx_flexbox_column_gap         = (isset($lgx_generator_meta['lgx_flexbox_column_gap']) ? $lgx_generator_meta['lgx_flexbox_column_gap'] : '15px');
$lgx_flexbox_row_gap            = (isset($lgx_generator_meta['lgx_flexbox_row_gap']) ? $lgx_generator_meta['lgx_flexbox_row_gap'] : '15px');
$lgx_flexbox_align_items        = (isset($lgx_generator_meta['lgx_flexbox_align_items']) ? $lgx_generator_meta['lgx_flexbox_align_items'] : 'flex-start');
$lgx_flexbox_justify_content    = (isset($lgx_generator_meta['lgx_flexbox_justify_content']) ? $lgx_generator_meta['lgx_flexbox_justify_content'] : 'flex-start');
$lgx_flexbox_wrap               = (isset($lgx_generator_meta['lgx_flexbox_wrap']) ? $lgx_generator_meta['lgx_flexbox_wrap'] : 'wrap');
$lgx_flexbox_direction          = (isset($lgx_generator_meta['lgx_flexbox_direction']) ? $lgx_generator_meta['lgx_flexbox_direction'] : 'row');


// Value
$lgx_grid_column_gap     = $lgx_generator_meta['lgx_grid_column_gap'];
$lgx_grid_row_gap        = $lgx_generator_meta['lgx_grid_row_gap'];

$lgx_lsw_dynamic_style_grid = '';
$lgx_lsw_dynamic_style_grid .= '#lgx_counter_up_app_'. $lgx_app_id.' .lgx_app_layout_flexbox .lgx_app_item_row{
                        -ms-flex-wrap: '.$lgx_flexbox_wrap .';
                        flex-wrap: '.$lgx_flexbox_wrap .';
                        gap: '.$lgx_flexbox_column_gap .' '.$lgx_flexbox_row_gap .';
                        align-items: '.$lgx_flexbox_align_items .'; 
                        justify-content: '.$lgx_flexbox_justify_content .';
                        flex-direction: '.$lgx_flexbox_direction.';
                        }';

    $lgx_lsw_dynamic_style_grid .= '#lgx_counter_up_app_'. $lgx_app_id.' .lgx_app_layout_flexbox .lgx_app_item {
        -ms-flex: 0 0 calc('.bcdiv('100', $lgx_large_desktop_item, 8).'% - '.$lgx_flexbox_row_gap .');
        flex: 0 0 calc('.bcdiv('100', $lgx_large_desktop_item, 8).'% - '.$lgx_flexbox_row_gap .');
        width: '.bcdiv('100', $lgx_large_desktop_item, 8).'%;
    }';


$lgx_lsw_dynamic_style_grid .= '@media (max-width: 767px) {
        #lgx_counter_up_app_'. $lgx_app_id.' .lgx_app_layout_flexbox .lgx_app_item {
            -ms-flex: 0 0 calc('.bcdiv('100', $lgx_mobile_item, 8).'% - '.$lgx_flexbox_row_gap .');
            flex: 0 0 calc('.bcdiv('100', $lgx_mobile_item, 8).'% - '.$lgx_flexbox_row_gap .');
            width: '.bcdiv('100', $lgx_mobile_item, 8).'%;
        }
        
    }';
$lgx_lsw_dynamic_style_grid .= '@media (min-width: 768px) {

        #lgx_counter_up_app_'. $lgx_app_id.' .lgx_app_layout_flexbox .lgx_app_item {
            -ms-flex: 0 0 calc('.bcdiv('100', $lgx_tablet_item, 8).'% - '.$lgx_flexbox_row_gap .');
            flex: 0 0 calc('.bcdiv('100', $lgx_tablet_item, 8).'% - '.$lgx_flexbox_row_gap .');
            width: '.bcdiv('100', $lgx_tablet_item, 8).'%;
        }
    }';
$lgx_lsw_dynamic_style_grid .= '@media (min-width: 992px) {
        #lgx_counter_up_app_'. $lgx_app_id.' .lgx_app_layout_flexbox .lgx_app_item {
            -ms-flex: 0 0 calc('.bcdiv('100', $lgx_desktop_item, 8).'% - '.$lgx_flexbox_row_gap .');
            flex: 0 0 calc('.bcdiv('100', $lgx_desktop_item, 8).'% - '.$lgx_flexbox_row_gap .');
            width: '.bcdiv('100', $lgx_desktop_item, 8).'%;
        }
    }';
$lgx_lsw_dynamic_style_grid .= '@media (min-width: 1200px) {
        #lgx_counter_up_app_'. $lgx_app_id.' .lgx_app_layout_flexbox .lgx_app_item {
            -ms-flex: 0 0 calc('.bcdiv('100', $lgx_large_desktop_item, 8).'% - '.$lgx_flexbox_row_gap .');
            flex: 0 0 calc('.bcdiv('100', $lgx_large_desktop_item, 8).'% - '.$lgx_flexbox_row_gap .');
            width: '.bcdiv('100', $lgx_large_desktop_item, 8).'%;
        }
    }';



/**
 *  Inline Style
 */

wp_add_inline_style( 'lgx-counter-up-style', $lgx_lsw_dynamic_style_grid );