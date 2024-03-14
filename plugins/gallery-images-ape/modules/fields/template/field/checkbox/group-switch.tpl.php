<div class="field small-12 columns">
	<fieldset>
		<?php if ($label) : ?>
			<legend><?php echo $label; ?></legend>
		<?php endif; ?>

		<?php foreach ($options['values'] as $key =>  $item) : ?>
			<?php $idElement = "{$id}_{$key}"; ?>
			<div id="field-element-<?php echo $idElement; ?>" class="switch-element">
				<div class="switch <?php echo $options['size']; ?>">
					<input id="<?php echo $idElement; ?>" class="switch-input"
					       type="checkbox" name="<?php echo "{$name}[{$item['name']}]"; ?>"
					       value="1" <?php echo isset($value[$item['name']]) && $value[$item['name']] ? 'checked' : '' ?>
					       data-dependents='<?php echo $dependents; ?>' >
					<label class="switch-paddle" for="<?php echo $idElement; ?>">
				<span class="switch-active" aria-hidden="true">
					<?php echo isset($item['onLabel']) ? $item['onLabel'] : ''; ?>
				</span>
				<span class="switch-inactive" aria-hidden="true">
					<?php echo isset($item['offLabel']) ? $item['offLabel'] : ''; ?>
				</span>
					</label>
				</div>
			</div>
		<?php endforeach; ?>
	</fieldset>

	<?php if ($description) : ?>
		<p class="help-text"><?php echo $description; ?></p>
	<?php endif; ?>
</div>
