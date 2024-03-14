<?php

function siteorigin_masonry_options_admin_menu() {
	add_options_page( __('SiteOrigin Masonry', 'so-masonry'), __('Masonry', 'so-masonry'), 'manage_options', 'siteorigin_masonry', 'siteorigin_masonry_options_page' );
}
add_action( 'admin_menu', 'siteorigin_masonry_options_admin_menu' );

function siteorigin_masonry_options_page(){
	include plugin_dir_path(__FILE__) . '../tpl/options.php';
}

/**
 * Initialize the masonry plugin settings
 */
function siteorigin_masonry_options_init() {
	register_setting( 'siteorigin-masonry-group', 'siteorigin_masonry_post_types', 'siteorigin_masonry_options_sanitize_post_types' );
	add_settings_section( 'general', __('General', 'so-masonry'), false, 'siteorigin-masonry' );
	add_settings_field( 'post-types', __('Post Types', 'so-masonry'), 'siteorigin_masonry_options_field_post_types', 'siteorigin-masonry', 'general' );
}
add_action( 'admin_init', 'siteorigin_masonry_options_init' );

/**
 * Display the post types options
 *
 * @param $args
 */
function siteorigin_masonry_options_field_post_types($args){
	$masonry_post_types = get_option('siteorigin_masonry_post_types', array('post'));

	$all_post_types = get_post_types(array('_builtin' => false));
	$all_post_types = array_merge(array('page' => 'page', 'post' => 'post'), $all_post_types);

	foreach($all_post_types as $type){
		$info = get_post_type_object($type);
		if(empty($info->labels->name)) continue;
		$checked = in_array(
			$type,
			$masonry_post_types
		);

		?>
		<label>
			<input type="checkbox" name="siteorigin_masonry_post_types[<?php echo esc_attr($type) ?>]" value="<?php echo esc_attr($type) ?>" <?php checked($checked) ?> />
			<?php echo esc_html($info->labels->name) ?>
		</label><br/>
		<?php
	}

	?><p class="description"><?php _e('Post types that will have the masonry brick size option.', 'so-masonry') ?></p><?php
}

/**
 * Sanitize the post types option
 *
 * @param $types
 * @return array
 */
function siteorigin_masonry_options_sanitize_post_types($types){
	$all_post_types = get_post_types(array('_builtin' => false));
	$all_post_types = array_merge(array('post' => 'post', 'page' => 'page'), $all_post_types);
	foreach($types as $type => $val){
		if(!in_array($type, $all_post_types)) unset($types[$type]);
		else $types[$type] = !empty($types[$type]);
	}

	// Only non empty items
	return array_keys(array_filter($types));
}