<?php

/**
 * Register post type and meta boxes
 *
 * @link       https://walterpinem.me/
 * @since      1.0.0
 *
 * @package    Wp_Mylinks
 * @subpackage Wp_Mylinks/includes
 * @author     Walter Pinem <hello@walterpinem.me>
 */
// Register Custom Post Type for MyLink
function wp_mylinks_register_post_type()
{
	$labels = array(
		'name' 						=> _x('MyLinks', 'Post Type General Name', 'wp-mylinks'),
		'singular_name' 			=> _x('MyLink', 'Post Type Singular Name', 'wp-mylinks'),
		'menu_name' 				=> _x('MyLinks', 'Admin Menu text', 'wp-mylinks'),
		'name_admin_bar' 			=> _x('MyLink', 'Add New on Toolbar', 'wp-mylinks'),
		'archives' 					=> __('MyLink Archives', 'wp-mylinks'),
		'attributes' 				=> __('MyLink Attributes', 'wp-mylinks'),
		'parent_item_colon' 		=> __('Parent MyLink:', 'wp-mylinks'),
		'all_items' 				=> __('All MyLinks', 'wp-mylinks'),
		'add_new_item' 				=> __('Add New MyLink', 'wp-mylinks'),
		'add_new' 					=> __('Add New', 'wp-mylinks'),
		'new_item' 					=> __('New MyLink', 'wp-mylinks'),
		'edit_item' 				=> __('Edit MyLink', 'wp-mylinks'),
		'update_item' 				=> __('Update MyLink', 'wp-mylinks'),
		'view_item' 				=> __('View MyLink', 'wp-mylinks'),
		'view_items' 				=> __('View MyLinks', 'wp-mylinks'),
		'search_items' 				=> __('Search MyLink', 'wp-mylinks'),
		'not_found' 				=> __('Not found', 'wp-mylinks'),
		'not_found_in_trash' 		=> __('Not found in Trash', 'wp-mylinks'),
		'featured_image' 			=> __('Featured Image', 'wp-mylinks'),
		'set_featured_image' 		=> __('Featured Image', 'wp-mylinks'),
		'remove_featured_image' 	=> __('Remove featured image', 'wp-mylinks'),
		'use_featured_image' 		=> __('Use as featured image', 'wp-mylinks'),
		'insert_into_item' 			=> __('Insert into MyLink', 'wp-mylinks'),
		'uploaded_to_this_item' 	=> __('Uploaded to this MyLink', 'wp-mylinks'),
		'items_list' 				=> __('MyLinks list', 'wp-mylinks'),
		'items_list_navigation' 	=> __('MyLinks list navigation', 'wp-mylinks'),
		'filter_items_list' 		=> __('Filter MyLinks list', 'wp-mylinks'),
		'register_meta_box_cb' 		=> 'wp_mylinks_register_metabox',
	);
	$rewrite = array(
		'with_front' 	=> false,
		'pages' 		=> false,
		'feeds' 		=> false,
		'slug' 			=> '/'
	);
	$args = array(
		'label' 				=> __('MyLink', 'wp-mylinks'),
		'description' 			=> __('Create micro landing pages that host all of your contents, products, etc to engage your audience and increase your brand awareness.', 'wp-mylinks'),
		'labels' 				=> $labels,
		'menu_icon' 			=> 'dashicons-editor-unlink',
		'supports' 				=> array('title'),
		'taxonomies' 			=> array(),
		'public' 				=> true,
		'show_ui' 				=> true,
		'show_in_menu' 			=> true,
		'menu_position' 		=> 26,
		'show_in_admin_bar' 	=> false,
		'show_in_nav_menus' 	=> true,
		'can_export' 			=> true,
		'has_archive' 			=> false,
		'hierarchical' 			=> false,
		'exclude_from_search' 	=> true,
		'show_in_rest' 			=> true,
		'query_var' 			=> 'mylink',
		'publicly_queryable' 	=> true,
		'capability_type' 		=> 'page',
		'rewrite' 				=> $rewrite,
	);
	register_post_type('mylink', $args);
	// flush_rewrite_rules();
}
add_action('init', 'wp_mylinks_register_post_type', 0);

/**
 * Flush Rewrite Rules for the post type
 * @since 1.0.5
 * Code by WPMUDEV
 */
function wp_mylinks_rewrite_flush()
{
	wp_mylinks_register_post_type();
	flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'wp_mylinks_rewrite_flush');

// Generate Metabox for MyLink post type
add_action('cmb2_admin_init', 'wp_mylinks_register_cmb2_metaboxes');
/**
 * Define the metabox and field configurations.
 */
function wp_mylinks_register_cmb2_metaboxes()
{
	$cmb = new_cmb2_box(
		[
			'id'           => mylinks_prefix('form'),
			'title'        => __('MyLinks Profile', 'wp-mylinks'),
			'object_types' => ['mylink'],
			'context'      => 'normal',
			'priority'     => 'high',
		]
	);
	$cmb->add_field(
		[
			'name'         => __('Avatar', 'wp-mylinks'),
			'desc'         => __('Upload an avatar, preferably square in size.', 'wp-mylinks'),
			'id'           => mylinks_prefix('avatar'),
			'type'         => 'file',
			'before_row'   => __('Build a profile section for your MyLinks page.', 'wp-mylinks'),
			'options'      => [
				'url'  => false, // Hide the text input for the url
			],
			'text'         => [
				'add_upload_file_text' => 'Choose Avatar',
			],
			'query_args'   => [
				'type' => 'image',
			],
			'preview_size' => 'thumbnail',
		]
	);
	$cmb->add_field(
		[
			'name'    		=> __('Avatar Style', 'wp-mylinks'),
			'id'			=> mylinks_prefix('avatar-style'),
			'desc'			=> __('Choose the avatar style.', 'wp-mylinks'),
			'type'			=> 'radio_inline',
			'show_option_none' => false,
			'options'		=> array(
				'story-like'    => __('Story-Like Border', 'wp-mylinks'),
				'shadow'        => __('Shadowy', 'wp-mylinks'),
				'plain'         => __('Plain', 'wp-mylinks'),
			),
			'default' => 'story-like',
		]
	);
	$cmb->add_field(
		[
			'name'    => __('Name', 'wp-mylinks'),
			'desc'    => __('Add your name or username that will be shown under your avatar.', 'wp-mylinks'),
			'id'      => mylinks_prefix('name'),
			'type'    => 'text',
			'attributes'  => array(
				'class' => 'mylinks-input-text',
			),
			'options' => [],
		]
	);
	$cmb->add_field(
		[
			'name'    => __('Description', 'wp-mylinks'),
			'desc'    => __('Add your profile description that will be shown under your name. Compatible with HTML.', 'wp-mylinks'),
			'id'      => mylinks_prefix('description'),
			'type'    => 'textarea_small',
			'attributes'  => array(
				'class' => 'mylinks-input-textarea',
			),
			'options' => [],
		]
	);
	$cmb->add_field(
		[
			'name'             => __('Theme', 'wp-mylinks'),
			'desc'    		   => __('Choose a theme that matches your personal or business brand.', 'wp-mylinks'),
			'id'               => mylinks_prefix('theme'),
			'before_row'   	   => __('Determine how you want the MyLinks page to look like. You can set its theme individually here or select <code>None</code>to<br> apply global theme you set on <a href="edit.php?post_type=mylink&page=welcome&tab=global" target="_blank"><strong>Global Configurations</strong></a> page instead.', 'wp-mylinks'),
			'type'             => 'select',
			'show_option_none' => false,
			'default'          => 'default',
			'attributes'  	   => array(
				'class' 	   => 'mylinks-input-select',
			),
			'options_cb'       => 'wp_mylinks_theme_callback',
		]
	);

	// Social Media Profiles
	$cmb = new_cmb2_box(
		[
			'id'           => mylinks_prefix('form-social'),
			'title'        => __('Social Media', 'wp-mylinks'),
			'object_types' => ['mylink'],
			'context'      => 'normal',
			'priority'     => 'high',
		]
	);
	$cmb->add_field(
		[
			'name'    		=> __('Position', 'wp-mylinks'),
			'id'			=> mylinks_prefix('social-media-position'),
			'desc'			=> __('Choose the Social Media profile icons position.', 'wp-mylinks'),
			'type'			=> 'radio_inline',
			'show_option_none' => false,
			'options'		=> array(
				'top'		=> __('Top', 'wp-mylinks'),
				'bottom'	=> __('Bottom', 'wp-mylinks'),
			),
			'default' => 'top',
		]
	);
	// Facebook
	$cmb->add_field(
		[
			'name'    => __('Facebook URL', 'wp-mylinks'),
			'desc'    => __('Add your Facebook profile.', 'wp-mylinks'),
			'id'      => mylinks_prefix('facebook-url'),
			'type'    => 'text_url',
			'before_row'   => __('Setup social links that will appear under your profile section.', 'wp-mylinks'),
			'attributes'  => array(
				'class' => 'mylinks-input-url',
			),
			'options' => [],
		]
	);
	$cmb->add_field(
		[
			'name'         => __('Facebook Icon', 'wp-mylinks'),
			'desc'         => __('If you don\'t like the default icon please upload your own icon for Facebook, preferably square in size.', 'wp-mylinks'),
			'id'           => mylinks_prefix('facebook-icon'),
			'type'         => 'file',
			'options'      => [
				'url'  => true, // Hide the text input for the url
			],
			'text'	   => [
				'add_upload_file_text' => 'Choose Icon',
			],
			'query_args'   => [
				'type' => 'image',
			],
			'preview_size' => 'thumbnail',
		]
	);
	// Twitter
	$cmb->add_field(
		[
			'name'    => __('Twitter URL', 'wp-mylinks'),
			'desc'    => __('Add your Twitter profile.', 'wp-mylinks'),
			'id'      => mylinks_prefix('twitter-url'),
			'type'    => 'text_url',
			'attributes'  => array(
				'class' => 'mylinks-input-url',
			),
			'options' => [],
		]
	);
	$cmb->add_field(
		[
			'name'         => __('Twitter Icon', 'wp-mylinks'),
			'desc'         => __('If you don\'t like the default icon please upload your own icon for Twitter, preferably square in size.', 'wp-mylinks'),
			'id'           => mylinks_prefix('twitter-icon'),
			'type'         => 'file',
			'options'      => [
				'url'  => true, // Hide the text input for the url
			],
			'text'         => [
				'add_upload_file_text' => 'Choose Icon',
			],
			'query_args'   => [
				'type' => 'image',
			],
			'preview_size' => 'thumbnail',
		]
	);
	// Linkedin
	$cmb->add_field(
		[
			'name'    => __('Linkedin URL', 'wp-mylinks'),
			'desc'    => __('Add your Linkedin profile.', 'wp-mylinks'),
			'id'      => mylinks_prefix('linkedin-url'),
			'type'    => 'text_url',
			'attributes'  => array(
				'class' => 'mylinks-input-url',
			),
			'options' => [],
		]
	);
	$cmb->add_field(
		[
			'name'         => __('Linkedin Icon', 'wp-mylinks'),
			'desc'         => __('If you don\'t like the default icon please upload your own icon for Linkedin, preferably square in size.', 'wp-mylinks'),
			'id'           => mylinks_prefix('linkedin-icon'),
			'type'         => 'file',
			'options'      => [
				'url'  => true, // Hide the text input for the url
			],
			'text'	   => [
				'add_upload_file_text' => 'Choose Icon',
			],
			'query_args'   => [
				'type' => 'image',
			],
			'preview_size' => 'thumbnail',
		]
	);
	// Instagram
	$cmb->add_field(
		[
			'name'    => __('Instagram URL', 'wp-mylinks'),
			'desc'    => __('Add your Instagram profile.', 'wp-mylinks'),
			'id'      => mylinks_prefix('instagram-url'),
			'type'    => 'text_url',
			'attributes'  => array(
				'class' => 'mylinks-input-url',
			),
			'options' => [],
		]
	);
	$cmb->add_field(
		[
			'name'         	=> __('Instagram Icon', 'wp-mylinks'),
			'desc'         	=> __('If you don\'t like the default icon please upload your own icon for Instagram, preferably square in size.', 'wp-mylinks'),
			'id'           	=> mylinks_prefix('instagram-icon'),
			'type'         	=> 'file',
			'options'      	=> [
				'url'	=> true, // Hide the text input for the url
			],
			'text'		=> [
				'add_upload_file_text' => 'Choose Icon',
			],
			'query_args'   	=> [
				'type'	   	=> 'image',
			],
			'preview_size' 	=> 'thumbnail',
		]
	);
	// Youtube
	$cmb->add_field(
		[
			'name'    => __('Youtube URL', 'wp-mylinks'),
			'desc'    => __('Add your Youtube profile.', 'wp-mylinks'),
			'id'      => mylinks_prefix('youtube-url'),
			'type'    => 'text_url',
			'attributes'  => array(
				'class' => 'mylinks-input-url',
			),
			'options' => [],
		]
	);
	$cmb->add_field(
		[
			'name'         	=> __('Youtube Icon', 'wp-mylinks'),
			'desc'         	=> __('If you don\'t like the default icon please upload your own icon for Youtube, preferably square in size.', 'wp-mylinks'),
			'id'           	=> mylinks_prefix('youtube-icon'),
			'type'         	=> 'file',
			'options'      	=> [
				'url' 		=> true, // Hide the text input for the url
			],
			'text'         	=> [
				'add_upload_file_text' => 'Choose Icon',
			],
			'query_args'   	=> [
				'type' 		=> 'image',
			],
			'preview_size' => 'thumbnail',
		]
	);
	// Pinterest
	$cmb->add_field(
		[
			'name'    => __('Pinterest URL', 'wp-mylinks'),
			'desc'    => __('Add your Pinterest profile.', 'wp-mylinks'),
			'id'      => mylinks_prefix('pinterest-url'),
			'type'    => 'text_url',
			'attributes'  => array(
				'class' => 'mylinks-input-url',
			),
			'options' => [],
		]
	);
	$cmb->add_field(
		[
			'name'         	=> __('Pinterest Icon', 'wp-mylinks'),
			'desc'         	=> __('If you don\'t like the default icon please upload your own icon for Pinterest, preferably square in size.', 'wp-mylinks'),
			'id'           	=> mylinks_prefix('pinterest-icon'),
			'type'         	=> 'file',
			'options'      	=> [
				'url' 		=> true, // Hide the text input for the url
			],
			'text'         	=> [
				'add_upload_file_text' => 'Choose Icon',
			],
			'query_args'   	=> [
				'type' 		=> 'image',
			],
			'preview_size' => 'thumbnail',
		]
	);
	// TikTok
	$cmb->add_field(
		[
			'name'    => __('TikTok URL', 'wp-mylinks'),
			'desc'    => __('Add your TikTok profile.', 'wp-mylinks'),
			'id'      => mylinks_prefix('tiktok-url'),
			'type'    => 'text_url',
			'attributes'  => array(
				'class' => 'mylinks-input-url',
			),
			'options' => [],
		]
	);
	$cmb->add_field(
		[
			'name'         => __('TikTok Icon', 'wp-mylinks'),
			'desc'         => __('If you don\'t like the default icon please upload your own icon for TikTok, preferably square in size.', 'wp-mylinks'),
			'id'           => mylinks_prefix('tiktok-icon'),
			'type'         => 'file',
			'options'      => [
				'url'  => true, // Hide the text input for the url
			],
			'text'         => [
				'add_upload_file_text' => 'Choose Icon',
			],
			'query_args'   => [
				'type' => 'image',
			],
			'preview_size' => 'thumbnail',
		]
	);
	// Discord
	$cmb->add_field(
		[
			'name'    => __('Discord URL', 'wp-mylinks'),
			'desc'    => __('Add your Discord profile.', 'wp-mylinks'),
			'id'      => mylinks_prefix('discord-url'),
			'type'    => 'text_url',
			'attributes'  => array(
				'class' => 'mylinks-input-url',
			),
			'options' => [],
		]
	);
	$cmb->add_field(
		[
			'name'         => __('Discord Icon', 'wp-mylinks'),
			'desc'         => __('If you don\'t like the default icon please upload your own icon for Discord, preferably square in size.', 'wp-mylinks'),
			'id'           => mylinks_prefix('discord-icon'),
			'type'         => 'file',
			'options'      => [
				'url'  => true, // Hide the text input for the url
			],
			'text'         => [
				'add_upload_file_text' => 'Choose Icon',
			],
			'query_args'   => [
				'type' => 'image',
			],
			'preview_size' => 'thumbnail',
		]
	);
	// END - Social Media Profiles

	// Links
	$cmb = new_cmb2_box(
		[
			'id'           => mylinks_prefix('form-links'),
			'title'        => __('Links', 'wp-mylinks'),
			'object_types' => ['mylink'],
			'context'      => 'normal',
			'priority'     => 'high',
		]
	);
	$links_group = $cmb->add_field(
		[
			'id'          		=> mylinks_prefix('links'),
			'type'        		=> 'group',
			'description' 		=> __('Set a link and its title. You can add unlimited number of buttons to your liking by clicking "Add Another Link" button.', 'wp-mylinks'),
			'options'     		=> [
				'group_title'   => __('Link {#}', 'wp-mylinks'),
				'add_button'    => __('Add Another Link', 'wp-mylinks'),
				'remove_button' => __('Remove Link', 'wp-mylinks'),
				'sortable'      => true,
				'remove_confirm' => esc_html__('Really? Remove This Link?', 'wp-mylinks'),
			],
		]
	);
	$cmb->add_group_field(
		$links_group,
		[
			'name' => __('Title', 'wp-mylinks'),
			'id'   => 'title',
			'type' => 'text',
			'attributes'  => array(
				'class' => 'mylinks-input-text',
			),
		]
	);

	$cmb->add_group_field(
		$links_group,
		[
			'name' => __('Link URL', 'wp-mylinks'),
			'id'   => 'select_url',
			'type' => 'pw_select',
			'description' => __('Select a link from the list you have previously prepared on <a href="' . esc_url(admin_url() . 'edit.php?post_type=mylinks-collection') . '" target="_blank"><strong>Collection</strong></span></a> page, existing posts or pages.', 'wp-mylinks'),
			'options_cb' => 'iweb_get_cmb2_post_options',
			'wp_query_args' => array(
				'post_type' => ['page', 'post', 'mylinks-collection'],
			),
			'attributes' => array(
				'class' => array('mylinks-input-select2'),
				'placeholder' => 'Select a link...',
				'data-maximum-selection-length' => '2',
			),
			'select_all_button' => false,
		]
	);

	$cmb->add_group_field(
		$links_group,
		[
			'name' => __('Selected URL', 'wp-mylinks'),
			'id'   => 'url',
			'type' => 'text',
			'description' 	=> __('If you want to manually add a link or edit it, just do it here.', 'wp-mylinks'),
			'attributes'  => array(
				'class' => 'mylinks-selected-url',
			),
		]
	);

	$cmb->add_group_field(
		$links_group,
		[
			'name' 			=> __('Image', 'wp-mylinks'),
			'id'   			=> 'image',
			'type' 			=> 'file',
			'preview_size' 	=> 'thumbnail',
			'description' 	=> __('Add featured image for your link, preferably square in size or use a landscape-sized image if you choose <b>Yes</b> for the <b>Use Card Layout for this Link?</b> option below.', 'wp-mylinks'),
			'options' => array(
				'add_upload_file_text' => __('Featured Image', 'wp-mylinks'),
			),
		]
	);
	$cmb->add_group_field(
		$links_group,
		[
			'name'    		=> __('Use Card Layout for this Link?', 'wp-mylinks'),
			'id'			=> 'card-layout',
			'desc'			=> __('If <b>Yes</b> is chosen, the featured image you chose on the <b>Image</b> section above will be used as the background image of this link. <br/>Please make sure to fill all the <b>Title</b>, <b>URL</b>, <b>Image</b> fields above and <b>empty</b> the Youtube Video field below.', 'wp-mylinks'),
			'type'			=> 'radio',
			'show_option_none' => false,
			'options'		=> array(
				'yes'		=> __('Yes', 'wp-mylinks'),
				'no'		=> __('No', 'wp-mylinks'),
			),
			'default' => 'no',
		]
	);
	$cmb->add_group_field(
		$links_group,
		[
			'name' 			=> __('Youtube Video', 'wp-mylinks'),
			'id'   			=> 'youtube-video',
			'type' 			=> 'oembed',
			'attributes'  	=> array(
				'class' => 'mylinks-input-url',
			),
			'description' 	=> __('Enter the full Youtube video URL. <b>Please do not fill</b> the other fields, make sure to only fill the Youtube URL field.', 'wp-mylinks'),
		]
	);
	$cmb->add_group_field(
		$links_group,
		[
			'name' 			=> __('Media Embed', 'wp-mylinks'),
			'id'   			=> 'media-embed',
			'type' 			=> 'oembed',
			'attributes'  	=> array(
				'class' => 'mylinks-input-url',
			),
			'description' 	=> __('You can embed TikTok videos, Tweets, Spotify playlist, or any other media from the list of <a href="https://wordpress.org/documentation/article/embeds/#list-of-sites-you-can-embed-from" target="_blank" rel="noopener noreferrer">WordPress\'s supported sites</a>.', 'wp-mylinks'),
		]
	);

	// Meta Tags
	$cmb = new_cmb2_box(
		[
			'id'           => mylinks_prefix('form-meta-tags'),
			'title'        => __('Setup Meta Tags & Favicon', 'wp-mylinks'),
			'description'    => __('If you use Yoast SEO, set the meta title on Yoast\'s meta box instead.', 'wp-mylinks'),
			'object_types' => ['mylink'],
			'context'      => 'normal',
			'priority'     => 'high',
		]
	);
	$cmb->add_field(
		[
			'name'    => __('Meta Title', 'wp-mylinks'),
			'desc'    => __('Set the meta title for this page.', 'wp-mylinks'),
			'id'      => mylinks_prefix('meta-title'),
			'type'    => 'text',
			'before_row'   => __('If you use Yoast SEO, you can set the meta tags on Yoast SEO\'s meta box. Or you can also set global meta tags on<br> <a href="edit.php?post_type=mylink&page=welcome&tab=global" target="_blank"><strong>Global Configurations</strong></a>. You can also set custom favicon independently for this MyLinks page.', 'wp-mylinks'),
			'attributes'  => array(
				'class' => 'mylinks-input-text',
			),
			'options' => [],
		]
	);
	$cmb->add_field(
		[
			'name'    => __('Meta Description', 'wp-mylinks'),
			'desc'    => __('Set the meta description for this page.', 'wp-mylinks'),
			'id'      => mylinks_prefix('meta-description'),
			'type'    => 'textarea_small',
			'attributes'  => array(
				'class' => 'mylinks-input-textarea',
			),
			'options' => [],
		]
	);
	$cmb->add_field(
		[
			'name'    => __('Set to Noindex?', 'wp-mylinks'),
			'desc'    => __('This will prevent MyLinks page from being indexed on search engine.', 'wp-mylinks'),
			'id'      => mylinks_prefix('noindex'),
			'type'             => 'radio',
			'show_option_none' => false,
			'options'          => array(
				'yes' => __('Yes', 'wp-mylinks'),
				'no'   => __('No', 'wp-mylinks'),
			),
		]
	);
	$cmb->add_field(
		[
			'name'    => __('Set to Nofollow?', 'wp-mylinks'),
			'desc'    => __('This will ban crawlers to follow all the links on the MyLinks page.', 'wp-mylinks'),
			'id'      => mylinks_prefix('nofollow'),
			'type'             => 'radio',
			'show_option_none' => false,
			'options'          => array(
				'yes' => __('Yes', 'wp-mylinks'),
				'no'   => __('No', 'wp-mylinks'),
			),
		]
	);
	$cmb->add_field(
		[
			'name'         => __('Favicon', 'wp-mylinks'),
			'desc'         => __('Set custom favicon for this MyLinks page.', 'wp-mylinks'),
			'id'           => mylinks_prefix('single-favicon'),
			'type'         => 'file',
			'options'      => [
				'url'  => true, // Hide the text input for the url
			],
			'text'         => [
				'add_upload_file_text' => 'Choose Favicon',
			],
			'query_args'   => [
				'type' => 'image',
			],
			'preview_size' => 'thumbnail',
		]
	);

	// Custom Script and Styles
	$cmb = new_cmb2_box(
		[
			'id'           => mylinks_prefix('form-custom-script-styles'),
			'title'        => __('Custom Scripts & Styles', 'wp-mylinks'),
			'description'    => __('Add custom script and styles independently for this MyLinks page.', 'wp-mylinks'),
			'object_types' => ['mylink'],
			'context'      => 'normal',
			'priority'     => 'high',
		]
	);
	$cmb->add_field(
		[
			'name'    => __('Header Script', 'wp-mylinks'),
			'desc'    => __('Anything you put here will be included inside <code>&lt;head&gt;</code>. Please include <code>&lt;script&gt;</code> etc.', 'wp-mylinks'),
			'id'      => mylinks_prefix('mylinks-single-custom-header-script'),
			'type'    => 'textarea_small',
			'before_row'   => __('Add custom scripts and styles independently for this MyLinks page. Or you can leave these fields blank and set it on<br> <a href="edit.php?post_type=mylink&page=welcome&tab=global" target="_blank"><strong>Global Configurations</strong></a> instead.', 'wp-mylinks'),
			'attributes'  => array(
				'class' => 'mylinks-input-textarea',
			),
			'sanitization_cb' => 'wp_mylinks_sanitization_func',
			'options' => [],
		]
	);
	$cmb->add_field(
		[
			'name'    => __('Footer Script', 'wp-mylinks'),
			'desc'    => __('Anything you put here will be placed just before <code>&lt;/body&gt;</code>. Please include <code>&lt;script&gt;</code> etc.', 'wp-mylinks'),
			'id'      => mylinks_prefix('mylinks-single-custom-footer-script'),
			'type'    => 'textarea_small',
			'attributes'  => array(
				'class' => 'mylinks-input-textarea',
			),
			'sanitization_cb' => 'wp_mylinks_sanitization_func',
			'options' => [],
		]
	);
	$cmb->add_field(
		[
			'name'    => __('Custom Styles', 'wp-mylinks'),
			'desc'    => __('Add your custom css code without the <code>&lt;style&gt;</code> tag.', 'wp-mylinks'),
			'id'      => mylinks_prefix('mylinks-single-custom-styles'),
			'type'    => 'textarea_small',
			'attributes'  => array(
				'class' => 'mylinks-input-textarea',
			),
			'options' => [],
		]
	);
	// END - Single Favicon	
}

/**
 * Added custom sidebars for UTM Tag Generator & Support Links
 * @since 1.0.3
 */
function wp_mylinks_custom_sidebar_utm()
{
	echo '<ul>';
	echo '<li><a href="https://walterpinem.me/projects/utm-tag-campaign-builder/?utm_source=mylink-editor&utm_medium=sidebar&utm_campaign=WP-MyLinks" target="_blank" rel="noopener" style="text-decoration: none!important;"><strong>🇬🇧 English Version</strong></a></li>';
	echo '<li><a href="https://www.seniberpikir.com/utm-campaign-builder-google-analytics/?utm_source=mylink-editor&utm_medium=sidebar&utm_campaign=WP-MyLinks" target="_blank" rel="noopener" style="text-decoration: none!important;"><strong>🇮🇩 Bahasa Version</strong></a></li>';
	echo '</ul>';
}
function wp_mylinks_custom_sidebar_suppport()
{
	echo '<ul>';
	echo '<li><a href="https://wordpress.org/support/plugin/wp-mylinks/reviews/?rate=5#new-post" target="_blank" rel="noopener" style="text-decoration: none!important;"><strong>&#11088; Rate This Plugin</strong></a></li>';
	echo '<li><a href="https://walterpinem.me/projects/contact/?utm_source=mylink-editor&utm_medium=sidebar&utm_campaign=WP-MyLinks" target="_blank" rel="noopener" style="text-decoration: none!important;"><strong>&#128199; Support & Feature Request</strong></a></li>';
	echo '<li><a href="https://paypal.me/walterpinem" target="_blank" rel="noopener" style="text-decoration: none!important;"><strong>&#9749; Buy Me a Coffee</strong></a></li>';
	echo '<li><a href="https://walterpinem.me/projects/tools/?utm_source=mylink-editor&utm_medium=sidebar&utm_campaign=WP-MyLinks" target="_blank" rel="noopener" style="text-decoration: none!important;"><strong>&#128736;&#65039; 100% Free Online Tools</strong></a></li>';
	echo '</ul>';
}
function wp_mylinks_add_side_meta_box()
{
	add_meta_box("mylink-sidebar-utm", "UTM Campaign Builder", "wp_mylinks_custom_sidebar_utm", ["mylink", "mylinks-collection"], "side", "low", null);
	add_meta_box("mylink-sidebar-support", "Support", "wp_mylinks_custom_sidebar_suppport", ["mylink", "mylinks-collection"], "side", "low", null);
}
add_action("add_meta_boxes", "wp_mylinks_add_side_meta_box");
