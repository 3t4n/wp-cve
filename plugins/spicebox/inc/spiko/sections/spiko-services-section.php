<?php 
add_action('spiceb_spiko_services_action','spiceb_spiko_services_section');

function spiceb_spiko_services_section()
{   
$service_data = get_theme_mod('spiko_service_content');
if (empty($service_data)) {
    $service_data = json_encode(array(
        array(
            'icon_value' => 'fa-headphones',
            'title' => esc_html__('Suspendisse Tristique', 'spicebox'),
            'text' => esc_html__('Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.', 'spicebox'),
            'choice' => 'customizer_repeater_icon',
            'link' => '#',
            'open_new_tab' => 'no',
            'id' => 'customizer_repeater_56d7ea7f40b56',
        ),
        array(
            'icon_value' => 'fa-mobile',
            'title' => esc_html__('Blandit-Gravida', 'spicebox'),
            'text' => esc_html__('Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.', 'spicebox'),
            'choice' => 'customizer_repeater_icon',
            'link' => '#',
            'open_new_tab' => 'no',
            'id' => 'customizer_repeater_56d7ea7f40b66',
        ),
        array(
            'icon_value' => 'fa fa-cogs',
            'title' => esc_html__('Justo Bibendum', 'spicebox'),
            'text' => esc_html__('Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.', 'spicebox'),
            'choice' => 'customizer_repeater_icon',
            'link' => '#',
            'open_new_tab' => 'no',
            'id' => 'customizer_repeater_56d7ea7f40b86',
        ),
    ));
}
$spiko_service_section_title = get_theme_mod('home_service_section_title', __('Quisque Blandit', 'spicebox'));
$spiko_service_section_discription = get_theme_mod('home_service_section_discription', __('Fusce Sed Massa', 'spicebox'));
$spiko_service_section_enabled = get_theme_mod('home_service_section_enabled',true);

$theme=wp_get_theme();
if ('Spiko Dark' == $theme->name):
    $service_section_class = 'services2';
else:
    $service_section_class = 'services';
endif;

if($spiko_service_section_enabled ==true)
{       
?>
<section class="section-space bg-default-color <?php echo esc_attr($service_section_class); ?>">
    <div class="container">
        <?php if ($spiko_service_section_discription != '' || $spiko_service_section_title != '') {
            ?>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="section-header">
                    <?php if($spiko_service_section_discription != ''){ ?>
                    <p class="section-subtitle"><?php echo wp_kses_post($spiko_service_section_discription); ?></p>
                    <?php }?>
                    <?php if($spiko_service_section_title != ''){ ?>
                    <h2 class="section-title"><?php echo esc_html($spiko_service_section_title); ?></h2><div class="section-separator border-center"></div>
                    <?php } ?>
                    
                </div>
            </div>
        </div>
        <?php } ?>
        <div class="row">
            <?php
            $service_data = json_decode($service_data);
            if (!empty($service_data)) {
                foreach ($service_data as $service_team) {
                    $service_icon = !empty($service_team->icon_value) ? apply_filters('spiko_translate_single_string', $service_team->icon_value, 'Service section') : '';
                    $service_image = !empty($service_team->image_url) ? apply_filters('spiko_translate_single_string', $service_team->image_url, 'Service section') : '';
                    $service_title = !empty($service_team->title) ? apply_filters('spiko_translate_single_string', $service_team->title, 'Service section') : '';
                    $service_desc = !empty($service_team->text) ? apply_filters('spiko_translate_single_string', $service_team->text, 'Service section') : '';
                    $service_link = !empty($service_team->link) ? apply_filters('spiko_translate_single_string', $service_team->link, 'Service section') : '';
            ?>
            <div class="col-md-4 col-sm-6 col-xs-12">  
             <?php if ('Spiko Dark' == $theme->name){ ?> 
                <article class="post">
                        <?php if ($service_team->choice == 'customizer_repeater_icon') {
                                    if ($service_icon != '') { ?>
                                        <figure class="post-thumbnail">  
                                        <?php if ($service_link != '') { ?>
                                                <a <?php if ($service_team->open_new_tab == 'yes') {
                                                        echo "target='_blank'";
                                                    } ?> href="<?php echo esc_url($service_link); ?>">
                                                        <i class="fa <?php echo esc_attr($service_icon); ?>"></i>
                                                    </a>
                                                <?php } else { ?>
                                                    <a><i class="fa <?php echo esc_attr($service_icon); ?>"></i></a>
                                            <?php } ?>
                                        </figure>  
                                        <?php
                                    }
                                } else if ($service_team->choice == 'customizer_repeater_image') {
                                        if ($service_image != '') { ?> 
                                             <figure class="post-thumbnail">
                                        <?php
                                            if ($service_link != '') { ?>
                                                <a <?php if ($service_team->open_new_tab == 'yes') {
                                                            echo "target='_blank'";
                                                        } ?> href="<?php echo esc_url($service_link); ?>">
                                                <?php }
                                            ?>
                                            <img class='img-fluid' src="<?php echo esc_url($service_image); ?>">
                                            <?php if ($service_link != '') { ?>
                                                </a>
                                            <?php } 
                                        } ?>
                                    </figure>
                                 <?php  
                                 } 
                                ?>
                                <?php if ($service_title != "") { ?>
                                <div class="entry-header">
                                    <h4 class="entry-title">
                                        <?php if ($service_link != '') { ?>
                                            <a href="<?php echo esc_url($service_link); ?>" <?php if ($service_team->open_new_tab == 'yes') {
                                                    echo "target='_blank'";
                                                } ?>><?php } echo esc_html($service_title);
                                            if ($service_link != '') { ?></a>
                                        <?php } ?>
                                    </h4>
                                </div>
                                <?php
                                }
                                if ($service_desc != ""): ?>
                                <div class="entry-content">
                                    <p><?php echo wp_kses_post($service_desc); ?></p>    
                                </div>                  
                                <?php endif; ?>
                </article>
                <?php } 
                else { ?>
                <div class="card">
                    <div class="card-body">
                        <?php if ($service_team->choice == 'customizer_repeater_icon') {
                                    if ($service_icon != '') {
                                        ?>
                                        <p class="service-icon">
                                            <?php if ($service_link != '') { ?>
                                                <a <?php if ($service_team->open_new_tab == 'yes') {
                                                    echo "target='_blank'";
                                                } ?> href="<?php echo esc_url($service_link); ?>">
                                                    <i class="fa <?php echo esc_attr($service_icon); ?>"></i>
                                                </a>
                                            <?php } else { ?>
                                                <a><i class="fa <?php echo esc_attr($service_icon); ?>"></i></a>
                                        <?php } ?>
                                        </p>
                                    <?php
                                    }
                            } else if ($service_team->choice == 'customizer_repeater_image') {
                                    if ($service_image != '') {
                                        ?>
                                        <p class="service-icon"> 
                                                <?php if ($service_link != '') { ?>
                                                <a <?php if ($service_team->open_new_tab == 'yes') {
                                                        echo "target='_blank'";
                                                    } ?> href="<?php echo esc_url($service_link); ?>">
                                            <?php } ?>
                                                <img class='img-fluid' src="<?php echo esc_url($service_image); ?>">
                                        <?php if ($service_link != '') { ?>
                                                </a>
                                        <?php } ?>
                                        </p>
                                    <?php
                                    }
                                }
                            if ($service_title != "") { ?>
                                <h4 class="entry-title">
                                    <?php if ($service_link != '') { ?>
                                        <a href="<?php echo esc_url($service_link); ?>" <?php if ($service_team->open_new_tab == 'yes') {
                                                echo "target='_blank'";
                                            } ?>><?php } echo esc_html($service_title);
                                        if ($service_link != '') { ?></a>
                                    <?php } ?>
                                </h4>
                            <?php
                            }
                            if ($service_desc != ""): ?>
                               <p class="description"><?php echo wp_kses_post($service_desc); ?></p>                      
                            <?php endif; ?>
                    </div>
                </div>
                <?php
                } ?>
            </div>
        <?php
            }
        }
        ?>
        </div>
    </div>
</section>
<?php } ?>
<div class="clearfix"></div>
<?php //End of service section enable condition
} 
?>