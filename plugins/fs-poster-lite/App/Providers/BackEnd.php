<?php

namespace FSPoster\App\Providers;

use Exception;

class BackEnd
{
	use PluginMenu;

	private $active_custom_post_types;

	public function __construct ()
	{
		new Ajax();
		new Popups();

		$this->initMenu();

		$this->enqueueAssets();
		$this->registerMetaBox();
		$this->addNewsWidget();

		$this->registerActions();
		$this->registerNotifications();
	}

	private function registerMetaBox ()
	{
		add_action( 'add_meta_boxes', function () {
			if ( Helper::isHiddenUser() )
			{
				return;
			}

			add_meta_box( 'fs_poster_meta_box', 'FS Poster', [
				$this,
				'publish_meta_box'
			], $this->getActiveCustomPostTypes(), 'side', 'high' );
		} );
	}

	public function publish_meta_box ( $post )
	{
		if ( in_array( $post->post_status, [ 'new', 'auto-draft', 'draft', 'pending' ] ) )
		{
			Pages::controller( 'Base', 'MetaBox', 'post_meta_box', [
				'post_id'          => $post->ID,
				'minified_metabox' => TRUE
			] );
		}
		else
		{
			Pages::controller( 'Base', 'MetaBox', 'post_meta_box_edit', [
				'post'             => $post,
				'minified_metabox' => TRUE
			] );
		}
	}

	private function addNewsWidget ()
	{
		add_action( 'wp_dashboard_setup', function () {
			wp_add_dashboard_widget( 'fsp-news', 'FS Poster', function () {
				$dataURL = 'https://www.fs-poster.com/api/news/';
				$expTime = 43200; // In seconds

				try
				{
					$cachedData = json_decode( Helper::getOption( 'news_cache', FALSE, TRUE ) );
					$now        = Date::epoch();

					if ( empty( $cachedData ) || $now - $cachedData->time >= $expTime )
					{
						$data = wp_remote_get( $dataURL );

						Helper::setOption( 'news_cache', json_encode( [
							'time' => $now,
							'data' => isset( $data[ 'body' ] ) ? $data[ 'body' ] : ''
						] ), TRUE );
					}
					else
					{
						$data = $cachedData->data;
					}
				}
				catch ( Exception $e )
				{
					$data = '';
				}

				echo isset( $data[ 'body' ] ) ? $data[ 'body' ] : $data;
			} );
		} );
	}

	private function enqueueAssets ()
	{
		add_action( 'admin_enqueue_scripts', function () {
			wp_register_script( 'fsp-select2', Pages::asset( 'Base', 'js/fsp-select2.full.min.js' ) );
			wp_enqueue_script( 'fsp-select2' );
			wp_register_script( 'fsp', Pages::asset( 'Base', 'js/fsp.js' ), [ 'jquery' ], NULL );
			wp_enqueue_script( 'fsp' );
			wp_localize_script( 'fsp', 'fspConfig', [
				'pagesURL' => plugins_url( 'Pages/', dirname( __FILE__ ) ),
				'siteURL'  => site_url()
			] );
			wp_localize_script( 'fsp', 'FSPObject', [
				'modals' => []
			] );

			wp_enqueue_style( 'fsp-fonts', Pages::asset( 'Base', 'css/fsp-fonts.css' ) );
			wp_enqueue_style( 'fsp-fontawesome', Pages::asset( 'Base', 'css/fsp-fontawesome.min.css' ) );
			wp_enqueue_style( 'fsp-select2', Pages::asset( 'Base', 'css/fsp-select2-cdn.min.css' ) );
			wp_enqueue_style( 'fsp-ui', Pages::asset( 'Base', 'css/fsp-ui.css' ), [], NULL );
			wp_enqueue_style( 'fsp-base', Pages::asset( 'Base', 'css/fsp-base.min.css' ), [], NULL );
			wp_enqueue_style( 'fsp-select2-custom', Pages::asset( 'Base', 'css/fsp-select2.css' ), [
				'fsp-select2',
				'fsp-ui',
				'fsp-base'
			], NULL );
		} );
	}

	private function registerActions ()
	{
		$page                = Request::get( 'page', '', 'string' );
		$is_download_request = Request::get( 'download', '', 'string' );
		$exported_json       = Helper::getOption( 'exported_json_' . $is_download_request, '', TRUE );

		if ( $page === 'fs-poster-settings' && ! empty( $is_download_request ) && ! empty( $exported_json ) )
		{
			Helper::deleteOption( 'exported_json_' . $is_download_request );

			add_action( 'admin_init', function () use ( $exported_json, $is_download_request ) {
				header( 'Content-disposition: attachment; filename=fs_poster_' . $is_download_request . '.json' );
				header( 'Content-type: application/json' );

				exit( $exported_json );
			} );
		}

		if ( Helper::getOption( 'show_fs_poster_column', '1', TRUE ) )
		{
			$usedColumnsSave = [];

			foreach ( $this->getActiveCustomPostTypes() as $postType )
			{
				$postType = preg_replace( '/[^a-zA-Z0-9\-\_]/', '', $postType );

				switch ( $postType )
				{
					case 'post':
						$typeName = 'posts';
						break;
					case 'page':
						$typeName = 'pages';
						break;
					case 'attachment':
						$typeName = 'media';
						break;
					default:
						$typeName = $postType . '_posts';
				}

				add_action( 'manage_' . $typeName . '_custom_column', function ( $column_name, $post_id ) use ( &$usedColumnsSave ) {
					if ( ! Helper::isHiddenUser() && $column_name === 'fsp-share-column' && get_post_status( $post_id ) === 'publish' && ! isset( $usedColumnsSave[ $post_id ] ) )
					{
						if ( get_post_status( $post_id ) === 'publish' )
						{
							echo '<i class="fas fa-rocket fsp-tooltip" data-title="' . esc_html__( 'Share', 'fs-poster' ) . '" data-load-modal="share_saved_post" data-parameter-post_id="' . $post_id . '"></i><i class="fas fa-history fsp-tooltip fsp-col-premium" data-title="' . esc_html__( 'Schedule', 'fs-poster' ) . '"></i>';
						}
						else
						{
							echo '<i class="fas fa-exclamation-triangle fsp-tooltip" data-title="' . esc_html__( 'Only published posts can be shared or scheduled.', 'fs-poster' ) . '"></i>';
						}

						$usedColumnsSave[ $post_id ] = TRUE;
					}
				}, 10, 2 );

				add_filter( 'manage_' . $typeName . '_columns', function ( $columns ) {
					if ( ! Helper::isHiddenUser() && is_array( $columns ) && ! isset( $columns[ 'fsp-share-column' ] ) )
					{
						$columns[ 'fsp-share-column' ] = 'FS Poster';
					}

					return $columns;
				} );

			}
		}
	}

	private function getActiveCustomPostTypes ()
	{
		if ( is_null( $this->active_custom_post_types ) )
		{
			$this->active_custom_post_types = explode( '|', Helper::getOption( 'allowed_post_types', 'post|page', TRUE ) );
		}

		return $this->active_custom_post_types;
	}

	private function registerNotifications ()
	{
		add_action( 'init', function () {

			if ( Helper::isHiddenUser() )
			{
				return;
			}

			$plgnVer = Helper::getOption( 'poster_plugin_installed', '0', TRUE );

			if ( ! $plgnVer )
			{
				return;
			}

			if ( Helper::getOption( 'hide_notifications', '0', TRUE ) != 1 )
			{
				$failed_feeds = DB::DB()->get_row( DB::DB()->prepare( 'SELECT COUNT(id) AS total FROM ' . DB::table( 'feeds' ) . ' tb1 WHERE is_sended = 1 AND ( ( node_type=\'account\' AND ( SELECT COUNT(0) FROM ' . DB::table( 'accounts' ) . ' tb2 WHERE tb2.id = tb1.node_id ) > 0 ) OR ( node_type <> \'account\' AND (SELECT COUNT(0) FROM ' . DB::table( 'account_nodes' ) . ' tb2 WHERE tb2.id = tb1.node_id ) ) ) AND status = \'error\' AND is_seen = 0' ), ARRAY_A );

				if ( $failed_feeds && $failed_feeds[ 'total' ] > 0 )
				{
					add_action( 'admin_notices', function () use ( $failed_feeds ) {
						$verb = (int) $failed_feeds[ 'total' ] >= 2 ? 'are' : 'is';

						echo '<div class="fsp-notification-container"><div class="fsp-notification"><div class="fsp-notification-info"><div class="fsp-notification-icon fsp-is-warning"></div><div class="fsp-notification-text"><div class="fsp-notification-status">' . 'FS Poster' . '</div><div class="fsp-notification-message">' . ( ! empty( [
								$verb,
								$failed_feeds[ 'total' ]
							] ) ? __( vsprintf( 'There %s <b>%s</b> failed post(s).', [
								$verb,
								$failed_feeds[ 'total' ]
							] ) ) : __( 'There %s <b>%s</b> failed post(s).' ) ) . '</div></div></div><div class="fsp-notification-buttons"><a class="fsp-button" href="' . admin_url() . 'admin.php?page=fs-poster-logs&filter_by=error">' . esc_html__( 'GO TO THE LOGS', 'fs-poster' ) . '</a><button class="fsp-button fsp-is-gray fsp-close-notification" data-hide="true">' . esc_html__( 'HIDE', 'fs-poster' ) . '</button></div></div></div>';
					} );
				}
			}

			$not_sended_feeds = DB::DB()->get_row( DB::DB()->prepare( 'SELECT COUNT(id) AS total FROM ' . DB::table( 'feeds' ) . ' tb1 WHERE is_sended = 0 AND ( ( node_type=\'account\' AND ( SELECT COUNT(0) FROM ' . DB::table( 'accounts' ) . ' tb2 WHERE tb2.id = tb1.node_id ) > 0 ) OR ( node_type <> \'account\' AND (SELECT COUNT(0) FROM ' . DB::table( 'account_nodes' ) . ' tb2 WHERE tb2.id = tb1.node_id ) ) ) AND status IS NULL  AND send_time <= %s', Date::dateTimeSQL( 'now', '-1 minutes' ) ), ARRAY_A );

			if ( $not_sended_feeds && $not_sended_feeds[ 'total' ] > 0 )
			{
				add_action( 'admin_notices', function () use ( $not_sended_feeds ) {
					$verb = (int) $not_sended_feeds[ 'total' ] >= 2 ? 'are' : 'is';

					echo '<div class="fsp-notification-container"><div class="fsp-notification"><div class="fsp-notification-info"><div class="fsp-notification-icon fsp-is-warning"></div><div class="fsp-notification-text"><div class="fsp-notification-status">' . 'FS Poster' . '</div><div class="fsp-notification-message">' . esc_html__( 'There', 'fs-poster' ) . ' ' . $verb . ' <b>' . $not_sended_feeds[ 'total' ] . '</b> ' . esc_html__( 'feed(s) that require action.', 'fs-poster' ) . ' <i class="far fa-question-circle fsp-tooltip" data-title="' . esc_html__( 'For various reasons like refreshing the page might interrupt the sharing process, or some new posts have been published via 3rd party plugins. That will cause some posts to remain unshared. You can share them in the background or with the pop-up.', 'fs-poster' ) . '"></i></div></div></div><div class="fsp-notification-buttons"><button id="fspNotificationShareWithPopup" class="fsp-button">' . esc_html__( 'SHARE WITH POP-UP', 'fs-poster' ) . '</button><button id="fspNotificationShareOnBackground" class="fsp-button">' . esc_html__( 'SHARE IN THE BACKGROUND', 'fs-poster' ) . '</button><button id="fspNotificationDoNotShare" class="fsp-button fsp-is-gray fsp-close-notification" data-hide="true">' . esc_html__( 'DON\'T SHARE', 'fs-poster' ) . '</button></div></div></div>';
				} );
			}
		} );
	}
}