<?php
$params = ReadMoreData::params();
if (empty($tab['contentType'])) {
	$tab['contentType'] = 'content';
}
$functions = new ReadMoreFunctions();
$allowedTag = ReadMoreAdminHelper::getAllowedTags();
?>
<div class="yrm-element-info-wrapper" data-id="<?php echo esc_attr($key); ?>">
	<div class="yrm-view-element-wrapper" data-options="false">
		<div class="yrm-element-label-wrapper">
			<span class="sub-option-hidden-data"></span>
			<i class="dashicons dashicons-arrow-right-alt2"></i><span>Item: <span class="tab-header-label"><?php echo esc_attr($tab['label']);?></span></span>
		</div>
		<div class="yrm-element-conf-wrapper">
			<i class="dashicons dashicons-sort delete-accordion-item" data-key="<?php echo esc_attr($key); ?>" aria-hidden="true"></i>
			<span class="yrm-conf-element yrm-conf-home dashicons dashicons-admin-generic"></span>
			<i class="dashicons dashicons-trash delete-accordion-item" data-key="<?php echo esc_attr($key); ?>" aria-hidden="true"></i>
		</div>
	</div>
	<div class="yrm-element-options-wrapper yrm-hide-element" >
		<div class="row form-group">
			<div class="col-md-6">
				<label><?php _e('Label', YRM_LANG); ?></label>
			</div>
			<div class="col-md-6">
				<input type="text" class="form-control yrm-accordion-label" name="yrm-accordion[<?php echo esc_attr($key) ?>][label]" value="<?php echo esc_attr($tab['label']);?>">
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-6">
				<label><?php _e('Content type', YRM_LANG); ?></label>
			</div>
			<div class="col-md-6">
				<div class="btn-group btn-group-toggle" data-toggle="buttons">
					<label class="btn btn-primary <?php echo ($tab['contentType'] == 'content' ? 'active': '')?> yrm-accordion-switcher-label yrm-accordion-switcher-label-<?php echo esc_attr($key) ?>-content" data-key="<?php echo esc_attr($key) ?>">
						<input type="radio" value="content" <?php echo ($tab['contentType'] == 'content' ? 'checked': '')?> name="yrm-accordion[<?php echo esc_attr($key) ?>][contentType]" data-id="content" autocomplete="off" class="yrm-accordion-switcher"> Content
					</label>
					<label class="btn btn-primary <?php echo ($tab['contentType'] == 'post' ? 'active': '')?> yrm-accordion-switcher-label yrm-accordion-switcher-label-<?php echo esc_attr($key) ?>-post" data-key="<?php echo esc_attr($key) ?>" <?php echo yrm_is_free() ? 'disabled="disabled" data-pro-url="'.YRM_PRO_URL.'"': ''; ?>>
						<input type="radio" value="post" <?php echo ($tab['contentType'] == 'post' ? 'checked': '')?> name="yrm-accordion[<?php echo esc_attr($key) ?>][contentType]" data-id="post" autocomplete="off" class="yrm-accordion-switcher" <?php echo yrm_is_free() ? 'disabled="disabled"': ''; ?>> Post <?php echo yrm_is_free() ? '(PRO)': '';?>
					</label>
				</div>
			</div>
		</div>
		<div class="yrm-accordion-content-wrapper yrm-hide" id="yrm-accordion-content-type-<?php echo esc_attr($key) ?>-content">
			<div class="row form-group">
				<div class="col-md-12">
					<label><?php _e('Content', YRM_LANG); ?></label>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-md-12 accordion-editor-wrapper">
					<?php
					$editorId = 'yrm-accordion-content-'.esc_attr($key);
					$content = $tab['content'];
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
		<div class="yrm-accordion-content-wrapper yrm-hide" id="yrm-accordion-content-type-<?php echo esc_attr($key) ?>-post">
			<div class="row form-group">
				<div class="col-md-6">
					<?php
						$name = 'yrm-accordion-content-post-'.esc_attr($key);
					?>
					<label for="<?php esc_attr_e($name);?>"><?php _e('Select Post', YRM_LANG)?></label>
				</div>
				<div class="col-md-6">
					<?php
						echo wp_kses($functions::yrmSelectBox($params['selectedPost'],esc_attr(@$tab['post']), array('name'=>$name,'class' => 'yrm-js-select2', 'id' => $name)), $allowedTag);
					?>
				</div>
			</div>
		</div>
	</div>
	<div class="yrm-element-margin-bottom"></div>
</div>