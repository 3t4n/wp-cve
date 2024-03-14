<?php
/**
 *
 * Class for mapping SmartBill Products
 *
 * @link       http://www.smartbill.ro
 * @since      1.0.0
 *
 * @copyright  Intelligent IT SRL 2018
 * @package    smartbill-facturare-si-gestiune
 * @subpackage Smartbill_Woocommerce/includes
 */

/**
 * Class responsible for managing smartbill products
 *
 * @since      3.0.2
 * @copyright  Intelligent IT SRL 2018
 * @package    smartbill-facturare-si-gestiune
 * @subpackage Smartbill_Woocommerce/includes
 * @author     Intelligent IT SRL <vreauapi@smartbill.ro>
 */
class SmartBill_Product {
    /**
	 * The Name of the SmartBill product.
	 *
	 * @since    3.0.2
	 * @access   private
	 * @var      string    $name    The Name of the SmartBill product.
	 */
    private $name; 
    
    /**
	 * The sku code of the SmartBill product.
	 *
	 * @since    3.0.2
	 * @access   private
	 * @var      string    $sku    The sku code of the SmartBill product.
	 */
    private $sku;

    /**
	 * The measuring unit of the SmartBill product.
	 *
	 * @since    3.0.2
	 * @access   private
	 * @var      string    $measuring_unit    The measuring unit of the SmartBill product.
	 */
    private $measuring_unit;

    /**
	 * The quantity of the SmartBill product.
	 *
	 * @since    3.0.2
	 * @access   private
	 * @var      int    $quantity    The quantity of the SmartBill product.
	 */
    private $quantity;

    /**
	 * The Woocommerce product that coresponds to the SmartBill product.
	 *
	 * @since    3.0.2
	 * @access   private
	 * @var      wc_Product|null    $woo_product    Woocommerce product.
	 */
    private $woo_product=null;

    /**
	 * Initialize the class and set its properties.
	 *
	 * @since    3.0.2
	 * @param    string    $name              The Name of the SmartBill product.
     * @param    string    $sku               The sku code of the SmartBill product.
     * @param    string    $measuring_unit    The measuring unit of the SmartBill product.
	 * @param    int       $quantity          The quantity of the SmartBill product.
	 */
    public function __construct($n, $c, $mu, $q){
        $this->name           = (string) $n;
        $this->sku            = (string) $c;
        $this->measuring_unit = (string) $mu;
        $this->quantity       = (int) $q;
    }

    /**
	 * Find woocommerce product either by sku or by name.
	 *
     * @return wc_Product|null $woocommerce_product
	 */
    private function find_woo_product(){
        if(!empty( $this->sku )){
            $this->woo_product = $this->get_woocommerce_product_by_code($this->sku);
        }else{
            $this->woo_product = $this->get_woocommerce_product_by_name($this->name);
        }

        return $this->woo_product;
    }

   	/**
	 * Update woocomemrce product stock if possible.
	 *
	 * @return boolean 
	 */
    public function sync_quantity(){
        $product = $this->find_woo_product();
        if( is_null($product) ){
            return false;
        }

        $update = wc_update_product_stock( $product->get_id(), $this->quantity );

        if( false !== $update ){
            return true;
        }else{
            return false;
        }

    }   

    /**
	 * Search for woocommerce product by name.
	 *
	 * @param string $product_name product name.
	 *
	 * @return wc_Product|null $woocommerce_product
	 */
    private function get_woocommerce_product_by_name($productName){
    
      	$query = new WP_Query(
			array(
				's'           => $productName,
				'post_type'   => array( 'product', 'product_variation' ),
				'post_status' => 'publish',
			)
		);
        
        $productName = sanitize_title($productName);
        $p_ID = null;
        if ($query->have_posts()){
            while($query->have_posts()){
                $query->the_post();
                $title = sanitize_title(get_the_title());
                if ($productName == $title){
                    $p_ID = get_the_ID();
                }
            }
        }
        wp_reset_postdata();
        if ($p_ID){
            $woocommerce_product = wc_get_product($p_ID);
            return $woocommerce_product;
        }
        else {
            return null;
        }
    }
    
    /**
	 * Search for woocommerce product by code.
	 *
	 * @param string $product_code product sku.
	 *
	 * @return wc_Product|null $woocommerce_product
	 */
    private function get_woocommerce_product_by_code($productCode){
        $query = new WP_Query(
			array(
				'post_type'   => array( 'product', 'product_variation' ),
				'post_status' => 'publish',
				'meta_query'  => array(
					array(
						'key'     => '_sku',
						'value'   => $productCode,
						'compare' => '=',
					),
				),
			)
		);

		$p_ID = null;
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$p_ID = get_the_ID();
				// we take the first element assuming the sku is unique.
				break;
			}
		}
        wp_reset_postdata();
        if ($p_ID){
            $woocommerce_product = wc_get_product($p_ID);
            return $woocommerce_product;
        }
        else {
            return null;
        }
    
    }

    /**
	 * Get Object properties as array for use in csv export.
	 *
	 * @return Array $product.
	 */
    public function to_arr(){
        $product = $this->woo_product;
        if(is_null($product)){
            $product_title = esc_attr__( 'Produsul nu a fost gasit in nomenclatorul WooCommerce.', 'smartbill-woocommerce' );
            $product_id="";
        }else{
            $product_title = $product->get_title();
            $product_id=$product->get_id();
        }
        return [$this->sku, $this->name, $product_title, $product_id, $this->quantity];
    }
}