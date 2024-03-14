<?php

if (!isset($argv[1])) {

  echo "\033[31m Please Enter the Gateways name .... \033[0m\n";
  exit();
}

$first_upper = ucfirst($argv[1]);
$filecontent = file_get_contents("includes\\class-install.php");

if (file_exists("gateways\\WC_Hyperpay_$first_upper" . "_Gateway.php") || strpos($filecontent, "WC_$first_upper" . "_Gateway")) {

  echo "\033[31m The GateWay already exists \033[0m\n";
  exit();
}


$myfile = fopen("gateways\\WC_Hyperpay_$first_upper" . "_Gateway.php", "w") or die("Unable to open file!");

$txt = '<?php

require_once HYPERPAY_ABSPATH . "includes/Hyperpay_main_class.php";
class WC_Hyperpay_' . $first_upper . '_Gateway extends Hyperpay_main_class
{
  /**
   * should be lower case and uniqe
   * @var string $id 
   */
  public $id = "hyperpay_' . strtolower($argv[1]) . '";
  
  /**
   * The title which appear next to gateway on setting page 
   * @var string $method_title
   */
  public $method_title = "Hyperpay ' . $first_upper . ' Gateway";

  /**
   * Describtion of gateways which will appear next to title
   * @var string $method_description
   */
  public $method_description = "' . $first_upper .' Plugin for Woocommerce";


  /**
   * you can overwrite styles options by 
   * uncomment array below
   * 
   * @var array $hyperpay_payment_style
   */

    //    protected  $hyperpay_payment_style = [
    //     "card" => "Card",
    //     "plain" => "Plain"
    // ];


  /**
   * 
   * the Brands supported by the gateway
   * @var array $supported_brands
   */
  protected $supported_brands = [
		"' . strtoupper($first_upper) . '" => "' . $first_upper . '",
    ];


  public function __construct()
  {
    parent::__construct();
  }


  /**
   * to set extra parameter on requested data to connector 
   * just uncomment the function below
   * 
   * @param object $order
   * @return array 
   */

  // public function setExtraData(WC_Order $order) : array
  // {
  //     return [
  //         "headers" => [
  //             "header extra option" => "value"
  //         ],
  //         "body" => [
  //             "extra param name " => "value"
  //         ]
  //     ];
  // }
}
';

fwrite($myfile, $txt);
fclose($myfile);


$new = "// Add Class Here
		'WC_Hyperpay_" . $first_upper . "_Gateway',";
$file = str_replace('// Add Class Here', $new, $filecontent);
file_put_contents("includes\\class-install.php", $file);


echo "\033[32m WC_Hyperpay_" . $first_upper . "_Gateway created \u{221a} \033[0m\n";
echo "\033[32m Done .... \u{221a} \033[0m\n";
