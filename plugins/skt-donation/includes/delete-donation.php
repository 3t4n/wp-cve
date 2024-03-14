<?php
/*
* Create custom plugin settings menu.
*/
if ( ! function_exists ( 'skt_donation_delete_donation_list' ) ) {
  add_action('admin_menu', 'skt_donation_delete_donation_list');
  function skt_donation_delete_donation_list(){
  	//create new top-level menu 
    add_submenu_page('', '', '', 'administrator', 'sktdonationdeletelist', 'skt_donation_donation_delete_list_page');
  	//call register settings function
  	add_action( 'admin_init', 'register_skt_donation_settings' );
  }
}
if ( ! function_exists ( 'skt_donation_donation_delete_list_page' ) ) {
	function skt_donation_donation_delete_list_page() {
	global $wpdb;	
?>
<div>
<?php
$retrieved_nonce = $_REQUEST['checknounce'];
if (!wp_verify_nonce($retrieved_nonce, 'my-nonce' ) ){
	die( 'Failed security check' );
} else{
	$delete_id = isset($_GET['delete_id']) ? $_GET['delete_id'] : '';
	$tablename_donation_amount = isset($_GET['tablename']) ? $_GET['tablename'] : '';
	$mode_delete = isset($_GET['mode_delete']) ? $_GET['mode_delete'] : '';
	$get_admin_url = get_admin_url();
	if($mode_delete=="delete_category"){
		$delete_record = $wpdb->query("DELETE FROM $tablename_donation_amount WHERE id=$delete_id");
		if ($delete_record) {
			$msg_success = "Your data deleted successfully.";
			$return_to_admin_url = $get_admin_url."admin.php?page=sktdonationlist&msg_success=$msg_success";
			echo '<script>window.location = "'.$return_to_admin_url.'";</script>';
		}else{
			$msg_failed = "Your data Not deleted.";
			$return_to_admin_url = $get_admin_url."admin.php?page=sktdonationlist&msg_failed=$msg_failed";
			echo '<script>window.location = "'.$return_to_admin_url.'";</script>';
		}   
	}
}
?>
</div>
<?php } }?>