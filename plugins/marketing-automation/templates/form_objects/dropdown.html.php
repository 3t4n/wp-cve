<tr valign="top">
	<th scope="row">
		<label for="<?php echo $key; ?>"><?php echo $name; ?></label>
	</th>
	<td>
		<?php if ($options != NULL): ?>
		<select name="<?php echo $key; ?><?php echo $multiple ? '[]' : ''; ?>" id="<?php echo $key; ?>" class="<?php echo $class ? $class : ''; ?>" <?php echo $multiple ? 'multiple' : ''; ?>>
			<?php if ($options != NULL): ?>
			<?php foreach ($options as $index => $option): ?>
			<?php	if ($multiple): ?>
			<option value="<?php echo $option['value']; ?>" <?php if(!is_bool($value)) echo in_array($option['value'], $value) ? 'selected' : ''; ?>><?php echo $option['label']; ?></option>
			<?php	else: ?>
			<option value="<?php echo $option['value']; ?>" <?php echo $value == $option['value'] ? 'selected' : ''; ?>><?php echo $option['label']; ?></option>
			<?php	endif; ?>
			<?php endforeach; ?>
			<?php else: ?>
			<?php	if ($multiple): ?>
			<option value="" <?php echo in_array($option['value'], $value) ? 'selected' : ''; ?>>- None -</option>
			<?php	else: ?>
			<option value="" <?php echo $value == $option['value'] ? 'selected' : ''; ?>>- None -</option>
			<?php	endif; ?>
			<?php endif; ?>
		</select>
		
		<?php if (isset($userSyncCount) && $userSyncCount): ?>
			(Users Synched: <?php echo count($userSyncCount);?>)
		<?php endif; ?>
		
		<?php if (isset($haveSyncButton) && $haveSyncButton): ?>
		<input type="button" class="button-primary syncUsersManualy" value="Synch Users Manually" />
		<?php endif; ?>

		<?php if (isset($trackingcode) && $trackingcode): ?>
		<?php	foreach($options as $index => $option): ?>
		<div id="trackingcode-<?php echo $option['value'];  ?>" style="display: none;">
		<?php echo $option['code']; ?>
		</div>
		<?php	endforeach; ?>
		<?php endif; ?>
		
		<?php if (isset($description) && !empty($description)): ?>
			<p class="description"><?php echo $description; ?></p>
		<?php endif; ?>
		<?php else: ?>
		<span style="color: red;">(Not Available)</span>
		<?php endif; ?>
	</td>
</tr>