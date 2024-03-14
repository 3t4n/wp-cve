<?php
use  WP_Reactions\Lite\Helper;
use WP_Reactions\Lite\Config;
?>
<div class="option-wrap">
	<div class="emojis-set-top-bar">
		<div class="sgc-go-back">
			<span class="dashicons dashicons-arrow-left-alt mr-2"></span> Emoji picker
		</div>
		<?php Helper::tooltip('your-emojis-set'); ?>
	</div>
	<h2 class="emojis-set-title"><?php _e('Your emojis are set!', 'wpreactions-lite'); ?></h2>
	<div class="row emojis-set-items">
		<div class="col emojis-set-item">
			<div class="emoji-lottie-holder"></div>
		</div>
		<div class="col emojis-set-item">
			<div itemprop="gif" class="emoji-lottie-holder"></div>
		</div>
		<div class="col emojis-set-item">
			<div itemprop="gif" class="emoji-lottie-holder"></div>
		</div>
		<div class="col emojis-set-item">
			<div itemprop="gif" class="emoji-lottie-holder"></div>
		</div>
		<div class="col emojis-set-item">
			<div itemprop="gif" class="emoji-lottie-holder"></div>
		</div>
		<div class="col emojis-set-item">
			<div itemprop="gif" class="emoji-lottie-holder"></div>
		</div>
	</div>
</div>
