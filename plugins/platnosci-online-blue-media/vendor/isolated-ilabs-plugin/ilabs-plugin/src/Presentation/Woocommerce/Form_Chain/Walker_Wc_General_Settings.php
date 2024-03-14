<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Woocommerce\Form_Chain;

use Exception;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Form\Abstract_Group_Walker;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Field_Ajax_Interface;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Field_Checkbox_Interface;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Field_Interface;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Field_Number_Interface;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Field_Select_Interface;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Field_Text_Area_Interface;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Field_Text_Interface;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Group_Interface;
class Walker_Wc_General_Settings extends Abstract_Group_Walker
{
    /*
     * array
     */
    private $result = [];
    public function __construct(Group_Interface $group)
    {
        parent::__construct($group);
    }
    protected function begin_group_callback(Group_Interface &$group)
    {
        if (null !== $group->get_name()) {
            $this->push_field(['title' => $group->get_name(), 'desc' => $group->get_desc(), 'type' => 'title', 'id' => $group->get_id()]);
        }
    }
    protected function end_group_callback(Group_Interface &$group)
    {
        if (null !== $group->get_name()) {
            $this->push_field(['id' => $group->get_id(), 'type' => 'sectionend']);
        }
    }
    private function push_field(array $field)
    {
        $fields = $this->result;
        $fields[] = $field;
        $this->result = $fields;
    }
    /**
     * @throws Exception
     */
    protected function group_field_callback(Field_Interface &$field)
    {
        $label = $field->get_label() ?: $field->get_id();
        switch ($field) {
            case $field instanceof Field_Text_Interface:
                $field_arr = ['title' => $label, 'id' => $field->get_id(), 'type' => $field->is_password() ? 'password' : 'text', 'description' => $field->get_desc(), 'default' => $field->get_default()];
                $this->push_field($field_arr);
                break;
            case $field instanceof Field_Text_Area_Interface:
                $field_arr = ['title' => $label, 'id' => $field->get_id(), 'type' => 'textarea', 'description' => $field->get_desc(), 'default' => $field->get_default()];
                $this->push_field($field_arr);
                break;
            case $field instanceof Field_Checkbox_Interface:
                $field_arr = ['title' => $label, 'desc' => $field->get_desc(), 'id' => $field->get_id(), 'type' => 'checkbox', 'default' => $field->get_default()];
                $this->push_field($field_arr);
                break;
            case $field instanceof Field_Select_Interface:
                $field_arr = ['title' => $label, 'id' => $field->get_id(), 'type' => 'text', 'description' => $field->get_desc(), 'default' => $field->get_default(), 'options' => $field->get_options()];
                $this->push_field($field_arr);
                break;
            case $field instanceof Field_Number_Interface:
                $field_arr = ['title' => $label, 'id' => $field->get_id(), 'type' => 'number', 'description' => $field->get_desc(), 'default' => $field->get_default()];
                $this->push_field($field_arr);
                break;
            case $field instanceof Field_Ajax_Interface:
                $this->push_field(Wc_General_Settings_Child::integrate_simple_ajax($field));
                break;
        }
    }
    /**
     * @return array
     */
    public function get_result() : array
    {
        return $this->result;
    }
}
