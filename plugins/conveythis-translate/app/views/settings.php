<div class="wrap">
	<div id="conveythis-trial-expired-message" style="display: none;border: #ce1717 2px solid;color: #000;padding-left: 10px;background: #fff;">
		<p>
			<?php
				$message = __( 'ConveyThis error: Your 7 day trial period is over. You can %supgrade your plan%s to keep your site translated.', 'conveythis-translate' );
				echo sprintf( esc_html($message), '<a target="_blank" href="https://app.conveythis.com/dashboard/pricing/?utm_source=widget&utm_medium=wordpress">', '</a>' );
			?>			 
		</p>
	</div>
	<form class="conveythis-widget-option-form" style="max-width: 60%; min-width: 1024px; position: relative;" method="post" action="options.php">

		<?php settings_fields( 'my-plugin-settings-group' ); ?>
		<?php do_settings_sections( 'my-plugin-settings-group' ); ?>

		<h3><?php echo __( 'Main configuration', 'conveythis-translate' ); ?></h3>

		<div id="customize-preview" style="display: none;">
			<div style="font-weight: 700; padding: 1em 1em; font-size: 1.3em;">
				<?php echo __( 'Preview', 'conveythis-translate' ); ?>
			</div>
			<div dir="ltr" style="margin: 0; padding: 1em 1em; height: 45px; border: none; border-top: 1px solid rgba(34,36,38,.1);">
				<div id="customize-view-button" style="z-index: 1;"></div>
			</div>
		</div>

		
		<!-- -->

		<h3><?php echo __( 'API Key', 'conveythis-translate' ); ?></h3>
		<div style="margin: 10px 0 28px 0; width: 550px;">
			<div style="float: left; width: 190px;">
				<?php
					$message = __( 'Log in to %sConveyThis%s to get your API key.', 'conveythis-translate' );
					echo sprintf( esc_html($message), '<a target="_blank" href="https://app.conveythis.com/account/register/?utm_source=widget&utm_medium=wordpress">', '</a>' );
				?>
			</div>
			<div style="margin-left: 220px;">

				<input type="text" class="conveythis-input-text" id="conveythis_api_key" name="api_key" value="<?php echo esc_attr( $this->variables->api_key ); ?>" placeholder="pub_XXXXXXXX" />

			</div>
		</div>
		<div style="clear:both"></div>

		<!-- -->

		<h3><?php echo __( 'Source Language', 'conveythis-translate' ); ?></h3>
		<div style="margin: 10px 0 28px 0; width: 550px;">
			<div style="float: left; width: 190px;">
				<p style="padding: 0; margin: 0;">
					<?php echo __( 'What is the source (current) language of your website?', 'conveythis-translate' ); ?>
				</p>
			</div>
			<div style="margin-left: 220px;">

					<!-- Semantic -->
					<div class="ui fluid search selection dropdown">
						<input type="hidden" name="source_language" value="<?php echo esc_html($this->variables->source_language); ?>">
						<i class="dropdown icon"></i>
						<div class="default text"><?php echo __( 'Select source language', 'conveythis-translate' ); ?></div>
						<div class="menu">

							<?php foreach( $this->variables->languages as $language ): ?>

								<div class="item" data-value="<?php echo esc_attr( $language['code2'] ); ?>">
									<?php esc_html_e( $language['title_en'], 'conveythis-translate' ); ?>
								</div>

							<?php endforeach; ?>

						</div>
					</div>
			
			</div>
		</div>
		<div style="clear:both"></div>

		<!-- -->

		<h3><?php echo __( 'Target Languages', 'conveythis-translate' ); ?></h3>
		<div style="margin: 10px 0 28px 0; width: 550px;">
			<div style="float: left; width: 190px;">
				<p style="padding: 0; margin: 0;">
					<?php echo __( 'Choose languages you want to translate into.', 'conveythis-translate' ); ?>
				</p>
			</div>
			<div style="margin-left: 220px;">

					<!-- Semantic -->
					<div class="ui fluid multiple search selection dropdown dropdown-target-languages">
						<input type="hidden" name="target_languages" value="<?php echo implode( ',', $this->variables->target_languages ); ?>">
						<i class="dropdown icon"></i>
						<div class="default text">French, German, Italian, Portuguese …</div>
						<div class="menu">

						<?php foreach( $this->variables->languages as $language ): ?>

							<div class="item" data-value="<?php echo esc_attr( $language['code2'] ); ?>">
								<?php esc_html_e( $language['title_en'], 'conveythis-translate' ); ?>
							</div>

						<?php endforeach; ?>

						</div>
					</div> 
			</div>
		</div>
		<div style="margin: 20px 0; width: 565px;">
			<div style="float: left; width: 170px;"></div>
			<div style="margin-left: 220px;">
				<?php
					$message = __( 'On the free plan, you can only choose one target language. If you want to use more than 1 language, please %supgrade your plan%s.', 'conveythis-translate' );
					echo sprintf( esc_html($message), '<a target="_blank" href="https://app.conveythis.com/dashboard/pricing/?utm_source=widget&utm_medium=wordpress">', '</a>' );
				?>
			</div>
		</div>
		<div style="clear:both"></div>

		<!-- -->

		<br>
		<br>

		<div style="border-bottom: 1px solid rgba(34,36,38,.15); padding-bottom: 10px;">
			<a href="#" id="customize-tab-toogle">
				<p style="font-size: 1.2em;">
					<span class="dashicons  dashicons-admin-generic"></span>
					<?php echo __( 'Show more options', 'conveythis-translate' ); ?>
					<span class="dashicons dashicons-arrow-down"></span>
				</p>
			</a>
		</div>

		<div id="customize-tab" style="display: none">
            <h3><?php echo __( 'Default Source Language', 'conveythis-translate' ); ?></h3>
            <span class="badge badge-pill badge-danger">New</span>
            <div style="margin: 10px 0 28px 0; width: 550px;">
                <div style="float: left; width: 190px;">
                    <p style="padding: 0; margin: 0;">
                        <?php echo __( 'What is the default target language of your website?', 'conveythis-translate' ); ?>
                    </p>
                </div>
                <div style="margin-left: 220px;">

                    <!-- Semantic -->
                    <div class="ui fluid search selection dropdown">
                        <input type="hidden" name="default_language" value="<?php echo esc_html($this->variables->default_language); ?>">
                        <i class="dropdown icon"></i>
                        <div class="default text"><?php echo __( 'Select default language', 'conveythis-translate' ); ?></div>
                        <div class="menu" id="default_language_list">
                            <div class="item" data-value="">No value</div>
                            <?php foreach( $this->variables->languages as $language ): ?>
                                <?php if (in_array($language['code2'], $this->variables->target_languages)) {?>
                                    <div class="item" data-value="<?php echo esc_attr( $language['code2'] ); ?>">
                                        <?php esc_html_e( $language['title_en'], 'conveythis-translate' ); ?>
                                    </div>
                                <?php } ?>

                            <?php endforeach; ?>

                        </div>
                    </div>

                </div>
            </div>
            <div style="clear:both"></div>

            <h3><?php echo __( 'Target Language Names', 'conveythis-translate' ); ?></h3>

            <div style="margin: 10px 0 28px 0; width: 550px;">
                <table class="table" style="width: 100%; text-align: left;">
                    <tbody id="target_languages_translations">
                    </tbody>
                </table>
                <input type="hidden" name="target_languages_translations" value='<?php echo json_encode( $this->variables->target_languages_translations ); ?>'>
            </div>

            <?php if( !empty( $this->variables->api_key ) ): ?>

                <h3><?php echo __( 'Where are my translations?', 'conveythis-translate' ); ?></h3>

                <div style="margin: 10px 0 28px 0; width: 550px;">
                    <div style="float: left; width: 190px;">
                        <p style="padding: 0; margin: 0;">
                            <?php echo __( 'You can find all your translations in your account', 'conveythis-translate' ); ?>
                        </p>
                    </div>
                    <div style="margin-left: 220px;">

                        <a target="_blank" href="https://app.conveythis.com/domains/?utm_source=widget&utm_medium=wordpress"><?php echo __( 'Edit My Translations', 'conveythis-translate' ); ?></a>

                    </div>
                </div>

            <?php endif; ?>

            <!-- -->

			<h3><?php echo __( 'General', 'conveythis-translate' ); ?></h3>
			<div style="margin: 10px 0 45px 0; width: 550px;">
				<div style="float: left; width: 220px;">
					<p style="padding: 0; margin: 0;">
						<?php echo __( "Redirect visitors to translated pages automatically based on user browser's settings.", 'conveythis-translate' ); ?>
					</p>
				</div>
				<div style="margin-left: 300px;">
				
					<br>
					<?php if(!empty($this->variables->auto_translate) && $this->variables->auto_translate == 1): ?>
						<div dir="ltr">
							<input type="radio" id="auto_translate_yes" name="auto_translate" value="1" checked style="margin-left: 20px;">Yes
							<input type="radio" id="auto_translate_no"  name="auto_translate" value="0" style="margin-left: 20px;">No
						</div>
					<?php else: ?>
						<div dir="ltr">
							<input type="radio" id="auto_translate_yes" name="auto_translate" value="1" style="margin-left: 20px;">Yes
							<input type="radio" id="auto_translate_no"  name="auto_translate" value="0" checked style="margin-left: 20px;">No
						</div>
					<?php endif; ?>
				</div>
			</div>
			<div style="clear:both"></div>

			<div style="margin: 20px 0; width: 565px;">
				<div style="float: left; width: 170px;"></div>
				<div style="margin-left: 220px;">
					<?php
						$message = __( 'This feature is not available on Free and Starter plans. If you want to use this feature, please %supgrade your plan%s.', 'conveythis-translate' );
						echo sprintf( esc_html($message), '<a target="_blank" href="https://app.conveythis.com/dashboard/pricing/?utm_source=widget&utm_medium=wordpress">', '</a>' );
					?>
				</div>
			</div>
			<div style="clear:both"></div>
			
			<div style="margin: 10px 0 30px 0; width: 550px;">
				<div style="float: left; width: 220px;">
					<p style="padding: 0; margin: 0;">
						<?php echo __( "Hide ConveyThis logo.", 'conveythis-translate' ); ?>
					</p>
				</div>
				<div style="margin-left: 300px;">
				
					<br>
					<?php if(!empty($this->variables->hide_conveythis_logo) && $this->variables->hide_conveythis_logo == 1): ?>
						<div dir="ltr">
							<input type="radio" id="hide_conveythis_logo_yes" name="hide_conveythis_logo" value="1" checked style="margin-left: 20px;"> Yes
							<input type="radio" id="hide_conveythis_logo_no"  name="hide_conveythis_logo" value="0" style="margin-left: 20px;"> No
						</div>
					<?php else: ?>
						<div dir="ltr">
							<input type="radio" id="hide_conveythis_logo_yes" name="hide_conveythis_logo" value="1" style="margin-left: 20px;"> Yes
							<input type="radio" id="hide_conveythis_logo_no"  name="hide_conveythis_logo" value="0" checked style="margin-left: 20px;"> No
						</div>
					<?php endif; ?>
				</div>
			</div>
			<div style="clear:both"></div>
			
			<div style="margin: 20px 0; width: 565px;">
				<div style="float: left; width: 170px;"></div>
				<div style="margin-left: 220px;">
					<?php
						$message = __( 'This feature is not available on Free plan. If you want to use this feature, please %supgrade your plan%s.', 'conveythis-translate' );
						echo sprintf( esc_html($message), '<a target="_blank" href="https://app.conveythis.com/dashboard/pricing/?utm_source=widget&utm_medium=wordpress">', '</a>' );
					?>
				</div>
			</div>
			<div style="clear:both"></div>
			
			
			<div style="margin: 10px 0 60px 0; width: 550px;">
				<div style="float: left; width: 220px;">
					<p style="padding: 0; margin: 0;">
						<?php echo __( "Translate Media (adopt images for specific language)", 'conveythis-translate' ); ?>
					</p>
				</div>
				<div style="margin-left: 300px;">
				
					<br>
					<?php if(!empty($this->variables->translate_media) && $this->variables->translate_media == 1): ?>
						<div dir="ltr">
							<input type="radio" id="translate_media_yes" name="translate_media" value="1" checked style="margin-left: 20px;"> Yes
							<input type="radio" id="translate_media_no"  name="translate_media" value="0" style="margin-left: 20px;"> No
						</div>
					<?php else: ?>
						<div dir="ltr">
							<input type="radio" id="translate_media_yes" name="translate_media" value="1" style="margin-left: 20px;"> Yes
							<input type="radio" id="translate_media_no"  name="translate_media" value="0" checked style="margin-left: 20px;"> No
						</div>
					<?php endif; ?>
				</div>
			</div>
			<div style="clear:both"></div>

			<div style="margin: 10px 0 60px 0; width: 550px;"> 
				<div style="float: left; width: 220px;">
					<p style="padding: 0; margin: 0;">
						<?php echo __( "Translate PDF (adopt PDF files for specific language)", 'conveythis-translate' ); ?>
					</p>
				</div>
				<div style="margin-left: 300px;">
				
					<br>
					<?php if(!empty($this->variables->translate_document) && $this->variables->translate_document == 1): ?>
						<div dir="ltr">
							<input type="radio" id="translate_document_yes" name="translate_document" value="1" checked style="margin-left: 20px;"> Yes
							<input type="radio" id="translate_document_no"  name="translate_document" value="0" style="margin-left: 20px;"> No
						</div>
					<?php else: ?>
						<div dir="ltr">
							<input type="radio" id="translate_document_yes" name="translate_document" value="1" style="margin-left: 20px;"> Yes
							<input type="radio" id="translate_document_no"  name="translate_document" value="0" checked style="margin-left: 20px;"> No
						</div>
					<?php endif; ?>
				</div>
			</div>
            <div style="margin: 10px 0 60px 0; width: 550px;">
                <div style="float: left; width: 220px;">
                    <p style="padding: 0; margin: 0;">
                        <?php echo __( "Translate Links", 'conveythis-translate' ); ?>
                    </p>
                </div>
                <div style="margin-left: 300px;">

                    <br>
                    <?php if(!empty($this->variables->translate_links) && $this->variables->translate_links == 1): ?>
                        <div dir="ltr">
                            <input type="radio" id="translate_links_yes" name="translate_links" value="1" checked style="margin-left: 20px;"> Yes
                            <input type="radio" id="translate_links_no"  name="translate_links" value="0" style="margin-left: 20px;"> No
                        </div>
                    <?php else: ?>
                        <div dir="ltr">
                            <input type="radio" id="translate_links_yes" name="translate_links" value="1" style="margin-left: 20px;"> Yes
                            <input type="radio" id="translate_links_no"  name="translate_links" value="0" checked style="margin-left: 20px;"> No
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
			<div style="clear:both"></div>
			
			<div style="margin: 10px 0 60px 0; width: 550px;"> 
				<div style="float: left; width: 220px;">
					<p style="padding: 0; margin: 0;">
						<?php echo __( "Allow to change text direction from left to right and vice versa.", 'conveythis-translate' ); ?>
					</p>
				</div>
				<div style="margin-left: 300px;">
				
					<br>
					<?php if(!empty($this->variables->change_direction) && $this->variables->change_direction == 1): ?>
						<div dir="ltr">
							<input type="radio" id="change_direction_yes" name="change_direction" value="1" checked style="margin-left: 20px;"> Yes
							<input type="radio" id="change_direction_no"  name="change_direction" value="0" style="margin-left: 20px;"> No
						</div>
					<?php else: ?>
						<div dir="ltr">
							<input type="radio" id="change_direction_yes" name="change_direction" value="1" style="margin-left: 20px;"> Yes
							<input type="radio" id="change_direction_no"  name="change_direction" value="0" checked style="margin-left: 20px;"> No
						</div>
					<?php endif; ?>
				</div>
			</div>
			<div style="clear:both"></div>
			
			<h3><?php echo __( 'SEO', 'conveythis-translate' ); ?></h3>

			<!-- -->

			<div style="margin: 10px 0 28px 0; width: 800px;">
				<div style="float: left; width: 190px;">
					<?php echo __( 'Hreflang tags', 'conveythis-translate' ); ?>
				</div>
				<div style="margin-left: 220px;">

					<input type="checkbox" name="alternate" value="1" <?php checked( 1, $this->variables->alternate, true ); ?>>
					<label><?php echo __( 'Add to all pages', 'conveythis-translate' ); ?></label>

				</div>
			</div>
			<div style="clear:both"></div>

			<!-- -->

			<h3><?php echo __( 'Customize Languages', 'conveythis-translate' ); ?></h3>

			<!-- -->

			<div style="margin: 10px 0 28px 0; width: 800px;">
				<div style="float: left; width: 190px;">
					<?php echo __( 'Languages in selectbox', 'conveythis-translate' ); ?>
				</div>
				<div style="margin-left: 220px;">

					<input type="checkbox" name="show_javascript" value="1" <?php checked( 1, $this->variables->show_javascript, true ); ?>>
					<label><?php echo __( 'Show', 'conveythis-translate' ); ?></label>

				</div>
			</div>
			<div style="clear:both"></div>

			<!-- -->

			<div style="margin: 10px 0 28px 0; width: 800px;">
				<div style="float: left; width: 190px;">
					<?php echo __( 'Languages in menu', 'conveythis-translate' ); ?>
				</div>
				<div style="margin-left: 220px;">

						<?php echo __( 'You can place the button in a menu area. Go to', 'conveythis-translate' ); ?>
						<a href="<?php echo admin_url( 'nav-menus.php' ); ?>"><?php echo __( 'Appearance &gt; Menus', 'conveythis-translate' ); ?></a>
						<?php echo __( 'and drag and drop the ConveyThis Translate Custom link where you want.', 'conveythis-translate' ); ?>

				</div>
			</div>
			
			<div style="margin: 10px 0 28px 0; width: 800px;">
				<div style="float: left; width: 190px;">
					<?php echo __( 'Languages in widget', 'conveythis-translate' ); ?>
				</div>
				<div style="margin-left: 220px;">

						<?php echo __( 'You can place the button in a widget area. Go to', 'conveythis-translate' ); ?>
						<a href="<?php echo admin_url( 'widgets.php' ); ?>"><?php echo __( 'Appearance &gt; Widgets', 'conveythis-translate' ); ?></a>
						<?php echo __( 'and drag and drop the ConveyThis Translate Widget where you want.', 'conveythis-translate' ); ?>

				</div>
			</div>
			
			<div style="margin: 10px 0 28px 0; width: 800px;">
				<div style="float: left; width: 190px;">
					<?php echo __( 'Languages with a shortcode', 'conveythis-translate' ); ?>
				</div>
				<div style="margin-left: 220px;">
						<?php echo __( 'You can use the ConveyThis shortcode [conveythis_switcher] wherever you want to place the button.', 'conveythis-translate' ); ?>					
				</div>
			</div>
			<div style="clear:both"></div>

			<!-- -->
			<h3><?php echo __( 'Style', 'conveythis-translate' ); ?></h3>
			<h4><?php echo __( 'Picture', 'conveythis-translate' ); ?></h4>

			<div style="margin: 10px 0 28px 0; width: 550px;">
				<div style="float: left; width: 190px;">
					<p style="padding: 0; margin: 0;">
						<?php echo __( 'Select the display style for flags', 'conveythis-translate' ); ?>
					</p>
				</div>
				<div style="margin-left: 220px;">

							<div class="grouped fields">
								<div class="field">
									<div class="ui radio checkbox">
										<?php if( !empty( $this->variables->style_flag ) && $this->variables->style_flag == 'rect' ): ?>

											<input type="radio" name="style_flag" value="rect" checked="checked">

										<?php else: ?>

											<input type="radio" name="style_flag" value="rect">

										<?php endif; ?>
										<label><?php echo __( 'Rectangle flag', 'conveythis-translate' ); ?></label>
									</div>
								</div>
								<div class="field">
									<div class="ui radio checkbox">
										<?php if( !empty( $this->variables->style_flag ) && $this->variables->style_flag == 'sqr' ): ?>

											<input type="radio" name="style_flag" value="sqr" checked="checked">

										<?php else: ?>

											<input type="radio" name="style_flag" value="sqr">

										<?php endif; ?>
										<label><?php echo __( 'Square flag', 'conveythis-translate' ); ?></label>
									</div>
								</div>
								<div class="field">
									<div class="ui radio checkbox">
										<?php if( !empty( $this->variables->style_flag ) && $this->variables->style_flag == 'cir' ): ?>

											<input type="radio" name="style_flag" value="cir" checked="checked">

										<?php else: ?>

											<input type="radio" name="style_flag" value="cir">

										<?php endif; ?>
										<label><?php echo __( 'Circle flag', 'conveythis-translate' ); ?></label>
									</div>
								</div>
								<div class="field">
									<div class="ui radio checkbox">
										<?php if( !empty( $this->variables->style_flag ) && $this->variables->style_flag == 'without-flag' ): ?>

											<input type="radio" name="style_flag" value="without-flag" checked="checked">

										<?php else: ?>

											<input type="radio" name="style_flag" value="without-flag">

										<?php endif; ?>
										<label><?php echo __( 'Without flag', 'conveythis-translate' ); ?></label>
									</div>
								</div>
							</div>
				
				</div>
			</div>
			<div style="clear:both"></div>
		
			<!-- -->

			<h4><?php echo __( 'Text', 'conveythis-translate' ); ?></h4>

			<div style="margin: 10px 0 28px 0; width: 550px;">
				<div style="float: left; width: 190px;">
					<p style="padding: 0; margin: 0;">
						<?php echo __( 'Display the text name of the language', 'conveythis-translate' ); ?>
					</p>
				</div>
				<div style="margin-left: 220px;">

							<div class="grouped fields">
								<div class="field">
									<div class="ui radio checkbox">
										<?php if( !empty( $this->style_text ) && $this->style_text == 'full-text' ): ?>

											<input type="radio" name="style_text" value="full-text" checked="checked">

										<?php else: ?>

											<input type="radio" name="style_text" value="full-text">

										<?php endif; ?>
										<label><?php echo __( 'Full text', 'conveythis-translate' ); ?></label>
									</div>
								</div>
								<div class="field">
									<div class="ui radio checkbox">
										<?php if( !empty( $this->style_text ) && $this->style_text == 'short-text' ): ?>

											<input type="radio" name="style_text" value="short-text" checked="checked">

										<?php else: ?>

											<input type="radio" name="style_text" value="short-text">

										<?php endif; ?>
										<label><?php echo __( 'Short text', 'conveythis-translate' ); ?></label>
									</div>
								</div>
								<div class="field">
									<div class="ui radio checkbox">
										<?php if( !empty( $this->style_text ) && $this->style_text == 'without-text' ): ?>

											<input type="radio" name="style_text" value="without-text" checked="checked">

										<?php else: ?>

											<input type="radio" name="style_text" value="without-text">

										<?php endif; ?>
										<label><?php echo __( 'Without text', 'conveythis-translate' ); ?></label>
									</div>
								</div>
							</div>
				
				</div>
			</div>
			<div style="clear:both"></div>
		
			<!-- -->
			
			<h4><?php echo __( 'Color', 'conveythis-translate' ); ?></h4>

			<div style="margin: 10px 0 28px 0; width: 520px;">
				<div style="float: left; width: 190px;">
					<?php echo __( 'Background color of widget', 'conveythis-translate' ); ?>
				</div>
				<div style="margin-left: 220px;">
					<input type="color" class="form-control form-control-color" id="style_background_color" name="style_background_color" value="<?php echo esc_attr($this->style_background_color) ?>" data-default="#ffffff">
					<button class="btn btn-outline-secondary btn-default-color" type="button"><?php echo __( 'Set default', 'conveythis-translate' ); ?></button>
				</div>
			</div>
			
			<div style="margin: 10px 0 28px 0; width: 520px;">
				<div style="float: left; width: 190px;">
					<?php echo __( 'Background color of widget on hover', 'conveythis-translate' ); ?>
				</div>
				<div style="margin-left: 220px;">
					<input type="color" class="form-control form-control-color" id="style_hover_color" name="style_hover_color" value="<?php echo esc_attr($this->style_hover_color) ?>" data-default="#f6f6f6">
					<button class="btn btn-outline-secondary btn-default-color" type="button"><?php echo __( 'Set default', 'conveythis-translate' ); ?></button>
				</div>
			</div>
			
			<div style="margin: 10px 0 28px 0; width: 520px;">
				<div style="float: left; width: 190px;">
					<?php echo __( 'Border color of widget', 'conveythis-translate' ); ?>
				</div>
				<div style="margin-left: 220px;">
					<input type="color" class="form-control form-control-color" id="style_border_color" name="style_border_color" value="<?php echo esc_attr($this->style_border_color) ?>" data-default="#e0e0e0">
					<button class="btn btn-outline-secondary btn-default-color" type="button"><?php echo __( 'Set default', 'conveythis-translate' ); ?></button>
				</div>
			</div>
			
			<div style="margin: 10px 0 28px 0; width: 520px;">
				<div style="float: left; width: 190px;">
					<?php echo __( 'Text color of widget', 'conveythis-translate' ); ?>
				</div>
				<div style="margin-left: 220px;">
					<input type="color" class="form-control form-control-color" id="style_text_color" name="style_text_color" value="<?php echo esc_attr($this->style_text_color) ?>" data-default="#000000">
					<button class="btn btn-outline-secondary btn-default-color" type="button"><?php echo __( 'Set default', 'conveythis-translate' ); ?></button>
				</div>
			</div>
			
			<div style="clear:both"></div>
			
			<!-- -->

			<h4><?php echo __( 'Corner', 'conveythis-translate' ); ?></h4>
			
			<div style="margin: 10px 0 28px 0; width: 600px;">
				<div style="float: left; width: 190px;">
					<?php echo __( 'Corner type', 'conveythis-translate' ); ?>
				</div>
				<div style="margin-left: 220px;">

					<div>
						<input type="radio" name="style_corner_type" value="cir" <?php echo $this->style_corner_type == 'cir' ? 'checked="checked"' : '' ?> >
						<label><?php echo __( 'Circle', 'conveythis-translate' ); ?></label>
					</div>
					<div>
						<input type="radio" name="style_corner_type" value="rect" <?php echo $this->style_corner_type == 'rect' ? 'checked="checked"' : '' ?> >
						<label><?php echo __( 'Rectangle', 'conveythis-translate' ); ?></label>
					</div>

				</div>
			</div>
			
			<!-- -->

			<h4><?php echo __( 'Position', 'conveythis-translate' ); ?></h4>
			
			<div style="margin: 10px 0 28px 0; width: 600px;">
				<div style="float: left; width: 190px;">
					<?php echo __( 'Position type', 'conveythis-translate' ); ?>
				</div>
				<div style="margin-left: 220px;">

					<div>
						<input type="radio" name="style_position_type" value="fixed" <?php echo $this->style_position_type == 'fixed' ? 'checked="checked"' : '' ?> >
						<label><?php echo __( 'Fixed (fixed in certain position of screen)', 'conveythis-translate' ); ?></label>
					</div>
					<div>
						<input type="radio" name="style_position_type" value="custom" <?php echo $this->style_position_type == 'custom' ? 'checked="checked"' : '' ?> >
						<label><?php echo __( 'Custom (placed inside of chosen element)', 'conveythis-translate' ); ?></label>
					</div>

				</div>
			</div>
			
			<div id="position-fixed" <?php echo $this->style_position_type != 'fixed' ? 'style="display:none;"' : '' ?> >
				<div style="margin: 10px 0 28px 0; width: 550px;">
					<div style="float: left; width: 190px;">
						<p style="padding: 0; margin: 0;">
							<?php echo __( 'Vertical location of the language selection button on the site', 'conveythis-translate' ); ?>
						</p>
					</div>
					<div style="margin-left: 220px;">

						<div class="grouped fields">
							<div class="field">
								<div class="ui radio checkbox">
									<?php if( !empty( $this->style_position_vertical ) && $this->style_position_vertical == 'top' ): ?>

										<input type="radio" name="style_position_vertical" value="top" checked="checked">

									<?php else: ?>

										<input type="radio" name="style_position_vertical" value="top">

									<?php endif; ?>
									<label><?php echo __( 'Top', 'conveythis-translate' ); ?></label>
								</div>
							</div>
							<div class="field">
								<div class="ui radio checkbox">
									<?php if( !empty( $this->style_position_vertical ) && $this->style_position_vertical == 'bottom' ): ?>

										<input type="radio" name="style_position_vertical" value="bottom" checked="checked">

									<?php else: ?>

										<input type="radio" name="style_position_vertical" value="bottom">

									<?php endif; ?>
									<label><?php echo __( 'Bottom', 'conveythis-translate' ); ?></label>
								</div>
							</div>
						</div>
					
					</div>
				</div>
			
			
			
				<div style="margin: 10px 0 28px 0; width: 550px;">
					<div style="float: left; width: 190px;">
						<p style="padding: 0; margin: 0;">
							<?php echo __( 'Horizontal location of the language selection button on the site', 'conveythis-translate' ); ?>
						</p>
					</div>
					<div style="margin-left: 220px;">

						<div class="grouped fields">
							<div class="field">
								<div class="ui radio checkbox">
									<?php if( !empty( $this->style_position_horizontal ) && $this->style_position_horizontal == 'left' ): ?>

										<input type="radio" name="style_position_horizontal" value="left" checked="checked">

									<?php else: ?>

										<input type="radio" name="style_position_horizontal" value="left">

									<?php endif; ?>
									<label><?php echo __( 'Left', 'conveythis-translate' ); ?></label>
								</div>
							</div>
							<div class="field">
								<div class="ui radio checkbox">
									<?php if( !empty( $this->style_position_horizontal ) && $this->style_position_horizontal == 'right' ): ?>

										<input type="radio" name="style_position_horizontal" value="right" checked="checked">

									<?php else: ?>

										<input type="radio" name="style_position_horizontal" value="right">

									<?php endif; ?>
									<label><?php echo __( 'Right', 'conveythis-translate' ); ?></label>
								</div>
							</div>
						</div>
					
					</div>
				</div>
				<div style="clear:both"></div>

				<!-- -->

				<h4><?php echo __( 'Indenting', 'conveythis-translate' ); ?></h4>

				<div style="margin: 10px 0 28px 0; width: 800px;">
					<div style="float: left; width: 190px;">
						<?php echo __( 'Vertical spacing from the top or bottom of the browser', 'conveythis-translate' ); ?>
					</div>
					<div style="margin-left: 220px;">

						<div style="float: left;">
							<input type="hidden" name="style_indenting_vertical" value="<?php echo esc_attr($this->style_indenting_vertical); ?>">
							<span id="display-style-indenting-vertical"><?php echo esc_attr($this->style_indenting_vertical); ?></span>px
						</div>
						<!-- Semantic -->
						<div class="ui grey range" style="margin-left: 36px;" id="range-style-indenting-vertical"></div>

					</div>
					<div style="clear:both"></div>
				</div>
				
				<div style="margin: 10px 0 28px 0; width: 800px;">
					<div style="float: left; width: 190px;">
						<?php echo __( 'Horizontal spacing from the top or bottom of the browser', 'conveythis-translate' ); ?>
					</div>
					<div style="margin-left: 220px;">

						<div style="float: left;">
							<input type="hidden" name="style_indenting_horizontal" value="<?php echo esc_attr($this->style_indenting_horizontal); ?>">
							<span id="display-style-indenting-horizontal"><?php echo esc_attr($this->style_indenting_horizontal); ?></span>px
						</div>
						<!-- Semantic -->
						<div class="ui grey range" style="margin-left: 36px;" id="range-style-indenting-horizontal"></div>

					</div>
					<div style="clear:both"></div>
				</div>
				
			</div>
			<div style="clear:both"></div>
			
			
			<div id="position-custom" <?php echo $this->style_position_type == 'fixed' ? 'style="display:none;"' : '' ?> >
				<div style="margin: 10px 0 28px 0; width: 520px;">
					<div style="float: left; width: 190px;">
						<?php echo __( 'Enter id of element, where button will be placed', 'conveythis-translate' ); ?>						
					</div>
					<div style="margin-left: 220px;">

						<div>
							<input type="text" name="style_selector_id" class="form-control" value="<?php echo esc_html($this->style_selector_id) ?>" style="width: 100%;">
							<label><?php echo __( '* If id of element will not be found on the page, default position will be used', 'conveythis-translate' ); ?></label>
						</div>

					</div>
				</div>
				<div style="margin: 10px 0 28px 0; width: 520px;">
					<div style="float: left; width: 190px;">
						<?php echo __( 'Select dropdown menu direction', 'conveythis-translate' ); ?>	
					</div>
					<div style="margin-left: 220px;">
						<div>
							<input type="radio" name="style_position_vertical_custom" value="bottom" <?php echo $this->style_position_vertical_custom == 'bottom' ? 'checked="checked"' : '' ?> >
							<label><?php echo __( 'Up', 'conveythis-translate' ); ?></label>
						</div>
						<div>
							<input type="radio" name="style_position_vertical_custom" value="top" <?php echo $this->style_position_vertical_custom == 'top' ? 'checked="checked"' : '' ?> >
							<label><?php echo __( 'Down', 'conveythis-translate' ); ?></label>
						</div>
					</div>
				</div>
				
			</div>
			<!-- -->


			<h4>Url</h4>
			<div style="margin: 10px 0 10px 0; width: 600px;">
				<div style="float: left; width: 190px;">
					<?php echo __( 'Url Structure', 'conveythis-translate' ); ?>
				</div>
				<div style="margin-left: 220px;">

					<div>
						<input type="radio" name="url_structure" value="regular" <?php echo (!empty($this->url_structure) && $this->url_structure == "regular") ? "checked" : "";?>>
						<label><?php echo __( 'Sub-directory (e.g. https://example.com/es/)', 'conveythis-translate' ); ?></label>
					</div>
					<div>
						<input type="radio" name="url_structure" value="subdomain" <?php echo (!empty($this->url_structure) && $this->url_structure == "subdomain") ? "checked" : "";?>>
						<label><?php echo __( 'Sub-domain (e.g. https://es.example.com) (Beta)', 'conveythis-translate' ); ?></label>
					</div>
					
				</div>
			</div>
			<div id="dns-plan-error" style="display:none; width: 800px;">
				<div style="float: left; width: 170px;"></div>
				<div style="margin-left: 220px;">
					<?php
						$message = __( 'This feature is available on Pro and Pro+ plans.<br> If you want to use this feature, please %supgrade your plan%s.', 'conveythis-translate' );
						echo sprintf( esc_html($message), '<a target="_blank" href="https://app.conveythis.com/dashboard/pricing/?utm_source=widget&utm_medium=wordpress">', '</a>' );
					?>
				</div>
			</div>
			<div id="dns-setup" <?php echo $this->url_structure == 'subdomain' ? 'max-width:750px;"' : 'style="display:none; max-width:750px;"' ?>>
				<div class="card">
					<div class="card-body">
						<p><?php echo __( 'Please add CNAME record for each language you wish to use in your DNS manager.', 'conveythis-translate' ); ?></p>
						<p>
							<?php
								$message = __( 'For more information, please check: %sHow to add CNAME records in DNS manager%s.', 'conveythis-translate' );
								echo sprintf( esc_html($message), '<a target="_blank" href="https://www.conveythis.com/help/how-to-add-cname-records-in-dns-manager/?utm_source=widget&utm_medium=wordpress" target="_blank">', '</a>' );
							?>
						</p>

						<table class="table" style="width: 100%; text-align: left;">
							<thead>
								<tr>
									<th scope="col">Language</th>
									<th scope="col">Name</th>
									<th scope="col">CNAME</th>
								</tr>
							</thead>
							<tbody id="dns-setup-records">							
							</tbody>
						</table>
					</div>
				</div>
			</div>
						
						
			<h4><?php echo __( 'Change flag', 'conveythis-translate' ); ?></h4>

			<!-- -->

			<div style="margin: 10px 0 28px 0; width: 800px;">
				<p style="padding: 0; margin: 0;">
					<?php echo __( 'By default all the languages have their flags in accordance with ISO standards. If you want to change the flag for one or several languages here you can customize this.', 'conveythis-translate' ); ?>
				</p>

				<?php $i = 0; ?>
				<?php while( $i < 5 ): ?>

				<div style="margin: 28px 0; width: 520px;">
					<div style="float: left; width: 250px;">

						<!-- Semantic -->
						<div class="ui fluid search selection dropdown">
							<input type="hidden" name="style_change_language[]" value="<?php echo (!empty($this->style_change_language[$i])) ? esc_attr($this->style_change_language[$i]): "" ; ?>">
							<i class="dropdown icon"></i>
							<div class="default text"><?php echo __( 'Select language', 'conveythis-translate' ); ?></div>
							<div class="menu">

								<?php foreach( $this->languages as $id => $language ): ?>

									<div class="item" data-value="<?php echo esc_attr($id); ?>">
										<?php esc_html_e( $language['title_en'], 'conveythis-translate' ); ?>
									</div>

								<?php endforeach; ?>

							</div>
						</div>

					</div>
					<div style="float: left; width: 250px; margin-left: 20px;">

						<!-- Semantic -->
						<div class="ui fluid search selection dropdown">
							<input type="hidden" name="style_change_flag[]" value="<?php echo (!empty($this->style_change_flag[$i])) ? esc_attr($this->style_change_flag[$i]) : ""; ?>">
							<i class="dropdown icon"></i>
							<div class="default text"><?php echo __( 'Select Flag', 'conveythis-translate' ); ?></div>
							<div class="menu">

								<?php foreach( $this->flags as $flag ): ?>

									<div class="item" data-value="<?php echo esc_attr( $flag['code'] ); ?>">
										<div class="ui image" style="height: 28px; width: 30px; background-position: 50% 50%; background-size: contain; background-repeat: no-repeat; background-image: url('//cdn.conveythis.com/images/flags/v3/rectangular/<?php echo esc_attr($flag['code']); ?>.png')"></div>
										<?php esc_html_e( $flag['title'], 'conveythis-translate' ); ?>
									</div>

								<?php endforeach; ?>

							</div>
						</div>

					</div>
					<div style="margin-left: 540px;">
						<button class="conveythis-reset">X</button>
					</div>
				</div>

				<?php $i++; ?>
				<?php endwhile; ?>

			</div>

			<!-- -->

			<br>
			<h3><?php echo __( 'Block pages', 'conveythis-translate' ); ?></h3>
			
			<!-- -->

			<div style="margin: 10px 0 28px 0; width: 800px;">
				<p>
					<?php echo __( 'Add URL that you want to exclude from translations.', 'conveythis-translate' ); ?>
				</p>

				<div id="blockpages_wrapper">
					<?php foreach( $this->blockpages as $blockpage ): ?>
						<div class="blockpage">
							<input type="url" name="blockpages[]" class="form-control" placeholder="https://example.com" value="<?php echo esc_url($blockpage); ?>" style="width: 515px;">
							<button class="conveythis-delete-page">X</button>
						</div>
					<?php endforeach; ?>
					<div class="blockpage">
						<input type="url" name="blockpages[]" class="form-control" placeholder="https://example.com" style="width: 515px;">
						<button class="conveythis-delete-page">X</button>
					</div>
				</div>
				<button id="add_blockpage" class="button button-primary">Add</button>
			</div>

			<!-- -->

            <div style="margin: 10px 0 28px 0; width: 800px;">
                <p>
                    <?php echo __( 'Add URL that you want to exclude from translations.', 'conveythis-translate' ); ?>
                </p>

                <div id="exclusion_wrapper">
                    <?php foreach( $this->exclusions as $exclusion ): ?>
                        <div class="exclusion">
                            <select class="form-control rule" style="width: 90px;" required="">
                                <?php foreach (array('start', 'end', 'contain', 'equal') as $rule) {?>
                                <option value="<?php echo $rule; ?>"<?php echo ($exclusion['rule'] == $rule?' selected':'')?>><?php echo ucfirst($rule); ?></option>
                                <?php } ?>
                            </select>
                            <input type="text" class="form-control page_url" placeholder="https://example.com" value="<?php echo $exclusion['page_url']; ?>" style="width: 515px;">
                            <input type="hidden" class="exclusion_id" value="<?php echo $exclusion['id']; ?>"/>
                            <button class="conveythis-delete-page">X</button>
                        </div>
                    <?php endforeach; ?>
                    <div class="exclusion">
                        <select class="form-control rule" style="width: 90px;" required="">
                            <option value="start">Start</option>
                            <option value="end">End</option>
                            <option value="contain">Contain</option>
                            <option value="equal">Equal</option>
                        </select>
                        <input type="text" class="form-control page_url" placeholder="https://example.com" style="width: 515px;">
                        <button class="conveythis-delete-page">X</button>
                    </div>
                </div>
                <input type="hidden" name="exclusions" value='<?php echo json_encode( $this->exclusions ); ?>'>
                <button id="add_exlusion" class="button button-primary">Add</button>
            </div>

            <!-- -->

            <div style="margin: 10px 0 28px 0; width: 800px;">
                <p>
                    <?php echo __( 'Glossary rules', 'conveythis-translate' ); ?>
                </p>

                <div id="glossary_wrapper">
                    <?php $languages = array_combine(array_column($this->languages, 'code2'), array_column($this->languages, 'title_en')); ?>
                    <?php foreach( $this->glossary as $glossary ): ?>
                    <div class="glossary">
                        <input type="text" class="form-control source_text" value="<?php echo $glossary['source_text']; ?>" placeholder="Enter Word" style="width: 215px;">
                        <select class="form-control rule" style="width: 130px;" required="">
                            <?php foreach (array('prevent' => "Don't translate", 'replace' => 'Translate as') as $ruleId => $rule) {?>
                                <option value="<?php echo $ruleId; ?>"<?php echo ($glossary['rule'] == $ruleId?' selected':'')?>><?php echo ucfirst($rule); ?></option>
                            <?php } ?>
                        </select>
                        <input type="text" class="form-control translate_text" value="<?php echo $glossary['translate_text']; ?>"<?php echo $glossary['rule'] == 'prevent'?' disabled="disabled"':'';?> style="width: 215px;">
                        <select class="form-control target_language" style="width: 130px;">
                            <option value="">All languages</option>
                            <?php foreach ($this->languages as $language) {?>
                                <?php if (in_array($language['code2'], $this->target_languages)) {?>
                                <option value="<?php echo $language['code2']; ?>"<?php echo ($glossary['target_language'] == $language['code2']?' selected':'')?>><?php echo $languages[$language['code2']]; ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                        <input type="hidden" class="glossary_id" value="<?php echo $glossary['glossary_id']; ?>"/>
                        <button class="conveythis-delete-page">X</button>
                    </div>
                    <?php endforeach; ?>
                    <div class="glossary">
                        <input type="text" class="form-control source_text" placeholder="Enter Word" style="width: 215px;">
                        <select class="form-control rule" style="width: 130px;" required="">
                            <option value="prevent">Don't translate</option>
                            <option value="replace">Translate as</option>
                        </select>
                        <input type="text" class="form-control translate_text" value="" disabled="disabled" style="width: 215px;">
                        <select class="form-control target_language" style="width: 130px;">
                            <option value="">All languages</option>
                            <?php foreach ($this->target_languages as $languageCode) {?>
                                <option value="<?php echo $languageCode; ?>"><?php echo $languages[$languageCode]; ?></option>
                            <?php } ?>
                        </select>
                        <button class="conveythis-delete-page">X</button>
                    </div>
                </div>

                <input type="hidden" name="glossary" value='<?php echo json_encode( $this->glossary ); ?>'>
                <button id="add_glossary" class="button button-primary">Add</button>
            </div>

            <div style="margin: 10px 0 28px 0; width: 800px;">
                <p>
                <?php echo __( 'Exclusion div Ids', 'conveythis-translate' ); ?>
                </p>

                <div id="exclusion_block_wrapper">
                    <?php foreach( $this->exclusion_blocks as $exclusion_block ) {?>
                        <div class="exclusion_block">
                            <input disabled="disabled" type="text" class="form-control id_value" value="<?php echo $exclusion_block['id_value']; ?>" placeholder="Enter Word" style="width:215px;">
                            <input type="hidden" class="exclusion_block_id" value="<?php echo $exclusion_block['id']; ?>"/>
                            <button class="conveythis-delete-page">X</button>
                        </div>
                    <?php } ?>
                </div>

                <input type="hidden" name="exclusion_blocks" value='<?php echo json_encode( $this->exclusion_blocks ); ?>'>
                <button id="add_exlusion_block" class="button button-primary">Add</button>

            </div>

		<?php submit_button(); ?>
	</form>

	<p>
		<a href="https://wordpress.org/support/plugin/conveythis-translate/reviews/#postform" target="_blank"><?php echo __( 'Love ConveyThis? Give us 5 stars on WordPress.org', 'conveythis-translate' ); ?></a>
	</p>
	<p>
	<?php
		$message = __( 'If you need any help, you can contact us via our live chat at %swww.ConveyThis.com%s or email us at support@conveythis.com. You can also check our %sFAQ%s', 'conveythis-translate' );
		echo sprintf( esc_html($message), '<a href="https://www.conveythis.com/?utm_source=widget&utm_medium=wordpress" target="_blank">', '</a>', '<a href="https://www.conveythis.com/faqs/?utm_source=widget&utm_medium=wordpress" target="_blank">', '</a>' );
	?>
	</p>
</div>

<?php

	wp_enqueue_style('conveythis-dropdown', plugins_url('../widget/css/dropdown.min.css',__FILE__) );
	wp_enqueue_style('conveythis-transition', plugins_url('../widget/css/transition.min.css',__FILE__) );
	wp_enqueue_style('conveythis-range', plugins_url('../widget/css/range.css?version=' . CONVEYTHIS_PLUGIN_VERSION,__FILE__) );
	wp_enqueue_style('conveythis-settings', plugins_url('../widget/css/settings.css?version=' . CONVEYTHIS_PLUGIN_VERSION,__FILE__) );
	
?>
<?php

	wp_enqueue_script('conveythis-dropdown', plugins_url('../widget/js/dropdown.min.js',__FILE__), array('jquery'), null, true);
	wp_enqueue_script('conveythis-transition', plugins_url('../widget/js/transition.min.js',__FILE__), array('jquery'), null, true);
	wp_enqueue_script('conveythis-range', plugins_url('../widget/js/range.js?version=' . CONVEYTHIS_PLUGIN_VERSION,__FILE__), array('jquery'), null, true);
	wp_enqueue_script('conveythis-plugin', CONVEYTHIS_JAVASCRIPT_PLUGIN_URL."/conveythis-preview.js");
	wp_enqueue_script('conveythis-settings', plugins_url('../widget/js/settings.js?version=' . CONVEYTHIS_PLUGIN_VERSION,__FILE__), array('jquery'), null, true);

?>
