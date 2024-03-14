<?php
/**
 * Sheet Base Class
 */

namespace FDSUS\Model;

use FDSUS\Id;
use FDSUS\Model\Data as Data;
use FDSUS\Model\Task as TaskModel;
use FDSUS\Model\Signup as SignupModel;
use FDSUS\Model\TaskCollection as TaskCollectionModel;
use WP_Post;
use WP_Error;
use WP_Query;

/**
 * Class Sheet
 *
 * @property int    ID
 * @property string post_status
 * @property string post_title
 * @property bool   dlssus_use_task_dates
 * @property string dlssus_date
 * @property string dlssus_start_date
 * @property string dlssus_end_date
 * @property string dlssus_hide_email
 * @property string dlssus_hide_phone
 * @property string dlssus_hide_address
 * @property string dlssus_use_task_checkboxes
 * @property bool   dlssus_is_active
 * @property int    dlssus_task_count
 * @property string dlssus_sheet_bcc
 * @property string dlssus_compact_signups
 * @property string dlssus_contiguous_task_signup_limit
 * @property string dlssus_task_signup_limit
 * @property string dlssus_sheet_email_conf_message
 * @property array  fdsus_autoclear
 * @property string fdsus_last_autoclear
 *
 * @package FDSUS\Model
 */
class SheetBase extends Base
{
    /** @var \FDSUS\Model\Data  */
    public $data;

    /** @var array|bool|WP_Post|null  */
    protected $_data;

    /** @var array  */
    public $custom_fields = array();

    /** @var array  */
    public $categories = array();

    /** @var string  */
    private static $defaultBaseSlug = 'sheet';

    /** @var string */
    const POST_TYPE = 'dlssus_sheet';

    /**
     * Constructor
     *
     * @param int|WP_Post $id
     * @param bool        $tryV20First try getting by the old v2.0 ID first
     */
    public function __construct($id, $tryV20First = false)
    {
        parent::__construct();
        $this->data = new Data();
        if ($tryV20First) {
            $this->_data = $this->getByV20Id($id);
        }

        if (!is_object($this->_data) || !is_a($this->_data, 'WP_Post')) {
            $this->_data = $this->get($id);
        }
    }

    /**
     * Set sheet object
     *
     * @param int|WP_Post $id
     *
     * @return array|bool|null|WP_Post
     */
    protected function get($id)
    {
        $post = get_post($id);
        if (!is_a($post, 'WP_Post') || $post->post_type != self::POST_TYPE) {
            return false;
        }

        $this->setFields($post);

        return $post;
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

        // Extra task data
        $extra = $this->getExtraTaskDataBySheet($post->ID);
        $post->dlssus_task_count = !empty($extra->count) ? $extra->count : 0;

        // Set start/end date
        if (Id::isPro() && $post->dlssus_use_task_dates) {
            $startDate = $extra->minDate;
            $endDate = $extra->maxDate;
            $post->dlssus_date = null;
        } else {
            $startDate = $post->dlssus_date;
            $endDate = $post->dlssus_date;
        }
        $post->dlssus_start_date = $startDate;
        $post->dlssus_end_date = $endDate;

        // Set is_active
        $post->dlssus_is_active = false;
        if (
            (
                $post->post_status == 'publish'
                || (
                    $post->post_status == 'private'
                    && current_user_can('read_private_' . self::POST_TYPE)
                )
            ) && (
                empty($endDate) || (strtotime($endDate) + 864000) > current_time('timestamp')
            )
        ) {
            $post->dlssus_is_active = true;
        }

        // Meta fields
        foreach ($metaFields as $key => $value) {
            if (strpos($key, Id::PREFIX . '_') === 0 || strpos($key, 'fdsus_') === 0) {
                switch ($key) {
                    // Arrays
                    case 'fdsus_autoclear':
                        $post->{$key} = maybe_unserialize($value);
                        break;
                    // Single Values
                    default:
                        $post->{$key} = maybe_unserialize(current($value));
                }
            }
        }
    }

    /**
     * Get sheet by v2.0 id
     *
     * @param int    $id
     * @param string $type
     *
     * @return WP_Post|false
     */
    protected function getByV20Id($id)
    {
        $query = new WP_Query(
            array(
                'post_type'      => self::POST_TYPE,
                'meta_key'       => Id::PREFIX . '_id_v2_0',
                'meta_value'     => $id,
                'post_status'    => 'all',
                'posts_per_page' => -1,
            )
        );

        if (empty($query->posts)) {
            return false;
        }

        $post = current($query->posts);
        $this->setFields($post);

        return $post;
    }

    /**
     * Get Base Slug
     *
     * @return string|false
     */
    public static function getBaseSlug()
    {
        return ($value = get_option('dls_sus_sheet_slug')) ? $value : self::$defaultBaseSlug;
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
        return $singular ? __('Sign-up Sheet', 'fdsus') : __('Sign-up Sheets', 'fdsus');
    }

    /**
     * Delete specified signups for this sheet
     *
     * @param array $idsToDelete
     *
     * @return   bool
     */
    public function deleteSignups($idsToDelete)
    {
        if (!is_array($idsToDelete)) {
            $idsToDelete = array($idsToDelete);
        }

        $args = array(
            'post_type'      => TaskModel::POST_TYPE,
            'post_parent'    => $this->ID,
            'post_status'    => 'publish',
            'posts_per_page' => -1,
        );
        $taskPosts = get_posts($args);

        foreach ($taskPosts as $taskPost) {
            $args = array(
                'post_type'      => SignupModel::POST_TYPE,
                'post_parent'    => $taskPost->ID,
                'post_status'    => 'publish',
                'posts_per_page' => -1,
            );
            $signupPosts = get_posts($args);

            foreach ($signupPosts as $signupPost) {
                if (!in_array($signupPost->ID, $idsToDelete)) {
                    continue;
                }
                $signup = new SignupModel($signupPost);
                if (!$signup->delete()) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Get Tasks
     *
     * @return TaskModel[]
     */
    public function getTasks()
    {
        if (!$this->isValid()) {
            return array();
        }
        $taskCollection = new TaskCollectionModel();
        return $taskCollection->getBySheet($this);
    }

    /**
     * Copy a sheet and all tasks to a new sheet for editing
     *
     * @return int|WP_Error new sheet id
     */
    public function copy()
    {
        $postsAdded = array();
        $sheetArray = $this->objectToArray($this->getData());
        $this->cleanBeforeCopy($sheetArray);
        $sheetArray['post_title'] .= esc_html__(' (Copy)', 'fdsus');

        // Copy sheet
        $newSheetId = wp_insert_post($sheetArray, true);
        if (is_wp_error($newSheetId)) {
            return $newSheetId;
        }
        $postsAdded[] = $newSheetId;

        // Copy sheet meta
        $this->copyPostMeta($this->ID, $newSheetId);

        // Copy tasks
        $tasks = $this->getTasks();
        foreach ($tasks as $task) {
            $origTaskId = $task->ID;
            $taskPost = $task->getData();
            $this->cleanBeforeCopy($taskPost);
            $taskPost->post_parent = $newSheetId;
            $newTaskId = wp_insert_post($taskPost, true);
            if (is_wp_error($newTaskId)) {
                foreach ($postsAdded as $postId) {
                    wp_delete_post($postId, true);
                }
                return $newTaskId;
            }
            $postsAdded[] = $newTaskId;

            // Copy tasks meta
            $this->copyPostMeta($origTaskId, $newTaskId);
        }
        reset($tasks);

        /**
         * Action when copy sheet is successful
         *
         * @param int   $newSheetId
         * @param Sheet $originalSheet
         *
         * @since 2.2
         */
        do_action('fdsus_copy_sheet', $newSheetId, $this);

        return $newSheetId;
    }

    /**
     * Copy post custom meta from one post to another
     *
     * @param int $origPostId Origin post ID
     * @param int $destPostId Destination post ID
     */
    private function copyPostMeta($origPostId, $destPostId)
    {
        $metaFields = get_post_custom($origPostId);
        foreach ($metaFields as $key => $values) {
            foreach ($values as $value) {
                update_post_meta($destPostId, $key, $value);
            }
        }
    }

    /**
     * Clean object of post specific fields before copying it
     *
     * @param $obj
     */
    private function cleanBeforeCopy(&$obj)
    {
        if (is_object($obj)) {
            unset($obj->ID);
            unset($obj->post_author);
            unset($obj->post_date);
            unset($obj->post_date_gmt);
            unset($obj->post_modified);
            unset($obj->post_modified_gmt);
        } elseif (is_array($obj)) {
            unset($obj['ID']);
            unset($obj['post_author']);
            unset($obj['post_date']);
            unset($obj['post_date_gmt']);
            unset($obj['post_modified']);
            unset($obj['post_modified_gmt']);
        }
    }

    /**
     * Get WP_Post
     *
     * @return array|bool|int|WP_Post|null
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
        return is_object($this->_data) && $this->_data instanceof WP_Post && !empty($this->ID);
    }

    /**
     * Is sheet expired?
     *
     * @return bool
     */
    public function isExpired()
    {
        return $this->dlssus_date && strtotime($this->dlssus_date . ' 23:59:59') < current_time('timestamp');
    }

    /**
     * Is sheet visible on frontend
     */
    public function isVisibleOnFrontend()
    {
        $frontendStatuses = array('publish');
        $sheetCaps = $this->data->get_add_caps_array(self::POST_TYPE);
        if (current_user_can($sheetCaps['read_private_posts'])) {
            $frontendStatuses[] = 'private';
        }
        return in_array($this->post_status, $frontendStatuses);
    }

    /**
     * Should the email be displayed on the sheet?
     *
     * @return bool
     */
    public function showEmail()
    {
        return (!empty($this->dlssus_hide_email)
            && $this->dlssus_hide_email !== 'true')
        || (empty($this->dlssus_hide_email)
            && get_option('dls_sus_hide_email') !== 'true');
    }

    /**
     * Should the phone be displayed on the sheet?
     *
     * @return bool
     */
    public function showPhone()
    {
        return (!empty($this->dlssus_hide_phone)
            && $this->dlssus_hide_phone !== 'true')
        || (empty($this->dlssus_hide_phone)
            && get_option('dls_sus_hide_phone') !== 'true');
    }

    /**
     * Should the address be displayed on the sheet?
     *
     * @return bool
     */
    public function showAddress()
    {
        return (!empty($this->dlssus_hide_address)
            && $this->dlssus_hide_address !== 'true')
        || (empty($this->dlssus_hide_address)
            && get_option('dls_sus_hide_address') !== 'true');
    }

    /**
     * Get number of total spots on a specific sheet
     *
     * @return int
     *
     * @todo fix inner join on header which requires "dlssus_task_row_type" to be set, but it shouldn't be required
     */
    public function getTotalSpots()
    {
        $sql = $this->wpdb->prepare(
            "
            SELECT
                SUM(m.meta_value) AS total
            FROM {$this->wpdb->posts} p
            LEFT OUTER JOIN {$this->wpdb->postmeta} m ON m.post_id = p.ID AND m.meta_key = 'dlssus_qty'
            INNER JOIN {$this->wpdb->postmeta} header ON header.post_id = p.ID AND header.meta_key = 'dlssus_task_row_type' AND header.meta_value <> 'header'
            WHERE p.post_parent = %d
                AND p.post_type = 'dlssus_task'
                AND p.post_status = 'publish'",
            $this->ID
        );

        $results = $this->wpdb->get_results($sql);

        return (int)$results[0]->total;
    }

    /**
     * Get number of signups on a specific sheet
     *
     * @return int
     */
    public function getSignupCount()
    {
        $results = $this->wpdb->get_results(
            $this->wpdb->prepare(
                "
                    SELECT COUNT(*) AS count FROM {$this->wpdb->posts} t
                    RIGHT OUTER JOIN {$this->wpdb->posts} s ON t.ID = s.post_parent AND s.post_type = 'dlssus_signup'
                    WHERE t.post_type = %s
                        AND t.post_parent = %d
                ",
                TaskModel::POST_TYPE,
                $this->ID
            )
        );

        return (int)$results[0]->count;
    }

    /**
     * Get open spots
     *
     * @return int
     */
    public function getOpenSpots()
    {
        return $this->getTotalSpots() - $this->getSignupCount();
    }

    /**
     * Should the phone be displayed on the sheet?
     *
     * @return bool
     */
    public function isPhoneRequired()
    {
        return (isset($this->dlssus_optional_phone) && $this->dlssus_optional_phone === 'false')
            || (empty($this->dlssus_optional_phone) && get_option('dls_sus_optional_phone') !== 'true');
    }

    /**
     * Should the address be displayed on the sheet?
     *
     * @return bool
     */
    public function isAddressRequired()
    {
        return (isset($this->dlssus_optional_address) && $this->dlssus_optional_address === 'false')
            || (empty($this->dlssus_optional_address) && get_option('dls_sus_optional_address') !== 'true');
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
            if (in_array($name, array('ID', 'post_parent'))) {
                return (int)$this->_data->$name;
            }

            // Clean true/false
            if (in_array($name, array('dlssus_use_task_dates'))) {
                if (!Id::isPro()) {
                    return false;
                }
                return $this->_data->$name === 'true';
            }
            return $this->_data->$name;
        }

        return null;
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
        if (!property_exists($this->_data, $name)) {
            return false;
        }

        return !is_null($this->_data->$name);
    }

    /**
     * Is full-compact mode?
     * Note: keep this method in Free version for task table logic
     *
     * @return bool
     */
    public function isFullCompact()
    {
        return false;
    }
}
