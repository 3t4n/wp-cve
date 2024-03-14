<?php
namespace WPHR\HR_MANAGER\HRM;

/**
 * List table class
 */
class Entitlement_List_Table extends \WP_List_Table {

    function __construct() {
        global $status, $page;

        parent::__construct(array(
            'singular' => 'entitlement',
            'plural' => 'entitlements',
            'ajax' => false
        ));
    }

    function get_table_classes() {
        return array('widefat', 'fixed', 'striped', 'entitlement-list-table', $this->_args['plural']);
    }

    /**
     * Extra filters for the list table
     *
     * @since 0.1
     * @since 1.2.0 Using financial year or years instead of single year filtering
     *
     * @param string $which
     *
     * @return void
     */
    function extra_tablenav($which) {
        if ($which != 'top') {
            return;
        }

        $entitlement_years = get_entitlement_financial_years();

        if (empty($entitlement_years)) {
            return;
        }

        $years = [];
        $selected = '';

        foreach ($entitlement_years as $year) {
            $years[$year] = $year;
        }

        if (!empty($_GET['financial_year'])) {
            $selected = sanitize_text_field($_GET['financial_year']);
        } else {
            $financial_year = wphr_get_financial_year_dates();

            $start = date('Y', strtotime($financial_year['start']));
            $end = date('Y', strtotime($financial_year['end']));

            if ($start === $end) {
                $selected = $start;
            } else {
                $selected = $start . '-' . $end;
            }
        }
        ?>
        <div class="alignleft actions">
            <select name="financial_year"><?php echo wphr_html_generate_dropdown($years, $selected); ?></select>
            <?php submit_button(__('Filter'), 'button', 'filter_entitlement', false); ?>
        </div>
        <?php
    }

    /**
     * Message to show if no entitlement found
     *
     * @return void
     */
    function no_items() {
        _e('No entitlement found.', 'wphr');
    }

    /**
     * Default column values if no callback found
     *
     * @param  object  $item
     * @param  string  $column_name
     *
     * @return string
     */
    function column_default($entitlement, $column_name) {
        
        $balance = wphr_hr_leave_get_balance($entitlement->user_id);

        $scheduled_balance_string = $available_balance_string = '';
        if (isset($balance[$entitlement->policy_id])) {

            $working_hours = $balance[$entitlement->policy_id]['working_hours'] ? $balance[$entitlement->policy_id]['working_hours'] : 9;
            $total_approved_minutes = $balance[$entitlement->policy_id]['total_approved_minutes'];
            $total_minutes = $balance[$entitlement->policy_id]['total_minutes'];

            $scheduled_arr = wphr_get_balance_details_from_minutes($total_approved_minutes, $working_hours);

            $available_arr = wphr_get_balance_details_from_minutes(( $total_minutes - $total_approved_minutes), $working_hours);

            $scheduled_balance_string = $available_balance_string = '';

            if ($scheduled_arr) {
                $scheduled_balance_string = $scheduled_arr['balance_string'];
            }

            if ($available_arr) {
                $available_balance_string = $available_arr['balance_string'];
            }
        }

        switch ($column_name) {
            case 'name':
                return sprintf('<strong><a href="%s">%s</a></strong>', wphr_hr_url_single_employee($entitlement->user_id), esc_html($entitlement->employee_name));

            case 'leave_policy':
                return esc_html($entitlement->policy_name1);

            case 'valid_from':
                return wphr_format_date($entitlement->from_date);

            case 'valid_to':
                return wphr_format_date($entitlement->to_date);

            case 'days':
                return number_format_i18n($entitlement->days);


            case 'scheduled':
//                return $scheduled ? sprintf(__('%d days', 'wphr'), number_format_i18n($scheduled)) : '-';
                return $scheduled_balance_string ? $scheduled_balance_string : '-';
            case 'available':

                return $available_balance_string ? sprintf('<span class="green">%s</span>', $available_balance_string ) : '-';

            default:
                return isset($entitlement->$column_name) ? $entitlement->$column_name : '';
        }
    }

    /**
     * Get the column names
     *
     * @return array
     */
    function get_columns() {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'name' => __('Employee Name', 'wphr'),
            'leave_policy' => __('Leave Policy', 'wphr'),
            'valid_from' => __('Valid From', 'wphr'),
            'valid_to' => __('Valid To', 'wphr'),
            'days' => __('Leave Entitlement', 'wphr'),
            'scheduled' => __('Scheduled', 'wphr'),
            'available' => __('Available', 'wphr')
        );

        return apply_filters('wphr_hr_entitlement_table_cols', $columns);
    }

    /**
     * Render the designation name column
     *
     * @param  object  $item
     *
     * @return string
     */
    function column_name($entitlement) {

        $actions = array();
        $delete_url = '';

        if (wphr_get_option('wphr_debug_mode', 'wphr_settings_general', 0)) {
            $actions['delete'] = sprintf('<a href="%s" class="submitdelete" data-id="%d" data-user_id="%d" data-policy_id="%d" title="%s">%s</a>', $delete_url, $entitlement->id, $entitlement->user_id, $entitlement->policy_id, __('Delete this item', 'wphr'), __('Delete', 'wphr'));
        }

        return sprintf('<a href="%3$s"><strong>%1$s</strong></a> %2$s', esc_html($entitlement->employee_name), $this->row_actions($actions), wphr_hr_url_single_employee($entitlement->user_id));
    }

    /**
     * Trigger current action
     *
     * @return string
     */
    public function current_action() {

        if (isset($_REQUEST['filter_entitlement'])) {
            return 'filter_entitlement';
        }

        return parent::current_action();
    }

    /**
     * Get sortable columns
     *
     * @return array
     */
    function get_sortable_columns() {
        $sortable_columns = array(
            'name' => array('name', true),
        );

        return $sortable_columns;
    }

    /**
     * Set the bulk actions
     *
     * @return array
     */
    function get_bulk_actions() {
        $actions = array(
            'entitlement_delete' => __('Delete', 'wphr'),
        );
        return $actions;
    }

    /**
     * Render the checkbox column
     *
     * @param  object  $item
     *
     * @return string
     */
    function column_cb($item) {
        return sprintf(
                '<input type="checkbox" name="entitlement_id[]" value="%s" />', $item->id
        );
    }

    /**
     * Prepare the class items
     *
     * @since 0.1
     * @since 1.2.0 Using `wphr_get_financial_year_dates` for financial start and end dates
     *
     * @return void
     */
    function prepare_items() {
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);

        $per_page = 20;
        $current_page = $this->get_pagenum();
        $offset = ( $current_page - 1 ) * $per_page;
        $this->page_status = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : '2';

        $args = [
            'offset' => $offset,
            'number' => $per_page,
        ];

        if (isset($_REQUEST['orderby']) && isset($_REQUEST['order'])) {
            $args['orderby'] = 'u.display_name';
            $args['order'] = sanitize_text_field($_REQUEST['order']);
        }

        // calculate start and end dates
        $financial_year_dates = wphr_get_financial_year_dates();

        $from_date = $financial_year_dates['start'];
        $to_date = $financial_year_dates['end'];

        if (!empty($_GET['financial_year'])) {
            preg_match_all('/^(\d{4})-(\d{4})|(\d{4})$/', sanitize_text_field($_GET['financial_year']), $matches);

            if (!empty($matches[3][0])) {
                $from_date = $matches[3][0] . '-01-01 00:00:00';
                $to_date = $matches[3][0] . '-12-31 23:59:59';
            } else if (!empty($matches[1][0]) && !empty($matches[2][0])) {
                $from_date = $matches[1][0] . '-01-01 00:00:00';
                $to_date = $matches[2][0] . '-12-31 23:59:59';
            }
        }

        $args['from_date'] = $from_date;
        $args['to_date'] = $to_date;

        // get the items
        $this->items = wphr_hr_leave_get_entitlements($args);

        $this->set_pagination_args(array(
            'total_items' => wphr_hr_leave_count_entitlements($args),
            'per_page' => $per_page
        ));
    }

}
