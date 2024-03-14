<?php if($wl_rcsm_options['maintenance_date'] != '') { ?>
<script>
jQuery(document).ready(function(jQuery) {
	countdown('<?php echo esc_attr($wl_rcsm_options['maintenance_date']); ?>', rcsm_callback); /* Date format ('MM/DD/YYYY  HH:MM:SS TT'); */
	function rcsm_callback(){
		//jQuery('.container-fluid.count').hide();
		//location.reload(true);		
	};
});

</script>
<?php } ?>
<div class="container-fluid space count">
	<div class="container" id="timer">
		<div class="row maintance-detail">
			<h2 data-sr="enter top"><span class="icon <?php echo esc_attr($wl_rcsm_options['counter_title_icon']); ?> "></span> <?php echo esc_html($wl_rcsm_options['counter_title']); ?></h2>
			<p class="desc"><?php echo esc_html($wl_rcsm_options['counter_msg']); ?></p>
		<?php 
			if($wl_rcsm_options['maintenance_date']!='') { ?>
			<div class="row countDown" data-sr="enter bottom">
				<div class="rotate"><div class="clock days wow fadeInUp" id="days" data-sr="enter bottom over 1s and move 110px wait 0.3s"><span class='digits'><?php esc_html_e('00', 'RCSM_TEXT_DOMAIN')?></span><span class='text'><?php esc_html_e('Days', 'RCSM_TEXT_DOMAIN')?></span></div></div>
				<div class="rotate"><div class="clock hours wow fadeInUp" id="hours" data-sr="enter bottom over 1s and move 110px wait 0.3s"><span class='digits'><?php esc_html_e('00', 'RCSM_TEXT_DOMAIN')?></span> <span class='text'><?php esc_html_e('Hours', 'RCSM_TEXT_DOMAIN')?></span></div></div>
				<div class="rotate "><div class="clock minutes wow fadeInUp" id="minutes" data-sr="enter bottom over 1s and move 110px wait 0.3s"><span class='digits'><?php esc_html_e('00', 'RCSM_TEXT_DOMAIN')?></span> <span class='text'><?php esc_html_e('Minutes', 'RCSM_TEXT_DOMAIN')?></span></div></div>
				<div class="rotate"><div class="clock seconds wow fadeInUp" id="seconds" data-sr="enter bottom over 1s and move 110px wait 0.3s"><span class='digits'><?php esc_html_e('00', 'RCSM_TEXT_DOMAIN')?></span> <span class='text'><?php esc_html_e('Seconds', 'RCSM_TEXT_DOMAIN')?></span></div></div>
			</div>
		<?php } ?>
		</div>	
	</div>
</div>