<?php
/**
 * Plugin Name: WP Analytics Tag Manager 
 * Plugin URI: http://niiyz.com/wordpress/plugin/wp_analytics_tag_manager.html
 * Description: WP analiytics manager can set analytics tag.
 * Version: 0.7.0
 * Author: Tetsuya Yoshida 
 * Author URI: http://niiyz.com/
 * Created : Feb 22, 2014
 * Modified: -
 * Text Domain: wp-analiytics-tag-manager
 * Domain Path: /languages/
 * License: GPL2
 *
 * Copyright 2014 Tetsuya Yoshida (email : hello@niiyz.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
include_once( plugin_dir_path( __FILE__ ) . 'system/wp_ana_tm_functions.php' );
include_once( plugin_dir_path( __FILE__ ) . 'system/wp_ana_tm_config.php' );
// debug
ini_set( 'display_errors', 1 );

$wp_analiytics_tag_manager = new wp_analiytics_tag_manager();
class wp_analiytics_tag_manager {

	protected $WP_Ana_Tag_Manager_Admin_Page;
	protected $analyticsPostList;


	/**
	 * __construct
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'init' ) );
		// 有効化した時の処理
		register_activation_hook( __FILE__, array( __CLASS__, 'activation' ) );
		// アンインストールした時の処理
		register_uninstall_hook( __FILE__, array( __CLASS__, 'uninstall' ) );
	}

	/**
	 * init
	 * ファイルの読み込み等
	 */
	public function init() {
		load_plugin_textdomain( WP_ANA_TAG_Config::DOMAIN, false, basename( dirname( __FILE__ ) ) . '/languages' );

		// 管理画面の実行
		include_once( plugin_dir_path( __FILE__ ) . 'system/wp_ana_tm_admin_page.php' );
		$this->WP_Ana_Tag_Manager_Admin_Page = new WP_Ana_Tag_Manager_Admin_Page();
		add_action( 'init', array( $this, 'register_post_type' ) );
		
		if ( is_admin() ) return;
		
		$args = array('post_type' => WP_ANA_TAG_Config::NAME, 'post_status'=>'publish');//publish
		$this->analyticsPostList = get_posts( $args );
		
		add_action( 'wp_head', array( $this, 'output_header' ) );
		add_action( 'wp_footer', array( $this, 'output_footer' ) );
	}

	/**
	 * remove_query_vars_from_post
	 * WordPressへのリクエストに含まれている、$_POSTの値を削除
	 */
	public function remove_query_vars_from_post( $query ) {
		if ( strtolower( $_SERVER['REQUEST_METHOD'] ) === 'post' && isset( $_POST['token'] ) ) {
			foreach ( $_POST as $key => $value ) {
				if ( $key == 'token' )
					continue;
				if ( isset( $query->query_vars[$key] ) && $query->query_vars[$key] === $value && !empty( $value ) ) {
					$query->query_vars[$key] = '';
				}
			}
		}
	}

	/**
	 * activation
	 * 有効化した時の処理
	 */
	public static function activation() {}

	/**
	 * uninstall
	 * アンインストールした時の処理
	 */
	public static function uninstall() {
		# DBから消去
		$param = array('post_type' => WP_ANA_TAG_Config::NAME, 'posts_per_page' => -1);
		$param['post_status'] = 'publish';
		$postList1 = get_posts($param);
		$param['post_status'] = 'inherit';
		$postList2 = get_posts($param);
		$param['post_status'] = 'trash';
		$postList3 = get_posts($param);		
		$param['post_status'] = 'auto-draft';
		$postList4 = get_posts($param);		
		$postList = array_merge($postList1, $postList2, $postList3, $postList4);				
		if ( empty( $postList ) ) return;
		foreach ( $postList as $post1 ) {
			wp_delete_post( $post1->ID, true );
		}
	}

	/**
	 * register_post_type
	 * 管理画面（カスタム投稿タイプ）の設定
	 */
	public function register_post_type() {
		$this->WP_Ana_Tag_Manager_Admin_Page->register_post_type();
	}

	/**
	 * 
	 * ヘッダー読み込み時
	 */
	public function output_header() {
		$this->main('header');
	}
	
	/**
	 * 
	 * フッター読み込み時
	 */
	public function output_footer() {
		$this->main('footer');
	}
		
	/**
	 * main
	 * 表示画面でのプラグインの処理等。
	 */
	public function main($position='header') {
		global $post, $template;

		foreach ($this->analyticsPostList as $ana1) {
			$customInfo = get_post_meta( $ana1->ID, WP_ANA_TAG_Config::NAME, true );
			# 出力場所
			if ($position == 'header' && isset($customInfo['output_position']) == true && $customInfo['output_position'] != '1') {
				continue;
			}
			if ($position == 'footer' && isset($customInfo['output_position']) == true && $customInfo['output_position'] != '2') {
				continue;
			}
			# 出力ページ
			if (isset($customInfo['output_page']) == true && $customInfo['output_page'] == '2') {
				if (isset($customInfo['page_post_id']) == true && strlen($customInfo['page_post_id']) > 0) {
					if ($post->ID != $customInfo['page_post_id']) {
						//print "debug xxx no set page";
						continue;
					}
				}
			}										
			# ステータスがOFF
			if (isset($customInfo['status']) == false || $customInfo['status'] == '1') {
				//print "debug xxx status off";
				continue;
			}			
			
			# 現在IPアドレス
			$ipaddr = $_SERVER['REMOTE_ADDR'];
			# IP除外
			if (isset($customInfo['ip_list']) == true && WP_ANA_TAG_Functions::in_list($ipaddr, $customInfo['ip_list']) == true) {
				//print "debug xxx ip";
				continue;
			}
			# 現在ホスト名 
			$hostname = gethostbyaddr($ipaddr);
			# ホスト名除外
			if (isset($customInfo['hostname_list']) == true && WP_ANA_TAG_Functions::in_list($hostname, $customInfo['hostname_list']) == true) {
				//print "debug xxx host";
				continue;
			}
			# タグ表示
			if (isset($customInfo['analytics_tag']) == true && strlen($customInfo['analytics_tag']) > 0) {
				echo $customInfo['analytics_tag'];
			}
		}
	}
}
