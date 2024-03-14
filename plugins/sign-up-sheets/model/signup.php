<?php
/**
 * Sign-up Model
 */

namespace FDSUS\Model;

use FDSUS\Id;
use FDSUS\Lib\Exception;
use FDSUS\Model\Sheet as SheetModel;
use FDSUS\Model\Signup as SignupModel;
use FDSUS\Model\Task as TaskModel;
use WP_Post;
use WP_Error;

if (Id::isPro()) {
    class SignupParent extends Pro\Signup {}
} else {
    class SignupParent extends SignupBase {}
}

/**
 * Class Signup
 *
 * @property int    ID
 * @property int    post_parent
 * @property string post_date
 * @property string post_modified
 * @property string dlssus_firstname
 * @property string dlssus_lastname
 * @property string dlssus_email
 * @property string dlssus_phone
 * @property string dlssus_address
 * @property string dlssus_city
 * @property string dlssus_state
 * @property string dlssus_zip
 * @property int    dlssus_user_id
 * @property string dlssus_removal_token
 * @property string dlssus_reminded
 *
 * @package FDSUS\Model
 */
class Signup extends SignupParent
{
    /** @var WP_Post */
    protected $_data;

    /** @var TaskModel Don't call directly, use getTask() instead */
    protected $task;

    /** @var string  */
    private static $defaultBaseSlug = 'signup';

    /** @var string */
    const POST_TYPE = 'dlssus_signup';

    /**
     * Constructor
     *
     * @param int|WP_Post    $signup id or post object
     * @param null|TaskModel $task   to allow setting the task on the object if already known
     */
    public function __construct($signup = 0, $task = null)
    {
        if (!empty($signup)) {
            $this->init($signup);
        }

        if (!empty($task) && $task instanceof TaskModel) {
            $this->task = $task;
        }

        parent::__construct();
    }

    /**
     * Init
     *
     * @param int|WP_Post $signup id or post object
     */
    protected function init($signup)
    {
        $this->_data = $this->get($signup);
    }

    /**
     * Get Base Slug
     *
     * @return string|false
     */
    public static function getBaseSlug()
    {
        return self::$defaultBaseSlug;
    }

    /**
     * Get name (plural or singular)
     *
     * @param bool $singular to retrieve singular rather than plural
     *
     * @return string
     */
    public static function getName($singular = false)
    {
        return $singular ? __('Sign-up', 'fdsus') : __('Sign-ups', 'fdsus');
    }

    /**
     * Get single sign-up
     *
     * @param int|WP_Post $id signup id or object
     *
     * @return mixed signup
     */
    public function get($id)
    {
        $post = get_post($id);
        if (!is_a($post, 'WP_Post') || get_post_type($post->ID) != self::POST_TYPE) {
            return false;
        }
        $this->setFields($post);

        return $post;
    }

    /**
     * Add a new signup to a task
     *
     * @param array $fields       array of fields and values to insert
     * @param int   $taskId
     * @param bool  $bypassChecks bypass general frontend checks like duplicate sign-ups
     *
     * @return int|bool
     * @throws Exception
     */
    public function add($fields, $taskId, $bypassChecks = false)
    {
        $cleanFields = $this->cleanArray($fields, 'signup_');
        $cleanFields['task_id'] = $taskId;
        $cleanFields['date_created'] = (!empty($fields['date_created'])) ? $fields['date_created'] : date('Y-m-d H:i:s');

        // Check if signup spots are filled
        $task = new TaskModel($taskId);
        $sheet = new SheetModel($task->post_parent);
        $signups = $task->getSignups();
        if (!$bypassChecks && count($signups) >= $task->dlssus_qty) {
            throw new Exception(
                sprintf(
                    /* translators: %1$s is replaced with task title and %2$s is replaced with optional detailed errors if enabled */
                    esc_html__('Error adding signup for %1$s.  All spots are filled. %2$s', 'fdsus'),
                    '<em>' . wp_kses_post($task->post_title) . '</em>',
                    Settings::isDetailedErrors()
                        ? ' Current Signups: ' . count($signups) . ', Total Spots:' . $task->dlssus_qty : null
                )
            );
        }

        if (!$bypassChecks) {
            $validation = null;
            /**
             * Filter for validation prior to adding a new signup
             *
             * @param null|WP_Error $validation
             * @param array         $fields
             * @param SignupModel[] $signups
             * @param TaskModel     $task
             * @param SheetModel    $sheet
             *
             * @return array|WP_Error
             * @since 2.2
             */
            $validation = apply_filters('fdsus_add_signup_validation', $validation, $cleanFields, $signups, $task, $sheet);
            if (is_wp_error($validation)) {
                throw new Exception($validation->get_error_message());
            }
        }

        // Check if already signed up for task by email address
        if (!$bypassChecks
            && empty($fields['double_signup'])
            && $sheet->showEmail()
            && $task->isEmailOnTask($fields['signup_email'])
        ) {
            $msg = esc_html__('You have already signed up for this task.  Do you want to sign up again?', 'fdsus') . '
                <form method="post" action="' . $this->getCurrentUrl(true) . '">';

            foreach ($fields as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $v) {
                        $msg .= sprintf(
                                '<input type="hidden" name="%s[]" value="%s" />',
                                esc_attr($key),
                                esc_attr($v)
                            ) . PHP_EOL;
                    }
                } else {
                    $msg .= sprintf(
                            '<input type="hidden" name="%s" value="%s" />',
                            esc_attr($key),
                            esc_attr($value)
                        ) . PHP_EOL;
                }
            }
            reset($fields);

            $msg .= '
                    <input type="hidden" name="double_signup" value="1" />
                    <input type="hidden" name="mode" value="submitted" />
                    <input type="submit" name="Submit" class="button-primary wp-block-button__link wp-element-button dls-sus-double-signup-confirm-button" value="' . esc_html__('Yes, sign me up', 'fdsus') . '" />
                    <a href="' . fdsus_back_to_sheet_url($task->ID) . '">' . esc_html__('No, thanks', 'fdsus') . '</a>
                </form>
            ';

            throw new Exception($msg);
        }

        // Set User ID
        if (!is_admin()) {
            $user = wp_get_current_user();
            if ($user->exists()) {
                $cleanFields['user_id'] = $user->ID;
            }
        }

        // Add main signup
        $my_post = array(
            'post_title'  => $sheet->showEmail() ? $cleanFields['email'] : '',
            'post_type'   => self::POST_TYPE,
            'post_status' => 'publish',
            'post_parent' => $taskId,
        );
        $signupId = wp_insert_post($my_post, true);
        if (is_wp_error($signupId)) {
            throw new Exception(
                sprintf(
                    /* translators: %s is replaced with the sign-up email */
                    esc_html__('Error adding the sign-up for "%s"', 'fdsus'), esc_attr($cleanFields['email']))
                . (($this->detailed_errors === true) ? '.. ' . print_r($signupId->get_error_message(), true) : '')
            );
        }

        $this->updateMetaFields('add', $cleanFields, $signupId, $task, $sheet);

        if ($signupId) {
            /**
             * Action that runs after the signup is successfully added
             *
             * @param int $signupId
             *
             * @api
             * @since 2.2
             */
            do_action('fdsus_after_add_signup', $signupId, 0);
        }

        return $signupId;
    }

    /**
     * Delete a signup
     *
     * @param int $id optional signup ID if not already initialized
     *
     * @return   bool
     */
    public function delete($id = 0)
    {
        if (empty($id)) {
            $id = $this->ID;
        }
        $taskId = wp_get_post_parent_id($id);
        $result = wp_delete_post($id, true);

        if (!empty($result)) {
            /**
             * Action that runs after the signup is successfully deleted
             *
             * @param int $signupId
             *
             * @api
             * @since 2.2.7
             */
            do_action('fdsus_after_delete_signup', $id, $taskId);
        }

        return !empty($result);
    }

    /**
     * Update signup
     *
     * @param int   $signupId     Set to "0" if already set when instantiated
     * @param array $fields       array of fields and values to insert
     * @param int   $taskId
     * @param bool  $bypassChecks bypass general frontend checks like duplicate sign-ups
     *
     * @return int|bool
     * @throws Exception
     */
    public function update($signupId, $fields, $bypassChecks = false)
    {
        if (empty($signupId)) {
            $signupId = $this->ID;
        }

        $cleanFields = $this->cleanArray($fields, 'signup_');
        $cleanFields['date_updated'] = date('Y-m-d H:i:s');

        if (!$bypassChecks) {
            $validation = null;
            /**
             * Filter for validation prior to updating a signup
             *
             * @param null|WP_Error $validation
             * @param array         $fields
             *
             * @return array|WP_Error
             * @since 2.2.9
             */
            $validation = apply_filters('fdsus_update_signup_validation', $validation, $cleanFields);
            if (is_wp_error($validation)) {
                throw new Exception($validation->get_error_message());
            }
        }

        // Set User ID
        if (!is_admin()) {
            $user = wp_get_current_user();
            if ($user->exists()) {
                $cleanFields['user_id'] = $user->ID;
            }
        }

        // Update main signup
        $postarr = array(
            'ID'          => $signupId,
            'post_title'  => $cleanFields['email'] ? $cleanFields['email'] : '',
        );
        $signupId = wp_update_post($postarr, true);
        if (empty($signupId || is_wp_error($signupId))) {
            throw new Exception(
                sprintf(
                /* translators: %s is replaced with the sign-up email */
                    esc_html__('Error adding the sign-up for "%s"', 'fdsus'), esc_attr($cleanFields['email'])
                )
                . (($this->detailed_errors === true && is_wp_error($signupId)) ? '.. ' . print_r($signupId->get_error_message(), true) : '')
            );
        }

        $this->updateMetaFields('update', $cleanFields, $signupId);

        if ($signupId) {
            /**
             * Action that runs after the signup is successfully added
             *
             * @param int $signupId
             *
             * @api
             * @since 2.2
             */
            do_action('fdsus_after_update_signup', $signupId, 0);
        }

        return $signupId;
    }

    /**
     * Update meta fields on signup add or update
     *
     * @param string          $type
     * @param array           $fields
     * @param int             $signupId
     * @param TaskModel|null  $task
     * @param SheetModel|null $sheet
     *
     * @return void
     * @throws Exception
     */
    protected function updateMetaFields($type, $fields, $signupId, $task = null, $sheet = null)
    {
        // Set meta fields (custom and default)
        $signupMetaFields[] = array('slug' => 'user_id');
        $signupMetaFields[] = array('slug' => 'firstname');
        $signupMetaFields[] = array('slug' => 'lastname');
        $signupMetaFields[] = array('slug' => 'email');
        $signupMetaFields[] = array('slug' => 'phone');
        $signupMetaFields[] = array('slug' => 'address');
        $signupMetaFields[] = array('slug' => 'city');
        $signupMetaFields[] = array('slug' => 'state');
        $signupMetaFields[] = array('slug' => 'zip');
        $signupMetaFields[] = array('slug' => 'id_v2_0');

        if ($type === 'add') {
            /**
             * Filter for meta fields during add signup process
             *
             * @param array       $signupMetaFields
             * @param SignupModel $signup
             * @param TaskModel   $task
             * @param SheetModel  $sheet
             *
             * @return array
             * @since 2.2
             */
            $signupMetaFields = apply_filters('fdsus_add_signup_meta_fields', $signupMetaFields, $this, $task, $sheet);
        } elseif ($type === 'update') {
            /**
             * Filter for meta fields during update signup process
             *
             * @param array       $signupMetaFields
             * @param SignupModel $signup
             * @param null        $task
             * @param null        $sheet
             *
             * @return array
             * @since 2.2.9
             */
            $signupMetaFields = apply_filters('fdsus_update_signup_meta_fields', $signupMetaFields, $this, $task, $sheet);
        }

        $allowedMetaFields = array();
        foreach ($signupMetaFields as $field) {
            $slug = str_replace('-', '_', $field['slug']);
            if (isset($fields[$slug])) {
                $allowedMetaFields[Id::PREFIX . '_' . $slug] = $fields[$slug];
            } elseif (!empty($field['value'])) {
                $allowedMetaFields[Id::PREFIX . '_' . $slug] = $field['value'];
            }
        }
        reset($signupMetaFields);

        // Add meta fields
        foreach ($allowedMetaFields as $k => $v) {
            if (is_array(maybe_unserialize($v))) {
                $v = implode(',', maybe_unserialize($v));
            }
            update_post_meta($signupId, $k, $v);
        }
    }

    /**
     * Set SUS Fields
     *
     * @param WP_Post $post
     */
    public function setFields(&$post)
    {
        $metaFields = get_post_custom($post->ID);

        // Remove default "tasks" meta field, use posts instead
        unset($metaFields[Id::PREFIX . '_tasks']);

        if (!isset($post->dlssus_removal_token)) {
            $post->dlssus_removal_token = 0;
        }

        // Meta fields
        foreach ($metaFields as $key => $value) {
            if (strpos($key, Id::PREFIX . '_') === 0) {
                $post->{$key} = maybe_unserialize(current($value));
            }
        }
    }

    /**
     * Get Task
     *
     * @return TaskModel
     *
     * @since 2.2.4-beta
     */
    public function getTask()
    {
        if (!$this->task instanceof TaskModel) {
            $this->task = new TaskModel($this->post_parent);
        }

        return $this->task;
    }

    /**
     * Get the WP_Post object
     *
     * @return WP_Post
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * Is sheet data valid?
     *
     * @return bool
     */
    public function isValid()
    {
        return is_object($this->_data);
    }

    /**
     * Validate required fields
     *
     * @param array      $fields
     * @param SheetModel $sheet
     *
     * @return true|array true if it validates, otherwise returns array of missing fields
     */
    public static function validateRequiredFields($fields, $sheet)
    {
        $missingFieldNames = array();
        if (empty($fields['signup_firstname'])) {
            $missingFieldNames[] = esc_html__('First Name', 'fdsus');
        }
        if (empty($fields['signup_lastname'])) {
            $missingFieldNames[] = esc_html__('Last Name', 'fdsus');
        }
        if ($sheet->showEmail() && empty($fields['signup_email'])) {
            $missingFieldNames[] = esc_html__('E-mail', 'fdsus');
        }
        if ($sheet->isPhoneRequired() && $sheet->showPhone() && empty($fields['signup_phone'])) {
            $missingFieldNames[] = esc_html__('Phone', 'fdsus');
        }
        if ($sheet->isAddressRequired() && $sheet->showAddress()
            && (empty($fields['signup_address'])
                || empty($fields['signup_city'])
                || empty($fields['signup_state'])
                || empty($fields['signup_zip']))
        ) {
            $missingFieldNames[] = esc_html__('Address', 'fdsus');
        }

        /**
         * Filter error handling on sign-up form
         *
         * @param array      $missingFieldNames
         * @param SheetModel $sheet
         *
         * @return array
         * @since 2.2
         */
        $missingFieldNames = apply_filters('fdsus_sign_up_form_errors_required_fields', $missingFieldNames, $sheet);

        // TODO CAPTCHA - move to Captcha class
        if (!is_admin() && !Settings::isAllCaptchaDisabled() && !Settings::isRecaptchaEnabled() && empty($fields['spam_check'])) {
            $missingFieldNames[] = esc_html__('Math Question', 'fdsus');
        }

        return $missingFieldNames ? $missingFieldNames : true;
    }

    /**
     * Magic getter
     *
     * @param string $name
     * @return null
     */
    public function __get($name)
    {
        if (is_object($this->_data) && property_exists($this->_data, $name)) {
            // Clean Int
            if (in_array($name, array('ID', 'post_parent', 'dlssus_user_id'))) {
                return (int)$this->_data->$name;
            }

            return $this->_data->$name;
        }

        return null;
    }

    /**
     * Magic isset (required for empty to work)
     *
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->_data->$name);
    }
}
