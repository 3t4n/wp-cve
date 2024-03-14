<?php
$widthMeasure = YcfDefaultValues::formWidthMeasure();
?>
<div class="row ycf-option-row">
	<div class="col-md-2">
		<span>Form width</span>
	</div>
	<div class="col-md-4">
		<input type="text" class="form-control col-md-2" name="contact-form-width" value="<?php echo esc_html($contactFormWidth);?>">
	</div>
	<div class="col-md-1">
		<?php echo YcfFunctions::createSelectBox($widthMeasure, $contactFormWidthMeasure, array("name"=>"contact-form-width-measure","class"=>"ycf-select-box")); ?>
	</div>
</div>