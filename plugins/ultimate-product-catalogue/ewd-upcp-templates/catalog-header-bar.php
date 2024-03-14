<div class='ewd-upcp-catalog-header-bar'>
	
	<?php foreach ( $this->get_catalog_views() as $catalog_view ) { ?>

		<div class='ewd-upcp-toggle-icon ewd-upcp-toggle-icon-<?php echo esc_attr( $catalog_view ); ?> ewd-upcp-toggle-icon-<?php echo esc_attr( $this->get_option( 'color-scheme' ) ); ?>' data-view='<?php echo esc_attr( $catalog_view ); ?>'></div>

	<?php } ?>

</div>