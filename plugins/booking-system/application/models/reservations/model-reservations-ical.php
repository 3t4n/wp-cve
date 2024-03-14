<?php

/*
 * Title                   : Pinpoint Booking System
 * File                    : application/models/reservations/model-reservations-ical.php
 * Author                  : Pinpoint World
 * Copyright               : © 2021 Pinpoint World
 * Website                 : https://pinpoint.world
 * Description             : Reservations iCal model PHP class.
 */

if (!class_exists('DOTModelReservationsIcal')){
    class DOTModelReservationsIcal{
        /*
         * Constructor
         *
         * @usage
         *      The constructor is called when a class instance is created.
         *
         * @params
         *      -
         *
         * @post
         *      -
         *
         * @get
         *      -
         *
         * @sessions
         *      -
         *
         * @cookies
         *      -
         *
         * @constants
         *      -
         *
         * @globals
         *      -
         *
         * @functions
         *      -
         *
         * @hooks
         *      -
         *
         * @layouts
         *      -
         *
         * @return
         *      -
         *
         * @return_details
         *      -
         *
         * @dv
         *      -
         *
         * @tests
         *      -
         */
        function __construct(){
        }

        /*
         * Get reservations iCal.
         *
         * @usage
         *      In FILE search for function call: $this->get
         *      In FILE search for function call in hooks: array(&$this, 'get')
         *      In PROJECT search for function call: $DOT->models->reservations_ical->get
         *
         * @params
         *      reservations (array): reservations list
         *
         * @post
         *      -
         *
         * @get
         *      -
         *
         * @sessions
         *      -
         *
         * @cookies
         *      -
         *
         * @constants
         *      -
         *
         * @globals
         *      DOT (object): DOT framework main class variable
         *
         * @functions
         *      application/models/ical/model-ical.php : get() // Get iCal.
         *
         * @hooks
         *      -
         *
         * @layouts
         *      -
         *
         * @return
         *      Reservations iCal content.
         *
         * @return_details
         *      -
         *
         * @dv
         *      -
         *
         * @tests
         *      -
         */
        /**
         * @param array $reservations
         */
        function get($reservations){
            global $DOPBSP;
            global $DOT;
            global $wpdb;

            $calendars = array();
            $events = array();
            $timezones = array();

            /*
             * Set reservations.
             */
            foreach ($reservations as $reservation){
                $event = new stdClass;

                /*
                 * Get calendar data.
                 */
                if (!array_key_exists($reservation->calendar_id,
                                      $calendars)){
                    $calendar = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$DOPBSP->tables->calendars.' WHERE id=%d',
                                                              $reservation->calendar_id));
                    $calendar_settings = $DOPBSP->classes->backend_settings->values($reservation->calendar_id,
                                                                                    'calendar');

                    /*
                     * Set timezones.
                     */
                    $timezone = $calendar_settings->timezone == ''
                            ? 'GMT'
                            : $calendar_settings->timezone;

                    !in_array($timezone,
                              $timezones)
                            ? array_push($timezones,
                                         $timezone)
                            : null;

                    /*
                     * Set settings.
                     */
                    $calendars[$reservation->calendar_id] = new stdClass;
                    $calendars[$reservation->calendar_id]->days_morning_check_out = $calendar_settings->days_morning_check_out;
                    $calendars[$reservation->calendar_id]->hours_enabled = $calendar_settings->hours_enabled;
                    $calendars[$reservation->calendar_id]->timezone = $timezone;
                }

                /*
                 * Set dates & time.
                 */
                $reservation->check_in != ''
                        ? $event->date_start = $reservation->check_in
                        : null;
                $reservation->start_hour != ''
                        ? $event->time_start = $reservation->start_hour
                        : null;

                if ($reservation->check_out != ''){
                    $event->date_end = $calendars[$reservation->calendar_id]->days_morning_check_out == 'false' && $calendars[$reservation->calendar_id]->hours_enabled == 'false'
                            ? date('Y-m-d',
                                   strtotime('+1 day',
                                             strtotime($reservation->check_out)))
                            : $reservation->check_out;
                }
                $reservation->end_hour != ''
                        ? $event->time_end = $reservation->end_hour
                        : null;

                $date_created = explode(' ',
                                        $reservation->date_created);
                $event->date_created = $date_created[0];
                $event->time_created = $date_created[1];

                /*
                 * Set description.
                 */
                $event->description = $DOPBSP->classes->backend_reservations->getSyncDescription('|FORM|',
                                                                                                 $reservation);

                /*
                 * Set summary.
                 */
                $event->summary = '['.$DOPBSP->text('RESERVATIONS_RESERVATION_STATUS_'.strtoupper($reservation->status)).']'.' '.$calendar->name.' (#'.$reservation->id.')';

                /*
                 * Set timezone.
                 */
                $event->timezone = $calendars[$reservation->calendar_id]->timezone;

                /*
                 * Set transparency.
                 */
                $event->transparency = 'OPAQUE';

                /*
                 * Set unique ID.
                 */
                $event->uid = $reservation->uid;

                /*
                 * Add event.
                 */
                array_push($events,
                           $event);
            }

            /*
             * Get iCal.
             */
            echo $DOT->models->ical->get($events,
                                         $timezones);

            exit;
        }
    }
}