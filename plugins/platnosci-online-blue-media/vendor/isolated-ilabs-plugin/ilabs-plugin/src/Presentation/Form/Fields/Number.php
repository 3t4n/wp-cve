<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Form\Fields;

use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Field_Interface;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Field_Number_Interface;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Group_Item_Interface;
class Number implements Group_Item_Interface, Field_Number_Interface, Field_Interface
{
    /**
     * @var int
     */
    private $value;
    /**
     * @var string
     */
    private $id;
    /**
     * @var string
     */
    private $label;
    /**
     * @var string
     */
    private $desc;
    /**
     * @var int
     */
    private $default;
    public function to_array() : array
    {
        return ['value' => $this->value, 'id' => $this->id, 'label' => $this->label, 'desc' => $this->desc];
    }
    /**
     * @return int
     */
    public function get_value() : int
    {
        return $this->value;
    }
    /**
     * @param int $value
     *
     * @return void
     */
    public function set_value(?int $value) : void
    {
        $this->value = $value;
    }
    public function set_test(int $test)
    {
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
    public function get_label() : string
    {
        return $this->label;
    }
    /**
     * @param string $label
     */
    public function set_label(string $label) : void
    {
        $this->label = $label;
    }
    /**
     * @return string
     */
    public function get_desc() : string
    {
        return $this->desc;
    }
    /**
     * @param string $desc
     */
    public function set_desc(?string $desc) : void
    {
        $this->desc = $desc;
    }
    /**
     * @return int
     */
    public function get_default() : int
    {
        return $this->default;
    }
    /**
     * @param int $default
     */
    public function set_default(?int $default) : void
    {
        $this->default = $default;
    }
}
