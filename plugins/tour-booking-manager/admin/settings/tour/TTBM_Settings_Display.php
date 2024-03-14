<?php
	if (!defined('ABSPATH')) {
		die;
	} // Cannot access pages directly.
	if (!class_exists('TTBM_Settings_Display')) {
		class TTBM_Settings_Display {
			public function __construct() {
				add_action( 'add_ttbm_settings_tab_name', [ $this, 'add_tab' ], 90 );
				add_action('add_ttbm_settings_tab_content', [$this, 'display_settings']);
				add_action('ttbm_settings_save', [$this, 'save_display']);
			}
			public function add_tab() {
				?>
				<li class="nav-item" data-tabs-target="#ttbm_display_settings">
					<i class="fas fa-chalkboard"></i><?php esc_html_e(' Display settings', 'tour-booking-manager'); ?>
				</li>
				<?php
			}
			public function display_settings($tour_id) {
				$seat_details_checked = MP_Global_Function::get_post_info($tour_id, 'ttbm_display_seat_details', 'on') == 'off' ? '' : 'checked';
				$tour_type_checked = MP_Global_Function::get_post_info($tour_id, 'ttbm_display_tour_type', 'on') == 'off' ? '' : 'checked';
				$hotel_checked = MP_Global_Function::get_post_info($tour_id, 'ttbm_display_hotels', 'on') == 'off' ? '' : 'checked';
				$sidebar_checked = MP_Global_Function::get_post_info($tour_id, 'ttbm_display_sidebar', 'off') == 'off' ? '' : 'checked';
				$duration_checked = MP_Global_Function::get_post_info($tour_id, 'ttbm_display_duration', 'on') == 'off' ? '' : 'checked';
				?>
				<div class=" tabsItem" data-tabs="#ttbm_display_settings">
					<h2 class="h4 px-0 text-primary"><?php esc_html_e('Details Settings', 'tour-booking-manager'); ?></h2>
					
					<?php $content_title_style = MP_Global_Function::get_post_info($tour_id, 'ttbm_section_title_style') ?: 'ttbm_title_style_2'; ?>
					<?php $ticketing_system = MP_Global_Function::get_post_info($tour_id, 'ttbm_ticketing_system', 'availability_section'); ?>
					<section class="component d-flex justify-content-between align-items-center mb-2">
                        <div class="w-50 d-flex justify-content-between align-items-center">
                            <label for=""><?php esc_html_e('Section Title Style?', 'tour-booking-manager'); ?> <i class="fas fa-question-circle tool-tips"><?php TTBM_Settings::des_p('ttbm_section_title_style'); ?></i></label>
                            <div class=" d-flex justify-content-between">
								<select class="formControl" name="ttbm_section_title_style">
									<option value="style_1" <?php echo esc_attr($content_title_style == 'style_1' ? 'selected' : ''); ?>><?php esc_html_e('Style One', 'tour-booking-manager'); ?></option>
									<option value="ttbm_title_style_2" <?php echo esc_attr($content_title_style == 'ttbm_title_style_2' ? 'selected' : ''); ?>><?php esc_html_e('Style Two', 'tour-booking-manager'); ?></option>
									<option value="ttbm_title_style_3" <?php echo esc_attr($content_title_style == 'ttbm_title_style_3' ? 'selected' : ''); ?>><?php esc_html_e('Style Three', 'tour-booking-manager'); ?></option>
								</select>
                            </div>    
                        </div>
						<div class="w-50 d-flex justify-content-between align-items-center ms-5">
                            <label for=""><?php esc_html_e('Ticket Purchase Settings', 'tour-booking-manager'); ?> <i class="fas fa-question-circle tool-tips"><?php TTBM_Settings::des_p('ttbm_ticketing_system'); ?></i></label>
                            <div class=" d-flex justify-content-between">
								<select class="formControl" name="ttbm_ticketing_system">
									<option value="regular_ticket" <?php echo esc_attr(!$ticketing_system ? 'selected' : ''); ?>><?php esc_html_e('Ticket Open', 'tour-booking-manager'); ?></option>
									<option value="availability_section" <?php echo esc_attr($ticketing_system == 'availability_section' ? 'selected' : ''); ?>><?php esc_html_e('Ticket Collapse System', 'tour-booking-manager'); ?></option>
								</select>
                            </div>    
                        </div>
                    </section>
					
					<section class="component d-flex justify-content-between align-items-center mb-2">
                        <div class="w-100 d-flex justify-content-between align-items-center"> 
                            <div class="w-50 d-flex justify-content-between align-items-center">
                                <label for=""><?php esc_html_e('On/Off Seat Info', 'tour-booking-manager'); ?> <i class="fas fa-question-circle tool-tips"><?php TTBM_Settings::des_p('ttbm_display_seat_details'); ?></i></label>
                                <?php MP_Custom_Layout::switch_button('ttbm_display_seat_details', $seat_details_checked); ?> 
                            </div>
                            <div class="w-50 d-flex justify-content-between align-items-center ms-5">
                                <label for=""><?php esc_html_e('On/Off Tour Type', 'tour-booking-manager'); ?> <i class="fas fa-question-circle tool-tips"><?php TTBM_Settings::des_p('ttbm_display_tour_type'); ?></i></label>
								<?php MP_Custom_Layout::switch_button('ttbm_display_tour_type', $tour_type_checked); ?> 
                            </div>
                        </div>
                    </section>

					<section class="component d-flex justify-content-between align-items-center mb-2">
                        <div class="w-100 d-flex justify-content-between align-items-center"> 
                            <div class="w-50 d-flex justify-content-between align-items-center">
                                <label for=""><?php esc_html_e('On/Off Hotels', 'tour-booking-manager'); ?> <i class="fas fa-question-circle tool-tips"><?php TTBM_Settings::des_p('ttbm_display_hotels'); ?></i></label>
                                <?php MP_Custom_Layout::switch_button('ttbm_display_hotels', $hotel_checked); ?> 
                            </div>
                            <div class="w-50 d-flex justify-content-between align-items-center ms-5">
                                <label for=""><?php esc_html_e('On/Off Sidebar widget', 'tour-booking-manager'); ?> <i class="fas fa-question-circle tool-tips"><?php TTBM_Settings::des_p('ttbm_display_sidebar'); ?></i></label>
                                <?php MP_Custom_Layout::switch_button('ttbm_display_sidebar', $sidebar_checked); ?> 
                            </div>
                        </div>
                    </section>

					<section class="component d-flex justify-content-between align-items-center mb-2">
                        <div class="w-100 d-flex justify-content-between align-items-center"> 
                            <div class="w-100 d-flex justify-content-between align-items-center">
                                <label for=""><?php esc_html_e('On/Off Duration', 'tour-booking-manager'); ?> <i class="fas fa-question-circle tool-tips"><?php TTBM_Settings::des_p('ttbm_display_duration'); ?></i></label>
                                <?php MP_Custom_Layout::switch_button('ttbm_display_duration', $duration_checked); ?> 
                            </div>
                    </section>

					<?php do_action('add_ttbm_display_settings', $tour_id); ?>
					
				</div>
				<?php
			}
			public function save_display($tour_id) {
				if (get_post_type($tour_id) == TTBM_Function::get_cpt_name()) {
					$content_title_style = MP_Global_Function::get_submit_info('ttbm_section_title_style') ?: 'style_1';
					$ticketing_system = MP_Global_Function::get_submit_info('ttbm_ticketing_system', 'availability_section');
					$seat_info = MP_Global_Function::get_submit_info('ttbm_display_seat_details') ? 'on' : 'off';
					$sidebar = MP_Global_Function::get_submit_info('ttbm_display_sidebar') ? 'on' : 'off';
					$tour_type = MP_Global_Function::get_submit_info('ttbm_display_tour_type') ? 'on' : 'off';
					$hotels = MP_Global_Function::get_submit_info('ttbm_display_hotels') ? 'on' : 'off';
					$duration = MP_Global_Function::get_submit_info('ttbm_display_duration') ? 'on' : 'off';
					update_post_meta($tour_id, 'ttbm_section_title_style', $content_title_style);
					update_post_meta($tour_id, 'ttbm_ticketing_system', $ticketing_system);
					update_post_meta($tour_id, 'ttbm_display_seat_details', $seat_info);
					update_post_meta($tour_id, 'ttbm_display_sidebar', $sidebar);
					update_post_meta($tour_id, 'ttbm_display_tour_type', $tour_type);
					update_post_meta($tour_id, 'ttbm_display_hotels', $hotels);
					update_post_meta($tour_id, 'ttbm_display_duration', $duration);
				}
			}
		}
		new TTBM_Settings_Display();
	}