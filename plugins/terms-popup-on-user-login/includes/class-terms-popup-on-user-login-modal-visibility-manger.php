<?php

use WpLHLAdminUi\LicenseKeys\LicenseKeyHandler;
use WpLHLAdminUi\Logger\TxtLogger;

class TPUL_Moddal_Visibility_Manager {
    private $generals_options;

    private $modal_options;
    private $terms_options_data;

    private $terms_modal_show_only_once = false;

    private $woo_options;
    private $woo_handler;

    private $terms_woo_options_data;

    private $popup_type;

    private $license_is_active;
    public $is_designated_test_user = false;

    private $debug = false;
    private $debug_log_to_file = true;
    private $debug_txt_logger;
    private $debug_current_user = '';


    public function __construct() {

        $this->is_designated_test_user = false;

        /**
         * Get Options on what to show if anything
         */
        $gen_options = new TPUL_General_Options();
        $this->generals_options = $gen_options->get_options();

        /**
         * Get Options on what to show if anything
         */
        $this->modal_options = new TPUL_Modal_Options();
        $this->terms_options_data = $this->modal_options->get_options();

        /**
         * Get Options for WooCommerce Popup Options
         */
        $this->woo_options = new TPUL_Woo_Options();
        $this->terms_woo_options_data = $this->woo_options->get_options();

        /**
         * Get popup type
         */
        $this->popup_type = new TPUL_Popup_Type();

        /**
         * Get Woo Connector
         */
        $this->woo_handler = new TPUL_Woo_Connector();

        /**
         * License Key handler
         */
        $license_key_handler = new LicenseKeyHandler(new TPUL_LicsenseKeyDataProvider());;
        $this->license_is_active = $license_key_handler->is_active();

        /**
         * Debug Logging into a file
         */
        $this->debug_txt_logger = new TxtLogger("terms-popup-on-user-login-log", 'decision_log.txt');


        if ($this->modal_options->get_txt_logger()) {
            $this->debug_log_to_file = true;
        }
        if ($this->debug_log_to_file) {

            $current_user = wp_get_current_user();
            $current_datetime = current_time('Y-m-d H:i:s');
            if ($current_user->ID != 0) {
                // User is logged in
                $this->debug_current_user = "[" . str_replace('admin', '', $current_user->user_login) . "]:";
            } else {
                $this->debug_current_user = "[empty]:";
            }
        }
    }


    public function should_modal_render() {

        /**
         * 1 second cache
         * as this function may be called multiple times throughout a sitebuild process
         * we cache it as YES or No
         * but we return true or false
         */

        $cached_data = get_transient('TPUL_transient_should_modal_render');
        // if ($this->debug) error_log(" Cache READ = " . $cached_data);

        if (false === $cached_data) {
            // TEMP remove cached data and always get it fresh
            // if (true) {

            // Get it with logic
            $should_popup_render =  $this->should_modal_render_logic($this->debug);
            if ($this->debug) error_log(" Cache MISS X-> should_popup_render:  " . $should_popup_render);

            // Store the data in the cache for 1 second
            // as yes or no
            if (false === $should_popup_render) {
                $set_cached_data = "no";
            } else {
                $set_cached_data = "yes";
            }
            // Remember for 1 second
            set_transient('TPUL_transient_should_modal_render', $set_cached_data, 1);
        } else {

            if ('yes' === $cached_data) {
                $should_popup_render = true;
            } else {
                $should_popup_render =  false;
            }
            // if ($this->debug) error_log(" Cache HIT --> should_popup_render:  " . $should_popup_render);
        }

        /**
         * Allow for override for active license holders
         */
        if ($this->license_is_active) {
            $old_should_popup_render = $should_popup_render;
            $should_popup_render = apply_filters('tpul_override_show_popup', $should_popup_render);
            if ($this->debug_log_to_file && $should_popup_render !== $old_should_popup_render) {
                $this->debug_txt_logger->write_log($this->debug_current_user . "Decision: {$should_popup_render}" . " -- Reason: Decision overriden by custom hook.");
            }
        }

        return $should_popup_render;
    }

    public function should_modal_render_logic() {

        /**
         * Logic for Terms popup on user login
         */

        /*******************************************************
         * FAST CHECKS
         *******************************************************/

        // if not woo modal and not logged in 
        if (!$this->popup_type->is_woo_modal() && !is_user_logged_in()) {
            if ($this->debug_log_to_file) {
                $this->debug_txt_logger->write_log($this->debug_current_user . "Decision: false" . " -- Reason: Not Woo modal and user is not logged in.");
            }
            return false;
        }

        /**
         * If logged in 
         * Init variables
         */
        if (is_user_logged_in()) {
            $this->is_designated_test_user = $this->modal_options->is_designated_test_user();
        }

        /**
         * Is designated test user and license is active
         */
        if ($this->is_designated_test_user && $this->license_is_active) {
            if ($this->debug) error_log(print_r("is_designated_test_user", true));
            if ($this->debug_log_to_file) {
                $this->debug_txt_logger->write_log($this->debug_current_user . "Decision: true" . " -- Reason: Is designated Test User.");
            }
            return true;
        }
        // OR TEST MODE ON
        elseif ($this->generals_options['modal_to_show'] == 'terms_and_conditions_modal_test') {
            if ($this->debug) error_log(print_r("terms_and_conditions_modal_test", true));
            if ($this->debug_log_to_file) {
                $this->debug_txt_logger->write_log($this->debug_current_user . "Decision: true" . " -- Reason: TEST mode is on.");
            }
            return true;
        }

        /********************************************************
         * END FAST CHECKS
         ********************************************************/


        // Init user state manager
        $user_id = get_current_user_id();
        $user_state_manager = new TPUL_User_State($user_id);
        $did_user_accept = $user_state_manager->did_user_accept();
        if ($this->debug) error_log("did_user_accept: " . $did_user_accept);

        /********************************************************
         * REGULAR CHECKS - LOGIN MODAL
         ********************************************************/

        /**
         * 0  - never accepted, never prompted
         * 1  - accepted but the terms have been updated since
         * 2  - accepted, and terms have not been updated since he accepted it, latest terms accepted for him
         * -1 - has accepted before, but has declined latest updated terms
         * -2 - declined the very first time
         */

        /**
         * A) If not in Test mode and 
         * not Designated Test User and
         * and is login modal
         */
        if ($this->popup_type->is_login_modal() && is_user_logged_in()) {

            // filter by role if hide for this role
            if (!$this->__should_show_for_this_role() && $this->license_is_active) {
                // if ($this->debug) error_log("__should_show_for_this_role");
                if ($this->debug_log_to_file) {
                    $this->debug_txt_logger->write_log($this->debug_current_user . "Decision: false" . " -- Reason: Not to show for this user role.");
                }
                return false;
            }

            /**
             * A1. 
             * SHOW on every login - was selected             
             * If show at every single login option is set we should show modal even if it was accepted before
             * we only care about accept in this session
             */

            if (
                !empty($this->terms_options_data['terms_modal_show_every_login'])
            ) {

                $accepted_for_this_session = $user_state_manager->get_user_acc_for_this_session();
                if ($this->debug) error_log("has_accepted_terms_this_session: " . $accepted_for_this_session);
                if ($user_state_manager->did_user_take_action_this_session()) {
                    // user accepted for this session   
                    if ($this->debug_log_to_file) {
                        $this->debug_txt_logger->write_log($this->debug_current_user . "Decision: false" . " -- Reason: User already taken action for this session.");
                    }
                    return false;
                }
                // everyone else
                if ($this->debug_log_to_file) {
                    $this->debug_txt_logger->write_log($this->debug_current_user . "Decision: true" . " -- Reason: User has not yet accepted this Session, Show on every login was selected.");
                }
                return true;
            }

            /**
             * A2. 
             * Show even for accepted was not selected
             * we check is user accepted before is so we do not show popup
             * We will use regular stored meta variables
             * regular accepted case
             */
            if ($user_state_manager->did_accept_latest_terms()) {
                // User seen popup and status = 2 meaning they accepted latest
                if ($this->debug_log_to_file) {
                    $this->debug_txt_logger->write_log($this->debug_current_user . "Decision: false" . " -- Reason: User has accepted latest terms and OPTION show on every login was not selected by admin.");
                }
                return false;
            }

            /**
             *  A3. SHOW on every login but only to those who have not accepted it yet 
             */

            if (!empty($this->terms_options_data['terms_modal_show_every_login_for_declined']) && $this->license_is_active) {

                if ($user_state_manager->did_accept_latest_terms()) {
                    if ($this->debug_log_to_file) {
                        $this->debug_txt_logger->write_log($this->debug_current_user . "Decision: false" . " -- Reason:Show only for users who have not accepted is checked, thi user already accepted");
                    }
                    return false;
                } else {

                    // User did not accept latest
                    if (!empty($this->terms_options_data['terms_modal_decline_nologout'])) {

                        // IF do not force logout is on
                        // show the popup but only if they have not taken action this session
                        if ($user_state_manager->did_user_take_action_this_session()) {
                            // user has taken some action
                            // leave them as is we will popup on next login
                            if ($this->debug_log_to_file) {
                                $this->debug_txt_logger->write_log($this->debug_current_user . "Decision: false" . " -- Reason: Show on every login for those who did not accept. User did not accept but has taken some action this session. Do not Logout on Decline option is on.");
                            }
                            return false;
                        } else {
                            if ($this->debug_log_to_file) {
                                $this->debug_txt_logger->write_log($this->debug_current_user . "Decision: true" . " -- Reason: Show on every login for those who did not accept. User did not accept and has not taken action this session. Do not Logout on Decline option is on.");
                            }
                            return true;
                        }
                    } else {
                        // do not force logout is off
                        // keep showing them the popup
                        // technically they should be forced logged out anyway
                        if ($this->debug_log_to_file) {
                            $this->debug_txt_logger->write_log($this->debug_current_user . "Decision: true" . " -- Reason: Show on every login for those who did not accept is on. Do not force logout is not on. User did not accept.");
                        }
                        return true;
                    }
                }
            }

            /**
             * A4.
             * Do not show popup after any initial action is ON
             * we check if user interacted in any way
             * if so we do not show popup
             */
            $this->terms_modal_show_only_once = $this->modal_options->get_show_only_once();

            if ($this->terms_modal_show_only_once && $this->license_is_active) {
                if ($this->debug) error_log("terms_modal_show_only_once");

                /**
                 * Check if user interacted with this modal in any way
                 */
                $user_seen_this_popup = $user_state_manager->did_user_act_after_reset();
                if ($user_seen_this_popup) {
                    if ($this->debug_log_to_file) {
                        $this->debug_txt_logger->write_log($this->debug_current_user . "Decision: false" . " -- Reason: User has taken some action on this popup at some point and OPTION Do not throw popup if user already seen it was set by admin.");
                    }
                    return false;
                }
            }

            /**
             * No special options are on
             * Show for everyone who has not accepted latest terms
             */
            if ($this->debug_log_to_file) {
                $this->debug_txt_logger->write_log($this->debug_current_user . "Decision true" . " -- Reason: User has not accepted latest terms No special options are on.");
            }
            return true;
        }
        /********************************************************
         * REGULAR CHECKS - WOOCOMMERCE POPUP
         ********************************************************/
        /**
         * B) Logic for WooCommerce popup
         */
        elseif ($this->popup_type->is_woo_modal() && $this->license_is_active) {
            if ($this->debug) error_log("is_woo_modal");

            $usertype_to_show = $this->terms_woo_options_data['terms_modal_woo_display_user_type'];
            $are_we_on_right_woo_page = false;

            // Check if page is selected to be shown
            if (
                $this->woo_options->get_display_on() == 'product_pages_and_category' &&
                $this->woo_handler->is_product_category() || $this->woo_handler->is_woo_product_page()
            ) {
                $are_we_on_right_woo_page = true;
                // is cart Page
            } elseif (($this->woo_options->get_display_on() == 'cart_page') &&
                $this->woo_handler->is_cart_page()
            ) {
                $are_we_on_right_woo_page = true;
            } elseif (($this->woo_options->get_display_on() == 'check_out_page') &&
                $this->woo_handler->is_checkout_page()
            ) {
                $are_we_on_right_woo_page = true;
            }


            if (!$are_we_on_right_woo_page) {
                if ($this->debug) error_log("we are not on the right page");
                if ($this->debug_log_to_file) {
                    $this->debug_txt_logger->write_log($this->debug_current_user . "Decision: false" . " -- Reason: WOO Option is on, but we are not on the right page.");
                }
                return false;
            }

            // Logged in user
            if (
                ($usertype_to_show == 'logged_in_only' || $usertype_to_show == 'anonymous_and_logged_in')
                && is_user_logged_in()
            ) {
                if (empty($did_user_accept) || $did_user_accept !== 2) {
                    if ($this->debug) error_log("logged in on right page");
                    if ($this->debug_log_to_file) {
                        $this->debug_txt_logger->write_log($this->debug_current_user . "Decision: true" . " -- Reason: WOO Option is on, user is logged in and has not accepted latest terms.");
                    }
                    return true;
                }
                /**
                 * User logged in and should show for logged in but user alredy accepted
                 */
                if ($this->debug_log_to_file) {
                    $this->debug_txt_logger->write_log($this->debug_current_user . "Decision: false" . " -- Reason: WOO Option is on, User logged in but already accepted latest terms.");
                }
                return false;
            }

            // Anon user
            if (
                ($usertype_to_show == 'anonymous_only' || $usertype_to_show == 'anonymous_and_logged_in') &&
                !is_user_logged_in()
            ) {
                if ($this->debug) error_log("not logged in but on right page as anon user annd should show for anon user");
                if ($this->debug_log_to_file) {
                    $this->debug_txt_logger->write_log($this->debug_current_user . "Decision: true" . " -- Reason: WOO Option is on, User not logged in but we are on a page where we should show the popup.");
                }
                return true;
            }

            // nothing out of the 3 were elesected anon_only,logged_in, annon_and_logged_in
            // I dont know how you got here
            if ($this->debug_log_to_file) {
                $this->debug_txt_logger->write_log($this->debug_current_user . "Decision: false" . " -- Reason: WOO Option is on, none of the options stuck. We dont know how we got here.");
            }
            return false;
        };

        /********************************************************
         * REGULAR CHECKS -  NOT LOGIN MODAL and NOT WOOMODAL - probably turned off
         ********************************************************/
        if ($this->debug_log_to_file) {
            $this->debug_txt_logger->write_log($this->debug_current_user . "Decision: false" . " -- Reason: Popup is turned off.");
        }
        // neither pop on user or woo option was selected
        return false;
    }


    function __should_show_for_this_role() {


        if (empty($this->terms_options_data['terms_modal_for_roles'])) {
            // if this was never specified show the modal,
            // probably just updated the plugin so this is not yet defined for them
            return true;
        }

        $user = wp_get_current_user();
        $the_roles = $user->roles;

        $to_show_roles = $this->terms_options_data['terms_modal_for_roles'];

        if (empty($to_show_roles) || in_array("all", $to_show_roles)) {
            // not defined yet or "show to all users" is selected as well
            return true;
        }

        $user = wp_get_current_user();
        $the_roles = (array) $user->roles;
        $roles = array_keys($the_roles);

        $common_roles = array_intersect($the_roles, $to_show_roles);
        if (count($common_roles) > 0) {
            // User has roles that were selected for the popup show
            return true;
        }

        return false;
    }
}
