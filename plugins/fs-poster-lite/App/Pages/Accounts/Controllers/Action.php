<?php

namespace FSPoster\App\Pages\Accounts\Controllers;

use FSPoster\App\Providers\DB;
use FSPoster\App\Providers\Helper;

class Action
{
	public function get_fb_accounts ()
	{
		$accounts_list  = DB::DB()->get_results( DB::DB()->prepare( "
	SELECT 
		*,
		(SELECT COUNT(0) FROM " . DB::table( 'account_nodes' ) . " WHERE account_id=tb1.id AND node_type='ownpage' ) ownpages,
		(SELECT COUNT(0) FROM " . DB::table( 'account_nodes' ) . " WHERE account_id=tb1.id AND node_type='group' ) `groups`,
		(SELECT COUNT(0) FROM " . DB::table( 'grouped_accounts' ) . " WHERE account_id = tb1.id AND account_type = 'account' AND user_id = %d) `is_hidden`
	FROM " . DB::table( 'accounts' ) . " tb1
	WHERE driver='fb'", [
			get_current_user_id()
		] ), ARRAY_A );
		$my_accounts_id = [ -1 ];

		foreach ( $accounts_list as $i => $account_info )
		{
			$my_accounts_id[] = (int) $account_info[ 'id' ];

			$accounts_list[ $i ][ 'node_list' ] = DB::DB()->get_results( DB::DB()->prepare( "
			SELECT 
				*,
				(SELECT COUNT(0) FROM " . DB::table( 'grouped_accounts' ) . " WHERE account_id = tb1.id AND account_type = 'node' AND user_id = %d) `is_hidden`
			FROM " . DB::table( 'account_nodes' ) . " tb1
			WHERE account_id=%d", [
				get_current_user_id(),
				$account_info[ 'id' ]
			] ), ARRAY_A );
		}

		return [
			'accounts_list' => $accounts_list
		];
	}

	public function get_linkedin_accounts ()
	{
		$accounts_list = DB::DB()->get_results( DB::DB()->prepare( "
	SELECT 
	 	*,
		(SELECT COUNT(0) FROM " . DB::table( 'account_nodes' ) . " WHERE account_id=tb1.id ) AS companies,
		(SELECT COUNT(0) FROM " . DB::table( 'grouped_accounts' ) . " WHERE account_id = tb1.id AND account_type = 'account' AND user_id = %d) `is_hidden`
	FROM " . DB::table( 'accounts' ) . " tb1
	WHERE driver='linkedin'", [
			get_current_user_id()
		] ), ARRAY_A );

		$my_accounts_id = [ -1 ];
		foreach ( $accounts_list as $i => $account_info )
		{
			$my_accounts_id[] = (int) $account_info[ 'id' ];

			$accounts_list[ $i ][ 'node_list' ] = DB::DB()->get_results( DB::DB()->prepare( "
			SELECT 
				*,
		(SELECT COUNT(0) FROM " . DB::table( 'grouped_accounts' ) . " WHERE account_id = tb1.id AND account_type = 'node' AND user_id = %d) `is_hidden`
			FROM " . DB::table( 'account_nodes' ) . " tb1
			WHERE account_id=%d", [
				get_current_user_id(),
				$account_info[ 'id' ]
			] ), ARRAY_A );
		}

		return [
			'accounts_list' => $accounts_list
		];
	}

	public function get_ok_accounts ()
	{
		$accounts_list = DB::DB()->get_results( DB::DB()->prepare( "
	SELECT 
	 	*,
		(SELECT COUNT(0) FROM " . DB::table( 'account_nodes' ) . " WHERE account_id=tb1.id) AS `groups`,
		(SELECT COUNT(0) FROM " . DB::table( 'grouped_accounts' ) . " WHERE account_id = tb1.id AND account_type = 'account' AND user_id = %d) `is_hidden`
	FROM " . DB::table( 'accounts' ) . " tb1
	WHERE driver='ok'", [
			get_current_user_id(),
		] ), ARRAY_A );

		$my_accounts_id = [ -1 ];
		foreach ( $accounts_list as $i => $account_info )
		{
			$my_accounts_id[] = (int) $account_info[ 'id' ];

			$accounts_list[ $i ][ 'node_list' ] = DB::DB()->get_results( DB::DB()->prepare( "
			SELECT 
				*,
		(SELECT COUNT(0) FROM " . DB::table( 'grouped_accounts' ) . " WHERE account_id = tb1.id AND account_type = 'node' AND user_id = %d) `is_hidden`
			FROM " . DB::table( 'account_nodes' ) . " tb1
			WHERE account_id=%d", [
				get_current_user_id(),
				$account_info[ 'id' ]
			] ), ARRAY_A );
		}

		return [
			'accounts_list' => $accounts_list
		];
	}

	public function get_reddit_accounts ()
	{
		$accounts_list = DB::DB()->get_results( DB::DB()->prepare( "
	SELECT 
		*,
		(SELECT COUNT(0) FROM " . DB::table( 'account_nodes' ) . " WHERE account_id=tb1.id ) subreddits,
		(SELECT COUNT(0) FROM " . DB::table( 'grouped_accounts' ) . " WHERE account_id = tb1.id AND account_type = 'account' AND user_id = %d) `is_hidden` 
	FROM " . DB::table( 'accounts' ) . " tb1 
	WHERE driver='reddit'", [
			get_current_user_id()
		] ), ARRAY_A );

		$my_accounts_id = [ -1 ];
		foreach ( $accounts_list as $i => $account_info )
		{
			$my_accounts_id[] = (int) $account_info[ 'id' ];

			$accounts_list[ $i ][ 'node_list' ] = DB::DB()->get_results( DB::DB()->prepare( "
			SELECT 
				*,
		(SELECT COUNT(0) FROM " . DB::table( 'grouped_accounts' ) . " WHERE account_id = tb1.id AND account_type = 'node' AND user_id = %d) `is_hidden`
			FROM " . DB::table( 'account_nodes' ) . " tb1
			WHERE account_id=%d", [
				get_current_user_id(),
				$account_info[ 'id' ]
			] ), ARRAY_A );
		}

		return [
			'accounts_list' => $accounts_list
		];
	}

	public function get_tumblr_accounts ()
	{
		$accounts_list = DB::DB()->get_results( DB::DB()->prepare( "
	SELECT 
		*,
		(SELECT COUNT(0) FROM " . DB::table( 'account_nodes' ) . " WHERE account_id=tb1.id) AS blogs,
		(SELECT COUNT(0) FROM " . DB::table( 'grouped_accounts' ) . " WHERE account_id = tb1.id AND account_type = 'account' AND user_id = %d) `is_hidden`
	FROM " . DB::table( 'accounts' ) . " tb1 
	WHERE driver='tumblr'", [
			get_current_user_id()
		] ), ARRAY_A );

		$my_accounts_id = [ -1 ];
		foreach ( $accounts_list as $i => $account_info )
		{
			$my_accounts_id[] = (int) $account_info[ 'id' ];

			$accounts_list[ $i ][ 'node_list' ] = DB::DB()->get_results( DB::DB()->prepare( "
			SELECT 
				*,
		(SELECT COUNT(0) FROM " . DB::table( 'grouped_accounts' ) . " WHERE account_id = tb1.id AND account_type = 'node' AND user_id = %d) `is_hidden`
			FROM " . DB::table( 'account_nodes' ) . " tb1
			WHERE account_id=%d", [
				get_current_user_id(),
				$account_info[ 'id' ]
			] ), ARRAY_A );
		}

		return [
			'accounts_list' => $accounts_list
		];
	}

	public function get_telegram_accounts ()
	{
		$accounts_list = DB::DB()->get_results( DB::DB()->prepare( "
	SELECT 
		*,
		(SELECT COUNT(0) FROM " . DB::table( 'account_nodes' ) . " WHERE account_id=tb1.id) AS chats,
		(SELECT COUNT(0) FROM " . DB::table( 'grouped_accounts' ) . " WHERE account_id = tb1.id AND account_type = 'account' AND user_id = %d) `is_hidden`
	FROM " . DB::table( 'accounts' ) . " tb1 
	WHERE driver='telegram'", [
			get_current_user_id()
		] ), ARRAY_A );

		$my_accounts_id = [ -1 ];
		foreach ( $accounts_list as $i => $account_info )
		{
			$my_accounts_id[] = (int) $account_info[ 'id' ];

			$accounts_list[ $i ][ 'node_list' ] = DB::DB()->get_results( DB::DB()->prepare( "
			SELECT 
				*,
		(SELECT COUNT(0) FROM " . DB::table( 'grouped_accounts' ) . " WHERE account_id = tb1.id AND account_type = 'node' AND user_id = %d) `is_hidden`
			FROM " . DB::table( 'account_nodes' ) . " tb1
			WHERE account_id=%d", [
				get_current_user_id(),
				$account_info[ 'id' ]
			] ), ARRAY_A );
		}

		return [
			'accounts_list' => $accounts_list
		];
	}

	public function get_plurk_accounts ()
	{
		$accounts_list = DB::DB()->get_results( DB::DB()->prepare( "
	SELECT 
		*,
		(SELECT COUNT(0) FROM " . DB::table( 'grouped_accounts' ) . " WHERE account_id = tb1.id AND account_type = 'account' AND user_id = %d) `is_hidden`
	FROM " . DB::table( 'accounts' ) . " tb1 
	WHERE driver='plurk'", [
			get_current_user_id()
		] ), ARRAY_A );

		return [
			'accounts_list' => $accounts_list
		];
	}

	public function get_vk_accounts ()
	{
		$accounts_list = DB::DB()->get_results( DB::DB()->prepare( "
	SELECT 
		*,
		(SELECT COUNT(0) FROM " . DB::table( 'account_nodes' ) . " WHERE account_id=tb1.id ) communities,
		(SELECT COUNT(0) FROM " . DB::table( 'grouped_accounts' ) . " WHERE account_id = tb1.id AND account_type = 'account' AND user_id = %d) `is_hidden`
	FROM " . DB::table( 'accounts' ) . " tb1 
	WHERE driver='vk'", [
			get_current_user_id()
		] ), ARRAY_A );

		$my_accounts_id = [ -1 ];
		foreach ( $accounts_list as $i => $account_info )
		{
			$my_accounts_id[] = (int) $account_info[ 'id' ];

			$accounts_list[ $i ][ 'node_list' ] = DB::DB()->get_results( DB::DB()->prepare( "
			SELECT 
				*,
		(SELECT COUNT(0) FROM " . DB::table( 'grouped_accounts' ) . " WHERE account_id = tb1.id AND account_type = 'node' AND user_id = %d) `is_hidden`
			FROM " . DB::table( 'account_nodes' ) . " tb1
			WHERE account_id=%d", [
				get_current_user_id(),
				$account_info[ 'id' ]
			] ), ARRAY_A );
		}

		return [
			'accounts_list' => $accounts_list
		];
	}

	public function get_counts ()
	{
		$accounts_list = DB::DB()->get_results( "SELECT driver, COUNT(0) AS _count FROM " . DB::table( 'accounts' ) . " GROUP BY driver", ARRAY_A );
		$nodes_list    = DB::DB()->get_results( 'SELECT driver, 1 AS _count FROM ' . DB::table( 'account_nodes' ) . ' WHERE account_id NOT IN ( SELECT id FROM ' . DB::table( 'accounts' ) . ' ) GROUP BY driver', ARRAY_A );

		$fsp_accounts_count = [
			'total'     => 0,
			'fb'        => [
				'total'  => 0,
				'active' => 0
			],
			'instagram' => [
				'total'  => 0,
				'active' => 0
			],
			'linkedin'  => [
				'total'  => 0,
				'active' => 0
			],
			'vk'        => [
				'total'  => 0,
				'active' => 0
			],
			'pinterest' => [
				'total'  => 0,
				'active' => 0
			],
			'reddit'    => [
				'total'  => 0,
				'active' => 0
			],
			'tumblr'    => [
				'total'  => 0,
				'active' => 0
			],
			'google_b'  => [
				'total'  => 0,
				'active' => 0
			],
			'blogger'   => [
				'total'  => 0,
				'active' => 0
			],
			'ok'        => [
				'total'  => 0,
				'active' => 0
			],
			'plurk'     => [
				'total'  => 0,
				'active' => 0
			],
			'telegram'  => [
				'total'  => 0,
				'active' => 0
			],
			'medium'    => [
				'total'  => 0,
				'active' => 0
			],
			'wordpress' => [
				'total'  => 0,
				'active' => 0
			]
		];

		foreach ( $accounts_list as $a_info )
		{
			if ( isset( $fsp_accounts_count[ $a_info[ 'driver' ] ] ) )
			{
				$fsp_accounts_count[ $a_info[ 'driver' ] ][ 'total' ] = $a_info[ '_count' ];
				$fsp_accounts_count[ 'total' ]                        += $a_info[ '_count' ];
			}
		}

		foreach ( $nodes_list as $node_info )
		{
			if ( isset( $fsp_accounts_count[ $node_info[ 'driver' ] ] ) )
			{
				$fsp_accounts_count[ $node_info[ 'driver' ] ][ 'total' ] += $node_info[ '_count' ];
				$fsp_accounts_count[ 'total' ]                           += $node_info[ '_count' ];
			}
		}

		$active_accounts = DB::DB()->get_results( "SELECT `driver` FROM " . DB::table( 'accounts' ) . " WHERE is_active=1 OR `id` IN ( SELECT `account_id` FROM " . DB::table( 'account_nodes' ) . " WHERE is_active=1 ) GROUP BY `driver`", ARRAY_A );

		foreach ( $active_accounts as $a_info )
		{
			if ( isset( $fsp_accounts_count[ $a_info[ 'driver' ] ] ) )
			{
				$fsp_accounts_count[ $a_info[ 'driver' ] ][ 'active' ] = 1;
			}
		}

		return $fsp_accounts_count;
	}

	public static function add_account ( $account, $nodes = [] )
	{
		$check_if_added = DB::fetch( 'accounts', [ 'fs_account_id' => $account[ 'fs_account_id' ] ] );

		if ( ! $check_if_added )
		{
			DB::DB()->insert( DB::table( 'accounts' ), $account );
			$insertID = DB::DB()->insert_id;
		}
		else
		{
			DB::DB()->update( DB::table( 'accounts' ), $account, [ 'id' => $check_if_added[ 'id' ] ] );
			$insertID = $check_if_added[ 'id' ];
		}

		self::add_node( $insertID, $nodes );

		return [
			'account_id' => $insertID
		];
	}

	public static function add_node ( $accountId, $nodes )
	{
		$node_ids = [];
		foreach ( $nodes as &$node )
		{
			$node_ids[]           = $node[ 'fs_account_id' ];
			$node[ 'account_id' ] = $accountId;

			if ( DB::fetch( 'account_nodes', [ 'fs_account_id' => $node[ 'fs_account_id' ] ] ) )
			{
				DB::DB()->update( DB::table( 'account_nodes' ), $node, [ 'fs_account_id' => $node[ 'fs_account_id' ] ] );
			}
			else
			{
				DB::DB()->insert( DB::table( 'account_nodes' ), $node );
			}
		}

		$dq = '';

		if ( ! empty( $node_ids ) )
		{
			$ids = "(" . implode( ',', $node_ids ) . ")";
			$dq  = " AND `fs_account_id` NOT IN $ids";
		}

		DB::DB()->query( "DELETE FROM " . DB::table( 'account_nodes' ) . " WHERE `account_id`=$accountId" . $dq );
	}

	public static function delete_account ( $account_id )
	{
		$check_account = DB::fetch( 'accounts', $account_id );

		if ( ! $check_account )
		{
			return [ 'status' => FALSE, 'error_msg' => esc_html__( 'The account isn\'t found!', 'fs-poster' ) ];
		}
		else if ( $check_account[ 'is_active' ] )
		{
			return [
				'status'    => FALSE,
				'error_msg' => esc_html__( 'You can\'t delete an active community.', 'fs-poster' )
			];
		}

		$response = Helper::api_cmd( 'delete_account', 'POST', Helper::getOption( 'access_token', '', TRUE ), [ 'id' => $check_account[ 'fs_account_id' ] ] );

		if ( isset( $response[ 'status' ] ) && $response[ 'status' ] === 'error' )
		{
			return [
				'status'    => FALSE,
				'error_msg' => $response[ 'error_msg' ]
			];
		}

		DB::DB()->delete( DB::table( 'accounts' ), [ 'id' => $account_id ] );

		DB::DB()->delete( DB::table( 'account_nodes' ), [ 'account_id' => $account_id ] );

		return [ 'status' => TRUE ];
	}

	public static function delete_node ( $node_id )
	{
		$check_account = DB::fetch( 'account_nodes', $node_id );

		if ( ! $check_account )
		{
			return [ 'status' => FALSE, 'error_msg' => esc_html__( 'The account isn\'t found!', 'fs-poster' ) ];
		}

		if ( $check_account[ 'is_active' ] )
		{
			return [
				'status'    => FALSE,
				'error_msg' => esc_html__( 'You can\'t delete an active community.', 'fs-poster' )
			];
		}

		if ( $check_account[ 'node_type' ] === 'subreddit' || $check_account[ 'node_type' ] === 'chat' )
		{
			$response = Helper::api_cmd( 'delete_account', 'POST', Helper::getOption( 'access_token', '', TRUE ), [ 'id' => $check_account[ 'fs_account_id' ] ] );

			if ( isset( $response[ 'status' ] ) && $response[ 'status' ] === 'error' )
			{
				return [
					'status'    => FALSE,
					'error_msg' => $response[ 'error_msg' ]
				];
			}
		}

		DB::DB()->delete( DB::table( 'account_nodes' ), [ 'id' => $node_id ] );

		return [ 'status' => TRUE ];
	}

	public static function activate_deactivate_account ( $account_id )
	{
		$check_account = DB::fetch( 'accounts', $account_id );

		if ( ! $check_account )
		{
			return [ 'status' => FALSE, 'error_msg' => esc_html__( 'The account isn\'t found!', 'fs-poster' ) ];
		}

		$response = Helper::api_cmd( 'activate_account', 'POST', Helper::getOption( 'access_token', '', TRUE ), [ 'id' => $check_account[ 'fs_account_id' ] ], TRUE );

		if ( $response[ 'status' ] === 'error' )
		{
			return [
				'status'    => FALSE,
				'error_msg' => $response[ 'error_msg' ]
			];
		}

		if ( $response[ 'status' ] === 'ok' )
		{
			DB::DB()->update( DB::table( 'accounts' ), [ 'is_active' => 0 ], [ 'is_active' => 1 ] );
			DB::DB()->update( DB::table( 'account_nodes' ), [ 'is_active' => 0 ], [ 'is_active' => 1 ] );

			if ( ! empty( $response[ 'ids' ] ) )
			{
				$ids = '(' . implode( ',', $response[ 'ids' ] ) . ')';
				DB::DB()->query( "UPDATE `" . DB::table( 'accounts' ) . "` SET `is_active`=1 WHERE `fs_account_id` IN $ids" );
				DB::DB()->query( "UPDATE `" . DB::table( 'account_nodes' ) . "` SET `is_active`=1 WHERE `fs_account_id` IN $ids" );
			}
			return [ 'status' => TRUE ];
		}

		return [ 'status' => FALSE ];
	}

	public static function activate_deactivate_node ( $node_id )
	{
		$check_account = DB::fetch( 'account_nodes', $node_id );

		if ( ! $check_account )
		{
			return [ 'status' => FALSE, 'error_msg' => esc_html__( 'The account isn\'t found!', 'fs-poster' ) ];
		}

		$response = Helper::api_cmd( 'activate_account', 'POST', Helper::getOption( 'access_token', '', TRUE ), [ 'id' => $check_account[ 'fs_account_id' ] ] );

		if ( $response[ 'status' ] === 'error' )
		{
			return [
				'status'    => FALSE,
				'error_msg' => $response[ 'error_msg' ]
			];
		}

		if ( $response[ 'status' ] === 'ok' )
		{
			DB::DB()->update( DB::table( 'accounts' ), [ 'is_active' => 0 ], [ 'is_active' => 1 ] );
			DB::DB()->update( DB::table( 'account_nodes' ), [ 'is_active' => 0 ], [ 'is_active' => 1 ] );

			if ( ! empty( $response[ 'ids' ] ) )
			{
				$ids = '(' . implode( ',', $response[ 'ids' ] ) . ')';
				DB::DB()->query( "UPDATE `" . DB::table( 'accounts' ) . "` SET `is_active`=1 WHERE `fs_account_id` IN $ids" );
				DB::DB()->query( "UPDATE `" . DB::table( 'account_nodes' ) . "` SET `is_active`=1 WHERE `fs_account_id` IN $ids" );
			}
			return [ 'status' => TRUE ];
		}

		return [ 'status' => FALSE ];
	}

	public static function hide_unhide_account ( $account_id, $checked )
	{
		$check_account = DB::fetch( 'accounts', $account_id );

		if ( ! $check_account )
		{
			return [ 'status' => FALSE, 'error_msg' => esc_html__( 'The account isn\'t found!', 'fs-poster' ) ];
		}

		$get_visibility = DB::fetch( 'grouped_accounts', [
			'account_id'   => $account_id,
			'account_type' => 'account',
			'user_id'      => get_current_user_id()
		] );

		if ( ! $get_visibility && $checked )
		{
			DB::DB()->insert( DB::table( 'grouped_accounts' ), [
				'account_id'   => $account_id,
				'account_type' => 'account',
				'user_id'      => get_current_user_id()
			] );
		}
		else if ( $get_visibility && ! $checked )
		{
			DB::DB()->delete( DB::table( 'grouped_accounts' ), [
				'account_id'   => $account_id,
				'account_type' => 'account',
				'user_id'      => get_current_user_id()
			] );
		}

		return [ 'status' => TRUE ];
	}

	public static function hide_unhide_node ( $node_id, $checked )
	{
		$check_account = DB::fetch( 'account_nodes', $node_id );

		if ( ! $check_account )
		{
			return [ 'status' => FALSE, 'error_msg' => esc_html__( 'The account isn\'t found!', 'fs-poster' ) ];
		}

		if ( $check_account[ 'account_id' ] != get_current_user_id() && $check_account[ 'is_public' ] != 1 )
		{
			return [
				'status'    => FALSE,
				'error_msg' => esc_html__( 'You haven\'t sufficient permissions!', 'fs-poster' )
			];
		}

		$get_visibility = DB::fetch( 'grouped_accounts', [
			'account_id'   => $node_id,
			'account_type' => 'node',
			'user_id'      => get_current_user_id()
		] );

		if ( ! $get_visibility && $checked )
		{
			DB::DB()->insert( DB::table( 'grouped_accounts' ), [
				'account_id'   => $node_id,
				'account_type' => 'node',
				'user_id'      => get_current_user_id()
			] );
		}
		else if ( $get_visibility && ! $checked )
		{
			DB::DB()->delete( DB::table( 'grouped_accounts' ), [
				'account_id'   => $node_id,
				'account_type' => 'node',
				'user_id'      => get_current_user_id()
			] );
		}

		return [ 'status' => TRUE ];
	}
}