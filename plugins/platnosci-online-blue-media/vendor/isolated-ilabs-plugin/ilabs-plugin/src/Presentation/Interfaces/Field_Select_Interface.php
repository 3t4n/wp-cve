<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces;

interface Field_Select_Interface
{
    public function get_value() : string;
    public function set_value(string $value);
    public function set_options(array $options);
    public function get_options() : array;
    public function get_default() : ?string;
    public function set_default(?string $default);
}
