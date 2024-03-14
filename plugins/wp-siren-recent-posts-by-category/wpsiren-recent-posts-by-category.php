<?php
/*

Plugin Name: WPSIREN Recent Posts By Category
Description: Adds a Recent Posts By Category Widget @ Widget Dashboard
Plugin Uri: http://www.wpsiren.com/posts-by-category
Author: WPSIREN
Author Uri: http://www.wpsiren.com
License: GPL
Version: 1.0.2


*/


// Registering the Widget

function register_RPBC_Widget(){
    register_widget('RPBC_Widget');
}
add_action('widgets_init','register_RPBC_Widget');

// Registering the Style & Enqueue it

function rpbc_style(){
  wp_register_style('rpbcStyle', plugins_url('wpsiren-recent-posts-by-category-style.css', __FILE__));
  wp_enqueue_style('rpbcStyle');
}

add_action('wp_enqueue_scripts','rpbc_style');


// RPBC Widget Class

class RPBC_Widget extends WP_Widget {  
    /** 
     * Register widget with WordPress. 
     */  
    public function __construct() {  
        parent::__construct(  
            'rpbc', // Base ID  
            'WPSiren - Recent Posts By Category', // Name  
            array( 
            'classname' => 'widget_rpbc',
            'description' => __( 'Add Recent Posts By Category', 'wpsiren.com' ) 
            
            ) // Args  
        );  
    }  
    /** 
     * Front-end display of widget. 
     * 
     * @see WP_Widget::widget() 
     * 
     * @param array $args     Widget arguments. 
     * @param array $instance Saved values from database. 
     */  
    public function widget( $args, $instance ) { 

        extract( $args );  
        $allwExcerpts = $instance['allow_excerpts'];
        $allwImages = $instance['allow_images'];
        $addIcn = $instance['add_icon'];
        $iconURI = $instance['icon_uri'];
        $iconWidth = $instance['icon_width'];
        $iconHeight = $instance['icon_height'];
        $iconImage = "<img class='rpbc_widget_icon' src='" . $iconURI ."' width='" . $iconWidth ."' height='" . $iconHeight . "' alt='" . $title . "'/>";  
        $imageWidth = $instance['image_width'];
        $imageHeight = $instance['image_height']; 
        $imageFloat = $instance['image_float']; 
        $widgtBGColor = $instance['widget_background_color'];
        $widgtTextColor = $instance['widget_text_color'];
        $widgtLinkColor = $instance['widget_link_color'];
        $widgtTitleColor = $instance['widget_title_text_color'];
        $allwComments= $instance['allow_comments'];
        $shwCredits = $instance['show_credits'];
        $commentsText= $instance['no_comments_text'];

        $title = apply_filters( 'widget_title', $instance['title'] );  
        echo $before_widget;
        echo "<div class='rpbc_widget_inner' style='background:" . $widgtBGColor . ";'>";
        if ( ! empty( $title ) )  
            echo $before_title . '<span style="color:' . $widgtTitleColor . ';">' . ( $addIcn == '1' ? '<p class=fixit>' . $iconImage . $title . '</p>' : $title ) . '</span>' . $after_title;

        $totalposts = $instance['noofposts'];
        $catName = $instance['category_name'];

// 

        if($catName == 'ALL'){
         
          $latestposts = query_posts('posts_per_page=' . $totalposts );

        }else{

        $latestposts = query_posts('posts_per_page=' . $totalposts . '&category_name=' . $catName);
        }
        echo '<ul class="rpbc">';
       
       while( have_posts($latestposts)) : the_post($latestposts); ?>
       
       <li class="fixit" style="color: <?php echo $widgtTextColor; ?>;">
        <?php

// Thumbnails

if($allwImages == '1'){ 
        
if ( has_post_thumbnail() ) {
     $thumbnail = get_the_post_thumbnail($latestpost->ID ,array($imageWidth, $imageHeight));
     echo '<div class="rpbc_thumbnail_' . ($imageFloat == "left" ? "left" : "right") . '">' . $thumbnail . '</div>';
}
else {
    
      $defaultthumburl = plugins_url('images/thumbnail_default.png', __FILE__);

    echo '<div class="rpbc_thumbnail_' . ($imageFloat == 'left' ? 'left' : 'right') . '"><img src="' . $defaultthumburl . '" width="' . $imageWidth . '" height="' . $imageHeight . '" /></div>';
}

}

?>
        <div class="rpbc_content" style="background:<?php ;?>;">

        <a style='color: <?php echo $widgtLinkColor; ?>;' href='<?php the_permalink(); ?>'><?php the_title(); ?></a>

      <?php

      //Excerpts

      if($allwExcerpts == '1'){ 

      $rpbc_excerpt_length = create_function('$length', "return " . $instance["excerpts_length"] . ";");
    if ( $instance["excerpts_length"] > 0 )
        add_filter('excerpt_length', $rpbc_excerpt_length);


$custom_excerpt_more = create_function('$more',"return ' ..';");
add_filter( 'excerpt_more', $custom_excerpt_more);

        ?>

      <p> <?php the_excerpt(); ?></p>

      </div>

  
      
      <?php
      }

// Comments

if($allwComments == '1'){       
global $wpdb;
$pid = get_the_ID();


$TotalComments = $wpdb->get_var(

           $wpdb->prepare("SELECT count(*) as 'totalcomments' FROM wp_comments WHERE comment_post_ID =%d", $pid )
  );


if($TotalComments == '0'){
?>
<div class="rpbc_comments_count">
<a href="<?php the_permalink(); ?>#comments"><?php echo $commentsText; ?></a>
</div>
<?php

}else{
?>
<div class="rpbc_comments_count">
<a href="<?php the_permalink(); ?>#comments"><?php echo $TotalComments . ( $TotalComments > 1 && $TotalComments != 0 ? ' Comments' : ' Comment' ); ?></a> 
</div>
<?php
}

}
   
      ?>
  


  </li>
      <?php

       endwhile;
               
        echo '</ul>';
        if($shwCredits == '1'){
            echo "<div class='rpbc-credits'><a href='http://www.wpsiren.com' title='www.wpsiren.com'>Powered by WPSIREN</a></div>";
        }
        wp_reset_query();
        echo "</div>";
        echo $after_widget;  
    }  
    /** 
     * Sanitize widget form values as they are saved. 
     * 
     * @see WP_Widget::update() 
     * 
     * @param array $new_instance Values just sent to be saved. 
     * @param array $old_instance Previously saved values from database. 
     * 
     * @return array Updated safe values to be saved. 
     */  
    public function update( $new_instance, $old_instance ) {  
        $instance = array();  
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['noofposts'] = strip_tags( $new_instance['noofposts'] );
        $instance['category_name'] = strip_tags( $new_instance['category_name'] );
        $instance['allow_excerpts'] = (bool)$new_instance['allow_excerpts'];
        $instance['excerpts_length'] = strip_tags( $new_instance['excerpts_length'] );
        $instance['allow_images'] = (bool)$new_instance['allow_images'];
        $instance['image_width'] = strip_tags($new_instance['image_width']);
        $instance['image_height'] = strip_tags($new_instance['image_height']);
        $instance['image_float'] = strip_tags($new_instance['image_float']);
        $instance['add_icon'] = (bool)$new_instance['add_icon'];
        $instance['icon_uri'] = strip_tags($new_instance['icon_uri']);
        $instance['icon_width'] = strip_tags($new_instance['icon_width']);
        $instance['icon_height'] = strip_tags($new_instance['icon_height']);
        $instance['widget_background_color'] = $new_instance['widget_background_color'];
        $instance['widget_text_color'] = $new_instance['widget_text_color'];
        $instance['widget_link_color'] = $new_instance['widget_link_color'];
        $instance['widget_title_text_color'] = $new_instance['widget_title_text_color'];
        $instance['allow_comments'] = (bool)$new_instance['allow_comments'];
        $instance['no_comments_text'] = strip_tags($new_instance['no_comments_text']);
        $instance['show_credits'] = (bool)$new_instance['show_credits'];
        return $instance;  
    }  
    /** 
     * Back-end widget form. 
     * 
     * @see WP_Widget::form() 
     * 
     * @param array $instance Previously saved values from database. 
     */  
    public function form( $instance ) {  
        
    // Default Values Of Options

        if ( isset( $instance[ 'title' ] ) ) {  
            $title = $instance[ 'title' ];  
        }
          

          if ( isset( $instance[ 'noofposts' ] ) ) {  
            $noofposts = $instance[ 'noofposts' ];  
        } else{
            $noofposts = "5";
        }


          if ( isset( $instance[ 'excerpts_length' ] ) ) {  
            $excerptsLength = $instance[ 'excerpts_length' ];  
        } else{
            $excerptsLength = "20";
        }
       

       if ( isset( $instance[ 'image_width' ] ) ) {  
            $imageWidth = $instance[ 'image_width' ];  
        }else{
            $imageWidth = "50";
        }

        if ( isset( $instance[ 'image_height' ] ) ) {  
            $imageHeight = $instance[ 'image_height' ];  
        }else{
            $imageHeight = "50";
        }

             
         if ( isset( $instance[ 'icon_uri' ] ) ) {  
            $iconURI = $instance[ 'icon_uri' ];  
        } 
        
        
         if ( isset( $instance[ 'icon_width' ] ) ) {  
            $iconWidth = $instance[ 'icon_width' ];  
        }else{
            $iconWidth = "32";
        }
         
         if ( isset( $instance[ 'icon_height' ] ) ) {  
            $iconHeight = $instance[ 'icon_height' ];  
        }else{
            $iconHeight = "32";
        }
        

        $widgetBGColor = $instance['widget_background_color'];
        $widgetTextColor = $instance['widget_text_color']; 
        $widgetLinkColor = $instance['widget_link_color'];
        $widgetTitleColor = $instance['widget_title_text_color']; 
          
        if(isset($instance['no_comments_text'])) {
        $commentsText = $instance['no_comments_text'];   
        }else{
            $commentsText = "Leave a Comment";
        }
        
        // Form
        ?>
 
        
        <p>  
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>  
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />  
        </p>

        <p>  
        <label for="<?php echo $this->get_field_id( 'noofposts' ); ?>"><?php _e( 'No Of Posts:' ); ?></label>  
        <input class="widefat" id="<?php echo $this->get_field_id( 'noofposts' ); ?>" name="<?php echo $this->get_field_name( 'noofposts' ); ?>" type="text" value="<?php echo esc_attr( $noofposts ); ?>" />  
        </p>

       <p>
        <label for="<?php echo $this->get_field_id('category_name'); ?>"><?php _e('Select a Category:'); ?></label>
        <select class="widefat" id="<?php echo $this->get_field_id('category_name'); ?>" name="<?php echo $this->get_field_name('category_name'); ?>">
        <option class="widefat" value="ALL" <?php ($instance['category_name'] == "ALL" ? "selected='selected'" : " ") ?>>ALL</option>
        <?php 

        $categories = get_categories();
        foreach($categories as $category){
        ?>
        
        <option class="widefat" value="<?php echo $category->name; ?>" <?php if( $instance['category_name'] == $category->name ) { echo "selected='selected'
        "; }else{ echo " "; }?>>
        <?php echo $category->name; ?>
        </option>
        
        <?php

        }
        ?>
        </select></p>
       
       

       <h4 style="margin-bottom:3px">Excerpts</h4>
       <p style="font-size:8px">Enable / Disable Excerpts</p>
       
       <p>  
        <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'allow_excerpts' ); ?>" name="<?php echo $this->get_field_name( 'allow_excerpts' ); ?>" value="1" <?php echo ($instance['allow_excerpts'] == "true" ? "checked='checked'" : ""); ?> />
        <label for="<?php echo $this->get_field_id( 'allow_excerpts' ); ?>"><?php _e( 'Include Excerpts' ); ?></label>  
       </p>

       <p>  
        <label for="<?php echo $this->get_field_id( 'excerpts_length' ); ?>"><?php _e( 'Excerpts Length:' ); ?></label>  
        <input class="widefat" id="<?php echo $this->get_field_id( 'excerpts_length' ); ?>" name="<?php echo $this->get_field_name( 'excerpts_length' ); ?>" type="text" value="<?php echo esc_attr( $excerptsLength ); ?>" />  
       </p>

       
       <h4 style="margin-bottom:3px">Thumbnails</h4>
       <p style="font-size:8px">Enable / Disable Thumbnails, Set Size , Position</p>
        <p>  
        <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'allow_images' ); ?>" name="<?php echo $this->get_field_name( 'allow_images' ); ?>" value="1" <?php echo ($instance['allow_images'] == "true" ? "checked='checked'" : ""); ?> />
        <label for="<?php echo $this->get_field_id( 'allow_images' ); ?>"><?php _e( 'Include Thumbnails' ); ?></label>  
       </p>

       <p>  
        <label for="<?php echo $this->get_field_id( 'image_width' ); ?>"><?php _e( 'Thumbnail Width:' ); ?></label>  
        <input class="widefat" id="<?php echo $this->get_field_id( 'image_width' ); ?>" name="<?php echo $this->get_field_name( 'image_width' ); ?>" type="text" value="<?php echo esc_attr( $imageWidth ); ?>" />  
       </p>

       <p>  
        <label for="<?php echo $this->get_field_id( 'image_height' ); ?>"><?php _e( 'Thumbnail Height:' ); ?></label>  
        <input class="widefat" id="<?php echo $this->get_field_id( 'image_height' ); ?>" name="<?php echo $this->get_field_name( 'image_height' ); ?>" type="text" value="<?php echo esc_attr( $imageHeight ); ?>" />  
       </p>

      
        <p>
       
        <label for="<?php echo $this->get_field_id('image_float'); ?>"><?php _e('Thumbnail Float:'); ?></label>
        <select class="widefat" id="<?php echo $this->get_field_id('image_float'); ?>" name="<?php echo $this->get_field_name('image_float'); ?>">
        
        <option class="widefat" value="<?php echo "left"; ?>" <?php if( $instance['image_float'] == "left" ) { echo "selected='selected'
        "; }else{ echo " "; }?>>
        Left
        </option>
        
        <option class="widefat" value="<?php echo "right"; ?>" <?php if( $instance['image_float'] == "right" ) { echo "selected='selected'
        "; }else{ echo " "; }?>>
        Right
        </option>
        
        </select>

        </p> 
        
        <h4 style="margin-bottom:3px">Icon</h4>
        <p style="font-size:10px">Enable / Disable Icon for Widget Title </p>
        
        <p>  
        <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'add_icon' ); ?>" name="<?php echo $this->get_field_name( 'add_icon' ); ?>" value="1" <?php echo ($instance['add_icon'] == "true" ? "checked='checked'" : ""); ?> />
        <label for="<?php echo $this->get_field_id( 'add_icon' ); ?>"><?php _e( 'Add Icon' ); ?></label>  
        </p>

       <p>  
        <label for="<?php echo $this->get_field_id( 'icon_uri' ); ?>"><?php _e( 'Icon URI:' ); ?></label>  
        <input class="widefat" id="<?php echo $this->get_field_id( 'icon_uri' ); ?>" name="<?php echo $this->get_field_name( 'icon_uri' ); ?>" type="text" value="<?php echo esc_attr( $iconURI ); ?>" />  
       </p>
      
      <p>  
        <label for="<?php echo $this->get_field_id( 'icon_width' ); ?>"><?php _e( 'Icon Width:' ); ?></label>  
        <input class="widefat" id="<?php echo $this->get_field_id( 'icon_width' ); ?>" name="<?php echo $this->get_field_name( 'icon_width' ); ?>" type="text" value="<?php echo esc_attr( $iconWidth ); ?>" />  
       </p>

       <p>  
        <label for="<?php echo $this->get_field_id( 'icon_height' ); ?>"><?php _e( 'Icon Height:' ); ?></label>  
        <input class="widefat" id="<?php echo $this->get_field_id( 'icon_height' ); ?>" name="<?php echo $this->get_field_name( 'icon_height' ); ?>" type="text" value="<?php echo esc_attr( $iconHeight ); ?>" />  
       </p>
  
      
      <h4 style="margin-bottom:3px">Style</h4>
      <p style="font-size:10px">Style your widget</p>
     
       <p>  
        <label for="<?php echo $this->get_field_id( 'widget_background_color' ); ?>"><?php _e( 'Widget Background Color:' ); ?></label>  
        <input class="widefat" id="<?php echo $this->get_field_id( 'widget_background_color' ); ?>" name="<?php echo $this->get_field_name( 'widget_background_color' ); ?>" type="text" value="<?php echo esc_attr( $widgetBGColor ); ?>" />  
       </p>



        <p>  
        <label for="<?php echo $this->get_field_id( 'widget_title_text_color' ); ?>"><?php _e( 'Widget Title Text Color:' ); ?></label>  
        <input class="widefat" id="<?php echo $this->get_field_id( 'widget_title_text_color' ); ?>" name="<?php echo $this->get_field_name( 'widget_title_text_color' ); ?>" type="text" value="<?php echo esc_attr( $widgetTitleColor ); ?>" />  
       </p>

        <p>  
        <label for="<?php echo $this->get_field_id( 'widget_text_color' ); ?>"><?php _e( 'Widget Text Color:' ); ?></label>  
        <input class="widefat" id="<?php echo $this->get_field_id( 'widget_text_color' ); ?>" name="<?php echo $this->get_field_name( 'widget_text_color' ); ?>" type="text" value="<?php echo esc_attr( $widgetTextColor ); ?>" />  
       </p>
       

        <p>  
        <label for="<?php echo $this->get_field_id( 'widget_link_color' ); ?>"><?php _e( 'Widget Link Color:' ); ?></label>  
        <input class="widefat" id="<?php echo $this->get_field_id( 'widget_link_color' ); ?>" name="<?php echo $this->get_field_name( 'widget_link_color' ); ?>" type="text" value="<?php echo esc_attr( $widgetLinkColor ); ?>" />  
       </p> 


       <h4 style="margin-bottom:3px">Comments</h4>
      <p style="font-size:10px">Enable / Disable Comments</p>
       
      
      <p>  
        <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'allow_comments' ); ?>" name="<?php echo $this->get_field_name( 'allow_comments' ); ?>" value="1" <?php echo ($instance['allow_comments'] == "true" ? "checked='checked'" : ""); ?> />
        <label for="<?php echo $this->get_field_id( 'allow_comments' ); ?>"><?php _e( 'Include Comments' ); ?></label>  
       </p>

       <p>  
        <label for="<?php echo $this->get_field_id( 'no_comments_text' ); ?>"><?php _e( 'Comments Text:' ); ?></label>  
        <input class="widefat" id="<?php echo $this->get_field_id( 'no_comments_text' ); ?>" name="<?php echo $this->get_field_name( 'no_comments_text' ); ?>" type="text" value="<?php echo esc_attr( $commentsText ); ?>" />  
       </p>

        <h4 style="margin-bottom:3px">Credits</h4>
      <p style="font-size:10px">Support us </p>
       
      
      <p>  
        <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'show_credits' ); ?>" name="<?php echo $this->get_field_name( 'show_credits' ); ?>" value="1" <?php if(empty($instance['show_credits'])){ echo "checked='checked'"; } ?><?php echo ($instance['show_credits'] == "true" ? "checked='checked'" : ""); ?> />
        <label for="<?php echo $this->get_field_id( 'show_credits' ); ?>"><?php _e( 'Enable Credits' ); ?></label>  
       </p>
       
<?php  
}  
}
?>