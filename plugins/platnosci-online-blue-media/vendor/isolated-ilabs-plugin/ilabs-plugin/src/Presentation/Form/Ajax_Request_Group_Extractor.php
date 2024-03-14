<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Form;

use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Field_Ajax_Interface;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Group_Interface;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Request;
class Ajax_Request_Group_Extractor extends Request_Group_Extractor
{
    /**
     * @var Field_Ajax_Interface
     */
    private $field_ajax;
    /**
     * @var Group_Interface
     */
    private $result;
    public function __construct(Form $form, Field_Ajax_Interface $field_ajax)
    {
        $this->field_ajax = $field_ajax;
        parent::__construct($form->get_items(), new Request());
    }
    public function extract() : ?Group_Interface
    {
        $this->walk();
        return $this->get_result();
    }
    protected function end_group_callback(Group_Interface &$group)
    {
        if ($group->get_id() === $this->field_ajax->get_payload_group_id()) {
            $this->result = $group;
        }
    }
    /**
     * @return Group_Interface
     */
    public function get_result() : ?Group_Interface
    {
        return $this->result;
    }
}
