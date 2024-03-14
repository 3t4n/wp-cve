<?php

use WP_Reactions\Lite\Helper;
use WP_Reactions\Lite\Config;
use WP_Reactions\Lite\Shortcode;
use WP_Reactions\Lite\FieldManager\Switcher;

$tooltip1 = $tooltip2 = '';
extract($data);
$is_regular = ( Config::$current_options['activation'] == 'true' and Config::$current_options['behavior'] == 'regular');
$active_class = ($options['activation'] == 'true') ? 'p-active' : '';
?>
<div class="wpe-behaviors">
    <div class="row">
        <div class="col-md-12">
            <div class="option-wrap">
                <div class="primary-color-blue wpra-light-activation-title">
                    <div>
                        <h5 class="fw-700">Reactions</h5>
                        <div class="wpra-light-activation-status <?php echo $active_class; ?>">
                            <span><?php _e('Your Reactions are live!', 'wpreactions-lite'); ?></span>
                            <span><?php _e('Your Reactions are not showing', 'wpreactions-lite'); ?></span>
                        </div>
                    </div>
                    <div class="d-flex">
	                    <?php
	                    (new Switcher())
		                    ->setId('regular')
		                    ->setName('global_behavior')
		                    ->setValue($is_regular)
		                    ->setChecked(true)
		                    ->build();
	                    ?>
                        <div class="wpra-restore ml-3">
                            <span class="wpra-restore-btn">
                                <span class="dashicons dashicons-image-rotate"></span>
                                <span><?php _e('Reset', 'wpreactions-lite'); ?></span>
                            </span>
		                    <?php Helper::tooltip('reset-button'); ?>
                        </div>
                    </div>
                </div>
                <div class="wpra-behavior-preview" style="min-height: 410px;">
                    <?php
                    $behavior_preview = $options;
                    $behavior_preview['post_id'] = 'preview_lite_classic';
                    echo Shortcode::build($behavior_preview);
                    ?>
                </div>
            </div>
            <a href="<?php echo Helper::getAdminPage('global', '&behavior=global'); ?>"
               class="btn btn-open-blue btn-lg w-100" <?php Helper::is_disabled(Config::$current_options['activation']); ?>>
                <span class="dashicons dashicons-admin-customizer"></span><span><?php _e('Customize Now', 'wpreactions-lite'); ?></span>
            </a>
        </div>
    </div>
</div>
