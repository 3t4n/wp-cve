<div class="field small-2 columns">
	<?php if ($label) : ?>
		<label>
			<?php echo $label; ?>
	<?php endif; ?>

	<select id="<?php echo $id; ?>" <?php echo $attributes; ?>
	        name="<?php echo $name; ?>"
	        data-dependents='<?php echo $dependents; ?>' >
		<?php foreach ($options['values'] as $optionValue => $optionLabel) : ?>
			<option value="<?php echo $optionValue; ?>" <?php if ($optionValue == $value) { echo 'selected'; } ?>>
				<?php echo $optionLabel; ?>
			</option>
		<?php endforeach; ?>
	</select>

	<?php if ($label) : ?>
		</label>
	<?php endif; ?>

	<?php if ($description) : ?>
		<p class="help-text"><?php echo $description; ?></p>
	<?php endif; ?>
</div>
