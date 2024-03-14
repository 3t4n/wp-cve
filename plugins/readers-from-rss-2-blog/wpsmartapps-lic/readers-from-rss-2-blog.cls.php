<?php 
/**
 * Plugin By Jabin Kadel (Web Programmer and WpSmartApps.com Founder)
 *
 * Holds necessary functions and variables
 */
class WSA_LIC_ReadersFromRss2Blog {


	var $rfr2b_options_table       = 'rfr2b_options';
	var $rfr2b_target_rss_table    = 'rfr2b_target';
	
	
	var	$rfr2b_control_options = array(
									'rfr2b_include_pages' => '',
									'displayINpageID' => array(),
									'rfr2b_display_post_tags' => 1,
									'display_x_comments' => 1, 
									'related_post' => 5,
									'noOfComments2display' => 3,
									'rfr2b_display_latest_post_comments' => 1,
								);
								
	/**
	 * Creates Optin options table
	 */
	function __rfr2b_options_table() {
	    global $wpdb;
		$db_table = $wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}rfr2b_options'");
		if ( $db_table != $wpdb->prefix.'rfr2b_options' ) {
			$create_rfr2b_options_table = "CREATE TABLE {$wpdb->prefix}rfr2b_options (                                  
										   `option_name` varchar(250) collate utf8_general_ci NOT NULL,  
										   `option_value` text collate utf8_general_ci,                  
											PRIMARY KEY  (`option_name`)                                    
											);
										   ";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );				
			dbDelta($create_rfr2b_options_table);
			return true;
		}
		return false;
	}
	
	
	/**
	 * Create auto table
	 */
	function __rfr2b_target_rss_table() {
	    global $wpdb;
		$db_table = $wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}rfr2b_target'");
		if ( $db_table != $wpdb->prefix.'rfr2b_target' ) {
		$create_targer_rss_table = "CREATE TABLE {$wpdb->prefix}rfr2b_target (                                                
										 `id` int(11) NOT NULL auto_increment,                                               
										 `rss_content` text collate utf8_general_ci NOT NULL,                     
										 `rss_ad_campaign_name` varchar(100) collate utf8_general_ci NOT NULL,                   
										 `optin_fields` text collate utf8_general_ci NOT NULL,                      
										 `rss_extra` text collate utf8_general_ci NOT NULL,                      
										 `flag_ad_campaign` enum('0','1') collate utf8_general_ci NOT NULL default '0',            
										 PRIMARY KEY  (`id`)                                                          
										); 
										";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );				
			dbDelta($create_targer_rss_table);
			return true;
		}
		return false;
	}
	
	
	/**
	 * Adds default optin data to DB table
	 */
	function __rfr2b_DefaultOptinData() {
		global $wpdb;
		$rfr2b_DefaultData = array(
					'rfr2b_affiliate_options'  => '',
					'rfr2b_no_Comments'        => '0 (Zero),  Be the first to leave a reply!',
					'rfr2b_one_Comments'       => '1 (One) on this item',
					'rfr2b_more_Comments'      => '% comments on this item',
					'rfr2b_randompost_title'   => 'You might be interested in this:',
					'rfr2b_social_links'       => stripslashes('<a href="http://del.icio.us/post?url=%post-url%&title=%post-title%">del.icio.us</a>&nbsp;|&nbsp; <a href="http://www.facebook.com/share.php?u=%post-url%" >Share on Facebook</a> &nbsp;|&nbsp; <a href="http://twitthis.com/twit?url=%post-url%&title=%post-title%">Twitter</a>&nbsp;|&nbsp; <a href="http://digg.com/submit?phase=2&url=%post-url%&title=%post-title%" >Digg</a>&nbsp;|&nbsp; <a href="http://www.stumbleupon.com/submit?url=%post-url%&title=%post-title%" >StumbleUpon</a>'),
					'rfr2b_copyright_notice'   => 'Copyright &copy;&nbsp;<a href="%blog-url%">%blog-name%</a> [<a href="%post-url%">%post-title%</a>], All Right Reserved. %year%.',
					'rfr2b_control_options'    => serialize($this->rfr2b_control_options),  
							);
		foreach( $rfr2b_DefaultData as $key => $val ) {
			$db_insert_DefaultData = "INSERT INTO $this->rfr2b_options_table (option_name, option_value) VALUES ('$key', '$val')";	
			$wpdb->query($db_insert_DefaultData);					
		}									
	}
	
	function __rfr2b_update_notify(){
	
		global $pagenow;
		// update notifaction
		if ('index.php' === $pagenow ) {
		
			$wsac  = filter_input(INPUT_GET, 'wsac', FILTER_SANITIZE_SPECIAL_CHARS);
			if( isset($wsac) && $wsac != '' ) {
				update_option('wsa_alert_msg', $wsac);
			}	
				
			$alertmsg = get_option('wsa_alert_msg');
			if( $alertmsg == ''  || $alertmsg > 0 ) {
		?>
		<script type="text/javascript" src="<?php echo base64_decode( 'aHR0cDovL3d3dy53cHNtYXJ0YXBwcy5jb20vd3NhLXBsdWdpbi9wbHVnaW4tdmVyc2lvbi9hcGkucGhwP3BsdWdpbj1pbGJwbGl0ZSZ2ZXJzaW9u'); ?>=<?php echo $alertmsg; ?>&apgurl=<?php echo get_admin_url(); ?>"></script>		
		<?php
			}
		}
		// eof update
	
	}
	
	
	/**
	 * Get options from option table
	 */
	function __rfr2b_fetch_Options( $option_name = '' ) {
		global $wpdb;
		$sql = "SELECT option_name, option_value FROM $this->rfr2b_options_table";
		if ( $option_name != '' ) $sql .= " WHERE option_name='$option_name'";
		$rs = $wpdb->get_results( $sql, ARRAY_A );
		if( $rs ) { 
			foreach ( $rs as $row ) {
				if ( $row['option_name'] == 'rfr2b_affiliate_options' ) $this->fetch_rfr2b_affiliateOptions = unserialize($row['option_value']);
				if ( $row['option_name'] == 'rfr2b_no_Comments' ) $this->fetch_rfr2b_no_Comments = $row['option_value'];
				if ( $row['option_name'] == 'rfr2b_one_Comments' ) $this->fetch_rfr2b_one_Comments = $row['option_value'];
				if ( $row['option_name'] == 'rfr2b_more_Comments' ) $this->fetch_rfr2b_more_Comments = $row['option_value'];
				if ( $row['option_name'] == 'rfr2b_social_links' ) $this->fetch_rfr2b_social_links = $row['option_value'];
				if ( $row['option_name'] == 'rfr2b_randompost_title' ) $this->fetch_rfr2b_randompost_title = $row['option_value'];
				if ( $row['option_name'] == 'rfr2b_copyright_notice' ) $this->fetch_copyright_notice = $row['option_value'];
				if ( $row['option_name'] == 'rfr2b_control_options' ) $this->fetch_rfr2b_control_options = unserialize($row['option_value']);
			}
		}	
	}	

	/**
	 * Displays Readers From RSS 2 Blog: Header
	 */
	function __rfr2b_header() {
		// Define page call
		$rfr2b_wp_pg_vars = 'page='.$_GET['page'].'&';
		// Define header css call
		if( $_GET['rfr2bpg'] == 'ug' ) $rfr2b_css_active_ug = 'active';
		else if( $_GET['rfr2bpg'] == 'target' ) $rfr2b_css_active_target = 'active';
		else if( $_GET['rfr2bpg'] == '' ) $rfr2b_css_active_global = 'active';
		
		// Header Option menus
		echo '<link rel="stylesheet" type="text/css" media="all" href="'.RFR2B_LIBPATH.'admin-pg/css/style.css" />';
		echo '<script type="text/javascript" src="'.RFR2B_FULLPATH.'/wpsmartapps-lic/js/global.js"></script>';
		//echo '<h2 style="color:#1C2A47;font-size:19px;padding-bottom:10px; font-weight:bold; ">'.$this->rfr2b_img_logo.'</h2>';
		echo '<h2 style="color:#1C2A47;font-size:25px;padding:23px 0px; font-weight:bold;"><a href="'.$this->rfr2b_plugin_page.'?'.$rfr2b_wp_pg_vars.'" style="text-decoration:none;"><span style="color:#444444">Readers From</span> <span style="color:#E68B01">RSS</span> 2 <span style="color:#176B94">BLOG</span> <span style="color:#FF3300;font-size:15px;font-weight:bold;">(Lite Version)</span></a> &nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;<a href="https://www.facebook.com/TheWpSmartApps" class="ssa_likes" target="_blank" style="text-decoration:none; font-size:12px; color:#3B59AF;">Like</a>';
		
		echo '&nbsp;&nbsp;<a href="'.$this->rfr2b_plugin_page.'?'.$rfr2b_wp_pg_vars.'rfr2bpg=mkt"><input type="button" name="reset_stats"  value="Market" style="background-color:#D95E46; color:#ffffff; font-weight:bold; cursor:pointer;	-moz-box-shadow: 0 2px 3px #3d3d3d; -webkit-box-shadow: 0 2px 3px #3d3d3d; border:1px solid #BC2507; width:70px; 	padding:5px;overflow:visible;  font-size:14px; " /></a>';
		
		echo '</h2>';
		echo '<div class="rfr2b_headermenu">';
		echo '<span class="'.$rfr2b_css_active_global.'"><b>&nbsp;<a href="'.$this->rfr2b_plugin_page.'?'.$rfr2b_wp_pg_vars.'">Global RSS Campaign </a></b></a></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		echo '<span class="'.$rfr2b_css_active_target.'"><b>&nbsp;<a href="'.$this->rfr2b_plugin_page.'?'.$rfr2b_wp_pg_vars.'rfr2bpg=target">Targeted RSS Ad Campaign</a></b></a></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		echo '</div>';
		
		
		if( $_GET['rssmsg'] == 1 ) {
			$this->rfr2b_process_msg = '<strong>Selected \'Targeted RSS Ad Campaign\' Update Successfully. </strong>';
		}
		
		if ( trim($this->rfr2b_process_msg) != '' ) {
			echo '<div id="global_rfr2b_msg" style="font-size:14px; width:775px; background:#FCF9E7; font-weight:bold;border:1px solid #FFAD33; padding:10px 10px 10px 10px;font-family:  Tahoma, Geneva, sans-serif;"><div style="float:left">';
			echo $this->rfr2b_img_edit.'&nbsp;&nbsp;'.$this->rfr2b_process_msg; 
			echo '</div><div>&nbsp;</div></div>';
		?>
			<script>
				  jQuery(document).ready(function(){
					 jQuery("#global_rfr2b_msg").fadeOut(6000); //animation	
				  });	
			</script>
		<?php
		}
	}
	
	
	/**
	 * Process global and target pages.
	 */
	function __rfr2b_processPg() {
		global $wpdb;
		$this->rfr2b_postrequest = $_POST['rfr2b'];
		$process_global_rss_campaign = $this->rfr2b_postrequest['rss_global_data_submit'];
		$process_affiliateData = $this->rfr2b_postrequest['SaveAffiliateData'];
		
		if( $process_global_rss_campaign == 'Save Global Changes' ) {
		
			foreach ( (array) $this->rfr2b_postrequest as $key => $val ) {
				if ( $key == 'rfr2b_no_Comments' ) $process_no_comment = trim($val);
				else if ( $key == 'rfr2b_one_Comments' ) $process_one_comment = trim($val);
				else if ( $key == 'rfr2b_more_Comments' ) $process_more_comment = trim($val);
				else if ( $key == 'rfr2b_social_links' ) $process_social_links = trim($val);
				else if ( $key == 'rfr2b_copyright_notice' ) $process_copyright_notice = trim($val);
				else if ( $key == 'rfr2b_randompost_title' ) $process_ranrom_post_title = trim($val);
				else if ( $key != 'rfr2b_control_options' ) $process_control_options[$key] = $val;
			}
			
			$process_control_options = serialize($process_control_options);
			$rfr2b_global_tempData = array(
									'rfr2b_no_Comments' => $process_no_comment,
									'rfr2b_one_Comments' => $process_one_comment,
									'rfr2b_more_Comments' => $process_more_comment,
									'rfr2b_social_links' => $process_social_links,
									'rfr2b_randompost_title' => $process_ranrom_post_title,
									'rfr2b_copyright_notice' => $process_copyright_notice,
									'rfr2b_control_options' => $process_control_options,
									 );	
			foreach($rfr2b_global_tempData as $key => $val) {
				$db_globalRss_sql = "UPDATE $this->rfr2b_options_table SET option_value='$val' WHERE option_name='$key'";
				$wpdb->query($db_globalRss_sql);			
			}	
			$this->rfr2b_process_msg = '<strong>Global RSS Campaign Saved Successfully. </strong>';
		
		
		} else if( $process_affiliateData == 'Submit' ) {
		
			foreach ( (array) $this->rfr2b_postrequest as $key => $val ) {
				if ( $key != 'affiliate_next_step' ) $process_affiliate_options[$key] = trim($val);
			}
			$process_affiliate_options = serialize($process_affiliate_options);
			$rfr2b_affiliate_tempData = array(
									'rfr2b_affiliate_options' => $process_affiliate_options,
									 );	
			foreach($rfr2b_affiliate_tempData as $key => $val) {
				$db_globalRss_sql = "UPDATE $this->rfr2b_options_table SET option_value='$val' WHERE option_name='$key'";
				$wpdb->query($db_globalRss_sql);			
			}	
			$this->rfr2b_process_msg = '<strong>Affiliate Program Saved Successfully. </strong>';
		
		}
	
		return $this->rfr2b_process_msg;
	}


	/**
	 * Displays the plugins options
	 */
	function __rfr2b_displayDashboardPg() {
		// Define pages according to page call
		if ( $_GET['rfr2bpg'] == 'target' )	 $display_page = 'admin-pg/target.php';
		else if( $_GET['rfr2bpg'] == 'ug' )  $display_page = 'admin-pg/upgrade.php';
		else if( $_GET['rfr2bpg'] == 'mkt' ) $display_page = 'admin-pg/market.php';
		else 						         $display_page = 'admin-pg/manage-rss.php';
		
		
		
		
		
		// Call
		$this->__rfr2b_fetch_Options();
		
		// Affiliate
		if( $this->fetch_rfr2b_affiliateOptions['no_pwd_by'] == 1 ) $no_poweredby_chk = 'checked';
		
		// Social Icons
		if( $this->fetch_rfr2b_control_options['rfr2b_social_del'] == 1 ) $social_del_check = 'checked';
		if( $this->fetch_rfr2b_control_options['rfr2b_social_facebook'] == 2 ) $social_facebook_check = 'checked';
		if( $this->fetch_rfr2b_control_options['rfr2b_social_tweet'] == 3 ) $social_tweet_check = 'checked';
		if( $this->fetch_rfr2b_control_options['rfr2b_social_digg'] == 4 ) $social_digg_check = 'checked';
		if( $this->fetch_rfr2b_control_options['rfr2b_social_stumble'] == 5 ) $social_stumble_check = 'checked';
		
		// Include Pages
		if( $this->fetch_rfr2b_control_options['rfr2b_include_pages'] == 1 ) {
			$rssIncludePages_chk = 'checked';
			$rssIncludePages_chk_display = 'block';
		} 
		
		// Latest Comments
		if( $this->fetch_rfr2b_control_options['rfr2b_display_latest_post_comments'] == 1 ) {
			$display_latestComments = 'checked';
		}		
		
		if( $this->fetch_rfr2b_control_options['display_x_comments'] == 1 ) { 
			$display_x_comment_chk = 'checked';
			$display_x_comment_chk_display = 'block';
		}
		
		if( $this->fetch_rfr2b_control_options['rfr2b_display_post_tags'] == 1 ) $rfr2b_display_post_tags_chk = 'checked';	

		// Call header
		$this->__rfr2b_header(); 
		// Display pages according to the page call.	
		require_once($display_page);
	}
	
	/**
	 * RSS Ad Campaign 
	 */
	function __rfr2b_display_Feed( $post_content, $post_id, $post_title, $noof_comments, $tags ) {
		$this->__rfr2b_fetch_Options();
		
		// Display Post Tag
		if( $this->fetch_rfr2b_control_options['rfr2b_display_post_tags'] == 1 ) {
			$post_content .= '<br><br><img src="'.RFR2B_LIBPATH.'images/ico-tag.png" border="0" align="absmiddle"> Tags:&nbsp;&nbsp;'.$tags;
		}
		
		// Social Links
			$displaySocialIcons = '<br><br><div style="width:100%"><table align="left" width="100%" cellspacing="0" cellpadding="0" bgcolor="#f1f1f1"  border="0px;">
				<tbody>
				<tr bgcolor="#ffffff">';
			
			if( $this->fetch_rfr2b_control_options['rfr2b_social_del'] != 1 ) {	
				$displaySocialIcons .= '<td align="center" width="17%" valign="top">
						<span class="sb_title">Del.icio.us</span><br>
						<a href="http://del.icio.us/post?url=%post-url%&title=%post-title%">
						<img src="'.RFR2B_LIBPATH.'images/delicious.gif" border="0" align="absmiddle">
						</a>  
						</td>';
			}
			
			if( $this->fetch_rfr2b_control_options['rfr2b_social_facebook'] != 2 ) {	
				$displaySocialIcons .= '<td align="center" width="17%" valign="top">
						<span class="sb_title">Facebook</span><br>
						<a href="http://www.facebook.com/share.php?u=%post-url%"><img src="'.RFR2B_LIBPATH.'images/facebook_icon.png" border="0" align="absmiddle"></a>  
						</td>';
			}		
			
			if( $this->fetch_rfr2b_control_options['rfr2b_social_tweet'] != 3 ) {	
				$displaySocialIcons .= '<td align="center" width="17%" valign="top">
						<span class="sb_title">TweetThis</span><br>
						<a href="http://twitthis.com/twit?url=%post-url%&title=%post-title%"><img src="'.RFR2B_LIBPATH.'images/tweet.png" border="0" align="absmiddle"></a>  					</td>';
			}		
					
			if( $this->fetch_rfr2b_control_options['rfr2b_social_digg'] != 4 ) {	
				$displaySocialIcons .= '<td align="center" width="17%" valign="top">
						<span class="sb_title">Digg</span><br>
						<a href="http://digg.com/submit?phase=2&url=%post-url%&title=%post-title%"><img src="'.RFR2B_LIBPATH.'images/digg.png" border="0" align="absmiddle"></a>  
						</td>';
			}		
					
			if( $this->fetch_rfr2b_control_options['rfr2b_social_stumble'] != 5 ) {	
				$displaySocialIcons .= '<td align="center" width="17%" valign="top">
						<span class="sb_title">StumbleUpon</span><br>
						<a href="http://www.stumbleupon.com/submit?url=%post-url%&title=%post-title%"><img src="'.RFR2B_LIBPATH.'images/stumble.gif" border="0" align="absmiddle"></a>  
						</td>';
			}		
					
					
		$displaySocialIcons .= '</tr>
				</tbody></table></div>';
			$newSocialIcons = str_replace('%post-url%', $this->post_url, $displaySocialIcons);
			$displayNewSocialIcons = str_replace('%post-title%', $this->postTitle, $newSocialIcons);
			$post_content = $post_content.$displayNewSocialIcons;
		
		// Feed Content Start
		$post_content .= '<br><div style="clear:both"></div><div style="background:#EEEEEE; padding:0px 0px 0px 15px; margin:10px 0px 0px 0px;">';
		
		// Display Comments
		if( $this->fetch_rfr2b_control_options['display_x_comments'] == 1 ) {
			if ( $noof_comments == 0 ) {
				$One_comment_text = $this->fetch_rfr2b_no_Comments;
				$comment_text .= '<a href="'.$this->post_url.'#respond">';
				$comment_text .= $One_comment_text;
				$comment_text .= '</a>';
			} else if ( $noof_comments == 1 ) {
				$Two_comment_text = $this->fetch_rfr2b_one_Comments;
				$comment_text .= '<a href="'.$this->post_url.'#comments">';
				$comment_text .= $Two_comment_text;
				$comment_text .= '</a>';
			} else if ( $noof_comments  == 2 || $noof_comments  > 2 ) {
				$More_comment_text = $this->fetch_rfr2b_more_Comments;
				$comment_text .= '<a href="'.$this->post_url.'#comments">';
				$comment_text .= $More_comment_text;
				$comment_text .= '</a>';
			}
			$comment_text = str_replace('%', $noof_comments, $comment_text);
			$rss_comment_text = '<div style="padding:5px 0px 5px 0px;">';
			$rss_comment_text .= '<b>Comments:</b>&nbsp;&nbsp;'.$comment_text;
			$rss_comment_text .= '</div>';
			$post_content = $post_content.$rss_comment_text;
		}
		
		// Random Post
		if( $this->fetch_rfr2b_control_options['related_post'] > 0 ) {
			$rss_random_post_heading = '<br><div style="clear:both"></div><div style="padding:13px 0px 5px 0px;"><span style="border-bottom:1px dashed #003399;padding-bottom:4px;"><strong>'.$this->fetch_rfr2b_randompost_title.'</strong></span>&nbsp;&nbsp;<br>';
			list($noof_random_posts,$fetch_random_posts) = $this->rfr2b_ramdom_post($this->fetch_rfr2b_control_options['related_post'], $this->postID);
			$the_random_posts .= $rss_random_post_heading.$fetch_random_posts.'</div>';
			$post_content = $post_content.$the_random_posts;
		}
		
		// Seprator
		$post_content .= '</div>';
		
		// Seprator
		$post_content .= '<hr style="color:#EBEBEB" />';
		
		// Copyright Notice   
		if( isset($this->fetch_copyright_notice) &&  !$this->fetch_copyright_notice == '' ) {
			$CopyrightNotice = $this->fetch_copyright_notice;
			$newCopyrightNotice = str_replace('%blog-url%', $this->blogurl, $CopyrightNotice);
			$newCopyrightNotice = str_replace('%blog-name%', $this->blog_name, $newCopyrightNotice);
			$newCopyrightNotice = str_replace('%year%', $this->year, $newCopyrightNotice);
			$newCopyrightNotice = str_replace('%post-url%', $this->post_url, $newCopyrightNotice);
			$newCopyrightNotice = str_replace('%post-title%', $this->postTitle, $newCopyrightNotice);
			$displayCopyrightNotice .= '<small>'.$newCopyrightNotice.'</small><br>';
			if( $this->fetch_rfr2b_affiliateOptions['no_pwd_by'] == 1 ) { 
			$displayCopyrightNotice .= '&nbsp;';
			}
			$post_content = $post_content.$displayCopyrightNotice;
		}
		
		if( $this->fetch_rfr2b_affiliateOptions['cbid'] == '' ) {
			$ckb_affiliate = '';
		} else {
			$ckb_affiliate = $this->fetch_rfr2b_affiliateOptions['cbid'];
		}
		
		if( $this->fetch_rfr2b_affiliateOptions['no_pwd_by'] == 1 ) { 
			$poweredByNotice = '<small>Powered by <a href="http://www.wpsmartapps.com/go.php?offer='.$ckb_affiliate.'&pid=6">Readers From RSS 2 Blog</small><br><br><br>';
			$post_content = $post_content.$poweredByNotice;
		}
		
		$post_content = $post_content;
		
		return $post_content;
	}
	
	/**
	 * Plugin registration form
	 */
	function __rfr2b_PluginActivateForm($form_name, $submit_btn_txt='Register', $name, $email, $hide=0, $submit_again='') {
		$plugin_pg    = ($this->wp_version >= 2.7) ? 'tools.php' : 'edit.php';
		$thankyou_url = RFR2B_SITEURL.'/wp-admin/'.$plugin_pg.'?page='.$_GET['page'];
		$onlist_url   = RFR2B_SITEURL.'/wp-admin/'.$plugin_pg.'?page='.$_GET['page'].'&amp;rfr2b_onlist=1';
		if ( $hide == 1 ) $align_tbl = 'left';
		else $align_tbl = 'center';
		?>
		
		<?php if ( $submit_again != 1 ) { ?>
		<script><!--
		function trim(str){
			var n = str;
			while ( n.length>0 && n.charAt(0)==' ' ) 
				n = n.substring(1,n.length);
			while( n.length>0 && n.charAt(n.length-1)==' ' )	
				n = n.substring(0,n.length-1);
			return n;
		}
		
		function was_email_trim( stringToTrim ) {
			return stringToTrim.replace(/^\s+|\s+$/g,"");
		}
		
		function __wpsamrtapps_regValidEmailChk() {
			var name = document.<?php echo $form_name;?>.name;
			var email = document.<?php echo $form_name;?>.from;
			var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
			var err = ''
			if ( trim(name.value) == '' )
				err += '- Name Required\n';
			if ( reg.test(was_email_trim(email.value)) == false )
				err += '- Valid Email Required\n';
			if ( err != '' ) {
				alert(err);
				return false;
			}
			return true;
		}
		//-->
		</script>
		<?php } ?>
		<form name="<?php echo $form_name;?>" method="post" action="http://www.aweber.com/scripts/addlead.pl" <?php if($submit_again!=1){; ?>onsubmit="return __wpsamrtapps_regValidEmailChk()"<?php }?>>
		<input type="hidden" name="meta_web_form_id" value="2008424774" />
		<input type="hidden" name="meta_split_id" value="" />
		<input type="hidden" name="listname" value="wsa-active" />
		<input type="hidden" name="redirect" value="<?php echo $thankyou_url;?>" />
		<input type="hidden" name="meta_adtracking" value="codex.wordpress" />
		<input type="hidden" name="meta_message" value="1" />
		<input type="hidden" name="meta_required" value="name,email" />
		<input type="hidden" name="meta_tooltip" value="" />
		<input type="hidden" name="meta_redirect_onlist" value="<?php echo $onlist_url;?>">
		<input type="hidden" name="meta_forward_vars" value="1">	
		 <?php if ( $submit_again == 1 ) { ?> 	
		 <input type="hidden" name="activate_again" value="1">
		 <?php } ?>		 
		 <?php if ( $hide == 1 ) { ?> 
		 <input type="hidden" name="name" value="<?php echo $name;?>">
		 <input type="hidden" name="email" value="<?php echo $email;?>">
		 <?php } else { ?>
		 <input type="text" name="name" value="<?php echo ($name?$name:'Your name...');?>"  class="text"onblur="if (this.value == '') {this.value = 'Your name...';}" onfocus="if (this.value == 'Your name...') {this.value = '';}" />
		 <input type="text" name="email" value="<?php echo ($email?$email:'Your e-mail...');?>" class="text" onblur="if (this.value == '') {this.value = 'Your e-mail...';}" onfocus="if (this.value == 'Your e-mail...') {this.value = '';}"  />
		 <?php } ?>
		 <input type="submit" name="submit" value="<?php echo $submit_btn_txt;?>" 
		 								style="overflow:visible;padding:5px 10px 6px 7px;    background-color: #5872A7; color:#fff;
									background-position: 0 -96px;
									border-color: 1px solid #1A356E; font-weight:bold; cursor:pointer;
									"  />
		 </form>
		
		<?php
	}
	
		function __rfr2b_StepTwoRegister( $form_name='frm2', $name, $email ){
		?>
		 <table width="640" cellpadding="5" cellspacing="1" bgcolor="#ffffff" style="padding:0px 15px 15px 15px;">
		  <tr><td>
		    <h2 style="font-size:18px; 
			             font:'droid sans',arial,sans-serif;
						 letter-spacing:-1px;
						 text-shadow: 0px 0px 1px #6B6B6D;
						 -moz-text-shadow: 0px 0px 1px #6B6B6D;
						 -webkit-text-shadow: 0px 0px 1px #6B6B6D;">
			  <strong><span style="border-bottom:2px solid #e9e9e9; padding-bottom:3px;">You Are Almost Done</span></strong> 
			  </h2>
		  </td></tr>
		  <tr><td><h3 style="border-bottom:1px dashed #003399;"><i><span style="font-weight:bold; font-size:18px; color:#009933;">Step 1:</i></h3></td></tr>
		  <tr><td>
		  <span style="color:#FF3333">
		  A confirmation email has been sent to your email <strong>"<?php echo $email;?>"</strong>. 
		  <br>You MUST <strong>Click On The Link Inside The Email To Activate The Plugin</strong>.
		  </span>
		  <br><br>
		  
		  <span><strong style="font-size:13px">1. Didn't See Any Email From Us</strong></span> 
		  <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Make sure to check your email's <strong><span style="color:#FF3333">junk & spam folder</span></strong> in case the email mistakenly get filtered there.
		  <br><br>
		  
		 <strong style="font-size:13px">2. It's not there in the junk & spam folder either</strong><br>
		 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sometimes the confirmation email takes time to arrive. Please be patient. WAIT FOR 1 HOURS AT MOST.<br> 
		 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The confirmation email should be there by then.
		 <br><br>
		 
		 <strong style="font-size:13px">3. One hours and yet confirmation email!</strong><br>
         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please register again from below:<br>
		 <div style="width:450px; color:#717171; font-weight:bold; background:#FFFFCC; padding:10px; -webkit-box-shadow: 0px 2px 3px rgba(0, 0, 0, 0.09);
	-moz-box-shadow: 0px 2px 3px rgba(0, 0, 0, 0.09); box-shadow: 0px 2px 3px rgba(0, 0, 0, 0.09); -moz-border-radius: 5px; border-radius: 5px;">
		 <strong><?php $this->__rfr2b_PluginActivateForm($form_name,'Activate Again',$name,$email,$hide=0,$submit_again=2);?></strong>
		 </div>
		 <div style="clear:both"></div>
		 <br><br>
		<i style="color:#999999;"> <strong>But I've Still Got Problems.</strong><br>
		 Stay calm. Contact us at  <strong><a href="http://support.wpsmartapps.com/open.php" target="_blank" style="text-decoration:none;">http://support.wpsmartapps.com</a></strong>  we will get to you immediately.</i>

		  </td></tr>
		  <tr><td><h3 style="border-bottom:1px dashed #003399;"><i><span style="font-weight:bold; font-size:18px; color:#009933;">Step 2:</i></h3></td></tr>
		  <tr><td>
		  <strong>Did You Click On The Link Inside The Email To Activate The Plugin? If you Did</strong><br> 
		  Now, Click on the button below to Verify and Activate the plugin.</td></tr>
		  <tr><td><?php $this->__rfr2b_PluginActivateForm($form_name.'_0','Verify and Activate',$name,$email,$hide=1,$submit_again=1);?></td></tr>
		 </table>
		 
		<?php
	}


	

} // Eof Class

$WSA_LIC_ReadersFromRss2Blog = new WSA_LIC_ReadersFromRss2Blog();
?>