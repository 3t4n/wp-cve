<?php

namespace GSBEH;
?>

<div class="gs-containeer">
	<div class="gs-roow">
		<?php

		foreach ($gs_behance_shots as $gs_beh_single_shot) {
			$bfields = unserialize($gs_beh_single_shot['bfields']);
			if (!empty($atts['field'])) {
				if (in_array($atts['field'],  array_column($bfields, 'name'))) { ?>

					<div class="<?php echo esc_attr($columnClasses); ?> beh-projects-pop">
						<?php

						$gs_beh_id = rand(10, 1000);
						?>

						<div class="gs_beh_external">
							<a class="gs_beh_pop open-popup-link" data-mfp-src="#gs-beh-pop-<?php echo esc_attr($gs_beh_id); ?>" href="javascript:void(0);" target="<?php echo esc_attr($shortcode_settings['link_target']); ?>">
								<?php echo plugin()->helpers->get_shot_thumbnail($gs_beh_single_shot['thum_image'], ''); ?>
								<div class="gs_beh_overlay"><i class="fa fa-external-link"></i></div>
							</a>
						</div>
						<?php
						$extra_class = '';
						if (isset($shortcode_settings['theme']) && $shortcode_settings['theme'] === 'gs_popup_style_2') {

							$extra_class = 'gs_beh_custom_popup_style';
						}
						?>

						<div id="gs-beh-pop-<?php echo esc_attr($gs_beh_id); ?>" class="white-popup mfp-hide mfp-with-anim gs_beh_popup <?php echo esc_attr($shortcode_settings['theme']);
																																		echo esc_attr($extra_class); ?>">
							<div class="gs-beh-pop-img">
								<img src="<?php echo esc_url($gs_beh_single_shot['big_img']); ?>" />
							</div>

							<div class="gs-beh-pop-info">
								<span class="beh-proj-tit"><?php echo esc_html($gs_beh_single_shot['name']); ?></span>
								<ul class="beh-cat">
									<i class="fa fa-tags"></i>
									<?php
										foreach ($bfields as $bcat) { ?>
											<li><?php echo esc_html($bcat['name']); ?></li><?php
										} ?>
								</ul>

								<ul class="beh-stat">
									<li class="beh-app"><i class="fa fa-thumbs-o-up"></i><span class="number"><?php echo number_format_i18n($gs_beh_single_shot['blike']); ?></span></li>
									<li class="beh-views"><i class="fa fa-eye"></i><span class="number "><?php echo number_format_i18n($gs_beh_single_shot['bview']); ?></span></li>
									<li class="beh-comments"><i class="fa fa-comment-o"></i><span class="number"><?php echo number_format_i18n($gs_beh_single_shot['bcomment']); ?></span></li>
								</ul>

								<a class="beh_hover" href="<?php echo esc_url($gs_beh_single_shot['url']); ?>" target="<?php echo esc_attr($shortcode_settings['link_target']); ?>">
									<i class="fa fa-paper-plane-o"></i>
								</a>

							</div>
						</div>
					</div>
				<?php

				}
			} else { ?>

				<div class="<?php echo esc_attr($columnClasses); ?> beh-projects-pop">
					<?php
					$gs_beh_id = rand(10, 1000); ?>

					<div class="gs_beh_external">
						<a class="gs_beh_pop open-popup-link" data-mfp-src="#gs-beh-pop-<?php echo esc_attr($gs_beh_id); ?>" href="javascript:void(0);" target="<?php echo esc_attr($shortcode_settings['link_target']); ?>">
							<?php echo plugin()->helpers->get_shot_thumbnail($gs_beh_single_shot['thum_image'], ''); ?>
							<div class="gs_beh_overlay"><i class="fa fa-external-link"></i></div>
						</a>
					</div>
					<?php
					$extra_class = '';
					if (isset($shortcode_settings['theme']) && $shortcode_settings['theme'] === 'gs_popup_style_2') {
						$extra_class = 'gs_beh_custom_popup_style';
					}
					?>

					<div id="gs-beh-pop-<?php echo esc_attr($gs_beh_id); ?>" class="white-popup mfp-hide mfp-with-anim gs_beh_popup <?php echo esc_attr($shortcode_settings['theme']); ?> <?php echo esc_attr($extra_class); ?>">
						<div class="gs-beh-pop-img">
							<img src="<?php echo esc_url($gs_beh_single_shot['big_img']); ?>" />
						</div>

						<div class="gs-beh-pop-info">
							<span class="beh-proj-tit"><?php echo esc_attr($gs_beh_single_shot['name']); ?></span>
							<h4 class="gs_beh-user-name"><?php echo esc_html($gs_beh_single_shot['beusername']); ?></h4>
							<ul class="beh-cat"><i class="fa fa-tags"></i>
							<?php
																					
								foreach ($bfields as $bcats) {

									if (isset($bcats['name'])) { ?>
									<li><?php echo esc_html($bcats['name']); ?></li><?php
								}
							} ?>
							</ul>

							<i class="fa fa-thumbs-o-up"></i>
							<ul class="gs_beh-credentials">
								<li class="beh-app">
									<i class="fa fa-eye"></i>
									<span class="number"><?php echo number_format_i18n($gs_beh_single_shot['blike']); ?></span>
								</li>

								<li class="beh-views"><i class="fa fa-eye"></i><span class="number "><?php echo number_format_i18n($gs_beh_single_shot['bview']); ?></span></li>
								<li class="beh-comments"><i class="fa fa-comment-o"></i><span class="number"><?php echo number_format_i18n($gs_beh_single_shot['bcomment']); ?></span></li>

							</ul>

							<a class="beh_hover" href="<?php echo esc_url($gs_beh_single_shot['url']); ?>" target="<?php echo esc_attr($shortcode_settings['link_target']); ?>">
								<i class="fa fa-paper-plane-o"></i>
							</a>

						</div>
					</div>

				</div><?php
					}
				} ?>

	</div><?php
			do_action('gs_behance_custom_css'); ?>
</div>