<?php
/* Generate a Featured Image Widget
  @category  utility
  @package  featured-image-pro
  @author  nomadcoder
  @link http:://www.shooflysolutions.com
*/

$plugindir = plugin_dir_path( __FILE__ );
require_once $plugindir . '/featured-image-pro-exec.php';
require_once $plugindir . '/functions/proto-snap-category-walker.php';
require_once $plugindir . '/functions/proto-widget.php';
require_once $plugindir . '/functions/proto-global.php'; //Utilities
/*
* Initialize the widget1
*/
add_action( 'widgets_init', 'featured_image_pro_masonry_widget' );

if ( !function_exists('featured_image_pro_masonry_widget') ) {
    function featured_image_pro_masonry_widget() {
        register_widget( 'Featured_Image_Pro_Masonry_Widget' );
    }
}

/*
* Featured Image Pro Maosnry Widget Class
*/
if ( !class_exists( 'Featured_Image_Pro_Masonry_Widget' ) ) {
	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array   $args     Widget arguments.
	 * @param array   $instance Saved values from database.
	 */
	class Featured_Image_Pro_Masonry_Widget extends WP_Widget {
		/**
		 * __construct function.
		 * Register widget with WordPress.
		 *
		 * @access public
		 * @return void
		 */
		function __construct() {
			parent::__construct(
				'featured_image_pro_masonry_widget', // Base ID
				__( 'Featured Image Pro', 'featured-image-pro' ), // Name
				array(
					'description' => __( 'Featured Image Masonry', 'featured-image-pro' ),
				) // Args
			);
			add_action( 'customize_controls_enqueue_scripts', array( $this, 'admin_scripts_method' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts_method' ) );
		}
		function admin_scripts_method( $hook ) {
            $screen = get_current_screen();
            if (!in_array( $screen->base, array( 'customize', 'widgets' ) ) ) {
                return;
            }

            wp_enqueue_script( 'jquery-ui-accordion' );
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_script( 'wp-color-picker' );
            wp_enqueue_script( 'proto_widgets_js', plugins_url('assets/js/fipwidget.js', __FILE__), array('jquery', 'jquery-ui-accordion', 'customize-controls', 'wp-color-picker'), '2.4', TRUE );
            wp_enqueue_style( 'dashicons' );
            wp_enqueue_style( 'proto_snap_customizer_css', plugins_url('assets/css/customizer.css', __FILE__), array('dashicons'), '1.8' );

        }
		/**
		 * widget function.
		 * Front-end display of widget
		 *
		 * @access public
		 * @param array   $args
		 * @param array   $instance
		 * @return void
		 */
		public function widget( $args, $instance ) {
			echo $args['before_widget'];
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
			}
			$instance = $this->getAtts( $instance );
			foreach ( $instance as $key=>$option ) {
				if ( $option == 'on' ) $instance[$key] = true;
				if ( $option == 'off' ) $instance[$key] = false;
			}
			add_filter( 'proto_masonry_options', array( $this, 'widget_options' ), 10, 2 ); //parse out the attributes & options into separate arrays
			$widgetId = str_replace( 'featured_image_pro_masonry_', '', $args['widget_id'] );
			$sliderclass    = new featured_image_pro_post_masonry( $instance, $widgetId, true ); //grid initialization, create scripts etc.
			$ret = $sliderclass->featured_image_pro_get_full_content(true);
			remove_filter('proto_masonry_options', array($this, 'widget_options' ), 10);
			echo $ret;
			echo $args['after_widget'];
		}
		/**
		 * getAtts function.
		 * Widget Settings/Initialize Defaults
		 *
		 * @access private
		 * @param array   $instance
		 * @return void
		 */
		private function getAtts( $instance ) {
			if ( $instance == null )
				$instance = $this->getDefaults();
			$instance = $this->getPreset($instance);
			$instance['shortcode'] = $this->getShortcode( $instance );
			return $instance;
		}

		function getPreset($instance)
		{

			$preset = isset ($instance['preset']) ? $instance['preset'] : 0;
			if ($preset > 0 )
			{


				switch ( $preset )
				{
					case '1': //nice posts
						$instance['showexcerpts'] = 'on';
						$instance['excerptalign'] = 'left';
						$instance['shocaptions'] = 'on';
						$instance['captionhr'] = 'on';
						$instance['showcaptions'] = 'on';
						$instance['subcaption1'] = 'date';
						$instance['linksubcaptions'] = false;
						break;
				}
				$instance['preset'] = null;
				unset( $instance['preset'] );
			}
			return $instance;
		}
		function getDefaults() {
			return array(
				'preset' => null,
				'posts_per_page' => get_option( 'posts_per_page' ), //default to the wordpress settings,
				'post_type'=>'post',
				'item_bgcolor' => '',
				'item_color' => '',
				'link_color' => '',
				'link_hovercolor' => '',
				'fitwidth' => 'on',
				'showcaptions' => 'off',
				'captionalign' => 'center',
				'hovercaptions' => 'off',
				'subcaption1' => '',
				'subcaption2' => '',
				'subcaptionalign' => 'center',
				'captionheight' => '',
				'marginbottom' => '5px',
				'order' => 'Desc',
				'orderby' => '',
				'gutter' => '5',
				'border' => '0',
				'showexcerpts' => 'off',
				'excerpthr' => 'off',
				'excerptheight' => '',
				'excerptlength' => 0,
				'captionhr' => 'off',
				'excerptalign' => 'left',
				'imagesize' => 'thumbnail',
				'maxwidth' => '',
				'maxheight' => '',
				'columnwidth' => '', //new
				'gridwidth' =>'',               //Width of Grid
				'openwindow' => 'off',
				'itemwidth' => '',
			//	'has_thumbnails' => 'on',
				'catarray' => '',
				'imagewidth' => '',
				'imageheight' => '',
				'gridalign' => 'center',
				'animate' => 'off',
				'padimage' => 'on',
				'tooltip' => 'on',
				'linksubcaptions'=>'on',
				'boxshadow'=>'on',
				'excerpt_custom_link_text' => '',
				'excerpt_custom_link_type' => 'button',


			);
		}
		/**
		 * form function.
		 * Back-end widget form.
		 *
		 * @access public
		 * @param array   $instance
		 * @return void
		 */
		public function form( $instance ) {
			if ( isset( $instance[ 'title' ] ) ) {
				$title = $instance[ 'title' ];
			}
			else {
				$title = '';
			}
?>
    <p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>"></p><?php
			$atts = $this->getAtts( $instance );
			extract( $atts );
			$widget_options =  new proto_widget_01( $this, $atts );
			$h3_class = "ui-accordion-header ui-state-default
                                 ui-accordion-icons ui-accordion-header-active
                                 ui-corner-top";
            $uniqueid = uniqid();
			$uniqueclass = 'widget' . $uniqueid;
			$div_class = "panel-collapse collapse ui-accordion-content
                                  ui-helper-reset ui-widget-content ui-corner-bottom";
?>
    <div style="margin-top: 0px; height: 296px;overflow-x: hidden; overflow-y: auto;" class="proto_wrapper">
        <!-- Main -->
        <div class="proto-section-content proto-snap-widget-wrapper" data-proto-target=".<?php echo $uniqueclass?>">
            <!-- Grid Options -->
            <div class="panel-group <?php echo $uniqueclass?>"
                id="proto_accordion-<?php echo $this->number; ?>"
                data-widget-number="<?php echo $this->number; ?>"
                data-loaded="0" role="tablist"
                data-proto-shortcode-meta='<?php echo urlencode(json_encode( array(
                    'instance' => $instance,
                    'defaults' => $this->getDefaults()
                ) ) ); ?>'
                aria-multiselectable="true">
                <div class="panel panel-default">
                    <h3 class="<?php echo $h3_class; ?>">
                        <a class="collapsed" data-toggle="collapse" data-proto-target=".<?php echo $uniqueclass?>" href="#collapse0" aria-expanded="false" aria-controls="collapse0"><?php echo  __( 'General', 'featured-image-pro' ) ?></a>
                    </h3>
                    <div id="collapse0" class="<?php echo $div_class; ?>" role="tabpanel" aria-labelledby="heading0">
                        <div class="panel-body">
 <?php //$widget_options->proto_select( __( 'Presets', 'featured-image-pro' ), 'preset', array( '' => '--select--', '1' => 'Nice Posts with Excerpts' ), isset( $preset ) ? $preset : '' );?>
                            <?php echo $widget_options->proto_number( 'Maximum number of images', 'posts_per_page', $posts_per_page);?>
  					    <?php
						 	$post_types1 = array( 'post' => 'post', 'page' => 'page' );
							$post_type_list = get_post_types( array( 'public'=>TRUE,   '_builtin' => false ) , 'names' );
							$post_type_list = array_merge( $post_types1, $post_type_list );
	 					    $widget_options->proto_select( __( 'Post Type', 'featured-image-pro' ), 'post_type', $post_type_list , isset( $post_type ) ? $post_type : 'post' );?>
                        </div>
                    </div>
                    <h3 class="<?php echo $h3_class; ?>">
                        <a class="collapsed" data-toggle="collapse" data-proto-target=".<?php echo $uniqueclass?>" href="#collapse1" aria-expanded="false" aria-controls="collapse1"><?php echo  __( 'Grid', 'featured-image-pro' ) ?></a>
                    </h3>
                    <div id="collapse1" class="<?php echo $div_class; ?>" role="tabpanel" aria-labelledby="heading1">
                        <div class="panel-body">
                            <?php $widget_options->proto_checkbox( __( 'Fit Width to Content', 'featured-image-pro' ),
				'fitwidth',  isset( $fitwidth ) ? $fitwidth: 'off' ) ?>
							<?php $widget_options->proto_text( __( 'Grid Width', 'featured-image-pro' ), 'gridwidth',
				isset( $gridwidth ) ? $gridwidth : '', __( '(px % em etc) blank for default' ), 'featured-image-pro' );?>
				<?php $widget_options->proto_select( __( 'Grid Align', 'featured-image-pro' ), 'gridalign', array( 'left' => 'Left', 'center' => 'Center', 'right' => 'Right' ), isset( $gridalign ) ? $gridalign : 'center' );?>
                                    <?php $widget_options->proto_checkbox( __( 'Animate on Resize', 'featured-image-pro' ), 'animate', isset( $animate ) ? $animate : 'off' );?>
                        </div>
                    </div>
                    <h3 class="<?php echo $h3_class; ?>" class="<?php echo $h3_class; ?>">
                        <a class="collapsed" data-toggle="collapse" data-proto-target=".<?php echo $uniqueclass?>" href="#collapse5" aria-expanded="false" aria-controls="collapse5"><?php echo  __( 'Images', 'featured-image-pro' ) ?></a>
                    </h3>
                    <div id="collapse5" class="<?php echo $div_class; ?>" role="tabpanel" aria-labelledby="heading5">
                        <div class="panel-body">
                            <div>
                                <?php $widget_options->proto_checkbox( __( 'Pad Image', 'featured-image-pro' ), 'padimage', isset( $padimage ) ? $padimage: 'off' );?>
                                <?php $widget_options->proto_checkbox( __( 'Tooltip', 'featured-image-pro' ), 'tooltip', isset( $tooltip ) ? $tooltip: 'off' );?>
                                <?php $sizes = $this->get_sizes()?><?php $widget_options->proto_select( __( 'Image Size', 'featured-image-pro' ), 'imagesize', $sizes, isset( $imagesize ) ? $imagesize: 'thumbnail' );?>
                                <?php $widget_options->proto_text( __( 'Image Width', 'featured-image-pro' ), 'imagewidth',   isset( $imagewidth ) ? $imagewidth : '', __( 'px value, ie: 200, 200px, auto or blank for default', 'featured-image-pro' ) , 'featured-image-pro');?>
                                <?php $widget_options->proto_text( __( 'Image Height', 'featured-image-pro' ), 'imageheight',   isset( $imageheight ) ? $imageheight : '', __( 'px value, ie: 200, 200px, auto or blank for default', 'featured-image-pro' ) , 'featured-image-pro');?>
                                <?php $widget_options->proto_text( __( 'Max Width', 'featured-image-pro' ), 'maxwidth', isset( $maxwidth ) ? $maxwidth : '',__( 'px value, ie: 200, 200px, or blank for default', 'featured-image-pro' ) , 'featured-image-pro');?>
                                <?php $widget_options->proto_text( __( 'Max Height', 'featured-image-pro' ), 'maxheight', isset( $maxheight ) ? $maxheight : '', __( 'px value, ie: 200, 200px, or blank for default', 'featured-image-pro' ) , 'featured-image-pro');?>

                                                            </div>
                        </div>
                    </div>
                    <!-- End Grid Options -->
                    <!-- Grid Items Options -->
                    <h3 class="<?php echo $h3_class; ?>">
                        <a class="collapsed" data-toggle="collapse" data-proto-target=".<?php echo $uniqueclass?>" href="#collapse2" aria-expanded="false" aria-controls="collapse2"><?php echo  __( 'Grid Items', 'featured-image-pro' ) ?></a>
                    </h3>
                    <div id="collapse2" class="<?php echo $div_class; ?>" role="tabpanel" aria-labelledby="heading2">
                        <div class="panel-body">
                            <div>
                                <?php $widget_options->proto_number( __( 'Column Width', 'featured-image-pro' ),
				'columnwidth', isset( $columnwidth ) ? $columnwidth : '' );?>
				<?php $widget_options->proto_number( __( 'Gutter (Spacing between items)', 'featured-image-pro' ), 'gutter', isset( $gutter ) ? $gutter : '' );?>
				<?php $widget_options->proto_checkbox( __( 'Box shadow', 'featured-image-pro' ),
				'boxshadow', isset( $boxshadow ) ? $boxshadow: 'off' );?>
				<?php $widget_options->proto_number( __( 'Border Width', 'featured-image-pro' ),
				'border', isset( $border ) ? $border : '' );?>
<?php $widget_options->proto_text( __( 'Item Bottom Margin', 'featured-image-pro' ),
				'marginbottom', isset( $marginbottom ) && $marginbottom != '' ? $marginbottom : '',
				__( '(px % em etc) blank for default' ), 'featured-image-pro' );?>
				<?php $widget_options->proto_text( __( 'Item Width', 'featured-image-pro' ),
				'itemwidth',   isset( $itemwidth ) ? $itemwidth : '', __( 'px value, ie: 200, 200px, auto or blank for default',
					'featured-image-pro' ) );?>
				<?php $widget_options->proto_checkbox( __( 'Open link in new window', 'featured-image-pro' ),
				'openwindow', isset( $openwindow ) ? $openwindow: 'off' );?>
				<?php $widget_options->proto_text( __( 'Custom Link Text', 'featured-image-pro' ), 'excerpt_custom_link_text', isset( $excerpt_custom_link_text )  ? $excerpt_custom_link_text : '' );?>
                                  <?php $widget_options->proto_select( __( 'Custom Link Style', 'featured-image-pro' ), 'excerpt_custom_link_type', array( 'span' => 'span', 'div' => 'div', 'button' => 'button' ), isset( $excerpt_custom_link_type ) ? $excerpt_custom_link_type : 'button' );?>

                            </div>
                        </div>
                    </div>
                    <!-- End Grid Item Options -->
                    <!-- Caption Options -->
                    <h3 class="<?php echo $h3_class; ?>">
                        <a class="collapsed" data-toggle="collapse" data-proto-target=".<?php echo $uniqueclass; ?>" href="#collapse3" aria-expanded="false" aria-controls="collapse3"><?php echo  __( 'Captions', 'featured-image-pro' ) ?></a>
                    </h3>
                    <div id="collapse3" class="<?php echo $div_class; ?>" role="tabpanel" aria-labelledby="heading3">
                        <div class="panel-body">
                            <div>
                                <?php $widget_options->proto_checkbox( __( 'Show Captions', 'featured-image-pro' ), 'showcaptions', isset( $showcaptions ) ? $showcaptions : 'off' );?><?php $widget_options->proto_checkbox( __( 'Hover Captions', 'featured-image-pro' ), 'hovercaptions', isset( $hovercaptions ) ? $hovercaptions: 'off' );?>
 								<?php $widget_options->proto_select( __( 'Caption Text Align', 'featured-image-pro' ), 'captionalign', array( 'left' => 'Left', 'center' => 'Center', 'right' => 'Right' ), isset( $captionalign ) ? $captionalign : 'center' );?>

                                <?php $widget_options->proto_text( __( 'Fixed Caption Height', 'featured-image-pro' ), 'captionheight', isset( $captionheight ) ? $captionheight: 'off', __( 'px value, ie: 200, 200px,  blank for default' ), 'featured-image-pro' );?>
                            </div>
                         <?php $widget_options->proto_select( __( 'Sub Caption 1', 'featured-image-pro' ), 'subcaption1', array( '' => '--select--', 'author' => 'Author', 'date' => 'Date', 'comment_count' => 'Comment Count' ), isset( $subcaption1 ) ? $subcaption1 : '' );?>
					    <?php $widget_options->proto_select( __( 'Sub Caption 2', 'featured-image-pro' ), 'subcaption2', array( '' => '--select--', 'author' => 'Author', 'date' => 'Date', 'comment_count' => 'Comment Count' ), isset( $subcaption2 ) ? $subcaption2 : '' );?>
						<?php $widget_options->proto_select( __( 'Sub Caption Text Align', 'featured-image-pro' ), 'subcaptionalign', array( 'left' => 'Left', 'center' => 'Center', 'right' => 'Right' ), isset( $subcaptionalign ) ? $subcaptionalign : 'center' );?>
                        <?php $widget_options->proto_checkbox( __( 'Link Sub Captions', 'featured-image-pro' ), 'linksubcaptions', isset( $linksubcaptions ) ? $linksubcaptions : 'off' );?>
 <?php $widget_options->proto_checkbox( __( 'Horizontal Rule Under Captions', 'featured-image-pro' ), 'captionhr', isset( $captionhr ) ? $captionhr : '' );?>

                        </div>
                    </div>
                    <!-- End Caption Options -->
                    <!-- Color Options -->
                    <h3 class="<?php echo $h3_class; ?>">
                        <a class="collapsed" data-toggle="collapse" data-proto-target=".<?php echo $uniqueclass; ?>" href="#collapse3" aria-expanded="false" aria-controls="collapse3"><?php echo  __( 'Colors', 'featured-image-pro' ) ?></a>
                    </h3>
                    <div id="collapse3" class="<?php echo $div_class; ?>" role="tabpanel" aria-labelledby="heading3">
                        <div class="panel-body">
                            <div>
                                <?php $widget_options->proto_color( __( 'Grid Item Background Color', 'featured-image-pro' ), 'item_bgcolor', isset( $item_bgcolor ) ? $item_bgcolor: '' ) ;?>
                                <?php $widget_options->proto_color( __( 'Grid Item Text Color', 'featured-image-pro' ), 'item_color', isset( $item_color ) ? $item_color: '' ) ;?>
                                <?php $widget_options->proto_color( __( 'Grid Item Link Color', 'featured-image-pro' ), 'link_color', isset( $link_color ) ? $link_color: '' ) ;?>
                                <?php $widget_options->proto_color( __( 'Grid Item Link Hover Color', 'featured-image-pro' ), 'link_hovercolor', isset( $link_hovercolor ) ? $link_hovercolor: '' ) ;?>
                            </div>
                        </div>
                    </div>
                    <!-- End Color Options -->
                    <!-- Excerpt Options -->
                    <h3 class="<?php echo $h3_class; ?>">
                        <a class="collapsed" data-toggle="collapse" data-proto-target=".<?php echo $uniqueclass?>" href="#collapse4" aria-expanded="false" aria-controls="collapse4"><?php echo  __( 'Show Excerpts', 'featured-image-pro' ) ?></a>
                    </h3>
                    <div id="collapse4" class="<?php echo $div_class; ?>" role="tabpanel" aria-labelledby="heading4">
                        <div class="panel-body">
                            <div>
                                <?php $widget_options->proto_checkbox( __( 'Show Excerpts', 'featured-image-pro' ), 'showexcerpts', isset( $showexcerpts ) ? $showexcerpts : 'off' );?><?php $widget_options->proto_number( __( 'Excerpt Word Count (0 for default)', 'featured-image-pro' ), 'excerptlength', isset( $excerptlength ) ? $excerptlength : '' );?>
								<?php $widget_options->proto_select( __( 'Excerpt Text Align', 'featured-image-pro' ), 'excerptalign', array( 'left' => 'Left', 'center' => 'Center', 'right' => 'Right' ), isset( $excerptalign ) ? $excerptalign : 'left' );?>

                                <?php $widget_options->proto_checkbox( __( 'Horizontal Rule Under Excerpt', 'featured-image-pro' ), 'excerpthr', isset( $excerpthr ) ? $excerpthr : '' );?>
                                <?php $widget_options->proto_text( __( 'Fixed Excerpt Height', 'featured-image-pro' ), 'excerptheight', isset( $excerptheight )  ? $excerptheight : '', __( 'px value, ie: 200, 200px, blank for default' ), 'featured-image-pro' );?>
                            </div>
                        </div>
                    </div>
                    <!-- End Excerpt Options -->
                    <!-- Query Options -->
                    <h3 class="<?php echo $h3_class; ?>">
                        <a class="collapsed" data-toggle="collapse" data-proto-target=".<?php echo $uniqueclass?>" href="#collapse6" aria-expanded="false" aria-controls="collapse6"><?php echo __( 'Query', 'featured-image-pro' ) ?></a>
                    </h3>
                    <div id="collapse6" class="<?php echo $div_class; ?>" role="tabpanel" aria-labelledby="heading6">
                        <div class="panel-body">
                            <div>
                                <?php $widget_options->proto_select( __( 'Order By', 'featured-image-pro' ), 'orderby', array( 'rand' => 'Random', 'title' => 'Title',  '' => 'Date' ), isset( $orderby ) ? $orderby : '' );?>
                                <?php $widget_options->proto_select( __( 'Order', 'featured-image-pro' ), 'order', array( 'Asc' => 'Asc', 'Desc' => 'Desc' ), isset( $order ) ? $order: 'DESC' );?>
                            </div>
                        </div>
                    </div>
                    <!-- End Query Options -->
                    <!-- Category Options -->
                    <h3 class="<?php echo $h3_class; ?>">
                        <a class="collapsed" data-toggle="collapse" data-proto-target=".<?php echo $uniqueclass?>" href="#collapse7" aria-expanded="false" aria-controls="collapse7"><?php echo __('Categories', 'featured-image-pro')?></a>
                    </h3>
                    <div id="collapse7" class="<?php echo $div_class; ?>" role="tabpanel" aria-labelledby="heading5">
                        <div class="panel-body">
                            <div id="categorychecklistparent" class="categorychecklistparent" style="height:100px; overflow-x: hidden; overflow-y: auto;">
                                <?php
			$terms = get_categories(  array(
					'hide_empty' => false,
				) );
			foreach ( $terms as $term ) {
				$termid = $this->get_field_id( 'tax_term-catarray-' . $term->term_id );
				$termname = $this->get_field_name( 'catarray' ) . '['.$term->term_id.']';
				$checked = (  ! empty( $instance['catarray'][$term->term_id] )) ? 'checked="checked"' : '' ;
				echo "<div><input id='$termid' name='$termname' value='$term->term_id' type='checkbox' $checked><label for='$termid'>$term->name</label></div>";
			}
?>
                            </div> <!-- .categorychecklistparent -->
                        </div> <!-- .panel-body -->
                    </div> <!-- .panel-collapse -->
                    <!-- End Category Options -->
                    <!-- Shortcode Options -->
                    <h3 class="<?php echo $h3_class; ?>">
                        <a class="collapsed" data-toggle="collapse" data-proto-target=".<?php echo $uniqueclass?>" href="#collapse8" aria-expanded="false" aria-controls="collapse7"><?php echo __('View/Copy Shortcode', 'featured-image-pro')?></a>
                    </h3>
                    <div id="collapse8" class="<?php echo $div_class; ?>" role="tabpanel" aria-labelledby="heading8">
                        <textarea style='width:100%' name='<?php echo $this->get_field_name('shortcode')?>' id='<?php echo $this->get_field_id('shortcode') ?>' class='proto_shortcode' onClick='this.triger("select";'><?php echo $shortcode; ?></textarea>
                        <span><i>Click the text box, and press Ctrl+C (Cmd+C on Mac) to copy shortcode</i></span>
                        <hr>
                    </div>
                    <!-- End Shortcode Options -->
                </div> <!-- .panel -->
            </div>
        </div>
    </div>
<?php }
		/**
		 * get_sizes function.
		 *
		 * @access private
		 * @return list of sizes
		 */
		private function get_sizes() {
			$sizes = array();
			$isizes = get_intermediate_image_sizes();
			foreach ( $isizes as $isize ) {
				$key = strtolower( $isize );
				$sizes[$key] = $isize;
			}
			return $sizes;
		}
		/**
		 * update function.
		 * Sanitize widget form values as they are saved.
		 *
		 * @access public
		 * @param array   $new_instance
		 * @param array   $old_instance
		 * @return void
		 */
		public function update( $new_instance, $old_instance ) {
			$catarray =  ( isset( $new_instance['catarray'] ) ? array_map( 'absint', $new_instance['catarray'] ) : array( '0' ) );
			$new_instance['catarray'] = $catarray;
			$new_instance['shortcode'] = $this->getShortcode( $new_instance );
			return $new_instance;
		}
		private function getShortcode( $instance ) {
			$defaults = $this->getDefaults();
			$shortcode = '[featured_image_pro ';
			$instance = proto_functions::check_settings($instance);
			foreach ( $instance as $key=>$option ) {
				if ( $key != 'preset' )
				{
					if ( isset( $defaults['key'] ) )
						$defaultvalue = $defaults[$key];
					else
						$defaultvalue = null;
					if ( $option != $defaultvalue && $key != 'title'  && $key != 'shortcode' ) {
						if ( $key == 'catarray' ) {
							$option = implode( ',', $option );
							if ( $option != '0' && $option != '' )
							{
								$shortcode .= "cat='$option' ";
							}
						}
						elseif ( $option == 'on' ) {
							$shortcode .= "$key=true ";
						}
						elseif ( $option == 'off' ) {
							$shortcode .= "$key=false ";
						}

						elseif ( is_string( $option ) )
							$shortcode .= "$key='$option' ";
						elseif ( isset( $option ) )
							$shortcode .= "$key=$option ";
					}
				}
			}
			if ( !isset( $instance['fitwidth'] ) )
				$shortcode .= ' fitwidth=false ';
			if ( !isset( $instance['excerpthr'] ) )
				$shortcode .= ' excerpthr=false ';
			if ( !isset( $instance['captionhr'] ) )
				$shortcode .= ' captionhr=false ';
			if ( !isset( $instance['tooltip'] ) )
				$shortcode .= ' tooltip=false ';
			$shortcode .= ']';
			return $shortcode;
		}
		/**
		 * basic_options function.
		 * Get options array from widgets
		 *
		 * @access public
		 * @param array   $options
		 * @param array   $atts
		 * @return void
		 */
		function widget_options( $options, $atts ) {
			$atts = $this->getPreset($atts);
			$noptions = shortcode_atts( array(
					'fitwidth' => false,             //masonry settings fit width to content
					'columnwidth' => '',  //masonry setting column width
					'gutter' => 5, //masonry setting space between items
					'showcaptions'  => FALSE,       //display post title
					'captionalign' => 'center',
					'subcaptionalign' => 'center',
					'hovercaptions' => FALSE,       //hover title over image
					'captionheight' => '',          //fixed caption height
					'captionheight' => '',          //fixed caption height
					'subcaption1' => '',   //Author or date
					'subcaption2' => '',   //Author or date
					'item_bgcolor' => '',           //background color of the item
					'item_color' => '',             //text color of the item
					'link_color' => '',             //link color
					'link_hovercolor' => '',        //link hover color
					'excerptheight' => '',          //fixed excerpt height
					'marginbottom' => '5px',        //masonry item margin bottom
					'imagesize' => 'thumbnail',     //image size
					'border' => 0,                  //item border width (<= gutter)
					'showexcerpts' => FALSE,             //show excerpt
					'excerpthr' => FALSE,            //include horizontal line under excerpt
					'captionhr'=>FALSE,			//include horizontal line above excerpt
					'excerptlength' => null,        //override excerpt length
					'excerptalign' => 'left',
					'imagewidth' => '',             //Fixed Image width
					'imageheight' => '',            //Fixed Image height
					'itemwidth' => '',    //Grid Item Width
					'gridwidth' =>'100%',            //Width of Grid
					'openwindow' => FALSE,          //Set target to blank
					'gridalign'=>'center',          //align grid center, left, right
					'uniqueid' => '',               //Unique grid id
					'maxwidth' => '',               //maximum image width
					'maxheight' => '',              //maximim image height
					'prefix' => 'featured_image_pro', //Prefix
					'title' => '',     //Widget Title
					'animate' => FALSE,
					'animationduration' => '.7s',  //Transition Duration
					'resizeonload' => false,
					'layoutonresize' => 500,
					'padimage' => false,
					'tooltip' => false,
					'linksubcaptions'=>false,
					'boxshadow'=>false,
					'transitionduration'=>0,
					'excerpt_custom_link_text' => '',
					'excerpt_custom_link_type' => 'button',

				), $atts  );

			unset( $atts['preset'] );
			if ( isset( $atts['excerpt'] ) && !isset( $atts['showexcerpts'] ))
				$noptions['showexcerpts'] = $atts['excerpt'];
			unset ( $atts['excerpt'] );
			$options = array_merge( $options, $noptions );
			unset ( $atts['preset' ]);
			if ( proto_boolval( $options['hovercaptions'] ) )
				$options['showcaptions'] = true;

			return $options;
		}
	}

}