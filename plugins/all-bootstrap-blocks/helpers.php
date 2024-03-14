<?php  
global $areoi_theme_colors;
$areoi_theme_colors = array(
    'primary'   => areoi_get_option_color( AREOI__PREPEND . '-customize-theme-colors-primary', '#0d6efd' ),
    'secondary' => areoi_get_option_color( AREOI__PREPEND . '-customize-theme-colors-secondary', '#6c757d' ),
    'success'   => areoi_get_option_color( AREOI__PREPEND . '-customize-theme-colors-success', '#198754' ),
    'info'      => areoi_get_option_color( AREOI__PREPEND . '-customize-theme-colors-info', '#0dcaf0' ),
    'warning'   => areoi_get_option_color( AREOI__PREPEND . '-customize-theme-colors-warning', '#ffc107' ),
    'danger'    => areoi_get_option_color( AREOI__PREPEND . '-customize-theme-colors-danger', '#dc3545' ),
    'light'     => areoi_get_option_color( AREOI__PREPEND . '-customize-theme-colors-light', '#f8f9fa' ),
    'dark'      => areoi_get_option_color( AREOI__PREPEND . '-customize-theme-colors-dark', '#212529' ),
    'body'      => areoi_get_option_color( AREOI__PREPEND . '-customize-body-body-bg', '#ffffff' ),
    'transparent' => '#ffffff',
);

function areoi_get_class_name_str( $classes )
{
	$class_string = '';
	if ( is_array( $classes) ) {
		foreach ( $classes as $class_key => $class ) {
			if ( !$class || $class == 'Default' ) {
				continue;
			}
			$class_string .= $class . ' ';
		}
	}
	return trim( $class_string );
}

function areoi_get_display_class_str( $attributes, $display )
{
	$devices 		= array( 'xs', 'sm', 'md', 'lg', 'xl', 'xxl' );
	$class_string 	= '';

	$prev_display 	= false;
	foreach ( $devices as $device ) {
		if ( empty( $attributes['hide_' . $device] ) ) {
			// continue;
		}
		$attr 		= !empty( $attributes['hide_' . $device] ) ? $attributes['hide_' . $device] : null;

		if ( !empty( $attr ) ) {
			$class_string 	.= ' d-' . ( $device == 'xs' ? '' : $device . '-' ) . 'none';
			$prev_display 	= true;
		} elseif ( $prev_display ) {

			$class_string 	.= ' d-' . ( $device == 'xs' ? '' : $device . '-' ) . $display;
			$prev_display 	= false;
		}
	}
	
	return trim( $class_string );
}

function areoi_get_background_display_class_str( $attributes, $display )
{
	$devices 		= array( 'xs', 'sm', 'md', 'lg', 'xl', 'xxl' );
	$class_string 	= '';

	$prev_display 	= false;
	foreach ( $devices as $device ) {
		if ( empty( $attributes['background_hide_' . $device] ) ) {
			// continue;
		}
		$attr 		= !empty( $attributes['background_hide_' . $device] ) ? $attributes['background_hide_' . $device] : null;

		if ( !empty( $attr ) ) {
			$class_string 	.= ' d-' . ( $device == 'xs' ? '' : $device . '-' ) . 'none';
			$prev_display 	= true;
		} elseif ( $prev_display ) {

			$class_string 	.= ' d-' . ( $device == 'xs' ? '' : $device . '-' ) . $display;
			$prev_display 	= false;
		}
	}
	
	return trim( $class_string );
}

function areoi_return_id( $attributes )
{
	return ( ( !empty( $attributes['anchor'] ) ) ? 'id="' . $attributes['anchor'] . '"' : '' );
}

function areoi_get_display_block_class_str( $attributes, $display )
{
	$devices 		= array( 'xs', 'sm', 'md', 'lg', 'xl', 'xxl' );
	$class_string 	= '';

	$prev_display 	= false;
	foreach ( $devices as $device ) {
		$attr 		= $attributes['block_' . $device];

		if ( !empty( $attr ) ) {
			$class_string 	.= ' d-' . ( $device == 'xs' ? '' : $device . '-' ) . 'block';
			$prev_display 	= true;
		} elseif ( $prev_display ) {
			$class_string 	.= ' d-' . ( $device == 'xs' ? '' : $device . '-' ) . $display;
			$prev_display 	= false;
		}
	}
	
	return trim( $class_string );
}

function areoi_get_utilities_classes( $attributes ) 
{
	$string = ' ';

	if ( !empty( $attributes['utilities_bg'] ) && $attributes['utilities_bg'] != 'Default' ) $string .= ' ' . $attributes['utilities_bg'];
	if ( !empty( $attributes['utilities_text'] ) && $attributes['utilities_text'] != 'Default' ) $string .= ' ' . $attributes['utilities_text'];
	if ( !empty( $attributes['utilities_border'] ) && $attributes['utilities_border'] != 'Default' ) $string .= ' ' . $attributes['utilities_border'];
	
	return $string;
}

function areoi_format_block_id( $block_id )
{
	return 'block-' . $block_id;
}

function areoi_get_rgba_str( $rgba )
{
	return trim( 'rgba(' . $rgba['r'] . ', ' . $rgba['g'] . ', ' . $rgba['b'] . ',' . $rgba['a'] . ')' );
}

function areoi_generate_breadcrumbs() 
{
	global $post,$wp_query;

	$breadcrumbs = array();
	if ( $post->post_parent ) {
		$breadcrumbs = areoi_generate_breadcrumbs_parent( $breadcrumbs, $post->post_parent );

		$breadcrumbs[] = array(
			'permalink' => home_url(),
			'label'		=> 'Home',
			'active'	=> false
		);
	}
	$breadcrumbs = array_reverse( $breadcrumbs );

	if ( get_permalink( $post->ID ) != home_url() ) {
		$breadcrumbs[] = array(
			'permalink' => get_the_permalink( $post->ID ),
			'label'		=> get_the_title( $post->ID ),
			'active'	=> true
		);
	} else {
		$breadcrumbs[0]['active'] = true;
	}
	return $breadcrumbs;
}

function areoi_generate_breadcrumbs_parent( $breadcrumbs, $parent_id ) 
{
	$parent = get_post( $parent_id );
	
	if ( get_permalink( $parent->ID ) != home_url() ) {
		$breadcrumbs[] = array(
			'permalink' => get_the_permalink( $parent->ID ),
			'label'		=> get_the_title( $parent->ID ),
			'active'	=> false
		);
	}

	if ( $parent->post_parent ) {
		return areoi_generate_breadcrumbs_parent( $breadcrumbs, $parent->post_parent );
	}
	return $breadcrumbs;
}

function areoi_enqueue_css( $enqueues )
{
	foreach ( $enqueues as $enqueue_key => $enqueue ) {
		wp_enqueue_style( $enqueue_key, AREOI__PLUGIN_URI . $enqueue, array(), filemtime( AREOI__PLUGIN_DIR . $enqueue ) );
	}
}

function areoi_enqueue_js( $enqueues )
{
	foreach ( $enqueues as $enqueue_key => $enqueue ) {
		wp_enqueue_script( $enqueue_key, AREOI__PLUGIN_URI . $enqueue['path'], $enqueue['includes'], filemtime( AREOI__PLUGIN_DIR . $enqueue['path'] ), true );
	}
}

function areoi_get_original_theme_json()
{
	$has_theme_json 	= null;
    $theme_json_path 	= get_stylesheet_directory() . '/theme.json';

    if ( file_exists( $theme_json_path ) ) $has_theme_json = json_decode( file_get_contents( $theme_json_path ), true );

	return $has_theme_json;
}

function areoi_get_theme_json()
{
	$has_theme_json 	= null;
    $theme_json_path 	= get_stylesheet_directory() . '/theme.json';

    if ( file_exists( $theme_json_path ) ) {
        $has_theme_json = json_decode( file_get_contents( $theme_json_path ), true );
        $settings 		= !empty( $has_theme_json['settings'] ) ? areoi_preset_properties_theme_json( $has_theme_json['settings'], array(), array( 'settings' ) ) : array();
        $styles = array(
			'text' => 'Styles',
			'children' => array()
		);
        $styles['children'] = !empty( $has_theme_json['styles'] ) ? areoi_flatten_theme_json( $has_theme_json['styles'], array(), array( 'styles' ) ) : array();
        $has_theme_json = array_merge( $settings, array( $styles ) );
    }

	return $has_theme_json;
}

function areoi_get_theme_json_last_update()
{
    $theme_json_path 	= get_stylesheet_directory() . '/theme.json';

	return filemtime( $theme_json_path );
}

function areoi_preset_properties_theme_json( $rows )
{
	$array = array();

	if ( !empty( $rows ) ) {
		
		if ( !empty( $rows['color']['gradients'] ) ) {
			$group = array(
				'text' => 'Gradient',
				'children' => array()
			);
			foreach ( $rows['color']['gradients'] as $row_key => $row ) {
				$var = 'settings!!color!!gradients!!' . $row_key . '!!gradient';
				$group['children'][] 		= [
					'id'	=> $var,
					'text' => ( !empty( $row['name'] ) ? $row['name'] : $row['slug'] )
				];
			}
			$array[] = $group;
		}

		if ( !empty( $rows['color']['palette'] ) ) {
			$group = array(
				'text' => 'Palette',
				'children' => array()
			);
			foreach ( $rows['color']['palette'] as $row_key => $row ) {
				$var = 'settings!!color!!palette!!' . $row_key . '!!color';
				$group['children'][] 		= [
					'id'	=> $var,
					'text' => ( !empty( $row['name'] ) ? $row['name'] : $row['slug'] )
				];
			}
			$array[] = $group;
		}

		if ( !empty( $rows['typography']['fontFamilies'] ) ) {
			$group = array(
				'text' => 'Font Families',
				'children' => array()
			);
			foreach ( $rows['typography']['fontFamilies'] as $row_key => $row ) {
				$var = 'settings!!typography!!fontFamilies!!' . $row_key . '!!fontFamily';
				$group['children'][] 		= [
					'id'	=> $var,
					'text' => ( !empty( $row['name'] ) ? $row['name'] : $row['slug'] )
				];
			}
			$array[] = $group;
		}

		if ( !empty( $rows['typography']['fontSizes'] ) ) {
			$group = array(
				'text' => 'Font Size',
				'children' => array()
			);
			foreach ( $rows['typography']['fontSizes'] as $row_key => $row ) {
				$var = 'settings!!typography!!fontSizes!!' . $row_key . '!!size';
				$group['children'][] 		= [
					'id'	=> $var,
					'text' => ( !empty( $row['name'] ) ? $row['name'] : $row['slug'] )
				];
			}
			$array[] = $group;
		}

		if ( !empty( $rows['layout'] ) ) {
			$group = array(
				'text' => 'Layout',
				'children' => array()
			);
			foreach ( $rows['layout'] as $row_key => $row ) {
				
				$var = 'settings!!layout!!' . $row_key;
				$group['children'][] 		= [
					'id'	=> $var,
					'text' => $row_key . ': ' . $row
				];
			}
			$array[] = $group;
		}

	}
	
	return $array;
}

function areoi_flatten_theme_json( $rows, $array, $append )
{
	if ( !empty( $rows ) ) {
		foreach ( $rows as $child_rows_key => $child_rows ) {
			$new_append 		= $append;
			$new_append[] 		= $child_rows_key;
			$append_id	 		= implode( '!!', $new_append ); 
			$append_label 		= implode( ' > ', $new_append ); 
			
			if ( in_array( $child_rows_key, array( 'slug', 'name' ) ) ) continue;

			if ( !is_array( $child_rows ) ) {
				$array[] 		= [
					'id'	=> $append_id,
					'text' 	=> $append_label
				];
			} else {
				$array 			= areoi_flatten_theme_json( $child_rows, $array, $new_append );
			}
		}
	}
	return $array;
}

function areoi_get_theme_json_value( $value )
{
	$theme_json = areoi_get_original_theme_json();
	$value 		= str_replace( 'theme-json-', '', $value );
	$array 		= explode( '!!', $value );
	$value 		= '';

	if ( !empty( $array ) ) {
		foreach ( $array as $arr_key => $arr ) {
			if ( !empty( $theme_json[$arr] ) ) {
				$theme_json = $theme_json[$arr];
			}
			if ( !empty( $theme_json['value'] ) ) {
				$value = $theme_json['value'];
			}
		}
	}

	return $theme_json;
}

function areoi_get_parent_block( $id )
{
	global $post;

	$all_blocks = parse_blocks( $post->post_content );

	foreach ( $all_blocks as $block_key => $block ) {
		if ( !empty( $block['attrs']['block_id'] ) && $block['attrs']['block_id'] == $id ) return $block;
	}

	return null;
}

function areoi_get_prepend_content( $attributes )
{
	$prepend = '';

	$prepend_row_class = trim(
		areoi_get_class_name_str( array( 
			'row',
			'position-relative',
			( empty( $attributes['hide_xs'] ) && !empty( $attributes['prepend_horizontal_align_xs'] ) ? $attributes['prepend_horizontal_align_xs'] : '' ),
			( empty( $attributes['hide_sm'] ) && !empty( $attributes['prepend_horizontal_align_sm'] ) ? $attributes['prepend_horizontal_align_sm'] : '' ),
			( empty( $attributes['hide_md'] ) && !empty( $attributes['prepend_horizontal_align_md'] ) ? $attributes['prepend_horizontal_align_md'] : '' ),
			( empty( $attributes['hide_lg'] ) && !empty( $attributes['prepend_horizontal_align_lg'] ) ? $attributes['prepend_horizontal_align_lg'] : '' ),
			( empty( $attributes['hide_xl'] ) && !empty( $attributes['prepend_horizontal_align_xl'] ) ? $attributes['prepend_horizontal_align_xl'] : '' ),
			( empty( $attributes['hide_xxl'] ) && !empty( $attributes['prepend_horizontal_align_xxl'] ) ? $attributes['prepend_horizontal_align_xxl'] : '' ),
		))
	);
	$prepend_col_class = trim(
		areoi_get_class_name_str( array(
			'col',
			( empty( $attributes['hide_xs'] ) && !empty( $attributes['prepend_col_xs'] ) ? $attributes['prepend_col_xs'] : '' ),
			( empty( $attributes['hide_sm'] ) && !empty( $attributes['prepend_col_sm'] ) ? $attributes['prepend_col_sm'] : '' ),
			( empty( $attributes['hide_md'] ) && !empty( $attributes['prepend_col_md'] ) ? $attributes['prepend_col_md'] : '' ),
			( empty( $attributes['hide_lg'] ) && !empty( $attributes['prepend_col_lg'] ) ? $attributes['prepend_col_lg'] : '' ),
			( empty( $attributes['hide_xl'] ) && !empty( $attributes['prepend_col_xl'] ) ? $attributes['prepend_col_xl'] : '' ),
			( empty( $attributes['hide_xxl'] ) && !empty( $attributes['prepend_col_xxl'] ) ? $attributes['prepend_col_xxl'] : '' ),
			( empty( $attributes['hide_xs'] ) && !empty( $attributes['prepend_text_align_xs'] ) ? $attributes['prepend_text_align_xs'] : '' ),
			( empty( $attributes['hide_sm'] ) && !empty( $attributes['prepend_text_align_sm'] ) ? $attributes['prepend_text_align_sm'] : '' ),
			( empty( $attributes['hide_md'] ) && !empty( $attributes['prepend_text_align_md'] ) ? $attributes['prepend_text_align_md'] : '' ),
			( empty( $attributes['hide_lg'] ) && !empty( $attributes['prepend_text_align_lg'] ) ? $attributes['prepend_text_align_lg'] : '' ),
			( empty( $attributes['hide_xl'] ) && !empty( $attributes['prepend_text_align_xl'] ) ? $attributes['prepend_text_align_xl'] : '' ),
			( empty( $attributes['hide_xxl'] ) && !empty( $attributes['prepend_text_align_xxl'] ) ? $attributes['prepend_text_align_xxl'] : '' ),
		) )
	);

	$heading_color = !empty( $attributes['prepend_heading_color'] ) ? $attributes['prepend_heading_color'] : '';
	$intro_color = !empty( $attributes['prepend_intro_color'] ) ? $attributes['prepend_intro_color'] : '';

	if ( !empty( $attributes['prepend_display_heading'] ) || !empty( $attributes['prepend_display_intro'] ) ) {

		$prepend_heading = !empty( $attributes['prepend_display_heading'] ) && !empty( $attributes['prepend_heading'] ) ? '<' . $attributes['prepend_heading_level'] . ' class="' . $heading_color . '">' . $attributes['prepend_heading'] . '</' . $attributes['prepend_heading_level'] . '>' : '';
		$prepend_intro = !empty( $attributes['prepend_intro'] ) && !empty( $attributes['prepend_intro'] ) ? '<p class="' . $intro_color . '">' . $attributes['prepend_intro'] . '</p>' : '';

		$prepend .= '
		<div class="' . $prepend_row_class . '">
			<div class="' . $prepend_col_class . '">
				' . $prepend_heading . '
				' . $prepend_intro . '
			</div>
		</div>
		';
	}

	return $prepend;
}

// HTML Minifier
function areoi_minify_html($input) {
    if(trim($input) === "") return $input;
    // Remove extra white-space(s) between HTML attribute(s)
    $input = preg_replace_callback('#<([^\/\s<>!]+)(?:\s+([^<>]*?)\s*|\s*)(\/?)>#s', function($matches) {
        return '<' . $matches[1] . preg_replace('#([^\s=]+)(\=([\'"]?)(.*?)\3)?(\s+|$)#s', ' $1$2', $matches[2]) . $matches[3] . '>';
    }, str_replace("\r", "", $input));
    // Minify inline CSS declaration(s)
    if(strpos($input, ' style=') !== false) {
        $input = preg_replace_callback('#<([^<]+?)\s+style=([\'"])(.*?)\2(?=[\/\s>])#s', function($matches) {
            return '<' . $matches[1] . ' style=' . $matches[2] . minify_css($matches[3]) . $matches[2];
        }, $input);
    }
    if(strpos($input, '</style>') !== false) {
      $input = preg_replace_callback('#<style(.*?)>(.*?)</style>#is', function($matches) {
        return '<style' . $matches[1] .'>'. minify_css($matches[2]) . '</style>';
      }, $input);
    }
    if(strpos($input, '</script>') !== false) {
      $input = preg_replace_callback('#<script(.*?)>(.*?)</script>#is', function($matches) {
        return '<script' . $matches[1] .'>'. minify_js($matches[2]) . '</script>';
      }, $input);
    }

    return preg_replace(
        array(
            // t = text
            // o = tag open
            // c = tag close
            // Keep important white-space(s) after self-closing HTML tag(s)
            '#<(img|input)(>| .*?>)#s',
            // Remove a line break and two or more white-space(s) between tag(s)
            '#(<!--.*?-->)|(>)(?:\n*|\s{2,})(<)|^\s*|\s*$#s',
            '#(<!--.*?-->)|(?<!\>)\s+(<\/.*?>)|(<[^\/]*?>)\s+(?!\<)#s', // t+c || o+t
            '#(<!--.*?-->)|(<[^\/]*?>)\s+(<[^\/]*?>)|(<\/.*?>)\s+(<\/.*?>)#s', // o+o || c+c
            '#(<!--.*?-->)|(<\/.*?>)\s+(\s)(?!\<)|(?<!\>)\s+(\s)(<[^\/]*?\/?>)|(<[^\/]*?\/?>)\s+(\s)(?!\<)#s', // c+t || t+o || o+t -- separated by long white-space(s)
            '#(<!--.*?-->)|(<[^\/]*?>)\s+(<\/.*?>)#s', // empty tag
            '#<(img|input)(>| .*?>)<\/\1>#s', // reset previous fix
            '#(&nbsp;)&nbsp;(?![<\s])#', // clean up ...
            '#(?<=\>)(&nbsp;)(?=\<)#', // --ibid
            // Remove HTML comment(s) except IE comment(s)
            '#\s*<!--(?!\[if\s).*?-->\s*|(?<!\>)\n+(?=\<[^!])#s'
        ),
        array(
            '<$1$2</$1>',
            '$1$2$3',
            '$1$2$3',
            '$1$2$3$4$5',
            '$1$2$3$4$5$6$7',
            '$1$2$3',
            '<$1$2',
            '$1 ',
            '$1',
            ""
        ),
    $input);
}

// CSS Minifier => http://ideone.com/Q5USEF + improvement(s)
function areoi_minify_css($input) {
    if(trim($input) === "") return $input;
    return preg_replace(
        array(
            // Remove comment(s)
            '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s',
            // Remove unused white-space(s)
            '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~]|\s(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si',
            // Replace `0(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)` with `0`
            '#(?<=[\s:])(0)(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)#si',
            // Replace `:0 0 0 0` with `:0`
            '#:(0\s+0|0\s+0\s+0\s+0)(?=[;\}]|\!important)#i',
            // Replace `background-position:0` with `background-position:0 0`
            '#(background-position):0(?=[;\}])#si',
            // Replace `0.6` with `.6`, but only when preceded by `:`, `,`, `-` or a white-space
            '#(?<=[\s:,\-])0+\.(\d+)#s',
            // Minify string value
            '#(\/\*(?>.*?\*\/))|(?<!content\:)([\'"])([a-z_][a-z0-9\-_]*?)\2(?=[\s\{\}\];,])#si',
            '#(\/\*(?>.*?\*\/))|(\burl\()([\'"])([^\s]+?)\3(\))#si',
            // Minify HEX color code
            '#(?<=[\s:,\-]\#)([a-f0-6]+)\1([a-f0-6]+)\2([a-f0-6]+)\3#i',
            // Replace `(border|outline):none` with `(border|outline):0`
            '#(?<=[\{;])(border|outline):none(?=[;\}\!])#',
            // Remove empty selector(s)
            '#(\/\*(?>.*?\*\/))|(^|[\{\}])(?:[^\s\{\}]+)\{\}#s'
        ),
        array(
            '$1',
            '$1$2$3$4$5$6$7',
            '$1',
            ':0',
            '$1:0 0',
            '.$1',
            '$1$3',
            '$1$2$4$5',
            '$1$2$3',
            '$1:0',
            '$1$2'
        ),
    $input);
}

// JavaScript Minifier
function areoi_minify_js($input) {
    if(trim($input) === "") return $input;
    return preg_replace(
        array(
            // Remove comment(s)
            '#\s*("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')\s*|\s*\/\*(?!\!|@cc_on)(?>[\s\S]*?\*\/)\s*|\s*(?<![\:\=])\/\/.*(?=[\n\r]|$)|^\s*|\s*$#',
            // Remove white-space(s) outside the string and regex
            '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/)|\/(?!\/)[^\n\r]*?\/(?=[\s.,;]|[gimuy]|$))|\s*([!%&*\(\)\-=+\[\]\{\}|;:,.<>?\/])\s*#s',
            // Remove the last semicolon
            '#;+\}#',
            // Minify object attribute(s) except JSON attribute(s). From `{'foo':'bar'}` to `{foo:'bar'}`
            '#([\{,])([\'])(\d+|[a-z_][a-z0-9_]*)\2(?=\:)#i',
            // --ibid. From `foo['bar']` to `foo.bar`
            '#([a-z0-9_\)\]])\[([\'"])([a-z_][a-z0-9_]*)\2\]#i'
        ),
        array(
            '$1',
            '$1$2',
            '}',
            '$1$3',
            '$1.$3'
        ),
    $input);
}

function areoi_get_option_colors()
{
	global $areoi_theme_colors;

	$has_css = areoi2_get_option( 'areoi-dashboard-global-bootstrap-css', true );

	$abs_colors = array();

	if ( $has_css ) {
		$abs_colors = array(
			array( 'name' => 'primary', 'color' => $areoi_theme_colors['primary'], 'slug' => 'primary' ),
			array( 'name' => 'secondary', 'color' => $areoi_theme_colors['secondary'], 'slug' => 'secondary' ),
			array( 'name' => 'success', 'color' => $areoi_theme_colors['success'], 'slug' => 'success' ),
			array( 'name' => 'info', 'color' => $areoi_theme_colors['info'], 'slug' => 'info' ),
			array( 'name' => 'warning', 'color' => $areoi_theme_colors['warning'], 'slug' => 'warning' ),
			array( 'name' => 'danger', 'color' => $areoi_theme_colors['danger'], 'slug' => 'danger' ),
			array( 'name' => 'light', 'color' => $areoi_theme_colors['light'], 'slug' => 'light' ),
			array( 'name' => 'dark', 'color' => $areoi_theme_colors['dark'], 'slug' => 'dark' ),
			array( 'name' => 'body', 'color' => $areoi_theme_colors['body'], 'slug' => 'body' ),
			array( 'name' => 'white', 'color' => '#fff', 'slug' => 'white' ),
		);
	}

	$existing_colors = get_theme_support( 'editor-color-palette' );

	if ( empty( $existing_colors[0] ) ) {
		$existing_colors = array();
	} else {
		$existing_colors = $existing_colors[0];
	}

	$all_colors = array_merge( $existing_colors, $abs_colors );

	if ( empty( $all_colors ) ) return array();

	$colors = array();
	foreach ( $all_colors as $color_key => $color ) {
		$key = $color['slug'] . $color['color'];
		$colors[$key] = $color;
	}

	return array_values( $colors );
}

function areoi_get_option_color( $option, $default )
{
	$value = areoi2_get_option( $option, $default );

	if ( strpos( $value, '#' ) === false ) $value = $default;

	return $value;
}

function areoi_is_lightspeed()
{
	$current_theme = wp_get_theme();
	return ( $current_theme && $current_theme->template == 'lightspeed' ) || areoi2_get_option( 'areoi-dashboard-global-include-lightspeed', false );
}

function areoi_has_plugin( $plugin )
{
	return in_array( $plugin, areoi2_get_option('active_plugins') );
}

function areoi_has_plugin_ninja_forms()
{
	return areoi_has_plugin( 'ninja-forms/ninja-forms.php' );
}

function areoi_get_utilities_bg()
{
	$data = array(
		array(
			'label' => 'Default',
			'value' => null
		),
		array(
			'label' => 'Primary',
			'value' => 'bg-primary'
		),
		array(
			'label' => 'Secondary',
			'value' => 'bg-secondary'
		),
		array(
			'label' => 'Success',
			'value' => 'bg-success'
		),
		array(
			'label' => 'Warning',
			'value' => 'bg-warning'
		),
		array(
			'label' => 'Danger',
			'value' => 'bg-danger'
		),
		array(
			'label' => 'Info',
			'value' => 'bg-info'
		),
		array(
			'label' => 'Dark',
			'value' => 'bg-dark'
		),
		array(
			'label' => 'Light',
			'value' => 'bg-light'
		),
		array(
			'label' => 'Body',
			'value' => 'bg-body'
		),
	);

	$data = apply_filters( 'areoi_get_utilities_bg', $data );

	return json_encode( $data );
}

function areoi_get_utilities_text()
{
	$data = array(
		array(
			'label' => 'Default',
			'value' => null
		),
		array(
			'label' => 'Primary',
			'value' => 'text-primary'
		),
		array(
			'label' => 'Secondary',
			'value' => 'text-secondary'
		),
		array(
			'label' => 'Success',
			'value' => 'text-success'
		),
		array(
			'label' => 'Warning',
			'value' => 'text-warning'
		),
		array(
			'label' => 'Danger',
			'value' => 'text-danger'
		),
		array(
			'label' => 'Info',
			'value' => 'text-info'
		),
		array(
			'label' => 'Dark',
			'value' => 'text-dark'
		),
		array(
			'label' => 'Light',
			'value' => 'text-light'
		),
	);

	$data = apply_filters( 'areoi_get_utilities_text', $data );

	return json_encode( $data );
}

function areoi_get_utilities_border()
{
	$data = array(
		array(
			'label' => 'Default',
			'value' => null
		),
		array(
			'label' => 'Primary',
			'value' => 'border-primary'
		),
		array(
			'label' => 'Secondary',
			'value' => 'border-secondary'
		),
		array(
			'label' => 'Success',
			'value' => 'border-success'
		),
		array(
			'label' => 'Warning',
			'value' => 'border-warning'
		),
		array(
			'label' => 'Danger',
			'value' => 'border-danger'
		),
		array(
			'label' => 'Info',
			'value' => 'border-info'
		),
		array(
			'label' => 'Dark',
			'value' => 'border-dark'
		),
		array(
			'label' => 'Light',
			'value' => 'border-light'
		),
	);

	$data = apply_filters( 'areoi_get_utilities_border', $data );

	return json_encode( $data );
}