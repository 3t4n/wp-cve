<?php
/**
 * Integrate setup - global - page.
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
<form class="ajax-form">
	<input hidden name="action" value="integrate_setup">
	<?php wp_nonce_field( 'iub_integrate_setup', 'iub_nonce' ); ?>
	<input hidden name="_redirect" value="<?php echo esc_url( add_query_arg( array( 'view' => 'products-page' ), iubenda()->base_url ) ); ?>">
	<input hidden name="iubenda_cookie_law_solution[configuration_type]" value="simplified">
	<div class="main-box">
		<?php
		// Including partial site-info.
		require_once IUBENDA_PLUGIN_PATH . 'views/partials/site-info.php';
		?>

		<div class="p-3 m-3">
			<?php if ( iubenda()->notice->has_inside_plugin_notice() ) : ?>
				<div class="p-3 m-3">
					<?php iubenda()->notice->show_notice_inside_plugin(); ?>
				</div>
			<?php endif; ?>

			<div class="radio-toggle">
				<div class="switch">
					<input type="checkbox" name="cookie_law" id="toggleAddCookieBanner" class="section-checkbox-control" data-section-name="#section-add-cookie-banner" checked/>
					<label for="toggleAddCookieBanner"></label>
				</div>
				<span><?php esc_html_e( 'Add a cookie banner', 'iubenda' ); ?></span>
			</div>
			<section id="section-add-cookie-banner">
				<?php
				// Including partial cs-simplified-configuration.
				require_once IUBENDA_PLUGIN_PATH . '/views/partials/cs-simplified-configuration.php';
				?>

				<div class="my-5">
					<?php
					$site_id                            = iub_array_get( iubenda()->options['global_options'], 'site_id' );
					$predefined_auto_block_section_data = array(
						'frontend-auto-blocking-checkbox-status' => iubenda()->iub_auto_blocking->is_autoblocking_feature_available( $site_id ),
					);

					// Including partial auto-block-section.
					require_once IUBENDA_PLUGIN_PATH . '/views/partials/auto-block-section.php';
					?>
				</div>
				<div class="my-5">
					<label class="checkbox-regular">
						<input type="checkbox" class="mr-2 section-checkbox-control" name="iubenda_cookie_law_solution[amp_support]" value="1" checked data-section-name="#amp_support"/>
						<span><?php esc_html_e( 'Enable Google AMP support', 'iubenda' ); ?> <a target="_blank" href="<?php echo esc_url( iubenda()->settings->links['enable_amp_support'] ); ?>" class="ml-1 tooltip-icon">?</a></span>
					</label>
					<section id="amp_support" class="subOptions my-2">
						<h4><?php esc_html_e( 'Select the iubenda AMP configuration file location.', 'iubenda' ); ?></h4>
						<div class="mb-2 d-flex flex-wrap align-items-center">
							<label class="radio-regular mr-4 mb-3">
								<input type="radio" name="iubenda_cookie_law_solution[amp_source]" value="local" class="mr-2 section-radio-control" data-section-name="#auto_generated_conf_file" data-section-group=".amp_configuration_file" checked>
								<span><?php esc_html_e( 'Auto-generated configuration file', 'iubenda' ); ?></span>
							</label>
							<label class="mr-4 mb-3 radio-regular text-xs">
								<input type="radio" name="iubenda_cookie_law_solution[amp_source]" value="remote" class="mr-2 section-radio-control" data-section-name="#custom_conf_file" data-section-group=".amp_configuration_file">
								<span><?php esc_html_e( 'Custom configuration file', 'iubenda' ); ?></span>
							</label>
						</div>

						<section id="auto_generated_conf_file" class="text-xs text-gray amp_configuration_file">
							<div class="border-1 border-gray rounded mt-2 py-2 px-3 d-flex flex-wrap align-items-center">
								<?php
								// Including partial amp-files-section.
								require_once IUBENDA_PLUGIN_PATH . '/views/partials/amp-template-links.php';
								?>
							</div>
							<div class="notice notice--general mt-2 p-3 d-flex align-items-center text-xs">
								<p><?php esc_html_e( 'Seeing the AMP cookie notice when testing from Google but not when visiting your AMP pages directly?', 'iubenda' ); ?> <a target="_blank" href="<?php echo esc_url( iubenda()->settings->links['amp_support'] ); ?>" class="link-underline"><?php esc_html_e( 'Learn how to fix it', 'iubenda' ); ?></a></p>
							</div>
						</section>
						<section id="custom_conf_file" class="text-xs text-gray amp_configuration_file hidden">
							<table class="table">
								<tbody>
								<?php
								$languages = ( iubenda()->multilang && ! empty( iubenda()->languages ) ) ? iubenda()->languages : array( 'default' => __( 'Default language', 'iubenda' ) );
								foreach ( $languages as $lang_id => $lang_name ) {
									?>
									<tr>
										<td><label class="text-bold" for="iub_amp_template-<?php echo esc_html( $lang_id ); ?>"><?php echo esc_html( $lang_name ); ?></label></td>
										<td><input id="iub_amp_template-<?php echo esc_html( $lang_id ); ?>" type="text" class="regular-text" name="iubenda_cookie_law_solution[amp_template][<?php echo esc_html( $lang_id ); ?>]" value="<?php echo esc_html( iub_array_get( iubenda()->options['cs'], "amp_template.{$lang_id}" ) ); ?>"/></td>
									</tr>
								<?php } ?>
								</tbody>
							</table>
						</section>
					</section>
				</div>
				<?php
				if ( ! can_use_dom_document_class() ) {
					$default_parser = 'default';
				} else {
					$default_parser = iubenda()->defaults['cs']['parser_engine'];
				}
				?>
				<div class="my-5">
					<label class="checkbox-regular">
						<input type="checkbox" name="iubenda_cookie_law_solution[parse]" value="1" class="mr-2 section-checkbox-control blocking-method native-blocking-method" data-section-name="#iub_parser_engine_container" checked>
						<span><?php esc_html_e( 'Native Blocking', 'iubenda' ); ?> <a target="_blank" href="<?php echo esc_url( iubenda()->settings->links['automatic_block_scripts'] ); ?>" class="ml-1 tooltip-icon">?</a></span>
					</label>
					<div id="both-blocking-methods-disabled-warning-message" class="mxx-4 mb-4 notice notice--warning mt-2 p-3 align-items-center text-warning text-xs <?php echo iubenda()->options['cs']['parse'] ? 'd-flex' : ''; ?>">
						<img class="mr-2" src="<?php echo esc_url( IUBENDA_PLUGIN_URL ); ?>/assets/images/warning-icon.svg">
						<p>
							<?php esc_html_e( 'Most legislation explicitly require prior consent in order to process user’s data. By disabling these blocking options you may be in breach of such requirements', 'iubenda' ); ?>
						</p>
					</div>
					<section id="iub_parser_engine_container" class="subOptions">
						<h4><?php esc_html_e( 'Select Parsing Engine', 'iubenda' ); ?></h4>
						<div class="mb-3 d-flex flex-wrap align-items-center">
							<label class="radio-regular mr-4 mb-3">
								<input type="radio" name="iubenda_cookie_law_solution[parser_engine]" value="new" class="mr-2 section-radio-control" <?php checked( 'new', $default_parser ); ?>>
								<span><?php esc_html_e( 'Primary', 'iubenda' ); ?></span>
							</label>
							<label class="mr-4 mb-3 radio-regular text-xs">
								<input type="radio" name="iubenda_cookie_law_solution[parser_engine]" value="default" class="mr-2 section-radio-control" <?php checked( 'default', $default_parser ); ?>>
								<span><?php esc_html_e( 'Secondary', 'iubenda' ); ?></span>
							</label>
							<?php if ( ! can_use_dom_document_class() ) : ?>
								<div class="notice notice--warning mt-2 p-3 d-flex align-items-center text-warning text-xs">
									<img class="mr-2" src="<?php echo esc_url( IUBENDA_PLUGIN_URL ); ?>/assets/images/warning-icon.svg">
									<p><?php echo wp_kses_post( ( __( "You won't be able to use the Primary engine since you don't have the <span class='text-bold'>PHP XML</span> extension", 'iubenda' ) ) ); ?></p>
								</div>
							<?php endif; ?>
						</div>
						<div class="mb-2 d-flex flex-wrap align-items-center">
							<label class="checkbox-regular">
								<input type="checkbox" name="iubenda_cookie_law_solution[skip_parsing]" value="1" class="mr-2 section-checkbox-control" data-section-name="#section-block-script">
								<div class="px-0 py-1">
									<span class="p-0"><?php esc_html_e( 'Leave scripts untouched on the page if the user has already given consent', 'iubenda' ); ?></span>
									<div class="notice notice--info mt-2 mb-3 p-3 d-flex align-items-center text-xs">
										<p>
											<?php echo wp_kses_post( ( __( "Enable this option to improve performance <strong>only</strong> if your site does <strong>not</strong> use a cache system or a cache plugin and if you're <strong>not</strong> collecting per-category consent. If you're in doubt, keep this setting disabled", 'iubenda' ) ) ); ?>
										</p>
									</div>
								</div>
							</label>
						</div>
						<div class="mb-2 d-flex flex-wrap align-items-center">
							<label class="checkbox-regular">
								<input type="checkbox" name="iubenda_cookie_law_solution[block_gtm]" value="1" class="mr-2 section-checkbox-control" <?php checked( true, (bool) iubenda()->options['cs']['block_gtm'] ); ?>>
								<div class="px-0 py-1">
									<span class="p-0"><?php esc_html_e( 'Block Google Tag Manager', 'iubenda' ); ?></span>
									<div class="notice notice--info mt-2 mb-3 p-3 d-flex align-items-center text-xs">
										<p>
										<?php
											/* translators: %s: Google tag manager blocking documentation URL. */
											echo wp_kses_post( sprintf( __( 'Enable this option to prevent Google Tag Manager from running. If, on the contrary, you would like to learn how to use Google Tag Manager to simplify the blocking of cookies, <a class="link-underline" target="_blank" href="%s">read our dedicated guide</a>', 'iubenda' ), esc_url( iubenda()->settings->links['google_tag_manager_blocking'] ) ) );
										?>
										</p>
									</div>
								</div>
							</label>
						</div>
					</section>
				</div>
			</section>

			<div class="radio-toggle">
				<div class="switch">
					<input type="checkbox" name="privacy_policy" id="toggleAddPrivacyButton" class="section-checkbox-control" data-section-name="#section-privacy-policy-button" checked/>

					<label for="toggleAddPrivacyButton"></label>
				</div>
				<span><?php esc_html_e( 'Add the privacy policy button', 'iubenda' ); ?></span>
			</div>
			<section id="section-privacy-policy-button">
				<?php
				// Including partial button-style.
				require_once IUBENDA_PLUGIN_PATH . '/views/partials/button-style.php';
				// Including partial button-position.
				require_once IUBENDA_PLUGIN_PATH . '/views/partials/button-position.php';
				?>
			</section>

		</div>

		<?php
		// Including partial integrate-footer.
		require_once IUBENDA_PLUGIN_PATH . '/views/partials/integrate-footer.php';
		?>

	</div>
</form>
<?php
// Including partial footer.
require_once IUBENDA_PLUGIN_PATH . 'views/partials/footer.php';
?>
