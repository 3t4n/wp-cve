
	<div class="field small-12 columns <?php if($is_sub_field) echo 'wrap-field checkbox-group-button'; ?>">
		<fieldset>
			<?php if ($label) : ?>
				<legend><?php echo $label; ?></legend>
			<?php endif; ?>

			<?php foreach ($options['values'] as $key => $item) : 
				$idElement = "{$id}_{$key}"; ?>
				<div id="field-element-<?php echo $idElement; ?>" class="button-element">
					<input id="<?php echo $idElement; ?>"
					       type="checkbox" name="<?php echo "{$name}[{$item['name']}]"; ?>"
					       value="1" <?php echo isset($value[$item['name']]) && $value[$item['name']] ? 'checked' : '' ?>
					       data-dependents='<?php echo $dependents; ?>' >
					<label class="button" for="<?php echo $idElement; ?>">
						<?php echo $item['label']; ?>
					</label>
				</div>
			<?php endforeach; ?>
		</fieldset>

		<?php if ($description) : ?>
			<p class="help-text"><?php echo $description; ?></p>
		<?php endif; ?>
	</div>
