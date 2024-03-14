<?php
/**
* Plugin Name:       ACF Woocommerce Account Fields
* Description:       Add Advanced Custom Fields to the Woocommerce registration form and edit profile form
* Version:           1.0.0
* Author:            Ken Key
* Author URI:        https://www.kennykey.com
* Text Domain:       kennykey
*/


class ACF_WC_ACCOUNT_FIELDS
{
  private $prefix = 'wc-acf-account-fields';
  public function __construct() {

    if(!defined('ACF_WC_ACCOUNT_FIELDS_URL'))
    define('ACF_WC_ACCOUNT_FIELDS_URL', plugin_dir_url( __FILE__ ));
    if(!defined('ACF_WC_ACCOUNT_FIELDS_PATH'))
    define('ACF_WC_ACCOUNT_FIELDS_PATH', plugin_dir_path( __FILE__ ));

    add_action( 'init', [$this, "init"] );

  }


  function init(){
    add_action( 'acf/init', array( $this, 'register_fields' ), 10, 0 );
    add_filter( 'acf/location/rule_types', array( $this, 'add_form_location_type' ), 10, 1 );
    add_filter( 'acf/location/rule_values/wc_account_fields', array( $this, 'form_location_rule_values' ), 10, 1 );
    add_filter('acf/location/rule_operators', array($this, 'acf_location_rules_operators'));
    add_action( 'woocommerce_register_form_start', array($this,'woocommerce_edit_my_account_page_top'), 15 );
    add_action( 'woocommerce_register_form', array($this,'woocommerce_edit_my_account_page_bottom'), 15 );
    add_action( 'woocommerce_edit_account_form', array($this,'woocommerce_edit_my_account_page_edit'), 30 );
    add_action( 'woocommerce_save_account_details', array($this,'wooc_save_extra_register_fields'));

    add_filter( 'woocommerce_get_settings_account', array( $this, 'wcpss_account_options' ) );
    add_filter('acf/load_field', array($this,'my_load_field'));
    add_filter('acf/prepare_field', array($this,'my_acf_prepare_field'));
    add_filter( 'woocommerce_registration_errors', array($this,'wooc_validate_extra_register_fields'), 10, 3 );
    add_action( 'woocommerce_save_account_details_errors', array($this,'woocommerce_save_account_details_errors'), 10,2 );
    add_action( 'woocommerce_created_customer', array($this, 'wooc_save_extra_register_fields' ));
    add_action( 'wp_enqueue_scripts', [$this,'add_theme_scripts'] );


  }



  function woocommerce_save_account_details_errors(&$args, &$user){

    if(!acf_validate_save_post()){
      $errors = acf_get_validation_errors();
      foreach($errors as $index => $error){
        wc_add_notice( '<strong>'+$error["message"]+'</strong> ' . __( 'is a required field.', 'woocommerce' ), 'error' );
      }
    }
  }

  function add_theme_scripts() {
    wp_enqueue_style('wc_acf_af', ACF_WC_ACCOUNT_FIELDS_URL."css/admin-style.css");
  }


  function wooc_save_extra_register_fields( $customer_id ) {

    if(!empty($_POST["acf"])){
      acf_update_values( $_POST['acf'], "user_".$customer_id);
    }
  }

  function my_acf_prepare_field( $field ) {

    if(in_array($field["type"], ["true_false"])){
      return $field;
    }

    $field['class'] .= ' input-text';

    return $field;

    }



  function acf_location_rules_operators( $choices ) {

    $choices = [];

    $choices['=='] = 'is equal to';

    return $choices;

  }



  function add_form_location_type( $choices ) {
    $choices['Woocommerce Account Fields']['wc_account_fields'] = 'Field Group';
    return $choices;
  }

  function form_location_rule_values( $choices ) {
    $choices['account_form'] = 'Account Form';
    return $choices;
  }

  function woocommerce_edit_my_account_page_top() {
    $reg_top = WC_Admin_Settings::get_option("woocommerce_myaccount_acf_fg_registration_top");
    if($reg_top){

      $args = [
        'field_groups' => [$reg_top],
        'form' => false,
        'return' => ''
      ];

      acf_form_head();
      acf_form( $args);
    }
  }

  function woocommerce_edit_my_account_page_bottom() {

    $conf_password = WC_Admin_Settings::get_option("woocommerce_myaccount_acf_fg_registration_confirm_password");

    if(!empty($conf_password) && $conf_password == "yes" ){

      echo woocommerce_form_field('confirm_password',  array(
        'type'        => 'password',
        'label'       => 'Confirm Password',
        'required'    => true,
        'auto-complete' => "new-password",
        'input_class' => ["woocommerce-Input", "woocommerce-Input--text"],
        'class' => ["woocommerce-form-row", "woocommerce-form-row--wide", "form-row-wide"],
        'id' => "reg_confirm_password"
      ));

    }

    $reg_bottom = WC_Admin_Settings::get_option("woocommerce_myaccount_acf_fg_registration_bottom");
    if($reg_bottom){
      $args = [
        'field_groups' => [$reg_bottom],
        'form' => false,
        'return' => ''
      ];

      acf_form_head();
      acf_form( $args);
    }
  }

  function woocommerce_edit_my_account_page_edit() {
    $account_edit = WC_Admin_Settings::get_option("woocommerce_myaccount_acf_fg_edit_account");
    if($account_edit){
      $args = [
        'field_groups' => [$account_edit],
        'form' => false
      ];

      if(is_user_logged_in()){
        $args["post_id"] = 'user_'.get_current_user_ID();
      }

      acf_form_head();
      acf_form( $args);
    }
  }



  function my_load_field( $field ) {

    // add to class
    $field['wrapper']['class'] .= ' form-row';


    // return
    return $field;

  }



  function wcpss_account_options( $settings ) {

    $location_opts = [];
    $location_opts[] = "Select a field group";
    $group_NAME = 'wc_account_fields';
    $group_query = new WP_Query( array( 'post_type' => 'acf-field-group', 's' => $group_NAME) );
    foreach($group_query->posts as $gqp){
      $location_opts[$gqp->post_name] = $gqp->post_title;
    }

    $dynamic_settings = array(
      array(
        'title' => __( 'ACF Field Groups', 'wc-acf-account-fields' ),
        'desc' => __( 'Select the locations where the field groups will be visible. Don\'t forget to set your field group to - WC Account Fields: [Field Group] == [Account Form]', 'wc-acf-account-fields' ),
        'type' => 'title',
        'class' => 'title',
        'id' => 'account_acf_field_groups'
      ),
      array(
        'title'    => __( 'Registration - Top', 'wc-acf-account-fields' ),
        'desc'     => __( 'Place a field group at the top of the registration form' ),
        'id'       => 'woocommerce_myaccount_acf_fg_registration_top',
        'type'     => 'select',
        'default'  => '',
        'desc_tip' => true,
        'options' =>  $location_opts
      ),
      array(
        'title'    => __( 'Registration - Bottom', 'wc-acf-account-fields' ),
        'desc'     => __( 'Place a field group at the bottom of the registration form' ),
        'id'       => 'woocommerce_myaccount_acf_fg_registration_bottom',
        'type'     => 'select',
        'default'  => '',
        'desc_tip' => true,
        'options' =>  $location_opts
      ),

      array(
  		'desc_tip' => __( 'Show and validate the confirm password field', 'text-domain' ),
  		'id'       => 'woocommerce_myaccount_acf_fg_registration_confirm_password',
  		'type'     => 'checkbox',
  		'css'      => 'min-width:300px;',
  		'desc'     => __( 'Enable Confirm Password Field', 'text-domain' ),
      ),

      array(
        'title'    => __( 'Edit Account', 'wc-acf-account-fields' ),
        'desc'     => __( 'Place a field group on the page - My Account: Edit Profile' ),
        'id'       => 'woocommerce_myaccount_acf_fg_edit_account',
        'type'     => 'select',
        'default'  => '',
        'desc_tip' => true,
        'options' =>  $location_opts
      ),
      array( 'type' => 'sectionend', 'id' => 'account_page_wcpss_options' ),
    );

    $settings = array_merge( $settings, $dynamic_settings );

    return $settings;
  }


  function wooc_validate_extra_register_fields( $validation_errors , $username, $email) {

    $conf_password = WC_Admin_Settings::get_option("woocommerce_myaccount_acf_fg_registration_confirm_password");
    if($conf_password){

      if(empty($_POST["confirm_password"])){
        $validation_errors->add( 'error_confirm_password_empty', __( "Invalid confirmation password", 'woocommerce' ) );
      } else if(!empty($_POST["password"]) && $_POST["password"] != $_POST["confirm_password"]){
        $validation_errors->add( 'error_confirm_password_mismatch', __( "Passwords do not match", 'woocommerce' ) );
      }
    }

    if(!acf_validate_save_post()){
      $errors = acf_get_validation_errors();
      foreach($errors as $index => $error){
        $validation_errors->add( 'error_'.$index, __( $error["message"], 'woocommerce' ) );
      }
    }
    return $validation_errors;
  }


}


new ACF_WC_ACCOUNT_FIELDS();
