<?php
/**
 * Expand Divi Contact Info Widget
 * adds a contat info widget
 *
 * @package  ExpandDivi/ExpandDiviContactInfoWidget
 */

// exit when accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ExpandDiviContactInfoWidget extends WP_Widget {

	function __construct() {
		add_action('admin_print_styles-widgets.php', array( $this, 'load_color_picker_style' ) );
		$args = array(
			'name' => esc_html__( 'Expand Divi Contact Info', 'expand-divi' ),
			'description' => esc_html__( 'Display your contact info.', 'expand-divi' )
		);
		parent::__construct( 'ed_contact_info', '', $args );

	}

	/**
	 * Enqueue color picker
	 */
    function load_color_picker_style() {
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' ); 
    }
        
	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	function widget( $args, $instance ) {
		echo $args['before_widget'];
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . $instance['title'] . $args['after_title'];
			}

			$icons_color = ! empty( $instance['icons_color'] ) ? $instance['icons_color'] : '';
			
			echo '<ul>';

			if ( ! empty( $instance['company_name'] ) ) {
				$company_name_li = '<li class="ed_company_name"><span';
				
				if ( ! empty( $icons_color ) ) {
					$company_name_li .= ' style="color:' . $icons_color . ';"';
				}

				$company_name_li .= '></span>';
				$company_name_li .= $instance['company_name'];
				$company_name_li .= '</li>';
				
				echo $company_name_li;
			}
			if ( ! empty( $instance['about'] ) ) {
				$about_li = '<li class="ed_about"><span';
				
				if ( ! empty( $icons_color ) ) {
					$about_li .= ' style="color:' . $icons_color . ';"';
				}

				$about_li .= '></span>';
				$about_li .= $instance['about'];
				$about_li .= '</li>';
				
				echo $about_li;
			}
			if ( ! empty( $instance['address'] ) ) {
				$address_li = '<li class="ed_address"><span';
				
				if ( ! empty( $icons_color ) ) {
					$address_li .= ' style="color:' . $icons_color . ';"';
				}

				$address_li .= '></span>';
				$address_li .= $instance['address'];
				$address_li .= '</li>';
				
				echo $address_li;
			}
			if ( ! empty( $instance['phone'] ) ) {
				$phone_li = '<li class="ed_phone"><span';
				
				if ( ! empty( $icons_color ) ) {
					$phone_li .= ' style="color:' . $icons_color . ';"';
				}

				$phone_li .= '></span><a href="tel:';
				$phone_li .= $instance['phone'];
				$phone_li .= '">';
				$phone_li .= $instance['phone'];
				$phone_li .= '</a></li>';
				
				echo $phone_li;
			}
			if ( ! empty( $instance['mobile'] ) ) {
				$mobile_li = '<li class="ed_mobile"><span';
				
				if ( ! empty( $icons_color ) ) {
					$mobile_li .= ' style="color:' . $icons_color . ';"';
				}

				$mobile_li .= '></span><a href="tel:';
				$mobile_li .= $instance['mobile'];
				$mobile_li .= '">';
				$mobile_li .= $instance['mobile'];
				$mobile_li .= '</a></li>';
				
				echo $mobile_li;
			}
			if ( ! empty( $instance['fax'] ) ) {
				$fax_li = '<li class="ed_fax"><span';
				
				if ( ! empty( $icons_color ) ) {
					$fax_li .= ' style="color:' . $icons_color . ';"';
				}

				$fax_li .= '></span>';
				$fax_li .= $instance['fax'];
				$fax_li .= '</li>';
				
				echo $fax_li;
			}
			if ( ! empty( $instance['email'] ) ) {
				$email_li = '<li class="ed_email"><span';
				
				if ( ! empty( $icons_color ) ) {
					$email_li .= ' style="color:' . $icons_color . ';"';
				}

				$email_li .= '></span><a href="mailto:';
				$email_li .= $instance['email'];
				$email_li .= '">';
				$email_li .= $instance['email'];
				$email_li .= '</a></li>';
				
				echo $email_li;
			}
			if ( ! empty( $instance['website'] ) ) {
				$website_li = '<li class="ed_website"><span';
				
				if ( ! empty( $icons_color ) ) {
					$website_li .= ' style="color:' . $icons_color . ';"';
				}

				$website_li .= '></span><a href="';
				$website_li .= $instance['website'];
				$website_li .= '">';
				$website_li .= $instance['website'];
				$website_li .= '</a></li>';
				
				echo $website_li;
			}
			echo '</ul>';
		echo $args['after_widget'];
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$company_name = ! empty( $instance['company_name'] ) ? $instance['company_name'] : '';
		$about = ! empty( $instance['about'] ) ? $instance['about'] : '';
		$email = ! empty( $instance['email'] ) ? $instance['email'] : '';
		$phone = ! empty( $instance['phone'] ) ? $instance['phone'] : '';
		$mobile = ! empty( $instance['mobile'] ) ? $instance['mobile'] : '';
		$fax = ! empty( $instance['fax'] ) ? $instance['fax'] : '';
		$address = ! empty( $instance['address'] ) ? $instance['address'] : '';
		$website = ! empty( $instance['website'] ) ? $instance['website'] : '';
		$icons_color = ! empty( $instance['icons_color'] ) ? $instance['icons_color'] : '';
		
		?>		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Contact Info Title:', 'expand-divi' ); ?></label>
			<input  type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'company_name' ) ); ?>"><?php esc_attr_e( 'Company Name:', 'expand-divi' ); ?></label>
			<input  type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'company_name' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'company_name' ) ); ?>" value="<?php echo esc_attr( $company_name ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'about' ) ); ?>"><?php esc_attr_e( 'About:', 'expand-divi' ); ?></label>
			<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'about' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'about' ) ); ?>"><?php echo esc_attr( $about ); ?></textarea>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'address' ) ); ?>"><?php esc_attr_e( 'Address:', 'expand-divi' ); ?></label>
			<input  type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'address' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'address' ) ); ?>" value="<?php echo esc_attr( $address ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'phone' ) ); ?>"><?php esc_attr_e( 'Phone:', 'expand-divi' ); ?></label>
			<input  type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'phone' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'phone' ) ); ?>" value="<?php echo esc_attr( $phone ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'mobile' ) ); ?>"><?php esc_attr_e( 'Mobile:', 'expand-divi' ); ?></label>
			<input  type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'mobile' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'mobile' ) ); ?>" value="<?php echo esc_attr( $mobile ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'fax' ) ); ?>"><?php esc_attr_e( 'Fax:', 'expand-divi' ); ?></label>
			<input  type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'fax' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'fax' ) ); ?>" value="<?php echo esc_attr( $fax ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'email' ) ); ?>"><?php esc_attr_e( 'Email:', 'expand-divi' ); ?></label>
			<input  type="email" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'email' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'email' ) ); ?>" value="<?php echo esc_attr( $email ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'website' ) ); ?>"><?php esc_attr_e( 'Website:', 'expand-divi' ); ?></label>
			<input  type="url" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'website' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'website' ) ); ?>" value="<?php echo esc_attr( $website ); ?>">
		</p>

		<p>  
	        <label for="<?php echo esc_attr( $this->get_field_id( 'icons_color' ) ); ?>"><?php esc_attr_e( 'Icons Color:', 'expand-divi' ); ?></label>
	        <input class="color-picker" type="text" id="<?php echo esc_attr( $this->get_field_id( 'icons_color' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'icons_color' ) ); ?>" value="<?php echo esc_attr( $icons_color ); ?>" />
	    </p>
		<script>
			( function( $ ){
				function initColorPicker( widget ) {
					widget.find( '.color-picker' ).wpColorPicker( {
						change: _.throttle( function() { // Customizer
							$(this).trigger( 'change' );
						}, 3000 )
					});
				}

				function onFormUpdate( event, widget ) {
					initColorPicker( widget );
				}

				$( document ).on( 'widget-added widget-updated', onFormUpdate );

				$( document ).ready( function() {
					$( '#widgets-right .widget:has(.color-picker)' ).each( function () {
						initColorPicker( $( this ) );
					} );
				} );
			}( jQuery ) );
		</script>
		<?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 *
	 * @return array
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

	    $instance['title']        = strip_tags( stripslashes( $new_instance['title'] ) );
	    $instance['company_name'] = strip_tags( stripslashes( $new_instance['company_name'] ) );
	    $instance['about']        = strip_tags( stripslashes( $new_instance['about'] ) );
	    $instance['email']        = strip_tags( stripslashes( $new_instance['email'] ) );
	    $instance['phone']        = strip_tags( stripslashes( $new_instance['phone'] ) );
	    $instance['mobile']       = strip_tags( stripslashes( $new_instance['mobile'] ) );
	    $instance['fax']          = strip_tags( stripslashes( $new_instance['fax'] ) );
	    $instance['address']      = strip_tags( stripslashes( $new_instance['address'] ) );
	    $instance['website']      = strip_tags( stripslashes( $new_instance['website'] ) );
	    $instance['icons_color']  = strip_tags( stripslashes( $new_instance['icons_color'] ) );

		return $instance;
	}

}

add_action( 'widgets_init', function(){
	register_widget( 'ExpandDiviContactInfoWidget' );
});