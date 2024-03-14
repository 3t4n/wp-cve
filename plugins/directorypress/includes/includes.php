<?php 
/**
 * @package    DirectoryPress
 * @subpackage DirectoryPress/includes
 * @author     Designinvento <developers@designinvento.net>
 */
 
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'constants.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-directorypress-loader.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-directorypress-i18n.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/posttypes.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/image-cropping.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/core/reset-password/class-reset-password.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/core/terms/categories-functions.php';		
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-directorypress-admin.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/directorypress-panel.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/directorypress-admin-display.php';	
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/core/validation/class-validation.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/core/listing/post.php';		
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/core/listing/class-backend.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/core/listing/class-listing.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/core/listing/class-payment.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/core/location/class-locations-core.php';		
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-directorypress-media.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/core/location/class-locations.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/core/location/class-locations-depths.php';		
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/core/fields/class_fields_backend.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/core/fields/class_fields.php';		
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/core/packages/class-packages.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-directorypress-directorytypes.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-directorypress-public.php';	
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/directorypress-shortcodes/directorypress_main.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/directorypress-shortcodes/directorypress_listing.php';
//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/directorypress-shortcodes/directorypress_map.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/directorypress-shortcodes/directorypress_categories.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/directorypress-shortcodes/directorypress_locations.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/directorypress-shortcodes/directorypress_search.php';		
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-directorypress-ajax.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/directorypress_filters.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/core/search/class_search.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/core/search/class_filters.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/core/terms/class-terms.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/core/terms/class-terms-locations.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/core/terms/class-terms-categories.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/core/terms/class-term-validate.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/directorypress_functions.php';
if(directorypress_is_elementor_active()){
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/el_widgets_settings.php';
}
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/functions.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/wpml-functions.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/wc-functions.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/user-functions.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/email-functions.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/map-functions.php'; // part of map addon
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/dynamic-styling-functions.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/admin_info_strings.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/core/listing/functions.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/core/search/functions.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/core/fields/functions.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/core/terms/functions.php';

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/core/listing/payment-functions.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/directorypress_vc_config.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/directorypress_svg.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-directorypress-public.php';

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class_directorypress_author_profile.php';	

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'resource-constants.php';
//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'compare.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/db/install-db.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/db/update-db.php';