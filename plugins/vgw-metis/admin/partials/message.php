<?php

namespace WP_VGWORT;

/**
 * Template for the create message view
 *
 * @package     vgw-metis
 * @copyright   Verwertungsgesellschaft Wort
 * @license     https://www.gnu.org/licenses/gpl-3.0.html
 * @author      Torben Gallob
 * @author      Michael Hillebrand
 *
 */
?>

<?php
    if(!empty($this->warning_message)) {
?>
<div class="notice notice-warning">
    <p><?php esc_html_e( $this->warning_message ); ?></p>
</div>
<?php } ?>

<div class="wrap message">
    <h1><?php esc_html_e( 'Meldung erstellen', 'vgw-metis' ); ?></h1>
	<?php esc_html_e( 'VG WORT METIS', 'vgw-metis' ); ?> <?php esc_html_e( $this->plugin::VERSION ); ?>
    <hr/>

    <h2><?php esc_html_e( 'Meldungsdetails', 'vgw-metis' ); ?></h2>
    <form method="post" id="create-message-form" action="admin-post.php">
        <input type="hidden" name="page" value="metis-message"/>
        <input type="hidden" name="post_id" value="<?php echo $this->post_id; ?>"/>
        <input type="hidden" name="action" value="wp_metis_save_message"/>
        <input type="hidden" name="post_id" value="<?php echo esc_html($this->post_id); ?>" />
		<?php wp_nonce_field( 'wp_metis_save_message', 'message-form-nonce' ); ?>
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="public_identification_id"><?php esc_html_e( 'Öffentlicher Identifikationscode', 'vgw-metis' ); ?></label>
                </th>
                <td>
                    <output name="public_identification_id" id="public_identification_id">
						<?php echo esc_html( $this->pixel->public_identification_id ); ?>
                    </output>
                    <input type="hidden" name="public_identification_id"
                           value="<?php echo esc_html( $this->pixel->public_identification_id ); ?>"/>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="private_identification_id"><?php esc_html_e( 'Privater Identifikationscode', 'vgw-metis' ); ?></label>
                </th>
                <td>
                    <output name="private_identification_id" id="private_identification_id">
						<?php echo esc_html( $this->pixel->private_identification_id ); ?>
                    </output>
                    <input type="hidden" name="private_identification_id"
                           value="<?php echo esc_html( $this->pixel->private_identification_id ); ?>"/>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="permalink"><?php esc_html_e( 'Permalink', 'vgw-metis' ); ?></label>
                </th>
                <td>
                    <output name="permalink" id="permalink"><a
                                href="<?php echo esc_url( get_permalink( $this->post_id ) ); ?>"
                                target="_blank"><?php echo esc_url( get_permalink( $this->post_id ) ); ?></a>
                    </output>
                    <input type="hidden" name="permalink"
                           value="<?php echo esc_url( get_permalink( $this->post_id ) ); ?>"/>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="add_url"><?php esc_html_e( 'Weitere URLs', 'vgw-metis' ); ?></label>
                </th>
                <td>
                    <ul class="urls" id="urls">
                    </ul>

                    <button id="add_url" type="button"
                            class="button button-secondary"><?php esc_html_e( 'URL hinzufügen', 'vgw-metis' ); ?></button>

                </td>
            </tr>
            </tbody>
        </table>

        <h2><?php esc_html_e( 'Textdetails', 'vgw-metis' ); ?></h2>

        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="title"><?php esc_html_e( 'Titel', 'vgw-metis' ); ?></label>
                </th>
                <td>
                    <output name="title" id="title"><?php echo esc_html( get_the_title( $this->post_id ) ) ?></output>
                    <input type="hidden" name="title"
                           value="<?php echo esc_html( get_the_title( $this->post_id ) ) ?>"/>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="text_type"><?php esc_html_e( 'Textart', 'vgw-metis' ); ?></label>
                </th>
                <td>
                    <output name="text_type"
                            id="text_type"><?php echo esc_html( $this->get_text_type_label() ); ?></output>
                    <input type="hidden" name="text_type" value="<?php echo esc_html( $this->pixel->text_type ); ?>"/>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="text_length"><?php esc_html_e( 'Textlänge', 'vgw-metis' ); ?></label>
                </th>
                <td>
                    <output name="text_length"
                            id="text_length"><?php echo Services::calculate_post_text_length( $this->post_id ); ?></output>
                    <input type="hidden" name="text_length"
                           value="<?php echo Services::calculate_post_text_length( $this->post_id ); ?>"/>
                </td>
            </tr>
            </tbody>
            <tr>
                <th scope="row">
                    <label for="text"><?php esc_html_e( 'Text', 'vgw-metis' ); ?></label>
                </th>
                <td>
                    <textarea disabled
                              id="text"><?php echo Services::get_striped_post_content( $this->post_id ); ?></textarea>
                    <input type="hidden" name="text"
                           value="<?php echo Services::get_striped_post_content( $this->post_id ); ?>"/>
                </td>
            </tr>
        </table>

        <h2><?php esc_html_e( 'Beteiligte', 'vgw-metis' ); ?></h2>

        <div id="transfer-list">
            <table id="available-participants">
                <thead>
                <tr class="table-title-row">
                    <th colspan="5">
                        Verfügbare Beteiligte
                    </th>
                </tr>
                <tr class="column-titles-row">
                    <th>Vorname</th>
                    <th>Nachname</th>
                    <th>Karteinummer</th>
                    <th>Funktion</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
				<?php
				foreach ( $this->participants as $participant ) {
					if ( $participant['id'] !== $this->current_user_as_participant->id ) {
						?>
                        <tr id="available-participant-<?php echo (int) $participant['id']; ?>">
                            <td><?php echo esc_html( $participant['first_name'] ); ?></td>
                            <td><?php echo esc_html( $participant['last_name'] ); ?></td>
                            <td><?php echo esc_html( $participant['file_number'] ); ?></td>
                            <td><?php echo esc_html( List_Table_Participants::participant_select_options[ $participant['involvement'] ] ); ?></td>
                            <td><span class="add-participant dashicons dashicons-arrow-right-alt2"
                                      data-participant="<?php echo esc_html( json_encode( $participant ) ); ?>"></span>
                            </td>
                        </tr>
						<?php
					}
				}
				?>
                </tbody>
            </table>

            <table id="chosen-participants">
                <thead>
                <tr class="table-title-row">
                    <th colspan="5">
                        Für die Meldung ausgewählte Beteiligte
                    </th>
                </tr>
                <tr class="column-titles-row">
                    <th></th>
                    <th>Vorname</th>
                    <th>Nachname</th>
                    <th>Karteinummer</th>
                    <th>Funktion</th>
                </tr>
                </thead>
                <tbody>
                <tr id="chosen-participant-<?php echo (int) $this->current_user_as_participant->id; ?>">
                    <td>&nbsp;</td>
                    <td><?php echo esc_html( $this->current_user_as_participant->first_name ); ?></td>
                    <td><?php echo esc_html( $this->current_user_as_participant->last_name ); ?></td>
                    <td><?php echo esc_html( $this->current_user_as_participant->file_number ); ?></td>
                    <td>
                        <select class="participant-function" id="participant-function-select-${pdata.id}">
                            <option
								<?php $this->current_user_as_participant->involvement === Common::INVOLVEMENT_AUTHOR ? 'selected' : ''; ?>
                                    value="<?php echo Common::INVOLVEMENT_AUTHOR; ?>"
                            ><?php echo esc_html_e( 'Autor', 'vgw-metis' ); ?></option>
                            <option
								<?php $this->current_user_as_participant->involvement === Common::INVOLVEMENT_TRANSLATOR ? 'selected' : ''; ?>
                                    value="<?php echo Common::INVOLVEMENT_TRANSLATOR; ?>"
                            ><?php echo esc_html_e( 'Übersetzer', 'vgw-metis' ); ?></option>

                        </select>
                        <input
                                type="hidden"
                                name="participants[]"
                                id="hidden-participant-<?php echo (int) $this->current_user_as_participant->id; ?>"
                                value="<?php echo esc_html( json_encode( $this->current_user_as_participant ) ); ?>"
                        />
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <hr/>

        <button type="submit"
                class="button button-primary"><?php esc_html_e( 'Meldung absenden', 'vgw-metis' ); ?></button>
        <a class="button button-secondary"
           onclick="history.back()"><?php esc_html_e( 'Abbrechen und zurück', 'vgw-metis' ); ?></a>

    </form>
</div>
