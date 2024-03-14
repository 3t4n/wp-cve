<?php
/**
 * Builds our admin page.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// options for effects
$wtfe_element_effects 		= get_option( 'wtfe_element_effects', '' );
// options for elements
$wtfe_advanced_headings 	= get_option( 'wtfe_advanced_headings', '' );
$wtfe_countdown 			= get_option( 'wtfe_countdown', '' );
$wtfe_darkmode			 	= get_option( 'wtfe_darkmode', '' );
$wtfe_qr_code 				= get_option( 'wtfe_qr_code', '' );    

if ( isset( $_POST[ 'wtfe_template_submit' ] ) ) {
	$wtfe_template_title		  = $_POST[ 'wtfe_template_title' ];
	$wtfe_template_title 		  = str_replace(' ', '_', $wtfe_template_title);
	$wtfe_template_title		  = strtolower($wtfe_template_title);
	$wtfe_template_url            = 'https://wpkoi.com/wet/json/' . esc_attr( $_POST[ 'wtfe_template_id' ] ) . '.json';
	$wtfe_template_response       = wp_remote_get( $wtfe_template_url, array( 'timeout' => 60 ) );
	
	if ( is_wp_error( $wtfe_template_response ) ) {
		return;
	}
	
	$wtfe_template_body           = wp_remote_retrieve_body( $wtfe_template_response ); ?>
<a id="downloadWPKoiTemplate" style="display:none"></a>
<script type="text/javascript">
jQuery(function($) {
var obj = <?php echo $wtfe_template_body; ?>;
var dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(obj));
var dlAnchorElem = document.getElementById('downloadWPKoiTemplate');
dlAnchorElem.setAttribute("href",     dataStr     );
dlAnchorElem.setAttribute("download", "<?php echo esc_attr( $wtfe_template_title ); ?>.json");
dlAnchorElem.click();
});
</script>
<?php } ?>
<div id="download-json"></div>
<div id="wet-page-body">
	<div class="wet-title-area">
    	<a href="<?php echo esc_url( 'https://wpkoi.com/' ); ?>" target="_blank"><img src="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_URL . 'assets/img/wpkoi-logo.png' ); ?>" class="wet-logo" /></a>
    	<div class="wet-title-content">
        	<h1><?php esc_html_e( 'WPKoi Templates for Elementor', 'wpkoi-templates-for-elementor' );?></h1>
            <p><?php esc_html_e( 'Give the spirit to Your site!', 'wpkoi-templates-for-elementor' );?></p>
        </div>
    </div>
    
    <div class="wet-cases">
    	<div class="wet-cases-half">
        	<h2><?php esc_html_e( 'How to import WPKoi templates to Your site', 'wpkoi-templates-for-elementor' );?></h2>
    		<p><?php esc_html_e( 'Download Your selected one from here and upload it to Your page.', 'wpkoi-templates-for-elementor' );?></p>
            <p><?php esc_html_e( '1. Find Your template here then click on the download button.', 'wpkoi-templates-for-elementor' );?><br /><br /><?php esc_html_e( '2. Create a new page and select Elementor Full Width page template. If You use a WPKoi theme, make the Page Builder Container full width at the bottom also. After that publish the page and edit with Elementor.', 'wpkoi-templates-for-elementor' );?><br /><br /><?php esc_html_e( '3. Upload the downloaded json file to Your templates with Elementor Library. ', 'wpkoi-templates-for-elementor' );?><a href="https://docs.elementor.com/article/60-library" target="_blank"><?php esc_html_e( 'Here You can check how to use the Elementor library', 'wpkoi-templates-for-elementor' );?></a><br /><br /><?php esc_html_e( '4. Replace the content and edit the template as You want!', 'wpkoi-templates-for-elementor' );?></p>
        </div>
        <div class="wet-cases-half">
        	<div class="wet-video">
        		<iframe src="https://www.youtube.com/embed/sMxh5SPkPqU" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        </div>
    </div>
	<div class="wet-templates-loop">
	<?php
		
	// Get the list of templates
	require WPKOI_TEMPLATES_FOR_ELEMENTOR_DIRECTORY . 'inc/template-list.php';
	
	$templates = wpkoi_templates_for_elementor_template_list();
	
	if ( ! empty( $templates ) ) {
		foreach ( $templates as $template_data ) {
			$scrimg = $template_data['thumbnail'];
			$scrimg =  WPKOI_TEMPLATES_FOR_ELEMENTOR_URL . 'assets/thumbnails/' . $scrimg;
	?>
        <div class="wetl-template">
            <div class="wetl-template-inner">
                <div class="wpkoi-ptemp">
                    <a href="<?php echo esc_url( $template_data['url'] ); ?>" target="_blank">
                        <div class="wpkoi-home-preview">
                            <img height="auto" src="<?php echo esc_url( $scrimg ); ?>" style="transition: top 3s ease-out 0s; top: 0px;">
                        </div>
                    </a>
                    <a href="<?php echo esc_url( $template_data['url'] ); ?>" target="_blank">
                        <div class="wpkoi-ptemp-main-title">
                           <?php echo esc_html($template_data['title']); ?>
                        </div>
                    </a>
                </div>
                <div class="clearfix"></div>
                <form method="post" action="">
                	<input spellcheck="false" class="wtfe-template-input" id="wtfe_template_id" name="wtfe_template_id" value="<?php echo esc_html( $template_data['template_id'] ); ?>" />
                	<input spellcheck="false" class="wtfe-template-input" id="wtfe_template_title" name="wtfe_template_title" value="<?php echo esc_html( $template_data['title'] ); ?>" />
                	<input type="submit" class="button button-primary wtfe-template-submit" name="wtfe_template_submit" value="<?php _e( 'Download', 'wpkoi-templates-for-elementor' );?>" />
    			</form>
                <a href="https://wpkoi.com/wet/json/<?php echo esc_html( $template_data['template_id'] ); ?>.json" target="_blank" class="wet-dl-json-btn"><?php _e( 'Download manually', 'wpkoi-templates-for-elementor' );?></a>
                <div class="clearfix"></div>
            </div>
        </div>
	<?php
		}
	}
	?>
    </div>
</div>
<div id="wet-page-sidebar">
    <div class="wpkoi-review wet-sidebar-element">
        <h3><?php esc_html_e( 'Help with You review', 'wpkoi-templates-for-elementor' ); ?></h3>
        <p><?php esc_html_e( 'If You like WPKoi Templates plugin, show it to the world with Your review. Your feedback helps a lot.', 'wpkoi-templates-for-elementor' ); ?></p>
        <a href="<?php echo esc_url('https://wordpress.org/support/plugin/wpkoi-templates-for-elementor/reviews/?rate=5#new-post'); ?>" class="wpkoi-admin-button" target="_blank"><?php esc_html_e( 'Add my review', 'wpkoi-templates-for-elementor' ); ?></a>
    </div>
    
    <div class="wpkoi-social wet-sidebar-element">
        <h3><?php esc_html_e( 'WPKoi on Facebook', 'wpkoi-templates-for-elementor' ); ?></h3>
        <p><?php esc_html_e( 'If You want to get useful infos about WPKoi products, follow WPKoi on Facebook.', 'wpkoi-templates-for-elementor' ); ?></p>
        <a href="<?php echo esc_url('https://www.facebook.com/wpkoithemes/'); ?>" class="wpkoi-admin-button" target="_blank"><?php esc_html_e( 'Go to Facebook', 'wpkoi-templates-for-elementor' ); ?></a>
    </div>
    
    <div class="wpkoi-disable-elements wet-sidebar-element">
    <?php if ( ( !defined('WPKOI_ELEMENTS_PATH' ) ) && ( ! function_exists( 'add_wpkoi_elements_elements' ) ) && ( ! function_exists( 'add_asagi_premium_elements' ) ) && ( ! function_exists( 'add_bekko_premium_elements' ) ) && ( ! function_exists( 'add_chagoi_premium_elements' ) ) && ( ! function_exists( 'add_lovewp_premium_elements' ) ) && ( ! function_exists( 'add_goshiki_premium_elements' ) ) && ( ! function_exists( 'add_ochiba_premium_elements' ) ) && ( ! function_exists( 'add_koromo_premium_elements' ) ) && ( ! function_exists( 'add_kohaku_premium_elements' ) ) ) { ?>
        <form method="post" action="options.php">
        <h3><?php esc_html_e( 'Switch Your unused effects off!', 'wpkoi-templates-for-elementor' ); ?></h3>
        <p class="wet-de-p"><?php esc_html_e( 'Here You can switch off the WPKoi Effects for Elementor builder if You don˙t want to use. These effects used for elements, sections or columns.', 'wpkoi-templates-for-elementor' ); ?></p>
        <div class="wet-de-e">
            <label class="switch">
              <input id="wtfe_element_effects" name="wtfe_element_effects" type="checkbox"<?php if ( $wtfe_element_effects == true ){ ?> checked<?php } ?> >
              <span class="slider"></span>
            </label>
            <p><?php esc_html_e( 'Element Effects', 'wpkoi-templates-for-elementor' ); ?></p>
        </div>
        
        <h3 class="switch-margin-top"><?php esc_html_e( 'Switch Your unused elements off!', 'wpkoi-templates-for-elementor' ); ?></h3>
        <p class="wet-de-p"><?php esc_html_e( 'Here You can switch off the WPKoi Elements for Elementor builder if You don˙t want to use.', 'wpkoi-templates-for-elementor' ); ?></p>
        <div class="wet-de-e">
            <label class="switch">
              <input id="wtfe_advanced_headings" name="wtfe_advanced_headings" type="checkbox"<?php if ( $wtfe_advanced_headings == true ){ ?> checked<?php } ?> >
              <span class="slider"></span>
            </label>
            <p><?php esc_html_e( 'Advanced Headings', 'wpkoi-templates-for-elementor' ); ?></p>
        </div>
        <div class="wet-de-e">
            <label class="switch">
              <input id="wtfe_countdown" name="wtfe_countdown" type="checkbox"<?php if ( $wtfe_countdown == true ){ ?> checked<?php } ?> >
              <span class="slider"></span>
            </label>
            <p><?php esc_html_e( 'Countdown', 'wpkoi-templates-for-elementor' ); ?></p>
        </div>
        <div class="wet-de-e">
            <label class="switch">
              <input id="wtfe_darkmode" name="wtfe_darkmode" type="checkbox"<?php if ( $wtfe_darkmode == true ){ ?> checked<?php } ?> >
              <span class="slider"></span>
            </label>
            <p><?php esc_html_e( 'Darkmode', 'wpkoi-templates-for-elementor' ); ?></p>
        </div>
        <div class="wet-de-e">
            <label class="switch">
              <input id="wtfe_qr_code" name="wtfe_qr_code" type="checkbox"<?php if ( $wtfe_qr_code == true ){ ?> checked<?php } ?> >
              <span class="slider"></span>
            </label>
            <p><?php esc_html_e( 'QR Code', 'wpkoi-templates-for-elementor' ); ?></p>
        </div>
        <input type="submit" class="button button-primary" name="wtfe_submit" value="<?php _e( 'Save', 'wpkoi-templates-for-elementor' );?>" />
    	</form>
    <?php } else { ?>
    	<h3 class="wet-use-another"><?php esc_html_e( 'You use another version of WPKoi Elements! To use the Elements from WPKoi Templates for Elementor, turn off the Elementor Addon at Appearance-> "Your theme name" menu', 'wpkoi-templates-for-elementor' ); ?></h3>
    <?php } ?>
    </div>
</div>
<?php 

// Enable json upload
require WPKOI_TEMPLATES_FOR_ELEMENTOR_DIRECTORY . 'inc/enable-upload.php';
