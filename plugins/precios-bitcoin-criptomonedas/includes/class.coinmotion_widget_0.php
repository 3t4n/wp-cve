<?php
class Coinmotion_Widget_0 extends WP_Widget {
 	public function __construct() {
 		$options = array(
 			'classname' => 'coinmotion_widget_0',
 			'description' => __( 'Sidebar widget to display cryptocurrencies exchange rates in real time.', 'coinmotion' )
 		);
 		$widget_title = __( 'Coinmotion: Price Table', 'coinmotion' );
 		parent::__construct(
 			'coinmotion_widget_0', $widget_title, $options
 		);
 	}

 	// Contenido del widget
 	public function widget( $args, $instance ) {
 		$params = coinmotion_get_widget_data();
 		echo $args[ 'before_widget' ];
 		//Título del widget por defecto
 		if ( ! empty( $instance[ 'title' ] ) ) {
 		  echo $args[ 'before_title' ] . apply_filters( 'widget_title', $instance[ 'title' ] ) . $args[ 'after_title' ];
 		  $params['title'] = $instance[ 'title' ];
 		}
 		if ( ! empty( $instance[ 'register_button_color' ] ) ) {
 		  $params['register_button_color'] = $instance[ 'register_button_color' ];
 		}
 		if ( ! empty( $instance[ 'refcode' ] ) ) {
 		  $params['refcode'] = $instance[ 'refcode' ];
 		}
 		if ( ! empty( $instance[ 'register_text' ] ) ) {
 		  $params['register_text'] = $instance[ 'register_text' ];
 		}
 		if ( ! empty( $instance[ 'lang' ] ) ) {
 		  $params['lang'] = $instance[ 'lang' ];
 		}
 		if ( ! empty( $instance[ 'register_text_color' ] ) ) {
 		  $params['register_text_color'] = $instance[ 'register_text_color' ];
 		}
 		if ( ! empty( $instance[ 'register_button_hover_color' ] ) ) {
 		  $params['register_button_hover_color'] = $instance[ 'register_button_hover_color' ];
 		}
 		if ( ! empty( $instance[ 'show_button' ] ) ) {
 		  $params['show_button'] = $instance[ 'show_button' ];
 		}
 		//Contenido
 		echo "<div class='coinmotion-widget-container22'></div>";
 		echo $args[ 'after_widget' ];
 	}

 		//Formulario widget
	public function form( $instance ) {
		$defaults = coinmotion_get_widget_data();

	  	if ( ! empty( $instance[ 'title' ] ) ) {
            $defaults['title'] = $instance['title'];
        }

	  	if ( ! empty( $instance[ 'refcode' ] ) ) {
            $defaults['refcode'] = $instance['refcode'];
        }
	   	
	   	if ( ! empty( $instance[ 'register_text' ] ) ) {
            $defaults['register_text'] = $instance['register_text'];
        }

	  	if ( ! empty( $instance[ 'lang' ] ) ) {
            $defaults['lang'] = $instance['lang'];
        }

	  	if ( ! empty( $instance[ 'register_button_color' ] ) ) {
            $defaults['register_button_color'] = $instance['register_button_color'];
        }

	  	if ( ! empty( $instance[ 'register_text_color' ] ) ) {
            $defaults['register_text_color'] = $instance['register_text_color'];
        }

	  	if ( ! empty( $instance[ 'register_button_hover_color' ] ) ) {
            $defaults['register_button_hover_color'] = $instance['register_button_hover_color'];
        }

	  	if ( ! empty( $instance[ 'show_button' ] ) ) {
            $defaults['show_button'] = $instance['show_button'];
        }
	  
	  $widget_title2 = __( 'Table Title', 'coinmotion' );
	  $widget_ref = __( 'Invitation code', 'coinmotion' );
	  $widget_boton = __( 'Button text', 'coinmotion' );
	  $widget_background = __( 'Button Background Color', 'coinmotion' );
	  $widget_color = __( 'Text color button', 'coinmotion' );
	  $widget_hover = __( ' Background color hover button', 'coinmotion' );
	  $widget_show_button = __( ' Show Coinmotion button', 'coinmotion' );
	  ?>
	  <!-- Estructura formulario-->
	  <p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
			<?php echo esc_attr($widget_title2) ?> 
		</label> 
 			
		<input 
		  class="coinmotion_widefat_coin2" 
		  id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" 
		  name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" 
		  type="text" 
		  value="<?php echo esc_attr( $defaults['title'] ); ?>">
	  </p>
	  <p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'refcode' ) ); ?>">
			<?php echo esc_attr($defaults['refcode']) ?>
		</label> 
 			
		<input 
		  class="coinmotion_widefat_coin2" 
		  id="<?php echo esc_attr( $this->get_field_id( 'refcode' ) ); ?>" 
		  name="<?php echo esc_attr( $this->get_field_name( 'refcode' ) ); ?>" 
		  type="text" 
		  value="<?php echo esc_attr( $defaults['refcode'] ); ?>">
	  </p>
	  <p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'register_text' ) ); ?>">
		<?php echo esc_attr($widget_boton) ?>
		</label> 
 			
		<input 
		  class="coinmotion_widefat_coin2" 
		  id="<?php echo esc_attr( $this->get_field_id( 'register_text' ) ); ?>" 
		  name="<?php echo esc_attr( $this->get_field_name( 'register_text' ) ); ?>" 
		  type="text" 
		  value="<?php echo esc_attr( $defaults['register_text'] ); ?>">
	  </p>
	  <p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'lang' ) ); ?>">
		<?php esc_attr_e( 'Language', 'coinmotion' ); ?>
		</label> 
 			
		<select class="coinmotion_widefat_coin2" 
		  id="<?php echo esc_attr( $this->get_field_id( 'lang' ) ); ?>" 
		  name="<?php echo esc_attr( $this->get_field_name( 'lang' ) ); ?>" >
		  <?php
          $langs = ['es', 'fi', 'en'];
          foreach ($langs as $lan){
          	if ($lan === $defaults['lang']){
            ?>
            	<option value="<?php echo esc_attr( $lan ); ?>" selected><?php echo esc_attr( $lan ); ?></option>
            <?php
            }
            else{
            ?>
            	<option value="<?php echo esc_attr( $lan ); ?>"><?php echo esc_attr( $lan ); ?></option>
            <?php
            }
          }
          ?>       
		</select>
	  </p>
	  <p>
		<input 
		  class="coinmotion_widefat_coin"
		  id="<?php echo esc_attr( $this->get_field_id( 'register_button_color' ) ); ?>" 
		  name="<?php echo esc_attr( $this->get_field_name( 'register_button_color' ) ); ?>" 
		  type="color" 
		  value="<?php echo esc_attr( $defaults['register_button_color'] ); ?>">
		  <label class="coinmotion_widefat_label" for="<?php echo esc_attr( $this->get_field_id( 'register_button_color' ) ); ?>">
		  <?php echo esc_attr($widget_background) ?>
		  </label> 
	  </p>
	  <p>        
		<input 
		  class="coinmotion_widefat_coin" 
		  id="<?php echo esc_attr( $this->get_field_id( 'register_text_color' ) ); ?>" 
		  name="<?php echo esc_attr( $this->get_field_name( 'register_text_color' ) ); ?>" 
		  type="color" 
		  value="<?php echo esc_attr( $defaults['register_text_color'] ); ?>">
		<label class="widefat_label" for="<?php echo esc_attr( $this->get_field_id( 'register_text_color' ) ); ?>">
		  <?php echo esc_attr($widget_color) ?>
		</label> 
	  </p>
	  <p>      
		<input 
		  class="coinmotion_widefat_coin"
		  id="<?php echo esc_attr( $this->get_field_id( 'register_button_hover_color' ) ); ?>" 
 		  name="<?php echo esc_attr( $this->get_field_name( 'register_button_hover_color' ) ); ?>" 
		  type="color" 
		  value="<?php echo esc_attr( $defaults['register_button_hover_color'] ); ?>">
  	    <label class="widefat_label" for="<?php echo esc_attr( $this->get_field_id( 'register_button_hover_color' ) ); ?>">
 			<?php echo esc_attr($widget_hover) ?>
		</label> 
	  </p>
	  <p>      
		<input 
		  class="coinmotion_widefat_coin"
		  id="<?php echo esc_attr( $this->get_field_id( 'show_button' ) ); ?>" 
 		  name="<?php echo esc_attr( $this->get_field_name( 'show_button' ) ); ?>" 
		  type="checkbox" 
		  value="<?php echo esc_attr( $defaults['show_button'] ); ?>">
  	    <label class="widefat_label" for="<?php echo esc_attr( $this->get_field_id( 'show_button' ) ); ?>">
 			<?php echo esc_attr($widget_hover) ?>
		</label> 
	  </p>
	  <?php
 	}
 	
 	function update( $new_instance, $old_instance ) {
 		$instance = $old_instance;
 		$instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
 		$instance[ 'refcode' ] = strip_tags( $new_instance[ 'refcode' ] );
 		$instance[ 'register_text' ] = strip_tags( $new_instance[ 'register_text' ] );
 		$instance[ 'lang' ] = strip_tags( $new_instance[ 'lang' ] );
 		$instance[ 'register_button_color' ] = strip_tags( $new_instance[ 'register_button_color' ] );
 		$instance[ 'register_text_color' ] = strip_tags( $new_instance[ 'register_text_color' ] );
 		$instance[ 'register_button_hover_color' ] = strip_tags( $new_instance[ 'register_button_hover_color' ] );
 		$instance[ 'show_button' ] = strip_tags( $new_instance[ 'show_button' ] );
 		return $instance;
 	}
}
?>