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

namespace FPFramework\Base\Factory;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use \FPFramework\Libs\Registry;

class Language
{
    private $data = [];

    public function __construct()
    {
        $this->setData();
    }

    private function setData()
    {
        $this->data = $this->getData();
    }

    private function getData()
    {
        $data = [
            'url' => $this->getLanguageURL(),
            'locale' => $this->getLocale(),
            'user_locale' => $this->getUserLocale()
        ];

        $data = new Registry($data);
        return $data;
    }

	/**
	 * Retrieves the language URL
	 * Example: en_US => en
	 * 
	 * @return  string
	 */
    private function getLanguageURL()
    {
		$default_lang = 'en';
		$locale       = $this->getUserLocale();
	
		if (!empty($locale))
		{
			$lang = explode( '_', $locale );
			if (!empty($lang) && is_array($lang))
			{
				$default_lang = strtolower($lang[0]);
			}
		}
	
		return $default_lang;
    }
    
    /**
     * Returns the current locale
     * 
     * @return  string
     */
    protected function getLocale()
    {
        return get_locale();
    }
    
    /**
     * Returns the user locale
     * 
     * @return  string
     */
    protected function getUserLocale()
    {
        return get_user_locale();
    }

    public function get($key)
    {
        if (!$this->data->get($key))
        {
            return '';
        }

        return $this->data->get($key);
    }
}