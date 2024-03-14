<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap duplicate_post_settings">
	<h1><?php _e('Plugin Settings', 'duplicate_wp_post')?></h1>
	<?php $duplicate_post_options = array();
	$opt = get_option('duplicate_wp_post_options');
	$instruct = isset($_GET['instruct']) ? $_GET['instruct'] : '';
	if(isset($_POST['submit_duplicate_wp_post']) && wp_verify_nonce( $_POST['duplicate_post_nonce_field'], 'duplicate_post_action' )) {
		_e("<strong>changes saving..</strong>", 'duplicate_wp_post');
		$duplicate_post_nosave = array('submit_duplicate_wp_post');
		foreach($duplicate_post_nosave as $noneed):
			unset($_POST[$noneed]);
		endforeach;
		foreach($_POST as $key => $val):
			$duplicate_post_options[$key] = $val;
		endforeach;
		$duplicate_post_settings_save = update_option('duplicate_wp_post_options', $duplicate_post_options );
		if($duplicate_post_settings_save) { 
			duplicate_wp_post::duplicate_page_redirect('options-general.php?page=duplicate_post_settings&instruct=1'); 
		} else { 
			duplicate_wp_post::duplicate_page_redirect('options-general.php?page=duplicate_post_settings&instruct=2'); 
		} 
	}
	if(!empty($instruct) && $instruct == 1) {
		_e( '<div class="updated settings-error duplicate_post_pluginnotice is-dismissible" id="setting-error-settings_updated"> 
	<p><strong>Changes saved.</strong></p><button class="notice-ignore" type="button"><span class="screen-reader-text">Ignore this notice.</span></button></div>', 'duplicate_wp_post');	
	} else if(!empty($instruct) && $instruct == 2) {
	  _e( '<div class="error settings-error duplicate_post_pluginnotice is-dismissible" id="setting-error-settings_updated"> 
	<p><strong>Changes not saved.</strong></p><button class="notice-ignore" type="button"><span class="screen-reader-text">Ignore this notice.</span></button></div>', 'duplicate_wp_post');
	}
	?> 
	<div id="duplicate-post-stuff">
		<div id="duplicate-post-body" class="metabox-holder columns-2">
			<div id="duplicate-post-body-content" style="position: relative;">
				<form style="padding: 10px; border: 1px solid #333;" action="" method="post" name="duplicate_wp_post_form">
				<?php  wp_nonce_field( 'duplicate_post_action', 'duplicate_post_nonce_field' ); ?>
				<table class="form-table">
					<tbody>
						<tr><th scope="row"><label for="duplicate_posteditor"><?php _e('Select Editor<br><em>Default: Classic Editor</em>', 'duplicate_wp_post'); ?></label></th>
							<td><select id="duplicate_posteditor" name="duplicate_posteditor"><option value="classic" <?php echo (isset($opt['duplicate_posteditor']) && $opt['duplicate_posteditor'] == 'classic') ? "selected = 'selected'" : ''; ?>><?php _e('Classic Editor', 'duplicate_wp_post'); ?></option><option value="gutenberg" <?php echo (isset($opt['duplicate_posteditor']) && $opt['duplicate_posteditor'] == 'gutenberg') ? "selected = 'selected'" : ''; ?>><?php _e('Gutenberg Editor', 'duplicate_wp_post'); ?></option></select>
							<p><?php _e('Please select which editor you are using.<br> If you are using Gutenberg, select gutenberg editor otherwise it will not show Duplicate button on edit screen.', 'duplicate_wp_post'); ?></p>
							</td>
						</tr>
						<tr><th scope="row"><label for="duplicate_post_status"><?php _e('Post Status<br><em>Default: Draft</em>', 'duplicate_wp_post'); ?></label></th>
							<td><select id="duplicate_post_status" name="duplicate_post_status"><option value="draft" <?php echo($opt['duplicate_post_status'] == 'draft') ? "selected = 'selected'" : ''; ?>><?php _e('Draft', 'duplicate_wp_post'); ?></option><option value="publish" <?php echo($opt['duplicate_post_status'] == 'publish') ? "selected = 'selected'" : ''; ?>><?php _e('Publish', 'duplicate_wp_post'); ?></option><option value="private" <?php echo($opt['duplicate_post_status'] == 'private') ? "selected = 'selected'" : ''; ?>><?php _e('Private', 'duplicate_wp_post'); ?></option><option value="pending" <?php echo($opt['duplicate_post_status'] == 'pending') ? "selected = 'selected'" : ''; ?>><?php _e('Pending', 'duplicate_wp_post'); ?></option></select>
							<p><?php _e('Please select any post status you want to assign for duplicate post.', 'duplicate_wp_post'); ?></p>
							</td>
						</tr>
						<tr><th scope="row"><label for="duplicate_post_redirect"><?php _e('Redirect<br><em>Default: To current list.</em><br>(After click on <strong>Duplicate</strong>)', 'duplicate_wp_post'); ?></label></th>
							<td><select id="duplicate_post_redirect" name="duplicate_post_redirect"><option value="to_list" <?php echo($opt['duplicate_post_redirect'] == 'to_list') ? "selected = 'selected'" : ''; ?>><?php _e('All Post List', 'duplicate_wp_post'); ?></option><option value="to_page" <?php echo($opt['duplicate_post_redirect'] == 'to_page') ? "selected = 'selected'" : ''; ?>><?php _e('Direct Edit', 'duplicate_wp_post'); ?></option></select>
							<p><?php _e('Please select any post redirection, redirect you to selected after click on duplicate.', 'duplicate_wp_post'); ?></p>
							</td>
						</tr>
						<tr><th scope="row"><label for="duplicate_post_suffix"><?php _e('Duplicate Post Suffix<br><em>Default: Empty</em>', 'duplicate_wp_post')?></label></th>
							<td><input type="text" class="regular-text" value="<?php echo !empty($opt['duplicate_post_suffix']) ? $opt['duplicate_post_suffix'] : ''?>" id="duplicate_post_suffix" name="duplicate_post_suffix">
							<p><?php _e('Add a suffix for duplicate post and page. It will show after title.', 'duplicate_wp_post')?></p>
							</td>
						</tr>		
						<tr><th scope="row"><label for="duplicate_post_link_title"><?php _e('Duplicate Link Text<br><em>Default: Duplicate</em>', 'duplicate_wp_post')?></label></th>
							<td><input type="text" class="regular-text" value="<?php echo !empty($opt['duplicate_post_link_title']) ? $opt['duplicate_post_link_title'] : ''?>" id="duplicate_post_link_title" name="duplicate_post_link_title">
							<p><?php _e('It will show above text on duplicate post/page link button instead of default (Duplicate)', 'duplicate_wp_post')?></p>
							</td>
						</tr>		
					</tbody>
				</table>
				<p class="submit"><input type="submit" value="Save Settings" class="button button-primary" id="submit" name="submit_duplicate_wp_post"></p>
				</form>
			</div>
		</div>
	</div>
</div>