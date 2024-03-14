<?php

namespace Modular\ConnectorDependencies\Ares\Framework\Wordpress\Admin;

/**
 * This class register menus in the admin panel.
 *
 * @link https://developer.wordpress.org/reference/functions/add_submenu_page/
 * @since 1.1.0
 * @internal
 */
abstract class Submenu extends Menu implements SubmenuInterface
{
    /**
     * The slug name for the parent menu
     *
     * @var string|MenuInterface
     */
    protected $parent;
    /**
     * Set slug name for the parent menu
     *
     * @return string|null
     */
    public function parent() : ?string
    {
        $parent = $this->parent;
        if ($parent instanceof MenuInterface) {
            $parent = $parent->slug;
        }
        return $parent;
    }
    /**
     * Set slug name for the parent menu
     *
     * @param $parent
     * @return $this
     */
    public function setParent($parent) : self
    {
        $this->parent = $parent;
        return $this;
    }
    /**
     * Register
     *
     * @return void
     * @throws \Exception
     */
    public function register()
    {
        \add_action('admin_menu', function () {
            \add_submenu_page($this->parent(), $this->pageTitle(), $this->menuTitle(), $this->capability, $this->slug, [$this, 'process'], $this->position);
        });
    }
}
