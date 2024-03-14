<?php
namespace WPHR\HR_MANAGER\HRM;

/**
 * List table class
 */
class Line_Manager_List_Table extends \WP_List_Table {

    private $counts = array();
    private $page_status = '';

    function __construct() {
        global $status, $page, $page_status, $serial_no;

		$serial_no = 1;
        parent::__construct( array(
            'singular' => 'employee',
            'plural'   => 'employees',
            'ajax'     => false
        ) );
    }

    /**
     * Message to show if no employee found
     *
     * @return void
     */
    function no_items() {
        _e( 'No line managers found.', 'wphr' );
    }

    /**
     * Default column values if no callback found
     *
     * @param  object  $item
     * @param  string  $column_name
     *
     * @return string
     */
    function column_default( $employee, $column_name ) {
		global $serial_no;
        switch ( $column_name ) {
			case 'no':
				return $serial_no++;
            default:
                return isset( $employee->$column_name ) ? $employee->$column_name : '';
        }
    }
	
    /**
     * Render the employee line manager column
     *
     * @param  object  $item
     *
     * @return string
     */
    function column_line_manager( $employee ) {
		$line_manager = get_user_by( 'id', $employee->reporting_to );
		return sprintf( '%3$s <a href="%2$s"><strong>%1$s</strong></a>', $line_manager->first_name . ' ' . $line_manager->last_name, wphr_hr_url_single_employee( $employee->reporting_to ), get_avatar($employee->reporting_to, 32) );
	}
	
    /**
     * Render the employee name column
     *
     * @param  object  $item
     *
     * @return string
     */
    function column_name( $employee ) {
        $actions     = array();
        $delete_url  = '';
        $data_hard   = ( isset( $_REQUEST['status'] ) && sanitize_text_field($_REQUEST['status']) == 'trash' ) ? 1 : 0;
        $delete_text = ( isset( $_REQUEST['status'] ) && sanitize_text_field($_REQUEST['status']) == 'trash' ) ? __( 'Permanent Delete', 'wphr' ) : __( 'Delete', 'wphr' );

        if ( current_user_can( 'wphr_edit_employee', $employee->id ) ) {
            $actions['edit']   =  sprintf( '<a href="%s" data-id="%d"  title="%s">%s</a>', $delete_url, $employee->id, __( 'Edit this item', 'wphr' ), __( 'Edit', 'wphr' ) );
        }

        if ( current_user_can( 'wphr_delete_employee' ) ) {
            $actions['delete'] = sprintf( '<a href="%s" class="submitdelete" data-id="%d" data-hard=%d title="%s">%s</a>', $delete_url, $employee->id, $data_hard, __( 'Delete this item', 'wphr' ), $delete_text );
        }

        if ( $data_hard ) {
            $actions['restore'] = sprintf( '<a href="%s" class="submitrestore" data-id="%d" title="%s">%s</a>', $delete_url, $employee->id,  __( 'Restore this item', 'wphr' ), __( 'Restore', 'wphr' ) );
        }

        return sprintf( '<a href="%2$s"><strong>%1$s</strong></a>', $employee->get_full_name(), wphr_hr_url_single_employee( $employee->id ) );
    }
	
	function column_received_mail( $employee ){
		$checked = '';
		if( $employee->send_mail_to_reporter ){
			$checked = 'checked';
		}
		return sprintf( '<input type="checkbox" %s name="receive_mail_for_leaves[%d]" value="1" />',  $checked, $employee->user_id );
	}
	
	function column_manage_leave( $employee ){
		$checked = '';
		if( $employee->manage_leave_by_reporter ){
			$checked = 'checked';
		}
		return sprintf( '<input type="checkbox" %1$s name="manage_leave_of_employees[%2$d]" value="1" /><input type="hidden" name="users[]" value="%2$d" />',  $checked, $employee->user_id );
	}

    public function current_action() {

        if ( isset( $_REQUEST['filter_employee'] ) ) {
            return 'filter_employee';
        }

        if ( isset( $_REQUEST['employee_search'] ) ) {
            return 'employee_search';
        }

        return parent::current_action();
    }

    /**
     * Get the column names
     *
     * @return array
     */
    function get_columns() {
        $columns = array(
            'no'           => __( 'No.', 'wphr' ),
            'line_manager' => __( 'Line Manager', 'wphr' ),
            'name'         => __( 'Employee Name', 'wphr' ),
            'received_mail'=> __( 'Receive Leave Notifications', 'wphr' ),
            'manage_leave' => __( 'Manage Leave', 'wphr' ),
        );

        return apply_filters( 'wphr_hr_manager_table_cols', $columns );
    }


    /**
     * Search form for employee table
     *
     * @since 0.1
     *
     * @param  string $text
     * @param  string $input_id
     *
     * @return void
     */
    public function search_box( $text, $input_id ) {
        if ( empty( $_REQUEST['s'] ) && !$this->has_items() )
            return;

        $input_id = $input_id . '-search-input';

        ?>
        <p class="search-box">
            <label class="screen-reader-text" for="<?php echo $input_id ?>"><?php echo $text; ?>:</label>
            <input type="search" id="<?php echo $input_id ?>" name="s" value="<?php _admin_search_query(); ?>" />
            <?php submit_button( $text, 'button', 'employee_search', false, array( 'id' => 'search-submit' ) ); ?>
        </p>
        <?php
    }

    /**
     * Prepare the class items
     *
     * @return void
     */
    function prepare_items() {
        $columns               = $this->get_columns();
        $hidden                = array();
        $sortable              = $this->get_sortable_columns();
        $this->_column_headers = array( $columns, $hidden, $sortable );

        $per_page              = 20;
        $current_page          = $this->get_pagenum();
        $offset                = ( $current_page -1 ) * $per_page;
        $this->page_status     = isset( $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : 'active';

        // only ncessary because we have sample data
        $args = array(
            'offset' => $offset,
            'number' => $per_page,
			'reporting_to' => 1
        );

        if ( isset( $_REQUEST['s'] ) && !empty( $_REQUEST['s'] ) ) {
            $args['s'] = sanitize_text_field($_REQUEST['s']);
        }
		

        $this->counts = wphr_hr_employee_get_status_count();
        $this->items  = wphr_hr_get_employees( $args );

        $args['count'] = true;
        $total_items = wphr_hr_get_employees( $args );


        $this->set_pagination_args( array(
            'total_items' => $total_items,
            'per_page'    => $per_page
        ) );
    }

}
