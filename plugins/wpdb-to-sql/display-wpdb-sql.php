<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap" style="float: left;display: contents;">
	<h2><?php echo esc_html(get_admin_page_title()); ?></h2>
	<div class="inner-wrap"  style="width: 70%;display: block;float: left;">
		<?php 
			if(isset($_GET['status']) && $_GET['status'] == 'noselect'){
				echo '<div id="message" class="error wordpress-message"><p> Please select any option from drop-down. </p></div>';
			}
		?>
	    <form method="post" name="wpdb_sql_options" action="<?php echo admin_url('tools.php?page=wpdb-sql&wpdb_sql=download');  ?>">
	    	<table>
	    		<tr>
	    			
	    		</tr>
	    	<tr>
    			<td><label for="opt_select">Backup Mode</label></td>
    			<td><select name="opt_select" id="opt_select">
    			<option value="-1"  selected="">Select an option</option>
	    		<option value="text">Open in Browser</option>
	    		<option value="sql">Download SQL file</option>
	    	</select></td>	
	    	</tr>
	    	<tr>
	    		<td colspan="2">
	    			<?php submit_button('Download Database', 'primary','submit', TRUE); ?>
	    		</td>
	    	</tr>
	     	</table>
	    </form>
	</div>

	<div class="wp-message" style="width: 30%;float: left;text-align: center;-webkit-box-shadow: 3px 1px 36px 11px rgba(0,0,0,0.32);
-moz-box-shadow: 3px 1px 36px 11px rgba(0,0,0,0.32);
box-shadow: 3px 1px 36px 11px rgba(0,0,0,0.32);">
		<h1>Plugin Designed and Developed by</h1>
		<a href="http://ecodeblog.com/" target="_blank"><img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/ecodeblog.png'; ?>" alt="Niket Joshi" style="border: 1px solid #ddd;border-radius: 4px;padding: 5px;width: 150px;" /></a>
		<h2><a href="https://www.google.com/search?q=niket+joshi&rlz=1C1GCEU_enIN839IN839&oq=niket+joshi"><br/>Niket Joshi</a></h2><h4>INDIA</h4><hr style="width: 20%;">
		<p>For more plugin you can contact me on following!</p>
		<p>Email address: <a href="mailto:joshiniket14@gmail.com">joshiniket14@gmail.com</a></p>
		<p>Skype ID: <a href="skype:joshiniket14?call">live:joshiniket14</a></p>
		<hr style="width: 80%;">
		<h3 style="font-weight: bolder;">More Plugin</h3>
		<div style="display: flex;align-items: center;justify-content: center;">
			<div style="padding: 10px;">
				<img src="https://ps.w.org/wpdb-to-sql/assets/icon-256x256.png" alt="WPDB to SQL" width="100px" height="100px">
				<h4>
					<a href="https://wordpress.org/plugins/wpdb-to-sql/" target="_blank">WPDB to SQL</a>
				</h4>
			</div>
			<div style="padding: 10px;">
				<img src="https://ps.w.org/woo-price-drop-alert/assets/icon-256x256.png" alt="Woo Price Drop Alert" width="100px" height="100px"><br/>
				<h4><a href="https://wordpress.org/plugins/woo-price-drop-alert/" target="_blank">Woo Price Drop Alert</a></h4>
			</div>
		</div>
	</div>
	
</div>