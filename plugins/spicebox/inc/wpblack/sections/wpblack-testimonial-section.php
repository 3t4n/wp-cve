<?php 
/* Call the action for team section */
add_action('spiceb_wpblack_testimonial_action','spiceb_wpblack_testimonial_section');
/* Function for team section*/
function spiceb_wpblack_testimonial_section()
{
$theme=wp_get_theme();    
$testimonial_animation_speed = get_theme_mod('testimonial_animation_speed', 3000);
$testimonial_smooth_speed = get_theme_mod('testimonial_smooth_speed', 1000);
$isRTL = (is_rtl()) ? (bool) true : (bool) false;

$slide_items = get_theme_mod('home_testimonial_slide_item', 1);
$testimonial_nav_style = get_theme_mod('testimonial_nav_style', 'bullets');
$testimonial_settings = array('design_id' => '#testimonial-carousel', 'slide_items' => $slide_items, 'animationSpeed' => $testimonial_animation_speed, 'smoothSpeed' => $testimonial_smooth_speed, 'testimonial_nav_style' => $testimonial_nav_style, 'rtl' => $isRTL);
wp_register_script('wpblack-testimonial', SPICEB_PLUGIN_URL . 'inc/wpblack/js/front-page/testi.js', array('jquery'));
wp_localize_script('wpblack-testimonial', 'testimonial_settings', $testimonial_settings);
wp_enqueue_script('wpblack-testimonial');

$home_testimonial_section_title = get_theme_mod('home_testimonial_section_title', __('Proin Egestas', 'spicebox'));
$home_testimonial_section_discription = get_theme_mod('home_testimonial_section_discription', __('Nam Viverra Iaculis Finibus', 'spicebox'));

$testimonial_options = get_theme_mod('wpblack_testimonial_content');
if (empty($testimonial_options)) {
    $testimonial_options = json_encode(array(
                    array(
                        'title' => 'Exellent Theme & Very Fast Support',
                        'text' => 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem ipsum dolor sit amet,<br> temp consectetur adipisicing elit.',
                        'clientname' => __('Amanda Smith', 'spicebox'),
                        'designation' => __('Developer', 'spicebox'),
                        'home_testimonial_star' => '4.5',
                        'link' => '#',
                        'image_url' => SPICEB_PLUGIN_URL . 'inc/busicare/images/testimonial/user1.jpg',
                        'open_new_tab' => 'no',
                        'id' => 'customizer_repeater_77d7ea7f40b96',
                        'home_slider_caption' => 'customizer_repeater_star_4.5',
                    ),
                    array(
                        'title' => 'Exellent Theme & Very Fast Support',
                        'text' => 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem ipsum dolor sit amet,<br> temp consectetur adipisicing elit.',
                        'clientname' => __('Travis Cullan', 'spicebox'),
                        'designation' => __('Team Leader', 'spicebox'),
                        'home_testimonial_star' => '5',
                        'link' => '#',
                        'image_url' => SPICEB_PLUGIN_URL . 'inc/busicare/images/testimonial/user2.jpg',
                        'open_new_tab' => 'no',
                        'id' => 'customizer_repeater_88d7ea7f40b97',
                        'home_slider_caption' => 'customizer_repeater_star_5',
                    ),
                    array(
                        'title' => 'Exellent Theme & Very Fast Support',
                        'text' => 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem ipsum dolor sit amet,<br> temp consectetur adipisicing elit.',
                        'clientname' => __('Victoria Wills', 'spicebox'),
                        'designation' => __('Volunteer', 'spicebox'),
                        'home_testimonial_star' => '3.5',
                        'link' => '#',
                        'image_url' => SPICEB_PLUGIN_URL . 'inc/busicare/images/testimonial/user3.jpg',
                        'id' => 'customizer_repeater_11d7ea7f40b98',
                        'open_new_tab' => 'no',
                        'home_slider_caption' => 'customizer_repeater_star_3.5',
                    ),
                    ));
}
if(get_theme_mod('testimonial_section_enable',true)==true):

$testimonial_class='testi-1';
$testimonial_callout_background = get_theme_mod('testimonial_callout_background', SPICEB_PLUGIN_URL . '/inc/busicare/images/testimonial/testimonial-bg.jpg');
?>
<section class="section-space testimonial <?php echo esc_attr($testimonial_class); ?> shortitem-1" style="background:url('<?php echo esc_url($testimonial_callout_background); ?>') 112% 50% no-repeat; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover;  background-size: cover; background-position: center center;
    background-repeat: no-repeat;">>

    <div class="container">
        <?php if ($home_testimonial_section_title != '' || $home_testimonial_section_discription != '') { ?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12">
                <div class="section-header">                
                    <?php if ($home_testimonial_section_discription != ''):?>
                        <p class="section-subtitle"><?php echo esc_html($home_testimonial_section_discription); ?></p>
                    <?php endif;?>

                    <?php if($home_testimonial_section_title):?>
                        <h2 class="section-title"><?php echo esc_html($home_testimonial_section_title); ?></h2>                    
                    <?php endif;?>         

                    <div class="section-separator border-center"></div>
                </div>
            </div>
        </div>
        <?php } ?>

        <!--Testimonial-->

        <div class="row">        
            <div class="owl-carousel owl-theme col-md-12 owl-loaded owl-drag"  id="testimonial-carousel">
            <?php
            $testimonial_options = json_decode($testimonial_options);
            if ($testimonial_options != ''){
                $allowed_html = array(
                                        'br' => array(),
                                        'em' => array(),
                                        'strong' => array(),
                                        'b' => array(),
                                        'i' => array(),
                                    );
                foreach ($testimonial_options as $testimonial_iteam){
                    $title = !empty($testimonial_iteam->title) ? apply_filters('wpblack_translate_single_string', $testimonial_iteam->title, 'Testimonial section') : '';
                    $test_desc = !empty($testimonial_iteam->text) ? apply_filters('wpblack_translate_single_string', $testimonial_iteam->text, 'Testimonial section') : '';
                    $test_link = $testimonial_iteam->link;
                    $open_new_tab = $testimonial_iteam->open_new_tab;
                    $clientname = !empty($testimonial_iteam->clientname) ? apply_filters('wpblack_translate_single_string', $testimonial_iteam->clientname, 'Testimonial section') : '';
                    $designation = !empty($testimonial_iteam->designation) ? apply_filters('wpblack_translate_single_string', $testimonial_iteam->designation, 'Testimonial section') : '';
                    $stars = !empty($testimonial_iteam->home_testimonial_star) ? apply_filters('wpblack_translate_single_string', $testimonial_iteam->home_testimonial_star, 'Testimonial section') : '';
                        //Below Code will Run For Testimonial Design 1
                        ?>
                    <div class="item">
                        <blockquote class="testimonial-block">
                                <div class="testimonial-pblock">
                                    <div class="testimonial-head">
                                        <?php if ($testimonial_iteam->image_url != ''): ?>
                                            <figure class="avatar">
                                                <img src="<?php echo esc_url($testimonial_iteam->image_url); ?>" alt="img" class="img-fluid rounded-circle"/>
                                            </figure>                                            
                                        <?php endif;?>
                                        <?php if (($clientname != '' || $designation != '')) { ?>
                                            <figcaption class="testimonial-dasignation">
                                                <?php if ($clientname != '') {?>
                                                    <h4 class="name text-left"><?php 
                                                    if ($open_new_tab == "yes"){ 
                                                        $target='target="_blank"';}
                                                    else{
                                                        $target='';
                                                    }
                                                    if(!empty($test_link)){
                                                        echo '<a href="'.esc_url($test_link).'" '.$target.'>'.esc_html($clientname).'</a>';
                                                    }
                                                    else{
                                                        echo esc_html($clientname);
                                                    }?></h4>
                                                <?php }
                                                if ($designation != '') {?>
                                                    <h5 class="designation text-left"><?php echo esc_html($designation); ?></h5>
                                                <?php } ?>
                                            </figcaption>
                                        <?php } ?>
                                    </div>
                                    <?php if (!empty($test_desc)): ?>
                                        <div class="entry-content">
                                           <p class="text-left"><?php echo wp_kses(html_entity_decode($test_desc), $allowed_html); ?></p>
                                        </div>
                                    <?php endif;?>
                                </div>
                            </blockquote>
                        </div>                        
                        <?php
                    }
                }?>
            </div>                  
        </div>
    </div>
</section>
<?php endif;?> 
<!-- /End of Testimonial Section-->
<?php } ?>