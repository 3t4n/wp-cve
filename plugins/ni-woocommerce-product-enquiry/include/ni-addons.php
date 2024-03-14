<?php
if ( ! defined( 'ABSPATH' ) ) { exit;}
if( !class_exists( 'ni_enquiry_addons' ) ) {
	class ni_enquiry_addons{
		public function __construct(){
			
		}
		function page_init(){
		?>
      <div class="container-fluid" id="niwoope">
        	 <div class="row">
             	<div class="col-md-12"  style="padding:0px;">
					<div class="card" style="max-width:1000% ">
						<div class="card-header bg-rgba-cyan-strong">
							<?php _e(  'Hire us for plugin Development and Customization', 'niwoope') ?>
						</div>
						<div class="card-body">
							 <p>
							<?php _e(  ' Our area of expertise is WordPress and custom plugins development. We specialize in creating custom plugin solutions for your business needs', 'niwoope') ?>
                           .</p>
                            <p><?php _e(  'Email us', 'niwoope') ?>: <strong><a href="mailto:support@naziinfotech.com">support@naziinfotech.com</a></strong></p>
                          
                            <div class="row">
                            	<div class="col-md-12">
                                <h3  class="box4"><?php _e(  'Our Other Free Wordpress Plugins', 'niwoope') ?></h3>
                                </div>
                            </div>
                            <div class="row">
                            	<div class="col-md-4">
                                	<h6 class="text-success">Ni WooCommerce Cost Of Goods</h6>
                                    <ul class="list-group" >
                                    	<li class="list-group-item">Ability to add the cost of goods for simple and variation product</li>
                                    	<li class="list-group-item">Dashboard report provide the total, monthly, yearly and daily sales amount, sales count, tax, and coupon</li>
                                    	<li class="list-group-item">Show sold product profit report</li>
                                        <li class="list-group-item"><a href="https://wordpress.org/plugins/ni-woocommerce-cost-of-goods" target="_blank">View</a> 
                        				<a href="https://downloads.wordpress.org/plugin/ni-woocommerce-cost-of-goods.zip" target="_blank">Download</a> </li>
                        			</ul>
                                </div>
                                <div class="col-md-4">
                                <h6 class="text-success">Ni WooCommerce Custom Order Status</h6>
                                	<ul class="list-group">
                                        <li class="list-group-item">Add/Edit/Delete new WooCommerce order status</li>
                                        <li class="list-group-item">Set Color to the order status</li>
                                        <li class="list-group-item">Display order status list</li>
                                        <li class="list-group-item">Add order status slug </li>
                                        <li class="list-group-item"> <a href="https://wordpress.org/plugins/ni-woocommerce-custom-order-status" target="_blank">View</a> 
                                    <a href="https://downloads.wordpress.org/plugin/ni-woocommerce-custom-order-status.zip" target="_blank">Download</a></li>
                                    </ul>
                                </div>
                                <div class="col-md-4">
                                 <h6 class="text-success">Ni Woocommerce Product Enquiry </h6>
                                
                        			<ul class="list-group">
                      					<li class="list-group-item">Display simple enquiry dashboard </li>
                            			<li class="list-group-item">Email Setting option</li>
                      					<li class="list-group-item">Display enquiry form on the product page </li>
                            			<li class="list-group-item">Send email to client or admin</li>
                                        <li class="list-group-item"> <a href="https://wordpress.org/plugins/ni-woocommerce-product-enquiry" target="_blank">View</a> 
                        <a href="https://downloads.wordpress.org/plugin/ni-woocommerce-product-enquiry.zip" target="_blank">Download</a></li>
                    				</ul>
                       
                                </div>
                                <div class="col-md-4">
                                	<h6 class="text-success">Ni WooCommerce Order Export</h6>
                                    <ul class="list-group">
                                        <li class="list-group-item">Show the order summary on dashboard </li>
                                        <li class="list-group-item">Export customer billing details like billing name,customer email address, billing address details etc.</li>
                                        <li class="list-group-item">Order Status Report</li>
                                        <li class="list-group-item">Payment Gateway report</li>
                                        <li class="list-group-item"> <a href="https://wordpress.org/plugins/ni-woocommerce-sales-report-email" target="_blank">View</a> 
                                    <a href="https://downloads.wordpress.org/plugin/ni-woocommerce-sales-report-email.zip" target="_blank">Download</a> </li>
                                    </ul>
                                   
                                </div>
                                <div class="col-md-4">
                                <h6 class="text-success">Ni WooCommerce Dashboard Report</h6>
                                <ul class="list-group">
                                    <li class="list-group-item">Display the sales summary on WordPress admin dashboard.</li>
                                    <li class="list-group-item">Display the recent order on dashboard </li>
                                    <li class="list-group-item">Order status summary report</li>
                                    <li class="list-group-item">Show the sales analysis report on dashboard</li>
                                    <li class="list-group-item"><a href="https://wordpress.org/plugins/ni-woocommerce-invoice" target="_blank">View</a> 
                                    <a href="https://downloads.wordpress.org/plugin/ni-woocommerce-invoice.zip" target="_blank">Download</a> </li>
                                </ul>
                        	
                                </div>
                                <div class="col-md-4">
                                	<h6 class="text-success">Ni WooCommerce Sales Report By User Role</h6>
                                    <ul class="list-group">
                                        <li>Ability to create new sales agent or sales person</li>
                                        <li>Assign order to sales agent or vendor</li>
                                        <li>Display the list of sales order with sales agent or <strong>sales person</strong> name</li>
                                        <li>Filter the sales order by sales person or sales agent.</li>
                                         <li class="list-group-item"><a href="https://wordpress.org/plugins/ni-woocommerce-sales-report-by-user-role/" target="_blank">View</a> 
                                    <a href="https://downloads.wordpress.org/plugin/ni-woocommerce-sales-report-by-user-role.zip" target="_blank">Download</a> </li>
                                    </ul>
                        
                                </div>
                                <div class="col-md-4">
                                 	<h6 class="text-success">Ni WooCommerce Sales Report Email</h6>
                                    <ul class="list-group">
                                        <li class="list-group-item">Display simple sales dashboard </li>
                                        <li class="list-group-item">Automatically email the daily sales report.</li>
                                        <li class="list-group-item">Email WooCommerce sales order list</li>
                                        <li class="list-group-item">Email setting option and enable/disable cron job</li>
                                        <li class="list-group-item"> <a href="https://wordpress.org/plugins/ni-woocommerce-sales-report-email" target="_blank">View</a> 
                                    <a href="https://downloads.wordpress.org/plugin/ni-woocommerce-sales-report-email.zip" target="_blank">Download</a> </li>
                                    </ul>
                       
                                </div>
                                <div class="col-md-4">
                                	<h6 class="text-success">Ni WooCommerce Product Editor</h6>
                                    <ul class="list-group">
                                        <li class="list-group-item">Display simple, variable and variation product in tabular format</li>
                                        <li class="list-group-item">Provide the product filter by product name, mange backorder </li>
                                        <li class="list-group-item">Ajax pagination and Ajax filter and Ajax data saving for better user experience </li>
                                        <li class="list-group-item"> <a href="https://wordpress.org/plugins/ni-woocommerce-product-editor" target="_blank">View</a> 
                                    <a href="https://downloads.wordpress.org/plugin/ni-woocommerce-product-editor.zip" target="_blank">Download</a> </li>
                                    </ul>
                       
                                </div>
                                
                                <div class="col-md-4">
                                	<h6 class="text-success">Ni One Page Inventory Management System For WooCommerce</h6>
                                    <ul class="list-group">
                                        <li class="list-group-item">PURCHASE ORDER SYSTEM</li>
                                        <li class="list-group-item">MULTI-LOCATION PURCHASE ORDER</li>
                                        <li class="list-group-item">INVENTORY MANAGEMENT</li>
                                        <li class="list-group-item">MANAGE PRODUCT</li>
                                        
                                        <li class="list-group-item"> <a href="https://wordpress.org/plugins/ni-one-page-inventory-management-system-for-woocommerce/" target="_blank">View</a> 
                                    <a href="https://downloads.wordpress.org/plugin/ni-one-page-inventory-management-system-for-woocommerce.zip" target="_blank">Download</a> </li>
                                    </ul>
                       
                                </div>
                                
                            </div>
                            <div class="row">
                            	<div class="col-md-12">
                                <h3 class="box4"><?php _e(  'All Free Plugins', 'niwoope') ?></h3>
                                	<ul class="list-group">
	
    <li class="list-group-item"><a href="https://wordpress.org/plugins/ni-woocommerce-cost-of-goods/"  class="ni_other_plugin_link" target="_blank">Ni WooCommerce Cost Of Goods</a>  </li>
    <li class="list-group-item"><a href="https://wordpress.org/plugins/ni-woocommerce-product-enquiry/"  class="ni_other_plugin_link" target="_blank">Ni WooCommerce Product Enquiry</a>  </li>
    <li class="list-group-item"><a href="https://wordpress.org/plugins/ni-woocommerce-payment-gateway-charges/"  class="ni_other_plugin_link" target="_blank">Ni WooCommerce Payment Gateway Charges</a>  </li>
    <li class="list-group-item"><a href="https://wordpress.org/plugins/ni-woocommerce-custom-order-status/"  class="ni_other_plugin_link" target="_blank">Ni WooCommerce Custom Order Status</a>  </li>
    <li class="list-group-item"><a href="https://wordpress.org/plugins/ni-woocommerce-product-variations-table/"  class="ni_other_plugin_link" target="_blank">Ni WooCommerce Product Variations Table</a>  </li>
    <li class="list-group-item"><a href="https://wordpress.org/plugins/ni-woocommerce-sales-report-by-user-role/"  class="ni_other_plugin_link" target="_blank">Ni WooCommerce Sales Report By User Role</a>  </li>
    <li class="list-group-item"><a href="https://wordpress.org/plugins/ni-woocommerce-product-editor/"  class="ni_other_plugin_link" target="_blank">Ni WooCommerce Product Editor</a>  </li>
    <li class="list-group-item"><a href="https://wordpress.org/plugins/ni-woocommerce-order-export/"  class="ni_other_plugin_link" target="_blank">Ni WooCommerce Order Export</a>  </li>
    <li class="list-group-item"><a href="https://wordpress.org/plugins/ni-woocommerce-dashboard-report/"  class="ni_other_plugin_link" target="_blank">Ni WooCommerce Dashboard Sales Report</a>  </li>
    <li class="list-group-item"><a href="https://wordpress.org/plugins/ni-woocommerce-sales-report-email/"  class="ni_other_plugin_link" target="_blank">Ni WooCommerce Sales Report Email</a>  </li>
    <li class="list-group-item"><a href="https://wordpress.org/plugins/ni-woocommerce-admin-order-columns/"  class="ni_other_plugin_link" target="_blank">Ni WooCommerce Admin Order Columns</a>  </li>
    <li class="list-group-item"><a href="https://wordpress.org/plugins/ni-woocommerce-invoice/"  class="ni_other_plugin_link" target="_blank">Ni WooCommerce Invoice</a>  </li>
    <li class="list-group-item"><a href="https://wordpress.org/plugins/ni-crm-lead/"  class="ni_other_plugin_link" target="_blank">Ni CRM Lead</a>  </li>
    <li class="list-group-item"><a href="https://wordpress.org/plugins/ni-woocommerce-order-delivery/"  class="ni_other_plugin_link" target="_blank">Ni WooCommerce Order Delivery</a>  </li>
    <li class="list-group-item"><a href="https://wordpress.org/plugins/ni-woocommerce-stock/"  class="ni_other_plugin_link" target="_blank">Ni WooCommerce Stock</a>  </li>   
    <li class="list-group-item"><a href="https://wordpress.org/plugins/ni-woocommerce-product-editor/"  class="ni_other_plugin_link" target="_blank">Ni WooCommerce Product Editor</a>  </li>
    <li class="list-group-item"><a href="https://wordpress.org/plugins/ni-woocommerce-multi-currency-report/"  class="ni_other_plugin_link" target="_blank">WooCommerce Multi Currency Report</a>  </li>
    <li class="list-group-item"><a href="https://wordpress.org/plugins/ni-woo-sales-commission/"  class="ni_other_plugin_link" target="_blank">Ni Sales Commission For WooCommerce</a>  </li>
    <li class="list-group-item"><a href="https://wordpress.org/plugins/ni-one-page-inventory-management-system-for-woocommerce/"  class="ni_other_plugin_link" target="_blank">Ni One Page Inventory Management System For WooCommerce</a>  </li>
</ul>
                                </div>
                            </div>
						</div>
					</div>
				</div>
            </div>
        </div>
        <?php	
		}
		function page_init1(){
		?>
        <style>
        .ni-container-addons {
			width:98%;
	 		margin: auto;
	 		background-color:#FFF;
	 		margin-top:10px;
		}
		.ni-container-addons .ni-addons-content { 
			width:100%;  
			margin: 0 auto;
		}
		.ni-container-addons  .ni-addons-content .ni-addons-row {
		  overflow:hidden;
		}
		
		.ni-container-addons .ni-addons-row .ni-addons-column {
		  width:300px;
		  float:left;
		  margin:10px;
		  padding:10px;
		  position: relative;
		  
		}
		.ni-container-addons .ni-addons-row .ni-column-height {
			height:200px;
			border:2px solid #00BCD4;
		    
		}
		.ni-addons-column .ni-addons-lable { 
			font-weight:bold;
			font-size:16px;
			border-bottom:1px solid #00BCD4; 
			padding-bottom:5px
		 }
        </style>
        <div class="ni-container-addons">
        	<div class="ni-addons-content">
                <div class="ni-addons-row">
                	<div class="ni-addons-column" style="width:100%;">
                    <div  style="width:100%;text-align:center; font-size:24px;"><strong>Hire us for plugin Development and Customization</strong></div>
                    <p>Our area of expertise is WordPress and custom plugins development. We specialize in creating custom plugin solutions for your business needs.</p>
                    <p>Email us: <strong><a href="mailto:support@naziinfotech.com">support@naziinfotech.com</a></strong></p>
                    <p style="font-weight:bold; font-size:24px; margin:0;">Our Other Free Wordpress Plugins</p>
                	</div>
                </div>
                <div style="clear:both"></div>
                <div class="ni-addons-row">
                    <div class="ni-addons-column ni-column-height">
                        <div class="ni-addons-lable">Ni WooCommerce Sales Report</div>
                        <div class="ni-addons-value">
                        	<ul>
                      		<li><strong>Display simple sales dashboard </strong></li>
                            <li><strong>Filter sales order product by date range</strong></li>
                      		<li><strong>Print WooCommerce sales order list</strong></li>
                            <li><strong>Display sales order list</strong></li>
                    	</ul>
                        <a href="https://wordpress.org/plugins/ni-woocommerce-sales-report" target="_blank">View</a> 
                        <a href="https://downloads.wordpress.org/plugin/ni-woocommerce-sales-report.zip" target="_blank">Download</a> 
                        </div>
                    </div>
                    <div class="ni-addons-column ni-column-height">
                        <div class="ni-addons-lable">Ni WooCommerce Custom Order Status</div>
                        <div class="ni-addons-value">
                        <ul>
                      		<li><strong>Add/Edit/Delete new WooCommerce order status</strong></li>
                      		<li><strong>Set Color to the order status</strong></li>
                            <li><strong>Display order status list</strong></li>
                            <li><strong>Add order status slug </strong></li>
                    	</ul>
                        <a href="https://wordpress.org/plugins/ni-woocommerce-custom-order-status" target="_blank">View</a> 
                        <a href="https://downloads.wordpress.org/plugin/ni-woocommerce-custom-order-status.zip" target="_blank">Download</a>
                         </div>
                    </div>
                    <div class="ni-addons-column ni-column-height">
                        <div class="ni-addons-lable">Ni Woocommerce Product Enquiry</div>
                        <div class="ni-addons-value">
                        	<ul>
                      		<li><strong>Display simple enquiry dashboard </strong></li>
                            <li><strong>Email Setting option</strong></li>
                      		<li><strong>Display enquiry form on the product page </strong></li>
                            <li><strong>Send email to client or admin</strong></li>
                    	</ul>
                        <a href="https://wordpress.org/plugins/ni-woocommerce-product-enquiry" target="_blank">View</a> 
                        <a href="https://downloads.wordpress.org/plugin/ni-woocommerce-product-enquiry.zip" target="_blank">Download</a>
                    </div>
                </div>
           		<div style="clear:both"></div>
            </div>
            	<div style="clear:both"></div>
                <div class="ni-addons-row">
                    <div class="ni-addons-column ni-column-height">
                        <div class="ni-addons-lable">Ni WooCommerce Sales Report Email</div>
                        <div class="ni-addons-value">
                        	<ul>
                      		<li><strong>Display simple sales dashboard </strong></li>
                            <li><strong>Automatically email the daily sales report.</strong></li>
                      		<li><strong>Email WooCommerce sales order list</strong></li>
                            <li><strong>Email setting option and enable/disable cron job</strong></li>
                    	</ul>
                        <a href="https://wordpress.org/plugins/ni-woocommerce-sales-report-email" target="_blank">View</a> 
                        <a href="https://downloads.wordpress.org/plugin/ni-woocommerce-sales-report-email.zip" target="_blank">Download</a> 
                        </div>
                    </div>
                    <div class="ni-addons-column ni-column-height">
                        <div class="ni-addons-lable">Ni WooCommerce Invoice</div>
                        <div class="ni-addons-value">
                        <ul>
                      		<li><strong>Filter sales order by date range</strong></li>
                      		<li><strong>Export sales order invoice PDF </strong></li>
                            <li><strong>Display sales order list</strong></li>
                            <li><strong>Setting option for store name and footer notes</strong></li>
                    	</ul>
                        	<a href="https://wordpress.org/plugins/ni-woocommerce-invoice" target="_blank">View</a> 
                        	<a href="https://downloads.wordpress.org/plugin/ni-woocommerce-invoice.zip" target="_blank">Download</a> a>
                         </div>
                    </div>
                    <div class="ni-addons-column ni-column-height">
                        <div class="ni-addons-lable">Ni CRM Lead</div>
                        <div class="ni-addons-value">
                        <ul>
                      		<li>Add/Edit/update and delete New Lead</li>
                      		<li>Display the lead list</li>
                            <li>Add Update, Delete Follow Up</li>
                            <li>Add, Delete, Service, Product and status</li>
                    	</ul>
                        <a href="https://wordpress.org/plugins/ni-crm-lead" target="_blank">View</a> 
                        <a href="https://downloads.wordpress.org/plugin/ni-crm-lead.zip" target="_blank">Download</a> 
                    </div>
                </div>
           		<div style="clear:both"></div>
            </div>	
            
            <h3>All Free Plugins</h3>
                <div>
                	<ul class="ni_other_plugin_link_ul">
	<li><a href="https://wordpress.org/plugins/ni-woocommerce-sales-report/" class="ni_other_plugin_link" target="_blank">Ni WooCommerce Sales Report</a>  </li>
    <li><a href="https://wordpress.org/plugins/ni-woocommerce-product-enquiry/"  class="ni_other_plugin_link" target="_blank">Ni WooCommerce Product Enquiry</a>  </li>
    <li><a href="https://wordpress.org/plugins/ni-woocommerce-payment-gateway-charges/"  class="ni_other_plugin_link" target="_blank">Ni WooCommerce Payment Gateway Charges</a>  </li>
    <li><a href="https://wordpress.org/plugins/ni-woocommerce-custom-order-status/"  class="ni_other_plugin_link" target="_blank">Ni WooCommerce Custom Order Status</a>  </li>
    <li><a href="https://wordpress.org/plugins/ni-woocommerce-product-variations-table/"  class="ni_other_plugin_link" target="_blank">Ni WooCommerce Product Variations Table</a>  </li>
    <li><a href="https://wordpress.org/plugins/ni-woocommerce-sales-report-by-user-role/"  class="ni_other_plugin_link" target="_blank">Ni WooCommerce Sales Report By User Role</a>  </li>
    <li><a href="https://wordpress.org/plugins/ni-woocommerce-product-editor/"  class="ni_other_plugin_link" target="_blank">Ni WooCommerce Product Editor</a>  </li>
    <li><a href="https://wordpress.org/plugins/ni-woocommerce-order-export/"  class="ni_other_plugin_link" target="_blank">Ni WooCommerce Order Export</a>  </li>
    <li><a href="https://wordpress.org/plugins/ni-woocommerce-dashboard-report/"  class="ni_other_plugin_link" target="_blank">Ni WooCommerce Dashboard Sales Report</a>  </li>
    <li><a href="https://wordpress.org/plugins/ni-woocommerce-sales-report-email/"  class="ni_other_plugin_link" target="_blank">Ni WooCommerce Sales Report Email</a>  </li>
    <li><a href="https://wordpress.org/plugins/ni-woocommerce-admin-order-columns/"  class="ni_other_plugin_link" target="_blank">Ni WooCommerce Admin Order Columns</a>  </li>
    <li><a href="https://wordpress.org/plugins/ni-woocommerce-invoice/"  class="ni_other_plugin_link" target="_blank">Ni WooCommerce Invoice</a>  </li>
    <li><a href="https://wordpress.org/plugins/ni-crm-lead/"  class="ni_other_plugin_link" target="_blank">Ni CRM Lead</a>  </li>
    <li><a href="https://wordpress.org/plugins/ni-woocommerce-order-delivery/"  class="ni_other_plugin_link" target="_blank">Ni WooCommerce Order Delivery</a>  </li>
    <li><a href="https://wordpress.org/plugins/ni-woocommerce-stock/"  class="ni_other_plugin_link" target="_blank">Ni WooCommerce Stock</a>  </li>
    <li><a href="https://wordpress.org/plugins/ni-woocommerce-customer-product-report/"  class="ni_other_plugin_link" target="_blank">Ni WooCommerce Customer Product Report</a>  </li>
    <li><a href="https://wordpress.org/plugins/ni-woocommerce-product-editor/"  class="ni_other_plugin_link" target="_blank">Ni WooCommerce Product Editor</a>  </li>
    
</ul>
                </div>	    
                
        </div>	
        <?php	
		}
	}
}
?>