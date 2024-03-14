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

namespace FPFramework\Base;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

// @deprecated  - Use \FPFramework\Base\Conditions\ConditionsHelper;
class Assignments
{   
    /**
     *  Factory object 
     * 
     *  @var \FPFramework\Base\Factory
     */
    protected $factory;

	/**
	 * Assignments to Conditions map.
	 * 
	 * @deprecated  To be removed Early/Mid 2024
	 */
	protected $typeAliases = [
		'date' 	=> 'Date\Date',
		'time'	=> 'Date\Time',
		'ip'	=> 'IP',
		'url'	=> 'URL',
		'timeonsite' => 'TimeOnSite',
		'posts' => 'WP\Posts',
		'pages' => 'WP\Pages',
		'cpts' => 'WP\CustomPostTypes',
		'tags' => 'WP\Tags',
		'categories' => 'WP\Categories',
		'device' => 'Device',
		'browser' => 'Browser',
		'os' => 'OS',
		'city' => 'Geo\City',
		'country' => 'Geo\Country',
		'continent' => 'Geo\Continent',
		'fbform' => 'FireBox\Form',
		'menu'	=> 'WP\Menu',
		'grouplevel' => 'WP\UserGroup',
		'userid' => 'WP\UserID',
		'pageviews' => 'Pageviews',
		'onotherbox' => 'FireBox\Popup',
		'language' => 'WP\Language',
		'referrer' => 'Referrer',
		'cookie' => 'Cookie',
		'php' => 'PHP',
	];
	
    /**
     *  Constructor
     */
    public function __construct($factory = null)
    {
        $this->factory = is_null($factory) ? new \FPFramework\Base\Factory() : $factory;        
    }

    /**
     *  Returns the classname for a given assignment alias
     *
     *  @param  string       $alias
     *  @return string|void
     */
    public function aliasToClassname($alias)
    {
        $alias = strtolower($alias);
        foreach ($this->typeAliases as $aliases => $type)
        {
            if (strtolower($type) == $alias)
            {
                return $type;
            }

            $aliases = explode('|', strtolower($aliases));
            if (in_array($alias, $aliases))
            {
                return $type;                
            }   
        }

        return null;
    }
}