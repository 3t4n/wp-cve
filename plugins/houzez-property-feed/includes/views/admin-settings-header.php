<div class="hpf-admin-settings-header">

	<?php if ( isset($_GET['page']) && sanitize_text_field($_GET['page']) == 'houzez-property-feed-export' ) { ?>
	<div class="add-import-export-button">
		<a href="<?php echo admin_url('admin.php?page=houzez-property-feed-export&action=addexport'); ?>" class="button button-primary button-hero"><span class="dashicons dashicons-plus-alt2"></span> <?php echo __( 'Create New Export', 'houzezpropertyfeed' ); ?></a>
	</div>
	<?php }else{ ?>
	<div class="add-import-export-button">
		<a href="<?php echo admin_url('admin.php?page=houzez-property-feed-import&action=addimport'); ?>" class="button button-primary button-hero"><span class="dashicons dashicons-plus-alt2"></span> <?php echo __( 'Create New Import', 'houzezpropertyfeed' ); ?></a>
	</div>
	<?php } ?>

	<div class="logo">
		<a href="<?php echo admin_url('admin.php?page=' . ( isset($_GET['page']) ? sanitize_text_field($_GET['page']) : 'houzez-property-feed-import' )); ?>"><img src="<?php echo untrailingslashit( plugins_url( '/', HOUZEZ_PROPERTY_FEED_PLUGIN_FILE ) ); ?>/assets/images/houzez-property-feed-logo.png" alt=""></a>
	</div>

	<div class="buttons">
		<?php if ( !class_exists('Houzez_Property_Feed_Pro') ) { ?><a href="https://houzezpropertyfeed.com/#pricing" class="button button-primary" target="_blank"><?php echo __( 'Upgrade To PRO', 'houzezpropertyfeed' ); ?></a> &nbsp;<?php } ?>
		<a href="https://houzezpropertyfeed.com/documentation/" class="button" target="_blank"><?php echo __( 'Documentation', 'houzezpropertyfeed' ); ?></a>
	</div>

	<div class="clear"></div>

</div>
