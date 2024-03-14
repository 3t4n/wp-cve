<?php include(dirname(__FILE__).'/unsupported-message.php');

?>
<h1><?php echo __("Configure WP Custom Fields Search","wp_custom_fields_search")?></h1>
<div id='wpcfs-presets-page'>
</div>
<script>
    jQuery('#wpcfs-presets-page').wp_custom_fields_search_editor({
        'mode': 'presets',
        'root_template': 'presets.html',
        'form_config': <?php echo json_encode($presets)?>,
        'building_blocks': <?php echo json_encode(WPCustomFieldsSearchPlugin::get_javascript_editor_config())?>,
        'settings_pages': <?php echo json_encode(apply_filters('wpcfs_settings_pages',array())) ?>,

        'root': "<?php echo plugin_dir_url(dirname(__FILE__)) ?>",
        'save_callback': "wpcfs_save_preset",
        'delete_callback': "wpcfs_delete_preset",
        'save_nonce': <?php echo json_encode(wp_create_nonce("wpcfs_save_preset"))?>,
        'delete_nonce': <?php echo json_encode(wp_create_nonce("wpcfs_delete_preset"))?>,

        'export_callback': "wpcfs_export_settings",
        'export_nonce': <?php echo json_encode(wp_create_nonce("wpcfs_export_settings"))?>
    });
</script>
