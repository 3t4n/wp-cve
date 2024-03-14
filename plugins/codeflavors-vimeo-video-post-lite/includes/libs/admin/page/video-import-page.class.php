<?php

namespace Vimeotheque\Admin\Page;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Vimeotheque\Admin\Admin;
use Vimeotheque\Admin\Ajax_Actions;
use Vimeotheque\Admin\Posts_Import_Meta_Panels;
use Vimeotheque\Admin\Table\Video_Import_List_Table;
use Vimeotheque\Helper;
use WP_List_Table;

/**
 * Video import page
 * @author CodeFlavors
 * @ignore
 */
class Video_Import_Page extends Page_Abstract implements Page_Interface{
	
	/**
	 * Stores reference to WP  List Table object to display videos
	 * @var WP_List_Table
	 */
	private $table;
	
	/**
	 * Stores the view type
	 * @var string
	 */
	private $mode = 'grid';
	
	/**
	 * Store reference to Posts_Import_Meta_Panels object
	 * @var Posts_Import_Meta_Panels
	 */
	private $meta;
	/**
	 * Ajax Class reference
	 * @var Ajax_Actions
	 */
	private $ajax_obj;
	/**
	 * @var \Vimeotheque\Post\Post_Type
	 */
	private $cpt;

	/**
	 * Constructor, fires up the parent __construct() and sets up other variables
	 *
	 * @param Admin $admin
	 * @param $page_title
	 * @param $menu_title
	 * @param $slug
	 * @param $parent
	 * @param $capability
	 */
	public function __construct( Admin $admin, $page_title, $menu_title, $slug, $parent, $capability ){
		parent::__construct( $admin, $page_title, $menu_title, $slug, $parent, $capability );
		$this->meta = new Posts_Import_Meta_Panels( $admin->get_post_type() );
		$this->ajax_obj = $admin->get_ajax();
		$this->cpt = $admin->get_post_type();
	}

	/**
	 * (non-PHPdoc)
	 * @see Page_Interface::get_html()
	 */
	public function get_html(){
?>
	<div class="wrap"<?php if( 'grid' === $this->mode ):?> id="cvm-video-import-grid"<?php endif;?>>
		<h1><?php _e( 'Import videos', 'codeflavors-vimeo-video-post-lite' );?></h1>
		<?php if( 'grid' === $this->mode ):?>	
		<div class="error hide-if-js">
			<?php
                printf(
                    '<p>%s %s</p>',
                    __( 'The grid view for Vimeo videos import requires JavaScript.', 'codeflavors-vimeo-video-post-lite' ),
                    sprintf(
                        '<a href="%s">%s</a>.',
                        menu_page_url('cvm_import', false) . '&mode=list',
                        __( 'Switch to the list view', 'codeflavors-vimeo-video-post-lite' )
                    )
                )
			?>
		</div>
		<?php endif;?>	
		<?php 
		if( !$this->table && 'list' == $this->mode ){		
			$this->search_form( false );		
		}else{
			// add meta boxes
			$this->meta->add_metaboxes();

			/**
			 * Run action on meta boxes display.
             * @ignore
			 */
			do_action( 'vimeotheque\admin\import\add_metaboxes' );

			if( 'list' == $this->mode ){
				$this->table->prepare_items();
				$this->output_import_errors( $this->table->get_query_errors() );
			}	
		?>	
		<div id="poststuff">		
			<div class="wp-filter">
	        	<div class="cvm_search">	
	        	<?php $this->search_form( true ); ?>
	        	<?php if( 'grid' == $this->mode ):?>
	        		<div id="cvm-query-messages"></div>	
	        	<?php endif;?>
		        </div>	
	        </div>
		
			<div id="post-body" class="metabox-holder columns-2">
		        <div class="post-body" id="post-body">	        	 
		        	<?php 
		        		$attrs = [];
		        		if( 'list' == $this->mode ){
		        			$attrs[] = 'class="ajax-submit"';
		        		}else if( 'grid' == $this->mode ){
		        			$attrs[] = 'id="cvm-grid-submit-form"';
		        		}
		        	?>
		        	<form method="post" action="" <?php echo implode( ' ', $attrs );?>>	        	 
			        	<div id="post-body-content">					
                        <?php
                            // add nonce field
                            wp_nonce_field( $this->ajax_obj->get_nonce_action('list_view_import_videos') , $this->ajax_obj->get_nonce_name( 'list_view_import_videos' ) );
                            // adds action hidden input
                            $this->input( 'action', $this->ajax_obj->get_action( 'list_view_import_videos' ), 'hidden', 'cvm_ajax_action' );
                            // add source hidden input
                            $this->input( 'cvm_source', 'vimeo' );
                            // add feed type hidden input field
                            $this->list_input_field( 'cvm_feed_type'  );
                            // add query hidden input
                            $this->list_input_field( 'cvm_query' );
                            // add results query hidden field
                            $this->list_input_field( 'cvm_search_results' );

                            if( 'list' == $this->mode ){
                                $this->table->display();
                            }elseif( 'grid' == $this->mode ){
                        ?>
							<div class="container-fluid">
								<div class="row" id="cvm-grid-container">
									<!-- content loaded here with Backbone -->
								</div>
							</div>
                        <?php
                            }
                        ?>
						</div>
						
						<div id="postbox-container-1" class="postbox-container">
							<div id="side-sortables" class="meta-box-sortables">
					        	<?php do_meta_boxes( get_current_screen()->id, 'side', null );?>
					            <?php wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false );?>
								<?php wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );?>
				        	</div>
				        </div>				        			        
					</form>	
				</div>
			</div>	
		</div>	
	<?php 	
		}
	?>
	</div>		
	<?php 	
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Page_Interface::on_load()
	 */
	public function on_load(){
		
		$this->mode = get_user_option( 'cvm_bulk_import_mode', get_current_user_id() ) ?
            get_user_option( 'cvm_bulk_import_mode', get_current_user_id() ) :
            $this->mode;

		$modes = [ 'grid', 'list' ];
		
		if ( isset( $_GET['mode'] ) && in_array( $_GET['mode'], $modes ) ) {
			$this->mode = $_GET['mode'];
			update_user_option( get_current_user_id(), 'cvm_bulk_import_mode', $this->mode );
		}
		
		$this->video_import_assets();
		
		// showing the grid, enqueue the grid script and stop
		if( 'grid' === $this->mode ){
			$this->video_import_grid_assets();
			// insert the templates
			add_action( 'admin_footer', [ $this, 'print_grid_templates' ] );
			return;
		}

		// search videos result
		if( isset( $_GET['cvm_search_nonce'] ) ){
			if( check_admin_referer( 'cvm-video-import', 'cvm_search_nonce' ) ){
				$screen = get_current_screen();
				$this->table = new Video_Import_List_Table( [ 'screen' => $screen->id ] );
			}
		}
	}

	/**
	 * Outputs the search form.
	 *
	 * @param bool $compact - show a compacted form (true) or the full form (false)
	 */
	private function search_form( $compact = false ){
		include VIMEOTHEQUE_PATH . 'views/import_videos.php';
	}
	
	/**
	 * Enqueue scripts and styles needed on import page
	 */
	private function video_import_assets(){
		// video import form functionality
		wp_enqueue_script(
				'cvm-video-search-js',
			VIMEOTHEQUE_URL . 'assets/back-end/js/video-import.js',
				[ 'jquery' ],
				'1.0'
		);

		wp_localize_script('cvm-video-search-js', 'cvm_importMessages', [
			'loading' => __('Importing, please wait...', 'codeflavors-vimeo-video-post-lite'),
			'wait'	=> __("Not done yet, still importing. You'll have to wait a bit longer.", 'codeflavors-vimeo-video-post-lite')
		] );
	
		wp_enqueue_script('post');
	
		wp_enqueue_style(
			'cvm-video-search-css',
			VIMEOTHEQUE_URL . 'assets/back-end/css/video-import.css',
			[],
			'1.0'
		);

		/**
		 * Video import page load action used to enqueue assets.
         * @ignore
		 */
		do_action( 'vimeotheque\admin\video-import-assets' );
	}
	
	/**
	 * Enqueue all scripts and styles needed for bulk video import when in grid view
	 */
	private function video_import_grid_assets(){
	
		wp_enqueue_script(
				'cvm-video-import-grid',
			VIMEOTHEQUE_URL . 'assets/back-end/js/video-import-app.js',
				[ 'jquery', 'underscore', 'backbone' ]
		);
	
		$data = [
			'strings' => [
				'loading' 		=> __('Loading, please wait...', 'codeflavors-vimeo-video-post-lite'),
				'waiting'		=> __('Please use the form above to perform a search for Vimeo videos.', 'codeflavors-vimeo-video-post-lite'),
				'query_results' => __('Displaying %1$d videos out of %2$d videos.', 'codeflavors-vimeo-video-post-lite'),
				'no_results' 	=> __('Sorry, there are no videos matching your criteria.', 'codeflavors-vimeo-video-post-lite'),
				'finished'		=> __('All results were loaded.', 'codeflavors-vimeo-video-post-lite'),
				'load_more'		=> __('Load more', 'codeflavors-vimeo-video-post-lite'),
				'unknown_error'	=> __('Not imported due to unknown error.', 'codeflavors-vimeo-video-post-lite'),
				'info_search'	=> __('Ready to accept queries, please use the form to search for videos.', 'codeflavors-vimeo-video-post-lite')
			],
			'assets' => [
				'no_image' => VIMEOTHEQUE_URL . 'assets/back-end/images/no-image.jpg'
			]
		];
		// add the nonce
		$data[ $this->ajax_obj->get_nonce_name( 'save_video' ) ] = wp_create_nonce( $this->ajax_obj->get_nonce_action( 'save_video' ) );
		
	
		wp_localize_script( 'cvm-video-import-grid', 'CvmVideos', $data );
	
		wp_enqueue_style(
				'bootstrap-grid',
			VIMEOTHEQUE_URL . 'assets/back-end/css/vendor/bootstrap.css'
		);
	
		wp_enqueue_style(
				'cvm-grid',
			VIMEOTHEQUE_URL . 'assets/back-end/css/video-import-grid.css'
		);

		Helper::enqueue_player();
	}
	
	/**
	 * Outputs the underscore templates used in Backbone App
	 */
	public function print_grid_templates(){
		// Output the templates as strings because in some rare cases users might have PHP asp_tags directive on and code between <% %> could be interpreted as PHP code and generate errors since it isn't
		$out = "
<script type=\"text/template\" id=\"video-template\">
<div class=\"cvm-video status-<%= status %>\">
	<div class=\"cvm-thumbnail\">
		<img src=\"<%- thumbnails[2] ? thumbnails[2] : no_image %>\" alt=\"<%= _.escape('" . __( 'Video thumbnail image', 'codeflavors-vimeo-video-post-lite' ) ." ') %>\" title=\"<%= _.escape('" . __( 'Video thumbnail image', 'codeflavors-vimeo-video-post-lite' ) . "') %>\" />
		<span class=\"duration\"><%= _duration %></span>
		<% if( 'private' === privacy ){ %>
		<span class=\"private\" title=\"" . esc_attr( __( 'Due to Vimeo privacy settings this video might not be visible on your website.', 'codeflavors-vimeo-video-post-lite' ) ) . "\"></span>
		<% } %>
		<% if( 'private' === embed_privacy ) { %>
		<span class=\"private embed<%- 'private' !== privacy ? ' top' : '' %>\" title=\"" . esc_attr( __('Due to Vimeo privacy settings you might not be able to embed this video on your website.', 'codeflavors-vimeo-video-post-lite') ) ."\"></span>
		<% } %>
	</div>
	<div class=\"details\">
		<h4>
			<% if(type) { %>[<%= type%>] <% } %> <a href=\"<%= link %>\" title=\"<%= _.escape(title) %>\" target=\"_blank\"><%= _.escape( title ) %></a>
		</h4>
		<div class=\"meta\">
			<span class=\"publish-date\"><%= _published %></span>			
			<span class=\"views\"><%= stats.views %></span>
			<span class=\"likes\"><%= stats.likes %></span>
			<span class=\"comments\"><%= stats.comments %></span>
		</div>
		
		<div class=\"actions\">
			<% if( status === 'none' || status === 'queued' ){ %>
			<a href=\"#\" class=\"button import<%- status === 'queued' ? ' dequeue' : '' %>\">
				<%
					switch( status ){
						case 'queued':
							print( '" . __( 'Clear', 'codeflavors-vimeo-video-post-lite' ) . "' );
						break;
						case 'none':
							print( '" . __( 'Select', 'codeflavors-vimeo-video-post-lite' ) . "' );
						break;					
					}
				%>			
			</a>
			<% } %>

			<% if( status === 'done' ){ %>
				<a class=\"button edit\" target=\"_blank\" href=\"<%= edit_link %>\">" . __( 'Edit Post', 'codeflavors-vimeo-video-post-lite' ) . "</a>
				<a class=\"button view\" target=\"_blank\" href=\"<%= permalink %>\">" . __( 'View Post', 'codeflavors-vimeo-video-post-lite' ) . "</a>
				<span class=\"success\">" . __('Post created!', 'codeflavors-vimeo-video-post-lite') . "</span>
			<% } %>
			
			<% if( status === 'error' ){ %>
				<span class=\"error\"><%= error %></span>
			<% } %>
			
			<% if( status === 'queued' ){ %>
			<a href=\"#\" class=\"button single-import\">
				" . __( 'Import now!' ) . "
			</a>
			<% } %>
		</div>
	</div>
	<% if( status === 'saving' ){ %>
	<div class=\"loading\"></div>
	<% } %>
</div>
</script>";

		$out .= "
<script type=\"text/template\" id=\"embed-template\">
<div class=\"cvm-video-embed\" data-video_id=\"<%= video_id %>\"></div>
</script>";

		$out .= "		
<script type=\"text/template\" id=\"load-more-template\">
<a href=\"#\" id=\"cvm-load-more-videos\">
	<div class=\"thumbnail <%- css %>\"><span></span></div>
	<h2>
		<%= message %>
	</h2>
</a>
</script>";
		
		$out .= "
<script type=\"text/template\" id=\"filter-view\">
<ul class=\"subsubsub<%- hide ? ' hide-if-js' : '' %>\">
	<li class=\"all\">
		<a href=\"#\" class=\"<%- 'all' === current ? 'current' : '' %>\" id=\"filter-show-all\">" . __('All videos', 'codeflavors-vimeo-video-post-lite') ." <span class=\"count\">(<%= items %>)</span></a> |
	</li>
	<li class=\"publish\">
		<a href=\"#\" class=\"<%- 'queued' === current ? 'current' : '' %>\" id=\"filter-show-importing\">" . __('To import', 'codeflavors-vimeo-video-post-lite') . " <span class=\"count\">(<%= queued %>)</span></a> | 
	</li>
	<li class=\"imported\">
		<a href=\"#\" class=\"<%- 'imported' === current ? 'current' : '' %>\" id=\"filter-show-imported\">" . __('Imported', 'codeflavors-vimeo-video-post-lite') . " <span class=\"count\">(<%= imported %>)</span></a>
	</li>
</ul>
</script>";
		
		$out .= "
<script type=\"text/template\" id=\"no-results-view\">
<% if( 'queued' === screen ){ %>
<p>
	<strong>" . __( 'No videos queued for importing!', 'codeflavors-vimeo-video-post-lite' ) . "</strong>
	" . __( 'Please select some videos first and import them by clicking the "Import videos" button on the sidebar.', 'codeflavors-vimeo-video-post-lite' ) . "
</p>
<% }else if( 'imported' === screen ){ %>
<p>
	<strong>" . __( "You haven't imported any videos yet!", 'codeflavors-vimeo-video-post-lite' ) . "</strong>
	" . __( 'All successfully imported videos will be available here.', 'codeflavors-vimeo-video-post-lite' ) . "
</p>
<% } %>
</script>";
		
		echo $out;
	}

	/**
     * Displays an input field
     *
	 * @param $name
	 * @param $value
	 * @param string $type
	 * @param string $class
	 * @param string $id
	 * @param bool $echo
	 *
	 * @return string
	 */
	private function input( $name, $value, $type = 'hidden', $class = '', $id = '', $echo = true ){
	    $output = sprintf('<input type="%s" value="%s" name="%s" %s %s />',
            esc_attr( $type ),
		    sanitize_text_field( $value ),
            esc_attr( $name ),
            ( !empty( $class ) ? 'class="' . esc_attr( $class ) . '"' : '' ),
		    ( !empty( $id ) ? 'id="' . esc_attr( $id ) . '"' : '' )
        );

	    if( $echo ){
	        echo $output;
        }
        return $output;
    }

	/**
     * Displays fields based on whether list view is on
     *
	 * @param $name
	 * @param $value
	 * @param string $type
	 * @param bool $echo
	 *
	 * @return string
	 */
    private function list_input_field( $name, $type = 'hidden', $echo = true ){
	    if( 'list' === $this->mode && isset( $_GET[ $name ] ) ){
	        return $this->input( $name, $_GET[ $name ], $type, '', '', $echo );
        }
    }

	/**
	 * @param \WP_Error $error
	 * @param bool $echo
	 * @param string $before
	 * @param string $after
	 *
	 * @return string|void
	 */
    private function output_import_errors( $error, $echo = true, $before = '<div class="error"><p>', $after = '</p></div>' ){
	    if( !is_wp_error( $error ) ){
		    return;
	    }

	    // wp error message
	    $code = 'cvm_wp_error';
	    $message = $error->get_error_message( $code );
	    if( $message ){
		    $output = __('WordPress encountered and error while trying to query Vimeo:', 'codeflavors-vimeo-video-post-lite'). '<br />' . '<strong>'.$message.'</strong></p>';
		    if( $echo ){
			    echo $before.$output.$after;
		    }
		    return $before.$output.$after;
	    }

	    // vimeo api errors
	    $code = 'cvm_vimeo_query_error';
	    $message 	= $error->get_error_message( $code );
	    $data		= $error->get_error_data( $code );

	    $output = '<strong>'.$message.'</strong></p>';
	    $output.= sprintf( __('Vimeo error code: %s (<em>%s</em>) - <strong>%s</strong>', 'codeflavors-vimeo-video-post-lite'), $data['code'], $data['msg'], $data['expl'] );

	    if( 401 == $data['code'] ){
		    $url = menu_page_url('cvm_settings', false).'#vimeo_consumer_key';
		    $link = sprintf('<a href="%s">%s</a>', $url, __('Settings page', 'codeflavors-vimeo-video-post-lite'));
		    $output.= '<br /><br />' . sprintf(__('Please visit %s and enter your consumer and secret keys.', 'codeflavors-vimeo-video-post-lite'), $link);
	    }

	    if( $echo ){
		    echo $before.$output.$after;
	    }

	    return $before.$output.$after;
    }

}