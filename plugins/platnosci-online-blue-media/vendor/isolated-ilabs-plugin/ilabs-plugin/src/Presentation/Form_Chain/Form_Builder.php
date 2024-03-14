<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Form_Chain;

use Exception;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Abstract_Ilabs_Plugin;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Form\Fields\Group;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Form\Form;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Form_Chain\Traits\Fields;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Field_Interface;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Group_Interface;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Group_Item_Interface;
class Form_Builder
{
    use Fields;
    /**
     * @var array
     */
    private $groups;
    /**
     * @var Group_Interface
     */
    private $current_group;
    /**
     * @var int
     */
    private $current_opened_group_index = 0;
    /**
     * @var array
     */
    private $field_ids_cache = [];
    /**
     * @var Abstract_Ilabs_Plugin
     */
    private $ilabs_plugin;
    /**
     * @var Group_Interface[]
     */
    private $reference_to_parent = [];
    public function __construct(Abstract_Ilabs_Plugin $ilabs_plugin)
    {
        $this->ilabs_plugin = $ilabs_plugin;
    }
    /**
     * @throws Exception
     */
    public function begin_group(string $id, string $name = null, string $desc = null) : Form_Builder
    {
        if (empty($id)) {
            throw new Exception('Group id can not be empty!');
        }
        $group = $this->open_group($id, $name, $desc);
        if ($this->current_group !== null) {
            $this->reference_to_parent[] = $this->current_group;
        } else {
            $this->reference_to_parent[] = $group;
        }
        $this->current_group = $group;
        return $this;
    }
    private function filter_field_id(string $id) : string
    {
        if (\count($this->reference_to_parent) === 0) {
            return $id;
        }
        return $this->current_group->get_id() . '_' . $id;
    }
    /**
     * @throws Exception
     */
    private function filter_group_id(string $id) : string
    {
        $return = '';
        if (\count($this->reference_to_parent) === 0) {
            $return = sanitize_title($this->ilabs_plugin->get_plugin_prefix());
        }
        if (\count($this->reference_to_parent) === 1) {
            $parent_group_id = \end($this->reference_to_parent)->get_id();
            $return .= $parent_group_id;
        }
        return "{$return}_{$id}";
    }
    /**
     * @throws Exception
     */
    public function close_group() : Form_Builder
    {
        if (\count($this->reference_to_parent) === 1) {
            return $this;
        }
        $new_current_group = \end($this->reference_to_parent);
        $items = $new_current_group->get_items();
        $items[] = $this->current_group;
        $new_current_group->set_items($items);
        $this->current_group = $new_current_group;
        \array_pop($this->reference_to_parent);
        return $this;
    }
    public function build() : Form
    {
        return new Form($this->current_group);
    }
    /**
     * @throws Exception
     */
    private function extract_group_item_interface($object) : Group_Item_Interface
    {
        if ($object instanceof Group_Item_Interface) {
            return $object;
        } else {
            throw new Exception('Object must be instance of Group_Item_Interface');
        }
    }
    /**
     * @throws Exception
     */
    private function open_group(string $id, string $name = null, string $desc = null) : Group_Interface
    {
        $group = new Group();
        $group->set_id($this->filter_group_id($id));
        $group->set_name($name);
        $group->set_desc($desc);
        return $group;
    }
    protected function get_form_chain() : self
    {
        return $this;
    }
    /**
     * @throws Exception
     */
    protected function add_group_item(Group_Item_Interface $field, bool $force = \false)
    {
        $field->set_id($this->filter_field_id($field->get_id()));
        if (!$force) {
            $field_ids_cache = $this->field_ids_cache;
            if (\in_array($field->get_id(), $field_ids_cache)) {
                throw new Exception("Duplicated Field ID detected! ({$field->get_id()}). Field ID must be unique!");
            }
        }
        $items = $this->current_group->get_items();
        $items[] = $field;
        $this->current_group->set_items($items);
        $field_ids_cache[] = $field->get_id();
        $this->field_ids_cache = $field_ids_cache;
    }
    /**
     * @return Group_Interface
     */
    public function get_current_group() : Group_Interface
    {
        return $this->current_group;
    }
    /**
     * @return Field_Interface
     */
    public function get_current_field() : Field_Interface
    {
        return $this->current_group->get_items()[\count($this->current_group->get_items()) - 1];
    }
}
