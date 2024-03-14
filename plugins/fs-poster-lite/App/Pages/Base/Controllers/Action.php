<?php

namespace FSPoster\App\Pages\Base\Controllers;

use FSPoster\App\Providers\DB;
use FSPoster\App\Providers\Helper;
use FSPoster\App\Providers\Request;

class Action
{
	public function get_post_meta_box ( $post_id )
	{
		$share = Request::get( 'share', '0', 'string' );

		if ( ! defined( 'FSPL_NOT_CHECK_SP' ) && $share === '1' )
		{
			$check_not_sended_feeds = DB::DB()->get_row( DB::DB()->prepare( "SELECT count(0) AS cc FROM " . DB::table( 'feeds' ) . " WHERE post_id=%d AND is_sended=0", [
				(int) $post_id
			] ), ARRAY_A );
		}

		$accounts = DB::fetchAll( 'accounts', [ 'is_active' => 1 ] );

		$active_nodes = DB::fetchAll( 'account_nodes', [ 'is_active' => 1 ] );

		$active_nodes = array_merge( $accounts, $active_nodes );

		if ( isset( $post_id ) && $post_id > 0 && ( get_post_status() === 'draft' || get_post_status() === 'pending' ) )
		{
			$share_checkbox = get_post_meta( $post_id, '_fs_poster_share', TRUE );
		}
		else
		{
			$share_checkbox = Helper::getOption( 'auto_share_new_posts', '1', TRUE ) || Request::get( 'page' ) == 'fs-poster-share' || Request::post( 'post_id', NULL ) !== NULL;
		}

		return [
			'active_nodes'           => $active_nodes,
			'share_checkbox'         => $share_checkbox,
			'check_not_sended_feeds' => isset( $check_not_sended_feeds ) ? $check_not_sended_feeds : NULL,
			'post_id'                => $post_id,
			'tabs'                   => [
				'fb'        => [
					'icon' => 'fab fa-facebook-f',
					'long' => esc_html__( 'Facebook', 'fs-poster' )
				],
				'instagram' => [
					'icon' => 'fab fa-instagram',
					'long' => esc_html__( 'Instagram', 'fs-poster' )
				],
				'linkedin'  => [
					'icon' => 'fab fa-linkedin-in',
					'long' => esc_html__( 'Linkedin', 'fs-poster' )
				],
				'vk'        => [
					'icon' => 'fab fa-vk',
					'long' => esc_html__( 'VKontakte', 'fs-poster' )
				],
				'pinterest' => [
					'icon' => 'fab fa-pinterest-p',
					'long' => esc_html__( 'Pinterest', 'fs-poster' )
				],
				'reddit'    => [
					'icon' => 'fab fa-reddit-alien',
					'long' => esc_html__( 'Reddit', 'fs-poster' )
				],
				'tumblr'    => [
					'icon' => 'fab fa-tumblr',
					'long' => esc_html__( 'Tumblr', 'fs-poster' )
				],
				'ok'        => [
					'icon' => 'fab fa-odnoklassniki',
					'long' => esc_html__( 'Odnoklassniki', 'fs-poster' )
				],
				'plurk'     => [
					'icon' => 'fas fa-parking',
					'long' => esc_html__( 'Plurk', 'fs-poster' )
				],
				'google_b'  => [
					'icon' => 'fab fa-google',
					'long' => esc_html__( 'GMB', 'fs-poster' )
				],
				'blogger'   => [
					'icon' => 'fab fa-blogger',
					'long' => esc_html__( 'Blogger', 'fs-poster' )
				],
				'telegram'  => [
					'icon' => 'fab fa-telegram-plane',
					'long' => esc_html__( 'Telegram', 'fs-poster' )
				],
				'medium'    => [
					'icon' => 'fab fa-medium-m',
					'long' => esc_html__( 'Medium', 'fs-poster' )
				],
				'wordpress' => [
					'icon' => 'fab fa-wordpress-simple',
					'long' => esc_html__( 'WordPress', 'fs-poster' )
				],
			]
		];
	}

	public function get_post_meta_box_edit ( $data )
	{
		$share = Request::get( 'share', '0', 'string' );

		if ( $share === '1' )
		{
			$background = Request::get( 'background', '', 'string' );

			if ( ! empty( $background ) )
			{
				?>
				<script>
					jQuery( document ).ready( function () {
						FSPoster.toast( "<?php echo esc_html__( 'The post will be shared in the background!', 'fs-poster' ); ?>", 'info' );

						window.history.pushState( {}, '', window.location.href.replace( /&share=1&background=([0-9]+)/, '' ) );
					} );
				</script>
				<?php
			}
			else
			{
				$checkNotSendedFeeds = DB::DB()->get_row( DB::DB()->prepare( "SELECT count(0) AS cc FROM " . DB::table( 'feeds' ) . " WHERE post_id=%d AND is_sended=0", [
					(int) $data[ 'post' ]->ID
				] ), ARRAY_A );
			}
		}

		$feeds = DB::fetchAll( 'feeds', [ 'post_id' => $data[ 'post' ]->ID ] );

		return [
			'parameters'             => [
				'post' => $data[ 'post' ]
			],
			'feeds'                  => $feeds,
			'check_not_sended_feeds' => isset( $checkNotSendedFeeds ) ? $checkNotSendedFeeds : [ 'cc' => 0 ]
		];
	}
}