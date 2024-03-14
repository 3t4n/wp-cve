<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/*  Copyright 2014 Sutherland Boswell  (email : sutherland.boswell@gmail.com)
Forked by Yehuda Hassine 2020
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Require thumbnail provider class
require_once( VIDEO_THUMBNAILS_RL_PATH . '/php/providers/class-video-thumbnails-provider.php' );

class Metacafe_Thumbnails extends Video_Thumbnails_Provider {

	// Human-readable name of the video provider
	public $service_name = 'Metacafe';
	const service_name = 'Metacafe';
	// Slug for the video provider
	public $service_slug = 'metacafe';
	const service_slug = 'metacafe';

	public static function register_provider( $providers ) {
		$providers[self::service_slug] = new self;
		return $providers;
	}

	// Regex strings
	public $regexes = array(
		'/src="https:\/\/www.metacafe.com\/embed\/(\d+)\/.*"/' // Metacafe iFrame
	);

	// Thumbnail URL
	public function get_thumbnail_url( $id ) {
		$endpoint = $this->get_thumb_base();
		$base = $this->get_base_id($id);
		$result = str_replace( array( '{base}', '{id}'), array( $base, $id ), $endpoint );

		return $result;
	}

	private function get_thumb_base() {
		return 'https://cdn.mcstatic.com/contents/videos_screenshots/{base}/{id}/preview.jpg';
	}

	private function get_base_id($id) {
		return substr($id, 0, -3) . '000';
	}

	// Test cases
	public static function get_test_cases() {
		return array(
			array(
				'markup'        => '<iframe width="560" height="315" src="https://www.metacafe.com/embed/11999155/probably-the-cutest-alligator-in-existence/" frameborder="0" allowfullscreen></iframe>',
				'expected'      => 'https://cdn.mcstatic.com/contents/videos_screenshots/11999000/11999155/preview.jpg',
				'expected_hash' => '23b1e34a4bdcd4e37b99eb46ac34b197',
				'name'          => __( 'iFrame Embed', 'video-thumbnails' )
			),
		);
	}

}

?>