<?php $search_results = $this->search_data;
if ( empty( $settings ) ) { $settings = []; } ?>
<div class="flexmls-map-wrapper" <?php echo (array_key_exists('default_view', $settings) && $settings['default_view'] == 'map' || $map_parameter_set) ? '' : 'style="display:none;"'; ?>>
	<?php
		$results_component = new fmcSearchResults;
		$pure_conditions = $results_component->get_pure_conditions( $settings );
		$results_component->load_search_results( 'shortcode', $pure_conditions );
		$results_component->render_map($search_results);
	?>
</div>
