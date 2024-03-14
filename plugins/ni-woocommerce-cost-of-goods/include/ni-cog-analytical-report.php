<?php
if ( ! defined( 'ABSPATH' ) ) { exit;}
include_once('ni-cog-function.php');  
	if( !class_exists( 'Ni_COG_Analytical_Report' ) ) {
		class Ni_COG_Analytical_Report extends Ni_COG_Function{
			  // Declare the property directly
   			 private $ni_cost_goods;
  			function __construct(){
				 $ni_cog_meta_key = $this->get_cog_setting_by_key("ni_cog_meta_key",'_ni_cost_goods');
				 if(empty( $ni_cog_meta_key)){
					$ni_cog_meta_key = '_ni_cost_goods';	 
				 }
				 $this->ni_cost_goods = $ni_cog_meta_key;
			}
			function page_init(){
			 $input_type="text";
		 	$input_type="hidden";	
			$today = date_i18n("Y-m-d");
			$sales_year =  $this->get_sales_year();
			//$this->print_data($sales_year);
			?>
            <div class="container-fluid">
            	<div id= 'niwoocog'>
                	<div class="row">
                    <div class="col">
                        <div class="card " style="max-width:50%">
                            <div class="card-header bd-indigo-400">
                                <?php esc_html_e("Current Year vs Previous Year Profit", 'wooreportcog'); ?>
                            </div>
                            <div class="card-body">
                                 <form name="frm_cog_report" id="frm_cog_report">
                                    <div class="row">
                                        <div class="col-3">
                                           <label for="selected_year"><?php esc_html_e('Year', 'wooreportcog'); ?></label>
                                        </div>
                                        <div class="col-3">
                                           <select name="selected_year"  id="selected_year" class="form-control">
                                        		<?php foreach($sales_year  as $key=>$value): ?>
											  <option value="<?php esc_attr_e($value) ?>"><?php esc_html_e($value, 'wooreportcog'); ?></option>
                                              <?php endforeach; ?>
											 
										</select>
                                        </div>
                                        
                                        
                                    </div>
                                    
                                   
                                    <div class="row">
                                    	<div  class="col" style="text-align:right">
                                        <input type="submit" value="<?php  esc_html_e("Search", 'wooreportcog'); ?>" class="btn bd-blue-500  mb-2" />
                                        </div>
                                    </div>
                                    
                                   <input type="<?php echo  $input_type; ?>" name="sub_action" value="ni_cog_analytical_report">
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
			 <div class="container-fluid" style="display:none">
             <div id="niwoocog">
             <div class="row">
					
					<div class="col-md-12"  style="padding:0px;">
						<div class="card" style="max-width:70% ">
							<div class="card-header niwoosr-bg-c-purple">
								<?php esc_html_e('Current Year vs Previous Year Sales', 'nisalesreport'); ?>
							</div>
							<div class="card-body">
								  <form id="frmOrderItem" method="post" >
									<div class="form-group row">
									<div class="col-sm-2">
										<label for="selected_year"><?php esc_html_e('Year', 'nisalesreport'); ?></label>
									</div>
									<div class="col-sm-4">
										<select name="selected_year"  id="selected_year" class="form-control">
                                        		<?php foreach($sales_year  as $key=>$value): ?>
											  <option value="<?php esc_attr_e($value) ?>"><?php esc_html_e($value, 'nisalesreport'); ?></option>
                                              <?php endforeach; ?>
											 
										</select>
									</div>
								</div>
									
								<div class="form-group row">
									<div class="col-sm-12 text-right">
										<input type="submit" class="niwoosalesreport_button_form niwoosalesreport_button" value="Search">
									</div>
									
									
								</div>
									
									 <input type="hidden"  name="action" id="action" value="sales_order"/>
									 <input type="hidden"  name="ajax_function" id="ajax_function" value="order_item"/>
									<input type="hidden" name="page" id="page" value="<?php echo isset($_REQUEST["page"])?$_REQUEST["page"]:''; ?>" />		
								</form>
						
							</div>
						</div>
					</div>
					
	
				</div>
             </div>
			 
			 <div class="row" >
					<div class="col-md-12"  style="padding:0px;">
						<div class="card">
						  
						  <div class="card-body "> 
							<div class="row">
								<div class="table-responsive niwoosr-table">
									<div class="ajax_content"></div>
								</div>
							   
							</div>
							</div>
						  
						</div>       	
					</div>
				</div> 
				 
		 </div>
			<?php	
			}
			function page_ajax(){
				$this->get_tables();
			}
			function get_query(){
				//$current_year = 2022;
			    //$previous_year = $current_year -1;
				
				$current_year		 = isset ($_REQUEST["selected_year"])?$_REQUEST["selected_year"]: date_i18n("Y") ;
				$previous_year = $current_year -1;
				
				global $wpdb;	
				$query ="";
				$query .= " SELECT ";
				$query .= " date_format( posts.post_date, '%Y-%m') as order_date";
				$query .= "	,SUM(ROUND(line_total.meta_value,2)-(ROUND(ni_cost_goods.meta_value,2)*(qty.meta_value))) as  order_total";
				
				
				$query .= " FROM {$wpdb->prefix}posts as posts	";
				
				
				
		$query .= "  LEFT JOIN  {$wpdb->prefix}woocommerce_order_items as order_items ON order_items.order_id=posts.ID ";
				
				$query .= "  LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as line_total ON line_total.order_item_id=order_items.order_item_id ";
			
				$query .= "  LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as qty ON qty.order_item_id=order_items.order_item_id ";
			
				$query .= " LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as ni_cost_goods ON ni_cost_goods.order_item_id=order_items.order_item_id ";
			
				
				$query .= "  WHERE 1 = 1";  
				$query .= " AND	posts.post_type ='shop_order' ";
				
				
				$query .= "	AND line_total.meta_key ='_line_total' ";
				$query .= "	AND qty.meta_key ='_qty' ";
				
				$query .= " AND order_items.order_item_type ='line_item'";
				$query .= " AND ni_cost_goods.meta_key='".$this->ni_cost_goods ."' ";
				
				$query .= " AND date_format( posts.post_date, '%Y') BETWEEN  '{$previous_year}' AND  '{$current_year}'";
				
				$query .= "  GROUP By  date_format( posts.post_date, '%Y-%m') ";
					
					
				$rows = $wpdb->get_results( $query);
			
				return $rows;	
			}
			function get_month_name(){
				$month_name = array(); 
				$month_name["01"] = esc_html__("January","nisalesreport");
				$month_name["02"] = esc_html__("February","nisalesreport");
				$month_name["03"] = esc_html__("March","nisalesreport");
				$month_name["04"] = esc_html__("April","nisalesreport");
				$month_name["05"] = esc_html__("May","nisalesreport");
				$month_name["06"] = esc_html__("June","nisalesreport");
				$month_name["07"] = esc_html__("July","nisalesreport");
				$month_name["08"] = esc_html__("August","nisalesreport");
				$month_name["09"] = esc_html__("September","nisalesreport");
				$month_name["10"] = esc_html__("October","nisalesreport");
				$month_name["11"] = esc_html__("November","nisalesreport");
				$month_name["12"] = esc_html__("December","nisalesreport");
				
				return $month_name;
	
			}
			function get_columns(){
				$current_year		 =   isset ($_REQUEST["selected_year"])?$_REQUEST["selected_year"]: date_i18n("Y") ;//$this->get_request("selected_year",date_i18n("Y"));
				$previous_year = $current_year -1;
				
				$column["month"] = esc_html__("Month","nisalesreport");
				$column[$current_year] =$current_year;
				$column[$previous_year] =$previous_year;
				
				return $column;
			}
			function get_tables(){
				$columns 		= $this->get_columns();
				$rows 			= $this->get_query();
				$month_name 	= $this->get_month_name();
				
				$rows_month_year = array();
				
				foreach($rows as $key=>$value){
					$rows_month_year[$value->order_date] = $value;
				}
				
				//echo $rows_month_year["2021-01"]->order_total;
				
				//$this->print_data($rows);
				//$this->print_data($rows_month_year);
				//$this->print_data($month_name);
				//$this->print_data($columns);
				
				
				?>
                 <table class="table table-bordered table-striped table-hover">
               		 <thead class="bd-indigo-400">
                    	<tr>
                        	<?php foreach($columns  as $col_key=>$col_value):?>
                            	<th><?php echo $col_value; ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
               		<tbody>
					<?php foreach($month_name  as $row_key=>$row_value):?>
                    	<tr>
                        		<?php foreach($columns  as $col_key=>$col_value):?>
                                	<?php switch($col_key): case 1: break; ?>
                                
                                		 <?php case "month": ?>
                                         	   <td> <?php echo $row_value; ?>  </td>
                                         <?php break; ?>
                                         <?php default; ?>
                                         		<?php $year_month_key  = $col_key.'-'.$row_key; ?>		
                                            <td> <?php echo wc_price(  isset( $rows_month_year[$year_month_key] )? $rows_month_year[$year_month_key]->order_total:0)  ; ?>  </td>
                                	<?php endswitch; ?>	
                                
                                
                           	    <?php endforeach; ?>
                        </tr>    
                    <?php endforeach; ?>
              
               		</tbody>
                </table>
                <?php
			}
		}
	}
?>