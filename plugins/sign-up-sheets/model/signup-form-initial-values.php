<?php
/**
 * Sign-up Form Initial Values Model
 */

namespace FDSUS\Model;

use FDSUS\Id;
use FDSUS\Model\Sheet as SheetModel;
use FDSUS\Model\Signup as SignupModel;
use FDSUS\Model\Task as TaskModel;
use WP_User;
use WP_Error;

/**
 * Class SignupFormInitialValues
 *
 * @package FDSUS\Model
 */
class SignupFormInitialValues
{
    protected $formPost;
    protected $signup;
    protected $task;
    protected $sheet;

    /**
     * Constructor
     *
     * @param SheetModel  $sheet
     * @param TaskModel   $task
     * @param SignupModel $signup
     * @param array       $formPost
     */
    public function __construct($sheet, $task, $signup = null, $formPost = null)
    {
        $this->formPost = $formPost;
        $this->signup = $signup;
        $this->task = $task;
        $this->sheet = $sheet;
    }

    /**
     * Get initial
     *
     * @return array
     */
    public function get()
    {
        $initial = array(
            'firstname' => '',
            'lastname' => '',
            'email' => '',
            'phone' => '',
            'address' => '',
            'city' => '',
            'state' => '',
            'zip' => '',
        );

        if (!empty($this->signup)) {
            foreach ($initial as $key => $value) {
                if (isset($this->signup->{'dlssus_' . $key})) {
                    $initial[$key] = esc_attr($this->signup->{'dlssus_' . $key});
                }
            }
            reset($initial);
        }

        if (!empty($this->formPost)) {
            foreach ($initial as $key => $value) {
                if (isset($this->formPost['signup_' . $key])) {
                    $initial[$key] = esc_attr($this->formPost['signup_' . $key]);
                }
            }
            reset($initial);
        }

        /**
         * Filter for initial values of sign-up form fields
         *
         * @param array      $initial
         * @param SheetModel $sheet
         * @param TaskModel  $task
         * @param array      $formPost
         *
         * @return array
         * @since 2.2
         */
        $initial = apply_filters('fdsus_initial_signup_form_values', $initial, $this->sheet, $this->task, $this->signup, $this->formPost);

        // If not set, but logged in, pull from user
        if (!is_admin() && !Settings::isUserAutopopulateDisabled()) {
            $currentUser = wp_get_current_user();
            if (($currentUser instanceof WP_User)) {
                if (empty($initial['firstname'])) {
                    $initial['firstname'] = $currentUser->user_firstname;
                }
                if (empty($initial['lastname'])) {
                    $initial['lastname'] = $currentUser->user_lastname;
                }
                if (empty($initial['email'])) {
                    $initial['email'] = $currentUser->user_email;
                }
            }
        }

        return $initial;
    }
}
