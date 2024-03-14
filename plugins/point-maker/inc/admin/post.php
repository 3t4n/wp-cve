<?php

defined( 'ABSPATH' ) || exit;


function point_maker_post_modal() {

	require_once POINT_MAKER_DIR . 'inc/functions.php';
	require_once POINT_MAKER_DIR . 'inc/settings/default_type.php';
	require_once POINT_MAKER_DIR . 'inc/colors.php';
	require_once POINT_MAKER_DIR . 'inc/icons.php';

	$default_settings = point_maker_default_type_settings();

	?>
	<button type="button" id="point_maker_modal_open" class="button" data-target="p_m_overlay" style="display: none;"></button>
	<div id="p_m_overlay" class="overlay">
		<div id="p_m_preview_wrap">
			<div id="p_m_preview">

				<div id="p_m_wrap" class="p_m_wrap p_m_simple_icon">
					<div id="p_m_content" class="p_m_content p_m_relative" style="border:2px solid #7ec3d8;">
						<div id="p_m_just_title_before_icon" class="p_m_title_icon" style="display:none;">
						</div>
						<div id="p_m_title" class="p_m_title p_m_absolute p_m_flex p_m_ai_c p_m_jc_c" style="background:#7ec3d8;">
							<svg id="p_m_edit_title_icon" style="width:22px;height:22px;fill:#fff;" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 32 32">
								<path d="M21.3,26v-3.3c0-0.4-0.3-0.7-0.7-0.7h-2V11.3c0-0.4-0.3-0.7-0.7-0.7h-6.7c-0.4,0-0.7,0.3-0.7,0.7v3.3c0,0.4,0.3,0.7,0.7,0.7 h2V22h-2c-0.4,0-0.7,0.3-0.7,0.7V26c0,0.4,0.3,0.7,0.7,0.7h9.3C21,26.7,21.3,26.4,21.3,26z M18.7,7.3V4c0-0.4-0.3-0.7-0.7-0.7h-4 c-0.4,0-0.7,0.3-0.7,0.7v3.3C13.3,7.7,13.6,8,14,8h4C18.4,8,18.7,7.7,18.7,7.3z M32,16c0,8.8-7.2,16-16,16S0,24.8,0,16S7.2,0,16,0 S32,7.2,32,16z">
								</path>
							</svg>
							<div id="p_m_change_title"></div>
						</div>
						<div id="p_m_change_detail" class="p_m_detail p_m_text"></div>
						<div id="p_m_end_of_content" class="p_m_end_of_content"></div>
					</div>
				</div>



			</div>

		</div>

		<div id="p_m_operation" class="p_m_relative">
			<button id="point_maker_modal_close" class="p_m_close_button p_m_absolute" type="button">
				<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 32 32">
					<path d="M32,25.8c0,0.7-0.3,1.3-0.8,1.8l-3.7,3.7c-0.5,0.5-1.2,0.8-1.8,0.8s-1.3-0.3-1.8-0.8L16,23.3l-7.9,7.9 C7.6,31.7,6.9,32,6.2,32s-1.3-0.3-1.8-0.8l-3.7-3.7C0.3,27.1,0,26.4,0,25.8s0.3-1.3,0.8-1.8L8.7,16L0.8,8.1C0.3,7.6,0,6.9,0,6.2 s0.3-1.3,0.8-1.8l3.7-3.7C4.9,0.3,5.6,0,6.2,0s1.3,0.3,1.8,0.8L16,8.7l7.9-7.9C24.4,0.3,25.1,0,25.8,0s1.3,0.3,1.8,0.8l3.7,3.7 C31.7,4.9,32,5.6,32,6.2s-0.3,1.3-0.8,1.8L23.3,16l7.9,7.9C31.7,24.4,32,25.1,32,25.8z"/>
				</svg>
			</button>
			<div id="p_m_modal">
				<div class="p_m_flex p_m_ai_c p_m_jc_sb p_m_wrap">
					<div>
						<label class="p_m_hedding" for="p_m_edit_type"><?php esc_html_e('Type' , 'point-maker'); ?></label>
						<select id="p_m_edit_type" name="p_m_edit_type" onchange="point_maker_change_type();">
							<?php
							foreach ($default_settings['type'] as $key => $value) {
								echo '<option value="'.$key.'">' . $value . '</option>';
							}

							?>
						</select>
					</div>
					<div>
						<label class="p_m_hedding" for="p_m_edit_color"><?php esc_html_e('Base Color' , 'point-maker'); ?></label>
						<select id="p_m_edit_color" name="p_m_edit_color" onchange="point_maker_change_color();">
							<?php
							foreach ($default_settings['type_colors'] as $key => $value) {
								$colors = point_maker_get_base_color($key);
								echo '<option value="'.$key.'"'.selected( $key, 'apple_green' , false ).' style="background-color:' . $colors['base'] . ';color:'. point_maker_BlackOrWhite($colors['base']) .'">' . $value . '</option>';
							}

							?>
						</select>
					</div>
				</div>
				<hr style="margin:8px 0">
				<div class="p_m_flex p_m_ai_c p_m_jc_sb p_m_wrap" style="margin-bottom:16px">
					<div style="flex: 1;">
						<label class="p_m_hedding" for="p_m_edit_title"><?php esc_html_e('Title' , 'point-maker'); ?></label>
						<input id="p_m_edit_title" class="p_m_w100" type="text" value="" oninput="point_maker_change_title()" />
					</div>
					<div>
						<label for="p_m_select_title_icon" class="p_m_hedding" style="margin-top:8px"><?php esc_html_e('Icon of title' , 'point-maker'); ?></label>
						<label id="p_m_select_title_icon" class="p_m_select_icon p_m_flex p_m_jc_c p_m_ai_c" for="p_m_icons_list_open" onclick="point_maker_open_icon_list('p_m_select_title_icon')" style="margin: auto;">
							<?php
							echo str_replace('<svg', '<svg style="width:32px;height:32px;fill:#111;"', point_maker_get_svg_icon( 'info-circle-solid' ) );
							?>
						</label>
					</div>
				</div>
				<div class="p_m_flex p_m_ai_c p_m_wrap">
					<div style="margin-right:16px">
						<label for="p_m_title_color_background" style="flex: none;"><?php _e('Color the background','point-maker'); ?></label>
						<div class="p_m_checkbox">
							<input type="checkbox" id="p_m_title_color_background" class="" name="p_m_title_color_background" onchange="point_maker_title_color_background();" checked="checked" />
							<label for="p_m_title_color_background"></label>
						</div>
					</div>
					<div>
						<label for="p_m_title_color_border" style="flex: none;"><?php _e('Color the border','point-maker'); ?></label>
						<div class="p_m_checkbox">
							<input type="checkbox" id="p_m_title_color_border" class="" name="p_m_title_color_border" onchange="point_maker_title_color_border();" checked="checked" />
							<label for="p_m_title_color_border"></label>
						</div>
					</div>
				</div>
				<hr style="margin:8px 0">
				<label class="p_m_hedding" for="p_m_edit_content"><?php esc_html_e('Content' , 'point-maker'); ?></label>
				<textarea id="p_m_edit_content" class="p_m_w100" name="" rows="6"></textarea>
				<div class="p_m_flex p_m_ai_c p_m_jc_sb p_m_wrap">
					<div>
						<label class="p_m_hedding" style="margin-top:8px"><?php esc_html_e('Type of content' , 'point-maker'); ?></label>
						<div class="p_m_flex p_m_ai_c" style="margin:8px 0">
							<input type="radio" id="p_m_edit_content_type_text" name="p_m_edit_content_type" value="text"
							checked onchange="point_maker_change_content_type()">
							<label for="p_m_edit_content_type_text"><?php esc_html_e('Text' , 'point-maker'); ?></label>
							<input type="radio" id="p_m_edit_content_type_list" name="p_m_edit_content_type" value="list" style="margin-left: 8px;" onchange="point_maker_change_content_type()">
							<label for="p_m_edit_content_type_list"><?php esc_html_e('List' , 'point-maker'); ?></label>
						</div>
					</div>
					<div>
						<div id="p_m_select_list_icon_wrap">
							<label for="p_m_select_list_icon" class="p_m_hedding"><?php esc_html_e('Icon of List' , 'point-maker'); ?></label>
							<label id="p_m_select_list_icon" class="p_m_select_icon p_m_flex p_m_jc_c p_m_ai_c" for="p_m_icons_list_open" onclick="point_maker_open_icon_list('p_m_select_list_icon')" style="margin: auto;">
								<?php
								echo str_replace('<svg', '<svg style="width:32px;height:32px;fill:#111;"', point_maker_get_svg_icon( 'caret-right-solid' ) );
								?>
							</label>
						</div>
					</div>
				</div>
				<div class="p_m_flex p_m_ai_c p_m_wrap">
					<div style="margin-right:16px">
						<label for="p_m_content_color_background" style="flex: none;"><?php _e('Color the background','point-maker'); ?></label>
						<div class="p_m_checkbox">
							<input type="checkbox" id="p_m_content_color_background" class="" name="p_m_content_color_background" onchange="point_maker_content_color_background();" />
							<label for="p_m_content_color_background"></label>
						</div>
					</div>
					<div>
						<label for="p_m_content_color_border" style="flex: none;"><?php _e('Color the border','point-maker'); ?></label>
						<div class="p_m_checkbox">
							<input type="checkbox" id="p_m_content_color_border" class="" name="p_m_content_color_border" onchange="point_maker_content_color_border();" checked="checked" />
							<label for="p_m_content_color_border"></label>
						</div>
					</div>
				</div>
				<button id="point_maker_submit" class="p_m_flex p_m_ai_c p_m_jc_c" type="button">
					<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 32 32" style="transform: rotate(90deg);width: 32px;height: 32px;margin-right: 8px;fill:#fff;">
						<path d="M24.7,16c0,0.4-0.1,0.7-0.4,0.9L12.9,28.3c-0.3,0.3-0.6,0.4-0.9,0.4c-0.7,0-1.3-0.6-1.3-1.3v-6H1.3C0.6,21.3,0,20.7,0,20v-8 c0-0.7,0.6-1.3,1.3-1.3h9.3v-6c0-0.7,0.6-1.3,1.3-1.3c0.4,0,0.7,0.1,0.9,0.4l11.3,11.3C24.5,15.3,24.7,15.6,24.7,16z M32,8.7v14.7 c0,3.3-2.7,6-6,6h-6.7c-0.4,0-0.7-0.3-0.7-0.7c0-0.6-0.3-2,0.7-2H26c1.8,0,3.3-1.5,3.3-3.3V8.7c0-1.8-1.5-3.3-3.3-3.3h-6 c-0.5,0-1.3,0.1-1.3-0.7c0-0.6-0.3-2,0.7-2H26C29.3,2.7,32,5.4,32,8.7z"/>
					</svg>
					<span style="color: #fff;"><?php esc_html_e('Insert' , 'point-maker'); ?></span>
				</button>
			</div>

		</div>

		<input id="p_m_icons_list_open" type="checkbox" style="display:none;" >
		<div id="p_m_icons_list_box" class="">
			<label id="p_m_icons_list_label" class="p_m_absolute" for="p_m_icons_list_open"></label>
			<div id="p_m_icons_list" class="p_m_absolute">
				<div class="p_m_flex p_m_ai_c p_m_wrap">
					<?php

					foreach ($default_settings['type_icons'] as $key => $value) {
						echo '<label class="p_m_icons_list_wrap p_m_flex p_m_jc_c p_m_ai_c" data-icon_name="'.$key.'" for="p_m_icons_list_open" onclick="point_maker_select_icon(\''.$key.'\')">';
						echo str_replace('<svg', '<svg style="width:32px;height:32px;fill:#111;"', point_maker_get_svg_icon( $key ) );
						echo '</label>';
					}

					?>
				</div>
			</div>
		</div>
		<input id="p_m_now_open_icon" type="hidden" value="" />
		<input id="p_m_selected_color" type="hidden" value="mandarin_orange" />
		<input id="p_m_selected_title_icon" type="hidden" value="info-circle-solid" />
		<input id="p_m_selected_list_icon" type="hidden" value="caret-right-solid" />
		<input id="p_m_checked_title_color_background" type="hidden" value="true" />
		<input id="p_m_checked_content_color_background" type="hidden" value="false" />
		<input id="p_m_checked_title_color_border" type="hidden" value="true" />
		<input id="p_m_checked_content_color_border" type="hidden" value="true" />

	</div>
	<div id="p_m_pop_up_message"></div>

	<?php

}


