<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Menu\Abstraction;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\View\Abstraction\Displayable;
use InvalidArgumentException;
use OutOfBoundsException;
/**
 * Class AbstractMenu, abstraction layer for menus.
 * @package WPDesk\Library\DropshippingXmlCore\Infrastructure\Menu
 */
abstract class AbstractMenu implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Menu\Abstraction\MenuInterface
{
    const DEFAULT_ACTION = 'index';
    /**
     * @var int
     */
    protected $position = 0;
    /**
     * @var string
     */
    protected $slug = '';
    /**
     * @var string
     */
    protected $title = '';
    /**
     * @var string
     */
    protected $capability = '';
    /**
     * @var array
     */
    protected $views = array();
    /**
     * @var bool
     */
    protected $hidden = \false;
    public function get_position() : int
    {
        return $this->position;
    }
    public function set_position(int $position) : self
    {
        $this->position = $position;
        return $this;
    }
    public function get_slug() : string
    {
        return $this->slug;
    }
    public function set_slug(string $slug) : self
    {
        $this->slug = $slug;
        return $this;
    }
    public function get_title() : string
    {
        return $this->title;
    }
    public function set_title(string $title) : self
    {
        $this->title = $title;
        return $this;
    }
    public function get_capability() : string
    {
        return $this->capability;
    }
    public function set_capability(string $capability) : self
    {
        $this->capability = $capability;
        return $this;
    }
    public function is_hidden() : bool
    {
        return $this->hidden;
    }
    public function set_hidden(bool $hidden) : self
    {
        $this->hidden = $hidden;
        return $this;
    }
    public function add_view_actions(array $view_classes) : self
    {
        foreach ($view_classes as $key => $view) {
            $this->add_view($view, $key);
        }
        return $this;
    }
    public function set_default_view(string $view_class) : self
    {
        $this->add_view($view_class, self::DEFAULT_ACTION);
        return $this;
    }
    public function has_view_action(string $action) : bool
    {
        return isset($this->views[$action]);
    }
    public function has_view_class_name(string $class_name) : bool
    {
        return \false !== \array_search($class_name, $this->views);
    }
    public function get_view_by_action(string $action) : string
    {
        if (!$this->has_view_action($action)) {
            throw new \OutOfBoundsException('Error, view for the specified action does not exist');
        }
        return $this->views[$action];
    }
    public function get_action_by_class_name(string $class_name) : string
    {
        if (!$this->has_view_class_name($class_name)) {
            throw new \OutOfBoundsException('Error, action for the specified class name does not exist');
        }
        return \array_search($class_name, $this->views);
    }
    private function add_view(string $view_class, string $action) : self
    {
        if (!\is_subclass_of($view_class, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\View\Abstraction\Displayable::class)) {
            throw new \InvalidArgumentException('Error, ' . $view_class . ' should implements ' . \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\View\Abstraction\Displayable::class);
        }
        $this->views[$action] = $view_class;
        return $this;
    }
}
