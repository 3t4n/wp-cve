<?php 

function s_j_s_checkit($name, $array){
    $result = '';
    if (is_array($array) && isset($array[$name])){ $result = $array[$name]; }
    return $result;
}

function s_j_s_swiper_js_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'show' => '',  
            'id' => 0
        ),
        $atts
    );

    $meta_prefix   = '_s_s_m_';
    $post_type     = 'swiper_js_slides';

    $args=array( 
        'post_type'         => $post_type,
        'posts_per_page'    => 1,
        'post__in'          => array($atts['id'])
    );

    $my_query = null;
    $my_query = new WP_Query($args);

    ob_start();
    if ( $my_query->have_posts() ) {
    ?>


    <?php
        $general        = get_post_meta($atts['id'], $meta_prefix.'setting_general', true);
        $autoplay       = get_post_meta($atts['id'], $meta_prefix.'setting_autoplay', true);
        $pagination     = get_post_meta($atts['id'], $meta_prefix.'setting_pagination', true);
        $navigation     = get_post_meta($atts['id'], $meta_prefix.'setting_navigation', true);
        $breakpoints    = get_post_meta($atts['id'], $meta_prefix.'setting_breakpoints', true);

        $auto;
        $autoCheck = s_j_s_checkit('autoplay', $autoplay);

        $pagi;
        $pagiCheck = s_j_s_checkit('pagination', $pagination);

        $navi;
        $naviCheck = s_j_s_checkit('navigation', $navigation);

        if($general){
            $gene = array(
                "initialSlide"          => s_j_s_checkit('initialSlide', $general),
                "direction"             => s_j_s_checkit('direction', $general),
                "speed"                 => s_j_s_checkit('speed', $general),
                "autoHeight"            => filter_var(s_j_s_checkit('autoHeight', $general), FILTER_VALIDATE_BOOLEAN),
                "effect"                => s_j_s_checkit('effect', $general),
                "slidesPerView"         => s_j_s_checkit('slidesPerView', $general),
                "slidesPerColumn"       => s_j_s_checkit('slidesPerColumn', $general),
                "spaceBetween"          => s_j_s_checkit('spaceBetween', $general),
            );
        }

        if($breakpoints){           
            $breakp = array(
                "320"   => array(
                    'slidesPerView' => s_j_s_checkit('320', $breakpoints)
                ),
                "480"   => array(
                    'slidesPerView' => s_j_s_checkit('480', $breakpoints)
                ),
                "640"   => array(
                    'slidesPerView' => s_j_s_checkit('640', $breakpoints)
                ),
                "768"   => array(
                    'slidesPerView' => s_j_s_checkit('768', $breakpoints)
                ),
                "980"   => array(
                    'slidesPerView' => s_j_s_checkit('980', $breakpoints)
                ),
                "1024"  => array(
                    'slidesPerView' => s_j_s_checkit('1024', $breakpoints)
                )
            );
        }

        if($autoCheck == 'false'){
            $auto = filter_var($autoCheck, FILTER_VALIDATE_BOOLEAN);
        }else{
            $auto = array(
                'delay'                 => s_j_s_checkit('delay', $autoplay),
                'stopOnLastSlide'       => filter_var(s_j_s_checkit('stopOnLastSlide', $autoplay), FILTER_VALIDATE_BOOLEAN),
                'disableOnInteraction'  => filter_var(s_j_s_checkit('disableOnInteraction', $autoplay), FILTER_VALIDATE_BOOLEAN),
                'reverseDirection'      => filter_var(s_j_s_checkit('reverseDirection', $autoplay), FILTER_VALIDATE_BOOLEAN),
                'waitForTransition'     => filter_var(s_j_s_checkit('waitForTransition', $autoplay), FILTER_VALIDATE_BOOLEAN)
            );
        }

        if($pagiCheck == 'false'){
            $pagi = filter_var($pagiCheck, FILTER_VALIDATE_BOOLEAN);
        }else{
            $pagi = array(
                "type"                  => s_j_s_checkit('type', $pagination),
                "el"                    => '.'.s_j_s_checkit('el', $pagination),
                "bulletElement"         => s_j_s_checkit('bulletElement', $pagination),
                "clickable"             => filter_var(s_j_s_checkit('clickable', $pagination), FILTER_VALIDATE_BOOLEAN),
                "bulletClass"           => s_j_s_checkit('bulletClass', $pagination),
                "currentClass"          => s_j_s_checkit('currentClass', $pagination),
                "bulletActiveClass"     => s_j_s_checkit('bulletActiveClass', $pagination),
                "clickableClass"        => s_j_s_checkit('clickableClass', $pagination),
                "lockClass"             => s_j_s_checkit('lockClass', $pagination),
            );
        }

        if($naviCheck == 'false'){
            $navi = filter_var($naviCheck, FILTER_VALIDATE_BOOLEAN);
        }else{
            $navi = array(
                "nextEl"                => '.'.s_j_s_checkit('nextEl', $navigation),
                "prevEl"                => '.'.s_j_s_checkit('prevEl', $navigation),
                "disabledClass"         => s_j_s_checkit('disabledClass', $navigation),
                "hiddenClass"           => s_j_s_checkit('hiddenClass', $navigation),
            );
        }

    ?>

    <div class="swiper-container"
        data-slider='swiperSlider<?php echo $atts['id']; ?>'
        data-general='<?php echo json_encode($gene, JSON_NUMERIC_CHECK); ?>'
        data-autoplay='<?php echo json_encode($auto, JSON_NUMERIC_CHECK); ?>'
        data-pagination='<?php echo json_encode($pagi, JSON_NUMERIC_CHECK); ?>'
        data-navigation='<?php echo json_encode($navi, JSON_NUMERIC_CHECK); ?>'
        data-breakpoints='<?php echo json_encode($breakp, JSON_NUMERIC_CHECK); ?>'>
        <div class="swiper-wrapper">
            <?php
             
            while ($my_query->have_posts()) : $my_query->the_post(); 
                $ids = get_post_meta(get_the_ID(), $meta_prefix.'gallery_id', true);
                ?>
                <?php if ($ids) : foreach ($ids as $key => $value) : $image = wp_get_attachment_image_src($value, 'full'); ?>
                    <div class="swiper-slide">
                        <img class="image-preview" src="<?php echo $image[0]; ?>">
                    </div>
                <?php endforeach; endif; ?>
            <?php 
            endwhile; 
            wp_reset_query(); ?>
        </div>

        

        <?php if($pagiCheck != 'false'){ ?>
            <div class="<?php echo s_j_s_checkit('el', $pagination); ?>"></div>
        <?php } ?>

        <?php if($naviCheck != 'false'){ ?>
            <div class="<?php echo s_j_s_checkit('prevEl', $navigation); ?>"></div>
            <div class="<?php echo s_j_s_checkit('nextEl', $navigation); ?>"></div>
        <?php } ?>
    </div>

    <?php 
            }else{ 
                _e( 'Sorry, no slider were found.', 'textdomain' );
                //die;
            }
        ?>
    
 
<?php


    $html = ob_get_clean();
    return $html;
}

add_shortcode( 'swiper-js-slider', 's_j_s_swiper_js_shortcode' );