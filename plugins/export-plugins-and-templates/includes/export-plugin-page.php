<style type="text/css">
		@import url(http://fonts.googleapis.com/earlyaccess/droidarabickufi.css);
		@import url(http://fonts.googleapis.com/earlyaccess/droidarabicnaskh.css);
		.ep-plugins-themes-fonts-hed{
			font-family: 'Droid Arabic Kufi', serif ;
		}
		.ep-plugins-themes-fonts-p{
			font-family: 'Droid Arabic Naskh', serif ;
		}
		.ep-plugins-templates-item-caption { margin: 3px 0 0 3px; font-size: 80%; color: #777; }
		.ep-plugins-templates-table-wrap { margin: 15px; }
		.ep-plugins-templates-table-wrap td { padding: 5px 10px; line-height: 18px; vertical-align: middle; }
		.ep-plugins-templates-table-wrap .ep-plugins-templates-table {}
		.ep-plugins-templates-table-wrap .widefat th { padding: 10px 15px; vertical-align: middle; }
		.ep-plugins-templates-table-wrap .widefat td { padding: 10px; vertical-align: middle; }

</style>
	<div id="ep-plugins-templates-plugin-options" class="wrap">
		<?php
			if(isset($_POST['plugins'])){
				ep_plugins_themes_mokfie_export_plugins($_POST['plugins']);
			}
		?>
		<form method="post">
			<div class="metabox-holder">
				<div class="meta-box-sortables ui-sortable">
						<h1 class="ep-plugins-themes-fonts-hed"><?php _e("Export plugins","ep-plugins-templates"); ?></h1>
							<p class="ep-plugins-themes-fonts-p"><?php _e("You can export plugins to zip file directly.", "ep-plugins-templates"); ?></p>
							<div class="ep-plugins-templates-table-wrap">
								<table class="widefat ep-plugins-templates-table">
									<tr>
										<th scope="row"><label class="description ep-plugins-themes-fonts-p"><?php _e("Choose your plugin","ep-plugins-templates"); ?></label></th>
										<td>
											<select name="plugins" class="regular-text"><?=get_all_plugins_options();?></select>
											<div class="ep-plugins-templates-item-caption ep-plugins-themes-fonts-p"><?php _e("NOTE: The names of the plugins appear as the names of folders only, and are not registered within the PHP file.","ep-plugins-templates"); ?></div>
										</td>
									</tr>
								</table>
							</div>
							<input type="submit" class="button-primary" value="<?php _e("Export Plugin Now","ep-plugins-templates"); ?>" name="export_plugins">
					</div>
			</div>
		</form>
		</div>
