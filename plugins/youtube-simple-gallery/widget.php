<?php // Creating the widget 
class ysg_widget extends WP_Widget {

function __construct() {
parent::__construct(
// Base ID of your widget
'ysg_widget', 

// Widget name will appear in UI
__('YouTube Simple Gallery Widget', 'youtube-simple-gallery'), 

// Widget description
array( 'description' => __('Um simples Widget para gerar a sua galeria de v&iacute;deos','youtube-simple-gallery' ), ) 
);
}

// Creating widget front-end
// This is where the action happens
public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );
$perpage = apply_filters( 'widget_perpage', $instance['perpage'] );
$select = apply_filters( 'widget_select', $instance['select'] );
$sluglink = apply_filters( 'widget_sluglink', $instance['sluglink'] );
// before and after widget arguments are defined by themes
echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];

/*Start - Loop Videos*/
if($select != 'all'){
	query_posts( array(
		'post_type' => 'youtube-gallery',
		'posts_per_page' => $perpage,
		'tax_query' => array(
            array(
                'taxonomy' => 'youtube-videos',
                'field' => 'slug',
                'terms' => $select
            )
        ),
		'order' => 'DESC',
		'orderby' => 'date'
		) 
	); 

}else{
    query_posts('post_type=youtube-gallery&posts_per_page='.$perpage.'&order=DESC&orderby=date');
};

global $ysg_options; $ysg_settings = get_option( 'ysg_options', $ysg_options );
	
	add_thickbox();

	$size_thumb_s_w = $ysg_settings['ysg_thumb_s_wight'];
 	$size_thumb_s_h = $ysg_settings['ysg_thumb_s_height'];

if (have_posts()) : 
	echo '<ul class="ul-Widget-YoutubeGallery">';
	while ( have_posts() ) : the_post();

	// Values YSG
	$desc_value = get_post_meta( get_the_ID(), 'valor_desc', true );
	$idvideo = get_post_meta( get_the_ID(), 'valor_url', true );
	
	$quality_video = get_post_meta( get_the_ID(), 'custom_element_grid_quality_meta_box', true );
	if ($quality_video == null){
		$quality_video = 'default';
	}else{
		$quality_video = $quality_video;
	}
	
	$similar_video = get_post_meta( get_the_ID(), 'radio_similiar', true );
	if ($similar_video == null){
		$similar_video = '1';
	}else{
		$similar_video = $similar_video;
	}

	$controles_video = get_post_meta( get_the_ID(), 'radio_controles', true );
	if ($controles_video == null){
		$controles_video = '1';
	}else{
		$controles_video = $controles_video;
	}

	$title_video = get_post_meta( get_the_ID(), 'radio_title', true );
	if ($title_video == null){
		$title_video = '1';
	}else{
		$title_video = $title_video;
	}

	$embed_code = ysg_youtubeEmbedFromUrl($idvideo);
	$size_thumb_w = $ysg_settings['ysg_thumb_wight'];
	$size_thumb_h = $ysg_settings['ysg_thumb_height']; 

	?>
	
	<li class="li-Widget-YoutubeGallery">
		<h3 class="title-Widget-YoutubeGallery"><?php the_title();?></h3>
		<div id="video-widget-id-<?php the_ID(); ?>" style="display:none;">
			<iframe width="<?php echo $ysg_settings['ysg_size_wight']; ?>" height="<?php echo $ysg_settings['ysg_size_height']; ?>" src="http://www.youtube.com/embed/<?php echo $embed_code;?>?rel=<?php echo $similar_video;?>&amp;vq=<?php echo $quality_video;?>&amp;controls=<?php echo $controles_video;?>&amp;showinfo=<?php echo $title_video;?>&amp;autoplay=<?php echo $ysg_settings['ysg_autoplay']; ?>" frameborder="0" allowfullscreen></iframe>
		</div>
		<a href="#TB_inline?width=<?php echo $ysg_settings['ysg_size_wight'] + '15'; ?>&height=<?php echo $ysg_settings['ysg_size_height'] + '20'; ?>&inlineId=video-widget-id-<?php the_ID(); ?>" title="<?php the_title();?>" class="thickbox">
			<?php if ( has_post_thumbnail()) { the_post_thumbnail('chr-thumb-youtube', array('class' => 'img-YoutubeGallery chr-size-s-thumb')); }else{ echo '<img src="http://img.youtube.com/vi/'.$embed_code.'/mqdefault.jpg" class="img-YoutubeGallery chr-size-s-thumb" alt="'.get_the_title().'" title="'.get_the_title().'" />'; } ?>
		</a>
	</li>
<?php endwhile;
echo '</ul>
<style>
.chr-size-s-thumb{
	width:'.$size_thumb_s_w.'px!important;
	height:'.$size_thumb_s_h.'px!important; 
}
</style><a href="'.get_bloginfo('url').'/'.$sluglink.'/" title="'.__('Veja Mais','youtube-simple-gallery' ).'" class="btn-YSG-Widget">'.__('Veja Mais','youtube-simple-gallery' ).'</a>
';
else:
echo '<h6>'.__( 'N&atilde;o existe nenhum v&iacute;deo cadastrado...', 'youtube-simple-gallery' ).'<h6>';
endif; 
wp_reset_query();
/*End - Loop Videos*/
echo $args['after_widget'];
}
		
// Widget Backend 
public function form( $instance ) {
	if ( isset( $instance[ 'title' ] ) ) {
		$title = $instance[ 'title' ];
		$perpage = $instance[ 'perpage' ];
		$select = esc_attr($instance['select']);
		$sluglink = $instance[ 'sluglink' ];
	}
	else {
		$title = __( 'Listagem dos V&iacute;deos', 'youtube-simple-gallery' );
		$perpage = '3';
		$select = __('all', 'youtube-simple-gallery' );
		$sluglink =  __('galeria-de-video','youtube-simple-gallery' );
	}
	// Widget admin form
	?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo __('T&iacute;tulo: ','youtube-simple-gallery' );?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'perpage' ); ?>"><?php echo __('Quantidade de V&iacute;deos:','youtube-simple-gallery' );?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'perpage' ); ?>" name="<?php echo $this->get_field_name( 'perpage' ); ?>" type="text" value="<?php echo esc_attr( $perpage ); ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('select'); ?>"><?php echo __('Listagem de V&iacute;deos por categoria:','youtube-simple-gallery' );?></label>
		<?php $taxonomyName = "youtube-videos"; $parent_terms = get_terms($taxonomyName, array('orderby' => 'slug', 'hide_empty' => false)); ?>
		<select name="<?php echo $this->get_field_name('select'); ?>" id="<?php echo $this->get_field_id('select'); ?>" style="width: 100%; display: inline-block;">
			<option value="all" selected><?php echo __('Todos','youtube-simple-gallery' );?></option>
        <?php
		foreach ($parent_terms as $pterm) {
			echo '<option value="' . $pterm->slug . '" id="' . $pterm->slug . '"', $select == $pterm->slug ? ' selected="selected"' : '', '>', $pterm->name, '</option>';
		}
		?>
		</select>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'sluglink' ); ?>"><?php echo __('Insir&aacute; o SLUG da p&aacute;gina <strong>Galeria de V&iacute;deo</strong>','youtube-simple-gallery' );?>:</label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'sluglink' ); ?>" name="<?php echo $this->get_field_name( 'sluglink' ); ?>" type="text" value="<?php echo esc_attr( $sluglink ); ?>" />
	</p>
	<?php 
}
	
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title']    = ( ! empty( $new_instance['title'] ) )    ? strip_tags( $new_instance['title'] ) : '';
$instance['perpage']  = ( ! empty( $new_instance['perpage'] ) )  ? strip_tags( $new_instance['perpage'] ) : '';
$instance['select']   = strip_tags($new_instance['select']);
$instance['sluglink'] = ( ! empty( $new_instance['sluglink'] ) ) ? strip_tags( $new_instance['sluglink'] ) : '';
return $instance;
}
} // Class wpb_widget ends here

// Register and load the widget
function wpb_load_widget() {
	register_widget( 'ysg_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );