<div class="wrap">

	<h2><?php _e('Master Post Advert Settings', $this->name); ?></h2>

	<form method="post" action="options.php">

		<?php settings_fields($this->name.'_options'); ?>

		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><?php _e('Advert area alignment', $this->name); ?></th>
					<td>
						<fieldset>
							<?php foreach(array('left', 'center', 'right') as $align): ?>
								<label>
									<input type="radio" name="master_post_advert[align]" value="<?php echo $align; ?>"<?php if ($align == $options['align']) echo ' checked'; ?> />
									<span><?php _e(ucfirst($align)); ?></span>
								</label><br/>
							<?php endforeach; ?>
						</fieldset>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e('Advert area title', $this->name); ?></th>
					<td>
						<input type="text" name="master_post_advert[title]" value="<?php echo htmlspecialchars($options['title']); ?>" class="regular-text code" />
						<p class="description"><?php _e('HTML enabled.', $this->name); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e('Advert code', $this->name); ?></th>
					<td>
						<textarea name="master_post_advert[code]" cols="50" rows="10" class="large-text code"><?php echo htmlspecialchars($options['code']); ?></textarea>
					</td>
				</tr>
			</tbody>
		</table>

		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes'); ?>" />
		</p>

	</form>

	<p>Copyright &copy;2010 <a href="http://www.bbproject.net">BBPROJECT.NET</a></p>

	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----">
		<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
		<img alt="" border="0" src="https://www.paypalobjects.com/pl_PL/i/scr/pixel.gif" width="1" height="1">
	</form>

</div>