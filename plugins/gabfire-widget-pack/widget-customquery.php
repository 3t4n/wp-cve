<?php
if ( !defined('ABSPATH')) exit;

class gab_custom_query extends WP_Widget {
 
    function __construct() {
        $widget_ops = array( 'classname' => 'gab_custom_query', 'description' => 'Query custom entries' );
        $control_ops = array( 'width' => 520, 'height' => 350, 'id_base' => 'gab_custom_query' );
        parent::__construct( 'gab_custom_query', 'Gabfire: Custom Content Query', $widget_ops, $control_ops);
    }
 
    public function widget($args, $instance) {      
        extract( $args );
        $title    = $instance['title'];
        $swap = $instance['swap'] ? '1' : '0';
		$video = $instance['video'] ? '1' : '0';
        $c_image = $instance['c_image'];
		$c_link = $instance['c_link'];
        $postnr    = $instance['postnr'];
		$postids    = $instance['postids'];
        $cat_or_postpage    = $instance['cat_or_postpage'];
		$post_order    = $instance['post_order'];
        $d_thumb    = $instance['d_thumb'] ? '1' : '0';
        $postmeta    = $instance['postmeta'] ? '1' : '0';
        $media_w    = $instance['media_w'];
        $media_h    = $instance['media_h'];
        $excerpt_l    = $instance['excerpt_l'];
        $thumbalign    = $instance['thumbalign'];
		$postcls    = $instance['postcls'];
		$titlecls    = $instance['titlecls'];
        
        echo $before_widget;
		
            if ( $title ) {
				echo $before_title;
				if ( $c_link ) { echo '<a href="' . $c_link . '">'; }
					echo $title;
				if ( $c_link ) { echo '</a>'; }
				echo $after_title;
            }
			
            if ( $c_image ) { 
				if ( $c_link ) { echo '<a href="' . $c_link . '">'; }
					echo '<img style="display:block;margin:0 0 7px;line-height:0" src="'.$c_image.'" alt="" />'; 
				if ( $c_link ) { echo '</a>'; }
			}
            $count = 0;
			global $do_not_duplicate, $post, $page;
            
			if ( $cat_or_postpage == 0 ) { 
				$args = array(
				  'post_type' =>array('page','post'),
				  'post__in'=> explode(',', $postids),
				);
			} else {
				$args = array(
				  'post__not_in'=>$do_not_duplicate,
				  'posts_per_page' => $postnr,
				  'orderby' => $post_order,
				  'cat' => $postids
				);	
			}
			
            $gab_query = new WP_Query();$gab_query->query($args); 
            while ($gab_query->have_posts()) : $gab_query->the_post();
			if (function_exists('gab_media')) {
				if (of_get_option('of_dnd') == 1) { $do_not_duplicate[] = $post->ID; }
			}
            ?>

                <div class="<?php if ( $postcls ) { echo $postcls; } else { echo 'featuredpost'; } if($count == $postnr) { echo ' lastpost'; } ?>">

					<?php
					if ( $d_thumb ) {
						if ( $swap ) { ?>
							<h2 class="<?php if ( $titlecls ) { echo $titlecls; } else { echo 'posttitle'; } ?>">
								<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( esc_attr__( 'Permalink to %s', 'gabfire-widget-pack' ), the_title_attribute( 'echo=0' ) ); ?>" ><?php the_title(); ?></a>
							</h2>   
							<?php
							if (function_exists('gab_media')) {
								gab_media(array(
									'name' => 'gabfire-widget-pack',
									'imgtag' => 1,
									'link' => 1,
									'enable_video' => $video,
									'video_id' => 'custom-widget',
									'catch_image' => 0,
									'enable_thumb' => 1,
									'resize_type' => 'c',
									'media_width' => $media_w, 
									'media_height' => $media_h, 
									'thumb_align' => $thumbalign,
									'enable_default' => 0
								));
							} else {
								add_image_size( 'custom-query', 100, 100, true );
							}						
						} else {
							if (function_exists('gab_media')) {
								gab_media(array(
									'name' => 'gabfire-widget-pack',
									'imgtag' => 1,
									'link' => 1,
									'enable_video' => $video,
									'video_id' => 'custom-widget',
									'catch_image' => 0,
									'enable_thumb' => 1,
									'resize_type' => 'c',
									'media_width' => $media_w, 
									'media_height' => $media_h, 
									'thumb_align' => $thumbalign,
									'enable_default' => 0
								));
							} ?>
							<h2 class="posttitle">
								<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( esc_attr__( 'Permalink to %s', 'gabfire-widget-pack' ), the_title_attribute( 'echo=0' ) ); ?>" ><?php the_title(); ?></a>
							</h2><?php							
						}
					} else { ?>
						<h2 class="<?php if ( $titlecls ) { echo $titlecls; } else { echo 'posttitle'; } ?>">
							<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( esc_attr__( 'Permalink to %s', 'gabfire-widget-pack' ), the_title_attribute( 'echo=0' ) ); ?>" ><?php the_title(); ?></a>
						</h2>
					<?php }
				    
					if ( $excerpt_l != 0 ) {
						echo '<p>' . string_limit_words(get_the_excerpt(), $excerpt_l) . '&hellip;</p>';
					}
                    
                    if ( $postmeta ) {
						$author_id=$post->post_author;
						echo '<span class="gabfire_meta gabfiremeta_bydate">';
						$authorlink = '<span class="author vcard" itemscope="itemscope" itemtype="http://schema.org/Person" itemprop="author"><a href="'.get_author_posts_url($post->post_author).'" rel="author" class="fn" itemprop="name">'.  get_the_author_meta( 'display_name', $post->post_author ) . '</a></span>';
						$date = '<time class="published updated" itemprop="datePublished" datetime="'. get_the_date() . 'T' . get_the_time() .'">'. get_the_date() . '</time>';
						printf(esc_attr__('By %1$s on %2$s','gabfire'), $authorlink, $date);
						echo '</span>';
                    } ?>
                    
                </div><!-- .featuredpost -->
            <?php $count++; endwhile; wp_reset_query(); ?>
    <?php         
        echo "<div class='clear'></div>$after_widget"; 
    }
    
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['video']     = $new_instance['video'] ? '1' : '0';
        $instance['swap']     = $new_instance['swap'] ? '1' : '0';  
        $instance['postnr']     = ( ! empty( $new_instance['postnr'] ) ) ? sanitize_text_field( (int)$new_instance['postnr'] ) : '';
		$instance['postids']     = ( ! empty( $new_instance['postids'] ) ) ? sanitize_text_field( $new_instance['postids'] ) : '';
		$instance['c_link']     = ( ! empty( $new_instance['c_link'] ) ) ? sanitize_text_field( $new_instance['c_link'] ) : '';
        $instance['c_image']     = ( ! empty( $new_instance['c_image'] ) ) ? sanitize_text_field( $new_instance['c_image'] ) : '';
        $instance['cat_or_postpage']     = ( ! empty( $new_instance['cat_or_postpage'] ) ) ? sanitize_text_field( $new_instance['cat_or_postpage'] ) : '';
		$instance['post_order']     = ( ! empty( $new_instance['post_order'] ) ) ? sanitize_text_field( $new_instance['post_order'] ) : '';
        $instance['d_thumb']     = $new_instance['d_thumb'] ? '1' : '0';
        $instance['postmeta']     = $new_instance['postmeta'] ? '1' : '0';
        $instance['media_w']     = ( ! empty( $new_instance['media_w'] ) ) ? sanitize_text_field( (int)$new_instance['media_w'] ) : '';
        $instance['media_h']     = ( ! empty( $new_instance['media_h'] ) ) ? sanitize_text_field( (int)$new_instance['media_h'] ) : ''; 
        $instance['excerpt_l']     = ( ! empty( $new_instance['excerpt_l'] ) ) ? sanitize_text_field( (int)$new_instance['excerpt_l'] ) : '';
        $instance['thumbalign']     = ( ! empty( $new_instance['thumbalign'] ) ) ? sanitize_text_field( $new_instance['thumbalign'] ) : '';
		$instance['postcls']     = ( ! empty( $new_instance['postcls'] ) ) ? sanitize_text_field( $new_instance['postcls'] ) : '';
		$instance['titlecls']     = ( ! empty( $new_instance['titlecls'] ) ) ? sanitize_text_field( $new_instance['titlecls'] ) : '';
        return $instance;
    }

    function form( $instance ) {
        $defaults = array(
			'video' => '0',
			'swap'=> '0',
			'postnr' => '5',
			'postids' => '',
			'c_link' => '',
			'c_image' => '',
			'cat_or_postpage' => '',
			'post_order' => 'date',
			'd_thumb' => '0',
			'postmeta' => '0',
			'media_w' => '35',
			'media_h' => '35',
			'excerpt_l' => '25',
			'thumbalign' => 'alignleft',
			'title' => 'Query Title',
			'postcls' => 'featuredpost',
			'titlecls' => 'posttitle'
		);
        $instance = wp_parse_args( (array) $instance, $defaults ); 
        ?>
        	
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Section or Category Title','gabfire-widget-pack'); ?> <span style="color:#aaa"><?php _e('(leave empty for no heading)','gabfire-widget-pack'); ?></span></label>
			<input class="widefat"  id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
		
        <p>
			<label for="<?php echo $this->get_field_id('c_image'); ?>"><?php _e('Section or Category Image URL (replaces text title)','gabfire-widget-pack'); ?> <span style="color:#aaa"><?php _e('ex: //www.domain.com/image.jpg)', 'gabfire-widget-pack'); ?></span></label> 
			<input class="widefat"  id="<?php echo $this->get_field_id('c_image'); ?>" name="<?php echo $this->get_field_name('c_image'); ?>" type="text" value="<?php echo esc_attr( $instance['c_image'] ); ?>" />
        </p>

        <p>
			<label for="<?php echo $this->get_field_id('c_link'); ?>"><?php _e('Link for Section or Category title','gabfire-widget-pack'); ?> <span style="color:#aaa"><?php _e('(leave empty for to disable)','gabfire-widget-pack'); ?></span></label> 
			<input class="widefat"  id="<?php echo $this->get_field_id('c_link'); ?>" name="<?php echo $this->get_field_name('c_link'); ?>" type="text" value="<?php echo esc_attr( $instance['c_link'] ); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('cat_or_postpage'); ?>"><?php _e('Do you want to run a query based on Category or Post/Page ID','gabfire-widget-pack'); ?></label><br />
            <select id="<?php echo $this->get_field_id( 'cat_or_postpage' ); ?>" name="<?php echo $this->get_field_name( 'cat_or_postpage' ); ?>" style="width:480px">
                <option value="1" <?php if ( '1' == $instance['cat_or_postpage'] ) echo 'selected="selected"'; ?>><?php _e('Category','gabfire-widget-pack'); ?></option>
                <option value="0" <?php if ( '0' == $instance['cat_or_postpage'] ) echo 'selected="selected"'; ?>><?php _e('Post/Page','gabfire-widget-pack'); ?></option>    
            </select>
        </p>
		
        <p>
            <label for="<?php echo $this->get_field_id('postids'); ?>"><?php _e('Enter <a href="//www.gabfirethemes.com/how-to-check-category-ids/">Category ID(s)</a> - OR - Post/Page ID(s)','gabfire-widget-pack'); ?> <span style="color:#aaa"><?php _e('(ex:3,5,99 comma separated, no spaces)','gabfire-widget-pack'); ?></span></label>
            <input class="widefat"  id="<?php echo $this->get_field_id('postids'); ?>" name="<?php echo $this->get_field_name('postids'); ?>" type="text" value="<?php echo esc_attr( $instance['postids'] ); ?>" />
        </p>   		
		
        <p>
            <label for="<?php echo $this->get_field_id('post_order'); ?>"><?php _e('Post Order: only if category option is selected as base for query','gabfire-widget-pack'); ?></label><br />
            <select id="<?php echo $this->get_field_id( 'post_order' ); ?>" name="<?php echo $this->get_field_name( 'post_order' ); ?>" style="width:480px">
                <option value="date" <?php if ( 'date' == $instance['post_order'] ) echo 'selected="selected"'; ?>><?php _e('Most Recent','gabfire-widget-pack'); ?></option>
                <option value="rand" <?php if ( 'rand' == $instance['post_order'] ) echo 'selected="selected"'; ?>><?php _e('Random','gabfire-widget-pack'); ?></option>
				<option value="comment_count" <?php if ( 'comment_count' == $instance['post_order'] ) echo 'selected="selected"'; ?>><?php _e('Most Popular (Criteria: Comment Count)','gabfire-widget-pack'); ?></option>
            </select>
        </p>			
		
        <p>
            <label for="<?php echo $this->get_field_name( 'postnr' ); ?>"><?php _e('Number of entries to display','gabfire-widget-pack'); ?></label>
            <select id="<?php echo $this->get_field_id( 'postnr' ); ?>" name="<?php echo $this->get_field_name( 'postnr' ); ?>">            
            <?php
                for ( $i = 1; $i <= 15; ++$i )
                echo "<option value='$i' " . ( $instance['postnr'] == $i ? "selected='selected'" : '' ) . ">$i</option>";
            ?>
            </select>
        </p>        

        <p style="background-color: #efefef;border:1px solid #ddd;padding:10px;"><?php _e('Custom query will generate thumbnails only if TimThumb is the active thumbnail script. All Gabfire themes have TimThumb option enabled by default. However, if you use WP Post Thumbnails option, then thumbnails CANNOT be generated by this widget.','gabfire-widget-pack'); ?></p>
		
        <p style="float:left;width:235px">
            <label for="<?php echo $this->get_field_id( 'd_thumb' ); ?>"><?php _e('Show Thumbnails','gabfire-widget-pack'); ?></label><br />
            <select id="<?php echo $this->get_field_id( 'd_thumb' ); ?>" name="<?php echo $this->get_field_name( 'd_thumb' ); ?>" style="width:235px">
                <option value="1" <?php if ( '1' == $instance['d_thumb'] ) echo 'selected="selected"'; ?>><?php _e('Yes','gabfire-widget-pack'); ?></option>
                <option value="0" <?php if ( '0' == $instance['d_thumb'] ) echo 'selected="selected"'; ?>><?php _e('No','gabfire-widget-pack'); ?></option>    
            </select>
        </p>
		
        <p style="float:right;width:235px">
            <label for="<?php echo $this->get_field_id( 'video' ); ?>"><?php _e('Show Videos','gabfire-widget-pack'); ?></label><br />
            <select id="<?php echo $this->get_field_id( 'video' ); ?>" name="<?php echo $this->get_field_name( 'video' ); ?>" style="width:235px">
                <option value="1" <?php if ( '1' == $instance['video'] ) echo 'selected="selected"'; ?>><?php _e('Yes','gabfire-widget-pack'); ?></option>
                <option value="0" <?php if ( '0' == $instance['video'] ) echo 'selected="selected"'; ?>><?php _e('No','gabfire-widget-pack'); ?></option>    
            </select>
        </p>   		
		
        <p style="float:left;width:235px">
            <label for="<?php echo $this->get_field_id('media_w'); ?>"><?php _e('Width of Thumbnail (ex: 50 or 300)','gabfire-widget-pack'); ?></label>
            <input class="widefat"  id="<?php echo $this->get_field_id('media_w'); ?>" name="<?php echo $this->get_field_name('media_w'); ?>" type="text" value="<?php echo esc_attr( $instance['media_w'] ); ?>" />
        </p>

        <p style="float:right;width:235px">
            <label for="<?php echo $this->get_field_id('media_h'); ?>"><?php _e('Height of Thumbnail (ex: 50 or 300)','gabfire-widget-pack'); ?></label>
            <input class="widefat"  id="<?php echo $this->get_field_id('media_h'); ?>" name="<?php echo $this->get_field_name('media_h'); ?>" type="text" value="<?php echo esc_attr( $instance['media_h'] ); ?>" />
        </p>
		
        <p style="float:left;width:235px">
            <label for="<?php echo $this->get_field_id( 'thumbalign' ); ?>"><?php _e('Thumbnail Alignment','gabfire-widget-pack'); ?></label><br />
            <select id="<?php echo $this->get_field_id( 'thumbalign' ); ?>" name="<?php echo $this->get_field_name( 'thumbalign' ); ?>" style="width:235px">
                <option value="alignleft" <?php if ( 'alignleft' == $instance['thumbalign'] ) echo 'selected="selected"'; ?>><?php _e('Left','gabfire-widget-pack'); ?></option>
                <option value="alignright" <?php if ( 'alignright' == $instance['thumbalign'] ) echo 'selected="selected"'; ?>><?php _e('Right','gabfire-widget-pack'); ?></option>
                <option value="aligncenter" <?php  if ( 'aligncenter' == $instance['thumbalign'] ) echo 'selected="selected"'; ?>><?php _e('Center','gabfire-widget-pack'); ?></option>            
            </select>
        </p>
		
        <p style="float:right;width:235px">
            <label for="<?php echo $this->get_field_id('excerpt_l'); ?>"><?php _e('# of Words in Excerpt (ex:35 / Max: 55)','gabfire-widget-pack'); ?></label>
            <input class="widefat"  id="<?php echo $this->get_field_id('excerpt_l'); ?>" name="<?php echo $this->get_field_name('excerpt_l'); ?>" type="text" value="<?php echo esc_attr( $instance['excerpt_l'] ); ?>" />
        </p> 
		
		<div style="clear:both"></div>
		
        <p>
			<label for="<?php echo $this->get_field_id( 'swap' ); ?>"><?php _e('Thumbnail and Title Position','gabfire-widget-pack'); ?></label><br />
			<select id="<?php echo $this->get_field_id( 'swap' ); ?>" name="<?php echo $this->get_field_name( 'swap' ); ?>" style="width:480px">
				<option value="1" <?php if ( '1' == $instance['swap'] ) echo 'selected="selected"'; ?>><?php _e('Show title first then thumbnail','gabfire-widget-pack'); ?></option>
				<option value="0" <?php if ( '0' == $instance['swap'] ) echo 'selected="selected"'; ?>><?php _e('Show thumbnail first then title','gabfire-widget-pack'); ?></option>
			</select>
		</p>			
		        
        <p>
            <label for="<?php echo $this->get_field_id( 'postmeta' ); ?>"><?php _e('Display post meta below excerpt','gabfire-widget-pack'); ?></label> 
            <select id="<?php echo $this->get_field_id( 'postmeta' ); ?>" name="<?php echo $this->get_field_name( 'postmeta' ); ?>">
                <option value="1" <?php if ( '1' == $instance['postmeta'] ) echo 'selected="selected"'; ?>><?php _e('Enable','gabfire-widget-pack'); ?></option>
                <option value="0" <?php if ( '0' == $instance['postmeta'] ) echo 'selected="selected"'; ?>><?php _e('Disable','gabfire-widget-pack'); ?></option>    
            </select>
        </p>
		
		<h4><?php _e('Advanced - For Developers Only','gabfire-widget-pack'); ?></h4>
		
		<p style="background-color: #efefef;border:1px solid #ddd;padding:10px;"><?php _e('For developers only. Do not edit any of classes below unless you know what you are doing.','gabfire-widget-pack'); ?></p>
		
        <p style="float:left;width:235px">
            <label for="<?php echo $this->get_field_id('postcls'); ?>"><?php _e('CSS Class of Post Wrapper Div','gabfire-widget-pack'); ?></label>
            <input class="widefat"  id="<?php echo $this->get_field_id('postcls'); ?>" name="<?php echo $this->get_field_name('postcls'); ?>" type="text" value="<?php echo esc_attr( $instance['postcls'] ); ?>" />
        </p>

        <p style="float:right;width:235px">
            <label for="<?php echo $this->get_field_id('titlecls'); ?>"><?php _e('CSS Class of Post Title','gabfire-widget-pack'); ?></label>
            <input class="widefat"  id="<?php echo $this->get_field_id('titlecls'); ?>" name="<?php echo $this->get_field_name('titlecls'); ?>" type="text" value="<?php echo esc_attr( $instance['titlecls'] ); ?>" />
        </p>		
<?php
    }
}

function register_gab_custom_query() {
	register_widget('gab_custom_query');
}

add_action('widgets_init', 'register_gab_custom_query');