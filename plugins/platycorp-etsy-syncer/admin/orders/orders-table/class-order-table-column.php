<?php
namespace platy\etsy\orders;
use platy\etsy\EtsyProduct;
use platy\etsy\EtsySyncerException;
use platy\etsy\EtsySyncer;
use platy\etsy\logs\PlatySyncerLogger;
class OrderTableColumn{

    /**
     * 
     *
     * @var EtsySyncer
     */
    private $syncer;

    function __construct($syncer){
        $this->syncer = $syncer;
    }

    public function add_column($columns){

        $columns['platy-syncer-etsy'] = esc_html__( 'Etsy', 'platy-syncer-etsy' );
        return $columns;
    }

    public function populate_column_legacy($column){
        global $post;
        $this->populate_column($column, wc_get_order($post->ID));
    }

    public function populate_column($column, $order){
        $order_id = $order->get_id();
        
        if ( $column == 'platy-syncer-etsy' ) {
            $html = "";
            try{
                $shop_id = $this->syncer->get_shop_id();
                $etsy_item = PlatySyncerLogger::get_instance()->get_etsy_item_data($order_id, $shop_id);
                $etsy_order_id = $etsy_item['etsy_id'];
                $etsy_link = "https://www.etsy.com/your/orders/sold/new?order_id=$etsy_order_id";
                
                include PLATY_SYNCER_ETSY_DIR_PATH . "admin/views/platy-syncer-etsy-logo.php";
            }catch(EtsySyncerException $e){

            }
            
        }

    }
}