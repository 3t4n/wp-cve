<?php
/**
 * Privacy and Cookie Policy configuration - pp - page.
 *
 * @package  Iubenda
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// Including partial header.
require_once IUBENDA_PLUGIN_PATH . '/views/partials/header.php';
?>

<div class="main-box">
	<?php
	// Including partial site-info.
	require_once IUBENDA_PLUGIN_PATH . 'views/partials/site-info.php';

	// Including partial breadcrumb.
	require_once IUBENDA_PLUGIN_PATH . 'views/partials/breadcrumb.php';
	?>
	<form class="ajax-form-to-options">
		<input hidden name="iubenda_section_name" value="iubenda_privacy_policy_solution">
		<input hidden name="iubenda_section_key" value="pp">
		<input hidden name="action" value="save_pp_options">
		<?php wp_nonce_field( 'iub_save_pp_options_nonce', 'iub_pp_nonce' ); ?>
		<input hidden name="_redirect" value="<?php echo esc_url( add_query_arg( array( 'view' => 'products-page' ), iubenda()->base_url ) ); ?>">
		<div class="mx-4 mx-lg-5">
			<div class="py-4 py-lg-5 text-gray">
				<p class=""><?php esc_html_e( 'Configure your privacy and cookie policy on our website and paste here the embed code to integrate the button on your website.', 'iubenda' ); ?></p>
				<div class="d-flex align-items-center ">
					<div class="steps flex-shrink mr-2">1</div>
					<p class="text-bold"> <?php esc_html_e( 'Configure privacy and cookie policy by', 'iubenda' ); ?>
						<a target="_blank" href="<?php echo esc_url( iubenda()->settings->links['about_pp'] ); ?>" class="link-underline text-gray-lighter"> <?php esc_html_e( 'clicking here', 'iubenda' ); ?></a>
					</p>
				</div>
				<div class="d-flex align-items-center ">
					<div class="steps flex-shrink mr-2">2</div>
					<p class="text-bold"> <?php esc_html_e( 'Paste your privacy and cookie policy embed code here', 'iubenda' ); ?>
					</p>
				</div>
				<div class="ml-5 mt-3">
					<?php
					// Including partial languages-tabs.
					require_once IUBENDA_PLUGIN_PATH . '/views/partials/languages-tabs.php';
					?>
				</div>
			</div>
			<hr>
			<div id="integration-div" class="py-5">
				<h3 class="m-0 mb-4"><?php esc_html_e( 'Integration', 'iubenda' ); ?></h3>
				<?php
				// Including partial button-style.
				require_once IUBENDA_PLUGIN_PATH . 'views/partials/button-style.php';

				// Including partial button-position.
				require_once IUBENDA_PLUGIN_PATH . 'views/partials/button-position.php';
				?>
			</div>
		</div>
		<hr>
		<div class="p-4 d-flex justify-content-end">
			<input class="btn btn-gray-lighter btn-sm mr-2" type="button" value="<?php esc_html_e( 'Cancel', 'iubenda' ); ?>" onclick="window.location.href = '<?php echo esc_url( add_query_arg( array( 'view' => 'products-page' ), iubenda()->base_url ) ); ?>'"/>
			<button type="submit" class="btn btn-green-primary btn-sm" value="Save" name="save">
				<span class="button__text"><?php esc_html_e( 'Save settings', 'iubenda' ); ?></span>
			</button>
		</div>

		<div class="hidden">
			<?php
			// Including partial languages-tabs.
			require_once IUBENDA_PLUGIN_PATH . '/views/partials/languages-tabs.php';
			?>
		</div>
	</form>
</div>

<?php
// Including partial modal-ops-embed-invalid.
require_once IUBENDA_PLUGIN_PATH . '/views/partials/modals/modal-ops-embed-invalid.php';

// Including partial footer.
require_once IUBENDA_PLUGIN_PATH . 'views/partials/footer.php';
?>
