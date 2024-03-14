<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Utils;

?>



<?php
// Assuming the widget's unique identifier is $widget_id
$widget_id = 'wps_tab_' . $this->get_id();

echo '
<script>
  jQuery(document).ready(function($)
  {
    //put the js code under this line 
    var enableSlide = ' . ($settings['enable_slide'] ? 'true' : 'false') . ';

    if (enableSlide) {
      // Initialize Owl Carousel if slide is enabled
      $(".' . $widget_id . ' .project-carousel").owlCarousel({
        loop:true,
        margin:15,
        smartSpeed: 500,
        autoplay: true, // Autoplay is always true when slide is enabled
        navText: ["<span class=\'eicon-arrow-left\'></span>", "<span class=\'eicon-arrow-right\'></span>"],
        responsive:{
          0:{
            items:1
          },
          480:{
            items:1
          },
             600:{
            items:' . esc_js($settings['wps_columns_tab']) . '
          },
          800:{
            items:' . esc_js($settings['wps_columns_tab']) . '
          },
          1200:{
            items:' . esc_js($settings['wps_columns']) . '
          }
        }
      });           
    } else {
  
    }

    // Handle tab click events
    $(".' . $widget_id . ' .tab-navigation a").on("click", function(e) {
      e.preventDefault();

      // Remove active class from all tabs
      $(".' . $widget_id . ' .tab-navigation a").removeClass("active");

      // Add active class to the clicked tab
      $(this).addClass("active");

      // Hide all tab content panes
      $(".' . $widget_id . ' .tab-content .tab-pane").hide();

      // Get the target tab ID
      var targetTab = $(this).attr("href");

      // Show the selected tab content
      $(targetTab).show();
    });

    // Display content of the first tab and add active class
    $(".' . $widget_id . ' .tab-navigation a:first").addClass("active");
    $(".' . $widget_id . ' .tab-content .tab-pane:first").show();
  });
</script>';
?>


<section class="wps_project_tab_style_1 project-section wps_project_tab <?php echo esc_attr($widget_id); ?>">
  <div class="auto-container">
    <!-- Tab Navigation -->
    <div class="wps_tab_container">
      <ul class="tab-navigation wps_tab_ul">
        <?php $tab_counter = 1; ?>
        <?php foreach ($settings['repeater'] as $item) : ?>
          <li class="wps_tab_button"><a class="nav-link" href="#tab-<?php echo $tab_counter; ?>"><?php echo esc_attr($item['tab_block_title_one'], $allowed_tags); ?></a></li>
          <?php $tab_counter++; ?>
        <?php endforeach; ?>
      </ul>
    </div>
    <!-- Tab Content -->
    <div class="tab-content">
      <?php $tab_counter = 1; ?>
      <?php foreach ($settings['repeater'] as $item) : ?>
        <div id="tab-<?php echo $tab_counter; ?>" class="tab-pane">
          <div class="<?php echo $settings['enable_slide'] ? 'project-carousel owl-carousel slider_path owl-theme owl-dots owl-nav ' . esc_attr($widget_id) : 'row'; ?>">
			  
			  
			  
			  
<?php if ('yes' === $item['tab_settings_one']) : ?>			  
            <!-- item One -->
            <div class="project-block-one <?php echo $settings['enable_slide'] ? '' : $columns_markup_print; ?>">
			<div class="wp_project_block wps_overlay_style_1">	
				<div class="inner-box mr_block_bttn mr_btton_a wps_overlay_style_1">
                <figure class="image-box wps_image_box">
                  <?php if (wp_get_attachment_url($item['block_image_one']['id'])) : ?>
                    <img class="mr_product_thumb " src="<?php echo wp_get_attachment_url($item['block_image_one']['id']); ?>" alt="">
                  <?php else : ?>
                    <div class="noimage"></div>
                  <?php endif; ?>
                </figure>
                <div class="content-box">
                  <div class="wps_project_icon view-btn">
                    <?php if (!empty($settings['block_plus_icon']['value'])) : ?>
                      <a href="<?php echo wp_get_attachment_url($item['block_image_one']['id']); ?>" class="wps_project_plus_icon lightbox-image" data-fancybox="gallery">
                        <i class="<?php echo esc_attr($settings['block_plus_icon']['value']); ?>"></i>
                      </a>
                    <?php endif; ?>
                    <?php if (!empty($settings['block_link_icon']['value'])) : ?>
                      <a href="<?php echo esc_url($item['block_btnlink_x_one']['url']); ?>" class="wps_project_expand_icon wps_image_link ">
                        <i class="<?php echo esc_attr($settings['block_link_icon']['value']); ?>"></i>
                      </a>
                    <?php endif; ?>
                  </div>
                  <?php if ('style-1' === $settings['text_box_style']) : ?>
                <div class="text_outside_box">
                  <div class="text-box">
                    <?php if ('style-1' === $settings['text_title_style']) : ?>
                      <h3 class="mr_block_title mr_featured_block"><a href="<?php echo esc_url($item['block_btnlink_x_one']['url']); ?>"><?php echo wp_kses($item['block_title_one'], $allowed_tags); ?></a></h3>
                    <?php endif; ?>
                    <p class="mr_block_subtitle mr_featured_block_subtitle"><?php echo esc_attr($item['block_subtitle_one'], $allowed_tags); ?></p>
                    <?php if ('style-2' === $settings['text_title_style']) : ?>
                      <h3 class="mr_block_title mr_featured_block"><a href="<?php echo esc_url($item['block_btnlink_x_one']['url']); ?>"><?php echo wp_kses($item['block_title_one'], $allowed_tags); ?></a></h3>
                    <?php endif; ?>
                  </div>
                </div>
                  <?php endif; ?>
                </div>
              </div>
              <?php if ('style-2' === $settings['text_box_style']) : ?>
                <div class="text_outside_box">
                  <div class="text-box">
                    <?php if ('style-1' === $settings['text_title_style']) : ?>
                      <h3 class="mr_block_title mr_featured_block"><a href="<?php echo esc_url($item['block_btnlink_x_one']['url']); ?>"><?php echo wp_kses($item['block_title_one'], $allowed_tags); ?></a></h3>
                    <?php endif; ?>
                    <p class="mr_block_subtitle mr_featured_block_subtitle"><?php echo esc_attr($item['block_subtitle_one'], $allowed_tags); ?></p>
                    <?php if ('style-2' === $settings['text_title_style']) : ?>
                      <h3 class="mr_block_title mr_featured_block"><a href="<?php echo esc_url($item['block_btnlink_x_one']['url']); ?>"><?php echo wp_kses($item['block_title_one'], $allowed_tags); ?></a></h3>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endif; ?>	
			 </div>	
            </div>
			  
			  
<?php endif; ?>				  
            <!-- item two -->
<?php if ('yes' === $item['tab_settings_two']) : ?>			
  <div class="project-block-one <?php echo $settings['enable_slide'] ? '' : $columns_markup_print; ?>">
			<div class="wp_project_block wps_overlay_style_1">	
				<div class="inner-box mr_block_bttn mr_btton_a wps_overlay_style_1">
                <figure class="image-box wps_image_box">
                  <?php if (wp_get_attachment_url($item['block_image_two']['id'])) : ?>
                    <img class="mr_product_thumb " src="<?php echo wp_get_attachment_url($item['block_image_two']['id']); ?>" alt="">
                  <?php else : ?>
                    <div class="noimage"></div>
                  <?php endif; ?>
                </figure>
                <div class="content-box">
                  <div class="wps_project_icon view-btn">
                    <?php if (!empty($settings['block_plus_icon']['value'])) : ?>
                      <a href="<?php echo wp_get_attachment_url($item['block_image_two']['id']); ?>" class="wps_project_plus_icon lightbox-image" data-fancybox="gallery">
                        <i class="<?php echo esc_attr($settings['block_plus_icon']['value']); ?>"></i>
                      </a>
                    <?php endif; ?>
                    <?php if (!empty($settings['block_link_icon']['value'])) : ?>
                      <a href="<?php echo esc_url($item['block_btnlink_x_two']['url']); ?>" class="wps_project_expand_icon wps_image_link ">
                        <i class="<?php echo esc_attr($settings['block_link_icon']['value']); ?>"></i>
                      </a>
                    <?php endif; ?>
                  </div>
                  <?php if ('style-1' === $settings['text_box_style']) : ?>
                <div class="text_outside_box">
                  <div class="text-box">
                    <?php if ('style-1' === $settings['text_title_style']) : ?>
                      <h3 class="mr_block_title mr_featured_block"><a href="<?php echo esc_url($item['block_btnlink_x_two']['url']); ?>"><?php echo wp_kses($item['block_title_two'], $allowed_tags); ?></a></h3>
                    <?php endif; ?>
                    <p class="mr_block_subtitle mr_featured_block_subtitle"><?php echo esc_attr($item['block_subtitle_two'], $allowed_tags); ?></p>
                    <?php if ('style-2' === $settings['text_title_style']) : ?>
                      <h3 class="mr_block_title mr_featured_block"><a href="<?php echo esc_url($item['block_btnlink_x_two']['url']); ?>"><?php echo wp_kses($item['block_title_two'], $allowed_tags); ?></a></h3>
                    <?php endif; ?>
                  </div>
                </div>
                  <?php endif; ?>
                </div>
              </div>
              <?php if ('style-2' === $settings['text_box_style']) : ?>
                <div class="text_outside_box">
                  <div class="text-box">
                    <?php if ('style-1' === $settings['text_title_style']) : ?>
                      <h3 class="mr_block_title mr_featured_block"><a href="<?php echo esc_url($item['block_btnlink_x_two']['url']); ?>"><?php echo wp_kses($item['block_title_two'], $allowed_tags); ?></a></h3>
                    <?php endif; ?>
                    <p class="mr_block_subtitle mr_featured_block_subtitle"><?php echo esc_attr($item['block_subtitle_two'], $allowed_tags); ?></p>
                    <?php if ('style-2' === $settings['text_title_style']) : ?>
                      <h3 class="mr_block_title mr_featured_block"><a href="<?php echo esc_url($item['block_btnlink_x_two']['url']); ?>"><?php echo wp_kses($item['block_title_two'], $allowed_tags); ?></a></h3>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endif; ?>	
			 </div>	
            </div>
<?php endif; ?>				  
<!-- item Three -->			  
<?php if ('yes' === $item['tab_settings_three']) : ?>			
  <div class="project-block-one <?php echo $settings['enable_slide'] ? '' : $columns_markup_print; ?>">
			<div class="wp_project_block wps_overlay_style_1">	
				<div class="inner-box mr_block_bttn mr_btton_a wps_overlay_style_1">
                <figure class="image-box wps_image_box">
                  <?php if (wp_get_attachment_url($item['block_image_three']['id'])) : ?>
                    <img class="mr_product_thumb " src="<?php echo wp_get_attachment_url($item['block_image_three']['id']); ?>" alt="">
                  <?php else : ?>
                    <div class="noimage"></div>
                  <?php endif; ?>
                </figure>
                <div class="content-box">
                  <div class="wps_project_icon view-btn">
                    <?php if (!empty($settings['block_plus_icon']['value'])) : ?>
                      <a href="<?php echo wp_get_attachment_url($item['block_image_three']['id']); ?>" class="wps_project_plus_icon lightbox-image" data-fancybox="gallery">
                        <i class="<?php echo esc_attr($settings['block_plus_icon']['value']); ?>"></i>
                      </a>
                    <?php endif; ?>
                    <?php if (!empty($settings['block_link_icon']['value'])) : ?>
                      <a href="<?php echo esc_url($item['block_btnlink_x_three']['url']); ?>" class="wps_project_expand_icon wps_image_link ">
                        <i class="<?php echo esc_attr($settings['block_link_icon']['value']); ?>"></i>
                      </a>
                    <?php endif; ?>
                  </div>
                  <?php if ('style-1' === $settings['text_box_style']) : ?>
                <div class="text_outside_box">
                  <div class="text-box">
                    <?php if ('style-1' === $settings['text_title_style']) : ?>
                      <h3 class="mr_block_title mr_featured_block"><a href="<?php echo esc_url($item['block_btnlink_x_three']['url']); ?>"><?php echo wp_kses($item['block_title_three'], $allowed_tags); ?></a></h3>
                    <?php endif; ?>
                    <p class="mr_block_subtitle mr_featured_block_subtitle"><?php echo esc_attr($item['block_subtitle_three'], $allowed_tags); ?></p>
                    <?php if ('style-2' === $settings['text_title_style']) : ?>
                      <h3 class="mr_block_title mr_featured_block"><a href="<?php echo esc_url($item['block_btnlink_x_three']['url']); ?>"><?php echo wp_kses($item['block_title_three'], $allowed_tags); ?></a></h3>
                    <?php endif; ?>
                  </div>
                </div>
                  <?php endif; ?>
                </div>
              </div>
              <?php if ('style-2' === $settings['text_box_style']) : ?>
                <div class="text_outside_box">
                  <div class="text-box">
                    <?php if ('style-1' === $settings['text_title_style']) : ?>
                      <h3 class="mr_block_title mr_featured_block"><a href="<?php echo esc_url($item['block_btnlink_x_three']['url']); ?>"><?php echo wp_kses($item['block_title_three'], $allowed_tags); ?></a></h3>
                    <?php endif; ?>
                    <p class="mr_block_subtitle mr_featured_block_subtitle"><?php echo esc_attr($item['block_subtitle_three'], $allowed_tags); ?></p>
                    <?php if ('style-2' === $settings['text_title_style']) : ?>
                      <h3 class="mr_block_title mr_featured_block"><a href="<?php echo esc_url($item['block_btnlink_x_three']['url']); ?>"><?php echo wp_kses($item['block_title_three'], $allowed_tags); ?></a></h3>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endif; ?>	
			 </div>	
            </div> 
			  
<?php endif; ?>				  
<!-- item Four -->				  

 <?php if ('yes' === $item['tab_settings_four']) : ?>
			  
  <div class="project-block-one <?php echo $settings['enable_slide'] ? '' : $columns_markup_print; ?>">
			<div class="wp_project_block wps_overlay_style_1">	
				<div class="inner-box mr_block_bttn mr_btton_a wps_overlay_style_1">
                <figure class="image-box wps_image_box">
                  <?php if (wp_get_attachment_url($item['block_image_four']['id'])) : ?>
                    <img class="mr_product_thumb " src="<?php echo wp_get_attachment_url($item['block_image_four']['id']); ?>" alt="">
                  <?php else : ?>
                    <div class="noimage"></div>
                  <?php endif; ?>
                </figure>
                <div class="content-box">
                  <div class="wps_project_icon view-btn">
                    <?php if (!empty($settings['block_plus_icon']['value'])) : ?>
                      <a href="<?php echo wp_get_attachment_url($item['block_image_four']['id']); ?>" class="wps_project_plus_icon lightbox-image" data-fancybox="gallery">
                        <i class="<?php echo esc_attr($settings['block_plus_icon']['value']); ?>"></i>
                      </a>
                    <?php endif; ?>
                    <?php if (!empty($settings['block_link_icon']['value'])) : ?>
                      <a href="<?php echo esc_url($item['block_btnlink_x_four']['url']); ?>" class="wps_project_expand_icon wps_image_link ">
                        <i class="<?php echo esc_attr($settings['block_link_icon']['value']); ?>"></i>
                      </a>
                    <?php endif; ?>
                  </div>
                  <?php if ('style-1' === $settings['text_box_style']) : ?>
                <div class="text_outside_box">
                  <div class="text-box">
                    <?php if ('style-1' === $settings['text_title_style']) : ?>
                      <h3 class="mr_block_title mr_featured_block"><a href="<?php echo esc_url($item['block_btnlink_x_four']['url']); ?>"><?php echo wp_kses($item['block_title_four'], $allowed_tags); ?></a></h3>
                    <?php endif; ?>
                    <p class="mr_block_subtitle mr_featured_block_subtitle"><?php echo esc_attr($item['block_subtitle_four'], $allowed_tags); ?></p>
                    <?php if ('style-2' === $settings['text_title_style']) : ?>
                      <h3 class="mr_block_title mr_featured_block"><a href="<?php echo esc_url($item['block_btnlink_x_four']['url']); ?>"><?php echo wp_kses($item['block_title_four'], $allowed_tags); ?></a></h3>
                    <?php endif; ?>
                  </div>
                </div>
                  <?php endif; ?>
                </div>
              </div>
              <?php if ('style-2' === $settings['text_box_style']) : ?>
                <div class="text_outside_box">
                  <div class="text-box">
                    <?php if ('style-1' === $settings['text_title_style']) : ?>
                      <h3 class="mr_block_title mr_featured_block"><a href="<?php echo esc_url($item['block_btnlink_x_four']['url']); ?>"><?php echo wp_kses($item['block_title_four'], $allowed_tags); ?></a></h3>
                    <?php endif; ?>
                    <p class="mr_block_subtitle mr_featured_block_subtitle"><?php echo esc_attr($item['block_subtitle_four'], $allowed_tags); ?></p>
                    <?php if ('style-2' === $settings['text_title_style']) : ?>
                      <h3 class="mr_block_title mr_featured_block"><a href="<?php echo esc_url($item['block_btnlink_x_four']['url']); ?>"><?php echo wp_kses($item['block_title_four'], $allowed_tags); ?></a></h3>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endif; ?>	
			 </div>	
            </div> 
<?php endif; ?>				  
<!-- item Five -->				  
<?php if ('yes' === $item['tab_settings_five']) : ?>
  <div class="project-block-one <?php echo $settings['enable_slide'] ? '' : $columns_markup_print; ?>">
			<div class="wp_project_block wps_overlay_style_1">	
				<div class="inner-box mr_block_bttn mr_btton_a wps_overlay_style_1">
                <figure class="image-box wps_image_box">
                  <?php if (wp_get_attachment_url($item['block_image_five']['id'])) : ?>
                    <img class="mr_product_thumb " src="<?php echo wp_get_attachment_url($item['block_image_five']['id']); ?>" alt="">
                  <?php else : ?>
                    <div class="noimage"></div>
                  <?php endif; ?>
                </figure>
                <div class="content-box">
                  <div class="wps_project_icon view-btn">
                    <?php if (!empty($settings['block_plus_icon']['value'])) : ?>
                      <a href="<?php echo wp_get_attachment_url($item['block_image_five']['id']); ?>" class="wps_project_plus_icon lightbox-image" data-fancybox="gallery">
                        <i class="<?php echo esc_attr($settings['block_plus_icon']['value']); ?>"></i>
                      </a>
                    <?php endif; ?>
                    <?php if (!empty($settings['block_link_icon']['value'])) : ?>
                      <a href="<?php echo esc_url($item['block_btnlink_x_five']['url']); ?>" class="wps_project_expand_icon wps_image_link ">
                        <i class="<?php echo esc_attr($settings['block_link_icon']['value']); ?>"></i>
                      </a>
                    <?php endif; ?>
                  </div>
                  <?php if ('style-1' === $settings['text_box_style']) : ?>
                <div class="text_outside_box">
                  <div class="text-box">
                    <?php if ('style-1' === $settings['text_title_style']) : ?>
                      <h3 class="mr_block_title mr_featured_block"><a href="<?php echo esc_url($item['block_btnlink_x_five']['url']); ?>"><?php echo wp_kses($item['block_title_five'], $allowed_tags); ?></a></h3>
                    <?php endif; ?>
                    <p class="mr_block_subtitle mr_featured_block_subtitle"><?php echo esc_attr($item['block_subtitle_five'], $allowed_tags); ?></p>
                    <?php if ('style-2' === $settings['text_title_style']) : ?>
                      <h3 class="mr_block_title mr_featured_block"><a href="<?php echo esc_url($item['block_btnlink_x_five']['url']); ?>"><?php echo wp_kses($item['block_title_five'], $allowed_tags); ?></a></h3>
                    <?php endif; ?>
                  </div>
                </div>
                  <?php endif; ?>
                </div>
              </div>
              <?php if ('style-2' === $settings['text_box_style']) : ?>
                <div class="text_outside_box">
                  <div class="text-box">
                    <?php if ('style-1' === $settings['text_title_style']) : ?>
                      <h3 class="mr_block_title mr_featured_block"><a href="<?php echo esc_url($item['block_btnlink_x_five']['url']); ?>"><?php echo wp_kses($item['block_title_five'], $allowed_tags); ?></a></h3>
                    <?php endif; ?>
                    <p class="mr_block_subtitle mr_featured_block_subtitle"><?php echo esc_attr($item['block_subtitle_five'], $allowed_tags); ?></p>
                    <?php if ('style-2' === $settings['text_title_style']) : ?>
                      <h3 class="mr_block_title mr_featured_block"><a href="<?php echo esc_url($item['block_btnlink_x_five']['url']); ?>"><?php echo wp_kses($item['block_title_five'], $allowed_tags); ?></a></h3>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endif; ?>	
			 </div>	
            </div> 
<?php endif; ?>				  
<!-- item Six -->				  
			  
<?php if ('yes' === $item['tab_settings_six']) : ?>
  <div class="project-block-one <?php echo $settings['enable_slide'] ? '' : $columns_markup_print; ?>">
			<div class="wp_project_block wps_overlay_style_1">	
				<div class="inner-box mr_block_bttn mr_btton_a wps_overlay_style_1">
                <figure class="image-box wps_image_box">
                  <?php if (wp_get_attachment_url($item['block_image_six']['id'])) : ?>
                    <img class="mr_product_thumb " src="<?php echo wp_get_attachment_url($item['block_image_six']['id']); ?>" alt="">
                  <?php else : ?>
                    <div class="noimage"></div>
                  <?php endif; ?>
                </figure>
                <div class="content-box">
                  <div class="wps_project_icon view-btn">
                    <?php if (!empty($settings['block_plus_icon']['value'])) : ?>
                      <a href="<?php echo wp_get_attachment_url($item['block_image_six']['id']); ?>" class="wps_project_plus_icon lightbox-image" data-fancybox="gallery">
                        <i class="<?php echo esc_attr($settings['block_plus_icon']['value']); ?>"></i>
                      </a>
                    <?php endif; ?>
                    <?php if (!empty($settings['block_link_icon']['value'])) : ?>
                      <a href="<?php echo esc_url($item['block_btnlink_x_six']['url']); ?>" class="wps_project_expand_icon wps_image_link ">
                        <i class="<?php echo esc_attr($settings['block_link_icon']['value']); ?>"></i>
                      </a>
                    <?php endif; ?>
                  </div>
                  <?php if ('style-1' === $settings['text_box_style']) : ?>
                <div class="text_outside_box">
                  <div class="text-box">
                    <?php if ('style-1' === $settings['text_title_style']) : ?>
                      <h3 class="mr_block_title mr_featured_block"><a href="<?php echo esc_url($item['block_btnlink_x_six']['url']); ?>"><?php echo wp_kses($item['block_title_six'], $allowed_tags); ?></a></h3>
                    <?php endif; ?>
                    <p class="mr_block_subtitle mr_featured_block_subtitle"><?php echo esc_attr($item['block_subtitle_six'], $allowed_tags); ?></p>
                    <?php if ('style-2' === $settings['text_title_style']) : ?>
                      <h3 class="mr_block_title mr_featured_block"><a href="<?php echo esc_url($item['block_btnlink_x_six']['url']); ?>"><?php echo wp_kses($item['block_title_six'], $allowed_tags); ?></a></h3>
                    <?php endif; ?>
                  </div>
                </div>
                  <?php endif; ?>
                </div>
              </div>
              <?php if ('style-2' === $settings['text_box_style']) : ?>
                <div class="text_outside_box">
                  <div class="text-box">
                    <?php if ('style-1' === $settings['text_title_style']) : ?>
                      <h3 class="mr_block_title mr_featured_block"><a href="<?php echo esc_url($item['block_btnlink_x_six']['url']); ?>"><?php echo wp_kses($item['block_title_six'], $allowed_tags); ?></a></h3>
                    <?php endif; ?>
                    <p class="mr_block_subtitle mr_featured_block_subtitle"><?php echo esc_attr($item['block_subtitle_six'], $allowed_tags); ?></p>
                    <?php if ('style-2' === $settings['text_title_style']) : ?>
                      <h3 class="mr_block_title mr_featured_block"><a href="<?php echo esc_url($item['block_btnlink_x_six']['url']); ?>"><?php echo wp_kses($item['block_title_six'], $allowed_tags); ?></a></h3>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endif; ?>	
			 </div>	
            </div>
<?php endif; ?>				  
<!-- item Seven -->				  
<?php if ('yes' === $item['tab_settings_seven']) : ?>
  <div class="project-block-one <?php echo $settings['enable_slide'] ? '' : $columns_markup_print; ?>">
			<div class="wp_project_block wps_overlay_style_1">	
				<div class="inner-box mr_block_bttn mr_btton_a wps_overlay_style_1">
                <figure class="image-box wps_image_box">
                  <?php if (wp_get_attachment_url($item['block_image_seven']['id'])) : ?>
                    <img class="mr_product_thumb " src="<?php echo wp_get_attachment_url($item['block_image_seven']['id']); ?>" alt="">
                  <?php else : ?>
                    <div class="noimage"></div>
                  <?php endif; ?>
                </figure>
                <div class="content-box">
                  <div class="wps_project_icon view-btn">
                    <?php if (!empty($settings['block_plus_icon']['value'])) : ?>
                      <a href="<?php echo wp_get_attachment_url($item['block_image_seven']['id']); ?>" class="wps_project_plus_icon lightbox-image" data-fancybox="gallery">
                        <i class="<?php echo esc_attr($settings['block_plus_icon']['value']); ?>"></i>
                      </a>
                    <?php endif; ?>
                    <?php if (!empty($settings['block_link_icon']['value'])) : ?>
                      <a href="<?php echo esc_url($item['block_btnlink_x_seven']['url']); ?>" class="wps_project_expand_icon wps_image_link ">
                        <i class="<?php echo esc_attr($settings['block_link_icon']['value']); ?>"></i>
                      </a>
                    <?php endif; ?>
                  </div>
                  <?php if ('style-1' === $settings['text_box_style']) : ?>
                <div class="text_outside_box">
                  <div class="text-box">
                    <?php if ('style-1' === $settings['text_title_style']) : ?>
                      <h3 class="mr_block_title mr_featured_block"><a href="<?php echo esc_url($item['block_btnlink_x_seven']['url']); ?>"><?php echo wp_kses($item['block_title_seven'], $allowed_tags); ?></a></h3>
                    <?php endif; ?>
                    <p class="mr_block_subtitle mr_featured_block_subtitle"><?php echo esc_attr($item['block_subtitle_seven'], $allowed_tags); ?></p>
                    <?php if ('style-2' === $settings['text_title_style']) : ?>
                      <h3 class="mr_block_title mr_featured_block"><a href="<?php echo esc_url($item['block_btnlink_x_seven']['url']); ?>"><?php echo wp_kses($item['block_title_seven'], $allowed_tags); ?></a></h3>
                    <?php endif; ?>
                  </div>
                </div>
                  <?php endif; ?>
                </div>
              </div>
              <?php if ('style-2' === $settings['text_box_style']) : ?>
                <div class="text_outside_box">
                  <div class="text-box">
                    <?php if ('style-1' === $settings['text_title_style']) : ?>
                      <h3 class="mr_block_title mr_featured_block"><a href="<?php echo esc_url($item['block_btnlink_x_seven']['url']); ?>"><?php echo wp_kses($item['block_title_seven'], $allowed_tags); ?></a></h3>
                    <?php endif; ?>
                    <p class="mr_block_subtitle mr_featured_block_subtitle"><?php echo esc_attr($item['block_subtitle_seven'], $allowed_tags); ?></p>
                    <?php if ('style-2' === $settings['text_title_style']) : ?>
                      <h3 class="mr_block_title mr_featured_block"><a href="<?php echo esc_url($item['block_btnlink_x_seven']['url']); ?>"><?php echo wp_kses($item['block_title_seven'], $allowed_tags); ?></a></h3>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endif; ?>	
			 </div>	
            </div>
<?php endif; ?>				  
<!-- item Eight -->				  
<?php if ('yes' === $item['tab_settings_eight']) : ?>
  <div class="project-block-one <?php echo $settings['enable_slide'] ? '' : $columns_markup_print; ?>">
			<div class="wp_project_block wps_overlay_style_1">	
				<div class="inner-box mr_block_bttn mr_btton_a wps_overlay_style_1">
                <figure class="image-box wps_image_box">
                  <?php if (wp_get_attachment_url($item['block_image_eight']['id'])) : ?>
                    <img class="mr_product_thumb " src="<?php echo wp_get_attachment_url($item['block_image_eight']['id']); ?>" alt="">
                  <?php else : ?>
                    <div class="noimage"></div>
                  <?php endif; ?>
                </figure>
                <div class="content-box">
                  <div class="wps_project_icon view-btn">
                    <?php if (!empty($settings['block_plus_icon']['value'])) : ?>
                      <a href="<?php echo wp_get_attachment_url($item['block_image_eight']['id']); ?>" class="wps_project_plus_icon lightbox-image" data-fancybox="gallery">
                        <i class="<?php echo esc_attr($settings['block_plus_icon']['value']); ?>"></i>
                      </a>
                    <?php endif; ?>
                    <?php if (!empty($settings['block_link_icon']['value'])) : ?>
                      <a href="<?php echo esc_url($item['block_btnlink_x_eight']['url']); ?>" class="wps_project_expand_icon wps_image_link ">
                        <i class="<?php echo esc_attr($settings['block_link_icon']['value']); ?>"></i>
                      </a>
                    <?php endif; ?>
                  </div>
                  <?php if ('style-1' === $settings['text_box_style']) : ?>
                <div class="text_outside_box">
                  <div class="text-box">
                    <?php if ('style-1' === $settings['text_title_style']) : ?>
                      <h3 class="mr_block_title mr_featured_block"><a href="<?php echo esc_url($item['block_btnlink_x_eight']['url']); ?>"><?php echo wp_kses($item['block_title_eight'], $allowed_tags); ?></a></h3>
                    <?php endif; ?>
                    <p class="mr_block_subtitle mr_featured_block_subtitle"><?php echo esc_attr($item['block_subtitle_eight'], $allowed_tags); ?></p>
                    <?php if ('style-2' === $settings['text_title_style']) : ?>
                      <h3 class="mr_block_title mr_featured_block"><a href="<?php echo esc_url($item['block_btnlink_x_eight']['url']); ?>"><?php echo wp_kses($item['block_title_eight'], $allowed_tags); ?></a></h3>
                    <?php endif; ?>
                  </div>
                </div>
                  <?php endif; ?>
                </div>
              </div>
              <?php if ('style-2' === $settings['text_box_style']) : ?>
                <div class="text_outside_box">
                  <div class="text-box">
                    <?php if ('style-1' === $settings['text_title_style']) : ?>
                      <h3 class="mr_block_title mr_featured_block"><a href="<?php echo esc_url($item['block_btnlink_x_eight']['url']); ?>"><?php echo wp_kses($item['block_title_eight'], $allowed_tags); ?></a></h3>
                    <?php endif; ?>
                    <p class="mr_block_subtitle mr_featured_block_subtitle"><?php echo esc_attr($item['block_subtitle_eight'], $allowed_tags); ?></p>
                    <?php if ('style-2' === $settings['text_title_style']) : ?>
                      <h3 class="mr_block_title mr_featured_block"><a href="<?php echo esc_url($item['block_btnlink_x_eight']['url']); ?>"><?php echo wp_kses($item['block_title_eight'], $allowed_tags); ?></a></h3>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endif; ?>	
			 </div>	
            </div>			  
<?php endif; ?>				  
<!-- item Nine -->		
<?php if ('yes' === $item['tab_settings_nine']) : ?>
  <div class="project-block-one <?php echo $settings['enable_slide'] ? '' : $columns_markup_print; ?>">
			<div class="wp_project_block wps_overlay_style_1">	
				<div class="inner-box mr_block_bttn mr_btton_a wps_overlay_style_1">
                <figure class="image-box wps_image_box">
                  <?php if (wp_get_attachment_url($item['block_image_nine']['id'])) : ?>
                    <img class="mr_product_thumb " src="<?php echo wp_get_attachment_url($item['block_image_nine']['id']); ?>" alt="">
                  <?php else : ?>
                    <div class="noimage"></div>
                  <?php endif; ?>
                </figure>
                <div class="content-box">
                  <div class="wps_project_icon view-btn">
                    <?php if (!empty($settings['block_plus_icon']['value'])) : ?>
                      <a href="<?php echo wp_get_attachment_url($item['block_image_nine']['id']); ?>" class="wps_project_plus_icon lightbox-image" data-fancybox="gallery">
                        <i class="<?php echo esc_attr($settings['block_plus_icon']['value']); ?>"></i>
                      </a>
                    <?php endif; ?>
                    <?php if (!empty($settings['block_link_icon']['value'])) : ?>
                      <a href="<?php echo esc_url($item['block_btnlink_x_nine']['url']); ?>" class="wps_project_expand_icon wps_image_link ">
                        <i class="<?php echo esc_attr($settings['block_link_icon']['value']); ?>"></i>
                      </a>
                    <?php endif; ?>
                  </div>
                  <?php if ('style-1' === $settings['text_box_style']) : ?>
                <div class="text_outside_box">
                  <div class="text-box">
                    <?php if ('style-1' === $settings['text_title_style']) : ?>
                      <h3 class="mr_block_title mr_featured_block"><a href="<?php echo esc_url($item['block_btnlink_x_nine']['url']); ?>"><?php echo wp_kses($item['block_title_nine'], $allowed_tags); ?></a></h3>
                    <?php endif; ?>
                    <p class="mr_block_subtitle mr_featured_block_subtitle"><?php echo esc_attr($item['block_subtitle_nine'], $allowed_tags); ?></p>
                    <?php if ('style-2' === $settings['text_title_style']) : ?>
                      <h3 class="mr_block_title mr_featured_block"><a href="<?php echo esc_url($item['block_btnlink_x_nine']['url']); ?>"><?php echo wp_kses($item['block_title_nine'], $allowed_tags); ?></a></h3>
                    <?php endif; ?>
                  </div>
                </div>
                  <?php endif; ?>
                </div>
              </div>
              <?php if ('style-2' === $settings['text_box_style']) : ?>
                <div class="text_outside_box">
                  <div class="text-box">
                    <?php if ('style-1' === $settings['text_title_style']) : ?>
                      <h3 class="mr_block_title mr_featured_block"><a href="<?php echo esc_url($item['block_btnlink_x_nine']['url']); ?>"><?php echo wp_kses($item['block_title_nine'], $allowed_tags); ?></a></h3>
                    <?php endif; ?>
                    <p class="mr_block_subtitle mr_featured_block_subtitle"><?php echo esc_attr($item['block_subtitle_nine'], $allowed_tags); ?></p>
                    <?php if ('style-2' === $settings['text_title_style']) : ?>
                      <h3 class="mr_block_title mr_featured_block"><a href="<?php echo esc_url($item['block_btnlink_x_nine']['url']); ?>"><?php echo wp_kses($item['block_title_nine'], $allowed_tags); ?></a></h3>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endif; ?>	
			 </div>	
            </div>		  
<?php endif; ?>				  
<!-- item TEn -->
		  
 <?php if ('yes' === $item['tab_settings_ten']) : ?>
			  					  
  <div class="project-block-one <?php echo $settings['enable_slide'] ? '' : $columns_markup_print; ?>">
			<div class="wp_project_block wps_overlay_style_1">	
				<div class="inner-box mr_block_bttn mr_btton_a wps_overlay_style_1">
                <figure class="image-box wps_image_box">
                  <?php if (wp_get_attachment_url($item['block_image_ten']['id'])) : ?>
                    <img class="mr_product_thumb " src="<?php echo wp_get_attachment_url($item['block_image_ten']['id']); ?>" alt="">
                  <?php else : ?>
                    <div class="noimage"></div>
                  <?php endif; ?>
                </figure>
                <div class="content-box">
                  <div class="wps_project_icon view-btn">
                    <?php if (!empty($settings['block_plus_icon']['value'])) : ?>
                      <a href="<?php echo wp_get_attachment_url($item['block_image_ten']['id']); ?>" class="wps_project_plus_icon lightbox-image" data-fancybox="gallery">
                        <i class="<?php echo esc_attr($settings['block_plus_icon']['value']); ?>"></i>
                      </a>
                    <?php endif; ?>
                    <?php if (!empty($settings['block_link_icon']['value'])) : ?>
                      <a href="<?php echo esc_url($item['block_btnlink_x_ten']['url']); ?>" class="wps_project_expand_icon wps_image_link ">
                        <i class="<?php echo esc_attr($settings['block_link_icon']['value']); ?>"></i>
                      </a>
                    <?php endif; ?>
                  </div>
                  <?php if ('style-1' === $settings['text_box_style']) : ?>
                <div class="text_outside_box">
                  <div class="text-box">
                    <?php if ('style-1' === $settings['text_title_style']) : ?>
                      <h3 class="mr_block_title mr_featured_block"><a href="<?php echo esc_url($item['block_btnlink_x_ten']['url']); ?>"><?php echo wp_kses($item['block_title_ten'], $allowed_tags); ?></a></h3>
                    <?php endif; ?>
                    <p class="mr_block_subtitle mr_featured_block_subtitle"><?php echo esc_attr($item['block_subtitle_ten'], $allowed_tags); ?></p>
                    <?php if ('style-2' === $settings['text_title_style']) : ?>
                      <h3 class="mr_block_title mr_featured_block"><a href="<?php echo esc_url($item['block_btnlink_x_ten']['url']); ?>"><?php echo wp_kses($item['block_title_ten'], $allowed_tags); ?></a></h3>
                    <?php endif; ?>
                  </div>
                </div>
                  <?php endif; ?>
                </div>
              </div>
              <?php if ('style-2' === $settings['text_box_style']) : ?>
                <div class="text_outside_box">
                  <div class="text-box">
                    <?php if ('style-1' === $settings['text_title_style']) : ?>
                      <h3 class="mr_block_title mr_featured_block"><a href="<?php echo esc_url($item['block_btnlink_x_ten']['url']); ?>"><?php echo wp_kses($item['block_title_ten'], $allowed_tags); ?></a></h3>
                    <?php endif; ?>
                    <p class="mr_block_subtitle mr_featured_block_subtitle"><?php echo esc_attr($item['block_subtitle_ten'], $allowed_tags); ?></p>
                    <?php if ('style-2' === $settings['text_title_style']) : ?>
                      <h3 class="mr_block_title mr_featured_block"><a href="<?php echo esc_url($item['block_btnlink_x_ten']['url']); ?>"><?php echo wp_kses($item['block_title_ten'], $allowed_tags); ?></a></h3>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endif; ?>	
			 </div>	
            </div>	
			  
<?php endif; ?>				  
			  
			  
			  
			  
          </div>
        </div>
        <?php $tab_counter++; ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>
