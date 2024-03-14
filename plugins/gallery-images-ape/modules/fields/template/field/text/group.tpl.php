<?php 
	$colCount = 12;  
	if(isset($options['column'])) $colCount = $options['column'];
	
	$colCountWrap = 12;
	if(isset($options['columnWrap'])) $colCountWrap = $options['columnWrap'];
?>
<div id="field-div-<?php echo $id; ?>" 
		class="field small-<?php echo $colCountWrap;?> columns" 
		<?php if( isset($options['hide']) ) echo 'style="display:none;"';?>
	>
	<?php if ($label) : ?>
		<label>
			<?php echo $label; ?>
		</label>
	<?php endif; ?>

	<div id="<?php echo "field-element-{$id}"; ?>" class="input-group small-<?php echo $colCount;?> ">
		<?php if (isset($options['leftLabel'])) : ?>
			<span class="input-group-label">
				<?php echo $options['leftLabel']; ?>
			</span>
		<?php endif; ?>

		<input id="<?php echo $id; ?>" class="input-group-field" <?php echo $attributes; ?>
		       type="text" name="<?php echo $name; ?>"
		       value="<?php echo $value; ?>" >

		<?php if (isset($options['rightLabel'])) : ?>
			<span class="input-group-label">
				<?php echo $options['rightLabel']; ?>
			</span>
		<?php endif; ?>
	</div>

	<?php if ($description) : ?>
		<p class="help-text"><?php echo $description; ?></p>
	<?php endif; ?>
</div>
