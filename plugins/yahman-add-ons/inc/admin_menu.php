<?php
defined( 'ABSPATH' ) || exit;
/**
 * Admin Page
 *
 * @package YAHMAN Add-ons
 */

function yahman_addons_option_sanitize ( $input ) {

  $option = array();

  $allowed_html = array(
    'a' => array(
      'href' => array (),
      'target' => array()
    ),
    'br' => array(),
    'strong' => array(),
    'b' => array(),
    'span' => array(),
  );

  foreach ( $input as $key => $val ) {
    if ( is_array ( $val ) ){
      $key = sanitize_text_field ( $key );
      $option[$key] = yahman_addons_option_sanitize ( $val );
    } else {
      $key = sanitize_text_field ( $key );
      if($key === 'text' || $key === 'preconnect_url'){
        $option[$key] = wp_kses($val, $allowed_html);
      }else{

        $option[$key] = sanitize_text_field ( $val );
      }

    }
  }
  return $option;
}

?>
<style>
  .ya_loading {
   width: 60px;
   height: 60px;
   border: 10px solid #f6f2ef;
   border-top-color: #00b9eb;
   border-radius: 50%;
   animation: loading_spin 1.2s linear 0s infinite;


   text-align: center;
   z-index: 10;
   position:absolute;

   top: 0;
   bottom: 0;
   left: 0;
   right: 0;
   margin: auto;
 }
 @keyframes loading_spin {
   0% {transform: rotate(0deg);}
   100% {transform: rotate(360deg);}
 }
 .ya_loading_bg{
   width: 100%;
   height: 100%;

   z-index: 5;

   position: fixed;
   top: 0;
   left: 0;
   right: 0;
   bottom: 0;
   background-color: rgba(0,0,0,0.90);
   overflow: hidden;
   overflow-y: auto;
   -webkit-overflow-scrolling: touch;
   -webkit-backface-visibility: hidden;
   backface-visibility: hidden;

   -webkit-box-sizing: border-box;
   -moz-box-sizing: border-box;
   -o-box-sizing: border-box;
   -ms-box-sizing: border-box;
   box-sizing: border-box;
 }
</style>

<div class="ya_loading_bg"><div class="ya_loading"></div></div>

<script>
  function to_top() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
  }
</script>

<input id="ya_pv" class="tabs" type="radio" name="tab_item" checked="">
<input id="ya_javascript" class="tabs" type="radio" name="tab_item">
<input id="ya_ga" class="tabs" type="radio" name="tab_item">
<input id="ya_google_ad" class="tabs" type="radio" name="tab_item">
<input id="ya_sns" class="tabs" type="radio" name="tab_item">
<input id="ya_cta_social" class="tabs" type="radio" name="tab_item">
<input id="ya_share" class="tabs" type="radio" name="tab_item">
<input id="ya_profile" class="tabs" type="radio" name="tab_item">
<input id="ya_toc" class="tabs" type="radio" name="tab_item">
<input id="ya_related_posts" class="tabs" type="radio" name="tab_item">
<input id="ya_sitemap" class="tabs" type="radio" name="tab_item">
<input id="ya_seo" class="tabs" type="radio" name="tab_item">
<input id="ya_blogcard" class="tabs" type="radio" name="tab_item">
<input id="ya_widget" class="tabs" type="radio" name="tab_item">
<input id="ya_robot" class="tabs" type="radio" name="tab_item">
<input id="ya_pwa" class="tabs" type="radio" name="tab_item">
<input id="ya_faster" class="tabs" type="radio" name="tab_item">
<input id="ya_other" class="tabs" type="radio" name="tab_item">


<div id="yahman_addons_header" class="yahman_addons_header">
  <div class="ya_flex ya_ai_c ya_jc_c">
    <img width="254" height="80" src="<?php echo YAHMAN_ADDONS_URI; ?>assets/images/yahman-add-ons.svg" alt="YAHMAN Add-ons" style="margin: 20px auto 4px;">
  </div>
  <div class="ya_admin_edit_version">
    <?php echo YAHMAN_ADDONS_VERSION; ?>
  </div>

  <div class="ya_flex ya_ai_c ya_o_s_t">
    <label id="ya_pv_label" class="tab_item" for="ya_pv"><?php esc_html_e('PV','yahman-add-ons'); ?></label>
    <label id="ya_javascript_label" class="tab_item" for="ya_javascript"><?php esc_html_e('JS','yahman-add-ons'); ?></label>
    <label id="ya_ga_label" class="tab_item" for="ya_ga"><?php esc_html_e('Google Analytics','yahman-add-ons'); ?></label>
    <label id="ya_google_ad_label" class="tab_item" for="ya_google_ad"><?php esc_html_e('Google AdSense','yahman-add-ons'); ?></label>
    <label id="ya_sns_label" class="tab_item" for="ya_sns"><?php esc_html_e('Social','yahman-add-ons'); ?></label>
    <label id="ya_cta_social_label" class="tab_item" for="ya_cta_social"><?php esc_html_e('CTA','yahman-add-ons'); ?></label>
    <label id="ya_share_label" class="tab_item" for="ya_share"><?php esc_html_e('Share','yahman-add-ons'); ?></label>
    <label id="ya_profile_label" class="tab_item" for="ya_profile"><?php esc_html_e('Profile','yahman-add-ons'); ?></label>
    <label id="ya_toc_label" class="tab_item" for="ya_toc"><?php esc_html_e('TOC','yahman-add-ons'); ?></label>
    <label id="ya_related_posts_label" class="tab_item" for="ya_related_posts"><?php esc_html_e('RP','yahman-add-ons'); ?></label>
    <label id="ya_sitemap_label" class="tab_item" for="ya_sitemap"><?php esc_html_e('Site map','yahman-add-ons'); ?></label>
    <label id="ya_seo_label" class="tab_item" for="ya_seo"><?php esc_html_e('SEO','yahman-add-ons'); ?></label>
    <label id="ya_blogcard_label" class="tab_item" for="ya_blogcard"><?php esc_html_e('Blog Card','yahman-add-ons'); ?></label>
    <label id="ya_widget_label" class="tab_item" for="ya_widget"><?php esc_html_e('Widget','yahman-add-ons'); ?></label>
    <label id="ya_robot_label" class="tab_item" for="ya_robot"><?php esc_html_e('Search Engine','yahman-add-ons'); ?></label>
    <label id="ya_pwa_label" class="tab_item" for="ya_pwa"><?php esc_html_e('PWA','yahman-add-ons'); ?></label>
    <label id="ya_faster_label" class="tab_item" for="ya_faster"><?php esc_html_e('Faster','yahman-add-ons'); ?></label>
    <label id="ya_other_label" class="tab_item" for="ya_other"><?php esc_html_e('Other','yahman-add-ons'); ?></label>
  </div>
</div>

<div id="yahman_addons" class="yahman_addons_wrap">

  <?php

    //Save when have $_POST['yahman_addons'])
  if ( isset($_POST['yahman_addons_nonce']) && $_POST['yahman_addons_nonce'] ) {
    if( check_admin_referer('yahman_addons','yahman_addons_nonce') ) {

      $sanitize_option = array();

      $sanitize_option = yahman_addons_option_sanitize ( wp_unslash( $_POST['yahman_addons'] ) ) ;

      update_option('yahman_addons', $sanitize_option );

      
      if( !empty( $_POST['yahman_addons_reset'] )){

        $reset_option = yahman_addons_option_sanitize ( wp_unslash( $_POST['yahman_addons_reset'] ) ) ;

        
        require_once YAHMAN_ADDONS_DIR . 'inc/remove_cache.php';

        if( isset($reset_option['faster']['cache']) )
          yahman_addons_remove_all_cache();

        
        if( isset($reset_option['pv']['count']) ){

          $reset_post_ids = explode(',', $reset_option['pv']['count']);
          $period_names = array('all','yearly','monthly','weekly','daily');

          foreach( $reset_post_ids as $reset_post_id ) {

            foreach ($period_names as $period_name) {
              delete_post_meta( (int)$reset_post_id, '_yahman_addons_pv_'.$period_name);
              delete_post_meta( (int)$reset_post_id, '_yahman_addons_coverage_period_'.$period_name);
            }

          }
        }



      }

      ?>
      <div id="message" class="updated notice notice-success is-dismissible notice-alt updated-message"><p><strong><?php esc_html_e('Options saved.','yahman-add-ons'); ?></strong></p></div>
      <?php

    }


  }

  ?>



  <form action="" method="post">
    <?php
    wp_nonce_field('yahman_addons','yahman_addons_nonce');
    require_once YAHMAN_ADDONS_DIR . 'inc/admin/option_key.php';
    require_once YAHMAN_ADDONS_DIR . 'inc/social-list.php';

    $option = get_option('yahman_addons');
    $option_key = yahman_addons_option_key();
    $option_checkbox = yahman_addons_option_checkbox();

    $tab_style = '';

    $admin_key = array(
      'page_view',
      'javascript',
      'ga',
      'google_ad',
      'social',
      'cta_social',
      'social_share',
      'profile',
      'toc',
      'related_posts',
      'sitemap',
      'seo',
      'blog_card',
      'pwa',
      'widget',
      'search_engine',
      'faster',
      'other',
    );

    foreach ($admin_key as $key) {

      if(file_exists(YAHMAN_ADDONS_DIR . 'inc/admin/admin_'.$key.'.php'))
        require_once YAHMAN_ADDONS_DIR . 'inc/admin/admin_'.$key.'.php';

      $function_name = 'yahman_addons_admin_'.$key;
      if(function_exists($function_name))
        $function_name( $option , $option_key ,$option_checkbox );

      $tab_style .= '#ya_'.$key.':checked ~ form #ya_'.$key.'_content,';
    }

    echo '<style type="text/css">'.substr($tab_style, 0, -1).'{display:block;}</style>';

    ?>
    <p class="submit"><input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save','yahman-add-ons'); ?>" /></p>
  </form>
  <div class="ya_sub_menu ya_box_design">
    <a href="https://wordpress.org/support/plugin/yahman-add-ons/" class="" target="_blank">
      <?php esc_html_e('Support Forum','yahman-add-ons'); ?>
      <p style="margin: 4px 0 0;">
        <i class="fa-external-link" aria-hidden="true" style=""></i> wordpress.org
      </p>
    </a>
  </div>
</div>
<?php
