<?php
$allowedTag = ReadMoreAdminHelper::getAllowedTags();
?>
<div class="panel panel-default">
	<div class="panel-heading">
		<?php _e('General options', YRM_LANG);?>
		<span class="yrm-tab-triangle glyphicon glyphicon-triangle-top"></span>
	</div>
	<div class="panel-body">
		<?php if(!ReadMore::RemoveOption('btn-dimension-mode')): ?>
			<div class="yrm-dimensions-mode yrm-multichoice-wrapper">
				<?php
					$multipleChoiceButton = new ExpmMultipleChoiceButton($params['dimensionsMode'], $savedObj->getOptionValue('yrm-dimension-mode'));
					echo wp_kses($multipleChoiceButton, $allowedTag);
				?>
			</div>
		<?php endif; ?>
		<div id="dimension-mode-auto" class="yrm-hide-content yrm-sub-option">
			<div class="row row-static-margin-bottom">
				<div class="col-xs-4">
					<label class="control-label"><?php _e('Button padding', YRM_LANG);?>:</label>
				</div>
				<div class="col-xs-2">
					<label for="yrm-button-padding-top" class="yrm-label">Top</label>
					<input type="text" id="yrm-button-padding-top" data-direction="top" name="yrm-button-padding-top" class="form-control button-padding" value="<?php echo esc_attr($savedObj->getOptionValue('yrm-button-padding-top'))?>">
				</div>
				<div class="col-xs-2">
					<label for="yrm-button-padding-right" class="yrm-label">Right</label>
					<input type="text" id="yrm-button-padding-right" data-direction="right" name="yrm-button-padding-right" class="form-control button-padding" value="<?php echo esc_attr($savedObj->getOptionValue('yrm-button-padding-right'))?>">
				</div>
				<div class="col-xs-2">
					<label for="yrm-button-padding-bottom" class="yrm-label">Bottom</label>
					<input type="text" id="yrm-button-padding-bottom" data-direction="bottom" name="yrm-button-padding-bottom" class="form-control button-padding" value="<?php echo esc_attr($savedObj->getOptionValue('yrm-button-padding-bottom'))?>">
				</div>
				<div class="col-xs-2">
					<label for="yrm-button-padding-left" class="yrm-label">Left</label>
					<input type="text" id="yrm-button-padding-left" data-direction="left" name="yrm-button-padding-left" class="form-control button-padding" value="<?php echo esc_attr($savedObj->getOptionValue('yrm-button-padding-left'))?>">
				</div>
			</div>
		</div>
		<div id="dimension-mode-classic" class="yrm-hide-content yrm-sub-option">
			<?php if(!ReadMore::RemoveOption('button-width')): ?>
			<div class="row">
				<div class="col-xs-5">
					<label class="control-label" for="expm-btn-width"><?php _e('Button Width', YRM_LANG);?>:</label>
				</div>
				<div class="col-xs-4">
					<input type="text" id="expm-btn-width" class="form-control input-md expm-options-margin expm-btn-width" name="button-width" value="<?php echo esc_attr($buttonWidth);?>"><br>
				</div>
				<div class="col-md-2 expm-option-info">(in pixels)</div>
			</div>
			<?php endif; ?>
			<?php if(!ReadMore::RemoveOption('button-height')): ?>
			<div class="row">
				<div class="col-xs-5">
					<label class="control-label" for="button-height"><?php _e('Button Height', YRM_LANG);?>:</label>
				</div>
				<div class="col-xs-4">
					<input type="text" id="button-height" class="form-control input-md expm-options-margin expm-btn-height" name="button-height" value="<?php echo esc_attr($buttonHeight);?>"><br>
				</div>
				<div class="col-md-2 expm-option-info">(in pixels)</div>
			</div>
		<?php endif; ?>
		</div>
		<div class="row">
			<div class="col-xs-5">
				<label class="control-label" for="font-size"><?php _e('Font Size', YRM_LANG);?>:</label>
			</div>
			<div class="col-xs-4">
				<input type='text' id="font-size" class="form-control input-md expm-option-font-size" name="font-size" value="<?php echo esc_attr($fontSize)?>"><br>
			</div>
			<div class="col-md-2 expm-option-info">(in pixels)</div>
		</div>
		<div class="row row-static-margin-bottom">
			<div class="col-xs-5">
				<label class="control-label" for="textinput"><?php _e('Font Weight', YRM_LANG);?>:</label>
			</div>
			<div class="col-xs-4">
				<?php echo wp_kses($functions::createSelectBox($params['btnFontWeight'],'yrm-btn-font-weight', esc_attr($yrmBtnFontWeight)), $allowedTag);?><br>
			</div>
		</div>
		<?php if(!ReadMore::RemoveOption('animation-duration')): ?>
		<div class="row">
			<div class="col-xs-5">
				<label class="control-label" for="textinput"><?php _e('Animation Speed', YRM_LANG);?>:</label>
			</div>
			<div class="col-xs-4">
			<input type="number" class="form-control input-md  expm-options-margin" name="animation-duration" value="<?php echo esc_attr($animationDuration)?>"><br>
			</div>
			<div class="col-md-2 expm-option-info">(Miliseconds)</div>
		</div>
        <div class="row row-static-margin-bottom">
			<div class="col-xs-5">
				<label class="control-label" for="textinput"><?php _e('Animation Behavior', YRM_LANG);?>:</label>
			</div>
			<div class="col-xs-4">
                <?php echo wp_kses($functions::yrmSelectBox($params['easings'], esc_attr($yrmEasings), array('name' => 'yrm-animate-easings', 'class' => 'yrm-js-select2 yrm-animate-easings')),$allowedTag);?><br>
			</div>
	        <div class="col-xs-1">
		        <span class="yrm-eye-icon yrm-eye-animation-behaviour"></span>
	        </div>
		</div>
		<?php endif; ?>
		<div class="row row-static-margin-bottom">
			<div class="col-xs-5">
				<label class="control-label" for="textinput"><?php _e('Button Hover Effect', YRM_LANG);?>:</label>
			</div>
			<div class="col-xs-4">
				<?php echo wp_kses($functions::createSelectBox($params['hoverEffect'],"yrm-btn-hover-animate", esc_attr($yrmBtnHoverAnimate)), $allowedTag);?><br>
			</div>
			<div class="col-xs-1">
				<span class="yrm-eye-icon yrm-eye-button-hover"></span>
			</div>
		</div>
        <div class="row row-static-margin-bottom">
			<div class="col-xs-5">
				<label class="control-label" for="textinput"><?php _e('Cursor', YRM_LANG);?>:</label>
			</div>
			<div class="col-xs-4">
				<?php echo wp_kses($functions::createSelectBox($params['cursor'],"yrm-cursor", esc_attr($yrmCursor)), $allowedTag);?><br>
			</div>
			<div class="col-md-2 expm-option-info"></div>
		</div>
        <div class="row row-static-margin-bottom">
			<div class="col-xs-5">
				<label class="control-label" for="textinput"><?php _e('More Text', YRM_LANG);?>:
					<?php echo yrm_info('If you remove the more="Read more" attribute from the "read more" shortcode, this will be evident.'); ?>
				</label>
			</div>
			<div class="col-xs-4">
				<input type="text" data-type="more" class="form-control yrm-button-title" name="more-button-title" value="<?php echo esc_attr($savedObj->getOptionValue('more-button-title')); ?>">
			</div>
		</div>
		<div class="row row-static-margin-bottom">
			<div class="col-xs-5">
				<label class="control-label" for="moreTitle"><?php _e('More Title', YRM_LANG);?>:</label>
			</div>
			<div class="col-xs-4">
				<input type="text" id="moreTitle" data-type="more" class="form-control yrm-button-title-text" name="more-title" value="<?php echo esc_attr($savedObj->getOptionValue('more-title')); ?>" placeholder="<?php _e('More Title', YRM_LANG);?>">
			</div>
		</div>
		<?php if(!ReadMore::RemoveOption('less-button-title')): ?>
        <div class="row row-static-margin-bottom">
			<div class="col-xs-5">
				<label class="control-label" for="textinput"><?php _e('Less Text', YRM_LANG);?>:
					<?php echo yrm_info('If you remove the less="Read less" attribute from the "read more" shortcode, this will be evident.'); ?>
				</label>
			</div>
			<div class="col-xs-4">
				<input type="text" data-type="less" class="form-control yrm-button-title" name="less-button-title" value="<?php echo esc_attr($savedObj->getOptionValue('less-button-title')); ?>" placeholder="<?php _e('Less Text', YRM_LANG);?>">
			</div>
		</div>
		<div class="row row-static-margin-bottom">
			<div class="col-xs-5">
				<label class="control-label" for="lessTitle"><?php _e('Less Title', YRM_LANG);?>:</label>
			</div>
			<div class="col-xs-4">
				<input type="text" id="lessTitle" data-type="more" class="form-control yrm-button-title-text" name="less-title" value="<?php echo esc_attr($savedObj->getOptionValue('less-title')); ?>" placeholder="<?php _e('Less Title', YRM_LANG);?>">
			</div>
		</div>
		<?php endif;?>
        <?php if($type == 'inline' || $type == 'inlinePopup'): ?>
			<?php require_once(dirname(__FILE__).'/inlineTypeOptions.php'); ?>
        <?php endif; ?>
        <div class="row form-group">
            <div class="col-md-6 yrm-slider-label-wrapper">
                <label class="ystp-label-of-select yrm-range-slider-wrapper" for="yrm-button-opacity"><?php _e('Opacity', YRM_LANG); ?>:</label>
            </div>
            <div class="col-md-5">
                <input type="text" name="yrm-button-opacity" id="yrm-button-opacity" class="form-control " value="<?php echo esc_attr($savedObj->getOptionValue('yrm-button-opacity')); ?>">
            </div>
        </div>
        <!-- Tooltip option start -->
        <div class="row row-static-margin-bottom">
            <div class="col-xs-5">
                <label class="control-label" for="enable-tooltip"><?php _e('Enable tooltip', YRM_LANG);?>:</label>
            </div>
            <div class="col-xs-4">
                <div class="yrm-switch-wrapper">
                    <label class="yrm-switch">
                        <input type="checkbox" name="enable-tooltip" id="enable-tooltip" class="yrm-accordion-checkbox" <?php echo esc_attr($savedObj->getOptionValue('enable-tooltip', true)); ?>>
                        <span class="yrm-slider yrm-round"></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="yrm-accordion-content">
            <div class="row row-static-margin-bottom">
                <div class="col-xs-5">
                    <label class="control-label" for="enable-tooltip-text"><?php _e('Text', YRM_LANG);?>:</label>
                </div>
                <div class="col-xs-4">
                    <input type="text" class="form-control" name="enable-tooltip-text" id="enable-tooltip-text" value="<?php echo esc_attr($savedObj->getOptionValue('enable-tooltip-text')); ?>">
                </div>
            </div>
        </div>
        <!-- Tooltip option end -->
        <?php if ($type == 'button' || $type == 'inline'): ?>
        <div class="row row-static-margin-bottom">
            <div class="col-xs-5">
                <label class="control-label" for="defaultShowHiddenContent"><?php _e('By Default Show Hidden Cgitontent', YRM_LANG);?>:</label>
            </div>
            <div class="col-xs-4">
                <div class="yrm-switch-wrapper">
                    <label class="yrm-switch">
                        <input type="checkbox" name="default-show-hidden-content" id="defaultShowHiddenContent" class="" <?php echo esc_attr($savedObj->getOptionValue('default-show-hidden-content', true)); ?>>
                        <span class="yrm-slider yrm-round"></span>
                    </label>
                </div>
            </div>
            <div class="col-md-2 expm-option-info"></div>
        </div>
        <?php endif; ?>
        <div class="row row-static-margin-bottom">
            <div class="col-xs-5">
                <label class="control-label" for="hideButtonForBlogPostPage"><?php _e('Hide Button On Blog Post Page', YRM_LANG);?>:</label>
            </div>
            <div class="col-xs-4">
                <div class="yrm-switch-wrapper">
                    <label class="yrm-switch">
                        <input type="checkbox" name="hide-button-blog-post" id="hideButtonForBlogPostPage" class="" <?php echo esc_attr($savedObj->getOptionValue('hide-button-blog-post', true)); ?>>
                        <span class="yrm-slider yrm-round"></span>
                    </label>
                </div>
            </div>
            <div class="col-md-2 expm-option-info"></div>
        </div>
		<?php if(!ReadMore::RemoveOption('hide-button-after-click')): ?>
		<div class="row row-static-margin-bottom">
			<div class="col-xs-5">
				<label class="control-label" for="hideButtonAfterClick"><?php _e('Hide Button After Click More Text', YRM_LANG);?>:</label>
			</div>
			<div class="col-xs-4">
                <div class="yrm-switch-wrapper">
                    <label class="yrm-switch">
                        <input type="checkbox" name="hide-button-after-click" id="hideButtonAfterClick" class="" <?php echo esc_attr($savedObj->getOptionValue('hide-button-after-click', true)); ?>>
                        <span class="yrm-slider yrm-round"></span>
                    </label>
                </div>
			</div>
			<div class="col-md-2 expm-option-info"></div>
		</div>
		<?php endif;?>
		<?php if(!ReadMore::RemoveOption('scroll-to-initial-position')): ?>
        <div class="row row-static-margin-bottom">
			<div class="col-xs-5">
				<label class="control-label" for="scrollToInitialPosition"><?php _e('After "Show Less" Scroll To Initial Position', YRM_LANG);?>:</label>
			</div>
			<div class="col-xs-4">
                <div class="yrm-switch-wrapper">
                    <label class="yrm-switch">
                        <input type="checkbox" name="scroll-to-initial-position" id="scrollToInitialPosition" class="" <?php echo esc_attr($savedObj->getOptionValue('scroll-to-initial-position', true)); ?>>
                        <span class="yrm-slider yrm-round"></span>
                    </label>
                </div>
			</div>
			<div class="col-md-2 expm-option-info"></div>
		</div>
        <?php endif; ?>
        <?php if(!ReadMore::RemoveOption('show-content-gradient')): ?>
        <div class="row row-static-margin-bottom">
			<div class="col-xs-5">
				<label class="control-label" for="showContentGradient"><?php _e('Show Content Gradient', YRM_LANG);?>:</label>
			</div>
			<div class="col-xs-4">
                <div class="yrm-switch-wrapper">
                    <label class="yrm-switch">
                        <input type="checkbox" name="show-content-gradient" id="showContentGradient" class="yrm-accordion-checkbox" <?php echo esc_attr($savedObj->getOptionValue('show-content-gradient', true)); ?>>
                        <span class="yrm-slider yrm-round"></span>
                    </label>
                </div>
			</div>
		</div>
		<div class="yrm-accordion-content">
			<div class="row row-static-margin-bottom">
				<div class="col-xs-5">
					<label class="control-label" for="showContentGradientHeight"><?php _e('Height', YRM_LANG);?>:</label>
				</div>
				<div class="col-xs-4">
					<input type="number" class="form-control" name="show-content-gradient-height" id="showContentGradientHeight" value="<?php echo esc_attr($savedObj->getOptionValue('show-content-gradient-height')); ?>">
				</div>
			</div>
			<div class="row row-static-margin-bottom">
				<div class="col-xs-5">
					<label class="control-label" for="showContentGradientPosition"><?php _e('Position', YRM_LANG);?>:</label>
				</div>
				<div class="col-xs-4">
					<input type="number" class="form-control" name="show-content-gradient-position" id="showContentGradientPosition" value="<?php echo esc_attr($savedObj->getOptionValue('show-content-gradient-position')); ?>">
				</div>
			</div>
			<div class="row row-static-margin-bottom <?php echo esc_attr($proClassWrapper); ?>">
				<?php if($proClassWrapper): ?>
					<?php echo ReadMoreAdminHelper::proOptionHTML(); ?>
				<?php endif; ?>
				<div class="col-xs-5">
					<label class="control-label" for="showContentGradientColor"><?php _e('Background color', YRM_LANG);?>:</label>
				</div>
				<div class="col-xs-4">
					<input type="text" class="input-md show-content-gradient-color" name="show-content-gradient-color" value="<?php echo esc_attr($savedObj->getOptionValue('show-content-gradient-color')); ?>"><br>
				</div>
			</div>
		</div>
		<?php endif; ?>
	</div>
</div>