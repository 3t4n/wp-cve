<?php
if ( ! defined( 'ABSPATH' ) ) { exit; } 
global $foxtool_options;
# phát hiện chặn quảng cáo
// add js css blocker
function foxtool_blocker_assets(){
	global $foxtool_options;
	if(isset($foxtool_options['notify-block1']) && !is_user_logged_in()){
	wp_enqueue_style( 'foxtoolbl', FOXTOOL_URL . 'link/blocker/foxtoolbl.css', array(), FOXTOOL_VERSION);
	wp_enqueue_script( 'foxtoolbl', FOXTOOL_URL . 'link/blocker/foxtoolbl.js', array('jquery'), FOXTOOL_VERSION);
	}
}
add_action('wp_enqueue_scripts', 'foxtool_blocker_assets');
// ads fake
function foxtool_blocker_adsfake(){
	echo '<div style="display:none" id="googleads"><div class="ads ad adsbox doubleclick ad-placement carbon-ads" style="background-color:red;height:300px;width:100%;"><!-- Quang cao -->Google Ads, Facebook Ads Google Adsense<!-- Ads --></div></div>';
}
add_action('wp_head', 'foxtool_blocker_adsfake');
// add footer
function foxtool_blocker_footer(){ 
	global $foxtool_options;
	if(isset($foxtool_options['notify-block1']) && !is_user_logged_in()){
		$title = !empty($foxtool_options['notify-block12']) ? $foxtool_options['notify-block12'] : __('Ad-blocker detection', 'foxtool');
		$content = !empty($foxtool_options['notify-block13']) ? $foxtool_options['notify-block13'] : __('Please disable ad-blocker on your browser to access and view the content', 'foxtool');
		$botbg = !empty($foxtool_options['notify-block-c1']) ? 'background:'. $foxtool_options['notify-block-c1'] .';' : NULL;
		$botbor = !empty($foxtool_options['notify-block-c2']) ? 'border-bottom: 6px solid '. $foxtool_options['notify-block-c2'] .';' : NULL;
		$setlock = isset($foxtool_options['notify-block11']);
		?>
		<div id="ft-blocker" class="ft-blocker" style="display:none">
			<div class="ft-blocker-box">
				<div class="ft-blocker-card" id="ft-blockid" data-enabled="<?php echo json_encode($setlock); ?>">
					<p class="ft-blocker-tit"><?php echo $title; ?></p>
					<div class="ft-blocker-cont"><?php echo $content; ?></div>
					<?php if(isset($foxtool_options['notify-block11'])){ ?>
					<div><span style="<?php echo $botbg . $botbor; ?>" id="ft-blocker-clo"><?php _e('Agree', 'foxtool') ?></span></div>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php
	}
}
add_action('wp_footer', 'foxtool_blocker_footer');
# thong bao
if(isset($foxtool_options['notify-notis1'])){
function foxtool_notic_footer(){ 
	global $foxtool_options;
	$content = !empty($foxtool_options['notify-notis11']) ? $foxtool_options['notify-notis11'] : __('You havent added content yet', 'foxtool');
	$colorbg = !empty($foxtool_options['notify-notis-c1']) ? 'style="background-color:'. $foxtool_options['notify-notis-c1']. ';"' : NULL;
	?>
	<div class="noti-info noti-message" id="noti-message" <?php echo $colorbg; ?>>
	<div class="fix-message noti-message-box">
		<div class="noti-message-1"><?php echo $content; ?></div>
		<div class="noti-message-2"><button onclick="ftnone(event, 'noti-message')">&#215;</button></div>
	</div>
	</div>
	<style> .noti-message {background-size: 40px 40px;background-image: linear-gradient(135deg, rgba(255, 255, 255, .05) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .05) 50%, rgba(255, 255, 255, .05) 75%, transparent 75%, transparent);width: 100%;border: none;color: #fff;padding: 20px 0px;position: fixed;top:0;text-shadow: 0 1px 0 rgba(0,0,0,.5);-webkit-animation: animate-ms 2s linear infinite;-moz-animation: animate-ms 2s linear infinite;z-index:9999;box-sizing: border-box;}.fix-message{max-width:1200px;margin:auto;padding: 0px 20px;}.noti-message a{color: #fff444;display: inline-block;}.noti-message-box{display:flex;}.noti-message-1{width:100%;animation: ani 1.5s;font-size: 16px;}@keyframes ani{from{filter: blur(5px);opacity: 0;}to{letter-spacing: 0;filter: blur(0);opacity: 1px;}}@keyframes thongbao-top {0% {transform: translate3d(0, 0, 0) scale(1);}33.3333% {transform: translate3d(0, 0, 0) scale(0.5);}66.6666% {transform: translate3d(0, 0, 0) scale(1);}100% {transform: translate3d(0, 0, 0) scale(1);}0% {box-shadow: 0 0 0 0px #fff,0 0 0 0px #fff;}50% {transform: scale(0.98);}100% {box-shadow: 0 0 0 15px rgba(0,210,255,0),0 0 0 30px rgba(0,210,255,0);}}.noti-message-2{width:30px;margin-left:10px;display: flex;align-items: center;}.noti-message-2 button {padding: 0px;width: 30px;height: 30px;display: flex;align-items: center;justify-content: center;font-size: 16px;background: #ffffff29;color: #fff;border-radius: 100%;animation: thongbao-top 1000ms infinite;margin:0px;border:none}.noti-info {background-color: #4ea5cd;}@-webkit-keyframes animate-ms {from {background-position: 0 0;}to {background-position: -80px 0;}}@-moz-keyframes animate-ms {from {background-position: 0 0;}to {background-position: -80px 0;}}</style>
	<?php
}
add_action('wp_footer', 'foxtool_notic_footer');	
} 