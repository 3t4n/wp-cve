<h3><?php echo __( 'Media', 'houzezpropertyfeed' ); ?></h3>

<p><?php echo __( 'Specify which fields should be used for media', 'houzezpropertyfeed' ); ?>:</p>

<h3><?php echo __( 'Images', 'houzezpropertyfeed' ); ?></h3>

<table class="form-table media-image-settings">
	<tbody>
		<tr class="csv-tip">
			<th><label for="image_field_arrangement_individual"><?php echo __( 'Image Arrangement', 'houzezpropertyfeed' ); ?></label></th>
			<td>
				<label style="display:block; padding:3px 0">
					<input type="radio" name="image_field_arrangement" id="image_field_arrangement_individual" value=""<?php echo ( !isset($import_settings['image_field_arrangement']) || ( isset($import_settings['image_field_arrangement']) && $import_settings['image_field_arrangement'] == '' ) ) ? ' checked' : ''; ?>>
					Each URL in a separate field
				</label>
				<label style="display:block; padding:3px 0">
					<input type="radio" name="image_field_arrangement" id="image_field_arrangement_comma_delimited" value="comma_delimited"<?php echo ( isset($import_settings['image_field_arrangement']) && $import_settings['image_field_arrangement'] == 'comma_delimited' ) ? ' checked' : ''; ?>>
					All URL's in one field
				</label>
			</td>
		</tr>
		<tr class="media-comma-delimited-row">
			<th><label for="image_field"><?php echo __( 'Field Containing Images', 'houzezpropertyfeed' ); ?></label></th>
			<td>
				<select name="image_field" id="image_field">
					<?php 
						$options = ( isset($import_settings['property_field_options']) && !empty($import_settings['property_field_options']) ) ? json_decode($import_settings['property_field_options']) : array();

						$new_options = array();
						if ( !empty($options) )
						{
							foreach ( $options as $option_key => $option_value )
							{
								$field_name = $option_value;

								$new_options[$field_name] = $field_name;
							}
						}

						$options = $new_options; 

						foreach ( $options as $option_key => $option_value )
						{
							echo '<option value="' . esc_attr($option_key) . '"';
							if ( isset($import_settings['image_field']) && $import_settings['image_field'] == $option_key ) { echo ' selected'; }
							echo '>' . esc_html($option_value) . '</option>';
						} 
					?>
				</select>
			</td>
		</tr>
		<tr class="media-comma-delimited-row">
			<th><label for="image_field_delimiter"><?php echo __( 'Delimiter Character', 'houzezpropertyfeed' ); ?></label></th>
			<td>
				<input type="text" name="image_field_delimiter" id="image_field_delimiter" style="width:50px;" value="<?php echo isset($import_settings['image_field_delimiter']) ? $import_settings['image_field_delimiter'] : ','; ?>">
			</td>
		</tr>
		<tr class="media-individual-row">
			<th><label for="image_fields"><?php echo __( 'Fields Containing Images', 'houzezpropertyfeed' ); ?></label></th>
			<td>
				<textarea name="image_fields" id="image_fields" placeholder="{/images/image[1]}&#10;{/images/image[2]}&#10;{/images/image[3]/url}|{/images/image[3]/caption}&#10;{/image[0]}.jpg" style="width:100%; height:120px; max-width:500px;"><?php echo isset($import_settings['image_fields']) ? $import_settings['image_fields'] : ''; ?></textarea>
				<div style="color:#999; font-size:13px; margin-top:5px;">
					Enter one image URL per line.<br>
					Separate with a pipe (|) character to specify the image caption.<span class="xml-tip"><br>
					Note: Uses the <a href="https://www.w3schools.com/xml/xpath_syntax.asp" target="_blank">XPath syntax</a>.</span>
				</div>
			</td>
		</tr>
	</tbody>
</table>

<hr>

<h3><?php echo __( 'Floorplans', 'houzezpropertyfeed' ); ?></h3>

<table class="form-table media-floorplan-settings">
	<tbody>
		<tr class="csv-tip">
			<th><label for="floorplan_field_arrangement_individual"><?php echo __( 'Floorplan Arrangement', 'houzezpropertyfeed' ); ?></label></th>
			<td>
				<label style="display:block; padding:3px 0">
					<input type="radio" name="floorplan_field_arrangement" id="floorplan_field_arrangement_individual" value=""<?php echo ( !isset($import_settings['floorplan_field_arrangement']) || ( isset($import_settings['floorplan_field_arrangement']) && $import_settings['floorplan_field_arrangement'] == '' ) ) ? ' checked' : ''; ?>>
					Each URL in a separate field
				</label>
				<label style="display:block; padding:3px 0">
					<input type="radio" name="floorplan_field_arrangement" id="floorplan_field_arrangement_comma_delimited" value="comma_delimited"<?php echo ( isset($import_settings['floorplan_field_arrangement']) && $import_settings['floorplan_field_arrangement'] == 'comma_delimited' ) ? ' checked' : ''; ?>>
					All URL's in one field
				</label>
			</td>
		</tr>
		<tr class="media-comma-delimited-row">
			<th><label for="floorplan_field"><?php echo __( 'Field Containing Floorplans', 'houzezpropertyfeed' ); ?></label></th>
			<td>
				<select name="floorplan_field" id="floorplan_field">
					<?php 
						$options = ( isset($import_settings['property_field_options']) && !empty($import_settings['property_field_options']) ) ? json_decode($import_settings['property_field_options']) : array();

						$new_options = array();
						if ( !empty($options) )
						{
							foreach ( $options as $option_key => $option_value )
							{
								$field_name = $option_value;

								$new_options[$field_name] = $field_name;
							}
						}

						$options = $new_options; 

						foreach ( $options as $option_key => $option_value )
						{
							echo '<option value="' . esc_attr($option_key) . '"';
							if ( isset($import_settings['floorplan_field']) && $import_settings['floorplan_field'] == $option_key ) { echo ' selected'; }
							echo '>' . esc_html($option_value) . '</option>';
						} 
					?>
				</select>
			</td>
		</tr>
		<tr class="media-comma-delimited-row">
			<th><label for="floorplan_field_delimiter"><?php echo __( 'Delimiter Character', 'houzezpropertyfeed' ); ?></label></th>
			<td>
				<input type="text" name="floorplan_field_delimiter" id="floorplan_field_delimiter" style="width:50px;" value="<?php echo isset($import_settings['floorplan_field_delimiter']) ? $import_settings['floorplan_field_delimiter'] : ','; ?>">
			</td>
		</tr>
		<tr class="media-individual-row">
			<th><label for="floorplan_fields"><?php echo __( 'Fields Containing Floorplans', 'houzezpropertyfeed' ); ?></label></th>
			<td>
				<textarea name="floorplan_fields" id="floorplan_fields" placeholder="{/floorplans/floorplan[1]/url}|{/floorplans/floorplan[1]/caption}&#10;{/floorplans/floorplan[2]}" style="width:100%; height:120px; max-width:500px;"><?php echo isset($import_settings['floorplan_fields']) ? $import_settings['floorplan_fields'] : ''; ?></textarea>
				<div style="color:#999; font-size:13px; margin-top:5px;">
					Enter one floorplan URL per line.<br>
					Separate with a pipe (|) character to specify the floorplan caption.<span class="xml-tip"><br>
					Note: Uses the <a href="https://www.w3schools.com/xml/xpath_syntax.asp" target="_blank">XPath syntax</a>.</span>
				</div>
			</td>
		</tr>
	</tbody>
</table>

<hr>

<h3><?php echo __( 'Documents (Brochures, EPCs etc)', 'houzezpropertyfeed' ); ?></h3>

<table class="form-table media-document-settings">
	<tbody>
		<tr class="csv-tip">
			<th><label for="document_field_arrangement_individual"><?php echo __( 'Document Arrangement', 'houzezpropertyfeed' ); ?></label></th>
			<td>
				<label style="display:block; padding:3px 0">
					<input type="radio" name="document_field_arrangement" id="document_field_arrangement_individual" value=""<?php echo ( !isset($import_settings['document_field_arrangement']) || ( isset($import_settings['document_field_arrangement']) && $import_settings['document_field_arrangement'] == '' ) ) ? ' checked' : ''; ?>>
					Each URL in a separate field
				</label>
				<label style="display:block; padding:3px 0">
					<input type="radio" name="document_field_arrangement" id="document_field_arrangement_comma_delimited" value="comma_delimited"<?php echo ( isset($import_settings['document_field_arrangement']) && $import_settings['document_field_arrangement'] == 'comma_delimited' ) ? ' checked' : ''; ?>>
					All URL's in one field
				</label>
			</td>
		</tr>
		<tr class="media-comma-delimited-row">
			<th><label for="document_field"><?php echo __( 'Field Containing Documents', 'houzezpropertyfeed' ); ?></label></th>
			<td>
				<select name="document_field" id="document_field">
					<?php 
						$options = ( isset($import_settings['property_field_options']) && !empty($import_settings['property_field_options']) ) ? json_decode($import_settings['property_field_options']) : array();

						$new_options = array();
						if ( !empty($options) )
						{
							foreach ( $options as $option_key => $option_value )
							{
								$field_name = $option_value;

								$new_options[$field_name] = $field_name;
							}
						}

						$options = $new_options; 

						foreach ( $options as $option_key => $option_value )
						{
							echo '<option value="' . esc_attr($option_key) . '"';
							if ( isset($import_settings['document_field']) && $import_settings['document_field'] == $option_key ) { echo ' selected'; }
							echo '>' . esc_html($option_value) . '</option>';
						} 
					?>
				</select>
			</td>
		</tr>
		<tr class="media-comma-delimited-row">
			<th><label for="document_field_delimiter"><?php echo __( 'Delimiter Character', 'houzezpropertyfeed' ); ?></label></th>
			<td>
				<input type="text" name="document_field_delimiter" id="document_field_delimiter" style="width:50px;" value="<?php echo isset($import_settings['document_field_delimiter']) ? $import_settings['document_field_delimiter'] : ','; ?>">
			</td>
		</tr>
		<tr class="media-individual-row">
			<th><label for="document_fields"><?php echo __( 'Fields Containing Documents', 'houzezpropertyfeed' ); ?></label></th>
			<td>
				<textarea name="document_fields" id="document_fields" placeholder="{/brochureURL}|Brochure&#10;{/epcs/epc[1]}&#10;{/documents/document[1]/url}|{/documents/document[1]/caption}" style="width:100%; height:120px; max-width:500px;"><?php echo isset($import_settings['document_fields']) ? $import_settings['document_fields'] : ''; ?></textarea>
				<div style="color:#999; font-size:13px; margin-top:5px;">
					Enter one document URL per line.<br>
					Separate with a pipe (|) character to specify the document caption.<span class="xml-tip"><br>
					Note: Uses the <a href="https://www.w3schools.com/xml/xpath_syntax.asp" target="_blank">XPath syntax</a>.</span>
				</div>
			</td>
		</tr>
	</tbody>
</table>

<hr>

<h3><?php echo __( 'Media Options', 'houzezpropertyfeed' ); ?></h3>

<table class="form-table">
	<tbody>
		<tr>
			<th><label for="media_download_clause_url_change"><?php echo __( 'Download Media', 'houzezpropertyfeed' ); ?></label></th>
			<td style="padding-top:20px;">
				<div style="padding:3px 0"><label><input type="radio" name="media_download_clause" id="media_download_clause_always" value="always"<?php echo ( isset($import_settings['media_download_clause']) && $import_settings['media_download_clause'] == 'always' ) ? ' checked' : ''; ?>> Every time an import runs</label></div>
				<div style="padding:3px 0"><label><input type="radio" name="media_download_clause" id="media_download_clause_url_change" value="url_change"<?php echo ( !isset($import_settings['media_download_clause']) || ( isset($import_settings['media_download_clause']) && $import_settings['media_download_clause'] == 'url_change' ) ) ? ' checked' : ''; ?>> Only if media URL changes (recommended)</label></div>
			</td>
		</tr>
	</tbody>
</table>