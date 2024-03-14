<?php

namespace SiteSEO\Services\ContentAnalysis;

defined('ABSPATH') or exit('Cheatin&#8217; uh?');

class RenderContentAnalysis {
	public function render($analyzes, $analysis_data) {
		?>
		<div id="siteseo-analysis-tabs">
			<div id="siteseo-analysis-tabs-1">
				<div class="analysis-score">
					<?php
					$impact = array_unique(array_values(wp_list_pluck($analyzes, 'impact')));
		$svg				= '<svg role="img" aria-hidden="true" focusable="false" width="100%" height="100%" viewBox="0 0 200 200" version="1.1" xmlns="http://www.w3.org/2000/svg">
								<circle r="90" cx="100" cy="100" fill="transparent" stroke-dasharray="565.48" stroke-dashoffset="0"></circle>
								<circle id="bar" r="90" cx="100" cy="100" fill="transparent" stroke-dasharray="565.48" stroke-dashoffset="0"></circle>
							</svg>';
		$tooltip = siteseo_tooltip(__('Content analysis', 'siteseo'), __('<strong>Should be improved:</strong> red or orange dots <br> <strong>Good:</strong> yellow or green dots', 'siteseo'), '');

		if ( ! empty($impact)) {
			if (in_array('medium', $impact) || in_array('high', $impact)) {
				$score = false; ?><p class="notgood"><?php echo wp_kses($svg, ['svg' => true, 'circle' => true]); ?> <span><?php echo esc_html__('Should be improved', 'siteseo') . wp_kses_post($tooltip); ?></span></p>
						<?php
			} else {
				$score = true; ?><p class="good"><?php echo wp_kses($svg, ['svg' => true, 'circle' => true]); ?> <span><?php echo esc_html__('Good', 'siteseo') . wp_kses_post($tooltip); ?></span></p>
						<?php
			}
		} else {
			$score = false;
		}

		if ( ! empty($analysis_data) && is_array($analysis_data)) {
			$analysis_data['score'] = $score;
			update_post_meta(get_the_ID(), '_siteseo_analysis_data', $analysis_data);
		} ?>
					<span><a href="#" id="expand-all"><?php echo esc_html__('Expand', 'siteseo'); ?></a> / <a href="#" id="close-all"><?php echo esc_html__('Close', 'siteseo'); ?></a></span>
				</div><!-- .analysis-score -->
				<?php
				if ( ! empty($analyzes)) {
					$order = [
						'1' => 'high',
						'2' => 'medium',
						'3' => 'low',
						'4' => 'good',
					];

					usort($analyzes, function ($a, $b) use ($order) {
						$pos_a = array_search($a['impact'], $order);
						$pos_b = array_search($b['impact'], $order);

						return $pos_a - $pos_b;
					});

					foreach ($analyzes as $key => $value) {
						?>
						<div class="gr-analysis">
							<?php if (isset($value['title'])) { ?>
								<div class="gr-analysis-title">
									<h3>
										<button type="button" aria-expanded="false" class="btn-toggle">
											<?php if (isset($value['impact'])) { ?>
												<span class="impact <?php echo esc_attr($value['impact']); ?>" aria-hidden="true"></span>
												<span class="screen-reader-text"><?php printf(esc_html__('Degree of severity: %s','siteseo'), esc_html($value['impact'])); ?></span>
											<?php } ?>
											<span class="siteseo-arrow" aria-hidden="true"></span>
											<?php echo esc_html($value['title']); ?>
										</button>
									</h3>
								</div>
							<?php } ?>
							<?php if (isset($value['desc'])) { ?>
								<div class="gr-analysis-content" aria-hidden="true"><?php echo wp_kses_post($value['desc']); ?></div>
							<?php } ?>
						</div><!-- .gr-analysis -->
					<?php
					}
				} ?>
				</div><!-- #siteseo-analysis-tabs-1 -->
			</div><!-- #siteseo-analysis-tabs -->
		<?php
	}
}
