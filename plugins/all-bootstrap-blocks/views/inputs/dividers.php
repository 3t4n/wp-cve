<?php 
$block_folders = array();
$block_folders['dividers'] = 'dividers';

$templates = lightspeed_get_block_templates( $block_folders, false );
?>

<select 
	name="<?php echo esc_attr( $option['name'] ) ?>" 
	type="text" 
	id="<?php echo esc_attr( $option['name'] ) ?>" 
	data-form-type="other"
	data-default="<?php echo esc_attr( $option['default'] ) ?>"
>
	<?php foreach ( $templates['dividers'] as $template_key => $template ) : ?>
		<option 
			<?php echo esc_attr( ( $value == $template['value'] ) || ( !$value && $template['value'] == $option['default'] ) ? 'selected="selected"' : '' ) ?> 
			value="<?php echo esc_attr( $template['value'] ) ?>" 
		>
			<?php echo esc_attr( $template['label'] ) ?>
		</option>
	<?php endforeach; ?>
</select>