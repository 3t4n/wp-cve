<?php
if(!defined('ABSPATH')) exit;

add_action( 'admin_menu', 'soc_pix_menu');
function soc_pix_menu(){
	add_options_page('Social Pixel', 'Social Pixel','manage_options', 'social-pixel', 'soc_pix_conf');
}
function soc_pix_conf(){
	$options 	= get_option('soc_pix_options');
	$fb_re		= $options['fb_re'];	
	$fb_tx		= $options['fb_tx'];
	$fb_id 		= $options['fb_id'];
	$fb_in		= $options['fb_in'];
	$fb_vp		= $options['fb_vp'];
	$fb_ac		= $options['fb_ac'];
	$fb_ic		= $options['fb_ic'];
	$fb_pc		= $options['fb_pc'];
	$fb_vs		= $options['fb_vs'];
	$fb_vc		= $options['fb_vc'];
	$fb_vt		= $options['fb_vt'];
	$tw_id 		= $options['tw_id'];
	$tw_in		= $options['tw_in'];
	$tw_vc		= $options['tw_vc'];
	$tw_ac		= $options['tw_ac'];
	$tw_ic		= $options['tw_ic'];
	$tw_pc		= $options['tw_pc'];
	$in_id 		= $options['in_id'];
	$in_in		= $options['in_in'];
	$pn_id 		= $options['pn_id'];
	$pn_in		= $options['pn_in'];
	$pn_vc		= $options['pn_vc'];
	$pn_ac		= $options['pn_ac'];
	$pn_ic		= $options['pn_ic'];
	$pn_pc		= $options['pn_pc'];
	$tt_id 		= $options['tt_id'];
	$tt_in		= $options['tt_in'];
	$tt_vc		= $options['tt_vc'];
	$tt_ac		= $options['tt_ac'];
	$tt_ic		= $options['tt_ic'];
	$tt_pc		= $options['tt_pc'];
	$ga_id 		= $options['ga_id'];
	$ga_in		= $options['ga_in'];
	$ga_ds 		= $options['ga_ds'];
	$ga_ip	 	= $options['ga_ip'];
	$ga_ln	 	= $options['ga_ln'];
	$ga_vi	 	= $options['ga_vi'];
	$ga_ac	 	= $options['ga_ac'];
	$ga_bc	 	= $options['ga_bc'];
	$ga_cp	 	= $options['ga_cp'];
	$ga_pc	 	= $options['ga_pc'];
	?>
<script>
(function($){
	'use strict';
	$(function(e){
		$('.tab-content > div').hide();
		$('.tab-content > div:first-of-type').show();
		$('.nav-tab-wrapper span').click(function(e){
			e.preventDefault();
			var $this = $(this),
				tabcontent = '#'+$this.parents('.nav-tab-wrapper').data('tab-content'),
				others = $this.closest('span').siblings(),
				target = $this.attr('data-url');
			others.removeClass('nav-tab-active');
			$this.addClass('nav-tab-active');
			$(tabcontent).children('div').hide();
			$(target).show();
		})	
	});
})(jQuery);
</script>
<div class="wrap">
	<h2><?php esc_html_e('Social Pixel','social-pixel'); ?></h2>
	<h2 class="nav-tab-wrapper" data-tab-content="description-tab-group">
		<span style="cursor:pointer" class="nav-tab nav-tab-active" data-url="#facebook"><?php esc_html_e('Facebook','social-pixel'); ?></span>				
		<span style="cursor:pointer" class="nav-tab" data-url="#twitter"><?php esc_html_e('Twitter','social-pixel'); ?></span>
		<span style="cursor:pointer" class="nav-tab" data-url="#linkedin"><?php esc_html_e('Linkedin','social-pixel'); ?></span>
		<span style="cursor:pointer" class="nav-tab" data-url="#pinterest"><?php esc_html_e('Pinterest','social-pixel'); ?></span>
		<span style="cursor:pointer" class="nav-tab" data-url="#tiktok"><?php esc_html_e('TikTok','social-pixel'); ?></span>
		<span style="cursor:pointer" class="nav-tab" data-url="#google-analytics"><?php esc_html_e('Google Analytics','social-pixel'); ?></span>
		<?php if(class_exists('woocommerce')){ ?>
		<span style="cursor:pointer" class="nav-tab" data-url="#woocommerce"><?php esc_html_e('Woocommerce','social-pixel'); ?></span>
		<?php } ?>
	</h2>
  	<form method="post" action='options.php'>
    	<?php settings_fields('soc_pix_options'); ?>
    	<div id="description-tab-group" class="tab-content">    
        	<!-- FACEBOOK -->
        	<div class="tab-pane" id="facebook">
    			<table class="form-table">
      				<tbody>
						<tr>
							<th colspan="2">
								<h3><?php esc_html_e('Facebook', 'social-pixel');?></h3>
								<hr>
							</th>
						</tr>
        				<tr>
          					<th scope="row"><?php esc_html_e('Identificador','social-pixel'); ?></th>
          					<td>
          						<fieldset>
            						<label for="fb_id">
            							<input name='soc_pix_options[fb_id]' id='fb_id' type='text' value='<?php echo $fb_id; ?>' />
            							<p class="description">
            								<?php esc_html_e('Ayuda para obtener el identificador del tag de Facebook','social-pixel'); ?> 
            								<a Href="https://www.labschool.es/configurar-pixel-de-seguimiento-de-facebook-twitter-linkedin-y-pinterest/#facebook" target="_blank" rel="noopener">
            								<?php esc_html_e('aquí.','social-pixel'); ?>
            								</a>
            							</p>
           							</label>
            					</fieldset>
            				</td>
						</tr>
						<tr>
          					<th scope="row"><?php esc_html_e('Activación','social-pixel'); ?></th>
          					<td>
          						<fieldset>
            						<label for="fb_in">
            							<input name='soc_pix_options[fb_in]' id='fb_in' type='checkbox' value='1' <?php echo checked($fb_in,1,false); ?>/>
            							<?php esc_html_e('Activa esta opción para agregar el tag de Facebook.','social-pixel'); ?>
            						</label>
            					</fieldset>
            				</td>
        				</tr>
        				<?php if(class_exists('woocommerce')){ ?>
        				<tr>
          					<th class="row" colspan="2">
          						<h4><?php esc_html_e('Configuración para Woocommerce','social-pixel'); ?></h4>
            					<hr/>
          					</th>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e('ViewContent','social-pixel'); ?></th>
						  	<td>
						  		<fieldset>
									<label for="fb_vp">
										<input name='soc_pix_options[fb_vp]' id='fb_vp' type='checkbox' value='1' <?php echo checked($fb_vp,1,false); ?>/>
										<?php esc_html_e('Incluye el evento estándar "ViewContent" cada vez que se carga una página de producto.','social-pixel'); ?>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr>
						  	<th scope="row"><?php esc_html_e('AddToCart','social-pixel'); ?></th>
						  	<td>
						  		<fieldset>
									<label for="fb_ac">
										<input name='soc_pix_options[fb_ac]' id='fb_ac' type='checkbox' value='1' <?php echo checked($fb_ac,1,false); ?>/>
										<?php esc_html_e('Incluye el evento estándar "AddToCart" cada vez que se carga la página del carrito.','social-pixel'); ?>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr>
						  	<th scope="row"><?php esc_html_e('InitiateCheckout','social-pixel'); ?></th>
						  	<td>
						  		<fieldset>
									<label for="fb_ic">
										<input name='soc_pix_options[fb_ic]' id='fb_ic' type='checkbox' value='1' <?php echo checked($fb_ic,1,false); ?>/>
										<?php esc_html_e('Incluye el evento estándar "InitiateCheckout" cada vez que se carga la página de finalizar la compra.','social-pixel'); ?>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr>
						  	<th scope="row"><?php esc_html_e('Purchase','social-pixel'); ?></th>
						  	<td>
						  		<fieldset>
									<label for="fb_pc">
										<input name='soc_pix_options[fb_pc]' id='fb_pc' type='checkbox' value='1' <?php echo checked($fb_pc,1,false); ?>/>
										<?php esc_html_e('Incluye el evento estándar "Purchase" cada vez que se carga la página de agradecimiento tras realizar una compra.','social-pixel'); ?>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr>
          					<th class="row" colspan="2">
          						<h4><?php esc_html_e('Eventos personalizados','social-pixel'); ?></h4>
            					<hr/>
          					</th>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e('ViewShop','social-pixel'); ?></th>
							<td>
								<fieldset>
									<label for="fb_vs">
										<input name='soc_pix_options[fb_vs]' id='fb_vs' type='checkbox' value='1' <?php echo checked($fb_vs,1,false); ?>/>
										<?php esc_html_e('Incluye el evento personalizado "ViewShop" cada vez que se carga una p&aacute;gina del cat&aacute;logo de productos.','social-pixel'); ?>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e('ViewCategory','social-pixel'); ?></th>
							<td>
								<fieldset>
									<label for="fb_vc">
										<input name='soc_pix_options[fb_vc]' id='fb_vc' type='checkbox' value='1' <?php echo checked($fb_vc,1,false); ?>/>
										<?php esc_html_e('Incluye el evento personalizado "ViewCategory" cada vez que se carga una p&aacute;gina de categor&iacute;a de productos.','social-pixel'); ?>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e('ViewTag','social-pixel'); ?></th>
							<td>
								<fieldset>
									<label for="fb_vt">
										<input name='soc_pix_options[fb_vt]' id='fb_vt' type='checkbox' value='1' <?php echo checked($fb_vt,1,false); ?>/>
										<?php esc_html_e('Incluye el evento personalizado "ViewTag" cada vez que se carga una p&aacute;gina de etiqueta de productos.','social-pixel'); ?>
									</label>
								</fieldset>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>			
			<!-- TWITTER -->
        	<div class="tab-pane" id="twitter">
    			<table class="form-table">
      				<tbody>
						<tr>
							<th colspan="2">
								<h3><?php esc_html_e('Twitter', 'social-pixel');?></h3>
								<hr>
							</th>
						</tr>
						<tr>
						  	<th scope="row"><?php esc_html_e('Identificador','social-pixel'); ?></th>
						  	<td>
						  		<fieldset>
									<label for="tw_id">
										<input name='soc_pix_options[tw_id]' id='tw_id' type='text' value='<?php echo $tw_id; ?>' />
										<p class="description">
											<?php esc_html_e('Ayuda para obtener el identificador del tag de Twitter','social-pixel'); ?>
											<a href="https://www.labschool.es/configurar-pixel-de-seguimiento-de-facebook-twitter-linkedin-y-pinterest/#twitter" target="_blank" rel="noopener">
											<?php esc_html_e('aquí.','social-pixel'); ?>
											</a>
										</p>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr>
						  	<th scope="row"> <?php esc_html_e('Activación','social-pixel'); ?>
						  	<td>
						  		<fieldset>
									<label for="tw_in">
										<input name='soc_pix_options[tw_in]' id='tw_in' type='checkbox' value='1' <?php echo checked($tw_in,1,false); ?>/>
										<?php esc_html_e('Activa esta opción para agregar el tag de Twitter.','social-pixel'); ?>
									</label>
								</fieldset>
							</td>
						</tr>
						<?php if(class_exists('woocommerce')){ ?>
        				<tr>
          					<th class="row" colspan="2">
          						<h4><?php esc_html_e('Configuración para Woocommerce','social-pixel'); ?></h4>
            					<hr/>
          					</th>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e('ViewContent','social-pixel'); ?></th>
						  	<td>
						  		<fieldset>
									<label for="tw_vc">
										<input name='soc_pix_options[tw_vc]' id='tw_vc' type='checkbox' value='1' <?php echo checked($tw_vc,1,false); ?>/>
										<?php esc_html_e('Incluye el evento estándar "ViewContent" cada vez que se carga una página de producto.','social-pixel'); ?>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr>
						  	<th scope="row"><?php esc_html_e('AddToCart','social-pixel'); ?></th>
						  	<td>
						  		<fieldset>
									<label for="tw_ac">
										<input name='soc_pix_options[tw_ac]' id='tw_ac' type='checkbox' value='1' <?php echo checked($tw_ac,1,false); ?>/>
										<?php esc_html_e('Incluye el evento estándar "AddToCart" cada vez que se carga la página del carrito.','social-pixel'); ?>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr>
						  	<th scope="row"><?php esc_html_e('InitiateCheckout','social-pixel'); ?></th>
						  	<td>
						  		<fieldset>
									<label for="tw_ic">
										<input name='soc_pix_options[tw_ic]' id='tw_ic' type='checkbox' value='1' <?php echo checked($tw_ic,1,false); ?>/>
										<?php esc_html_e('Incluye el evento estándar "InitiateCheckout" cada vez que se carga la página de finalizar la compra.','social-pixel'); ?>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr>
						  	<th scope="row"><?php esc_html_e('Purchase','social-pixel'); ?></th>
						  	<td>
						  		<fieldset>
									<label for="tw_pc">
										<input name='soc_pix_options[tw_pc]' id='tw_pc' type='checkbox' value='1' <?php echo checked($tw_pc,1,false); ?>/>
										<?php esc_html_e('Incluye el evento estándar "Purchase" cada vez que se carga la página de agradecimiento tras realizar una compra.','social-pixel'); ?>
									</label>
								</fieldset>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>			
			<!-- LINKEDIN -->
        	<div class="tab-pane" id="linkedin">
    			<table class="form-table">
      				<tbody>
						<tr>
							<th colspan="2">
								<h3><?php esc_html_e('Linkedin', 'social-pixel');?></h3>
								<hr>
							</th>
						</tr>
						<tr>
						  	<th scope="row"><?php esc_html_e('Identificador','social-pixel'); ?></th>
						  	<td>
						  		<fieldset>
									<label for="in_id">
										<input name='soc_pix_options[in_id]' id='in_id' type='text' value='<?php echo $in_id; ?>' />
										<p class="description">
											<?php esc_html_e('Ayuda para obtener el identificador del tag de Linkedin','social-pixel'); ?> 
											<a href="https://www.labschool.es/configurar-pixel-de-seguimiento-de-facebook-twitter-linkedin-y-pinterest/#linkedin" target="_blank" rel="noopener">
											<?php esc_html_e('aquí.','social-pixel'); ?>
											</a>
										</p>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr>
						  	<th scope="row"><?php esc_html_e('Activación','social-pixel'); ?></th>
						 	<td>
						 		<fieldset>
									<label for="in_in">
										<input name='soc_pix_options[in_in]' id='in_in' type='checkbox' value='1' <?php echo checked($in_in,1,false); ?>/>
										<?php esc_html_e('Activa esta opción para agregar el tag de Linkedin.','social-pixel'); ?>
									</label>
								</fieldset>
							</td>
						</tr>
					</tbody>
				</table>
			</div>			
			<!-- PINTEREST -->
        	<div class="tab-pane" id="pinterest">
    			<table class="form-table">
      				<tbody>
						<tr>
							<th colspan="2">
								<h3><?php esc_html_e('Pinterest', 'social-pixel');?></h3>
								<hr>
							</th>
						</tr>
						<tr>
						  	<th scope="row"><?php esc_html_e('Identificador','social-pixel'); ?></th>
						  	<td>
						  		<fieldset>
									<label for="pn_id">
										<input name='soc_pix_options[pn_id]' id='pn_id' type='text' value='<?php echo $pn_id; ?>' />
										<p class="description">
											<?php esc_html_e('Ayuda para obtener el identificador del tag de Pinterest','social-pixel'); ?> 
											<a href="https://www.labschool.es/configurar-pixel-de-seguimiento-de-facebook-twitter-linkedin-y-pinterest/#pinterest" target="_blank" rel="noopener">
											<?php esc_html_e('aquí.','social-pixel'); ?>
											</a>
										</p>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr>
						  	<th scope="row"><?php esc_html_e('Activación','social-pixel'); ?></th>
						  	<td>
						  		<fieldset>
									<label for="pn_in">
										<input name='soc_pix_options[pn_in]' id='pn_in' type='checkbox' value='1' <?php echo checked($pn_in,1,false); ?>/>
										<?php esc_html_e('Activa esta opción para agregar el tag de Pinterest.','social-pixel'); ?>
									</label>
								</fieldset>
							</td>
						</tr>
        				<?php if(class_exists('woocommerce')){ ?>
						<tr>
						  	<th class="row" colspan="2">
						  		<h4><?php esc_html_e('Configuración para Woocommerce','social-pixel'); ?></h4>
								<hr/>
						  	</th>
						</tr>
						<tr>
						  	<th scope="row"><?php esc_html_e('PageVisit','social-pixel'); ?></th>
						  	<td>
								<fieldset>
									<label for="pn_vc">
										<input name='soc_pix_options[pn_vc]' id='pn_vc' type='checkbox' value='1' <?php echo checked($pn_vc,1,false); ?>/>
										<?php esc_html_e('Incluye el evento estándar "PageVisit" cada vez que se carga una página de producto.','social-pixel'); ?>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr>
						  	<th scope="row"><?php esc_html_e('AddToCart','social-pixel'); ?></th>
						  	<td>
								<fieldset>
									<label for="pn_ac">
										<input name='soc_pix_options[pn_ac]' id='pn_ac' type='checkbox' value='1' <?php echo checked($pn_ac,1,false); ?>/>
										<?php esc_html_e('Incluye el evento estándar "AddToCart" cada vez que se carga la página del carrito.','social-pixel'); ?>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr>
						  	<th scope="row"><?php esc_html_e('InitiateCheckout','social-pixel'); ?></th>
						  	<td>
								<fieldset>
									<label for="pn_ic">
										<input name='soc_pix_options[pn_ic]' id='pn_ic' type='checkbox' value='1' <?php echo checked($pn_ic,1,false); ?>/>
										<?php esc_html_e('Incluye el evento personalizado "InitiateCheckout" cada vez que se carga la página de finalizar la compra.','social-pixel'); ?>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr>
						  	<th scope="row"><?php esc_html_e('Checkout','social-pixel'); ?></th>
						  	<td>
								<fieldset>
									<label for="pn_pc">
										<input name='soc_pix_options[pn_pc]' id='pn_pc' type='checkbox' value='1' <?php echo checked($pn_pc,1,false); ?>/>
										<?php esc_html_e('Incluye el evento estándar "Checkout" cada vez que se carga la página de agradecimiento tras realizar una compra.','social-pixel'); ?>
									</label>
								</fieldset>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<!-- TIKTOK -->
        	<div class="tab-pane" id="tiktok">
    			<table class="form-table">
      				<tbody>
						<tr>
							<th colspan="2">
								<h3><?php esc_html_e('TikTok', 'social-pixel');?></h3>
								<hr>
							</th>
						</tr>
						<tr>
						  	<th scope="row"><?php esc_html_e('Identificador','social-pixel'); ?></th>
						  	<td>
						  		<fieldset>
									<label for="tt_id">
										<input name='soc_pix_options[tt_id]' id='tt_id' type='text' value='<?php echo $tt_id; ?>' />
										<p class="description">
											<?php esc_html_e('Ayuda para obtener el identificador del tag de TikTok','social-pixel'); ?>
											<a href="https://www.labschool.es/configurar-pixel-de-seguimiento-de-facebook-twitter-linkedin-y-pinterest/#tiktok" target="_blank" rel="noopener">
											<?php esc_html_e('aquí.','social-pixel'); ?>
											</a>
										</p>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr>
						  	<th scope="row"> <?php esc_html_e('Activación','social-pixel'); ?>
						  	<td>
						  		<fieldset>
									<label for="tt_in">
										<input name='soc_pix_options[tt_in]' id='tt_in' type='checkbox' value='1' <?php echo checked($tt_in,1,false); ?>/>
										<?php esc_html_e('Activa esta opción para agregar el tag de TikTok.','social-pixel'); ?>
									</label>
								</fieldset>
							</td>
						</tr>
						<?php if(class_exists('woocommerce')){ ?>
        				<tr>
          					<th class="row" colspan="2">
          						<h4><?php esc_html_e('Configuración para Woocommerce','social-pixel'); ?></h4>
            					<hr/>
          					</th>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e('ViewContent','social-pixel'); ?></th>
						  	<td>
						  		<fieldset>
									<label for="tt_vc">
										<input name='soc_pix_options[tt_vc]' id='tt_vc' type='checkbox' value='1' <?php echo checked($tt_vc,1,false); ?>/>
										<?php esc_html_e('Incluye el evento estándar "ViewContent" cada vez que se carga una página de producto.','social-pixel'); ?>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr>
						  	<th scope="row"><?php esc_html_e('AddToCart','social-pixel'); ?></th>
						  	<td>
						  		<fieldset>
									<label for="tt_ac">
										<input name='soc_pix_options[tt_ac]' id='tt_ac' type='checkbox' value='1' <?php echo checked($tt_ac,1,false); ?>/>
										<?php esc_html_e('Incluye el evento estándar "AddToCart" cada vez que se carga la página del carrito.','social-pixel'); ?>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr>
						  	<th scope="row"><?php esc_html_e('InitiateCheckout','social-pixel'); ?></th>
						  	<td>
						  		<fieldset>
									<label for="tt_ic">
										<input name='soc_pix_options[tt_ic]' id='tt_ic' type='checkbox' value='1' <?php echo checked($tt_ic,1,false); ?>/>
										<?php esc_html_e('Incluye el evento estándar "InitiateCheckout" cada vez que se carga la página de finalizar la compra.','social-pixel'); ?>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr>
						  	<th scope="row"><?php esc_html_e('PlaceAnOrder','social-pixel'); ?></th>
						  	<td>
						  		<fieldset>
									<label for="tt_pc">
										<input name='soc_pix_options[tt_pc]' id='tt_pc' type='checkbox' value='1' <?php echo checked($tt_pc,1,false); ?>/>
										<?php esc_html_e('Incluye el evento estándar "PlaceAnOrder" cada vez que se carga la página de agradecimiento tras realizar una compra.','social-pixel'); ?>
									</label>
								</fieldset>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>				
			<!-- GOOGLE ANALYTICS -->
        	<div class="tab-pane" id="google-analytics">
    			<table class="form-table">
      				<tbody>
						<tr>
							<th colspan="2">
								<h3><?php esc_html_e('Google Analytics', 'social-pixel');?></h3>
								<hr>
							</th>
						</tr>
						<tr>
						  	<th scope="row"><?php esc_html_e('Identificador','social-pixel'); ?></th>
						  	<td>
						  		<fieldset>
									<label for="ga_id">
										<input name='soc_pix_options[ga_id]' id='ga_id' type='text' value='<?php echo $ga_id; ?>' />
										<p class="description">
											<?php esc_html_e('Ayuda para obtener el identificador del tag de Google Analytics','social-pixel'); ?> 
											<a href="https://www.labschool.es/configurar-pixel-de-seguimiento-de-facebook-twitter-linkedin-y-pinterest/#google-analytics" target="_blank" rel="noopener">
											<?php esc_html_e('aquí.','social-pixel'); ?>
											</a>
										</p>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr>
						  	<th scope="row"><?php esc_html_e('Activación','social-pixel'); ?></th>
						  	<td>
						  		<fieldset>
									<label for="ga_in">
										<input name='soc_pix_options[ga_in]' id='ga_in' type='checkbox' value='1' <?php echo checked($ga_in,1,false); ?>/>
										<?php esc_html_e('Activa esta opción para agregar el tag de Google Analytics.','social-pixel'); ?>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr>
						  	<th class="row" colspan="2"> 
						  		<h4><?php esc_html_e('Configuración avanzada','social-pixel'); ?></h4>
								<hr/>
						  	</th>
						</tr>
						<tr>
						  	<th scope="row"><?php esc_html_e('Informes demográficos','social-pixel'); ?></th>
						  	<td>
						  		<fieldset>
									<label for="ga_ds">
										<input name='soc_pix_options[ga_ds]' id='ga_ds' type='checkbox' value='1' <?php echo checked($ga_ds,1,false); ?>/>
										<?php esc_html_e('Activa esta opción para usar los informes demográficos.','social-pixel'); ?>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr>
						  	<th scope="row"><?php esc_html_e('Anonimizar IP','social-pixel'); ?></th>
						  	<td>
						  		<fieldset>
									<label for="ga_ip">
										<input name='soc_pix_options[ga_ip]' id='ga_ip' type='checkbox' value='1' <?php echo checked($ga_ip,1,false); ?>/>
										<?php esc_html_e('Activa esta opción para anonimizar IP.','social-pixel'); ?>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr>
						  	<th scope="row"><?php esc_html_e('Atribución de enlace mejorada','social-pixel'); ?></th>
						  	<td>
						  		<fieldset>
									<label for="ga_ln">
										<input name='soc_pix_options[ga_ln]' id='ga_ln' type='checkbox' value='1' <?php echo checked($ga_ln,1,false); ?>/>
										<?php esc_html_e('Activa esta opción para usar atribución de enlace mejorada.','social-pixel'); ?>
									</label>
								</fieldset>
							</td>
						</tr>
						<?php if(class_exists('woocommerce')){ ?>
						<tr>
						  	<th class="row" colspan="2">
						  		<h4><?php esc_html_e('Configuración para Woocommerce','social-pixel'); ?></h4>
								<hr/>
						  	</th>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e('View Item','social-pixel'); ?></th>
						  	<td>
						  		<fieldset>
									<label for="ga_vi">
										<input name='soc_pix_options[ga_vi]' id='ga_vi' type='checkbox' value='1' <?php echo checked($ga_vi,1,false); ?>/>
										<?php esc_html_e('Incluye el evento estándar "View Item" cada vez que se carga una página de producto.','social-pixel'); ?>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr>
						  	<th scope="row"><?php esc_html_e('Add To Cart','social-pixel'); ?></th>
						  	<td>
						  		<fieldset>
									<label for="ga_ac">
										<input name='soc_pix_options[ga_ac]' id='ga_ac' type='checkbox' value='1' <?php echo checked($ga_ac,1,false); ?>/>
										<?php esc_html_e('Incluye el evento estándar "Add To Cart" cada vez que se agrega un producto al carrito.','social-pixel'); ?>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr>
						  	<th scope="row"><?php esc_html_e('Begin Checkout','social-pixel'); ?></th>
						  	<td>
						  		<fieldset>
									<label for="ga_bc">
										<input name='soc_pix_options[ga_bc]' id='ga_bc' type='checkbox' value='1' <?php echo checked($ga_bc,1,false); ?>/>
										<?php esc_html_e('Incluye el evento estándar "Begin Checkout" cada vez que se carga la página del carrito.','social-pixel'); ?>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr>
						  	<th scope="row"><?php esc_html_e('Checkout Progress','social-pixel'); ?></th>
						  	<td>
						  		<fieldset>
									<label for="ga_cp">
										<input name='soc_pix_options[ga_cp]' id='ga_cp' type='checkbox' value='1' <?php echo checked($ga_cp,1,false); ?>/>
										<?php esc_html_e('Incluye el evento estándar "Checkout Progress" cada vez que se carga la página de finalizar la compra.','social-pixel'); ?>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr>
						  	<th scope="row"><?php esc_html_e('Purchase','social-pixel'); ?></th>
						  	<td>
						  		<fieldset>
									<label for="ga_pc">
										<input name='soc_pix_options[ga_pc]' id='ga_pc' type='checkbox' value='1' <?php echo checked($ga_pc,1,false); ?>/>
										<?php esc_html_e('Incluye el evento estándar "Purchase" cada vez que se carga la página de agradecimiento tras realizar una compra.','social-pixel'); ?>
									</label>
								</fieldset>
							</td>
						</tr>
						<?php } ?>
      				</tbody>
    			</table>
    		</div>
    		<!-- WOOCOMMERCE -->
			<?php if(class_exists('woocommerce')){ ?>
			<div class="tab-pane" id="woocommerce">
    			<table class="form-table">
      				<tbody>
      					<tr>
							<th colspan="2">
								<h3><?php esc_html_e('Woocommerce', 'social-pixel');?></h3>
								<hr>
							</th>
						</tr>
        				<tr>
          					<th scope="row"><?php esc_html_e('Identificador producto','social-pixel'); ?></th>
          					<td>
								<fieldset>
									<label for="fb_re">
										<select name='soc_pix_options[fb_re]' id="fb_re">
											<option value='1' <?php echo selected($fb_re,1,false); ?>><?php esc_html_e('ID Producto','social-pixel'); ?></option>
											<option value='0' <?php echo selected($fb_re,0,false); ?>><?php esc_html_e('SKU','social-pixel'); ?></option>
										</select>
										<p class="description"><?php esc_html_e('Puedes identificar la referencia del producto mediante el ID de Wordpress o el SKU que hayas definido.','social-pixel'); ?></p>
									</label>
								</fieldset>
            				</td>
        				</tr>
        				<tr>
          					<th scope="row"><?php esc_html_e('Aplicar impuestos','social-pixel'); ?></th>
          					<td>
          						<fieldset>
            						<label for="fb_tx">
            							<input name='soc_pix_options[fb_tx]' id='fb_tx' type='checkbox' value='1' <?php echo checked($fb_tx,1,false); ?>/>
            							<?php esc_html_e('Incluye los impuestos sobre los precios recogidos en los diferentes eventos activados.','social-pixel'); ?>
            						</label>
            					</fieldset>
            				</td>
        				</tr>
        			</tbody>
        		</table>
        	</div>
        	<?php } ?>
    	</div>
    	<p class="submit">
      		<input class="button-primary" type="submit" name="submit" value="<?php esc_html_e('Guardar cambios','social-pixel'); ?>"/>
    	</p>
  	</form>
</div>
<?php
}

// CARGAR FORMULARIO
add_action('admin_init', 'soc_pix_admin_init');
function soc_pix_admin_init(){
	register_setting('soc_pix_options','soc_pix_options','soc_pix_validate');	
}

// GUARDAR OPCIONES
function soc_pix_validate($form){
	$options 		  = get_option('soc_pix_options');
	$updated 		  = $options;
	$updated['fb_re'] = $form['fb_re'];	
	$updated['fb_tx'] = $form['fb_tx'];
	$updated['fb_id'] = $form['fb_id'];
	$updated['fb_in'] = $form['fb_in'];
	$updated['fb_vp'] = $form['fb_vp'];
	$updated['fb_ac'] = $form['fb_ac'];
	$updated['fb_ic'] = $form['fb_ic'];
	$updated['fb_pc'] = $form['fb_pc'];
	$updated['fb_vs'] = $form['fb_vs'];
	$updated['fb_vc'] = $form['fb_vc'];
	$updated['fb_vt'] = $form['fb_vt'];
	$updated['tw_id'] = $form['tw_id'];
	$updated['tw_in'] = $form['tw_in'];
	$updated['tw_vc'] = $form['tw_vc'];
	$updated['tw_ac'] = $form['tw_ac'];
	$updated['tw_ic'] = $form['tw_ic'];
	$updated['tw_pc'] = $form['tw_pc'];
	$updated['in_id'] = $form['in_id'];
	$updated['in_in'] = $form['in_in'];
	$updated['pn_id'] = $form['pn_id'];
	$updated['pn_in'] = $form['pn_in'];
	$updated['pn_vc'] = $form['pn_vc'];
	$updated['pn_ac'] = $form['pn_ac'];
	$updated['pn_ic'] = $form['pn_ic'];
	$updated['pn_pc'] = $form['pn_pc'];
	$updated['tt_id'] = $form['tt_id'];
	$updated['tt_in'] = $form['tt_in'];
	$updated['tt_vc'] = $form['tt_vc'];
	$updated['tt_ac'] = $form['tt_ac'];
	$updated['tt_ic'] = $form['tt_ic'];
	$updated['tt_pc'] = $form['tt_pc'];
	$updated['ga_id'] = $form['ga_id'];
	$updated['ga_in'] = $form['ga_in'];
	$updated['ga_ds'] = $form['ga_ds'];
	$updated['ga_ip'] = $form['ga_ip'];
	$updated['ga_ln'] = $form['ga_ln'];
	$updated['ga_vi'] = $form['ga_vi'];
	$updated['ga_ac'] = $form['ga_ac'];
	$updated['ga_bc'] = $form['ga_bc'];
	$updated['ga_cp'] = $form['ga_cp'];
	$updated['ga_pc'] = $form['ga_pc'];
	return $updated;
}