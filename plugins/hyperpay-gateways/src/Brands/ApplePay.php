<?php

namespace Hyperpay\Gateways\Brands;

use Hyperpay\Gateways\App\DefaultGateway;
use Hyperpay\Gateways\App\HyperpayExpressBlock;
use Hyperpay\Gateways\App\View;
use Hyperpay\Gateways\Main;

class ApplePay extends DefaultGateway
{
    use HyperpayExpressBlock;

    /**
     * should be lower case and unique
     * @var string $id 
     */
    public $id = 'hyperpay_applepay';


    public $isExpress = true;

    public $action_button = true;
    public $action_style = '';
    public $action_type = '';

    /**
     * The title which appear next to gateway on setting page 
     * @var string $method_title
     */
    public $method_title = 'ApplePay';

    /**
     * Description of gateways which will appear next to title
     * @var string $method_description
     */
    public $method_description = 'ApplePay Plugin for Woocommerce';



    /**
     * 
     * the Brands supported by the gateway
     * @var array $supported_brands
     */
    protected $supported_brands = [
        'APPLEPAY' => 'ApplePay',
    ];


    protected $button_types = [
        "buy" => "buy",
        "donate" => "donate",
        "plain" => "plain",
        "book" => "book",
        "check-out" => "check-out",
        "subscribe" => "subscribe",
        "add-money" => "add-money",
        "order" => "order",
        "reload" => "reload",
        "rent" => "rent",
        "support" => "support",
    ];



    public function __construct()
    {
        parent::__construct();

        /**
         * add supported_network field to position number 6
         */
        $extra_fields['supported_network'] = [
            'title' => __('Supported Network', 'hyperpay-payments'),
            'type' => 'multiselect',
            'options' => [
                "visa" => "Visa",
                "masterCard" => "Master",
                "amex" => "Amex",
                "mada" => "Mada"
            ],
            'default' => ["visa", "masterCard", "amex"]
        ];

        $extra_fields['icon'] = [
            'title' => __('Preview action button', 'hyperpay-payments'),
            'type' => 'icon',
            'tip' => __('This will be the apple pay style on checkout', 'hyperpay-payments'),
        ];


        $extra_fields['action_type'] = [
            'title' => __('Action button', 'hyperpay-payments'),
            'type' => 'select',
            'options' => $this->button_types,
            'default' => "plain",
        ];

        $extra_fields['action_style'] = [
            'title' => __('Action style', 'hyperpay-payments'),
            'type' => 'select',
            'options' => ["black" => "black", "white-with-line" => "white"],
            'default' => "white-with-line"
        ];


        $this->form_fields = array_merge(array_slice($this->form_fields, 0, 7), $extra_fields, array_slice($this->form_fields, 7));
        $this->supported_network = $this->get_option('supported_network');
        $this->action_type = $this->get_option('action_type');
        $this->action_style = $this->get_option('action_style');
        $this->action_button = HYPERPAY_PLUGIN_DIR . "/src/assets/images/applePayButtons/button-{$this->action_type}-{$this->action_style}-min.png";
    }

    public function extraScriptData()
    {
        return [
            "action_type" => $this->action_type,
            "action_style" => $this->action_style,
            'testMode' => $this->testMode
        ];
    }


    public function generate_icon_html($key, $data)
    {
        ob_start();
        View::render('fields/icon.html', compact('data'));
        return ob_get_clean();
    }
}
