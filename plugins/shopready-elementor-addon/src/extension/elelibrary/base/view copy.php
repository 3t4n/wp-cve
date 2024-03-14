<?php
if (!defined('ABSPATH')) {
	exit;
}
$data = self::direct_eready_get_library_data(true); ?>
<div id="shop-ready-template-lib" class="sready-template-lib-modal-overlay">
    <div class="sready-sr-template-lib-modal-content">
        <div class="sraedy-sr-template-lib-modal-header">
            <div class="seready-header-left">
                <h2>ShopReady Templates</h2>
            </div>
            <div class="eready-header-center">

            </div>
            <div class="srrready-header-right">
                <i id="shopr-ready-template-close-icon" class="eicon-close" aria-hidden="true" title="Close"></i>
            </div>
        </div>
        <div class="sready-template-lib-modal-body">
            <div class="er-template-inner-section">
                <div class="shop-ready-sr-template-category-section er-filter-wrapper">
                    <div class="er-category-wrapper">
                        <?php
						$cats = $data['config']['block']['categories'];
						sort($cats);
						?>
                        <div class="er-tpl-category-label"> Category </div>
                        <select class="shop-sr-templates-category">
                            <option value=""> All </option>
                            <?php foreach ($cats as $cat) : ?>
                            <option value=".<?php echo esc_html(strtolower(str_replace(' ', '-', $cat))); ?>"
                                class="er-templates-cat-option"> <?php echo esc_html($cat); ?> </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="element-ready-template-search">
                        <div class="shop-sr-ready--tpl-search">
                            <span class="eicon-search"></span>
                            <input placeholder="Search term">
                        </div>
                    </div>
                    <div class="shop-ready--tpl-tag-filter">
                        <div class="header-filter" data-title="header">
                            <?php echo esc_html__('Header', 'shopready-elementor-addon'); ?>
                        </div>
                        <div class="footer-filter" data-title="footer">
                            <?php echo esc_html__('Footer', 'shopready-elementor-addon'); ?>
                        </div>
                        <div class="Page-filter" data-title="landing-page">
                            <?php echo esc_html__('Page', 'shopready-elementor-addon'); ?>
                        </div>
                    </div>
                </div>
                <div id="sready-sr-template-render-section">
                    <div class="grid shop-ready-template-grid-wrapper">
                        <div class="grid-sizer"></div>

                        <?php foreach ($data['templates'] as $item) : ?>
                        <div class="<?php echo esc_html($item['type']); ?> grid-item shop-ready-template-single-item <?php echo esc_html(strtolower(str_replace(' ', '-', $item['subtype']))); ?>"
                            data-category="<?php echo esc_attr(strtolower(str_replace(' ', '-', $item['subtype']))); ?> <?php echo esc_html($item['title']); ?>">
                            <div class="shop-ready-grid-item-inner-content">
                                <a class="er-tyemplate-view" target="_blank" href="<?php echo esc_url($item['url']); ?>"
                                    data-pro="<?php echo esc_attr($item['isPro']); ?>"
                                    data-template_id="<?php echo esc_attr($item['template_id']); ?>"
                                    data-title="<?php echo esc_attr($item['title']); ?>">
                                    <?php echo esc_html__('View', 'shopready-elementor-addon'); ?>
                                </a>
                                <div class="img-wrapper">

                                    <img loading="lazy" data-src="<?php echo esc_url($item['thumbnail']); ?>"
                                        src="<?php echo esc_url($item['thumbnail']); ?>" />
                                </div>
                                <?php if (!$item['isPro']) : ?>
                                <a class="shop-sr-template-import" href="javascript:void(0);"
                                    data-pro="<?php echo esc_attr($item['isPro']); ?>"
                                    data-template_id="<?php echo esc_attr($item['template_id']); ?>"
                                    data-title="<?php echo esc_attr($item['title']); ?>">
                                    <?php echo esc_html__('Insert', 'shopready-elementor-addon'); ?>
                                </a>
                                <?php else : ?>
                                <a class="er-template-pro" href="javascript:void(0);"
                                    data-pro="<?php echo esc_attr($item['isPro']); ?>"
                                    data-title="<?php echo esc_attr($item['title']); ?>">
                                    <?php echo esc_html__('Pro', 'shopready-elementor-addon'); ?>
                                </a>
                                <?php endif; ?>
                            </div>

                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>