<?php
global $wp_post_types, $wp_roles;

$all_post_types = array();
$default_post_types = array();

foreach ($wp_post_types as $post_type => $details) {
	$all_post_types[$post_type] = $details;

	if (!in_array($post_type, array('post', 'page', 'attachment', 'revision', 'nav_menu_item'))) {
		$custom_post_types[$post_type] = $details;
	}
}

$wpac_members_blog_redirect        = get_option('wpac_members_blog_redirect', '');
$wpac_members_only_blog            = get_option('wpac_members_only_blog', false);
$wpac_custom_post_types            = get_option('wpac_custom_post_types', array());
$wpac_show_in_menus                = get_option('wpac_show_in_menus', 'with_access');
$wpac_default_page_state           = get_option('wpac_default_page_state', 'public');
$wpac_default_members_redirect     = get_option('wpac_default_members_redirect' , '');
$wpac_show_posts_in_search         = get_option('wpac_show_posts_in_search', false);
$wpac_show_post_excerpts_in_search = get_option('wpac_show_post_excerpts_in_search', false);
$wpac_show_pages_in_search         = get_option('wpac_show_pages_in_search', false);
$wpac_show_page_excerpts_in_search = get_option('wpac_show_page_excerpts_in_search', false);

// All post types that WPAC is enabled for
$wpac_enabled_post_types = array_merge(array('post', 'page'), $wpac_custom_post_types);
?>
<div class="wrap">
	<div class="icon32" id="icon-options-general"><br></div>
	<h2><?php _e('Member Access Control Settings', 'wpac') ?></h2>

	<?php if (isset($admin_message) && ! empty($admin_message)) { ?>
	<div class="updated below-h2" id="message"><p><?php echo $admin_message ?></p></div>
	<?php } ?>

	<?php if (isset($admin_error) && ! empty($admin_error)) { ?>
	<div class="error below-h2" id="error"><p><?php echo $admin_error ?></p></div>
	<?php } ?>

	<form action="options-general.php?page=wpac-options" method="post">
		<input type="hidden" value="wpac-options" name="options_page">
		<input type="hidden" value="update" name="action">
		<?php wp_nonce_field('wpac_options_save') ?>

		<h3 class="title"><?php _e('Shortcodes', 'wpac') ?></h3>

		<p><?php _e('To make a specific section of content members only instead of the entire page, sorround it with [member]Your content here[/member] tags. For non-member content, use [nonmember]Your content here[/nonmember].', 'wpac') ?></p>

		<h3 class="title"><?php _e('General Options', 'wpac') ?></h3>

		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						<label for="wpac_members_only_blog"><?php _e('Make Website Members Only', 'wpac') ?></label>
					</th>

					<td>
						<label><input type="checkbox" name="wpac_members_only_blog" value="yes" id="wpac_members_only_blog" <?php checked($wpac_members_only_blog, 1) ?>/> <span><?php _e('Make Blog Members Only', 'wpac') ?></span></label>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="wpac_members_blog_redirect"><?php _e('Members Website Redirect', 'wpac') ?></label>
					</th>

					<td>
						<input type="text" class="regular-text" value="<?php esc_attr_e($wpac_members_blog_redirect) ?>" id="wpac_members_blog_redirect" name="wpac_members_blog_redirect">
						<span class="description"><?php printf(__('Where to redirect non-members when they try to visit the Website. %1$s
Defaults to WordPress login page (%2$s)%1$s
After a user logs in they will be redirected back to the Website', 'wpac'),  '<br>', '<a href="' . wp_login_url() . '">' . wp_login_url() . '</a>') ?></span>
					</td>
				</tr>

				<?php if (! empty($custom_post_types)) { ?>
				<tr>
					<th scope="row">
						<label for="wpac_members_only_blog"><?php _e('Custom Post Types', 'wpac') ?></label>
					</th>

					<td>
						<p class="description"><?php _e('You can enable access controls for custom post types by selecting them below. May not work if the post type uses a custom admin interface.', 'wpac') ?></p>
						<?php
						foreach ($custom_post_types as $post_type => $details) {
							if (in_array($post_type, $wpac_custom_post_types)) {
								echo '<label><input type="checkbox" checked="checked" value="' . esc_attr($post_type) . '" id="wpac_enable_for_post_type_' . esc_attr($post_type) . '" name="wpac_custom_post_types[]"> <span>' . $post_type . ' &ndash; ' . $details->labels->name . ' - ' . ((! empty($details->description)) ? $details->description : '<em>No Description</em>') . '</span></label><br>';
							} else {
								echo '<label><input type="checkbox" value="' . esc_attr($post_type) . '" id="wpac_enable_for_post_type_' . esc_attr($post_type) . '" name="wpac_custom_post_types[]"> <span>' . $post_type . ' &ndash; ' . $details->labels->name . ' - ' . ((! empty($details->description)) ? $details->description : '<em>No Description</em>') . '</span></label><br>';
							}
						}
						?>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>

		<h3 class="title"><?php _e('Override Permisisons', 'wpac') ?></h3>

		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						<?php _e('Always Accessible By', 'wpac') ?>
					</th>
					<td>
						<?php
						$roles = array_map('translate_user_role', $wp_roles->get_names());
						$checked_roles = (array) maybe_unserialize(get_option('wpac_always_accessible_by', array(0 => 'administrator')));

						foreach ($roles as $role => $label) {
							if (in_array($role, $checked_roles)) {
								echo '<label><input type="checkbox" name="wpac_always_accessible_by[]" checked="checked" value="' . $role . '"> ' . $label . '<br></label>';
							} else {
								echo '<label><input type="checkbox" name="wpac_always_accessible_by[]" value="' . $role . '"> ' . $label . '<br></label>';
							}
						}
						?>
					</td>
				</tr>
			</tbody>
		</table>

		<h3 class="title"><?php _e('Menu Options', 'wpac') ?></h3>

		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						<?php _e('Display In Menus', 'wpac') ?>
					</th>
					<td>
						<p class="description"><?php _e('By default, should pages and posts be displayed in your menu even if the user doesn\'t have access to them? Items will always show up for users with access to them', 'wpac') ?></p>
						<label><input <?php checked($wpac_show_in_menus, 'with_access') ?> type="radio" value="with_access" name="wpac_show_in_menus"> <span><?php _e('Only show menu items to users with access to them', 'wpac') ?></span></label><br>
						<label><input <?php checked($wpac_show_in_menus, 'always') ?> type="radio" value="always" name="wpac_show_in_menus"> <span><?php _e('Always show all menu items even if the user cannot access them', 'wpac') ?></span></label>
					</td>
				</tr>
			</tbody>
		</table>

		<h3 class="title"><?php _e('Post Type Default Options', 'wpac') ?></h3>

		<p>
			<?php _e('These options are defaults for post types in case the majority of your posts will have similar settings. You can override any of these settings on a per-post basis.') ?>
		</p>

		<table class="form-table">
			<tbody>
				<?php foreach ($wpac_enabled_post_types as $post_type) : ?>
				<tr>
					<th scope="row">
						<?php printf(__('Default State For: %s', 'wpac'), $all_post_types[$post_type]->labels->name) ?>
					</th>
					<td>
						<?php $default_state = get_option('wpac_default_' . $post_type . '_state', 'public'); ?>
						<label><input <?php checked($default_state, 'public') ?> type="radio" value="public" name="default_state[<?php echo $post_type ?>]"> <span><?php _e('Public', 'wpac') ?></span></label><br>
						<label><input <?php checked($default_state, 'members') ?> type="radio" value="members" name="default_state[<?php echo $post_type ?>]"> <span><?php _e('Members Only', 'wpac') ?></span></label><br>
						<label><input <?php checked($default_state, 'nonmembers') ?> type="radio" value="nonmembers" name="default_state[<?php echo $post_type ?>]"> <span><?php _e('Non-Members Only', 'wpac') ?></span></label><br>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<?php printf(__('%s: Default Roles', 'wpac'), $all_post_types[$post_type]->labels->name) ?>
					</th>
					<td>
						<?php
						$roles = array_map('translate_user_role', $wp_roles->get_names());
						$checked_roles = (array) get_option('wpac_' . $post_type . 's_default_restricted_to', array());

						foreach ($roles as $role => $label) {
							if (in_array($role, $checked_roles)) {
								echo '<label><input type="checkbox" name="wpac_default_restricted_to[' . $post_type . '][]" checked value="' . $role . '"> ' . $label . '</label><br>';
							} else {
								echo '<label><input type="checkbox" name="wpac_default_restricted_to[' . $post_type . '][]" value="' . $role . '"> ' . $label . '</label><br>';
							}
						}
						?>
						<span class="description"><?php _e('Defaults to all roles', 'wpac') ?></span>
					</td>
				</tr>

				<tr><td colspan="2"><hr></td></tr>
				<?php endforeach ?>

				<tr>
					<th scope="row">
						<label for="wpac_default_members_redirect"><?php _e('Default Redirect For Members Only Pages', 'wpac') ?></label>
					</th>
					<td>
						<input type="text" class="regular-text" value="<?php esc_attr_e($wpac_default_members_redirect) ?>" id="wpac_default_members_redirect" name="wpac_default_members_redirect">
						<span class="description">
							<?php /* translators: Text in the brackets (%1$s) is a link to the default login page */ printf(__('Defaults to WordPress login page (%1$s)%2$sAfter a user logs in they will be redirected back to the page they attempted to view', 'wpac'), '<a href="' . wp_login_url() . '">' . wp_login_url() . '</a>', '<br>') ?>
						</span>
					</td>
				</tr>
			</tbody>
		</table>

		<h3 class="title"><?php _e('Search/Archive Options', 'wpac') ?></h3>

		<p>
			<?php __('I use the wording "search" below, but these settings apply to search AND archive pages (Such as the blog page, categories and tags pages, and such).
<strong>For example</strong>, if you wanted a blog where non-members could see post titles and excerpts but not the actual posts, set the default
post state to Members Only, then set the Search Options to show restricted posts in search results and show post excerpts.', 'wpac') ?>
		</p>

		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						<?php _e('Search Options', 'wpac') ?>
					</th>
					<td>
						<label><input type="checkbox" <?php checked($wpac_show_posts_in_search, 1) ?> value="yes" name="show_posts_in_search"> <span><?php _e('Show restricted posts in search results?', 'wpac') ?></span></label><br>
						<label><input type="checkbox" <?php checked($wpac_show_post_excerpts_in_search, 1) ?> value="yes" name="show_post_excerpts_in_search"> <span><?php _e('Show restricted post excerpts in search results?', 'wpac') ?></span></label><br>
						<label><input type="checkbox" <?php checked($wpac_show_pages_in_search, 1) ?> value="yes" name="show_pages_in_search"> <span><?php _e('Show restricted pages in search results?', 'wpac') ?></span></label><br>
						<label><input type="checkbox" <?php checked($wpac_show_page_excerpts_in_search, 1) ?> value="yes" name="show_page_excerpts_in_search"> <span><?php _e('Show restricted page excerpts in search results?', 'wpac') ?></span></label><br>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="post_excerpt_text"><?php _e('Search Excerpt (Posts)', 'wpac') ?></label>
					</th>
					<td>
						<fieldset>
							<legend class="screen-reader-text"><?php _e('Search Excerpt (Posts)', 'wpac') ?></legend>

							<p>
								<label for="post_excerpt_text"><?php _e('If a post is set to show in search results WITHOUT an excerpt, this text will be displayed instead.', 'wpac') ?></label>
							</p>

							<p>
								<textarea id="post_excerpt_text" name="post_excerpt_text" class="large-text" cols="50" rows="5"><?php echo get_option('wpac_post_excerpt_text', __('To view the contents of this post, you must be authenticated and have the required access level.', 'wpac')) ?></textarea>
							</p>
						</fieldset>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="page_excerpt_text"><?php _e('Search Excerpt (Pages)', 'wpac') ?></label>
					</th>
					<td>
						<fieldset>
							<legend class="screen-reader-text"><?php _e('Search Excerpt (Pages)', 'wpac') ?>)</legend>

							<p>
								<label for="page_excerpt_text"><?php _e('If a page is set to show in search results WITHOUT an excerpt, this text will be displayed instead.', 'wpac') ?></label>
							</p>

							<p>
								<textarea id="page_excerpt_text" name="page_excerpt_text" class="large-text" cols="50" rows="5"><?php echo get_option('wpac_page_excerpt_text', __('To view the contents of this page, you must be authenticated and have the required access level.', 'wpac')) ?></textarea>
							</p>
						</fieldset>
					</td>
				</tr>
			</tbody>
		</table>

		<p class="submit"><input type="submit" value="<?php _e('Save Changes', 'wpac') ?>" class="button-primary" id="submit" name="submit"></p>
	</form>

	<h3 class="title"><?php _e('Bulk Actions', 'wpac') ?></h3>

	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<?php _e('Add Role By Existing Role', 'wpac') ?>
				</th>
				<td>
					<form action="options-general.php?page=wpac-options" method="post">
						<input type="hidden" value="wpac-options" name="options_page">
						<input type="hidden" value="bulk_set_same" name="action">
						<?php wp_nonce_field('wpac_options_save') ?>
						Make all
						<select name="bulk_post_type">
						<?php
						$roles = array_map('translate_user_role', $wp_roles->get_names());

						foreach ($wpac_enabled_post_types as $post_type) {
							echo '<option value="' . $post_type . '">' . $all_post_types[$post_type]->labels->name . '</option>';
						}
						?>
						</select>
						accessible by
						<select name="bulk_grant_role">
						<?php
						$roles = array_map('translate_user_role', $wp_roles->get_names());

						foreach ($roles as $role => $label) {
							echo '<option value="' . $role . '">' . $label . '</option>';
						}
						?>
						</select>
						where
						<select name="bulk_has_role">
						<?php
						$roles = array_map('translate_user_role', $wp_roles->get_names());

						foreach ($roles as $role => $label) {
							echo '<option value="' . $role . '">' . $label . '</option>';
						}
						?>
						</select>
						already has access

						<br><br><input type="submit" value="<?php _e('Execute', 'wpac') ?>" class="button-secondary" id="submit" name="submit">
					</form>
				</td>
			</tr>

			<tr>
				<th scope="row">
					<?php _e('Add Role To Post Type', 'wpac') ?>
				</th>
				<td>
					<form action="options-general.php?page=wpac-options" method="post">
						<input type="hidden" value="wpac-options" name="options_page">
						<input type="hidden" value="bulk_set_all_roles" name="action">
						<?php wp_nonce_field('wpac_options_save') ?>
						Make all
						<select name="bulk_post_type">
						<?php
						$roles = array_map('translate_user_role', $wp_roles->get_names());

						foreach ($wpac_enabled_post_types as $post_type) {
							echo '<option value="' . $post_type . '">' . $all_post_types[$post_type]->labels->name . '</option>';
						}
						?>
						</select>
						accessible by
						<select name="bulk_role">
						<?php
						$roles = array_map('translate_user_role', $wp_roles->get_names());

						foreach ($roles as $role => $label) {
							echo '<option value="' . $role . '">' . $label . '</option>';
						}
						?>
						</select>

						<br><br><input type="submit" value="<?php _e('Execute', 'wpac') ?>" class="button-secondary" id="submit" name="submit">
					</form>
				</td>
			</tr>

			<tr>
				<th scope="row">
					<?php _e('Set Post Type Accessibility', 'wpac') ?>
				</th>
				<td>
					<form action="options-general.php?page=wpac-options" method="post">
						<input type="hidden" value="wpac-options" name="options_page">
						<input type="hidden" value="bulk_set_all" name="action">
						<?php wp_nonce_field('wpac_options_save') ?>
						Make all
						<select name="bulk_post_type">
						<?php
						$roles = array_map('translate_user_role', $wp_roles->get_names());

						foreach ($wpac_enabled_post_types as $post_type) {
							echo '<option value="' . $post_type . '">' . $all_post_types[$post_type]->labels->name . '</option>';
						}
						?>
						</select>
						only accessible to members

						<br><br><input type="submit" value="<?php _e('Execute', 'wpac') ?>" class="button-secondary" id="submit" name="submit">
					</form>
				</td>
			</tr>
		</tbody>
	</table>
</div>
