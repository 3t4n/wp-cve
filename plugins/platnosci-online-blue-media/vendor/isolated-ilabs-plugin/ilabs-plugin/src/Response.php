<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin;

class Response
{
    const STATUS_OK = 'ok';
    const STATUS_ERROR = 'error';
    /**
     * @var string
     */
    private $status;
    /**
     * @var string
     */
    private $message;
    /**
     * @param string $status
     * @param string $message
     */
    public function __construct(string $status, string $message)
    {
        $this->status = $status;
        $this->message = $message;
    }
    public function send_end_exit() : void
    {
        echo wp_json_encode(['status' => $this->status, 'message' => $this->message]);
        exit;
    }
    /**
     * @return string
     */
    public function get_status() : string
    {
        return $this->status;
    }
    /**
     * @return string
     */
    public function get_message() : string
    {
        return $this->message;
    }
}
