<div class="field small-12 columns">
	<fieldset>
		<?php if ($label) : ?>
			<legend><?php echo $label; ?></legend>
		<?php endif; ?>

		<?php foreach ($options['values'] as $key => $item) : ?>
			<?php $idElement = "{$id}_{$key}"; ?>
			<input id="<?php echo $idElement; ?>"
			       type="checkbox" name="<?php echo "{$name}[{$item['name']}]"; ?>"
			       value="1" <?php echo isset($value[$item['name']]) && $value[$item['name']] ? 'checked' : ''; ?>
			       data-dependents='<?php echo $dependents; ?>' >
			<label for="<?php echo $idElement; ?>"><?php echo $item['label']; ?></label>
		<?php endforeach; ?>
	</fieldset>

	<?php if ($description) : ?>
		<p class="help-text"><?php echo $description; ?></p>
	<?php endif; ?>
</div>
