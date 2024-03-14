<?php
class ChatGPT_Assistant_Rates_List_Table extends WP_List_Table{
    private $plugin_name;

    /** Class constructor */
    public function __construct($plugin_name) {
        $this->plugin_name = $plugin_name;
        parent::__construct( array(
            'ajax'     => false //does this table support ajax?
        ) );
    }

    /**
     * Retrieve customers data from the database
     *
     * @param int $per_page
     * @param int $page_number
     *
     * @return mixed
     */
    public static function get_data( $per_page = 20, $page_number = 1 ) {
        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->prefix}ayschatgpt_rates";

        // if ( self::get_where_condition() !== false ) {
        //     $sql .= self::get_where_condition();
        // } else {
        //     return array();
        // }

        // if ( ! empty( $_REQUEST['orderby'] ) ) {
        //     $order_by  = ( isset( $_REQUEST['orderby'] ) && sanitize_text_field( $_REQUEST['orderby'] ) != '' ) ? sanitize_text_field( $_REQUEST['orderby'] ) : 'id';
        //     $order_by .= ( ! empty( $_REQUEST['order'] ) && strtolower( $_REQUEST['order'] ) == 'asc' ) ? ' ASC' : ' DESC';

        //     $sql_orderby = sanitize_sql_orderby($order_by);

        //     if ( $sql_orderby ) {
        //         $sql .= ' ORDER BY ' . $sql_orderby;
        //     } else {
                $sql .= ' ORDER BY id DESC';
        //     }
        // }

        $sql .= " LIMIT $per_page";
        $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;

        $result = $wpdb->get_results( $sql, 'ARRAY_A' );

        return $result;
    }

    /**
     * Returns the count of records in the database.
     *
     * @return null|string
     */
    public static function record_count() {
        global $wpdb;
        
        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}ayschatgpt_rates ";

        // if ( self::get_where_condition() !== false ) {
        //     $sql .= self::get_where_condition();
        // } else {
        //     return 0;
        // }

        return $wpdb->get_var( $sql );
    }

    /** Text displayed when no customer data is available */
    public function no_items() {
        echo __( 'There are no rates yet.', $this->plugin_name );
    }

    /**
     * Render a column when no column specific method exist.
     *
     * @param array $item
     * @param string $column_name
     *
     * @return mixed
     */
    public function column_default( $item, $column_name ) {
        switch ( $column_name ) {
            case 'id':
            case 'user_id':
            case 'user_name':
            case 'user_email':
            case 'chat_source':
            case 'chat_type':
            case 'action':
            case 'feedback':
            case 'date':
                return $item[ $column_name ];
                break;
            default:
                return print_r( $item, true ); //Show the whole array for troubleshooting purposes
        }
    }

    // function column_cb( $item ) {
    //     return sprintf(
    //         '<input type="checkbox" class="ays-chatgpt-assistant-log-id-cb" name="bulk_action_data[]" value="%s" />', $item['id']
    //     );
    // }

    function column_post_title( $item ) {
        $post_id = $item['post_id'];

        if ($post_id == -2) {
            $post_title = __('Home page', 'chatgpt-assistant');
        } else {
            $post_data = get_post($post_id);
            $post_title = isset($post_data->post_title) ? $post_data->post_title : __('No post info', 'chatgpt-assistant');
        }

        return $post_title;
    }

    /**
     *  Associative array of columns
     *
     * @return array
     */
    function get_columns() {
        $columns = array(
            // 'cb'                    => '<input type="checkbox" />',
            'id'                    => __( 'ID', $this->plugin_name ),
            'user_id'               => __( 'User ID', $this->plugin_name ),
            'user_name'             => __( 'User Name', $this->plugin_name ),
            'user_email'            => __( 'User Email', $this->plugin_name ),
            'post_title'            => __( 'Post Title', $this->plugin_name ),
            'date'                  => __( 'Date', $this->plugin_name ),
            'chat_source'           => __( 'Source', $this->plugin_name ),
            'chat_type'             => __( 'Chat Type', $this->plugin_name ),
            'action'                => __( 'Action', $this->plugin_name ),
            'feedback'              => __( 'Feedback', $this->plugin_name ),
        );

        return $columns;
    }

    /**
     * Columns to make sortable.
     *
     * @return array
     */
    public function get_sortable_columns() {
        $sortable_columns = array(
            'post_id'               => array( 'post_id', true ),
            'date'                  => array( 'date', true ),
        );

        return $sortable_columns;
    }

    /**
     * Handles data query and filter, sorting, and pagination.
     */
    public function prepare_items() {

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = array();
        $this->_column_headers = array($columns, $hidden, $sortable);

        $per_page     = 20;

        $current_page = $this->get_pagenum();
        $total_items  = self::record_count();

        $this->set_pagination_args( array(
            'total_items' => $total_items, //WE have to calculate the total number of items
            'per_page'    => $per_page //WE have to determine how many items to show on a page
        ) );

        $this->items = self::get_data( $per_page, $current_page );
    }

}
