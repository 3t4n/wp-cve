<label class="pb-settings-label">
	<span class="control"><?php echo wp_kses_post( $field['name'] ); ?>
		<ul class="js-dynamic-list pb-dynamic-list" data-field="<?php echo esc_attr( $field['code'] ); ?>" id="dynamic-list-<?php echo esc_attr( $field['code'] ); ?>">
		</ul>
		<a class="pb-button" onclick="PBAdmin.addFilter(this)" data-field="<?php echo esc_attr( $field['code'] ); ?>"><?php esc_html_e( 'Add filter', 'photoblocks' ); ?></a>
		<input type="hidden" value="" name="<?php echo esc_attr( $field['code'] ); ?>" class="js-serialize js-serialize-list"></span>
</label>
<div class="pb-settings-description"><p><?php echo wp_kses_post( $field['description'] ); ?></p></div>
