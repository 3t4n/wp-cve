<?php
if (!defined('WPINC')) {
    die;
}

// Value
$lgx_grid_column_gap     = $lgx_generator_meta['lgx_grid_column_gap'];
$lgx_grid_row_gap        = $lgx_generator_meta['lgx_grid_row_gap'];

$lgx_lsw_dynamic_style_grid = '';

$lgx_lsw_dynamic_style_grid .= '#lgx_counter_up_app_'. $lgx_app_id.' .lgx_app_layout_grid .lgx_app_item_row{
        grid-column-gap: '. $lgx_grid_column_gap.';
        grid-row-gap: '. $lgx_grid_row_gap.';
        grid-template-columns: repeat('. $lgx_large_desktop_item.', 1fr);
    }';


$lgx_lsw_dynamic_style_grid .= '@media (max-width: 767px) {
        #lgx_counter_up_app_'. $lgx_app_id.' .lgx_app_layout_grid .lgx_app_item_row{
            grid-template-columns: repeat('. $lgx_mobile_item.', 1fr);
        }
    }';
$lgx_lsw_dynamic_style_grid .= '@media (min-width: 768px) {
        #lgx_counter_up_app_'. $lgx_app_id.' .lgx_app_layout_grid .lgx_app_item_row{
            grid-template-columns: repeat('. $lgx_tablet_item.', 1fr);
        }
    }';
$lgx_lsw_dynamic_style_grid .= '@media (min-width: 992px) {
        #lgx_counter_up_app_'. $lgx_app_id.' .lgx_app_layout_grid .lgx_app_item_row{
            grid-template-columns: repeat('. $lgx_desktop_item.', 1fr);
        }
    }';
$lgx_lsw_dynamic_style_grid .= '@media (min-width: 1200px) {
        #lgx_counter_up_app_'. $lgx_app_id.' .lgx_app_layout_grid .lgx_app_item_row{
            grid-template-columns: repeat('. $lgx_large_desktop_item.', 1fr);
        }
    }';



/**
 *  Inline Style
 */

wp_add_inline_style( 'lgx-counter-up-style', $lgx_lsw_dynamic_style_grid );