<div class="field small-12 columns">
	<?php if ($label) : ?>
		<label>
			<?php echo $label; ?>
		</label>
	<?php endif; ?>

	<textarea id="<?php echo $id; ?>" <?php echo $attributes; ?> name="<?php echo $name; ?>" ><?php echo $value; ?></textarea>

	<?php if ($description) : ?>
		<p class="help-text"><?php echo $description; ?></p>
	<?php endif; ?>
</div>
