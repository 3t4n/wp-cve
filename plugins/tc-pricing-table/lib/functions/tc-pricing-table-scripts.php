<?php
 function tc_pricing_table_scripts() {
 		//Plugin Main CSS File.
  wp_enqueue_style('tcpt-style', plugins_url('/../../assets/css/tcpt-plugin.css', __FILE__ ) );
  wp_enqueue_style('font-awesome', plugins_url('/../../vendors/font-awesome/css/font-awesome.css', __FILE__ ) );

  }
 //This hook ensures our scripts and styles are only loaded in the admin.
 add_action( 'wp_enqueue_scripts', 'tc_pricing_table_scripts' );

 if ( function_exists( 'add_theme_support' ) ) {
     add_theme_support( 'post-thumbnails' );
 }

 add_action( 'admin_enqueue_scripts', 'tc_pricing_table_admin_style' );

 function tc_pricing_table_admin_style() {

  wp_enqueue_style( 'tcpt_pricing_admin', plugins_url('/../../assets/css/tcpt-admin.css',__FILE__ ));

 }

 ?>
