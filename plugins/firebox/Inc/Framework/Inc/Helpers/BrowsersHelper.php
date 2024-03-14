<?php
/**
 * @package         FirePlugins Framework
 * @version         1.1.94
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FPFramework\Helpers;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class BrowsersHelper extends SearchDropdownProviderHelper
{
	public function __construct($provider = null)
	{
		$this->class_name = 'Browsers';

		parent::__construct($provider);
	}

	/**
	 * Returns all browser
	 * 
	 * @return  array
	 */
	public static function getBrowsers()
	{
		return [
			'chrome'  => fpframework()->_('FPF_CHROME'),
			'firefox' => fpframework()->_('FPF_FIREFOX'),
			'edge'    => fpframework()->_('FPF_EDGE'),
			'ie'      => fpframework()->_('FPF_IE'),
			'safari'  => fpframework()->_('FPF_SAFARI'),
			'opera'   => fpframework()->_('FPF_OPERA')
		];
	}
}