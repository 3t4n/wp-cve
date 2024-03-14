<?php
/**
 * Plugin's WPCF7 contact form properties.
 *
 * @package add-on-cf7-for-notion
 */

namespace WPC_WPCF7_NTN\CFP;

use WPC_WPCF7_NTN\WPCF7_Notion_Service;
use WPC_WPCF7_NTN\Helpers;
use WPC_WPCF7_NTN\WPCF7_Field_Mapper;
use function WPC_WPCF7_NTN\Helpers\tooltip;

defined( 'ABSPATH' ) || exit;

/**
 * Registers the wpc_notion contact form property.
 *
 * @param array $properties A list of WPCF7 properties.
 * @see 'wpcf7_pre_construct_contact_form_properties' filter hook
 */
function register_property( $properties ): array {
	$service = WPCF7_Notion_Service::get_instance();

	if ( $service->is_active() ) {
		$properties += array(
			'wpc_notion' => array(),
		);
	}

	return $properties;
}


/**
 * Saves the wpc_notion property value.
 *
 * @param WPCF7_ContactForm $contact_form A WPCF7_ContactForm instance.
 * @see 'wpcf7_save_contact_form' action hook
 */
function save_contact_form( $contact_form ) {
	$service = WPCF7_Notion_Service::get_instance();

	if ( ! $service->is_active() ) {
		return;
	}

	// Nonce checked by WPCF7 {@see wpcf7_load_contact_form_admin} and sanitize functions apply on each prop.
	// phpcs:ignore WordPress.Security.NonceVerification.Missing
	$prop = isset( $_POST['wpc-wpcf7-notion'] )
		? (array) wp_unslash( $_POST['wpc-wpcf7-notion'] ) // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Missing
		: array();

	$prop                      = wp_parse_args(
		$prop,
		array(
			'enable_database'   => false,
			'database_selected' => '',
			'mapping'           => array(),
		)
	);
	$prop['enable_database']   = (bool) $prop['enable_database'];
	$prop['database_selected'] = sanitize_key( $prop['database_selected'] );
	if ( $prop['enable_database'] && $prop['database_selected'] ) {
		$columns         = Helpers\get_notion_databases_columns( $prop['database_selected'] );
		$columns_keys    = array_keys( $columns );
		$prop['mapping'] = array_filter(
			$prop['mapping'],
			function ( $column_id ) use ( $columns_keys ) {
				return in_array( $column_id, $columns_keys, true );
			}
		);
	}

	$contact_form->set_properties(
		array(
			'wpc_notion' => $prop,
		)
	);
}


/**
 * Builds the editor panel for the wpc_notion property.
 *
 * @param array $panels Contact Form 7 panels.
 * @see 'wpcf7_editor_panels' filter hook.
 */
function editor_panels( $panels ) {
	$service = WPCF7_Notion_Service::get_instance();

	if ( ! $service->is_active() ) {
		return $panels;
	}

	$contact_form = \WPCF7_ContactForm::get_current();

	$prop = wp_parse_args(
		$contact_form->prop( 'wpc_notion' ),
		array(
			'enable_database'   => false,
			'database_selected' => '',
			'mapping'           => array(),
		)
	);

	$editor_panel = function () use ( $prop, $service, $contact_form ) {
		$description = sprintf(
		/* translators: %s: html link */
			esc_html( __( 'You can set up the Notion integration here. For details, see %s.', 'add-on-cf7-for-notion' ) ),
			str_replace(
				'<a',
				'<a target="_blank"',
				wpcf7_link(
					__( 'https://wordpress.org/plugins/add-on-cf7-for-notion/#installation', 'add-on-cf7-for-notion' ),
					__( 'Notion integration', 'add-on-cf7-for-notion' )
				)
			)
		);

		$api       = wpconnect_wpcf7_notion_get_api();
		$databases = $api->get_databases();

		$database_selected = false;

		?>
		<script>
			jQuery(function ($) {
				var enableDatabaseCheck = $('#wpc-wpcf7-notion-enable-database-list');
				enableDatabaseCheck.change(function () {
					if ($(this).prop('checked')) {
						$(this).closest('tr').removeClass('inactive');
					} else {
						$(this).closest('tr').addClass('inactive');
					}
				});

				$('.js-wpc-wpcf7-notion-mapping-table').each(function () {
					var selects = $('.js-wpc-wpcf7-notion-mapping-select');
					var getSelectedNotionFieldIds = function () {
						return selects.map(function () {
							return $(this).val();
						}).toArray().filter(function (val) { return val !== ''; });
					};
					var updateAvailableFields = function () {
						selects.each(function () {
							var select = $(this);
							var selectedIds = getSelectedNotionFieldIds();
							$(this).find('option').each(function () {
								$(this).prop('disabled', $(this).val() !== select.val() && selectedIds.indexOf($(this).val()) > -1);
							})
						})
					};
					selects.change(updateAvailableFields);

					updateAvailableFields();

				});

			});
		</script>
		<div class="wpc-wpcf7-notion-wpco-icon">
			<a href="https://wpconnect.co/" target="_blank" rel="noopener noreferrer">
				<img src="<?php echo esc_url( WPCONNECT_WPCF7_NTN_URL . 'assets/img/logo-wpconnect.png' ); ?>" alt="icon wpconnect">
			</a>
		</div>
		<h2><?php echo esc_html( __( 'Notion', 'add-on-cf7-for-notion' ) ); ?></h2>

		<fieldset>
			<legend><?php echo wp_kses_post( $description ); ?></legend>

			<table class="form-table " role="presentation">
				<tbody>
				<tr class="<?php echo $prop['enable_database'] ? '' : 'inactive'; ?>">
					<th scope="row">
						<?php
						echo sprintf(
							esc_html( __( 'Databases', 'add-on-cf7-for-notion' ) )
						);
						tooltip( __( 'Select the Notion database where you want to save the answers.', 'add-on-cf7-for-notion' ) );
						?>
					</th>
					<td>
						<fieldset>
							<legend class="screen-reader-text">
								<?php

								echo esc_html( __( 'Databases lists', 'add-on-cf7-for-notion' ) );

								?>
							</legend>
							<label for="wpc-wpcf7-notion-enable-database-list">
								<input type="checkbox" name="wpc-wpcf7-notion[enable_database]" id="wpc-wpcf7-notion-enable-database-list" value="1" <?php checked( $prop['enable_database'] ); ?> />
								<?php

								echo esc_html(
									__( 'Add form submissions to your database', 'add-on-cf7-for-notion' )
								);

								?>
							</label>
						</fieldset>
					</td>
				</tr>
				<tr>
					<th scope="row"></th>
					<td>
						<fieldset>
							<?php

							if ( $databases ) {
								echo sprintf(
									'<legend>%1$s</legend>',
									esc_html( __( 'Select the database to which form submissions are added:', 'add-on-cf7-for-notion' ) )
								);

								echo '<ul>';

								foreach ( $databases as $database ) {
									$checked = false;
									if ( $database->id === $prop['database_selected'] ) {
										$database_selected = $database;
										$checked           = true;
									}
									echo sprintf(
										'<li><label><input %1$s /> %2$s</label></li>',
										// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										wpcf7_format_atts(
											array(
												'type'    => 'radio',
												'name'    => 'wpc-wpcf7-notion[database_selected]',
												'value'   => $database->id,
												'checked' => $checked
													? 'checked'
													: '',
											)
										),
										esc_html( $database->title[0]->plain_text )
									);
								}

								echo '</ul>';
							} else {
								echo sprintf(
									'<legend>%1$s </br> %2$s</legend>',
									esc_html( __( 'No Notion database is visible at the moment. Create one and refresh this page after 1 minute.', 'add-on-cf7-for-notion' ) ),
									sprintf(
										/* translators: %1$s: Notion add and manage integrations help page  */
										esc_html__( 'Make sure you have %1$s', 'add-on-cf7-for-notion' ),
										sprintf(
											'<a href="https://www.notion.so/help/add-and-manage-integrations-with-the-api#add-integrations-to-pages" target="_blank">%1$s</a>',
											esc_html__( 'added the integration to the Notion page', 'add-on-cf7-for-notion' )
										)
									)
								);
							}

							?>
						</fieldset>
					</td>
				</tr>
				</tbody>
			</table>
			<?php
			if ( $prop['enable_database'] && $database_selected ) {
				?>
				<h3><?php echo esc_html( __( 'Database fields', 'add-on-cf7-for-notion' ) ); ?></h3>
				<p>
					<?php echo esc_html( __( 'Select a Notion field name for each Contact Form 7 field to map them.', 'add-on-cf7-for-notion' ) ); ?> <br />
					<?php echo esc_html( __( 'You can only select compatible fields (e.g. a Contact Form 7 url field with a Notion url or text field).', 'add-on-cf7-for-notion' ) ); ?>
				</p>

				<table class="widefat fixed striped js-wpc-wpcf7-notion-mapping-table" style="max-width: 600px;">
					<thead>
					<tr>
						<th><?php echo esc_html( __( 'Contact Form 7 field', 'add-on-cf7-for-notion' ) ); ?></th>
						<th><?php echo esc_html( __( 'Notion Database column', 'add-on-cf7-for-notion' ) ); ?></th>
					</tr>
					</thead>
					<tbody>
					<?php
					$field_mapper = WPCF7_Field_Mapper::get_instance();

					$columns                = Helpers\get_notion_databases_columns( $database_selected->id );
					$supported_remote_types = $field_mapper->get_supported_notion_types();
					$available_columns_name = array_reduce(
						$columns,
						function ( $columns_name, $column ) use ( $supported_remote_types ) {
							if ( in_array( $column->type, $supported_remote_types, true ) ) {
								$columns_name[] = $column->name;
							}
							return $columns_name;
						},
						array()
					);

					$mapped_tags    = Helpers\get_mapped_tags_from_contact_form( $contact_form, $columns );
					$mapped_columns = array();
					// Make sure mapped tags are compatible.
					$cleaned_mapped_tags = $field_mapper->filter_mapped_tags( $mapped_tags );
					$wrongly_mapped_tags = array_diff_key( $mapped_tags, $cleaned_mapped_tags );
					$displayedFileWarning = false;

					foreach ( $contact_form->scan_form_tags() as $tag ) {
						if ( empty( $tag->name ) ) {
							continue;
						}
						if ($tag->basetype == 'file' && !$displayedFileWarning) {
							echo '<div class="file-field-warning">';
							echo '<p>' . esc_html__('Due to Notion API limitation your field file-upload won\'t be transfered as an image but as a link.', 'add-on-cf7-for-notion') . '</p>';
							echo '</div>';
							$displayedFileWarning = true;
						}
						?>
						<tr>
							<?php
							$tag_name             = $tag->name;
							$selected_column_id   = '';
							$selected_column_name = '';
							$mapped_class         = '';
							$mapped_text          = $tag_name;
							$mapped_error_desc    = '';
							if ( isset( $cleaned_mapped_tags[ $tag_name ] ) ) {
								$selected_column_name = $cleaned_mapped_tags[ $tag_name ]['notion_field_name'];
								$selected_column_id   = $cleaned_mapped_tags[ $tag_name ]['notion_field_id'];
								$mapped_class         = 'is-mapped';
								/* translators: %s: tag name */
								$mapped_text = sprintf( __( '%s: mapped', 'add-on-cf7-for-notion' ), $tag_name );
							} elseif ( isset( $wrongly_mapped_tags[ $tag_name ] ) ) {
								$selected_column_name = $wrongly_mapped_tags[ $tag_name ]['notion_field_name'];
								$selected_column_id   = $wrongly_mapped_tags[ $tag_name ]['notion_field_id'];
								$mapped_class         = 'is-error';
								/* translators: %s: tag name */
								$mapped_text = sprintf( __( '%s: error', 'add-on-cf7-for-notion' ), $tag_name );

								if ( empty( $selected_column_name ) || ! in_array( $selected_column_name, $available_columns_name, true ) ) {
									$mapped_error_desc = __( 'The mapped column does not exist or its type is not supported', 'add-on-cf7-for-notion' );
								} else {
									/* translators: %s: WPCF7 tag type */
									$mapped_error_desc = sprintf( __( 'The column is not supported by the tag type "%s"', 'add-on-cf7-for-notion' ), $wrongly_mapped_tags[ $tag_name ]['type'] );
								}
							}
							if ( ! empty( $selected_column_name ) ) {
								$mapped_columns[] = $selected_column_name;
							}

							?>
							<td class="<?php echo esc_attr( $mapped_class ); ?>">
								<?php
								echo esc_html( $tag_name );
								?>
								<div class="screen-reader-text"><?php echo esc_html( $mapped_text ); ?></div>
								<?php

								if ( ! empty( $mapped_error_desc ) ) {
									?>
									<div class="wpc-wpcf7-notion-error"><?php echo esc_html( $mapped_error_desc ); ?></div>
									<?php
								}
								?>
							</td>
							<td>
								<select class="js-wpc-wpcf7-notion-mapping-select wpc-wpcf7-notion-mapping-select" name="wpc-wpcf7-notion[mapping][<?php echo esc_attr( $tag_name ); ?>]"><option value=""><?php echo esc_html( __( 'None', 'add-on-cf7-for-notion' ) ); ?></option>
									<?php
									foreach ( $columns as $column_id => $column ) {
										if ( $field_mapper->check_field_compat( $tag->basetype, $column->type ) ) {
											$selected = $column_id === $selected_column_id ? ' selected="selected"' : '';
											?>
											<option value="<?php echo esc_attr( $column_id ); ?>"<?php echo esc_html( $selected ); ?>><?php echo esc_html( $column->name ); ?></option>
											<?php
										}
									}
									?>
								</select>
							</td>
						</tr>
						<?php
					}

					?>
					</tbody>
					<tr></tr>
				</table>

				<?php

				$unmapped_columns_name = array_diff( $available_columns_name, $mapped_columns );
				if ( count( $unmapped_columns_name ) > 0 ) {
					?>
					<p><strong>
							<?php
							echo esc_html( _n( 'The Notion\'s column below is not mapped yet:', 'The Notion\'s columns below are not mapped yet:', count( $unmapped_columns_name ), 'add-on-cf7-for-notion' ) );
							echo '<br />' . esc_html( implode( ', ', $unmapped_columns_name ) );
							?>
						</strong></p>
					<?php
				}
			}
			?>
		</fieldset>
		<?php
	};

	$panels += array(
		'wpc-notion-panel' => array(
			'title'    => __( 'Notion', 'add-on-cf7-for-notion' ),
			'callback' => $editor_panel,
		),
	);

	return $panels;
}
