<?php
$params = ReadMoreData::params();
$checkIsChecked = function ($optionName) {
	return (get_option($optionName) ? 'checked': '');
};
$userRoles = $params['userRoles'];
$allowedTag = ReadMoreAdminHelper::getAllowedTags();
?>
<div class="ycf-bootstrap-wrapper yrm-settings">
	<?php if(!empty($_GET['saved'])) : ?>
		<div id="default-message" class="updated notice notice-success is-dismissible">
			<p><?php echo _e('Settings saved.', YRM_LANG);?></p><button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php echo _e('Dismiss this notice.', YRM_LANG);?></span></button>
		</div>
	<?php endif; ?>
<div class="row">
	<div class="col-md-6">
        <form action="<?php echo admin_url().'admin-post.php?action=yrmSaveSettings'?>" method="post">
        <?php wp_nonce_field('YRM_ADMIN_POST_NONCE', YRM_ADMIN_POST_NONCE);?>
		<div class="panel panel-default">
			<div class="panel-heading"><?php _e('Settings', YRM_LANG);?></div>
			<div class="panel-body">
                <div class="row form-group">
                    <div class="col-md-4">
                        <label class="ycd-label-of-switch" for="yrm-delete-data"><?php _e('Remove Settings', YRM_LANG); ?></label>
                    </div>
                    <div class="col-md-2">
                        <div class="yrm-switch-wrapper">
                            <label class="yrm-switch">
                                <input type="checkbox" id="yrm-delete-data" name="yrm-delete-data" class="yrm-accordion-checkbox" <?php echo esc_attr($checkIsChecked('yrm-delete-data')); ?> >
                                <span class="yrm-slider yrm-round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="ycd-label-of-switch">
                            <?php _e('This option will remove all settings and styles when <b>Delete plugin</b>', YRM_LANG); ?>
                        </label>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-4">
                        <label class="ycd-label-of-switch" for="yrm-hid-find-and-replace-menu"><?php _e('Hide Find And Replace Menu', YRM_LANG); ?></label>
                    </div>
                    <div class="col-md-2">
                        <div class="yrm-switch-wrapper">
                            <label class="yrm-switch">
                                <input type="checkbox" id="yrm-hid-find-and-replace-menu" name="yrm-hid-find-and-replace-menu" class="" <?php echo esc_attr($checkIsChecked('yrm-hid-find-and-replace-menu')); ?> >
                                <span class="yrm-slider yrm-round"></span>
                            </label>
                        </div>
                    </div>
                </div>
				<div class="row form-group">
					<div class="col-md-4">
						<label class="ycd-label-of-switch" for="yrm-hid-accordion-menu"><?php _e('Hide Accordion Menu', YRM_LANG); ?></label>
					</div>
					<div class="col-md-2">
						<div class="yrm-switch-wrapper">
							<label class="yrm-switch">
								<input type="checkbox" id="yrm-hid-accordion-menu" name="yrm-hid-accordion-menu" class="" <?php echo esc_attr($checkIsChecked('yrm-hid-accordion-menu')); ?> >
								<span class="yrm-slider yrm-round"></span>
							</label>
						</div>
					</div>
				</div>
				<div class="row form-group">
                    <div class="col-md-4">
                        <label class="ycd-label-of-switch" for="yrm-hide-media-buttons"><?php _e('Hide media buttons', YRM_LANG); ?></label>
                    </div>
                    <div class="col-md-2">
                        <div class="yrm-switch-wrapper">
                            <label class="yrm-switch">
                                <input type="checkbox" id="yrm-hide-media-buttons" name="yrm-hide-media-buttons" class="" <?php echo esc_attr($checkIsChecked('yrm-hide-media-buttons')); ?> >
                                <span class="yrm-slider yrm-round"></span>
                            </label>
                        </div>
                    </div>
                </div>
				<!-- Google fonts start -->
				<?php if (YRM_PKG > YRM_FREE_PKG): ?>
				<div class="row form-group">
					<div class="col-md-4">
						<label class="ycd-label-of-switch" for="yrm-hide-google-fonts"><?php _e('Do not Include Google Fonts', YRM_LANG); ?></label>
					</div>
					<div class="col-md-2">
						<div class="yrm-switch-wrapper">
							<label class="yrm-switch">
								<input type="checkbox" id="yrm-hide-google-fonts" name="yrm-hide-google-fonts" class="" <?php echo esc_attr($checkIsChecked('yrm-hide-google-fonts')); ?> >
								<span class="yrm-slider yrm-round"></span>
							</label>
						</div>
					</div>
				</div>
				<?php endif; ?>
				<!-- Google fonts end -->
				<div class="row form-group">
					<div class="col-md-4">
                        <label>
	                        <?php _e('User role who can use plugin', YRM_LANG);?>
                        </label>
					</div>
					<div class="col-md-8">
                        <?php echo wp_kses($functions::yrmSelectBox($userRoles, get_option('yrm-user-roles'), array('name' => 'yrm-user-roles[]', 'multiple' => 'multiple', 'class' => 'yrm-js-select2')), $allowedTag);?>
                    </div>
				</div>
                <div class="row">
                    <div class="col-md-12">
                        <input type="submit" class="btn btn-primary" value="<?php _e('Save changes', YRM_LANG); ?>">
                    </div>
                </div>
			</div>
		</div>
        </form>
	</div>
</div>
</div>