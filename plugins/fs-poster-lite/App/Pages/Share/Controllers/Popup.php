<?php

namespace FSPoster\App\Pages\Share\Controllers;

use FSPoster\App\Providers\DB;
use FSPoster\App\Providers\Date;
use FSPoster\App\Providers\Pages;
use FSPoster\App\Providers\Helper;
use FSPoster\App\Providers\Request;

trait Popup
{
	public function share_feeds ()
	{
		$post_id         = Request::post( 'post_id', '0', 'num' );
		$is_paused_feeds = Request::post( 'is_paused_feeds', 0, 'int' );
		$dont_reload     = Request::post( 'dont_reload', '0', 'num', [ 0, 1 ] );

		if ( $is_paused_feeds !== 1 && ! ( $post_id > 0 ) )
		{
			exit();
		}

		if ( $is_paused_feeds === 1 )
		{
			$feeds = DB::DB()->get_results( DB::DB()->prepare( 'SELECT * FROM ' . DB::table( 'feeds' ) . ' WHERE is_sended = %d', [
				0
			] ), ARRAY_A );
		}
		else
		{
			$feeds = DB::DB()->get_results( DB::DB()->prepare( 'SELECT * FROM ' . DB::table( 'feeds' ) . ' WHERE is_sended = %d AND post_id = %d AND send_time >= %s', [
				0,
				$post_id,
				Date::dateTimeSQL( '-30 seconds' )
			] ), ARRAY_A );
		}

		Pages::modal( 'Share', 'share_feeds', [
			'parameters' => [
				'feeds' => $feeds,
				'dont_reload' => $dont_reload
			]
		] );
	}

	public function share_saved_post ()
	{
		$post_id = Request::post( 'post_id', '0', 'num' );

		if ( ! ( $post_id > 0 ) )
		{
			exit();
		}

		Pages::modal( 'Share', 'share_saved_post', [
			'parameters' => [
				'post_id' => $post_id
			]
		] );
	}
}