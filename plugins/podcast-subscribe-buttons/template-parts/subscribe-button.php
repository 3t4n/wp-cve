<?php
/**
 * @package slt
 */
?>
	
	<div id="podcast-subscribe-button-<?php echo esc_attr($atts['id']) ;?>" class="secondline-psb-<?php echo esc_html($atts[SECONDLINE_PSB_PREFIX . 'select_style']);?>-style secondline-psb-alignment-<?php echo isset($atts['alignment']) ? esc_attr($atts['alignment']) : 'none' ?>">
		<?php
			if ($atts[SECONDLINE_PSB_PREFIX . 'select_type'] != null) {
				if($atts[SECONDLINE_PSB_PREFIX . 'select_type'] == 'modal') {
					include SECONDLINE_PSB_SUBSCRIBE_ELEMENTS_PATH . 'template-parts/modal-button.php';
				} elseif($atts[SECONDLINE_PSB_PREFIX . 'select_type'] == 'inline') {
					include SECONDLINE_PSB_SUBSCRIBE_ELEMENTS_PATH . 'template-parts/inline-button.php';
				} elseif($atts[SECONDLINE_PSB_PREFIX . 'select_type'] == 'list') {
					include SECONDLINE_PSB_SUBSCRIBE_ELEMENTS_PATH . 'template-parts/list-button.php';
				} elseif($atts[SECONDLINE_PSB_PREFIX . 'select_type'] == 'icons') {
					include SECONDLINE_PSB_SUBSCRIBE_ELEMENTS_PATH . 'template-parts/icon-button.php';
				}					
			}
			//wp_reset_query();
			
		?>		
	</div>