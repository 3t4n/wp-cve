<?php
/**
 * Starter Sites
 */

if( ! function_exists( 'wpkites_plus_activate' ) ) {
    wp_enqueue_style( 'wpkites-info-screen-css', WPKITES_TEMPLATE_DIR_URI . '/admin/assets/css/welcome.css' );
	wp_enqueue_style( 'wpkites-info-css', WPKITES_TEMPLATE_DIR_URI . '/assets/css/bootstrap.css' );
}
?>
<div id="starter-sites" class="text-center">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<h1 class="wpkites-info-title text-center m-top-30">
					<?php esc_html_e('Starter Sites','spicebox') ?>
                </h1>
			</div>
		</div>
	</div>

	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="col-md-4 col-sm-4 col-xs-12 strater-div">   
					<div class="ribbon">
                        <img src="<?php echo SPICEB_PLUGIN_URL . 'inc/wpkites/images/pro-bedge.png' ?>">
                    </div>
                 	<img src="https://spicethemes.com/startersites/thumbnail/photography/thumb.png">   
                    <div class="col-md-12 col-sm-12 col-xs-12 panel-txt">
                        <div class="col-md-6 col-sm-12 col-xs-12 text-left">
                            <h4 class="strater-name">
                                <?php esc_html_e('Photography','spicebox'); ?>
                            </h4>
                        </div>
                       	<div class="col-md-6 col-sm-12 col-xs-12 text-right">
                            <a href="https://photography-wpkites.spicethemes.com/" class="starter-btn" target="_blank">
                                <?php esc_html_e('Demo','spicebox'); ?>
                            </a>
                        </div>
                    </div> 
                </div>

                <div class="col-md-4 col-sm-4 col-xs-12 strater-div">   
					<div class="ribbon">
                        <img src="<?php echo SPICEB_PLUGIN_URL . 'inc/wpkites/images/pro-bedge.png' ?>">
                    </div>
                 	<img src="https://spicethemes.com/startersites/thumbnail/job-portal/thumb.png">   
                    <div class="col-md-12 col-sm-12 col-xs-12 panel-txt">
                        <div class="col-md-6 col-sm-12 col-xs-12 text-left">
                            <h4 class="strater-name">
                                <?php esc_html_e('Job Portal','spicebox'); ?>
                            </h4>
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12 text-right">
                            <a href="https://job-portal-wpkites.spicethemes.com/" class="starter-btn" target="_blank">
                                <?php esc_html_e('Demo','spicebox'); ?>
                            </a>
                        </div>
                    </div> 
                </div>

                <div class="col-md-4 col-sm-4 col-xs-12 strater-div">   
					<div class="ribbon">
                        <img src="<?php echo SPICEB_PLUGIN_URL . 'inc/wpkites/images/pro-bedge.png' ?>">
                    </div>
                 	<img src="https://spicethemes.com/startersites/thumbnail/restaurant/thumb.png">   
                    <div class="col-md-12 col-sm-12 col-xs-12 panel-txt">
                        <div class="col-md-6 col-sm-12 col-xs-12 text-left">
                            <h4 class="strater-name">
                                <?php esc_html_e('Restaurant','spicebox'); ?>
                            </h4>
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12 text-right">
                            <a href="https://food-restaurant-wpkites.spicethemes.com/" class="starter-btn" target="_blank">
                                <?php esc_html_e('Demo','spicebox'); ?>
                            </a>
                        </div>
                    </div> 
                </div>

                <div class="col-md-4 col-sm-4 col-xs-12 strater-div">   
					<div class="ribbon">
                        <img src="<?php echo SPICEB_PLUGIN_URL . 'inc/wpkites/images/pro-bedge.png' ?>">
                    </div>
                 	<img src="https://spicethemes.com/startersites/thumbnail/corporate/thumb.png">   
                    <div class="col-md-12 col-sm-12 col-xs-12 panel-txt">
                        <div class="col-md-6 col-sm-12 col-xs-12 text-left">
                            <h4 class="strater-name">
                                <?php esc_html_e('Corporate','spicebox'); ?>
                            </h4>
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12 text-right">
                            <a href="https://corporate-wpkites.spicethemes.com/" class="starter-btn" target="_blank">
                                <?php esc_html_e('Demo','spicebox'); ?>
                            </a>
                        </div>
                    </div> 
                </div>

                <div class="col-md-4 col-sm-4 col-xs-12 strater-div">   
					<div class="ribbon">
                        <img src="<?php echo SPICEB_PLUGIN_URL . 'inc/wpkites/images/pro-bedge.png' ?>">
                    </div>
                 	<img src="https://spicethemes.com/startersites/thumbnail/business/thumb.png">   
                    <div class="col-md-12 col-sm-12 col-xs-12 panel-txt">
                        <div class="col-md-6 col-sm-12 col-xs-12 text-left">
                            <h4 class="strater-name">
                                <?php esc_html_e('Business','spicebox'); ?>
                            </h4>
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12 text-right">
                            <a href="https://business-wpkites.spicethemes.com/" class="starter-btn" target="_blank">
                                <?php esc_html_e('Demo','spicebox'); ?>
                            </a>
                        </div>
                    </div> 
                </div>

			</div>
		</div>
	</div>
</div>