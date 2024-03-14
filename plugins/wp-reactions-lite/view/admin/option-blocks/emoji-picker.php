<?php

use WP_Reactions\Lite\Helper;
use WP_Reactions\Lite\Config;

Helper::getTemplate( 'view/admin/components/option-heading',
	[
		'heading'    => __( 'Emoji Picker', 'wpreactions' ),
		'subheading' => __( 'Choose up to 7 of your favorite reactions and arrange them at the bottom of the page. To use our default lineup, go to the next step.', 'wpreactions-lite' ),
		'align'      => 'left',
		'tooltip'    => 'mix-and-match',
	]
);
?>
<div class="option-wrap emoji-picker">
	<?php for ( $i = 1; $i <= Config::MAX_EMOJIS; $i ++ ) {
		$active_class = in_array( $i, Config::$current_options['emojis'] ) ? 'active' : '';
		$size_fix_class = in_array($i, [8,9,10]) ? ' wpra-size-fix' : '';
		?>
        <div class="emoji-pick <?php echo $active_class . $size_fix_class; ?>"
             data-emoji_id="<?php echo $i; ?>" title="<?php echo Config::EMOJI_NAMES[ $i ]; ?>">
            <div class="emoji-lottie-holder" style="display: none"></div>
            <figure itemprop="gif" class="emoji-svg-holder"
                    style="background-image: url('<?php echo Helper::getAsset( "emojis/svg/$i.svg" ); ?>'"></figure>
        </div>
	<?php } ?>
</div>
<div class="option-wrap">
    <div class="drag-and-drop mb-3">
        <span class="dashicons dashicons-move"></span> <?php _e( 'DRAG & DROP TO ARRANGE', 'wpreactions-lite' ); ?>
    </div>
    <div class="picked-emojis">
		<?php foreach ( Config::$current_options['emojis'] as $emoji_id ): if ($emoji_id == -1) continue; ?>
            <div class="picked-emoji emoji-lottie-holder lottie-element <?php Helper::echoIf(in_array($emoji_id, [8,9,10]), 'picker-pad-fix'); ?>"
                 data-emoji_id="<?php echo $emoji_id; ?>"></div>
		<?php endforeach; ?>
    </div>
</div>