<?php

//check if an auction has been expired, send email to the winner
add_action('wp_footer', 'wdm_email_auction_winner');
add_action('admin_head', 'wdm_email_auction_winner');

function wdm_email_auction_winner(){
				
global $wpdb;

$all_auc = array(
                'posts_per_page'  => -1,
                'post_type'       => 'ultimate-auction'
                );

if(!empty($all_auc)){				
	$all_auctions = get_posts( $all_auc);

foreach($all_auctions as $single_auc){
				
$active_term = wp_get_post_terms($single_auc->ID, 'auction-status',
	array("fields" => "names"));
if(current_time( 'timestamp' ) >= strtotime(get_post_meta($single_auc->ID,'wdm_listing_ends',true))){
				if(!in_array('expired',$active_term))
				{
					$check_tm = term_exists('expired', 'auction-status');
					wp_set_post_terms($single_auc->ID, $check_tm["term_id"], 
						'auction-status');
				}
  }
}				
$comp_auc = array(
                'posts_per_page'  => -1,
                'post_type'       => 'ultimate-auction',
                'auction-status'  => 'expired' 
                );

$completed_auctions = get_posts( $comp_auc );
            
foreach($completed_auctions as $ca){
				
    if(get_post_meta($ca->ID,'auction_email_sent',true) !== 'sent'){
				
	    $bought = get_post_meta($ca->ID,'auction_bought_status', true);
    
	    /*$count_qry = "SELECT COUNT(bid) FROM ".$wpdb->prefix."wdm_bidders WHERE auction_id =".$ca->ID;*/

	    $table = $wpdb->prefix . "wdm_bidders";
		$auctionid = $ca->ID;

		$count_qry = $wpdb->prepare("SELECT COUNT(bid) FROM {$table} 
	    	WHERE auction_id = %d", $auctionid);

	    $count_bid = $wpdb->get_var($count_qry);
	    
	    $was_sent_imd = get_post_meta($ca->ID, 'email_sent_imd', true);
	    $is_in_progress = get_post_meta($ca->ID,'wdm_to_be_sent',true);
	    
	    if($bought !== 'bought' && $count_bid > 0 && $was_sent_imd !== 'sent_imd' 
			/*&& $is_in_progress !== 'in_progress'*/){
				
		  $reserve_price_met = get_post_meta($ca->ID, 'wdm_lowest_bid',true);
		  $winner_bid = "";

          /*$bid_qry = "SELECT MAX(bid) FROM ".$wpdb->prefix."wdm_bidders WHERE auction_id =".$ca->ID." ORDER BY id DESC";*/

          $bid_qry = $wpdb->prepare("SELECT MAX(bid) FROM {$table} WHERE auction_id = %d ORDER BY id DESC", $auctionid);

          $winner_bid = $wpdb->get_var($bid_qry);
	  
		  if($winner_bid >= $reserve_price_met){

	        	update_post_meta($ca->ID, 'wdm_to_be_sent', 'in_progress');
				$winner_email  = "";

				/*$email_qry = "SELECT email FROM ".$wpdb->prefix."wdm_bidders WHERE bid =".$winner_bid." AND auction_id =".$ca->ID." ORDER BY id DESC";*/

				$email_qry = $wpdb->prepare("SELECT email FROM {$table} WHERE bid = %d 
					AND auction_id = %d ORDER BY id DESC", $winner_bid, $auctionid);

				$winner_email = $wpdb->get_var($email_qry);

				$return_url = get_post_meta($ca->ID, 'current_auction_permalink',true);
				wp_enqueue_script('jquery');
				require('ajax-actions/send-email.php');
	  }
	
				}
	                }
                }
        }
}
?>