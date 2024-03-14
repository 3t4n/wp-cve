<?php
/**
 * Tasks Table Controller
 */

namespace FDSUS\Controller;

use FDSUS\Id;
use FDSUS\Model\Sheet as SheetModel;
use FDSUS\Model\Task as TaskModel;
use FDSUS\Model\Signup as SignupModel;
use FDSUS\Model\Settings;
use FDSUS\Model\TaskTable as TaskTableModel;

class TaskTable extends Base
{
    /** @var SheetModel */
    public $sheet;

    /** @var TaskModel[] */
    public $tasks;

    /** @var array */
    public $config = array(
        'showSignupLink'                => false,
        'isFullCompact'                 => false,
        'displayAll'                    => false,
        'customFields'                  => array(),
        'displayCustomExtraFieldsCount' => 0,
        'lastRowKey'                    => 0,
        'spotIndex'                     => 1, // index of current spot
        'openSpots'                     => 0,
        'emptySignupColspan'            => 1,
        'showEmptySpotSignupLink'       => true,
    );

    /** @var TaskTableModel */
    public $table;

    /**
     * TaskTable constructor.
     *
     * @param SheetModel $sheet
     * @param array      $atts
     */
    public function __construct($sheet, $atts = array())
    {
        $this->sheet = $sheet;

        /**
         * @var bool $showSignupLink
         */
        extract(shortcode_atts(array(
            'showSignupLink' => false,
        ), $atts));
        $this->config['showSignupLink'] = $showSignupLink;

        $this->tasks = $this->sheet->getTasks();
        $this->table = new TaskTableModel($sheet);

        parent::__construct();
    }

    /**
     * Set variables
     */
    private function setVariables()
    {
        $this->config['isFullCompact'] = $this->sheet->isFullCompact() && !is_admin();
        $this->config['displayAll'] = Settings::isDisplayAllSignupDataEnabled() || is_admin();

        /**
         * Filters task table configs at the beginning of the process
         *
         * @param array      $config
         * @param SheetModel $sheet
         *
         * @return TaskTableModel
         * @since 2.2
         */
        $this->config = apply_filters('fdsus_tasktable-config-init', $this->config, $this->sheet);
    }

    /**
     * Build header output
     *
     * @return string
     */
    private function buildHeader()
    {
        $out = '';
        foreach ($this->table->header as $headerCell) {
            $out .= sprintf('<th scope="col" class="%s %s">%s</th>',
                Id::PREFIX . '-tasktable-col-' . esc_attr($headerCell['slug']),
                $headerCell['class'] ? $headerCell['class'] : '',
                $headerCell['value']
            );
        }

        return '<tr>' . $out . '</tr>';
    }

    /**
     * Build Body
     *
     * @return string
     */
    private function buildbody()
    {
        $out = '';
        foreach ($this->table->rows as $row) {
            $out .= sprintf('<tr class="%s">', $row->class ? $row->class : '');
            foreach ($row->cells as $cell) {
                $scope = '';
                if ($cell['element'] === 'th') {
                    $scope = ' scope="' . ($cell['rowspan'] > 1 ? 'rowgroup' : 'row') . '"';
                }
                $out .= sprintf(
                    '<%s class="%s %s"%s%s%s>%s</%s>',
                    $cell['element'] === 'th' ? 'th' : 'td',
                    Id::PREFIX . '-tasktable-col-' . esc_attr($cell['slug']),
                    $cell['class'] ? $cell['class'] : '',
                    $cell['colspan'] > 1 ? ' colspan="' . ((int)$cell['colspan']) . '"' : '',
                    $cell['rowspan'] > 1 ? ' rowspan="' . ((int)$cell['rowspan']) . '"' : '',
                    $scope,
                    $cell['value'],
                    $cell['element'] === 'th' ? 'th' : 'td'
                );
            }
            $out .= '</tr>';
        }

        return $out;
    }

    /**
     * Build Header Data
     */
    private function buildHeaderData()
    {
        // Remaining empty spots
        if ($this->config['displayAll'] && !$this->config['isFullCompact']) {
            $this->config['emptySignupColspan'] += 7;
            if ($this->sheet->showPhone()) {
                $this->config['emptySignupColspan']++;
            }
            if ($this->sheet->showAddress()) {
                $this->config['emptySignupColspan'] = $this->config['emptySignupColspan'] + 4;
            }
            if (is_admin()) {
                $this->config['emptySignupColspan']++;
            }
        }
        $this->config['emptySignupColspan'] += $this->config['displayCustomExtraFieldsCount'];

        /**
         * Filters task table model during header data after task title
         *
         * @param TaskTableModel $table
         * @param SheetModel     $sheet
         * @param array          $config
         *
         * @return TaskTableModel
         * @since 2.2
         */
        $this->table = apply_filters('fdsus_tasktable-table-before_header_data', $this->table, $this->sheet, $this->config);

        $this->table->addHeaderCell('task_title', esc_html(Settings::$text['task_title_label']['value']));

        /**
         * Filters task table model during header data after task title
         *
         * @param TaskTableModel $table
         * @param SheetModel     $sheet
         * @param array          $config
         *
         * @return TaskTableModel
         * @since 2.2
         */
        $this->table = apply_filters('fdsus_tasktable-table-header_data_after_task_title', $this->table, $this->sheet, $this->config);

        $this->table->addHeaderCell('name', esc_html__('Name', 'fdsus'));

        if ($this->config['displayAll']) {
            $this->table->addHeaderCell('email', esc_html__('E-mail', 'fdsus'));
            if ($this->sheet->showPhone()) {
                $this->table->addHeaderCell('phone', esc_html__('Phone', 'fdsus'));
            }
            if ($this->sheet->showAddress()) {
                $this->table->addHeaderCell('address', esc_html__('Address', 'fdsus'));
                $this->table->addHeaderCell('city', esc_html__('City', 'fdsus'));
                $this->table->addHeaderCell('state', esc_html__('State', 'fdsus'));
                $this->table->addHeaderCell('zip', esc_html__('Zip', 'fdsus'));
            }
        }

        /**
         * Filters task table model during header data after core signup info
         *
         * @param TaskTableModel $table
         * @param SheetModel     $sheet
         * @param array          $config
         *
         * @return TaskTableModel
         * @since 2.2
         */
        $this->table = apply_filters('fdsus_tasktable-table-header_data_after_core_signup_info', $this->table, $this->sheet, $this->config);

        if ($this->config['displayAll']) {
            if (is_admin()) {
                $this->table->addHeaderCell(
                    'clear-checkbox',
                    '<label>
                        <span class="sr-only">' . __('Select all spots to Clear', 'fdsus') . '</span>
                        <input type="checkbox" value="" id="select-all-clear">
                    </label>
                    <input name="multi_submit" type="submit" class="button" value="' . esc_html__('Clear Selected', 'fdsus') . '" onclick="return confirm(\'' . esc_html__('This will permanently remove all selected sign-ups for this sheet.', 'fdsus') . '\');">',
                    'fdsus-col-clear'
                );
            }
        }
    }

    /**
     * Build body data
     */
    private function buildBodyData()
    {
        $taskIndex = 0;
        foreach ($this->tasks as $task) {
            if (!Id::isPro() && $task->dlssus_task_row_type === 'header') {
                continue;
            }

            $taskIndex++;
            $continue = false;
            $this->config['taskRowCount'] = $task->dlssus_qty;
            $this->config['spotIndex'] = 1; // index at which to start displaying open spots
            $class['task_index'] = 'dlssus-task-' . $taskIndex;
            $class['last_task'] = (end($this->tasks) === $task) ? 'dls-sus-last-task' : null;
            $class['task_expired'] = ($this->sheet->isExpired() || $task->isExpired()) ? 'dls-sus-task-expired' : null;

            /**
             * Filters task table configs at the beginning of the task loop
             *
             * @param array      $config
             * @param SheetModel $sheet
             * @param TaskModel  $task
             *
             * @return TaskTableModel
             * @since 2.2
             */
            $this->config = apply_filters('fdsus_tasktable-config-task_loop_start', $this->config, $this->sheet, $task);

            /**
             * Filters task table model at the start of the task loop
             *
             * @param TaskTableModel $table
             * @param SheetModel     $sheet
             * @param TaskModel      $task
             * @param array          $config
             *
             * @return TaskTableModel
             * @since 2.2
             */
            $this->table = apply_filters('fdsus_tasktable-table-body_data_task_loop_start', $this->table, $this->sheet, $task, $this->config);

            /**
             * Filter continue flag at the start of the task loop
             *
             * @param bool      $continue
             * @param TaskModel $task
             *
             * @return bool
             * @since 2.2
             */
            $continue = apply_filters('fdsus_tasktable-continue-body_data_task_loop_start', $continue, $task);
            if ($continue) {
                continue;
            }

            $signups = $task->getSignups();
            $this->config['openSpots'] = $task->getOpenSpotCount();

            $signupIndex = 0;
            if (!$this->config['isFullCompact']) {
                if (!empty($signups) && is_array($signups)) {
                    foreach ($signups AS $signup) {
                        $signupIndex++;
                        $class['task_first_row'] = $signupIndex === 1 ? 'fdsus-task-first-row' : null;
                        $class['last_spot'] = ($this->config['spotIndex'] == $task->dlssus_qty) ? 'fdsus-task-last-row' : null;
                        $rowClass = 'dls-sus-row dls-sus-filled ' . implode(' ', $class) . ' dls-sus-spot-' . ((int)$this->config['spotIndex']);
                        $this->config['lastRowKey'] = $this->table->addRow($signup->ID, $task->ID, $rowClass);

                        /**
                         * Filters task table model at the start of the signup loop
                         *
                         * @param TaskTableModel $table
                         * @param SheetModel     $sheet
                         * @param TaskModel      $task
                         * @param SignupModel    $signup
                         * @param array          $config
                         *
                         * @return TaskTableModel
                         * @since 2.2
                         */
                        $this->table = apply_filters(
                            'fdsus_tasktable-table-body_data_signup_loop_start',
                            $this->table, $this->sheet, $task, $signup, $this->config
                        );

                        // Task Title (if at least one spot is filled)
                        if (($this->config['spotIndex'] === 1)) {
                            $this->table->addRowCell('auto', 'task_title', wp_kses_post($task->post_title),
                                '', 1, $this->config['taskRowCount'], 'th'
                            );
                        }

                        /**
                         * Filters task table model in the signup loop after the task title
                         *
                         * @param TaskTableModel $table
                         * @param SheetModel     $sheet
                         * @param TaskModel      $task
                         * @param SignupModel    $signup
                         * @param array          $config
                         *
                         * @return TaskTableModel
                         * @since 2.2
                         */
                        $this->table = apply_filters(
                            'fdsus_tasktable-table-body_data_signup_loop_after_task_title',
                            $this->table, $this->sheet, $task, $signup, $this->config
                        );

                        // Spot Number and Name
                        if ($this->config['displayAll']) {
                            $name = esc_html($signup->dlssus_firstname . ' ' . $signup->dlssus_lastname);
                        } else {
                            $name = esc_html($signup->dlssus_firstname . ' ' . substr($signup->dlssus_lastname, 0, 1)) . '.';
                        }

                        // Wrap name in span
                        $name = '<span class="fdsus-name">' . $name . '</span>';

                        /**
                         * Filter for signup name
                         *
                         * @param string      $name
                         * @param SheetModel  $sheet
                         * @param TaskModel   $task
                         * @param SignupModel $signup
                         * @param array       $config
                         *
                         * @return string
                         * @since 2.2
                         */
                        $name = apply_filters('fdsus_tasktable_signup_name', $name, $this->sheet, $task, $signup, $this->config);

                        $cellValue = sprintf(
                            '<span class="dls-sus-spot-num">%s</span> <span>%s</span>',
                            /* translators: %d is replaced with the spot number */
                            sprintf(esc_html__('#%d:', 'fdsus'), (int)$this->config['spotIndex']),
                            $name
                        );
                        $this->table->addRowCell('auto', 'name', $cellValue);

                        if ($this->config['displayAll']) {
                            $this->table->addRowCell('auto', 'email', esc_html($signup->dlssus_email));
                            if ($this->sheet->showPhone()) {
                                $this->table->addRowCell('auto', 'phone', esc_html($signup->dlssus_phone));
                            }
                            if ($this->sheet->showAddress()) {
                                $this->table->addRowCell('auto', 'address', esc_html($signup->dlssus_address));
                                $this->table->addRowCell('auto', 'city', esc_html($signup->dlssus_city));
                                $this->table->addRowCell('auto', 'state', esc_html($signup->dlssus_state));
                                $this->table->addRowCell('auto', 'zip', esc_html($signup->dlssus_zip));
                            }
                        }

                        /**
                         * Filters task table model at the start of the task loop
                         *
                         * @param TaskTableModel $table
                         * @param SheetModel     $sheet
                         * @param TaskModel      $task
                         * @param SignupModel    $signup
                         * @param array          $config
                         *
                         * @return TaskTableModel
                         * @since 2.2
                         */
                        $this->table = apply_filters('fdsus_tasktable-table-body_data_signup_loop_after_core_signup_info', $this->table, $this->sheet, $task, $signup, $this->config);

                        // Clear Checkbox
                        if ($this->config['displayAll'] && is_admin()) {
                            $clear_url = wp_nonce_url(
                                '?post_type=' . SheetModel::POST_TYPE . '&amp;action=clear&amp;page=fdsus-manage&amp;sheet_id=' . $_GET['sheet_id'] . '&amp;clear=' . $signup->ID,
                                'clear-signup_' . $signup->ID,
                                'manage_signup_nonce'
                            );
                            $userDisplay = '(not logged in)';
                            if ($user = get_userdata($signup->dlssus_user_id)) {
                                $userDisplay = '<a href="' . esc_url(get_edit_profile_url($user->ID)) . '">' . $user->user_login . '</a>';
                            }
                            $cellValue = sprintf(
                                '
                                <span class="delete">
                                    <label>
                                        <span class="sr-only">'
                                            .  /* translators: %s is replaced with the index of the spot within the current task */
                                            sprintf(__('Select spot #%s to clear', 'fdsus'), (int)$this->config['spotIndex']) . '</span>
                                        <input type="checkbox" name="clear[]" value="' . (int)$signup->ID . '" class="clear-checkbox">
                                    </label>
                                    <a href="' . esc_attr($clear_url) . '" aria-label="%1$s" title="%1$s" %2$s>
                                        <i class="dashicons dashicons-trash" aria-hidden="true"></i>
                                    </a>
                                </span>
                                <a href="' . Settings::getAdminEditSignupPageUrl($signup->ID, $_GET['sheet_id']) . '">
                                    <span class="sr-only">' . __('Edit', 'fdsus') . '</span>
                                    <i class="dashicons dashicons-edit" aria-hidden="true"></i>
                                </a>
                                <div class="fdsus-toggletip">
                                    <a href="#/" aria-expanded="false"
                                        id="fdsus-signup-metadata-control-' . (int)$signup->ID . '"
                                        aria-controls="fdsus-signup-metadata-detail-' . (int)$signup->ID . '">
                                        <span class="sr-only">' . __('Additional Details', 'fdsus') . '</span>
                                        <i class="dashicons dashicons-info" aria-hidden="true"></i>
                                    </a>
                                    <div role="region" hidden=""
                                        id="fdsus-signup-metadata-detail-' . (int)$signup->ID . '"
                                        aria-labelledby="fdsus-signup-metadata-control-' . (int)$signup->ID . '">
                                        <ul class="fdsus-signup-metadata">
                                            <li>' . __('Added', 'fdsus') . ': %3$s</li>
                                            <li>' . __('Updated', 'fdsus') . ': %4$s</li>
                                            <li>' . __('Linked user', 'fdsus') . ':  %5$s</li>
                                        </ul>
                                    </div>
                                </div>
                                ',
                                esc_html__('Clear Spot Now', 'fdsus'),
                                'onclick="return confirm(\'' . esc_html__('This will permanently remove this sign-up.', 'fdsus') . '\');"',
                                date('Y-m-d ' . get_option('time_format'), strtotime($signup->post_date)),
                                date('Y-m-d ' . get_option('time_format'), strtotime($signup->post_modified)),
                                $userDisplay
                            );
                            $this->table->addRowCell('auto', 'clear-checkbox', $cellValue, 'fdsus-col-clear');
                        }

                        $this->config['spotIndex']++;
                    }
                }
            }

            // Remaining empty spots
            for ($i = $this->config['spotIndex']; $i <= $this->config['taskRowCount']; $i++) {
                $this->config['spotIndex'] = $i;

                // Add a row (if a cell will be added later)
                if ($i === 1 || $this->config['showEmptySpotSignupLink']) {
                    $class['task_first_row'] = $this->config['spotIndex'] === 1 ? 'fdsus-task-first-row' : null;
                    $class['last_spot'] = ($i == $task->dlssus_qty) ? 'fdsus-task-last-row' : null;

                    $rowClass = 'dls-sus-row dls-sus-empty ' . implode(' ', $class) . ' dls-sus-spot-' . $i;
                    $this->config['lastRowKey'] = $this->table->addRow(0, $task->ID, $rowClass);
                }

                /**
                 * Filters task table model body empty fields at the start
                 *
                 * @param TaskTableModel $table
                 * @param SheetModel     $sheet
                 * @param TaskModel      $task
                 * @param array          $config
                 *
                 * @return TaskTableModel
                 * @since 2.2
                 */
                $this->table = apply_filters(
                    'fdsus_tasktable-table-body_data_empty_start',
                    $this->table, $this->sheet, $task, $this->config
                );

                if ($i === 1) {
                    // Task title (if all spots are empty)
                    $this->table->addRowCell('auto', 'task_title', wp_kses_post($task->post_title),
                        '', 1, $this->config['taskRowCount'], 'th'
                    );
                }

                /**
                 * Filters task table model body empty fields after task title
                 *
                 * @param TaskTableModel $table
                 * @param SheetModel     $sheet
                 * @param TaskModel      $task
                 * @param array          $config
                 *
                 * @return TaskTableModel
                 * @since 2.2
                 */
                $this->table = apply_filters(
                    'fdsus_tasktable-table-body_data_empty_after_task_title', $this->table, $this->sheet, $task,
                    $this->config
                );

                if ($this->config['showEmptySpotSignupLink']) {
                    // Set signup link
                    if ($this->config['showSignupLink'] && !$this->sheet->isExpired() && !$task->isExpired()) {
                        $signupLink = $task->getSignupLink();
                    } else {
                        $signupLink = esc_html__('(empty)', 'fdsus');
                        if (is_admin()) {
                            $signupLink .= '
                                <a href="' . Settings::getAdminEditSignupPageUrl($task->ID, $_GET['sheet_id'], 'add') . '">
                                    <span class="sr-only">' . __('Add Sign-up', 'fdsus') . '</span>
                                    <i class="dashicons dashicons-plus" aria-hidden="true"></i>
                                </a>';
                        }
                    }
                    if ($this->sheet->isExpired() || $task->isExpired()) {
                        $signupLink .= esc_html__(' - sign-ups closed', 'fdsus');
                    }

                    /**
                     * Filters sign-up link
                     *
                     * @param string     $signupLink
                     * @param SheetModel $sheet
                     * @param TaskModel  $task
                     * @param int        $spotIndex
                     *
                     * @return string
                     * @since 2.2
                     */
                    $signupLink = apply_filters('fdsus_signup_link', $signupLink, $this->sheet, $task, $i);

                    // Spot Number and Name
                    $cellValue = '<span class="dls-sus-spot-num">#' . $i . ':</span>' . ' ' . $signupLink;
                    $this->table->addRowCell('auto', 'name', $cellValue, '', $this->config['emptySignupColspan']);
                }
            }

        }
        reset($this->tasks);
    }

    /**
     * Get display code for tasks table (back and front-end)
     */
    public function output()
    {
        if (empty($this->tasks)) : ?>
            <p><?php esc_html_e('No tasks were found.', 'fdsus'); ?></p>
            <?php
            return;
        endif;

        $this->setVariables();

        // Header
        $this->buildHeaderData();
        $header = $this->buildHeader();

        // Body
        $this->buildBodyData();
        $body = $this->buildBody();

        // Form URL
        $formUrl = add_query_arg(
            array('task_id' => $this->tasks[key($this->tasks)]->ID),
            remove_query_arg(array('action', 'status', 'tasks', 'signups', 'notice'))
        );

        // Build Table
        ?>
        <form action="<?php echo $formUrl; ?>" method="post" id="sus_form" class="fdsus-form">
            <table class="dls-sus-tasks <?php echo is_admin() ? 'wp-list-table widefat' : null; ?>">
                <thead><?php echo $header; ?></thead>
                <tfoot><?php echo $header; ?></tfoot>
                <tbody><?php echo $body; ?></tbody>
            </table>
            <?php
            /**
             * Action that runs after the Task Table <table> is output
             *
             * @param SheetModel $sheet
             *
             * @since 2.2
             */
            do_action('fdsus_tasktable_after_table', $this->sheet);

            wp_nonce_field('clear-multiple-signups', 'manage_signup_nonce', true, false);
            ?>
        </form>
        <?php
    }

}
