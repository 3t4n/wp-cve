<?php 

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

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
    //'source'   => get_template_directory_uri() . '/bundled-plugins/bundled-plugin.zip',
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