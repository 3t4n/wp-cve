<?php
defined('ABSPATH') or die();
?>
<div class="wrap">
	<h1><?php _e( 'Legal Pages Generator - Notices', 'legal-page-generator' ); ?></h1>
	<div class="notice notice-warning is-dismissible">
        <p><?php _e( 'Please configure your company info before proceeding, make sure all the fields are filled in.', 'legal-page-generator' ); ?></p>
    </div>
	<p><a class="button button-primary" href="<?php echo admin_url( 'admin.php?page=legal-page-generator' ); ?>"><?php _e( 'Set up my company info', 'legal-page-generator' ); ?></a></p>
</div>