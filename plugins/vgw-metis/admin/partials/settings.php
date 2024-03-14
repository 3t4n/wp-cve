<?php

namespace WP_VGWORT;

/**
 * Template for the Settings view
 *
 * @package     vgw-metis
 * @copyright   Verwertungsgesellschaft Wort
 * @license     https://www.gnu.org/licenses/gpl-3.0.html
 * @author      Torben Gallob
 * @author      Michael Hillebrand
 *
 */
?>
<div class="wrap">
    <?php settings_errors(); ?>
    <h1><?php esc_html_e('Einstellungen', 'vgw-metis'); ?></h1>
	<?php esc_html_e('VG WORT METIS', 'vgw-metis'); ?> <?php esc_html_e($this->plugin::VERSION) ?>
    <hr />
    <form action="options.php" method="post">
	    <?php
            settings_fields( 'wp_metis_settings' );
	        do_settings_sections( 'wp_metis_settings' );
	        submit_button( esc_html__('Einstellungen speichern', 'vgw-metis'), 'primary large' );
        ?>
        <input type="hidden" name="_wp_http_referer" value="admin.php?page=metis-settings" />
    </form>
    <br /><br />
    <h2>Aktionen</h2>
    <table class="form-table" role="presentation">
        <tr>
            <th scope="row"><?php esc_html_e( 'API Verbindung', 'vgw-metis' ) ?></th>
            <td>
                <a
                   id="btn-test-connection"
                   class="button button-secondary"
                   href="<?php echo esc_url(admin_url( 'admin-post.php?action=wp_metis_check_api_key&page=metis-settings' )) ?>"><?php esc_html_e( "API testen", 'vgw-metis' ) ?>
                </a>
                <p class="description"><?php esc_html_e( 'Überprüfen, ob die API-Verbindung mit dem hinterlegten API-Key funktioniert.', 'vgw-metis' ) ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php esc_html_e( 'Zählmarkenbestellung', 'vgw-metis' ) ?></th>
            <td>
                <a
                    id="btn-order-marks"
                    class="button button-secondary"
                    href="<?php echo esc_url(admin_url('admin-post.php?action=wp_metis_order_pixels&page=metis-settings')); ?>"><?php esc_html_e( "Zählmarken bestellen", 'vgw-metis' ) ?>
                </a>
                <p class="description"><?php echo sprintf( esc_html__( 'Manuell %s neue Zählmarken bestellen', 'vgw-metis' ), Common::NUMBER_ORDER_PIXEL ) ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php esc_html_e( 'Zählmarkenüberprüfung', 'vgw-metis' ) ?></th>
            <td>
                <a
                    id="btn-check-pixel"
                    class="button button-secondary"
                    href="<?php echo esc_url(admin_url('admin-post.php?action=wp_metis_check_pixels&page=metis-settings')); ?>"><?php esc_html_e( "Überprüfung starten", 'vgw-metis' ) ?>
                </a>
                <p class="description"><?php esc_html_e( 'Der Status aller bekannten Zählmarken wird mit den Daten aus dem T.O.M. Portal abgeglichen.', 'vgw-metis' ) ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php esc_html_e( 'Scan-Funktion', 'vgw-metis' ) ?></th>
            <td>
                <a
                    id="btn-scan-page"
                    class="button button-secondary"
                    href="<?php echo esc_url(admin_url('admin-post.php?action=wp_metis_scan_pixels&page=metis-settings')); ?>"><?php esc_html_e( "Scan starten", 'vgw-metis' ) ?>
                </a>
                <p class="description"><?php esc_html_e( 'Beiträge und Seiten nach Zählmarken durchsuchen und verknüpfen.', 'vgw-metis' ) ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php esc_html_e( 'CSV-Import aus T.O.M.', 'vgw-metis' ) ?></th>
            <td>
                <form method="post" action="admin-post.php" enctype="multipart/form-data">
                    <input id="btn-csv-import" type="submit" class="button button-secondary" value="<?php esc_html_e( "Zählmarken importieren", 'vgw-metis' ) ?>" />
                    <?php $this->csv->render_tom_csv_import_file_input(); ?>
                    <input type="hidden" name="page" value="metis-settings" />
                </form>
                <p class="description"><?php esc_html_e( 'Zählmarken über die aus T.O.M. exportierte CSV-Datei importieren.', 'vgw-metis' ); ?></p>
            </td>
        </tr>
    </table>
</div>
