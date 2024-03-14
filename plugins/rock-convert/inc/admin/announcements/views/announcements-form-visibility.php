<?php
/**
 * Announcements Form Visibility
 *
 * @package Rock_Convert
 */

?>
<div class="postbox">
	<h3 class="hndle" style="padding-left: 20px;padding-bottom: 10px;">
		<span><?php esc_html_e( 'Visibilidade', 'rock-convert' ); ?></span>
	</h3>
	<div class="inside">
		<p style="padding-bottom: 10px;">
			<?php
				esc_html_e(
					'Escolha em quais páginas a barra de anúncios deve ser exibida.',
					'rock-convert'
				);
				?>
		</p>
		<div>
			<input type="radio" class="rock-convert-visibility-control"
				id="rconvert_announcement_visibility_posts"
				name="rock_convert_visibility"
				<?php echo esc_attr( $visibility ) ? 'checked' : ''; ?>
				value="<?php echo esc_attr( 'post' ); ?>"/>
			<label for="rconvert_announcement_visibility_posts">
			<?php esc_html_e( 'Apenas dentro de posts.', 'rock-convert' ); ?>
			<br/>
			<small style="padding-left: 24px;">
				<?php
					esc_html_e(
						'O banner será exibido apenas dentro das páginas de posts do blog.',
						'rock-convert'
					);
					?>
				</small>
			</label>
		</div>
		<br/>
		<div>
			<input type="radio" class="rock-convert-visibility-control"
				id="rconvert_announcement_visibility_all"
				name="rock_convert_visibility"
				<?php echo 'all' === esc_attr( $visibility ) ? 'checked' : ''; ?>
				value="<?php echo esc_attr( 'all' ); ?>"/>
			<label for="rconvert_announcement_visibility_all"><?php esc_html_e( 'Todas páginas.', 'rock-convert' ); ?><br/>
				<small style="padding-left: 24px;">
					<?php
						esc_html_e(
							'O banner será exibido em todas as páginas, inclusive na página inicial.',
							'rock-convert'
						);
						?>
				</small>
			</label>
		</div>
		<br/>
		<div>
			<input type="radio" class="rock-convert-visibility-control"
				id="rconvert_announcement_visibility_exclude"
				name="rock_convert_visibility"
				<?php echo 'exclude' === esc_attr( $visibility ) ? 'checked' : ''; ?>
				value="<?php echo esc_attr( 'exclude' ); ?>"/>
			<label for="rconvert_announcement_visibility_exclude">
			<?php
				esc_html_e(
					'Todas as páginas exceto',
					'rock-convert'
				);
				?>
				<br/>
				<small style="padding-left: 24px;">
				<?php
				esc_html_e(
					'O banner será exibido em todas as páginas, com exceção das páginas cadastradas abaixo.',
					'rock-convert'
				);
				?>
				</small>
			</label>
		</div>
		<div class="rock-convert-exclude-control"
			style="<?php echo 'exclude' === esc_attr( $visibility ) ? 'display: block' : 'display: none'; ?>">
			<div style="padding-top: 20px; clear: both;"
				class="rock-convert-exclude-pages">
				<?php if ( ! empty( $excluded_urls ) ) { ?>
					<?php foreach ( $excluded_urls as $url ) { ?>
						<div style="display: flex; margin-top: 5px;"
							class="rock-convert-exclude-pages-link">
							<input type="text"
							name="rconvert_announcement_excluded_pages[]"
							style="width: 95%;margin-right: 10px;"
							value="<?php echo esc_url( $url ); ?>"
							placeholder="<?php esc_html_e( 'Exemplo', 'rock-convert' ); ?>:
							<?php echo esc_url( get_bloginfo( 'url' ) ); ?>/meu-post">
							<input type="button"
							class="preview button rock-convert-exclude-pages-remove"
							value="x">
						</div>
					<?php } ?>
				<?php } else { ?>
					<div style="display: flex;"
						class="rock-convert-exclude-pages-link">
						<input type="text" name="rconvert_announcement_excluded_pages[]"
						style="width: 95%;margin-right: 10px;"
						placeholder="<?php esc_html_e( 'Exemplo', 'rock-convert' ); ?>: <?php echo esc_url( get_bloginfo( 'url' ) ); ?>/meu-post" >
						<input type="button"
						class="preview button rock-convert-exclude-pages-remove"
						value="x">
					</div>
				<?php } ?>
			</div>
			<input type="button"
				class="preview button rock-convert-exclude-pages-add"
				style="float: left;margin-top: 10px;"
				value="+ Adicionar página">
			<div class="clear"></div>
			<br>
			<small><?php esc_html_e( 'Exemplo', 'rock-convert' ); ?>:
			<?php echo esc_url( get_bloginfo( 'url' ) . '/minha-pagina/' ); ?></small>
		</div>
		<div class="clearfix" style="display: block;clear: both;"></div>
		<p class="submit">
			<input type="submit" class="button-primary"
			value="<?php esc_html_e( 'Salvar todas as configurações', 'rock-convert' ); ?>">
		</p>
	</div>

</div>






