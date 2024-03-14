<?php
/**
 * Simple Ticker
 *
 * @package    Simple Ticker
 * @subpackage SimpleTicker
/*
	Copyright (c) 2016- Katsushi Kawamori (email : dodesyoswift312@gmail.com)
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; version 2 of the License.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

$simpleticker = new SimpleTicker();

/** ==================================================
 * Main Functions
 */
class SimpleTicker {

	/** ==================================================
	 * Option
	 *
	 * @var $simpleticker_option  simpleticker_option.
	 */
	private $simpleticker_option;

	/** ==================================================
	 * Construct
	 *
	 * @since 1.06
	 */
	public function __construct() {

		$this->simpleticker_option = get_option( 'simple_ticker' );

		add_action( 'init', array( $this, 'simpleticker_block_init' ) );
		add_shortcode( 'simpleticker', array( $this, 'simpleticker_func' ) );

		/* original hook */
		add_filter( 'smptck_read_tickers', array( $this, 'read_tickers' ), 10, 14 );
		add_filter( 'smptck_html_text', array( $this, 'html_text' ), 10, 1 );

		add_action( 'wp_print_styles', array( $this, 'load_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_styles' ) );

		if ( 'none' <> $this->simpleticker_option['insert'] ) {
			add_filter( 'the_content', array( $this, 'insert_post' ) );
		}
		if ( $this->simpleticker_option['insert_body_open'] ) {
			add_action( 'wp_body_open', array( $this, 'insert_body_open' ) );
		}
	}

	/** ==================================================
	 * Attribute block
	 *
	 * @since 3.00
	 */
	public function simpleticker_block_init() {

		register_block_type(
			plugin_dir_path( __DIR__ ) . 'block/build',
			array(
				'render_callback' => array( $this, 'simpleticker_func' ),
				'title' => 'Simple Ticker',
				'description' => _x( 'Displays the ticker.', 'block description', 'simple-ticker' ),
				'keywords' => array(
					_x( 'ticker', 'block keyword', 'simple-ticker' ),
					'woocommerce',
				),
				'attributes'      => array(
					'ticker1_color' => array(
						'type'      => 'color',
						'default'   => $this->simpleticker_option['ticker1']['color'],
					),
					'ticker1_text'  => array(
						'type'      => 'string',
						'default'   => $this->simpleticker_option['ticker1']['text'],
					),
					'ticker1_url'  => array(
						'type'      => 'string',
						'default'   => $this->simpleticker_option['ticker1']['url'],
					),
					'ticker2_color' => array(
						'type'      => 'color',
						'default'   => $this->simpleticker_option['ticker2']['color'],
					),
					'ticker2_text'  => array(
						'type'      => 'string',
						'default'   => $this->simpleticker_option['ticker2']['text'],
					),
					'ticker2_url'  => array(
						'type'      => 'string',
						'default'   => $this->simpleticker_option['ticker2']['url'],
					),
					'ticker3_color' => array(
						'type'      => 'color',
						'default'   => $this->simpleticker_option['ticker3']['color'],
					),
					'ticker3_text'  => array(
						'type'      => 'string',
						'default'   => $this->simpleticker_option['ticker3']['text'],
					),
					'ticker3_url'  => array(
						'type'      => 'string',
						'default'   => $this->simpleticker_option['ticker3']['url'],
					),
					'sticky_posts_display' => array(
						'type'    => 'boolean',
						'default' => $this->simpleticker_option['sticky_posts']['display'],
					),
					'sticky_posts_title_color' => array(
						'type'    => 'color',
						'default' => $this->simpleticker_option['sticky_posts']['title_color'],
					),
					'sticky_posts_content_color'   => array(
						'type'    => 'color',
						'default' => $this->simpleticker_option['sticky_posts']['content_color'],
					),
					'woo_sales_display' => array(
						'type'    => 'boolean',
						'default' => $this->simpleticker_option['woo_sales']['display'],
					),
					'woo_sales_color'  => array(
						'type'      => 'color',
						'default'   => $this->simpleticker_option['woo_sales']['color'],
					),
				),
			)
		);

		$script_handle = generate_block_asset_handle( 'simple-ticker/simpleticker-block', 'editorScript' );
		wp_set_script_translations( $script_handle, 'simple-ticker' );
	}

	/** ==================================================
	 * Body open action
	 *
	 * @since 3.00
	 */
	public function insert_body_open() {

		$allowed_html = array(
			'div' => array(
				'class' => array(),
			),
			'span' => array(
				'style' => array(),
			),
			'a' => array(
				'href' => array(),
			),
		);

		echo wp_kses( $this->read_tickers( null, null, null, null, null, null, null, null, null, null, null, null, null, null ), $allowed_html );
	}

	/** ==================================================
	 * Contents filter
	 *
	 * @param string $content  content.
	 * @return string $content
	 * @since 2.04
	 */
	public function insert_post( $content ) {

		$custom_content = $this->read_tickers( null, null, null, null, null, null, null, null, null, null, null, null, null, null );
		switch ( $this->simpleticker_option['insert'] ) {
			case 'before':
				$content = $custom_content . $content;
				break;
			case 'after':
				$content .= $custom_content;
				break;
			case 'beforeafter':
				$content = $custom_content . $content . $custom_content;
				break;
		}

		return $content;
	}

	/** ==================================================
	 * Main
	 *
	 * @param string $ticker1_color  ticker1_color.
	 * @param string $ticker1_text  ticker1_text.
	 * @param string $ticker1_url  ticker1_url.
	 * @param string $ticker2_color  ticker2_color.
	 * @param string $ticker2_text  ticker2_text.
	 * @param string $ticker2_url  ticker2_url.
	 * @param string $ticker3_color  ticker3_color.
	 * @param string $ticker3_text  ticker3_text.
	 * @param string $ticker3_url  ticker3_url.
	 * @param bool   $sticky_posts_display  sticky_posts_display.
	 * @param string $sticky_posts_title_color  sticky_posts_title_color.
	 * @param string $sticky_posts_content_color  sticky_posts_content_color.
	 * @param bool   $woo_sales_display  woo_sales_display.
	 * @param string $woo_sales_color  woo_sales_color.
	 * @return string $ticker_html
	 * @since 1.00
	 */
	public function read_tickers( $ticker1_color, $ticker1_text, $ticker1_url, $ticker2_color, $ticker2_text, $ticker2_url, $ticker3_color, $ticker3_text, $ticker3_url, $sticky_posts_display, $sticky_posts_title_color, $sticky_posts_content_color, $woo_sales_display, $woo_sales_color ) {

		if ( empty( $ticker1_color ) ) {
			$ticker1_color = $this->simpleticker_option['ticker1']['color']; }
		if ( empty( $ticker1_text ) ) {
			$ticker1_text = $this->simpleticker_option['ticker1']['text']; }
		if ( empty( $ticker1_url ) ) {
			$ticker1_url = $this->simpleticker_option['ticker1']['url']; }
		if ( empty( $ticker2_color ) ) {
			$ticker2_color = $this->simpleticker_option['ticker2']['color']; }
		if ( empty( $ticker2_text ) ) {
			$ticker2_text = $this->simpleticker_option['ticker2']['text']; }
		if ( empty( $ticker2_url ) ) {
			$ticker2_url = $this->simpleticker_option['ticker2']['url']; }
		if ( empty( $ticker3_color ) ) {
			$ticker3_color = $this->simpleticker_option['ticker3']['color']; }
		if ( empty( $ticker3_text ) ) {
			$ticker3_text = $this->simpleticker_option['ticker3']['text']; }
		if ( empty( $ticker3_url ) ) {
			$ticker3_url = $this->simpleticker_option['ticker3']['url']; }

		if ( empty( $sticky_posts_display ) ) {
			$sticky_posts_display = $this->simpleticker_option['sticky_posts']['display']; }
		if ( empty( $sticky_posts_title_color ) ) {
			$sticky_posts_title_color = $this->simpleticker_option['sticky_posts']['title_color']; }
		if ( empty( $sticky_posts_content_color ) ) {
			$sticky_posts_content_color = $this->simpleticker_option['sticky_posts']['content_color']; }

		if ( empty( $woo_sales_display ) ) {
			$woo_sales_display = $this->simpleticker_option['woo_sales']['display']; }
		if ( empty( $woo_sales_color ) ) {
			$woo_sales_color = $this->simpleticker_option['woo_sales']['color']; }

		$ticker_html = null;

		if ( ! empty( $ticker1_text ) ) {
			if ( ! empty( $ticker1_url ) ) {
				$ticker_html .= '<a href="' . esc_url( $ticker1_url ) . '">';
			}
			$ticker_html .= '<div class="marquee"><div class="marquee-inner"><span style="color: ' . esc_attr( $ticker1_color ) . '; font-weight: bold;">';
			$ticker_html .= ' ' . apply_filters( 'simple_ticker_1_inner_text', $ticker1_text, get_the_ID() );
			$ticker_html .= '</span></div></div>';
			if ( ! empty( $ticker1_url ) ) {
				$ticker_html .= '</a>';
			}
		}
		if ( ! empty( $ticker2_text ) ) {
			if ( ! empty( $ticker2_url ) ) {
				$ticker_html .= '<a href="' . esc_url( $ticker2_url ) . '">';
			}
			$ticker_html .= '<div class="marquee"><div class="marquee-inner"><span style="color: ' . esc_attr( $ticker2_color ) . '; font-weight: bold;">';
			$ticker_html .= ' ' . apply_filters( 'simple_ticker_2_inner_text', $ticker2_text, get_the_ID() );
			$ticker_html .= '</span></div></div>';
			if ( ! empty( $ticker2_url ) ) {
				$ticker_html .= '</a>';
			}
		}
		if ( ! empty( $ticker3_text ) ) {
			if ( ! empty( $ticker3_url ) ) {
				$ticker_html .= '<a href="' . esc_url( $ticker3_url ) . '">';
			}
			$ticker_html .= '<div class="marquee"><div class="marquee-inner"><span style="color: ' . esc_attr( $ticker3_color ) . '; font-weight: bold;">';
			$ticker_html .= ' ' . apply_filters( 'simple_ticker_3_inner_text', $ticker3_text, get_the_ID() );
			$ticker_html .= '</span></div></div>';
			if ( ! empty( $ticker3_url ) ) {
				$ticker_html .= '</a>';
			}
		}

		if ( $sticky_posts_display ) {
			$stickies = get_option( 'sticky_posts' );
			if ( ! empty( $stickies ) ) {
				rsort( $stickies );
				foreach ( $stickies as $sticky ) {
					$post = null;
					$title = null;
					$content = null;
					$post = get_post( $sticky );
					$title = $post->post_title;
					$content = $this->html_text( $post->post_content );
					$permalink = get_permalink( $post->ID );
					$ticker_html .= '<a href="' . esc_url( $permalink ) . '">';
					$ticker_html .= '<div class="marquee"><div class="marquee-inner"><span style="color: ' . esc_attr( $sticky_posts_title_color ) . '; font-weight: bold;">';
					$ticker_html .= ' ' . esc_html( $title ) . ':';
					$ticker_html .= '</span>';
					$ticker_html .= '<span style="color: ' . esc_attr( $sticky_posts_content_color ) . '; font-weight: bold;">';
					$ticker_html .= esc_html( $content );
					$ticker_html .= '</span></div></div>';
					$ticker_html .= '</a>';
				}
			}
		}

		if ( $woo_sales_display && class_exists( 'WooCommerce' ) ) {
			global $wpdb;
			$sales = $wpdb->get_results(
				"
				SELECT	post_id, meta_key, meta_value
				FROM	{$wpdb->prefix}postmeta
				WHERE	meta_key LIKE '%%_sale_price%%'
				"
			);
			$ticker_html .= '<div class="marquee"><div class="marquee-inner">';
			$woo_sales_text_tag = $this->simpleticker_option['woo_sales']['text'];
			$currency = get_option( 'woocommerce_currency' );
			$currency_symbol = get_woocommerce_currency_symbol( $currency );
			foreach ( $sales as $sale ) {
				if ( '_sale_price' === $sale->meta_key ) {
					if ( ! empty( $sale->meta_value ) ) {
						$woo_sales_datas = array();
						$sale_product = get_the_title( $sale->post_id );
						$sale_link = get_permalink( $sale->post_id );

						$regular_price = get_post_meta( $sale->post_id, '_regular_price', true );
						$sale_price = get_post_meta( $sale->post_id, '_sale_price', true );
						$to_date_ux = intval( get_post_meta( $sale->post_id, '_sale_price_dates_to', true ) );
						$current_date_ux = time();

						$woo_sales_datas['regular_price'] = sprintf( get_woocommerce_price_format(), $currency_symbol, $regular_price );
						$woo_sales_datas['sale_price'] = sprintf( get_woocommerce_price_format(), $currency_symbol, $sale_price );

						$dis_amount = sprintf( get_woocommerce_price_format(), $currency_symbol, intval( $regular_price - $sale_price ) );
						$dis_rate = intval( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
						/* translators: Woo sale rate price */
						$woo_sales_datas['dis_rate_price'] = sprintf( __( '%1$s %2$s', 'simple-ticker' ), $dis_amount, __( 'OFF', 'countdown-woocommerce-sale' ) );
						/* translators: Woo sale rate percent */
						$woo_sales_datas['dis_rate_percent'] = sprintf( __( '%1$d&#37; %2$s', 'simple-ticker' ), $dis_rate, __( 'OFF', 'countdown-woocommerce-sale' ) );

						if ( function_exists( 'wp_date' ) ) {
							$woo_sales_datas['to_date'] = wp_date( 'Y-m-d H:i:s', $to_date_ux, new DateTimeZone( 'UTC' ) );
						} else {
							$woo_sales_datas['to_date'] = date_i18n( 'Y-m-d H:i:s', $to_date_ux, false );
						}
						$interval_day_ux = $to_date_ux - $current_date_ux;
						if ( $to_date_ux > 0 ) {
							$woo_sales_datas['interval_day'] = intval( $interval_day_ux / ( 60 * 60 * 24 ) ) . __( 'Days', 'simple-ticker' );
						} else {
							$woo_sales_datas['interval_day'] = null;
						}
						$woo_sales_datas['interval_time'] = gmdate( 'H:i:s', $interval_day_ux % ( 60 * 60 * 24 ) );
						$ticker_html .= '<a href="' . esc_url( $sale_link ) . '">' . esc_html( $sale_product ) . '</a>';
						$ticker_html .= '<span style="color: ' . esc_attr( $woo_sales_color ) . '; font-weight: bold;"> : ';
						$woo_sales_text = null;
						if ( $woo_sales_datas ) {
							$woo_sales_text = $woo_sales_text_tag;
							foreach ( $woo_sales_datas as $item => $woo_sale ) {
								$woo_sales_text = str_replace( '%' . $item . '%', $woo_sale, $woo_sales_text );
							}
							preg_match_all( '/%(.*?)%/', $woo_sales_text, $woo_sales_text_per_match );
							foreach ( $woo_sales_text_per_match as $key1 ) {
								foreach ( $key1 as $key2 ) {
									$woo_sales_text = str_replace( '%' . $key2 . '%', '', $woo_sales_text );
								}
							}
						}
						$ticker_html .= esc_html( $woo_sales_text );
						$ticker_html .= '</span> ';
					}
				}
			}
			$ticker_html .= '</div></div>';
		}

		if ( empty( $ticker_html ) && is_user_logged_in() ) {
			$ticker_html .= '<div style="text-align: center;">';
			$ticker_html .= '<div><strong><span class="dashicons dashicons-megaphone" style="position: relative; top: 5px;"></span>Simple Ticker</strong></div>';
			$ticker_html .= __( 'Please input Ticker.', 'simple-ticker' );
			$ticker_html .= '</div>';
		}

		return $ticker_html;
	}

	/** ==================================================
	 * short code
	 *
	 * @param array $atts  atts.
	 * @return string $this->read_tickers()
	 */
	public function simpleticker_func( $atts ) {

		$a = shortcode_atts(
			array(
				'ticker1_color' => '',
				'ticker1_text' => '',
				'ticker1_url' => '',
				'ticker2_color' => '',
				'ticker2_text' => '',
				'ticker2_url' => '',
				'ticker3_color' => '',
				'ticker3_text' => '',
				'ticker3_url' => '',
				'sticky_posts_display' => '',
				'sticky_posts_title_color' => '',
				'sticky_posts_content_color' => '',
				'woo_sales_display' => '',
				'woo_sales_color' => '',
			),
			$atts
		);

		$ticker1_color              = $a['ticker1_color'];
		$ticker1_text               = $a['ticker1_text'];
		$ticker1_url                = $a['ticker1_url'];
		$ticker2_color              = $a['ticker2_color'];
		$ticker2_text               = $a['ticker2_text'];
		$ticker2_url                = $a['ticker2_url'];
		$ticker3_color              = $a['ticker3_color'];
		$ticker3_text               = $a['ticker3_text'];
		$ticker3_url                = $a['ticker3_url'];
		$sticky_posts_display       = $a['sticky_posts_display'];
		$sticky_posts_title_color   = $a['sticky_posts_title_color'];
		$sticky_posts_content_color = $a['sticky_posts_content_color'];
		$woo_sales_display          = $a['woo_sales_display'];
		$woo_sales_color            = $a['woo_sales_color'];

		return $this->read_tickers( $ticker1_color, $this->html_text( $ticker1_text ), $ticker1_url, $ticker2_color, $this->html_text( $ticker2_text ), $ticker2_url, $ticker3_color, $this->html_text( $ticker3_text ), $ticker3_url, $sticky_posts_display, $sticky_posts_title_color, $sticky_posts_content_color, $woo_sales_display, $woo_sales_color );
	}

	/** ==================================================
	 * Html to text
	 *
	 * @param string $html  html.
	 * @return string $text
	 * @since 1.00
	 */
	public function html_text( $html ) {

		$text = wp_strip_all_tags( $html );
		$text = str_replace( array( "\r", "\n" ), '', $text );

		return $text;
	}

	/** ==================================================
	 * Load Marquee Style
	 *
	 * @since 2.00
	 */
	public function load_styles() {
		$speed = $this->simpleticker_option['speed'];
		wp_enqueue_style( 'simpleticker', plugin_dir_url( __DIR__ ) . 'css/marquee.css', array(), '1.00' );
		$css = '.marquee > .marquee-inner { animation-duration: ' . $speed . 's; }';
		wp_add_inline_style( 'simpleticker', $css );
	}
}
