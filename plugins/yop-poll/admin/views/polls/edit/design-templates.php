<div class="row">
	<div class="col-md-12">
		&nbsp;
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<!--  -->
		<div class="yop-poll-poll-templates" id="ypContainer" style="margin-top: 25px;">
			<?php
			$i = 0;
			foreach ( $templates as $template ) {
				if ( 0 === $i % 2 ) {
					?>
					<div class="row">
					<?php
				}
				if ( $template->base === $poll->template_base ) {
					$template_selected = ' selected';
				} else {
					$template_selected = '';
				}
				?>
				<div class="col-xs-6 col-sm-6">
					<h4 class="template-name text-center">
						<?php
							echo esc_html( $template->name );
						?>
					</h4>
					<figure class="yp-figure imgContainer text-center<?php echo esc_attr( $template_selected ); ?>">
						<div class="selected-overlay">
							<i class="glyphicon glyphicon-ok"></i>
						</div>
						<?php
							echo wp_kses(
								$template->html_preview,
								$allowed_tags
							);
						?>
						<figcaption class="yp-figcaption">
							<input name="publish" data-template-id="<?php echo esc_attr( $template->id ); ?>" 
								data-template-base="<?php echo esc_attr( $template->base ); ?>"
								class="button button-primary button-large center choose-template"
								value="<?php esc_html_e( 'Use and customize', 'yop-poll' ); ?>" type="button" />
						</figcaption>
					</figure>
				</div>
				<?php
				$i++;
				if ( 0 === $i % 2 ) {
					?>
					</div>
					<?php
				}
			}
			?>
		</div>
	</div>
	<input type="hidden" name="poll[template]" value="<?php echo esc_attr( $poll->template ); ?>" data-template-base="<?php echo esc_attr( $poll->template_base ); ?>">
	<input type="hidden" name="poll[skin]" value="" data-skin-base="<?php echo esc_attr( $poll->skin_base ); ?>">
</div>
<div class="row">
	<div class="col-md-12">
		&nbsp;
	</div>
</div>
