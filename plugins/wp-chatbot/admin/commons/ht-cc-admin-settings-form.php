<?php
/**
 * sidebar in admin area - plugin settings page.
 *
 * @uses at settings_page.php
 *
 */

if (!defined('ABSPATH')) exit;
include(HTCC_PLUGIN_DIR . 'admin/contact_page.php');
$table = new MobileMonkey_Contacts_List_Table();
$table->prepare_items();
$curren = get_transient( 'current-tab' );
$tab = get_transient( 'done-tab' );
if ($tab){
    foreach ($tab as $key => $value) {
        if ($value == "true") {
            $tabs[$key] = 'done';
        } else {
            $tabs[$key]= '';
        }
    }
}else {
	$tabs[1] = "done current";
}
$tabs[$curren] .= ' current';

?>
<div class="step-wrapper">
    <div class="tab_header">
        <ul class="tabs_wrapper">
            <li class="tab-link <?php echo $tabs[1] ?>" data-tab="tab-1">
                <span class="tab_number">1</span>
                <span class="tab_header">Setup</span>
            </li>
            <li class="tab-link <?php echo $tabs[2] ?>" data-tab="tab-2">
                <span class="tab_number">2</span>
                <span class="tab_header">Customize</span>
            </li>
            <li class="tab-link <?php echo $tabs[3] ?>" data-tab="tab-3">
                <span class="tab_number">3</span>
                <span class="tab_header">Leads</span>
                <span class="tab_contacts__count"><?php echo $table->totalItems ?></span>
            </li>
            <li class="tab-link <?php echo $tabs[4] ?>" data-tab="tab-4">
                <span class="tab_number">4</span>
                <span class="tab_header">Chatbot Settings</span>
            </li>
            <li class="tab-link <?php echo $tabs[5] ?>" style="position: relative;" data-tab="tab-5">
                <span class="tab_header">Your Subscription</span>
                <?php

				$limit = [
					'limit' => $wp_plan_info->outgoing_messages_limit,
					'count' => $message_statistic->count,
                    'is_wp'=> $page_info['is_wp_subscribe'],
                    'subscribe'=> $subscribe_info,
                    'app_domain' => $app_domain,
				];
				HT_CC::view('ht-cc-admin-limit-tooltip', $limit); ?>
            </li>

        </ul>
        <div class="list_tabs__button">
            <ul class="list_tabs"></ul>
        </div>
    </div>
	<?php
	$mm_only ? $state = 'none' : $state = 'block';
	!$mm_only ? $mm = 'none' : $mm = 'block'; ?>
    <div id="tab-1" class="tab-content <?php echo $tabs[1] ?> setup_section">
        <div class="tab-content__wrapper">
            <form method="post" action="options.php" style="display: block">
				<?php
				settings_fields('htcc_as_setting_group');
				do_settings_sections('htcc-as-setting-section');
				?>

				<?php submit_button('Save Changes'); ?>
            </form>
			<?php
			$fb_connected_area_active_page_settings = [
				'connected_page' => $connected_page
			];
			HT_CC::view('ht-cc-admin-form-bottom-connect', $fb_connected_area_active_page_settings); ?>
        </div>
    </div>
    <div id="tab-2" class="tab-content customize_section <?php echo $tabs[2] ?>">
        <div class="tab-content__wrapper">
            <h1><?php _e('Customize') ?></h1>
            <form method="post" action="options.php">
				<?php
				settings_fields('htcc_custom_setting_group');
				do_settings_sections('wp-custom-settings-section');
				?>
				<?php submit_button('Save Changes'); ?>
            </form>

        </div>
    </div>
    <div id="tab-3" class="tab-content contact_tab <?php echo $tabs[3] ?>">
        <h1><?php _e('Leads') ?></h1>

        <p>To chat with your website visitors, go to your MobileMonkey inbox</p>
        <a target="_blank" href="<?php echo $app_domain ?>chatbot-editor/<?php echo $connected_page['bot_id']?>/live-chat" class="go-to-inbox-link">
            <img src="<?php echo plugins_url('admin/assets/img/live-chat.png',HTCC_PLUGIN_FILE)?>">
            Go to Inbox
        </a>

        <div class="contact_head__wrap">
            <h4><?php
				$text = $table->totalItems > 1 ? 'Leads' : 'Lead';
				if ($table->totalItems == 0) {
					$table->totalItems = '';
					$text = "No Leads ";
				}
				echo $table->totalItems
				?> <p><?php _e($text) ?><?php _e(' generated') ?></p></h4>
            <?php
            ?>
            <div class="download__wrap">
                <a id="csv" href="" style="pointer-events:<?php $subscribe_info?_e('all'):_e('none')?> "><i class="fa fa-download" aria-hidden="true"></i><?php _e("Download Leads");?></a>
                <div class="pro_button__wrapper" style="display: none">
                    <a href="#" class="pro_button__link">
                        <div class="pro_button">
                            <div class="pro_button__content">
                                <p><?php _e('Upgrade to unlock this feature') ?></p>
								<h3><?php _e('Get 50% off when you upgrade today.') ?></h3>
                            </div>
                            <div class="pro_button__action">
                                <span class="pro_button_action__text"><?php _e('Upgrade') ?></span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="table__wrap">
			<?php
			$table->display();
			?>
        </div>
        <div class="customization_button__wrapper">
            <a target="_blank" rel="noopener noreferrer" href="<?php echo $app_domain ?>chatbot-editor/<?php echo $connected_page['bot_id']?>/dashboard" class="customization_button__link">
                <div class="customization_button">
                    <div class="customization_button__content">More chatbot customization in <span class="customization_button__image"></span> MobileMonkey</div>
                    <div class="customization_button__action">
                        <span class="button_action__text">LEt's go</span>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div id="tab-4" class="tab-content chatbot_settings_tab <?php echo $tabs[4] ?>">
        <div class="tab-content__wrapper">
            <h1><?php _e('Chatbot Settings') ?></h1>
            <div class="chatbot_settings_tab__content">
                <p>
                    <input
                        type="radio"
                        <?php echo $chat_widget_channel == 'omnichat' ? 'checked="checked"' : '' ?>
                        onclick="return false;"
                    />
                    Support both Facebook Messenger & webchat (OmniChat enabled)
                    <a 
                       href="https://mobilemonkey.com/help/article/lceo0wtp0l-omni-chat"
                       target="_blank" rel="noopener noreferrer">
                        <i class="fa fa-question-circle-o"></i>
                    </a>
                </p>
                <p>
                    <input
                        type="radio"
                        <?php echo $chat_widget_channel == 'facebook' ? 'checked="checked"' : '' ?>
                        onclick="return false;"
                    />
                    Support only Facebook Messenger
                </p>
                <p>
                    <input  
                        type="radio"
                        <?php echo $chat_widget_channel == 'web_chat' ? 'checked="checked"' : '' ?>
                        onclick="return false;"
                    />
                    Support only webchat
                </p>
                <a target="_blank" href="<?php echo $app_domain ?>chatbot-editor/<?php echo $connected_page['bot_id']?>/settings/customer-chat-widget" class="go-to-chat-widget-settings-link">
                    Edit this setting
                </a>

                <?php if ($chat_widget_channel == 'omnichat') { ?>
                <div class="chatbot_settings_tab__info-box">
                    <i class="fa fa-question-circle-o"></i>
                    <div>
                        WP-Chatbot will show a Messenger chat widget if your website visitor is logged into Facebook. Otherwise, it will show a native webchat widget. Learn more about OmniChat
                        <a 
                            href="https://mobilemonkey.com/help/article/lceo0wtp0l-omni-chat"
                            target="_blank" rel="noopener noreferrer">
                            here</a>.
                    </div>  
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <div id="tab-5" class="tab-content subscribe_section <?php echo $tabs[5] ?>">
        <div class="tab-content__wrapper">
			<?php
			$plan = json_decode(json_encode($wp_plan_info), True);
			$fb_subscribe_info = [
                'account' => $account_info,
                'subscribe_info' => $subscribe_info,
                'message_statistic' => $message_statistic,
				'page' => [
                    'page_name'=>$page_info['pageName'],
                    'since'=>$page_info['connected_at'],
                    'is_wp' =>$page_info['is_wp_subscribe'],
				],
				'plan' => $wp_plan_info
			];
			HT_CC::view('ht-cc-admin-fb-subscription', $fb_subscribe_info); ?>
        </div>
    </div>
    <div id="to_pro" class="modal">
        <div class="modal_close">X</div>
        <div class="upgrade__wrapper">
            <div class="upgrade__content">
                <h4><?php _e('Are you sure that you want to disconnect this page?') ?></h4>
                <p><?php _e('Disconnecting will disable all chatbots on your Facebook page and remove the chat widget from your website.') ?></p>
            </div>
            <div class="upgrade__button">
                <a class="button-close-modal blues" href="#"><?php _e('Cancel') ?></a>
                <a href="<?php echo $connected_page['path']; ?>" id="disconnect"
                   class="button-lazy-load reds"><?php _e('Disconnect') ?>
                    <div class="lazyload"></div>
                </a>


            </div>
        </div>
    </div>
    <div id="cancel" class="modal">
        <div class="modal_close">X</div>
        <div class="cancel__wrapper">
            <div class="cancel__content">
                <h4><?php _e('Are you sure  you want to deactivate this subscription?') ?></h4>
                <p><?php _e('You will lose access to all Pro features once this subscription expires.') ?></p>
            </div>
            <div class="cancel__button">
                <a class="button-close-modal blues" href="#"><?php _e('Cancel') ?></a>
                <a href="#" id="cancel_sub"
                   class="button-lazy-load reds"><?php _e('Deactivate anyway') ?>
                    <div class="lazyload"></div>
                </a>


            </div>
        </div>
    </div>
    <div id="unsaved_option" class="modal">
        <div class="modal_close">X</div>
        <div class="unsaved__wrapper">
            <div class="unsaved__content">
                <h4><?php _e('Do you want to save your changes?') ?></h4>
            </div>
            <div class="unsaved__button">
                <a class="blues save_change button-lazy-load" href="#"><?php _e('Save') ?>
                    <div class="lazyload"></div>
                </a>
                <a href="#" id="discard_button" class="reds button-lazy-load"><?php _e('Discard') ?></a>
            </div>
        </div>
    </div>
	<div class="modal-overlays" id="modal-overlay">
	</div>
    <div id="pro_option" class="modal">
        <div class="modal_close"><i class="fa fa-times" aria-hidden="true"></i></div>
        <div class="mm__wrapper">
            <form class="checkout-form" id="checkout-form">
                <input type="hidden" data-recurly="token" name="recurly-token" />
                <div class="billing-modal-header">
                    <div class="billing-modal-header__logo">
                        <div class="logo"></div>
                        <span><?php _e('MobileMonkey') ?></span></div>
                    <div class="billing-modal-header__plan-name">WP-CHATBOT PRO</div>
                    <div class="billing-modal-header__plan-price">
                        <h4>$<?php _e(round(number_format(($plan['unit_amount_in_cents'] /100), 2, '.', ' ')/12))?><b>/month</b></h4>
                        <p class="billed"><?php _e("billed annually")?></p>
                        <div class="discount">
                            <p class="disc_cross"><?php _e('<b>$8/</b>month') ?></p>
                            <p><?php _e('Save 50% today') ?></p>
                        </div>
                    </div>

                    <div class="billing-page-details">
                        <div class="billing-page-details__left-section">
                            <div class="billing-page-details__middle">
                                <div class="billing-page-details__name">
                                    <?php _e($connected_page['name']) ?>
                                </div>
                                <div class="billing-page-details__sends-text">
                                </div>
                            </div>
                        </div>
                        <div class="billing-page-details__check-circle">
                            <i class="fa fa-check" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
                <div class="billing-modal-body">
                    <?php
                    if ($account_info){
                    ?>
                    <div class="billing_info_wrap">
                        <div class="payment_info">
                            <div class="payment_info_text"><?php _e('Payment Information')?></div>
                            <div class="billed_with"><?php _e('Billed with')?>: XXXX-XXXX-XXXX-<?php _e($account_info->last_card_numbers)?></div>
                        </div>
                    </div>
                    <?php
					}else{?>
                        <div class="name__wrap">
                            <div class="firstname__wrap">
                                <label for="firstname"><?php _e('FIRST NAME') ?></label>
                                <input type="text" id="firstname" data-recurly="first_name" required />
                            </div>
                            <div class="lastname__wrap">
                                <label for="lastname"><?php _e('LAST NAME') ?></label>
                                <input type="text" id="lastname" data-recurly="last_name" required />
                            </div>
                        </div>
                        <label for="email"><?php _e('EMAIL') ?></label>
                        <input type="email" id="email" required />
                        <div class="separator"></div>
                        <div class="form-field-wrap">
                        <label for="card_number"><?php _e('CARD INFO') ?></label>
                        <div class="card__wrap">
                            <div id="card_number" data-recurly="card"></div>
                        </div>
                        </div>
                        <div class="form-field-wrap">
                            <label for="country"><?php _e('COUNTRY')?></label>
                            <select name="country" id="country" data-recurly="country" required>
                            <?php foreach (HTCC_Countries::$contries as $k=>$v){
                                echo "<option value=".$v[0].">".$v[1]."</option>";
                            }?>
                            </select>
                        </div>
                        <div class="separator"></div>
                        <div class="form-field-wrap">
                            <label for="address1"><?php _e('ADDRESS LINE 1') ?></label>
                            <input type="text" id="address1" placeholder="Street address, P.O box, company name" data-recurly="address1" required />
                        </div>
                        <div class="form-field-wrap">
                            <label for="address2"><?php _e('ADDRESS LINE 2') ?></label>
                            <input type="text" id="address2"  placeholder="Apartment, suite, unit, building, floor, etc." data-recurly="address2" />
                        </div>
                        <div class="name__wrap">
                            <div class="form-field-wrap">
                                 <label for="city"><?php _e('CITY') ?></label>
                                 <input type="text" id="city"  data-recurly="city" required />
                            </div>
                            <div class="form-field-wrap">
                                 <label for="state"><?php _e('STATE') ?></label>
                                 <input type="text" id="state"  data-recurly="state" class="states-input"/>
                                 <select name="state" id="state" data-recurly="state" class="states-select" required>
                                  <?php foreach (HTCC_States::$states as $k=>$v){
                                  echo "<option value=".$v[1].">".$v[0]."</option>";
                                  } ?>
                               </select>
                            </div>
                            <div class="form-field-wrap">
                                 <label for="postal_code"><?php _e('ZIP CODE') ?></label>
                                 <input type="text" id="postal_code"  data-recurly="postal_code" required />
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <div class="billing-modal-footer">
					<?php
					if (!$account_info){
					?>
                    <div class="term__wrap">
                        <input type="checkbox" required>
                        <span class="terms-label"><?php _e('I have read and accept the ') ?> <b><a href="https://mobilemonkey.com/privacy-policy"><?php _e('Privacy Policy ') ?></a></b><?php _e('and ') ?> <b><a href="https://mobilemonkey.com/master-service-agreement"><?php _e('Terms of Service') ?></a></b></span>
                    </div>
						<?php
					}
					?>
                    <div id="errors"></div>
                    <button id="pay_plan" class="oranges">Confirm
                        <div class="lazyload"></div>
                    </button>
                </div>

            </form>
        </div>
    </div>
    <div id="promo_app" class="modal">
        <div class="modal_close"><i class="fa fa-times" aria-hidden="true"></i></div>
            <div class="promo-app__wrapper">
                <p>Download App</p>
              <a target="_blank" class="android_app" href='https://play.google.com/store/apps/details?id=com.mobilemonkey&pcampaignid=pcampaignidMKT-Other-global-all-co-prtnr-py-PartBadge-Mar2515-1'><img alt='Get it on Google Play' src='<?php echo plugins_url('admin/assets/img/get_it_on_google_play.png', HTCC_PLUGIN_FILE) ?>'/></a>
              <img src="<?php echo plugins_url('admin/assets/img/download_app_store.svg', HTCC_PLUGIN_FILE) ?>" alt="" class="ios_app">
            </div>
            <div class="ios-app__wrap">
                <p>Scan on your mobile device to view in the App Store</p>
                <div class="ios_code"></div>
            </div>
    </div>
    <div class="modal-overlays" id="modal-overlay">
    </div>

</div>
