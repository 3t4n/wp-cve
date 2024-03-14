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

class SiteData
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
            'name'           => $this->getName(),
            'wpurl'          => $this->getWPURL(),
            'url'            => $this->getURL(),
            'admin_email'    => $this->getAdminEmail(),
            'email'          => $this->getAdminEmail(),
            'charset'        => $this->getCharset(),
            'version'        => $this->getVersion(),
            'html_type'      => $this->getHTMLType(),
            'text_direction' => $this->getTextDirection(),
            'language'       => $this->getLanguage(),
            'description'    => $this->getDescription(),
            'tagline'        => $this->getDescription(),
            'title'          => $this->getTitle(),
            'browser_title'  => $this->getBrowserTitle(),
            'base_url'       => $this->getBaseURL(),
        ];

        $data = new Registry($data);
        return $data;
    }

    /**
     * Site title (set in Settings > General)
     * 
     * @return  string
     */
    private function getName()
    {
        return get_bloginfo('name');
    }

    /**
     * Site tagline (set in Settings > General)
     * 
     * @return  string
     */
    private function getDescription()
    {
        return get_bloginfo('description');
    }

    /**
     * The WordPress address (URL) (set in Settings > General)
     * 
     * @return  string
     */
    private function getWPURL()
    {
        return get_bloginfo('wpurl');
    }

    /**
     * The Site address (URL) (set in Settings > General)
     * 
     * @return  string
     */
    private function getURL()
    {
        return get_bloginfo('url');
    }

    /**
     * Admin email (set in Settings > General)
     * 
     * @return  string
     */
    private function getAdminEmail()
    {
        return get_bloginfo('admin_email');
    }

    /**
     * The "Encoding for pages and feeds" (set in Settings > Reading)
     * 
     * @return  string
     */
    private function getCharset()
    {
        return get_bloginfo('charset');
    }

    /**
     * The current WordPress version
     * 
     * @return  string
     */
    private function getVersion()
    {
        return get_bloginfo('version');
    }

    /**
     * The content-type (default: "text/html").
     * 
     * @return  string
     */
    private function getHTMLType()
    {
        return get_bloginfo('html_type');
    }

    /**
     * Language code for the current site
     * 
     * @return  string
     */
    private function getLanguage()
    {
        return get_bloginfo('language');
    }

    /**
     * Determines whether the current locale is right-to-left (RTL).
     * 
     * @return  string
     */
    private function getTextDirection()
    {
        return is_rtl();
    }

    /**
     * Retrieves the current post title
     * 
     * @return  string
     */
    private function getTitle()
    {
        wp_reset_postdata();
        
        return get_the_title();
    }

    /**
     * Retrieves current browser title
     * 
     * @return  string
     */
    private function getBrowserTitle()
    {
        return wp_get_document_title();
    }

    /**
     * Settings > General : Site Address
     * but appends language code to URL.
     * 
     * @return  string
     */
    private function getBaseURL()
    {
        return get_site_url();
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