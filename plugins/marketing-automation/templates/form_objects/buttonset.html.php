<tr valign="top">
	<th scope="row">
		<label for="<?php echo $key; ?>"><?php echo $name; ?></label>
	</th>
	<td>
		<?php if ($options != NULL): ?>
		<div class="vb-buttonset-wrapper">
			<?php foreach ($options as $index => $option): ?>
				<input type="<?php echo $type; ?>" name="<?php echo $key; ?>" id="<?php echo "{$key}_{$index}"; ?>" value="<?php echo $option['value']; ?>" class="<?php echo $option['class'] ? $option['class'] : ''; ?>" <?php echo in_array($option['value'], $value) ? 'checked' : ''; ?> />
				<label for="<?php echo "{$key}_{$index}"; ?>"><?php echo $option['label']; ?></label>
			<?php endforeach; ?>
		</div>
		<?php else: ?>
		<span style="color: red;">(Not Available)</span>
		<?php endif; ?>
		
		<?php if (isset($description) && !empty($description)): ?>
			<p class="description"><?php echo $description; ?></p>
		<?php endif; ?>
	</td>
</tr>