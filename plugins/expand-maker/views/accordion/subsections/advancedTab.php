<div class="row row-static-margin-bottom">
	<div class="col-xs-5">
		<label class="control-label" for="yrm-accordion-advanced-content-bg-color"><?php _e('Tab', YRM_LANG);?></label>
	</div>
</div>
<div class="sub-content">
	<div class="row row-static-margin-bottom">
		<div class="col-xs-5">
			<label class="control-label" for="yrm-accordion-advanced-tab-color"><?php _e('Color', YRM_LANG);?></label>
		</div>
		<div class="col-xs-4">
			<input type="text" id="yrm-accordion-advanced-tab-color" class="input-md yrm-accordion-colors yrm-accordion-advanced-tab-color" name="yrm-accordion-advanced-tab-color" value="<?php echo esc_attr($this->getOptionValue('yrm-accordion-advanced-tab-color'))?>"><br>
		</div>
	</div>
	<div class="row row-static-margin-bottom">
		<div class="col-xs-5">
			<label class="control-label" for="yrm-accordion-advanced-tab-bg-color"><?php _e('Background Color', YRM_LANG);?></label>
		</div>
		<div class="col-xs-4">
			<input type="text" id="yrm-accordion-advanced-tab-bg-color" class="input-md yrm-accordion-colors yrm-accordion-advanced-tab-bg-color" name="yrm-accordion-advanced-tab-bg-color" value="<?php echo esc_attr($this->getOptionValue('yrm-accordion-advanced-tab-bg-color'))?>"><br>
		</div>
	</div>
	<div class="row row-static-margin-bottom">
		<div class="col-xs-5">
			<label class="control-label" for="yrm-accordion-advanced-tab-font-size"><?php _e('Font size', YRM_LANG);?></label>
		</div>
		<div class="col-xs-4">
			<input type="text" id="yrm-accordion-advanced-tab-font-size" class="input-md form-control" name="yrm-accordion-advanced-tab-font-size" value="<?php echo esc_attr($this->getOptionValue('yrm-accordion-advanced-tab-font-size'))?>">
		</div>
	</div>
	<div class="row row-static-margin-bottom">
		<div class="col-xs-5">
			<label class="control-label" for="yrm-accordion-advanced-tab-font-weight"><?php _e('Font weight', YRM_LANG);?></label>
		</div>
		<div class="col-xs-4">
			<?php echo ReadMoreFunctions::yrmSelectBox($params['btnFontWeight'], esc_attr($this->getOptionValue('yrm-accordion-advanced-tab-font-weight')), array('name' => 'yrm-accordion-advanced-tab-font-weight', 'class' => 'yrm-js-select2 yrm-accordion-icons'));?><br>
		</div>
	</div>
	<div class="row row-static-margin-bottom">
		<div class="col-xs-5">
			<label class="control-label" for="yrm-accordion-advanced-tab-text-align"><?php _e('Text align', YRM_LANG);?></label>
		</div>
		<div class="col-xs-4">
			<?php echo ReadMoreFunctions::yrmSelectBox($params['horizontalAlign'], esc_attr($this->getOptionValue('yrm-accordion-advanced-tab-text-align')), array('name' => 'yrm-accordion-advanced-tab-text-align', 'class' => 'yrm-js-select2 yrm-accordion-icons'));?><br>
		</div>
	</div>
	<div class="row row-static-margin-bottom">
		<div class="col-xs-5">
			<label class="control-label" for="yrm-accordion-advanced-content-bg-color"><?php _e('Border', YRM_LANG);?></label>
		</div>
	</div>
	<div class="sub-sub-content">
		<div class="row row-static-margin-bottom">
			<div class="col-xs-5">
				<label class="control-label" for="yrm-accordion-advanced-tab-border-color"><?php _e('Color', YRM_LANG);?></label>
			</div>
			<div class="col-xs-4">
				<input type="text" id="yrm-accordion-advanced-tab-border-color" class="input-md yrm-accordion-colors yrm-accordion-advanced-tab-bg-color" name="yrm-accordion-advanced-tab-border-color" value="<?php echo esc_attr($this->getOptionValue('yrm-accordion-advanced-tab-border-color'))?>">
			</div>
		</div>
		<div class="row row-static-margin-bottom">
			<div class="col-xs-5">
				<label class="control-label" for="yrm-accordion-advanced-tab-border-size"><?php _e('Width', YRM_LANG);?></label>
			</div>
			<div class="col-xs-4">
				<input type="text" id="yrm-accordion-advanced-tab-border-size" class="input-md form-control" name="yrm-accordion-advanced-tab-border-size" value="<?php echo esc_attr($this->getOptionValue('yrm-accordion-advanced-tab-border-size'))?>">
			</div>
		</div>
		<div class="row row-static-margin-bottom">
			<div class="col-xs-5">
				<label class="control-label" for="yrm-accordion-advanced-tab-border-style"><?php _e('Style', YRM_LANG);?></label>
			</div>
			<div class="col-xs-4">
				<?php echo ReadMoreFunctions::yrmSelectBox($params['borderStyle'], esc_attr($this->getOptionValue('yrm-accordion-advanced-tab-border-style')), array('name' => 'yrm-accordion-advanced-tab-border-style', 'class' => 'yrm-js-select2 yrm-accordion-icons'));?><br>
			</div>
		</div>
		<div class="row row-static-margin-bottom">
			<div class="col-xs-5">
				<label class="control-label" for=""><?php _e('Padding', YRM_LANG);?></label>
			</div>
			<div class="col-xs-1">
				<label for="yrm-accordion-advanced-tab-padding-top"><?php _e('Top', YRM_LANG);?></label>
				<input type="text" id="yrm-accordion-advanced-tab-padding-top" class="input-md form-control" name="yrm-accordion-advanced-tab-padding-top" value="<?php echo esc_attr($this->getOptionValue('yrm-accordion-advanced-tab-padding-top'))?>" placeholder="<?php _e('Padding', YRM_LANG)?>"><br>
			</div>
			<div class="col-xs-1">
				<label for="yrm-accordion-advanced-tab-padding-right"><?php _e('Right', YRM_LANG);?></label>
				<input type="text" id="yrm-accordion-advanced-tab-padding-right" class="input-md form-control" name="yrm-accordion-advanced-tab-padding-right" value="<?php echo esc_attr($this->getOptionValue('yrm-accordion-advanced-tab-padding-right'))?>" placeholder="<?php _e('Padding', YRM_LANG)?>"><br>
			</div>
			<div class="col-xs-1">
				<label for="yrm-accordion-advanced-tab-padding-bottom"><?php _e('Bottom', YRM_LANG);?></label>
				<input type="text" id="yrm-accordion-advanced-tab-padding-bottom" class="input-md form-control" name="yrm-accordion-advanced-tab-padding-bottom" value="<?php echo esc_attr($this->getOptionValue('yrm-accordion-advanced-tab-padding-bottom'))?>" placeholder="<?php _e('Padding', YRM_LANG)?>"><br>
			</div>
			<div class="col-xs-1">
				<label for="yrm-accordion-advanced-tab-padding-left"><?php _e('Left', YRM_LANG);?></label>
				<input type="text" id="yrm-accordion-advanced-tab-padding-left" class="input-md form-control" name="yrm-accordion-advanced-tab-padding-left" value="<?php echo esc_attr($this->getOptionValue('yrm-accordion-advanced-tab-padding-left'))?>" placeholder="<?php _e('Padding', YRM_LANG)?>"><br>
			</div>
		</div>
	</div>
</div>