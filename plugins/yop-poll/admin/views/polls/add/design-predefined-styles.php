<div class="row">
	<div class="col-md-12">
		&nbsp;
	</div>
</div>
<div class="row skins-no-template" style="margin-top: 30px;">
	<div class="col-md-12 text-center">
		<p>
			<h4>
				<?php
				esc_html_e( 'You need to select a template first to see the available skins for it', 'yop-poll' );
				?>
			</h4>
		</p>
		<p style="margin-top: 30px;">
			<h4>
				<?php
				esc_html_e( 'You can select a template <a href="#" class="skins-select-template">here</a>', 'yop-poll' );
				?>
			</h4>
		</p>
	</div>
</div>
<div class="row skins-basic hide">
	<?php
	foreach ( $skins as $skin ) {
		if ( 'basic' === $skin->template_base ) {
			$skin_meta_data = unserialize( $skin->meta_data );
			?>
			 <div class="col-xs-6 col-sm-3 col-md-2 col-width">
			 	<h4 class="text-center">
					<?php
					echo esc_html( $skin->name );
					?>
				</h4>
			 	<figure class="yp-figure">
				 	<div class="selected-overlay">
						<i class="glyphicon glyphicon-ok"></i>
					</div>
					<?php
					echo wp_kses(
						$skin->html_preview,
						$allowed_tags
					);
					?>
					<figcaption class="yp-figcaption">
						<button class="btn btn-primary choose-skin"
							 data-temp="basic"
							 data-skin-id="<?php echo esc_attr( $skin->id ); ?>"
							 data-skin-base="<?php echo esc_attr( $skin->base ); ?>"
							 data-poll-background-color="<?php echo esc_attr( $skin_meta_data['poll']['backgroundColor'] ); ?>"
							 data-poll-border-size="<?php echo esc_attr( $skin_meta_data['poll']['borderSize'] ); ?>"
							 data-poll-border-color="<?php echo esc_attr( $skin_meta_data['poll']['borderColor'] ); ?>"
							 data-poll-border-radius="<?php echo esc_attr( $skin_meta_data['poll']['borderRadius'] ); ?>"
							 data-poll-padding-left-right="<?php echo esc_attr( $skin_meta_data['poll']['paddingLeftRight'] ); ?>"
							 data-poll-padding-top-bottom="<?php echo esc_attr( $skin_meta_data['poll']['paddingTopBottom'] ); ?>"
							 data-questions-text-color="<?php echo esc_attr( $skin_meta_data['questions']['textColor'] ); ?>"
							 data-questions-text-size="<?php echo esc_attr( $skin_meta_data['questions']['textSize'] ); ?>"
							 data-questions-text-weight="<?php echo esc_attr( $skin_meta_data['questions']['textWeight'] ); ?>"
							 data-questions-text-align="<?php echo esc_attr( $skin_meta_data['questions']['textAlign'] ); ?>"
							 data-answers-padding-left-right="<?php echo esc_attr( $skin_meta_data['answers']['paddingLeftRight'] ); ?>"
							 data-answers-padding-top-bottom="<?php echo esc_attr( $skin_meta_data['answers']['paddingTopBottom'] ); ?>"
							 data-answers-text-color="<?php echo esc_attr( $skin_meta_data['answers']['textColor'] ); ?>"
							 data-answers-text-size="<?php echo esc_attr( $skin_meta_data['answers']['textSize'] ); ?>"
							 data-answers-text-weight="<?php echo esc_attr( $skin_meta_data['answers']['textWeight'] ); ?>"
							 data-answers-skin="<?php echo esc_attr( $skin_meta_data['answers']['skin'] ); ?>"
							 data-answers-padding-color-scheme="<?php echo esc_attr( $skin_meta_data['answers']['colorScheme'] ); ?>"
							 data-buttons-background-color="<?php echo esc_attr( $skin_meta_data['buttons']['backgroundColor'] ); ?>"
							 data-buttons-border-size="<?php echo esc_attr( $skin_meta_data['buttons']['borderSize'] ); ?>"
							 data-buttons-border-color="<?php echo esc_attr( $skin_meta_data['buttons']['borderColor'] ); ?>"
							 data-buttons-border-radius="<?php echo esc_attr( $skin_meta_data['buttons']['borderRadius'] ); ?>"
							 data-buttons-padding-left-right="<?php echo esc_attr( $skin_meta_data['buttons']['paddingLeftRight'] ); ?>"
							 data-buttons-padding-top-bottom="<?php echo esc_attr( $skin_meta_data['buttons']['paddingTopBottom'] ); ?>"
							 data-buttons-text-color="<?php echo esc_attr( $skin_meta_data['buttons']['textColor'] ); ?>"
							 data-buttons-text-size="<?php echo esc_attr( $skin_meta_data['buttons']['textSize'] ); ?>"
							 data-buttons-text-weight="<?php echo esc_attr( $skin_meta_data['buttons']['textWeight'] ); ?>"
							 data-errors-border-left-color-for-success="<?php echo esc_attr( $skin_meta_data['errors']['borderLeftColorForSuccess'] ); ?>"
							 data-errors-border-left-color-for-error="<?php echo esc_attr( $skin_meta_data['errors']['borderLeftColorForError'] ); ?>"
							 data-errors-border-left-size="<?php echo esc_attr( $skin_meta_data['errors']['borderLeftSize'] ); ?>"
							 data-errors-padding-top-bottom="<?php echo esc_attr( $skin_meta_data['errors']['paddingTopBottom'] ); ?>"
							 data-errors-border-text-color="<?php echo esc_attr( $skin_meta_data['errors']['textColor'] ); ?>"
							 data-errors-border-text-size="<?php echo esc_attr( $skin_meta_data['errors']['textSize'] ); ?>"
							 data-errors-border-text-weight="<?php echo esc_attr( $skin_meta_data['errors']['textWeight'] ); ?>"
							 data-custom-css="<?php echo esc_attr( $skin_meta_data['custom']['css'] ); ?>"
						>
							<?php esc_html_e( 'Use as is', 'yop-poll' ); ?>
						</button>
						<button class="btn btn-primary customize-skin"
							data-temp="basic"
							data-skin-id="<?php echo esc_attr( $skin->id ); ?>"
							data-skin-base="<?php echo esc_attr( $skin->base ); ?>"
						>
							<?php esc_html_e( 'Customize', 'yop-poll' ); ?>
						</button>
					</figcaption>
				</figure>
			 </div>
			<?php
		}
	}
	?>
</div>
<div class="row skins-basic-pretty hide">
	<?php
	foreach ( $skins as $skin ) {
		if ( 'basic-pretty' === $skin->template_base ) {
			$skin_meta_data = unserialize( $skin->meta_data );
			?>
			 <div class="col-xs-6 col-sm-3 col-md-2 col-width">
				<h4 class="text-center">
					<?php
					echo esc_html( $skin->name );
					?>
				</h4>
			 	<figure class="yp-figure">
				 	<div class="selected-overlay">
						<i class="glyphicon glyphicon-ok"></i>
					</div>
				 	<?php
					echo wp_kses(
						$skin->html_preview,
						$allowed_tags
					);
					 ?>
					<figcaption class="yp-figcaption">
						<button class="btn btn-primary choose-skin"
							 data-temp="basic-pretty"
							 data-skin-id="<?php echo esc_attr( $skin->id ); ?>"
							 data-skin-base="<?php echo esc_attr( $skin->base ); ?>"
							 data-poll-background-color="<?php echo esc_attr( $skin_meta_data['poll']['backgroundColor'] ); ?>"
							 data-poll-border-size="<?php echo esc_attr( $skin_meta_data['poll']['borderSize'] ); ?>"
							 data-poll-border-color="<?php echo esc_attr( $skin_meta_data['poll']['borderColor'] ); ?>"
							 data-poll-border-radius="<?php echo esc_attr( $skin_meta_data['poll']['borderRadius'] ); ?>"
							 data-poll-padding-left-right="<?php echo esc_attr( $skin_meta_data['poll']['paddingLeftRight'] ); ?>"
							 data-poll-padding-top-bottom="<?php echo esc_attr( $skin_meta_data['poll']['paddingTopBottom'] ); ?>"
							 data-questions-text-color="<?php echo esc_attr( $skin_meta_data['questions']['textColor'] ); ?>"
							 data-questions-text-size="<?php echo esc_attr( $skin_meta_data['questions']['textSize'] ); ?>"
							 data-questions-text-weight="<?php echo esc_attr( $skin_meta_data['questions']['textWeight'] ); ?>"
							 data-questions-text-align="<?php echo esc_attr( $skin_meta_data['questions']['textAlign'] ); ?>"
							 data-answers-padding-left-right="<?php echo esc_attr( $skin_meta_data['answers']['paddingLeftRight'] ); ?>"
							 data-answers-padding-top-bottom="<?php echo esc_attr( $skin_meta_data['answers']['paddingTopBottom'] ); ?>"
							 data-answers-text-color="<?php echo esc_attr( $skin_meta_data['answers']['textColor'] ); ?>"
							 data-answers-text-size="<?php echo esc_attr( $skin_meta_data['answers']['textSize'] ); ?>"
							 data-answers-text-weight="<?php echo esc_attr( $skin_meta_data['answers']['textWeight'] ); ?>"
							 data-answers-skin="<?php echo esc_attr( $skin_meta_data['answers']['skin'] ); ?>"
							 data-answers-padding-color-scheme="<?php echo esc_attr( $skin_meta_data['answers']['colorScheme'] ); ?>"
							 data-buttons-background-color="<?php echo esc_attr( $skin_meta_data['buttons']['backgroundColor'] ); ?>"
							 data-buttons-border-size="<?php echo esc_attr( $skin_meta_data['buttons']['borderSize'] ); ?>"
							 data-buttons-border-color="<?php echo esc_attr( $skin_meta_data['buttons']['borderColor'] ); ?>"
							 data-buttons-border-radius="<?php echo esc_attr( $skin_meta_data['buttons']['borderRadius'] ); ?>"
							 data-buttons-padding-left-right="<?php echo esc_attr( $skin_meta_data['buttons']['paddingLeftRight'] ); ?>"
							 data-buttons-padding-top-bottom="<?php echo esc_attr( $skin_meta_data['buttons']['paddingTopBottom'] ); ?>"
							 data-buttons-text-color="<?php echo esc_attr( $skin_meta_data['buttons']['textColor'] ); ?>"
							 data-buttons-text-size="<?php echo esc_attr( $skin_meta_data['buttons']['textSize'] ); ?>"
							 data-buttons-text-weight="<?php echo esc_attr( $skin_meta_data['buttons']['textWeight'] ); ?>"
							 data-errors-border-left-color-for-success="<?php echo esc_attr( $skin_meta_data['errors']['borderLeftColorForSuccess'] ); ?>"
							 data-errors-border-left-color-for-error="<?php echo esc_attr( $skin_meta_data['errors']['borderLeftColorForError'] ); ?>"
							 data-errors-border-left-size="<?php echo esc_attr( $skin_meta_data['errors']['borderLeftSize'] ); ?>"
							 data-errors-padding-top-bottom="<?php echo esc_attr( $skin_meta_data['errors']['paddingTopBottom'] ); ?>"
							 data-errors-border-text-color="<?php echo esc_attr( $skin_meta_data['errors']['textColor'] ); ?>"
							 data-errors-border-text-size="<?php echo esc_attr( $skin_meta_data['errors']['textSize'] ); ?>"
							 data-errors-border-text-weight="<?php echo esc_attr( $skin_meta_data['errors']['textWeight'] ); ?>"
							 data-custom-css="<?php echo esc_attr( $skin_meta_data['custom']['css'] ); ?>"
						>
							<?php esc_html_e( 'Use as is', 'yop-poll' ); ?>
						</button>
						<button class="btn btn-primary customize-skin" 
							data-temp="basic-pretty"
							data-skin-id="<?php echo esc_attr( $skin->id ); ?>"
							data-skin-base="<?php echo esc_attr( $skin->base ); ?>"
						>
							<?php esc_html_e( 'Customize', 'yop-poll' ); ?>
						</button>
					</figcaption>
				</figure>
			 </div>
			<?php
		}
	}
	?>
</div>
