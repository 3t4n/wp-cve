<?php 
/* Call the action for team section */
add_action('spiceb_spiko_testimonial_action','spiceb_spiko_testimonial_section');
/* Function for team section*/
function spiceb_spiko_testimonial_section()
{
$theme=wp_get_theme();    
$testimonial_animation_speed = get_theme_mod('testimonial_animation_speed', 3000);
$testimonial_smooth_speed = get_theme_mod('testimonial_smooth_speed', 1000);
$isRTL = (is_rtl()) ? (bool) true : (bool) false;

$slide_items = get_theme_mod('home_testimonial_slide_item', 1);
$testimonial_nav_style = get_theme_mod('testimonial_nav_style', 'bullets');
if ('Spiko Dark' == $theme->name){
    $testimonial_settings = array('design_id' => '#testimonial-carousel4', 'slide_items' => $slide_items, 'animationSpeed' => $testimonial_animation_speed, 'smoothSpeed' => $testimonial_smooth_speed, 'testimonial_nav_style' => $testimonial_nav_style, 'rtl' => $isRTL); 
}else{
    $testimonial_settings = array('design_id' => '#testimonial-carousel1', 'slide_items' => $slide_items, 'animationSpeed' => $testimonial_animation_speed, 'smoothSpeed' => $testimonial_smooth_speed, 'testimonial_nav_style' => $testimonial_nav_style, 'rtl' => $isRTL);
}
wp_register_script('spiko-testimonial', SPICEB_PLUGIN_URL . 'inc/spiko/js/front-page/testi.js', array('jquery'));
wp_localize_script('spiko-testimonial', 'testimonial_settings', $testimonial_settings);
wp_enqueue_script('spiko-testimonial');

$home_testimonial_section_title = get_theme_mod('home_testimonial_section_title', __('Proin Egestas', 'spicebox'));
$home_testimonial_section_discription = get_theme_mod('home_testimonial_section_discription', __('Nam Viverra Iaculis Finibus', 'spicebox'));
if ('Spiko Dark' == $theme->name){
    $testimonial_callout_background = get_theme_mod('testimonial_callout_background',SPICEB_PLUGIN_URL.'inc/spiko/images/testimonial/bg-img1.jpg'); 
}else{
    $testimonial_callout_background = get_theme_mod('testimonial_callout_background',SPICEB_PLUGIN_URL.'inc/spiko/images/testimonial/bg-img.jpg');
}

    $testimonial_options = get_theme_mod('spiko_testimonial_content');
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

$theme=wp_get_theme();
if ('Spiko Dark' == $theme->name){ 
    $testimonial_class='testi testi-4';
}
else{
    $testimonial_class='testi-1';
}

if ($testimonial_callout_background != '') {
?>
<section class="section-space testimonial <?php echo esc_attr($testimonial_class); ?> slideitem-1"  style="background-image:url('<?php echo esc_url($testimonial_callout_background); ?>'); background-repeat: no-repeat; background-position: top left; width: 100%; background-size: cover;">
<?php    
}
else
{
?>
<section class="section-space testimonial <?php echo esc_attr($testimonial_class); ?> slideitem-1">
 <?php
}?>
<div class="owl-carousel owl-theme">
     <div class="container">
       <?php if ($home_testimonial_section_title != '' || $home_testimonial_section_discription != '') { ?>
       <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="section-header">
                    <?php if ($home_testimonial_section_discription != ''):?>
                        <p class="section-subtitle"><?php echo esc_attr($home_testimonial_section_discription); ?></p>
                    <?php endif;?>
                    <?php if($home_testimonial_section_title):?>
                        <h2 class="section-title"><?php echo esc_attr($home_testimonial_section_title); ?></h2>
                        <div class="section-separator border-center"></div>
                    <?php endif;?>
                    
                    <div class="title_seprater"></div>
                </div>
            </div>
        </div>
        <?php } ?>
        <!--Testimonial-->
        <div class="row">
        <?php if ('Spiko Dark' == $theme->name) { ?>
            <div class="col-md-12 text-center owl-loaded owl-drag" id="testimonial-carousel4">
            <?php } else { ?>
            <div class="col-md-12" id="testimonial-carousel1">
            <?php  }
            $testimonial_options = json_decode($testimonial_options);
                if ($testimonial_options != '') {
                    $allowed_html = array(
                        'br' => array(),
                        'em' => array(),
                        'strong' => array(),
                        'b' => array(),
                        'i' => array(),
                    );
                    foreach ($testimonial_options as $testimonial_iteam) {
                        $home_testimonial_thumb = $testimonial_iteam->image_url;
                        $home_testimonial_title = !empty($testimonial_iteam->title) ? apply_filters('spiko_translate_single_string', $testimonial_iteam->title, 'Testimonial section') : '';
                        $home_testimonial_desc = !empty($testimonial_iteam->text) ? apply_filters('spiko_translate_single_string', $testimonial_iteam->text, 'Testimonial section') : '';
                        $home_testimonial_link = $testimonial_iteam->link;
                        $open_new_tab = $testimonial_iteam->open_new_tab;
                        $home_testimonial_clientname = !empty($testimonial_iteam->clientname) ? apply_filters('spiko_translate_single_string', $testimonial_iteam->clientname, 'Testimonial section') : '';
                        $home_testimonial_designation = !empty($testimonial_iteam->designation) ? apply_filters('spiko_translate_single_string', $testimonial_iteam->designation, 'Testimonial section') : '';
                        $stars = !empty($testimonial_iteam->home_testimonial_star) ? apply_filters('spiko_translate_single_string', $testimonial_iteam->home_testimonial_star, 'Testimonial section') : '';
                        ?>
                    <div class="item">
                    <?php 
                     if ('Spiko Dark' == $theme->name) { ?>
                     <blockquote class="testmonial-block">
                        <?php if ($home_testimonial_thumb != ''): ?>
                            <figure class="avatar">
                                <img src="<?php echo esc_url($home_testimonial_thumb); ?>" class="img-fluid rounded-circle" alt="<?php echo esc_attr($home_testimonial_clientname);?>" >
                            </figure>
                        <?php endif; 
                        if (($home_testimonial_clientname != '' || $home_testimonial_designation != '')) { ?>
                            <figcaption>
                              <?php if (!empty($home_testimonial_clientname)): ?>
                                <a href="<?php if (empty($test_link)) { echo '#'; } else { echo $test_link; } ?>" <?php if ($open_new_tab == "yes") { ?> target="_blank"<?php } ?>>   
                            <cite class="name"><?php echo $home_testimonial_clientname; ?></cite></a><?php endif; ?>
                            <?php if (!empty($home_testimonial_designation)): ?><span class="designation"><?php echo $home_testimonial_designation; ?></span><?php endif; ?>
                            </figcaption>
                        <?php }  if (!empty($home_testimonial_desc)): ?>
                        <div class="entry-content">
                            <p><?php echo wp_kses(html_entity_decode($home_testimonial_desc), $allowed_html); ?></p>
                        </div>      
                        <?php endif; ?>
                    </blockquote>
                    <?php } else { ?>
                    <div class="testmonial-block">
                        <?php $default_arg = array('class' => "img-circle"); ?>
                        <?php if ($home_testimonial_thumb != ''): ?>
                            <figure class="avatar">
                                <img src="<?php echo esc_url($home_testimonial_thumb); ?>" class="img-fluid" alt="<?php echo esc_attr($home_testimonial_clientname);?>" >
                                <span class="quotes-seprator"></span>
                            </figure>
                        <?php endif;
                        if ($home_testimonial_clientname != '' || $home_testimonial_desc != '' ) { ?>  
                            <div class="entry-content">
                                <?php if (!empty($home_testimonial_clientname)): ?>
                                    <h4 class="name">
                                <a href="<?php if (empty($home_testimonial_link)) {echo '#';} else { echo esc_url($home_testimonial_link);}?>" <?php if($open_new_tab=='yes') { ?> target="_blank"<?php } ?>>
                                        <?php echo esc_html($home_testimonial_clientname); ?></a>
                                    </h4>
                                <?php endif; ?>
                               <?php if ($home_testimonial_desc != '') { ?><p><?php echo wp_kses(html_entity_decode($home_testimonial_desc), $allowed_html); ?></p> <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                    <?php }?>
                </div> 
                <?php 
                }
            }
            ?> 
            </div>                        
        </div>
    </div>
</section>
<?php endif;?> 
<!-- /End of Testimonial Section-->
<?php } ?>