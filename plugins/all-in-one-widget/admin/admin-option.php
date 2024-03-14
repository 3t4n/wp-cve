<?php
// ------------------------------------------------------------------------------
// CALLBACK FUNCTION SPECIFIED IN: add_options_page()
// ------------------------------------------------------------------------------
// THIS FUNCTION IS SPECIFIED IN add_options_page() AS THE CALLBACK FUNCTION THAT
// ACTUALLY RENDER THE PLUGIN OPTIONS FORM AS A SUB-MENU UNDER THE EXISTING
// SETTINGS ADMIN MENU.
// ------------------------------------------------------------------------------

// Render the Plugin options form
/* Exit if accessed directly
 ***********************************************************************************/
	if ( !defined('ABSPATH')) exit;
	
	
	
	?>
	<script type="text/javascript">
		jQuery(document).ready(function($) {

			//Change widget status

			$(".themeidol_checkbox").change(function() {

				if ($(this).is(":checked")) {
					$("." + $(this).attr("id")).removeClass("deactive").addClass("active");
				} else {
					$("." + $(this).attr("id")).removeClass("active").addClass("deactive");
				}

			});

			$('.arrow-down,.arrow-up').on('click',function(e){
				
				if ( $(this).hasClass('arrow-up') ) {
  				$(this).removeClass('arrow-up');
				} else {

  				$(this).toggleClass('arrow-up');
				}
				
				$(this).parent().parent().find('p').toggle();

			});




		});
	</script>
<div class="wrap themeidol-plugin-settings">

	<div id="panelheader">
		<div id="branding">
			<a href="http://www.themeidol.com/" />
				<img src="<?php echo THEMEIDOL_WIDGET_IMAGES_URL.'logo.png'; ?>" alt="" />
			</a>
		</div>
		<div class="header-info">
			All-in-one Widget

		</div>
	</div>

	<div class="metabox-holder has-right-sidebar ">
		<div class="inner-sidebar">
				<div class="postbox th-rightbox">
				<h3><span>Social</span></h3>
				<div class="inside">
					<div class="themeidol-social" id="themeidol-social-2">
					<a class="themeidol-social-link themeidol-social-facebook" href="http://facebook.com/themeidol" title="Facebook">
						<span class="themeidol-social-icon"></span>
					</a>
					<a class="themeidol-social-link themeidol-social-twitter" href="http://twitter.com/themeidol" title="Twitter">
						<span class="themeidol-social-icon"></span>
					</a>
					<a class="themeidol-social-link themeidol-social-gplus" href="http://plus.google.com/themeidol" title="Google+">
						<span class="themeidol-social-icon"></span>
					</a>
					<a class="themeidol-social-link themeidol-social-linkedin" href="http://linkedin.com/themeidol" title="LinkedIn">
						<span class="themeidol-social-icon"></span>
					</a>
					<a class="themeidol-social-link themeidol-social-youtube" href="http://youtube.com/themeidol" title="YouTube">
						<span class="themeidol-social-icon"></span>
					</a>
					<a class="themeidol-social-link themeidol-social-tumblr" href="http://tumblr.com/themeidol" title="Tumblr">
						<span class="themeidol-social-icon"></span>
					</a>
					<a class="themeidol-social-link themeidol-social-skype" href="http://retechoffshore" title="Skype">
						<span class="themeidol-social-icon"></span>
					</a>
					<a class="themeidol-social-link themeidol-social-pinterest" href="http://pinterest.com/themeidol" title="Pinterest">
						<span class="themeidol-social-icon"></span>
					</a>
					<a class="themeidol-social-link themeidol-social-instagram" href="http://instagram.com/themeidol" title="Instagram">
						<span class="themeidol-social-icon"></span>
					</a>
					<a class="themeidol-social-link themeidol-social-dribbble" href="http://dribble.com/themeidol" title="Dribbble">
						<span class="themeidol-social-icon"></span>
					</a>
					</div>
				</div>
			</div>
			<div class="postbox th-rightbox">
				<h3><span>Products & Services</span></h3>
				<div class="inside">
					<ul>
						<li><a href="http://themeidol.com/product/all-in-one-widget-pro/" target="_blank">All-in-one widget Pro</a></li>
						<li><a href="http://themeidol.com/items/themes/" target="_blank">WordPress Themes</a></li>
						<li><a href="http://themeidol.com/items/plugins/" target="_blank">WordPress Plugins</a></li>
						<li><a href="http://themeidol.com/contact/" target="_blank">Contact Us</a></li>
					</ul>
				</div>
			</div>



		
		</div> <!-- .inner-sidebar -->

		<div id="post-body">
			<div id="post-body-content">
				<!-- Beginning of the Plugin Options Form -->
				<form method="post" action="options.php">
					<div class="postbox">
						<div class="themeidol_options_submit">
							<p class="activate_widgets_notice"><?php _e('Activate the widgets you wish to enable and <strong>Save Changes</strong>','themeidol-all-widget'); ?></p>
							<input type="submit" class="button-primary" value="<?php _e('Save Changes', 'themeidol-all-widget'); ?>" />
						</div>

						<div class="inside">

							<?php settings_fields('themeidol_plugin_options'); 
							$options = get_option('themeidol_options');
							
							$allWidgets=array_unique(array_merge($this->default,$this->widgets), SORT_REGULAR);
							$widgetlists1 = array_splice($allWidgets, 0, floor(count($allWidgets)/2));
							$widgetlists2 = $allWidgets;
							
							?>
							<p class="activate_widgets_notice"></p>

							<div class="themeidol-col-left">
								<?php foreach($widgetlists2 as $key  => $value): 
						    
								if(isset($options[$key]) && $options[$key] == 1) { $state = "active"; } else { $state = "deactive"; } ?>
								<div class="themeidol_options_<?php echo $key;?>_widget themeidol_option_box <?php echo $state; ?>">
									<div class="themeidol_option_box_inner">
										<h3><?php echo $value[1]; ?><div class="arrow-down"></div></h3>
										
										<?php if($value[0]==1):?>
										<label class="widget_trigger">
											<input type="checkbox" class="themeidol_checkbox" id="themeidol_options_<?php echo $key;?>_widget" name="themeidol_options[<?php echo $key;?>]" value="1" <?php if (isset($options[$key])) { checked('1', $options[$key]); } ?> />
											<span class="themeidol_switcher">
												<span class="themeidol_switcheron"><?php _e('ON','themeidol-all-widget'); ?></span>
												<span class="themeidol_switcheroff"><?php _e('OFF','themeidol-all-widget'); ?></span>
												<span class="themeidol_switcherblock"></span>
											</span>

										</label>
										<?php else:?>
										<?php if(array_key_exists($key, $this->default)): ?>
										<label class="widget_trigger">
											<input type="checkbox" class="themeidol_checkbox" id="themeidol_options_<?php echo $key;?>_widget" name="themeidol_options[<?php echo $key;?>]" value="1" <?php if (isset($options[$key])) { checked('1', $options[$key]); } ?> />
											<span class="themeidol_switcher">
												<span class="themeidol_switcheron"><?php _e('ON','themeidol-all-widget'); ?></span>
												<span class="themeidol_switcheroff"><?php _e('OFF','themeidol-all-widget'); ?></span>
												<span class="themeidol_switcherblock"></span>
											</span>

										</label>
										<?php else: ?>
										<label class="widget_pro">
											<span class="pro">Upgrade to Pro</span>
										</label>
										<?php endif; ?>
										<?php endif; ?>

										<p><?php echo $value[2]; ?></p>
									</div><!-- .themeidol_option_box_inner -->
								</div>

							<?php endforeach; ?>

								
							</div><!-- .themeidol-col-left -->

							<div class="themeidol-col-right">

								<?php foreach($widgetlists1 as $key  => $value): 
						    
								if(isset($options[$key]) && $options[$key] == 1) { $state = "active"; } else { $state = "deactive"; } ?>
								<div class="themeidol_options_<?php echo $key;?>_widget themeidol_option_box <?php echo $state; ?>">
									<div class="themeidol_option_box_inner">
										<h3><?php echo $value[1]; ?><div class="arrow-down"></div></h3>

										
										<?php if($value[0]==1):?>
										<label class="widget_trigger">
											<input type="checkbox" class="themeidol_checkbox" id="themeidol_options_<?php echo $key;?>_widget" name="themeidol_options[<?php echo $key;?>]" value="1" <?php if (isset($options[$key])) { checked('1', $options[$key]); } ?> />
											<span class="themeidol_switcher">
												<span class="themeidol_switcheron"><?php _e('ON','themeidol-all-widget'); ?></span>
												<span class="themeidol_switcheroff"><?php _e('OFF','themeidol-all-widget'); ?></span>
												<span class="themeidol_switcherblock"></span>
											</span>

										</label>
										<?php else:?>
										<?php if(array_key_exists($key, $this->default)): ?>
											<label class="widget_trigger">
											<input type="checkbox" class="themeidol_checkbox" id="themeidol_options_<?php echo $key;?>_widget" name="themeidol_options[<?php echo $key;?>]" value="1" <?php if (isset($options[$key])) { checked('1', $options[$key]); } ?> />
											<span class="themeidol_switcher">
												<span class="themeidol_switcheron"><?php _e('ON','themeidol-all-widget'); ?></span>
												<span class="themeidol_switcheroff"><?php _e('OFF','themeidol-all-widget'); ?></span>
												<span class="themeidol_switcherblock"></span>
											</span>

										</label>
										<?php else: ?>
										<label class="widget_pro">
											<span class="pro">Upgrade to Pro</span>
										</label>
										<?php endif; ?>
										<?php endif; ?>

										<p><?php echo $value[2]; ?></p>
									</div><!-- .themeidol_option_box_inner -->
								</div>

							<?php endforeach; ?>



										
								
							</div><!-- .themeidol-col-right -->

							<div class="clearfix"></div>

							
						</div> <!-- .inside -->

						<div class="themeidol_options_submit submit-bottom">
							<p class="activate_widgets_notice"><?php _e('Activate the widgets you wish to enable and <strong>Save Changes</strong>','themeidol-all-widget'); ?></p>
							<input type="submit" class="button-primary" value="<?php _e('Save Changes', 'themeidol-all-widget') ?>" />
						</div>
					</div><!-- .postbox -->
				</form>
			</div> <!-- #post-body-content -->
		</div> <!-- #post-body -->

	</div> <!-- .metabox-holder -->

</div> <!-- .wrap -->