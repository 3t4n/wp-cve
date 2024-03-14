<?php
function bc_spawp_get_template_part($slug, $name = null) {

  do_action("spawp_get_template_part_{$slug}", $slug, $name);

  $templates = array();
  if (isset($name))
      $templates[] = "{$slug}-{$name}.php";

  $templates[] = "{$slug}.php";

  bc_spawp_get_template_path($templates, true, false);
}

function bc_spawp_get_template_path($template_names, $load = false, $require_once = true ) {
	$themedata = wp_get_theme();
	$mytheme = $themedata->name;
	$mytheme = strtolower( $mytheme );
	$mytheme = str_replace( ' ','-', $mytheme );

    $located = ''; 
    foreach ( (array) $template_names as $template_name ) { 
      if ( !$template_name ) 
        continue; 

      /* search file within the PLUGIN_DIR_PATH only */ 
      if ( file_exists(bc_plugin_dir . "inc/$mytheme/" . $template_name)) { 
        $located = bc_plugin_dir . "inc/$mytheme/" . $template_name; 
        break; 
      }
    }

    if ( $load && '' != $located )
        load_template( $located, $require_once );

    return $located;
}

function bc_spawp_theme_init(){

  if( class_exists('Spawp_Premium_Theme_Setup') ){
    return;
  }

	include('default-data.php');
	include('front-page-helpers.php');
  include('customizer/front-page-slider-settings.php');
  include('customizer/front-page-sections-common-settings.php');
  include('customizer/front-page-sections-contents-settings.php');
  include('css-output.php');
}
add_action('init','bc_spawp_theme_init', 20);