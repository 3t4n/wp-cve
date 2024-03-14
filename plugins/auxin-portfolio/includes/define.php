<?php

// no direct access allowed
if ( ! defined('ABSPATH') ) {
    die();
}

//die( "MJ" );

// theme name
if( ! defined( 'THEME_NAME' ) ){
    $theme_data = wp_get_theme();
    define( 'THEME_NAME', $theme_data->Name );
}



define( 'AUXPFO_VERSION'        , '2.3.2' );

define( 'AUXPFO_SLUG'           , 'auxin-portfolio' );


define( 'AUXPFO_DIR'            , dirname( plugin_dir_path( __FILE__ ) ) );
define( 'AUXPFO_URL'            , plugins_url( '', plugin_dir_path( __FILE__ ) ) );
define( 'AUXPFO_BASE_NAME'      , plugin_basename( AUXPFO_DIR ) . '/auxin-portfolio.php' ); // auxin-portfolio/auxin-portfolio.php


define( 'AUXPFO_ADMIN_DIR'      , AUXPFO_DIR . '/admin' );
define( 'AUXPFO_ADMIN_URL'      , AUXPFO_URL . '/admin' );

define( 'AUXPFO_INC_DIR'        , AUXPFO_DIR . '/includes' );
define( 'AUXPFO_INC_URL'        , AUXPFO_URL . '/includes' );

define( 'AUXPFO_PUB_DIR'        , AUXPFO_DIR . '/public' );
define( 'AUXPFO_PUB_URL'        , AUXPFO_URL . '/public' );
