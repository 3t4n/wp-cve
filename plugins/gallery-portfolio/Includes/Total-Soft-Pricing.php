<?php
	if(!current_user_can('manage_options'))
	{
		die('Access Denied');
	}
	global $wpdb;
	$table_namenp  = $wpdb->prefix . "totalsoft_new_plugin";
	$sql = 'CREATE TABLE IF NOT EXISTS ' .$table_namenp . '( id INTEGER(10) UNSIGNED AUTO_INCREMENT, New_Plugin_Name VARCHAR(255) NOT NULL, Our_Plugin_Name VARCHAR(255) NOT NULL, Dismiss VARCHAR(255) NOT NULL, PRIMARY KEY (id))';
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
	$TotalSoftNew_Plugin = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_namenp WHERE New_Plugin_Name = %s AND Our_Plugin_Name = %s", 'Pricing Table', 'Gallery Portfolio'));
	if(count($TotalSoftNew_Plugin)==0)
	{
		$wpdb->query($wpdb->prepare("INSERT INTO $table_namenp (id, New_Plugin_Name, Our_Plugin_Name, Dismiss) VALUES (%d, %s, %s, %s)", '', 'Pricing Table', 'Gallery Portfolio', '0'));
	}
	$TotalSoftPort_Quest = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_namenp WHERE New_Plugin_Name = %s AND Our_Plugin_Name = %s", 'Gallery Portfolio Question', 'Gallery Portfolio'));
	if(count($TotalSoftPort_Quest)==0)
	{
		$wpdb->query($wpdb->prepare("INSERT INTO $table_namenp (id, New_Plugin_Name, Our_Plugin_Name, Dismiss) VALUES (%d, %s, %s, %s)", '', 'Gallery Portfolio Question', 'Gallery Portfolio', '0'));
	}
	$TotalSoftNew_Plugin = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_namenp WHERE New_Plugin_Name = %s AND Our_Plugin_Name = %s", 'Pricing Table', 'Gallery Portfolio'));
	$TotalSoftPort_Quest = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_namenp WHERE New_Plugin_Name = %s AND Our_Plugin_Name = %s", 'Gallery Portfolio Question', 'Gallery Portfolio'));
?>
<style type="text/css">
	.TS_PTable_New_MDiv { position: relative; margin: 15px 0; width: 99%; padding: 10px; background: white; box-sizing: border-box; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; }
	.TS_PTable_New_MTable { position: relative; width: 100%; display: table; text-align: center; color: #000; border-spacing: 1px; }
	.TS_PTable_New_MTable img { position: absolute; width: 100%; height: 100%; top: 0; left: 0; }
	.TS_PTable_New_MTable span.TS_PTable_New_MTable_Span { position: absolute; width: 25px; height: 25px; top: -10px; right: -10px; font-size: 20px; color: #ff0000; cursor: pointer; }
	.TS_PTable_New_MTable span.TS_PTable_New_MTable_Span:hover { color: #f70f0f; }
	.TS_PTable_New_MTable p { position: absolute; width: 100%; left: 0; bottom: 0; margin: 0; padding: 0; text-align: right; }
	.TS_PTable_New_MTable h1 { color: #925189; font-family: Andalus; margin: 0 0 10px; font-size: 2em; }
	.TS_PTable_New_MTable_DisMiss { display: inline-block; margin: 0 10px; padding: 5px 10px; border: 1px solid #925189; width: 150px; border-radius: 24px; color: #925189; background: #ffffff; cursor: pointer; transition: all 0.5s; -moz-transition: all 0.5s; -webkit-transition: all 0.5s; }
	.TS_PTable_New_MTable_DisMiss:hover { background: #925189; color: #ffffff; }
	.TS_PTable_New_MTable_DisMiss:focus { outline: none !important; }
	.TS_PTable_New_MTable_Plugin { display: inline-block; margin: 0 10px; padding: 5px 10px; border: 1px solid #925189; width: 250px; border-radius: 24px; color: #ffffff; background: #925189; cursor: pointer; transition: all 0.5s; -moz-transition: all 0.5s; -webkit-transition: all 0.5s; text-align: center; text-decoration: none; }
	.TS_PTable_New_MTable_Plugin:hover { text-decoration: none; background-color: #ffffff; color: #925189; outline: none !important; box-shadow: none !important; -moz-box-shadow: none !important; -webkit-box-shadow: none !important; }
	.TS_PTable_New_MTable_Plugin:focus { text-decoration: none; outline: none !important; box-shadow: none !important; -moz-box-shadow: none !important; -webkit-box-shadow: none !important; }
	.TS_Port_Question_MDiv { position: relative; margin: 15px 0; width: 99%; padding: 10px; background: white; box-sizing: border-box; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; }
	.TS_Port_Question_Icon { font-size: 50px; color: #009491; }
	.TS_Port_Question_H1 { color: #009491 !important; font-family: Andalus; margin: 0 0 10px; font-size: 24px !important; }
	.TS_Port_Question_DisMiss { display: inline-block; margin: 0 10px; padding: 5px 10px; border: 1px solid #009491; width: 150px; border-radius: 24px; color: #009491; background: #ffffff; cursor: pointer; transition: all 0.5s; -moz-transition: all 0.5s; -webkit-transition: all 0.5s; }
	.TS_Port_Question_DisMiss:hover { background: #009491; color: #ffffff; }
	.TS_Port_Question_DisMiss:focus { outline: none !important; }
	.TS_Port_Question_Ask { display: inline-block; margin: 0 10px; padding: 5px 10px; border: 1px solid #009491; width: 250px; border-radius: 24px; color: #ffffff; background: #009491; cursor: pointer; transition: all 0.5s; -moz-transition: all 0.5s; -webkit-transition: all 0.5s; text-align: center; text-decoration: none; }
	.TS_Port_Question_Ask:hover { text-decoration: none; background-color: #ffffff; color: #009491; outline: none !important; box-shadow: none !important; -moz-box-shadow: none !important; -webkit-box-shadow: none !important; }
	.TS_Port_Question_Ask:focus { text-decoration: none; outline: none !important; box-shadow: none !important; -moz-box-shadow: none !important; -webkit-box-shadow: none !important; }
</style>
<script type="text/javascript">
	function TS_PTable_New_MTable_Span(val)
	{
		jQuery('.TS_PTable_New_MDiv').animate({'height':'0px','opacity':'0','margin':'0 0','padding':'0'},500);
		if(val == 'Dismiss')
		{
			var ajaxurl = ts_pg_object.ajaxurl;
			var data = {
			ts_pg_nonce_field : ts_pg_object.ts_pg_nonce_field,
			action: 'TS_PTable_New_MTable_DisMiss_Port', // wp_ajax_my_action / wp_ajax_nopriv_my_action in ajax.php. Can be named anything.
			foobar: val, // translates into $_POST['foobar'] in PHP
			};
			jQuery.post(ajaxurl, data, function(response) {})
		}
	}
	function TS_Portfolio_Question_Button(val)
	{
		jQuery('.TS_Port_Question_MDiv').animate({'height':'0px','opacity':'0','margin':'0 0','padding':'0'},500);
		if(val == 'Dismiss')
		{
			var ajaxurl = ts_pg_object.ajaxurl;
			var data = {
			ts_pg_nonce_field : ts_pg_object.ts_pg_nonce_field,
			action: 'TS_Port_Question_DisMiss', // wp_ajax_my_action / wp_ajax_nopriv_my_action in ajax.php. Can be named anything.
			foobar: val, // translates into $_POST['foobar'] in PHP
			};
			jQuery.post(ajaxurl, data, function(response) {})
		}
	}
</script>
<form method="POST">
	<?php if($TotalSoftNew_Plugin[0]->Dismiss == '0'){ ?>
		<div class="TS_PTable_New_MDiv">
			<table class="TS_PTable_New_MTable">
				<tr>
					<td style="width: 128px !important; height: 128px; position: relative;">
						<img src="<?php echo esc_url(plugins_url('../Images/Pricing-icon.png',__FILE__));?>">
					</td>
					<td style="width: calc(100% - 128px) !important; position: relative; font-size: 16px; font-family: Andalus">
						<span class="TS_PTable_New_MTable_Span" onclick="TS_PTable_New_MTable_Span('Close')">
							<i class="totalsoft totalsoft-times"></i>
						</span>
						<h1>New Plugin by Total Soft</h1>
						WooCommerce Pricing plugin is a powerful, amazing pricing plugin which helps you to make a table with your products and to give them beautiful design for each one. WooCommerce Pricing plugin offers a powerful tool to directly modify prices.
						<p>
							<a href="https://wordpress.org/plugins/woo-pricing-table/" class="TS_PTable_New_MTable_Plugin" target="_blank">
								Get Plugin
							</a>
							<input type="button" class="TS_PTable_New_MTable_DisMiss" onclick="TS_PTable_New_MTable_Span('Dismiss')" value="Dismiss">
						</p>
					</td>
				</tr>
			</table>
		</div>
	<?php }?>
	<?php if($TotalSoftPort_Quest[0]->Dismiss == '0'){ ?>
		<div class="TS_Port_Question_MDiv">
			<table class="TS_PTable_New_MTable">
				<tr>
					<td style="width: 50px !important; position: relative;">
						<i class="totalsoft totalsoft-question-circle-o TS_Port_Question_Icon"></i>
					</td>
					<td style="width: calc(100% - 50px) !important; position: relative; position: relative; font-size: 16px; font-family: Andalus;">
						<h1 class="TS_Port_Question_H1">Total Soft Support Team</h1>
						Hello. <br>
						Thank You for using our plugin. Our team is always ready to help you if you have any questions. We want to know does plugin work as it must? Do you have any questions or problems with the plugin?
						<p style="position: relative !important;">
							<a href="http://total-soft.pe.hu/contact-us/" class="TS_Port_Question_Ask" target="_blank">
								Ask Question
							</a>
							<input type="button" class="TS_Port_Question_DisMiss" value="Dismiss" onclick="TS_Portfolio_Question_Button('Dismiss')">
							<input type="button" class="TS_Port_Question_DisMiss" value="Close" onclick="TS_Portfolio_Question_Button('Close')">
						</p>
					</td>
				</tr>
			</table>
		</div>
	<?php }?>
</form>