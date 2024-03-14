<?php
$allowedTag = ReadMoreAdminHelper::getAllowedTags();
?>
<div class="row">
	<div class="col-xs-5">
		<label class="control-label" for="addButtonOfTheNext"><?php _e('Add Button To The Next Of The Text', YRM_LANG);?>:</label>
	</div>
	<div class="col-xs-4">
		<div class="yrm-switch-wrapper">
			<label class="yrm-switch">
				<input type="checkbox" name="add-button-next-content" id="addButtonOfTheNext" class="" <?php echo esc_attr($savedObj->getOptionValue('add-button-next-content', true)); ?>>
				<span class="yrm-slider yrm-round"></span>
			</label>
		</div>
	</div>
	<div class="col-md-2 expm-option-info"></div>
</div>
<!-- Text decoration -->
<div class="row row-static-margin-bottom">
	<div class="col-xs-5">
		<label class="control-label" for="yrm-enable-decoration"><?php _e('Enable Text Decoration', YRM_LANG);?>:</label>
	</div>
	<div class="col-xs-4">
		<div class="yrm-switch-wrapper">
			<label class="yrm-switch">
				<input type="checkbox" name="yrm-enable-decoration" id="yrm-enable-decoration" class="yrm-accordion-checkbox" <?php echo esc_attr($savedObj->getOptionValue('yrm-enable-decoration', true)); ?>>
				<span class="yrm-slider yrm-round"></span>
			</label>
		</div>
	</div>
</div>
<div class="yrm-accordion-content">
	<div class="row row-static-margin-bottom">
		<div class="col-xs-5">
			<label class="control-label" for="yrm-decoration-type"><?php _e('Line type', YRM_LANG);?>:</label>
		</div>
		<div class="col-xs-4">
			<?php echo wp_kses($functions::yrmSelectBox($params['textDecorationType'], esc_attr($savedObj->getOptionValue('yrm-decoration-type')), array('name' => 'yrm-decoration-type', 'class' => 'yrm-js-select2 yrm-decoration-type')), $allowedTag);?><br>
		</div>
	</div>
	<div class="row row-static-margin-bottom">
		<div class="col-xs-5">
			<label class="control-label" for="yrm-decoration-style"><?php _e('Line Style', YRM_LANG);?>:</label>
		</div>
		<div class="col-xs-4">
			<?php echo wp_kses($functions::yrmSelectBox($params['textDecorationStyle'], esc_attr($savedObj->getOptionValue('yrm-decoration-style')), array('name' => 'yrm-decoration-style', 'class' => 'yrm-js-select2 yrm-decoration-style')), $allowedTag);?><br>
		</div>
	</div>
	<div class="row row-static-margin-bottom <?php echo esc_attr($proClassWrapper); ?>">
		<?php if($proClassWrapper): ?>
			<?php echo wp_kses(ReadMoreAdminHelper::proOptionHTML(), $allowedTag); ?>
		<?php endif; ?>
		<div class="col-xs-5">
			<label class="control-label" for="yrm-decoration-color"><?php _e('Line Style', YRM_LANG);?>:</label>
		</div>
		<div class="col-xs-4">
			<input type="text" class="input-md yrm-decoration-color minicolors-input" name="yrm-decoration-color" value="<?php echo esc_attr($savedObj->getOptionValue('yrm-decoration-color')); ?>"><br>
		</div>
	</div>
</div>