<?php
defined( 'ABSPATH' ) || exit;

/* @var $card_id int of card ID*/
/* @var $qr_code string of QR code image url */
?>
<div class="qr-code-card qr-code-card-<?php echo WQM_Common::clear_digits($card_id) ?>">
    <img src="<?php esc_attr_e($qr_code) ?>" alt="contact information in QR code" title="contact information in QR code"/>
</div>

