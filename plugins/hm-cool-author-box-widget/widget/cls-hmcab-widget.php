<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
* Widget Master Class
*/
class Hmcab_Widget extends WP_Widget {

	use Cab_Core, Hmcab_Personal_Settings, Hmcab_Social_Settings, Hmcab_Template_Settings, Hmcab_Styles_Post_Settings;
	
	function __construct() {
		parent::__construct('hm-cool-author-box-widget', __('HM Cool Author Box', HMCABW_TXT_DOMAIN), array('description' => __('Display HM Cool Author Box', HMCABW_TXT_DOMAIN)));
	}
	
	/**
	* Front-end display of widget.
	*
	* @see WP_Widget::widget()
	*
	* @param array $args Widget arguments.
	* @param array $instance Saved values from database.
	*/
	function widget( $args, $instance ) {

		echo $args['before_widget'];
		
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}

		$hmcabwCurrentUser = wp_get_current_user();

		// General Settings Data
		$hmcabwGeneralSettings	= $this->get_personal_settings();
		foreach ( $hmcabwGeneralSettings as $gs_name => $gs_value ) {
			if ( isset( $hmcabwGeneralSettings[$gs_name] ) ) {
				${"" . $gs_name}  = $gs_value;
			}
		}
		
		// Social Settings Data
		$hmcabwSocialSettings	= $this->get_social_settings();
		
		// Template Settings Data
		$hmcabwTempSettings = $this->get_template_settings();
		foreach ( $hmcabwTempSettings as $ts_name => $ts_value ) {
			if ( isset( $hmcabwTempSettings[$ts_name] ) ) {
				${"" . $ts_name}  = $ts_value;
			}
		}

		// Styles Sttings Data
		$hmcabStylesPost = $this->get_styles_post_settings();
		foreach ( $hmcabStylesPost as $option_name => $option_value ) {
			if ( isset( $hmcabStylesPost[$option_name] ) ) {
				${"" . $option_name} = $option_value;
			}
		}
		
		$hmcabwSocials = $this->get_social_network();

		// Load Styling
		include HMCABW_PATH . 'assets/css/post-author-box.php';
		?>
		<div class="hmcabw-main-wrapper-widget <?php esc_attr_e( $hmcabw_select_template ); ?>">

			<div class="hmcabw-image-container <?php echo esc_attr( $hmcabw_icon_shape ); ?>">
				<?php
					$hmcabwImage = array();
					if ( 'upload_image' === $hmcabw_author_image_selection ) {
						if ( intval( $hmcabw_photograph ) > 0 ) {
							$hmcabwImage 		= wp_get_attachment_image_src( $hmcabw_photograph, 'fulll', false );
							$hmcabwPhotograph2 	= $hmcabwImage[0];
						} else {
							$hmcabwPhotograph2 = HMCABW_ASSETS . 'img/noimage.png';
						}
						?>
						<img src="<?php echo esc_url( $hmcabwPhotograph2 ); ?>"  alt="...">
						<?php
					} else{
						echo get_avatar( $hmcabw_author_email, $hmcabw_photo_width );
					} 
				?>
			</div>

			<div class="hmcabw-info-container">
				
				<h3 class="hmcabw-name"><?php esc_html_e( $hmcabw_author_name ); ?></h3>
				
				<?php 
				if ( $hmcabw_display_title ) { 
					?>
					<span class="hmcabw-title"><?php esc_html_e( $hmcabw_author_title ); ?></span>
					<?php 
				} 
				?>
				
				<div class="hmcab-name-border"></div>
				
				<p class="hmcabw-bio-info"><?php echo wp_kses_post( $hmcabw_biographical_info ); ?></p>

			</div>

			<div class="hmcabw-email-url-container">
				<?php 
				if ( $hmcabw_display_email ) { 
					?>
					<span class="hmcabw-email">
						<i class="fa fa-envelope" aria-hidden="true"></i>&nbsp;<?php esc_html_e( $hmcabw_author_email ); ?>
					</span>
					<?php 
				} 
				
				if ( $hmcabw_display_web ) { 
					?>
					<a href="<?php echo esc_url( $hmcabw_author_website ); ?>" class="hmcabw-website">
						<i class="fa fa-globe" aria-hidden="true"></i>&nbsp;<?php esc_html_e( $hmcabw_author_website ); ?>
					</a>
					<?php
				}
				?>
			</div>
			<div class="hmcabw-social-container">
				<?php 
				foreach ( $hmcabwSocials as $hmcabwSocial ) {

					if ( isset( $hmcabwSocialSettings['hmcabw_'.$hmcabwSocial.'_enable'] ) ) {

						if ( filter_var($hmcabwSocialSettings['hmcabw_'.$hmcabwSocial.'_enable'], FILTER_SANITIZE_NUMBER_INT) == 1 ) {
							?><a href="<?php echo esc_url($hmcabwSocialSettings['hmcabw_'.$hmcabwSocial.'_link']); ?>" class="cab-front-social-icon <?php esc_attr_e( $hmcabw_icon_shape ); ?>">	
								<i class="fa-brands fa-<?php esc_attr_e( $hmcabwSocial ); ?>" aria-hidden="true"></i>
							</a><?php
						}
					}
				}
				?>
				</div>
		</div>
		<?php
		echo $args['after_widget'];
	}
	
	/**
	* Widget Form
	*
	* @see WP_Widget::form()
	*
	* @param array $instance Previously saved values from database.
	*/
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ){
			$title = $instance[ 'title' ];
		}else{
			$title = 'Author Box';
		} ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php
	}
	
	/*
	* Update Widget Value
	*
	* @see WP_Widget::update()
	*
	* @param array $new_instance Values just sent to be saved.
	* @param array $old_instance Previously saved values from database.
	*
	* @return array Updated safe values to be saved.
	*/
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : 'Author Box';
		return $instance;
	}
}
?>