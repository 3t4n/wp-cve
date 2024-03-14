<?php 
	$colCount = 12;  
	if(isset($options['column'])) $colCount = $options['column'];
	
	$colCountWrap = 12;
	if(isset($options['columnWrap'])) $colCountWrap = $options['columnWrap'];
	?>
<div class="field small-<?php echo $colCountWrap;?> columns">
	<?php if ($label) : ?>
		<label for="field-select-<?php echo $id; ?>">
			<?php echo $label; ?>
		</label>
	<?php endif; ?>

	<select id="field-select-<?php echo $id; ?>" 
			<?php echo $attributes; ?>
			class="input-group small-<?php echo $colCount;?> "
		    name="<?php echo $name; ?>"
		    data-dependents='<?php echo $dependents; ?>' >
		<?php foreach ($options['values'] as $optionValue => $optionLabel) : ?>
			<option value="<?php echo $optionValue; ?>" <?php if ($optionValue == $value) { echo 'selected'; } ?>>
				<?php echo $optionLabel; ?>
			</option>
		<?php endforeach; ?>
	</select>

	<?php if ($description) : ?>
		<p class="help-text"><?php echo $description; ?></p>
	<?php endif; ?>
</div>
