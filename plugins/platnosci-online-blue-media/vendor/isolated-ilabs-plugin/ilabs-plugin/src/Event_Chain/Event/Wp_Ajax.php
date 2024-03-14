<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Event;

use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Abstracts\Abstract_Event;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Field_Ajax_Interface;
class Wp_Ajax extends Abstract_Event
{
    /**
     * @var Field_Ajax_Interface
     */
    private $field_ajax;
    public function __construct(Field_Ajax_Interface $field_ajax)
    {
        $this->field_ajax = $field_ajax;
    }
    public function create()
    {
        //var_dump("wp_ajax_{$this->field_ajax->get_payload()->get_id()}");die;
        add_action("wp_ajax_{$this->field_ajax->get_id()}", [$this, 'callback']);
    }
}
