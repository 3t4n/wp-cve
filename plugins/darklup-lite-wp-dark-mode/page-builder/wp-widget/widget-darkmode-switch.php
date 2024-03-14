<?php
 /**
  *
  * @package    DarklupLite - WP Dark Mode
  * @version    1.0.0
  * @author
  * @Websites:
  *
  */

/**************************************
*Creating Widget
***************************************/

class DarklupLite_Darkmode_Switch extends WP_Widget {


function __construct() {

parent::__construct(
// Base ID of your widget
'darkluplite_darkmode_switch_widget',


// Widget name will appear in UI
esc_html__( 'Darkmode Switch [DarklupLite]', 'darklup-lite' ),

// Widget description
array( 'description' => esc_html__( 'Select darkmode switch.', 'darklup-lite' ), )
);

}

// This is where the action happens
public function widget( $args, $instance ) {
$title 				= apply_filters( 'widget_title', $instance['title'] );
$darkmodeSwitch 	= apply_filters( 'widget_darkmode_switch', $instance['darkmode_switch'] );

// before and after widget arguments are defined by themes
echo wp_kses_post( $args['before_widget'] );
if ( ! empty( $title ) )
echo wp_kses_post( $args['before_title'] . $title . $args['after_title'] );

	// Switch style
	echo \DarklupLite\Switch_Style::switchStyle( esc_html( $darkmodeSwitch ) );

echo wp_kses_post( $args['after_widget'] );
}

// Widget Backend
public function form( $instance ) {

if ( isset( $instance[ 'title' ] ) ) {
	$title = $instance[ 'title' ];
}else {
	$title = esc_html__( 'Dark Mode Switch', 'darklup-lite' );
}
//
if ( isset( $instance[ 'darkmode_switch' ] ) ) {
	$darkmodeSwitch  = $instance[ 'darkmode_switch' ];
}else {
	$darkmodeSwitch  = 1;
}


// Widget admin form

// radio image switch style
$this->radio_image_style();
?>
<p>
<label style="font-weight:bold;margin-bottom:10px;display: block;" for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:' ,'darklup-lite'); ?></label>
<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>

<div class="image-select-content-wrapper">
<label style="font-weight:bold;margin-bottom:10px;display: block;" for="<?php echo esc_attr( $this->get_field_id( 'darkmode_switch' ) ); ?>"><?php esc_html_e( 'Select Switch:' ,'darklup-lite'); ?></label>
<?php

$images = \DarklupLite\Helper::switchDemoImage();

foreach( $images as $key => $option ):
?>
<div class="darkluplite-image-select-item">
    <label for="<?php echo esc_attr( $this->get_field_id( 'darkmode_switch'.$key ) ); ?>" class="image-item">
        <input id="<?php echo esc_attr( $this->get_field_id( 'darkmode_switch'.$key ) ); ?>" class="wp-widget-image-readio widefat" type="radio" name="<?php echo esc_attr( $this->get_field_name( 'darkmode_switch' ) ); ?>" value="<?php echo esc_html( $key ); ?>" <?php checked( $darkmodeSwitch, $key ); ?> />
        <img src="<?php echo esc_url( $option['url'] ); ?>" />
    </label>
</div>
<?php
endforeach;
?>
</div>

<?php

}

// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {

$instance = array();
$instance['title'] 	  	= ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
$instance['darkmode_switch'] = ( ! empty( $new_instance['darkmode_switch'] ) ) ? strip_tags( $new_instance['darkmode_switch'] ) : '';

return $instance;
}


public function radio_image_style() {
	?>
	<style>
		.image-select-content-wrapper {
			clear: both;
    		overflow: hidden;
		}
		.darkluplite-image-select-item label {
			display: block;
		}
		.image-select-content-wrapper .darkluplite-image-select-item {
			width: 30%;
		    padding: 5px;
		   /* border: 2px solid #bababa;*/
		    margin: 5px;
		    float: left;

		}
		.image-select-content-wrapper .darkluplite-image-select-item img{
			width: 100%;
            border: 2px solid #f5f5f5;
            transition: all 0.7s;
		}
		.darkluplite-image-select-item label input {
		    display: none;
		}
		.darkluplite-image-select-item {
			transition: all 0.7s;
			cursor: pointer;
		}
		.darkluplite-image-select-item.darkluplite_block-active, .darkluplite-image-select-item img:hover {
		    border-color: #3700B3;
		}
		.image-item > [type=radio]:checked + img {
		  border: 2px solid #3700B3;
		}

	</style>
	<?php
}


} // Class  ends here


// Register and load the widget
function darkluplite_darkmode_switch_init_widget() {
	register_widget( 'DarklupLite_Darkmode_Switch' );
}
add_action( 'widgets_init', 'darkluplite_darkmode_switch_init_widget' );

