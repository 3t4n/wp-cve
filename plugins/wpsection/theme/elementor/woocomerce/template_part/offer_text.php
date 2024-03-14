   

<?php if (isset($settings['show_offer_x_event']) && $settings['show_offer_x_event']) { ?>
<div class="wps_order order-<?php echo $settings['position_order_ten']; ?> ">          
	<div class="wps_offer_text">

		<?php if (!get_post_meta( get_the_id(), 'meta_show_offer_text', true ) ) : ?>                                        

		<p>                  
			<?php echo wp_kses(get_post_meta(get_the_id(), 'meta_offer_text', true), wp_kses_allowed_html('post')); ?>                                                       
		</p>

		<?php endif; ?>                                          

	</div>

</div>
<?php  }  ?>                             
                                    

