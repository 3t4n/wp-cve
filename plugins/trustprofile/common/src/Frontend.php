<?php
namespace Valued\WordPress;

use RuntimeException;
use WP_Error;

class Frontend {
    private $plugin;

    protected $wwk_shop_id;

    private $script_printed = false;

    private $enable_rich_snippet = true;

    protected function is_sidebar_inactive() {
        return !$this->plugin->getOption('javascript');
    }

    public function __construct(BasePlugin $plugin) {
        $this->plugin = $plugin;

        if (basename($_SERVER['SCRIPT_FILENAME']) == 'wp-login.php') {
            return;
        }

        $this->wwk_shop_id = (int) $this->plugin->getOption('wwk_shop_id');
        if (!$this->wwk_shop_id) {
            return;
        }

        add_shortcode("{$this->plugin->getSlug()}_rich_snippet", function () {
            return $this->get_rich_snippet();
        });

        foreach ([
            'wp_head',
            'wp_footer',
            'wp_print_scripts',
        ] as $action) {
            add_action($action, [$this, 'sidebar']);
        }

        if ($this->plugin->getOption('rich_snippet')) {
            add_action('wp_footer', [$this, 'rich_snippet']);
            add_action('woocommerce_before_single_product', [$this, 'disable_rich_snippet']);
        }
    }

    public function sidebar() {
        if ($this->script_printed) {
            return;
        }
        $this->script_printed = true;

        if ($this->is_sidebar_inactive()) {
            echo "<!-- {$this->plugin->getName()}: sidebar not activated -->";
            return;
        }

        echo $this->plugin->render('sidebar', [
            'plugin' => $this->plugin,
            'id' => (int) $this->wwk_shop_id
        ]);
    }

    public function rich_snippet() {
        if (!$this->enable_rich_snippet) {
            return;
        }
        $html = $this->get_rich_snippet();
        if ($html) {
            echo $html;
        }
    }

    public function disable_rich_snippet() {
        $this->enable_rich_snippet = false;
    }

    private function get_rich_snippet() {
        $locale = explode('_', get_locale());
        $params = http_build_query([
            'id'   => $this->wwk_shop_id,
            'lang' => $locale[0],
        ]);
        $url = sprintf(
            'https://%s/webshops/rich_snippet?%s',
            $this->plugin->getDashboardDomain(),
            $params
        );

        $transient = implode(':', [$this->plugin->getSlug(), 'rich_snippet', md5($url)]);

        $data = get_transient($transient);
        if (is_array($data) && !empty($data[0]) && $data[0] > time()) {
            return $data[1];
        }

        try {
            $result = $this->fetchRichSnippet($url);
        } catch (RuntimeException $e) {
            return $this->log_error($e->getMessage());
        }

        set_transient($transient, [time() + 7200, $result], 7200);

        return $result;
    }

    private function fetchRichSnippet($url) {
        $response = wp_remote_get($url);

        if ($response instanceof WP_Error) {
            throw new RuntimeException(
                "Rich snippet fetch from {$url} failed: {$response->get_error_message()}"
            );
        }

        if (empty($response['body'])) {
            throw new RuntimeException("Rich snippet response from {$url} is empty");
        }

        $data = json_decode($response['body'], true);

        if (empty($data['result'])) {
            throw new RuntimeException("Failed to decode rich snippet data from {$url}");
        }

        if ($data['result'] != 'ok') {
            throw new RuntimeException("Did not get a succesful response from {$url}");
        }

        return $data['content'];
    }

    private function log_error($message) {
        return sprintf(
            '<script>console.error(%s)</script>',
            json_encode("{$this->plugin->getName()}: $message")
        );
    }
}
