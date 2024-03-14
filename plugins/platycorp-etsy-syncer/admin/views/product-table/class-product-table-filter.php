<?php
namespace platy\etsy\admin;
use platy\etsy\EtsyDataService;
class ProductTableFilter{
    
    /**
     * 
     *
     * @var EtsyDataService
     */
    private $service;

    private $shop_id;
    function __construct($syncer){
        $this->service = EtsyDataService::get_instance();
        $this->shop_id = $this->service->get_current_shop_id();
    }

    function add_etsy_filter( $filters ) {
        $filters['platy-syner-etsy'] = [$this, "render_etsy_filter"];
        $filters['platy-syncer-etsy-shops'] = [$this, "maybe_add_shops_select"];
        return $filters;
    }

    function render_etsy_filter(){
        $value1 = '';
        $value2 = '';
        $value3 = "";
        
        // Check if filter has been applied already so we can adjust the input element accordingly
        
        if( isset( $_GET['platy-syncer-etsy-filter'] ) ) {
        
          switch( $_GET['platy-syncer-etsy-filter'] ) {
        
            // We will add the "selected" attribute to the appropriate <option> if the filter has already been applied
            case 'synced':
              $value1 = ' selected';
              break;
        
            case 'unsynced':
              $value2 = ' selected';
              break;
        
            case 'errors':
                $value3 = ' selected';
    
          }
        
        }

        
        // Add your filter input here. Make sure the input name matches the $_GET value you are checking above.
        echo '<select name="platy-syncer-etsy-filter">';
        echo '<option value>Etsy</option>';
        echo '<option value="synced"' . $value1 . '>Synced</option>';
        echo '<option value="unsynced"' . $value2 . '>Not Synced</option>';
        echo '<option value="errors"' . $value3 . '>Errors</option>';
        echo '</select>';
        

    }

    public function maybe_add_shops_select(){
      $shops = $this->service->get_shops();
      if(count($shops) > 1){
        $filter_id   = 'platy-syncer-etsy-shops-filter';
        $current     = isset($_GET[$filter_id])? $_GET[$filter_id] : '';
        
        echo '<select name="platy-syncer-etsy-shops-filter">';
        $selected = $current == ''? 'selected' : '';
        echo "<option value='' $selected>Etsy Shops</option>";

        $selected = $current == '-1'? 'selected' : '';
        echo "<option value='-1' $selected>All Etsy Shops</option>";

        foreach($shops as $shop){
          $shop_id = $shop['id'];
          $shop_name = $shop['name'];
          $selected = $current == $shop_id ? 'selected' : '';
          echo "<option value=$shop_id $selected>$shop_name</option>";
        }
        echo '</select>';
      }
    }

    function get_shops_filter_sql(){
      $shop_id = $this->shop_id;
      $product_tbl = \Platy_Syncer_Etsy::PRODUCT_TABLE_NAME;
      $shop_filter = "";
      if(!empty($_REQUEST['platy-syncer-etsy-shops-filter'])){
        $shop_id = $_REQUEST['platy-syncer-etsy-shops-filter'];
        if($shop_id == "-1"){
          $shop_filter = " AND $product_tbl.shop_id LIKE '%' ";
        }else{
          $shop_filter = " AND $product_tbl.shop_id=$shop_id ";
        }
      }
      return $shop_filter;
    }

    function add_prorduct_filter_sql($args){
      $product_tbl = \Platy_Syncer_Etsy::PRODUCT_TABLE_NAME;
      if(isset($_REQUEST['platy-syncer-etsy-filter'])){
        $args['join']    = $this->append_product_filtering_join( $args['join']);
        $shop_filter = $this->get_shops_filter_sql();
        switch($_REQUEST['platy-syncer-etsy-filter']){
            case "synced":
              $args['where'] .= " AND $product_tbl.status=1 $shop_filter ";
              break;
            case "unsynced":
              $args['where'] .= " AND (($product_tbl.etsy_id=0 $shop_filter) OR $product_tbl.etsy_id IS NULL) ";
              break;
            case "errors":
              $args['where'] .= " AND $product_tbl.status=0 $shop_filter ";
              break;
            default:
              $args['where'] .= " $shop_filter ";
              break;
            
        }
      }
      return $args;
    }

    function append_product_filtering_join($sql){
      global $wpdb;
      $product_tbl = \Platy_Syncer_Etsy::PRODUCT_TABLE_NAME;

      if ( ! strstr( $sql, "$product_tbl" ) ) {
        $sql .= " LEFT JOIN {$wpdb->prefix}$product_tbl $product_tbl ON $wpdb->posts.ID = $product_tbl.post_id ";
      }
      return $sql;
    }
}
