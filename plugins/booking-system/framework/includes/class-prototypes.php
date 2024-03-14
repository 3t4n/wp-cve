<?php

/*
 * Title                   : DOT Framework
 * File                    : framework/includes/class-prototypes.php
 * Author                  : Dot on Paper
 * Copyright               : © 2019 Dot on Paper
 * Website                 : https://dotonpaper.net
 * Description             : Prototypes PHP class.
 */

if (!class_exists('DOTPrototypes')){
    class DOTPrototypes{

        /*
         * Constructor
     *
     * @usage
     *	    The constructor is called when a class instance is created.
     *
         * @params
     *	    -
     *
     * @post
     *	    -
     *
     * @get
     *	    -
     *
     * @sessions
     *	    -
     *
     * @cookies
     *	    -
     *
     * @constants
     *	    -
     *
     * @globals
     *	    -
     *
     * @functions
     *	    -
     *
     * @hooks
     *	    -
     *
     * @layouts
     *	    -
     *
     * @return
     *	    -
     *
     * @return_details
     *	    -
     *
     * @dv
     *	    -
     *
     * @tests
     *	    -
         */
        function __construct(){
        }

        /*
         * Date & time.
         */

        /*
         * Returns "time ago" of a date.
     *
     * @usage
     *	    Reserved framework function that will be called by DOT application.
     *
     *	    In FILE search for function call: $this->ago
     *	    In FILE search for function call in hooks: array(&$this, 'ago')
     *	    In PROJECT search for function call: $DOT->prototypes->ago
     *
         * @params
         *	    date (string): the date, in format YYYY-MM-DD HH:MM:SS
     *
     * @post
     *	    -
     *
     * @get
     *	    -
     *
     * @sessions
     *	    -
     *
     * @cookies
     *	    -
     *
     * @constants
     *	    -
     *
     * @globals
     *	    dot_text (array): application translation text
     *
     * @functions
     *	    -
     *
     * @hooks
     *	    -
     *
     * @layouts
     *	    -
     *
     * @return
     *	    "Time ago" date.
     *
     * @return_details
     *	    -
     *
     * @dv
     *	    -
     *
     * @tests
     *	    -
         */
        function ago($date){
            global $dot_text;

            /*
             * Set time estimate.
             */
            $time_estimate = time()-strtotime($date);

            /*
             * Set period intervals.
             */
            $time_intervals = array('year' => 12*30*24*60*60,
                                    'month' => 30*24*60*60,
                                    'week' => 7*24*60*60,
                                    'day' => 24*60*60,
                                    'hour' => 60*60,
                                    'minute' => 60,
                                    'second' => 1);

            /*
             * Set labels.
             */
            $labels = array('ago' => isset($dot_text['TIME_AGO'])
                    ? $dot_text['TIME_AGO']
                    : 'ago',
                            'year' => isset($dot_text['TIME_AGO_YEAR'])
                                    ? $dot_text['TIME_AGO_YEAR']
                                    : 'year',
                            'years' => isset($dot_text['TIME_AGO_YEARS'])
                                    ? $dot_text['TIME_AGO_YEARS']
                                    : 'years',
                            'month' => isset($dot_text['TIME_AGO_MONTH'])
                                    ? $dot_text['TIME_AGO_MONTH']
                                    : 'month',
                            'months' => isset($dot_text['TIME_AGO_MONTHS'])
                                    ? $dot_text['TIME_AGO_MONTHS']
                                    : 'months',
                            'week' => isset($dot_text['TIME_AGO_WEEK'])
                                    ? $dot_text['TIME_AGO_WEEK']
                                    : 'week',
                            'weeks' => isset($dot_text['TIME_AGO_WEEKS'])
                                    ? $dot_text['TIME_AGO_WEEKS']
                                    : 'weeks',
                            'day' => isset($dot_text['TIME_AGO_DAY'])
                                    ? $dot_text['TIME_AGO_DAY']
                                    : 'day',
                            'days' => isset($dot_text['TIME_AGO_DAYS'])
                                    ? $dot_text['TIME_AGO_DAYS']
                                    : 'days',
                            'hour' => isset($dot_text['TIME_AGO_HOUR'])
                                    ? $dot_text['TIME_AGO_HOUR']
                                    : 'hour',
                            'hours' => isset($dot_text['TIME_AGO_HOURS'])
                                    ? $dot_text['TIME_AGO_HOURS']
                                    : 'hours',
                            'minute' => isset($dot_text['TIME_AGO_MINUTE'])
                                    ? $dot_text['TIME_AGO_MINUTE']
                                    : 'minute',
                            'minutes' => isset($dot_text['TIME_AGO_MINUTES'])
                                    ? $dot_text['TIME_AGO_MINUTES']
                                    : 'minutes',
                            'second' => isset($dot_text['TIME_AGO_SECOND'])
                                    ? $dot_text['TIME_AGO_SECOND']
                                    : 'second',
                            'seconds' => isset($dot_text['TIME_AGO_SECONDS'])
                                    ? $dot_text['TIME_AGO_SECONDS']
                                    : 'seconds');

            /*
             * Return the first interval that is lower or equal with time estimate.
             */
            foreach ($time_intervals as $label => $seconds){
                $time_ago = $time_estimate/$seconds;

                if ($time_ago>=1){
                    $time_ago = round($time_ago);

                    return $time_ago.' '.($time_ago>1
                                    ? $labels[$label.'s']
                                    : $labels[$label]).' '.$labels['ago'];
                }
            }

            /*
             * Return if time is lower than 1 second.
             */
            return '1 '.$labels['second'].' '.$labels['ago'];
        }

        /*
         * Returns date in requested patern.
     *
     * @usage
     *	    Reserved framework function that will be called by DOT application.
     *
     *	    In FILE search for function call: $this->date
     *	    In FILE search for function call in hooks: array(&$this, 'date')
     *	    In PROJECT search for function call: $DOT->prototypes->date
     *
         * @params
         *	    date (string): the date that will be returned, in format YYYY-MM-DD
         *	    patern (string): the pattern of the new date; the pattern contains some constants to display the date:
     *		[DD] : day with leading zeros
     *		[D] : day without leading zeros
     *		[MM] : month with leading zeros
     *		[M] : month without leading zeros
     *		[mm] : month name
     *		[m] : short month name
     *		[YYYY] : the year
     *		[YY] : short year
     *
     * @post
     *	    -
     *
     * @get
     *	    -
     *
     * @sessions
     *	    -
     *
     * @cookies
     *	    -
     *
     * @constants
     *	    -
     *
     * @globals
     *	    dot_text (array): application translation text
     *
     * @functions
     *	    -
     *
     * @hooks
     *	    -
     *
     * @layouts
     *	    -
     *
     * @return
     *	    The date after pattern.
     *
     * @return_details
     *	    Month names are set in application translation with prefixes [MONTH_] and [MONTH_SHORT_].
     *
     * @dv
     *	    -
     *
     * @tests
     *	    -
         */
        function date($date,
                      $patern = '[DD] [mm] [YYYY]'){
            global $dot_text;

            /*
             * Default months names.
             */
            $month_names = array(isset($dot_text['MONTH_JANUARY_JS'])
                                         ? $dot_text['MONTH_JANUARY_JS']
                                         : 'January',
                                 isset($dot_text['MONTH_FEBRUARY_JS'])
                                         ? $dot_text['MONTH_FEBRUARY_JS']
                                         : 'February',
                                 isset($dot_text['MONTH_MARCH_JS'])
                                         ? $dot_text['MONTH_MARCH_JS']
                                         : 'March',
                                 isset($dot_text['MONTH_APRIL_JS'])
                                         ? $dot_text['MONTH_APRIL_JS']
                                         : 'April',
                                 isset($dot_text['MONTH_MAY_JS'])
                                         ? $dot_text['MONTH_MAY_JS']
                                         : 'May',
                                 isset($dot_text['MONTH_JUNE_JS'])
                                         ? $dot_text['MONTH_JUNE_JS']
                                         : 'June',
                                 isset($dot_text['MONTH_JULY_JS'])
                                         ? $dot_text['MONTH_JULY_JS']
                                         : 'July',
                                 isset($dot_text['MONTH_AUGUST_JS'])
                                         ? $dot_text['MONTH_AUGUST_JS']
                                         : 'August',
                                 isset($dot_text['MONTH_SEPTEMBER_JS'])
                                         ? $dot_text['MONTH_SEPTEMBER_JS']
                                         : 'September',
                                 isset($dot_text['MONTH_OCTOBER_JS'])
                                         ? $dot_text['MONTH_OCTOBER_JS']
                                         : 'October',
                                 isset($dot_text['MONTH_NOVEMBER_JS'])
                                         ? $dot_text['MONTH_NOVEMBER_JS']
                                         : 'November',
                                 isset($dot_text['MONTH_DECEMBER_JS'])
                                         ? $dot_text['MONTH_DECEMBER_JS']
                                         : 'December');
            $month_short_names = array(isset($dot_text['MONTH_SHORT_JANUARY_JS'])
                                               ? $dot_text['MONTH_SHORT_JANUARY_JS']
                                               : 'Jan',
                                       isset($dot_text['MONTH_SHORT_FEBRUARY_JS'])
                                               ? $dot_text['MONTH_SHORT_FEBRUARY_JS']
                                               : 'Feb',
                                       isset($dot_text['MONTH_SHORT_MARCH_JS'])
                                               ? $dot_text['MONTH_SHORT_MARCH_JS']
                                               : 'Mar',
                                       isset($dot_text['MONTH_SHORT_APRIL_JS'])
                                               ? $dot_text['MONTH_SHORT_APRIL_JS']
                                               : 'Apr',
                                       isset($dot_text['MONTH_SHORT_MAY_JS'])
                                               ? $dot_text['MONTH_SHORT_MAY_JS']
                                               : 'May',
                                       isset($dot_text['MONTH_SHORT_JUNE_JS'])
                                               ? $dot_text['MONTH_SHORT_JUNE_JS']
                                               : 'Jun',
                                       isset($dot_text['MONTH_SHORT_JULY_JS'])
                                               ? $dot_text['MONTH_SHORT_JULY_JS']
                                               : 'Jul',
                                       isset($dot_text['MONTH_SHORT_AUGUST_JS'])
                                               ? $dot_text['MONTH_SHORT_AUGUST_JS']
                                               : 'Aug',
                                       isset($dot_text['MONTH_SHORT_SEPTEMBER_JS'])
                                               ? $dot_text['MONTH_SHORT_SEPTEMBER_JS']
                                               : 'Sep',
                                       isset($dot_text['MONTH_SHORT_OCTOBER_JS'])
                                               ? $dot_text['MONTH_SHORT_OCTOBER_JS']
                                               : 'Oct',
                                       isset($dot_text['MONTH_SHORT_NOVEMBER_JS'])
                                               ? $dot_text['MONTH_SHORT_NOVEMBER_JS']
                                               : 'Nov',
                                       isset($dot_text['MONTH_SHORT_DECEMBER_JS'])
                                               ? $dot_text['MONTH_SHORT_DECEMBER_JS']
                                               : 'Dec');

            /*
             * Get date pieces.
             */
            $date_pieces = explode('-',
                                   $date);
            $day = isset($date_pieces[2])
                    ? $date_pieces[2]
                    : '01';
            $month = $date_pieces[1];
            $year = $date_pieces[0];

            /*
             * Set day.
             * DD, D
             */
            $patern = str_replace('[DD]',
                                  $day,
                                  $patern);
            $patern = str_replace('[D]',
                                  (int)$day,
                                  $patern);

            /*
             * Set month.
             * MM, M, mm, m
             */
            $patern = str_replace('[MM]',
                                  $month,
                                  $patern);
            $patern = str_replace('[M]',
                                  (int)$month,
                                  $patern);
            $patern = str_replace('[mm]',
                                  $month_names[(int)$month-1],
                                  $patern);
            $patern = str_replace('[m]',
                                  $month_short_names[(int)$month-1],
                                  $patern);

            /*
             * Set year.
             * YYYY, YY
             */
            $patern = str_replace('[YYYY]',
                                  $year,
                                  $patern);
            $patern = str_replace('[YY]',
                                  substr($year,
                                         -2),
                                  $patern);

            return $patern;
        }

        /*
         * String & numbers.
         */

        /*
         * Parses a code type text to be displayed correctly.
     *
     * @usage
     *	    Reserved framework function that will be called by DOT application.
     *
     *	    In FILE search for function call: $this->code
     *	    In FILE search for function call in hooks: array(&$this, 'code')
     *	    In PROJECT search for function call: $DOT->prototypes->code
     *
         * @params
         *	    code (string): the text
     *
     * @post
     *	    -
     *
     * @get
     *	    -
     *
     * @sessions
     *	    -
     *
     * @cookies
     *	    -
     *
     * @constants
     *	    -
     *
     * @globals
     *	    -
     *
     * @functions
     *	    -
     *
     * @hooks
     *	    -
     *
     * @layouts
     *	    -
     *
     * @return
     *	    The parsed text.
     *
     * @return_details
     *	    -
     *
     * @dv
     *	    -
     *
     * @tests
     *	    -
         */
        function code($code){
            return nl2br(str_replace(' ',
                                     '&nbsp;',
                                     htmlspecialchars($code)));
        }

        /*
         * Create a permalink from a string.
         *
         * @usage
         *	    Reserved framework function that will be called by DOT application.
         *
         *	    In FILE search for function call: $this->permalink
         *	    In FILE search for function call in hooks: array(&$this, 'permalink')
         *	    In PROJECT search for function call: $DOT->prototypes->permalink
         *
             * @params
             *	    string (string): the string
         *
         * @post
         *	    -
         *
         * @get
         *	    -
         *
         * @sessions
         *	    -
         *
         * @cookies
         *	    -
         *
         * @constants
         *	    -
         *
         * @globals
         *	    -
         *
         * @functions
         *	    -
         *
         * @hooks
         *	    -
         *
         * @layouts
         *	    -
         *
         * @return
         *	    The permalink slug.
         *
         * @return_details
         *	    All non alphanumeric characters are deleted; spaces [ ] and underscore [_] characters are replaced with hyphens [-].
         *
         * @dv
         *	    -
         *
         * @tests
         *	    -
             */
        function permalink($string){
            $string = preg_replace('/[~`!@#$%^&*()+={}\[\]|\\:;"\'<,>.?\/€]/u',
                                   '',
                                   $string);
            $string = preg_replace('/[ ]/u',
                                   '-',
                                   $string);
            $string = preg_replace('/[_]/u',
                                   '-',
                                   $string);
            $string = strtolower($string);

            return $string;
        }

        /*
         * Creates a string with random characters.
     *
     * @usage
     *	    Reserved framework function that will be called by DOT application.
     *
     *	    In FILE search for function call: $this->random
     *	    In FILE search for function call in hooks: array(&$this, 'random')
     *	    In PROJECT search for function call: $DOT->prototypes->random
     *
         * @params
         *	    length (integer): the length of the returned string
         *	    allowed_characters (string): the string of allowed characters; by default only alphanumeric characters are allowed
     *
     * @post
     *	    -
     *
     * @get
     *	    -
     *
     * @sessions
     *	    -
     *
     * @cookies
     *	    -
     *
     * @constants
     *	    -
     *
     * @globals
     *	    -
     *
     * @functions
     *	    -
     *
     * @hooks
     *	    -
     *
     * @layouts
     *	    -
     *
     * @return
     *	    Random string.
     *
     * @return_details
     *	    -
     *
     * @dv
     *	    -
     *
     * @tests
     *	    -
         */
        function random($length,
                        $allowed_characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz'){
            $random_string = '';

            for ($i = 0; $i<$length; $i++){
                $character_position = mt_rand(1,
                                              strlen($allowed_characters))-1;
                $random_string .= $allowed_characters[$character_position];
            }

            return $random_string;
        }

        /*
         * Email validation.
     *
     * @usage
     *	    Reserved framework function that will be called by DOT application.
     *
     *	    In FILE search for function call: $this->email
     *	    In FILE search for function call in hooks: array(&$this, 'email')
     *	    In PROJECT search for function call: $DOT->prototypes->email
     *
         * @params
         *	    email (string): email to be checked
     *
     * @post
     *	    -
     *
     * @get
     *	    -
     *
     * @sessions
     *	    -
     *
     * @cookies
     *	    -
     *
     * @constants
     *	    -
     *
     * @globals
     *	    -
     *
     * @functions
     *	    -
     *
     * @hooks
     *	    -
     *
     * @layouts
     *	    -
     *
     * @return
     *	    If the email is valid "true" is returned, "false" if its not.
     *
     * @return_details
     *	    -
     *
     * @dv
     *	    -
     *
     * @tests
     *	    -
         */
        function email($email){
            if (preg_match("/^[a-z0-9&\'\.\-_\+]+@[a-z0-9\-]+\.([a-z0-9\-]+\.)*+[a-z]{2}/is",
                           $email)){
                return true;
            }
            else{
                return false;
            }
        }

        function hours($hour1,
                       $hour2){
            $hour1 = explode(':',
                             $hour1);
            $hour2 = explode(':',
                             $hour2);
            $hours1 = intval($hour1[0]);
            $minutes1 = intval($hour1[1]);
            $seconds1 = isset($hour1[2])
                    ? intval($hour1[2])
                    : 0;
            $hours2 = intval($hour2[0]);
            $minutes2 = intval($hour2[1]);
            $seconds2 = isset($hour2[2])
                    ? intval($hour2[2])
                    : 0;

            $time1 = $hours1+$minutes1/60+$seconds1/60/60;
            $time2 = $hours2+$minutes2/60+$seconds2/60/60;

            return abs($time1-$time2);
        }
    }
}