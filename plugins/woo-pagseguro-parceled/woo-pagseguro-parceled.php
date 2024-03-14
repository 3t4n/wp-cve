<?php
/*---------------------------------------------------------
Plugin Name: PagSeguro Parceled for WooCommerce
Plugin URI: https://wordpress.org/plugins/woo-pagseguro-parceled/
Author: carlosramosweb
Author URI: http://plugins.criacaocriativa.com
Donate link: https://donate.criacaocriativa.com
Description: Ativa a exibição dos resultados de parcelamento sem juros e com, sistema do pagseguro. Shortcodes [product_parceled_single] a tabela e o [product_parceled_loop] o preço no loop dos produtos.
Text Domain: wc-pagseguro-parceled
Domain Path: /languages/
Version: 1.8.5
Requires at least: 3.5.0
Tested up to: 6.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html 
------------------------------------------------------------*/

/**
 * Calcular o valor dos produtos em 12x parcelas sem juros.
 */ 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Sair se for acessado diretamente.
}

/**
 * Class WC_PagSeguro_Parceled.
 */
if ( ! class_exists( 'WC_PagSeguro_Parceled' ) ) {
		
	class WC_PagSeguro_Parceled {		
		/*
		 * Inicia tudo
		 * Aqui são adicionado os actions e filter para o sistema WooCommerce
		 * Pega os dados do Plugin PagSeguro
		 * Copia ou não o arquivo automaticamente
		 */
		public function __construct() {	
					
			// Cadastra as infos padrão para o sistema rodar
			register_activation_hook( __FILE__, array( $this, 'activate_plugin' ) );
			
			// Verifica com está instalado.	
			$active_plugins = get_option( 'active_plugins' );
			add_action( 'init', array( $this, 'localise' ) );
			$pagseguro_settings = get_option( 'woo_pagseguro_parceled_settings' );

			$order_loop = esc_attr($pagseguro_settings['order_loop'] );
			$order_single = esc_attr($pagseguro_settings['order_single'] );

			if ( empty( $order_loop ) && empty( $order_single ) ) {
				add_action( 'woocommerce_after_shop_loop_item',  array( $this, 'wc_product_parceled_loop' ), 10 );
				add_action( 'woocommerce_single_product_summary',  array( $this, 'wc_product_parceled_single_product' ), 11 );
			} else {
				add_action( 'woocommerce_after_shop_loop_item',  array( $this, 'wc_product_parceled_loop' ), $order_loop );
				add_action( 'woocommerce_single_product_summary',  array( $this, 'wc_product_parceled_single_product' ), $order_single );
			}
			add_action( 'woocommerce_cart_totals_after_order_total',  array( $this, 'wc_product_parceled_cart' ), 20 );
			//add_action( 'woocommerce_after_variations_form',  array( $this, 'wc_product_parceled_single_product_variations' ), 10 );
			add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'plugin_action_links_settings' ) );
			
			// Chama o arquivo de administração
			self::includes_once();
	
			if ( ! in_array( 'woocommerce/woocommerce.php', $active_plugins ) ) {
				add_action( 'admin_notices', array( $this, 'wc_plugin_failure_notice' ) );
			}

			add_shortcode( 'product_parceled_loop', array( $this, 'wc_shortcode_parceled_loop' ) );
			add_shortcode( 'product_parceled_single', array( $this, 'wc_shortcode_parceled_single_product' ) );	

			if (is_admin()) {
				$pagseguro_settings = array();
				$pagseguro_settings = get_option( 'woo_pagseguro_parceled_settings' );
				if (!isset($pagseguro_settings['title'])) {
					$pagseguro_settings['title'] = 'PagSeguro';
					update_option( "woo_pagseguro_parceled_settings", $pagseguro_settings );
				}
				if (!isset($pagseguro_settings['fees'])) {
					$pagseguro_settings['fees'] = 2.99;
					update_option( "woo_pagseguro_parceled_settings", $pagseguro_settings );
				}
				if (!isset($pagseguro_settings['show_installment'])) {
					$pagseguro_settings['show_installment'] = 'yes';
					update_option( "woo_pagseguro_parceled_settings", $pagseguro_settings );
				}

			}		
		}
		
			
		/**	
		 * Função para tradução
		 */
		public static function localise() {
			load_plugin_textdomain( 'wc-pagseguro-parceled', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}
		
		/**
		 * Activa o plugin no site e salva as infonações no banco de dados
		 */
		public static function activate_plugin() {			
			add_option( 'Activated_Plugin', 'woo-pagseguro-parceled' );	  
			if ( is_admin() && get_option( 'Activated_Plugin' ) == 'woo-pagseguro-parceled' ) {				
				$pagseguro_settings = array(
					'enabled'						=> 'yes',
					'title'							=> 'PagSeguro',
					'show_installment'				=> 'yes',
					'installment_table_single'		=> 'yes',
					'installment_loop_single'		=> 'yes',
					'installment_loop_product'		=> 'yes',
					'installment_text_cart'			=> 'yes',
					'installment_single_product'	=> 'yes',
					'fees'							=> 2.99,
					'minimum_installment'			=> '5',
					'installment' 					=> '1',
					'sales_up' 						=> '5',
					'order_loop' 					=> '10',
					'order_single' 					=> '11',
					'installment_extra' 			=> '',
					'installment_extra_factor' 		=> '1',
					'installment_extra_value' 		=> '0',
					'code_css_archive_product' 		=> self::code_css_archive_product(),
					'code_css_single_product' 		=> self::code_css_single_product(),
					'code_css_page_cart' 			=> self::code_css_page_cart(),
				);				
				update_option( 'woo_pagseguro_parceled_settings', $pagseguro_settings, 'yes' );
			}
		}
		
		/**
		 * Código CSS Page Archive Product
		 */
		public function code_css_archive_product() { 
			return '.p-woo-pagseguro-price { display:block; margin:5px 0; padding:8px 0; border-top:1px solid #b78789; border-radius:0 0 5px 5px; background-color:#f9e6e7; }.p-woo-pagseguro-installment { text-align:center; font-size:12px; line-height:13px; }.p-woo-pagseguro-installment span { font-size:14px; font-weight:700; color:#4c87c1; }';
		}
		
		/**
		 * Código CSS Page Single Product
		 */
		public function code_css_single_product() { 
			return '.price { margin-bottom:0px; }.p-woo-pagseguro-price { display:block; margin:0 0 5px; padding:8px 10px; border-top:1px solid #b78789; border-radius:0 0 5px 5px; background-color:#f9e6e7; }.p-woo-pagseguro-installment { display:block; padding:0 10px; font-size:12px; line-height:15px; }.p-woo-pagseguro-installment span { font-size:14px; font-weight:700; color:#4c87c1; }.box-parceled { display:block; text-align:left; margin:0 0 5px; padding:0; border:1px solid #c1c1c1; border-radius:5px; background-color:#f1f1f1; }.box-parceled h2 { display:block; background:#dfdfdf; border-bottom:1px solid #c1c1c1; font-size:14px; text-transform:uppercase; text-align:center; padding:5px 0; margin:0; }.box-parceled .left { display:block; float: left; width:49.8%; border-right:1px solid #c1c1c1; }.box-parceled .right { display:block; float:right; width:49.8%; border-right:0; }.box-parceled .span-woo-pagseguro-installments { display: block; padding:5px 10px; font-size:12px; text-align:center; }.box-parceled .span-woo-pagseguro-installments.color { background:#FFF; }.box-parceled .clear { clear:both; }';
		}
		
		/**
		 * Código CSS Page Cart
		 */
		public function code_css_page_cart() { 
			return '.p-woo-pagseguro-price { margin:0; padding:8px 10px; border-top:1px solid #b78789; border-radius:0 0 5px 5px; background-color:#f9e6e7; }.p-woo-pagseguro-installment { font-size:12px; line-height:15px; text-align:center; }';
		}
		
		
		/**
		 * Includes
		 * Página de Administrativa
		 */
		public function includes_once() {
			@include_once( plugin_dir_path( __FILE__ ) . 'woo-pagseguro-parceled-admin.php' );
		}
		
		/**
		 * Aviso de falta do plugin obrigatório
		 */
		public static function wc_plugin_failure_notice() { ?>
			<div class="clear"></div>
			<div class="error">
				<p>
					<?php echo __( '<strong>Erro Importante!</strong> Você ainda não tem o ', 'wc-pagseguro-parceled' ) ; ?>
					<a href="https://wordpress.org/plugins/woocommerce/" target="_blank">
						<?php echo __( '<strong>Plugin WooCommerce</strong>', 'wc-pagseguro-parceled' ); ?>
					</a> <?php echo __( 'instalado ou ativo!', 'wc-pagseguro-parceled' ); ?><hr/>
					<?php echo __( '<em>É requerimento obrigatório para uso do plugin Woo PagSeguro Parceled.</em>', 'wc-pagseguro-parceled' ) ; ?>				
				</p>
			</div>
			<style>#message{ display:none; }</style>
			<?php
		}	
		
		/*
		 * Mensagem de erro caso o sistema não consiga copiar o arquivo
		 */
		public static function wc_install_notice_erro() { ?>
			<div class="clear"></div>
			<div class="error">
				<p>
					<?php echo __( 'Houve um erro ao tentar finalizar a configuração do plugin! Faça a copia manualmente do arquivo.', 'wc-pagseguro-parceled' ) ; ?>
					<a href="https://wordpress.org/plugins/woo-pagseguro-parceled/faq/" target="_blank">
					<?php echo __( 'Ajuda aqui.', 'wc-pagseguro-parceled' ); ?>
					</a>
				</p>
				<button type="button" class="notice-dismiss">
					<span class="screen-reader-text">
						<?php echo __( 'Descartar este aviso.', 'wc-pagseguro-parceled' ); ?>
					</span>
				</button>
			</div>
			<?php
		}
	
		/*
		 * Link para configurar o plugin após sua instalação com sucesso.
		 */
		public static function plugin_action_links_settings( $links ) {
			$action_links = array(
				'settings'	=> '<a href="' . esc_url( admin_url( 'admin.php?page=wc-pagseguro-parceled-admin' ) ) . '" title="'. __( 'Configurar Plugin', 'wc-pagseguro-parceled' ) .'" class="error">'. __( 'Configurar', 'wc-pagseguro-parceled' ) .'</a>',
				'donate' 	=> '<a href="' . esc_url( 'https://donate.criacaocriativa.com') . '" title="'. __( 'Doação Plugin', 'wc-pagseguro-parceled' ) .'" class="error">'. __( 'Doação', 'wc-pagseguro-parceled' ) .'</a>',
			);
	
			return array_merge( $action_links, $links );
		}
	 
		/*
		 * Pega os dados do sistema (produto e configuração)
		 * Faz o Calculo retirando o parcelamento configurado com o valor do produto
		 */
		public function wc_product_parceled() {
			$product = wc_get_product();
			$pagseguro_settings = get_option( 'woo_pagseguro_parceled_settings' );
			$args = array();
			
			$sales_up 				= esc_attr( $pagseguro_settings['sales_up'] );
			$installment 			= esc_attr( $pagseguro_settings['installment'] );
			$minimum_installment 	= esc_attr( $pagseguro_settings['minimum_installment'] );

			if ( $sales_up > wc_get_price_including_tax( $product, $args ) ) {
				$installment = 1;
			}
			
			if ( wc_get_price_including_tax( $product, $args ) ) {
				
				$value =  wc_get_price_including_tax( $product, $args ) / $installment;
				
				while( ( wc_get_price_including_tax( $product, $args ) / $installment ) < $minimum_installment && $installment > 1 ) {
					$installment--;
				}
				return wc_price( $this->add_price_extra_parceled( wc_get_price_including_tax( $product, $args ) / $installment, 1 ) );
			}
		}
		
		/*
		 * Faz o Calculo retirando o parcelamento configurado com o valor do produto.
		 * No caso do valor da parcela for menor que minimum_installment referente a configuração do plugin
		 */
		public function wc_product_parceled_installment( $parceled, $get_price ) {
			$pagseguro_settings = get_option( 'woo_pagseguro_parceled_settings' );

			$installment 			= esc_attr( $pagseguro_settings['installment'] );
			$minimum_installment 	= esc_attr( $pagseguro_settings['minimum_installment'] );
				
			$installment = $parceled;
			if( $installment > 0 && $get_price >= $minimum_installment ) {	
				
				while( ( $get_price / $installment ) < $minimum_installment && $installment > 0) {
					$installment--;
				}
			}
			return $installment;	
		}
		
		/*
		 * Exibe na tela o resultado do calculo no loop dos produtos
		 */
		public function wc_product_parceled_loop($shortcode=false) {

			$args = array();
			$product = wc_get_product();
			$pagseguro_settings = get_option( 'woo_pagseguro_parceled_settings' );

			$enabled 					= esc_attr( $pagseguro_settings['enabled'] );
			$title 						= esc_attr( $pagseguro_settings['title'] );
			$show_installment			= esc_attr( $pagseguro_settings['show_installment'] );
			$installment_loop_product	= esc_attr( $pagseguro_settings['installment_loop_product'] );
			$installment 				= esc_attr( $pagseguro_settings['installment'] );
			$minimum_installment 		= esc_attr( $pagseguro_settings['minimum_installment'] );
			$sales_up 					= esc_attr( $pagseguro_settings['sales_up'] );
			$code_css_archive_product 	= esc_attr( $pagseguro_settings['code_css_archive_product'] );
			
			if( isset( $enabled ) && $enabled == "yes" && $installment_loop_product == "yes" || isset( $enabled ) && $enabled == "yes" && $shortcode) {
				if ( isset( $installment ) && $installment > 0 && wc_get_price_including_tax( $product, $args ) > 0 ) { 
				echo "<style>" . $code_css_archive_product . "</style>";
				
					if ( $sales_up > wc_get_price_including_tax( $product, $args ) ) {
						$installment = 1;
					}
				?>	
				<div class="p-woo-pagseguro-price">
					<div class="p-woo-pagseguro-installment">        
					<?php 
					if (wc_get_price_including_tax( $product, $args ) > $minimum_installment ) {
						$wc_product_parceled_installment = $this->wc_product_parceled_installment( $installment, wc_get_price_including_tax( $product, $args ) );
					} else {
						$wc_product_parceled_installment = 1;
					}
					printf(__('Ou em até <span>%sx</span> de <span>%s</span>', 'wc-pagseguro-parceled' ),  $wc_product_parceled_installment, $this->wc_product_parceled() ); ?><br/>
					<strong><?php printf( __( 'Sem Juros - %s', 'wc-pagseguro-parceled'), $title ); ?></strong>
					</div>
				</div>
				<?php
				}
			}
		}

		/*
		 * Adiciona o valor fixo ou porcentagem extra
		 */
		public function add_price_extra_parceled( $price, $start ) {
			$pagseguro_settings = get_option( 'woo_pagseguro_parceled_settings' );

			$installment_extra 			= esc_attr( $pagseguro_settings['installment_extra'] );
			$installment_extra_value 	= esc_attr( $pagseguro_settings['installment_extra_value'] );
			$installment_extra_factor 	= esc_attr( $pagseguro_settings['installment_extra_factor'] );

			if ( $installment_extra > 0 ) {
				if ( $start == 1 && $installment_extra == 1 || $start == 2 && $installment_extra == 2 || $installment_extra == 3 ) {
					if ( $installment_extra_value > 0 ) {						
						if ( $installment_extra_factor == 1 ) {
							$price = $price + ( $price / 100 * $installment_extra_value );
						} elseif ( $installment_extra_factor == 2 ) {
							$price = ( $price + $installment_extra_value );
						}
					}
				}
			}			
			return $price;
		}
		
		/*
		 * Exibe na tela o resultado do calculo no loop dos produtos
		 */
		public function price_parceled( $price, $installments ) {
			$pagseguro_settings = get_option( 'woo_pagseguro_parceled_settings' );
			$fees = (isset($pagseguro_settings['fees'])) ? esc_attr( $pagseguro_settings['fees'] ) : 2.99;			
			$value_total = intval( $price );			
			$fees_cf = $fees / 100 ;
			$cf = $fees_cf / ( 1-( 1 / ( ( $fees_cf + 1 ) ** intval( $installments ) ) ) );
			$total = $this->round_up( $cf * $value_total, 2 );			
			return $total;
		}
		
		/*
		 * Exibe na tela o resultado do calculo no loop dos produtos
		 */
		public function round_up( $value, $places = 0 ) {
			if ( $places < 0 ) { 
				$places = 0;
			}
			$mult = pow( 10, $places );
			
			return ceil( $value * $mult ) / $mult;
		}
		
		/*
		 * Exibe na tela o resultado do calculo no loop dos produtos
		 */
		public function woo_product_box_parceled_single_product( $price, $start, $installment ) {
			$pagseguro_settings = get_option( 'woo_pagseguro_parceled_settings' );

			$show_installment	 = esc_attr( $pagseguro_settings['show_installment'] );
			$minimum_installment = esc_attr( $pagseguro_settings['minimum_installment'] );
			
			if ( $price > 0) {				
				if ( $installment > 0 )  {
					$j = 0;
					for ( $i = 0; $i <= 11; $i++ ) {
						if ( $start == 'left' ) {
							// left
							if ($show_installment == 'yes') {
								if ( ( $price / ( $i + 1 ) ) >= $minimum_installment && ( $i % 2 ) == 0 ) {
									$j++;
									$class = "";
									if ( ($i + 1) > $installment) { $installments = __( '<br/>com juros', 'wc-pagseguro-parceled' ); } else { $installments = __( '<br/><strong>*sem juros</strong>', 'wc-pagseguro-parceled' ); }
									if ( ( $j + 1 ) % 2 ) { $class = ""; } else { $class = "color"; }
									if ( ( $i + 1 ) <= $installment ) {
										echo '<span class="span-woo-pagseguro-installments ' . $class . '">' . ( $i + 1 ) . __( 'x de ', 'wc-pagseguro-parceled' ) . wc_price( $this->add_price_extra_parceled( $price / ($i + 1 ), 2 ) ) . ' ' . $installments . '</span>';
									}else{
										echo '<span class="span-woo-pagseguro-installments ' . $class . '">' . ( $i + 1 ) . __( 'x de ', 'wc-pagseguro-parceled' ) . wc_price( $this->add_price_extra_parceled( $this->price_parceled( $price, ( $i + 1 ) ), 1 ) ) . ' ' . $installments . '</span>';
									}
								} 
							} else if ( ($i + 1) <= $installment ){
								if ( ( $price / ( $i + 1 ) ) >= $minimum_installment && ( $i % 2 ) == 0 ) {
									$j++;
									$class = "";
									if ( ($i + 1) > $installment) { $installments = __( '<br/>com juros', 'wc-pagseguro-parceled' ); } else { $installments = __( '<br/><strong>*sem juros</strong>', 'wc-pagseguro-parceled' ); }
									if ( ( $j + 1 ) % 2 ) { $class = ""; } else { $class = "color"; }
									if ( ( $i + 1 ) <= $installment ) {
										echo '<span class="span-woo-pagseguro-installments ' . $class . '">' . ( $i + 1 ) . __( 'x de ', 'wc-pagseguro-parceled' ) . wc_price( $this->add_price_extra_parceled( $price / ($i + 1 ), 2 ) ) . ' ' . $installments . '</span>';
									}else{
										echo '<span class="span-woo-pagseguro-installments ' . $class . '">' . ( $i + 1 ) . __( 'x de ', 'wc-pagseguro-parceled' ) . wc_price( $this->add_price_extra_parceled( $this->price_parceled( $price, ( $i + 1 ) ), 1 ) ) . ' ' . $installments . '</span>';
									}
								} 
							}
							//
						} else if ( $start == 'right' ) {
							// right
							if ($show_installment == 'yes') {
								if ( ( $price / ( $i + 1 ) ) >= $minimum_installment && ( $i % 2 ) != 0 ) {
									$j++;
									$class = "";									
									if ( ($i + 1) > $installment ) { $installments = __( '<br/>com juros', 'wc-pagseguro-parceled' ); } else { $installments = __( '<br/><strong>*sem juros</strong>', 'wc-pagseguro-parceled' ); }
									if ( ( $j + 1 ) % 2 ) { $class = ""; } else { $class = "color"; }
									if ( ( $i + 1 ) <= $installment ) {
										echo '<span class="span-woo-pagseguro-installments ' . $class . '">' . ( $i + 1 ) . __( 'x de ', 'wc-pagseguro-parceled' ) . wc_price( $this->add_price_extra_parceled( $price / ( $i + 1 ), 2 ) ) . ' ' . $installments . '</span>';
									}else{
										echo '<span class="span-woo-pagseguro-installments ' . $class . '">' . ( $i + 1 ) . __( 'x de ', 'wc-pagseguro-parceled' ) . wc_price( $this->add_price_extra_parceled( $this->price_parceled( $price, ( $i + 1 ) ), 1 ) ) . ' ' . $installments . '</span>';
									}
								}
							} else if ( ($i + 1) <= $installment ){
								if ( ( $price / ( $i + 1 ) ) >= $minimum_installment && ( $i % 2 ) != 0 ) {
									$j++;
									$class = "";									
									if ( ($i + 1) > $installment ) { $installments = __( '<br/>com juros', 'wc-pagseguro-parceled' ); } else { $installments = __( '<br/><strong>*sem juros</strong>', 'wc-pagseguro-parceled' ); }
									if ( ( $j + 1 ) % 2 ) { $class = ""; } else { $class = "color"; }
									if ( ( $i + 1 ) <= $installment ) {
										echo '<span class="span-woo-pagseguro-installments ' . $class . '">' . ( $i + 1 ) . __( 'x de ', 'wc-pagseguro-parceled' ) . wc_price( $this->add_price_extra_parceled( $price / ( $i + 1 ), 2 ) ) . ' ' . $installments . '</span>';
									}else{
										echo '<span class="span-woo-pagseguro-installments ' . $class . '">' . ( $i + 1 ) . __( 'x de ', 'wc-pagseguro-parceled' ) . wc_price( $this->add_price_extra_parceled( $this->price_parceled( $price, ( $i + 1 ) ), 1 ) ) . ' ' . $installments . '</span>';
									}
								}
							}
							//
						}
						
					}
				}else{
						echo '<span class="span-woo-pagseguro-installments">' . __( '1x de ', 'wc-pagseguro-parceled' ) . wc_price( $price ) . __( '<br/> *sem juros</span>', 'wc-pagseguro-parceled' );
				}
	
			}
		}

		/*
		 * Chama a função para exibir o shortcode do detalhe de loop do produto
		 */
		public function wc_shortcode_parceled_loop() {
			$this->wc_product_parceled_loop(true);
		}

		/*
		 * Chama a função para exibir o shortcode da tabela de parcelas do produto
		 */
		public function wc_shortcode_parceled_single_product() {
			$this->wc_product_parceled_single_product(true);
		}
		
		/*
		 * Exibe na tela o calculo completo com os parcelamentos sem juros e com no página do produto
		 */
		public function wc_product_parceled_single_product($shortcode=false) {
			global $post, $product;	

			$product = wc_get_product();
			$args = array();
			$pagseguro_settings = get_option( 'woo_pagseguro_parceled_settings' );

			$enabled 					= esc_attr( $pagseguro_settings['enabled'] );
			$title 						= esc_attr( $pagseguro_settings['title'] );
			$installment 				= esc_attr( $pagseguro_settings['installment'] );
			$installment_table_single 	= esc_attr( $pagseguro_settings['installment_table_single'] );
			$installment_loop_single 	= esc_attr( $pagseguro_settings['installment_loop_single'] );
			$installment_single_product = esc_attr( $pagseguro_settings['installment_single_product'] );
			$minimum_installment 		= esc_attr( $pagseguro_settings['minimum_installment'] );
			$sales_up 					= esc_attr( $pagseguro_settings['sales_up'] );
			$code_css_single_product 	= esc_attr( $pagseguro_settings['code_css_single_product'] );

			if( isset( $variation_id ) && $variation_id > 0 ) {
				return $variation_id;				
			}
			
			if( isset( $enabled ) && $enabled == "yes" ) {
				
				if ( isset( $installment ) && $installment > 0 && wc_get_price_including_tax( $product, $args ) > 0 ) {
					echo "<style>" . $code_css_single_product . "</style>"; 
					
					if ( $sales_up > wc_get_price_including_tax( $product, $args ) ) {
						$installment = 1;
					}

					if( isset( $installment_loop_single ) && $installment_loop_single == "yes" ) {					
				?>		
				<div class="p-woo-pagseguro-price">
					<div class="p-woo-pagseguro-installment">
					<?php 
					$args = array();
					if ( wc_get_price_including_tax( $product, $args ) > $minimum_installment ) {
						$wc_product_parceled_installment = $this->wc_product_parceled_installment( $installment, wc_get_price_including_tax( $product, $args ) );
					} else {
						$wc_product_parceled_installment = 1;
					}
					printf(__( 'Ou em até <span>%sx</span> de <span>%s</span>', 'wc-pagseguro-parceled' ), $wc_product_parceled_installment, $this->wc_product_parceled() ); ?><br/>
					<span><strong><?php printf( __( 'Sem Juros - %s', 'wc-pagseguro-parceled'), $title ); ?></strong></span>
					</div>
				</div>
				<?php
					}
				}
				if ( $shortcode && wc_get_price_including_tax( $product, $args ) > $minimum_installment || isset( $installment_table_single ) && $installment_table_single == "yes" && wc_get_price_including_tax( $product, $args ) > $minimum_installment ) { ?>
					<div class="box-parceled">
						<h2><?php printf( __( 'Parcelamento %s', 'wc-pagseguro-parceled'), $title ); ?></h2>				
						<div class="left">
						<?php $this->woo_product_box_parceled_single_product( wc_get_price_including_tax( $product, $args ), 'left',  $this->wc_product_parceled_installment( $installment, wc_get_price_including_tax( $product, $args ) ) ); ?>
						</div>
						<div class="right">
						<?php if ( $this->wc_product_parceled_installment( $installment, wc_get_price_including_tax( $product, $args ) ) > 0 ) { ?>
						<?php $this->woo_product_box_parceled_single_product( wc_get_price_including_tax( $product, $args ), 'right',  $this->wc_product_parceled_installment( $installment, wc_get_price_including_tax( $product, $args ) ) ); ?>
						<?php } ?>
						</div>
						<div class="clear"></div>
					 </div>
					<?php
				}
			}	
		}
		
		/*
		 * Altera a tabela de parcelamento assim que for escolhido uma opção de produto
		 */
		public function wc_product_parceled_single_product_variations() {
			
			global $post, $product;
			$variations = $product->get_available_variations();
			
			// Infos Variaveis do produto
			foreach( $variations as $variation ) {
				$variation_id 			= esc_attr( $variation['variation_id'] );
				$display_price 			= esc_attr( $variation['display_price'] );
				$display_regular_price 	= esc_attr( $variation['display_regular_price'] );			
			}
			
			?>
			<script type="text/javascript">	
				jQuery( document ).ready( function() {
					var start_late;
					jQuery( ".variations" ).change(function() {	
						start_late = setTimeout(function (self) { 									
							jQuery( ".p-woo-pagseguro-price" ).attr( "style", "opacity: 0.5;" );	
							//jQuery( ".box-parceled" ).attr( "style", "opacity: 0.5;" );	
							//=>
							var variation_id = jQuery( ".variation_id" ).val();
							jQuery.ajax({
								type: 'POST',
								url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
								data: {
									'action': 'wc_product_parceled_single_product_show',
									'variation_id': variation_id,
								},
								success:function( response ) {
									if( response != '' ) {
										jQuery( ".p-woo-pagseguro-price" ).append( response );
										jQuery( ".p-woo-pagseguro-price" ).attr( "style", "opacity: 1;" );
										//jQuery( ".box-parceled" ).append( response );
										//jQuery( "#loading35" ).remove();
									}
								}
							});
							//=>
						}, 400, this);
					});
				});			
				//jQuery( "#code_css_archive_product" ).val('');				
			</script>
			<?php			
		}
		
		/*
		 * Exibe na tela uma pequena mensagem de marketing na página do carrinho
		 */
		public function wc_product_parceled_cart() {		
			$product = wc_get_product();
			$pagseguro_settings = get_option( 'woo_pagseguro_parceled_settings' );

			$enabled					= esc_attr( $pagseguro_settings['enabled'] );
			$title						= esc_attr( $pagseguro_settings['title'] );
			$installment				= esc_attr( $pagseguro_settings['installment'] );
			$installment_text_cart		= esc_attr( $pagseguro_settings['installment_text_cart'] );
			$installment_single_product	= esc_attr( $pagseguro_settings['installment_single_product'] );
			$code_css_page_cart			= esc_attr( $pagseguro_settings['code_css_page_cart'] );
			
			if( isset( $enabled ) && $enabled == "yes" ) {			
				if ( isset( $installment_text_cart ) && $installment_text_cart == "yes" && isset( $installment ) && $installment > 1 ) { 
				?>
				<tr>
					<th colspan="2" class="p-woo-pagseguro-price">
						<?php echo "<style>" . $code_css_page_cart . "</style>"; ?>
						<div class="p-woo-pagseguro-installment">
							<?php printf( __( '* Pague suas compras em até<br/> %sx sem juros com %s em nossa loja.', 'wc-pagseguro-parceled'), $installment, $title ); ?>
						</div>
					</th>
				</tr>
				<?php
				}
			}
		}

		/*
		 * Limpa possivel campo de array que não existe no sistema
		 */
		public function wc_sanitize_fields_array_settings( $array_settings ) {

			$pagseguro_settings = array(
				'enabled',
				'title',
				'show_installment',
				'installment_single_product',
				'minimum_installment',
				'installment',
				'sales_up',
				'order_loop',
				'order_single',
				'installment_extra',
				'installment_extra_factor',
				'installment_extra_value',
				'code_css_archive_product',
				'code_css_single_product',
				'code_css_page_cart',
				'installment_table_single',
				'installment_loop_single',
				'installment_loop_product',
				'installment_text_cart'
			);

			foreach ( $array_settings as $key => $field ) {
				if ( ! in_array( $key, $pagseguro_settings )  ) {
					unset( $array_settings[$key] );
				}
			}
			return $array_settings;
		}
		
	}

	/**
	 * Ativar a class
	 */
	new WC_PagSeguro_Parceled();
}
