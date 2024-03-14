<?php
namespace platy\etsy\api;

class RequestException extends \RuntimeException {

    private $status_code;

    function __construct($error, $status_code = 0) {
        parent::__construct($error);
        $this->status_code = $status_code;
    }

    public function get_status_code() {
        return $this->status_code;
    }
}