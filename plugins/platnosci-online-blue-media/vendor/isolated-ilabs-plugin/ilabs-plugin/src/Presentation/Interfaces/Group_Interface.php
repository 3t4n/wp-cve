<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces;

interface Group_Interface
{
    public function get_id() : string;
    public function set_id(string $id);
    public function get_name() : ?string;
    public function set_name(?string $name);
    public function get_desc() : ?string;
    public function set_desc(?string $name);
    /**
     * @return Field_Interface[]
     */
    public function get_items() : ?array;
    /**
     * @param Group_Item_Interface[] $fields
     *
     * @return mixed
     */
    public function set_items(array $fields);
}
