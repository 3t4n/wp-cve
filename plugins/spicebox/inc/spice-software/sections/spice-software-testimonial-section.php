<?php 
/* Call the action for team section */
add_action('spiceb_spice_software_testimonial_action','spiceb_spice_software_testimonial_section');
/* Function for team section*/
function spiceb_spice_software_testimonial_section()
{
$testimonial_animation_speed = get_theme_mod('testimonial_animation_speed', 3000);
$testimonial_smooth_speed = get_theme_mod('testimonial_smooth_speed', 1000);
$isRTL = (is_rtl()) ? (bool) true : (bool) false;

$slide_items = get_theme_mod('home_testimonial_slide_item', 1);
$testimonial_nav_style = get_theme_mod('testimonial_nav_style', 'bullets');

$testimonial_settings = array('design_id' => '#testimonial-carousel', 'slide_items' => $slide_items, 'animationSpeed' => $testimonial_animation_speed, 'smoothSpeed' => $testimonial_smooth_speed, 'testimonial_nav_style' => $testimonial_nav_style, 'rtl' => $isRTL);

wp_register_script('spice-software-testimonial', SPICEB_PLUGIN_URL . 'inc/spice-software/js/front-page/testi.js', array('jquery'));
wp_localize_script('spice-software-testimonial', 'testimonial_settings', $testimonial_settings);
wp_enqueue_script('spice-software-testimonial');

$home_testimonial_section_title = get_theme_mod('home_testimonial_section_title', __('Proin Egestas', 'spicebox'));
$home_testimonial_section_discription = get_theme_mod('home_testimonial_section_discription', __('Nam Viverra Iaculis Finibus', 'spicebox'));
$testimonial_callout_background = get_theme_mod('testimonial_callout_background', SPICEB_PLUGIN_URL . '/inc/spice-software/images/testimonial/wavy-dots.png');

    $testimonial_options = get_theme_mod('spice_software_testimonial_content');
if (empty($testimonial_options)) {
    $testimonial_options = json_encode(array(
                    array(
                        'title' => 'Exellent Theme & Very Fast Support',
                        'text' => 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem ipsum dolor sit amet,<br> temp consectetur adipisicing elit.',
                        'clientname' => __('Amanda Smith', 'spice-software-plus'),
                        'designation' => __('Developer', 'spice-software-plus'),
                        'home_testimonial_star' => '4.5',
                        'link' => '#',
                        'image_url' => SPICEB_PLUGIN_URL . '/inc/spice-software/images/testimonial/user1.jpg',
                        'open_new_tab' => 'no',
                        'id' => 'customizer_repeater_77d7ea7f40b96',
                        'home_slider_caption' => 'customizer_repeater_star_4.5',
                    ),
                    array(
                        'title' => 'Exellent Theme & Very Fast Support',
                        'text' => 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem ipsum dolor sit amet,<br> temp consectetur adipisicing elit.',
                        'clientname' => __('Travis Cullan', 'spice-software-plus'),
                        'designation' => __('Team Leader', 'spice-software-plus'),
                        'home_testimonial_star' => '5',
                        'link' => '#',
                        'image_url' => SPICEB_PLUGIN_URL . '/inc/spice-software/images/testimonial/user2.jpg',
                        'open_new_tab' => 'no',
                        'id' => 'customizer_repeater_88d7ea7f40b97',
                        'home_slider_caption' => 'customizer_repeater_star_5',
                    ),
                    array(
                        'title' => 'Exellent Theme & Very Fast Support',
                        'text' => 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem ipsum dolor sit amet,<br> temp consectetur adipisicing elit.',
                        'clientname' => __('Victoria Wills', 'spice-software-plus'),
                        'designation' => __('Volunteer', 'spice-software-plus'),
                        'home_testimonial_star' => '3.5',
                        'link' => '#',
                        'image_url' => SPICEB_PLUGIN_URL . '/inc/spice-software/images/testimonial/user3.jpg',
                        'id' => 'customizer_repeater_11d7ea7f40b98',
                        'open_new_tab' => 'no',
                        'home_slider_caption' => 'customizer_repeater_star_3.5',
                    ),
                    ));
}
$theme=wp_get_theme();
if ('Spice Software Dark' == $theme->name):
    $sectionClass = 'testi-4';
    $textColor = 'text-white';
    $textAlign = 'text-center';
else:
    $sectionClass = 'testi-1';
    $textColor = 'text-black';
    $textAlign = 'text-center';
endif;
$testimonial_callout_background = get_theme_mod('testimonial_callout_background',SPICEB_PLUGIN_URL . '/inc/spice-software/images/testimonial/bg-img.jpg');
if(get_theme_mod('testimonial_section_enable',true)==true):
    if('Spice Software Dark'==$theme->name):?>
         <section class="section-space testimonial <?php echo $sectionClass; ?>"  style="background:url('<?php echo esc_url($testimonial_callout_background); ?>') 100% 100% no-repeat; -webkit-background-size: cover;
                  -moz-background-size: cover;
                  -o-background-size: cover;
                  background-size: cover;">
    <?php else:?>
    <section class="section-space testimonial <?php echo $sectionClass; ?>">
    <?php endif;?>
    <div class="owl-carousel owl-theme">
     <div class="container">
       <?php if ($home_testimonial_section_title != '' || $home_testimonial_section_discription != '') { ?>
       <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="section-header">
                    <?php if($home_testimonial_section_title):?>
                        <h2 class="section-title <?php echo $textColor; ?>"><?php echo esc_attr($home_testimonial_section_title); ?></h2>
                    <?php endif;?>
                    <?php if ($home_testimonial_section_discription != ''):?>
                        <p class="<?php echo $textColor; ?>"><?php echo esc_attr($home_testimonial_section_discription); ?></p>
                    <?php endif;?>
                    <div class="title_seprater"></div>
                </div>
            </div>
        </div>
        <?php } ?>

        <!--Testimonial-->
        <div class="row">
        <div class="col-md-12" id="testimonial-carousel">
	    <?php
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
                    $home_testimonial_title = !empty($testimonial_iteam->title) ? apply_filters('spice_software_translate_single_string', $testimonial_iteam->title, 'Testimonial section') : '';
                    $home_testimonial_desc = !empty($testimonial_iteam->text) ? apply_filters('spice_software_translate_single_string', $testimonial_iteam->text, 'Testimonial section') : '';
                    $home_testimonial_link = $testimonial_iteam->link;
                    $open_new_tab = $testimonial_iteam->open_new_tab;
                    $home_testimonial_clientname = !empty($testimonial_iteam->clientname) ? apply_filters('spice_software_translate_single_string', $testimonial_iteam->clientname, 'Testimonial section') : '';
                    $home_testimonial_designation = !empty($testimonial_iteam->designation) ? apply_filters('spice_software_translate_single_string', $testimonial_iteam->designation, 'Testimonial section') : '';
                    $stars = !empty($testimonial_iteam->home_testimonial_star) ? apply_filters('spice_software_translate_single_string', $testimonial_iteam->home_testimonial_star, 'Testimonial section') : '';
                    ?>
                <div class="item">
                    <blockquote class="testmonial-block <?php echo $textAlign; ?>">
                        <?php $default_arg = array('class' => "img-circle"); ?>
                        <?php if ($home_testimonial_thumb != ''): ?>
                            <figure class="avatar">
                                <img src="<?php echo esc_url($home_testimonial_thumb); ?>" class="img-fluid rounded-circle" alt="<?php echo esc_attr($home_testimonial_clientname);?>" >
                            </figure>
                        <?php endif;
                        if ('Spice Software Dark' == $theme->name):
                            if ($home_testimonial_clientname != '' || $home_testimonial_designation != '' ) { ?>                          
                            <figcaption>
                                <?php if (!empty($home_testimonial_designation)): ?>
                                <a href="<?php if (empty($home_testimonial_link)) {echo '#';} else { echo esc_url($home_testimonial_link);}?>" <?php if($open_new_tab==true) { ?> target="_blank"<?php } ?>>
                                        <cite class="name"><?php echo esc_html($home_testimonial_clientname); ?></cite></a>
                                <?php endif; ?>
                                <?php if (!empty($home_testimonial_designation)): ?>
                                 <span class="designation <?php echo esc_attr($textColor);?>"><?php echo esc_html($home_testimonial_designation); ?></span>
                                <?php endif; ?>
                            </figcaption>
                            <?php } 
                        endif;

    					if (!empty($home_testimonial_desc)): ?>
                            <div class="entry-content">
                                <?php if ($home_testimonial_desc != '') { ?><p class="<?php echo esc_attr($textColor);?>" ><?php echo wp_kses(html_entity_decode($home_testimonial_desc), $allowed_html); ?></p> <?php } ?>
                            </div>  
                        <?php endif;
                        if ('Spice Software Dark' != $theme->name):
                            if ($home_testimonial_clientname != '' || $home_testimonial_designation != '' ) { ?>                              
                                <figcaption>
                                    <?php if (!empty($home_testimonial_designation)): ?>
                                    <a href="<?php if (empty($home_testimonial_link)) {echo '#';} else { echo esc_url($home_testimonial_link);}?>" <?php if($open_new_tab==true) { ?> target="_blank"<?php } ?>>
                                            <cite class="name"><?php echo esc_html($home_testimonial_clientname); ?></cite></a>
                                    <?php endif; ?>
                                    <?php if (!empty($home_testimonial_designation)): ?>
                                     <span class="designation"><?php echo esc_html($home_testimonial_designation); ?></span>
                                 	<?php endif; ?>
                                </figcaption>
                            <?php } 
                        endif;  

                        if(!empty($stars)) { ?>
                            <div class="rating">   
                                <?php
                                $stars = end(explode('_', $stars));
                                $stars = explode('.', end(explode('_', $stars)));
                                for ($i = 1; $i <= $stars[0]; $i++) {
                                    ?>
                                    <span class="fa fa-star"></span>
                                <?php } ?>
                                <?php if ($stars[1]) { ?>
                                    <span class="fa fa-star-half-o"></span>
                                <?php } ?>
                            </div>
                        <?php } ?>
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