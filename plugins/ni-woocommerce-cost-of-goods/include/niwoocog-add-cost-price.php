<?php
if ( ! defined( 'ABSPATH' ) ) { exit;}
if( !class_exists( 'NiWooCOG_Add_Cost_Price' ) ) { 
	include_once("ni-cog-function.php"); 
	class NiWooCOG_Add_Cost_Price  extends Ni_COG_Function{
		 var $ni_cost_goods ='_ni_cost_goods';
		 public function __construct(){
		 	
		 }
		 function page_init(){
			 $exampleListTable = new Product_List_Table();
			 $exampleListTable->prepare_items();
			?>
            <div id="niwoocog">
            	<div class="wrap">    
                <h2><?php _e( 'Add Cost Price', "wooreportcog"); ?></h2>
                    <div id="nds-wp-list-table-demo">			
                        <div id="nds-post-body">		
                    <form id="nds-user-list-form" method="post">
                        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
                        <?php 
                            $exampleListTable->search_box( __( 'Search Product', "wooreportcog" ), 'wooreportcog');
                            $exampleListTable->display(); 
                        ?>					
                    </form>
                        </div>			
                    </div>
            </div>
            </div>
            
            <?php	
		 }
		 function page_init2(){
			
			$exampleListTable = new Product_List_Table();
			$exampleListTable->prepare_items();
			?>
				<div class="wrap">
					<div id="icon-users" class="icon32"></div>
					<h2>Add Cost Price</h2>
                    <?php  // $exampleListTable->search_box('search', 'search_id'); ?>
					<?php $exampleListTable->display(); ?>
				</div>
			<?php
		 }
		 function page_ajax(){
			 $call = isset($_REQUEST["call"])?$_REQUEST["call"]:'';
			 if ($call == "add_cost"){
			 	$this->add_product_cost();
			 }
		 	
			
		 }
		 function add_product_cost(){
			 
			
			 
			 $message = array();
			 $message["status"] = 1;
			 $message["message"] = "Record Saved";
			 try {
				 $product_id 	=  absint(isset($_REQUEST["product_id"])?$_REQUEST["product_id"]:0);
				 $product_cost  = isset($_REQUEST["product_cost"])?$_REQUEST["product_cost"]:'';
				 $ni_cost_goods =  $this->get_cog_setting_by_key("ni_cog_meta_key",'_ni_cost_goods');
				 if(is_array($ni_cost_goods)){
					error_log("Bulk Cost of Goods Array(0004)".$data);
					return false;
				}
				 update_post_meta(	 $product_id ,$ni_cost_goods, $product_cost);
				 
			 }
			 catch(Exception $e) {
  				$message["status"] = 0;
			 	$message["message"] = $e->getMessage();
			 }
			 
			 echo json_encode($message);
			 
			 die;
		 }
		
  	}
	class Product_List_Table extends WP_List_Table{
		
		 var $ni_cost_goods ='_ni_cost_goods';
		 
		 
		/**
		 * Prepare the items for the table to process
		 *
		 * @return Void
		 */
		public function prepare_items()
		{
			$columns = $this->get_columns();
			$hidden = $this->get_hidden_columns();
			$sortable = $this->get_sortable_columns();
	
			$data = $this->table_data();
			usort( $data, array( &$this, 'sort_data' ) );
	
			$perPage = 15;
			$currentPage = $this->get_pagenum();
			$totalItems = count($data);
	
			$this->set_pagination_args( array(
				'total_items' => $totalItems,
				'per_page'    => $perPage
			) );
	
			$data = array_slice($data,(($currentPage-1)*$perPage),$perPage);
	
			$this->_column_headers = array($columns, $hidden, $sortable);
			$this->items = $data;
		}
		/**
		 * Override the parent columns method. Defines the columns to use in your listing table
		 *
		 * @return Array
		 */
		public function get_columns()
		{
			$columns = array(
				'product_id'  	=> esc_html__('#ID', 'wooreportcog') ,
				'product_name'  => esc_html__('Product Name', 'wooreportcog')  ,
				'cost_price'  	=>  esc_html__('Cost Price', 'wooreportcog') ,
				'regular_price'  	=>  esc_html__( 'Regular Price', 'wooreportcog'),
				'sale_price'  	=>  esc_html__(  'Sale Price', 'wooreportcog'),
				'profit'  	=>  esc_html__(  'Profit', 'wooreportcog') ,
				'update'  	=>  esc_html__( 'Update', 'wooreportcog') ,
				
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
			return array();
		}
	
		/**
		 * Define the sortable columns
		 *
		 * @return Array
		 */
		public function get_sortable_columns()
		{
			//return array('product_name' => array('product_name', false));
			
			$sortable_columns = array(
				'product_name' => array('product_name',true) ,
				'regular_price' => array('regular_price',true) ,
				'sale_price' => array('sale_price',true) ,
				'profit' => array('profit',true) ,
			
				
			);
			return $sortable_columns;
			
		}
		public function table_data(){
			global $wpdb;
			$product_parent =  		$this->get_product_parent();
			 $meta_key				=  $this->get_item_meta_key_list() ;
			 
		
			 // check if a search was performed.
			$user_search_key = isset( $_REQUEST['s'] ) ? wp_unslash( trim( $_REQUEST['s'] ) ) : '';
			//error_log($user_search_key);
	
			 
		 	$query  = "";
			$query .=" SELECT    ";
			$query .=" post.ID as product_id ";
			$query .=", post.post_title as product_name ";
		
			$query .=" FROM {$wpdb->prefix}posts as post  ";
			
			$query .=" WHERE 1=1 ";
			$query .=" AND post.post_type  IN ('product_variation','product') ";
			$query .=" AND post.ID NOT IN ('".  implode("','", $product_parent). "') ";
			$query .=" AND post.post_status='publish'";
			
			if ($user_search_key !=''){
				$query .=" AND  post.post_title  LIKE '%{$user_search_key}%'";	 
			}
			
			$query .=" Order BY  post.post_title asc ";
			
			$row = $wpdb->get_results( $query ,'ARRAY_A');	
			
			
			foreach($row as $key=>$value){
				$product_id =$value["product_id"] ;
				$all_meta = $this->get_wooreport_cog_post_meta($product_id,$meta_key);
				//$this->prettyPrint($all_meta);
			
				foreach($all_meta as $k=>$v){
					$row[$key][$k] =$v;
				}
			}
			
			
			//$this->prettyPrint($row);
			//	error_log(print_r($row,true));
			return $row;
		}
		public  function get_product_parent(){
		    global $wpdb;
			$query = "";
			$query = " SELECT ";
			$query .= " posts.post_parent as post_parent ";
			$query .= " FROM  {$wpdb->prefix}posts as posts			";
			$query .= "	WHERE 1 = 1";
			$query .= "	AND posts.post_type  IN ('product_variation') ";
			$query .=" AND posts.post_status='publish'";
			
			$query .= " GROUP BY post_parent ";
			$row = $wpdb->get_results($query);		
			
			$post_parent_array = array();
			foreach($row as $key=>$value){
				$post_parent_array[] = $value->post_parent;
			}
			return $post_parent_array;
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
			$add_class = "";
			$product_id  = absint( isset($item[ "product_id"])?$item[ "product_id"]:0);
			$cost_price = get_post_meta($product_id,$this->get_cog_setting_by_key("ni_cog_meta_key",'_ni_cost_goods'),true);
			
			if(!is_numeric($cost_price)){
				$cost_price = 0;
			}
			
		
				
			$regular_price = 	isset( $item[ "regular_price" ]) ? $item[ "regular_price" ]:0;
			
			$profit = $regular_price - $cost_price ;
			if ($profit > 0){
				$add_class = "niwoocog_success";
			}else{
				$add_class = "niwoocog_error";
			}
						
			switch( $column_name ) {
				case 'product_id':
				case 'product_name':
					return $item[ $column_name ];	
				case 'regular_price':
				case 'sale_price':
					return isset($item[ $column_name ])?$item[ $column_name ]:0;	
				case 'cost_price':
					return  '<input type="number" name="cost_price" class="_product_cost" value='.$cost_price .'>';	
				case 'update':
					return  '<a href="#" class="_add_product_cost" product-id='.$product_id.'>Add</a>';	
				case 'profit':
						return  '<span class='.$add_class.'>'.$profit.'</span>';		
					//return $profit;
				default:
					return print_r( $item, true ) ;
			}
		}
		  /**
		 * Allows you to sort the data by the variables set in the $_GET
		 *
		 * @return Mixed
		 */
		private function sort_data( $a, $b )
		{
			// Set defaults
			$orderby = 'product_name';
			$order = 'asc';
	
			// If orderby is set, use this as the sort column
			if(!empty($_GET['orderby']))
			{
				$orderby = $_GET['orderby'];
			}
	
			// If order is set use this as the order
			if(!empty($_GET['order']))
			{
				$order = $_GET['order'];
			}
	
	
			$result = strcmp( $a[$orderby], $b[$orderby] );
	
			if($order === 'asc')
			{
				return $result;
			}
	
			return -$result;
		}
		function get_wooreport_cog_post_meta($post_id=0, $meta_key =  array()){
			$new_row_cog  = array();
			global $wpdb;
			$query_cog =  "";
			$query_cog .=  " SELECT ";
			$query_cog .=  " *  ";
			$query_cog .=  " FROM  {$wpdb->prefix}postmeta as postmeta ";
			$query_cog .=  " WHERE 1 = 1";
			$query_cog .=  " AND postmeta.post_id = '{$post_id}'";
			if (count($meta_key)>0){
				$query_cog .=  " AND postmeta.meta_key IN ('" . implode("','", $meta_key) . "')"; 
			}
			
			$row_cog =  $wpdb->get_results($query_cog);
			foreach($row_cog as $k=>$v){
				$new_row_cog[ltrim ($v->meta_key,"_")] =$v->meta_value; 
			}
			
			return $new_row_cog;
		}
		function get_item_meta_key_list(){
			$meat_key = array("_sku","_manage_stock","_stock","_backorders","_visibility","_regular_price","_sale_price","_price","_stock_status",$this->get_cog_setting_by_key("ni_cog_meta_key",'_ni_cost_goods'));
			return $meat_key;
		}
		public function get_cog_setting_by_key($key,$default=''){
			$niwoocog_setting = array();	 
			$niwoocog_setting =  get_option("niwoocog_setting",array());
			$ni_cog_setting = '';
		    $ni_cog_setting = sanitize_text_field(isset($niwoocog_setting[$key])?$niwoocog_setting[$key]:$default);
			
			if(empty( $ni_cog_meta_key)){
				$ni_cog_meta_key = '_ni_cost_goods';	 
			 }
			 $this->ni_cost_goods = $ni_cog_meta_key;
			
			return $this->ni_cost_goods;
 			 
		}
	
	}
}  	 