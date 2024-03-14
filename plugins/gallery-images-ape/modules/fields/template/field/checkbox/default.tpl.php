<div class="field small-12 columns">
	<fieldset>
		<?php if ($label) : ?>
			<legend><?php echo $label; ?></legend>
		<?php endif; ?>

		<input id="<?php echo $id; ?>"
		       type="checkbox"  name="<?php echo $name; ?>"
		       value="1" <?php echo $value ? 'checked' : ''; ?>
		       data-dependents='<?php echo $dependents; ?>' >
		<label for="<?php echo $id; ?>"><?php echo $label; ?></label>

		<?php if ($description) : ?>
			<p class="help-text"><?php echo $description; ?></p>
		<?php endif; ?>
	</fieldset>
</div>
