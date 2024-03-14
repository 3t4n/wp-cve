<div class="field small-12 columns">
	<fieldset>
		<?php if ($label) : ?>
			<legend><?php echo $label; ?></legend>
		<?php endif; ?>

		<div class="button-group  ">
			<?php foreach ($options['values'] as $key => $item) : ?>
				<?php $idElement = "{$id}_{$key}"; ?>
				<input id="<?php echo $idElement; ?>"
				       type="radio" name="<?php echo $name; ?>"
				       value="<?php echo $item['value']; ?>" <?php echo $item['value'] === $value ? 'checked' : ''; ?>
				       data-dependents='<?php echo $dependents; ?>' >
				<label for="<?php echo $idElement; ?>" class="button">
					<?php echo $item['label']; ?>
				</label>
			<?php endforeach; ?>
		</div>
	</fieldset>

	<?php if ($description) : ?>
		<p class="help-text"><?php echo $description; ?></p>
	<?php endif; ?>
</div>
