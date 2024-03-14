<?php

namespace FSPoster\App\Pages\Accounts\Controllers;

use FSPoster\App\Providers\DB;
use FSPoster\App\Providers\Pages;
use FSPoster\App\Providers\Helper;
use FSPoster\App\Providers\Request;

trait Popup
{
	public function reddit_add_subreddit ()
	{
		$accountId = (int) Request::post( 'account_id', '0', 'num' );

		Pages::modal( 'Accounts', 'reddit/add_subreddit', [ 'accountId' => $accountId ] );
	}

	public function add_vk_account ()
	{
		$response = Helper::api_cmd( 'get_vk_app_id', 'POST', Helper::getOption( 'access_token', '', TRUE ), '' );

		if ( isset( $response[ 'status' ] ) && $response[ 'status' ] === 'error' )
		{
			Helper::response( FALSE, $response );
		}

		Pages::modal( 'Accounts', 'vk/add_account', [ 'app' => $response[ 'app' ] ] );
	}

	public function add_plurk_account ()
	{
		$response = Helper::api_cmd( 'get_plurk_auth_link', 'POST', Helper::getOption( 'access_token', '', TRUE ) );

		if ( isset( $response[ 'status' ] ) && $response[ 'status' ] === 'error' )
		{
			Helper::response( FALSE, $response );
		}

		Pages::modal( 'Accounts', 'plurk/add_account', [ 'data' => $response ] );
	}

	public function add_telegram_account ()
	{
		Pages::modal( 'Accounts', 'telegram/add_bot', [] );
	}

	public function telegram_add_chat ()
	{
		Pages::modal( 'Accounts', 'telegram/add_chat', [
			'accountId' => (int) Request::post( 'account_id', '0', 'num' )
		] );
	}
}
