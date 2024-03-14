<?php
/**
 * Plugin Name: WP Analytics Tag Manager 
 * Plugin URI: http://niiyz.com/wordpress/plugin/wp_analytics_tag_manager.html
 * Description: 管理画面クラス
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
class WP_Ana_Tag_Manager_Admin_Page {

	private $postdata;
	private $inputname;

	/**
	 * __construct
	 */
	public function __construct() {
		$this->inputname = esc_attr(WP_ANA_TAG_Config::NAME);
		add_action( 'admin_print_styles', array( $this, 'admin_style' ) );
		add_action( 'admin_print_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'admin_head', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save_post' ) );

	}

	/**
	 * current_screen
	 * 寄付リンクを表示
	 */
	public function current_screen( $screen ) {
		if ( $screen->id === 'edit-' . WP_ANA_TAG_Config::NAME )
			add_filter( 'views_' . $screen->id, array( $this, 'display_donate_link' ) );
	}
	public function display_donate_link( $views ) {
		$donation = array( 'donation' => '<div class="donation"><p>' . __( 'Your contribution is needed for making this plugin better.', WP_ANA_TAG_Config::DOMAIN ) . ' <a href="http://www.amazon.co.jp/registry/wishlist/39ANKRNSTNW40" class="button">' . __( 'Donate', WP_ANA_TAG_Config::DOMAIN ) . '</a></p></div>' );
		$views = array_merge( $donation, $views );
		return $views;
	}

	/**
	 * get_post_data
	 * フォームの設定データを返す
	 */
	protected function get_post_data( $key ) {
		global $post;
		if ( isset( $this->postdata[$key] ) ) {
			return $this->postdata[$key];
		}
		return "";
	}

	/**
	 * register_post_type
	 */
	public function register_post_type() {
		register_post_type( WP_ANA_TAG_Config::NAME, array(
			'label' => 'WP Analytics Tag Manager',
			'labels' => array(
				'name' => 'WP Analytics Tag Manager',
				'singular_name' => 'WP Analytics Tag Manager',
				'add_new_item' => __( 'Add New Tag Setting', WP_ANA_TAG_Config::DOMAIN ),
				'edit_item' => __( 'Edit Tag Setting', WP_ANA_TAG_Config::DOMAIN ),
				'new_item' => __( 'New Tag Setting', WP_ANA_TAG_Config::DOMAIN ),
				'view_item' => __( 'View Tag Setting', WP_ANA_TAG_Config::DOMAIN ),
				'search_items' => __( 'Search Tag Setting', WP_ANA_TAG_Config::DOMAIN ),
				'not_found' => __( 'No Tag Setting found', WP_ANA_TAG_Config::DOMAIN ),
				'not_found_in_trash' => __( 'No Tag Setting found in Trash', WP_ANA_TAG_Config::DOMAIN ),
			),
			'capability_type' => 'page',
			'public'  => false,
			'show_ui' => true,
		) );
	}

	/**
	 * add_meta_box
	 */
	public function add_meta_box() {
		$post_type = get_post_type();
		if (WP_ANA_TAG_Config::NAME == $post_type) {
			global $post;
			// 設定データ取得
			$this->postdata = get_post_meta( $post->ID, WP_ANA_TAG_Config::NAME, true );
			// 出力ページ
			add_meta_box(
				WP_ANA_TAG_Config::NAME . '_output_page_metabox',
				__( 'Output Page', WP_ANA_TAG_Config::DOMAIN ),
				array( $this, 'add_output_page' ),
				WP_ANA_TAG_Config::NAME,
				'normal', 'high'
			);				
			// 出力位置
			add_meta_box(
				WP_ANA_TAG_Config::NAME . '_output_position_metabox',
				__( 'Output Position', WP_ANA_TAG_Config::DOMAIN ),
				array( $this, 'add_output_position' ),
				WP_ANA_TAG_Config::NAME,
				'normal', 'high'
			);				
			// 解析タグ
			add_meta_box(
				WP_ANA_TAG_Config::NAME . '_analytics_tag_metabox',
				__( 'Analytics Tag', WP_ANA_TAG_Config::DOMAIN ),
				array( $this, 'add_analytics_tag' ),
				WP_ANA_TAG_Config::NAME,
				'normal', 'high'
			);			
			// 除外ホスト名
			add_meta_box(
				WP_ANA_TAG_Config::NAME . '_hostname_list_metabox',
				__( 'Exclusion host name list', WP_ANA_TAG_Config::DOMAIN ),
				array( $this, 'add_hostname_list' ),
				WP_ANA_TAG_Config::NAME,
				'normal', 'high'
			);
			// 除外IPアドレス
			add_meta_box(
				WP_ANA_TAG_Config::NAME . '_ip_list_metabox',
				__( 'Exclude IP address list', WP_ANA_TAG_Config::DOMAIN ),
				array( $this, 'add_ip_list' ),
				WP_ANA_TAG_Config::NAME,
				'normal', 'high'
			);
			// ON OFF
			add_meta_box(
				WP_ANA_TAG_Config::NAME . '_status_metabox',
				__( 'Status', WP_ANA_TAG_Config::DOMAIN ),
				array( $this, 'add_status' ),
				WP_ANA_TAG_Config::NAME,
				'side', 'high'
			);
		}
	}
	
	/**
	 * add_output_page
	 * 出力ページ
	 */
	public function add_output_page() {
		//global $post;
		$content = esc_html($this->get_post_data('output_page'));
		
		if ($content == '2') {
			$radio1 = '';
			$radio2 = 'checked';
		} else {
			$radio1 = 'checked';
			$radio2 = '';		
		}						
		$html = "";
		$html .= "<div>";
		$str = __("all page", WP_ANA_TAG_Config::DOMAIN);
		$html .= "<input type='radio' name='$this->inputname[output_page]' value='1' id='output_page1' $radio1> <label for='output_page1'>$str</label>&nbsp;&nbsp;";
		$str = __("only one PostType page", WP_ANA_TAG_Config::DOMAIN);
		$html .= "<input type='radio' name='$this->inputname[output_page]' value='2' id='output_page2' $radio2> <label for='output_page2'>$str</label>";
		
		# 固定ページ情報
		$args = array('post_type' => 'page', 'post_status'=>'publish');
		$pageList = get_posts( $args );
		$content = esc_html($this->get_post_data('page_post_id'));

		if (count($pageList) > 0) {
			$html .= "<select id='wp_ana_tag_page_post_id' name='$this->inputname[page_post_id]'>";
			foreach ($pageList as $page1) {
				$post_id = $page1->ID;
				$selected = '';
				if ($content == $post_id) {
					$selected = 'selected';
				}
				$html .= "<option value='$post_id' $selected>".htmlspecialchars($page1->post_title, ENT_QUOTES, 'UTF-8');
			}
			$html .= "</select>";
		} else {
			$str = __("PostType 'page' has not been created.", WP_ANA_TAG_Config::DOMAIN );
			$html .= "$str<br>";
		}		
		
		$html .= "</div>";
		echo $html;
	}
			
	/**
	 * add_output_position
	 * 出力位置
	 */
	public function add_output_position() {
		//global $post;
		$content = esc_html($this->get_post_data('output_position'));
		
		if ($content == '2') {
			$radio1 = '';
			$radio2 = 'checked';
		} else {
			$radio1 = 'checked';
			$radio2 = '';		
		}						
		$html = "";
		$html .= "<div>";
		$str = __("header", WP_ANA_TAG_Config::DOMAIN);
		$html .= "<input type='radio' name='$this->inputname[output_position]' value='1' id='output_position1' $radio1> <label for='output_position1'>$str</label>&nbsp;&nbsp;";
		$str = __("footer", WP_ANA_TAG_Config::DOMAIN);
		$html .= "<input type='radio' name='$this->inputname[output_position]' value='2' id='output_position2' $radio2> <label for='output_position2'>$str</label>&nbsp;&nbsp;";
		$html .= "</div>";
		echo $html;
	}	
		
	/**
	 * add_analytics_tag
	 * 解析タグ表示
	 */
	public function add_analytics_tag() {
		//global $post;
		$content = esc_html($this->get_post_data('analytics_tag'));
 		$html = "";
		$html .= "<h3>"._e("Please be pasted directly embed tag, such as Google Analytics. ", WP_ANA_TAG_Config::DOMAIN )."</h3>";
		$html .= "<textarea name='$this->inputname[analytics_tag]' id='analyticsTagTextArea' rows='8' style='width:100%;'>$content</textarea>";
		
		# 認証
		$nonce_value = wp_create_nonce( WP_ANA_TAG_Config::NAME );
		$html .= "<input type='hidden' name='{$this->inputname}_nonce' value='$nonce_value' />";
		echo $html;
	}

	/**
	 * add_hostname_list
	 * 除外ホスト名リスト表示
	 */
	public function add_hostname_list() {
		//global $post;
		# 保存済みホスト名リスト
		$content = esc_html($this->get_post_data('hostname_list'));
		
		# 現在ホスト名
		$ipaddr = $_SERVER['REMOTE_ADDR'];
 		$hostname = gethostbyaddr($ipaddr);
 			
 		$html = "";
 		$title = __("Host name that you are currently connected", WP_ANA_TAG_Config::DOMAIN );
		$html .= "$title:&nbsp;";
		$html .= "<span id='currentHost' style='color:blue;'>$hostname</span>&nbsp;";
		if (WP_ANA_TAG_Functions::in_list($content, $hostname) == true) {
			$url = plugin_dir_url( __FILE__ );
			$str = __("Already added", WP_ANA_TAG_Config::DOMAIN );
			$html .= "<img src='$url../images/chk.gif'><span style='color:green;'>$str</span>&nbsp;";
		} else {
			$str = __("Add", WP_ANA_TAG_Config::DOMAIN );
			$html .= "<a href='javascript:void(0);' id='hostBtn' class='button'>$str</a>";
		}
		$html .= "<textarea name='$this->inputname[hostname_list]' id='hostTextArea' rows='4' style='width:100%;'>$content</textarea>";
		echo $html;
	}
	
	/**
	 * add_ip_list
	 * 除外IPアドレスリスト表示
	 */
	public function add_ip_list() {
		//global $post;
		# 保存済みIPアドレスリスト
		$content = esc_html($this->get_post_data('ip_list'));
		
		# 現在IPアドレス
		$ipaddr = $_SERVER['REMOTE_ADDR'];
 			
 		$html = "";
 		$title = __("IP address that you are currently connected", WP_ANA_TAG_Config::DOMAIN );
		$html .= "$title:&nbsp;";
		$html .= "<span id='currentIP' style='color:blue;'>$ipaddr</span>&nbsp;";
		if (WP_ANA_TAG_Functions::in_list($content, $ipaddr) == true) {
			$url = plugin_dir_url( __FILE__ );
			$str = __("Already added", WP_ANA_TAG_Config::DOMAIN );
			$html .= "<img src='$url../images/chk.gif'><span style='color:green;'>$str</span>";
		} else {
			$str = __("Add", WP_ANA_TAG_Config::DOMAIN );
			$html .= "<a href='javascript:void(0);' id='ipBtn' class='button'>$str</a>";
		}
		$html .= "<textarea name='$this->inputname[ip_list]' id='ipTextArea' rows='4' style='width:100%;'>$content</textarea>";
		echo $html;
	}
	
	/**
	 * add_status
	 * ステータス
	 */
	public function add_status() {
		//global $post;
		$content = esc_html($this->get_post_data('status'));
		if ($content == '1') {
			$checked0 = '';
			$checked1 = 'checked';
		} else {
			$checked0 = 'checked';
			$checked1 = '';
		}
		
		$html = "";
		$html .= "<div>";
		$str = __("ON", WP_ANA_TAG_Config::DOMAIN );
		$html .= "<input type='radio' name='$this->inputname[status]' value='0' $checked0 id='status_on'><label for='status_on'>$str</label>&nbsp;&nbsp;";
		$str = __("OFF", WP_ANA_TAG_Config::DOMAIN );		
		$html .= "<input type='radio' name='$this->inputname[status]' value='1' $checked1 id='status_off'><label for='status_off'>$str</label>";
		$html .= "</div>";
		echo $html;
	}
	

	
	/**
	 * admin_style
	 * CSS適用
	 */
	public function admin_style() {
		if ( WP_ANA_TAG_Config::NAME == get_post_type() ) {
			$url = plugin_dir_url( __FILE__ );
			wp_register_style( WP_ANA_TAG_Config::DOMAIN . '-admin', $url . '../css/admin.css' );
			wp_enqueue_style( WP_ANA_TAG_Config::DOMAIN . '-admin' );
		}
	}
	
	/**
	 * admin_scripts
	 * JavaScript適用
	 */
	public function admin_scripts() {
		if ( WP_ANA_TAG_Config::NAME == get_post_type() ) {
			$url = plugin_dir_url( __FILE__ );
			wp_register_script( WP_ANA_TAG_Config::DOMAIN . '-admin', $url . '../js/admin.js' );
			wp_enqueue_script( WP_ANA_TAG_Config::DOMAIN . '-admin' );
		}
	}

	/**
	 * save_post
	 * @param	$post_ID
	 */
	public function save_post( $post_ID ) {
		if ( !( isset( $_POST['post_type'] ) && $_POST['post_type'] === WP_ANA_TAG_Config::NAME ) )
			return $post_ID;
		if ( !isset( $_POST[WP_ANA_TAG_Config::NAME . '_nonce'] ) )
			return $post_ID;
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_ID;
		if ( !wp_verify_nonce( $_POST[WP_ANA_TAG_Config::NAME . '_nonce'], WP_ANA_TAG_Config::NAME ) )
			return $post_ID;
		if ( !current_user_can( 'edit_pages' ) )
			return $post_ID;
					
		$data = $_POST[WP_ANA_TAG_Config::NAME];
		update_post_meta( $post_ID, WP_ANA_TAG_Config::NAME, $data, $this->postdata );
	
	}

}
?>