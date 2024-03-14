<?php defined( 'ABSPATH' ) || exit; ?>
<div class="remodal merge-attributes-modal" data-remodal-id="merge-attributes-modal" data-remodal-options="closeOnOutsideClick: false, hashTracking: false">

	<div class="modal-content">
		<form class="merge-attributes-form vgse-modal-form " action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" method="POST">
			<h3><?php _e('Merge attributes', 'vg_sheet_editor' ); ?></h3>
			<ul class="unstyled-list">
				<li class="attributes-to-remove">
					<label><?php _e('Replace these attributes', 'vg_sheet_editor' ); ?>  <a href="#" data-wpse-tooltip="right" aria-label="<?php _e('Select the attributes that will be removed.', 'vg_sheet_editor' ); ?>">( ? )</a></label>
					<select name="vgse_attributes_source">
						<option value="">- -</option>
						<option value="individual"><?php _e('Select individual items', 'vg_sheet_editor' ); ?></option>
						<option value="search"><?php _e('Select all the items from a search', 'vg_sheet_editor' ); ?></option>
						<option value="duplicates"><?php _e('Merge all the duplicates with same name', 'vg_sheet_editor' ); ?></option>
					</select>

					<br>
					<select name="attributes_to_remove[]" data-remote="true" data-action="vgse_search_attribute_taxonomies" data-min-input-length="3" data-placeholder="<?php esc_attr_e('Enter name...', 'vg_sheet_editor' ); ?>" data-post-type="<?php echo esc_attr($post_type); ?>" data-nonce="<?php echo esc_attr($nonce); ?>"  class="select2 individual-attribute-selector" multiple>
						<option></option>
					</select>
				</li>	
				<li class="final-attribute">
					<label><?php _e('with this attribute', 'vg_sheet_editor' ); ?>  <a href="#" data-wpse-tooltip="right" aria-label="<?php _e('This attribute will remain saved.', 'vg_sheet_editor' ); ?>">( ? )</a></label>
					<select name="final_attribute" data-remote="true" data-min-input-length="3" data-action="vgse_search_attribute_taxonomies" data-placeholder="<?php esc_attr_e('Enter a name...', 'vg_sheet_editor' ); ?>" data-post-type="<?php echo esc_attr($post_type); ?>" data-nonce="<?php echo esc_attr($nonce); ?>"  class="select2 final-attribute-selector">
						<option></option>
					</select>
				</li>	
				<li class="confirmation-search confirmation-wrapper">
					<label class="use-search-query-container"><input type="checkbox" value="yes"  name="confirmation"><?php _e('I understand it will remove all the attributes from my search and keep the attribute selected above, and the products and variations will be updated too.', 'vg_sheet_editor' ); ?> <a href="#" data-wpse-tooltip="right" aria-label="<?php _e('For example, if you searched for attributes with keyword Color, it will combine all the found attributes into one', 'vg_sheet_editor' ); ?>">( ? )</a><input type="hidden" name="filters"></label>
				</li>
				<li class="confirmation-individual confirmation-wrapper">
					<label><input type="checkbox" value="yes"  name="confirmation"><?php _e('I understand it will remove all the selected attributes, their terms inside will be transferred to the final attribute, the duplicate terms inside the final attribute will be merged, and the products and variations will be updated to use the final attribute, and I also made a backup because the changes are not reversible without a backup.', 'vg_sheet_editor' ); ?></label>
				</li>
				<li class="confirmation-duplicates confirmation-wrapper">
					<label><input type="checkbox" value="yes"  name="confirmation"><?php _e('I understand it will remove all the attributes with duplicate name and their terms inside, it will keep one attribute per name only, and the products and variations will be updated to use the final attribute, and I also made a backup because the changes are not reversible without a backup.', 'vg_sheet_editor' ); ?></label>
				</li>
			</ul>
			<div class="response">
			</div>

			<input type="hidden" value="vgse_merge_attributes" name="action">
			<input type="hidden" value="<?php echo esc_attr($nonce); ?>" name="nonce">
			<input type="hidden" value="<?php echo esc_attr($post_type); ?>" name="post_type">
			<br>
			<button class="remodal-confirm" type="submit"><?php _e('Execute', 'vg_sheet_editor' ); ?> </button>
			<button data-remodal-action="confirm" class="remodal-cancel"><?php _e('Close', 'vg_sheet_editor' ); ?></button>
		</form>
	</div>
</div>