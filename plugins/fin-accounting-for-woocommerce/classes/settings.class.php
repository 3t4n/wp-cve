<?php
/**
 * Class for Settings management
 *
 *
 * @link              https://finpose.com
 * @since             2.1.0
 * @package           Finpose
 * @author            info@finpose.com
 */
if ( !class_exists( 'fin_settings' ) ) {
  class fin_settings extends fin_app {

    public $table = 'fin_settings';
    public $v = 'pageSettings';
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
    public function __construct($v = 'pageSettings') {
      parent::__construct();

			// POST verification, before processing
      if($this->post) {
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
      if($v != 'ajax') { $this->$v(); }
      if($this->ask->errmsg) { $this->view['errmsg'] = $this->ask->errmsg; }
		}

		public function pageSettings() {
      $this->view['logs'] = get_option('finpose_errors');
		}

    public function getSettings() {
      $sett = get_option('finpose_settings');
      $this->payload['settings'] = $sett ? $sett : array('fiscal'=>'standard', 'dateformat'=>'usa');
      $this->payload['timezones'] = $this->presets->timezones;
      $this->payload['wc_timezone'] = get_option('timezone_string');
      $this->payload['server_timezone'] = date_default_timezone_get();
    }

    public function saveSettings() {
      $this->payload['settings'] = $this->post;
      update_option( 'finpose_settings', $this->post );
    }
  }
}