<div class="notice notice-error no-format-notice inline"><p>Please select an export format in order to configure the following page.</p></div>

<h3><?php echo __( 'Taxonomy Settings', 'houzezpropertyfeed' ); ?></h3>

<p>Below you can map the taxonomies you have setup in Houzez to the accepted values by the third party accepting the export.</p>
<p><strong>Need help?</strong> Our <a href="https://houzezpropertyfeed.com/documentation/managing-exports/taxonomies/" target="_blank">documentation</a> covers this step in more detail.</p>

<hr>

<div id="taxonomy_mapping_status">

	<h3><?php echo __( 'Status Taxomomy', 'houzezpropertyfeed' ); ?></h3>

	<table class="form-table" id="taxonomy_mapping_table_status">
		<tbody>
			<tr>
				<th>Value In Houzez <a href="<?php echo admin_url( 'edit-tags.php?taxonomy=property_status&post_type=property'); ?>" target="_blank" style="color:inherit; text-decoration:none; margin-right:4px;" title="Configure Property Status Terms"><span class="dashicons dashicons-admin-tools"></span></a></th>
				<td style="padding-left:0; font-weight:600">Value Sent In <span class="hpf-export-format-name"></span> Feed</td>
			</tr>
			<?php
				$terms = get_terms( array(
	                'taxonomy'   => 'property_status',
	                'hide_empty' => false,
	            ) );

	            if ( is_array($terms) && !empty($terms) )
	            {
	                foreach ( $terms as $term )
	                {
	        ?>
	        <tr>
				<td style="padding-left:0"><?php echo __( $term->name, 'houzezpropertyfeed' ); ?></td>
				<td style="padding-left:0">
					<select name="taxonomy_mapping[property_status][<?php echo $term->term_id; ?>]">
						<option value=""></option>
					</select>
				</td>
			</tr>
	        <?php
	                }
	            }
			?>
		</tbody>
	</table>

	<hr>

</div>

<div id="taxonomy_mapping_property_type">

	<h3><?php echo __( 'Property Type Taxomomy', 'houzezpropertyfeed' ); ?></h3>

	<table class="form-table" id="taxonomy_mapping_table_property_type">
		<tbody>
			<tr>
				<th>Value In Houzez <a href="<?php echo admin_url( 'edit-tags.php?taxonomy=property_type&post_type=property'); ?>" target="_blank" style="color:inherit; text-decoration:none; margin-right:4px;" title="Configure Property Status Terms"><span class="dashicons dashicons-admin-tools"></span></a></th>
				<td style="padding-left:0; font-weight:600">Value Sent In <span class="hpf-export-format-name"></span> Feed</td>
			</tr>
			<?php
				$terms = get_terms( array(
	                'taxonomy'   => 'property_type',
	                'hide_empty' => false,
	            ) );

	            if ( is_array($terms) && !empty($terms) )
	            {
	                foreach ( $terms as $term )
	                {
	        ?>
	        <tr>
				<td style="padding-left:0"><?php echo __( $term->name, 'houzezpropertyfeed' ); ?></td>
				<td style="padding-left:0">
					<select name="taxonomy_mapping[property_type][<?php echo $term->term_id; ?>]">
						<option value=""></option>
					</select>
				</td>
			</tr>
	        <?php
	                }
	            }
			?>
		</tbody>
	</table>

</div>

<div id="taxonomy_mapping_price_qualifier">

	<h3><?php echo __( 'Price Qualifier', 'houzezpropertyfeed' ); ?></h3>

	<table class="form-table" id="taxonomy_mapping_table_price_qualifier">
		<tbody>
			<tr>
				<th>Value In Houzez </th>
				<td style="padding-left:0; font-weight:600">Value Sent In <span class="hpf-export-format-name"></span> Feed</td>
			</tr>
			<?php
				// get unique price qualifiers
				$unique_price_qualifier_values = $wpdb->get_results( "SELECT DISTINCT meta_value FROM $wpdb->postmeta pm, $wpdb->posts p WHERE meta_key = 'fave_property_price_prefix' and pm.post_id=p.ID and p.post_type='property' ORDER BY meta_value", ARRAY_A );
				
	            if ( is_array($unique_price_qualifier_values) && !empty($unique_price_qualifier_values) )
	            {
	                foreach ( $unique_price_qualifier_values as $unique_price_qualifier_value )
	                {
	                	if ( empty($unique_price_qualifier_value['meta_value']) ) { continue; }
	        ?>
	        <tr>
				<td style="padding-left:0"><?php echo esc_html($unique_price_qualifier_value['meta_value']); ?></td>
				<td style="padding-left:0">
					<select name="taxonomy_mapping[price_qualifier][<?php echo sanitize_title($unique_price_qualifier_value['meta_value']); ?>]">
						<option value=""></option>
					</select>
				</td>
			</tr>
	        <?php
	                }
	            }
			?>
		</tbody>
	</table>

</div>