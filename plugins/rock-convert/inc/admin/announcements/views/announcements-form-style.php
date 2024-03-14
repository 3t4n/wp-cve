<?php
/**
 * Announcements Form Style
 *
 * @package Rock_Convert
 */

?>
<div class="postbox">
	<h3 class="hndle" style="padding-left: 20px;padding-bottom: 10px;">
		<span><?php esc_html_e( 'Estilo da barra de anúncios', 'rock-convert' ); ?></span>
	</h3>
	<div class="inside">

		<div style="float: left;width: 300px;">
			<p>
				<label for="rconvert_announcement_bg_color"><?php esc_html_e( 'Cor do fundo', 'rock-convert' ); ?></label><br>
				<input type="text" name="rconvert_announcement_bg_color" class="color-picker"
					id="rconvert_announcement_bg_color" value="<?php echo esc_attr( $bg_color ); ?>"
					data-default-color="<?php echo esc_attr( $default['bg_color'] ); ?>"/>
			</p>

			<p>
				<label for="rconvert_announcement_text_color"><?php esc_html_e( 'Cor do texto', 'rock-convert' ); ?></label><br>
				<input type="text" name="rconvert_announcement_text_color" class="color-picker"
					id="rconvert_announcement_text_color" value="<?php echo esc_attr( $text_color ); ?>"
					data-default-color="<?php echo esc_attr( $default['text_color'] ); ?>"/>
			</p>

			<p>
				<label for="rconvert_announcement_btn_color"><?php esc_html_e( 'Cor do botão', 'rock-convert' ); ?></label><br>
				<input type="text" name="rconvert_announcement_btn_color" class="color-picker"
					id="rconvert_announcement_btn_color" value="<?php echo esc_attr( $btn_color ); ?>"
					data-default-color="<?php echo esc_attr( $default['btn_color'] ); ?>"/>
			</p>

			<p>
				<label for="rconvert_announcement_btn_text_color"><?php esc_html_e( 'Cor do texto do botão', 'rock-convert' ); ?></label><br>
				<input type="text" name="rconvert_announcement_btn_text_color" class="color-picker"
					id="rconvert_announcement_btn_text_color" value="<?php echo esc_attr( $btn_text_color ); ?>"
					data-default-color="<?php echo esc_attr( $default['btn_text_color'] ); ?>"/>
			</p>
		</div>

		<div style="float: left;">
			<span><strong><?php esc_html_e( 'Pre-visualização', 'rock-convert' ); ?></strong></span><br>
			<div class="ann_preview rconvert_announcement_bg_color"
				style="padding: 20px; background-color:<?php echo esc_attr( $bg_color ); ?>;">
				<span style="color: <?php echo esc_attr( $text_color ); ?>"
					class="ann_preview_text rconvert_announcement_text_color"><?php echo esc_attr( $text ); ?></span>
				<?php if ( ! empty( $btn ) && ! empty( $ann_link ) ) { ?>
					<a href="<?php echo esc_url( $ann_link ); ?>"
					class="ann_preview_btn rconvert_announcement_btn_color rconvert_announcement_btn_text_color"
					style="background:<?php echo esc_attr( $btn_color ); ?>;color:<?php echo esc_html( $btn_text_color ); ?>"><?php echo esc_attr( $btn ); ?></a>
				<?php } ?>
			</div>

		</div>
		<br>

		<div class="clearfix" style="display: block;clear: both;"></div>
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php esc_html_e( 'Salvar todas as configurações', 'rock-convert' ); ?>">
		</p>
	</div>

</div>
