<?php

defined('ABSPATH') or exit('Please don&rsquo;t call the plugin directly. Thanks :)');

$data_attr = siteseo_metaboxes_init();
?>
<div class="postbox siteseo_ca_holder" id="siteseo_content_analysis">
<div id="siteseo-ca-tabs" class="wrap-siteseo-analysis"
	data-home-id="<?php echo esc_attr($data_attr['isHomeId']); ?>"
	data-term-id="<?php echo esc_attr($data_attr['termId']); ?>"
	data_id="<?php echo esc_attr($data_attr['current_id']); ?>"
	data_origin="<?php echo esc_attr($data_attr['origin']); ?>"
	data_tax="<?php echo esc_attr($data_attr['data_tax']); ?>">

	<?php do_action('siteseo_ca_tab_before'); ?>

	<div id="siteseo-ca-tabs-2">
		<p>
			<?php esc_html_e('Enter a few keywords for analysis to help you write optimized content.', 'siteseo'); ?>
		</p>
		<p class="description-alt">
			<svg width="24" height="24" viewBox="0 0 24 24" role="img" aria-hidden="true" focusable="false">
				<path
					d="M12 15.8c-3.7 0-6.8-3-6.8-6.8s3-6.8 6.8-6.8c3.7 0 6.8 3 6.8 6.8s-3.1 6.8-6.8 6.8zm0-12C9.1 3.8 6.8 6.1 6.8 9s2.4 5.2 5.2 5.2c2.9 0 5.2-2.4 5.2-5.2S14.9 3.8 12 3.8zM8 17.5h8V19H8zM10 20.5h4V22h-4z">
				</path>
			</svg>
			<?php esc_html_e('Writing content for your users is the most important thing! If it doesn‘t feel natural, your visitors will leave your site, Google will know it and your ranking will be affected.', 'siteseo'); ?>
		</p>
		<div class="col-left">
			<p>
				<label for="siteseo_analysis_target_kw_meta"><?php esc_html_e('Target keywords', 'siteseo'); ?>
					<?php echo wp_kses_post(siteseo_tooltip(__('Target keywords', 'siteseo'), __('Separate target keywords with commas. Do not use spaces after the commas, unless you want to include them', 'siteseo'), esc_html('my super keyword,another keyword,keyword'))); ?>
				</label>
				<input id="siteseo_analysis_target_kw_meta" type="text" name="siteseo_analysis_target_kw"
					placeholder="<?php esc_html_e('Enter your target keywords', 'siteseo'); ?>"
					aria-label="<?php esc_html_e('Target keywords', 'siteseo'); ?>"
					value="<?php echo esc_attr($siteseo_analysis_target_kw); ?>" />
			</p>

			<button id="siteseo_launch_analysis" type="button" class="<?php echo esc_attr(siteseo_btn_secondary_classes()); ?>" data_id="<?php echo esc_attr(get_the_ID()); ?>" data_post_type="<?php echo esc_attr(get_current_screen()->post_type); ?>"><?php esc_html_e('Refresh analysis', 'siteseo'); ?></button>

			<?php do_action('siteseo_ca_after_resfresh_analysis'); ?>

			<p><span class="description"><?php esc_html_e('To get the most accurate analysis, save your post first. We analyze all of your source code as a search engine would.', 'siteseo'); ?></span></p>
		</div>
			<?php do_action('siteseo_ca_before'); ?>

			<div id="siteseo-wrap-notice-target-kw" style="clear:both">
				<?php
				
					$html = '';
					$i = 0;
					if (!empty($siteseo_analysis_data['target_kws_count'])) {
						foreach($siteseo_analysis_data['target_kws_count'] as $kw => $item) {
							if(!is_array($item)){
								continue;
							}

							if(count($item['rows']) === 0){
								continue;
							}
							$html .= '<li>
									<span class="dashicons dashicons-minus"></span>
									<strong>' . esc_html($item['key']) . '</strong>
									' . sprintf(_n('is already used %d time', 'is already used %d times', count($item['rows']), 'siteseo'), count($item['rows'])). '
								</li>';
							$i++;
						}
					}
				?>

				<?php if (!empty($html)) { ?>
					<div id="siteseo-notice-target-kw" class="siteseo-notice is-warning">
						<span class="dashicons dashicons-warning"></span>
						<p><?php printf(esc_html(_n('The keyword:','These keywords:', esc_html($i), 'siteseo')), esc_html(number_format_i18n($i))); ?></p>
						<ul>
							<?php echo wp_kses_post($html); ?>
						</ul>
						<p><?php esc_html_e('You should avoid using multiple times the same keyword for different pages. Try to consolidate your content into one single page.','siteseo'); ?></p>
					</div>
				<?php } ?>
			</div>
		<?php
		if (function_exists('siteseo_get_service')) {
			$analyzes = siteseo_get_service('GetContentAnalysis')->getAnalyzes($post);
			siteseo_get_service('RenderContentAnalysis')->render($analyzes, $siteseo_analysis_data);
		} ?>
	</div>
	<?php do_action('siteseo_ca_tab_after', $data_attr['current_id']); ?>
</div>
</div>