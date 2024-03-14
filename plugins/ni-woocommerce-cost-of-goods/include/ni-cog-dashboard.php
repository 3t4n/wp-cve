<?php
if ( ! defined( 'ABSPATH' ) ) { exit;}
if( !class_exists( 'Ni_COG_Dashboard' ) ) { 
include_once("ni-cog-function.php"); 
	class Ni_COG_Dashboard  extends Ni_COG_Function{
		 var $ni_cost_goods ='_ni_cost_goods';
		 public function __construct(){
		 	 $ni_cog_meta_key = $this->get_cog_setting_by_key("ni_cog_meta_key",'_ni_cost_goods');
			 if(empty( $ni_cog_meta_key)){
				$ni_cog_meta_key = '_ni_cost_goods';	 
			 }
			 $this->ni_cost_goods = $ni_cog_meta_key;
			 
		 }
		 /*Not in user*/
		 function test(){
			
		 }
		 function get_stock_value(){
			global $wpdb;
		 	$query = " SELECT ";
		 }
		 function page_init(){
			$top_category = $this->get_top_category_query();
			$top_customer = $this->get_top_customer_query();
			$top_product = $this->get_top_product_query();
			?>
             <div class="container-fluid" id="niwoocog">
             	
                <div class="row" >
             	<div class="col-md-12"  style="padding:0px;">
                	<div class="card">
                      <div class="card-body">
                      <h5 style="text-align:center"> Buy Ni WooCommerce cost of goods Pro @ $34.00  for more reports and exports</h5>
                      <h5> <span class="font-weight-bold" >Coupon Code: <span class="text-warning">ni10</span>  Get 10% OFF</span></h5> 
                    	<span> <span class="font-weight-bold" >Email at:</span><a href="mailto:support@naziinfotech.com" target="_blank">support@naziinfotech.com</a></span><br />
                    	<span> <span class="font-weight-bold" >Website: </span><a href="http://naziinfotech.com/" target="_blank">www.naziinfotech.com</a></span>	<br />	
                        
                        <br />
                                    <br />
                                    <a href="http://demo.naziinfotech.com/?demo_login=woo_cost_of_goods" class="btn bd-blue-500  mb-2" target="_blank">View Demo</a>
                                    <a href="http://naziinfotech.com/product/ni-woocommerce-cost-of-good-pro/" target="_blank" class="btn bd-blue-500  mb-2">Buy Now</a>
             
                      </div>
                    </div>
                </div>
            </div>
            	
                <div class="row" >
             	<div class="col-md-12"  style="padding:0px;">
                	<div class="card">
                      <div class="card-body">
                      <h5> We will develop a <span class="text-success" style="font-size:26px;">New</span> WordPress and WooCommerce <span class="text-success" style="font-size:26px;">plugin</span> and customize or modify  the <span class="text-success" style="font-size:26px;">existing</span> plugin, if yourequire any <span class="text-success" style="font-size:26px;"> customization</span>  in WordPress and WooCommerce then please <span class="text-success" style="font-size:26px;">contact us</span> at: <a href="mailto:support@naziinfotech.com">support@naziinfotech.com</a>.</h5>
                      </div>
                    </div>
                </div>
            </div>
             
             	<div class="row" >
            	<div class="col-md-12">
         			<div class="card">
                      <div class="card-header bd-indigo-400">
                        <?php _e('Sales Analysis',  'wooreportcog'); ?>
                      </div>
                      <div class="card-body"> 
                        <div class="row">
                           <div class="col-xl-3 col-md-6 col-lg-4   box10">
							  <div class="card card-border-top card-border-top-box10  shadow p-3 mb-4 bg-white rounded">
                                  <div class="card-body card-body-padding" >
                                  	<div class="card-block">
									<h6 class="m-b-20" style="font-size: 14px; text-transform: uppercase"><strong> <?php _e('Total Sales',  'wooreportcog'); ?></strong></h6>
									<p class="text-right"><span>&nbsp;</span></p>
									<h3 class="m-b-0"><span class="f-right"><?php  echo wc_price( $this->get_total_sales("ALL")); ?></span></h3>
								</div>
                                  </div>
                                </div>
                            </div>
						   <div class="col-xl-3 col-md-6 col-lg-4   box1">
							  <div class="card card-border-top card-border-top-box1  shadow p-3 mb-5 bg-white rounded">
                                  <div class="card-body card-body-padding">
                                  	<div class="card-block">
									<h6 class="m-b-20" style="font-size: 14px; text-transform: uppercase"><strong><?php _e('This Year Sales',  'wooreportcog'); ?></strong></h6>
									<p class="text-right"><span>&nbsp;</span></p>
									<h3 class="m-b-0"><span class="f-right"><?php  echo wc_price( $this->get_total_sales("YEAR")); ?></span></h3>
								</div>
                                  </div>
                                </div>
                            </div>
						   <div class="col-xl-3 col-md-6 col-lg-4   box4">
							  <div class="card card-border-top card-border-top-box4  shadow p-3 mb-5 bg-white rounded">
                                  <div class="card-body card-body-padding">
                                  	<div class="card-block">
									<h6 class="m-b-20" style="font-size: 14px; text-transform: uppercase"><strong><?php _e('This Month Sales',  'wooreportcog'); ?></strong></h6>
									<p class="text-right"><span>&nbsp;</span></p>
									<h3 class="m-b-0"><span class="f-right"><?php  echo wc_price( $this->get_total_sales("MONTH")); ?></span></h3>
								</div>
                                  </div>
                                </div>
                            </div>
						   <div class="col-xl-3 col-md-6 col-lg-4   box6">
							  <div class="card card-border-top card-border-top-box6  shadow p-3 mb-5 bg-white rounded">
                                  <div class="card-body card-body-padding">
                                  	<div class="card-block">
									<h6 class="m-b-20" style="font-size: 14px; text-transform: uppercase"><strong><?php _e('This Week Sales',  'wooreportcog'); ?></strong></h6>
									<p class="text-right"><span>&nbsp;</span></p>
									<h3 class="m-b-0"><span class="f-right"><?php  echo wc_price( $this->get_total_sales("WEEK")); ?></span></h3>
								</div>
                                  </div>
                                </div>
                            </div>
                            
                            
                           <div class="col-xl-3 col-md-6 col-lg-4   box8">
							  <div class="card card-border-top card-border-top-box8  shadow p-3 mb-5 bg-white rounded">
                                  <div class="card-body card-body-padding">
                                  	<div class="card-block">
									<h6 class="m-b-20" style="font-size: 14px; text-transform: uppercase"><strong> <?php _e('Yesterday Sales',  'wooreportcog'); ?></strong></h6>
									<p class="text-right"><span>&nbsp;</span></p>
									<h3 class="m-b-0"><span class="f-right"><?php  echo wc_price( $this->get_total_sales("YESTERDAY")); ?></span></h3>
								</div>
                                  </div>
                                </div>
                            </div>
                            
                           
                           <div class="col-xl-3 col-md-6 col-lg-4   box9">
							  <div class="card card-border-top card-border-top-box9  shadow p-3 mb-5 bg-white rounded">
                                  <div class="card-body card-body-padding">
                                  	<div class="card-block">
									<h6 class="m-b-20" style="font-size: 14px; text-transform: uppercase"><strong> <?php _e('Today Sales',  'wooreportcog'); ?></strong></h6>
									<p class="text-right"><span>&nbsp;</span></p>
									<h3 class="m-b-0"><span class="f-right"><?php  echo wc_price( $this->get_total_sales("DAY")); ?></span></h3>
								</div>
                                  </div>
                                </div>
                            </div>
                            
                            
                           
                           <div class="col-xl-3 col-md-6 col-lg-4   box10">
							  <div class="card card-border-top card-border-top-box10  shadow p-3 mb-5 bg-white rounded">
                                  <div class="card-body card-body-padding">
                                  	<div class="card-block">
									<h6 class="m-b-20" style="font-size: 14px; text-transform: uppercase"><strong> <?php _e('Total Sales Count',  'wooreportcog'); ?></strong></h6>
									<p class="text-right"><span>&nbsp;</span></p>
									<h3 class="m-b-0"><span class="f-right"><?php echo $this->get_total_sales_count("ALL"); ?></span></h3>
								</div>
                                  </div>
                                </div>
                            </div>
						   <div class="col-xl-3 col-md-6 col-lg-4   box1">
							  <div class="card card-border-top card-border-top-box1  shadow p-3 mb-5 bg-white rounded">
                                  <div class="card-body card-body-padding">
                                  	<div class="card-block">
									<h6 class="m-b-20" style="font-size: 14px; text-transform: uppercase"><strong><?php _e('This Year Sales Count',  'wooreportcog'); ?></strong></h6>
									<p class="text-right"><span>&nbsp;</span></p>
									<h3 class="m-b-0"><span class="f-right"><?php echo $this->get_total_sales_count("YEAR"); ?></span></h3>
								</div>
                                  </div>
                                </div>
                            </div>
						   <div class="col-xl-3 col-md-6 col-lg-4   box4">
							  <div class="card card-border-top card-border-top-box4  shadow p-3 mb-5 bg-white rounded">
                                  <div class="card-body card-body-padding">
                                  	<div class="card-block">
									<h6 class="m-b-20" style="font-size: 14px; text-transform: uppercase"><strong><?php _e('This Month Sales Count',  'wooreportcog'); ?></strong></h6>
									<p class="text-right"><span>&nbsp;</span></p>
									<h3 class="m-b-0"><span class="f-right"><?php echo $this->get_total_sales_count("MONTH"); ?></span></h3>
								</div>
                                  </div>
                                </div>
                            </div>
						   <div class="col-xl-3 col-md-6 col-lg-4   box6">
							  <div class="card card-border-top card-border-top-box6  shadow p-3 mb-5 bg-white rounded">
                                  <div class="card-body card-body-padding">
                                  	<div class="card-block">
									<h6 class="m-b-20" style="font-size: 14px; text-transform: uppercase"><strong><?php _e('This Week Sales Count',  'wooreportcog'); ?></strong></h6>
									<p class="text-right"><span>&nbsp;</span></p>
									<h3 class="m-b-0"><span class="f-right"><?php echo $this->get_total_sales_count("WEEK"); ?></span></h3>
								</div>
                                  </div>
                                </div>
                            </div>
                           <div class="col-xl-3 col-md-6 col-lg-4   box8">
							  <div class="card card-border-top card-border-top-box8  shadow p-3 mb-5 bg-white rounded">
                                  <div class="card-body card-body-padding">
                                  	<div class="card-block">
									<h6 class="m-b-20" style="font-size: 14px; text-transform: uppercase"><strong> <?php _e('Yesterday Sales Count',  'wooreportcog'); ?></strong></h6>
									<p class="text-right"><span>&nbsp;</span></p>
									<h3 class="m-b-0"><span class="f-right"><?php echo $this->get_total_sales_count("YESTERDAY"); ?></span></h3>
								</div>
                                  </div>
                                </div>
                            </div>
                            
                           <div class="col-xl-3 col-md-6 col-lg-4   box8">
							  <div class="card card-border-top card-border-top-box8  shadow p-3 mb-5 bg-white rounded">
                                  <div class="card-body card-body-padding">
                                  	<div class="card-block">
									<h6 class="m-b-20" style="font-size: 14px; text-transform: uppercase"><strong> <?php _e('Today Sales Count',  'wooreportcog'); ?></strong></h6>
									<p class="text-right"><span>&nbsp;</span></p>
									<h3 class="m-b-0"><span class="f-right"><?php echo $this->get_total_sales_count("DAY"); ?></span></h3>
								</div>
                                  </div>
                                </div>
                            </div> 
                            
                            
						</div>
						</div>
                     
                    </div>       	
                </div>
            </div>
            
            	<div class="row" >
            	<div class="col-md-12">
         			<div class="card">
                      <div class="card-header bd-indigo-400">
                        <?php _e('Profit Analysis',  'wooreportcog'); ?>
                      </div>
                      <div class="card-body"> 
                        <div class="row">
                           <div class="col-xl-3 col-md-6 col-lg-4   box10">
							  <div class="card card-border-top card-border-top-box10  shadow p-3 mb-4 bg-white rounded">
                                  <div class="card-body card-body-padding" >
                                  	<div class="card-block">
									<h6 class="m-b-20" style="font-size: 14px; text-transform: uppercase"><strong> <?php _e('Total Profit',  'wooreportcog'); ?></strong></h6>
									<p class="text-right"><span>&nbsp;</span></p>
									<h3 class="m-b-0"><span class="f-right"><?php  echo wc_price( $this->get_profit_summary("ALL")); ?></span></h3>
								</div>
                                  </div>
                                </div>
                            </div>
						   <div class="col-xl-3 col-md-6 col-lg-4   box1">
							  <div class="card card-border-top card-border-top-box1  shadow p-3 mb-5 bg-white rounded">
                                  <div class="card-body card-body-padding">
                                  	<div class="card-block">
									<h6 class="m-b-20" style="font-size: 14px; text-transform: uppercase"><strong><?php _e('Last Year Profit',  'wooreportcog'); ?></strong></h6>
									<p class="text-right"><span>&nbsp;</span></p>
									<h3 class="m-b-0"><span class="f-right"><?php  echo wc_price( $this->get_profit_summary("LAST_YEAR")); ?></span></h3>
								</div>
                                  </div>
                                </div>
                            </div>
						   <div class="col-xl-3 col-md-6 col-lg-4   box4">
							  <div class="card card-border-top card-border-top-box4  shadow p-3 mb-5 bg-white rounded">
                                  <div class="card-body card-body-padding">
                                  	<div class="card-block">
									<h6 class="m-b-20" style="font-size: 14px; text-transform: uppercase"><strong><?php _e('This Year Profit',  'wooreportcog'); ?></strong></h6>
									<p class="text-right"><span>&nbsp;</span></p>
									<h3 class="m-b-0"><span class="f-right"><?php  echo wc_price( $this->get_profit_summary("YEAR")); ?></span></h3>
								</div>
                                  </div>
                                </div>
                            </div>
						   <div class="col-xl-3 col-md-6 col-lg-4   box6">
							  <div class="card card-border-top card-border-top-box6  shadow p-3 mb-5 bg-white rounded">
                                  <div class="card-body card-body-padding">
                                  	<div class="card-block">
									<h6 class="m-b-20" style="font-size: 14px; text-transform: uppercase"><strong><?php _e('This Month Profit',  'wooreportcog'); ?></strong></h6>
									<p class="text-right"><span>&nbsp;</span></p>
									<h3 class="m-b-0"><span class="f-right"><?php  echo wc_price( $this->get_profit_summary("MONTH")); ?></span></h3>
								</div>
                                  </div>
                                </div>
                            </div>
                           <div class="col-xl-3 col-md-6 col-lg-4   box8">
							  <div class="card card-border-top card-border-top-box8  shadow p-3 mb-5 bg-white rounded">
                                  <div class="card-body card-body-padding">
                                  	<div class="card-block">
									<h6 class="m-b-20" style="font-size: 14px; text-transform: uppercase"><strong> <?php _e('This Week Profit',  'wooreportcog'); ?></strong></h6>
									<p class="text-right"><span>&nbsp;</span></p>
									<h3 class="m-b-0"><span class="f-right"><?php  echo wc_price( $this->get_profit_summary("WEEK")); ?></span></h3>
								</div>
                                  </div>
                                </div>
                            </div>
                           
                           <div class="col-xl-3 col-md-6 col-lg-4   box10">
							  <div class="card card-border-top card-border-top-box10  shadow p-3 mb-5 bg-white rounded">
                                  <div class="card-body card-body-padding">
                                  	<div class="card-block">
									<h6 class="m-b-20" style="font-size: 14px; text-transform: uppercase"><strong> <?php _e('YESTERDAY Profit',  'wooreportcog'); ?></strong></h6>
									<p class="text-right"><span>&nbsp;</span></p>
									<h3 class="m-b-0"><span class="f-right"><?php echo   wc_price(  $this->get_profit_summary("YESTERDAY")); ?></span></h3>
								</div>
                                  </div>
                                </div>
                            </div> 
                           
                           <div class="col-xl-3 col-md-6 col-lg-4   box10">
							  <div class="card card-border-top card-border-top-box10  shadow p-3 mb-5 bg-white rounded">
                                  <div class="card-body card-body-padding">
                                  	<div class="card-block">
									<h6 class="m-b-20" style="font-size: 14px; text-transform: uppercase"><strong> <?php _e('Today Profit',  'wooreportcog'); ?></strong></h6>
									<p class="text-right"><span>&nbsp;</span></p>
									<h3 class="m-b-0"><span class="f-right"><?php echo  wc_price( $this->get_profit_summary("DAY")); ?></span></h3>
								</div>
                                  </div>
                                </div>
                            </div>
							</div>
						</div>
                     
                    </div>       	
                </div>
            </div>
            
           	<div class="row" >
            	<div class="col-md-6">
         			<div class="card">
                      <div class="card-header bd-indigo-400">
                        <?php _e('Top 5 Profit Product',  'wooreportcog'); ?>
                      </div>
                      <div class="card-body"> 
                        <table class="table table-bordered table-striped table-hover">
                        	<thead class="fw-bolder">
                            	<tr>
                                	<td><?php esc_html_e('Product Name',  'wooreportcog'); ?></td>
                                    <td><?php esc_html_e('Quantity',  'wooreportcog'); ?></td>
                                    <td class="text-right"><?php esc_html_e('Line Total',  'wooreportcog'); ?></td>
                                    <td><?php esc_html_e('Product Profit',  'wooreportcog'); ?></td>
                                </tr>
                            </thead>
                            <tbody>
                        
						<?php foreach($top_product as $key=>$value): ?>
                        	<tr>
                                	<td><?php echo $value->order_item_name; ?></td>
                                    <td><?php echo $value->qty; ?></td>
                                    <td class="text-right"><?php echo wc_price($value->line_total) ; ?></td>
                                    <td><?php  echo wc_price($value->product_profit) ; ?></td>
                                </tr>
                        <?php endforeach;?>
                        </tbody>
                        </table>
					   </div>
                     
                    </div>       	
                </div>
                <div class="col-md-6">
         			<div class="card">
                      <div class="card-header bd-indigo-400">
                        <?php _e('Top 5 Profit Customer',  'wooreportcog'); ?>
                      </div>
                      <div class="card-body"> 
                        <table class="table table-bordered table-striped table-hover">
                        	<thead class="fw-bolder">
                            	<tr>
                                	<td><?php esc_html_e('First Name',  'wooreportcog'); ?></td>
                                    <td><?php esc_html_e('Last Name',  'wooreportcog'); ?></td>
                                    <td ><?php esc_html_e('Email',  'wooreportcog'); ?></td>
                                    <td><?php esc_html_e('Sold Quantity',  'wooreportcog'); ?></td>
                                    
                                     <td><?php esc_html_e('Cost',  'wooreportcog'); ?></td>
                                    
                                    <td><?php esc_html_e('Line Total',  'wooreportcog'); ?></td>
                                     <td><?php esc_html_e('Profit',  'wooreportcog'); ?></td>
                                </tr>
                            </thead>
                            <tbody>
                        
						<?php foreach($top_customer as $key=>$value): ?>
                        	<tr>
                                	<td><?php echo $value->billing_first_name; ?></td>
                                    <td><?php echo $value->billing_last_name; ?></td>
                                    <td><?php echo $value->billing_email ; ?></td>
                                    <td><?php  echo $value->qty; ?></td>
                                    
                                     <td><?php  echo $value->total_cost; ?></td>
                                    
                                    <td><?php  echo wc_price($value->line_total); ?></td>
                                    <td><?php  echo wc_price($value->line_profit); ?></td>
                                </tr>
                        <?php endforeach;?>
                        </tbody>
                        </table>
					   </div>
                     
                    </div>       	
                </div>
            </div>
            
            
            <div class="row" >
            	<div class="col-md-6">
         			<div class="card">
                      <div class="card-header bd-indigo-400">
                        <?php _e('Top 5 Profit Category',  'wooreportcog'); ?>
                      </div>
                      <div class="card-body"> 
                        <table class="table table-bordered table-striped table-hover">
                        	<thead class="fw-bolder">
                            	<tr>
                                	<td><?php esc_html_e('Category Name',  'wooreportcog'); ?></td>
                                    <td style="text-align:right"><?php esc_html_e('Quantity',  'wooreportcog'); ?></td>
                                    <td class="text-right" style="text-align:right"><?php esc_html_e('Line Total',  'wooreportcog'); ?></td>
                                    <td class="text-right" style="text-align:right"><?php esc_html_e('Category Profit',  'wooreportcog'); ?></td>
                                </tr>
                            </thead>
                            <tbody>
                        
						<?php foreach($top_category as $key=>$value): ?>
                        	<tr>
                                	<td><?php echo $value->category_name; ?></td>
                                    <td style="text-align:right"><?php echo $value->qty; ?></td>
                                    <td class="text-right" style="text-align:right"><?php echo wc_price($value->line_total) ; ?></td>
                                    <td class="text-right" style="text-align:right"><?php  echo wc_price($value->category_profit) ; ?></td>
                                </tr>
                        <?php endforeach;?>
                        </tbody>
                        </table>
					   </div>
                     
                    </div>       	
                </div>
                
            </div>
            
                
             </div>
            <?php	 
		 }
		 function get_top_customer_query(){
			 global $wpdb;
			 $cog_query = "";
			$cog_query = "SELECT  ";
			$cog_query .= " billing_email.meta_value as   billing_email ";
			
			$cog_query .= ", billing_first_name.meta_value as   billing_first_name ";
			
			$cog_query .= ", billing_last_name.meta_value as   billing_last_name ";
			
			
			$cog_query .= ", ROUND(SUM(wooreport_cog.meta_value),2) as   total_cost ";
			$cog_query .= ", billing_email.meta_value as   billing_email ";
			$cog_query .= ", SUM(quantity.meta_value)									as qty ";
			$cog_query .= ", ROUND(SUM(line_total.meta_value),2)  as line_total ";
			$cog_query .= ",ROUND( SUM(line_total.meta_value) - SUM(wooreport_cog.meta_value*quantity.meta_value),2)	as line_profit";
			
			$cog_query .= " FROM {$wpdb->prefix}posts as posts ";
			
			//$cog_query .= " LEFT JOIN {$wpdb->prefix}postmeta as order_total ON order_total.post_id = posts.ID ";
			
			$cog_query .= " LEFT JOIN {$wpdb->prefix}postmeta as billing_email ON billing_email.post_id = posts.ID ";
			
			$cog_query .= "LEFT JOIN  {$wpdb->prefix}woocommerce_order_items as woocommerce_order_items ON woocommerce_order_items.order_id=posts.ID ";
			
			$cog_query .= " LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as wooreport_cog ON wooreport_cog.order_item_id=woocommerce_order_items.order_item_id ";
			
			$cog_query .= "	LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as quantity ON quantity.order_item_id=woocommerce_order_items.order_item_id ";
			$cog_query .= " LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as line_total ON line_total.order_item_id=woocommerce_order_items.order_item_id ";
			$cog_query .= " LEFT JOIN {$wpdb->prefix}postmeta as billing_first_name ON billing_first_name.post_id = posts.ID ";
			$cog_query .= " LEFT JOIN {$wpdb->prefix}postmeta as billing_last_name ON billing_last_name.post_id = posts.ID ";
		
			
			$cog_query .= "	WHERE 1 = 1 ";
			$cog_query .= " AND posts.post_status NOT IN ('auto-draft','inherit')";
			$cog_query .= " AND posts.post_type ='shop_order'";
			
			$cog_query .= " AND billing_email.meta_key = '_billing_email'";	  
			$cog_query .= " AND billing_first_name.meta_key = '_billing_first_name'";	
			$cog_query .= " AND billing_last_name.meta_key = '_billing_last_name'";	 
			
			$cog_query .= " AND woocommerce_order_items.order_item_type ='line_item' ";
			$cog_query .= " AND wooreport_cog.meta_key='".$this->ni_cost_goods  ."'  ";
			
			$cog_query .= " AND line_total.meta_key='_line_total'";
			$cog_query .= " AND wooreport_cog.meta_value > 0";
			
			$cog_query .= " AND quantity.meta_key='_qty' ";
			
			$cog_query .= " GROUP BY  billing_email.meta_value  ";
			$cog_query .= " ORDER BY billing_first_name asc, billing_last_name asc ";
			$cog_query .= " LIMIT 5";
				
			$rows = $wpdb->get_results( $cog_query);	
			
			return $rows ;
		 }
		 function get_top_product_query(){
			global $wpdb;	
			
			$query = " SELECT ";
 			$query .= "		order_items.order_item_name";
			$query .= "		,product_id.meta_value as  product_id";
			$query .= "		,variation_id.meta_value as  variation_id";
			$query .= "		,SUM(line_total.meta_value) as  line_total";
			
			$query .= "		,SUM(ROUND(line_total.meta_value,2)-(ROUND(ni_cost_goods.meta_value,2)*(qty.meta_value))) as  product_profit";
			
			$query .= "		,SUM(qty.meta_value) as  qty";
			
			$query .= "		FROM {$wpdb->prefix}posts as posts	";		
				
			$query .= "  LEFT JOIN  {$wpdb->prefix}woocommerce_order_items as order_items ON order_items.order_id=posts.ID ";
			
			$query .= "  LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as product_id ON product_id.order_item_id=order_items.order_item_id ";
			$query .= "  LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as variation_id ON variation_id.order_item_id=order_items.order_item_id ";
			
			$query .= "  LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as line_total ON line_total.order_item_id=order_items.order_item_id ";
			
			$query .= "  LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as qty ON qty.order_item_id=order_items.order_item_id ";
			
			$query .= " LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as ni_cost_goods ON ni_cost_goods.order_item_id=order_items.order_item_id ";
				
				
				$query .= "  WHERE 1 = 1";  
				$query .= " AND	posts.post_type ='shop_order' ";
				$query .= "	AND order_items.order_item_type ='line_item' ";
				
				$query .= "	AND product_id.meta_key ='_product_id' ";
				$query .= "	AND variation_id.meta_key ='_variation_id' ";
				$query .= "	AND line_total.meta_key ='_line_total' ";
				$query .= "	AND qty.meta_key ='_qty' ";
				
				//$query .= " AND ni_cost_goods.meta_key ='_ni_cost_goods'";
				
				$query .= " AND ni_cost_goods.meta_key ='".$this->ni_cost_goods ."' ";
				
				$query .= " AND ni_cost_goods.meta_value>0";
				
				$query .= " GROUP BY 	product_id.meta_value, variation_id.meta_value	 ";
				
				$query .= " order by product_profit DESC ";	
				$query .= " LIMIT 5";
				
				$results = $wpdb->get_results( $query);	
				
				
				return $results;
		 }
		 function get_top_category_query(){
			 global $wpdb;	
			
			$query = " SELECT ";
			$query .= "		terms.name as category_name";
			$query .= "		,SUM(line_total.meta_value) as  line_total";
			$query .= "		,SUM(ROUND(line_total.meta_value,2)-(ROUND(ni_cost_goods.meta_value,2)*(qty.meta_value))) as category_profit";
			$query .= "		,SUM(qty.meta_value) as  qty";
			
			
			$query .= "		FROM {$wpdb->prefix}posts as posts	";
			
			$query .= "  LEFT JOIN  {$wpdb->prefix}woocommerce_order_items as order_items ON order_items.order_id=posts.ID ";
			
			$query .= "  LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as product_id ON product_id.order_item_id=order_items.order_item_id ";
			
			
			$query .= "  LEFT JOIN  {$wpdb->prefix}term_relationships as term_relationships ON term_relationships.object_id=product_id.meta_value  ";
			$query .= "  LEFT JOIN  {$wpdb->prefix}term_taxonomy as term_taxonomy ON term_taxonomy.term_taxonomy_id=term_relationships.term_taxonomy_id ";
			$query .= "  LEFT JOIN  {$wpdb->prefix}terms as terms ON terms.term_id=term_taxonomy.term_id ";
			
			
			
			$query .= "  LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as line_total ON line_total.order_item_id=order_items.order_item_id ";
			$query .= "  LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as qty ON qty.order_item_id=order_items.order_item_id ";
			$query .= " LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as ni_cost_goods ON ni_cost_goods.order_item_id=order_items.order_item_id ";
			
			
			$query .= "  WHERE 1 = 1";  
			$query .= " AND	posts.post_type ='shop_order' ";
			$query .= "	AND order_items.order_item_type ='line_item' ";		
			$query .= "	AND product_id.meta_key ='_product_id' ";
			
			$query .= "	AND term_taxonomy.taxonomy ='product_cat' ";
			
			
			$query .= "	AND line_total.meta_key ='_line_total' ";
			$query .= "	AND qty.meta_key ='_qty' ";
			$query .= " AND ni_cost_goods.meta_key ='".$this->ni_cost_goods ."' ";
			$query .= " AND ni_cost_goods.meta_value>0";
			
			
			$query .= " GROUP BY 	terms.term_id	 ";
			
			$query .= " Order BY 	line_total DESC, qty DESC	 ";
			$query .= " LIMIT 5";
			
			
			$rows = $wpdb->get_results( $query);	
			
			//$this->prettyPrint($rows);
			
			return $rows;
			
		 }
		 function get_total_sales($period="CUSTOM",$start_date=NULL,$end_date=NULL){
			global $wpdb;	
			$today_date = date_i18n("Y-m-d");	
			$query = "SELECT
					SUM(order_total.meta_value)as 'total_sales'
					FROM {$wpdb->prefix}posts as posts			
					LEFT JOIN  {$wpdb->prefix}postmeta as order_total ON order_total.post_id=posts.ID 
					
					WHERE 1=1
					AND posts.post_type ='shop_order' 
					AND order_total.meta_key='_order_total' ";
					
			$query .= " AND posts.post_status IN ('wc-pending','wc-processing','wc-on-hold', 'wc-completed')";
			
			
			if ($period =="YESTERDAY"){
					$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') = DATE_SUB('$today_date', INTERVAL 1 DAY) "; 
			}
			if ($period =="DAY"){		
				$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') = '{$today_date}' "; 
				$query .= " GROUP BY  date_format( posts.post_date, '%Y-%m-%d') ";
			
			
			}
			if ($period =="WEEK"){		
				//$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') > DATE_SUB(date_format(NOW(), '%Y-%m-%d'), INTERVAL 1 WEEK) "; 
				$query .= "  AND  YEAR(date_format( posts.post_date, '%Y-%m-%d')) = YEAR(CURRENT_DATE()) AND 
      WEEK(date_format( posts.post_date, '%Y-%m-%d')) = WEEK(CURRENT_DATE()) ";
			}
			if ($period =="MONTH"){		
				//$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') > DATE_SUB(date_format(NOW(), '%Y-%m-%d'), INTERVAL 1 MONTH) "; 
				$query .= "  AND  YEAR(date_format( posts.post_date, '%Y-%m-%d')) = YEAR(CURRENT_DATE()) AND 
      MONTH(date_format( posts.post_date, '%Y-%m-%d')) = MONTH(CURRENT_DATE()) ";
			}
			if ($period =="YEAR"){		
				//$query .= " AND YEAR(date_format( posts.post_date, '%Y-%m-%d')) = YEAR(date_format(NOW(), '%Y-%m-%d')) "; 
				$query .= " AND YEAR(date_format( posts.post_date, '%Y-%m-%d')) = YEAR(date_format(NOW(), '%Y-%m-%d')) "; 
			}
			$query .= " AND  posts.post_status NOT IN ('trash')";
			
			
			
					
			$results = $wpdb->get_var($query);
			$results = isset($results) ? $results : "0";
			return $results;
		}
		function get_total_sales_count($period="CUSTOM",$start_date=NULL,$end_date=NULL){
			global $wpdb;	
				$today_date = date_i18n("Y-m-d");	
			$query = "SELECT
					count(order_total.meta_value)as 'sales_count'
					FROM {$wpdb->prefix}posts as posts			
					LEFT JOIN  {$wpdb->prefix}postmeta as order_total ON order_total.post_id=posts.ID 
					
					WHERE  1 = 1
					AND posts.post_type ='shop_order' 
					AND order_total.meta_key='_order_total' ";
				
			$query .= " AND posts.post_status IN ('wc-pending','wc-processing','wc-on-hold', 'wc-completed') ";
			
		 if ($period =="YESTERDAY"){
					$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') = DATE_SUB('$today_date', INTERVAL 1 DAY) "; 
			}
			if ($period =="DAY"){		
				$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') = '{$today_date}' "; 
				$query .= " GROUP BY  date_format( posts.post_date, '%Y-%m-%d') ";
			
			
			}
			if ($period =="WEEK"){		
				
				$query .= "  AND  YEAR(date_format( posts.post_date, '%Y-%m-%d')) = YEAR(CURRENT_DATE()) AND 
      WEEK(date_format( posts.post_date, '%Y-%m-%d')) = WEEK(CURRENT_DATE()) ";
			}
			if ($period =="MONTH"){		
			
				$query .= "  AND  YEAR(date_format( posts.post_date, '%Y-%m-%d')) = YEAR(CURRENT_DATE()) AND 
      MONTH(date_format( posts.post_date, '%Y-%m-%d')) = MONTH(CURRENT_DATE()) ";
			}
			if ($period =="YEAR"){		
				
				$query .= " AND YEAR(date_format( posts.post_date, '%Y-%m-%d')) = YEAR(date_format(NOW(), '%Y-%m-%d')) "; 
			}
			$query .= " AND  posts.post_status NOT IN ('trash')";
			
			
			//echo $query;
			$results = $wpdb->get_var($query);	
			$results = isset($results) ? $results : "0";	
			return $results;
		}
		function get_profit_summary($period="CUSTOM",$start_date=NULL,$end_date=NULL){
			
				$today_date = date_i18n("Y-m-d");	
				
			global $wpdb;
			$query = "";
			$query = "SELECT
			posts.ID as order_id
			,posts.post_status as order_status
			,woocommerce_order_items.order_item_id as order_item_id
			, date_format( posts.post_date, '%Y-%m-%d') as order_date 
			,woocommerce_order_items.order_item_name
			, ni_cost_goods.meta_value as ni_cost_goods
			, line_total.meta_value as line_total
			, qty.meta_value as qty
			FROM {$wpdb->prefix}posts as posts			
			LEFT JOIN  {$wpdb->prefix}woocommerce_order_items as woocommerce_order_items ON woocommerce_order_items.order_id=posts.ID  ";
			
			$query .= "	LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as ni_cost_goods ON ni_cost_goods.order_item_id=woocommerce_order_items.order_item_id  ";
			
			$query .= "	LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as line_total ON 	line_total.order_item_id=woocommerce_order_items.order_item_id  ";
			$query .= "	LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as qty ON 	qty.order_item_id=woocommerce_order_items.order_item_id  ";
			
			$query .= " WHERE 1=1
			AND posts.post_type ='shop_order' 
			AND woocommerce_order_items.order_item_type ='line_item'
			AND posts.post_status IN ('wc-pending','wc-processing','wc-on-hold', 'wc-completed') ";
			$query .= " AND ni_cost_goods.meta_key='".$this->ni_cost_goods ."' ";
			$query .= " AND line_total.meta_key='_line_total' 
			AND qty.meta_key='_qty' 
			
			";
			
			
			 if ($period =="YESTERDAY"){
					$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') = DATE_SUB('$today_date', INTERVAL 1 DAY) "; 
			}
			if ($period =="DAY"){		
				$query .= " AND   date_format( posts.post_date, '%Y-%m-%d') = '{$today_date}' "; 
				$query .= " GROUP BY  date_format( posts.post_date, '%Y-%m-%d') ";
			
			
			}
			if ($period =="WEEK"){		
				
				$query .= "  AND  YEAR(date_format( posts.post_date, '%Y-%m-%d')) = YEAR(CURRENT_DATE()) AND 
      WEEK(date_format( posts.post_date, '%Y-%m-%d')) = WEEK(CURRENT_DATE()) ";
			}
			if ($period =="MONTH"){		
			
				$query .= "  AND  YEAR(date_format( posts.post_date, '%Y-%m-%d')) = YEAR(CURRENT_DATE()) AND 
      MONTH(date_format( posts.post_date, '%Y-%m-%d')) = MONTH(CURRENT_DATE()) ";
			}
			if ($period =="YEAR"){		
				
				$query .= " AND YEAR(date_format( posts.post_date, '%Y-%m-%d')) = YEAR(date_format(NOW(), '%Y-%m-%d')) "; 
			}
			
			
			
			if ($period =="LAST_YEAR"){		
				//$query .= " AND YEAR(date_format( posts.post_date, '%Y-%m-%d')) = YEAR(date_format(NOW(), '%Y-%m-%d')) "; 
				$query .= " AND YEAR(date_format( posts.post_date, '%Y-%m-%d')) = YEAR(DATE_SUB(date_format(CURDATE(), '%Y-%m-%d'), INTERVAL 1 YEAR)) "; 
			}
			
			$query .= " AND  posts.post_status NOT IN ('trash')";
			
			
			$row = $wpdb->get_results( $query);
			$total_profit = 0;
			$sales_price = 0;
			$qty= 0;
			$line_total= 0;
			$cost_goods= 0;
			$cost_total= 0;
			
			foreach($row as $key=>$value){
				$qty= 0;
				$line_total  = 0;
				$cost_good =0;
				
				$qty = isset($value->qty)?$value->qty:0;
				$line_total = isset($value->line_total)?$value->line_total:0;
			
				$cost_goods = isset($value->ni_cost_goods)?$value->ni_cost_goods:0;
				
				$cost_goods = is_numeric($cost_goods) ?$cost_goods:0;
				$qty = is_numeric($qty) ?$qty:0;
				
				$cost_total = ($cost_goods * $qty  ); /*Total Cost*/
				$total_profit += ($line_total-$cost_total);
			}
			//$this->print_data($row);
			return isset($total_profit)?$total_profit:0;
			
		}
		function print_data($data){
			print "<pre>";
			print_r($data);
			print "</pre>";
		 }
	}
}