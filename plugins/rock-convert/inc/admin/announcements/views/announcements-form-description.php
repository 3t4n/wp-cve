<?php
/**
 * Announcements Form Structure
 *
 * @package Rock_Convert
 */

?>
<div class="postbox">
	<h3 class="hndle" style="padding-left: 20px;padding-bottom: 10px;">
		<span><?php esc_html_e( 'Configurações', 'rock-convert' ); ?></span>
	</h3>
	<div class="inside">
		<label for="activate_announcement" class="announcement">
			<input type="checkbox"
				name="rconvert_activate_announcement" <?php echo '1' === $activated ? 'checked' : null; ?>
				value="<?php echo esc_attr( '1' ); ?>" id="activate_announcement">
			<?php esc_html_e( 'Ativar barra de anúncios', 'rock-convert' ); ?>
		</label>
		<br/>
		<br/>
		<label for="announcement_text">
			<?php esc_html_e( 'Texto do anúncio', 'rock-convert' ); ?><br/>
			<input type="text" name="rconvert_announcement_text" size="80" value="<?php echo esc_attr( $text ); ?>"
				id="announcement_text">
			<div>
				<small>
				<?php esc_html_e( 'É recomendado que o texto tenha no máximo ', 'rock-convert' ); ?>
				<strong><?php esc_html_e( '70 caracteres', 'rock-convert' ); ?></strong>
				</small>
			</div>
		</label><br/>
		<label for="announcement_btn">
			<?php esc_html_e( 'Texto do botão', 'rock-convert' ); ?><br/>
			<input type="text" name="rconvert_announcement_btn" size="50"
				value="<?php echo esc_attr( $btn ); ?>"
				id="announcement_btn">
			<div>
				<small>
					<strong>
						<?php esc_html_e( 'Dica:', 'rock-convert' ); ?>
					</strong>
						<?php
							esc_html_e(
								'Para deixar a barra de anúncios sem botão, basta deixar o campo acima vazio.',
								'rock-convert'
							);
							?>
				</small>
			</div>
		</label>
		<br/>
		<label for="announcement_link">
			<?php esc_html_e( 'Link do botão', 'rock-convert' ); ?><br/>
			<input type="text" name="rconvert_announcement_link" size="50" value="<?php echo esc_url( $ann_link ); ?>"
				id="announcement_link">
		</label>
		<br>
		<br>
		<?php esc_html_e( 'Posição onde a barra deve aparecer', 'rock-convert' ); ?><br/><br>
		<div class="rconvert_announcement_position_preview">
			<img src="<?php echo esc_url( PLUGIN_NAME_URL . 'assets/admin/img/preview-top.png' ); ?>" alt="Top preview"
				class="rconvert_announcement-preview-img"/>
			<label for="announcement_position_top" style="padding-right: 20px;">
				<input type="radio" name="rconvert_announcement_position" id="announcement_position_top" value="<?php echo esc_attr( 'top' ); ?>"
					<?php echo 'top' === $position ? 'checked' : null; ?>> <?php esc_html_e( 'Fixa no topo do site', 'rock-convert' ); ?>
			</label>
		</div>
		<div class="rconvert_announcement_position_preview">
			<img src="<?php echo esc_url( PLUGIN_NAME_URL . 'assets/admin/img/preview-bottom.png' ); ?>" alt="Bottom preview"
				class="rconvert_announcement-preview-img"/>
			<label for="announcement_position_bottom">
				<input type="radio" name="rconvert_announcement_position" id="announcement_position_bottom"
					value="<?php echo esc_attr( 'bottom' ); ?>" <?php echo 'bottom' === $position ? 'checked' : null; ?>>
				<?php esc_html_e( 'Fixa no fundo do site', 'rock-convert' ); ?>
			</label>
		</div>
		<br>
		<div class="clearfix" style="display: block;clear: both;"></div>
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php esc_html_e( 'Salvar todas as configurações', 'rock-convert' ); ?>">
		</p>
	</div>
</div>
