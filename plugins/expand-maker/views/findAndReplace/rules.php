<?php
$params = ReadMoreData::params();
$isExtensionActive = is_plugin_active(YRM_FAR_PLUGIN_KEY);
$allowedTag = ReadMoreAdminHelper::getAllowedTags();
?>
<div class="panel panel-default">
	<div class="panel-heading">
		<?php _e('Replace Rules', YRM_LANG);?>
		<span class="yrm-tab-triangle glyphicon glyphicon-triangle-top"></span>
	</div>
	<div class="panel-body">
		<div class="row row-static-margin-bottom">
			<div class="col-xs-5">
				<label class="control-label" for="yrm-find-name"><?php _e('Find Word', YRM_LANG);?>:</label>
			</div>
			<div class="col-xs-4">
				<input type="text" class="form-control" placeholder="<?php _e('Find', YRM_LANG);?>" name="yrm-find-name" id="yrm-find-name" value="<?php echo esc_attr($typeObj->getOptionValue('yrm-find-name')); ?>">
			</div>
		</div>
		<div class="row row-static-margin-bottom">
			<div class="col-xs-5">
				<label class="control-label" for="yrm-replace-name"><?php _e('Replace Word', YRM_LANG);?>:</label>
			</div>
			<div class="col-xs-4">
				<?php
					$editorId = 'yrm-replace-name';
					$beforeCountdown = $typeObj->getOptionValue($editorId);
					$settings = array(
						'wpautop' => false,
						'tinymce' => array(
							'width' => '100%'
						),
						'textarea_rows' => '6',
						'media_buttons' => true
					);
					wp_editor($beforeCountdown, $editorId, $settings);
				?>
			</div>
		</div>
       <div class="yrm-far-advanced-wrapper">
            <div class="row">
                <div class="col-xs-5">
                    <label class="control-label" for="yrm-far-enable-selected-devices"><?php _e('Replace On Selected Devices', YRM_LANG);?>:</label>
                </div>
                <div class="col-xs-4">
                    <div class="yrm-switch-wrapper">
                        <label class="yrm-switch">
                            <input type="checkbox" name="yrm-far-enable-selected-devices" id="yrm-far-enable-selected-devices" class="yrm-accordion-checkbox yrm-far-enable-selected-devices" <?php echo esc_attr($typeObj->getOptionValue('yrm-far-enable-selected-devices', true)); ?>>
                            <span class="yrm-slider yrm-round"></span>
                        </label>
                    </div><br>
                </div>
            </div>
            <div class="yrm-accordion-content yrm-hide-content">
                <div class="row row-static-margin-bottom">
                    <div class="col-xs-5">
                        <label class="control-label" for="yrm-replace-name"><?php _e('Select Device(s)', YRM_LANG);?>:</label>
                    </div>
                    <div class="col-xs-4">
                        <?php echo wp_kses($functions::yrmSelectBox($params['devices'], $typeObj->getOptionValue('yrm-far-selected-devices'), array('name'=>"yrm-far-selected-devices[]", 'multiple'=>'multiple', 'class'=>'yrm-js-select2')), $allowedTag);?>
                    </div>
                </div>
            </div>
            <?php require_once(dirname(__FILE__).'/displayRule.php')?>
           <?php if (!$isExtensionActive): ?>
               <a href="<?php echo YRM_PRO_URL; ?>" target="_blank" class="yrm-far-upgreade-button">
                   <div class="yrm-pro yrm-pro-options-div" style="text-align: right">
                       <button class="yrm-upgrade-button-orange yrm-link-button yrm-extentsion-pro" onclick="window.open('<?php echo YRM_PRO_URL; ?>');">
                           <b class="h2">Unlock</b><br><span class="h5">Extension</span>
                       </button>
                   </div>
               </a>
           <?php endif; ?>
       </div>
	</div>
</div>