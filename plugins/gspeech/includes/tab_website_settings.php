<?php 
// no direct access!
defined('ABSPATH') or die("No direct access");

?>

<div class="dashboard_title_inner">Website Settings</div>

<div class="dashboard_tabs_wrapper" style="">
	<div class="dashboard_tab ss_tab_link_general ss_selected" data-tab_ident="general">General</div>
	<div class="dashboard_tab" data-tab_ident="player">Player</div>
	<div class="dashboard_tab" data-tab_ident="translation">Translation</div>
	<div class="dashboard_tab" data-tab_ident="custom">Custom Code</div>
	<div class="dashboard_tab" data-tab_ident="aliases">Aliases</div>
	<div class="ss_upgrade_info">
		<div class="ss_locked_icon"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/lock.svg" /></div>
		<a href="https://gspeech.io/#pricing" target="_blank">Upgrade</a><span>to Activate Locked Features</span>
	</div>
</div>

<div id="gsp_dash_main_wrapper">
	<div id="dashboard_content" class="ss_dash_webs_set">

		<div class="inner_options_wrapper">

			<div class="ss_options_group_1 ss_tab_wrapper ss_tab_general ss_tab_active"">

				<div class="opts_title">General options</div>

				<div class="opts_block gsp_hidden">
					<div class="opts_block_label title_holder_vertical">
						<span>Title</span>
						<span class="questions_icon">
							<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" />
						</span>
						<span class="gs_title_vertical"><span class="title_v_subtitle">Webiste title:</span> Will be used only in dashboard.</span>
					</div>
					<input type="text" class="opts_input gsp_readonly" readonly="readonly" id="ss_website_title" value="" />
				</div>

				<div class="opts_block gsp_hidden">
					<div class="opts_block_label title_holder_vertical">
						<span>Url</span>
						<span class="questions_icon">
							<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" />
						</span>
						<span class="gs_title_vertical"><span class="title_v_subtitle">Webiste url:</span> Changing the value will cause old ulr to stop working!</span>
					</div>
					<input type="text" class="opts_input gsp_readonly" readonly="readonly" id="ss_website_url" value="" />
				</div>

				<div class="opts_block gsp_hidden">
					<div class="opts_block_label title_holder_vertical">
						<span>Widget ID</span>
						<span class="questions_icon">
							<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" />
						</span>
						<span class="gs_title_vertical"><span class="title_v_subtitle">Widget ID:</span> Unique identificator for your website.</span>
					</div>
					<input id="ss_website_widget_id" type="text" readonly="readonly" class="opts_input gsp_readonly" value=""  />
				</div>

				<div class="opts_block">
					<div class="opts_block_label title_holder_vertical">
						<span>Language</span>
						<span class="questions_icon">
							<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" />
						</span>
						<span class="gs_title_vertical"><span class="title_v_subtitle">Webiste language:</span> Your webiste native language. Will be used as default language for all audio widgets.</span>
					</div>
					<div id="lng_sel" data-val="{CSLAB_LNG_VAL}" class="items_select_filter_wrapper" data-def_txt="Select language">
						<div class="items_select_filter">
							<div class="items_select_filter_content">
								<span>Select language</span>
								<input type="text" class="li_search_input" />
							</div>
							<div class="items_select_filter_icon_wrapper">
								<div class="items_select_filter_icon_holder">
									<div class="items_select_filter_icon_inner">
										<span class="items_select_filter_icon">
											<svg class="" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M424.4 214.7L72.4 6.6C43.8-10.3 0 6.1 0 47.9V464c0 37.5 40.7 60.1 72.4 41.3l352-208c31.4-18.5 31.5-64.1 0-82.6z"></path></svg>
										</span>
									</div>
								</div>
							</div>
							<div class="items_select_ul_wrapper">
								<div class="items_select_ul_holder">
									<div class="items_select_ul_inner">
										<ul class="items_select_ul">
										</ul>
									</div>
								</div>
							</div>

						</div>
					</div>
				</div>

				<div class="opts_block">
					<div class="opts_block_label title_holder_vertical">
						<span>Plan</span>
						<span class="questions_icon">
							<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" />
						</span>

						<span class="gs_title_vertical"><span class="title_v_subtitle">Webiste plan:</span> Upgrade to activate locked features.</span>
					</div>
					
					<div id="wbs_plan" class="items_select_filter_wrapper" >
						<div class="items_select_filter">
							<div class="items_select_filter_content"><span>Free</span></div>
							<div class="items_select_filter_icon_wrapper">
								<div class="items_select_filter_icon_holder">
									<div class="items_select_filter_icon_inner">
										<span class="items_select_filter_icon">
											<svg class="" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M424.4 214.7L72.4 6.6C43.8-10.3 0 6.1 0 47.9V464c0 37.5 40.7 60.1 72.4 41.3l352-208c31.4-18.5 31.5-64.1 0-82.6z"></path></svg>
										</span>
									</div>
								</div>
							</div>

							<div class="items_select_ul_wrapper">
								<div class="items_select_ul_holder">
									<div class="items_select_ul_inner">
										<ul class="items_select_ul">
											<li data-val="0" class="search_li user_plan_free li_selected ss_ul_li_act"><span>Free</span></li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="opts_block_wrapper_row">
					<div class="opts_block">
						<div class="opts_block_label title_holder_vertical">
							<span>Status</span>
							<span class="questions_icon">
								<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" />
							</span>
							<span class="gs_title_vertical"><span class="title_v_subtitle">Status:</span> Publish or unpublish the website!</span>
						</div>

						<div class="gs_mono_checkbox_wrapper" id="wbs_status">
							<div class="gs_mono_switcher">
								<div class="gs_mono_switcher_button"></div>
							</div>
							<div class="gs_mono_label">
								<span class="gs_mono_label gs_mono_label_0">Unpublished</span>
								<span class="gs_mono_label gs_mono_label_1">Published</span>
								<input type="checkbox" class="gs_mono_checkbox" value="1"/>
							</div>
						</div>
					</div>

					<div class="opts_block">
						<div class="opts_block_label title_holder_vertical">
							<span>Lazy Loading</span>
							<span class="questions_icon">
								<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" />
							</span>
							<span class="gs_title_vertical"><span class="title_v_subtitle">Lazy Loading:</span>. GSpeech loads all required resoursec, included svg and style files via single javascrip file. If this option is disabled, it will load that file asynchronely, which means your page does not wait for it, anyway it can affect on loading time in general. If you enable this option, it will load that file after your page loads, so does not affect on loading time in no way, but the players will be rendered with some delay.</span>
						</div>

						<div class="gs_mono_checkbox_wrapper" id="wbs_lazy_load">
							<div class="gs_mono_switcher">
								<div class="gs_mono_switcher_button"></div>
							</div>
							<div class="gs_mono_label">
								<span class="gs_mono_label gs_mono_label_0">Disabled</span>
								<span class="gs_mono_label gs_mono_label_1">Enabled</span>
								<input type="checkbox" class="gs_mono_checkbox" value="1"/>
							</div>
						</div>
					</div>

					<div class="opts_block">
						<div class="opts_block_label title_holder_vertical">
							<span>Reload Session</span>
							<span class="questions_icon">
								<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" />
							</span>
							<span class="gs_title_vertical"><span class="title_v_subtitle">Reload Session:</span>. GSpeech uses crypto hashing for secure authentication. Enable this, if you need to reload the generated crypto hash. Usually do not needed.</span>
						</div>

						<div class="gs_mono_checkbox_wrapper" id="wbs_reload_session">
							<div class="gs_mono_switcher">
								<div class="gs_mono_switcher_button"></div>
							</div>
							<div class="gs_mono_label">
								<span class="gs_mono_label gs_mono_label_0">Disabled</span>
								<span class="gs_mono_label gs_mono_label_1">Enabled</span>
								<input type="checkbox" class="gs_mono_checkbox" value="1"/>
							</div>
						</div>
					</div>
				</div>

			</div>

			<div class="ss_options_group_1 ss_tab_wrapper ss_tab_general ss_tab_active"">

				<div class="opts_title">Voice configuration</div>

				<div class="opts_block">
					<div class="opts_block_label title_holder_vertical">
						<span>Voice</span>
						<span class="questions_icon">
							<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" />
						</span>

						<span class="gs_title_vertical"><span class="title_v_subtitle">Website voice:</span> Will be used as default voice for all audio widgets.</span>
					</div>

					<div id="wbs_voice" class="items_select_filter_wrapper ss_audio_holder" data-sel_voice="" data-def_txt="Select voice">
						<div class="items_select_filter">
							<div class="items_select_filter_content"><span>Select voice</span><input type="text" class="li_search_input" /></div>
							<div class="items_select_filter_icon_wrapper">
								<div class="items_select_filter_icon_holder">
									<div class="items_select_filter_icon_inner">
										<span class="items_select_filter_icon">
											<svg class="" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M424.4 214.7L72.4 6.6C43.8-10.3 0 6.1 0 47.9V464c0 37.5 40.7 60.1 72.4 41.3l352-208c31.4-18.5 31.5-64.1 0-82.6z"></path></svg>
										</span>
									</div>
								</div>
							</div>

							<div class="items_select_ul_wrapper">
								<div class="items_select_ul_holder">
									<div class="items_select_ul_inner">
										<ul class="items_select_ul">
											<li data-val="-1" class="search_li"><span>select voice</span></li>
										</ul>
									</div>
								</div>
							</div>

						</div>
					</div>
				</div>
				<div class="opts_block">
					<div class="opts_block_label title_holder_vertical">
						<span>Speed</span>
						<span class="questions_icon">
							<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" />
						</span>
						<div class="ss_locked_icon ss_locked_icon_inner"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/lock.svg" /></div>
						<span class="gs_title_vertical"><span class="title_v_subtitle">Voice speed:</span> Will be used as default voice speed for all audio widgets.<br /><br />Available for Premium voices only!</span>
					</div>
					<div class="ss_slider_element_wrapper gsp_option_disable_switcher gsp_option_disabled" style="">
						<div class="gsp_slider_element" id="gsp_slider_speed">
							<div class="gsp_label_wrapper">
								<label for="gsp_voice_type" class=""></label>
								<div class="gsp_label_reset_icon gsp_reset_disabled">
									<svg role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M512 256c0 88.224-71.775 160-160 160H170.067l34.512 32.419c9.875 9.276 10.119 24.883.539 34.464l-10.775 10.775c-9.373 9.372-24.568 9.372-33.941 0l-92.686-92.686c-9.373-9.373-9.373-24.568 0-33.941l92.686-92.686c9.373-9.373 24.568-9.373 33.941 0l10.775 10.775c9.581 9.581 9.337 25.187-.539 34.464L170.067 352H352c52.935 0 96-43.065 96-96 0-13.958-2.996-27.228-8.376-39.204-4.061-9.039-2.284-19.626 4.723-26.633l12.183-12.183c11.499-11.499 30.965-8.526 38.312 5.982C505.814 205.624 512 230.103 512 256zM72.376 295.204C66.996 283.228 64 269.958 64 256c0-52.935 43.065-96 96-96h181.933l-34.512 32.419c-9.875 9.276-10.119 24.883-.539 34.464l10.775 10.775c9.373 9.372 24.568 9.372 33.941 0l92.686-92.686c9.373-9.373 9.373-24.568 0-33.941l-92.686-92.686c-9.373-9.373-24.568-9.373-33.941 0L306.882 29.12c-9.581 9.581-9.337 25.187.539 34.464L341.933 96H160C71.775 96 0 167.776 0 256c0 25.897 6.186 50.376 17.157 72.039 7.347 14.508 26.813 17.481 38.312 5.982l12.183-12.183c7.008-7.008 8.786-17.595 4.724-26.634z"></path></svg>
								</div>
								<input type="text" class="gsp_value_label" value="0.00" />
							</div>
							<div class="gsp_slider_wrapper" data-bar_limit_start="0.25" data-bar_start="0" data-bar_end="4" data-start_value="{CSLAB_SPEED}">
								<div class="gsp_slider_bar">
									<div class="gsp_slider_bar_active">
										<div class="gsp_slider_bar_button"></div>
									</div>
									<div class="gsp_slider_bar_percents"></div>
								</div>
							</div>
						</div>
						<div class="gsp_option_disable_wrapper"></div>
					</div>
				</div>
				<div class="opts_block">
					<div class="opts_block_label title_holder_vertical">
						<span>Pitch</span>
						<span class="questions_icon">
							<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" />
						</span>
						<div class="ss_locked_icon ss_locked_icon_inner"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/lock.svg" /></div>
						<span class="gs_title_vertical"><span class="title_v_subtitle">Voice pitch:</span> Will be used as default voice pitch for all audio blocks.<br /><br />Available for Premium voices only!</span>
					</div>
					<div class="ss_slider_element_wrapper gsp_option_disable_switcher gsp_option_disabled" style="">
						<div class="gsp_slider_element" id="gsp_slider_pitch">
							<div class="gsp_label_wrapper">
								<label for="gsp_voice_type" class=""></label>
								<div class="gsp_label_reset_icon gsp_reset_disabled">
									<svg role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M512 256c0 88.224-71.775 160-160 160H170.067l34.512 32.419c9.875 9.276 10.119 24.883.539 34.464l-10.775 10.775c-9.373 9.372-24.568 9.372-33.941 0l-92.686-92.686c-9.373-9.373-9.373-24.568 0-33.941l92.686-92.686c9.373-9.373 24.568-9.373 33.941 0l10.775 10.775c9.581 9.581 9.337 25.187-.539 34.464L170.067 352H352c52.935 0 96-43.065 96-96 0-13.958-2.996-27.228-8.376-39.204-4.061-9.039-2.284-19.626 4.723-26.633l12.183-12.183c11.499-11.499 30.965-8.526 38.312 5.982C505.814 205.624 512 230.103 512 256zM72.376 295.204C66.996 283.228 64 269.958 64 256c0-52.935 43.065-96 96-96h181.933l-34.512 32.419c-9.875 9.276-10.119 24.883-.539 34.464l10.775 10.775c9.373 9.372 24.568 9.372 33.941 0l92.686-92.686c9.373-9.373 9.373-24.568 0-33.941l-92.686-92.686c-9.373-9.373-24.568-9.373-33.941 0L306.882 29.12c-9.581 9.581-9.337 25.187.539 34.464L341.933 96H160C71.775 96 0 167.776 0 256c0 25.897 6.186 50.376 17.157 72.039 7.347 14.508 26.813 17.481 38.312 5.982l12.183-12.183c7.008-7.008 8.786-17.595 4.724-26.634z"></path></svg>
								</div>
								<input type="text" class="gsp_value_label" value="0.00" />
							</div>
							<div class="gsp_slider_wrapper" data-bar_start="-20" data-bar_end="20" data-start_value="{CSLAB_PITCH}">
								<div class="gsp_slider_bar">
									<div class="gsp_slider_bar_active">
										<div class="gsp_slider_bar_button"></div>
									</div>
									<div class="gsp_slider_bar_percents"></div>
								</div>
							</div>
						</div>
						<div class="gsp_option_disable_wrapper"></div>
					</div>
				</div>

				<div class="opts_block" style="margin-top: 15px;">
					<div class="ss_voice_preview_wrapper"></div>
					<div class="ss_option_info">Listen to the voice with applied configuration.</div>
					<div id="voice_preview_data" data-preview_txt_def="Hello, you are listening to the preview of this voice." data-preview_txt_ready="Hello, you are listening to the preview of this voice."></div>
					<div id="wbs_website_options" data-val="" data-state=""></div>
				</div>

				<div class="opts_block">
					<div class="opts_block_label title_holder_vertical">
						<span>Voice Panel</span>
						<span class="questions_icon">
							<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" />
						</span>
						<span class="gs_title_vertical"><span class="title_v_subtitle">Voice Panel:</span> Shows voice panel in the players.<br /><br />Allow your users to listen audio in desired voice(differnent male/female voices).<br /><br /><a class="gsp_title_sub_link" href="https://gspeech.io/demos" target="_blank">See live demo</a></span>
						<div class="ss_locked_icon ss_locked_icon_inner"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/lock.svg" /></div>
					</div>

					<div class="gs_mono_checkbox_wrapper ss_commercial_switcher" id="wbs_voice_enabled">
						<div class="gs_mono_switcher">
							<div class="gs_mono_switcher_button"></div>
						</div>
						<div class="gs_mono_label">
							<span class="gs_mono_label gs_mono_label_0">Disabled</span>
							<span class="gs_mono_label gs_mono_label_1">Enabled</span>
							<input type="checkbox" class="gs_mono_checkbox" value="1"/>
						</div>
					</div>

					<div id="wbs_multiple_voices" style="margin-top: 15px;" class="items_select_filter_wrapper ss_select_multiple ss_disabled ss_audio_holder" data-sel_voice="" data-def_txt="Select voices">
						<div class="items_select_filter">
							<div class="items_select_filter_content"><span>Select voices</span></div>
							<div class="items_select_filter_icon_wrapper">
								<div class="items_select_filter_icon_holder">
									<div class="items_select_filter_icon_inner">
										<span class="items_select_filter_icon">
											<svg class="" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M424.4 214.7L72.4 6.6C43.8-10.3 0 6.1 0 47.9V464c0 37.5 40.7 60.1 72.4 41.3l352-208c31.4-18.5 31.5-64.1 0-82.6z"></path></svg>
										</span>
									</div>
								</div>
							</div>
							<div class="items_select_ul_wrapper">
								<div class="items_select_ul_holder">
									<div class="items_select_ul_inner">
										<ul class="items_select_ul"></ul>
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>

			<div class="ss_options_group_1 ss_tab_wrapper ss_tab_player"">

				<div class="opts_title">Player settings</div>

				<div class="opts_block">
					<div class="opts_block_label title_holder_vertical">
						<span>Appear animation</span>
						<span class="questions_icon">
							<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" />
						</span>
						<span class="gs_title_vertical"><span class="title_v_subtitle">Appear animation:</span> How the players should animate, when appearing first time.</span>
					</div>
					<div id="wbs_appear_class" style="margin-top: 5px;" class="items_select_filter_wrapper">
						<div class="items_select_filter">
							<div class="items_select_filter_content"><span>Choose one</span></div>
							<div class="items_select_filter_icon_wrapper">
								<div class="items_select_filter_icon_holder">
									<div class="items_select_filter_icon_inner">
										<span class="items_select_filter_icon">
											<svg class="" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M424.4 214.7L72.4 6.6C43.8-10.3 0 6.1 0 47.9V464c0 37.5 40.7 60.1 72.4 41.3l352-208c31.4-18.5 31.5-64.1 0-82.6z"></path></svg>
										</span>
									</div>
								</div>
							</div>
							<div class="items_select_ul_wrapper">
								<div class="items_select_ul_holder">
									<div class="items_select_ul_inner">
										<ul class="items_select_ul">
											<li data-val="ss_fade" class="search_li ss_li_ss_fade"><span>Fade</span></li>
											<li data-val="ss_zoom_in" class="search_li ss_li_ss_zoom_in"><span>Zoom In</span></li>
											<li data-val="ss_zoom_out" class="search_li ss_li_ss_zoom_out"><span>Zoom Out</span></li>
											<li data-val="ss_slide_down" class="search_li ss_li_ss_slide_down"><span>Slide Down</span></li>
											<li data-val="ss_slide_right" class="search_li ss_li_ss_slide_right"><span>Slide Right</span></li>
										</ul>
									</div>
								</div>
							</div>

						</div>
					</div>
				</div>

				<div class="opts_hor_wrapper">
					<div class="opts_block">
						<div class="opts_block_label title_holder_vertical">
							<span>Speed control</span>
							<span class="questions_icon">
								<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" />
							</span>
							<span class="gs_title_vertical"><span class="title_v_subtitle">Speed control:</span> Enable/disable speed control in the players.<br /><br /><a class="gsp_title_sub_link" href="https://gspeech.io/demos" target="_blank">See live demo</a></span>
						</div>
						<div class="gs_mono_checkbox_wrapper" id="wbs_speed_enabled">
							<div class="gs_mono_switcher">
								<div class="gs_mono_switcher_button"></div>
							</div>
							<div class="gs_mono_label">
								<span class="gs_mono_label gs_mono_label_0">Disabled</span>
								<span class="gs_mono_label gs_mono_label_1">Enabled</span>
								<input type="checkbox" class="gs_mono_checkbox" value="1"/>
							</div>
						</div>
					</div>
					<div class="opts_block">
						<div class="opts_block_label title_holder_vertical">
							<span>Volume control</span>
							<span class="questions_icon">
								<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" />
							</span>
							<span class="gs_title_vertical"><span class="title_v_subtitle">Volume control:</span> Enable/disable volume control in the players.<br /><br /><a class="gsp_title_sub_link" href="https://gspeech.io/demos" target="_blank">See live demo</a></span>
						</div>
						<div class="gs_mono_checkbox_wrapper" id="wbs_volume_enabled">
							<div class="gs_mono_switcher">
								<div class="gs_mono_switcher_button"></div>
							</div>
							<div class="gs_mono_label">
								<span class="gs_mono_label gs_mono_label_0">Disabled</span>
								<span class="gs_mono_label gs_mono_label_1">Enabled</span>
								<input type="checkbox" class="gs_mono_checkbox" value="1"/>
							</div>
						</div>
					</div>
					<div class="opts_block">
						<div class="opts_block_label title_holder_vertical">
							<span>Text panel</span>
							<span class="questions_icon">
								<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" />
							</span>
							<span class="gs_title_vertical"><span class="title_v_subtitle">Text panel:</span> Enable/disable text panel in the players.<br /><br />Shows text panel, and caption the part of text, which is being read.<br /><br /><a class="gsp_title_sub_link" href="https://gspeech.io/demos" target="_blank">See live demo</a></span>
							<div class="ss_locked_icon ss_locked_icon_inner"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/lock.svg" /></div>
						</div>
						<div class="gs_mono_checkbox_wrapper ss_commercial_switcher" id="wbs_text_enabled">
							<div class="gs_mono_switcher">
								<div class="gs_mono_switcher_button"></div>
							</div>
							<div class="gs_mono_label">
								<span class="gs_mono_label gs_mono_label_0">Disabled</span>
								<span class="gs_mono_label gs_mono_label_1">Enabled</span>
								<input type="checkbox" class="gs_mono_checkbox" value="1"/>
							</div>
						</div>
					</div>
					<div class="opts_block">
						<div class="opts_block_label title_holder_vertical">
							<span>Download audio</span>
							<span class="questions_icon">
								<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" />
							</span>
							<span class="gs_title_vertical"><span class="title_v_subtitle">Download audio:</span> Enable/disable audio downloads for users.<br /><br />Shows a download icon in the players, and the downloads count.<br /><br /><a class="gsp_title_sub_link" href="https://gspeech.io/demos" target="_blank">See live demo</a></span>
							<div class="ss_locked_icon ss_locked_icon_inner"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/lock.svg" /></div>
						</div>
						<div class="gs_mono_checkbox_wrapper ss_commercial_switcher" id="wbs_download_enabled">
							<div class="gs_mono_switcher">
								<div class="gs_mono_switcher_button"></div>
							</div>
							<div class="gs_mono_label">
								<span class="gs_mono_label gs_mono_label_0">Disabled</span>
								<span class="gs_mono_label gs_mono_label_1">Enabled</span>
								<input type="checkbox" class="gs_mono_checkbox" value="1"/>
							</div>
						</div>
					</div>
					<div class="opts_block">
						<div class="opts_block_label title_holder_vertical">
							<span>Context player</span>
							<span class="questions_icon">
								<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" />
							</span>
							<span class="gs_title_vertical"><span class="title_v_subtitle">Context player:</span> Would follow the users as they scroll and allow them to control the player when it's out of view.<br /><br />Shows text panel, and caption the part of text, which is being read.<br /><br /><a class="gsp_title_sub_link" href="https://gspeech.io/demos" target="_blank">See live demo</a></span>
							<div class="ss_locked_icon ss_locked_icon_inner"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/lock.svg" /></div>
						</div>
						<div class="gs_mono_checkbox_wrapper ss_commercial_switcher" id="wbs_context_enabled">
							<div class="gs_mono_switcher">
								<div class="gs_mono_switcher_button"></div>
							</div>
							<div class="gs_mono_label">
								<span class="gs_mono_label gs_mono_label_0">Disabled</span>
								<span class="gs_mono_label gs_mono_label_1">Enabled</span>
								<input type="checkbox" class="gs_mono_checkbox" value="1"/>
							</div>
						</div>
					</div>
					<div class="opts_block">
						<div class="opts_block_label title_holder_vertical">
							<span>Plays count</span>
							<span class="questions_icon">
								<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" />
							</span>
							<span class="gs_title_vertical"><span class="title_v_subtitle">Plays count:</span> Shows how many times audio played.</span>
							<div class="ss_locked_icon ss_locked_icon_inner"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/lock.svg" /></div>
						</div>
						<div class="gs_mono_checkbox_wrapper ss_commercial_switcher" id="wbs_plays_enabled">
							<div class="gs_mono_switcher">
								<div class="gs_mono_switcher_button"></div>
							</div>
							<div class="gs_mono_label">
								<span class="gs_mono_label gs_mono_label_0">Disabled</span>
								<span class="gs_mono_label gs_mono_label_1">Enabled</span>
								<input type="checkbox" class="gs_mono_checkbox" value="1"/>
							</div>
						</div>
					</div>
				</div>

			</div>

			<div class="ss_options_group_1 ss_tab_wrapper ss_tab_translation"">

				<div class="opts_title">Translation</div>

				<div class="opts_block_label title_holder_vertical">
					<span>Translation</span>
					<span class="questions_icon">
						<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" />
					</span>
					<span class="gs_title_vertical"><span class="title_v_subtitle">Translation:</span> Give your users opportunity to translate audio to desired language instantly from the player.<br /><br />Shows a globe icon in the player, to choose the language.<br /><br /><a class="gsp_title_sub_link" href="https://gspeech.io/demos" target="_blank">See live demo</a></span>
					<div class="ss_locked_icon ss_locked_icon_inner"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/lock.svg" /></div>
				</div>

				<div class="gs_mono_checkbox_wrapper ss_commercial_switcher" id="wbs_lang_enabled">
					<div class="gs_mono_switcher">
						<div class="gs_mono_switcher_button"></div>
					</div>
					<div class="gs_mono_label">
						<span class="gs_mono_label gs_mono_label_0">Disabled</span>
						<span class="gs_mono_label gs_mono_label_1">Enabled</span>
						<input type="checkbox" class="gs_mono_checkbox" value="1"/>
					</div>
				</div>

				<div id="ss_translation_options" class="ss_display_none"></div>
			</div>

			<div class="ss_options_group_1 ss_tab_wrapper ss_tab_custom">

				<div class="opts_title">Custom Code</div>

				<div class="opts_block_label title_holder_vertical">
					<span>Custom JS</span>
					<span class="questions_icon">
						<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" />
					</span>
					<span class="gs_title_vertical"><span class="title_v_subtitle">Custom JS:</span> Write custom javascript. The script here will run in jQuery environment, when docuement is ready!</span>
					<div class="ss_locked_icon ss_locked_icon_inner"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/lock.svg" /></div>
				</div>
				<textarea class="opts_input ss_commercial_switcher" id="ss_custom_js" style="height: 250px;"></textarea>

				<div class="opts_block_label title_holder_vertical" style="margin-top: 10px;">
					<span>Custom CSS</span>
					<span class="questions_icon">
						<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" />
					</span>
					<span class="gs_title_vertical"><span class="title_v_subtitle">Custom CSS:</span> Write custom CSS.</span>
					<div class="ss_locked_icon ss_locked_icon_inner"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/lock.svg" /></div>
				</div>
				<textarea class="opts_input ss_commercial_switcher" id="ss_custom_css" style="height: 250px;"></textarea>
			</div>

			<div class="ss_options_group_1 ss_tab_wrapper ss_tab_aliases">

				<div class="opts_title">Aliases</div>

				<div class="opts_block_label title_holder_vertical">
					<span>Aliases</span>
					<span class="questions_icon">
						<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" />
					</span>
					<span class="gs_title_vertical"><span class="title_v_subtitle">Aliases:</span> Create text aliases.<br />Insert one alias per row!<br /><br />For example you need AI to be read as Artificial Intelligence. Just add the line:<br /><span class="gsp_title_sub_code">ai:artificial intelligence</span><br /><br />Aliases created here will be applied to all audio widgets. If you need to create a custom alias, do that in widget's aliases section!</span>
					<div class="ss_locked_icon ss_locked_icon_inner"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/lock.svg" /></div>
				</div>
				<textarea class="opts_input ss_commercial_switcher" id="ss_aliases" style="height: 250px;"></textarea>
			</div>

			<div class="ss_options_group_1 ss_buttons_holder">
				<div class="gsp_login_button" id="ss_website_settings_submit" data-user_id="">Save</div>
			</div>

		</div>
	</div>
	<div id="gsp_chars_stat_wrapper">
		<div class="gsp_chars_counter_wrapper">
			<div class="gsp_chars_counter">
				<div class="gsp_chars_count_title">Characters left <span class="month_data"> (From <span class="gsp_chars_itm_1">-</span>)</span></div>
				<div class="gsp_chars_count_val"><span class="gsp_chars_itm_2">-</span></div>
				<div class="gsp_chars_per_m">of <span class="gsp_month_data gsp_chars_itm_3">-</span></div>
				<div class="gsp_chars_count_progress__wrapper">
					<div class="gsp_chars_count_progress" style="width: 100%"></div>
				</div>
				<div class="gsp_chars_plan_info">You are subscribed to <span class="gsp_chars_plan_title">Free</span> plan</div>
				<a href="https://gspeech.io/#pricing" target="_blank" class="gsp_chars_block_upgrade">Upgrade</a>
			</div>
			<div class="gsp_chars_stat">
				<div class="gsp_chars_stat_title">Characters Usage Statistics</div>
				<div class="gsp_chars_stat_line">
					<div class="gsp_stat_line_title">This cycle:</div>
					<div class="gsp_stat_line_val"><span class="gsp_chars_itm_4">-</span></div>
				</div>
				<div class="gsp_chars_stat_line">
					<div class="gsp_stat_line_title">Last cycle:</div>
					<div class="gsp_stat_line_val"><span class="gsp_chars_itm_5">-</span></div>
				</div>
				<div class="gsp_chars_stat_line">
					<div class="gsp_stat_line_title">All time:</div>
					<div class="gsp_stat_line_val"><span class="gsp_chars_itm_6">-</span></div>
				</div>
			</div>
			<div class="gsp_upgrade_wrapper">
				<div class="gsp_com_f_t">Commercial features</div>
				<ul class="gsp_upg_ul">
					<li>
						<span class="title_holder_vertical">
							<span class="gsp_icon_checked"></span>
							<span>AI Voices</span>
							<span class="questions_icon">
								<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" />
							</span>
							<span class="gs_title_vertical"><span class="title_v_subtitle">AI voices:</span> Deploy the last groundbreaking technologies to generate speech with humanlike intonation. <span class="gsp_title_sub_code">They have the star icon in the list of voices.</span></span>
						</span>
					</li>
					<li>
						<span class="title_holder_vertical">
							<span class="gsp_icon_checked"></span>
							<span>Voice Tuning</span>
							<span class="questions_icon">
								<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" />
							</span>
							<span class="gs_title_vertical"><span class="title_v_subtitle">Voice Tuning:</span> Personalize the pitch of your selected voice, up to 20 semitones more or less from the default. Adjust your speaking rate to be 4x faster or slower than the normal rate. <span class="gsp_title_sub_code">Available only for premium voices.</span></span>
						</span>
					</li>
					<li>
						<span class="title_holder_vertical">
							<span class="gsp_icon_checked"></span>
							<span>Real-Time Translation</span>
							<span class="questions_icon">
								<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" />
							</span>
							<span class="gs_title_vertical"><span class="title_v_subtitle">Real-Time Translation:</span> Give your users opportunity to on-the-fly translate audio to desired language instantly from the players. We use Google Power and the best neural solutions. <span class="gsp_title_sub_code">Shows a globe icon in the players, to choose the language.</span></span>
						</span>
					</li>
					<li>
						<span class="title_holder_vertical">
							<span class="gsp_icon_checked"></span>
							<span>Multi-Lang Websites</span>
							<span class="questions_icon">
								<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" />
							</span>
							<span class="gs_title_vertical"><span class="title_v_subtitle">Multi-Lang Websites:</span> Support of multi lang websites. It will catch your webiste's current language, and show appropriate player.<br /><br />Compatible with <b>any multilingual plugin</b>! We will configure it for you!</span>
						</span>
					</li>
					<li>
						<span class="title_holder_vertical">
							<span class="gsp_icon_checked"></span>
							<span>Download audio</span>
							<span class="questions_icon">
								<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" />
							</span>
							<span class="gs_title_vertical"><span class="title_v_subtitle">Download audio:</span> Download mp3 audio files directly from the players for offline listening.<span class="gsp_title_sub_code">Shows a download icon in the players.</span></span>
						</span>
					</li>
					<li>
						<span class="title_holder_vertical">
							<span class="gsp_icon_checked"></span>
							<span>Vocie Panel</span>
							<span class="questions_icon">
								<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" />
							</span>
							<span class="gs_title_vertical"><span class="title_v_subtitle">Vocie Panel:</span> Allow your users to listen audio in desired voice(differnent male/female voices).<span class="gsp_title_sub_code">Shows a voice selector icon in the players, to choose the voice.</span></span>
						</span>
					</li>
					<li>
						<span class="title_holder_vertical">
							<span class="gsp_icon_checked"></span>
							<span>Text Panel</span>
							<span class="questions_icon">
								<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" />
							</span>
							<span class="gs_title_vertical"><span class="title_v_subtitle">Text Panel:</span> Shows text panel, and caption the part of text, which is being read.<span class="gsp_title_sub_code">Shows a text panel icon in the players, to expand the panel.</span></span>
						</span>
					</li>
					<li>
						<span class="title_holder_vertical">
							<span class="gsp_icon_checked"></span>
							<span>Context Player</span>
							<span class="questions_icon">
								<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" />
							</span>
							<span class="gs_title_vertical"><span class="title_v_subtitle">Context Player:</span> Would follow the users as they scroll and allow them to control the player when it’s out of view.<span class="gsp_title_sub_code">Shows a context player icon in the players, to expand the context player.</span></span>
						</span>
					</li>
					<li>
						<span class="title_holder_vertical">
							<span class="gsp_icon_checked"></span>
							<span>Text Aliases</span>
							<span class="questions_icon">
								<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" />
							</span>
							<span class="gs_title_vertical"><span class="title_v_subtitle">Text Aliases:</span> For example you need AI to be read as Artificial Intelligence. Just add the line: <span class="gsp_title_sub_code">ai:artificial intelligence</span><br /><br />You can create multiple text aliases in a comfortable interface. Just insert one rule per row.</span>
						</span>
					</li>
					<li>
						<span class="title_holder_vertical">
							<span class="gsp_icon_checked"></span>
							<span>Priority Support</span>
							<span class="questions_icon">
								<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" />
							</span>
							<span class="gs_title_vertical"><span class="title_v_subtitle">Priority Support:</span> We will fix any possible issues and make all the setup.</span>
						</span>
					</li>
					<li>
						<span class="title_holder_vertical">
							<span class="gsp_icon_checked"></span>
							<span>Advanced Analytics</span>
							<span class="questions_icon">
								<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/info.svg" />
							</span>
							<span class="gs_title_vertical"><span class="title_v_subtitle">Advanced Analytics:</span> More detailed analytics of the usage / play statystics.</span>
						</span>
					</li>
				</ul>
				<a href="https://gspeech.io/#pricing" target="_blank" class="gsp_upg_link">Upgrade to unlock <img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/svg/lock.svg" /></a>
			</div>
		</div>
	</div>
</div>