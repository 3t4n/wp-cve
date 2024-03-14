<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin;

class Security_Request_Filter implements Request_Filter_Interface
{
    /**
     * @param $key
     * @param $value
     *
     * @return array|string
     */
    public function filter($key, $value)
    {
        if (!\is_array($value)) {
            return sanitize_text_field($value);
        }
        return $value;
    }
}
