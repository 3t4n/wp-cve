<?php

/*
 * Title                   : Pinpoint Booking System
 * File                    : application/models/model-ical-timezone.php
 * Author                  : Pinpoint World
 * Copyright               : © 2021 Pinpoint World
 * Website                 : https://pinpoint.world
 * Description             : iCal timezone model PHP class.
 */

if (!class_exists('DOTModelIcalTimezone')){
    class DOTModelIcalTimezone{
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
         * Set iCal timezone date.
         *
         * @usage
         *      In FILE search for function call: $this->date
         *      In FILE search for function call in hooks: array(&$this, 'date')
         *      In PROJECT search for function call: $DOT->models->ical_timezone->date
         *
         * @params
         *      ical (array): current iCal timezone data
         *      transition (array): timezone transition data
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
         *      "ical" is updated by reference.
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
         * @param array $ical
         * @param array $transition
         */
        function date(&$ical,
                      $transition){
            $offset = $transition['offset'];

            /*
             * Set timestamp.
             */
            $timestamp = $transition['ts'];
            $timestamp_offset = $timestamp+$offset;

            /*
             * Set date.
             */
            $date = date('Ymd',
                         $timestamp_offset)
                    .'T'
                    .date('his',
                          $timestamp_offset);

            array_push($ical,
                       'DTSTART:'.$date);
        }

        /*
         * Get iCal timezone.
         *
         * @usage
         *      In FILE search for function call: $this->get
         *      In FILE search for function call in hooks: array(&$this, 'get')
         *      In PROJECT search for function call: $DOT->models->ical_timezone->get
         *
         * @params
         *      timezone (object): timezone data
	     *		                   {timezone}->name (string): timezone name
	     *		                   {timezone}->year_end (integer): the year were events end
	     *		                   {timezone}->year_start (integer): the year were events start
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
         *      this : id() // Set iCal timezone ID.
         *      this : transitions() // Set iCal timezone transitions.
         *
         * @hooks
         *      -
         *
         * @layouts
         *      -
         *
         * @return
         *      Timezone iCal content.
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
         * @param object $timezone
         *
         * @return array|boolean
         */
        function get($timezone){
            $ical = array();

            /*
             * Verify timezone.
             */
            if ($timezone->name == 'GMT'){
                return false;
            }

            /*
             * Start timezone.
             */
            array_push($ical,
                       'BEGIN:VTIMEZONE');

            /*
             * Set ID.
             */
            $this->id($ical,
                      $timezone);

            /*
             * Set transitions.
             */
            $this->transitions($ical,
                               $timezone);

            /*
             * End timezone.
             */
            array_push($ical,
                       'END:VTIMEZONE');

            return $ical;
        }

        /*
         * Set iCal timezone ID.
         *
         * @usage
         *      In FILE search for function call: $this->id
         *      In FILE search for function call in hooks: array(&$this, 'id')
         *      In PROJECT search for function call: $DOT->models->ical_timezone->id
         *
         * @params
         *      ical (array): current iCal timezone data
         *      timezone (object): timezone data
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
         *      "ical" is updated by reference.
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
         * @param array $ical
         * @param object $timezone
         */
        function id(&$ical,
                    $timezone){
            array_push($ical,
                       'TZID:'.$timezone->name);
        }

        /*
         * Get timezone limits.
         *
         * @usage
         *      In FILE search for function call: $this->limits
         *      In FILE search for function call in hooks: array(&$this, 'limits')
         *      In PROJECT search for function call: $DOT->models->ical_timezone->limits
         *
         * @params
         *      events (array): events list
         *                      explained in function [application/models/model-ical-event.php : get()]
         *      timezone (string): the timezone
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
         *      Timezone object.
         *
         * @return_details
	     *	    Timezone object:
	     *		    {timezone}->name (string): timezone name
	     *		    {timezone}->year_end (integer): the year were events end
	     *		    {timezone}->year_start (integer): the year were events start
         *
         * @dv
         *      -
         *
         * @tests
         *      -
         */
        /**
         * @param array $events
         * @param string $timezone
         *
         * @return object
         */
        function limits($events,
                        $timezone){
            $timezone_data = new stdClass;
            $timezone_data->name = $timezone;
            $timezone_data->year_end = 1970;
            $timezone_data->year_start = 3000;

            /*
             * Set timezone limits.
             */
            foreach ($events as $event){
                /*
                 * Set minimum year.
                 */
                $date = explode('-',
                                $event->date_start);
                $date_start = (int)$date[0];
                $timezone_data->year_start = $timezone_data->year_start>$date_start
                        ? $date_start
                        : $timezone_data->year_start;

                /*
                 * Set maximum year.
                 */
                $date = explode('-',
                                $event->date_end ?? $event->date_start);
                $date_end = (int)$date[0];
                $timezone_data->year_end = $timezone_data->year_end<$date_end
                        ? $date_end
                        : $timezone_data->year_end;
            }

            return $timezone_data;
        }

        /*
         * Set iCal timezone offset.
         *
         * @usage
         *      In FILE search for function call: $this->offset
         *      In FILE search for function call in hooks: array(&$this, 'offset')
         *      In PROJECT search for function call: $DOT->models->ical_timezone->offset
         *
         * @params
         *      ical (array): current iCal timezone data
         *      transition (array): timezone transition data
         *      type (string): timezone type
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
         *      this : offsetFormat() // Format iCal timezone offset.
         *
         * @hooks
         *      -
         *
         * @layouts
         *      -
         *
         * @return
         *      "ical" is updated by reference.
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
         * @param array $ical
         * @param array $transition
         * @param string $type
         */
        function offset(&$ical,
                        $transition,
                        $type){
            $offset = $transition['offset'];

            /*
             * Set offset to.
             */
            $offset_prev = $type == 'DAYLIGHT'
                    ? $offset-3600
                    : $offset+3600;

            array_push($ical,
                       'TZOFFSETFROM:'.$this->offsetFormat($offset_prev));

            array_push($ical,
                       'TZOFFSETTO:'.$this->offsetFormat($offset));
        }

        /*
         * Format iCal timezone offset.
         *
         * @usage
         *      In FILE search for function call: $this->offsetFormat
         *      In FILE search for function call in hooks: array(&$this, 'offsetFormat')
         *      In PROJECT search for function call: $DOT->models->ical_timezone->offsetFormat
         *
         * @params
         *      offset (string): the offset
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
         *      Formatted offset {sign}HHMM
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
         * @param string $offset
         *
         * @return string
         */
        function offsetFormat($offset){
            /*
             * Set sign.
             */
            $sign = $offset<0
                    ? '-'
                    : '+';
            $offset = abs($offset);

            /*
             * Set hours.
             */
            $hours = sprintf('%02d',
                             intval($offset/3600));

            /*
             * Set minutes.
             */
            $minutes = sprintf('%02d',
                               $offset/60-intval($offset/60/60)*60);

            return $sign.$hours.$minutes;
        }

        /*
         * Set iCal timezone transitions.
         *
         * @usage
         *      In FILE search for function call: $this->transitions
         *      In FILE search for function call in hooks: array(&$this, 'transitions')
         *      In PROJECT search for function call: $DOT->models->ical_timezone->transitions
         *
         * @params
         *      ical (array): current iCal timezone data
         *      timezone (object): timezone data
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
         *      this : transition() // Set iCal timezone transition.
         *
         * @hooks
         *      -
         *
         * @layouts
         *      -
         *
         * @return
         *      "ical" is updated by reference.
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
         * @param array $ical
         * @param object $timezone
         */
        function transitions(&$ical,
                             $timezone){
            /*
             * Init time.
             */
            $time_start = gmmktime(0,
                                   0,
                                   0,
                                   1,
                                   1,
                                   $timezone->year_start-1);
            $time_end = gmmktime(23,
                                 59,
                                 59,
                                 12,
                                 31,
                                 $timezone->year_end+1);

            /*
             * Get transitions.
             */
            $timezone_obj = new DateTimeZone($timezone->name);
            $transitions = $timezone_obj->getTransitions($time_start,
                                                         $time_end);

            /*
             * Set transitions.
             */
            for ($i = 1; $i<count($transitions); $i = $i+2){
                /*
                 * Set daylight transition.
                 */
                $this->transition($ical,
                                  $transitions[$i]);

                /*
                 * Set standard transition.
                 */
                $this->transition($ical,
                                  $transitions[$i+1]);
            }
        }

        /*
         * Set iCal timezone transition.
         *
         * @usage
         *      In FILE search for function call: $this->transition
         *      In FILE search for function call in hooks: array(&$this, 'transition')
         *      In PROJECT search for function call: $DOT->models->ical_timezone->transition
         *
         * @params
         *      ical (array): current iCal timezone data
         *      transition (array): timezone transition data
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
         *      this : date() // Set iCal timezone date.
         *      this : offset() // Set iCal timezone offset.
         *
         * @hooks
         *      -
         *
         * @layouts
         *      -
         *
         * @return
         *      "ical" is updated by reference.
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
         * @param array $ical
         * @param array $transition
         */
        function transition(&$ical,
                            $transition){
            /*
             * Set type.
             */
            $type = $transition['isdst']
                    ? 'DAYLIGHT'
                    : 'STANDARD';

            /*
             * Start transition.
             */
            array_push($ical,
                       'BEGIN:'.$type);

            /*
             * Set name.
             */
            array_push($ical,
                       'TZNAME:'.$transition['abbr']);

            /*
             * Set date.
             */
            $this->date($ical,
                        $transition);

            /*
             * Set offset.
             */
            $this->offset($ical,
                          $transition,
                          $type);

            /*
             * End transition.
             */
            array_push($ical,
                       'END:'.$type);
        }
    }
}