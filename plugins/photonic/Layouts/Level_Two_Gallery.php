<?php
namespace Photonic_Plugin\Layouts;

use Photonic_Plugin\Components\Album_List;
use Photonic_Plugin\Platforms\Base;

interface Level_Two_Gallery {
	/**
	 * Generates the HTML for a group of level-2 items, i.e. Photosets (Albums) and Galleries for Flickr, Albums for Google Photos,
	 * Albums for SmugMug, and Photosets (Galleries and Collections) for Zenfolio. No concept of albums
	 * exists in native WP and Instagram.
	 *
	 * @param Album_List $album_list
	 * @param array $short_code
	 * @param $module Base
	 * @return string
	 */
	public function generate_level_2_gallery(Album_List $album_list, array $short_code, Base $module): string;
}
