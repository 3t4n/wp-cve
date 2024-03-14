<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly	?>
<!-- Snow Storm Javascript -->
<script type="text/javascript">
(function($) {
	$(document).ready(function() {		
		if (typeof(snowStorm) !== 'undefined') {
			snowStorm.flakesMax = <?php echo get_option('snowstorm_flakesMax'); ?>;
			snowStorm.flakesMaxActive = <?php echo get_option('snowstorm_flakesMaxActive'); ?>;
			snowStorm.animationInterval = <?php echo get_option('snowstorm_animationInterval'); ?>;
			snowStorm.excludeMobile = <?php if (get_option('snowstorm_excludeMobile') == "Y") : ?>true<?php else : ?>false<?php endif; ?>;
			snowStorm.followMouse = <?php if (get_option('snowstorm_followMouse') == "Y") : ?>true<?php else : ?>false<?php endif; ?>;
			snowStorm.snowColor = '<?php echo get_option('snowstorm_snowColor'); ?>';
			snowStorm.snowCharacter = '&bull;';
			snowStorm.snowStick = <?php if (get_option('snowstorm_snowStick') == "Y") : ?>true<?php else : ?>false<?php endif; ?>;
			snowStorm.useMeltEffect = <?php if (get_option('snowstorm_useMeltEffect') == "Y") : ?>true<?php else : ?>false<?php endif; ?>;
			snowStorm.useTwinkleEffect = <?php if (get_option('snowstorm_useTwinkleEffect') == "Y") : ?>true<?php else : ?>false<?php endif; ?>;
			snowStorm.zIndex = 999999;
		}
	});
})(jQuery);
</script>