<div class="notice notice-error no-format-notice inline"><p>Please select an import format in order to configure the following page.</p></div>

<h3><?php echo __( 'Taxonomy Settings', 'houzezpropertyfeed' ); ?></h3>

<p>Houzez has a number of <a href="<?php echo admin_url('admin.php?page=houzez_taxonomies'); ?>" target="_blank">taxonomies included</a> and we appreciate that everyone uses them slightly differently and that they come in differently depending on which format you're using. That's why we've added various options below to allow you to configure how these are used.</p>
<p><strong>Need help?</strong> Our <a href="https://houzezpropertyfeed.com/documentation/managing-imports/taxonomies/" target="_blank">documentation</a> covers this step in more detail.</p>

<hr>

<div id="taxonomy_mapping_sales_status">

	<h3><?php echo __( 'Sales Statuses Taxomomy', 'houzezpropertyfeed' ); ?></h3>

	<table class="form-table" id="taxonomy_mapping_table_sales_status">
		<tbody>
			<tr>
				<th>Value Sent In <span class="hpf-import-format-name"></span> Feed</th>
				<td style="padding-left:0; font-weight:600">Value In Houzez <a href="<?php echo admin_url( 'edit-tags.php?taxonomy=property_status&post_type=property'); ?>" target="_blank" style="color:inherit; text-decoration:none; margin-right:4px;" title="Configure Property Status Terms"><span class="dashicons dashicons-admin-tools"></span></a></td>
			</tr>
		</tbody>
	</table>
	<br>
	<a href="#sales_status" class="button add-additional-mapping"><span class="dashicons dashicons-plus-alt2"></span> Add Additional Mapping</a>

	<hr>

</div>

<div id="taxonomy_mapping_lettings_status">

	<h3><?php echo __( 'Lettings Status Taxomomy', 'houzezpropertyfeed' ); ?></h3>

	<table class="form-table" id="taxonomy_mapping_table_lettings_status">
		<tbody>
			<tr>
				<th>Value Sent In <span class="hpf-import-format-name"></span> Feed</th>
				<td style="padding-left:0; font-weight:600">Value In Houzez <a href="<?php echo admin_url( 'edit-tags.php?taxonomy=property_status&post_type=property'); ?>" target="_blank" style="color:inherit; text-decoration:none; margin-right:4px;" title="Configure Property Status Terms"><span class="dashicons dashicons-admin-tools"></span></a></td>
			</tr>
		</tbody>
	</table>
	<br>
	<a href="#lettings_status" class="button add-additional-mapping"><span class="dashicons dashicons-plus-alt2"></span> Add Additional Mapping</a>

	<hr>

</div>

<div id="taxonomy_mapping_property_type">

	<h3><?php echo __( 'Property Type Taxomomy', 'houzezpropertyfeed' ); ?></h3>

	<table class="form-table" id="taxonomy_mapping_table_property_type">
		<tbody>
			<tr>
				<th>Value Sent In <span class="hpf-import-format-name"></span> Feed</th>
				<td style="padding-left:0; font-weight:600">Value In Houzez <a href="<?php echo admin_url( 'edit-tags.php?taxonomy=property_type&post_type=property'); ?>" target="_blank" style="color:inherit; text-decoration:none; margin-right:4px;" title="Configure Property Type Terms"><span class="dashicons dashicons-admin-tools"></span></a></td>
			</tr>
		</tbody>
	</table>
	<br>
	<a href="#property_type" class="button add-additional-mapping"><span class="dashicons dashicons-plus-alt2"></span> Add Additional Mapping</a>

	<hr>

</div>

<?php
	$houzez_tax_settings = get_option('houzez_tax_settings', array() );
?>

<?php
	if ( !isset($houzez_tax_settings['property_city']) || ( isset($houzez_tax_settings['property_city']) && $houzez_tax_settings['property_city'] != 'disabled' ) )
	{
?>

<h3><?php echo __( 'City Taxomomy', 'houzezpropertyfeed' ); ?></h3>

<table class="form-table">
	<tbody>
		<tr>
			<th><label for="property_city_address_field"><?php echo __( 'Address Field To Use From <span class="hpf-import-format-name"></span> Feed', 'houzezpropertyfeed' ); ?></label></th>
			<td>
				<select name="property_city_address_field" id="property_city_address_field">
					<option value=""></option>
				</select>
			</td>
		</tr>
	</tbody>
</table>
<?php
	}
?>

<?php
	if ( !isset($houzez_tax_settings['property_area']) || ( isset($houzez_tax_settings['property_area']) && $houzez_tax_settings['property_area'] != 'disabled' ) )
	{
?>
<hr>

<h3><?php echo __( 'Area Taxomomy', 'houzezpropertyfeed' ); ?></h3>

<table class="form-table">
	<tbody>
		<tr>
			<th><label for="property_area_address_field"><?php echo __( 'Address Field To Use From <span class="hpf-import-format-name"></span> Feed', 'houzezpropertyfeed' ); ?></label></th>
			<td>
				<select name="property_area_address_field" id="property_area_address_field">
					<option value=""></option>
				</select>
			</td>
		</tr>
	</tbody>
</table>
<?php
	}
?>

<?php
	if ( !isset($houzez_tax_settings['property_state']) || ( isset($houzez_tax_settings['property_state']) && $houzez_tax_settings['property_state'] != 'disabled' ) )
	{
?>
<hr>

<h3><?php echo __( 'County / State Taxomomy', 'houzezpropertyfeed' ); ?></h3>

<table class="form-table">
	<tbody>
		<tr>
			<th><label for="property_state_address_field"><?php echo __( 'Address Field To Use From <span class="hpf-import-format-name"></span> Feed', 'houzezpropertyfeed' ); ?></label></th>
			<td>
				<select name="property_state_address_field" id="property_state_address_field">
					<option value=""></option>
				</select>
			</td>
		</tr>
	</tbody>
</table>
<?php
	}
?>

<?php
	if ( 
		( !isset($houzez_tax_settings['property_city']) || ( isset($houzez_tax_settings['property_city']) && $houzez_tax_settings['property_city'] != 'disabled' ) )
		||
		( !isset($houzez_tax_settings['property_area']) || ( isset($houzez_tax_settings['property_area']) && $houzez_tax_settings['property_area'] != 'disabled' ) )
		||
		( !isset($houzez_tax_settings['property_state']) || ( isset($houzez_tax_settings['property_state']) && $houzez_tax_settings['property_state'] != 'disabled' ) )
	)
	{
?>
<hr>

<h3><?php echo __( 'Location Taxomomies', 'houzezpropertyfeed' ); ?></h3>

<table class="form-table">
	<tbody>
		<tr>
			<th><label for="create_location_taxonomy_terms"><?php echo __( 'Create Location Terms If New Ones Found', 'houzezpropertyfeed' ); ?></label></th>
			<td>
				<input type="checkbox" name="create_location_taxonomy_terms" id="create_location_taxonomy_terms" value="yes"<?php if ( isset($import_settings['create_location_taxonomy_terms']) && $import_settings['create_location_taxonomy_terms'] === true ) { echo ' checked'; } ?>>
			</td>
		</tr>
	</tbody>
</table>

<p style="color:#999"><span class="dashicons dashicons-editor-help"></span> Should we create new city, area and county/state terms if a location is received in the <span class="hpf-import-format-name"></span> feed but doesn't exist already? If left unticked you'll need to create these manually.</p>
<?php
	}
?>