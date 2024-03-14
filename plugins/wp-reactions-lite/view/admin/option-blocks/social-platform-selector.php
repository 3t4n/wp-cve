<?php
use WP_Reactions\Lite\Helper;
use WP_Reactions\Lite\FieldManager;
use WP_Reactions\Lite\Config;
?>

<div class="option-wrap">
    <div class="option-header">
        <h4>
            <span><?php _e('Social Share Buttons', 'wpreactions'); ?></span>
			<?php Helper::tooltip( 'social-platform-selector' ); ?>
        </h4>
        <small><?php _e('Turn on to make buttons active. Use custom text in the label fields.', 'wpreactions'); ?></small>
    </div>
    <div class="social-picker">
		<?php foreach ( $options['social_platforms'] as $platform => $status ) { ?>
            <div class="social-picker-item">
				<?php
				( new FieldManager\Switcher() )
					->setId( "social_platforms_$platform" )
					->addClasses( 'wpe-switch-small' )
					->setValue( $options['social_platforms'][ $platform ] )
					->setChecked( 'true' )
					->build();
				?>
                <div class="social-picker-item-img" style="background-color: <?php echo Config::SOCIAL_PLATFORMS[$platform]['color']; ?>40;">
					<span class="d-inline-block" style="background-color: <?php echo Config::SOCIAL_PLATFORMS[$platform]['color']; ?>">
                        <img src="<?php echo Helper::getAsset( "images/social/$platform.svg" ); ?>" alt="">
					</span>
                </div>
				<?php
				( new FieldManager\Text() )
					->setId( "social_labels_$platform" )
					->setValue( $options['social_labels'][ $platform ] )
					->build();
				?>
            </div>
		<?php } ?>
    </div>
    <div class="social-platforms-banner d-flex">
        <div>
            <h3 style="font-size: 20px" class="fw-700">Go Pro</h3>
            <p style="font-size: 16px;">Get more social platforms<br>for your users to share on</p>
            <a href="https://wpreactions.com/pricing" target="_blank" class="btn btn-purple">Upgrade Now</a>
        </div>
        <img src="<?php echo Helper::getAsset('images/banners/rocket-and-man.png'); ?>" alt="Share platforms banner">
        <div class="social-platforms-banner-icons">
            <div><img src="<?php echo Helper::getAsset('images/social-color/facebook.svg'); ?>" alt=""></div>
            <div><img src="<?php echo Helper::getAsset('images/social-color/gmail.svg'); ?>" alt=""></div>
            <div><img src="<?php echo Helper::getAsset('images/social-color/messenger.svg'); ?>" alt=""></div>
            <div><img src="<?php echo Helper::getAsset('images/social-color/twitter.svg'); ?>" alt=""></div>
            <div><img src="<?php echo Helper::getAsset('images/social-color/pinterest.svg'); ?>" alt=""></div>
            <div><img src="<?php echo Helper::getAsset('images/social-color/reddit.svg'); ?>" alt=""></div>
            <div><img src="<?php echo Helper::getAsset('images/social-color/linkedin.svg'); ?>" alt=""></div>
            <div><img src="<?php echo Helper::getAsset('images/social-color/whatsapp.svg'); ?>" alt=""></div>
            <div><img src="<?php echo Helper::getAsset('images/social-color/tumblr.svg'); ?>" alt=""></div>
            <div><img src="<?php echo Helper::getAsset('images/social-color/vkontakte.svg'); ?>" alt=""></div>
            <div><img src="<?php echo Helper::getAsset('images/social-color/telegram.svg'); ?>" alt=""></div>
            <div><img src="<?php echo Helper::getAsset('images/social-color/email.svg'); ?>" alt=""></div>
        </div>
    </div>
</div>
