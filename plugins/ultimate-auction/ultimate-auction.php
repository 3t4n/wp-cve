<?php

/*
  Plugin Name: Ultimate Wordpress Auction Plugin
  Plugin URI: https://auctionplugin.net
  Description: Awesome plugin to host auctions on your wordpress site and sell anything you want.
  Author: Nitesh Singh
  Author URI: https://auctionplugin.net
  Version: 4.2.3
  Text Domain: wdm-ultimate-auction
  License: GPLv2
  Copyright 2023 Nitesh Singh
*/

load_plugin_textdomain('wdm-ultimate-auction', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

require_once('settings-page.php');
require_once('auction-shortcode.php');
require_once('send-auction-email.php');

//create a table for auction bidders on plugin activation
register_activation_hook(__FILE__,'wdm_create_bidders_table');


function wdm_create_bidders_table(){

   require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   global $wpdb;
   
   $data_table = $wpdb->prefix."wdm_bidders";
   $sql = "CREATE TABLE IF NOT EXISTS $data_table
  (
	   id MEDIUMINT(9) NOT NULL AUTO_INCREMENT,
	   name VARCHAR(45),
	   email VARCHAR(45),
	   auction_id BIGINT(20),
	   bid DECIMAL(10,2),
	   date datetime,
	   PRIMARY KEY (id)
  );";
  
   dbDelta($sql);
   
   //for old table (till 'Wordpress Auction Plugin' version 1.0.2) which had 'bid' column as integer(MEDIUMINT)
   /* $alt_sql = "ALTER TABLE $data_table MODIFY bid DECIMAL(10,2);";
   $wpdb->query($alt_sql);
   
   //for old table which had 'bid' column without index
   $alt_sql = "ALTER TABLE $data_table ADD INDEX (bid);";
   $wpdb->query($alt_sql);*/

   	/*$indx = $wpdb->get_results("SHOW indexes FROM $data_table WHERE Column_name = 'bid';");*/

	$columnname = "bid";
	$show_qry = $wpdb->prepare("SHOW indexes FROM {$data_table} WHERE 
		Column_name = %s", $columnname);

   	$indx = $wpdb->get_results($show_qry);
    for ($i=2; $i <= count($indx); $i++) {
        $alt_sql = "ALTER TABLE $data_table DROP INDEX bid_".$i.';';
        $wpdb->query($alt_sql);
    }
}

//create feed page along with shortcode on plugin activation
register_activation_hook(__FILE__, 'wdm_create_shortcode_pages');

function wdm_create_shortcode_pages(){
   
   $option = 'ua_page_exists';
   $default = array();
   $default = get_option($option);
   
    if(!isset($default['listing'])){
        
        $feed_page = array(
            'post_type' => 'page',
            'post_title' => __("Auctions", "wdm-ultimate-auction"),
            'post_status' => 'publish',
            'post_content' => '[wdm_auction_listing]'
            );
	
        $id = wp_insert_post($feed_page);
	
		if(!empty($id)){
			$default['listing'] = $id;
			update_option( $option, $default );
		}
    }
}

//send email Ajax callback - An automatic activity once an auction has expired
function send_auction_email_callback() {
    //require_once('email-template.php');
	$mail_sent = get_post_meta($_POST['auc_id'], "wdm_won_email_sent", true);
	
	if ( $mail_sent !='yes' ) {  
    $sent_email = ultimate_auction_email_template(
      esc_attr($_POST['auc_title']), 
      esc_attr($_POST['auc_id']), 
      esc_attr($_POST['auc_cont']), 
      esc_attr($_POST['auc_bid']), 
      esc_attr($_POST['auc_email']), 
      esc_url($_POST['auc_url'])
    );
	if($sent_email){
		 update_post_meta(esc_attr($_POST['auc_id']), 'wdm_won_email_sent', 'yes');
	}
    if(!$sent_email)
        update_post_meta(esc_attr($_POST['auc_id']), 'wdm_to_be_sent', '');
    } 
    die();
}

add_action('wp_ajax_send_auction_email', 'send_auction_email_callback');
add_action('wp_ajax_nopriv_send_auction_email', 'send_auction_email_callback');

//resend email Ajax callback - 'Resend' link on 'Manage Auctions' page
function resend_auction_email_callback()
{
    //require_once('email-template.php');
    
    $res_email = ultimate_auction_email_template(
      esc_attr($_POST['a_title']), 
      esc_attr($_POST['a_id']), 
      esc_attr($_POST['a_cont']), 
      esc_attr($_POST['a_bid']), 
      esc_attr($_POST['a_em']), 
      esc_url($_POST['a_url'])
    );
    
    if($res_email)
        _e("Email sent successfully.", "wdm-ultimate-auction");
    else
        _e("Sorry, the email could not sent.", "wdm-ultimate-auction");
    
    die();
}

add_action('wp_ajax_resend_auction_email', 'resend_auction_email_callback');
add_action('wp_ajax_nopriv_resend_auction_email', 'resend_auction_email_callback');

//delete auction Ajax callback - 'Delete' link on 'Manage Auctions' page
function delete_auction_callback(){
    global $wpdb;
	
	if(wp_verify_nonce($_POST['uwaajax_nonce'], 'uwaajax_nonce')){

		/*$delete_auction_array = $wpdb->get_col($wpdb->prepare("SELECT meta_value from $wpdb->postmeta WHERE meta_key LIKE %s AND 
			post_id = %d", '%wdm-image-%', $delete_post_id));*/
   
		$table_postmeta = $wpdb->postmeta;
		$delete_post_id = $_POST["del_id"];		
		$pre_qry = $wpdb->prepare("SELECT meta_value FROM {$table_postmeta} WHERE meta_key LIKE %s AND post_id = %d", '%wdm-image-%', $delete_post_id);
		$delete_auction_array = $wpdb->get_col($pre_qry);
		
		if($_POST["force_del"] === 'yes')
			$force = true;
		else
			$force = false;
	   
		if(current_user_can('delete_posts')) {
			$del_auc = wp_delete_post(esc_attr($_POST["del_id"]), false);
			/*$wpdb->query( $wpdb->prepare("DELETE FROM ".$wpdb->prefix."wdm_bidders WHERE auction_id = %d", esc_attr($_POST["del_id"])));*/

			$table = $wpdb->prefix."wdm_bidders";
			$delid = $_POST["del_id"];			
			$n_pre_qry =  $wpdb->prepare("DELETE FROM {$table} WHERE 
				auction_id = %d", $delid);
			$wpdb->query($n_pre_qry);
		}
		
		if($del_auc){	
			foreach($delete_auction_array as $delete_image_url){
				if(!empty($delete_image_url) && $delete_image_url !== null) {
					/*$auction_url_post_id = $wpdb->get_var("SELECT ID from $wpdb->posts WHERE guid = '$delete_image_url' AND 
						post_type = ");*/

					$table_posts = $wpdb->posts;
					$n2_pre_qry = $wpdb->prepare("SELECT ID FROM {$table_posts} WHERE 
						guid = %s AND post_type = %s", $delete_image_url, 'attachment');

					$auction_url_post_id = $wpdb->get_var($n2_pre_qry);
					wp_delete_post($auction_url_post_id, true); //also delete images attached
				}
			}
			printf(__("Auction %s and its attachments are deleted successfully.", "wdm-ultimate-auction"), esc_attr($_POST['auc_title']));
		}
		else
			_e("Sorry, this auction cannot be deleted.", "wdm-ultimate-auction");
		
	} /* end of if */
    die();
}

add_action('wp_ajax_delete_auction', 'delete_auction_callback');
add_action('wp_ajax_nopriv_delete_auction', 'delete_auction_callback');

//multiple delete auction Ajax callback
function multi_delete_auction_callback(){
	global $wpdb;
	
	if(wp_verify_nonce($_POST['uwaajax_nonce'], 'uwaajax_nonce')){
   
		if($_POST["force_del"] === 'yes')
			$force = true;
		else
			$force = false;

		$all_aucs = explode(',', esc_attr($_POST['del_ids']));
	   
		foreach($all_aucs as $aa){

			/*$delete_auction_array = $wpdb->get_col($wpdb->prepare("SELECT meta_value from $wpdb->postmeta WHERE meta_key LIKE %s AND post_id = %d", '%wdm-image-%', $aa));*/

		 	$table_postmeta = $wpdb->postmeta;
		 	$n3_pre_qry = $wpdb->prepare("SELECT meta_value FROM {$table_postmeta}  
		 		WHERE meta_key LIKE %s AND post_id = %d", '%wdm-image-%', $aa);
			$delete_auction_array = $wpdb->get_col($n3_pre_qry);
		
			$del_auc = wp_delete_post($aa, false);
			if($del_auc){
				foreach($delete_auction_array as $delete_image_url){
					if(!empty($delete_image_url)  && $delete_image_url !== null) {

						/*$auction_url_post_id = $wpdb->get_var("SELECT ID from $wpdb->posts WHERE guid = '$delete_image_url' AND post_type = 'attachment'");*/

						$table_posts = $wpdb->posts;
						$n4_pre_qry = $wpdb->prepare("SELECT ID FROM {$table_posts} WHERE guid = %s AND post_type = %s", $delete_image_url, 'attachment');

						$auction_url_post_id = $wpdb->get_var($n4_pre_qry);
						wp_delete_post($auction_url_post_id, true); //also delete images attached
					}
				}
			}
			/*$wpdb->query( $wpdb->prepare("DELETE FROM ".$wpdb->prefix."wdm_bidders WHERE auction_id = %d", $aa));*/

			$table = $wpdb->prefix."wdm_bidders";
		 	$n5_pre_qry = $wpdb->prepare("DELETE FROM {$table} WHERE auction_id = %d", $aa);
			$wpdb->query($n5_pre_qry);
		}
		if($del_auc){
			printf(__("Auctions and their attachments are deleted successfully.", "wdm-ultimate-auction"));
		}
		else
			_e("Sorry, the auctions cannot be deleted.", "wdm-ultimate-auction");
		
	} /* end of if */
	
    die();
}

add_action('wp_ajax_multi_delete_auction', 'multi_delete_auction_callback');
add_action('wp_ajax_nopriv_multi_delete_auction', 'multi_delete_auction_callback');

//end auction Ajax callback - 'End Auction' link on 'Manage Auctions' page
function end_auction_callback(){
	
	if(wp_verify_nonce($_POST['uwaajax_nonce'], 'uwaajax_nonce')){
		$end_auc = update_post_meta(esc_attr($_POST['end_id']), 'wdm_listing_ends', date("Y-m-d H:i:s", current_time( 'timestamp' )));
		
		$check_term = term_exists('expired', 'auction-status');
		wp_set_post_terms(esc_attr($_POST['end_id']), $check_term["term_id"], 'auction-status');
		
		if($end_auc)
			printf(__("Auction %s ended successfully.", "wdm-ultimate-auction"), esc_attr($_POST['end_title']));
		else
			_e("Sorry, this auction cannot be ended.", "wdm-ultimate-auction");
		
	} /* end of if */
    die();
}

add_action('wp_ajax_end_auction', 'end_auction_callback');
add_action('wp_ajax_nopriv_end_auction', 'end_auction_callback');

//cancel bid entry Ajax callback - 'Cancel Last Bid' link on 'Manage Auctions' page
function cancel_last_bid_callback(){	
    global $wpdb;
    if(wp_verify_nonce($_POST['uwaajax_nonce'], 'uwaajax_nonce')){
		/*$cancel_bid = $wpdb->query( 
		   $wpdb->prepare( "DELETE FROM ".$wpdb->prefix."wdm_bidders WHERE id = %d",
				esc_attr($_POST['cancel_id'])
			));*/

		$table = $wpdb->prefix."wdm_bidders";
		$cid = $_POST['cancel_id'];
		$n6_pre_qry = $wpdb->prepare("DELETE FROM {$table} WHERE id = %d", $cid);
		$cancel_bid = $wpdb->query($n6_pre_qry);
			
		if($cancel_bid)
			printf(__("Bid entry of %s was removed successfully.", "wdm-ultimate-auction"), 
			  esc_attr($_POST['bidder_name']));
		else
			_e("Sorry, bid entry cannot be removed.", "wdm-ultimate-auction");
    }    
    die();
}

add_action('wp_ajax_cancel_last_bid', 'cancel_last_bid_callback');
add_action('wp_ajax_nopriv_cancel_last_bid', 'cancel_last_bid_callback');

//place bid Ajax callback - 'Place Bid' button on Single Auction page
function place_bid_now_callback(){
	if(wp_verify_nonce($_POST['uwaajax_nonce'], 'uwaajax_nonce')){
	
		   $ab_bid=round((double)esc_attr($_POST['ab_bid']),2);
		   $check=get_option('wdm_users_login');
		   $flag=false;
		   if($check=='with_login' && !is_user_logged_in())
		   {
			  echo json_encode(array("stat" => "Please log in to place bid"));
			  die();
		   }
		   else if($check=="without_login" || is_user_logged_in())
		   {
			  $flag=true;
		   }
		   if($flag){
			global $wpdb;
			$wpdb->hide_errors();
			
			/*$q="SELECT MAX(bid) FROM ".$wpdb->prefix."wdm_bidders WHERE auction_id =".esc_attr($_POST['auction_id']);*/

			$table = $wpdb->prefix."wdm_bidders";
			$auctionid = $_POST['auction_id'];
			$n7_pre_qry = $wpdb->prepare("SELECT MAX(bid) FROM {$table} WHERE 
				auction_id = %d", $auctionid);
			$next_bid = $wpdb->get_var($n7_pre_qry);
			
			if(!empty($next_bid)){
			  update_post_meta(esc_attr($_POST['auction_id']), 'wdm_previous_bid_value', $next_bid); //store bid value of the most recent bidder
			  $first_bid = 1;
			}					   
			
		   if(empty($next_bid)){
			  $next_bid = (float)$next_bid + (float)get_post_meta(esc_attr($_POST['auction_id']), 'wdm_incremental_val',true);
			  $first_bid = 0;
			}			  
			$high_bid = $next_bid;			
			
			if ($first_bid == 1) {   
			  $next_bid = $next_bid + get_post_meta(esc_attr($_POST['auction_id']),'wdm_incremental_val',true);
			}
			
			$terms = wp_get_post_terms(esc_attr($_POST['auction_id']), 'auction-status',array("fields" => "names"));
		   
			$next_bid=round($next_bid,2);
		   
			if($ab_bid < $next_bid)
			{
			  echo json_encode(array('stat' => 'inv_bid', 'bid' => $next_bid));
			}
			elseif(in_array('expired',$terms))
			{
			  echo json_encode(array("stat" => "Expired"));
			}
			else
			{
				 $ab_name = esc_attr($_POST['ab_name']);
				 $ab_email = esc_attr($_POST['ab_email']);
			 
				 $ab_bid = apply_filters('wdm_ua_modified_bid_amt', $ab_bid, $high_bid, 
				  esc_attr($_POST['auction_id']));
				 
				 $a_bid = array();
				 
				 if(is_array($ab_bid)){
					$a_bid = $ab_bid;
					if(!empty($a_bid['abid'])){
					   $ab_bid = $a_bid['abid'];
					}
					
					if(!empty($a_bid['cbid'])){
					   $cu_bid = $a_bid['cbid'];
					}
					
					if(!empty($a_bid['name'])){
					   $ab_name = $a_bid['name'];
					}
					
					if(!empty($a_bid['email'])){
					   $ab_email = $a_bid['email'];
					}
				 }
				 
				 $buy_price = get_post_meta(esc_attr($_POST['auction_id']), 'wdm_buy_it_now', true);
				 
				 if(!empty($buy_price) && $ab_bid >= $buy_price){
					add_post_meta(esc_attr($_POST['auction_id']), 'wdm_this_auction_winner', $ab_email, true);
					
					if(get_post_meta(esc_attr($_POST['auction_id']), 'wdm_this_auction_winner', true) === $ab_email){
					   if(!empty($a_bid)){
					do_action('wdm_ua_modified_bid_place', array( 'email_type' => 'winner', 'mod_name' => $ab_name, 'mod_email' => $ab_email, 'mod_bid' => $ab_bid, 'orig_bid' => $cu_bid, 'orig_name' => esc_attr($_POST['ab_name']), 'orig_email' => esc_attr($_POST['ab_email']), 'auc_name' => esc_attr($_POST['auc_name']), 'auc_desc' => esc_attr($_POST['auc_desc']), 'auc_url' => esc_url($_POST['auc_url']), 
					  'site_char' => esc_attr($_POST['ab_char']), 'auc_id' => esc_attr($_POST['auction_id'])));
					}
					else{
						$place_bid = $wpdb->insert( 
							$wpdb->prefix.'wdm_bidders', 
							array( 
								'name' => $ab_name, 
								'email' => $ab_email,
								'auction_id' => $_POST['auction_id'],
								'bid' => $ab_bid,
								'date' => date("Y-m-d H:i:s", current_time( 'timestamp' ))
							), 
							array( 
								'%s', 
								'%s',
								'%d',
								'%f',
								'%s'
							));
					   
					if($place_bid){
					 update_post_meta(esc_attr($_POST['auction_id']), 'wdm_listing_ends', date("Y-m-d H:i:s", current_time( 'timestamp' )));
					 $check_term = term_exists('expired', 'auction-status');
					 wp_set_post_terms(esc_attr($_POST['auction_id']), $check_term["term_id"], 'auction-status');
							 update_post_meta(esc_attr($_POST['auction_id']), 'email_sent_imd', 'sent_imd');
					
							 echo json_encode(array('type' => 'simple', 'stat' => 'Won', 'bid' => $ab_bid));
					   }
					}   
					}
					else{
						  echo json_encode(array("stat" => "Sold"));
					}
				 }
				 else{
					
					//$args = array();
					if(!empty($a_bid)){
					do_action('wdm_ua_modified_bid_place', array( 'mod_name' => $ab_name, 'mod_email' => $ab_email, 'mod_bid' => $ab_bid, 'orig_bid' => $cu_bid, 'orig_name' => 
					  esc_attr($_POST['ab_name']), 'orig_email' => esc_attr($_POST['ab_email']), 
					  'auc_name' => esc_attr($_POST['auc_name']), 'auc_desc' => esc_attr($_POST['auc_desc']), 'auc_url' => esc_url($_POST['auc_url']), 'site_char' => esc_attr($_POST['ab_char']), 'auc_id' => esc_attr($_POST['auction_id'])));
					} 
				else{
					   do_action('wdm_extend_auction_time', esc_attr($_POST['auction_id']));
					   
					   $place_bid = $wpdb->insert( 
					  		$wpdb->prefix.'wdm_bidders', 
							   	array( 
									'name' => $ab_name, 
									'email' => $ab_email,
									'auction_id' => $_POST['auction_id'],
									'bid' => $ab_bid,
									'date' => date("Y-m-d H:i:s", current_time( 'timestamp' ))
								), 
							   	array( 
									'%s', 
									'%s',
									'%d',
									'%f',
									'%s'
								));
							 
					if($place_bid){
					   echo json_encode(array('type' => 'simple', 'stat' => 'Placed', 'bid' => $ab_bid));
					}
				}
				 }
			}
		}
		else{
		   echo json_encode(array("stat" => "Please log in to place bid"));
		}
	
	} /* end of if */
	
	die();
}

add_action('wp_ajax_place_bid_now', 'place_bid_now_callback');
add_action('wp_ajax_nopriv_place_bid_now', 'place_bid_now_callback');

//bid notification email Ajax callback - Single Auction page
function bid_notification_callback(){
	
	if(wp_verify_nonce($_POST['uwaajax_nonce'], 'uwaajax_nonce')){
    
            $char = esc_attr($_POST['ab_char']);
                
            $ret_url = esc_url($_POST['auc_url']).$char."ult_auc_id=".esc_attr($_POST['auction_id']);
            
            $adm_email = get_option("wdm_auction_email");
  
            $hdr = "";
            //$hdr  = "From: ". get_bloginfo('name') ." <". $adm_email ."> \r\n";
            $hdr .= "MIME-Version: 1.0\r\n";
            $hdr .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            wdm_ua_seller_notification_mail($adm_email, 
              esc_attr($_POST['md_bid']), 
              $ret_url, 
              esc_attr($_POST['auc_name']), 
              esc_attr($_POST['auc_desc']), 
              esc_attr($_POST['ab_email']), 
              esc_attr($_POST['ab_name']), $hdr, '');
            
            wdm_ua_bidder_notification_mail(
              esc_attr($_POST['ab_email']), 
              esc_attr($_POST['ab_bid']), $ret_url, 
              esc_attr($_POST['auc_name']), 
              esc_attr($_POST['auc_desc']), $hdr, '');
            
	    //outbid email
	    global $wpdb;
	    $wpdb->hide_errors();
	    
	    $prev_bid = get_post_meta(esc_attr($_POST['auction_id']), 'wdm_previous_bid_value', true);
	    
	    if(!empty($prev_bid) && ($_POST['ab_bid'] > $prev_bid)){

	    	$bidder_email  = "";
	    	/*$email_qry = "SELECT email FROM ".$wpdb->prefix."wdm_bidders WHERE bid =".$prev_bid." AND auction_id =".esc_attr($_POST['auction_id']);*/

	    	$table = $wpdb->prefix."wdm_bidders";
            $auctionid = $_POST['auction_id'];
            $n8_pre_qry = $wpdb->prepare("SELECT email FROM {$table} WHERE bid = %d AND auction_id = %d", $prev_bid, $auctionid);             
	        $bidder_email = $wpdb->get_var($n8_pre_qry);
	       
	      	if($bidder_email != $_POST['ab_email']){
                  wdm_ua_outbid_notification_mail($bidder_email, 
                    esc_attr($_POST['md_bid']), $ret_url, 
                    esc_attr($_POST['auc_name']), 
                    esc_attr($_POST['auc_desc']), $hdr, '');
	       }
	    }
            
	    //auction won immediately
            if(isset($_POST['email_type']) && $_POST['email_type'] === 'winner_email')
            {
                //require_once('email-template.php');    
                ultimate_auction_email_template(
                  esc_attr($_POST['auc_name']), 
                  esc_attr($_POST['auction_id']), 
                  esc_attr($_POST['auc_desc']), 
                  round(esc_attr($_POST['md_bid']), 2), 
                  esc_attr($_POST['ab_email']), $ret_url);
            }
			
    } /* end of if */
	
	die();
}
add_action('wp_ajax_bid_notification', 'bid_notification_callback');
add_action('wp_ajax_nopriv_bid_notification', 'bid_notification_callback');

//private message Ajax callback - Single Auction page
function private_message_callback()
{
        
        $char = esc_attr($_POST['p_char']);
        
        $auc_url = esc_url($_POST['p_url']).$char."ult_auc_id=".esc_attr($_POST['p_auc_id']);
                
        $adm_email = get_option('wdm_auction_email');
        if(empty($adm_email)){
            $adm_email = get_option('admin_email');
        }
            
        $p_sub = "[".get_bloginfo('name')."] ".__("You have a private message from a site visitor", "wdm-ultimate-auction");
        
        $msg = "";
        $msg = __("Name", "wdm-ultimate-auction").": ".esc_attr($_POST['p_name'])."<br /><br />";
        $msg .= __("Email", "wdm-ultimate-auction").": ".esc_attr($_POST['p_email'])."<br /><br />";
        $msg .= __("Message", "wdm-ultimate-auction").": <br />".esc_attr($_POST['p_msg'])."<br /><br />";
        $msg .= __("Product URL", "wdm-ultimate-auction").": <a href='".$auc_url."'>".$auc_url."</a><br />";
        
        $hdr = "";
        //$hdr  = "From: ". get_bloginfo('name') ." <". $adm_email ."> \r\n";
	$hdr .= "Reply-To: <". esc_attr($_POST['p_email']) ."> \r\n";
        $hdr .= "MIME-Version: 1.0\r\n";
        $hdr .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        
        $sent = wp_mail($adm_email, $p_sub, $msg, $hdr, '');
        
        if($sent)
            _e("Email sent successfully.", "wdm-ultimate-auction");
        else
            _e("Sorry, the email could not sent.", "wdm-ultimate-auction");
        
	die();
}

add_action('wp_ajax_private_message', 'private_message_callback');
add_action('wp_ajax_nopriv_private_message', 'private_message_callback');

//plugin credit link
add_action('wp_footer', 'wdm_plugin_credit_link');

function wdm_plugin_credit_link() {
	
    add_option('wdm_powered_by', "Yes");
    
    $check_credit = get_option('wdm_powered_by');
    $wdm_layout_style = get_option('wdm_layout_style', 'layout_style_two');
        
        if ($wdm_layout_style == 'layout_style_one') {
            wp_enqueue_style('wdm_auction_front_end_styling', plugins_url('css/ua-front-end-one.css', __FILE__));
        } else {
        	wp_enqueue_style('wdm_auction_front_end_plugin_styling', plugins_url('css/ua-front-end-two.css', __FILE__));
        }

    
    /*if($check_credit == "Yes")
    {
        if(!is_admin())
        echo "<center><div id='ult-auc-footer-credit'><a href='http://auctionplugin.net' target='_blank'>".__("Powered By Ultimate Auction", "wdm-ultimate-auction")."</a></div></center>";
    }*/

    if($check_credit == "Yes"){
        if(!is_admin()) {
        	echo "<div id='ult-auc-footer-credit'><a href='http://auctionplugin.net' target='_blank'>".
        		__("Powered by Ultimate Auction", "wdm-ultimate-auction")."</a></div>";
       	}
    }

}


	add_action('init', 'wdm_set_auction_timezone');
function wdm_set_auction_timezone()
{
    $get_default_timezone = get_option('wdm_time_zone');
	$timezone_string = get_option( 'timezone_string' );
    
    if(!empty($get_default_timezone))
    {
        return $timezone_string;
    }
    
                                    if(isset($_GET["ult_auc_id"]) && $_GET["ult_auc_id"]){
                                       
                                    $single_auction=get_post($_GET["ult_auc_id"]);
                                    
                                        $auth_key = get_post_meta($single_auction->ID, 'wdm-auth-key', true);
                                        
                                        if(isset($_GET['wdm']) && $_GET['wdm'] === $auth_key)
                                        {
                                          $terms = wp_get_post_terms($single_auction->ID, 'auction-status',array("fields" => "names"));
                                          if(!in_array('expired',$terms))
                                          {
                                             $chck_term = term_exists('expired', 'auction-status');
                                             wp_set_post_terms($single_auction->ID, $chck_term["term_id"], 'auction-status');
                                             update_post_meta($single_auction->ID, 'wdm_listing_ends', date("Y-m-d H:i:s", current_time( 'timestamp' )));
                                          }
                                          
                                          update_post_meta($single_auction->ID, 'auction_bought_status', 'bought');
					  update_post_meta($single_auction->ID, 'wdm_auction_buyer', get_current_user_id());
                                          echo '<script type="text/javascript">
                                          setTimeout(function() {
                                                                alert("'.__("Thank you for buying this product.", "wdm-ultimate-auction").'");
                                                               }, 1000);       
                                          </script>';
					  
					  //details of a product sold through buy now link
					  if(is_user_logged_in()){
					  $curr_user = wp_get_current_user();
					  $buyer_email = $curr_user->user_email;
					  $winner_name = $curr_user->user_login;
					  }
					  
					  $auction_email = get_option('wdm_auction_email');
					  $site_name = get_bloginfo('name');
					  $site_url = get_bloginfo('url');
					  $c_code = substr(get_option('wdm_currency'), -3);
					  $rec_email = get_option('wdm_paypal_address');
					  $buy_now_price = get_post_meta($single_auction->ID, 'wdm_buy_it_now', true);
					  
					  $headers = "";
					  //$headers  = "From: ". $site_name ." <". $auction_email ."> \r\n";
					  $headers .= "Reply-To: <". $buyer_email ."> \r\n";
					  $headers .= "MIME-Version: 1.0\r\n";
					  $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
					  
					  $return_url = "";
					  $return_url = strstr($_SERVER['REQUEST_URI'], 'ult_auc_id', true);
					  $return_url = $site_url.$return_url."ult_auc_id=".$_GET["ult_auc_id"];
					  
					  $auction_data = array('auc_id' => $single_auction->ID,
								 'auc_name' => $single_auction->post_title,
								 'auc_desc' => $single_auction->post_content,
								 'auc_price' => $buy_now_price,
								 'auc_currency' => $c_code,
								 'seller_paypal_email' => $rec_email,
								 'winner_email' => $buyer_email,
								 'seller_email' => $auction_email,
								 'winner_name' => $winner_name,
								 'pay_method' => 'method_paypal',
								 'site_name' => $site_name,
								 'site_url' => $site_url,
								 'product_url' => $return_url,
								 'header' => $headers
					  );
					  
                                          $check_method = get_post_meta($single_auction->ID, 'wdm_payment_method', true);
						
					  if($check_method === 'method_paypal'){
                                             do_action('ua_shipping_data_email', $auction_data);
                                          }
                                          
                                        }
                                    }
                              

}

function wdm_ending_time_second_layout_calculator($seconds)
{
   $days = floor($seconds / 86400);
   $seconds %= 86400;

   $hours = floor($seconds / 3600);
   $seconds %= 3600;

   $minutes = floor($seconds / 60);
   $seconds %= 60;
					
   $rem_tm = "";
					

   if($days == 1 || $days == -1 || $days == 0)
      $rem_tm = "<div class='days'><span class='wdm_datetime' id='wdm_days'>".$days."</span><span id='wdm_days_text'> ".__("day", "wdm-ultimate-auction")." </span></div>";
   else
      $rem_tm = "<div class='days'><span class='wdm_datetime' id='wdm_days'>".$days."</span><span id='wdm_days_text'> ".__("days", "wdm-ultimate-auction")." </span></div>";

	if($hours == 1 || $hours == -1 || ($hours == 0))
      $rem_tm .= "<div class='hours'><span class='wdm_datetime' id='wdm_hours'>".$hours."</span><span id='wdm_hrs_text'> ".__("hour", "wdm-ultimate-auction")." </span></div>";
   else 
      $rem_tm .= "<div class='hours'><span class='wdm_datetime' id='wdm_hours'>".$hours."</span><span id='wdm_hrs_text'> ".__("hours", "wdm-ultimate-auction")." </span></div>";

   if($minutes == 1 || $minutes == -1 || $minutes == 0)
      $rem_tm .= "<div class='minutes'><span class='wdm_datetime' id='wdm_minutes'>".$minutes."</span><span id='wdm_mins_text'> ".__("minute", "wdm-ultimate-auction")." </span></div>";
   else
      $rem_tm .= "<div class='minutes'><span class='wdm_datetime' id='wdm_minutes'>".$minutes."</span><span id='wdm_mins_text'> ".__("minutes", "wdm-ultimate-auction")." </span></div>";

   if($seconds == 1 || $seconds == -1 || $seconds == 0)
      $rem_tm .= "<div class='second'><span class='wdm_datetime' id='wdm_seconds'>".$seconds."</span><span id='wdm_secs_text'> ".__("second", "wdm-ultimate-auction")."</span></div>";
   else
      $rem_tm .= "<div class='second'><span class='wdm_datetime' id='wdm_seconds'>".$seconds."</span><span id='wdm_secs_text'> ".__("seconds", "wdm-ultimate-auction")."</span></div>";
      
      return $rem_tm;
}


function wdm_ending_time_calculator($seconds)
{
   $days = floor($seconds / 86400);
   $seconds %= 86400;

   $hours = floor($seconds / 3600);
   $seconds %= 3600;

   $minutes = floor($seconds / 60);
   $seconds %= 60;
					
   $rem_tm = "";
					

   if($days == 1 || $days == -1)
      $rem_tm = "<span class='wdm_datetime' id='wdm_days'>".$days."</span><span id='wdm_days_text'> ".__("day", "wdm-ultimate-auction")." </span>";
   elseif($days == 0)
      $rem_tm = "<span class='wdm_datetime' id='wdm_days' style='display:none;'>".$days."</span><span id='wdm_days_text'></span>";
   else
      $rem_tm = "<span class='wdm_datetime' id='wdm_days'>".$days."</span><span id='wdm_days_text'> ".__("days", "wdm-ultimate-auction")." </span>";
   
   if($hours == 1 || $hours == -1)
      $rem_tm .= "<span class='wdm_datetime' id='wdm_hours'>".$hours."</span><span id='wdm_hrs_text'> ".__("hour", "wdm-ultimate-auction")." </span>";
   elseif($hours == 0)
      $rem_tm .= "<span class='wdm_datetime' id='wdm_hours' style='display:none;'>".$hours."</span><span id='wdm_hrs_text'></span>";
   else 
      $rem_tm .= "<span class='wdm_datetime' id='wdm_hours'>".$hours."</span><span id='wdm_hrs_text'> ".__("hours", "wdm-ultimate-auction")." </span>";

   if($minutes == 1 || $minutes == -1)
      $rem_tm .= "<span class='wdm_datetime' id='wdm_minutes'>".$minutes."</span><span id='wdm_mins_text'> ".__("minute", "wdm-ultimate-auction")." </span>";
   elseif($minutes == 0)
      $rem_tm .= "<span class='wdm_datetime' id='wdm_minutes' style='display:none;'>".$minutes."</span><span id='wdm_mins_text'></span>"; 
   else
      $rem_tm .= "<span class='wdm_datetime' id='wdm_minutes'>".$minutes."</span><span id='wdm_mins_text'> ".__("minutes", "wdm-ultimate-auction")." </span>";

   if($seconds == 1 || $seconds == -1)
      $rem_tm .= "<span class='wdm_datetime' id='wdm_seconds'>".$seconds."</span><span id='wdm_secs_text'> ".__("second", "wdm-ultimate-auction")."</span>";
   elseif($seconds == 0)
      $rem_tm .= "<span class='wdm_datetime' id='wdm_seconds' style='display:none;'>".$seconds."</span><span id='wdm_secs_text'></span>";
   else
      $rem_tm .= "<span class='wdm_datetime' id='wdm_seconds'>".$seconds."</span><span id='wdm_secs_text'> ".__("seconds", "wdm-ultimate-auction")."</span>";
      
      return $rem_tm;
}

add_filter('ua_list_winner_info', 'wdm_list_winner_info', 99, 4);

function wdm_list_winner_info($info, $winner, $id, $col) {

	if (!empty($winner)) {

		$info = "<a href='#' class='wdm_winner_info wdm-margin-bottom' id='wdm_winner_info_" . $col . "_" . $id . "'>" . $winner->user_login . "</a>";
		$info .= "<div class='wdm-margin-bottom wdm_winner_info_" . $col . "_" . $id . "' style='display:none;'><div>";
		$info .= !empty($winner->first_name) ? $winner->first_name : "";
		$info .= !empty($winner->last_name) ? " " . $winner->last_name : "";
		$info .= "</div><div><a href='mailto:" . $winner->user_email . "'>" . $winner->user_email . "</a></div></div>";
	}

	return $info;
}

/*add_filter('comment_post_redirect', 'redirect_after_comment');
function redirect_after_comment($location)
{
return $_SERVER["HTTP_REFERER"];
}*/

function prepare_single_auction_title($id, $title){
   
   $perma_type = get_option('permalink_structure');
   if(empty($perma_type))
         $set_char = "&";
   else
         $set_char = "?";
   
   $auc_url = get_option('wdm_auction_page_url');
   
   if(!empty($auc_url)){
      $link_title = $auc_url.$set_char."ult_auc_id=".$id;
      $link_title = "<a href='".$link_title."' target='_blank'>".$title."</a>";
      $title = $link_title;
   }
   
   return $title;
}

function paypal_auto_return_url_notes(){

   $pp_ms = '<div class="paypal-config-note-text" style="float: right;width: 530px;">';
		
   $pp_ms .= '<span class="pp-please-note">'.__("Mandatory Settings:", "wdm-ultimate-auction").'</span> <br />';
		
   $pp_ms .= '<span class="pp-url-notification">'.sprintf(__("It is mandatory to set %1\$s (if not already set) and enable %2\$s (if not already enabled) in your PayPal account for proper functioning of payment related features.", "wdm-ultimate-auction"),"<strong>Auto Return URL</strong>","<strong>Payment Data Transfer</strong>").'</span>';

   $pp_ms .= '<a href="" class="auction_fields_tooltip"><strong>'.__("?", "wdm-ultimate-auction").'</strong><span style="width: 370px;margin-left: -90px;">';
   
   $pp_ms .= sprintf(__("Whenever a visitor clicks on 'Buy it Now' button of a product/auction, he is redirected to PayPal where he can make payment for that product/auction.", "wdm-ultimate-auction")).'<br />';
   
   $pp_ms .= sprintf(__("After making payment he is again redirected automatically (if the %s has been set) to this site and then the auction expires.", "wdm-ultimate-auction"),"Auto Return URL").'<br />';
   
   $pp_ms .= '</span></a>';
   
   $pp_ms .= '<br /><a href="#" id="how-set-pp-auto-return">'.__("How to do these settings?", "wdm-ultimate-auction").'</a><br />';
   
   $pp_ms .= '<div id="wdm-steps-to-be-followed" style="display:none;"><br />';
   
   $pp_ms .= sprintf(__("1. Log in to your PayPal account", "wdm-ultimate-auction")).'- <a href="https://www.paypal.com/us/cgi-bin/webscr?cmd=_account" target="_blank">Live</a>/ <a href="https://www.sandbox.paypal.com/us/cgi-bin/webscr?cmd=_account" target="_blank">Sandbox</a><br />';
   
   $pp_ms .= sprintf(__("2. Go to the %s.", "wdm-ultimate-auction"),"<strong>Account setting</strong>").'<br />';
   
   $pp_ms .= sprintf(__("3. Go to the %s menu.", "wdm-ultimate-auction"),"<strong>Seller Tools</strong>").'<br />';

   $pp_ms .= sprintf(__("4. Click %1\$s under %2\$s.", "wdm-ultimate-auction"), "<strong>Website preferences</strong>", "<strong>Selling online section</strong>").'<br />';

   $pp_ms .= sprintf(__("5. %s page will open.", "wdm-ultimate-auction"),"<strong>Website Preferences</strong>").'<br />';

         
   $pp_ms .= sprintf(__("6. Enable %s.", "wdm-ultimate-auction"),"<strong>Auto Return</strong>").'<br />';
	 
   $pp_ms .= sprintf(__("7. Set a URL in %s box. Enter feed page URL.", "wdm-ultimate-auction"),"<strong>Return URL</strong>").'<br />';
	 
   $pp_ms .= sprintf(__("8. Enable %1\$s option (if the %2\$s is not set, %3\$s can not be enabled).", "wdm-ultimate-auction"),"<strong>PDT (Payment Data Transfer)</strong>", "<strong>Return URL</strong>", "<strong>PDT</strong>")." <br />";

   $pp_ms .= '</div></div>';
	
   $pp_ms .=  '<script type="text/javascript">
	jQuery(document).ready(function(){
	jQuery("#how-set-pp-auto-return").click(
		function(){
		jQuery("#wdm-steps-to-be-followed").slideToggle("slow");
		jQuery("html, body").animate({scrollTop: jQuery(".paypal-config-note-text").offset().top - 50});
		return false;
		});
	});
      </script>';
      
   return $pp_ms;
}

require_once('email-template.php');


/* notice for premium auction plugin */
function wdm_uwa_pro_add_plugins_notice() {		

	global $current_user ;
    $user_id = $current_user->ID;
    if ( ! get_user_meta($user_id, 'wdm_uwa_pro_ignore_notice') ) {	
	?>

		<div class="notice notice-info">
			<div class="get_uwa_pro">
				<a href=" https://auctionplugin.net?utm_source=ultimate plugin&utm_medium=admin notice&utm_campaign=learn more" target="_blank"> <img src="<?php echo plugins_url('/img/UWCA_row.jpg',__FILE__);?>" alt="" /> </a>
					
					<?php
					printf(__('<a href="%s"><img src="%s" /></a>'),'?wdm_uwa_pro_ignore=0',plugins_url('/img/error.png',__FILE__));
					?>

				<div class="clear"></div>
			</div>
		</div>
		<?php
	
	}
}
add_action('admin_notices', 'wdm_uwa_pro_add_plugins_notice');

function wdm_uwa_pro_ignore() {
	global $current_user;
        $user_id = $current_user->ID;
        /* If user clicks to ignore the notice, add that to their user meta */
        if ( isset($_GET['wdm_uwa_pro_ignore']) && '0' == $_GET['wdm_uwa_pro_ignore'] ) {
             add_user_meta($user_id, 'wdm_uwa_pro_ignore_notice', 'true', true);
	}
}
add_action('admin_init', 'wdm_uwa_pro_ignore');

add_action('admin_init', 'wdm_uwa_pro_ignore');
		
	function wdm_image_sizes() {
    add_image_size( 'wdm-list-slider-thumb', 160, 160, true );
}
add_action( 'init', 'wdm_image_sizes' );	


function wdm_uwa_plugin_layout_notice() {

	global $current_user ;
    $user_id = $current_user->ID;
    if ( ! get_user_meta($user_id, 'wdm_uwa_plugin_layout_ignore_notice') ) {	
  
    echo '<div class="notice"><p>' . sprintf( __( '<b>Ultimate WordPress Auction Plugin:</b> Important Message - We have implemented a new layout for the auction list page and auction detail page. The new layout appears by default on both pages. We have given the option to change the layout for auction pages. So, the admin can set the old layout or new layout from the auction settings. <a href="%2$s">Hide Notice</a>', 'woo_ua' ), get_bloginfo( 'url' ), esc_attr( add_query_arg( 'wdm_uwa_plugin_layout_ignore', '0' ) ) ) . '</p></div>';
    }
}
add_action( 'admin_notices', 'wdm_uwa_plugin_layout_notice' );

function wdm_uwa_plugin_layout_ignore() {
	
	global $current_user;
	$user_id = $current_user->ID;
		
		if ( isset( $_GET['wdm_uwa_plugin_layout_ignore'] ) && '0' == $_GET['wdm_uwa_plugin_layout_ignore'] ) {
			add_user_meta( $user_id, 'wdm_uwa_plugin_layout_ignore_notice', 'true', true );
		}
}

add_action('admin_init', 'wdm_uwa_plugin_layout_ignore');
?>