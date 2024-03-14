<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Menu;

/**
 * Class SubMenu, sub menu class.
 * @package WPDesk\Library\DropshippingXmlCore\Infrastructure\Menu
 */
class SubMenu extends \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Menu\Abstraction\AbstractMenu implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Menu\Abstraction\SubMenuInterface
{
    /**
     * @var string
     */
    private $parent_slug = '';
    public function get_parent_slug() : string
    {
        return $this->parent_slug;
    }
    public function set_parent_slug(string $parent_slug) : self
    {
        $this->parent_slug = $parent_slug;
        return $this;
    }
}
