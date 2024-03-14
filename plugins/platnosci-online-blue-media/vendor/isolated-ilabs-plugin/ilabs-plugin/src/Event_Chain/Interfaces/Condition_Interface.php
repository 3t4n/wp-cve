<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Interfaces;

interface Condition_Interface
{
    public function assert() : bool;
    public function set_current_event(Event_Interface $event);
}
