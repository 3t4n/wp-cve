<div class="field small-12 columns">
	<?php if ($label) : ?>
		<label>
			<?php echo $label; ?>
		</label>
	<?php endif; ?>

	<div class="row">
		<?php
			$leftColumns = empty($options['textBefore']) ? 0 : 3;
			$rightColumns = empty($options['textAfter']) ? 0 : 3;
			$centerColumns = 12 - $leftColumns - $rightColumns;
		?>

		<?php if (!empty($options['textBefore'])) : ?>
		<div class="columns small-<?php echo $leftColumns ?> text-before">
			<?php echo $options['textBefore']; ?>
		</div>
		<?php endif; ?>

		<div class="columns small-<?php echo $centerColumns; ?>">
			<input id="<?php echo $id; ?>" <?php echo $attributes; ?>
			       type="text" name="<?php echo $name; ?>"
			       value="<?php echo $value; ?>" >
		</div>

		<?php if (!empty($options['textAfter'])) : ?>
			<div class="columns small-<?php echo $rightColumns ?> text-after">
				<?php echo $options['textAfter']; ?>
			</div>
		<?php endif; ?>
	</div>

	<?php if ($description) : ?>
		<p class="help-text"><?php echo $description; ?></p>
	<?php endif; ?>
</div>
