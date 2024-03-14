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

namespace FPFramework\Base\Fields;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use FPFramework\Base\Field;

class GeoLastUpdated extends Field
{
	public function getValue()
	{
		$file = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'fpframework' . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . 'GeoLite2-City.mmdb';
		
        if (!file_exists($file))
        {
            return '';
		}
		
		$date = new \DateTime();
		$date->setTimestamp(@filemtime($file));
		
		$factory = new \FPFramework\Base\Factory();

		return $factory->getDate($date->format('Y-m-d H:i:s'))->format('d M Y H:m');
	}
}