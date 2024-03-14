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

class OsesHelper extends SearchDropdownProviderHelper
{
	public function __construct($provider = null)
	{
		$this->class_name = 'Oses';

		parent::__construct($provider);
	}

	/**
	 * Returns all OSes
	 * 
	 * @return  array
	 */
	public static function getOSes()
	{
		return [
			'linux'      => fpframework()->_('FPF_LINUX'),
			'mac'        => fpframework()->_('FPF_MAC'),
			'android'    => fpframework()->_('FPF_ANDROID'),
			'ios'        => fpframework()->_('FPF_IOS'),
			'windows'    => fpframework()->_('FPF_WINDOWS'),
			'blackberry' => fpframework()->_('FPF_BLACKBERRY'),
			'chromeos'   => fpframework()->_('FPF_CHROMEOS')
		];
	}
}