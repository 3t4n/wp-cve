<?php
/*
Plugin Name: 030 Ps Display Upload_path For WP3.5
Plugin URI: http://wordpress.org/extend/plugins/030-ps-display-upload-path-for-WP3.5
Description: Display Upload_path And Upload_url_path for WordPress3.5 
Author: Wang Bin (oh@prime-strategy.co.jp)
Version: 1.1
Author URI: http://www.prime-strategy.co.jp/about/staff/oh/
*/

/**
 * ps_display_upload_path_for_wp3.5
 *
 * Main Ps Display Upload_path for WP3.5 Class
 *
 * @package ps_display_upload_path_for_wp3.5
 */

class ps_display_upload_path_for_wp35{
	/*
	* plugins loaded
	*/
	function ps_display_upload_path_for_wp35( ){
		$this->__construct( );
	}

	/*
	 * コンストラクタ.
	 */
	function __construct( ) {
		$this->initialize( );
			
	}	
	/**
	* ファンクション名：_set
	* 機能概要：Class内部変数を設定
	*/
	function _set( $key , $value ){
		$this->$key = $value;
	}

	/**
	* ファンクション名：_unset
	* 機能概要：Class内部変数を開放
	*/
	function _unset( $key , $value ){
		if ( $this->_get( $key ) ){
			unset( $this->$key );	
		}
	}

	/**
	* ファンクション名：_get
	* 機能概要：Class内部変数を取得
	*/	
	function _get( $key ){
		return $this->$key;
	}

	/*
	 * initializing
	 */
	function initialize( ){
		//echo number_format(memory_get_usage());
		
		if( !defined('DS') ):
			define( 'DS', DIRECTORY_SEPARATOR );
		endif;

		/*
		 * デフォルトUPLOPADディレクトリを設定する
		 */	
		if( ! defined('DEFAULT_UPLOADS_PATH') ):
			define( 'DEFAULT_UPLOADS_PATH' , 'wp-content/uploads' );
		endif;
						
		$this->_init( );
	}	

	/**
	* ファンクション名：_init
	* 機能概要：プラグインの機能実行をスタート 
	* 作成：プライム・ストラテジー株式会社 王 濱
	* 作成：
	* 変更：
	* @param なし
	* @return なし
	*/
	function _init( ){
		
		if ( is_admin( ) ):
			add_action( 'admin_init'										, array( &$this, 'admin_init' )				);
		endif;
		//全部

	}
	/**
	* ファンクション名： admin_init
	* 機能概要： 管理のinit
	* 作成：プライム・ストラテジー株式会社 王 濱 2012/10/22
	* 変更：
	* @param なし
	* @return なし
	*/
	function admin_init( ){
		//アップロードするファイルの保存場所の設定を表示する option_(option_key)
		add_filter( 'option_upload_url_path'							, array( &$this , 'ps_upload_url_path' )  	);

		//プラグイン無効するときにDEFAULT_UPLOADS_PATHを削除にする
		register_deactivation_hook( __FILE__ 							, array( $this , 'ps_delete_default_uploads_path') );
	}

	/**
	* ファンクション名： ps_upload_url_path
	* 機能概要： upload_url_pathのデフォルトの戻り値を上書
	* 作成：プライム・ストラテジー株式会社 王 濱
	* 作成：
	* 変更：
	* @param String $upload_url_path
	* @return String $upload_url_path
	*/
	function ps_upload_url_path( $upload_url_path ){
		global $wp_version;

		//var_dump($upload_url_path);

		//WordPressのバージョン確認
		if ( ! version_compare( $wp_version, '3.5', '>=' ) ) {
			return $upload_url_path;
		}

		//マルチサイト無効にする
		if ( is_multisite( ) ){
	        return $upload_url_path;
		}

		//upload_url_path 空じゃない場合、戻る
		if ( ! $this->chk_string_empty( $upload_url_path ) ) {
		    return $upload_url_path;
		}

		$upload_path = get_option('upload_path');
		if ( ! $this->chk_string_empty( $upload_path ) && $upload_path != DEFAULT_UPLOADS_PATH ){
		    return $upload_url_path;
		}else{
			if ( $this->chk_string_empty( $upload_url_path ) ){
				add_action( 'admin_print_styles-options-media.php'			    	, array( &$this, 'add_admin_print_styles' ) );	
			}
		    return '/' . DEFAULT_UPLOADS_PATH ;
		}
	}

	/**
	* ファンクション名： ps_delete_default_uploads_path
	* 機能概要： プラグインを無効するときに、デフォルトUPLOPADディレクトリを設定されたら、空にする
	* 作成：プライム・ストラテジー株式会社 王 濱
	* 作成：
	* 変更：
	* @param なし
	* @return なし
	*/
	function ps_delete_default_uploads_path( ){
		$upload_url_path = get_option('upload_url_path');
		$upload_path = get_option('upload_path');

		if ( $upload_url_path == '/' . DEFAULT_UPLOADS_PATH && (! $upload_path  || $upload_path == DEFAULT_UPLOADS_PATH ) ){
			update_option('upload_url_path', '');
		}
	}

	/*
	* ファンクション名： add_admin_print_styles
	* 機能概要： プラグインのcssを読み込み
	* 作成：プライム・ストラテジー株式会社 王 濱
	* 作成：
	* 変更：
	* @param resource
	* @param int
	* @param string
	* @return
	*/
	function add_admin_print_styles( ){
		wp_register_style( 'prefix-style-'. strtolower(__CLASS__) , plugins_url('css/prefix-style.css', __FILE__) );
		wp_enqueue_style( 'prefix-style-' . strtolower(__CLASS__) );	
		wp_enqueue_script( 'prefix-js-' . strtolower(__CLASS__) , plugins_url('js/prefix-js.js', __FILE__) );
	}

	/**
	* ファンクション名： chk_string_empty
	* 機能概要：文字列のチェック
	* 作成：プライム・ストラテジー株式会社 王 濱
	* 変更：
	*/	
	function chk_string_empty( $string ){
		if ( ! isset( $string  )){
			return true;
		}
		if ( empty( $string )){
			return true;
		}

		if ( $string == '' ){
			return true;
		}

		if ( ! $string ){
			return true;
		}

		return false;
	}

	/**
	* ファンクション名： chk_array_empty
	* 機能概要：配列・オブジェクトの空チェック
	* 作成：プライム・ストラテジー株式会社 王 濱
	* 変更：
	*/	
	function chk_array_empty( $array ){

		if ( ! isset( $array )){
			return true;
		}

		if ( ! is_array( $array ) && ! is_object( $array )){
			return true;
		}

		if ( ! $array ){
			return true;			
		}
		
		foreach( $array as $val ){
			return false;
		}

		return true;
	}

    /** 
     * destruct
     *
     * @author プライム・ストラテジー株式会社 王 濱
     * @date 2012.11.27
     *
     * @param void
     * @return null
     */
    function __destruct() {
    	//number_format(memory_get_usage());
	}   
	
}//class end

$ps_display_upload_path_for_wp35 = new ps_display_upload_path_for_wp35( );

?>
