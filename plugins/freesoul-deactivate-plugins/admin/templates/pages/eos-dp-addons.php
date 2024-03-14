<?php
/**
 * Template Addons.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

// Calback function for the FDP Add-ons page.
function eos_dp_addons_callback() {
	$addons = array(
		'freesoul-deactivate-plugins-pro' => array(
			'title'      => 'FDP PRO',
			'type'       => 'premium',
			'plugin_url' => 'https://freesoul-deactivate-plugins.com/',
			'category'   => array( 'optimization', 'cleanup', 'debugging', 'plugins_management' ),
		),
		'asset-preloader'                 => array(
			'title'    => 'Asset Preloader',
			'type'     => 'free',
			'category' => array( 'optimization' ),
		),
		'content-no-cache'                => array(
			'title'    => 'Content No Cache',
			'type'     => 'free',
			'category' => array( 'optimization' ),
		),
		'defer-transictional-emails-for-woocommerce' => array(
			'title'    => 'Defer transactional emails',
			'type'     => 'free',
			'category' => array( 'optimization' ),
		),
		'disable-global-style'            => array(
			'title'    => 'Disable global style',
			'type'     => 'free',
			'category' => array( 'optimization', 'cleanup' ),
		),
		'editor-cleanup-for-avada'        => array(
			'title'    => 'Editor Cleanup For Avada',
			'type'     => 'free',
			'category' => array( 'optimization', 'cleanup', 'debugging' ),
		),
		'editor-cleanup-for-divi-builder' => array(
			'title'    => 'Editor Cleanup For Divi Builder',
			'type'     => 'free',
			'category' => array( 'optimization', 'cleanup', 'debugging' ),
		),
		'editor-cleanup-for-elementor'    => array(
			'title'    => 'Editor Cleanup For Elementor',
			'type'     => 'free',
			'category' => array( 'optimization', 'cleanup', 'debugging' ),
		),
		'editor-cleanup-for-flatsome'     => array(
			'title'    => 'Editor Cleanup For Flatsome',
			'type'     => 'free',
			'category' => array( 'optimization', 'cleanup', 'debugging' ),
		),
		'specific-content-for-mobile'     => array(
			'title'    => 'Specific Content For Mobile',
			'type'     => 'free',
			'category' => array( 'optimization', 'cleanup' ),
		),
		'editor-cleanup-for-oxygen'       => array(
			'title'    => 'Editor Cleanup For Oxygen',
			'type'     => 'free',
			'category' => array( 'optimization', 'cleanup', 'debugging' ),
		),
		'editor-cleanup-for-wpbakery'     => array(
			'title'    => 'Editor Cleanup For WPBakery',
			'type'     => 'free',
			'category' => array( 'optimization', 'cleanup' ),
		),
		'inline-image-base64'             => array(
			'title'    => 'Inline image base64',
			'type'     => 'free',
			'category' => array( 'optimization' ),
		),
		'lazy-load-control-for-elementor' => array(
			'title'    => 'Lazy load control for Elementor',
			'type'     => 'free',
			'category' => array( 'optimization' ),
		),
		'mu-manager'                      => array(
			'title'    => 'MU Manager',
			'type'     => 'free',
			'category' => array( 'plugins_management' ),
		),
		'plugversions'                    => array(
			'title'    => 'Plugversions',
			'type'     => 'free',
			'category' => array( 'plugins_management' ),
		),
	);
	?>
  <style id="fdp-addon-css">
  .fdp-addon-link:visited,.fdp-addon-link:active,.fdp-addon-link:focus{outline:none !important;color:inherit !important;text-decoration:none !important;border:none !important}
  #fdp-addons .eos-hidden{display:none !important}
  .fdp-addons-filter{border-radius:0 !important}
  section#fdp-addons{column-count:3;column-gap:20px;max-width:1024px;margin:0 auto}
  #fdp-addons h2{text-transform:capitalize;height:2.5em;letter-spacing:3px;font-size:23px;line-height:1.2em;text-align:center;padding:0 30px}
  .fdp-addon-wrp{text-align:center;transition:0.5s linear;position:relative;width:265px;display:inline-block;margin-bottom:24px;background-color:#253042 !important;padding:20px}
  .fdp-addon-wrp h2,.fdp-addon-wrp div,.fdp-addon-wrp a{text-decoration:none;color:#fff !important;display:inline-block !important}
  .fdp-addon-wrp .button{text-decoration:none;color:#253042 !important;border:1px solid #fff !important;border-radius:0;background-color:#fff !important;text-transform:uppercase;font-weight:bold;font-size:15px;letter-spacing:2.5px;padding:0 20px}
  .fdp-addon-premium{background:-webkit-linear-gradient(#d6b375,#a2824b)}
  #fdp-addons .fdp-addon-link{margin-bottom:32px !important;display:inline-block}
  .fdp-addon-wrp:hover{transform:scale(1.05)}
  .fdp-addon-img-wrp{padding:30px;background-size:225px;background-position:center center;background-repeat:no-repeat;background-color:#fff;width:180px;height:180px;margin-top:24px}
  .fdp-addon-readmore-wrp{text-align:center;padding-bottom:18px;margin-top:32px}
  .fdp-addon-readmore-wrp .button{font-size:17px;letter-spacing:1.5px}
  .fdp-addons-filter{border-radius:0}
  </style>
  <h1 style="text-align:center;margin-top:32px;text-transform:uppercase"><?php esc_html_e( 'Add-ons', 'freesoul-deactivate-plugins' ); ?></h1>
  <div id="fdp-addons-filters" style="text-align:center;margin-top:32px;margin-bottom:32px">
	<span class="fdp-addons-filter button fdp-filter-all eos-active" data-category="all"><?php esc_html_e( 'All', 'freesoul-deactivate-plugins' ); ?></span>
	<span class="fdp-addons-filter button fdp-filter-optimization" data-category="optimization"><?php esc_html_e( 'Optimization', 'freesoul-deactivate-plugins' ); ?></span>
	<span class="fdp-addons-filter button fdp-filter-cleanup" data-category="cleanup"><?php esc_html_e( 'Cleanup', 'freesoul-deactivate-plugins' ); ?></span>
	<span class="fdp-addons-filter button fdp-filter-debugging" data-category="debugging"><?php esc_html_e( 'Debugging', 'freesoul-deactivate-plugins' ); ?></span>
	<span class="fdp-addons-filter button fdp-filter-plugins_management" data-category="plugins_management"><?php esc_html_e( 'Plugin Management', 'freesoul-deactivate-plugins' ); ?></span>
  </div>
  <section id="fdp-addons">
	<?php
	foreach ( $addons as $addon => $arr ) {
		$icon_url   = 'free' === $arr['type'] || in_array( $addon, array( 'freesoul-deactivate-plugins-pro' ) ) ? 'https://ps.w.org/' . str_replace( '-pro', '', $addon ) . '/assets/icon-256x256.png' : EOS_DP_PLUGIN_URL . '/assets/img/' . $addon . '/icon-256x256.png';
		$plugin_url = 'free' === $arr['type'] ? 'https://wordpress.org/plugins/' . $addon . '/' : $arr['plugin_url'];
		?>
  <a class="fdp-addon-link fdp-addon-link-<?php echo 'free' === $arr['type'] ? 'free' : 'premium'; ?>" href="<?php echo esc_url( $plugin_url ); ?>" target="_<?php echo esc_attr( $addon ); ?>" rel="noopener">
	  <div id="fdp-addon-<?php echo esc_attr( $addon ); ?>" class="fdp-addon-wrp fdp-addon-
									<?php
									echo 'free' === $arr['type'] ? 'free' : 'premium';
									echo ' fdp-addon-' . esc_attr( implode( ' fdp-addon-', $arr['category'] ) );
									?>
									">
		<h2><?php echo esc_html( $arr['title'] ); ?></h2>
		<div class="fdp-addon-img-wrp" style="background-image:url(<?php echo esc_url( $icon_url ); ?>)"></div>
		<div class="fdp-addon-readmore-wrp">
		  <span class="button" href="<?php echo esc_url( $plugin_url ); ?>" target="_<?php echo esc_attr( $addon ); ?>" rel="noopener"><?php esc_html_e( 'Learn more', 'freesoul-deactivate-plugins' ); ?></span>
		</div>
	  </div>
	</a>
		<?php
	}
	?>
  </section>
  <script id="fdp-addons-js">
  function fdp_addons_filter(){
	var bs=document.getElementsByClassName('fdp-addons-filter'),ads=document.getElementsByClassName('fdp-addon-wrp'),n=0;
	for(n;n<bs.length;++n){
	  bs[n].addEventListener('click',function(){
		for(var b=0;b<bs.length;++b){
		  bs[b].className = bs[b].className.replace(' eos-active','');
		}
		this.className += ' eos-active';
		for(var k=0;k<ads.length;++k){
		  ads[k].className = ads[k].className.replace(' eos-hidden','');
		  if('all' !== this.dataset.category && ads[k].className.indexOf('fdp-addon-' + this.dataset.category)<0){
			ads[k].className += ' eos-hidden';
		  }
		}
	  });
	}
  }
  fdp_addons_filter();
  </script>
	<?php

}
