<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

class revi_products
{
    var $REVI_API_URL;
    var $prefix;
    var $wpdb;
    var $revimodel;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->prefix = $wpdb->prefix;
        $this->REVI_API_URL = REVI_API_URL;
        $this->revimodel = new revimodel();

        if (isset($_REQUEST['reset_data']) && $_REQUEST['reset_data'] == 1) {
            $this->resetDataProducts();
        }

        $limit = 20;
        if (isset($_REQUEST['limit']) && !empty($_REQUEST['limit'])) {
            $limit = $_REQUEST['limit'];
        }
        $cicles = 50;
        if (isset($_REQUEST['cicles']) && !empty($_REQUEST['cicles'])) {
            $cicles = $_REQUEST['cicles'];
        }

        $sync_result = $this->sendAllProducts($limit, $cicles);

        echo $sync_result;
    }

    private function sendAllProducts($limit, $cicles)
    {
        $num_products_left = $this->revimodel->getNumProductsLeft();

        if (!$num_products_left) {
            $data = array('num_products_left' => 0);
            $this->revimodel->reviCURL($this->REVI_API_URL . 'wsapi/products_left', "POST", $data, true);
            return 'No products LEFT to Sync';
        } else {
            $data = array('num_products_left' => $num_products_left);
            $this->revimodel->reviCURL($this->REVI_API_URL . 'wsapi/products_left', "POST", $data, true);
        }


        $products = array();
        $count_cicles = 1;
        do {
            $products = $this->revimodel->getProductsToSend();

            if (!empty($products)) {
                $data = array(
                    'products' => json_encode($products),
                );

                $result = $this->revimodel->reviCURL($this->REVI_API_URL . 'wsapi/products', "POST", $data, true);
                $result = json_decode($result);

                if (isset($result) && $result->success) {
                    foreach ($products as $product) {

                        // Pasamos el id_product_parent que es el del producto que estamos cogiendo de la BD
                        $data = array(
                            "id_product" => $product['id_product_parent'],
                            "num_ratings" => 0,
                            "avg_rating" => 5,
                        );


                        $this->revimodel->addReviProduct($product['id_product_parent'], $data, date("Y-m-d H:i:s"));
                    }

                    echo count($products) . ' products sync succesfully<br>';
                } else {
                    return 'error CURL result';
                }
            } else {
                return 'No products to Sync';
            }
            sleep(rand(0, 3));
            $count_cicles++;
        } while (!empty($products) && $count_cicles <= $cicles);
        echo "se han necesitado $count_cicles ciclos con un límite de $limit";
    }

    private function resetDataProducts()
    {
        global $wpdb;

        //DELETE TABLE
        $structure0 = "DELETE FROM `revi_products`";
        $wpdb->query($structure0);
        echo "<br>Product Revi Data Tables Deleted<br>";
    }
}
