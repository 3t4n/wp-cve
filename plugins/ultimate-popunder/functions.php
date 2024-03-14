<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

add_action( 'admin_menu', 'ultimate_popunder_add_admin_menu' );
add_action( 'admin_init', 'ultimate_popunder_settings_init' );

/**
 * Check the version to see if we need to run an upgrade
 */
function ultimate_popunder_update_db_check()
{
	if ( ! current_user_can( 'update_plugins' ) )
	{
		return;
	}

	$str_current_version = get_option( '_ultimate_popunder_version' );

	if ($str_current_version != ULTIMATE_POPUNDER_VERSION)
	{
		ultimate_popunder_upgrade_install( $str_current_version, ULTIMATE_POPUNDER_VERSION);
	}
}
add_action( 'plugins_loaded', 'ultimate_popunder_update_db_check' );

/**
 * Will be used to manage the upgrades should any DB data need changing
 * @param  String $str_current_version The version last registered with this WP install
 * @param  String $str_release_version The current version of this plugin
 */
function ultimate_popunder_upgrade_install( $str_current_version, $str_release_version )
{
	update_option( '_ultimate_popunder_version', $str_release_version );
}

/**
 * Add the list of URLs to the page/post
 * @uses is_single()
 */
function ultimate_popunder_the_content_filter( $content )
{
	global $post;
	global $wpdb;

	$options = get_option( '_ultimate_popunder_settings' );
	$popWidth = isset( $options['ultimate_popunder_text_popwidth'] ) ? intval( $options['ultimate_popunder_text_popwidth'] ) : 80;
	$popHeight = isset( $options['ultimate_popunder_text_popheight'] ) ? intval( $options['ultimate_popunder_text_popheight'] ) : 80;
	$popLength = isset( $options['ultimate_popunder_text_poplength'] ) ? intval( $options['ultimate_popunder_text_poplength'] ) : 30;
	$popMax = isset( $options['ultimate_popunder_text_popmax'] ) ? intval( $options['ultimate_popunder_text_popmax'] ) : 1;



	$sql = "
		SELECT `{$wpdb->prefix}postmeta`.`post_id`
		FROM `{$wpdb->prefix}postmeta`
		INNER JOIN `{$wpdb->prefix}posts` ON `{$wpdb->prefix}postmeta`.`post_id` = `{$wpdb->prefix}posts`.`ID`
		WHERE `{$wpdb->prefix}postmeta`.`meta_key` = '_popunder_post_type'
			AND `{$wpdb->prefix}posts`.`post_status` = 'publish'
			AND (`{$wpdb->prefix}postmeta`.`meta_value` = '{$post->post_type}' OR `{$wpdb->prefix}postmeta`.`meta_value` = '*');
	";

	$results = $wpdb->get_results( $sql );
	if (count( $results ) > 0)
	{
		$content .= "<script type=\"text/javascript\">var URLlist = [";
		foreach ($results as $obj_post)
		{
			$post_meta = get_post_meta( $obj_post->post_id );

			// Show it to everyone
			if ($post_meta['_popunder_post_visible'][0] == '*' || $post_meta['_popunder_post_visible'][0] == FALSE)
			{
				$content .= "[\"{$post_meta['_popunder_url'][0]}\", {$post_meta['_popunder_priority'][0]}],";
			}

			// Show it to only those not logged in
			if ($post_meta['_popunder_post_visible'][0] == 'guest' && ! is_user_logged_in())
			{
				$content .= "[\"{$post_meta['_popunder_url'][0]}\", {$post_meta['_popunder_priority'][0]}],";
			}

			// Show it to only those logged in
			if ($post_meta['_popunder_post_visible'][0] == 'member' && is_user_logged_in())
			{
				$content .= "[\"{$post_meta['_popunder_url'][0]}\", {$post_meta['_popunder_priority'][0]}],";
			}
		}
		$content .= "];";

		$content .= "var ultimatePopunderSettings = {width:(screen.width * .{$popWidth}),height:(screen.height * .{$popHeight}),cap:{$popMax},wait:(60 * {$popLength}),cookie:\"ultimatePopunder\"};</script>";

	}
	else
	{
		$content .= "<script type=\"text/javascript\">var URLlist = [];</script>";
	}

	return $content;
}
add_filter( 'the_content', 'ultimate_popunder_the_content_filter', 20 );

/**
 * Add the script to the footer
 */
function ultimate_popunder_widget_enqueue_script()
{
	wp_enqueue_script(
		'lanund',
		plugin_dir_url( __FILE__ ) . 'assets/lanund.js',
		array( 'jquery' ),
		ULTIMATE_POPUNDER_VERSION,
		TRUE
	);
	wp_enqueue_script(
		'ultimate_popunder_footer',
		plugin_dir_url( __FILE__ ) . 'assets/ultimate-popunder.js',
		array( 'lanund' ),
		ULTIMATE_POPUNDER_VERSION,
		TRUE
	);

	check_for_external_tracking();
}
add_action('wp_enqueue_scripts', 'ultimate_popunder_widget_enqueue_script');

/**
 * See if we are using an external tracker
 */
function check_for_external_tracking()
{
	$options = get_option( '_ultimate_popunder_settings' );

	if (isset( $options['ultimate_popunder_select_tracker'] ) )
	{
		if ($options['ultimate_popunder_select_tracker'] != '0')
		{
			wp_enqueue_script(
				'trackers_' . $options['ultimate_popunder_select_tracker'],
				plugin_dir_url( __FILE__ ) . 'assets/tracker.' . $options['ultimate_popunder_select_tracker'] . '.js',
				array( 'ultimate_popunder_footer' ),
				ULTIMATE_POPUNDER_VERSION,
				TRUE
			);
		}
	}
}

/**
 * Create our custom post type
 */
function create_ultimate_popunder()
{
	register_post_type( 'ultimate_popunder',
		array(
			'labels' => array(
				'name' => 'PopUnders',
				'singular_name' => 'PopUnder',
				'add_new' => 'Add New',
				'add_new_item' => 'Add New PopUnder',
				'edit' => 'Edit',
				'edit_item' => 'Edit PopUnder',
				'new_item' => 'New PopUnder',
				'view' => 'View',
				'view_item' => 'View PopUnder',
				'search_items' => 'Search PopUnders',
				'not_found' => 'No PopUnders found',
				'not_found_in_trash' => 'No PopUnders found in Trash',
				'parent' => 'Parent PopUnder'
				),
			'public' => true,
			'menu_position' => 15,
			'supports' => array( 'title' ),
			'taxonomies' => array( '' ),
			'publicly_queryable' => true,
			'menu_icon' => 'dashicons-external',
			'rewrite' => array(
				'slug' => 'upout',
				'with_front' => false,
				)
			)
		);
}
add_action( 'init', 'create_ultimate_popunder' );

/**
 * We don't want to see this post type, just send it to the link
 * @uses is_single()
 */
function ultimate_popunder_redirect_post()
{
	global $post;

	if ( is_single() && 'ultimate_popunder' ==  $post->post_type )
	{
		$post_meta = get_post_meta( $post->ID );
		wp_redirect( $post_meta['_popunder_url'][0], 302 );
		exit();
	}
}
add_action( 'template_redirect', 'ultimate_popunder_redirect_post' );

/**
 * Save the custom data from the popunder post
 * @param  Integer $post_id The post ID
 */
function ultimate_popunder_meta_box_save( $post_id )
{
	// Bail if we're doing an auto save
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

	// If our nonce isn't there, or we can't verify it, bail
	if( ! isset( $_POST['popunder_post_meta_nonce'] ) || ! wp_verify_nonce( $_POST['popunder_post_meta_nonce'], 'popunder_meta_box_nonce' ) ) return;

	// We are good to go
	if( current_user_can('author') || current_user_can('editor') || current_user_can('administrator') )
	{
		// Make sure the data is set before trying to save it
		if( isset( $_POST['_popunder_url'] ) )
			update_post_meta( $post_id, '_popunder_url', esc_url_raw( $_POST['_popunder_url'] ) );

		if( isset( $_POST['_popunder_post_type'] ) )
			update_post_meta( $post_id, '_popunder_post_type', sanitize_text_field( $_POST['_popunder_post_type'] ) );

		if( isset( $_POST['_popunder_post_visible'] ) )
			update_post_meta( $post_id, '_popunder_post_visible', sanitize_text_field( $_POST['_popunder_post_visible'] ) );

		if( isset( $_POST['_popunder_priority'] ) )
			update_post_meta( $post_id, '_popunder_priority', intval( $_POST['_popunder_priority'] ) );
	}
}
add_action( 'save_post', 'ultimate_popunder_meta_box_save' );

/**
 * Add the content to the custom post type
 */
function ultimate_popunder_meta_box_cb()
{
	global $post;

	$values = get_post_custom( $post->ID );

	$pop_url = isset( $values['_popunder_url'] ) ? $values['_popunder_url'][0] : '*';
	$pop_post_type = isset( $values['_popunder_post_type'] ) ? esc_attr( $values['_popunder_post_type'][0] ) : '';
	$pop_priority = isset( $values['_popunder_priority'] ) ? esc_attr( $values['_popunder_priority'][0] ) : '10';
	$pop_visibility = isset( $values['_popunder_post_visible'] ) ? esc_attr( $values['_popunder_post_visible'][0] ) : '*';

	wp_nonce_field( 'popunder_meta_box_nonce', 'popunder_post_meta_nonce' );

	$arr_post_types = get_post_types( array( 'public' => TRUE ) );
	?>

	<p>Provide the details for this PopUnder</p>

	<table id="ultimate_popunder_meta">
		<thead>
			<tr>
				<th style="text-align:left;"><label for="_popunder_url">URL</label></th>
				<th style="text-align:left;"><label for="_popunder_post_type">Post Type</label></th>
				<th style="text-align:left;"><label for="_popunder_post_visible">Visibility</label></th>
				<th style="text-align:left;"><label for="_popunder_priority">Priority</label></th>
			</tr>
		</thead>

		<tbody>
			<tr>
				<td style="width:55%;padding-right:10px">
					<input class="regular-text code" style="width:100%;" type="text" name="_popunder_url" id="_popunder_url" value="<?php echo $pop_url; ?>" />
				</td>
				<td style="width:15%;padding-right:10px">
					<select name="_popunder_post_type" id="_popunder_post_type" style="width:100%;">
						<option value="*"<?php if ($pop_post_type === "*") echo ' selected="selected"'; ?>>all</option>
						<?php
						foreach ($arr_post_types as $str_post_type)
						{
							if ($str_post_type === 'ultimate_popunder') continue;

							if ($str_post_type === $pop_post_type)
							{
								?><option value="<?php echo $str_post_type; ?>" selected="selected"><?php echo $str_post_type; ?></option><?php
							}
							else
							{
								?><option value="<?php echo $str_post_type; ?>"><?php echo $str_post_type; ?></option><?php
							}
						}
						?>
					</select>
				</td>
				<td style="width:15%;padding-right:10px">
					<select name="_popunder_post_visible" id="_popunder_post_visible" style="width:100%;">
						<option value="*"<?php if ($pop_visibility === "*") echo ' selected="selected"'; ?>>everyone</option>
						<?php
						$arr_visible_users = ['guest','member'];
						foreach ($arr_visible_users as $str_user_type)
						{
							if ($str_user_type === $pop_visibility)
							{
								?><option value="<?php echo $str_user_type; ?>" selected="selected"><?php echo $str_user_type; ?></option><?php
							}
							else
							{
								?><option value="<?php echo $str_user_type; ?>"><?php echo $str_user_type; ?></option><?php
							}
						}
						?>
					</select>
				</td>
				<td style="width:15%;padding-right:10px">
					<input style="width:100%;" type="number" name="_popunder_priority" id="_popunder_priority" onkeypress="return event.charCode >= 48 && event.charCode <= 57" value="<?php echo $pop_priority; ?>" />
				</td>
			</tr>
		</tbody>
	</table>

	<?php
}

/**
 * Show the meta box for the custom post type
 */
function uptimate_popunder_meta_box_add()
{
	add_meta_box(
		'ultimate-popunder-meta-box',
		'PopUnder Details',
		'ultimate_popunder_meta_box_cb',
		'ultimate_popunder',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'uptimate_popunder_meta_box_add' );

?>