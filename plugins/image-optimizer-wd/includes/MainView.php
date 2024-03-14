<?php
namespace TenWebPluginIO;

class MainView
{
    public $boosterPluginStatus; //0-not installed, 1-not active, 2-active\
    public $dataIO;
    private $imagesCount;
    private $speedScore;
    private $totalNotOptimizedImages = 0;
    private $notOptimizedTotalSize = 0;
    private $totalOptimizedImages = 0;
    private $allSet = false;
    private $totalOptimizedFull = 0;
    private $lastOptimizedSize = 0;
    private $lastOptimizedPercent = 0;
    private $totalReduced = 0;
    private $totalReducedPercent = 0;

    public function __construct()
    {
        $this->setVar();
        $this->enqueueScripts();

        if ($this->imagesCount == 0) {
            //if there is no images in media library
            echo $this->emptyMediaLibraryView();
        } else if ($this->allSet && $this->boosterPluginStatus == 2) {
            // if all images are optimized and booster is active
            echo $this->allSetBoosterActiveView();
        } else {
            //all other cases
            echo $this->displayMainPage();
        }
    }

    private function setVar()
    {
        $this->boosterPluginStatus = \TenWebPluginIO\Utils::getBoosterStatus();
        $this->speedScore = $this->getspeedScore();
        $this->dataIO = \TenWebPluginIO\Utils::getImagesData();
        if (!empty($this->dataIO)) {
            if (!empty($this->dataIO['not_optimized'])) {
                $this->totalNotOptimizedImages = (int)$this->dataIO['not_optimized']['full']
                    + $this->dataIO['not_optimized']['thumbs'] + $this->dataIO['not_optimized']['other'];
                $this->notOptimizedTotalSize = \TenWebPluginIO\Utils::formatBytes((float)$this->dataIO['not_optimized']['total_size']);
                if ($this->notOptimizedTotalSize == 0 && $this->imagesCount !== 0) {
                    $this->allSet = true;
                }
            }
            if (!empty($this->dataIO['compressed'])) {
                $this->totalOptimizedImages = (int)$this->dataIO['compressed']['full']
                    + $this->dataIO['compressed']['thumbs'] + $this->dataIO['compressed']['other'];
                $this->totalOptimizedFull = (int)$this->dataIO['compressed']['full'];
            }
            if (!empty($this->dataIO['last_optimized'])) {
                $this->lastOptimizedSize = \TenWebPluginIO\Utils::formatBytes((float)$this->dataIO['last_optimized']['size']);
                $this->lastOptimizedPercent = number_format((float)$this->dataIO['last_optimized']['percent'], 2, '.', '');
            }
            if (!empty($this->dataIO['reduced'])) {
                $this->totalReduced = \TenWebPluginIO\Utils::formatBytes((float)$this->dataIO['reduced']['total_reduced']);
                $this->totalReducedPercent = number_format((float)$this->dataIO['reduced']['total_reduced_percent'], 2, '.', '');
            }
        }

        $this->imagesCount = $this->totalNotOptimizedImages + $this->totalOptimizedImages;
    }

    private function getspeedScore()
    {
        $iowd_speed_score = get_option('iowd_speed_score');
        $speed_score = array(
            'url'                  => get_home_url(),
            'last_analyzed_time'   => '',
        );
        if (!empty($iowd_speed_score) && !empty($iowd_speed_score['last']) && !empty($iowd_speed_score['last']['url'])) {
            $last_url = $iowd_speed_score['last']['url'];
            if (!empty($iowd_speed_score[$last_url]['desktop_score'])
                && !empty($iowd_speed_score[$last_url]['desktop_loading_time'])
                && !empty($iowd_speed_score[$last_url]['mobile_score'])
                && !empty($iowd_speed_score[$last_url]['mobile_loading_time'])
                && !empty($iowd_speed_score[$last_url]['last_analyzed_time'])) {
                $speed_score = array(
                    'url'                  => $last_url,
                    'desktop_score'        => $iowd_speed_score[$last_url]['desktop_score'],
                    'desktop_loading_time' => $iowd_speed_score[$last_url]['desktop_loading_time'],
                    'mobile_score'         => $iowd_speed_score[$last_url]['mobile_score'],
                    'mobile_loading_time'  => $iowd_speed_score[$last_url]['mobile_loading_time'],
                    'last_analyzed_time'   => $iowd_speed_score[$last_url]['last_analyzed_time'],
                );
            }
        }

        return $speed_score;
    }

    private function enqueueScripts()
    {
        wp_register_style('iowd-open-sans', 'https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700,800&display=swap');
        wp_enqueue_style(TENWEBIO_PREFIX . 'main_page_css',
            TENWEBIO_URL . '/assets/css/main_page.css', array(), TENWEBIO_VERSION);
        wp_enqueue_style(TENWEBIO_PREFIX . 'main_css',
            TENWEBIO_URL . '/assets/css/main.css', array('iowd-open-sans'), TENWEBIO_VERSION);
        wp_enqueue_script('iowd_circle_js', TENWEBIO_URL . '/assets/js/circle-progress.js', array('jquery'), TENWEBIO_VERSION);
        wp_enqueue_script('iowd_main_js', TENWEBIO_URL . '/assets/js/main.js', array('jquery', 'iowd_circle_js'), TENWEBIO_VERSION);
        wp_localize_script('iowd_main_js', 'iowd', array(
            'wrong_domain_url'   => __('Please enter a URL from your domain.', 'tenweb-image-optimizer'),
            'wrong_url'          => __('Please enter correct URL.', 'tenweb-image-optimizer'),
            'enter_page_url'     => __('Please enter a Page URL.', 'tenweb-image-optimizer'),
            'page_is_not_public' => __('This page is not public. Please publish the page to check the score.', 'tenweb-image-optimizer'),
            'home_url'           => get_home_url(),
            'home_speed_status'  => $this->checkHomeSpeedStatus(),
            'booster_admin_url'  => get_admin_url() . 'admin.php?page=two_settings_page',
            'something_wrong'    => __('Something went wrong, please try again.', 'tenweb-image-optimizer'),
            'speed_ajax_nonce'   => wp_create_nonce('speed_ajax_nonce'),
            'nonce'              => wp_create_nonce('iowd_nonce'),
            'ajax_url'           => admin_url('admin-ajax.php'),
        ));
    }

    private function displayMainPage()
    {
        $domain_id = (int)get_option(TENWEBIO_MANAGER_PREFIX . '_domain_id', 0);
        $optimize_now_link = TENWEB_DASHBOARD . '/websites/' . $domain_id . '/booster/image-optimizer';
        if ($this->allSet) {
            $desc1 = __('Our plugin is now optimizing your website. Manage optimization settings from the 10Web dashboard.', 'tenweb-image-optimizer');
            $button_manage = __('Manage', 'tenweb-image-optimizer');
            $manage_button_url = TENWEB_DASHBOARD . '/websites/' . $domain_id . '/booster/image-optimizer';
        } else {
            $desc1 = __('Manage all optimization settings from your 10Web dashboard for easier control over<br> 
optimization modes and images.', 'tenweb-image-optimizer');
        }
        ob_start();
        ?>
        <div class="iowd-main-container iowd-flex-center">
            <?php
            echo $this->freePlanBanner();
            if ($this->allSet) { ?>
                <div class="iowd-white-with-borders">
                    <p class="iowd-status-label iowd-status-green-mark iowd-text-bold iowd-main-small-description">
                        <?php esc_html_e('Site is connected', 'tenweb-image-optimizer'); ?>
                    </p>
                    <p class="iowd-main-title iowd-text-bold iowd-active-title">
                        <?php esc_html_e('Image Optimizer is active', 'tenweb-image-optimizer'); ?>
                    </p>
                    <p class="iowd-main-medium-description">
                        <?php echo wp_kses_post($desc1); ?>
                    </p>
                    <div class="iowd-manage-button-container">
                        <a class="iowd-button iowd-button-green-transparent"
                           href="<?php echo esc_url($manage_button_url); ?>">
                            <?php echo esc_html($button_manage); ?>
                        </a>
                    </div>
                </div>
            <?php } ?>
            <div class="iowd-white-with-borders">
                <?php if (!$this->allSet) { ?>
                    <div>
                        <p class="iowd-status-label iowd-status-green-mark iowd-text-bold iowd-main-small-description">
                            <?php esc_html_e('Site is connected', 'tenweb-image-optimizer'); ?>
                        </p>
                        <p class="iowd-main-title iowd-text-bold iowd-active-title">
                            <?php esc_html_e('Image Optimizer is active', 'tenweb-image-optimizer'); ?>
                        </p>
                        <p class="iowd-main-medium-description">
                            <?php echo wp_kses_post($desc1); ?>
                        </p>
                    </div>
                    <div class="iowd-images-count-section iowd-flex-space-between">
                        <div class="iowd-optimized-images-section iowd-grey-bg-section">
                            <p class="iowd-status-label iowd-status-green-mark iowd-text-bold iowd-main-small-description">
                                <?php esc_html_e('Optimized', 'tenweb-image-optimizer'); ?>
                            </p>
                            <div class="iowd-images-count-numbers">
                                <span class="iowd-huge-numbers iowd-text-bold"><?php echo $this->totalOptimizedImages; ?></span>
                                <span class="iowd-huge-numbers-text">
                                <?php esc_html_e('images', 'tenweb-image-optimizer'); ?>
                            </span>
                            </div>
                            <p class="iowd-main-small-description">
                                <?php
                                $opt_images_desc = sprintf(__('The number of optimized images<br> in your media library(%s full-sized).', 'tenweb-image-optimizer'), $this->totalOptimizedFull);
                                echo wp_kses_post($opt_images_desc); ?>
                            </p>
                        </div>
                        <div class="iowd-not-optimized-images-section iowd-grey-bg-section">
                            <p class="iowd-status-label iowd-status-red-cross iowd-text-bold iowd-main-small-description">
                                <?php esc_html_e('Not optimized', 'tenweb-image-optimizer'); ?>
                            </p>
                            <div class="iowd-images-count-numbers">
                                <span class="iowd-huge-numbers iowd-text-bold"><?php echo $this->totalNotOptimizedImages; ?></span>
                                <span class="iowd-huge-numbers-text">
                                <?php esc_html_e('images', 'tenweb-image-optimizer'); ?>
                            </span>
                            </div>
                            <p class="iowd-main-small-description">
                                <?php
                                $not_opt_desc = __('The number of images in your media library that<br> need optimization for faster loading.', 'tenweb-image-optimizer');
                                echo wp_kses_post($not_opt_desc);
                                ?>
                            </p>
                            <div class="iowd-not-optimized-images-size iowd-flex-space-between">
                                <div class="iowd-not-optimizes-images-text">
                                    <p class="iowd-main-small-description iowd-text-bold">
                                        <?php esc_html_e('Total size:', 'tenweb-image-optimizer'); ?>
                                    </p>
                                    <p class="iowd-main-small-description iowd_total_size_value">
                                        <?php echo $this->notOptimizedTotalSize; ?>
                                    </p>
                                </div>
                                <div>
                                    <a class="iowd-button iowd-button-green-transparent"
                                       href="<?php echo esc_url($optimize_now_link); ?>">
                                        <?php esc_html_e('Optimize now', 'tenweb-image-optimizer'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }
                if ($this->boosterPluginStatus != 2) {
                    $this->BoosterViewPart();
                } ?>
            </div>
            <?php echo $this->mainPageFooterPart(); ?>
        </div>
        <?php
        return ob_get_clean();
    }

    public function checkHomeSpeedStatus()
    {
        $hompage_optimized = get_option('iowd_homepage_optimized');
        /* Case when homepage optimized but score not updated */
        if (!empty($hompage_optimized) && $hompage_optimized == 1) {
            return 0;
        }
        $speed_score = get_option('iowd_speed_score');
        if (!empty($speed_score) && isset($speed_score['last']) && isset($speed_score['last']['url'])) {
            $url = $speed_score['last']['url'];
            if (isset($speed_score[$url]) && $speed_score[$url]['desktop_score'] && $speed_score[$url]['mobile_score']) {
                return array(
                    'desktop_score' => $speed_score[$url]['desktop_score'],
                    'mobile_score'  => $speed_score[$url]['mobile_score'],
                );
            }
        }

        return 0;
    }

    private function BoosterViewPart()
    {
        ?>
        <div class="iowd-booster-info iowd-grey-bg-section">
            <p class="iowd-main-title iowd-text-bold">
                <?php esc_html_e('Optimize your website with 10Web Booster', 'tenweb-image-optimizer'); ?>
            </p>
            <p class="iowd-main-medium-description">
                <?php esc_html_e('Go beyond image optimization and optimize your website for free, with 10Web Booster.',
                    'tenweb-image-optimizer'); ?>
            </p>
            <div class="iowd-flex-end iowd-flex-space-between">
                <div class="iowd-booster-check-list">
                    <ul>
                        <li class="iowd-main-small-description"><?php esc_html_e('90+ PageSpeed score', 'tenweb-image-optimizer'); ?></li>
                        <li class="iowd-main-small-description"><?php esc_html_e('Higher Google rankings', 'tenweb-image-optimizer'); ?></li>
                        <li class="iowd-main-small-description"><?php esc_html_e('Full website caching', 'tenweb-image-optimizer'); ?></li>
                        <li class="iowd-main-small-description">
                            <?php esc_html_e('CSS, HTML & JS minification and compression', 'tenweb-image-optimizer'); ?>
                        </li>
                    </ul>
                </div>
                <div>
                    <a class="iowd-button iowd-button-green-fillcolor iowd-install-optimize" href="#">
                        <?php esc_html_e('Install & optimize', 'tenweb-image-optimizer'); ?>
                    </a>
                </div>
            </div>
        </div>
        <div class="iowd-score-check-section">
            <p class="iowd-main-medium-description iowd-text-semi-bold">
                <?php esc_html_e('Check your score', 'tenweb-image-optimizer'); ?>
            </p>
            <div class="iowd-sc-input-section iowd-flex-space-between">
                <div class="iowd-analyze-input-container">
                    <input class="iowd-main-medium-description iowd-analyze-input" type="url" placeholder="https://example.com">
                    <?php $page_is_public = true;
                    if ($page_is_public === 0) { ?>
                        <p class="iowd-error-msg"><?php esc_html_e('This page is not public. Please publish the page to check the score.', 'tenweb-image-optimizer'); ?></p>
                    <?php } ?>
                </div>
                <div>
                    <a class="iowd-button iowd-button-green-transparent iowd-analyze-input-button iowd-disable-button">
                        <?php esc_html_e('Analyze', 'tenweb-image-optimizer'); ?>
                    </a>
                </div>
            </div>
            <div class="iowd-flex-space-between iowd-sc-container">
                <div class="iowd-sc-info">
                    <div class="iowd-sc-datas iowd-flex-space-between">
                        <div class="iowd-sc-data iowd-sc-mobile iowd-grey-bg-section">
                            <div class="iowd-score-circle iowd_circle_with_bg  <?php echo empty($this->speedScore['mobile_score']) ? 'iowd-hidden' : ''; ?>"
                                 data-score="<?php echo esc_attr($this->speedScore['mobile_score']); ?>" data-size="76"
                                 data-thickness="5" data-id="mobile">
                                <span class="iowd-score-circle-animated"></span>
                            </div>
                            <div class="iowd_score_info  <?php echo empty($this->speedScore['mobile_score']) ? 'iowd-hidden' : ''; ?>">
                                <p class="iowd-score-device iowd-text-bold"><?php _e('Mobile score', 'tenweb-speed-optimizer') ?></p>
                                <p class="iowd-main-small-description"><?php _e('Load time: ', 'tenweb-speed-optimizer'); ?>
                                    <span class="iowd_load_time"><?php esc_html_e($this->speedScore['mobile_loading_time']); ?></span>
                                </p>
                            </div>
                            <div class="iowd-score-reanalyze-container <?php echo !empty($this->speedScore['mobile_score']) ? 'iowd-hidden' : ''; ?>">
                                <a onclick="<?php echo 'iowd_get_google_score( \'' . esc_url($this->speedScore['url']) . '\' )';?>" data-from-io-page="1" data-post_url="<?php echo esc_url($this->speedScore['url']) ?>"
                                   data-initiator="io-page" class="iowd-reanalyze-button">
                                </a>
                            </div>
                        </div>
                        <div class="iowd-sc-data iowd-sc-desktop iowd-grey-bg-section">
                            <div class="iowd-score-circle iowd_circle_with_bg <?php echo empty($this->speedScore['desktop_score']) ? ' iowd-hidden' : ''; ?>"
                                 data-score="<?php echo esc_attr($this->speedScore['desktop_score']); ?>" data-size="76"
                                 data-thickness="5" data-id="mobile">
                                <span class="iowd-score-circle-animated"></span>
                            </div>
                            <div class="iowd_score_info <?php echo empty($this->speedScore['desktop_score']) ? 'iowd-hidden' : ''; ?>">
                                <p class="iowd-score-device iowd-text-bold"><?php _e('Desktop score', 'tenweb-speed-optimizer') ?></p>
                                <p class="iowd-main-small-description"><?php _e('Load time: ', 'tenweb-speed-optimizer'); ?>
                                    <span class="iowd_load_time"><?php esc_html_e($this->speedScore['desktop_loading_time']); ?></span>
                                </p>
                            </div>
                            <div class="iowd-score-reanalyze-container <?php echo !empty($this->speedScore['desktop_score']) ? 'iowd-hidden' : ''; ?>">
                                <a onclick="<?php echo 'iowd_get_google_score( \'' . esc_url($this->speedScore['url']) . '\' )';?>" data-from-io-page="1" data-post_url="<?php echo esc_url($this->speedScore['url']) ?>"
                                   data-initiator="io-page" class="iowd-reanalyze-button">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="iowd-sc-scales iowd-grey-bg-section iowd-flex-center">
                        <p class="iowd-main-small-description iowd-text-semi-bold">
                            <?php esc_html_e('Scale:', 'tenweb-speed-optimizer'); ?>
                            <span class="iowd-scale-green iowd-scale-each"></span>
                            <?php esc_html_e('90-100 (fast)', 'tenweb-speed-optimizer'); ?>
                        </p>
                        <p class="iowd-main-small-description iowd-text-semi-bold">
                            <span class="iowd-scale-orange iowd-scale-each"></span>
                            <?php esc_html_e('50-89 (average)', 'tenweb-speed-optimizer'); ?>
                        </p>
                        <p class="iowd-main-small-description iowd-text-semi-bold">
                            <span class="iowd-scale-red iowd-scale-each"></span>
                            <?php esc_html_e('0-49 (slow)', 'tenweb-speed-optimizer'); ?>
                        </p>
                    </div>
                </div>
                <div class="iowd-page-url-info iowd-grey-bg-section">
                    <p class="iowd-main-small-description iowd-text-semi-bold iowd-check-pagespeed">
                        <?php esc_html_e('Check your score with', 'tenweb-speed-optimizer'); ?>
                        <br>
                        <a class="iowd-main-small-description iowd-text-semi-bold"
                           href="<?php esc_url('https://pagespeed.web.dev/') ?>">
                            <?php esc_html_e('Google PageSpeed Insights', 'tenweb-speed-optimizer'); ?>
                        </a>
                    </p>
                    <p class="iowd-main-medium-description iowd-text-bold">
                        <?php esc_html_e('Analyzed page:', 'tenweb-speed-optimizer'); ?>
                    </p>
                    <p class="iowd-main-small-description iowd-last-analyzed-page">
                        <?php echo esc_url($this->speedScore['url']); ?>
                    </p>
                    <p class="iowd-main-medium-description iowd-text-bold">
                        <?php esc_html_e('Last analyzed:', 'tenweb-speed-optimizer'); ?>
                    </p>
                    <p class="iowd-main-small-description iowd-last-analyzed-date">
                        <?php esc_html_e($this->speedScore['last_analyzed_time']); ?>
                    </p>
                </div>
            </div>
        </div>
        <?php
    }

    private function mainPageFooterPart()
    {
        $wp_plugin_url = 'https://wordpress.org/support/plugin/tenweb-image-optimizer/';
        $contact_text = __('Please create a topic in', 'tenweb-image-optimizer');
        $contact_link_text = __('WordPress.org', 'tenweb-image-optimizer');
        $contact_link = $wp_plugin_url;
        new \TenWebPluginIO\DeactivatePopups('iowd_main_page');
        ?>
        <div class="iowd-flex-space-between">
            <div class="iowd-disconnect-link">
                <img src="<?php echo TENWEBIO_URL . '/assets/img/check_solid.svg'; ?>" alt="Connected"
                     class="iowd-connected-img"/>
                <b><?php _e('Site is connected', 'tenweb-speed-optimizer'); ?></b>
                <a href="#"
                   class="iowd_disconnect_from_page"><?php _e('Disconnect from 10Web', 'tenweb-image-optimizer'); ?></a>
            </div>
            <div class="iowd-wp-link">
                <b><?php _e('Have a question?', 'tenweb-speed-optimizer'); ?></b>
                <span><?php echo esc_html($contact_text); ?> <a href="<?php echo esc_url($contact_link); ?>"
                                                                target="_blank"><?php echo esc_html($contact_link_text); ?></a></span>
            </div>
        </div>
        <?php
    }

    private function emptyMediaLibraryView()
    {
        ob_start();
        $desc1 = __('Manage all optimization settings from your 10Web dashboard for easier control over<br> optimization modes and images.', 'tenweb-image-optimization'); ?>
        <div class="iowd-main-container iowd-flex-center">
        <?php echo $this->freePlanBanner(); ?>
        <div class="iowd-white-with-borders iowd-empty-lib">
            <div>
                <p class="iowd-status-label iowd-status-green-mark iowd-text-bold iowd-main-small-description">
                    <?php esc_html_e('Site is connected', 'tenweb-image-optimizer'); ?>
                </p>
                <p class="iowd-main-title iowd-text-bold iowd-active-title">
                    <?php esc_html_e('Image Optimizer is active', 'tenweb-image-optimizer'); ?>
                </p>
                <p class="iowd-main-medium-description">
                    <?php echo wp_kses_post($desc1); ?>
                </p>
            </div>
            <div class="iowd-images-count-section iowd-flex-space-between">
                <div class="iowd-optimized-images-section iowd-grey-bg-section">
                    <div class="iowd-images-count-null">
                        <span class="iowd-null-number iowd-text-bold">0</span>
                        <span class="iowd-null-number-text">
                                <?php esc_html_e('/image', 'tenweb-image-optimizer'); ?>
                            </span>
                    </div>
                    <p class="iowd-main-small-description">
                        <?php
                        $opt_images_desc = __('0 image in media library (0 full-sized)', 'tenweb-image-optimizer');
                        esc_html_e($opt_images_desc); ?>
                    </p>
                    <p class="iowd-main-large-description iowd-text-bold">
                        <?php esc_html_e('No Images to optimize', 'tenweb-image-optimizer'); ?>
                    </p>
                </div>
                <div class="iowd-empty-lib-data-section iowd-grey-bg-section">
                    <p class="iowd-main-medium-description iowd-text-bold">
                        <?php esc_html_e('0 Mb (0%)', 'tenweb-image-optimizer'); ?>
                    </p>
                    <p class="iowd-main-small-description">
                        <?php esc_html_e('Last optimization', 'tenweb-image-optimizer'); ?>
                    </p>
                    <p class="iowd-main-medium-description iowd-text-bold">
                        <?php esc_html_e('0 Mb (0%)', 'tenweb-image-optimizer'); ?>
                    </p>
                    <p class="iowd-main-small-description">
                        <?php esc_html_e('Total reduction', 'tenweb-image-optimizer'); ?>
                    </p>
                    <div>
                        <a class="iowd-button iowd-button-green-fillcolor iowd-disable-button">
                            <?php esc_html_e('Optimize now', 'tenweb-image-optimizer'); ?>
                        </a>
                    </div>
                </div>
            </div>
            <?php if ($this->boosterPluginStatus != 2) {
                echo $this->BoosterViewPart();
            } ?>
        </div>
        <?php echo $this->mainPageFooterPart(); ?>
        </div><?php
        return ob_get_clean();
    }

    private function allSetBoosterActiveView()
    {
        $desc1 = __('Manage all optimization settings from your 10Web dashboard for easier control over<br> optimization modes and images.', 'tenweb-image-optimization');
        ob_start(); ?>
        <div class="iowd-main-container iowd-flex-center">
            <?php echo $this->freePlanBanner(); ?>
            <div class="iowd-white-with-borders iowd-all-set-lib">
                <div>
                    <p class="iowd-status-label iowd-status-green-mark iowd-text-bold iowd-main-small-description">
                        <?php esc_html_e('Site is connected', 'tenweb-image-optimizer'); ?>
                    </p>
                    <p class="iowd-main-title iowd-text-bold iowd-active-title">
                        <?php esc_html_e('Image Optimizer is active', 'tenweb-image-optimizer'); ?>
                    </p>
                    <p class="iowd-main-medium-description">
                        <?php echo wp_kses_post($desc1); ?>
                    </p>
                </div>
                <div class="iowd-images-count-section iowd-flex-space-between">
                    <div class="iowd-optimized-images-section iowd-grey-bg-section">
                        <img alt="Check Mark" src="<?php echo TENWEBIO_URL_IMAGES . '/check_solid.svg'; ?>">
                        <p class="iowd-main-large-description iowd-text-bold">
                            <?php esc_html_e('You are all set', 'tenweb-image-optimizer'); ?>
                        </p>
                        <p class="iowd-main-small-description">
                            <?php
                            $opt_images_desc = (int)$this->imagesCount . sprintf(__(' images in media library (%s full-sized) are optimized', 'tenweb-image-optimizer'), $this->totalOptimizedFull);
                            esc_html_e($opt_images_desc); ?>
                        </p>
                    </div>
                    <div class="iowd-empty-lib-data-section iowd-grey-bg-section">
                        <p class="iowd-main-medium-description iowd-text-bold">
                            <?php echo $this->lastOptimizedSize . esc_html(' (') .
                                $this->lastOptimizedPercent . esc_html('%)'); ?>
                        </p>
                        <p class="iowd-main-small-description">
                            <?php esc_html_e('Last optimization', 'tenweb-image-optimizer'); ?>
                        </p>
                        <p class="iowd-main-medium-description iowd-text-bold">
                            <?php
                            echo $this->totalReduced . esc_html(' (') . $this->totalReducedPercent . esc_html('%)');
                            ?>
                        </p>
                        <p class="iowd-main-small-description">
                            <?php esc_html_e('Total reduction', 'tenweb-image-optimizer'); ?>
                        </p>
                        <div>
                            <a class="iowd-button iowd-button-green-fillcolor iowd-disable-button">
                                <?php esc_html_e('Optimize now', 'tenweb-image-optimizer'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo $this->mainPageFooterPart(); ?>
        </div> <?php
        return ob_get_clean();
    }

    private function freePlanBanner()
    {
        if (\TenWebPluginIO\Utils::isFreeUser()) {
            $plan_limit = (int)$this->dataIO['limitation']['plan_limit'] / 1000;
            $total_optimized = $plan_limit * 1000 - (int)$this->dataIO['limitation']['remained'];
            ?>
            <div class="iowd-white-with-borders iowd-flex-space-between iowd-free-plan-banner">
                <div>
                    <p class="iowd-main-medium-description iowd-text-bold">
                        <?php esc_html_e('Free plan', 'tenweb-image-optimizer'); ?>
                    </p>
                    <p class="iowd-main-medium-description">
                        <?php esc_html_e('Includes optimization of ' . $plan_limit . 'K images per month', 'tenweb-image-optimizer'); ?>
                    </p>
                </div>
                <div class="iowd-text-align-right">
                    <p class="iowd-main-medium-description iowd-text-bold">
                        <?php esc_html_e($total_optimized . ' of ' . $plan_limit . 'K optimized', 'tenweb-image-optimizer'); ?>
                    </p>
                    <p class="iowd-main-medium-description">
                        <?php esc_html_e('Next reset: ' . date("M d, Y", strtotime($this->dataIO['limitation']['reset_date'])), 'tenweb-image-optimizer'); ?>
                    </p>
                </div>
            </div>
            <?php
        }
    }
}