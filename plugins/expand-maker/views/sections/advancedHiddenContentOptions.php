<?php
$allowedTag = ReadMoreAdminHelper::getAllowedTags();
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<?php _e('Advanced options hidden content', YRM_LANG);?>
		<span class="yrm-tab-triangle glyphicon glyphicon-triangle-top"></span>
	</div>
	<div class="panel-body yrm-pro-options-wrapper">
		<?php echo ReadMoreAdminHelper::upgradeContent(); ?>
		<div class="row row-static-margin-bottom">
			<div class="col-xs-5">
				<label class="control-label" for="textinput"><?php _e('Hidden Content', YRM_LANG);?>:</label>
			</div>
			<div class="col-xs-4">
			</div>
		</div>
		<div class="yrm-sub-tab">
			<div class="row row-static-margin-bottom">
				<div class="col-xs-5">
					<label class="control-label" for="hidden-inner-width"><?php _e('Inner width', YRM_LANG);?></label>
				</div>
				<div class="col-xs-4">
					<input type="text" class="form-control input-md" placeholder="Ex: 100, 100px or 100%" id="hidden-inner-width" name="hidden-inner-width" value="<?php echo esc_attr($dataObj->getOptionValue('hidden-inner-width')
					)?>"><br>
				</div>
			</div>
			<!-- End button hoover -->
			<div class="row row-static-margin-bottom">
				<div class="col-xs-5">
					<label class="control-label" for="textinput"><?php _e('Background Color', YRM_LANG);?></label>
				</div>
				<div class="col-xs-4">
					<input type="text" class="input-md hidden-content-bg-color" name="hidden-content-bg-color" value="<?php echo esc_attr($hiddenContentBgColor)?>"><br>
				</div>
			</div>
			<div class="row row-static-margin-bottom">
				<div class="col-xs-5">
					<label class="control-label" for="textinput"><?php _e('Text Color', YRM_LANG);?></label>
				</div>
				<div class="col-xs-4">
					<input type="text" class="input-md hidden-content-text-color" name="hidden-content-text-color" value="<?php echo esc_attr($hiddenContentTextColor)?>"><br>
				</div>
			</div>
			<div class="row row-static-margin-bottom">
				<div class="col-xs-5">
					<label class="control-label" for="hidden-content-padding"><?php _e('Padding', YRM_LANG);?></label>
				</div>
				<div class="col-xs-3">
					<input type="number" class="input-md form-control js-hidden-content-padding" id="hidden-content-padding" name="hidden-content-padding" value="<?php echo esc_attr($hiddenContentPadding)?>"><br>
				</div>
				<div class="col-xs-1">
					<label class="control-label"><?php _e('Px', YRM_LANG);?></label>
				</div>
			</div>
			<div class="row row-static-margin-bottom">
				<div class="col-xs-5">
					<label class="control-label" for="textinput"><?php _e('Font Family', YRM_LANG);?></label>
				</div>
				<div class="col-xs-4">
					<?php echo wp_kses($functions::createSelectBox($params['googleFonts'], 'hidden-content-font-family', esc_attr($dataObj->getOptionValue('hidden-content-font-family'))), $allowedTag);?><br>
				</div>
			</div>
			<div class="yrm-accordion-content yrm-hide-content">
				<div class="row">
					<div class="col-xs-5">
						<label class="control-label" for="hidden-custom-font-family"><?php _e('button custom font family', YRM_LANG);?>:</label>
					</div>
					<div class="col-xs-4">
						<input type="text" id="hidden-custom-font-family" class="form-control input-md custom-font-family" name="hidden-content-custom-font-family" value="<?php echo esc_attr($dataObj->getOptionValue('hidden-content-custom-font-family'))?>"><br>
					</div>
				</div>
			</div>
			<div class="row row-static-margin-bottom">
				<div class="col-xs-5">
					<label class="control-label" for="textinput"><?php _e('Align', YRM_LANG);?></label>
				</div>
				<div class="col-xs-4">
					<?php echo wp_kses($functions::createSelectBox($params['hiddenContentAlign'], 'hidden-content-align', esc_attr($dataObj->getOptionValue('hidden-content-align'))), $allowedTag);?><br>
				</div>
			</div>
			<div class="row row-static-margin-bottom">
				<div class="col-xs-5">
					<label class="control-label" for="textinput"><?php _e('Line height', YRM_LANG);?></label>
				</div>
				<div class="col-xs-4">
					<?php echo wp_kses($functions::createSelectBox($params['hiddenContentLineHeight'], 'yrm-hidden-content-line-height', esc_attr($dataObj->getOptionValue('yrm-hidden-content-line-height'))), $allowedTag);?><br>
				</div>
			</div>
			<div class="yrm-accordion-content yrm-hide-content">
				<div class="row row-static-margin-bottom">
					<div class="col-xs-5">
						<label class="control-label" for="yrm-hidden-content-line-height-size"><?php _e('line height size', YRM_LANG);?></label>
					</div>
					<div class="col-xs-4">
						<input type="text" name="yrm-hidden-content-line-height-size" id="yrm-hidden-content-line-height-size" class="form-control" value="<?php echo esc_attr($dataObj->getOptionValue('yrm-hidden-content-line-height-size')); ?>">
					</div>
				</div>
			</div>
			<div class="row row-static-margin-bottom">
				<div class="col-xs-5">
					<label class="control-label-checkbox control-label" for="hidden-content-bg-image"><?php _e('Background Image', YRM_LANG);?></label>
				</div>
				<div class="col-xs-4">
					<div class="yrm-switch-wrapper">
						<label class="yrm-switch">
							<input type="checkbox" name="hidden-content-bg-image" id="hidden-content-bg-image" class="yrm-accordion-checkbox" <?php echo esc_attr($savedObj->getOptionValue('hidden-content-bg-image', true)); ?>>
							<span class="yrm-slider yrm-round"></span>
						</label>
					</div>
				</div>
			</div>
			<div class="yrm-accordion-content yrm-hide-content">
				<div class="row form-group">
					<div class="col-md-5">
						<label for="" class="ycd-label-of-select"><?php _e('Background Size', YRM_LANG); ?></label>
					</div>
					<div class="col-md-7 ycd-circles-width-wrapper">
						<?php echo wp_kses($functions::yrmSelectBox($params['bgImageSize'], $dataObj->getOptionValue('hidden-content-bg-img-size'), array('name'=>"hidden-content-bg-img-size",'class' => 'yrm-js-select2')), $allowedTag);?><br>
					</div>
				</div>
				<div class="row form-group">
					<div class="col-md-5">
						<label for="" class="ycd-label-of-select"><?php _e('Background Repeat', YRM_LANG); ?></label>
					</div>
					<div class="col-md-7 ycd-circles-width-wrapper">
						<?php echo wp_kses($functions::yrmSelectBox($params['bgImageRepeat'], $dataObj->getOptionValue('hidden-content-bg-repeat'), array('name'=>"hidden-content-bg-repeat",'class' => 'yrm-js-select2')), $allowedTag);?><br>
					</div>
				</div>
				<div class="row form-group">
					<div class="col-md-5">
						<label>
							<input id="js-upload-hidden-bg-image-button" class="button js-read-more-image-btn" type="button" value="<?php _e('Select Image', YRM_LANG)?>">
						</label>
					</div>
					<div class="col-md-7 ycd-circles-width-wrapper">
						<input type="url" name="hidden-bg-image-url" id="hidden-bg-image-url" class="form-control" value="<?php echo esc_attr($dataObj->getOptionValue('hidden-bg-image-url')); ?>">
					</div>
				</div>
			</div>
			<div class="row row-static-margin-bottom">
				<div class="col-xs-5">
					<label class="control-label-checkbox control-label" for="hidden-content-font-size"><?php _e('Font Size', YRM_LANG);?></label>
				</div>
				<div class="col-xs-4">
					<div class="yrm-switch-wrapper">
						<label class="yrm-switch">
							<input type="checkbox" name="hidden-content-font-size-enable" id="hidden-content-font-size-enable" class="yrm-accordion-checkbox" <?php echo esc_attr($savedObj->getOptionValue('hidden-content-font-size-enable', true)); ?>>
							<span class="yrm-slider yrm-round"></span>
						</label>
					</div>
				</div>
			</div>
			<div class="yrm-accordion-content yrm-hide-content">
				<div class="row row-static-margin-bottom">
					<div class="col-xs-5">
						<label class="control-label" for="hidden-content-font-size"><?php _e('font size', YRM_LANG);?>:</label>
					</div>
					<div class="col-xs-3">
						<input type="number" class="input-md form-control js-hidden-content-font-size" id="hidden-content-font-size" name="hidden-content-font-size" value="<?php echo esc_attr($dataObj->getOptionValue('hidden-content-font-size'))?>"><br>
					</div>
					<div class="col-xs-1">
						<label class="control-label"><?php _e('Px', YRM_LANG);?></label>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>