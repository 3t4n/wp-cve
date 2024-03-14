<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces;

interface Field_Text_Area_Interface
{
    public function get_value() : string;
    public function set_value(string $value);
    public function get_default() : ?string;
    public function set_default(?string $default);
}
