<?php

namespace FSPoster\App\Providers;

use FSPoster\App\Pages\Base\Controllers\Ajax as BaseAjax;
use FSPoster\App\Pages\Logs\Controllers\Ajax as LogsAjax;
use FSPoster\App\Pages\Share\Controllers\Ajax as ShareAjax;
use FSPoster\App\Pages\Accounts\Controllers\Ajax as AccountsAjax;
use FSPoster\App\Pages\Settings\Controllers\Ajax as SettingsAjax;

Helper::disableDebug();

class Ajax
{
	use BaseAjax, AccountsAjax, ShareAjax, LogsAjax, SettingsAjax;

	public function __construct ()
	{
		$methods = get_class_methods( $this );

		foreach ( $methods as $method )
		{
			if ( $method === '__construct' )
			{
				continue;
			}

			add_action( 'wp_ajax_' . $method, function () use ( $method ) {
				$this->$method();

				exit;
			} );
		}
	}

	public function verify_app ()
	{
        $found_from = Request::post( 'found_from', '', 'string' );
        $email      = Request::post( 'email', '', 'string' );

        if ( Helper::getOption( 'poster_plugin_installed', '0', TRUE ) )
		{
			Helper::response( FALSE, esc_html__( 'Your plugin is already installed!', 'fs-poster' ) );
		}

		$result = Helper::api_cmd( 'register', 'POST', '', [
			'email'      => $email,
            'found_from' => $found_from,
            'domain'     => network_site_url(),
            'version'    => Helper::getVersion()

        ] );

		if ( $result[ 'status' ] === 'error' )
		{
			Helper::response( FALSE, htmlspecialchars( $result[ 'message' ] ) );
		}

        Pages::modal( 'Base', 'install' );
	}

    public function activate_app ()
	{
        $found_from = Request::post( 'found_from', '', 'string' );
        $otp_code   = Request::post( 'otp_code', '', 'string' );
        $email      = Request::post( 'email', '', 'string' );

        if ( Helper::getOption( 'poster_plugin_installed', '0', TRUE ) )
		{
			Helper::response( FALSE, esc_html__( 'Your plugin is already installed!', 'fs-poster' ) );
		}

		$result = Helper::api_cmd( 'verification', 'POST', '', [
            'email'      => $email,
			'domain'     => network_site_url(),
            'version'    => Helper::getVersion(),
            'otp_code'   => $otp_code,
            'found_from' => $found_from
		] );

		if ( $result[ 'status' ] === 'error' )
		{
			Helper::response( FALSE, htmlspecialchars( $result[ 'message' ] ) );
		}

        if ( ! empty( $result[ 'access_token' ] ) )
        {
            Helper::setOption( 'access_token', $result[ 'access_token' ], TRUE );

            register_uninstall_hook( FSPL_ROOT_DIR . '/init.php', [ Helper::class, 'removePlugin' ] );

            Helper::response( TRUE, [ 'msg' => esc_html__( 'Plugin installed!', 'fs-poster' ) ] );
        }

        Helper::response( FALSE );
	}
}
