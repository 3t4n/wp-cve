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

namespace FireBox\Core\Controllers;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use \FireBox\Core\Helpers\Form\Form;

class Campaigns extends BaseController
{
	/**
	 * Render Submissions page.
	 * 
	 * @return  void
	 */
	public function render()
	{
		firebox()->renderer->admin->render('pages/campaigns');
	}
}