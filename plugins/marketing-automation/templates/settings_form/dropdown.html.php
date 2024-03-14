<tr valign="top">
	<th scope="row">
		<label for="<?php echo $key; ?>"><?php echo $name; ?></label>
	</th>
	<td>
		<select name="<?php echo $key; ?>" id="<?php echo $key; ?>" class="<?php echo $class ? $class : ''; ?>">
			<?php if ($options != NULL): ?>
			<?php foreach ($options as $index => $option): ?>
			<option value="<?php echo $option['value']; ?>" <?php echo $value == $option['value'] ? 'selected' : ''; ?>><?php echo $option['label']; ?></option>
			<?php endforeach; ?>
			<?php else: ?>
			<option value="" <?php echo $value == $option['value'] ? 'selected' : ''; ?>>- None -</option>
			<?php endif; ?>
		</select>
		
		<?php if (isset($description) && !empty($description)): ?>
			<p class="description"><?php echo $description; ?></p>
		<?php endif; ?>
	</td>
</tr>