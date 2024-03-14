<div class="wrap aarambha-ds--demo__wrap">
    <h1><?php echo get_admin_page_title(); ?></h1>

    <?php if (isset($_GET['_clear']) && sanitize_text_field($_GET['_clear'])) {
    ?>
        <div class="updated notice is-dismissible">
            <p><?php esc_html_e('Cache successfully cleared.', 'aarambha-demo-sites'); ?></p>
            <button type="button" class="notice-dismiss">
                <span class="screen-reader-text">Dismiss this notice.</span>
            </button>
        </div>
    <?php
    }
    ?>

    <?php
    $categories = $data['categories'] ?: [];
    $demos      = ($data['demos'])  ? $data['demos'] : [];

    $total      = count($demos);
    ?>

    <div class="aarambha-ds--main__wrap">

        <?php if (count($categories) > 0) : ?>
            <div class="aarambha-ds--filter wp-filter hide-if-no-js">

                <div class="aarambha-ds--filter__action">
                    <div class="filter-count">
                        <span class="count theme-count">
                            <?php echo esc_html($total); ?>
                        </span>
                    </div>
                    <?php if (is_array($categories) && count($categories) > 0) : ?>
                        <ul class="aarambha-ds--filter__links filter-links">
                            <li>
                                <a href="#" data-filter="all" class="current" aria-current="page">
                                    <?php esc_html_e('All', 'aarambha-demo-sites'); ?>
                                </a>
                            </li>

                            <?php foreach ($categories as $category) : ?>
                                <li>
                                    <a href="#" data-filter="<?php echo esc_html($category['slug']); ?>">
                                        <?php echo esc_html($category['name']); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>

                    <?php endif; ?>

                </div>

                <div class="aarambha-ds--filter__search">
                    <ul class="filter-links">
                        <li>
                            <a href="#" data-filter="all" class="current">All</a>
                        </li>
                        <li><a href="#" data-filter="free">Free</a></li>
                        <li><a href="#" data-filter="pro">Pro</a></li>
                    </ul>

                    <form class="search-form">
                        <label class="screen-reader-text" for="wp-filter-search-input">Search Themes</label>
                        <input placeholder="Search themes..." type="search" aria-describedby="live-search-desc" id="wp-filter-search-input" class="aarambha-ds--search__input wp-filter-search">
                    </form>
                </div>

            </div>
        <?php endif; ?>

        <div class="aarambha-ds--demo__browser theme-browser content-filterable rendered">
            <div class="themes wp-clearfix">

                <?php if ($demos && isset($demos['data'])) : ?>
                    <?php foreach ($demos['data'] as $demo) : ?>
                        <?php
                        $demoType  = $demo['demo_type'];
                        $classes   = [];
                        $classes[] = $demoType;

                        foreach ($demo['categories'] as $slug) {
                            $classes[] = $slug;
                        }

                        $purchaseUrl = $demo['purchase_url'] ?: '#';
                        $slug        = $demo['slug'];
                        $name        = $demo['name'];

                        $nonce        = "retrieve-demo-{$slug}";

                        ?>
                        <div class="theme <?php echo implode(' ', $classes); ?>" data-slug="<?php echo esc_attr($slug); ?>">


                            <div class="theme-screenshot">
                                <?php if ($demo['image']) : ?>
                                    <img src="<?php echo esc_url($demo['image']); ?>" alt="<?php echo esc_attr($name); ?>">
                                <?php endif; ?>

                                <?php if ('pro' === $demoType) : ?>
                                    <div class="aarambha-ds--demo__ribbon">
                                        <?php esc_html_e('Pro', 'aarambha-demo-sites'); ?>
                                    </div>
                                <?php endif; ?>
                            </div>


                            <a class="more-details--demo more-details" data-slug="<?php echo esc_attr($slug); ?>" data-type="<?php echo esc_attr($demoType); ?>" data-purchase-link="<?php echo esc_url($purchaseUrl); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce($nonce) ); ?>">
                                <span class="ocdi--loader"></span>
                                <span>
                                    <?php esc_html_e('Import Demo', 'aarambha-demo-sites'); ?>
                                </span>
                            </a>

                            <div class="theme-id-container">
                                <h3 class="theme-name"><?php echo esc_html($name); ?></h3>

                                <?php if ($demo['preview']) : ?>
                                    <div class="theme-actions">
                                        <a class="button" target="_blank" href="<?php echo esc_url($demo['preview']); ?>">Preview</a>
                                    </div>
                                <?php endif; ?>
                            </div>

                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <h3>
                        <?php esc_html_e('No demos found for theme. Please try again later.', 'aarambha-demo-sites'); ?>
                    </h3>
                <?php endif; ?>

            </div>
        </div>
    </div>

</div>