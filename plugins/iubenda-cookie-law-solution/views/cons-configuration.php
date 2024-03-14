<?php
/**
 * Consent Database configuration - cons - page.
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
		<input hidden name="action" value="save_cons_options">
		<?php wp_nonce_field( 'iub_save_cons_options_nonce', 'iub_cons_nonce' ); ?>
		<input hidden name="_redirect" value="<?php echo esc_url( add_query_arg( array( 'view' => 'products-page' ), iubenda()->base_url ) ); ?>">
		<div class="p-4 p-lg-5 text-gray">

		<p><?php echo wp_kses_post( __( 'Activate <strong>Consent Database</strong> on our website in your iubenda dashboard and paste the <strong>API key</strong> here to integrate it in your website.', 'iubenda' ) ); ?></p>
		<div class="d-flex align-items-center">
			<div class="steps flex-shrink mr-3">1</div>
			<p class="text-bold"> <?php esc_html_e( 'Activate & Configure Consent Database Solution by', 'iubenda' ); ?>
				<a target="_blank" href="<?php echo esc_url( iubenda()->settings->links['flow_page'] ); ?>" class="link-underline text-gray-lighter"> <?php esc_html_e( 'clicking here', 'iubenda' ); ?></a>
			</p>
		</div>
		<div class="d-flex align-items-center">
			<div class="steps flex-shrink mr-3">2</div>
			<p class="text-bold"> <?php esc_html_e( 'Paste your public API key here', 'iubenda' ); ?>
			</p>
		</div>

		<div class="subOptions">
			<div class="paste-api-form" tabindex="0">
				<input class="paste-api-input" id="public_api_key" name="iubenda_consent_solution[public_api_key]" type="text" placeholder="<?php esc_html_e( 'Your iubenda Javascript library public API key', 'iubenda' ); ?>" value="<?php echo esc_html( iub_array_get( iubenda()->options['cons'], 'public_api_key' ) ? iub_array_get( iubenda()->options['cons'], 'public_api_key' ) : '' ); ?>" required>
				<button type="submit" id="public_api_button" class="btn btn-xs btn-green-secondary">
					<span class="button__text"><?php esc_html_e( 'Confirm API', 'iubenda' ); ?></span>
				</button>
			</div>
		</div>

		<div class="text-right mt-2">
			<a target="_blank" href="<?php echo esc_url( iubenda()->settings->links['how_generate_cons'] ); ?>" class="link link-helper"><span class="tooltip-icon mr-2">?</span><?php esc_html_e( 'Where can I find this code?', 'iubenda' ); ?></a>
		</div>
			<?php
			$_status = '';
			if ( empty( (string) iub_array_get( iubenda()->options['cons'], 'public_api_key' ) ) ) {
				$_status = 'hidden';
			}

			?>
		<div id="public-api-key-div" class="<?php echo esc_html( $_status ); ?>">
			<div class="d-flex align-items-center">
				<div class="steps flex-shrink mr-3">3</div>
				<p class="text-bold">
					<?php esc_html_e( 'Add forms', 'iubenda' ); ?>
				</p>
			</div>
			<div class="ml-3 pl-4 mb-5">
				<div id="auto-detect-parent-div">
					<section id="auto-detect-forms">
						<?php
						// Including partial auto-detect-forms.
						require_once IUBENDA_PLUGIN_PATH . 'views/partials/auto-detect-forms.php';
						?>
					</section>
				</div>
				<a tabindex="-1" href="
				<?php
				echo esc_url(
					add_query_arg(
						array(
							'view'   => 'cons-configuration',
							'action' => 'autodetect',
						),
						iubenda()->base_url
					)
				);
				?>
				" class="btn btn-xs btn-gray-outline mt-2 auto-detect-forms"><?php esc_html_e( 'Auto-detect forms', 'iubenda' ); ?></a>
			</div>
		</div>

		</div>
		<hr>
		<div class="p-4 d-flex justify-content-end">
			<input class="btn btn-gray-lighter btn-sm mr-2" type="button" value="<?php esc_html_e( 'Cancel', 'iubenda' ); ?>" onclick="window.location.href = '<?php echo esc_url( add_query_arg( array( 'view' => 'products-page' ), iubenda()->base_url ) ); ?>'"/>
			<button type="submit" class="btn btn-green-primary btn-sm" value="Save" name="save">
				<span class="button__text"><?php esc_html_e( 'Save settings', 'iubenda' ); ?></span>
			</button>
		</div>
	</form>
</div>

<?php
// Including partial footer.
require_once IUBENDA_PLUGIN_PATH . 'views/partials/footer.php';
?>
