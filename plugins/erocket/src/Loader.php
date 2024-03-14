<?php
namespace ERocket;

class Loader {
	public function __construct() {
		define( 'EROCKET_DIR', dirname( __DIR__ ) );

		add_action( 'widgets_init', [ $this, 'register_widgets' ] );
		new Sharing;
		new FeaturedPosts;
	}

	public function register_widgets() {
		register_widget( __NAMESPACE__ . '\Widgets\ContactInfo' );
		register_widget( __NAMESPACE__ . '\Widgets\RecentPosts' );
		register_widget( __NAMESPACE__ . '\Widgets\SocialMedia' );
	}
}
