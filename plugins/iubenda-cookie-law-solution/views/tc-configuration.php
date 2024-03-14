<?php
/**
 * Terms and Conditions configuration - tc - page.
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
		<input hidden name="action" value="save_tc_options">
		<?php wp_nonce_field( 'iub_save_tc_options_nonce', 'iub_tc_nonce' ); ?>
		<input hidden name="_redirect" value="<?php echo esc_url( add_query_arg( array( 'view' => 'products-page' ), iubenda()->base_url ) ); ?>">
		<div class="mx-4 mx-lg-5">
			<div class="py-4 py-lg-5 text-gray">
				<p class=""><?php esc_html_e( 'Configure your terms and conditions on our website and paste here the embed code to integrate the button on your website.', 'iubenda' ); ?></p>
				<div class="d-flex align-items-center ">
					<div class="steps flex-shrink mr-2">1</div>
					<p class="text-bold"> <?php esc_html_e( 'Configure terms and conditions by', 'iubenda' ); ?>
						<a target="_blank" href="<?php echo esc_url( iubenda()->settings->links['about_tc'] ); ?>" class="link-underline text-gray-lighter"> <?php esc_html_e( 'clicking here', 'iubenda' ); ?></a>
					</p>
				</div>
				<div class="d-flex align-items-center ">
					<div class="steps flex-shrink mr-2">2</div>
					<p class="text-bold"> <?php esc_html_e( 'Paste your terms and conditions embed code here', 'iubenda' ); ?>
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
				<!-- Button Style -->
				<h4><?php esc_html_e( 'Button style', 'iubenda' ); ?></h4>
				<div class="scrollable gap-fixer">
					<div class="button-style mb-3 d-flex">
						<div class="m-1 mr-2">
							<label class="radio-btn-style radio-btn-style-light">
								<input type="radio" class="update-button-style" name="iubenda_terms_conditions_solution[button_style]" value="white" <?php checked( 'white', iub_array_get( iubenda()->options['tc'], 'button_style' ) ); ?>>
								<div>
									<div class="btn-fake"></div>
								</div>
								<p class="text-xs text-center"><?php esc_html_e( 'Light', 'iubenda' ); ?></p>
							</label>
						</div>
						<div class="m-1 mr-2">
							<label class="radio-btn-style radio-btn-style-dark">
								<input type="radio" class="update-button-style" name="iubenda_terms_conditions_solution[button_style]" value="black" <?php checked( 'black', iub_array_get( iubenda()->options['tc'], 'button_style' ) ); ?>>
								<div>
									<div class="btn-fake"></div>
								</div>
								<p class="text-xs text-center"><?php esc_html_e( 'Dark', 'iubenda' ); ?></p>
							</label>
						</div>
					</div>
				</div>

				<!-- Button Position -->
				<h4><?php esc_html_e( 'Button position', 'iubenda' ); ?></h4>
				<div class="mb-2 align-items-center flex-wrap">
					<label class="radio-regular mb-3">
						<input type="radio" name="iubenda_terms_conditions_solution[button_position]" value="automatic" class="mr-2 section-radio-control" data-section-group=".tc_button_position" data-section-name="#tc_button_position_automatic" <?php checked( 'automatic', iub_array_get( iubenda()->options['tc'], 'button_position' ) ); ?>>
						<span><?php esc_html_e( 'Add to the footer automatically', 'iubenda' ); ?></span>
					</label>
					<label class="mr-4 radio-regular text-xs">
						<input type="radio" name="iubenda_terms_conditions_solution[button_position]" value="manual" class="mr-2 section-radio-control" data-section-group=".tc_button_position" data-section-name="#tc_button_position_manually" <?php checked( 'manual', iub_array_get( iubenda()->options['tc'], 'button_position' ) ); ?>>
						<span><?php esc_html_e( 'Integrate manually', 'iubenda' ); ?></span>
					</label>
				</div>
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

					$_status = '';
					if ( 'automatic' !== (string) iub_array_get( iubenda()->options['tc'], 'button_position' ) ) {
						$_status = 'hidden';
					}
					?>
					<section id="tc_button_position_automatic" class="tc_button_position <?php echo esc_attr( $_status ); ?>">
						<div class="notice notice--warning mt-2 mb-4 p-3 d-flex align-items-center text-warning text-xs">
							<img class="mr-2" src="<?php echo esc_url( IUBENDA_PLUGIN_URL ); ?>/assets/images/warning-icon.svg">
							<p>
								<?php
								echo wp_kses_post( sprintf( $message, $url, $target ) );
								?>
							</p>
						</div>
					</section>
					<?php
				}

				$_status = '';
				if ( 'manual' !== (string) iub_array_get( iubenda()->options['tc'], 'button_position' ) ) {
					$_status = 'hidden';
				}
				?>
				<section id="tc_button_position_manually" class="tc_button_position <?php echo esc_attr( $_status ); ?>">
					<div class="subOptions">

						<p class="text-gray text-sm mb-4"><?php esc_html_e( 'Just copy and paste the embed code (WP shortcode or HTML) where you want the button to appear.', 'iubenda' ); ?></p>

						<div class="d-lg-flex">
							<div class="flex-fill mb-3 mr-0 mr-lg-5">
								<h4><?php esc_html_e( 'WP shortcode (recommended)', 'iubenda' ); ?></h4>
								<fieldset class="paste_embed_code">
									<input type="text" class="form-control text-sm m-0" value="[iub-tc-button]" readonly/>
								</fieldset>
								<p class="text-gray-lighter"><?php esc_html_e( 'A shortcode is a tiny bit of code that allows embedding interactive elements or creating complex page layouts with a minimal effort.', 'iubenda' ); ?></p>
							</div>
							<div class="flex-fill mb-0">
								<h4><?php esc_html_e( 'HTML', 'iubenda' ); ?></h4>
								<fieldset class="paste_embed_code tabs tabs--style2">
									<ul class="tabs__nav">
										<?php foreach ( iubenda()->languages as $k => $v ) : ?>
											<li class="tabs__nav__item <?php echo esc_attr( (string) iubenda()->lang_default === $k ? 'active' : '' ); ?>" data-target="tab-<?php echo esc_attr( $k ); ?>" id="iub-embed-code-readonly-<?php echo esc_attr( $k ); ?>" data-group="language-tabs">
												<?php echo esc_html( strtoupper( $k ) ); ?>
											</li>
										<?php endforeach; ?>
									</ul>
									<?php
									$languages = ( new Product_Helper() )->get_languages();
									foreach ( $languages as $lang_id => $v ) :
										$code = iub_array_get( iubenda()->options['tc'], "code_{$lang_id}" );
										$code = html_entity_decode( iubenda()->parse_code( $code ) );
										?>
										<div data-target="tab-<?php echo esc_attr( $lang_id ); ?>" class="tabs__target <?php echo esc_attr( (string) iubenda()->lang_default === (string) $lang_id || 'default' === (string) $lang_id ? 'active' : '' ); ?>" data-group="language-tabs">
											<textarea readonly class='form-control text-sm m-0 iub-tc-code' id="iub-tc-code-<?php echo esc_attr( $lang_id ); ?>" rows='4'><?php echo esc_html( $code ); ?></textarea>
										</div>
									<?php endforeach; ?>

								</fieldset>
							</div>
						</div>
					</div>
				</section>
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
// Including partial modal-ops-embed-invalid.
require_once IUBENDA_PLUGIN_PATH . '/views/partials/modals/modal-ops-embed-invalid.php';
// Including partial footer.
require_once IUBENDA_PLUGIN_PATH . '/views/partials/footer.php';
?>
