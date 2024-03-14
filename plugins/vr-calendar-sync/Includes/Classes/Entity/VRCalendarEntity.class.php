<?php
/**
 * Short description: Booking calendar created by Innate Images, LLC
 * PHP Version 8.0
 
 * @category  VRCalendarEntity_Class
 * @package   VRCalendarEntity_Class
 * @author    Innate Images, LLC <info@innateimagesllc.com>
 * @copyright 2015 Innate Images, LLC
 * @license   GPL-2.0+ http://www.vrcalendarsync.com
 * @link      http://www.vrcalendarsync.com
 */

 /**
  * Short description: Booking calendar created by Innate Images, LLC
  * VRCalendarEntity Class Doc Comment
  * 
  * VRCalendarEntity Class
  * 
  * @category  VRCalendarEntity_Class
  * @package   VRCalendarEntity_Class
  * @author    Innate Images, LLC <info@innateimagesllc.com>
  * @copyright 2015 Innate Images, LLC
  * @license   GPL-2.0+ http://www.vrcalendarsync.com
  * @link      http://www.vrcalendarsync.com
  */
class VRCalendarEntity extends VRCSingleton
{

    public $table_name;

    private $_calendar_id;

    /**
     * Define template file
     **/
    protected function __construct()
    {
        global $wpdb;
        $this->table_name = $wpdb->prefix.'vrcalandar';
    }

    /**
     * Create database table based on web instance
     * 
     * @return String
     */
    function createTable()
    {
        global $wpdb;

        $calendar_table_sql = "CREATE TABLE {$this->table_name} (
			calendar_id INT(11) NOT NULL AUTO_INCREMENT,
			calendar_name TEXT,
			calendar_links TEXT,
			calendar_layout_options TEXT,
			calendar_author_id INT(11),
			calendar_is_synchronizing ENUM('yes','no') DEFAULT 'no',
			calendar_last_synchronized DATETIME,
			calendar_created_on DATETIME,
			calendar_modified_on DATETIME,
			PRIMARY KEY  (calendar_id)
		);";
        dbDelta($calendar_table_sql);
    }

    /**
     * Get all calendar based on web instance
     * 
     * @return String
     */
    function getAllCalendar()
    {
        global $wpdb;
        $sql = "SELECT calendar_id FROM {$this->table_name}";
        $cals = $wpdb->get_results( $sql );
        $arr = array();
        foreach ($cals as $cal) {
            $arr[] = $this->getCalendar($cal->calendar_id);
        }
        return $arr;
    }

    /**
     * Save calendar based on web instance
     * 
     * @param array $data field data
     * 
     * @return String
     */
    function saveCalendar($data)
    {
        global $wpdb;
        $data['calendar_name'] = htmlentities($data['calendar_name'], ENT_QUOTES);
        $data['calendar_links'] = json_encode($data['calendar_links']);
        $data['calendar_layout_options'] = json_encode($data['calendar_layout_options']);
        $calendar_id = $data['calendar_id'];
        if ($data['calendar_id']>0) {
            $cal_data = $this->getCalendar($data['calendar_id']);

            if (!isset($data['calendar_is_synchronizing'])) {
                $data['calendar_is_synchronizing']=$cal_data->calendar_is_synchronizing;
            }
            if (!isset($data['calendar_last_synchronized'])) {
                $data['calendar_last_synchronized']=$cal_data->calendar_last_synchronized;
            }

            $sql = "UPDATE {$this->table_name} SET calendar_name='{$data['calendar_name']}', calendar_links='{$data['calendar_links']}', calendar_layout_options='{$data['calendar_layout_options']}', calendar_is_synchronizing = '{$data['calendar_is_synchronizing']}', calendar_last_synchronized='{$data['calendar_last_synchronized']}', calendar_modified_on='{$data['calendar_modified_on']}' WHERE calendar_id=%d;";
            $prepare = $data['calendar_id'];
        } else {
            $sql = "INSERT INTO {$this->table_name} (calendar_name, calendar_links, calendar_layout_options, calendar_author_id, calendar_created_on, calendar_modified_on) VALUES (%s, %s, %s, %d, %s, %s);";
            $prepare = array(
                $data['calendar_name'], 
                $data['calendar_links'],
                $data['calendar_layout_options'],
                $data['calendar_author_id'],
                $data['calendar_created_on'],
                $data['calendar_modified_on']
            );
        }
        $wpdb->query( 
            $wpdb->prepare(
                $sql, 
                $prepare
            )
        );
        if ($data['calendar_id']<=0) {
            $calendar_id = $wpdb->insert_id;
        }
        return true;
    }

    /**
     * Remove calendar based on web instance
     * 
     * @param int $calendar_id id
     * 
     * @return String
     */
    function deleteCalendar($calendar_id)
    {
        $VRCalendarBooking = VRCalendarBooking::getInstance();
        global $wpdb;
        $sql = "DELETE FROM {$this->table_name} WHERE calendar_id=%d";
        $wpdb->query(
            $wpdb->prepare(
                $sql,
                $calendar_id
                )
            );

        $VRCalendarBooking->removeCalendarBookings($calendar_id);
        return true;
    }

    /**
     * Get calendar based on web instance
     * 
     * @param int $calendar_id id
     * 
     * @return String
     */
    function getCalendar($calendar_id)
    {
        global $wpdb;
        $sql = "SELECT * FROM {$this->table_name} WHERE calendar_id=%d";
        $data = $wpdb->get_row($wpdb->prepare(
                $sql,
                $calendar_id
            )
        );
        if (isset($data->calendar_id)) {
            $data->calendar_name = html_entity_decode($data->calendar_name, ENT_QUOTES);
            $data->calendar_layout_options = json_decode($data->calendar_layout_options, true);
            $data->calendar_links = json_decode($data->calendar_links);
        }
        foreach ($data->calendar_links as $clink) {
            if (!is_object($clink)) {
                $temp = new stdClass();
                $temp->url = $clink;
                $temp->name = '';
                $clink = $temp;
            }
        }

        return $data;
    }

    /**
     * Get empty calendar based on web instance
     * 
     * @return String
     */
    function getEmptyCalendar()
    {
        $calendar = new stdClass();
        $calendar->calendar_id = '';
        $calendar->calendar_name = '';
        $link_obj = new stdClass();
        $link_obj->name='';
        $link_obj->url='';
        $calendar->calendar_links = array(
            $link_obj
        );

        $calendar->calendar_layout_options = array (
            'columns' => '3',
            'rows' => '4',
            'size' => 'small',
            'default_bg_color' => '#FFFFFF',
            'default_font_color' => '#000000',
            'calendar_border_color' => '#CCCCCC',
            'week_header_bg_color' => '#F1F0F0',
            'week_header_font_color' => '#000000',
            'available_bg_color' => '#DDFFCC',
            'available_font_color' => '#000000',
            'unavailable_bg_color' => '#FFC0BD',
            'unavailable_font_color' => '#000000',
        );

        return $calendar;
    }

    /**
     * Get synchronize calendar based on web instance
     * 
     * @param int $calendar_id id
     * 
     * @return String
     */
    function synchronizeCalendar($calendar_id)
    {
        $VRCalendarBooking = VRCalendarBooking::getInstance();
        $cal = $this->getCalendar($calendar_id);
        $calData =  json_decode(json_encode($cal), true);
        $calData['calendar_is_synchronizing'] = 'yes';
        $this->saveCalendar($calData);
        $calLinks = $cal->calendar_links;

        /* First remove all bookings except local bookings */
        $VRCalendarBooking->removeBookingsExceptLocal($cal->calendar_id);
        foreach ($calLinks as $calLink) {
            $ical   = new VRCICS_ICal\ICal($calLink->url);
            $events = $ical->events();


            foreach ($events as $event) {
                $booking_data = array(
                    'booking_calendar_id'=>$cal->calendar_id,
                    'booking_source'=>$calLink->url,
                    'booking_date_from'=>date('Y-m-d H:i:s', $ical->iCalDateToUnixTimestamp($event->dtstart)),
                    'booking_date_to'=>date('Y-m-d H:i:s', $ical->iCalDateToUnixTimestamp($event->dtend)),
                    'booking_guests'=>'',
                    'booking_user_fname'=>'',
                    'booking_user_lname'=>'',
                    'booking_user_email'=>'',
                    'booking_summary'=>$event->summary,
                    'booking_status'=>'confirmed',
                    'booking_payment_status'=>'confirmed',
                    'booking_admin_approved'=>'yes',
                    'booking_payment_data'=>'',
                    'booking_sub_price'=>array(),
                    'booking_total_price'=>0,
                    'booking_created_on'=>date('Y-m-d H:i:s'),
                    'booking_modified_on'=>date('Y-m-d H:i:s'),
                );
                $VRCalendarBooking->saveBooking($booking_data);
            }
        }
        $calData['calendar_is_synchronizing'] = 'no';
        $calData['calendar_last_synchronized'] = date('Y-m-d H:i:s');
        $this->saveCalendar($calData);
    }

    /**
     * Get available price variation based on web instance
     * 
     * @param int    $cal_id         id
     * @param string $check_in_date  check in date
     * @param string $check_out_date check out date
     * 
     * @return String
     */
    function getAvailablePriceVariations($cal_id, $check_in_date, $check_out_date)
    {
        global $wpdb;
        /* Check if a price variation is available in b/w these dates */
        $sql = "SELECT * FROM {$this->price_variation_table_name} WHERE calendar_id=%d AND(
        DATE(variation_start_date) BETWEEN DATE(%s) AND DATE(%s) OR
        DATE(variation_end_date) BETWEEN DATE(%s) AND DATE(%s) OR
        DATE(%s) BETWEEN DATE(variation_start_date) AND DATE(variation_end_date) )";

        $price_variations = $wpdb->get_results(
            $wpdb->prepare(
                $sql,
                array(
                    $cal_id,
                    $check_in_date,
                    $check_out_date,
                    $check_in_date,
                    $check_out_date,
                    $check_in_date
                )
            )
        );
        return $price_variations;
    }

    /**
     * Get calculate nights based on web instance
     * 
     * @param string $check_in_date  check in date
     * @param string $check_out_date check out date
     * 
     * @return String
     */
    function calculateNights($check_in_date, $check_out_date)
    {
        return ceil(abs(strtotime($check_out_date) - strtotime($check_in_date)) / 86400);
    }

    /**
     * Get price per nights based on web instance
     * 
     * @param array  $cal_data         calendar data
     * @param string $check_in_date    check in date
     * @param string $check_out_date   check out date
     * @param array  $price_variations price variations
     * 
     * @return String
     */
    function getPricePerNight($cal_data, $check_in_date, $check_out_date, $price_variations)
    {

        if (count($price_variations)<=0) {
            return $cal_data->calendar_price_per_night;
        }

        /* We have some variations */
        $dates = array();
        for ($date = $check_in_date; $date<$check_out_date; $date=date('Y-m-d', strtotime("+1 day", strtotime($date))) ) {
            $dates[$date] = $cal_data->calendar_price_per_night;
        }
        /* Now update this array to get price from a variation */
        foreach ($dates as $date=>$price) {
            //$price = $cal_data->calendar_price_per_night;
            foreach ($price_variations as $variation) {
                if (strtotime($variation->variation_start_date) <= strtotime($date) &&  strtotime($variation->variation_end_date) >= strtotime($date) ) {
                    $price = $variation->variation_price_per_night;
                    break;
                }
            }
            $dates[$date] = $price;
        }
        return number_format((float)(array_sum($dates)/count($dates)), 2, '.', '');
    }

    /**
     * Get booking base price based on web instance
     * 
     * @param array  $cal_data         calendar data
     * @param string $check_in_date    check in date
     * @param string $check_out_date   check out date
     * @param array  $price_variations price variations
     * 
     * @return String
     */
    function getBaseBookingPrice($cal_data, $check_in_date, $check_out_date, $price_variations)
    {
        $booking_days = $this->calculateNights($check_in_date, $check_out_date);
        $price_per_day = $this->getPricePerNight($cal_data, $check_in_date, $check_out_date, $price_variations);
        return $booking_days*$price_per_day;

    }

    /**
     * Get booking price based on web instance
     * 
     * @param array  $cal_id         id
     * @param string $check_in_date  check in date
     * @param string $check_out_date check out date
     * 
     * @return String
     */
    function getBookingPrice($cal_id, $check_in_date, $check_out_date)
    {
        global $wp_db;
        $cal_data = $this->getCalendar($cal_id);

        $price_variations = $this->getAvailablePriceVariations($cal_id, $check_in_date, $check_out_date);

        $booking_days = $this->calculateNights($check_in_date, $check_out_date);

        $price_per_night = $this->getPricePerNight($cal_data, $check_in_date, $check_out_date, $price_variations);

        $base_booking_price = $booking_days*$price_per_night;

        $cleaning_fee = $cal_data->calendar_cfee_per_stay;
        $booking_price_without_taxes = $base_booking_price+$cleaning_fee;

        $tax = $cal_data->calendar_tax_per_stay;
        $tax_type = $cal_data->calendar_tax_type;
        $tax_amt = $tax;

        if ($tax_type == 'percentage') {
            $tax_amt = ($base_booking_price*$tax)/100;
        }

        $booking_price_with_taxes =  $booking_price_without_taxes+$tax_amt;

        return array(
            'booking_days'=>$booking_days,
            'price_per_night'=>$price_per_night,
            'base_booking_price'=>$base_booking_price,
            'cleaning_fee'=>$cleaning_fee,
            'booking_price_without_taxes'=>$booking_price_without_taxes,
            'tax'=>$tax,
            'tax_type'=>$tax_type,
            'tax_amt'=>$tax_amt,
            'booking_price_with_taxes'=>$booking_price_with_taxes
        );

    }

}
