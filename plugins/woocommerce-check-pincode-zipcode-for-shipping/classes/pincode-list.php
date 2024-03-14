<?php 
if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class Phoeniixx_Pincode_Zipcode_List_Table extends WP_List_Table{

	private $pincode_listing;
    private $pincode;

    private function get_pincode_listing_data(){
     	global $wpdb,$table_prefix;
        $this->pincode = new Pincode;

        if(isset($_GET['orderby']) && isset($_GET['order'])){
            return $this->pincode::all($_GET['orderby'],$_GET['order']);
        }



        if(isset($_POST['s'])){
            return $this->pincode::select(sanitize_text_field($_POST['s']),'pincode');
        }else{
            return $this->pincode::all();
        }
    }

    // Define table columns
    public function get_columns(){
        $columns = array(
            'cb'            => '<input type="checkbox" />',
            'pincode'       => "Pincode",
            'city'          => "City",
            'state'         => "State",
            'country'       => "Country",
            'dod'           => "Delivery Within a Days",
            'cod'           => "Cash On Delivery"
        );
        return $columns;
    }

    // Bind table with columns, data and all
    public function prepare_items(){

        $this->pincode_listing          = $this->get_pincode_listing_data();
        $columns               			= $this->get_columns();
        $hidden                 		= array();
        $sortable               		= $this->get_sortable_columns();
        $this->_column_headers  		= array($columns, $hidden, $sortable);
        
        //pagination
        $per_page               		= 20;
        $current_page           		= $this->get_pagenum();
        $total_items            		= count($this->pincode_listing);
        $this->pincode_listing          = array_slice($this->pincode_listing, (($current_page - 1) * $per_page), $per_page);
        $this->set_pagination_args(array(
            'total_items' => $total_items, // total number of items
            'per_page'    => $per_page // items to show on a page
        ));

        $this->items = $this->pincode_listing;
        $this->process_bulk_action();
    }

    // bind data with column
    public function column_default($item, $column_name){
        switch ($column_name) {
            case 'id':
            	return $item[$column_name];
            case 'pincode':
            	return $item[$column_name];
            case 'city':
                return $item[$column_name];
            case 'state':
                return $item[$column_name];
            case 'country':
                return $item[$column_name];
            case 'dod':
                return $item[$column_name];
            case 'cod':
                return $item[$column_name];
            default:
                return print_r($item, true); //Show the whole array for troubleshooting purposes
        }
    }

    public function column_cb($item){
        return sprintf( '<input type="checkbox" name="ID[]" value="%s" />', $item['id']);
    }

    public function column_pincode($item){
        $actions = array(
            'edit' => sprintf('<a href="?page=%s&action=%s&id=%s">Edit</a>',sanitize_text_field( $_REQUEST['page'] ),'edit',$item['id']),
            'delete' => sprintf('<a class="confirm-delete" href="?page=%s&action=%s&id=%s">Delete</a>',sanitize_text_field( $_REQUEST['page'] ),'delete',$item['id']),

        );
        return sprintf('%1$s %3$s', $item['pincode'],$item['id'],$this->row_actions($actions));
    }

    protected function get_sortable_columns(){
        return array(
            'state' => array('state', false),
            'city'  => array('city', false),
        );
    }

    protected function get_bulk_actions() {
        return ['delete' => __( 'Delete', 'phoeniixx-pincode-zipcode' )];
    }

     public function process_bulk_action() {
        if ( isset( $_POST['_wpnonce'] ) && ! empty( $_POST['_wpnonce'] ) ) {

            $nonce  = filter_input( INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING );
            $action = 'bulk-' . $this->_args['plural'];

            if ( ! wp_verify_nonce( $nonce, $action ) ){
                wp_die( 'Nope! Security check failed!' );
            }
        }

        $action = $this->current_action();

        switch ( $action ) {
            case 'delete':
                $this->phoeniixx_pincode_zipcode_delete_pincode($_POST['ID']);
                break;

            default:
                return;
                break;
        }
        return;
    }

    private function phoeniixx_pincode_zipcode_delete_pincode($IDs){
        if(!empty($IDs) && is_array($IDs)){
            foreach($IDs as $id){
                $this->pincode::delete($id);
            }
            wp_redirect($_SERVER['HTTP_REFERER']);
        }
    }
}?>