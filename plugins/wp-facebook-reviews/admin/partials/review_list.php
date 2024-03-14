<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    WP_FB_Reviews
 * @subpackage WP_FB_Reviews/admin/partials
 */
 
     // check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }
	
	
	$html="";
//db function variables
global $wpdb;
$table_name = $wpdb->prefix . 'wpfb_reviews';
$rowsperpage = 20;
$nonce = wp_create_nonce( 'my-nonce' );
?>
<div class="">
<h1></h1>
<div class="wrap" id="wp_rev_maindiv">

	<img class="wprev_headerimg" src="<?php echo plugin_dir_url( __FILE__ ) . 'logo.png?id='.$this->_token; ?>">
	
<?php 
include("tabmenu.php");
?>	
<div class="welcomecontainer wpfbr_margin10 w3-row-padding w3-section w3-stretch">

<div class="w3-col w3-container ">
<div class="welcomediv w3-white w3-border w3-border-light-gray2 w3-round-small">

<div class="wpfbr_margin10">
	<a id="wpfbr_helpicon" class="wpfbr_btnicononly button dashicons-before dashicons-editor-help"></a>
	<a id="wpfbr_removeallbtn" data-sec="<?php echo esc_attr( $nonce ); ?>" class="button dashicons-before dashicons-no"><?php _e('Remove All Reviews', 'wp-fb-reviews'); ?></a>
<p>
	<?php 
_e('Search reviews, hide certain reviews, manually add reviews, save a CSV file of your reviews to your computer, and more features available in the <a href="?page=wp_fb-get_pro">Pro Version</a> of this plugin!', 'wp-fb-reviews'); 
?>
</p>
</div>
<?php 

	//remove all, first make sure they want to remove all
	if(isset($_GET['opt']) && $_GET['opt']=="delallfb"){
		//security
		$nonce = $_REQUEST['_wpnonce'];
		if ( ! wp_verify_nonce( $nonce, 'my-nonce' ) ) {
			// This nonce is not valid.
			die( __( 'Failed security check.', 'wp-airbnb-review-slider' ) ); 
		}
		
		//$delete = $wpdb->query("TRUNCATE TABLE `".$table_name."`");
		$delete = $wpdb->query("DELETE FROM `".$table_name."` WHERE `type` = 'Facebook'");
		
		//delete all cached avatars
		$img_locations_option = json_decode(get_option( 'wprev_img_locations' ),true);
		//delete all local avatars, used for FB images
			$avatar_dir = $img_locations_option['upload_dir_wprev_avatars'];
			$localfiles = glob($avatar_dir.'*');
			foreach($localfiles as $file){ // iterate files
			  if(is_file($file))
				unlink($file); // delete file
			}
	}
	if(isset($_GET['opt']) && $_GET['opt']=="delalltw"){
		//security
		$nonce = $_REQUEST['_wpnonce'];
		if ( ! wp_verify_nonce( $nonce, 'my-nonce' ) ) {
			// This nonce is not valid.
			die( __( 'Failed security check.', 'wp-airbnb-review-slider' ) ); 
		}
		
		//$delete = $wpdb->query("TRUNCATE TABLE `".$table_name."`");
		$delete = $wpdb->query("DELETE FROM `".$table_name."` WHERE `type` = 'Twitter'");
	}
	if(isset($_GET['opt']) && $_GET['opt']=="delall"){
		//security
		$nonce = $_REQUEST['_wpnonce'];
		if ( ! wp_verify_nonce( $nonce, 'my-nonce' ) ) {
			// This nonce is not valid.
			die( __( 'Failed security check.', 'wp-airbnb-review-slider' ) ); 
		}
		//$delete = $wpdb->query("TRUNCATE TABLE `".$table_name."`");
		$delete = $wpdb->query("DELETE FROM `".$table_name."`");
		
		//delete all cached avatars
		$img_locations_option = json_decode(get_option( 'wprev_img_locations' ),true);
		//delete all local avatars, used for FB images
			$avatar_dir = $img_locations_option['upload_dir_wprev_avatars'];
			$localfiles = glob($avatar_dir.'*');
			foreach($localfiles as $file){ // iterate files
			  if(is_file($file))
				unlink($file); // delete file
			}

	}
	
	//pagenumber
	if(isset($_GET['pnum'])){
	$temppagenum = $_GET['pnum'];
	} else {
	$temppagenum ="";
	}
	if ( $temppagenum=="") {
		$pagenum = 1;
	} else if(is_numeric($temppagenum)){
		$pagenum = intval($temppagenum);
	}
	
	if(!isset($_GET['sortdir'])){
		$_GET['sortdir'] = "";
	}
	if ( $_GET['sortdir']=="" || $_GET['sortdir']=="DESC") {
		$sortdirection = "&sortdir=ASC";
	} else {
		$sortdirection = "&sortdir=DESC";
	}
	$currenturl = remove_query_arg( 'sortdir' );
	
	//make sure sortby is valid
	if(!isset($_GET['sortby'])){
		$_GET['sortby'] = "";
	}
	$allowed_keys = ['created_time_stamp', 'reviewer_name', 'rating', 'review_length', 'pagename', 'type', 'recommendation_type' ];
	$checkorderby = sanitize_key($_GET['sortby']);
	
		if(in_array($checkorderby, $allowed_keys, true) && $_GET['sortby']!=""){
			$sorttable = $_GET['sortby']. " ";
		} else {
			$sorttable = "created_time_stamp ";
		}
		if($_GET['sortdir']=="ASC" || $_GET['sortdir']=="DESC"){
			$sortdir = $_GET['sortdir'];
		} else {
			$sortdir = "DESC";
		}
		unset($sorticoncolor);
		for ($x = 0; $x <= 10; $x++) {
			$sorticoncolor[$x]="";
		} 
		if($sorttable=="hide "){
			$sorticoncolor[0]="text_green";
		} else if($sorttable=="reviewer_name "){
			$sorticoncolor[1]="text_green";
		} else if($sorttable=="rating "){
			$sorticoncolor[2]="text_green";
		} else if($sorttable=="created_time_stamp "){
			$sorticoncolor[3]="text_green";
		} else if($sorttable=="review_length "){
			$sorticoncolor[4]="text_green";
		} else if($sorttable=="pagename "){
			$sorticoncolor[5]="text_green";
		} else if($sorttable=="type "){
			$sorticoncolor[6]="text_green";	
		}else if($sorttable=="recommendation_type "){
			$sorticoncolor[7]="text_green";	
		}
		
		$html .= '
		<table class="wp-list-table widefat striped posts">
			<thead>
				<tr>
					<th scope="col" width="50px" class="manage-column">'.__('Pic', 'wp-fb-reviews').'</th>
					<th scope="col" style="min-width:70px" class="manage-column"><a href="'.esc_url( add_query_arg( 'sortby', 'reviewer_name',$currenturl ) ).$sortdirection.'"><i class="dashicons dashicons-sort '.$sorticoncolor[1].'" aria-hidden="true"></i> '.__('Name', 'wp-fb-reviews').'</a></th>
					<th scope="col" width="70px" class="manage-column"><a href="'.esc_url( add_query_arg( 'sortby', 'rating',$currenturl ) ).$sortdirection.'"><i class="dashicons dashicons-sort '.$sorticoncolor[2].'" aria-hidden="true"></i> '.__('Rating', 'wp-fb-reviews').'</a></th>
					<th scope="col" width="70px" class="manage-column"><a href="'.esc_url( add_query_arg( 'sortby', 'recommendation_type',$currenturl ) ).$sortdirection.'"><i class="dashicons dashicons-sort '.$sorticoncolor[7].'" aria-hidden="true"></i> '.__('R_Type', 'wp-fb-reviews').'</a></th>
					<th scope="col" class="manage-column">'.__('Review Text', 'wp-fb-reviews').'</th>
					<th scope="col" width="100px" class="manage-column"><a href="'.esc_url( add_query_arg( 'sortby', 'created_time_stamp',$currenturl ) ).$sortdirection.'"><i class="dashicons dashicons-sort '.$sorticoncolor[3].'" aria-hidden="true"></i> '.__('Date', 'wp-fb-reviews').'</a></th>
					<th scope="col" width="70px" class="manage-column"><a href="'.esc_url( add_query_arg( 'sortby', 'review_length',$currenturl ) ).$sortdirection.'"><i class="dashicons dashicons-sort '.$sorticoncolor[4].'" aria-hidden="true"></i> '.__('Length', 'wp-fb-reviews').'</a></th>
					<th scope="col" width="100px" class="manage-column"><a href="'.esc_url( add_query_arg( 'sortby', 'pagename',$currenturl ) ).$sortdirection.'"><i class="dashicons dashicons-sort '.$sorticoncolor[5].'" aria-hidden="true"></i> '.__('Page Name', 'wp-fb-reviews').'</a></th>
					<th scope="col" width="100px" class="manage-column"><a href="'.esc_url( add_query_arg( 'sortby', 'type',$currenturl ) ).$sortdirection.'"><i class="dashicons dashicons-sort '.$sorticoncolor[6].'" aria-hidden="true"></i> '.__('Type', 'wp-fb-reviews').'</a></th>
				</tr>
				</thead>
			<tbody id="review_list">';
		//get reviews from db
		$lowlimit = ($pagenum - 1) * $rowsperpage;
		$tablelimit = $lowlimit.",".$rowsperpage;
		$reviewsrows = $wpdb->get_results(
			$wpdb->prepare("SELECT * FROM ".$table_name."
			WHERE id>%d
			ORDER BY ".$sorttable." ".$sortdir." 
			LIMIT ".$tablelimit." ", "0")
		);
		//total number of rows
		$reviewtotalcount = $wpdb->get_var( 'SELECT COUNT(*) FROM '.$table_name );
		//total pages
		$totalpages = ceil($reviewtotalcount/$rowsperpage);
		$userpicwarning = '';
		$foundlocalimg = false;
		if($reviewtotalcount>0){
			foreach ( $reviewsrows as $reviewsrow ) 
			{
				if($reviewsrow->hide!="yes"){
					$hideicon = '<i class="dashicons dashicons-visibility text_green" aria-hidden="true"></i>';
				} else {
					$hideicon = '<i class="dashicons dashicons-hidden" aria-hidden="true"></i>';
				}
				$hideicon ='';
				//user image
				$userpicwarning = '';
				if($reviewsrow->userpiclocal!=""){
					$userpic = '<img style="-webkit-user-select: none" width="60px" src="'.$reviewsrow->userpiclocal.'">';
					$foundlocalimg=true;
				} else {
					$userpic = '<img style="-webkit-user-select: none" width="60px" src="'.$reviewsrow->userpic.'">';
					if($foundlocalimg==false){
					//$userpicwarning = '<div id="userpicwarning">Notice: The PHP Copy function was not able to save the Profile Images to your server. It may still be working in the background. Wait a little while, refresh this page, and see if this message will disappear. The more reviews you have the longer it takes. If that doesn\'t work then make sure the Copy function is enabled on your server. This plugin will still work, however Facebook now expires Profile Images after a while. When they expire you will need to delete the reviews and re-download them.</div>';
					} else {
					$userpicwarning ='';
					}
				}
				//user profile link
				$profilelink='';
				if($reviewsrow->type=="Facebook"){
					$profilelink = "http://facebook.com/".$reviewsrow->reviewer_id;
				}
				if($profilelink==''){
					$userpic = '<a href="'.$profilelink.'" target=_blank>'.$userpic.'</a>';
				}
	
				$html .= '<tr id="'.$reviewsrow->id.'">
						<th scope="col" class="manage-column">'.$userpic.'</th>
						<th scope="col" class="manage-column">'.$reviewsrow->reviewer_name.'</th>
						<th scope="col" class="manage-column">'.$reviewsrow->rating.'</th>
						<th scope="col" class="manage-column">'.$reviewsrow->recommendation_type.'</th>
						<th scope="col" class="manage-column">'.$reviewsrow->review_text.'</th>
						<th scope="col" class="manage-column">'.$reviewsrow->created_time.'</th>
						<th scope="col" class="manage-column">'.$reviewsrow->review_length.'</th>
						<th scope="col" class="manage-column">'.$reviewsrow->pagename.'</th>
						<th scope="col" class="manage-column">'.$reviewsrow->type.'</th>
					</tr>';
			}
		} else {
				$html .= '<tr>
						<th colspan="9" scope="col" class="manage-column">'.__('No reviews found. Please visit the <a href="?page=wpfb-facebook">Get FB Reviews</a> page to retrieve reviews from Facebook, or manually add one. If you\'ve already done that, then try de-activating and re-activating the plugin.', 'wp-fb-reviews').'</th>
					</tr>';
		}					
				
				
		$html .= '</tbody>
		</table>';
		
		//pagination bar
		$html .= '<div id="wpfb_review_list_pagination_bar">';
		$currenturl = remove_query_arg( 'pnum' );
		for ($x = 1; $x <= $totalpages; $x++) {
			if($x==$pagenum){$blue_grey = "blue_grey";} else {$blue_grey ="";}
			$html .= '<a href="'.esc_url( add_query_arg( 'pnum', $x,$currenturl ) ).'" class="button '.$blue_grey.'">'.$x.'</a>';
		} 
		
		$html .= '</div><br>';
				
		$html .= '</div>';		
 
 echo $userpicwarning;
 echo $html;
?>
	<div id="popup_review_list" class="popup-wrapper wpfbr_hide">
	  <div class="popup-content">
		<div class="popup-title">
		  <button type="button" class="popup-close">&times;</button>
		  <h3 id="popup_titletext"></h3>
		</div>
		<div class="popup-body">
		  <div id="popup_bobytext1"></div>
		  <div id="popup_bobytext2"></div>
		</div>
	  </div>
	</div>
	
	</div></div>

