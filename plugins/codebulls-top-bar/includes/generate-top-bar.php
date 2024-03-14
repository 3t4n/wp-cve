<?php 
add_action('wp_head', 'cb_top_add_cb_top_bar');
/**
 * Method that add the top bar to the page
 */
function cb_top_add_cb_top_bar(){
	$options=get_option('options_cb_top_bar');
	$user_can_close_top_bar=$options['user-can-close-top-bar'] == '1' ? true:false;
	$options_content=get_option('options_cb_top_bar_content');
	$style_top_bar='
		background-color:'.$options['color-top-bar-plugin'].';
	';
	$style_content='
		color:'.$options['color-text-top-bar-plugin'].';
		height:'.$options['height-top-bar-plugin'].';
	';
	$style_content_mobile='
		color:'.$options['color-text-top-bar-plugin'].';
		height:'.$options['height-top-bar-plugin'].';
	';
	if($options['available-plugin'] == '1'){
		echo '
		<style type="text/css">
			'.$options['custom-css-top-bar-plugin'].'
			.cb-top-bar-flex-container{
				'.$style_top_bar.'
			}
		</style>';
		switch ($options['number-columns-top-bar-plugin']) {
			case '1':
				$style_content.=$user_can_close_top_bar ? 'width:94%;':'width:100%;';
				$style_content_mobile.=$user_can_close_top_bar ? 'width:80%;':'width:100%;';
				echo '
				<style type="text/css">
					.cb-top-bar-plugin-center,.cb-top-bar-plugin-left,.cb-top-bar-plugin-right{
						'.$style_content.'
					}
					#cb-top-bar-close > a{
						color:'.$options['color-text-top-bar-plugin'].';
					}
					@media only screen and (max-width: 600px) {
						.cb-top-bar-plugin-center,.cb-top-bar-plugin-left,.cb-top-bar-plugin-right{
							'.$style_content_mobile.'
						}
					}
				</style>';
				$content_top_bar= '
					<div id="top-bar">
						<div class="cb-top-bar-flex-container">
							<div class="cb-top-bar-plugin-center">'.do_shortcode(html_entity_decode($options_content['center-content-top-bar-plugin'], ENT_QUOTES | ENT_IGNORE)).'</div>';
				$content_top_bar.=$user_can_close_top_bar ? '<div id="cb-top-bar-close"><a href="javascript:void(0)">X</a></div>':'';
				$content_top_bar.='</div></div>';
				echo $content_top_bar;
				break;
			case '2':
				$style_content.=$user_can_close_top_bar ? 'width:46%;':'width:48%;';
				$style_content_mobile.=$user_can_close_top_bar ? 'width:100%;':'width:48%;';
				echo '
				<style type="text/css">
					.cb-top-bar-plugin-center,.cb-top-bar-plugin-left,.cb-top-bar-plugin-right{
						'.$style_content.'
					}
					#cb-top-bar-close > a{
						color:'.$options['color-text-top-bar-plugin'].';
					}
					@media only screen and (max-width: 600px) {
						.cb-top-bar-plugin-center,.cb-top-bar-plugin-left,.cb-top-bar-plugin-right{
							'.$style_content_mobile.'
						}
					}
				</style>';
				$content_top_bar= '
					<div id="top-bar">
						<div class="cb-top-bar-flex-container">
							<div class="cb-top-bar-plugin-left">'.do_shortcode(html_entity_decode($options_content['left-content-top-bar-plugin'])).'</div>
							<div class="cb-top-bar-plugin-right">'.do_shortcode(html_entity_decode($options_content['right-content-top-bar-plugin'])).'</div>';
				$content_top_bar.=$user_can_close_top_bar ? '<div id="cb-top-bar-close"><a href="javascript:void(0)">X</a></div>':'';
				$content_top_bar.='</div></div>';
				echo $content_top_bar;
				break;
			case '3':
				$style_content.=$user_can_close_top_bar ? 'width:30%;':'width:31.3%;';
				$style_content_mobile.=$user_can_close_top_bar ? 'width:100%;':'width:31.3%;';
				echo '
				<style type="text/css">
					.cb-top-bar-plugin-center,.cb-top-bar-plugin-left,.cb-top-bar-plugin-right{
						'.$style_content.'
					}
					#cb-top-bar-close > a{
						color:'.$options['color-text-top-bar-plugin'].';
					}
					@media only screen and (max-width: 600px) {
						.cb-top-bar-plugin-center,.cb-top-bar-plugin-left,.cb-top-bar-plugin-right{
							'.$style_content_mobile.'
						}
					}
				</style>';
				$content_top_bar= '
					<div id="top-bar">
						<div class="cb-top-bar-flex-container">
							<div class="cb-top-bar-plugin-left">'.do_shortcode(html_entity_decode($options_content['left-content-top-bar-plugin'])).'</div>
							<div class="cb-top-bar-plugin-center">'.do_shortcode(html_entity_decode($options_content['center-content-top-bar-plugin'])).'</div>
							<div class="cb-top-bar-plugin-right">'.do_shortcode(html_entity_decode($options_content['right-content-top-bar-plugin'])).'</div>';
				$content_top_bar.=$user_can_close_top_bar ? '<div id="cb-top-bar-close"><a href="javascript:void(0)">X</a></div>':'';
				$content_top_bar.='</div></div>';
				echo $content_top_bar;
				break;
		}
	}
}
?>