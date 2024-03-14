<select name="<?php echo esc_attr( self::OPTION_NAME_CURRENCY ); ?>" >
	<option value="automatic" <?php echo esc_attr( (($currency === 'automatic') ? selected(true) : '') ); ?>>
		<?php _e('Automatic', 'getyourguide-widget'); ?>
	</option>

	<?php foreach ($values as $isoCode => $currencyName): ?>
		<option value="<?php echo esc_attr( $isoCode ) ?>" <?php echo esc_attr( (($currency === $isoCode) ? selected(true) : '') ); ?>>
			<?php echo esc_html($currencyName) ?>
		</option>
	<?php endforeach; ?>
</select>
<p class="description">
	<?= _e('Select the currency for the widget. If automatic is selected GetYourGuide will guess the currency based on the user\'s locale.', 'getyourguide-widget'); ?>
</p>