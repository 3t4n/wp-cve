<?php 
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

add_action('admin_menu', 'CPIW_pincode_list');
 
function CPIW_pincode_list() {

    add_submenu_page(
        'pin-code',
        __( 'List Pincodes', 'check-pincode-in-woocommerce'),
        __( 'List Pincodes', 'check-pincode-in-woocommerce'),
        'manage_options',
        'my-list-pincode-submenu-page',
        'CPIW_pincode_list_callback' 
    );  


}

 function CPIW_pincode_list_callback() {
    $exampleListTable = new cpiw_List_Table();
    $exampleListTable->prepare_items();
    
    if(isset($_GET['delete']) && $_GET['delete'] == 'success') { ?>
        <div class="notice notice-success is-dismissible">
             <p><?php echo  esc_html( __( 'Record deleted successfully.' , 'check-pincode-in-woocommerce' ) ); ?></p>
        </div>
    <?php } ?>
    <div id="poststuff">
        <div class="postbox">
            <div class="postbox-header">
                <h2><?php echo __('List Pin Code','check-pincode-in-woocommerce');?></h2>
            </div>
            
            <div class="inside">

                <form  method="post" class="cpiw_list_postcode">
                    <?php
                        $list_paged = sanitize_text_field($_REQUEST['page']);
                        $page  = $list_paged;
                        $paged = filter_input( INPUT_GET, 'paged', FILTER_SANITIZE_NUMBER_INT );

                        printf( '<input type="hidden" name="page" value="%s" />', $page );
                        printf( '<input type="hidden" name="paged" value="%d" />', $paged ); 
                    ?>
                    <?php $exampleListTable->display(); ?>
                </form>
            </div>
        </div>
    </div>
    <?php
}

class cpiw_List_Table extends WP_List_Table {
    public function __construct() {
        parent::__construct(
            array(
                'singular' => 'singular_form',
                'plural'   => 'plural_form',
                'ajax'     => false
            )
        );
    }


    public function prepare_items() {
        
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        $data = $this->table_data();
        usort( $data, array( &$this, 'sort_data' ) );
        $perPage = 10;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);
        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );
        $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
        $this->process_bulk_action();
    }
   

    public function get_columns() {
        $columns = array(
            'cb'        => '<input type="checkbox" />',
            'pincode'     => 'Pincode',
            'city'        => 'City',
            'state'       => 'State',
            'date'        => 'Delivery Day',
            'shipping_amount' =>'Shipping Amount',
            'cod'        => 'Cash on Delivery',
        );
        return $columns;
    }
   

    public function get_hidden_columns() {
        return array();
    }
  

    public function get_sortable_columns() {
        return array('pincode' => array('pincode', false));
    }


    private function table_data() {
        $data = array();
        global $wpdb;
        $tablename = $wpdb->prefix.'cpiw_pincode';
        $cpiw_records = $wpdb->get_results( "SELECT * FROM $tablename" );
        foreach ($cpiw_records as $cpiw_record) {

            if($cpiw_record->caseondilvery == '1') {
                $cod = 'Yes';
            } else {
                $cod = 'No';
            }

            $data[] = array(
                'id'          => $cpiw_record->id,
                'pincode'     => $cpiw_record->pincode,
                'city'        => $cpiw_record->city,
                'state'       => $cpiw_record->state,
                'date'        => $cpiw_record->ddate,
                'shipping_amount' =>$cpiw_record->ship_amount,
                'cod'         => $cod,
            );
        }
        return $data;
    }
   

    public function column_default( $item, $column_name ) {
        switch( $column_name ) {
            case 'id':
                return $item['id'];
            case 'pincode':
                return $item['pincode'];
            case 'city':
                return $item['city'];
            case 'state':
                return $item['state'];
            case 'date':
                return $item['date'];
            case 'shipping_amount':
                return $item['shipping_amount'];
            case 'cod':
                return $item['cod'];
            default:
                return print_r( $item, true ) ;
        }
    }


    private function sort_data( $a, $b ) {
        // Set defaults
        $orderby = 'pincode';
        $order = 'asc';
        // If orderby is set, use this as the sort column
        if(!empty($_GET['orderby'])) {
            $orderby = sanitize_text_field($_GET['orderby']);
        }
        // If order is set use this as the order
        if(!empty($_GET['order'])) {
            $order = sanitize_text_field($_GET['order']);
        }
        $result = strcmp( $a[$orderby], $b[$orderby] );
        if($order === 'asc') {
            return $result;
        }
        return -$result;
    }


    public function get_bulk_actions() {
        return array(
            'delete' => __( 'Delete', 'cpiw' ),
        );
    }


    function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />', $item['id']
        );    
    }

    function cpiw_recursive_sanitize_text_field($array) {
         
        foreach ( $array as $key => &$value ) {
            if ( is_array( $value ) ) {
                $value = $this->cpiw_recursive_sanitize_text_field($value);
            }else{
                $value = sanitize_text_field( $value );
            }
        }
        return $array;
    }



    public function process_bulk_action() {
        global $wpdb;
        $tablename = $wpdb->prefix.'cpiw_pincode';
        // security check!
        if ( isset( $_POST['_wpnonce'] ) && ! empty( $_POST['_wpnonce'] ) ) {
            $_wpnonce =  sanitize_text_field($_POST['_wpnonce']);
            $nonce  = $_wpnonce;
            $action = 'bulk-' . $this->_args['plural'];

            if ( ! wp_verify_nonce( $nonce, $action ) )
                wp_die( 'Nope! Security check failed!' );
        }

        $action = $this->current_action();
        switch ( $action ) {

            case 'delete':
                $ids = isset($_REQUEST['id']) ? $this->cpiw_recursive_sanitize_text_field($_REQUEST['id']) : array();

                if (is_array($ids)) $ids = implode(',', $ids);

                    if (!empty($ids)) {
                        $wpdb->query("DELETE FROM $tablename WHERE id IN($ids)");
                    }

                wp_redirect( $_SERVER['HTTP_REFERER'] );

                break;

            default:
                // do nothing or something else
                return;
                break;
        }
        return;
    }


    function column_pincode($item) {

        $delete_url = wp_nonce_url( admin_url().'?page=my-add-pincode-submenu-page&action=cpiw_delete&id='.$item['id'], 'my_nonce' );
        
        $actions = array(
            'edit'      => sprintf('<a href="?page=my-add-pincode-submenu-page&action=%s&id=%s">Edit</a>','pincode_edit',$item['id']),
            'delete'    => '<a href="'.$delete_url.'">Delete</a>',
        );

        return sprintf('%1$s %2$s', $item['pincode'], $this->row_actions($actions) );
    }
}