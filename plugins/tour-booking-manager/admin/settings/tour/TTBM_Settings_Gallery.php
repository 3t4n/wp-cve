<?php
	if (!defined('ABSPATH')) {
		die;
	} // Cannot access pages directly.
	if (!class_exists('TTBM_Settings_Gallery')) {
		class TTBM_Settings_Gallery {
			public function __construct() {
				add_action('add_ttbm_settings_tab_name', [$this, 'add_tab'], 90);
				add_action('add_ttbm_settings_tab_content', [$this, 'gallery_settings']);
				add_action('ttbm_settings_save', [$this, 'save_gallery']);
			}
			public function add_tab() {
				?>
				<li class="nav-item" data-tabs-target="#ttbm_settings_gallery">
					<i class="fas fa-images"></i><?php esc_html_e('Gallery ', 'tour-booking-manager'); ?>
				</li>
				<?php
			}
			public function gallery_settings($tour_id) {
				$display = MP_Global_Function::get_post_info($tour_id, 'ttbm_display_slider', 'on');
				$active = $display == 'off' ? '' : 'mActive';
				$checked = $display == 'off' ? '' : 'checked';
				$image_ids = MP_Global_Function::get_post_info($tour_id, 'ttbm_gallery_images', array());
				?>
				
				<div class="tabsItem ttbm_settings_gallery" data-tabs="#ttbm_settings_gallery">
					<h2 class="h4 px-0 text-primary"><?php esc_html_e('Gallery Settings', 'tour-booking-manager'); ?></h2>
					<section class="component d-flex justify-content-between align-items-center mb-2">
                        <div class="w-100 d-flex justify-content-between align-items-center">
                            <label for=""><?php esc_html_e('On/Off Slider', 'tour-booking-manager'); ?> <i class="fas fa-question-circle tool-tips"><?php TTBM_Settings::des_p('ttbm_display_slider'); ?></i></label>
                            <div class=" d-flex justify-content-between">
								<?php MP_Custom_Layout::switch_button('ttbm_display_slider', $checked); ?>
                            </div>    
                        </div>
                    </section>

					<div data-collapse="#ttbm_display_slider" class="<?php echo esc_attr($active); ?>">
						
						<section class="component d-flex flex-column justify-content-between align-items-start mb-2">
							<div class="w-100 d-flex justify-content-between align-items-start mb-2">
								<label for=""><?php esc_html_e('Gallery Images ', 'tour-booking-manager'); ?> <i class="fas fa-question-circle tool-tips"><?php TTBM_Settings::des_p('ttbm_gallery_images'); ?></i></label>
								
							</div>
							<div class="w-100 d-flex justify-content-between align-items-start">
								<?php TTBM_Layout::add_multi_image('ttbm_gallery_images', $image_ids); ?>
							</div>
						</section>
						
					</div>
				</div>
				<?php
			}
			public function save_gallery($tour_id) {
				if (get_post_type($tour_id) == TTBM_Function::get_cpt_name()) {
					$slider = MP_Global_Function::get_submit_info('ttbm_display_slider') ? 'on' : 'off';
					update_post_meta($tour_id, 'ttbm_display_slider', $slider);
					$images = MP_Global_Function::get_submit_info('ttbm_gallery_images', array());
					$all_images = explode(',', $images);
					update_post_meta($tour_id, 'ttbm_gallery_images', $all_images);
				}
			}
		}
		new TTBM_Settings_Gallery();
	}