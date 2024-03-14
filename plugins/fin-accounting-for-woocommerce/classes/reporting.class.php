<?php
/**
 * Class for Reporting
 *
 *
 * @link              https://finpose.com
 * @since             1.0.0
 * @package           Finpose
 * @author            info@finpose.com
 */
if ( !class_exists( 'fin_reporting' ) ) {
  class fin_reporting extends fin_app {

    public $v = 'buildTimeReport';
    public $p = '';

    public $selyear;
    public $selmonth;

    public $success = false;
    public $message = '';
    public $results = array();
    public $callback = '';

    /**
	 * Reporting Constructor
	 */
    public function __construct($v = 'buildTimeReport') {
      parent::__construct();
      $this->selyear = $this->curyear;
      $this->selmonth = $this->curmonth;

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

      if($v != 'ajax') { $this->$v(); }

      if($this->ask->errmsg) { $this->view['errmsg'] = $this->ask->errmsg; }
    }

    /**
	 * Validate all inputs before use
	 */
    public function validate() {
      $status = true;

      foreach ($this->post as $pk => $pv) {
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
	 * Generate P/L report
	 */
    private function getReport() {
      if(isset($this->post['year'])) {
        $this->selyear = $this->post['year'];
      }

      $monthframes = $this->getMonthsArray($this->selyear, $this->settings['fiscal']);
      $allmonths = true;
      if($this->settings['fiscal']=='standard' && ($this->selyear == $this->curyear)) {
        $allmonths = false;
      }

      
      
      $months = array();
      foreach ($monthframes as $m=>$mstart) {
        if($m<13) {
          $months[$m] = array('name'=> date('F',$mstart), 'year'=>$this->selyear, 'sales'=>0, 'salessh'=>0, 'cogs'=>0, 'spendings'=>array(), 'sptotal'=> 0, 'ebitda'=>0, 'tp'=> 0, 'tr'=>0, 'taxes'=>0, 'netprofit'=>0);
          $nextm = $monthframes[$m+1];

          // spendings
          $q = "SELECT * FROM fin_costs WHERE siteid='%d' AND datepaid BETWEEN '%d' AND '%d'";
          $mc = $this->ask->selectrows($q, array($this->view['siteid'], $mstart, $nextm));
          if($mc) {
            foreach ($mc as $mcv) {
              if(!isset($months[$m]['spendings'][$mcv->cat])) { $months[$m]['spendings'][$mcv->cat] = 0; }
              $months[$m]['spendings'][$mcv->cat] += (float)$mcv->amount;
              if($mcv->cat!='inventory') { 
                $months[$m]['sptotal'] += (float)$mcv->amount;
              }
              $months[$m]['tr'] += (float)$this->format($mcv->tr);
            }
          }

          // orders
          $orders = $this->ask->getOrdersByDate($mstart,$nextm);
          foreach ($orders as $order) {
            $months[$m]['sales'] += $order->get_total();
            $months[$m]['salessh'] += $order->get_shipping_total();
            $months[$m]['tp'] += $order->get_total_tax();
          }

          $cogs = $this->ask->getVar("SELECT SUM(cost) AS cogs FROM fin_inventory WHERE siteid='%d' AND timesold BETWEEN %d AND %d", array($this->view['siteid'], $mstart, $nextm));
          $months[$m]['cogs'] = $cogs?$cogs:0;

          $months[$m]['gross'] = $months[$m]['sales'] - $months[$m]['salessh'] - $months[$m]['cogs'];
          $months[$m]['ebitda'] = $months[$m]['gross'] - $months[$m]['sptotal'];
          $months[$m]['taxes'] = $months[$m]['tp'] - $months[$m]['tr'];
          $months[$m]['netprofit'] = $months[$m]['ebitda'] - $months[$m]['taxes'];
        }
      }

        $this->view['categories'] = $this->getSpendingCategories();
        $this->view['year'] = $this->selyear;
        $this->view['data'] = $months;
    }

    /**
	 * Generate Product Based Reports
	 */
    private function buildProductReport() {

      if(isset($this->post['year'])) {
        $this->selyear = $this->post['year'];
      }

      $mstart = $this->selyear.'-01-01';
      $mend = ($this->selyear+1).'-01-01';
      $msu = strtotime($mstart);
      $mse = strtotime($mend)-1;

      $products = $this->ask->getAllProducts();
      $numproducts = count($products);

      $q = "SELECT type, SUM(amount) AS total, IFNULL(SUM(tr), 0) AS tr FROM fin_costs WHERE siteid='%d' AND (datepaid BETWEEN '%d' AND '%d') AND items='%d' GROUP BY type";
      $costs4all = $this->ask->selectRows($q, array($this->view['siteid'], $msu, $mse, 0));
      $allperitem = array('cost'=>0, 'expense'=>0, 'acquisition'=>0 ,'tr'=>0, 'qty'=>0, 'tp'=>0, 'sa'=>0, 'sh'=>0 );
      $trall = 0;
      foreach ($costs4all as $ctype) {
        $t = $ctype->type;
        $allperitem[$t] = $ctype->total/$numproducts;
        $trall += $ctype->tr;
      }
      $allperitem['tr'] = $trall/$numproducts;

      $data = array();
      foreach ($products as $product) {
        unset($pdata);
        $pdata = $allperitem;
        $pdata['wcid'] = $wcpid = $product->get_id();
        $pdata['name'] = $product->get_name();

        // costs
        $q = "SELECT type, SUM(amount) AS total, IFNULL(SUM(tr), 0) AS tr FROM fin_costs WHERE siteid='%d' AND (datepaid BETWEEN '%d' AND '%d') AND items='%s' GROUP BY type";
        $costs4item = $this->ask->selectRows($q, array($this->view['siteid'], $msu, $mse, $pdata['wcid']));
        foreach ($costs4item as $ictype) {
          $t = $ictype->type;
          $pdata[$t] += $ictype->total;
          $pdata['tr'] += $ictype->tr;
        }

        $data[$wcpid] = $pdata;
      }
      //print_r($data);
      $this->view['shtax'] = 0;
      $orders = $this->ask->getOrdersByDate($msu, $mse);
      foreach ($orders as $order) {
        $items = $order->get_items();
        //print_r($items);
        $this->view['shtax'] += $order->get_shipping_tax();
        $sh = $order->get_shipping_total();
        $numitems = count($items);
        $avgsh = $numitems>0?$sh/$numitems:0;
        foreach ( $items as  $item_key => $item ) {
          $pid = $item->get_product_id();
          $itemarr = $item->get_data();
          $data[$pid]['qty'] += $itemarr['quantity'];
          $data[$pid]['tp'] += $itemarr['total_tax'];
          $data[$pid]['sa'] += $itemarr['total'];
          $data[$pid]['sh'] += $avgsh;
        }
      }

      foreach ($data as $pid => $parr) {
        $in = $parr['sa'] + $parr['sh'];
        $out = $parr['cost'] + $parr['expense'] + $parr['acquisition'];
        $data[$pid]['pl'] = $in - $out;
        $data[$pid]['roi'] = 0;
        if($out) {
          $data[$pid]['roi'] = (($in/$out)*100)-100;
        }

      }

      $this->view['data'] = $this->autoFormat($data);
      $this->view['totals'] = $this->getTotals($data);
    }

    /**
	 * Generate Sales Reports
	 */
    private function buildSalesReport() {
      if(isset($this->post['year'])) {
        $this->selyear = $this->post['year'];
        $this->selmonth = $this->post['month'];
        $this->selcat = $this->post['cat'];
      }
      $mstart = $this->selyear.'-'.$this->selmonth.'-01';
      $mend = $this->selyear.'-'.($this->selmonth+1).'-01';
      if($this->selmonth=='12') { $mend = ($this->selyear+1).'-01-01'; }

      $msu = strtotime($mstart);
      $mse = strtotime($mend)-1;

      $data = array();
      $orders = $this->ask->getOrdersByDate($msu, $mse);
      foreach ($orders as $order) {
        $odata = array();
        $odata['id'] = '#'.$order->get_id();
        $odata['url'] = $order->get_edit_order_url();
        $odata['date'] = $order->get_date_paid()->getTimestamp();
        $odata['account'] = $order->get_payment_method_title();
        $odata['cusname'] = $order->get_billing_first_name().' '.$order->get_billing_last_name();
        $odata['country'] = $order->get_billing_country();
        $odata['tax'] = $order->get_total_tax();
        $odata['shipping'] = $order->get_shipping_total();
        $odata['amount'] = $order->get_total();
        $data[] = $odata;
      }
      $this->view['data'] = $this->autoFormat($data);
      $this->view['totals'] = $this->getTotals($data);
    }

    public function getBalanceSheet() {
    global $wpdb;
      if(isset($this->post['year'])) {
        $this->selyear = $this->post['year'];
      }

      $mstart = $this->selyear.'-01-01';
      $mend = ($this->selyear+1).'-01-01';
      if($this->settings['fiscal'] == 'indian') {
        $mstart = $this->selyear.'-04-01';
        $mend = ($this->selyear+1).'-04-01';
      }

      if($this->settings['fiscal'] == 'australian') {
        $mstart = $this->selyear.'-07-01';
        $mend = ($this->selyear+1).'-07-01';
      }
      $msu = strtotime($mstart);
      $mse = strtotime($mend)-1;

      $order_totals = $this->ask->getVar("SELECT SUM(pm.meta_value) FROM {$wpdb->prefix}posts as p INNER JOIN {$wpdb->prefix}postmeta as pm ON p.ID = pm.post_id WHERE p.post_type = 'shop_order' AND p.post_status IN ('wc-completed') AND pm.meta_key = '_order_total' AND p.post_date BETWEEN '%s' AND '%s'", array($mstart, $mend) );
      $acc_receive = $this->ask->getVar("SELECT SUM(pm.meta_value) FROM {$wpdb->prefix}posts as p INNER JOIN {$wpdb->prefix}postmeta as pm ON p.ID = pm.post_id WHERE p.post_type = 'shop_order' AND p.post_status IN ('wc-pending', 'wc-on-hold') AND pm.meta_key = '_order_total' AND p.post_date BETWEEN '%s' AND '%s'", array($mstart, $mend) );
      $tax_receive = $this->ask->getVar("SELECT SUM(tr) FROM fin_costs WHERE datepaid BETWEEN '%d' AND '%d'", array($msu, $mse));
      $inventory = $this->ask->getVar("SELECT SUM(cost) FROM fin_inventory WHERE is_sold='%d'", array(0));
      $assets = $order_totals + $acc_receive + $tax_receive + $inventory;

      $expenses = $this->ask->getVar("SELECT SUM(amount) FROM fin_costs WHERE datepaid BETWEEN '%d' AND '%d'", array($msu, $mse));
      $vendor_amount = $this->ask->getVar("SELECT SUM(amount) FROM fin_purchase_orders");
      $vendor_paid = $this->ask->getVar("SELECT SUM(amount) FROM fin_costs WHERE vid!='%d'", array(0));
      $vendor_balance = $vendor_amount - $vendor_paid;
      $tax_pay = $this->ask->getVar("SELECT SUM(pm.meta_value) FROM {$wpdb->prefix}posts as p INNER JOIN {$wpdb->prefix}postmeta as pm ON p.ID = pm.post_id WHERE p.post_type = 'shop_order' AND p.post_status IN ('wc-completed') AND pm.meta_key IN ('_order_tax', '_order_shipping_tax') AND p.post_date BETWEEN '%s' AND '%s'", array($mstart, $mend) );

      $this->payload['data']['cash'] = $order_totals;
      $this->payload['data']['acc_receive'] = $acc_receive;
      $this->payload['data']['tax_receive'] = $tax_receive;
      $this->payload['data']['inventory'] = $inventory;
      $this->payload['data']['assets'] = $assets;
      $this->payload['data']['expenses'] = $expenses;
      $this->payload['data']['vendor_balance'] = $vendor_balance;
      $this->payload['data']['tax_pay'] = $tax_pay;
      $this->payload['data']['liabilities'] = $expenses + $vendor_balance + $tax_pay;
      $this->payload['data']['equity'] = $assets - $this->payload['data']['liabilities'];

      $this->payload['data']['last_query'] = $this->ask->last_query;
    }

    /**
     * Get list of spending categories
     */
    private function getSpendingCategories() {
      $costs = get_option('fin-cost-categories');
      $expenses = get_option('fin-expense-categories');
      $acqs = get_option('fin-acquisition-categories');
      return array_merge($costs,$expenses,$acqs);
    }



  }
}
