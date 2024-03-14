<?php
/*
Plugin Name: bbPress Notification
Plugin URI:  https://www.bbp.design/product/bbpress-notification-pluginfree/
Description: Send new topic and reply notification to admin email automatically.
Version: 1.4.3
Author: https://www.bbp.design/
Author URI: https://www.bbp.design/
License: GPLv3
*/
if (!defined('ABSPATH'))
{
	exit;
}

/**** localization ****/
add_action('plugins_loaded','bbp_notification_load_textdomain');

function bbp_notification_load_textdomain()
{
	load_plugin_textdomain('bbp-notification', false, dirname( plugin_basename( __FILE__ ) ).'/languages/');
}

add_action('admin_menu', 'bbp_notify_option_menu');

function bbp_notify_option_menu()
{
	add_menu_page(__('bbPress Notification', 'bbp-notification'), __('bbPress Notification', 'bbp-notification'), 'manage_options', 'bbPressnotification', 'bbpress_notification_setting');
	add_submenu_page('bbPressnotification', __('bbPress Notification','bbp-notification'), __('bbPress Notification','bbp-notification'), 'manage_options', 'bbPressnotification', 'bbpress_notification_setting');
}

//!!!start
$bbpdisablebbpnotificationallfeature = get_option('bbpdisablebbpnotificationallfeature');
if ('yes' == $bbpdisablebbpnotificationallfeature)
{
	return;
}
//!!!end

function bbpress_notification_setting()
{

	global $wpdb;
	
	if (isset($_POST['bbpnotificationsubmitnew']))
	{
		
		check_admin_referer( 'bpmo_tomas_bp_members_only_nonce' );
		
		if (isset($_POST['newtopicemail']))
		{
		    //1.4.1
		    $m_newtopicemail = sanitize_textarea_field(trim($_POST['newtopicemail']));
			//$m_newtopicemail = trim($_POST['newtopicemail']);

			if (strlen($m_newtopicemail) == 0)
			{
				delete_option('newtopicemail');
			}
			else
			{
				$m_newtopicemail = sanitize_textarea_field($m_newtopicemail);
				update_option('newtopicemail',$m_newtopicemail);
			}			
		}

		if (isset($_POST['newreplyemail']))
		{
		    //1.4.1
		    $m_newreplyemail = sanitize_textarea_field($_POST['newreplyemail']);
			//$m_newreplyemail = trim($_POST['newreplyemail']);
			if (strlen($m_newreplyemail) == 0)
			{
				delete_option('newreplyemail');
			}
			else
			{
				$m_newreplyemail = sanitize_textarea_field($m_newreplyemail);
				update_option('newreplyemail',$m_newreplyemail);
			}
		}
	
		if (isset($_POST['bbpdisablebbpnotificationallfeature']))
		{
			$bbpdisablebbpnotificationallfeature = sanitize_text_field($_POST['bbpdisablebbpnotificationallfeature']);
			update_option('bbpdisablebbpnotificationallfeature',$bbpdisablebbpnotificationallfeature);
		}
		else
		{
			delete_option('bbpdisablebbpnotificationallfeature');
		}
		
		$bbpdisablebbpnotificationallfeature = get_option('bbpdisablebbpnotificationallfeature');
		
		$bpNotificationMessageString =  __( 'Your changes has been saved.', 'bbp-notification' );
		bbp_notification_message($bpNotificationMessageString);
	}
	echo "<br />";
	
	$m_newtopicemail = get_option('newtopicemail');
	$m_newreplyemail = get_option('newreplyemail');
	?>
	<div style='margin:10px 5px;'>
	<div style='float:left;margin-right:10px;'>
	<img src='<?php echo get_option('siteurl');  ?>/wp-content/plugins/bbpress-notification/images/new.png' style='width:30px;height:30px;'>
	</div> 
	<div style='padding-top:5px; font-size:22px;'> <i></>bbPress Notification Email Settings Panel:</i></div>
	</div>
	<div style='clear:both'></div>		
			<div class="wrap">
				<div id="dashboard-widgets-wrap">
				    <div id="dashboard-widgets" class="metabox-holder">
						<div id="post-body"  style="width:60%;">
							<div id="dashboard-widgets-main-content">
								<div class="postbox-container" style="width:98%;">
									<div class="postbox">
										<h3 class='hndle' style='padding-top:10px;padding-bottom:10px;'><span>
										<?php 
												echo  __( 'bbPress Notification Email Settings:', 'bbp-notification' );
										?>
										</span>
										</h3>
									
										<div class="inside" style='padding-left:10px;'>
											<form id="bpmoform" name="bpmoform" action="" method="POST">
												<?php
												// 1.8.3
												wp_nonce_field('bpmo_tomas_bp_members_only_nonce');
												?>										
											<table id="bpmotable" width="100%">
											<tr>
											<td width="100%" style="padding: 20px;">
											<p>
											<?php echo  __( 'New Topic Email Notification Address:', 'bbp-notification' ); ?>
											</p>
											<?php 
											$m_newtopicemail = get_option('newtopicemail');
											/*
											 * 1.4.1
											 * <textarea name="newtopicemail" id="newtopicemail" cols="70" rows="10" style="width:500px;"><?php echo $m_newtopicemail; ?></textarea>
											 */
											?>											
											<textarea name="newtopicemail" id="newtopicemail" cols="70" rows="10" style="width:500px;"><?php echo esc_textarea($m_newtopicemail); ?></textarea>
											<p><font color="Gray"><i><?php echo  __( 'Enter one URL per line please.', 'bbp-notification' ); ?></i></p>
											<p><font color="Gray"><i><?php echo  __( 'These pages will opened for guest and guest will not be directed to register page.', 'bbp-notification' ); ?></i></p>
											
											</td>
											</tr>
											
											<tr style="margin-top:30px;">
											<td width="100%" style="padding: 20px;">
											<p>
											<?php echo  __( 'New Reply Email Notification Address:', 'bbp-notification' ); ?>
											</p>
											<?php 
											$m_newreplyemail = get_option('newreplyemail');
											/*
											 * 1.4.1
											 * <textarea name="newreplyemail" id="newreplyemail" cols="70" rows="10" style="width:500px;"><?php echo $m_newreplyemail; ?></textarea>
											 */
											?>
											<textarea name="newreplyemail" id="newreplyemail" cols="70" rows="10" style="width:500px;"><?php echo esc_textarea($m_newreplyemail); ?></textarea>
											<p><font color="Gray"><i><?php echo  __( 'Enter one URL per line please.', 'bbp-notification' ); ?></i></p>
											<p><font color="Gray"><i><?php echo  __( 'These pages will opened for guest and guest will not be directed to register page.', 'bbp-notification' ); ?></i></p>					
											</td>
											</tr>
											
										<tr style="margin-top:30px;">
										<td width="100%" style="padding: 20px;">
										<p>
										<?php 
											echo  __( 'Temporarily Turn Off All Featrures:', 'bbp-members-only' );
										?>
										</p>
										<p>
										<?php
										$bbpdisablebbpnotificationallfeature = get_option('bbpdisablebbpnotificationallfeature');
										if (!(empty($bbpdisablebbpnotificationallfeature)))
										{
											echo '<input type="checkbox" id="bbpdisablebbpnotificationallfeature" name="bbpdisablebbpnotificationallfeature"  style="" value="yes"  checked="checked"> Temporarily Turn Off All Featrures Of bbPress Notification ';
 
										}
										else 
										{
											echo '<input type="checkbox" id="bbpdisablebbpnotificationallfeature" name="bbpdisablebbpnotificationallfeature"  style="" value="yes" > Temporarily Turn Off All Featrures Of bbPress Notification ';
										}
										?>
										</p>
										<p><font color="Gray"><i>
										<?php 
										echo  __( '# If you enabled this option, all features of bbPress Notification plugin will be disabled', 'bbp-members-only') ;
										?></i></p>
										</td>
										</tr>
																					
											</table>
											<br />
											<input type="submit" id="bbpnotificationsubmitnew" name="bbpnotificationsubmitnew" value=" Submit " style="margin:1px 20px;">
											</form>
											
											<br />
										</div>
									</div>
								</div>
							</div>
						</div>
	<?php if (function_exists('is_rtl'))
	{
		//better rtl support
		if (is_rtl())
		{
			echo '<div id="post-body"  style="width:40%; float:left;">';
		}
		else
		{
			echo '<div id="post-body"  style="width:40%; float:right;">';
		}
	}
	else 
	{
		echo '<div id="post-body"  style="width:40%; float:right;">';
	}
	?>
					
	<?php 					
	/*
	 * no rtl
	<div id="post-body"  style="width:40%; float:right;">
	*/
	?>
							<div id="dashboard-widgets-main-content">
								<div class="postbox-container" style="width:90%;">
								
								
									<div class="postbox">
										<h3 class='hndle' style='padding: 10px 0px; !important'>
										<span>
										<a class="" target="_blank" href="https://www.bbp.design/shop/">Other Plugins Maybe You Like:</a>
										</span>
										</h3>
									
										<div class="inside" style='padding-left:10px;'>
								<div class="inside">
										<ul>
										<li>
											* <a class="" target="_blank" href="https://www.bbp.design/product/bbpress-members-only-pro-single-site/">bbPress Members Only Membership Plugin</a></b>
											<p> Help you to make your bbPress site only viewable to logged in member users, based on user role.</p>
										</li>
										<li>
											* <a class="" target="_blank" href="https://www.bbp.design/product/bbpress-login-register-pro-single-site/">bbPress Login Pro Plugin</a></b>
											<p> Help your bbpress forums more friendly for users, stop brute force attacks on your bbpress forums, make your login / register pages more beautiful via preset preset wallpapers, login and logout auto redirect based on user roles, and blocks spam-bots to protect </p>
										</li>
										<li>
											* <a class="" target="_blank" href="https://www.bbp.design/product/bbpress-most-liked-topics-plugin/">bbPress Most Liked Topics Plugin</a></b>
											<p> The plugin add a like button to bbPress topics and replies, bbPress forum members can like topics and replies, When users View forum topic, he will find most liked replies at the top of the topic page, show most valuable replies to users is a good way to let users like and join in your forum</p>
										</li>
										<li>
											* <a class="" target="_blank" href="https://www.bbp.design/product/bbpress-woocommerce-payment-gateway-plugin/">bbPress WooCommerce Payment Gateway Plugin</a></b>
											<p> A bbPress plugin to integrate WooCommerce Payment Gateway to help webmaster charge money from users of bbPress forums.</p>
										</li>
										<li>
											* <a class="" target="_blank" href="https://www.bbp.design/product/bbpress-blacklist-whitelist-security-plugin-product/">bbPress Blacklist Plugin</a></b>
											<p> A bbPress plugin which allow you build a blacklist to prevent spammers register as your users..</p>
										</li>
										<li>
											* <a class="" target="_blank" href="https://www.bbp.design/product/bbpress-new-user-approve/">bbPress New User Approve</a></b>
											<p> When users register as members, they need awaiting administrator approve their account manually, at the same time when unapproved users try to login your site, they can not login your site and they will get a message that noticed they have to waiting for admin approve their access first</p>
										</li>
										<li>
											* <a class="" target="_blank" href="https://www.bbp.design/product/bbpress-google-xml-sitemaps-generator/">bbPress Google XML Sitemaps Generator</a></b>
											<p> generate google xml sitemap for your bbpress site, include all wordpress posts and wordpress pages, and include all bbpress topics and all bbpress replies</p>
										</li>
										<li>
											* <a class="" target="_blank" href="https://www.bbp.design/product/bbpress-new-user-must-to-do/">bbPress New User Must To Do Plugin</a></b>
											<p>  force new bbPress users to something first, for example, post a post in the welcome forum, to introduce themselves, before they can post topic, reply topics… and so on</p>
										</li>
										<li>
											* <a class="" target="_blank" href="https://www.bbp.design/product/bbpress-user-post-management/">bbPress User Post Management</a></b>
											<p>  one click to bulk manage bbPress users’ topics and replies easier</p>
										</li>						
										<li>
											* <a class="" target="_blank" href="https://www.bbp.design/product/customize-bbpress/">Customize bbPress</a></b>
											<p>help you to customize bbPress forums to build a user friend front end for your bbPress forums.</p>
										</li>
											</div>									
										</div>
									</div>
									<div class="postbox">
										<h3 class='hndle' style='padding: 20px 0px; !important'>
										<span>
										<?php 
												echo  __( 'bbPress Plugin Tips Feed:', 'bbp-notification' );
										?>
										</span>
										</h3>
									
										<div class="inside" style='padding-left:10px;'>
							<?php 
								wp_widget_rss_output('https://tomas.zhu.bz/feed/', array(
								'items' => 3, 
								'show_summary' => 0, 
								'show_author' => 0, 
								'show_date' => 1)
								);
							?>
											<br />
										</div>
									</div>
								</div>
							</div>
												
						</div>
						<div style='clear:both'></div>					
			    	</div>
				</div>
			</div>
			<div style="clear:both"></div>
			<br />
	
			
			
			<?php
}		

function bbp_notification_message($p_message)
{
	echo "<div id='message' class='updated fade' style='line-height: 30px;margin-left: 0px;'>";
	echo $p_message;
	echo "</div>";
}


function new_topic_notification($topic_id = 0, $forum_id = 0, $anonymous_data = false, $topic_author = 0)
{
	$admin_email = get_option('admin_email');
	
	$blog_name = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
	$topic_title = html_entity_decode(strip_tags(bbp_get_topic_title($topic_id)), ENT_NOQUOTES, 'UTF-8');
	$topic_content = html_entity_decode(strip_tags(bbp_get_topic_content($topic_id)), ENT_NOQUOTES, 'UTF-8');
	$topic_excerpt = html_entity_decode(strip_tags(bbp_get_topic_excerpt($topic_id, 100)), ENT_NOQUOTES, 'UTF-8');
	$topic_author = bbp_get_topic_author($topic_id);
	$topic_url = bbp_get_topic_permalink($topic_id);
	$topic_reply = bbp_get_reply_url($topic_id);

	$email_subject = $blog_name. " New Topic Alert: ".$topic_title;

	$email_body = $blog_name.": $topic_title\n\r";
	$email_body .= $topic_content;
	$email_body .= "\n\r--------------------------------\n\r";
	$email_body .= "Topic Url: ".$topic_url."\n\rAuthor: $topic_author". "\n\rYou can reply at: $topic_reply";

	//@wp_mail( $admin_email, $email_subject, $email_body );
	
	$m_newtopicemail = get_option('newtopicemail');
	if (empty($m_newtopicemail))
	{
		@wp_mail( $admin_email, $email_subject, $email_body );
	}
	else 
	{
		$m_topicemailarray = explode("\n", trim($m_newtopicemail));
		
		if ((is_array($m_topicemailarray)) && (count($m_topicemailarray) > 0))
		{
			foreach ($m_topicemailarray as $m_topicemailsingle)
			{
				$m_topicemailsingle = trim($m_topicemailsingle);

				if (empty($m_topicemailsingle))
				{
					@wp_mail( $admin_email, $email_subject, $email_body );
				}
				else 
				{
					@wp_mail( $m_topicemailsingle, $email_subject, $email_body );
				}

			}
		}
	}
}


function new_reply_notification( $reply_id = 0, $topic_id = 0, $forum_id = 0, $anonymous_data = false, $reply_author = 0, $is_edit = false, $reply_to = 0 ) 
{

	$admin_email = get_option('admin_email');
		
	$user_id  = (int) $reply_author_id;
	$reply_id = bbp_get_reply_id( $reply_id );
	$topic_id = bbp_get_topic_id( $topic_id );
	$forum_id = bbp_get_forum_id( $forum_id );
		
	$email_subject = get_option('bbpress_notify_newreply_email_subject');
	$email_body = get_option('bbpress_notify_newreply_email_body');

	$blog_name = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
	$topic_title = html_entity_decode(strip_tags(bbp_get_topic_title($topic_id)), ENT_NOQUOTES, 'UTF-8');
	$topic_content = html_entity_decode(strip_tags(bbp_get_topic_content($topic_id)), ENT_NOQUOTES, 'UTF-8');
	$topic_excerpt = html_entity_decode(strip_tags(bbp_get_topic_excerpt($topic_id, 100)), ENT_NOQUOTES, 'UTF-8');
	$topic_author = bbp_get_topic_author($topic_id);
	$topic_url = bbp_get_topic_permalink($topic_id);
	$topic_reply = bbp_get_reply_url($topic_id);
	$reply_url     = bbp_get_reply_url( $reply_id );
	$reply_content = get_post_field( 'post_content', $reply_id, 'raw' );
	//before 1.4.3 $reply_author = bbp_get_topic_author($user_id);
	//1.4.3
	$reply_author = bbp_get_reply_author_display_name( $reply_id );
	//end 1.4.3
	
	
	
	$email_subject = $blog_name. " New Reply Alert: ".$topic_title;

	$email_body = $blog_name.": $topic_title\n\r";
	$email_body .= $reply_content;
	$email_body .= "\n\r--------------------------------\n\r";
	$email_body .= "Reply Url: ".$reply_url."\n\rAuthor: $reply_author". "\n\rYou can reply at: $reply_url";

	//@wp_mail( $admin_email, $email_subject, $email_body );
	
	$m_newreplyemail = get_option('newreplyemail');
	if (empty($m_newreplyemail))
	{
		@wp_mail( $admin_email, $email_subject, $email_body );
	}
	else
	{
		$m_topicemailarray = explode("\n", trim($m_newreplyemail));
	
		if ((is_array($m_topicemailarray)) && (count($m_topicemailarray) > 0))
		{
			foreach ($m_topicemailarray as $m_topicemailsingle)
			{
				$m_topicemailsingle = trim($m_topicemailsingle);
	
				if (empty($m_topicemailsingle))
				{
					@wp_mail( $admin_email, $email_subject, $email_body );
				}
				else
				{
					@wp_mail( $m_topicemailsingle, $email_subject, $email_body );
				}
	
			}
		}
	}	
}


add_action('bbp_new_topic', 'new_topic_notification');
add_action('bbp_new_reply', 'new_reply_notification');

