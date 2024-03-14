<?php
/**
 * @author Tomáš Blatný
 */

namespace Pays\PaymentGate;

use DateTime;

class Plugin
{   const URL_TYPE_OWN_PAGE = 2,
        URL_TYPE_WOOCOMMERCE = 1;

    const OPTION_NAME = 'pays-payment-gate';
    const DOMAIN = 'axima-payment-gateway';
    const TABLE_PAYMENTS = 'payments';

    const STATUS_INITIATED = 0;
    const STATUS_PAID = 1;
    const STATUS_ERROR = 2;

    /** @var Database */
    private $database;


    public function install()
    {
        global $wp_version;

        $checks = array(
            'Your Wordpress version is not compatible with Pays plugin which requires at least version 3.1.)' => version_compare($wp_version, '3.1', '<'),
            'This plugin requires at least PHP version 5.5.0, your version: ' . PHP_VERSION . '. Please ask your hosting company to bring your PHP version up to date.' => version_compare(PHP_VERSION, '5.5.0', '<'),
            'You need WooCommerce plugin installed and activated to run this plugin.' => !in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')), TRUE),
        );

        foreach ($checks as $message => $disable) {
            if ($disable) {
                deactivate_plugins(basename(__FILE__));
                wp_die($message);
            }
        }

        $this->database->createTable(self::TABLE_PAYMENTS, array(
            'id' => 'int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'identifier' => 'varchar(255) NOT NULL',
            'payment_order_id' => 'varchar(255) NULL',
            'status' => 'tinyint(1) unsigned NOT NULL',
            'customer' => 'text NOT NULL',
            'order' => 'varchar(255) NOT NULL',
            'amount' => 'varchar(255) NOT NULL',
            'note' => 'text NULL',
            'date_initiated' => 'datetime NOT NULL',
            'date_paid' => 'datetime NULL',
        ));

        $urlType = $this->getOption('url-type');

        // set notification page option if it is not already set. Default value is Woocommerce way now.
        if ($urlType == null) {
            $this->updateOptions(['url-type' => self::URL_TYPE_WOOCOMMERCE]);
        }
    }


    public static function uninstall()
    {
        global $wpdb;
        $database = new Database($wpdb);
        $database->dropTable(self::TABLE_PAYMENTS);
        delete_option(self::OPTION_NAME);
    }


    public function enqueueAdminScripts()
    {
        $that = $this;
        if (isset($_GET['page']) && $_GET['page'] === $that::DOMAIN) {
            $pluginUrl = plugins_url('', __DIR__);
            wp_enqueue_style('axima_pays_style_bootstrap', $pluginUrl . '/assets/bootstrap.min.css');
            wp_enqueue_style('axima_pays_style_bootstrap_theme', $pluginUrl . '/assets/bootstrap-theme.min.css');
            wp_enqueue_script('axima_pays_script_bootstrap', $pluginUrl . '/assets/bootstrap.min.js');
        }
    }

    public function init()
    {
        $that = $this;

        add_action( 'admin_enqueue_scripts', function() use ($that) { $that->enqueueAdminScripts(); }) ;

        load_plugin_textdomain('axima-payment-gateway', FALSE, dirname(plugin_basename(__FILE__)) . '/lang/');

        // add_shortcode('name', function () {} );

        add_action('admin_menu', function () use ($that) {
            add_menu_page(
                __('Pays.cz', $that::DOMAIN),
                __('Pays.cz', $that::DOMAIN),
                'manage_options',
                $that::DOMAIN,
                array($that, 'actionSettings')
            );
        });

        add_filter('plugin_action_links_' . $that::DOMAIN . '/' . $that::DOMAIN . '.php', function ($links) use ($that) {
            $settings_link = '<a href="admin.php?page=' . $that::DOMAIN . '">' . __('Settings', $that::DOMAIN) . '</a>';
            array_unshift($links, $settings_link);
            return $links;
        });
    }


    public function redirect($action = NULL, $other = NULL)
    {
        wp_redirect($this->link($action, $other));
        exit;
    }


    public function link($action = NULL, $other = NULL)
    {
        return '?page=' . self::DOMAIN . ($action ? ('&payspage=' . urlencode($action)) : '') . $other;
    }


    public function actionSettings()
    {
        $page = isset($_GET['payspage']) ? $_GET['payspage'] : 'default';
        if (!preg_match('~^[a-zA-Z]+$~', $page)) {
            $page = 'default';
        }
        $method = 'render' . ucfirst($page);
        $data = array();
        if (method_exists($this, $method)) {
            $data = (array) $this->$method();
        }
        $this->render(__DIR__ . '/templates/' . $page . '.php', array(
                'page' => $page,
                'domain' => self::DOMAIN,
            ) + $data);
    }


    private function renderDefault()
    {
        $lastWeek = new DateTime('-1 week');
        $lastWeek = $lastWeek->format('Y-m-d G:i:s');
        return array(
            'totalInitiated' => $this->database->selectOne('COUNT(*)', self::TABLE_PAYMENTS, 'WHERE `status` IN (%d, %d)', array(self::STATUS_INITIATED, self::STATUS_PAID)),
            'totalPaid' => $this->database->selectOne('COUNT(*)', self::TABLE_PAYMENTS, 'WHERE `status` = %d', array(self::STATUS_PAID)),
            'lastWeekInitiated' => $this->database->selectOne('COUNT(*)', self::TABLE_PAYMENTS, 'WHERE `status` IN (%d, %d) AND `date_initiated` > %s', array(self::STATUS_INITIATED, self::STATUS_PAID, $lastWeek)),
            'lastWeekPaid' => $this->database->selectOne('COUNT(*)', self::TABLE_PAYMENTS, 'WHERE `status` = %d AND `date_initiated` > %s', array(self::STATUS_PAID, $lastWeek)),
            'totalErrors' => $this->database->selectOne('COUNT(*)', self::TABLE_PAYMENTS, 'WHERE `status` = %d', array(self::STATUS_ERROR)),
            'lastWeekErrors' => $this->database->selectOne('COUNT(*)', self::TABLE_PAYMENTS, 'WHERE `status` = %d AND `date_initiated` > %s', array(self::STATUS_ERROR, $lastWeek)),
        );
    }


    private function renderLogs()
    {
        $page = (int) $this->getQuery('list', 1);
        $count = (int) $this->database->selectOne('COUNT(*)', self::TABLE_PAYMENTS);
        $perPage = 30;
        $pageCount = (int) ceil($count / $perPage);
        if ($page < 1) {
            $page = 1;
        }
        if ($page > $pageCount) {
            $page = $pageCount;
        }
        $pages = array(1, $pageCount, $page, $page - 1, $page - 2, $page + 1, $page + 2);
        if ($pageCount > 5) {
            for ($i = 1; $i <= $pageCount; $i += ($pageCount / 5)) {
                $pages[] = round($i);
            }
        }
        $pages = array_unique(array_filter($pages, function ($item) use ($pageCount) {
            return ($item >= 1) && ($item <= $pageCount);
        }));
        sort($pages);
        $results = array();
        if ($count) {
            $results = $this->database->select(self::TABLE_PAYMENTS, 'ORDER BY `date_initiated` DESC LIMIT ' . $perPage . ' OFFSET ' . ($page - 1) * $perPage);
        }

        return array(
            'payments' => $results,
            'pages' => $pages,
            'list' => $page,
            'maxPage' => $pageCount,
        );
    }


    public function renderSettings()
    {
        // check if notification page option exists, if not, create for backward compatibility
        $urlType = $this->getOption('url-type');

        if ($urlType == null) {
            $this->updateOptions(['url-type' => self::URL_TYPE_OWN_PAGE]);
        }

        if (isset($_POST['_submit'])) {
            $password = trim($this->getPost('hash-password'));
            $update = array(
                'merchantId' => trim($this->getPost('merchant-id')),
                'shopId' => trim($this->getPost('shop-id')),
                'success-url' => trim($this->getPost('success-url')),
                'error-url' => trim($this->getPost('error-url')),
                'url-type' => trim($this->getPost('url-type')),
            );
            if ($password) {
                $update['hashPassword'] = $password;
            }
            $this->updateOptions($update);
        }

        $pages = array();

        $pages[''] = ' - Choose - ';

        foreach ($this->database->select($this->database->getPrefix() . 'posts', 'WHERE `post_type` IN (\'post\', \'page\') AND `post_status` = \'publish\'') as $post) {
            $pages[get_permalink($post)] = $post->post_title;
        }

        return array(
            'settings' => $this->getOptions(),
            'pages' => $pages,
        );
    }


    public function register($file)
    {
        global $wpdb;
        $this->database = new Database($wpdb);

        $that = $this;
        register_activation_hook($file, function () use ($that) {
            $that->install();
        });
        register_deactivation_hook($file, function () use ($that) {
            // $that->deactivate();
        });
        register_uninstall_hook($file, array(__CLASS__, 'uninstall'));

        $this->pluginUrl = plugins_url('', $file);
        if (is_admin()) {
            add_action('plugins_loaded', function () use ($that) {
                $that->init();
            });
        }

        add_action('plugins_loaded', function () use ($that) {
            $handlerConfirm = array($that, 'confirmPayment');
            $handlerSuccess = array($that, 'successPayment');
            $handlerError = array($that, 'errorPayment');

            add_action('wp_ajax_nopriv_pays-confirmation', $handlerConfirm);
            add_action('wp_ajax_pays-confirmation', $handlerConfirm);

            add_action('wp_ajax_nopriv_pays-success', $handlerSuccess);
            add_action('wp_ajax_pays-success', $handlerSuccess);

            add_action('wp_ajax_nopriv_pays-error', $handlerError);
            add_action('wp_ajax_pays-error', $handlerError);

            call_user_func(function () {
                require_once __DIR__ . '/Gateway.php';
            });

            add_filter('woocommerce_payment_gateways', function ($gateways) use ($that) {
                $gateways[] = new Gateway($that->database);
                return $gateways;
            });
        });
    }

    private function getOrderFromParams()
    {
        $get = function ($name, $default = NULL) {
            return isset($_GET[$name]) ? $_GET[$name] : $default;
        };

        $hashString = $get('PaymentOrderID') . $get('MerchantOrderNumber') . $get('PaymentOrderStatusID') . $get('CurrencyID') . $get('Amount') . $get('CurrencyBaseUnits');
        if (hash_hmac('md5', $hashString, self::getHashPassword()) !== $get('Hash', $get('hash'))) {
            wp_die('ERR: Invalid hash', '', 400);
        }

        $item = $this->database->select(self::TABLE_PAYMENTS, 'WHERE `identifier` = %s ORDER BY id DESC', array($get('MerchantOrderNumber')));
        $item = reset($item);
        if (!$item) {
            wp_die('ERR: MerchantOrderNumber not in database', '', 400);
        }
        return wc_get_order($item->order);
    }

    public function confirmPayment()
    {
        $get = function ($name, $default = NULL) {
            return isset($_GET[$name]) ? $_GET[$name] : $default;
        };

        $order = $this->getOrderFromParams();

        $status = $get('PaymentOrderStatusID', 2);
        $note = $get('PaymentOrderStatusDescription', '');

        // check if currency and amount match
        if ($order->get_total() !=  $get('Amount') / $get('CurrencyBaseUnits') ||  $order->get_currency() != $get('CurrencyID')) {
            $status = self::STATUS_ERROR;
            $note = "zaplacena jiná částka";
            $order->update_status('failed', $note);
        } elseif ($status == 3) { // intentionally ==
            $status = self::STATUS_PAID;
            $order->payment_complete();
        } else {
            $status = self::STATUS_ERROR;
            $order->update_status('failed', $note);
        }

        $item = $this->database->select(self::TABLE_PAYMENTS, 'WHERE `identifier` = %s ORDER BY id DESC', array($get('MerchantOrderNumber')));
        $item = reset($item);

        $this->database->update(self::TABLE_PAYMENTS, array(
            'payment_order_id' => $get('PaymentOrderID'),
            'status' => $status,
            'note' => $note,
            'date_paid' => date('Y-m-d G:i:s'),
        ), '`id` = %s', array($item->id));

        wp_die('OK', '', 202);
    }

    public function successPayment()
    {
        $order = $this->getOrderFromParams();
        $order->payment_complete();
        wp_redirect($order->get_checkout_order_received_url());
        exit;
    }

    public function errorPayment()
    {
        $get = function ($name, $default = NULL) {
            return isset($_GET[$name]) ? $_GET[$name] : $default;
        };

        $order = $this->getOrderFromParams();
        $note = $get('PaymentOrderStatusDescription', '');
        $order->update_status('failed', $note);

        wc_add_notice("Platba se nezdařila: $note. Zopakujte prosím platbu.", 'error');
        wp_redirect($order->get_checkout_payment_url(false));
        exit;
    }

    private function render($template, $vars = array())
    {
        call_user_func_array(function () use ($template, $vars) {
            extract($vars);
            include $template;
        }, array());
    }


    private function updateOptions(array $options)
    {
        $current = $this->getOptions();
        foreach ($options as $key => $option) {
            $current[$key] = $option;
        }
        update_option(self::OPTION_NAME, $current);
    }


    /**
     * @return array
     */
    private function getOptions()
    {
        return get_option(self::OPTION_NAME);
    }


    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    private function getOption($name, $default = NULL)
    {
        $options = $this->getOptions();
        return isset($options[$name]) ? $options[$name] : $default;
    }


    private function getPost($name, $default = NULL)
    {
        return isset($_POST[$name]) && $_POST[$name] ? $_POST[$name] : $default;
    }


    private function getQuery($name, $default = NULL)
    {
        return isset($_GET[$name]) && $_GET[$name] ? $_GET[$name] : $default;
    }


    public static function getMerchantId()
    {
        $options = get_option(self::OPTION_NAME);
        return isset($options['merchantId']) ? $options['merchantId'] : '';
    }


    public static function getShopId()
    {
        $options = get_option(self::OPTION_NAME);
        return isset($options['shopId']) ? $options['shopId'] : '';
    }


    public static function getHashPassword()
    {
        $options = get_option(self::OPTION_NAME);
        return isset($options['hashPassword']) ? $options['hashPassword'] : '';
    }
}
