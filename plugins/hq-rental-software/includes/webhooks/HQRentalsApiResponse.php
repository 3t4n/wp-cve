<?php

namespace HQRentalsPlugin\HQRentalsWebhooks;

class HQRentalsApiResponse
{
    protected $status;
    protected $data;
    protected $errors;

    public static function resolveSuccess($data): array
    {
        return [
            'status' => 200,
            'data' => $data,
            'errors' => null
        ];
    }

    public static function resolveError($errors): array
    {
        return [
            'status' => 500,
            'data' => null,
            'errors' => $errors
        ];
    }
}
