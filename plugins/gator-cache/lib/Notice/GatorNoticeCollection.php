<?php
/**
 * Gator Notice Collection
 *
 * A collection class for Gator Notices.
 *
 * Copyright(c) Schuyler W Langdon
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class GatorNoticeCollection
{
    protected $data = array();
    
    public function __construct(array $notices = null)
    {
        if (isset($notices)) {
            $this->data = $notices;
        }
    }

    public function get()
    {
        return empty($this->data) ? false : end($this->data);
    }

    public function add($message, $code = null)
    {
        $this->data[$code] = new GatorNotice($message, $code);
    }

    public function has($priority = null)
    {
        return !empty($this->data);//or array filter by priority
    }

    public function all()
    {
        return $this->data;
    }

    public function dismissAll()
    {
        $this->data = array();
    }
}
