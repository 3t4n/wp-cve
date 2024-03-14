<?php

abstract class AmministrazioneAperta_Metabox_Concessioni {
 
    public static function add() {
        $screens = [ 'spesa' ];
        foreach ( $screens as $screen ) {
            add_meta_box(
                'wporg_box_id',          // Unique ID
                'Dettagli concessione', // Box title
                [ self::class, 'html' ],   // Content callback, must be of type callable
                $screen                  // Post type
            );
        }
    }

    public static function save( int $post_id ) {
        $fields = array(
            'ammap_beneficiario',
            'ammap_importo',
            'ammap_data',
            'ammap_fiscale',
            'ammap_norma',
            'ammap_responsabile',
            'ammap_determina',
            'ammap_assegnazione'
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
                    <th scope="row"><label for="ammap_importo">Importo</label></th>
                    <td><input name="ammap_importo" type="number" step='0.01' placeholder='0.00' id="ammap_importo" value="<?php echo esc_attr( get_post_meta( $post->ID, 'ammap_importo', true ) ); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="ammap_data">Data</label></th>
                    <td><input name="ammap_data" type="date" id="ammap_data" value="<?php echo get_post_meta( $post->ID, 'ammap_data', true ) ?? esc_attr( date("Y-m-d",strtotime(get_post_meta( $post->ID, 'ammap_data', true ) ) ) ); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="ammap_fiscale">Dati fiscali</label></th>
                    <td><input name="ammap_fiscale" type="text" id="ammap_fiscale" value="<?php echo esc_attr( get_post_meta( $post->ID, 'ammap_fiscale', true ) ); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="ammap_norma">Norma</label></th>
                    <td><input name="ammap_norma" type="text" id="ammap_norma" value="<?php echo esc_attr( get_post_meta( $post->ID, 'ammap_norma', true ) ); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="ammap_responsabile">Responsabile</label></th>
                    <td><input name="ammap_responsabile" type="text" id="ammap_responsabile" value="<?php echo esc_attr( get_post_meta( $post->ID, 'ammap_responsabile', true ) ); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="ammap_determina">Determina</label></th>
                    <td><input name="ammap_determina" type="text" id="ammap_determina" value="<?php echo esc_attr( get_post_meta( $post->ID, 'ammap_determina', true ) ); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="ammap_assegnazione">Modalità di assegnazione</label></th>
                    <td>
                        <select id="ammap_assegnazione" name="ammap_assegnazione">
                            <?php
                            $assegn_arr = array( 
                                'Chiamata Diretta',
                                'Bando Pubblico',
                                'Cottimo Fiduciario',
                                'Mercato Elettronico',
                                'Convenzione CONSIP',
                                'Procedura aperta',
                                'Procedura negoziata',
                                'Procedura ristretta',
                                'Procedura selettiva'
                            );
                            foreach( $assegn_arr as $aa ) {
                                echo '<option '.( get_post_meta( $post->ID, 'ammap_assegnazione', true ) == $aa ? 'selected' : '' ).' value="'.esc_attr( $aa ).'">'.esc_html( $aa ).'</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php
    }
}

add_action( 'add_meta_boxes', [ 'AmministrazioneAperta_Metabox_Concessioni', 'add' ] );
add_action( 'save_post', [ 'AmministrazioneAperta_Metabox_Concessioni', 'save' ] );
?>