<?php

namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Interfaces;

interface Filterable_String_Interface
{
    public function filter(string $value);
    public function get_filterable_value() : string;
}
