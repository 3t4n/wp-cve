<?php
	if (!defined('ABSPATH')) {
		die;
	} // Cannot access pages directly.
	if (!class_exists('TTBM_Tour_List')) {
		class TTBM_Tour_List {
			public function __construct() {
				add_action('ttbm_all_list_item', array($this, 'all_list_item'), 10, 2);
			}
			public function all_list_item($loop, $params) {
				$style = $params['style'] ?: 'modern';
				$style = $style == 'list' ? 'modern' : $style;
				$grid_class = 'grid_' . $params['column'];
				$per_page = $params['show'] > 1 ? $params['show'] : $loop->post_count;
				$count = 0;
				?>
				<div class="all_filter_item">
					<div class="flexWrap <?php echo esc_attr($style); ?>">
						<?php
							foreach ($loop->posts as $tour) {
								$ttbm_post_id = $tour->ID;
								$tour_id = TTBM_Function::post_id_multi_language($ttbm_post_id);
								//if ($ttbm_post_id == $tour_id) {
									$active_class = $count < $per_page ? $grid_class : $grid_class . ' dNone';
									$count++;
									?>
									<div class="filter_item placeholder_area <?php echo esc_attr($active_class); ?>"
										<?php if ($params['title-filter'] == 'yes') { ?>
											data-title="<?php echo esc_attr(get_the_title($tour_id)); ?>"
										<?php } ?>
										<?php if ($params['type-filter'] == 'yes') { ?>
											data-type="<?php echo esc_attr(TTBM_Function::get_tour_type($tour_id)); ?>"
										<?php } ?>
										<?php if ($params['category-filter'] == 'yes') { ?>
											data-category="<?php echo esc_attr(TTBM_Function::get_taxonomy_id_string($tour_id, 'ttbm_tour_cat')); ?>"
										<?php } ?>
										<?php if ($params['organizer-filter'] == 'yes') { ?>
											data-organizer="<?php echo esc_attr(TTBM_Function::get_taxonomy_id_string($tour_id, 'ttbm_tour_org')); ?>"
										<?php } ?>
										<?php if ($params['location-filter'] == 'yes') {
											$location = MP_Global_Function::get_post_info($tour_id, 'ttbm_location_name');
											$location_id = $location ? get_term_by('name', $location, 'ttbm_tour_location')->term_id : '';
											?>
											data-location="<?php echo esc_attr($location_id); ?>"
										<?php } ?>
										<?php if ($params['country-filter'] == 'yes') { ?>
											data-country="<?php echo esc_attr(TTBM_Function::get_country($tour_id)); ?>"
										<?php } ?>
										<?php if ($params['month-filter'] == 'yes') { ?>
											data-month="<?php echo esc_attr(MP_Global_Function::get_post_info($tour_id, 'ttbm_month_list')); ?>"
										<?php } ?>
										<?php
											if ($params['feature-filter'] == 'yes') {
												$include_services = TTBM_Function::get_feature_list($tour_id, 'ttbm_service_included_in_price');
												?>
												data-feature="<?php echo esc_attr(TTBM_Function::feature_array_to_string($include_services)); ?>"
											<?php } ?>
										<?php
											if ($params['tag-filter'] == 'yes') {
												$tour_tags = wp_get_post_terms($tour_id, 'ttbm_tour_tag', array("fields" => "all"));
												?>
												data-tag="<?php echo esc_attr(TTBM_Function::get_tag_id($tour_tags)); ?>"
											<?php } ?>
										<?php if ($params['duration-filter'] == 'yes') { ?>
											data-duration="<?php echo esc_attr(TTBM_Function::get_duration($tour_id)); ?>"
										<?php } ?>
										<?php if ($params['activity-filter'] == 'yes') { ?>
											data-activity="<?php echo esc_attr(TTBM_Function::get_taxonomy_name_to_id_string($tour_id, 'ttbm_tour_activities', 'ttbm_tour_activities')); ?>"
										<?php } ?>
									>
										<?php
											if ($params['style'] == 'blossom') {
												include(TTBM_Function::template_path('list/blossom_list.php'));
											}
											elseif ($params['style'] == 'flora') {
												include(TTBM_Function::template_path('list/flora_list.php'));
											}
											elseif ($params['style'] == 'orchid') {
												include(TTBM_Function::template_path('list/orchid_list.php'));
											}
											elseif ($params['style'] == 'lotus') {
												include(TTBM_Function::template_path('list/lotus_list.php'));
											}
											elseif ($params['style'] == 'grid') {
												include(TTBM_Function::template_path('list/grid_list.php'));
											}
											else {
												include(TTBM_Function::template_path('list/default.php'));
											}
										?>
									</div>
								<?php //} ?>
							<?php } ?>
					</div>
				</div>
				<?php
			}
		}
		new TTBM_Tour_List();
	}