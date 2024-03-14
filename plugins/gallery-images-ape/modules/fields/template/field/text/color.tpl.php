<?php 
	$colCount = 12;  
	if(isset($options['column'])) $colCount = $options['column'];
	
	$colCountWrap = 12;
	if(isset($options['columnWrap'])) $colCountWrap = $options['columnWrap'];
?>
<div id="field-div-color-<?php echo $id; ?>" 
	class="field small-<?php echo $colCountWrap;?> columns">
	<?php if ($label) : ?>
		<label>
			<?php echo $label; ?>
		</label>
	<?php endif; ?>

	<div class="input-group small-<?php echo $colCount;?>" id="field-color-panel-<?php echo $id; ?>">
		<?php if( isset($options['leftLabel']) ) : ?>
			<span class="input-group-label">
				<?php echo $options['leftLabel']; ?>
			</span>
		<?php endif; ?>

		<input  <?php echo $attributes; ?>
	       id="field-color-<?php echo $id; ?>"
	       type="text" 
	       class="input-group-field field-color-picker"
	       name="<?php echo $name; ?>"
	       value="<?php echo $value; ?>"
	       data-alpha="<?php echo isset($options['alpha']) && $options['alpha'] ? '1' : '0'; ?>"
	       data-default="<?php echo $default; ?>"
	       >

		<span 
			class="input-group-label"  
			id="field-color-button-<?php echo $id; ?>"
			style="background-color: <?php echo $value; ?>"
		> &nbsp;
		</span>

	</div>

	<?php if ($description) : ?>
		<p class="help-text"><?php echo $description; ?></p>
	<?php endif; ?>
</div>

<script>
(function(){
    var parentEl = document.querySelector('#field-color-panel-<?php echo $id; ?>'),
    	buttonEl = document.querySelector('#field-color-button-<?php echo $id; ?>'),
    	inputEl  = document.querySelector('#field-color-<?php echo $id; ?>'),
    	valueColor = inputEl.value,
    	alphaEnable = inputEl.getAttribute('data-alpha') == 1 ? true : false;
	
	if( !tinycolor(valueColor).isValid() ) valueColor = inputEl.getAttribute('data-default');

    var picker = new Picker({
    	parent: parentEl,
    	popup: 'top',
    	editor: true,
    	editorFormat: 'rgb',
    	color: valueColor,
    	alpha: alphaEnable,
    	onDone : function(color){

    		var colorVal = color.hex;

    		if( colorVal.length > 7 ) colorVal = colorVal.substring( 0, 7);

    		if( alphaEnable ){
    			if(color.rgba[3]==1) colorVal = color.rgbString;
    				else colorVal = color.rgbaString;
    		}

    		if( ! tinycolor(colorVal).isValid() ) colorVal = inputEl.getAttribute('data-default');
    		
    		inputEl.value = colorVal;
    		buttonEl.style.backgroundColor = colorVal;
    	}
    });
})();
</script>

