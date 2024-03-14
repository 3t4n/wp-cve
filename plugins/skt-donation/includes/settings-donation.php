<?php
/*
* Create custom plugin settings menu.
*/
if ( ! function_exists ( 'skt_donation_create_donation_list' ) ) {
  add_action('admin_menu', 'skt_donation_create_donation_list');
  function skt_donation_create_donation_list(){
  	//create new top-level menu 
    add_submenu_page('skt-donations-settings', 'Donation List','Donations List', 'administrator', 'sktdonationlist', 'skt_donation_donation_list_page');
  	//call register settings function
  	add_action( 'admin_init', 'register_skt_donation_settings' );
  }
}
if ( ! function_exists ( 'skt_donation_donation_list_page' ) ) {
  function skt_donation_donation_list_page() { 
  	$msg_success = isset($_GET['msg_success']) ? $_GET['msg_success'] : '';
  	if($msg_success !=''){
        $skt_donation_active_tab = isset($_GET['skt_donation_active_tab']) ? $_GET['skt_donation_active_tab'] : '';
        $msg_success = $_GET['msg_success'];
        ?>
        <p class="skt_donation_msg_success"><?php echo esc_attr($msg_success);?></p>
        <?php } 
        $msg_failed = isset($_GET['msg_failed']) ? $_GET['msg_failed'] : '';
       if($msg_failed !=''){
        $skt_donation_active_tab = isset($_GET['skt_donation_active_tab']) ? $_GET['skt_donation_active_tab'] : '';
        $msg_failed = $_GET['msg_failed'];
      ?>
        <p class="skt_donation_msg_failed"><?php echo esc_attr($msg_failed);?></p>
      <?php } 
  	include_once( SKT_DONATIONS_DIR . '/includes/general-options-tab.php' );
  }
}
?>