<?php
require_once dirname(__FILE__)."/helper.php";
require_once dirname(__FILE__)."/AppsbdAjaxConfirmResponse.php";
abstract class elite_licenser_woocommerce_base {
	protected static $appsbd_globalCss;
	protected static $appsbd_globalJS;
	protected static $appGlobalVar=[];
	protected $slug = "unknownslug";
	protected $pluginFile="";
	protected $pluginVersion="1.0";
	protected $pluginName="";
	protected $options=null;
	
	function __construct() {
		
		add_action( 'admin_menu', [ $this, "AdminMenu" ] );
		add_action( 'init', [ $this, "Init" ] );
		add_action( 'admin_print_scripts', [ $this, 'AdminScriptData' ], 9999 );
		$this->pluginSlugWitoutChar =strtoupper(preg_replace('/[^a-zA-Z]/','',$this->slug));
		$this->initialize();
		$this->SetOption();
		
	}
	function initialize(){
	   
    }
	function SetOption() {
		$modulename = get_class( $this );
		$this->options= get_option( $this->slug . "_o_" . $modulename, NULL );
	}
	function GetOption($key='',$default='') {
		if(empty($key)){
			return $this->options;
		}else{
			if(!empty($this->options[$key])){
				return $this->options[$key];
			}else{
				return $default;
			}
		}
	}
	function __toString() {
		return  get_class( $this );
	}
	
	function AddOption($key,$value){
		$this->options[$key]=$value;
		return $this->UpdateOption();
	}
	function UpdateOption() {
		return update_option( $this->slug . "_o_" . $this, $this->options ) || add_option( $this->slug . "_o_" . $this, $this->options );
	}
	public function AddAppGlobalVar( $key, $value ) {
		self::$appGlobalVar[ $key ] = $this->__( $value );
	}
	function CheckAdminPage() {
		$page = ! empty( $_REQUEST['page'] ) ? sanitize_text_field($_REQUEST['page']) : "";
		$page = trim( $page );
		if ( ! empty( $page ) ) {
			if ( $page == $this->slug ) {
				return true;
			}
		}
		
		return false;
		
	}
	
	/**
	 * @param $actionName
	 * @param callable $function_to_add
	 */
	function AddAjaxAction($actionName,$function_to_add){
		$actionName=$this->GetActionName($actionName);
		add_action( 'wp_ajax_' .$actionName, $function_to_add );
	}
	function GetActionUrl($actionString='',$params=[]){
		$actionName=$this->GetActionName($actionString);
		$paramStr=count($params)>0?"&".http_build_query($params):"";
		return admin_url( 'admin-ajax.php' ).'?action='.$actionName.$paramStr;
	}
	function GetActionName($actionString=''){
		if(!empty($actionString)){
			$actionString='_'.$actionString;
		}
		return hash('crc32b',$this->slug).$actionString;
	}
	function AdminScriptData() {
		?>
		<script type="text/javascript">
           var <?php echo $this->pluginSlugWitoutChar;?>="<?php echo plugins_url( "", $this->pluginFile );?>";
           if(typeof appGlobalLang == "undefined") {
               var appGlobalLang =<?php echo json_encode( self::$appGlobalVar ); ?>;
           }else{
               jQuery( document ).ready(function( $ ) {
                   appGlobalLang = $.extend( appGlobalLang, <?php echo json_encode( self::$appGlobalVar ); ?>);
               });
           }
		</script>
		<?php
	}
	function Init() {
		add_action( 'admin_enqueue_scripts', [ $this, 'JSScripts' ], 999 );
		add_action( 'admin_print_styles', [ $this, 'CSSStyles' ] );
		add_action( 'wp_print_styles', [ $this, 'ClientStyles' ], 998 );
		load_plugin_textdomain($this->slug, FALSE, basename( dirname( $this->pluginFile ) ).'/languages/');
	}
	
	function JSScripts() {
		if ( ! $this->CheckAdminPage() ) {
			return;
		}
		wp_enqueue_script( 'jquery' );
		$this->AddAdminScript( "boostrap4", "uilib/boostrap/4.3.1/js/bootstrap.bundle.min.js", true );
		$this->AddAdminScript( "apboostrap_validatior_js", "uilib/bootstrapValidation/js/bootstrapValidator4.min.js", true );
		$this->AddAdminScript( "apboostrap_magnificjs", "uilib/magnific/magnific.min.js", true );
		$this->AddAdminScript( "apboostrap_sgnofi_js", "uilib/sliding-growl-notification/js/notify.min.js", true );
		$this->AddAdminScript( "apboostrap_sweetalertjs", "uilib/sweetalert/sweetalert.min.js", true );
		$this->AddAdminScript( "apboostrap_datetimepickercss", "uilib/datetimepicker/jquery.datetimepicker.js", true );
		$this->AddAdminScript( "apboostrap_boostrap_select", "uilib/boostrap-select/js/bootstrap-select.min.js", true );
		$this->AddAdminScript( "apboostrap_ajax_boostrap_select", "uilib/boostrap-select/js/ajax-bootstrap-select.js", true );
		$this->AddAdminScript( "apd-main-js", "main.min.js", false, [ 'wp-color-picker' ] );
		
	}
	function WPAdminCheckDefaultCssScript( $src ) {
		if ( empty( $src ) || $src == 1 || preg_match( "/\/wp-admin\/|\/wp-includes\//", $src ) ) {
			return true;
		}
		
		return false;
	}
	function ClientStyles(){
		$this->AddClientStyle( "elite-woo-css", "elite-woo-license.css" );
    }
	function CSSStyles() {
		if ( ! $this->CheckAdminPage() ) {
			return;
		}
		
		$this->AddAdminStyle( 'wp-color-picker' );
		$this->AddAdminStyle( "apsbdboostrap", "uilib/boostrap/4.3.1/appsbdbootstrap.css", true );
		$this->AddAdminStyle( "font-awesome", "uilib/font-awesome/4.7.0/css/font-awesome.min.css", true );
		$this->AddAdminStyle( "apboostrap_magnificcss", "uilib/magnific/apbd-magnific-bootstrap.css", true );
		$this->AddAdminStyle( "apboostrap_validatior_css", "uilib/bootstrapValidation/css/bootstrapValidator.min.css", true );
		
		$this->AddAdminStyle( "apboostrap_sgnofi_css1", "uilib/sliding-growl-notification/css/notify.css", true );
		$this->AddAdminStyle( "apboostrap_sweetalertcss", "uilib/sweetalert/sweetalert.css", true );
		$this->AddAdminStyle( "apboostrap_datetimepickercss", "uilib/datetimepicker/jquery.datetimepicker.css", true );
		$this->AddAdminStyle( "apboostrap_boostrap_select", "uilib/boostrap-select/css/bootstrap-select-bundle.css", true );
		$this->AddAdminStyle( "bootstrap-material-css","uilib/material/material.css",true);
		$this->AddAdminStyle( "appsbdcore", "admin-core-style.css" );
		$this->AddAdminStyle( "elite-woo-css", "elite-woo-license.css" );
		
		global $wp_styles;
		
		foreach ( $wp_styles->queue as $style ) {
			if ( ! in_array( $style, static::$appsbd_globalCss ) ) {
				if ( ! $this->WPAdminCheckDefaultCssScript( $wp_styles->registered[ $style ]->src ) ) {
					wp_dequeue_style( $style );
				}
			}
		}
	}
	
	
	function AddClientStyle( $StyleId, $StyleFileName = '', $isFromRoot = false, $deps = [] ) {
		if ( $isFromRoot ) {
			$start = "/";
		} else {
			$start = "/css/";
		}
		
		if ( ! empty( $StyleFileName ) ) {
			self::RegisterAdminStyle( $StyleId, plugins_url( $start . $StyleFileName, $this->pluginFile ), $deps, $this->pluginVersion );
		} else {
			self::RegisterAdminStyle( $StyleId );
		}
		
	}
	
	function AddAdminStyle( $StyleId, $StyleFileName = '', $isFromRoot = false, $deps = [] ) {
		if ( $isFromRoot ) {
			$start = "/";
		} else {
			$start = "/css/";
		}
		
		if ( ! empty( $StyleFileName ) ) {
			self::RegisterAdminStyle( $StyleId, plugins_url( $start . $StyleFileName, $this->pluginFile ), $deps, $this->pluginVersion );
		} else {
			self::RegisterAdminStyle( $StyleId );
		}
		
	}
	
	function AddAdminScript( $ScriptId, $ScriptFileName = '', $isFromRoot = false, $deps = [] ) {
		if ( $isFromRoot ) {
			$start = "/";
		} else {
			$start = "/js/";
		}
		if ( ! empty( $ScriptFileName ) ) {
			self::RegisterAdminScript( $ScriptId, plugins_url( $start . $ScriptFileName, $this->pluginFile ), $deps, $this->pluginVersion );
		} else {
			self::RegisterAdminScript( $ScriptId, '' );
		}
	}
	function _e($string, $parameter = null, $_ = null) {
		$args = func_get_args();
		echo call_user_func_array( [ $this, "__" ], $args );
	}
	function _ee($string, $parameter = null, $_ = null) {
		$args = func_get_args();
		foreach ($args as &$arg){
			if(is_string($arg)) {
				$arg = $this->__( $arg );
			}
		}
		echo call_user_func_array( [ $this, "__" ], $args );
	}
	function ___($string, $parameter = null, $_ = null)
	{
		$args=func_get_args();
		foreach ($args as &$arg){
			if(is_string($arg)) {
				$arg = $this->__( $arg );
			}
		}
		return call_user_func_array( [ $this, "__" ], $args );
	}
	function __($string, $parameter = null, $_ = null)
	{
		$args=func_get_args();
		

		$args[0]=__($args[0],$this->slug);
		if(count($args)>1){
			$msg=call_user_func_array("sprintf",$args);
		}else{
			$msg=$args[0];
		}
		return $msg;
	}
	
	static function RegisterAdminStyle( $handle, $src = "", $deps = [], $ver = false, $in_footer = false ) {
		self::$appsbd_globalCss[] = $handle;
		if ( ! empty( $src ) ) {
			wp_register_style( $handle, $src, $deps, $ver, $in_footer );
		}
		wp_enqueue_style( $handle );
	}
	
	static function RegisterAdminScript( $handle, $src = "", $deps = [], $ver = false, $in_footer = false ) {
		self::$appsbd_globalJS[] = $handle;
		if ( ! empty( $src ) ) {
			wp_deregister_script( $handle );
			wp_register_script( $handle, $src, $deps, $ver, $in_footer );
		}
		wp_enqueue_script( $handle );
	}
	
	
	abstract function AdminMenu();
	
	abstract function OptionPage();
	
}

