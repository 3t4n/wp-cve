<?php
/**
 * The Custom Metabox class handle the custom fields of the plugin.
 *
 * @package    Rock_Convert\Inc\Admin
 * @link       https://rockcontent.com
 * @since      1.0.0
 *
 * @author     Rock Content
 */

namespace Rock_Convert\Inc\Admin\CTA;

use ParagonIE\Sodium\Core\Util;
use Rock_Convert\Inc\Admin\Admin;
use Rock_Convert\inc\admin\Utils;

/**
 * Class Custom_Meta_Box
 *
 * @package    Rock_Convert\Inc\Admin
 * @link       https://rockcontent.com
 * @since      1.0.0
 * @since      2.0.0
 *
 * @author     Rock Content
 */
class Custom_Meta_Box {


	/**
	 * Construct loads files by hooks
	 */
	public function __construct() {
		if ( is_admin() ) {
			add_action( 'load-post.php', array( $this, 'init_metabox' ) );
			add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
		}
	}

	/**
	 * Initiate metaboxes
	 *
	 * @return void
	 */
	public function init_metabox() {
		add_action( 'add_meta_boxes', array( $this, 'add_upload_metabox' ) );
		add_action( 'do_meta_boxes', array( $this, 'move_categories' ) );
		add_action( 'do_meta_boxes', array( $this, 'move_tags' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_metabox' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_shortcode_box' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_visibility_box' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_analytics_box' ) );
		add_action( 'save_post', array( $this, 'save_metabox' ), 10, 2 );
	}

	/**
	 * Include post categories in RC menu.
	 *
	 * @return void
	 */
	public function move_categories() {
		remove_meta_box( 'categorydiv', 'cta', 'side' );
		add_meta_box(
			'categorydiv',
			__( 'Categorias de exibição', 'rock-convert' ),
			'post_categories_meta_box',
			'cta',
			'side',
			'high'
		);
	}

	/**
	 * Include post tags in RC menu.
	 *
	 * @return void
	 */
	public function move_tags() {
		remove_meta_box( 'tagsdiv-post_tag', 'cta', 'side' );
		add_meta_box(
			'tagsdiv-post_tag',
			__( 'Tags de exibição <span class="rc-new-label">Novo</span>', 'rock-convert' ),
			'post_tags_meta_box',
			'cta',
			'side',
			'high'
		);
	}

	/**
	 * Add Visibility of CTAs.
	 *
	 * @return void
	 */
	public function add_visibility_box() {
		add_meta_box(
			'rock-convert-visibility',
			__( 'Visibilidade', 'rock-convert' ),
			array( $this, 'render_visibility_box' ),
			'cta',
			'normal',
			'low'
		);
	}

	/**
	 * Add settings of banners.
	 *
	 * @return void
	 */
	public function add_metabox() {
		add_meta_box(
			'rock-convert-meta',
			__( 'Configurações do Banner', 'rock-convert' ),
			array( $this, 'render_metabox' ),
			'cta',
			'normal',
			'low'
		);
	}

	/**
	 * Shortcode feature
	 *
	 * @return void
	 */
	public function add_shortcode_box() {
		global $post;

		if ( ! isset( $post ) ) {
			return;
		}

		if ( 'publish' !== $post->post_status ) {
			return;
		}

		add_meta_box(
			'rock-convert-shortcode',
			__( 'Utilização em qualquer lugar', 'rock-convert' ),
			array( $this, 'render_shortcode_box' ),
			'cta',
			'side',
			'high'
		);
	}

	/**
	 * Analytics feature
	 *
	 * @return void
	 */
	public function add_analytics_box() {
		global $post;

		if ( ! isset( $post ) ) {
			return;
		}

		if ( 'publish' !== $post->post_status ) {
			return;
		}

		add_meta_box(
			'rock-convert-analytics',
			__( 'Estatísticas', 'rock-convert' ),
			array( $this, 'render_analytics_box' ),
			'cta',
			'side',
			'high'
		);
	}

	/**
	 * Render analitycs screen.
	 *
	 * @param object $post Post object from WordPress.
	 * @return void
	 */
	public function render_analytics_box( $post ) {
		$analytics_enabled = Admin::analytics_enabled();
		$analytics         = Utils::get_post_analytics( $post->ID, $analytics_enabled );
		?>

		<div style="text-align: center; <?php echo esc_attr( $analytics_enabled ) ? null : 'filter: blur(6px)'; ?>">
			<span class="rconvert-analytics-numbers">
			<?php echo number_format( $analytics['views'], 0, ',', '.' ); ?>
			</span>
			<div class="rconvert-analytics-label">
				<img alt="Rock Convert Views"
				src="<?php echo esc_url( PLUGIN_NAME_URL . 'assets/admin/img/views.svg' ); ?>"
				class="rconvert-analytics-icon"/>
			<?php esc_html_e( 'Visualizações', 'rock-convert' ); ?>
			</div>

			<hr class="rconvert-analytics-divider">

			<span class="rconvert-analytics-numbers">
			<?php echo number_format( $analytics['clicks'], 0, ',', '.' ); ?>
			</span>
			<div class="rconvert-analytics-label">
				<img alt="Rock Convert Clicks"
				src="<?php echo esc_url( PLUGIN_NAME_URL . 'assets/admin/img/click.svg' ); ?>"
				class="rconvert-analytics-icon"/>
			<?php esc_html_e( 'Clicks', 'rock-convert' ); ?>
			</div>

			<hr class="rconvert-analytics-divider">

			<span class="rconvert-analytics-numbers">
			<?php echo esc_attr( round( $analytics['ctr'], 2 ) ); ?>
				%
			</span>
			<div class="rconvert-analytics-label">
				<img alt="Rock Convert Convertion"
				src="<?php echo esc_url( PLUGIN_NAME_URL . 'assets/admin/img/ctr.svg' ); ?>"
				class="rconvert-analytics-icon"/>
			<?php esc_html_e( 'Taxa de conversão', 'rock-convert' ); ?>
			</div>
		</div>
		<?php if ( ! $analytics_enabled ) { ?>
			<div class="analytics-warning" style="margin-top: 50px;">
				<p>
					<strong style="color: red">
						<?php esc_html_e( 'Atenção:', 'rock-convert' ); ?>
					</strong>
					<?php esc_html_e( 'A funcionalidade analytics não está habilitada.', 'rock-convert' ); ?><br/><br>
					<?php esc_html_e( 'Para ver as estatísticas deste CTA', 'rock-convert' ); ?>
					<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=cta&page=rock-convert-settings&tab=general' ) ); ?>">
					<?php esc_html_e( 'habilite a funcionalidade Analytics', 'rock-convert' ); ?>
				</a>
				</p>
			</div>
			<?php
		}
	}

	/**
	 * Add Upload metabox
	 *
	 * @return void
	 */
	public function add_upload_metabox() {
		add_meta_box(
			'rock_convert_banner',
			__( 'Selecione a imagem do banner', 'rock-convert' ),
			array( $this, 'rock_convert_media_upload' ),
			'cta',
			'normal',
			'high'
		);
	}

	/**
	 * Render visibility screen.
	 *
	 * @param object $post Post object from WordPress.
	 * @return void
	 */
	public function render_visibility_box( $post ) {
		$visibility = get_post_meta(
			$post->ID,
			'_rock_convert_visibility',
			true
		);

		$urls = get_post_meta( $post->ID, '_rock_convert_excluded_urls', true );

		if ( empty( $visibility ) ) {
			$visibility = 'all';
		}

		?>
		<p></p>
		<p>
		<div>
			<input type="radio" class="rock-convert-visibility-control"
				id="rock_convert_visibility_all"
				name="rock_convert_visibility"
			<?php echo esc_attr( $visibility ) === 'all' ? 'checked' : ''; ?>
				value="all"/>
			<label for="rock_convert_visibility_all">
			<?php
				esc_html_e(
					'Exibir em todas as páginas das categorias selecionadas',
					'rock-convert'
				);
			?>
				<br/>
				<small style="padding-left: 24px;">
				<?php
				esc_html_e(
					'O banner será exibido em todos os posts que estejam nas categorias selecionadas ao lado.',
					'rock-convert'
				);
				?>
				</small>
			</label>

		</div>
		<br/>
		<div>
			<input type="radio" class="rock-convert-visibility-control"
				id="rock_convert_visibility_exclude"
				name="rock_convert_visibility"
				<?php echo esc_attr( $visibility ) === 'exclude' ? 'checked' : ''; ?>
				value="exclude"/>
			<label for="rock_convert_visibility_exclude">
				<?php esc_html_e( 'Exibir em todas exceto:', 'rock-convert' ); ?> <br/>
				<small style="padding-left: 24px;">
				<?php
				esc_html_e(
					'Exibe em todos os posts que estejam nas categorias selecionadas ao lado,
                         com exceção das páginas cadastradas abaixo.',
					'rock-convert'
				);
				?>
				</small>
			</label>
		</div>
		<div class="rock-convert-exclude-control"
		style="<?php echo esc_attr( $visibility ) === 'all' ? 'display: none' : null; ?>">
			<div style="padding-top: 20px; clear: both;"
				class="rock-convert-exclude-pages">
				<?php if ( ! empty( $urls ) ) { ?>
					<?php foreach ( $urls as $url ) { ?>
						<div style="display: flex;"
							class="rock-convert-exclude-pages-link">
							<input type="text"
								name="rock_convert_exclude_pages[]"
								style="width: 65%;margin-right: 10px;"
								value="<?php echo esc_url( $url ); ?>"
								placeholder="<?php echo esc_html( 'Exemplo', 'rock-convert' ) . ': '
								 . esc_url( get_bloginfo( 'url' ) . '/meu-post' ); ?>">
							<input type="button"
								class="preview button rock-convert-exclude-pages-remove"
								value="x">
						</div>
					<?php } ?>

				<?php } else { ?>
					<div style="display: flex;"
						class="rock-convert-exclude-pages-link">
						<input type="text" name="rock_convert_exclude_pages[]"
							style="width: 95%;margin-right: 10px;"
							placeholder="<?php echo esc_html( 'Exemplo', 'rock-convert' ) . ': '
							 . esc_url( get_bloginfo( 'url' ) . '/meu-post' ); ?>">
						<input type="button"
							class="preview button rock-convert-exclude-pages-remove"
							value="x">
					</div>
				<?php } ?>
			</div>
			<input type="button"
				class="preview button rock-convert-exclude-pages-add"
				style="float: left;margin-top: 10px;"
				value="+ <?php esc_html_e( 'Adicionar página', 'rock-convert' ); ?>">
			<div class="clear"></div>
			<br><br>
		</div>
		</p>
			<?php
	}

	/**
	 * Render shortcode box.
	 *
	 * @param object $post Post object from WordPress.
	 * @return void
	 */
	public function render_shortcode_box( $post ) {
		?>
		<p>
		<?php
			esc_html_e(
				'Copie e cole o código abaixo para exibir o banner em qualquer lugar do conteúdo.',
				'rock-convert'
			);
		?>
		</p>
		<p>
			<label for="shortcode">
				<strong><?php esc_html_e( 'Shortcode', 'rock-convert' ); ?></strong> <br>
				<input type="text" id="shortcode" readonly
					value='[rock-convert-cta id="<?php echo esc_attr( $post->ID ); ?>"]'
					style="background-color: #EEE;margin-top: 3px; margin-bottom: 5px; max-width:100%;"
					size="30">
				<br>
			</label>
		</p>
		<?php
	}

	/**
	 * Render metaboxes.
	 *
	 * @param object $post Post object from WordPress.
	 * @return void
	 */
	public function render_metabox( $post ) {
		// Add an nonce field so we can check for it later.
		wp_nonce_field(
			'rock_convert_inner_custom_box',
			'rock_convert_inner_custom_box_nonce'
		);

		$custom_fields = get_post_custom( $post->ID );

		$title    = Utils::getArrayValue( $custom_fields, '_rock_convert_title', 0 );
		$source   = Utils::getArrayValue( $custom_fields, '_rock_convert_utm_source', 0 );
		$position = Utils::getArrayValue( $custom_fields, '_rock_convert_position', 0 );

		/**
		 * Support for version 1.0 that medium field was editable
		 */
		$campaign = Utils::getArrayValue( $custom_fields, '_rock_convert_utm_campaign', 0 );
		$medium   = Utils::getArrayValue( $custom_fields, '_rock_convert_utm_medium', 0 );

		if ( empty( $campaign ) && ! empty( $medium ) ) {
			$campaign = $medium;
		}

		if ( empty( $position ) ) {
			$position = 'bottom';
		}

		// Display the form, using the current value.
		?>
		<p>
			<label for="rock_convert_title">
				<strong><?php esc_html_e( 'Link', 'rock-convert' ); ?></strong>
			</label><br>
			<input type="text" id="rock_convert_title" name="rock_convert_title"
				value="<?php echo esc_attr( $title ); ?>" size="56"/>
			<br>
			<em><strong><?php esc_html_e( 'Dica', 'rock-convert' ); ?>:</strong>
			<?php esc_html_e( 'Não utilize parâmetros do tipo UTM neste campo.', 'rock-convert' ); ?>
			</em>
		</p>

		<div class="rconvert_announcement_position_preview">

			<p>
				<label for="rock_convert_utm_source">
					<strong><?php esc_html_e( 'UTM Source', 'rock-convert' ); ?></strong>
				</label><br>
				<input type="text" id="rock_convert_utm_source"
					name="rock_convert_utm_source"
					value="<?php echo esc_attr( $source ); ?>" size="25"/><br>
				<em><?php esc_html_e( 'Ex: google, newsletter', 'rock-convert' ); ?></em>
			</p>
		</div>

		<div class="rconvert_announcement_position_preview">
			<p>
				<label for="rock_convert_utm_medium">
					<strong><?php esc_html_e( 'UTM Campaign', 'rock-convert' ); ?></strong>
				</label>
				<br>
				<input type="text" id="rock_convert_utm_campaign"
					name="rock_convert_utm_campaign"
					value="<?php echo esc_attr( $campaign ); ?>" size="25"/><br>
				<em><?php esc_html_e( 'Ex: ebook_de_natal', 'rock-convert' ); ?></em>
			</p>
		</div>
		<div class="clearfix" style="display: block;clear: both;"></div>
		<p>
			<label>
				<strong><?php esc_html_e( 'Posição do CTA', 'rock-convert' ); ?></strong>
			</label>
		</p>
		<div class="rconvert_announcement_position_preview">
			<label for="rock_convert_position_top">
				<img alt="Banner Top" src="<?php echo esc_url( PLUGIN_NAME_URL . 'assets/admin/img/banner-top.png' ); ?>"
					class="rconvert_announcement-preview-img"/>
				<input type="radio" id="rock_convert_position_top"
					name="rock_convert_position"
				<?php echo esc_attr( $position ) === 'top' ? 'checked' : ''; ?>
					value="top"/>
			<?php esc_html_e( 'Acima do conteúdo', 'rock-convert' ); ?></label>
		</div>
		<div class="rconvert_announcement_position_preview">
			<label for="rock_convert_position_middle">
				<img alt="Banner Middle" src="<?php echo esc_url( PLUGIN_NAME_URL . 'assets/admin/img/banner-middle.png' ); ?>"
					class="rconvert_announcement-preview-img"/>
				<input type="radio" id="rock_convert_position_middle"
					name="rock_convert_position"
				<?php echo esc_attr( $position ) === 'middle' ? 'checked' : ''; ?>
					value="middle"/>
			<?php esc_html_e( 'No meio do conteúdo', 'rock-convert' ); ?></label>
		</div>
		<div class="rconvert_announcement_position_preview">
			<label for="rock_convert_position_bottom">
				<img alt="Banner Bottom" src="<?php echo esc_url( PLUGIN_NAME_URL . 'assets/admin/img/banner-bottom.png' ); ?>"
					class="rconvert_announcement-preview-img"/>
				<input type="radio" id="rock_convert_position_bottom"
					name="rock_convert_position"
				<?php
					echo esc_attr( $position ) === 'bottom' ? 'checked' : '';
				?>
					value="bottom"/>
				<?php esc_html_e( 'Abaixo do conteúdo', 'rock-convert' ); ?></label>
		</div>
		<br>
		<div class="clearfix" style="display:block; clear:both;"></div>
			<?php
	}

	/**
	 * Save metaboxes.
	 *
	 * @param int $post_id ID from post.
	 * @return void|string
	 */
	public function save_metabox( $post_id ) {

		$meta_nonce = isset( $_POST['rock_convert_inner_custom_box_nonce'] ) ?
		sanitize_text_field( wp_unslash( $_POST['rock_convert_inner_custom_box_nonce'] ) ) : null;

		if ( wp_verify_nonce( $meta_nonce, 'rock_convert_inner_custom_box' ) ) {

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

			$title      = sanitize_text_field( Utils::getArrayValue( $_POST, 'rock_convert_title' ) );
			$source     = sanitize_text_field( Utils::getArrayValue( $_POST, 'rock_convert_utm_source' ) );
			$campaign   = sanitize_text_field( Utils::getArrayValue( $_POST, 'rock_convert_utm_campaign' ) );
			$position   = sanitize_text_field( Utils::getArrayValue( $_POST, 'rock_convert_position' ) );
			$visibility = sanitize_text_field( Utils::getArrayValue( $_POST, 'rock_convert_visibility' ) );

			$exclude_pages = isset( $_POST['rock_convert_exclude_pages'] ) ?
				$_POST['rock_convert_exclude_pages'] : null;
			$urls          = $exclude_pages ? Utils::sanitize_array( $exclude_pages ) : null;

			// Update the meta field.
			update_post_meta( $post_id, '_rock_convert_title', $title );
			update_post_meta( $post_id, '_rock_convert_utm_source', $source );
			update_post_meta( $post_id, '_rock_convert_utm_campaign', $campaign );
			update_post_meta( $post_id, '_rock_convert_position', $position );
			update_post_meta( $post_id, '_rock_convert_visibility', $visibility );

			if ( ! empty( $urls ) ) {
					update_post_meta( $post_id, '_rock_convert_excluded_urls', $urls );
			}

			$images =  isset( $_POST['rock-convert-media'] ) ? Utils::sanitize_array( $_POST['rock-convert-media'] ) : null;

			if( $images ){
			$image = array_map( 'intval', $images );
				foreach ( $image as $value ) {
					update_post_meta( $post_id, '_rock_convert_image_media', $value );
				}
			}
		}
	}

	/**
	 * Rock Convert Media Upload.
	 *
	 * @return void
	 */
	public function rock_convert_media_upload() {
		wp_enqueue_media();
		wp_enqueue_script(
			'meta-box-media',
			PLUGIN_NAME_URL . 'dist/admin.js',
			array( 'jquery' ),
			'1.0.0',
			true
		);
		wp_nonce_field( 'nonce_action', 'nonce_name' );

		$field_names = array( '_rock_convert_image_media' );
		foreach ( $field_names as $name ) {
			$value = esc_attr( get_post_meta( get_the_id(), esc_attr( $name ), true ) );
			?>
				<input type='hidden' id='<?php echo esc_attr( $name ); ?>-value'
						class='small-text'
						name='rock-convert-media[]'
						value='<?php echo esc_attr( $value ); ?>' />
				<input type='button' id='<?php echo esc_attr( $name ); ?>'
						class='button button-primary rock-convert-upload-button'
						value='<?php esc_html_e( 'Selecionar imagem', 'rock-convert' ); ?>'/>
				<input type='button' id='<?php echo esc_attr( $name ); ?>-remove'
						class='button rock-convert-upload-button-remove'
						value='<?php esc_html_e( 'Remover', 'rock-convert' ); ?>' />
				<?php $image_url = ! $value ? '' : wp_get_attachment_image_url( $value, 'full' ); ?>
				<div class='rock-convert-image-preview'>
					<?php if ( $image_url ) { ?>
					<img style="max-width:100%;" src="<?php echo esc_url( $image_url ); ?>" alt="Banner">
					<?php } ?>
				</div>
				<br />
			<?php
		}
	}
}
