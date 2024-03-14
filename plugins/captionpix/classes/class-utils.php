<?php
class Captionpix_Utils {

    protected $slug = 'captionpix';
    protected $prefix = '';
	protected $is_html5 = null;

    function __construct($slug='') {
        if (!empty($slug)) $this->slug = $slug;
        $this->prefix = sprintf('_%1$s_', $this->slug);
	}

 	function get_code($code='') {
 		$format = empty($code) ? '%1$s' : '%1$s-%2$s';	
		return sprintf($format, $this->slug, $code);
	}

    function get_prefix() { return $this->prefix;}
    function get_metakey($fld) { return $this->prefix . $fld;}
	function get_home_meta_key() { return $this->get_metakey('home_meta'); }
	function get_post_meta_key() { return $this->get_metakey('post_meta'); }
	function get_term_meta_key() { return $this->get_metakey('term_meta'); }
	function get_user_meta_key() { return $this->get_metakey('user_meta'); }

	function is_html5() {
		if ($this->is_html5 == null)
			$this->is_html5 = function_exists('current_theme_supports') && current_theme_supports('html5');		
		return $this->is_html5;
	}

    function get_current_term() {
		if (is_tax() || is_category() || is_tag()) {
			if (is_category())
				$term = get_term_by('slug',get_query_var('category_name'),'category') ;
			elseif (is_tag())
				$term = get_term_by('slug',get_query_var('tag'),'post_tag') ;
			else {
            if ($obj = get_queried_object())  
				  $term = get_term_by('slug', $obj->slug, $obj->taxonomy) ;
				else
				  $term = false;
         }
		} else {
			$term = false;         
		} 
      return $term; 
	}

    function get_term_id() {
        if (is_archive() && ($term = $this->get_current_term()))
            return $term->term_id;
        else
            return false;
    }

	function get_post_id() {
		global $post;

		if (is_object($post) 
		&& property_exists($post, 'ID') 
		&& ($post_id = $post->ID))
			return $post_id ;
		else
			return false;
	}

    function get_meta($type, $id, $key = false, $result= false) {
        switch ($type) {
            case 'home': return $this->get_home_meta($key, $result); break;
            case 'term': return $this->get_term_meta($id, $key, $result); break;
            case 'user': return $this->get_user_meta($id, $key, $result); break;
            case 'post': 
            default: 	return $this->get_post_meta($id, $key, $result); break;
        }
        return $result;
    }

	function update_meta( $type = 'post', $id = false, $metakey, $vals, $defaults = false) {
        if (!$defaults) $defaults = array();	
        if (is_array($vals)) {
            foreach ($vals as $k => $v) if (!is_array($v)) $vals[$k] = stripslashes(trim($v));
            $vals = @serialize(wp_parse_args($vals, $defaults));
        } else {
            $vals = stripslashes(trim($vals));
        }
		switch ($type) { 
		  case 'home': return $this->update_home_meta( $metakey, $vals ); break;
		  case 'term': return $this->update_term_meta( $id, $metakey, $vals ); break;
          case 'user': return $this->update_user_meta( $id, $metakey, $vals ); break;	
          case 'post': 
          default:	return $this->update_post_meta( $id, $metakey, $vals ); break;		
        }
	}

    function get_home_meta( $key = false, $result = array() ) {
        if ($meta = get_option($this->get_home_meta_key()))
            if ($key && ($key != $this->get_home_meta_key()))
                return isset($meta[$key]) ? (is_serialized($meta[$key]) ? @unserialize($meta[$key]) : $meta[$key]) : $result;
            else
                return $meta;
        else
            return $result;
    }

    function update_home_meta( $key, $vals) {
        $meta = $this->get_home_meta();
        if ($key && ($key != $this->get_home_meta_key()))
            $meta[$key] = $vals;
        else
            $meta = $vals;
        update_option($this->get_home_meta_key(), $meta);
    }


	function get_post_meta ($post_id, $key= false, $result = false) {
        if (!$post_id) $post_id = $this->get_post_id();
        if (!$key) $key = $this->get_post_meta_key();
		if ($post_id && $key
		&& ($meta = get_post_meta($post_id, $key, true))
		&& ($options = (is_serialized($meta) ? @unserialize($meta) : $meta))
		&& (is_array($options) || is_string($options)))
			return $options;
		else
			return $result;
	}

	function get_post_meta_value($post_id, $key) {
        return get_post_meta($post_id, $key, true);
	}

    function update_post_meta( $post_id, $key = false, $values = false) {
        if (!$post_id) $post_id = $this->get_post_id();   
        if (!$key) $key = $this->get_post_meta_key();
        return update_post_meta( $post_id, $key, $values);
    }

    function get_term_meta( $term_id, $key= false, $result = false ) {
        if (!$term_id) $term_id = $this->get_term_id();
        if (function_exists('get_term_meta')) {
            if (!$key) $key = $this->get_term_meta_key();
            if ($vals = get_term_meta( $term_id, $key, true)) return maybe_unserialize($vals);            
        } else {
             $meta = get_option($this->get_term_meta_key());           
             if (!$meta) return $result; 
             if ($key && ($key != $this->get_term_meta_key()) ) { 
                if (isset($meta[$term_id][$key])) return $meta[$term_id][$key];
             } else {
                if (isset($meta[$term_id])) return $meta[$term_id];                
             }
        }   
        return $result;
    }

    function update_term_meta( $term_id, $key = false, $values = false) {
		$default_metakey = $this->get_term_meta_key();
        if (function_exists('update_term_meta')) {
            if (!$key) $key = $default_metakey;            
            return update_term_meta( $term_id, $key, $values);
        } else {
            $meta = get_option($default_metakey);
            if (!$meta) $meta = array(); 
            if ($key && ($key != $default_metakey))               
                $meta[$term_id][$key] = $values;
            else
                $meta[$term_id] = $values;               
            update_option($default_metakey, $meta);
        }
    }

	function get_user_meta ($user_id, $key= false, $result = false) {
        if (!$key) $key = $this->get_user_meta_key();
		if ($user_id && $key
		&& ($meta = get_user_meta($user_id, $key, true))
		&& ($options = (is_serialized($meta) ? @unserialize($meta) : $meta))
		&& (is_array($options) || is_string($options)))
			return $options;
		else
			return $result;
	}

    function update_user_meta( $user_id, $key = false, $values = false) {
        if (!$key) $key = $this->get_user_meta_key();
        return update_user_meta( $user_id, $key, $values);
    }

	function get_toggle_post_meta_key($action,  $item) {
		return sprintf('%1$s%2$s_%3$s', $this->prefix, $action, $item );
	}

	function post_has_shortcode($shortcode, $attribute = false) {
		global $wp_query;
		if (isset($wp_query)
		&& isset($wp_query->post)
		&& isset($wp_query->post->post_content)
		&& function_exists('has_shortcode')
		&& has_shortcode($wp_query->post->post_content, $shortcode)) 
			if ($attribute)
				return strpos($wp_query->post->post_content, $attribute) !== FALSE ;
			else
				return true;
		else
			return false;
	}

    function overrides($defaults, $atts) {
        $overrides = array();
        foreach ($defaults as $key => $value)
            if (isset($atts[$key]) && ($atts[$key] || ($atts[$key] === false)))
                 $overrides[$key] = $atts[$key];
		else 
                 $overrides[$key] = $value;
        return $overrides;
    }

    function clean_css_classes($classes) {
        $classes = str_replace(array('{', '}', '[', ']', '(', ')'), '', $classes);
        $classes = str_replace(array(',', ';', ':'), ' ', $classes);
        return trim($classes);
	}

	function json_encode($params) {
   		//fix numerics and booleans
		$pat = '/(\")([0-9]+)(\")/';	
		$rep = '\\2';
		return str_replace (array('"false"','"true"'), array('false','true'), 
			preg_replace($pat, $rep, json_encode($params)));
	} 
   
	function is_mobile_device() {
        if (function_exists('wp_is_mobile'))	
            return wp_is_mobile();
        else
		return  preg_match("/wap.|.wap/i", $_SERVER["HTTP_ACCEPT"])
    		|| preg_match("/iphone|ipad/i", $_SERVER["HTTP_USER_AGENT"])
    		|| preg_match("/android/i", $_SERVER["HTTP_USER_AGENT"]);
	} 

	function is_landing_page($page_template='') {	
		if (empty($page_template)
		&& ($post_id = $this->get_post_id()))
			$page_template = get_post_meta($post_id,'_wp_page_template',TRUE);
		
		if (empty($page_template)) return false;

		$landing_pages = (array) apply_filters('diy_landing_page_templates', array('page_landing.php'));
		return in_array($page_template, $landing_pages );
	}

	function read_more_link($link_text='Read More', $class='', $spacer = '') {
 		$classes = empty($class) ? '' : (' ' . $class);
 		return sprintf('%1$s<a class="more-link%2$s" href="%3$s">%4$s</a>', $spacer, $classes, get_permalink(), $link_text);
	}
	
	function register_tooltip_styles() {
		wp_register_style('diy-tooltip', plugins_url('styles/tooltip.css',dirname(__FILE__)), array(), null); 
	}

	function enqueue_tooltip_styles() {
         wp_enqueue_style('diy-tooltip');
         wp_enqueue_style('dashicons');
    }

	function selector($fld_id, $fld_name, $value, $options, $multiple = false) {
		$input = '';
		if (is_array($options)) {
			foreach ($options as $optkey => $optlabel)
				$input .= sprintf('<option%1$s value="%2$s">%3$s</option>',
					$multiple ? selected(in_array($optkey, (array)$value), true, false)
					: selected($optkey, $value, false), $optkey, $optlabel); 
		} else {
			$input = $options;
		}
		return sprintf('<select id="%1$s" name="%2$s"%4$s>%3$s</select>', $fld_id, $fld_name . ($multiple?'[]':''), $input, $multiple ? ' multiple="multiple"':'');							
	}

	function form_field($fld_id, $fld_name, $label, $value, $type, $options = array(), $args = array(), $wrap = false) {
		if ($args) extract($args);
		$input = '';
		$label = sprintf('<label class="diy-label" for="%1$s">%2$s</label>', $fld_id, __($label));
		switch ($type) {
			case 'number':
			case 'password':
			case 'range':
			case 'text':
			case 'url':
				$input .= sprintf('<input type="%1$s" id="%2$s" name="%3$s" value="%4$s" %5$s%6$s%7$s%8$s%9$s%10$s%11$s%12$s /> %13$s',
					$type, $fld_id, $fld_name, $value, 
					isset($readonly) ? (' readonly="'.$readonly.'"') : '',
					isset($size) ? (' size="'.$size.'"') : '', 
					isset($maxlength) ? (' maxlength="'.$maxlength.'"') : '',
					isset($class) ? (' class="'.$class.'"') : '', 
					isset($min) ? (' min="'.$min.'"') : '', 
					isset($max) ? (' max="'.$max.'"') : '', 
					isset($step) ? (' step="'.$step.'"') : '', 
					isset($pattern) ? (' pattern="'.$pattern.'"') : '',
					isset($suffix) ? $suffix : '');
				break;
			case 'file':
				$input .= sprintf('<input type="file" id="%1$s" name="%2$s" value="%3$s" %4$s%5$s%6$s accept="image/*" />',
					$fld_id, $fld_name, $value, 
					isset($size) ? ('size="'.$size.'"') : '', 
					isset($maxlength) ? (' maxlength="'.$maxlength.'"') : '',
					isset($class) ? (' class="'.$class.'"') : '');
				break;
			case 'textarea':
				$input .= sprintf('<textarea id="%1$s" name="%2$s"%3$s%4$s%5$s%6$s>%7$s</textarea>',
					$fld_id, $fld_name, 
					isset($readonly) ? (' readonly="'.$readonly.'"') : '', 
					isset($rows) ? (' rows="'.$rows.'"') : '', 
					isset($cols) ? (' cols="'.$cols.'"') : '',
					isset($class) ? (' class="'.$class.'"') : '', stripslashes($value));
				break;
			case 'checkbox':
				if (is_array($options) && (count($options) > 0)) {
					if (isset($legend))
						$input .= sprintf('<legend class="screen-reader-text"><span>%1$s</span></legend>', $legend);
					if (!isset($separator)) $separator = '';
					foreach ($options as $optkey => $optlabel)
						$input .= sprintf('<input type="checkbox" id="%1$s" name="%2$s[]" %3$s value="%4$s" /><label for="%1$s">%5$s</label>%6$s',
							$fld_id, $fld_name, str_replace('\'','"',checked($optkey, $value, false)), $optkey, $optlabel, $separator); 
					$input = sprintf('<fieldset class="diy-fieldset">%1$s</fieldset>',$input); 						
				} else {		
					$input .= sprintf('<input type="checkbox" class="checkbox" id="%1$s" name="%2$s" %3$svalue="1" class="diy-checkbox" />',
						$fld_id, $fld_name, checked($value, '1', false));
				}
				break;
				
			case 'checkboxes': 
			   $values = (array) $value;
			   $options = (array) $options;
			   if (isset($legend))
				  $input .= sprintf('<legend class="screen-reader-text"><span>%1$s</span></legend>', $legend);
				foreach ($options as $optkey => $optlabel)
				  $input .= sprintf('<li><input type="checkbox" id="%1$s" name="%2$s[]" %3$s value="%4$s" /><label for="%1$s">%5$s</label></li>',
					$fld_id, $fld_name, in_array($optkey, $values) ? 'checked="checked"' : '', $optkey, $optlabel); 
				$input = sprintf('<fieldset class="diy-fieldset%2$s"><ul>%1$s</ul></fieldset>',$input, isset($class) ? (' '.$class) : ''); 						
		
				break;
				
			case 'keypairs': 
                $keypairs = (array) $value;
                $i=0;
                $lines = array();
                $spares = isset($args['spares']) ? $args['spares'] : 0;
			    if (isset($legend))
                    $input .= sprintf('<legend class="screen-reader-text"><span>%1$s</span></legend>', $legend);
                foreach ($keypairs as $key => $value){
    				$i++;
                    $lines[] = 
                        sprintf('<div class="diy-keypair"><input type="text" id="%1$s" name="%2$s" value="%3$s" %4$s%5$s%6$s /> ',
					       $fld_id.'key'.$i, sprintf('%1$s[key][%2$s]', $fld_name, $i), $key, 
					       isset($size) ? (' size="'.$size.'"') : '', 
					       isset($maxlength) ? (' maxlength="'.$maxlength.'"') : '',
					       isset($pattern) ? (' pattern="'.$pattern.'"') : '') .
                        sprintf('<textarea id="%1$s" name="%2$s"%3$s%4$s%5$s>%6$s</textarea></div>',
					       $fld_id.'val'.$i, sprintf('%1$s[val][%2$s]', $fld_name, $i), 
					       isset($rows) ? (' rows="'.$rows.'"') : '', 
					       isset($cols) ? (' cols="'.$cols.'"') : '',
					       isset($class) ? (' class="'.$class.'"') : '', stripslashes($value));
	
				}
				for ($j = 1; $j <= $spares; $j++) {
				    $r = $i+$j;
                    $lines[] = 
                        sprintf('<div class="diy-keypair"><input id="%1$s" name="%2$s" value="%3$s" %4$s%5$s%6$s%7$s/> ',
		                   $fld_id.'key'.($r), sprintf('%1$s[key][%2$s]', $fld_name, $r), '', 
					       isset($size) ? (' size="'.$size.'"') : '', 
					       isset($maxlength) ? (' maxlength="'.$maxlength.'"') : '',
					       isset($placeholder) ? (' placeholder="'.$placeholder.'"') : '',
					       isset($pattern) ? (' pattern="'.$pattern.'"') : '') .
                        sprintf('<textarea id="%1$s" name="%2$s"%3$s%4$s%5$s>%6$s</textarea></div>',
					       $fld_id.'value'.($r), sprintf('%1$s[val][%2$s]', $fld_name, $r),
					       isset($rows) ? (' rows="'.$rows.'"') : '', 
					       isset($cols) ? (' cols="'.$cols.'"') : '',
					       isset($class) ? (' class="'.$class.'"') : '','');
				}
				
				$input = sprintf('<fieldset class="diy-fieldset%2$s">%1$s</fieldset>',implode('',$lines), isset($class) ? (' '.$class) : ''); 						
		
				break;

			case 'radio': 
				if (is_array($options) && (count($options) > 0)) {
					if (isset($legend))
						$input .= sprintf('<legend class="screen-reader-text"><span>%1$s</span></legend>', $legend);
					if (!isset($separator)) $separator = '';
					foreach ($options as $optkey => $optlabel)
						$input .= sprintf('<input type="radio" id="%1$s" name="%2$s" %3$s value="%4$s" /><label for="%1$s">%5$s</label>%6$s',
							$fld_id, $fld_name, str_replace('\'','"',checked($optkey, $value, false)), $optkey, $optlabel, $separator); 
					$input = sprintf('<fieldset class="diy-fieldset">%1$s</fieldset>',$input); 						
				}
				break;		
			case 'select': 
				$input =  $this->selector($fld_id, $fld_name, $value, $options, isset($multiple));							
				break;	
            case 'page':
                $args = array( 'id' => $fld_name, 'name' => $fld_name, 'selected' => $value, 'echo' => false,  'depth' => 0, 'option_none_value' => 0);
                if (isset($show_option_none)) $args['show_option_none'] = $show_option_none;
                $input = wp_dropdown_pages($args);
				break;	
			case 'hidden': return sprintf('<input type="hidden" name="%1$s" value="%2$s" />', $fld_name, $value);	
			default: $input = $value;	
		}
		if (!$wrap) $wrap = 'div';
		switch ($wrap) {
			case 'tr': $format = '<tr class="diy-row"><th scope="row">%1$s</th><td>%2$s</td></tr>'; break;
			case 'br': $format = 'checkbox'==$type ? '%2$s%1$s<br/>' : '%1$s%2$s<br/>'; break;
			default: $format = strpos($input,'fieldset') !== FALSE ? 
				'<div class="diy-row wrapfieldset">%1$s%2$s</div>' : ('<'.$wrap.' class="diy-row">%1$s%2$s</'.$wrap.'>');
		}
		return sprintf($format, $label, $input);
	}
	
    function sanitize_post_keypairs($lexicon) {
        if (isset($_POST[$lexicon]['key']) && isset($_POST[$lexicon]['val']) ) {
			foreach ( $_POST[$lexicon]['key'] as $i => $key ){
				if ( empty($key) ){ 
					unset( $_POST[$lexicon]['val'][$i] );
				} else {
				    $_POST[$lexicon][$key] = $_POST[$lexicon]['val'][$i];                    
				}
			}
			unset($_POST[$lexicon]['key']);
			unset($_POST[$lexicon]['val']);
        }
    }

    function get_image_sizes() {
    	global $_wp_additional_image_sizes;
    	$sizes = array();
    	foreach ( get_intermediate_image_sizes() as $_size ) {
    		if ( in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
    			$sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
    			$sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
    			$sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );
    		} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
    			$sizes[ $_size ] = array(
    				'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
    				'height' => $_wp_additional_image_sizes[ $_size ]['height'],
    				'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
    			);
    		}
    	}
    	return $sizes;
    }

    function is_gutenberg_page() {
            return  function_exists( 'is_gutenberg_page' ) && is_gutenberg_page() ;        
    }

    function truncate_last_space($text, $max_chars ) {
    	if ( ! $max_chars ) return '';
    	$text = trim( $text );
    	if ( mb_strlen( $text ) > $max_chars ) {
    		$text = mb_substr( $text, 0, $max_chars + 1 );
    		$text_trim = trim( mb_substr( $text, 0, mb_strrpos( $text, ' ' ) ) );
    		$text = empty( $text_trim ) ? $text : $text_trim;
        }
	   return $text;
    }


	function maybe_enqueue_tooltip_styles() {
      if ( class_exists('FLBuilderModel')
      && is_callable(array('FLBuilderModel', 'is_builder_active')) 
      && FLBuilderModel::is_builder_active() ) {
         add_action('wp_enqueue_scripts', array($this, 'register_tooltip_styles'));
         add_action('wp_enqueue_scripts', array($this, 'enqueue_tooltip_styles'));
      }
	}

	function late_inline_styles($css) {
		if (empty($css)) return;
		$wrap = '$("<style type=\"text/css\">%1$s</style>").appendTo("head");';
        $this->print_script(sprintf($wrap, $css));
	}

	function print_immediate_script($script) {
	    $this->print_script($script, false);
	}
	
	function append_css($css){
  		if (empty($css)) return false;
  		print <<< CUSTOM_CSS
<style type="text/css">
{$css}
</style>
CUSTOM_CSS;

	}

	function print_script($script, $ready = true) {
        $ready_begin = $ready ? 'jQuery(document).ready( function($) {' : '';  
        $ready_end = $ready ? '});' : '';  
		print <<< SCRIPT
<script type="text/javascript">
//<![CDATA[
{$ready_begin}
	{$script}
{$ready_end}
//]]>
</script>	
	
SCRIPT;
	}


    function print_late_stylesheet($url) {
 		print <<< STYLES

<script>
//<![CDATA[
(function(d) {
	var wf = d.createElement('link'), s = d.scripts[0];
	wf.href = '{$url}';
	wf.rel = 'stylesheet';
	s.parentNode.insertBefore(wf, s);
})(document);
//]]>
</script>

STYLES;
    }
    
	function print_async_script($url, $config='') {
		print <<< SCRIPT

<script>
//<![CDATA[
{$config}
(function(d) {
	var wf = d.createElement('script'), s = d.scripts[0];
	wf.src = '{$url}';
	wf.async = true;
	s.parentNode.insertBefore(wf, s);
})(document);
//]]>
</script>

SCRIPT;
	}

	function print_async_script_with_callback($url, $callback='') {
		print <<< SCRIPT

<script>
//<![CDATA[
(function(d) {
	var wf = d.createElement('script'), s = d.scripts[0], loaded = false;
	wf.src = '{$url}';
	wf.async = true;
    wf.onreadystatechange = wf.onload = function() { 
        if (!loaded) {
            {$callback}
          }
        loaded = true;
    };	
	s.parentNode.insertBefore(wf, s);
})(document);
//]]>
</script>

SCRIPT;
	}

}
