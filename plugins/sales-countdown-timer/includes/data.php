<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SALES_COUNTDOWN_TIMER_Data {
	private $params, $default;

	/**
	 * SALES_COUNTDOWN_TIMER_Data constructor.
	 * Init setting
	 */
	public function __construct() {

		global $woo_ctr_settings;
		$this->default = array(
			'id'                                 => array( 'salescountdowntimer' ),
			'names'                              => array( 'Countdown timer' ),
			'message'                            => array( 'Hurry Up! Offer ends in {countdown_timer}' ),
			'active'                             => array( 1 ),
			'enable_single_product'              => array( 1 ),
			'time_type'                          => array( 'fixed' ),
			'count_style'                        => array( 2 ),
			'sale_from_date'                     => array( date( "Y-m-d", current_time( 'timestamp' ) ) ),
			'sale_to_date'                       => array( date( "Y-m-d", current_time( 'timestamp' ) + 30 * 86400 ) ),
			'sale_from_time'                     => array( '' ),
			'sale_to_time'                       => array( '' ),
			'upcoming'                           => array( 1 ),
			'upcoming_message'                   => array( 'Sale starts in {countdown_timer}' ),
			'style'                              => array( '' ),
			'position'                           => array( 'after_price' ),
			'archive_page_position'              => array( 'after_price' ),
			'progress_bar'                       => array( 1 ),
			'progress_bar_message'               => array( '{quantity_sold}/{goal} sold' ),
			'progress_bar_type'                  => array( 'increase' ),
			'progress_bar_order_status'          => array( '' ),
			'progress_bar_total'                 => array( '' ),
			'progress_bar_position'              => array( 'below_countdown' ),
			'progress_bar_style'                 => array( 1 ),
			'progress_bar_width'                 => array( '' ),
			'progress_bar_height'                => array( 20 ),
			'progress_bar_color'                 => array( '#ffb600' ),
			'progress_bar_bg_color'              => array( '#eeeeee' ),
			'progress_bar_border_radius'         => array( 20 ),
			'time_separator'                     => array( 'blank' ),
			'display_type'                       => array( 3 ),
			'datetime_unit_bg_color'             => array( '' ),
			'datetime_unit_color'                => array( '' ),
			'datetime_unit_font_size'            => array( 12),
			'datetime_value_color'               => array( '#ffb600' ),
			'datetime_value_bg_color'            => array( '' ),
			'datetime_value_font_size'           => array( 28 ),
			'countdown_timer_hide_zero'          => array( 1 ),
			'countdown_timer_padding'            => array( 2 ),
			'countdown_timer_border_radius'      => array( 0 ),
			'countdown_timer_color'              => array( '#000000' ),
			'countdown_timer_bg_color'           => array( '' ),
			'countdown_timer_border_color'       => array( '' ),
			'countdown_timer_item_border_radius' => array( 0 ),
			'countdown_timer_item_border_color'  => array( '#e2e2e2' ),
			'countdown_timer_item_height'        => array( 70 ),
			'countdown_timer_item_width'         => array( 70 ),
			'shop_page'                          => array( '' ),
			'category_page'                      => array( '' ),
			'size_on_archive_page'               => array( '75' ),
			'datetime_unit_position'             => array( 'bottom' ),
			'animation_style'                    => array( 'default' ),
			'circle_smooth_animation'            => array( 1 ),
			'stick_to_top'                       => array( 1 ),

		);
		if (!$woo_ctr_settings){
			$woo_ctr_settings = get_option( 'sales_countdown_timer_params', array() );
		}
		if (empty($woo_ctr_settings) || empty($woo_ctr_settings['names'])){
			set_transient( '_sales_countdown_timer_demo_product_init', current_time( 'timestamp' ), 180 * DAY_IN_SECONDS );
		}

		$this->params = apply_filters( 'woo_ctr_settings_args', wp_parse_args( $woo_ctr_settings, $this->default ) );
	}

	/**
	 * Get add to cart redirect
	 * @return mixed|void
	 */
	public function get_id() {
		return apply_filters( 'woo_ctr_get_id', $this->params['id'] );
	}

	public function get_names() {
		return apply_filters( 'woo_ctr_get_names', $this->params['names'] );
	}

	public function get_message() {
		return apply_filters( 'woo_ctr_get_message', $this->params['message'] );
	}

	public function get_upcoming_message() {
		return apply_filters( 'woo_ctr_get_upcoming_message', $this->params['upcoming_message'] );
	}

	public function get_time_type() {
		return apply_filters( 'woo_ctr_get_time_type', $this->params['time_type'] );
	}

	public function get_count_style() {
		return apply_filters( 'woo_ctr_get_count_style', $this->params['count_style'] );
	}

	public function get_active() {
		return apply_filters( 'woo_ctr_get_active', $this->params['active'] );
	}

	public function get_enable_single_product() {
		return apply_filters( 'woo_ctr_get_enable_single_product', $this->params['enable_single_product'] );
	}

	public function get_sale_from_date() {
		return apply_filters( 'woo_ctr_get_sale_from_date', $this->params['sale_from_date'] );
	}

	public function get_sale_to_date() {
		return apply_filters( 'woo_ctr_get_sale_to_date', $this->params['sale_to_date'] );
	}

	public function get_sale_from_time() {
		return apply_filters( 'woo_ctr_get_sale_from_time', $this->params['sale_from_time'] );
	}

	public function get_sale_to_time() {
		return apply_filters( 'woo_ctr_get_sale_to_time', $this->params['sale_to_time'] );
	}

	public function get_upcoming() {
		return apply_filters( 'woo_ctr_get_upcoming', $this->params['upcoming'] );
	}

	public function get_style() {
		return apply_filters( 'woo_ctr_get_style', $this->params['style'] );
	}

	public function get_position() {
		return apply_filters( 'woo_ctr_get_position', $this->params['position'] );
	}

	public function get_archive_page_position() {
		return apply_filters( 'woo_ctr_get_archive_page_position', $this->params['archive_page_position'] );
	}

	public function get_progress_bar() {
		return apply_filters( 'woo_ctr_get_progress_bar', $this->params['progress_bar'] );
	}

	public function get_progress_bar_message() {
		return apply_filters( 'woo_ctr_get_progress_bar_message', $this->params['progress_bar_message'] );
	}

	public function get_progress_bar_type() {
		return apply_filters( 'woo_ctr_get_progress_bar_type', $this->params['progress_bar_type'] );
	}

	public function get_progress_bar_order_status() {
		return apply_filters( 'woo_ctr_get_progress_bar_order_status', $this->params['progress_bar_order_status'] );
	}

	public function get_progress_bar_total() {
		return apply_filters( 'woo_ctr_get_progress_bar_total', $this->params['progress_bar_total'] );
	}

	public function get_progress_bar_position() {
		return apply_filters( 'woo_ctr_get_progress_bar_position', $this->params['progress_bar_position'] );
	}

	public function get_progress_bar_initial() {
		return apply_filters( 'woo_ctr_get_progress_bar_initial', $this->params['progress_bar_initial'] );
	}

	public function get_progress_bar_max() {
		return apply_filters( 'woo_ctr_get_progress_bar_max', $this->params['progress_bar_max'] );
	}

	public function get_progress_bar_style() {
		return apply_filters( 'woo_ctr_get_progress_bar_style', $this->params['progress_bar_style'] );
	}

	public function get_progress_bar_border_radius() {
		return apply_filters( 'woo_ctr_get_progress_bar_border_radius', $this->params['progress_bar_border_radius'] );
	}

	public function get_progress_bar_width() {
		return apply_filters( 'woo_ctr_get_progress_bar_width', $this->params['progress_bar_width'] );
	}

	public function get_progress_bar_height() {
		return apply_filters( 'woo_ctr_get_progress_bar_height', $this->params['progress_bar_height'] );
	}

	public function get_progress_bar_color() {
		return apply_filters( 'woo_ctr_get_progress_bar_color', $this->params['progress_bar_color'] );
	}

	public function get_progress_bar_bg_color() {
		return apply_filters( 'woo_ctr_get_progress_bar_bg_color', $this->params['progress_bar_bg_color'] );
	}

	public function get_time_separator() {
		return apply_filters( 'woo_ctr_get_time_separator', $this->params['time_separator'] );
	}

	public function get_display_type() {
		return apply_filters( 'woo_ctr_get_display_type', $this->params['display_type'] );
	}

	public function get_datetime_unit_color() {
		return apply_filters( 'woo_ctr_get_datetime_unit_color', $this->params['datetime_unit_color'] );
	}

	public function get_datetime_unit_bg_color() {
		return apply_filters( 'woo_ctr_get_datetime_unit_bg_color', $this->params['datetime_unit_bg_color'] );
	}

	public function get_datetime_unit_font_size() {
		return apply_filters( 'woo_ctr_get_datetime_unit_font_size', $this->params['datetime_unit_font_size'] );
	}

	public function get_datetime_value_color() {
		return apply_filters( 'woo_ctr_get_datetime_value_color', $this->params['datetime_value_color'] );
	}

	public function get_datetime_value_bg_color() {
		return apply_filters( 'woo_ctr_get_datetime_value_bg_color', $this->params['datetime_value_bg_color'] );
	}

	public function get_datetime_value_font_size() {
		return apply_filters( 'woo_ctr_get_datetime_value_font_size', $this->params['datetime_value_font_size'] );
	}

	public function get_countdown_timer_color() {
		return apply_filters( 'woo_ctr_get_countdown_timer_color', $this->params['countdown_timer_color'] );
	}

	public function get_countdown_timer_hide_zero() {
		return apply_filters( 'woo_ctr_countdown_timer_hide_zero', $this->params['countdown_timer_hide_zero'] );
	}

	public function get_countdown_timer_padding() {
		return apply_filters( 'woo_ctr_get_countdown_timer_padding', $this->params['countdown_timer_padding'] );
	}

	public function get_countdown_timer_border_radius() {
		return apply_filters( 'woo_ctr_get_countdown_timer_border_radius', $this->params['countdown_timer_border_radius'] );
	}

	public function get_countdown_timer_bg_color() {
		return apply_filters( 'woo_ctr_get_countdown_timer_bg_color', $this->params['countdown_timer_bg_color'] );
	}

	public function get_countdown_timer_border_color() {
		return apply_filters( 'woo_ctr_get_countdown_timer_border_color', $this->params['countdown_timer_border_color'] );
	}

	public function get_countdown_timer_item_border_color() {
		return apply_filters( 'woo_ctr_get_countdown_timer_item_border_color', $this->params['countdown_timer_item_border_color'] );
	}

	public function get_countdown_timer_item_border_radius() {
		return apply_filters( 'woo_ctr_get_countdown_timer_item_border_radius', $this->params['countdown_timer_item_border_radius'] );
	}

	public function get_countdown_timer_item_height() {
		return apply_filters( 'woo_ctr_get_countdown_timer_item_height', $this->params['countdown_timer_item_height'] );
	}

	public function get_countdown_timer_item_width() {
		return apply_filters( 'woo_ctr_get_countdown_timer_item_width', $this->params['countdown_timer_item_width'] );
	}

	public function get_category_page() {
		return apply_filters( 'woo_ctr_get_category_page', $this->params['category_page'] );
	}

	public function get_shop_page() {
		return apply_filters( 'woo_ctr_get_shop_page', $this->params['shop_page'] );
	}

	public function get_size_on_archive_page() {
		return apply_filters( 'woo_ctr_get_size_on_archive_page', $this->params['size_on_archive_page'] );
	}

	public function get_datetime_unit_position() {
		return apply_filters( 'woo_ctr_get_datetime_unit_position', $this->params['datetime_unit_position'] );
	}

	public function get_animation_style() {
		return apply_filters( 'woo_ctr_get_animation_style', $this->params['animation_style'] );
	}
	public function get_circle_smooth_animation() {
		return apply_filters( 'woo_ctr_get_circle_smooth_animation', $this->params['circle_smooth_animation'] );
	}
	public function get_stick_to_top() {
		return apply_filters( 'woo_ctr_get_stick_to_top', $this->params['stick_to_top'] );
	}

	public function get_params( $name = "" ) {
		if ( ! $name ) {
			return $this->params;
		} elseif ( isset( $this->params[ $name ] ) ) {
			return apply_filters( 'sctv_countdown_settings-' . $name, $this->params[ $name ] );
		} else {
			return false;
		}
	}

	public function get_default( $name = "" ) {
		if ( ! $name ) {
			return $this->default;
		} elseif ( isset( $this->default[ $name ] ) ) {
			return apply_filters( 'sctv_countdown_settings_default-' . $name, $this->default[ $name ] );
		} else {
			return false;
		}
	}
	public function get_current_countdown( $name = "", $i = 0 ) {
		$result =$this->get_params( $name )[ $i ] ?? $this->get_default( $name )[0] ?? false;

		return $result;
	}
}

new SALES_COUNTDOWN_TIMER_Data();