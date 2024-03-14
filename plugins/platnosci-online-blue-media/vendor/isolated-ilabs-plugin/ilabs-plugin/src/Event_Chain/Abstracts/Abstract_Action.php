<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Abstracts;

use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Interfaces\Action_Interface;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Interfaces\Event_Interface;
abstract class Abstract_Action implements Action_Interface
{
    /**
     * @var Event_Interface
     */
    protected $current_event;
    /**
     * @var callable
     */
    protected $callable_arguments;
    /**
     * @return int
     * @depracated
     */
    public function get_post_id_from_event() : int
    {
        return 0;
    }
    public function run()
    {
        $callable = $this->callable_arguments;
        $callable($this->current_event);
    }
    public function set_current_event(Event_Interface $event)
    {
        $this->current_event = $event;
    }
    /**
     * @depracated
     * @return bool
     */
    protected function is_event_provide_post_id() : bool
    {
        return \false;
    }
}
