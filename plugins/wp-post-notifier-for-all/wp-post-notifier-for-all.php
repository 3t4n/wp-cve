<?php
/*
Plugin Name: WP Post Notifier For All
Plugin URI: http://faycaltirich.blogspot.com/1979/01/wp-post-notifier-for-all.html
Description: Notify all Wordpress users (and not only the admin) on post publishing. The notification is sent only one time after the first post publishing(not on every update).
Version: 2.7.1
Author: Fayçal Tirich
Author URI: http://faycaltirich.blogspot.com
*/

define("WPNFA_ACTIVATION-DATE", "wp-post-notifier-for-all_first-activation-date");
define("WPNFA_NOTIFIED-POSTS", "wp-post-notifier-for-all_notified-posts");
define("WPNFA_EXCLUDE", "wp-post-notifier-for-all_exclude");
define("WPNFA_FROM-TPL", "wp-post-notifier-for-all_from-tpl");
define("WPNFA_SUBJECT-TPL", "wp-post-notifier-for-all_subject-tpl");
define("WPNFA_BODY-TPL", "wp-post-notifier-for-all_body-tpl");
define("WPNFA_EXCLUDE-OWNER", "wp-post-notifier-for-all_exclude-owner");
define("WPNFA_STRIP-TAGS", "wp-post-notifier-for-all_strip-tags");

$pnfa_from_tpl = "Name <iLove@gmail.com>";
$pnfa_subject_tpl = "[BLOG_NAME] - [AUTHOR] just published a new article: [TITLE]";
$pnfa_body_tpl=<<<EOD
[AUTHOR] just published a new article !<br /><br />
<h3>[TITLE]</h3>
In: [CATEGORIES]<br /><br />
[EXCERPT]<br /><br />
[CONTENT]<br /><br />
[LINK]<br /><br />
Good reading !<br /><br />
EOD;

function pnfa_get_users() {
    global $wpdb;
    $blog_users = array();
    $users = get_users();
    foreach($users as $user) {
        $object = new stdClass();
        $object->ID = $user->ID;
        $object->user_login = $user->user_login;
        $object->display_name = $user->display_name;
        $object->user_email = $user->user_email;
        $blog_users[$user->ID]=$object;
        $isExcluded = 0;
        $savedIsExcluded = get_user_meta($user->ID, constant("WPNFA_EXCLUDE"), true);
        if(!empty($savedIsExcluded) && $savedIsExcluded==1){
            $isExcluded = 1;
        }
        $object->isExcluded = $isExcluded;
    }
    return $blog_users;
}

$pnfa_otions_msg = '';
if ( isset($_POST['pnfa_submit']) ) {
    update_option(constant("WPNFA_FROM-TPL"), htmlentities(stripslashes_deep(trim($_POST['pnfa_from'])),ENT_QUOTES, "UTF-8"));
    update_option(constant("WPNFA_SUBJECT-TPL"), htmlentities(stripslashes_deep(trim($_POST['pnfa_subject'])),ENT_QUOTES, "UTF-8"));
    update_option(constant("WPNFA_BODY-TPL"), htmlentities(stripslashes_deep(trim($_POST['pnfa_body'])),ENT_QUOTES, "UTF-8"));
    update_option(constant("WPNFA_EXCLUDE-OWNER"), htmlentities(stripslashes_deep(trim($_POST['pnfa_exclude_owner'])),ENT_QUOTES, "UTF-8"));
    update_option(constant("WPNFA_STRIP-TAGS"), htmlentities(stripslashes_deep(trim($_POST['pnfa_strip_tags'])),ENT_QUOTES, "UTF-8"));
    $pnfa_otions_msg = '<span style="color:green">'.__('Options updated').'</span><br />';
}

$pnfa_from = get_option(constant("WPNFA_FROM-TPL"), $pnfa_from_tpl);
$pnfa_subject = get_option(constant("WPNFA_SUBJECT-TPL"), $pnfa_subject_tpl);
$pnfa_body = get_option(constant("WPNFA_BODY-TPL"), $pnfa_body_tpl);
$pnfa_exclude_owner = get_option(constant("WPNFA_EXCLUDE-OWNER"), false);
$pnfa_strip_tags = get_option(constant("WPNFA_STRIP-TAGS"), false);

function pnfa_notify_users($post_ID) {
    global $pnfa_from, $pnfa_subject, $pnfa_body, $pnfa_exclude_owner, $pnfa_strip_tags;
    $process = 0;
    $post = get_post($post_ID);
    //check if the post was already notified
    $notified_posts = get_option(constant("WPNFA_NOTIFIED-POSTS"));
    if (!is_array($notified_posts)){
        $notified_posts = array();
        update_option(constant("WPNFA_NOTIFIED-POSTS"), $notified_posts);
        $process = 1;
    } else {
        if (in_array($post_ID, $notified_posts)) {
            $process = 0;
        } else {
            $process = 1;
        }
    }
    
    if ($process==1){
        //only notify for new posts
        $activation_date = get_option(constant("WPNFA_ACTIVATION-DATE"));
        if(strtotime($post->post_date)<strtotime($activation_date)) {
            $process = 0;
        }
    }

    if ($process == 1){
        $users = pnfa_get_users();
        foreach($users as $user) {
            if (!$user->isExcluded) {
                $emails[] = $user->user_email;
            }
        }

        $author = get_the_author_meta('display_name',$post->post_author);
        $author_email = get_the_author_meta('user_email',$post->post_author);

        $email_pnfa_subject = str_replace('[AUTHOR]', htmlspecialchars_decode($author), $pnfa_subject);
        $email_pnfa_subject = str_replace('[BLOG_NAME]',html_entity_decode(get_bloginfo('name'), ENT_QUOTES), $email_pnfa_subject);
        $email_pnfa_subject = str_replace('[TITLE]', htmlspecialchars_decode($post->post_title), $email_pnfa_subject);

        $email_pnfa_body = str_replace('[AUTHOR]', htmlspecialchars_decode($author), $pnfa_body);

        $link = '<a style="color: #2D83D5" href="'.get_permalink($post_ID).'">'.get_permalink($post_ID).'</a>';
        $email_pnfa_body = str_replace('[LINK]', $link, $email_pnfa_body);
		
        
        $email_pnfa_body = str_replace('[TITLE]', htmlspecialchars_decode($post->post_title), $email_pnfa_body);
        
        $excerpt = nl2br(htmlspecialchars_decode($post->post_excerpt));
        $content = nl2br(htmlspecialchars_decode($post->post_content));
        if ($pnfa_strip_tags){
            $excerpt = strip_tags( $excerpt, "<br>" );
            $content = strip_tags( $content, "<br>" );
        }
        $email_pnfa_body = str_replace('[EXCERPT]', $excerpt, $email_pnfa_body);
        $email_pnfa_body = str_replace('[CONTENT]', $content, $email_pnfa_body);
        
        $email_pnfa_body = str_replace('[BLOG_NAME]',html_entity_decode(get_bloginfo('name'), ENT_QUOTES), $email_pnfa_body);
        
        $post_categories = wp_get_post_categories( $post_ID , array('fields' => 'all'));
        $cats = '';
        foreach ($post_categories as $cat){
        	$cats .= $cat->name.', ';
        }
        $cats = substr($cats,0,-2); 
        $email_pnfa_body = str_replace('[CATEGORIES]', htmlspecialchars_decode($cats), $email_pnfa_body);

        //
        $email_pnfa_from = html_entity_decode($pnfa_from, ENT_QUOTES, "UTF-8");
        $message_headers = "From: ".$email_pnfa_from."\r\n";
        $message_headers .= "MIME-Version: 1.0\n";
        $message_headers .= "Content-type: text/html; charset=UTF-8\r\n"; 

        $message .= "<html>\n";
        $message .= "<body style=\"font-family:Verdana, Verdana, Geneva, sans-serif; font-size:12px; color:#666666;\">\n";
        $message .= html_entity_decode($email_pnfa_body);
        $message .= "\n\n";
        $message .= "</body>\n";
        $message .= "</html>\n";

        add_filter('wp_mail_charset', 'pnfa_get_mail_charset');
        foreach ( $emails as $email ){
            if( $email == $author_email ){
                if( $pnfa_exclude_owner ){
                    continue ;
                }
            }
            @wp_mail($email, $email_pnfa_subject, $message, $message_headers );
        }
        remove_filter('wp_mail_charset', 'pnfa_get_mail_charset');

        $notified_posts[] = $post_ID;
        sort($notified_posts);
        update_option(constant("WPNFA_NOTIFIED-POSTS"), $notified_posts);
    }
    return $post_ID;
}

function pnfa_get_mail_charset(){
    return "UTF-8";
}

// Options page
function pnfa_options() {
    global $pnfa_from_tpl, $pnfa_from, $pnfa_body_tpl, $pnfa_subject_tpl, $pnfa_body, $pnfa_subject, $pnfa_otions_msg, $pnfa_exclude_owner, $pnfa_strip_tags ;
    if ( isset($_POST['pnfa_exclude_submit']) ) {
        $users_to_exclude_array = array();
        if(isset($_POST['pnfa_excluded_users'])) {
            $users_to_exclude_array = $_POST['pnfa_excluded_users'];
        }
        $users = pnfa_get_users();
        $log = '';
        foreach($users as $user) {
            if (in_array($user->ID, $users_to_exclude_array)) {
                if (!$user->isExcluded){
                    update_user_meta($user->ID,constant("WPNFA_EXCLUDE"),1);
                    $log = $log .'<span style="color:green">'.$user->display_name.' excluded</span><br />';
                }
            } else {
                if ($user->isExcluded){
                    update_user_meta($user->ID,constant("WPNFA_EXCLUDE"),0);
                    $log = $log .'<span style="color:green">'.$user->display_name.' will be notified</span><br />';
                }
            }
        }
        if ($log!=''){
            $pnfa_otions_msg = $log;
        }
    }
    if(!empty($pnfa_otions_msg)) {
        ?>
<!-- Last Action -->
<div id="message" class="updated fade">
	<p>
	<?php echo $pnfa_otions_msg; ?>
	</p>
</div>
	<?php
    }
    ?>
<style type="text/css">
.excluded {
	background-color: #FF9999;
}

.defaultText {
	font-size: smaller !important;
	margin-bottom: 20px !important;
}
</style>
<div class="wrap">
<?php screen_icon(); ?>
	<h2>Post Notifier For All</h2>
	<form method="post" action="">
		<table class="widefat">
			<thead>
				<tr>
					<th>Email Notification Template</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<div>
							<label for="pnfa_from"><strong>Email "From" Template</strong> </label>
							<br /> <input type="text" size="150" id="pnfa_from"
								name="pnfa_from" value="<?php echo $pnfa_from; ?>" />
							<p class="defaultText">
								Default:<br />
								<?php
								$temp = $pnfa_from_tpl ;
								$temp = str_replace("<","&lt;",$temp);
								$temp = str_replace(">","&gt;",$temp);
								echo nl2br($temp);
								?>
							</p>
						</div>
						<div>
							<label for="pnfa_subject"><strong>Email "Subject" Template</strong>
							</label> <br /> <input type="text" size="150" id="pnfa_subject"
								name="pnfa_subject" value="<?php echo $pnfa_subject; ?>" />
							<p class="defaultText">
								Default:<br />
								<?php echo $pnfa_subject_tpl; ?>
							</p>
						</div>
						<div>
							<label for="pnfa_body"><strong>Email "Body" Template</strong> </label>
							<br />
							<textarea style="width: 90%; font-size: 12px;" rows="8" cols="60"
								id="pnfa_body" name="pnfa_body"><?php echo $pnfa_body; ?></textarea>
							<p class="defaultText">
								Default:<br />
								<?php
								$temp = $pnfa_body_tpl ;
								$temp = str_replace("<","&lt;",$temp);
								$temp = str_replace(">","&gt;",$temp);
								echo nl2br($temp);
								?>
							</p>
						</div>
						<div>
							<label for="pnfa_exclude_owner"><strong>Exclude email owner from notification</strong>
							</label>  <input type="checkbox" id="pnfa_exclude_owner"
								name="pnfa_exclude_owner" <?php echo ($pnfa_exclude_owner)?'checked':''; ?> value="1" />
						</div>
						<div>
							<label for="pnfa_strip_tags"><strong>Strip email content tags</strong>
							</label>  <input type="checkbox" id="pnfa_strip_tags"
								name="pnfa_strip_tags" <?php echo ($pnfa_strip_tags)?'checked':''; ?> value="1" />
						</div>
						<p class="submit">
							<input class="button-primary" type="submit" name="pnfa_submit"
								class="button" value="<?php _e('Save Changes'); ?>" />
						</p>
					</td>
				</tr>
			</tbody>
		</table>
	</form>
	<br />
	<?php include 'donate.php';?>
	<h2>Exclude users</h2>
	<form method="post" action="">
		<table class="widefat fixed" cellspacing="0">
			<thead>
				<tr class="thead">
					<th id="cb" class="manage-column column-cb column-exclude" style=""
						scope="col"><?php echo __('Exclude'); ?>?</th>
					<th id="username" class="manage-column column-username" style=""
						scope="col"><?php echo __('Username'); ?>
					</th>
					<th id="email" class="manage-column column-email" style=""
						scope="col"><?php echo __('Email'); ?>
					</th>
				</tr>
			</thead>

			<tfoot>
				<tr class="thead">
					<th id="cb" class="manage-column column-cb column-exclude" style=""
						scope="col"><?php echo __('Exclude'); ?>?</th>
					<th id="username" class="manage-column column-username" style=""
						scope="col"><?php echo __('Username'); ?>
					</th>
					<th id="email" class="manage-column column-email" style=""
						scope="col"><?php echo __('Email'); ?>
					</th>
				</tr>
			</tfoot>

			<tbody id="users" class="list:user user-list">
			<?php
			$style = '';
			$users = pnfa_get_users();
			$normalStyle='';
			foreach($users as $user) {
			    $normalStyle = ( ' class="alternate"' == $normalStyle ) ? '' : ' class="alternate"';
			    $excludedStyle = ' class="alternate excluded" ';
			    if($normalStyle==''){
			        $excludedStyle = ' class="excluded" ';
			    }
			    ?>
				<tr id='user-<?php echo $user->ID; ?>'
				<?php echo ($user->isExcluded)?$excludedStyle:$normalStyle ?>>
					<th scope='row' class='check-column'><input type='checkbox'
						name='pnfa_excluded_users[]' id='user_<?php echo $user->ID; ?>'
						<?php echo ($user->isExcluded)?"checked":""; ?>
						value='<?php echo $user->ID; ?>' />
					</th>
					<td><?php echo $user->user_login; ?></td>
					<td><?php echo $user->user_email; ?></td>
				</tr>
				<?php
			}
			?>
			</tbody>
		</table>
		<p class="submit">
			<input class="button-primary" type="submit"
				name="pnfa_exclude_submit" class="button"
				value="<?php _e('Save Changes'); ?>" />
		</p>
	</form>
	<script type="text/javascript">
			jQuery(document).ready(function() { 
				jQuery("[name='pnfa_excluded_users[]']").click(function(){
					if(jQuery(this).is(":checked")){
						jQuery(this).parents("tr").addClass("excluded");
					}else {
						jQuery(this).parents("tr").removeClass("excluded");
					}
				});
			});
	</script>
</div>
			<?php
}

function pnfa_user_options() {
    global $user_ID;
    $isExcluded = (bool) get_user_meta($user_ID,constant("WPNFA_EXCLUDE"),true);
    $text = '';
    get_currentuserinfo();
    if ( isset($_POST['pnfa_user_submit']) ) {
        if(isset($_POST['pnfa_user_active']) && $_POST['pnfa_user_active']=='true') {
            if (!$isExcluded){
                $isExcluded = 1;
            }
        } else {
            if ($isExcluded){
                $isExcluded = 0;
            }
        }
        update_user_meta($user_ID, constant("WPNFA_EXCLUDE"), $isExcluded);
        $text = '<span style="color:green">'.__('Option updated').'</span><br />';
    }
    if(!empty($text)) { echo '<!-- Last Action --><div id="message" class="updated fade"><p>'.$text.'</p></div>'; }
    ?>
<div class="wrap">
<?php screen_icon(); ?>
	<h2>Post Notifier</h2>
	<br /> <br />
	<form method="post" action="">
		<table class="widefat">
			<thead>
				<tr>
					<th>Disable new posts notification</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><input type="checkbox" name="pnfa_user_active" value="true"
					<?php if($isExcluded) echo ' checked="checked"'; ?> />&nbsp;<?php _e('Check to disable further notifications'); ?>
						<p class="submit">
							<input class="button-primary" type="submit"
								name="pnfa_user_submit" class="button"
								value="<?php _e('Save Changes'); ?>" />
						</p>
					</td>
				</tr>
			</tbody>
		</table>
	</form>
</div>
					<?php
}

function pnfa_activation() {
    $first_date = get_option(constant("WPNFA_ACTIVATION-DATE"));
    if (!isset($first_date) || empty($first_date)){
        update_option(constant("WPNFA_ACTIVATION-DATE"), date("Y-m-d H:m:s"));
    }
}
register_activation_hook( __FILE__, 'pnfa_activation' );

function pnfa_menu() {
    if (function_exists('add_options_page')) {
        if( current_user_can('manage_options') ) {
            add_options_page(__('Post Notifier'), __('Post Notifier'), 'manage_options', __FILE__, 'pnfa_options') ;
        }
    }
    if (function_exists('add_submenu_page')) {
        add_submenu_page('users.php', __('Post Notifier'), __('Post Notifier'), 'read', __FILE__, 'pnfa_user_options');
    }
}

add_action('admin_menu', 'pnfa_menu');
add_action('publish_post', 'pnfa_notify_users' );
?>