<?php

namespace FSPoster\App\Pages\Share\Controllers;

use FSPoster\App\Providers\DB;
use FSPoster\App\Providers\Date;
use FSPoster\App\Providers\Helper;
use FSPoster\App\Providers\Request;
use FSPoster\App\Providers\ShareService;

trait Ajax
{
	public function share_post ()
	{
		$post_id = Request::post( 'id', 0, 'num' );

		if ( ! ( $post_id && $post_id > 0 ) )
		{
			exit();
		}

		$feedId = (int) $post_id;

		$res = ShareService::post( $feedId );
		Helper::response( TRUE, [ 'result' => $res ] );
	}

	public function share_saved_post ()
	{
		$post_id = Request::post( 'post_id', '0', 'num' );
		$nodes   = Request::post( 'nodes', [], 'array' );

		if ( empty( $post_id ) || empty( $nodes ) || $post_id <= 0 )
		{
			Helper::response( FALSE );
		}

		if ( ! ShareService::insertFeeds( $post_id, $nodes, FALSE ) )
		{
			Helper::response( FALSE, esc_html__( 'There isn\'t any active account or community to share the post!', 'fs-poster' ) );
		}

		Helper::response( TRUE );
	}

	public function check_post_is_published ()
	{
		$id = Request::post( 'id', '0', 'num' );

		$postStatus = get_post_status( $id );

		$feeds = DB::DB()->get_row( DB::DB()->prepare( 'SELECT * FROM ' . DB::table( 'feeds' ) . ' WHERE is_sended = %d AND post_id = %d AND send_time >= %s', [
			0,
			$id,
			Date::dateTimeSQL( '-30 seconds' ),
		] ), ARRAY_A );

		if ( $postStatus === 'publish' && $feeds != NULL )
		{
			$status = '2';
		}
		else if ( $postStatus === 'publish' )
		{
			$status = '1';
		}
		else
		{
			$status = FALSE;
		}

		Helper::response( TRUE, [
			'post_status' => $status
		] );
	}

	public function get_feed_details ()
	{
		$feedId = Request::post( 'feed_id', '0', 'num' );

		if ( empty( $feedId ) || $feedId <= 0 )
		{
			Helper::response( FALSE );
		}

		$feed = DB::fetch( 'feeds', [
			'id' => $feedId
		] );

		if ( ! $feed )
		{
			Helper::response( FALSE );
		}

		$result = [
			'post_id' => $feed[ 'post_id' ],
			'nodes'   => [ $feed[ 'driver' ] . ':' . $feed[ 'node_type' ] . ':' . $feed[ 'node_id' ] ]
		];

		DB::DB()->delete( DB::table( 'feeds' ), [ 'id' => $feed[ 'id' ] ] );

		Helper::response( TRUE, [ 'result' => $result ] );
	}

	public function do_not_share_paused_feeds ()
	{
		DB::DB()->delete( DB::table( 'feeds' ), [
			'is_sended' => 0
		] );

		Helper::response( TRUE );
	}
}