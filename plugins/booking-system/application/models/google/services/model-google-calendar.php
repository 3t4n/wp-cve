<?php

/*
 * Title                   : Pinpoint Booking System
 * File                    : application/models/model-google-calendar.php
 * Author                  : Dot on Paper
 * Copyright               : © 2016-2020 Dot on Paper
 * Website                 : https://www.dotonpaper.net
 * Description             : Google model PHP class.
 */

if (!class_exists('DOTModelGoogleCalendar')){
    class DOTModelGoogleCalendar{
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

        function getGcalId($calendar_id){
            global $DOPBSP;
            $settings_calendar = $DOPBSP->classes->backend_settings->values($calendar_id,
                                                                            'calendar');

            $calendarID = ($settings_calendar->google_calendar_id !== '')
                    ? $settings_calendar->google_calendar_id
                    : 'primary';
            return $calendarID;
        }

        function add($calendar_id,
                     $reservation_id,
                     $reservation){
            global $DOPBSP;
            $client = (new DOTModelGoogle)->connect($calendar_id);
            if ($client){
                $service = new Google_Service_Calendar($client);

                $settings_calendar = $DOPBSP->classes->backend_settings->values($calendar_id,
                                                                                'calendar');
                if ($settings_calendar->timezone != ''){
                    $timezone = $settings_calendar->timezone;
                }
                else{
                    $timezone = 'GMT';
                }
                $calendarId = isset($settings_calendar->google_calendar_id)
                        ? $settings_calendar->google_calendar_id
                        : 'primary';

                $reservation['check_out'] = $reservation['check_out'] != ''
                        ? ($settings_calendar->days_morning_check_out == 'false' && $settings_calendar->hours_enabled == 'false'
                                ? date('Y-m-d',
                                       strtotime('+1 day',
                                                 strtotime($reservation['check_out'])))
                                : $reservation['check_out'])
                        : $reservation['check_in'];

                if ($reservation['start_hour'] != ''){
                    $event = new Google_Service_Calendar_Event(apply_filters('dopbsp_filter_google_calendar_event_data_time',
                                                                             array(
                                                                                     'summary' => 'Pinpoint Reservation',
                                                                                     'description' => 'Reservation ID: '
                                                                                             .$reservation_id,
                                                                                     'start' => array(
                                                                                             'dateTime' => $reservation['check_in']
                                                                                                     .'T'.$reservation['start_hour']
                                                                                                     .':00',
                                                                                             'timeZone' => $timezone,
                                                                                     ),
                                                                                     'end' => array(
                                                                                             'dateTime' => $reservation['check_in']
                                                                                                     .'T'.($reservation['end_hour'] != ''
                                                                                                             ? $reservation['end_hour']
                                                                                                             : $reservation['start_hour'])
                                                                                                     .':00',
                                                                                             'timeZone' => $timezone,
                                                                                     ),
                                                                                     'reminders' => array(
                                                                                             'useDefault' => true,
                                                                                     ),
                                                                                     'iCalUID' => $reservation['uid']
                                                                             ),
                                                                             $reservation_id));
                }
                else{
                    $event = new Google_Service_Calendar_Event(apply_filters('dopbsp_filter_google_calendar_event_data_dates',
                                                                             array(
                                                                                     'summary' => 'Pinpoint Reservation',
                                                                                     'description' => 'Reservation ID: '
                                                                                             .$reservation_id,
                                                                                     'start' => array(
                                                                                             'date' => $reservation['check_in'],
                                                                                             'timeZone' => 'GMT',
                                                                                     ),
                                                                                     'end' => array(
                                                                                             'date' => $reservation['check_out'],
                                                                                             'timeZone' => $timezone,
                                                                                     ),
                                                                                     'reminders' => array(
                                                                                             'useDefault' => true,
                                                                                     ),
                                                                                     'iCalUID' => $reservation['uid']
                                                                             ),
                                                                             $reservation_id));
                }
                $service->events->insert($calendarId,
                                         $event);
            }
        }

        function getAll($calendar_id,
                        $initial_export = false){
            $client = (new DOTModelGoogle)->connect($calendar_id);
            if ($client){
                $service = new Google_Service_Calendar($client);
            }
            else{
                return $events = '';
            }

            $calendarId = $this->getGcalId($calendar_id);

            $optParams = array(
                    'maxResults' => 100,
                    'orderBy' => 'startTime',
                    'singleEvents' => true,
                    'showDeleted' => false,
                    'timeMin' => date('c'),
            );
            $results = $service->events->listEvents($calendarId,
                                                    $optParams);
            $events = $results->getItems();

            if (empty($events)){
                return 0;
            }
            else{
                foreach ($events as $event){
                    $start = $event->start->dateTime;
                    if (empty($start)){
                        $start = $event->start->date;
                    }
                }
            }

            return $events;
        }

        /*
         * Inserts google event into Pinpoint Calendar.
         */

        function addAll($calendar_id,
                        $events){
            global $DOT;
            global $DOPBSP;

            $reservations = $DOT->db->results($DOT->db->safe('SELECT * FROM '.$DOT->tables->reservations.' WHERE reservation_from=%s AND calendar_id='.$calendar_id,
                                                             array('pinpoint')));

            foreach ($events as $event){
                foreach ($reservations as $reservation){
                    if ($event->iCalUID !== $reservation->uid){
                        $this->add($calendar_id,
                                   $reservation->id,
                                   $reservation);
                    }
                }
            }
        }

        function get($calendar_id,
                     $reservation_uid,
                     $hidden){
            $client = (new DOTModelGoogle)->connect($calendar_id);
            $service = new Google_Service_Calendar($client);

            $calendarId = $this->getGcalId($calendar_id);
            $optParams = array(
                    'maxResults' => 100,
                    'orderBy' => 'startTime',
                    'singleEvents' => true,
                    'showDeleted' => $hidden,
                    'timeMin' => date('c'),
            );
            $results = $service->events->listEvents($calendarId,
                                                    $optParams);
            for ($i = 0; $i<count($results); $i++){
                if ($results[$i]->iCalUID === $reservation_uid){
                    $reservation = $results[$i];
                }
            }
            return $reservation;
        }

        function delete($calendar_id,
                        $reservation_uid){
            try{
                $client = (new DOTModelGoogle)->connect($calendar_id);
                $service = new Google_Service_Calendar($client);
                $event = $this->get($calendar_id,
                                    $reservation_uid,
                                    false);
                $eventID = $event->id;
                //    print_r($eventID);
                $calendarId = $this->getGcalId($calendar_id);
                $service->events->delete($calendarId,
                                         $eventID);
            }
            catch(Exception $e){
                return null;
            }
        }

        function update($calendar_id,
                        $reservation_uid){
            try{
                $client = (new DOTModelGoogle)->connect($calendar_id);
                $service = new Google_Service_Calendar($client);
                $event = $this->get($calendar_id,
                                    $reservation_uid,
                                    true);
                $calendarId = $this->getGcalId($calendar_id);
                $event->setStatus('confirmed');
                // if ($event){
                $eventID = $event->getId();

                $service->events->update($calendarId,
                                         $eventID,
                                         $event);
                //   }
            }
            catch(Exception $e){
                return null;
            }
        }

    }
}