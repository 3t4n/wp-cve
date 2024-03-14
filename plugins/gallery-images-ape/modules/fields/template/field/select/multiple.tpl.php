<div class="field small-12 columns">
	<?php if ($label) : ?>
		<label>
			<?php echo $label; ?>
		</label>
	<?php endif; ?>

	<select id="<?php echo $id; ?>" <?php echo $attributes; ?> multiple
	        name="<?php echo $name; ?>[]" >
		<?php foreach ($options['values'] as $optionValue => $optionLabel) : ?>
			<option value="<?php echo $optionValue; ?>" <?php if (in_array($optionValue, $value)) { echo 'selected'; } ?>>
				<?php echo $optionLabel; ?>
			</option>
		<?php endforeach; ?>
	</select>

	<?php if ($description) : ?>
		<p class="help-text"><?php echo $description; ?></p>
	<?php endif; ?>
</div>
