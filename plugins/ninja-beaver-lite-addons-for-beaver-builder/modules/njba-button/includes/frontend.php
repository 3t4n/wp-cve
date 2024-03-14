<div class="njba-btn-main <?php echo $settings->button_style; ?>">
    <a href="<?php if ( $settings->link !== '' ) {
		echo $settings->link;
	} ?>" target="<?php if ( $settings->link_target !== '' ) {
		echo $settings->link_target;
	} ?>"
       class="njba-btn <?php echo ( isset( $settings->btn_class ) ) ? $settings->btn_class : ''; ?> <?php echo ( isset( $settings->a_class ) ) ? $settings->a_class : ''; ?> " <?php echo ( isset( $settings->a_data ) ) ? $settings->a_data : ''; ?>>
		<?php if ( $settings->button_icon_aligment === 'left' ) {
			$module->njba_ButtonIcon_Image();
		} ?>
        <span class="njba-button-text"><?php if ( $settings->button_text ) {
				echo $settings->button_text;
			} ?></span>
		<?php if ( $settings->button_icon_aligment === 'right' ) {
			$module->njba_ButtonIcon_Image();
		} ?>
    </a>
</div>
