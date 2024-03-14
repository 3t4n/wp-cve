<?php

namespace Vimeotheque\Admin\Table;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Vimeotheque\Admin\Helper_Admin;
use Vimeotheque\Plugin;
use Vimeotheque\Helper;
use WP_Query;

/**
 * Load WP_List_Table class
 */
if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Class Video_List_Table
 *
 * Used for displaying the videos list in shortcode modal window
 * in post edit screen.
 *
 * @package Vimeotheque
 * @ignore
 */
class Video_List_Table extends \WP_List_Table{

	/**
	 * Store view post type
	 * @var string
	 */
	private $post_type;
	/**
	 * Store view category taxonomy
	 * @var string
	 */
	private $taxonomy;

	/**
	 * Class contructor
	 *
	 * @param array $args
	 * @return null
	 */
	function __construct( $args = [] ){
		parent::__construct( [
			'singular' => 'vimeo-video',
			'plural'   => 'vimeo-videos',
			'screen'   => isset( $args['screen'] ) ? $args['screen'] : null,
		] );

		$post_type = Plugin::instance()->get_registered_post_types()->get_post_type( Helper::get_var( 'view', 'GET' ) );
		if( !$post_type ){
			$this->post_type = Plugin::instance()->get_cpt()->get_post_type();
			$this->taxonomy = Plugin::instance()->get_cpt()->get_post_tax();
        }else{
		    $this->post_type = $post_type->get_post_type()->name;
		    $this->taxonomy = $post_type->get_taxonomy()->name;
        }
	}

	/**
	 * Default column
	 *
	 * @param array $item
	 * @param string $column
	 *
	 * @return mixed
	 */
	function column_default( $item, $column  ){
		return $item[ $column ];
	}

	/**
	 * Title
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	public function column_post_title( $item ){

	    $video = Helper::get_video_post( $item['ID'] );

		$label = sprintf( '<label for="cvm-video-%1$s" id="title%1$s" class="cvm_video_label">%2$s</label>', $item['ID'], $item['post_title'] );

		$settings = $video->get_embed_options();

		$form = '<div class="single-video-settings" id="single-video-settings-'.$item['ID'].'">';
		$form.= '<h4>'.$item['post_title'].' (' . $video->_duration . ')</h4>';
		$form.= '<label for="cvm_volume'.$item['ID'].'">'.__('Volume', 'codeflavors-vimeo-video-post-lite').'</label> <input size="3" type="text" name="volume['.$item['ID'].']" id="cvm_volume'.$item['ID'].'" value="'.$settings['volume'].'" /><br />';
		$form.= '<label for="cvm_width'.$item['ID'].'">'.__('Width', 'codeflavors-vimeo-video-post-lite').'</label> <input size="3" type="text" name="width['.$item['ID'].']" id="cvm_width'.$item['ID'].'" value="'.$settings['width'].'" /><br />';

		$aspect_select = Helper_Admin::aspect_ratio_select(
			[
				'name' 		=> 'aspect_ratio['.$item['ID'].']',
				'id' 		=> 'cvm_aspect_ratio'.$item['ID'],
				'selected' 	=> $settings['aspect_ratio']
			], false
		);
		$form.= '<label for="cvm_aspect_ratio'.$item['ID'].'">'.__('Aspect ratio', 'codeflavors-vimeo-video-post-lite').'</label> '.$aspect_select.'<br />';
		$form.= '<input type="checkbox" name="autoplay['.$item['ID'].']" id="cvm_autoplay'.$item['ID'].'" value="1"' . Helper_Admin::check( (bool)$settings['autoplay'], false ) . ' /> <label class="inline" for="cvm_autoplay' . $item['ID'] . '">' . __('Auto play', 'codeflavors-vimeo-video-post-lite') . '</label><br />';
		$form.= '<input type="checkbox" name="loop['.$item['ID'].']" id="cvm_loop'.$item['ID'].'" value="1"' . Helper_Admin::check( (bool)$settings['loop'], false ) . ' /> <label class="inline" for="cvm_loop' . $item['ID'] . '">' . __('Loop video', 'codeflavors-vimeo-video-post-lite') . '</label><br />';
		$form.= '<input type="button" id="shortcode'.$item['ID'].'" value="'.__('Insert shortcode', 'codeflavors-vimeo-video-post-lite').'" class="button cvm-insert-shortcode" />';
		$form.= '<input type="button" id="cancel'.$item['ID'].'" value="'.__('Cancel', 'codeflavors-vimeo-video-post-lite').'" class="button cvm-cancel-shortcode" />';
		$form.= '<div style="width:100%; display:block; clear:both"></div>';
		$form.= '</div>';

		// row actions
    	$actions = [
    		'shortcode' => sprintf(
    		    '%s%s',
			    sprintf(
				    '<a href="#" id="cvm-embed-%1$s" class="cvm-show-form">%2$s</a>',
				    $item['ID'],
				    __('Get video shortcode', 'codeflavors-vimeo-video-post-lite')
			    ),
			    $form
            )
	    ];

    	return sprintf('%s %s',
    		$label,
    		$this->row_actions( $actions )
    	);

	}

	/**
	 * Checkbox column
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_cb( $item ){
		return sprintf(
			'<input autocomplete="off" type="checkbox" name="%1$s" value="%2$s" id="%3$s" class="cvm-video-checkboxes" />',
			'cvm_video[]',
			$item['ID'],
			'cvm-video-'.$item['ID']
		);
	}

	/**
	 * Vimeo video ID column
	 *
	 * @param array $item
	 *
	 * @return
	 */
	function column_video_id( $item ){
	    $post = Helper::get_video_post( $item['ID'] );
		return $post->video_id;
	}

	/**
	 * Video duration column
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_duration( $item ){
		$post = Helper::get_video_post( $item['ID'] );
		return '<span id="duration'.$item['ID'].'">' . $post->_duration . '</span>';
	}

	/**
	 * Display video categories
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_category( $item ){

		$taxonomy = $this->_get_view_taxonomy();

		if ( $terms = get_the_terms( $item['ID'], $taxonomy ) ) {
			$out = [];
			foreach ( $terms as $t ) {
				$url = add_query_arg(
					[
						'view' 		=> Helper::get_var('view', 'GET') ? Helper::get_var('view', 'GET') : Plugin::instance()->get_cpt()->get_post_type(),
						'page' 		=> 'cvm_videos',
						'cat'		=> $t->term_id
					]
				, 'edit.php');

				$out[] = sprintf('<a href="%s">%s</a>', $url, $t->name);
			}
			return implode(', ', $out);
		}else {
			return '&#8212;';
		}
	}

	/**
	 * Date column
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_post_date( $item ){

		$output = sprintf(
		    '<abbr title="%s">%s</abbr><br />',
            $item['post_date'],
            mysql2date( 'Y/m/d', $item['post_date'] )
        );
		$output.= 'publish' == $item['post_status'] ? __('Published', 'codeflavors-vimeo-video-post-lite') : '';
		return $output;

	}

	/**
	 * (non-PHPdoc)
	 * @see WP_List_Table::extra_tablenav()
	 */
	function extra_tablenav($which){

		if( 'top' !== $which ){
			return ;
		}

		$selected = false;
		if( isset( $_GET['cat'] ) ){
			$selected = $_GET['cat'];
		}

		$args = [
			//'show_option_all' => __('Most recent videos', 'codeflavors-vimeo-video-post-lite'),
			'show_option_none' => __('Most recent videos', 'codeflavors-vimeo-video-post-lite'),
			'option_none_value' => 0,
			'show_count' 	=> 1,
			'taxonomy' 		=> $this->taxonomy,
			'name'			=> 'cat',
			'id'			=> 'cvm_video_categories',
			'selected'		=> $selected,
			'hide_empty'    => false,
			'hide_if_empty'	=> false,
			'echo'			=> false
		];
		$categories_select = wp_dropdown_categories($args);
		if( !$categories_select ){
			return;
		}

		$taxonomy = get_taxonomy($this->taxonomy);

		?>
		<label for="cvm_video_categories"><?php echo $taxonomy->labels->name;?> :</label>
		<?php echo $categories_select;?>
		<?php submit_button( __( 'Filter', 'codeflavors-vimeo-video-post-lite' ), 'button-secondary apply', 'filter_videos', false );?>
        <input type="button" name="add_category" id="cvm_add_category" class="button button-primary" value="<?php _e( 'Add category to shortcode', 'codeflavors-vimeo-video-post-lite' );?>" />
		<?php
	}

	/**
	 * (non-PHPdoc)
	 * @see WP_List_Table::get_views()
	 */
	function get_views(){
		$url = menu_page_url('cvm_videos', false).'&view=%s';
		$lt = '<a href="' . $url . '" title="%s" class="%s">%s</a>';

		$views = [];

        foreach( Plugin::instance()->get_registered_post_types()->get_post_types() as $_post_type ){
            $views[] = sprintf(
                $lt,
                $_post_type->get_post_type()->name,
                $_post_type->get_post_type()->label,
                ( $this->post_type == $_post_type->get_post_type()->name ? 'current' : '' ),
	            $_post_type->get_post_type()->label
            );
        }

		printf(
		        '<input type="hidden" name="cvm_post_type" id="cvm_post_type" value="%s" />',
			    $this->post_type
            );

    	return $views;
	}

	/**
	 * Returns the post type for the current view
	 *
	 * @return string
	 */
	private function _get_view_post_type(){
		$view = Helper::get_var('view', 'GET');
		return $view ? $view : Plugin::instance()->get_cpt()->get_post_type();
	}

	/**
	 * Returns the taxonomy for the current view
	 *
	 * @return string
	 */
	private function _get_view_taxonomy(){
		$post_type = $this->_get_view_post_type();
		if( 'post' == $post_type ){
			return 'category';
		}
		return Plugin::instance()->get_cpt()->get_post_tax();
	}

	/**
	 * (non-PHPdoc)
	 * @see WP_List_Table::get_columns()
	 */
	function get_columns(){
		$columns = [
			'cb'			=> '<input type="checkbox" class="cvm-video-list-select-all" />',
			'post_title'	=> __('Title', 'codeflavors-vimeo-video-post-lite'),
			'video_id'		=> __('Video ID', 'codeflavors-vimeo-video-post-lite'),
			'duration'		=> __('Duration', 'codeflavors-vimeo-video-post-lite'),
			'category'	=> __('Category', 'codeflavors-vimeo-video-post-lite'),
			'post_date' 	=> __('Date', 'codeflavors-vimeo-video-post-lite'),
		];
    	return $columns;
	}

	/**
     * (non-PHPdoc)
     * @see WP_List_Table::prepare_items()
     */
    function prepare_items() {

    	$columns = $this->get_columns();
        $hidden = [];
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = [ $columns, $hidden, $sortable ];

    	$per_page 		= 20;
    	$current_page 	= $this->get_pagenum();

    	$search_for = '';
    	if( isset($_REQUEST['s']) ){
    		$search_for = esc_attr( stripslashes( $_REQUEST['s'] ) );
    	}

    	$category = false;
    	if( isset( $_GET['cat'] ) && $_GET['cat'] ){
    		$category = $_GET['cat'];
    	}

        $args = [
			'post_type'			=> $this->post_type,
			'orderby' 			=> 'post_date',
		    'order' 			=> 'DESC',
	    	'posts_per_page'	=> $per_page,
	    	'offset'			=> ($current_page-1) * $per_page,
        	'post_status'		=> 'publish',
			's'					=> $search_for
        ];
        if( 'post' == $this->post_type ){
        	$args['meta_query'] = [
		        [
        		'key' => '__cvm_is_video',
        		'value' => true,
        		'compare' => '=='
		        ]
	        ];
        }

        if( $category ){
        	$args['tax_query'] = [
        		[
        			'taxonomy' => $this->taxonomy,
        			'field' => 'id',
        			'terms' => $category
		        ]
	        ];
        }

        // remove all filters added by third party plugins or themes
        remove_all_filters( 'pre_get_posts' );

        // run the query
		$query = new WP_Query( $args );

		$data = [];
        if( $query->posts ){
        	foreach($query->posts as $k => $item){
        		$data[$k] = (array)$item;
        	}
        }

        $total_items = $query->found_posts;
        $this->items = $data;

        $this->set_pagination_args( [
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil($total_items/$per_page)
        ] );
    }

}