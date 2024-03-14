<?php
/**
 * Demographic view.
 *
 * @package GoogleAnalytics
 */

$enabledisable = get_option( 'googleanalytics_demographic' ) === '1' ? 'Disable' : 'Enable';
?>
<?php if ( 'Enable' === $enabledisable ) : ?>
	<tr>
		<th scope="row"><?php esc_html_e( 'Enable demographic charts', 'googleanalytics' ); ?>:</th>
	</tr>
	<?php if ( true === Ga_Helper::are_features_enabled() ) : ?>
		<td>
			<button id="demographic-popup"><?php esc_html_e( 'Enable', 'googleanalytics' ); ?></button>
		</td>
	<?php else : ?>
		<td>
			<label class="<?php echo false === Ga_Helper::are_features_enabled() ? 'label-grey ga-tooltip' : ''; ?>">
				<button class="gdpr-enable" disabled="disabled"><?php esc_html_e( 'Enable' ); ?></button>
				<span class="ga-tooltiptext ga-tt-abs"><?php echo esc_html( $tooltip ); ?></span>
			</label>
		</td>
	<?php endif; ?>
<?php endif; ?>
