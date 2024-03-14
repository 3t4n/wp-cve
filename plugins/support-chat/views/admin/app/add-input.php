<div class="wpsaio-app-input-wrap <?php echo esc_attr( $state ); ?>" data-appname="<?php echo esc_attr( $type ); ?>">
	<div class="wpsaio-handler-sort">
		<div class="handler-sort">
			<span class="saio-icon-sort"></span>
		</div>
		<div style="<?php echo esc_attr( 'background:'. $color_icon ); ?>" class="wp-saio-icon wp-saio-icon-<?php echo esc_attr( $type ); ?>"><?php echo $icon; ?></div>
		<?php if (str_contains($type, 'custom-app')) {
			$class = "wp-saio-$type";
			?>
			<input require id="wp-saio-custom-app-title-input" class="wp-saio-title <?php echo esc_attr($class) ?>" type='text' value='<?php echo $title; ?>' />
		<?php } else { ?>
		<div class="wp-saio-title"><?php echo $title; ?></div>
        <?php } ?>
	</div>
	<div class="wpsaio-input-content">
		<div class="wp-saio-input-wrap">
			<?php echo $inputs; ?>
		</div>
		
		<?php if (str_contains($type, 'custom-app')) { ?>
			<div class="wpsaio-btns">
				<span class="wpsaio-btn-move">
					<img src="<?php echo WP_SAIO_URL; ?>/assets/admin/img/move.svg" alt="" />
				</span>
					<div class="wpsaio-btn-remove">
						<span class="saio-icon-close"></span>
					</div>
			</div>
		<?php } ?>
	</div>
</div>