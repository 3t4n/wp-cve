<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
	if ( !empty(w2w_get_option( 'opt-enable-contact-button' )) && !empty(w2w_get_option( 'opt-enable-contact-button' )) ) {
		add_action( 'wp_footer', 'w2w_RenderContactButton' );
	}	
		
	function w2w_RenderContactButton() {
		// Ignore admin, feed, robots or trackbacks
		if ( is_admin() || is_feed() || is_robots() || is_trackback() ) {
			return;
		}
		$w2w_classes = array();
	
		//$w2w_classes = implode(' ', w2w_get_option( 'fs-style' )['opt-button-style']);
		$w2w_classes = w2w_get_option( 'fs-style' )['opt-button-style'];
		
		$html = '
			<style>
				.w2w-pinkBg {
					
					background-image: linear-gradient(90deg, ' . w2w_get_option( 'fs-style' )['opt-button-color']['color-1'] .', ' . w2w_get_option( 'fs-style' )['opt-button-color']['color-2'] .');
				}
			</style>
			<div id="w2w-widget-flyout" class="pos-' . $w2w_classes . ' w2w-pos-fixed w2w-pinkBg">
				
				<span class="w2w-button">
					<i class="' . w2w_get_option( 'fs-style' )['opt-icon'] .'"></i>
					<span class="ripple w2w-pinkBg"></span>
					<span class="ripple w2w-pinkBg"></span>
					<span class="ripple w2w-pinkBg"></span>
				</span>
				
				<ul class="w2w-nav-panel">';
				if(!empty(w2w_get_option( 'txtEmail' ))){
					$html .= '<li class="btn-email"><span class="w2w-icon"><i class="fas fa-envelope"></i></span><a href="mailto:' . w2w_get_option( 'txtEmail' ) . '" target="_blank"><span class="w2w-text">' . w2w_get_option( 'txtEmail' ) . '</span></a>
					</li>';
				}
				if( !empty(w2w_get_option( 'gr-phone' )[0]['txtName']) && !empty(w2w_get_option( 'gr-phone' )[0]['txtPhoneNumber']) ){
					$html .= '<li class="btn-phone"><span class="w2w-icon"><i class="fab fa-whatsapp"></i></span><a href="tel:' . w2w_get_option( 'gr-phone' )[0]['txtPhoneNumber'] . '"><span class="w2w-text">' . w2w_get_option( 'gr-phone' )[0]['txtName'] . ': ' . w2w_get_option( 'gr-phone' )[0]['txtPhoneNumber'] . '</span></a>
					</li>';
				}
				if(!empty(w2w_get_option( 'txtFBMessenger' ))){
					$html .= '<li class="btn-facebook"><span class="w2w-icon"><i class="fab fa-facebook-messenger"></i></span><a href="https://m.me/' . w2w_get_option( 'txtFBMessenger' ) . '" target="_blank"><span class="w2w-text">Messenger</span></a>
					</li>';
				}
				if(!empty(w2w_get_option( 'txtZalo' ))){
					$html .= '<li class="btn-zalo"><span class="w2w-icon"><span class="icon-zalo"></span></span><a href="//zalo.me/' . w2w_get_option( 'txtZalo' ) . '" target="_blank"><span class="w2w-text">Zalo: ' . w2w_get_option( 'txtZalo' ) . '</span></a>
					</li>';
				}
		$html .='
				</ul>
			</div>';
		echo $html;
	}