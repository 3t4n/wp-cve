<?php
namespace CatFolders\Blocks;

use CatFolders\Traits\Singleton;

class GalleryBlock {
	use Singleton;

	private $script_handle = 'catfolders-image-gallery';

	public function __construct() {
		add_action( 'init', array( $this, 'create_block_catfolders_block_init' ) );
	}

	public function create_block_catfolders_block_init() {
		register_block_type( __DIR__ . '/build', array( 'render_callback' => array( $this, 'render_block' ) ) );
	}

	public function render_block( $attributes ) {
		if ( empty( $attributes['folders'] ) ) {
			return '';
		}

		wp_enqueue_style( $this->script_handle, CATF_PLUGIN_URL . 'includes/Blocks/build/style-index.css', array(), CATF_VERSION );

		return $this->generate_html( $attributes );
	}

	public function get_attachments( $args ) {
		$selectedFolders = isset( $args['folders'] ) ? array_map( 'intval', $args['folders'] ) : array();
		if ( ! $selectedFolders ) {
			return false;
		}

		global $wpdb;
		$ids         = $selectedFolders;
		$where_arr[] = '`folder_id` IN (' . implode( ',', $ids ) . ')';
		$in_not_in   = $wpdb->get_col( "SELECT `post_id` FROM {$wpdb->prefix}catfolders_posts" . ' WHERE ' . implode( ' AND ', $where_arr ) );
		if ( ! $in_not_in ) {
			return false;
		}

		$queryArgs = array(
			'post_type'      => 'attachment',
			'post__in'       => $in_not_in,
			'posts_per_page' => -1,
			'orderby'        => array(
				'ID' => 'ASC',
			),
			'post_status'    => 'inherit',
		);

		$query = new \WP_Query( $queryArgs );

		$posts = $query->get_posts();

		if ( count( $posts ) < 1 ) {
			return '';
		}

		$attachments_data = array();
		foreach ( $posts as $post ) {

			if ( ! wp_attachment_is_image( $post ) ) {
				continue;
			}

			$imageSrc               = wp_get_attachment_image_src( $post->ID, 'full' );
			$imageSrc               = $imageSrc[0];
			$attachment_data['src'] = $imageSrc;

			$imageAlt               = get_post_meta( $post->ID, '_wp_attachment_image_alt', true );
			$imageAlt               = empty( $imageAlt ) ? $post->post_title : $imageAlt;
			$attachment_data['alt'] = $imageAlt;

			$imageCaption               = wp_get_attachment_caption( $post->ID );
			$attachment_data['caption'] = $imageCaption;

			$attachments_data[] = $attachment_data;

		}

		return $attachments_data;
	}

	public function generate_html( $attributes ) {
		$attachments = $this->get_attachments( $attributes );
		$html        = '';
		if ( $attachments && '' !== $attachments ) {
			$html    .= '<div class="wp-block-catfolders-block-catfolders-gallery catf-wp-block-gallery">';
			$ulClass  = 'catf-blocks-gallery-grid';
			$ulClass .= ! empty( $attributes['className'] ) ? ' ' . esc_attr( $attributes['className'] ) : '';
			$ulClass .= 'masonry' == $attributes['layout'] ? ' is-style-masonry' : '';
			$ulClass .= ' catf-columns-' . esc_attr( $attributes['columns'] );

			$html .= '<ul class="' . esc_attr( $ulClass ) . '">';
			foreach ( $attachments as $attachment ) {
				$img     = '<img src="' . esc_attr( $attachment['src'] ) . '" alt="' . esc_attr( $attachment['alt'] ) . '" >';
				$caption = $attachment['caption'] ? '<figcaption class="wp-block-image-caption">' . esc_html( $attachment['caption'] ) . '</figcaption>' : '';
				$li      = '<li class="catf-blocks-gallery-item wp-block-image">';
				$li     .= '<figure>';
				$li     .= $img;
				$li     .= $caption;

				$li .= '</figure>';
				$li .= '</li>';

				$html .= $li;
			}
			$html .= '</ul>';
			$html .= '</div>';
		}
		return $html;
	}
}


