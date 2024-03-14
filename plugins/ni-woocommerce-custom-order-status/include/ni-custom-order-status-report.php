<?php 
if ( ! defined( 'ABSPATH' ) ) { exit;}
  if( !class_exists( 'ni_custom_order_status_report' ) ) {
	class ni_custom_order_status_report {
		
		public function __construct(){
			/*
				code
			*/	
		}
		
		function get_yearly_sales(){
			global $wpdb;
			$query = "
				SELECT 
				SUM(postmeta.meta_value) as 'order_total'
				,YEAR(date_format( posts.post_date, '%Y-%m-%d')) as Year
			FROM {$wpdb->prefix}posts as posts	";		
				
			$query .=
				"	LEFT JOIN  {$wpdb->prefix}postmeta as postmeta ON postmeta.post_id=posts.ID ";
			
			$query .= " WHERE 1=1 ";
			
			
			
			$query .= " AND postmeta.meta_key ='_order_total' ";
			$query .= " AND  posts.post_status NOT IN ('trash')";
			$query .= " AND posts.post_status IN ('wc-processing','wc-on-hold', 'wc-completed')";
			$query .= "  GROUP BY YEAR(date_format( posts.post_date, '%Y-%m-%d')) ";
			
			$rows = $wpdb->get_results( $query);	
			
			//$this->print_data($rows);
			
			return $rows;
		}
		
		function page_init(){
			
			
		?>
        <div class="container-fluid" id="niwoocos">
        	<div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel">
            	 <ol class="carousel-indicators">
                    <li data-target="#carouselExampleSlidesOnly" data-slide-to="0" class="active"></li>
                    <li data-target="#carouselExampleSlidesOnly" data-slide-to="1"></li>
                    <li data-target="#carouselExampleSlidesOnly" data-slide-to="2"></li>
                    <li data-target="#carouselExampleSlidesOnly" data-slide-to="3"></li>
                     <li data-target="#carouselExampleSlidesOnly" data-slide-to="4"></li>
                  </ol>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="card bg-rgba-green-slight">
                            <div class="card-header bg-rgba-salmon-strong"> <?php esc_html_e('Monitor your sales and grow your online business with naziinfotech plugins', 'niwoopvt'); ?> </div>
                            <div class="card-body">
                                <h2 class="card-title text-center color-rgba-salmon-strong">Buy Ni Display Product Variation Table Pro $34.00</h2>
                               	<div class="row" style="font-size:16px">
                                	<div  class="col-md-6">
                                    	  <span class="font-weight-bold color-rgba-black-strong">Show variation product table on product detail page</span>	<br />	
                                        <span class="font-weight-bold color-rgba-black-strong">Show the variation dropdown on product page and category page</span>	<br />
                                        <span class="font-weight-bold color-rgba-black-strong">Show the variation product on shop page and category page</span>	<br />	
                                        <span class="font-weight-bold color-rgba-black-strong">Add to cart bulk quantity on product detail page in variation table</span>	<br />					<span class="font-weight-bold color-rgba-black-strong">Set the default quantity in variation table</span><br />
                                    </div>
                                   
                                    <div  class="col-md-3">
                                    	
                                        <span class="font-weight-bold color-rgba-black-strong">Change the display order for table variation columns</span>	<br />
                                        <span class="font-weight-bold color-rgba-black-strong">Set columns of product variation table</span>	<br />	
                                        
                                    </div>
                                   <div  class="col-md-3">
                                   		<h5> <span class="font-weight-bold" >Coupon Code: <span class="text-warning">ni10</span>  Get 10% OFF</span></h5> 
                                        <span> <span class="font-weight-bold" >Email at:</span><a href="mailto:support@naziinfotech.com" target="_blank">support@naziinfotech.com</a></span><br />
                                        <span> <span class="font-weight-bold" >Website: </span><a href="http://naziinfotech.com/" target="_blank">www.naziinfotech.com</a></span>	<br />	
                                   </div>
                                </div>
                                
                                <div class="text-center">
                                    <a href="http://demo.naziinfotech.com/product/hoodie/" class="btn btn-rgba-salmon-strong btn-lg" target="_blank">View Demo</a>
                                    <a href="http://naziinfotech.com/?product=ni-woocommerce-sales-report-pro" target="_blank" class="btn btn-rgba-salmon-strong btn-lg">Buy Now</a>
                                </div>
                                <br />
                                <br /> 
                           </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="card bg-rgba-green-slight">
                            <div class="card-header bg-rgba-green-strong"> <?php esc_html_e('Monitor your sales and grow your online business with naziinfotech plugins', 'niwoopvt'); ?> </div>
                            <div class="card-body">
                                <h2 class="card-title text-center color-rgba-green-strong">Buy Ni WooCommerce Sales Report Pro $24.00</h2>
                               	<div class="row" style="font-size:16px">
                                	<div  class="col-md-3">
                                    	<span class="font-weight-bold color-rgba-black-strong">Dashboard order Summary</span><br />
                                        <span class="font-weight-bold color-rgba-black-strong">Daily sales line chart and monthly bar chart</span><br />
                                        <span class="font-weight-bold color-rgba-black-strong">Order List - Display order list</span>	<br />
                                        <span class="font-weight-bold color-rgba-black-strong">Order Detail - Display Product information</span>	<br />	
                                        <span class="font-weight-bold color-rgba-black-strong">Sold Product variation Report</span>	<br />	
                                    </div>
                                    <div  class="col-md-3">
                                    	<span class="font-weight-bold color-rgba-black-strong">Customer Sales Report</span><br />
                                        <span class="font-weight-bold color-rgba-black-strong">Payment Gateway Sales Report</span>	<br />
                                        <span class="font-weight-bold color-rgba-black-strong">Country Sales Report</span>	<br />	
                                        <span class="font-weight-bold color-rgba-black-strong">Coupon Sales Report</span>	<br />	
                                         <span class="font-weight-bold color-rgba-black-strong">Product Report</span>	<br />	
                                    </div>
                                    <div  class="col-md-3">
                                    	<span class="font-weight-bold color-rgba-black-strong">Tax Report</span><br />
                                        <span class="font-weight-bold color-rgba-black-strong">Order Export To CSV</span><br />
                                        <span class="font-weight-bold color-rgba-black-strong">Custom Date Filter, Start Date and End Date</span>	<br />
                                        <span class="font-weight-bold color-rgba-black-strong">Customer Analysis</span>	<br />	
                                        <span class="font-weight-bold color-rgba-black-strong">Product Analysis</span>	<br />	
                                    </div>
                                   <div  class="col-md-3">
                                   		<h5> <span class="font-weight-bold" >Coupon Code: <span class="text-warning">ni10</span>  Get 10% OFF</span></h5> 
                                        <span> <span class="font-weight-bold" >Email at:</span><a href="mailto:support@naziinfotech.com" target="_blank">support@naziinfotech.com</a></span><br />
                                        <span> <span class="font-weight-bold" >Website: </span><a href="http://naziinfotech.com/" target="_blank">www.naziinfotech.com</a></span>	<br />	
                                   </div>
                                </div>
                               <div class="text-center">
                                    <a href="http://demo.naziinfotech.com?demo_login=woo_sales_report" class="btn btn-green-strong btn-lg" target="_blank">View Demo</a>
                                    <a href="http://naziinfotech.com/?product=ni-woocommerce-sales-report-pro" target="_blank" class="btn btn-green-strong btn-lg">Buy Now</a>
                                </div>
                                  <br />
                                <br /> 
                           </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="card bg-rgba-cyan-slight">
                            <div class="card-header bg-rgba-cyan-strong"> <?php esc_html_e('Monitor your sales and grow your online business with naziinfotech plugins', 'niwoopvt'); ?> </div>
                            <div class="card-body">
                                <h2 class="card-title text-center color-rgba-cyan-strong">Buy Ni WooCommerce cost of goods Pro @ $34.00</h2>

                               	<div class="row" style="font-size:16px">
                                	<div  class="col-md-3">
                                    	 <span class="font-weight-bold color-rgba-black-strong">Sales Profit Report</span>	<br />	
                                        <span class="font-weight-bold color-rgba-black-strong">Dashboard order Summary</span><br />
                                        <span class="font-weight-bold color-rgba-black-strong">Daily profit Report</span>	<br />
                                        <span class="font-weight-bold color-rgba-black-strong">Monthly profit Report</span>	<br />	
                                       
                                    </div>
                                    <div  class="col-md-3">
                                    	<span class="font-weight-bold color-rgba-black-strong">Add Cost of goods for simple product</span><br />
                                        <span class="font-weight-bold color-rgba-black-strong">Add Cost of goods for variation product</span>	<br />
                                        <span class="font-weight-bold color-rgba-black-strong">Top Profit Product</span>	<br />	
                                        <span class="font-weight-bold color-rgba-black-strong">Stock valuation</span>	<br />	
                                    </div>
                                    <div  class="col-md-3">
                                    	<span class="font-weight-bold color-rgba-black-strong">Order Export To CSV</span><br />
                                        <span class="font-weight-bold color-rgba-black-strong">Custom Date Filter, Start Date and End Date</span>	<br />
                                        <span class="font-weight-bold color-rgba-black-strong">Ajax pagination </span>	<br />	
                                        <span class="font-weight-bold color-rgba-black-strong">Easy to use </span>	<br />	
                                    </div>
                                   <div  class="col-md-3">
                                   		<h5> <span class="font-weight-bold" >Coupon Code: <span class="text-warning">ni10</span>  Get 10% OFF</span></h5> 
                                        <span> <span class="font-weight-bold" >Email at:</span><a href="mailto:support@naziinfotech.com" target="_blank">support@naziinfotech.com</a></span><br />
                                        <span> <span class="font-weight-bold" >Website: </span><a href="http://naziinfotech.com/" target="_blank">www.naziinfotech.com</a></span>	<br />	
                                   </div>
                                </div>
                                <div class="text-center">
                                    <a href="http://demo.naziinfotech.com/?demo_login=woo_cost_of_goods" class="btn btn-rgba-cyan-strong btn-lg" target="_blank">View Demo</a>
                                    <a href="http://naziinfotech.com/product/ni-woocommerce-cost-of-good-pro/" target="_blank" class="btn btn-rgba-cyan-strong btn-lg">Buy Now</a>
                                </div>
                                  <br />
                                <br /> 
                           </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="card bg-rgba-indigo-slight">
                            <div class="card-header bg-rgba-indigo-strong"> <?php esc_html_e('Monitor your sales and grow your online business with naziinfotech plugins', 'niwoopvt'); ?> </div>
                            <div class="card-body">
                                <h2 class="card-title text-center color-rgba-indigo-strong"> <?php esc_html_e('Buy Ni WooCommerce Product Enquiry Pro @ $24.00', 'niwoopvt'); ?> </h2>
                               	<div class="row" style="font-size:16px">
                                	<div  class="col-md-3">
                                    	<span class="font-weight-bold color-rgba-black-strong"><?php esc_html_e('Dashboard Summary (Today, Total Enquiry)', 'niwoopvt'); ?></span><br />
                                        <span class="font-weight-bold color-rgba-black-strong">Monthly Enquiry Graph</span>	<br />
                                        <span class="font-weight-bold color-rgba-black-strong">Recent Enquiry</span>	<br />	
                                        <span class="font-weight-bold color-rgba-black-strong">Last Enquiry Date</span>	<br />	
                                    </div>
                                    <div  class="col-md-3">
                                    	<span class="font-weight-bold color-rgba-black-strong">Enquiry List</span><br />
                                        <span class="font-weight-bold color-rgba-black-strong">Enquiry Export</span>	<br />
                                        <span class="font-weight-bold color-rgba-black-strong">Top Enquiry Product</span>	<br />	
                                        <span class="font-weight-bold color-rgba-black-strong">Top Enquiry Visitor</span>	<br />	
                                    </div>
                                    <div  class="col-md-3">
                                    	<span class="font-weight-bold color-rgba-black-strong">Order Export To CSV</span><br />
                                        <span class="font-weight-bold color-rgba-black-strong">Custom Date Filter, Start Date and End Date</span>	<br />
                                        <span class="font-weight-bold color-rgba-black-strong">Ajax pagination </span>	<br />	
                                        <span class="font-weight-bold color-rgba-black-strong">Easy to use </span>	<br />	
                                    </div>
                                   <div  class="col-md-3">
                                   		<h5> <span class="font-weight-bold" >Coupon Code: <span class="text-warning">ni10</span>  Get 10% OFF</span></h5> 
                                        <span> <span class="font-weight-bold" >Email at:</span><a href="mailto:support@naziinfotech.com" target="_blank">support@naziinfotech.com</a></span><br />
                                        <span> <span class="font-weight-bold" >Website: </span><a href="http://naziinfotech.com/" target="_blank">www.naziinfotech.com</a></span>	<br />	
                                   </div>
                                </div>
                               <div class="text-center">
                                    <a href="http://demo.naziinfotech.com/enquiry-demo/" class="btn btn-rgba-indigo-strong btn-lg" target="_blank">View Demo</a>
                                    <a href="http://naziinfotech.com/product/ni-woocommerce-product-enquiry-pro/" target="_blank" class="btn btn-rgba-indigo-strong btn-lg">Buy Now</a>
                                </div>
                                 <br />
                                <br />  
                           </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="card bg-rgba-blue-slight">
                            <div class="card-header bg-rgba-blue-strong"> <?php esc_html_e('Monitor your sales and grow your online business with naziinfotech plugins', 'niwoopvt'); ?> </div>
                            <div class="card-body">
                                <h2 class="card-title text-center color-rgba-blue-strong"> <?php esc_html_e('Ni One Page Inventory Management System For WooCommerce', 'niwoopvt'); ?> </h2>
                               	<div class="row" style="font-size:16px">
                                	<div  class="col-md-3">
                                    	<span class="font-weight-bold color-rgba-black-strong"><?php esc_html_e('Dashboard Summary stock status', 'niwoopvt'); ?></span><br />
                                        <span class="font-weight-bold color-rgba-black-strong">Manage Purchase order</span>	<br />
                                        <span class="font-weight-bold color-rgba-black-strong">Multi location inventory management</span>	<br />	
                                        <span class="font-weight-bold color-rgba-black-strong">Stock Center</span>	<br />	
                                    </div>
                                    <div  class="col-md-3">
                                    	<span class="font-weight-bold color-rgba-black-strong">Purchase History</span><br />
                                        <span class="font-weight-bold color-rgba-black-strong">Mange product</span>	<br />
                                        <span class="font-weight-bold color-rgba-black-strong">Vendor management</span>	<br />	
                                        <span class="font-weight-bold color-rgba-black-strong">Product Vendor</span>	<br />	
                                    </div>
                                    <div  class="col-md-3">
                                    	<span class="font-weight-bold color-rgba-black-strong">Order Export To CSV</span><br />
                                        <span class="font-weight-bold color-rgba-black-strong">Custom Date Filter, Start Date and End Date</span>	<br />
                                        <span class="font-weight-bold color-rgba-black-strong">Ajax pagination </span>	<br />	
                                        <span class="font-weight-bold color-rgba-black-strong">Easy to use </span>	<br />	
                                    </div>
                                   <div  class="col-md-3">
                                   		<span> <span class="font-weight-bold" >Email at:</span><a href="mailto:support@naziinfotech.com" target="_blank">support@naziinfotech.com</a></span><br />
                                        <span> <span class="font-weight-bold" >Website: </span><a href="http://naziinfotech.com/" target="_blank">www.naziinfotech.com</a></span>	<br />	
                                   </div>
                                </div>
                                 <div class="text-center">
                                    <a href="https://wordpress.org/plugins/ni-one-page-inventory-management-system-for-woocommerce/" class="btn btn-rgba-blue-strong btn-lg" target="_blank">View</a>
                                    <a href="https://downloads.wordpress.org/plugin/ni-one-page-inventory-management-system-for-woocommerce.zip" target="_blank" class="btn btn-rgba-blue-strong btn-lg">Download</a>
                                </div>
                                   <br />
                                <br />  
                           </div>
                        </div>
                    </div>
                </div>
                 <a class="carousel-control-prev" href="#carouselExampleSlidesOnly" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                  </a>
                  <a class="carousel-control-next" href="#carouselExampleSlidesOnly" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                  </a>
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
                      <div class="card-header niwoocos-bg-c-pink-strong">
                        <?php esc_html_e('Dashboard - Sales Analysis', 'niwoocos'); ?>
                      </div>
                      <div class="card-body"> 
                        <div class="row">
                           <div class="col-xl-3 col-md-6 col-lg-4   box10">
							  <div class="card card-border-top card-border-top-box10  shadow p-3 mb-4 bg-white rounded">
                                  <div class="card-body card-body-padding" >
                                  	<div class="card-block">
									<h6 class="m-b-20" style="font-size: 14px; text-transform: uppercase"><strong> <?php esc_html_e('Total Sales', 'niwoocos'); ?></strong></h6>
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
									<h6 class="m-b-20" style="font-size: 14px; text-transform: uppercase"><strong><?php esc_html_e('This Year Sales', 'niwoocos'); ?></strong></h6>
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
									<h6 class="m-b-20" style="font-size: 14px; text-transform: uppercase"><strong><?php esc_html_e('This Month Sales', 'niwoocos'); ?></strong></h6>
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
									<h6 class="m-b-20" style="font-size: 14px; text-transform: uppercase"><strong><?php esc_html_e('This Week Sales', 'niwoocos'); ?></strong></h6>
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
									<h6 class="m-b-20" style="font-size: 14px; text-transform: uppercase"><strong> <?php esc_html_e('Yesterday Sales', 'niwoocos'); ?></strong></h6>
									<p class="text-right"><span>&nbsp;</span></p>
									<h3 class="m-b-0"><span class="f-right"><?php  echo wc_price( $this->get_total_sales("YESTERDAY")); ?></span></h3>
								</div>
                                  </div>
                                </div>
                            </div>
                            
                           
                           <div class="col-xl-3 col-md-6 col-lg-4   box10">
							  <div class="card card-border-top card-border-top-box10  shadow p-3 mb-5 bg-white rounded">
                                  <div class="card-body card-body-padding">
                                  	<div class="card-block">
									<h6 class="m-b-20" style="font-size: 14px; text-transform: uppercase"><strong> <?php esc_html_e('Total Sales Count', 'niwoocos'); ?></strong></h6>
									<p class="text-right"><span>&nbsp;</span></p>
									<h3 class="m-b-0"><span class="f-right"><?php print($this->get_total_sales_count("ALL")); ?></span></h3>
								</div>
                                  </div>
                                </div>
                            </div>
						   <div class="col-xl-3 col-md-6 col-lg-4   box1">
							  <div class="card card-border-top card-border-top-box1  shadow p-3 mb-5 bg-white rounded">
                                  <div class="card-body card-body-padding">
                                  	<div class="card-block">
									<h6 class="m-b-20" style="font-size: 14px; text-transform: uppercase"><strong><?php esc_html_e('This Year Sales Count', 'niwoocos'); ?></strong></h6>
									<p class="text-right"><span>&nbsp;</span></p>
									<h3 class="m-b-0"><span class="f-right"><?php print($this->get_total_sales_count("YEAR")); ?></span></h3>
								</div>
                                  </div>
                                </div>
                            </div>
						   <div class="col-xl-3 col-md-6 col-lg-4   box4">
							  <div class="card card-border-top card-border-top-box4  shadow p-3 mb-5 bg-white rounded">
                                  <div class="card-body card-body-padding">
                                  	<div class="card-block">
									<h6 class="m-b-20" style="font-size: 14px; text-transform: uppercase"><strong><?php esc_html_e('This Month Sales Count', 'niwoocos'); ?></strong></h6>
									<p class="text-right"><span>&nbsp;</span></p>
									<h3 class="m-b-0"><span class="f-right"><?php print($this->get_total_sales_count("MONTH")); ?></span></h3>
								</div>
                                  </div>
                                </div>
                            </div>
						   <div class="col-xl-3 col-md-6 col-lg-4   box6">
							  <div class="card card-border-top card-border-top-box6  shadow p-3 mb-5 bg-white rounded">
                                  <div class="card-body card-body-padding">
                                  	<div class="card-block">
									<h6 class="m-b-20" style="font-size: 14px; text-transform: uppercase"><strong><?php esc_html_e('This Week Sales Count', 'niwoocos'); ?></strong></h6>
									<p class="text-right"><span>&nbsp;</span></p>
									<h3 class="m-b-0"><span class="f-right"><?php print($this->get_total_sales_count("WEEK")); ?></span></h3>
								</div>
                                  </div>
                                </div>
                            </div>
                           <div class="col-xl-3 col-md-6 col-lg-4   box8">
							  <div class="card card-border-top card-border-top-box8  shadow p-3 mb-5 bg-white rounded">
                                  <div class="card-body card-body-padding">
                                  	<div class="card-block">
									<h6 class="m-b-20" style="font-size: 14px; text-transform: uppercase"><strong> <?php esc_html_e('Yesterday Sales Count', 'niwoocos'); ?></strong></h6>
									<p class="text-right"><span>&nbsp;</span></p>
									<h3 class="m-b-0"><span class="f-right"><?php print($this->get_total_sales_count("YESTERDAY")); ?></span></h3>
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
            	<div class="col-md-12" >
         			<div class="card">
                      <div class="card-header niwoocos-bg-c-pink-strong">
                        <?php esc_html_e('Customer Analysis', 'niwoocos'); ?> 
                      </div>
                      <div class="card-body "> 
                        <div class="row">
                           <div class="col-xl-3 col-md-6 col-lg-4   box10">
							  <div class="card card-border-top card-border-top-box10  shadow p-3 mb-5 bg-white rounded">
                                  <div class="card-body card-body-padding">
                                  	<div class="card-block">
									<h6 class="m-b-20" style="font-size: 14px; text-transform: uppercase"><strong> <?php esc_html_e('Total Customer Count', 'niwoocos'); ?></strong></h6>
									<p class="text-right"><span>&nbsp;</span></p>
									<h3 class="m-b-0"><span class="f-right"><?php echo $this->get_customer("ALL"); ?></span></h3>
								</div>
                                  </div>
                                </div>
                            </div>
						   <div class="col-xl-3 col-md-6 col-lg-4   box1">
							  <div class="card card-border-top card-border-top-box1  shadow p-3 mb-5 bg-white rounded">
                                  <div class="card-body card-body-padding">
                                  	<div class="card-block">
									<h6 class="m-b-20" style="font-size: 14px; text-transform: uppercase"><strong><?php esc_html_e('Today Customer Count', 'niwoocos'); ?></strong></h6>
									<p class="text-right"><span>&nbsp;</span></p>
									<h3 class="m-b-0"><span class="f-right"><?php echo ($this->get_customer("TODAY")); ?></span></h3>
								</div>
                                  </div>
                                </div>
                            </div>
						   <div class="col-xl-3 col-md-6 col-lg-4   box4">
							  <div class="card card-border-top card-border-top-box4  shadow p-3 mb-5 bg-white rounded">
                                  <div class="card-body card-body-padding">
                                  	<div class="card-block">
									<h6 class="m-b-20" style="font-size: 14px; text-transform: uppercase"><strong><?php esc_html_e('Total Guest Customer Count', 'niwoocos'); ?></strong></h6>
									<p class="text-right"><span>&nbsp;</span></p>
									<h3 class="m-b-0"><span class="f-right"><?php print($this->get_guest_customer("ALL")); ?></span></h3>
								</div>
                                  </div>
                                </div>
                            </div>
						   <div class="col-xl-3 col-md-6 col-lg-4   box6">
							  <div class="card card-border-top card-border-top-box6  shadow p-3 mb-5 bg-white rounded">
                                  <div class="card-body card-body-padding">
                                  	<div class="card-block">
									<h6 class="m-b-20" style="font-size: 14px; text-transform: uppercase"><strong><?php esc_html_e('Today Guest Cust. Count', 'niwoocos'); ?></strong></h6>
									<p class="text-right"><span>&nbsp;</span></p>
									<h3 class="m-b-0"><span class="f-right"><?php print($this->get_guest_customer("TODAY")); ?></span></h3>
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
            	<div class="col-md-12" >
         			<div class="card">
                      <div class="card-header niwoocos-bg-c-pink-strong">
                       <?php esc_html_e('Today  Sales Analysis', 'niwoocos'); ?>
                      </div>
                      <div class="card-body "> 
                        <div class="row">
                           <div class="col-xl-3 col-md-6 col-lg-4   box10">
							  <div class="card card-border-top card-border-top-box10  shadow p-3 mb-5 bg-white rounded">
                                  <div class="card-body card-body-padding">
                                  	<div class="card-block">
									<h6 class="m-b-20" style="font-size: 14px; text-transform: uppercase"><strong> <?php esc_html_e('Today Order Count', 'niwoocos'); ?></strong></h6>
									<p class="text-right"><span>&nbsp;</span></p>
									<h3 class="m-b-0"><span class="f-right"><?php print($this->get_total_sales_count("DAY")); ?></span></h3>
								</div>
                                  </div>
                                </div>
                            </div>
						   <div class="col-xl-3 col-md-6 col-lg-4   box1">
							  <div class="card card-border-top card-border-top-box1  shadow p-3 mb-5 bg-white rounded">
                                  <div class="card-body card-body-padding">
                                  	<div class="card-block">
									<h6 class="m-b-20" style="font-size: 14px; text-transform: uppercase"><strong><?php esc_html_e('Today Sales', 'niwoocos'); ?></strong></h6>
									<p class="text-right"><span>&nbsp;</span></p>
									<h3 class="m-b-0"><span class="f-right"><?php echo wc_price( $this->get_total_sales("DAY")); ?></span></h3>
								</div>
                                  </div>
                                </div>
                            </div>
						   <div class="col-xl-3 col-md-6 col-lg-4   box4">
							  <div class="card card-border-top card-border-top-box4  shadow p-3 mb-5 bg-white rounded">
                                  <div class="card-body card-body-padding">
                                  	<div class="card-block">
									<h6 class="m-b-20" style="font-size: 14px; text-transform: uppercase"><strong><?php esc_html_e('Today Product Sold', 'niwoocos'); ?></strong></h6>
									<p class="text-right"><span>&nbsp;</span></p>
									<h3 class="m-b-0"><span class="f-right"><?php print($this->get_sold_product_count("TODAY")); ?></span></h3>
								</div>
                                  </div>
                                </div>
                            </div>
                           <div class="col-xl-3 col-md-6 col-lg-4   box6">
							  <div class="card card-border-top card-border-top-box6  shadow p-3 mb-5 bg-white rounded">
                                  <div class="card-body card-body-padding">
                                  	<div class="card-block">
									<h6 class="m-b-20" style="font-size: 14px; text-transform: uppercase"><strong><?php esc_html_e('Today Discount', 'niwoocos'); ?></strong></h6>
									<p class="text-right"><span>&nbsp;</span></p>
									<h3 class="m-b-0"><span class="f-right"><?php echo wc_price($this->get_total_discount("TODAY")); ?></span></h3>
								</div>
                                  </div>
                                </div>
                            </div>
                           <div class="col-xl-3 col-md-6 col-lg-4   box6">
							  <div class="card card-border-top card-border-top-box6  shadow p-3 mb-5 bg-white rounded">
                                  <div class="card-body card-body-padding">
                                  	<div class="card-block">
									<h6 class="m-b-20" style="font-size: 14px; text-transform: uppercase"><strong><?php esc_html_e('Today Tax', 'niwoocos'); ?></strong></h6>
									<p class="text-right"><span>&nbsp;</span></p>
									<h3 class="m-b-0"><span class="f-right"><?php echo  wc_price($this->get_total_tax("TODAY")); ?></span></h3>
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
            	<div class="col-md-12" >
         			<div class="card">
                      <div class="card-header niwoocos-bg-c-pink-strong">
                      <?php esc_html_e('Stock Analysis', 'niwoocos'); ?>
                      </div>
                      <div class="card-body "> 
                        <div class="row">
                           <div class="col-xl-3 col-md-6 col-lg-4   box10">
							  <div class="card card-border-top card-border-top-box10  shadow p-3 mb-5 bg-white rounded">
                                  <div class="card-body card-body-padding">
                                  	<div class="card-block">
									<h6 class="m-b-20" style="font-size: 14px; text-transform: uppercase"><strong> <?php esc_html_e('Low in stock', 'niwoocos'); ?></strong></h6>
									<p class="text-right"><span>&nbsp;</span></p>
									<h3 class="m-b-0"><span class="f-right"> <a href="<?php echo esc_url_raw(admin_url("admin.php")."?page=wc-reports&tab=stock&report=low_in_stock"); ?>"><?php   print($this->get_low_in_stock()); ?></a></span></h3>
								</div>
                                  </div>
                                </div>
                            </div>
						   <div class="col-xl-3 col-md-6 col-lg-4   box1">
							  <div class="card card-border-top card-border-top-box1  shadow p-3 mb-5 bg-white rounded">
                                  <div class="card-body card-body-padding">
                                  	<div class="card-block">
									<h6 class="m-b-20" style="font-size: 14px; text-transform: uppercase"><strong><?php esc_html_e('Out of stock', 'niwoocos'); ?></strong></h6>
									<p class="text-right"><span>&nbsp;</span></p>
									<h3 class="m-b-0"><span class="f-right"><a href="<?php echo esc_url_raw(admin_url("admin.php")."?page=wc-reports&tab=stock&report=out_of_stock");  ?>"><?php   print($this->get_out_of_stock()); ?></a></span></h3>
								</div>
                                  </div>
                                </div>
                            </div>
						   <div class="col-xl-3 col-md-6 col-lg-4   box4">
							  <div class="card card-border-top card-border-top-box4  shadow p-3 mb-5 bg-white rounded">
                                  <div class="card-body card-body-padding">
                                  	<div class="card-block">
									<h6 class="m-b-20" style="font-size: 14px; text-transform: uppercase"><strong><?php esc_html_e('Most Stocked', 'niwoocos'); ?></strong></h6>
									<p class="text-right"><span>&nbsp;</span></p>
									<h3 class="m-b-0"><span class="f-right"><a href="<?php echo esc_url_raw(admin_url("admin.php")."?page=wc-reports&tab=stock&report=most_stocked"); ?>"><?php   print($this->get_most_stock()); ?></a></span></h3>
								</div>
                                  </div>
                                </div>
                            </div>
                           
                           <?php do_action("ni_sales_report_dashboard_after_today_summary"); ?> 
                        </div>
						</div>
                     
                    </div>       	
                </div>
            </div>
            
            
            <div class="row" >
            	<div class="col-md-12" >
         			<div class="card">
                      <div class="card-header niwoocos-bg-c-pink-strong">
                      <?php esc_html_e('Yearly Sales', 'niwoocos'); ?>
                      </div>
                      <div class="card-body "> 
                        <div class="row">
                        	<?php $yearly = $this->get_yearly_sales(); ?>
                            <?php $i= 2; ?>
                            <?php foreach($yearly as $key=>$value): ?>
                            	
                            	<div class="col-xl-3 col-md-6 col-lg-4   box<?php echo esc_attr($i); ?> ">
							  <div class="card card-border-top card-border-top-box<?php echo esc_attr($i); ?>  shadow p-3 mb-5 bg-white rounded">
                                  <div class="card-body card-body-padding">
                                  	<div class="card-block">
									<h6 class="m-b-20" style="font-size: 14px; text-transform: uppercase"><strong><?php echo esc_html($value->Year);?></strong></h6>
									<p class="text-right"><span>&nbsp;</span></p>
									<h3 class="m-b-0"><span class="f-right"> <a href="<?php echo admin_url("admin.php?page=wc-reports&tab=stock&report=low_in_stock"); ?>"><?php   echo wc_price( $value->order_total); ?></a></span></h3>
								</div>
                                  </div>
                                </div>
                            </div>
                            <?php $i++; ?>
							<?php endforeach; ?>
                           <?php do_action("ni_sales_report_dashboard_after_today_summary"); ?> 
                        </div>
						</div>
                     
                    </div>       	
                </div>
            </div>
            
            
            <div class="row" >
            	<div class="col-md-12">
         			<div class="card">
                      <div class="card-header niwoocos-bg-c-pink-strong">
                     <?php esc_html_e( 'recent orders', 'niwoocos'); ?>
                      </div>
                      <div class="card-body "> 
                        <div class="row">
                        	<div class="table-responsive niwoocos-table">
                            	<table class="table table-striped table-hover">
                    	 <thead class="shadow-sm p-3 mb-5 bg-white rounded">
                        	<tr>
                                <th><?php esc_html_e( 'Order ID', 'niwoocos'); ?></th>
                                <th><?php esc_html_e( 'Order Date', 'niwoocos'); ?> </th>
                                <th><?php esc_html_e( 'First Name', 'niwoocos'); ?> </th>
                                <th><?php esc_html_e( 'Billing Email', 'niwoocos'); ?></th>
                                <th><?php esc_html_e( 'Country', 'niwoocos'); ?> </th>
                                <th><?php esc_html_e( 'Order Status', 'niwoocos'); ?>  </th>
                                <th><?php esc_html_e( 'Currency', 'niwoocos'); ?> </th>
                                <th style="text-align:right"><?php esc_html_e( 'Order Total', 'niwoocos'); ?>  </th>
                            </tr>
                        </thead>
						
					   <?php $order_data = $this->get_recent_order_list();   ?>
					   <?php foreach($order_data as $key=>$v){ ?>
                       <tr>
                            <td><?php echo esc_html($v["order_id"]); ?></td>
                            <td><?php echo esc_html($v["order_date"]); ?></td>
                            <td><?php echo esc_html(isset($v["billing_first_name"]) ? $v["billing_first_name"] : ''); ?></td>
                            <td><?php echo esc_html(isset($v["billing_email"]) ? $v["billing_email"] : ''); ?></td>
                            <td><?php echo esc_html($this->get_country_name((isset($v["billing_country"]) ? $v["billing_country"] : ''))); ?></td>
                            <td><?php echo esc_html(ucfirst(str_replace("wc-","", $v["order_status"]))); ?></td>
                            <td><?php echo esc_html($v["order_currency"]); ?></td>
                            <td style="text-align:right"><?php echo wc_price( $v["order_total"]); ?></td>
                        </tr>
                        <?php } ?>
					</table>
                            </div>
                           
                        </div>
						</div>
                      
                    </div>       	
                </div>
            </div>
            
            <div class="row" >
            	<div class="col-md-12" >
         			<div class="card">
                      <div class="card-header niwoocos-bg-c-pink-strong">
                     <?php esc_html_e( 'Order Status', 'niwoocos'); ?>
                      </div>
                      <div class="card-body "> 
                        <div class="row">
                        	
						<div class="col-sm-6 col-md-6 col-lg-6">



                            <div class="table-responsive niwoocos-table">
                            	<table class="table table-striped table-hover">
                                 <thead class="shadow-sm p-3 mb-5 bg-white rounded">
                                    <tr>
                                    <th><?php esc_html_e('Order Status (All Order Status)', 'niwoocos'); ?></th>
                                    <th style="text-align:right"><?php esc_html_e('Order Count', 'niwoocos'); ?></th>
                                    <th style="text-align:right"><?php esc_html_e('Order Total', 'niwoocos'); ?></th>
                                    </tr>
                                </thead>
                                
                                <?php $results = $this->get_order_status("ALL");?>
                                <?php foreach($results as $key=>$value){ ?>
                                <tr>
                                    <td><?php echo  esc_html(ucfirst ( str_replace("wc-","", $value["order_status"]))); ?></td>
                                    <td style="text-align:right"><?php echo esc_html($value["order_count"]); ?></td>
                                    <td style="text-align:right"><?php echo wc_price($value["order_total"]); ?></td>
                                </tr>
                                <?php }?>
                            </table>
                            </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-6">
							<div class="table-responsive niwoocos-table">
                            	<table class="table table-striped table-hover">
                                 <thead class="shadow-sm p-3 mb-5 bg-white rounded">
                                    <tr>
                                    <th><?php esc_html_e('Order Status (Today order status)', 'niwoocos'); ?></th>
                                    <th style="text-align:right"><?php esc_html_e('Order Count', 'niwoocos'); ?></th>
                                    <th style="text-align:right"><?php esc_html_e('Order Total', 'niwoocos'); ?></th>
                                    </tr>
                                </thead>
                                
                                <?php $results = $this->get_order_status("TODAY");?>
                                <?php foreach($results as $key=>$value){ ?>
                                <tr>
                                    <td><?php echo  esc_html(ucfirst ( str_replace("wc-","", $value["order_status"]))); ?></td>
                                    <td style="text-align:right"><?php echo esc_html($value["order_count"]); ?></td>
                                    <td style="text-align:right"><?php echo wc_price($value["order_total"]); ?></td>
                                </tr>
                                <?php }?>
                            </table>
                            </div>
                        	
                           
                        </div>
						</div>
                      
                    </div>       	
                </div>
            
            <div class="row" >
            	<div class="col-md-12" >
         			<div class="card">
                      <div class="card-header niwoocos-bg-c-pink-strong">
                    <?php esc_html_e('payment gateway', 'niwoocos'); ?>
                      </div>
                      <div class="card-body "> 
                        <div class="row">


                        	<div class="col-sm-6 col-md-6 col-lg-6">
                            <div class="table-responsive niwoocos-table">
                            		<table class="table table-striped table-hover">
                        	 <thead class="shadow-sm p-3 mb-5 bg-white rounded">
                            	<tr>
                                <th><?php esc_html_e( 'Payment Method (All order Payment Method))', 'niwoocos'); ?> </th>
                                <th><?php esc_html_e( 'Order Count', 'niwoocos'); ?></th>
                                <th><?php esc_html_e( 'Order Total', 'niwoocos'); ?></th>
                            </tr>
                            </thead>
							<?php $data  = $this->get_payment_gateway("ALL"); ?>
                            <?php  foreach($data  as $k=>$v){ ?>
							<tr>
                                <td><?php echo esc_html($v["payment_method_title"]); ?></td>
                                <td><?php echo esc_html($v["order_count"]); ?></td>
                                <td><?php echo wc_price($v["order_total"]); ?></td>
                            </tr>
                           <?php } ?> 
						</table>
                            </div>
                            </div>

							<div class="col-sm-6 col-md-6 col-lg-6">
                            <div class="table-responsive niwoocos-table">
                            		<table class="table table-striped table-hover">
                        	 <thead class="shadow-sm p-3 mb-5 bg-white rounded">
                            	<tr>
                                <th><?php esc_html_e( 'Payment Method (Today Payment Method)', 'niwoocos'); ?> </th>
                                <th><?php esc_html_e( 'Order Count', 'niwoocos'); ?></th>
                                <th><?php esc_html_e( 'Order Total', 'niwoocos'); ?></th>
                            </tr>
                            </thead>
							<?php $data  = $this->get_payment_gateway("TODAY"); ?>
                            <?php  foreach($data  as $k=>$v){ ?>
							<tr>
                                <td><?php echo esc_html($v["payment_method_title"]); ?></td>
                                <td><?php echo esc_html($v["order_count"]); ?></td>
                                <td><?php echo wc_price($v["order_total"]); ?></td>
                            </tr>
                           <?php } ?> 
						</table>
                            </div>
                </div>
                
            <div class="row" >
            	<div class="col-md-6"  >
         			<div class="card">
                      <div class="card-header niwoocos-bg-c-pink-strong">
                    <?php esc_html_e('Top 5 Customer Report', 'niwoocos'); ?>
                      </div>
                      <div class="card-body "> 
                        <div class="row">
                        	
                            <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="table-responsive niwoocos-table">
                            		<table class="table table-striped table-hover">
                        	 <thead class="shadow-sm p-3 mb-5 bg-white rounded">
                            	<tr>
                                <th><?php esc_html_e('First Name', 'niwoocos'); ?>  </th>
                                <th><?php esc_html_e('Email Address', 'niwoocos'); ?></th>
                                <th><?php esc_html_e('Order Count', 'niwoocos'); ?></th>
                                <th><?php esc_html_e('Order Total', 'niwoocos'); ?></th>
                            </tr>
                            </thead>
							
                            <?php $data  = $this->get_customer_report(); ?>
                            <?php 
							if (count($data)==0){
							?>
                            <tr>
                            	<td colspan="4"><?php esc_html_e('No Customer found', 'niwoocos'); ?></td>
                            </tr>
                            <?php
							} 
							 ?>
                            <?php  foreach($data  as $k=>$v){ ?>
							<tr>
                                <td><?php echo esc_html($v->billing_first_name); ?></td>
                                <td><?php echo esc_html($v->billing_email); ?></td>
                                 <td><?php echo esc_html($v->order_count); ?></td>
                                <td><?php print($this->get_price($v->order_total)); ?></td>
                            </tr>
                           <?php } ?> 
						</table>
                            </div>
                            </div>
                            </div>
                            
                        	
                           
                        </div>
						</div>
                      
                    </div>
                <div class="col-md-6" >
         			<div class="card">
                      <div class="card-header niwoocos-bg-c-pink-strong">
                  <?php esc_html_e('TOP 5 Country REPORT', 'niwoocos'); ?> 
                      </div>
                      <div class="card-body "> 
                        <div class="row">
                        	<div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="table-responsive niwoocos-table">
                            		<div class="col-sm-12 col-md-12 col-lg-12">
                        		<table class="table table-striped table-hover">
                        	 <thead class="shadow-sm p-3 mb-5 bg-white rounded">
                            	<tr>
                                <th><?php esc_html_e('Country', 'niwoocos'); ?></th>
                                <th><?php esc_html_e('Order Count', 'niwoocos'); ?></th>
                                <th><?php esc_html_e('Order Total', 'niwoocos'); ?></th>
                            </tr>
                            </thead>
							
                            <?php $data  = $this->get_country_report(); ?>
                            <?php  foreach($data  as $k=>$v){ ?>
							<tr>
                                <td><?php echo esc_html($this->get_country_name( $v["billing_country"])); ?></td>
                                <td><?php echo esc_html($v["order_count"]); ?></td>
                                <td><?php print($this->get_price($v["order_total"])); ?></td>
                            </tr>
                           <?php } ?> 
						</table>
                            </div>
                            </div>
                            
                            </div>
                            
                        	
                           
                        </div>
						</div>
                      
                    </div>           	
                </div>        
                
            </div>
            
        </div>
		
		<?php
		}
		
		function print_array($arr){
			echo "<pre>";
			print_r($arr);
			echo "</pre>";
		}
		function get_total_sales($period="CUSTOM", $start_date=NULL, $end_date=NULL){

			$start_date = date('Y-m-d');
			$end_date = date('Y-m-d');

			$args = array();

			switch ($period) {
				case 'ALL':
					$args = array();
					break;
				case 'YEAR':
					$start_date = date('Y-01-01');
					$end_date = date('Y-12-31');
					$args["date_created"] =  $start_date . '...' . $end_date;
					break;
				case 'MONTH':
					$start_date = date('Y-m-01');
					$end_date = date('Y-m-t');
					$args["date_created"] =  $start_date . '...' . $end_date;
					break;
				case 'WEEK':
					$start_date = date('Y-m-d', strtotime('last sunday'));
					$end_date = date('Y-m-d');
					$args["date_created"] =  $start_date . '...' . $end_date;
					break;	
				case 'YESTERDAY':
					$start_date = date('Y-m-d', strtotime('-1 day'));
					$end_date = date('Y-m-d', strtotime('-1 day'));
					$args["date_created"] =  $start_date . '...' . $end_date;
					break;
				case 'TODAY':
					$args["date_created"] =  $start_date . '...' . $end_date;
					break;							
				default:
					$args["date_created"] =  $start_date . '...' . $end_date;
					break;	
			}

			$orders = wc_get_orders($args);


			$completed_order_total = 0;
			foreach ($orders as $order) {
				$completed_order_total += $order->get_total();
			}
			return	$completed_order_total ;
		}

		function get_date_filter($period='ALL'){

			$start_date = date('Y-m-d');
			$end_date = date('Y-m-d');

			$args = array();

			switch ($period) {
				case 'ALL':
					$args = array();
					break;
				case 'YEAR':
					$start_date = date('Y-01-01');
					$end_date = date('Y-12-31');
					$args["date_created"] =  $start_date . '...' . $end_date;
					break;
				case 'MONTH':
					$start_date = date('Y-m-01');
					$end_date = date('Y-m-t');
					$args["date_created"] =  $start_date . '...' . $end_date;
					break;
				case 'WEEK':
					$start_date = date('Y-m-d', strtotime('last sunday'));
					$end_date = date('Y-m-d');
					$args["date_created"] =  $start_date . '...' . $end_date;
					break;	
				case 'YESTERDAY':
					$start_date = date('Y-m-d', strtotime('-1 day'));
					$end_date = date('Y-m-d', strtotime('-1 day'));
					$args["date_created"] =  $start_date . '...' . $end_date;
					break;
				case 'TODAY':
					$args["date_created"] =  $start_date . '...' . $end_date;
					break;							
				default:
					$args["date_created"] =  $start_date . '...' . $end_date;
					break;	
			}
			
			return $args;

		}

		function get_total_sales_deprecated($period="CUSTOM", $start_date=NULL, $end_date=NULL){
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
			$prepare = false;
			if ($period =="YESTERDAY"){
				$prepare = true;
				$query .= ' 
				AND DATE_FORMAT(posts.post_date, \'%\Y-%\m-%\d\') = DATE_SUB(\'%1$s\', INTERVAL 1 DAY)';
			}
			
			if ($period =="DAY"){
				$prepare = true;
				$query .= ' AND date_format( posts.post_date, \'%\Y-%\m-%\d\') = \'%1$s\' ';
			}
			
			if ($period =="WEEK"){	
				$query .= "  AND  YEAR(date_format( posts.post_date, '%Y-%m-%d')) = YEAR(CURRENT_DATE()) AND WEEK(date_format( posts.post_date, '%Y-%m-%d')) = WEEK(CURRENT_DATE()) ";
			}
			
			if ($period =="MONTH"){		
				$query .= "  AND  YEAR(date_format( posts.post_date, '%Y-%m-%d')) = YEAR(CURRENT_DATE()) AND MONTH(date_format( posts.post_date, '%Y-%m-%d')) = MONTH(CURRENT_DATE()) ";
			}
			
			if ($period =="YEAR"){		
				$query .= " AND YEAR(date_format( posts.post_date, '%Y-%m-%d')) = YEAR(date_format(NOW(), '%Y-%m-%d')) "; 
			}
			
			$query .= ' AND  posts.post_status NOT IN (\'trash\')';
			
			if ($period =="DAY"){
				$query .= ' GROUP BY  date_format( posts.post_date, \'%\Y-%\m-%\d\')';
			}
			
			if($prepare){
				$query = $wpdb->prepare($query , $today_date);
			}				
			$results = $wpdb->get_var($query);	
			
			if($wpdb->last_error){
				error_log($period);
				error_log($query);
			}
							
			$results = isset($results) ? $results : "0";
			
			return $results;
			
			return 0;
			
		}
		function get_total_sales_count($period="CUSTOM",$start_date=NULL,$end_date=NULL){
		
			$start_date = date('Y-m-d');
			$end_date = date('Y-m-d');

			$args = array();

			switch ($period) {
				case 'ALL':
					$args = array();
					break;
				case 'YEAR':
					$start_date = date('Y-01-01');
					$end_date = date('Y-12-31');
					$args["date_created"] =  $start_date . '...' . $end_date;
					break;
				case 'MONTH':
					$start_date = date('Y-m-01');
					$end_date = date('Y-m-t');
					$args["date_created"] =  $start_date . '...' . $end_date;
					break;
				case 'WEEK':
					$start_date = date('Y-m-d', strtotime('last sunday'));
					$end_date = date('Y-m-d');
					$args["date_created"] =  $start_date . '...' . $end_date;
					break;	
				case 'YESTERDAY':
					$start_date = date('Y-m-d', strtotime('-1 day'));
					$end_date = date('Y-m-d', strtotime('-1 day'));
					$args["date_created"] =  $start_date . '...' . $end_date;
					break;
				case 'TODAY':
					$args["date_created"] =  $start_date . '...' . $end_date;
					break;							
				default:
					$args["date_created"] =  $start_date . '...' . $end_date;
					break;	
			}

			$orders = wc_get_orders($args);


		
			$total_order_count = count($orders);

			return	$total_order_count ;

		}
		function get_total_sales_count_deprecated($period="CUSTOM",$start_date=NULL,$end_date=NULL){
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
			
			$prepare = false;
			
			if($period=="YESTERDAY"){
				$prepare = true;
				$query .= ' AND DATE_FORMAT(posts.post_date,\'%\Y-%\m-%\d\') = DATE_SUB(\'%1$s\', INTERVAL 1 DAY)';
			}
			
			if ($period =="DAY"){		
				$prepare = true;
				$query .= ' AND DATE_FORMAT( posts.post_date, \'%\Y-%\m-%\d\') = \'%1$s\' '; 				
			}
			
			if ($period =="WEEK"){
				$query .= "  AND  YEAR(date_format( posts.post_date, '%Y-%m-%d')) = YEAR(CURRENT_DATE()) AND WEEK(date_format( posts.post_date, '%Y-%m-%d')) = WEEK(CURRENT_DATE()) ";
			}
			if ($period =="MONTH"){			
				$query .= "  AND  YEAR(date_format( posts.post_date, '%Y-%m-%d')) = YEAR(CURRENT_DATE()) AND MONTH(date_format( posts.post_date, '%Y-%m-%d')) = MONTH(CURRENT_DATE()) ";			
			}
			if ($period =="YEAR"){		
				$query .= " AND YEAR(date_format( posts.post_date, '%Y-%m-%d')) = YEAR(date_format(NOW(), '%Y-%m-%d')) "; 
			}
			
			$query .= " AND  posts.post_status NOT IN ('trash')";
			
			if ($period =="DAY"){		
				$query .= ' GROUP BY  DATE_FORMAT( posts.post_date, \'%\Y-%\m-%\d\') ';
			}
			
			if($prepare){
				$query = $wpdb->prepare($query,$today_date);
			}
			
			if($period=="YESTERDAY"){
				//error_log($query);
			}
			$results = $wpdb->get_var($query);
			
			$results = isset($results) ? $results : "0";	
			
			return $results;
		}
		function get_recent_order_list(){
			$args = array();
			$args  = $this->get_date_filter();

			$args["order"]  = "DESC";
			$args["limit"]  =10;

			$orders = wc_get_orders($args);
			

			
			// Initialize an array to store order data
			$order_data_array = array();

			// Loop through each order and collect relevant information
			foreach ($orders as $order) {
				$order_id = $order->get_id();
				$order_date = $order->get_date_created()->format('Y-m-d H:i:s');
				$first_name = $order->get_billing_first_name();
				$last_name = $order->get_billing_last_name();
				$email_address = $order->get_billing_email();
				$country = $order->get_billing_country();
				$currency = $order->get_currency();
				$order_total = $order->get_total();
				$order_status = $order->get_status();

				// Store order data in an array
				$order_data_array[] = array(
					'order_id' => $order_id,
					'order_date' => $order_date,
					'billing_first_name' => $first_name,
					'billing_last_name' => $last_name,
					'billing_email' => $email_address,
					'billing_country' => $country,
					'order_currency' => $currency,
					'order_total' => $order_total,
					'order_status' => $order_status, // Include the order status
				);
			}

			return $order_data_array;
		}
		function get_recent_order_list_deprecated(){
			global $wpdb;
			$query = "SELECT
				posts.ID as order_id
				,posts.post_status as order_status
				
				, date_format( posts.post_date, '%Y-%m-%d') as order_date 
				
				FROM {$wpdb->prefix}posts as posts			
				
				WHERE 
						posts.post_type ='shop_order' 
						
						AND posts.post_status IN ('wc-pending','wc-processing','wc-on-hold', 'wc-completed' ,'wc-cancelled' ,  'wc-refunded' ,'wc-failed')
						
						";
			$query .= " AND  posts.post_status NOT IN ('trash')";
			$query .= " order by posts.post_date DESC";	
			$query .= " LIMIT 10 ";
			$order_data = $wpdb->get_results( $query);	
			if(count($order_data)> 0){
				foreach($order_data as $k => $v){
					
					/*Order Data*/
					$order_id =$v->order_id;
					$order_detail = $this->get_order_detail($order_id);
					foreach($order_detail as $dkey => $dvalue)
					{
							$order_data[$k]->$dkey =$dvalue;
						
					}
					
				}
			}
			else
			{
				echo "No Record Found";
			}
			return $order_data;
		}
		function get_order_status($period){
			
			// Get all order statuses
				$order_statuses = wc_get_order_statuses();

				
				$args  = $this->get_date_filter($period);


				$status_data = array();

				// Loop through each order status
				foreach ($order_statuses as $status_key => $status_label) {
					// Get orders for the current status
					
					// $orders = wc_get_orders(array(
					// 	'status' => $status_key,
					// ));


					$args  ["status"] = $status_key;
					$orders = wc_get_orders($args);

					// Calculate count and total for the current status
					$order_count = count($orders);
					$order_total = 0;

					foreach ($orders as $order) {
						$order_total += $order->get_total();
					}

					// Store the data for the current status in the array

					if ( $order_count > 0)
					$status_data[$status_key] = array(
						'order_count' => $order_count,
						'order_total' => $order_total,
						'order_status' => $status_key,
					);
				}

			return $status_data;
		}
		function get_payment_gateway($period){
			
			// Get all order statuses
			$payment_gateways = WC()->payment_gateways->payment_gateways();
	
			$args  = $this->get_date_filter($period);


			// Initialize an array to store counts and totals for each gateway
			$gateway_data = array();
			// Loop through each order status
			foreach ($payment_gateways as $gateway_id => $gateway) {
				// Get orders for the current status
				
				// $orders = wc_get_orders(array(
				// 	'status' => $status_key,
				// ));

				//$this->print_array($gateway->method_title);

				$args  ["payment_method"] = $gateway_id;
				$orders = wc_get_orders($args);

				// Calculate count and total for the current status
				$order_count = count($orders);
				$order_total = 0;

				foreach ($orders as $order) {
					$order_total += $order->get_total();
				}

				// Store the data for the current status in the array

				if ( $order_count > 0)
				$gateway_data[$gateway_id] = array(
					'order_count' => $order_count,
					'order_total' => $order_total,
					'payment_method_title' => $gateway->method_title,
				);
			}

		return $gateway_data;


		}
		function get_payment_gateway_deprecated(){
			global $wpdb;	
			$query = "
				SELECT 
				payment_method_title.meta_value as 'payment_method_title'
				
				,SUM(order_total.meta_value) as 'order_total'
				,count(*) as order_count
				FROM {$wpdb->prefix}posts as posts	";		
				
		
				
			$query .= "	LEFT JOIN  {$wpdb->prefix}postmeta as order_total ON order_total.post_id=posts.ID ";
			
			$query .= "	LEFT JOIN  {$wpdb->prefix}postmeta as payment_method_title ON payment_method_title.post_id=posts.ID ";
			
			
			$query .=	"WHERE 1=1 ";
				
			$query .= " AND posts.post_type ='shop_order' ";
			$query .= " AND order_total.meta_key ='_order_total' ";
			$query .= " AND payment_method_title.meta_key ='_payment_method_title' ";
			$query .= " AND  posts.post_status NOT IN ('trash')";
			$query .= " GROUP BY payment_method_title.meta_value";
			
			$data = $wpdb->get_results($query);	
			
			return $data;	
		}
		function get_sold_product_count($period){
			$args = array();
			$args  = $this->get_date_filter($period);

			//$this->print_array($args);
			

			$orders = wc_get_orders($args);
			$total_quantity_sold = 0;

			//$this->print_array($orders );


			// Loop through each order and calculate the total quantity sold
			foreach ($orders as $order) {
				foreach ($order->get_items() as $item_id => $item) {
					$total_quantity_sold += $item->get_quantity();
				}
			}

			return $total_quantity_sold;
			  
		}
		function get_sold_product_count_depreacted($start_date=NULL,$end_date =NULL){
			global $wpdb;
			$query = " SELECT  SUM(qty.meta_value) as sold_product_count  ";
			$query .= " FROM {$wpdb->prefix}posts as posts ";
			$query .= " LEFT JOIN  {$wpdb->prefix}woocommerce_order_items as line_item ON line_item.order_id=posts.ID  " ;
			
			 $query .= "  LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as qty ON qty.order_item_id=line_item.order_item_id  ";
			
			$query .= " WHERE 1=1 ";
			
			
			$query .= " AND posts.post_type ='shop_order' ";
			$query .= " AND qty.meta_key ='_qty' ";
			$query .= " AND line_item.order_item_type ='line_item' ";
			$query .= " AND posts.post_status IN ('wc-processing','wc-on-hold', 'wc-completed')";
		   /*Wooc Include refund item in sold product count*/
		  // $query .= " AND posts.post_status IN ('wc-processing','wc-on-hold', 'wc-completed','wc-refunded')";
		   $query .= " AND  posts.post_status NOT IN ('trash')";
		   
		  if ($start_date && $end_date){
			  $query .= ' AND date_format( posts.post_date, \'%\Y-%\m-%\d\') BETWEEN  \'%1$s\' AND \'%2$s\'';				
			  $results = $wpdb->get_var($wpdb->prepare($query,$start_date,$end_date));
		  }else{
			  $results = $wpdb->get_var( $query);	
		  }
		  $results = isset($results) ? $results : "0";	
		  return $results;
			
	  }
	  function get_total_discount($period){
			
			$args = array();
			$args  = $this->get_date_filter($period);

			$orders = wc_get_orders($args);
			$total_discount = 0;

			foreach ($orders as $order) {
				$order_discount = $order->get_discount_total();
				$total_discount += $order_discount;
			}
			return $total_discount;

	    }
		function get_total_discount_deprecated($start_date= NULL ,$end_date=NULL){
			global $wpdb;	
			$query = "";
			$query = " SELECT
				   
				   SUM(woocommerce_order_itemmeta.meta_value) as total_discount
				   
				   FROM {$wpdb->prefix}posts as posts			
				   LEFT JOIN  {$wpdb->prefix}woocommerce_order_items as woocommerce_order_items ON woocommerce_order_items.order_id=posts.ID 
				   
				   LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as woocommerce_order_itemmeta ON woocommerce_order_itemmeta.order_item_id=woocommerce_order_items.order_item_id 
				   
				   
				   WHERE 1=1
				   AND posts.post_type ='shop_order'  ";
				   
		   $query .= " AND woocommerce_order_items.order_item_type ='coupon' ";	
		   
		   $query .= " AND woocommerce_order_itemmeta.meta_key ='discount_amount' ";	
			   
		   $query .= " AND posts.post_status IN ('wc-processing','wc-on-hold', 'wc-completed') ";
		   $query .= " AND  posts.post_status NOT IN ('trash')";
		   if ($start_date && $end_date){
			   $query .= ' AND date_format( posts.post_date, \'%\Y-%\m-%\d\') BETWEEN  \'%1$s\' AND \'%2$s\'';				
			   $results = $wpdb->get_var($wpdb->prepare($query,$start_date,$end_date));
		   }else{
			   $results = $wpdb->get_var( $query);	
		   }
		   return $results ;
		   //$this->print_data($results);
	   }
		function get_total_tax($period="DAY"){
		
			$args = array();
			$args  = $this->get_date_filter($period);

			$orders = wc_get_orders($args);
			$total_tax = 0;

			foreach ($orders as $order) {
				$order_tax = $order->get_total_tax();
				$total_tax += $order_tax;
			}
			return $total_tax;
		 }
		
		 function get_order_detail($order_id){
			$order_detail	= get_post_meta($order_id);
			$order_detail_array = array();
			foreach($order_detail as $k => $v)
			{
				$k =substr($k,1);
				$order_detail_array[$k] =$v[0];
			}
			return 	$order_detail_array;
		}
		function get_total_tax_depreacated($start_date =NULL, $end_date=NULL){
			global $wpdb;	
			$query = "";
			//shipping_tax_amount
		   $query = " SELECT " ;
		   
		   //10.13		
		   $query .= "	(ROUND(SUM(woocommerce_order_itemmeta.meta_value),2)+  ROUND(SUM(shipping_tax_amount.meta_value),2)) as total_tax ";
		   
		   //10.12
		   //$query .= "	(SUM(ROUND(woocommerce_order_itemmeta.meta_value,2))+  SUM(ROUND(shipping_tax_amount.meta_value,2))) as total_tax ";
				   
			   $query .= "	 FROM {$wpdb->prefix}posts as posts			
				   LEFT JOIN  {$wpdb->prefix}woocommerce_order_items as woocommerce_order_items ON woocommerce_order_items.order_id=posts.ID 
				   
				   LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as woocommerce_order_itemmeta ON woocommerce_order_itemmeta.order_item_id=woocommerce_order_items.order_item_id 
				   
				   
				   LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as shipping_tax_amount ON shipping_tax_amount.order_item_id=woocommerce_order_items.order_item_id 
				   
				   
				   
				   WHERE 1=1
				   AND posts.post_type ='shop_order'  ";
				   
		   $query .= " AND woocommerce_order_items.order_item_type ='tax' ";	
		   
		   $query .= " AND woocommerce_order_itemmeta.meta_key ='tax_amount' ";
		   
		   $query .= " AND shipping_tax_amount.meta_key ='shipping_tax_amount' ";	
			   
		   //$query .= " AND posts.post_status IN ('wc-processing','wc-on-hold', 'wc-completed','wc-pending') ";
		   
		   $query .= " AND posts.post_status IN ('wc-processing','wc-on-hold', 'wc-completed')";
		   $query .= " AND  posts.post_status NOT IN ('trash')";
		   /*
		   if ($this->report_order_status ==""){
				   $query .= " AND posts.post_status IN ('wc-processing','wc-on-hold', 'wc-completed','wc-refunded')";
		   }else{
				$query .= " AND posts.post_status IN ('{$this->report_order_status}')";
		   }
		   */
		   
		   if ($start_date && $end_date){
			   $query .= ' AND date_format( posts.post_date, \'%\Y-%\m-%\d\') BETWEEN  \'%1$s\' AND \'%2$s\'';				
			   $results = $wpdb->get_var($wpdb->prepare($query,$start_date,$end_date));
		   }else{
			   $results = $wpdb->get_var( $query);	
		   }
		   
		   
		   return $results;
		   //$this->print_data($results);
		}   
		function get_customer($period){
			
			$args = array();
			$args  = $this->get_date_filter($period);

			$orders = wc_get_orders($args);
			$customer_ids = array();

			foreach ($orders as $order) {
				$customer_id = $order->get_customer_id();
			
				// Check if the customer ID is not empty and not already in the array
				if (!empty($customer_id) && !in_array($customer_id, $customer_ids)) {
					$customer_ids[] = $customer_id;
				}
			}
			
			$total_customers = count($customer_ids);

			return $total_customers;
		}
		function get_customer_deprecated($start_date =NULL, $end_date=NULL){
			global $wpdb;	
			$query = "";
			$query .= " SELECT COUNT(customer_user.meta_value) as count ";
			
			$query .= "	 FROM {$wpdb->prefix}posts as posts		";
			$query .= "	LEFT JOIN  {$wpdb->prefix}postmeta as customer_user ON customer_user.post_id=posts.ID ";
			$query .= "	WHERE 1=1 ";
			$query .= " AND posts.post_type ='shop_order'  ";
			$query .= " AND customer_user.meta_key ='_customer_user' ";	
			
			if ($start_date && $end_date){
			   $query .= ' AND date_format( posts.post_date, \'%\Y-%\m-%\d\') BETWEEN  \'%1$s\' AND \'%2$s\'';				
			   $query .= " AND customer_user.meta_value >0 ";
			   $query .= " AND  posts.post_status NOT IN ('trash')";
			   $row = $wpdb->get_var($wpdb->prepare($query,$start_date,$end_date));
			}else{
				$query .= " AND customer_user.meta_value >0 ";
			   $query .= " AND  posts.post_status NOT IN ('trash')";
			   $row = $wpdb->get_var($query);	
		   }
		   
		   return $row;
	   }

	   function get_guest_customer_deprecated($start_date =NULL, $end_date=NULL){
				global $wpdb;	
				$query = "";
				$query .= " SELECT COUNT(customer_user.meta_value) as count ";
				
				$query .= "	 FROM {$wpdb->prefix}posts as posts		";
				$query .= "	LEFT JOIN  {$wpdb->prefix}postmeta as customer_user ON customer_user.post_id=posts.ID ";
				$query .= "	WHERE 1=1 ";
				$query .= " AND posts.post_type ='shop_order'  ";
				$query .= " AND customer_user.meta_key ='_customer_user' ";	
				
				$query .= " AND customer_user.meta_value=0 ";
				$query .= " AND  posts.post_status NOT IN ('trash')";
			
				if ($start_date && $end_date){
				$query .= ' AND date_format( posts.post_date, \'%\Y-%\m-%\d\') BETWEEN  \'%1$s\' AND \'%2$s\'';				
				$row = $wpdb->get_var($wpdb->prepare($query,$start_date,$end_date));
				}else{
					$row = $wpdb->get_var($query);
				}
					
			
			
			//$this->print_data($row);
			return $row;
		}
		function get_guest_customer($period){
			$args = array();
			$args  = $this->get_date_filter($period);

			$orders = wc_get_orders($args);
			$total_guest_customers = 0;

			foreach ($orders as $order) {
				$customer_id = $order->get_customer_id();
			
				// If the order doesn't have an associated customer ID, it's a guest order
				if (empty($customer_id)) {
					$total_guest_customers++;
				}
			}
			
			return $total_guest_customers;
		}
		function get_low_in_stock(){
			global $wpdb;
			$row = array();
			$query = "";
			$stock   = absint( max( get_option( 'woocommerce_notify_low_stock_amount' ), 1 ) );
			$nostock = absint( max( get_option( 'woocommerce_notify_no_stock_amount' ), 0 ) );
		
			$query =  "SELECT COUNT( DISTINCT posts.ID ) as low_in_stock  FROM {$wpdb->prefix}posts as posts
			INNER JOIN {$wpdb->prefix}postmeta AS postmeta ON posts.ID = postmeta.post_id
			INNER JOIN {$wpdb->prefix}postmeta AS postmeta2 ON posts.ID = postmeta2.post_id
			WHERE 1=1
			AND posts.post_type IN ( 'product', 'product_variation' )
			AND posts.post_status = 'publish'
			AND postmeta2.meta_key = '_manage_stock' AND postmeta2.meta_value = 'yes'";
			
			$query .=  ' AND postmeta.meta_key = \'_stock\' AND CAST(postmeta.meta_value AS SIGNED) <= %1$d';
			$query .=  ' AND postmeta.meta_key = \'_stock\' AND CAST(postmeta.meta_value AS SIGNED) > %2$d';
			
			$row = $wpdb->get_var($wpdb->prepare($query,$stock,$nostock));
			
			//$this->print_data($wpdb);
			//$this->print_data($row);
			
		
			return $row;
			
		}
		function get_out_of_stock(){
			global $wpdb;
			$row = array();
			$query = "";
			$stock = absint( max( get_option( 'woocommerce_notify_no_stock_amount' ), 0 ) );
		
			$query =  "SELECT COUNT( DISTINCT posts.ID ) as out_of_stock FROM {$wpdb->prefix}posts as posts
			INNER JOIN {$wpdb->prefix}postmeta AS postmeta ON posts.ID = postmeta.post_id
			INNER JOIN {$wpdb->prefix}postmeta AS postmeta2 ON posts.ID = postmeta2.post_id
			WHERE 1=1
			AND posts.post_type IN ( 'product', 'product_variation' )
			AND posts.post_status = 'publish'
			AND postmeta2.meta_key = '_manage_stock' AND postmeta2.meta_value = 'yes'
			AND postmeta.meta_key = '_stock'";
			
			
			$query .=  '  AND CAST(postmeta.meta_value AS SIGNED) <= %1$d';
			
			$row = $wpdb->get_var($wpdb->prepare($query,$stock));
		
			return $row;
			
		}
		function get_most_stock(){
			global $wpdb;
			$row = array();
			$query = "";
			$stock = absint( max( get_option( 'woocommerce_notify_low_stock_amount' ), 0 ) );
		
			$query =  " SELECT COUNT( DISTINCT posts.ID ) FROM {$wpdb->prefix}posts as posts
			INNER JOIN {$wpdb->prefix}postmeta AS postmeta ON posts.ID = postmeta.post_id
			INNER JOIN {$wpdb->prefix}postmeta AS postmeta2 ON posts.ID = postmeta2.post_id
			WHERE 1=1
			AND posts.post_type IN ( 'product', 'product_variation' )
			AND posts.post_status = 'publish'
			AND postmeta2.meta_key = '_manage_stock' AND postmeta2.meta_value = 'yes'";
			
			$query .=  " AND postmeta.meta_key = '_stock' ";
			
			$query .=  '  AND CAST(postmeta.meta_value AS SIGNED) > %1$d';
			
			
			$row = $wpdb->get_var($wpdb->prepare($query,$stock));
		
			return $row;
			
		}
		function get_country_name($code)
		{	$name = "";
			if (strlen($code)>0){
				$name = isset(WC()->countries->countries[$code]) ? WC()->countries->countries[$code] : $code;	
				$name  = isset($name) ? $name : $code;
			}
			return $name;
		}
		function get_customer_report(){
			global $wpdb;
			$row = array();
			$query = "";
			$query = "SELECT
					SUM(order_total.meta_value)as 'order_total'
					,COUNT(*)as 'order_count'
					,billing_first_name.meta_value as billing_first_name
					,billing_email.meta_value as billing_email
					FROM {$wpdb->prefix}posts as posts			
					LEFT JOIN  {$wpdb->prefix}postmeta as order_total ON order_total.post_id=posts.ID 
					LEFT JOIN  {$wpdb->prefix}postmeta as billing_first_name ON billing_first_name.post_id=posts.ID 
					LEFT JOIN  {$wpdb->prefix}postmeta as billing_email ON billing_email.post_id=posts.ID 
					
					WHERE 1=1
					AND posts.post_type ='shop_order' 
					AND order_total.meta_key='_order_total' 
					AND billing_first_name.meta_key='_billing_first_name' 
					AND billing_email.meta_key='_billing_email' 
					AND  posts.post_status NOT IN ('trash')
					GROUP BY  billing_email.meta_value 
					
					ORDER BY SUM(order_total.meta_value) DESC
					
					LIMIT 5
					";
			$row = $wpdb->get_results($query);
			//$this->print_data($row );
			//$row = array();
			return $row;
		}
		function get_country_report(){

			$query = new WC_Order_Query(array() );
			$orders = $query->get_orders();
			//echo count($orders );
			//$this->print_array($orders );

			

			// Get all orders
			$all_orders = wc_get_orders(array());

			// Initialize an array to store order counts and totals for each country
			$country_data = array();

			// Loop through each order
			foreach ($all_orders as $order) {
				// Get the billing country for the order
				$billing_country = $order->get_billing_country();

				// If the billing country is not set, skip this order
				if (empty($billing_country)) {
					continue;
				}

				// Calculate the order total
				$order_total = $order->get_total();

				// Increment order count and add the order total to the corresponding country in the array
				if (isset($country_data[$billing_country])) {
					$country_data[$billing_country]['order_count']++;
					$country_data[$billing_country]['order_total'] += $order_total;
				} else {
					$country_data[$billing_country] = array(
						'order_count' => 1,
						'billing_country' =>$billing_country,
						'order_total' => $order_total,
					);
				}
			}

			// Sort the array in descending order by order counts
			uasort($country_data, function($a, $b) {
				return $b['order_count'] - $a['order_count'];
			});

			// Take only the top 5 countries
			$top_countries = array_slice($country_data, 0, 5);

			//echo count($all_orders );
			//$this->print_array($top_countries);
			return $top_countries;

		}
		function get_country_report_depreacted(){
			global $wpdb;
			$row = array();
			$query = "";
			$query = "SELECT
					SUM(order_total.meta_value)as 'order_total'
					,COUNT(*)as 'order_count'
					,billing_country.meta_value as billing_country
					FROM {$wpdb->prefix}posts as posts			
					LEFT JOIN  {$wpdb->prefix}postmeta as order_total ON order_total.post_id=posts.ID 
					LEFT JOIN  {$wpdb->prefix}postmeta as billing_country ON billing_country.post_id=posts.ID 
		
					
					WHERE 1=1
					AND posts.post_type ='shop_order' 
					AND order_total.meta_key='_order_total' 
					AND billing_country.meta_key='_billing_country' 
					AND  posts.post_status NOT IN ('trash')
					
					GROUP BY  billing_country.meta_value 
					
					ORDER BY SUM(order_total.meta_value) DESC
					
					LIMIT 5
					";
			$row = $wpdb->get_results($query);
			//$this->print_data($row );
			//$row = array();
			return $row;
		}
		function get_price($price =0)
		{	$new_price = 0;
			if ($price){
				$new_price = wc_price($price);
			}else{
				$new_price = wc_price($new_price);
			}
			//echo '<pre>',print_r($r,1),'</pre>';	
			return	$new_price;
		}
		
	}
}
?>