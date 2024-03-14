<?php
/*
---------------------------------------------------------
Plugin Name: Payment On Delivery for WooCommerce 
Plugin URI: https://wordpress.org/plugins/woo-payment-on-delivery/
Author: carlosramosweb
Author URI: https://www.criacaocriativa.com
Donate link: https://donate.criacaocriativa.com
Description: Receba em dinheiro, cheque, no cartão de crédito, débito e ou cartão alimentação (voucher) no ato da entrega.
Text Domain: woo-payment-on-delivery
Domain Path: /languages/
Version: 1.4.0
Requires at least: 3.5.0
Tested up to: 6.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html 
------------------------------------------------------------
*/

/*
 * Sair se o arquivo for acessado diretamente
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/*
 * Cash On Delivery Gateway for WooCommerce
 */
function init_woo_payment_on_delivery_gateway_class() {	
	if ( !class_exists( 'WC_Payment_Gateway' ) ) return;
	
	class Woo_Payment_On_Delivery extends WC_Payment_Gateway {		
		public function __construct() {	
		
			// Carrega o texto do plugin no site.
			add_action( 'plugins_loaded', array( $this, 'woo_load_plugin_textdomain' ) );
			
			// Inicializar pedido
			$this->order = new WC_Order( absint( get_query_var( 'order-pay' ) ) );	
					
			// Variáveis globais
			$this->id                 = 'woo_payment_on_delivery';
			$this->has_fields         = true;
			$this->enabled 			=  "no";
			$this->method_title       = __( 'Delivery', 'woo-payment-on-delivery' );
			$this->method_description = __( 'Adicione nova forma de pagamento na entrega.', 'woo-payment-on-delivery' );
			$this->supports           = array(
				'products',
			);
			
			// Carregue as configurações.
			$this->init_form_fields();
			$this->init_settings();	
					
			// Definir variáveis do conjunto de usuários
			$this->title 		= $this->get_option( 'title' );
			$this->description 	= $this->get_option( 'description' );
			$this->status 		= $this->get_option( 'status' );
			$this->note_enabled	= $this->get_option( 'note_enabled' );
			$this->paymenttypes	= $this->get_option( 'paymenttypes' );
			
			// Avisos
			$this->notice_money    	= $this->get_option( 'notice_money' );
			$this->pix_key  		= $this->get_option( 'pix_key' );
			$this->pix_description  = $this->get_option( 'pix_description' );
			$this->debit_card    	= $this->get_option( 'debit_card' );
			$this->credit_card    	= $this->get_option( 'credit_card' );
			$this->voucher_card		= $this->get_option( 'voucher_card' );
			
			// As bandeiras desativadas
			$this->debit_card_disable    	= $this->get_option( 'debit_card_disable' );
			$this->credit_card_disable    	= $this->get_option( 'credit_card_disable' );
			$this->voucher_card_disable    	= $this->get_option( 'voucher_card_disable' );
			
			// Ativo se entrega
			$this->enable_for_methods_all 	= $this->get_option( 'enable_for_methods_all' );
       		$this->enable_for_methods		= $this->get_option( 'enable_for_methods', array() );
			
			// Salvar configurações
			if ( is_admin() ) {				
				add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );				
			}		
				
			// Adicionar ganchos
			add_action( 'woocommerce_card_delivery', array( $this, 'payment_page' ) );	
		}
		
		// Opções de administração
		public function admin_options() {				
			echo '<h3> ' . __( 'Pagamento no Delivery', 'woo-payment-on-delivery' ) . ' </h3>';	
			echo '<table class="form-table" id="settings-block">';							
			$this->generate_settings_html();
			echo '</table>';
		}
		
		// Inicializar campos
		public function init_form_fields() {			
			$this->form_fields = include( 'includes/settings-form-fields.php' );
		}
		
		// Processo de pagamento
		public function process_payment( $order_id ) {
			global $woocommerce;
			
			// Obter variáveis
			if( isset( $_REQUEST['card_machine_indicated'] ) ) {
				$card_machine_indicated = $_REQUEST['card_machine_indicated'];
			}
			if( ! empty( $_REQUEST['flagcard_indicated'] ) or isset( $_REQUEST['flagcard_indicated'] ) ) {
				$flagcard_indicated = $_REQUEST['flagcard_indicated'];
			}
			if( isset( $_REQUEST['woo_cash_delivery'] ) ) {
				$woo_cash_delivery	= sanitize_text_field( $_REQUEST['woo_cash_delivery'] );
				$woo_cash_delivery 	= str_replace( " . ", "", $woo_cash_delivery );
				$woo_cash_delivery 	= str_replace( ",", ".", $woo_cash_delivery );
			}
			
			$card_machine_disable = "no";
			if( $card_machine_indicated == "debitcard" && $this->debit_card_disable == "yes") {
				$card_machine_disable = "yes";
			}
			if( $card_machine_indicated == "creditcard" && $this->credit_card_disable == "yes") {
				$card_machine_disable = "yes";
			}
			if( $card_machine_indicated == "voucher" && $this->voucher_card_disable == "yes") {
				$card_machine_disable = "yes";
			}
			
			// Se vazio
			if( ! isset( $error_message ) ) {
				$error_message = "";
			}
			if ( $card_machine_indicated == '' && $card_machine_disable == "no" ) {	
				wc_add_notice( __('Erro! Para fazer seu pedido, selecione uma forma de pagamento.', 'woo-payment-on-delivery' ) . $error_message, 'error' );
				return;
				
			} else if ( $card_machine_indicated == 'money' ) { // Se for dinheiro
			
				if( $woo_cash_delivery < $woocommerce->cart->total && $woo_cash_delivery != '0'

				or $woo_cash_delivery < $woocommerce->cart->total && $woo_cash_delivery == '' ) {										
					wc_add_notice( sprintf( __('O valor do troco precisa ser maior que o total do seu pedido que é de <strong>%s %s.</strong>', 'woo-payment-on-delivery', 'woo-payment-on-delivery' ), get_woocommerce_currency_symbol(), number_format( $woocommerce->cart->total, 2, ',', '') ) . $error_message, 'error' );
					return;
					
				} else {
					
					$order = new WC_Order( $order_id );	
					$current_user = wp_get_current_user();
					$order->update_status( $this->status, __('Aguardando pagamento em dinheiro.', 'woo-payment-on-delivery') );
					
					// Adicionar nota de encomenda
					if( $woo_cash_delivery == 0 ) {
						$order_note .= __( 'Informou que não vai precisar de troco.', 'woo-payment-on-delivery' );
					} else {
						$troco = $woo_cash_delivery - $woocommerce->cart->total;
						$order_note .= __( 'Leve troco para ', 'woo-payment-on-delivery' );
						$order_note .= number_format( esc_attr( $woo_cash_delivery ), 2, ',', '' );
						$order_note .= "\n Valor do troco: " . number_format( esc_attr( $troco ), 2, ',', '' );
					}

					// Adiciona a descrição do pagamento também na nota do pedido
					// Campo do Post @post_excerpt
					if ( $this->note_enabled == 'yes' ) {
						$order_post 	= get_post( $order_id );
						$order_note 	= "Detalhes do Pagamento:\n" . $order_note . "\n\n";
						$order_excerpt	= $order_post->post_excerpt;
						if ( $order_excerpt != "" ) {
							$order_new_note = $order_note . $order_excerpt;
						} else {
							$order_new_note = $order_note;
						}
						$note_data 		= array(
						 	'ID' 			=> $order_id,
						 	'post_excerpt'	=> $order_new_note,
						);						 
						wp_update_post( $note_data );
					}

					$order->add_order_note( $order_note, $current_user->display_name );
					
					// Carrinho Vazio WooCommerce
					$woocommerce->cart->empty_cart();
							
					return array(
						'result'    => 'success',
						'redirect'  => $this->get_return_url( $order )
					);
				}
			} else if ( $card_machine_indicated == 'pix' ) {// Se para o Paycheck
			
				$order = new WC_Order( $order_id );	
				$current_user = wp_get_current_user();
				$order->update_status( $this->status, __( 'Aguardando pagamento no PIX.', 'woo-payment-on-delivery' ) );
				
				// Adicionar nota de encomenda
				if( ! empty( $card_machine_indicated ) ) {
					$order_note .= __( 'O pagamento será no PIX!', 'woo-payment-on-delivery' );
					$order_note .= "\n";
					$order_note .= __( $this->pix_key, 'woo-payment-on-delivery' );
					$order_note .= "\n\n";
				}

				// Adiciona a descrição do pagamento também na nota do pedido
				// Campo do Post @post_excerpt
				if ( $this->note_enabled == 'yes' ) {
					$order_post 	= get_post( $order_id );
					$order_note 	= $order_note;
					$order_excerpt	= $order_post->post_excerpt;
					if ( $order_excerpt != "" ) {
						$order_new_note = $order_note . $order_excerpt;
					} else {
						$order_new_note = $order_note;
					}
					$note_data 		= array(
					 	'ID' 			=> $order_id,
					 	'post_excerpt'	=> $order_new_note,
					);						 
					wp_update_post( $note_data );
				}

				$order->add_order_note( $order_note, $current_user->display_name );
				
				// Carrinho Vazio WooCommerce
				$woocommerce->cart->empty_cart();
						
				return array(
					'result'    => 'success',
					'redirect'  => $this->get_return_url( $order )
				);
				
			} else if ( $card_machine_indicated == 'paycheck' ) {// Se para o Paycheck
			
				$order = new WC_Order( $order_id );	
				$current_user = wp_get_current_user();
				$order->update_status( $this->status, __( 'Aguardando pagamento em cheque.', 'woo-payment-on-delivery' ) );
				
				// Adicionar nota de encomenda
				if( ! empty( $card_machine_indicated ) ) {
					$order_note .= __( 'O pagamento será em cheque!', 'woo-payment-on-delivery' );
				}

				// Adiciona a descrição do pagamento também na nota do pedido
				// Campo do Post @post_excerpt
				if ( $this->note_enabled == 'yes' ) {
					$order_post 	= get_post( $order_id );
					$order_note 	= "Detalhes do Pagamento:\n" . $order_note . "\n\n";
					$order_excerpt	= $order_post->post_excerpt;
					if ( $order_excerpt != "" ) {
						$order_new_note = $order_note . $order_excerpt;
					} else {
						$order_new_note = $order_note;
					}
					$note_data 		= array(
					 	'ID' 			=> $order_id,
					 	'post_excerpt'	=> $order_new_note,
					);						 
					wp_update_post( $note_data );
				}

				$order->add_order_note( $order_note, $current_user->display_name );
				
				// Carrinho Vazio WooCommerce
				$woocommerce->cart->empty_cart();
						
				return array(
					'result'    => 'success',
					'redirect'  => $this->get_return_url( $order )
				);
				
			} else if ( $card_machine_indicated == 'multibanco' ) {// Se para o Paycheck
			
				$order = new WC_Order( $order_id );	
				$current_user = wp_get_current_user();
				$order->update_status( $this->status, __( 'Aguardando pagamento em MultiBanco.', 'woo-payment-on-delivery') );
				
				// Adicionar nota de encomenda
				if( ! empty( $card_machine_indicated ) ) {
					$order_note .= __( 'O pagamento será em MultiBanco!', 'woo-payment-on-delivery' );
				}

				// Adiciona a descrição do pagamento também na nota do pedido
				// Campo do Post @post_excerpt
				if ( $this->note_enabled == 'yes' ) {
					$order_post 	= get_post( $order_id );
					$order_note 	= "Detalhes do Pagamento:\n" . $order_note . "\n\n";
					$order_excerpt	= $order_post->post_excerpt;
					if ( $order_excerpt != "" ) {
						$order_new_note = $order_note . $order_excerpt;
					} else {
						$order_new_note = $order_note;
					}
					$note_data 		= array(
					 	'ID' 			=> $order_id,
					 	'post_excerpt'	=> $order_new_note,
					);						 
					wp_update_post( $note_data );
				}

				$order->add_order_note( $order_note, $current_user->display_name );
				
				// Carrinho Vazio WooCommerce
				$woocommerce->cart->empty_cart();
						
				return array(
					'result'    => 'success',
					'redirect'  => $this->get_return_url( $order )
				);
				
			} else if ( !empty($card_machine_indicated) && $card_machine_indicated == 'debitcard'
			or ! empty( $card_machine_indicated) && $card_machine_indicated == 'creditcard'
			or ! empty( $card_machine_indicated) && $card_machine_indicated == 'voucher' ) { // If for Card
				
				if ( empty( $flagcard_indicated ) && $card_machine_disable == "no") {
					wc_add_notice( __('Erro! Para fazer seu pedido, selecione uma bandeira.', 'woo-payment-on-delivery') . $error_message, 'error' );
					return;
				} else {
			
					$order = new WC_Order( $order_id );	
					$current_user = wp_get_current_user();
					$order->update_status( $this->status, __( 'Aguardando pagamento no cartão. \n', 'woo-payment-on-delivery' ) );
					
					// Adicionar cartão de nota de encomenda
					if( ! empty( $card_machine_indicated ) ) {
						$order_note .= __( 'Lever máquina de ', 'woo-payment-on-delivery' );
						$order_note .= esc_attr( woo_check_the_name_card_on( $card_machine_indicated ) );
						if( $flagcard_indicated != "" ) {
							$order_note .= ' - Bandeira ';
							$order_note .= esc_attr( $flagcard_indicated );
						}
					} else {
						$order_note .= __( 'Erro! O tipo de cartão não foi indicado! ', 'woo-payment-on-delivery' );
					}

					// Adiciona a descrição do pagamento também na nota do pedido
					// Campo do Post @post_excerpt
					if ( $this->note_enabled == 'yes' ) {
						$order_post 	= get_post( $order_id );
						$order_note 	= "Detalhes do Pagamento:\n" . $order_note . "\n\n";
						$order_excerpt	= $order_post->post_excerpt;
						if ( $order_excerpt != "" ) {
							$order_new_note = $order_note . $order_excerpt;
						} else {
							$order_new_note = $order_note;
						}
						$note_data 		= array(
						 	'ID' 			=> $order_id,
						 	'post_excerpt'	=> $order_new_note,
						);						 
						wp_update_post( $note_data );
					}

					$order->add_order_note( $order_note, $current_user->display_name );
					
					get_post_meta( $order_id, '_payment_method_title', esc_attr( woo_check_the_name_card_on( $card_machine_indicated ) ) );
					
					// Carrinho Vazio WooCommerce
					$woocommerce->cart->empty_cart();
							
					return array(
						'result'    => 'success',
						'redirect'  => $this->get_return_url( $order )
					);	
				}
				
			} else {		
				// Erro 
				wc_add_notice( __('Ocorreu um erro inesperado. Entre em contato com o gerente desta loja.', 'woo-payment-on-delivery') . $error_message, 'error' );
				return;	
				
			}
		}			
		
		// Icone
		public function get_icon() {
			$icon = "";
			$icon = '<img src="' . plugins_url( '/woo-payment-on-delivery/images/icon-types-delivery.png', dirname(__FILE__) ) . '" alt="'.__( 'Pagamento no Delivery', 'woo-payment-on-delivery' ).'" /> ';	
				
			return apply_filters( 'woocommerce_gateway_icon', $icon, $this->id );
		}
		/**
		 * Retornar a descrição do gateway.
		 *
		 * @return string
		 */
		public function get_description() {
			$select_flag = "";
			$select_flag = __( 'Selecione uma bandeira', 'woo-payment-on-delivery' );			
			?>
            <script type="text/javascript">
            function k(i) {
            	var v = i.value.replace(/\D/g,'');
            	v = (v/100).toFixed(2) + '';
            	v = v.replace(".", ",");
            	v = v.replace(/(\d)(\d{3})(\d{3}),/g, "$1.$2.$3,");
            	v = v.replace(/(\d)(\d{3}),/g, "$1.$2,");
            	i.value = v;
            }
			jQuery(document).ready(function(){
				jQuery("select[id=card_machine_indicated]").change(function() {
					jQuery("p.woo_payment_indicated").empty();
					switch(jQuery("#card_machine_indicated option:selected").val()) {
						case "money":
						    jQuery("p.woo_payment_indicated").append('<span>Precisa de troco?</span><label><input id="troco_sim" type="radio" name="troco" value="1"> Sim</label><label><input id="troco_nao" name="troco" type="radio" value="0" checked> Não</label><span id="troco-msg" style="display: none">Troco para quanto? </span><input id="woo_cash_delivery" class="input-text" type="hidden" style="padding:10px;" placeholder="0,00" name="woo_cash_delivery" value="0" onkeyup="k(this);" /><hr/><span><?php if( isset( $this->notice_money)) { echo $this->notice_money; } ?></span>');
							
							jQuery('#troco_nao, #troco_sim').change(function() {
						        var type = jQuery(this).val();
						        if(type == 0){
						        	document.getElementById('woo_cash_delivery').setAttribute('type','hidden');
						        	document.getElementById('woo_cash_delivery').setAttribute('value','0');
						        	document.getElementById('troco-msg').setAttribute('style','display: none');
						        
                                    
                                    var target_offset = jQuery(".woocommerce-terms-and-conditions-wrapper").offset();
                                    var target_top = target_offset.top;
                                    jQuery('html, body').animate({ scrollTop: target_top }, 0);
                                    
						        }else if(type == 1){
						        	document.getElementById('woo_cash_delivery').setAttribute('type','text');
						        	document.getElementById('woo_cash_delivery').setAttribute('value','');
						        	document.getElementById('troco-msg').setAttribute('style','display: block');
						        }
						    });
						    
						    
						break;
						case "pix":
						    jQuery("p.woo_payment_indicated").append('<span><?php if( isset( $this->pix_description ) ) { echo $this->pix_description; } ?></span>');				    
						break;
						case "paycheck":
							jQuery("p.woo_payment_indicated").append("<input id='woo_paycheck_delivery' type='hidden' name='woo_paycheck_delivery' value='paycheck'/><?php if( ! empty( $this->notice_paycheck)) { ?><hr/><span><?php if( isset( $this->notice_paycheck)) { echo $this->notice_paycheck; } ?></span><?php } ?>");
							break;
						<?php if (!empty($this->debit_card) && $this->debit_card_disable == "no") { ?>
						case "debitcard":
							jQuery("p.woo_payment_indicated").append("<select style='padding:10px;' id='flagcard_indicated' name='flagcard_indicated'><option value=''><?php echo $select_flag; ?></option><?php foreach ($this->debit_card as $debit_card) { echo "<option value='".$debit_card."'>".$debit_card."</option>"; } ?>");
							break;
						<?php } ?>
						<?php if (!empty($this->credit_card) && $this->credit_card_disable == "no") { ?>
						case "creditcard":
							jQuery("p.woo_payment_indicated").append("<select style='padding:10px;' id='flagcard_indicated' name='flagcard_indicated'><option value=''><?php echo $select_flag; ?></option><?php foreach ($this->credit_card as $credit_card) { echo "<option value='".$credit_card."'>".$credit_card."</option>"; } ?>");
							break;
						<?php } ?>
						<?php if (!empty($this->voucher_card)  && $this->voucher_card_disable == "no") { ?>
						case "voucher":
							jQuery("p.woo_payment_indicated").append("<select style='padding:10px;' id='flagcard_indicated' name='flagcard_indicated'><option value=''><?php echo $select_flag; ?></option><?php foreach ($this->voucher_card as $voucher_card) { echo "<option value='".$voucher_card."'>".$voucher_card."</option>"; } ?>");
							break;
						<?php } ?>
						<?php if (!empty($this->multibanco)) { ?>
						case "multibanco":
							jQuery("p.woo_payment_indicated").append("<input id='woo_paycheck_delivery' type='hidden' name='woo_multibanco_delivery' value='multibanco'/>");
							break;
						<?php } ?>
						default: "";
					}
				});
			});
			</script>
            <?php	
			$select_the_type = 	__( 'Selecione um pagamento', 'woo-payment-on-delivery' );
			$default_fields = '';
			$default_fields .= '<p class="form-row form-row-wide hide-if-token">
			<select style="padding:8px 10px; margin-bottom:10px;" id="card_machine_indicated" name="card_machine_indicated">';
			$default_fields .= '<option value="">'.$select_the_type.'</option>';
			if( isset( $this->paymenttypes)) {
				foreach ($this->paymenttypes as $paymenttypes) {
					$default_fields .= '<option value="'.$paymenttypes.'">'.woo_check_the_name_card_on($paymenttypes).'</option>';
				}
			}
			$default_fields .= '</select></p>';			
			$default_fields .= '<p class="form-row woo_payment_indicated"></p>';			
			$description = apply_filters( 'woocommerce_gateway_description', $this->description, $this->id );			
			return $description . $default_fields;
		}
		// =>
	}


	// Carregue o plugin do campo de texto para tradução.
	add_action( 'plugins_loaded', 'woo_load_plugin_textdomain' );
	function woo_load_plugin_textdomain() {
		load_plugin_textdomain( 'woo-payment-on-delivery', false, plugin_basename(__FILE__) . '/languages/' );
	}

	// Carrega o item de menu no admin página de plugins	
	function plugin_action_links_settings( $links ) {
		$action_links = array(
			'settings' => '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=woo_payment_on_delivery' ) . '" title="'.__( 'Settings', 'woo-payment-on-delivery' ).'" class="error">'.__( 'Settings', 'woo-payment-on-delivery' ).'</a>',
			'donate' => '<a href="' . 'http://donate.criacaocriativa.com' . '" title="'.__( 'Donation', 'woo-payment-on-delivery' ).'" class="error">'.__( 'Donation', 'woo-payment-on-delivery' ).'</a>',
		);

		return array_merge( $action_links, $links );
	}
	add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'plugin_action_links_settings' );	
	
	// Verifique o nome da entrega
	function woo_check_the_name_card_on( $paymenttypes ) {			
		$paymenttypes = strtolower($paymenttypes);
		switch ($paymenttypes) {
			case 'money':
				return __( 'Dinheiro', 'woo-payment-on-delivery' );
				break;
			case 'pix':
				return __( 'Pix', 'woo-payment-on-delivery' );
				break;
			case 'paycheck':
				return __( 'Paycheck', 'woo-payment-on-delivery' );
				break;
			case 'debitcard':
				return __( 'Cartão de Débito', 'woo-payment-on-delivery' );
				break;
			case 'creditcard':
				return __( 'Cartão de Crédito', 'woo-payment-on-delivery' );
				break;
			case 'voucher':
				return __( 'Voucher', 'woo-payment-on-delivery' );
				break;
			case 'multibanco':
				return __( 'MultiBanco', 'woo-payment-on-delivery' );
				break;
		}
	}
	
	// Adicionar gateway de pagamento personalizado
	function add_woo_payment_on_delivery_gateway_class( $methods ) {
		// Formas de busca de entregas
		if ( ! is_admin() && isset( WC()->session ) ) {
			$cash_on_delivery_settings = get_option( 'woocommerce_woo_payment_on_delivery_settings' );
			$chosen_methods = WC()->session->get( 'chosen_shipping_methods' );				
			$chosen_methods_meta = (is_array($chosen_methods[0])) ? $chosen_methods[0] : '';	
		} else {
			$methods[] = 'Woo_Payment_On_Delivery'; 		
			return $methods;
		}
		
		// Válido se for entregas antecipadas
		if (is_numeric($chosen_methods_meta)) { 
			$chosen_methods_meta = "advanced_shipping";
		}

		// Valide e compare as strings para abrir o método de pagamento
		if ( $cash_on_delivery_settings['enabled'] == "yes") {
			if( is_array($cash_on_delivery_settings['enable_for_methods']) ) {
				if( $cash_on_delivery_settings['enable_for_methods_all'] != "yes" ) {
                    $chosens_methods = (is_array($chosen_methods[0])) ? $chosen_methods[0] : '';
                    $chosen_methods = preg_replace("/[0-9]+/", "",  $chosens_methods ); 
                    $chosen_methods = preg_replace("/:/", "", $chosen_methods ); 
                    $key = array_search( $chosen_methods, $cash_on_delivery_settings['enable_for_methods'] );
					
					if( strstr( $chosen_methods, $cash_on_delivery_settings['enable_for_methods'][$key] ) ) {	
						$methods[] = 'Woo_Payment_On_Delivery';
						return $methods;
					}
					return $methods;
					
				} else {
					$methods[] = 'Woo_Payment_On_Delivery';
					return $methods;
				}
			} else {
				return $methods;
			}
			
		} else {
			return $methods;
		}
		
	}
	add_filter( 'woocommerce_payment_gateways', 'add_woo_payment_on_delivery_gateway_class' );
}
add_action( 'plugins_loaded', 'init_woo_payment_on_delivery_gateway_class' );


