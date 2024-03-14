<?php

namespace Memsource\Dto;

use Memsource\Utils\StringUtils;

class AbstractDto
{
    public function __construct(array $row)
    {
        foreach ($row as $key => $value) {
            $key = StringUtils::camelCase($key);
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}
