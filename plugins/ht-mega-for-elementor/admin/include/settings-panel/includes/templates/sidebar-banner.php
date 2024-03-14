<?php 
ob_start(); 
?>
<div class="htoptions-sidebar-adds-area">
<?php 

$template_data = HTMega_Template_Library::instance()->get_templates_info();
    if( is_plugin_active('htmega-pro/htmega_pro.php') ){
        $htmega_license_title = apply_filters('htmega_license_title', 'lifetime' ); 
        if ( !str_contains( $htmega_license_title, 'Growth' ) && !str_contains( $htmega_license_title, 'Unlimited - Lifetime' ) ) {

            if( isset( $template_data['notices']['sidebar'][1]['status'] ) && !empty( $template_data['notices']['sidebar'][1]['status'] ) ){
                ?>
                <a href="<?php echo esc_url( $template_data['notices']['sidebar'][1]['bannerlink'] ); ?>" target="_blank">
                    <img class="htoptions-banner-img" src="<?php echo esc_url( $template_data['notices']['sidebar'][1]['bannerimage'] ); ?>" alt="<?php echo esc_attr__( 'HT Mega Addons', 'htmega-addons' ); ?>"/>
                </a>
                <?php
            }
        }
    }else{

        if( isset( $template_data['notices']['sidebar'][0]['status'] ) && !empty( $template_data['notices']['sidebar'][0]['status'] )){
            ?>
            <a href="<?php echo esc_url( $template_data['notices']['sidebar'][0]['bannerlink'] ); ?>" target="_blank">
                <img  class="htoptions-banner-img" src="<?php echo esc_url( $template_data['notices']['sidebar'][0]['bannerimage'] ); ?>" alt="<?php echo esc_attr__( 'HT Mega Addons', 'htmega-addons' ); ?>"/>
            </a>
         <?php 
        }
    }
    ?>
    <div class="htoption-rating-area">
        <div class="htoption-rating-icon">
            <img src="<?php echo esc_url(HTMEGA_ADDONS_PL_URL.'admin/assets/images/icon/rating.png'); ?>" alt="<?php echo esc_attr__( 'Rating icon', 'htmega-addons' ); ?>">
        </div>
        <div class="htoption-rating-intro">
            <?php echo esc_html__('If you’re loving how our product has helped your business, please let the WordPress community know by','htmega-addons'); ?> <a target="_blank" href="https://wordpress.org/support/plugin/ht-mega-for-elementor/reviews/?filter=5#new-post"><?php echo esc_html__( 'leaving us a review on our WP repository', 'htmega-addons' ); ?></a>. <?php echo esc_html__( 'Which will motivate us a lot.', 'htmega-addons' ); ?>
        </div>
    </div>

</div>
<?php echo apply_filters('htmega_sidebar_adds_banner', ob_get_clean() ); ?>