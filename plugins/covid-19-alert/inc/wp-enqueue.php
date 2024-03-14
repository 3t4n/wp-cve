<?php

/**
 * Required Styles and Scripts
 */
function devign_covid_nineteen_wp_equeue() {
  
    // JS
    $javascript_uri = DEVIGN_COVID_19_PLUGIN_PATH . 'assets/scripts.js';
    $javascript = DEVIGN_COVID_19_PLUGIN_DIR . '/assets/scripts.js';

    wp_register_script( 
      'devign-covid-nineteen-script', 
      $javascript_uri,
      array( 'jquery' ), 
      filemtime( $javascript ) 
    );
    wp_enqueue_script( 'devign-covid-nineteen-script' );

    // CSS
    $stylesheet_uri = DEVIGN_COVID_19_PLUGIN_PATH . 'assets/styles.css';
    $stylesheet = DEVIGN_COVID_19_PLUGIN_DIR . '/assets/styles.css';

    wp_register_style( 
      'devign-covid-nineteen-style', 
      $stylesheet_uri,
      array(), 
      filemtime( $stylesheet ) 
    );
    wp_enqueue_style( 'devign-covid-nineteen-style' ); 

}
add_action( 'wp_enqueue_scripts', 'devign_covid_nineteen_wp_equeue' );


/**
 * Required Admin Styles and Scripts
 */
function devign_covid_nineteen_admin_equeue() {

    wp_enqueue_style( 'wp-color-picker');
    wp_enqueue_script( 'wp-color-picker');
    
    // JS
    $javascript_uri = DEVIGN_COVID_19_PLUGIN_PATH . 'assets/admin.js';
    $javascript = DEVIGN_COVID_19_PLUGIN_DIR . '/assets/admin.js';

    wp_register_script( 
      'devign-covid-nineteen-script-admin', 
      $javascript_uri,
      array( 'jquery' ), 
      filemtime( $javascript ) 
    );
    wp_enqueue_script( 'devign-covid-nineteen-script-admin' );

    // CSS
    $stylesheet_uri = DEVIGN_COVID_19_PLUGIN_PATH . 'assets/admin.css';
    $stylesheet = DEVIGN_COVID_19_PLUGIN_DIR . '/assets/admin.css';

    wp_register_style( 
      'devign-covid-nineteen-style-admin', 
      $stylesheet_uri,
      array(), 
      filemtime( $stylesheet ) 
    );
    wp_enqueue_style( 'devign-covid-nineteen-style-admin' ); 

}
add_action( 'admin_enqueue_scripts', 'devign_covid_nineteen_admin_equeue', 999999 );