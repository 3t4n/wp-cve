<?php 
/* Call the action for team section */
add_action('spiceb_busicare_testimonial_action','spiceb_busicare_testimonial_section');
/* Function for team section*/
function spiceb_busicare_testimonial_section()
{
    $isRTL = (is_rtl()) ? (bool) true : (bool) false;
    $testimonial_nav_style = get_theme_mod('testimonial_nav_style', 'bullets');
    $testimonialsettings = array('design_id' => '#testimonial-carousel', 'testimonial_nav_style' => $testimonial_nav_style, 'rtl' => $isRTL);
    wp_register_script('busicare-testimonial', SPICEB_PLUGIN_URL . 'inc/busicare/js/front-page/testi.js', array('jquery'));
    wp_localize_script('busicare-testimonial', 'testimonial_settings', $testimonialsettings);
    wp_enqueue_script('busicare-testimonial');
$home_testimonial_section_title = get_theme_mod('home_testimonial_section_title', __('Nam Viverra Iaculis Finibus', 'spicebox'));
$testimonial_callout_background = get_theme_mod('testimonial_callout_background', SPICEB_PLUGIN_URL . '/inc/busicare/images/testimonial/testimonial-bg.jpg');

    $testimonial_options = get_theme_mod('busicare_testimonial_content');
if (empty($testimonial_options)) {
    $testimonial_options = json_encode(array(
                array(
                    'title' => 'Nam Viverra Iaculis Finibus',
                    'text' => 'Sed ut Perspiciatis Unde Omnis Iste Sed ut perspiciatis unde omnis iste natu error sit voluptatem accu tium neque fermentum veposu miten a tempor nise. Duis autem vel eum iriure dolor in hendrerit in vulputate velit consequat reprehender in voluptate velit esse cillum duis dolor fugiat nulla pariatur.',
                    'clientname' => esc_html__('Cras Vitae', 'busicare-plus'),
                    'designation' => esc_html__('Eu Suscipit', 'busicare-plus'),
                    'link' => '#',
                    'image_url' => SPICEB_PLUGIN_URL . '/inc/busicare/images/testimonial/user1.jpg',
                    'open_new_tab' => 'no',
                    'id' => 'customizer_repeater_56d7ea7f40b96',
                ),
                array(
                    'title' => 'Nam Viverra Iaculis Finibus',
                    'text' => 'Sed ut Perspiciatis Unde Omnis Iste Sed ut perspiciatis unde omnis iste natu error sit voluptatem accu tium neque fermentum veposu miten a tempor nise. Duis autem vel eum iriure dolor in hendrerit in vulputate velit consequat reprehender in voluptate velit esse cillum duis dolor fugiat nulla pariatur.',
                    'clientname' => esc_html__('Cras Vitae', 'busicare-plus'),
                    'designation' => esc_html__('Eu Suscipit', 'busicare-plus'),
                    'link' => '#',
                    'image_url' => SPICEB_PLUGIN_URL . '/inc/busicare/images/testimonial/user2.jpg',
                    'open_new_tab' => 'no',
                    'id' => 'customizer_repeater_56d7ea7f40b97',
                ),
                array(
                    'title' => 'Nam Viverra Iaculis Finibus',
                    'text' => 'Sed ut Perspiciatis Unde Omnis Iste Sed ut perspiciatis unde omnis iste natu error sit voluptatem accu tium neque fermentum veposu miten a tempor nise. Duis autem vel eum iriure dolor in hendrerit in vulputate velit consequat reprehender in voluptate velit esse cillum duis dolor fugiat nulla pariatur.',
                    'clientname' => esc_html__('Cras Vitae', 'busicare-plus'),
                    'designation' => esc_html__('Eu Suscipit', 'busicare-plus'),
                    'link' => '#',
                    'image_url' => SPICEB_PLUGIN_URL . '/inc/busicare/images/testimonial/user3.jpg',
                    'id' => 'customizer_repeater_56d7ea7f40b98',
                    'open_new_tab' => 'no',
                ),
                    ));
}
if(get_theme_mod('testimonial_section_enable',true)==true):?>
<section class="section-space testimonial" style="background:url('<?php echo esc_url($testimonial_callout_background); ?>') 112% 50% no-repeat; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover;  background-size: cover; background-position: center center;
    background-repeat: no-repeat;">
    <div class="overlay"></div>
     <div class="container">
       <?php if ($home_testimonial_section_title != '' ) { ?>
       <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="section-header">
                    <h2 class="section-title text-white"><?php echo esc_html($home_testimonial_section_title); ?></h2>
                    <div class="title_seprater"></div>
                </div>
            </div>
        </div>
        <?php } ?>

        <!--Testimonial-->
        <div class="row">
            <div class="owl-carousel owl-theme col-md-12" id="testimonial-carousel">
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
                    $home_testimonial_title = !empty($testimonial_iteam->title) ? apply_filters('busicare_translate_single_string', $testimonial_iteam->title, 'Testimonial section') : '';
                    $home_testimonial_desc = !empty($testimonial_iteam->text) ? apply_filters('busicare_translate_single_string', $testimonial_iteam->text, 'Testimonial section') : '';
                    $home_testimonial_link = $testimonial_iteam->link;
                    $open_new_tab = $testimonial_iteam->open_new_tab;
                    $home_testimonial_clientname = !empty($testimonial_iteam->clientname) ? apply_filters('busicare_translate_single_string', $testimonial_iteam->clientname, 'Testimonial section') : '';
                    $home_testimonial_designation = !empty($testimonial_iteam->designation) ? apply_filters('busicare_translate_single_string', $testimonial_iteam->designation, 'Testimonial section') : '';
                    $stars = !empty($testimonial_iteam->home_testimonial_star) ? apply_filters('busicare_translate_single_string', $testimonial_iteam->home_testimonial_star, 'Testimonial section') : '';
                    ?>
                <div class="item">
                <blockquote class="testmonial-block text-center">
                    <?php $default_arg = array('class' => "img-circle"); ?>
                    <?php if ($home_testimonial_thumb != ''): ?>
                        <figure class="avatar">
                            <img src="<?php echo esc_url($home_testimonial_thumb); ?>" class="img-fluid rounded-circle" alt="<?php echo esc_attr($home_testimonial_clientname);?>" >
                        </figure>
                    <?php endif;
                    if (!empty($home_testimonial_desc)): ?>
                        <div class="entry-content">
                            <?php if ($home_testimonial_title != ''){ ?><h3 class="title"><span>“ <?php echo wp_kses(html_entity_decode($home_testimonial_title), $allowed_html); ?> ”</span></h3><?php } ?>

                            <?php if ($home_testimonial_desc != '') { ?><p class="text-white" ><?php echo wp_kses(html_entity_decode($home_testimonial_desc), $allowed_html); ?></p> <?php } ?>
                        </div>  
                    <?php endif;
                    if ($home_testimonial_clientname != '' || $home_testimonial_designation != '' ) { ?>                              
                        <figcaption>
                            <?php if (!empty($home_testimonial_designation)): ?>
                            <a href="<?php if (empty($home_testimonial_link)) {echo '#';} else { echo esc_url($home_testimonial_link);}?>" <?php if($open_new_tab=='yes') { ?> target="_blank"<?php } ?>>
                                    <cite class="name"><?php echo esc_html($home_testimonial_clientname); ?></cite></a>
                            <?php endif; ?>
                            <?php if (!empty($home_testimonial_designation)): ?>
                             <span class="designation"><?php echo esc_html($home_testimonial_designation); ?></span>
                            <?php endif; ?>
                        </figcaption>
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