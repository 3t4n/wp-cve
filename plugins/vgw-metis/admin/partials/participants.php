<?php
namespace WP_VGWORT;

/**
 * Template for the participant List view
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
	<h1><?php esc_html_e( 'Beteiligtenverwaltung', 'vgw-metis' ); ?></h1>
	<?php esc_html_e( 'VG WORT METIS', 'vgw-metis' ); ?> <?php esc_html_e($this->plugin::VERSION) ?>
	<hr/>
	<?php $this->list_table_participants->display() ?>
</div>
