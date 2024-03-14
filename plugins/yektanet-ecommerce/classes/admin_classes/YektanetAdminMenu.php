<?php

class YektanetAdminMenu extends YektanetAdminMainClass
{
    public function __construct()
    {
        parent::__construct();
        add_action('wp_ajax_nopriv_yektanet_change_timeframe_ajax', function () {
            $this->yektanet_change_timeframe_ajax();
        });
        add_action('wp_ajax_yektanet_change_timeframe_ajax', function () {
            $this->yektanet_change_timeframe_ajax();
        });

        add_action('admin_menu', function () {

            add_menu_page(
                'یکتانت',
                'یکتانت',
                'activate_plugins',
                'yektanet-menu.php',
                [$this, 'yektanetSettingPage'],
                plugin_dir_url(__DIR__) . '../assets/images/menu-icon.png',
                6
            );

            add_submenu_page(
                'yektanet-menu.php',
                __('تنظیمات', 'yektanet-ecommerce'),
                __('تنظیمات', 'yektanet-ecommerce'),
                'activate_plugins',
                'yektanet-settings.php',
                [$this, 'yektanetSettingPage'],
            );

            if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
                add_submenu_page(
                    'yektanet-menu.php',
                    __('آمار', 'yektanet-ecommerce'),
                    __('آمار', 'yektanet-ecommerce'),
                    'activate_plugins',
                    'yektanet-analyze.php',
                    [$this, 'yektanetAnalyzePage'],
                );
            }

        });
    }

    public function yektanet_change_timeframe_ajax()
    {
        switch ($_POST['timeframe']) {
            case '1D':
                $data = $this->showOneDayTopViewed();
                break;
            case '1W':
                $data = $this->showOneWeekTopViewed();
                break;
            case '1M':
                $data = $this->showOneMonthTopViewed();
                break;
            case '3M':
                $data = $this->showThreeMonthTopViewed();
                break;
            default:
                $data = $this->showAllTopViewed();
                break;
        }
        $data_to_send = array(
            'status' => true,
            'data' => $data
        );
        echo json_encode($data_to_send);
        wp_die();
    }

    private function getTopProductsInOneDay()
    {
        global $wpdb;
        $now_date = time();
        $one_day = $this->calculateOneDayForGetData();
        $limit = count_of_products_to_show_in_charts;
        return $wpdb->get_results("SELECT COUNT(product_id) as count_val, product_id, last_updated_time FROM {$wpdb->prefix}yektanet_products_views WHERE last_updated_time between $one_day and $now_date GROUP by product_id order by count_val DESC limit $limit", 'ARRAY_A');
    }

    private function showOneDayTopViewed()
    {
        $get_products = $this->getTopProductsInOneDay();

        $data = [];
        foreach ($get_products as $product) {
            $link = get_permalink($product['product_id']);
            $title = get_the_title($product['product_id']);
            array_push($data, [
                "<a href='$link' target='_blank'>$title</a>",
                intval($product['count_val'])
            ]);
        }
        return $data;
    }


    private function getTopProductsInOneWeek()
    {
        global $wpdb;
        $now_date = time();
        $one_week = $this->calculateOneWeekForGetData();
        $limit = count_of_products_to_show_in_charts;
        return $wpdb->get_results("SELECT COUNT(product_id) as count_val, product_id, last_updated_time FROM {$wpdb->prefix}yektanet_products_views WHERE last_updated_time between $one_week and $now_date GROUP by product_id order by count_val DESC limit $limit", 'ARRAY_A');
    }

    private function showOneWeekTopViewed()
    {
        $get_products = $this->getTopProductsInOneWeek();

        $data = [];
        foreach ($get_products as $product) {
            $link = get_permalink($product['product_id']);
            $title = get_the_title($product['product_id']);
            array_push($data, [
                "<a href='$link' target='_blank'>$title</a>",
                intval($product['count_val'])
            ]);
        }
        return $data;
    }


    private function getTopProductsInOneMonth()
    {
        global $wpdb;
        $now_date = time();
        $one_month = $this->calculateOneMonthForGetData();
        $limit = count_of_products_to_show_in_charts;
        return $wpdb->get_results("SELECT COUNT(product_id) as count_val, product_id, last_updated_time FROM {$wpdb->prefix}yektanet_products_views WHERE last_updated_time between $one_month and $now_date GROUP by product_id order by count_val DESC limit $limit", 'ARRAY_A');
    }

    private function showOneMonthTopViewed()
    {
        $get_products = $this->getTopProductsInOneMonth();

        $data = [];
        foreach ($get_products as $product) {
            $link = get_permalink($product['product_id']);
            $title = get_the_title($product['product_id']);
            array_push($data, [
                "<a href='$link' target='_blank'>$title</a>",
                intval($product['count_val'])
            ]);
        }
        return $data;
    }


    private function getTopProductsInThreeMonth()
    {
        global $wpdb;
        $now_date = time();
        $three_month = $this->calculateThreeMonthForGetData();
        $limit = count_of_products_to_show_in_charts;
        return $wpdb->get_results("SELECT COUNT(product_id) as count_val, product_id, last_updated_time FROM {$wpdb->prefix}yektanet_products_views WHERE last_updated_time between $three_month and $now_date GROUP by product_id order by count_val DESC limit $limit", 'ARRAY_A');
    }

    private function showThreeMonthTopViewed()
    {
        $get_products = $this->getTopProductsInThreeMonth();

        $data = [];
        foreach ($get_products as $product) {
            $link = get_permalink($product['product_id']);
            $title = get_the_title($product['product_id']);
            array_push($data, [
                "<a href='$link' target='_blank'>$title</a>",
                intval($product['count_val'])
            ]);
        }
        return $data;
    }


    private function showAllTopViewed()
    {
        $limit = count_of_products_to_show_in_charts;
        $top_posts_in_all_time = new WP_Query(
            array(
                'post_type' => 'product',
                'meta_key' => 'yektanet_view_count',
                'orderby' => 'meta_value_num',
                'order' => 'DESC',
                'posts_per_page' => $limit
            )
        );

        $data = [];
        if ($top_posts_in_all_time->have_posts()) {
            while ($top_posts_in_all_time->have_posts()) {
                $top_posts_in_all_time->the_post();
                $link = get_permalink(get_the_ID());
                $title = get_the_title();
                array_push($data, [
                    "<a href='$link' target='_blank'>$title</a>",
                    intval(get_post_meta(get_the_ID(), 'yektanet_view_count', true))
                ]);
            }
            wp_reset_postdata();
        }
        return $data;
    }

    private function createTitleForChart(): array
    {
        return array(
            'all' => __('Most visited products in all times', 'yektanet-ecommerce'),
            '3M' => __('Most visited products of the last three months', 'yektanet-ecommerce'),
            '1M' => __('Most visited products of the last one month', 'yektanet-ecommerce'),
            '1W' => __('Most visited products of the last one week', 'yektanet-ecommerce'),
            '1D' => __('Most visited products of the last one day', 'yektanet-ecommerce'),
        );
    }

    public function yektanetAnalyzePage()
    {
        $titles = $this->createTitleForChart();
        ?>
        <form>
            <input type="hidden" id="yektanet_ajax_url" value="<?= admin_url('admin-ajax.php'); ?>">
        </form>
        <script>
            yektanetChartTimeFrameChange('all', "<?= $titles['all'] ?>");
        </script>
        <div class="yektanet__chart__timeframes__buttons">
            <span id="yektanet_all_timeframe" class="yektanet__change__timeframe__button"
                onclick='yektanetChartTimeFrameChange("all", "<?= $titles['all'] ?>")'>
                <?= __('all times', 'yektanet-ecommerce') ?>
            </span>
            <span id="yektanet_3M_timeframe" class="yektanet__change__timeframe__button"
                onclick='yektanetChartTimeFrameChange("3M", "<?= $titles['3M'] ?>")'>
                <?= __('three months', 'yektanet-ecommerce') ?>
            </span>
            <span id="yektanet_1M_timeframe" class="yektanet__change__timeframe__button"
                onclick='yektanetChartTimeFrameChange("1M", "<?= $titles['1M'] ?>")'>
                <?= __('one month', 'yektanet-ecommerce') ?>
            </span>
            <span id="yektanet_1W_timeframe" class="yektanet__change__timeframe__button"
                onclick='yektanetChartTimeFrameChange("1W", "<?= $titles['1W'] ?>")'>
                <?= __('one week', 'yektanet-ecommerce') ?>
            </span>
            <span id="yektanet_1D_timeframe" class="yektanet__change__timeframe__button"
                onclick='yektanetChartTimeFrameChange("1D", "<?= $titles['1D'] ?>")'>
                <?= __('one day', 'yektanet-ecommerce') ?>
            </span>
        </div>

        <figure class="highcharts-figure">
            <div id="yektanet_top_products_chart"></div>
        </figure>
        <?php
    }

    public function yektanetSettingPage()
    {
        if (isset($_POST['submit'])) {
            $this->processSetting();
        }
        ?>
        <h3>
            <?php echo __('تنظیمات', 'yektanet-ecommerce'); ?>
        </h3>
        <form method="post" action="">
            <input name="_nonce" type="hidden" value="<?php echo wp_create_nonce("save_app_id"); ?>" />
            <div class="yektanet__setting__main__div">
                <div>
                    <label for="yektanet_app_id" class="yektanet__settings__field__title">
                        <?php echo __('Yektanet App ID', 'yektanet-ecommerce'); ?>
                    </label>
                    <input type="text" id="yektanet_app_id" name="yektanet_app_id" class="yektanet__settings__field__input"
                        value="<?php echo get_option('yektanet_app_id'); ?>">
                </div>
            </div>
            <input type="submit" name="submit" value="<?php echo __('ذخیره', 'yektanet-ecommerce'); ?>"
                class="yektanet__setting__submit__btn button button-primary button-large">
        </form>
        <?php
    }

    private function processSetting()
    {
        if (wp_verify_nonce($_POST['_nonce'], 'save_app_id')) {
            $app_id = sanitize_text_field($_POST['yektanet_app_id']);
            if (strlen($app_id) == 8) {
                update_option('yektanet_app_id', $app_id);
                $text = __('App ID با موفقیت ثبت شد.', 'yektanet-ecommerce');
                $this->sendSuccessMessage("$text");
            } else {
                $text = __('طول App ID باید ۸ کاراکتر باشد.', 'yektanet-ecommerce');
                $this->sendErrorMessage("$text");
            }
        }
    }
}