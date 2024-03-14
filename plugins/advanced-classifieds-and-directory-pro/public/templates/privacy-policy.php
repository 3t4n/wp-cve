<?php

/**
 * Privacy Policy
 *
 * @link    https://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */

$privacy_policy_settings = get_option( 'acadp_privacy_policy' );

if ( empty( $privacy_policy_settings['show_privacy_policy'] ) || empty( $privacy_policy_settings['privacy_policy_text'] ) ) {
	return false;
}

$optin_text  = trim( $privacy_policy_settings['privacy_policy_text'] );					
$optin_label = ! empty( $privacy_policy_settings['privacy_policy_label'] ) ? trim( $privacy_policy_settings['privacy_policy_label'] ) : __( 'I have read and agree to the Terms and Conditions', 'advanced-classifieds-and-directory-pro' );

$is_url = filter_var( $optin_text, FILTER_VALIDATE_URL ) ? true : false;
?>
<div id="acadp-form-group-privacy_policy" class="acadp-form-group">
	<label class="acadp-inline-flex acadp-gap-1.5 acadp-items-center">
		<input type="checkbox" name="privacy_policy" class="acadp-form-control acadp-form-checkbox acadp-form-validate" required />
		<?php
		if ( $is_url ) {
			printf(
				'<a href="%s" class="acadp-underline" target="_blank">%s</a>',
				esc_url( $optin_text ),
				wp_kses_post( $optin_label )
			);
		} else {
			printf(
				'<a href="javascript:void(0);" class="acadp-button-modal acadp-underline" data-target="#acadp-modal-privacy_policy">%s</a>',
				wp_kses_post( $optin_label )
			);
		}
		?>
		<span class="acadp-form-required" aria-hidden="true">*</span>
	</label>
</div>

<?php if ( ! $is_url ) : ?>
	<!-- Modal -->
	<div id="acadp-modal-privacy_policy" class="acadp-modal">
		<!-- Dialog -->
		<div class="acadp-modal-dialog">
			<div class="acadp-modal-content">
				<!-- Header -->
				<div class="acadp-modal-header">
					<div class="acadp-text-xl">
						<?php esc_html_e( 'Privacy Policy', 'advanced-classifieds-and-directory-pro' ); ?>
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