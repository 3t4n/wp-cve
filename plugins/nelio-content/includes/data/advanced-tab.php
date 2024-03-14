<?php
/**
 * List of settings.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/data
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

return array(

	array(
		'type'  => 'section',
		'name'  => 'social-settings',
		'label' => nc_make_settings_title( esc_html_x( 'Social Settings', 'text', 'nelio-content' ), 'admin-users' ),
	),

	array(
		'type'     => 'custom',
		'name'     => 'calendar_post_types',
		'label'    => esc_html_x( 'Managed Post Types', 'text', 'nelio-content' ),
		'instance' => new Nelio_Content_Calendar_Post_Type_Setting(),
		'default'  => array( 'post' ),
	),

	array(
		'type'    => 'select',
		'name'    => 'auto_share_default_mode',
		'label'   => esc_html_x( 'Automatic Social Sharing', 'text', 'nelio-content' ),
		'desc'    => esc_html_x( 'Nelio Content can automatically share content on your social media according to your preferences:', 'text', 'nelio-content' ),
		'default' => 'include-in-auto-share',
		'options' => array(
			array(
				'value' => 'include-in-auto-share',
				'label' => esc_html_x( 'Include all posts, unless stated otherwise', 'command', 'nelio-content' ),
				'desc'  => esc_html_x( 'Nelio Content can automatically share any post on your social profiles, unless you’ve explicitly excluded it from resharing.', 'text', 'nelio-content' ),
			),
			array(
				'value' => 'exclude-from-auto-share',
				'label' => esc_html_x( 'Exclude all posts, unless stated otherwise', 'command', 'nelio-content' ),
				'desc'  => esc_html_x( 'Nelio Content will only automatically share those posts that you’ve manually marked as eligible for automatic sharing.', 'text', 'nelio-content' ),
			),
		),
	),

	array(
		'type'  => 'section',
		'name'  => 'advanced-setups',
		'label' => nc_make_settings_title( esc_html_x( 'Plugin Setup', 'text', 'nelio-content' ), 'admin-generic' ),
	),

	array(
		'type'    => 'checkbox',
		'name'    => 'use_notifications',
		'label'   => esc_html_x( 'Notifications', 'text', 'nelio-content' ),
		'desc'    => esc_html_x( 'Send email notifications when the status of a post is updated, when an editorial task is created or completed, or when an editorial comment is added.', 'command', 'nelio-content' ),
		'default' => false,
	),

	array(
		'type'     => 'custom',
		'name'     => 'cloud_notification_emails',
		'label'    => esc_html_x( 'Cloud Notifications', 'text', 'nelio-content' ),
		'instance' => new Nelio_Content_Cloud_Notification_Emails_Setting(),
		'default'  => '',
	),

	array(
		'type'     => 'custom',
		'name'     => 'use_ics_subscription',
		'label'    => esc_html_x( 'iCal Calendar Feed', 'text', 'nelio-content' ),
		'desc'     => esc_html_x( 'Export your calendar posts to Google Calendar or any other calendar tool.', 'user', 'nelio-content' ),
		'instance' => new Nelio_Content_ICS_Calendar_Setting(),
		'default'  => false,
	),

	array(
		'type'    => 'checkbox',
		'name'    => 'use_missed_schedule_handler',
		'label'   => esc_html_x( 'Missed Schedule Handler', 'text', 'nelio-content' ),
		'desc'    => esc_html_x( 'Check for scheduled WordPress posts not properly published with a “missed schedule” error and automatically publish them.', 'command', 'nelio-content' ),
		'default' => false,
	),

	array(
		'type'    => 'checkbox',
		'name'    => 'are_meta_tags_active',
		'label'   => esc_html_x( 'Meta', 'text', 'nelio-content' ),
		'desc'    => esc_html_x( 'Add Facebook’s Open Graph and X’s Card meta tags on shared content from your site.', 'command', 'nelio-content' ),
		'default' => false,
	),

	array(
		'type'    => 'checkbox',
		'name'    => 'are_auto_tutorials_enabled',
		'label'   => '',
		'desc'    => _x( 'Show plugin tutorials automatically to introduce new users to Nelio Content’s features.', 'command', 'nelio-content' ),
		'default' => true,
	),

	array(
		'type'  => 'section',
		'name'  => 'analytics',
		'label' => nc_make_settings_title( esc_html_x( 'Analytics', 'text', 'nelio-content' ), 'chart-bar' ),
	),

	array(
		'type'    => 'checkbox',
		'name'    => 'use_analytics',
		'label'   => esc_html_x( 'Basic', 'text (analytics)', 'nelio-content' ),
		'desc'    => esc_html_x( 'Enable analytics for Nelio Content’s managed post types.', 'command', 'nelio-content' ),
		'default' => false,
	),

	array(
		'type'     => 'custom',
		'name'     => 'ga4_property_id',
		'label'    => esc_html_x( 'Analytics Data', 'text', 'nelio-content' ),
		'instance' => new Nelio_Content_Google_Analytics_Setting(),
		'default'  => '',
	),

	array(
		'type'  => 'section',
		'name'  => 'nelioefi',
		'label' => nc_make_settings_title( esc_html_x( 'External Featured Images', 'text', 'nelio-content' ), 'format-image' ),
	),

	array(
		'type'    => 'checkbox',
		'name'    => 'use_external_featured_image',
		'label'   => esc_html_x( 'External Featured Images', 'text', 'nelio-content' ),
		'desc'    => esc_html_x( 'Enable External Featured Images.', 'command', 'nelio-content' ),
		'default' => true,
	),

	array(
		'type'    => 'select',
		'name'    => 'efi_mode',
		'label'   => esc_html_x( 'Mode', 'text', 'nelio-content' ),
		'desc'    => _x( 'Themes can insert featured images in different ways. For example, some themes use a WordPress function named <code>(get_)the_post_thumbnail</code> whereas others use a combination of <code>wp_get_attachment_image_src</code> and <code>get_post_thumbnail_id</code>. Depending on how your theme operates, Nelio Content may or may not be compatible with it. In order to maximize the number of compatible themes, the plugin implements different <em>modes</em>.', 'html', 'nelio-content' ),
		'default' => 'default',
		'options' => array(
			array(
				'value' => 'default',
				'label' => esc_html_x( 'Default Mode', 'text', 'nelio-content' ),
				'desc'  => _x( 'This mode assumes your theme uses the function <code>(get_)the_post_thumbnail</code> for inserting featured images. For example, WordPress default themes should work with this setting.', 'text', 'nelio-content' ),
			),
			array(
				'value' => 'double-quotes',
				'label' => esc_html_x( 'Double-Quote Mode', 'text', 'nelio-content' ),
				'desc'  => _x( 'If your theme retrieves the URL of the featured image and outputs it within an <code>img</code> tag, this mode might be the one you need. Compatible themes include Newspaper, Newsmag, Enfold, and others.', 'text', 'nelio-content' ),
			),
			array(
				'value' => 'single-quotes',
				'label' => esc_html_x( 'Single-Quote Mode', 'text', 'nelio-content' ),
				'desc'  => esc_html_x( 'Equivalent to “Double-Quote Mode,” but using single quotes instead.', 'text', 'nelio-content' ),
			),
		),
	),

	array(
		'type'    => 'select',
		'name'    => 'auto_feat_image',
		'label'   => esc_html_x( 'Autoset Featured Image', 'text', 'nelio-content' ),
		'desc'    => esc_html_x( 'If a post doesn’t have a featured image set, Nelio Content can set it automatically for you. To do this, it looks for all the images included in the post and uses one of them as the featured image.', 'text', 'nelio-content' ),
		'default' => 'disabled',
		'options' => array(
			array(
				'value' => 'disabled',
				'label' => esc_html_x( 'Disabled', 'text', 'nelio-content' ),
				'desc'  => esc_html_x( 'Nelio Content doesn’t set the featured image automatically.', 'text', 'nelio-content' ),
			),
			array(
				'value' => 'first',
				'label' => esc_html_x( 'Use First Image in Post', 'text', 'nelio-content' ),
				'desc'  => esc_html_x( 'Nelio Content will use the first image included in the post.', 'text', 'nelio-content' ),
			),
			array(
				'value' => 'any',
				'label' => esc_html_x( 'Use Any Image In Post', 'text', 'nelio-content' ),
				'desc'  => esc_html_x( 'Nelio Content will use one of the images included in the post, selecting it randomly. If there are more than two images, Nelio Content will ignore the first and the last image.', 'text', 'nelio-content' ),
			),
			array(
				'value' => 'last',
				'label' => esc_html_x( 'Use Last Image In Post', 'text', 'nelio-content' ),
				'desc'  => esc_html_x( 'Nelio Content will use the last image included in the post.', 'text', 'nelio-content' ),
			),
		),
	),

);
