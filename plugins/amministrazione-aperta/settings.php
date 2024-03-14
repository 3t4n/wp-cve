<?php

/**
 * Class for registering a new settings page under Settings.
 */
class AmministrazioneAperta_options {
 
    function __construct() {
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
    }
 
    /**
     * Registers a new settings page
     */
    function admin_menu() {
        add_options_page(
            'Amministrazione Aperta',
            'Amministrazione Aperta',
            'manage_options',
            'options_page_slug',
            array(
                $this,
                'settings_page'
            )
        );
    }
 
    /**
     * Settings page display callback.
     */
    function settings_page() {
        if (!(is_plugin_active( 'amministrazione-aperta/amministrazioneaperta.php' ))) { echo 'Plugin non installato!'; return;}
	
		if(isset($_POST['Submit'])) {

			if(isset($_POST['aa_disabilita_visauomatica_allegati_n'])){
				update_option( 'aa_disabilita_visauomatica_allegati', '1' );
			} else {
				update_option( 'aa_disabilita_visauomatica_allegati', '0' );
			}
		}
		
		echo '<h2>Amministrazione Aperta</h2>
		<form method="post" name="options" target="_self">';
		settings_fields( 'at_option_group' );
		
		echo '<table class="form-table"><tbody>';
		
		echo '<tr><td><input type="checkbox" name="aa_disabilita_visauomatica_allegati_n" ';
		$get_aa_disabilita_visauomatica_allegati = get_option('aa_disabilita_visauomatica_allegati');
		if ($get_aa_disabilita_visauomatica_allegati == '1') {
			echo 'checked=\'checked\'';
		}
		echo '/>&nbsp;Spunta questa casella per disabilitare la visualizzazione automatica degli allegati (es. se vuoi inserirli manualmente nel testo o usi un plugin per la loro visualizzazione come WP Attachments)</td></tr>';
		echo '</tbody></table>';
		
		echo '<p class="submit"><input type="submit"  class="button-primary" name="Submit" value="Aggiorna Impostazioni" /></p>
		</form>';
    }
}
 
new AmministrazioneAperta_options();