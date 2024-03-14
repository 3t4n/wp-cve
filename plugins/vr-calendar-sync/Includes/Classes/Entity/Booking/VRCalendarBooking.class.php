<?php
/**
 * Short description: Booking calendar created by Innate Images, LLC
 * PHP Version 8.0
 
 * @category  VRCalendarBooking_Class
 * @package   VRCalendarBooking_Class
 * @author    Innate Images, LLC <info@innateimagesllc.com>
 * @copyright 2015 Innate Images, LLC
 * @license   GPL-2.0+ http://www.vrcalendarsync.com
 * @link      http://www.vrcalendarsync.com
 */

 /**
  * Short description: Booking calendar created by Innate Images, LLC
  * VRCalendarBooking Class Doc Comment
  * 
  * VRCalendarBooking Class
  * 
  * @category  VRCalendarBooking_Class
  * @package   VRCalendarBooking_Class
  * @author    Innate Images, LLC <info@innateimagesllc.com>
  * @copyright 2015 Innate Images, LLC
  * @license   GPL-2.0+ http://www.vrcalendarsync.com
  * @link      http://www.vrcalendarsync.com
  */
class VRCalendarBooking extends VRCSingleton
{

    public $table_name;

    private $_calendar_id;

    /**
     * Define template file
     **/
    protected function __construct()
    {
        global $wpdb;
        $this->table_name = $wpdb->prefix.'vrcalandar_bookings';
    }

    /**
     * Create table based on web instance
     * 
     * @return String
     */
    function createTable()
    {
        global $wpdb;

        $calendar_table_sql = "CREATE TABLE {$this->table_name} (
            booking_id INT(11) NOT NULL AUTO_INCREMENT,
			booking_calendar_id INT(11),
			booking_source TEXT,
			booking_date_from DATETIME,
			booking_date_to DATETIME,
            booking_guests INT(11),
            booking_user_fname TEXT,
            booking_user_lname TEXT,
            booking_user_email TEXT,
            booking_summary TEXT,
            booking_status ENUM('pending','confirmed'),
            booking_payment_status ENUM('pending','confirmed','not_required'),
            booking_admin_approved ENUM('yes','no'),
            booking_payment_data TEXT,
            booking_sub_price TEXT,
            booking_total_price TEXT,
            booking_created_on DATETIME,
            booking_modified_on DATETIME,
			PRIMARY KEY  (booking_id)
		);";
        dbDelta($calendar_table_sql);
    }

    /**
     * Save booking based on web instance
     * 
     * @param array $data booking data
     * 
     * @return String
     */
    function saveBooking($data)
    {
        global $wpdb;
        $data['booking_sub_price'] = json_encode($data['booking_sub_price']);
        $data['booking_payment_data'] = json_encode($data['booking_payment_data']);

        $data['booking_summary'] = htmlentities($data['booking_summary'], ENT_QUOTES);

        if (@$data['booking_id']>0) {
            $sql = "UPDATE {$this->table_name} SET booking_calendar_id='{$data['booking_calendar_id']}', booking_source='{$data['booking_source']}', booking_date_from='{$data['booking_date_from']}', booking_date_to='{$data['booking_date_to']}', booking_guests='{$data['booking_guests']}', booking_user_fname='{$data['booking_user_fname']}', booking_user_lname='{$data['booking_user_lname']}', booking_user_email='{$data['booking_user_email']}', booking_summary='{$data['booking_summary']}', booking_status='{$data['booking_status']}', booking_payment_status='{$data['booking_payment_status']}', booking_admin_approved='{$data['booking_admin_approved']}', booking_payment_data='{$data['booking_payment_data']}', booking_sub_price='{$data['booking_sub_price']}', booking_total_price='{$data['booking_total_price']}', booking_modified_on='{$data['booking_modified_on']}' WHERE booking_id=%d;";

            $prepare = $data['booking_id'];
        } else {
            $sql = "INSERT INTO {$this->table_name} (booking_calendar_id, booking_source, booking_date_from, booking_date_to, booking_guests, booking_user_fname, booking_user_lname, booking_user_email, booking_summary, booking_status, booking_payment_status, booking_admin_approved, booking_payment_data, booking_sub_price, booking_total_price, booking_created_on, booking_modified_on) VALUES (%d, %s, %s, %s, %d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s);";

            $prepare = array(
                $data['booking_calendar_id'],
                $data['booking_source'],
                $data['booking_date_from'],
                $data['booking_date_to'],
                $data['booking_guests'],
                $data['booking_user_fname'],
                $data['booking_user_lname'],
                $data['booking_user_email'],
                $data['booking_summary'],
                $data['booking_status'],
                $data['booking_payment_status'],
                $data['booking_admin_approved'],
                $data['booking_payment_data'],
                $data['booking_sub_price'],
                $data['booking_total_price'],
                $data['booking_created_on'],
                $data['booking_modified_on']
            );
        }
        $wpdb->query(
            $wpdb->prepare(
                $sql,
                $prepare
            )
        );

        if (@$data['booking_id']<=0 || !isset($data['booking_id'])) {
            return $wpdb->insert_id;
        }

        return true;
    }

    /**
     * Remove booking based on web instance
     * 
     * @param int $booking_id id
     * 
     * @return String
     */
    function deleteBooking($booking_id)
    {
        global $wpdb;
        $sql = "DELETE FROM {$this->table_name} WHERE booking_id=%d";
        $wpdb->query(
            $wpdb->prepare(
                    $sql,
                    $booking_id
                )
            );
        return true;
    }

    /**
     * Get booking id based on web instance
     * 
     * @param int $booking_id id
     * 
     * @return String
     */
    function getBookingByID($booking_id)
    {
        global $wpdb;
        $sql = "SELECT * FROM {$this->table_name} WHERE booking_id=%d";
        $data = $wpdb->get_row(
            $wpdb->prepare(
                    $sql,
                    $booking_id
                )
            );
        $data->booking_sub_price = json_decode($data->booking_sub_price, true);
        $data->booking_payment_data = json_decode($data->booking_payment_data, true);
        $data->booking_summary = html_entity_decode($data->booking_summary, ENT_QUOTES);
        return $data;
    }

    /**
     * Get bookings based on web instance
     * 
     * @param int $calendar_id id
     * 
     * @return String
     */
    function getBookings($calendar_id)
    {
        global $wpdb;
        $sql = "SELECT booking_id FROM {$this->table_name} WHERE booking_calendar_id=%d";
        $data = $wpdb->get_results(
            $wpdb->prepare(
                $sql,
                $calendar_id
            )
        );

        $arr = array();

        foreach ($data as $tdata) {
            $arr[] = $this->getBookingByID($tdata->booking_id);
        }
        return $arr;
    }

    /**
     * Remove booking by its source based on web instance
     * 
     * @param int    $calendar_id id
     * @param string $source      source
     * 
     * @return String
     */
    function removeBookingsBySource($calendar_id, $source)
    {
        global $wpdb;
        $sql = "DELETE FROM {$this->table_name} WHERE booking_calendar_id=%d AND booking_source=%s";
        $wpdb->query( 
            $wpdb->prepare(
                $sql,
                array(
                    $calendar_id,
                    $source
                )
            ) 
        );

        return true;
    }

    /**
     * Remove bookings except local data based on web instance
     * 
     * @param int $calendar_id id
     * 
     * @return String
     */
    function removeBookingsExceptLocal($calendar_id)
    {
        global $wpdb;
        $sql = "DELETE FROM {$this->table_name} WHERE booking_calendar_id=%d AND booking_source != %s";
        $wpdb->query( $wpdb->prepare(
            $sql,
            array(
                $calendar_id,
                'website'
            )
            ) 
        );
        return true;
    }

    /**
     * Remove calendar bookings based on web instance
     * 
     * @param int $calendar_id id
     * 
     * @return String
     */
    function removeCalendarBookings($calendar_id)
    {
        global $wpdb;
        $sql = "DELETE FROM {$this->table_name} WHERE booking_calendar_id=%d";
        $wpdb->query(
            $wpdb->prepare(
                $sql,
                $calendar_id
            ) 
        );
        return true;
    }

    /**
     * Get available until based on web instance
     * 
     * @param int    $cal_id     id
     * @param string $start_date date from
     * 
     * @return String
     */
    function availableTill( $cal_id, $start_date )
    {
        $sql = "SELECT DATE(booking_date_from) FROM {$this->table_name} WHERE booking_calendar_id=%d AND DATE(booking_date_from) > DATE(%s) ORDER BY booking_date_from ASC LIMIT 1";
        global $wpdb;
        $booking_range_ends = $wpdb->get_var(
            $wpdb->prepare(
                $sql,
                array(
                    $cal_id,
                    $start_date
                )
            )
        );
        /* Dec 1 date */
        if (!empty($booking_range_ends)) {
            //$booking_range_ends = date('Y-m-d', strtotime("-1 day", strtotime($booking_range_ends)) );
            $booking_range_ends = date('Y-m-d', strtotime($booking_range_ends));
        }
        return $booking_range_ends;
    }

    /**
     * Get booked dates based on web instance
     * 
     * @param array $cal_data calendar data
     * 
     * @return String
     */
    function getBookedDates( $cal_data )
    {
        global $wpdb;

        /* loop from first of this month to 36 months */
        $unavailable_dates = array();
        for ($i=0; $i<36; $i++) {
            $next_date = date('Y-m-d', strtotime("+{$i} months"));
            $month = date('n', strtotime($next_date));
            $year =  date('Y', strtotime($next_date));
            $days_in_month = date('t', mktime(0, 0, 0, $month, 1, $year));

            for ($list_day = 1; $list_day <= $days_in_month; $list_day++) {
                $cDate = date('Y-m-d', mktime(0, 0, 0, $month, $list_day, $year));
                if (!$this->isDateAvailable($cal_data, $cDate)) {
                    $unavailable_dates[] = $cDate;
                }
            }
        }
        return $unavailable_dates;
    }

    /**
     * Get date range availability based on web instance
     * 
     * @param array  $cal_data   calendar data
     * @param string $start_date date from
     * @param string $end_date   date until
     * 
     * @return String
     */
    function isDateRangeAvailable($cal_data, $start_date, $end_date)
    {
        global $wpdb;
        $cdate = $start_date;
        /* check if start date is available */
        if ($this->isStartEndDate($cal_data, $start_date)) {
            return false;
        } else if ($this->isStartDate($cal_data, $start_date)) {
            return false;
        } else if (!$this->isDateAvailable($cal_data, $start_date)) {
            if (!$this->isEndDate($cal_data, $start_date)) {
                return false;
            }
        }
        while (strtotime($cdate)<=strtotime($end_date)) {
            if (!$this->isDateAvailable($cal_data, $cdate)) {
                return false;
            }
            $cdate = date('Y-m-d', strtotime("$cdate + 1 day"));
        }
        return true;
    }

    /**
     * Check date available based on web instance
     * 
     * @param array  $cal_data calendar data
     * @param string $date     date from
     * 
     * @return String
     */
    function isDateAvailable($cal_data, $date)
    {
        global $wpdb;
        $sql = "SELECT COUNT(booking_id) FROM {$this->table_name} WHERE booking_calendar_id=%d AND ( DATE(booking_date_from) BETWEEN DATE(%s) AND DATE(%s) OR DATE(booking_date_to) BETWEEN DATE(%s) AND DATE(%s) OR DATE(%s) BETWEEN DATE(booking_date_from) AND DATE(booking_date_to) )";
        $booking_count = $wpdb->get_var(
            $wpdb->prepare(
                    $sql,
                    array(
                        $cal_data->calendar_id,
                        $date,
                        $date,
                        $date,
                        $date,
                        $date
                    )
                )
            );
        if ($booking_count>0) {
            if ($this->isStartEndDate($cal_data, $date)) {
                return false;
            }
            if (!$this->isEndDate($cal_data, $date)) {
                return false;
            }
        }

        /* Dates in past are also not allowed */
        $current_data = date('Y-m-d');
        if (strtotime($date)<strtotime($current_data)) {
            return false;
        }

        return true;
    }

    /**
     * Check start and end date based on web instance
     * 
     * @param array  $cal_data calendar data
     * @param string $date     date start
     * 
     * @return String
     */
    function isStartEndDate( $cal_data, $date )
    {
        if ($this->isStartDate($cal_data, $date) && $this->isEndDate($cal_data, $date)) {
            return true;
        }

        return false;
    }

    /**
     * Check if start date based on web instance
     * 
     * @param array  $cal_data calendar data
     * @param string $date     default settings
     * 
     * @return String
     */
    function isStartDate( $cal_data, $date )
    {
        global $wpdb;
        $sql = "SELECT COUNT(booking_id) FROM {$this->table_name} WHERE booking_calendar_id=%d AND ( DATE(booking_date_from) = DATE(%s) )";
        $booking_count = $wpdb->get_var(
            $wpdb->prepare(
                $sql,
                array(
                    $cal_data->calendar_id,
                    $date
                )
            )
        );
        if ($booking_count>0) {
            return true;
        }

        return false;
    }

    /**
     * Check if end date based on web instance
     * 
     * @param array  $cal_data calendar data
     * @param string $date     end date
     * 
     * @return String
     */
    function isEndDate($cal_data, $date )
    {
        global $wpdb;
        $sql = "SELECT COUNT(booking_id) FROM {$this->table_name} WHERE booking_calendar_id=%d AND ( date(booking_date_to) = date(%s) )";
        $booking_count = $wpdb->get_var(
            $wpdb->prepare(
                $sql,
                array(
                    $cal_data->calendar_id,
                    $date
                )
            )
        );
        if ($booking_count>0) {
            return true;
        }

        return false;
    }
}
