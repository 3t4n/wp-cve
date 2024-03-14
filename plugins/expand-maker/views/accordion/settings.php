<?php
	$params = ReadMoreData::params();
	$allowedTag = ReadMoreAdminHelper::getAllowedTags();
?>
<div class="panel panel-default">
	<div class="panel-heading"><?php _e('Settings', YRM_LANG);?></div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-6"><label><?php _e('Accordion Mode on Load', YRM_LANG);?></label></div>
			<div class="col-md-6">
				<?php
					$multipleChoiceButton = new ExpmMultipleChoiceButton($params['accordionModes'], $this->getOptionValue('yrm-accordion-mode'));
					echo wp_kses($multipleChoiceButton, $allowedTag);
				?>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-6">
				<label><?php _e('Activate event', YRM_LANG);?></label>
			</div>
			<div class="col-md-6">
				<?php
					$selectbox = ReadMoreFunctions::yrmSelectBox($params['activateEvent'], esc_attr($this->getOptionValue('yrm-accordion-activate-event')), array('name' => 'yrm-accordion-activate-event', 'class' => 'yrm-js-select2'));
					echo wp_kses($selectbox, $allowedTag);
				?>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-6">
				<label><?php _e('Keep expanded others', YRM_LANG);?></label>
			</div>
			<div class="col-md-6">
				<?php
				$selectbox = ReadMoreFunctions::yrmSelectBox(array('true' => 'Yes', 'false' => 'No'), esc_attr($this->getOptionValue('yrm-accordion-keep-extended')), array('name' => 'yrm-accordion-keep-extended', 'class' => 'yrm-js-select2'));
				echo wp_kses($selectbox, $allowedTag);
				?>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-xs-6">
				<label class="control-label" for="textinput"><?php _e('Animation Behavior', YRM_LANG);?></label>
			</div>
			<div class="col-xs-6">
				<?php echo ReadMoreFunctions::yrmSelectBox($params['easings'], esc_attr($this->getOptionValue('yrm-accordion-animate-easings')), array('name' => 'yrm-accordion-animate-easings', 'class' => 'yrm-js-select2 yrm-animate-easings'));?><br>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-xs-6">
				<label class="control-label" for="yrm-accordion-animate-duration"><?php _e('Animation Duration', YRM_LANG);?></label>
			</div>
			<div class="col-xs-5">
				<input type="text" class="form-control yrm-button-title" id="yrm-accordion-animate-duration" name="yrm-accordion-animate-duration" value="<?php echo esc_attr($this->getOptionValue('yrm-accordion-animate-duration')); ?>">
			</div>
			<div class="col-xs-1">
				MS
			</div>
		</div>
		<div class="row form-group">
			<div class="col-xs-6">
				<label class="control-label" for="yrm-accordion-icons"><?php _e('Open/Close icons', YRM_LANG);?></label>
			</div>
			<div class="col-xs-5">
				<?php
					$icon = $this->getOptionValue('yrm-accordion-icons');
					list($openClass, $closeClass) = explode("_", $icon);
				?>
				<?php echo ReadMoreFunctions::yrmSelectBox($params['accordionOpenCloseIcons'], esc_attr($icon), array('name' => 'yrm-accordion-icons', 'class' => 'yrm-js-select2 yrm-accordion-icons'));?><br>
			</div>
			<div class="col-xs-1 yrm-accordion-preview">
				<span class="icons-preview-span"><?php _e('Preview') ?></span>
				<div class="icon-open-wrapper">
					<i class="fa <?php echo  esc_attr($openClass); ?>"></i>
				</div>
				<hr>
				<div class="icon-close-wrapper">
					<i class="fa <?php echo  esc_attr($closeClass); ?>"></i>
				</div>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-xs-6">
				<label class="control-label" for="yrm-accordion-icons"><?php _e('Icons size', YRM_LANG);?></label>
			</div>
			<div class="col-xs-6">
				<input name="yrm-accordion-icons-size" class="form-control" id="form-control" value="<?php esc_attr_e($this->getOptionValue('yrm-accordion-icons-size'));?>">
			</div>
		</div>
		<div class="row form-group">
			<div class="col-xs-6">
				<label class="control-label" for="yrm-accordion-icons"><?php _e('Icons position', YRM_LANG);?></label>
			</div>
			<div class="col-xs-6">
				<?php echo ReadMoreFunctions::yrmSelectBox(array('left' => 'Left', 'right' => 'Right', 'hide' => 'Hide'), esc_attr($this->getOptionValue('yrm-accordion-icons-position')), array('name' => 'yrm-accordion-icons-position', 'class' => 'yrm-js-select2 yrm-accordion-icons'));?>
			</div>
		</div>
		<div class="row row-static-margin-bottom">
			<div class="col-xs-6">
				<label class="control-label" for="textinput"><?php _e('Cursor', YRM_LANG);?>:</label>
			</div>
			<div class="col-xs-6">
				<?php echo ReadMoreFunctions::yrmSelectBox($params['cursor'], esc_attr($this->getOptionValue('yrm-accordion-cursor')), array('name' => 'yrm-accordion-cursor', 'class' => 'yrm-js-select2 yrm-accordion-icons'));?><br>
			</div>
			<div class="col-md-2 expm-option-info"></div>
		</div>
		<div class="row form-group">
			<div class="col-md-6">
				<label for="yrm-accordion-enable-max-height"><?php _e('Enable Fixed Content Height', YRM_LANG);?></label>
			</div>
			<div class="col-md-6">
				<div class="yrm-switch-wrapper">
					<label class="yrm-switch">
						<input type="checkbox" name="yrm-accordion-enable-max-height" id="yrm-accordion-enable-max-height" class="yrm-accordion-checkbox" <?php echo esc_attr($this->getOptionValue('yrm-accordion-enable-max-height', true)); ?>>
						<span class="yrm-slider yrm-round"></span>
					</label>
				</div>
			</div>
		</div>
		<div class="yrm-accordion-content yrm-hide-content">
			<div class="row form-group">
				<div class="col-xs-6">
					<label class="control-label" for="yrm-accordion-max-height"><?php _e('Max Height', YRM_LANG);?></label>
				</div>
				<div class="col-xs-6">
					<input type="text" class="form-control yrm-button-title" id="yrm-accordion-max-height" name="yrm-accordion-max-height" value="<?php echo esc_attr($this->getOptionValue('yrm-accordion-max-height')); ?>">
				</div>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-6">
				<label for="yrm-accordion-scroll-to-active-item"><?php _e('Scroll to Active Item', YRM_LANG);?></label>
			</div>
			<div class="col-md-6">
				<div class="yrm-switch-wrapper">
					<label class="yrm-switch">
						<input type="checkbox" name="yrm-accordion-scroll-to-active-item" id="yrm-accordion-scroll-to-active-item" <?php echo esc_attr($this->getOptionValue('yrm-accordion-scroll-to-active-item', true)); ?>>
						<span class="yrm-slider yrm-round"></span>
					</label>
				</div>
			</div>
		</div>
        <div class="row form-group">
            <div class="col-md-6">
                <label for="yrm-accordion-before-content"><?php _e('Before Accordion Content', YRM_LANG);?></label>
            </div>
            <div class="col-md-6 accordion-editor-wrapper">
                <?php
                    $editorId = 'yrm-accordion-before-content';
                    $content = $this->getOptionValue('yrm-accordion-before-content');
                    $settings = array(
                        'wpautop' => false,
                        'tinymce' => array(
                            'width' => '100%'
                        ),
                        'textarea_rows' => '18',
                        'media_buttons' => true
                    );
                    wp_editor($content, htmlspecialchars($editorId), $settings);
                ?>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-6">
                <label for="yrm-accordion-after-content"><?php _e('After Accordion Content', YRM_LANG);?></label>
            </div>
            <div class="col-md-6 accordion-editor-wrapper">
                <?php
                    $editorId = 'yrm-accordion-after-content';
                    $content = $this->getOptionValue('yrm-accordion-after-content');
                    $settings = array(
                        'wpautop' => false,
                        'tinymce' => array(
                            'width' => '100%'
                        ),
                        'textarea_rows' => '18',
                        'media_buttons' => true
                    );
                    wp_editor($content, htmlspecialchars($editorId), $settings);
                ?>
            </div>
        </div>
	</div>
</div>