<?php
/*
* Create custom plugin settings menu.
*/
if ( ! function_exists ( 'skt_donation_create_menu' ) ) {
  add_action('admin_menu', 'skt_donation_create_menu');
  function skt_donation_create_menu(){
  	//create new top-level menu 
    add_menu_page('SKT Donation Settings', 'SKT Donation', 'administrator', 'skt-donations-settings', 'skt_donation_settings_page' ,'dashicons-universal-access-alt');
  	//call register settings function
  	add_action( 'admin_init', 'register_skt_donation_settings' );
  }
}
include_once( SKT_DONATIONS_DIR . '/includes/register-settings.php' );
if ( ! function_exists ( 'skt_donation_settings_page' ) ) {
  function skt_donation_settings_page() {
  ?>
  <style type="text/css">
  .skt-donations-form select {-moz-appearance:none; 
    background-image:url(<?php echo esc_url(SKT_DONATIONS_URI.'/img/down-arrow.png');?>); background-position:center right; 
    background-repeat:no-repeat; padding:5px 10px !important;}
    #skt_donation_myTable_length select {border: none; -webkit-border-radius: 3px; border-radius: 3px; height:auto; width:12%; -webkit-appearance:none; -moz-appearance:none; appearance:none; position:relative;
   background-image:url(<?php echo esc_url(SKT_DONATIONS_URI.'/img/down-arrow.png');?>); background-position:center right; background-repeat:no-repeat; color: #333; background-color:#f1f1f1; box-shadow:none; margin:0 8px;}
    .skt-donations-tabs li label {background: <?php echo esc_attr( get_option('skt_donation_admin_menu_backgroundcolor') ); ?>;color: #fff;display: block; font-weight: 600;padding: 10px 15px; cursor: pointer;}
  .skt-donations-settings .button.button-primary {background-color: <?php echo esc_attr( get_option('skt_donation_admin_backgroundcolor') ); ?>;border: none;text-shadow: none;padding: 9px 15px; height: auto;font-weight: 600;box-shadow: none; line-height: normal;border-radius: 0;font-size: 14px;}
  .skt-donations-settings .button.button-primary:active { transform: none;}
  .skt-donations-settings .button.button-primary:hover {background-color: <?php echo esc_attr( get_option('skt_donation_admin_hover_backgroundcolor') ); ?>;}
    .skt-inline-items li input[type=radio]:checked + img {box-shadow: 0 0 0px 3px <?php echo esc_attr( get_option('skt_donation_admin_hover_backgroundcolor') ); ?>;}
    .skt-donations-form-column.delete a {background: <?php echo esc_attr( get_option('skt_donation_admin_backgroundcolor') ); ?>; color: #fff; padding:8px 15px; border-radius: 5px; text-decoration: none;}
  .skt-donations-form-column.delete a:hover { background:<?php echo esc_attr( get_option('skt_donation_admin_hover_backgroundcolor') ); ?>; cursor:pointer;}
  .skt-donation-accordion-tab { display: block; background-color:<?php echo esc_attr( get_option('skt_donation_admin_backgroundcolor') ); ?>; margin-bottom:3px; background-image:url(<?php echo esc_url(SKT_DONATIONS_URI.'/img/down-arrow.png');?>); background-position:center right; background-repeat:no-repeat}
  .skt-donation-accordion-tab:hover { background:<?php echo esc_attr( get_option('skt_donation_admin_hover_backgroundcolor') ); ?>; background-image:url(<?php echo esc_url(SKT_DONATIONS_URI.'/img/down-arrow-white.png');?>); background-position:center right; background-repeat:no-repeat}
  #skt_donation_myTable_paginate a:hover, #skt_donation_myTable_paginate a.current { background:<?php echo esc_attr( get_option('skt_donation_admin_page_backgroundcolor') ); ?>; color:#fff;}
  :checked ~ .skt-donation-accordion-title { background: <?php echo esc_attr( get_option('skt_donation_admin_page_backgroundcolor') ); ?>; color:#fff; background-image:url(<?php echo esc_url(SKT_DONATIONS_URI.'/img/up-arrow.png');?>); background-position:center right; background-repeat:no-repeat}
  </style>
  <?php
    global $wpdb;
      $msg_success = isset($_GET['msg_success']) ? $_GET['msg_success'] : '';
      if($msg_success !=''){
        $skt_donation_active_tab = isset($_GET['skt_donation_active_tab']) ? $_GET['skt_donation_active_tab'] : '';
        ?>
        <p class="skt_donation_msg_success"><?php echo esc_attr($msg_success);?></p>
        <?php } 

        $msg_failed = isset($_GET['msg_failed']) ? $_GET['msg_failed'] : '';
       if($msg_failed !=''){
        echo $skt_donation_active_tab = isset($_GET['skt_donation_active_tab']) ? $_GET['skt_donation_active_tab'] : '';
      ?>
        <p class="skt_donation_msg_failed"><?php echo esc_attr($msg_failed);?></p>
      <?php } 
      $installation_plugin_date = esc_attr(get_option('skt_donation_installation_date')); 
      $mesg_one = date('d-m-Y', strtotime($installation_plugin_date .'+362 day'));
      $mesg_two = date('d-m-Y', strtotime($installation_plugin_date .'+363 day'));
      $mesg_three = date('d-m-Y', strtotime($installation_plugin_date .'+364 day'));
      $current_date = date('d-m-Y');
    ?>
    <div class="wrap">
      <?php
        if ($mesg_one==$current_date || $mesg_two==$current_date || $mesg_three==$current_date ) {
      ?>
      <div class="skt_donation_session_expired">
        <h3><?php esc_attr_e('Your session will expire!','skt-donation');?></h3>
      </div>
      <?php } ?>
      <h1><?php esc_attr_e('SKT Donation - Settings','skt-donation');?></h1>
      <div class="skt-donations-settings">
        <div class="preloader"></div>
        <form method="post" action="<?php echo esc_url('options.php');?>">
          <?php settings_fields( 'skt-donations-settings-group' ); ?>
          <?php do_settings_sections( 'skt-donations-settings-group' ); ?>
          <ul class="skt-donations-tabs">
            <li class="skt-donations-tab-link <?php if ( esc_attr(get_option('skt_donation_active_tab') == 'tab2' ) ) { ?> skt-donations-current <?php } ?>" data-tab="skt-donations-tab-2">
              <label><?php esc_attr_e('Payment Gateway','skt-donation');?>
                <input type="radio" name="skt_donation_active_tab" value="tab2" <?php if ( esc_attr(get_option('skt_donation_active_tab') == 'tab2' ) ) { ?> checked <?php } ?>>
              </label>
            </li>
            <li class="skt-donations-tab-link <?php if ( esc_attr(get_option('skt_donation_active_tab') == 'tab4' ) ) { ?> skt-donations-current <?php } ?>" data-tab="skt-donations-tab-4">
              <label><?php esc_attr_e('Form Fields','skt-donation');?>
                <input type="radio" name="skt_donation_active_tab" value="tab4" <?php if ( esc_attr(get_option('skt_donation_active_tab') == 'tab4' ) ) { ?> checked <?php } ?>>
              </label>
            </li>
            <li class="skt-donations-tab-link <?php if ( esc_attr(get_option('skt_donation_active_tab') == 'tab5' ) ) { ?> skt-donations-current <?php } ?>" data-tab="skt-donations-tab-5">
              <label><?php esc_attr_e('Admin Colors Settings','skt-donation');?>
                <input type="radio" name="skt_donation_active_tab" value="tab5" <?php if ( esc_attr(get_option('skt_donation_active_tab') == 'tab5' ) ) { ?> checked <?php } ?>>
              </label>
            </li>
            <li class="skt-donations-tab-link <?php if ( esc_attr(get_option('skt_donation_active_tab') == 'tab6' ) ) { ?> skt-donations-current <?php } ?>" data-tab="skt-donations-tab-6">
              <label><?php esc_attr_e('Form Colors Settings','skt-donation');?>
                <input type="radio" name="skt_donation_active_tab" value="tab6" <?php if ( esc_attr(get_option('skt_donation_active_tab') == 'tab6' ) ) { ?> checked <?php } ?>>
              </label>
            </li>
            <li class="skt-donations-tab-link <?php if ( esc_attr(get_option('skt_donation_active_tab') == 'tab7' ) ) { ?> skt-donations-current <?php } ?>" data-tab="skt-donations-tab-7">
              <label><?php esc_attr_e('Shortcode','skt-donation');?>
                <input type="radio" name="skt_donation_active_tab" value="tab7" <?php if ( esc_attr(get_option('skt_donation_active_tab') == 'tab7' ) ) { ?> checked <?php } ?>>
              </label>
            </li>
            <li class="skt-donations-tab-link <?php if ( esc_attr(get_option('skt_donation_active_tab') == 'tab8' ) ) { ?> skt-donations-current <?php } ?>" data-tab="skt-donations-tab-8">
              <label><?php esc_attr_e('Manage Email Setting','skt-donation');?>
                <input type="radio" name="skt_donation_active_tab" value="tab8" <?php if ( esc_attr(get_option('skt_donation_active_tab') == 'tab8' ) ) { ?> checked <?php } ?>>
              </label>
            </li>
          </ul>
          <?php 
            include_once( SKT_DONATIONS_DIR . '/includes/color-options-tab.php' ); 
            include_once( SKT_DONATIONS_DIR . '/includes/shortcodes-tab.php' ); 
            include_once( SKT_DONATIONS_DIR . '/includes/admin_background.php' );
            include_once( SKT_DONATIONS_DIR . '/includes/frondend_background.php' );
            include_once( SKT_DONATIONS_DIR . '/includes/get_shortcode.php' ); ?>
          <div class="skt-donations-settings-footer">
            <?php echo esc_attr(submit_button()); ?>
          </div>
        </form>
      </div>
    </div>
    <?php
  } 
}
?>