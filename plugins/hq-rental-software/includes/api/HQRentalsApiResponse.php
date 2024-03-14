<?php

namespace HQRentalsPlugin\HQRentalsApi;

class HQRentalsApiResponse
{
    public function __construct($errors, $success, $data)
    {
        $this->success = $success;
        $this->errors = $errors;
        $this->data = $data;
    }
}
