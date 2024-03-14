<?php
if( !defined('ABSPATH') ){ exit();}
add_action('wp_ajax_xyz_smap_ajax_backlink', 'xyz_smap_ajax_backlink_call');
function xyz_smap_ajax_backlink_call() {

	global $wpdb;
	if($_POST){
		if (! isset( $_POST['_wpnonce'] )|| ! wp_verify_nonce( $_POST['_wpnonce'],'backlink' ))
		 {
					echo 1;die;
		 }
		 if(current_user_can('administrator')){
		 	global $wpdb;
		 	if(isset($_POST)){
		 		if(intval($_POST['enable'])==1){
		 			update_option('xyz_credit_link','smap');
		 			echo "smap";
		 		}
		 		if(intval($_POST['enable'])==-1){
		 			update_option('xyz_smap_credit_dismiss', "hide");
		 			echo -1;
		 		}
		 	}
		 }
	}
	die();
}

add_action('wp_ajax_xyz_smap_selected_pages_auto_update', 'xyz_smap_free_selected_pages_auto_update_fn');

function xyz_smap_free_selected_pages_auto_update_fn() {
	global $wpdb;
	if(current_user_can('administrator')){
	if($_POST){
		if (! isset( $_POST['_wpnonce'] )|| ! wp_verify_nonce( $_POST['_wpnonce'],'xyz_smap_selected_pages_nonce' ))
		{
			echo 1;die;
		}
		global $wpdb;
		if(isset($_POST)){
			$pages=stripslashes($_POST['pages']);
			$smap_sec_key=$_POST['smap_secretkey'];
			$xyz_smap_fb_numericid=$_POST['xyz_fb_numericid'];
			$xyz_smap_smapsoln_userid=$_POST['smapsoln_userid'];
			update_option('xyz_smap_page_names',$pages);
			update_option('xyz_smap_af', 0);
			update_option('xyz_smap_secret_key', $smap_sec_key);
			update_option('xyz_smap_fb_numericid', $xyz_smap_fb_numericid);
			update_option('xyz_smap_smapsoln_userid', $xyz_smap_smapsoln_userid);
		}
	}
}
	die();
}
////////////////////////////////////instagram ////////////////////////////////////////////////
add_action('wp_ajax_xyz_smap_selected_ig_pages_auto_update', 'xyz_smap_free_selected_ig_pages_auto_update_fn');
function xyz_smap_free_selected_ig_pages_auto_update_fn() {
    global $wpdb;
    if(current_user_can('administrator'))
    {
        if($_POST){
            if (! isset( $_POST['_wpnonce'] )|| ! wp_verify_nonce( $_POST['_wpnonce'],'xyz_smap_selected_ig_pages_nonce' ))
            {
                echo -1;die;
            }
            if(isset($_POST)){
                    $ig_page=stripslashes($_POST['page_ig']);
                    update_option('xyz_smap_ig_page_names',$ig_page);
                $smap_sec_key=$_POST['smap_secretkey'];
                $xyz_smap_ig_numericid=$_POST['xyz_ig_numericid'];
                $xyz_smap_smapsoln_userid=$_POST['smapsoln_userid'];
                update_option('xyz_smap_secret_key_ig', $smap_sec_key);
                update_option('xyz_smap_ig_numericid', $xyz_smap_ig_numericid);
                update_option('xyz_smap_smapsoln_userid_ig', $xyz_smap_smapsoln_userid);
                update_option('xyz_smap_ig_af',0);
            }
        }
    }
    die();
}

add_action('wp_ajax_xyz_smap_xyzscripts_accinfo_auto_update', 'xyz_smap_xyzscripts_accinfo_auto_update_fn');
function xyz_smap_xyzscripts_accinfo_auto_update_fn() {
	global $wpdb;
	if(current_user_can('administrator')){
	if($_POST){
		if (! isset( $_POST['_wpnonce'] )|| ! wp_verify_nonce( $_POST['_wpnonce'],'xyz_smap_xyzscripts_accinfo_nonce' ))
		{
			echo 1;die;
		}
		global $wpdb;
		if(isset($_POST)){
			$xyzscripts_hash_val=stripslashes($_POST['xyz_user_hash']);
			$xyzscripts_user_id=$_POST['xyz_userid'];
			update_option('xyz_smap_xyzscripts_user_id', $xyzscripts_user_id);
			update_option('xyz_smap_xyzscripts_hash_val', $xyzscripts_hash_val);
		}
	}
}
	die();
}
////////////////////////////////////twitter ////////////////////////////////////////////////
add_action('wp_ajax_xyz_smap_tw_account_details_auto_update', 'xyz_smap_free_tw_account_details_auto_update_fn');
function xyz_smap_free_tw_account_details_auto_update_fn() {
    global $wpdb;
    if(current_user_can('administrator')){
        if($_POST){
            if (! isset( $_POST['_wpnonce'] )|| ! wp_verify_nonce( $_POST['_wpnonce'],'xyz_smap_tw_account_details_nonce' ))
            {
                echo -1;die;
            }
            global $wpdb;
            if(isset($_POST)){
               $smap_sec_key=$_POST['smap_secretkey'];
                $xyzscripts_user_id=$_POST['xyz_smap_xyzscripts_user_id'];
                $xyzscripts_hash_val=$_POST['xyz_smap_xyzscripts_hash_val'];
                $xyz_smap_smapsoln_userid=$_POST['smapsoln_userid'];
                $xyz_tw_username=$_POST['xyz_tw_username'];
                update_option('xyz_smap_xyzscripts_user_id', $xyzscripts_user_id);
                update_option('xyz_smap_xyzscripts_hash_val', $xyzscripts_hash_val);
                update_option('xyz_smap_secret_key_tw', $smap_sec_key);
                update_option('xyz_smap_smapsoln_userid_tw', $xyz_smap_smapsoln_userid);
                update_option('xyz_smap_tw_id', $xyz_tw_username);
            }
        }
    }
    die();
}
//////////////////////TWITTER///////////////////////////
add_action('wp_ajax_xyz_smap_del_entries', 'xyz_smap_del_entries_fn');
function xyz_smap_del_entries_fn() {
	global $wpdb;
	if(current_user_can('administrator')){
	if($_POST){
		if (! isset( $_POST['_wpnonce'] )|| ! wp_verify_nonce( $_POST['_wpnonce'],'xyz_smap_del_entries_nonce' ))
		{
			echo 1;die;
		}
		$auth_id=$_POST['auth_id'];
		$xyz_smap_xyzscripts_user_id = $_POST['xyzscripts_id'];
		$xyz_smap_xyzscripts_hash_val= $_POST['xyzscripts_user_hash'];
		$delete_entry_details=array('smap_id'=>$auth_id,
									'xyzscripts_user_id' =>$xyz_smap_xyzscripts_user_id);
		$url=XYZ_SMAP_SOLUTION_AUTH_URL.'authorize/delete-fb-auth.php';//save-selected-pages-test.php
		$content=xyz_smap_post_to_smap_api($delete_entry_details, $url,$xyz_smap_xyzscripts_hash_val);
		echo $content;
		$result=json_decode($content);$delete_flag=0;
		if(!empty($result))
		{
			if (isset($result->status))
				$delete_flag =$result->status;
		}
		if ($delete_flag===1)
		{
 			if ($auth_id==get_option('xyz_smap_smapsoln_userid')){
			update_option('xyz_smap_page_names','');
			update_option('xyz_smap_af', 1);
			update_option('xyz_smap_secret_key', '');
			update_option('xyz_smap_smapsoln_userid', 0);
			update_option('xyz_smap_fb_numericid', 0);
 			}
		}
	}
}
	die();
}
/////////////////////////////////////////LINKEDIN//////////////////////////////////////////
add_action('wp_ajax_xyz_smap_ln_selected_pages_auto_update', 'xyz_smap_free_selected_pages_auto_update_ln_fn');
function xyz_smap_free_selected_pages_auto_update_ln_fn() {
	global $wpdb;
	if(current_user_can('administrator')){
	if($_POST){
		if (! isset( $_POST['_wpnonce'] )|| ! wp_verify_nonce( $_POST['_wpnonce'],'xyz_smap_ln_selected_pages_nonce' ))
		{
			echo 1;die;
		}
		if(isset($_POST)){
			$pages=array();$ln_total_pages0='';
			$xyz_smap_ln_share_post_profile=0;
			if (isset($_POST['pages']))
				$pages=$_POST['pages'];
				if (array_key_exists('-1', $pages)){
					$xyz_smap_ln_share_post_profile=1;
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
			$smap_sec_key=$_POST['smap_secretkey'];
			$xyz_smap_smapsoln_userid=$_POST['smapsoln_userid'];
			update_option('xyz_smap_ln_page_names',$ln_total_pages0);
			update_option('xyz_smap_lnaf', 0);
			update_option('xyz_smap_secret_key_ln', $smap_sec_key);
			update_option('xyz_smap_lnshare_to_profile', $xyz_smap_ln_share_post_profile);
			update_option('xyz_smap_smapsoln_userid_ln', $xyz_smap_smapsoln_userid);
			update_option('xyz_smap_ln_company_ids', $page_id);
			update_option('xyz_smap_lnappscoped_userid', $_POST['xyz_ln_user_id']);
		}
	}
}
	die();
}
add_action('wp_ajax_xyz_smap_del_ln_entries', 'xyz_smap_del_entries_ln_fn');
function xyz_smap_del_entries_ln_fn() {
	global $wpdb;
	if(current_user_can('administrator')){
	if($_POST){
		if (! isset( $_POST['_wpnonce'] )|| ! wp_verify_nonce( $_POST['_wpnonce'],'xyz_smap_del_entries_ln_nonce' ))
		{
			echo 1;die;
		}
		$ln_auth_id=$_POST['ln_auth_id'];
		$xyz_smap_xyzscripts_user_id = $_POST['xyzscripts_id'];
		$xyz_smap_xyzscripts_hash_val= $_POST['xyzscripts_user_hash'];
		$delete_entry_details=array('smap_ln_auth_id'=>$ln_auth_id,
				'xyzscripts_user_id' =>$xyz_smap_xyzscripts_user_id);
		$url=XYZ_SMAP_SOLUTION_AUTH_URL.'authorize_linkedIn/delete-ln-auth.php';//save-selected-pages-test.php
		$content=xyz_smap_post_to_smap_api($delete_entry_details, $url,$xyz_smap_xyzscripts_hash_val);
		echo $content;
		$result=json_decode($content);$delete_flag=0;
		if(!empty($result))
		{
			if (isset($result->status))
				$delete_flag =$result->status;
		}
		if ($delete_flag===1)
		{
			if ($ln_auth_id==get_option('xyz_smap_smapsoln_userid_ln')){
				update_option('xyz_smap_ln_company_ids','');
				update_option('xyz_smap_lnaf', 1);
				update_option('xyz_smap_secret_key_ln', '');
				update_option('xyz_smap_smapsoln_userid_ln', 0);
				update_option('xyz_smap_ln_page_names', '');
				update_option('xyz_smap_lnappscoped_userid', '');
			}
			}
		}
	}
	die();
}
add_action('wp_ajax_xyz_smap_del_tw_entries', 'xyz_smap_del_entries_tw_fn');
function xyz_smap_del_entries_tw_fn() {
    global $wpdb;
    if(current_user_can('administrator')){
        if($_POST){
            if (! isset( $_POST['_wpnonce'] )|| ! wp_verify_nonce( $_POST['_wpnonce'],'xyz_smap_del_entries_tw_nonce' ))
            {
                echo 1;die;
            }
            $tw_auth_id=$_POST['tw_auth_id'];
            $xyz_smap_xyzscripts_user_id = $_POST['xyzscripts_id'];
            $xyz_smap_xyzscripts_hash_val= $_POST['xyzscripts_user_hash'];
            $delete_entry_details=array('smap_tw_auth_id'=>$tw_auth_id,
                'xyzscripts_user_id' =>$xyz_smap_xyzscripts_user_id);
            $url=XYZ_SMAP_SOLUTION_AUTH_URL.'authorize-twitter/delete-tw-auth.php';//save-selected-pages-test.php
            $content=xyz_smap_post_to_smap_api($delete_entry_details, $url,$xyz_smap_xyzscripts_hash_val);
            echo $content;
            $result=json_decode($content);$delete_flag=0;
            if(!empty($result))
            {
                if (isset($result->status))
                    $delete_flag =$result->status;
            }
            if ($delete_flag===1)
            {
                if ($tw_auth_id==get_option('xyz_smap_smapsoln_userid_tw')){
                    update_option('xyz_smap_twaf', 1);
                    update_option('xyz_smap_secret_key_tw', '');
                    update_option('xyz_smap_smapsoln_userid_tw', 0);
                    update_option('xyz_smap_tw_id', '');
                }
            }
        }
    }
    die();
}
add_action('wp_ajax_xyz_smap_del_ig_entries', 'xyz_smap_del_entries_ig_fn');
function xyz_smap_del_entries_ig_fn() {
    global $wpdb;
    if(current_user_can('administrator')){
        if($_POST){
            if (! isset( $_POST['_wpnonce'] )|| ! wp_verify_nonce( $_POST['_wpnonce'],'xyz_smap_del_entries_ig_nonce' ))
            {
                echo 1;die;
            }
            $ig_auth_id=$_POST['ig_auth_id'];
            $xyz_smap_xyzscripts_user_id = $_POST['xyzscripts_id'];
            $xyz_smap_xyzscripts_hash_val= $_POST['xyzscripts_user_hash'];
            $delete_entry_details=array('smap_id'=>$ig_auth_id,
                'xyzscripts_user_id' =>$xyz_smap_xyzscripts_user_id);
            $url=XYZ_SMAP_SOLUTION_AUTH_URL.'authorize-instagram/delete-ig-auth.php';//save-selected-pages-test.php
            $content=xyz_smap_post_to_smap_api($delete_entry_details, $url,$xyz_smap_xyzscripts_hash_val);
            echo $content;
            $result=json_decode($content);$delete_flag=0;
            if(!empty($result))
            {
                if (isset($result->status))
                    $delete_flag =$result->status;
            }
            if ($delete_flag===1)
            {
                if ($ig_auth_id==get_option('xyz_smap_smapsoln_userid_ig')){
                    update_option('xyz_smap_igaf', 1);
                    update_option('xyz_smap_secret_key_ig', '');
                    update_option('xyz_smap_smapsoln_userid_ig', 0);
                    update_option('xyz_smap_ig_page_names','');//
                    update_option('xyz_smap_ig_numericid','');
                    update_option('xyz_smap_ig_af',1);
                }
            }
        }
    }
    die();
}

add_action('wp_ajax_xyz_smap_del_fb_entries', 'xyz_smap_del_fb_entries_fn');
function xyz_smap_del_fb_entries_fn() {
	global $wpdb;
	if(current_user_can('administrator')){
	if($_POST){
		if (! isset( $_POST['_wpnonce'] )|| ! wp_verify_nonce( $_POST['_wpnonce'],'xyz_smap_del_fb_entries_nonce' ))
		{
			echo 1;die;
		}
		$fb_userid=$_POST['fb_userid'];
		$xyz_smap_xyzscripts_user_id = $_POST['xyzscripts_id'];
		$xyz_smap_xyzscripts_hash_val= $_POST['xyzscripts_user_hash'];
		$tr_iterationid=$_POST['tr_iterationid'];
		$delete_entry_details=array('fb_userid'=>$fb_userid,
				'xyzscripts_user_id' =>$xyz_smap_xyzscripts_user_id);
		$url=XYZ_SMAP_SOLUTION_AUTH_URL.'authorize/delete-fb-auth.php';// save-selected-pages-test.php
		$content=xyz_smap_post_to_smap_api($delete_entry_details, $url,$xyz_smap_xyzscripts_hash_val);
		echo $content;
	}
}
	die();
}
add_action('wp_ajax_xyz_smap_del_lnuser_entries', 'xyz_smap_del_lnuser_entries_fn');
function xyz_smap_del_lnuser_entries_fn() {
	global $wpdb;
	if(current_user_can('administrator')){
	if($_POST){
		if (! isset( $_POST['_wpnonce'] )|| ! wp_verify_nonce( $_POST['_wpnonce'],'xyz_smap_del_lnuser_entries_nonce' ))
		{
			echo 1;die;
		}
		$ln_userid=$_POST['ln_userid'];
		$xyz_smap_xyzscripts_user_id = $_POST['xyzscripts_id'];
		$xyz_smap_xyzscripts_hash_val= $_POST['xyzscripts_user_hash'];
// 		$tr_iterationid=$_POST['tr_iterationid'];
		$delete_entry_details=array('ln_userid'=>$ln_userid,
				'xyzscripts_user_id' =>$xyz_smap_xyzscripts_user_id);
		$url=XYZ_SMAP_SOLUTION_AUTH_URL.'authorize_linkedIn/delete-ln-auth.php';//delete-fb-auth.php save-selected-pages-test.php
		$content=xyz_smap_post_to_smap_api($delete_entry_details, $url,$xyz_smap_xyzscripts_hash_val);
		echo $content;
	}
}
	die();
}
add_action('wp_ajax_xyz_smap_del_twuser_entries', 'xyz_smap_del_twuser_entries_fn');
function xyz_smap_del_twuser_entries_fn() {
    global $wpdb;
    if(current_user_can('administrator')){
        if($_POST){
            if (! isset( $_POST['_wpnonce'] )|| ! wp_verify_nonce( $_POST['_wpnonce'],'xyz_smap_del_twuser_entries_nonce' ))
            {
                echo 1;die;
            }
            $inactive_tw_userid=$_POST['inactive_tw_userid'];
            $xyz_smap_xyzscripts_user_id = $_POST['xyzscripts_id'];
            $xyz_smap_xyzscripts_hash_val= $_POST['xyzscripts_user_hash'];
            $delete_entry_details=array('inactive_tw_userid'=>$inactive_tw_userid,
                'xyzscripts_user_id' =>$xyz_smap_xyzscripts_user_id);
            $url=XYZ_SMAP_SOLUTION_AUTH_URL.'authorize-twitter/delete-tw-auth.php';
            $content=xyz_smap_post_to_smap_api($delete_entry_details, $url,$xyz_smap_xyzscripts_hash_val);
            echo $content;
        }
    }
    die();
}
add_action('wp_ajax_xyz_smap_del_iguser_entries', 'xyz_smap_del_iguser_entries_fn');
function xyz_smap_del_iguser_entries_fn() {
    global $wpdb;
    if(current_user_can('administrator')){
        if($_POST){
            if (! isset( $_POST['_wpnonce'] )|| ! wp_verify_nonce( $_POST['_wpnonce'],'xyz_smap_del_iguser_entries_nonce' ))
            {
                echo 1;die;
            }
            $inactive_ig_userid=$_POST['inactive_ig_userid'];
            $xyz_smap_xyzscripts_user_id = $_POST['xyzscripts_id'];
            $xyz_smap_xyzscripts_hash_val= $_POST['xyzscripts_user_hash'];
            $delete_entry_details=array('inactive_ig_userid'=>$inactive_ig_userid,
                'xyzscripts_user_id' =>$xyz_smap_xyzscripts_user_id);
            $url=XYZ_SMAP_SOLUTION_AUTH_URL.'authorize-instagram/delete-ig-auth.php';
            $content=xyz_smap_post_to_smap_api($delete_entry_details, $url,$xyz_smap_xyzscripts_hash_val);
            echo $content;
        }
    }
    die();
}

////////////////////////////////////////////////////////////////////////////////////////////
