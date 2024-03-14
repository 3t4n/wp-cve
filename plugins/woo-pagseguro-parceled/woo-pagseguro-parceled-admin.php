<?php
/**
 * Calcular o valor dos produtos em 12x parcelas sem juros.
 */ 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Sair se for acessado diretamente.
}

	
/**
 * Criação do submenu
 */
function register_pagseguro_parceled_wc_submenu_page() {
	add_submenu_page( 'woocommerce', __( 'PagSeguro Parcelamento', 'wc-pagseguro-parceled' ), __( 'PagSeguro Parcelamento', 'wc-pagseguro-parceled' ), 'manage_woocommerce', 'wc-pagseguro-parceled-admin', 'pagseguro_parceled_wc_admin' ); 
}

add_action( 'admin_menu', 'register_pagseguro_parceled_wc_submenu_page', 70 );

function pagseguro_parceled_wc_admin() {
	
	$WC_PagSeguro_Parceled = new WC_PagSeguro_Parceled();
	$classname = "WC_PagSeguro_Parceled";
	$tab = (isset($_GET['tab'])) ? trim($_GET['tab']) : '';
	
	/* Configurações */
	 $message = "";
	 if( isset( $_POST['_update'] ) && isset( $_POST['_wpnonce'] ) ) {
		$_update 	= sanitize_text_field( $_POST['_update'] );
		$_wpnonce 	= sanitize_text_field( $_POST['_wpnonce'] );
	 }
	 
	 if( isset( $_wpnonce ) && isset( $_update ) ) {
		if ( ! wp_verify_nonce( $_wpnonce, "woo-pagseguro-parceled-update-settings" ) ) {
			$message = 'error';
			
		} else if ( empty( $_update ) ) {
			$message = 'error';			
		}
		
		if( isset( $_POST['pagseguro_settings'] ) ) {
			$post_settings = array();
			$post_settings = (array)$_POST['pagseguro_settings'];

			// Configurações
			if ( empty($tab) ) {
				$new_settings['enabled'] = ( isset( $post_settings['enabled'] ) ) ? sanitize_text_field( $post_settings['enabled'] ) : "";
				$new_settings['title'] = ( isset( $post_settings['title'] ) ) ? sanitize_text_field( $post_settings['title'] ) : "no";				
				$new_settings['show_installment'] = ( isset( $post_settings['show_installment'] ) ) ? sanitize_text_field( $post_settings['show_installment'] ) : "";
				$new_settings['installment_table_single'] = ( isset( $post_settings['installment_table_single'] ) ) ? sanitize_text_field( $post_settings['installment_table_single'] ) : "";
				$new_settings['installment_loop_single'] = ( isset( $post_settings['installment_loop_single'] ) ) ? sanitize_text_field( $post_settings['installment_loop_single'] ) : "";
				$new_settings['installment_loop_product'] = ( isset( $post_settings['installment_loop_product'] ) ) ? sanitize_text_field( $post_settings['installment_loop_product'] ) : "0";
				$new_settings['installment_text_cart'] = ( isset( $post_settings['installment_text_cart'] ) ) ? sanitize_text_field( $post_settings['installment_text_cart'] ) : "0";
				$new_settings['installment_single_product'] = ( isset( $post_settings['installment_single_product'] ) ) ? sanitize_text_field( $post_settings['installment_single_product'] ) : "";
				$new_settings['installment'] = ( isset( $post_settings['installment'] ) ) ? sanitize_text_field( $post_settings['installment'] ) : "";
				$new_settings['minimum_installment'] = ( isset( $post_settings['minimum_installment'] ) ) ? sanitize_text_field( $post_settings['minimum_installment'] ) : "";
				$new_settings['sales_up'] = ( isset( $post_settings['sales_up'] ) ) ? sanitize_text_field( $post_settings['sales_up'] ) : "";
				$new_settings['fees'] = ( isset( $post_settings['fees'] ) ) ? sanitize_text_field( $post_settings['fees'] ) : 2.99;
			}

			if( isset($tab) && $tab == 'wpn-extras' ) {
				// Extras
				if ( isset( $post_settings['installment_extra'] ) ) {
					$new_settings['installment_extra'] = sanitize_text_field( $post_settings['installment_extra'] );
				}
				if ( isset( $post_settings['installment_extra_factor'] ) ) {
					$new_settings['installment_extra_factor'] = sanitize_text_field( $post_settings['installment_extra_factor'] );
				}
				if ( isset( $post_settings['installment_extra_value'] ) ) {
					$new_settings['installment_extra_value'] = sanitize_text_field( str_replace( ',', '.', $post_settings['installment_extra_value'] ) );
				}
				if ( isset( $post_settings['order_loop'] ) ) {
					$new_settings['order_loop'] = sanitize_text_field( $post_settings['order_loop'] );
				}
				if ( isset( $post_settings['order_single'] ) ) {
					$new_settings['order_single'] = sanitize_text_field( $post_settings['order_single'] );
				}
			}
			
			if( isset($tab) && $tab == 'wpn-code-css' ) {
				// CSS Avançado
				if ( isset( $post_settings['code_css_archive_product'] ) ) {
					$new_settings['code_css_archive_product'] = sanitize_textarea_field( $post_settings['code_css_archive_product'] );
				}
				if ( isset( $post_settings['code_css_single_product'] ) ) {
					$new_settings['code_css_single_product'] = sanitize_textarea_field( $post_settings['code_css_single_product'] );
				}
				if ( isset( $post_settings['code_css_page_cart'] ) ) {
					$new_settings['code_css_page_cart'] = sanitize_textarea_field( $post_settings['code_css_page_cart'] );
				}
			}

			// Atualizando
			$sanitize_pagseguro_settings = array();
			$sanitize_pagseguro_settings = get_option( 'woo_pagseguro_parceled_settings' );

			$pagseguro_settings = array();
			$pagseguro_settings = $WC_PagSeguro_Parceled->wc_sanitize_fields_array_settings( $sanitize_pagseguro_settings );

			update_option( "woo_pagseguro_parceled_settings", array_merge( $pagseguro_settings, $new_settings ) );
		}
		
		$message = "updated";	
	 }
	
	// Buscando os dados
	$new_pagseguro_settings = array();
	$new_pagseguro_settings = get_option( 'woo_pagseguro_parceled_settings' );

	// Configurações
	$enabled 					= esc_attr( $new_pagseguro_settings['enabled'] );
	$title 						= esc_attr( $new_pagseguro_settings['title'] );
	$show_installment 			= esc_attr( $new_pagseguro_settings['show_installment'] );
	$installment_table_single 	= esc_attr( $new_pagseguro_settings['installment_table_single'] );
	$installment_loop_single 	= esc_attr( $new_pagseguro_settings['installment_loop_single'] );
	$installment_loop_product 	= esc_attr( $new_pagseguro_settings['installment_loop_product'] );
	$installment_text_cart 		= esc_attr( $new_pagseguro_settings['installment_text_cart'] );
	$installment_single_product = esc_attr( $new_pagseguro_settings['installment_single_product'] );
	$minimum_installment 		= esc_attr( $new_pagseguro_settings['minimum_installment'] );
	$installment 				= esc_attr( $new_pagseguro_settings['installment'] );
	$sales_up 					= esc_attr( $new_pagseguro_settings['sales_up'] );
	$fees 						= esc_attr( $new_pagseguro_settings['fees'] );

	// Extras
	$installment_extra 			= esc_attr( $new_pagseguro_settings['installment_extra'] );
	$installment_extra_factor 	= esc_attr( $new_pagseguro_settings['installment_extra_factor'] );
	$installment_extra_value 	= esc_attr( $new_pagseguro_settings['installment_extra_value'] );
	$order_loop 				= esc_attr( $new_pagseguro_settings['order_loop'] );
	$order_single 				= esc_attr( $new_pagseguro_settings['order_single'] );

	// CSS Avançado
	$code_css_archive_product 	= esc_textarea( $new_pagseguro_settings['code_css_archive_product'] );
	$code_css_single_product 	= esc_textarea( $new_pagseguro_settings['code_css_single_product'] );
	$code_css_page_cart 		= esc_textarea( $new_pagseguro_settings['code_css_page_cart'] );
?>
<div id="wpwrap">
    
	<?php echo __( '<h1>Exibir Parcelamento PagSeguro</h1>', 'wc-pagseguro-parceled' ); ?>
	<?php echo __( '<p>De acordo com as configurações no site do PagSeguro, faça o mesmo aqui.<br/>
	O plugin irá exibir tabela de parcelas sem juros e com juros.</p>', 'wc-pagseguro-parceled' ); ?>

    <?php if( isset( $message ) ) { ?>
        <div class="wrap">
    	<?php if( $message == "updated" ) { ?>
            <div id="message" class="updated notice is-dismissible" style="margin-left: 0px;">
                <p><?php echo __( 'Atualizações feita com sucesso!', 'wc-pagseguro-parceled' ) ; ?></p>
                <button type="button" class="notice-dismiss">
                    <span class="screen-reader-text">
                        <?php echo __( 'Fechar', 'wc-pagseguro-parceled' ) ; ?>
                    </span>
                </button>
            </div>
            <?php } ?>
            <?php if( $message == "error" ) { ?>
            <div id="message" class="updated error is-dismissible" style="margin-left: 0px;">
                <p><?php echo __( 'Opa! Não conseguimos fazer as atualizações!', 'wc-pagseguro-parceled' ) ; ?></p>
                <button type="button" class="notice-dismiss">
                    <span class="screen-reader-text">
                        <?php echo __( 'Fechar', 'wc-pagseguro-parceled' ) ; ?>
                    </span>
                </button>
            </div>
        <?php } ?>
    	</div>
    <?php } ?>
    <!---->
    <div class="wrap woocommerce">
    	<!---->
            <nav class="nav-tab-wrapper wc-nav-tab-wrapper">
           		<a href="<?php echo esc_url( admin_url( 'admin.php?page=wc-pagseguro-parceled-admin' ) ); ?>" class="nav-tab <?php if( $tab == "" ) { echo "nav-tab-active"; }; ?>"><?php echo __( 'Configurações', 'wc-pagseguro-parceled' ); ?></a>
           		<a href="<?php echo esc_url( admin_url( 'admin.php?page=wc-pagseguro-parceled-admin&tab=wpn-extras' ) ); ?>" class="nav-tab <?php if( $tab == "wpn-extras" ) { echo "nav-tab-active"; }; ?>"><?php echo __( 'Extras', 'wc-pagseguro-parceled' ); ?></a>
           		<a href="<?php echo esc_url( admin_url( 'admin.php?page=wc-pagseguro-parceled-admin&tab=wpn-code-css' ) ); ?>" class="nav-tab <?php if( $tab == "wpn-code-css" ) { echo "nav-tab-active"; }; ?>"><?php echo __( 'CSS Avançado', 'wc-pagseguro-parceled' ); ?></a>
            	<a href="<?php echo esc_url( admin_url( 'admin.php?page=wc-pagseguro-parceled-admin&tab=wpn-doacao' ) ); ?>" class="nav-tab <?php if( $tab == "wpn-doacao") { echo "nav-tab-active"; }; ?>"><?php echo __( 'Doação', 'wc-pagseguro-parceled' ); ?></a>
            </nav>
            <!---->
            <?php if( empty($tab) ) { ?>
        	<form action="<?php echo esc_url( admin_url( 'admin.php?page=wc-pagseguro-parceled-admin' ) ); ?>" method="post" enctype="application/x-www-form-urlencoded">
                <!---->
                <table class="form-table">
                    <tbody>
                        <!---->
                        <tr valign="top">
                            <th scope="row">
                                <label>
                                    <?php echo __( 'Habilitar:', 'wc-pagseguro-parceled' ) ; ?>
                                </label>
                            </th>
                            <td>
                            	&nbsp;&nbsp;&nbsp;&nbsp;
                                <label>
                                    <input type="checkbox" name="pagseguro_settings[enabled]" value="yes" <?php if( $enabled == "yes" ) { echo 'checked="checked"'; } ?> class="form-control">
                                    <?php echo __( 'Ativar plugin', 'wc-pagseguro-parceled' ) ; ?>
                                </label>
                           </td>
                        </tr>

                        <!---->
                        <tr valign="top">
                            <th scope="row">
                                <label>
                                    <?php echo __( 'Exibir:', 'wc-pagseguro-parceled' ) ; ?>
                                </label>
                            </th>
                            <td>
                            	&nbsp;&nbsp;&nbsp;&nbsp;
                                <label>
                                    <input type="checkbox" name="pagseguro_settings[show_installment]" value="yes" <?php if( $show_installment == "yes" ) { echo 'checked="checked"'; } ?> class="form-control">
                                    <?php echo __( 'Se desativado vai aparecer somente as parcelas sem juros.', 'wc-pagseguro-parceled' ) ; ?>
                                </label>
                           </td>
                        </tr>

                        <!---->
                        <tr valign="top">
                            <th scope="row">
                                <label>
                                    <?php echo __( 'Título:', 'wc-pagseguro-parceled' ) ; ?>
                                </label>
                            </th>
                            <td>
                            	&nbsp;&nbsp;&nbsp;&nbsp;
                                <label>
                                    <input type="text" name="pagseguro_settings[title]" value="<?php echo $title; ?>" class="form-control">
	                                <br/>
									<span aria-hidden="true" class="dashicons dashicons-warning" style="vertical-align: sub;"></span>
                                	<?php echo __( 'Escreve o título que irá aparecer no website.', 'wc-pagseguro-parceled' ) ; ?>
                                </label>
                           </td>
                        </tr>
                        <!---->
                        <tr valign="top">
                            <th scope="row">
                                <label>
                                    <?php echo __( 'Descrição no Loop:', 'wc-pagseguro-parceled' ) ; ?>
                                </label>
                            </th>
                            <td>
                            	&nbsp;&nbsp;&nbsp;&nbsp;
                                <label>
                                	<input type="checkbox" name="pagseguro_settings[installment_loop_product]" value="yes" <?php if( $installment_loop_product == "yes" ) { echo 'checked="checked"'; } ?> class="form-control"> <?php echo __( 'Exibir', 'wc-pagseguro-parceled' ); ?>
								 - 
								<a href="<?php echo esc_url( plugins_url( '/woo-pagseguro-parceled/images/show-loop-product.jpg', dirname(__FILE__) ) ); ?>" target="_blank" style="display: inline-block; margin-left:20px; text-decoration:none;">
									<span>
										<span aria-hidden="true" class="dashicons dashicons-visibility"></span>
										<?php echo __( 'Exemplo', 'wc-pagseguro-parceled' ) ; ?>
									</span>
								</a>
                                </label>
                            </td>
                        </tr>
						<!---->

                        <!---->
                        <tr valign="top">
                            <th scope="row">
                                <label>
                                    <?php echo __( 'Descrição no Detalhe do Produto:', 'wc-pagseguro-parceled' ) ; ?>
                                </label>
                            </th>
                            <td>
                            	&nbsp;&nbsp;&nbsp;&nbsp;
                                <label>
                                	<input type="checkbox" name="pagseguro_settings[installment_loop_single]" value="yes" <?php if( $installment_loop_single == "yes" ) { echo 'checked="checked"'; } ?> class="form-control"> <?php echo __( 'Exibir', 'wc-pagseguro-parceled' ); ?>
								 - 
								<a href="<?php echo esc_url( plugins_url( '/woo-pagseguro-parceled/images/show-loop-product.jpg', dirname(__FILE__) ) ); ?>" target="_blank" style="display: inline-block; margin-left:20px; text-decoration:none;">
									<span>
										<span aria-hidden="true" class="dashicons dashicons-visibility"></span>
										<?php echo __( 'Exemplo', 'wc-pagseguro-parceled' ) ; ?>
									</span>
								</a>
                                </label>
                            </td>
                        </tr>
						<!---->

                        <!---->
                        <tr valign="top">
                            <th scope="row">
                                <label>
                                    <?php echo __( 'Mensagem no Carrinho de Compras:', 'wc-pagseguro-parceled' ) ; ?>
                                </label>
                            </th>
                            <td>
                            	&nbsp;&nbsp;&nbsp;&nbsp;
                                <label>
                                	<input type="checkbox" name="pagseguro_settings[installment_text_cart]" value="yes" <?php if( $installment_text_cart == "yes" ) { echo 'checked="checked"'; } ?> class="form-control"> <?php echo __( 'Exibir', 'wc-pagseguro-parceled' ); ?>
								 - 
								<a href="<?php echo esc_url( plugins_url( '/woo-pagseguro-parceled/images/show-text-cart.jpg', dirname(__FILE__) ) ); ?>" target="_blank" style="display: inline-block; margin-left:20px; text-decoration:none;">
									<span>
										<span aria-hidden="true" class="dashicons dashicons-visibility"></span>
										<?php echo __( 'Exemplo', 'wc-pagseguro-parceled' ) ; ?>
									</span>
								</a>
                                </label>
                            </td>
                        </tr>
						<!---->

                        <!---->
                        <tr valign="top">
                            <th scope="row">
                                <label>
                                    <?php echo __( 'Tabela no Detalhe do Produto:', 'wc-pagseguro-parceled' ) ; ?>
                                </label>
                            </th>
                            <td>
                            	&nbsp;&nbsp;&nbsp;&nbsp;
                                <label>
                                	<input type="checkbox" name="pagseguro_settings[installment_table_single]" value="yes" <?php if( $installment_table_single == "yes" ) { echo 'checked="checked"'; } ?> class="form-control"> <?php echo __( 'Exibir', 'wc-pagseguro-parceled' ); ?>
								 - 
								<a href="<?php echo esc_url( plugins_url( '/woo-pagseguro-parceled/images/show-product-single.jpg', dirname(__FILE__) ) ); ?>" target="_blank" style="display: inline-block; margin-left:20px; text-decoration:none;">
									<span>
										<span aria-hidden="true" class="dashicons dashicons-visibility"></span>
										<?php echo __( 'Exemplo', 'wc-pagseguro-parceled' ) ; ?>
									</span>
								</a>
                                </label>
                                <input type="hidden" name="pagseguro_settings[installment_single_product]" value="yes">
                            </td>
                        </tr>
						<!---->

                        <tr valign="top">
                            <th scope="row">
                                <label>
                                    <?php echo __( 'Parcelas sem juros:', 'wc-pagseguro-parceled' ) ; ?>
                                </label>
                            </th>
                            <td>
							&nbsp;&nbsp;&nbsp;&nbsp;
							<select name="pagseguro_settings[installment]" style="width: 100%; max-width: 170px;" class="form-control">
								<option value="1" <?php if( $installment == "1" ) { echo "selected"; } ?>>
									<?php echo __( '1x sem juros', 'wc-pagseguro-parceled' ); ?>
								</option>
								<option value="2" <?php if( $installment == "2" ) { echo "selected"; } ?>>
									<?php echo __( '2x sem juros', 'wc-pagseguro-parceled' ); ?>
								</option>
								<option value="3" <?php if( $installment == "3" ) { echo "selected"; } ?>>
									<?php echo __( '3x sem juros', 'wc-pagseguro-parceled' ); ?>
								</option>
								<option value="4" <?php if( $installment == "4" ) { echo "selected"; } ?>>
									<?php echo __( '4x sem juros', 'wc-pagseguro-parceled' ); ?>
								</option>
								<option value="5" <?php if( $installment == "5" ) { echo "selected"; } ?>>
									<?php echo __( '5x sem juros', 'wc-pagseguro-parceled' ); ?>
								</option>
								<option value="6" <?php if( $installment == "6" ) { echo "selected"; } ?>>
									<?php echo __( '6x sem juros', 'wc-pagseguro-parceled' ); ?>
								</option>
								<option value="7" <?php if( $installment == "7" ) { echo "selected"; } ?>>
									<?php echo __( '7x sem juros', 'wc-pagseguro-parceled' ); ?>
								</option>
								<option value="8" <?php if( $installment == "8" ) { echo "selected"; } ?>>
									<?php echo __( '8x sem juros', 'wc-pagseguro-parceled' ); ?>
								</option>
								<option value="9" <?php if( $installment == "9" ) { echo "selected"; } ?>>
									<?php echo __( '9x sem juros', 'wc-pagseguro-parceled' ); ?>
								</option>
								<option value="10" <?php if( $installment == "10" ) { echo "selected"; } ?>>
									<?php echo __( '10x sem juros', 'wc-pagseguro-parceled' ); ?>
								</option>
								<option value="11" <?php if( $installment == "11" ) { echo "selected"; } ?>>
									<?php echo __( '11x sem juros', 'wc-pagseguro-parceled' ); ?>
								</option>
								<option value="12" <?php if( $installment == "12" ) { echo "selected"; } ?>>
									<?php echo __( '12x sem juros', 'wc-pagseguro-parceled' ); ?>
								</option>
							</select>							
								<a href="https://pagseguro.uol.com.br/installment/configuration.jhtml" target="_blank" style="text-decoration:none;">
									<span>
										<span aria-hidden="true" class="dashicons dashicons-money-alt" style="vertical-align: sub;"></span>
										<?php echo __( 'Configurar no PagSeguro.', 'wc-pagseguro-parceled' ); ?>
									</span>
								</a>
                            </td>
                        </tr>
                        <!---->
                        <tr valign="top">
                            <th scope="row">
                                <label>
                                    <?php echo __( 'Juros (%):', 'wc-pagseguro-parceled' ) ; ?>
                                </label>
                            </th>
                            <td>
								% <input type="number" name="pagseguro_settings[fees]" placeholder="Padrão: 2.99" min="0.01" class="form-control input-text wc_input_decimal" step="0.01" value="<?php echo $fees; ?>">
								<span aria-hidden="true" class="dashicons dashicons-warning" style="vertical-align: sub;"></span>
								<?php echo __( 'Porcentagem para o juros de cada parcela.', 'wc-pagseguro-parceled' ) ; ?>
								</span>						
                            </td>
                        </tr>
                        <!---->
                        <tr valign="top">
                            <th scope="row">
                                <label>
                                    <?php echo __( 'Valor mínimo das parcelas:', 'wc-pagseguro-parceled' ) ; ?>
                                </label>
                            </th>
                            <td>
								R$ <input type="number" name="pagseguro_settings[minimum_installment]" placeholder="Padrão: 5" min="5" class="form-control input-text wc_input_decimal"  value="<?php echo $minimum_installment; ?>">
								<span aria-hidden="true" class="dashicons dashicons-warning" style="vertical-align: sub;"></span>
								<?php echo __( 'Esse será o valor mínimo para a parcela.', 'wc-pagseguro-parceled' ) ; ?>
								</span>						
                            </td>
                        </tr>
                        <!---->
                        <tr valign="top">
                            <th scope="row">
                                <label>
                                    <?php echo __( 'Parcelamento a partir de:', 'wc-pagseguro-parceled' ); ?>
                                </label>
                            </th>
                            <td>
								R$ <input type="number" name="pagseguro_settings[sales_up]" placeholder="Ex: 100" min="5" class="form-control input-text wc_input_decimal" value="<?php echo $sales_up; ?>">
								<span aria-hidden="true" class="dashicons dashicons-warning" style="vertical-align: sub;"></span>
								<?php echo __( 'O sistema só começará exibir o calculo do Parcelamento se o preço do produto for igual ou acima desse.', 'wc-pagseguro-parceled' ) ; ?>
								</span>						
                            </td>
                        </tr>
                        <!---->										
                    </tbody>
                </table>
                <!---->
                <hr/>
                <div class="submit">
                    <button class="button button-primary" type="submit"><?php echo __( 'Salvar Alterações', 'wc-pagseguro-parceled' ) ; ?></button>
                    <input type="hidden" name="_update" value="1">
                    <input type="hidden" name="_wpnonce" value="<?php echo sanitize_text_field( wp_create_nonce( 'woo-pagseguro-parceled-update-settings' ) ); ?>">
                    <!---->
                    <span>
                    	<span aria-hidden="true" class="dashicons dashicons-warning" style="vertical-align: sub;"></span>
    					<?php echo __( 'Não esqueça de <strong>salvar suas alterações</strong>.', 'wc-pagseguro-parceled' ) ; ?>
                    </span>
                </div>
                <!---->
        	</form>
			<?php } ?>
			<!---->
			<?php if( isset($tab) && $tab == "wpn-extras" ) { ?>
        	<form action="<?php echo esc_url( admin_url( 'admin.php?page=wc-pagseguro-parceled-admin&tab=wpn-extras' ) ); ?>" method="post" enctype="application/x-www-form-urlencoded">
                <!---->

                <table class="form-table">
                    <tbody>
                        <!---->
                        <tr valign="top">
                            <th scope="row">
                            	<label>
                            		Shortcodes da tabela
                            	</label>					
                            </th>
                            <td>
                            	&nbsp;&nbsp;&nbsp;&nbsp;
                            	<span>[product_parceled_single]</span>
                            </td>
                        </tr>
                        <!---->
                        <tr valign="top">
                            <th scope="row">
                            	<label>
                            		Shortcodes do preço no loop dos produtos.	
                            	</label>				
                            </th>
                            <td>
                            	&nbsp;&nbsp;&nbsp;&nbsp;
                            	<span>[product_parceled_loop]</span>
                            </td>
                        </tr>
                        <!---->	
                        <tr valign="top">
                            <th scope="row">
                                <label>
                                    <?php echo __( 'Extras nas Parcelas:', 'wc-pagseguro-parceled' ); ?>
                                </label>
                            </th>
                            <td>
								&nbsp;&nbsp;&nbsp;&nbsp;
								<select name="pagseguro_settings[installment_extra]" style="width: 100%; max-width: 170px;" class="form-control">
									<option value=""<?php if( $installment_extra == "" ) { echo "selected"; } ?>>
										<?php echo __( 'Desativado', 'wc-pagseguro-parceled' ); ?>
									</option>
									<option value="1" <?php if( $installment_extra == "1" ) { echo "selected"; } ?>>
										<?php echo __( 'No com juros', 'wc-pagseguro-parceled' ); ?>
									</option>
									<option value="2" <?php if( $installment_extra == "2" ) { echo "selected"; } ?>>
										<?php echo __( 'No sem juros', 'wc-pagseguro-parceled' ); ?>
									</option>
									<option value="3" <?php if( $installment_extra == "3" ) { echo "selected"; } ?>>
										<?php echo __( 'Nos com e sem juros', 'wc-pagseguro-parceled' ); ?>
									</option>
								</select>
								<span>
								<span aria-hidden="true" class="dashicons dashicons-warning" style="vertical-align: sub;"></span>
								<?php echo __( 'Aqui vc habilita se quer adicionar <strong>valor extra</strong> em <strong>cima das parcelas</strong>.', 'wc-pagseguro-parceled' ) ; ?>
								</span>						
                            </td>
                        </tr>
                        <!---->	
                        <tr valign="top">
                            <th scope="row">
                                <label>
                                    <?php echo __( 'Fator:', 'wc-pagseguro-parceled' ); ?>
                                </label>
                            </th>
                            <td>
								&nbsp;&nbsp;&nbsp;&nbsp;
								<select name="pagseguro_settings[installment_extra_factor]" style="width: 100%; max-width: 170px;" class="form-control">
									<option value="1" <?php if( $installment_extra_factor == "1" ) { echo "selected"; } ?>>
										<?php echo __( 'Porcentagem (%)', 'wc-pagseguro-parceled' ); ?>
									</option>
									<option value="2" <?php if( $installment_extra_factor == "2" ) { echo "selected"; } ?>>
										<?php echo __( 'Valor Fixo (R$)', 'wc-pagseguro-parceled' ); ?>
									</option>
								</select>
								<span>
								<span aria-hidden="true" class="dashicons dashicons-warning" style="vertical-align: sub;"></span>
								<?php echo __( 'Se vai ser em <strong>porcentagem ou valor fixo</strong>.', 'wc-pagseguro-parceled' ) ; ?>
								</span>						
                            </td>
                        </tr>
                        <!---->	
                        <tr valign="top">
                            <th scope="row">
                                <label>
                                    <?php echo __( 'Valor do Extra Adicional:', 'wc-pagseguro-parceled' ); ?>
                                </label>
                            </th>
                            <td>
                            	&nbsp;&nbsp;&nbsp;&nbsp;
                            	<input type="text" class="short wc_input_price" name="pagseguro_settings[installment_extra_value]" placeholder="Ex: 10" value="<?php echo $installment_extra_value; ?>">
								<span aria-hidden="true" class="dashicons dashicons-warning" style="vertical-align: sub;"></span>
								<?php echo __( 'Esse será o <strong>valor fixo (R$)</strong> ou <strong>porcentagem (%)</strong> que será <strong>adicionado nas parcelas</strong> de cada produto</strong>.', 'wc-pagseguro-parceled' ) ; ?>
								</span>						
                            </td>
                        </tr>
                        <!---->	
                        <tr valign="top">
                            <td colspan="2">
                            	<hr/>					
                            </td>
                        </tr>
                        <!---->	
                        <tr valign="top">
                            <th scope="row">
                                <label>
                                    <?php echo __( 'Ordem no Loop:', 'wc-pagseguro-parceled' ); ?>
                                </label>
                            </th>
                            <td>
                            	&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="number" name="pagseguro_settings[order_loop]" placeholder="Ex: 5, 10, 20 ou 30" min="1" step="1" class="form-control input-text wc_input_decimal" value="<?php echo $order_loop; ?>">
								<span>
								<span aria-hidden="true" class="dashicons dashicons-warning" style="vertical-align: sub;"></span>
								<?php echo __( 'Será exibido no <strong>loop dos produtos</strong>.', 'wc-pagseguro-parceled' ) ; ?>
								</span>						
                            </td>
                        </tr>
                        <!---->	
                        <tr valign="top">
                            <th scope="row">
                                <label>
                                    <?php echo __( 'Ordem na página simples de produto:', 'wc-pagseguro-parceled' ); ?>
                                </label>
                            </th>
                            <td>
                            	&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="number" name="pagseguro_settings[order_single]" placeholder="Ex: 5, 10, 20 ou 30" min="1" step="1" class="form-control input-text wc_input_decimal" value="<?php echo $order_single; ?>">
								<span>
								<span aria-hidden="true" class="dashicons dashicons-warning" style="vertical-align: sub;"></span>
								<?php echo __( 'Será exibido na página de <strong>detalhe dos produtos</strong>.', 'wc-pagseguro-parceled' ) ; ?>
								</span>						
                            </td>
                        </tr>
                        <!---->										
                    </tbody>
                </table>
                <!---->
                <hr/>
                <div class="submit">
                    <button class="button button-primary" type="submit"><?php echo __( 'Salvar Alterações', 'wc-pagseguro-parceled' ) ; ?></button>
                    <input type="hidden" name="_update" value="1">
                    <input type="hidden" name="_wpnonce" value="<?php echo sanitize_text_field( wp_create_nonce( 'woo-pagseguro-parceled-update-settings' ) ); ?>">
                    <!---->
                    <span>
                    	<span aria-hidden="true" class="dashicons dashicons-warning" style="vertical-align: sub;"></span>
    					<?php echo __( 'Não esqueça de <strong>salvar suas alterações</strong>.', 'wc-pagseguro-parceled' ) ; ?>
                    </span>
                </div>
                <!---->
        	</form>
			<?php } ?>
			<!---->
			<?php if( isset($tab) && $tab == "wpn-code-css" ) { ?>
			<form action="<?php echo esc_url( admin_url( 'admin.php?page=wc-pagseguro-parceled-admin&tab=wpn-code-css' ) ); ?>" method="post" enctype="multipart/form-data">
				<input name="update" type="hidden" value="update_code_incorporated">
				<br/>
				<p style="margin: 10px 0 0;">( Page Archive Product )</p>
				<h2 style="margin: 0 0 10px;">Lista de Produto</h2>
				
				<textarea name="pagseguro_settings[code_css_archive_product]" id="code_css_archive_product" placeholder="<?php echo __( 'Não deixe esse campo em branco', 'wc-pagseguro-parceled' ) ; ?>" style="display: block; width: 100%; max-width: 600px;" class="form-control" rows="5"><?php echo $code_css_archive_product; ?></textarea>
				<br/><hr/>
				
				<p style="margin: 10px 0 0;">( Page Single Product )</p>
				<h2 style="margin: 0 0 10px;">Produto Único</h2>
				<textarea name="pagseguro_settings[code_css_single_product]" id="code_css_single_product" placeholder="<?php echo __( 'Não deixe esse campo em branco', 'wc-pagseguro-parceled' ) ; ?>" style="display: block; width: 100%; max-width: 600px;" class="form-control" rows="15"><?php echo $code_css_single_product; ?></textarea>			
				<br/><hr/>
				
				<p style="margin: 10px 0 0;">( Page Cart )</p>
				<h2 style="margin: 0 0 10px;">Carrinho de Compras</h2>
				<textarea name="pagseguro_settings[code_css_page_cart]" id="code_css_page_cart" placeholder="<?php echo __( 'Não deixe esse campo em branco', 'wc-pagseguro-parceled' ) ; ?>" style="display: block; width: 100%; max-width: 600px;" class="form-control" rows="5"><?php echo $code_css_page_cart; ?></textarea>			
				<br/><hr/>
				
				<div class="submit">
					<button class="button" type="button" onclick="restore_css();"><?php echo __( 'Restaurar Padrão', 'wc-pagseguro-parceled' ) ; ?></button>
					<button class="button button-primary" type="submit"><?php echo __( 'Salvar Alterações', 'wc-pagseguro-parceled' ) ; ?></button>
					<input type="hidden" name="_update" value="1">
					<input type="hidden" name="_wpnonce" value="<?php echo sanitize_text_field( wp_create_nonce( 'wc-pagseguro-parceled-update' ) ); ?>">
					<!---->
				<span>
						<span aria-hidden="true" class="dashicons dashicons-warning" style="vertical-align: sub;"></span>
						<?php echo __( 'Não esqueça de <strong>salvar suas alterações</strong>.', 'wc-pagseguro-parceled' ) ; ?>
					</span>
				</div>
			</form>
        <?php } ?>
        <!---->
		<?php if( isset($tab) && $tab == "wpn-doacao" ) { ?>
            <h2><?php echo __( 'Oba! Fique a vontade.', 'wc-pagseguro-parceled' ) ; ?></h2>
        	<div class="">
            	<p><?php echo __( '<strong>É totalmente seguro!</strong> Ajude a manter esse plugin sempre atualizado com seu incentivo.', 'wc-pagseguro-parceled' ) ; ?></p>
            </div>
			<!---->
            <table class="form-table">
                <tbody>
                    <!---->
                    <tr valign="top">
                        <th scope="row">
                            <button class="button-primary" onClick="window.open('https://donate.criacaocriativa.com')">
                            <?php echo __( 'Quero doar agora', 'wc-pagseguro-parceled' ) ; ?>
                            </button>
                        </th>
                        <td>
                            <label>
							<span>
								<span class="dashicons dashicons-warning" style="vertical-align: middle;"></span>
								<?php echo __( 'Você será direcionado para um site seguro.', 'wc-pagseguro-parceled' ) ; ?> 
							</span> 
                            </label>
                        </td>
                    </tr>
                    <!---->
                </tbody>
            </table>
            <!---->
        <?php } ?>
        <!---->
    </div>
	<!---->
</div> 
<div style="clear:both;"></div>
<script type="text/javascript">
function restore_css() {	
	jQuery( "#code_css_archive_product" ).val('<?php echo call_user_func( array( $classname, 'code_css_archive_product' ) ); ?>');
	jQuery( "#code_css_single_product" ).val('<?php echo call_user_func( array( $classname, 'code_css_single_product' ) ); ?>');
	jQuery( "#code_css_page_cart" ).val('<?php echo call_user_func( array( $classname, 'code_css_page_cart' ) ); ?>');
}
</script>
<?php	
}