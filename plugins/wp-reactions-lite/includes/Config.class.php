<?php

namespace WP_Reactions\Lite;

class Config {
	static public $default_options = [
		"activation"           => "false",
		"behavior"             => "regular",
		"show_count"           => "true",
		"count_color"          => "#ff0015",
		"count_text_color"     => "#FFFFFF",
		"enable_share_buttons" => "onclick",
		"social_platforms"     => [
			"facebook" => "true",
			"twitter"  => "true",
			"email"    => "true",
			"telegram" => "false",
		],
		"animation"            => "true",
		"show_title"           => "true",
		"title_text"           => "What’s your Reaction?",
		"title_size"           => "25px",
		"title_weight"         => "600",
		"title_color"          => "#000000",
		"emojis"               => [ 1, 2, 3, 4, 5, 6, 7 ],
		"bgcolor"              => "#FFFFFF",
		"bgcolor_trans"        => "false",
		"social_labels"        => [
			"facebook" => "Facebook",
			"twitter"  => "Twitter",
			"email"    => "Email",
			"telegram" => "Telegram"
		],
		"display_where"        => "both",
		"content_position"     => "after",
		"size"                 => "medium",
		"align"                => "center",
		"shadow"               => "true",
		"social_style_buttons" => "false",
		"social"               => [
			"border_radius" => "30px",
			"border_color"  => "#303030",
			"text_color"    => "#303030",
			"bg_color"      => "#FFFFFF",
			"button_type"   => "bordered",
		],
		"border_radius"        => "50px",
		"border_color"         => "#FFFFFF",
		"border_width"         => "0px",
		"border_style"         => "solid",
	];
	const SOCIAL_PLATFORMS = [
		'facebook' => [
			'color' => '#3b5998',
			'url'   => [
				'desktop' => 'https://www.facebook.com/sharer/sharer.php?u='
			]
		],
		'twitter'  => [
			'color' => '#00acee',
			'url'   => [
				'desktop' => 'https://twitter.com/intent/tweet?text='
			]
		],
		'email'    => [
			'color' => '#424242',
			'url'   => [
				'desktop' => 'mailto:?Subject=Shared%20with%20wpreactions&body='
			]
		],
		'telegram' => [
			'color' => '#0088cc',
			'url'   => [
				'desktop' => 'https://t.me/share/url?url='
			]
		]
	];
	const EMOJI_NAMES = [
		"Unused",
		"Eye Blink",
		"Goofy Love",
		"Cool Guy",
		"Loud Laugh",
		"Wow",
		"Sleepy",
		"Disappointment",
		"Thumbs Up",
		"Thumbs Down",
		"Heart",
		"Shit",
		"Crying",
		"Fuu",
		"Smile",
	];
	const DOCS = [
		[
			'name' => 'WP Reactions Lite Introduction',
			'url'  => 'https://wpreactions.com/documentation/wp-reactions-lite/',
		],
		[
			'name' => 'Choosing emoji reaction sizes',
			'url'  => 'https://wpreactions.com/documentation/wp-reactions-lite/global-activation-step-1-setup/',
		],
		[
			'name' => 'Setting up fake user counts',
			'url'  => 'https://wpreactions.com/documentation/global-activation/on-page-options/',
		],
		[
			'name' => 'Personalize your social media buttons',
			'url'  => 'https://wpreactions.com/documentation/wp-reactions-lite/global-activation-step-3-social-media/',
		],
		[
			'name' => 'Accessing page analytics',
			'url'  => 'https://wpreactions.com/documentation/global-activation/on-page-options/',
		],
		[
			'name' => 'Setting up Overhead Badges',
			'url'  => 'https://wpreactions.com/documentation/wp-reactions-lite/global-activation-step-1-setup/'
		]
	];
	static public $current_options = [];
	static public $tbl_reacted_users;
	static public $top_menu_items = [];
	const MAX_EMOJIS = 14;
	const FEEDBACK_API = 'https://wpreactions.com/api/v1/submit_feedback';

	public static function init() {
		global $wpdb;

		self::$tbl_reacted_users = $wpdb->prefix . 'wpreactions_reacted_users';
		self::$current_options   = json_decode( get_option( WPRA_LITE_OPTIONS ), true );
		self::$top_menu_items    = [
			[
				'name'   => __( 'Dashboard', 'wpreactions-lite' ),
				'link'   => Helper::getAdminPage( 'dashboard' ),
				'icon'   => 'dashicons dashicons-dashboard',
				'target' => '',
			],
			[
				'name'   => __( 'Global Activation', 'wpreactions-lite' ),
				'link'   => Helper::getAdminPage( 'global' ),
				'icon'   => 'dashicons dashicons-admin-site-alt3',
				'target' => '',
			],
			[
				'name'   => __( 'Support', 'wpreactions-lite' ),
				'link'   => Helper::getAdminPage( 'support' ),
				'icon'   => 'dashicons dashicons-sos',
				'target' => '',
			],
			[
				'name'   => __( 'Pro', 'wpreactions-lite' ),
				'link'   => Helper::getAdminPage( 'pro' ),
				'icon'   => 'dashicons dashicons-star-filled',
				'target' => '',
			],
			[
				'name'   => __( 'Feedback', 'wpreactions-lite' ),
				'link'   => '#toggle-feedback-form',
				'icon'   => 'dashicons dashicons-testimonial',
				'target' => '',
			],
		];
	}

} // end of Configuration class
