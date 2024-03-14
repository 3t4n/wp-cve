<?php

	global $wpdb, $wp_version, $wp_locale;
	pgee_txtdomain();

	If ( !current_user_can('edit_posts') )
		wp_die(__('You don\'t have permission to edit excerpts!', 'excerpt-editor'));

	if ( empty($wp_version) || version_compare($wp_version, '2.6', '<') ) { // WP 2.1 or less
		wp_die(__('This version of Excerpt Editor requires WordPress version 2.6 or newer. For older versions of WordPress, please use Excerpt Editor version 0.3.2.', 'excerpt-editor'));
	}

	$opt = get_option('pgee_options', array());
	$autoopt = get_option('pgee_auto_options', array());
	$appendopt = get_option('pgee_append_options', array());
	$replaceopt = get_option('pgee_replace_options', array());

	if ( isset($_POST['update_pgee']) ) {
		check_admin_referer( 'pgee_update_options' );

		$opt['pgee_posts'] = !empty($_POST['pgee_posts']) ? '1' : '';
		$opt['pgee_pages'] = !empty($_POST['pgee_pages']) ? '1' : '';
		$opt['pgee_noexc'] = !empty($_POST['pgee_noexc']) ? '1' : '';
		$opt['show_page_box'] = !empty($_POST['show_page_box']) ? '1' : '';
		$opt['pgee_tags'] = !empty($_POST['pgee_tags']) ? '1' : '';

		update_option('pgee_options', $opt);
		$updated = true;
	}

	if ( isset($_POST['update_auto_pgee']) ) {
		check_admin_referer( 'pgee_update_options' );

		$autoopt['pgee_more'] = !empty($_POST['pgee_more']) ? '1' : '';
		$autoopt['more_link'] = !empty($_POST['more_link']) ? '1' : '';
		$autoopt['more_link_cc'] = !empty($_POST['more_link_cc']) ? '1' : '';
		$autoopt['more_text'] = !empty($_POST['more_text']) ? $wpdb->escape($_POST['more_text']) : 'Continue&nbsp;reading';
		$autoopt['pgee_length'] = !empty($_POST['pgee_length']) ? (int) $_POST['pgee_length'] : '70';
		$autoopt['on_site'] = !empty($_POST['on_site']) ? '1' : '';
		$autoopt['in_rss'] = !empty($_POST['in_rss']) ? '1' : '';

		$autoopt['do_shortcodes'] = !empty($_POST['do_shortcodes']) ? '1' : '';
		$autoopt['pgee_tags'] = !empty($_POST['allowedtags']) ? preg_replace('/[^<>a-zA-Z0-6]+/', '', $_POST['allowedtags']) : '';

		update_option('pgee_auto_options', $autoopt);
		$updated = true;
	}

	if( isset($_POST['update_append']) ) {
		check_admin_referer( 'pgee_update_options' );

		$appendopt['append_to_posts'] = !empty($_POST['append_to_posts']) ? '1' : '';
		$appendopt['append_to_pages'] = !empty($_POST['append_to_pages']) ? '1' : '';
		$appendopt['append_subpages'] = !empty($_POST['append_subpages']) ? '1' : '';
		$appendopt['append_number_posts'] = isset($_POST['append_number_posts']) ? (int) $_POST['append_number_posts'] : '3';
		$appendopt['append_number_pages'] = isset($_POST['append_number_pages']) ? (int) $_POST['append_number_pages'] : '3';
		$appendopt['append_include'] = isset($_POST['append_include']) ? (int) $_POST['append_include'] : '';
		$appendopt['append_newest_post'] = !empty($_POST['append_newest_post']) ? '1' : '';
		$appendopt['before_appended'] = !empty($_POST['before_appended']) ? $wpdb->escape(sanitize_user($_POST['before_appended'])) : '';

		update_option('pgee_append_options', $appendopt);
		$updated = true;
	}

	if( isset($_POST['update_replace']) ) {
		check_admin_referer( 'pgee_update_options' );

		$replaceopt['replace_home'] = !empty($_POST['replace_home']) ? '1' : '';
		$replaceopt['replace_archives'] = !empty($_POST['replace_archives']) ? '1' : '';
		$replaceopt['replace_tags'] = !empty($_POST['replace_tags']) ? '1' : '';
		$replaceopt['keep_latest'] = !empty($_POST['keep_latest']) ? '1' : '';
		$replaceopt['no_cmnt_count'] = !empty($_POST['no_cmnt_count']) ? '1' : '';

		update_option('pgee_replace_options', $replaceopt);
		$updated = true;
	}

	if ( empty($opt) ) {
		$opt = array(
			'pgee_posts' => '1',
			'pgee_pages' => '1',
			'show_page_box' => '1'
		);
		add_option('pgee_options', $opt);
	}

	if ( empty($autoopt) ) {
		$autoopt = array(
			'pgee_length' => '70',
			'pgee_tags' => '<p><div><span><strong><em><a><img><ul><ol><li><dd><dl><dt><h1><h2><h3><h4><h5><h6>',
			'more_text' => 'Continue&nbsp;reading',
			'more_link' => '1'
		);
		add_option('pgee_auto_options', $autoopt);
	}

	if ( empty($appendopt) ) {
		$appendopt = array(
			'append_number_pages' => '3',
			'append_number_posts' => '3'
		);
		add_option('pgee_append_options', $appendopt);
	}

	if( $_POST['append_exclude_add'] ) {
		check_admin_referer( 'pgee_update_options' );

		if( ! is_array($appendopt['append_exclude']) )
			$appendopt['append_exclude'] = array();

		$appendopt['append_exclude'][] = (int) $_POST['append_exclude'];
		$appendopt['append_exclude'] = array_unique($appendopt['append_exclude']);
		sort( $appendopt['append_exclude'] );
		update_option('pgee_append_options', $appendopt);
	}

	if( $_POST['append_exclude_rem'] ) {
		check_admin_referer( 'pgee_update_options' );

		if( ! is_array($appendopt['append_exclude']) )
			$appendopt['append_exclude'] = array();

		if ( in_array((int) $_POST['append_exclude'], $appendopt['append_exclude']) ) {
			$rem[] = (int) $_POST['append_exclude'];
			$appendopt['append_exclude'] = array_diff($appendopt['append_exclude'], $rem);
			sort( $appendopt['append_exclude'] );
			update_option('pgee_append_options', $appendopt);
		}
	}

	if( $_POST['dont_append_add'] ) {
		check_admin_referer( 'pgee_update_options' );

		if( ! is_array($appendopt['dont_append']) )
			$appendopt['dont_append'] = array();

		$appendopt['dont_append'][] = (int) $_POST['dont_append'];
		$appendopt['dont_append'] = array_unique($appendopt['dont_append']);
		sort( $appendopt['dont_append'] );
		update_option('pgee_append_options', $appendopt);
	}

	if( $_POST['dont_append_rem'] ) {
		check_admin_referer( 'pgee_update_options' );

		if( ! is_array($appendopt['dont_append']) )
			$appendopt['dont_append'] = array();

		if ( in_array((int) $_POST['dont_append'], $appendopt['dont_append']) ) {
			$rem[] = (int) $_POST['dont_append'];
			$appendopt['dont_append'] = array_diff($appendopt['dont_append'], $rem);
			sort( $appendopt['dont_append'] );
			update_option('pgee_append_options', $appendopt);
		}
	}

	if ( ! empty($_POST['pgee_edit_delete']) && (int) $_POST['pgee_list'] ) {
		check_admin_referer( 'pgee_nonce_delete' );

		$del_id = (int) $_POST['pgee_list'];
		$wpdb->query($wpdb->prepare("UPDATE $wpdb->posts SET post_excerpt = '' WHERE ID = %d", $del_id));
	}

	if ( ! empty($_POST['pgee_edit_submit']) && (int) $_POST['pgee_edit_ID'] ) {
		check_admin_referer( 'pgee_nonce_delete' );

		if ( ! empty($_POST['pgee_edit_excrpt']) )
			$sav = balanceTags($_POST['pgee_edit_excrpt'], true);
		else
			$sav = '';

		$edit_id = (int) $_POST['pgee_edit_ID'];
		$postar = array( 'ID' => $edit_id, 'post_excerpt' => $sav );
		wp_update_post($postar);
	}

	$showmnt = (int) $_POST['showmnt'];
	$mnt = '';
	if ( $showmnt > 10000 && $showmnt < 210000 ) {
		$yr = substr( $showmnt, 0, 4 );
		$mn = substr( $showmnt, 4, 2 );
		$mnt = "AND YEAR(post_date) = '$yr' AND MONTH(post_date) = '$mn'";
	}

	$what = "post_status = 'publish'";
	if ( !empty($opt['pgee_posts']) && empty($opt['pgee_pages']) )
		$what .= " AND post_type = 'post'";
	elseif ( empty($opt['pgee_posts']) && !empty($opt['pgee_pages']) )
		$what .= " AND post_type = 'page'";

	If ( !empty($opt['pgee_noexc']) )
		$what .= " AND post_excerpt = ''";

	$all = $wpdb->get_results( "SELECT ID, post_title, post_excerpt FROM $wpdb->posts WHERE $what $mnt ORDER BY post_date ASC" );

	$months = $wpdb->get_results("SELECT DISTINCT YEAR(post_date) AS yyear, MONTH(post_date) AS mmonth FROM $wpdb->posts WHERE $what ORDER BY post_date ASC");  // from WP

	if ( ! empty($_POST['pgee_edit_gonext']) )
		$id = (int) $_POST['pgee_edit_next'];
	elseif ( ! empty($_POST['pgee_list']) )
		$id = (int) $_POST['pgee_list'];
	else
		$id = $all[0]->ID;

	if ( $id )
		$post = get_post( $id, ARRAY_A );

	if ( trim($post['post_excerpt']) != '' && empty($_POST['pgee_edit_getcont']) )
		$output = ( $post['post_excerpt'] );
	elseif ( $post['post_content'] != '' || ! empty($_POST['pgee_edit_getcont']) )
		$output = pgee_make($post['post_content'], true);
	else
		$output = '';
?>

	<div class="wrap">
	<h2><?php _e('Excerpt Editor', 'excerpt-editor');

	if ( count($all) > 1 ) {
		echo ' - ' . count($all) . ' ';
		if ( !empty($opt['pgee_posts']) && empty($opt['pgee_pages']) )
			_e('Posts selected.', 'excerpt-editor');
		elseif ( empty($opt['pgee_posts']) && !empty($opt['pgee_pages']) )
			_e('Pages selected.', 'excerpt-editor');
		else
			_e('Posts & Pages selected.', 'excerpt-editor');
	} ?>
	</h2>

<script type="text/javascript">
//<![CDATA[
function I(n){return document.getElementById(n);};
function foldIt(id) {
	I('editor_options').style.display = 'none';
	I('b_editor_options').style.color = '';
	I('autogenerate_options').style.display = 'none';
	I('b_autogenerate_options').style.color = '';
	I('append_options').style.display = 'none';
	I('b_append_options').style.color = '';
	I('replace_options').style.display = 'none';
	I('b_replace_options').style.color = '';
	I('b_hide_all').style.visibility = 'visible';

	if (id) {
		I(id).style.color = '#d54e21';
		I(id.substring(2)).style.display = 'block';
	}
}
//]]>
</script>

<?php if( $updated ) { ?>
	<div id="message" class="updated fade"><p><?php _e('Options saved.', 'excerpt-editor') ?></p></div>
<?php } ?>

	<div class="pgee-optwrap">
	Options: &nbsp;
	<strong>
	<a href="#" id="b_editor_options" onclick="foldIt(this.id);return false;" class="pgee-optbtn"> <?php _e('Editor', 'excerpt-editor'); ?> </a>
	<a href="#" id="b_autogenerate_options" onclick="foldIt(this.id);return false;" class="pgee-optbtn"> <?php _e('Auto-Generate', 'excerpt-editor'); ?> </a>
	<a href="#" id="b_append_options" onclick="foldIt(this.id);return false;" class="pgee-optbtn"> <?php _e('Append', 'excerpt-editor'); ?> </a>
	<a href="#" id="b_replace_options" onclick="foldIt(this.id);return false;" class="pgee-optbtn"> <?php _e('Replace Posts', 'excerpt-editor'); ?> </a>
	<a href="#" id="b_hide_all" onclick="foldIt();this.style.visibility = 'hidden';return false;" style="visibility:hidden;" class="pgee-optbtn"><?php _e('Hide Options', 'excerpt-editor'); ?></a>
	</strong>
	</div>

	<div id="editor_options" class="pgee-optpanel">
	<form method="post" action="" name="f4">
	<?php wp_nonce_field('pgee_update_options'); ?>

	<table><tbody><tr><td>

		<p><?php _e('Edit excerpt for', 'excerpt-editor'); ?> <label for="pgee_posts" class="pgee-boxlbl"><?php _e('Posts', 'excerpt-editor'); ?>
			<input type="checkbox" name="pgee_posts" id="pgee_posts" <?php if ( !empty($opt['pgee_posts']) ) echo ' checked="checked"'; ?> /></label>
		<?php _e('and', 'excerpt-editor'); ?> <label for="pgee_pages" class="pgee-boxlbl"><?php _e('Pages', 'excerpt-editor'); ?>
			<input type="checkbox" name="pgee_pages" id="pgee_pages" <?php if ( !empty($opt['pgee_pages']) ) echo ' checked="checked"'; ?> /></label>
		</p>

		<p><?php _e('Show only Posts/Pages that currently are', 'excerpt-editor'); ?> <label for="pgee_noexc" class="pgee-boxlbl"><?php _e('without excerpt', 'excerpt-editor'); ?>
		<input type="checkbox" name="pgee_noexc" id="pgee_noexc" <?php if ( !empty($opt['pgee_noexc']) ) echo ' checked="checked"'; ?> /></label></p>

		<input type="hidden" name="pgee_list" value="<?php echo $id; ?>" />
<?php  if ( (int) $showmnt ) { ?>
		<input type="hidden" name='showmnt' value="<?php echo $showmnt; ?>" />
<?php  } ?>

		</td></tr>
		<tr><td>

		<p>&bull; <?php _e('For Posts/Pages without excerpts, the first few sentences from the content are pre-loaded in the editing area, according to the settings in the Auto-Generate options.', 'excerpt-editor'); ?></p>

		</td></tr>
		</tbody></table>
	<input class="button-primary" type="submit" name="update_pgee" value="<?php _e('Save Editor Options', 'excerpt-editor'); ?>" />
	</form>
	</div>

	<div id="autogenerate_options" class="pgee-optpanel" >
	<form method="post" action="" name="f6" id="f6">
	<?php wp_nonce_field('pgee_update_options'); ?>

		<p><strong><?php _e('Auto-Generate excerpts when displaying posts without excerpts', 'excerpt-editor'); ?>
		<label for="on_site" class="pgee-boxlbl"><?php _e('on the site', 'excerpt-editor'); ?> <input type="checkbox" name="on_site" id="on_site" <?php if ( !empty($autoopt['on_site']) ) echo ' checked="checked"'; ?> /></label>
		<label for="in_rss" class="pgee-boxlbl"><?php _e('or in the RSS feeds', 'excerpt-editor'); ?> <input type="checkbox" name="in_rss" id="in_rss" <?php if ( !empty($autoopt['in_rss']) ) echo ' checked="checked"'; ?> /></label></strong><br />

		<?php _e('Include', 'excerpt-editor'); ?>
		<label for="more_link" class="pgee-boxlbl"><?php _e('&quot;Read More&quot; link', 'excerpt-editor'); ?>
		<input type="checkbox" name="more_link" id="more_link" <?php if ( !empty($autoopt['more_link']) ) echo ' checked="checked"'; ?> /></label>
		<?php _e('with text', 'excerpt-editor'); ?>
		<input type="text" name="more_text" id="more_text" size="30" max-size="50" value="<?php echo stripslashes($autoopt['more_text']); ?>" />
		<?php _e('when displaying all excerpts. Use <strong>%s</strong> to insert the post title. Example: Read the rest of %s... Also include', 'excerpt-editor'); ?>
		<label for="more_link_cc" class="pgee-boxlbl"><?php _e('comments count', 'excerpt-editor'); ?>
		<input type="checkbox" name="more_link_cc" id="more_link_cc" <?php if ( !empty($autoopt['more_link_cc']) ) echo ' checked="checked"'; ?> /></label> <?php _e('(if there are any comments).', 'excerpt-editor'); ?></p>

		<div class="pgee-optsubpanel">
		<p><strong><?php _e('For all auto-generated excerpts:', 'excerpt-editor'); ?></strong></p>

		<p><label for="do_shortcodes" class="pgee-boxlbl"><?php _e('Remove all shortcodes', 'excerpt-editor'); ?>
		<input type="checkbox" name="do_shortcodes" id="do_shortcodes" <?php if ( !empty($autoopt['do_shortcodes']) ) echo ' checked="checked"'; ?> /></label></p>

		<div style="padding: 0 8px;"><?php _e('Allowed HTML tags:', 'excerpt-editor'); ?><br />
		<textarea rows="2" cols="80" type="text" name="allowedtags" id="allowedtags"><?php echo !empty($autoopt['pgee_tags']) ? htmlspecialchars($autoopt['pgee_tags']) : ''; ?></textarea>
		<p>(<?php echo __('default:', 'excerpt-editor') . ' ' . htmlspecialchars('<p><div><span><strong><em><a><img><ul><ol><li><dd><dl><dt><h1><h2><h3><h4><h5><h6>'); ?>)</p>
		<p><a class="button" href="#allowed" onclick="document.getElementById('allowedtags').value = '<?php echo htmlspecialchars('<p><div><span><strong><em><a><img><ul><ol><li><dd><dl><dt><h1><h2><h3><h4><h5><h6>'); ?>'"><?php _e('Restore Default', 'excerpt-editor'); ?></a> &nbsp;
		<a class="button" href="#allowed" onclick="document.getElementById('allowedtags').value = ''"><?php _e('Disallow All', 'excerpt-editor'); ?></a></p>
		</div>

		<p><?php _e('Lenght: Use the', 'excerpt-editor'); ?>
		<label for="pgee_more" class="pgee-boxlbl"><?php _e('&lt;!--More--&gt; tag', 'excerpt-editor'); ?>
		<input type="checkbox" name="pgee_more" id="pgee_more" <?php if ( !empty($autoopt['pgee_more']) ) echo ' checked="checked"'; ?> /></label>
		<?php _e('if available, or get the first', 'excerpt-editor'); ?>
		<select name="pgee_length" id="pgee_length"><?php
			foreach ( array('35', '55', '70', '100', '130') as $number ) {
				$sel = $autoopt['pgee_length'] == $number ? "selected='selected'" : "";
				echo "<option $sel value='$number'>$number</option>\n";
			} ?>
		</select>
		<?php _e('words from the content.', 'excerpt-editor'); ?></p>
	</div>

	<input class="button-primary" type="submit" name="update_auto_pgee" value="<?php _e('Save Auto-Generate Options', 'excerpt-editor'); ?>" />
	</form>
	</div>

	<div id="append_options" class="pgee-optpanel" >
	<form method="post" name="f10" id="f10" action="">
	<?php wp_nonce_field('pgee_update_options'); ?>

		<p><?php _e('Append excerpts from the latests posts', 'excerpt-editor'); ?>
		<label for="append_to_posts" class="pgee-boxlbl"><?php _e('to each <strong>Post</strong>', 'excerpt-editor'); ?>
		<input type="checkbox" name="append_to_posts" id="append_to_posts" <?php if ( !empty($appendopt['append_to_posts']) ) echo ' checked="checked"'; ?> /></label>

		<?php _e('and', 'excerpt-editor'); ?>
		<label for="append_to_pages" class="pgee-boxlbl"><?php _e('to each <strong>Page</strong>', 'excerpt-editor'); ?>
		<input type="checkbox" name="append_to_pages" id="append_to_pages" <?php if ( !empty($appendopt['append_to_pages']) ) echo ' checked="checked"'; ?> /></label>
		</p>

		<p>
		<?php _e('Append excerpts from the sub-pages (if any)', 'excerpt-editor'); ?>
		<label for="append_subpages" class="pgee-boxlbl"><?php _e('to each <strong>Page</strong>', 'excerpt-editor'); ?>
		<input type="checkbox" name="append_subpages" id="append_subpages" <?php if ( !empty($appendopt['append_subpages']) ) echo ' checked="checked"'; ?> /></label>
		</p>

		<p><?php _e('Do not append excerpts to these Posts/Pages (by ID):', 'excerpt-editor'); ?>
<?php
	if ( !empty($appendopt['dont_append']) && is_array($appendopt['dont_append']) ) {

		foreach( $appendopt['dont_append'] as $pgid ) { ?>

		<span class="pgee-number" onclick="document.getElementById('dont_append').value = '<?php echo $pgid; ?>'"><strong><?php echo $pgid; ?></strong></span>
<?php  }

	} else {
		echo ' ' . __('[none]', 'excerpt-editor');
	} ?>

		&bull; <input type="text" name="dont_append" id="dont_append" size="4" maxlength="4" value="" />
		<input class="button" type="submit" name="dont_append_add" value="<?php _e('Add ID', 'excerpt-editor'); ?>" onclick="if(isNaN(this.form.dont_append.value)||''==this.form.dont_append.value){alert('<?php echo js_escape(__("Please enter the Post/Page ID where you do not want to append excerpts.", "excerpt-editor")); ?>');return false;}" />
		<input class="button" type="submit" name="dont_append_rem" value="<?php _e('Remove ID', 'excerpt-editor'); ?>" onclick="if(isNaN(this.form.dont_append.value)||''==this.form.dont_append.value){alert('<?php echo js_escape(__("Please enter a valid Post/Page ID to remove it from this list.", "excerpt-editor")); ?>');return false;}" />
		</p>

		<div class="pgee-optsubpanel">
		<p>&bull; <?php _e('Show this title', 'excerpt-editor'); ?>
		<input type="text" name="before_appended" id="before_appended" size="30" max-size="50" value="<?php echo stripslashes($appendopt['before_appended']); ?>" />
		<?php _e('before the appended excerpts', 'excerpt-editor'); ?>
		</p>

		<p>&bull; <?php _e('Show excerpts from', 'excerpt-editor'); ?>
		<input type="text" name="append_number_posts" id="append_number_posts" size="2" maxlength="2" value="<?php echo $appendopt['append_number_posts']; ?>" />
		<?php _e('most recent posts under each Post/Page', 'excerpt-editor'); ?>
		</p>

		<p>&bull; <?php _e('Show excerpts from up to', 'excerpt-editor'); ?>
		<input type="text" name="append_number_pages" id="append_number_pages" size="2" maxlength="2" value="<?php echo $appendopt['append_number_pages']; ?>" />
		<?php _e('sub-pages on each Page', 'excerpt-editor'); ?>
		</p>

		<p>&bull; <?php _e('Always show excerpt from', 'excerpt-editor'); ?>
		<label for="append_newest_post" class="pgee-boxlbl"><?php _e('my latest Post', 'excerpt-editor'); ?>
		<input type="checkbox" name="append_newest_post" id="append_newest_post" <?php if ( !empty($appendopt['append_newest_post']) ) echo ' checked="checked"'; ?> /></label>
		 <?php _e('(will be shown first)', 'excerpt-editor'); ?>
		</p>

		<p> &bull; <?php _e('Always include excerpt from this Post/Page ID', 'excerpt-editor'); ?>
		<input type="text" name="append_include" id="append_include" size="4" maxlength="4" value="<?php echo $appendopt['append_include']; ?>" />
		</p>

		<p>&bull; <?php _e('Never show excerpts from these Posts/Pages (by ID)', 'excerpt-editor'); ?>
<?php
	if ( !empty($appendopt['append_exclude']) && is_array($appendopt['append_exclude']) ) {

		foreach( $appendopt['append_exclude'] as $pgid ) { ?>

		<span class="pgee-number" onclick="document.getElementById('append_exclude').value = '<?php echo $pgid; ?>'"><strong><?php echo $pgid; ?></strong></span>
<?php  }

	} else {
		echo ' ' . __('[none]', 'excerpt-editor');
	} ?>

		&bull; <input type="text" name="append_exclude" id="append_exclude" size="4" maxlength="4" value="" />
		<input class="button" type="submit" name="append_exclude_add" value="<?php _e('Add excluded ID', 'excerpt-editor'); ?>" onclick="if(isNaN(this.form.append_exclude.value)||''==this.form.append_exclude.value){alert('<?php echo js_escape(__("Please enter the Post/Page ID whose excerpt you want to always exclude \nwhile showing excerpts for sub-pages or for recent posts.", "excerpt-editor")); ?>');return false;}" />
		<input class="button" type="submit" name="append_exclude_rem" value="<?php _e('Remove excluded ID', 'excerpt-editor'); ?>" onclick="if(isNaN(this.form.append_exclude.value)||''==this.form.append_exclude.value){alert('<?php echo js_escape(__("Please enter a valid Post/Page ID to remove it from this list.", "excerpt-editor")); ?>');return false;}" />
		</p>
		</div>

		<p>&bull; <?php _e('You can set the excerpt\'s lenght, allowed HTML tags and the text for &quot;Read More&quot; link in the &quot;Auto-Generate Options&quot;.', 'excerpt-editor'); ?></p>

		<p>&bull; <?php _e('You can edit', 'excerpt-editor'); ?> <a href="<?php bloginfo('wpurl'); ?>/wp-admin/plugin-editor.php?file=excerpt-editor/excerpt-editor.php">excerpt-editor</a> <?php _e('to customise the styles used when displaying appended excerpts (lines 29 to 39).', 'excerpt-editor'); ?></p>

		<p>&bull; <?php _e('If you only want to show excerpt from the latest Post or a specific Post/Page (or both), set the number for &quot;Show excerpts from the most recent posts&quot; and/or &quot;Show excerpts from sub-pages&quot; to 0 (zero).', 'excerpt-editor'); ?></p>

	<input class="button-primary" type="submit" class="button" name="update_append" value="<?php _e('Save Append Options', 'excerpt-editor'); ?>" />
	</form>
	</div>

	<div id="replace_options" class="pgee-optpanel">
	<form method="post" action="" name="f12" id="f12">
	<?php wp_nonce_field('pgee_update_options'); ?>

		<p><strong><?php _e('Replace the Posts', 'excerpt-editor'); ?></strong> <?php _e('displayed on the', 'excerpt-editor'); ?>
		<label for="replace_home" class="pgee-boxlbl"><?php _e('Home Page', 'excerpt-editor'); ?>
		<input type="checkbox" name="replace_home" id="replace_home" <?php if ( !empty($replaceopt['replace_home']) ) echo ' checked="checked"'; ?> /></label>

		<label for="replace_archives" class="pgee-boxlbl"><?php _e('all Archive Pages', 'excerpt-editor'); ?>
		<input type="checkbox" name="replace_archives" id="replace_archives" <?php if ( !empty($replaceopt['replace_archives']) ) echo ' checked="checked"'; ?> /></label>
		<?php _e('(Category, Author, Day, Month, Year) and', 'excerpt-editor'); ?>

		<label for="replace_tags" class="pgee-boxlbl"><?php _e('all Tags Pages', 'excerpt-editor'); ?>
		<input type="checkbox" name="replace_tags" id="replace_tags" <?php if ( !empty($replaceopt['replace_tags']) ) echo ' checked="checked"'; ?> /></label>

		<?php _e('with excerpts, but show', 'excerpt-editor'); ?>
		<label for="keep_latest" class="pgee-boxlbl"><?php _e('the latest Post in full', 'excerpt-editor'); ?>
		<input type="checkbox" name="keep_latest" id="keep_latest" <?php if ( !empty($replaceopt['keep_latest']) ) echo ' checked="checked"'; ?> /></label>
		</p>

		<p><label for="no_cmnt_count" class="pgee-boxlbl"><?php _e('Hide the comments count', 'excerpt-editor'); ?>
		<input type="checkbox" name="no_cmnt_count" id="no_cmnt_count" <?php if ( !empty($replaceopt['no_cmnt_count']) ) echo ' checked="checked"'; ?> /></label>
		 <?php _e('from the &quot;Read More&quot; link (if selected in the Auto-Generate options).', 'excerpt-editor'); ?>
		</p>

		<p>&bull; <?php _e('Displaying excerpts instead of full content on all Archive pages, as well as on the Home page and all Tags pages will eliminate', 'excerpt-editor'); ?> <a href="http://www.google.ca/search?hl=en&q=content+duplication+wordpress&btnG=Search&meta="><?php _e('content duplication', 'excerpt-editor'); ?></a> <?php _e('and is one of the main', 'excerpt-editor'); ?> <acronym title="<?php _e('Search Engine Optimization', 'excerpt-editor'); ?>">SEO</acronym> <?php _e('improvements in WordPress.', 'excerpt-editor'); ?></p>

		<p>&bull; <?php _e('Excerpts will be generated according to the Auto-Generate options.', 'excerpt-editor'); ?></p>

	<input class="button-primary" type="submit" name="update_replace" value="<?php _e('Save the Replace Posts options', 'excerpt-editor'); ?>" />
	</form>
	</div>

	<form name="f5" id="f5" action="" method="post">
	<fieldset class="options" style="padding:1em 0 4px;">

	<?php _e('Select by month:', 'excerpt-editor'); ?>
	<select name="showmnt">
		<option value="0"><?php _e('All', 'excerpt-editor'); ?></option>
<?php
	if ( count($months) ) { // from WP
		foreach ($months as $m) {
			if ( $m->yyear == 0 ) continue;

			$val = $m->yyear . $m->mmonth;
			$default = ( isset($showmnt) && $val == $showmnt ) ? 'selected="selected" ' : '';

			echo '<option ' . $default . 'value="' . $val . '">';
			echo $wp_locale->get_month(zeroise($m->mmonth, 2)) . ' ' . $m->yyear . '</option>' . "\n";
		}
	} ?>
	</select>

	<input class="button" type="submit" name="submit" value="<?php _e('Show', 'excerpt-editor'); ?>" />
	</fieldset></form>

	<form method="post" action="" name="f1" id="f1">
	<fieldset class="options" style="padding:1em 0">
	<table><tbody><tr><td>
	<?php _e('Title:', 'excerpt-editor'); ?> <select name="pgee_list" id="pgee_list" style="font-weight: bold;" onchange="document.forms.f1.submit();">
	<?php foreach ( $all as $k => $key ) {
		$exc = trim($key->post_excerpt); ?>
		<option value="<?php echo($key->ID); ?>"<?php if ( $id == $key->ID ) { $nn = ( $k + 1 ); echo ' selected="selected"'; } ?>><?php echo($key->post_title); if ( empty($exc) ) _e(' (no excerpt)', 'excerpt-editor'); ?></option>
	<?php } ?>
		</select>
<?php $ne_id = $all["$nn"]->ID;

	if ( $showmnt != 0 ) { ?>
		<input type="hidden" name="showmnt" value="<?php echo $showmnt; ?>" />
	<?php } ?>

		<input class="button" type="submit" name="pgee_list_submit" value="<?php _e('Reload', 'excerpt-editor'); ?>" />
	</td></tr>

<?php if ( $id ) { ?>
	<tr><td>
	<span style="color:#002389;font-size:11px;">
	<?php if ( $post['post_type'] == 'post' )
		echo '<strong>' . __('Post', 'excerpt-editor') . ' </strong>';
	else
		echo '<strong>' . __('Page', 'excerpt-editor') . ' </strong>';

	$time = mysql2date( get_option('links_updated_date_format'), $post['post_date'] );
	echo ' | ' . __('Published on', 'excerpt-editor') . ' ' . $time . ' | ';

	if ( $post['post_date'] != $post['post_modified'] ) {
		$time_mod = mysql2date( get_option('links_updated_date_format'), $post['post_modified'] );
		echo __('Last modified on', 'excerpt-editor') . ' ' . $time_mod . ' | ';
	} ?>

	<a href="<?php echo get_permalink($id); ?>" target="_blanc" ><?php _e('View', 'excerpt-editor'); ?></a> |
	<a href="<?php bloginfo(wpurl); ?>/wp-admin/<?php echo ( $post['post_type'] == 'page' ) ? 'page.php' : 'post.php'; ?>?action=edit&post=<?php echo $id; ?>"><?php _e('Edit', 'excerpt-editor'); ?></a>

<?php
	if ( $post['post_type'] == 'post' ) {
		$cats = get_the_category($id);
		if ( !empty( $cats ) ) {
			echo ' | ' . __('Categories:', 'excerpt-editor');
			$out = array();
			foreach ( $cats as $c ) {
				$out[] = "<a href='edit.php?category_name=$c->slug'> " . esc_html(sanitize_term_field('name', $c->name, $c->term_id, 'category', 'display')) . "</a>";
			}
			echo ' ' . join( ', ', $out );
		}
	} ?></span>
	</td></tr>
<?php } ?>
	</tbody>
	</table>
	</fieldset>
	</form>

	<form method="post" action="" name="f2" id="f2">
	<table><tbody><tr><td>
	<?php wp_nonce_field('pgee_nonce_delete');
	$the_title = htmlspecialchars($post['post_title']);

	if ( $showmnt != 0 ) { ?>
		<input type="hidden" name="showmnt" value="<?php echo $showmnt; ?>" />
	<?php } ?>
		<input type="hidden" name="pgee_list" value="<?php echo $id; ?>" />

		<input class="button" type="submit" name="pgee_edit_delete" class="delete" value="<?php _e('Delete Excerpt', 'excerpt-editor'); ?>"
	<?php if ( $post['post_excerpt'] != '' ) { ?>
		onclick="return confirm('<?php printf(js_escape(__("Deleting the excerpt from %s...", "excerpt-editor")), $the_title); ?>')"
	<?php } else { ?>
		onclick="alert('<?php printf(js_escape(__("%s has no excerpt.", "excerpt-editor")), $the_title); ?>');return false;"
	<?php } ?> />

		<input class="button" type="submit" name="pgee_edit_getcont" value="<?php _e('Load from the content', 'excerpt-editor'); ?>"
	<?php if ( $post['post_content'] == '' ) { ?>
		onclick="alert( '<?php printf(js_escape(__("%s has no content.", "excerpt-editor")), $the_title); ?>');return false;"
	<?php } else { ?>
		onclick="return confirm('<?php printf(js_escape(__("Reload the first %s words from the content?", "excerpt-editor")), $autoopt['pgee_length']); ?>')"
	<?php } ?> />


	<input class="button" type="submit" name="pgee_edit_gonext" value="<?php _e('Next', 'excerpt-editor'); ?>"
<?php
	$last = end($all);
	echo $last->ID == $id ? ' disabled="disabled"' : ''; ?> />

	<input type="hidden" name="pgee_edit_next" value="<?php echo $ne_id; ?>" />
	</td></tr></tbody></table>
	</form>

	<form method="post" action="" name="f3" id="f3">
	<?php wp_nonce_field('pgee_nonce_delete'); ?>

	<table><tbody>
	<tr><td class="resizers">
		<div><a href="#f5" id="plus">+</a> <a href="#f5" id="minus">&ndash;</a></div>
	</td></tr>
	<tr><td>
		<textarea name="pgee_edit_excrpt" id="pgee_edit_excrpt" rows="8" cols="60"><?php echo $output; ?></textarea>

		<?php if ( $showmnt != 0 ) { ?>
			<input type="hidden" name='showmnt' value="<?php echo $showmnt; ?>" />
		<?php } ?>

		<input type="hidden" name="pgee_list" value="<?php echo $ne_id; ?>" />
		<input type="hidden" name="pgee_edit_ID" value="<?php echo $id; ?>" />
	</td></tr>
	<tr><td>

		<p>
		<input type="submit" name="pgee_edit_submit" class="button-primary"
	<?php if ( $last->ID == $id ) { ?>
		value="<?php _e('Save (last post)', 'excerpt-editor'); ?>" style="color:#0023DE;"
	<?php } else { ?>
		value="<?php _e('Save and get the next', 'excerpt-editor'); ?>"
	<?php } ?>
		onclick="if (form.pgee_edit_excrpt.value == ''){return confirm('<?php echo js_escape(__("Leaving the editor empty will delete the excerpt. Continue?", "excerpt-editor")); ?>');}" />
		</p>

	</td></tr></tbody></table>
	</form>

	</div>
	<script type="text/javascript" src="<?php echo WP_PLUGIN_URL; ?>/excerpt-editor/textarea-resizer.js"></script>
<?php
// end pgee_page
