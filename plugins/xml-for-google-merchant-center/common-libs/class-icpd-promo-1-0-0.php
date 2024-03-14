<?php 
/**
 * This class is responsible for the output of the promo
 *
 * @package                 iCopyDoc Plugins (ICPD)
 * @subpackage              
 * @since                   0.1.0
 * 
 * @version                 1.0.0 (12-06-2023)
 * @author                  Maxim Glazunov
 * @link                    https://icopydoc.ru/
 * @see                     
 * 
 * @param	string	
 *
 * @depends                 classes:    
 *                          traits:     
 *                          methods:    
 *                          functions:  
 *                          constants:  
 *                          actions:    print_view_html_icpd_my_plugins_list
 *                          filters:    icpd_f_plugins_arr
 */
defined( 'ABSPATH' ) || exit;

// 'xml-for-google-merchant-center' - slug for translation (be sure to make an autocorrect)
if ( ! class_exists( 'ICPD_Promo' ) ) {
	final class ICPD_Promo {
		private $pref = '';
		private $plugins_arr;

		public function __construct( $pref = '' ) {
			$this->pref = $pref;
			$plugins_arr = [ 
				[ 
					'name' => 'XML for Google Merchant Center',
					'desc' => __( 'Сreates a XML-feed to upload to Google Merchant Center', 'xml-for-google-merchant-center' ),
					'url' => 'https://wordpress.org/plugins/xml-for-google-merchant-center/'
				],
				[ 
					'name' => 'YML for Yandex Market',
					'desc' => __(
						'Сreates a YML-feed for importing your products to Yandex Market',
						'xml-for-google-merchant-center'
					),
					'url' => 'https://wordpress.org/plugins/yml-for-yandex-market/'
				],
				[ 
					'name' => 'Import from YML',
					'desc' => __( 'Imports products from YML to your shop', 'xml-for-google-merchant-center' ),
					'url' => 'https://wordpress.org/plugins/import-from-yml/'
				],
				[ 
					'name' => 'Integrate myTarget for WooCommerce',
					'desc' => __(
						'This plugin helps setting up myTarget counter for dynamic remarketing for WooCommerce',
						'xml-for-google-merchant-center'
					),
					'url' => 'https://wordpress.org/plugins/wc-mytarget/'
				],
				[ 
					'name' => 'XML for Hotline',
					'desc' => __( 'Сreates a XML-feed for importing your products to Hotline', 'xml-for-google-merchant-center' ),
					'url' => 'https://wordpress.org/plugins/xml-for-hotline/'
				],
				[ 
					'name' => 'Gift upon purchase for WooCommerce',
					'desc' => __(
						'This plugin will add a marketing tool that will allow you to give gifts to the buyer upon purchase',
						'xml-for-google-merchant-center'
					),
					'url' => 'https://wordpress.org/plugins/gift-upon-purchase-for-woocommerce/'
				],
				[ 
					'name' => 'Import Products to OK.ru',
					'desc' => __(
						'With this plugin, you can import products to your group on ok.ru',
						'xml-for-google-merchant-center'
					),
					'url' => 'https://wordpress.org/plugins/import-products-to-ok-ru/'
				],
				[ 
					'name' => 'Import Products to OZON',
					'desc' => __(
						'With this plugin, you can import products to OZON',
						'xml-for-google-merchant-center'
					),
					'url' => 'https://wordpress.org/plugins/xml-for-google-merchant-center/'
				],
				[ 
					'name' => 'Import Products to VK.com',
					'desc' => __(
						'With this plugin, you can import products to your group on VK.com',
						'xml-for-google-merchant-center'
					),
					'url' => 'https://wordpress.org/plugins/xml-for-google-merchant-center/'
				],
				[ 
					'name' => 'XML for Avito',
					'desc' => __( 'Сreates a XML-feed for importing your products to', 'xml-for-google-merchant-center' ),
					'url' => 'https://wordpress.org/plugins/xml-for-avito/'
				],
				[ 
					'name' => 'XML for O.Yandex (Яндекс Объявления)',
					'desc' => __( 'Сreates a XML-feed for importing your products to', 'xml-for-google-merchant-center' ),
					'url' => 'https://wordpress.org/plugins/xml-for-o-yandex/'
				]
			];
			$plugins_arr = apply_filters( 'icpd_f_plugins_arr', $plugins_arr );
			$this->plugins_arr = $plugins_arr;
			unset( $plugins_arr );
			$this->init_hooks();
		}

		public function init_hooks() {
			add_action( 'admin_print_footer_scripts', [ $this, 'print_css_styles' ] );
			add_action( 'print_view_html_icpd_my_plugins_list', [ $this, 'print_view_html_plugins_list_block' ], 10, 1 );
		}

		public function print_css_styles() {
			print( '<style>.clear{clear: both;} .icpd_bold {font-weight: 700;}</style>' );
		}

		public function print_view_html_plugins_list_block( $pref ) {
			if ( $pref !== $this->get_pref() ) {
				return;
			}
			?>
			<div class="clear"></div>
			<div class="metabox-holder">
				<div class="postbox">
					<h2 class="hndle">
						<?php _e( 'My plugins that may interest you', 'xml-for-google-merchant-center' ); ?>
					</h2>
					<div class="inside">
						<?php
						for ( $i = 0; $i < count( $this->plugins_arr ); $i++ ) {
							$this->print_view_html_plugins_list_item( $this->plugins_arr[ $i ] );
						}
						?>
					</div>
				</div>
			</div>
			<?php
		}

		private function print_view_html_plugins_list_item( $data_arr ) {
			printf( '<p><span class="icpd_bold">%1$s</span> - %2$s. <a href="%3$s" target="_blank">%4$s</a>.</p>%5$s',
				$data_arr['name'],
				$data_arr['desc'],
				$data_arr['url'],
				__( 'Read more', 'xml-for-google-merchant-center' ),
				PHP_EOL
			);
		}

		private function get_pref() {
			return $this->pref;
		}
	} // end final class ICPD_Promo
} // end if (!class_exists('ICPD_Promo'))