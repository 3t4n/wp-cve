<?php
if (!function_exists('xpro_dashboard_templates')) {
    function xpro_dashboard_templates()
    {

        /**
         * Template Cloud Page backend
         *
         * @package Render Xpro Template cloud
         */

        ?>
        <div id="fl-xpro-cloud-templates-form" class="xpro-bb-dashboard">

            <!-- Header -->
            <div class="xpro-bb-header-wrapper">
                <a href="<?php echo esc_url( get_site_url() . '/wp-admin/admin.php?page=xpro_dashboard_welcome')?>" class="xpro-bb-header-logo">
                    <img src="<?php echo esc_url(XPRO_ADDONS_FOR_BB_URL . 'assets/images/Logo.png'); ?>" alt="image">
                </a>
                <div class="xpro-bb-header-btn">
                    <a href="https://beaver.wpxpro.com/docs/" target="_blank" class="xpro-bb-dashboard-btn xpro-bb-btn-document">Documentation</a>
                    <a href="https://beaver.wpxpro.com/contact-us/" target="_blank" class="xpro-bb-dashboard-btn xpro-bb-btn-support">support</a>
                </div>
            </div>

            <form id="xpro-cloud-templates-form" action="<?php echo esc_url( admin_url( 'admin.php?page=xpro_dashboard_templates' ) ); ?>" method="post" data-xpro-cloud-nonce="<?php echo esc_attr( wp_create_nonce( 'xpro_cloud_nonce' ) ); ?>" onSubmit="return false;">

                <?php if ( class_exists('FLBuilderAdminSettings') && FLBuilderAdminSettings::multisite_support() && ! is_network_admin() ) : ?>
                    <label>
                        <input class="fl-override-ms-cb" type="checkbox" name="fl-override-ms" value="1"
                            <?php
                            if ( get_option( '_fl_builder_xpro_cloud_templates' ) ) {
                                echo 'checked="checked"';
                            }
                            ?>
                        />
                        <?php esc_attr_e( 'Override network settings?', 'xpro-bb-addons' ); ?>
                    </label>
                <?php endif; ?>

                <div class="xpro-settings-form-content">

                    <?php
                    WP_Filesystem();
                    global $wp_filesystem;
                    $json_theme =  $wp_filesystem->get_contents('https://bbdemos.wpxpro.com/json-data/themes.json');
                    $json_templates = $wp_filesystem->get_contents('https://bbdemos.wpxpro.com/json-data/layouts.json');
                    $json_sections = $wp_filesystem->get_contents('https://bbdemos.wpxpro.com/json-data/sections.json');

                    //Network disable
                    if(!$json_theme && !$json_templates && !$json_sections ){
                        XPRO_Cloud_Templates::message('not-found');
                        return;
                    }

                    $data[] = json_decode($json_theme,true);
                    $data[] = json_decode($json_templates,true);
                    $data[] = json_decode($json_sections,true);

                    $json_merge = json_encode($data);

                    // Converts it into a PHP object
                    $data = json_decode($json_merge,true);

                    $themeCount = 0;
                    $galleryCount = 0;
                    $portfolioCount = 0;
                    $blocksCount = 0;

                    foreach ($data as $dat){

                        foreach ($dat as $d){

                            //Industries get
                            $industry[] = $d['industry'];
                            $tags[] = $d['tags'];
                            $category = $d['category'];

                            if($d['category'] == 'themes'){
                                $tags = array_unique(explode(' ', $d['tags']));
                                foreach ($tags as $f){ $themesTags[] = $f; }
                            }
                            if($d['category'] == 'page-templates'){
                                $tags = array_unique(explode(' ', $d['tags']));
                                foreach ($tags as $f){ $templatesTags[] = $f; }
                            }
                            if($d['category'] == 'gallery'){
                                $tags = array_unique(explode(' ', $d['tags']));
                                foreach ($tags as $f){ $galleryTags[] = $f; }
                            }
                            if($d['category'] == 'portfolio'){
                                $tags = array_unique(explode(' ', $d['tags']));
                                foreach ($tags as $f){ $portfolioTags[] = $f; }
                            }
                            if($d['category'] == 'sections'){
                                $tags = array_unique(explode(' ', $d['tags']));
                                foreach ($tags as $f){ $sectionsTags[] = $f; }
                            }

                            if($d['category'] == 'themes'){$themeCount++;}
                            if($d['category'] == 'gallery'){$galleryCount++;}
                            if($d['category'] == 'portfolio'){$portfolioCount++;}
                            if($d['category'] == 'sections'){$blocksCount++;}

                        }
                    }

                    ?>

                    <!-- Append all templates -->
                    <div id="xpro-cloud-templates-tabs" class="">
                        <div class="xpro-cloud-templates-wrapper">
                            <div class="xpro-cloud-templates-header-wrapper">
                                <button class="xpro-header-toggle">Filter Dropdown<i class="xi xi-hamburger"></i></button>
                                <div id="xpro-cloud-templates-inner" class="xpro-cloud-templates-inner xpro-demo-header">
                                    <!--Industry-->
                                    <div class="xpro-demo-industry">
                                        <span class="xpro-demo-industry-label">Industries</span>
                                        <i class="xi xi-chevron-down"></i>
                                        <!--List-->
                                        <ul class="xpro-demo-dropdown-list" data-filter-group="industry">
                                            <li>
                                                <input type="checkbox" checked="checked" id="industry-all" value="all">
                                                <label for="industry-all">All Industries</label>
                                            </li>
                                            <?php
                                            foreach (array_unique($industry) as $ind){ ?>

                                                <li>
                                                    <input type="checkbox" id="industry-<?php echo $ind; ?>" value="<?php echo $ind; ?>">
                                                    <label for="industry-<?php echo $ind; ?>"> <?php echo $ind; ?> </label>
                                                </li>

                                            <?php }
                                            ?>
                                        </ul>
                                    </div>

                                    <ul class="xpro-filter-links">
                                        <li><a class="themes active" href="#xpro-themes"> <?php esc_attr_e( 'Themes', 'xpro-bb-addons' ); ?> <span class="xpro-count">0</span></a>
                                            <div class="xpro-demo-theme-tags owl-carousel">
                                                <span class="active" data-tag-filter="all">All</span>
                                                <?php
                                                foreach (array_unique($themesTags) as $tag){ ?>
                                                    <span data-tag-filter="<?php echo $tag; ?>"><?php echo str_replace('-', ' ', $tag); ?></span>
                                                <?php }
                                                ?>
                                            </div>
                                        </li>
                                        <li><a class="page-templates" href="#xpro-page-templates"> <?php esc_attr_e( 'Page Templates', 'xpro-bb-addons' ); ?> <span class="xpro-count">0</span></a>
                                            <div class="xpro-demo-theme-tags owl-carousel">
                                                <span class="active" data-tag-filter="all">All</span>
                                                <?php
                                                foreach (array_unique($templatesTags) as $tag){ ?>
                                                    <span data-tag-filter="<?php echo $tag; ?>"><?php echo str_replace('-', ' ', $tag); ?></span>
                                                <?php }
                                                ?>
                                            </div>
                                        </li>
                                        <li><a class="gallery" href="#xpro-gallery"> <?php esc_attr_e( 'Gallery', 'xpro-bb-addons' ); ?> <span class="xpro-count"></span></a>
                                            <div class="xpro-demo-theme-tags owl-carousel">
                                                <span class="active" data-tag-filter="all">All</span>
                                                <?php
                                                foreach (array_unique($galleryTags) as $tag){ ?>
                                                    <span data-tag-filter="<?php echo $tag; ?>"><?php echo str_replace('-', ' ', $tag); ?></span>
                                                <?php }
                                                ?>
                                            </div>
                                        </li>
                                        <li><a class="portfolio" href="#xpro-portfolio"> <?php esc_attr_e( 'Portfolio', 'xpro-bb-addons' ); ?> <span class="xpro-count"></span></a>
                                            <div class="xpro-demo-theme-tags owl-carousel">
                                                <span class="active" data-tag-filter="all">All</span>
                                                <?php
                                                foreach (array_unique($portfolioTags) as $tag){ ?>
                                                    <span data-tag-filter="<?php echo $tag; ?>"><?php echo str_replace('-', ' ', $tag); ?></span>
                                                <?php }
                                                ?>
                                            </div>
                                        </li>
                                        <li><a class="sections" href="#xpro-sections"> <?php esc_attr_e( 'Sections', 'xpro-bb-addons' ); ?> <span class="xpro-count"></span></a>
                                            <div class="xpro-demo-theme-tags owl-carousel">
                                                <span class="active" data-tag-filter="all">All</span>
                                                <?php
                                                foreach (array_unique($sectionsTags) as $tag){ ?>
                                                    <span data-tag-filter="<?php echo $tag; ?>"><?php echo str_replace('-', ' ', $tag); ?></span>
                                                <?php }
                                                ?>
                                            </div>
                                        </li>
                                    </ul>

                                    <div class="xpro-demo-version-list">
                                        <span>
                                            <input type="checkbox" checked="checked" id="version-all" value="all">
                                            <label for="version-all">All</label>
                                        </span>
                                        <span>
                                            <input type="checkbox" id="version-pro" value="pro">
                                            <label for="version-pro">Pro</label>
                                        </span>
                                        <span>
                                            <input type="checkbox" id="version-lite" value="lite">
                                            <label for="version-lite">Free</label>
                                        </span>
                                    </div>

                                    <div class="xpro-filter-search-area xpro-demo-search-area">
                                        <label for="template-search"></label>
                                        <input placeholder="Search..." type="search" id="template-search" class="xpro-filter-template-search xpro-demo-search">
                                    </div>

                                </div>
                            </div>
                            <div class="xpro-cloud-templates-tabs-container">

                                <div id="xpro-themes" class="xpro-dashboad-tab-content active">

                                    <div class="xpro-templates-showcase-page-templates">

                                        <div id="xpro-templates-page-templates" class="xpro-templates-grid xpro-templates-page-templates">

                                            <?php

                                            $themes_data = json_decode($json_theme,true);

                                            foreach ( $themes_data as $demo_id => $themesdata ) {
                                                // Demo Variables.
                                                $name    = isset( $themesdata['name'] ) && $themesdata['name'] ? $themesdata['name'] : null;
                                                $type    = isset( $themesdata['version'] ) && $themesdata['version'] ? $themesdata['version'] : null;
                                                $categories = isset( $themesdata['category'] ) && $themesdata['category'] ? $themesdata['category'] : null;
                                                $industry = isset($themesdata['industry']) && $themesdata['industry'] ? $themesdata['industry'] : null;
                                                $tags = isset($themesdata['tags']) && $themesdata['tags'] ? $themesdata['tags'] : null;
                                                $preview = isset( $themesdata['preview_url'] ) && $themesdata['preview_url'] ? $themesdata['preview_url'] : 'false';

                                                $filer = '';
                                                $filer = $industry;
                                                $filer .= ' ' . $categories;
                                                $filer .= ' ' . $tags;
                                                $filer .= ' ' . $type;

                                                ?>

                                                <div id="<?php echo esc_attr( $themesdata['id'] ); ?>" class="xpro-template-block xpro-single-<?php echo esc_attr( $type ); ?> <?php echo esc_attr( $filer ); ?>">
                                                    <div class="xpro-template">

                                                        <figure class="xpro-template-screenshot lazy-load post__image" data-template-name="<?php echo esc_attr( $themesdata['name'] ); ?>" data-preview-url="<?php echo esc_url( $themesdata['preview_url'] ); ?>" data-template-id='<?php echo esc_attr( $themesdata['id'] ); ?>' data-template-type='<?php echo esc_attr( $type ); ?>'>
                                                            <img data-src="<?php echo esc_url( $themesdata['image'] ); ?>" src="" alt="">
                                                        </figure>
                                                        <div class="xpro-template-item-overlay">
                                                            <span data-src-preview="<?php echo $themesdata['preview_url'] ?>" class="xpro-template-item-preview">
                                                                <i class="xi xi-eye"></i>
                                                            </span>
                                                        </div>

                                                        <div class="xpro-template-info">
                                                            <h2 class="xpro-template-name"> <?php echo esc_attr( $themesdata['name'] ); ?> </h2>
                                                            <div class="xpro-template-actions">
                                                                <?php if ($themesdata['version'] == 'pro') {
                                                                    if ( did_action( 'xpro_addons_for_bb_pro_loaded' ) ) {
                                                                        ?>
                                                                        <span class="xpro-demo-item-preview" data-popup-id="xpro-themes">
                                                                        <?php echo esc_html__( 'Download' ); ?>
                                                                    </span>
                                                                        <?php

                                                                    } else {
                                                                        ?>
                                                                        <span class="xpro-demo-item-preview" data-popup-id="addons-pro">
                                                                        <?php echo esc_html__( 'Download' ); ?>
                                                                    </span>
                                                                        <?php
                                                                    }
                                                                } else { ?>
                                                                    <span class="xpro-demo-item-preview" data-popup-id="xpro-themes">
                                                                    <?php echo esc_html__( 'Download' ); ?>
                                                                </span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>

                                <div id="xpro-page-templates" class="xpro-dashboad-tab-content">
                                    <?php
                                    // Print Templates HTML.
                                    XPRO_Cloud_Templates::template_html( 'page-templates' );
                                    ?>
                                </div>

                                <div id="xpro-gallery" class="xpro-dashboad-tab-content">
                                    <?php
                                    // Print Templates HTML.
                                    XPRO_Cloud_Templates::template_html( 'gallery' );

                                    ?>
                                </div>

                                <div id="xpro-portfolio" class="xpro-dashboad-tab-content">
                                    <?php
                                    // Print Templates HTML.
                                    XPRO_Cloud_Templates::template_html( 'portfolio' );

                                    ?>
                                </div>

                                <div id="xpro-sections" class="xpro-dashboad-tab-content">
                                    <?php
                                    // Print Templates HTML.
                                    XPRO_Cloud_Templates::template_html( 'sections' );

                                    ?>
                                </div>

                                <!--Xpro Frame Preview-->
                                <div class="xpro-preview">

                                    <div class="xpro-preview-header">

                                        <!--Preview Left-->
                                        <div class="xpro-preview-header-left">

                                            <div class="xpro-preview-header-col xpro-preview-header-arrow">
                                                <span class="xpro-preview-arrow xpro-preview-prev-demo xpro-preview-inactive"></span>
                                            </div>

                                            <div class="xpro-preview-header-col xpro-preview-header-arrow">
                                                <span class="xpro-preview-arrow xpro-preview-next-demo"></span>
                                            </div>

                                            <div class="xpro-preview-header-col xpro-preview-header-info">
                                                <span class="xpro-preview-demo-name">Original</span>
                                            </div>
                                        </div>

                                        <!--Preview Center-->
                                        <ul class="xpro-preview-header-devices">
                                            <li data-device="desktop" class="xpro-desktop active"><svg viewBox="0 0 15 15"><path d="M14.059 1.418H1.063a.829.829 0 00-.829.828v8.738c0 .457.371.829.829.829h4.792l-.144.71h-.984a.236.236 0 00-.239.235v.71c0 .13.106.235.239.235h5.668a.236.236 0 00.238-.234v-.711a.236.236 0 00-.238-.235h-.989l-.14-.71h4.793a.825.825 0 00.824-.829V2.246a.825.825 0 00-.824-.828zM10.16 12.996v.234h-5.2v-.234zm-3.965-.473l.14-.71h2.45l.14.71zm8.22-1.539c0 .2-.161.356-.356.356H1.063a.354.354 0 01-.356-.356v-.59h13.707zm0-1.062H.706V2.246c0-.2.16-.355.356-.355h12.996c.195 0 .355.156.355.355zm0 0"/><path d="M7.797 10.633h-.473a.236.236 0 000 .473h.473a.236.236 0 000-.473zm0 0"/></svg></li>
                                            <li data-device="laptop" class="xpro-laptop"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15 15"><path d="M1.438 11.45h12.175c.25 0 .457-.204.457-.458V3.168a.458.458 0 00-.457-.457H1.438a.458.458 0 00-.458.457v7.824c0 .254.208.457.458.457zM1.37 3.167c0-.035.031-.066.067-.066h12.175c.035 0 .063.03.063.066v7.824c0 .035-.028.067-.063.067H1.438a.068.068 0 01-.067-.067zm13.68 8.633v.363a.175.175 0 01-.176.176H.175A.175.175 0 010 12.164v-.363h6.18a.248.248 0 00.238.176h2.215a.248.248 0 00.238-.176zm0 0"/></svg></li>
                                            <li data-device="tablet" class="tnit-tablet"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 10"><path d="M8.395 0h-6.79a.593.593 0 00-.593.59v8.82c0 .324.265.59.593.59h6.79a.593.593 0 00.593-.59V.59A.593.593 0 008.395 0zm.253 9.41a.256.256 0 01-.253.254h-6.79a.256.256 0 01-.253-.254V.59c0-.137.113-.254.253-.254h6.79c.14 0 .253.117.253.254zm0 0"/><path d="M8.313 1.078H1.686c-.058 0-.109.05-.109.113V8.81c0 .062.05.113.11.113h6.625c.058 0 .109-.05.109-.113V1.19a.113.113 0 00-.11-.113zm-.114 7.617H1.801v-7.39h6.398zm0 0M5 9.156a.175.175 0 00-.121.047.175.175 0 00-.047.121c0 .043.016.09.047.121A.175.175 0 005 9.492c.043 0 .09-.015.121-.047a.175.175 0 00.047-.12.175.175 0 00-.047-.122A.175.175 0 005 9.156zm0 0M5 .84a.098.098 0 00.078-.035.098.098 0 00.035-.078.098.098 0 00-.035-.079A.098.098 0 005 .613a.098.098 0 00-.078.035.098.098 0 00-.035.079c0 .03.011.058.035.078.02.023.047.035.078.035zm0 0"/></svg></li>
                                            <li data-device="mobile" class="tnit-mobile"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15 15"><path d="M10.844 14.594H4.156a.811.811 0 01-.812-.809V1.215c0-.445.363-.809.812-.809h6.688c.449 0 .812.364.812.809v12.57a.811.811 0 01-.812.809zM4.156.813a.403.403 0 00-.406.402v12.57c0 .223.18.402.406.402h6.688c.226 0 .406-.18.406-.402V1.215a.403.403 0 00-.406-.403zm0 0"/><path d="M11.453 2.637H3.547a.204.204 0 01-.203-.203c0-.114.09-.204.203-.204h7.906c.113 0 .203.09.203.204 0 .109-.09.203-.203.203zm0 0M11.453 11.96H3.547a.204.204 0 01-.203-.202c0-.113.09-.203.203-.203h7.906c.113 0 .203.09.203.203 0 .11-.09.203-.203.203zm0 0M7.5 13.82a.744.744 0 01-.746-.746c0-.414.332-.746.746-.746s.746.332.746.746a.744.744 0 01-.746.746zm0-1.09a.347.347 0 00-.344.344c0 .188.157.344.344.344a.347.347 0 00.344-.344.35.35 0 00-.344-.344zm0 0M8.313 1.773H6.687a.203.203 0 010-.406h1.625a.203.203 0 010 .406zm0 0"/></svg></li>
                                        </ul>

                                        <!--Preview Right-->
                                        <div class="xpro-preview-header-right">

                                            <div class="xpro-preview-header-col xpro-preview-header-arrow">
                                                <span class="xpro-preview-arrow xpro-preview-close"></span>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="xpro-preview-iframe-outer">
                                        <iframe loading="lazy" class="xpro-preview-iframe" src=""></iframe>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>


        <!-- Theme Import Popup -->
        <div class="xpro-bb-popup-wrapper" data-popup-type="xpro-themes">
            <div class="xpro-bb-popup">
                <span class="xpro-bb-popup-close-btn">
                    <i class="dashicons dashicons-no-alt"></i>
                </span>
                <h3>XPRO THEME IMPORTER PLUGIN REQUIRED</h3>
                <h4>Just One More Step to Go!</h4>
                <h4>To download our amazing templates you need to Install Xpro Theme Plugin. It’s 100% free – no strings attached.</h4>
                <a href="https://beaver.wpxpro.com/free-premium-addons-for-beaver-builder/" class="xpro-bb-popup-button" target="_blank">Let’s Get It</a>
                <p>No thanks! I would rather go the hard way <br/> and design my own template</p>
            </div>
        </div>
        <!-- Theme Import Popup -->
        <!-- Theme Builder Popup -->
        <div class="xpro-bb-popup-wrapper" data-popup-type="xpro-beaver-themer">
            <div class="xpro-bb-popup">
                <span class="xpro-bb-popup-close-btn">
                    <i class="dashicons dashicons-no-alt"></i>
                </span>
                <h3>XPRO THEME BUILDER PLUGIN REQUIRED</h3>
                <h4>Just One More Step to Go!</h4>
                <h4>To download our amazing templates you need to Install Xpro Theme Builder Plugin.</h4>
                <a href="https://beaver.wpxpro.com/xpro-beaver-themer/" class="xpro-bb-popup-button" target="_blank">Let’s Get It</a>
                <p>No thanks! I would rather go the hard way <br/> and design my own template</p>
            </div>
        </div>
        <!-- Theme Builder Popup -->
        <!-- Gallery Popup Pro -->
        <div class="xpro-bb-popup-wrapper" data-popup-type="gallery-pro">
            <div class="xpro-bb-popup">
                <span class="xpro-bb-popup-close-btn">
                    <i class="dashicons dashicons-no-alt"></i>
                </span>
                <h3>XPRO GALLARY REQUIRED</h3>
                <h4>For Pro Gallery Templates you have to download and install Xpro Gallery Pro For Beaver Builder.</h4>
                <a href="https://beaver.wpxpro.com/filterable-gallery-for-beaver-builder/#pricing" class="xpro-bb-popup-button" target="_blank">CLICK HERE TO GET</a>
                <p>No thanks! I would rather go the hard way <br/> and design my own template</p>
            </div>
        </div>
        <!-- Gallery Popup Pro -->
        <!-- Gallery Popup Lite -->
        <div class="xpro-bb-popup-wrapper" data-popup-type="gallery-lite">
            <div class="xpro-bb-popup">
                <span class="xpro-bb-popup-close-btn">
                    <i class="dashicons dashicons-no-alt"></i>
                </span>
                <h3>XPRO GALLERY REQUIRED</h3>
                <h4>For Free GALLERY Templates you have to download and install Xpro Gallery For Beaver Builder from WordPress.</h4>
                <a href="https://wordpress.org/plugins/filterable-photo-gallery-beaver-builder-elementor/" class="xpro-bb-popup-button" target="_blank">CLICK HERE TO GET</a>
                <p>No thanks! I would rather go the hard way <br/> and design my own template</p>
            </div>
        </div>
        <!-- Gallery Popup Lite -->
        <!-- Portfolio Popup Pro -->
        <div class="xpro-bb-popup-wrapper" data-popup-type="portfolio-pro">
            <div class="xpro-bb-popup">
                <span class="xpro-bb-popup-close-btn">
                    <i class="dashicons dashicons-no-alt"></i>
                </span>
                <h3>XPRO PORTFOLIO REQUIRED</h3>
                <h4>For Pro Portfolio Templates you have to download and install Xpro Portfolio Pro For Beaver Builder.</h4>
                <a href="https://beaver.wpxpro.com/creative-portfolio/#pricing" class="xpro-bb-popup-button" target="_blank">CLICK HERE TO GET</a>
                <p>No thanks! I would rather go the hard way <br/> and design my own template</p>
            </div>
        </div>
        <!-- Portfolio Popup Pro -->
        <!-- Portfolio Popup Lite -->
        <div class="xpro-bb-popup-wrapper" data-popup-type="portfolio-lite">
            <div class="xpro-bb-popup">
                <span class="xpro-bb-popup-close-btn">
                    <i class="dashicons dashicons-no-alt"></i>
                </span>
                <h3>XPRO PORTFOLIO REQUIRED</h3>
                <h4>For Free Portfolio Templates you have to download and install Xpro Portfolio For Beaver Builder from WordPress.</h4>
                <a href="https://wordpress.org/plugins/filterable-portfolio-for-beaver-builder-elementor/" class="xpro-bb-popup-button" target="_blank">CLICK HERE TO GET</a>
                <p>No thanks! I would rather go the hard way <br/> and design my own template</p>
            </div>
        </div>
        <!-- Portfolio Popup Lite -->
        <!-- Addons Popup Pro -->
        <div class="xpro-bb-popup-wrapper" data-popup-type="addons-pro">
            <div class="xpro-bb-popup">
                <span class="xpro-bb-popup-close-btn">
                    <i class="dashicons dashicons-no-alt"></i>
                </span>
                <h3>XPRO ADDONS REQUIRED</h3>
                <h4>For Pro Templates and Sections you have to download and install Xpro Addons For Beaver Builder.</h4>
                <a href="https://beaver.wpxpro.com/modules/#addons-pricing" class="xpro-bb-popup-button" target="_blank">CLICK HERE TO GET</a>
                <p>No thanks! I would rather go the hard way <br/> and design my own template</p>
            </div>
        </div>
        <!-- Addons Popup Pro -->
        <!-- Addons Popup Lite -->
        <div class="xpro-bb-popup-wrapper" data-popup-type="addons-lite">
            <div class="xpro-bb-popup">
                <span class="xpro-bb-popup-close-btn">
                    <i class="dashicons dashicons-no-alt"></i>
                </span>
                <h3>XPRO ADDONS REQUIRED</h3>
                <h4>For Free Templates and Section you have to download and install Xpro Addons For Beaver Builder from WordPress.</h4>
                <a href="https://wordpress.org/plugins/xpro-addons-beaver-builder-elementor/" class="xpro-bb-popup-button" target="_blank">CLICK HERE TO GET</a>
                <p>No thanks! I would rather go the hard way <br/> and design my own template</p>
            </div>
        </div>
        <!-- Addons Popup Lite -->

        <?php
    }
}
