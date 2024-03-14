<?php
#===============================================
# This template creates the input form fields for EM Locations.
# https://wordpress.org/plugins/stonehenge-em-osm/
# To edit this file, first copy it to /wp-content/themes/your-theme/stonehenge/
# VERSION: 4.2.0
# Since 4.2.0 supports location types.
#===============================================
global $EM_Event;
?>
	<tr class="osm-location-data-address">
		<th><?php echo __em('Address:')?> <?php echo $required; ?>&nbsp;</th>
		<td><input id="location-address" type="text" name="location_address" value="<?php echo esc_attr($EM_Location->output('#_LOCATIONADDRESS'), ENT_QUOTES); ?>"> </td>
	</tr>
	<tr class="osm-location-data-town">
		<th><?php echo __em('City/Town:')?> <?php echo $required; ?>&nbsp;</th>
		<td><input id="location-town" type="text" name="location_town" value="<?php echo esc_attr($EM_Location->output('#_LOCATIONTOWN'), ENT_QUOTES); ?>"> </td>
	</tr>
	<tr class="osm-location-data-state">
		<th><?php echo __em('State/County:')?>&nbsp;</th>
		<td><input id="location-state" type="text" name="location_state" value="<?php echo esc_attr($EM_Location->output('#_LOCATIONSTATE'), ENT_QUOTES); ?>"></td>
	</tr>
	<tr class="osm-location-data-postcode">
		<th><?php echo __em('Postcode:')?>&nbsp;</th>
		<td><input id="location-postcode" type="text" name="location_postcode" value="<?php echo esc_attr($EM_Location->output('#_LOCATIONPOSTCODE'), ENT_QUOTES); ?>"></td>
	</tr>
	<tr class="osm-location-data-region">
		<th><?php echo __em('Region:')?>&nbsp;</th>
		<td><input id="location-region" type="text" name="location_region" value="<?php echo esc_attr($EM_Location->output('#_LOCATIONREGION'), ENT_QUOTES); ?>">
			<input id="location-region-wpnonce" type="hidden" value="<?php echo wp_create_nonce('search_regions'); ?>">
		</td>
	</tr>
	<tr class="osm-location-data-country">
		<th><?php echo __em('Country:')?> <?php echo $required; ?>&nbsp;</th>
		<td><select id="location-country" name="location_country">
				<?php
				foreach( em_get_countries(__em('none selected')) as $country_key => $country_name) {
					$selected = ($EM_Location->location_country === $country_key || ($EM_Location->location_country == '' && $EM_Location->location_id == '' && get_option('dbem_location_default_country')==$country_key)) ? 'selected="selected"' : '';
					?>
					<option value="<?php echo esc_attr($country_key, ENT_QUOTES); ?>" <?php echo esc_attr($selected); ?>><?php echo esc_attr($country_name); ?></option>
					<?php
					;
				} ?>
			</select>
		</td>
	</tr>
	<?php echo $EM_OSM->show_per_location_select_dropdowns( $EM_Location ); ?>
	<?php
		// This is incorrectly implemented by the EM team! Not being saved anywhere, so 100% unusable. No need to show it, as this leads to confusion.

		/* if( is_object($EM_Event) ): ?>
		<tr class="osm-location-data-url">
			<th><?php echo esc_html( __em('URL:'));?>&nbsp;</th>
			<td>
				<input id="location-url" type="text" name="location_url" value="<?php echo esc_attr($EM_Location->location_url); ?>" />
			</td>
		</tr>
	<?php endif; */ ?>
</tbody>
</table>
	<?php echo $EM_OSM->admin_map($EM_Location); ?>
	<br style="clear:both;">
	<div id="osm-button">
		<?php echo $EM_OSM->show_search_tip(); ?>
	</div>
