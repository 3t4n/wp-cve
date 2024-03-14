<div class="row row-static-margin-bottom">
	<div class="col-xs-5">
		<label class="control-label" for="yrm-accordion-advanced-content-bg-color"><?php _e('Content', YRM_LANG);?></label>
	</div>
</div>
<div class="sub-content">
	<div class="row row-static-margin-bottom">
		<div class="col-xs-5">
			<label class="control-label" for="yrm-accordion-advanced-content-bg-color"><?php _e('Background Color', YRM_LANG);?></label>
		</div>
		<div class="col-xs-4">
			<input type="text" id="yrm-accordion-advanced-content-bg-color" class="input-md yrm-accordion-colors yrm-accordion-advanced-content-bg-color" name="yrm-accordion-advanced-content-bg-color" value="<?php echo esc_attr($this->getOptionValue('yrm-accordion-advanced-content-bg-color'))?>"><br>
		</div>
	</div>
	<div class="row row-static-margin-bottom">
		<div class="col-xs-5">
			<label class="control-label" for="yrm-accordion-advanced-content-color"><?php _e('Color', YRM_LANG);?></label>
		</div>
		<div class="col-xs-4">
			<input type="text" id="yrm-accordion-advanced-content-color" class="input-md yrm-accordion-colors yrm-accordion-advanced-content-color" name="yrm-accordion-advanced-content-color" value="<?php echo esc_attr($this->getOptionValue('yrm-accordion-advanced-content-color'))?>"><br>
		</div>
	</div>
	<div class="row row-static-margin-bottom">
		<div class="col-xs-5">
			<label class="control-label" for="yrm-accordion-advanced-content-font-size"><?php _e('Font zie', YRM_LANG);?></label>
		</div>
		<div class="col-xs-4">
			<input type="text" id="yrm-accordion-advanced-content-font-size" class="input-md form-control" name="yrm-accordion-advanced-content-font-size" value="<?php echo esc_attr($this->getOptionValue('yrm-accordion-advanced-content-font-size'))?>" placeholder="<?php _e('Font size', YRM_LANG)?>">
		</div>
	</div>
	<div class="row row-static-margin-bottom">
		<div class="col-xs-5">
			<label class="control-label" for="yrm-accordion-advanced-content-font-weight"><?php _e('Font weight', YRM_LANG);?></label>
		</div>
		<div class="col-xs-4">
			<?php echo ReadMoreFunctions::yrmSelectBox($params['btnFontWeight'], esc_attr($this->getOptionValue('yrm-accordion-advanced-content-font-weight')), array('name' => 'yrm-accordion-advanced-content-font-weight', 'class' => 'yrm-js-select2 yrm-accordion-icons'));?><br>
		</div>
	</div>
	<div class="row row-static-margin-bottom">
		<div class="col-xs-5">
			<label class="control-label" for="yrm-accordion-advanced-content-text-align"><?php _e('Text align', YRM_LANG);?></label>
		</div>
		<div class="col-xs-4">
			<?php echo ReadMoreFunctions::yrmSelectBox($params['horizontalAlign'], esc_attr($this->getOptionValue('yrm-accordion-advanced-content-text-align')), array('name' => 'yrm-accordion-advanced-content-text-align', 'class' => 'yrm-js-select2 yrm-accordion-icons'));?><br>
		</div>
	</div>
	<div class="row row-static-margin-bottom">
		<div class="col-xs-5">
			<label class="control-label" for=""><?php _e('Padding', YRM_LANG);?></label>
		</div>
		<div class="col-xs-1">
			<label for="yrm-accordion-advanced-content-padding-top"><?php _e('Top', YRM_LANG);?></label>
			<input type="text" id="yrm-accordion-advanced-content-padding-top" class="input-md form-control" name="yrm-accordion-advanced-content-padding-top" value="<?php echo esc_attr($this->getOptionValue('yrm-accordion-advanced-content-padding-top'))?>" placeholder="<?php _e('Padding', YRM_LANG)?>"><br>
		</div>
		<div class="col-xs-1">
			<label for="yrm-accordion-advanced-content-padding-right"><?php _e('Right', YRM_LANG);?></label>
			<input type="text" id="yrm-accordion-advanced-content-padding-right" class="input-md form-control" name="yrm-accordion-advanced-content-padding-right" value="<?php echo esc_attr($this->getOptionValue('yrm-accordion-advanced-content-padding-right'))?>" placeholder="<?php _e('Padding', YRM_LANG)?>"><br>
		</div>
		<div class="col-xs-1">
			<label for="yrm-accordion-advanced-content-padding-bottom"><?php _e('Bottom', YRM_LANG);?></label>
			<input type="text" id="yrm-accordion-advanced-content-padding-bottom" class="input-md form-control" name="yrm-accordion-advanced-content-padding-bottom" value="<?php echo esc_attr($this->getOptionValue('yrm-accordion-advanced-content-padding-bottom'))?>" placeholder="<?php _e('Padding', YRM_LANG)?>"><br>
		</div>
		<div class="col-xs-1">
			<label for="yrm-accordion-advanced-content-padding-left"><?php _e('Left', YRM_LANG);?></label>
			<input type="text" id="yrm-accordion-advanced-content-padding-left" class="input-md form-control" name="yrm-accordion-advanced-content-padding-left" value="<?php echo esc_attr($this->getOptionValue('yrm-accordion-advanced-content-padding-left'))?>" placeholder="<?php _e('Padding', YRM_LANG)?>"><br>
		</div>
	</div>
</div>