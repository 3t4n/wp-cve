<?php
if ( ! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


class ApaczkaFSHooks
{

    public static $instance = null;
    
    private $_shipping_methods = null;

    private function __construct()
    {
        add_filter('flexible_shipping_integration_options', array($this, 'integration_options'));
        add_filter('flexible_shipping_method_integration_col', array($this, 'method_integration_col'), 10, 2);
    }


    /**
     * @return self
     */
    public static function get_instance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @return array
     */
    private function _get_shipping_methods()
    {
        if ($this->_shipping_methods === null) {
            $this->_shipping_methods = WC()->shipping->get_shipping_methods();
        }

        return $this->_shipping_methods;
    }

    /**
     * @param array $options
     *
     * @return array
     */
    function integration_options($options)
    {
        $shipping_methods = $this->_get_shipping_methods();
        if ($shipping_methods['apaczka']->enabled == 'yes') {
            $options['apaczka'] = __('Apaczka', 'woocommerce-apaczka');
        }
        return $options;
    }

    /**
     * @param string $col
     * @param string $shipping_method
     *
     * @return string
     */
    public function method_integration_col($col, $shipping_method)
    {
        if (isset($shipping_method['method_integration']) && 'apaczka' === $shipping_method['method_integration']) {
            $services = implode(',', WPDesk_Apaczka_Shipping::$services);
            ob_start();
            ?>
            <td width="1%" class="integration default">
                <span class="tips" data-tip="<?php echo $services; ?>">
                    <?php echo ucfirst($shipping_method['method_integration']); ?>
                </span>
            </td>
            <?php
            $col = ob_get_contents();
            ob_end_clean();
        }

        return $col;
    }
}
