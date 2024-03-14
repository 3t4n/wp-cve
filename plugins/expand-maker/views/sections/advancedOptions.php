<?php
$savedIcon = $savedObj->getOptionValue('yrm-button-icon');
$hideRemoveButton = 'yrm-hide';
if($savedIcon != YRM_BUTTON_ICON_URL) {
    $hideRemoveButton = '';
}
$allowedTag = ReadMoreAdminHelper::getAllowedTags();
?>
<div class="panel panel-default">
	<div class="panel-heading">
		<?php _e('Advanced options', YRM_LANG);?>
		<span class="yrm-tab-triangle glyphicon glyphicon-triangle-top"></span>
	</div>
	<div class="panel-body yrm-pro-options-wrapper">
		<?php if(YRM_PKG == YRM_FREE_PKG) :?>
			<div class="yrm-upgrade-text-wrapper yrm-upgrade-advanced-text-wrapper">
				<h3 class="yrm-pro-info-headline"><?php _e('Upgrade Advanced options in PRO Version', YRM_LANG)?></h3>
				<?php echo ReadMoreAdminHelper::upgradeButton('<b class="h2">Upgrade Now</b>'); ?>
			</div>
			<div class="yrm-pro-options"></div>
		<?php endif;?>
        <div class="row row-static-margin-bottom">
            <div class="col-xs-5">
                <label class="control-label-checkbox" for="load-data-after-action"><?php _e('Load Hidden Data After Page Load', YRM_LANG);?>:</label>
            </div>
            <div class="col-xs-4">
                <div class="yrm-switch-wrapper">
                    <label class="yrm-switch">
                        <input type="checkbox" name="load-data-after-action" id="load-data-after-action" class="yrm-accordion-checkbox" <?php echo esc_attr($savedObj->getOptionValue('load-data-after-action', true)); ?>>
                        <span class="yrm-slider yrm-round"></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="yrm-accordion-content yrm-hide-content">
            <div class="yrm-dimensions-mode yrm-multichoice-wrapper">
                <div class="row row-static-margin-bottom">
                    <div class="col-xs-5">
                        <label class="control-label"><?php _e('Delay', YRM_LANG);?>:</label>
                    </div>
                    <div class="col-xs-2">
                        <input type="number" class="form-control" id="load-data-delay" name="load-data-delay" value="<?php echo esc_attr($savedObj->getOptionValue('load-data-delay'))?>">
                    </div>
                    <div class="col-xs-2"><label class="control-label"><?php echo __('Sec', YRM_LANG); ?></label></div>
                </div>
		        <?php
		        $multipleChoiceButton = new ExpmMultipleChoiceButton($params['hiddenDataLoadMode'], $savedObj->getOptionValue('yrm-hidden-data-load-mode'));

		        ?>
            </div>
            <div id="after-page-load-section" class="yrm-hide-content yrm-sub-option">
<!--                <div class="row row-static-margin-bottom">-->
<!--                    <div class="col-xs-5">-->
<!--                        <label class="control-label">--><?php //_e('Delay', YRM_LANG);?><!--:</label>-->
<!--                    </div>-->
<!--                    <div class="col-xs-2">-->
<!--                        <input type="number" class="form-control" id="load-data-delay" name="load-data-delay" value="--><?php //echo esc_attr($savedObj->getOptionValue('load-data-delay'))?><!--">-->
<!--                    </div>-->
<!--                    <div class="col-xs-2"><label class="control-label">--><?php //echo __('Sec', YRM_LANG); ?><!--</label></div>-->
<!--                </div>-->
            </div>
        </div>
        
        <div class="row row-static-margin-bottom">
            <div class="col-xs-5">
                <label class="control-label" for="textinput"><?php _e('Button', YRM_LANG);?>:</label>
            </div>
            <div class="col-xs-4">
            </div>
        </div>
        <div class="yrm-sub-tab">
		<?php if(!ReadMore::RemoveOption('btn-background-color')): ?>
		<div class="row row-static-margin-bottom">
			<div class="col-xs-5">
				<label class="control-label" for="textinput"><?php _e('Background Color', YRM_LANG);?></label>
			</div>
			<div class="col-xs-4">
				<input type="text" class="input-md background-color color-picker" name="btn-background-color" value="<?php echo esc_attr($btnBackgroundColor) ?>"><br>
			</div>
		</div>
		<?php endif; ?>
		<div class="row row-static-margin-bottom">
			<div class="col-xs-5">
				<label class="control-label" for="textinput"><?php _e('Text Color', YRM_LANG);?></label>
			</div>
			<div class="col-xs-4">
				<input type="text" class="input-md btn-text-color" name="btn-text-color" value="<?php echo esc_attr($btnTextColor)?>"><br>
			</div>
		</div>
		<div class="row row-static-margin-bottom">
			<div class="col-xs-5">
				<label class="control-label" for="textinput"><?php _e('Font Family', YRM_LANG);?></label>
			</div>
			<div class="col-xs-4">
				<?php echo wp_kses($functions::createSelectBox($params['googleFonts'],"expander-font-family", esc_attr($expanderFontFamily)), $allowedTag);?><br>
			</div>
		</div>
		<div class="yrm-accordion-content yrm-hide-content">
			<div class="row">
				<div class="col-xs-5">
					<label class="control-label" for="custom-font-family"><?php _e('button custom font family', YRM_LANG);?>:</label>
				</div>
				<div class="col-xs-4">
					<input type="text" id="custom-font-family" class="form-control input-md custom-font-family" name="btn-custom-font-family" value="<?php echo esc_attr($dataObj->getOptionValue('btn-custom-font-family'))?>"><br>
				</div>
			</div>
		</div>
		<?php if(!ReadMore::RemoveOption('btn-border-radius')): ?>
		<div class="row">
			<div class="col-xs-5">
				<label class="control-label" for="btn-border-radius"><?php _e('Border Radius', YRM_LANG);?></label>
			</div>
			<div class="col-xs-4">
				<input type="text" id="btn-border-radius" class="form-control input-md btn-border-radius" name="btn-border-radius" value="<?php echo esc_attr($btnBorderRadius)?>"><br>
			</div>
		</div>
		<?php endif; ?>
		<div class="row row-static-margin-bottom">
			<div class="col-xs-5">
				<label class="control-label" for="textinput"><?php _e('Horizontal alignment', YRM_LANG);?>:</label>
			</div>
			<div class="col-xs-4">
				<?php echo wp_kses($functions::createSelectBox($params['horizontalAlign'],"horizontal", esc_attr($horizontal)), $allowedTag);?><br>
			</div>
		</div>
		<div class="row row-static-margin-bottom">
			<div class="col-xs-5">
				<label class="control-label" for="textinput"><?php _e('Vertical alignment', YRM_LANG);?></label>
			</div>
			<div class="col-xs-4">
				<?php echo wp_kses($functions::createSelectBox($params['vertical'],"vertical", esc_attr($vertical)), $allowedTag);?><br>
			</div>
		</div>
		<?php if(!ReadMore::RemoveOption('button-border')): ?>
			<div class="row row-static-margin-bottom">
				<div class="col-xs-5">
					<label class="control-label" for="button-border"><?php _e('Border', YRM_LANG);?></label>
				</div>
				<div class="col-xs-4">
                    <div class="yrm-switch-wrapper">
                        <label class="yrm-switch">
                            <input type="checkbox" name="button-border" id="button-border" class="yrm-accordion-checkbox" <?php echo esc_attr($savedObj->getOptionValue('button-border', true)); ?>>
                            <span class="yrm-slider yrm-round"></span>
                        </label>
                    </div>
				</div>
			</div>
			<div class="yrm-accordion-content yrm-hide-content">
				<div class="row row-static-margin-bottom">
					<div class="col-xs-5">
						<label class="control-label" for="button-border-width"><?php _e('border width', YRM_LANG);?>:</label>
					</div>
					<div class="col-xs-4">
						<input type="text" id="button-border-width" name="button-border-width" class="yrm-button-border-width form-control" value="<?php echo esc_attr($savedObj->getOptionValue('button-border-width'));?>">
					</div>
				</div>
				<div class="row">
					<div class="col-xs-5">
						<label class="control-label" for="button-border-color"><?php _e('border color', YRM_LANG);?>:</label>
					</div>
					<div class="col-xs-4">
						<input type="text" class="button-border-color" name="button-border-color" value="<?php echo esc_attr($savedObj->getOptionValue('button-border-color'))?>"><br>
					</div>
				</div>
			</div>
		<?php endif; ?>
        <?php if(!ReadMore::RemoveOption('button-border-bottom')): ?>
			<div class="row row-static-margin-bottom">
				<div class="col-xs-5">
					<label class="control-label" for="yrm-button-border-bottom"><?php _e('Enable Border Bottom', YRM_LANG);?></label>
				</div>
				<div class="col-xs-4">
                    <div class="yrm-switch-wrapper">
                        <label class="yrm-switch">
                            <input type="checkbox" name="yrm-button-border-bottom" id="yrm-button-border-bottom" class="yrm-accordion-checkbox" <?php echo esc_attr($savedObj->getOptionValue('yrm-button-border-bottom', true)); ?>>
                            <span class="yrm-slider yrm-round"></span>
                        </label>
                    </div>
				</div>
			</div>
			<div class="yrm-accordion-content yrm-hide-content">
				<div class="row row-static-margin-bottom">
					<div class="col-xs-5">
						<label class="control-label" for="yrm-button-bottom-border-width"><?php _e('border width', YRM_LANG);?>:</label>
					</div>
					<div class="col-xs-4">
						<input type="text" id="yrm-button-bottom-border-width" name="yrm-button-bottom-border-width" placeholder="<?php _e('Border width', YRM_LANG);?>" class="yrm-button-bottom-border-width form-control" value="<?php echo esc_attr($savedObj->getOptionValue('yrm-button-bottom-border-width'));?>">
					</div>
				</div>
				<div class="row row-static-margin-bottom">
					<div class="col-xs-5">
						<label class="control-label" for=""><?php _e('border style', YRM_LANG);?></label>
					</div>
					<div class="col-xs-4">
						<?php echo wp_kses($functions::createSelectBox($params['border-style'], 'yrm-button-bottom-border-style', esc_attr($savedObj->getOptionValue('yrm-button-bottom-border-style'))), $allowedTag);?><br>
					</div>
				</div>
				<div class="row row-static-margin-bottom">
					<div class="col-xs-5">
						<label class="control-label" for="yrm-button-bottom-border-color"><?php _e('border color', YRM_LANG);?>:</label>
					</div>
					<div class="col-xs-4">
						<input type="text" class="yrm-button-bottom-border-color wp-color-picker" name="yrm-button-bottom-border-color" value="<?php echo esc_attr($savedObj->getOptionValue('yrm-button-bottom-border-color'))?>"><br>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<?php if(!ReadMore::RemoveOption('button-box-shadow')): ?>
			<div class="row row-static-margin-bottom">
				<div class="col-xs-5">
					<label class="control-label" for="button-box-shadow"><?php _e('Box Shadow', YRM_LANG);?></label>
				</div>
				<div class="col-xs-4">
                    <div class="yrm-switch-wrapper">
                        <label class="yrm-switch">
                            <input type="checkbox" name="button-box-shadow" id="button-box-shadow" class="yrm-accordion-checkbox" <?php echo esc_attr($savedObj->getOptionValue('button-box-shadow', true)); ?>>
                            <span class="yrm-slider yrm-round"></span>
                        </label>
                    </div>
				</div>
			</div>
			<div class="yrm-accordion-content yrm-hide-content">
				<div class="row row-static-margin-bottom">
					<div class="col-xs-5">
						<label class="control-label" for="button-box-shadow-horizontal"><?php _e('Horizontal Length', YRM_LANG);?>:</label>
					</div>
					<div class="col-xs-4">
						<input type="number" class="input-md form-control" id="button-box-shadow-horizontal" placeholder="example 5 or -5" name="button-box-shadow-horizontal-length" value="<?php echo esc_attr($savedObj->getOptionValue('button-box-shadow-horizontal-length'))?>"><br>
					</div>
					<div class="col-xs-1">
						<label class="control-label"><?php _e('px', YRM_LANG);?></label>
					</div>
				</div>
				<div class="row row-static-margin-bottom">
					<div class="col-xs-5">
						<label class="control-label" for="button-box-shadow-vertical"><?php _e('Vertical Length', YRM_LANG);?>:</label>
					</div>
					<div class="col-xs-4">
						<input type="number" class="input-md form-control" placeholder="example 5 or -5" id="button-box-shadow-vertical" name="button-box-shadow-vertical-length" value="<?php echo esc_attr($savedObj->getOptionValue('button-box-shadow-vertical-length'))?>"><br>
					</div>
					<div class="col-xs-1">
						<label class="control-label"><?php _e('px', YRM_LANG);?></label>
					</div>
				</div>
				<div class="row row-static-margin-bottom">
					<div class="col-xs-5">
						<label class="control-label" for="button-box-blur-radius"><?php _e('Blur Radius', YRM_LANG);?>:</label>
					</div>
					<div class="col-xs-4">
						<input type="number" class="input-md form-control" placeholder="example 5 or -5" id="button-box-blur-radius" name="button-box-blur-radius" value="<?php echo esc_attr($savedObj->getOptionValue('button-box-blur-radius'))?>"><br>
					</div>
					<div class="col-xs-1">
						<label class="control-label"><?php _e('px', YRM_LANG);?></label>
					</div>
				</div>
				<div class="row row-static-margin-bottom">
					<div class="col-xs-5">
						<label class="control-label" for="button-box-spread-radius"><?php _e('Spread Radius', YRM_LANG);?>:</label>
					</div>
					<div class="col-xs-4">
						<input type="number" class="input-md form-control" placeholder="example 5 or -5" id="button-box-spread-radius" name="button-box-spread-radius" value="<?php echo esc_attr($savedObj->getOptionValue('button-box-spread-radius'))?>"><br>
					</div>
					<div class="col-xs-1">
						<label class="control-label"><?php _e('px', YRM_LANG);?></label>
					</div>
				</div>
				<div class="row row-static-margin-bottom">
					<div class="col-xs-5">
						<label class="control-label" for="button-box-shadow-color"><?php _e('Shadow Color', YRM_LANG);?>:</label>
					</div>
					<div class="col-xs-4">
						<input type="text" class="input-md" id="button-box-shadow-color" name="button-box-shadow-color" value="<?php echo esc_attr($savedObj->getOptionValue('button-box-shadow-color'))?>"><br>
					</div>
				</div>
			</div>
		<?php endif; ?>
        <!-- Start button icon -->
        <div class="row row-static-margin-bottom">
            <div class="col-xs-5">
                <label class="control-label-checkbox control-label" for="enable-button-icon"><?php _e('Enable Button Icon', YRM_LANG);?></label>
            </div>
            <div class="col-xs-4">
                <div class="yrm-switch-wrapper">
                    <label class="yrm-switch">
                        <input type="checkbox" name="enable-button-icon" id="enable-button-icon" class="yrm-accordion-checkbox" <?php echo esc_attr($savedObj->getOptionValue('enable-button-icon', true)); ?>>
                        <span class="yrm-slider yrm-round"></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="yrm-accordion-content yrm-hide-content">
            <div class="row row-static-margin-bottom">
                <div class="col-xs-5">
                    <label class="control-label-checkbox" for="arrow-icon-width"><?php _e('Icon width', YRM_LANG);?>:</label>
                </div>
                <div class="col-xs-3">
                    <input type="text" id="arrow-icon-width" class="form-control" name="arrow-icon-width" value="<?php echo esc_attr($savedObj->getOptionValue('arrow-icon-width')); ?>">
                </div>
            </div>
            <div class="row row-static-margin-bottom">
                <div class="col-xs-5">
                    <label class="control-label-checkbox" for="arrow-icon-height"><?php _e('Icon height', YRM_LANG);?>:</label>
                </div>
                <div class="col-xs-3">
                    <input type="text" id="arrow-icon-height" class="form-control" name="arrow-icon-height" value="<?php echo esc_attr($savedObj->getOptionValue('arrow-icon-height')); ?>">
                </div>
            </div>
	        <div class="row row-static-margin-bottom">
		        <div class="col-xs-5">
			        <label class="control-label-checkbox" for="arrow-icon-alignment"><?php _e('Icon alignment', YRM_LANG);?>:</label>
		        </div>
		        <div class="col-xs-3">
			        <?php echo wp_kses($functions::yrmSelectBox($params['arrowIconAlignment'], esc_attr($savedObj->getOptionValue('arrow-icon-alignment')), array('name'=>"arrow-icon-alignment", 'class'=>'yrm-js-select2', 'id'=>'arrow-icon-alignment')), $allowedTag);?>
		        </div>
	        </div>
            <div class="row row-static-margin-bottom">
                <div class="col-xs-5">
                    <label class="control-label-checkbox" for=""><?php _e('Button image', YRM_LANG);?>:</label>
                </div>
                <div class="col-xs-1">
                    <input type="hidden" id="yrm-button-icon" name="yrm-button-icon" data-default-url="<?php echo YRM_BUTTON_ICON_URL; ?>" value="<?php echo esc_attr($savedObj->getOptionValue('yrm-button-icon')); ?>">
                    <div class="yrm-icon-container-preview" style="background-image: url(<?php echo esc_attr($savedObj->getOptionValue('yrm-button-icon')); ?>)"></div>
                </div>
                <div class="col-xs-2">
                    <input id="js-button-upload-image-button" class="btn btn-sm btn-default" type="button" value="<?php _e('Change image'); ?>">
                </div>
                <div class="col-xs-1 yrm-remove-changed-image-wrapper <?php echo esc_attr($hideRemoveButton); ?>">
                    <input id="js-button-upload-image-remove-button" class="btn btn-sm btn-danger" type="button" value="<?php _e('Remove'); ?>">
                </div>
            </div>
        </div>
        <!-- End button icon -->
        <!-- Start button hover -->
        <div class="row row-static-margin-bottom">
            <div class="col-xs-5">
                <label class="control-label-checkbox control-label" for="hover-effect"><?php _e('Hover Effect', YRM_LANG);?></label>
            </div>
            <div class="col-xs-4">
                <div class="yrm-switch-wrapper">
                    <label class="yrm-switch">
                        <input type="checkbox" name="hover-effect" id="hover-effect" class="yrm-accordion-checkbox" <?php echo esc_attr($hoverEffect); ?>>
                        <span class="yrm-slider yrm-round"></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="yrm-accordion-content yrm-hide-content">
            <div class="row">
                <div class="col-xs-5">
                    <label class="control-label" for="btn-hover-text-color"><?php _e('button color', YRM_LANG);?>:</label>
                </div>
                <div class="col-xs-5">
                    <input type="text" id="btn-hover-text-color" class="input-md btn-hover-color" name="btn-hover-text-color" value="<?php echo esc_attr($btnHoverTextColor)?>" >
                </div>
            </div>
            <?php if(!ReadMore::RemoveOption('btn-hover-bg-color')): ?>
                <div class="row">
                    <div class="col-xs-5">
                        <label class="control-label" for="textinput"><?php _e('button bg color', YRM_LANG);?>:</label>
                    </div>
                    <div class="col-xs-5">
                        <input type="text" class="input-md btn-hover-color" name="btn-hover-bg-color" value="<?php echo esc_attr($btnHoverBgColor)?>" >
                    </div>
                </div>
            <?php endif; ?>
        </div>
        </div>
        <div class="row row-static-margin-bottom">
            <div class="col-xs-5">
                <label class="control-label-checkbox" for="auto-open"><?php _e('Auto Open', YRM_LANG);?>:</label>
            </div>
            <div class="col-xs-4">
                <div class="yrm-switch-wrapper">
                    <label class="yrm-switch">
                        <input type="checkbox" name="auto-open" id="auto-open" class="yrm-accordion-checkbox" <?php echo esc_attr($savedObj->getOptionValue('auto-open', true)); ?>>
                        <span class="yrm-slider yrm-round"></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="yrm-accordion-content yrm-hide-content">
            <div class="row row-static-margin-bottom">
                <div class="col-xs-5">
                    <label class="control-label-checkbox" for="auto-open-delay"><?php _e('delay', YRM_LANG);?>:</label>
                </div>
                <div class="col-xs-4">
                    <input type="number" class="form-control" id="auto-open-delay" name="auto-open-delay" value="<?php echo esc_attr($savedObj->getOptionValue('auto-open-delay'))?>">
                </div>
                <div class="col-xs-2"><?php echo __('Sec', YRM_LANG); ?></div>
            </div>
        </div>
		<div class="row row-static-margin-bottom">
			<div class="col-xs-5">
				<label class="control-label-checkbox" for="auto-close"><?php _e('Auto Close', YRM_LANG);?>:</label>
			</div>
			<div class="col-xs-4">
				<div class="yrm-switch-wrapper">
					<label class="yrm-switch">
						<input type="checkbox" name="auto-close" id="auto-close" class="yrm-accordion-checkbox" <?php echo esc_attr($savedObj->getOptionValue('auto-close', true)); ?>>
						<span class="yrm-slider yrm-round"></span>
					</label>
				</div>
			</div>
		</div>
		<div class="yrm-accordion-content yrm-hide-content">
			<div class="row row-static-margin-bottom">
				<div class="col-xs-5">
					<label class="control-label-checkbox" for="auto-close-delay"><?php _e('delay', YRM_LANG);?>:</label>
				</div>
				<div class="col-xs-4">
					<input type="number" class="form-control" id="auto-close-delay" name="auto-close-delay" value="<?php echo esc_attr($savedObj->getOptionValue('auto-close-delay'))?>">
				</div>
				<div class="col-xs-2"><?php echo __('Sec', YRM_LANG); ?></div>
			</div>
		</div>

        <div class="row row-static-margin-bottom">
			<div class="col-xs-5">
				<label class="control-label-checkbox" for="show-only-devices"><?php _e('Show On Selected Devices', YRM_LANG);?>:</label>
			</div>
			<div class="col-xs-4">
                <div class="yrm-switch-wrapper">
                    <label class="yrm-switch">
                        <input type="checkbox" name="show-only-devices" id="show-only-devices" class="yrm-accordion-checkbox" <?php echo esc_attr($showOnlyDevices); ?>>
                        <span class="yrm-slider yrm-round"></span>
                    </label>
                </div>
			</div>
		</div>
		<div class="yrm-accordion-content yrm-hide-content">
			<div class="row row-static-margin-bottom">
				<div class="col-xs-5">
					<label class="control-label-checkbox" for="hover-effect"><?php _e('Select device(s)', YRM_LANG);?>:</label>
				</div>
				<div class="col-xs-4">
					<?php echo wp_kses($functions::yrmSelectBox($params['devices'], $selectedDevices, array('name'=>"yrm-selected-devices[]", 'multiple'=>'multiple', 'class'=>'yrm-js-select2')), $allowedTag);?>
				</div>
			</div>
			<div class="row row-static-margin-bottom">
				<div class="col-xs-5">
					<label class="control-label-checkbox" for="hide-content"><?php _e('hide content if not matched devices', YRM_LANG);?>:</label>
				</div>
				<div class="col-xs-4">
                    <div class="yrm-switch-wrapper">
                        <label class="yrm-switch">
                            <input type="checkbox" name="hide-content" id="hide-content" class="yrm-accordion-checkbox" <?php echo esc_attr($savedObj->getOptionValue('hide-content', true)); ?>>
                            <span class="yrm-slider yrm-round"></span>
                        </label>
                    </div>
				</div>
			</div>
		</div>
        <?php if(!ReadMore::RemoveOption('button-for-post')): ?>
		<div class="row row-static-margin-bottom">
			<div class="col-xs-5">
				<label class="control-label-checkbox" for="button-for-post"><?php _e('Add Button For Posts', YRM_LANG);?>:</label>
			</div>
			<div class="col-xs-4">
                <div class="yrm-switch-wrapper">
                    <label class="yrm-switch">
                        <input type="checkbox" name="button-for-post" id="button-for-post" class="yrm-accordion-checkbox" <?php echo esc_attr($buttonForPost); ?>>
                        <span class="yrm-slider yrm-round"></span>
                    </label>
                </div>
			</div>
		</div>
		<div class="yrm-accordion-content yrm-hide-content yrm-multichoice-wrapper">
			<?php
				$multipleChoiceButton = new ExpmMultipleChoiceButton($params['buttonForPost'], $savedObj->getOptionValue('yrm-button-for-post'));
				echo wp_kses($multipleChoiceButton, $allowedTag);
			?>
			<div id="botton-for-selected-posts" class="yrm-hide-content yrm-sub-option">
				<div class="row">
					<div class="col-xs-5">
						<label class="control-label" for="textinput"><?php _e('Selected post', YRM_LANG);?>:</label>
					</div>
					<div class="col-xs-5">
						<?php echo wp_kses($functions::yrmSelectBox($params['selectedPost'],$yrmSelectedPost, array('name'=>"yrm-selected-post[]", 'multiple'=>'multiple','size'=>10,'class' => 'yrm-js-select2')), $allowedTag);?><br>
					</div>
				</div>
			</div>
			<div class="row" style="margin-top: 5px">
				<div class="col-xs-5">
					<label class="control-label" for="hide-after-word-count" for="textinput"><?php _e('Hide after word count', YRM_LANG);?>:</label>
				</div>
				<div class="col-xs-5">
					<input type="text" id="hide-after-word-count" class="form-control input-md btn-border-radius" name="hide-after-word-count" value="<?php echo esc_attr($hideAfterWordCount)?>"><br>
				</div>
			</div>
		 </div>
        <?php endif; ?>
	</div>
</div>