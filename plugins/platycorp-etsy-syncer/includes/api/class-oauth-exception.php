<?php
namespace platy\etsy\api;
use platy\etsy\EtsySyncerException;

class OAuthException extends EtsySyncerException {

    private $status_code;

    function __construct($error_msg, $status_code = 0){
        parent::__construct($error_msg);
        $this->status_code = $status_code;
    }

    public function get_status_code() {
        return $this->status_code;
    }
}