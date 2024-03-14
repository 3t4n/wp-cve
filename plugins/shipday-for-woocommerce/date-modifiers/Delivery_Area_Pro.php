<?php

require_once dirname( __FILE__ ) . '/Date_Picker_Object.php';

class Delivery_Area_Pro extends Date_Picker_Object
{
    public function __construct($order_id) {
        $this->utc = new DateTimeZone('Utc');
        $this->set_delivery_time($order_id);
    }

    private function set_delivery_time($order_id) {
        if (metadata_exists('post', $order_id, 'delivery_timeslot') && get_post_meta( $order_id, 'delivery_timeslot', true )  != '') {
            $this->delivery_date_time = new DateTime();
            $this->delivery_date_time->setTimezone(wp_timezone());

            $timeslot                 = get_post_meta($order_id, 'delivery_timeslot', true);
            $times = explode('-', $timeslot);
            $hr_min = explode(':', $times[0]);
            $this->delivery_date_time->setTime(intval($hr_min[0]), intval($hr_min[1]));

            $this->delivery_date_time->setTimezone($this->utc);
            $this->delivery_time_flag = true;
        }

    }
}