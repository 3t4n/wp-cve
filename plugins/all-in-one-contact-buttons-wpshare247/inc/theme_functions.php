<?php
/**
 * @class   WS247_aio_ct_button_Theme
 */
 
if( !class_exists('WS247_aio_ct_button_Theme') ):
	class WS247_aio_ct_button_Theme{
		/**
		 * Constructor
		 */
		function __construct() {
			$this->init();
		}
		
		public function init(){
			add_action('wp_footer', array($this, 'wle_contact_icons_display'), 99999, 0);
		}
		
		function wle_contact_icons_display(){
			$shake_hotline = Ws247_aio_ct_button::class_get_option('shake_hotline');
			$hide_shake_hotline = Ws247_aio_ct_button::class_get_option('hide_shake_hotline');
			$shake_hotline_pos = Ws247_aio_ct_button::class_get_option('shake_hotline_pos'); 
			$icons_pos = Ws247_aio_ct_button::class_get_option('icons_pos'); 
			
			$company_zalo = Ws247_aio_ct_button::class_get_option('company_zalo'); 
			$stt_email = Ws247_aio_ct_button::class_get_option('stt_email'); 
			$stt_hotline = Ws247_aio_ct_button::class_get_option('stt_hotline'); 
			$icon_google_map = Ws247_aio_ct_button::class_get_option('icon_google_map'); 
			$icon_fb_messenger = Ws247_aio_ct_button::class_get_option('icon_fb_messenger'); 
			
			$text_fb_messenger = Ws247_aio_ct_button::class_get_option('text_fb_messenger');
			$text_company_zalo = Ws247_aio_ct_button::class_get_option('text_company_zalo'); 
			$text_stt_email = Ws247_aio_ct_button::class_get_option('text_stt_email'); 
			$text_stt_hotline = Ws247_aio_ct_button::class_get_option('text_stt_hotline'); 
			$text_icon_google_map = Ws247_aio_ct_button::class_get_option('text_icon_google_map');
			
			$hide_company_zalo = Ws247_aio_ct_button::class_get_option('hide_company_zalo'); 
			$hide_stt_email = Ws247_aio_ct_button::class_get_option('hide_stt_email'); 
			$hide_stt_hotline = Ws247_aio_ct_button::class_get_option('hide_stt_hotline'); 
			$hide_icon_google_map = Ws247_aio_ct_button::class_get_option('hide_icon_google_map'); 
			$hide_icon_fb_messenger = Ws247_aio_ct_button::class_get_option('hide_icon_fb_messenger'); 
			
			$primary_color = Ws247_aio_ct_button::class_get_option('primary_color');
			$hover_color = Ws247_aio_ct_button::class_get_option('hover_color'); 
			$text_color = Ws247_aio_ct_button::class_get_option('text_color');   
			$text_contact_color = Ws247_aio_ct_button::class_get_option('text_contact_color'); 
			
			$text_contact = Ws247_aio_ct_button::class_get_option('text_contact'); 
			if(!$text_contact){ $text_contact = __('Contact', WS247_AIO_CT_BUTTON_TEXTDOMAIN); } 
			
			$shake_hotline_bottom = (int)Ws247_aio_ct_button::class_get_option('shake_hotline_bottom');
			$icons_bottom = (int)Ws247_aio_ct_button::class_get_option('icons_bottom'); 
			
			$talkto_embed = Ws247_aio_ct_button::class_get_option('talkto_embed');
			
			
			$hide_icons = Ws247_aio_ct_button::class_get_option('hide_icons'); 

			$zalo_ring = Ws247_aio_ct_button::class_get_option('is_zalo_shake_hotline'); 

			if($shake_hotline && $hide_shake_hotline != 'on'){

				$shake_hotline_t = 'tel:'.esc_attr($shake_hotline);

				if($zalo_ring){
					$shake_hotline_t = esc_attr($shake_hotline);
					$shake_hotline = 'Zalo';
				}
			?>
				<style>
                	.phonering-alo-phone {
						<?php 
						if($shake_hotline_pos==2){
							echo 'right:110px;';
						}else{
							echo 'left:-50px;';
						}
						?>
					}
                </style>
				<div class="hotline <?php if($shake_hotline_pos==2){ echo 'hotline-on-right'; } ?>">
					<div id="phonering-alo-phoneIcon" class="phonering-alo-phone phonering-alo-green phonering-alo-show">
                    	<span class="number"><a href="<?php echo $shake_hotline_t; ?>"><i class="fas fa-caret-left"></i><?php echo esc_attr($shake_hotline); ?></a></span>
						<div class="phonering-alo-ph-circle"></div>
						<div class="phonering-alo-ph-circle-fill"></div>
						<div class="phonering-alo-ph-img-circle <?php if($zalo_ring){ ?>zalo-ring<?php } ?>">
							<a class="pps-btn-img" href="<?php echo $shake_hotline_t; ?>"></a>
						</div>
					</div>
				</div>
			<?php
			}
			?>
            
            <style>
				<?php 
				if($primary_color):
				?>
				.phonering-alo-phone.phonering-alo-hover .phonering-alo-ph-img-circle, .phonering-alo-phone:hover .phonering-alo-ph-img-circle,
            	.phonering-alo-phone.phonering-alo-green .phonering-alo-ph-img-circle, #phonering-alo-phoneIcon .number a,
				#phonering-alo-phoneIcon .number a, #ft-contact-icons li span.ab {
					background-color: <?php echo esc_attr($primary_color); ?>;
				}
				.phonering-alo-phone.phonering-alo-hover .phonering-alo-ph-circle, .phonering-alo-phone:hover .phonering-alo-ph-circle,
				.phonering-alo-phone.phonering-alo-green .phonering-alo-ph-circle {
					border-color: <?php echo esc_attr($primary_color); ?>;
				}
				#phonering-alo-phoneIcon .number i, #ft-contact-icons li span.ab i{
					color: <?php echo esc_attr($primary_color); ?>;
				}
				<?php 
				endif;
				?>
				
				<?php 
				if($hover_color):
				?>
				#ft-contact-icons li a:hover span.ab,.phonering-alo-phone.phonering-alo-green.phonering-alo-hover .phonering-alo-ph-img-circle, .phonering-alo-phone.phonering-alo-green:hover .phonering-alo-ph-img-circle, #phonering-alo-phoneIcon:hover .number a{
					background-color:<?php echo esc_attr($hover_color);?>;
				}
				.phonering-alo-phone.phonering-alo-green.phonering-alo-hover .phonering-alo-ph-circle, 
				.phonering-alo-phone.phonering-alo-green:hover .phonering-alo-ph-circle, #phonering-alo-phoneIcon:hover .number a{
					border-color: <?php echo esc_attr($hover_color);?>;
				}
				#phonering-alo-phoneIcon:hover .number i, #ft-contact-icons li a:hover span.ab i{
					color: <?php echo esc_attr($hover_color);?>;
				}
				<?php 
				endif;
				?>
				
				<?php 
				if($text_color):
				?>
					#ft-contact-icons li span.ab, #phonering-alo-phoneIcon .number a{
						color:<?php echo esc_attr($text_color); ?>;
					}
				<?php 
				endif;
				?>
				
				<?php 
				if($shake_hotline_bottom):
				?>
					.phonering-alo-phone{
						bottom:<?php echo esc_attr($shake_hotline_bottom);?>px;
					}
				<?php 
				endif;
				?>
				
				<?php 
				if($icons_bottom):
				?>
					.show-all-icon, #ft-contact-icons{
						bottom:<?php echo esc_attr($icons_bottom); ?>px;
					}
				<?php 
				endif;
				?>
				
				<?php 
				if($text_contact_color):
				?>
					.show-all-icon, .show-all-icon i{
						color:<?php echo esc_attr($text_contact_color); ?>;
					}
				<?php 
				endif;
				?>
				
            </style>
            
            <script>
            	jQuery(document).ready(function(e) {
					jQuery(".js-show-all-icon").click(function(e) {
                        if(jQuery("#ft-contact-icons").hasClass('active')){
							jQuery("#ft-contact-icons").removeClass('active');
							jQuery(this).removeClass('hide-me');
						}else{
							jQuery("#ft-contact-icons").addClass('active');
							jQuery(this).addClass('hide-me');
						}
						return false;
                    });
					
					jQuery(".js-hide-all-icon").click(function(e) {
                        jQuery(".js-show-all-icon").click();
						return false;
                    });
				});
            </script>
			
			<?php 
			if($hide_icons != 'on'){
				$hide_def = 'hide-me';
				$active_def = 'active';
			?>
            <a id="ws247-aio-ct-button-show-all-icon" href="#" class="<?php echo $hide_def;?> js-show-all-icon show-all-icon <?php if($icons_pos!=2) echo 'contact-icons-right'; ?>"><span><?php echo esc_attr($text_contact);?></span><i class="fas fa-long-arrow-alt-up"></i></a>
			<ul id="ft-contact-icons" class="<?php echo $active_def;?> <?php if($icons_pos!=2) echo 'contact-icons-right'; ?>">
            	<?php 
				if($company_zalo && $hide_company_zalo != 'on'){
					$zalo_link = 'https://zalo.me/'.esc_attr($company_zalo);
					if(strpos($company_zalo, "http") !== false){
						$zalo_link = $company_zalo;
					}
				?>
				<li class="icon-zalo">
					<a target="_blank" href="<?php echo esc_attr($zalo_link); ?>">
                    	<span class="icon"></span>
                        <?php 
						if($text_company_zalo){
						?>
                        <span class="ab"><i class="fas fa-caret-left"></i> <label><?php echo esc_attr($text_company_zalo); ?></label></span>
                        <?php 
						}
						?>
                    </a>
                    
				</li>
				<?php 
				}
				?>
                
				<?php 
				if($icon_fb_messenger && $hide_icon_fb_messenger != 'on'){
				?>
				<li class="icon-messenger">
					<a target="_blank" href="<?php echo esc_attr($icon_fb_messenger); ?>">
                    	<span class="icon"></span>
                        <?php 
						if($text_fb_messenger){
						?>
                        <span class="ab"><i class="fas fa-caret-left"></i> <label><?php echo esc_attr($text_fb_messenger); ?></label></span>
                        <?php 
						}
						?>
                    </a>
                    
				</li>
				<?php 
				}
				?>
                
                <?php 
				if($stt_hotline && $hide_stt_hotline != 'on'):
				?>
				<li class="icon-phone">
					<a href="tel:<?php echo esc_html($stt_hotline);?>" target="_blank">
                    	<span class="icon"><i class="fas fa-phone" aria-hidden="true"></i></span>
                        <?php 
						if($text_stt_hotline){
						?>
                        <span class="ab"><i class="fas fa-caret-left"></i> <label><?php echo esc_attr($text_stt_hotline); ?></label></span>
                        <?php 
						}
						?>
                    </a>
                   	
				</li>
				<?php 
				endif;
				?>
				
				
				
				<?php 
				if($stt_email && $hide_stt_email != 'on'):
				?>
				<li class="icon-envelope">
					<a href="mailto:<?php echo esc_html($stt_email);?>" target="_blank">
                    	<span class="icon"><i class="fas fa-envelope" aria-hidden="true"></i></span>
                        <?php 
						if($text_stt_email ){
						?>
                        <span class="ab"><i class="fas fa-caret-left"></i> <label><?php echo esc_attr($text_stt_email); ?></label></span>
                        <?php 
						}
						?>
                    </a>
                    
				</li>
				<?php 
				endif;
				?>
				
				
				
				
				<?php 
				if($icon_google_map && $hide_icon_google_map != 'on'):
				?>
				<li class="icon-map">
					<a href="https://maps.google.com/maps?q=<?php echo esc_html($icon_google_map);?>&hl=vi&ie=UTF8" target="_blank">
                    	<span class="icon"><i class="fas fa-map-marker" aria-hidden="true"></i></span>
                        <?php 
						if($text_icon_google_map){
						?>
                        <span class="ab"><i class="fas fa-caret-left"></i> <label><?php echo esc_attr($text_icon_google_map); ?></label></span>
                        <?php 
						}
						?>
                    </a>
					
                </li>
				<?php 
				endif;
				?>
                
                <li><a href="#" class="js-hide-all-icon"><span class="icon"><i class="fas fa-times"></i></span></a></li>
				
			</ul>
            
            <?php 
			if($talkto_embed){
				echo $talkto_embed;
			}
			?>
            
			<?php
			}
		}

	
	//End class------------------------
	}
	
	//Init
	$WS247_aio_ct_button_Theme = new WS247_aio_ct_button_Theme();
	
endif;