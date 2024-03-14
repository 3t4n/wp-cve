<?php
/*
 * CF7 Advance Security(C)
 * @get_cf7as_sidebar_options()
 * @get_cf7as_sidebar_content()
 * 
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
// Get plugin options
$isEnable=get_option('cf7as_captcha');
/** Start Captcha Code */
if( !empty($isEnable) ) {

	add_action( 'wpcf7_init', 'cf7as_shortcodes_capctha' );	
	
	add_filter( 'wpcf7_validate_cf7ascaptcha', 'cf7as_captcha_confirmation_validation_filter', 20, 2 );
	
	add_filter( 'wpcf7_validate_cf7ascaptcha*', 'cf7as_captcha_confirmation_validation_filter', 20, 2 );
	
	if( !function_exists('cf7as_captcha_confirmation_validation_filter') ) :
	
		function cf7as_captcha_confirmation_validation_filter( $result, $tag ) {
		    
			//check captcha type
			if( $tag->type=='cf7ascaptcha' ) {
				
				$tag->name= 'cf7as-captchcode';
				$finalCechking = '';
				$cptha1=sanitize_text_field($_POST['cf7as_hdn_cpthaval1']);
				$cptha2=sanitize_text_field($_POST['cf7as_hdn_cpthaval2']);
				$cptha3=sanitize_text_field($_POST['cf7as_hdn_cpthaaction']);
				
						
		        $required = isset( $tag->values[2] ) ? $tag->values[2] : 'Invalid Answer!';
				
				if( $cptha3=='x' ) { 
					$finalCechking=( $cptha1*$cptha2 );
				}elseif( $cptha3=='+' ) { 
					$finalCechking=( $cptha1+$cptha2 );
				}else {
					$finalCechking=( $cptha1-$cptha2 );
					}
					
				$cptcha_value = isset( $_POST['cf7as-captchcode'] )	? trim( wp_unslash( strtr( (string) $_POST['cf7as-captchcode'], "\n", " " ) ) )	: '';
				
				if( $cptcha_value=='' ) {
					$result->invalidate($tag,$required);
				}
				
				if( $cptcha_value!='' && $cptcha_value!=$finalCechking ) {
					
					$result->invalidate($tag,$required);
				 }
				 //check double security
				 $cptcha_value = isset( $_POST['cf7as-zplus'] )	? sanitize_text_field($_POST['cf7as-zplus'])	: '';
				 
				 if( $cptcha_value!='' ) {
					$result->invalidate($tag,'You are not human!');
				  }
			}
			
			return $result;
     }
  endif;
}
/** captcha */
if( !function_exists('cf7as_shortcodes_capctha') ) :

 function cf7as_shortcodes_capctha() {
     
		/*	wpcf7_add_form_tag(
				'cf7ascaptcha1',
				'cf7as_captcha_shortcode_handler', 
				array( 
					'name-attr' => true 
					)
			);*/
 }
endif;


add_action( 'wpcf7_init', 'custom_add_form_tag_time_selector' );
 
function custom_add_form_tag_time_selector() {
    wpcf7_add_form_tag( 
        'cf7ascaptcha', 
        'cf7as_captcha_shortcode_handler'
        ); 
}

if( !function_exists('cf7as_captcha_shortcode_handler') ) :

	function cf7as_captcha_shortcode_handler( $tag ) {
		
		$title = isset( $tag->values[0] ) ? $tag->values[0] : 'What is ';
		
		$placeholder = isset( $tag->values[1] ) ? $tag->values[1] : 'Type your answer';

		$operationAry=array('+','x','-');
		$random_action=array_rand($operationAry,2);
		$random_actionVal=$operationAry[$random_action[0]];
		$actnVal1=rand(1,9);
		$actnVal2=rand(1,9);
		$cf7as_captcha='<p class="cf7ascaptcha"><input name="cf7as_hdn_cpthaval1" id="cf7as_hdn_cpthaval1" type="hidden" value="'.$actnVal1.'" /><input name="cf7as_hdn_cpthaval2" id="cf7as_hdn_cpthaval2" type="hidden" value="'.$actnVal2.'" /><input name="cf7as_hdn_cpthaaction" id="cf7as_hdn_cpthaaction" type="hidden" value="'.$random_actionVal.'" />';
		$cf7as_captcha.=$title.' <span class="cf7as-firstAct">'.$actnVal2.'</span> '.$random_actionVal.'<span class="cf7as-firstAct"> '.$actnVal1.'</span> <br><span class="wpcf7-form-control-wrap cf7as-captchcode" data-name="cf7as-captchcode"> <input type="text" aria-invalid="false" aria-required="true" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" size="5" value="" name="cf7as-captchcode" placeholder="'.$placeholder.'" style="width:200px;margin-bottom:10px;" oninput="this.value = this.value.replace(/[^0-9.]/g, \'\').replace(/(\..*)\./g, \'$1\');"></span><input type="hidden" name="cf7as-zplus" value=""></p>';
		

		return $cf7as_captcha;
	}
endif;
/** End Captcha Code */

add_action( 'wpcf7_admin_init', 'cf7as_add_tag_generator_button', 55, 0 );

function cf7as_add_tag_generator_button() {
	$tag_generator = WPCF7_TagGenerator::get_instance();
	$tag_generator->add( 'cf7ascaptcha', __( 'cf7ascaptcha', 'contact-form-7' ),
		'cf7as_tag_generator_cf7ascaptcha', array( 'nameless' => 1 ) );
}

function cf7as_tag_generator_cf7ascaptcha( $contact_form, $args = '' ) {
	$args = wp_parse_args( $args, array() );

	$description = __( "Generate a form advance secuirty captcha", 'contact-form-7' );
	
	$desc_link = 'https://www.wp-experts.in';

?>
<div class="control-box">
<fieldset>
<legend><?php echo sprintf( esc_html( $description ), $desc_link ); ?></legend>

<table class="form-table">
<tbody>
	<tr>
	<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-values' ); ?>"><?php echo esc_html( __( 'Title', 'contact-form-7' ) ); ?></label></th>
	<td><input type="text" name="values" class="oneline" id="<?php echo esc_attr( $args['content'] . '-values' ); ?>" /><label></td>
	</tr>
</tbody>
</table>
<p>&nbsp;</p>
<em>Sample Shortcode </br> <b>[cf7ascaptcha "What is your answer" "enter answer" "invalid answer"]</b></em>

</fieldset>
</div>

<div class="insert-box">
	<input type="text" name="cf7ascaptcha" class="tag code" readonly="readonly" onfocus="this.select()" />
	<div class="submitbox">
	<input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __( 'Insert Captcha', 'contact-form-7' ) ); ?>" />
	</div>
</div>
<?php
}

/** form css */
add_action('wp_enqueue_scripts','add_cf7as_inline_style');

if( !function_exists('add_cf7as_inline_style') ) :
	function add_cf7as_inline_style() {
		
		$inlinecss = get_option('cf7as-inlinecss');

	     // register css  
		 wp_register_style( 'cf7as-inlinecss', false );
		 wp_enqueue_style( 'cf7as-inlinecss' );
		 wp_add_inline_style( 'cf7as-inline-css', $inlinecss );
	}
endif;