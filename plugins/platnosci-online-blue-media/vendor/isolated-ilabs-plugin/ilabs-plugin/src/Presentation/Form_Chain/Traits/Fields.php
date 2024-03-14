<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Form_Chain\Traits;

use Exception;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Form\Fields\Basic_Ajax_Submit;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Form\Fields\Checkbox;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Form\Fields\Number;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Form\Fields\Text;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Form\Fields\Text_Area;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Form_Chain\Form_Builder;
trait Fields
{
    /**
     * @throws Exception
     */
    public function add_field_checkbox(string $id, string $label = '', string $desc = '', string $value = '', string $default = null) : Form_Builder
    {
        $field = new Checkbox();
        $field->set_desc($desc);
        $field->set_id($id);
        $field->set_label($label);
        $field->set_value($value);
        $field->set_default($default);
        $this->get_form_chain()->add_group_item($field);
        return $this->get_form_chain();
    }
    protected abstract function get_form_chain() : Form_Builder;
    /**
     * @throws Exception
     */
    public function add_field_number(string $id, string $label = '', string $desc = '', int $value = null, int $default = null) : Form_Builder
    {
        $field = new Number();
        $field->set_desc($desc);
        $field->set_id($id);
        $field->set_label($label);
        $field->set_value($value);
        $field->set_default($default);
        $this->get_form_chain()->add_group_item($field);
        return $this->get_form_chain();
    }
    /**
     * @throws Exception
     */
    public function add_field_text(string $id, string $label = '', string $desc = '', string $value = '', string $default = null, $is_password = \false) : Form_Builder
    {
        $field = new Text();
        $field->set_desc($desc);
        $field->set_id($id);
        $field->set_label($label);
        $field->set_value($value);
        $field->set_default($default);
        $field->set_is_password($is_password);
        $this->get_form_chain()->add_group_item($field);
        return $this->get_form_chain();
    }
    /**
     * @throws Exception
     */
    public function add_field_text_area(string $id, string $label = '', string $desc = '', string $value = '', string $default = null) : Form_Builder
    {
        $field = new Text_Area();
        $field->set_desc($desc);
        $field->set_id($id);
        $field->set_label($label);
        $field->set_value($value);
        $field->set_default($default);
        $this->get_form_chain()->add_group_item($field);
        return $this->get_form_chain();
    }
    /**
     * @throws Exception
     */
    public function add_ajax_integration(string $id, string $label = '', string $desc = '', string $payload_group_id = null) : Form_Builder
    {
        $payload_group_id = $payload_group_id ?: $this->get_form_chain()->get_current_group()->get_id();
        $field = new Basic_Ajax_Submit();
        $field->set_desc($desc);
        $field->set_id($id);
        $field->set_label($label);
        $field->set_payload_group_id($payload_group_id);
        $this->get_form_chain()->add_group_item($field);
        return $this->get_form_chain();
    }
}
