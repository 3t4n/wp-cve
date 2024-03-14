<?php
/*
Plugin Name: Contact Form 7 Star Rating
Plugin URI: http://www.themelogger.com/contact-form-7-star-rating-plugin/
Description: Contact Form 7 Star Rating
Author: themelogger.com
Author URI: http://www.themelogger.com/
Version: 1.10
*/

/*  Copyright 2014 themelogger.com (email: support at jqhelp.com)

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

add_action('plugins_loaded', 'contact_form_7_starrating_fields', 11);
function contact_form_7_starrating_fields() {
	global $pagenow;	
        
    if( defined('WPCF7_VERSION') )  {

        $options = get_option( 'starrating' );
        if(!isset($options) || $options=='') {            
            $options = array('load_starrating_js'=>1,'load_starrating_css'=>1) ;
        }

        if (version_compare(WPCF7_VERSION, '4.5.0','>=')) {
            wpcf7_add_form_tag( 'starrating', 'wpcf7_starrating_shortcode_handler', true );
            wpcf7_add_form_tag( 'starrating*', 'wpcf7_starrating_shortcode_handler', true );                
        } else {
            wpcf7_add_shortcode( 'starrating', 'wpcf7_starrating_shortcode_handler', true );
            wpcf7_add_shortcode( 'starrating*', 'wpcf7_starrating_shortcode_handler', true );
        }
        
        if($options['load_starrating_js']==1) {
            add_action( 'wpcf7_enqueue_scripts', 'wpcf7_enqueue_scripts_starrating' );
        }
        
        add_action( 'wpcf7_enqueue_scripts', 'wpcf7_enqueue_scripts_starrating2' );
        
        if($options['load_starrating_css']==1) {
            add_action( 'wpcf7_enqueue_styles', 'wpcf7_enqueue_styles_starrating' );            
        }
        
        add_action( 'wpcf7_enqueue_styles', 'wpcf7_enqueue_styles_starrating2' );
	} 
}

function wpcf7_enqueue_scripts_starrating2() {

	$in_footer = true;
	if ( 'header' === WPCF7_LOAD_JS )
		$in_footer = false;

	wp_enqueue_script( 'jquery-wpcf7-starrating',
		plugin_dir_url( __FILE__ ).'js/jquery.wpcf7-starrating.js',
            array( 'jquery'), '1.5' , $in_footer );    
}

function wpcf7_enqueue_scripts_starrating() {

	$in_footer = true;
	if ( 'header' === WPCF7_LOAD_JS )
		$in_footer = false;

	wp_enqueue_script( 'jquery-rating',
		plugin_dir_url( __FILE__ ).'jquery.rating/jquery.rating.js' ,
            array( 'jquery'), '4.11' , $in_footer );
    
}


function wpcf7_enqueue_styles_starrating() {
	wp_enqueue_style( 'jquery-rating-style',
		plugin_dir_url( __FILE__ ).'jquery.rating/jquery.rating.css' ,
		array(), '4.11' );
}


function wpcf7_enqueue_styles_starrating2() {
	wp_enqueue_style( 'jquery-wpcf7-rating-style',
		plugin_dir_url( __FILE__ ).'css/jquery.wpcf7-starrating.css' ,
		array(), '1.5' );
}


add_filter( 'wpcf7_validate_starrating*', 'wpcf7_starrating_validation_filter', 10, 2 );
function wpcf7_starrating_validation_filter( $result, $tag ) {
	$name = $tag->name;
	$value = isset( $_POST[$name] ) ? intval($_POST[$name]) : 0;
    $min = $tag->get_option( 'min', 'signed_int', true );        
    if ( $tag->is_required() && $min!=0 && $value==0 ) {        
        $result->invalidate( $tag, wpcf7_get_message( 'invalid_required' ) );
    }
	return $result;
}

/* Shortcode handler */
function wpcf7_starrating_shortcode_handler( $tag ) {
    if (version_compare(WPCF7_VERSION, '4.5.0','>=')) {       
        $tag = new WPCF7_FormTag($tag);
    } else {
        if ( ! is_array( $tag ) ) {
            return '';
        }             
        $tag = new WPCF7_Shortcode( $tag );        
    }
                
	$atts = array();
    $class = wpcf7_form_controls_class( $tag->type );
            
    $name = isset($tag->name) ? $tag->name : $tag->type ;
    
	$atts['class'] = $tag->get_class_option( $class );
	$atts['id'] = $tag->get_option( 'id', 'id', true );	
	$atts['min'] = $tag->get_option( 'min', 'signed_int', true );
	$atts['max'] = $tag->get_option( 'max', 'signed_int', true );
	$atts['step'] = $tag->get_option( 'step', 'int', true );
    $show_number = $tag->has_option( 'show_number' ) ;
    $disable_cancel = $tag->has_option( 'disable_cancel' ) ? ' data-cancel="1" ' : ' data-cancel="0" ';    
    
    $validation_error = wpcf7_get_validation_error( $tag->name );    
    
	if ( $tag->is_required() ) {
		$atts['aria-required'] = 'true';
	}    
    
	$atts['aria-invalid'] = $validation_error ? 'true' : 'false';    

    $def = 0 ;
    if(isset($tag->values) && is_array($tag->values) && count($tag->values)>0) {
        $def = $tag->values[0];
    }
           
    $atts['class'] .= ' starrating' ;
    
	$atts['min'] = $atts['min'] ? $atts['min'] : 1 ;
	$atts['max'] = $atts['max'] ? $atts['max'] : 10 ;
	$atts['step'] = $atts['step'] ? $atts['step'] : 1 ;
    $str_id = $atts['id'] ? 'id="'.$atts['id'].'"' : '' ;
            
    $html = '' ;
    $html .= '<span '.$str_id.' class="wpcf7-form-control-wrap '. $name.' '.$atts['class'].'" data-def="'.$def.'" '.$disable_cancel.'>';
    for( $i=$atts['min']; $i<=$atts['max']; $i+=$atts['step'] ) {
        $html .= '<input type="radio" name="'.$name.'" value="'.$i.'"/>';    
    }
    if($show_number) {
         $html .= '<span class="starrating_number" >'.$def.'</span>' ;
    }    
    $html .= $validation_error.'</span>' ;
        
    return $html ;
}



/* Tag generator */
if ( is_admin() ) {
    add_action( 'admin_init', 'wpcf7_add_tag_generator_starrating', 30 );
    function wpcf7_add_tag_generator_starrating() {              
        if(function_exists('wpcf7_add_tag_generator')) {            
            if (version_compare(WPCF7_VERSION, '4.2.0','>=')) {       
                $tag_generator = WPCF7_TagGenerator::get_instance();
                $tag_generator->add( 'starrating', 'starrating','wpcf7_tg_pane_starrating' );
            } else {
                wpcf7_add_tag_generator( 'starrating', __( 'Star Rating', 'wpcf7' ), 'wpcf7-tg-pane-starrating', 'wpcf7_tg_pane_starrating_old' );
            }
        }
    }
}

function wpcf7_tg_pane_starrating( $contact_form, $args = '' ) {

	$args = wp_parse_args( $args, array() );
    $type = 'starrating';    
?>
<div class="control-box">
<fieldset>

<table class="form-table">
<tbody>
	<tr>
	<th scope="row"><?php echo esc_html( __( 'Field type', 'contact-form-7' ) ); ?></th>
	<td>
		<fieldset>
		<legend class="screen-reader-text"><?php echo esc_html( __( 'Field type', 'contact-form-7' ) ); ?></legend>
		<label><input type="checkbox" name="required" /> <?php echo esc_html( __( 'Required field', 'contact-form-7' ) ); ?></label>
		</fieldset>
	</td>
	</tr>

	<tr>
	<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-name' ); ?>"><?php echo esc_html( __( 'Name', 'contact-form-7' ) ); ?></label></th>
	<td><input type="text" name="name" class="tg-name oneline" id="<?php echo esc_attr( $args['content'] . '-name' ); ?>" /></td>
	</tr>

	<tr>
	<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-values' ); ?>"><?php echo esc_html( __( 'Default value', 'contact-form-7' ) ); ?></label></th>
	<td><input type="text" name="values" class="oneline" id="<?php echo esc_attr( $args['content'] . '-values' ); ?>" /></label></td>
	</tr>

	<tr>
	<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-id' ); ?>"><?php echo esc_html( __( 'Id attribute', 'contact-form-7' ) ); ?></label></th>
	<td><input type="text" name="id" class="idvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-id' ); ?>" /></td>
	</tr>

	<tr>
	<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-class' ); ?>"><?php echo esc_html( __( 'Class attribute', 'contact-form-7' ) ); ?></label></th>
	<td><input type="text" name="class" class="classvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-class' ); ?>" /></td>
	</tr>

    
	<tr>
	<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-min' ); ?>"><?php echo esc_html( __( 'min', 'contact-form-7' ) ); ?></label></th>
	<td><input type="text" name="min" class="numeric oneline option" id="<?php echo esc_attr( $args['content'] . '-min' ); ?>" /></td>
	</tr>

	<tr>
	<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-max' ); ?>"><?php echo esc_html( __( 'max', 'contact-form-7' ) ); ?></label></th>
	<td><input type="text" name="max" class="numeric oneline option" id="<?php echo esc_attr( $args['content'] . '-max' ); ?>" /></td>
	</tr>
 
	<tr>
	<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-step' ); ?>"><?php echo esc_html( __( 'step', 'contact-form-7' ) ); ?></label></th>
	<td><input type="text" name="step" class="numeric oneline option" id="<?php echo esc_attr( $args['content'] . '-step' ); ?>" /></td>
	</tr>
  
	<tr>
	<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-disable_cancel' ); ?>"><?php echo esc_html( __( 'Cancel button', 'contact-form-7' ) ); ?></label></th>
	<td><label><input type="checkbox" name="disable_cancel" class="option" /> <?php echo esc_html( __( 'Disable cancel button', 'contact-form-7' ) ); ?></td>
	</tr>
    
	<tr>
	<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-show_number' ); ?>"><?php echo esc_html( __( 'Show number', 'contact-form-7' ) ); ?></label></th>
	<td><label><input type="checkbox" name="show_number" class="option" /> <?php echo esc_html( __( 'Show number', 'contact-form-7' ) ); ?></td>
	</tr>
        
</tbody>
</table>
</fieldset>
</div>

<div class="insert-box">
	<input type="text" name="<?php echo $type; ?>" class="tag code" readonly="readonly" onfocus="this.select()" />

	<div class="submitbox">
	<input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __( 'Insert Tag', 'contact-form-7' ) ); ?>" />
	</div>

	<br class="clear" />

	<p class="description mail-tag"><label for="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>"><?php echo sprintf( esc_html( __( "To use the value input through this field in a mail field, you need to insert the corresponding mail-tag (%s) into the field on the Mail tab.", 'contact-form-7' ) ), '<strong><span class="mail-tag"></span></strong>' ); ?><input type="text" class="mail-tag code hidden" readonly="readonly" id="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>" /></label></p>
</div>


<?php
}


function wpcf7_tg_pane_starrating_old( $type = 'starrating' ) {

	if ( ! in_array( $type, array() ) )
		$type = 'starrating';

?>
<div id="wpcf7-tg-pane-<?php echo $type; ?>" class="hidden">
<form action="">
<table>
<tr><td><input type="checkbox" name="required" />&nbsp;<?php echo esc_html( __( 'Required field?', 'contact-form-7' ) ); ?></td></tr>
<tr><td><?php echo esc_html( __( 'Name', 'contact-form-7' ) ); ?><br /><input type="text" name="name" class="tg-name oneline" /></td><td></td></tr>
</table>

<table>
<tr>
<td><code>id</code> (<?php echo esc_html( __( 'optional', 'contact-form-7' ) ); ?>)<br />
<input type="text" name="id" class="idvalue oneline option" /></td>

<td><code>class</code> (<?php echo esc_html( __( 'optional', 'contact-form-7' ) ); ?>)<br />
<input type="text" name="class" class="classvalue oneline option" /></td>
</tr>

<tr>
<td><code>min</code> (<?php echo esc_html( __( 'optional', 'contact-form-7' ) ); ?>)<br />
<input type="number" name="min" class="numeric oneline option" />

</td>

<td><code>max</code> (<?php echo esc_html( __( 'optional', 'contact-form-7' ) ); ?>)<br />
    <input type="number" name="max" class="numeric oneline option" />
</td>

</tr>

<tr>
<td><code>step</code> (<?php echo esc_html( __( 'optional', 'contact-form-7' ) ); ?>)<br />
<input type="number" name="step" class="numeric oneline option" min="1" /></td>

<td><br />
    <input type="checkbox" name="disable_cancel" class="option" />&nbsp;<?php echo esc_html( __( 'Disable cancel button', 'contact-form-7' ) ); ?>
</td>  

</tr>

<tr>
<td><?php echo esc_html( __( 'Default value', 'contact-form-7' ) ); ?> (<?php echo esc_html( __( 'optional', 'contact-form-7' ) ); ?>)<br /><input type="text" name="values" class="oneline" />
</td>
<td><br />
    <input type="checkbox" name="show_number" class="option" />&nbsp;<?php echo esc_html( __( 'Show number', 'contact-form-7' ) ); ?>
</td>  

</tr>

</table>

<div class="tg-tag"><?php echo esc_html( __( "Copy this code and paste it into the form left.", 'contact-form-7' ) ); ?><br /><input type="text" name="<?php echo $type; ?>" class="tag wp-ui-text-highlight code" readonly="readonly" onfocus="this.select()" /></div>

<div class="tg-mail-tag"><?php echo esc_html( __( "And, put this code into the Mail fields below.", 'contact-form-7' ) ); ?><br /><input type="text" class="mail-tag wp-ui-text-highlight code" readonly="readonly" onfocus="this.select()" /></div>
</form>
</div>
<?php
}



class StarratingSettingsPage
{
    private $options;
    private $option_name;
    private $page;

    /**
     * Start up
     */
    public function __construct()
    {
        $this->option_name = 'starrating' ;        
        $this->page = 'starrating-setting-admin' ; 
        $this->group = 'starrating-option-group' ;
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'Star rating settings', 
            'manage_options', 
             $this->page, 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( $this->option_name );  
        ?>
        <div class="wrap">
            <?php screen_icon(); ?>
            <h2>Star rating settings</h2>           
            <form method="post" action="options.php">
            <?php                
                settings_fields(  $this->group );   
                do_settings_sections( $this->page );
                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            $this->group,
            $this->option_name,
            array( $this, 'sanitize' )
        );

        add_settings_section(
            'setting_section_id',
            'Star Rating Custom Settings',
            array( $this, 'print_section_info' ),
            $this->page 
        );  

        add_settings_field(
            'load_starrating_js',
            'Load Star Rating JS',
            array( $this, 'load_starrating_js_callback' ),
            $this->page,
            'setting_section_id'
        );      

        add_settings_field(
            'load_starrating_css', 
            'Load Star Rating CSS', 
            array( $this, 'load_starrating_css_callback' ), 
            $this->page, 
            'setting_section_id'
        );      
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['load_starrating_css'] ) )
            $new_input['load_starrating_css'] = absint( $input['load_starrating_css'] );

        if( isset( $input['load_starrating_js'] ) )
            $new_input['load_starrating_js'] = absint( $input['load_starrating_js'] );            

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function load_starrating_js_callback()
    {
        $k = 'load_starrating_js' ;
        $v = isset( $this->options[$k] ) ? esc_attr( $this->options[$k]) : 1 ;
        echo $this->yesno($this->option_name."[".$k."]",null,$v) ;
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function load_starrating_css_callback()
    {
        $k = 'load_starrating_css' ;
        $v = isset( $this->options[$k] ) ? esc_attr( $this->options[$k]) : 1 ;
        echo $this->yesno($this->option_name."[".$k."]",null,$v) ;
    }
    
    
    function yesno($name,$attribs = null,$selected)
    {
        $list = array() ;
        $data = new stdClass;       
        $data->text = 'No'  ;
        $data->value = 0  ;
        $list[] =  $data ;
        $data = new stdClass;       
        $data->text = 'Yes'  ;
        $data->value = 1  ;
        $list[] =  $data ;
        return $this->genericlist($list,$name,$attribs,'value','text', $selected) ;       
    }
        
	function genericlist( $arr, $name, $attribs = null, $key = 'value', $text = 'text', $selected = NULL, $idtag = false, $translate = false )
	{
		if ( is_array( $arr ) ) {
			reset( $arr );
		}

		$id = $name;

		if ( $idtag ) {
			$id = $idtag;
		}

		$id		= str_replace('[','',$id);
		$id		= str_replace(']','',$id);
        
		$html	= '<select name="'. $name .'" id="'. $id .'" '. $attribs .'>';
		$html	.= $this->options( $arr, $key, $text, $selected, $translate );
		$html	.= '</select>';

		return $html;
	}     
        
	function options( $arr, $key = 'value', $text = 'text', $selected = null, $translate = false )
	{
		$html = '';

		foreach ($arr as $i => $option)
		{
			$element =& $arr[$i];

			$isArray = is_array( $element );
			$extra	 = '';
			if ($isArray)
			{
				$k 		= $element[$key];
				$t	 	= $element[$text];
				$id 	= ( isset( $element['id'] ) ? $element['id'] : null );
				if(isset($element['disable']) && $element['disable']) {
					$extra .= ' disabled="disabled"';
				}
			}
			else
			{
				$k 		= $element->$key;
				$t	 	= $element->$text;
				$id 	= ( isset( $element->id ) ? $element->id : null );
				if(isset( $element->disable ) && $element->disable) {
					$extra .= ' disabled="disabled"';
				}
			}

			if ($k === '<OPTGROUP>') {
				$html .= '<optgroup label="' . $t . '">';
			} else if ($k === '</OPTGROUP>') {
				$html .= '</optgroup>';
			}
			else
			{
				$splitText = explode( ' - ', $t, 2 );
				$t = $splitText[0];
				if(isset($splitText[1])){ $t .= ' - '. $splitText[1]; }

				if (is_array( $selected ))
				{
					foreach ($selected as $val)
					{
						$k2 = is_object( $val ) ? $val->$key : $val;
						if ($k == $k2)
						{
							$extra .= ' selected="selected"';
							break;
						}
					}
				} else {
					$extra .= ( (string)$k == (string)$selected  ? ' selected="selected"' : '' );
				}
                
				$k = $this->ampReplace($k);
				$t = $this->ampReplace($t);

				$html .= '<option value="'. $k .'" '. $extra .'>' . $t . '</option>';
			}
		}
        return $html;
    }   
    
    function ampReplace( $text )
    {
        $text = str_replace( '&&', '*--*', $text );
        $text = str_replace( '&#', '*-*', $text );
        $text = str_replace( '&amp;', '&', $text );
        $text = preg_replace( '|&(?![\w]+;)|', '&amp;', $text );
        $text = str_replace( '*-*', '&#', $text );
        $text = str_replace( '*--*', '&&', $text );

        return $text;
    }    
}

if( is_admin() )
    $starrating_settings_page = new StarratingSettingsPage();

