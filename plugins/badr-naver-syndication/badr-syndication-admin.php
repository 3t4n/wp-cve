<?php
require_once(trailingslashit(dirname(__FILE__)) . 'badr-syndication-class.php');

class badrSyndicationAdmin extends badrSyndication{
	
	var $plugin_name = 'badr-syndication';
	var $ping_url;
	var $syndication_url;
	
	function init() {
		$this->syndication_url = $this->_baseUrl . '/?syndication_feeds=';
		add_action( 'admin_init', array( &$this, 'initAdmin' ) );
		add_action( 'admin_menu', array( &$this, 'initAdminPage') );	
	}
	
	function initAdmin() {
		//add_action( 'ns_update_category', array( &$this, 'setExCategory'), 10, 1 ); //제외카테고리설정반영
		add_action( 'wp_ajax_configCheck', array( &$this, 'adminConfigCheck'));
		//add_action( 'wp_ajax_getIndexed', array( &$this, 'getIndexed'));
		add_action( 'save_post', array( &$this, 'procSavePing'), 10, 2);
		add_action( 'trashed_post', array( &$this, 'procTrashPing'), 10, 1);
		add_action( 'untrashed_post', array( &$this, 'procTrashPing'), 10, 1);
	}
	
	function initAdminPage() {
		$suffix = add_options_page( '네이버 신디케이션', '네이버 신디케이션', 'manage_options', $this->plugin_name, array( &$this , 'dispManagementPage') );
	 	add_action( 'admin_print_scripts-' . $suffix, array( &$this , 'loadAdminScript') );
		add_meta_box( 'badr_syndication_metabox', '네이버 신디케이션', array( &$this, 'dispMetabox'),'post','side','default');
	 	add_action( 'admin_enqueue_scripts', array( &$this , 'loadMetaboxScript'));
	}

	
	/*
	 * FIXME 제외 카테고리 추가시 발행된 해당 포스트를 삭제 처리 루틴 작성
	 */
	function setExCategory( $aExCategory ){
			$aCheckForPing = array_diff( $aExCategory , $this->_aCategory );
			//if( count($aCheckForPing) > 0 ) $this->procPingCategory();
	}
	
	/**
	 * 신디케이션 문서의 정합성 체크. 설정페이지 동작확인시에만 실행
	 * @return boolean
	 */
	function checkValidate(){
		if( empty($_GET['dovalidate']) ) return false;
		if( !extension_loaded('libxml') || !extension_loaded('DOM') ) die( '996' );
		libxml_use_internal_errors(true);
		$response = wp_remote_get( $this->ping_url );
		
		$xml= new DOMDocument();
		$xml->loadXML( $response['body'] );
		//die(var_dump($xml));
		$xsd_file = trailingslashit(dirname(__FILE__)).'inc/syndi.xsd';
		if ( !$xml->schemaValidate($xsd_file) ) {
    		$this->libxml_display_errors();
		}
		return $response['body'];
	}	

	function libxml_display_errors() {
		$aErrors = libxml_get_errors();
		$message = '<br />';
		foreach ($aErrors as $error) {
			$message .= $error->message;
		}
		libxml_clear_errors();
		$this->jsonResponse(998, $message);
	}
	
	/**
	 * 문서목록을 발송할때 post meta 값이 없다면 현재시각을 저장.
	 * 설정페이지의 동작확인시에만 실행
	 */
	function checkPostMeta(){
		$sXml = $this->checkValidate();
		if(!$sXml) {
			$response = wp_remote_get( $this->ping_url );
			$sXml = $response['body'];
		} 
		$oXml = @simplexml_load_string($sXml);
		if( empty($oXml->id) || empty($oXml->title) || empty($oXml->updated) ) {
			 $this->jsonResponse(998, '');
		}
		/* entry id 에서 meta값을 추출해서 업데이트해준다 */
		foreach( $oXml->entry as $entry){
			preg_match ( '/p=([0-9]+)&s=([0-9]+)$/i', (string) $entry->id, $matches );
			update_post_meta($matches[1], '_syndication', $matches[2]);
		}
	}	

	/**
	 * 설정페이지에서 동작확인시 실행.
	 */
	function adminConfigCheck(){
		if( empty($this->_aOptions['key']) ) $this->jsonResponse(999);
		/* ping_url : page-{number}.xml */
		if( isset($_GET['ping_id']) ) {
			$this->ping_url = $this->syndication_url.$_GET['ping_id'];
			$this->checkPostMeta();
			$result = $this->ping();
			$oXml = @simplexml_load_string($result['body']);
			$this->jsonResponse( $oXml );
		} else {
			$query = new WP_Query( $this->_getPostsQuery() );
			$output = array('pages' => $query->max_num_pages);
			die(json_encode($output));
		}
	}
	
	/**
	 * admin.js ajax요청에 대하여 문서주소, 에러코드, 에러메세지를 json 포맷으로 반환한다. 
	 * 
	 * @param mixed $obj
	 * @param string $message
	 */
	function jsonResponse( $obj = '', $message = '' ) {
		if( is_int( $obj ) ) $code = $obj;
		elseif( is_object( $obj ) ) $code = (string) $obj->error_code;
		else $code = '997';
		die(json_encode(array( 'ping_url' => $this->ping_url, 'code' => $code, 'message' => $message)));
	}
	

	/**
	 * badr.kr api서버로 부터 사이트 url 등록된 색인 목록을 받아온다. 
	 * @todo 0.9
	 */
	function getIndexed(){
		if(isset($_GET['page'])) return $this->sendResetPing($_GET['page']);
		$url = 'http://api.badr.kr' . (isset($_GET['start']) ? '/?start='.$_GET['start'] : '');
		$result = wp_remote_get( $url );
		$data = json_decode($result['body']);
		$this->_procDB('emptyIndexedLog');
		$this->_procDB('indexedLog',$data->links);
		die( (string)$data->total );
	}
	
	function sendResetPing( $page ){
		$response = $this->ping( 'list-'.$page.'.xml' );
		$this->pingResponse($response['body']);
	}
	
	function procTrashPing( $post_id ){
		$this->ping_url = $this->syndication_url.'post-'.$post_id.'.xml';
		$this->ping();
	}
	
	function procSavePing( $post_id, $oPost ){
		if( wp_is_post_revision( $oPost ) ) return false;
  		if( !isset($_POST['_syndication_is_off']) || !isset($_POST['_syndication_do_off'])) return;
	  	if ( !$this->savePostMeta( $post_id, $oPost ) ) return;
	  	$this->ping_url = $this->syndication_url.'post-'.$post_id.'.xml';
		$this->ping();
	}
	
	/**
	 * 포스트메타박스의 연동설정값 저장한다. 연동할 경우 포스트메타값으로 현재시각, 안할경우 '0'을 저장
	 * 
	 * @return boolean
	 * @since 0.8
	 */
	function savePostMeta( $post_id, $oPost ) {

		$on_date = (int) $_POST['_syndication_is_off'];
		$do_off = !! (int) $_POST['_syndication_do_off'];
		if($on_date <= 0) {
			/* 연동안된상태에서 연동할 경우 현재시각 저장 */
			$input = $do_off ? '0' : date('YmdHis', current_time('timestamp'));
		} else {
			/* 연동된 상태에서 연동할경우 기존값, 연동안할 경우 마이너스 */
			$input = $do_off ? -1*$on_date : $on_date;
		}
		if(isset($input)) update_post_meta($post_id, '_syndication', $input);
		if( $on_date <= 0 && $do_off ) return false;
		return true;
	}

	function updateConfig( $input ){
		$bResult = update_option( '_syndication', $input );
		if($bResult) $this->_aOptions = $input;
		return $bResult;
	}
	
	function loadMetaboxScript( $hook ){
    	if ( !in_array($hook, array('post.php','post-new.php')) ) return;
    	$bIsNewPost = isset($_GET['post']) ? 0 : 1;
	 	wp_register_script( 'badr-syndication-script', plugins_url( 'js/badr-syndication-metabox.js', __FILE__ ), array("jquery"));
		wp_enqueue_script( 'badr-syndication-script');
		wp_register_style( 'badr-syndication-style', plugins_url( 'css/badr-syndication-metabox.css', __FILE__ ) );
		wp_enqueue_style( 'badr-syndication-style');
		wp_localize_script( 'badr-syndication-script', 'badrSyndication', array( 'sCategory' => $this->_sCategory, 'bIsNewPost' => $bIsNewPost ) );
	}

	function dispMetabox($oPost,$box) {
	    echo'
	    <input type="radio" name="_syndication_do_off" id="_syndication_do_on" value="0" class="syndication" />
	    <label for="_syndication_do_on" class="syndication-icon syndication-on">연동함<span id="syndi_notice"></span></label>
	    <br />
	    <input type="radio" name="_syndication_do_off" id="_syndication_do_off" value="1" class="syndication" />
	    <label for="_syndication_do_off" class="syndication-icon syndication-off">연동안함</label>
	    <br />
     	<input type="hidden" name="_syndication_metabox_flag" value="1" />
     	<input type="hidden" name="_syndication_is_off" id="syndication_status" value="'.get_post_meta($oPost->ID,'_syndication',true).'" />';
	}
	
	/**
	 * 설정페이지를 출력하고 옵션을 저장한다 
	 * 
	 */
	function dispManagementPage() {
		if( !isset($_GET['page']) || $_GET['page'] != $this->plugin_name ) return;
		/* 설정 저장시 업데이트 메세지를 출력하기 위한 플래그 */
		$bResult = false;
		/* 설정 저장 */
		if( isset( $_POST['submit'] ) ) {
			$aExCategory = empty($_POST['except_category']) ? array() : $_POST['except_category'];
			//do_action( 'ns_update_category ', $aExCategory );
			$this->_aOptions['except_category'] = $_POST['except_category'];
			$this->_aOptions['key'] = $_POST['syndi_key'];
			$this->_aOptions['email'] = sanitize_email( $_POST['syndi_email'] );
			$this->_aOptions['name'] = sanitize_text_field( $_POST['syndi_name'] );
			$this->_aOptions['post_type'] = !empty($this->_aOptions['post_type']) ? $this->_aOptions['post_type'] : array('post');
			$this->_aOptions['use_ex_parameter'] = isset($_POST['useExParameter']) ? '1' : '';
			$bResult = $this->updateConfig( $this->_aOptions );
		}
		/* 네이버 예티봇의 방문 시각 */
		$yeti_visited_time = get_option('_syndication_yeti');
		$aExCategory = isset($this->_aOptions['except_category']) ? explode(',',$this->_aOptions['except_category']) : '';
		if( ! class_exists( 'badr_syndication_table' ) ) {
			require_once(trailingslashit(dirname(__FILE__)) . 'badr-syndication-table.php');
		}
		$logTable = new badr_syndication_table();
		require_once(trailingslashit(dirname(__FILE__)) . 'tpl/config.php');
	}
	
	function loadAdminScript(){
		if ( !isset( $_GET['page'] ) || $_GET['page'] != $this->plugin_name ) return;
	 	wp_register_script('badrSyndicationAdminJs', plugins_url( 'js/badr-syndication-admin.js', __FILE__ ), array("jquery","jquery-ui-dialog"));
		wp_register_style( 'badrSyndicationStylesheet', plugins_url('css/style.css', __FILE__) );
		wp_enqueue_script('badrSyndicationAdminJs');
		wp_enqueue_style('badrSyndicationStylesheet');
		wp_enqueue_style('wp-jquery-ui-dialog');
		wp_enqueue_script( 'postbox' );
		wp_enqueue_script( 'dashboard' );
		//js에서 사용할 플러그인 디렉토리 변수를 naverSyndication.plugin_url에 담는다.
   		wp_localize_script( 'badrSyndicationAdminJs', 'badrSyndication', array( 'plugin_url' => plugin_dir_url( __FILE__ ), 'ajax_url' => admin_url('admin-ajax.php') ) );
	}
		
  	function ping() {
		$url = 'https://apis.naver.com/crawl/nsyndi/v2';
		$arr = array(
					'method' => 'POST',
					'headers' => array(
						"Authorization" => "Bearer ".$this->_aOptions['key'],
						"Accept-Encoding" => "gzip,deflate",
						"Accept-Language" => "ko-KR",
						"Accept" => "text/html, application/xhtml+xml, */*"
					),
					'body' => array('ping_url' => $this->ping_url)
				);
		$result = wp_remote_post( $url, $arr );
		//ChromePhp::log($this->ping_url);
		return $result;
  	}
  	
  	/**
  	 * 플러그인 활성화시 플러그인 옵션을 생성한다. autoload='no'
  	 */
  	function activatePlugin() {
  		if( !get_option('_syndication') ) add_option('_syndication',array('version' => badr_syndication_version),'','no');
  		if( !get_option('_syndication_yeti') ) add_option('_syndication_yeti','','no');

  	}
 	
  	/**
  	 * 0.7 버전에서 사용하던 옵션을 삭제한다.
  	 */
  	function deActivatePlugin() {
  		if( empty($this->_aOptions['version']) ) delete_option('_syndication');
  	}
}