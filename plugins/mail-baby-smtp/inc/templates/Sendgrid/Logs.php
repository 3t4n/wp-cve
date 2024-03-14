<?php
/**
 * WP SendGrid Mailer Plugin file.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */

namespace WPMailPlus;

class Logs extends WP_List_Table
{
    /**
     * Per page count
     * @var int
     */
    public $per_page_count = 10;

    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns()
    {
        $columns = array(
            'id'             => 'ID',
            'mail_from'      => 'From',
            'mail_to'        => 'To',
            'email_service'  => 'Service',
            'email_subject'  => 'Subject',
            'sent_time'      => 'Time',
            'status'         => 'Status',
        );

        return $columns;
    }

    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns()
    {
        return array('id');
    }

    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns()
    {
        return array('sent_time' => array('sent_time', false));
    }

    /**
     * Prepare where condition
     * @return string
     */
    private function prepare_where_condition()
    {
        $where_query = '';
        if($_POST)
        {
            if(isset($_POST['service']) && !empty($_POST['service']))
                $service = sanitize_text_field($_POST['service']);
                $where_query .= " email_service = '{$service}' and";

            if(isset($_POST['status']) && !empty($_POST['status']))
                $status = sanitize_text_field($_POST['status']);
                $where_query .= " status = '{$status}' and";

            if($where_query)    {
                $where_query = ' where ' . $where_query;
                $where_query = substr($where_query, 0, -3);
            }
        }
        return $where_query;
    }

    /**
     * Get the table data
     *
     * @return Array
     */
    private function table_data()
    {
        global $wpdb;
        // If orderby is set, use this as the sort column
        if(!empty($_GET['orderby']))
            $orderby = sanitize_sql_orderby($_GET['orderby']);

        // If order is set use this as the order
        if(!empty($_GET['order']))
            $order = sanitize_text_field($_GET['order']);

        $order_query = null;
        if(!empty($orderby) && !empty($order))
            $order_query = " order by $orderby $order ";

        $where_query = $this->prepare_where_condition();

        $current_page = $this->get_pagenum();
        $from = ($current_page - 1) * $this->per_page_count;
        $table_name = $wpdb->prefix . "mailplus_logs";
        $data = $wpdb->get_results("select * from $table_name {$where_query} {$order_query} LIMIT $from, $this->per_page_count");
        return $data;
    }

    /**
     * Used to display the value of the id column
     *
     * @param $item
     * @return Integer
     */
    public function column_id($item)
    {
        return $item->id;
    }

    /**
     * Define what data to show on each column of the table
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default( $item, $column_name )
    {
        $allowed_html = array(
            'a'      => array(
                'class' => array(),
                'id' => array(),
                'href'  => array(),
                'title' => array(),
            ),
            'script'     => array(),
            'em'     => array(),
            'img' => array(),
            'td' => array(),
            'tr' => array()
        );
        switch( $column_name ) {
            case 'id':
                echo esc_attr($item->$column_name);
                break;
            case 'mail_from':
                echo esc_attr($item->$column_name);
                break;
            case 'mail_to':
                echo esc_attr($item->$column_name);
                break;
            case 'email_service':
                echo esc_attr($item->$column_name);
                break;
            case 'email_subject':
                echo esc_attr($item->$column_name);
                break;
            case 'status':
                if($item->$column_name == 'Failed') {
                    $output = "<span class = 'label label-danger'> Failed </span>  <span class='dashicons dashicons-info' data-toggle='tooltip' data-placement='bottom' title = '{$item->message}'></span>";
                    echo wp_kses($output, $allowed_html);
                }
                else if($item->$column_name == 'Success')   {
                    $output =  "<span class = 'label label-success'> Success </span>";
                    echo wp_kses($output, $allowed_html);
                }
                break;
            case 'sent_time':
                echo esc_attr($item->$column_name);
                break;
        }
    }

    public static function process()
    {
        include( WPMP_PLUGIN_DIR . 'includes/views/Logs.php' );
    }

    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items()
    {
        global $wpdb;
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        $table_name = $wpdb->prefix . "mailplus_logs";
        $where_query = $this->prepare_where_condition();
        $getLogCount = $wpdb->get_results("select count(*) as count from {$table_name} {$where_query}");
        $this->set_pagination_args( array(
            'total_items' => $getLogCount[0]->count,
            'per_page'    => $this->per_page_count
        ) );

        $data = $this->table_data();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }
}
