<?php 
if (!defined('ABSPATH')) die('-1');

require_once 'helper.php';

require_once 'components/VCE_component.php';
require_once 'components/VCE_fmcSearch.php';
require_once 'components/VCE_fmcMarketStats.php';
require_once 'components/VCE_fmcPhotos.php';
require_once 'components/VCE_fmcAccount.php';
require_once 'components/VCE_fmcListingDetails.php';
require_once 'components/VCE_fmcLeadGen.php';
require_once 'components/VCE_fmcIDXLinksWidget.php';
require_once 'components/VCE_fmcLocationLinks.php';
require_once 'components/VCE_fmcSearchResults.php';

add_action( 'admin_enqueue_scripts', 'vce_fmc_admin' );
add_action( 'wp_enqueue_scripts', 'vce_fmc_wp', true );

add_action( 'vc_mapper_init_before', function(){		
  require_once 'params/text_field_tag.php';
  require_once 'params/select_tag.php';
  require_once 'params/section_title.php';
  require_once 'params/dropdown_tag.php';
  require_once 'params/sortable_list_tag.php';
  require_once 'params/location_tag.php';
  require_once 'params/checkbox_group_tag.php';
  require_once 'params/script_tag.php';
  require_once 'params/callback.php';
} );

function vce_fmc_admin(){
  wp_enqueue_style('vce_fmc_styles', plugins_url('wpb.css', __FILE__));
}

function vce_fmc_wp(){
  wp_enqueue_script( 'vce_fmc_scripts1', plugins_url( '../../assets/js/integration.js', __FILE__), array( 'jquery' ), FMC_PLUGIN_VERSION );
}
