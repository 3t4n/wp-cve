<div class="field small-8 columns">
	<?php if ($label) : ?>
		<label>
			<?php echo $label; ?>
		</label>
	<?php endif; ?>

	<input id="<?php echo $id; ?>" <?php echo $attributes; ?>
	       type="text" name="<?php echo $name; ?>"
	       value="<?php echo $value; ?>" >

	<?php if ($description) : ?>
		<p class="help-text"><?php echo $description; ?></p>
	<?php endif; ?>
</div>
