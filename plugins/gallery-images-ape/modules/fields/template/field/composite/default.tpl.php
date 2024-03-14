<div class="field small-12 columns"  >
	<?php if ($label) : ?>
		<label>
			<?php echo $label; ?>
		</label>
	<?php endif; ?>

	<div id="<?php echo $id; ?>" class="row" <?php echo $attributes; ?>>
		<?php echo $fields; ?>
	</div>

	<?php if ($description) : ?>
		<p class="help-text"><?php echo $description; ?></p>
	<?php endif; ?>
</div>
