<?php

$generated_id       = str_replace( '-', '_', strtolower( $template['id'] ) );
$template_id        = $template['id'];
$dynamic_id         = 'reuseb_dynamic_preview_' . $template_id;
$dynamic_input_id   = 'reuseb_dynamic_meta_input_' . $generated_id;
$dynamic_input_name = '_reuseb_dynamic_meta_data_' . $generated_id;

$fileds = '';
if( isset( $template['args']['meta_preview']['fields'] ) ) {
    $fileds = json_encode( $template['args']['meta_preview']['fields'], true );
    $conditions = json_encode( $template['args']['meta_preview']['allLogicBlock'], true );
	$post_meta_values = get_post_meta($post->ID, $dynamic_input_name, true);
}else{
	$fileds = json_encode( $arg['meta_preview']['fields'], true );
	$conditions = json_encode( $arg['meta_preview']['allLogicBlock'], true );
  	$post_meta_values = get_user_meta($post->ID, $dynamic_input_name, true);
}

/**
 * Localize the updated data from database
 */

$custom_script = <<<EOD
    InitialMetaboxPreview['$template_id'] = {
    generatedId: '$generated_id',
    dynamicID: '$dynamic_id',
    dynamicInputId: '$dynamic_input_id',
    dynamicInputName: '$dynamic_input_name',
	fields: '$fileds',
	conditions: $conditions,
    metaValues: '$post_meta_values',
  };
EOD;
wp_add_inline_script('reuseb-admin-init', $custom_script);

?>

<div id="<?php echo esc_attr( $dynamic_id ) ?>" data-tpl="<?php echo esc_attr( $template_id ) ?>"></div>
<input type="hidden" id="<?php echo esc_attr( $dynamic_input_id ) ?>" name="<?php echo esc_attr( $dynamic_input_name ) ?>">
