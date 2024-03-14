<?php
/**
 * Class for Vendor management
 *
 *
 * @link              https://finpose.com
 * @since             1.0.0
 * @package           Finpose
 * @author            info@finpose.com
 */
if ( !class_exists( 'fin_vendors' ) ) {
  class fin_vendors extends fin_app {

    public $table = 'fin_costs';
    public $v = 'getCosts';
    public $p = '';

    public $selyear;
    public $selmonth;
    public $selcat = '';

    public $success = false;
    public $message = '';
    public $payload = array();
    public $callback = '';

    /**
	 * Constructor
	 */
    public function __construct($v = 'getVendors') {
      parent::__construct();

      $this->selyear = $this->curyear;
      $this->selmonth = $this->curmonth;

      $this->view['accounts'] = $this->getAccounts();

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
        $this->view['products'] = wc_get_products( $args );

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
          if(!in_array($pv, array_keys($this->presets->costTypes)) && $pv != 'all') {
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
        if(in_array($pk, array('amount', 'tr'))) {
          if(!preg_match('/^(?!0\.00)\d{1,3}(,\d{3})*(\.\d\d)?$/', $pv) && $pv!='0.00') {
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

    public function pageVendors() {

    }

		public function getVendors() {
      $this->payload['pager'] = json_decode(stripslashes($this->post['pager']), true);
      $this->payload['pager']['total'] = $this->ask->getVar("SELECT COUNT(vid) FROM fin_vendors WHERE siteid='%d'", array($this->view['siteid']));
      $this->payload['pager']['pages'] = ceil($this->payload['pager']['total']/$this->payload['pager']['perpage']);
      $start = $this->payload['pager']['perpage'] * ($this->payload['pager']['page'] - 1);

      $w = "ORDER BY vid ASC";
      $q = "SELECT * FROM fin_vendors $w LIMIT %d, %d";
      $vals = array($start, $this->payload['pager']['perpage']);
      $vendors = $this->ask->selectRows($q, $vals);
      
      foreach ($vendors as $k=>$vd) {
        $vendors[$k]->paid = $paid = $this->ask->getVar("SELECT SUM(amount) FROM fin_costs WHERE siteid='%d' AND vid='%d'", array($this->view['siteid'], $vd->vid));
        $vendors[$k]->total = $total = $this->ask->getVar("SELECT SUM(amount) FROM fin_purchase_orders WHERE siteid='%d' AND vid='%d'", array($this->view['siteid'], $vd->vid));
        $vendors[$k]->unpaid = $total - $paid;
      }

			$this->payload['vendors'] = $vendors;
		}

    public function addVendor() {
      $v = array();
      $v['vname'] = $this->post['name'];
      $v['siteid'] = $this->view['siteid'];
      $v['timecr'] = time();
      $add = $this->put->insert('fin_vendors', $v);
      $this->success = $add ? true : false;
      $this->message = $add ? __('Success', 'finpose') : $this->put->errmsg;
    }

    public function editVendor() {
      $v = array('vname' => $this->post['name']);
      $edit = $this->put->update('fin_vendors', $v, array('vid'=>$this->post['vid']));
      $this->success = $edit ? true : false;
      $this->message = $edit ? __('Success', 'finpose') : $this->put->errmsg;
    }

    public function getPurchaseOrders() {
      $this->payload['pager'] = json_decode(stripslashes($this->post['pager']), true);
      $this->payload['pager']['total'] = $this->ask->getVar("SELECT COUNT(poid) FROM fin_purchase_orders WHERE vid='%d' AND siteid='%d'", array($this->post['vid'], $this->view['siteid']));
      $this->payload['pager']['pages'] = ceil($this->payload['pager']['total']/$this->payload['pager']['perpage']);
      $start = $this->payload['pager']['perpage'] * ($this->payload['pager']['page'] - 1);

      $w = "WHERE vid='%d' ORDER BY poid DESC LIMIT %d, %d";
      $q = "SELECT * FROM fin_purchase_orders $w";
      $vals = array($this->post['vid'], $start, $this->payload['pager']['perpage']);
      $porders = $this->ask->selectRows($q, $vals);

      foreach($porders as $pk => $po) {
        $porders[$pk]->amount_paid = $this->ask->getVar("SELECT SUM(amount) FROM fin_costs WHERE poid='%d' AND siteid='%d'", array($po->poid, $this->view['siteid']));
      }
      
			$this->payload['porders'] = $porders;
      $this->payload['categories']['cost'] = get_option('fin-cost-categories');
      $this->payload['categories']['expense'] = get_option('fin-expense-categories');
      $this->payload['categories']['acquisition'] = get_option('fin-acquisition-categories');
      $this->payload['accounts'] = $this->getAccounts();
    }

    public function addPurchaseOrder() {
      $po = $this->post;
      $po['timedue'] = strtotime($po['datedue']);
      $po['siteid'] = $this->view['siteid'];
      $po['amount'] = $this->moneyToDB($po['amount']);
      $po['timecr'] = time();
      unset($po['datedue']);
      $add = $this->put->insert('fin_purchase_orders', $po);
      $this->success = $add ? true : false;
      $this->message = $add ? __('Success', 'finpose') : $this->put->errmsg;
    }

    public function editPurchaseOrder() {
      $po = $this->post;
      $po['amount'] = $this->moneyToDB($po['amount']);
      $poid = $po['poid'];
      $po['timedue'] = strtotime($po['datedue']);
      unset($po['poid'], $po['datedue']);
      $edit = $this->put->update('fin_purchase_orders', $po, array('poid'=>$poid));
      $this->success = $edit ? true : false;
      $this->message = $edit ? __('Success', 'finpose') : $this->put->errmsg;
    }

    public function deletePurchaseOrder() {
      $del = $this->put->delete('fin_purchase_orders', array('poid'=>$this->post['key']));
      if($del) {
        $this->message = esc_html__( 'Removed purchase order from records successfully.', 'finpose' );
        $this->success = true;
      }
    }

    public function getVendorPayments() {
      $costs = $this->ask->selectRows("SELECT * FROM fin_costs WHERE vid='%d' ORDER BY timecr DESC", array($this->post['vid']));
      $totals = array('amount'=>0, 'tr'=>0);

      foreach ($costs as $r=>$c) {
        $costs[$r]->amountFormatted = $this->format($c->amount);
        $costs[$r]->trFormatted = $this->format($c->tr);
        $costs[$r]->pm = $this->view['accounts'][$c->paidwith];
        $costs[$r]->datepick = date("Y-m-d", $c->datepaid);
        $costs[$r]->datepaid = $this->dateFormat($c->datepaid);
        $totals['amount'] += $c->amount;
        $totals['tr'] += $c->tr;
      }
      $totals['amount'] = $this->format($totals['amount']);
      $totals['tr'] = $this->format($totals['tr']);
      $this->payload['payments'] = $costs;
      $this->payload['totals'] = $totals;
      $this->getCategories();
    }

    /**
	 * List cost categories
	 */
    public function getCategories() {
      $this->payload['categories']['cost'] = get_option('fin-cost-categories');
      $this->payload['categories']['expense'] = get_option('fin-expense-categories');
      $this->payload['categories']['acquisition'] = get_option('fin-acquisition-categories');
      $this->success = true;
    return $this->payload;
    }

    public function rotateStatus() {
      $status = $this->post['status'];
      if($status=='unpaid') { $ns = 'partial'; }
      if($status=='partial') { $ns = 'paid'; }
      if($status=='paid') { $ns = 'unpaid'; }
      $edit = $this->put->update('fin_purchase_orders', array('status'=>$ns), array('poid'=>$this->post['poid']));
      $this->success = $edit ? true : false;
    }

    /**
	 * Attach file to the Purchase Order
	 */
  public function attachFile() {
    require_once(ABSPATH.'wp-admin/includes/file.php');
    $uploadedfile = $_FILES['file'];
    $key = $this->post['poid'];
    $movefile = wp_handle_upload($uploadedfile, array('test_form' => false)); 
    
    if ( $movefile ){
      $this->put->update('fin_purchase_orders', array('attfile'=>$movefile['url']), array('poid'=>$key));
      $this->callback = 'reload';
      $this->payload = $movefile;
      $this->message = esc_html__( 'Uploaded successfully', 'finpose' );
      $this->success = true;
      return;
    }
    $this->message = esc_html__( 'Unable to upload file', 'finpose' );
  }

		
	}
}