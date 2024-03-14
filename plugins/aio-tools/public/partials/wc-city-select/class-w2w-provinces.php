<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The class to handle Vietnam Provinces
 *
 * @author   htdat
 * @since    1.0
 *
 */
class W2W_Provinces {

	/**
	 * Constructor: Add filters
	 */
	public function __construct() {
		add_filter( 'woocommerce_states', array( $this, 'add_provinces' ) );
		add_filter( 'woocommerce_get_country_locale', array( $this, 'edit_vn_locale' ) );
		add_filter( 'woocommerce_localisation_address_formats', array( $this, 'edit_vn_address_formats' ) );

		// Enqueue province scripts
		//add_action( 'wp_enqueue_scripts', array( $this, 'load_provinces_scripts' ) );
	}

	/**
	 * Change the address format of Vietnam, add {state} (or "Province" in Vietnam)
	 *
	 * @param $array
	 *
	 * @return array
	 */
	public function edit_vn_address_formats( $array ) {

		$array['VN'] = "{name}\n{company}\n{address_1}\n{city}\n{state}\n{country}";

		return $array;

	}

	/**
	 * Change the way displaying address fields in the checkout page when selecting Vietnam
	 *
	 * @param $array
	 *
	 * @return array
	 */
	public function edit_vn_locale( $array ) {
		$array['VN']['state']['label']    = __( 'Town / City', 'woocommerce' );
		$array['VN']['state']['required'] = true;

		$array['VN']['city']['label']      = __( 'Town / District', 'woocommerce' );
		$array['VN']['postcode']['hidden'] = true;

		return $array;
	}


	/**
	 * Add 63 provinces of Vietnam
	 *
	 * @param $states
	 *
	 * @return array
	 */
	public function add_provinces( $states ) {
		/**
		 * @source: https://vi.wikipedia.org/wiki/Tỉnh_thành_Việt_Nam and https://en.wikipedia.org/wiki/Provinces_of_Vietnam
		 */
		$states['VN'] = array(
			'AN-GIANG'        => __( 'An Giang', 'woocommerce' ),
			'BA-RIA-VUNG-TAU' => __( 'Bà Rịa - Vũng Tàu', 'woocommerce' ),
			'BAC-LIEU'        => __( 'Bạc Liêu', 'woocommerce' ),
			'BAC-KAN'         => __( 'Bắc Kạn', 'woocommerce' ),
			'BAC-GIANG'       => __( 'Bắc Giang', 'woocommerce' ),
			'BAC-NINH'        => __( 'Bắc Ninh', 'woocommerce' ),
			'BEN-TRE'         => __( 'Bến Tre', 'woocommerce' ),
			'BINH-DUONG'      => __( 'Bình Dương', 'woocommerce' ),
			'BINH-DINH'       => __( 'Bình Định', 'woocommerce' ),
			'BINH-PHUOC'      => __( 'Bình Phước', 'woocommerce' ),
			'BINH-THUAN'      => __( 'Bình Thuận', 'woocommerce' ),
			'CA-MAU'          => __( 'Cà Mau', 'woocommerce' ),
			'CAO-BANG'        => __( 'Cao Bằng', 'woocommerce' ),
			'CAN-THO'         => __( 'Cần Thơ', 'woocommerce' ),
			'DA-NANG'         => __( 'Đà Nẵng', 'woocommerce' ),
			'DAK-LAK'         => __( 'Đắk Lắk', 'woocommerce' ),
			'DAK-NONG'        => __( 'Đắk Nông', 'woocommerce' ),
			'DONG-NAI'        => __( 'Đồng Nai', 'woocommerce' ),
			'DONG-THAP'       => __( 'Đồng Tháp', 'woocommerce' ),
			'DIEN-BIEN'       => __( 'Điện Biên', 'woocommerce' ),
			'GIA-LAI'         => __( 'Gia Lai', 'woocommerce' ),
			'HA-GIANG'        => __( 'Hà Giang', 'woocommerce' ),
			'HA-NAM'          => __( 'Hà Nam', 'woocommerce' ),
			'HA-NOI'          => __( 'Hà Nội', 'woocommerce' ),
			'HA-TINH'         => __( 'Hà Tĩnh', 'woocommerce' ),
			'HAI-DUONG'       => __( 'Hải Dương', 'woocommerce' ),
			'HAI-PHONG'       => __( 'Hải Phòng', 'woocommerce' ),
			'HOA-BINH'        => __( 'Hòa Bình', 'woocommerce' ),
			'HAU-GIANG'       => __( 'Hậu Giang', 'woocommerce' ),
			'HUNG-YEN'        => __( 'Hưng Yên', 'woocommerce' ),
			'HO-CHI-MINH'     => __( 'Hồ Chí Minh', 'woocommerce' ),
			'KHANH-HOA'       => __( 'Khánh Hòa', 'woocommerce' ),
			'KIEN-GIANG'      => __( 'Kiên Giang', 'woocommerce' ),
			'KON-TUM'         => __( 'Kon Tum', 'woocommerce' ),
			'LAI-CHAU'        => __( 'Lai Châu', 'woocommerce' ),
			'LAO-CAI'         => __( 'Lào Cai', 'woocommerce' ),
			'LANG-SON'        => __( 'Lạng Sơn', 'woocommerce' ),
			'LAM-DONG'        => __( 'Lâm Đồng', 'woocommerce' ),
			'LONG-AN'         => __( 'Long An', 'woocommerce' ),
			'NAM-DINH'        => __( 'Nam Định', 'woocommerce' ),
			'NGHE-AN'         => __( 'Nghê An', 'woocommerce' ),
			'NINH-BINH'       => __( 'Ninh Bình', 'woocommerce' ),
			'NINH-THUAN'      => __( 'Ninh Thuận', 'woocommerce' ),
			'PHU-THO'         => __( 'Phú Thọ', 'woocommerce' ),
			'PHU-YEN'         => __( 'Phú Yên', 'woocommerce' ),
			'QUANG-BINH'      => __( 'Quảng Bình', 'woocommerce' ),
			'QUANG-NAM'       => __( 'Quảng Nam', 'woocommerce' ),
			'QUANG-NGAI'      => __( 'Quảng Ngãi', 'woocommerce' ),
			'QUANG-NINH'      => __( 'Quảng Ninh', 'woocommerce' ),
			'QUANG-TRI'       => __( 'Quảng Trị', 'woocommerce' ),
			'SOC-TRANG'       => __( 'Sóc Trăng', 'woocommerce' ),
			'SON-LA'          => __( 'Sơn La', 'woocommerce' ),
			'TAY-NINH'        => __( 'Tây Ninh', 'woocommerce' ),
			'THAI-BINH'       => __( 'Thái Bình', 'woocommerce' ),
			'THAI-NGUYEN'     => __( 'Thái Nguyên', 'woocommerce' ),
			'THANH-HOA'       => __( 'Thanh Hóa', 'woocommerce' ),
			'THUA-THIEN-HUE'  => __( 'Thừa Thiên - Huế', 'woocommerce' ),
			'TIEN-GIANG'      => __( 'Tiền Giang', 'woocommerce' ),
			'TRA-VINH'        => __( 'Trà Vinh', 'woocommerce' ),
			'TUYEN-QUANG'     => __( 'Tuyên Quang', 'woocommerce' ),
			'VINH-LONG'       => __( 'Vĩnh Long', 'woocommerce' ),
			'VINH-PHUC'       => __( 'Vĩnh Phúc', 'woocommerce' ),
			'YEN-BAI'         => __( 'Yên Bái', 'woocommerce' ),
		);

		return $states;

	}

	/**
	 * Enqueue provinces scripts
	 *
	 * Arrange the address field orders to the Vietnam standard in the checkout page: Country - Province - District - Address
	 * @author    Longkt
	 * @since    1.4
	 */
	public function load_provinces_scripts() {
		// Enqueue province style
		wp_enqueue_style( 'woo-viet-provinces-style', WOO_VIET_URL . 'assets/provinces.css' );

		// Enqueue province script
		wp_enqueue_script( 'woo-viet-provinces-script', WOO_VIET_URL . 'assets/provinces.js', array( 'jquery' ), '1.0', true );
	}
}
