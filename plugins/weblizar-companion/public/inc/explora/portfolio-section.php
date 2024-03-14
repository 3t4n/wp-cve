<?php

defined( 'ABSPATH' ) or die();

class wl_companion_portfolios_explora
{
    
    public static function wl_companion_portfolios_explora_html() {
    ?>
        <div class="container-fluid w_portfolio space blog_gallery">
            <?php if ( ! empty ( get_theme_mod( 'explora_portfolio_title' ) ) )  { ?> 
                <div class="row wc_heading">
                    <h1 class="section_heading explora_portfolio_title"><?php echo get_theme_mod( 'explora_portfolio_title', 'Recent Works '); ?></h1>       
                </div>
            <?php }  ?>
            <?php if ( ! empty ( get_theme_mod('explora_portfolio_data' ) ) ) {?>
                <div class="row">
                                  
                    <ul class="portfolio-items list-unstyled" id="grid">
                        <?php 
                        $name_arr = unserialize(get_theme_mod( 'explora_portfolio_data'));
                        foreach ( $name_arr as $key => $value ) {
                        ?>
                            <li class="col-md-4 col-sm-4 col-xs-6 w_port two-colom">
                                <figure class="portfolio-item">         
                                    <div class="img-thumbnail">
                                        <img src="<?php echo esc_url($value['portfolio_image']); ?>" alt="explora_image" height="280" width="440" class="img-responsive wp-post-image">
                                        <div class="w_overlay">             
                                            <a class="photobox_a" href="<?php echo esc_url($value['portfolio_image']); ?>"><i class="fa fa-arrows-v icon"></i></a>
                                            <h3><a href="<?php  echo esc_url($value['portfolio_link']);  ?>"><?php esc_html_e($value['portfolio_name'],WL_COMPANION_DOMAIN);  ?></a></h3>
                                            <span><?php  esc_html_e($value['portfolio_desc'],WL_COMPANION_DOMAIN);  ?></span>
                                        </div>
                                    </div>
                                </figure>
                            </li>           
                        <?php } ?>
                        <li class="col-md-4 col-sm-4 col-xs-6 shuffle_sizer"></li>
                    </ul>
                
                </div>
            <?php } ?>
        </div>
        <!-- Portfolio End -->
        
    <?php
    }
}
?>