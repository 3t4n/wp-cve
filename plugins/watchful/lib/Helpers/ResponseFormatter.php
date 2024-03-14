<?php

namespace Watchful\Helpers;

use WP_REST_Response;

class ResponseFormatter {

    /**
     * @param $data
     * @return WP_REST_Response
     * @TODO: This method can be removed once most updates from 1.4.13 to 1.5.0+ are done.
     */
    public static function format($data) {
        return new WP_REST_Response(self::add_response_delimiters($data));
    }
    /**
     * @param $data
     * @return string
     */
    public static function add_response_delimiters($data) {
        if (is_array($data) || is_object($data)) {
            $data = json_encode($data);
        }
        return '{wcode}' . $data . '{|wcode}';
    }
}
