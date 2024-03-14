<?php
/**
 * Cookie law solution configuration - cs - page.
 *
 * @package  Iubenda
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// Including partial header.
require_once IUBENDA_PLUGIN_PATH . 'views/partials/header.php';
?>
<div class="main-box">
	<?php
	// Including partial site-info.
	require_once IUBENDA_PLUGIN_PATH . 'views/partials/site-info.php';

	// Including partial breadcrumb.
	require_once IUBENDA_PLUGIN_PATH . 'views/partials/breadcrumb.php';
	?>
	<form class="ajax-form-to-options">
		<input hidden name="iubenda_section_name" value="iubenda_cookie_law_solution">
		<input hidden name="iubenda_section_key" value="cs">
		<input hidden name="action" value="save_cs_options">
		<?php wp_nonce_field( 'iub_save_cs_options_nonce', 'iub_cs_nonce' ); ?>
		<input hidden name="_redirect" value="<?php echo esc_url( add_query_arg( array( 'view' => 'products-page' ), iubenda()->base_url ) ); ?>">
		<div class="p-4 p-lg-5">
			<?php
			// Including partial cs-general-settings.
			require_once IUBENDA_PLUGIN_PATH . 'views/partials/cs-general-settings.php';
			?>
		</div>
		<hr>
		<div class="p-4 d-flex justify-content-end">
			<input class="btn btn-gray-lighter btn-sm mr-2" type="button" value="<?php esc_attr_e( 'Cancel', 'iubenda' ); ?>" onclick="window.location.href = '<?php echo esc_url( add_query_arg( array( 'view' => 'products-page' ), iubenda()->base_url ) ); ?>'"/>
			<button type="submit" class="btn btn-green-primary btn-sm" value="Save" name="save">
				<span class="button__text"><?php esc_html_e( 'Save settings', 'iubenda' ); ?></span>
			</button>
		</div>
	</form>
</div>
<?php
// Including partial modal-ops-embed-invalid.
require_once IUBENDA_PLUGIN_PATH . '/views/partials/modals/modal-ops-embed-invalid.php';

// Including partial footer.
require_once IUBENDA_PLUGIN_PATH . 'views/partials/footer.php';
?>
