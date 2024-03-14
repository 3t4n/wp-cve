<?php
if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

class revi_sync
{
    var $REVI_API_URL;
    public $prefix;
    public $wpdb;
    public $revimodel;
    public $subscription;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->prefix = $wpdb->prefix;
        $this->REVI_API_URL = REVI_API_URL;
        $this->revimodel = new revimodel();

        $this->revimodel->updateConfiguration();

        $this->subscription = get_option('REVI_SUBSCRIPTION');

        if (isset($_REQUEST['checkModuleActive'])) {
            $this->checkModuleActive();
        }

        if (isset($_REQUEST['reset_data']) && $_REQUEST['reset_data'] == 1) {
            $this->resetDataAll();
        }

        $sync_result =  '';
        $sync_result .= $this->syncProducts();

        if ($this->subscription >= 2) {
            $sync_result .= $this->syncComments();
        }

        $dir_path = plugin_dir_path(__FILE__) . '../revi.php';
        $plugin_data = get_plugin_data($dir_path);
        $this->revimodel->sendModuleVersion($plugin_data['Version']);

        echo $sync_result;
    }

    private function checkModuleActive()
    {
        $result = $this->revimodel->reviCURL($this->REVI_API_URL . 'wsapi/checkModuleActive', 'GET');
        $result = json_decode($result, true);
        echo $result->message;
        die;
    }

    private function syncProducts()
    {
        $response = $this->revimodel->reviCURL($this->REVI_API_URL . 'wsapi/productsratings', 'GET');
        $response = json_decode($response, true);
        $products = $response['data'];

        $num_products = 0;
        if (!empty($products)) {
            foreach ($products as $product) {
                $this->revimodel->addReviProduct($product['id_product'], $product);
                $num_products++;
            }
        }
        return '- Updating ' . $num_products . ' products.<br>';
    }

    private function syncComments()
    {
        $last_comment = $this->revimodel->getLastIDComment();
        $response = $this->revimodel->reviCURL($this->REVI_API_URL . 'wsapi/comments?last_comment=' . $last_comment, 'GET');
        $response = json_decode($response, true);
        $comments = $response['data'];


        $result = '';
        if (count($comments)) {
            foreach ($comments as $comment) {
                $this->revimodel->addReviComment($comment);
                $result .= 'INSERTING COMMENT: ' . $comment['id_comment'] . "</br>";
            }

            return $result . "- Last Comment: $last_comment -|- Updating: " . count($comments) . " comments. </br>\n";
        }
        return 'No comment to SYNC <br>';
    }

    private function resetDataAll()
    {
        global $wpdb;

        //DELETE TABLES
        $structure0 = "DELETE FROM `revi_orders`";
        $structure1 = "DELETE FROM `revi_comments`";
        $structure2 = "DELETE FROM `revi_products`";
        $structure3 = "DELETE FROM `revi_categories`";
        $wpdb->query($structure0);
        $wpdb->query($structure1);
        $wpdb->query($structure2);
        $wpdb->query($structure3);

        echo "<br>All Revi Data Tables Deleted<br>";
    }
}
