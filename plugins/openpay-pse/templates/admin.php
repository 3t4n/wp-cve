<?php
/*  
  Title:	Openpay Payment extension for WooCommerce
  Author:	Openpay
  URL:		http://www.openpay.mx
  License: GNU General Public License v3.0
  License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */
?>

<h3>
    <?php _e('Openpay PSE', 'woothemes'); ?>
</h3>

<?php if(!$this->validateCurrency()): ?>
    <div class="inline error">Openpay PSE Plugin is only available for COP currency.</div>
<?php endif; ?>

<table class="form-table">
    <?php $this->generate_settings_html(); ?>
</table>
