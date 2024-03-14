<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$data = self::direct_eready_get_library_data( true ); ?>
<div id="shop-ready-template-lib" class="sready-template-lib-modal-overlay">
    <div class="sready-sr-template-lib-modal-content">
        <div class="sraedy-sr-template-lib-modal-header">
            <div class="seready-header-left">
                <img src="<?php echo esc_url( SHOP_READY_PUBLIC_ROOT_IMG . 'editor-logo.svg' ); ?>" />
                <h2>
                    <?php echo esc_html__( 'ShopReady', 'shopready-elementor-addon' ); ?>
                </h2>
            </div>
            <div class="eready-header-center">
                <div class="shop-ready--tpl-tag-filter">
                    <div class="header-filter" data-title="header">
                        <?php echo esc_html__( 'Header', 'shopready-elementor-addon' ); ?>
                    </div>
                    <div class="footer-filter" data-title="footer">
                        <?php echo esc_html__( 'Footer', 'shopready-elementor-addon' ); ?>
                    </div>
                    <div class="Page-filter" data-title="block">
                        <?php echo esc_html__( 'Block', 'shopready-elementor-addon' ); ?>
                    </div>
                </div>
            </div>
            <div class="srrready-header-right">
                <i id="shopr-ready-template-close-icon" class="eicon-close" aria-hidden="true" title="Close"></i>
            </div>
        </div>
        <div class="sready-template-lib-modal-body">
            <div class="er-template-inner-section">
                <div class="shop-ready-sr-template-category-section er-filter-wrapper">
                    <div class="shop-ready-tpl-sort-filter-wrapper">
                        <div class="er-category-wrapper">
                            <?php
							$cats = $data['config']['block']['categories'];
							sort( $cats );
							?>
                            <select class="shop-sr-templates-category">
                                <option value=""> All </option>
                                <?php foreach ( $cats as $cat ) : ?>
                                <option value=".<?php echo esc_attr( strtolower( str_replace( ' ', '-', $cat ) ) ); ?>"
                                    class="er-templates-cat-option"> <?php echo esc_html( $cat ); ?> </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="shop-ready-tpl-sort-btn">
                            <div class="shop-ready-temlpates-sorts-button-group">
                                <button class="button" data-sort-by="title" data-sort-direction="desc">Title
                                    Desc</button>
                                <button class="button" data-sort-by="title" data-sort-direction="asc">Title Asc</button>
                                <button class="button" data-sort-by="publicationdate"
                                    data-sort-direction="desc">New</button>
                                <button class="button" data-sort-by="publicationdate"
                                    data-sort-direction="asc">Popular</button>

                                <button class="button" data-sort-by="insert" data-sort-direction="desc">Free</button>
                                <button class="button" data-sort-by="pro" data-sort-direction="desc">Pro</button>

                            </div>
                        </div>
                    </div>

                    <div class="element-ready-template-search">
                        <div class="shop-sr-ready--tpl-search">
                            <span class="eicon-search"></span>
                            <input placeholder="Search term">
                        </div>
                    </div>
                </div>
                <div id="sready-sr-template-render-section">
                    <div class="grid shop-ready-template-grid-wrapper">
                        <div class="grid-sizer"></div>
                        <?php
						$shop_templates = $data['templates'];
						function shop_ready_s_date_compare( $a, $b ) {
							$t1 = strtotime( $a['date'] );
							$t2 = strtotime( $b['date'] );
							return $t1 - $t2;
						}

						usort( $shop_templates, 'shop_ready_s_date_compare' );
						?>
                        <?php foreach ( $shop_templates as $item ) : ?>
                        <div class="<?php echo esc_attr( $item['type'] ); ?> grid-item shop-ready-template-single-item <?php echo esc_attr( strtolower( str_replace( ' ', '-', $item['subtype'] ) ) ); ?>"
                            data-category="<?php echo esc_attr( strtolower( str_replace( ' ', '-', $item['subtype'] ) ) ); ?> <?php echo esc_attr( $item['title'] ); ?> <?php echo esc_attr( $item['type'] ); ?>">
                            <div class="shop-ready-grid-item-inner-content">

                                <div class="img-wrapper">
                                    <img loading="lazy" data-src="<?php echo wp_kses_post( $item['thumbnail'] ); ?>"
                                        src="<?php echo esc_url( $item['thumbnail'] ); ?>" />
                                </div>
                                <div class="action-wrapper">
                                    <div>

                                        <?php if ( ! $item['isPro'] ) : ?>
                                        <a class="shop-sr-template-import" href="javascript:void(0);"
                                            data-pro="<?php echo esc_attr( $item['isPro'] ); ?>"
                                            data-template_id="<?php echo esc_attr( $item['template_id'] ); ?>"
                                            data-title="<?php echo esc_attr( $item['title'] ); ?>">
                                            <?php echo esc_html__( 'Insert', 'shopready-elementor-addon' ); ?>
                                        </a>
                                        <?php else : ?>
                                        <a class="er-template-pro" href="javascript:void(0);"
                                            data-pro="<?php echo esc_attr( $item['isPro'] ); ?>"
                                            data-title="<?php echo esc_attr( $item['title'] ); ?>">
                                            <?php echo esc_html__( 'Pro', 'shopready-elementor-addon' ); ?>
                                        </a>
                                        <?php endif; ?>
                                        <a class="er-tyemplate-view" target="_blank"
                                            href="<?php echo esc_url( $item['url'] ); ?>"
                                            data-pro="<?php echo esc_attr( $item['isPro'] ); ?>"
                                            data-template_id="<?php echo esc_attr( $item['template_id'] ); ?>"
                                            data-title="<?php echo esc_attr( $item['title'] ); ?>">
                                            <?php echo esc_html__( 'View', 'shopready-elementor-addon' ); ?>
                                        </a>
                                    </div>

                                </div>
                                <h3 class="shop-ready-tpl-title">
                                    <b>
                                        <?php echo esc_html( $item['title'] ); ?>
                                    </b>
                                    <span class="publicationdate" hidden>
                                        <?php echo esc_html( $item['date'] ); ?>
                                    </span>
                                    <span class="pro_text" hidden>
                                        <?php echo esc_html( $item['pro_text'] ); ?>
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