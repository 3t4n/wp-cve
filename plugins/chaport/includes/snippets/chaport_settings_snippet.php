<?php if ($this->wp_version_is_compatible()): ?>
	<form action='options.php' method='POST'>
		<?php settings_fields('chaport_options') ?>
		<?php do_settings_sections('chaport') ?>
		<p class='submit'>
			<input type='submit' name='submit' id='submit' class='button button-primary' value='<?php echo __('Save Changes', 'chaport') ?>' />
		</p>
	</form>
<?php else: ?>
	<p>
		<?php printf(
			__('Chaport Live Chat Plugin supports Wordpress starting from version %s. Please upgrade.', 'chaport'),
			self::WP_MAJOR . '.' . self::WP_MINOR
		) ?>
	</p>
<?php endif; ?>
