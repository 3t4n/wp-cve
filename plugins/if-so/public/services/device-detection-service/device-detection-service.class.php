<?php
	
	namespace IfSo\PublicFace\Services\DeviceDetectionService;
	require_once('IfSo_Mobile_Detector.php');
	class DeviceDetectionService {
		private static $instance;
		private $detector;
		private function __construct() {
			$this->detector = new \IfSo_Mobile_Detect;
		}
		public static function get_instance() {
			if ( NULL == self::$instance )
				self::$instance = new DeviceDetectionService();
			return self::$instance;
		}
		public function is_mobile() {
			if( $this->detector->isMobile() && !$this->detector->isTablet() ) {
				return true;
			} else {
				return false;
			}
		}
		public function is_tablet() {
			if( $this->detector->isTablet() ) {
				return true;
			} else {
				return false;
			}
		}
	}