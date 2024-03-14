<?php
/**
 * template for options page
 * @uses HTCC_Admin::settings_page
 * @since 1.0.0
 */

if (!defined('ABSPATH')) exit;
$options = get_option('htcc_options');
$options_as = get_option('htcc_as_options', array());
$options_custom = get_option('htcc_custom_options');
$api = $this->getApi();
$this->api->logoutMobilemonkey();
$token = $this->api->connectMobileMonkey();
$internal =  $this->api->getActivePageId();
$update = false;
$cg_notice =  get_transient( 'cg_notice_off' );
$hide_mobile_app_banner = get_transient( 'ht_cc_admin_sidebar__hide_mobile_app_banner' );
$main_css_classes = $hide_mobile_app_banner ? 'm12 x12' : 'm9 x9';
$chat_widget_channel = null;
if ($api->getActiveBotId()) {
	$bot = $this->api->getBotRef($api->getActiveBotId());
	$chat_widget_channel = $bot->chat_widget_channel;
}
?>

<!-- style="display: flex; flex-wrap: wrap;" -->
<div class="wrap">
    <div class="row mobile_wrap">
        <div class="col s12 <?php echo $main_css_classes ?>options ht-cc-admin__main">
            <div class="mobilemonkey-logo">
                <?php
                if ($api->getActiveBotId()){
                    $data_cg = [
						'bot_id'=> $api->getActiveBotId(),
						'app_domain' => $api->app_domain,
                    ];
                    if (!$api->isCommentGuard()&&$cg_notice!=true){
                        HT_CC::view('ht-cc-admin-comment-guard',$data_cg);
                    }
                }
                ?>
            </div>
            <h6 class="options-subtitle"><?php _e('WP-Chatbot is ') ?><a href="https://mobilemonkey.com/" target="_blank"><?php _e('powered by MobileMonkey') ?></a><?php _e(': an Official Facebook Messenger Solutions Provider Partner') ?></h6>
			<?php
			if ($token) {
				$reset = FALSE;
				if ($this->api->connectPage() || $this->api->disconnectPage()) {
					$reset = TRUE;
					exit;
				}
				$this->api->getSubscribeInfo();
				$activePage = $this->api->getActivePage($reset);
				$actpage = get_option("mobilemonkey_active_page_info");
				$previouslyConnected = get_transient('wp-chatbot__previously-connected-page');
				if ($actpage && $previouslyConnected) {
					$already_connected_settings_form_settings = [
						'connected_page' => $actpage,
						'app_domain' => $this->api->app_domain,
					];
					HT_CC::view('ht-cc-admin-already-connected-settings-form', $already_connected_settings_form_settings);
				}
				else if ($actpage) {
					if ($actpage['bot_id']){
						if ( isset($_REQUEST['settings-updated']) && $_REQUEST['settings-updated']){
						    $update = true;
							$current_language = $this->api->getLanguage($actpage['remote_id']);
							if (!empty($options_custom['fb_sdk_lang']) && $options_custom['fb_sdk_lang'] !== $current_language) {
								$this->api->updateLanguage($options_custom['fb_sdk_lang'], $actpage['remote_id']);
							}
						}
						$this->api->setWidgets($options_as,$actpage['remote_id'],$update);
						$this->api->setCustomChatSettings($actpage['remote_id'],$options_custom,$update);
					}else {
						echo "<style>.settings-error{display: none}</style>";
						$this->api->renderNotice('<p class="bot_disabled">Your chatbot has been disabled in MobileMonkey. Please reactivate it before making additional edits. Go <a target="_blank" rel="noopener noreferrer" href="' . $this->api->app_domain . 'chatbot-editor/">here</a> to reactivate your chatbot</p>');

					}
					$is_pro = $this->api->GetCurrentSubscription()? true : false;
					$fb_connected_area_active_page_settings = [
						'connected_page' => $actpage,
                        'is_pro' => $is_pro
					];
					$page_subscribe_info = [
                        'page_info'=> $this->api->getPageInfo(),
					    'account_info'=> $this->api->getAccountInfo(),
					    'subscribe_info'=> $this->api->getCurrentSubscription(),
					    'wp_plan_info'=> $this->api->getWpPlan(),
					    'message_statistic'=> $this->api->getMessageStatistic(),
						'connected_page' => $actpage,
						'chat_widget_channel' => $chat_widget_channel,
						'app_domain' => $this->api->app_domain,
                    ];
					HT_CC::view('ht-cc-admin-fb-button-connected', $fb_connected_area_active_page_settings);
					HT_CC::view('ht-cc-admin-settings-form',$page_subscribe_info);

				} else {
                    $pages = $this->api->getPages();
					if ($internal){
						echo "<style>.settings-error{display: none}</style>";
						$this->api->renderNotice('Your Facebook page has been disconnected in MobileMonkey. Please connect to a page to reactivate your chatbot.');
					}
					$fb_connected_area_pages_settings = [
						'pages' => $pages,
						'logout_path' => add_query_arg([
							'page' => HTCC_PLUGIN_MAIN_MENU,
							'logout' => true,
						], admin_url('admin.php')),
					];
					HT_CC::view('ht-cc-admin-fb-button-select-page', $fb_connected_area_pages_settings);
				}

			} else {

				HT_CC::view('ht-cc-admin-fb-button-not-connected', [
					'options' => $options,
					'path' => $this->getApi()->connectLink(),
				]);
			}

			?>

</div>
<?php if ($hide_mobile_app_banner != true)  { ?>

	<div class="col s12 m3 x3 ht-cc-admin-sidebar">
		<div class="side-bar">
			<div class="phone">
				<div class="mobile__wrapper">
					<div class="logo_promo"></div>
					<div class="text__wrap">
						<div class="dot_icon">
							<div class="white_circle"></div>
							<div class="green_circle"></div>
						</div>
						<div class="dialog_block">Want to chat with your leads while you’re on the go? Download our mobile app!</div>
					</div>
					<div class="button_download_app">Download App</div>
				</div>
				<div class="ht-cc-admin-sidebar__dismiss_button_container">
					<span class="button__wrapper_text dismiss_button"><i class="fa fa-times" aria-hidden="true"></i></span>
				</div>
			</div>
		</div>
	</div>
	</div>


	</div>
	<script>
		jQuery(document).ready(function($){
			$('.ht-cc-admin-sidebar .dismiss_button').on('click',function (e) {
				$.ajax({
				type: 'POST',
				url: ajax_obj.ajax_url,
				data: {
					action:'ht_cc_admin_sidebar__hide_mobile_app_banner',
          _ajax_nonce: ajax_obj.nonce,
        },
				dataType: 'json',
				success: function (data,response) {
					$('.ht-cc-admin-sidebar .dismiss_button').parents('.ht-cc-admin-sidebar').fadeOut(300, function(){ 
						$(this).remove();
						$('.ht-cc-admin__main').removeClass('m9 x9').addClass('m12 x12');
						});;

				}
				});
			});
		});

	</script>
<?php } ?>
