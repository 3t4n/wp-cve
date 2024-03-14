<?php
/**
 * Display all project list template.
 *
 * @package MPG
 */

?>
<div class="wrap">
	<h2 style="display:inline-block; margin-right: 5px;"><?php esc_html_e( 'Projects', 'mpg' ); ?></h2>
	<?php
		$new_project_url = add_query_arg(
			'page',
			'mpg-dataset-library',
			admin_url( 'admin.php' )
		);
		?>
	<a href="<?php echo esc_url_raw( $new_project_url ); ?>" class="page-title-action"><?php esc_html_e( 'Add New Project', 'mpg' ); ?></a>
	<hr class="wp-header-end">
	<form method="get">
		<?php $projects_list->prepare_items(); ?>
		<p class="search-box">
			<input type="hidden" name="page" value="<?php esc_attr_e( 'mpg-project-builder', 'mpg' ); ?>">
			<label class="screen-reader-text" for="search_email-search-input"><?php esc_html_e( 'Search:', 'mpg' ); ?></label>
			<input type="search" id="search_email-search-input" name="s" value="<?php echo isset( $_GET['s'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_GET['s'] ) ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification ?>" placeholder="<?php esc_attr_e( 'Search by project name', 'mpg' ); ?>">
			<input type="hidden" name="_mpg_nonce" value="<?php echo esc_attr( wp_create_nonce( MPG_BASENAME ) ); ?>">
			<input type="submit" id="search-submit" class="button" value="<?php esc_attr_e( 'Search', 'mpg' ); ?>">
		</p>
		<?php $projects_list->display(); ?>
	</form>
</div>
