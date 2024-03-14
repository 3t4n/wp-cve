<?php
if(!class_exists('WP_List_Table')){
	require_once( get_home_path() . 'wp-admin/includes/class-wp-list-table.php' );
}
class linkedin_master_admin_table_news extends WP_List_Table {
	/**
	 * Display the rows of records in the table
	 * @return string, echo the markup of the rows
	 */
	function display() {
?>
<table class="widefat" cellspacing="0">
	<thead>
		<tr>
			<th colspan="2"><h2><img src="<?php echo plugins_url('images/techgasp-minilogo-16.png', dirname(__FILE__)); ?>" style="float:left; height:18px; vertical-align:middle;" /><?php _e('&nbsp;Latest News', 'linkedin_master'); ?></h2></th>
		</tr>
	</thead>

	<tfoot>
		<tr>
			<th colspan="2"></th>
		</tr>
	</tfoot>

	<tbody>
		<tr class="alternate">
			<td style="width:500px;">
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.7&appId=281766848505812";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div id="container-fb" style="width:100%;">
<style>
.fb-like-box, .fb-like-box span, .fb-like-box.fb_iframe_widget span iframe {
    width: 100% !important;
}
</style>
<div class="fb-like-box" data-href="https://www.facebook.com/TechGasp" data-width="500" data-colorscheme="light" data-show-faces="false" data-header="false" data-stream="true" data-show-border="false"></div>
</div>
			</td>
			<td style="max-width:90%; height:auto; position: relative; display:block; margin:0 auto;">
				<h1 style="text-align:center; padding-bottom: 10px;">2 Million Threats and Counting</h1>
				<a href="https://www.techgasp.com/spam-master-2-million-threats-and-counting/" target="_blank"><img src="<?php echo plugins_url('images/techgasp-spam-master-2-million.jpg', dirname(__FILE__)); ?>" style="width:100% !important; height:100% !important; display:block;" /></a>
				<p style="text-align:left;">Protect your website against constant malicious spam registrations, comments, dangerous exploits like HTTP and HTTPS DDoS (denial-of-service), SQL, brute force attacks and injections and many others that made it through the HTTP or HTTPS. We did it the TechGasp way… coded a brand new, clean and fast plugin that will make your professional wordpress website safe and clean.</p>
				<p style="text-align:center;"><a class="button-primary" href="https://www.techgasp.com/spam-master-2-million-threats-and-counting/" target="_blank" title="Read More">Read More</a> <a class="button-primary" href="https://wordpress.org/plugins/spam-master/" target="_blank" title="Take a Peak">Take a Peak!</a></p>
			</td>
		</tr>
	</tbody>
</table>
<?php
		}
}
