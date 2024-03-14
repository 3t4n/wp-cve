<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces;

interface Field_Interface
{
    public function get_label() : string;
    public function set_label(string $label);
    public function get_id() : string;
    public function set_id(string $label);
    public function get_desc() : ?string;
    public function set_desc(?string $desc);
}
