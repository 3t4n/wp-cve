<?php if (! defined('ABSPATH')) {
    exit;
}
/**
 * Shorcode For Facebook By Weblizar
 */
add_shortcode("FBW", "FacebookLikeBox");
function FacebookLikeBox()
{
    ob_start();
    $FacebookSettings = unserialize(get_option("weblizar_facebook_shortcode_settings"));

    $ForceWall = 'false';
    if (isset($FacebookSettings[ 'ForceWall' ])) {
        $ForceWall = $FacebookSettings[ 'ForceWall' ];
    }

    $Header = 'true';

    $Height = 600;
    if (isset($FacebookSettings[ 'Height' ])) {
        $Height = $FacebookSettings[ 'Height' ];
    }

    $FacebookPageUrl = 'https://www.facebook.com/Weblizarwp/';
    if (isset($FacebookSettings[ 'FacebookPageUrl' ])) {
        $FacebookPageUrl = $FacebookSettings[ 'FacebookPageUrl' ];
    }

    $ShowBorder = 'true';
    if (isset($FacebookSettings[ 'ShowBorder' ])) {
        $ShowBorder = $FacebookSettings[ 'ShowBorder' ];
    }

    $ShowFaces = 'true';
    if (isset($FacebookSettings[ 'ShowFaces' ])) {
        $ShowFaces = $FacebookSettings[ 'ShowFaces' ];
    }

    $Stream = 'true';
    if (isset($FacebookSettings[ 'Stream' ])) {
        $Stream = $FacebookSettings[ 'Stream' ];
    }

    $Width = 350;
    if (isset($FacebookSettings[ 'Width' ])) {
        $Width = $FacebookSettings[ 'Width' ];
    }

    $FbAppId = '529331510739033';
    if (isset($FacebookSettings[ 'FbAppId' ])) {
        $FbAppId = $FacebookSettings[ 'FbAppId' ];
    }

    $weblizar_locale_fb = "en_GB";
    if (isset($FacebookSettings[ 'weblizar_locale_fb' ])) {
        $weblizar_locale_fb = $FacebookSettings[ 'weblizar_locale_fb' ];
    } ?>
<div id="fb-root"></div>
<script>
	(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s);
		js.id = id;
		js.src =
			"//connect.facebook.net/<?php echo esc_attr($weblizar_locale_fb); ?>/sdk.js#xfbml=1&version=v2.7";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
</script>
<div class="fb-like-box"
	data-height="<?php echo esc_attr($Height); ?>"
	data-href="<?php echo esc_url($FacebookPageUrl); ?>"
	data-show-border="<?php echo esc_attr($ShowBorder); ?>"
	data-show-faces="<?php echo esc_attr($ShowFaces); ?>"
	data-stream="<?php echo esc_attr($Stream); ?>"
	data-width="<?php echo esc_attr($Width); ?>"
	data-force-wall="<?php echo esc_attr($ForceWall); ?>">
</div>
<?php
    return ob_get_clean();
}
