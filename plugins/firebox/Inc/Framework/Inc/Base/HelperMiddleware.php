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

use \FPFramework\Helpers\MenuHelper;
use \FPFramework\Helpers\CategoriesHelper;
use \FPFramework\Helpers\ContinentsHelper;
use \FPFramework\Helpers\CountriesHelper;
use \FPFramework\Helpers\BrowsersHelper;
use \FPFramework\Helpers\CptsHelper;
use \FPFramework\Helpers\DevicesHelper;
use \FPFramework\Helpers\LanguageHelper;
use \FPFramework\Helpers\OsesHelper;
use \FPFramework\Helpers\PostsHelper;
use \FPFramework\Helpers\PagesHelper;
use \FPFramework\Helpers\TagsHelper;
use \FPFramework\Helpers\UserHelper;
use \FPFramework\Helpers\UserIDHelper;
use \FPFramework\Helpers\UserRoleHelper;
use \FPFramework\Helpers\ConditionsHelper;
use \FPFramework\Helpers\SearchDropdownBaseHelper;
use \FPFramework\Helpers\FireBoxHelper;
use \FPFramework\Helpers\FireBoxFormHelper;
use \FPFramework\Helpers\WooCommerceHelper;
use \FPFramework\Helpers\WooCommerceCategoryHelper;
use \FPFramework\Helpers\EDDHelper;
use \FPFramework\Helpers\EDDCategoryHelper;

class HelperMiddleware {
    public $factory;
    public $wpdb;

    public $menu;

    public $categories;
    public $conditions;
    public $continents;
    public $browsers;
    public $countries;
    public $cpts;
    public $devices;
    public $language;
    public $oses;
    public $posts;
    public $pages;
    public $tags;
    public $user;
    public $userid;
    public $userrole;
    public $searchdropdownbase;

    // FireBox
    public $firebox;
    public $fireboxform;

    // EDD
    public $edd;
    public $eddcategory;

    // WooCommerce
    public $woocommerce;
    public $woocommercecategory;

    public function __construct($factory = null)
    {
        if (!$factory)
        {
            $factory = new \FPFramework\Base\Factory();
        }

        $this->factory = $factory;
        $this->wpdb = $this->factory->getDbo();

        $this->menu = new MenuHelper();

        $this->categories = new CategoriesHelper();
        $this->conditions = new ConditionsHelper();
        $this->continents = new ContinentsHelper();
        $this->browsers = new BrowsersHelper();
        $this->countries = new CountriesHelper();
        $this->cpts = new CptsHelper();
        $this->devices = new DevicesHelper();
        $this->language = new LanguageHelper();
        $this->oses = new OsesHelper();
        $this->posts = new PostsHelper();
        $this->pages = new PagesHelper();
        $this->tags = new TagsHelper();
        $this->user = new UserHelper();
        $this->userid = new UserIDHelper();
        $this->userrole = new UserRoleHelper();
        $this->searchdropdownbase = new SearchDropdownBaseHelper();

        // FireBox
        $this->firebox = new FireBoxHelper();
        $this->fireboxform = new FireBoxFormHelper();

        // WooCommerce
        $this->woocommerce = new WooCommerceHelper();
        $this->woocommercecategory = new WooCommerceCategoryHelper();
        
        // EDD
        $this->edd = new EDDHelper();
        $this->eddcategory = new EDDCategoryHelper();
    }

    /**
     * Set middleware value
     * 
     * @param   string  $key
     * @param   object  $value
     * 
     * @return  void
     */
    public function setMiddleware($key, $value)
    {
        if (!$key)
        {
            return;
        }

        if (!property_exists($this, $key))
        {
            return;
        }

        $this->$key = $value;
    }
}