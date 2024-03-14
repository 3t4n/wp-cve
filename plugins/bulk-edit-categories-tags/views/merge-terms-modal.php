<?php defined( 'ABSPATH' ) || exit; ?>
<div class="remodal merge-terms-modal" data-remodal-id="merge-terms-modal" data-remodal-options="closeOnOutsideClick: false, hashTracking: false">

	<div class="modal-content">
		<form class="merge-terms-form vgse-modal-form " action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" method="POST">
			<h3><?php _e('Merge terms', 'vg_sheet_editor' ); ?></h3>
			<ul class="unstyled-list">
				<li class="terms-to-remove">
					<label><?php _e('Replace these terms', 'vg_sheet_editor' ); ?>  <a href="#" data-wpse-tooltip="right" aria-label="<?php _e('Select the categories that will be removed.', 'vg_sheet_editor' ); ?>">( ? )</a></label>
					<select name="vgse_terms_source">
						<option value="">- -</option>
						<option value="individual"><?php _e('Select individual items', 'vg_sheet_editor' ); ?></option>
						<option value="search"><?php _e('Select all the items from a search', 'vg_sheet_editor' ); ?></option>
						<option value="duplicates"><?php _e('Merge all the duplicates with same name and hierarchy', 'vg_sheet_editor' ); ?></option>
					</select>

					<br>
					<select name="terms_to_remove[]" data-remote="true" data-action="vgse_search_taxonomy_terms" data-output-format="%slug%" data-min-input-length="3" data-placeholder="<?php esc_attr_e('Enter name...', 'vg_sheet_editor' ); ?>" data-taxonomies="<?php echo esc_attr($post_type); ?>" data-post-type="<?php echo esc_attr($post_type); ?>" data-nonce="<?php echo esc_attr($nonce); ?>"  class="select2 individual-term-selector" multiple>
						<option></option>
					</select>
				</li>	
				<li class="final-term">
					<label><?php _e('with this term', 'vg_sheet_editor' ); ?>  <a href="#" data-wpse-tooltip="right" aria-label="<?php _e('This term will remain saved.', 'vg_sheet_editor' ); ?>">( ? )</a></label>
					<select name="final_term" data-remote="true" data-min-input-length="3" data-action="vgse_search_taxonomy_terms" data-output-format="%slug%" data-placeholder="<?php esc_attr_e('Enter a name...', 'vg_sheet_editor' ); ?>" data-post-type="<?php echo esc_attr($post_type); ?>" data-taxonomies="<?php echo esc_attr($post_type); ?>" data-nonce="<?php echo esc_attr($nonce); ?>"  class="select2 final-term-selector">
						<option></option>
					</select>
				</li>	
				<li class="confirmation">
					<label class="use-search-query-container"><input type="checkbox" value="yes"  name="use_search_query"><?php _e('I understand it will remove all the terms from my search and keep the term selected above.', 'vg_sheet_editor' ); ?> <a href="#" data-wpse-tooltip="right" aria-label="<?php _e('For example, if you searched for categories with keyword Car, it will combine all the found categories into one', 'vg_sheet_editor' ); ?>">( ? )</a><input type="hidden" name="filters"></label>
				</li>
			</ul>
			<div class="response">
			</div>

			<input type="hidden" value="vgse_merge_terms" name="action">
			<input type="hidden" value="<?php echo esc_attr($nonce); ?>" name="nonce">
			<input type="hidden" value="<?php echo esc_attr($post_type); ?>" name="post_type">
			<br>
			<button class="remodal-confirm" type="submit"><?php _e('Execute', 'vg_sheet_editor' ); ?> </button>
			<button data-remodal-action="confirm" class="remodal-cancel"><?php _e('Close', 'vg_sheet_editor' ); ?></button>
		</form>
	</div>
</div>