<?php
if ( ! defined( 'ABSPATH' ) ) { exit;}
if( !class_exists( 'ni_enquiry_dashboard' ) ) :
class ni_enquiry_dashboard extends ni_enquiry_function{
	function __construct(){
	}
	function init(){
	$ni_count_settings 	= get_option('ni_enquiry_count_settings', array());	
	$total_count 		= isset($ni_count_settings["total_count"])?$ni_count_settings["total_count"]:0;
	$daily_counts 		= isset($ni_count_settings["daily_counts"][date_i18n("Y-m-d")])?$ni_count_settings["daily_counts"][date_i18n("Y-m-d")]:0; 
	?>
    <div class="container-fluid">
        <div id="niwoope">
        	<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
              <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3" aria-label="Slide 4"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="4" aria-label="Slide 5"></button>
               
              </div>
              <div class="carousel-inner">
                <div class="carousel-item active">
                 <div class="card bg-rgba-green-slight">
                            <div class="card-header bg-rgba-salmon-strong"> <?php _e('Monitor your sales and grow your online business with naziinfotech plugins', 'niwoope'); ?> </div>
                            <div class="card-body">
                                <h2 class="card-title text-center color-rgba-salmon-strong">Buy Ni Display Product Variation Table Pro $34.00</h2>
                               	<div class="row" style="font-size:16px">
                                	<div  class="col-md-6">
                                    	  <span class="font-weight-bold color-rgba-black-strong">Show variation product table on product detail page</span>	<br />	
                                        <span class="font-weight-bold color-rgba-black-strong">Show the variation dropdown on product page and category page</span>	<br />
                                        <span class="font-weight-bold color-rgba-black-strong">Show the variation product on shop page and category page</span>	<br />	
                                        <span class="font-weight-bold color-rgba-black-strong">Add to cart bulk quantity on product detail page in variation table</span>	<br />	
                                        <span class="font-weight-bold color-rgba-black-strong">Set the default quantity in variation table</span><br />
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
                                	<br />
                                    <br />
                                    <a href="http://demo.naziinfotech.com?demo_login=woo_sales_report" class="btn btn-rgba-salmon-strong btn-lg" target="_blank">View Demo</a>
                                    <a href="http://naziinfotech.com/?product=ni-woocommerce-sales-report-pro" target="_blank" class="btn btn-rgba-salmon-strong btn-lg">Buy Now</a>
                                    <br />
                                    <br />
                                    <br />
                                    <br />
                                </div>
                                 
                           </div>
                        </div>
                </div>
                <div class="carousel-item">
                  <div class="card bg-rgba-green-slight">
                            <div class="card-header bg-rgba-green-strong"> <?php _e('Monitor your sales and grow your online business with naziinfotech plugins', 'niwoope'); ?> </div>
                            <div class="card-body">
                                <h2 class="card-title text-center color-rgba-green-strong">Buy Ni WooCommerce Sales Report Pro $24.00</h2>
                               	<div class="row" style="font-size:16px">
                                	<div  class="col-md-3">
                                    	<span class="font-weight-bold color-rgba-black-strong">Dashboard order Summary</span><br />
                                        <span class="font-weight-bold color-rgba-black-strong">Order List - Display order list</span>	<br />
                                        <span class="font-weight-bold color-rgba-black-strong">Order Detail - Display Product information</span>	<br />	
                                        <span class="font-weight-bold color-rgba-black-strong">Sold Product variation Report</span>	<br />	
                                    </div>
                                    <div  class="col-md-3">
                                    	<span class="font-weight-bold color-rgba-black-strong">Customer Sales Report</span><br />
                                        <span class="font-weight-bold color-rgba-black-strong">Payment Gateway Sales Report</span>	<br />
                                        <span class="font-weight-bold color-rgba-black-strong">Country Sales Report</span>	<br />	
                                        <span class="font-weight-bold color-rgba-black-strong">Coupon Sales Report</span>	<br />	
                                    </div>
                                    <div  class="col-md-3">
                                    	<span class="font-weight-bold color-rgba-black-strong">Order Export To CSV</span><br />
                                        <span class="font-weight-bold color-rgba-black-strong">Custom Date Filter, Start Date and End Date</span>	<br />
                                        <span class="font-weight-bold color-rgba-black-strong">Product Center</span>	<br />	
                                        <span class="font-weight-bold color-rgba-black-strong">Customer center </span>	<br />	
                                    </div>
                                   <div  class="col-md-3">
                                   		<h5> <span class="font-weight-bold" >Coupon Code: <span class="text-warning">ni10</span>  Get 10% OFF</span></h5> 
                                        <span> <span class="font-weight-bold" >Email at:</span><a href="mailto:support@naziinfotech.com" target="_blank">support@naziinfotech.com</a></span><br />
                                        <span> <span class="font-weight-bold" >Website: </span><a href="http://naziinfotech.com/" target="_blank">www.naziinfotech.com</a></span>	<br />	
                                   </div>
                                </div>
                                <div class="text-center">
                               		 <br />
                                    <br />
                                    <a href="http://demo.naziinfotech.com?demo_login=woo_sales_report" class="btn btn-green-strong btn-lg" target="_blank">View Demo</a>
                                    <a href="http://naziinfotech.com/?product=ni-woocommerce-sales-report-pro" target="_blank" class="btn btn-green-strong btn-lg">Buy Now</a>
                                     <br />
                                    <br />
                                    <br />
                                    <br />
                                </div>
                                 
                           </div>
                        </div>
                </div>
                <div class="carousel-item">
                  <div class="card bg-rgba-cyan-slight">
                            <div class="card-header bg-rgba-cyan-strong"> <?php _e('Monitor your sales and grow your online business with naziinfotech plugins', 'niwoope'); ?> </div>
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
                                	<br />
                                    <br />
                                    <a href="http://demo.naziinfotech.com/?demo_login=woo_cost_of_goods" class="btn btn-rgba-cyan-strong btn-lg" target="_blank">View Demo</a>
                                    <a href="http://naziinfotech.com/product/ni-woocommerce-cost-of-good-pro/" target="_blank" class="btn btn-rgba-cyan-strong btn-lg">Buy Now</a>
                                     <br />
                                    <br />
                                    <br />
                                    <br />
                                </div>
                                 
                           </div>
                        </div>
                </div>
                <div class="carousel-item">
                  <div class="card bg-rgba-indigo-slight">
                            <div class="card-header bg-rgba-indigo-strong"> <?php _e('Monitor your sales and grow your online business with naziinfotech plugins', 'niwoope'); ?> </div>
                            <div class="card-body">
                                <h2 class="card-title text-center color-rgba-indigo-strong"> <?php _e('Buy Ni WooCommerce Product Enquiry Pro @ $24.00', 'niwoope'); ?> </h2>
                               	<div class="row" style="font-size:16px">
                                	<div  class="col-md-3">
                                    	<span class="font-weight-bold color-rgba-black-strong"><?php esc_html_e('Dashboard Summary (Today, Total Enquiry)', 'niwoope'); ?></span><br />
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
                                	<br />
                                    <br />
                                    <a href="http://demo.naziinfotech.com/enquiry-demo/" class="btn btn-rgba-indigo-strong btn-lg" target="_blank">View Demo</a>
                                    <a href="http://naziinfotech.com/product/ni-woocommerce-product-enquiry-pro/" target="_blank" class="btn btn-rgba-indigo-strong btn-lg">Buy Now</a>
                                      <br />
                                    <br />
                                    <br />
                                    <br />
                                </div>
                                 
                           </div>
                        </div>
                </div>
                 <div class="carousel-item">
                  <div class="card bg-rgba-blue-slight">
                            <div class="card-header bg-rgba-blue-strong"> <?php _e('Monitor your sales and grow your online business with naziinfotech plugins', 'niwoope'); ?> </div>
                            <div class="card-body">
                                <h2 class="card-title text-center color-rgba-blue-strong"> <?php _e('Ni One Page Inventory Management System For WooCommerce', 'niwoope'); ?> </h2>
                               	<div class="row" style="font-size:16px">
                                	<div  class="col-md-3">
                                    	<span class="font-weight-bold color-rgba-black-strong"><?php esc_html_e('Dashboard Summary stock status', 'niwoope'); ?></span><br />
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
                                  <br />
                                    <br />
                                    <a href="https://wordpress.org/plugins/ni-one-page-inventory-management-system-for-woocommerce/" class="btn btn-rgba-blue-strong btn-lg" target="_blank">View</a>
                                    <a href="https://downloads.wordpress.org/plugin/ni-one-page-inventory-management-system-for-woocommerce.zip" target="_blank" class="btn btn-rgba-blue-strong btn-lg">Download</a>
                                     <br />
                                    <br />
                                    <br />
                                    <br />
                                </div>
                                 
                           </div>
                        </div>
                </div>
              </div>
              <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
            </div>
        
            <div class="row" >
             	<div class="col">
                	<div class="card">
                      <div class="card-body">
                      <h5> We will develop a <span class="text-success" style="font-size:26px;">New</span> WordPress and WooCommerce <span class="text-success" style="font-size:26px;">plugin</span> and customize or modify  the <span class="text-success" style="font-size:26px;">existing</span> plugin, if yourequire any <span class="text-success" style="font-size:26px;"> customization</span>  in WordPress and WooCommerce then please <span class="text-success" style="font-size:26px;">contact us</span> at: <a href="mailto:support@naziinfotech.com">support@naziinfotech.com</a>.</h5>
                      </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-2">
                    <div class="card" style="max-width:99%">
                      <div class="card-header bg-rgba-green-strong box1">
                        <?php esc_html_e("Total Enquiry","niwoope"); ?>
                      </div>
                      <div class="card-body text-center">
                        <span style="font-size:20px; font-weight:bold" class="box1"> <?php esc_html_e($total_count ); ?></span>
                      </div>
                    </div>
                </div>
                <div class="col-2">
                    <div class="card" style="max-width:99%">
                      <div class="card-header bg-rgba-blue-strong">
                        <?php esc_html_e("Today Enquiry","niwoope"); ?>
                      </div>
                      <div class="card-body text-center">
                       <span style="font-size:20px; font-weight:bold" class="box2"> <?php esc_html_e($daily_counts ); ?></span>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php		
	}	
	
}
endif;
?>