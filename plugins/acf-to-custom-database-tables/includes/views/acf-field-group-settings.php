<?php
global $field_group;
global $wpdb;

acf_render_field_wrap(array(
	'label'			=> 'Custom Table',
	'instructions'	=> 'Create custom table for this Field Group.',
	'type'			=> 'true_false',
	'name'			=> ACF_CT_ENABLE,
	'prefix'		=> 'acf_field_group',
	'value'			=> (isset($field_group[ACF_CT_ENABLE])) ? $field_group[ACF_CT_ENABLE] : 0,
	'ui'			=> 1,
));

acf_render_field_wrap(array(
	'label'		=> 'Custom Table Name',
	'name'		=> ACF_CT_TABLE_NAME,
	'prefix'	=> 'acf_field_group',
	'type'		=> 'text',
	'prepend'	=> $wpdb->prefix,
	'value'		=> (isset($field_group[ACF_CT_TABLE_NAME])) ? $field_group[ACF_CT_TABLE_NAME] : '',
));

?>
<script type="text/javascript">
    if( typeof acf !== 'undefined' ) {
        acf.newPostbox({
            'id': 'acf-field-group-custom-table-settings',
            'label': 'left'
        });
    }
</script>
<?php
