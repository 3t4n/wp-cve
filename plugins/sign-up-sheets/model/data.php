<?php
/**
 * Database queries and actions
 *
 * @deprecated this class is deprecated.  Common methods should be moved to Base, everything else moved to proper model classes.
 */

namespace FDSUS\Model;

use FDSUS\Id;
use FDSUS\Model\Sheet as SheetModel;
use FDSUS\Model\Task as TaskModel;
use FDSUS\Model\Signup as SignupModel;
use WP_Error;
use wpdb;
use WP_Post;
use WP_Roles;

class Data extends Base
{
    public $err;

    public $prefix = 'dlssus';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Include sus specific fields on post objects
     *
     * @param array|WP_Post|TaskModel|SignupModel $posts
     *
     * @todo move to individual models using setFields()
     */
    private function includeFields(&$posts)
    {
        if (is_null($posts)) {
            return;
        }

        $was_array = is_array($posts);
        if (is_a($posts, '\WP_Post')
            || is_a($posts, '\FDSUS\Model\Task')
            || is_a($posts, '\FDSUS\Model\Signup')
        ) {
            $posts = array($posts);
        }

        // Add additional fields
        foreach ($posts as $postKey => $post) {

            $metaFields = get_post_custom($post->ID);

            switch ($post->post_type) {

                // Task
                case TaskModel::POST_TYPE:

                    // Set sort if not already
                    if (!isset($posts[$postKey]->dlssus_sort)) {
                        $posts[$postKey]->dlssus_sort = 0;
                    }

                    // Set date
                    if (!isset($posts[$postKey]->dlssus_date)) {
                        $posts[$postKey]->dlssus_date = null;
                    }

                    // Set is_active
                    $posts[$postKey]->dlssus_is_active = false;
                    $parentSheet = new SheetModel($posts[$postKey]->post_parent);
                    if (!$parentSheet) {
                        $parentSheetUseTaskDate = '';
                        $parentSheetEndDate = '';
                    } else {
                        $parentSheetUseTaskDate = $parentSheet->dlssus_date;
                        $parentSheetEndDate = $parentSheet->dlssus_use_task_dates;
                    }

                    if (
                        $posts[$postKey]->post_status == 'publish'
                        && (
                            empty($posts[$postKey]->dlssus_date)
                            || (strtotime($posts[$postKey]->dlssus_date) + 864000) > current_time('timestamp')
                        )
                        || (
                            $parentSheetUseTaskDate != "true"
                            &&
                            ((strtotime($parentSheetEndDate) + 864000) > current_time('timestamp')
                                || empty($parentSheetEndDate))
                        )
                    ) {
                        $posts[$postKey]->dlssus_is_active = true;
                    }
                    break;

                // Signup
                case SignupModel::POST_TYPE:

                    if (!isset($posts[$postKey]->dlssus_removal_token)) {
                        $posts[$postKey]->dlssus_removal_token = 0;
                    }

            }

            // Meta fields
            foreach ($metaFields as $key => $value) {
                if (strpos($key, Id::PREFIX . '_') === 0) {
                    $posts[$postKey]->{$key} = maybe_unserialize(current($value));
                }
            }

        }
        reset($posts);

        if (!$was_array) {
            $posts = current($posts);
        }
    }

    /**
     * Get v2.0 item count
     *
     * @return array|null|object|void
     */
    public function getV20ItemCount()
    {
        $hasCategoriesSql = "SELECT count(*)
            FROM information_schema.TABLES
            WHERE (TABLE_SCHEMA = '" . DB_NAME . "') AND (TABLE_NAME = '{$this->wpdb->prefix}dls_sus_categories')";
        $hasCategories = (int)$this->wpdb->get_var($hasCategoriesSql);
        $sql = "SELECT
            (SELECT COUNT(id) FROM {$this->wpdb->prefix}dls_sus_sheets) AS sheets,
            (SELECT COUNT(id) FROM {$this->wpdb->prefix}dls_sus_tasks) AS tasks,
            (SELECT COUNT(id) FROM {$this->wpdb->prefix}dls_sus_signups) AS signups
        ";
        if ($hasCategories > 0) {
            $sql .= ", (SELECT COUNT(id) FROM {$this->wpdb->prefix}dls_sus_categories) AS categories";
        }
        return $this->wpdb->get_row($sql);
    }

    /**
     * Get tasks by sheet
     *
     * @param     int $sheet_id
     *
     * @return    mixed    array of tasks
     *
     * @todo remove eventually once it's fully moved into Sheet model or Tasks Collection
     */
    public function get_tasks($sheet_id)
    {
        if (empty($sheet_id)) {
            return array();
        }

        $args = array(
            'posts_per_page' => -1,
            'post_type' => TaskModel::POST_TYPE,
            'post_parent' => $sheet_id,
            'post_status' => 'publish',
            'suppress_filters' => true,
        );
        $tasks = get_posts($args);

        $this->includeFields($tasks);

        usort($tasks, array(&$this, '_order_task_by_sort'));

        return $tasks;
    }

    /**
     * Order an array of tasks by the "sort" field
     * Works as part of usort()
     * Ex: usort( $tasks, array( &$this, '_order_task_by_sort') );
     *
     * @param $a
     * @param $b
     *
     * @return int
     */
    private function _order_task_by_sort($a, $b)
    {
        return (int)$a->{'dlssus_sort'} - (int)$b->{'dlssus_sort'};
    }

    /**
     * Get signups by task
     *
     * @param int $task_id
     *
     * @return array signups
     *
     * @todo move all instances of this to version in SignupCollection model
     */
    public function get_signups($task_id)
    {
        if (empty($task_id)) {
            return array();
        }

        $args = array(
            'posts_per_page' => -1,
            'post_type' => SignupModel::POST_TYPE,
            'post_parent' => $task_id,
            'post_status' => 'publish',
            'suppress_filters' => true,
            'orderby' => 'date, ID',
            'order' => 'ASC',
        );

        $signups = get_posts($args);

        $this->includeFields($signups);

        return $signups;
    }

    /**
     * Get term meta (based on get_metadata('term', ...))
     *
     * @since 2.1
     *
     * @param int $termId
     * @param string $metaKey
     * @param bool $single
     * @return string|array Single metadata value, or array of values
     * @todo update to be able to use core termmeta functions for WP v4.4+
     */
    public function getTermMeta($termId, $metaKey = '', $single = false)
    {
        $metaType = Id::PREFIX . '_term';

        $metaCache = wp_cache_get($termId, $metaType . '_meta');

        if (!$metaCache) {
            $metaCache = $this->updateTermMetaCache(array($termId));
            $metaCache = $metaCache[$termId];
        }

        if (!$metaKey) {
            return $metaCache;
        }

        if (isset($metaCache[$metaKey])) {
            if ($single)
                return maybe_unserialize($metaCache[$metaKey][0]);
            else
                return array_map('maybe_unserialize', $metaCache[$metaKey]);
        }

        if ($single)
            return '';
        else
            return array();
    }

    /**
     * Update Terms Meta (based on update_post_meta)
     *
     * @since 2.1
     *
     * @param int $termId
     * @param $metaKey
     * @param $metaValue
     * @param $prevValue
     *
     * @return int|WP_Error|bool Returns meta_id if the meta doesn't exist, otherwise
     *                  returns true on success and false on failure. NOTE: If
     *                  the meta_value passed to this function is the same as
     *                  the value that is already in the database, this
     *                  function returns false.
     * @todo update to be able to use core termmeta functions for WP v4.4+
     */
    public function updateTermMeta($termId, $metaKey, $metaValue, $prevValue = null)
    {
        $metaType = Id::PREFIX . '_term';
        $table = $this->wpdb->prefix . Id::PREFIX . '_termmeta';

        $column = sanitize_key('term_id');

        // expected_slashed ($metaKey)
        $metaKey = wp_unslash($metaKey);
        $passed_value = $metaValue;
        $metaValue = wp_unslash($metaValue);
        $metaValue = sanitize_meta($metaKey, $metaValue, 'term');

        // Add if new
        $meta_ids = $this->wpdb->get_col($this->wpdb->prepare("SELECT meta_id FROM $table WHERE meta_key = %s AND $column = %d", $metaKey, $termId));
        if (empty($meta_ids)) {
            return $this->addTermMeta($termId, $metaKey, $passed_value);
        }

        // Compare existing value to new value if no prev value given and the key exists only once.
        if (empty($prevValue)) {
            $old_value = $this->getTermMeta($termId, $metaKey);
            if (count($old_value) == 1) {
                if ($old_value[0] === $metaValue)
                    return false;
            }
        }

        $metaValue = maybe_serialize($metaValue);

        $data = compact('metaValue');
        $where = array($column => $termId, 'meta_key' => $metaKey);

        if (!empty($prevValue)) {
            $prevValue = maybe_serialize($prevValue);
            $where['meta_value'] = $prevValue;
        }

        $result = $this->wpdb->update($table, $data, $where);
        if (!$result) return false;

        wp_cache_delete($termId, $metaType . '_meta');
    }

    /**
     * Add term meta
     *
     * @since 2.1
     *
     * @param int $termId
     * @param string $metaKey
     * @param mixed $metaValue
     * @param bool $unique
     * @return bool|int
     * @todo update to be able to use core termmeta functions for WP v4.4+
     */
    public function addTermMeta($termId, $metaKey, $metaValue, $unique = false)
    {
        $table = $this->wpdb->prefix . Id::PREFIX . '_termmeta';

        if ($unique && $this->wpdb->get_var($this->wpdb->prepare(
                "SELECT COUNT(*) FROM $table WHERE meta_key = %s AND term_id = %d",
                $metaKey, $termId))
        )
            return false;

        $result = $this->wpdb->insert($table, array(
            'term_id' => $termId,
            'meta_key' => $metaKey,
            'meta_value' => $metaValue
        ));

        if (!$result)
            return false;

        $mid = (int)$this->wpdb->insert_id;
        return $mid;
    }

    /**
     * Update the custom metadata cache for SUS terms.
     * Based on update_meta_cache()
     *
     * @since 2.1
     *
     * @global wpdb $wpdb WordPress database abstraction object.
     *
     * @param int|array $objectIds Array or comma delimited list of object IDs to update cache for
     * @return array|false Metadata cache for the specified objects, or false on failure.
     * @todo update to be able to use core termmeta functions for WP v4.4+
     */
    function updateTermMetaCache($objectIds)
    {
        global $wpdb;
        $table = $this->wpdb->prefix . Id::PREFIX . '_termmeta';
        $metaType = Id::PREFIX . '_term';

        if (!$objectIds) {
            return false;
        }

        if (!is_array($objectIds)) {
            $objectIds = preg_replace('|[^0-9,]|', '', $objectIds);
            $objectIds = explode(',', $objectIds);
        }

        $objectIds = array_map('intval', $objectIds);

        $cacheKey = $metaType . '_meta';
        $ids = array();
        $cache = array();
        foreach ($objectIds as $id) {
            $cachedObject = wp_cache_get($id, $cacheKey);
            if (false === $cachedObject)
                $ids[] = $id;
            else
                $cache[$id] = $cachedObject;
        }

        if (empty($ids))
            return $cache;

        // Get meta info
        $id_list = join(',', $ids);
        $metaList = $wpdb->get_results("
          SELECT term_id, meta_key, meta_value
          FROM $table
          WHERE meta_id IN ($id_list)
          ORDER BY meta_id ASC
        ", ARRAY_A);

        if (!empty($metaList)) {
            foreach ($metaList as $metarow) {
                $mpid = intval($metarow['term_id']);
                $mkey = $metarow['meta_key'];
                $mval = $metarow['meta_value'];

                // Force subkeys to be array type:
                if (!isset($cache[$mpid]) || !is_array($cache[$mpid]))
                    $cache[$mpid] = array();
                if (!isset($cache[$mpid][$mkey]) || !is_array($cache[$mpid][$mkey]))
                    $cache[$mpid][$mkey] = array();

                // Add a value to the current pid/key:
                $cache[$mpid][$mkey][] = $mval;
            }
        }

        foreach ($ids as $id) {
            if (!isset($cache[$id]))
                $cache[$id] = array();
            wp_cache_add($id, $cache[$id], $cacheKey);
        }

        return $cache;
    }

    /**
     * Delete a signup
     *
     * @param    int $id
     *
     * @return   bool
     *
     * @todo move to Signup object
     */
    public function delete_signup($id)
    {
        $result = wp_delete_post($id, true);
        if (!$result) {
            return false;
        }
        return true;
    }

    /**
     * Add/remove sign-up sheet capabilities to all roles that need them
     */
    public function set_capabilities()
    {
        /** @global WP_Roles $wp_roles */
        global $wp_roles;
        $all_roles = $wp_roles->get_names();
        if (!is_array($all_roles)) {
            $all_roles = array();
        }
        $manager_roles = get_option('dls_sus_roles');
        if (!is_array($manager_roles)) {
            $manager_roles = array();
        }
        $manager_roles[] = 'administrator';
        $manager_roles[] = 'signup_sheet_manager';
        $caps_all = array(
            $this->get_add_caps_array(SheetModel::POST_TYPE),
            $this->get_add_caps_array(TaskModel::POST_TYPE),
            $this->get_add_caps_array(SignupModel::POST_TYPE)
        );

        foreach ($all_roles as $k => $v) {
            $role = get_role($k);
            if (is_object($role)) {
                if (in_array($k, $manager_roles)) {
                    if ($k == 'signup_sheet_manager') {
                        $role->add_cap('read');
                    }
                    foreach ($caps_all as $caps) {
                        foreach ($caps as $cap) {
                            $role->add_cap($cap);
                        }
                        reset($caps);
                    }
                    reset($caps_all);
                } else {
                    foreach ($caps_all as $caps) {
                        foreach ($caps as $cap) {
                            $role->remove_cap($cap);
                        }
                        reset($caps);
                    }
                    reset($caps_all);
                }
            }
        }
    }

    /**
     * Remove plugin specific capabilities from all roles
     */
    public function remove_capabilities()
    {
        /** @global WP_Roles $wp_roles */
        global $wp_roles;

        $caps_all = array(
            $this->get_add_caps_array(SheetModel::POST_TYPE),
            $this->get_add_caps_array(TaskModel::POST_TYPE)
        );

        $all_roles = $wp_roles->get_names();
        foreach ($all_roles as $k => $v) {
            $role = get_role($k);
            foreach ($caps_all as $caps) {
                foreach ($caps as $cap) {
                    $role->remove_cap($cap);
                }
                reset($caps);
            }
            reset($caps_all);
        }
    }

    /**
     * Is the honeypot enabled on the sign-up form
     *
     * @return bool
     */
    public function is_honeypot_enabled()
    {
        $disable_honeypot = get_option('dls_sus_disable_honeypot');

        return !($disable_honeypot === 'true');
    }

    /**
     * Generate Token
     *
     * @return  string  random token
     */
    public function generate_token()
    {
        return sha1(uniqid(mt_rand(), true));
    }

    /**
     * Get add caps array
     *
     * @param $cap_type
     *
     * @return array
     */
    public function get_add_caps_array($cap_type)
    {
        return array(
            'edit_post'              => "edit_{$cap_type}",
            'read_post'              => "read_{$cap_type}",
            'delete_post'            => "delete_{$cap_type}",
            'edit_posts'             => "edit_{$cap_type}s",
            'edit_others_posts'      => "edit_others_{$cap_type}s",
            'publish_posts'          => "publish_{$cap_type}s",
            'read_private_posts'     => "read_private_{$cap_type}s",
            'delete_posts'           => "delete_{$cap_type}s",
            'delete_private_posts'   => "delete_private_{$cap_type}s",
            'delete_published_posts' => "delete_published_{$cap_type}s",
            'delete_others_posts'    => "delete_others_{$cap_type}s",
            'edit_private_posts'     => "edit_private_{$cap_type}s",
            'edit_published_posts'   => "edit_published_{$cap_type}s",
        );
    }

    /**
     * Build WP_Error
     *
     * @param string $code
     * @param string $message
     *
     * @return WP_Error
     */
    public function err( $code, $message ) {
        if ( ! empty( $this->err ) && is_wp_error( $this->err ) ) {
            $this->err->add( $code, $message );
        } else {
            $this->err = new WP_Error( $code, $message );
        }

        return $this->err;
    }

}
