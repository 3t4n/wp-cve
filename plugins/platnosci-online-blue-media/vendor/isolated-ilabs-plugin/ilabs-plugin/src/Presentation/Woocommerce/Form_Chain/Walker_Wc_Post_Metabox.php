<?php

namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Woocommerce\Form_Chain;

use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Abstract_Ilabs_Plugin;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Form\Abstract_Group_Walker;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Field_Interface;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Group_Interface;
class Walker_Wc_Post_Metabox extends Abstract_Group_Walker
{
    /**
     * @var string
     */
    private $templates_directory;
    private function get_templates_directory() : string
    {
        return Abstract_Ilabs_Plugin::$initial_instance->get_framework_templates_dir() . \DIRECTORY_SEPARATOR . 'Woocommerce' . \DIRECTORY_SEPARATOR;
    }
    public function __construct(Group_Interface $group)
    {
        $this->templates_directory = $this->get_templates_directory();
        parent::__construct($group);
    }
    protected function begin_group_callback(Group_Interface $group)
    {
        $group_id = $group->get_id();
        $group_header = $group->get_name();
        include $this->templates_directory . 'Metabox_form_group_header.php';
    }
    protected function end_group_callback(Group_Interface $group)
    {
        include $this->templates_directory . 'Metabox_form_group_footer.php';
    }
    protected function group_field_callback(Field_Interface $field)
    {
        include $this->templates_directory . 'Metabox_form_froup_item.php';
    }
}
