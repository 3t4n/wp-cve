<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Icon_Element_Icons_Integration' ) ) {

	class Icon_Element_Icons_Integration {

		private static $instance = null;

		public function __construct() { 
			add_filter( 'elementor/icons_manager/additional_tabs', array( $this, 'add_material_icons_tabs' ) );
		}

		public function add_material_icons_tabs( $tabs = array() ) {

			if ( get_option('icon-elementie-captain') ){
				$tabs['captain'] = array(
					'name'          => 'captain',
					'label'         => esc_html__( 'Captain', 'icon-element' ),
					'labelIcon'     => 'xlcaptain-100',
					'prefix'        => 'xlcaptain-',
					'displayPrefix' => 'xlcpt',
					'url'           => ICON_ELEM_URL . 'assets/captain/captain.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/captain/fonts/captain.json',
					'ver'           => '3.0.1',
				);
			}

			if ( get_option('icon-elementie-elementor') ){
				$tabs['elementor'] = array(
					'name'          => 'elementor',
					'label'         => esc_html__( 'Elementor', 'icon-element' ),
					'labelIcon'     => 'eicon-elementor-circle',
					'prefix'        => 'eicon-',
					'displayPrefix' => 'eicon',
					'url'           => ICON_ELEM_URL . 'assets/elementor/elementor.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/elementor/fonts/elementor.json',
					'ver'           => '3.0.1',
				);
			}

			if ( get_option('icon-elementie-feather') ){
				$tabs['feather'] = array(
					'name'          => 'feather',
					'label'         => esc_html__( 'Feather', 'icon-element' ),
					'labelIcon'     => 'feather feather-feather',
					'prefix'        => 'feather-',
					'displayPrefix' => 'feather',
					'url'           => ICON_ELEM_URL . 'assets/feather/feather.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/feather/fonts/feather.json',
					'ver'           => '3.0.1',
				);
			}

			if ( get_option('icon-elementie-elusive') ){
				$tabs['elusive'] = array(
					'name'          => 'elusive',
					'label'         => esc_html__( 'Elusive', 'icon-element' ),
					'labelIcon'     => 'el-icon-wrench',
					'prefix'        => 'el-icon-',
					'displayPrefix' => 'elusive',
					'url'           => ICON_ELEM_URL . 'assets/elusive/elusive.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/elusive/fonts/elusive.json',
					'ver'           => '3.0.1',
				);
			}

			if ( get_option('icon-elementie-obicon') ){
				$tabs['obicon'] = array(
					'name'          => 'obicon',
					'label'         => esc_html__( 'Obicon', 'icon-element' ),
					'labelIcon'     => 'obicon-socket-square',
					'prefix'        => 'obicon-',
					'displayPrefix' => 'obicon',
					'url'           => ICON_ELEM_URL . 'assets/obicon/obicon.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/obicon/fonts/obicon.json',
					'ver'           => '3.0.1',
				);
			}

			if ( get_option('icon-elementie-webicon') ){
				$tabs['webicon'] = array(
					'name'          => 'webicon',
					'label'         => esc_html__( 'Web icon', 'icon-element' ),
					'labelIcon'     => 'wb-book',
					'prefix'        => 'wb-',
					'displayPrefix' => 'wb',
					'url'           => ICON_ELEM_URL . 'assets/webicons/webicons.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/webicons/fonts/webicons.json',
					'ver'           => '3.0.1',
				);
			}

			if ( get_option('icon-elementie-vscode') ){
				$tabs['vscode'] = array(
					'name'          => 'vscode',
					'label'         => esc_html__( 'Vscode', 'icon-element' ),
					'labelIcon'     => 'vscode-debug-rerun',
					'prefix'        => 'vscode-',
					'displayPrefix' => 'vscode',
					'url'           => ICON_ELEM_URL . 'assets/vscode/vscode.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/vscode/fonts/vscode.json',
					'ver'           => '3.0.1',
				);
			}

			if ( get_option('icon-elementie-ionicons') ){

				$tabs['ionicons'] = array(
					'name'          => 'ionicons',
					'label'         => esc_html__( 'Ionicons', 'icon-element' ),
					'labelIcon'     => 'ion-ios-appstore',
					'prefix'        => 'ion-',
					'displayPrefix' => 'xlio',
					'url'           => ICON_ELEM_URL . 'assets/ionicons/css/ionicons.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/ionicons/fonts/ionicons.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-material-design') ){

				$tabs['material-design'] = array(
					'name'          => 'material-design',
					'label'         => esc_html__( 'Material Design Icons', 'icon-element' ),
					'labelIcon'     => 'fab fa-google',
					'prefix'        => 'md-',
					'displayPrefix' => 'material-icons',
					'url'           => ICON_ELEM_URL . 'assets/material-icons/css/material-icons.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/material-icons/fonts/material-icons.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-metrize') ){

				$tabs['metrize'] = array(
					'name'          => 'metrize',
					'label'         => esc_html__( 'Metrize', 'icon-element' ),
					'labelIcon'     => 'metriz-yen',
					'prefix'        => 'metriz-',
					'displayPrefix' => 'xlmetriz',
					'url'           => ICON_ELEM_URL . 'assets/metrize/metrize.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/metrize/fonts/metrize.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-simpline') ){

				$tabs['simpline'] = array(
					'name'          => 'simpline',
					'label'         => esc_html__( 'Simple Line', 'icon-element' ),
					'labelIcon'     => 'simpline-user',
					'prefix'        => 'simpline-',
					'displayPrefix' => 'xlsmpli',
					'url'           => ICON_ELEM_URL . 'assets/simple-line-icons/css/simple-line-icons.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/simple-line-icons/fonts/simple-line-icons.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-wppagebuilder') ){

				$tabs['wppagebuilder'] = array(
					'name'          => 'wppagebuilder',
					'label'         => esc_html__( 'Wp pagebuilder', 'icon-element' ),
					'labelIcon'     => 'wppb-font-balance',
					'prefix'        => 'wppb-font-',
					'displayPrefix' => 'xlwpf',
					'url'           => ICON_ELEM_URL . 'assets/wppagebuilder/wppagebuilder.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/wppagebuilder/fonts/wppagebuilder.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-wppagebuilder') ){

				$tabs['wppagebuilder'] = array(
					'name'          => 'wppagebuilder',
					'label'         => esc_html__( 'Wp pagebuilder', 'icon-element' ),
					'labelIcon'     => 'wppb-font-balance',
					'prefix'        => 'wppb-font-',
					'displayPrefix' => 'xlwpf',
					'url'           => ICON_ELEM_URL . 'assets/wppagebuilder/wppagebuilder.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/wppagebuilder/fonts/wppagebuilder.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-iconsaxbold') ){

				$tabs['iconsaxbold'] = array(
					'name'          => 'iconsaxbold',
					'label'         => esc_html__( 'Iconsax Bold', 'icon-element' ),
					'labelIcon'     => 'isaxbold-wind',
					'prefix'        => 'isaxbold-',
					'displayPrefix' => 'xlwpf',
					'url'           => ICON_ELEM_URL . 'assets/iconsax-bold/iconsax-bold.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/iconsax-bold/fonts/iconsax-bold.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-tutor') ){

				$tabs['tutor'] = array(
					'name'          => 'tutor',
					'label'         => esc_html__( 'Tutor', 'icon-element' ),
					'labelIcon'     => 'tutor-icon-ban',
					'prefix'        => 'tutor-icon-',
					'displayPrefix' => 'xlwpf',
					'url'           => ICON_ELEM_URL . 'assets/tutor/tutor.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/tutor/fonts/tutor.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-uniconsolid') ){

				$tabs['uniconsolid'] = array(
					'name'          => 'uniconsolid',
					'label'         => esc_html__( 'Unicon solid', 'icon-element' ),
					'labelIcon'     => 'unisolid-airplay',
					'prefix'        => 'unisolid-',
					'url'           => ICON_ELEM_URL . 'assets/uniconsolid/uniconsolid.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/uniconsolid/fonts/uniconsolid.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-happyicon') ){

				$tabs['happyicon'] = array(
					'name'          => 'happyicon',
					'label'         => esc_html__( 'Happy icon', 'icon-element' ),
					'labelIcon'     => 'hm-3d-rotate',
					'prefix'        => 'hm-',
					'url'           => ICON_ELEM_URL . 'assets/happyicon/happyicon.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/happyicon/fonts/happyicon.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-woocommerce') ){

				$tabs['woocommerce'] = array(
					'name'          => 'woocommerce',
					'label'         => esc_html__( 'Woocommerce', 'icon-element' ),
					'labelIcon'     => 'wcicon-woo',
					'prefix'        => 'wcicon-',
					'url'           => ICON_ELEM_URL . 'assets/woocommerce/woocommerce.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/woocommerce/fonts/woocommerce.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-detheme') ){

				$tabs['detheme'] = array(
					'name'          => 'detheme',
					'label'         => esc_html__( 'DeTheme', 'icon-element' ),
					'labelIcon'     => 'dticon-add-circle-outline',
					'prefix'        => 'dticon-',
					'url'           => ICON_ELEM_URL . 'assets/detheme/detheme.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/detheme/fonts/detheme.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-prestashop') ){

				$tabs['prestashop'] = array(
					'name'          => 'prestashop',
					'label'         => esc_html__( 'Prestashop', 'icon-element' ),
					'labelIcon'     => 'ps-icon-lego',
					'prefix'        => 'ps-icon-',
					'url'           => ICON_ELEM_URL . 'assets/prestashop/prestashop.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/prestashop/fonts/prestashop.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-uicons') ){

				$tabs['uicons'] = array(
					'name'          => 'uicons',
					'label'         => esc_html__( 'Uicons', 'icon-element' ),
		            'labelIcon' => 'fi-rr-0',
		            'prefix' => 'fi-rr-',
		            'displayPrefix' => 'uic',
					'url'           => ICON_ELEM_URL . 'assets/uicons/uicons.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/uicons/fonts/uicons.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-jquery-uicons') ){

				$tabs['jquery-ui-icon'] = array(
					'name'          => 'jquery-ui-icon',
					'label'         => esc_html__( 'Jquery UI Icons', 'icon-element' ),
		            'labelIcon' => 'jquery-ui-icon-addon',
		            'prefix' => 'jquery-ui-icon-',
		            'displayPrefix' => 'uic',
					'url'           => ICON_ELEM_URL . 'assets/ui-icon/ui-icon.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/ui-icon/fonts/ui-icon.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-fabric') ){
				
				$tabs['fabric'] = array(
					'name'          => 'fabric',
					'label'         => esc_html__( 'Fabric icon', 'icon-element' ),
		            'labelIcon' => 'ms-Icon--OfficeLogo',
		            'prefix' => 'ms-Icon--',
		            'displayPrefix' => 'uic',
					'url'           => ICON_ELEM_URL . 'assets/ms-fabric/ms-fabric.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/ms-fabric/fonts/ms-fabric.json',
					'ver'           => '3.0.1',
				);
				
			}

			if ( get_option('icon-elementie-xlslim') ){
				
				$tabs['xlslim'] = array(
					'name'          => 'xlslim',
					'label'         => esc_html__( 'Xl Slim', 'icon-element' ),
		            'labelIcon' => 'xlslim-action-redo',
		            'prefix' => 'xlslim-',
		            'displayPrefix' => 'uic',
					'url'           => ICON_ELEM_URL . 'assets/xlslim/xlslim.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/xlslim/fonts/xlslim.json',
					'ver'           => '3.0.1',
				);
				
			}

			if ( get_option('icon-elementie-grommet') ){
				
				$tabs['grommet'] = array(
					'name'          => 'grommet',
					'label'         => esc_html__( 'Grommet', 'icon-element' ),
		            'labelIcon' => 'grmt-amex',
		            'prefix' => 'grmt-',
		            'displayPrefix' => 'uic',
					'url'           => ICON_ELEM_URL . 'assets/grommet/grommet.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/grommet/fonts/grommet.json',
					'ver'           => '3.0.1',
				);
				
			}

			if ( get_option('icon-elementie-orchid') ){ 
				
				$tabs['orchid'] = array(
					'name'          => 'orchid',
					'label'         => esc_html__( 'Orchid', 'icon-element' ),
		            'labelIcon' => 'lorchid-action-redo',
		            'prefix' => 'lorchid-',
		            'displayPrefix' => 'uic',
					'url'           => ICON_ELEM_URL . 'assets/orchid/orchid.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/orchid/fonts/orchid.json',
					'ver'           => '3.0.1',
				);
				
			} 

			if ( get_option('icon-elementie-icofont') && Icon_Element_Icons::is_iconelement_pro() ){

				$tabs['icofont'] = array(
					'name'          => 'icofont',
					'label'         => esc_html__( 'Icofont', 'icon-element' ),
					'labelIcon'     => 'icofont-angry-monster',
					'prefix'        => 'icofont-',
					'displayPrefix' => 'xlikj',
					'url'           => ICON_ELEM_PRO_URL . 'assets/icofont/icofont.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/icofont/fonts/icofont.json',
					'ver'           => '3.0.1',
				);
 
			}

			if ( get_option('icon-elementie-remix') && Icon_Element_Icons::is_iconelement_pro() ){

				$tabs['remix'] = array(
					'name'          => 'remix',
					'label'         => esc_html__( 'Remix', 'icon-element' ),
					'labelIcon'     => 'ri-4k-fill',
					'prefix'        => 'ri-',
					'displayPrefix' => 'xlrmx',
					'url'           => ICON_ELEM_PRO_URL . 'assets/remix/remix.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/remix/fonts/remix.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-medical') && Icon_Element_Icons::is_iconelement_pro() ){

				$tabs['ie-medical'] = array(
					'name'          => 'ie-medical',
					'label'         => esc_html__( 'Medical', 'icon-element' ),
					'labelIcon'     => 'medical-icon-i-imaging-alternative-pet',
					'prefix'        => 'medical-icon-',
					'displayPrefix' => 'xlmdcl',
					'url'           => ICON_ELEM_PRO_URL . 'assets/medical-icons/medical-icons.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/medical-icons/fonts/medical-icons.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-payment') && Icon_Element_Icons::is_iconelement_pro() ){

				$tabs['ie-payment'] = array(
					'name'          => 'ie-payment',
					'label'         => esc_html__( 'Payment', 'icon-element' ),
					'labelIcon'     => 'pw-paypal',
					'prefix'        => 'pw-',
					'displayPrefix' => 'xldpymnt',
					'url'           => ICON_ELEM_PRO_URL . 'assets/payment/payment.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/payment/fonts/payment.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-clarity') && Icon_Element_Icons::is_iconelement_pro() ){

				$tabs['ie-clarity'] = array(
					'name'          => 'ie-clarity',
					'label'         => esc_html__( 'Clarity', 'icon-element' ),
					'labelIcon'     => 'clarity-airplane-solid',
					'prefix'        => 'clarity-',
					'displayPrefix' => 'xldcrty',
					'url'           => ICON_ELEM_PRO_URL . 'assets/clarity/clarity.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/clarity/fonts/clarity.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-jam') && Icon_Element_Icons::is_iconelement_pro() ){

				$tabs['ie-jam'] = array(
					'name'          => 'ie-jam',
					'label'         => esc_html__( 'Jam', 'icon-element' ),
					'labelIcon'     => 'jam-alert',
					'prefix'        => 'jam-',
					'displayPrefix' => 'xldjam',
					'url'           => ICON_ELEM_PRO_URL . 'assets/jam/jam.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/jam/fonts/jam.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-octicons') && Icon_Element_Icons::is_iconelement_pro() ){

				$tabs['ie-octicons'] = array(
					'name'          => 'ie-octicons',
					'label'         => esc_html__( 'Octicons', 'icon-element' ),
					'labelIcon'     => 'octicons-inbox',
					'prefix'        => 'octicons-',
					'displayPrefix' => 'xldoktcn',
					'url'           => ICON_ELEM_PRO_URL . 'assets/octicons/octicons.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/octicons/fonts/octicons.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-nip') && Icon_Element_Icons::is_iconelement_pro() ){

				$tabs['nip'] = array(
					'name'          => 'nip',
					'label'         => esc_html__( 'Nip', 'icon-element' ),
					'labelIcon'     => 'xldnip-achievement',
					'prefix'        => 'xldnip-',
					'displayPrefix' => 'xldnip',
					'url'           => ICON_ELEM_PRO_URL . 'assets/xldnip/xldnip.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/xldnip/fonts/xldnip.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-trademe') && Icon_Element_Icons::is_iconelement_pro() ){

				$tabs['trademe'] = array(
					'name'          => 'trademe',
					'label'         => esc_html__( 'Trademe', 'icon-element' ),
					'labelIcon'     => 'tmicon-truck-free',
					'prefix'        => 'tmicon-',
					'url'           => ICON_ELEM_PRO_URL . 'assets/trademe/trademe.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/trademe/fonts/trademe.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon_elm_ie-framework7') ){

				$tabs['ie-framework7'] = array(
					'name'          => 'ie-framework7',
					'label'         => esc_html__( 'Framework7', 'icon-element' ),
					'labelIcon'     => 'f7icons-alarm',
					'prefix'        => 'f7icons-',
					'displayPrefix' => 'f7icons',
					'url'           => ICON_ELEM_URL . 'assets/framework7/framework7.css',
					'fetchJson'     => ICON_ELEM_URL . 'assets/framework7/fonts/framework7.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-ixsiemens') && Icon_Element_Icons::is_iconelement_pro() ){
				
				$tabs['ixsiemens'] = array(
					'name'          => 'ixsiemens',
					'label'         => esc_html__( 'Siemens icon', 'icon-element' ),
		            'labelIcon' => 'ixsiemens-zoom-out',
		            'prefix' => 'ixsiemens-',
		            'displayPrefix' => 'uic',
					'url'           => ICON_ELEM_PRO_URL . 'assets/siemens/siemens.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/siemens/fonts/siemens.json',
					'ver'           => '3.0.1',
				);
				
			}

			if ( get_option('icon-elementie-iconsaxcrypto') && Icon_Element_Icons::is_iconelement_pro() ){
				$tabs['iconsaxcrypto'] = array(
					'name'          => 'iconsaxcrypto',
					'label'         => esc_html__( 'Iconsax Crypto', 'icon-element' ),
					'labelIcon'     => 'isxcrypto-xiaomi',
					'prefix'        => 'isxcrypto-',
					'displayPrefix' => 'wb',
					'url'           => ICON_ELEM_PRO_URL . 'assets/iconsax-crypto/iconsax-crypto.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/iconsax-crypto/fonts/iconsax-crypto.json',
					'ver'           => '3.0.1',
				);
			}

			if ( get_option('icon-elementie-iconsaxoutline') && Icon_Element_Icons::is_iconelement_pro() ){
				$tabs['iconsaxoutline'] = array(
					'name'          => 'iconsaxoutline',
					'label'         => esc_html__( 'Iconsax Outline', 'icon-element' ),
					'labelIcon'     => 'isxoutline-wind',
					'prefix'        => 'isxoutline-',
					'displayPrefix' => 'wb',
					'url'           => ICON_ELEM_PRO_URL . 'assets/iconsax-outline/iconsax-outline.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/iconsax-outline/fonts/iconsax-outline.json',
					'ver'           => '3.0.1',
				);
			}

			if ( get_option('icon-elementie-elementplus') && Icon_Element_Icons::is_iconelement_pro() ){

				$tabs['elementplus'] = array(
					'name'          => 'elementplus',
					'label'         => esc_html__( 'Elementplus', 'icon-element' ),
					'labelIcon'     => 'elementplus-apple',
					'prefix'        => 'elementplus-',
					'displayPrefix' => 'dfrt',
					'url'           => ICON_ELEM_PRO_URL . 'assets/elementplus/elementplus.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/elementplus/fonts/elementplus.json',
					'ver'           => '3.0.1',
				);
			
			}

			if ( get_option('icon-elementie-tilda')&& Icon_Element_Icons::is_iconelement_pro() ){

				$tabs['tilda'] = array(
					'name'          => 'tilda',
					'label'         => esc_html__( 'Tilda', 'icon-element' ),
					'labelIcon'     => 'xldtld-wed_arch',
					'prefix'        => 'xldtld-',
					'displayPrefix' => 'tlda',
					'url'           => ICON_ELEM_PRO_URL . 'assets/tilda/tilda.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/tilda/fonts/tilda.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-themify-icons') && Icon_Element_Icons::is_iconelement_pro() ){

				$tabs['themify-icons'] = array(
					'name'          => 'themify-icons',
					'label'         => esc_html__( 'Themify', 'icon-element' ),
					'labelIcon'     => 'ti-wand',
					'prefix'        => 'ti-',
					'displayPrefix' => 'tivo',
					'url'           => ICON_ELEM_PRO_URL . 'assets/themify-icons/themify-icons.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/themify-icons/fonts/themify-icons.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-rivolicon') && Icon_Element_Icons::is_iconelement_pro() ){

				$tabs['rivolicon'] = array(
					'name'          => 'rivolicon',
					'label'         => esc_html__( 'Rivolicon', 'icon-element' ),
					'labelIcon'     => 'ri-adjust',
					'prefix'        => 'ri-',
					'displayPrefix' => 'rivo',
					'url'           => ICON_ELEM_PRO_URL . 'assets/rivolicon/rivolicon.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/rivolicon/fonts/rivolicon.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-mobirise') && Icon_Element_Icons::is_iconelement_pro() ){

				$tabs['mobirise'] = array(
					'name'          => 'mobirise',
					'label'         => esc_html__( 'Mobirise', 'icon-element' ),
					'labelIcon'     => 'mbri-alert',
					'prefix'        => 'mbri-',
					'displayPrefix' => 'khmbr',
					'url'           => ICON_ELEM_PRO_URL . 'assets/mobirise/mobirise.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/mobirise/fonts/mobirise.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-lineicons') && Icon_Element_Icons::is_iconelement_pro() ){

				$tabs['lineicons'] = array(
					'name'          => 'lineicons',
					'label'         => esc_html__( 'lineicons', 'icon-element' ),
					'labelIcon'     => 'lni-image',
					'prefix'        => 'lni-',
					'displayPrefix' => 'KbL',
					'url'           => ICON_ELEM_PRO_URL . 'assets/LineIcons/LineIcons.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/LineIcons/fonts/LineIcons.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-lineawesone-regular') && Icon_Element_Icons::is_iconelement_pro() ){

				$tabs['lineawesone-regular'] = array(
					'name'          => 'lineawesone-regular',
					'label'         => esc_html__( 'Lineawesome Regular', 'icon-element' ),
					'labelIcon'     => 'lineawesome-spell-check-solid',
					'prefix'        => 'lineawesome-',
					'displayPrefix' => 'la',
					'url'           => ICON_ELEM_PRO_URL . 'assets/line-awesome-regular/line-awesome-regular.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/line-awesome-regular/fonts/line-awesome-regular.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-linearicons') && Icon_Element_Icons::is_iconelement_pro() ){

				$tabs['linearicons'] = array(
					'name'          => 'linearicons',
					'label'         => esc_html__( 'Linearicons', 'icon-element' ),
					'labelIcon'     => 'lnr-apartment',
					'prefix'        => 'lnr-',
					'displayPrefix' => 'linearicons',
					'url'           => ICON_ELEM_PRO_URL . 'assets/linearicons/linearicons.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/linearicons/fonts/linearicons.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-linea_ecommerce') && Icon_Element_Icons::is_iconelement_pro() ){

				$tabs['linea_ecommerce'] = array(
					'name'          => 'linea_ecommerce',
					'label'         => esc_html__( 'Linea Ecommerce', 'icon-element' ),
					'labelIcon'     => 'icon-ecommerce-bag',
					'prefix'        => 'icon-ecommerce-',
					'displayPrefix' => 'linea_ecommerce',
					'url'           => ICON_ELEM_PRO_URL . 'assets/linea_ecommerce/linea_ecommerce.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/linea_ecommerce/fonts/linea_ecommerce.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-linea_elaboration') && Icon_Element_Icons::is_iconelement_pro() ){

				$tabs['linea_elaboration'] = array(
					'name'          => 'linea_elaboration',
					'label'         => esc_html__( 'Linea Elaboration', 'icon-element' ),
					'labelIcon'     => 'icon-basic-elaboration-bookmark-checck',
					'prefix'        => 'icon-basic-elaboration-',
					'displayPrefix' => 'icon-bion',
					'url'           => ICON_ELEM_PRO_URL.'assets/linea_basic_elaboration/linea_basic_elaboration.css',
					'fetchJson'     => ICON_ELEM_PRO_URL.'assets/linea_basic_elaboration/fonts/linea_basic_elaboration.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-linea_basic') && Icon_Element_Icons::is_iconelement_pro() ){

				$tabs['linea_basic'] = array(
					'name'          => 'linea_basic',
					'label'         => esc_html__( 'Linea Basic', 'icon-element' ),
					'labelIcon'     => 'icon-basic-accelerator',
					'prefix'        => 'icon-basic-',
					'displayPrefix' => 'icon-basic',
					'url'           => ICON_ELEM_PRO_URL . 'assets/linea_basic/linea_basic.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/linea_basic/fonts/linea_basic.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-io7-icon') && Icon_Element_Icons::is_iconelement_pro() ){

				$tabs['io7-icon'] = array(
					'name'          => 'io7-icon',
					'label'         => esc_html__( 'iOS7 Icons', 'icon-element' ),
					'labelIcon'     => 'ios7-dice',
					'prefix'        => 'ios7-',
					'displayPrefix' => 'ios7',
					'url'           => ICON_ELEM_PRO_URL . 'assets/ios7/ios7.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/ios7/fonts/ios7.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-iconmonstr-font') && Icon_Element_Icons::is_iconelement_pro() ){

				$tabs['iconmonstr-font'] = array(
					'name'          => 'iconmonstr-font',
					'label'         => esc_html__( 'IconMonster Icons', 'icon-element' ),
					'labelIcon'     => 'im-calendar',
					'prefix'        => '',
					'displayPrefix' => 'im',
					'url'           => ICON_ELEM_PRO_URL . 'assets/iconmonstr/iconmonstr.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/iconmonstr/fonts/iconmonstr.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-icomoon-font') && Icon_Element_Icons::is_iconelement_pro() ){

				$tabs['icomoon-font'] = array(
					'name'          => 'icomoon-font',
					'label'         => esc_html__( 'IcoMoon Font Icons', 'icon-element' ),
					'labelIcon'     => 'iconmn-pen',
					'prefix'        => 'iconmn-',
					'displayPrefix' => 'iconmn',
					'url'           => ICON_ELEM_PRO_URL . 'assets/icomoon/icomoon.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/icomoon/fonts/icomoon.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-gonzo-font') && Icon_Element_Icons::is_iconelement_pro() ){

				$tabs['gonzo-font'] = array(
					'name'          => 'gonzo-font',
					'label'         => esc_html__( 'Gonzo Font Icons', 'icon-element' ),
					'labelIcon'     => 'gnj-antenna',
					'prefix'        => 'gnj-',
					'displayPrefix' => 'gnj-font',
					'url'           => ICON_ELEM_PRO_URL . 'assets/gonzo/gonzo.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/gonzo/fonts/gonzo.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-et-line') && Icon_Element_Icons::is_iconelement_pro() ){

				$tabs['et-line-font'] = array(
					'name'          => 'et-line-font',
					'label'         => esc_html__( 'ET Line Icons', 'icon-element' ),
					'labelIcon'     => 'et-line-icon-mobile',
					'prefix'        => 'et-line-',
					'displayPrefix' => 'et-line-font',
					'url'           => ICON_ELEM_PRO_URL . 'assets/et-line-font/et-line-font.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/et-line-font/fonts/et-line-font.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-elegant-font') && Icon_Element_Icons::is_iconelement_pro() ){

				$tabs['elegant-font'] = array(
					'name'          => 'elegant-font',
					'label'         => esc_html__( 'Elegant Font Icons', 'icon-element' ),
					'labelIcon'     => 'ele-icon_cloud-upload_alt',
					'prefix'        => 'ele-',
					'displayPrefix' => 'elegant-font',
					'url'           => ICON_ELEM_PRO_URL . 'assets/elegant-font/elegant-font.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/elegant-font/fonts/elegant-font.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-boxicons-icons') && Icon_Element_Icons::is_iconelement_pro() ){

				$tabs['boxicons-icons'] = array(
					'name'          => 'boxicons-icons',
					'label'         => esc_html__( 'Boxicons Icons', 'icon-element' ),
					'labelIcon'     => 'bx-bug',
					'prefix'        => 'bx-',
					'displayPrefix' => 'boxicons-icons',
					'url'           => ICON_ELEM_PRO_URL . 'assets/boxicons/boxicons.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/boxicons/fonts/boxicons.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-linea-music') && Icon_Element_Icons::is_iconelement_pro() ){

				$tabs['linea-music'] = array(
					'name'          => 'linea-music',
					'label'         => esc_html__( 'Linea Music', 'icon-element' ),
					'labelIcon'     => 'icon-music-cd',
					'prefix'        => 'icon-music-',
					'displayPrefix' => 'lineamusic',
					'url'           => ICON_ELEM_PRO_URL . 'assets/linea_music/linea_music.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/linea_music/fonts/linea_music.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-dripicons-icons') && Icon_Element_Icons::is_iconelement_pro() ){

				$tabs['dripicons-icons'] = array(
					'name'          => 'dripicons-icons',
					'label'         => esc_html__( 'Dripicons Icons', 'icon-element' ),
					'labelIcon'     => 'dripicons-alarm',
					'prefix'        => 'dripicons-',
					'displayPrefix' => 'dripicons',
					'url'           => ICON_ELEM_PRO_URL . 'assets/dripicons/dripicons.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/dripicons/fonts/dripicons.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-bootstrap') && Icon_Element_Icons::is_iconelement_pro() ){
				$tabs['bootstrap-icons'] = array(
					'name'          => 'bootstrap-icons',
					'label'         => esc_html__( 'Bootstrap', 'icon-element' ),
					'labelIcon'     => 'bi bi-bootstrap',
					'prefix'        => 'bi-',
					'displayPrefix' => 'bi',
					'url'           => ICON_ELEM_PRO_URL . 'assets/bootstrap/bootstrap.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/bootstrap/fonts/bootstrap.json',
					'ver'           => '3.0.1',
				);
			}

			if ( get_option('icon-elementie-antdesign') && Icon_Element_Icons::is_iconelement_pro() ){
				$tabs['antdesign'] = array(
					'name'          => 'antdesign',
					'label'         => esc_html__( 'Antdesign', 'icon-element' ),
					'labelIcon'     => 'antdesign-account-book',
					'prefix'        => 'antdesign-',
					'displayPrefix' => 'ieant',
					'url'           => ICON_ELEM_PRO_URL . 'assets/antdesign/antdesign.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/antdesign/fonts/antdesign.json',
					'ver'           => '3.0.1',
				);
			}

			if ( get_option('icon-elementie-tabler') && Icon_Element_Icons::is_iconelement_pro() ){
				$tabs['tabler'] = array(
					'name'          => 'tabler',
					'label'         => esc_html__( 'Tabler', 'icon-element' ),
					'labelIcon'     => 'tblr tblr-apps',
					'prefix'        => 'tblr-',
					'displayPrefix' => 'tblr',
					'url'           => ICON_ELEM_PRO_URL . 'assets/tabler/tabler.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/tabler/fonts/tabler.json',
					'ver'           => '3.0.1',
				);
			}

			if ( get_option('icon-elementie-iconicool') && Icon_Element_Icons::is_iconelement_pro() ){
				$tabs['iconicool'] = array(
					'name'          => 'iconicool',
					'label'         => esc_html__( 'Iconicool', 'icon-element' ),
					'labelIcon'     => 'iecool iecool-Son',
					'prefix'        => 'iecool-',
					'displayPrefix' => 'iecool',
					'url'           => ICON_ELEM_PRO_URL . 'assets/iconicool/iconicool.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/iconicool/fonts/iconicool.json',
					'ver'           => '3.0.1',
				);
			}

			if ( get_option('icon-elementie-evaicon') && Icon_Element_Icons::is_iconelement_pro() ){
				$tabs['evaicon'] = array(
					'name'          => 'evaicon',
					'label'         => esc_html__( 'Evaicon', 'icon-element' ),
					'labelIcon'     => 'evaicon-arrow-back-outline',
					'prefix'        => 'evaicon-',
					'displayPrefix' => 'evaicon',
					'url'           => ICON_ELEM_PRO_URL . 'assets/evaicon/evaicon.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/evaicon/fonts/evaicon.json',
					'ver'           => '3.0.1',
				);
			}

			if ( get_option('icon-elementie-androidholo') && Icon_Element_Icons::is_iconelement_pro() ){
				$tabs['androidholo'] = array(
					'name'          => 'androidholo',
					'label'         => esc_html__( 'Android holo', 'icon-element' ),
					'labelIcon'     => 'icholo-accept',
					'prefix'        => 'icholo-',
					'displayPrefix' => 'icholo',
					'url'           => ICON_ELEM_PRO_URL . 'assets/androidholo/androidholo.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/androidholo/fonts/androidholo.json',
					'ver'           => '3.0.1',
				);
			}

			if ( get_option('icon-elementie-androidmaterial') && Icon_Element_Icons::is_iconelement_pro() ){
				$tabs['androidmaterial'] = array(
					'name'          => 'androidmaterial',
					'label'         => esc_html__( 'Android material', 'icon-element' ),
					'labelIcon'     => 'icmaterial-access-alarms',
					'prefix'        => 'icmaterial-',
					'displayPrefix' => 'icmaterial',
					'url'           => ICON_ELEM_PRO_URL . 'assets/androidmaterial/androidmaterial.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/androidmaterial/fonts/androidmaterial.json',
					'ver'           => '3.0.1',
				);
			}

			if ( get_option('icon-elementie-chart') && Icon_Element_Icons::is_iconelement_pro() ){
				$tabs['chart'] = array(
					'name'          => 'chart',
					'label'         => esc_html__( 'chart', 'icon-element' ),
					'labelIcon'     => 'iechart-Area-chart-curved',
					'prefix'        => 'iechart-',
					'displayPrefix' => 'iechart',
					'url'           => ICON_ELEM_PRO_URL . 'assets/chart/chart.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/chart/fonts/chart.json',
					'ver'           => '3.0.1',
				);
			}

			if ( get_option('icon-elementie-outlined') && Icon_Element_Icons::is_iconelement_pro() ){

				$tabs['ie-outlined'] = array(
					'name'          => 'ie-outlined',
					'label'         => esc_html__( 'Outline', 'icon-element' ),
					'labelIcon'     => 'outlined-music-stop',
					'prefix'        => 'outlined-',
					'displayPrefix' => 'xlotlnd',
					'url'           => ICON_ELEM_PRO_URL . 'assets/outline/outline.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/outline/fonts/outline.json',
					'ver'           => '3.0.1',
				);

			}

			if ( get_option('icon-elementie-devicon') && Icon_Element_Icons::is_iconelement_pro() ){
				$tabs['devicon'] = array(
					'name'          => 'devicon',
					'label'         => esc_html__( 'Devicon', 'icon-element' ),
					'labelIcon'     => 'devicon-openal-plain',
					'prefix'        => 'devicon-',
					'displayPrefix' => 'devicon',
					'url'           => ICON_ELEM_PRO_URL . 'assets/devicon/devicon.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/devicon/fonts/devicon.json',
					'ver'           => '3.0.1',
				);
			}

			if ( get_option('icon-elementie-mapicon') && Icon_Element_Icons::is_iconelement_pro() ){
				$tabs['mapicon'] = array(
					'name'          => 'mapicon',
					'label'         => esc_html__( 'Map icon', 'icon-element' ),
					'labelIcon'     => 'map-icon map-icon-map-pin',
					'prefix'        => 'map-icon-',
					'displayPrefix' => 'map-icon',
					'url'           => ICON_ELEM_PRO_URL . 'assets/mapicon/mapicon.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/mapicon/fonts/mapicon.json',
					'ver'           => '3.0.1',
				);
			}

			if ( get_option('icon-elementie-keyrune') && Icon_Element_Icons::is_iconelement_pro() ){
				
				$tabs['keyrune'] = array(
					'name'          => 'keyrune',
					'label'         => esc_html__( 'Keyrune', 'icon-element' ),
		            'labelIcon' => 'ss-lea',
		            'prefix' => 'ss-',
		            'displayPrefix' => 'uic',
					'url'           => ICON_ELEM_PRO_URL . 'assets/keyrune/keyrune.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/keyrune/fonts/keyrune.json',
					'ver'           => '3.0.1',
				);
				
			}

			if ( get_option('icon-elementie-mingcute') && Icon_Element_Icons::is_iconelement_pro() ){

				$tabs['mingcute'] = array(
					'name'          => 'mingcute',
					'label'         => esc_html__( 'Mingcute', 'icon-element' ),
		            'labelIcon' => 'mgc_ABS_line',
		            'prefix' => 'mgc_',
		            'displayPrefix' => 'uic',
					'url'           => ICON_ELEM_PRO_URL . 'assets/mingcute/mingcute.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/mingcute/fonts/mingcute.json',
					'ver'           => '3.0.1',
				);
				
			}

			if ( get_option('icon-elementie-olicons') && Icon_Element_Icons::is_iconelement_pro() ){
				
				$tabs['olicons'] = array(
					'name'          => 'olicons',
					'label'         => esc_html__( 'Olicons', 'icon-element' ),
		            'labelIcon' => 'ol-washer-o',
		            'prefix' => 'ol-',
		            'displayPrefix' => 'uic',
					'url'           => ICON_ELEM_PRO_URL . 'assets/olicons/olicons.css',
					'fetchJson'     => ICON_ELEM_PRO_URL . 'assets/olicons/fonts/olicons.json',
					'ver'           => '3.0.1',
				);
				
			}

			///////////
			return $tabs;
		}

		public static function get_instance() {

			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}

}
