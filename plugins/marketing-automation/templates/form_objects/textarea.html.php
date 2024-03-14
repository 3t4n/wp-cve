<tr valign="top">
	<th scope="row">
		<label for="<?php echo $key; ?>"><?php echo $name; ?></label>
	</th>
	<td>
		<textarea cols="60" rows="5" name="<?php echo $key; ?>" id="<?php echo $key; ?>" class="<?php echo !$class ? 'regular-text' : $class; ?>"><?php echo $value; ?></textarea>
		<?php if (isset($description) && !empty($description)): ?>
			<p class="description"><?php echo $description; ?></p>
		<?php endif; ?>
	</td>
</tr>