<?php

namespace Vimeotheque\Admin\Table;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Vimeotheque\Video_Import;

/**
 * Class Video_Import_List_Table
 * @package Vimeotheque\Admin
 * @ignore
 */
class Video_Import_List_Table extends \WP_List_Table{
	
	// holds errors returned by query to Vimeo
	private $query_errors;
	
	function __construct( $args = [] ){
		// override parent's modes
		/*
		$this->modes = array(
			'list' => __( 'List View', 'codeflavors-vimeo-video-post-lite' ),
			'grid' => __( 'Grid View', 'codeflavors-vimeo-video-post-lite' )
		);
		*/
		parent::__construct( [
			'singular' => 'vimeo-video',
			'plural'   => 'vimeo-videos',
			'screen'   => isset( $args['screen'] ) ? $args['screen'] : null,
		] );
	}
	
	/**
	 * Default column
	 * @param array $item
	 * @param string $column
	 */
	function column_default( $item, $column ){
		if( array_key_exists($column, $item) ){
			return $item[ $column ];
		}else{
			return sprintf(
				'<span style="color:red">%s</span>',
				sprintf(
					__('Column %s was not found.', 'codeflavors-vimeo-video-post-lite'),
					sprintf(
						'<em>%s</em>',
						$column
					)
				)
			);
		}
	}
	
	/**
	 * Checkbox column
	 * @param array $item
	 */
	function column_cb( $item ){

		$output = sprintf( '<input type="checkbox" name="cvm_import[]" value="%1$s" id="cvm_video_%1$s" />', $item['video_id'] );
		return $output;
		
	}
	
	/**
	 * Title column
	 * @param array $item
	 */
	function column_title( $item ){	
		
		$before = 'private' == $item['privacy'] ? '<span class="dashicons dashicons-hidden" style="color:#CCC;"></span> ' : '';
		
		$title = $item['title'];
		if( isset( $item['type'] ) && $item['type'] ){
			$title = ' <strong>[' . ucfirst( $item['type'] ) . ']</strong> ' . $title;
		}
		
		$label = sprintf( '<label for="cvm_video_%1$s" class="cvm_video_label">%2$s</label>', $item['video_id'], $before . $title );
		
		// row actions
    	$actions = [
    		'view' 		=> sprintf( '<a href="https://vimeo.com/%1$s" target="_cvm_vimeo_open">%2$s</a>', $item['video_id'], __('View on Vimeo', 'codeflavors-vimeo-video-post-lite') ),
	    ];
    	
    	return sprintf('%1$s %2$s',
    		$label,
    		$this->row_actions( $actions )
    	);		
	}
	
	
	
	/**
	 * Column for video duration
	 * @param array $item
	 */
	function column_duration( $item ){		
		return \Vimeotheque\Helper::human_time( $item['duration'] );
	}
	
	/**
	 * Rating column
	 * @param array $item 
	 */
	function column_likes( $item ){

		if( 0 == $item['stats']['likes'] ){
			return '-';
		}
		
		return sprintf( __('%d likes', 'codeflavors-vimeo-video-post-lite'), $item['stats']['likes'] );
	}
	
	/**
	 * Views column
	 * @param array $item
	 */
	function column_views( $item ){
		if( 0 == $item['stats']['views'] ){
			return '-';
		}		
		return number_format( $item['stats']['views'], 0, '.', ',');		
	}
	
	/**
	 * Date when the video was published
	 * @param array $item
	 */
	function column_published( $item ){
		if( !$item['published'] ){
			return __('Unknown', 'codeflavors-vimeo-video-post-lite');
		}
		
		
		$time = strtotime( $item['published'] );
		return date('M dS, Y @ H:i:s', $time);
	}
		
	/**
     * (non-PHPdoc)
     * @see WP_List_Table::get_bulk_actions()
     */
    function get_bulk_actions() {    	
    	$actions = [
    		/*'import' => __('Import', 'codeflavors-vimeo-video-post-lite')*/
	    ];
    	
    	//global $mode;
    	//$this->view_switcher( $mode );
    	
    	return $actions;
    }
	
	/**
     * Returns the columns of the table as specified
     */
    function get_columns(){
        
		$columns = [
			'cb'		=> '<input type="checkbox" />',
			'title'		=> __('Title', 'codeflavors-vimeo-video-post-lite'),
			'video_id'	=> __('Video ID', 'codeflavors-vimeo-video-post-lite'),
			'uploader'	=> __('Uploader', 'codeflavors-vimeo-video-post-lite'),
			'duration'	=> __('Duration', 'codeflavors-vimeo-video-post-lite'),
			'likes'		=> __('Likes', 'codeflavors-vimeo-video-post-lite'),
			'views'		=> __('Views', 'codeflavors-vimeo-video-post-lite'),
			'published' => __('Published', 'codeflavors-vimeo-video-post-lite'),
		];
    	return $columns;
    }
    
    function extra_tablenav( $which ){    	
    	return;
    }
    
    /**
     * (non-PHPdoc)
     * @see WP_List_Table::prepare_items()
     */    
    function prepare_items() {
        $per_page 	 = 20;
		$current_page = $this->get_pagenum();

		$resource = isset($_GET['cvm_feed']) ? $_GET['cvm_feed'] : false;
		$resource_id = isset($_GET['cvm_query']) ? $_GET['cvm_query'] : false;
		$user = isset($_GET['cvm_album_user']) ? $_GET['cvm_album_user'] : false;

		$args = [
			'order' => isset($_GET['cvm_order']) ? $_GET['cvm_order'] : false, // @todo implement new ordering
    		'results' => $per_page,
			'page' => $current_page,
			'query' => isset( $_GET['cvm_search_results'] ) ? $_GET['cvm_search_results'] : false
		];
		
		$import = new Video_Import(
			$resource,
			$resource_id,
			$user,
			$args
		);
		$videos = $import->get_feed();
        
		$this->query_errors = $import->get_errors();
		
		$total_items = $import->get_total_items();
		
    	$this->items 	= $videos;
        $this->set_pagination_args( [
            'total_items' => $total_items,                  
            'per_page'    => $per_page,                     
            'total_pages' => ceil( $total_items / $per_page )
        ] );
    }   
    
    /**
     * Returns any errors issued by the importer
     */
    public function get_query_errors(){
    	return $this->query_errors;	
    }    
}