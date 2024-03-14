<?php 
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */


if ( ! defined( 'WPINC' ) )  die;
if ( ! defined( 'ABSPATH' ) ){ exit;  }

class Gallery_Images_Ape_Widget extends WP_Widget {

  function __construct(){
    parent::__construct(
      'Gallery_Images_Ape_Widget', 
      __( 'Gallery Ape' , 'gallery-images-ape' ),
      array( 'description' => __( "Add Gallery Ape to the frontend", 'gallery-images-ape' ), ) 
    );
  }

  public function widget( $args, $instance ) {
    $title = apply_filters( 'widget_title', $instance['title'] );
    $galleryId = $instance['galleryId'];

    echo $args['before_widget'];

    if( !empty( $title ) ) echo $args['before_title'] . $title . $args['after_title'];

    if( empty( $galleryId ) ){ 
    	echo __('Ape Gallery:: Select gallery in widget settings', 'gallery-images-ape');
    	return ;
    }
    
    if( function_exists( 'wpape_premium_widget' ) ){
    	wpape_premium_widget($galleryId);
    } else {
    	echo do_shortcode('[ape-gallery id="'.$galleryId.'"]');
    }
   
    echo $args['after_widget'];
  }


  public function form( $instance ) {
    if ( isset( $instance[ 'title' ] ) ) {
      $title = $instance[ 'title' ];
    } else {
      $title = __( 'Ape Widget Title', 'gallery-images-ape' );
    }
    
    if ( isset( $instance[ 'galleryId' ] ) ) {
        $galleryId = (int) $instance[ 'galleryId' ];
    }
    else {
        $galleryId = 0;
    }
    $args = array(
      'child_of'     => 0,
      'sort_order'   => 'ASC',
      'sort_column'  => 'post_title',
      'hierarchical' => 1,
      'selected'     => $galleryId,
      'name'         => $this->get_field_name( 'galleryId' ),
      'id'           => $this->get_field_id( 'galleryId' ),
      'echo'    => 1,
      'post_type' => WPAPE_GALLERY_POST
    );
    ?>

	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">
	 		<?php _e( 'Title', 'gallery-images-ape' ); ?>:
		</label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'galleryId' ); ?>"><?php _e( 'Gallery Ape', 'gallery-images-ape'); ?>:</label> 
		<?php wp_dropdown_pages( $args ); ?>
	</p>
	<p><?php _e( 'First setup some gallery in ','gallery-images-ape');?> 
		<a href="edit.php?post_type=<?php echo WPAPE_GALLERY_POST; ?>">
			<?php 
			_e( 'Gallery Ape','gallery-images-ape').' '.__( 'manager','gallery-images-ape');
			?>
		</a>
	</p>
	<script type="text/javascript">
		var elem = document.getElementById("<?php echo $this->get_field_id( 'galleryId' ); ?>");
		elem.classList.add('widefat');
		//jQuery(function(){ jQuery('#<?php echo $this->get_field_id( 'galleryId' ); ?>').addClass('widefat'); });
	</script>
    <?php 
  }

  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
    $instance['galleryId'] = ( ! empty( $new_instance['galleryId'] ) ) ? (int) $new_instance['galleryId'] : 0;
    return $instance;
  }
}

function wpApeGalleryLoadWidget() {  register_widget( 'Gallery_Images_Ape_Widget' ); }
add_action( 'widgets_init', 'wpApeGalleryLoadWidget' );