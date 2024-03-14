<?php

namespace Vimeotheque\Widgets;

use Vimeotheque\Admin\Helper_Admin;
use Vimeotheque\Helper;
use Vimeotheque\Plugin;
use Vimeotheque\Shortcode\Playlist;
use WP_Post;
use WP_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Playlist_Widget
 *
 * @package Vimeotheque\Widgets
 * @ignore
 */
class Playlist_Widget extends WP_Widget {
	/**
	 * @var string
	 */
	public $post_type;
	/**
	 * @var string
	 */
	public $taxonomy;

	/**
	 * Constructor
	 */
	public function __construct() {
		/* Widget settings. */
		$widget_options = [
			'classname'   => 'cvm-latest-videos',
			'description' => __( 'The most recent videos on your site.',
				'codeflavors-vimeo-video-post-lite' )
		];

		/* Widget control settings. */
		$control_options = [
			'id_base' => 'cvm-latest-videos-widget'
		];

		/* Create the widget. */
		parent::__construct(
			'cvm-latest-videos-widget',
			__( 'Recent Vimeo videos', 'codeflavors-vimeo-video-post-lite' ),
			$widget_options,
			$control_options
		);
	}

	/**
	 * (non-PHPdoc)
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
	 * @param array $instance The settings for the particular instance of the widget.
	 *
	 * @see WP_Widget::widget()
	 */
	public function widget( $args, $instance ) {
		/**
		 * @var string $before_title
		 * @var string $after_title
		 * @var string $before_widget
		 * @var string $after_widget
		 */
		extract( $args );
		$instance = wp_parse_args( $instance, $this->get_defaults() );

		$widget_title = '';
		if ( isset( $instance['cvm_widget_title'] )
		     && ! empty( $instance['cvm_widget_title'] )
		) {
			$widget_title = $before_title . apply_filters( 'widget_title',
					$instance['cvm_widget_title'] ) . $after_title;
		}

		$posts = $this->get_posts( $instance );
		if ( ! $posts ) {
			return;
		}

		// if setting to display player is set, show it
		if ( isset( $instance['cvm_show_playlist'] )
		     && $instance['cvm_show_playlist']
		) {
			$playlist = new Playlist();
			$playlist->set_posts( $posts );
			$playlist_output = $playlist->get_output( $instance, '' );

			if ( ! $playlist_output ) {
				return;
			}

			echo $before_widget;
			echo $widget_title;
			echo $playlist_output;
			echo $after_widget;

			return;
		}


		echo $before_widget;

		if ( ! empty( $instance['cvm_widget_title'] ) ) {
			echo $before_title . apply_filters( 'widget_title', $instance['cvm_widget_title'] ) . $after_title;
		}
		?>
        <ul class="cvm-recent-videos-widget">
			<?php foreach ( $posts as $post ): ?>
				<?php
				if ( $instance['cvm_vim_image'] ) {
					$thumbnail = get_the_post_thumbnail( $post->ID,
						'thumbnail' );
					if ( ! $thumbnail ) {
						$video_data
							= Helper::get_video_post( $post->ID );
						if ( isset( $video_data->thumbnails[0] ) ) {
							$thumbnail = sprintf( '<img src="%s" alt="%s" />',
								$video_data->thumbnails[0],
								esc_attr( apply_filters( 'the_title',
									$post->post_title ) ) );
						}
					}
				} else {
					$thumbnail = '';
				}
				?>
                <li>
                    <a href="<?php echo get_permalink( $post->ID ); ?>" title="<?php esc_attr_e( apply_filters( 'the_title', $post->post_title ) ); ?>">
                        <?php echo $thumbnail; ?><br/>
                        <?php echo apply_filters( 'post_title', $post->post_title ); ?>
                    </a>
                </li>
			<?php endforeach; ?>
        </ul>
		<?php
		echo $after_widget;
	}

	/**
	 * Default widget values
	 */
	private function get_defaults() {
		$player_defaults = Plugin::instance()->get_embed_options_obj()
		                         ->get_options();

		$defaults = [
			'cvm_post_type'     => Plugin::instance()->get_cpt()->get_post_type(),
			'cvm_taxonomy'      => Plugin::instance()->get_cpt()->get_post_tax(),
			'cvm_widget_title'  => '',
			'cvm_posts_number'  => 5,
			'cvm_posts_tax'     => - 1,
			'cvm_vim_image'     => false,
			'cvm_show_playlist' => false,
			'theme'             => 'default',
			'layout'            => '',
			'show_excerpts'     => false,
			'playlist_loop'     => 0,
			'aspect_ratio'      => $player_defaults['aspect_ratio'],
			'width'             => $player_defaults['width'],
			'volume'            => $player_defaults['volume'],
			'title'             => $player_defaults['title'],
			'byline'            => $player_defaults['byline'],
			'portrait'          => $player_defaults['portrait']
		];

		/**
		 * Allow additional option setup.
         *
         * @param array $options    Array of options.
		 */
		$optional = apply_filters(
            'vimeotheque\classic-widget\playlist-widget-extra_options',
            []
        );

		return array_merge( $optional, $defaults );
	}

	/**
	 * @param $params
	 *
	 * @return WP_Post[]
	 */
	private function get_posts( $params ) {
		$posts_count = absint( $params['cvm_posts_number'] );
		$post_type   = isset( $params['cvm_post_type'] )
			? $params['cvm_post_type']
			: Plugin::instance()->get_cpt()->get_post_type();

		$args = [
			'numberposts'      => $posts_count,
			'posts_per_page'   => $posts_count,
			'orderby'          => 'post_date',
			'order'            => 'DESC',
			'post_type'        => $post_type,
			'post_status'      => 'publish',
			'suppress_filters' => true
		];

		if ( $post_type != Plugin::instance()->get_cpt()->get_post_type() ) {
			$args['meta_query'] = [
				[
					'key'     => Plugin::instance()->get_cpt()
					                   ->get_post_settings()
					                   ->get_meta_video_data(),
					'compare' => 'EXISTS'
				]
			];
		}

		$taxonomy_select = isset( $params['cvm_posts_tax'] )
		                   && - 1 !== $params['cvm_posts_tax']
			? absint( $params['cvm_posts_tax'] ) : false;
		if ( $taxonomy_select ) {
			$taxonomy = isset( $params['cvm_taxonomy'] )
				? $params['cvm_taxonomy']
				: Plugin::instance()->get_cpt()->get_post_tax();
			$term     = get_term( $taxonomy_select, $taxonomy, ARRAY_A );
			if ( ! is_wp_error( $term ) ) {
				$args['tax_query'] = [
					[
						'taxonomy' => $taxonomy,
						'field'    => 'slug',
						'terms'    => $term['slug']
					]
				];
			}
		}

		return get_posts( $args );
	}

	/**
	 * (non-PHPdoc)
	 *
	 * @param array $new_instance New settings for this instance as input by the user via WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 *
	 * @return array
	 * @see WP_Widget::update()
	 */
	public function update( $new_instance, $old_instance ) {

		$instance                      = $old_instance;

		$instance['cvm_widget_title']  = $new_instance['cvm_widget_title'];
		$instance['cvm_post_type']     = $new_instance['cvm_post_type'];
		$instance['cvm_taxonomy']      = $this->get_taxonomy( $new_instance['cvm_post_type'] );
		$instance['cvm_posts_number']  = (int) $new_instance['cvm_posts_number'];
		$instance['cvm_posts_tax']     = (int) $new_instance['cvm_posts_tax'];
		$instance['cvm_vim_image']     = (bool) $new_instance['cvm_vim_image'];
		$instance['cvm_show_playlist'] = (bool) $new_instance['cvm_show_playlist'];

		$instance['theme']             = $new_instance['theme'];
		$instance['layout']            = $new_instance['layout'];
		$instance['show_excerpts']     = $new_instance['show_excerpts'];
		$instance['playlist_loop']     = $new_instance['playlist_loop'];
		$instance['aspect_ratio']      = $new_instance['aspect_ratio'];
		$instance['width']             = absint( $new_instance['width'] );
		$instance['volume']            = absint( $new_instance['volume'] );
		$instance['title']             = $new_instance['title'];
		$instance['byline']            = $new_instance['byline'];
		$instance['portrait']          = $new_instance['portrait'];

		/**
		 * Allow additional option setup.
         *
         * @param array $instance_options   Array of widget options.
         * @param array $new_instance       Array of new widget options.
         * @param array $old_instance       Array of old widget options.
		 */
		return apply_filters(
            'vimeotheque\classic-widget\playlist-widget-extra_options_save',
            $instance,
            $new_instance,
            $old_instance
        );
	}

	/**
	 * @param $taxonomy
	 *
	 * @return string
	 */
	private function get_taxonomy( $taxonomy ) {
		$post_type = Helper_Admin::get_registered_post_type( $taxonomy );

		return $post_type
			? $post_type->get_taxonomy()->name
			: Plugin::instance()->get_cpt()
			        ->get_category_taxonomy_object()->name;
	}

	/**
	 * (non-PHPdoc)
	 *
	 * @param $instance
	 *
	 * @see WP_Widget::form()
	 */
	public function form( $instance ) {

		$defaults = $this->get_defaults();
		$options = wp_parse_args( (array) $instance, $defaults );
		?>
        <div class="cvm-player-settings-options">
            <p>
                <label for="<?php echo $this->get_field_id( 'cvm_widget_title' ); ?>"><?php _e( 'Title',
						'codeflavors-vimeo-video-post-lite' ); ?>: </label>
                <input type="text"
                       name="<?php echo $this->get_field_name( 'cvm_widget_title' ); ?>"
                       id="<?php echo $this->get_field_id( 'cvm_widget_title' ); ?>"
                       value="<?php echo $options['cvm_widget_title']; ?>"
                       class="widefat"/>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'cvm_posts_number' ); ?>"><?php _e( 'Number of videos to show',
						'codeflavors-vimeo-video-post-lite' ); ?>: </label>
                <input type="text"
                       name="<?php echo $this->get_field_name( 'cvm_posts_number' ); ?>"
                       id="<?php echo $this->get_field_id( 'cvm_posts_number' ); ?>"
                       value="<?php echo $options['cvm_posts_number']; ?>"
                       size="3"/>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'cvm_post_type' ); ?>"><?php _e( 'Post type',
						'codeflavors-vimeo-video-post-lite' ); ?>: </label>
				<?php
				Helper_Admin::select_post_type(
					$this->get_field_name( 'cvm_post_type' ),
					$options['cvm_post_type'],
					$this->get_field_id( 'cvm_post_type' ),
					'cvm_widget_post_type'
				);
				?>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'cvm_posts_tax' ); ?>"><?php _e( 'Category',
						'codeflavors-vimeo-video-post-lite' ); ?>: </label>
				<?php
				$args   = [
					'show_option_all'  => false,
					'show_option_none' => __( 'All categories',
						'codeflavors-vimeo-video-post-lite' ),
					'orderby'          => 'NAME',
					'order'            => 'ASC',
					'show_count'       => true,
					'hide_empty'       => false,
					'selected'         => $options['cvm_posts_tax'],
					'hierarchical'     => true,
					'name'             => $this->get_field_name( 'cvm_posts_tax' ),
					'id'               => $this->get_field_id( 'cvm_posts_tax' ),
					'taxonomy'         => $this->get_taxonomy( $options['cvm_post_type'] ),
					'hide_if_empty'    => true,
					'class'            => 'cvm_widget_taxonomy'
				];
				$select = wp_dropdown_categories( $args );
				if ( ! $select ) {
					_e( 'Nothing found.', 'codeflavors-vimeo-video-post-lite' );
					?>
                    <input type="hidden"
                           name="<?php echo $this->get_field_name( 'cvm_posts_tax' ); ?>"
                           id="<?php echo $this->get_field_id( 'cvm_posts_tax' ); ?>"
                           value=""/>
					<?php
				}
				?>
            </p>
            <p class="cvm-widget-show-vim-thumbs"<?php if ( $options['cvm_show_playlist'] ): ?> style="display:none;"<?php endif; ?>>
                <input class="checkbox" type="checkbox"
                       name="<?php echo $this->get_field_name( 'cvm_vim_image' ) ?>"
                       id="<?php echo $this->get_field_id( 'cvm_vim_image' ); ?>"<?php Helper_Admin::check( (bool) $options['cvm_vim_image'] ); ?> />
                <label for="<?php echo $this->get_field_id( 'cvm_vim_image' ); ?>"><?php _e( 'Display Vimeo thumbnails?',
						'codeflavors-vimeo-video-post-lite' ); ?></label>
            </p>
            <p>
                <input class="checkbox cvm-show-as-playlist-widget"
                       type="checkbox"
                       name="<?php echo $this->get_field_name( 'cvm_show_playlist' ); ?>"
                       id="<?php echo $this->get_field_id( 'cvm_show_playlist' ) ?>"<?php Helper_Admin::check( (bool) $options['cvm_show_playlist'] ); ?> />
                <label for="<?php echo $this->get_field_id( 'cvm_show_playlist' ) ?>"><?php _e( 'Show as video playlist',
						'codeflavors-vimeo-video-post-lite' ); ?></label>
            </p>
            <div class="cvm-recent-videos-playlist-options"<?php if ( ! $options['cvm_show_playlist'] ): ?> style="display:none;"<?php endif; ?>>
                <p>
                    <label for="<?php echo $this->get_field_id( 'theme' ); ?>"><?php _e( 'Theme',
							'codeflavors-vimeo-video-post-lite' ); ?> :</label>
					<?php
					Helper_Admin::select_playlist_theme(
						$this->get_field_name( 'theme' ),
						$options['theme'],
						$this->get_field_id( 'theme' ),
						'cvm_playlist_theme'
					);
					?>
                </p>

                <div class="cvm-theme-customize default"<?php if ( $options['theme'] != 'default' ): ?> style="display: none;"<?php endif; ?>>
					<?php _e( 'Playlist location',
						'codeflavors-vimeo-video-post-lite' ); ?> :
                    <label for=""><input type="radio"
                                         name="<?php echo $this->get_field_name( 'layout' ) ?>"
                                         value="" <?php echo $options['layout']
					                                         == ''
							? 'checked="checked"'
							: ''; ?> /> <?php _e( 'bottom',
							'codeflavors-vimeo-video-post-lite' ); ?></label>
                    <label for=""><input type="radio"
                                         name="<?php echo $this->get_field_name( 'layout' ) ?>"
                                         value="right" <?php echo $options['layout']
					                                              == 'right'
							? 'checked="checked"' : ''; ?> /> <?php _e( 'right',
							'codeflavors-vimeo-video-post-lite' ); ?></label>
                    <label for=""><input type="radio"
                                         name="<?php echo $this->get_field_name( 'layout' ) ?>"
                                         value="left" <?php echo $options['layout']
					                                             == 'left'
							? 'checked="checked"' : ''; ?> /> <?php _e( 'left',
							'codeflavors-vimeo-video-post-lite' ); ?></label>
                    <p>
                        <label for="<?php echo $this->get_field_id( 'show_excerpts' ); ?>"><?php _e( 'Show excerps',
								'codeflavors-vimeo-video-post-lite' ); ?>
                            :</label>
                        <input type="checkbox"
                               name="<?php echo $this->get_field_name( 'show_excerpts' ) ?>"
                               id="<?php echo $this->get_field_id( 'show_excerpts' ) ?>"
                               value="1"<?php Helper_Admin::check( (bool) $options['show_excerpts'] ); ?> />
                    </p>
                </div>

	            <?php
	            /**
	             * Theme specific playlist settings
                 * @ignore
	             */
	            do_action(
		            'vimeotheque\classic-widget\playlist-widget-theme-settings',
                    $this,
                    $options
	            );
	            ?>

                <p>
                    <label for="<?php echo $this->get_field_id( 'playlist_loop' ); ?>"><?php _e( 'Loop playlist',
							'codeflavors-vimeo-video-post-lite' ); ?> :</label>
                    <input type="checkbox"
                           name="<?php echo $this->get_field_name( 'playlist_loop' ) ?>"
                           id="<?php echo $this->get_field_id( 'loop' ) ?>"
                           value="1"<?php Helper_Admin::check( (bool) $options['playlist_loop'] ); ?> />
                </p>

                <p>
                    <label for="<?php echo $this->get_field_id( 'title' ) ?>"><?php _e( 'Title',
							'codeflavors-vimeo-video-post-lite' ); ?></label>:
                    <input type="checkbox"
                           name="<?php echo $this->get_field_name( 'title' ) ?>"
                           id="<?php echo $this->get_field_id( 'title' ) ?>"
                           value="1" <?php Helper_Admin::check( (bool) $options['title'] ); ?> />
                    <label for="<?php echo $this->get_field_id( 'title' ) ?>"><span
                                class="description"><?php _e( 'will display title on video',
								'codeflavors-vimeo-video-post-lite' ); ?></span></label>
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id( 'byline' ) ?>"><?php _e( 'Author',
							'codeflavors-vimeo-video-post-lite' ); ?></label>:
                    <input type="checkbox"
                           name="<?php echo $this->get_field_name( 'byline' ) ?>"
                           id="<?php echo $this->get_field_id( 'byline' ) ?>"
                           value="1" <?php Helper_Admin::check( (bool) $options['byline'] ); ?> />
                    <label for="<?php echo $this->get_field_id( 'byline' ) ?>"><span
                                class="description"><?php _e( 'will display author name on video',
								'codeflavors-vimeo-video-post-lite' ); ?></span></label>
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id( 'portrait' ) ?>"><?php _e( 'Image',
							'codeflavors-vimeo-video-post-lite' ); ?></label>:
                    <input type="checkbox"
                           name="<?php echo $this->get_field_name( 'portrait' ) ?>"
                           id="<?php echo $this->get_field_id( 'portrait' ) ?>"
                           value="1" <?php Helper_Admin::check( (bool) $options['portrait'] ); ?> />
                    <label for="<?php echo $this->get_field_id( 'portrait' ) ?>"><span
                                class="description"><?php _e( 'will display author image on video',
								'codeflavors-vimeo-video-post-lite' ); ?></span></label>
                </p>

                <p>
                    <label for="cvm_aspect_ratio"><?php _e( 'Aspect',
							'codeflavors-vimeo-video-post-lite' ); ?> :</label>
					<?php
					$args = [
						'name'     => $this->get_field_name( 'aspect_ratio' ),
						'id'       => $this->get_field_id( 'aspect_ratio' ),
						'class'    => 'cvm_aspect_ratio',
						'selected' => $options['aspect_ratio']
					];
					Helper_Admin::aspect_ratio_select( $args );
					?>
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id( 'width' ) ?>"><?php _e( 'Width',
							'codeflavors-vimeo-video-post-lite' ); ?> :</label>
                    <input type="text" class="cvm_width"
                           name="<?php echo $this->get_field_name( 'width' ); ?>"
                           id="<?php echo $this->get_field_id( 'width' ) ?>"
                           value="<?php echo $options['width']; ?>" size="2"/>px
                    | <?php _e( 'Height',
						'codeflavors-vimeo-video-post-lite' ); ?> : <span
                            class="cvm_height"
                            id="<?php echo $this->get_field_id( 'cvm_calc_height' ) ?>"><?php echo Helper::calculate_player_height( $options['aspect_ratio'],
							$options['width'] ); ?></span>px
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id( 'volume' ); ?>"><?php _e( 'Volume',
							'codeflavors-vimeo-video-post-lite' ); ?></label> :
                    <input type="text"
                           name="<?php echo $this->get_field_name( 'volume' ); ?>"
                           id="<?php echo $this->get_field_id( 'volume' ); ?>"
                           value="<?php echo $options['volume']; ?>" size="1"
                           maxlength="3"/>
                    <label for="<?php echo $this->get_field_id( 'volume' ); ?>"><span
                                class="description"><?php _e( 'number between 0 (mute) and 100 (max)',
								'codeflavors-vimeo-video-post-lite' ); ?></span></label>

                </p>
            </div>
        </div>
		<?php
	}

	/**
	 * @return mixed
	 */
	public function get_parent() {
		return parent;
	}
}