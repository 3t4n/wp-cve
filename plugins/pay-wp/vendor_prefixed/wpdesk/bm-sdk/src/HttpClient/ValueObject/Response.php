<?php

declare (strict_types=1);
namespace WPPayVendor\BlueMedia\HttpClient\ValueObject;

final class Response
{
    private $data;
    public function __construct($data)
    {
        $this->data = $data;
    }
    public function getData()
    {
        return $this->data;
    }
}
