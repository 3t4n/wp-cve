<?php

namespace FSPoster\App\Providers {

	class Bootstrap
	{
		/**
		 * Bootstrap constructor.
		 */
		public function __construct ()
		{
			$this->registerDefines();

			new FSCodeUpdater();

			$this->loadPluginTextdomaion();
			$this->loadPluginLinks();
			$this->createPostSaveEvent();

			if ( is_admin() )
			{
				new BackEnd();
			}
			else
			{
				new FrontEnd();
			}

			add_action( 'init', function () {
				new Api();
			} );
		}

		private function registerDefines ()
		{
			define( 'FSPL_ROOT_DIR', dirname( dirname( __DIR__ ) ) );
			define( 'FSPL_API_URL', 'https://lite-api.fs-poster.com/api/' );
		}

		private function loadPluginLinks ()
		{
			add_filter( 'plugin_action_links_fs-poster-lite/init.php', function ( $links ) {
				$newLinks = [
					'<a href="https://fs-poster.com/buynow" target="_blank">' . esc_html__( 'Upgrade', 'fs-poster' ) . '</a>',
					'<a href="https://www.fs-poster.com/documentation/" target="_blank">' . esc_html__( 'Documentation', 'fs-poster' ) . '</a>'
				];

				return array_merge( $newLinks, $links );
			} );

			add_filter( 'network_admin_plugin_action_links', function ( $plugin_actions, $plugin_file ) {
				if ( $plugin_file === 'fs-poster-lite/init.php' )
				{
					$plugin_actions[ 'upgrade' ] = "<a href='https://fs-poster.com/buynow' target='_blank'>" . esc_html__( 'Upgrade', 'fs-poster' ) . "</a>";
					unset( $plugin_actions[ 'activate' ] );
				}

				return $plugin_actions;
			}, 10, 2 );
		}

		private function loadPluginTextdomaion ()
		{
			add_action( 'init', function () {
				load_plugin_textdomain( 'fs-poster', FALSE, 'fs-poster-lite/languages' );
			} );
		}

		private function createPostSaveEvent ()
		{
			add_action( 'transition_post_status', [ 'FSPoster\App\Providers\ShareService', 'postSaveEvent' ], 10, 3 );
			add_action( 'delete_post', [ 'FSPoster\App\Providers\ShareService', 'deletePostFeeds' ], 10 );
		}
	}
}
