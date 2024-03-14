<?php
/**
 * The Social Media Links Widget definition
 *
 * @link       https://theme4press.com/widget-box/
 * @since      1.0.0
 * @package    Widget Box Lite
 * @author     Theme4Press
 */

if ( ! class_exists( 'Widget_Box_Lite_Social_Media_Links_Widget' ) ) {
	class Widget_Box_Lite_Social_Media_Links_Widget extends WP_Widget {

		function __construct() {
			parent::__construct(
				'widget-box-lite-social-media-links-widget', esc_html__( 'Widget Box Lite: Social Media Links', 'widget-box-lite' ), // Name
				array(
					'classname'   => 'widget-box widget-box-lite-social-media-links',
					'description' => esc_html__( 'Provides many social media links with various styles or custom types', 'widget-box-lite' )
				) // Args
			);
		}

		function widget( $args, $instance ) {
			extract( $args );
			$title = apply_filters( 'widget_title', $instance['title'] );

			echo $before_widget;

			if ( $title ) {
				echo $before_title . $title . $after_title;
			}

			$rel         = $instance['relationship'];
			$max_entries = '5';

			if ( ! isset( $instance['link_target'] ) ) {
				$instance['link_target'] = '_blank';
			}

			if ( ! isset( $instance['icon_size'] ) ) {
				$instance['icon_size'] = 'normal';
			}

			foreach ( $instance as $name => $value ) {
				if ( strpos( $name, '_link' ) ) {
					$social_networks[ $name ] = str_replace( '_link', '', $name );
				}
			}

			$style_color = $style_boxed = $style_radius = $border_color = '';

			if ( isset( $instance['icon_color'] ) && $instance['icon_color'] ) {
				$style_color .= sprintf( 'color: %s;', $instance['icon_color'] );
			}

			if ( isset( $instance['icon_radius'] ) && ( $instance['icon_radius'] || $instance['icon_radius'] === '0' ) ) {
				$style_radius .= sprintf( ' border-radius: %spx;', $instance['icon_radius'] );
			}

			if ( isset( $instance['icon_border'] ) && $instance['icon_border'] == 'yes' && isset( $instance['border_color'] ) && $instance['border_color'] ) {
				$border_color .= sprintf( ' border-width: 1px; border-style: solid; border-color: %s;', $instance['border_color'] );
			}

			if ( isset( $instance['icon_size'] ) && $instance['icon_size'] ) {
				if ( $instance['icon_size'] == 'large' ) {
					$icon_height = $icon_width = '1.5rem';
				} else if ( $instance['icon_size'] == 'small' ) {
					$icon_height = $icon_width = '1rem';
				} else if ( $instance['icon_size'] == 'xlarge' ) {
					$icon_height = $icon_width = '1.8rem';
				} else {
					$icon_height = $icon_width = '1.2rem';
				}
			}

			$print_icons_css = '';

			$print_icons_css .= '<style type="text/css">';
			$print_icons_css .= '.' . $this->id . ' a { ' . $style_color . $style_radius . $style_boxed . $border_color . ' }';
			$print_icons_css .= '.' . $this->id . ' .icon, .' . $this->id . ' .svg-inline--fa {  height: ' . $icon_height . '; width: ' . $icon_width . ' }';
			$print_icons_css .= '</style>';

			echo $print_icons_css;
			?>
            <div class="widget-box-social-media-links-widget <?php echo $this->id; ?>">
                <ul class="social-media-links-widget text-center<?php if ( $instance['alignment'] != 'right' ) {
					echo ' w-100 d-inline-block';
				}
				echo ' float-md-' . $instance['alignment']; ?>">
					<?php
					foreach ( $social_networks as $name => $value ):
						if ( $instance[ $name ] ):
							if ( $value == 'fb' ) {
								$value = 'facebook';
							} elseif ( $value == 'rss' ) {
								$value = 'rss';
							} elseif ( $value == 'google' ) {
								$value = 'google-plus';
							}

							if ( $value == 'email' ) {
								$title = 'title="' . esc_html__( 'Newsletter', 'widget-box-lite' ) . '"';
							} else {
								$title = 'title="' . ucwords( str_replace( "-", " ", $value ) ) . '"';
							}
							?>
                            <li<?php if ( $instance['alignment'] == 'center' ) {
								echo ' class="float-md-none"';
							} ?>>
                                <a<?php echo( ( $rel == 'nofollow' ) ? ' rel="' . $rel . '"' : '' ); ?>
                                        target="<?php echo $instance['link_target']; ?>"
                                        href="<?php echo $instance[ $name ]; ?>" data-toggle="tooltip"
                                        data-placement="bottom"
									<?php echo $title; ?>
                                        data-original-title="<?php echo ucwords( str_replace( "-", " ", $value ) ); ?>"><?php echo evolve_get_svg( $value ); ?></a>
                            </li>
						<?php
						endif;
					endforeach;

					for ( $i = 0; $i < $max_entries; $i ++ ) {
						$block      = $instance[ 'block-' . $i ];
						$icon       = esc_html( $instance[ 'custom-icon-' . $i ] );
						$link_url   = esc_url( $instance[ 'link-' . $i ] );
						$link_title = esc_html( $instance[ 'title-' . $i ] );
						if ( isset( $block ) && $block != "" && $icon && $link_url ) {

							$item = '<li' . ( ( $instance['alignment'] == 'center' ) ?
									' class="float-md-none"' : '' ) . '><a' . ( ( $rel == 'nofollow' ) ? ' rel="' . $rel . '"' : '' ) . ' target="' . $instance['link_target'] . '" href="' . $link_url . '" data-toggle="tooltip"
                                   data-placement="bottom"
									title="' . $link_title . '"
                                   data-original-title="' . $link_title . '"><i class="' . $icon . '"></i></a></li>';

							echo $item;
						}
					} ?>

                </ul>
            </div>
            <div class="clearfix"></div>
			<?php
			echo $after_widget;
		}

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;

			$instance['title']          = $new_instance['title'];
			$instance['link_target']    = $new_instance['link_target'];
			$instance['relationship']   = $new_instance['relationship'];
			$instance['alignment']      = $new_instance['alignment'];
			$instance['icon_color']     = $new_instance['icon_color'];
			$instance['icon_size']      = $new_instance['icon_size'];
			$instance['icon_border']    = $new_instance['icon_border'];
			$instance['border_color']   = $new_instance['border_color'];
			$instance['icon_radius']    = $new_instance['icon_radius'];
			$instance['show_custom']    = $new_instance['show_custom'];
			$instance['rss_link']       = $new_instance['rss_link'];
			$instance['email_link']     = $new_instance['email_link'];
			$instance['fb_link']        = $new_instance['fb_link'];
			$instance['twitter_link']   = $new_instance['twitter_link'];
			$instance['youtube_link']   = $new_instance['youtube_link'];
			$instance['pinterest_link'] = $new_instance['pinterest_link'];

			$max_entries = '5';

			for ( $i = 0; $i < $max_entries; $i ++ ) {
				$block = $new_instance[ 'block-' . $i ];
				if ( $block == 0 || $block == "" ) {
					$instance[ 'custom-icon-' . $i ] = strip_tags( $new_instance[ 'custom-icon-' . $i ] );
					$instance[ 'block-' . $i ]       = $new_instance[ 'block-' . $i ];
					$instance[ 'title-' . $i ]       = strip_tags( $new_instance[ 'title-' . $i ] );
					$instance[ 'link-' . $i ]        = strip_tags( $new_instance[ 'link-' . $i ] );
				} else {
					$count                               = $block - 1;
					$instance[ 'custom-icon-' . $count ] = strip_tags( $new_instance[ 'custom-icon-' . $i ] );
					$instance[ 'block-' . $count ]       = $new_instance[ 'block-' . $i ];
					$instance[ 'title-' . $count ]       = strip_tags( $new_instance[ 'title-' . $i ] );
					$instance[ 'link-' . $count ]        = strip_tags( $new_instance[ 'link-' . $i ] );
				}
			}

			return $instance;
		}

		function form( $instance ) {
			$defaults = array(
				'title'          => esc_html__( 'Connect with us', 'widget-box-lite' ),
				'link_target'    => '_blank',
				'relationship'   => 'nofollow',
				'alignment'      => 'left',
				'icon_color'     => '#999999',
				'icon_size'      => 'normal',
				'icon_border'    => 'no',
				'border_color'   => '',
				'icon_radius'    => '0',
				'rss_link'       => '',
				'email_link'     => '',
				'fb_link'        => '',
				'twitter_link'   => '',
				'youtube_link'   => '',
				'pinterest_link' => '',
			);

			$max_entries = '5';

			$instance_default = array();

			for ( $i = 0; $i < $max_entries; $i ++ ) {
				$block = isset( $instance[ 'block-' . $i ] ) ? 0 : 1;
				if ( $block == 0 || $block == "" ) {
					$count                                       = $block - 1;
					$instance_default[ 'custom-icon-' . $count ] = '';
					$instance_default[ 'block-' . $count ]       = '';
					$instance_default[ 'title-' . $count ]       = '';
					$instance_default[ 'link-' . $count ]        = '';
				} else {
					$instance[ 'custom-icon-' . $i ] = '';
					$instance[ 'block-' . $i ]       = '';
					$instance[ 'title-' . $i ]       = '';
					$instance[ 'link-' . $i ]        = '';
				}
			}

			$defaults = array_merge( $defaults, $instance_default );
			$instance = wp_parse_args( (array) $instance, $defaults ); ?>

            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title', 'widget-box-lite' ); ?></label>
                <br/>
                <small class="howto"><?php esc_html_e( 'Leave empty to disable the title', 'widget-box-lite' ); ?></small>
                <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>"
                       name="<?php echo $this->get_field_name( 'title' ); ?>"
                       value="<?php echo $instance['title']; ?>"/>
            </p>
            <legend class="widget-box">
                <p>
                    <label for="<?php echo $this->get_field_id( 'link_target' ); ?>"><?php esc_html_e( 'Link Target', 'widget-box-lite' ); ?></label>
                    <br/>
                    <small class="howto"><?php esc_html_e( '_blank = open in new window, _self = open in same window', 'widget-box-lite' ); ?></small>
                    <select id="<?php echo $this->get_field_id( 'link_target' ); ?>"
                            name="<?php echo $this->get_field_name( 'link_target' ); ?>" class="widefat">
                        <option <?php if ( '_self' == $instance['link_target'] ) {
							echo 'selected="selected"';
						} ?> value="_self"><?php echo '_self'; ?></option>
                        <option <?php if ( '_blank' == $instance['link_target'] ) {
							echo 'selected="selected"';
						} ?> value="_blank"><?php echo '_blank'; ?></option>
                    </select>
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id( 'relationship' ); ?>"><?php esc_html_e( 'Link Relationship', 'widget-box-lite' ); ?></label>
                    <br/>
                    <small class="howto"><?php esc_html_e( 'Select the "rel" attribute for the links', 'widget-box-lite' ); ?></small>
                    <select id="<?php echo $this->get_field_id( 'relationship' ); ?>"
                            name="<?php echo $this->get_field_name( 'relationship' ); ?>" class="widefat">
                        <option <?php if ( 'none' == $instance['relationship'] ) {
							echo 'selected="selected"';
						} ?> value="none"><?php esc_html_e( 'None', 'widget-box-lite' ); ?></option>
                        <option <?php if ( 'nofollow' == $instance['relationship'] ) {
							echo 'selected="selected"';
						} ?> value="nofollow"><?php esc_html_e( 'nofollow', 'widget-box-lite' ); ?></option>
                    </select>
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id( 'alignment' ); ?>"><?php esc_html_e( 'Icons Alignment', 'widget-box-lite' ); ?></label>
                    <br/>
                    <small class="howto"><?php esc_html_e( 'Select the icons alignment', 'widget-box-lite' ); ?></small>
                    <select id="<?php echo $this->get_field_id( 'alignment' ); ?>"
                            name="<?php echo $this->get_field_name( 'alignment' ); ?>" class="widefat">
                        <option <?php if ( 'left' == $instance['alignment'] ) {
							echo 'selected="selected"';
						} ?> value="left"><?php esc_html_e( 'Left', 'widget-box-lite' ); ?></option>
                        <option <?php if ( 'right' == $instance['alignment'] ) {
							echo 'selected="selected"';
						} ?> value="right"><?php esc_html_e( 'Right', 'widget-box-lite' ); ?></option>
                        <option <?php if ( 'center' == $instance['alignment'] ) {
							echo 'selected="selected"';
						} ?> value="center"><?php esc_html_e( 'Center', 'widget-box-lite' ); ?></option>
                    </select>
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id( 'icon_color' ); ?>"><?php esc_html_e( 'Icons Color', 'widget-box-lite' ); ?></label>
                    <input class="widefat widget-box-color-picker" type="text"
                           id="<?php echo $this->get_field_id( 'icon_color' ); ?>"
                           name="<?php echo $this->get_field_name( 'icon_color' ); ?>"
                           value="<?php echo $instance['icon_color']; ?>"/>
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id( 'icon_size' ); ?>"><?php esc_html_e( 'Icons Size', 'widget-box-lite' ); ?></label>
                    <select id="<?php echo $this->get_field_id( 'icon_size' ); ?>"
                            name="<?php echo $this->get_field_name( 'icon_size' ); ?>" class="widefat">
                        <option <?php if ( 'normal' == $instance['icon_size'] ) {
							echo 'selected="selected"';
						} ?> value="normal"><?php esc_html_e( 'Normal', 'widget-box-lite' ); ?>
                        </option>
                        <option <?php if ( 'small' == $instance['icon_size'] ) {
							echo 'selected="selected"';
						} ?> value="small"><?php esc_html_e( 'Small', 'widget-box-lite' ); ?>
                        </option>
                        <option <?php if ( 'large' == $instance['icon_size'] ) {
							echo 'selected="selected"';
						} ?> value="large"><?php esc_html_e( 'Large', 'widget-box-lite' ); ?>
                        </option>
                        <option <?php if ( 'xlarge' == $instance['icon_size'] ) {
							echo 'selected="selected"';
						} ?> value="xlarge"><?php esc_html_e( 'X-Large', 'widget-box-lite' ); ?>
                        </option>
                    </select>
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id( 'icon_border' ); ?>"><?php esc_html_e( 'Icons Border', 'widget-box-lite' ); ?></label>
                    <select id="<?php echo $this->get_field_id( 'icon_border' ); ?>"
                            name="<?php echo $this->get_field_name( 'icon_border' ); ?>" class="widefat">
                        <option <?php if ( 'yes' == $instance['icon_border'] ) {
							echo 'selected="selected"';
						} ?> value="yes"><?php esc_html_e( 'Yes', 'widget-box-lite' ); ?>
                        </option>
                        <option <?php if ( 'no' == $instance['icon_border'] ) {
							echo 'selected="selected"';
						} ?> value="no"><?php esc_html_e( 'No', 'widget-box-lite' ); ?>
                        </option>
                    </select>
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id( 'border_color' ); ?>"><?php esc_html_e( 'Border Color', 'widget-box-lite' ); ?></label>
                    <input class="widefat widget-box-color-picker" type="text"
                           id="<?php echo $this->get_field_id( 'border_color' ); ?>"
                           name="<?php echo $this->get_field_name( 'border_color' ); ?>"
                           value="<?php echo $instance['border_color']; ?>"/>
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id( 'icon_radius' ); ?>"><?php esc_html_e( 'Icons Border Radius (px)', 'widget-box-lite' ); ?></label>
                    <small class="howto"><?php esc_html_e( '0px - square style, 25px - rounded style', 'widget-box-lite' ); ?></small>
                    <input class="widget-box-input-slider" type="range" min="0" step="1" max="25"
                           id="<?php echo $this->get_field_id( 'icon_radius' ); ?>"
                           name="<?php echo $this->get_field_name( 'icon_radius' ); ?>"
                           value="<?php echo absint( $instance['icon_radius'] ); ?>">
                    <span class="widget-box-slider-number"><?php echo absint( $instance['icon_radius'] ); ?></span>
                </p>
            </legend>
            <p>
                <label for="<?php echo $this->get_field_id( 'rss_link' ); ?>"><?php esc_html_e( 'RSS Link', 'widget-box-lite' ); ?></label>
                <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'rss_link' ); ?>"
                       name="<?php echo $this->get_field_name( 'rss_link' ); ?>"
                       value="<?php echo $instance['rss_link']; ?>"/>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'email_link' ); ?>"><?php esc_html_e( 'Newsletter Link', 'widget-box-lite' ); ?></label>
                <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'email_link' ); ?>"
                       name="<?php echo $this->get_field_name( 'email_link' ); ?>"
                       value="<?php echo $instance['email_link']; ?>"/>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'fb_link' ); ?>"><?php esc_html_e( 'Facebook Link', 'widget-box-lite' ); ?></label>
                <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'fb_link' ); ?>"
                       name="<?php echo $this->get_field_name( 'fb_link' ); ?>"
                       value="<?php echo $instance['fb_link']; ?>"/>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'twitter_link' ); ?>"><?php esc_html_e( 'Twitter Link', 'widget-box-lite' ); ?></label>
                <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'twitter_link' ); ?>"
                       name="<?php echo $this->get_field_name( 'twitter_link' ); ?>"
                       value="<?php echo $instance['twitter_link']; ?>"/>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'youtube_link' ); ?>"><?php esc_html_e( 'YouTube Link', 'widget-box-lite' ); ?></label>
                <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'youtube_link' ); ?>"
                       name="<?php echo $this->get_field_name( 'youtube_link' ); ?>"
                       value="<?php echo $instance['youtube_link']; ?>"/>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'pinterest_link' ); ?>"><?php esc_html_e( 'Pinterest Link', 'widget-box-lite' ); ?></label>
                <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'pinterest_link' ); ?>"
                       name="<?php echo $this->get_field_name( 'pinterest_link' ); ?>"
                       value="<?php echo $instance['pinterest_link']; ?>"/>
            </p>

			<?php echo Widget_Box_Lite_Admin::upgrade();
		}
	}
}

if ( ! function_exists( 'widget_box_lite_social_media_links_widget_register' ) ) {
	function widget_box_lite_social_media_links_widget_register() {
		register_widget( 'Widget_Box_Lite_Social_Media_Links_Widget' );
	}
}

add_action( 'widgets_init', 'widget_box_lite_social_media_links_widget_register' );