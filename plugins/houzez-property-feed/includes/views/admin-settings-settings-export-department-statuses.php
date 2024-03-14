<h3><?php echo __( 'Department Statuses', 'houzezpropertyfeed' ); ?></h3>

<p>Here you can select which statuses determine whether a property should be sent in exports as a sales or lettings property. Statuses can be <a href="<?php echo admin_url('edit-tags.php?taxonomy=property_status&post_type=property'); ?>" target="_blank">configured here</a>.</p>

<table class="form-table">
	<tbody>
		<tr>
			<th><label for="sales_statuses"><?php echo __( 'Sales Statuses', 'houzezpropertyfeed' ); ?></label></th>
			<td>

				<select name="sales_statuses[]" id="sales_statuses" multiple style="width:100%; max-width:250px; height:130px;">
				<?php
					$terms = get_terms( array(
		                'taxonomy'   => 'property_status',
		                'hide_empty' => false,
		            ) );

		            if ( is_array($terms) && !empty($terms) )
		            {
		                foreach ( $terms as $term )
		                {
		                    echo '<option value="' . $term->term_id . '"';
		                    if ( isset($options['sales_statuses']) && is_array($options['sales_statuses']) && in_array($term->term_id, $options['sales_statuses']) )
		                    {
		                    	echo ' selected';
		                    }
		                    echo '>' . $term->name . '</option>';
		                }
		            }
				?>
				</select>

				<div style="color:#999; font-size:13px; margin-top:5px;">Ctrl + click to select multiple</div>

			</td>
		</tr>
		<tr>
			<th><label for="lettings_statuses"><?php echo __( 'Lettings Statuses', 'houzezpropertyfeed' ); ?></label></th>
			<td>

				<select name="lettings_statuses[]" id="lettings_statuses" multiple style="width:100%; max-width:250px; height:130px;">
				<?php
					$terms = get_terms( array(
		                'taxonomy'   => 'property_status',
		                'hide_empty' => false,
		            ) );

		            if ( is_array($terms) && !empty($terms) )
		            {
		                foreach ( $terms as $term )
		                {
		                    echo '<option value="' . $term->term_id . '"';
		                    if ( isset($options['lettings_statuses']) && is_array($options['lettings_statuses']) && in_array($term->term_id, $options['lettings_statuses']) )
		                    {
		                    	echo ' selected';
		                    }
		                    echo '>' . $term->name . '</option>';
		                }
		            }
				?>
				</select>

				<div style="color:#999; font-size:13px; margin-top:5px;">Ctrl + click to select multiple</div>

			</td>
		</tr>
	</tbody>
</table>