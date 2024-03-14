<?php

/**
 * Sözleşme Taslak Ekranı Burada
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://yemlihakorkmaz.com
 * @since      1.0.0
 *
 * @package    Korkmaz_contract
 * @subpackage Korkmaz_contract/admin/partials
 */


if (!current_user_can('manage_options')) {
	return;
}
$birinci_sozlesme_metni = new Korkmaz_woo_sales_contract_taslak_ekrani();

echo '<div class="wrap">';

echo '<h1 class="wp-heading-inline">' . __('Sözleşme Düzenleme Ekranı', 'korkmaz_contract') . '</h1> <hr>';

$birinci_sozlesme_metni->taslak_ekrani('birinci_sozlesme_metni');


echo '</div>';
