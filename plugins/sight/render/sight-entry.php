<?php
/**
 * The Class Portfolio Entry
 *
 * @link       https://codesupply.co
 * @since      1.0.0
 *
 * @package    Sight
 */

if ( ! class_exists( 'Sight_Entry' ) ) {
	/**
	 * Create Class Portfolio Entry
	 */
	class Sight_Entry {
		/**
		 * The attributes.
		 *
		 * @var string $attributes The attributes.
		 */
		public $attributes = null;

		/**
		 * The options.
		 *
		 * @var string $options The options.
		 */
		public $options = null;

		/**
		 * The source.
		 *
		 * @var string $source The source.
		 */
		public $source = null;

		/**
		 * The object_id.
		 *
		 * @var string $object_id The object_id.
		 */
		public $object_id = null;

		/**
		 * The object_link.
		 *
		 * @var string $object_link The object_link.
		 */
		public $object_link = null;

		/**
		 * The attachment_id.
		 *
		 * @var string $attachment_id The attachment_id.
		 */
		public $attachment_id = null;

		/**
		 * The attachment_lightbox.
		 *
		 * @var string $attachment_lightbox The attachment_lightbox.
		 */
		public $attachment_lightbox = null;

		/**
		 * The attachment_lightbox_icon.
		 *
		 * @var string $attachment_lightbox_icon The attachment_lightbox_icon.
		 */
		public $attachment_lightbox_icon = null;

		/**
		 * The attachment_link.
		 *
		 * @var string $attachment_link The attachment_link.
		 */
		public $attachment_link = null;

		/**
		 * The attachment_link_to.
		 *
		 * @var string $attachment_link_to The attachment_link_to.
		 */
		public $attachment_link_to = null;

		/**
		 * The attachment_full_link.
		 *
		 * @var string $attachment_full_link The attachment_full_link.
		 */
		public $attachment_full_link = null;

		/**
		 * The attachment_view_more.
		 *
		 * @var string $attachment_view_more The attachment_view_more.
		 */
		public $attachment_view_more = null;

		/**
		 * The attachment_view_more_label.
		 *
		 * @var string $attachment_view_more_label The attachment_view_more_label.
		 */
		public $attachment_view_more_label = null;

		/**
		 * The attachment_html.
		 *
		 * @var string $attachment_html The attachment_html.
		 */
		public $attachment_html = null;

		/**
		 * The attachment_title.
		 *
		 * @var string $attachment_title The attachment_title.
		 */
		public $attachment_title = null;

		/**
		 * The attachment_title_tag.
		 *
		 * @var string $attachment_title_tag The attachment_title_tag.
		 */
		public $attachment_title_tag = 'h3';

		/**
		 * The attachment_meta.
		 *
		 * @var string $attachment_meta The attachment_meta.
		 */
		public $attachment_meta = null;

		/**
		 * The attachment_caption.
		 *
		 * @var string $attachment_caption The attachment_caption.
		 */
		public $attachment_caption = null;

		/**
		 * The attachment_orientation.
		 *
		 * @var string $attachment_orientation The attachment_orientation.
		 */
		public $attachment_orientation = null;

		/**
		 * __construct
		 *
		 * This function will initialize the initialize
		 *
		 * @param array $attributes The attributes of block.
		 * @param array $options    The options of block.
		 */
		public function __construct( $attributes, $options ) {
			$this->attributes = $attributes;
			$this->options    = $options;

			// Set source of entry.
			$this->source = $attributes['source'];
		}

		/**
		 * Init Entry
		 */
		public function init() {
			$this->set_object_id();
			$this->set_object_link();
			$this->set_attachment_id();
			$this->set_attachment_lightbox();
			$this->set_attachment_lightbox_icon();
			$this->set_attachment_link();
			$this->set_attachment_link_to();
			$this->set_attachment_full_link();
			$this->set_attachment_view_more();
			$this->set_attachment_view_more_label();
			$this->set_attachment_html();
			$this->set_attachment_title();
			$this->set_attachment_title_tag();
			$this->set_attachment_meta();
			$this->set_attachment_caption();
			$this->set_attachment_orientation();
		}

		/**
		 * Set Object Id
		 */
		public function set_object_id() {
			if ( 'projects' === $this->source ) {
				$this->object_id = get_the_ID();
			}
			if ( 'categories' === $this->source ) {
				$this->object_id = intval( $this->object_id );
			}
			if ( 'post' === $this->source ) {
				$this->object_id = intval( $this->attachment_id );
			}
			if ( 'custom' === $this->source ) {
				$this->object_id = intval( $this->attachment_id );
			}
		}

		/**
		 * Set Object Link
		 */
		public function set_object_link() {
			if ( 'projects' === $this->source ) {
				$this->object_link = get_permalink( get_the_ID() );
			}
			if ( 'categories' === $this->source ) {
				$this->object_link = get_term_link( $this->object_id );

				if ( empty( sight_is_archive() ) ) {
					$this->object_link = null;
				}
			}
			if ( 'post' === $this->source ) {
				$this->object_link = get_attachment_link( $this->attachment_id );
			}
			if ( 'custom' === $this->source ) {
				$this->object_link = get_attachment_link( $this->attachment_id );
			}
		}

		/**
		 * Set Attachment Id
		 */
		public function set_attachment_id() {
			if ( 'projects' === $this->source ) {
				$this->attachment_id = get_post_thumbnail_id();
			}
		}

		/**
		 * Set Attachment Lightbox
		 */
		public function set_attachment_lightbox() {
			if ( ! isset( $this->attributes['attachment_lightbox'] ) || ! $this->attributes['attachment_lightbox'] ) {
				return;
			}

			$this->attachment_lightbox = true;
		}
		/**
		 * Set Attachment Lightbox Icon
		 */
		public function set_attachment_lightbox_icon() {
			if ( ! isset( $this->attributes['attachment_lightbox'] ) || ! $this->attributes['attachment_lightbox'] ) {
				return;
			}
			if ( ! isset( $this->attributes['attachment_lightbox_icon'] ) || ! $this->attributes['attachment_lightbox_icon'] ) {
				return;
			}

			$this->attachment_lightbox_icon = true;
		}

		/**
		 * Set Attachment Link
		 */
		public function set_attachment_link() {
			$this->attachment_link = wp_get_attachment_image_url( $this->attachment_id, $this->attributes['attachment_size'] );
		}

		/**
		 * Set Attachment Full Link
		 */
		public function set_attachment_full_link() {
			$this->attachment_full_link = wp_get_attachment_image_url( $this->attachment_id, 'full' );
		}

		/**
		 * Set Attachment Html
		 */
		public function set_attachment_html() {
			$attrs = apply_filters( 'sight_portfolio_attachment_attrs', array(), $this->attributes );

			$this->attachment_html = wp_get_attachment_image( $this->attachment_id, $this->attributes['attachment_size'], false, $attrs );
		}

		/**
		 * Set Attachment Link To
		 */
		public function set_attachment_link_to() {
			if ( isset( $this->attributes['attachment_link_to'] ) && $this->attributes['attachment_link_to'] ) {
				$this->attachment_link_to = $this->attributes['attachment_link_to'];
			}
		}

		/**
		 * Set Attachment View More
		 */
		public function set_attachment_view_more() {
			if ( isset( $this->attributes['attachment_view_more'] ) && $this->attributes['attachment_view_more'] ) {
				$this->attachment_view_more = $this->attributes['attachment_view_more'];
			}
		}

		/**
		 * Set Attachment View More Label
		 */
		public function set_attachment_view_more_label() {
			switch ( $this->attachment_link_to ) {
				case 'media':
					$this->attachment_view_more_label = apply_filters( 'sight_portfolio_view_more', esc_html__( 'View Media', 'sight' ), $this );
					break;
				case 'page':
					$this->attachment_view_more_label = apply_filters( 'sight_portfolio_view_more', esc_html__( 'View Project', 'sight' ), $this );
					break;
			}
		}

		/**
		 * Set Attachment Title
		 */
		public function set_attachment_title() {
			if ( ! isset( $this->attributes['meta_title'] ) || ! $this->attributes['meta_title'] ) {
				return;
			}

			if ( 'projects' === $this->source ) {
				$this->attachment_title = get_the_title( $this->object_id );
			}
			if ( 'categories' === $this->source ) {
				$this->attachment_title = get_term( $this->object_id, 'sight-categories' )->name;
			}
		}
		/**
		 * Set Attachment Title Tag
		 */
		public function set_attachment_title_tag() {
			if ( ! isset( $this->attributes['meta_title'] ) || ! $this->attributes['meta_title'] ) {
				return;
			}

			if ( isset( $this->attributes['typography_heading_tag'] ) && $this->attributes['typography_heading_tag'] ) {
				$this->attachment_title_tag = $this->attributes['typography_heading_tag'];
			}
		}

		/**
		 * Set Attachment Meta
		 */
		public function set_attachment_meta() {
			ob_start();

			// Meta Category.
			if ( isset( $this->attributes['meta_category'] ) && $this->attributes['meta_category'] ) {

				if ( 'projects' === $this->source ) {

					$terms_list = array();

					$terms = get_the_terms( $this->object_id, 'sight-categories' );

					foreach ( $terms as $term ) {
						if ( sight_is_archive() ) {
							$link = get_term_link( $term );

							if ( ! is_wp_error( $link ) ) {
								$terms_list[] = '<a class="sight-portfolio-meta-item" href="' . esc_url( $link ) . '" rel="tag">' . $term->name . '</a>';
							}
						} else {
							$terms_list[] = '<span class="sight-portfolio-meta-item">' . $term->name . '</span>';
						}
					}

					if ( $terms_list ) {
						?>
						<div class="sight-portfolio-meta-category">
							<?php call_user_func( 'printf', '%s', join( '', $terms_list ) ); ?>
						</div>
						<?php
					}
				}
			}

			// Meta Date.
			if ( isset( $this->attributes['meta_date'] ) && $this->attributes['meta_date'] ) {
				?>
				<div class="sight-portfolio-meta-date">
					<?php call_user_func( 'printf', '%s', get_the_date( '', $this->object_id ) ); ?>
				</div>
				<?php
			}

			$this->attachment_meta = ob_get_clean();
		}

		/**
		 * Set Attachment Caption
		 */
		public function set_attachment_caption() {
			if ( ! isset( $this->attributes['meta_caption'] ) || ! $this->attributes['meta_caption'] ) {
				return;
			}

			if ( 'projects' === $this->source ) {
				$this->attachment_caption = sight_get_the_excerpt( $this->object_id, $this->attributes['meta_caption_length'] );
			}
			if ( 'categories' === $this->source ) {
				$this->attachment_caption = sight_str_truncate( term_description( $this->object_id ), $this->attributes['meta_caption_length'] );
			}
			if ( 'custom' === $this->source ) {
				$this->attachment_caption = sight_str_truncate( wp_get_attachment_caption( $this->attachment_id ), $this->attributes['meta_caption_length'] );
			}
			if ( 'post' === $this->source ) {
				$this->attachment_caption = sight_str_truncate( wp_get_attachment_caption( $this->attachment_id ), $this->attributes['meta_caption_length'] );
			}
		}

		/**
		 * Set Attachment Orientation
		 */
		public function set_attachment_orientation() {
			if ( isset( $this->attributes['attachment_orientation'] ) && $this->attributes['attachment_orientation'] ) {
				$this->attachment_orientation = sprintf( 'sight-portfolio-overlay-ratio sight-portfolio-ratio-%s', $this->attributes['attachment_orientation'] );
			}
		}

		/**
		 * Has Item Content
		 */
		public function has_item_content() {
			$has_item_content = false;

			if ( $this->attachment_title ) {
				$has_item_content = true;
			}
			if ( $this->attachment_meta ) {
				$has_item_content = true;
			}
			if ( $this->attachment_caption ) {
				$has_item_content = true;
			}

			return apply_filters( 'sight_entry_has_item_content', $has_item_content, $this );
		}

		/**
		 * Compile Item Class
		 *
		 * @param string $class The class of entry.
		 */
		public function item_class( $class = array() ) {
			$classes = array();

			if ( $class ) {
				if ( ! is_array( $class ) ) {
					$class = preg_split( '#\s+#', $class );
				}
				$classes = array_map( 'esc_attr', $class );
			} else {
				// Ensure that we always coerce class to being an array.
				$class = array();
			}

			// Set individual class.
			$classes[] = 'sight-portfolio-entry sight-portfolio-video-wrap';

			// Set entry source.
			if ( $this->source ) {
				$classes[] = 'sight-portfolio-entry-' . $this->source;
			}

			// Set entry object id.
			if ( $this->object_id ) {
				$classes[] = 'sight-portfolio-entry-' . $this->object_id;
			}

			// Set entry link to.
			if ( $this->attachment_link_to ) {
				$classes[] = 'sight-portfolio-entry-link-' . $this->attachment_link_to;
			}

			// Set entry request class.
			if ( 'standard' === $this->attributes['layout'] ) {

				if ( isset( $_REQUEST['action'] ) && 'sight_portfolio_ajax_load_more' === $_REQUEST['action'] ) {
					$classes[] = 'sight-portfolio-entry-request';
				}
			}

			// For projects.
			if ( 'projects' === $this->source ) {
				$post = get_post( get_the_ID() );

				$classes[] = 'type-' . $post->post_type;
				$classes[] = 'status-' . $post->post_status;

				// Post thumbnails.
				if ( current_theme_supports( 'post-thumbnails' ) && has_post_thumbnail( $post->ID ) && ! is_attachment( $post ) ) {
					$classes[] = 'has-post-thumbnail';
				}
			}

			call_user_func( 'printf', 'class="%s"', join( ' ', $classes ) );
		}

		/**
		 * Compile Item Outer Class
		 *
		 * @param string $class The class of entry.
		 */
		public function item_outer_class( $class = array() ) {
			$classes = array();

			if ( $class ) {
				if ( ! is_array( $class ) ) {
					$class = preg_split( '#\s+#', $class );
				}
				$classes = array_map( 'esc_attr', $class );
			} else {
				// Ensure that we always coerce class to being an array.
				$class = array();
			}

			// Set individual class.
			$classes[] = 'sight-portfolio-entry-outer';

			call_user_func( 'printf', 'class="%s"', join( ' ', $classes ) );
		}

		/**
		 * Compile Item Attachment
		 */
		public function item_attachment() {
			if ( ! $this->attachment_html ) {
				return;
			}

			switch ( $this->attachment_link_to ) {
				case 'media':
					$attachment_link = $this->attachment_full_link;
					break;
				case 'page':
					$attachment_link = $this->object_link;
					break;
			}

			if ( $this->attachment_lightbox ) {
				$attachment_link = $this->attachment_full_link;
			}
			?>
			<div class="sight-portfolio-entry__inner sight-portfolio-entry__thumbnail sight-portfolio-entry__overlay <?php echo esc_attr( $this->attachment_orientation ); ?>">
				<figure class="sight-portfolio-overlay-background">
					<?php do_action( 'sight_entry_item_attachment_before', $this ); ?>

					<?php call_user_func( 'printf', '%s', $this->attachment_html ); ?>

					<?php do_action( 'sight_entry_item_attachment_after', $this ); ?>

					<?php
					if ( $this->attachment_view_more ) {
						?>
						<span class="sight-portfolio-view-more">
							<span class="sight-portfolio-view-more-label">
								<?php echo esc_html( $this->attachment_view_more_label ); ?>
							</span>
						</span>
						<?php
					}
					?>

					<?php
					if ( isset( $this->attributes['video'] ) && $this->attributes['video'] && 'none' !== $this->attributes['video'] ) {
						sight_get_video_background( $this->attributes['video'], null, null, 'default', (bool) $this->attributes['video_controls'] );
					}
					?>

					<?php
					if ( isset( $attachment_link ) ) {
						?>
						<a class="sight-portfolio-overlay-link" href="<?php echo esc_url( $attachment_link ); ?>"></a>
						<?php
					}
					?>

					<?php
					if ( $this->attachment_lightbox_icon ) {
						?>
						<span class="sight-zoom-icon-popup"></span>
						<?php
					}
					?>
				</figure>

			</div>
			<?php
		}

		/**
		 * Compile Item Content
		 */
		public function item_content() {
			if ( $this->has_item_content() ) {
				?>
				<div class="sight-portfolio-entry__inner sight-portfolio-entry__content">
					<?php do_action( 'sight_entry_item_content_before', $this ); ?>

					<?php if ( $this->attachment_title ) { ?>
						<div class="sight-portfolio-entry__title">
							<?php if ( $this->object_link ) { ?>
								<<?php echo esc_html( $this->attachment_title_tag ); ?> class="sight-portfolio-entry__heading">
									<a href="<?php echo esc_url( $this->object_link ); ?>">
										<?php echo wp_kses( $this->attachment_title, 'post' ); ?>
									</a>
								</<?php echo esc_html( $this->attachment_title_tag ); ?>>
							<?php } else { ?>
								<<?php echo esc_html( $this->attachment_title_tag ); ?> class="sight-portfolio-entry__heading">
									<?php echo wp_kses( $this->attachment_title, 'post' ); ?>
								</<?php echo esc_html( $this->attachment_title_tag ); ?>>
							<?php } ?>
						</div>
					<?php } ?>

					<?php if ( $this->attachment_meta ) { ?>
						<div class="sight-portfolio-entry__meta">
							<?php echo wp_kses( $this->attachment_meta, 'post' ); ?>
						</div>
					<?php } ?>

					<?php if ( $this->attachment_caption ) { ?>
						<div class="sight-portfolio-entry__caption">
							<?php echo wp_kses( $this->attachment_caption, 'post' ); ?>
						</div>
					<?php } ?>

					<?php do_action( 'sight_entry_item_content_after', $this ); ?>
				</div>
				<?php
			}
		}
	}
}
