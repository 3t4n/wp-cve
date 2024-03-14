<?php

namespace FSPoster\App\Pages\Settings\Controllers;

use FSPoster\App\Providers\Helper;
use FSPoster\App\Providers\Request;

trait Ajax
{
	private function isAdmin ()
	{
		if ( ! ( current_user_can( 'administrator' ) ) )
		{
			exit();
		}
	}

	public function settings_general_save ()
	{
		$this->isAdmin();

		$fs_hide_notifications        = Request::post( 'fs_hide_notifications', 0, 'string', [ 'on' ] ) === 'on' ? 1 : 0;
		$fs_show_fs_poster_column     = Request::post( 'fs_show_fs_poster_column', 0, 'string', [ 'on' ] ) === 'on' ? 1 : 0;
		$fs_collect_statistics        = Request::post( 'fs_collect_statistics', 0, 'string', [ 'on' ] ) === 'on' ? 1 : 0;
		$fs_hide_for_roles   = Request::post( 'fs_hide_for_roles', [], 'array' );
		$new_arrHideForRoles = [];
		$allRoles            = get_editable_roles();
		foreach ( $fs_hide_for_roles as $fs_aPT )
		{
			if ( $fs_aPT != 'administrator' && is_string( $fs_aPT ) && isset( $allRoles[ $fs_aPT ] ) )
			{
				$new_arrHideForRoles[] = $fs_aPT;
			}
		}
		$new_arrHideForRoles = implode( '|', $new_arrHideForRoles );

		$fs_auto_share_new_posts                = Request::post( 'fs_auto_share_new_posts', 0, 'string', [ 'on' ] ) === 'on' ? 1 : 0;
		$fs_keep_logs                           = Request::post( 'fs_keep_logs', 0, 'string', [ 'on' ] ) === 'on' ? 1 : 0;

		Helper::setOption( 'auto_share_new_posts', (string) $fs_auto_share_new_posts, TRUE );
		Helper::setOption( 'keep_logs', (string) $fs_keep_logs, TRUE );

		Helper::setOption( 'hide_notifications', (string) $fs_hide_notifications, TRUE );
		Helper::setOption( 'show_fs_poster_column', (string) $fs_show_fs_poster_column, TRUE );
		Helper::setOption( 'hide_menu_for', $new_arrHideForRoles, TRUE );
		Helper::setOption( 'collect_statistics', (string) $fs_collect_statistics, TRUE );

		Helper::response( TRUE, [ 'msg' => esc_html__( 'Saved successfully!', 'fs-poster' ) ] );
	}
}
