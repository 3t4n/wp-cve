<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/** @var string[] $bpost_meta */
?>
    <h4><?php echo bpost__( 'bpost shipping details' ); ?></h4>

    <div>
		<?php
		foreach ( $bpost_meta as $key => $value ) {
			if ( $key !== 'status' ) {
				echo '<p><strong>' . esc_html( $value['translation'] ) . ':</strong> <span id="bpost-order-meta-' . $key . '">' . esc_html( $value['value'] ) . '</span></p>';
			}
		}
		?>
    </div>

<?php if ( ! empty( $bpost_meta['status'] ) ) {
	$value = $bpost_meta['status'];
	?>
    <p>
		<?php echo '<strong>', esc_html( $value['translation'] ), ':</strong> <span id="bpost-order-meta-status">', esc_html( $value['value'] ), '</span>'; ?>
        <button type="button" class="button bpost-refresh-box-status-action">
			<?php echo bpost__( 'Refresh' ); ?>
        </button>
    </p>
<?php } ?>