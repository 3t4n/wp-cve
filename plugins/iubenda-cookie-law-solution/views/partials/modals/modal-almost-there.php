<?php
/**
 * Almost there - global - partial modal page.
 *
 * @package  Iubenda
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="modalAlmostThere">

	<div class="text-center">
		<img src="<?php echo esc_url( IUBENDA_PLUGIN_URL ); ?>/assets/images/modals/modal_almost_there.svg" alt=""/>
		<h2 class="text-lg"><?php esc_html_e( 'Nice! We are almost there.', 'iubenda' ); ?></h2>
		<p class="text-regular pb-3"><?php esc_html_e( 'Since you already activated some products for this website, we just ask you to copy and paste the embedding code of the product you already have to syncronize your iubenda acount with WP plugin.', 'iubenda' ); ?></p>
	</div>

	<form class="ajax-form">
		<input hidden name="action" value="synchronize_products">
		<?php wp_nonce_field( 'iub_synchronize_products', 'iub_synchronize_products_nonce' ); ?>
		<input hidden name="_redirect" value="<?php echo esc_url( add_query_arg( array( 'view' => 'products-page' ), iubenda()->base_url ) ); ?>">

		<h4 class="mb-3"><?php esc_html_e( 'Select products you have already activated', 'iubenda' ); ?></h4>
		<div class="radio-cards pb-3 mb-4">

			<?php foreach ( iubenda()->settings->services as $key => $service ) : ?>
				<label for="radio-card-<?php echo esc_attr( $key ); ?>" class="radio-card">
					<input
							type="checkbox"
							name="iubenda_<?php echo esc_attr( $service['name'] ); ?>_solution_status"
							class="select-product-checkbox section-checkbox-control 
							<?php
							if ( 'cs' === (string) $key ) :
								echo esc_attr( 'required-control' ); endif
							?>
							"
							data-section-name="#section-<?php echo esc_attr( $key ); ?>"
							value="true" id="radio-card-<?php echo esc_attr( $key ); ?>"
				<?php if ( 'cs' === (string) $key ) : ?>
							data-required-control="#submit-btn"
<?php endif ?>
					<?php echo esc_attr( 'true' === (string) $service['status'] ? 'checked' : '' ); ?>/>
					<span class="check-icon"></span>

					<div>
						<img src="<?php echo esc_url( IUBENDA_PLUGIN_URL ); ?>/assets/images/checkboxes/<?php echo esc_attr( $key ); ?>_icon.svg" alt="<?php echo esc_attr( $service['label'] ); ?>"/>
						<span>
							<?php echo esc_html( $service['label'] ); ?>
							<?php if ( 'cs' === (string) $key ) : ?>
								<span style="color: rgb(207, 116, 99);"><?php esc_html_e( '(required)', 'iubenda' ); ?></span>
							<?php endif; ?>
						</span>
					</div>
				</label>

			<?php endforeach; ?>

		</div>

		<?php foreach ( iubenda()->settings->services as $key => $service ) : ?>
			<?php
			if ( 'cons' === (string) $key ) :
				$_status = '';
				if ( 'true' !== (string) $service['status'] ) {
					$_status = 'hidden';
				}
				?>
				<section id="section-<?php echo esc_attr( $key ); ?>" class="<?php echo esc_attr( $_status ); ?>">
				<h4 class="mb-3"><?php esc_html_e( 'Consent Database API key', 'iubenda' ); ?></h4>
					<fieldset class="paste_embed_code">
						<input name="public_api_key" class="form field-input" type="text" value="<?php echo esc_attr( iubenda()->options['cons']['public_api_key'] ); ?>" placeholder="<?php esc_html_e( 'Paste your API key here', 'iubenda' ); ?>">
					</fieldset>
					<div class="text-right mt-2">
						<a target="_blank" href="<?php echo esc_url( iubenda()->settings->links['how_generate_cons'] ); ?>" class="link link-helper"><span class="tooltip-icon mr-2">?</span><?php esc_html_e( 'Where can I find this code?', 'iubenda' ); ?></a>
					</div>
				</section>
				<?php
			else :
				$_status = '';
				if ( 'true' !== (string) $service['status'] ) {
					$_status = 'hidden';
				}
				?>
				<section id="section-<?php echo esc_attr( $key ); ?>" class="<?php echo esc_attr( $_status ); ?>">
					<h4 class="mb-3"><?php echo esc_html( $service['label'] ); ?></h4>
					<?php
					// Check if we support current theme to attach legal.
					if ( ! iubenda()->check_if_we_support_current_theme_to_attach_legal() ) {
						$url    = 'javascript:void(0)';
						$target = '_self';
						/* translators: 1: Admin url or javascript:void(0), 2: Target type */
						$message = __( 'We were not able to add a "Legal" widget/block to the footer as your theme is not compatible, you can position the "Legal" widget/block manually from <a href="%1$s" target="%2$s">here</a>.', 'iubenda' );

						if ( iubenda()->widget->check_current_theme_supports_widget() ) {
							$url    = esc_url( admin_url( 'widgets.php' ) );
							$target = '_blank';
						} elseif ( iubenda()->block->check_current_theme_supports_blocks() ) {
							$url    = esc_url( admin_url( 'site-editor.php' ) );
							$target = '_blank';
						} elseif ( iubenda()->is_elementor_installed_and_activated() ) {
							$message = __( 'It seems you are using elementor plugin, please use elementor theme builder to add the iubenda legal widget manually to the footer.', 'iubenda' );
						}
						?>
						<div class="notice notice--warning mt-2 mb-4 p-3 d-flex align-items-center text-warning text-xs">
							<img class="mr-2" style="width: 3rem !important" src="<?php echo esc_url( IUBENDA_PLUGIN_URL ); ?>/assets/images/warning-icon.svg">
							<p>
								<?php
								echo wp_kses_post( sprintf( $message, $url, $target ) );
								?>
							</p>
						</div>
						<?php
					}

					// Including partial languages-tabs.
					require IUBENDA_PLUGIN_PATH . '/views/partials/languages-tabs.php';

					// Add CS default options.
					if ( 'cs' === (string) $key ) :
						?>
						<input type="hidden" class="blocking-method native-blocking-method" name="iubenda_cookie_law_solution[parse]" value="1">
						<input type="hidden" name="iubenda_cookie_law_solution[parser_engine]" value="<?php echo can_use_dom_document_class() ? esc_attr( 'new' ) : 'default'; ?>">
					<?php endif; ?>
				</section>
			<?php endif; ?>

		<?php endforeach; ?>

		<div class="text-center">
			<button type="submit" class="btn btn-green-primary btn-sm mt-5 hidden" id="submit-btn">
				<span class="button__text"><?php esc_html_e( 'Synchronize products', 'iubenda' ); ?></span>
			</button>
		</div>

	</form>

</div>
