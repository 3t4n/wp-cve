<div class="<?php echo esc_attr('tenweb-opacity tenweb-' . $wd_options->prefix . '-opacity')?>"></div>
<div class="<?php echo esc_attr('tenweb-deactivate-popup tenweb-' . $wd_options->prefix . '-deactivate-popup')?>">
	<div class="<?php echo esc_attr('tenweb-deactivate-popup-opacity tenweb-deactivate-popup-opacity-' . $wd_options->prefix) ?>">
		<img src="<?php echo esc_url($wd_options->wd_url_img . '/spinner.gif'); ?>" class="tenweb-img-loader" >
	</div>
	<form method="post" id="<?php echo esc_attr($wd_options->prefix . '_deactivate_form'); ?>">
		<div class="tenweb-deactivate-popup-header">
			<?php _e( "Please let us know why you are deactivating. Your answer will help us to provide you support or sometimes offer discounts. (Optional)", esc_html($wd_options->prefix) ); ?>:
            <span class="tenweb-deactivate-popup-close-btn"></span>
		</div>

		<div class="tenweb-deactivate-popup-body">
			<?php foreach( $deactivate_reasons as $deactivate_reason_slug => $deactivate_reason ) { ?>
				<div class="<?php echo esc_attr('tenweb-' . $wd_options->prefix . '-reasons'); ?>">
					<input type="radio" value="<?php echo esc_attr($deactivate_reason["id"]);?>" id="<?php echo esc_attr($wd_options->prefix . "-" .$deactivate_reason["id"]); ?>" name="<?php echo esc_attr($wd_options->prefix . '_reasons'); ?>" >
					<label for="<?php echo esc_attr($wd_options->prefix . "-" . $deactivate_reason["id"]); ?>"><?php echo esc_html($deactivate_reason["text"]);?></label>
				</div>
			<?php } ?>
			<div class="<?php echo esc_attr($wd_options->prefix . '_additional_details_wrap'); ?>"></div>
		</div>
		<div class="tenweb-btns">
			<a href="<?php echo esc_url($deactivate_url); ?>" data-val="1" class="button button-secondary button-close" id="<?php echo esc_attr('tenweb-' .  $wd_options->prefix . '-deactivate'); ?>"><?php _e( "Skip and Deactivate" , esc_html($wd_options->prefix) ); ?></a>
			<a href="<?php echo esc_url($deactivate_url); ?>" data-val="2" class=" <?php echo esc_attr('button button-primary button-primary-disabled button-close tenweb-' . $wd_options->prefix . '-deactivate') ; ?>" id="<?php echo esc_attr( 'tenweb-' . $wd_options->prefix . '-submit-and-deactivate') ; ?>"><?php _e( "Submit and Deactivate" , esc_html($wd_options->prefix) ); ?></a>
		</div>
		<input type="hidden" name="<?php echo esc_attr($wd_options->prefix . "_submit_and_deactivate"); ?>" value="" >
		<?php wp_nonce_field( esc_html($wd_options->prefix) . '_save_form', esc_html($wd_options->prefix . '_save_form_fild')); ?>
	</form>
</div>
