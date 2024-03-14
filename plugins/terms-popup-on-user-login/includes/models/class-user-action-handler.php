<?php

/**
 * returns
 * false - not logged in
 * 0  - never accepted, never prompted
 * 1  - accepted but the terms have been updated since
 * 2  - accepted, and terms have not been updated since he accepted it, latest terms accepted for him
 * -1 - has accepted before, but has declined latest updated terms
 * -2 - declined the very first time
 *
 */

class TPUL_User_State {

    private $debug = false;
    private $user_id = false;
    private $user_state = false;
    private $last_user_action = false;

    public function __construct($user_id) {
        $this->user_id = $user_id;
        $this->user_state = get_user_meta($user_id, 'tpul_user_accepted_terms', true);
        if (is_null($this->user_state) || empty($this->user_state)) {
            $this->user_state = 0;
        }
        $this->user_state = intval($this->user_state);
        $this->last_user_action = get_user_meta($user_id, 'tpul_last_user_action', true);
    }

    /**
     * ID
     */
    public function get_user_id() {
        return $this->user_id;
    }

    /**
     * Acceptance State
     */
    public function get_user_state() {
        return $this->user_state;
    }

    /**
     * Set Acceptance State
     */
    public function set_user_state($state) {
        $this->user_state = $state;
        update_user_meta($this->user_id, 'tpul_user_accepted_terms', $state);
    }

    /**
     * Get user agent
     */
    public function get_recorded_useragent() {
        return get_user_meta($this->user_id, 'tpul_user_accepted_useragent', true);
    }

    /**
     * Get user agent
     */
    public function get_location_coordinates() {
        return get_user_meta($this->user_id, 'tpul_user_location_coordinates', true);
    }

    /**
     * Get user agent
     */
    public function get_clientIP() {
        return get_user_meta($this->user_id, 'tpul_user_clientIP', true);
    }

    /**
     * This session data
     * this data on the user is used if popup on every login is turned on
     * we set this at acceptance
     * we clear it on reset
     * clear it on decline
     * clear it on logout
     * they have to accept again after login
     */

    public function get_user_acc_for_this_session() {
        return get_user_meta($this->user_id, 'tpul_user_acc_for_this_session', true);
    }
    public function did_user_take_action_this_session() {
        $this_session_action = $this->get_user_acc_for_this_session();
        if ($this_session_action == 0) {
            return false;
        }
        return true;
    }

    public function clear_acceptance_for_this_session() {
        update_user_meta($this->user_id, 'tpul_user_acc_for_this_session', 0);
    }

    /**
     *  SET User Last Action
     *   0 - if no action was taken ever 
     *   1 - if no user action was taken since reset
     * -10 - if last user action was decline since reset
     * +10 - if last user action was accept since reset
     */

    public function set_user_last_action($last_user_action) {
        $this->last_user_action = $last_user_action;
        update_user_meta($this->user_id, 'tpul_last_user_action', $last_user_action);
    }

    public function get_user_last_action() {
        return get_user_meta($this->user_id, 'tpul_last_user_action', true);
    }

    /**
     * Checks if user did any actions after a reset
     */
    public function did_user_act_after_reset() {
        $last_user_action = get_user_meta($this->user_id, 'tpul_last_user_action', true);
        if ($this->debug) error_log('last_user_action: ' . $last_user_action);

        if ($last_user_action === false) {
            // Old version of plugin was used we have no record
            // we compare reset date and action date
            $last_reset_ran = 0;
            $reset_info = get_option('tpul_settings_term_modal_reset_info');
            if (!empty($reset_info)) {
                if (!empty($reset_info['last_ran'])) {
                    $last_reset_ran = $reset_info['last_ran'];
                    $last_user_action_date = $this->get_user_accepted_date_raw();
                    if ($last_user_action_date > $last_reset_ran) {
                        return true;
                    }
                }
            }
            return false;
        } else {
            // new version of plugin
            // We make tecord of this as user action happens
            // no need to compare dates
            // see $this->set_user_last_action
            if (!empty($last_user_action)) {
                if ($last_user_action == -10 || $last_user_action == 10) {
                    return true;
                }
                return false;
                // it's either 0 and no action was ever taken
                // or its 1 and it was reset since last action
            }
        }

        return false;
    }

    /**
     * Date
     */
    public function set_user_accepted_date($last_user_action) {
        //$this->last_user_action = $last_user_action;
        //update_user_meta($this->user_id, 'tpul_last_user_action', $last_user_action);
    }

    public function get_user_accepted_date_raw() {
        $tpul_user_accepted_terms_date = get_user_meta($this->user_id, 'tpul_user_accepted_terms_date', true);
        return $tpul_user_accepted_terms_date;
    }

    public function get_user_accepted_date() {

        $user_accepted_date = "";
        $tpul_user_accepted_terms_date = get_user_meta($this->user_id, 'tpul_user_accepted_terms_date', true);
        // Format dete to the website preferred format
        if (!empty($tpul_user_accepted_terms_date)) {
            $date_format = get_option('date_format');
            $time_format = get_option('time_format');
            $user_accepted_date = wp_date("{$date_format} {$time_format}", $tpul_user_accepted_terms_date);
        }

        return $user_accepted_date;
    }

    /********************************************
     * Actions
     ********************************************/

    /**
     * helper to flatten array
     */
    public function _flattenArrayToString($array, $separator) {
        return implode($separator, array_map(
            function ($key, $value) {
                $value = str_replace(',', ' ', $value);
                return "{$key}:{$value}";
            },
            array_keys($array),
            $array
        ));
    }

    /**
     * ACCEPTs
     */
    public function useraction_accepted($options = []) {

        // Mark as terms as accepted
        // update_user_meta($user_id, 'tpul_user_accepted_terms', 2);
        $this->set_user_state(2);
        // this last action is accept
        $this->set_user_last_action(10);

        // Mark the Time
        $the_time = time();
        update_user_meta($this->user_id, 'tpul_user_accepted_terms_date', $the_time);

        // Mark the Browser UserAgent
        if (!empty($options['useragent'])) {
            update_user_meta($this->user_id, 'tpul_user_accepted_useragent', strip_tags($options['useragent']));
        }
        if (!empty($options['locationCoordinates'])) {
            $flattened_locationCoordinates = json_encode($options['locationCoordinates']);
            update_user_meta($this->user_id, 'tpul_user_location_coordinates', '"' . $flattened_locationCoordinates . '"');
        }
        if (!empty($options['clientIP'])) {
            $flattened_client_ip = json_encode($options['clientIP']);
            update_user_meta($this->user_id, 'tpul_user_clientIP', '"' . $flattened_client_ip . '"');
        }

        /**
         * Mark as accepted for this session
         *  This is needed if the show on every login is turned on
         *  We mark it as accepted for this session, but on logout this will be cleared
         *  so when they log back in it will be needed again
         *  for consistency I use 2
         */
        update_user_meta($this->user_id, 'tpul_user_acc_for_this_session', 2);
    }

    /**
     * DECLINE
     */
    public function useraction_declined() {
        $this->set_user_last_action(-10);
        switch ($this->user_state) {
            case 0:
                // never seen it
                // declining now
                $this->set_user_state(-2);
                break;
            case -2:
                // declined first go
                // declining now
                $this->set_user_state(-2);
                break;
            case 2:
                // accepted first go
                // declining now
                $this->set_user_state(-1);
                break;
            case -1:
                // accepted first go
                // then got reset
                // then declined second time
                // declining now as well
                $this->set_user_state(-1);
                break;
            case 1:
                // accepted at some point
                // then got reset
                // declining now
                $this->set_user_state(-1);
                break;

            default:
                # code...
                break;
        }
        /**
         * When Check at every login checkbox is checked
         * the plugin is looking at this variable not "tpul_user_accepted_terms"
         * this needs to be cleared as well
         */
        update_user_meta($this->user_id, 'tpul_user_acc_for_this_session', -2);
    }
    public function useraction_reset() {
        $this->set_user_last_action(1);

        switch ($this->user_state) {
            case 0:
                // never seen it
                // resetting now
                $this->set_user_state(0);
                break;
            case -2:
                // declined first go
                // resetting now
                $this->set_user_state(-2);
                break;
            case 2:
                // accepted first go
                // resetting now
                $this->set_user_state(1);
                break;
            case -1:
                // accepted first go
                // then declined
                // declining now as well
                $this->set_user_state(-1);
                break;
            case 1:
                // accepted at some point
                // then got reset
                // never seen it after reset
                // resetting again now
                $this->set_user_state(1);
                break;

            default:
                # code...
                break;
        }

        /**
         * When Check at every login checkbox is checked
         * the plugin is looking at this variable not "tpul_user_accepted_terms"
         * this needs to be cleared as well
         */
        update_user_meta($this->user_id, 'tpul_user_acc_for_this_session', 0);
    }

    /**
     * Checks if user accepted terms
     * can give full answer as 2,1,0,-1,-2 as the state
     * or binary answer of 1 or 0
     */
    public function has_accepted_terms($bool_answer = false) {

        $user_accepted_check = 0;
        $user_accepted_terms = get_user_meta($this->user_id, 'tpul_user_accepted_terms', true);

        if (!empty($user_accepted_terms)) {

            /**
             * if 1 we need to handle for date
             * older version of plugin where there was no date it meant not accepted
             */
            if ($user_accepted_terms == 1) {
                /**
                 * plugin compatability issue fix
                 * older versions of plugin "1" used to mean not accepted (accepted but was reset)
                 */
                $tpul_user_accepted_terms_date = get_user_meta($this->user_id, 'tpul_user_accepted_terms_date', true);
                if (!empty($tpul_user_accepted_terms_date)) {
                    // if date also exists, they tryly did accept the temrs
                    $user_accepted_check = 1;
                } else {
                    // If not they actualy never did
                    $user_accepted_check = 0;
                }
            } else {
                $user_accepted_check = intval($user_accepted_terms);
            }
        }

        /**
         * fast 1 or 0 answer
         */
        if ($bool_answer) {
            // [-2, -1, -0, 1, 2]
            return $user_accepted_check > 0 ? 1 : 0;
        }

        return $user_accepted_check;
    }

    /**
     * Returns user accepted terms
     * in human language not at state variables
     */
    public function get_has_accepted_terms_labels() {

        $label = "";

        switch ($this->user_state) {

            case 2:

                $label = "&#x2713 " . __('Latest Terms Accepted', 'terms-popup-on-user-login');
                break;

            case 1:

                $label = "&nbsp;&nbsp;&nbsp;&nbsp;" . __('Accepted on', 'terms-popup-on-user-login');
                break;

            case -1:

                $label = "&nbsp;&nbsp;&nbsp;&nbsp;" . __('Latest Terms Declined', 'terms-popup-on-user-login');
                break;

            case -2:

                $label = "&nbsp;&nbsp;&nbsp;&nbsp;" . __('Declined', 'terms-popup-on-user-login');
                break;

            case 0:
            default:
                $label = "";
                break;
        }

        return $label;
    }

    /**
     * Checks if user accepted terms even after the last reset
     */
    public function did_accept_latest_terms() {

        $user_accepted_terms = $this->get_user_state();

        if (empty($user_accepted_terms)) {
            return false;
        }
        if ($user_accepted_terms == 2) {
            return true;
        }
        return false;
    }


    /**
     * returns
     * false - not logged in
     * 0  - never accepted, never prompted
     * 1  - accepted but the terms have been updated since
     * 2  - accepted, and terms have not been updated since he accepted it, latest terms accepted for him
     * -1 - has accepted before, but has declined latest updated terms
     * -2 - declined the very first time
     *
     */
    function did_user_accept() {

        if (!is_user_logged_in()) {
            return false;
        }
        $did_user_accept = $this->get_user_state();

        return $did_user_accept;
    }
}
