<?php
/**
 * Services section for the homepage.
 */
if (!function_exists('spiceb_honeypress_service')) :

    function spiceb_honeypress_service() {
        $theme = wp_get_theme();
        if ($theme->name == 'HoneyWaves') {
            $service_variant_class = 'section-module services4 service_wrapper';
        } elseif ($theme->name == 'Radix Multipurpose') {
            $service_variant_class = 'section-module services3 service_wrapper';
        } elseif ($theme->name == 'Bizhunt') {
            $service_variant_class = 'section-module services2 service_wrapper';
        } elseif ($theme->name == 'Tromas') {
            $service_variant_class = 'section-module services5 service_wrapper';
        } elseif ($theme->name == 'HoneyBee') {
            $service_variant_class = 'section-module services7';
        } else {
            $service_variant_class = 'section-module services';
        }
        $home_service_section_enabled = get_theme_mod('home_service_section_enabled', 'on');
        $home_service_section_title = get_theme_mod('home_service_section_title', __('Etiam et Urna?', 'spicebox'));
        $home_service_section_discription = get_theme_mod('home_service_section_discription', __('Fusce Sed Massa', 'spicebox'));
        $honeypress_service_content = get_theme_mod('honeypress_service_content', spiceb_honeypress_get_service_default());
        $section_is_empty = empty($honeypress_service_content) && empty($home_service_section_discription) && empty($home_service_section_title);
        if ($home_service_section_enabled != 'off') {
            ?>
            <section class="<?php echo $service_variant_class; ?>">
                <div class="container">		
            <?php if (($home_service_section_title) || ($home_service_section_discription) != '') { ?>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12">
                                <div class="section-header text-center">
                                    <div class="section-separator border-center"></div>
                        <?php if (!empty($home_service_section_title) || is_customize_preview()) : ?>
                                        <p class="section-subtitle">
                    <?php echo $home_service_section_title; ?>
                                        </p>
                                    <?php endif; ?>
                                    <?php if ($home_service_section_discription) { ?>
                                        <h2 class="section-title">
                                            <?php echo $home_service_section_discription; ?>
                                        </h2>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                                <?php
                                }
                                spiceb_honeypress_service_content($honeypress_service_content);
                                ?>
                </div>
            </section>
                <?php
                }
            }

        endif;

        function spiceb_honeypress_service_content($honeypress_service_content, $is_callback = false) {
            if (!$is_callback) {
                ?>
        <?php
    }
    if (!empty($honeypress_service_content)) :

        $allowed_html = array(
            'br' => array(),
            'em' => array(),
            'strong' => array(),
            'b' => array(),
            'i' => array(),
        );

        $honeypress_service_content = json_decode($honeypress_service_content);
        if (!empty($honeypress_service_content)) {
            $theme = wp_get_theme();
            if ($theme->name == 'HoneyPress') {
                $post_class = 'text-center wow flipInX animated';
            } elseif ($theme->name == 'HoneyWaves') {
                $post_class = '';
            } elseif ($theme->name == 'Radix Multipurpose') {
                $post_class = 'text-center';
            } elseif ($theme->name == 'Bizhunt') {
                $post_class = '';
            } elseif ($theme->name == 'Tromas') {
                $post_class = '';
            } else {
                $post_class = 'text-center wow flipInX animated';
            }
            $i = 1;
            echo '<div class="row" id="service_content_section">';
            foreach ($honeypress_service_content as $service_item) :
                $icon = !empty($service_item->icon_value) ? $service_item->icon_value : '';
                $image = !empty($service_item->image_url) ? $service_item->image_url : '';
                $title = !empty($service_item->title) ? $service_item->title : '';
                $text = !empty($service_item->text) ? $service_item->text : '';
                $link = !empty($service_item->link) ? $service_item->link : '';
                $color = !empty($service_item->color) ? $service_item->color : '';
                $choice = !empty($service_item->choice) ? $service_item->choice : 'customizer_repeater_icon';
                $open_new_tab = !empty($service_item->open_new_tab) ? $service_item->open_new_tab : 'no';
                ?>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="post <?php echo $post_class; ?>"<?php if ($theme->name == 'HoneyPress') {
                    echo 'data-wow-delay=".5s"';
                } ?>>
                <?php if ($choice == 'customizer_repeater_image') { ?>	
                    <?php if (!empty($image)) : ?>
                                <figure class="post-thumbnail">	

                                <?php if (!empty($link)) : ?>
                                        <a href="<?php echo esc_url($link); ?>" <?php if ($open_new_tab == 'yes') {
                                echo 'target="_blank"';
                            } ?>>
                                    <?php endif; ?>
                                        <img class="services_cols_mn_icon"
                                             src="<?php echo esc_url($image); ?>" <?php if (!empty($title)) : ?> alt="<?php echo esc_attr($title); ?>" title="<?php echo esc_attr($title); ?>" <?php endif; ?> />
                                        <?php if (!empty($link)) : ?>
                                        </a>
                                             <?php endif; ?>
                                         <?php endif; ?>
                            </figure>
                            <?php } ?>

                        <?php if ($choice == 'customizer_repeater_icon') { ?>
                            <?php if (!empty($icon)) : ?>
                                <figure class="post-thumbnail">	
                                <?php if (!empty($link)) : ?>
                                        <a href="<?php echo esc_url($link); ?>" <?php if ($open_new_tab == 'yes') {
                                echo 'target="_blank"';
                            } ?> >
                                        <?php endif; ?>

                                        <i class="fa <?php echo esc_html($icon); ?> txt-pink"></i>	
                                        <?php if (!empty($link)) : ?>
                                        </a>
                                    <?php endif; ?>
                                </figure>	
                            <?php endif; ?>
                        <?php } ?>

                        <?php if (!empty($title)) : ?>
                            <div class="entry-header">
                                <h4 class="entry-title"><?php if (!empty($link)) : ?>
                                        <a href="<?php echo esc_url($link); ?>" <?php if ($open_new_tab == 'yes') {
                                    echo 'target="_blank"';
                                } ?> ><?php endif; ?><?php echo esc_html($title); ?><?php if (!empty($link)) : ?></a>
                    <?php endif; ?>
                                </h4>
                            </div>

                        <?php endif; ?>
                        <?php if (!empty($link)) : ?>
                            </a>
                        <?php endif; ?>
                <?php if (!empty($text)) : ?>

                            <div class="entry-content">
                                <p><?php echo wp_kses(html_entity_decode($text), $allowed_html); ?></p>
                            </div>
                <?php endif; ?>
                    </div>
                </div>
                <?php
                if ($i % apply_filters('honeypress_service_per_row_no', 3) == 0) {
                    echo '</div><!-- /.row -->';
                    echo '<div class="row">';
                }
                $i++;

            endforeach;
            echo '</div>';
        }// End if().
    endif;
    if (!$is_callback) {
        ?>
        <?php
    }
}

/**
 * Get default values for service section.
 *
 * @since 1.1.31
 * @access public
 */
function spiceb_honeypress_get_service_default() {

    return apply_filters(
            'honeypress_service_content', json_encode(
                    array(
                        array(
                            'icon_value' => 'fa-laptop',
                            'title' => esc_html__('Suspendisse Tristique', 'spicebox'),
                            'text' => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem amet dolore ut labore et tempor', 'spicebox',
                            'choice' => 'customizer_repeater_icon',
                            'link' => '#',
                            'open_new_tab' => 'no',
                        ),
                        array(
                            'icon_value' => 'fa fa-cogs',
                            'title' => esc_html__('Blandit-Gravida', 'spicebox'),
                            'text' => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem amet dolore ut labore et tempor', 'spicebox',
                            'choice' => 'customizer_repeater_icon',
                            'link' => '#',
                            'open_new_tab' => 'no',
                        ),
                        array(
                            'icon_value' => 'fa fa-cog',
                            'title' => esc_html__('Justo Bibendum', 'spicebox'),
                            'text' => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem amet dolore ut labore et tempor',
                            'choice' => 'customizer_repeater_icon',
                            'link' => '#',
                            'open_new_tab' => 'no',
                        ),
                    )
            )
    );
}

if (function_exists('spiceb_honeypress_service')) {
    $section_priority = apply_filters('honeypress_section_priority', 12, 'spiceb_honeypress_service');
    add_action('spiceb_honeypress_sections', 'spiceb_honeypress_service', absint($section_priority));
}