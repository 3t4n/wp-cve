<?php
/**
 * @package         FireBox
 * @version         2.1.8 Free
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FireBox\Core\Analytics\Helpers;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Campaigns
{
	/**
	 * Gets recents X campaigns.
	 * 
	 * @param   int    $limit
	 * 
	 * @return  array
	 */
	public static function getRecentCampaignsList($limit = -1)
	{
		$args_published = [
			'post_type'      => 'firebox',
			'posts_per_page' => $limit,
			'post_status'    => ['publish', 'draft'],
			'orderby'        => 'post_modified',
			'order'          => 'DESC'
		];
		
		$query = new \WP_Query($args_published);
		
		wp_reset_postdata($query);
		
		return $query;
	}
}