<?php

/*
 * Title                   : Pinpoint Booking System
 * File                    : application/models/model-ical.php
 * Author                  : Pinpoint World
 * Copyright               : © 2021 Pinpoint World
 * Website                 : https://pinpoint.world
 * Description             : iCal model PHP class.
 */

if (!class_exists('DOTModelIcal')){
    class DOTModelIcal{
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
         * Set iCal date & time.
         *
         * @usage
         *      In FILE search for function call: $this->dt
         *      In FILE search for function call in hooks: array(&$this, 'dt')
         *      In PROJECT search for function call: $DOT->models->ical->dt
         *
         * @params
         *      date (string): event date (YYYY-MM-DD)
         *      time (string): event time (HH:MM{:SS})
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
         *      Date in iCal format "{YYYY}{MM}{DD}T{}{}{}Z" or "{YYYY}{MM}{DD}".
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
         * @param string $date
         * @param string $time
         *
         * @return string
         */
        function dt($date,
                    $time = ''){
            return str_replace('-',
                               '',
                               $date)
                    .($time != ''
                            ? 'T'.str_replace(':',
                                              '',
                                              $time)
                            .(substr_count($time,
                                           ':') == 1
                                    ? '00'
                                    : '')
                            : '');
        }

        /*
         * Set iCal events.
         *
         * @usage
         *      In FILE search for function call: $this->events
         *      In FILE search for function call in hooks: array(&$this, 'events')
         *      In PROJECT search for function call: $DOT->models->ical->events
         *
         * @params
         *      ical (array): current iCal data
         *      events (array): events list
         *                      explained in function [application/models/model-ical-event.php : get()]
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
         *      application/models/model-ical-event.php : get() // Get iCal event.
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
         * @param array $events
         */
        function events(&$ical,
                        $events){
            global $DOT;

            foreach ($events as $event){
                $ical = array_merge($ical,
                                    $DOT->models->ical_event->get($event));
            }
        }

        /*
         * Get iCal.
         *
         * @usage
         *      In FILE search for function call: $this->get
         *      In FILE search for function call in hooks: array(&$this, 'get')
         *      In PROJECT search for function call: $DOT->models->ical->get
         *
         * @params
         *      events (array): events list
         *                      explained in function [application/models/model-ical-event.php : get()]
         *      timezones (array): events timezones
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
         *      application/models/model-ical-timezones.php : get() // Get iCal timezones.
         *
         *      this : events() // Set iCal events.
         *      this : timezones() // Set iCal timezones.
         *
         * @hooks
         *      -
         *
         * @layouts
         *      -
         *
         * @return
         *      iCal content.
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
         * @param array $events
         * @param array $timezones
         *
         * @return string
         */
        function get($events,
                     $timezones){
            $ical = array();

            /*
             * Start iCal.
             */
            array_push($ical,
                       'BEGIN:VCALENDAR');
            array_push($ical,
                       'PRODID:PINPOINT.WORLD');
            array_push($ical,
                       'VERSION:2.0');
            array_push($ical,
                       'CALSCALE:GREGORIAN');
            array_push($ical,
                       'METHOD:PUBLISH');

            /*
             * Set timezones.
             */
            $this->timezones($ical,
                             $events,
                             $timezones);

            /*
             * Set events.
             */
            $this->events($ical,
                          $events);

            /*
             * End iCal.
             */
            array_push($ical,
                       'END:VCALENDAR');

            return implode(PHP_EOL,
                           $ical);
        }

        /*
         * Sanitize iCal content.
         *
         * @usage
         *      In FILE search for function call: $this->sanitize
         *      In FILE search for function call in hooks: array(&$this, 'sanitize')
         *      In PROJECT search for function call: $DOT->models->ical->sanitize
         *
         * @params
         *      content (string): the content
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
         *      Sanitized content.
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
         * @param string $content
         *
         * @return string
         */
        function sanitize($content){
            return str_replace(array('\\',
                                     ',',
                                     ';'),
                               array('\\\\',
                                     '\\,',
                                     '\\;'),
                               $content);
        }

        /*
         * Set iCal timezones.
         *
         * @usage
         *      In FILE search for function call: $this->timezones
         *      In FILE search for function call in hooks: array(&$this, 'timezones')
         *      In PROJECT search for function call: $DOT->models->ical->timezones
         *
         * @params
         *      ical (array): current iCal data
         *      events (array): events list
         *                      explained in function [application/models/model-ical-event.php : get()]
         *      timezones (array): events timezones
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
         *      application/models/model-ical-timezone.php : get() // Get iCal timezone.
         *      application/models/model-ical-timezone.php : limits() // Get timezone limits.
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
         * @param array $events
         * @param array $timezones
         */
        function timezones(&$ical,
                           $events,
                           $timezones){
            global $DOT;

            /*
             * Set timezones.
             */
            if (count($timezones)>0){
                foreach ($timezones as $timezone){
                    /*
                     * Get limits.
                     */
                    $timezone = $DOT->models->ical_timezone->limits($events,
                                                                    $timezone);

                    /*
                     * Set timezone.
                     */
                    $timezone_ical = $DOT->models->ical_timezone->get($timezone);
                    $timezone_ical !== false
                            ? $ical = array_merge($ical,
                                                  $DOT->models->ical_timezone->get($timezone))
                            : null;
                }
            }
        }
    }
}