<?php

// File Security Check
if ( ! defined( 'ABSPATH' ) ) exit; 

class wpwidget_media_gallery extends WP_Widget { 
    
        function __construct() {
            $widget_ops = array('description' => __('Creates gallery on your sidebar and has ability to display on page of your choice.') );
            $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'wpwidget_media_gallery' );
            $this->WP_Widget( 'wpwidget_media_gallery', __('WP Widget Gallery'), $widget_ops, $control_ops ); 
	}

	// Widget Output
	function widget($args, $instance) {
	   
                extract($args); 
                   
                global $post;
		        $title        = apply_filters('widget_title', $instance['title']);               
                $wpwidgetpage = $instance['wpwidgetpage'];
                $wpwidgetsize = $instance['wpwidgetsize'];
                $images       = explode(',',$instance['wpwidget_thumbnail_image']);
                $showtitle    = !empty($instance['wpwidget_showtitle'])?true:false;
                $showdesc     = !empty($instance['wpwidget_showdesc'])?true:false;
                $carousel     = !empty($instance['wpwidget_showcarousel'])?true:false;
                $pager        = !empty($instance['wpwidget_showpager'])?true:false;
                $button       = !empty($instance['wpwidget_showbutton'])?true:false;  
                $scroll       = $instance['wpwidget_cscroll'];
                $delay        = $instance['wpwidget_cdelay'];
                $visible      = $instance['wpwidget_cvisible'];
                
                
                $page_object = get_queried_object();
                $wtheID     = get_queried_object_id();
                $styleadditions = "margin:0 auto 3px;";
                if ($instance['wpwidgetsize'] == "small-thumb"){
                        $smallThumb = array(50, 50);
                        $wpwidgetsize = $smallThumb;
                        // Width and Height added to style as IE was ignoring the default width and height set.
                        //$styleadditions = "float: left; display: block;margin:3px; width: 50px; height: 50px;";
                }
                           
                if( !empty($wpwidgetpage) && !(in_array(0, $instance["wpwidgetpage"]))){     
                    
                if ( in_array($wtheID, $wpwidgetpage) ):
                    // ------                 
                    echo $before_widget;
                    echo $before_title . $title . $after_title;   
                    if (is_array($images)):
                        if ( $carousel ){
                            
                            $scroll == 'scroll-vert' ? $scroll = 'true' : $scroll = 'false'; 
                            
                            $btnpager  = '<div class=wpwidget-button>';
                            $btnpager .= '<a href=# id=wpwidget-button-prev></a>';
                            $btnpager .= '<a href=# id=wpwidget-button-next></a>';
                            $btnpager .= '</div>'; 
                            
                            if ( $button ) { echo $btnpager; }
                            
                            echo "<div class=\"wpwidget-slideshow\" 
                                       data-cycle-carousel-vertical={$scroll} 
                                       data-cycle-fx=carousel 
                                       data-cycle-timeout={$delay} 
                                       data-cycle-carousel-visible={$visible} 
                                       data-cycle-pager=\"#wpwidget-pager\"
                                       data-cycle-prev=\"#wpwidget-button-prev\"
                                       data-cycle-next=\"#wpwidget-button-next\">";                                                                                  
                            
                            foreach( $images as $image){ 
                                    $attachment = get_post( $image );
                                    $obj = array(
                                            'alt' => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
                                            'caption' => $attachment->post_excerpt,
                                            'description' => $attachment->post_content,
                                            'href' => get_permalink( $attachment->ID ),
                                            'src' => $attachment->guid,
                                            'title' => $attachment->post_title
                                    );
                                    $url   = wp_get_attachment_image($image, $wpwidgetsize, false);
                                    $src   = wp_get_attachment_image_src( $image, 'full' );
                                    if ( $url ):
                                        $out   = $url;
                                        if ( $showtitle )
                                        $out  .= '<p style="text-align:center;text-transform:uppercase;font-size:.9em;">'.$obj['title'].'</p>';                            
                                        if ( $showdesc )
                                        $out  .= '<p style="text-align:center;font-size:.9em;">'.$obj['description'].'</p>';                                                     
                                        echo $out;
                                    endif;
                            }                                                        
                            
                            echo '</div>'; 
                            
                            $dotpager = '<div class="cycle-pager" id="wpwidget-pager"></div>';
                            
                            if ( $pager ) { echo $dotpager; }
                            
                            ?>
                                <script>
                                    jQuery(document).ready(function($){
                                        $.fn.cycle.defaults.autoSelector = '.wpwidget-slideshow';
                                    });
                                </script>
                            <?php
                        }else{
                            
                            echo '<ul id="widget-media-container">';    
                                               
                            foreach( $images as $image){ 
                                    $attachment = get_post( $image );
                                    $obj = array(
                                            'alt' => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
                                            'caption' => $attachment->post_excerpt,
                                            'description' => $attachment->post_content,
                                            'href' => get_permalink( $attachment->ID ),
                                            'src' => $attachment->guid,
                                            'title' => $attachment->post_title
                                    );
                                    $url   = wp_get_attachment_image($image, $wpwidgetsize, false);
                                    $src   = wp_get_attachment_image_src( $image, 'full' );
                                    if ( $url ):
                                        $out   = '<li class="item"><a href="'.$src[0].'" data-lightbox="'.$obj['title'].'" title="'.$obj['title'].'">'.$url.'</a>';
                                        if ( $showtitle )
                                        $out  .= '<p style="text-align:center;text-transform:uppercase;font-size:.9em;">'.$obj['title'].'</p>';                            
                                        if ( $showdesc )
                                        $out  .= '<p style="text-align:center;font-size:.9em;">'.$obj['description'].'</p>';                                          
                                        $out  .= '</li>';              
                                        echo $out;
                                    endif;
                            }
                            
                            echo '</ul>';
                            
                         }   
                    endif;
                    echo $after_widget;		                                	                                
		// ------
                endif;    
           }else{
               // ------                 
                    echo $before_widget;
                    echo $before_title . $title . $after_title;   
                    if (is_array($images)):
                    
                        if ( $carousel ){
                            
                            $scroll == 'scroll-vert' ? $scroll = 'true' : $scroll = 'false'; 
                            
                            $btnpager  = '<div class=wpwidget-button>';
                            $btnpager .= '<a href=# id=wpwidget-button-prev></a>';
                            $btnpager .= '<a href=# id=wpwidget-button-next></a>';
                            $btnpager .= '</div>'; 
                            
                            if ( $button ) { echo $btnpager; }
                            
                            echo "<div class=\"wpwidget-slideshow\" 
                                       data-cycle-carousel-vertical={$scroll} 
                                       data-cycle-fx=carousel 
                                       data-cycle-timeout={$delay} 
                                       data-cycle-carousel-visible={$visible} 
                                       data-cycle-pager=\"#wpwidget-pager\"
                                       data-cycle-prev=\"#wpwidget-button-prev\"
                                       data-cycle-next=\"#wpwidget-button-next\">";                                                                                  
                            
                            foreach( $images as $image){ 
                                    $attachment = get_post( $image );
                                    $obj = array(
                                            'alt' => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
                                            'caption' => $attachment->post_excerpt,
                                            'description' => $attachment->post_content,
                                            'href' => get_permalink( $attachment->ID ),
                                            'src' => $attachment->guid,
                                            'title' => $attachment->post_title
                                    );
                                    $url   = wp_get_attachment_image($image, $wpwidgetsize, false);
                                    $src   = wp_get_attachment_image_src( $image, 'full' );
                                    if ( $url ):
                                        $out   = $url;
                                        if ( $showtitle )
                                        $out  .= '<p style="text-align:center;text-transform:uppercase;font-size:.9em;">'.$obj['title'].'</p>';                            
                                        if ( $showdesc )
                                        $out  .= '<p style="text-align:center;font-size:.9em;">'.$obj['description'].'</p>';                                                     
                                        echo $out;
                                    endif;
                            }                                                        
                            
                            echo '</div>'; 
                            
                            $dotpager = '<div class="cycle-pager" id="wpwidget-pager"></div>';
                            
                            if ( $pager ) { echo $dotpager; }
                            
                            ?>
                                <script>
                                    jQuery(document).ready(function($){
                                        $.fn.cycle.defaults.autoSelector = '.wpwidget-slideshow';
                                    });
                                </script>
                            <?php
                        }else{
                            
                            echo '<ul id="widget-media-container">';    
                                               
                            foreach( $images as $image){ 
                                    $attachment = get_post( $image );
                                    $obj = array(
                                            'alt' => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
                                            'caption' => $attachment->post_excerpt,
                                            'description' => $attachment->post_content,
                                            'href' => get_permalink( $attachment->ID ),
                                            'src' => $attachment->guid,
                                            'title' => $attachment->post_title
                                    );
                                    $url   = wp_get_attachment_image($image, $wpwidgetsize, false);
                                    $src   = wp_get_attachment_image_src( $image, 'full' );
                                    if ( $url ):
                                        $out   = '<li class="item"><a href="'.$src[0].'" rel="prettyPhoto[wpwidgetgallery]" title="'.$obj['title'].'">'.$url.'</a>';
                                        if ( $showtitle )
                                        $out  .= '<p style="text-align:center;text-transform:uppercase;font-size:.9em;">'.$obj['title'].'</p>';                            
                                        if ( $showdesc )
                                        $out  .= '<p style="text-align:center;font-size:.9em;">'.$obj['description'].'</p>';                                          
                                        $out  .= '</li>';              
                                        echo $out;
                                    endif;
                            }
                            
                            echo '</ul>';
                            
                         }   
                         
                    endif;
                    
                    echo $after_widget;		                                
		// ------
           }    
	}

// Update
	function update( $new_instance, $old_instance ) {  
		$instance = $old_instance; 
                $instance['title'] = strip_tags( $new_instance['title'] );
                $instance['wpwidgetpage'] = $new_instance['wpwidgetpage'];
                $instance['wpwidgetsize'] = strip_tags($new_instance['wpwidgetsize']);
                $instance['wpwidget_showtitle'] = isset($new_instance['wpwidget_showtitle']);
                $instance['wpwidget_showdesc'] = isset($new_instance['wpwidget_showdesc']);
                $instance['wpwidget_thumbnail_image'] = $new_instance['wpwidget_thumbnail_image'];
                $instance['wpwidget_showcarousel'] = isset($new_instance['wpwidget_showcarousel']);
                $instance['wpwidget_cscroll'] = strip_tags($new_instance['wpwidget_cscroll']);
                $instance['wpwidget_showpager'] = isset($new_instance['wpwidget_showpager']);
                $instance['wpwidget_showbutton'] = isset($new_instance['wpwidget_showbutton']);
                $instance['wpwidget_cdelay'] = $new_instance['wpwidget_cdelay'];
                $instance['wpwidget_cvisible'] = $new_instance['wpwidget_cvisible'];    
                
		return $instance;
	}
	
	// Backend Form
	function form($instance) {
		
		$defaults = array( 
                    'title' => 'Widget Gallery',
                    'wpwidgetpage' => 0,
                    'wpwidgetsize' => 'thumbnail',
                    'wpwidget_showtitle' => false,
                    'wpwidget_showdesc' => false,
                    'wpwidget_thumbnail_image' => array(),
                    'wpwidget_showcarousel' => false,
                    'wpwidget_cscroll' => 'scroll-horz',
                    'wpwidget_showpager' => true,
                    'wpwidget_showbutton' => true,
                    'wpwidget_cdelay' => 1000,
                    'wpwidget_cvisible' => 1 ); // Default Values
                    
		$instance = wp_parse_args( (array) $instance, $defaults ); 
                
        ?>        
        <p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Widget Title:</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
	    </p>
        <p>
                    <label for="<?php echo $this->get_field_id( 'wpwidgetpage' ); ?>">Show in Pages:<br /></label>			
                    <?php  
                        $args = array(
                                'sort_order' => 'ASC',
                                'sort_column' => 'post_title',
                                'hierarchical' => 1,
                                'exclude' => '',
                                'include' => '',
                                'meta_key' => '',
                                'meta_value' => '',
                                'authors' => '',
                                'child_of' => 0,
                                'parent' => -1,
                                'exclude_tree' => '',
                                'number' => '',
                                'offset' => 0,
                                'post_type' => 'page',
                                'post_status' => 'publish'
                        ); 
                        $pages = get_pages($args); 
                        ?>

                        <select multiple='multiple' name="<?php echo $this->get_field_name('wpwidgetpage'); ?>[]" style='width:100%;'>                        
                        
                            <?php 
                                if ( $instance["wpwidgetpage"] === NULL || !is_array($instance['wpwidgetpage']) ){
                                    echo '<option value=0 selected=selected>Default (Displayed in Sidebar)</options>';
                                }else{
                                    echo '<option value=0 '.selected(in_array(0, $instance["wpwidgetpage"])).'>Default (Displayed in Sidebar)</options>';
                                }                                          
                            ?>  
                              
                            <?php  foreach ($pages as $page): ?>
                            
                            <?php  if (is_array($instance['wpwidgetpage']) ): ?>            
                                        <option value="<?php echo $page->ID ?>" <?php selected(in_array($page->ID, $instance["wpwidgetpage"]))?>><?php echo trim($page->post_title)?></options>
                            <?php   else: ?>
                                        <option value="<?php echo $page->ID ?>"><?php echo trim($page->post_title) ?></options>
                            <?php   endif; ?>

                        <?php endforeach;  ?>

                        </select>   
                        <small>Hold down the Ctrl (windows) / Command (Mac) button to select multiple options.</small>                            
        </p>        
        <p>
                    <label for="<?php echo $this->get_field_id( 'wpwidgetsize' ); ?>">Image Size:<br /></label>
                    <select name="<?php echo $this->get_field_name('wpwidgetsize'); ?>" style='width:100%;'> 
                        <?php $selected = 'selected=selected'; echo $instance['wpwidgetsize']; ?>
                        <option value="small-thumb" <?php if (trim($instance['wpwidgetsize']) == 'small-thumb')echo $selected; else ''; ?>>Small Thumb</options>
                        <option value="thumbnail" <?php if (trim($instance['wpwidgetsize']) == 'thumbnail')echo $selected; else ''; ?>>Thumbnail</options>
                         <option value="medium" <?php if (trim($instance['wpwidgetsize']) == 'medium')echo $selected; else ''; ?>>Medium</options>
                         <option value="full" <?php if (trim($instance['wpwidgetsize']) == 'full')echo $selected; else ''; ?>>Full</options>    
                    </select>
        </p>
        <p>                
		      <input type="checkbox" class="" id="<?php echo $this->get_field_id( 'wpwidget_showtitle' ); ?>" name="<?php echo $this->get_field_name( 'wpwidget_showtitle' ); ?>" <?php checked(isset($instance['wpwidget_showtitle']) ? $instance['wpwidget_showtitle'] : 0); ?>/>
              <label for="<?php echo $this->get_field_id( 'wpwidget_showtitle' ); ?>">Display image title:</label>
        </p>
        <p>                
		      <input type="checkbox" class="" id="<?php echo $this->get_field_id( 'wpwidget_showdesc' ); ?>" name="<?php echo $this->get_field_name( 'wpwidget_showdesc' ); ?>" <?php checked(isset($instance['wpwidget_showdesc']) ? $instance['wpwidget_showdesc'] : 0); ?> />
              <label for="<?php echo $this->get_field_id( 'wpwidget_showdesc' ); ?>">Display image description:</label>
        </p>
        <p class="wp-widget-gal">
                <input type="button" value="<?php _e( 'Upload Image', 'wpwidget_media_gallery' ); ?>" class="button wpwidget_media_upload" id="wpwidget_media_upload"/>                  	
                <input type="hidden" value="<?php echo $instance['wpwidget_thumbnail_image'] ?>" name="<?php echo $this->get_field_name('wpwidget_thumbnail_image'); ?>" class="wpwidget_arr" id="<?php echo $this->get_field_id( 'wpwidget_thumbnail_image' ); ?>">
                <?php if (!empty($instance['wpwidget_thumbnail_image'])){ ?>
        	<ul class="wpwidgetgallery">
                    <?php       
                        /*
                        if (is_admin() && !isset($_COOKIE['image_array'])) {                                                    
                            unset($_COOKIE['image_array']);
                            setcookie('image_array', '', time() - 3600);
                            setcookie("image_array", $instance['wpwidget_thumbnail_image']);
                        }else{
                            unset($_COOKIE['image_array']);
                            setcookie('image_array', '', time() - 3600);
                            setcookie("image_array", $instance['wpwidget_thumbnail_image']);
                        } 
                         * 
                         */       
                        $images = explode(',',$instance['wpwidget_thumbnail_image']);
                        $cnt = 0;
                        
                        foreach( $images as $image):
                            $url = wp_get_attachment_image($image, array(80,80),false, array('data-attachment_id' => $image ));
                            if ($url):
                            $out  = '<li style="display:inline-block;padding:5px;">'.$url;
                            $out .= '<div class="wpwidgetoverlay"><a href="#" data-attachment_id ="'.$image.'" id="'.$cnt.'" class="wpwidget_rem_img">remove</a> | <a href="post.php?post='.$image.'&action=edit" id="'. $image .'" class="wpwidget_edit_img" >edit</a></div>';
                            $out .= '</li>';        
                            $cnt++;        
                            echo $out;
                            endif;
                        endforeach;     
                    ?>
                </ul> 
                <?php } ?>
        </p>
        
        <p>
            <input type="checkbox" class="enable-carousel" id="<?php echo $this->get_field_id( 'wpwidget_showcarousel' ); ?>" name="<?php echo $this->get_field_name( 'wpwidget_showcarousel' ); ?>" <?php checked(isset($instance['wpwidget_showcarousel']) ? $instance['wpwidget_showcarousel'] : 0); ?> />
            <label for="<?php echo $this->get_field_id( 'wpwidget_showcarousel' ); ?>">Enable carousel:</label>            
        </p>
        
        <div id="carousel_content">
            <p>
                <input type="checkbox" class="" id="<?php echo $this->get_field_id( 'wpwidget_showpager' ); ?>" name="<?php echo $this->get_field_name( 'wpwidget_showpager' ); ?>" <?php checked(isset($instance['wpwidget_showpager']) ? $instance['wpwidget_showpager'] : 0); ?> />
                <label for="<?php echo $this->get_field_id( 'wpwidget_showpager' ); ?>">Show Pager:</label>
            </p>
            <p>
                <input type="checkbox" class="" id="<?php echo $this->get_field_id( 'wpwidget_showbutton' ); ?>" name="<?php echo $this->get_field_name( 'wpwidget_showbutton' ); ?>" <?php checked(isset($instance['wpwidget_showbutton']) ? $instance['wpwidget_showbutton'] : 0); ?> />
                <label for="<?php echo $this->get_field_id( 'wpwidget_showbutton' ); ?>">Show Prev / Next:</label>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'wpwidget_cscroll' ); ?>">Carousel Display:<br /></label>
                <select name="<?php echo $this->get_field_name('wpwidget_cscroll'); ?>" style='width:100%;'> 
                    <?php $selected = 'selected=selected'; echo $instance['wpwidget_cscroll']; ?>
                    <option value="scroll-horz" <?php if (trim($instance['wpwidget_cscroll']) == 'scroll-horz')echo $selected; else ''; ?>>Horizontal</options>
                    <option value="scroll-vert" <?php if (trim($instance['wpwidget_cscroll']) == 'scroll-vert')echo $selected; else ''; ?>>Vertical</options>  
                </select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'wpwidget_cdelay' ); ?>">Delay:<br /></label>
                <input type="text" class="" id="<?php echo $this->get_field_id( 'wpwidget_cdelay' ); ?>" name="<?php echo $this->get_field_name( 'wpwidget_cdelay' ); ?>" value="<?php echo $instance['wpwidget_cdelay']; ?>" />                
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'wpwidget_cvisible' ); ?>">Number of visible images:<br /></label>
                <input type="text" class="" id="<?php echo $this->get_field_id( 'wpwidget_cvisible' ); ?>" name="<?php echo $this->get_field_name( 'wpwidget_cvisible' ); ?>" value="<?php echo $instance['wpwidget_cvisible']; ?>" />                
            </p>
        </div>
                
<?php }       
}//end of class 
?>