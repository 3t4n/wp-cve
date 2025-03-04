<?php
namespace SiteSEO\Actions\Api\Metas;

if (! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Core\Hooks\ExecuteHooks;

class AdvancedSettings implements ExecuteHooks
{
	public function hooks() {
		register_post_meta( '', '_siteseo_robots_primary_cat',
			[
				'show_in_rest' => true,
				'single'	   => true,
				'type'		 => 'string',
				'auth_callback' => [$this, 'meta_auth']
			]
		);
	}

	/**
	 * Auth callback is required for protected meta keys
	 *
	 * @param   bool	$allowed
	 * @param   string  $meta_key
	 * @param   int	 $id
	 * @return  bool	$allowed
	 */
	public function meta_auth( $allowed, $meta_key, $id ) {
		return current_user_can( 'edit_posts', $id );
	}
}
