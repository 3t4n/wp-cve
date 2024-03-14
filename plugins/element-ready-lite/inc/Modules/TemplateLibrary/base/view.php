<?php $data = self::direct_eready_get_library_data(true); ?>
<div id="element-ready-template-lib" class="er-template-lib-modal-overlay">
	<div class="er-template-lib-modal-content">
		<div class="er-template-lib-modal-header">
			<div class="eready-header-left">
				<img src="<?php echo esc_url(ELEMENT_READY_ROOT_IMG . 'logo.svg'); ?>" />
				<h2>
					<?php echo esc_html__('ElementsReady', 'element-ready-lite') ?>
				</h2>
			</div>
			<div class="eready-header-center">
				<div class="element-ready--tpl-tag-filter">
					<div class="header-filter" data-title="header">
						<?php echo esc_html__('Header', 'element-ready-lite'); ?>
					</div>
					<div class="footer-filter" data-title="footer">
						<?php echo esc_html__('Footer', 'element-ready-lite'); ?>
					</div>
					<div class="Page-filter" data-title="landing-page">
						<?php echo esc_html__('Page', 'element-ready-lite'); ?>
					</div>
				</div>
			</div>
			<div class="eready-header-right">
				<i id="er-ready-template-close-icon" class="eicon-close" aria-hidden="true" title="Close"></i>
			</div>
		</div>
		<div class="er-template-lib-modal-body">
			<div class="er-template-inner-section">
				<div class="er-template-category-section er-filter-wrapper">
					<div class="element-ready-tpl-sort-filter-wrapper">
						<div class="er-category-wrapper">
							<?php
							$cats = $data['config']['block']['categories'];
							$cats = array_map('ucwords', $cats);
							sort($cats);
							?>
							<select class="er-templates-category">
								<option value="">
									<?php echo esc_html__('All', 'element-ready-lite'); ?>
								</option>
								<?php foreach ($cats as $cat): ?>
									<option value=".<?php echo strtolower(str_replace(' ', '-', $cat)); ?>"
										class="er-templates-cat-option">
										<?php echo esc_html($cat); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="element-ready-tpl-sort-btn">
							<div class="element-ready-temlpates-sorts-button-group">
								<button class="button" data-sort-by="title" data-sort-direction="desc">
									<?php echo esc_html__('Title Desc', 'element-ready-lite') ?>
								</button>
								<button class="button" data-sort-by="title" data-sort-direction="asc">
									<?php echo esc_html__('Title Asc', 'element-ready-lite'); ?>
								</button>
								<button class="button" data-sort-by="publicationdate" data-sort-direction="desc">
									<?php echo esc_html__('New', 'element-ready-lite'); ?>
								</button>
								<button class="button" data-sort-by="publicationdate" data-sort-direction="asc">
									<?php echo esc_html__('Popular', 'element-ready-lite'); ?>
								</button>
								<button class="button" data-sort-by="insert" data-sort-direction="desc">
									<?php echo esc_html__('Free', 'element-ready-lite'); ?>
								</button>
								<button class="button" data-sort-by="pro" data-sort-direction="desc">
									<?php echo esc_html__('Pro', 'element-ready-lite'); ?>
								</button>
							</div>
						</div>
					</div>
					<div class="element-ready-template-search">
						<div class="element-ready--tpl-search">
							<span class="eicon-search"></span>
							<input placeholder="Search term" />
						</div>
					</div>
				</div>
				<div id="er-template-render-section">
					<div class="grid element-ready-template-grid-wrapper">
						<div class="grid-sizer"></div>
						<?php
						$er_templates = $data['templates'];
						function eelement_ready_s_date_compare($a, $b)
						{
							$t1 = strtotime($a['date']);
							$t2 = strtotime($b['date']);
							return $t1 - $t2;
						}
						usort($er_templates, 'eelement_ready_s_date_compare');
						?>
						<?php foreach ($er_templates as $item): ?>
							<div class="<?php echo esc_attr($item['type']); ?> grid-item element-ready-template-single-item <?php echo strtolower(str_replace(' ', '-', $item['subtype'])); ?>"
								data-category="<?php echo esc_attr(strtolower(str_replace(' ', '-', $item['subtype']))); ?> <?php echo esc_html($item['title']); ?>">
								<div class="element-ready-grid-item-inner-content">
									<div class="img-wrapper">
										<img loading="lazy" data-src="<?php echo esc_url($item['thumbnail']); ?>"
											src="<?php echo esc_url($item['thumbnail']); ?>" />
									</div>
									<div class="action-wrapper">
										<div>
											<?php if (!$item['isPro']): ?>
												<a class="er-template-import" href="javascript:void(0);"
													data-pro="<?php echo esc_attr($item['isPro']); ?>"
													data-template_id="<?php echo esc_attr($item['template_id']); ?>"
													data-title="<?php echo esc_attr($item['title']); ?>">
													<?php echo esc_html__('Insert', 'element-ready-lite'); ?>
												</a>
											<?php else: ?>
												<a class="er-template-pro" target="_blank"
													href="https://elementsready.com/#er-pricing"
													data-pro="<?php echo esc_attr($item['isPro']); ?>"
													data-title="<?php echo esc_attr($item['title']); ?>">
													<?php echo esc_html__('Pro', 'element-ready-lite'); ?>
												</a>
											<?php endif; ?>
											<a class="er-tyemplate-view" target="_blank"
												href="<?php echo esc_url($item['url']); ?>"
												data-pro="<?php echo esc_attr($item['isPro']); ?>"
												data-template_id="<?php echo esc_attr($item['template_id']); ?>"
												data-title="<?php echo esc_attr($item['title']); ?>">
												<?php echo esc_html__('View', 'element-ready-lite'); ?>
											</a>
										</div>
									</div>
									<h3 class="element-ready-tpl-title">
										<b>
											<?php echo esc_html($item['title']); ?>
										</b>
										<span class="publicationdate" hidden>
											<?php echo esc_html($item['date']); ?>
										</span>
									</h3>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>