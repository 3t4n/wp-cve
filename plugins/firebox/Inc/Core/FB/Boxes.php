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

namespace FireBox\Core\FB;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use FPFramework\Libs\Registry;
use FireBox\Core\Helpers\BoxHelper;

class Boxes
{
	/**
	 * Render boxes
	 * 
	 * @return  string
	 */
	public function render()
	{
		if (!$boxes = BoxHelper::getAllBoxes())
		{
			return;
		}

		return $this->renderAll($boxes);
	}
	
	/**
	 * Renders all boxes
	 * 
	 * @param   array  $boxes
	 * 
	 * @return  string
	 */
	public function renderAll($boxes)
	{
		$html = '';
		
		if ($boxes instanceof \WP_Query)
		{
			if (!$boxes->have_posts())
			{
				return;
			}

			while ($boxes->have_posts())
			{
				$boxes->the_post();

				global $post;

				$this->prepare($post);

				$html .= $this->get_output($post);
			}
			
			wp_reset_postdata();
		}
		else if (is_array($boxes))
		{
			foreach ($boxes as $box)
			{
				$this->prepare($box);

				$html .= $this->get_output($box);
			}
		}
		
		return $html;
	}

	/**
	 * Prepares the box by setting its meta settings.
	 * 
	 * @param   object  $box
	 * 
	 * @return  void
	 */
	private function prepare(&$box)
	{
		// get meta options for box
		$meta = get_post_meta($box->ID, 'fpframework_meta_settings', true);
		$box->params = new Registry($meta);
	}

	/**
	 * Returns the box output
	 * 
	 * @param   object  $box
	 * 
	 * @return  string
	 */
	private function get_output($box)
	{
		$logged_and_admin = (is_user_logged_in() && current_user_can('manage_options'));

		$testmode = $box->params->get('testmode', false);
		
		// if box is in test mode only logged-in admin users can see it.
		if ($testmode == '1' && !$logged_and_admin)
		{
			return '';
		}
		
		return firebox()->box->setBox($box)->render();
	}
}