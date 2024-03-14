<?php

namespace WpifyWooDeps\Wpify\Core\Abstracts;

/**
 * @package Wpify\Core
 */
abstract class AbstractController extends AbstractComponent
{
    /**
     * Disable auto init by default
     *
     * @var bool
     */
    protected $auto_init = \false;
    private $template = '';
    private $assets = array();
    /**
     * @return string
     */
    public function get_template() : string
    {
        return $this->template;
    }
    /**
     * Set the view to render
     *
     * @param $template
     */
    public function set_template($template)
    {
        $this->template = $template;
    }
    /**
     * @return array
     */
    public function get_assets() : array
    {
        return $this->assets;
    }
    /**
     * Set assets
     *
     * @param $assets
     */
    public function set_assets($assets)
    {
        $this->assets = $assets;
    }
    /**
     * Render the view
     *
     * @return mixed
     */
    public function render()
    {
        return null;
    }
}
