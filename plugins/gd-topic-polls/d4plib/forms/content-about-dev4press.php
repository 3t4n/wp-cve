<?php

use Dev4Press\v43\API\Store;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$_plugins = Store::instance()->plugins();

?>

<div class="d4p-about-dev4press-plugins">
	<?php

	foreach ( $_plugins as $_plugin ) {
		$_url     = Store::instance()->url( $_plugin['code'] );
		$_pro     = Store::instance()->is_pro( $_plugin['code'] );
		$_free    = Store::instance()->is_free( $_plugin['code'] );
		$_edition = $_pro && $_free ? '_both' : '_single';

		if ( isset( $_plugin['internal'] ) && $_plugin['internal'] ) {
			continue;
		}

		?>

        <div class="d4p-dev4press-plugin">
            <div class="_badge">
                <div class="_icon" style="background-color: <?php echo esc_attr( $_plugin['color'] ); ?>;">
                    <a href="<?php echo esc_url( $_url ); ?>" target="_blank" rel="noopener"><i class="d4p-icon d4p-plugin-<?php echo esc_attr( $_plugin['code'] ); ?>"></i></a>
                </div>
                <div class="_edition <?php echo esc_attr( $_edition ); ?>">
					<?php
					if ( $_pro ) {
						echo '<span class="_pro">Pro</span>';
					}
					?>

					<?php
					if ( $_free ) {
						echo '<span class="_free">Free</span>';
					}
					?>
                </div>
            </div>
            <div class="_info">
                <h5>
                    <a href="<?php echo esc_url( $_url ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $_plugin['name'] ); ?></a>
                </h5>
                <em><?php echo esc_html( $_plugin['punchline'] ); ?></em>
                <p><?php echo esc_html( $_plugin['description'] ); ?></p>
            </div>
        </div>

	<?php } ?>
</div>
