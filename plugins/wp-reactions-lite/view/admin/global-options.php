<?php
use WP_Reactions\Lite\Helper;
global $wpra_lite;
?>
<div class="wpreactions primary-color-blue wpra-global-options" data-secure="<?php echo wp_create_nonce( 'wpra-admin-action' ); ?>">
    <!-- floating preview -->
	<?php
	Helper::getTemplate(
		'view/admin/components/floating-preview'
	);
	?>
    <div class="loading-overlay">
        <div class="overlay-center">
            <div class="wpra-spinner" style="width: 50px; height: 50px;"></div>
            <div class="overlay-message"></div>
        </div>
    </div>

    <!-- top bar -->
	<?php
	Helper::getTemplate(
		'view/admin/components/top-bar',
		[
			"section_title" => "DASHBOARD",
			"logo"          => Helper::getAsset( 'images/wpj_logo.png' ),
		]
	);
	?>
	<?php if ( ! isset( $_GET['behavior'] ) ): ?>
        <div id="global-behavior-chooser" class="mt-3">
            <div class="wpra-option-heading d-flex align-items-center justify-content-between heading-left">
                <div>
                    <h4>
                        <span><?php _e('Global Activation', 'wpreactions-lite'); ?></span>
		                <?php Helper::tooltip( 'heading-global-behavior-chooser' ); ?>
                    </h4>
                    <p><?php _e('Get started by turning on the toggle to deploy your emoji reactions sitewide. Click customize now to override factory settings.', 'wpreactions-lite'); ?></p>
                </div>
                <a href="https://wpreactions.com/pricing" target="_blank" class="btn btn-blue"><i class="qa qa-star"></i><?php _e('Go Pro', 'wpreactions-lite'); ?></a>
            </div>
			<?php
			Helper::getOptionBlock(
				"behavior-chooser",
				[
					"tooltip1" => "behavior-chooser-regular",
					"tooltip2" => "behavior-chooser-reveal",
				]
			);
			?>
        </div>
	<?php else:
		Helper::getTemplate( 'view/admin/steps/global-regular-steps' );
		Helper::getTemplate(
			'view/admin/components/step-control',
			[
				'text_prev'  => 'Go Back',
				'prev_class' => 'prev',
				'text_save'  => 'Save & Exit',
				'save_class' => 'save-wpj-options',
				'text_next'  => 'Next',
				'next_class' => 'next',
			]
		);
	endif; ?>
</div>
