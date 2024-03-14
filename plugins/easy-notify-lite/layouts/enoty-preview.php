<?php

if ( ! defined('ABSPATH') ) {
	die('Please do not load this file directly.');
}

function easynotify_preview( $id, $val ) {
	
	if ( $val['enoty_cp_thumbsize_swc'] == 'on' ) {
		$notyw = $val['enoty_cp_thumbsize_tw'];
		$notyh = $val['enoty_cp_thumbsize_th'];
		} else {
			$notyw = 740;
			$notyh = 'auto';
			}
	
	$offect = explode("-", $val['enoty_cp_open_effect'] );
	$cffect = explode("-", $val['enoty_cp_close_effect']);
	
	$lyot = $val['enoty_cp_layoutmode'];
	$layout = preg_replace('/\\.[^.\\s]{3,4}$/', '', $lyot);
	
	$csslyot = $val['enoty_cp_layoutmode'];
	$csslayout = preg_replace('/\\.[^.\\s]{3,4}$/', '', $csslyot);
	
	wp_register_style( 'preview_wp_admin_css', plugins_url( 'css/layouts/'. str_replace('_', '-', $csslayout ).'.css' , dirname(__FILE__) ), false, '1.0.0' );

	ob_start(); 
	
		echo '<!DOCTYPE html>';
		echo '<html><head><title>'.ENOTIFY_NAME.' Preview</title>';
		echo '<style> body, html { height: 100%; width: 100%;} ';
		echo 'body { display: block;margin: 0;padding: 0;} *, *::before, *::after {webkit-box-sizing: content-box !important;-moz-box-sizing: content-box !important;box-sizing: content-box !important;}</style>';
		wp_enqueue_script( 'jquery' );
    	wp_enqueue_style( 'preview_wp_admin_css' );
		wp_enqueue_style( 'enoty_enotybox_style', ENOTIFY_URL .'/css/enotybox/jquery.enotybox.css' );
		wp_enqueue_style( 'preview_frontend_css', ENOTIFY_URL .'/css/frontend.css' );

		wp_enqueue_scripts();
		wp_print_styles();
		print_admin_styles();
		wp_print_head_scripts();
	
		echo '</head><body>';

		wp_enqueue_script( 'enoty-enotybox-js' );
		wp_enqueue_script( 'enoty-placeholder' );
		wp_enqueue_script( 'enoty-js' );
		
		echo '<div style="display: none !important;" id="inline-container-'.$id.'">';
		echo'<a style="display: none !important;" href="#noty-'.$id.'" id="launcher-'.$id.'"></a>';
		echo'<div style="display: none !important;"><div id="noty-'.$id.'">';
		
		include_once str_replace('_', '-', $layout ).'.php';
		$layoutfunc = $layout;
		$layoutfunc( null, $val, '1' );
		echo'</div>';

		wp_print_footer_scripts();
		
		
	?>

	<script type="text/javascript">
	jQuery(document).ready(function($) {

					var timerId;
					if(timerId != undefined){clearInterval(timerId);}
 					timerId =  setInterval(function (){
					jQuery('#launcher-<?php echo $id; ?>').fancybox({
						type: 'inline',
						padding: 0,
						margin: 60,
						width: '<?php echo $notyw; ?>',
						height: '<?php echo $notyh; ?>',
						transitionIn: '<?php echo $offect[1]; ?>', 
						transitionOut: '<?php echo $cffect[1]; ?>',
						autoScale: false,
						showNavArrows: false,
						hideOnOverlayClick: false,
						autoDimensions: false,
						fitToView: false,
						scrolling: 'no',
						centerOnScroll: true,
						onComplete: function(){
							    clearInterval(timerId);
								}
						}).trigger("click");
						}, <?php echo $val['enoty_cp_notify_delay']; ?>000);

			});
	</script>	


<?php

easynotify_dynamic_styles( $id, $val, '1' );

echo '</div></div>'; 
echo '</body></html>'; 
$prevw = ob_get_clean();
echo $prevw;  

}

?>