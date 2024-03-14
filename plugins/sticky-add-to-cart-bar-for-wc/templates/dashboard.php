<div class="wrap">
	<div class="title">
		<h1>Sticky Add To Cart Bar For WooCommerce Settings</h1>
		<div style="margin-top: 15px; margin-bottom: 15px;">We need your support to keep updating and improving the plugin. <a target="_blank" href="https://wordpress.org/support/plugin/sticky-add-to-cart-bar-for-wc/reviews/?filter=5#new-post"><b>Please, help us by leaving review <span class="dashicons  dashicons-star-filled" style="color:#dba617"></span><span class="dashicons  dashicons-star-filled" style="color:#dba617"></span><span class="dashicons  dashicons-star-filled" style="color:#dba617"></span><span class="dashicons  dashicons-star-filled" style="color:#dba617"></span><span class="dashicons  dashicons-star-filled" style="color:#dba617"></span></a></b> Thanks!</div>
		<?php settings_errors();  ?>
	</div>
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab-1">Settings</a></li>
		<li><a href="#tab-2">Appearance</a></li>
	</ul>
	<form id="pluginform" method="post" action="options.php">
		<div class="tab-content">
			<div id="tab-1" class="tab-pane active">
				<?php
				settings_fields('woo_sticky_cart_setting');
				echo "<h3>Settings for features.</h3>";
				echo '<table class="form-table">';
				do_settings_fields('woo_cart', 'settings');
				echo '</table>';
				?>
				<p class="submit"><input type="submit" name="submit" class="button button-primary" value="Save Changes"> <input type="submit" name="reset" class="button button-secondary" value="Reset Changes"></p>
			</div>
			<div id="tab-2" class="tab-pane">
				<?php
				echo "<h3>Settings for appearance.</h3>";
				echo '<table class="form-table">';
				do_settings_fields('woo_cart', 'appearance');
				echo '</table>';
				?>
				<p class="submit"><input type="submit" name="submit" class="button button-primary" value="Save Changes"> <input type="submit" name="reset" class="button button-secondary" value="Reset Changes"></p>
			</div>
		</div>    
	</form>
	<div class="author">
        <a href="https://addonsplus.com/downloads/woocommerce-sticky-add-to-cart-bar-pro/" target="_blank">
          <div class="rateus" id="rateus">
            <strong>View Pro Plugin,</strong><br/>
            Increase product page conversion.
          </div>
        </a>
        <a href="https://wordpress.org/support/plugin/sticky-add-to-cart-bar-for-wc/" target="_blank">
          <div class="support" id="support">
            <strong>Having Issues?,</strong><br/>
            Support on WordPress.org
          </div>
        </a>
        <a href="https://addonsplus.com/" target="_blank">
          <div class="addonsplus" id="addonsplus">
            <strong>Check our products,</strong><br/>
            Click here to get other addonsplus products.
          </div>
        </a>  
      </div>
</div>