<?php
/**
 * Gator Notice
 *
 * An abstract of a notification notice.
 *
 * Copyright(c) Schuyler W Langdon
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class GatorNotice
{
    protected $id;
    protected $message;
    protected $priority;
    
    public function __construct($message, $id = null)
    {
        $this->message = $message;
        if (isset($id)) {
            $this->id = $id;
        }
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getCode()
    {
        return isset($this->id) ? $this->id : '0';//gotta love error code zero
    }
}
