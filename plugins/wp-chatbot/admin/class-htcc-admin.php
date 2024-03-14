<?php
/**
 * Creates top level menu
 * and options page
 *
 * @package htcc
 * @subpackage admin
 * @since 1.0.0
 *
 */

if (!defined('ABSPATH')) exit;

if (!class_exists('HTCC_Admin')) :

	class HTCC_Admin
	{

		private $api;
		private $fb_page_id;
		private $options;
		private $botid;
		private $token;
		private $test;
		private $internal;
		private $stepdis;
		private $email_block;

		public function __construct()
		{
			$this->api = new MobileMonkeyApi();
			$this->token = $this->api->connectMobileMonkey();
			$this->options_as = get_option('htcc_as_options');
			$this->fb_page_id = $this->api->getActiveRemotePageId();
			$this->botid = $this->api->getActiveBotId();
			$this->internal = $this->api->getActivePage();
			$this->stepdis = "close";
		}

		private function getApi()
		{
			return $this->api;
		}

		/**
		 * Adds top level menu -> WP CSS Shapes
		 *
		 * @uses action hook - admin_menu
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function htcc_options_page()
		{
			$notification = '!';
			add_menu_page(
				'WP-Chatbot Setting page',
				!$this->fb_page_id || !$this->token || !$this->internal ? sprintf('WP-Chatbot <span class="awaiting-mod">%s</span>', $notification) : '<span data-tab="tab-1">WP-Chatbot</span>',
				'manage_options',
				'wp-chatbot',
				array($this, 'settings_page'),
				'dashicons-format-chat'
			);

			if ($this->fb_page_id && $this->token && $this->internal){
				if(get_transient('wp-chatbot__previously-connected-page')) {
					return;
				}
				add_submenu_page(
					'wp-chatbot',
					'wp-chatbot',
					'<span data-tab="tab-1">Setup</span>',
					'manage_options',
					'wp-chatbot',
					array($this, 'settings_page')
				);
                add_submenu_page(
                    'wp-chatbot',
                    'Customize',
                    '<span data-tab="tab-2">Customize</span>',
                    'manage_options',
                    '',
                    ''
                );
                add_submenu_page(
                    'wp-chatbot',
                    'Contacts',
                    '<span data-tab="tab-3">Leads</span>',
                    'manage_options',
                    '',
                    ''
                );
		            add_submenu_page(
			            'wp-chatbot',
			            'Chatbot Settings',
			            '<span data-tab="tab-4">Chatbot Settings</span>',
			            'manage_options',
			            '',
			            ''
		            );
                add_submenu_page(
                    'wp-chatbot',
                    'Your Subscription',
                    '<span data-tab="tab-5">Your Subscription</span>',
                    'manage_options',
                    '',
                    ''
                );
			}

		}
		/**
		 * Incomplete Setup Notification
		 *
		 * @uses action hook - admin_init
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function htcc_incomplete_setup(){
			if (!$this->fb_page_id || !$this->token || !$this->internal){
				add_action( 'admin_bar_menu', function( \WP_Admin_Bar $bar )
				{
					$bar->add_menu( array(
						'id'     => 'wp-chatbot',
						'title'  => '<span class="ab-icon chat-bot"></span>',
						'parent' => 'top-secondary',
						'href'   => admin_url( 'admin.php?page=wp-chatbot' ),
						'meta'   => array(
							'target'   => '_self',
							'title'    => __( 'Wp-Chatbot', 'htcc_plugin' ),
							'html'     => '',
						),
					) );
				}, 210);
			}

		}
		public function example_admin_notice() {
            $delay = get_transient( 'banner_notice_off' );
			if (!$this->fb_page_id || !$this->token || !$this->internal){
			    if ($delay!=true){
				    HT_CC::view('ht-cc-admin-notice-not-connected');
                }
			}
		}
		public function new_leads() {
			if ($this->fb_page_id && $this->token && $this->internal){
                $notice = get_transient( 'lead_notice_off' );
                $contacts = $this->api->getContacts();
                $count = 0;
                if (isset($contacts)){
                    foreach ($contacts as $contact){
                        $date = new DateTime();
                        $match_date = new DateTime($contact->created_at);
                        $interval = $match_date->diff($date);
                        $day = $interval->format('%r%a');
                        if ($day<=3&&$day>=0){
                            $count +=1;
                        }
                    }

				}
			    if ($count>=2&&!$this->options_as['email']){
			        if ($notice != true ){
						$this->api->notice = true;
                        $leads_info = [
							'header_text' => "You had  $count  new leads on your website in the last 3 days. ",
							'p_text'=>"Set up notifications in WP-Chatbot to get an email each time a new lead is detected!",
							'button_text'=>"SET UP NOTIFICATION",
                            'type'=>'lead'
                        ];
                        HT_CC::view('ht-cc-admin-top-banner',$leads_info);
                    }
                }
			}

		}
		public function mobile_promo() {
			if ($this->fb_page_id && $this->token && $this->internal) {
				if ($this->api->notice != true){
					$notice = get_transient( 'promo_notice_off' );
					if ($notice != true ) {
						$leads_info = [
							'header_text' => "Heard about our mobile app?",
							'p_text' => " Install the app now to respond to customers straight from your phone",
							'button_text' => "Download App",
							'type' => 'promo'
						];
						HT_CC::view('ht-cc-admin-top-banner', $leads_info);
					}

			    }
			}
		}
		/**
		 * Options page Content -
		 *   get settings form from a template settings_page.php
		 *
		 * Call back from - $this->htcc_options_page, add_menu_page
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function settings_page()
		{

			if (!current_user_can('manage_options')) {
				return;
			}

			// get options page form
			require_once('settings_page.php');
		}

	  public function get_tab_done() {
	    check_ajax_referer('htcc_nonce');
	    if(!current_user_can('manage_options')) {
		    wp_die('Unauthorized', 403);
	    }

	    $response = array( 'done' => true );
		  $tab      = get_transient( 'done-tab' );
		  $response = $tab;
		  wp_send_json_success( $response );
	  }

	  public function notice_lead_off() {
		  check_ajax_referer( 'htcc_nonce' );
		  if ( ! current_user_can( 'manage_options' ) ) {
			  wp_die( 'Unauthorized', 403 );
		  }

		  $response = array( 'done' => true );
		  set_transient( 'lead_notice_off', true, WEEK_IN_SECONDS * 2 );
		  wp_send_json_success( $response );
	  }

	  public function notice_promo_off() {
		  check_ajax_referer( 'htcc_nonce' );
		  if ( ! current_user_can( 'manage_options' ) ) {
			  wp_die( 'Unauthorized', 403 );
		  }

		  $response = array( 'done' => true );
		  set_transient( 'promo_notice_off', true );
		  wp_send_json_success( $response );
	  }
		public function ht_cc_admin_sidebar__hide_mobile_app_banner()
		{
      check_ajax_referer('htcc_nonce');
      if(!current_user_can('manage_options')) {
        wp_die('Unauthorized', 403);
      }

      $response= array('done'=>true);
			set_transient( 'ht_cc_admin_sidebar__hide_mobile_app_banner', true);
			wp_send_json_success ($response);
		}

        public function pre_val(){
			set_transient( 'pre_value', false, YEAR_IN_SECONDS );
			$response= array('done'=>true);
			wp_send_json_success ($response);
        }

		public function set_tab_done(){
			$tab = get_transient( 'done-tab' );
			$resp_tab = $_GET['state']+$tab;
			$response = $resp_tab;
			set_transient( 'done-tab', $resp_tab, YEAR_IN_SECONDS );
			wp_send_json_success ( $response);
		}

	  public function set_current_tab() {
	    check_ajax_referer('htcc_nonce');
	    if(!current_user_can('manage_options')) {
		    wp_die('Unauthorized', 403);
	    }

	    set_transient( 'current-tab', preg_replace( '/[^0-9]/', '', $_POST['current'] ), YEAR_IN_SECONDS );
		  wp_send_json_success();
	  }

	  public function banner_off() {
	    check_ajax_referer('htcc_nonce');
	    if(!current_user_can('manage_options')) {
		    wp_die('Unauthorized', 403);
	    }

	    $response = array( 'done' => true );
		  set_transient( 'banner_notice_off', true, WEEK_IN_SECONDS * 2 );
		  wp_send_json_success( $response );
	  }

	  public function cg_off() {
		  $response = array( 'done' => true );
		  set_transient( 'cg_notice_off', true, WEEK_IN_SECONDS * 2 );
		  wp_send_json_success( $response );
	  }


		/**
		 * Options page - Regsiter, add section and add setting fields
		 *
		 * @uses action hook - admin_init
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function htcc_custom_settings()
		{

			add_settings_section('htcc_settings_as', '', array($this, 'htcc_section_as_render'), 'htcc-as-setting-section');

			add_settings_field('htcc_fb_welcome_message', '', array($this, 'htcc_fb_welcome_message_cb'), 'htcc-as-setting-section', 'htcc_settings_as');
			add_settings_field('htcc_fb_as_state',  '', array($this, 'htcc_fb_as_state_cb'), 'htcc-as-setting-section', 'htcc_settings_as');
			add_settings_field('htcc_fb_answer','', array($this, 'htcc_fb_answer_cb'), 'htcc-as-setting-section', 'htcc_settings_as');
			add_settings_field('htcc_fb_question','', array($this, 'htcc_fb_question_cb'), 'htcc-as-setting-section', 'htcc_settings_as');
			add_settings_field('htcc_fb_email_trans', '', array($this, 'htcc_fb_email_trans_cb'), 'htcc-as-setting-section', 'htcc_settings_as');
			add_settings_field('htcc_fb_thank_answer', '', array($this, 'htcc_fb_thank_answer_cb'), 'htcc-as-setting-section', 'htcc_settings_as');
			add_settings_field('htcc_fb_qq','', array($this, 'htcc_fb_qa_cb'), 'htcc-as-setting-section', 'htcc_settings_as');
			register_setting('htcc_as_setting_group', 'htcc_as_options', array($this, 'htcc_as_options_sanitize'));



			add_settings_section('htcc_custom_settings', '', array($this, 'print_additional_settings_section_info'), 'wp-custom-settings-section');
			add_settings_field('htcc_fb_color', __('Color', 'wp-chatbot'), array($this, 'htcc_fb_color_cb'), 'wp-custom-settings-section', 'htcc_custom_settings');
			add_settings_field('htcc_fb_greeting_login', __('Logged in Greeting', 'wp-chatbot'), array($this, 'htcc_fb_greeting_login_cb'), 'wp-custom-settings-section', 'htcc_custom_settings');
			add_settings_field('htcc_fb_greeting_logout', __('Logged out Greeting', 'wp-chatbot'), array($this, 'htcc_fb_greeting_logout_cb'), 'wp-custom-settings-section', 'htcc_custom_settings');
			add_settings_field('htcc_fb_greeting_dialog_display', __('Greeting Dialog Display<span class="pro">PRO</span>', 'wp-chatbot'), array($this, 'htcc_fb_greeting_dialog_display_cb'), 'wp-custom-settings-section', 'htcc_custom_settings');
			add_settings_field('htcc_fb_greeting_dialog_delay', __('Greeting Dialog Delay<span class="pro">PRO</span>', 'wp-chatbot'), array($this, 'htcc_fb_greeting_dialog_delay_cb'), 'wp-custom-settings-section', 'htcc_custom_settings');
			add_settings_field('htcc_fb_sdk_lang', __('Messenger language', 'wp-chatbot'), array($this, 'htcc_fb_sdk_lang_cb'), 'wp-custom-settings-section', 'htcc_custom_settings');
			add_settings_field('htcc_show_hide', __('Hide Based on post type', 'wp-chatbot'), array($this, 'htcc_show_hide_post_types_cb'), 'wp-custom-settings-section', 'htcc_custom_settings');
			add_settings_field('htcc_list_id_tohide', __('Post, Page IDs to Hide', 'wp-chatbot'), array($this, 'htcc_list_id_tohide_cb'), 'wp-custom-settings-section', 'htcc_custom_settings');
			add_settings_field('htcc_list_cat_tohide', __('Categories to Hide', 'wp-chatbot'), array($this, 'htcc_list_cat_tohide_cb'), 'wp-custom-settings-section', 'htcc_custom_settings');
			add_settings_field('htcc_devices_show_hide', __('Hide Based on Devices', 'wp-chatbot'), array($this, 'htcc_show_hide_devices_cb'), 'wp-custom-settings-section', 'htcc_custom_settings');
			add_settings_field('htcc_shortcode', __('Shortcode name', 'wp-chatbot'), array($this, 'htcc_custom_shortcode_cb'), 'wp-custom-settings-section', 'htcc_custom_settings');
			register_setting('htcc_custom_setting_group', 'htcc_custom_options', array($this, 'htcc_custom_options_sanitize'));

			register_setting('htcc_setting_group', 'htcc_options', array($this, 'htcc_options_sanitize'));
		}


		function print_additional_settings_section_info() {
			?>
			<?php
		}
		function htcc_section_as_render() {
			?>
			<?php
		}
		public function htcc_fb_qa_cb(){
			$htcc_as_options = get_option('htcc_as_options');
            $html='';
			$i=1;
			$this->email_block = false;
			foreach ($htcc_as_options as $key=>$value){
				$span='';
				$inp='';
				$ans='';
				$y=1;
				if (strpos($key, 'qa_')!==false){
					$this->email_block = true;
                    $html.= '  <h3>Q&A '.$i.'</h3>
					<div class="main-qa" id="main_qa_'.$i.'">
                        <div class="qa-question__wrap">
                            <div class="qa-question_input">
                                <h6>If user says something similar to</h6>
                                <div class="question_button_wrap">
                                    <input type="text" placeholder="e.g.&quot;Home&quot;,&quot;prices&quot;,etc." autocomplete="off">
                                    <div class="add_qa_question">Add</div>
                                </div>
                            </div>';
				    foreach ($value as $k=>$v){
				        if ($k=='phrases' && !empty($v)){
				            foreach ($v as $ke=>$va){
								$span.= '<div class="qa-question-block-item"><span class="qa-question-result" data-index="'.$y.'">'.$va.'</span><div class="edit_qa" data-index="'.$y.'"><i class="fa fa-pencil" aria-hidden="true"></i></div><span class="qa__bin" data-index="'.$y.'"><i class="fa fa-trash-o" aria-hidden="true"></i></span></div>';
								$inp.= '<input type="hidden" name="htcc_as_options[qa_'.$i.'][phrases][]" value="'.$va.'" id="htcc_qa_'.$i.'_'.$y.'">';
								$y++;
							}
                        }elseif($k=='bot_responses'){
				            $ans.= '<input name="htcc_as_options[qa_'.$i.'][bot_responses]" value="'.$v.'" autocomplete="off" id="htcc_qa_'.$i.'_answer" type="text" placeholder="Enter the answer here">';
                        }
                    }
					$i++;
				    $html.='<div class="qa-question-block">
                                '.$span.' </div> <div class="qa-question_value"> '.$inp.' </div> <div class="qa-input__wrapper" style="display: none;"> <span class="triangle"></span> <div class="qa-input__item"> <input type="text" id="qa-state"> </div> <div class="qa-input__state"> <span class="qa_cancel">Cancel</span> <span class="qa_submit">OK</span> </div> </div> </div> <div class="qa-response"> <h6>Wp-chatbot will respond with</h6> '.$ans.' </div>
                            <div class="del_qa">
                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                        </div>
                      ';
				    $html.= '</div>';
                }
            }

		   ?>
            <div class="input-field col s12 qa-wrapper">
                <h3 class="qa_head"><?php _e('Q&A', 'wp-chatbot') ?></h3>
                <p class="qa_p"><?php _e('WP-Chatbot will answer questions on your page based on keywords detected in the user’s question.', 'wp-chatbot') ?></p>
                <div class="qa_new-wrapper">
			        <?php
                    if (!$htcc_as_options['advanced_triggers_present']||$this->api->getCurrentSubscription()) {
						echo $html;
					}
                    ?>
                </div>
            </div>
            <?php $trigger = $htcc_as_options['advanced_triggers_present']&&!$this->api->getCurrentSubscription()? "disabled":""; ?>
            <div class="qa-button__add">
                <span class="add_qa <?php echo $trigger?>" ><b>+</b> Add Q&A</span>
                <div class="pro_button__wrapper" style="opacity: 1; display: none;"><a href="#" class="pro_button__link"><div class="pro_button"><div class="pro_button__content"><p>Upgrade to unlock this feature</p><h3>Get <b>50% off</b> when you upgrade today.</h3></div><div class="pro_button__action"><span class="pro_button_action__text">Upgrade</span></div></div></a></div>
            </div>
            <?php if ($htcc_as_options['advanced_triggers_present']){?>
                <div class="have_qa">
                    <a target="_blank" href="<?php echo $this->api->app_domain ?>chatbot-editor/<?php echo $this->internal['bot_id'] ?>/trigger" >You have more advanced Q&As created in MobileMonkey. Go to MobileMonkey to edit those Q&As</a>
                </div>
		    <?php
		    }
        }
        public function htcc_fb_question_cb(){
			$htcc_as_options = get_option('htcc_as_options');
            if ($htcc_as_options['answering_service_mm_only_mode']==false) {
                $html = '';
                $i = 1;
                $this->email_block = false;
                foreach ($htcc_as_options as $key => $value) {
                    $span = '';
                    $inp = '';
                    $y = 1;
                    if (strpos($key, 'lq_') !== false) {
                        $this->email_block = true;
                        $html .= '<div class="main-question" id="main_question_' . $i . '"><h3>QUESTION ' . $i . '</h3><div class="question-block__wrapper"><div class="question-block__header"><div class="header__close"></div></div><div class="question-block_content">';
                        foreach ($value as $k => $v) {
                            if ($k == 'question') {
                                $html .= ' <div class="question-input__wrapper">
                            <div class="edit"><i class="fa fa-pencil" aria-hidden="true"></i></div><div class="question-input__item"><input id="htcc-q' . $i . '" name="htcc_as_options[lq_' . $i . '][question]" value="' . $htcc_as_options['lq_' . $i . '']['question'] . '" type="text"></div>
                            <div class="question-input__state">
                                <span class="question_cancel">Cancel</span><span class="question_submit">OK</span>
                            </div>
                        </div>';
                            } else {
                                $span .= '<div class="answer-item__result"><span class="answer__result" data-index="' . $y . '">' . $htcc_as_options['lq_' . $i . '']['answers' . $y . '']['answer'] . '</span><div class="edit_answer" data-index="' . $y . '"><i class="fa fa-pencil" aria-hidden="true"></i></div><span class="answer__bin" data-index="' . $y . '"><i class="fa fa-trash-o" aria-hidden="true"></i></span></div>';
                                $inp .= '<input id="htcc-answer_' . $i . '_' . $y . '" name="htcc_as_options[lq_' . $i . '][answers' . $y . '][answer]" value="' . $htcc_as_options['lq_' . $i . '']['answers' . $y]['answer'] . '" type="hidden">';
                                $val = isset($htcc_as_options['lq_' . $i]['answers' . $y]['qualified']) ? 1 : 0;
                                $inp .= '<input id="qualified_answer_' . $i . '_' . $y . '" name="htcc_as_options[lq_' . $i . '][answers' . $y . '][qualified]" value="' . $val . '" type="hidden">';
                                $y++;
                            }

                        }
                        $i++;
                        $html .= '<div class="answer-input__block">
                            <div class="answer-input__button">
                                ' . $span . '
                            </div>
                            <div class="answer-input__add">
                                <span class="add__answer" data-index="1"><b>+</b> Add answer</span>
                            </div>
                            <div class="answer-input__value">' . $inp . '</div>
                            <div class="answer-input__wrapper">
                                <span class="triangle"></span>
                                <div class="answer-input__item">
                                    <input type="text" id="answer-state">
                                    <input type="checkbox" id="qualified">
                                    <p>Mark as qualified answer</p>
                                </div>
                                <div class="answer-input__state">
                                    <span class="answer_cancel">Cancel</span><span class="answer_submit">OK</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>';
                        $html .= '</div>';
                    }
                }

                ?>
                <div class="input-field col s12 questions-wrapper">
                    <h3><?php _e('Lead Qualifier', 'wp-chatbot') ?></h3>
                    <p class="qual_p"><?php _e('Add a lead qualifying questions with multiple choice answers, and we`ll notify you as soon as qualified lead is detected', 'wp-chatbot') ?></p>
                    <div class="question_new-wrapper">
                        <?php echo $html; ?>
                    </div>
                </div>
                <div class="question-button__add">
                    <span class="add_question"><b>+</b> Add Question</span>
                </div>
                <?php
            }
        }

		// color - next new version added ..
		public function htcc_fb_color_cb_old()
		{
			$htcc_fb_color = get_option('htcc_custom_options');
			?>

            <div class="row">
                <div class="input-field col s12">

                    <!-- <input name="htcc_custom_options[fb_color]" data-default-color="#26a69a" value="<?php echo esc_attr($htcc_fb_color['fb_color']) ?>" type="text" class="htcc-color-wp" style="height: 1.375rem;" > -->

                    <input id="htcc-color-wp" class="htcc-color-wp" name="htcc_custom_options[fb_color]"
                           value="<?php echo esc_attr($htcc_fb_color['fb_color']) ?>" type="text"
                           style="height: 1.375rem;">
                    <p class="description"><?php _e('Messenger theme color; leave empty for default color', 'wp-chatbot') ?>
                        <a target="_blank"
                           href="https://mobilemonkey.com/wp-chatbot/messenger-theme-color/"><?php _e('more info', 'wp-chatbot') ?></a>
                    </p>
                </div>
            </div>
			<?php
		}

		// color
		public function htcc_fb_color_cb()
		{

			$htcc_fb_color = get_option('htcc_custom_options');
			?>
            <label for="htcc-color-wp" class="gray"> <?php _e('Messenger theme color; leave empty for default color', 'wp-chatbot') ?>
                <a target="_blank"
                   href="https://mobilemonkey.com/wp-chatbot/messenger-theme-color/"><?php _e(' - more info', 'wp-chatbot') ?></a>  </label>
            <div class="row">
                <div class="input-field col s12">
                    <!-- <input name="htcc_custom_options[fb_color]" value="<?php echo esc_attr($htcc_fb_color['fb_color']) ?>" type="color" class="htcc-color-wp" style="width: 5rem; height: 1.5rem;" > -->
                    <input name="htcc_custom_options[fb_color]" id="htcc-color-wp" value="<?php echo esc_attr($htcc_fb_color['fb_color']) ?>"
                           type="text" class="htcc-color-wp" style="height: 1.375rem;">

                    <!-- <p class="description"><?php _e('please open settings page in the browser that supports "type color", we are planning to make a better way to choose the color ', 'wp-chatbot') ?></p> -->
                </div>
            </div>
			<?php
		}


		// Welcome message
		public function htcc_fb_welcome_message_cb()
		{
			$htcc_fb_welcome_message = get_option('htcc_as_options');
			$ref = get_option('htcc_fb_ref');
			$htcc_fb_app_id = get_option('mobilemonkey_environment');
            if ($htcc_fb_welcome_message['answering_service_mm_only_mode']==false) {
				?>
                <p class="head_text welcome_text"><?php _e('WELCOME MESSAGE', 'wp-chatbot') ?></p>
                <div class="row">
                    <div class="test_button__wrap">
                        <div class="test-bot-button" style="display: <?php echo $this->test; ?>">
                            <div class="test-bot-button__button-wrapper">
                                <div class="test-bot-button__messenger">
                                    <div class="fb-send-to-messenger"
                                         messenger_app_id="<?php echo $htcc_fb_app_id->fb_app_id; ?>"
                                         page_id="<?php echo $this->fb_page_id; ?>"
                                         data-ref="<?php echo $ref; ?>"
                                         color="blue"
                                         size="large">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a target="_blank" rel="noopener noreferrer" style="display: none"
                           href="https://www.m.me/<?php echo $this->fb_page_id ?>" id="messanger"
                           class="button testchat">Open Messenger</a>
                    </div>
                    <div class="input-field col s12">
                        <label for="fb_welcome_message"><?php _e('Welcome message - WP-Chatbot will greet your chat users with this message.', 'wp-chatbot') ?></label>
                        <textarea rows="2" style="width:100%" name="htcc_as_options[fb_welcome_message]"
                                  id="fb_welcome_message"> <?php echo esc_attr($htcc_fb_welcome_message['fb_welcome_message']) ?></textarea>
                    </div>
                </div>
				<?php
			}
		}
		public function htcc_fb_as_state_cb()
		{
			$htcc_fb_as_state = get_option('htcc_as_options');

                ?>
                <div class="row">
                    <div class="input-field as_state col s12">
                        <p class="head_text"><?php _e('Answering Service', 'wp-chatbot') ?></p> <?php
                        if ($htcc_fb_as_state['answering_service_mm_only_mode']==false) { ?>
                        <div class="switch__wrap">
                            <label class="switch">
                                <input id="htcc_fb_as_state" name="htcc_as_options[fb_as_state]" type="checkbox"
                                       value="1" <?php isset($htcc_fb_as_state['fb_as_state']) ? checked($htcc_fb_as_state['fb_as_state'], 1) : checked(0); ?>/>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <?php }else{ ?>
                <div class="mm_only_block">
                    <h6><?php _e('Looks like you made changes to the Answering Service in MobileMonkey. Please go to MobileMonkey to continue editing.', 'wp-chatbot') ?></h6>
                    <div class="but__wrap">
                        <a target="_blank" rel="noopener noreferrer"
                           href='<?php echo $this->api->app_domain ?>chatbot-editor/<?php echo $this->internal['bot_id'] ?>/build/'
                           class="go_mm"><?php _e('Go to MobileMonkey') ?></a>
                    </div>
                </div>
                <?php } ?>
                    </div>
                </div>
                <?php

		}
		public function htcc_fb_answer_cb()
		{
			$htcc_fb_answer = get_option('htcc_as_options');
            if ($htcc_fb_answer['answering_service_mm_only_mode']==false) {
                ?>
                <div class="row as">
                    <div class="input-field col l12 m12 answer_server"> <?php
                        $html = '';
                        $i = 1;
                        if (isset($htcc_fb_answer['fb_answer'])){
                            foreach ($htcc_fb_answer['fb_answer'] as $key => $value) {

                                $html .= '<div class="as_item__wrap"><input type="text" id="fb_answer' . $i . '" name="htcc_as_options[fb_answer][]" class="fb_answer"
                               value="' . $value . '">';
                                $html .= $i>3 ?'<div class="del_as"><i class="fa fa-trash-o" aria-hidden="true"></i></div></div>': '</div>';
                                $i++;
                            }
                        }
                        ?>
                        <h3 class="qq_label"><?php _e('Quick Questions', 'wp-chatbot') ?></h3>
                        <label class="gray"
                               for="fb_answer1"><?php _e('WP-Chatbot will ask your chat users a few questions.', 'wp-chatbot') ?></label>
                        <div class="as_main__wrap">
                            <?php echo $html; ?>
                        </div>
                    </div>
                    <div class="add_as_button">
                        <span class="add_as"><b>+</b> Add Question</span>
                    </div>
                </div>
                <?php
            }
		}
		public function email_section(){
		check_ajax_referer('htcc_nonce');
		if(!current_user_can('manage_options')) {
			wp_die('Unauthorized', 403);
		}
		$htcc_fb_email_trans = get_option('htcc_as_options');
            if ($htcc_fb_email_trans['answering_service_mm_only_mode']==false) {
                $html = '';
                $email = isset($htcc_fb_email_trans['email']) ? $htcc_fb_email_trans['email'] : '';
                $state = json_decode($_GET['has_lq']);
                if (isset($state) && !$state) {
                    $html .= '<div class="input-field col l12 m12"><h3>Email to send transcripts to<span class="pro">PRO</span></h3>';
                    $html .= '<label class="gray" for="htcc_fb_email_trans">When people answer all of the questions below, we can send the answers to an email address of your choice!</label>';
                    $html .= '<div class="wrap__pro"><input type="text" name="htcc_as_options[email]" id="email" value=' . $email . '><div class="pro_button__wrapper" style="display: none"><a href="#" class="pro_button__link"><div class="pro_button"><div class="pro_button__content"><p>Upgrade to unlock this feature</p><h3>Get <b>50% off</b> when you upgrade today.</h3></div><div class="pro_button__action"><span class="pro_button_action__text">Upgrade</span></div></div></a></div></div></div>';
                } else {
                    $html .= '<div class="input-field col l12 m12"><p class="notify_mode">Notify me when a user sends a qualifying answer<span class="pro">PRO</span></p>';
                    $html .= '<div class="wrap__pro email_block">';
                    $html .= '<div class="notify__wrap">';
                    $html .= '<div class="notify_radio__wrap">';
                    $all = $htcc_fb_email_trans['notify_mode'] == 'all' ? "checked" : '';
                    $html .= '<input id="htcc_as_options[notify_mode]_1" name="htcc_as_options[notify_mode]" type="radio" value="all" ' . $all . '/>';
                    $html .= '<label for="htcc_as_options[notify_mode]_1">For all lead qualifiers</label>';
                    $html .= '</div>';
                    $html .= '<div class="notify_radio__wrap">';
                    $any = $htcc_fb_email_trans['notify_mode'] == 'any' ? "checked" : '';
                    $html .= '<input id="htcc_as_options[notify_mode]_2" name="htcc_as_options[notify_mode]" type="radio" value="any" ' . $any . '/>';
                    $html .= '<label for="htcc_as_options[notify_mode]_2">For at least one lead qualifier</label>';
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '<div class="email__wrap"><label for="email">My email</label><input type="text" name="htcc_as_options[email]" id="email" value="' . $email . '"></div><div class="pro_button__wrapper" style="display: none"><a href="#" class="pro_button__link"><div class="pro_button"><div class="pro_button__content"><p>Upgrade to unlock this feature</p><h3>Get <b>50% off</b> when you upgrade today.</h3></div><div class="pro_button__action"><span class="pro_button_action__text">Upgrade</span></div></div></a></div></div>';
                }
                wp_send_json_success($html);
            }
		}
		public function htcc_fb_email_trans_cb()
		{
			$htcc_fb_email_trans = get_option('htcc_as_options');
            if ($htcc_fb_email_trans['answering_service_mm_only_mode']==false) {
                ?>
                <div class="row as pro" id="email_test">
                    <div class="input-field col l12 m12">
                        <?php if (!$this->email_block) { ?>
                            <?php _e('<h3>Email to send transcripts to<span class="pro">PRO</span></h3>') ?>
                            <label class="gray"
                                   for="htcc_fb_email_trans"><?php _e('When people answer all of the questions below, we can send the answers to an email address of your choice!', 'wp-chatbot') ?></label>
                            <div class="wrap__pro">
                                <input type="text" name="htcc_as_options[email]" id="email"
                                       value="<?php echo esc_attr($htcc_fb_email_trans['email']) ?>">
                                <div class="pro_button__wrapper" style="display: none">
                                    <a href="#" class="pro_button__link">
                                        <div class="pro_button">
                                            <div class="pro_button__content">
                                                <p><?php _e('Upgrade to unlock this feature') ?></p>
                                                <h3><?php _e('Get <b>50% off</b> when you upgrade today.') ?></h3>
                                            </div>
                                            <div class="pro_button__action">
                                                <span class="pro_button_action__text"><?php _e('Upgrade') ?></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>

                        <?php } else { ?>
                            <?php _e('<p class="notify_mode">Notify me when a user sends a qualifying answer<span class="pro">PRO</span></p>') ?>
                            <div class="wrap__pro email_block">
                                <div class="notify__wrap">
                                    <div class="notify_radio__wrap">
                                        <input id="htcc_as_options[notify_mode]_1" name="htcc_as_options[notify_mode]"
                                               type="radio"
                                               value="all" <?php $htcc_fb_email_trans['notify_mode'] == 'all' ? checked(1) : checked(0); ?>/>
                                        <label for="htcc_as_options[notify_mode]_1"><?php _e('For all lead qualifiers') ?></label>
                                    </div>
                                    <div class="notify_radio__wrap">
                                        <input id="htcc_as_options[notify_mode]_2" name="htcc_as_options[notify_mode]"
                                               type="radio"
                                               value="any" <?php $htcc_fb_email_trans['notify_mode'] == 'any' ? checked(1) : checked(0); ?>/>
                                        <label for="htcc_as_options[notify_mode]_2"><?php _e('For at least one lead qualifier') ?></label>
                                    </div>
                                </div>
                                <div class="email__wrap">
                                    <label for="email"><?php _e('My email') ?></label>
                                    <input type="text" name="htcc_as_options[email]" id="email"
                                           value="<?php echo esc_attr($htcc_fb_email_trans['email']) ?>">
                                </div>
                                <div class="pro_button__wrapper" style="display: none">
                                    <a href="#" class="pro_button__link">
                                        <div class="pro_button">
                                            <div class="pro_button__content">
                                                <p><?php _e('Upgrade to unlock this feature') ?></p>
                                                <h3><?php _e('Get <b>50% off</b> when you upgrade today.') ?></h3>
                                            </div>
                                            <div class="pro_button__action">
                                                <span class="pro_button_action__text"><?php _e('Upgrade') ?></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <?php
            }
		}

		public function htcc_fb_thank_answer_cb()
		{
			$htcc_fb_thank_answer = get_option('htcc_as_options');
            if ($htcc_fb_thank_answer['answering_service_mm_only_mode']==false) {
                ?>
                <div class="row as">
                    <div class="input-field col l12 m12">
                        <h3><?php _e('Thank you Message') ?></h3>
                        <label class="gray"
                               for="fb_answer1"><?php _e('Thank your users for answering your questions, and let them know you\'ll get back to them.', 'wp-chatbot') ?></label>
                        <input type="text" name="htcc_as_options[thank_message]" id="thank_message"
                               value="<?php echo esc_attr($htcc_fb_thank_answer['thank_message']) ?>">
                    </div>
                </div>
                <?php
            }
		}


		// Greeting for logged in user
		public function htcc_fb_greeting_login_cb()
		{

			$htcc_fb_greeting_login = get_option('htcc_custom_options');
			?>
            <div class="row">
                <div class="input-field col s12">
                    <label class="gray" for="fb_greeting_login"><?php _e('Greeting text - If logged into Facebook in the current browser; leave empty for default message - ', 'wp-chatbot') ?>
                        <a target="_blank"
                           href="https://mobilemonkey.com/wp-chatbot/change-facebook-messenger-greetings-text/"><?php _e('more info', 'wp-chatbot') ?></a>
                    </label>
                    <input type="text" name="htcc_custom_options[fb_logged_in_greeting]" id="fb_greeting_login"
                           value="<?php echo esc_attr($htcc_fb_greeting_login['fb_logged_in_greeting']) ?>">
                    <!-- <p class="description"><?php _e('Grettings can add in any language, this can be different to the messenger language', 'wp-chatbot') ?></p> -->
                    <!-- <p class="description"><?php _e('If this Greetings fields are blank, default Greetings will load based on Messenger Language', 'wp-chatbot') ?></p> -->
                </div>
            </div>
			<?php
		}

		// Greeting for logged out user
		public function htcc_fb_greeting_logout_cb()
		{

			$htcc_fb_greeting_logout = get_option('htcc_custom_options');
			?>
            <div class="row">
                <div class="input-field col s12">
                    <label class="gray" for="fb_greeting_logout"><?php _e('Greeting text - If logged out of Facebook in the current browser; leave empty for default message - ', 'wp-chatbot') ?>
                        <a target="_blank"
                           href="https://mobilemonkey.com/wp-chatbot/change-facebook-messenger-greetings-text/"><?php _e('more info', 'wp-chatbot') ?></a></label>
                    <input type="text" name="htcc_custom_options[fb_logged_out_greeting]" id="fb_greeting_logout"
                           value="<?php echo esc_attr($htcc_fb_greeting_logout['fb_logged_out_greeting']) ?>">
                </div>
            </div>
			<?php
		}

		// sdk lang. / messenger lang
		public function htcc_fb_sdk_lang_cb()
		{
			if ($this->fb_page_id && $this->token && $this->botid){
				$lang = $this->getApi()->getLanguage($this->fb_page_id);
			}
			?>
            <div class="row">
                <div class="input-field col s12">
                    <label class="gray"><?php _e('Language displays in chat window, not user input - ', 'wp-chatbot') ?>
                        <a target="_blank"
                           href="https://mobilemonkey.com/wp-chatbot/messenger-language/"><?php _e('more info', 'wp-chatbot') ?></a>
                        <p>Facebook SDK does not support all languages</p>
                    </label>
                    <select name="htcc_custom_options[fb_sdk_lang]" id="htcc_sdk_lang">
						<?php
						$fb_lang = HTCC_Lang::$fb_lang;

						foreach ($fb_lang as $key => $value) {
							?>
                            <option value="<?php echo $value ?>"<?php if ($lang == $value) echo 'SELECTED'; ?> ><?php echo $value ?></option>
							<?php
						}
						?>
                    </select>
                </div>
            </div>
			<?php
		}

		// greeting_dialog_display - since v2.2
		public function htcc_fb_greeting_dialog_display_cb()
		{
			$greeting_dialog_display = get_option('htcc_custom_options');
			$min_value = esc_attr($greeting_dialog_display['fb_greeting_dialog_display']);
			?>
            <div class="row pro">
                <div class="input-field col s12">
                    <label class="gray"><?php _e('Greetings Dialog Display  - ', 'wp-chatbot') ?><a target="_blank" href="https://mobilemonkey.com/wp-chatbot/greeting-dialog-display/"><?php _e('more info', 'wp-chatbot') ?></a></label>
                    <div class="wrap__pro">
                        <select name="htcc_custom_options[fb_greeting_dialog_display]" class="select-1" id="htcc_greeting_dialog_display">
                            <option value="" <?php echo $min_value == "" ? 'SELECTED' : ''; ?> >Default</option>
                            <option value="show" <?php echo $min_value == "show" ? 'SELECTED' : ''; ?> >Show</option>
                            <option value="fade" <?php echo $min_value == "fade" ? 'SELECTED' : ''; ?> >Fade</option>
                            <option value="hide" <?php echo $min_value == "hide" ? 'SELECTED' : ''; ?> >Hide</option>
                        </select>
                        <div class="pro_button__wrapper" style="display: none">
                            <a href="#" class="pro_button__link">
                                <div class="pro_button">
                                    <div class="pro_button__content">
                                        <p><?php _e('Upgrade to unlock this feature') ?></p>
                                        <h3><?php _e('Get <b>50% off</b> when you upgrade today.') ?></h3>
                                    </div>
                                    <div class="pro_button__action">
                                        <span class="pro_button_action__text"><?php _e('Upgrade') ?></span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <label class="gray">Show - The greeting dialog will always be shown when the plugin loads.</label>
                    <label class="gray">Fade - The greeting dialog of the plugin will be shown, then fade away and stay minimized afterwards.</label>
                    <label class="gray">Hide - The greeting dialog of the plugin will always be minimized until a user clicks on the plugin.</label>
                </div>
            </div>
			<?php
		}
		// greeting_dialog_delay - since v2.2
		public function htcc_fb_greeting_dialog_delay_cb()
		{
			$greeting_dialog_delay = get_option('htcc_custom_options');
			$delay_time = esc_attr($greeting_dialog_delay['fb_greeting_dialog_delay']);
			?>
            <div class="row pro">
                <div class="input-field col s12">
                    <label class="gray" for="fb_greeting_dialog_delay"><?php _e('Sets the number of seconds of delay before the greeting dialog is shown after the plugin is loaded - ', 'wp-chatbot') ?>
                        <a target="_blank"
                           href="https://mobilemonkey.com/wp-chatbot/greeting-dialog-delay/"><?php _e('more info', 'wp-chatbot') ?></a></label>
                    <div class="wrap__pro">
                        <input type="number" min="0" name="htcc_custom_options[fb_greeting_dialog_delay]" id="fb_greeting_dialog_delay"
                               value="<?php echo $delay_time ?>">
                        <div class="pro_button__wrapper" style="display: none">
                            <a href="#" class="pro_button__link">
                                <div class="pro_button">
                                    <div class="pro_button__content">
                                        <p><?php _e('Upgrade to unlock this feature') ?></p>
                                        <h3><?php _e('Get <b>50% off</b> when you upgrade today.') ?></h3>
                                    </div>
                                    <div class="pro_button__action">
                                        <span class="pro_button_action__text"><?php _e('Upgrade') ?></span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
			<?php
		}


		// minimized  - deprecated - since v2.2
		// removed since 3.2
		public function htcc_fb_is_minimized_cb()
		{
			$minimized = get_option('htcc_custom_options');
			$min_value = esc_attr($minimized['minimized']);
			?>
            <div class="row">
                <div class="input-field col s12">
                    <div>
                        <select name="htcc_custom_options[minimized]" class="select-1">
                            <option value="" <?php echo $min_value == "" ? 'SELECTED' : ''; ?> >Default</option>
                            <option value="false" <?php echo $min_value == "false" ? 'SELECTED' : ''; ?> >False</option>
                            <option value="true" <?php echo $min_value == "true" ? 'SELECTED' : ''; ?> >True</option>
                        </select> This attribute is now deprecated - <a target="_blank"
                                                                        href="https://mobilemonkey.com/wp-chatbot/minimize-messenger/"><?php _e('more info', 'wp-chatbot') ?></a>
                    </div>
                    <p class="description"><?php _e('Instead, use greeting_dialog_display, greeting_dialog_delay for customization', 'wp-chatbot') ?> </p>
                </div>
            </div>
			<?php
		}


		// checkboxes - Hide based on Type of posts ..
		public function htcc_show_hide_post_types_cb()
		{
			$htcc_checkbox = get_option('htcc_custom_options');
            ?>
            <label class="gray"><?php _e('Check the box to suppress Messenger chat; based on page type - ', 'wp-chatbot') ?>
                <a target="_blank"
                   href="https://mobilemonkey.com/wp-chatbot/show-hide-messenger-based-on-type-of-the-page/"><?php _e('more info', 'wp-chatbot') ?></a></label>
            <?php
			// Single Posts
			if (isset($htcc_checkbox['hideon_posts'])) {
				?>
                <p>
                    <label>
                        <input name="htcc_custom_options[hideon_posts]" type="checkbox"
                               value="1" <?php checked($htcc_checkbox['hideon_posts'], 1); ?> id="filled-in-box1"/>
                        <span><?php _e('Hide on - Posts', 'wp-chatbot') ?></span>
                    </label>
                </p>
				<?php
			} else {
				?>
                <p>
                    <label>
                        <input name="htcc_custom_options[hideon_posts]" type="checkbox" value="1" id="filled-in-box1"/>
                        <span><?php _e('Hide on - Posts', 'wp-chatbot') ?></span>
                    </label>
                </p>
				<?php
			}


			// Page
			if (isset($htcc_checkbox['hideon_page'])) {
				?>
                <p>
                    <label>
                        <input name="htcc_custom_options[hideon_page]" type="checkbox"
                               value="1" <?php checked($htcc_checkbox['hideon_page'], 1); ?> id="filled-in-box2"/>
                        <span><?php _e('Hide on - Pages', 'wp-chatbot') ?></span>
                    </label>
                </p>
				<?php
			} else {
				?>
                <p>
                    <label>
                        <input name="htcc_custom_options[hideon_page]" type="checkbox" value="1" id="filled-in-box2"/>
                        <span><?php _e('Hide on - Pages', 'wp-chatbot') ?></span>
                    </label>
                </p>
				<?php
			}


			// Home Page
			if (isset($htcc_checkbox['hideon_homepage'])) {
				?>
                <p>
                    <label>
                        <input name="htcc_custom_options[hideon_homepage]" type="checkbox"
                               value="1" <?php checked($htcc_checkbox['hideon_homepage'], 1); ?> id="filled-in-box3"/>
                        <span><?php _e('Hide on - Home Page', 'wp-chatbot') ?></span>
                    </label>
                </p>
				<?php
			} else {
				?>
                <p>
                    <label>
                        <input name="htcc_custom_options[hideon_homepage]" type="checkbox" value="1" id="filled-in-box3"/>
                        <span><?php _e('Hide on - Home Page', 'wp-chatbot') ?></span>
                    </label>
                </p>
				<?php
			}


			/* Front Page
			 A front page is also a home page, but home page is not a front page
			 if front page unchecked - it works on both homepage and fornt page
			 but if home page is unchecked - it works only on home page, not on front page */
			if (isset($htcc_checkbox['hideon_frontpage'])) {
				?>
                <p>
                    <label>
                        <input name="htcc_custom_options[hideon_frontpage]" type="checkbox"
                               value="1" <?php checked($htcc_checkbox['hideon_frontpage'], 1); ?> id="filled-in-box4"/>
                        <span><?php _e('Hide on - Front Page', 'wp-chatbot') ?></span>
                    </label>
                </p>
				<?php
			} else {
				?>
                <p>
                    <label>
                        <input name="htcc_custom_options[hideon_frontpage]" type="checkbox" value="1" id="filled-in-box4"/>
                        <span><?php _e('Hide on - Front Page', 'wp-chatbot') ?></span>
                    </label>
                </p>
				<?php
			}


			// Category
			if (isset($htcc_checkbox['hideon_category'])) {
				?>
                <p>
                    <label>
                        <input name="htcc_custom_options[hideon_category]" type="checkbox"
                               value="1" <?php checked($htcc_checkbox['hideon_category'], 1); ?> id="filled-in-box5"/>
                        <span><?php _e('Hide on - Category', 'wp-chatbot') ?></span>
                    </label>
                </p>
				<?php
			} else {
				?>
                <p>
                    <label>
                        <input name="htcc_custom_options[hideon_category]" type="checkbox" value="1" id="filled-in-box5"/>
                        <span><?php _e('Hide on - Category', 'wp-chatbot') ?></span>
                    </label>
                </p>
				<?php
			}


			// Archive
			if (isset($htcc_checkbox['hideon_archive'])) {
				?>
                <p>
                    <label>
                        <input name="htcc_custom_options[hideon_archive]" type="checkbox"
                               value="1" <?php checked($htcc_checkbox['hideon_archive'], 1); ?> id="filled-in-box6"/>
                        <span><?php _e('Hide on - Archive', 'wp-chatbot') ?></span>
                    </label>
                </p>
				<?php
			} else {
				?>
                <p>
                    <label>
                        <input name="htcc_custom_options[hideon_archive]" type="checkbox" value="1" id="filled-in-box6"/>
                        <span><?php _e('Hide on - Archive', 'wp-chatbot') ?></span>
                    </label>
                </p>
				<?php
			}


			// 404 Page
			if (isset($htcc_checkbox['hideon_404'])) {
				?>
                <p>
                    <label>
                        <input name="htcc_custom_options[hideon_404]" type="checkbox"
                               value="1" <?php checked($htcc_checkbox['hideon_404'], 1); ?> id="filled-in-box7"/>
                        <span><?php _e('Hide on - 404 Page', 'wp-chatbot') ?></span>
                    </label>
                </p>
				<?php
			} else {
				?>
                <p>
                    <label>
                        <input name="htcc_custom_options[hideon_404]" type="checkbox" value="1" id="filled-in-box7"/>
                        <span><?php _e('Hide on - 404 Page', 'wp-chatbot') ?></span>
                    </label>
                </p>
				<?php
			}
			?>



			<?php
		}


		// ID 's list to hide styles
		function htcc_list_id_tohide_cb()
		{
			$htcc_list_id_tohide = get_option('htcc_custom_options');
			?>
            <div class="row">
                <div class="input-field col s12">
                    <input name="htcc_custom_options[list_hideon_pages]"
                           value="<?php echo esc_attr($htcc_list_id_tohide['list_hideon_pages']) ?>"
                           id="list_hideon_pages" type="text">
                    <label for="list_hideon_pages" class="gray"><?php _e('Post, Page IDs to Hide', 'ht-click') ?></label>
                    <p class="description"> <?php _e('Add Post, Page, Media - IDs to hide', 'wp-chatbot') ?>
                        <br> <?php _e('Can add multiple IDs separate with comma ( , )', 'wp-chatbot') ?> - <a
                                target="_blank"
                                href="https://mobilemonkey.com/wp-chatbot/hide-messenger-based-on-post-id/"><?php _e('more info', 'wp-chatbot') ?></a>
                    </p>
                </div>
            </div>
			<?php
		}

		//  Categorys list - to hide
		function htcc_list_cat_tohide_cb()
		{
			$htcc_list_cat_tohide = get_option('htcc_custom_options');
			?>
            <div class="row">
                <div class="input-field col s12">
                    <input name="htcc_custom_options[list_hideon_cat]"
                           value="<?php echo esc_attr($htcc_list_cat_tohide['list_hideon_cat']) ?>"
                           id="list_hideon_cat" type="text">
                    <label for="list_hideon_cat" class="gray"><?php _e('Categories to Hide', 'ht-click') ?></label>
                    <p class="description"> <?php _e('Category names to hide', 'wp-chatbot') ?>
                        <br> <?php _e('Сan add multiple Categories separate by comma ( , )', 'wp-chatbot') ?> - <a
                                target="_blank"
                                href="https://mobilemonkey.com/wp-chatbot/hide-messenger-based-on-category/"><?php _e('more info', 'wp-chatbot') ?></a>
                    </p>
                </div>
            </div>
			<?php
		}


		// checkboxes - based on Type of device ..
		public function htcc_show_hide_devices_cb()
		{
			$htcc_devices = get_option('htcc_custom_options');

			// Hide on Mobile Devices
			if (isset($htcc_devices['fb_hide_mobile'])) {
				?>
                <p>
                    <label>
                        <input name="htcc_custom_options[fb_hide_mobile]" type="checkbox"
                               value="1" <?php checked($htcc_devices['fb_hide_mobile'], 1); ?> id="fb_hide_mobile"/>
                        <span><?php _e('Hide on - Mobile Devices', 'wp-chatbot') ?></span>
                    </label>
                </p>
				<?php
			} else {
				?>
                <p>
                    <label>
                        <input name="htcc_custom_options[fb_hide_mobile]" type="checkbox" value="1" id="fb_hide_mobile"/>
                        <span><?php _e('Hide on - Mobile Devices', 'wp-chatbot') ?></span>
                    </label>
                </p>
				<?php
			}


			// Hide on Desktop Devices
			if (isset($htcc_devices['fb_hide_desktop'])) {
				?>
                <p>
                    <label>
                        <input name="htcc_custom_options[fb_hide_desktop]" type="checkbox"
                               value="1" <?php checked($htcc_devices['fb_hide_desktop'], 1); ?> id="fb_hide_desktop"/>
                        <span><?php _e('Hide on - Desktops', 'wp-chatbot') ?></span>
                    </label>
                </p>
				<?php
			} else {
				?>
                <p>
                    <label>
                        <input name="htcc_custom_options[fb_hide_desktop]" type="checkbox" value="1" id="fb_hide_desktop"/>
                        <span><?php _e('Hide on - Desktops', 'wp-chatbot') ?></span>
                    </label>
                </p>
				<?php
			}
		}


		//  Custom shortcode
		function htcc_custom_shortcode_cb()
		{
			$htcc_shortcode = get_option('htcc_custom_options');
			?>
            <div class="row">
                <div class="input-field col s12">
                    <input name="htcc_custom_options[shortcode]" value="<?php echo esc_attr($htcc_shortcode['shortcode']) ?>"
                           id="shortcode" type="text" class="validate input-margin">
                    <label for="shortcode" class="gray"><?php _e('Shortcode name', 'ht-click') ?></label>
					<?php
					// $shorcode_list = '';
					// foreach ($GLOBALS['shortcode_tags'] AS $key => $value) {
					//    $shorcode_list .= $key . ', ';
					//  }
					?>
                    <p class="description"> <?php printf(__('Default value is \'%1$s\', can customize shortcode name', 'wp-chatbot'), 'chatbot') ?>
                        - <a target="_blank"
                             href="https://mobilemonkey.com/wp-chatbot/change-shortcode-name/"><?php _e('more info', 'wp-chatbot') ?></a>
                    </p>
                    <p class="description"> <?php _e('Please don\'t add an already existing shortcode name', 'wp-chatbot') ?>
                        <!-- <p class="description"> <?php _e('please dont add this already existing shorcode names', 'wp-chatbot') ?> - </p> -->
                </div>
            </div>
			<?php
		}



		public function htcc_options_sanitize($input)
		{
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( 'not allowed to modify - please contact admin ' );
			}
			$new_input = array();
			foreach ($input as $key => $value) {
				if( isset( $input[$key] ) ) {
					$new_input[$key] = sanitize_text_field( $input[$key] );
				}
			}
			return $new_input;
		}
		public function htcc_custom_options_sanitize($input)
		{
			$option = get_option('htcc_custom_options');
			$error=false;
			$error_delay_lenght =false;
			$error_delay_value =false;
			if (!current_user_can('manage_options')) {
				wp_die('not allowed to modify - please contact admin ');
			}

			$new_input = array();
			if(isset($_REQUEST['action']) && $_REQUEST['action']== 'update') {
				$tab = get_transient( 'done-tab' );
				$tab[2] = 'true';
				set_transient('done-tab',$tab,YEAR_IN_SECONDS);
			}
			foreach ($input as $key => $value) {
				if($key == 'fb_greeting_dialog_delay'&& isset($_REQUEST['action']) && $_REQUEST['action']== 'update'){
					if (strlen($value) > 9){
						$new_input[$key] = $option[$key];
						$error_delay_lenght = true;
					}else {
						if ($value == '0'){
							$error_delay_value = true;
							$new_input[$key] = $option[$key];
						}else {
							$new_input[$key] = sanitize_text_field($input[$key]);
						}
					}
				}elseif(isset($input[$key])) {
					$new_input[$key] = sanitize_text_field($input[$key]);
				}
			}

			if ($error_delay_lenght){
				$this->getApi()->settingSaveError("delay_length");
			}
			if ($error_delay_value){
				$this->getApi()->settingSaveError("delay_0");
			}
			return $new_input;
		}
		public function htcc_as_options_sanitize($input)
		{
			$error=false;
			$error_welcome=false;
			$error_email=false;
			$option = get_option('htcc_as_options');
            $as_mm = $this->getApi()->getWidgets($this->fb_page_id);
			if (!current_user_can('manage_options')) {
				wp_die('not allowed to modify - please contact admin ');
			}
			if ($input){
			    if (!empty($as_mm)&&($as_mm['answering_service_mm_only_mode'] ?? false)==false){
                    $new_input = array();
                    if(isset($_REQUEST['action']) && $_REQUEST['action']== 'update') {
                        $tab = get_transient( 'done-tab' );
                        $tab[1] = 'true';
                        set_transient('done-tab',$tab,YEAR_IN_SECONDS);
                    }
                    foreach ($input as $key => $value) {
                        if ($key == 'fb_welcome_message' && isset($_REQUEST['action']) && $_REQUEST['action']== 'update') {
                            if ($value == '' || ctype_space($value)) {
                                $new_input[$key] = $option[$key];
                                $error_welcome = true;
                            } else {
                                $tab = get_transient( 'done-tab' );
                                $tab[1] = 'true';
                                set_transient('done-tab',$tab,YEAR_IN_SECONDS);
                                $new_input[$key] = sanitize_text_field($input[$key]);
                            }
                        }
                        if ($value == '' || ctype_space($value)){
                            if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update'){
                                if($key == 'email'){
                                    $new_input[$key] = '';
                                }else{
                                    $new_input[$key] = $option[$key];
                                    $error = true;
                                }
                            }
                        }elseif (isset($input[$key])) {
                            if ($key == 'email' && !is_email($value)){
                                $new_input[$key] = $option[$key];
                                $error_email = true;
                            }else {
                                if (strpos($key, 'lq_')!==false||strpos($key, 'qa_')!==false || $key =='fb_answer'){
                                    $new_input[$key] = $value;
                                }else{
                                    $new_input[$key] = sanitize_text_field($input[$key]);
                                }
                            }
                        }
                    }
					$new_input['answering_service_mm_only_mode'] = !empty($as_mm)?false:true;
                }else {

                    if(isset($_REQUEST['action']) && $_REQUEST['action']== 'update') {
                        foreach ($input as $key => $value) {
                            if (strpos($key, 'qa_')!==false) {
                                $new_input[$key] = $value;
                            }
                        }
                    }else{
                        foreach ($input as $key => $value) {
                            if (strpos($key, 'qa_')!==false) {
                                $new_input[$key] = $value;
                            }
                        }
                    }
                    $new_input['answering_service_mm_only_mode'] = !empty($as_mm)?false:true;

                }

			}

			$new_input['answering_service_mm_only_mode'] = !empty($as_mm)?false:true;
			if ($error_welcome){
				$this->api->settingSaveError("welcome_message");
			}
			if ($error){
				$this->api->settingSaveError("AS");
			}
			if ($error_email){
                set_transient( 'current-tab', '1',YEAR_IN_SECONDS );
				$this->api->settingSaveError("email");
			}
			return $new_input;
		}

	}

endif; // END class_exists check
