
<?php
// Retrieve the selected background color option from the control
$thumbnail_bg_option = $this->get_settings('wps_thumbnial_bgcolor_select');

// Retrieve the thumbnail background color from post meta based on the selected option
$thumbnail_bg_color = '';

if ($thumbnail_bg_option === 'bg_meta') {
    $thumbnail_bg_color = get_post_meta(get_the_ID(), 'thumbnail_bg_color', true);
} elseif ($thumbnail_bg_option === 'bg_elementor') {
    // Add logic to retrieve background color from Elementor settings if needed
    // Example: $thumbnail_bg_color = get_elementor_setting('thumbnail_bg_color');
}

// Get the default background color from settings if available
$default_bg_color = isset($settings['wps_thumbnail_bg']) ? $settings['wps_thumbnail_bg'] : '';

// Generate inline style based on the selected color or default
$inlineStyle = $thumbnail_bg_color ? sprintf('style="background-color: %s;"', esc_attr($thumbnail_bg_color)) : sprintf('style="background-color: %s;"', esc_attr($default_bg_color));
?>




<?php if ($thumbnail_style === 'style-2') { ?> 
 <div class="mr_product_thumb product_image">             
   <figure class="image-box" <?php echo $inlineStyle; ?>>
      <a href="<?php echo esc_url(get_the_permalink(get_the_ID())); ?>">
          <img src="<?php echo esc_url(wp_get_attachment_url($meta_image['id'])); ?>" alt="">
      </a>
  </figure>
</div>   
<?php } elseif  ($thumbnail_style === 'style-3') { ?> 

 <div class="flip-box mr_product_thumb product_image">
        <div class="flip-box-inner" <?php echo $inlineStyle; ?>>
          <div class="flip-box-front">
           <img src="<?php echo esc_url(wp_get_attachment_url($meta_image['id'])); ?>" alt="" style="width:100%;height:100%">
          </div>
          <div class="flip-box-back">
            <img src="<?php echo esc_url(wp_get_attachment_url($meta_image_two['id'])); ?>" alt="" style="width:100%;height:100%">
          </div>
        </div>
    </div>
              
              
  
 <?php }  elseif  ($thumbnail_style === 'style-5') { ?> 

<div class="product_block_one mr_product_thumb product_image">
    <div class="product-inner">
        <div class="product_image" <?php echo $inlineStyle; ?>>
        <?php   
            $images = $repeater_images;

            if ($images && isset($images['select_image_media'])) {
                $image_urls = array();
                $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail'); // Get the product thumbnail URL

                foreach ($images['select_image_media'] as $key => $image_set) {
                    $image_url = isset($image_set['url']) ? $image_set['url'] : '';
                    $first_image_url = isset($images['select_image_media'][0]['url']) ? $images['select_image_media'][0]['url'] : '';

         
                    if ($image_url && $key !== 0) {
                        $image_urls[] = esc_url($image_url);
                    }
                }

                $image_hover_slides = implode(',', $image_urls);

              
                if (!empty($image_hover_slides)) { ?>
                    <img src="<?php echo esc_url($first_image_url); ?>" data-hover-slides="<?php echo esc_url($image_hover_slides); ?>" data-options="{&quot;touch&quot;: &quot;end&quot; }">
                <?php }
            }
        ?>
        </div>
    </div>
</div>


<?php } else  { ?> 
<div class="product_block_one mr_product_thumb product_image">              
<figure class="image-box" <?php echo $inlineStyle; ?> >
        <?php echo the_post_thumbnail();?>  
    </figure>
 </div> 
<?php }   ?>
