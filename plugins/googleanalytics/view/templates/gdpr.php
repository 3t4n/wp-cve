<?php
/**
 * GDPR view.
 *
 * @package GoogleAnalytics
 */

?>
<?php if ( true === empty( $gdpr_config ) ) : ?>
	<tr>
		<th scope="row"><?php esc_html_e( 'Enable GDPR Consent Management Tool', 'googleanalytics' ); ?>:</th>
	</tr>
	<tr>
		<?php if ( true === Ga_Helper::are_features_enabled() ) : ?>
			<td>
				<button class="gdpr-enable"><?php esc_html_e( 'Enable' ); ?></button>
			</td>
		<?php else : ?>
			<td>
				<label class="<?php echo esc_attr( false === Ga_Helper::are_features_enabled() ? 'label-grey ga-tooltip' : '' ); ?>">
					<button class="gdpr-enable" disabled="disabled"><?php esc_html_e( 'Enable' ); ?></button>
					<span class="ga-tooltiptext ga-tt-abs"><?php echo esc_html( $tooltip ); ?></span>
				</label>
			</td>
		<?php endif; ?>
	</tr>
<?php endif; ?>
