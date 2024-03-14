<?php 
if ( ! defined( 'ABSPATH' ) ) { exit;}
  if( !class_exists( 'Ni_Order_Status_Report' ) ) {
	class Ni_Order_Status_Report {
		public function __construct(){
		}
		function page_init(){
			$order_days = $this->get_order_days();
			$order_status = $this->get_order_status();
			?>
           <div class="container-fluid">
            	<div id="niwoocos">
                	<div class="row">
                    <div class="col">
                        <div class="card " style="max-width:50%">
                            <div class="card-header niwoocos-bg-c-pink-strong">
                                <?php esc_html_e("Order Status Search","niwoocos"); ?>
                            </div> 
                            <div class="card-body">
                                <form id="frm_order_status_report" name="frm_order_status_report">
                                    <div class="row">
                                        <div class="col-3">
                                            <label for="order_days">
                                                <?php  esc_html_e("Select Order Days","niwoocos"); ?>
                                            </label>
                                        </div>
                                        <div class="col-2">
                                            <select id="order_days" name="order_days" class="form-control" >
                                                <?php foreach($order_days as $key=>$value): ?>
                                                    <option value="<?php echo esc_attr($key); ?>">
                                                        <?php  echo esc_html( $value ); ?>
                                                    </option>
                                                    <?php endforeach;?>
                                            </select>
                                        </div>
                                        <div class="col-2">
                                            <label for="order_country">
                                                <?php  esc_html_e("Order Status","niwoocos"); ?>
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <select id="order_status" name="order_status" class="form-control" >
                                                <option value="-1">  <?php  esc_html_e("Select One Order Status","niwoocos"); ?></option>
												<?php foreach($order_status as $key=>$value): ?>                                                	
                                                    <option value="<?php echo esc_attr($key); ?>">
                                                        <?php  echo esc_html( $value ); ?>
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
                                        	<label for="order_days"><?php  esc_html_e("Order By","niwoocos"); ?></label>
                                        </div>
                                        <div class="col-2">
                                        	<select id="order_by" name="order_by">
                                            	<option value="order_status"><?php esc_html_e("Order Status","niwoocos"); ?></option>
                                                <option value="order_total"><?php esc_html_e("Order Total","niwoocos"); ?></option>
                                                <option value="order_count"><?php esc_html_e("Order Count","niwoocos"); ?></option>
                                            </select>
                                        </div>
                                        
                                        <div class="col-2">
                                        	<label for="sort">
                                                <?php  esc_html_e("Sort","niwoocos"); ?>
                                            </label>
                                        </div>
                                        <div class="col-2">
                                        	<select id="sort" name="sort">
                                                <option value="asc"> <?php  esc_html_e("ASC","niwoocos"); ?></option>
                                                <option value="desc"> <?php  esc_html_e("DESC","niwoocos"); ?></option>
                                                
                                            </select>
                                        </div>
                                        <div class="col-2" style="text-align:right">
                                        	<input type="submit" value="<?php  esc_html_e("Search","niwoocos "); ?>" class="btn niwoocos-bg-c-pink-strong mb-2" />
                                        </div> 
                                    </div>
                                    <input type="hidden" name="sub_action" id="sub_action" value="order_status_report" />
                                    <input type="hidden" name="action" id="action" value="niwoocos_ajax" />
                                    <input type="hidden" name="call" id="call" value="get_report" />
                                </form>
                                    
                            </div>
                        </div>
                    </div>
                	</div>
                	
                    
                    
                    <div class="row" >
                        <div class="col-md-12" >
                            <div class="card">
                              <div class="card-header niwoocos-bg-c-pink-strong">
                              <?php esc_html_e('Order Status', 'niwoocos'); ?>
                              </div>
                              <div class="card-body "> 
                                    <div class="_ajax_content"></div>
                                </div>
                             
                            </div>       	
                        </div>
           		 </div>
                    
                  
                    
                    
               		
           		</div>
            </div>
            <?php
		}
		function get_order_days(){
			$order_days = array();
			$order_days["today"] = esc_html__("Today","niwoocos");
			$order_days["yesterday"] = esc_html__("Yesterday","niwoocos");
			$order_days["last_7_days"] = esc_html__("Last 7 Days","niwoocos");
			$order_days["last_15_days"] = esc_html__("Last 15 Days","niwoocos");
			$order_days["last_30_days"] = esc_html__("Last 30 Days","niwoocos");
			$order_days["last_60_days"] = esc_html__("Last 60 Days","niwoocos");
			$order_days["last_90_days"] = esc_html__("Last 90 Days","niwoocos");
			return $order_days;
		}
		function get_order_status(){
			global $wpdb;

			$order_statuses = wc_get_order_statuses();
			$new_data = array();
			foreach($order_statuses as $key=>$value){
				$new_data[$key] =  ucfirst ( str_replace("wc-","",$value));
			}
			

			return $new_data;
		}
		function get_order_status_deprecated(){
			global $wpdb;

		

		$query = "
			SELECT 
			posts.post_status as order_status 
			
			FROM {$wpdb->prefix}posts as posts	";	
			
		
		
		$query .= " WHERE 1=1 ";
		$query .= " AND posts.post_type ='shop_order' ";
		
		$query .= " GROUP BY posts.post_status ";
		
		$query .= " Order BY posts.post_status ASC";	
		
		//$query = $wpdb->prepare($query );
		$rows = $wpdb->get_results( $query);
		
		
		$new_data = array();
		foreach($rows as $key=>$value){
			$new_data[$value->order_status] =  ucfirst ( str_replace("wc-","", $value->order_status));
		}
		
		

		return $new_data;
	}
	// Define a function to sort the array by the selected order by and sort options.
	function sort_array($array, $order_by, $sort) {
		usort($array, function($a, $b) use ($order_by, $sort) {
			if ($order_by === 'order_status') {
				// If sorting by order_status, sort alphabetically
				return $sort === 'asc' ? strcasecmp($a[$order_by], $b[$order_by]) : strcasecmp($b[$order_by], $a[$order_by]);
			} else {
				// If sorting by order_total or order_count, sort based on the selected property
				return $sort === 'asc' ? $a[$order_by] - $b[$order_by] : $b[$order_by] - $a[$order_by];
			}
		});
	
		return $array;
	}
	
	function get_query(){

		$initial_date = date('Y-m-d');
		$final_date = date('Y-m-d');
		$selected_order_days = sanitize_text_field(isset($_REQUEST["order_days"])?$_REQUEST["order_days"]:'today');
		$order_status = sanitize_text_field(isset($_REQUEST["order_status"])?$_REQUEST["order_status"]:'-1');
		
	    $order_by = sanitize_text_field(isset($_REQUEST["order_by"])?$_REQUEST["order_by"]:'total');
	
		$sort = sanitize_text_field(isset($_REQUEST["sort"])?$_REQUEST["sort"]:'asc');
	

		switch ($selected_order_days) {
			case 'today':
			  $initial_date = date('Y-m-d');
			  $final_date = date('Y-m-d');
			  break;
			case 'yesterday':
			  $initial_date = date('Y-m-d', strtotime('-1 day'));
			  $final_date = date('Y-m-d', strtotime('-1 day'));
			  break;
			case 'last_7_days':
			  $initial_date = date('Y-m-d', strtotime('-7 days'));
			  $final_date = date('Y-m-d');
			  break;
			case 'last_15_days':
			  $initial_date = date('Y-m-d', strtotime('-15 days'));
			  $final_date = date('Y-m-d');
			  break;
			case 'last_30_days':
			  $initial_date = date('Y-m-d', strtotime('-30 days'));
			  $final_date = date('Y-m-d');
			  break;
			case 'last_60_days':
			  $initial_date = date('Y-m-d', strtotime('-60 days'));
			  $final_date = date('Y-m-d');
			  break;
			case 'last_90_days':
			  $initial_date = date('Y-m-d', strtotime('-90 days'));
			  $final_date = date('Y-m-d');
			  break;
			default:
			  // Default to today's date
			  $initial_date = date('Y-m-d');
			  $final_date = date('Y-m-d');
			  break;
		  }
		  
		$order_query_args = array(
			'date_created' => $initial_date . '...' . $final_date,
		  );
		  
		if ($order_status !== '-1') {
			$order_query_args['status'] = array($order_status);
		}

	
		$order_query_args['order'] = $sort;
	
		 
	    $order_query = new WC_Order_Query($order_query_args);
		$orders = $order_query->get_orders();
		$order_data = [];


		foreach ($orders as $order) {
			$status = $order->get_status();
		  
			if (!isset($order_data[$status])) {
			  $order_data[$status] = [
				'order_total' => 0,
				'order_count' => 0,
				'order_status' => 'order_status',
			  ];
			}
		  
			$order_data[$status]['order_total'] += $order->get_total();
			$order_data[$status]['order_count'] += 1;
			$order_data[$status]['order_status'] = $status;
			
		}

		
		//$this->print_array($order_data );
		$order_data = $this->sort_array($order_data, $order_by, $sort);

		
		// foreach ($order_data as $data) {
		// 	echo 'Status: ' . $data['order_status'] . ', Total: ' . $data['order_total'] . ', Count: ' . $data['order_count'] . '<br>';
		// }

		


			return $order_data;			
		}
		

		function get_query_deprecated(){
			global $wpdb;
			
			$today = date_i18n("Y-m-d");
			
			$order_days = sanitize_text_field(isset($_REQUEST["order_days"])?$_REQUEST["order_days"]:'today');
			$order_status = sanitize_text_field(isset($_REQUEST["order_status"])?$_REQUEST["order_status"]:'-1');
			
			$order_by = sanitize_text_field(isset($_REQUEST["order_by"])?$_REQUEST["order_by"]:'billing_country');
			$sort = sanitize_text_field(isset($_REQUEST["sort"])?$_REQUEST["sort"]:'asc');
			
			
			$query = "
				SELECT 
				posts.post_status as order_status
				,ROUND(SUM(order_total.meta_value),2) as order_total
				,count(*) as order_count
				FROM {$wpdb->prefix}posts as posts	";	
				
			$query .= "	LEFT JOIN  {$wpdb->prefix}postmeta as order_total ON order_total.post_id=posts.ID ";
	
			
			$query .= " WHERE 1=1 ";
			$query .= " AND posts.post_type ='shop_order' ";
			$query .= " AND order_total.meta_key ='_order_total' ";
		
			
			if ($order_status !=='-1'){
				$query .= ' AND posts.post_status  IN (\'%s\') ';
			}
			
			$prepare = true;
			switch ($order_days) {				
			  case "yesterday":
				$query .= ' AND  date_format( posts.post_date, \'%\Y-%\m-%\d\') = date_format( DATE_SUB(\'%s\', INTERVAL 1 DAY), \'%\Y-%\m-%\d\')';
				break;
			  case "last_7_days":
				$query .= ' AND  date_format( posts.post_date, \'%\Y-%\m-%\d\') BETWEEN date_format(DATE_SUB(CURDATE(), INTERVAL 7 DAY), \'%\Y-%\m-%\d\') AND   \'%s\' ';
				break;
			  case "last_15_days":
					$query .= ' AND  date_format( posts.post_date, \'%\Y-%\m-%\d\') BETWEEN date_format(DATE_SUB(CURDATE(), INTERVAL 15 DAY), \'%\Y-%\m-%\d\') AND   \'%s\' ';
				break;
			  case "last_30_days":
				$query .= ' AND  date_format( posts.post_date, \'%\Y-%\m-%\d\') BETWEEN date_format(DATE_SUB(CURDATE(), INTERVAL 30 DAY), \'%\Y-%\m-%\d\') AND   \'%s\' ';
				break;
			 case "last_60_days":
				$query .= ' AND  date_format( posts.post_date, \'%\Y-%\m-%\d\') BETWEEN date_format(DATE_SUB(CURDATE(), INTERVAL 60 DAY),\'%\Y-%\m-%\d\') AND   \'%s\' ';
				break;
			 case "last_90_days":
				$query .= ' AND  date_format( posts.post_date, \'%\Y-%\m-%\d\') BETWEEN date_format(DATE_SUB(CURDATE(), INTERVAL 90 DAY), \'%\Y-%\m-%\d\') AND   \'%s\' ';
				break;				
			  case "today":
			  default:
					$query .= ' AND   date_format( posts.post_date, \'%\Y-%\m-%\d\') BETWEEN \'%s\' AND \'%s\'';
					break;
			}
			
			
			$query .= " GROUP By posts.post_status " ;
			
			$sort = strtolower($sort);
			switch ($order_by) {
				case "order_status":					
					$query .= ($sort == 'asc') ? ' Order BY  posts.post_status ASC' : ' Order BY posts.post_status DESC';
					break;
				case "order_total":					
					$query .= ($sort == 'asc') ? ' Order BY  order_total ASC' : ' Order BY  order_count DESC';
					break;
				case "order_count":
					$query .= ($sort == 'asc') ? ' Order BY  order_count ASC' : ' Order BY  order_count DESC';
					break;
				default:					
					$query .= ($sort == 'asc') ? ' Order BY  order_total ASC' : ' Order BY  order_count DESC';
					break;
			}
			
			if($prepare){				
					switch ($order_days) {
						case "yesterday":
						case "last_7_days":
						case "last_15_days":
						case "last_30_days":
						case "last_60_days":
						case "last_90_days":
							if ($order_status !=='-1'){
								$query = $wpdb->prepare($query,$order_status,$today);	
							}else{
								$query = $wpdb->prepare($query,$today);	
							}
							break;				
						case "today":
						default:
							if ($order_status !=='-1'){
								$query = $wpdb->prepare($query,$order_status,$today,$today);	
							}else{
								$query = $wpdb->prepare($query,$today,$today);	
							}
							break;
					}
			}
			$rows = $wpdb->get_results($query);
			
			return $rows;			
		}
		

		function print_array($arr){
			echo "<pre>";
			print_r($arr);
			echo "</pre>";
		}
		function get_columns(){
			$columns = array();
			$columns["order_status"] =  esc_html__("Status","niwoocos");
			$columns["order_total"] = 	esc_html__("Order Total ","niwoocos");
			$columns["order_count"] = 	esc_html__("Order Count ","niwoocos");
			return $columns;
		}
		function get_report(){
			$rows = $this->get_query();
			//$this->print_array($rows);
			$columns = $this->get_columns();
			
			?>
            <table class="table table-bordered">
            	<thead>
                	<tr class="bd-indigo-400">
                	<?php foreach($columns as $key=>$value): ?>
                    	
                        <?php switch($key): case 1: break; ?>
                         	<?php case "order_total": ?>
                            <?php case "order_count": ?>
                             <th style="text-align:right"><?php echo esc_html($value); ?></th>
                         	<?php break; ?>
                            <?php default; ?>
                              <th><?php echo esc_html( $value); ?></th>
                        <?php endswitch; ?>  
                    <?php endforeach; ?>
                	</tr>
                </thead>
                <tbody>
                <?php if (count($rows ) ===0): ?>
                <tr>
                	<td colspan="<?php echo esc_attr(intval(count($columns)));?>"><?php esc_html_e("No record found","niwoocos"); ?></td>
                </tr>
                <?php return; ?>
                <?php endif;?>
                
                	<?php foreach($rows as $row_key=>$row_value): ?>
                    <?php $td_class = ""; ?>
                    	<tr>
						<?php foreach($columns  as $col_key=>$col_value): ?>
                            <?php switch($col_key): case 1: break; ?>                            
								<?php case "order_total": ?>
									<?php $td_vale = isset($row_value[$col_key])?$row_value[$col_key]:0; ?>
                                    <td style="text-align:right"><?php echo wc_price( intval($td_vale )); ?></td>
									<?php break; ?> 
                                <?php case "order_count": ?>
									<?php $td_vale = isset($row_value[$col_key])?$row_value[$col_key]:0; ?>
                                    <td style="text-align:right"><?php echo intval($td_vale); ?></td>
									<?php break; ?>                                
                                <?php case "order_status": ?>
									<?php $td_vale = ucfirst ( str_replace("wc-","", isset($row_value["order_status"])?$row_value["order_status"]:"")); ?>
                                	<td style="text-align:left"><?php echo esc_html($td_vale); ?></td>
									<?php break; ?>
                                <?php default; 
									$td_value = isset($row_value->$col_key)?$row_value->$col_key:"";
									?>                                	
                                    <td style="text-align:left"><?php echo esc_textarea($td_value); ?></td>
                                    <?php break; ?>
                            <?php endswitch; ?>                             
                            
                        <?php endforeach; ?>                   
               		 </tr>
					<?php endforeach; ?>
                </tbody>
            </table>
            
            <?php
			
		}
		function ajax_init(){
			$call = sanitize_text_field(isset($_REQUEST['call'])?$_REQUEST['call']:'');
			if ($call =="get_report"){
				$this->get_report();
			}
			die;
		}
	}
  }

?>