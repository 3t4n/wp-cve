<?php
define('PINTEREST_PRODUCT_CATALOGS_VERSION', '1.0.4');
define('PINTEREST_PRODUCT_CATALOGS_PLUGIN_URL', plugin_dir_url( __FILE__ ));
define('PINTEREST_PRODUCT_CATALOGS_PLUGIN_ADMIN_FILE', plugin_dir_path(__FILE__)."index.php" );

function pinterest_product_catalogs_admin_menu() {
    add_options_page( 'Pinterest Product Catalogs Plugin Options', 
    'Product Feed for Pinterest Product Catalogs', 
    'manage_options', 
    'pinterest-product-catalogs-admin-options', 
    'pinterest_product_catalogs_options' );
}
add_action( 'admin_menu', 'pinterest_product_catalogs_admin_menu' );

function pinterest_product_catalogs_options(){
	
    ?>

<div>
        <h1>Product Feed for Pinterest Product Catalogs</h1>
		<p>Product RSS Feed for 'Pinterest Product Catalogs'. Automatically pin the products on your website by posting information such as photo, price, stock status, product description in your pinterest account.</p>
	</div>
	
	<div>
		<h3>Your feed link:</h3>
		<a href="<?php echo site_url() ?>?call_pinterest_product_catalogs=1" target="_blank">
			<?php echo site_url() ?>?call_pinterest_product_catalogs=1
		</a>
		<div>
		<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
		<div class="w3-panel w3-card w3-light-grey"> 
			<h4>Example</h4>
			<div class="w3-code htmlHigh notranslate">
				&lt;channel&gt;<br>
				&lt;item&gt;<br>
				&lt;id&gt;1234&lt;/id&gt;<br>
				&lt;title&gt;Grey Travel Bag&lt;/title&gt;<br>
				&lt;description&gt;Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.&lt;/description&gt;<br>
				&lt;link&gt;http://localhost/wordpress/product/grey-travel-bag/&lt;/link&gt;<br>
				&lt;image_link&gt;http://localhost/wordpress/wp-content/uploads/2018/04/product-73.jpg&lt;/image_link&gt;<br>
				&lt;g:availability&gt;in stock&lt;/g:availability&gt;<br>
				&lt;g:condition&gt;New&lt;/g:condition&gt;<br>
				&lt;sale_price&gt;19.59&lt;/sale_price&gt;<br>
				&lt;g:price&gt;23.59&lt;/g:price&gt;<br>
				&lt;/item&gt;<br>
				&lt;channel&gt;
			</div>
			</div>
		</div>
	</div>
    
   <?php
}

