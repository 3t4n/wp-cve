<?php
namespace Hyperpay\Gateways\Brands;

use Hyperpay\Gateways\App\DefaultGateway;
use Hyperpay\Gateways\Main;
use WC_Order;

class CreditCard extends DefaultGateway 
{
    /**
     * should be lower case and unique
     * @var string $id 
     */
    public $id = 'hyperpay';
    
    /**
     * The title which appear next to gateway on setting page 
     * @var string $method_title
     */
    public $method_title = 'Hyperpay Gateway';

    /**
     * Description of gateways which will appear next to title
     * @var string $method_description
     */
    public $method_description = 'Hyperpay Plugin for Woocommerce';

    /**
     * 
     * the Brands supported by the gateway
     * @var array $supported_brands
     */
    protected $supported_brands = [
        'VISA' => 'Visa',
        'MASTER' => 'Master Card',
        'AMEX' => 'American Express',
        'JCB' => 'Japan Credit Bureau'
    ];

    public function __construct()
    {
        parent::__construct();
        $this->blackBins = require_once(Main::ROOT_PATH . '/App/blackBins.php');
    }


}
