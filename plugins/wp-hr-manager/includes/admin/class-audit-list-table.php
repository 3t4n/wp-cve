<?php
namespace WPHR\HR_MANAGER\Admin;

/**
 * List table class
 */
class Auditlog_List_Table extends \WP_List_Table {

    function __construct() {
        global $status, $page;

        parent::__construct( array(
            'singular' => 'audit_log',
            'plural'   => 'audit_logs',
            'ajax'     => false
        ) );

        $this->table_css();
    }

    /**
     * Table column width css
     *
     * @return void
     */
    function table_css() {
        echo '<style type="text/css">';
        echo '.audit-log-list-table .column-name { width: 10%; }';
        echo '.audit-log-list-table .column-sections { width: 12%; }';
        echo '.audit-log-list-table .column-message { width: 45%; }';
        echo '</style>';
    }

    function get_table_classes() {
        return array( 'widefat', 'fixed', 'striped', 'audit-log-list-table', $this->_args['plural'] );
    }

    /**
     * Render extra filtering option in
     * top of the table
     *
     * @since 0.1
     *
     * @param  string $which
     *
     * @return void
     */
    function extra_tablenav( $which ) {
        if ( $which != 'top' ) {
            return;
        }

        $modules          = wphr_get_audit_log_modules();
        $sections         = wphr_get_audit_log_sub_component();
        $selected_module  = ( isset( $_GET['filter_module'] ) ) ? sanitize_text_field($_GET['filter_module']) : '';
        $selected_section = ( isset( $_GET['filter_section'] ) ) ? sanitize_text_field($_GET['filter_section']) : '';

        $selected_duration = ( isset( $_GET['filter_duration'] ) ) ? sanitize_text_field($_GET['filter_duration']) : '';

        ?>
        <div class="alignleft actions">

            <label class="screen-reader-text" for="filter_module"><?php _e( 'Filter by Module', 'wphr' ) ?></label>
            <select name="filter_module" id="filter_module">
                <option value=""><?php _e( '&mdash; All Modules &mdash;', 'wphr' ); ?></option>
                <?php foreach ( $modules as $key => $module ): ?>
                    <option value="<?php echo $module['component'] ?>" <?php selected( $selected_module, $module['component'] ); ?>><?php echo $module['component']; ?></option>
                <?php endforeach ?>
            </select>

            <label class="screen-reader-text" for="filter_section"><?php _e( 'Filter by Section', 'wphr' ) ?></label>
            <select name="filter_section" id="filter_section">
                <option value=""><?php _e( '&mdash; All Sections &mdash;', 'wphr' ); ?></option>
                <?php foreach ( $sections as $key => $section ): ?>
                    <option value="<?php echo $section['sub_component'] ?>" <?php selected( $section['sub_component'], $selected_section ); ?>><?php echo ucfirst( $section['sub_component'] ); ?></option>
                <?php endforeach ?>
            </select>
            <label class="screen-reader-text" for="new_role"><?php _e( 'Filter by Duration', 'wphr' ) ?></label>
            <select name="filter_duration" id="filter_duration">
                <option value="-1"><?php _e( '&mdash; All Times &mdash;', 'wphr' ) ?></option>
                <?php
                $types = $this->wphr_log_get_filters();

                foreach ( $types as $key => $title ) {
                    echo sprintf( "<option value='%s'%s>%s</option>\n", $key, selected( $selected_duration, $key, false ), $title );
                }
                ?>
            </select>

            <?php
            submit_button( __( 'Filter', 'wphr' ), 'button', 'filter_audit_log', false );
        echo '</div>';
    }

    /**
     * Get Lists of months for attendance filter
     *
     * @since 1.0
     *
     * @return array
     */
    function wphr_log_get_filters() {

        $filters = array(
            'today'        => __( 'Today',        'wphr' ),
            'yesterday'    => __( 'Yesterday',    'wphr' ),
            'this_month'   => __( 'This Month',   'wphr' ),
            'last_month'   => __( 'Last Month',   'wphr' ),
            'this_quarter' => __( 'This Quarter', 'wphr' ),
            'last_quarter' => __( 'Last Quarter',  'wphr' ),
            'this_year'    => __( 'This Year',    'wphr' ),
            'last_year'    => __( 'Last Year',    'wphr' ),
            'custom'    => __( 'Custom',    'wphr' )
        );

        return $filters;
    }

    /**
     * Render current actions
     * @return [type] [description]
     */
    public function current_action() {

        if ( isset( $_REQUEST['filter_audit_log'] ) ) {
            return 'filter_audit_log';
        }

        return parent::current_action();
    }


    /**
     * Message to show if no logs found
     *
     * @return void
     */
    function no_items() {
        _e( 'No logs found.', 'wphr' );
    }

    /**
     * Default column values if no callback found
     *
     * @param  object  $item
     * @param  string  $column_name
     *
     * @return string
     */
    function column_default( $audit_log, $column_name ) {

        switch ( $column_name ) {
            case 'name':
                return ucfirst( $audit_log->component );

            case 'sections':
                return ucfirst( $audit_log->sub_component );

            case 'message':
                if ( $audit_log->changetype == 'edit' ) {
                    if ( !empty( $audit_log->old_value ) && !empty( $audit_log->new_value ) ) {
                        return sprintf( __( '%s. <a href="#" class="wphr-audit-log-view-changes wphr-tips" data-id="%d" title="%s"> (view changes)</a>', 'wphr' ), htmlspecialchars_decode( $audit_log->message ), $audit_log->id, __( 'View what elements are changes', 'wphr' ) );
                    } else {
                        return htmlspecialchars_decode( $audit_log->message );
                    }
                }

                return htmlspecialchars_decode( $audit_log->message );

            case 'created_by':
                return $audit_log->display_name;

            case 'created_at':
                return wphr_format_date( $audit_log->created_at );

            default:
                return isset( $audit_log->$column_name ) ? $audit_log->$column_name : '';
        }
    }

    /**
     * Get the column names
     *
     * @return array
     */
    function get_columns() {
        $columns = array(
            'cb'         => '',
            'name'       => __( 'Module', 'wphr' ),
            'sections'   => __( 'Sections', 'wphr' ),
            'message'    => __( 'Message', 'wphr' ),
            'created_by' => __( 'Created By', 'wphr' ),
            'created_at' => __( 'Created At', 'wphr' ),
        );

        return apply_filters( 'wphr_hr_audit_table_cols', $columns );
    }


    /**
     * Render the checkbox column
     *
     * @param  object  $item
     *
     * @return string
     */
    function column_cb( $item ) {
        return false;
    }

    /**
     * Prepare the class items
     *
     * @return void
     */
    function prepare_items() {

        $columns               = $this->get_columns();
        $hidden                = array( );
        $sortable              = $this->get_sortable_columns();
        $this->_column_headers = array( $columns, $hidden, $sortable );

        $per_page              = 30;
        $current_page          = $this->get_pagenum();
        $offset                = ( $current_page -1 ) * $per_page;
        $this->page_status     = isset( $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : '2';
        $args                  = [];


        // only ncessary because we have sample data
        $args = array(
            'offset' => $offset,
            'number' => $per_page,
        );

        if ( isset( $_REQUEST['filter_module'] ) && !empty( $_REQUEST['filter_module'] ) ) {
            $args['component'] = sanitize_text_field($_REQUEST['filter_module']);
        }

        if ( isset( $_REQUEST['filter_section'] ) && !empty( $_REQUEST['filter_section'] ) ) {
            $args['sub_component'] = sanitize_text_field($_REQUEST['filter_section']);
        }

        if ( isset( $_REQUEST['filter_duration'] ) && !empty( $_REQUEST['filter_duration'] ) ) {
            $args['filter_duration'] = sanitize_text_field($_REQUEST['filter_duration']);


            if ( '-1' != $args['filter_duration'] ) {

                if ( $args['filter_duration'] == 'custom' ) {
                    $args['start'] = isset( $_REQUEST['start'] ) ? sanitize_text_field($_REQUEST['start']) : '';
                    $args['end']   = isset( $_REQUEST['end'] ) ? sanitize_text_field($_REQUEST['end']) : '';
                } else {
                    $duration      = $this->wphr_log_get_start_end_date( $args['filter_duration'] );
                    $args['start'] = $duration['start'];
                    $args['end']   = $duration['end'];
                }
            }

        }

        $this->items  = wphr_log()->get( $args );
        $total_items  = wphr_log()->get( $args, true );

        $this->set_pagination_args( array(
            'total_items' => $total_items,
            'per_page'    => $per_page
        ) );
    }


    /**
     * set timespan in wich the data should be fetched
     */
    function wphr_log_get_start_end_date( $time = '' ) {

        $duration = [];

        if ( $time ) {

            switch ( $time ) {

                case 'today':

                    $start_date = current_time( "Y-m-d" );
                    $end_date   = $start_date;
                    break;

                case 'yesterday':

                    $today      = strtotime( current_time( "Y-m-d" ) );
                    $start_date = date( "Y-m-d", strtotime( "-1 days", $today ) );
                    $end_date   = $start_date;
                    break;

                case 'last_7_days':

                    $end_date   = current_time( "Y-m-d" );
                    $start_date = date( "Y-m-d", strtotime( "-6 days", strtotime( $end_date ) ) );
                    break;

                case 'this_month':

                    $start_date = date( "Y-m-d", strtotime( "first day of this month" ) );
                    $end_date   = date( "Y-m-d", strtotime( "last day of this month" ) );
                    break;

                case 'last_month':

                    $start_date = date( "Y-m-d", strtotime( "first day of previous month" ) );
                    $end_date   = date( "Y-m-d", strtotime( "last day of previous month" ) );
                    break;

                case 'this_quarter':

                    $current_month = date( 'm' );
                    $current_year  = date( 'Y' );

                    if ( $current_month >= 1 && $current_month <= 3 ){

                        $start_date = date( 'Y-m-d', strtotime( '1-January-'.$current_year ) );
                        $end_date   = date( 'Y-m-d', strtotime( '31-March-'.$current_year ) );

                    } else  if ( $current_month >= 4 && $current_month <= 6 ){

                        $start_date = date( 'Y-m-d', strtotime( '1-April-'.$current_year ) );
                        $end_date   = date( 'Y-m-d', strtotime( '30-June-'.$current_year ) );

                    } else  if ( $current_month >= 7 && $current_month <= 9){

                        $start_date = date( 'Y-m-d', strtotime( '1-July-'.$current_year ) );
                        $end_date   = date( 'Y-m-d', strtotime( '30-September-'.$current_year ) );

                    } else  if ( $current_month >= 10 && $current_month <= 12 ){

                        $start_date = date( 'Y-m-d', strtotime( '1-October-'.$current_year ) );
                        $end_date   = date( 'Y-m-d', strtotime( '31-December-'.$current_year ) );
                    }
                    break;

                case 'last_quarter':

                    $current_month = date( 'm' );
                    $current_year  = date( 'Y' );

                    if ( $current_month >= 1 && $current_month <= 3 ) {

                        $start_date = date( 'Y-m-d', strtotime( '1-October-'.( $current_year-1 ) ) );
                        $end_date   = date( 'Y-m-d', strtotime( '31-December-'.( $current_year-1 ) ) );

                    } else if( $current_month >=4 && $current_month <= 6){

                        $start_date = date( 'Y-m-d', strtotime( '1-January-'.$current_year ) );
                        $end_date   = date( 'Y-m-d', strtotime( '31-March-'.$current_year ) );

                    } else if( $current_month >= 7 && $current_month <= 9){

                        $start_date = date( 'Y-m-d', strtotime( '1-April-'.$current_year ) );
                        $end_date   = date( 'Y-m-d', strtotime( '30-June-'.$current_year ) );

                    } else if( $current_month >= 10 && $current_month <= 12 ){

                        $start_date = date( 'Y-m-d', strtotime( '1-July-'.$current_year ) );
                        $end_date   = date( 'Y-m-d', strtotime( '30-September-'.$current_year ) );
                    }
                    break;

                case 'last_year':

                    $start_date = date( "Y-01-01", strtotime( "-1 year" ) );
                    $end_date   = date( "Y-12-31", strtotime( "-1 year" ) );
                    break;

                case 'this_year':

                    $start_date = date( "Y-01-01" );
                    $end_date   = date( "Y-12-31" );
                    break;

                default:
                    break;
            }
        }

        $duration   = [
            'start' => $start_date,
            'end'   => $end_date
        ];

        return $duration;
    }

}
