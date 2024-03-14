<?php
namespace Ponhiro_Blocks;

if ( ! defined( 'ABSPATH' ) ) exit;

class Data {

	/**
	 * 設定値
	 */
	protected static $settings = '';
	protected static $default_settings = '';

	/**
	 * DB名
	 */
	const DB_NAME = [
		'settings' => 'useful_blocks_settings'
	];

	/**
	 * 設定ページのスラッグ
	 */
	const PAGE_SLUG  = 'useful_blocks';

	/**
	 * settings_field() と settings_section() で使う $page
	 */
	const PAGE_NAMES = [
		// basic
		'colors'  => 'usfl_blks_colors',
		'icons'  => 'usfl_blks_iconss',
		'reset'   => 'usfl_blks_reset',
	];

	/**
	 * メニューのタブ
	 */
	public static $menu_tabs = [];

	/**
	 * 外部からインスタンス化させない
	 */
	private function __construct() {}

	/**
	 * 変数セット（翻訳関数が使用できるようにメソッド内でセット）
	 */
	public static function set_variables() {

		// $menu_tabsのセット
		self::$menu_tabs = [
			'colors' => __( 'Color set', 'useful-blocks' ),
			'icons' => __( 'Icon image', 'useful-blocks' ),
			'reset'  => __( 'Reset', 'useful-blocks' ),
		];


		// 設定のデフォルト値をセット
		self::$default_settings = [

			// イエロー
			'colset_yellow' => '#fdc44f',
			'colset_yellow_thin' => '#fef9ed',
			'colset_yellow_dark' => '#b4923a',

			// ピンク
			'colset_pink' => '#fd9392',
			'colset_pink_thin' => '#ffefef',
			'colset_pink_dark' => '#d07373',

			// 緑
			'colset_green' => '#91c13e',
			'colset_green_thin' => '#f2f8e8',
			'colset_green_dark' => '#61841f',

			// 青
			'colset_blue' => '#6fc7e1',
			'colset_blue_thin' => '#f0f9fc',
			'colset_blue_dark' => '#419eb9',


			// CVボックス
			'colset_cvbox_01_bg' => '#f5f5f5',
			'colset_cvbox_01_list' => '#3190b7',
			'colset_cvbox_01_btn' => '#91c13e',
			'colset_cvbox_01_shadow' => '#628328',
			'colset_cvbox_01_note' => '#fdc44f',

			// 比較小
			'colset_compare_01_l' => '#6fc7e1',
			'colset_compare_01_l_bg' => '#f0f9fc',
			'colset_compare_01_r' => '#ffa883',
			'colset_compare_01_r_bg' => '#fff6f2',

			// アイコンボックス
			'colset_iconbox_01' => '#6e828a',
			'colset_iconbox_01_bg' => '#fff',
			'colset_iconbox_01_icon' => '#ee8f81',
			'iconbox_img_01' => '',
			'iconbox_img_02' => '',
			'iconbox_img_03' => '',
			'iconbox_img_04' => '',

			// 棒グラフ
			'colset_bargraph_01' => '#9dd9dd',
			'colset_bargraph_01_bg' => '#fafafa',
			'colset_bar_01' => '#f8db92',
			'colset_bar_02' => '#fda9a8',
			'colset_bar_03' => '#bdda8b',
			'colset_bar_04' => '#a1c6f1',

			// 評価グラフ
			'colset_rating_01_bg' => '#fafafa',
			'colset_rating_01_text' => '#71828a',
			'colset_rating_01_label' => '#71828a',
			'colset_rating_01_point' => '#ee8f81',
		];
	}

	/**
	 * 設定値を取得してメンバー変数にセット
	 */
	public static function set_settings() {

		$settings = get_option( self::DB_NAME['settings'] ) ?: [];
		self::$settings = array_merge( self::$default_settings, $settings );
	}

	/**
	 * デフォルト値を取得
	 */
	public static function get_default_settings( $key = null ) {

		if ( null !== $key ) {
			return self::$default_settings[$key];
		}
		return self::$default_settings;

	}

	/**
	 * 設定値の取得用メソッド
	 * キーが指定されていればそれを、指定がなければ全てを返す。
	 */
	public static function get_settings( $key = null ) {
		if ( null !== $key ) {
			return self::$settings[ $key ] ?: '';
		}
		return self::$settings;
	}

}
