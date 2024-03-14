<?php
/**
 * Testimonial section for the homepage.
 */
if (!function_exists('spiceb_honeypress_testimonial')) :

    function spiceb_honeypress_testimonial() {

        $testimonial_section_enable = get_theme_mod('testimonial_section_enable', 'on');
        if ($testimonial_section_enable != 'off') {
            $theme = wp_get_theme();
            if ($theme->name == 'HoneyPress') {
                $testimonial_overlay_section_color = get_theme_mod('testimonial_overlay_section_color', 'rgba(0, 76, 236, 0.9)');
                $testimonial_section_class="section-module testimonial";
            } elseif ($theme->name == 'Radix Multipurpose') {
                $testimonial_overlay_section_color = get_theme_mod('testimonial_overlay_section_color', 'rgba(130, 180, 64, 0.9)');
                $testimonial_section_class="section-module testimonial";
            } elseif ($theme->name == 'Bizhunt') {
                $testimonial_overlay_section_color = get_theme_mod('testimonial_overlay_section_color', 'rgba(105,116,234,0.9)');
                $testimonial_section_class="section-module testimonial";
            } elseif ($theme->name == 'Tromas') {
                $testimonial_overlay_section_color = get_theme_mod('testimonial_overlay_section_color', 'rgba(206, 27, 40,0.9)');
                $testimonial_section_class="section-module testimonial";
            }elseif ($theme->name == 'HoneyWaves') {
                $testimonial_overlay_section_color = get_theme_mod('testimonial_overlay_section_color', 'rgba(0, 0, 0, 0.9)');
                $testimonial_section_class="section-module testimonial";
            } elseif ($theme->name == 'HoneyBee') {
                $testimonial_overlay_section_color = get_theme_mod('testimonial_overlay_section_color', 'rgba(248, 145, 8, 0.9)');
                $testimonial_section_class="section-module testimonial7";
            } elseif ($theme->name == 'Honeypress Dark') {
                $testimonial_overlay_section_color = get_theme_mod('testimonial_overlay_section_color', 'rgba(249, 0, 77, 0.9)');
                $testimonial_section_class="section-module testimonial testi-5";
            } else {
                $testimonial_overlay_section_color = get_theme_mod('testimonial_overlay_section_color', 'rgba(0, 76, 236, 0.9)');
                $testimonial_section_class="section-module testimonial";
            }?>
            <section class="<?php echo esc_attr($testimonial_section_class);?>" style="background-color:<?php echo $testimonial_overlay_section_color; ?>;">
                <div class="container">
                    <?php
                    $home_testimonial_section_title = get_theme_mod('home_testimonial_section_title', __('Proin Egestas', 'spicebox'));
                    $home_testimonial_section_discription = get_theme_mod('home_testimonial_section_discription', __('Nam Viverra Iaculis Finibus', 'spicebox'));
                    $home_testimonial_title = get_theme_mod('home_testimonial_title', __('Cras Vitae', 'spicebox'));
                    $home_testimonial_designation = get_theme_mod('home_testimonial_designation', __('Eu Suscipit', 'spicebox'));
                    $home_testimonial_thumb = get_theme_mod('home_testimonial_thumb', SPICEB_PLUGIN_URL . 'inc/honeypress/images/testimonial/testi1.jpg');
                    $home_testimonial_desc = get_theme_mod('home_testimonial_desc', __('Sed ut Perspiciatis Unde Omnis Iste Sed ut perspiciatis unde omnis iste natu error sit voluptatem accu tium neque fermentum veposu miten a tempor nise. Duis autem vel eum iriure dolor in hendrerit in vulputate velit consequat reprehender in voluptate velit esse cillum duis dolor fugiat nulla pariatur.', 'spicebox'));
                    ?>
                    <?php if ($home_testimonial_section_title != '' || $home_testimonial_section_discription != '') { ?>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12">
                                <div class="section-header text-center">
                                    <div class="section-separator white border-center"></div>
                                    <p class="section-subtitle text-white">
                                        <?php echo esc_attr($home_testimonial_section_title); ?>
                                    </p>
                                    <h2 class="section-title text-white">
                                        <?php echo esc_attr($home_testimonial_section_discription); ?>
                                    </h2>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="row">
                        <?php
                        honeypress_testimonial_variant();
                        ?>
                    </div>
                </div>
            </section>
            <div class="clearfix"></div>
            <?php
        }
    }

endif;
if (function_exists('spiceb_honeypress_testimonial')) {
    $section_priority = apply_filters('honeypress_section_priority', 13, 'spiceb_honeypress_testimonial');
    add_action('spiceb_honeypress_sections', 'spiceb_honeypress_testimonial', absint($section_priority));
}

// Testimonial Variant
if (!function_exists('honeypress_testimonial_variant')) :

    function honeypress_testimonial_variant() {
        $home_testimonial_title = get_theme_mod('home_testimonial_title', __('Cras Vitae', 'spicebox'));
        $home_testimonial_designation = get_theme_mod('home_testimonial_designation', __('Eu Suscipit', 'spicebox'));
        $theme = wp_get_theme();
        if ($theme->name == 'HoneyBee') {
           $home_testimonial_thumb = get_theme_mod('home_testimonial_thumb', SPICEB_PLUGIN_URL . 'inc/honeypress/images/testimonial/user1.jpg');
        } else{
            $home_testimonial_thumb = get_theme_mod('home_testimonial_thumb', SPICEB_PLUGIN_URL . 'inc/honeypress/images/testimonial/testi1.jpg');
        }
        $home_testimonial_desc = get_theme_mod('home_testimonial_desc', __('Sed ut Perspiciatis Unde Omnis Iste Sed ut perspiciatis unde omnis iste natu error sit voluptatem accu tium neque fermentum veposu miten a tempor nise. Duis autem vel eum iriure dolor in hendrerit in vulputate velit consequat reprehender in voluptate velit esse cillum duis dolor fugiat nulla pariatur.', 'spicebox'));

        if ($theme->name == 'HoneyWaves') {
            ?>
            <div id="testimonial-carousel2" >
                <div class="item">
                    <article class="testmonial-block">
                        <?php if ($home_testimonial_thumb != '') { ?>
                            <figure class="avatar">
                                <img src="<?php echo $home_testimonial_thumb; ?>" class="img-fluid rounded-circle" alt="img">
                            </figure>
                        <?php } ?>
                        <div class="entry-content">
                            <p class="text-white"><?php echo $home_testimonial_desc; ?></p>
                        </div>
                        <figcaption>
                            <cite class="name"><?php echo $home_testimonial_title; ?></cite>
                            <span class="designation"><?php echo $home_testimonial_designation; ?></span>
                        </figcaption>
                    </article>
                </div>
            </div>
        <?php }elseif ($theme->name == 'HoneyBee') {?>
            <div class="col-lg-12 col-md-12 col-sm-12">
                    <article class="testmonial-block">
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <?php if ($home_testimonial_thumb != '') { ?>
                            <figure class="avatar">
                                <img src="<?php echo $home_testimonial_thumb; ?>" class="img-fluid" alt="img">
                            </figure>
                        <?php } ?>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-12 testimonial-text">
                            <div class="testmonial-content">
                                <div class="entry-content">
                                    <p class="text-white"><?php echo $home_testimonial_desc; ?></p>
                                </div>
                                <figcaption>
                                    <cite class="name"><?php echo $home_testimonial_title; ?></cite>
                                    <span class="designation"><?php echo $home_testimonial_designation; ?></span>
                                </figcaption>
                            </div>
                        </div>
                    </article>
            </div>
        <?php } elseif ($theme->name == 'HoneyPress') {
            ?>
            <div class="col-md-12">
                <article class="testmonial-block text-center">
                    <?php if ($home_testimonial_thumb != '') { ?>
                        <figure class="avatar">
                            <img src="<?php echo $home_testimonial_thumb; ?>" class="img-fluid rounded-circle" alt="img">
                        </figure>
                    <?php } ?>
                    <div class="entry-content">
                        <p class="text-white"><?php echo $home_testimonial_desc; ?></p>
                    </div>
                    <figcaption>
                        <cite class="name"><?php echo $home_testimonial_title; ?></cite>
                        <span class="designation"><?php echo $home_testimonial_designation; ?></span>
                    </figcaption>
                </article>
            </div>
        <?php } elseif ($theme->name == 'Radix Multipurpose') {
            ?>
            <div id="testimonial-carousel4">
                <div class="item">
                    <article class="testmonial-block text-center">
                        <?php if ($home_testimonial_thumb != '') { ?>
                            <figure class="avatar">
                                <img src="<?php echo $home_testimonial_thumb; ?>" class="img-fluid rounded-circle" alt="img">
                            </figure>
                        <?php } ?>
                        <figcaption>
                            <cite class="name"><?php echo $home_testimonial_title; ?></cite>
                            <span class="designation"><?php echo $home_testimonial_designation; ?></span>
                        </figcaption>
                        <div class="entry-content">
                            <p class="text-white"><?php echo $home_testimonial_desc; ?></p>
                        </div>
                    </article>
                </div>
            </div>
        <?php } elseif ($theme->name == 'Bizhunt') {
            ?>
            <div class="col-md-12">
                <article class="testmonial-block text-center">
                    <?php if ($home_testimonial_thumb != '') { ?>
                        <figure class="avatar">
                            <img src="<?php echo $home_testimonial_thumb; ?>" class="img-fluid rounded-circle" alt="img">
                        </figure>
                    <?php } ?>

                    <figcaption>
                        <cite class="name"><?php echo $home_testimonial_title; ?></cite>
                        <span class="designation"><?php echo $home_testimonial_designation; ?></span>
                    </figcaption>

                    <div class="entry-content">
                        <p class="text-white"><?php echo $home_testimonial_desc; ?></p>
                    </div>

                </article>
            </div>
        <?php } elseif ($theme->name == 'Tromas') {
            ?>
            <div id="testimonial-carousel5" class="col-md-12">
                <div class="item">
                    <article class="testmonial-block5 text-center">
                        <?php if ($home_testimonial_thumb != '') { ?>
                            <figure class="avatar">
                                <img src="<?php echo $home_testimonial_thumb; ?>" class="img-fluid rounded-circle" alt="img">
                            </figure>
                        <?php } ?>
                        <div class="entry-content">
                            <p class="text-white"><?php echo $home_testimonial_desc; ?></p>
                        </div>
                        <figcaption>
                            <cite class="name"><?php echo $home_testimonial_title; ?></cite>
                            <span class="designation"><?php echo $home_testimonial_designation; ?></span>
                        </figcaption>
                    </article>
                </div>
            </div>
        <?php } elseif ($theme->name == 'Honeypress Dark') {
            ?>
            <div id="testimonial-carousel5">
      					<div class="col-lg-12">
      						<article class="testmonial-block5">
      							<figure class="avatar">
      								<img src="<?php echo $home_testimonial_thumb; ?>" class="img-fluid rounded-circle" alt="<?php echo $home_testimonial_title; ?>" >
      							</figure>
      							<div class="entry-content">
      								<p><?php echo $home_testimonial_desc; ?></p>
      							</div>
      							<figcaption>
      								<cite class="name"><?php echo $home_testimonial_title; ?></cite>
      								<span class="designation"><?php echo $home_testimonial_designation; ?></span>
      							</figcaption>
      						</article>
      					</div>
            </div>
        <?php } else {
            ?>
            <div class="col-md-12">
                <article class="testmonial-block text-center">
                    <?php if ($home_testimonial_thumb != '') { ?>
                        <figure class="avatar">
                            <img src="<?php echo $home_testimonial_thumb; ?>" class="img-fluid rounded-circle" alt="img">
                        </figure>
                    <?php } ?>
                    <div class="entry-content">
                        <p class="text-white"><?php echo $home_testimonial_desc; ?></p>
                    </div>
                    <figcaption>
                        <cite class="name"><?php echo $home_testimonial_title; ?></cite>
                        <span class="designation"><?php echo $home_testimonial_designation; ?></span>
                    </figcaption>
                </article>
            </div>
            <?php
        }
    }






endif;
