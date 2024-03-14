<?php
/**
 * Application main class
 *
 *
 * @link              https://finpose.com
 * @since             1.0.0
 * @package           Finpose
 * @author            info@finpose.com
 */
if ( !class_exists( 'fin_app' ) ) {
  class fin_app {
    public $put;
    public $ask;
    public $get = array();
    public $post = array();
    public $presets = array();
    public $success = false;
    public $message = '';
    public $db;
    public $validated = false;

    public $curyear;
    public $curmonth;

    public $view = array();

    public $cookiepath = '';
    public $cookiehost = '';
    public $cookieexpire = '';

    public $previous_error_handler = null;
    public $previous_exception_handler = null;

    /**
	 * Finpose App Constructor
	 */
    public function __construct() {
      $this->assignInputs();

      $this->curyear = date('Y');
      $this->curmonth = date('m');
      $this->curq = ceil(date("n") / 3);

      $this->view['years'] = $this->getYears();
      
      $this->cookiepath = parse_url(get_option('siteurl'), PHP_URL_PATH);
      $this->cookiehost = parse_url(get_option('siteurl'), PHP_URL_HOST);
      $this->cookieexpire = strtotime('+1 month');

      $this->settings = get_option('finpose_settings');
      if(!isset($this->settings['fiscal']) || !$this->settings['fiscal']) { $this->settings['fiscal'] = 'standard'; }
      if(!isset($this->settings['dateformat']) || !$this->settings['dateformat']) { $this->settings['dateformat'] = 'default'; }

      require 'put.class.php';
      $this->put = new fin_put($this->db);

      require 'ask.class.php';
      $this->ask = new fin_ask($this->db);

      require 'preset.class.php';
      $this->presets = new fin_preset();
      $this->view['presets'] = json_encode($this->presets);

      if (function_exists('is_multisite') && is_multisite()) {
        $this->view['multisite'] = true;
        $this->view['siteid'] = get_current_blog_id();
      } else {
        $this->view['multisite'] = false;
        $this->view['siteid'] = 0;
      }

      $this->setErrorHandler();

    }

    /**
	 * All GET, POST variables sanitized as string, additional sanitation will be applied inside methods when necessary
	 */
    public function assignInputs() {
      $this->get = array_map('sanitize_text_field', $_GET);
      $this->post = array_map('sanitize_text_field', $_POST);
    }

    /**
	 * Retrieve list of years available
	 */
    public function getYears() {
      $cy = date('Y');
      $cs = $cy-7;
      $years = array();
      for ($i = $cs; $i <= $cy; $i++) {
        $years[$i] = $i;
      }
    return array_reverse($years, true);
    }

    /**
	 * Get start of each month for given year in unix timestamp
	 */
    public function getMonthsUnix($year) {
      $months = array();
      for ($m = 1; $m < 13; $m++) {
        $mstart = strtotime($year."-".$m."-01");
        $months[$m] = $mstart;
        if($m==12) {
          $months[] = strtotime(($year+1)."-01-01");
        }
      }
    return $months;
    }

    public function getMonthsArray($year, $format) {
      $months = array();
      for ($m = 1; $m <= 13; $m++) {
        $str = '+'.($m-1).' months';
        $start = $year."-01-01 ";
        if($format=='indian') {
          $start = $year."-04-01 ";
        }

        if($format=='australian') {
          $start = $year."-07-01 ";
        }
        // $mstart = date('Y-m-d', strtotime($start.$str));
        $mstart = strtotime($start.$str);
        $months[$m] = $mstart;
      }
    return $months;
    }

    /**
	 * Format given timestamp as date
	 */
    public function dateFormat($unix, $hours = false, $format = '') {
      $wc_timezone = get_option('timezone_string');
      $system_timezone = $wc_timezone ? $wc_timezone : date_default_timezone_get();
      $display_timezone = isset($this->settings['timezone']) && $this->settings['timezone'] ? $this->settings['timezone'] : $system_timezone;
      $timezone = $display_timezone;

      if($this->settings['dateformat']=='default') { 
        $dateStr = "M d, Y".($hours?' H:i':'');
      }

      if($this->settings['dateformat']=='usa') { 
        $dateStr = "m-d-Y".($hours?' H:i':'');
      }

      if($this->settings['dateformat']=='european') { 
        $dateStr = "d-m-Y".($hours?' H:i':'');
      }

      if($this->settings['dateformat']=='universal') { 
        $dateStr = "r";
      }

      if($format) {
        $dateStr = $format;
      }

      $tz = new DateTimeZone($timezone);
      $tzGMT = new DateTimeZone("Europe/London");
      $dtGMT = new DateTime("now", $tzGMT);
      $offset = $tz->getOffset($dtGMT);
      $date = new DateTime(date('c', $unix), $tz);
      $offsetStr = ($offset/3600).' hours';
      $dt = $date->modify($offsetStr);
      $result = $dt->format($dateStr);

      return $result;
    }

    /**
	 * Format monetary values
	 */
    public function format($amount,$commas=true) {
      if(!$amount) return 0;
      $thousandSeperator = ',';
      if(!$commas) $thousandSeperator = '';
      return number_format((float)$amount, 2, '.', $thousandSeperator);
    }

    /**
	 * Format monetary values before insert operation
	 */
    public function moneyToDB($amount) {
      $amt = preg_replace("/([^0-9\\.])/i", "", $amount);
      return $amt;
    }

    /**
	 * Add zero when month number below 10
	 */
    public function addZero($month) {
      return intval($month)<10?'0'.intval($month):$month;
    }


    /**
	 * Add zero when month number below 10
	 */
    public function nextMonth($year, $month, $day) {
      $nextm = intval($month)+1;
      $nextmonth = $year.'-'.$this->addZero($nextm).'-01';
      if($month === '12') {
        $nextmonth = (intval($year)+1).'-01-'.$day;
      }
    return $nextmonth;
    }

    /**
	 * Format all values in given array recursively
	 */
    public function autoFormat($marr, $commas=true) {
      foreach ($marr as $mk=>$mv) {
        if(is_array($mv)) {
          foreach ($mv as $sk=>$sv) {
            $t = gettype($sv);
            if ($t=='double'||$t=='float') {
              $marr[$mk][$sk] = $this->format($sv, $commas);
            } else if ($t=='integer') {
              if($sk=='qty') { $marr[$mk][$sk] = $sv; } else {
                $marr[$mk][$sk] = $this->format($sv, $commas);
              }
            } else if ($t=='string') {
              $marr[$mk][$sk] = $sv;
            }
          }
        } else {
          $t = gettype($mv);
          if ($t=='double'||$t=='float') {
            $marr[$mk] = $this->format($mv, $commas);
          } else if ($t=='integer') {
            if($mk=='qty') { $marr[$mk] = $mv; } else {
              $marr[$mk] = $this->format($mv, $commas);
            }
          } else if ($t=='string') {
            $marr[$mk] = $mv;
          }
        }
      }
    return $marr;
    }

    /**
	 * Produce random chars
	 */
    public function randomChars($length = 8) {
      $start = rand(0,10);
      return substr(md5(time()), $start, $length);
    }

    /**
     * Get Account Name
     */
    public function getAccountName($ak) {
      $accs = get_option('finpose_accounts');
      if(isset($accs[$ak])) {
        return $accs[$ak]['name'];
      }
    return 'N/A';
    }

    /**
     * Get Enabled Accounts
     */
    public function getAccounts() {
      return get_option('finpose_accounts');
    }

    /**
	 * Create account slug
	 */
    public function slugit($string, $separator = '-') {
      $accents_regex = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
      $special_cases = array( '&' => 'and', "'" => '');
      $string = strtolower( trim( $string ) );
      $string = str_replace( array_keys($special_cases), array_values( $special_cases), $string );
      $string = preg_replace( $accents_regex, '$1', htmlentities( $string, ENT_QUOTES, 'UTF-8' ) );
      $string = preg_replace("/[^a-z0-9]/u", "$separator", $string);
      $string = preg_replace("/[$separator]+/u", "$separator", $string);
      return $string;
    }

    public function setFilters() {
      $filters = json_decode(stripslashes($this->post['filters']), true);

      $mstart = $this->selyear.'-'.($this->selmonth).'-01';
      $mend = $this->selyear.'-'.$this->addZero(($this->selmonth+1)).'-01';
      if($this->selmonth=='12') { $mend = ($this->selyear+1).'-01-01'; }
      $msu = strtotime($mstart);
      $mse = strtotime($mend)-1;

      if($filters['datestart']) { $mstart = $filters['datestart']; $msu = strtotime($mstart); }
      if($filters['dateend']) { $mend = $filters['dateend']; $mse = strtotime($mend); }
      
      if($msu>$mse) {
        $this->message = esc_html__( 'Start date should be before end date', 'finpose' );
        return false;
      }
      
      $this->payload['filters'] = $filters;
      $this->payload['filters']['datestart'] = date('Y-m-d', $msu);
      $this->payload['filters']['dateend'] = date('Y-m-d', $mse);
    return true;
    }

    public function setErrorHandler() {
      $this->previous_error_handler = set_error_handler( array( $this, 'error_handler' ), ( E_ALL ) );

      if ( ! interface_exists( 'Throwable' ) ) {
        // Fatal error handler for PHP < 7:
        register_shutdown_function( array( $this, 'shutdown_handler' ) );
      }

      $this->previous_exception_handler = set_exception_handler( array( $this, 'exception_handler' ) );
    }

    public function shortenPath($filePath) {
      $filePathArr = explode('/', $filePath);
      $filePathElements = array_slice($filePathArr, -3);
      return implode('/', $filePathElements);
    }

    public function exception_handler( $e ) {
      $msg = $e->getMessage();
      $file = $this->shortenPath($e->getFile());
      $line = $e->getLine();
      $err = "$msg ($file:$line)";
      $this->logError($err);
      //$e->getTrace()
      if ( $this->previous_exception_handler ) {
        call_user_func( $this->previous_exception_handler, $e );
      } else {
        throw $e;
      }
      exit( 1 );
    }

    public function error_handler( $errno, $msg, $file = null, $line = null, $context = null, $do_trace = true ) {
      $file = $this->shortenPath($file);
      $err = "$msg ($file:$line)";
      $this->logError($err);
    }

    public function shutdown_handler() {
      $e = error_get_last();
      $msg = $e['message'];
      $file = $this->shortenPath($e['file']);
      $line = $e['line'];
      $err = "$msg ($file:$line)";
      $this->logError($err);
    }

    private function logError($err) {
      $ver = phpversion();
      $wpver = get_bloginfo('version');
      $wcver = WC_VERSION;
      $finver = FINPOSE_VERSION;
      $date = date('j/n');
      $errLine = "$date ($ver|$wpver|$wcver|$finver) $err";
      $logs = get_option('finpose_errors');
      if(!$logs) {
        $logs = array();
      }

      array_unshift($logs, $errLine);
      if(count($logs)>10) {
        array_pop($logs);
      }

      update_option('finpose_errors', $logs);
    }

  }

}
