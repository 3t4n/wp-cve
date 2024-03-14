<?php
namespace ERocket;

class Sharing {
	private $services;

	public function __construct() {
		$this->services = [
			'facebook'  => [
				'label' => __( 'Facebook', 'erocket' ),
				'title' => __( 'Share on Facebook', 'erocket' ),
				'url'   => 'https://facebook.com/sharer/sharer.php?u=%s',
			],
			'twitter'   => [
				'label' => __( 'Twitter', 'erocket' ),
				'title' => __( 'Tweet on Twitter', 'erocket' ),
				'url'   => 'https://twitter.com/intent/tweet?url=%s',
			],
			'linkedin'  => [
				'label' => __( 'LinkedIn', 'erocket' ),
				'title' => __( 'Share on LinkedIn', 'erocket' ),
				'url'   => 'https://www.linkedin.com/shareArticle?mini=true&url=%s',
			],
			'pinterest' => [
				'label' => __( 'Pinterest', 'erocket' ),
				'title' => __( 'Pin on Pinterest', 'erocket' ),
				'url'   => 'https://pinterest.com/pin/create/button/?url=%s',
			],
			'pocket'    => [
				'label' => __( 'Pocket', 'erocket' ),
				'title' => __( 'Save to pocket', 'erocket' ),
				'url'   => 'https://getpocket.com/save?url=%s',
			],
			'reddit'    => [
				'label' => __( 'Reddit', 'erocket' ),
				'title' => __( 'Share on Reddit', 'erocket' ),
				'url'   => 'https://www.reddit.com/submit?url=%s',
			],
			'mail'      => [
				'label' => __( 'Mail', 'erocket' ),
				'title' => __( 'Share via Email', 'erocket' ),
				'url'   => 'mailto:?body=%s',
			],
		];

		add_action( 'admin_menu', [ $this, 'add_settings_page' ] );
		add_filter( 'the_content', [ $this, 'output' ] );
		add_action( 'erocket_sharing_ouput', [ $this, 'output_html' ] );
	}

	public function add_settings_page() {
		$page_hook = add_options_page( __( 'eRocket', 'erocket' ), __( 'eRocket', 'erocket' ), 'manage_options', 'erocket', [ $this, 'render' ] );
		add_action( "load-{$page_hook}", [ $this, 'save' ] );
	}

	public function render() {
		$option = get_option( 'erocket' );
		?>
		<div class="wrap">
			<h1><?= esc_html( get_admin_page_title() ) ?></h1>

			<form method="POST" action="">
				<?php wp_nonce_field( 'save' ) ?>
				<h3><?php esc_html_e( 'Sharing', 'erocket' ); ?></h3>

				<table class="form-table">
					<tr>
						<th><?php esc_html_e( 'Share on', 'erocket' ); ?></th>
						<td>
							<?php $share_text = $option['share_text'] ?? ''; ?>
							<input type="text" name="share_text" value="<?php echo esc_attr( $share_text ); ?>" placeholder="<?= esc_html_e( 'Share on', 'erocket' ); ?>">
						</td>
					</tr>
					<tr>
						<th><?php esc_html_e( 'Social Networks', 'erocket' ); ?></th>
						<td>
							<?php $selected = $option['sharing_services'] ?? array_keys( $this->services ); ?>
							<?php foreach ( $this->services as $key => $service ) : ?>
								<p>
									<label>
										<input type="checkbox" name="sharing_services[]" value="<?= esc_attr( $key ); ?>"<?php checked( in_array( $key, $selected ) ) ?>>
										<?= esc_html( $service['label'] ); ?>
									</label>
								</p>
							<?php endforeach; ?>
						</td>
					</tr>
					<tr>
						<th><?php esc_html_e( 'Position', 'erocket' ); ?></th>
						<td>
							<?php $position = $option['sharing_position'] ?? 'after'; ?>
							<select name="sharing_position">
								<option value="before"<?php selected( $position, 'before' ); ?>><?php esc_html_e( 'Before Content', 'erocket' ); ?></option>
								<option value="after"<?php selected( $position, 'after' ); ?>><?php esc_html_e( 'After Content', 'erocket' ); ?></option>
								<option value="both"<?php selected( $position, 'both' ); ?>><?php esc_html_e( 'Both', 'erocket' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th><?php esc_html_e( 'Post Types', 'erocket' ); ?></th>
						<td>
							<?php
							$selected   = $option['sharing_types'] ?? [ 'post' ];
							$post_types = get_post_types( [ 'public' => true ], 'objects' );
							?>
							<?php foreach ( $post_types as $slug => $post_type ) : ?>
								<p>
									<label>
										<input type="checkbox" name="sharing_types[]" value="<?= esc_attr( $slug ); ?>"<?php checked( in_array( $slug, $selected ) ) ?>>
										<?= esc_html( $post_type->labels->singular_name ); ?>
									</label>
								</p>
							<?php endforeach; ?>
						</td>
					</tr>
				</table>

				<?php submit_button( __( 'Save Changes', 'erocket' ) ); ?>
			</form>
		</div>
		<?php
	}

	public function save() {
		if ( empty( $_POST['submit'] ) || ! check_ajax_referer( 'save', false, false ) ) {
			return;
		}
		$share_text = isset( $_POST['share_text'] ) ? sanitize_text_field( wp_unslash( $_POST['share_text'] ) ) : '';

		// Make sure selected services are in the predefined list.
		$sharing_services = isset( $_POST['sharing_services'] ) && is_array( $_POST['sharing_services'] ) ? wp_unslash( $_POST['sharing_services'] ) : [];
		$sharing_services = array_filter( $sharing_services, function( $service ) {
			return array_key_exists( $service, $this->services );
		} );

		// Validate position.
		$sharing_position = isset( $_POST['sharing_position'] ) && in_array( $_POST['sharing_position'], [ 'before', 'after', 'both' ], true ) ? sanitize_text_field( wp_unslash( $_POST['sharing_position'] ) ) : 'after';

		// Make sure post types valid and exist.
		$sharing_types = isset( $_POST['sharing_types'] ) && is_array( $_POST['sharing_types'] ) ? wp_unslash( $_POST['sharing_types'] ) : [];
		$sharing_types = array_filter( $sharing_types, function( $post_type ) {
			return post_type_exists( $post_type );
		} );

		update_option( 'erocket', compact( 'share_text', 'sharing_services', 'sharing_position', 'sharing_types' ) );
	}

	public function output( $content ) {
		if ( ! $this->is_enabled() ) {
			return $content;
		}

		$html     = $this->get_html();
		$option   = get_option( 'erocket' );
		$position = $option['sharing_position'] ?? 'after';

		if ( 'before' === $position ) {
			$content = $html . $content;
		} elseif ( 'after' === $position ) {
			$content .= $html;
		} elseif ( 'both' === $position ) {
			$content = $html . $content . $html;
		}

		return $content;
	}

	public function output_html() {
		$html = $this->get_html();
		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	private function get_html() {
		$option     = get_option( 'erocket' );
		$services   = $option['sharing_services'] ?? array_keys( $this->services );
		$services   = array_intersect( $services, array_keys( $this->services ) );
		$share_text = $option['share_text'] ?? '';

		if ( empty( $services ) ) {
			return '';
		}

		$html = $share_text ? '<span>' . $share_text . ':</span>' : '';

		$blocks = '<!-- wp:social-links --><ul class="wp-block-social-links">';
		foreach ( $services as $key ) {
			$service    = $this->services[ $key ];
			$attributes = [
				'service' => $key,
				'url'     => sprintf( $service['url'], get_permalink() ),
				'label'   => $service['title'],
			];
			$blocks    .= '<!-- wp:social-link ' . wp_json_encode( $attributes ) . ' /-->';
		}
		$blocks .= '</ul><!-- /wp:social-links -->';

		$html .= do_blocks( $blocks );

		return "<div class='es-buttons'>$html</div>";
	}

	private function is_enabled() {
		$option   = get_option( 'erocket' );
		$types    = $option['sharing_types'] ?? [ 'post' ];
		$services = $option['sharing_services'] ?? array_keys( $this->services );
		$services = array_intersect( $services, array_keys( $this->services ) );

		return apply_filters( 'erocket_enable_sharing', is_singular() && in_array( get_post_type(), $types ) && ! empty( $services ) );
	}
}
