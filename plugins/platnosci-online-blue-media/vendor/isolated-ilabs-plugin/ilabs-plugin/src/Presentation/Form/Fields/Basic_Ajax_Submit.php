<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Form\Fields;

use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Field_Ajax_Interface;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Field_Interface;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Group_Interface;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Group_Item_Interface;
class Basic_Ajax_Submit implements Field_Ajax_Interface, Group_Item_Interface, Field_Interface
{
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
    private $action;
    /**
     * @var string
     */
    private $payload_group_id;
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
     * @param string|null $desc
     */
    public function set_desc(?string $desc) : void
    {
        $this->desc = $desc;
    }
    public function to_array() : array
    {
        // TODO: Implement to_array() method.
    }
    /**
     * @return string
     */
    public function get_payload_group_id() : string
    {
        return $this->payload_group_id;
    }
    public function set_payload_group_id(string $payload_group_id) : void
    {
        $this->payload_group_id = $payload_group_id;
    }
}
