<?php
/**
 * The Posts Slider Widget definition
 *
 * @link       https://theme4press.com/widget-box/
 * @since      1.0.0
 * @package    Widget Box Lite
 * @author     Theme4Press
 */

if ( ! class_exists( 'Widget_Box_Lite_Posts_Slider_Widget' ) ) {
	class Widget_Box_Lite_Posts_Slider_Widget extends WP_Widget {

		var $defaults;
		var $ints;
		var $custom;
		var $customs;

		function __construct() {

			parent::__construct(
				'widget-box-lite-posts-slider-widget', esc_html__( 'Widget Box Lite: Posts Slider', 'widget-box-lite' ), // Name
				array(
					'classname'   => 'widget-box widget-box-posts-slider',
					'description' => esc_html__( 'Add the posts slider to any widget area', 'widget-box-lite' )
				) // Args
			);

			$this->defaults['widget_title']               = '';
			$this->defaults['plugin_slug']                = 'widget-box-lite-posts-slider-widget';
			$this->defaults['category_ids']               = array( 0 );
			$this->defaults['posts_number']               = 5;
			$this->defaults['post_author']                = 'no';
			$this->defaults['post_comments']              = 'no';
			$this->defaults['post_excerpt']               = 'no';
			$this->defaults['use_excerpt']                = 'yes';
			$this->defaults['posts_excerpt_title_length'] = 40;
			$this->defaults['thumbnail_placeholder_url']  = plugins_url( 'assets/images/no-thumbnail-slider.jpg', __FILE__ );
			$this->defaults['slide_interval']             = 7000;
			$this->defaults['background_style']           = 'gradient';

			$parsed_url                      = parse_url( home_url() );
			$this->defaults['site_protocol'] = $parsed_url['host'];
			$this->defaults['site_url']      = $parsed_url['scheme'];
			unset( $parsed_url );

			$this->ints                  = array(
				'posts_number',
				'posts_excerpt_title_length',
				'slide_interval'
			);
			$this->valid_excerpt_sources = array( 'post_content', 'excerpt_field' );
			$this->custom                = array( 'use_excerpt' );

			add_action( 'save_post', array( $this, 'clear_cache' ) );
			add_action( 'deleted_post', array( $this, 'clear_cache' ) );
			add_action( 'switch_theme', array( $this, 'clear_cache' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		}

		function widget( $args, $instance ) {
			global $post;

			extract( $args );

			if ( ! isset( $args['widget_id'] ) ) {
				$args['widget_id'] = $this->id;
			}

			$title                       = ( ! empty( $instance['title'] ) ) ? $instance['title'] : $this->defaults['widget_title'];
			$title                       = apply_filters( 'widget_title', $title, $instance, $this->id_base );
			$category_ids                = ( ! empty( $instance['category_ids'] ) ) ? array_map( 'absint', $instance['category_ids'] ) : $this->defaults['category_ids'];
			$thumbnail_default_image_url = ( ! empty( $instance['thumbnail_default_image_url'] ) ) ? $instance['thumbnail_default_image_url'] : $this->defaults['thumbnail_placeholder_url'];

			$ints = array();
			foreach ( $this->ints as $key ) {
				$ints[ $key ] = ( ! empty( $instance[ $key ] ) ) ? absint( $instance[ $key ] ) : $this->defaults[ $key ];
			}

			$custom = array();
			foreach ( $this->custom as $key ) {
				$custom[ $key ] = ( ! empty( $instance[ $key ] ) ) ? $instance[ $key ] : $this->defaults[ $key ];
			}

			if ( in_array( 0, $category_ids ) ) {
				$category_ids = $this->defaults['category_ids'];
			}

			if ( '' == esc_url_raw( $thumbnail_default_image_url ) ) {
				$thumbnail_default_image_url = $this->defaults['thumbnail_placeholder_url'];
			}

			$query_args = array(
				'posts_per_page' => $ints['posts_number'],
				'no_found_rows'  => true,
				'post_status'    => 'publish',
			);

			$query_args['orderby'] = 'date';
			$query_args['order']   = 'DESC';

			if ( ! in_array( 0, $category_ids ) ) {
				$query_args['category__in'] = $category_ids;
			}

			$r                                = new WP_Query( apply_filters( 'widget_box_posts_args', $query_args ) );

			if ( $r->have_posts() ) :

				$this->customs['posts_excerpt_title_length'] = $ints['posts_excerpt_title_length'];
				$this->customs['use_excerpt'] = $custom['use_excerpt'];

				$thumb_class = array();

				$thumb_class[] = 'w-100';

				if ( ! empty( $thumb_class ) ) {
					$thumb_class = join( ' ', $thumb_class );
				}

				$default_attr = array(
					'src'   => $thumbnail_default_image_url,
					'class' => sprintf( "%s", $thumb_class ),
					'alt'   => '',
				);

				$default_img = '<img ';
				foreach ( $default_attr as $name => $value ) {
					$default_img .= ' ' . $name . '="' . $value . '"';
				}
				$default_img .= ' />';

				$this->defaults['comma']        = esc_html__( ', ', 'widget-box-lite' );
				$this->defaults['ellipses']     = esc_html__( '&hellip;', 'widget-box-lite' );
				$this->defaults['author_label'] = esc_html_x( 'Written by %s', 'theme author', 'widget-box-lite' );

				echo $before_widget; ?>

                <div class="widget-box widget-box-posts-slider-widget"
                     id="<?php echo $args['widget_id']; ?>">
					<?php if ( $title ) {
						echo $args['before_title'] . $title . $args['after_title'];
					} ?>

                    <div id="<?php echo $args['widget_id']; ?>-slider"
                         class="widget-columns-1 carousel slide widget-box-carousel"
                         data-ride="carousel"
                         data-interval="<?php echo $instance['slide_interval']; ?>">

                        <div class="carousel-inner carousel-resize">

							<?php $count = 0;
							while ( $r->have_posts() ) :
								$r->the_post(); ?>

                                <div class="carousel-item<?php if ( $count == '0' ) {
									echo " active";
								} ?>">

									<?php $is_thumb = false;
									if ( has_post_thumbnail() ) :
										the_post_thumbnail( 'evolve-slider-thumbnail', array( 'class' => $thumb_class ) );
										$is_thumb = true;
									endif;
									if ( ! $is_thumb ) :
										echo $default_img;
									endif;

									?>

                                    <div class="gradient"></div>

                                    <div class="carousel-caption<?php if ( $instance['background_style'] == "color" ): echo ' p-0'; endif; ?>">
										<?php if ( $instance['background_style'] == "color" ) { ?>
                                        <div class="background-caption">
											<?php } ?>
                                            <a href="<?php the_permalink(); ?>">
                                                <h5 class="mt-0"><?php if ( $post_title = $this->get_post_title_excerpt() ) {
														echo $post_title;
													} else {
														the_ID();
													} ?>
                                                </h5>
                                            </a>

                                            <div class="row post-meta align-items-center">
                                                <div class="col">
                                                            <span class="widget-box-posts-slider-widget-post-date published">
                                                    <a href="<?php the_permalink(); ?>"><?php echo get_the_date(); ?></a>
                                                </span>
													<?php
													if ( $instance['post_author'] == "yes" ) : ?>
                                                        <span class="widget-box-posts-slider-widget-post-author">
													<?php echo esc_html( $this->get_the_author() ); ?>
                                                </span>
													<?php endif; ?>
                                                </div>
                                            </div>
											<?php

											if ( $instance['post_excerpt'] == "yes" ) : ?>
                                                <div class="widget-box-posts-slider-widget-post-excerpt my-3">
													<?php echo $this->get_post_content_excerpt(); ?>
                                                </div>
											<?php endif; ?>
                                            <div class="row post-meta post-meta-footer align-items-top">
                                                <div class="widget-box-posts-slider-widget-post-categories col">
													<?php echo $this->get_post_categories( $r->post->ID ); ?>
                                                </div>
												<?php
												if ( $instance['post_comments'] == "yes" && ( ( comments_open() || get_comments_number() ) ) ) : ?>
                                                    <div class="widget-box-posts-slider-widget-post-comments-number col comment-count">
														<?php comments_popup_link( __( 'No Comments', 'widget-box-lite' ), __( '1 Comment', 'widget-box-lite' ), __( '% Comments', 'widget-box-lite' ) ); ?>
                                                    </div>
												<?php endif; ?>
                                            </div>
											<?php

											if ( $instance['background_style'] == "color" ) { ?>
                                        </div><!-- .background-caption -->
									<?php } ?>

                                    </div><!-- .carousel-caption -->
                                </div><!-- .carousel-item -->

								<?php
								$count ++;
							endwhile; ?>

                        </div><!-- .carousel-inner -->

						<?php if ( $count > 1 ) {
							echo "<a class='carousel-control-prev' href='#" . $args['widget_id'] . "-slider' role='button' data-slide='prev'>
	              <span class='carousel-control-button carousel-control-prev-icon' aria-hidden='true'></span>
                    <span class='screen-reader-text sr-only'> " . __( 'Previous', 'widget-box-lite' ) . "</span>
                </a>
                <a class='carousel-control-next' href='#" . $args['widget_id'] . "-slider' role='button' data-slide='next'>
                <span class='carousel-control-button carousel-control-next-icon' aria-hidden='true'></span>
                <span class='screen-reader-text sr-only'>" . __( 'Next', 'widget-box-lite' ) . "</span>
                </a>";
						}

						?>

                    </div>
                </div>

				<?php echo $after_widget;

				wp_reset_postdata();

			endif;

		}

		function update( $new_instance, $old_instance ) {
			$instance                                = $old_instance;
			$instance['title']                       = ( isset( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : $this->defaults['widget_title'];
			$instance['post_author']                 = ( isset( $new_instance['post_author'] ) ) ? strip_tags( $new_instance['post_author'] ) : $this->defaults['post_author'];
			$instance['post_comments']               = ( isset( $new_instance['post_comments'] ) ) ? strip_tags( $new_instance['post_comments'] ) : $this->defaults['post_comments'];
			$instance['post_excerpt']                = ( isset( $new_instance['post_excerpt'] ) ) ? strip_tags( $new_instance['post_excerpt'] ) : $this->defaults['post_excerpt'];
			$instance['use_excerpt']                 = ( isset( $new_instance['use_excerpt'] ) ) ? strip_tags( $new_instance['use_excerpt'] ) : $this->defaults['use_excerpt'];
			$instance['category_ids']                = ( isset( $new_instance['category_ids'] ) ) ? array_map( 'absint', $new_instance['category_ids'] ) : $this->defaults['category_ids'];
			$instance['thumbnail_default_image_url'] = ( isset( $new_instance['thumbnail_default_image_url'] ) ) ? esc_url_raw( $new_instance['thumbnail_default_image_url'] ) : $this->defaults['thumbnail_placeholder_url'];
			$instance['background_style']            = ( isset( $new_instance['background_style'] ) ) ? strip_tags( $new_instance['background_style'] ) : $this->defaults['background_style'];

			foreach ( $this->ints as $key ) {
				$instance[ $key ] = ( isset( $new_instance[ $key ] ) ) ? absint( $new_instance[ $key ] ) : $this->defaults[ $key ];
			}

			if ( in_array( 0, $instance['category_ids'] ) ) {
				$instance['category_ids'] = $this->defaults['category_ids'];
			}

			$this->clear_cache();

			$alloptions = wp_cache_get( 'alloptions', 'options' );
			if ( isset( $alloptions[ $this->defaults['plugin_slug'] ] ) ) {
				delete_option( $this->defaults['plugin_slug'] );
			}

			return $instance;
		}

		function clear_cache() {
			wp_cache_delete( $this->defaults['plugin_slug'], 'widget' );
		}

		function form( $instance ) {
			$title                       = ( isset( $instance['title'] ) ) ? $instance['title'] : $this->defaults['widget_title'];
			$post_author                 = ( isset( $instance['post_author'] ) ) ? $instance['post_author'] : $this->defaults['post_author'];
			$post_comments               = ( isset( $instance['post_comments'] ) ) ? $instance['post_comments'] : $this->defaults['post_comments'];
			$post_excerpt                = ( isset( $instance['post_excerpt'] ) ) ? $instance['post_excerpt'] : $this->defaults['post_excerpt'];
			$use_excerpt                 = ( isset( $instance['use_excerpt'] ) ) ? $instance['use_excerpt'] : $this->defaults['use_excerpt'];
			$thumbnail_default_image_url = ( isset( $instance['thumbnail_default_image_url'] ) ) ? $instance['thumbnail_default_image_url'] : $this->defaults['thumbnail_placeholder_url'];
			$category_ids                = ( isset( $instance['category_ids'] ) ) ? $instance['category_ids'] : $this->defaults['category_ids'];
			$background_style            = ( isset( $instance['background_style'] ) ) ? $instance['background_style'] : $this->defaults['background_style'];

			$ints = array();
			foreach ( $this->ints as $key ) {
				$ints[ $key ] = ( isset( $instance[ $key ] ) ) ? absint( $instance[ $key ] ) : $this->defaults[ $key ];
			}

			if ( in_array( 0, $category_ids ) ) {
				$category_ids = $this->defaults['category_ids'];
			}

			if ( '' == esc_url_raw( $thumbnail_default_image_url ) ) {
				$thumbnail_default_image_url = $this->defaults['thumbnail_placeholder_url'];
			}

			$field_ids                                = array();
			$field_ids['category_ids']                = $this->get_field_id( 'category_ids' );
			$field_ids['thumbnail_default_image_url'] = $this->get_field_id( 'thumbnail_default_image_url' );
			$field_ids['title']                       = $this->get_field_id( 'title' );
			foreach ( array_merge( $this->ints ) as $key ) {
				$field_ids[ $key ] = $this->get_field_id( $key );
			}

			global $_wp_additional_image_sizes;
			$wp_standard_image_size_labels              = array();
			$wp_standard_image_size_labels['full']      = esc_html__( 'Full Size', 'widget-box-lite' );
			$wp_standard_image_size_labels['large']     = esc_html__( 'Large', 'widget-box-lite' );
			$wp_standard_image_size_labels['medium']    = esc_html__( 'Medium', 'widget-box-lite' );
			$wp_standard_image_size_labels['thumbnail'] = esc_html__( 'Thumbnail', 'widget-box-lite' );

			$wp_standard_image_size_names = array_keys( $wp_standard_image_size_labels );
			$size_options                 = array();
			foreach ( get_intermediate_image_sizes() as $size_name ) {

				if ( is_integer( $size_name ) ) {
					continue;
				}
				$option_values              = array();
				$option_values['size_name'] = $size_name;
				$option_values['name']      = in_array( $size_name, $wp_standard_image_size_names ) ? $wp_standard_image_size_labels[ $size_name ] : $size_name;
				$option_values['width']     = isset( $_wp_additional_image_sizes[ $size_name ]['width'] ) ? $_wp_additional_image_sizes[ $size_name ]['width'] : get_option( "{$size_name}_size_w" );
				$option_values['height']    = isset( $_wp_additional_image_sizes[ $size_name ]['height'] ) ? $_wp_additional_image_sizes[ $size_name ]['height'] : get_option( "{$size_name}_size_h" );
				$size_options[]             = $option_values;
			}

			$label_settings = esc_html__( 'Settings', 'widget-box-lite' );
			$label_media    = esc_html_x( 'Media', 'post type general name', 'widget-box-lite' );
			$label          = sprintf( '%s &rsaquo; %s', $label_settings, $label_media );
			$media_trail    = ( current_user_can( 'manage_options' ) ) ? sprintf( '<a href="%s" target="_blank">%s</a>', esc_url( admin_url( 'options-media.php' ) ), esc_html( $label ) ) : sprintf( '<em>%s</em>', esc_html( $label ) );

			$label_all_cats = esc_html__( 'All categories', 'widget-box-lite' );

			$categories     = get_categories( array( 'hide_empty' => 0, 'hierarchical' => 1 ) );
			$number_of_cats = count( $categories );

			$selection_element = sprintf(
				'<select name="%s[]" id="%s" class="widefat">',
				$this->get_field_name( 'category_ids' ),
				$field_ids['category_ids']
			);
			$selection_element .= "\n";

			$cat_list = array();
			if ( 0 < $number_of_cats ) {

				while ( $categories ) {
					if ( '0' == $categories[0]->parent ) {
						$current_entry = array_shift( $categories );
						$cat_list[]    = array(
							'id'    => absint( $current_entry->term_id ),
							'name'  => esc_html( $current_entry->name ),
							'depth' => 0
						);
						continue;
					}
					$parent_index = $this->get_category_parent_index( $cat_list, $categories[0]->parent );
					if ( false === $parent_index ) {
						$current_entry = array_shift( $categories );
						$categories[]  = $current_entry;
						continue;
					}
					$depth     = $cat_list[ $parent_index ]['depth'] + 1;
					$new_index = $parent_index + 1;
					foreach ( $cat_list as $entry ) {
						if ( $depth <= $entry['depth'] ) {
							$new_index = $new_index + 1;
							continue;
						}
						$current_entry = array_shift( $categories );
						$end_array     = array_splice( $cat_list, $new_index );
						$cat_list[]    = array(
							'id'    => absint( $current_entry->term_id ),
							'name'  => esc_html( $current_entry->name ),
							'depth' => $depth
						);
						$cat_list      = array_merge( $cat_list, $end_array );
						break;
					}
				}

				$selected          = ( in_array( 0, $category_ids ) ) ? ' selected="selected"' : '';
				$selection_element .= "\t";
				$selection_element .= '<option value="0"' . $selected . '>' . $label_all_cats . '</option>';
				$selection_element .= "\n";

				foreach ( $cat_list as $category ) {
					$cat_name          = apply_filters( 'widget_box_recent_posts_list_cats', $category['name'], $category );
					$pad               = ( 0 < $category['depth'] ) ? str_repeat( '&ndash;&nbsp;', $category['depth'] ) : '';
					$selection_element .= "\t";
					$selection_element .= '<option value="' . $category['id'] . '"';
					$selection_element .= ( in_array( $category['id'], $category_ids ) ) ? ' selected="selected"' : '';
					$selection_element .= '>' . $pad . $cat_name . '</option>';
					$selection_element .= "\n";
				}

			}

			$selection_element .= "</select>\n"; ?>

            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title', 'widget-box-lite' ); ?></label>
                <br/>
                <small class="howto"><?php esc_html_e( 'Leave empty to disable the title', 'widget-box-lite' ); ?></small>
                <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>"
                       name="<?php echo $this->get_field_name( 'title' ); ?>"
                       value="<?php echo esc_attr( $title ); ?>"/>
            </p>
            <legend class="widget-box">
                <h4><?php esc_html_e( 'Display and Order', 'widget-box-lite' ); ?></h4>
                <p>
                    <label for="<?php echo $this->get_field_id( 'posts_number' ); ?>"><?php esc_html_e( 'Number of Posts', 'widget-box-lite' ); ?></label>
                    <input class="widget-box-input-slider" type="range" min="1" step="1" max="5"
                           id="<?php echo $this->get_field_id( 'posts_number' ); ?>"
                           name="<?php echo $this->get_field_name( 'posts_number' ); ?>"
                           value="<?php echo absint( $ints['posts_number'] ); ?>">
                    <span class="widget-box-slider-number"><?php echo absint( $ints['posts_number'] ); ?></span>
                </p>
                <p>
                    <label for="<?php echo $field_ids['category_ids']; ?>"><?php esc_html_e( 'Posts Category', 'widget-box-lite' ); ?></label>
                    <br/>
                    <small class="howto"><?php esc_html_e( 'Select the posts category', 'widget-box-lite' ); ?></small>
					<?php echo $selection_element; ?><br/>
                </p>
            </legend>
            <legend class="widget-box">
                <h4><?php esc_html_e( 'Post Title', 'widget-box-lite' ); ?></h4>
                <p>
                    <label for="<?php echo $this->get_field_id( 'posts_excerpt_title_length' ); ?>"><?php esc_html_e( 'Post Title Excerpt Length', 'widget-box-lite' ); ?></label>
                    <br/>
                    <small class="howto"><?php esc_html_e( 'Enter number of characters for post title excerpt', 'widget-box-lite' ); ?></small>
                    <input class="widefat" type="text"
                           id="<?php echo $this->get_field_id( 'posts_excerpt_title_length' ); ?>"
                           name="<?php echo $this->get_field_name( 'posts_excerpt_title_length' ); ?>"
                           value="<?php echo esc_attr( $ints['posts_excerpt_title_length'] ); ?>"/>
                </p>
            </legend>
            <legend class="widget-box">
                <h4><?php esc_html_e( 'Meta', 'widget-box-lite' ); ?></h4>
                <p>
                    <label for="<?php echo $this->get_field_id( 'post_author' ); ?>"><?php esc_html_e( 'Enable Post Author', 'widget-box-lite' ); ?></label>
                    <br/>
                    <small class="howto"><?php esc_html_e( 'Select if you want to display the post author', 'widget-box-lite' ); ?></small>
                    <select id="<?php echo $this->get_field_id( 'post_author' ); ?>"
                            name="<?php echo $this->get_field_name( 'post_author' ); ?>" class="widefat">
                        <option value="<?php echo esc_attr( 'yes' ); ?>" <?php selected( $post_author, 'yes' ); ?>><?php esc_html_e( 'Yes', 'widget-box-lite' ); ?></option>
                        <option value="<?php echo esc_attr( 'no' ); ?>" <?php selected( $post_author, 'no' ); ?>><?php esc_html_e( 'No', 'widget-box-lite' ); ?></option>
                    </select>
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id( 'post_comments' ); ?>"><?php esc_html_e( 'Enable Comments', 'widget-box-lite' ); ?></label>
                    <br/>
                    <small class="howto"><?php esc_html_e( 'Select if you want to display the post comments', 'widget-box-lite' ); ?></small>
                    <select id="<?php echo $this->get_field_id( 'post_comments' ); ?>"
                            name="<?php echo $this->get_field_name( 'post_comments' ); ?>" class="widefat">
                        <option value="<?php echo esc_attr( 'yes' ); ?>" <?php selected( $post_comments, 'yes' ); ?>><?php esc_html_e( 'Yes', 'widget-box-lite' ); ?></option>
                        <option value="<?php echo esc_attr( 'no' ); ?>" <?php selected( $post_comments, 'no' ); ?>><?php esc_html_e( 'No', 'widget-box-lite' ); ?></option>
                    </select>
                </p>
            </legend>
            <legend class="widget-box">
                <h4><?php esc_html_e( 'Content', 'widget-box-lite' ); ?></h4>
                <p>
                    <label for="<?php echo $this->get_field_id( 'post_excerpt' ); ?>"><?php esc_html_e( 'Enable Post Excerpt', 'widget-box-lite' ); ?></label>
                    <br/>
                    <small class="howto"><?php esc_html_e( 'Select if you want to display the post excerpt', 'widget-box-lite' ); ?></small>
                    <select id="<?php echo $this->get_field_id( 'post_excerpt' ); ?>"
                            name="<?php echo $this->get_field_name( 'post_excerpt' ); ?>" class="widefat">
                        <option value="<?php echo esc_attr( 'yes' ); ?>" <?php selected( $post_excerpt, 'yes' ); ?>><?php esc_html_e( 'Yes', 'widget-box-lite' ); ?></option>
                        <option value="<?php echo esc_attr( 'no' ); ?>" <?php selected( $post_excerpt, 'no' ); ?>><?php esc_html_e( 'No', 'widget-box-lite' ); ?></option>
                    </select>
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id( 'use_excerpt' ); ?>"><?php esc_html_e( 'Use The Defined Excerpt', 'widget-box-lite' ); ?></label>
                    <br/>
                    <small class="howto"><?php esc_html_e( 'Select if you want to use or not the defined excerpt of the post, otherwise will be auto generated from the post content', 'widget-box-lite' ); ?></small>
                    <select id="<?php echo $this->get_field_id( 'use_excerpt' ); ?>"
                            name="<?php echo $this->get_field_name( 'use_excerpt' ); ?>" class="widefat">
                        <option value="<?php echo esc_attr( 'yes' ); ?>" <?php selected( $use_excerpt, 'yes' ); ?>><?php esc_html_e( 'Yes', 'widget-box-lite' ); ?></option>
                        <option value="<?php echo esc_attr( 'no' ); ?>" <?php selected( $use_excerpt, 'no' ); ?>><?php esc_html_e( 'No', 'widget-box-lite' ); ?></option>
                    </select>
                </p>
            </legend>
            <legend class="widget-box">
                <h4><?php esc_html_e( 'Post Images', 'widget-box-lite' ); ?></h4>
                <p>
                    <label for="<?php echo $field_ids['thumbnail_default_image_url']; ?>"><?php esc_html_e( 'Placeholder Image URL (with HTTP/S)', 'widget-box-lite' ); ?></label>
                    <small class="howto"><?php esc_html_e( 'Insert full URL of the default placeholder image, e.g. https://example.com/wp-content/uploads/no-thumbnail-slider.jpg', 'widget-box-lite' ); ?></small>
                    <input class="widefat" id="<?php echo $field_ids['thumbnail_default_image_url']; ?>"
                           name="<?php echo $this->get_field_name( 'thumbnail_default_image_url' ); ?>" type="text"
                           value="<?php echo esc_url( $thumbnail_default_image_url ); ?>"/>
                </p>
            </legend>
            <legend class="widget-box">
                <h4><?php esc_html_e( 'Layout and Styling', 'widget-box-lite' ); ?></h4>
                <p>
                    <label for="<?php echo $this->get_field_id( 'slide_interval' ); ?>"><?php esc_html_e( 'Slider Speed (1000 = 1 second)', 'widget-box-lite' ); ?></label>
                    <input class="widget-box-input-slider" type="range" min="0" step="100" max="20000"
                           id="<?php echo $this->get_field_id( 'slide_interval' ); ?>"
                           name="<?php echo $this->get_field_name( 'slide_interval' ); ?>"
                           value="<?php echo absint( $ints['slide_interval'] ); ?>">
                    <span class="widget-box-slider-number"><?php echo absint( $ints['slide_interval'] ); ?></span>
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id( 'background_style' ); ?>"><?php esc_html_e( 'Content Background Style', 'widget-box-lite' ); ?></label>
                    <br/>
                    <small class="howto"><?php esc_html_e( 'Select the background style of the slide content', 'widget-box-lite' ); ?></small>
                    <select id="<?php echo $this->get_field_id( 'background_style' ); ?>"
                            name="<?php echo $this->get_field_name( 'background_style' ); ?>" class="widefat">
                        <option value="<?php echo esc_attr( 'color' ); ?>" <?php selected( $background_style, 'color' ); ?>><?php esc_html_e( 'Color', 'widget-box-lite' ); ?></option>
                        <option value="<?php echo esc_attr( 'gradient' ); ?>" <?php selected( $background_style, 'gradient' ); ?>><?php esc_html_e( 'Gradient', 'widget-box-lite' ); ?></option>
                    </select>
                </p>
            </legend>

			<?php echo Widget_Box_Lite_Admin::upgrade();
		}

		private function get_category_parent_index( $arr, $id ) {
			$len = count( $arr );
			if ( 0 == $len ) {
				return false;
			}
			$id = absint( $id );
			for ( $i = 0; $i < $len; $i ++ ) {
				if ( $id == $arr[ $i ]['id'] ) {
					return $i;
				}
			}

			return false;
		}

		public function enqueue_styles() {

			$css_code = '';

			$all_settings = $this->get_settings();

			list( $css_code ) = $this->dynamic_css( $all_settings );

			printf( "<style type='text/css'>\n%s</style>\n", $css_code );

		}

		private function get_first_image_id() {
			global $wpdb;
			$post = get_post();
			if ( $post and isset( $post->post_content ) ) {
				preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $all_img_tags );
				if ( $all_img_tags ) {
					foreach ( $all_img_tags[0] as $img_tag ) {
						preg_match( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $img_tag, $img_class );
						if ( $img_class ) {
							preg_match( '/wp-image-([\d]+)/i', $img_class[1], $thumb_id );
							if ( $thumb_id ) {
								$img_id = absint( $thumb_id[1] );
								if ( wp_attachment_is_image( $img_id ) ) {
									return $img_id;
								}
							}
						}

						preg_match( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $img_tag, $img_src );
						if ( $img_src ) {
							$url = preg_replace( '/([^?]+).*/', '\1', $img_src[1] );
							$url = preg_replace( '/(.+)-\d+x\d+\.(\w+)/', '\1.\2', $url );
							if ( 0 === strpos( $url, '//' ) ) {
								$url = $this->defaults['site_protocol'] . ':' . $url;
							} elseif ( 0 === strpos( $url, '/' ) ) {
								$url = $this->defaults['site_url'] . $url;
							}
							$thumb_id = $wpdb->get_var( $wpdb->prepare( "SELECT `ID` FROM $wpdb->posts WHERE `guid` = '%s'", $url ) );
							if ( $thumb_id ) {
								return absint( $thumb_id );
							}
						}
					}
				}
			}

			return 0;
		}

		private function get_first_image() {
			$thumb_id = $this->get_first_image_id();
			if ( $thumb_id ) :
				echo wp_get_attachment_image( $thumb_id, 'evolve-slider-thumbnail' );

				return true;
			else :
				return false;
			endif;
		}

		private function get_post_categories( $id ) {
			$terms = get_the_terms( $id, 'category' );

			if ( is_wp_error( $terms ) ) {
				return esc_html__( 'Error on listing categories', 'widget-box-lite' );
			}

			if ( empty( $terms ) ) {
				$text = esc_html__( 'No categories', 'widget-box-lite' );

				return $text;
			}

			$categories = array();

			foreach ( $terms as $term ) {
				$categories[] = sprintf(
					'<a href="%s">%s</a>',
					get_category_link( $term->term_id ),
					esc_html( $term->name )
				);
			}

			$string = '';
			$string .= join( $this->defaults['comma'], $categories );

			return $string;
		}

		private function get_the_author() {
			$author = get_the_author();

			if ( empty( $author ) ) {
				return '';
			} else {
				return sprintf( $this->defaults['author_label'], $author );
			}

		}

		private function dynamic_css( $all_instances ) {

			$ints     = array();
			$css_code = '';

			foreach ( $all_instances as $number => $settings ) {

				$css_code .= sprintf( '#%s-%d h5 { font-size: 2rem; }', $this->defaults['plugin_slug'], $number );
				$css_code .= "\n";

				$css_code .= sprintf( '#%s-%d .widget-box-posts-slider-widget-post-excerpt, #%s-%d .widget-box-posts-slider-widget-post-excerpt a { font-size: 1.2rem; }', $this->defaults['plugin_slug'], $number, $this->defaults['plugin_slug'], $number );
				$css_code .= "\n";
			}

			return array( $css_code, true );
		}

		private function get_post_content_excerpt() {

			$post = get_post();

			if ( empty( $post ) ) {
				return '';
			}

			$excerpt = '';

			if ( post_password_required( $post ) ) {
				return esc_html__( 'There is no excerpt because this is a protected post', 'widget-box-lite' );
			}

			if ( $this->customs['use_excerpt'] == 'yes' ) {
				$excerpt = apply_filters( 'widget_box_recent_posts_the_excerpt', $post->post_excerpt, $post );
			}

			if ( empty( $excerpt ) ) {

				$excerpt = strip_shortcodes( get_the_content( '' ) );
				$excerpt = apply_filters( 'the_excerpt', $excerpt );
				$excerpt = str_replace( ']]>', ']]&gt;', $excerpt );
				$excerpt = wp_trim_words( $excerpt, 100, '[&hellip;]' );

				if ( mb_strlen( $excerpt ) > 100 ) {
					$sub_excerpt   = mb_substr( $excerpt, 0, 100 );
					$excerpt_words = explode( ' ', $sub_excerpt );
					$excerpt_cut   = - ( mb_strlen( $excerpt_words[ count( $excerpt_words ) - 1 ] ) );
					if ( $excerpt_cut < 0 ) {
						$excerpt = mb_substr( $sub_excerpt, 0, $excerpt_cut );
					} else {
						$excerpt = $sub_excerpt;
					}
				}
			}

			$excerpt .= '[&hellip;]';

			return $excerpt;
		}

		private function get_post_title_excerpt() {

			$post_title = get_the_title();

			if ( mb_strlen( $post_title ) > $this->customs['posts_excerpt_title_length'] ) {
				$post_title = mb_substr( $post_title, 0, $this->customs['posts_excerpt_title_length'] );
				$post_title .= $this->defaults['ellipses'];
			}

			return $post_title;
		}

		private function get_thumbnail_size( $size = 'thumbnail' ) {

			$width  = 0;
			$height = 0;
			if ( in_array( $size, get_intermediate_image_sizes() ) ) {
				if ( in_array( $size, array( 'thumbnail', 'medium', 'large' ) ) ) {
					$width  = get_option( $size . '_size_w' );
					$height = get_option( $size . '_size_h' );
				} else {
					global $_wp_additional_image_sizes;
					$width  = $_wp_additional_image_sizes[ $size ]['width'];
					$height = $_wp_additional_image_sizes[ $size ]['height'];
				}
			}

			return array( $width, $height );
		}
	}
}

if ( ! function_exists( 'Widget_Box_Lite_Posts_Slider_Widget_register' ) ) {
	function Widget_Box_Lite_Posts_Slider_Widget_register() {
		register_widget( 'Widget_Box_Lite_Posts_Slider_Widget' );
	}
}

add_action( 'widgets_init', 'Widget_Box_Lite_Posts_Slider_Widget_register' );