<?php 
add_action('spiceb_wpblack_services_action','spiceb_wpblack_services_section');

function spiceb_wpblack_services_section()
{   
$service_data = get_theme_mod('wpblack_service_content');
if (empty($service_data)) {
    $service_data = json_encode(array(
        array(
            'icon_value' => 'fa-headphones',
            'title' => esc_html__('Suspendisse Tristique', 'spicebox'),
            'text' => esc_html__('Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.', 'spicebox'),
            'choice' => 'customizer_repeater_icon',
            'button_text' => esc_html__('Learn More','spice-box'),
            'link' => '#',
            'open_new_tab' => 'no',
            'id' => 'customizer_repeater_56d7ea7f40b56',
        ),
        array(
            'icon_value' => 'fa-mobile',
            'title' => esc_html__('Blandit-Gravida', 'spicebox'),
            'text' => esc_html__('Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.', 'spicebox'),
            'choice' => 'customizer_repeater_icon',
            'button_text' => esc_html__('Learn More','spice-box'),
            'link' => '#',
            'open_new_tab' => 'no',
            'id' => 'customizer_repeater_56d7ea7f40b66',
        ),
        array(
            'icon_value' => 'fa fa-cogs',
            'title' => esc_html__('Justo Bibendum', 'spicebox'),
            'text' => esc_html__('Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.', 'spicebox'),
            'choice' => 'customizer_repeater_icon',
            'button_text' => esc_html__('Learn More','spice-box'),
            'link' => '#',
            'open_new_tab' => 'no',
            'id' => 'customizer_repeater_56d7ea7f40b86',
        ),
    ));
}
$wpblack_service_section_title = get_theme_mod('home_service_section_title', __('Quisque Blandit', 'spicebox'));
$wpblack_service_section_discription = get_theme_mod('home_service_section_discription', __('Fusce Sed Massa', 'spicebox'));
$wpblack_service_section_enabled = get_theme_mod('home_service_section_enabled',true);

$service_section_class = 'services';
$service_secton_button_title=get_theme_mod('service_secton_button_title',esc_html__('Duis Aute','spice-box'));
$service_section_link=get_theme_mod('service_section_link','#');
$service_secton_button_target=get_theme_mod('service_secton_button_target',true);
if($wpblack_service_section_enabled ==true)
{       
?>
<section class="section-space <?php echo esc_attr($service_section_class); ?> bg-default-lite">
    <div class="container">
        <?php if ($wpblack_service_section_discription != '' || $wpblack_service_section_title != '') {?>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="section-header">
                    <?php if ($wpblack_service_section_discription != '') { ?>
                    <p class="section-subtitle"><?php echo esc_html($wpblack_service_section_discription); ?></p>
                    <?php } ?>
                    <?php if ($wpblack_service_section_title != '') { ?>
                    <h2 class="section-title"><?php echo esc_html($wpblack_service_section_title); ?></h2>
                    <?php } ?>
                    <div class="section-separator border-center"></div>                    
                </div>
            </div>
        </div>
        <?php } ?>

        <div class="row">
            <?php
            $service_data = json_decode($service_data);
            if (!empty($service_data)) {
                foreach ($service_data as $service_team) {
                    $service_icon = !empty($service_team->icon_value) ? apply_filters('wpblack_translate_single_string', $service_team->icon_value, 'Service section') : '';
                    $service_image = !empty($service_team->image_url) ? apply_filters('wpblack_translate_single_string', $service_team->image_url, 'Service section') : '';
                    $service_title = !empty($service_team->title) ? apply_filters('wpblack_translate_single_string', $service_team->title, 'Service section') : '';
                    $service_desc = !empty($service_team->text) ? apply_filters('wpblack_translate_single_string', $service_team->text, 'Service section') : '';
                    $service_link = !empty($service_team->link) ? apply_filters('wpblack_translate_single_string', $service_team->link, 'Service section') : '';
                    $service_button=!empty($service_team->button_text) ? apply_filters('wpblack_translate_single_string', $service_team->button_text, 'Service section') : '';
                    ?>
                    <div class="col-md-4 col-sm-6 col-xs-12">  
                        <div class="card">
                            <div class="overlay"></div>
                            <div class="card-body">
                                <?php if ($service_team->choice == 'customizer_repeater_icon') {
                                    if($service_icon != '') { ?>
                                        <p class="service-icon"> 
                                        <i class="fa <?php echo esc_attr($service_icon); ?>"></i>
                                        </p>  
                                        <?php
                                    }
                                }else if ($service_team->choice == 'customizer_repeater_image') {
                                    if ($service_image != '') { ?> 
                                        <p class="service-image">
                                            <img class='img-fluid' src="<?php echo esc_url($service_image); ?>"></a></p>
                                    <?php } 
                                } 
                                if ($service_title != "") { ?>
                                    <h4 class="entry-title">
                                        <?php if ($service_link != '') { ?>
                                            <a href="<?php echo esc_url($service_link); ?>" <?php if ($service_team->open_new_tab == 'yes') {echo "target='_blank'";} ?>><?php } echo esc_html($service_title);
                                            if ($service_link != '') { ?></a>
                                        <?php } ?>
                                    </h4>
                                    <?php
                                }
                                if ($service_desc != ""): ?>
                                <p class="description"><?php echo wp_kses_post($service_desc); ?></p>
                                <?php endif; ?>
                                <?php if ($service_button != '') { ?>
                                    <a class="btn-small" href="<?php echo esc_url($service_link); ?>" <?php if ($service_team->open_new_tab == 'yes') {echo "target='_blank'";} ?>><?php echo esc_html($service_button);?><i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }?>
        </div>
        <?php
        if($service_secton_button_title !=''){?>
            <div class="text-center service-btn">
                <a href="<?php echo esc_url($service_section_link); ?>" class="btn-small btn-default"<?php if ($service_secton_button_target == true) {echo "target='_blank'";} ?> alt="Check-it-out"><?php echo esc_html($service_secton_button_title); ?><i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
            </div>
        <?php }?>
    </div>
</section>
<?php } ?>
<div class="clearfix"></div>
<?php //End of service section enable condition
} 
?>