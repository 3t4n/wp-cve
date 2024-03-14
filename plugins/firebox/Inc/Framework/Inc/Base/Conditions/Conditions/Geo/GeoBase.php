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

namespace FPFramework\Base\Conditions\Conditions\Geo;

defined('ABSPATH') or die;

use FPFramework\Base\Conditions\Condition;
use FPFramework\Base\User;

/**
 *  IP addresses sample
 *
 *  Greece / Dodecanese:  94.67.238.3
 *  Belgium / Flanders:   37.62.255.255
 *  USA / New York:       72.229.28.185
 */
class GeoBase extends Condition
{
    /**
     *  GeoIP Class
     *
     *  @var  class
     */
    protected $geo;

    /**
     * Indicates whether we detected successfully the user's geographical location
     *
     * @var bool
     */
    protected $success;

    /**
     *  Class constructor
     *
     *  @param  object  $options
     *  @param  object  $factory
     */
    public function __construct($options = null, $factory = null)
    {
        parent::__construct($options, $factory);

        $ip = $this->params->get('ip', null);

        $this->loadGeo($ip);
    }

    /**
     *  Load GeoIP Classes
     *
     *  @return  void
     */
    private function loadGeo($ip)
    {
        $this->geo = $this->factory->getGeoIP($ip);

        $record = $this->geo->getRecord();

		$this->success = ($record !== false AND !is_null($record));
    }

	/**
	 * A one-line text that describes the current value detected by the rule. Eg: The current time is %s.
	 *
	 * @return string
	 */
	public function getValueHint()
	{
        if (!$this->success)
        {
            return sprintf(fpframework()->_('FPF_DISPLAY_CONDITIONS_HINT_GEO_ERROR'), User::getIP());
        }

		// If the rule returns an array, use the 1st one.
		$value = $this->value();
		$value = is_array($value) ? $value[0] : $value;

		return sprintf(fpframework()->_('FPF_DISPLAY_CONDITIONS_HINT_GEO'), User::getIP(), $this->getName(), ucfirst(strtolower($value)));
	}
}