<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AWC_Due_Date_Validator
{
    const ADD_ONE_DAYS = '+ 1 days';

    const ADD_TWO_DAYS = '+ 2 days';

    const ADD_THREE_DAYS = '+ 3 days';

    public static function awc_get_default_due_days()
    {
        return AWC_DUE_DAYS;
    }

    public static function awc_verify_due_days( $due_days )
    {
        if ( $due_days == self::awc_get_default_due_days() ) {
            return sprintf( "+ %d days", self::awc_get_default_due_days() );
        }

        return sprintf( "+ %d days", $due_days );
    }

    public static function awc_adjust_business_days_from_due_date( $due_date )
    {
        $holidays = self::awc_get_holidays();

        $dates = array_column( $holidays, 'date' );

        if (! in_array( $due_date, $dates ) ) {
            return self::awc_verify_days_that_not_holidays( $due_date );
        }

        $holiday = $holidays[ array_search( $due_date, $dates ) ];

        if ( $holiday['day_week'] == AWC_Day_Week::AWC_FRIDAY ) {
            return date( 'Y-m-d', strtotime( $due_date . self::ADD_THREE_DAYS ) );
        }

        if ( $holiday['day_week'] == AWC_Day_Week::AWC_SATURDAY ) {
            return date( 'Y-m-d', strtotime( $due_date . self::ADD_TWO_DAYS ) );
        }

        if ( in_array( $holiday['day_week'], self::awc_get_days_week_that_need_add_one_day() ) ) {
            return date( 'Y-m-d', strtotime( $due_date . self::ADD_ONE_DAYS ) );
        }
    }

    public static function awc_verify_days_that_not_holidays( $due_date )
    {
        $day_week = AWC_Helper::awc_get_day_week_textual( $due_date );

        if ( $day_week == AWC_Day_Week::AWC_SATURDAY ) {
            return date( 'Y-m-d', strtotime( $due_date . self::ADD_TWO_DAYS ) );
        }

        if ( $day_week == AWC_Day_Week::AWC_SUNDAY ) {
            return date( 'Y-m-d', strtotime( $due_date . self::ADD_ONE_DAYS ) );
        }

        return $due_date;
    }

    public static function awc_generate_due_date( $due_days )
    {
        $due_date = date( "Y-m-d", strtotime( date( "Y-m-d" ) . self::awc_verify_due_days ( $due_days ) ) );

        return self::awc_adjust_business_days_from_due_date( $due_date );
    }

    public static function awc_get_days_week_that_need_add_one_day()
    {
        return array(
            AWC_Day_Week::AWC_SUNDAY,
            AWC_Day_Week::AWC_MONDAY,
            AWC_Day_Week::AWC_TUESDAY,
            AWC_Day_Week::AWC_WEDNESDAY,
            AWC_Day_Week::AWC_THURSDAY,
        );
    }

    public static function awc_get_holidays()
    {
        return [
            [
                'date' => date( 'Y-m-d', strtotime( sprintf( '%d-%d-%d', date('Y'), 1, 1 ) ) ),
                'day_week' => AWC_Helper::awc_get_day_week_textual( sprintf( '%d-%d-%d', date('Y'), 1, 1 ) ),
            ],
            [
                'date' => date( 'Y-m-d', easter_date() - ( 2 * 60 * 60 * 24 ) ),
                'day_week' => AWC_Helper::awc_get_day_week_textual( date( 'Y-m-d', easter_date() - ( 2 * 60 * 60 * 24 ) ) ),
            ],
            [
                'date' => date( 'Y-m-d', easter_date() ),
                'day_week' => AWC_Helper::awc_get_day_week_textual( date( 'Y-m-d', easter_date() ) ),
            ],
            [
                'date' => date( 'Y-m-d', strtotime( sprintf( '%d-%d-%d', date('Y'), 4, 21 ) ) ),
                'day_week' => AWC_Helper::awc_get_day_week_textual( sprintf( '%d-%d-%d', date('Y'), 4, 21 ) ),
            ],
            [
                'date' => date( 'Y-m-d', strtotime( sprintf( '%d-%d-%d', date('Y'), 5, 1 ) ) ),
                'day_week' => AWC_Helper::awc_get_day_week_textual( sprintf( '%d-%d-%d', date('Y'), 5, 1 ) ),
            ],
            [
                'date' => date( 'Y-m-d', strtotime( sprintf( '%d-%d-%d', date('Y'), 9, 7 ) ) ),
                'day_week' => AWC_Helper::awc_get_day_week_textual( sprintf( '%d-%d-%d', date('Y'), 9, 7 ) ),
            ],
            [
                'date' => date( 'Y-m-d', strtotime( sprintf( '%d-%d-%d', date('Y'), 10, 12 ) ) ),
                'day_week' => AWC_Helper::awc_get_day_week_textual( sprintf( '%d-%d-%d', date('Y'), 10, 12 ) ),
            ],
            [
                'date' => date( 'Y-m-d', strtotime( sprintf( '%d-%d-%d', date('Y'), 11, 2 ) ) ),
                'day_week' => AWC_Helper::awc_get_day_week_textual( sprintf( '%d-%d-%d', date('Y'), 11, 2 ) ),
            ],
            [
                'date' => date( 'Y-m-d', strtotime( sprintf( '%d-%d-%d', date('Y'), 11, 15 ) ) ),
                'day_week' => AWC_Helper::awc_get_day_week_textual( sprintf( '%d-%d-%d', date('Y'), 11, 15 ) ),
            ],
            [
                'date' => date( 'Y-m-d', strtotime( sprintf( '%d-%d-%d', date('Y'), 12, 25 ) ) ),
                'day_week' => AWC_Helper::awc_get_day_week_textual( sprintf( '%d-%d-%d', date('Y'), 12, 25 ) ),
            ],
        ];
    }
}