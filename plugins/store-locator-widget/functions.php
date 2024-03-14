<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

// Create the row to store the keys
function storelocatorwidget_create_first_row(){
  global $wpdb;
  $table_name = storelocatorwidget_get_table_name();
  $wpdb->insert( $table_name, array('storelocatorwidget_api' => '', 'google_api' => ''), array());
}

// Save the storelocatorwidget API key
function storelocatorwidget_save_storelocatorwidget_api($api){
  global $wpdb;

  $table_id = 1;
  $table_name = storelocatorwidget_get_table_name();
  $wpdb->query($wpdb->prepare("UPDATE ".$table_name." SET storelocatorwidget_api='$api' WHERE id = %d", $table_id));
}

// Save the Google API key
function storelocatorwidget_save_google_api($gapi){
  global $wpdb;

  $table_id = 1;
  $table_name = storelocatorwidget_get_table_name();
  $wpdb->query($wpdb->prepare("UPDATE ".$table_name." SET google_api='$gapi' WHERE id = %d", $table_id));
}

// Get the storelocatorwidget api from the db
function storelocatorwidget_get_storelocatorwidget_api(){
  global $wpdb;

  $table_id = 1;
  $table_name = storelocatorwidget_get_table_name();
  $api = $wpdb->get_row( $wpdb->prepare( "SELECT storelocatorwidget_api FROM " .$table_name. " WHERE ID = %d", $table_id));
  return $api->storelocatorwidget_api;
}

// Get the google API from the db
function storelocatorwidget_get_google_api(){
  global $wpdb;

  $table_id = 1;
  $table_name = storelocatorwidget_get_table_name();
  $gapi = $wpdb->get_row( $wpdb->prepare( "SELECT google_api FROM " .$table_name. " WHERE ID = %d", $table_id));
  return $gapi->google_api;
}

// Process the form data
function storelocatorwidget_process_storelocatorwidget_keys(){
  if ($_POST){

    // Check for the google api key
    if (isset($_POST['google_api_key'])){
      storelocatorwidget_save_google_api(sanitize_text_field($_POST['google_api_key']));
    }

    // Check for the apply api key
    if (isset($_POST['storelocatorwidget_api_key'])){
      storelocatorwidget_save_storelocatorwidget_api(sanitize_text_field($_POST['storelocatorwidget_api_key']));
    }

    // redirect
    wp_redirect(admin_url( 'admin.php?page=store-locator-widget/store-locator-widget.php&settings-saved'));
    exit;
  }
}
