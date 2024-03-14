<?php
if ( ! defined( 'ABSPATH' ) ) { exit;}
if( !class_exists( 'Ni_COG_Sales_Report' ) ) { 
	include_once("ni-cog-function.php"); 
	class Ni_COG_Sales_Report  extends Ni_COG_Function{
		 var $ni_cost_goods ='_ni_cost_goods';
		 public function __construct(){
		 	 $ni_cog_meta_key = $this->get_cog_setting_by_key("ni_cog_meta_key",'_ni_cost_goods');
			 if(empty( $ni_cog_meta_key)){
				$ni_cog_meta_key = '_ni_cost_goods';	 
			 }
			 $this->ni_cost_goods = $ni_cog_meta_key;
		 }
		 function page_init(){
		 $input_type="text";
		 $input_type="hidden";	
		 $order_days = $this->get_order_days();
		 $order_country = $this->get_order_country();
		 ?>
         <div class="container-fluid">
            	<div id= 'niwoocog'>
                	<div class="row">
                    <div class="col">
                        <div class="card " style="max-width:50%">
                            <div class="card-header bd-indigo-400">
                                <?php esc_html_e("Search Product Profit Report", 'wooreportcog'); ?>
                            </div>
                            <div class="card-body">
                                <form name="frm_cog_report" id="frm_cog_report">
                                    <div class="row">
                                        <div class="col-3">
                                            <label for="order_days">
                                                <?php  esc_html_e("Select Order Days", 'wooreportcog'); ?>
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <select id="select_order" name="select_order" class="form-control" >
                                                <?php foreach($order_days as $key=>$value): ?>
                                                    <option value="<?php esc_attr_e($key); ?>">
                                                        <?php  esc_html_e( $value ); ?>
                                                    </option>
                                                    <?php endforeach;?>
                                            </select>
                                        </div>
                                        <div class="col-3">
                                            <label for="order_country">
                                                <?php  esc_html_e("Order Country", 'wooreportcog'); ?>
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <select id="order_country" name="order_country" class="form-control" >
                                                <option value="-1">  <?php  esc_html_e("Select One Country", 'wooreportcog'); ?></option>
												<?php foreach($order_country as $key=>$value): ?>
                                                	
                                                    <option value="<?php esc_attr_e($value->billing_country); ?>">
                                                        <?php  esc_html_e( $value->country_name ); ?>
                                                    </option>
                                                    <?php endforeach;?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                    	<div  class="col"  style="padding:10px;"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3">
                                            <label for="order_days">
                                                <?php  esc_html_e("First Name", 'wooreportcog'); ?>
                                            </label>
                                        </div>
                                        <div class="col-3">
                                           <input type="text"  class="form-control" name="first_name"  />
                                        </div>
                                        <div class="col-3">
                                            <label for="order_country">
                                                <?php  esc_html_e("Last Name", 'wooreportcog'); ?>
                                            </label>
                                        </div>
                                        <div class="col-3">
                                             <input type="text"  class="form-control" name="last_name"  />
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                    	<div  class="col"  style="padding:10px;"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3">
                                            <label for="order_id">
                                                <?php  esc_html_e("#ID", 'wooreportcog'); ?>
                                            </label>
                                        </div>
                                        <div class="col-3">
                                           <input type="text"  class="form-control" name="order_id"  id="order_id" />
                                        </div>
                                        <div class="col-3">
                                            <label for="email_address">
                                                <?php  esc_html_e("Email", 'wooreportcog'); ?>
                                            </label>
                                        </div>
                                        <div class="col-3">
                                             <input type="text"  class="form-control" name="email_address" id="email_address"  />
                                        </div>
                                    </div>
                                     <div class="row">
                                    	<div  class="col"  style="padding:10px;"></div>
                                    </div>
                                    
                                    <div class="row">
                                    	<div class="col-3">
                                        	<label for="order_days">
                                                <?php  esc_html_e("Order By", 'wooreportcog'); ?>
                                            </label>
                                        </div>
                                        <div class="col-3">
                                        	<select id="order_by" name="order_by">
                                            	<option value="order_id"> <?php  esc_html_e("#ID", 'wooreportcog'); ?></option>
                                                <option value="order_date"> <?php  esc_html_e("Order Date", 'wooreportcog'); ?></option>
                                                <option value="first_name"> <?php  esc_html_e("First Name", 'wooreportcog'); ?></option>
                                                <option value="last_name"> <?php  esc_html_e("Last Name", 'wooreportcog'); ?></option>
                                            </select>
                                        </div>
                                        
                                        <div class="col-3">
                                        	<label for="sort">
                                                <?php  esc_html_e("Sort", 'wooreportcog'); ?>
                                            </label>
                                        </div>
                                        <div class="col-3">
                                        	<select id="sort" name="sort">
                                                <option value="desc"> <?php  esc_html_e("DESC", 'wooreportcog'); ?></option>
                                                <option value="asc"> <?php  esc_html_e("ASC", 'wooreportcog'); ?></option>
                                               
                                                
                                            </select>
                                        </div>
                                        
                                    </div>
                                    
                                    <div class="row">
                                    	<div  class="col"  style="padding:10px;"></div>
                                    </div>
                                    
                                    <div class="row">
                                    	<div  class="col" style="text-align:right">
                                        <input type="submit" value="<?php  esc_html_e("Search", 'wooreportcog'); ?>" class="btn bd-blue-500  mb-2" />
                                        </div>
                                    </div>
                                    
                                   <input type="<?php echo  $input_type; ?>" name="sub_action" value="ni_cog_sales_report">
             						<input type="<?php echo  $input_type; ?>" name="action" value="ni_cog_action">
                                </form>
                                    
                            </div>
                        </div>
                    </div>
                	</div>
                	
                    
                    <div class="row" style="padding-top:20px;">
                    	<div class="col">
                        	<div class="ajax_cog_content"></div>
                        </div>
                    </div>
                    
                    
               		
           		</div>
            </div>
         <?php
		 }
		 function get_order_days(){
			$order_days = array();
			$order_days["today"] = esc_html__("Today", 'wooreportcog');
			$order_days["yesterday"] = esc_html__("Yesterday", 'wooreportcog');
			$order_days["last_7_days"] = esc_html__("Last 7 Days", 'wooreportcog');
			$order_days["last_15_days"] = esc_html__("Last 15 Days", 'wooreportcog');
			$order_days["last_30_days"] = esc_html__("Last 30 Days", 'wooreportcog');
			$order_days["last_60_days"] = esc_html__("Last 60 Days", 'wooreportcog');
			$order_days["last_90_days"] = esc_html__("Last 90 Days", 'wooreportcog');
			return $order_days;
		}
		function get_order_country(){
				global $wpdb;
			$query = "
				SELECT 
				billing_country.meta_value as 'billing_country'
				
				FROM {$wpdb->prefix}posts as posts	";	
				
			$query .= "	LEFT JOIN  {$wpdb->prefix}postmeta as billing_country ON billing_country.post_id=posts.ID ";
			
			$query .= " WHERE 1=1 ";
			$query .= " AND posts.post_type ='shop_order' ";
			$query .= " AND billing_country.meta_key ='_billing_country' ";
			$query .= " GROUP BY billing_country.meta_value";
			
			$query .= " Order BY billing_country.meta_value ASC";	
			
			//$query = $wpdb->prepare($query );
			$rows = $wpdb->get_results( $query);
			
			
			
			$countries = $this->get_countries();
			
			foreach($rows as $key=>$value){
				$rows[$key]->country_name = isset($countries[$value->billing_country])?$countries[$value->billing_country]:$value->billing_country;
			}
			
			return $rows;
		}
		function get_countries(){
			$countries_obj = new WC_Countries();
   			$countries_array = $countries_obj->get_countries();
			
			return  $countries_array ;
		} 
		function get_cog_table(){
		 	$row = $this->get_cog_sales_product();
			
			
			
			$select_order 	=  sanitize_text_field(isset($_REQUEST["select_order"])?$_REQUEST["select_order"]:'today');
			$order_id	  	= sanitize_text_field(isset($_REQUEST["order_id"])?$_REQUEST["order_id"]:'');
			
			$order_country 	= sanitize_text_field(isset($_REQUEST["order_country"])?$_REQUEST["order_country"]:'');
			$first_name		= sanitize_text_field(isset($_REQUEST["first_name"])?$_REQUEST["first_name"]:'');
			$last_name 		= sanitize_text_field(isset($_REQUEST["last_name"])?$_REQUEST["last_name"]:'');
			$order_by 		= sanitize_text_field(isset($_REQUEST["order_by"])?$_REQUEST["order_by"]:'order_id');
			$sort 			= sanitize_text_field(isset($_REQUEST["sort"])?$_REQUEST["sort"]:'asc');
			$columns_total = array();
			
			do_action("nicog_profit_report_row",$row );
			
			
			$columns = $this->get_columns();
			?>
            <div style="overflow-x:auto;">
            	<?php if (count($row)>0 ): ?>
                <div style="text-align:right">
                
                <form method="post" class="noprint">
                	<input type="hidden" value="<?php echo $select_order; ?>" name="select_order" />
                    <input type="hidden" value="<?php echo $order_id; ?>" name="order_id" />
                    
                <input type="submit" name="btn_nicog_print" id="btn_nicog_print" value="<?php  esc_html_e("Print", 'wooreportcog'); ?> " class="btn bd-blue-500  mb-2" />
                </form>
                </div>
				<?php endif; ?>
            	 <table class="table table-bordered table-striped table-hover">
                <thead class="bd-indigo-400">
            	<tr>
                	<?php foreach($columns as $key=>$value): ?>
                    	<th scope="col"><?php echo $value; ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
			<?php
			
			if (count($row)==0 ):
				?>
                <tr>
                	<td colspan="<?php echo count($columns) ?>"><?php  esc_html_e("No record found", 'wooreportcog'); ?> </td>
                </tr>
                <?php
			else :
				?>
               
                <?php	
			endif; 
			foreach($row as $key=>$value):
				if (isset($columns_total["ni_profit"])){
					$columns_total["ni_profit"] += isset($value->ni_profit)?$value->ni_profit:0;
				}else{
					$columns_total["ni_profit"] = isset($value->ni_profit)?$value->ni_profit:0;
				}
				$ahref_order_id = isset($value->order_id)?$value->order_id:0;
				$admin_url = admin_url("post.php")."?action=edit&post=".$ahref_order_id;
				?>
                <tr>
					<?php
                    foreach($columns as $k=>$v):
                    	
					?>
                    <?php switch($k) :
					
							case "order_id":
                           		$td_vale = "<a href=\"". $admin_url ."\" target=\"_blank\">". $value->order_id. "</a>"   ;
								?>
                            	<td><?php echo $td_vale;?></td>
                            	<?php
                            	break; 
					
							case "line_total":
								?>
                            	<td><?php echo  wc_price($value->$k); ?></td>
                            	<?php
								break;
							case "line_subtotal":
								?>
                            	<td><?php echo  wc_price($value->$k); ?></td>
                            	<?php
								break;
							case "ni_cost_goods";
								?>
                            	<td><?php echo  wc_price($value->$k); ?></td>
                            	<?php
								break;
                   			case "sales_price":
								$qty = 0;
								$line_total = 0;
								$qty = isset($value->qty)?$value->qty:0;
								$line_total = isset($value->line_total)?$value->line_total:0;
								?>
                            	<td><?php echo wc_price( ($line_total/	$qty)) ; ?></td>
                            	<?php
					        	break;
							case "ni_profit":
								?>
                            	<td><?php echo wc_price($value->$k) ; ?></td>
                            	<?php
								break;
							case "ni_profit3":
								$ni_cost_goods = 0;
								$sales_price = 0;
								$qty = 0;
								$line_subtotal = 0;
								$ni_profit = 0;
								$ni_cost_goods = isset($value->ni_cost_goods)?$value->ni_cost_goods:0;
								$qty = isset($value->qty)?$value->qty:0;
								$line_total = isset($value->line_total)?$value->line_total:0;
								$sales_price = ($line_total/$qty);
								$ni_profit = (($sales_price-$ni_cost_goods ) *$qty);
								if ($ni_profit < 0){
									?>
									<td style="color:#f90202;font-weight:bold"><?php echo wc_price( $ni_profit) ; ?></td>
                            		<?php	
								}else{
									?>
									<td><?php echo wc_price( $ni_profit) ; ?></td>
                            		<?php
								}
								
					        	break;	
							case "order_status":
								?>
                                  <td> <?php echo ucfirst ( str_replace("wc-","", $value->$k));?> </td>
                                <?php
								
								break;
							case "billing_email":
							?>
                                  <td><a href="mailto:<?php echo $value->$k; ?>"><?php echo $value->$k; ?></a></td>
                                <?php
								break;		
							default:
        						?>
                                 <td><?php echo $value->$k; ?></td>
                                <?php
                   	    endswitch;?>
                   
					<?php
                    endforeach;
                    ?>
                </tr>
                <?php
			endforeach;
			?>
            </tbody>
            </table>
            <br />
            <table class="table table-bordered table-striped table-hover">
                <thead class="bd-indigo-400">
                	<tr>
                    	<th style="text-align:right"><?php  esc_html_e("Total Profit", 'wooreportcog'); ?></th>
                    </tr>
                </thead>
            	<tr>
                	<td style="text-align:right"><?php echo wc_price( isset($columns_total["ni_profit"])?$columns_total["ni_profit"]:0 );?></td>
                </tr>
            </table>
            </div>
            
            <?php
		 }
		 function page_ajax(){
			$this->get_cog_table();
			die;	
		 }
		 function get_cog_sales_product(){
			 $row  = array();
			 $row = $this->get_query_data();
		 	 foreach($row as $k => $v){
				  /*Order Data*/
				$order_id =$v->order_id;
				$order_detail = $this->get_order_detail($order_id);
				foreach($order_detail as $dkey => $dvalue){
					$row[$k]->$dkey =$dvalue;
				}
				/*Order Item Detail*/
				$order_item_id = $v->order_item_id;
				$order_item_detail= $this->get_order_item_detail($order_item_id );
				foreach ($order_item_detail as $mKey => $mValue){
					$new_mKey = $str= ltrim ($mValue->meta_key, '_');
					$row[$k]->$new_mKey = $mValue->meta_value;		
				}
			 }
			 
			 

			$cost_price = 0 ;
			$line_total = 0 ;
			$quntity = 0 ;
			$profit = 0 ;
			$sales_price = 0;
			 
			$cog_meta_key = $k =ltrim($this->ni_cost_goods,'_'); 
			 
			 foreach($row as $key=>$value){
			 	$cost_price =  isset($value->$cog_meta_key)?$value->$cog_meta_key:0;
				$quntity =  isset($value->qty)?$value->qty:0;
				$line_total = isset($value->line_total)?$value->line_total:0;
				
				$sales_price = ($line_total/$quntity);
				
				$cog_profit = (($sales_price-$cost_price ) *$quntity);
				
				if (empty($cog_profit)){
					$cog_profit = 0;
				}
				$row[$key]->ni_profit = $cog_profit;
				
				
			 }
			 //$this->prettyPrint( $row);
			 return $row;	
		 }
		 function get_query_data($type="DEFAULT")
		 {
			global $wpdb;	
			$today = date_i18n("Y-m-d");
			//$select_order = $this->get_request("select_order","today");
			$select_order = sanitize_text_field(isset($_REQUEST["select_order"])?$_REQUEST["select_order"]:'today');
			$order_id = isset($_REQUEST["order_id"])?$_REQUEST["order_id"]:'0';
			
			$order_country 	= sanitize_text_field(isset($_REQUEST["order_country"])?$_REQUEST["order_country"]:'');
			$first_name		= sanitize_text_field(isset($_REQUEST["first_name"])?$_REQUEST["first_name"]:'');
			$last_name 		= sanitize_text_field(isset($_REQUEST["last_name"])?$_REQUEST["last_name"]:'');
			
			$email_address		= sanitize_text_field(isset($_REQUEST["email_address"])?$_REQUEST["email_address"]:'');
			
			$order_by 		= sanitize_text_field(isset($_REQUEST["order_by"])?$_REQUEST["order_by"]:'order_id');
			$sort 			= sanitize_text_field(isset($_REQUEST["sort"])?$_REQUEST["sort"]:'DESC');
			
			
			
			
			$query = "SELECT
			posts.ID as order_id
			,posts.post_status as order_status
			,woocommerce_order_items.order_item_id as order_item_id
			, date_format( posts.post_date, '%Y-%m-%d') as order_date 
			,woocommerce_order_items.order_item_name
			, ni_cost_goods.meta_value as ni_cost_goods
			FROM {$wpdb->prefix}posts as posts	";
					
			$query .= " LEFT JOIN  {$wpdb->prefix}woocommerce_order_items as woocommerce_order_items ON woocommerce_order_items.order_id=posts.ID ";
			
			$query .= " LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as ni_cost_goods ON ni_cost_goods.order_item_id=woocommerce_order_items.order_item_id ";
			
			if ($first_name  !=="" || $order_by ==='first_name'){
				$query .= " LEFT JOIN  {$wpdb->prefix}postmeta as first_name ON first_name.post_id=posts.ID ";
			}
			
			if ($order_country  !=="" && $order_country  !=="-1"){
				$query .= " LEFT JOIN  {$wpdb->prefix}postmeta as order_country ON order_country.post_id=posts.ID ";
			}
			
			
			if ($last_name  !==""  || $order_by ==='last_name'){
				$query .= " LEFT JOIN  {$wpdb->prefix}postmeta as last_name ON last_name.post_id=posts.ID ";
			}
			
			if ($email_address !==''){
					$query .= " LEFT JOIN  {$wpdb->prefix}postmeta as billing_email ON billing_email.post_id=posts.ID ";
			}
			
			
			
			
			$query .= "	WHERE 1=1 ";
			$query .= "	AND posts.post_type ='shop_order' 
			AND woocommerce_order_items.order_item_type ='line_item'
			AND posts.post_status IN ('wc-pending','wc-processing','wc-on-hold', 'wc-completed')";
			
			if ($first_name  !=="" || $order_by ==='first_name'){
				$query .= "	AND first_name.meta_key ='_billing_first_name' ";
				$query .= "	AND first_name.meta_value LIKE '%".$first_name ."%' ";
			}
			
			if ($last_name  !=="" || $order_by ==='last_name'){
				$query .= "	AND last_name.meta_key ='_billing_last_name' ";
				$query .= "	AND last_name.meta_value LIKE '%".$last_name ."%' ";
			}
			if ($order_country  !=="" && $order_country  !=="-1"){
				$query .= "	AND order_country.meta_key ='_billing_country' ";
				$query .= "	AND order_country.meta_value LIKE '%".$order_country ."%' ";
			}
			
			if ($email_address !==''){
				$query .= "	AND billing_email.meta_key ='_billing_email' ";
				$query .= "	AND billing_email.meta_value LIKE '%".$email_address ."%' ";
			}
			
			
			$query .= " AND ni_cost_goods.meta_key='".$this->ni_cost_goods ."' ";
			
			if ($order_id !="" and $order_id != 0){
				$query .= " AND	posts.ID IN ($order_id)";
			}
			
			switch ($select_order) {
				case "today":
					$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') BETWEEN '{$today}' AND '{$today}'";
				break;
				case "yesterday":
					$query .= " AND  date_format( posts.post_date, '%Y-%m-%d') = date_format( DATE_SUB(CURDATE(), INTERVAL 1 DAY), '%Y-%m-%d')";
				break;
				case "last_7_days":
					$query .= " AND  date_format( posts.post_date, '%Y-%m-%d') BETWEEN date_format(DATE_SUB(CURDATE(), INTERVAL 7 DAY), '%Y-%m-%d') AND   '{$today}' ";
				break;
				case "last_15_days":
					$query .= " AND  date_format( posts.post_date, '%Y-%m-%d') BETWEEN date_format(DATE_SUB(CURDATE(), INTERVAL 15 DAY), '%Y-%m-%d') AND   '{$today}' ";
				break;	
				case "last_30_days":
					$query .= " AND  date_format( posts.post_date, '%Y-%m-%d') BETWEEN date_format(DATE_SUB(CURDATE(), INTERVAL 30 DAY), '%Y-%m-%d') AND   '{$today}' ";
				break;	
				case "last_60_days":
					$query .= " AND  date_format( posts.post_date, '%Y-%m-%d') BETWEEN date_format(DATE_SUB(CURDATE(), INTERVAL 60 DAY), '%Y-%m-%d') AND   '{$today}' ";
				break;	
				case "last_90_days":
					$query .= " AND  date_format( posts.post_date, '%Y-%m-%d') BETWEEN date_format(DATE_SUB(CURDATE(), INTERVAL 90 DAY), '%Y-%m-%d') AND   '{$today}' ";
				break;		
				case "this_year":
					$query .= " AND  YEAR(date_format( posts.post_date, '%Y-%m-%d')) = YEAR(date_format(CURDATE(), '%Y-%m-%d'))";			
				break;		
				default:
					$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') BETWEEN '{$today}' AND '{$today}'";
			}
			
			
			switch ($order_by) {
				case "order_id":
					$query .= " Order BY posts.ID " .$sort;	
				break;
				case "order_date":
					$query .= " Order BY  date_format( posts.post_date, '%Y-%m-%d') " .$sort;	
					break;
				break;
				case "first_name":
					$query .= "  Order BY  first_name.meta_value " .$sort;	
					break;
				case "last_name":
					$query .= "  Order BY  last_name.meta_value " .$sort;		
					break;
				break;
				 default:
					$query .= " Order BY posts.ID " .$sort;	
			}
			
			if ($type=="ARRAY_A") /*Export*/
			{
			$row = $wpdb->get_results( $query, ARRAY_A );
			}
			if($type=="DEFAULT") /*default*/
			{
			$row = $wpdb->get_results( $query);
			}
			if($type=="COUNT") /*Count only*/	
			{
			$row = $wpdb->get_var($query);		
			}
			//$this->print_data($row);
			
			return $row;	
		 }
		 function get_order_detail($order_id)
		 {
			$order_detail	= get_post_meta($order_id);
			$order_detail_array = array();
			foreach($order_detail as $k => $v)
			{
				//$k =substr($k,1);
				
				$k =ltrim($k,'_');
				$order_detail_array[$k] =$v[0];
			}
			return 	$order_detail_array;
		 }
		 function get_order_item_detail($order_item_id)
		 {
			global $wpdb;
			$sql = "SELECT
					* FROM {$wpdb->prefix}woocommerce_order_itemmeta as woocommerce_order_itemmeta			
					WHERE order_item_id = {$order_item_id}
					";
					
			$results = $wpdb->get_results($sql);
			return $results;			
		 }
		 function print_data($data){
			print "<pre>";
			print_r($data);
			print "</pre>";
		 }
		 function get_columns(){
			$columns = array(					
				 "order_id"				=>  esc_html__("#ID", 'wooreportcog') 
				,"order_date"			=>  esc_html__("Order Date", 'wooreportcog')
				,"billing_first_name"	=>  esc_html__("First Name", 'wooreportcog')  
				,"billing_last_name"	=>  esc_html__("Last Name", 'wooreportcog')   
				,"billing_email"		=> esc_html__("Email", 'wooreportcog')    
				,"order_item_name"		=> esc_html__("Product Name", 'wooreportcog')    
				,"order_status"			=> esc_html__("Status", 'wooreportcog')     
				,"order_currency"		=> esc_html__("Currency", 'wooreportcog')      
				,"billing_country"		=> esc_html__("Country", 'wooreportcog')       
				
				,"payment_method_title" => esc_html__("Payment Method", 'wooreportcog') 
				,"qty"					=> esc_html__("Quantity", 'wooreportcog')  
				,"sales_price"			=> esc_html__("Sales Price", 'wooreportcog')   
				,"line_total"			=> esc_html__("Line Total", 'wooreportcog')   
				,"ni_cost_goods"		=>  esc_html__("Cost Price", 'wooreportcog')    
				,"ni_profit"			=> esc_html__("Profit", 'wooreportcog')     
				//,"line_subtotal"		=>"Subtotal"
				
				//,"line_discount"		=>"Discount"
			  );
			  
			return apply_filters('nicog_profit_report_column', $columns );
		}
		function get_print_content(){
		?>
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Print</title>
           
            <link rel='stylesheet' id='sales-report-style-css'  href='<?php echo  plugins_url( '../assets/css/ni-cog.css', __FILE__ ); ?>' type='text/css' media='all' />
            <style>
            .ni-cog-table { font-size:12px;}
			@media print { 
			   .noprint { 
				  visibility: hidden; 
			   } 
			}
            </style>
            </head>
            
            <body>
             <div class="">
                <?php 
                     $this->get_cog_table();
                ?>
               
              <div class="noprint" style="text-align:right; margin-top:15px"><input type="button" value="Back" onClick="window.history.go(-1)" class="niwoosalesreport_button_form niwoosalesreport_button"> <input type="button" class="niwoosalesreport_button_form niwoosalesreport_button" value="Print this page" onClick="window.print()">	</div>
             </div>
            </body>
            </html>
    
        <?php
		}
	}
}
?>