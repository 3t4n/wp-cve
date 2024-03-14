<?php
if ( !class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Class to create the table layout
 *
 * @package WordPress
 * @subpackage List_Table
 * @since 1.0
 */
class LOGICS_Rss_Overview extends WP_List_Table {
    
    /**
     * Set the amount of stores that are shown on each page
     * @since 1.0
     * @var string
     */
    private $_per_page;

    /**
     * Class constructor
     */
    function __construct() {
        
        global $status, $page;

        parent::__construct( array(
            'singular' => __( 'Store', 'logics' ),
            'plural'   => __( 'Stores', 'logics' ),
            'ajax'     => true
        ) );
        
        $this->_per_page = 10;
    }
    
    /**
     * Get the per_page value from the option table
     * 
     * @since 1.2.20
     * @return string $per_page The amount of stores to show per page
     */
    function get_per_page() {
        
        $user     = get_current_user_id();
        $screen   = get_current_screen();
        $option   = $screen->get_option( 'per_page', 'option' );
        $per_page = get_user_meta( $user, $option, true );
        
        if ( empty( $per_page ) || $per_page < 1 ) {
            $per_page = $screen->get_option( 'per_page', 'default' );
        }
        
        return $per_page;
    }
    
    /**
     * The default message that is shown when no store data exists
     * 
     * @since 1.0
     * @return void
     */
    function no_items() {
        _e( 'No stores found', 'logics' );
    }

    /**
     * Set the default values for each column
     * 
     * @since 1.0
     * @param array $item Data for a single column row
     * @param array $column_name The name of the column
     * @return mixed The value for each column
     */
    function column_default( $item, $column_name ) {
        
        switch( $column_name ) { 
            case 'id':
            case 'title':
			case 'pid':
			case 'taxid':
				return $item[$column_name];
            case 'action':
                $rtn = '<a href="' . admin_url( 'admin.php?page=logics_managerss&action=edit_rss&rss_id=' . $item['id'] ) . '">' . __( 'Edit', 'logics' ) . '</a>'; 
				$rtn .= ' | <a href="' . admin_url( 'admin.php?page=logics_managerss&action=runjob&rss_id=' . $item['id'] ) . '">' . __( 'Run', 'logics' ) . '</a>'; 
				return $rtn;
            default:
                return 'id';
        }
    }

    /**
     * Define which columns should be sortable
     * 
     * @since 1.0
     * @return array $sortable_columns The list of sortable scolumns
     */
    function get_sortable_columns() {
        
        $sortable_columns = array(
            'id' => array( 'id', true ), //true = already sorted ( default )
            'title'   => array( 'title', false ),
			'pid'   => array( 'pid', false ),
			'taxid'   => array( 'taxid', false )
        );

        return $sortable_columns;
    }
    
    /**
     * Get the list of columns
     * 
     * @since 1.0
     * @return array $columns The list of columns
     */
    function get_columns() {
        
        $columns = array(
            'cb'      => '<input type="checkbox" />',
            'id' => __( 'ID', 'logics' ),
            'title'   => __( 'Title', 'logics' ),
			'pid'   => __( 'Post Type', 'logics' ),
			'taxid'   => __( 'Taxonomy', 'logics' ),
            'action'  => __( 'Actions', 'logics' )
        );

        return $columns;
    }
    
    /**
     * Set the data for a checkbox column
     * 
     * @since 1.0
     * @param array $item The store data for this table row
     * @return string The html for the checkbox with the correct value set
     */
    function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="store[]" value="%s" />', $item['id']
        );    
    }

    /**
     * Get the list of available bulk actions
     * 
     * @since 1.0
     * @return array $actions The list of bulk actions
     */
    function get_bulk_actions() {
        
        $actions = array(
            'delete'     => __( 'Delete', 'logics' ),
            'activate'   => __( 'Activate', 'logics' ),
            'deactivate' => __( 'Deactivate', 'logics' )
        );

        return $actions;
    }
    
    /**
     * Change the store status into either active or inactive
     * 
     * @since 1.0
     * @param string $store_ids The list of store ids
     * @param string $status Is set to either activate or deactivate
     * @return void
     */
    function update_store_status( $store_ids, $status ) {
        
        global $wpdb;

        if ( $status === 'deactivate' ) {
            $active_status       = 0;
            $success_action_desc = __( 'deactivated', 'logics' );
            $fail_action_desc    = __( 'deactivating', 'logics' );
        } else {
            $active_status       = 1;
            $success_action_desc = __( 'activated', 'logics' );
            $fail_action_desc    = __( 'activating', 'logics' );
        }
        
        $result = $wpdb->query( $wpdb->prepare( "UPDATE $wpdb->rss_settings SET active = %d WHERE id IN ( $store_ids )", $active_status ) );	   
        
        if ( $result === false ) {
            $state = 'error';
            $msg = sprintf( __( 'There was a problem %s the store(s), please try again.', 'logics' ), $fail_action_desc );

        } else {
            $state = 'updated';
            $msg = sprintf( __( 'Store(s) successfully %s.', 'logics' ), $success_action_desc );
        } 
        
        add_settings_error ( 'bulk-state', esc_attr( 'bulk-state' ), $msg, $state );
    }
    
    /**
     * Remove the selected stores from the db
     * 
     * @since 1.0
     * @param string $store_ids The list of store ids that should be deleted.
     * @return void
     */
    function remove_stores( $store_ids ) {

        global $wpdb;

        $result = $wpdb->query( "DELETE FROM $wpdb->rss_settings WHERE id IN ( $store_ids )" );
        
        if ( $result === false ) {
            $state = 'error';
            $msg   = __( 'There was a problem removing the store(s), please try again.', 'logics' );
        } else {
            $state = 'updated';
            $msg   = __( 'Rss Listing(s) successfully removed.' , 'logics' );
        } 
        
        add_settings_error ( 'bulk-remove', esc_attr( 'bulk-remove' ), $msg, $state );
    }
    
    /**
     * Process the bulk actions
     * 
     * @since 1.0
     * @return void
     */
    function process_bulk_action() {
        
        if ( !current_user_can( apply_filters( 'logics_capability', 'manage_options' ) ) )
            die( '-1' );
        
        if ( isset( $_POST['_wpnonce'] ) && ! empty( $_POST['_wpnonce'] ) ) {
            $nonce  = filter_input( INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING );
            $action = 'bulk-' . $this->_args['plural'];

            if ( !wp_verify_nonce( $nonce, $action ) )
                wp_die( 'Nope! Security check failed!' );
            
            $action = $this->current_action();

            /* If an action is set continue, otherwise reload the page */
            if ( !empty( $action ) ) {
                $id_list = array();

                foreach ( $_POST['store'] as $store_id ) {
                    $id_list[] = $store_id;
                }

                /* Before checking which type of bulk action to run, we make sure we actually have some ids to process */
                if ( !empty( $id_list ) ) {
                    $store_ids = esc_sql( implode( ',', wp_parse_id_list( $id_list ) ) );

                    switch ( $action ) {
                        case 'activate':
                            $this->update_store_status( $store_ids, 'activate' );
                            break;
                        case 'deactivate':
                            $this->update_store_status( $store_ids, 'deactivate' );
                            break;                 
                         case 'delete':
                            $this->remove_stores( $store_ids );
                            break;                
                    }   
                }
            }
        }
    }
    
    /**
     * Get the required store data for the table list
     * 
     * @since 1.0
     * @return array $response The required store data
     */
    function get_store_list() {
        
        global $wpdb;
        
        $total_items = 0;
                
        /* Check if we need to run the search query or just show all the data */
        if ( isset( $_POST['s'] ) && ( !empty( $_POST['s'] ) ) ) {
            $search = trim( $_POST['s'] );
            $result = $wpdb->get_results( 
                            $wpdb->prepare( "SELECT id, title, pid, taxid, isrun  
                                             FROM $wpdb->rss_settings
                                             WHERE title LIKE %s", 
                                             '%' . like_escape( $search ). '%'
                                          ), ARRAY_A 
                            );
        } else {
            /* Order params */
            $orderby   = !empty ( $_GET["orderby"] ) ? mysql_real_escape_string ( $_GET["orderby"] ) : 'title';
            $order     = !empty ( $_GET["order"] ) ? mysql_real_escape_string ( $_GET["order"] ) : 'ASC';
            $order_sql = $orderby.' '.$order; 

            /* Pagination parameters */
            $total_items = $wpdb->get_var( "SELECT COUNT(*) AS count FROM $wpdb->rss_settings" );
            $paged       = !empty ( $_GET["paged"] ) ? mysql_real_escape_string ( $_GET["paged"] ) : '';
            
            if ( empty( $paged ) || !is_numeric( $paged ) || $paged <= 0 ) { 
                $paged = 1; 
            }

            $totalpages = ceil( $total_items / $this->_per_page );
            
            if ( !empty( $paged ) && !empty( $this->_per_page ) ){
                $offset    = ( $paged - 1 ) * $this->_per_page;
                $limit_sql = (int)$offset.',' . (int)$this->_per_page;
            }
            
            $result = $wpdb->get_results( "SELECT id, title, pid, taxid, isrun FROM $wpdb->rss_settings ORDER BY $order_sql LIMIT $limit_sql", ARRAY_A );
        }
        
        $i = 0;
        foreach ( $result as $k => $store_details ) {
            
            /* Check for thumbnails */
            if ( absint ( $store_details["thumb"] ) ) {
				$thumb_src = wp_get_attachment_image_src( $store_details["thumb"] );
				$result[$i]["thumb"] = $thumb_src[0];
			}
            
            $i++;
        }
        
        $response = array(
            "data"  => stripslashes_deep( $result ),
            "count" => $total_items
        );
        
        return $response;
    }   

    /**
     * Prepares the list of items for displaying.
     * 
     * @since 1.0
     * @uses WP_List_Table::set_pagination_args()
     * @return void
     */
    function prepare_items() {
        
        $columns  = $this->get_columns();
        $hidden   = array();
        $sortable = $this->get_sortable_columns();
        
        $this->process_bulk_action();        
        $response = $this->get_store_list();

        $current_page = $this->get_pagenum();
        $total_items  = $response['count'];
        
        $this->set_pagination_args( array(
            'total_items' => $total_items,
            'per_page'    => $this->_per_page,
            'total_pages' => ceil( $total_items / $this->_per_page ) 
        ) );

        $this->items = $response['data'];
        $this->_column_headers = array( $columns, $hidden, $sortable );       
    }
    
    /**
     * Display the table with the store list
     * 
     * @since 1.0
     * @uses WP_List_Table::display_tablenav()
     * @uses WP_List_Table::get_table_classes()
     * @uses WP_List_Table::print_column_headers()
     * @uses WP_List_Table::display_rows_or_placeholder()
     * @return void
     */
   function display() {
        extract( $this->_args );

        $this->display_tablenav( 'top' );
	?>
        <table class="wp-list-table <?php echo implode( ' ', $this->get_table_classes() ); ?>" cellspacing="0">
                <thead>
                <tr>
                        <?php $this->print_column_headers(); ?>
                </tr>
                </thead>

                <tfoot>
                <tr>
                        <?php $this->print_column_headers( false ); ?>
                </tr>
                </tfoot>

                <tbody id="the-list"<?php if ( $singular ) echo " data-wp-lists='list:$singular'"; ?>>
                        <?php $this->display_rows_or_placeholder(); ?>
                </tbody>
        </table>
        <?php
            $this->display_tablenav( 'bottom' );
    }

    /**
     * Send required variables to JavaScript land
     */
    function _js_vars() {

        $args = array(
            'url'    => LOGICS_URL
        );

        printf( "<script type='text/javascript'>var logics_data = %s;</script>\n", json_encode( $args ) );
    }
    
}