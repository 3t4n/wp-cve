<?php 

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/*-----------------------------------------------------------------------------------*/
/*	Import Files
/*-----------------------------------------------------------------------------------*/


function borderless_library_import_files() {
  $theme = wp_get_theme(get_template());
  $aesir = $architect = $beyond = $cafe = $church = $construction = $cryptocurrency = $dark = $edge = $education = $employment = $financial = $fitness = $food = $forum = $gym = $hotel = $ink = $it = $marvel = $mechanic = $medical = $minimalist = $music = $nectar = $nonprofit = $peak = $petshop = $photography = $politic = $rare = $realestate = $resume = $salon = $seller = $spark = $sport = $stream = $traveler = $visualmentor = $wedding = $winehouse = $zenith = $borderless_free_templates = $borderless_pro_templates = [];


  require_once( BORDERLESS__INC . "/library/templates/classic-templates.php" );
  require_once( BORDERLESS__INC . "/library/templates/borderless-templates.php" );

  return array_merge($aesir,$architect,$beyond,$cafe,$church,$construction,$cryptocurrency,$dark,$edge,$education,$employment,$financial,$fitness,$food,$forum,$gym,$hotel,$ink,$it,$marvel,$mechanic,$medical,$minimalist,$music,$nectar,$nonprofit,$peak,$petshop,$photography,$politic,$rare,$realestate,$resume,$salon,$seller,$spark,$sport,$stream,$traveler,$visualmentor,$wedding,$winehouse,$zenith,$borderless_templates);
}
add_filter( 'pt-library/import_files', 'borderless_library_import_files' );


/*-----------------------------------------------------------------------------------*/
/*	Import Files - Plugins
/*-----------------------------------------------------------------------------------*/


function library_register_plugins( $plugins ) {

  $theme = wp_get_theme(get_template());

  if ( $theme == 'Anzu' ) {

    $theme_plugins = [
      [ 
        'name'     => 'Borderless', 
        'description' => 'Widgets, Elements, Templates and Toolkit',
        'slug'     => 'borderless', 
        'required' => true,                    
        'preselected' => true,
      ],
      
      [ 
        'name'     => 'Elementor',
        'description' => 'Website Builder',
        'slug'     => 'elementor',        
        'preselected' => true,
      ],
      
    ];

  } else {

    $theme_plugins = [
      [ 
        'name'     => 'Borderless', 
        'description' => 'Widgets, Elements, Templates and Toolkit',
        'slug'     => 'borderless', 
        'required' => true,                    
        'preselected' => true,
      ],
      
      [ 
        'name'     => 'Elementor',
        'description' => 'Website Builder',
        'slug'     => 'elementor',        
        'preselected' => true,
      ],
      
      [ 
        'name'     => 'WPBakery',
        'description' => 'Website Builder',
        'slug'     => 'js_composer',        
        'source'   => 'https://cdn.visualmodo.com/plugin/js_composer.zip',
        'preselected' => true,
      ],
      
    ];

  }

  return array_merge( $plugins, $theme_plugins );
 
}
add_filter( 'library/register_plugins', 'library_register_plugins' );


/*-----------------------------------------------------------------------------------*/
/*	Import Files - After Setup
/*-----------------------------------------------------------------------------------*/

function borderless_library_after_import( $template ) {
  $theme = wp_get_theme(get_template());
  require_once( BORDERLESS__INC . "/library/templates/classic-templates-after.php" );
  require_once( BORDERLESS__INC . "/library/templates/borderless-templates-after.php" );

}

add_action( 'pt-library/after_import', 'borderless_library_after_import' );