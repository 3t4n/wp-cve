<?php

/**
 * Interface Dotdigital_WordPress_Page_Tab_Interface
 *
 * @package Dotdigital_WordPress
 */
namespace Dotdigital_WordPress\Admin\Page;

interface Dotdigital_WordPress_Page_Tab_Interface
{
    /**
     * Boot the page and add the hooks and filters.
     *
     * @return mixed
     */
    public function initialise();
    /**
     * @return void
     */
    public function render();
    /**
     * Get the title of the page.
     *
     * @return string
     */
    public function get_title();
    /**
     * The slug is also the option name.
     *
     * @return string
     */
    public function get_slug() : string;
    /**
     * For the URL.
     *
     * @return string
     */
    public function get_url_slug() : string;
}
