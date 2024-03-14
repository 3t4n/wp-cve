<?php
/**
 * Media Columns hooks
 *
 * @package AdminTweaks
 */

namespace ADTW;

class HooksMediaColumns {
	/**
	 * Check options and dispatch hooks
	 * 
	 * @return void
	 */
	public function __construct() {
		// COLUMN ID
		if( ADTW()->getop('media_image_id_column_enable') ) {
			add_filter(
                'manage_upload_columns', 
                [$this, 'idColumnDefine']
			);
			add_action(
                'manage_media_custom_column', 
                [$this, 'idColumnDisplay'], 
                10, 2
			);
            add_action(
                'admin_head-upload.php', 
                [$this, 'idColumnCSS']
			);
		}

		// COLUMN IMAGE SIZE
		if( ADTW()->getop('media_image_size_column_enable') ) {
			add_filter(
                'manage_upload_columns', 
                [$this, 'sizeColumnDefine']
			);
			add_action(
                'manage_media_custom_column', 
                [$this, 'sizeColumnDisplay'], 
                10, 2
			);
			add_action( 
                'admin_head-upload.php', 
                [$this, 'sizeColumnCSS']
			);
		}

		// COLUMN LIST OF THUMBNAILS
		if( ADTW()->getop('media_image_thubms_list_column_enable') ) {
			add_filter(
                'manage_upload_columns', 
                [$this, 'all_thumbs_column_define']
			);
			add_action(
                'manage_media_custom_column', 
                [$this, 'all_thumbs_column_display'], 
                10, 2
			);
			add_action( 
                'admin_head-upload.php', 
                [$this, 'all_thumbs_column_css']
			);
		}
	}

	/**
	 * Add ID colum to wp-admin/upload.php
	 * 
	 * @param type $cols
	 * @return type
	 */
	public function idColumnDefine( $cols ) {
		$in = ['id' => 'ID'];
		$cols = ADTW()->array_push_after( $cols, $in, 0 );
		return $cols;
	}

	/**
	 * Display ID column in wp-admin/upload.php
	 * 
	 * @param type $col_name
	 * @param type $post_id
	 */
	public function idColumnDisplay( $col_name, $post_id ) {
		if( $col_name == 'id' )
			echo $post_id;
	}

	/**
	 * Add size column to wp-admin/upload.php
	 * 
	 * @param array $columns
	 * @return type
	 */
	public function sizeColumnDefine( $columns ) {
		$columns['dimensions'] = esc_html__( 'Dimensions', 'mtt' );
		return $columns;
	}

    public function sizeColumnCSS() {
        echo '<style>th#dimensions{width:8%}</style>';
    }

	/**
	 * Display size column in wp-admin/upload.php
	 * 
	 * @param type $column_name
	 * @param type $post_id
	 * @return type
	 */
	public function sizeColumnDisplay( $column_name, $post_id ) {
		if( 'dimensions' != $column_name || !wp_attachment_is_image( $post_id ) )
			return;
		
		list($url, $width, $height) = wp_get_attachment_image_src( $post_id, 'full' );
		
		echo "{$width}<span style=\"color:#aaa\"> &times; </span>{$height}";
	}

	/**
	 * Print custom columns CSS
	 * 
	 */
	public function idColumnCSS() {
        echo '<style>th#id{width:5%}</style>';
	}

	/**
	 * Add all thumbs column to wp-admin/upload.php
	 * 
	 * @param array $columns
	 * @return string
	 */
	public function all_thumbs_column_define( $columns ) {
		$columns['all_thumbs'] = 'All Thumbs';
		return $columns;
	}

    public function all_thumbs_column_css() {
        echo '<style>th#all_thumbs{width:15%}</style>';
    }


	/**
	 * Display all thumbs column in wp-admin/upload.php
     * SVG files are ignored
	 * 
	 * @param type $column_name
	 * @param type $post_id
	 * @return type
	 */
	public function all_thumbs_column_display( $column_name, $post_id ) {
		if( 'all_thumbs' != $column_name 
            || !wp_attachment_is_image( $post_id ) ) return;

		$full_size = wp_get_attachment_image_src( $post_id, 'full' );

        $is_svg = ADTW()->endswith($full_size[0],'.svg');
        if ($is_svg) return;

        echo '<div style="clear:both">FULL SIZE : ' . $full_size[1] . ' x ' . $full_size[2] . '</div>';

		$size_names = get_intermediate_image_sizes();

		foreach( $size_names as $name ) {
            $the_list = wp_get_attachment_image_src( $post_id, $name );
            
			if( $the_list[3] )
				echo '<div style="clear:both"><a href="' . $the_list[0] . '" target="_blank">' . $name . '</a> : ' . $the_list[1] . ' x ' . $the_list[2] . '</div>';
		}
	}
}