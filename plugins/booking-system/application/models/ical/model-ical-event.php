<?php

/*
 * Title                   : Pinpoint Booking System
 * File                    : application/models/model-ical-event.php
 * Author                  : Pinpoint World
 * Copyright               : © 2021 Pinpoint World
 * Website                 : https://pinpoint.world
 * Description             : iCal event model PHP class.
 */

if (!class_exists('DOTModelIcalEvent')){
    class DOTModelIcalEvent{
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
         * Set iCal event creation date.
         *
         * @usage
         *      In FILE search for function call: $this->dateCreated
         *      In FILE search for function call in hooks: array(&$this, 'dateCreated')
         *      In PROJECT search for function call: $DOT->models->ical_event->dateCreated
         *
         * @params
         *      ical (array): current iCal event data
         *      event (object): event data
         *                      explained in function [this : get()]
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
         *      application/models/model-ical.php : dt() // Set iCal date & time.
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
         * @param object $event
         */
        function dateCreated(&$ical,
                             $event){
            global $DOT;

            isset($event->date_created) && isset($event->time_created)
                    ? array_push($ical,
                                 'CREATED:'.$DOT->models->ical->dt($event->date_created,
                                                                   $event->time_created))
                    : null;
        }

        /*
         * Set iCal event end date.
         *
         * @usage
         *      In FILE search for function call: $this->dateEnd
         *      In FILE search for function call in hooks: array(&$this, 'dateEnd')
         *      In PROJECT search for function call: $DOT->models->ical_event->dateEnd
         *
         * @params
         *      ical (array): current iCal event data
         *      event (object): event data
         *                      explained in function [this : get()]
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
         *      application/models/model-ical.php : dt() // Set iCal date & time.
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
         * @param object $event
         */
        function dateEnd(&$ical,
                         $event){
            global $DOT;

            if (isset($event->date_start)
                    || isset($event->date_end)){
                /*
                 * Set date.
                 */
                $date = $event->date_end ?? $event->date_start;

                /*
                 * Set time.
                 */
                $time = $event->time_end ?? ($event->time_start ?? '');

                array_push($ical,
                           'DTEND'
                           .($time != ''
                                   ? ';TZID='.$event->timezone.';VALUE=DATE-TIME:'
                                   : ';VALUE=DATE:')
                           .$DOT->models->ical->dt($date,
                                                   $time));
            }
        }

        /*
         * Set iCal event start date.
         *
         * @usage
         *      In FILE search for function call: $this->dateStart
         *      In FILE search for function call in hooks: array(&$this, 'dateStart')
         *      In PROJECT search for function call: $DOT->models->ical_event->dateStart
         *
         * @params
         *      ical (array): current iCal event data
         *      event (object): event data
         *                      explained in function [this : get()]
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
         *      application/models/model-ical.php : dt() // Set iCal date & time.
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
         * @param object $event
         */
        function dateStart(&$ical,
                           $event){
            global $DOT;

            if (isset($event->date_start)){
                /*
                 * Set date.
                 */
                $date = $event->date_start;

                /*
                 * Set time.
                 */
                $time = $event->time_start ?? '';

                array_push($ical,
                           'DTSTART'
                           .($time != ''
                                   ? ($event->timezone != 'GMT'
                                           ? ';TZID='.$event->timezone
                                           : '').';VALUE=DATE-TIME:'
                                   : ';VALUE=DATE:')
                           .$DOT->models->ical->dt($date,
                                                   $time));
            }
        }

        /*
         * Set iCal event description.
         *
         * @usage
         *      In FILE search for function call: $this->description
         *      In FILE search for function call in hooks: array(&$this, 'description')
         *      In PROJECT search for function call: $DOT->models->ical_event->description
         *
         * @params
         *      ical (array): current iCal event data
         *      event (object): event data
         *                      explained in function [this : get()]
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
         *      application/models/model-ical.php : sanitize() // Sanitize Ical content.
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
         * @param object $event
         */
        function description(&$ical,
                             $event){
            global $DOT;

            isset($event->summary)
                    ? array_push($ical,
                                 'DESCRIPTION:'.$DOT->models->ical->sanitize($event->description))
                    : null;
        }

        /*
         * Get iCal event.
         *
         * @usage
         *      In FILE search for function call: $this->get
         *      In FILE search for function call in hooks: array(&$this, 'get')
         *      In PROJECT search for function call: $DOT->models->ical_event->get
         *
         * @params
         *      event (object): event data
         *                      {event}->date_created (string) : event creation date (YYYY-MM-DD)
         *                      {event}->date_end (string) : event end date (YYYY-MM-DD)
         *                      {event}->date_start (string) : event start date (YYYY-MM-DD)
         *                      {event}->description (string) : event summary
         *                      {event}->summary (string) : event summary
         *                      {event}->time_created (string) : event creation time (HH:MM:SS)
         *                      {event}->time_end (string) : event end time (HH:MM)
         *                      {event}->time_start (string) : event start time (HH:MM)
         *                      {event}->timezone (string) : event timezone
         *                      {event}->transparency (string) : event transparency; "OPAQUE" | "TRANSPARENT"
         *                      {event}->uid (string) : event unique ID
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
         *      this : dateCreated() // Set iCal event creation date.
         *      this : dateEnd() // Set iCal event end date.
         *      this : dateStart() // Set iCal event start date.
         *      this : description() // Set iCal event description.
         *      this : summary() // Set iCal event summary.
         *      this : timestamp() // Set iCal event timestamp.
         *      this : transparency() // Set iCal event transparency.
         *      this : uid() // Set iCal event unique ID.
         *
         * @hooks
         *      -
         *
         * @layouts
         *      -
         *
         * @return
         *      Event iCal content.
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
         * @param object $event
         *
         * @return array
         */
        function get($event){
            $ical = array();

            /*
             * Start event.
             */
            array_push($ical,
                       'BEGIN:VEVENT');

            /*
             * Set start date.
             */
            $this->dateStart($ical,
                             $event);

            /*
             * Set end date.
             */
            $this->dateEnd($ical,
                           $event);

            /*
             * Set creation date.
             */
            $this->dateCreated($ical,
                               $event);

            /*
             * Set description.
             */
            $this->description($ical,
                               $event);

            /*
             * Set summary.
             */
            $this->summary($ical,
                           $event);

            /*
             * Set timestamp.
             */
            $this->timestamp($ical);

            /*
             * Set transparency.
             */
            $this->transparency($ical,
                                $event);

            /*
             * Set unique ID.
             */
            $this->uid($ical,
                       $event);

            /*
             * End event.
             */
            array_push($ical,
                       'END:VEVENT');

            return $ical;
        }

        /*
         * Set iCal event summary.
         *
         * @usage
         *      In FILE search for function call: $this->summary
         *      In FILE search for function call in hooks: array(&$this, 'summary')
         *      In PROJECT search for function call: $DOT->models->ical_event->summary
         *
         * @params
         *      ical (array): current iCal event data
         *      event (object): event data
         *                      explained in function [this : get()]
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
         *      application/models/model-ical.php : sanitize() // Sanitize Ical content.
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
         * @param object $event
         */
        function summary(&$ical,
                         $event){
            global $DOT;

            isset($event->summary)
                    ? array_push($ical,
                                 'SUMMARY:'.$DOT->models->ical->sanitize($event->summary))
                    : null;
        }

        /*
         * Set iCal event timestamp.
         *
         * @usage
         *      In FILE search for function call: $this->timestamp
         *      In FILE search for function call in hooks: array(&$this, 'timestamp')
         *      In PROJECT search for function call: $DOT->models->ical_event->timestamp
         *
         * @params
         *      ical (array): current iCal event data
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
         */
        function timestamp(&$ical){
            date_default_timezone_set('UTC');

            array_push($ical,
                       'DTSTAMP:'.gmdate("Ymd\THis\Z"));
        }

        /*
         * Set iCal event transparency.
         *
         * @usage
         *      In FILE search for function call: $this->summary
         *      In FILE search for function call in hooks: array(&$this, 'transparency')
         *      In PROJECT search for function call: $DOT->models->ical_event->transparency
         *
         * @params
         *      ical (array): current iCal event data
         *      event (object): event data
         *                      explained in function [this : get()]
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
         * @param object $event
         */
        function transparency(&$ical,
                              $event){
            isset($event->transparency)
                    ? array_push($ical,
                                 'TRANSP:'.$event->transparency)
                    : null;
        }

        /*
         * Set iCal event unique ID.
         *
         * @usage
         *      In FILE search for function call: $this->uid
         *      In FILE search for function call in hooks: array(&$this, 'uid')
         *      In PROJECT search for function call: $DOT->models->ical_event->uid
         *
         * @params
         *      ical (array): current iCal event data
         *      event (object): event data
         *                      explained in function [this : get()]
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
         * @param object $event
         */
        function uid(&$ical,
                     $event){
            array_push($ical,
                       'UID:'.$event->uid);
        }
    }
}