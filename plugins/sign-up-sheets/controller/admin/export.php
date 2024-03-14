<?php
/**
 * Admin Sheet Export
 */

namespace FDSUS\Controller\Admin;

use FDSUS\Id;
use FDSUS\Model\Data;
use FDSUS\Model\Sheet as SheetModel;
use FDSUS\Model\Signup as SignupModel;
use FDSUS\Model\SheetCollection as SheetCollectionModel;
use FDSUS\Model\Task as TaskModel;
use FDSUS\Model\Settings;
use WP_Post;
use WP_Query;
use wpdb;

class Export
{
    private $data;
    private $action = 'fdsus-export';

    /** @var bool */
    private $allsheets;

    public function __construct()
    {
        $this->data = new Data();
        add_action('current_screen', array(&$this, 'maybeProcessExport'));
        add_filter('views_edit-dlssus_sheet', array(&$this, 'addExportAllLink'), 10, 1);
        add_action('fdsus_edit_sheet_quick_info', array(&$this, 'addExportSheetLink'), 20, 1);
        add_action('fdsus_manage_signup_h1_suffix', array(&$this, 'addExportSheetLinkOnManageSignups'), 20, 1);
        add_filter('posts_where', array(&$this, 'modifyCollectionWhere'), 10, 2);
    }

    /**
     * Add export all link
     *
     * @param array $views
     *
     * @return mixed
     */
    public function addExportAllLink($views)
    {
        $views['export_all'] = sprintf(
            '<a href="%s">%s</a>',
            admin_url(
                add_query_arg(
                    array(
                        'post_type' => SheetModel::POST_TYPE,
                        'page'      => 'fdsus-manage',
                        'action'    => $this->action
                    ), 'edit.php'
                )
            ),
            esc_html__('Export All as CSV', 'fdsus')
        );
        return $views;
    }

    /**
     * @param $post
     *
     * @return string|null
     */
    public function getExportUrl($post)
    {
        return add_query_arg(array('action' => $this->action), Settings::getManageSignupsPageUrl($post->ID));
    }

    /**
     * Add export sheet link
     *
     * @param WP_Post $post
     */
    public function addExportSheetLink($post)
    {
        echo sprintf(
            '<a href="%s" id="dls-sus-export-sheet" class="quick-info-item">%s</a>',
            $this->getExportUrl($post),
            esc_html__('Export as CSV', 'fdsus')
        );
    }

    /**
     * Add export sheet link on manage signups page
     *
     * @param SheetModel $sheet
     */
    public function addExportSheetLinkOnManageSignups($sheet)
    {
        echo sprintf(
            '<a href="%s" class="add-new-h2 page-title-action"><span class="dashicons dashicons-download"></span> %s</a>',
            $this->getExportUrl($sheet),
            esc_html__('Export as CSV', 'fdsus')
        );
    }

    /**
     * Maybe Process Export
     */
    public function maybeProcessExport()
    {
        if (!function_exists('\get_current_screen')) {
            return;
        }

        $screen = \get_current_screen();

        if (!isset($_GET['action']) || $_GET['action'] != $this->action
            || !is_a($screen, 'WP_Screen')
            || $screen->base != Id::PREFIX . '_sheet_page_fdsus-manage'
        ) {
            return;
        }

        $caps = $this->data->get_add_caps_array(SheetModel::POST_TYPE);
        if (!current_user_can('manage_options') && !current_user_can($caps['read_post'])) {
            wp_die(esc_html__('You do not have sufficient permissions to export.', 'fdsus'));
        }

        if (isset($_GET['sheet_id']) && !is_numeric($_GET['sheet_id'])) {
            wp_die(esc_html__('Invalid sheet ID.', 'fdsus'));
        }

        // Process Export
        $this->create();
    }

    /**
     * Get sheet row array
     *
     * @param SheetModel $sheet
     *
     * @return array
     */
    private function getSheetRowArray($sheet)
    {
        $row[] = $sheet->ID;
        $row[] = $sheet->post_title;
        $row[] = !$sheet->dlssus_use_task_dates && !empty($sheet->dlssus_start_date)
                ? date('Y-m-d', strtotime($sheet->dlssus_start_date)) : null;

        /**
         * Filter for row data after sheet title
         *
         * @param array      $row
         * @param SheetModel $sheet
         *
         * @return array
         * @since 2.2
         */
        $row = apply_filters('fdsus_export_row_after_sheet', $row, $sheet);

        return $row;
    }

    /**
     * Get task row array
     *
     * @param TaskModel  $task
     * @param SheetModel $sheet
     *
     * @return array
     */
    private function getTaskRowArray($task, $sheet)
    {
        $row = array();

        if (!empty($task)) {
            $row[] = $task->ID;
            $row[] = $task->post_title;
        } else {
            // Empty
            $row = array('', '', '');

            if (!empty($tasks)) {
                foreach ($tasks as $t) {
                    $row[] = '';
                }
            }
        }

        /**
         * Filter for headers after task title
         *
         * @param array      $row
         * @param TaskModel  $task
         * @param SheetModel $sheet
         * @param bool       $isAllSheets
         *
         * @return array
         * @since 2.2
         */
        $row = apply_filters('fdsus_export_row_after_task', $row, $task, $sheet, $this->allsheets);

        return $row;
    }

    /**
     * Get signup row array
     *
     * @param SignupModel|int $signup
     * @param TaskModel       $task
     * @param SheetModel      $sheet
     *
     * @return array
     */
    public function getSignupRowArray($signup, $task, $sheet)
    {
        $row = array();

        if (!empty($signup)) {
            $row[] = $signup->ID;
            $row[] = $signup->dlssus_firstname;
            $row[] = $signup->dlssus_lastname;
            $row[] = $signup->dlssus_phone;
            $row[] = $signup->dlssus_email;
            $row[] = $signup->dlssus_address;
            $row[] = $signup->dlssus_city;
            $row[] = $signup->dlssus_state;
            $row[] = $signup->dlssus_zip;
            $row[] = !empty($signup->dlssus_user_id) ? $signup->dlssus_user_id : '';
        } else {
            $row = array('', '', '', '', '', '', '', '', '', '');
        }

        /**
         * Filter for headers after signup
         *
         * @param array       $row
         * @param SignupModel $signup
         * @param TaskModel   $task
         * @param SheetModel  $sheet
         * @param bool        $isAllSheets
         *
         * @return array
         * @since 2.2
         */
        $row = apply_filters('fdsus_export_row_after_signup', $row, $signup, $task, $sheet, $this->allsheets);

        return $row;
    }

    /**
     * Create export file with data
     */
    public function create()
    {
        if (isset($_GET['sheet_id'])) {
            $sheetId = (int)$_GET['sheet_id'];
            $sheets = array(new SheetModel($sheetId));
            $this->allsheets = false;
        } else {
            $sheetCollection = new SheetCollectionModel();
            $sheets = $sheetCollection->get();
            $this->allsheets = true;
        }

        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=sign-up-sheets-" . date('Ymd-His') . ".csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $headersArray = array();
        $headersArray[] = esc_html__('Sheet ID', 'fdsus');
        $headersArray[] = esc_html__('Sheet Title', 'fdsus');
        $headersArray[] = esc_html__('Sheet Date', 'fdsus');

        /**
         * Filter for headers after sheet headers
         *
         * @param array        $headersArray
         * @param SheetModel[] $sheets
         *
         * @return array
         * @since 2.2
         */
        $headersArray = apply_filters('fdsus_export_headers_after_sheet', $headersArray, $sheets);

        $headersArray[] = esc_html__('Task ID', 'fdsus');
        $headersArray[] = esc_html__('Task Title', 'fdsus');

        /**
         * Filter for headers after task title
         *
         * @param array        $headersArray
         * @param SheetModel[] $sheets
         *
         * @return array
         * @since 2.2
         */
        $headersArray = apply_filters('fdsus_export_headers_after_task', $headersArray, $sheets);

        $headersArray[] = esc_html__('Sign-up ID', 'fdsus');
        $headersArray[] = esc_html__('Sign-up First Name', 'fdsus');
        $headersArray[] = esc_html__('Sign-up Last Name', 'fdsus');
        $headersArray[] = esc_html__('Sign-up Phone', 'fdsus');
        $headersArray[] = esc_html__('Sign-up Email', 'fdsus');
        $headersArray[] = esc_html__('Address', 'fdsus');
        $headersArray[] = esc_html__('City', 'fdsus');
        $headersArray[] = esc_html__('State', 'fdsus');
        $headersArray[] = esc_html__('Zip', 'fdsus');
        $headersArray[] = esc_html__('User ID', 'fdsus');

        /**
         * Filter for headers after core signup info
         *
         * @param array        $headersArray
         * @param SheetModel[] $sheets
         *
         * @return array
         * @since 2.2
         */
        $headersArray = apply_filters('fdsus_export_headers_core_signup_info', $headersArray, $sheets);

        $csv = $this->convertArrayToCsvRow($headersArray);

        // Build Body
        foreach ($sheets as $sheet) {
            if ($sheet instanceof WP_Post) {
                $sheet = new SheetModel($sheet);
            }
            $tasks = $sheet->getTasks();
            if (!empty($tasks)) {
                foreach ($tasks as $task) {
                    $signups = $task->getSignups();

                    for ($i = 0; $i < $task->dlssus_qty; $i++) {
                        /** @var SignupModel|int $signup */
                        $signup = !empty($signups[$i]) ? $signups[$i] : 0;
                        $rowArray = $this->getSheetRowArray($sheet);
                        $rowArray = array_merge($rowArray, $this->getTaskRowArray($task, $sheet));
                        $rowArray = array_merge($rowArray, $this->getSignupRowArray($signup, $task, $sheet));
                        $csv .= $this->convertArrayToCsvRow($rowArray);
                    }
                }
            }
        }
        echo $csv;
        exit;
    }

    /**
     * Convert array to CSV Row
     *
     * @param $array
     *
     * @return string
     */
    private function convertArrayToCsvRow($array)
    {
        $array = array_map(array(&$this, 'cleanCsv'), $array);
        return '"' . implode('","', $array) . '"' . PHP_EOL;
    }

    /**
     * Clean, escape and sanitize CSV values
     *
     * @param   string $value input value
     *
     * @return  string   cleaned value
     */
    private function cleanCsv($value)
    {
        if (empty($value)) {
            return $value;
        }

        // Prevent CSV Injection - if value starts with +, -, =, @ or " prepend ' to neutralize
        if (strpos($value, '=') === 0
            || strpos($value, '@') === 0
            || strpos($value, '+') === 0
            || strpos($value, '-') === 0
            || strpos($value, '"') === 0
        ) {
            $value = "'" . $value;
        }

        // Escape double quotes
        $value = str_replace('"', '""', $value);

        return $value;
    }

    /**
     * Modify collection where for export
     *
     * @param string   $where   String containing where clauses
     * @param WP_Query $wpQuery Object
     *
     * @return string
     * @global wpdb    $wpdb
     */
    public function modifyCollectionWhere($where, $wpQuery)
    {
        if (!function_exists('\get_current_screen')) {
            return $where;
        }

        $screen = \get_current_screen();

        if (!isset($_GET['action']) || $_GET['action'] != $this->action
            || !is_a($screen, 'WP_Screen')
            || $screen->base != Id::PREFIX . '_page_fdsus-manage'
            || !is_admin()
            || is_single()
        ) {
            return $where;
        }

        global $wpdb;

        // Exclude sheets that have no tasks
        $where .= "AND {$wpdb->posts}.ID IN
            (SELECT DISTINCT {$wpdb->posts}.post_parent FROM {$wpdb->posts}
                WHERE {$wpdb->posts}.post_parent > 0
                AND {$wpdb->posts}.post_type = '" . TaskModel::POST_TYPE ."'
                AND {$wpdb->posts}.post_status = 'publish'
            )";

        return $where;
    }
}
