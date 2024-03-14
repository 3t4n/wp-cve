<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Form;

use Exception;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Field_Interface;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Group_Interface;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Request;
class Request_Group_Extractor extends Abstract_Group_Walker
{
    /**
     * @var Group_Interface
     */
    private $result;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var bool
     */
    private $success = \false;
    public function __construct(Group_Interface $group, Request $request)
    {
        parent::__construct($group);
        $this->request = $request;
    }
    protected function begin_group_callback(Group_Interface &$group)
    {
    }
    protected function end_group_callback(Group_Interface &$group)
    {
    }
    /**
     * @throws Exception
     */
    public function extract() : ?Group_Interface
    {
        $this->walk();
        return $this->get_result();
    }
    /**
     * @throws Exception
     */
    protected function group_field_callback(Field_Interface &$field)
    {
        if (\method_exists($field, 'set_value')) {
            if ($this->request->key_exsists($field->get_id())) {
                $field->set_value($this->request->get_by_key($field->get_id()));
                $this->success = \true;
            } else {
                $this->success = \false;
            }
        }
    }
    /**
     * @return Group_Interface | null
     */
    public function get_result() : ?Group_Interface
    {
        return $this->success ? $this->group : null;
    }
}
