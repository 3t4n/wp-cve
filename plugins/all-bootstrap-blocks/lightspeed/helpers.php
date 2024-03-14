<?php 
function lightspeed_list_files( $directory )
{
	if ( file_exists( $directory ) ) {

		$list = array_values( array_diff( scandir( $directory ), array( '..', '.', 'index.php' ) ) );

		return array_combine( $list, $list );
	}
	return array();
}

function lightspeed_list_files_with_dir( $directory )
{
	if ( file_exists( $directory ) ) {

		$lists = array_values( array_diff( scandir( $directory ), array( '..', '.', 'index.php' ) ) );

		$files = array();
		foreach ( $lists as $list_key => $list ) {
			$files[$list] = $directory . $list;
		}

		return $files;
	}
	return array();
}

function lightspeed_list_files_with_uri( $directory, $uri )
{
	if ( file_exists( $directory ) ) {

		$lists = array_values( array_diff( scandir( $directory ), array( '..', '.', 'index.php' ) ) );

		$files = array();
		foreach ( $lists as $list_key => $list ) {
			$files[$list] = $uri . $list;
		}

		return $files;
	}
	return array();
}

function lightspeed_get_custom_directory( $is_child = false )
{
	if ( $is_child ) {
		$template = get_stylesheet_directory();
	} else {
		$template = get_template_directory();
	}
	return $template . '/all-bootstrap-blocks/lightspeed/custom/';
}

function lightspeed_get_custom_directory_uri( $is_child = false )
{
	if ( $is_child ) {
		$template = get_stylesheet_directory_uri();
	} else {
		$template = get_template_directory_uri();
	}
	return $template . '/all-bootstrap-blocks/lightspeed/custom/';
}

function lightspeed_get_placeholder_path()
{
	$placeholder = areoi2_get_option( 'areoi-lightspeed-company-stock-media', 'default' );

	if ( !$placeholder ) $placeholder = 'default';

	$directory = AREOI__PLUGIN_LIGHTSPEED_DIR . 'placeholders/';

	$theme_directory = get_template_directory() . '/all-bootstrap-blocks/lightspeed/placeholders/';
	if ( file_exists( $theme_directory . $placeholder ) ) {
		$directory = get_template_directory() . '/all-bootstrap-blocks/lightspeed/placeholders/';
	}

	$theme_directory = get_stylesheet_directory() . '/all-bootstrap-blocks/lightspeed/placeholders/';
	if ( file_exists( $theme_directory . $placeholder ) ) {
		$directory = get_stylesheet_directory() . '/all-bootstrap-blocks/lightspeed/placeholders/';
	}

	return $directory . $placeholder;
}

function lightspeed_get_placeholder_uri()
{
	$placeholder = areoi2_get_option( 'areoi-lightspeed-company-stock-media', 'default' );

	if ( !$placeholder ) $placeholder = 'default';

	$directory = AREOI__PLUGIN_LIGHTSPEED_URI . 'placeholders/';

	$theme_directory = get_template_directory() . '/all-bootstrap-blocks/lightspeed/placeholders/';
	if ( file_exists( $theme_directory . $placeholder ) ) {
		$directory = get_template_directory_uri() . '/all-bootstrap-blocks/lightspeed/placeholders/';
	}

	$theme_directory = get_stylesheet_directory() . '/all-bootstrap-blocks/lightspeed/placeholders/';
	if ( file_exists( $theme_directory . $placeholder ) ) {
		$directory = get_stylesheet_directory_uri() . '/all-bootstrap-blocks/lightspeed/placeholders/';
	}
	
	return $directory . $placeholder;
}

function lightspeed_get_block_templates( $block_folders, $is_blocks = true )
{
	$templates = array();

	foreach ( $block_folders as $block_folder_key => $block_folder ) {
		$directory = AREOI__PLUGIN_LIGHTSPEED_DIR . ( $is_blocks ? 'blocks/' : '' ) . $block_folder . '/';
		$plugin_templates = lightspeed_list_files( $directory );

		$theme_directory = get_template_directory() . '/all-bootstrap-blocks/lightspeed/' . $block_folder . '/';
		$theme_templates = lightspeed_list_files( $theme_directory );

		$child_templates = array();
		if ( is_child_theme() ) {
			$child_directory = get_stylesheet_directory() . '/all-bootstrap-blocks/lightspeed/' . $block_folder . '/';
			$child_templates = lightspeed_list_files( $child_directory );
		}
		
		$all_templates = array_merge( $plugin_templates, $theme_templates, $child_templates );
		sort( $all_templates, SORT_NATURAL | SORT_FLAG_CASE );

		$block_templates = array(
			array( 'value' => '', 'label' => 'Default' )
		);
		foreach ( $all_templates as $template ) {

			$label = str_replace( '.php', '', $template );
			$label = str_replace( '.svg', '', $label );
			$label = str_replace( '-', ' ', $label );
			$label = ucwords( $label );

			$block_templates[] = array(
				'value' => $template,
				'label'	=> $label
			);
		}

		$templates[$block_folder_key] = $block_templates;	
	}
	return $templates;
}

function lightspeed_get_template( $block_type, $block_order, $with_path = true )
{
	$alternate 	= ( $block_order % 2 == 0 && in_array( $block_type, array( 'content-with-media' ) ) ) ? '-alternate' : '';
	$fallback 	= areoi2_get_option( 'areoi-lightspeed-blocks-' . $block_type . $alternate );
	if ( !$fallback ) $fallback = areoi2_get_option( 'areoi-lightspeed-blocks-' . $block_type );
	if ( !$fallback ) $fallback = 'basic.php';
	$template 	= lightspeed_get_template_directory( 
		$block_type, 
		'templates/' . lightspeed_get_attribute( 'filename', $fallback ) 
	);
	
	if ( $with_path ) return $template;

	return lightspeed_get_attribute( 'filename', $fallback );
}

function lightspeed_get_is_first_strip()
{
	global $lightspeed_block_order;

	$header_template_part 	= get_block_template( get_stylesheet() . '//' . 'header', 'wp_template_part' );
    $position = null;
    if ( $header_template_part ) {
    	$header_blocks = parse_blocks( $header_template_part->content );
    	foreach ( $header_blocks as $block_key => $block ) {
    		if ( !empty( $block['attrs']['position'] ) && in_array( $block['attrs']['position'], array( 'position-fixed', 'position-absolute' ) ) ) {
    			$position = true;
    		}
    	}
	}

	$is_first_strip = false;
	if ( $position && $lightspeed_block_order == 1 ) $is_first_strip = true;

	return $is_first_strip;
}

function lightspeed_get_block_styles( $is_first_strip, $padding, $padding_top, $mobile_padding, $mobile_padding_top )
{
	$styles 	= '
		.' . lightspeed_get_block_id() . '.areoi-lightspeed-block,
		.' . lightspeed_get_block_id() . ' .areoi-background-pattern-media {
			padding: ' . $mobile_padding . 'px 0;
			padding-top: ' . $mobile_padding_top . 'px;
		}
		.' . lightspeed_get_block_id() . '.areoi-lightspeed-block img,
		.' . lightspeed_get_block_id() . '.areoi-lightspeed-block video {
			display: block;
		}
		.' . lightspeed_get_block_id() . '.areoi-lightspeed-block > div.container > .row:not(.block-editor-block-preview__content-iframe .row),
		.' . lightspeed_get_block_id() . '.areoi-lightspeed-block > div.container-fluid > .row:not(.block-editor-block-preview__content-iframe .row) {
			min-height: calc( ' . lightspeed_get_attribute( 'size', '100vh' ) . ' - ' . ( $mobile_padding + $mobile_padding_top ) . 'px );
		}
		@media only screen and (min-width: ' . areoi2_get_option( 'areoi-layout-grid-grid-breakpoint-lg', '992px' ) . ') {
			.' . lightspeed_get_block_id() . '.areoi-lightspeed-block,
			.' . lightspeed_get_block_id() . ' .areoi-background-pattern-media {
				padding: ' . $padding . 'px 0;
				padding-top: ' . $padding_top . 'px;
			}
			.' . lightspeed_get_block_id() . '.areoi-lightspeed-block > div.container > .row:not(.block-editor-block-preview__content-iframe .row),
			.' . lightspeed_get_block_id() . '.areoi-lightspeed-block > div.container-fluid > .row:not(.block-editor-block-preview__content-iframe .row) {
				min-height: calc( ' . lightspeed_get_attribute( 'size', '100vh' ) . ' - ' . ( $padding + $padding_top ) . 'px );
			}
		}
	';

	$mask = null;
	if ( lightspeed_get_attribute( 'mask', false ) ) $mask = lightspeed_get_attribute( 'mask', false );
	$mask_template 	= lightspeed_get_masks_directory_uri( $mask );
	if ( $mask ) {
		$styles .= '
			.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .areoi-has-mask {
				mask-image: url(' . $mask_template . '); 
				-webkit-mask-image: url(' . $mask_template . ');
			}
		';
	}

	$introduction_color = lightspeed_get_attribute( 'introduction_color', lightspeed_get_default_color( 'text' ) );
	$link_color = lightspeed_get_theme_color( $introduction_color );
	
	$styles .= '
		.' . lightspeed_get_block_id() . '.areoi-lightspeed-block a:not(.btn) {
			color: ' . $link_color . ';
		}
		.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .bg-dark a:not(.btn) {
			color: ' . lightspeed_get_theme_color( 'light' ) . ';
		}
		.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .bg-light a:not(.btn) {
			color: ' . lightspeed_get_theme_color( 'dark' ) . ';
		}
	';

	return $styles;
}

function lightspeed_get_divider( $block_order )
{
	
	$divider = areoi2_get_option( 'areoi-lightspeed-styles-strip-divider', 'none.svg' );
	if ( $block_order == 1 ) $divider = 'none.svg';
	if ( lightspeed_get_attribute( 'divider', false ) ) $divider = lightspeed_get_attribute( 'divider', false );
	
	return $divider;
}

function lightspeed_get_divider_styles( $block_order )
{
	$divider = null;
	if ( $block_order == 1 ) $divider = 'none.svg';
	if ( lightspeed_get_attribute( 'divider', false ) ) $divider = lightspeed_get_attribute( 'divider', false );
	$divider_template 	= lightspeed_get_dividers_directory_uri( $divider );
	
	$divider_styles = '';
	if ( $divider ) {
		$divider_styles .= '
			mask-image: url(' . $divider_template . '); 
			-webkit-mask-image: url(' . $divider_template . ');
		';
	}

	return $divider_styles;
}

function lightspeed_set_attribute( $key, $value )
{
	global $lightspeed_attributes;
	
	$lightspeed_attributes[$key] = $value;
}

function lightspeed_get_attribute( $key, $default = null )
{
	global $lightspeed_attributes;
	
	if ( 
		isset( $lightspeed_attributes[$key] ) && 
		( !empty( $lightspeed_attributes[$key] ) || $lightspeed_attributes[$key] === false ) && 
		$lightspeed_attributes[$key] !== 'Default' 
	) {
		return $lightspeed_attributes[$key];
	}
	return $default;
}

function lightspeed_attribute( $key, $default = null )
{
	echo lightspeed_get_attribute( $key, $default );
}

function lightspeed_get_block_id_append()
{
	global $lightspeed_attributes;

	if ( !empty( $lightspeed_attributes['block_id'] ) ) {
		return $lightspeed_attributes['block_id'];
	}
	return md5( serialize( $lightspeed_attributes ) . date( 'H:i:s' ) );
}

function lightspeed_get_block_id()
{
	return 'block-' . lightspeed_get_block_id_append();
}

function lightspeed_block_id()
{
	echo lightspeed_get_block_id();
}

function lightspeed_get_block_classes( $block )
{
	$classes = array();
	$classes[] = lightspeed_get_block_id();
	$classes[] = 'areoi-lightspeed-block';
	$classes[] = 'areoi-lightspeed-' . $block;
	$classes[] = 'areoi-divider';

	if ( lightspeed_get_attribute( 'align', false ) ) {
		$classes[] = 'align' . lightspeed_get_attribute( 'align', false );
	}

	$divider = areoi2_get_option( 'areoi-lightspeed-styles-strip-divider', false );
	if ( $divider && $divider != 'none.svg' ) {
		$classes[] = 'areoi-divider-styled';
	}

	$pattern = areoi2_get_option( 'areoi-lightspeed-styles-strip-pattern', false );
	if ( $pattern && !empty( lightspeed_get_attribute( 'exclude_pattern', false ) ) ) {
		$classes[] = 'areoi-pattern-none';
	}

	$transition = areoi2_get_option( 'areoi-lightspeed-transition-transition', false );
	if ( $transition && !empty( lightspeed_get_attribute( 'exclude_transition', false ) ) ) {
		$classes[] = 'areoi-transition-none';
	}

	$parallax = areoi2_get_option( 'areoi-lightspeed-parallax-parallax', false );
	if ( $parallax && !empty( lightspeed_get_attribute( 'exclude_parallax', false ) ) ) {
		$classes[] = 'areoi-parallax-none';
	}

	if ( lightspeed_get_attribute( 'utilities_bg', null ) && lightspeed_get_attribute( 'utilities_bg', null ) != 'Default' ) $classes[] = lightspeed_get_attribute( 'utilities_bg', null );
	if ( lightspeed_get_attribute( 'utilities_text', null ) && lightspeed_get_attribute( 'utilities_text', null ) != 'Default' ) $classes[] = lightspeed_get_attribute( 'utilities_text', null );
	if ( lightspeed_get_attribute( 'utilities_border', null ) && lightspeed_get_attribute( 'utilities_border', null ) != 'Default' ) $classes[] = lightspeed_get_attribute( 'utilities_border', null );

	return implode( ' ', $classes );
}

function lightspeed_get_media_col_class()
{
	$media_fit = lightspeed_get_attribute( 'media_fit', 'cover' );

	$classes = array();
	$classes[] = 'areoi-media-col';
	$classes[] = 'areoi-media-col-' . $media_fit;

	return implode( ' ', $classes );
}

function lightspeed_media_col_class()
{
	echo lightspeed_get_media_col_class();
}

function lightspeed_block_classes( $block )
{
	echo implode( ' ', lightspeed_get_block_classes( $block ) );
}

function lightspeed_get_template_directory( $block, $filename )
{
	$template = '';

	$theme_dir = get_template_directory() . '/all-bootstrap-blocks/lightspeed/' . $block . '/' . $filename;
	$theme_dir_custom = get_template_directory() . '/all-bootstrap-blocks/lightspeed/custom/' . $block . '/' . $filename;
	$child_dir = get_stylesheet_directory() . '/all-bootstrap-blocks/lightspeed/' . $block . '/' . $filename;
	$child_custom_dir = get_stylesheet_directory() . '/all-bootstrap-blocks/lightspeed/custom/' . $block . '/' . $filename;

	if ( file_exists( $child_dir ) ) {
		$template = $child_dir;
	} elseif ( file_exists( $child_custom_dir ) ) {
		$template = $child_custom_dir;
	} elseif ( file_exists( $theme_dir ) ) {
		$template = $theme_dir;
	} elseif ( file_exists( $theme_dir_custom ) ) {
		$template = $theme_dir_custom;
	} else {
		$template = AREOI__PLUGIN_LIGHTSPEED_DIR . 'blocks/' . $block . '/' . $filename;
	}
	
	return $template;
}

function lightspeed_get_content_directory( $filename )
{
	$template = '';

	$theme_dir = get_template_directory() . '/all-bootstrap-blocks/lightspeed/content/' . $filename;

	$child_dir = get_stylesheet_directory() . '/all-bootstrap-blocks/lightspeed/content/' . $filename;

	if ( file_exists( $child_dir ) ) {
		$template = $child_dir;
	} elseif ( file_exists( $theme_dir ) ) {
		$template = $theme_dir;
	} else {
		$template = AREOI__PLUGIN_LIGHTSPEED_DIR . 'content/' . $filename;
	}
	
	return $template;
}

function lightspeed_get_patterns_directory( $filename )
{
	$template = '';

	$theme_dir = get_template_directory() . '/all-bootstrap-blocks/lightspeed/patterns/' . $filename;

	$child_dir = get_stylesheet_directory() . '/all-bootstrap-blocks/lightspeed/patterns/' . $filename;

	if ( file_exists( $child_dir ) ) {
		$template = $child_dir;
	} elseif ( file_exists( $theme_dir ) ) {
		$template = $theme_dir;
	} else {
		$template = AREOI__PLUGIN_LIGHTSPEED_DIR . 'patterns/' . $filename;
	}
	
	return $template;
}

function lightspeed_get_dividers_directory_uri( $filename )
{
	$template = '';

	$theme_dir = get_template_directory() . '/all-bootstrap-blocks/lightspeed/dividers/' . $filename;
	$theme_uri = get_template_directory_uri() . '/all-bootstrap-blocks/lightspeed/dividers/' . $filename;

	$child_dir = get_stylesheet_directory() . '/all-bootstrap-blocks/lightspeed/dividers/' . $filename;
	$child_theme_uri = get_stylesheet_directory_uri() . '/all-bootstrap-blocks/lightspeed/dividers/' . $filename;
	
	if ( file_exists( $child_dir ) ) {
		$template = $child_theme_uri;
	} elseif ( file_exists( $theme_dir ) ) {
		$template = $theme_uri;
	} else {
		$template = AREOI__PLUGIN_LIGHTSPEED_URI . 'dividers/' . $filename;
	}
	
	return $template;
}

function lightspeed_get_masks_directory_uri( $filename )
{
	$template = '';

	$theme_dir = get_template_directory() . '/all-bootstrap-blocks/lightspeed/masks/' . $filename;
	$theme_uri = get_template_directory_uri() . '/all-bootstrap-blocks/lightspeed/masks/' . $filename;

	$child_dir = get_stylesheet_directory() . '/all-bootstrap-blocks/lightspeed/masks/' . $filename;
	$child_theme_uri = get_stylesheet_directory_uri() . '/all-bootstrap-blocks/lightspeed/masks/' . $filename;
	
	if ( file_exists( $child_dir ) ) {
		$template = $child_theme_uri;
	} elseif ( file_exists( $theme_dir ) ) {
		$template = $theme_uri;
	} else {
		$template = AREOI__PLUGIN_LIGHTSPEED_URI . 'masks/' . $filename;
	}
	
	return $template;
}

function lightspeed_get_theme_color( $col )
{
	global $areoi_theme_colors;

	$theme_colors = $areoi_theme_colors;

	if ( !is_array( $col ) ) {
		$clean_col = str_replace( 'bg-', '', $col );
		$clean_col = str_replace( 'text-', '', $clean_col );
		$clean_col = str_replace( 'border-', '', $clean_col );
		$clean_col = str_replace( 'hex-', '', $clean_col );

		if ( isset( $theme_colors[$clean_col] ) ) {
			$col = $theme_colors[$clean_col];
		}

	}
	return $col;
}

function lightspeed_hex_to_rgb( $hex )
{
	$R1 = hexdec(substr($hex, 1, 2));
    $G1 = hexdec(substr($hex, 3, 2));
    $B1 = hexdec(substr($hex, 5, 2));

    return [
    	'r' => $R1,
    	'g' => $G1,
    	'b' => $B1,
    ];
}

function lightspeed_get_contrast_color( $col )
{
	if ( !is_array( $col ) ) {
		$col = lightspeed_get_theme_color( $col );

	}
	
    // hex_color RGB
    if ( !is_array( $col ) && str_starts_with( $col, '#' ) ) {
    	$rgb = lightspeed_hex_to_rgb( $col );
    	$R1 = $rgb['r'];
        $G1 = $rgb['g'];
        $B1 = $rgb['b'];
    } else {
    	$R1 = $col['r'];
	    $G1 = $col['g'];
	    $B1 = $col['b'];
    }

    // Black RGB
    $blackColor = "#000000";
    $R2BlackColor = hexdec(substr($blackColor, 1, 2));
    $G2BlackColor = hexdec(substr($blackColor, 3, 2));
    $B2BlackColor = hexdec(substr($blackColor, 5, 2));

     // Calc contrast ratio
     $L1 = 0.2126 * pow($R1 / 255, 2.2) +
           0.7152 * pow($G1 / 255, 2.2) +
           0.0722 * pow($B1 / 255, 2.2);

    $L2 = 0.2126 * pow($R2BlackColor / 255, 2.2) +
          0.7152 * pow($G2BlackColor / 255, 2.2) +
          0.0722 * pow($B2BlackColor / 255, 2.2);

    $contrastRatio = 0;
    if ($L1 > $L2) {
        $contrastRatio = (int)(($L1 + 0.05) / ($L2 + 0.05));
    } else {
        $contrastRatio = (int)(($L2 + 0.05) / ($L1 + 0.05));
    }

    // If contrast is more than 5, return black color
    if ($contrastRatio > 5) {
        return '#000000';
    } else { 
        // if not, return white color.
        return '#FFFFFF';
    }
}

function lightspeed_get_default_color( $type, $override_color = null )
{
	$color = $type == 'btn' ? 'btn-primary' : null;
	$contrast = null;
	
	if ( lightspeed_get_attribute( 'background_display', null ) ) {
		if ( lightspeed_get_attribute( 'background_display_overlay', null ) && lightspeed_get_attribute( 'background_overlay', null ) ) {
			$contrast = lightspeed_get_contrast_color( lightspeed_get_attribute( 'background_overlay', null )['rgb'] );
		} elseif ( lightspeed_get_attribute( 'background_utility', null ) ) {
			$contrast = lightspeed_get_contrast_color( lightspeed_get_attribute( 'background_utility', null ) );
		} elseif ( lightspeed_get_attribute( 'background_color', null ) ) {
			$contrast = lightspeed_get_contrast_color( lightspeed_get_attribute( 'background_color', null )['rgb'] );
		} elseif ( lightspeed_get_attribute( 'main_background_color', null ) ) {
			$contrast = lightspeed_get_contrast_color( lightspeed_get_attribute( 'main_background_color', null )['rgb'] );
		}
	}
	if ( $override_color ) {
		if ( $override_color == 'bg-transparent' && lightspeed_get_attribute( 'block_type', null ) == 'header' ) {
			$contrast = lightspeed_get_contrast_color( 'bg-primary' );
		} else {
			$contrast = lightspeed_get_contrast_color( $override_color );
		}
	}

	if ( $contrast == '#FFFFFF' ) {
		$color = $type != 'logo' ? $type . '-light' : 'light';
	} elseif ( $contrast == '#000000' ) {
		$color = $type != 'logo' ? $type . '-dark' : 'dark';
	}

	if ( $type == 'hex' ) $color = lightspeed_get_theme_color( $color );

	return $color;
}

function lightspeed_get_logo( $type, $color = null )
{
	$logos = array(
		'dark' => areoi2_get_option( 'areoi-lightspeed-company-logo-dark', null ),
		'light' => areoi2_get_option( 'areoi-lightspeed-company-logo-light', null ),
	);

	$icons = array(
		'dark' => areoi2_get_option( 'areoi-lightspeed-company-icon-dark', null ),
		'light' => areoi2_get_option( 'areoi-lightspeed-company-icon-light', null ),
	);

	if ( !$color ) {
		$color = lightspeed_get_default_color( 'logo' );
	}
	
	$content = '';

	switch ( $type ) {
		case 'icon':
			
			if ( !empty( $icons[$color] ) ) {
				$content .= '
				<img src="' . $icons[$color] . '" alt="Go back to Homepage" />
				';
			}

			break;
		
		default:
			
			if ( !empty( $logos[$color] ) ) {
				$content .= '
				<img src="' . $logos[$color] . '" alt="Go back to Homepage" />
				';
			}

			break;
	}

	return $content;
}

function lightspeed_logo( $color = 'dark' )
{
	if ( !empty( lightspeed_get_logo( lightspeed_get_attribute( 'logo', null ), lightspeed_get_attribute( 'logo_color', $color ) ) ) ) : ?>
		<?php echo lightspeed_get_logo( lightspeed_get_attribute( 'logo', null ), lightspeed_get_attribute( 'logo_color', $color ) ) ?>
	<?php else : ?>
		<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 51.4 53.3" xml:space="preserve">
			<path fill="currentColor" xmlns="http://www.w3.org/2000/svg" d="M23.6,2.9L2.8,50.4l0,0c6.7,0,12.8-4,15.5-10.1L34.8,2.9H23.6z"/>
			<path fill="currentColor" xmlns="http://www.w3.org/2000/svg" d="M37.1,39.7c4.3,0,8.4,1.7,11.5,4.7l-11.5-26L25.7,44.3C28.8,41.5,32.9,39.8,37.1,39.7z"/>
		</svg>
	<?php endif;
}

function lightspeed_has_content()
{
	$has_content = false;

	if ( trim( strip_tags( lightspeed_get_attribute( 'heading', null ) ) ) ) $has_content = true;
	if ( trim( strip_tags( lightspeed_get_attribute( 'introduction', null ) ) ) ) $has_content = true;
	if ( trim( strip_tags( lightspeed_get_attribute( 'sub_heading', null ) ) ) ) $has_content = true;
	if ( lightspeed_get_attribute( 'include_cta', null ) ) $has_content = true;

	return $has_content;
}

function lightspeed_get_content( $heading_level, $default_align, $col_class = 'col-lg-6 col-xxl-5' )
{
	$content = '';

	$template 	= lightspeed_get_content_directory( lightspeed_get_attribute( 'content_filename', 'basic.php' ) );

	ob_start(); include( $template ); $content .= ob_get_clean();

	return $content;
}

function lightspeed_content( $heading_level, $default_align, $col_class = 'col-lg-6 col-xxl-5' )
{
	echo lightspeed_get_content( $heading_level, $default_align, $col_class );
}

function lightspeed_get_heading( $level, $item = null, $level_class = '' )
{
	$content = '';

	$original_level = $level;

	$heading_template = '';
	if ( $level < 3 ) $heading_template = areoi2_get_option( 'areoi-lightspeed-styles-headings', null );

	if ( $level == 3 && !lightspeed_get_attribute( 'heading', null ) ) {
		$level = 2;
		$level_class = 'h3';
	}

	$heading = lightspeed_get_attribute( 'heading', null );
	if ( lightspeed_get_attribute( 'is_post_title', null ) ) {
		if ( is_single() || is_page() ) $heading = get_the_title();
		if ( is_archive() ) $heading = get_the_archive_title();
	}
	$heading_color = lightspeed_get_attribute( 'heading_color', lightspeed_get_default_color( 'text' ) );

	$sub_heading = lightspeed_get_attribute( 'sub_heading', null );
	$sub_heading_color = lightspeed_get_attribute( 'sub_heading_color', lightspeed_get_default_color( 'text' ) );

	if ( $item ) {
		$heading = !empty( $item['heading'] ) ? $item['heading'] : '';
		if ( !empty( $item['background_color'] ) ) $heading_color = lightspeed_get_default_color( 'text', $item['background_color'] );
		if ( !empty( $item['heading_color'] ) ) $heading_color = $item['heading_color'];
	}

	$border_color = str_replace( 'text-', 'border-', $heading_color );

	if ( $heading ) {

		$include_icon = areoi2_get_option( 'areoi-lightspeed-styles-heading-icon', null );

		if ( $include_icon && $original_level < 3 ) {
			$content .= '
			<div class="mb-3 ' . $heading_color . ' areoi-heading-icon">
				' . lightspeed_get_logo( 'icon' ) . '
			</div>
			';
		}

		if ( $sub_heading && $original_level < 3 ) {
			$content .= '<p class="mb-2 fw-bold ' . $sub_heading_color . '">' . $sub_heading . '</p>';
		}

		if ( trim( $heading ) || trim( $sub_heading ) ) {
			switch ( $heading_template ) {
				case 'basic':
				case 'dotted':
				case 'wide':
					$content .= '
					<h' . $level . ' class="' . $heading_color . ' ' . $level_class . '">
						' . $heading . '
					</h' . $level . '>
					<span class="h' . $level . ' ' . $border_color . ' areoi-heading-divider areoi-heading-divider-' . $heading_template . '"></span>
					<div class="d-block"></div>
					';
					break;

				case 'basic-top':
				case 'dotted-top':
				case 'wide-top':
					$content .= '
					<span class="h' . $level . ' ' . $border_color . ' areoi-heading-divider areoi-heading-divider-' . $heading_template . '"></span>
					<div class="d-block"></div>
					<h' . $level . ' class="' . $heading_color . ' ' . $level_class . '">
						' . $heading . '
					</h' . $level . '>
					';
					break;
				
				default:
					$content .= '
					<h' . $level . ' class="' . $heading_color . ' ' . $level_class . '">
						' . $heading . '
					</h' . $level . '>
					';
					break;
			}
		}
	}

	return $content;
}

function lightspeed_heading( $level, $item = null, $level_class = '' )
{
	echo lightspeed_get_heading( $level, $item, $level_class );
}

function lightspeed_get_introduction( $item = null )
{
	$content = '';

	$limit =  400;

	$has_cta = lightspeed_get_attribute( 'cta', null );

	$introduction = lightspeed_get_attribute( 'introduction', null );
	if ( lightspeed_get_attribute( 'is_post_excerpt', null ) ) {
		if ( is_single() || is_page() ) $introduction = '<p>' . get_the_excerpt() . '</p>';
		if ( is_archive() ) $introduction = get_the_archive_description();
	}

	$introduction_color = lightspeed_get_attribute( 'introduction_color', lightspeed_get_default_color( 'text' ) );
	$introduction_class = '';
	$modal_id = 'modal-' . lightspeed_get_block_id();
	$heading = lightspeed_get_attribute( 'heading', 'Continue Reading' );

	if ( $item ) {
		$has_cta = $item['cta'];
		$introduction = $item['introduction'];
		if ( !empty( $item['background_color'] ) ) $introduction_color = lightspeed_get_default_color( 'text', $item['background_color'] );
		if ( !empty( $item['introduction_color'] ) ) $introduction_color = $item['introduction_color'];
		$introduction_class = '';
		$modal_id .= '-item-' . $item['id'];
		$heading = !empty( $item['heading'] ) ? $item['heading'] : 'Continue Reading';

		$limit =  220;
	}

	$introduction_strlen = strlen( wp_strip_all_tags( $introduction ) );
	$introduction_short = $introduction;
	if ( ( lightspeed_get_attribute( 'include_read_more', null ) || ( isset( $item['include_read_more'] ) && $item['include_read_more'] ) ) && $introduction_strlen > ($limit*2) ) {
		$introduction_short = substr( strip_tags( str_replace( '</p>', "</p><br><br>", $introduction ), array( '<br>' ) ), 0, $limit );
		$introduction_short .= '... <a href="#'  . $modal_id . '">Continue reading.</a>';
	}

	if ( $introduction != $introduction_short ) {

		$introduction_short = '<p>' . $introduction_short . '</p>';

		global $areoi_introduction_modals;
		$areoi_introduction_modals[$modal_id] = '
		<div id="' . $modal_id . '" class="modal" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">' . $heading . '</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						' . $introduction . '
					</div>
				</div>
			</div>
		</div>
		';
	}

	if ( strip_tags( $introduction ) ) {
		$content .= '
		<div class="' . $introduction_color . ' ' . $introduction_class . '">
			' . $introduction_short . '
			' . ( $has_cta ? '<span></span>' : '' ) . '
		</div>
		';
	}

	return $content;
}

function lightspeed_introduction( $item = null )
{
	echo lightspeed_get_introduction( $item );
}

function lightspeed_get_cta( $item = null, $is_simple = false )
{
	$content = '';

	$btn_class  = 'btn';

	$include 	= lightspeed_get_attribute( 'include_cta', true );
	$href 		= lightspeed_get_href( $item );
	$size 		= lightspeed_get_attribute( 'cta_size', 'btn-lg' );
	$color 		= lightspeed_get_attribute( 'cta_color', lightspeed_get_default_color( 'btn' ) );
	$cta 		= lightspeed_get_attribute( 'cta', null );

	if ( $item ) {
		$include 	= !empty( $item['include_cta'] ) ? $item['include_cta'] : '';
		$size 		= !empty( $item['cta_size'] ) ? $item['cta_size'] : '';
		$cta 		= !empty( $item['cta'] ) ? $item['cta'] : '';
		if ( !empty( $item['background_color'] ) ) $color = lightspeed_get_default_color( 'btn', $item['background_color'] );
		if ( !empty( $item['cta_color'] ) ) $color = $item['cta_color'];
	}

	if ( $is_simple ) {
		$btn_class = 'btn-simple';
		$color = str_replace( 'btn', 'text', $color );
	}

	if ( $include && $cta ) {
		$content .= '<a' . $href . ' class="' . $btn_class . ' areoi-has-url areoi-has-url-small ' . $size . ' ' . $color . '">
			' . lightspeed_get_btn_content( $cta ) . '
		</a>';
	}

	return $content;
}

function lightspeed_cta( $item = null, $is_simple = false )
{
	echo lightspeed_get_cta( $item, $is_simple );
}

function lightspeed_get_search()
{
	$color = lightspeed_get_attribute( 'cta_color', lightspeed_get_default_color( 'btn' ) ); ?>
	<form role="search" method="get" action="<?php echo get_home_url() ?>" class="input-group input-group-lg">
		<input type="search" class="form-control" name="s" value="" placeholder="<?php _e( 'Enter keyword or phrase' ) ?>" required="">
		<button type="submit" class="btn <?php echo $color ?>">Search</button>
	</form>
	<?php
}

function lightspeed_search()
{
	echo lightspeed_get_search();
}

function lightspeed_get_author_url( $color = null )
{
	global $post;
	$author_id = $post->post_author;

	$author 		= get_the_author_meta( 'display_name', $author_id );
	$author_url 	= get_author_posts_url( $author_id );

	return '<a href="' . $author_url . '" class="' . $color . '">' . ( $author ? $author : __( 'Author Display Name' ) ) . '</a>';
}

function lightspeed_author_url( $color = null )
{
	echo lightspeed_get_author_url( $color );
}

function lightspeed_get_media( $item = null )
{
	$content = '';

	$featured_image_id = get_post_thumbnail_id();
	$featured_image = wp_get_attachment_image_src( $featured_image_id, 'full' );

	$video = lightspeed_get_attribute( 'video', null );
	$image = lightspeed_get_attribute( 'image', null );
	if ( lightspeed_get_attribute( 'is_post_image', null ) && $featured_image ) {
		list( $src, $width, $height ) = $featured_image;
		$image = [
			'url' 		=> $src,
			'width' 	=> $width,
			'height' 	=> $height,
			'alt' 		=> ''
		];
		$video = null;
	}

	if ( $item ) {
		$video = !empty( $item['video'] ) ? $item['video'] : null;
		$image = !empty( $item['image'] ) ? $item['image'] : null;
	}

	if ( $video ) {
		$content .= '<video class="img-fluid" autoplay loop playsinline muted>
			<source src="' . $video['url'] . '" />
		</video>';
	} elseif ( $image ) {
		$content .= '<img 
			src="' . $image['url'] . '" 
			width="' . $image['width'] . '" 
			height="' . $image['height'] . '" 
			alt="' . $image['alt'] . '"
		/>';
	}

	return $content;
}

function lightspeed_media( $item = null )
{
	echo lightspeed_get_media( $item );
}

function lightspeed_get_btn_content( $content )
{	
	$selected_icon = areoi2_get_option( 'areoi-lightspeed-styles-btn-icon', null );

	$icon = '';

	if ( $selected_icon ) {
		$icon = '
			<i class="' . $selected_icon . ' ms-3 align-middle " style="font-size: 24px;"></i>
		';
	}
	
	return $content . ' ' . $icon;
}

function lightspeed_get_href( $item = null )
{
	$link = '';
	$url = lightspeed_get_attribute( 'url', null );
	$include_cta = lightspeed_get_attribute( 'include_cta', null );
	$open_in_new_tab = lightspeed_get_attribute( 'opensInNewTab', null );

	if ( $item ) {
		$url = $item['url'];
		$include_cta = $item['include_cta'];
		$open_in_new_tab = $item['opensInNewTab'];
	}

	if ( $url && $include_cta ) {
		$link = ' href="' . $url . '"';
		if ( $open_in_new_tab ) $link .= ' target="_blank"';
	}

	return $link;
}

function lightspeed_href( $item = null )
{
	echo lightspeed_get_href( $item );
}

function lightspeed_get_stretched_link( $item = null )
{
	$link = '';

	$href = lightspeed_get_href( $item );

	if ( $href ) $link .= '<a' . $href . ' class="stretched-link"></a>';

	return $link;
}

function lightspeed_stretched_link( $item = null )
{
	echo lightspeed_get_stretched_link( $item );
}

function lightspeed_btn_content()
{
	echo lightspeed_get_btn_content();
}

function lightspeed_spacer( $default )
{
	if ( lightspeed_get_attribute( 'media_shape' ) == 'square' ) {
		lightspeed_square_spacer();
	} elseif ( lightspeed_get_attribute( 'media_shape' ) == 'rectangle' ) {
		lightspeed_rectangle_spacer();
	} elseif ( lightspeed_get_attribute( 'media_shape' ) == 'tall-rectangle' ) {
		lightspeed_tall_rectangle_spacer();
	} else {
		if ( $default == 'square' ) {
			lightspeed_square_spacer();
		} else {
			lightspeed_rectangle_spacer();
		}
	}
}

function lightspeed_get_square_spacer()
{
	return '<svg class="areoi-square-spacer" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 5 5" style="enable-background:new 0 0 5 5;" xml:space="preserve"></svg>
	';
}

function lightspeed_square_spacer()
{
	echo lightspeed_get_square_spacer();
}

function lightspeed_rectangle_spacer()
{
	echo '<svg class="areoi-square-spacer" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 5 2.5" style="enable-background:new 0 0 5 2.5;" xml:space="preserve"></svg>
	';
}

function lightspeed_tall_rectangle_spacer()
{
	echo '<svg class="areoi-square-spacer" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 5 10" style="enable-background:new 0 0 5 10;" xml:space="preserve"></svg>
	';
}

function lightspeed_get_posts()
{
	$post_type 		= lightspeed_get_attribute( 'post_type', 'post' );
	$display_posts 	= lightspeed_get_attribute( 'display_posts', 'selected' );
	$posts_per_page = lightspeed_get_attribute( 'posts_per_page', '8' );
	$orderby 		= lightspeed_get_attribute( 'orderby', 'title' );
	$order 			= lightspeed_get_attribute( 'order', 'asc' );
	$post_ids 		= lightspeed_get_attribute( 'post_ids', array() );
	$show_all 		= in_array( 'all', $post_ids );
	
	$post__in = $post_ids;
 	$post_parent__in = array();
 	$post_parent__not_in = array();
 	if ( $post_type == 'child-pages' || $display_posts == 'children' ) {
 		$post_parent__in = $post_ids;
 		$post__in = array();
 	}

 	if ( $show_all ) {
 		$post__in = array();
 		$post_parent__in = array('0');
 	}

 	if ( $show_all && $display_posts == 'children' ) {
 		$post__in = array();
 		$post_parent__in = array();
 		$post_parent__not_in = array('0');
 	}

 	$paged = 1;
 	if ( !empty( $_GET['paginate'] ) && lightspeed_get_attribute( 'include_pagination', false ) ) {
 		$paged = esc_attr( $_GET['paginate'] );
 	}
 	$args = array(
 		'posts_per_page'   	=> $posts_per_page,
    	'post_type'        	=> $post_type,
    	'post_parent__in' 	=> $post_parent__in,
    	'post_parent__not_in'=> $post_parent__not_in,
    	'post__in'			=> $post__in,
    	'orderby'			=> $orderby,
    	'order'				=> $order,
    	'paged'				=> $paged,
    	'ignore_sticky_posts' => 1
 	);
 	
 	if ( lightspeed_get_attribute( 'is_post_query', false ) ) {
 		global $wp_query;

 		return $wp_query;
 	} else {
 		return new WP_Query( $args );
 	}
}

function lightspeed_post_pagination( $the_query )
{
	if ( lightspeed_get_attribute( 'include_pagination', false ) ) :
		if ( lightspeed_get_attribute( 'is_post_query', false ) ) {
			$paged = !empty( $_GET['paged'] ) ? esc_attr( $_GET['paged'] ) : 1;
	 		$pages = paginate_links( array(
			    'format' 	=> '?paged=%#%',
			    'current' 	=> max( 1, $paged ),
			    'total'	 	=>  $the_query->max_num_pages,
			) );
		} else {
			$paged = !empty( $_GET['paginate'] ) ? esc_attr( $_GET['paginate'] ) : 1;
	 		$pages = paginate_links( array(
			    'format' 	=> '?paginate=%#%',
			    'current' 	=> max( 1, $paged ),
			    'total'	 	=>  $the_query->max_num_pages,
			) );
		}
		
		$pages = str_replace( 'page-numbers', 'page-numbers btn ' . lightspeed_get_attribute( 'pagination_color', lightspeed_get_default_color( 'btn' ) ), $pages );
		$pages = str_replace( 'current', 'current active', $pages );
	?>
		<div class="text-center p-2">
			<div class="btn-group"><?php echo $pages ?></div>
		</div>
	<?php endif;
}

function lightspeed_get_post_heading( $background_color = null )
{
	$content = '';

	$heading_tag = lightspeed_get_attribute( 'heading', null ) ? 'h3' : 'h2';

	$color = lightspeed_get_attribute( 'heading_color', lightspeed_get_default_color( 'text' ) );
	if ( !empty( $background_color ) ) $color = lightspeed_get_default_color( 'text', $background_color );
	if ( !empty( lightspeed_get_attribute( 'post_title_color', null ) ) ) $color = lightspeed_get_attribute( 'post_title_color', null );

	if ( lightspeed_get_attribute( 'include_title', true ) ) {
		$content .= '<' . $heading_tag . ' class="h3 ' . $color . '">' . get_the_title() . '</' . $heading_tag . '>';
	}

	return $content;
}

function lightspeed_get_post_introduction( $background_color = null )
{
	$content = '';

	$color = lightspeed_get_attribute( 'heading_color', lightspeed_get_default_color( 'text' ) );
	if ( !empty( $background_color ) ) $color = lightspeed_get_default_color( 'text', $background_color );
	if ( !empty( lightspeed_get_attribute( 'post_excerpt_color', null ) ) ) $color = lightspeed_get_attribute( 'post_excerpt_color', null );

	if ( lightspeed_get_attribute( 'include_excerpt', true ) ) {
		$content .= '<div class="' . $color . '">
			<p>' . get_the_excerpt() . '</p>
			<span></span>
		</div>';
	}

	return $content;
}

function lightspeed_get_post_permalink()
{
	$content = '';

	if ( lightspeed_get_attribute( 'include_permalink', true ) ) {
		$content .= '<a href="' . get_the_permalink() . '" class="stretched-link"></a>';
	}

	return $content;
}

function lightspeed_item_icon( $item )
{
	$icon = '';

	if ( !empty( $item['include_icon'] ) ) {
		$icon = '
			<div class="' . $item['icon_style'] . ' areoi-lightspeed-item-icon">
				<div  style="width: ' . ( $item['icon_size'] + 30 ) . 'px; font-size: ' . $item['icon_size'] . 'px;">
					' . lightspeed_get_square_spacer() . '
					<i class="' . $item['icon'] . '"></i>
				</div>
			</div>
		';
	}

	return $icon;
}

function lightspeed_item( $item, $is_post = false, $is_column = false, $is_background = false )
{	
	if ( $is_post ) {
		global $post;
		$background_color = !empty( lightspeed_get_attribute( 'post_background_color', null ) ) ? lightspeed_get_attribute( 'post_background_color', null ) : '';
		if ( $is_background && !$background_color ) $background_color = lightspeed_get_default_color( 'bg', lightspeed_get_attribute( 'background_utility' ) );
		$item['background_color'] = $background_color;
		$media = get_the_post_thumbnail() && lightspeed_get_attribute( 'include_media', true ) ? get_the_post_thumbnail() : '';
		$heading = lightspeed_get_post_heading( $background_color );
		$introduction = lightspeed_get_post_introduction( $background_color );
		$stretched_link = lightspeed_get_post_permalink();

		$item['include_cta'] = true;
		$item['cta'] = __( 'Read more', AREOI__TEXT_DOMAIN );
		$item['opensInNewTab'] = false;
		$item['url'] = get_the_permalink();
		$cta = lightspeed_get_cta( $item, true );
	} else {
		$background_color = !empty( $item['background_color'] ) ? $item['background_color'] : '';
		if ( $is_background && !$background_color ) $background_color = lightspeed_get_default_color( 'bg', lightspeed_get_attribute( 'background_utility' ) );
		$item['background_color'] = $background_color;
		$media = lightspeed_get_media( $item );
		$heading = lightspeed_get_heading( 3, $item );
		$introduction = lightspeed_get_introduction( $item );
		$cta = lightspeed_get_cta( $item, true );
		$stretched_link = lightspeed_stretched_link( $item );
	}

	if ( !$media && !$is_column && areoi2_get_option( 'areoi-lightspeed-company-include-lightspeed', false ) && !lightspeed_item_icon( $item ) ) {
		$media = lightspeed_get_media( array( 'image' => lightspeed_get_placeholder_images( 'rand' ) ) );
	}

	$media_fit = lightspeed_get_attribute( 'media_fit', 'cover' );

	$rounded = '';
	if ( !$is_column ) $rounded = 'rounded overflow-hidden';
	
	?>
	<div class="d-flex flex-column h-100 position-relative <?php echo $is_background ? 'areoi-item-has-background' : '' ?> <?php echo $background_color ?> <?php echo $rounded ?> <?php echo $stretched_link ? 'areoi-has-url' : '' ?>">
		
		<?php if ( $media ) : ?>
		
			<div class="<?php lightspeed_media_col_class() ?> <?php echo $media_fit == 'contain' && $is_background ? 'p-4 pb-0' : '' ?>">
				<div class="areoi-media-col-content">
					<?php lightspeed_spacer( 'square' ) ?>
					<?php echo $media ?>
				</div>
			</div>
		
		<?php endif; ?>


		<div class="flex-grow-1 areoi-item-content card-body position-relative <?php echo $is_background ? 'p-4 text-shadow' : ( $background_color ? 'p-4' : 'p-0') ?> <?php echo !$media && !$is_background ? 'pt-0' : 'pt-4' ?>">
			
			<?php 
			$icon_color = !empty( $item['heading_color'] ) ? $item['heading_color'] : lightspeed_get_default_color( 'text', $background_color );
			if ( lightspeed_item_icon( $item ) ) : ?>
				<div class="<?php echo $icon_color ?> mb-2"><?php echo lightspeed_item_icon( $item ) ?></div>
			<?php endif; ?>

			<?php echo $heading ?>

			<?php echo $introduction ?>

			<?php echo $cta ?>
		</div>

		<?php echo $stretched_link ?>
	</div>

	<?php
}

function lightspeed_has_contact()
{
	$company_address 	= lightspeed_get_attribute( 'company_address', areoi2_get_option( 'areoi-lightspeed-company-address', null ) );
	$company_phone 		= lightspeed_get_attribute( 'company_phone', areoi2_get_option( 'areoi-lightspeed-company-phone', null ) );
	$company_email 		= lightspeed_get_attribute( 'company_email', areoi2_get_option( 'areoi-lightspeed-company-email', null ) );

	if ( $company_address || $company_email || $company_phone ) {
		return true;
	}
	return false;
}

function lightspeed_has_social()
{
	$social_facebook 	= lightspeed_get_attribute( 'social_facebook', areoi2_get_option( 'areoi-lightspeed-company-facebook', null ) );
	$social_twitter 	= lightspeed_get_attribute( 'social_twitter', areoi2_get_option( 'areoi-lightspeed-company-twitter', null ) );
	$social_instagram 	= lightspeed_get_attribute( 'social_instagram', areoi2_get_option( 'areoi-lightspeed-company-instagram', null ) );
	$social_linkedin 	= lightspeed_get_attribute( 'social_linkedin', areoi2_get_option( 'areoi-lightspeed-company-linkedin', null ) );
	$social_tiktok 		= lightspeed_get_attribute( 'social_tiktok', areoi2_get_option( 'areoi-lightspeed-company-tiktok', null ) );
	$social_pinterest 	= lightspeed_get_attribute( 'social_pinterest', areoi2_get_option( 'areoi-lightspeed-company-pinterest', null ) );
	$social_youtube 	= lightspeed_get_attribute( 'social_youtube', areoi2_get_option( 'areoi-lightspeed-company-youtube', null ) );

	if (
		$social_facebook ||
		$social_twitter ||
		$social_instagram ||
		$social_linkedin ||
		$social_tiktok ||
		$social_pinterest ||
		$social_youtube
	) {
		return true;
	}
	return false;
}

function lightspeed_contact( $color = '', $class = '', $include_address = true, $format_address = false, $include_break = false )
{
	$company_address 	= lightspeed_get_attribute( 'company_address', areoi2_get_option( 'areoi-lightspeed-company-address', null ) );
	$company_phone 		= lightspeed_get_attribute( 'company_phone', areoi2_get_option( 'areoi-lightspeed-company-phone', null ) );
	$company_email 		= lightspeed_get_attribute( 'company_email', areoi2_get_option( 'areoi-lightspeed-company-email', null ) );
	?>
		
	<?php if ( !lightspeed_get_attribute( 'exclude_company', false ) ) : ?>
		<?php if ( $company_address && $include_address ) : ?>
			<p class="<?php echo $class ?> <?php echo $color ?>">
				<svg class="me-1" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="currentColor"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 12c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm6-1.8C18 6.57 15.35 4 12 4s-6 2.57-6 6.2c0 2.34 1.95 5.44 6 9.14 4.05-3.7 6-6.8 6-9.14zM12 2c4.2 0 8 3.22 8 8.2 0 3.32-2.67 7.25-8 11.8-5.33-4.55-8-8.48-8-11.8C4 5.22 7.8 2 12 2z"/></svg>
				<?php echo $format_address ? str_replace( ',', ',<br>', $company_address ) : $company_address ?>
			</p>
		<?php endif; ?>

		<?php if ( $company_email || $company_phone ) : ?>
			<p class="<?php echo $class ?> <?php echo $color ?>">
				<?php if ( $company_email ) : ?>
					<svg class="me-1" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="currentColor"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M22 6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6zm-2 0l-8 5-8-5h16zm0 12H4V8l8 5 8-5v10z"/></svg>
					<a href="mailto:<?php echo $company_email ?>" class="<?php echo $color ?>"><?php echo $company_email ?></a>
				<?php endif; ?>

				<?php if ( $include_break ) : ?>
					<span class="d-block mb-2"></span>
				<?php endif; ?>

				<?php if ( $company_phone ) : ?>
					<svg class="me-1 <?php echo ( !$include_break && $company_email ) ? 'ms-2' : '' ?>" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="currentColor"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M6.54 5c.06.89.21 1.76.45 2.59l-1.2 1.2c-.41-1.2-.67-2.47-.76-3.79h1.51m9.86 12.02c.85.24 1.72.39 2.6.45v1.49c-1.32-.09-2.59-.35-3.8-.75l1.2-1.19M7.5 3H4c-.55 0-1 .45-1 1 0 9.39 7.61 17 17 17 .55 0 1-.45 1-1v-3.49c0-.55-.45-1-1-1-1.24 0-2.45-.2-3.57-.57-.1-.04-.21-.05-.31-.05-.26 0-.51.1-.71.29l-2.2 2.2c-2.83-1.45-5.15-3.76-6.59-6.59l2.2-2.2c.28-.28.36-.67.25-1.02C8.7 6.45 8.5 5.25 8.5 4c0-.55-.45-1-1-1z"/></svg>
					<a href="tel:<?php echo $company_phone ?>" class="<?php echo $color ?>"><?php echo $company_phone ?></a>
				<?php endif; ?>
				
			</p>
		<?php endif; ?>

	<?php endif; 
}

function lightspeed_social( $color = '', $align = '' )
{
	$social_facebook 	= lightspeed_get_attribute( 'social_facebook', areoi2_get_option( 'areoi-lightspeed-company-facebook', null ) );
	$social_twitter 	= lightspeed_get_attribute( 'social_twitter', areoi2_get_option( 'areoi-lightspeed-company-twitter', null ) );
	$social_instagram 	= lightspeed_get_attribute( 'social_instagram', areoi2_get_option( 'areoi-lightspeed-company-instagram', null ) );
	$social_linkedin 	= lightspeed_get_attribute( 'social_linkedin', areoi2_get_option( 'areoi-lightspeed-company-linkedin', null ) );
	$social_tiktok 		= lightspeed_get_attribute( 'social_tiktok', areoi2_get_option( 'areoi-lightspeed-company-tiktok', null ) );
	$social_pinterest 	= lightspeed_get_attribute( 'social_pinterest', areoi2_get_option( 'areoi-lightspeed-company-pinterest', null ) );
	$social_youtube 	= lightspeed_get_attribute( 'social_youtube', areoi2_get_option( 'areoi-lightspeed-company-youtube', null ) );

	$margin 			= $align == '' ? 'me-2' : ( strpos( $align, 'end' ) !== false ? 'ms-2' : 'ms-1 me-1' );
	
	if (
		!lightspeed_get_attribute( 'exclude_social', false ) &&
		(
			$social_facebook ||
			$social_twitter ||
			$social_instagram ||
			$social_linkedin ||
			$social_tiktok ||
			$social_pinterest ||
			$social_youtube
		)
	) : ?>
		<div class="areoi-social d-flex <?php echo $color ?> <?php echo $align ?>">

			<?php if ( $social_facebook ) : ?>
				<a href="<?php echo $social_facebook ?>" target="_blank" class="<?php echo $color ?> areoi-has-url areoi-has-url-small">
					<svg class="<?php echo $margin ?>" xmlns="http://www.w3.org/2000/svg" height="30px" viewBox="0 0 24 24" width="30px" fill="currentColor"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M3.2,3.3h17.5v17.5H3.2V3.3z M18.1,6.8h-2.2c-0.8,0-1.6,0.3-2.2,0.9c-0.6,0.6-0.9,1.3-0.9,2.2V12h-1.8v2.6h1.8v6.1h2.6   v-6.1h2.6V12h-2.6v-1.8c0-0.2,0.1-0.4,0.2-0.6c0.2-0.2,0.4-0.3,0.6-0.3h1.8V6.8z"/></svg>
				</a>
			<?php endif; ?>

			<?php if ( $social_twitter ) : ?>
				<a href="<?php echo $social_twitter ?>" target="_blank" class="<?php echo $color ?> areoi-has-url areoi-has-url-small">
					<svg class="<?php echo $margin ?>" xmlns="http://www.w3.org/2000/svg" height="30px" viewBox="0 0 24 24" width="30px" fill="currentColor"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19.1,3.1c0.5,0,0.9,0.2,1.3,0.5s0.5,0.8,0.5,1.2v14.3c0,0.5-0.2,0.9-0.5,1.2s-0.8,0.5-1.3,0.5H4.9c-0.5,0-0.9-0.2-1.3-0.5   s-0.5-0.8-0.5-1.2V4.8c0-0.5,0.2-0.9,0.5-1.2s0.8-0.5,1.3-0.5H19.1z M17.1,9.6c0.6-0.5,1-0.9,1.2-1.3c-0.4,0.2-0.8,0.3-1.3,0.4   c0.5-0.4,0.8-0.8,1-1.3c-0.6,0.3-1.1,0.5-1.5,0.6c-0.3-0.4-0.8-0.6-1.3-0.7c-0.5-0.1-1,0-1.5,0.2c-0.5,0.2-0.8,0.6-1.1,1   s-0.3,1-0.2,1.6C10.4,10,8.8,9.2,7.4,7.7C7.1,8.2,7,8.9,7.2,9.5c0.2,0.7,0.5,1.1,0.9,1.4c-0.3,0-0.6-0.1-1-0.3c0,1.2,0.6,2,1.8,2.4   c-0.3,0.1-0.7,0.1-1,0c0.3,1,1.1,1.6,2.2,1.7c-0.4,0.4-1,0.6-1.6,0.8c-0.7,0.2-1.3,0.2-1.9,0.1c1.3,0.8,2.5,1.1,3.9,1.1   c2-0.1,3.5-0.8,4.8-2.1S17.1,11.7,17.1,9.6z"/></svg>
				</a>
			<?php endif; ?>

			<?php if ( $social_instagram ) : ?>
				<a href="<?php echo $social_instagram ?>" target="_blank" class="<?php echo $color ?> areoi-has-url areoi-has-url-small">
					<svg class="<?php echo $margin ?>" xmlns="http://www.w3.org/2000/svg" height="30px" viewBox="0 0 24 24" width="30px" fill="currentColor"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19.2,3c0.5,0,0.9,0.2,1.3,0.5C20.9,3.9,21,4.3,21,4.8v14.5c0,0.5-0.2,0.9-0.5,1.2c-0.4,0.4-0.8,0.5-1.3,0.5H4.8   c-0.5,0-0.9-0.2-1.3-0.5S3,19.7,3,19.2V4.8c0-0.5,0.2-0.9,0.5-1.2S4.3,3,4.8,3H19.2z M5.2,19.2h13.6c0.3,0,0.5-0.2,0.5-0.5v-7.7   h-1.9c0.1,0.3,0.1,0.6,0.1,0.9c0,1.5-0.5,2.8-1.6,3.8s-2.3,1.6-3.8,1.6s-2.8-0.5-3.8-1.6S6.6,13.5,6.6,12c0-0.3,0-0.6,0.1-0.9H4.8   v7.7C4.8,19.1,4.9,19.2,5.2,19.2z M14.6,9.5C13.9,8.8,13,8.4,12,8.4s-1.9,0.4-2.6,1.1C8.7,10.2,8.4,11,8.4,12s0.4,1.8,1.1,2.5   c0.7,0.7,1.6,1.1,2.6,1.1s1.9-0.4,2.6-1.1c0.7-0.7,1.1-1.6,1.1-2.5S15.3,10.2,14.6,9.5z M19.2,7V5.2c0-0.3-0.2-0.5-0.5-0.5H17   c-0.3,0-0.5,0.2-0.5,0.5V7c0,0.3,0.2,0.4,0.5,0.4h1.8C19.1,7.5,19.2,7.3,19.2,7z"/></svg>
				</a>
			<?php endif; ?>

			<?php if ( $social_linkedin ) : ?>
				<a href="<?php echo $social_linkedin ?>" target="_blank" class="<?php echo $color ?> areoi-has-url areoi-has-url-small">
					<svg class="<?php echo $margin ?>" xmlns="http://www.w3.org/2000/svg" height="30px" viewBox="0 0 24 24" width="30px" fill="currentColor"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19.2,3c0.5,0,0.9,0.2,1.3,0.5C20.9,3.9,21,4.3,21,4.8v14.5c0,0.5-0.2,0.9-0.5,1.2c-0.4,0.4-0.8,0.5-1.3,0.5H4.8   c-0.5,0-0.9-0.2-1.3-0.5S3,19.7,3,19.2V4.8c0-0.5,0.2-0.9,0.5-1.2S4.3,3,4.8,3H19.2z M5.9,8.2C6.2,8.5,6.6,8.7,7,8.7   s0.8-0.2,1.2-0.5C8.5,7.9,8.7,7.5,8.7,7c0-0.5-0.2-0.8-0.5-1.2C7.9,5.6,7.5,5.4,7,5.4S6.2,5.6,5.9,5.9C5.5,6.2,5.4,6.6,5.4,7   C5.4,7.5,5.5,7.9,5.9,8.2z M8.4,18.3v-8.1H5.7v8.1H8.4z M18.3,18.3v-5.1c0-0.9-0.3-1.6-0.9-2.2S16,10,15.2,10   c-0.4,0-0.8,0.1-1.3,0.4c-0.4,0.2-0.7,0.5-1,0.9v-1.1h-2.7v8.1h2.7v-4.8c0-0.4,0.1-0.7,0.4-1c0.3-0.3,0.6-0.4,1-0.4s0.7,0.1,1,0.4   c0.3,0.3,0.4,0.6,0.4,1v4.8H18.3z"/></svg>
				</a>
			<?php endif; ?>

			<?php if ( $social_tiktok ) : ?>
				<a href="<?php echo $social_tiktok ?>" target="_blank" class="<?php echo $color ?> areoi-has-url areoi-has-url-small">
					<svg class="<?php echo $margin ?>" xmlns="http://www.w3.org/2000/svg" height="30px" viewBox="0 0 24 24" width="30px" fill="currentColor"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M20.5,3.5C20.2,3.2,19.7,3,19.2,3H4.8C4.3,3,3.8,3.2,3.5,3.5C3.1,3.9,3,4.3,3,4.8v14.5c0,0.5,0.2,0.9,0.5,1.2  C3.8,20.8,4.3,21,4.8,21h14.4c0.5,0,0.9-0.2,1.3-0.5c0.4-0.4,0.5-0.8,0.5-1.2V4.8C21,4.3,20.9,3.9,20.5,3.5z M16.9,10.8  c-0.9,0.1-1.7-0.2-2.6-0.8v3.4c0,4.3-4.7,5.6-6.6,2.5c-1.2-2-0.5-5.5,3.4-5.6v1.9c-0.3,0-0.6,0.1-0.9,0.2c-0.9,0.3-1.4,0.8-1.2,1.8  c0.3,1.9,3.7,2.4,3.4-1.2V6.3h1.9c0.2,1.6,1.1,2.6,2.7,2.7V10.8z"/></svg>
				</a>
			<?php endif; ?>

			<?php if ( $social_pinterest ) : ?>
				<a href="<?php echo $social_pinterest ?>" target="_blank" class="<?php echo $color ?> areoi-has-url areoi-has-url-small">
					<svg class="<?php echo $margin ?>" xmlns="http://www.w3.org/2000/svg" height="30px" viewBox="0 0 24 24" width="30px" fill="currentColor"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19.2,3c0.5,0,0.9,0.2,1.3,0.5C20.9,3.9,21,4.3,21,4.8v14.5c0,0.5-0.2,0.9-0.5,1.2c-0.4,0.4-0.8,0.5-1.3,0.5H4.8   c-0.5,0-0.9-0.2-1.3-0.5S3,19.7,3,19.2V4.8c0-0.5,0.2-0.9,0.5-1.2S4.3,3,4.8,3H19.2z M12.9,15.8c1.5,0,2.7-0.5,3.5-1.5   c0.8-1,1.2-2.1,1.2-3.5c0-1.5-0.6-2.7-1.7-3.8s-2.4-1.6-4-1.6S9.1,6,8,7.1s-1.7,2.3-1.7,3.8c0,1,0.3,1.8,0.8,2.7   C7.3,13.8,7.6,14,7.9,14c0.3,0,0.5-0.1,0.7-0.3c0.2-0.2,0.3-0.4,0.3-0.6c0-0.1-0.1-0.3-0.2-0.5c-0.3-0.6-0.5-1.2-0.5-1.8   c0-1,0.4-1.8,1.1-2.5s1.6-1,2.7-1s1.9,0.3,2.7,1c0.7,0.7,1.1,1.5,1.1,2.5c0,0.8-0.2,1.6-0.7,2.2c-0.5,0.6-1.2,0.9-2.2,0.9   c-0.3,0-0.6-0.1-0.8-0.4c-0.2-0.2-0.3-0.5-0.3-0.9c0-0.3,0.1-0.7,0.4-1.2c0.3-0.6,0.4-1.1,0.4-1.5c0-0.8-0.4-1.2-1.3-1.2   c-0.4,0-0.7,0.2-1,0.5c-0.3,0.3-0.4,0.8-0.4,1.5c0,0.2,0,0.5,0,0.7c0,0.2,0.1,0.4,0.1,0.5l0,0.1l-1.7,5l0,0.2v0.1   c0,0.3,0.1,0.5,0.3,0.7s0.4,0.3,0.7,0.3c0.4,0,0.7-0.2,0.8-0.5l0,0l0-0.2L11,15C11.5,15.5,12.1,15.8,12.9,15.8z"/></svg>
				</a>
			<?php endif; ?>

			<?php if ( $social_youtube ) : ?>
				<a href="<?php echo $social_youtube ?>" target="_blank" class="<?php echo $color ?> areoi-has-url areoi-has-url-small">
					<svg class="<?php echo $margin ?>" xmlns="http://www.w3.org/2000/svg" height="30px" viewBox="0 0 24 24" width="30px" fill="currentColor"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M20.5,3.5C20.2,3.2,19.7,3,19.2,3H4.8C4.3,3,3.8,3.2,3.5,3.5C3.1,3.9,3,4.3,3,4.8v14.5c0,0.5,0.2,0.9,0.5,1.2  C3.8,20.8,4.3,21,4.8,21h14.4c0.5,0,0.9-0.2,1.3-0.5c0.4-0.4,0.5-0.8,0.5-1.2V4.8C21,4.3,20.9,3.9,20.5,3.5z M15.8,12.4l-6.4,3.3  V8.2l7.3,3.8L15.8,12.4z"/></svg>
				</a>
			<?php endif; ?>
		</div>
	<?php endif;
}








function lightspeed_content_items()
{
	?>
	<?php if ( lightspeed_get_attribute( 'items', array() ) ) : ?>
		<div class="row row-cols-1 row-cols-sm-2">
			
			<?php foreach ( lightspeed_get_attribute( 'items', array() ) as $item_key => $item ) : ?>
				<div class="col">
					<?php lightspeed_item( $item ) ?>
				</div>
			<?php endforeach; ?>
			<?php if ( lightspeed_get_attribute( 'include_cta', false ) ) : ?>
				<div class="h1"></div>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<?php
}

function lightspeed_media_carousel( $medias, $type = 'square', $include_mask = true )
{
	?>
	<div id="<?php lightspeed_block_id() ?>-media-carousel" class="carousel slide" data-bs-ride="carousel">
		
		<?php if ( count( $medias ) > 1 ) : ?>
			<div class="carousel-indicators">
				<?php foreach ( $medias as $gallery_key => $media ) : ?>
					<button 
					type="button" 
					data-bs-target="#<?php lightspeed_block_id() ?>-media-carousel" 
					data-bs-slide-to="<?php echo $gallery_key ?>" 
					class="<?php echo $gallery_key == 0 ? 'active' : '' ?>" 
					aria-current="true" 
					aria-label="Slide <?php echo $gallery_key + 1 ?>"
					></button>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<div class="carousel-inner <?php echo $include_mask ? 'areoi-has-mask' : '' ?> rounded overflow-hidden">
			<?php foreach ( $medias as $gallery_key => $media ) : ?>
				<div class="carousel-item <?php echo $gallery_key == 0 ? 'active' : '' ?>">
					<div class="<?php lightspeed_media_col_class() ?>">
						<div class="areoi-media-col-content">
							<?php lightspeed_spacer( $type ) ?>
							<?php if ( $media['type'] == 'image' ) : ?>
								<img src="<?php echo $media['url'] ?>" class="d-block img-fluid" alt="<?php echo $media['alt'] ?>" width="<?php echo $media['width'] ?>" height="<?php echo $media['height'] ?>">
							<?php else : ?>
								<video src="<?php echo $media['url'] ?>" muted playsinline autoplay loop class="img-fluid"></video>
							<?php endif; ?>
						</div>
					</div>
					
				</div>
			<?php endforeach; ?>
		</div>

		<?php if ( count( $medias ) > 1 ) : ?>
			<button class="carousel-control-prev" type="button" data-bs-target="#<?php lightspeed_block_id() ?>-media-carousel" data-bs-slide="prev">
				<span class="carousel-control-prev-icon" aria-hidden="true"></span>
				<span class="visually-hidden">Previous</span>
			</button>
			<button class="carousel-control-next" type="button" data-bs-target="#<?php lightspeed_block_id() ?>-media-carousel" data-bs-slide="next">
				<span class="carousel-control-next-icon" aria-hidden="true"></span>
				<span class="visually-hidden">Next</span>
			</button>
		<?php endif; ?>
	</div>
	<?php
}

function lightspeed_accordion( $items )
{
	?>
	<div class="accordion" id="<?php lightspeed_block_id() ?>-accordion">
					
		<?php 
		foreach ( $items as $item_key => $item ) : 

			$heading_color = lightspeed_get_attribute( 'heading_color', lightspeed_get_default_color( 'text' ) );
			if ( !empty( $item['heading_color'] ) ) $heading_color = $item['heading_color'];

			$border_color = str_replace( 'text', 'border', lightspeed_get_attribute( 'heading_color', lightspeed_get_default_color( 'border' ) ) );
		?>

			<div class="accordion-item <?php echo $border_color ?>">
				<h3 class="accordion-header" id="<?php lightspeed_block_id() ?>-accordion-heading-<?php echo $item_key ?>">
					<button 
					class="accordion-button align-items-center h3 m-0 <?php echo $item_key > 0 ? 'collapsed' : '' ?> <?php echo $heading_color ?>" 
					type="button" 
					data-bs-toggle="collapse" 
					data-bs-target="#<?php lightspeed_block_id() ?>-accordion-heading-<?php echo $item_key ?>-collapse" 
					aria-expanded="true" 
					aria-controls="<?php lightspeed_block_id() ?>-accordion-heading-<?php echo $item_key ?>-collapse"
					>
						<?php echo $item['heading'] ?>

						<i class="bi bi-chevron-down"></i>
					</button>
				</h3>
				<div 
					id="<?php lightspeed_block_id() ?>-accordion-heading-<?php echo $item_key ?>-collapse" 
					class="accordion-collapse collapse <?php echo $item_key == 0 ? 'show' : '' ?>" 
					aria-labelledby="<?php lightspeed_block_id() ?>-accordion-heading-<?php echo $item_key ?>" 
					data-bs-parent="#<?php lightspeed_block_id() ?>-accordion"
				>
					<div class="accordion-body">
						
						<div class="row">
							<div class="col-12 <?php echo lightspeed_get_media( $item ) ? 'col-lg-6' : '' ?>">
								<div class="mb-3"><?php lightspeed_heading( 3, $item ) ?></div>

								<div class="mb-3"><?php lightspeed_introduction( $item ) ?></div>

								<?php lightspeed_cta( $item, true ) ?>
							</div>

							<?php if ( lightspeed_get_media( $item ) ) : ?>
								<div class="col-12 col-lg-6 <?php lightspeed_media_col_class() ?>">
									<div class="h1 d-lg-none"></div>
									<div class="areoi-media-col-content areoi-has-mask rounded overflow-hidden">
										<?php lightspeed_square_spacer() ?>
										<?php lightspeed_media( $item ) ?>
									</div>
								</div>
							<?php endif; ?>
						</div>	

					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
	<?php
}

function lightspeed_tabs( $items, $media = 'square', $type = 'basic' )
{
	$justify = $type == 'basic' ? 'nav-justified' : 'justify-content-center';
	$content_col = $type == 'basic' ? 'col-md-6' : 'col-md-6 col-lg-4 offset-lg-1';
	$media_col = $type == 'basic' ? 'col-md-6' : 'col-12 col-md-6 col-lg-5';

	$border_color = str_replace( 'text', 'border', lightspeed_get_attribute( 'heading_color', lightspeed_get_default_color( 'border' ) ) );
?>
	<ul class="nav nav-tabs <?php echo $justify ?> <?php echo $border_color ?>" id="<?php echo lightspeed_get_block_id() . '-tabs' ?>" role="tablist">
		<?php 
		foreach ( lightspeed_get_attribute( 'items', array() ) as $item_key => $item ) : 
			$heading_color = lightspeed_get_attribute( 'heading_color', lightspeed_get_default_color( 'text' ) );
			if ( !empty( $item['heading_color'] ) ) $heading_color = $item['heading_color'];
			
		?>
			<li 
			class="nav-item" 
			role="presentation"
			>
				<button 
				class="nav-link bg-transparent <?php echo $heading_color . ' ' . $border_color ?> <?php echo $item_key == 0 ? 'active' : '' ?>" 
				id="<?php echo lightspeed_get_block_id() . '-' . $item_key ?>-tab" 
				data-bs-toggle="tab" 
				data-bs-target="#<?php echo lightspeed_get_block_id() . '-' . $item_key ?>" 
				type="button" 
				role="tab" 
				aria-controls="<?php echo lightspeed_get_block_id() . '-' . $item_key ?>" 
				aria-selected="true"
				><?php echo $item['heading'] ?></button>
			</li>
		<?php endforeach; ?>
		
	</ul>

	<div class="tab-content">
		<div style="height: 50px;"></div>
		<?php foreach ( lightspeed_get_attribute( 'items', array() ) as $item_key => $item ) : ?>
			<div class="tab-pane <?php echo $item_key == 0 ? 'active' : '' ?>" id="<?php echo lightspeed_get_block_id() . '-' . $item_key ?>" role="tabpanel" aria-labelledby="<?php echo lightspeed_get_block_id() . '-' . $item_key ?>-tab">
				<div class="row align-items-center justify-content-around">
					<div class="col-12 <?php echo lightspeed_get_media( $item ) ? $content_col : '' ?>">
						
						<div class="mb-3"><?php lightspeed_heading( 3, $item ) ?></div>

						<div class="mb-3"><?php lightspeed_introduction( $item ) ?></div>

						<?php lightspeed_cta( $item, true ) ?>
					</div>

					<?php if ( lightspeed_get_media( $item ) ) : ?>
						<div class="<?php echo $media_col ?> <?php lightspeed_media_col_class() ?>">
							<div class="h1 d-lg-none"></div>
							<div class="areoi-media-col-content areoi-has-mask rounded overflow-hidden">
								<?php lightspeed_spacer( $media ) ?>
								<?php lightspeed_media( $item ) ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
<?php
}	

function lightspeed_tabs_vertical( $items )
{
?>
	<div class="row row-cols-1 <?php echo lightspeed_get_attribute( 'alignment', 'start' ) == 'end' ? 'justify-content-start' : 'justify-content-end' ?> align-items-center">
		<div class="col col-lg-4 <?php echo lightspeed_get_attribute( 'alignment', 'start' ) == 'end' ? 'order-lg-1 offset-lg-1' : '' ?>">
			<ul class="nav nav-pills flex-column border-0 nav-justified" id="<?php echo lightspeed_get_block_id() . '-tabs' ?>" role="tablist">
				<?php 
				foreach ( lightspeed_get_attribute( 'items', array() ) as $item_key => $item ) : 
					$heading_color = lightspeed_get_attribute( 'heading_color', lightspeed_get_default_color( 'text' ) );
					if ( !empty( $item['heading_color'] ) ) $heading_color = $item['heading_color'];
					$border_color = str_replace( 'text', 'border', lightspeed_get_attribute( 'heading_color', lightspeed_get_default_color( 'border' ) ) );
				?>
					<li 
					class="nav-item" 
					role="presentation"
					>
						<button 
						class="nav-link lead bg-transparent text-start ps-0 <?php echo $heading_color . ' ' . $border_color ?> <?php echo $item_key == 0 ? 'active' : '' ?>" 
						id="<?php echo lightspeed_get_block_id() . '-' . $item_key ?>-tab" 
						data-bs-toggle="tab" 
						data-bs-target="#<?php echo lightspeed_get_block_id() . '-' . $item_key ?>" 
						type="button" 
						role="tab" 
						aria-controls="<?php echo lightspeed_get_block_id() . '-' . $item_key ?>" 
						aria-selected="true"
						><?php echo $item['heading'] ?></button>
					</li>
				<?php endforeach; ?>
				
			</ul>
		</div>
		<div class="col col-lg-6">
			<div class="tab-content">
				<div class="h1 d-lg-none"></div>
				<?php foreach ( lightspeed_get_attribute( 'items', array() ) as $item_key => $item ) : ?>
					<div class="tab-pane <?php echo $item_key == 0 ? 'active' : '' ?>" id="<?php echo lightspeed_get_block_id() . '-' . $item_key ?>" role="tabpanel" aria-labelledby="<?php echo lightspeed_get_block_id() . '-' . $item_key ?>-tab">

						<?php if ( lightspeed_get_media( $item ) ) : ?>
							<div class="<?php lightspeed_media_col_class() ?> rounded overflow-hidden">
								<div class="areoi-media-col-content">
									<?php lightspeed_rectangle_spacer() ?>
									<?php lightspeed_media( $item ) ?>
								</div>
							</div>
							<div class="h1"></div>
						<?php endif; ?>

						<div class="mb-3"><?php lightspeed_heading( 3, $item ) ?></div>

						<div class="mb-3"><?php lightspeed_introduction( $item ) ?></div>

						<?php lightspeed_cta( $item, true ) ?>

					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
<?php
}	

function lightspeed_gallery_media( $media )
{
	?>
	<?php if ( $media['type'] == 'image' ) : ?>
		<img src="<?php echo $media['url'] ?>" class="d-block" alt="<?php echo $media['alt'] ?>" width="<?php echo $media['width'] ?>" height="<?php echo $media['height'] ?>">
	<?php else : ?>
		<video src="<?php echo $media['url'] ?>" muted playsinline autoplay loop></video>
	<?php endif; ?>
	<?php
}