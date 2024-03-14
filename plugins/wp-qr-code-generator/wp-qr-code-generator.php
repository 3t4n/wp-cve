<?php
/*
Plugin Name: WP QR Code Generator
Plugin URI: https://www.startbitsolutions.com
Description: An easy way to add your QR Code widget in your sidebars and add in your page .
Version: 1.6
Author URI: https://www.startbitsolutions.com
* Author Email: support@startbitsolutions.com
Requires at least: 3.0
Text Domain: WP-QR-Code-Generator
License: http://www.gnu.org/licenses/gpl-2.0.html
*/
/* Copyright 2014,2015,2016  Startbit IT Solutions Pvt. Ltd.  (email : support@startbitsolutions.com)

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
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
add_filter('plugin_row_meta', 'viqcg_RegisterPluginLinks_qr',10, 2);
function viqcg_RegisterPluginLinks_qr($links, $file) {
	if ( strpos( $file, 'wp-qr-code-generator.php' ) !== false ) {
		$links[] = '<a href="https://wordpress.org/plugins/wp-qr-code-generator/faq/">FAQ</a>';
		$links[] = '<a href="mailto:support@startbitsolutions.com">Support</a>';
	}
	return $links;
}

add_action('admin_enqueue_scripts','viqcg_admin_jquery_link');
add_action('wp_enqueue_scripts','viqcg_front_jquery_link');
add_shortcode('vqr','viqcg_qr_shortcode');

function viqcg_admin_jquery_link($hook_suffix){
 wp_enqueue_style( 'wp-color-picker' );
 wp_enqueue_script( 'admin_script', plugins_url('/js/admin_script.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}
 
function viqcg_front_jquery_link(){
wp_enqueue_script( 'WP-QR-Code-Generator-js', plugins_url( 'qrcode.js' , __FILE__ ));
}
                                                                                                                                                                                                                   
function viqcg_qr_shortcode($attr,$cont=null){
extract(shortcode_atts( array(
		'msg' => " WP QR Code Generator",
		'size' => 160,
      'color_Light' => "#c1c1c1",
		'level' => "Q"
	 	), $attr ));
		$r=rand(0,9999);
return '<div id="vqr'.$r.'"></div>
<script type="text/javascript">
new QRCode(document.getElementById("vqr'.$r.'"), {
	text: "'.$msg.'",
	width: '.$size.',
	height: '.$size.',
	colorDark : "#222222",
	colorLight : "'.$color_Light.'",
	correctLevel : QRCode.CorrectLevel.'.$level.'
});
</script>';

}
add_action( 'init', 'viqcg_qrcode_submit' );
function viqcg_qrcode_submit() {
    add_filter( "mce_external_plugins", "viqcg_qr_getvalue" );
    add_filter( 'mce_buttons', 'viqcg_registration_qr' );
}
function viqcg_qr_getvalue( $plugin_array ) {
    $plugin_array['vqr'] = plugins_url( 'js/WP-QR-Code-Generator.js' , __FILE__ );
    return $plugin_array;
}
function viqcg_registration_qr( $buttons ) {
    array_push( $buttons,'qrcode_submitvalue' );
    return $buttons;
}
class qrcode_Widget extends WP_Widget {
	
	public function __construct() {
		parent::__construct(
	 		'qrcode_Widget', 
			'WP QRcode Widget', 
			array( 'description' =>  ' QRcode Generator widget' )
		);
	}

	public function widget( $args, $input_value ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $input_value['title'] );
		$text = $input_value[ 'text' ];
		$size = $input_value[ 'size' ];
                $color_Light = $input_value[ 'color_Light' ];
		$level = $input_value[ 'level' ];
		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
		echo '<div id="getvalue"></div>
<script type="text/javascript">
new QRCode(document.getElementById("getvalue"), {
	text: "'.$text.'",
	width: '.$size.',
	height: '.$size.',
	colorDark : "#222222",
	colorLight : "'.$color_Light.'",
	correctLevel : QRCode.CorrectLevel.'.$level.'
});

   
</script>';

		echo $after_widget;
	}

   
 	public function form( $input_value ) {
		$text = isset($input_value[ 'text' ])?$input_value[ 'text' ]:"Enter TEXT";
		$size = isset($input_value[ 'size' ])?$input_value[ 'size' ]:"150";
		$title = isset($input_value[ 'title' ])?$input_value[ 'title' ]:" QR Code Generator";
      $color_Light = isset($input_value[ 'color_Light' ])?$input_value[ 'color_Light' ]:"#c1c1c1";
		$level =  isset($input_value[ 'level' ])?$input_value[ 'level' ]:"";
		$page_data =  isset($input_value[ 'page_date' ])?$input_value[ 'page_date' ]:""; 
		$post_data =  isset($input_value[ 'post_data' ])?$input_value[ 'post_data' ]:"";  
		?>
		<p>
		<label for="<?php echo sanitize_text_field($this->get_field_id( 'title' )); ?>">TITLE:</label> <br/>
		<input class="block_text" id="<?php echo sanitize_text_field($this->get_field_id( 'title' )); ?>" name="<?php echo sanitize_text_field($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo sanitize_text_field( esc_attr( $title )); ?>" /><br/>
		<label for="<?php echo sanitize_text_field($this->get_field_id( 'text' )); ?>">TEXT:</label> <br/>
		<textarea class="block_text" id="<?php echo sanitize_text_field($this->get_field_id( 'text' )); ?>" name="<?php echo sanitize_text_field($this->get_field_name( 'text' )); ?>" ><?php echo sanitize_text_field(esc_attr( $text )); ?></textarea><br/>
		<label for="<?php echo sanitize_text_field($this->get_field_id( 'size' )); ?>">SIZE:</label> <br/>
		<input class="block_text" id="<?php echo sanitize_text_field($this->get_field_id( 'size' )); ?>" name="<?php echo sanitize_text_field($this->get_field_name( 'size' )); ?>" type="text" value="<?php echo sanitize_text_field(esc_attr( $size )); ?>" /><br/>
      <label for="<?php echo sanitize_text_field($this->get_field_id( 'color_Light' )); ?>">BACKGROUND COLOR:</label> <br/>
		<input class="color_Light intentColor" id="<?php echo sanitize_text_field($this->get_field_id( 'color_Light' )); ?>" name="<?php echo sanitize_text_field($this->get_field_name( 'color_Light' )); ?>" type="text" value="<?php echo sanitize_text_field(esc_attr( $color_Light )); ?>" data-default-color="<?php echo sanitize_text_field( esc_attr( $color_Light )); ?>"/><br/>
		<label for="<?php echo $this->get_field_id( 'level' ); ?>">LEVEL:</label> <br/>
		
	<select class="block_text" id="<?php echo sanitize_text_field($this->get_field_id( 'level' )); ?>" name="<?php echo sanitize_text_field($this->get_field_name( 'level' )); ?>">
	<option value="L" <?php if($level=='L' ) echo 'selected'; ?> >L</option>
	<option value="M" <?php if($level=='M') echo 'selected'; ?>>M</option>
	<option value="H" <?php if($level=='H' ) echo 'selected'; ?>>H</option>
	<option value="Q" <?php if($level=='Q' || $level == ''  ) echo 'selected'; ?>>Q</option>
	
	</select></br>
	<label for="<?php echo $this->get_field_id( 'page' ); ?>">Page:</label> <br/>
	 <input type="checkbox" class="widefat"
           id="<?php echo sanitize_text_field($this->get_field_id( 'page' )); ?>"
           name="<?php echo sanitize_text_field($this->get_field_name( 'page' )); ?>"
           value="1"  <?php if($page_data==1 ) echo 'checked'; ?>/></br>
	<label for="<?php echo $this->get_field_id( 'post' ); ?>">Post:</label> <br/>
	 <input type="checkbox" class="widefat"
           id="<?php echo sanitize_text_field($this->get_field_id( 'post' )); ?>"
           name="<?php echo sanitize_text_field($this->get_field_name( 'post' )); ?>"
           value="1" <?php if($post_data==1 ) echo 'checked'; ?> />
		</p>
		<?php 
	}

	public function update( $new_inputvalue, $old_inputvalue ) {
		$input_value = array();
		$input_value['title'] = strip_tags( $new_inputvalue['title'] );
		$input_value['text'] = strip_tags( $new_inputvalue['text'] );
		$input_value['size'] = strip_tags( $new_inputvalue['size'] );
      $input_value['color_Light'] = strip_tags( $new_inputvalue['color_Light'] );
		$input_value['level'] = strip_tags( $new_inputvalue['level'] );
		$input_value['page_date'] = strip_tags( $new_inputvalue['page'] );
		$input_value['post_data'] = strip_tags( $new_inputvalue['post'] );
		return $input_value;
	}
    
}
add_action( 'widgets_init', create_function( '', 'register_widget( "qrcode_Widget" );' ) );

function qrrcode_pp(){
   
   	 echo '<div id="demo"></div>'; 
   	
   	foreach(get_option('widget_qrcode_widget') as $d){
   		if(isset($d['title'])){
		       $size1= $d['size'];
		       $color_Light=['color_Light'];
		       $level = ['level'];
		   	echo  '<script type="text/javascript">
		   	
		    jQuery("#demo").qrcode({
		    //render:"table"
		    width: "'.$size1.'",
		    height: "'.$size1.'",
		    colorDark : "#222222",
			 colorLight : "'.$color_Light.'",
			 
		    text: "'.get_page_link(get_the_id()).'"
		});
		   	</script>';
       }//if condition
   	} //forloop
   	
 }
 
add_action( "add_meta_boxes", "se20892273_add_meta_boxes_page" );

// Register Your Meta box
function se20892273_add_meta_boxes_page( $post )
{
		foreach(get_option('widget_qrcode_widget') as $d){
   		if($d['page_date'] == 1){
		 
		    add_meta_box( 
		       'se20892273_custom_meta_box', // this is HTML id
		       'Metabox Title', 
		       'qrrcode_pp', // the callback function
		       'page', // register on post type = page
		       'side', // 
		       'core'
		    );
		  } //if
		  if($d['post_data'] ==1 ){
		    add_meta_box( 
		       'se2089227_custom_meta_box', // this is HTML id
		       'Metabox Titlebcxbcx', 
		       'qrrcode_pp', // the callback function
		       'post', // register on post type = page
		       'side', // 
		       'core'
		    );
		    }// if
		 }
   
}
function qr_script(){
 wp_enqueue_script( 'bootstrap-js', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.qrcode/1.0/jquery.qrcode.min.js', array( 'jquery' ) );   
}    
add_action( 'init', 'qr_script' );

?>
