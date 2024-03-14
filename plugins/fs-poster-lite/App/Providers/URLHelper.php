<?php

namespace FSPoster\App\Providers;

trait URLHelper
{
	/**
	 * @param $post_id
	 * @param $driver
	 * @param string $username
	 *
	 * @return string
	 */
	public static function postLink ( $post_id, $driver, $username = '' )
	{
		if ( $driver === 'fb' )
		{
			return 'https://fb.com/' . $post_id;
		}
		else if ( $driver === 'linkedin' )
		{
			return 'https://www.linkedin.com/feed/update/' . $post_id . '/';
		}
		else if ( $driver === 'vk' )
		{
			return 'https://vk.com/wall' . $post_id;
		}
		else if ( $driver === 'reddit' )
		{
			return 'https://www.reddit.com/' . $post_id;
		}
		else if ( $driver === 'telegram' )
		{
			return "http://t.me/" . esc_html( $username );
		}
		else if ( $driver === 'tumblr' )
		{
			return 'https://' . $username . '.tumblr.com/post/' . $post_id;
		}
		else if ( $driver === 'plurk' )
		{
			return 'https://plurk.com/p/' . base_convert( $post_id, 10, 36 );
		}
		else if ( $driver === 'ok' )
		{
			if ( strpos( $post_id, 'topic' ) !== FALSE )
			{
				return 'https://ok.ru/group/' . $post_id;
			}
			else
			{
				return 'https://ok.ru/profile/' . $post_id;
			}
		}
	}
}
