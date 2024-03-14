<label class="pb-settings-label">
  <span class="control"><?php echo wp_kses_post( $field['name'] ); ?> 
</label>
<table class="js-mobile-layouts-list mobile-layouts-list" data-field="<?php echo esc_attr( $field['code'] ); ?>">
  <thead>
	<tr>
	  <th><?php esc_html_e( 'Number of columns', 'photoblocks' ); ?></th>
	  <th><?php esc_html_e( 'Max resolution (px)', 'photoblocks' ); ?></th>
	  <th></th>
	</tr>
  </thead>
  <tbody id="mobile-layout-<?php echo esc_attr( $field['code'] ); ?>"></tbody>
</table>
<input type="hidden" value="" name="<?php echo esc_attr( $field['code'] ); ?>" class="js-serialize js-serialize-mobile-layout"></span>
<a class="pb-button" onclick="PBAdmin.addMobileLayout(this)" data-field="<?php echo esc_attr( $field['code'] ); ?>"><?php esc_html_e( 'Add layout', 'photoblocks' ); ?></a>

<div class="pb-settings-description"><p><?php echo wp_kses_post( $field['description'] ); ?></p></div>
