<?php
/*
+----------------------------------------------------------------+
|																						
|	WordPress Plugin: WP-PostRatings-Cheater							
|	Copyright (c) 2012 Manfred Fettinger									
|																						
|	File Written By:														
|	- Manfred Fettinger									
|																						
|	File Information:																
|	- Sales Cheater Settings Menu											
|	- wp-content/plugins/wp-postratings-cheater/wp-postratings-cheater-settings.php		
|																						
+----------------------------------------------------------------+
*/


### Needed Variables
$pc_ratings_image 			= get_option('postratings_image');
$pc_ratings_max 			= intval(get_option('postratings_max'));
$pc_ratings_custom 			= intval(get_option('postratings_customrating'));
$pc_post_ratings_alt_text 	= 'Alt Text';
$pc_ratings_texts 			= get_option('postratings_ratingstext');
$pc_ratings_custom 			= intval(get_option('postratings_customrating'));

### Check Whether User Can Manage Ratings
if(!current_user_can('role_prcheater')) {
	die('Access Denied');
}

if(!is_plugin_active('wp-postratings/wp-postratings.php')){
	die('Plugin WP-PostRatings seems not to be active');
}


### If Form Is Submitted
if($_POST['Submit']) {

	global $wpdb;
	
	### $pc_score 		  = intval($_POST['pc_score']);
	$pc_average       = floatval($_POST['pc_average']);
	$pc_users 		  = intval($_POST['pc_users']);
	$pc_score		  = $pc_average * $pc_users;
	$pc_post_id       = intval($_POST['post_id']);

	if($pc_average > $pc_ratings_max){
		$txt = '<font color="red">Average value can not be greater than the max value of '.$pc_ratings_max.' </font>';
	}else if($pc_users == 0){
		$txt = '<font color="red">Please enter a value for number of users!</font>';
	}

	if(!empty($txt)){
		echo '<!-- Last Action --><div id="message" class="updated fade"><p>'.$txt.'</p></div>';
	}else{
		$text = '<font color="green"><b>Update Log for Post ID '.$pc_post_id.'</b></font><br /><br>';
		if(update_post_meta($pc_post_id, 'ratings_score',   $pc_score)){
			$text .= '<font color="green">Score Value Updated</font><br />';
		}else{
			$text .= '<font color="red">Score Value NOT Updated</font><br />';
		}
		
		if(update_post_meta($pc_post_id, 'ratings_average', $pc_average)){
			$text .= '<font color="green">Ratings Value Updated</font><br />';
		}else{
			$text .= '<font color="red">Ratings Value NOT Updated</font><br />';
		}
		
		if(update_post_meta($pc_post_id, 'ratings_users',   $pc_users)){
			$text .= '<font color="green">Users Value Updated</font><br />';
		}else{
			$text .= '<font color="red">Users Value NOT Updated</font><br />';
		}

		echo '<!-- Last Action --><div id="message" class="updated fade"><p>'.$text.'</p></div>'; 
	}
	
}

global $title;

###$sql =  "select a.meta_id, a.post_id, a.meta_key, a.meta_value, b.post_title ".
###		"from wp_postmeta as a join wp_posts as b on a.post_id = b.id ".
###		"where a.meta_key = 'ratings_score' ".
###		"order by a.post_id;";

### Thanks to User mlloret
	$dbprefix = $wpdb->base_prefix;
	$sql = "select a.meta_id, a.post_id, a.meta_key, a.meta_value, b.post_title ".
		   "from " . $dbprefix . "postmeta as a join " . $dbprefix . "posts as b on a.post_id = b.id ".
		   "where a.meta_key = 'ratings_score' ".
		   "order by a.post_id;";
	
$result = $wpdb->get_results($sql);
?>
	<h2><?php echo $title; ?></h2>



	<?php wp_nonce_field('wp-postratings-cheater-settings'); ?>
		
	<table class="widefat">
		<tr>
			<th scope="row" valign="top"><b>PostID</b></th>
			<th scope="row" valign="top"><b>Post Title</b></th>
			<th scope="row" valign="top"><b>Score</b></th>
			<th scope="row" valign="top"><b>Average</b></th>
			<th scope="row" valign="top"><b>Users</b></th>
			<th scope="row" valign="top"><b>Rating</b></th>
			<th scope="row" valign="top"><b>Action</b></th>
		</tr>
		
<?php
	foreach($result as $line){ 
		$pc_post_ratings_score 		= get_post_meta($line->post_id, 'ratings_score', true);
		$pc_post_ratings_average 	= get_post_meta($line->post_id, 'ratings_average', true);
		$pc_post_ratings_users 		= get_post_meta($line->post_id, 'ratings_users', true); ?>
		
		<form method="post" action="<?php echo admin_url('admin.php?page='.plugin_basename(__FILE__)); ?>"> 
		<tr>
			<td>
				<input type="hidden" name="post_id" value="<?php echo $line->post_id; ?>">
				<?php echo $line->post_id; ?>
			</td>
			<td>
				<?php echo $line->post_title;?>
			</td>
			<td>
				<?php echo $pc_post_ratings_score; ?>
			</td>
			<td>
				<input type="text" id="pc_average" name="pc_average" value="<?php echo $pc_post_ratings_average; ?>" size="3"/>
			</td>
			<td>
				<input type="text" id="pc_users" name="pc_users" value="<?php echo $pc_post_ratings_users; ?>" size="3"/>
			</td>
			<td><?php
			
				$pc_post_ratings = round($pc_post_ratings_average, 1);
				
				// Check for half star
				$pc_insert_half = 0;
				$average_diff = abs(floor($pc_post_ratings_average)-$pc_post_ratings);
				if($average_diff >= 0.25 && $average_diff <= 0.75) {
					$pc_insert_half = ceil($pc_post_ratings_average);
				} elseif($average_diff > 0.75) {
					$pc_insert_half = ceil($pc_post_ratings);
				}  
				
				$post_ratings_images = get_ratings_images_vote( $line->post_id, 
															    $pc_ratings_custom, 
																$pc_ratings_max, 
																$pc_post_ratings, 
																$pc_ratings_image, 
																$pc_post_ratings_alt_text, 
																$pc_insert_half, 
																$pc_ratings_texts);
				echo $post_ratings_images;
			
			?></td>

			<td>
				<input type="submit" name="Submit" value="Update this Rating" class="button-secondary" />
			</td>
		</tr>
		</form>
		
		<?php } //foreach end?>
	</table>		
	
	<br>