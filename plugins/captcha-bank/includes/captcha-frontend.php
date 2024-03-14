<?php
/**
 * This file contains frontend code.
 *
 * @author  Tech Banker
 * @package captcha-bank/includes
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} //exit if accessed directly.
global $captcha_array;
$border_style  = explode( ',', $captcha_array['border_style'] );
$captcha_url   = admin_url( 'admin-ajax.php' ) . '?captcha_code=';
$enter_captcha = __( 'Enter Captcha Here', 'captcha-bank' );
?>
<p class="captcha-title">
	<?php echo esc_attr( $enter_captcha ); ?> :
	<span class="error" style="color:red">*</span>
</p>
<input type="text" name="ux_txt_captcha_challenge_field" id="ux_txt_captcha_challenge_field" style="display:block;"/>
<img src="<?php echo esc_attr( $captcha_url . rand( 111, 99999 ) ); ?>" class="captcha_code_img"  id="captcha_code_img" style= "margin-top:10px; cursor:pointer; border:<?php echo intval( $border_style[0] ); ?>px <?php echo esc_attr( $border_style[1] ); ?> <?php echo esc_attr( $border_style[2] ); ?>" />
<img class="refresh-img" style = "cursor:pointer;margin-top:9px;vertical-align: top;" onclick="refresh();"  alt="Reload Image" height="16" width="16" src="<?php echo esc_attr( plugins_url( '/assets/global/img/refresh-icon.png', ( dirname( __FILE__ ) ) ) ); ?>"/>

<script type="text/javascript">
	function refresh()
	{
		var randNum = Math.floor((Math.random() * 99999) + 1);
		jQuery("#captcha_code_img").attr("src", "<?php echo esc_attr( $captcha_url ); ?>" + randNum);
		return true;
	}
</script>
