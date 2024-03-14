<?php

namespace FSPoster\App\Providers;

trait PluginMenu
{
	public function initMenu ()
	{
		add_action( 'init', function () {
			if ( Helper::isHiddenUser() )
			{
				return;
			}

			$plgnVer = Helper::getOption( 'poster_plugin_installed', '0', TRUE );

			if ( empty( $plgnVer ) )
			{
				add_action( 'admin_menu', function () {
					add_menu_page( 'FS Poster', 'FS Poster Lite', 'read', 'fs-poster', function () {
						Pages::controller( 'Base', 'App', 'install' );
					}, Pages::asset( 'Base', 'img/logo_xs_new.png' ), 90 );
				} );

				return;
			}

			add_action( 'admin_menu', function () {
				add_menu_page( 'FS Poster', 'FS Poster', 'read', 'fs-poster', [
					Pages::class,
					'load_page'
				], Pages::asset( 'Base', 'img/logo_xs.png' ), 90 );

				add_submenu_page( 'fs-poster', esc_html__( 'Dashboard', 'fs-poster' ), esc_html__( 'Dashboard', 'fs-poster' ), 'read', 'fs-poster', [
					Pages::class,
					'load_page'
				] );

				add_submenu_page( 'fs-poster', esc_html__( 'Accounts', 'fs-poster' ), esc_html__( 'Accounts', 'fs-poster' ), 'read', 'fs-poster-accounts', [
					Pages::class,
					'load_page'
				] );

				add_submenu_page( 'fs-poster', esc_html__( 'Logs', 'fs-poster' ), esc_html__( 'Logs', 'fs-poster' ), 'read', 'fs-poster-logs', [
					Pages::class,
					'load_page'
				] );

				if ( current_user_can( 'administrator' ) )
				{
					add_submenu_page( 'fs-poster', esc_html__( 'Settings', 'fs-poster' ), esc_html__( 'Settings', 'fs-poster' ), 'read', 'fs-poster-settings', [
						Pages::class,
						'load_page'
					] );
				}
			} );
		} );
	}
}
