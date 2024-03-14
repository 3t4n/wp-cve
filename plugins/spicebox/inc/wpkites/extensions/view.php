<?php
/**
 * Extensions
 */

if( ! function_exists( 'wpkites_plus_activate' ) ) {
    wp_enqueue_style( 'wpkites-info-screen-css', WPKITES_TEMPLATE_DIR_URI . '/admin/assets/css/welcome.css' );
	wp_enqueue_style( 'wpkites-info-css', WPKITES_TEMPLATE_DIR_URI . '/assets/css/bootstrap.css' );
}
?>
<div id="wpkites-extensions" class="text-center">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<h1 class="wpkites-info-title text-center">
					<?php esc_html_e('Extensions','spicebox') ?>
                </h1>
			</div>
		</div>
	</div>

	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 ">
				<div class="col-lg-4 col-md-4 col-sm-4 strater-div">   
					<div class="ribbon">
                        <img src="<?php echo SPICEB_PLUGIN_URL . 'inc/wpkites/images/free-bedge.png' ?>">
                    </div>
                 	<img src="<?php echo SPICEB_PLUGIN_URL . 'inc/wpkites/images/extensions/post-slider.png' ?>">   
                    <div class="col-lg-12 col-md-12 col-sm-12 panel-txt">
                        <div class="col-lg-6 col-md-12 col-sm-12 text-left">
                            <h4 class="strater-name">
                                <?php esc_html_e('Spice Post Slider','spicebox'); ?>
                            </h4>
                        </div>
                       	<div class="col-lg-6 col-md-12 col-sm-12 text-right">
                            <a href="https://spicethemes.com/spice-post-slider/" class="starter-btn" target="_blank">
                                <?php esc_html_e('View More','spicebox'); ?>
                            </a>
                        </div>
                    </div> 
                </div>

                <div class="col-lg-4 col-md-4 col-sm-4 strater-div">   
					<div class="ribbon">
                        <img src="<?php echo SPICEB_PLUGIN_URL . 'inc/wpkites/images/free-bedge.png' ?>">
                    </div>
                 	<img src="<?php echo SPICEB_PLUGIN_URL . 'inc/wpkites/images/extensions/social-share.png' ?>">   
                    <div class="col-lg-12 col-md-12 col-sm-12 panel-txt">
                        <div class="col-lg-6 col-md-12 col-sm-12 text-left">
                            <h4 class="strater-name">
                                <?php esc_html_e('Spice Social Share','spicebox'); ?>
                            </h4>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 text-right">
                            <a href="https://spicethemes.com/social-share/" class="starter-btn" target="_blank">
                                <?php esc_html_e('View More','spicebox'); ?>
                            </a>
                        </div>
                    </div> 
                </div>

                <div class="col-lg-4 col-md-4 col-sm-4 strater-div">   
					<div class="ribbon">
                        <img src="<?php echo SPICEB_PLUGIN_URL . 'inc/wpkites/images/pro-bedge.png' ?>">
                    </div>
                 	<img src="<?php echo SPICEB_PLUGIN_URL . 'inc/wpkites/images/extensions/white-label.png' ?>">   
                    <div class="col-lg-12 col-md-12 col-sm-12 panel-txt">
                        <div class="col-lg-6 col-md-12 col-sm-12 text-left">
                            <h4 class="strater-name">
                                <?php esc_html_e('Spice White Label','spicebox'); ?>
                            </h4>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 text-right">
                            <a href="https://spicethemes.com/white-label/" class="starter-btn" target="_blank">
                                <?php esc_html_e('View More','spicebox'); ?>
                            </a>
                        </div>
                    </div> 
                </div>

                <div class="col-lg-4 col-md-4 col-sm-4 strater-div"> 
					<div class="ribbon">
                        <img src="<?php echo SPICEB_PLUGIN_URL . 'inc/wpkites/images/pro-bedge.png' ?>">
                    </div>
                 	<img src="<?php echo SPICEB_PLUGIN_URL . 'inc/wpkites/images/extensions/side-panel.png' ?>">   
                    <div class="col-lg-12 col-md-12 col-sm-12 panel-txt">
                        <div class="col-lg-6 col-md-12 col-sm-12 text-left">
                            <h4 class="strater-name">
                                <?php esc_html_e('Spice Side Panel','spicebox'); ?>
                            </h4>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 text-right">
                            <a href="https://spicethemes.com/side-panel/" class="starter-btn" target="_blank">
                                <?php esc_html_e('View More','spicebox'); ?>
                            </a>
                        </div>
                    </div> 
                </div>

                <div class="col-lg-4 col-md-4 col-sm-4 strater-div">   
					<div class="ribbon">
                        <img src="<?php echo SPICEB_PLUGIN_URL . 'inc/wpkites/images/pro-bedge.png' ?>">
                    </div>
                 	<img src="<?php echo SPICEB_PLUGIN_URL . 'inc/wpkites/images/extensions/popup-login.png' ?>">   
                    <div class="col-lg-12 col-md-12 col-sm-12 panel-txt">
                        <div class="col-lg-6 col-md-12 col-sm-12 text-left">
                            <h4 class="strater-name">
                                <?php esc_html_e('Spice Popup Login','spicebox'); ?>
                            </h4>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 text-right">
                            <a href="https://spicethemes.com/popup-login/" class="starter-btn" target="_blank">
                                <?php esc_html_e('View More','spicebox'); ?>
                            </a>
                        </div>
                    </div> 
                </div>

                <div class="col-lg-4 col-md-4 col-sm-4 strater-div">   
                    <div class="ribbon">
                        <img src="<?php echo SPICEB_PLUGIN_URL . 'inc/wpkites/images/pro-bedge.png' ?>">
                    </div>
                    <img src="<?php echo SPICEB_PLUGIN_URL . 'inc/wpkites/images/extensions/instagram.png' ?>">   
                    <div class="col-lg-12 col-md-12 col-sm-12 panel-txt">
                        <div class="col-lg-6 col-md-12 col-sm-12 text-left">
                            <h4 class="strater-name">
                                <?php esc_html_e('Spice Instagram','spicebox'); ?>
                            </h4>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 text-right">
                            <a href="https://spicethemes.com/instagram/" class="starter-btn" target="_blank">
                                <?php esc_html_e('View More','spicebox'); ?>
                            </a>
                        </div>
                    </div> 
                </div>

			</div>
		</div>
	</div>
</div>