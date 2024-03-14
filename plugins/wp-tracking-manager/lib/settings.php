<div class="wrap">
    <h2>WP Tracking Manager</h2>
	<div id="wp_tracking_manager-tab-menu"><a id="wp_tracking_manager-general" class="wp_tracking_manager-tab-links active" >General</a>  <a id="wp_tracking_manager-post-tracking" class="wp_tracking_manager-tab-links" >Post Type Specific Tracking Code</a> <a  id="wp_tracking_manager-support" class="wp_tracking_manager-tab-links">Our Other plugin</a></div>
    <form method="post" action="options.php"> 
        <div class="wp_tracking_manager-setting">
			<!-- General Setting -->	
			<div class="first wp_tracking_manager-tab" id="div-wp_tracking_manager-general">
				<table class="form-table">
				<tr><td  valign="top">
				<table>
										<tr>
						<td valign="top"><?php _e('Header Tracking code:');?><br><textarea rows="10" cols="60" name="wtm_header_script" id="wtm_header_script"><?php echo get_option('wtm_header_script');?></textarea><br><i>Code will display between &lt;head>&lt;/head> throught the website.</i>			
						</td>
					</tr>
					<tr>
						<td valign="top"><?php _e('Footer Tracking code:');?><br><textarea rows="10" cols="60" name="wtm_footer_script" id="wtm_footer_script"><?php echo get_option('wtm_footer_script');?></textarea><br><i>Code will display in footer of the website before close &lt;/body> tag</i>				
						</td>
					</tr>
					<tr>
						<td valign="top"><input type="checkbox" <?php checked(get_option('wtm_enable'),1);?> name="wtm_enable" value="1"><strong><?php _e('Enable Page Specific Traking Section');?></strong><br><i>(Page specific tracking section will display on edit screen.)</i>				
						</td>
					</tr>
					</table>
					</td>
					<!--<td>
					<h2>Video Tutorial:</h2>
					<iframe width="560" height="315" src="https://www.youtube.com/embed/Oc3dRk37yK4?autoplay=1&mute=1" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
					</td>-->
					</tr>
				</table>
			</div>
			<div class="second wp_tracking_manager-tab" id="div-wp_tracking_manager-post-tracking">
				<table class="form-table">
				<tr><td  valign="top">
				<table>
					<tr>
				    <th colspan="2"><h2>Post Type Specific Tracking Code<hr></h2>
				    <h4><strong>Header Tracking Code</strong></h4>
				    </th>
					</tr>
					<?php 	
					  // get register all post type 
						$post_types = $custompostype = get_post_types(array('public' => true,'_builtin' => false),'names','and'); 
						array_push($post_types,'post');array_push($post_types,'page');
					 
							       sort($post_types);
									foreach($post_types as $val)
									{
										 $currentValue = get_option('wtm_header_script_'.$val);
										 echo '<tr>
						<td valign="top">'.ucfirst($val).' Header Tracking code<br><textarea rows="10" cols="60" name="wtm_header_script_'.$val.'" id="wtm_header_script_'.$val.'">'.$currentValue.'</textarea><br><i>This code will display between &lt;head>&lt;/head> tag only on <strong>'.ucfirst($val).'</strong></i>			
						<hr></td>
					</tr>';
										 
										}
					?>
					<tr>
				    <td colspan="2"><strong>Footer Tracking Code</strong></td>
					</tr>
					<?php 	
					  // get register all post type 
									foreach($post_types as $val)
									{
										 $currentValue = get_option('wtm_footer_script_'.$val);
										 echo '<tr>
						<td valign="top">'.ucfirst($val).' Footer Tracking code<br><textarea rows="10" cols="60" name="wtm_footer_script_'.$val.'" id="wtm_footer_script_'.$val.'">'.$currentValue.'</textarea><br><i>This code will display in footer of the site only on <strong>'.ucfirst($val).'</strong> before close &lt;/body> tag</i>			
						<hr></td>
					</tr>';
										 
					 }
					?>
					</table>
					</td>
					</tr>
				</table>
		    </div>
			<div class="last wp_tracking_manager-tab" id="div-wp_tracking_manager-support">
				<p><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=4624D4L4LT6NU" target="_blank" style="font-size: 17px; font-weight: bold;"><img src="<?php echo  plugins_url( '../images/btn_donate_LG.gif' , __FILE__ );?>" title="Donate for this plugin"></a></p>
				<p><strong>Plugin Author:</strong><br><a href="http://www.wp-experts.in" target="_blank">WP-Experts.In Team</a></p>
				<p><a href="mailto:raghunath.0087@gmail.com" target="_blank" class="contact-author">Contact Author</a></p>
				
				<h2>Other plugins</h2>
				<p>
				<ol>
					<li><a href="https://wordpress.org/plugins/custom-share-buttons-with-floating-sidebar" target="_blank">Custom Share Buttons With Floating Sidebar</a></li>
					<li><a href="https://wordpress.org/plugins/wp-tracking-manager/" target="_blank">WP Tracking Manager</a></li>
					<li><a href="https://wordpress.org/plugins/seo-manager/" target="_blank">SEO Manager</a></li>
					<li><a href="https://wordpress.org/plugins/protect-wp-admin/" target="_blank">Protect WP-Admin</a></li>
					<li><a href="https://wordpress.org/plugins/wp-categories-widget/" target="_blank">WP Categories Widget</a></li>
					<li><a href="https://wordpress.org/plugins/wp-protect-content/" target="_blank">WP Protect Content</a></li>
					<li><a href="https://wordpress.org/plugins/wp-version-remover/" target="_blank">WP Version Remover</a></li>
					<li><a href="https://wordpress.org/plugins/wp-posts-widget/" target="_blank">WP Post Widget</a></li>
					<li><a href="https://wordpress.org/plugins/wp-importer" target="_blank">WP Importer</a></li>
					<li><a href="https://wordpress.org/plugins/optimize-wp-website/" target="_blank">Optimize WP Website</a></li>
					<li><a href="https://wordpress.org/plugins/wp-testimonial/" target="_blank">WP Testimonial</a></li>
					<li><a href="https://wordpress.org/plugins/wc-sales-count-manager/" target="_blank">WooCommerce Sales Count Manager</a></li>
					<li><a href="https://wordpress.org/plugins/wp-social-buttons/" target="_blank">WP Social Buttons</a></li>
					<li><a href="https://wordpress.org/plugins/wp-youtube-gallery/" target="_blank">WP Youtube Gallery</a></li>
					<li><a href="https://wordpress.org/plugins/rg-responsive-gallery/" target="_blank">RG Responsive Slider</a></li>
					<li><a href="https://wordpress.org/plugins/cf7-advance-security" target="_blank">Contact Form 7 Advance Security WP-Admin</a></li>
					<li><a href="https://wordpress.org/plugins/wp-easy-recipe/" target="_blank">WP Easy Recipe</a></li>
                </ol>
				</p>
			</div>
		</div>
		  <?php settings_fields('wp-tracking-manager-group'); ?>
        <?php @submit_button(); ?>
    </form>
</div>
