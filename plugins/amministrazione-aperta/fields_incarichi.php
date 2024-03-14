<?php

abstract class AmministrazioneAperta_Metabox_Incarichi {
 
    public static function add() {
        $screens = [ 'incarico' ];
        foreach ( $screens as $screen ) {
            add_meta_box(
                'wporg_box_id',          // Unique ID
                'Dettagli incarico', // Box title
                [ self::class, 'html' ],   // Content callback, must be of type callable
                $screen                  // Post type
            );
        }
    }

    public static function save( int $post_id ) {
        $fields = array(
            'ammap_beneficiario',
            'ammap_importo_previsto',
            'ammap_importo',
            'ammap_data_incarico',
            'ammap_protocollo',
            'ammap_data_inizio',
            'ammap_data_fine'
        );
        foreach( $fields as $field ) {
            if ( array_key_exists( $field, $_POST ) ) {
                update_post_meta(
                    $post_id,
                    $field,
                    sanitize_text_field( $_POST[ $field ] )
                );
            }
        }
    }

    public static function html( $post ) { ?>
        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row"><label for="ammap_beneficiario">Soggetto percettore</label></th>
                    <td><input name="ammap_beneficiario" type="text" id="ammap_beneficiario" value="<?php echo esc_attr( get_post_meta( $post->ID, 'ammap_beneficiario', true ) ); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="ammap_importo_previsto">Importo previsto</label></th>
                    <td><input name="ammap_importo_previsto" type="number" step='0.01' placeholder='0.00' id="ammap_importo_previsto" value="<?php echo esc_attr( get_post_meta( $post->ID, 'ammap_importo_previsto', true ) ); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="ammap_importo">Importo erogato</label></th>
                    <td><input name="ammap_importo" type="number" step='0.01' placeholder='0.00' id="ammap_importo" value="<?php echo esc_attr( get_post_meta( $post->ID, 'ammap_importo', true ) ); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="ammap_data_incarico">Data affidamento</label></th>
                    <td><input name="ammap_data_incarico" type="date" id="ammap_data_incarico" value="<?php echo get_post_meta( $post->ID, 'ammap_data_incarico', true ) ?? esc_attr( date("Y-m-d",strtotime(get_post_meta( $post->ID, 'ammap_data_incarico', true ) ) ) ); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="ammap_protocollo">N° protocollo</label></th>
                    <td><input name="ammap_protocollo" type="text" id="ammap_protocollo" value="<?php echo esc_attr( get_post_meta( $post->ID, 'ammap_protocollo', true ) ); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="ammap_data_inizio">Data inizio</label></th>
                    <td><input name="ammap_data_inizio" type="date" id="ammap_data_inizio" value="<?php echo get_post_meta( $post->ID, 'ammap_data_inizio', true ) ?? esc_attr( date("Y-m-d",strtotime(get_post_meta( $post->ID, 'ammap_data_inizio', true ) ) ) ); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="ammap_data_fine">Data fine</label></th>
                    <td><input name="ammap_data_fine" type="date" id="ammap_data_fine" value="<?php echo get_post_meta( $post->ID, 'ammap_data_fine', true ) ?? esc_attr( date("Y-m-d",strtotime(get_post_meta( $post->ID, 'ammap_data_fine', true ) ) ) ); ?>" class="regular-text"></td>
                </tr>
            </tbody>
        </table>
        <?php
    }
}

add_action( 'add_meta_boxes', [ 'AmministrazioneAperta_Metabox_Incarichi', 'add' ] );
add_action( 'save_post', [ 'AmministrazioneAperta_Metabox_Incarichi', 'save' ] );

?>