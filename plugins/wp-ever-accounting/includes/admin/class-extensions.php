<?php
/**
 * Admin Extensions Page.
 *
 * @since       1.0.2
 * @subpackage  Admin/Tools
 * @package     EverAccounting
 */

namespace EverAccounting\Admin;

defined( 'ABSPATH' ) || exit();

/**
 * Class Admin
 *
 * @package EverAccounting\Admin
 * @since   1.1.0
 */
class Extensions {

	/**
	 * Extensions constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'register_page' ), 999 );
	}

	/**
	 * Registers the extensions page.
	 */
	public function register_page() {
		add_submenu_page(
			'eaccounting',
			__( 'Extensions', 'wp-ever-accounting' ),
			__( 'Extensions', 'wp-ever-accounting' ),
			'manage_options',
			'ea-extensions',
			array( $this, 'render_page' )
		);
	}

	/**
	 * Render page.
	 *
	 * @since 1.1.0
	 */
	public function render_page() {
		$extensions = $this->get_extensions();
		?>
		<div class="wrap">
			<h2><?php esc_html_e( 'Extensions', 'wp-ever-accounting' ); ?></h2>
			<div class="ea-extensions ea-row">
				<?php foreach ( $extensions as $extension ) : ?>
					<div class="ea-col-3">
						<div class="ea-extension ea-card">
							<div class="ea-card__inside">
								<h3 class="ea-extension__title"><?php echo esc_html( $extension->info->title ); ?></h3>
								<a href="<?php echo esc_url( $extension->info->link ); ?>" title="<?php echo esc_html( $extension->info->title ); ?>">
									<img class="attachment-download-grid-thumb size-download-grid-thumb wp-post-image" src="<?php echo esc_url( $extension->info->thumbnail ); ?>">
								</a>
								<p><?php echo wp_kses_post( $extension->info->excerpt ); ?></p>
								<a class="button-secondary" href="<?php echo esc_url( $extension->info->link ); ?>" target="_blank"><?php esc_html_e( 'Get this Extension', 'wp-ever-accounting' ); ?></a>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Get extensions.
	 *
	 * @return array
	 */
	public function get_extensions() {
		$cache = get_transient( 'wpeveraccounting_extensions_feed' );

		if ( false === $cache ) {
			$url = 'https://wpeveraccounting.com/edd-api/products/';

			$feed = wp_remote_get( esc_url_raw( $url ), array( 'sslverify' => false ) );
			if ( ! is_wp_error( $feed ) ) {
				if ( isset( $feed['body'] ) && strlen( $feed['body'] ) > 0 ) {
					$body  = wp_remote_retrieve_body( $feed );
					$cache = json_decode( $body )->products;
					set_transient( 'wpeveraccounting_extensions_feed', $cache, 3600 );
				}
			} else {
				$cache = '<div class="error"><p>' . __( 'There was an error retrieving the extensions list from the server. Please try again later.', 'wp-ever-accounting' ) . '</div>';
			}
		}

		return $cache;
	}

}

new Extensions();
