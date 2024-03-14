<?php
/**
 * Task Base Model
 */

namespace FDSUS\Model;

use FDSUS\Id;
use FDSUS\Lib\Exception;
use FDSUS\Model\Sheet as SheetModel;
use FDSUS\Model\Signup as SignupModel;
use FDSUS\Model\SignupCollection as SignupCollection;
use WP_Post;
use WP_Error;

/**
 * Class Task
 *
 * @property int    ID
 * @property string post_title
 * @property int    post_parent
 * @property string dlssus_date // use getDate() on Pro to account for getting task or sheet date
 * @property int    dlssus_qty
 * @property string dlssus_task_row_type
 * @property bool   dlssus_is_active
 *
 * @package FDSUS\Model
 */
class TaskBase extends Base
{
    /** @var WP_Post */
    protected $_data;

    /** @var SheetModel */
    protected $sheet;

    /** @var string  */
    protected static $defaultBaseSlug = 'task';

    /** @var string  */
    const POST_TYPE = 'dlssus_task';

    /** @var SignupModel[] */
    protected $signups;

    /**
     * Constructor
     *
     * @param int|WP_Post     $taskId id or post object
     * @param null|SheetModel $sheet
     */
    public function __construct($taskId = 0, $sheet = null)
    {
        if (!empty($sheet) && $sheet instanceof SheetModel) {
            $this->sheet = $sheet;
        }

        if (!empty($taskId)) {
            $this->init($taskId);
        }

        parent::__construct();
    }

    /**
     * Init
     *
     * @param int|WP_Post $taskId id or post object
     */
    protected function init($taskId)
    {
        $this->_data = $this->get($taskId);
    }

    /**
     * Get Base Slug
     *
     * @return string
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
        return $singular ? __('Task', 'fdsus') : __('Tasks', 'fdsus');
    }

    /**
     * Get single task
     *
     * @param int|WP_Post $id task id or object
     *
     * @return array|false|WP_Post|null task
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
     * Add a new task
     *
     * @param array $fields array of fields and values to insert
     * @param int   $sheetId
     *
     * @return int|WP_Error post_id of new task
     * @throws Exception
     */
    public function add($fields, $sheetId)
    {
        $cleanFields = $this->cleanArray($fields);
        $cleanFields['sheet_id'] = $sheetId;
        if (empty($cleanFields['qty']) || $cleanFields['qty'] < 2) {
            $cleanFields['qty'] = 1;
        }
        if (isset($cleanFields['date'])) {
            if ($cleanFields['date'] == '') {
                $cleanFields['date'] = null;
            }
            if (!empty($cleanFields['date'])) {
                $cleanFields['date'] = date(
                    'Ymd',
                    strtotime($cleanFields['date'])
                );
            }
        } else {
            $cleanFields['date'] = null;
        }

        $my_post = array(
            'post_title'  => $cleanFields['title'],
            'post_type'   => self::POST_TYPE,
            'post_status' => 'publish',
            'post_parent' => $sheetId,
        );
        $taskId = wp_insert_post($my_post, true);
        if (is_wp_error($taskId)) {
            return new WP_Error(
                'add_task_err',
                sprintf(
                    /* translators: %s is replaced with the task title */
                    esc_html__('Error adding the task "%s"', 'fdsus'),
                    $cleanFields['title']
                )
                . (Settings::isDetailedErrors() ? '.. ' . print_r($taskId->get_error_message(), true) : '')
            );
        }

        // Set non-custom meta fields
        $meta = array(
            'dlssus_qty'           => $cleanFields['qty'],
            'dlssus_date'          => $cleanFields['date'],
            'dlssus_sort'          => $cleanFields['sort'],
            'dlssus_task_row_type' => isset($cleanFields['task_row_type']) ? $cleanFields['task_row_type'] : null,
        );

        if (!empty($cleanFields['id_v2_0'])) {
            $meta[Id::PREFIX . '_id_v2_0'] = $cleanFields['id_v2_0'];
        }

        /**
         * Filter allowed meta fields before task save
         *
         * @param array $meta
         * @param array $cleanFields
         * @param int   $sheetId
         *
         * @return array
         * @since 2.2
         */
        $meta = apply_filters('fdsus_meta_before_add_task', $meta, $cleanFields, $sheetId);

        // Add meta fields
        $i = 0;
        foreach ($meta as $k => $v) {
            if (update_post_meta($taskId, $k, maybe_unserialize($v)) === false) {
                return new WP_Error('add_task_additional_err', esc_html__('Error adding additional fields to task.', 'fdsus'));
            }
            $i++;
        }

        return $taskId;
    }

    /**
     * Update a task
     *
     * @param    array $fields array of fields and values to update
     *
     * @return   mixed|WP_Error number of rows update or false if fails
     */
    public function update($fields)
    {
        // Clean Data
        $cleanFields = $this->cleanArray($fields);
        if (isset($cleanFields['date'])) {
            if ($cleanFields['date'] == '') {
                $cleanFields['date'] = null;
            }
            if (!empty($cleanFields['date'])) {
                $cleanFields['date'] = date('Ymd', strtotime($cleanFields['date']));
            }
        } else {
            $cleanFields['date'] = '';
        }

        if (empty($cleanFields['qty']) || $cleanFields['qty'] < 2) {
            $cleanFields['qty'] = 1;
        }

        // Error Handling
        $signup_count = count($this->getSignups());
        if ($signup_count > $cleanFields['qty']) {
            return new WP_Error('spots_less_than_signups', 'Could not update the number of people needed on task "' . $cleanFields['title'] . '" to be "' . $cleanFields['qty'] . '" because the number of signups is already "' . $signup_count . '".  You will need to clear spots before adjusting this number.');
        }

        $myPost = array(
            'ID' => $this->ID,
            'post_title' => $cleanFields['title'],
        );
        $task_id = wp_update_post($myPost, true);
        if (is_wp_error($task_id)) {
            return new WP_Error('update_task_err',
                sprintf(
                    /* translators: %s is replaced with the task title */
                    esc_html__('Error updating the task "%s"', 'fdsus'),
                    $cleanFields['title']
                ) . (Settings::isDetailedErrors() ? '.. ' . print_r($task_id->get_error_message(), true) : '')
            );
        }

        // Allowed non-custom meta fields
        $meta['qty'] = $cleanFields['qty'];
        $meta['date'] = $cleanFields['date'];
        $meta['sort'] = $cleanFields['sort'];

        /**
         * Filter allowed meta fields before task save
         *
         * @param array $meta
         * @param array $cleanFields
         * @param int   $sheetId
         *
         * @return array
         * @since 2.2
         */
        $meta = apply_filters('fdsus_meta_before_update_task', $meta, $cleanFields, $this->post_parent);

        foreach ($meta as $k => $v) {
            update_post_meta($this->ID, Id::PREFIX . '_' . $k, $v);
        }

        return $task_id;
    }

    /**
     * Delete
     *
     * @param int $id optional task ID if not already initialized
     *
     * @return array|WP_Error
     */
    public function delete($id = 0)
    {
        if (empty($id)) {
            $id = $this->ID;
        }
        $result = wp_delete_post($id, true);
        if ($result === false) {
            return new WP_Error('task_delete_err', esc_html__('Error deleting task.', 'fdsus'));
        }

        return $result;
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

        // Set sort if not already
        if (!isset($post->dlssus_sort)) {
            $post->dlssus_sort = 0;
        }

        // Set date
        if (!isset($post->dlssus_date)) {
            $post->dlssus_date = null;
        }

        // Set is_active
        $post->dlssus_is_active = false;
        $parentSheet = $this->getSheet($post->post_parent);
        if (!$parentSheet) {
            $parentSheetUseTaskDate = '';
            $parentSheetEndDate = '';
        } else {
            $parentSheetUseTaskDate = $parentSheet->dlssus_date;
            $parentSheetEndDate = is_null($parentSheet->dlssus_use_task_dates) ? '' : $parentSheet->dlssus_use_task_dates;
        }

        if (
            $post->post_status == 'publish'
            && (
                empty($post->dlssus_date)
                || (strtotime($post->dlssus_date) + 864000) > current_time('timestamp')
            )
            || (
                $parentSheetUseTaskDate != "true"
                &&
                ((strtotime($parentSheetEndDate) + 864000) > current_time('timestamp')
                    || empty($parentSheetEndDate))
            )
        ) {
            $post->dlssus_is_active = true;
        }

        // Meta fields
        foreach ($metaFields as $key => $value) {
            if (strpos($key, Id::PREFIX . '_') === 0) {
                $post->{$key} = maybe_unserialize(current($value));
            }
        }
    }

    /**
     * Get sign-ups
     *
     * @return SignupModel[]
     */
    public function getSignups()
    {
        if (!empty($this->signups) && is_array($this->signups)) {
            return $this->signups;
        }

        if (!$this->isValid()) {
            return array();
        }

        $signupCollection = new SignupCollection();
        $this->signups = $signupCollection->getByTask($this);

        return $this->signups;
    }

    /**
     * Get filled spot count
     *
     * @return int
     */
    public function getFilledSpotCount()
    {
        $this->getSignups();
        return count($this->signups);
    }

    /**
     * Get open spot count
     *
     * @return int
     */
    public function getOpenSpotCount()
    {
        $this->getSignups();
        return $this->dlssus_qty - $this->getFilledSpotCount();
    }

    /**
     * Check if an email is already listed on a signup for a task
     *
     * @param string $email
     *
     * @return bool|WP_Error
     */
    public function isEmailOnTask($email)
    {
        $signups = $this->getSignups();
        if (empty($signups)) {
            $signups = array();
        }

        if (empty($signups) || !is_array($signups)) {
            return false;
        }

        foreach ($signups as $signup) {
            if (empty($signup->dlssus_email)) {
                continue;
            }
            if ($signup->dlssus_email === $email) {
                return true;
            }
        }
        reset($signups);

        return false;
    }

    /**
     * Get Sheet
     *
     * @param int $sheetId
     *
     * @return SheetModel
     */
    public function getSheet($sheetId = 0)
    {
        if ($this->sheet instanceof SheetModel) {
            if (!empty($sheetId) && $sheetId !== $this->sheet->ID) {
                $this->sheet = new SheetModel($sheetId);
            }
        } elseif (!empty($sheetId)) {
            $this->sheet = new SheetModel($sheetId);
        } else {
            $this->sheet = new SheetModel($this->post_parent);
        }

        return $this->sheet;
    }

    /**
     * Get date of task
     *
     * @return string
     */
    public function getDate()
    {
        $sheet = $this->getSheet();
        return $sheet->dlssus_date;
    }

    /**
     * Get data
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * Is valid?
     *
     * @return bool
     */
    public function isValid()
    {
        return is_object($this->_data) && !empty($this->ID);
    }

    /**
     * Is expired?
     *
     * @return bool
     */
    public function isExpired()
    {
        $sheet = $this->getSheet();

        return $sheet->dlssus_use_task_dates && $this->dlssus_date
            && strtotime($this->dlssus_date . ' 23:59:59') < current_time('timestamp');
    }

    /**
     * Get Sign-up Link
     *
     * @param string $linkText override the default link text
     *
     * @return string
     */
    public function getSignupLink($linkText = '') {
        if (empty($linkText)) {
            $linkText = esc_html__('Sign up &raquo;', 'fdsus');
        }

        return sprintf(
            '<a href="%s" class="fdsus-signup-cta">%s</a>',
            add_query_arg(
                array('task_id' => $this->ID),
                remove_query_arg(array('action', 'status', 'tasks', 'signups', 'remove_spot_task_id', '_susnonce'))
            ),
            $linkText
        );
    }

    /**
     * Magic getter
     *
     * @param string $name
     *
     * @return null
     */
    public function __get($name)
    {
        if (is_object($this->_data) && property_exists($this->_data, $name)) {
            // Clean Int
            if (in_array($name, array('ID', 'post_parent', 'dlssus_qty'))) {
                return (int)$this->_data->$name;
            }

            return $this->_data->$name;
        }

        return null;
    }

    /**
     * Magic setter
     *
     * @param string $name
     * @param string $value
     *
     * @return bool
     */
    public function __set($name, $value)
    {
        if (is_object($this->_data) && property_exists($this->_data, $name)) {
            $this->_data->$name = $value;
            return true;
        }

        return false;
    }

    /**
     * Magic isset (required for empty to work)
     *
     * @param $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->_data->$name);
    }
}
