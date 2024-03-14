<?php

class PMLC_Destination_Record extends PMLC_Model_Record {
	/**
	 * Initialize model instance
	 * @param array[optional] $data Array of record data to initialize object with
	 */
	public function __construct($data = array()) {
		parent::__construct($data);
		$this->setTable(PMLC_Plugin::getInstance()->getTablePrefix() . 'destinations');
	}
	
	public function getUrl() {
		if (isset($this->url)) {
			return $this->url;
		} else {
			return NULL;
		}
	}
	
	/**
	 * Redirect to urls defined by the current destination
	 * NOTES: function relies on `__a` get parameter to track redirect methods (dev note: codes below 100 are inital referer loosing operation, codes above 100 is operation of loosing referer after tracking code is output) 
	 * @param string $type Redirection type
	 */
	public function redirect($type) {
		$url = $this->url;
		
		if ( ! headers_sent()) {
			header('Cache-Control: no-cache');
	  		header('Pragma: no-cache');
		}
		$rule = $this->getRelated('PMLC_Rule_Record', array('id' => 'rule_id'));
		$link = $rule->getRelated('PMLC_Link_Record', array('id' => 'link_id'));
		if ('REFERER_MASK' == $type) {
			isset($_GET['__a']) and $__a = intval($_GET['__a']) or $__a = 0;
			if ( ! $__a) {
				// store original referer in session so we log it in plugin's own click history
				isset($_SERVER['HTTP_REFERER']) and $_SESSION[PMLC_Plugin::PREFIX . 'referer'] = $_SERVER['HTTP_REFERER']
					or $_SESSION[PMLC_Plugin::PREFIX . 'referer'] = '';
			}
			if (empty($_SERVER['HTTP_REFERER']) and ! in_array($__a, array(3, 103))) { // no referer detected (skip 3 and 103 codes since they are intermediate steps of loosing referer with frameset method)
				if (100 <= $__a or '' == trim($link->getTrackingCode('header')) and '' == trim($link->getTrackingCode('footer'))) { // no tracking code required
					$this->redirect(301); // ordinary redirect
				} else { // output tracking code
					switch ($__a) { // adjust next step so we use method which lost referer right away and do not loop through them once again
						case 0:
						case 1:
							$__a = 100;
							break;
						case 2:
							$__a = 101;
							break;
						case 4:
							$__a = 102;
							break;
						default: // (!) unrecognized redirect method
							return FALSE;
					}
					$url = add_query_arg('__a', $__a);
					echo  '<html><head><title>' . $link->name . '</title><meta http-equiv="refresh" content="' . PMLC_Plugin::getInstance()->getOption('meta_redirect_delay') . '; URL=' . $url . '" />' . $link->getTrackingCode('header') . '</head><body>' . $link->getTrackingCode('footer') . '</body></html>';
				}
			} else { // try to hide referer
				$url = add_query_arg('__a', $__a + 1);
				switch($__a) {
					// refresh header
					case 0:
					case 100:
						header('Refresh: 0;url=' . $url);
						break;
					// meta refresh
					case 1:
					case 101:
						echo  '<html><head><title>' . $link->name . '</title><meta http-equiv="refresh" content="0; URL=' . $url . '" /></head><body></body></html>';
						break;
					// frameset: 1st step
					case 2:
					case 102:
						echo '<html><head><title>' . $link->name . '</title></head><frameset><frame src="' . $url . '"></frame></frameset></html>';
						break;
					// frameset: 2nd step
					case 3:
					case 103:
						echo '<html><head><title>' . $link->name . '</title></head><body><iframe src="javascript:window.top.location.replace(\'' . $url . '\')" style="display:none"></iframe></body></html>';
						break;
					default: // unable to make redirect with referer masked
						return FALSE;
						break;
				}
				
			}
		} else {
			if (PMLC_Plugin::getInstance()->getOption('forward_url_params') and $link->forward_url_params) { // forward query params
				foreach ($_GET as $key => $val) {
					! in_array($key, array('subid', 'cloaked', '__a')) and $url = add_query_arg($key, $val, $url);
				}
			}
			
			if (is_numeric($type)) {
				header("Location: " . $url, true, intval($type));
			} else if ('META_REFRESH' == $type) {
				echo  '<html><head><title>' . $link->name . '</title><meta http-equiv="refresh" content="' . PMLC_Plugin::getInstance()->getOption('meta_redirect_delay') . '; URL=' . $url . '" />' . $link->getTrackingCode('header') . '</head><body>' . $link->getTrackingCode('footer') . '</body></html>';
			} else if ('JAVASCRIPT' == $type) {
				echo '<html><head><title>' . $link->name . '</title>' . $link->getTrackingCode('header') . '</head><body><script type="text/javascript">window.onload=function(){window.top.location.replace(\'' . $url . '\');};</script>' . $link->getTrackingCode('footer') . '</body></html>';
			} else if ('FRAME' == $type) {
				echo '<html><title>' . $link->name . '</title><style>*{margin:0;padding:0;border:none;width:100%;height:100%;}</style>' . $link->getTrackingCode('header') . '</head><body><iframe src="' . $url . '"></iframe>' . $link->getTrackingCode('footer') . '</body></html>';
			} else {
				throw new Exception('Unsupported redirection type specified');
			}
			
			// [log redirect to stats]
			$input = new PMLC_Input();
			$geoip = new PMLC_GeoIPCountry_Record();
			
			// detect referrer
			$referer = '';
			if ('REFERER_MASK' == $link->redirect_type) {
				isset($_SESSION[PMLC_Plugin::PREFIX . 'referer']) and $referer = $_SESSION[PMLC_Plugin::PREFIX . 'referer'];
			} else {
				isset($_SERVER['HTTP_REFERER']) and $referer = $_SERVER['HTTP_REFERER'];
			}
			unset($_SESSION[PMLC_Plugin::PREFIX . 'referer']);
			
			// create stat object
			$stat = new PMLC_Stat_Record(array(
				'link_id' => $link->id,
				'sub_id' => isset($_GET['subid']) ? $_GET['subid'] : '',
				'registered_on' => date('Y-m-d H:i:s'),
				'rule_type' => $rule->type,
				'destination_url' => $url,
				'ip' => $input->server('REMOTE_ADDR', ''),
				'ip_num' => sprintf('%u', ip2long($input->server('REMOTE_ADDR', '0.0.0.0'))),
				'country' => ! $geoip->getByIp($input->server('REMOTE_ADDR', ''))->isEmpty() ? $geoip->country : '',
				'host' => $input->server('REMOTE_HOST', ''),
				'user_agent' => $input->server('HTTP_USER_AGENT', ''),
				'accept_language' => $input->server('HTTP_ACCEPT_LANGUAGE', ''),
				'referer' => $referer,
			));
			$stat->insert();
			// [/log redirect to stats]
			
		}
		die();
	}

}