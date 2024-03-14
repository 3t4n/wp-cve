<?php
/**
 * Envira Gallery Lite Support.
 *
 * @package   Envira_Gallery_Lite
 */

/**
 * Class Envira_Lite_Support
 */
class Envira_Lite_Support {
	/**
	 * Nonce action for the support page.
	 *
	 * @var string
	 */
	private $nonce_action = 'envira-support';

	/**
	 * Action notices.
	 *
	 * @var string
	 */
	private $notice = '';

	/**
	 * All galleries.
	 *
	 * @var array
	 */
	private $all_galleries = [];

	/**
	 * WP actions and filters, needed for support page.
	 */
	public function hooks() {
		add_action( 'admin_menu', [ $this, 'admin_menu' ] );
	}

	/**
	 * Register the support page.
	 */
	public function admin_menu() {
		$plugin = Envira_Gallery_Lite::get_instance();

		$hook_suffix = add_submenu_page(
			'',
			__( 'Support General', 'envira-gallery-lite' ),
			__( 'Support', 'envira-gallery-lite' ),
			apply_filters( 'envira_gallery_menu_cap_support', 'manage_options' ),
			$plugin->plugin_slug . '-support',
			[ $this, 'support_page' ]
		);

		if ( $hook_suffix ) {
			add_action( "load-$hook_suffix", [ $this, 'on_load_page' ] );

		}
	}

	/**
	 * On load page actions.
	 */
	public function on_load_page() {
		$this->all_galleries = $this->get_all_galleries();
		$this->notice        = '';

		if ( empty( $this->all_galleries ) ) {
			return;
		}

		$valid_request = isset( $_POST['action'], $_POST['envira_nonce'] );
		$valid_nonce   = wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['envira_nonce'] ) ), $this->nonce_action );

		if ( ! $valid_request || ! $valid_nonce ) {
			return;
		}
		$gallery_id = isset( $_POST['gallery_id'] ) ? intval( $_POST['gallery_id'] ) : null;
		$action     = sanitize_text_field( wp_unslash( $_POST['action'] ) );

		switch ( $action ) {
			case 'toggle-debug': // General Tab.
				if ( get_option( 'envira_debug' ) ) {
					delete_option( 'envira_debug' );
				} else {
					add_option( 'envira_debug', true );
				}
				$this->notice = '<div class="notice notice-warning">Successfully toggled debug mode.</div>';
				break;
			case 'fix-galleries':
				if ( null === $gallery_id ) {
					return;
				}
				$this->notice = $this->fix_gallery( $gallery_id );
				break;
		}
	}

	/**
	 * Get all galleries.
	 *
	 * @return array{string:array}
	 */
	private function get_all_galleries() {
		$galleries_posts = new WP_Query(
			[
				'post_type'      => 'envira',
				'posts_per_page' => - 1,
				'post_status'    => 'any',
			]
		);
		if ( ! $galleries_posts->have_posts() ) {
			return [];
		}

		$gallery_ids    = array_map(
			function ( $post ) {
				return "$post->ID";
			},
			$galleries_posts->posts
		);
		$galleries_meta = array_map(
			function ( $gallery_id ) {
				return get_post_meta( $gallery_id, '_eg_gallery_data', true );
			},
			$gallery_ids
		);

		return array_combine( $gallery_ids, $galleries_meta );
	}

	/**
	 * Fix gallery missing src, link or data.
	 *
	 * @param int $gallery_id Gallery ID.
	 *
	 * @return string
	 */
	private function fix_gallery( $gallery_id ) {
		$all_galleries = [];
		$notice        = '<div class="notice notice-warning">';

		if ( - 1 === $gallery_id ) {
			$all_galleries = $this->get_all_galleries();
		} else {
			$all_galleries[ "$gallery_id" ] = get_post_meta( $gallery_id, '_eg_gallery_data', true );
		}

		foreach ( $all_galleries as $gallery_id => $data ) {
			$updated = 0;
			foreach ( $data['gallery'] as $item_id => $item ) {
				if ( empty( $item['src'] ) || empty( $item['link'] ) ) {
					++$updated;
				}

				if ( empty( $item['src'] ) ) {
					$src = wp_get_attachment_url( $item_id );
					if ( false === $src ) {
						// Item does not exist on db, remove.
						unset( $item );
						continue;
					}
					$item['src'] = $src;
					$notice     .= "<p>Updated <strong>src</strong> of image #$item_id to {$item['src']}</p>";
				}

				$valid_link = isset( $item['link'] ) && wp_http_validate_url( $item['link'] );

				if ( ! $valid_link ) {
					$item['link'] = $item['src'];
					$notice      .= "<p>Updated <strong>link</strong> of image #$item_id to {$item['src']}</p>";
				}
			}

			if ( $updated > 0 ) {
				update_post_meta( $gallery_id, '_eg_gallery_data', $data );
			}
			$notice .= sprintf(
				'<p>Updated a total of <strong>%s</strong> items for the <strong>%s</strong> gallery. ID: %s</p>',
				$updated,
				get_the_title( $gallery_id ),
				$gallery_id
			);
		}
		$notice .= '</div>';

		return $notice;
	}

	/**
	 * Output the support page.
	 */
	public function support_page() {
		$galleries    = $this->all_galleries;
		$debug_const  = defined( 'ENVIRA_DEBUG' ) && filter_var( ENVIRA_DEBUG, FILTER_VALIDATE_BOOL );
		$debug_option = filter_var( get_option( 'envira_debug' ), FILTER_VALIDATE_BOOL );
		$options      = '<option value="-1">ALL</option>';
		foreach ( $galleries as $gallery ) {
			$post        = get_post( $gallery['id'] );
			$option_name = sprintf(
				'%s -- %s Images (ID: %s)',
				$post->post_title,
				count( $gallery['gallery'] ),
				$gallery['id']
			);
			$options    .= sprintf(
				'<option value="%s">%s</option>',
				esc_attr( $gallery['id'] ),
				esc_html( $option_name )
			);
		}
		?>
		<div class="envira-welcome-wrap envira-welcome">
			<div class="wrap">
				<h1>Support</h1>
				<?php echo wp_kses_post( $this->notice ); ?>
				<form method="post">
					<?php wp_nonce_field( $this->nonce_action, 'envira_nonce', false ); ?>
					<div class="card">
						<h2 class="title">Fix Gallery</h2>
						<p class="subtitle"><small>Fixes gallery missing data.</small></p>
							<p>
								<label for="fix-image-links-gallery" >Select Gallery:</label>
								<select name="gallery_id" id="fix-image-links-gallery">
									<?php echo wp_kses_post( $options ); ?>
								</select>
							</p>
							<button type="submit" class="button button-primary" name="action" value="fix-galleries">3
								<?php esc_html_e( 'Fix', 'envira-gallery-lite' ); ?>
							</button>
					</div>
					<div class="card">
						<h2 class="title">Debug toggle</h2>
						<p>ENVIRA_DEBUG: <strong><?php echo $debug_const ? 'ON' : 'OFF'; ?></strong></p>
						<p>envira_debug option: <strong><?php echo $debug_option ? 'ON' : 'OFF'; ?></strong></p>
						<button type="submit" class="button button-primary" name="action" value="toggle-debug">
							<?php esc_html_e( 'Toggle', 'envira-gallery-lite' ); ?>
						</button>
					</div>
				</form>
			</div>
		</div>
		<?php
	}
}
