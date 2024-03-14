<?php

namespace S2WPImporter\Traits;

trait AjaxTrait
{
    protected function error(string $message, array $data = []): void
    {
        echo wp_json_encode(wp_parse_args([
            'error' => true,
            'message' => $message,
        ], $data));

        die();
    }

    protected function success(string $message, array $data = []): void
    {
        echo wp_json_encode(wp_parse_args([
            'error' => false,
            'message' => $message,
        ], $data));

        die();
    }
}
