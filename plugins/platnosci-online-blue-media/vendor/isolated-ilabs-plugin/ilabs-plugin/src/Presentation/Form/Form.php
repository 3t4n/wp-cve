<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Form;

use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Group_Interface;
class Form
{
    /**
     * @var Group_Interface
     */
    private $items;
    /**
     * @param Group_Interface $items
     */
    public function __construct(Group_Interface $items)
    {
        $this->items = $items;
    }
    /**
     * @return Group_Interface
     */
    public function get_items() : Group_Interface
    {
        return $this->items;
    }
}
