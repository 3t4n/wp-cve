<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Loader\Product;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ImportDAO;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Helper\PluginHelper;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Conditional\Conditional;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\Hookable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Config;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ProductDAO;
/**
 * Class ProductColumnLoaderAction, Beacon loader.
 */
class ProductColumnLoaderAction implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\Hookable
{
    const COLUMN_KEY = 'dropshipping';
    /**
     * @var Config
     */
    private $config;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var PluginHelper
     */
    private $plugin_helper;
    /**
     * @var ImportDAO
     */
    private $import_dao;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Config $config, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request $request, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Helper\PluginHelper $helper, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ImportDAO $import_dao)
    {
        $this->config = $config;
        $this->request = $request;
        $this->plugin_helper = $helper;
        $this->import_dao = $import_dao;
    }
    public function hooks()
    {
        \add_filter('manage_product_posts_columns', [$this, 'set_dropshipping_column_name']);
        \add_action('manage_product_posts_custom_column', [$this, 'get_dropshipping_columns'], 10, 2);
    }
    public function set_dropshipping_column_name($columns)
    {
        if (empty($columns) && !\is_array($columns)) {
            $columns = [];
        }
        $columns[self::COLUMN_KEY] = \__('Dropshipping', 'dropshipping-xml-core');
        return $columns;
    }
    public function get_dropshipping_columns($column, $post_id)
    {
        if ($column == self::COLUMN_KEY) {
            $product = \wc_get_product($post_id);
            $uid = $product->get_meta(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO\ProductDAO::PRODUCT_IMPORT_ID_META, \true);
            if (!empty($uid) && $this->import_dao->is_uid_exists($uid)) {
                $import = $this->import_dao->find_by_uid($uid);
                $name = !empty($import->get_import_name()) ? $import->get_import_name() : $import->get_url();
                echo '<span> ' . $name . ' </span>';
            } else {
                echo '<span> - </span>';
            }
        } else {
            echo '<span> - </span>';
        }
    }
}
