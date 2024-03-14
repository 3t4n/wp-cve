<?php

namespace WpLHLAdminUi\Helpers;

/**
 * Given a folder name
 * return th path to the folder in the uploads directory
 * provides a url path for it
 */
class DateTimeUtil {

    private $date_format = '';
    private $time_format = '';

    public function __construct()
    {
        $this->date_format = get_option('date_format');
        $this->time_format = get_option('time_format');
    }

    public function default_date_time(){

        $user_accepted_date = "";
        // Format dete to the website preferred format
        $user_accepted_date = wp_date("{$this->date_format} {$this->time_format}", time());

        return $user_accepted_date;
    }

    public function date_time_w_sec(){
        $the_time = wp_date("{$this->date_format} h:i:s A", time());
        return $the_time;
    }

}