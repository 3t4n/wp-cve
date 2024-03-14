<?php
namespace platy\etsy\admin;
use platy\etsy\EtsyProduct;
use platy\etsy\EtsySyncerException;
use platy\etsy\EtsySyncer;

class ProductTableColumn{

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

    public function populate_column($column){
        global $post;

        
        if ( $column == 'platy-syncer-etsy' ) {
            $html = "";
            try{
                $etsy_item = $this->syncer->get_etsy_product_data($post->ID);
                $etsy_listing_id = $etsy_item['etsy_id'];
                $etsy_link = "https://www.etsy.com/listing/$etsy_listing_id/";
                
                include PLATY_SYNCER_ETSY_DIR_PATH . "admin/views/platy-syncer-etsy-logo.php";
            }catch(EtsySyncerException $e){

            }
            
        }

    }
}