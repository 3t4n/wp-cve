<?php
if( !class_exists( 'WP_List_Table' ) ) {
    // require_once ABSPATH . 'wp-admin/includes/template.php';
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

// Connection List Table class.
class Advanced_Form_Integration_List_Table extends WP_List_Table {

    /**
     * Construct function
     * Set default settings.
     */
    function __construct() {
        global $status, $page;
        //Set parent defaults
        parent::__construct( array(
            'ajax'     => FALSE,
            'singular' => 'integration',
            'plural'   => 'integrations',
        ) );
    }

    /**
     * Renders the columns.
     *
     * @since 1.0.0
     */
    function column_default( $item, $column_name ) {

        switch ( $column_name ) {
            case 'id':
                $value = $item['id'];
                break;
            case 'title':
                $value = $item['title'];
                break;
            case 'form_provider':
                $value = $item['form_provider'];
                break;
            case 'form_name':
                $value = $item['form_name'];
                break;
            case 'action_provider':
                $value = $item['action_provider'];
                break;
            case 'task':
                $value = $item['task'];
                break;
            case 'action':
                $value = $item['action'];
                break;
            default:
                $value = '';
        }

        return apply_filters( 'adfoin_integration_table_column_value', $value, $item, $column_name );
    }

    /**
     * Retrieve the table columns.
     *
     * @since 1.0.0
     * @return array $columns Array of all the list table columns.
     */
    function get_columns() {
        $columns = array(
            'cb'              => '<input type="checkbox" />',
            'title'           => esc_html__( 'Title', 'advanced-form-integration' ),
            'form_provider'   => esc_html__( 'Form Provider', 'advanced-form-integration' ),
            'form_name'       => esc_html__( 'Form', 'advanced-form-integration' ),
            'action_provider' => esc_html__( 'Action', 'advanced-form-integration' ),
            'task'            => esc_html__( 'Task', 'advanced-form-integration' ),
            'status'          => esc_html__( 'Active', 'advanced-form-integration' )
        );

        return apply_filters( 'adfoin_integration_table_columns', $columns );
    }

    /**
     * Render the checkbox column.
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function column_cb( $item ) {
        return '<input type="checkbox" name="id[]" value="' . absint( $item['id'] ) . '" />';
    }

    public function column_form_provider( $item ) {
        $form_providers = adfoin_get_form_providers();

        if( array_key_exists( $item['form_provider'], $form_providers ) ) {
            return $form_providers[$item['form_provider']];
        } else {
            return __( 'Deactivated?', 'advanced-form-integration');
        }

    }

    /**
     * Render the form name column with action links.
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function column_title( $item ) {

        $name = ! empty( $item['title'] ) ? $item['title'] : $item['form_provider'];
        $name = sprintf( '<span><strong>%s</strong></span>', esc_html__( $name ) );

        // Build all of the row action links.
        $row_actions = array();

        // Edit.
        $row_actions['edit'] = sprintf(
            '<a href="%s" title="%s">%s</a>',
            add_query_arg(
                array(
                    'action' => 'edit',
                    'id'     => $item['id'],
                ),
                admin_url( 'admin.php?page=advanced-form-integration' )
            ),
            esc_html__( 'Edit This Integration', 'advanced-form-integration' ),
            esc_html__( 'Edit', 'advanced-form-integration' )
        );

        // Duplicate.
        $row_actions['duplicate'] = sprintf(
            '<a href="%s" title="%s">%s</a>',
            add_query_arg(
                array(
                    'action' => 'duplicate',
                    'id'     => $item['id'],
                ),
                admin_url( 'admin.php?page=advanced-form-integration' )
            ),
            esc_html__( 'Duplicate This Integration', 'advanced-form-integration' ),
            esc_html__( 'Duplicate', 'advanced-form-integration' )
        );

        // Delete.
        $row_actions['delete'] = sprintf(
            '<a href="%s" class="adfoin-integration-delete" title="%s">%s</a>',
            wp_nonce_url(
                add_query_arg(
                    array(
                        'action'  => 'delete',
                        'id' => $item['id'],
                    ),
                    admin_url( 'admin.php?page=advanced-form-integration' )
                ),
                'adfoin_delete_integration_nonce'
            ),
            esc_html__( 'Delete this integration', 'advanced-form-integration' ),
            esc_html__( 'Delete', 'advanced-form-integration' )
        );

        // Build the row action links and return the value.
        return $name . $this->row_actions( apply_filters( 'adfoin_integration_row_actions', $row_actions, $item ) );
    }

    /*
     * Renders action provider column
     */
    public function column_action_provider( $item ) {
        $actions = adfoin_get_action_porviders();
        $action  = isset( $actions[$item['action_provider']] ) ? $actions[$item['action_provider']] : '';

        return $action;
    }

    /*
 * Renders task column
 */
    public function column_task( $item ) {
        $tasks = adfoin_get_action_tasks( $item["action_provider"] );
        $task  = isset( $tasks[$item['task']] ) ? $tasks[$item['task']] : '';

        return $task;
    }

    /**
     * Define bulk actions available for our table listing.
     *
     * @since 1.0.0
     *
     * @return array
     */
    public function get_bulk_actions() {

        $actions = array(
            'delete' => esc_html__( 'Delete', 'advanced-form-integration' ),
        );

        return $actions;
    }

    /**
     * Process the bulk actions.
     *
     * @since 1.0.0
     */
    public function process_bulk_actions() {

        $ids = isset( $_REQUEST['id'] ) ? $_REQUEST['id'] : array();

        if ( ! is_array( $ids ) ) {
            $ids = array( $ids );
        }

        $ids    = array_map( 'absint', $ids );
        $action = ! empty( $_REQUEST['action'] ) ? $_REQUEST['action'] : false;

        if ( empty( $ids ) || empty( $action ) ) {
            return;
        }

        // Delete one or multiple relations - both delete links and bulk actions.
        if ( 'delete' === $this->current_action() ) {

            if (
                wp_verify_nonce( $_REQUEST['_wpnonce'], 'bulk-integrations' ) ||
                wp_verify_nonce( $_REQUEST['_wpnonce'], 'adfoin_delete_integration_nonce' )
            ) {

                foreach ( $ids as $id ) {
                    $this->delete( $id );
                }

                advanced_form_integration_redirect( admin_url( 'admin.php?page=advanced-form-integration' ) );

                exit;
            }
        }
    }

    /**
     * Message to be displayed when there are no relations.
     *
     * @since 1.0.0
     */
    public function no_items() {
        printf(
            wp_kses(
                __( 'Whoops, you haven\'t created an integration yet. Want to <a href="%s">give it a go</a>?', 'advanced-form-integration' ),
                array(
                    'a' => array(
                        'href' => array(),
                    ),
                )
            ),
            admin_url( 'admin.php?page=advanced-form-integration&action=new' )
        );
    }

    /**
     * Sortable settings.
     */
    function get_sortable_columns() {
        return array(
            'title'           => array('title', TRUE),
            'form_provider'   => array('form_provider', TRUE),
            'action_provider' => array('action_provider', TRUE)
        );
    }

    // public function fetch_table_data( $args = array() ) {
    //     global $wpdb;

    //     $defaults = array(
    //         'number'    => 20,
    //         'offset'    => 0,
    //         'orderby'   => 'id',
    //         'order'     => 'DESC'
    //     );

    //     $args  = wp_parse_args( $args, $defaults );

    //     $log_table     = $wpdb->prefix . 'adfoin_integration';
    //     $sql = "SELECT * FROM {$log_table}";

    //     if ( ! empty( $args['orderby'] ) ) {
    //         $sql .= ' ORDER BY ' . esc_sql( $args['orderby'] );
    //         $sql .= ! empty( $args['order'] ) ? ' ' . esc_sql( $args['order'] ) : ' ASC';
    //     }

    //     $sql .= " LIMIT {$args['number']}";

    //     $sql .= ' OFFSET ' . $args['offset'];

    //     $result = $wpdb->get_results( $sql, 'ARRAY_A' );

    //     return $result;
    // }

    public function fetch_table_data( $args = array() ) {
        global $wpdb;
    
        $defaults = array(
            'number'    => 20,
            'offset'    => 0,
            'orderby'   => 'id',
            'order'     => 'DESC'
        );
    
        $args  = wp_parse_args( $args, $defaults );
    
        $log_table = $wpdb->prefix . 'adfoin_integration';
    
        $sql = $wpdb->prepare(
            "SELECT * FROM {$log_table} ORDER BY %s %s LIMIT %d OFFSET %d",
            $args['orderby'],
            $args['order'],
            $args['number'],
            $args['offset']
        );
    
        $result = $wpdb->get_results( $sql, 'ARRAY_A' );
    
        return $result;
    }
    

    //Query, filter data, handle sorting, pagination, and any other data-manipulation required prior to rendering
    public function prepare_items() {
        // Process bulk actions if found.
        $this->process_bulk_actions();


        $count                 = $this->count();
        $columns               = $this->get_columns();
        $hidden                = array();
        $sortable              = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->admin_header();

        $per_page              = 20;
        $current_page          = $this->get_pagenum();
        $offset                = ( $current_page -1 ) * $per_page;

        $args = array(
            'offset' => $offset,
            'number' => $per_page,
        );

        if ( isset( $_REQUEST['orderby'] ) && !empty( $_REQUEST['orderby'] ) ) {
            $args['orderby'] = sanitize_text_field( wp_unslash( $_REQUEST['orderby'] ) );
        }

        if ( isset( $_REQUEST['order'] ) && !empty( $_REQUEST['order'] ) ) {
            $args['order'] = sanitize_text_field( wp_unslash( $_REQUEST['order'] ) );
        }

        $this->items = $this->fetch_table_data( $args );


        $this->set_pagination_args(
            array(
                'total_items' => $count,
                'per_page'    => $per_page,
                'total_pages' => ceil( $count / $per_page ),
            )
        );
    }

    /*
     * Renders status column
     */
    public function column_status($item) {

        $status = $item['status'];
        $id     = $item['id'];

        return '<label class="adfoin-toggle-form form-enabled"><input type="checkbox" data-id="' . absint( $id ) . '" value="1" ' . checked( 1, $status, false ) . '/><span class="slider round"></span></label>';
    }

    /*
     * Handles delete
     */
    public function delete( $id='' ) {
        global $wpdb;
        $relation_table = $wpdb->prefix.'adfoin_integration';
        $action_status  = $wpdb->delete( $relation_table, array( 'id' => $id ) );

        return $action_status;
    }

    /*
     * Handles connection count
     */
    public function count() {
        global $wpdb;

        $relation_table = $wpdb->prefix.'adfoin_integration';
        $count          =  $wpdb->get_var("SELECT COUNT(*) FROM " . $relation_table );

        return $count;
    }

    /*
     * Handles column width
     */
    public function admin_header() {
        $page = ( isset($_GET['page'] ) ) ? esc_attr( $_GET['page'] ) : false;
        if( 'advanced-form-integration' != $page )
            return;

        echo '<style type="text/css">';
        echo '.wp-list-table .column-id { width: 10%; }';
        echo '.wp-list-table .column-title { width: 16%; }';
        echo '.wp-list-table .column-form_provider { width: 16%; }';
        echo '.wp-list-table .column-form_name { width: 16%; }';
        echo '.wp-list-table .column-action_provider { width: 16%; }';
        echo '.wp-list-table .column-action_name { width: 16%; }';
        echo '.wp-list-table .column-status { width: 10%; }';
        echo '</style>';
    }
}