<?php
/**
 * Localize the updated data from database
 */
 	use Reuse\Builder\Provider;
	$settings_array = new Provider();
	$settings_fields = $settings_array->reuse_builder_settings_array();
	$reuse_builder_settings = stripslashes_deep(get_option('reuseb_settings', true ));

	wp_localize_script( 'reuseb_settings', 'REUSEB_ADMIN',
		apply_filters('reuseb_admin_generator_localize_args', array(
			'REUSEB_SETTINGS' 	=> ( isset($reuse_builder_settings) && $reuse_builder_settings != 1 ) ? $reuse_builder_settings : '{}',
			'fields' 						=> $settings_fields,
	) ));
?>

<h1><?php _e('Reuse Builder Settings', 'userplace') ?></h1>
<div id="reuse_builder_settings" style="background: #fff; padding: 30px;"></div>


<input type="hidden" id="_reuse_builder_settings" name="_reuse_builder_settings" value="<?php echo esc_attr( ( isset($reuse_builder_settings) && $reuse_builder_settings != 1 ) ? $reuse_builder_settings : '{}') ?>">
