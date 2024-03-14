<?php

/**
 * Terms and Conditions.
 *
 * @link    https://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */

$tos_settings = get_option( 'acadp_terms_of_agreement' );

if ( empty( $tos_settings['show_agree_to_terms'] ) || empty( $tos_settings['agree_text'] ) ) {
	return false;
}

$optin_text  = trim( $tos_settings['agree_text'] );					
$optin_label = ! empty( $tos_settings['agree_label'] ) ? trim( $tos_settings['agree_label'] ) : __( 'I have read and agree to the Terms and Conditions', 'advanced-classifieds-and-directory-pro' );

$is_url = filter_var( $optin_text, FILTER_VALIDATE_URL ) ? true : false;
?>
<div id="acadp-form-group-terms_of_agreement" class="acadp-form-group">
	<label class="acadp-inline-flex acadp-gap-1.5 acadp-items-center">
		<input type="checkbox" name="terms_of_agreement" class="acadp-form-control acadp-form-checkbox acadp-form-validate" required />
		<?php
		if ( $is_url ) {
			printf(
				'<a href="%s" class="acadp-underline" target="_blank">%s</a>',
				esc_url( $optin_text ),
				wp_kses_post( $optin_label )
			);
		} else {
			printf(
				'<a href="javascript:void(0);" class="acadp-button-modal acadp-underline" data-target="#acadp-modal-terms_of_agreement">%s</a>',
				wp_kses_post( $optin_label )
			);
		}
		?>
		<span class="acadp-form-required" aria-hidden="true">*</span>
	</label>
</div>

<?php if ( ! $is_url ) : ?>
	<!-- Modal -->
	<div id="acadp-modal-terms_of_agreement" class="acadp-modal">
		<!-- Dialog -->
		<div class="acadp-modal-dialog">
			<div class="acadp-modal-content">
				<!-- Header -->
				<div class="acadp-modal-header">
					<div class="acadp-text-xl">
						<?php esc_html_e( 'Terms and Conditions', 'advanced-classifieds-and-directory-pro' ); ?>
					</div>

					<button type="button" class="acadp-button acadp-button-close">
						<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" width="20px" height="20px" stroke-width="1.5" stroke="currentColor" class="acadp-flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
					</button>
				</div>
				<!-- Body -->
				<div class="acadp-modal-body">
					<?php echo wp_kses_post( nl2br( $optin_text ) ); ?>
				</div>
				<!-- Footer -->
				<div class="acadp-modal-footer">
					<button type="button" class="acadp-button acadp-button-primary acadp-button-close">
						<?php esc_html_e( 'Close', 'advanced-classifieds-and-directory-pro' ); ?>
					</button>
				</div>
			</div>
		</div>
	</div>
<?php endif;