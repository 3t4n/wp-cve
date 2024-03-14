<div id="iup-footer" class="container mt-5">
	<div class="row">
		<div class="col-sm text-center text-muted">
			<strong><?php esc_html_e( "The Cloud by Infinite Uploads", 'infinite-uploads' ); ?></strong>
		</div>
	</div>
	<div class="row mt-3">
		<div class="col-sm text-center text-muted">
			<a href="<?php echo esc_url( Infinite_Uploads_Admin::get_instance()->api_url( '/support/?utm_source=iup_plugin&utm_medium=plugin&utm_campaign=iup_plugin&utm_content=footer&utm_term=support' ) ); ?>"
			   class="text-muted text-decoration-none"><?php esc_html_e( "Support", 'infinite-uploads' ); ?></a> |
			<a href="<?php echo esc_url( Infinite_Uploads_Admin::get_instance()->api_url( '/terms-of-service/?utm_source=iup_plugin&utm_medium=plugin&utm_campaign=iup_plugin&utm_content=footer&utm_term=terms' ) ); ?>"
			   class="text-muted text-decoration-none"><?php esc_html_e( "Terms of Service", 'infinite-uploads' ); ?></a> |
			<a href="<?php echo esc_url( Infinite_Uploads_Admin::get_instance()->api_url( '/privacy/?utm_source=iup_plugin&utm_medium=plugin&utm_campaign=iup_plugin&utm_content=footer&utm_term=privacy' ) ); ?>"
			   class="text-muted text-decoration-none"><?php esc_html_e( "Privacy Policy", 'infinite-uploads' ); ?></a>
		</div>
	</div>
	<div class="row mt-3">
		<div class="col-sm text-center text-muted">
			<a href="https://twitter.com/infiniteuploads" class="text-muted text-decoration-none" data-toggle="tooltip" title="<?php esc_attr_e( 'Twitter', 'infinite-uploads' ); ?>"><span class="dashicons dashicons-twitter"></span></a>
			<a href="https://www.facebook.com/infiniteuploads/" class="text-muted text-decoration-none" data-toggle="tooltip" title="<?php esc_attr_e( 'Facebook', 'infinite-uploads' ); ?>"><span class="dashicons dashicons-facebook-alt"></span></a>
		</div>
	</div>
</div>
