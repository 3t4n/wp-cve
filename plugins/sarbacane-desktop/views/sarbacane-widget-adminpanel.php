<?php if ( defined( 'ABSPATH' ) ) { ?>
<script type="text/javascript">
	var sarbacaneFieldNumber = <?php echo count( $fields ) ?>;
</script>
<div id="sarbacane_desktop_content">
	<p class="sarbacane_desktop_logo"></p>
	<!-- ========================================================================================== -->
	<!-- =====================================WIDGET SETUP========================================= -->
	<!-- ========================================================================================== -->
	<div id="sarbacane_desktop_widget">
		<form method="POST" action="" id="sarbacane_desktop_widget_form" autocomplete="off">
			<p class="sarbacane_desktop_configuration_title">
				<?php _e( 'Widget settings', 'sarbacane-desktop' ) ?>
			</p>

			<p class="sarbacane_desktop_div_splitter"></p>
			<!-- ================================WIDGET FIELDS================================= -->
			<label class="sarbacane_desktop_configuration_label" for="sarbacane_desktop_widget_title">
				<?php _e( 'Title', 'sarbacane-desktop' ) ?> :
			</label>
			<input type="text"
				   name="sarbacane_widget_title"
				   id="sarbacane_desktop_widget_title"
				   onkeyup="sarbacaneDisplayPreview()"
				   class="sarbacane_desktop_configuration_input sarbacane_desktop_configuration_input_large"
				   value="<?php esc_html_e( ( $title ) ) ?>"/>

			<p class="sarbacane_desktop_div_splitter"></p>

			<label class="sarbacane_desktop_configuration_label" for="sarbacane_desktop_widget_description">
				<?php _e( 'Description', 'sarbacane-desktop' ) ?> :
			</label>
			<textarea name="sarbacane_widget_description"
					  id="sarbacane_desktop_widget_description"
					  class="sarbacane_desktop_configuration_input sarbacane_desktop_configuration_input_large sarbacane_desktop_huge_field"
					  onkeyup="sarbacaneDisplayPreview()"><?php esc_html_e( ( $description ) ) ?></textarea>

			<p class="sarbacane_desktop_div_splitter"></p>

			<!-- ================================CUSTOM FIELDS================================= -->
			<?php
			$i = 0;
			foreach ( $fields as $field ) {
				if ( !isset ( $field->placeholder ) ) {
					$field->placeholder = '';
				}
				if ( strtolower( $field->label ) == 'email' ) {
					$isEmail = true;
					$label = esc_attr__( 'Email', 'sarbacane-desktop' );
				}
				else {
					$isEmail = false;
					$label = esc_html( $field->label );
				}
			?>
			<div id="sarbacane_desktop_widget_field_<?php echo $i ?>">
				<div class="sarbacane_desktop_widget_summary">
					<label class="sarbacane_desktop_field_number">
						<?php echo __( 'Field', 'sarbacane-desktop' ) . ' ' . ( $i + 1 ) ?>
					</label>
					<ul class="sarbacane_widget_menu">
						<li<?php if ( !$isEmail ) { ?> onclick="sarbacaneDeleteField( <?php echo $i ?> )" <?php } ?>
							class="sarbacane_desktop_menu_item sarbacane_desktop_trash<?php if ( $isEmail ) { ?> sarbacane_desktop_menu_item_disabled_email<?php } ?>">
						</li>
						<li onclick="sarbacaneMoveDown( <?php echo $i ?> )"
							class="sarbacane_desktop_menu_item sarbacane_desktop_down">
						</li>
						<li onclick="sarbacaneMoveUp( <?php echo $i ?> )"
							class="sarbacane_desktop_menu_item sarbacane_desktop_up">
						</li>
					</ul>
				</div>
				<p class="sarbacane_desktop_widget_configuration">
					<label class="sarbacane_desktop_inline_configuration_label"
						   for="sarbacane_desktop_label_<?php echo $i ?>">
						<?php _e( 'Name', 'sarbacane-desktop' ) ?> :
					</label>
					<?php if ( $isEmail ) { ?>
					<input type="hidden" name="sarbacane_label_<?php echo $i ?>" value="email"/>
					<input type="text"
						   value="<?php echo $label ?>"
						   id="sarbacane_desktop_label_<?php echo $i ?>"
						   class="sarbacane_desktop_configuration_input"
						   readonly="readonly"/>
					<br/>
					<?php } else { ?>
					<input type="text"
						   name="sarbacane_label_<?php echo $i ?>"
						   id="sarbacane_desktop_label_<?php echo $i ?>"
						   value="<?php echo $label ?>"
						   class="sarbacane_desktop_configuration_input"
						   onkeyup="sarbacaneDisplayPreview()"/>
					<br/>
					<?php } ?>
					<label class="sarbacane_desktop_inline_configuration_label"
						   for="sarbacane_desktop_field_<?php echo $i ?>">
						<?php _e( 'Placeholder', 'sarbacane-desktop' ) ?> :
					</label>
					<input type="text"
						   name="sarbacane_field_<?php echo $i ?>"
						   id="sarbacane_desktop_field_<?php echo $i ?>"
						   class="sarbacane_desktop_configuration_input"
						   value="<?php esc_html_e( $field->placeholder ) ?>"
						   onkeyup="sarbacaneDisplayPreview()"/>
					<br/>
					<label class="sarbacane_desktop_inline_configuration_label"
						   for="sarbacane_desktop_mandatory_<?php echo $i ?>">
						<?php _e( 'Mandatory', 'sarbacane-desktop' ) ?> :
					</label>
					<input<?php if ( $field->mandatory ) { ?> checked="checked"<?php } ?><?php if ( $isEmail ) { ?> disabled="disabled"<?php } ?>
						   type="radio"
						   name="sarbacane_mandatory_<?php echo $i ?>"
						   id="sarbacane_desktop_mandatory_true_<?php echo $i ?>"
						   value="true"
						   onclick="sarbacaneDisplayPreview()"/>
					<label class="sarbacane_desktop_yes_no_label"
						   for="sarbacane_desktop_mandatory_true_<?php echo $i ?>">
						<?php _e( 'Yes', 'sarbacane-desktop' ) ?>
					</label>
					<input<?php if ( !$field->mandatory ) { ?> checked="checked"<?php } ?><?php if ( $isEmail ) { ?> disabled="disabled"<?php } ?>
						   type="radio"
						   name="sarbacane_mandatory_<?php echo $i ?>"
						   id="sarbacane_desktop_mandatory_false_<?php echo $i ?>"
						   value="false"
						   onclick="sarbacaneDisplayPreview()"/>
					<label class="sarbacane_desktop_yes_no_label"
						   for="sarbacane_desktop_mandatory_false_<?php echo $i ?>">
						<?php _e( 'No', 'sarbacane-desktop' ) ?>
					</label>
				</p>
				<p class="sarbacane_desktop_div_splitter"></p>
			</div>
			<?php
				$i++;
			}
			?>
			<div id="sarbacane_desktop_additional_fields"></div>

			<p>
				<input type="button"
					   class="sarbacane_desktop_configuration_button"
					   value="<?php esc_attr_e( 'Add field', 'sarbacane-desktop' ) ?>"
					   onclick="sarbacaneAddField()"
					   id="sarbacane_desktop_add_field"/>
			</p>

			<p class="sarbacane_desktop_div_splitter"></p>

 			<!-- =============================COMPLEMENT FIELDS============================== -->
			<input type="hidden" name="sarbacane_widget_list_type" id="sarbacane_desktop_widget_list_type"/>

			<label class="sarbacane_desktop_configuration_label"
				   for="sarbacane_desktop_widget_registration_button">
				<?php _e( 'Button name', 'sarbacane-desktop' ) ?> :
			</label>
			<label class="sarbacane_desktop_inline_configuration_label"
				   for="sarbacane_desktop_widget_registration_button">
				<?php _e( 'Name', 'sarbacane-desktop' ) ?> :
			</label>
			<input type="text"
				   name="sarbacane_widget_registration_button"
				   id="sarbacane_desktop_widget_registration_button"
				   class="sarbacane_desktop_configuration_input required"
				   required="required"
				   value="<?php esc_attr_e( $registration_button ) ?>"
				   onkeyup="sarbacaneDisplayPreview()"/>

			<p class="sarbacane_desktop_div_splitter"></p>

			<label class="sarbacane_desktop_configuration_label"
				   for="sarbacane_desktop_widget_registration_mandatory_fields">
				<?php _e( 'Mandatory fields message', 'sarbacane-desktop' ) ?> :
			</label>
			<label class="sarbacane_desktop_inline_configuration_label"
				   for="sarbacane_desktop_widget_registration_mandatory_fields">
				<?php _e( 'Name', 'sarbacane-desktop' ) ?> :
			</label>
			<input type="text"
				   name="sarbacane_widget_registration_mandatory_fields"
				   id="sarbacane_desktop_widget_registration_mandatory_fields"
				   class="sarbacane_desktop_configuration_input required"
				   required="required"
				   value="<?php esc_attr_e( $registration_mandatory_fields ) ?>"
				   onkeyup="sarbacaneDisplayPreview()"/>

			<p class="sarbacane_desktop_div_splitter"></p>

			<label class="sarbacane_desktop_configuration_label">
				<?php _e( 'Legal notice', 'sarbacane-desktop' ) ?> :
			</label>

			<label class="sarbacane_desktop_inline_configuration_label"
				   for="sarbacane_desktop_widget_registration_legal_notices_mentions">
				<?php _e( 'Mentions', 'sarbacane-desktop' ) ?> :
			</label>
			<input type="text"
				   name="sarbacane_widget_registration_legal_notices_mentions"
				   id="sarbacane_desktop_widget_registration_legal_notices_mentions"
				   class="sarbacane_desktop_configuration_input"
				   required="required"
				   value="<?php esc_attr_e( $registration_legal_notices_mentions ) ?>"
				   onkeyup="sarbacaneDisplayPreview()"/>

			<label class="sarbacane_desktop_inline_configuration_label"
				   for="sarbacane_desktop_widget_registration_legal_notices_url">
				<?php _e( 'Url', 'sarbacane-desktop' ) ?> :
			</label>
			<input type="url"
				   name="sarbacane_widget_registration_legal_notices_url"
				   id="sarbacane_desktop_widget_registration_legal_notices_url"
				   class="sarbacane_desktop_configuration_input"
				   required="required"
				   value="<?php esc_attr_e( $registration_legal_notices_url ) ?>"
				   onkeyup="sarbacaneDisplayPreview()"/>

			<p class="sarbacane_desktop_div_splitter"></p>

			<label class="sarbacane_desktop_configuration_label"
				   for="sarbacane_desktop_widget_registration_message">
				<?php _e( 'Successful form submission message', 'sarbacane-desktop' ) ?> :
			</label>
			<textarea name="sarbacane_widget_registration_message"
					  id="sarbacane_desktop_widget_registration_message"
					  class="sarbacane_desktop_configuration_input sarbacane_desktop_configuration_input_large sarbacane_desktop_huge_field required"
					  required="required"
					  onkeyup="sarbacaneDisplayPreview()"><?php esc_html_e( $registration_message ) ?></textarea>

			<p class="sarbacane_desktop_div_splitter"></p>
			<input type="hidden" name="sarbacane_field_number" id="sarbacane_desktop_field_number" value=""/>
			<input type="hidden" name="sarbacane_save_configuration" id="sarbacane_desktop_save_configuration" value="true"/>
			<?php wp_nonce_field( 'sarbacane_save_configuration', 'sarbacane_token' ) ?>
		</form>
		<div id="sarbacane_desktop_configuration_footer">
			<input type="button"
				   class="sarbacane_desktop_configuration_button sarbacane_desktop_configuration_button_green"
				   value="<?php esc_attr_e( 'Save', 'sarbacane-desktop' ) ?>"
				   onclick="sarbacaneSubmitForm()"/>
		</div>
	</div>
	<!-- ========================================================================================== -->
	<!-- ===================================WIDGET PREVIEW========================================= -->
	<!-- ========================================================================================== -->
	<div id="sarbacane_widget_preview">
		<p class="sarbacane_widget_configuration_title"><?php _e( 'Preview', 'sarbacane-desktop' ) ?></p>
		<div id="sarbacane_preview"></div>
	</div>
	<div id="sarbacane_desktop_vertical_splitter"></div>
	<!-- ========================================================================================== -->
	<!-- =================================WIDGET INFORMATIONS====================================== -->
	<!-- ========================================================================================== -->
	<div id="sarbacane_desktop_widget_info">
		<div class="sarbacane_desktop_help_title">
			<?php _e( 'Information', 'sarbacane-desktop' ) ?>
		</div>
		<p>
			<?php _e( 'This tool allows you to create a form widget that you can add to WordPress', 'sarbacane-desktop' ) ?>
		</p>
		<p>
			<?php _e( 'The widget will sync subscribers with your Sarbacane contact list', 'sarbacane-desktop' ) ?>
		</p>
		<p>
			<?php _e( 'All data from the widget will be available in your list', 'sarbacane-desktop' ) ?>
		</p>
		<p>
			<?php _e( 'Any changes in the structure of the form will cause a refresh of the associated list in Sarbacane.', 'sarbacane-desktop' ) ?>
		</p>
		<p class="sarbacane_desktop_div_splitter"></p>
		<div class="sarbacane_desktop_help_title">
			<?php _e( 'Need help?', 'sarbacane-desktop' ) ?>
		</div>
		<p>
			<?php _e( 'Email', 'sarbacane-desktop' ) ?> : <?php _e( 'support@sarbacane.com', 'sarbacane-desktop' ) ?>
			<br/>
			<?php _e( 'Phone', 'sarbacane-desktop' ) ?> : <?php _e( '+33(0) 328 328 040', 'sarbacane-desktop' ) ?>
		</p>
		<p>
			<?php _e( 'For more informations, please take a look to our website', 'sarbacane-desktop' ) ?> :
			<br/>
			<a href="<?php _e( 'http://sarbacane.com/?utm_source=module-wordpress&utm_medium=plugin&utm_content=lien-sarbacane&utm_campaign=wordpress', 'sarbacane-desktop' ) ?>">
				<?php _e( 'http://www.sarbacane.com', 'sarbacane-desktop' ) ?>
			</a>
		</p>
	</div>
	<!-- ========================================================================================== -->
	<!-- =====================================FIELDS PATTERN======================================= -->
	<!-- ========================================================================================== -->
	<div style="display:none" id="fieldPattern">
		<div id="sarbacane_desktop_widget_field_FIELDNUMBER">
			<div class="sarbacane_desktop_widget_summary">
				<label class="sarbacane_desktop_field_number">
					<?php _e( 'Field', 'sarbacane-desktop' ) ?> PLUSONEFIELD
				</label>
				<ul class="sarbacane_widget_menu">
					<li onclick="sarbacaneDeleteField(FIELDNUMBER)"
						class="sarbacane_desktop_menu_item sarbacane_desktop_trash">
					</li>
					<li onclick="sarbacaneMoveDown(FIELDNUMBER)"
						class="sarbacane_desktop_menu_item sarbacane_desktop_down">
					</li>
					<li onclick="sarbacaneMoveUp(FIELDNUMBER)"
						class="sarbacane_desktop_menu_item sarbacane_desktop_up">
					</li>
				</ul>
			</div>
			<p class="sarbacane_desktop_widget_configuration">
				<label class="sarbacane_desktop_inline_configuration_label"
					   for="sarbacane_desktop_label_FIELDNUMBER">
					<?php _e( 'Name', 'sarbacane-desktop' ) ?> :
				</label>
				<input type="text"
					   name="sarbacane_label_FIELDNUMBER"
					   id="sarbacane_desktop_label_FIELDNUMBER"
					   value=""
					   class="sarbacane_desktop_configuration_input"
					   onkeyup="sarbacaneDisplayPreview()"/>
				<br/>
				<label class="sarbacane_desktop_inline_configuration_label"
					   for="sarbacane_desktop_field_FIELDNUMBER">
					<?php _e( 'Placeholder', 'sarbacane-desktop' ) ?> :
				</label>
				<input type="text"
					   name="sarbacane_field_FIELDNUMBER"
					   id="sarbacane_desktop_field_FIELDNUMBER"
					   class="sarbacane_desktop_configuration_input"
					   value=""
					   onkeyup="sarbacaneDisplayPreview()"/>
				<br/>
				<label class="sarbacane_desktop_inline_configuration_label"
					   for="sarbacane_desktop_mandatory_FIELDNUMBER">
					<?php _e( 'Mandatory', 'sarbacane-desktop' ) ?> :
				</label>
				<input type="radio"
					   name="sarbacane_mandatory_FIELDNUMBER"
					   id="sarbacane_desktop_mandatory_true_FIELDNUMBER"
					   value="true"
					   onclick="sarbacaneDisplayPreview()"/>
				<label class="sarbacane_desktop_yes_no_label"
					   for="sarbacane_desktop_mandatory_true_FIELDNUMBER">
					<?php _e( 'Yes', 'sarbacane-desktop' ) ?>
				</label>
				<input type="radio"
					   checked="checked"
					   name="sarbacane_mandatory_FIELDNUMBER"
					   id="sarbacane_desktop_mandatory_false_FIELDNUMBER"
					   value="false"
					   onclick="sarbacaneDisplayPreview()"/>
				<label class="sarbacane_desktop_yes_no_label"
					   for="sarbacane_desktop_mandatory_false_FIELDNUMBER">
					<?php _e( 'No', 'sarbacane-desktop' ) ?>
				</label>
			</p>
			<p class="sarbacane_desktop_div_splitter"></p>
		</div>
	</div>
	<!-- ========================================================================================== -->
	<!-- ============================================DIALOGS======================================= -->
	<!-- ========================================================================================== -->
</div>
<?php } ?>
