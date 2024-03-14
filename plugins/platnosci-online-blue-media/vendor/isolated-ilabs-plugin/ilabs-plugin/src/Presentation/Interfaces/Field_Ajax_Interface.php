<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces;

interface Field_Ajax_Interface
{
    public function set_payload_group_id(string $payload_group_id) : void;
    public function get_payload_group_id() : string;
    public function get_label() : string;
    public function set_label(string $label);
    public function get_id() : string;
    public function set_id(string $label);
    public function get_desc() : ?string;
    public function set_desc(?string $desc);
}
