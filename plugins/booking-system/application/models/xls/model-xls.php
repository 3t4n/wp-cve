<?php

/*
 * Title                   : Pinpoint Booking System
 * File                    : application/models/model-xls.php
 * Author                  : Pinpoint World
 * Copyright               : © 2021 Pinpoint World
 * Website                 : https://pinpoint.world
 * Description             : XLS model PHP class.
 */

if (!class_exists('DOTModelXls')){
    class DOTModelXls{
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
         * Get iCal.
         *
         * @usage
         *      In FILE search for function call: $this->get
         *      In FILE search for function call in hooks: array(&$this, 'get')
         *      In PROJECT search for function call: $DOT->models->ical->get
         *
         * @params
         *      labels (array): labels list
         *      data (array): XLS data
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
         *      XLS content.
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
         * @param array $labels
         * @param array $data
         *
         * @return string
         */
        function get($labels,
                     $data){
            $content = array();

            /*
             * Set labels.
             */
            $row = array();
            $row_empty = array();

            foreach ($labels as $label){
                if ($label->usage != 0){
                    array_push($row,
                               $label->label);
                    array_push($row_empty,
                               ' ');
                }
            }
            array_push($content,
                       implode("\t",
                               $row));
            array_push($content,
                       implode("\t",
                               $row_empty));

            /*
             * Set data.
             */
            foreach ($data as $item){
                $row = array();

                foreach ($labels as $key => $label){
                    $label->usage != 0
                            ? array_push($row,
                                         $item[$key] ?? '-')
                            : null;
                }
                array_push($content,
                           implode("\t",
                                   $row));
            }

            return implode("\t\n",
                           $content)
                    ."\t\n";
        }
    }
}