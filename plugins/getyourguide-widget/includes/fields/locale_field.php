<select name="<?php echo esc_attr( self::OPTION_NAME_LOCALE ); ?>" >
	<?php foreach ($values as $localeCode => $localeName): ?>
		<option value="<?php echo esc_attr( $localeCode ) ?>" <?php echo esc_attr( (($localeCode === $locale) ? selected(true) : '') ); ?>>
			<?php echo esc_html($localeName) ?>
		</option>
	<?php endforeach; ?>
</select>
<p class="description">
	<?= _e('Select the Language for the widget.', 'getyourguide-widget'); ?>
</p>