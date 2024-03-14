<?php
/**
 * The Ebook Meta Box Class.
 *
 * @package    Rock_Convert\Inc\Admin
 * @link       https://rockcontent.com
 * @since      1.0.0
 *
 * @author     Rock Content
 */

namespace Rock_Convert\Inc\Admin\Ebook;

use Rock_Convert\Inc\Admin\Utils;

/**
 * Class Meta_Box
 *
 * @package Rock_Convert\Inc\Ebook
 */
class Meta_Box {

	/**
	 * Constructor.
	 */
	public function __construct() {
		if ( is_admin() ) {
			add_action( 'load-post.php', array( $this, 'init_metabox' ) );
			add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
		}
	}

	/**
	 * Initialize custom fields.
	 *
	 * @return void
	 */
	public function init_metabox() {
		add_action( 'add_meta_boxes', array( $this, 'add_metabox' ) );
		add_action( 'save_post', array( $this, 'save_metabox' ), 10, 2 );
	}

	/**
	 * Add metabox to ebook cpt.
	 *
	 * @return void
	 */
	public function add_metabox() {
		add_meta_box(
			'rock-convert-ebook-meta',
			__( 'Captura de leads', 'rock-convert' ),
			array( $this, 'render_metabox' ),
			'post',
			'side',
			'high'
		);
	}

	/**
	 * Render metabox to ebbok cpt.
	 *
	 * @param mixed $post A entire post object.
	 *
	 * @return void
	 */
	public function render_metabox( $post ) {
		// Add an nonce field so we can check for it later.
		wp_nonce_field(
			'rock_convert_inner_ebook_box',
			'rock_convert_inner_ebook_box_nonce'
		);

		$enable_ebook = get_post_meta(
			$post->ID,
			'_rock_convert_enable_ebook',
			true
		);
		?>
		<div class="rock-convert-admin-download-meta">
			<input type="checkbox" name="rock_convert_enable_ebook"
				id="rock_convert_enable_ebook"
				value="true"
			<?php echo '1' === $enable_ebook ? 'checked' : ''; ?>
				/>
			<label for="rock_convert_enable_ebook">
				<strong>
				<?php
					esc_html_e(
						'Disponibilizar post para download',
						'rock-convert'
					);
				?>
				</strong>
			</label>
			<br><br>
			<em>
				<?php
				esc_html_e(
					'Permitir o download do post no formato PDF pelos leitores do site.',
					'rock-convert'
				);
				?>
			</em>

				<?php if ( '1' === $enable_ebook ) { ?>
				<br><br>

				<label for="shortcode">
					<strong><?php esc_html_e( 'Código do formulário', 'rock-convert' ); ?></strong><br>
					<input type="text" id="shortcode" readonly
						value='[rock-convert-pdf id="<?php echo esc_attr( $post->ID ); ?>"]'
						style="background-color: #EEE;margin-top: 3px; margin-bottom: 5px;"
						size="30">
					<em>
						<small>
							<?php
								esc_html_e(
									'Copie e cole o código acima em qualquer lugar do texto',
									'rock-convert'
								);
							?>
						</small>
					</em>
					<br>
				</label>
			<?php } ?>
		</div>
			<?php $this->preview_button( $post->ID ); ?>
			<?php
	}

	/**
	 * Buttom Preview
	 *
	 * @param int $post_id ID from post.
	 * @return void
	 */
	public function preview_button( $post_id ) {
		$attatchment_path = $this->get_attatchment_path( $post_id );

		if ( ! empty( $attatchment_path ) ) {
			?>
			<div id="major-publishing-actions">
				<a href="<?php echo esc_url( $attatchment_path ); ?>"
				target="_blank"
				class="preview button">
					<em class="mce-ico mce-i-link rock-convert-download-pdf-button-ico"></em>
				<?php esc_html_e( 'Visualizar arquivo PDF', 'rock-convert' ); ?>
				</a>
				<div class="clear"></div>
			</div>
			<?php
		}
	}

	/**
	 * Get attatchment path
	 *
	 * @param int $post_id ID from post.
	 *
	 * @return false|string
	 * @since 2.0.0
	 */
	public function get_attatchment_path( $post_id ) {
		$attatchment_id = get_post_meta(
			$post_id,
			'_rock_convert_ebook_attatchment_id',
			true
		);

		return esc_url( wp_get_attachment_url( $attatchment_id ) );
	}

	/**
	 * Save the data
	 *
	 * @param int $post_id ID from post.
	 * @return void
	 */
	public function save_metabox( $post_id ) {

		$meta_nonce = isset( $_POST['rock_convert_inner_ebook_box_nonce'] ) ?
		sanitize_text_field( wp_unslash( $_POST['rock_convert_inner_ebook_box_nonce'] ) ) : null;

		if ( wp_verify_nonce( $meta_nonce, 'rock_convert_inner_ebook_box' ) ) {

			// Check if user has permissions to save data.
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}

			// Check if not an autosave.
			if ( wp_is_post_autosave( $post_id ) ) {
				return;
			}

			// Check if not a revision.
			if ( wp_is_post_revision( $post_id ) ) {
				return;
			}

			$sanitized_field = Utils::getArrayValue( $_POST, 'rock_convert_enable_ebook' );
			$enable_ebook    = filter_var( $sanitized_field, FILTER_VALIDATE_BOOLEAN );

			// Update the meta field.
			update_post_meta( $post_id, '_rock_convert_enable_ebook', $enable_ebook );
		}
	}

}
