<?php
namespace WPHR\HR_MANAGER\HRM;

/**
 * List table class
 */
class HR_Manager_List_Table extends \WP_List_Table {

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
        _e( 'No HR managers found.', 'wphr' );
    }

    /**
     * Default column values if no callback found
     *
     * @param  object  $item
     * @param  string  $column_name
     *
     * @return string
     */
    function column_default( $user, $column_name ) {
		global $serial_no;
		$employee = new \WPHR\HR_MANAGER\HRM\Employee( $user->ID );
        switch ( $column_name ) {
			case 'no':
				return $serial_no++;
            case 'name':
                return $employee->get_full_name();

            case 'designation':
				$designation = $employee->get_job_title();
                return $designation ? $designation : '-';

            case 'department':
				$department = $employee->get_department_title();
                return $department ? $department : '-';

            case 'type':
				$type = $employee->get_type();
                return $type ? $type : '-';

            case 'date_of_hire':
				$date_of_hire = $employee->get_joined_date();
                return $date_of_hire ? $date_of_hire : '-';

            default:
                return isset( $employee->$column_name ) ? $employee->$column_name : '';
        }
    }

    /**
     * Render the employee name column
     *
     * @param  object  $item
     *
     * @return string
     */
    function column_name( $user ) {
		$employee = new \WPHR\HR_MANAGER\HRM\Employee( $user->ID );
        $actions     = array();
        $delete_url  = '';
        $data_hard   = ( isset( $_REQUEST['status'] ) && sanitize_text_field( $_REQUEST['status'] == 'trash' ) ) ? 1 : 0;
        $delete_text = ( isset( $_REQUEST['status'] ) && sanitize_text_field( $_REQUEST['status'] == 'trash' ) ) ? __( 'Permanent Delete', 'wphr' ) : __( 'Delete', 'wphr' );

        if ( current_user_can( 'wphr_edit_employee', $employee->id ) ) {
            $actions['edit']   =  sprintf( '<a href="%s" data-id="%d"  title="%s">%s</a>', $delete_url, $employee->id, __( 'Edit this item', 'wphr' ), __( 'Edit', 'wphr' ) );
        }

        if ( current_user_can( 'wphr_delete_employee' ) ) {
            $actions['delete'] = sprintf( '<a href="%s" class="submitdelete" data-id="%d" data-hard=%d title="%s">%s</a>', $delete_url, $employee->id, $data_hard, __( 'Delete this item', 'wphr' ), $delete_text );
        }

        if ( $data_hard ) {
            $actions['restore'] = sprintf( '<a href="%s" class="submitrestore" data-id="%d" title="%s">%s</a>', $delete_url, $employee->id,  __( 'Restore this item', 'wphr' ), __( 'Restore', 'wphr' ) );
        }
		$name = $employee->get_full_name();
		$name = $name ? $name : '-';
        return sprintf( '%3$s <a href="%2$s"><strong>%1$s</strong></a>', $name, wphr_hr_url_single_employee( $employee->id ), $employee->get_avatar() );
    }
	
	function column_received_mail( $user ){
		$is_receive_mail_for_leaves = get_user_meta( $user->ID, 'receive_mail_for_leaves', true );
		
		$checked = 'checked';
		
		if( $is_receive_mail_for_leaves != '' && $is_receive_mail_for_leaves == 0 ){
			$checked = '';
		}
		
		return sprintf( '<input type="checkbox" %s name="receive_mail_for_leaves[%d]" value="1" />',  $checked, $user->ID );
	}
	
	function column_manage_leave( $user ){
		$is_manage_leave_of_employees = get_user_meta( $user->ID, 'manage_leave_of_employees', true );
		
		$checked = 'checked';
		
		if( $is_manage_leave_of_employees != '' && $is_manage_leave_of_employees == 0 ){
			$checked = '';
		}
		return sprintf( '<input type="checkbox" %s name="manage_leave_of_employees[%2$d]" value="1" /><input type="hidden" name="users[]" value="%2$d" />',  $checked, $user->ID );
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
            'name'         => __( 'Employee Name', 'wphr' ),
            'department'   => __( 'Department', 'wphr' ),
            'type'         => __( 'Employment Type', 'wphr' ),
            'date_of_hire' => __( 'Joined', 'wphr' ),
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
        $this->page_status     = isset( $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : 'active';

        // only ncessary because we have sample data
		$search_term = isset( $_REQUEST['s'] ) ? sanitize_text_field($_REQUEST['s']) : '';
        $args = array(
		    'search'     => '*' . esc_attr( $search_term ) . '*',
            'paged' => $current_page,
            'number' => $per_page,
			'role' => wphr_hr_get_manager_role()
        );

		$users = new \WP_User_Query( $args );
		$user_list = $users->get_results();
		
        $this->counts = $users->total_users;
        $this->items  = $user_list;

        $args['count'] = true;
        $total_items = $users->total_users;


        $this->set_pagination_args( array(
            'total_items' => $total_items,
            'per_page'    => $per_page
        ) );
    }

}
