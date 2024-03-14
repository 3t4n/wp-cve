<?php
use WP_Reactions\Lite\Config;
use WP_Reactions\Lite\Helper;
?>
<div class="mt-3">
    <ol class="wpra-stepper-bar" data-activation="<?php echo Config::$current_options['activation']; ?>">
        <li class="wpra-stepper-single is-current" data-tab_id="1">
            <div class="single-wrap">
                <span class="wpra-stepper-num">1</span>
                <span class="wpra-stepper-desc">
                    <span><?php _e('Emoji Picker', 'wpreactions-lite'); ?></span>
                </span>
            </div>
        </li>
        <li class="wpra-stepper-single" data-tab_id="2">
            <div class="single-wrap">
                <span class="wpra-stepper-num">2</span>
                <span class="wpra-stepper-desc">
                    <span><?php _e('Setup', 'wpreactions-lite'); ?></span>
                </span>
            </div>
        </li>
        <li class="wpra-stepper-single" data-tab_id="3">
            <div class="single-wrap">
                <span class="wpra-stepper-num">3</span>
                <span class="wpra-stepper-desc">
                    <span><?php _e('Styling', 'wpreactions-lite'); ?></span>
                </span>
            </div>
        </li>
        <li class="wpra-stepper-single" data-tab_id="4">
            <div class="single-wrap">
                <span class="wpra-stepper-num">4</span>
                <span class="wpra-stepper-desc">
                    <span><?php _e('Social Share', 'wpreactions-lite'); ?></span>
                </span>
            </div>
        </li>
        <li class="wpra-stepper-single" data-tab_id="5">
            <div class="single-wrap">
                <span class="wpra-stepper-num">5</span>
                <span class="wpra-stepper-desc">
                    <span><?php _e('Review & Save', 'wpreactions-lite'); ?></span>
                </span>
            </div>
        </li>
    </ol>
    <div class="wpra-option-body">
        <div class="body-item active" data-body_id="1">
		    <?php Helper::getTemplate( 'view/admin/steps/regular/picker' ); ?>
        </div>
		<div class="body-item" data-body_id="2">
			<?php Helper::getTemplate( 'view/admin/steps/regular/display' ); ?>
		</div>
		<div class="body-item" data-body_id="3">
			<?php Helper::getTemplate( 'view/admin/steps/regular/styling' ); ?>
		</div>
		<div class="body-item" data-body_id="4">
			<?php Helper::getTemplate( 'view/admin/steps/regular/social' ); ?>
		</div>
		<div class="body-item" data-body_id="5">
			<?php Helper::getTemplate( 'view/admin/steps/regular/finish' ); ?>
		</div>
	</div>
</div>
