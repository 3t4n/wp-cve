<?php
 
/**
 * Plugin URI: http://www.softwarehtec.com/project/woocommerce-sendle-shipping-method/
 * Description: Sendle delivers parcels door-to-door across Australia at flat rates cheaper than post. Send 25kg from $5.98. Save time with fast ordering & easy tracking.
 * Author: softwarehtec.com
 * Author URI: http://www.softwarehtec.com
 * License: GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Domain Path: /lang
 * Text Domain: softwarehtec
 */ 
if ( ! defined( 'WPINC' ) ) {
    die;
}

class sendle_tracking_widget extends WP_Widget {

    function __construct() {
        parent::__construct(
            'sendle_tracking_widget', 
            __('Sendle Tracking', 'softwarehtec'), 
            array( 'description' => __( 'Track Sendle Parcel', 'softwarehtec' ), ) 
        );
    }

    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance['title'] );
        echo $args['before_widget'];
        if ( ! empty( $title ) )
            echo $args['before_title'] . $title . $args['after_title'];
        ?>
        <div class="sendle_tracking_wrapper">
            <div class="sendle_tracking_form">
                <input type="text" name="sendle_reference" value="" placeholder="Sendle Reference"/><button>LookUp</button>
            </div>
            <div class="sendle_tracking_info">

            </div>
        </div>
        <?php
        echo $args['after_widget'];

    }

    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        }else {
            $title = __( 'New title', 'softwarehtec' );
        }
 
        ?>
        <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>

        <?php 
    }
	
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        return $instance;
    }

}

function sendle_tracking_load_widget() {
    if(!is_sendle_widget_enable()){
        return false;
    }

    register_widget( 'sendle_tracking_widget' );
}
add_action( 'widgets_init', 'sendle_tracking_load_widget' );



function sendle_tracking_scripts_basic(){

    wp_enqueue_style( 'sendle-tracking-style', plugins_url( '/style.css', __FILE__ ) );
    wp_register_script( 'sendle-tracking-script', plugins_url( '/scripts.js', __FILE__ ), array( 'jquery' ) );
    wp_enqueue_script( 'sendle-tracking-script' );
    wp_localize_script( 'sendle-tracking-script', 'sendletracking',array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}
add_action( 'wp_enqueue_scripts', 'sendle_tracking_scripts_basic' );

add_action( 'wp_ajax_sendletrack', 'sendle_track_ajax' );

function sendle_track_ajax() {
	global $wpdb; // this is how you get access to the database

	$reference = trim( $_POST['reference'] );
        $reference = preg_replace("/[^A-Za-z0-9 ]/", '', $reference );
        if(strlen($reference) != 6){
            $error = "Invalid Sendle reference number.";
        }
        $result = array();
        if(!empty($error)){
            $result["result"] = 0;
            $result["info"] = $error;
        }else{

            $api_mode = get_option('sendle_shipping_api_mode');
            $api_id = get_option('sendle_shipping_api_id');
            $api_key= get_option('sendle_shipping_api_key');
            $api_debug = get_option('sendle_shipping_api_debug');

 
            if(!empty($api_mode) && !empty($api_id) && !empty($api_key)){

                if($api_mode  == "live"){
                    $apiurl = "https://api.sendle.com";
                }else{
                    $apiurl = "https://sandbox.sendle.com";
                }

                $url = $apiurl."/api/tracking/".$reference;

                if($api_debug == "yes"){
                    file_put_contents(ERROR_FILE, date("y-M-D h:i:s")." sendle-widget ".$url."\n", FILE_APPEND );
                }

                $args= array();
                $response =  wp_remote_get( $url, $args );

                if($api_debug == "yes"){
                    file_put_contents(ERROR_FILE, date("y-M-D h:i:s")." sendle-widget ".serialize($response) ."\n", FILE_APPEND );
                }

                $content = wp_remote_retrieve_body( $response );
                $sendle_result = json_decode( $content); 

                if(isset($sendle_result->tracking_events) ){

                    if(count($sendle_result->tracking_events) > 0 ){
                        $info = "<ul>";
                        foreach($sendle_result->tracking_events as $t){
                            $sendle_time = str_replace(array("T","Z"),"",$t->scan_time);
                            $info .= "<li><div class='sendle-column-left'><div class='sendle_event_type'>".$t->event_type."</div><div class='sendle_scan_time'>".$sendle_time."</div></div><div class='sendle-column-right'><div class='sendle_description'>".$t->description."</div></div></li>";
                        }
                        $info .= "</ul>";
                        $result["result"] = 1;
                        $result["info"] = $info;
                    }else{
                        $result["result"] = 1;
                        $result["info"] = "Please try again later, we are awaiting tracking info from Sendle.";
                    }
                }else{

                    $result["result"] = 1;
                    $result["info"] = "Your requested reference number was not found.";
                }
            }else{

                $result["result"] = 1;
                $result["info"] = "Your requested reference number was not found.";
            }
        }

        echo json_encode($result);
	wp_die(); // this is required to terminate immediately and return a proper response
}

add_shortcode('sendle_tracking', 'sendle_tracking_shortcode');

function sendle_tracking_shortcode() {
    if(!is_sendle_widget_enable()){
        return false;
    }
    $reference = preg_replace('/[^A-Za-z0-9]/', '', $_GET["reference"]);

?>
        <div class="sendle_tracking_wrapper sendle_tracking_shortcode_wrapper ">
            <div class="sendle_tracking_form">
                <input type="text" name="sendle_reference" value="<?php echo $reference; ?>" placeholder="Sendle Reference"/><button>LookUp</button>
            </div>
            <div class="sendle_tracking_info">

            </div>
        </div>
<?php
    if(!empty($reference)){
?>
<script type="text/javascript">
var $s = jQuery.noConflict();
$s(document).ready(function($) {
    $(".sendle_tracking_shortcode_wrapper .sendle_tracking_form button").click();
});
</script>
<?php
    }

}