<?php

namespace FSPoster\App\Providers;

use FSPoster\App\Pages\Accounts\Controllers\Action as AccountAction;

class Api
{
	public function __construct ()
	{
		$action = Request::get( 'fsp_api_event', FALSE, 'string', [
			'account_added'
		] );
		$data   = json_decode( urldecode( Request::get( 'data', '', 'num' ) ), TRUE );
		$token  = Request::get( 'token', FALSE, 'string' );

		if ( $action && $data && $token )
		{
			if ( ! password_verify( Helper::getOption( 'access_token', '', TRUE ), $token ) )
			{
				exit( json_encode( [
					'status'    => 'error',
					'error_msg' => 'Token is not correct.'
				] ) );
			}

			self::$action( $data );
		}
	}

	private static function account_added ( $data = '' )
	{
		$error_msg = '';
		$result    = Helper::api_cmd( 'get_account', 'POST', Helper::getOption( 'access_token', '', TRUE ), [
			'id' => $data
		] );

		if ( $result[ 'status' ] === 'error' )
		{
			$error_msg = htmlspecialchars( $result[ 'message' ] );
		}

		if ( is_array( $result[ 'data' ] ) && ! empty( $result[ 'data' ] ) )
		{
			AccountAction::add_account($result[ 'data' ][ 'account' ], $result[ 'data' ][ 'nodes' ]);
		}
		else
		{
			$error_msg = 'Account can not be found.';
		}

		exit( '<script>if ( typeof window.opener.accountAdded === "function" ) { window.opener.accountAdded( "' . ( ! empty( $error_msg ) ? $error_msg : '' ) . '" ); } window.close();</script>' );
	}
}
