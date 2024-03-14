<?php
/*  
 * Robo Maps            http://robosoft.co/wordpress-google-maps
 * Version:             1.0.6 - 19837
 * Author:              Robosoft
 * Author URI:          http://robosoft.co
 * License:             GPL-2.0+
 * License URI:         http://www.gnu.org/licenses/gpl-2.0.txt
 * Date:                Thu, 18 May 2017 11:11:10 GMT
 */

?>
<div class="wrap">
	<h2><?php _e('Robo Maps', 'robo-maps'); ?></h2>
	<div>
		<div class="robo-map-pp-logo-block">
			<img src="<?php echo ROBO_MAPS_URL;?>/images/logo.png" alt="<?php _e('Robo Maps', 'robo-maps'); ?>" class="robo-map-pp-logo" />
		</div>
		<div class="robo-map-pp-desc">
			<p><?php _e("RoboMaps it's simple and really powerful Google maps plugin. You have full set of settings to customize your maps. It’s Mobile optimized fully responsive plugin. You can easily customize styles and settings of every of your map", 'robo-maps'); ?></p>
			<ul class="subsubsub">
				<li class="robo-map-homepage">		<a href="https://robosoft.co"><?php _e('Homepage', 'robo-maps'); ?></a> | </li>
				<li class="robo-map-faq">			<a href="https://robosoft.co/products_info/?type=faq"><?php _e('FAQ', 'robo-maps'); ?></a> | </li>
				<li class="robo-map-support">		<a href="https://robosoft.co/products_info/?type=support"><?php _e('Support Tickets', 'robo-maps'); ?></a> </li>				
			</ul>
		</div>
	</div>
	<div class="clearfix"></div>
	<hr />
	<div class="robo-map-pp-col1">
		<h3><?php _e('Settings'); ?>:</h3>
		<form method="post" action="options.php">
			<?php wp_nonce_field('update-options'); ?>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><label forid="robo-map-key"><?php _e('Google Maps Api Key', 'robo-maps'); ?></label></th>
						<td>
							<input type="text" id="robo-map-key" name="robo-map-key" class="regular-text" placeholder="" value="<?php echo get_option('robo-map-key', ''); ?>" />
							<br />
							<span class="description">
								(<a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">
									<?php _e('direct link to the Google Maps Api key help', 'robo-maps'); ?>
								</a>)
							</span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label forid="robo-map-width"><?php _e('Width', 'robo-maps'); ?></label></th>
						<td>
							<input type="text" id="robo-map-width" name="robo-map-width" class="form-control" placeholder="" value="<?php echo get_option('robo-map-width', '100%'); ?>" />
							<span class="description">px <?php _e('or', 'robo-maps'); ?> %</span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label forid="robo-map-height"><?php _e('Height', 'robo-maps'); ?></label></th>
						<td>
							<input type="text" id="robo-map-height" name="robo-map-height" class="form-control" placeholder=""  value="<?php echo get_option('robo-map-height', '400px'); ?>"  />
							<span class="description">px</span>
						</td>
					</tr>
				</tbody>
			</table>

			<div class="clearfix"></div>
			<input type="hidden" name="action" value="update" />
			<input type="hidden" name="page_options" value="robo-map-width,robo-map-height,robo-map-key" />
			<p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes', 'robo-maps'); ?>" /></p>
		</form>
		
		
	</div>

	<div class="robo-map-pp-col2">
		<h3><?php echo __('Key Features of the Pro Version').__('Robo Maps') ; ?>:</h3>
		<ul>
			<li>- <?php echo __('Mobile optimized ', 'robo-maps').__('Maps', 'robo-maps'); ?></li>
			<li>- <?php _e('Optimized javascript code', 'robo-maps'); ?></li>
			<li>- <?php _e('OSM custom maps type', 'robo-maps'); ?></li>
			<li>- <?php _e('Minimaps', 'robo-maps'); ?></li>
			<li>- <?php _e('Robo Maps preview in admin section', 'robo-maps'); ?></li>
			<li>- <?php _e('Listing of the saved maps (no limits)', 'robo-maps'); ?></li>
			<li>- <?php _e('Listing of the saved markers (no limits)', 'robo-maps'); ?></li>
			<li>- <?php _e('Long and short map code', 'robo-maps'); ?></li>
			<li>- <?php _e('Access to support ticket system', 'robo-maps'); ?></li>
			<li>- <?php _e('Priority support tickets', 'robo-maps'); ?></li>
			<li>- <?php _e('Access to updates and new features', 'robo-maps'); ?></li>
		</ul>
		<a href="https://robosoft.co/products_info/?type=buy&amp;product=maps" class="button-primary"><?php _e('Buy Robo Maps Pro'); ?></a>
	</div>

	<div class="clearfix"></div>

	<hr />
	<h3><?php _e('User Guide'); ?></h3>
	<p>
		<?php _e('With Robo Maps you can insert Google maps into your WodPress posts, pages or widgets. You can insert / edit shortcode manually or with Robo Maps build in editor.'); ?><br/>
		<?php _e('Most effectife and simple way create shotcode by wizard. You can find RoiboMaps shortcode wizard button on toolbar of the buildin Wordpress editor. Please check screenshots below and description to understand how to use this plugin.'); ?>
	</p>

	<h3><?php _e('Shortcode Wizard'); ?>:</h3>
	<a href="<?php echo ROBO_MAPS_URL; ?>images/screenshots/screenshot1.png" target="_blank" class="robo-screenshot">
		<img src="<?php echo ROBO_MAPS_URL; ?>/images/screen_view.png" alt="<?php _e('Robo Maps Screen'); ?>" class="robo-map-pp-screenshot" />
	</a>
	<p>
		<?php _e("When you open for edit Post or Page you'll see such wizard button on editor toolbar. Click on this button and you'll wizard form."); ?><br/>
		<?php _e("When you open shortcode wizard you'll see there tabbed forms with all parameters for map configuration. Tabs of the main controls is General, View, Controls, Markers. By default it's open"); ?>
	</p>
	<div class="clearfix"></div>

	<h3><?php _e('General Settings'); ?>:</h3>
	<a href="<?php echo ROBO_MAPS_URL; ?>images/screenshots/screenshot2.png" target="_blank" class="robo-screenshot">
		<img src="<?php echo ROBO_MAPS_URL; ?>/images/screen_view.png" alt="<?php _e('Robo Maps Screen'); ?>" class="robo-map-pp-screenshot" />
	</a>
	<p><?php _e("On this tab  you'll see next fields"); ?>:<br />
		- <?php _e("address / coordinates - select the way you define location of the map, my coordinates or address"); ?><br />
		- <?php _e("below this filed you can find fields for coordinates or address filed, depend of first option"); ?><br />
		- <?php _e("option for marker behavior enable, disable or show after click"); ?><br />
		- <?php _e("marker caption field"); ?><br />
	</p>

	<h3><?php _e('View Settings'); ?>: </h3>
	<a href="<?php echo ROBO_MAPS_URL; ?>images/screenshots/screenshot3.png" target="_blank" class="robo-screenshot">
		<img src="<?php echo ROBO_MAPS_URL; ?>/images/screen_view.png" alt="<?php _e('Robo Maps Screen'); ?>" class="robo-map-pp-screenshot" />
	</a>
	<p><?php _e('View tab contain next fields'); ?>:<br />
		- <?php _e('size options width/height. Values you can define in pixels or in percents'); ?><br />
		- <?php _e('map view switch between standard Google maps values: ROADMAP, SATELLITE, HYBRID,  TERRAIN, OSM'); ?><br />
		- <?php _e('Zoom value of the default zooming for the map in the range from 0 to 18'); ?><br />
	</p>
	<div class="clearfix"></div>

	<h3><?php _e('Controls Settings'); ?>:</h3>
	<a href="<?php echo ROBO_MAPS_URL; ?>images/screenshots/screenshot4.png" target="_blank" class="robo-screenshot">
		<img src="<?php echo ROBO_MAPS_URL; ?>/images/screen_view.png" alt="<?php _e('Robo Maps Screen'); ?>" class="robo-map-pp-screenshot" />
	</a>
	<p>
		<?php _e('Controls tab contain next fields: Scroll Wheel Control, Street View, Zoom Control, Pan Control, Overview Map, Map Type'); ?><br/>
		<?php _e('All this options enable/disable such elements on map. So you can decide which elements do you wish to show on the front end map.'); ?>
	</p>
	<div class="clearfix"></div>

	<h3>Markers Settings: </h3>
	<a href="<?php echo ROBO_MAPS_URL; ?>images/screenshots/screenshot5.png" target="_blank" class="robo-screenshot">
		<img src="<?php echo ROBO_MAPS_URL; ?>/images/screen_view.png" alt="<?php _e('Robo Maps Screen'); ?>" class="robo-map-pp-screenshot" />
	</a>
	<p>
		<?php _e('Here you can use Add Marker button to create new markers, manage already created markers you  can in list below.  Every marker contain Label , Coordinate or address of the marker link and icon of the icon.'); ?><br/>
		<?php _e('After customization of the map you can save pre-configured map into maps list or directly insert  shortcode code to the post, page or widget.'); ?>
	</p>
	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery('.robo-screenshot').magnificPopup({type:'image'});
		});
	</script>
</div>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->