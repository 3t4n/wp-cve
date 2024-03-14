<?php

/*
 *
 * Copyright (c) 2008, 2016 Predrag Supurović
 *
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, without
 * modification, are permitted provided that the following conditions
 * are met:
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer
 *    in the documentation and/or other materials provided with the
 *    distribution.
 * 3. The name of the author may not be used to endorse or promote
 *    products derived from this software without specific prior
 *    written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS
 * OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED.  IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
 * GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER
 * IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR
 * OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN
 * IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 */


/*
Plugin Name: Stop Comment Spam
Plugin URI: http://pedja.supurovic.net/stop-comment-spam
Description: Treats comments by predefined rules to stop spam. <a href="/wp-admin/options-general.php?page=stop-comment-spam-options">Settings</a>
Author: Predrag Supurović
Version: 0.5.3
Author URI: http://pedja.supurovic.net
*/

add_action('admin_menu', 'scs_add_page');
add_action('rightnow_end', 'scs_dashboard');

$g_scs_plugin_active_option = 'scs_plugin_active';
$g_scs_plugin_active = get_option($g_scs_plugin_active_option);
if (gettype ($g_scs_plugin_active) == 'boolean') $g_scs_plugin_active  = 'on';

$g_scs_content_rules_option = 'scs_content_rules';
$g_scs_content_rules = get_option($g_scs_content_rules_option);
if (gettype ($g_scs_content_rules) == 'boolean') $g_scs_content_rules = "ы
ю
щ
я
э
ь
й
ё
что
все
Все
Мне
мне
автор
Что
viagra
sex
gambling";

$g_scs_url_rules_option = 'scs_url_rules';
$g_scs_url_rules = get_option($g_scs_url_rules_option);

if (gettype ($g_scs_url_rules) == 'boolean') $g_scs_url_rules = "drugstore
mail
loan
finance
insurance
viagra
baidu.com
clearance
forum
xxx
topic
gscraper
jimdo
nikeschuhe
jordan
discount
money
pharmacy";

$g_scs_link_count_option = 'scs_link_count';
$g_scs_link_count = get_option($g_scs_link_count_option);
if (gettype ($g_scs_link_count) == 'boolean') $g_scs_link_count = 20;

$g_scs_blocked_spam_count_option = 'scs_blocked_spam_count';
$g_scs_blocked_spam_count = get_option($g_scs_blocked_spam_count_option);
if (gettype ($g_scs_blocked_spam_count) == 'boolean') $g_scs_blocked_spam_count = 0;

if ($g_scs_plugin_active == 'on') {
	add_filter('preprocess_comment', 'scs_check_comment_spam', 2, 1);
}



function scs_add_page() {

    // Add a new submenu under Options:
    add_options_page('StopCommentSpam', 'Stop Comment Spam', 8, 'stop-comment-spam-options', 'scs_options_page');
	
}	

function scs_dashboard() {
	global $submenu, $wp_db_version, $g_scs_blocked_spam_count;

	echo '<p class="scs-right-now"><a href="http://pedja.supurovic.net/stop-comment-spam/">Stop Comment Spam</a> protected your blog from ' . $g_scs_blocked_spam_count . ' spam attempts.</p>' . "\n";
}



function scs_check_comment_spam ($content) {
	global $g_scs_content_rules;
	global $g_scs_url_rules;	
	global $g_scs_link_count;

	global $g_scs_blocked_spam_count_option;
	global $g_scs_blocked_spam_count;
	
	
	$m_forbiden_content = preg_split ("/[\n\r]+/", $g_scs_content_rules);
	foreach ($m_forbiden_content as $m_item) {
		if (! empty ($m_item)) {
			$m_pos = mb_strpos ($content['comment_content'], $m_item);
			if ($m_pos == true) {
				$g_scs_blocked_spam_count = $g_scs_blocked_spam_count + 1;
				update_option($g_scs_blocked_spam_count_option, $g_scs_blocked_spam_count);
				wp_die('Your comment has been rejected. It failed to pass our spam checking system');
			}
		}
	}
	
	$m_forbiden_url = preg_split ("/[\n\r]+/", $g_scs_url_rules);
	foreach ($m_forbiden_url as $m_item) {
		if (! empty ($m_item)) {
			$m_pos = mb_strpos ($content['comment_author_url'], $m_item);
			if ($m_pos == true) {
				$g_scs_blocked_spam_count = $g_scs_blocked_spam_count + 1;
			 	update_option($g_scs_blocked_spam_count_option, $g_scs_blocked_spam_count);
				wp_die('Your comment has been rejected. It failed to pass our spam checking system');
			}
		}
	}

	$m_link_count = preg_match_all("/http:\/\/|www\./", $content['comment_content'], $m_matches);
	if ($m_link_count > $g_scs_link_count) {

//echo $content['comment_content'];
//echo "###$m_link_count - $g_scs_link_count###";

		$g_scs_blocked_spam_count = $g_scs_blocked_spam_count + 1;
		update_option($g_scs_blocked_spam_count_option, $g_scs_blocked_spam_count);
		wp_die('Your comment has been rejected. It failed to pass our spam link count checking system');
	}
	
	return $content;
}




// scs_options_page() displays the page content for the Options submenu
function scs_options_page() {
	global $g_scs_content_rules_option;
	global $g_scs_content_rules;
	global $g_scs_plugin_active_option;
	global $g_scs_plugin_active;
	global $g_scs_url_rules_option;
	global $g_scs_url_rules;
	global $g_scs_link_count_option;
	global $g_scs_link_count;
	global $g_scs_blocked_spam_count_option;
	global $g_scs_blocked_spam_count;


	if( ! empty ($_POST['Submit']) ) {
	
        $g_scs_plugin_active = sanitize_textarea_field($_POST[$g_scs_plugin_active_option]);
        update_option($g_scs_plugin_active_option, $g_scs_plugin_active);

        $g_scs_content_rules = sanitize_textarea_field($_POST[$g_scs_content_rules_option]);
        update_option($g_scs_content_rules_option, $g_scs_content_rules);

        $g_scs_url_rules = sanitize_textarea_field($_POST[$g_scs_url_rules_option]);
        update_option($g_scs_url_rules_option, $g_scs_url_rules);
		
        $g_scs_link_count = sanitize_textarea_field($_POST[$g_scs_link_count_option]);
        update_option($g_scs_link_count_option, $g_scs_link_count);


?>
<div class="updated"><p><strong><?php _e('Options saved.'); ?></strong></p></div>
<?php

	}
?>
<div class="wrap">
<h2><?php __( 'SrbTransLat Plugin Options') ?></h2>
<form name="form1" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
<table class="form-table">
<tr>
<th scope="row"></th>
<td>
<input name="<?php echo $g_scs_plugin_active_option; ?>" type="checkbox" <?php echo $g_scs_plugin_active=='on' ? 'checked="checked"' : '' ?>> <?php _e("Stop Content Spam active"); ?><br />
</td>
</tr>
<tr>
<th scope="row"><?php _e("Forbidden items in comment contents"); ?>:</th>
<td>
<textarea name="<?php echo $g_scs_content_rules_option; ?>" rows="10" cols="55"><?php echo $g_scs_content_rules; ?></textarea><br />
<?php _e("Enter each forbidden string into separate line."); ?>
</td>
</tr>
</tr>
<tr>
<th scope="row"><?php _e("Forbidden items in comment url"); ?>:</th>
<td>
<textarea name="<?php echo $g_scs_url_rules_option; ?>" rows="10" cols="55"><?php echo $g_scs_url_rules; ?></textarea><br />
<?php _e("Enter each forbidden string into separate line."); ?>
</td>
</tr>
<tr>
<th scope="row"><?php _e("Maximum allowed links"); ?>:</th>
<td>
<input name="<?php echo $g_scs_link_count_option; ?>" value="<?php echo $g_scs_link_count; ?>" size="5" maxlength="3">
<br />
<?php _e("More links in comment than specified here means comment is spam."); ?>
</td>
</tr>



<tr>
<th scope="row"></th>
<td>
<?php _e("Count of blocked spam"); ?>: <?php echo $g_scs_blocked_spam_count; ?>
</td>
</tr>
</table>
<hr />

<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Update Options') ?>" />
</p>

</form>
</div>

<?php




}

?>