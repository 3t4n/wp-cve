<?php

namespace WP_VGWORT;

/**
 * Template for the Messages / Posts & Pages with Pixels List view
 *
 * @package     vgw-metis
 * @copyright   Verwertungsgesellschaft Wort
 * @license     https://www.gnu.org/licenses/gpl-3.0.html
 * @author      Torben Gallob
 * @author      Michael Hillebrand
 *
 */
?>

<div class="wrap messages">
	<h1><?php esc_html_e( 'Meldungsübersicht', 'vgw-metis' ); ?></h1>
	<?php esc_html_e( 'VG WORT METIS', 'vgw-metis' ); ?> <?php esc_html_e($this->plugin::VERSION); ?>
	<hr/>

	<form method="get" id="messages-form">
		<?php $this->messages_table->display() ?>
	</form>
</div>
