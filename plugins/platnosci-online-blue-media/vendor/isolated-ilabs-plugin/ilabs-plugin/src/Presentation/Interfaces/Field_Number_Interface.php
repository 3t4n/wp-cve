<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces;

interface Field_Number_Interface
{
    /**
     * @return int
     */
    public function get_value() : int;
    /**
     * @param int $value
     *
     * @return mixed
     */
    public function set_value(?int $value);
    public function get_default() : ?int;
    public function set_default(?int $default);
}
