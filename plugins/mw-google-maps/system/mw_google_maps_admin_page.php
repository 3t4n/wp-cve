<?php
/**
 * Name: MW Google Maps Admin Page
 * Plugin URI: http://2inc.org/blog/category/products/wordpress_plugins/mw-google-maps/
 * Description: 管理画面クラス
 * Version: 1.1.0
 * Author: Takashi Kitajima
 * Author URI: http://2inc.org
 * Created: february 25, 2013
 * Modified: March 4, 2013
 * Modified: February 25, 2015
 * License: GPL2
 *
 * Copyright 2014 Takashi Kitajima (email : inc@2inc.org)
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
class MW_Google_Maps_Admin_Page {

	const NAME = 'mw-google-maps';
	const DOMAIN = 'mw-google-maps';
	private $level;
	protected $option;

	/**
	 * __construct
	 */
	public function __construct() {
		$this->level = 'manage_options';
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_setting' ) );

		$this->option = get_option( self::NAME );
		if ( empty( $this->option['post_types'] ) || !is_array( $this->option['post_types'] ) )
			return;

		add_action( 'admin_menu', array( $this, 'add_meta_box' ) );

		add_action( 'admin_init', array( $this, 'init' ) );
	}

	/**
	 * register_setting
	 * formから送信した値をデータベースに登録したりサニタイズする
	 * $whitelist_optionsにキーを登録する
	 */
	public function register_setting() {
		register_setting( self::NAME . '-options', self::NAME );
	}

	/**
	 * view
	 * 管理画面の表示
	 */
	public function view() {
		if ( isset( $_POST['submit'] ) ) {
			if ( ! current_user_can( $this->level ) ) die( __( 'You cannot edit options.' ) );
			$updateFlg = true;
		}
		?>
<div class="wrap">
	<?php screen_icon( 'edit-pages' ); ?>
	<h2>MW Google Maps</h2>
	<?php if ( !empty( $updateFlg ) ) : ?>
	<div class="updated">
		<p>
			<strong><?php _e( 'Updated', self::DOMAIN ); ?></strong>
		</p>
		<!-- end .updated --></div>
	<?php endif; ?>

	<form method="post" action="options.php">
		<?php
		// セキュリティ対策
		settings_fields( self::NAME . '-options' );
		// データベースから設定を取得
		$option = get_option( self::NAME );
		// 投稿タイプを取得
		$post_types = get_post_types( array( 'show_ui' => true ) );
		unset( $post_types['attachment'] );
		unset( $post_types['links'] );
		?>
		<table class="form-table">
			<tr>
				<th valign="top" style="width:40%">
					<?php _e( 'Select post types to add google maps settings.', self::DOMAIN ); ?>
				</th>
				<td>
					<?php foreach ( $post_types as $post_type ) : ?>
					<label>
						<input type="checkbox" name="<?php echo self::NAME; ?>[post_types][<?php echo $post_type; ?>]" value="<?php echo $post_type; ?>" <?php checked( @$option['post_types'][$post_type], $post_type ); ?>>
						<?php echo get_post_type_object( $post_type )->label; ?>
					</label><br />
					<?php endforeach; ?>
				</td>
			</tr>
		</table>
		<br/>
		<span class="submit" style="border : 0;">
			<input type="submit" name="submit" value="<?php _e( 'update', self::DOMAIN ); ?>" />
		</span>
	</form>
<!-- end .wrap --></div>
		<?php
	}

	/**
	 * get_post_meta
	 * post_metaを返す
	 * @return	$post_ID
	 */
	protected function get_post_meta() {
		global $post;
		$post_meta = get_post_meta( $post->ID, '_'.self::NAME, true );
		$post_meta = array_merge( array(
			'address'   => '',
			'latitude'  => '',
			'longitude' => '',
			'zoom'      => 13,
		), (array)$post_meta );
		return $post_meta;
	}

	/**
	 * get_id
	 * title属性名を返す
	 * @param	String	ID属性名
	 * @return	String	ID属性名
	 */
	protected function get_id( $key ) {
		return esc_attr( self::NAME ) . '_' . $key;
	}

	/**
	 * get_name
	 * name属性名を返す
	 * @param	String	name属性名
	 * @return	String	name属性名
	 */
	protected function get_name( $key ) {
		return esc_attr( self::NAME ) . '[' . $key . ']';
	}

	/**
	 * admin_menu
	 * 設定メニューにプラグインのサブメニューを追加する
	 */
	public function admin_menu() {
		add_options_page( 'MW Google Maps', 'MW Google Maps', $this->level, __FILE__,  array( $this, 'admin_page' ) );
	}

	/**
	 * admin_page
	 * 管理画面の表示
	 */
	public function admin_page() {
		$this->view();
	}

	/**
	 * init
	 */
	public function init() {
		add_action( 'save_post', array( $this, 'save_post' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'admin_footer-post-new.php', array( $this, 'init_google_maps' ) );
		add_action( 'admin_footer-post.php', array( $this, 'init_google_maps' ) );
	}

	/**
	 * save_post
	 * 緯度経度等を保存
	 * @param	$post_ID
	 */
	public function save_post( $post_ID ) {
		if ( ! isset( $_POST[self::NAME.'_nonce'] ) )
			return $post_ID;
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_ID;
		if ( !wp_verify_nonce( $_POST[self::NAME.'_nonce'], self::NAME ) )
			return $post_ID;
		if ( !current_user_can( 'edit_posts', $post_ID ) )
			return $post_ID;

		$post_meta = $_POST[self::NAME];
		if ( empty( $post_meta['latitude'] ) || empty( $post_meta['longitude'] ) ) {
			delete_post_meta( $post_ID, '_'.self::NAME );
		} else {
			update_post_meta( $post_ID, '_'.self::NAME, $post_meta );
		}
	}

	/**
	 * add_meta_box
	 * metaboxを追加
	 */
	public function add_meta_box() {
		$post_types = $this->option['post_types'];
		foreach ( $post_types as $post_type => $val ) {
			add_meta_box( self::NAME, 'Google Maps', array( $this, '_add_meta_box' ), $post_type );
		}
	}

	/**
	 * _add_meta_box
	 * Google Mapsを入力するメタボックスを出力
	 */
	public function _add_meta_box() {
		$post_meta = $this->get_post_meta();
		?>
		<input type="hidden" name="<?php echo esc_attr( self::NAME ); ?>_nonce" value="<?php echo wp_create_nonce( self::NAME ); ?>" />
		<div id="<?php echo $this->get_id( 'map' ); ?>" style="width:100%;height:200px;"></div>
		<input type="hidden" id="<?php echo $this->get_id( 'zoom' ); ?>" name="<?php echo $this->get_name( 'zoom' ); ?>" value="<?php echo esc_attr( $post_meta['zoom'] ); ?>" />
		<table border="0" cellpadding="0" cellspacing="5" style="width:100%">
			<tr>
				<td>
					<?php _e( 'Address', self::DOMAIN ); ?>
				</td>
				<td colspan="3">
					<input type="text" id="<?php echo $this->get_id( 'address' ); ?>" name="<?php echo $this->get_name( 'address' ); ?>" value="<?php echo esc_attr( $post_meta['address'] ); ?>" />
					<input type="button" id="<?php echo $this->get_id( 'mapbtn' ); ?>" value="<?php _e( 'Search', self::DOMAIN ); ?>" />
				</td>
			</tr>
			<tr>
				<td style="width:10%">
					<?php _e( 'Latitude', self::DOMAIN ); ?>
				</td>
				<td>
					<input type="text" id="<?php echo $this->get_id( 'latitude' ); ?>" name="<?php echo $this->get_name( 'latitude' ); ?>" value="<?php echo esc_attr( $post_meta['latitude'] ); ?>" />
				</td>
				<td style="width:10%">
					<?php _e( 'Longitude', self::DOMAIN ); ?>
				</td>
				<td>
					<input type="text" id="<?php echo $this->get_id( 'longitude' ); ?>" name="<?php echo $this->get_name( 'longitude' ); ?>" value="<?php echo esc_attr( $post_meta['longitude'] ); ?>" />
				</td>
			</tr>
		</table>
		<?php
	}

	/**
	 * admin_enqueue_scripts
	 * @param	String	ページ名
	 */
	public function admin_enqueue_scripts( $hook ) {
		if( 'post-new.php' != $hook && 'post.php' != $hook )
			return;
		if( ! in_array( get_post_type(), $this->option['post_types'] ) )
			return;
		$url = plugin_dir_url( __FILE__ );
		wp_register_script(
			'googlemaps-api',
			'http://maps.google.com/maps/api/js?sensor=false',
			array(),
			'',
			true
		);
		wp_register_script(
			'jquery.mw-google-maps',
			$url.'../js/jquery.mw-google-maps.js',
			array( 'jquery', 'googlemaps-api' ),
			'1.0',
			true
		);
		wp_enqueue_script( 'jquery.mw-google-maps' );
	}

	/**
	 * init_google_maps
	 */
	public function init_google_maps() {
		if( ! in_array( get_post_type(), $this->option['post_types'] ) )
			return;
		$post_meta = $this->get_post_meta();
		?>
		<script type="text/javascript">
		jQuery( function( $ ) {
			var gmap = $( '#<?php echo $this->get_id( "map" ); ?>' ).mw_google_maps( {
				zoom: <?php echo esc_js( $post_meta['zoom'] ); ?>
			} );
			gmap.mw_google_maps( 'addMarker', {
				latitude : <?php echo ( empty( $post_meta['latitude'] ) ) ? '35.71012566481748' : esc_js( $post_meta['latitude'] ); ?>,
				longitude: <?php echo ( empty( $post_meta['longitude'] ) ) ? '139.81149673461914' : esc_js( $post_meta['longitude'] ); ?>,
				draggable: true
			} );
			gmap.mw_google_maps( 'geocode', {
				btn      : $( '#<?php echo esc_attr( self::NAME ); ?>_mapbtn' ),
				address  : $( '#<?php echo $this->get_id( "address" ); ?>' ),
				latitude : $( '#<?php echo $this->get_id( "latitude" ); ?>' ),
				longitude: $( '#<?php echo $this->get_id( "longitude" ); ?>' ),
				zoom     : $( '#<?php echo $this->get_id( "zoom" ); ?>' )
			} );
			gmap.mw_google_maps( 'render' );
		} );
		</script>
		<?php
	}
}
?>