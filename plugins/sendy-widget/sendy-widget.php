<?php
/*
  Plugin Name: Sendy Widget
  Description: A Simple yet powerfull Widget to allow users to subscribe to your newsletter via Sendy
  Author: WebHolics
  Author URI: https://webholics.org
  Plugin URI: https://webholics.org
  Version: 1.4
  Requires at least: 3.0.0
  Tested up to: 5.9

 */

/*

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

function wh_sendy_widget_enqueue_script() {
   wp_enqueue_script( 'sendy-widget',  plugins_url( '/js/script.js', __FILE__ ) , array('jquery') );
}

add_action( 'wp_enqueue_scripts', 'wh_sendy_widget_enqueue_script' );



add_action( 'widgets_init', 'register_Sendy_widget' );

function register_Sendy_widget() {
	register_widget( 'Sendy_Widget' );
}


/**
 * Adds Sendy_Widget widget.
 */
class Sendy_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'sendy_widget', // Base ID
			'Sendy Widget', // Name
			array( 'description' => __( 'A simple Widget to integrate Sendy', 'sendywidget' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array   $args     Widget arguments.
	 * @param array   $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
			// echo '<pre>';
			// print_r($args);
			// print_r($instance);

		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		if (isset($_POST['widget_id']) && $_POST['widget_id'] === $args['widget_id']){

		 if(isset($_POST['sub-submit'])){
			$api_url = $instance['sendyurl'].'/subscribe';
			$name = $_POST['subscriber_name'];
			$email = $_POST['subscriber_email'];
			$listid = $_POST['list'];
			$body = array( 'api_key' => $instance['api_key'], 'name' => $name, 'email' => $email,'list'=>$listid, 'boolean' => 'true' );
			$response = wp_remote_post( $api_url, array( 'body' => $body ) );

			if ( is_wp_error( $response ) ) {

				$message  = $response->get_error_message();

			} else {

				$response = $response['body'];
				if(  $response == '1'){
					$message ='Thanks for subscribing';
				}else{
					$message =$response;
				}
			}

			echo '<script>';
			echo 'alert("'.$message.'")';
			echo '</script>';


		}
		}

		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
?>


				<form  class="sendy-subscribe-form" id="subscribe-form" action=" " method="POST" accept-charset="utf-8">
				  <?php if ( $instance['hidename']!='on' ) { ?>
					<label for="name">Name</label><br/>
					<input type="text" name="subscriber_name" id="subscriber-name"/>
					<br/>
					<?php } ?>
					<label for="email">Email</label><br/>
					<input type="text" class="subscriber-email" name="subscriber_email" id="subscriber-email"/>
					<br/>
					<div>
					<input type="hidden"  name="widget_id" value="<?php echo $args['widget_id']; ?>"/>
					<input type="hidden" class="list" name="list" value="<?php echo $instance['listid']; ?>"/>
				 </div>

					<input type="submit" name="sub-submit" value="Subscribe"  id="sub-submit"/>
					<div class="resp"></div>
				</form>




	<?php echo $after_widget;

	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array   $new_instance Values just sent to be saved.
	 * @param array   $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['sendyurl'] = strip_tags( $new_instance['sendyurl'] );
		$instance['listid'] = strip_tags( $new_instance['listid'] );
		$instance['hidename'] = strip_tags( $new_instance['hidename'] );
		$instance['api_key'] = strip_tags( $new_instance['api_key'] );
		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array   $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( ' ', 'sendywidget' );
		}
?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Heading:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
				</p><p>
				<label for="<?php echo $this->get_field_id( 'sendyurl' ); ?>"><?php _e( 'Sendy Url:' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'sendyurl' ); ?>" name="<?php echo $this->get_field_name( 'sendyurl' ); ?>" type="text" value="<?php echo esc_attr( isset( $instance[ 'sendyurl' ] ) ?$instance[ 'sendyurl' ] :''); ?>" />
				 </p><p>
				<label for="<?php echo $this->get_field_id( 'api_key' ); ?>"><?php _e( 'Sendy API Key:' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'api_key' ); ?>" name="<?php echo $this->get_field_name( 'api_key' ); ?>" type="text" value="<?php echo esc_attr( isset( $instance[ 'api_key' ] )?$instance[ 'api_key' ] :''); ?>" />
				 </p><p>
				<label for="<?php echo $this->get_field_id( 'listid' ); ?>"><?php _e( 'List ID:' ); ?></label>
					<input class="widefat" id="<?php echo $this->get_field_id( 'listid' ); ?>" name="<?php echo $this->get_field_name( 'listid' ); ?>" type="text" value="<?php echo esc_attr( isset(  $instance[ 'listid' ] )?$instance[ 'listid' ] :''); ?>" />
				</p><p>

				<input class="checkbox" id="<?php echo $this->get_field_id( 'hidename' ); ?>" name="<?php echo $this->get_field_name( 'hidename' ); ?>" type="checkbox"  <?php echo ( isset($instance[ 'hidename' ]) && $instance[ 'hidename' ] =='on' )?'checked="checked"':'' ; ?>  />
				<label for="<?php echo $this->get_field_id( 'hidename' ); ?>"><?php _e( 'Hide Name' ); ?></label>

				</p>
				<p><a style="font-style:italic" target="_blank" href="https://codecanyon.net/item/sendy-widget-pro/6215362" >Get more features in Sendy Widget Pro</a></p>
		<?php
	}

} // class Sendy_Widget
