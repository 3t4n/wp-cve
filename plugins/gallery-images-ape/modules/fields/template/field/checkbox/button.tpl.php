<div class="field small-12 columns">
	<div id="field-element-<?php echo $id; ?>" class="button-element">
		<input id="<?php echo $id; ?>"
		       type="checkbox" name="<?php echo $name; ?>"
		       value="1" <?php echo $value ? 'checked' : '' ?>
		       data-dependents='<?php echo $dependents; ?>' >
		<label class="button" for="<?php echo $id; ?>">
			<?php echo $label; ?>
		</label>
	</div>

	<?php if ($description) : ?>
		<p class="help-text"><?php echo $description; ?></p>
	<?php endif; ?>
</div>
