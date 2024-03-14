<?php
/**
 * Media hooks
 *
 * @package AdminTweaks
 */

namespace ADTW;

class HooksMedia {
	/**
	 * Check options and dispatch hooks
	 * 
	 * @param  array $options
	 * @return void
	 */
	public function __construct() 
    {
        # BIG IMAGE THRESHOLD
		if( ADTW()->getop('media_big_image_size') && !ADTW()->getop('media_big_image_disable') ) {
            add_filter( 'big_image_size_threshold', function(){
                return ADTW()->getop('media_big_image_size');
            });
        }
		if( ADTW()->getop('media_big_image_disable') ) {
            add_filter( 'big_image_size_threshold', '__return_false' );
        }

		$current_display = get_user_option( 'media_library_mode', get_current_user_id() );
		$no_grid = ( 'list' == $current_display );
        
		# BIGGER THUMBS
		if( ADTW()->getop('media_image_bigger_thumbs') && $no_grid ) {
            add_action( 
                'admin_head-upload.php', 
                [$this, 'biggerThumbs'] 
            );
        }

        # CAMERA EXIF
		if( ADTW()->getop('media_camera_exif') ) {
			add_filter( 
                'manage_upload_columns', 
                [$this, 'camera_info_column'] 
			);
			add_action( 
                'manage_media_custom_column', 
                [$this, 'camera_info_display'], 
                10, 2 
			);
			add_action( 
                'admin_head-upload.php', 
                [$this, 'camera_info_css']
			);
			add_post_type_support( 'attachment', 'custom-fields' );
		}
        
		# SANITIZE FILENAME
		if( ADTW()->getop('media_sanitize_filename') )
			add_filter(
                'sanitize_file_name', 
                [$this, 'sanitize_filename'], 
                10
			);
  
		# ALLOW SVG
		if( ADTW()->getop('media_allow_svg') ) {
			add_filter(
                'upload_mimes', 
                [$this, 'allow_svg'],
                999998
			);
        } else {/*
			add_filter(
                'upload_mimes', 
                [$this, 'disallow_svg'],
                999999
			);
        */}

		# CUSTOM SIZES IN INSERT MEDIA
		if( ADTW()->getop('media_include_extras_sizes') )
			add_filter(
                'image_size_names_choose', 
                [$this, 'include_extras_sizes']
			);

	}


	/**
	 * Manipulates thumbnails attributes and properties in wp-admin/upload.php
	 */
	public function biggerThumbs() {	
		?>
		<script type="text/javascript">
			jQuery(document).ready( function($) {
				$('.wp-list-table img').each(function(){
					$(this)
						.attr('width','100').css('max-width','100%')
						.attr('height','100').css('max-height','100%');
				});
				$('.media-icon').css('width', '100px');
			});     
		</script>
		<?php
	}


	/**
	 * Clean up uploaded file names
	 * 
     * Sanitization test done with the filename:
     * ÄäÆæÀàÁáÂâÃãÅåªₐāĆćÇçÐđÈèÉéÊêËëₑƒğĞÌìÍíÎîÏïīıÑñⁿÒòÓóÔôÕõØøₒÖöŒœßŠšşŞ™ÙùÚúÛûÜüÝýÿŽž¢€‰№$℃°C℉°F⁰¹²³⁴⁵⁶⁷⁸⁹₀₁₂₃₄₅₆₇₈₉±×₊₌⁼⁻₋–—‑․‥…‧.png
	 * @author toscho
	 * @url    https://github.com/toscho/Germanix-WordPress-Plugin
	 */
	public function sanitize_filename( $filename ) {

		$filename	 = html_entity_decode( $filename, ENT_QUOTES, 'utf-8' );
		$filename	 = $this->translit( $filename );
		$filename	 = $this->lower_ascii( $filename );
		$filename	 = $this->remove_doubles( $filename );
		return $filename;
	}


	/**
	 * Add custom sizes to Insert Media selector
	 * 
	 * @author http://kucrut.org/insert-image-with-custom-size-into-post/
	 * 
	 */
	public function include_extras_sizes( $sizes ) {
		global $_wp_additional_image_sizes;
		if( empty( $_wp_additional_image_sizes ) )
			return $sizes;
		foreach( $_wp_additional_image_sizes as $id => $data )
		{
			if( !isset( $sizes[$id] ) )
				$sizes[$id] = ucfirst( str_replace( '-', ' ', $id ) );
		}
		
		return $sizes;
	}
	
	public function manipulate_metadata( $metadata, $id ) {
		if ( !wp_attachment_is( 'image',$id ) )
			return $metadata;
		
		foreach( $metadata['image_meta'] as $meta => $value ) {
			if( !empty( $value ) ) {
				if( 'created_timestamp' == $meta )
					$value = gmdate( 'Y-m-d H:i:s', $value );
				update_post_meta( $id, "photo_$meta", $value );
			}
		}
		return $metadata;
	}

	public function camera_info_column( $columns ) {
		$columns['cam_info'] = 'Metadata';
		return $columns;
	}

	public function camera_info_css() {
        echo '<style>th#cam_info{width:12%}</style>';
    }

	public function camera_info_display( $column_name, $post_id ) {
		if( 'cam_info' != $column_name )
			return;
		
		$meta = get_post_meta( $post_id, '_wp_attachment_metadata', true );
		$return = array();
		
		if( wp_attachment_is( 'image', $post_id ) ) {
			$default_exif = array( 'title', 'camera', 'aperture', 'focal_length', 'iso', 'shutter_speed', 'caption', 'credit', 'copyright', 'created_timestamp' );
			$exif = $meta['image_meta'];
			foreach( $default_exif as $v ) {
				if( !empty($exif[$v] ) ){
					$title = ( $v == 'created_timestamp' ) ? 'Created' : ucwords( str_replace('_', ' ', $v) );
					$value = ( $v == 'created_timestamp' ) ? date('Y-m-d', $exif[$v] ) : $exif[$v];
					$return[] = '<small>' . $title . ':</small> <b>' . $value . '</b>';
				}
			}
			echo implode( '<br />', $return );
		}
		if( wp_attachment_is( 'audio', $post_id ) ) {
			$default_id3 = array( 'title', 'artist', 'album', 'year', 'genre', 'filesize', 'length_formatted' );
			foreach( $default_id3 as $v ) {
				if( !empty($meta[$v] ) ){
					$title = ( $v == 'length_formatted' ) ? 'Length' : ucwords($v);
					$value = ( $v == 'filesize' ) ? ADTW()->format_size( $meta[$v] ) : $meta[$v];
					$return[] = '<small>' . $title . ':</small> <b>' . $value . '</b>';
				}
			}
			echo implode( '<br />', $return );
		}
		if( wp_attachment_is( 'video', $post_id ) ) {
			$default_v = array( 'width', 'height', 'filesize', 'length_formatted' );
			foreach( $default_v as $v ) {
				if( !empty($meta[$v] ) ){
					$title = ( $v == 'length_formatted' ) ? 'Length' : ucwords($v);
					$value = ( $v == 'filesize' ) ? ADTW()->format_size( $meta[$v] ) : $meta[$v];
					$return[] = '<small>' . $title . ':</small> <b>' . $value . '</b>';
				}
			}
			echo implode( '<br />', $return );
		}
	}
	
    /**
     * Add SVG to the allowed filetypes
     *
     * @param array $mimes
     * @return array
     */
    public function allow_svg( $mimes ) {
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
    }

    /**
     * I'm baffled that disabling the filter doesn't 
     * stop the filetype from being allowed to upload,
     * I had to do this to fix it...
     *
     * @param array $mimes
     * @return array
     */
    public function disallow_svg( $mimes ) {
        unset( $mimes['svg'] );
        return $mimes;
    }

    /**
	 * Converts uppercase characters to lowercase and removes the rest.
	 * https://github.com/toscho/Germanix-WordPress-Plugin
	 *
	 * @uses   apply_filters( 'germanix_lower_ascii_regex' )
	 * @param  string $str Input string
	 * @return string
	 */
	private function lower_ascii( $str ) {
		$str	 = strtolower( $str );
		$regex	 = array(
			'pattern'		 => '~([^a-z\d_.-])~'
			, 'replacement'	 => ''
		);
		// Leave underscores, otherwise the taxonomy tag cloud in the
		// backend won’t work anymore.
		return preg_replace( $regex['pattern'], $regex['replacement'], $str );
	}


	/**
	 * Reduces repeated meta characters (-=+.) to one.
	 * https://github.com/toscho/Germanix-WordPress-Plugin
	 *
	 * @uses   apply_filters( 'germanix_remove_doubles_regex' )
	 * @param  string $str Input string
	 * @return string
	 */
	private function remove_doubles( $str ) {
		$regex = apply_filters(
				'germanix_remove_doubles_regex',
				array(
					'pattern'		 => '~([=+.-])\\1+~',
					'replacement'	 => "\\1"
				)
		);
		return preg_replace( $regex['pattern'], $regex['replacement'], $str );
	}

    
	/**
	 * Replaces non ASCII chars.
	 * https://github.com/toscho/Germanix-WordPress-Plugin
	 *
	 * wp-includes/formatting.php#L531 is unfortunately completely inappropriate.
	 * Modified version of Heiko Rabe’s code.
	 *
	 * @author Heiko Rabe http://code-styling.de
	 * @link   http://www.code-styling.de/?p=574
	 * @param  string $str
	 * @return string
	 */
	private function translit( $str ) {
		$utf8 = array(
			'Ä'	 => 'Ae'
			, 'ä'	 => 'ae'
			, 'Æ'	 => 'Ae'
			, 'æ'	 => 'ae'
			, 'À'	 => 'A'
			, 'à'	 => 'a'
			, 'Á'	 => 'A'
			, 'á'	 => 'a'
			, 'Â'	 => 'A'
			, 'â'	 => 'a'
			, 'Ã'	 => 'A'
			, 'ã'	 => 'a'
			, 'Å'	 => 'A'
			, 'å'	 => 'a'
			, 'ª'	 => 'a'
			, 'ₐ'	 => 'a'
			, 'ā'	 => 'a'
			, 'Ć'	 => 'C'
			, 'ć'	 => 'c'
			, 'Ç'	 => 'C'
			, 'ç'	 => 'c'
			, 'Ð'	 => 'D'
			, 'đ'	 => 'd'
			, 'È'	 => 'E'
			, 'è'	 => 'e'
			, 'É'	 => 'E'
			, 'é'	 => 'e'
			, 'Ê'	 => 'E'
			, 'ê'	 => 'e'
			, 'Ë'	 => 'E'
			, 'ë'	 => 'e'
			, 'ₑ'	 => 'e'
			, 'ƒ'	 => 'f'
			, 'ğ'	 => 'g'
			, 'Ğ'	 => 'G'
			, 'Ì'	 => 'I'
			, 'ì'	 => 'i'
			, 'Í'	 => 'I'
			, 'í'	 => 'i'
			, 'Î'	 => 'I'
			, 'î'	 => 'i'
			, 'Ï'	 => 'Ii'
			, 'ï'	 => 'ii'
			, 'ī'	 => 'i'
			, 'ı'	 => 'i'
			, 'I'	 => 'I' // turkish, correct?
			, 'Ñ'	 => 'N'
			, 'ñ'	 => 'n'
			, 'ⁿ'	 => 'n'
			, 'Ò'	 => 'O'
			, 'ò'	 => 'o'
			, 'Ó'	 => 'O'
			, 'ó'	 => 'o'
			, 'Ô'	 => 'O'
			, 'ô'	 => 'o'
			, 'Õ'	 => 'O'
			, 'õ'	 => 'o'
			, 'Ø'	 => 'O'
			, 'ø'	 => 'o'
			, 'ₒ'	 => 'o'
			, 'Ö'	 => 'Oe'
			, 'ö'	 => 'oe'
			, 'Œ'	 => 'Oe'
			, 'œ'	 => 'oe'
			, 'ß'	 => 'ss'
			, 'Š'	 => 'S'
			, 'š'	 => 's'
			, 'ş'	 => 's'
			, 'Ş'	 => 'S'
			, '™'	 => 'TM'
			, 'Ù'	 => 'U'
			, 'ù'	 => 'u'
			, 'Ú'	 => 'U'
			, 'ú'	 => 'u'
			, 'Û'	 => 'U'
			, 'û'	 => 'u'
			, 'Ü'	 => 'Ue'
			, 'ü'	 => 'ue'
			, 'Ý'	 => 'Y'
			, 'ý'	 => 'y'
			, 'ÿ'	 => 'y'
			, 'Ž'	 => 'Z'
			, 'ž'	 => 'z'
			// misc
			, '¢'	 => 'Cent'
			, '€'	 => 'Euro'
			, '‰'	 => 'promille'
			, '№'	 => 'Nr'
			, '$'	 => 'Dollar'
			, '℃'	 => 'Grad Celsius'
			, '°C' => 'Grad Celsius'
			, '℉'	 => 'Grad Fahrenheit'
			, '°F' => 'Grad Fahrenheit'
			// Superscripts
			, '⁰'	 => '0'
			, '¹'	 => '1'
			, '²'	 => '2'
			, '³'	 => '3'
			, '⁴'	 => '4'
			, '⁵'	 => '5'
			, '⁶'	 => '6'
			, '⁷'	 => '7'
			, '⁸'	 => '8'
			, '⁹'	 => '9'
			// Subscripts
			, '₀'	 => '0'
			, '₁'	 => '1'
			, '₂'	 => '2'
			, '₃'	 => '3'
			, '₄'	 => '4'
			, '₅'	 => '5'
			, '₆'	 => '6'
			, '₇'	 => '7'
			, '₈'	 => '8'
			, '₉'	 => '9'
			// Operators, punctuation
			, '±'	 => 'plusminus'
			, '×'	 => 'x'
			, '₊'	 => 'plus'
			, '₌'	 => '='
			, '⁼'	 => '='
			, '⁻'	 => '-'	// sup minus
			, '₋'	 => '-'	// sub minus
			, '–'	 => '-'	// ndash
			, '—'	 => '-'	// mdash
			, '‑'	 => '-'	// non breaking hyphen
			, '․'	 => '.'	// one dot leader
			, '‥'	 => '..'  // two dot leader
			, '…'	 => '...'  // ellipsis
			, '‧'	 => '.'	// hyphenation point
			, ' '	 => '-'   // nobreak space
			, ' '	 => '-'   // normal space
		);

		$str = strtr( $str, $utf8 );
		return trim( $str, '-' );
	}


}