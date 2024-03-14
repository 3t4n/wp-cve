<?php

/**
 * Model for links
 *
 * Class Wpil_Model_Link
 */
class Wpil_Model_Link
{
    public $link_id = 0; // the link's row index in report_links table
    public $url = '';
    public $host = '';
    public $internal = false;
    public $post = false;
    public $anchor = '';
    public $added_by_plugin = false;
    public $location = 'content';
    public $link_whisper_created = 0;
    public $is_autolink = 0;

    public function __construct($params = [])
    {
        //fill model properties from initial array
        foreach ($params as $key => $value) {
            if (isset($this->{$key})) {
                $this->{$key} = $value;
            }
        }
    }
}
