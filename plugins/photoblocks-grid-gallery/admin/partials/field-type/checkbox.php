<div class="pb-settings-label">
	<?php echo wp_kses_post( $field['name'] ); ?>
	<span class="control js-checkbox-list pb-checkbox-list" data-field="<?php echo esc_attr( $field['code'] ); ?>">
		<?php foreach ( $field['values'] as $label => $value ) : ?>
			<label>
				<input type="checkbox" value="<?php echo esc_attr( $value ); ?>" class="js-checkbox">
				<?php esc_html_e( $label, 'photoblocks' ); ?>
			</label>
		<?php endforeach ?>
		<input type="hidden" class="js-serialize js-serialize-checkboxes" name="<?php echo esc_attr( $field['code'] ); ?>" value="">
	</span>
</div>
<div class="pb-settings-description"><p><?php wp_kses_post( $field['description'] ); ?></p></div>
