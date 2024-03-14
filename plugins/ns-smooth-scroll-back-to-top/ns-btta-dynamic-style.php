<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


function ns_btta_dinamic_style()
{
    switch (get_option('ns_btta_position', 4)) {
        case 1:
            $margin_lr = "left: 20px;";
            $margin_tb = "top: 70px;";
            break;
        case 2:
            $margin_lr = "right: 20px;";
            $margin_tb = "top: 70px;";
            break;
        case 3:
            $margin_lr = "left: 20px;";
            $margin_tb = "bottom: 70px;";
            break;
        case 4:
            $margin_lr = "right: 20px;";
            $margin_tb = "bottom: 70px;";
            break;
    }

    ?>
        <style>
        .ns-back-to-top {
            <?php echo $margin_lr; ?>
            <?php echo $margin_tb; ?>
        	background-color: <?php echo get_option('ns_btta_background', '#FFFFFF'); ?>;
        	border: 1px solid <?php echo get_option('ns_btta_border_color', '#000000'); ?>; 	
        }
        .ns-back-to-top:hover {
        	background-color: <?php echo get_option('ns_btta_background_hover', '#000000'); ?>;
        	border: 1px solid <?php echo get_option('ns_btta_border_color_hover', '#FFFFFF'); ?>;
        }
        a.ns-back-to-top {
        	color: <?php echo get_option('ns_btta_text_color', '#000000'); ?>;
        }
        a.ns-back-to-top:hover {
        	color: <?php echo get_option('ns_btta_text_color_hover', '#FFFFFF'); ?>;
        }        
        </style>
    <?php
}
 
add_action('wp_head', 'ns_btta_dinamic_style');
?>