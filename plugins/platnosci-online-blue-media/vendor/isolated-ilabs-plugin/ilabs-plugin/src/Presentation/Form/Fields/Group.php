<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Form\Fields;

use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Group_Interface;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Group_Item_Interface;
class Group implements Group_Interface, Group_Item_Interface
{
    /**
     * @var string
     */
    private $id;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $desc;
    /**
     * @var Group_Item_Interface[]
     */
    private $items;
    public function to_array() : array
    {
        return ['items' => (function () {
            $items = [];
            foreach ($this->items as $item) {
                $items[] = $item->to_array();
            }
            return $items;
        })(), 'id' => $this->id];
    }
    /**
     * @return string
     */
    public function get_id() : string
    {
        return $this->id;
    }
    /**
     * @param string $id
     */
    public function set_id(string $id) : void
    {
        $this->id = $id;
    }
    /**
     * @return string
     */
    public function get_name() : ?string
    {
        return $this->name;
    }
    /**
     * @param string|null $name
     */
    public function set_name(?string $name) : void
    {
        $this->name = $name;
    }
    /**
     * @return array
     */
    public function get_items() : ?array
    {
        return $this->items;
    }
    /**
     * @param array $items
     */
    public function set_items(array $items) : void
    {
        $this->items = $items;
    }
    /**
     * @return string
     */
    public function get_desc() : ?string
    {
        return $this->desc;
    }
    /**
     * @param string|null $desc
     */
    public function set_desc(?string $desc) : void
    {
        $this->desc = $desc;
    }
}
