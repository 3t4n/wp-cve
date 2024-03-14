<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
global $foxtool_options;
# add css js chat web
function foxtool_enqueue_chat(){
	wp_enqueue_style('chat-css', FOXTOOL_URL . 'link/chat/chat.css', array(), FOXTOOL_VERSION);
}
add_action('wp_enqueue_scripts', 'foxtool_enqueue_chat');
# add chat on
function foxtool_add_chat(){
    global $foxtool_options;
	$defaultIcon = FOXTOOL_URL . 'img/chat/icon1.svg';
	if (isset($foxtool_options['chat-nut-ico'])) {
		switch ($foxtool_options['chat-nut-ico']) {
			case 'Icon2':
				$ico = FOXTOOL_URL . 'img/chat/icon2.svg';
				break;
			case 'Icon3':
				$ico = FOXTOOL_URL . 'img/chat/icon3.svg';
				break;
			case 'Icon4':
				$ico = FOXTOOL_URL . 'img/chat/icon4.svg';
				break;
			case 'Icon5':
				$ico = FOXTOOL_URL . 'img/chat/icon5.svg';
				break;
			default:
				$ico = $defaultIcon;
		}
	} else {
		$ico = $defaultIcon;
	}
	$radius = !empty($foxtool_options['chat-nut-rus']) ? 'style="border-radius:'. $foxtool_options['chat-nut-rus'] .'px;"' : NULL;
	if (isset($foxtool_options['chat-nut1'])){
	?>
    <div class="ft-chatbox">
	<div class="ft-chaton" id="ft-chaton" style="display:none">
		<div class="ft-chaton-scroll">
        <?php
        for ($i = 1; $i <= 10; $i++) {
            if (isset($foxtool_options['chat-nut1'. $i])) {
                $chat_option = $foxtool_options['chat-nut1'. $i];
				$chat_text = isset($foxtool_options['chat-nut2'. $i]) ? $foxtool_options['chat-nut2'. $i] : __('Not entered', 'foxtool');
                $chat_value = isset($foxtool_options['chat-nut3'. $i]) ? $foxtool_options['chat-nut3'. $i] : NULL;
				$chat_new = isset($foxtool_options['chat-nut-new']) ? 'target="_blank"' : NULL;

                switch ($chat_option) {
                    case 'Phone':
                        echo '<a rel="nofollow noopener sponsored" title="Phone" href="tel:'. $chat_value .'"><img alt="Phone" src="'. FOXTOOL_URL . 'img/chat/phone.svg' .'">'. $chat_text .'</a>';
                        break;
					case 'SMS':
                        echo '<a rel="nofollow noopener sponsored" title="SMS" href="sms:'. $chat_value .'"><img alt="SMS" src="'. FOXTOOL_URL . 'img/chat/sms.svg' .'">'. $chat_text .'</a>';
                        break;
					case 'Messenger':
                        echo '<a '. $chat_new .' rel="nofollow noopener sponsored" title="Messenger" href="https://m.me/'. $chat_value .'"><img alt="Messenger" src="'. FOXTOOL_URL . 'img/chat/messenger.svg' .'">'. $chat_text .'</a>';
                        break;
					case 'Telegram':
                        echo '<a '. $chat_new .' rel="nofollow noopener sponsored" title="Telegram" href="https://telegram.me/'. $chat_value .'"><img alt="Telegram" src="'. FOXTOOL_URL . 'img/chat/telegram.svg' .'">'. $chat_text .'</a>';
                        break;
					case 'Zalo':
                        echo '<a '. $chat_new .' rel="nofollow noopener sponsored" title="Zalo" href="https://zalo.me/'. $chat_value .'"><img alt="Zalo" src="'. FOXTOOL_URL . 'img/chat/zalo.svg' .'">'. $chat_text .'</a>';
                        break;
					case 'Whatsapp':
                        echo '<a '. $chat_new .' rel="nofollow noopener sponsored" title="Whatsapp" href="https://wa.me/'. $chat_value .'"><img alt="Whatsapp" src="'. FOXTOOL_URL . 'img/chat/whatsapp.svg' .'">'. $chat_text .'</a>';
                        break;
					case 'Mail':
                        echo '<a rel="nofollow noopener sponsored" title="Mail" href="mailto:'. $chat_value .'"><img alt="Mail" src="'. FOXTOOL_URL . 'img/chat/mail.svg' .'">'. $chat_text .'</a>';
                        break;
					case 'Maps':
                        echo '<a '. $chat_new .' rel="nofollow noopener sponsored" title="Maps" href="https://www.google.com/maps/search/'. $chat_value .'"><img alt="Maps" src="'. FOXTOOL_URL . 'img/chat/maps.svg' .'">'. $chat_text .'</a>';
                        break;
                }
            }
        }
        ?>
		</div>
    </div>
	  <button <?php echo $radius; ?> title="<?php _e('Contact', 'foxtool'); ?>" id="chatona" onclick="ftnone(event, 'ft-chaton')"><img src="<?php echo $ico; ?>" /></button>
	</div>
<?php
		$cor = !empty($foxtool_options['chat-nut-color']) ? '.ft-chatbox #chatona{background:'. $foxtool_options['chat-nut-color'] .';}@keyframes logo {0% {box-shadow: 0 0 0 0px '. $foxtool_options['chat-nut-color'] .',0 0 0 0px '. $foxtool_options['chat-nut-color'] .';} 50% {transform: scale(0.8);} 100% {box-shadow: 0 0 0 15px rgba(0,210,255,0),0 0 0 30px rgba(0,210,255,0);}}' : NULL;
		$lr1 = !empty($foxtool_options['chat-nut-lr']) && isset($foxtool_options['chat-nut-mar']) && $foxtool_options['chat-nut-mar'] != 'Right' ? '.ft-chatbox{left:'. $foxtool_options['chat-nut-lr'] .'px;}' : NULL;
		$lr2 = !empty($foxtool_options['chat-nut-lr']) && isset($foxtool_options['chat-nut-mar']) && $foxtool_options['chat-nut-mar'] == 'Right' ? 'right:'. $foxtool_options['chat-nut-lr'] .'px;' : NULL;
		$mar = isset($foxtool_options['chat-nut-mar']) && $foxtool_options['chat-nut-mar'] == 'Right' ? '.ft-chatbox{'. $lr2 .'left:auto;text-align:right;}.ft-chaton::after{right:0;left:auto;margin-right:10px;}' : NULL;
		if(!empty($foxtool_options['chat-nut-bot']) && $foxtool_options['chat-nut-bot'] < 300 ){
			$bot = '.ft-chatbox{bottom:'. $foxtool_options['chat-nut-bot'] .'px;}';
		} elseif (!empty($foxtool_options['chat-nut-bot']) && $foxtool_options['chat-nut-bot'] >= 300 ){
			$bot = '.ft-chatbox{bottom:50%;}';
		} else { 
		    $bot = NULL; 
		}
		$ope = !empty($foxtool_options['chat-nut-op']) ? '.ft-chatbox #chatona{opacity:'. $foxtool_options['chat-nut-op'] .';}' : NULL;
		if(!empty($foxtool_options['chat-nut-mar'])){
		echo '<style>'. $cor . $lr1 . $mar . $bot . $ope .'</style>';
		}
		if(isset($foxtool_options['chat-tawk1']) && !empty($foxtool_options['chat-tawk11'])){
		echo $foxtool_options['chat-tawk11'];	
		}
	}
}
add_action('wp_footer', 'foxtool_add_chat');