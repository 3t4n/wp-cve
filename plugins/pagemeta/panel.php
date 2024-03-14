<style type="text/css">
	#page_meta { font-size: 12px; }
	#page_meta th { width: 100px; }
	#page_meta input { width: 100%; }
</style>
<?php wp_nonce_field( 'wp_' . $this->tag, $this->tag . 'nonce' )?>
<table id="page_meta" class="form-table">
	<?php foreach ( $this->fields AS $key => $label ) : ?>
	<tr>
		<th><label><?php _e($label); ?></label></th>
		<td><input type="text" name="<?php esc_attr_e( $this->tag ); ?>[<?php esc_attr_e( $key ); ?>]" value="<?php esc_attr_e( $this->value( $key ) ); ?>" /></td>
	</tr>
	<?php endforeach; ?>
</table>