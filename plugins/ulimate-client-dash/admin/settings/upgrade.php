<?php


// Upgrade Page Content

add_action( 'ucd_settings_content', 'ucd_upgrade_page' );
function ucd_upgrade_page() {
		global $ucd_active_tab;
		if ( 'upgrade' != $ucd_active_tab )
		return;
?>

<!-- Padding 0px for upgrade page -->
<style>
		.ucd-tab-content {
				padding: 0px!important;
		}
</style>

<div style="margin-top: 2px; margin-bottom: 1px;" class='ucd-upgrade-body'>
		<div class="upgrade-section">
				<h1>Ultimate Client Dash Pro</h1>
				<p>Purchase Pro to unlock the power of Ultimate Client Dash.</p>
				<a href="https://ultimateclientdash.com/pricing/" target="_blank">Get Pro Version</a>
		</div>

				<div class='compare-section'>
						<h5>Pro Features</h5>
								<div class='compare-table'>
												<div class='ucd-row'>
														<div class='ucd-column title'>
																<ul>
																		<li class='table-header'>Dashboard</li>
																		<li class='ucd-tooltip'>Dashboard Logo
																				<div class='ucd-tooltip'><img src='<?php echo plugins_url( 'assets/information.png', __FILE__ ); ?>'/>
																						<span class='ucd-tooltip-text'>Add your logo to the WordPress dashboard that will appear at the top of the admin menu.</span>
																				</div>
																		</li>
																		<li class='ucd-tooltip'>White Label WordPress
																				<div class='ucd-tooltip'><img src='<?php echo plugins_url( 'assets/information.png', __FILE__ ); ?>'/>
																						<span class='ucd-tooltip-text'>Hide all references to WordPress in the WordPress dashboard.</span>
																				</div>
																		</li>
																		<li class='ucd-tooltip'>Dashboard Custom CSS
																				<div class='ucd-tooltip'><img src='<?php echo plugins_url( 'assets/information.png', __FILE__ ); ?>'/>
																						<span class='ucd-tooltip-text'>Add your own custom CSS to restyle the WordPress dashboard.</span>
																				</div>
																		</li>
																		</li>
																		<li class='table-header'>Login Page</li>
																		<li class='ucd-tooltip'>Background Image
																				<div class='ucd-tooltip'><img src='<?php echo plugins_url( 'assets/information.png', __FILE__ ); ?>'/>
																						<span class='ucd-tooltip-text'>Add your own background image to the login page.</span>
																				</div>
																		</li>
																		<li class='ucd-tooltip'>Login Page Custom CSS
																				<div class='ucd-tooltip'><img src='<?php echo plugins_url( 'assets/information.png', __FILE__ ); ?>'/>
																						<span class='ucd-tooltip-text'>Add your own custom CSS to restyle the Login Page.</span>
																				</div>
																		</li>
																		</li>
																		<li class='table-header'>Menu</li>
																		<li class='ucd-tooltip'>Remove Menu Items
																				<div class='ucd-tooltip'><img src='<?php echo plugins_url( 'assets/information.png', __FILE__ ); ?>'/>
																						<span class='ucd-tooltip-text'>Simplify the WordPress dashboard by removing menu items of your choice.</span>
																				</div>
																		</li>
																		</li>
																		<li class='table-header'>Widgets</li>
																		<li class='ucd-tooltip'>Multiple Custom Widgets
																				<div class='ucd-tooltip'><img src='<?php echo plugins_url( 'assets/information.png', __FILE__ ); ?>'/>
																						<span class='ucd-tooltip-text'>Create up to 4 custom widgets to be displayed on the WordPress dashboard.</span>
																				</div>
																		</li>
																		</li>
																		<li class='table-header'>Landing Page</li>
																		<li class='ucd-tooltip'>Background Image
																				<div class='ucd-tooltip'><img src='<?php echo plugins_url( 'assets/information.png', __FILE__ ); ?>'/>
																						<span class='ucd-tooltip-text'>Add your own background image to the landing page.</span>
																				</div>
																		</li>
																		<li class='ucd-tooltip'>Meta Title
																				<div class='ucd-tooltip'><img src='<?php echo plugins_url( 'assets/information.png', __FILE__ ); ?>'/>
																						<span class='ucd-tooltip-text'>Customize the meta title for the landing page. This is the title that displays on seach engine results.</span>
																				</div>
																		</li>
																		<li class='ucd-tooltip'>Meta Description
																				<div class='ucd-tooltip'><img src='<?php echo plugins_url( 'assets/information.png', __FILE__ ); ?>'/>
																						<span class='ucd-tooltip-text'>Customize the meta description for the landing soon page. This is the description that displays on seach engine results.</span>
																				</div>
																		</li>
																		<li class='ucd-tooltip'>Landing Page Custom CSS
																				<div class='ucd-tooltip'><img src='<?php echo plugins_url( 'assets/information.png', __FILE__ ); ?>'/>
																						<span class='ucd-tooltip-text'>Add your own custom CSS to restyle the landing page.</span>
																				</div>
																		</li>
																		</li>
																		<li class='table-header'>Client Access</li>
																		<li class='ucd-tooltip'>Manage Settings
																				<div class='ucd-tooltip'><img src='<?php echo plugins_url( 'assets/information.png', __FILE__ ); ?>'/>
																						<span class='ucd-tooltip-text'>Allow the user role client to manage WordPress settings. Useful if 3rd party plugins require manage_options capabilities.</span>
																				</div>
																		</li>
																		<li class='ucd-tooltip'>Extend Client Capabilities
																				<div class='ucd-tooltip'><img src='<?php echo plugins_url( 'assets/information.png', __FILE__ ); ?>'/>
																						<span class='ucd-tooltip-text'>The extend Client Capabitilies feature will dynamically populate all WordPress core, theme, and plugin capabilities and allow you to assign them to the user role client.</span>
																				</div>
																		</li>
																		</li>
																		<li class='table-header'>Misc</li>
																		<li class='ucd-tooltip'>Theme Name
																				<div class='ucd-tooltip'><img src='<?php echo plugins_url( 'assets/information.png', __FILE__ ); ?>'/>
																						<span class='ucd-tooltip-text'>Replace any use of the theme name in the WordPress dashboard.</span>
																				</div>
																		</li>
																		<li class='ucd-tooltip'>Rebrand Name
																				<div class='ucd-tooltip'><img src='<?php echo plugins_url( 'assets/information.png', __FILE__ ); ?>'/>
																						<span class='ucd-tooltip-text'>Name which will replace the theme name.</span>
																				</div>
																		</li>
																		<li class='ucd-tooltip'>Disable Client Nags & Notices
																				<div class='ucd-tooltip'><img src='<?php echo plugins_url( 'assets/information.png', __FILE__ ); ?>'/>
																						<span class='ucd-tooltip-text'>Hide all update notifications and nags in the WordPress dashboard for user role 'clients'.</span>
																				</div>
																		</li>
																		<li class='ucd-tooltip'>Disable Fatal Error Protection
																				<div class='ucd-tooltip'><img src='<?php echo plugins_url( 'assets/information.png', __FILE__ ); ?>'/>
																						<span class='ucd-tooltip-text'>Disable fatal PHP error protection email notifications. Added in WordPress 5.2.</span>
																				</div>
																		</li>
																		<li class='ucd-tooltip'>Disable Auto Update Email
																				<div class='ucd-tooltip'><img src='<?php echo plugins_url( 'assets/information.png', __FILE__ ); ?>'/>
																						<span class='ucd-tooltip-text'>Disable WordPress auto update email notification.</span>
																				</div>
																		</li>
																		<li class='ucd-tooltip'>Hide UCD Plugin
																				<div class='ucd-tooltip'><img src='<?php echo plugins_url( 'assets/information.png', __FILE__ ); ?>'/>
																						<span class='ucd-tooltip-text'>Hide Ultimate Client Dash from the plugin list for user role 'Client'.</span>
																				</div>
																		</li>
																		</li>
																		<li class='table-header'>Shortcodes</li>
																		<li class='ucd-tooltip'>Site Information
																				<div class='ucd-tooltip'><img src='<?php echo plugins_url( 'assets/information.png', __FILE__ ); ?>'/>
																						<span class='ucd-tooltip-text'>Useful site information shortcodes you can use throughout your website to dynamically populate data.</span>
																				</div>
																		</li>
																		<li class='ucd-tooltip'>User Information
																				<div class='ucd-tooltip'><img src='<?php echo plugins_url( 'assets/information.png', __FILE__ ); ?>'/>
																						<span class='ucd-tooltip-text'>Useful user information shortcodes you can use throughout your website to dynamically populate data.</span>
																				</div>
																		</li>
																		<li class='ucd-tooltip'>Date
																				<div class='ucd-tooltip'><img src='<?php echo plugins_url( 'assets/information.png', __FILE__ ); ?>'/>
																						<span class='ucd-tooltip-text'>Useful date shortcodes you can use throughout your website to dynamically populate data.</span>
																				</div>
																		</li>
																		<li class='ucd-tooltip'>Symbols
																				<div class='ucd-tooltip'><img src='<?php echo plugins_url( 'assets/information.png', __FILE__ ); ?>'/>
																						<span class='ucd-tooltip-text'>Useful symbol shortcodes you can use throughout your website to dynamically populate data.</span>
																				</div>
																		</li>
																		</li>
																</ul>
														</div>
														<div class='ucd-column pro'>
																<ul>
																		<li class='table-header'>PRO</li>
																		<li><img src='<?php echo plugins_url( 'assets/check.png', __FILE__ ); ?>'/></li>
																		<li><img src='<?php echo plugins_url( 'assets/check.png', __FILE__ ); ?>'/></li>
																		<li><img src='<?php echo plugins_url( 'assets/check.png', __FILE__ ); ?>'/></li>
																		<li class='table-header' style='color: #f5f5f5!important;'>-</li>
																		<li><img src='<?php echo plugins_url( 'assets/check.png', __FILE__ ); ?>'/></li>
																		<li><img src='<?php echo plugins_url( 'assets/check.png', __FILE__ ); ?>'/></li>
																		<li class='table-header' style='color: #f5f5f5!important;'>-</li>
																		<li><img src='<?php echo plugins_url( 'assets/check.png', __FILE__ ); ?>'/></li>
																		<li class='table-header' style='color: #f5f5f5!important;'>-</li>
																		<li><img src='<?php echo plugins_url( 'assets/check.png', __FILE__ ); ?>'/></li>
																		<li class='table-header' style='color: #f5f5f5!important;'>-</li>
																		<li><img src='<?php echo plugins_url( 'assets/check.png', __FILE__ ); ?>'/></li>
																		<li><img src='<?php echo plugins_url( 'assets/check.png', __FILE__ ); ?>'/></li>
																		<li><img src='<?php echo plugins_url( 'assets/check.png', __FILE__ ); ?>'/></li>
																		<li><img src='<?php echo plugins_url( 'assets/check.png', __FILE__ ); ?>'/></li>
																		<li class='table-header' style='color: #f5f5f5!important;'>-</li>
																		<li><img src='<?php echo plugins_url( 'assets/check.png', __FILE__ ); ?>'/></li>
																		<li><img src='<?php echo plugins_url( 'assets/check.png', __FILE__ ); ?>'/></li>
																		<li class='table-header' style='color: #f5f5f5!important;'>-</li>
																		<li><img src='<?php echo plugins_url( 'assets/check.png', __FILE__ ); ?>'/></li>
																		<li><img src='<?php echo plugins_url( 'assets/check.png', __FILE__ ); ?>'/></li>
																		<li><img src='<?php echo plugins_url( 'assets/check.png', __FILE__ ); ?>'/></li>
																		<li><img src='<?php echo plugins_url( 'assets/check.png', __FILE__ ); ?>'/></li>
																		<li><img src='<?php echo plugins_url( 'assets/check.png', __FILE__ ); ?>'/></li>
																		<li><img src='<?php echo plugins_url( 'assets/check.png', __FILE__ ); ?>'/></li>
																		<li class='table-header' style='color: #f5f5f5!important;'>-</li>
																		<li><img src='<?php echo plugins_url( 'assets/check.png', __FILE__ ); ?>'/></li>
																		<li><img src='<?php echo plugins_url( 'assets/check.png', __FILE__ ); ?>'/></li>
																		<li><img src='<?php echo plugins_url( 'assets/check.png', __FILE__ ); ?>'/></li>
																		<li><img src='<?php echo plugins_url( 'assets/check.png', __FILE__ ); ?>'/></li>
																</ul>
													 </div>
										 </div>
					 </div>
			</div>

			<div style="padding-top: 45px;" class="upgrade-section">
					<p>Purchase Pro to unlock the power of Ultimate Client Dash.</p>
					<a href="https://ultimateclientdash.com/pricing/" target="_blank">Get Pro Version</a>
			</div>

</div>

<?php }
