<?php
/*
Plugin Name: WP-Parsi Iran weather
Plugin URI: http://forum.wp-parsi.com
Author: Morteza Geransayeh
Author URI: http://geransayeh.com
Description: Get accurate and beautiful weather forecasts for Iran cities powered by weather.com for your site.
Version: 1.9
*/

load_plugin_textdomain('wpp-iran-weather', 'wp-content/plugins/'.dirname(plugin_basename( __FILE__ )).'/languages/');
//echo WP_PLUGIN_URL.dirname( plugin_basename( __FILE__ ) );

require_once('includes/simple_html_dom.php');

/********************************************/
/**********      FARSI NUMBER       *********/
/********************************************/
function farsinum($text) {
   $en_numbrers = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
   $fa_numbrers = array('٠', '١', '٢', '٣', '۴', '۵', '۶', '٧', '٨', '٩');
   
   global $locale;
   if($locale=='fa_IR'){
      return str_replace($en_numbrers, $fa_numbrers, $text);
   }else{
      return $text;
   }
}


/********************************************/
/**********    ADD HEADER FILES     *********/
/********************************************/
if( ! is_admin() ){
   wp_enqueue_script("jquery");
}
function wppiw_css(){
	wp_register_style('wpp-iw-stylesheet', plugins_url('css/wp-parsi-iran-weather.css', __FILE__));
	wp_enqueue_style('wpp-iw-stylesheet');
}
add_action( 'wp_enqueue_scripts', 'wppiw_css' );

wp_enqueue_script( 'my-ajax-handle', plugin_dir_url( __FILE__ ) . 'js/wp-parsi-iran-weather.js', array( 'jquery' ) );
wp_localize_script( 'my-ajax-handle', 'wppiw_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );


add_action( 'wp_ajax_wppiw_ajax_hook', array('WPPIW_Widget','wppiw_action_function'));
add_action( 'wp_ajax_nopriv_wppiw_ajax_hook', array('WPPIW_Widget','wppiw_action_function'));


/********************************************/
/***********    WIDGET CLASS     ************/
/********************************************/
class WPPIW_Widget extends WP_Widget {

   function WPPIW_Widget() {
     $widget_ops = array(
       'classname' => 'postsfromcat',
       'description' => 'Allows you to display a newsletter form.');

     $control_ops = array(
        'width' => 250,
        'height' => 250,
        'id_base' => 'postsfromcat-widget');

     $this->WP_Widget('postsfromcat-widget',  __( 'WP-Parsi Iran weather', 'wpp-iran-weather' ), $widget_ops, $control_ops );
   }

   function form($instance) {
		?>
	      
		<label for="<?php echo $this->get_field_id( 'wppdcity' ); ?>"><?php _e('Default City', 'wpp-iran-weather'); ?></label>
		<?php include('includes/cities.php'); ?>
		  <script type="text/javascript">
	          var wppiws = "<?php echo $instance[ 'wppdcity' ]; ?>";
	          jQuery("#whtrcities option").each(function() {
	              var attr = jQuery(this).attr('value');
	              if(attr==wppiws){
	                 jQuery(this).attr('selected','selected');
	              }
	          });
	      </script>
	    
	    <table>
		<tr><td>
		<label for="<?php echo $this->get_field_id( 'wpptu' ); ?>"><?php _e( 'Temperature Unit', 'wpp-iran-weather'); ?></label>
		</td><td>
		<select id="<?php echo $this->get_field_id( 'wpptu' ); ?>"  name="<?php echo $this->get_field_name( 'wpptu' ); ?>">
			<option value="f" <?php if($instance[ 'wpptu' ]=='f'){echo ' selected';} ?>>°F</option>
			<option value="c" <?php if($instance[ 'wpptu' ]=='c'){echo ' selected';} ?>>°C</option>
		</select>
		</td></tr>
		<tr><td>
		<label for="<?php echo $this->get_field_id( 'wppico' ); ?>"><?php _e('Icon Size', 'wpp-iran-weather'); ?></label>
		</td><td>
		<select id="<?php echo $this->get_field_id( 'wppico' ); ?>"  name="<?php echo $this->get_field_name( 'wppico' ); ?>">
			<option value="52" <?php if($instance[ 'wppico' ]=='52'){echo ' selected';} ?>>52px</option>
			<option value="120" <?php if($instance[ 'wppico' ]=='120'){echo ' selected';} ?>>120px</option>
		</select>
		</td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td colspan="2"><strong><?php _e('Widget display options', 'wpp-iran-weather'); ?></strong></td></tr>
		<tr><td>
		<label for="<?php echo $this->get_field_id( 'wppwnd' ); ?>"><?php _e('Wind', 'wpp-iran-weather'); ?></label>
		</td><td>
		<select id="<?php echo $this->get_field_id( 'wppwnd' ); ?>"  name="<?php echo $this->get_field_name( 'wppwnd' ); ?>">
			<option value="1" <?php if($instance[ 'wppwnd' ]=='1'){echo ' selected';} ?>><?php _e('Yes', 'wpp-iran-weather'); ?></option>
			<option value="0" <?php if($instance[ 'wppwnd' ]=='0'){echo ' selected';} ?>><?php _e('No', 'wpp-iran-weather'); ?></option>
		</select>
		</td></tr>
		<tr><td>
		<label for="<?php echo $this->get_field_id( 'wppprs' ); ?>"><?php _e('Pressure', 'wpp-iran-weather'); ?></label>
		</td><td>
		<select id="<?php echo $this->get_field_id( 'wppprs' ); ?>"  name="<?php echo $this->get_field_name( 'wppprs' ); ?>">
			<option value="1" <?php if($instance[ 'wppprs' ]=='1'){echo ' selected';} ?>><?php _e('Yes', 'wpp-iran-weather'); ?></option>
			<option value="0" <?php if($instance[ 'wppprs' ]=='0'){echo ' selected';} ?>><?php _e('No', 'wpp-iran-weather'); ?></option>
		</select>
		</td></tr>
		<tr><td>
		<label for="<?php echo $this->get_field_id( 'wppvs' ); ?>"><?php _e('Visibility', 'wpp-iran-weather'); ?></label>
		</td><td>
		<select id="<?php echo $this->get_field_id( 'wppvs' ); ?>"  name="<?php echo $this->get_field_name( 'wppvs' ); ?>">
			<option value="1" <?php if($instance[ 'wppvs' ]=='1'){echo ' selected';} ?>><?php _e('Yes', 'wpp-iran-weather'); ?></option>
			<option value="0" <?php if($instance[ 'wppvs' ]=='0'){echo ' selected';} ?>><?php _e('No', 'wpp-iran-weather'); ?></option>
		</select>
        </td></tr>
        <tr><td>
		<label for="<?php echo $this->get_field_id( 'wppuv' ); ?>"><?php _e('UV Index', 'wpp-iran-weather'); ?></label>
		</td><td>
		<select id="<?php echo $this->get_field_id( 'wppuv' ); ?>"  name="<?php echo $this->get_field_name( 'wppuv' ); ?>">
			<option value="1" <?php if($instance[ 'wppuv' ]=='1'){echo ' selected';} ?>><?php _e('Yes', 'wpp-iran-weather'); ?></option>
			<option value="0" <?php if($instance[ 'wppuv' ]=='0'){echo ' selected';} ?>><?php _e('No', 'wpp-iran-weather'); ?></option>
		</select>
		</td></tr>
		<tr><td>
		<label for="<?php echo $this->get_field_id( 'wpphm' ); ?>"><?php _e('Humidity', 'wpp-iran-weather'); ?></label>
		</td><td>
		<select id="<?php echo $this->get_field_id( 'wpphm' ); ?>"  name="<?php echo $this->get_field_name( 'wpphm' ); ?>">
			<option value="1" <?php if($instance[ 'wpphm' ]=='1'){echo ' selected';} ?>><?php _e('Yes', 'wpp-iran-weather'); ?></option>
			<option value="0" <?php if($instance[ 'wpphm' ]=='0'){echo ' selected';} ?>><?php _e('No', 'wpp-iran-weather'); ?></option>
		</select>
		</td></tr>
		<tr><td>
		<label for="<?php echo $this->get_field_id( 'wppwht' ); ?>"><?php _e('Weather Text', 'wpp-iran-weather'); ?></label>
		</td><td>
		<select id="<?php echo $this->get_field_id( 'wppwht' ); ?>"  name="<?php echo $this->get_field_name( 'wppwht' ); ?>">
			<option value="1" <?php if($instance[ 'wppwht' ]=='1'){echo ' selected';} ?>><?php _e('Yes', 'wpp-iran-weather'); ?></option>
			<option value="0" <?php if($instance[ 'wppwht' ]=='0'){echo ' selected';} ?>><?php _e('No', 'wpp-iran-weather'); ?></option>
		</select>
		</td></tr>
		<tr><td>
		<label for="<?php echo $this->get_field_id( 'wppcl' ); ?>"><?php _e('City List', 'wpp-iran-weather'); ?></label>
		</td><td>
		<select id="<?php echo $this->get_field_id( 'wppcl' ); ?>"  name="<?php echo $this->get_field_name( 'wppcl' ); ?>">
			<option value="1" <?php if($instance[ 'wppcl' ]=='1'){echo ' selected';} ?>><?php _e('Yes', 'wpp-iran-weather'); ?></option>
			<option value="0" <?php if($instance[ 'wppcl' ]=='0'){echo ' selected';} ?>><?php _e('No', 'wpp-iran-weather'); ?></option>
		</select>
		</td></tr>
		</table>
		
		<?php 
   }

   function update($new_instance, $old_instance) {
   	   global $instance;
		$instance = array();
		$instance['wppdcity'] = strip_tags( $new_instance['wppdcity'] );
		$instance['wpptu'] = strip_tags( $new_instance['wpptu'] );
		$instance['wppwnd'] = strip_tags( $new_instance['wppwnd'] );
		$instance['wppprs'] = strip_tags( $new_instance['wppprs'] );
		$instance['wppvs'] = strip_tags( $new_instance['wppvs'] );
		$instance['wppuv'] = strip_tags( $new_instance['wppuv'] );
		$instance['wpphm'] = strip_tags( $new_instance['wpphm'] );
		$instance['wppico'] = strip_tags( $new_instance['wppico'] );
		$instance['wppwht'] = strip_tags( $new_instance['wppwht'] );
		$instance['wppcl'] = strip_tags( $new_instance['wppcl'] );
		return $instance;
   }



 public function wppiw_action_function(){
 	$dummy = new WPPIW_Widget();
    $settings = array_values(array_filter($dummy->get_settings()));

   if(isset($_POST['wppdcity'])){
       $cityid = $_POST['wppdcity'];
       $city = "http://wxdata.weather.com/wxdata/weather/local/$cityid?cc=*&dayf=1&prod=xoap&par=1003666583&key=4128909340a9b2fc";
   }
   elseif($settings[0][ 'wppdcity' ]){
       $cityid = $settings[0][ 'wppdcity' ];
       $city = "http://wxdata.weather.com/wxdata/weather/local/$cityid?cc=*&dayf=1&prod=xoap&par=1003666583&key=4128909340a9b2fc";
   }else{
       $cityid = 'IRXX0127';
       $city = "http://wxdata.weather.com/wxdata/weather/local/IRXX0127?cc=*&dayf=1&prod=xoap&par=1003666583&key=4128909340a9b2fc";
   }
   
   $curl = curl_init();
   curl_setopt($curl, CURLOPT_URL, $city);
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
   $str = curl_exec($curl);
   curl_close($curl);
   $html = str_get_html($str);
   
   $dnam = $html->find('dnam');
   $cname = explode(',',$dnam[0]->innertext);
   
   
   foreach($html->find('tmp') as $ee):
	    $f = $ee->innertext;
   endforeach;
   if($settings[0][ 'wpptu' ]=='c'){
      $tmp = farsinum(number_format((($f-32)*5/9), 0)).'<small>(°C)</small>';
   }
   if($settings[0][ 'wpptu' ]=='f'){
      $tmp = farsinum($f).'<small>(°F)</small>';
   }
   

   //Wind
	   $s = $html->find('s');
	   $t = $html->find('wind t');
	   if($s[0]->innertext=='calm'){
	      $wind = 'Calm';
	   }else{
	      $wind = farsinum($s[0]->innertext).'<small>(mph)</small>';//$t[0]->innertext;
	   }
   
   //Pressure
	   $bar = $html->find('bar r');
	   $prs = farsinum($bar[0]->innertext).'<small>(in)</small>';

   //Visibility
	   $vis = $html->find('vis');
	   $vs = farsinum($vis[0]->innertext).'<small>(mi)</small>';
	
   //UV
	   $uvi = $html->find('uv i');
	   $uvt = $html->find('uv t');
	   $uv = $uvi[0]->innertext.'-'.$uvt[0]->innertext;
	
   //Humidity
	   $hmid = $html->find('hmid');
	   $hm = farsinum($hmid[0]->innertext).'<small>(%)</small>';

   $src = $html->find('icon');
   if($settings[0][ 'wppico' ]=='52'){
	    $ico =  '<img src="http://s.imwx.com/v.20120328.084208/img/wxicon/52/'.$src[0]->innertext.'.png"/>';
   }
   if($settings[0][ 'wppico' ]=='120'){
	    $ico = '<img src="http://s.imwx.com/v.20120328.084208/img/wxicon/120/'.$src[0]->innertext.'.png"/>';
   }
 ?>
  <table>
    <tr class="tbrdr cname">
      <td><?php _e($cname[0], 'wpp-iran-weather'); ?></td>
      <td rowspan="2" class="img">
         <?php echo $ico; ?>
         <small>
         <?php
         	if($settings[0][ 'wppwht' ]=='1'){
         	$cc = $html->find('cc t');
         	//echo $cc[0]->innertext;
         	switch ($cc[0]->innertext){
         	   case "Sunny": _e('Sunny', 'wpp-iran-weather'); break;
         	   case "Fair": _e('Fair', 'wpp-iran-weather'); break;
         	   case "Partly Cloudy": _e('Partly Cloudy', 'wpp-iran-weather'); break;
         	   case "Mostly Sunny": _e('Mostly Sunny', 'wpp-iran-weather'); break;
         	   case "Cloudy": _e('Cloudy', 'wpp-iran-weather'); break;
         	   case "Scattered Showers": _e('Scattered Showers', 'wpp-iran-weather'); break;
         	   case "Showers": _e('Showers', 'wpp-iran-weather'); break;
         	   case "Few Showers": _e('Few Showers', 'wpp-iran-weather'); break;
         	   case "AM Clouds / PM Sun": _e('AM Clouds / PM Sun', 'wpp-iran-weather'); break;
         	   case "Snow Shower": _e('Snow Shower', 'wpp-iran-weather'); break;
         	   case "AM Snow Showers": _e('AM Snow Showers', 'wpp-iran-weather'); break;
         	   case "Scattered Snow Showers": _e('Scattered Snow Showers', 'wpp-iran-weather'); break;
	           case "Few Snow Showers": _e('Few Snow Showers', 'wpp-iran-weather'); break;
         	   case "Rain / Snow": _e('Rain / Snow', 'wpp-iran-weather'); break;
	           case "AM Snow": _e('AM Snow', 'wpp-iran-weather'); break;
         	   case "Isolated T-Storms": _e('Isolated T-Storms', 'wpp-iran-weather'); break;
         	   case "T-Showers": _e('T-Showers', 'wpp-iran-weather'); break;
         	   case "Light Snow Shower": _e('Light Snow Shower', 'wpp-iran-weather'); break;
         	   case "Rain / Snow Showers": _e('Rain / Snow Showers', 'wpp-iran-weather'); break;
         	   case "Mostly Sunny": _e('Mostly Sunny', 'wpp-iran-weather'); break;
         	   case "Light Rain / Wind": _e('Light Rain / Wind', 'wpp-iran-weather'); break;
	           case "AM Showers / Wind": _e('AM Showers / Wind', 'wpp-iran-weather'); break;
         	   case "Partly Cloudy / Wind": _e('Partly Cloudy / Wind', 'wpp-iran-weather'); break;
         	   case "Rain": _e('Rain', 'wpp-iran-weather'); break;
	           case "PM T-Storms": _e('PM T-Storms', 'wpp-iran-weather'); break;
	           case "Scattered T-Storms": _e('Scattered T-Storms', 'wpp-iran-weather'); break;
	           case "Rain Late": _e('Rain Late', 'wpp-iran-weather'); break;
	           case "PM Showers": _e('PM Showers', 'wpp-iran-weather'); break;
         	   case "PM Snow": _e('PM Snow', 'wpp-iran-weather'); break;
         	   case "Mist": _e('Mist', 'wpp-iran-weather'); break;
         	   case "Mist": _e('Haze', 'wpp-iran-weather'); break;
               default: echo $cc[0]->innertext;
         	}
         	}
         ?>
         </small>
      </td>
    </tr>
    <tr class="tbrdr">
      <td class="tmp"><?php echo $tmp; ?></td>
    </tr>
    <?php if($settings[0][ 'wppwnd' ]=='1'){ ?>
    <tr>
      <td><?php _e('Wind', 'wpp-iran-weather'); ?></td>
      <td><?php if($wind=='Calm'){_e('Calm', 'wpp-iran-weather');}else{echo $wind;} ?></td>
    </tr>
    <?php }
    if($settings[0][ 'wppprs' ]=='1'){ ?>
    <tr>
      <td><?php _e('Pressure', 'wpp-iran-weather'); ?></td>
      <td><?php echo $prs; ?></td>
    </tr>
    <?php }
    if($settings[0][ 'wppvs' ]=='1'){ ?>
    <tr>
      <td><?php _e('Visibility', 'wpp-iran-weather'); ?></td>
      <td><?php echo $vs; ?></td>
    </tr>
    <?php }
    if($settings[0][ 'wppuv' ]=='1'){ ?>
    <tr>
      <td><?php _e('UV Index', 'wpp-iran-weather'); ?></td>
      <td><?php echo $uv; ?></td>
    </tr>
    <?php }
    if($settings[0][ 'wpphm' ]=='1'){ ?>
    <tr>
      <td><?php _e('Humidity', 'wpp-iran-weather'); ?></td>
      <td><?php echo $prs; ?></td>
    </tr>
    <?php } ?>
  </table>
<?php  } 


public function hello_world_ajax_frontend(){
	
 	$dummy = new WPPIW_Widget();
    $settings = array_values(array_filter($dummy->get_settings()));
   ?>
<div class="whtr">
<div id="whtr">
<?php WPPIW_Widget::wppiw_action_function(); ?>
</div><!--#whtr-->
<?php
   	      if($settings[0][ 'wppcl' ]=='1'){
   	         echo '<form id="whtrform"><div class="cities">';include('includes/cities.php');echo '</div><input name="action" type="hidden" value="wppiw_ajax_hook" /></form>';
   	      }
   	   echo '</div>';
}


   function widget($args,$instance) {
   	      WPPIW_Widget::hello_world_ajax_frontend();
   }
}//CLASS

function wppiw_load_widgets() {
   register_widget('WPPIW_Widget');
}
add_action('widgets_init', 'wppiw_load_widgets');


function add_shortcode_whtr( $atts ) {
 	$dummy = new WPPIW_Widget();
    $settings = $dummy->get_settings();
   ?>
<div class="whtr">
<div id="whtr">
<?php WPPIW_Widget::wppiw_action_function(); ?>
</div><!--#whtr-->
<?php
   	      if($settings[0][ 'wppcl' ]=='1'){
   	         echo '<form id="whtrform"><div class="cities">';include('includes/cities.php');echo '</div><input name="action" type="hidden" value="wppiw_ajax_hook" /></form>';
   	      }
   	   echo '</div>';
}
add_shortcode('wppwhtr', 'add_shortcode_whtr');
?>