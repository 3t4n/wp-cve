<?php
if( !defined('ABSPATH') ){ exit();}
add_action('wp_ajax_xyz_lnap_ajax_backlink', 'xyz_lnap_ajax_backlink_call');

function xyz_lnap_ajax_backlink_call() {


	global $wpdb;
	if(current_user_can('administrator')){
	if($_POST){
		if (! isset( $_POST['_wpnonce'] )
				|| ! wp_verify_nonce( $_POST['_wpnonce'],'backlink' )
				) {
					echo 1;die;
		 }
		 if(current_user_can('administrator')){
		 	global $wpdb;
		 	if(isset($_POST)){
		 		if(intval($_POST['enable'])==1){
		 			update_option('xyz_credit_link','lnap');
		 			echo "lnap";
		 		}
		 		if(intval($_POST['enable'])==-1){
		 			update_option('xyz_lnap_credit_dismiss', "hide");
		 			echo -1;
		 		}
		 	}
		 }
	}
  }
	die();
}
//////////////////////////////////////////new/////////////////////////////////////////
add_action('wp_ajax_xyz_lnap_selected_pages_auto_update', 'xyz_lnap_selected_pages_auto_update_fn');
function xyz_lnap_selected_pages_auto_update_fn() {
	global $wpdb;
	if(current_user_can('administrator')){
	if($_POST){
		if (! isset( $_POST['_wpnonce'] )|| ! wp_verify_nonce( $_POST['_wpnonce'],'xyz_lnap_selected_pages_nonce' ))
		{
			echo 1;die;
		}
		if(isset($_POST)){
			$xyz_lnap_ln_share_post_profile=0;
			if (isset($_POST['pages']))
				$pages=$_POST['pages'];
				if (array_key_exists('-1', $pages)){
					$xyz_lnap_ln_share_post_profile=1;
					unset($pages['-1']);
				}
				if(!empty($pages)){
					$ln_total_pages0=base64_encode(serialize($pages));
					foreach ($pages as $sel_pageid=>$sel_pagename)
					{
						if ($sel_pageid!='-1'){
							$page_id[]=$sel_pageid;
						}
					}
					$page_id=implode(',', $page_id);
				}
			$lnap_sec_key=$_POST['smap_secretkey'];
			$xyz_lnap_lnappscoped_userid=$_POST['xyz_ln_user_id'];//xyz_lnap_lnappscoped_userid
			$xyz_lnap_smapsoln_userid=$_POST['smapsoln_userid'];
			update_option('xyz_lnap_page_names',$ln_total_pages0);
			update_option('xyz_lnap_lnaf', 0);
			update_option('xyz_lnap_secret_key', $lnap_sec_key);
			update_option('xyz_lnap_lnappscoped_userid' , $xyz_lnap_lnappscoped_userid);
			update_option('xyz_lnap_smapsoln_userid', $xyz_lnap_smapsoln_userid);
			update_option('xyz_lnap_lnshare_to_profile', $xyz_lnap_ln_share_post_profile);
			update_option('xyz_lnap_ln_share_post_company', $page_id);
		}
	}
}
	die();
}
add_action('wp_ajax_xyz_lnap_xyzscripts_accinfo_auto_update', 'xyz_lnap_xyzscripts_accinfo_auto_update_fn');
function xyz_lnap_xyzscripts_accinfo_auto_update_fn() {
	global $wpdb;
	if(current_user_can('administrator')){
	if($_POST){
		if (! isset( $_POST['_wpnonce'] )|| ! wp_verify_nonce( $_POST['_wpnonce'],'xyz_lnap_xyzscripts_accinfo_nonce' ))
		{
			echo 1;die;
		}
		if(isset($_POST)){
			$xyzscripts_hash_val=stripslashes($_POST['xyz_user_hash']);
			$xyzscripts_user_id=$_POST['xyz_userid'];
			update_option('xyz_lnap_xyzscripts_user_id', $xyzscripts_user_id);
			update_option('xyz_lnap_xyzscripts_hash_val', $xyzscripts_hash_val);
		}
	}
}
	die();
}
add_action('wp_ajax_xyz_lnap_del_entries', 'xyz_lnap_del_entries_fn');
function xyz_lnap_del_entries_fn() {
	global $wpdb;
	if(current_user_can('administrator')){
	if($_POST){
		if (! isset( $_POST['_wpnonce'] )|| ! wp_verify_nonce( $_POST['_wpnonce'],'xyz_lnap_del_entries_nonce' ))
		{
			echo 1;die;
		}
		$auth_id=$_POST['auth_id'];
		$xyz_lnap_xyzscripts_user_id = $_POST['xyzscripts_id'];
		$xyz_lnap_xyzscripts_hash_val= $_POST['xyzscripts_user_hash'];
		$delete_entry_details=array('smap_ln_auth_id'=>$auth_id,
				'xyzscripts_user_id' =>$xyz_lnap_xyzscripts_user_id,
		);
		$url=XYZ_SMAP_SOLUTION_AUTH_URL.'authorize_linkedIn/delete-ln-auth.php';
		$content=xyz_lnap_post_to_smap_api($delete_entry_details, $url,$xyz_lnap_xyzscripts_hash_val);
		echo $content;
		$result=json_decode($content);$delete_flag=0;
		if(!empty($result))
		{
			if (isset($result->status))
				$delete_flag =$result->status;
		}
		if ($delete_flag===1)
		{
			if ($auth_id==get_option('xyz_lnap_smapsoln_userid'))
			{
				update_option('xyz_lnap_ln_share_post_company','');
				update_option('xyz_lnap_lnaf', 1);
				update_option('xyz_lnap_secret_key', '');
				update_option('xyz_lnap_smapsoln_userid', 0);
				update_option('xyz_lnap_lnappscoped_userid' , 0);
				update_option('xyz_lnap_page_names','');
			}
			}
		}
	}
	die();
}
//////////////////////////////////////////////////////////////////////////////////////////
add_action('wp_ajax_xyz_lnap_del_lnuser_entries', 'xyz_lnap_del_lnuser_entries_fn');
function xyz_lnap_del_lnuser_entries_fn() {
	global $wpdb;
	if(current_user_can('administrator')){
	if($_POST){
		if (! isset( $_POST['_wpnonce'] )|| ! wp_verify_nonce( $_POST['_wpnonce'],'xyz_lnap_del_lnuser_entries_nonce' ))
		{
			echo 1;die;
		}
		$ln_userid=$_POST['ln_userid'];
		$xyz_lnap_xyzscripts_user_id = $_POST['xyzscripts_id'];
		$xyz_lnap_xyzscripts_hash_val= $_POST['xyzscripts_user_hash'];
		$tr_iterationid=$_POST['tr_iterationid'];
		$delete_entry_details=array('ln_userid'=>$ln_userid,
				'xyzscripts_user_id' =>$xyz_lnap_xyzscripts_user_id);
		$url=XYZ_SMAP_SOLUTION_AUTH_URL.'authorize_linkedIn/delete-ln-auth.php';
		$content=xyz_lnap_post_to_smap_api($delete_entry_details, $url,$xyz_lnap_xyzscripts_hash_val);
		echo $content;
	}
}
	die();
}
///////////////////////////////////////////////////////////////////////////////////////////
?>