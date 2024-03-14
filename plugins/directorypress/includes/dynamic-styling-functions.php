<?php

if (!function_exists('directorypress_dynamic_css_injection')) {
     function directorypress_dynamic_css_injection()
     {

      global $directorypress_style_json, $directorypress_styles;  
    
    echo '<script>';
    

    $backslash_styles = str_replace('\\', '\\\\', $directorypress_styles);
    $clean_styles = preg_replace('!\s+!', ' ', $backslash_styles);
    $clean_styles_w = str_replace("'", "\"", $clean_styles);
    $is_admin_bar = is_admin_bar_showing() ? 'true' : 'false';
    $directorypress_json_encode = json_encode($directorypress_style_json);
    echo '  
    php = {
        hasAdminbar: '.$is_admin_bar.',
        json: ('.$directorypress_json_encode.' != null) ? '.$directorypress_json_encode.' : "",
        styles:  \''.$clean_styles_w.'\'
      };
      
    var styleTag = document.createElement("style"),
      head = document.getElementsByTagName("head")[0];

    styleTag.type = "text/css";
    styleTag.innerHTML = php.styles;
    head.appendChild(styleTag);
    </script>';

    

     }
}

add_action('wp_enqueue_scripts', 'directorypress_dynamic_css_injection');
/*-----------------*/


function directorypress_clean_dynamic_styles($value) {

  $clean_styles = preg_replace('!\s+!', ' ', $value);
  $clean_styles_w = str_replace("'", "\"", $clean_styles);

  return $clean_styles_w;

}

function directorypress_clean_init_styles($value) {

  $backslash_styles = str_replace('\\', '\\\\', $value);
  $clean_styles = preg_replace('!\s+!', ' ', $backslash_styles);
  $clean_styles_w = str_replace("'", "\"", $clean_styles);

  return $clean_styles_w;

}

function create_global_styles() {
    $directorypress_styles = '';
    global $directorypress_styles;
}
create_global_styles();
//////////////////////////////////////////////////////////////////////////
// 
//  Global JSON object to collect all DOM related data
//  todo - move here all VC shortcode settings
//
//////////////////////////////////////////////////////////////////////////

function directorypress_create_global_json() {
    $directorypress_style_json = array();
    global $directorypress_style_json;
}
directorypress_create_global_json();


function directorypress_create_global_dynamic_styles() {
    $directorypress_dynamic_styles = array();
    global $directorypress_dynamic_styles;
}
directorypress_create_global_dynamic_styles();



/* footer scripts */
add_action('wp_footer', 'directorypress_footer_elements', 1);
function directorypress_footer_elements() { 
global $post, $directorypress_style_json;
 $post_id = directorypress_global_get_post_id();


	global $directorypress_dynamic_styles;

	$directorypress_dynamic_styles_ids = array();
	$directorypress_dynamic_styles_inject = '';
	if(!empty($directorypress_dynamic_styles)){
		$directorypress_styles_length = count($directorypress_dynamic_styles);
	}else{
		$directorypress_styles_length = 0;
	}
	if ($directorypress_styles_length > 0) {
		foreach ($directorypress_dynamic_styles as $key => $val) { 
			$directorypress_dynamic_styles_ids[] = $val["id"]; 
			$directorypress_dynamic_styles_inject .= $val["inject"];
		};
	}

?>
<script>
	window.$ = jQuery

	var dynamic_styles = '<?php echo directorypress_clean_init_styles($directorypress_dynamic_styles_inject); ?>';
	var dynamic_styles_ids = (<?php echo json_encode($directorypress_dynamic_styles_ids); ?> != null) ? <?php echo json_encode($directorypress_dynamic_styles_ids); ?> : [];

	var styleTag = document.createElement('style'),
		head = document.getElementsByTagName('head')[0];

	styleTag.type = 'text/css';
	styleTag.setAttribute('data-ajax', '');
	styleTag.innerHTML = dynamic_styles;
	head.appendChild(styleTag);


	$('.directorypress-dynamic-styles').each(function() {
		$(this).remove();
	});

	function ajaxStylesInjector() {
		$('.directorypress-dynamic-styles').each(function() {
			var $this = $(this),
				id = $this.attr('id'),
				commentedStyles = $this.html();
				styles = commentedStyles
						 .replace('<!--', '')
						 .replace('-->', '');

			if(dynamic_styles_ids.indexOf(id) === -1) {
				$('style[data-ajax]').append(styles);
				$this.remove();
			}

			dynamic_styles_ids.push(id);
		});
	};
</script>



<?php } 

function directorypress_path_convert($path) {
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        $path = str_replace('/', '\\', $path);
    }
    else {
        $path = str_replace('\\', '/', $path);
    }
    return $path;
}


if (!function_exists('directorypress_convert_rgba')) {
      function directorypress_convert_rgba($colour, $alpha)
      {
            if (!empty($colour)) {
                  if ($alpha >= 0.95) {
                        return $colour; // If alpha is equal 1 no need to convert to RGBA, so we are ok with it. :)
                  } else {
                        if ($colour[0] == '#') {
                              $colour = substr($colour, 1);
                        }
                        if (strlen($colour) == 6) {
                              list($r, $g, $b) = array(
                                    $colour[0] . $colour[1],
                                    $colour[2] . $colour[3],
                                    $colour[4] . $colour[5]
                              );
                        } elseif (strlen($colour) == 3) {
                              list($r, $g, $b) = array(
                                    $colour[0] . $colour[0],
                                    $colour[1] . $colour[1],
                                    $colour[2] . $colour[2]
                              );
                        } else {
                              return false;
                        }
                        $r      = hexdec($r);
                        $g      = hexdec($g);
                        $b      = hexdec($b);
                        $output = array(
                              'red' => $r,
                              'green' => $g,
                              'blue' => $b
                        );
                        
                        return 'rgba(' . implode($output, ',') . ',' . $alpha . ')';
                  }
            }
      }
}