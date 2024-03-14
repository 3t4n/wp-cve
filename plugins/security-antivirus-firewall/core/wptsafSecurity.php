<?php
/*  
 * Security Antivirus Firewall (wpTools S.A.F.)
 * http://wptools.co/wordpress-security-antivirus-firewall
 * Version:           	2.3.5
 * Build:             	77229
 * Author:            	WpTools
 * Author URI:        	http://wptools.co
 * License:           	License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * Date:              	Sat, 01 Dec 2018 19:09:28 GMT
 */

if ( ! defined( 'WPINC' ) )  die;
if ( ! defined( 'ABSPATH' ) ) exit;

class wptsafSecurity extends wptsafAbstractExtension{

	protected $settings;
	protected $extensions = array();
	protected static $instance;

	public function __construct(){
		$this->name = 'wptsaf-security';
		$this->title = __('S.A.F. Security', 'security-antivirus-firewall');
		$this->description = __('Security SAF Description', 'security-antivirus-firewall');

		parent::__construct();

		register_activation_hook(WPTSAF_PLUGINNAME, array($this, 'activatePlugin'));
		register_deactivation_hook(WPTSAF_PLUGINNAME, array($this, 'deactivatePlugin'));
		register_uninstall_hook(WPTSAF_PLUGINNAME, array(get_called_class(), 'uninstallPlugin'));

		$runUpdate = true;
		$installVersion = get_option( 'wpsaf_install_version' );
		if(!$installVersion) $installVersion = 0;
		if( $installVersion && $installVersion == WPTSAF_VERSION )  $runUpdate = false;
		if( $runUpdate ){
			delete_option("wpsaf_install_version");
			add_option( "wpsaf_install_version", WPTSAF_VERSION );
			add_action('init', array($this, 'activatePlugin'));
		}
		
		//delete_option("wpsaf_hide_wizard");

		$whoWizard = get_option( 'wpsaf_hide_wizard' );

		if( !$whoWizard || $whoWizard < 99 ){
			add_action('wptsaf_admin_page_after_content', array($this, 'adminPageAfterContent'));
		}

		$locale = apply_filters('plugin_locale', get_locale(), 'wptsaf-security');
		load_textdomain('wptsaf-security', WP_LANG_DIR . "/plugins/wptsaf-security/wptsaf-security-$locale.mo" );
		load_plugin_textdomain('wptsaf-security');

		$this->dashboardWidgetHook();

	}

	public function dashboardWidgetHook(){
		add_action('wp_dashboard_setup', array($this, 'dashboardWidgetInit') );
	}


	public function dashboardWidgetInit(){
		wp_add_dashboard_widget(
			'dashboard_wptools_saf_widget', 
			__('S.A.F. - WpTools Security', 'security-antivirus-firewall'), 
			array($this, 'dashboardWidget')
		);
		$this->dashboardWidgetSetup();
	}

	public function dashboardWidgetSetup(){
		global $wp_meta_boxes;
		if( isset($wp_meta_boxes['dashboard']['normal']['core']) ){
			$widgets = $wp_meta_boxes['dashboard']['normal']['core'];
			if( isset($widgets['dashboard_wptools_saf_widget']) ){
				$temp = array( 'dashboard_wptools_saf_widget' => $widgets['dashboard_wptools_saf_widget'] );
		   		unset( $widgets['dashboard_wptools_saf_widget'] );
		    	$wp_meta_boxes['dashboard']['normal']['core'] =  $temp + $widgets;
			}
		}
	}

	public function dashboardWidget( $post, $callback_args ) {
		$extensionIgnore = array('info', 'wptsaf-security', 'extension-error-monitor');
		
		$extensionNetworkMonitorName = '';
		if( class_exists('wptsafExtensionNetworkMonitor') ){
			$extensionNetworkMonitorName = wptsafExtensionNetworkMonitor::getInstance()->getName();	
		}
		
		$extensions = $this->getExtensions();
		$enabledCount = 0;
		$desabledCount = 0;
		$securityCoreName = $this->getName();
		$rowsAmount = 0;
		$modulesStats = array();
		foreach ($extensions as $extension) {
			$extensionName = $extension->getName();

			if( in_array($extensionName, $extensionIgnore ) ) continue ;

			if( (bool) $extension->isEnabled() ) ++$enabledCount;
				else ++$desabledCount;

			if ($log = $extension->getLog()) {
				$rowsAmount += $log->getRowsAmount();

				$addonAmount = 0;
				if ($extensionName == $extensionNetworkMonitorName){
					$managerIp = $extension->getManagerIp();
					$addonAmount = $managerIp->getRowsAmount();
				}

				$modulesStats[] = array( 'title' => $extension->getTitle(), 'total' => ($log->getRowsAmount() + $addonAmount) );
			}

			

		}
		$allProc = 0;
		$otherData = array('proc'=> 0 , 'title'=>'Other modules', 'total' => 0);
		$tempStats = array();

		foreach ($modulesStats as $key => $value) {
			
			if($rowsAmount)
				$modulesStats[$key]['proc'] = round( $modulesStats[$key]['total'] * 100 / $rowsAmount, 1);
			else 
				$modulesStats[$key]['proc'] = 0;

			if( $modulesStats[$key]['proc'] < 1 ){
				$otherData['total'] += $modulesStats[$key]['proc'];
			} else {
				$tempStats[] = $modulesStats[$key];
				$allProc += $modulesStats[$key]['proc'];
			}
		}
		$otherData['proc'] = round(100 - $allProc, 2);
		$tempStats[] = $otherData;
		$modulesStats = $tempStats;

		$view = new wptsafView();

		$response = $view->content(
			$this->getExtensionDir() . 'template/widget.php',
			array(
				'totalIssues' 	=> $rowsAmount,
				'enabledCount' 	=> $enabledCount,
				'desabledCount' => $desabledCount,
				'modulesStats' => $modulesStats,
			)
		);

		echo $response;
	}

	public static function getInstance(){
		if (!self::$instance) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function isEnabled(){
		return true;
	}

	public function getExtensionDir(){
		return 'core/';
	}

	public function addExtension(wptsafAbstractExtension $extension){
		$this->extensions[$extension->getName()] = $extension;
	}

	public function getExtensions(){
		return $this->extensions;
	}

	public function getExtension($name){
		return isset($this->extensions[$name]) ? $this->extensions[$name] : null;
	}

	public function getExtensionByTitle($title){
		foreach ($this->extensions as $extension) {
			if ($title == $extension->getTitle() ) {
				return $extension;
			}
		}
		return null;
	}

	public static function currentUserCanManage(){
		return current_user_can(WPTSAF_ACCESS_LEVEL);
	}

	public function activatePlugin(){
		$this->activate();
		foreach ($this->getExtensions() as $extension) {
			$extension->activate();
		}
	}

	public function deactivatePlugin(){
		foreach ($this->getExtensions() as $extension) {
			$extension->deactivate();
		}
		$this->deactivate();
	}

	public static function uninstallPlugin(){
		foreach (self::getInstance()->getExtensions() as $extension) {
			$extension->uninstall();
		}
		self::getInstance()->uninstall();
	}

	public function adminPageAfterContent(){

		$scanner = $this->getExtension('malware-scanner');

		/*delete_option( 'wpsaf_hide_wizard' );*/
		$whoWizard = get_option( 'wpsaf_hide_wizard', 0 );

		if(!$whoWizard) $js = '&method=dialog&args[name]=wizardStart&args[popup]=0';
			elseif( $whoWizard==1 && !$scanner->isEnabled() ) $js = '&method=wizardStepOne';
				else $js = '&method=wizardStepTwo';

 		echo '<script>(function($){ $(document).ready(function(){ 
			wptsafDataAction.processAction( null, "action=wptsaf_security&extension=wptsaf-security'.$js.'", null, true );
		}); }(jQuery));</script>';
 	}
 	
}
