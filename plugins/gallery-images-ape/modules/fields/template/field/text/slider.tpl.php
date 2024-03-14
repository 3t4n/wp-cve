<div class="field small-12 columns">
	<?php if ($label) : ?>
		<label>
			<?php echo $label; ?>
		</label>
	<?php endif; ?>
</div>

<?php
	$textBeforeColumns = empty($options['textBefore']) ? 0 : 2;
	$textAfterColumns = empty($options['textAfter']) ? 0 : 2;
	$textColumns = 2;
	$sliderColumns = 12 - $textBeforeColumns - $textAfterColumns - $textColumns;
?>

<?php if (!empty($options['textBefore'])) : ?>
	<div class="field columns small-<?php echo $textBeforeColumns ?> text-before">
		<?php echo $options['textBefore']; ?>
	</div>
<?php endif; ?>

<div class="field columns small-<?php echo $textColumns; ?>">
	<input id="<?php echo $id; ?>" <?php echo $attributes; ?>
	       type="number" name="<?php echo $name; ?>"
	       value="<?php echo $value; ?>" >
</div>

<?php if (!empty($options['textAfter'])) : ?>
	<div class="field columns small-<?php echo $textAfterColumns ?> text-after">
		<?php echo $options['textAfter']; ?>
	</div>
<?php endif; ?>

<div class="field small-<?php echo $sliderColumns; ?> columns">
	<div class="slider" data-slider
	     data-initial-start="<?php echo $value; ?>"
	     data-start="<?php echo $options['data-start']; ?>"
	     data-end="<?php echo $options['data-end']; ?>"
	     data-step="<?php echo $options['step']; ?>">
		<span class="slider-handle" data-slider-handle role="slider" tabindex="1"
		      aria-controls="<?php echo $id; ?>"></span>
		<span class="slider-fill" data-slider-fill></span>
	</div>
</div>

<div class="field small-12 columns">
	<?php if ($description) : ?>
		<p class="help-text"><?php echo $description; ?></p>
	<?php endif; ?>
</div>
