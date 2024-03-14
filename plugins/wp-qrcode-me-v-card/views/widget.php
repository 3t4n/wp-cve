<?php
defined( 'ABSPATH' ) || exit;

/* @var $qrcode string of qr-code image url */
/* @var $title string of widget name */
/* @var $as_link string */
/* @var $code_id int */

$code_id = WQM_Common::clear_digits( $code_id );
?>
<div class="qr-code-me-v-card">
	<?php switch ( $as_link ) {
		case 'none': ?>
            <img src="<?php esc_attr_e( $qrcode ) ?>" alt="<?php esc_attr_e( $title ) ?>"
                 title="<?php esc_attr_e( $title ) ?>"/><?php
			break;
		case 'img': ?>
        <a href="<?php esc_attr_e( $qrcode ) ?>">
            <img src="<?php esc_attr_e( $qrcode ) ?>" alt="<?php esc_attr_e( $title ) ?>"
                 title="<?php esc_attr_e( $title ) ?>"/>
            </a><?php
			break;
		case 'vcf': ?>
        <a href="/?qr-code=<?php echo  $code_id ?>">
            <img src="<?php esc_attr_e( $qrcode ) ?>" alt="<?php esc_attr_e( $title ) ?>"
                 title="<?php esc_attr_e( $title ) ?>"/>
            </a><?php
			break;

	} ?>

</div>