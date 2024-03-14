<div class="field small-12 columns">
	<?php if ($label) : ?>
		<label>
			<?php echo $label; ?>
		</label>
	<?php endif; ?>
	
	<div class="field small-2 columns"><strong>Screen size</strong></div>
	<div class="field small-3 columns"> <strong> Auto size</strong></div>
	<hr />
	<div id="<?php echo $id; ?>" class="field small-12 columns" <?php echo $attributes; ?>>
			<?php echo $fields; ?>
	</div>

	<?php if ($description) : ?>
		<p class="help-text"><?php echo $description; ?></p>
	<?php endif; ?>
</div>
