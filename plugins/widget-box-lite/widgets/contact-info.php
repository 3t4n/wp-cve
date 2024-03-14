<?php
/**
 * The Contact Info Widget definition
 *
 * @link       https://theme4press.com/widget-box/
 * @since      1.0.0
 * @package    Widget Box Lite
 * @author     Theme4Press
 */

if ( ! class_exists( 'Widget_Box_Lite_Contact_Info_Widget' ) ) {
	class Widget_Box_Lite_Contact_Info_Widget extends WP_Widget {

		function __construct() {
			parent::__construct(
				'widget-box-lite-contact-info-widget', esc_html__( 'Widget Box Lite: Contact Info', 'widget-box-lite' ), // Name
				array(
					'classname'   => 'widget-box widget-box-lite-contact-info',
					'description' => esc_html__( 'Add contact information about your company', 'widget-box-lite' )
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

			$print_css = $icon_style = '';

			if ( isset( $instance['icon_color'] ) && $instance['icon_color'] ) {
				$icon_style .= sprintf( 'color: %s;', $instance['icon_color'] );
			}

			$print_css .= '<style type="text/css">';
			$print_css .= '.' . $this->id . ' .icon { ' . $icon_style . ' }';
			$print_css .= '</style>';

			echo $print_css;

			if ( $instance['display'] == 'list' ) {
				$list = 'p';
			} else {
				$list       = 'li';
				$list_class = ' list-inline-item mr-4 my-1';
			} ?>

        <div class="widget-box-contact-info-widget <?php echo $this->id; ?>">

			<?php if ( $instance['display'] != 'list' ) { ?>
            <ul class="list-inline">
		<?php } ?>

			<?php if ( isset( $instance['address'] ) && $instance['address'] ): ?>
            <<?php echo $list; ?> class="address<?php echo $list_class; ?>">
            <span class="mr-1"><?php echo evolve_get_svg( 'address' ); ?></span>
            <strong class="mr-2"><?php esc_html_e( 'Address', 'widget-box-lite' ); ?></strong>
			<?php
			echo $instance['address']; ?>
            </<?php echo $list; ?>>
		<?php
		endif;

			if ( isset( $instance['phone'] ) && $instance['phone'] ): ?>
                <<?php echo $list; ?> class="phone<?php echo $list_class; ?>">
                <span class="mr-1"><?php echo evolve_get_svg( 'phone' ); ?></span>
                <strong class="mr-2"><?php esc_html_e( 'Phone', 'widget-box-lite' ); ?></strong>
				<?php
				echo $instance['phone']; ?>
                </<?php echo $list; ?>>
			<?php
			endif;

			if ( isset( $instance['fax'] ) && $instance['fax'] ): ?>
                <<?php echo $list; ?> class="fax<?php echo $list_class; ?>">
                <span class="mr-1"><?php echo evolve_get_svg( 'fax' ); ?></span>
                <strong class="mr-2"><?php esc_html_e( 'Fax', 'widget-box-lite' ); ?></strong>
				<?php
				echo $instance['fax']; ?>
                </<?php echo $list; ?>>
			<?php
			endif;

			if ( isset( $instance['email'] ) && $instance['email'] ): ?>
                <<?php echo $list; ?> class="email<?php echo $list_class; ?>">
                <span class="mr-1"><?php echo evolve_get_svg( 'contact-email' ); ?></span>
                <strong class="mr-2"><?php esc_html_e( 'Email', 'widget-box-lite' ); ?></strong>
                <a href="mailto:<?php echo antispambot( $instance['email'] ); ?>"><?php
					if ( $instance['email_text'] ) {
						echo $instance['email_text'];
					} else {
						echo $instance['email'];
					}
					?></a>
                </<?php echo $list; ?>>
			<?php
			endif; ?>

			<?php if ( $instance['display'] != 'list' ) { ?>
                </ul>
			<?php } ?>

            </div>

			<?php echo $after_widget;
		}

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;

			$instance['title']      = strip_tags( $new_instance['title'] );
			$instance['display']    = strip_tags( $new_instance['display'] );
			$instance['icon_color'] = $new_instance['icon_color'];
			$instance['address']    = strip_tags( $new_instance['address'] );
			$instance['phone']      = strip_tags( $new_instance['phone'] );
			$instance['fax']        = strip_tags( $new_instance['fax'] );
			$instance['email']      = strip_tags( $new_instance['email'] );
			$instance['email_text'] = strip_tags( $new_instance['email_text'] );

			return $instance;
		}

		function form( $instance ) {
			$defaults = array(
				'title'      => esc_html__( 'Get In Touch', 'widget-box-lite' ),
				'display'    => 'list',
				'icon_color' => '',
				'address'    => '',
				'phone'      => '',
				'fax'        => '',
				'email'      => '',
				'email_text' => '',
			);
			$instance = wp_parse_args( (array) $instance, $defaults );
			?>
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title', 'widget-box-lite' ); ?></label>
                <br/>
                <small class="howto"><?php esc_html_e( 'Leave empty to disable the title', 'widget-box-lite' ); ?></small>
                <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>"
                       name="<?php echo $this->get_field_name( 'title' ); ?>"
                       value="<?php echo $instance['title']; ?>"/>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'display' ); ?>"><?php esc_html_e( 'Items Display', 'widget-box-lite' ); ?></label>
                <br/>
                <small class="howto"><?php esc_html_e( 'Select how the items are displayed', 'widget-box-lite' ); ?></small>
                <select id="<?php echo $this->get_field_id( 'display' ); ?>"
                        name="<?php echo $this->get_field_name( 'display' ); ?>" class="widefat">
                    <option <?php if ( 'list' == $instance['display'] ) {
						echo 'selected="selected"';
					} ?> value="list"><?php esc_html_e( 'List', 'widget-box-lite' ); ?></option>
                    <option <?php if ( 'inline' == $instance['display'] ) {
						echo 'selected="selected"';
					} ?> value="inline"><?php esc_html_e( 'Inline', 'widget-box-lite' ); ?></option>
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
                <label for="<?php echo $this->get_field_id( 'address' ); ?>"><?php esc_html_e( 'Address', 'widget-box-lite' ); ?></label>
                <br/>
                <small class="howto"><?php esc_html_e( 'Insert an address, ex: 775 New York Ave, Brooklyn, Kings, New York 11203', 'widget-box-lite' ); ?></small>
                <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'address' ); ?>"
                       name="<?php echo $this->get_field_name( 'address' ); ?>"
                       value="<?php echo $instance['address']; ?>"/>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'phone' ); ?>"><?php esc_html_e( 'Phone', 'widget-box-lite' ); ?></label>
                <br/>
                <small class="howto"><?php esc_html_e( 'Insert a phone number, ex: +0(0)987654321', 'widget-box-lite' ); ?></small>
                <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'phone' ); ?>"
                       name="<?php echo $this->get_field_name( 'phone' ); ?>"
                       value="<?php echo $instance['phone']; ?>"/>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'fax' ); ?>"><?php esc_html_e( 'Fax', 'widget-box-lite' ); ?></label>
                <br/>
                <small class="howto"><?php esc_html_e( 'Insert a fax number, ex: +0(0)987654321', 'widget-box-lite' ); ?></small>
                <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'fax' ); ?>"
                       name="<?php echo $this->get_field_name( 'fax' ); ?>" value="<?php echo $instance['fax']; ?>"/>
            </p>
            <legend class="widget-box">
                <p>
                    <label for="<?php echo $this->get_field_id( 'email' ); ?>"><?php esc_html_e( 'Email', 'widget-box-lite' ); ?></label>
                    <br/>
                    <small class="howto"><?php esc_html_e( 'Insert an email address, ex: info@example.com', 'widget-box-lite' ); ?></small>
                    <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'email' ); ?>"
                           name="<?php echo $this->get_field_name( 'email' ); ?>"
                           value="<?php echo $instance['email']; ?>"/>
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id( 'email_text' ); ?>"><?php esc_html_e( 'Email Link Text', 'widget-box-lite' ); ?></label>
                    <br/>
                    <small class="howto"><?php esc_html_e( 'Insert a text for email contact, ex: Contact Us', 'widget-box-lite' ); ?></small>
                    <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'email_text' ); ?>"
                           name="<?php echo $this->get_field_name( 'email_text' ); ?>"
                           value="<?php echo $instance['email_text']; ?>"/>
                </p>
            </legend>

			<?php echo Widget_Box_Lite_Admin::upgrade();
		}

	}
}

if ( ! function_exists( 'widget_box_lite_contact_info_widget_register' ) ) {
	function widget_box_lite_contact_info_widget_register() {
		register_widget( 'Widget_Box_Lite_Contact_Info_Widget' );
	}
}

add_action( 'widgets_init', 'widget_box_lite_contact_info_widget_register' );