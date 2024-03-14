<?php 
// no direct access!
defined('ABSPATH') or die("No direct access");

?>
<div class="gsp_magic_wrapper">

	<img src="<?php echo plugin_dir_url( __FILE__ ) . '/images/bricks.svg';?>" class="ss_dash_loader" />

	<div class="gsp_gspeech_info_wrapper" style="display: none">

		<div class="gsp_gspeech_info_inner gsp_ov_v">

			<div class="gsp_gspeech_info_inner_bg"></div>
			<div class="gsp_gspeech_info_inner_c">
				<div class="gsp_data_title">Info</div>

				<ul class="gsp_info_ul">
					<li class="gsp_link_list_20 gsp_link_cloud_activate title_holder_vertical">
						<a href="#" target="_blank" class="activate_activate_tab">
							<span class="gsp_link_icon"></span>
							<span class="gsp_link_content gsp_link_content_activate">Activate Cloud Console</span>
						</a>
						<span class="gsp_info_sep"></span>
						<span class="gsp_link_info"> <b>Just one click</b> is required.</span>
						<span class="questions_icon"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" /></span>
						<span class="gs_title_vertical"><span class="title_v_subtitle">Activate Cloud Console:</span> <b>Just one click</b> is required. It will install all required audio widgets, to use shortcodes.<span class="gsp_title_sub_code">There is a audio widget, to automatically enable player on all pages.</span></span></span>
					</li>
					<li class="gsp_link_list_20 gsp_link_cloud_login title_holder_vertical">
						<a href="#" target="_blank" class="activate_login_tab">
							<span class="gsp_link_icon"></span>
							<span class="gsp_link_content gsp_link_content_login">Login to Cloud</span>
						</a>
						<span class="gsp_info_sep"></span>
						<span class="gsp_link_info"> <b>Login with your data!</b></span>
						<span class="questions_icon"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" /></span>
						<span class="gs_title_vertical"><span class="title_v_subtitle">Login:</span> If you activated your cloud console, login with your data. We will store your session until you logout.<span class="gsp_title_sub_code">You can login also with your account created on GSpeech Online Dashboard.</span><a class="gsp_title_sub_link" href="https://gspeech.io/dashboard" target="_blank">Dashboard Online</a></span>
					</li>
					<li class="gsp_link_list_20 gsp_link_cloud_loged_in_email ss_hidden">
						<span class="gsp_list_link_style">
							<span class="gsp_link_icon"></span>
							<span class="gsp_link_content">Cloud Status</span>
						</span>
						<span class="gsp_info_sep"></span>
						<span class="gsp_link_info gsp_link_info_active">Active</span>
					</li>
					<li class="gsp_link_list_20 gsp_link_cloud_loged_in_as ss_hidden">
						<span class="gsp_list_link_style">
							<span class="gsp_link_icon"></span>
							<span class="gsp_link_content">Loged in as </span>
						</span>
						<span class="gsp_info_sep"></span>
						<span class="gsp_link_info"><span class="gsp_login_email"></span></span>
					</li>
					<li class="gsp_link_list_20 gsp_link_cloud_logout ss_hidden">
						<a href="#" target="_blank" class="activate_logout_tab">
							<span class="gsp_link_icon"></span>
							<span class="gsp_link_content gsp_link_content_logout">Logout from Cloud</span>
						</a>
						<span class="gsp_info_sep"></span>
						<span class="gsp_link_info"> <b>Logout</b> from your current session</b>.</span>
					</li>
					<li class="gsp_link_list_20">
						<span class="gsp_list_link_style">
							<span class="gsp_link_icon"></span>
							<span class="gsp_link_content">Plugin Version</span>
						</span>
						<span class="gsp_info_sep"></span>
						<span class="gsp_link_info gsp_link_info_plg_v"><?php echo PLG_VERSION; ?></span>
					</li>
				</ul>

				<ul class="gsp_info_intro_links_ul">
					
					<li class="gsp_link_info_demos">
						<a href="https://gspeech.io/demos" target="_blank">
							<span class="gsp_link_icon"></span>
							<span class="">Demo</span>
						</a>
					</li>
					<li class="gsp_link_info_docs">
						<a href="https://gspeech.io/docs/connect-to-wordpress-website" target="_blank">
							<span class="gsp_link_icon"></span>
							<span class="">Docs</span>
						</a>
					</li>
					<li class="gsp_link_info_contact">
						<a href="https://gspeech.io/contact-us" target="_blank">
							<span class="gsp_link_icon"></span>
							<span class="">Support</span>
						</a>
					</li>
					<li class="gsp_link_info_reviews">
						<a href="https://wordpress.org/support/plugin/gspeech/reviews" target="_blank">
							<span class="gsp_link_icon"></span>
							<span class="">Reviews</span>
						</a>
					</li>
				</ul>
			</div>
		</div>

		<div class="gsp_gspeech_info_inner gsp_ov_v gsp_com_usage">

			<div class="gsp_gspeech_info_inner_bg"></div>
			<div class="gsp_gspeech_info_inner_c">
				<div class="gsp_data_title">Usage</div>

				<div class="gsp_usage_info_title title_holder_vertical">
					<span>There are many ways to insert players in your content. Easiest way to insert a shortcode or custom html. Or you can use the Multi-Page audio widget. See the instructions below.</span>
					<span class="questions_icon" style="transform: translate(0px, 10px);"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" /></span>
					<span class="gs_title_vertical"><span class="title_v_subtitle">Usage:</span> Once you <span class="gsp_title_sub_path_inline">Activate Cloud Console</span>(the first link under <span class="gsp_title_sub_path_inline">Info</span> section of this page), it automatically creates all required audio widgets, and the menu <span class="gsp_title_sub_path_inline">Cloud Console</span> is being activated. In all examples below, you manage the widgets in the <span class="gsp_title_sub_path_inline">Widgets</span> menu under the <span class="gsp_title_sub_path_inline">Cloud Console</span>.<span class="gsp_title_sub_code">Just one click is required to activate the cloud console.</span></span>
				</div>

				<ul class="gsp_info_ul">
					<li class="gsp_link_list_20 title_holder_vertical">
						<span class="gsp_list_link_style">
							<span class="gsp_link_icon"></span>
							<span class="gsp_link_content">RHT Player</span>
						</span>
						<span class="gsp_info_sep"></span>
						<span class="gsp_link_info">Read any selected text(<b>Read Highlighted Text</b>).</span>
						<span class="questions_icon"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" /></span>
						<span class="gs_title_vertical"><span class="title_v_subtitle">RHT Player:</span> To manage RHT Player settings, go to:<span class="gsp_title_sub_path">Cloud Console->Widgets->RHT Player.</span>It is being published automatically.<span class="gsp_title_sub_code">You should enable Cloud Console, to use this.</span></span></span>
					</li>
					<li class="gsp_link_list_20 title_holder_vertical">
						<span class="gsp_list_link_style">
							<span class="gsp_link_icon"></span>
							<span class="gsp_link_content">Full Page Player</span>
						</span>
						<span class="gsp_info_sep"></span>
						<span class="gsp_link_info">Use <textarea class="gsp_shortcode_html_1" style="width: 78px;">&#91;gspeech]</textarea> or <textarea class="gsp_shortcode_html_1" style="width: 227px;">&lt;div class="gsp_full_player"&gt;&lt;/div&gt;</textarea></span>
						<span class="questions_icon"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" /></span>
						<span class="gs_title_vertical"><span class="title_v_subtitle">Full Page Player Shortcode:</span> To manage this player settings, go to:<span class="gsp_title_sub_path">Cloud Console->Widgets->Full Page Player Shortcode.</span><span class="gsp_title_sub_code">You should enable Cloud Console, to use this.</span></span></span>
					</li>
					<li class="gsp_link_list_20 title_holder_vertical">
						<span class="gsp_list_link_style">
							<span class="gsp_link_icon"></span>
							<span class="gsp_link_content">Button Player</span>
						</span>
						<span class="gsp_info_sep"></span>
						<span class="gsp_link_info">Use <textarea class="gsp_shortcode_html_1" style="width: 120px;">&#91;gspeech-button]</textarea> or <textarea class="gsp_shortcode_html_1" style="width: 247px;">&lt;div class="gsp_button_player"&gt;&lt;/div&gt;</textarea></span>
						<span class="questions_icon"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" /></span>
						<span class="gs_title_vertical"><span class="title_v_subtitle">Button Player Shortcode:</span> To manage this player settings, go to:<span class="gsp_title_sub_path">Cloud Console->Widgets->Button Player Shortcode.</span><span class="gsp_title_sub_code">You should enable Cloud Console, to use this.</span></span></span>
					</li>
					<li class="gsp_link_list_20 title_holder_vertical">
						<span class="gsp_list_link_style">
							<span class="gsp_link_icon"></span>
							<span class="gsp_link_content">Circle Player</span>
						</span>
						<span class="gsp_info_sep"></span>
						<span class="gsp_link_info">Use <textarea class="gsp_shortcode_html_1" style="width: 115px;">&#91;gspeech-circle]</textarea> or <textarea class="gsp_shortcode_html_1" style="width: 239px;">&lt;div class="gsp_circle_player"&gt;&lt;/div&gt;</textarea></span>
						<span class="questions_icon"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" /></span>
						<span class="gs_title_vertical"><span class="title_v_subtitle">Circle Player Shortcode:</span> To manage this player settings, go to:<span class="gsp_title_sub_path">Cloud Console->Widgets->Circle Player Shortcode.</span><span class="gsp_title_sub_code">You should enable Cloud Console, to use this.</span></span></span>
					</li>
					<li class="gsp_link_list_20 title_holder_vertical">
						<span class="gsp_list_link_style">
							<span class="gsp_link_icon"></span>
							<span class="gsp_link_content">Inline Shortcode</span>
						</span>
						<span class="gsp_info_sep"></span>
						<span class="gsp_link_info">Use <textarea class="gsp_shortcode_html_1" style="width: 222px;">{gspeech}text to speech{/gspeech}</textarea></span>
						<span class="questions_icon"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" /></span>
						<span class="gs_title_vertical"><span class="title_v_subtitle">Inline Shortcode:</span> To manage this player settings, go to:<span class="gsp_title_sub_path">Cloud Console->Widgets->Inline Shortcode.</span><span class="gsp_title_sub_code">You should enable Cloud Console, to use this.</span></span></span>
					</li>
					<li class="gsp_link_list_20 title_holder_vertical">
						<span class="gsp_list_link_style">
							<span class="gsp_link_icon"></span>
							<span class="gsp_link_content">Welcome Message</span>
						</span>
						<span class="gsp_info_sep"></span>
						<span class="gsp_link_info">Publish the widget: <b>Welcome Message</b></span>
						<span class="questions_icon"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" /></span>
						<span class="gs_title_vertical"><span class="title_v_subtitle">Welcome Message:</span> To manage welcome message settings, go to:<span class="gsp_title_sub_path">Cloud Console->Widgets->Welcome Message.</span>It is unpublished by default.<span class="gsp_title_sub_code">You should enable Cloud Console, to use this.</span></span></span>
					</li>
					<li class="gsp_link_list_20 title_holder_vertical">
						<span class="gsp_list_link_style">
							<span class="gsp_link_icon"></span>
							<span class="gsp_link_content">Multi-Page Player</span>
						</span>
						<span class="gsp_info_sep"></span>
						<span class="gsp_link_info">Can be used on <b>Multiple Pages</b></span>
						<span class="questions_icon"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" /></span>
						<span class="gs_title_vertical"><span class="title_v_subtitle">Multi-Page Player:</span> This player can be used on multiple pages. To manage multi-page player settings, go to:<span class="gsp_title_sub_path">Cloud Console->Widgets->Multi-Page Player.</span>It is unpublished by default. Once you publish it, it will catch your content automatically. It works with most WordPress site structures. If it does not work on your website, just contact us, we will configure it quickly!<span class="gsp_title_sub_code">You should enable Cloud Console, to use this.</span></span></span>
					</li>
				</ul>
			</div>
		</div>

		<div class="gsp_gspeech_info_inner gsp_ov_v gsp_com_f_l">

			<div class="gsp_gspeech_info_inner_bg"></div>
			<div class="gsp_gspeech_info_inner_c">
				<div class="gsp_data_title">Commercial features</div>

				<ul class="gsp_info_ul">
					<li class="gsp_link_list_20 title_holder_vertical">
						<span class="gsp_list_link_style">
							<span class="gsp_link_icon"></span>
							<span class="gsp_link_content">AI voices</span>
						</span>
						<span class="gsp_info_sep"></span>
						<span class="gsp_link_info">Get access to the <b>Best AI Voices.</b></span>
						<span class="questions_icon"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" /></span>
						<span class="gs_title_vertical"><span class="title_v_subtitle">AI voices:</span> Deploy the last groundbreaking technologies to generate speech with humanlike intonation.<span class="gsp_title_sub_code">They have the star icon in the list of voices.</span></span></span>
					</li>
					<li class="gsp_link_list_20 title_holder_vertical">
						<span class="gsp_list_link_style">
							<span class="gsp_link_icon"></span>
							<span class="gsp_link_content">Voice Tuning</span>
						</span>
						<span class="gsp_info_sep"></span>
						<span class="gsp_link_info">Adjust speaking <b>Pitch and Speed</b> to your needs.</span>
						<span class="questions_icon"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" /></span>
						<span class="gs_title_vertical"><span class="title_v_subtitle">Voice Tuning:</span> Personalize the speaking pitch of your selected voice, up to 20 semitones more or less from the default. Adjust your speaking rate(speed) to be 4x faster or slower than the normal rate.</span></span>
					</li>
					<li class="gsp_link_list_20 title_holder_vertical">
						<span class="gsp_list_link_style">
							<span class="gsp_link_icon"></span>
							<span class="gsp_link_content">Real-time Translation</span>
						</span>
						<span class="gsp_info_sep"></span>
						<span class="gsp_link_info">Best neural engines.</span>
						<span class="questions_icon"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" /></span>
						<span class="gs_title_vertical"><span class="title_v_subtitle">Real-Time Translation:</span> Give your users opportunity to on-the-fly translate audio to desired language instantly from the players. We use Google Power and the best neural solutions. <span class="gsp_title_sub_code">Shows a globe icon in the players, to choose the language.</span></span>
					</li>
					<li class="gsp_link_list_20 title_holder_vertical">
						<span class="gsp_list_link_style">
							<span class="gsp_link_icon"></span>
							<span class="gsp_link_content">Multi-Lang Websites</span>
						</span>
						<span class="gsp_info_sep"></span>
						<span class="gsp_link_info">Support of any <b>multi lang website.</b></span>
						<span class="questions_icon"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" /></span>
						<span class="gs_title_vertical"><span class="title_v_subtitle">Multi-Lang Websites:</span> Support of multi lang websites. It will catch your webiste's current language, and show appropriate player.<br /><br />Compatible with <b>any multilingual plugin</b>! We will configure it for you!</span>
					</li>
					<li class="gsp_link_list_20 title_holder_vertical">
						<span class="gsp_list_link_style">
							<span class="gsp_link_icon"></span>
							<span class="gsp_link_content">Download audio</span>
						</span>
						<span class="gsp_info_sep"></span>
						<span class="gsp_link_info">Download <b>mp3 audio files</b> directly from the players.</span>
						<span class="questions_icon"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" /></span>
						<span class="gs_title_vertical"><span class="title_v_subtitle">Download audio:</span> Download mp3 audio files directly from the players for offline listening.<span class="gsp_title_sub_code">Shows a download icon in the players.</span></span>
					</li>
					<li class="gsp_link_list_20 title_holder_vertical">
						<span class="gsp_list_link_style">
							<span class="gsp_link_icon"></span>
							<span class="gsp_link_content">Voice Panel</span>
						</span>
						<span class="gsp_info_sep"></span>
						<span class="gsp_link_info">Listen audio in desired voice.</span>
						<span class="questions_icon"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" /></span>
						<span class="gs_title_vertical"><span class="title_v_subtitle">Vocie Panel:</span> Allow your users to listen audio in desired voice(differnent male/female voices).<span class="gsp_title_sub_code">Shows a voice selector icon in the players, to choose the voice.</span></span>
					</li>
					<li class="gsp_link_list_20 title_holder_vertical">
						<span class="gsp_list_link_style">
							<span class="gsp_link_icon"></span>
							<span class="gsp_link_content">Text Panel</span>
						</span>
						<span class="gsp_info_sep"></span>
						<span class="gsp_link_info">Captions the part of text, which is <b>being read.</b></span>
						<span class="questions_icon"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" /></span>
						<span class="gs_title_vertical"><span class="title_v_subtitle">Text Panel:</span> Shows text panel, and caption the part of text, which is being read.<span class="gsp_title_sub_code">Shows a text panel icon in the players, to expand the panel.</span></span>
					</li>
					<li class="gsp_link_list_20 title_holder_vertical">
						<span class="gsp_list_link_style">
							<span class="gsp_link_icon"></span>
							<span class="gsp_link_content">Context Player</span>
						</span>
						<span class="gsp_info_sep"></span>
						<span class="gsp_link_info">Out of view player.</span>
						<span class="questions_icon"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" /></span>
						<span class="gs_title_vertical"><span class="title_v_subtitle">Context Player:</span> Would follow the users as they scroll and allow them to control the player when it’s out of view.<span class="gsp_title_sub_code">Shows a context player icon in the players, to expand the context player.</span></span>
					</li>
					<li class="gsp_link_list_20 title_holder_vertical">
						<span class="gsp_list_link_style">
							<span class="gsp_link_icon"></span>
							<span class="gsp_link_content">Text Aliases</span>
						</span>
						<span class="gsp_info_sep"></span>
						<span class="gsp_link_info">Example: read <b>AI</b> as <b>Artificial Intelligence</b>.</span>
						<span class="questions_icon"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" /></span>
						<span class="gs_title_vertical"><span class="title_v_subtitle">Text Aliases:</span> For example you need AI to be read as Artificial Intelligence. Just add the line: <span class="gsp_title_sub_code">ai:artificial intelligence</span><br /><br />You can create multiple text aliases in a comfortable interface. Just insert one rule per row.</span>
					</li>
					<li class="gsp_link_list_20 title_holder_vertical">
						<span class="gsp_list_link_style">
							<span class="gsp_link_icon"></span>
							<span class="gsp_link_content">Priority Support</span>
						</span>
						<span class="gsp_info_sep"></span>
						<span class="gsp_link_info">We will fix any possible issues and make all the setup.</span>
						<span class="questions_icon"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" /></span>
						<span class="gs_title_vertical"><span class="title_v_subtitle">Priority Support:</span> We will fix any possible issues and make all the setup.</span>
					</li>
					<li class="gsp_link_list_20 title_holder_vertical">
						<span class="gsp_list_link_style">
							<span class="gsp_link_icon"></span>
							<span class="gsp_link_content">Advanced Analytics</span>
						</span>
						<span class="gsp_info_sep"></span>
						<span class="gsp_link_info">More detailed analytics of the usage / play statystics.</span>
						<span class="questions_icon"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" /></span>
						<span class="gs_title_vertical"><span class="title_v_subtitle">Advanced Analytics:</span> More detailed analytics of the usage / play statystics.</span>
					</li>
				</ul>
				<a href="https://gspeech.io/#pricing" target="_blank" class="gsp_upg_link" style="width: 350px;margin-top: 20px;">Upgrade to unlock <img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/lock.svg" /></a>
			</div>
		</div>

		<div class="gsp_gspeech_info_inner">

			<div class="gsp_gspeech_info_inner_bg"></div>
			<div class="gsp_gspeech_info_inner_c">
				<div class="gsp_data_title">Useful Links</div>

				<ul class="gsp_info_ul">
					<li class="gsp_link_list_1">
						<a href="https://gspeech.io" target="_blank">
							<span class="gsp_link_icon"></span>
							<span class="gsp_link_content">Project Homepage</span>
						</a>
						<span class="gsp_info_sep"></span>
						<span class="gsp_link_info">Online <b>Text To Audio generator</b> is available.</span>
					</li>
					<li class="gsp_link_list_2">
						<a href="https://gspeech.io/demos" target="_blank">
							<span class="gsp_link_icon"></span>
							<span class="gsp_link_content">Live Demo</span>
						</a>
						<span class="gsp_info_sep"></span>
						<span class="gsp_link_info">See <b>GSpeech in action</b>. Different languages.</span>
					</li>
					<li class="gsp_link_list_3">
						<a href="https://gspeech.io/docs/connect-to-wordpress-website" target="_blank">
							<span class="gsp_link_icon"></span>
							<span class="gsp_link_content">Quick Start (video demo)</span>
						</a>
						<span class="gsp_info_sep"></span>
						<span class="gsp_link_info">Make all the setup with <b>single click</b>.</span>
					</li>
					<li class="gsp_link_list_4">
						<a href="https://gspeech.io/contact-us" target="_blank">
							<span class="gsp_link_icon"></span>
							<span class="gsp_link_content">Contact Us</span>
						</a>
						<span class="gsp_info_sep"></span>
						<span class="gsp_link_info">We usually respond <b>within an hour</b>.</span>
					</li>
					<li class="gsp_link_list_5">
						<a href="https://wordpress.org/plugins/gspeech/#reviews" target="_blank">
							<span class="gsp_link_icon"></span>
							<span class="gsp_link_content">Reviews</span>
						</a>
						<span class="gsp_info_sep"></span>
						<span class="gsp_link_info">What our <b>customers say</b>.</span>
					</li>
					<li class="gsp_link_list_6">
						<a href="https://wordpress.org/support/plugin/gspeech/reviews/#new-post" target="_blank">
							<span class="gsp_link_icon"></span>
							<span class="gsp_link_content">Write a review</span>
						</a>
						<span class="gsp_info_sep"></span>
						<span class="gsp_link_info">Support our work and <b>write a review please</b>.</span>
					</li>
					<li class="gsp_link_list_7">
						<a href="https://gspeech.io/dashboard" target="_blank">
							<span class="gsp_link_icon"></span>
							<span class="gsp_link_content">Dashboard Online</span>
						</a>
						<span class="gsp_info_sep"></span>
						<span class="gsp_link_info">The <b>online version</b> of your dashboard.</span>
					</li>
					<li class="gsp_link_list_11">
						<a href="https://gspeech.io/#pricing" target="_blank">
							<span class="gsp_link_icon"></span>
							<span class="gsp_link_content">Compare plans</span>
						</a>
						<span class="gsp_info_sep"></span>
						<span class="gsp_link_info">See the difference between our plans.</span>
					</li>
					<li class="gsp_link_list_8">
						<a href="https://wordpress.org/plugins/gspeech/#screenshots" target="_blank">
							<span class="gsp_link_icon"></span>
							<span class="gsp_link_content">Screenshots</span>
						</a>
						<span class="gsp_info_sep"></span>
						<span class="gsp_link_info"><b>Live previews</b> from clients websites.</span>
					</li>
					<li class="gsp_link_list_9">
						<a href="https://wordpress.org/plugins/gspeech/#faq" target="_blank">
							<span class="gsp_link_icon"></span>
							<span class="gsp_link_content">FAQ</span>
						</a>
						<span class="gsp_info_sep"></span>
						<span class="gsp_link_info"><b>Common questions</b> answered.</span>
					</li>
					<li class="gsp_link_list_10">
						<a href="https://gspeech.io/blog" target="_blank">
							<span class="gsp_link_icon"></span>
							<span class="gsp_link_content">Blog</span>
						</a>
						<span class="gsp_info_sep"></span>
						<span class="gsp_link_info"><b>Read</b> our blog.</span>
					</li>
				</ul>
			</div>
		</div>

		
	</div>

	<div class="gsp_videos_wrapper" style="display: none">

		<div class="gsp_gspeech_info_inner">

			<div class="gsp_gspeech_info_inner_bg"></div>
			<div class="gsp_gspeech_info_inner_c">

				<div class="gsp_data_title">Video tutorials</div>

				<div class="ss_video_item_3">
					<div class="ss_video_title ss_title_green">GSpeech Installation Guide - 4m</div>
					<div class="ss_video_item_holder ss_video_gb_1" data-video_id="IBeCaYtKGeQ"></div>
				</div>

				<div class="ss_video_item_3">
					<div class="ss_video_title ss_title_violet">GSpeech Full Player - Paid Version</div>
					<div class="ss_video_item_holder ss_video_gb_2" data-video_id="dbvmoo10_QQ"></div>
				</div>

				<div class="ss_video_item_3">
					<div class="ss_video_title ss_title_orange">GSpeech RHT Player - Enable Translation, Male/Female AI Voices</div>
					<div class="ss_video_item_holder ss_video_gb_3" data-video_id="y2zJjNtOHqY"></div>
				</div>
			</div>

		</div>

	</div>
	
</div>