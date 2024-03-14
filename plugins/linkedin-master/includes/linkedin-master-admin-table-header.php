<?php
if(!class_exists('WP_List_Table')){
	require_once( get_home_path() . 'wp-admin/includes/class-wp-list-table.php' );
}
class linkedin_master_admin_table_header extends WP_List_Table {
	/**
	 * Display the rows of records in the table
	 * @return string, echo the markup of the rows
	 */
	function display() {
	$plugin_master_name = constant('LINKEDIN_MASTER_NAME');
?>
<table class="widefat" cellspacing="0">
	<thead>
		<tr>
			<th colspan="2"><h2><img src="<?php echo plugins_url('images/techgasp-minilogo-16.png', dirname(__FILE__)); ?>" style="float:left; height:18px; vertical-align:middle;" /><?php _e('&nbsp;About', 'linkedin_master'); ?></h2></th>
		</tr>
	</thead>

	<tfoot>
		<tr>
			<th colspan="2"></th>
		</tr>
	</tfoot>

	<tbody>
		<tr class="alternate">
			<td><img src="<?php echo plugins_url('images/techgasp-spammaster-logo.png', dirname(__FILE__)); ?>" alt="<?php echo $plugin_master_name; ?>" align="left" width="387px" height="171px" style="padding:5px;"/></td>
			<td>
<p>Major player in the Content Management System world! Wordpress, Joomla and Jomsocial Partner with more than 100 high quality, error free Extensions. We provide website customizations and development, SEO Optimization, Facebook Apps, etc. We have fast & furious specialized Hosting for WordPress and Joomla. Our promise, the use of minimal error free code with fast loading times. Check our website for more high quality extensions. Stay up to date by "like" and "follow" our facebook and twitter page for fresh news, releases and upgrades and updates.</p>
<p>
<a class="button-primary" href="https://www.techgasp.com" target="_blank" title="Visit Website">Main Website</a>
<a class="button-primary" href="https://wordpress.techgasp.com" target="_blank" title="Visit Website">Wordpress Website</a>
<a class="button-primary" href="https://hosting.techgasp.com" target="_blank" title="Visit Website">Hosting Website</a>
<a class="button-secondary" href="https://www.facebook.com/TechGasp" target="_blank" title="Facebook Page">Facebook Page</a>
<a class="button-secondary" href="https://twitter.com/TechGasp" target="_blank" title="Follow Twitter">Twitter Page</a>
<a class="button-secondary" href="https://plus.google.com/118126459543511361864" target="_blank" title="Follow Google">Google Page</a>
</p>
<h3>Stay up-to-date with new extension releases, extension updates, & upgrades:</h3>
</td>
		</tr>
		<tr>
			<td></td>
			<td>
<p>
<div style="display:flex !important;">
<fb:like href="https://www.facebook.com/TechGasp" send="true" layout="button_count" width="90" show_faces="false"></fb:like>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.7&appId=281766848505812";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
&nbsp;
<a href="https://twitter.com/TechGasp" class="twitter-follow-button" data-show-count="true">Follow @TechGasp</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
</div>
</p>
			</td>
		</tr>
	</tbody>
</table>
<?php
		}
}
