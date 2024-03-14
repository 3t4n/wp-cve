<?php
namespace platy\etsy\orders;
use platy\etsy\EtsySyncer;
use platy\etsy\admin\ProductTableFilter;

class OrderTableFilter extends ProductTableFilter{

  public function maybe_add_shops_select_legacy(){
    global $pagenow, $typenow;

    if( 'shop_order' === $typenow && 'edit.php' === $pagenow ) {
      parent::maybe_add_shops_select();
    }
  }

  function add_etsy_orders_filter_legacy(){
    global $pagenow, $typenow;

    if( 'shop_order' === $typenow && 'edit.php' === $pagenow ) {
        

        // Initializing
        $filter_id   = 'platy-syncer-etsy-filter';
        $current     = isset($_GET[$filter_id])? $_GET[$filter_id] : '';

        echo '<select name="'.$filter_id.'">';
        echo '<option value=""' . ($current == "" ? ' selected ' : "") .'>Etsy</option>';
        echo '<option value="synced"'.  ($current == "synced" ? ' selected ' : "") .'>'.__( 'Synced', 'woocommerce' )."</option>";
        echo '<option value="unsynced"'.  ($current == "unsynced" ? ' selected ' : "") .'>'.__( 'Not Synced', 'woocommerce' )."</option>";
        echo '<option value="errors"'.  ($current == "errors" ? ' selected ' : "") .'>'.__( 'Errors', 'woocommerce' )."</option>";

        
        echo '</select>';
    }
  }

  function add_etsy_orders_filter() {
    $value1 = '';
    $value2 = '';
    $value3 = '';
    
    // Check if filter has been applied already so we can adjust the input element accordingly
    
    if( isset( $_GET['platy-syncer-etsy-filter'] ) ) {
    
      switch( $_GET['platy-syncer-etsy-filter'] ) {
    
        // We will add the "selected" attribute to the appropriate <option> if the filter has already been applied
        case 'etsy-only':
          $value1 = ' selected';
          break;
    
        case 'not-etsy':
          $value2 = ' selected';
          break;
        
        default:
          $value3 = ' selected';
          break;
      }
    
    }

    
    // Add your filter input here. Make sure the input name matches the $_GET value you are checking above.
    echo '<select name="platy-syncer-etsy-filter">';
    echo '<option value="all-orders"' . $value3 . '>All Orders</option>';
    echo '<option value="etsy-only"' . $value1 . '>Etsy Orders</option>';
    echo '<option value="not-etsy"' . $value2 . '>Non Etsy Orders</option>';
    echo '</select>';
  }


  function add_order_query_args($query) {
    $meta_query = isset($query['meta_query']) ? $query['meta_query'] : [];
    if(@$_REQUEST['platy-syncer-etsy-filter'] == 'etsy-only') {
      $meta_query[] = [
          'key'     => EtsyOrdersSyncer::IS_ETSY_ORDER,
          'value'   => "true",
          'compare' => '='
      ];
    } else if(@$_REQUEST['platy-syncer-etsy-filter'] == 'not-etsy') {
      $meta_query[] = [
          'key'     => EtsyOrdersSyncer::IS_ETSY_ORDER,
          'value'   => "true",
          'compare' => '!='
      ];
    }

    $request_shop_id = @$_REQUEST['platy-syncer-etsy-shops-filter'];
    if($request_shop_id != '' && $request_shop_id != -1) {
      $meta_query[] = [
          'key'     => 'etsy_shop_id',
          'value'   => $request_shop_id,
          'compare' => '='
      ];
    }
    $query['meta_query'] = $meta_query;
    return $query;
  }
   
}
