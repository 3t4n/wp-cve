<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Interfaces;

interface Action_Interface
{
    public function run();
    public function set_current_event(Event_Interface $event);
}
