<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Form\Fields;

use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Field_Interface;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Field_Text_Interface;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Group_Item_Interface;
class Text implements Group_Item_Interface, Field_Interface, Field_Text_Interface
{
    /**
     * @var string
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
     * @var string
     */
    private $default;
    /**
     * @var bool
     */
    private $is_password;
    public function to_array() : array
    {
        return ['value' => $this->value, 'id' => $this->id, 'label' => $this->label, 'desc' => $this->desc];
    }
    /**
     * @return string
     */
    public function get_value() : string
    {
        return $this->value;
    }
    /**
     * @param string $value
     */
    public function set_value(string $value) : void
    {
        $this->value = $value;
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
     * @return string
     */
    public function get_default() : ?string
    {
        return $this->default;
    }
    /**
     * @param string|null $default
     */
    public function set_default(?string $default) : void
    {
        $this->default = $default;
    }
    public function is_password() : bool
    {
        return $this->is_password;
    }
    public function set_is_password(?bool $is_password)
    {
        $this->is_password = $is_password;
    }
}
