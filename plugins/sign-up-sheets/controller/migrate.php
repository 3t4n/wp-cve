<?php
/**
 * Sign-up Sheets Data Migration from v2.0.X to 2.1.X
 */

namespace FDSUS\Controller;

use WP_Error;
use FDSUS\Id;
use FDSUS\Model\Data;
use FDSUS\Model\Sheet as SheetModel;
use FDSUS\Model\Task as TaskModel;
use FDSUS\Model\Signup as SignupModel;
use FDSUS\Model\DbUpdate;
use FDSUS\Lib\Exception as Exception;
use FDSUS\Lib\TimeoutHandler;

if (!FDSUS_DISABLE_MIGRATE_2_0_to_2_1) {
    require_once(ABSPATH . DIRECTORY_SEPARATOR . 'wp-load.php');
}

if (Id::isPro()) {
    class MigrateParent extends Pro\Migrate {}
} else {
    class MigrateParent extends Base {}
}

class Migrate extends MigrateParent
{
    /** @var Data  */
    private $data;

    /** @var string  */
    public $statusKey;

    /** @var TimeoutHandler  */
    private $timeout;

    /** @var string  */
    public $runningTransientValue = 'dls_sus_version2.1_migration_running';

    /** @var array  */
    private $tablesExist = array();

    /** @var \wpdb  */
    private $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->data = new Data();
        $this->statusKey = Id::PREFIX . '_migrate_2.0_to_2.1';
        $this->timeout = new TimeoutHandler();
        parent::__construct();
    }

    /**
     * Run
     *
     * @param bool $force force migration to run regardless of specific checks
     *
     * @return void
     */
    public function run($force = false)
    {
        if (!$force && $this->isRunning()) {
            Id::log('Sign-up Sheets Migration already running.', 'migrate');
            return;
        }
        set_transient(Id::PREFIX . '_migration_running', $this->runningTransientValue, 60 * 60 * 6);

        $this->data->remove_capabilities();
        $this->data->set_capabilities();

        // Check migration status
        $status = $this->getStatus();
        if ($status['state'] == 'complete' && (empty($_GET['retry']) || $_GET['retry'] != 'true')) {
            $output = sprintf(
                /* translators: %1$s is replaced with the URL to retry */
                esc_html__('Migration has already been completed. <a href="%1$s" class="button">Run Migration Again</a>', 'fdsus'),
                add_query_arg('retry', 'true', $this->data->getCurrentUrl())
            );
            wp_die($output);
        }

        defined('SAVEQUERIES') or define('SAVEQUERIES', false);
        $startingMemory = memory_get_usage();

        update_option($this->statusKey, array('state' => 'running'));
        Id::log('---------- Sign-up Sheets Migration: Started ----------', 'migrate');

        // Set defer counting for performance
        wp_defer_term_counting(true);
        wp_defer_comment_counting(true);

        // Migrate
        if (
            (method_exists($this, 'migrateCategories') && is_wp_error($categories = $result = $this->migrateCategories()))
            || is_wp_error($sheets = $result = $this->_migrateSheets())
            || (method_exists($this, 'migrateSheetsCategoriesLinks') && is_wp_error($links = $result = $this->migrateSheetsCategoriesLinks($sheets, $categories)))
            || (method_exists($this, 'migrateCustomFields') && is_wp_error($result = $this->migrateCustomFields()))
        ) {
            delete_transient(Id::PREFIX . '_migration_running');
            Id::log('MIGRATION ERROR: ' . $result->get_error_message(), 'migrate');
            return;
        }

        // Remove defer counting
        wp_defer_term_counting(false);
        wp_defer_comment_counting(false);

        $this->updateStatus('all', 'complete');
        delete_transient(Id::PREFIX . '_migration_running');
        set_transient(Id::PREFIX . '_flush_rewrite_rules', true);
        $endingMemory = memory_get_usage();
        Id::log('Total memory used for migration: ' . number_format($endingMemory - $startingMemory) . ' ----------', 'migrate');
        Id::log('---------- Sign-up Sheets Migration: Complete ----------', 'migrate');
    }

    /**
     * Migrate Sheets
     *
     * @return array|WP_Error
     */
    private function _migrateSheets()
    {
        Id::log('BEGIN: Migrate Sheets', 'migrate');
        $this->updateStatus('sheets', 'running');

        $limit = 20;
        $offset = 0;
        while ($sheets = $this->_getSheets($limit, $offset)) {
            $newAndOldIds = $this->getConvertedPostIds(SheetModel::POST_TYPE);
            $newIdsByOld = array();
            foreach ($newAndOldIds as $newAndOldId) {
                $newIdsByOld[$newAndOldId->old_id] = $newAndOldId->new_id;
            }

            foreach ($sheets as $sheetKey => $sheet) {
                if (is_wp_error($checkTimeout = $this->checkTimeout())) {
                    return $checkTimeout;
                }
                Id::log(' - Old Sheet ID: ' . ((int)$sheet->id), 'migrate');

                // Get new sheet if already exists otherwise add it
                if (!empty($newIdsByOld[$sheet->id])) {
                    Id::log(' - * Sheet exists', 'migrate');
                    $newSheetId = $newIdsByOld[$sheet->id];
                } else {
                    Id::log(' - * Sheet does not exist', 'migrate');
                    $args = array(
                        'post_content' => $sheet->details,
                        'post_title' => $sheet->title,
                        'post_status' => ($sheet->trash) ? 'trash' : 'publish',
                        'post_type' => SheetModel::POST_TYPE,
                    );
                    $newSheetId = wp_insert_post($args);
                    Id::log(' - * Sheet created (NEW ID: ' . ((int)$newSheetId) . ')', 'migrate');
                }
                $sheets[$sheetKey]->new_id = (int)$newSheetId;
                update_post_meta($newSheetId, Id::PREFIX . '_id_v2_0', $sheet->id);

                // Add remaining meta fields
                if (!empty($sheet->date) && $sheet->date != '0000-00-00') {
                    update_post_meta($newSheetId, Id::PREFIX . '_date', date('Ymd', strtotime($sheet->date)));
                }
                if (!empty($sheet->use_task_dates)) {
                    update_post_meta($newSheetId, Id::PREFIX . 'dlssus_use_task_dates', 'true');
                }
                if (!empty($sheet->fields['sheet_bcc'])) {
                    update_post_meta($newSheetId, Id::PREFIX . '_sheet_bcc', $sheet->fields['sheet_bcc']);
                }
                if (!empty($sheet->fields['optional_phone'])) {
                    update_post_meta($newSheetId, Id::PREFIX . '_optional_phone', $sheet->fields['optional_phone']);
                }
                if (!empty($sheet->fields['optional_address'])) {
                    update_post_meta($newSheetId, Id::PREFIX . '_optional_address', $sheet->fields['optional_address']);
                }
                if (!empty($sheet->fields['hide_email'])) {
                    update_post_meta($newSheetId, Id::PREFIX . '_hide_email', $sheet->fields['hide_email']);
                }
                if (!empty($sheet->fields['hide_phone'])) {
                    update_post_meta($newSheetId, Id::PREFIX . '_hide_phone', $sheet->fields['hide_phone']);
                }
                if (!empty($sheet->fields['hide_address'])) {
                    update_post_meta($newSheetId, Id::PREFIX . '_hide_address', $sheet->fields['hide_address']);
                }
                if (!empty($sheet->fields['compact_signups'])) {
                    update_post_meta($newSheetId, Id::PREFIX . '_compact_signups', $sheet->fields['compact_signups']);
                }
                if (!empty($sheet->fields['use_task_checkboxes'])) {
                    update_post_meta($newSheetId, Id::PREFIX . '_use_task_checkboxes', $sheet->fields['use_task_checkboxes']);
                }
                if (!empty($sheet->fields['task_signup_limit'])) {
                    update_post_meta($newSheetId, Id::PREFIX . '_task_signup_limit', $sheet->fields['task_signup_limit']);
                }
                if (!empty($sheet->fields['contiguous_task_signup_limit'])) {
                    update_post_meta($newSheetId, Id::PREFIX . '_contiguous_task_signup_limit', $sheet->fields['contiguous_task_signup_limit']);
                }
                if (!empty($sheet->fields['sheet_reminder_days'])) {
                    update_post_meta($newSheetId, Id::PREFIX . '_sheet_reminder_days', $sheet->fields['sheet_reminder_days']);
                }
                if (!empty($sheet->fields['sheet_email_message'])) {
                    update_post_meta($newSheetId, Id::PREFIX . '_sheet_email_message', $sheet->fields['sheet_email_message']);
                }
                if (!empty($sheet->fields['sheet_email_conf_message'])) {
                    update_post_meta($newSheetId, Id::PREFIX . '_sheet_email_conf_message', $sheet->fields['sheet_email_conf_message']);
                }
                Id::log(' - * Sheet\'s standard meta fields migrated', 'migrate');

                // Migrate custom fields for this sheet manually
                if (method_exists($this, 'migrateCustomFields')) {
                    if (is_wp_error($result = $this->migrateCustomFields($sheet->id, $newSheetId))) {
                        return $result;
                    }
                    Id::log(' - * Sheet\'s custom fields migrated', 'migrate');
                }

                // Migrate tasks
                if (is_wp_error($result = $this->_migrateTasks($sheet->id, $newSheetId))) return $result;

                // Migrate categories
                if (method_exists($this, 'migrateCategoriesForSheet')) {
                    if (is_wp_error($result = $this->migrateCategoriesForSheet($sheet->id, $newSheetId))) {
                        return $result;
                    }
                    Id::log(' - * Sheet\'s categories migrated (count: ' . count($result) . ')', 'migrate');
                }

                $this->updateStatus('sheets', 'running', 1);
                unset($sheet);
            }

            $offset += $limit;
        }

        return $sheets;
    }

    /**
     * Migrate tasks
     *
     * @param int $oldSheetId
     * @param int $newSheetId
     * @return true|WP_Error
     */
    private function _migrateTasks($oldSheetId, $newSheetId)
    {
        Id::log(' - Migrate Tasks for old sheet ID ' . $oldSheetId . ' (new id ' . $newSheetId . ')', 'migrate');
        $this->updateStatus('tasks', 'running');
        $limit = 20;
        $offset = 0;
        while ($oldTasks = $this->_getTasks($oldSheetId, $limit, $offset)) {
            if (empty($oldTasks) || !is_array($oldTasks)) return true; // No tasks

            $newAndOldIds = $this->getConvertedPostIds(TaskModel::POST_TYPE);
            $newIdsByOld = array();
            foreach ($newAndOldIds as $newAndOldId) {
                $newIdsByOld[$newAndOldId->old_id] = $newAndOldId->new_id;
            }

            foreach ($oldTasks as $taskKey => $oldTask) {
                if (is_wp_error($checkTimeout = $this->checkTimeout())) {
                    return $checkTimeout;
                }

                // Get new task if already exists otherwise add it
                if (!empty($newIdsByOld[$oldTask->id])) {
                    Id::log(' - * Task exists', 'migrate');
                    $newTaskId = $newIdsByOld[$oldTask->id];
                } else {
                    Id::log(' - * Task does not exist', 'migrate');
                    $isheader = isset($oldTask->is_header) ? (bool)$oldTask->is_header : false;
                    $taskDate = isset($oldTask->date) && (bool)strtotime($oldTask->date) ? $oldTask->date : null; // check for valid date string;
                    $baseFields = array(
                        'id' => '',
                        'title' => $oldTask->title,
                        'qty' => $oldTask->qty,
                        'sort' => $oldTask->position,
                        'date' => $taskDate,
                        'is_header' => $isheader ? 1 : 0,
                        'id_v2_0' => $oldTask->id,
                        'task_row_type' => $isheader ? 'header' : ''
                    );
                    $customFields = array();
                    if (!empty($oldTask->fields) && is_array($oldTask->fields)) {
                        foreach ($oldTask->fields as $k => $v) {
                            $customFields[$k] = $v;
                        }
                        reset($oldTask->fields);
                    }
                    $fields = array_merge($baseFields, $customFields);
                    try {
                        $taskModel = new TaskModel();
                        $newTaskId = $taskModel->add($fields, $newSheetId);
                        Id::log(' - * Task created (NEW ID: ' . ((int)$newTaskId) . ')', 'migrate');
                    } catch (Exception $e) {
                        Id::log(' - * Task creation failed with error: ' . $e->getMessage(), 'migrate');
                        return new WP_Error(Id::PREFIX . '_add_task_err', $e->getMessage());
                    }
                }
                $oldTasks[$taskKey]->new_id = (int)$newTaskId;

                // Migrate signups
                if (is_wp_error($result = $this->_migrateSignups($oldTask->id, $newTaskId))) return $result;

                $this->updateStatus('tasks', 'running', 1);
                unset($oldTask);
                unset($newTask);
            }
            unset($oldTasks);
            $offset += $limit;
        }

        return true;
    }

    /**
     * Migrate signups
     *
     * @param int $oldTaskId
     * @param int $newTaskId
     * @return true|WP_Error
     */
    private function _migrateSignups($oldTaskId, $newTaskId)
    {
        Id::log(' - Migrate Signups for old task ID ' . $oldTaskId . ' (new id ' . $newTaskId . ')', 'migrate');
        $this->updateStatus('signups', 'running');
        $limit = 20;
        $offset = 0;
        while ($oldSignups = $this->_getSignups($oldTaskId, $limit, $offset)) {
            if (empty($oldSignups) || !is_array($oldSignups)) return true; // No signups

            $newAndOldIds = $this->getConvertedPostIds(SignupModel::POST_TYPE);
            $newIdsByOld = array();
            foreach ($newAndOldIds as $newAndOldId) {
                $newIdsByOld[$newAndOldId->old_id] = $newAndOldId->new_id;
            }

            foreach ($oldSignups as $oldSignup) {
                if (is_wp_error($checkTimeout = $this->checkTimeout())) {
                    return $checkTimeout;
                }

                try {
                    // Get new signup if already exists otherwise add it
                    if (!empty($newIdsByOld[$oldSignup->id])) {
                        Id::log(' - * Signup exists', 'migrate');
                    } else {
                        Id::log(' - * Signup does not exist', 'migrate');
                        $baseFields = array(
                            'signup_firstname'     => $oldSignup->firstname,
                            'signup_lastname'      => $oldSignup->lastname,
                            'signup_email'         => $oldSignup->email,
                            'signup_phone'         => $oldSignup->phone,
                            'signup_address'       => isset($oldSignup->address) ? $oldSignup->address : null,
                            'signup_city'          => isset($oldSignup->city) ? $oldSignup->city : null,
                            'signup_state'         => isset($oldSignup->state) ? $oldSignup->state : null,
                            'signup_zip'           => isset($oldSignup->zip) ? $oldSignup->zip : null,
                            'signup_date_created'  => isset($oldSignup->date_created) ? $oldSignup->date_created : null,
                            'signup_removal_token' => isset($oldSignup->removal_token) ? $oldSignup->removal_token : null,
                            'signup_reminded'      => isset($oldSignup->reminded) ? $oldSignup->reminded : null,
                            'signup_id_v2_0'       => $oldSignup->id,
                        );
                        $customFields = array();
                        foreach ($oldSignup->fields as $k => $v) {
                            $customFields['signup_' . $k] = $v;
                        }
                        if (!empty($oldSignup->reminded)) $customFields['signup_reminded'] = $oldSignup->reminded;
                        $fields = array_merge($baseFields, $customFields);
                        $signup = new SignupModel();
                        $newSignupId = $signup->add($fields, $newTaskId, true);
                        Id::log(' - * Signup created (NEW ID: ' . ((int)$newSignupId) . ')', 'migrate');
                    }
                } catch (Exception $e) {
                    Id::log(' - * Signup creation failed with error: ' . $e->getMessage(), 'migrate');
                    return new WP_Error(Id::PREFIX . '_add_signup_err', $e->getMessage());
                }

                $this->updateStatus('signups', 'running', 1);

                unset($oldSignup);
            }
            $offset += $limit;
        }

        unset($oldSignups);

        return true;
    }

    /**
     * Get all Sheets (2.0 format)
     *
     * @param int $limit
     * @param int $offset
     *
     * @return mixed array of sheets
     */
    private function _getSheets($limit, $offset)
    {
        $active_only = false;
        $sort = 'end_date DESC, s.id DESC';

        $sort_string = (!empty($sort)) ? "ORDER BY $sort" : "";

        $this->wpdb->query('SET SESSION SQL_BIG_SELECTS = 1');

        $this->wpdb->query("SHOW TABLES LIKE '{$this->data->tables['sheet_field']['name']}'");
        $fieldSheetTableExists = ($this->wpdb->num_rows > 0) ? true : false;

        $sql = '';

        if ($fieldSheetTableExists) {
            $sql = $this->wpdb->prepare("
            SELECT
                s.*
                , IF(use_task_dates.`value` IS TRUE, TRUE, FALSE) AS use_task_dates
                , IF(use_task_dates.`value` IS TRUE, t_start_date.date, s.`date`) AS start_date
                , IF(use_task_dates.`value` IS TRUE, t_end_date.date, s.`date`) AS end_date
            FROM {$this->wpdb->prefix}dls_sus_sheets s

            -- Use Task Dates
            LEFT OUTER JOIN (
                SELECT `sheet_id`, IF(`value` = 'true', TRUE, FALSE) AS `value` FROM {$this->wpdb->prefix}dls_sus_sheet_fields WHERE slug = 'use_task_dates' GROUP BY sheet_id, slug
            ) use_task_dates ON use_task_dates.sheet_id = s.id

            -- Start Date
            LEFT OUTER JOIN (
                SELECT `sheet_id`, MIN(`date`) AS `date` FROM {$this->wpdb->prefix}dls_sus_tasks WHERE `date` <> '0000-00-00' GROUP BY `sheet_id`
            ) t_start_date ON s.`id` = t_start_date.sheet_id

            -- End Date
            LEFT OUTER JOIN (
                SELECT `sheet_id`, MAX(`date`) AS `date` FROM {$this->wpdb->prefix}dls_sus_tasks WHERE `date` <> '0000-00-00' GROUP BY `sheet_id`
            ) t_end_date ON s.`id` = t_end_date.sheet_id

            WHERE 1=1
                " . (($active_only) ? "
                    AND (
                        (IF(use_task_dates.`value` IS TRUE, TRUE, FALSE) = FALSE AND (s.date >= DATE_FORMAT(NOW(), '%Y-%m-%d') OR s.date = '0000-00-00'))
                        OR (IF(use_task_dates.`value` IS TRUE, TRUE, FALSE) = TRUE AND IF(use_task_dates.`value` IS TRUE, t_end_date.date, s.date) >= DATE_FORMAT(NOW(), '%Y-%m-%d'))
                    )
                " : "") . "
            $sort_string
            LIMIT %d, %d
            ",
                $offset,
                $limit
            );
        } else {
            $sql = $this->wpdb->prepare("
            SELECT
                s.*
                , FALSE AS use_task_dates
                , s.`date` AS start_date
                , s.`date` AS end_date
            FROM {$this->wpdb->prefix}dls_sus_sheets s
            WHERE 1=1
                " . (($active_only) ? "
                    AND  (s.date >= DATE_FORMAT(NOW(), '%Y-%m-%d') OR s.date = '0000-00-00')
                " : "") . "
            $sort_string
            LIMIT %d, %d
            ",
                $offset,
                $limit
            );
        }

        $results = $this->wpdb->get_results($sql);
        foreach ($results as $key => $result) {
            $results[$key]->fields = $this->_getFields('sheet', $result->id);
        }
        $results = $this->data->stripslashes_full($results);

        return $results;
    }

    /**
     * Get tasks by sheet
     *
     * @param int $sheetId
     * @param int $limit
     * @param int $offset
     * @return mixed array of tasks
     */
    private function _getTasks($sheetId, $limit, $offset)
    {
        $results = $this->wpdb->get_results($this->wpdb->prepare(
            "SELECT * FROM {$this->wpdb->prefix}dls_sus_tasks WHERE sheet_id = %d ORDER BY position, id LIMIT %d, %d",
            $sheetId,
            $offset,
            $limit
        ));
        foreach ($results as $key => $result) {
            $results[$key]->fields = $this->_getFields('task', $result->id);
        }
        return $this->data->stripslashes_full($results);
    }

    /**
     * Get signups by task
     *
     * @param int $taskId
     * @param int $limit
     * @param int $offset
     *
     * @return mixed signups
     */
    public function _getSignups($taskId, $limit, $offset)
    {
        $results = $this->wpdb->get_results($this->wpdb->prepare(
            "SELECT * FROM {$this->wpdb->prefix}dls_sus_signups WHERE `task_id` = %d ORDER BY `id` LIMIT %d, %d",
            $taskId,
            $offset,
            $limit
        ));
        foreach ($results as $key => $result) $results[$key]->fields = $this->_getFields('signup', $result->id);

        return $this->data->stripslashes_full($results);
    }

    /**
     * Get custom fields
     *
     * @param string $entityType (ex: signup, sheet, task)
     * @param int    $entityId
     *
     * @return array
     */
    public function _getFields($entityType, $entityId)
    {
        if ($entityType == 'sheet') return $this->_getSheetFields($entityId);

        if (!$this->tableExists('dls_sus_fields')) {
            return array();
        }

        $taskOptions = get_option('dls_sus_custom_task_fields');

        // TODO: handle if sus_fields doesn't exist such as if they are coming from Free
        $results = $this->wpdb->get_results($this->wpdb->prepare(
            "SELECT * FROM {$this->wpdb->prefix}dls_sus_fields WHERE entity_type = %s AND entity_id = %d",
            $entityType,
            $entityId)
        );
        $fields = array();
        foreach ($results as $result) {
            $key = null;
            foreach ($taskOptions as $k => $value) {
                $sl = $result->slug;
                if (in_array($sl, $value)) {
                    $key = $k;
                    break;
                }
            }
            $type = '';
            if (array_key_exists($key, $taskOptions)) {
                if (array_key_exists('type', $taskOptions[$key])) {
                    $type = $taskOptions[$key]['type'];
                }
            }

            if ($type == 'dropdown') {
                $options = $this->data->optionsStringToArray($taskOptions[$key]['options']);
                $fields[$result->slug] = array_search($result->value, $options);
            } else if ($type == 'radio') {
                $options = $this->data->optionsStringToArray($taskOptions[$key]['options']);
                $fields[$result->slug] = array_search($result->value, $options);
            } else {
                $fields[$result->slug] = $result->value;
            }
        }
        return $fields;
    }

    /**
     * Get custom sheet fields (2.0 format)
     *
     * @param int $sheet_id
     *
     * @return mixed signups
     */
    private function _getSheetFields($sheet_id)
    {
        if (!$this->tableExists('dls_sus_sheet_fields')) {
            return array();
        }

        $fields = array();
        $true_false = array(
            '' => 'Global',
            'true' => 'True',
            'false' => 'False',
        );
        $sheet_fields = array(
            array(
                'name' => 'Sheet Specific BCC',
                'slug' => 'sheet_bcc',
                'type' => 'text',
                'group' => 'settings',
                'options' => null,
                'note' => esc_html__('(comma-separated list of emails to be copied on sign-up confirmations/removals for this sheet)', 'fdsus'),
            ),
            array(
                'name' => 'Set Phone as Optional',
                'slug' => 'optional_phone',
                'type' => 'dropdown',
                'group' => 'settings',
                'options' => $true_false,
                'note' => esc_html__('(Global setting in Settings > Sign-up Sheets)', 'fdsus'),
            ),
            array(
                'name' => 'Set Address as Optional',
                'slug' => 'optional_address',
                'type' => 'dropdown',
                'group' => 'settings',
                'options' => $true_false,
                'note' => esc_html__('(Global setting in Settings > Sign-up Sheets)', 'fdsus'),
            ),
            array(
                'name' => 'Hide Phone Field',
                'slug' => 'hide_phone',
                'type' => 'dropdown',
                'group' => 'settings',
                'options' => $true_false,
                'note' => esc_html__('(Global setting in Settings > Sign-up Sheets)', 'fdsus'),
            ),
            array(
                'name' => 'Hide Address Fields',
                'slug' => 'hide_address',
                'type' => 'dropdown',
                'group' => 'settings',
                'options' => $true_false,
                'note' => esc_html__('(Global setting in Settings > Sign-up Sheets)', 'fdsus'),
            ),
            array(
                'name' => 'Compact Sign-up Mode',
                'slug' => 'compact_signups',
                'type' => 'dropdown',
                'group' => 'settings',
                'options' => array(
                    ''      => esc_html__('Global', 'fdsus'),
                    'false' => esc_html__('Disabled', 'fdsus'),
                    'true'  => esc_html__('Enabled', 'fdsus'),
                    'semi'  => esc_html__('Semi-Compact', 'fdsus'),
                ),
                'note' => esc_html__('Show sign-up spots on one line with just # of open spots and a link to sign-up if open. Semi-Compact will also include the names of those who already signed up (assuming "Front-end Display Names" is not set to "anonymous"', 'fdsus'),
            ),
            array(
                'name' => 'Use task dates instead',
                'slug' => 'use_task_dates',
                'type' => 'checkbox',
                'group' => 'other',
                'options' => null,
                'note' => null,
            ),
            array(
                'name' => 'Enable Task Checkboxes',
                'slug' => 'use_task_checkboxes',
                'type' => 'dropdown',
                'group' => 'settings',
                'options' => $true_false,
                'note' => esc_html__('(Global setting in Settings > Sign-up Sheets)', 'fdsus'),
            ),
            array(
                'name' => 'Enable task sign-up limit',
                'slug' => 'task_signup_limit',
                'type' => 'dropdown',
                'group' => 'settings',
                'options' => $true_false,
                'note' => esc_html__('(Global setting in Settings > Sign-up Sheets)', 'fdsus'),
            ),
            array(
                'name' => 'Enable contiguous task sign-up limit',
                'slug' => 'contiguous_task_signup_limit',
                'type' => 'dropdown',
                'group' => 'settings',
                'options' => $true_false,
                'note' => esc_html__('(Global setting in Settings > Sign-up Sheets)', 'fdsus'),
            ),
            array(
                'name' => 'Reminder Schedule',
                'slug' => 'sheet_reminder_days',
                'type' => 'text',
                'group' => 'settings',
                'note' => '<br><em>' . esc_html__('Number of days before the date on the sign-up sheet that the email should be sent.  Use whole numbers, for example, to remind one day before use...', 'fdsus') . ' <code>1</code> ' . esc_html__('(If this is blank Global setting is used. Global setting in Settings > Sign-up Sheets.)', 'fdsus') . '</em>',
                'options' => null,
            ),
            array(
                'name' => 'Reminder Email Message',
                'slug' => 'sheet_email_message',
                'type' => 'textarea',
                'group' => 'settings',
                'note' => '<br><em>' . esc_html__('Global setting in Settings > Sign-up Sheets', 'fdsus') . '</em>',
                'options' => null,
            ),
            array(
                'name' => 'Confirmation Email Message',
                'slug' => 'sheet_email_conf_message',
                'type' => 'textarea',
                'group' => 'settings',
                'note' => '<br><em>' . esc_html__('Global setting in Settings > Sign-up Sheets', 'fdsus') . '</em>',
                'options' => null,
            ),
        );

        // Set default sheet fields
        foreach ($sheet_fields as $sheet_field) {
            $fields[$sheet_field['slug']] = null;
        }
        reset($sheet_fields);

        // Get sheet field data
        $results = $this->wpdb->get_results(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->wpdb->prefix}dls_sus_sheet_fields WHERE sheet_id = %d",
                $sheet_id
            )
        );
        foreach ($results as $result) {
            $fields[$result->slug] = maybe_unserialize($result->value);
        }

        return $fields;
    }

    /**
     * Update migration status option
     *
     * @param string $type (ex: sheets, categories, all)
     * @param string $status (ex: running, complete)
     * @param int $numCompleted how many items of this type were added since the last update
     * @return bool
     */
    public function updateStatus($type, $status, $numCompleted = 0)
    {
        $option = $this->getStatus();
        if (!is_array($option)) $option = array();
        $option['state'] = $status;
        $option['last_updated'] = current_time('timestamp', 1);
        if ($type != 'all') $option [$type . '_completed'] += $numCompleted;
        return update_option($this->statusKey, $option);
    }

    /**
     * Get migration status
     *
     * @return array
     */
    public function getStatus()
    {
        $array = array(
            'state' => null,
            'last_updated' => null,
            'sheets_completed' => 0,
            'tasks_completed' => 0,
            'signups_completed' => 0,
            'categories_completed' => 0,
        );
        $status = get_option($this->statusKey);
        if (empty($status) || !is_array($status)) return $array;
        foreach ($array as $key => $value) {
            if (!isset($status[$key])) continue;
            $array[$key] = $status[$key];
        }
        return $array;
    }

    /**
     * Check if the migration is flagged as running
     *
     * @return bool
     */
    public function isRunning()
    {
        return get_transient(Id::PREFIX . '_migration_running') == $this->runningTransientValue;
    }

    /**
     * Is the migration flagged to have been completed
     *
     * @return bool
     */
    public function isComplete()
    {
        $status = $this->getStatus();
        return get_option($status['state']) == 'complete';
    }

    /**
     * Check if the timeout is getting close and schedule the cron to pickup where we left off
     *
     * @return WP_Error|true
     */
    public function checkTimeout()
    {
        if (!$this->timeout->isClose()) {
            return true;
        }

        $timeoutRerunCount = (int)get_transient(Id::PREFIX . '_migration_timeout_rerun_count');
        if ($timeoutRerunCount < 30) {
            set_transient(Id::PREFIX . '_migration_timeout_rerun_count', $timeoutRerunCount + 1);
            $update = new DbUpdate();
            $update->scheduleAsyncUpdate();
        }

        return new WP_Error(Id::PREFIX . '_migrate_timeout', esc_html__('Sign-up Sheets migration timed out.', 'fdsus'));
    }

    /**
     * Does table exists?
     *
     * @param string $table
     *
     * @return bool
     */
    public function tableExists($table)
    {
        if (isset($this->tablesExist[$table])) {
            return $this->tablesExist[$table];
        }

        $tableCountSql = "SELECT count(*)
            FROM information_schema.TABLES
            WHERE (TABLE_SCHEMA = '" . DB_NAME . "') AND (TABLE_NAME = '{$this->wpdb->prefix}{$table}')";
        $tableCount = (int)$this->wpdb->get_var($tableCountSql);
        $this->tablesExist[$table] = $tableCount > 0;

        return $this->tablesExist[$table];
    }

    /**
     * Get array of converted post IDs by post type
     *
     * @param string $postType
     *
     * @return array|object|null
     */
    protected function getConvertedPostIds($postType)
    {
        $sql = $this->wpdb->prepare(
            "SELECT MAX(`posts`.`ID`) AS `new_id`, `v2_0_meta`.`meta_value` AS `old_id` FROM {$this->wpdb->posts} `posts`
            INNER JOIN {$this->wpdb->postmeta} `v2_0_meta` ON ( `posts`.`ID` = `v2_0_meta`.`post_id` AND `v2_0_meta`.`meta_key` = 'dlssus_id_v2_0')
            WHERE `posts`.`post_type` = %s
            GROUP BY v2_0_meta.meta_value",
            $postType
        );

        return $this->wpdb->get_results($sql);
    }
}
