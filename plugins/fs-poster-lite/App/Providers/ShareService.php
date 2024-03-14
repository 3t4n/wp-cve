<?php

namespace FSPoster\App\Providers;

class ShareService
{
	public static function insertFeeds ( $wpPostId, $nodes_list, $categoryFilter = TRUE )
	{
		/**
		 * Accounts, communications list array
		 */
		$nodes_list = is_array( $nodes_list ) ? $nodes_list : [];

		$sendDateTime = Date::dateTimeSQL();

		$feedsCount = 0;

		foreach ( $nodes_list as $nodeId )
		{
			if ( is_string( $nodeId ) && strpos( $nodeId, ':' ) !== FALSE )
			{
				$parse         = explode( ':', $nodeId );
				$driver        = $parse[ 0 ];
				$nodeType      = $parse[ 1 ];
				$nodeId        = $parse[ 2 ];
				$filterType    = isset( $parse[ 3 ] ) ? $parse[ 3 ] : 'no';
				$categoriesStr = isset( $parse[ 4 ] ) ? $parse[ 4 ] : '';

				if ( $categoryFilter && ! empty( $categoriesStr ) && $filterType != 'no' )
				{
					$categoriesFilter = [];

					foreach ( explode( ',', $categoriesStr ) as $termId )
					{
						if ( is_numeric( $termId ) && $termId > 0 )
						{
							$categoriesFilter[] = (int) $termId;
						}
					}

					$result = DB::DB()->get_row( "SELECT count(0) AS r_count FROM `" . DB::WPtable( 'term_relationships', TRUE ) . "` WHERE object_id='" . (int) $wpPostId . "' AND `term_taxonomy_id` IN (SELECT `term_taxonomy_id` FROM `" . DB::WPtable( 'term_taxonomy', TRUE ) . "` WHERE `term_id` IN ('" . implode( "' , '", $categoriesFilter ) . "'))", ARRAY_A );

					if ( ( $filterType == 'in' && $result[ 'r_count' ] == 0 ) || ( $filterType == 'ex' && $result[ 'r_count' ] > 0 ) )
					{
						continue;
					}
				}

				if ( $nodeType === 'account' && $driver === 'tumblr' )
				{
					continue;
				}

				if ( ! ( in_array( $nodeType, [
						'account',
						'ownpage',
						'page',
						'group',
						'event',
						'blog',
						'company',
						'community',
						'subreddit',
						'location',
						'chat',
						'board',
						'publication'
					] ) && is_numeric( $nodeId ) && $nodeId > 0 ) )
				{
					continue;
				}

				DB::DB()->insert( DB::table( 'feeds' ), [
					'driver'    => $driver,
					'post_id'   => $wpPostId,
					'node_type' => $nodeType,
					'node_id'   => (int) $nodeId,
					'send_time' => $sendDateTime,
					'is_seen'   => 0
				] );

				$feedsCount++;
			}
		}

		return $feedsCount;
	}

	public static function postSaveEvent ( $new_status, $old_status, $post )
	{
		global $wp_version;

		$post_id = $post->ID;

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		{
			return;
		}

		if ( ! in_array( $new_status, [ 'publish', 'draft', 'pending' ] ) )
		{
			return;
		}

		/**
		 * Gutenberg bug...
		 * https://github.com/WordPress/gutenberg/issues/15094
		 */
		if ( version_compare( $wp_version, '5.0', '>=' ) && isset( $_GET[ '_locale' ] ) && $_GET[ '_locale' ] == 'user' && empty( $_POST ) )
		{
			delete_post_meta( $post_id, '_fs_poster_post_old_status_saved' );
			add_post_meta( $post_id, '_fs_poster_post_old_status_saved', $old_status, TRUE );

			return;
		}

		if ( ! in_array( $post->post_type, explode( '|', Helper::getOption( 'allowed_post_types', 'post|page', TRUE ) ) ) )
		{
			return;
		}

		$metaBoxLoader            = (int) Request::get( 'meta-box-loader', 0, 'num', [ '1' ] );
		$original_post_old_status = Request::post( 'original_post_status', '', 'string' );

		if ( $metaBoxLoader === 1 && ! empty( $original_post_old_status ) )
		{
			// Gutenberg bug!
			$old_status = get_post_meta( $post_id, '_fs_poster_post_old_status_saved', TRUE );
			delete_post_meta( $post_id, '_fs_poster_post_old_status_saved' );
		}

		if ( $old_status === 'publish' || $old_status === 'future' )
		{
			return;
		}
		// if the request is from real user
		if ( metadata_exists( 'post', $post_id, '_fs_is_manual_action' ) )
		{
			$share_checked_input = get_post_meta( $post_id, '_fs_poster_share', TRUE );
		}
		else
		{
			$share_checked_input = Helper::getOption( 'auto_share_new_posts', '1', TRUE ) ? 'on' : 'off';
		}

		if ( $share_checked_input !== 'on' )
		{
			DB::DB()->delete( DB::table( 'feeds' ), [
				'post_id'   => $post_id,
				'is_sended' => '0'
			] );

			return;
		}

		$nodes_list = [];

		$accounts = DB::fetchAll( 'accounts', [ 'is_active' => 1 ] );

		$active_nodes = DB::fetchAll( 'account_nodes', [ 'is_active' => 1 ] );
		$active_nodes = array_merge( $accounts, $active_nodes );

		foreach ( $active_nodes as $nodeInf )
		{
			$node_type = empty($nodeInf[ 'node_type' ]) ? 'account' : $nodeInf[ 'node_type' ];
			$nodes_list[] = $nodeInf[ 'driver' ] . ':' . $node_type . ':' . $nodeInf[ 'id' ] . ':no:';
		}

		if ( $new_status === 'draft' || $new_status === 'pending' )
		{
			add_post_meta( $post_id, '_fs_poster_share', 1, TRUE );

			return;
		}

		self::insertFeeds( $post_id, $nodes_list, TRUE );

		if ( $new_status == 'publish' )
		{
			add_filter( 'redirect_post_location', function ( $location ) {
				return $location . '&share=1';
			} );
		}
	}

	public static function deletePostFeeds ( $post_id )
	{
		DB::DB()->delete( DB::table( 'feeds' ), [
			'post_id'   => $post_id,
			'is_sended' => 0
		] );
	}

	public static function post ( $feedId, $secureShare = FALSE )
	{
		$feedInf = DB::fetch( 'feeds', $feedId );

		if ( ! $feedInf || ( $secureShare && $feedInf[ 'is_sended' ] != 2 ) )
		{
			return;
		}

		$post_id = $feedInf[ 'post_id' ];
		$title   = get_the_title( $post_id );
		$link    = get_post_permalink( $post_id );

		if ( Helper::getOption( 'collect_statistics', '1', TRUE ) == 1 )
		{
			$link .= "&feed_id=$feedId";
		}

		if ( $feedInf[ 'node_type' ] === 'account' )
		{
			$node_info = DB::fetch( 'accounts', [ 'id' => $feedInf[ 'node_id' ] ] );
		}
		else
		{
			$node_info = DB::fetch( 'account_nodes', [ 'id' => $feedInf[ 'node_id' ] ] );
		}

		$fs_account_id = $node_info[ 'fs_account_id' ];
		$driver        = $node_info[ 'driver' ];

		if ( in_array( $driver, [ 'fb', 'ok', 'tumblr', 'reddit', 'vk', 'linkedin', 'plurk', 'telegram' ] ) )
		{
			$res = Helper::api_cmd( 'share', 'POST', Helper::getOption( 'access_token', '', TRUE ), [
				'title' => $title,
				'link'  => $link,
				'node'  => $fs_account_id,
				'sn'    => $driver
			] );
		}
		else
		{
			$res = [
				'status'    => 'error',
				'error_msg' => ! empty( [ htmlspecialchars( $driver ) ] ) ? esc_html__( vsprintf( 'Driver error! Driver type: %s', [ htmlspecialchars( $driver ) ] ) ) : esc_html__( 'Driver error! Driver type: %s', 'fs-poster' )
			];
		}

		if ( ! Helper::getOption( 'keep_logs', '1', TRUE ) )
		{
			DB::DB()->delete( DB::table( 'feeds' ), [
				'id' => $feedId
			] );
		}
		else
		{
			$udpateDate = [
				'is_sended'      => 1,
				'send_time'      => Date::dateTimeSQL(),
				'status'         => $res[ 'status' ],
				'error_msg'      => isset( $res[ 'error_msg' ] ) ? Helper::cutText( $res[ 'error_msg' ], 250 ) : '',
				'driver_post_id' => isset( $res[ 'id' ] ) ? $res[ 'id' ] : NULL,
			];

			DB::DB()->update( DB::table( 'feeds' ), $udpateDate, [ 'id' => $feedId ] );
		}

		if ( isset( $res[ 'id' ] ) )
		{
			$username = isset( $node_info[ 'node_type' ] ) ? $node_info[ 'screen_name' ] : $node_info[ 'username' ];

			if ( ! isset( $res[ 'post_link' ] ) )
			{
				$res[ 'post_link' ] = Helper::postLink( $res[ 'id' ], $driver, $username );
			}
		}

		return $res;
	}
}
