<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces;

interface Field_Checkbox_Interface
{
    public function get_value() : string;
    public function set_value(string $values);
    public function get_default() : ?string;
    public function set_default(?string $default);
}
