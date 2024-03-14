<?php

namespace WP_VGWORT;

/**
 * Template for the create message error view
 *
 * @package     vgw-metis
 * @copyright   Verwertungsgesellschaft Wort
 * @license     https://www.gnu.org/licenses/gpl-3.0.html
 * @author      Torben Gallob
 * @author      Michael Hillebrand
 *
 */
?>

<div class="wrap message">
    <h1><?php esc_html_e( 'Meldung erstellen', 'vgw-metis' ); ?></h1>
	<?php esc_html_e( 'VG WORT METIS', 'vgw-metis' ); ?> <?php esc_html_e( $this->plugin::VERSION ); ?>
    <hr/>

	<?php Notifications::display_error_notice( $this->error_message ); ?>
