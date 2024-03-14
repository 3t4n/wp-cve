<?php

/**
 * @package Flag Icons 
 * @version 2
 */

/*

Plugin Name: Flag Icons 

Plugin URI: http://www.webcraft.gr/muli

Description: This plugin helps you to add flag icons with links on the site.

Author: Vasilis Triantafyllou, Dimitrios Chatzidimitriou

Version: 2.2

Author URI: http://www.webcraft.gr

*/


$number_of_options = 91; 

add_action('wp_footer', 'Ffll_fucntion');

require ('settingsmenu.php');   

add_shortcode( 'flagicons', 'Ffll__langshort' );


function Ffll__langshort ($atts) {

$number_of_options = 91; 
for ($i=1;$i<=$number_of_options;$i++){

	${'opt'.$i} = get_option('option_'.$i);

}

/* END OF SQUEEZING opts */
require('result.php');

}



function Ffll_fucntion () {

$op1 = get_option('option_lr');

$op2 = get_option('option_lm');

$op3 = get_option('option_lb');

$op4 = get_option('option_mt');

$op5 = get_option('option_mb');

$op6 = get_option('option_rt');

$op7 = get_option('option_rm');

$op8 = get_option('option_rb');

$opff = get_option('option_float');

$opin = get_option('option_inline');


if ($op1 == 1) { ?><div class ="op1"><?php }

elseif ($op2 == 1) { ?><div class ="op2"><?php }

elseif ($op3 == 1) { ?><div class ="op3"><?php }

elseif ($op4 == 1) { ?><div class ="op4"><?php }

elseif ($op5 == 1) { ?><div class ="op5"><?php }

elseif ($op6 == 1) { ?><div class ="op6"><?php }

elseif ($op7 == 1) { ?><div class ="op7"><?php }

elseif ($op8 == 1) { ?><div class ="op8"><?php } 

else { ?><div class ="op9"><?php }

if ($opff == 1) { ?><style>.op8,.op7,.op6,.op5,.op4,.op3,.op2,.op1 {position:absolute !important;}</style><?php } else {  ?><style>.op8,.op7,.op6,.op5,.op4,.op3,.op2,.op1  {position:fixed!important;}</style><?php }

if ($opin == 1) { ?><style>.op8 a,.op7 a,.op6 a,.op5 a,.op4 a,.op3 a,.op2 a,.op1  a {float:left;margin:5px;}</style><?php } else { ?><style>.op8 a,.op7 a,.op6 a,.op5 a,.op4 a,.op3 a,.op2 a,.op1  a {display:block;}</style><?php }



$number_of_options = 91;


for ($i=1;$i<=$number_of_options;$i++){

	${'opt'.$i} = get_option('option_'.$i);

}


require('result.php'); } 


    add_action( 'admin_enqueue_scripts', 'safely_add_stylesheet_to_admin' );

    /**
     * Add stylesheet to the page
     */

    function safely_add_stylesheet_to_admin() {

        wp_enqueue_style( 'prefix-style', plugins_url('style.css', __FILE__) );

    }

    add_action( 'wp_enqueue_scripts', 'safely_add_stylesheet' );

    /**
     * Add stylesheet to the page
     */

    function safely_add_stylesheet() {

        wp_enqueue_style( 'prefix-style', plugins_url('style.css', __FILE__) ); }


// Add settings link on plugin page
function your_plugin_settings_link_fl($links) { 
  $settings_link = '<a href="options-general.php?page=my_plugin_menu_flags">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
 
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'your_plugin_settings_link_fl' );


		?>