<?php

namespace Directorist_WPML_Integration\Helper;

class Response {
    /**
     * Success
     * 
     * @return bool
     */
    public $success = false;

    /**
     * Message
     * 
     * @return string
     */
    public $message = false;

    /**
     * Data
     * 
     * @return mixed
     */
    public $data = null;

    /**
     * Return The Class Properties As Array
     * 
     * @return array
     */
    public function toArray() {
        return [
            'success' => $this->success,
            'message' => $this->message,
            'data'    => $this->data,
        ];
    }

}