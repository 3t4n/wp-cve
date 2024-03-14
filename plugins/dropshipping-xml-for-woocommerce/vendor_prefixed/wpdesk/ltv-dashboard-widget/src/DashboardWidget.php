<?php

namespace DropshippingXmlFreeVendor\WPDesk\Dashboard;

final class DashboardWidget
{
    const ID = 'dropshipping-xml-for-woocommerce';
    const WP_DESK_CARE_ID = 296222;
    const AUTOPAY_ID = 349143;
    const MUTEX_HOOK = 'wpdesk/ltvdashboard/initialized';
    const PL_LOCALE = 'pl_PL';
    /**
     *
     * @var string
     */
    private $widget_title = '';
    /**
     *
     * @var bool
     */
    private $show_widget_header = \true;
    /**
     *
     * @var bool
     */
    private $show_widget_footer = \true;
    /**
     *
     * @var int
     */
    private $plugins_limit = 3;
    /**
     *
     * @var int
     */
    private $cache_timeout = 0;
    /**
     *
     * @var int
     */
    private $cache_retry_timeout = 0;
    /**
     *
     * @var string
     */
    private $locale;
    /**
     *
     * @var array
     */
    private $cache_data = [];
    /**
     *
     * @var string
     */
    private $utm_base = 'utm_source=dashboard-metabox&utm_campaign=dashboard-metabox';
    public function __construct()
    {
        $this->widget_title = \__('Grow your business with WP Desk', 'dropshipping-xml-for-woocommerce');
        $this->cache_timeout = 24 * 60 * 60;
        $this->cache_retry_timeout = 6 * 60 * 60;
        $this->locale = \get_user_locale();
    }
    public function set_widget_title(string $title)
    {
        $this->widget_title = $title;
    }
    public function show_widget_header(bool $bool)
    {
        $this->show_widget_header = $bool;
    }
    public function show_widget_footer(bool $bool)
    {
        $this->show_widget_footer = $bool;
    }
    public function set_plugins_limit(int $limit)
    {
        $this->plugins_limit = $limit;
    }
    public function set_cache_timeout(int $timeout)
    {
        $this->cache_timeout = $timeout;
    }
    public function set_locale(string $locale)
    {
        $this->locale = $locale;
    }
    public function set_utm_base(string $utm_base)
    {
        $this->utm_base = $utm_base;
    }
    public function hooks()
    {
        if (\apply_filters(self::MUTEX_HOOK, \false) === \false) {
            \add_filter(self::MUTEX_HOOK, '__return_true');
            \add_action('wp_dashboard_setup', [$this, 'add_widget']);
        }
    }
    public function add_widget()
    {
        \wp_add_dashboard_widget(self::ID, $this->widget_title, [$this, 'widget_output'], null, null, 'normal', 'high');
    }
    private function get_all_plugins_dirs() : array
    {
        $all_plugins = \array_keys(\get_plugins());
        return \array_map('dirname', $all_plugins);
    }
    private function filter_plugins_to_show(array $plugins) : array
    {
        \usort($plugins, static function ($a, $b) {
            return \strnatcmp($a['priority'], $b['priority']);
        });
        $installed_plugins_dir = $this->get_all_plugins_dirs();
        $plugins = \array_filter($plugins, static function ($plugin) use($installed_plugins_dir) {
            return !\in_array($plugin['slug'], $installed_plugins_dir, \true);
        });
        return \array_slice($plugins, 0, $this->plugins_limit);
    }
    private function get_server() : string
    {
        $locale = $this->locale;
        if ($locale === self::PL_LOCALE) {
            return 'www.wpdesk.pl';
        }
        return 'www.wpdesk.net';
    }
    private function has_cached_data() : string
    {
        if ($this->cache_timeout <= 0) {
            return \false;
        }
        $cache_data = $this->get_raw_cached_data();
        return null === $cache_data || \is_array($cache_data);
    }
    private function get_cached_data_key() : string
    {
        return \sprintf('wpdesk_ltv_%1$s_%2$s', self::ID, $this->locale);
    }
    /**
     *
     * @return mixed
     */
    private function get_raw_cached_data()
    {
        return \get_transient($this->get_cached_data_key());
    }
    private function get_cached_data() : array
    {
        if (!empty($this->cache_data)) {
            return $this->cache_data;
        }
        $cache_data = $this->get_raw_cached_data();
        $this->cache_data = \is_array($cache_data) ? $cache_data : [];
        return $this->cache_data;
    }
    /**
     *
     * @param mixed $data
     * @param int $timeout
     *
     * @return void
     */
    private function set_data_to_cache($data, int $timeout)
    {
        $cache_key = $this->get_cached_data_key();
        \set_transient($cache_key, $data, $timeout);
    }
    private function get_widget_data() : array
    {
        if ($this->has_cached_data()) {
            return $this->get_cached_data();
        }
        $response_data = $this->get_widget_data_from_remote();
        if ($response_data !== null) {
            $this->set_data_to_cache($response_data, $this->cache_timeout);
            return $response_data;
        } else {
            $this->set_data_to_cache(null, $this->cache_retry_timeout);
        }
        return [];
    }
    /**
     * @return array|null
     */
    private function get_widget_data_from_remote()
    {
        $response = \wp_remote_get(\sprintf('https://%s?wpdesk_api=1&t=1', $this->get_server()), ['timeout' => 10, 'sslverify' => \false]);
        if (!\is_array($response)) {
            return null;
        }
        $ret = \json_decode($response['body'], \true);
        if (!$ret || !\is_array($ret)) {
            return null;
        }
        return ['header' => $ret['header'] ?? null, 'plugins' => $this->filter_plugins_to_show($ret['plugins'] ?? []), 'footer' => $ret['footer'] ?? null];
    }
    public function widget_output()
    {
        $widget_data = $this->get_widget_data();
        $server = $this->get_server();
        $utm_base = $this->utm_base;
        if (!empty($widget_data)) {
            echo '<div class="wpdesk_ltv_dashboard_widget">';
            if ($this->show_widget_header && $widget_data['header']) {
                echo \wp_kses_post($widget_data['header']);
            }
            echo '<ul class="ltv-rows">';
            foreach ($widget_data['plugins'] as $plugin) {
                $plugin_url = \sprintf('%1$s?%2$s&utm_medium=more-info-button&utm_term=%3$s', $plugin['url'], $utm_base, $plugin['slug']);
                $add_to_cart_url = \sprintf('https://%1$s/?add-to-cart=%2$s&%3$s&utm_medium=buy-now-button&utm_term=%4$s', $server, $plugin['add_to_cart_id'], $utm_base, $plugin['slug']);
                $add_to_cart_button_label = \esc_html__('Buy now', 'dropshipping-xml-for-woocommerce');
                if ($plugin['add_to_cart_id'] === self::WP_DESK_CARE_ID) {
                    $add_to_cart_button_label = \esc_html__('Learn more', 'dropshipping-xml-for-woocommerce');
                    $add_to_cart_url = $plugin_url;
                } else {
                    if ($plugin['add_to_cart_id'] === self::AUTOPAY_ID) {
                        $add_to_cart_button_label = \esc_html__('Download', 'dropshipping-xml-for-woocommerce');
                        $add_to_cart_url = \esc_url("https://wpde.sk/autopay-wpdeskpl");
                    }
                }
                echo '<li class="ltv-row">';
                if ($plugin['image']) {
                    echo '<img src="' . \esc_url($plugin['image']) . '" alt="" />';
                }
                echo '<p><strong>' . \esc_html($plugin['name']) . '</strong></p>';
                echo '<div class="ltv-row-description">' . \wp_kses_post($plugin['description']) . '</div>';
                echo '<div class="ltv-buttons">';
                echo '<a class="button button-primary button-large" href="' . \esc_url($add_to_cart_url) . '" target="_blank">' . $add_to_cart_button_label . '</a>';
                echo '</div>';
                echo '</li>';
            }
            echo '</ul>';
            echo '<div class="ltv-footer">';
            if ($this->show_widget_footer && $widget_data['footer']) {
                echo \wp_kses_post($widget_data['footer']);
            }
            echo '</div>';
            echo '</div>';
            ?>
            <style>
                .wpdesk_ltv_dashboard_widget .ltv-rows {
                    margin-left: -12px;
                    margin-right: -12px;
                }

                .wpdesk_ltv_dashboard_widget .ltv-row {
                    padding: 6px 12px 24px;
                }

                .wpdesk_ltv_dashboard_widget .ltv-row:nth-child(odd) {
                    background-color: #f6f7f7;
                }

                .wpdesk_ltv_dashboard_widget .ltv-row-description p {
                    margin-top: 6px;
                }

                .wpdesk_ltv_dashboard_widget img {
                    display: block;
                    margin: 0 auto 10px;
                    width: 250px;
                    max-width: 100%;
                }

                .wpdesk_ltv_dashboard_widget .ltv-buttons {
                    display: flex;
                    justify-content: space-around;
                }

                .ltv-buttons a.button.button-large {
                    width: 100%;
                    text-align: center;
                }

                .wpdesk_ltv_dashboard_widget .ltv-footer {
                    margin: 0 -12px;
                    padding: 0 12px;
                }

                .wpdesk_ltv_dashboard_widget .ltv-footer p {
                    margin: 0;
                }
            </style>
            <?php 
        }
    }
}
