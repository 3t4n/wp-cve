<?php

class SarbacaneSettings {

	public function get_settings() {
		$optin_sync = get_option( 'sarbacane_news_list', false );
		$users_sync = get_option( 'sarbacane_users_list', false );
		$theme_sync = get_option( 'sarbacane_theme_sync', false );
		$blog_content = get_option( 'sarbacane_blog_content', false );
		$media_content = get_option( 'sarbacane_media_content', false );
		$rss_data = get_option( 'sarbacane_rss_data', false );
		$rss_url = get_feed_link( 'rss2' );
		$posts_per_rss = get_option( 'posts_per_rss', 10 );
		return '{
	"settings":{
		"general_settings":{
			"optin_sync":' . intval( $optin_sync ) . ',
			"users_sync":' . intval( $users_sync ) . ',
			"theme_sync":' . intval( $theme_sync ) . ',
			"blog_content":' . intval( $blog_content ) . ',
			"media_content":' . intval( $media_content ) . ',
			"rss_data":' . intval( $rss_data ) . '
		},
		"rss_settings":{
			"rss_url":"' . $rss_url . '",
			"rss_max":"' . $posts_per_rss . '"
		},
		"theme_settings":{{colors}}
	}
}';
	}

}
