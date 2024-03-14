<?php
/**
 * Class for Inventory management
 *
 *
 * @link              https://finpose.com
 * @since             2.1.0
 * @package           Finpose
 * @author            info@finpose.com
 */
if ( !class_exists( 'fin_inventory' ) ) {
  class fin_inventory extends fin_app {

    public $table = 'fin_inventory';
    public $v = 'getInventory';
    public $p = '';

    public $selyear;
    public $selmonth;
    public $selcat = '';
    public $selpage;

    public $success = false;
    public $message = '';
    public $payload = array();
    public $callback = '';

      /**
     * Constructor
     */
    public function __construct($v = 'getInventory') {
      parent::__construct();

      $this->selyear = $this->curyear;
      $this->selmonth = $this->curmonth;
      $this->view['settings'] = $this->settings;

      // POST verification, before processing
      if($this->post) {
        $validated = $this->validate();
        if($validated) {
          $verified = wp_verify_nonce( $this->post['nonce'], 'finpost' );
          $can = current_user_can( 'view_woocommerce_reports' );
          if($verified && $can) {

            if(isset($this->post['process'])) {
              $p = $this->post['process'];

              unset(
                $this->post['process'],
                $this->post['handler'],
                $this->post['action'],
                $this->post['nonce'],
                $this->post['_wp_http_referer']
              );

              $this->$p();
            }
          }
        }
      }

      if($v != 'ajax') {
        $args = array(
            'status' => 'publish',
        );


        $this->$v();
      }

      if($this->ask->errmsg) { $this->view['errmsg'] = $this->ask->errmsg; }
    }

    /**
   * Validate all inputs before use
   */
    public function validate() {
      $status = true;

      foreach ($this->post as $pk => $pv) {
        if($pk == 'type') {
          if(!in_array($pv, array_keys($this->presets->costTypes))) {
            $status = false;
            $this->message = esc_html__( 'Invalid Type', 'finpose' );
          }
        }
        if($pk == 'paidwith') {
          if(strlen($pv)>32) {
            $status = false;
            $this->message = esc_html__( 'Invalid Paid With Information', 'finpose' );
          }
        }
        if($pk == 'items') {
          if($pv != intval($pv)) {
            $status = false;
            $this->message = esc_html__( 'Invalid items', 'finpose' );
          }
        }
        if($pk == 'page') {
          if($pv != intval($pv)) {
            $status = false;
            $this->message = esc_html__( 'Invalid page', 'finpose' );
          }
        }
        if(in_array($pk, array('amount', 'tr'))) {
          if($pv!='0.00' && !preg_match('/^(?!0\.00)\d{1,3}(,\d{3})*(\.\d\d)?$/', $pv)) {
            $status = false;
            $this->message = esc_html__( 'Invalid money format', 'finpose' );
          }
        }
        if($pk == 'name') {
          if(strlen($pv)>128) {
            $status = false;
            $this->message = esc_html__( 'Name can not be longer than 128 characters', 'finpose' );
          }
        }
        if($pk == 'notes') {
          if(strlen($pv)>512) {
            $status = false;
            $this->message = esc_html__( 'Notes can not be longer than 512 characters', 'finpose' );
          }
        }
        if($pk == 'datepaid') {
          if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $pv)) {
            $status = false;
            $this->message = esc_html__( 'Date format provided is invalid', 'finpose' );
          }
        }
        if($pk == 'year') {
          if(intval($pv)>2030||intval($pv)<2010) {
            $status = false;
            $this->message = esc_html__( 'Year provided is invalid', 'finpose' );
          }
        }
        if($pk == 'month') {
          if(intval($pv)>12||intval($pv)<1) {
            $status = false;
            $this->message = esc_html__( 'Month provided is invalid', 'finpose' );
          }
        }
      }

    return $status;
    }

    /**
   * Before inventory page loaded
   */
    public function pageInventory() {
      $orderby = 'name';
      $order = 'asc';
      $hide_empty = false ;
      $cat_args = array(
          'orderby'    => $orderby,
          'order'      => $order,
          'hide_empty' => $hide_empty,
      );
       
      $product_categories = get_terms( 'product_cat', $cat_args );
      $this->view['cats'] = $product_categories;      
    }

    /**
   * Helper to get inventory module specific data for the given product
   */
    private function getFinmeta($product) {
      $product->wc_quantity = $product->get_stock_quantity();
      $product->name = $product->get_name();
      $product->price = $product->get_price();
      $product->pid = $product->get_id();
      $product->type = ucfirst($product->get_type());

      $product->finmeta = $this->ask->selectRow("SELECT COUNT(iid) AS units, AVG(cost) AS avgcost, SUM(cost) AS totalvalue FROM fin_inventory WHERE siteid='%d' AND pid = '%d' AND is_sold='%d'", array($this->view['siteid'], $product->pid, 0));
      if(!(floatval($product->finmeta->avgcost))) { $product->finmeta->avgcost = 0; }
      if(!(floatval($product->finmeta->totalvalue))) { $product->finmeta->totalvalue = 0; }
      
      $product->finmeta->margin = 0;
      if($product->finmeta->avgcost && $product->price && $product->finmeta->avgcost) {
        $product->finmeta->margin = ((100 * ($product->price - $product->finmeta->avgcost)) / $product->price);
      }

      $sold = $this->ask->selectRows("SELECT * FROM fin_inventory WHERE siteid='%d' AND pid = '%d' AND is_sold='%d' ORDER BY timesold DESC LIMIT 50", array($this->view['siteid'], $product->pid, 1));
      $numsold = count($sold); 
      
      if(!isset($product->finmeta->units)) {
        $product->finmeta->units = 0;
      }

      if($product->wc_quantity > $product->finmeta->units) {
        $product->finmeta->import = true;
      } else {
        $product->finmeta->import = false;
      }
      
      if($numsold<5) {
        $product->finmeta->rodate = esc_html__( 'Waiting more data', 'finpose' );;
      } else {
        if($product->finmeta->units<1) {
          $product->finmeta->rodate = esc_html__( 'Out of stock', 'finpose' );;
        } else {
          $lastSale = current($sold)->timesold;
          $now = time();
          $firstSale = end($sold)->timesold;
          $product->diff = $now - $firstSale;
          $avgunix = ($now - $firstSale)/$numsold;
          $timetogo = $product->finmeta->units * $avgunix;
          $result = time() + $timetogo; //$lastSale
          $product->finmeta->rodate = $this->dateFormat($result);
        }
      }
      
    return $product;
    }
    

    /**
   * Get all store products and prepare inventory data
   */
    private function getProducts() {
      global $wpdb;

      $filters = json_decode(stripslashes($this->post['filters']), true);
      $this->payload['pager'] = json_decode(stripslashes($this->post['pager']), true);
      
      $args = array(
          //'status'    => 'publish',
          //'type' => array('', 'simple', 'grouped', 'variable'),
          'orderby' => 'name',
          'order'   => 'ASC',
          'limit' => $this->payload['pager']['perpage'],
          'page'  => $this->payload['pager']['page'],
          'paginate' => true,
      );

      if($filters['type']) { $args['type'] = $filters['type']; }
      if($filters['category']) { $args['category'] = array($filters['category']); }
      if($filters['term']) { $args['like_name'] = $filters['term']; }

      $result = wc_get_products($args);
      $this->payload['pager']['total'] = $result->total;
      $this->payload['pager']['pages'] = $result->max_num_pages;
      
      $prdlist = array();
      foreach ($result->products as $key => $product) {
        if ($product->get_type() == "variable") {
          if($product->get_manage_stock()) {
            $prdlist[] = $this->getFinmeta($product);
          } else {
            $varmanage = false;
            foreach ($product->get_children() as $vid) {
              $variation_obj = new WC_Product_variation($vid);
              if($variation_obj->get_manage_stock()) { $varmanage = true; }
              $prdlist[] = $this->getFinmeta($variation_obj);
            }
            if(!$varmanage) {
              $prdlist[] = $this->getFinmeta($product);
            }
          }
        } else {
          $prdlist[] = $this->getFinmeta($product);
        }
      }
      $this->payload['products'] = $prdlist;
    }

    /**
   * Inventory module entry page method
   */
    private function getInventory() {
      $this->getProducts();
      $this->payload['summary'] = $this->ask->selectRow("SELECT COUNT(iid) AS units, SUM(cost) AS totalvalue FROM fin_inventory WHERE siteid='%d' AND is_sold='%d'", array($this->view['siteid'], 0));
      $this->payload['summary']->lastupdate = $this->dateFormat($this->ask->getVar("SELECT timecr FROM fin_inventory WHERE siteid='%d' ORDER BY timecr DESC LIMIT %d", array($this->view['siteid'], 1)));
      if($this->post['initial'] == 'true') {
        $this->payload['accounts'] = $this->getAccounts();
        $this->payload['vendors'] = $this->ask->selectRows("SELECT * FROM fin_vendors WHERE siteid='%d' ORDER BY vname ASC", array($this->view['siteid']));
      }
    }

    /**
   * Inventory needs Sync
   */

    private function getNeedSync() {
      $args = [
        //'status'    => 'publish',
        //'type' => array('', 'simple', 'grouped', 'variable'),
        'orderby' => 'name',
        'order'   => 'ASC',
        'limit' => -1,
      ];
      $products = wc_get_products($args);

      $prdlist = array();
      foreach ($products as $key => $product) {
        if ($product->get_type() == "variable") {
          if($product->get_manage_stock()) {
            $prdmeta = $this->getFinmeta($product);
            if($prdmeta->finmeta->import) { $prdlist[] = $prdmeta; }
          } else {
            $varmanage = false;
            foreach ($product->get_children() as $vid) {
              $variation_obj = new WC_Product_variation($vid);
              if($variation_obj->get_manage_stock()) { $varmanage = true; }
              $prdmeta = $this->getFinmeta($variation_obj);
              if($prdmeta->finmeta->import) { $prdlist[] = $prdmeta; }
            }
            if(!$varmanage) {
              $prdmeta = $this->getFinmeta($product);
              if($prdmeta->finmeta->import) { $prdlist[] = $prdmeta; }
            }
          }
        } else {
          $prdmeta = $this->getFinmeta($product);
          if($prdmeta->finmeta->import) { $prdlist[] = $prdmeta; }
        }

        //echo $prdmeta->name;
      }
      $this->payload['products'] = $prdlist;
    }

    /**
   * Add more stock for given product
   */
    private function addStock() {
    global $wpdb;
      $now = time();
      $numunits = $this->post['units'];
      $unsold = $this->ask->getVar("SELECT COUNT(iid) FROM fin_inventory WHERE siteid='%d' AND is_sold='%d' AND pid='%s'", array($this->view['siteid'], 0, $this->post['pid']));
      $add = false;
      $totalValue = (float)$this->moneyToDB($this->post['unitcost']) * (int)$this->post['units'];
      $product = wc_get_product( $this->post['pid'] );

      for($i=1;$i<=(int)$numunits;$i++) {
        $iarr = array();
        $iarr['iid'] = str_shuffle($this->randomChars());
        $iarr['siteid'] = $this->view['siteid'];
        $iarr['pid'] = $this->post['pid'];
        $iarr['vid'] =  $this->post['vid'];
        $iarr['is_sold'] = 0;
        $iarr['cost'] = $this->moneyToDB($this->post['unitcost']);
        $iarr['timecr'] = $now+$i;
        $add = $this->put->insert($this->table, $iarr);
      }

      if($this->post['import'] == '0') {
        if(!$product->get_manage_stock()) { $product->set_manage_stock(true); }
        $product->set_stock_quantity($unsold + $this->post['units']);
        $product->save();
      }
      
      if(isset($this->post['savecost']) && $this->post['savecost']=='1') {
        $copy = array();
        $copy['coid'] = $this->randomChars();
        $copy['siteid'] = $this->view['siteid'];
        $copy['cat'] = 'inventory';
        $copy['amount'] = $totalValue;
        $copy['tr'] = $this->moneyToDB($this->post['tr']);
        $copy['datepaid'] = time();
        $copy['timecr'] = time();
        $copy['vid'] = $this->post['vid'];
        $copy['paidwith'] = $this->post['paidwith'];
        $copy['items'] = $product->get_id();
        $copy['name'] = $product->get_name().' x '.$this->post['units'];
        $copy['notes'] = esc_html__( 'Added by inventory module', 'finpose' );
        
        $addcost = $this->put->insert('fin_costs', $copy);
      }

      if($add) {
        $this->success = true;
        $this->payload = (int)$numunits;
        $this->message = esc_html__( 'Added new units successfully', 'finpose' );
      } else {
        $this->message = esc_html__( 'Unable to add new units', 'finpose' );
      }
    }

    public function getProduct() {
      $pid = $this->post['pid'];
      $prod = wc_get_product($pid);
      $this->payload = array('id'=>$pid, 'name'=>$prod->get_name());
      $this->success = true;
    }

    public function getInventoryItems() {
      $this->payload['pager'] = json_decode(stripslashes($this->post['pager']), true);

      $pid = $this->post['pid'];
      $product = wc_get_product($pid);
      $this->payload['editor']['product']['sku'] = $product->get_sku();
      $this->payload['editor']['product']['name'] = $product->get_name();
      $this->payload['pager']['total'] = $this->ask->getVar("SELECT COUNT(iid) FROM fin_inventory WHERE siteid='%d' AND pid = '%d'", array($this->view['siteid'], $pid));
      $this->payload['pager']['pages'] = ceil($this->payload['pager']['total']/$this->payload['pager']['perpage']);
      
      $start = $this->payload['pager']['perpage'] * ($this->payload['pager']['page'] - 1);
      $items = $this->ask->selectRows("SELECT * FROM fin_inventory WHERE siteid='%d' AND pid = '%d' ORDER BY timecr DESC LIMIT %d, %d", array($this->view['siteid'], $pid, $start, $this->payload['pager']['perpage']));
      
      $this->payload['editor']['product']['summary']['sold'] = $this->ask->getVar("SELECT COUNT(iid) FROM fin_inventory WHERE siteid='%d' AND pid = '%d' AND is_sold = '%d'", array($this->view['siteid'], $pid, 1));
      $this->payload['editor']['product']['summary']['unsold'] = $this->ask->getVar("SELECT COUNT(iid) FROM fin_inventory WHERE siteid='%d' AND pid = '%d' AND is_sold = '%d'", array($this->view['siteid'], $pid, 0));
      $this->payload['editor']['product']['summary']['total'] = $this->payload['editor']['product']['summary']['sold'] + $this->payload['editor']['product']['summary']['unsold'];
       
      foreach($items as $k=>$item) {
        $item->date_added = $this->dateFormat($item->timecr);
        $item->date_sold = $this->dateFormat($item->timesold);
        if($item->is_sold) {
          $timeinstock = $item->timesold - $item->timecr;
          $item->days_in_stock = round($timeinstock/86400);
        }
        $items[$k] = $item;
      }
      $this->payload['editor']['items'] = $items;
      $this->success = true;
    }

    public function removeInventoryUnit(){
      $iid = $this->post['iid'];
      $del = $this->put->delete("fin_inventory", array('iid' => $iid));
      if($del) {
        $product = wc_get_product( $this->post['pid'] );
        if($product) {
          $fin_stocks = $this->ask->getVar("SELECT COUNT(iid) FROM fin_inventory WHERE siteid='%d' AND pid = '%d' AND is_sold='%d'", array($this->view['siteid'], $this->post['pid'], 0));
          if(!$product->get_manage_stock()) { $product->set_manage_stock(true); }
          $product->set_stock_quantity($fin_stocks);
          $product->save();
        }

        $this->success = true;
        $this->message = esc_html__( 'Removed successfully', 'finpose' );
      } else {
        $this->message = esc_html__( 'Unable to remove', 'finpose' );
      }
    }
  }
}
