<?php

namespace Premmerce\WoocommerceMulticurrency\Admin;

use Premmerce\WoocommerceMulticurrency\Model\Model;

class CurrenciesListTable extends \WP_List_Table
{
    /**
     * @var array
     */
    protected $fileManager;

    /**
     * @var Model
     */
    private $model;

    /**
     * @var string Temp storage for current currency symbol
     */
    private $currentCurrencySymbol;

    /**
     * CurrenciesListTable constructor.
     *
     * @param $model Model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;

        parent::__construct(array(
            'singular' => __('currency', 'premmerce-woocommerce-multicurrency'),
            'plural' => __('currencies', 'premmerce-woocommerce-multicurrency'),
            'ajax' => false
        ));
    }

    /**
     * @param $item
     *
     * @return string
     */
    public function column_currency_name($item)
    {
        $deleteUrl = wp_nonce_url(sprintf(
            '%s?action=%s&currency_id=%d',
            admin_url('admin-post.php'),
            'delete-currency',
            $item['id']
        ), 'premmerce-currency-delete');

        $actions = array(
            'edit' => sprintf(
                '<a href="?page=%s&action=%s&currency_id=%d">' . __('Edit') . '</a>',
                'premmerce_multicurrency',
                'edit-currency',
                $item['id']
            ),
            'delete' => '<a class="premmerce-currency-delete" href="' . $deleteUrl . '">' . __("Delete") . '</a>'
        );

        //Disable delete button for main currency
        if ($item['id'] === get_option(Model::MAIN_CURRENCY_OPTION_NAME)) {
            $actions['delete'] = __('Delete');
        }

        $content = sprintf('%1$s %2$s', $item['currency_name'], $this->row_actions($actions));

        return apply_filters('premmerce_multicurrency_list_table_column_name', $content, $item);
    }

    /**
     * @param object $item
     * @param string $column_name
     *
     * @return mixed
     */
    public function column_default($item, $column_name)
    {
        $priceFormats = array(
            'left' => '%1$s%2$s',
            'right' => '%2$s%1$s',
            'left_space' => '%1$s&nbsp;%2$s',
            'right_space' => '%2$s&nbsp;%1$s'

        );


        $priceFormatArgs = array(
            'currency_code' => $item['code'],
            'decimal_separator' => $item['decimal_separator'],
            'thousand_separator' => $item['thousand_separator'],
            'decimals' => $item['decimals_num'],
            'price_format' => $priceFormats[$item['position']]
        );

        switch ($column_name) {
            case 'id':
            case 'isMain':
            case 'code':
            case 'symbol':
            case 'rate':
            case 'last_update':
                return $item[$column_name];
            case 'example':

                //Trick to display currency symbol chosen by user in example column and don't broke anything
                add_filter('woocommerce_currency_symbol', array($this, 'changeCurrencySymbol'));
                $this->currentCurrencySymbol = $item['symbol'];
                $price = wc_price(1000.00, $priceFormatArgs);
                remove_filter('woocommerce_currency_symbol', array($this, 'changeCurrencySymbol'));

                return '<span class="price-example">' . $price . '</span>';
            case 'display_on_front':
                $messageUsersCant = __(
                    'Users can\'t choose this currency on site.',
                    'premmerce-woocommerce-multicurrency'
                );
                $messageUsersCan = __('Users can choose this currency on site.', 'premmerce-woocommerce-multicurrency');
                $icon = $item['display_on_front'] ? 'visibility' : 'hidden';
                $title = $item['display_on_front'] ? $messageUsersCan : $messageUsersCant;
                return '<span title="' . $title . '" class="dashicons dashicons-' . $icon . '"></span>';
            default:
                return apply_filters(
                    'premmerce_multicurrency_list_table_column_' . $column_name . '_content',
                    $item[$column_name]
                );
        }
    }

    /**
     * @return string
     */
    public function changeCurrencySymbol()
    {
        return $this->currentCurrencySymbol;
    }

    /**
     * @return array
     */
    public function get_columns()
    {
        $columns = array(
            'id' => 'ID',
            'currency_name' => __('Currency', 'premmerce-woocommerce-multicurrency'),
            'isMain' => __('Main', 'premmerce-woocommerce-multicurrency'),
            'code' => __('Code', 'premmerce-woocommerce-multicurrency'),
            'symbol' => __('Symbol', 'premmerce-woocommerce-multicurrency'),
            'example' => __('Example', 'premmerce-woocommerce-multicurrency'),
            'rate' => __('Exchange rate', 'premmerce-woocommerce-multicurrency'),
            'display_on_front' => __('Visibility on site', 'premmerce-woocommerce-multicurrency'),
            'last_update' => __('Last update', 'premmerce-woocommerce-multicurrency')
        );
        return apply_filters('premmerce_multicurrency_list_table_columns', $columns);
    }

    /**
     * @return array
     */
    public function get_sortable_columns()
    {
        return array(
            'id' => array('id', false),
            'rate' => array('rate', false),
            'currency_name' => array('currency_name', false),
            'code' => array('code', false),
            'last_update' => array('last_update', false)
        );
    }

    /**
     * Prepare table items
     */
    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $currencies = $this->model->getCurrencies();


        foreach ($currencies as &$currency) {
            $currency['isMain'] = '<input class="is-main-checkbox" name="is-main" type="radio" value="' . $currency['id'] . '"' . checked(
                get_option(Model::MAIN_CURRENCY_OPTION_NAME),
                $currency['id'],
                false
            ) . ' >';
            $currency['rate'] = rtrim(rtrim(sprintf('%.10F', $currency['rate']), '0'), ".");
        }

        usort($currencies, array(&$this, 'sortItems'));
        $this->items = $currencies;
    }

    /**
     * Add class to currencies table
     *
     * @return array
     */
    public function get_table_classes()
    {
        $classes = parent::get_table_classes();
        $classes[] = 'premmerce-multicurrency-currencies-table';
        return $classes;
    }

    /**
     * @param $a
     * @param $b
     *
     * @return int
     */
    private function sortItems($a, $b)
    {
        $orderby = isset($_GET['orderby']) ? $_GET['orderby'] : 'currency_name';
        $order = isset($_GET['order']) ? $_GET['order'] : 'asc';
        $result = strcmp(strval($a[$orderby]), strval($b[$orderby]));

        return ($order === 'asc') ? $result : -$result;
    }
}
