<?php
/**
 * @var bool $has_bpost_order
 * @var string $attachment_url
 * @var string $caption
 * @var string $track_url
 */

?>
<img id="bpost-logo" alt="bpost-logo" src="<?php echo esc_url(BPOST_PLUGIN_URL) ?>/public/images/bpost-logo.png"/>

<?php if ( $has_bpost_order ) { ?>
    <a class="button" href="<?php echo esc_url( $attachment_url ) ?>">
        <i style="color: #e51837" class="fa fa-print" aria-hidden="true"></i>
		<?php echo bpost__( 'Show label' ); ?>
    </a>

    <br>
    <a href="<?php echo esc_url( $track_url ) ?>" target="_blank" class="button">
        <i style="color: #e51837" class="fa fa-truck" aria-hidden="true"></i>
		<?php echo bpost__( 'Show tracking' ); ?>
    </a>

<?php } else { ?>
    <a class="button" disabled="disabled">
        <i style="color: #e51837" class="fa fa-print" aria-hidden="true"></i>
		<?php
		echo bpost__( 'Label not available' ); ?>
    </a>
<?php } ?>

<?php
if ( $caption ) {
	echo '<p>' . esc_html( $caption ) . '</p>';
}
?>

<div class="clear"></div>
