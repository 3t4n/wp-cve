<?php

use Dev4Press\v43\Library;
use Dev4Press\v43\WordPress;
use function Dev4Press\v43\Functions\panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="d4p-info-block">
    <h3>
		<?php esc_html_e( 'System Information', 'd4plib' ); ?>
    </h3>
    <div>
        <ul class="d4p-info-list">
            <li>
                <span><?php esc_html_e( 'PHP Version', 'd4plib' ); ?>:</span><strong><?php echo esc_html( Library::instance()->php_version() ); ?></strong>
            </li>
            <li>
                <span><?php

	                /* translators: About System information. %s: CMS name. */
	                echo sprintf( esc_html__( '%s Version', 'd4plib' ), esc_html( WordPress::instance()->cms_title() ) );

	                ?>:</span><strong><?php echo esc_html( WordPress::instance()->version() ); ?></strong>
            </li>
        </ul>
        <hr/>
        <ul class="d4p-info-list">
            <li>
                <span><?php esc_html_e( 'Debug Mode', 'd4plib' ); ?>:</span><strong><?php echo WordPress::instance()->is_debug() ? esc_html__( 'ON', 'd4plib' ) : esc_html__( 'OFF', 'd4plib' ); ?></strong>
            </li>
            <li>
                <span><?php esc_html_e( 'Script Debug', 'd4plib' ); ?>:</span><strong><?php echo WordPress::instance()->is_script_debug() ? esc_html__( 'ON', 'd4plib' ) : esc_html__( 'OFF', 'd4plib' ); ?></strong>
            </li>
        </ul>
    </div>
</div>

<div class="d4p-info-block">
    <h3>
		<?php esc_html_e( 'Plugin Information', 'd4plib' ); ?>
    </h3>
    <div>
        <ul class="d4p-info-list">
            <li>
                <span><?php esc_html_e( 'Path', 'd4plib' ); ?>:</span><strong><?php echo esc_html( panel()->a()->path ); ?></strong>
            </li>
            <li>
                <span><?php esc_html_e( 'URL', 'd4plib' ); ?>:</span><strong><?php echo esc_html( panel()->a()->url ); ?></strong>
            </li>
        </ul>
    </div>
</div>


<div class="d4p-info-block">
    <h3>
		<?php esc_html_e( 'Shared Library', 'd4plib' ); ?>
    </h3>
    <div>
        <ul class="d4p-info-list">
            <li>
                <span><?php esc_html_e( 'Version', 'd4plib' ); ?>:</span><strong><?php echo esc_html( Library::instance()->version() ); ?></strong>
            </li>
            <li>
                <span><?php esc_html_e( 'Build', 'd4plib' ); ?>:</span><strong><?php echo esc_html( Library::instance()->build() ); ?></strong>
            </li>
        </ul>
        <hr/>
        <ul class="d4p-info-list">
            <li>
                <span><?php esc_html_e( 'Path', 'd4plib' ); ?>:</span><strong><?php echo esc_html( Library::instance()->path() ); ?></strong>
            </li>
            <li>
                <span><?php esc_html_e( 'URL', 'd4plib' ); ?>:</span><strong><?php echo esc_html( Library::instance()->url() ); ?></strong>
            </li>
        </ul>
    </div>
</div>
