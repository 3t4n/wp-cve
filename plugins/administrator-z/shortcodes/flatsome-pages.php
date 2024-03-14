<?php 
/* 
__DIR__ . '
 get_template_directory().'/inc/builder/shortcodes
*/
use Adminz\Admin\Adminz as Adminz;


add_action('ux_builder_setup', function(){
  if ( ! isset( $repeater_type)) $repeater_type        = 'row';
  if ( ! isset( $default_text_align)) $default_text_align   = 'left';
  if ( ! isset( $repeater_col_spacing)) $repeater_col_spacing = 'normal';
  if ( ! isset( $repeater_post_type ) ) $repeater_post_type = 'page';
  if ( ! isset( $repeater_columns)) $repeater_columns = '4';

  $options = array(
    'pages_options'   => array(
      'type'    => 'group',
      'heading' => __( 'Options','administrator-z'),
      'options' => array(
        'style'   => array(
          'type'    => 'select',
          'heading' => __( 'Style','administrator-z'),
          'default' => 'normal',
          'options' => require(get_template_directory().'/inc/builder/shortcodes/values/box-layouts.php'),
        ),
        'parent'  => array(
          'type'    => 'select',
          'heading' => 'Parent',
          'default' => '',
          'options' => ux_builder_get_page_parents(),
        ),
        'multiple_level'  => array(
          'type'    => 'checkbox',
          'heading' => 'Multiple level',
          'default' => 'true',
        ),
        'orderby' => array(
          'type'    => 'select',
          'heading' => __( 'Order By','administrator-z'),
          'default' => 'menu_order',
          'options' => array(
            'post_title'    => 'Title',
            'post_date'     => 'Date',
            'menu_order'    => 'Menu Order',
            'post_modified' => 'Last Modified',
          ),
        ),
        'order'   => array(
          'type'    => 'select',
          'heading' => __( 'Order','administrator-z'),
          'default' => 'asc',
          'options' => array(
            'asc'  => 'ASC',
            'desc' => 'DESC',
          ),
        ),
      ),
    ),
    'post_options' =>array(
      'type' => 'group',
      'heading' => __( 'Custom ','administrator-z'),
      'options' => array(

       'ids' => array(
          'type' => 'select',
          'heading' => 'Custom Pages',
          'param_name' => 'ids',
          'config' => array(
              'multiple' => true,
              'placeholder' => 'Select..',
              'postSelect' => array(
                  'post_type' => array($repeater_post_type)
              ),
            )
          ),
         'heading_tag'   => array(
            'type'    => 'select',
            'heading' => __( 'Heading Tag','administrator-z'),
            'default' => 'p',
            'options' => array(
              'p'  => 'p',
              'div'  => 'div',
              'h1' => 'h1',
              'h2' => 'h2',
              'h3' => 'h3',
              'h4' => 'h4',
              'h5' => 'h5',
              'h6' => 'h6',
            ),
          ),
        ),
    ),    
    'layout_options'        => require(get_template_directory().'/inc/builder/shortcodes/commons/repeater-options.php' ),
    'post_title_options' => array(
        'type' => 'group',
        'heading' => __( 'Title','administrator-z'),
            'options' => array(
                'title_size' => array(
                    'type' => 'select',
                    'heading' => 'Title Size',
                    'default' => '',
                    'options' => require( get_template_directory().'/inc/builder/shortcodes/values/sizes.php' )
                ),
                'title_style' => array(
                    'type' => 'radio-buttons',
                    'heading' => 'Title Style',
                    'default' => '',
                    'options' => array(
                        ''   => array( 'title' => 'Abc'),
                        'uppercase' => array( 'title' => 'ABC'),
                    )
            ),
        )
    ),
    'post_meta_options' => array(
      'type' => 'group',
      'heading' => __( 'Meta','administrator-z'),
      'options' => array(
        'excerpt' => array(
            'type' => 'select',
            'heading' => 'Excerpt',
            'default' => 'visible',
            'options' => array(
                'visible' => 'Visible',
                'fade' => 'Fade In On Hover',
                'slide' => 'Slide In On Hover',
                'reveal' => 'Reveal On Hover',
                'false' => 'Hidden',
            )
        ),
       'excerpt_length' => array(
            'type' => 'slider',
            'heading' => 'Excerpt Length',
            'default' => 15,
            'max' => 50,
            'min' => 5,
        ),
      )
    ),
    'read_more_button' => array(
        'type' => 'group',
        'heading' => __( 'Read More','administrator-z'),
            'options' => array(
                'readmore' => array(
                    'type' => 'textfield',
                    'heading' => 'Text',
                    'default' => '',
                ),
                'readmore_color' => array(
                'type' => 'select',
                'heading' => 'Color',
                'conditions' => 'readmore',
                'default' => '',
                'options' => array(
                    '' => 'Default',
                    'primary' => 'Primary',
                    'secondary' => 'Secondary',
                    'alert' => 'Alert',
                    'success' => 'Success',
                    'white' => 'White',
                )
            ),
            'readmore_style' => array(
                'type' => 'select',
                'heading' => 'Style',
                'conditions' => 'readmore',
                'default' => 'outline',
                'options' => array(
                    '' => 'Default',
                    'outline' => 'Outline',
                    'link' => 'Simple',
                    'underline' => 'Underline',
                    'shade' => 'Shade',
                    'bevel' => 'Bevel',
                    'gloss' => 'Gloss',
                )
            ),
            'readmore_size' => array(
                'type' => 'select',
                'conditions' => 'readmore',
                'heading' => 'Size',
                'default' => '',
                'options' => require( get_template_directory().'/inc/builder/shortcodes/values/sizes.php' ),
            ),
        )
    ),

    'layout_options_slider' => require(get_template_directory().'/inc/builder/shortcodes/commons/repeater-slider.php' ),
  );

  $box_styles = require(get_template_directory().'/inc/builder/shortcodes/commons/box-styles.php' );
  $options    = array_merge( $options, $box_styles );

  $advanced = array('advanced_options' => require(get_template_directory().'/inc/builder/shortcodes/commons/advanced.php'));
  $options = array_merge($options, $advanced);

  add_ux_builder_shortcode( 'adminz_ux_pages',
    array(
      'name'      => __( 'Custom pages', 'administrator-z' ),
      'category'  => Adminz::get_adminz_menu_title(),
      'thumbnail' => get_template_directory_uri() . '/inc/builder/shortcodes/'."thumbnails/pages.svg",
      'scripts'   => array(
        'flatsome-masonry-js' => get_template_directory_uri() . '/assets/libs/packery.pkgd.min.js',
      ),
      'presets'   => array(
        array(
          'name'    => __( 'Default','administrator-z'),
          'content' => '[adminz_ux_pages]',
        ),
      ),
      'options'   => $options,
    )
  );
});























function adminz_ux_pages($atts) {
    extract(shortcode_atts(array(
      // meta
      '_id' => 'pages-'.rand(),
      'class' => '',
      'visibility' => '',
      'parent' => '',
      'orderby' => 'menu_order',
      'order' => 'asc',
      'ids' => false,
      'heading_tag'=> 'p',
      'target' => '',
      'multiple_level'=> "true",

      // Layout
      'style' => '',
      'columns' => '4',
      'columns__md' => '',
      'columns__sm' => '',
      'col_spacing' => '',
      'type' => 'row', // slider, row, masonery, grid
      'width' => '',
      'grid' => '1',
      'grid_height' => '600px',
      'grid_height__md' => '500px',
      'grid_height__sm' => '400px',
      'slider_nav_style' => 'reveal',
      'slider_nav_position' => '',
      'slider_nav_color' => '',
      'slider_bullets' => 'false',
      'slider_arrows' => 'true',
      'auto_slide' => 'false',
      'infinitive' => 'true',
      'depth' => '',
      'depth_hover' => '',

      // Read more
      'readmore' => '',
      'readmore_color' => '',
      'readmore_style' => 'outline',
      'readmore_size' => 'small',

      //Title
      'title_size' => '',
      'title_style' => '',

      //excerpt
      'excerpt' => 'visible',
      'excerpt_length' => 15,

       // Box styles
      'animate' => '',
      'text_pos' => '',
      'text_padding' => '',
      'text_bg' => '',
      'text_color' => '',
      'text_hover' => '',
      'text_align' => 'left',
      'text_size' => '',
      'image_size' => 'medium',
      'image_mask' => '',
      'image_width' => '',
      'image_height' => '',
      'image_radius' => '',
      'image_hover' => '',
      'image_hover_alt' => '',
      'image_overlay' => '',


    ), $atts));

      ob_start();
      echo '<div class="adminz_custom_pages">';
        global $post;      

        if ( !empty( $ids ) ) {
          $ids = explode( ',', $ids );
          $ids = array_map( 'trim', $ids );
          $args = [
            'post_type'=>'page',
            'post__in'=>$ids,
            'orderby'=> 'post__in'
          ];

          $the_query = new WP_Query( $args );
          if ( $the_query->have_posts() ) :
          while ( $the_query->have_posts() ) : $the_query->the_post();
            global $post;
            $childpages[] = $post;
          endwhile;
          endif;
          wp_reset_postdata();

        } else{
          if ( is_page() && $post->post_parent && !$parent ){        
            $args = [
              'child_of' => $post->post_parent, 
              'sort_column' => $orderby, 
              'sort_order' => $order 
            ];     
            if(!$multiple_level){
              $args['parent']= $post->post_parent;
            }   

          } else {
            $post_id = $post->ID;
            if($parent) {
              if(!is_numeric($parent)){
                $id = get_page_by_path( $parent );
                $parent = $id->ID;
              }
              $post_id = $parent;
            }
            $args = [
              'child_of' => $post_id, 
              'sort_column' => $orderby, 
              'sort_order' => $order,          
            ];
            if(!$multiple_level){
              $args['parent']= $post_id;
            }
          } 
          $childpages = get_pages( $args );
        } 

             
        



        $childpages = isset($childpages) ? $childpages : [];
        if(!$childpages) echo '<p class="lead shortcode-error text-center">Sorry, no pages was found</p>';
        

        $classes_box = array('page-box','box','has-hover');
        $classes_image = array('box-image');
        $classes_text = array('box-text');

        // Create Grid
        if($type == 'grid'){
          if(!$text_pos) $text_pos = 'center';
          if(!$text_color) $text_color = 'dark';
          // if($style !== 'shade') $style = 'overlay'; Why?
          $columns = 0;
          $current_grid = 0;
          // $grid = flatsome_get_grid($grid);
          $grid = apply_filters('adminz_flatsome_get_grid',flatsome_get_grid($grid),$grid);
          $grid_total = count($grid);
          flatsome_get_grid_height($grid_height, $_id);
        }

        // Add Animations
        if($animate) {$animate = 'data-animate="'.$animate.'"';}

        // Set box style
        if($style) $classes_box[] = 'box-'.$style;
        if($style == 'overlay') $classes_box[] = 'dark';
        if($style == 'shade') $classes_box[] = 'dark';
        if($style == 'badge') $classes_box[] = 'hover-dark';
        if($text_pos) $classes_box[] = 'box-text-'.$text_pos;
        if($style == 'overlay' && !$image_overlay) $image_overlay = 'rgba(0,0,0,.3)';

        // Set image styles
        if($image_hover)  $classes_image[] = 'image-'.$image_hover;
        if($image_hover_alt)  $classes_image[] = 'image-'.$image_hover_alt;
        if($image_height) $classes_image[] = 'image-cover';

        // Text classes
        if($text_hover) $classes_text[] = 'show-on-hover hover-'.$text_hover;
        if($text_align) $classes_text[] = 'text-'.$text_align;
        if($text_size) $classes_text[] = 'is-'.$text_size;
        if($text_color == 'dark') $classes_text[] = 'dark';

        $css_col = array(
          array( 'attribute' => 'border-radius', 'value' => $image_radius, 'unit' => '%'),
        );

        $css_args_img = array(
          array( 'attribute' => 'border-radius', 'value' => $image_radius, 'unit' => '%'),
          array( 'attribute' => 'width', 'value' => $image_width, 'unit' => '%' ),
        );

        $css_args = array(
          array( 'attribute' => 'background-color', 'value' => $text_bg ),
          array( 'attribute' => 'padding', 'value' => $text_padding ),
        );
        $css_image_height = array(
          array( 'attribute' => 'padding-top', 'value' => $image_height),
        );

        // Repeater options
        $repeater['id'] = $_id;
        $repeater['type'] = $type;
        $repeater['style'] = $style;
        $repeater['class'] = $class;
        $repeater['visibility'] = $visibility;
        $repeater['slider_style'] = $slider_nav_style;
        $repeater['slider_nav_color'] = $slider_nav_color;
        $repeater['slider_nav_position'] = $slider_nav_position;
        $repeater['slider_bullets'] = $slider_bullets;
        $repeater['auto_slide'] = $auto_slide;
        $repeater['infinitive'] = $infinitive;
        $repeater['row_spacing'] = $col_spacing;
        $repeater['row_width'] = $width;
        $repeater['columns'] = $columns;
        $repeater['columns__md'] = $columns__md;
        $repeater['columns__sm'] = $columns__sm;
        $repeater['depth'] = $depth;
        $repeater['depth_hover'] = $depth_hover;

        get_flatsome_repeater_start($repeater);

        foreach (  $childpages as $page ) {        
          $classes_col = array('page-col','col');
          $show_excerpt = $excerpt;
          if($type == 'grid'){
              if($grid_total > $current_grid) $current_grid++;
              $current = $current_grid-1;
              $classes_col[] = 'grid-col';
              if($grid[$current]['height']) $classes_col[] = 'grid-col-'.$grid[$current]['height'];
              if($grid[$current]['span']) $classes_col[] = 'large-'.$grid[$current]['span'];
              if($grid[$current]['md']) $classes_col[] = 'medium-'.$grid[$current]['md'];

              // Set image size
              if($grid[$current]['size'] == 'large') $image_size = 'large';
              if($grid[$current]['size'] == 'medium') $image_size = 'medium';
          }

        ?>
        <div class="<?php echo esc_attr(implode(' ', $classes_col)); ?>" <?php echo esc_attr($animate);?>>
          <div class="col-inner" <?php echo get_shortcode_inline_css($css_col); ?>>
            <?php do_action( 'adminz_custom_pages_before_plain',$repeater,$page ); ?>
            <a class="plain" href="<?php echo get_the_permalink($page->ID); ?>" title="<?php echo esc_attr($page->post_title); ?>" target="<?php echo esc_attr($target); ?>">
              <div class="<?php echo esc_attr(implode(' ', $classes_box)); ?>">
                    <div class="box-image" <?php echo get_shortcode_inline_css($css_args_img); ?>>
                        <div class="<?php echo esc_attr(implode(' ', $classes_image)); ?>" <?php echo get_shortcode_inline_css($css_image_height); ?>>
                        <?php $img_id = get_post_thumbnail_id($page->ID); echo wp_get_attachment_image($img_id, $image_size); ?>
                        </div>
                        <?php if($image_overlay){ ?><div class="overlay" style="background-color: <?php echo esc_attr($image_overlay);?>"></div><?php } ?>
                        <?php if($style == 'shade'){ ?><div class="shade"></div><?php } ?>
                    </div>
                    <div class="<?php echo esc_attr(implode(' ', $classes_text)); ?>" <?php echo get_shortcode_inline_css($css_args); ?>>
                          <div class="box-text-inner">
                              <<?php echo esc_attr($heading_tag);?> class="is-<?php echo esc_attr($title_size); ?> <?php echo esc_attr($title_style);?>"><?php echo esc_attr($page->post_title); ?></<?php echo esc_attr($heading_tag);?>>
                          </div>

                          <?php if($excerpt !== 'false') { ?>
                            <div class="is-divider"></div>
                            <p class="from_the_blog_excerpt <?php if($show_excerpt !== 'visible'){ echo 'show-on-hover hover-'.$show_excerpt; } ?>">

                              <?php
                                $the_excerpt  = get_the_excerpt($page->ID);                              
                                $excerpt_more = apply_filters( 'excerpt_more', ' [...]' );
                                echo flatsome_string_limit_words($the_excerpt, $excerpt_length) . $excerpt_more;
                              ?>
                            </p>                          
                          <?php } ?>
                          <?php if($readmore) { ?>
                            <button href="<?php echo get_the_permalink($page->ID); ?>" class="button <?php echo esc_attr($readmore_color); ?> is-<?php echo esc_attr($readmore_style); ?> is-<?php echo esc_attr($readmore_size); ?> mb-0">
                              <?php echo esc_attr($readmore) ;?>
                            </button>
                          <?php } ?>
                    </div>
                    
              </div>
            </a>
            <?php do_action( 'adminz_custom_pages_after_plain',$repeater,$page ); ?>
          </div>
        </div>
        <?php
      } // Loop
      echo '</div>';
      echo '</div>'; // adminz_custom_pages
      $content = ob_get_contents();

    ob_end_clean();
    return apply_filters('adminz_output_debug',$content);    
}

add_shortcode("adminz_ux_pages", "adminz_ux_pages");