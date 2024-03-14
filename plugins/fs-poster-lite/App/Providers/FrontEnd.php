<?php

namespace FSPoster\App\Providers;

class FrontEnd
{
	public function __construct ()
	{
		add_action( 'wp', [ $this, 'boot' ] );
	}

	public function boot ()
	{
		$this->checkVisits();
		$this->goto_add_account();
	}

	public function checkVisits ()
	{
		if ( is_single() || is_page() )
		{

			$feed_id = Request::get( 'feed_id', '0', 'int' );

			if ( ! isset( $_COOKIE[ 'fsp_last_visited_' . $feed_id ] ) )
			{
				global $post;

				if ( isset( $post->ID ) && $feed_id > 0 )
				{
					$post_id = $post->ID;

					DB::DB()->query( DB::DB()->prepare( "UPDATE " . DB::table( 'feeds' ) . " SET visit_count=visit_count+1 WHERE id=%d AND post_id=%d", [
						$feed_id,
						$post_id
					] ) );

					setcookie( 'fsp_last_visited_' . $feed_id, '1', time() + 30, COOKIEPATH, COOKIE_DOMAIN );
				}
			}
		}
	}

	private function goto_add_account ()
	{
		$social_network = Request::get( 'fsp_add_account', '', 'string', [
			'fb',
			'linkedin',
			'vk',
			'reddit',
			'tumblr',
			'ok'
		] );

		if ( ! empty( $social_network ) )
		{
			wp_redirect( FSPL_API_URL . 'redirect/?social_network=' . $social_network . '&domain=' . network_site_url());
		}
	}
}
