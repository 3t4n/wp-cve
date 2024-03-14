<?php
defined( 'ABSPATH' ) || exit;
?>

<div class="notice notice-info is-dismissible" id="catf-empty-folder-notice">
	<span class="catf-logo-cover">
		<img src="<?php echo esc_url( CATF_PLUGIN_URL . 'assets/img/logo.svg' ); ?>" width="40" alt="logo">
	</span>
	<p class="catf-notice-wrap">
		<span class="label"><?php esc_html_e( 'Get Started', 'catfolders' ); ?></span>
		<?php esc_html_e( 'Start organizing with CatFolders now', 'catfolders' ); ?>.
		<a href="<?php echo esc_url( admin_url( '/upload.php' ) ); ?>">
			<strong><?php esc_html_e( 'Add your first folder', 'catfolders' ); ?></strong>
		</a>
	</p>
</div>

<style>
#catf-empty-folder-notice {
  border-left: 4px solid #ea60d5;
  position: relative;
  display: flex;
  align-items: center;
}
#catf-empty-folder-notice .catf-logo-cover {
  padding-right: 10px;
}
#catf-empty-folder-notice .catf-notice-wrap .label {
  display: block;
  font-size: 14px;
  font-weight: 700;
  margin-bottom: 5px;
}
#catf-empty-folder-notice .catf-notice-wrap {
  margin: 0.7em 0;
  flex: 1;
}
</style>


