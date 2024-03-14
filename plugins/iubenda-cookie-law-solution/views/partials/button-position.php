<?php
/**
 * Button position - pp - partial page.
 *
 * @package  Iubenda
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<h4><?php esc_html_e( 'Button position', 'iubenda' ); ?></h4>
<div class="mb-2 flex-wrap">
	<label class="radio-regular mb-3">
		<input type="radio" name="iubenda_privacy_policy_solution[button_position]" value="automatic" class="mr-2 section-radio-control" data-section-group=".pp_button_position" data-section-name="#pp_button_position_automatically" <?php checked( 'automatic', iub_array_get( iubenda()->options['pp'], 'button_position' ) ); ?>>
		<span><?php esc_html_e( 'Add to the footer automatically', 'iubenda' ); ?></span>
	</label>
	<label class="mr-4 radio-regular text-xs">
		<input type="radio" name="iubenda_privacy_policy_solution[button_position]" value="manual" class="mr-2 section-radio-control" data-section-group=".pp_button_position" data-section-name="#pp_button_position_manually" <?php checked( 'manual', iub_array_get( iubenda()->options['pp'], 'button_position' ) ); ?>>

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
	if ( (string) iub_array_get( iubenda()->options['pp'], 'button_position' ) !== 'automatic' ) {
		$_status = 'hidden';
	}
	?>
	<section id="pp_button_position_automatically" class="pp_button_position <?php echo esc_html( $_status ); ?>">
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
if ( iub_array_get( iubenda()->options['pp'], 'button_position' ) !== 'manual' ) {
	$_status = 'hidden';
}
?>
<section id="pp_button_position_manually" class="pp_button_position <?php echo esc_html( $_status ); ?>">
	<div class="subOptions">

		<p class="text-gray text-sm mb-4"><?php esc_html_e( 'Just copy and paste the embed code (WP shortcode or HTML) where you want the button to appear.', 'iubenda' ); ?></p>

		<div class="d-lg-flex">
			<div class="flex-fill mb-3 mr-0 mr-lg-5">
				<h4><?php esc_html_e( 'WP shortcode (recommended)', 'iubenda' ); ?></h4>
				<fieldset class="paste_embed_code">
					<input type="text" class="form-control text-sm m-0" value="[iub-pp-button]" readonly/>
				</fieldset>
				<p class="text-gray-lighter"><?php esc_html_e( 'A shortcode is a tiny bit of code that allows embedding interactive elements or creating complex page layouts with a minimal effort.', 'iubenda' ); ?></p>
			</div>

			<div class="flex-fill mb-0">
				<h4><?php esc_html_e( 'HTML', 'iubenda' ); ?></h4>
				<fieldset class="paste_embed_code tabs tabs--style2">
					<ul class="tabs__nav">
						<?php
						foreach ( iubenda()->languages as $k => $v ) :
							$_status = '';
							if ( (string) iubenda()->lang_default === (string) $k ) {
								$_status = 'active';
							}
							?>
							<li class="tabs__nav__item <?php echo esc_html( $_status ); ?>" data-target="tab-<?php echo esc_html( $k ); ?>" data-group="language-tabs" id="iub-embed-code-readonly-<?php echo esc_attr( $k ); ?>">
								<?php echo esc_html( strtoupper( $k ) ); ?>
							</li>
						<?php endforeach; ?>
					</ul>
					<?php
					$privacy_policy_generator = new Privacy_Policy_Generator();
					$global_options           = get_option( 'iubenda_global_options' );

					$languages = ( new Product_Helper() )->get_languages();
					foreach ( $languages as $lang_id => $v ) :
						$code = iub_array_get( iubenda()->options['pp'], "code_{$lang_id}" );

						// if there is no embed code saved generate embed code.
						if ( ! $code ) {
							$public_ids = iub_array_get( $global_options, 'public_ids' );
							$public_id  = iub_array_get( $public_ids, $lang_id );

							$code = $privacy_policy_generator->handle( $lang_id, $public_id, 'white' );
						}
						$code = html_entity_decode( iubenda()->parse_code( $code ) );

						$_status = '';
						if ( (string) iubenda()->lang_default === (string) $lang_id || 'default' === (string) $lang_id ) {
							$_status = 'active';
						}
						?>
						<div data-target="tab-<?php echo esc_attr( $lang_id ); ?>" class="tabs__target <?php echo esc_html( $_status ); ?>" data-group="language-tabs">
							<textarea readonly class='form-control text-sm m-0 iub-pp-code' id="iub-pp-code-<?php echo esc_attr( $lang_id ); ?>" rows='4'><?php echo esc_html( $code ); ?></textarea>
						</div>
					<?php endforeach; ?>

				</fieldset>
			</div>
		</div>

	</div>
</section>
