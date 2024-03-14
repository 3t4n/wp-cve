<?php
/**
 * The Banner Ads Widget definition
 *
 * @link       https://theme4press.com/widget-box/
 * @since      1.0.0
 * @package    Widget Box Lite
 * @author     Theme4Press
 */

if ( ! class_exists( 'Widget_Box_Lite_Banner_Ads_Widget' ) ) {
	class Widget_Box_Lite_Banner_Ads_Widget extends WP_Widget {

		function __construct() {
			parent::__construct(
				'widget-box-lite-banner-ads-widget', esc_html__( 'Widget Box Lite: Banner Ads', 'widget-box-lite' ), // Name
				array(
					'classname'   => 'widget-box widget-box-lite-banner-ads',
					'description' => esc_html__( 'Insert a banner ad', 'widget-box-lite' )
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
			?>
            <div class="d-flex flex-wrap">
				<?php if ( $instance['ad_img'] && $instance['ad_link'] && $instance['ad_target'] && $instance['ad_rel'] ): ?>

                    <a<?php if ( $instance['ad_rel'] == 'nofollow' ) {
						echo ' rel="' . $instance['ad_rel'] . '"';
					}
					if ( $instance['ad_title'] ) {
						echo ' title="' . $instance['ad_title'] . '"';
					} ?> target="<?php echo $instance['ad_target']; ?>" href="<?php echo $instance['ad_link']; ?>"><img
                                src="<?php echo $instance['ad_img']; ?>"
                                alt="<?php echo $instance['alt_text']; ?>"/></a>

				<?php endif; ?>
            </div>
			<?php
			echo $after_widget;
		}

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;

			$instance['title']     = $new_instance['title'];
			$instance['ad_title']  = $new_instance['ad_title'];
			$instance['alt_text']  = $new_instance['alt_text'];
			$instance['ad_img']    = $new_instance['ad_img'];
			$instance['ad_link']   = $new_instance['ad_link'];
			$instance['ad_target'] = $new_instance['ad_target'];
			$instance['ad_rel']    = $new_instance['ad_rel'];

			return $instance;
		}

		function form( $instance ) {
			$defaults = array(
				'title'     => '',
				'ad_title'  => '',
				'alt_text'  => '',
				'ad_img'    => '',
				'ad_link'   => '',
				'ad_target' => '',
				'ad_rel'    => ''
			);

			$instance = wp_parse_args( (array) $instance, $defaults ); ?>

            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title', 'widget-box-lite' ); ?></label>
                <br/>
                <small class="howto"><?php esc_html_e( 'Leave empty to disable the title', 'widget-box-lite' ); ?></small>
                <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>"
                       name="<?php echo $this->get_field_name( 'title' ); ?>"
                       value="<?php echo $instance['title']; ?>"/>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'alt_text' ); ?>"><?php esc_html_e( 'Image Alternative Text', 'widget-box-lite' ); ?></label>
                <br/>
                <small class="howto"><?php esc_html_e( 'Define the "alt" attribute of the banner image', 'widget-box-lite' ); ?></small>
                <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'alt_text' ); ?>"
                       name="<?php echo $this->get_field_name( 'alt_text' ); ?>"
                       value="<?php echo $instance['alt_text']; ?>"/>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'ad_img' ); ?>"><?php esc_html_e( 'Banner Image', 'widget-box-lite' ); ?></label>
                <br/>
                <small class="howto"><?php esc_html_e( 'Upload an image for the item, or specify an image URL directly', 'widget-box-lite' ); ?></small>
				<?php $html = '';
				$show       = ( ! empty( $instance['ad_img'] ) ) ? 'style="display: block;"' : '';
				$html       .= '<img src="' . $instance['ad_img'] . '" class="upload-' . $this->id . '-img remove-' . $this->id . '-img block-image" ' . $show . '>';
				$html       .= '<input type="text" class="widefat img upload-' . $this->id . '-url img remove-' . $this->id . '-url" name="' . $this->get_field_name( 'ad_img' ) . '" id="' . $this->get_field_id( 'ad_img' ) . '" value="' . $instance['ad_img'] . '" />';
				$html       .= '<input type="button" id="upload-' . $this->id . '" class="button button-primary widget-box-upload-media select-img" value="' . esc_html__( 'Select Image', 'widget-box-lite' ) . '"/>';
				echo $html; ?>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'ad_link' ); ?>"><?php esc_html_e( 'Link URL', 'widget-box-lite' ); ?></label>
                <br/>
                <small class="howto"><?php esc_html_e( 'Insert the link URL, ex: https://example.com', 'widget-box-lite' ); ?></small>
                <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'ad_link' ); ?>"
                       name="<?php echo $this->get_field_name( 'ad_link' ); ?>"
                       value="<?php echo $instance['ad_link']; ?>"/>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'ad_title' ); ?>"><?php esc_html_e( 'Link Title', 'widget-box-lite' ); ?></label>
                <br/>
                <small class="howto"><?php esc_html_e( 'Insert the link "title" attribute', 'widget-box-lite' ); ?></small>
                <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'ad_title' ); ?>"
                       name="<?php echo $this->get_field_name( 'ad_title' ); ?>"
                       value="<?php echo $instance['ad_title']; ?>"/>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'ad_target' ); ?>"><?php esc_html_e( 'Link Target', 'widget-box-lite' ); ?></label>
                <br/>
                <small class="howto"><?php esc_html_e( '_blank = open in new window, _self = open in same window', 'widget-box-lite' ); ?></small>
                <select id="<?php echo $this->get_field_id( 'ad_target' ); ?>"
                        name="<?php echo $this->get_field_name( 'ad_target' ); ?>" class="widefat">
                    <option <?php if ( '_self' == $instance['ad_target'] ) {
						echo 'selected="selected"';
					} ?> value="_self"><?php echo '_self'; ?></option>
                    <option <?php if ( '_blank' == $instance['ad_target'] ) {
						echo 'selected="selected"';
					} ?> value="_blank"><?php echo '_blank'; ?></option>
                </select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'ad_rel' ); ?>"><?php esc_html_e( 'Relationship', 'widget-box-lite' ); ?></label>
                <br/>
                <small class="howto"><?php esc_html_e( 'Select the "rel" attribute for the link', 'widget-box-lite' ); ?></small>
                <select id="<?php echo $this->get_field_id( 'ad_rel' ); ?>"
                        name="<?php echo $this->get_field_name( 'ad_rel' ); ?>" class="widefat">
                    <option <?php if ( 'none' == $instance['ad_rel'] ) {
						echo 'selected="selected"';
					} ?> value="none"><?php esc_html_e( 'None', 'widget-box-lite' ); ?></option>
                    <option <?php if ( 'nofollow' == $instance['ad_rel'] ) {
						echo 'selected="selected"';
					} ?> value="nofollow"><?php echo 'nofollow'; ?></option>
                </select>
            </p>

			<?php echo Widget_Box_Lite_Admin::upgrade();

		}
	}
}

if ( ! function_exists( 'widget_box_lite_banner_ads_widget_register' ) ) {
	function widget_box_lite_banner_ads_widget_register() {
		register_widget( 'Widget_Box_Lite_Banner_Ads_Widget' );
	}
}

add_action( 'widgets_init', 'widget_box_lite_banner_ads_widget_register' );