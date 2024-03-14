<div class="wrap">
	<h2><span class="dashicons dashicons-format-image" style="font-size:38px;display:inline;vertical-align:middle;"></span> Retro Game Emulator</h2>
	<p>To insert the emulator in a post or page use the shortcode <code>[nes]</code></p>
	<div class="card">
		<h3><?php _e('Upload Rom'); ?></h3>
		<p><?php printf(__('Use this form to upload roms.  Be sure they have an %s extension.'), '<code>.nes</code>'); ?></p>
		<form action="admin-post.php" method="post" enctype="multipart/form-data">
			<input name="rom_file" type="file" value="" />
			<input type="hidden" name="action" value="retro_game_upload_rom" />
			<?php wp_nonce_field('retro-game-emulator-options', 'retro-game-emulator-nonce'); ?>
			<?php submit_button('Upload'); ?>
		</form>
	</div>

	<h3><?php _e('Installed Roms'); ?></h3>
	<table class="wp-list-table widefat fixed striped">
		<tr>
			<td>Filename</td>
			<td>Actions</td>
		</tr>
		<?php if (is_dir($this->romsPath)) : ?>
			<?php foreach (scandir($this->romsPath) as $rom) : ?>
				<?php if (substr($rom, -3) === 'nes') : ?>
					<tr>
						<td><?php echo $rom; ?></td>
						<td class=""><a href="<?php echo wp_nonce_url("?page=retro-game-emulator&action=delete-rom-$rom", "delete-rom-$rom"); ?>" style="color:#a00;" onclick="return confirm('Are you sure?')">Delete</a></td>
					</tr>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endif; ?>
	</table>
	<p><?php _e('Roms are stored in'); ?> <code><?php echo $this->romsPath; ?></code></p>
</div>