<?php
/**
 * Controller for editing and adding sheets
 */

namespace FDSUS\Controller\Admin;

use FDSUS\Id;
use FDSUS\Model\Sheet as SheetModel;
use FDSUS\Model\Task as TaskModel;
use WP_Post;

class EditSheet
{

    public function __construct()
    {
        add_filter('dlsmb_override_repeater_row', array(&$this, 'repeaterRowOutput'), 10, 4);
        add_filter('dlsmb_repeater_actions', array(&$this, 'repeaterActions'), 10, 2);
        add_filter('dlsmb_display_meta_field_value', array(&$this, 'displayTasks'), 10, 4);
        add_action('submitpost_box', array(&$this, 'topFormButtons'));
    }

    /**
     * Repeater row output override
     *
     * @param null|bool $check Whether to allow updating metadata.
     * @param array     $field
     * @param array     $v
     * @param int       $i
     *
     * @return null|mixed
     */
    public function repeaterRowOutput($check, $field, $v, $i)
    {
        if (!array_key_exists('task_row_type', $v) || $v['task_row_type'] !== 'header') return $check;

        $check = true;

        echo '<tr class="dlsmb-repeater-dlssus_tasks-row dls-sus-task-header-row" id="dlsmb-repeater-dlssus_tasks-row-' . $i . '"><td colspan="99">
            <input name="dlssus_tasks[' . $i . '][title]" value="' . $v['title'] . '" type="text">
            <input name="dlssus_tasks[' . $i . '][task_row_type]" value="header" type="hidden">
            <input name="dlssus_tasks[' . $i . '][id]" value="' . $v['id'] . '" type="hidden">
            <a href="#" class="dlsmb-icon dlsmb-js-remove" title="Delete Row"><i class="dashicons dashicons-trash"></i></a>
            </td></tr>
        ';

        return $check;
    }

    /**
     * Add custom actions to task repeater
     *
     * @param array $actions
     * @param array $field
     *
     * @return mixed
     */
    public function repeaterActions($actions, $field)
    {
        if ($field['key'] != Id::PREFIX . '_tasks') {
            return $actions;
        }

        $actions['copy'] = array(
            'title' => esc_html__('Copy Row', 'fdsus'),
            'icon'  => 'dashicons dashicons-admin-page',
        );

        return $actions;
    }

    /**
     * Display tasks in admin meta field (overrides default from DLSMB)
     *
     * @param null|bool $value    Value of field
     * @param int       $field    Meta field
     * @param int       $post_id  Post ID
     * @param array     $meta_box Current meta box data
     *
     * @return mixed
     */
    public function displayTasks($value, $field, $post_id, $meta_box)
    {
        $return['value'] = $value;
        $return['field'] = $field;

        if ($field['key'] !== Id::PREFIX . '_tasks') {
            return $return;
        }

        $newValue = array();
        $sheet = new SheetModel($post_id);
        $tasks = $sheet->getTasks();

        if (!empty($tasks)) {
            $i = 0;
            foreach ($tasks as $task) {
                $task = new TaskModel($task->ID);
                $taskFields = array(
                    'title' => $task->post_title,
                    'sort'  => $i,
                    'id'    => $task->ID,
                );

                foreach ($field['fields'] as $f) {
                    if ($f['key'] != 'title' && $f['key'] != 'id') {
                        $taskFields[$f['key']] = $task->{Id::PREFIX . '_' . $f['key']};
                    }
                }

                $newValue[] = $taskFields;
                $i++;
            }
            reset( $tasks );
        }

        $return['value'] = array($newValue);

        /**
         * Filter for edit sheet display tasks fields
         *
         * @param array       $return
         * @param SheetModel  $sheet
         * @param TaskModel[] $tasks
         *
         * @return array
         * @since 2.2
         */
        return apply_filters('fdsus_edit_sheet_display_tasks', $return, $sheet, $tasks);
    }

    /**
     * Add quick info to top of edit form
     *
     * @param WP_Post $post
     */
    public function topFormButtons(WP_Post $post)
    {
        if ($post->post_type == SheetModel::POST_TYPE && !empty($_GET['action']) && $_GET['action'] === 'edit') {
            ?>
            <div class="fdsus-edit-quick-info" role="group" aria-label="<?php esc_attr_e('Sheet Quick Info', 'fdsus') ?>">
                <span class="quick-info-item quick-info-id"><strong><?php esc_html_e('Sheet ID', 'fdsus') ?>: </strong> <code><?php echo $post->ID ?></code></span>
                <?php do_action('fdsus_edit_sheet_quick_info', $post); ?>
            </div>
            <?php
        }
    }

}
