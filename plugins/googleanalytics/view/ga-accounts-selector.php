<?php
/**
 * Accounts Selector view.
 *
 * @package GoogleAnalytics
 */

// Set add_manually_enabled fallback.
$add_manually_enabled = isset( $add_manually_enabled ) ? $add_manually_enabled : false;

// Set selected value fallback.
$selected = isset( $selected ) ? $selected : '';
?>
<div class="wrap">
	<input type="hidden" name="<?php echo esc_attr( Ga_Admin::GA_SELECTED_ACCOUNT ); ?>"
		value="<?php echo esc_attr( $selected ); ?>">
	<select id="ga_account_selector"
			name="<?php echo esc_attr( Ga_Admin::GA_SELECTED_ACCOUNT ); ?>"
		<?php echo disabled( true === $add_manually_enabled ); ?>
	>
		<option><?php echo esc_html__( 'Please select your Google Analytics account:', 'googleanalytics' ); ?></option>
		<?php
		if ( false === empty( $selector ) ) :
			foreach ( $selector as $account ) :
				?>
				<optgroup label="<?php echo esc_attr( $account['name'] ); ?>">
					<?php foreach ( $account['webProperties'] as $property ) : ?>
						<?php
						foreach ( $property['profiles'] as $profile ) :
							$profile_value = $account['id'] . '_' . $property['webPropertyId'] . '_' . $profile['id'];
							$profile_label = $property['name'] . '&nbsp;[' . $property['webPropertyId'] . '][' . $profile['id'] . ']';
							?>
							<option value="<?php echo esc_attr( $profile_value ); ?>"
								<?php echo selected( $selected, $profile_value ); ?>
							>
								<?php echo esc_html( $profile_label ); ?>
							</option>
						<?php endforeach; ?>
					<?php endforeach; ?>
				</optgroup>
				<?php
			endforeach;
		endif;
		?>
	</select>
</div>

