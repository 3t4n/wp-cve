<?php
/**
 *
 */

namespace Reuse\Builder;

class Reuse_Builder_Admin_Lacalize {
	public static function redq_admin_language() {

		/**
		 * Localize language files for js rendering
		 */
		$lang = array(
			'POST_TYPE' 														=> esc_html__('Post Type', 'reuse-builder'),
			'TAXONOMY' 															=> esc_html__('Taxonomy', 'reuse-builder'),
			'PLEASE_SELECT_ANY_POST_TYPE_YOU_WANT_TO_ADD_THIS_TAXONOMY'	 		=> esc_html__('Please select any post type you want to add this taxonomy', 'reuse-builder'),
			'PLEASE_SELECT_ANY_TAXONOMT_YOU_WANT_TO_ADD_THIS_TERM_META' 		=> esc_html__('Please select any taxonomy you want to add this term meta', 'reuse-builder'),
			'ENABLE_HIERARCHY' 													=> esc_html__('Enable Hierarchy', 'reuse-builder'),
			'IF_YOU_WANT_TO_ENABLE_THE_TAXONOMY_HIERARCHY_SET_TRUE' 			=> esc_html__('If you want to enable the taxonomy hierarchy set true', 'reuse-builder'),
			'POST_FORMATS' 														=> esc_html__('Post Formats', 'reuse-builder'),
			'ENABLE_POST_FORMATS_INTO_THIS_POST' 								=> esc_html__('Enable post formats into this post.', 'reuse-builder'),
			'PAGE_ATTRIBUTES' 													=> esc_html__('Page Attributes', 'reuse-builder'),
			'ENABLE_PAGE_ATTRIBUTES_INTO_THIS_POST' 							=> esc_html__('Enable page attributes into this post.', 'reuse-builder'),
			'REVISIONS' 														=> esc_html__('Revisions', 'reuse-builder'),
			'ENABLE_REVISIONS_INTO_THIS_POST' 									=> esc_html__('Enable revisions into this post.', 'reuse-builder'),
			'COMMENTS' 															=> esc_html__('Comments', 'reuse-builder'),
			'ENABLE_COMMENTS_INTO_THIS_POST' 									=> esc_html__('Enable comments into this post.', 'reuse-builder'),
			'REST_API' 															=> esc_html__('Rest API', 'reuse-builder'),
			'REST_API_META' 															=> esc_html__('Rest API Meta', 'reuse-builder'),
			'ENABLE_REST_API_SUPPORT_FOR_THIS_POST_META' 									=> esc_html__('Enable Rest API Support For This Post Meta', 'reuse-builder'),
			'ENABLE_REST_API_SUPPORT_FOR_THIS_POST' 									=> esc_html__('Enable Rest API Support', 'reuse-builder'),
			'CUSTOM_FIELDS' 													=> esc_html__('Custom Fields', 'reuse-builder'),
			'ENABLE_CUSTOM_FIELDS_INTO_THIS_POST' 								=> esc_html__('Enable custom fields into this post.', 'reuse-builder'),
			'TRACKBACKS' 														=> esc_html__('Trackbacks', 'reuse-builder'),
			'ENABLE_TRACKBACKS_INTO_THIS_POST' 									=> esc_html__('Enable trackbacks into this post.', 'reuse-builder'),
			'EXCERPT' 															=> esc_html__('Excerpt', 'reuse-builder'),
			'ENABLE_EXCERPT_INTO_THIS_POST' 									=> esc_html__('Enable excerpt into this post.', 'reuse-builder'),
			'THUMBNAIL' 														=> esc_html__('Thumbnail', 'reuse-builder'),
			'ENABLE_THUMBNAIL_INTO_THIS_POST' 									=> esc_html__('Enable thumbnail into this post.', 'reuse-builder'),
			'AUTHOR'	 														=> esc_html__('Author', 'reuse-builder'),
			'ENABLE_AUTHOR_INTO_THIS_POST' 										=> esc_html__('Enable author into this post.', 'reuse-builder'),
			'EDITOR' 															=> esc_html__('Editor', 'reuse-builder'),
			'ENABLE_EDITOR_INTO_THIS_POST' 										=> esc_html__('Enable editor into this post.', 'reuse-builder'),
			'TITLE' 															=> esc_html__('Title', 'reuse-builder'),
			'ENABLE_TITILE_INTO_THIS_POST' 										=> esc_html__('Enable title into this post.', 'reuse-builder'),
			'ALL_ITEMS' 														=> esc_html__('All Items', 'reuse-builder'),
			'SINGULAR_NAME' 													=> esc_html__('Singular Name', 'reuse-builder'),
			'POST_SLUG' 														=> esc_html__('Post Slug', 'reuse-builder'),
			'IF_WANT_TO_CHANGE_THE_DEFAULT_ALL_ITEMS_NAME_ADD_THE_NAME_HERE' 	=> esc_html__('If want to change the default all items name, add the name here', 'reuse-builder'),
			'IF_WANT_TO_CHANGE_THE_DEFAULT_SINGULAR_NAME_ADD_THE_NAME_HERE' 	=> esc_html__('If want to change the default singular name, add the name here', 'reuse-builder'),
			'IF_WANT_TO_CHANGE_THE_DEFAULT_POST_SLUG_ADD_THE_NAME_HERE' 		=> esc_html__('If want to change the default post slug, add the slug here', 'reuse-builder'),
			'MENU_POSITION' 													=> esc_html__('Menu Position', 'reuse-builder'),
			'SELECT_THE_POST_TYPE_MENU_POSITION' 								=> esc_html__('Select the post type menu position.', 'reuse-builder'),
			'MENU_ICON' 														=> esc_html__('Menu Icon', 'reuse-builder'),
			'SELECT_MENU_ICON' 													=> esc_html__('Select a menu icon.', 'reuse-builder'),
			'BELOW_FIRST_SEPARATOR' 											=> esc_html__('Below First Separator', 'reuse-builder'),
			'BELOW_POSTS' 														=> esc_html__('Below Posts', 'reuse-builder'),
			'BELOW_MEDIA' 														=> esc_html__('Below Media', 'reuse-builder'),
			'BELOW_LINKS' 														=> esc_html__('Below Links', 'reuse-builder'),
			'BELOW_PAGES' 														=> esc_html__('Below Pages', 'reuse-builder'),
			'BELOW_COMMENTS' 													=> esc_html__('Below Comments', 'reuse-builder'),
			'BELOW_SECOND_SEPARATOR' 											=> esc_html__('Below Second Separator', 'reuse-builder'),
			'BELOW_PLUGINS' 													=> esc_html__('Below Plugins', 'reuse-builder'),
			'BELOW_USERS' 														=> esc_html__('Below Users', 'reuse-builder'),
			'BELOW_TOOLS' 														=> esc_html__('Below Tools', 'reuse-builder'),
			'BELOW_SETTINGS' 													=> esc_html__('Below Settings', 'reuse-builder'),
			'DEFAULT_ICON' 														=> esc_html__('Default Icon', 'reuse-builder'),
			'UPLOAD_ICON' 														=> esc_html__('Upload Icon', 'reuse-builder'),
			'ICON_TYPE' 														=> esc_html__('Icon Type', 'reuse-builder'),
			'SELECT_THE_DEFAULT_ICON_TYPE_OR_UPLOAD_A_NEW' 						=> esc_html__('Select the default icon type or upload a new.', 'reuse-builder'),
			'UPLOAD_CUSTOM_ICON' 												=> esc_html__('Upload Custom Icon', 'reuse-builder'),
			'YOU_CAN_UPLOAD_ANY_CUSTOM_IMAGE_ICON' 								=> esc_html__('You can upload any custom image icon.', 'reuse-builder'),
			'BUNDLE_COMPONENT' 													=> esc_html__('Bundle Component', 'reuse-builder'),
			'PICK_COLOR' 														=> esc_html__('Pick Color','reuse-builder'),
			'NO_RESULT_FOUND'	 												=> esc_html__('No result found', 'reuse-builder'),
			'SEARCH' 															=> esc_html__('search','reuse-builder'),
			'OPEN_ON_SELECTED_HOURS' 											=> esc_html__('Open on selected hours', 'reuse-builder'),
			'ALWAYS_OPEN' 														=> esc_html__('Always open', 'reuse-builder'),
			'NO_HOURS_AVAILABLE' 												=> esc_html__('No hours available', 'reuse-builder'),
			'PERMANENTLY_CLOSE' 												=> esc_html__('Permanently closed', 'reuse-builder'),
			'MONDAY' 															=> esc_html__('Monday', 'reuse-builder'),
			'TUESDAY' 															=> esc_html__('Tuesday', 'reuse-builder'),
			'WEDNESDAY' 														=> esc_html__('Wednesday', 'reuse-builder'),
			'THURSDAY' 															=> esc_html__('Thursday', 'reuse-builder'),
			'FRIDAY' 															=> esc_html__('Friday', 'reuse-builder'),
			'SATURDAY' 															=> esc_html__('Saturday', 'reuse-builder'),
			'SUNDAY' 															=> esc_html__('Sunday', 'reuse-builder'),
			'WRONG_PASS' 														=> esc_html__('Wrong Password', 'reuse-builder'),
			'PASS_MATCH' 														=> esc_html__('Password Matched', 'reuse-builder'),
			'CONFIRM_PASS' 														=> esc_html__('Confirm Password', 'reuse-builder'),
			'CURRENTLY_WORK' 													=> esc_html__('I currently work here', 'reuse-builder'),
		);

		return $lang;
	}

	public static function redq_admin_error() {
		/**
		 * Localize Error Message files for js rendering
		 */
	  	$error_message_list = array(
		  'notNull'   => esc_html__( 'The field should not be empty', 'reuse-builder'),
		  'email'     => esc_html__( 'The field should be email', 'reuse-builder'),
		  'isNumeric' => esc_html__( 'The field should be numeric', 'reuse-builder'),
		  'isURL'     => esc_html__( 'The field should be Url', 'reuse-builder'),
		);

		return $error_message_list;
	}

	public static function dynamic_page_builder_tab_list(){
		$tabs_in_dynamic_page = array();
		$tabs_in_dynamic_page['general'] 	= esc_html__( 'General Options', 'reuse-builder' );
		$tabs_in_dynamic_page['header'] 	= esc_html__( 'Header Options', 'reuse-builder' );
		$tabs_in_dynamic_page['background'] = esc_html__( 'Background Options', 'reuse-builder' );
		$tabs_in_dynamic_page['banner'] 	= esc_html__( 'Banner Options', 'reuse-builder' );
		$tabs_in_dynamic_page['listing'] 	= esc_html__( 'Listing Banner', 'reuse-builder' );
		$tabs_in_dynamic_page['sidebar'] 	= esc_html__( 'Sidebar Options', 'reuse-builder' );
		$tabs_in_dynamic_page['footer'] 	= esc_html__( 'Footer Options', 'reuse-builder' );
		$tabs_in_dynamic_page['copyright'] 	= esc_html__( 'Copyright Options', 'reuse-builder' );


		return $tabs_in_dynamic_page;
	}

	public static function dynamic_page_builder_data_provider(){

		// Header options

		$fields[] = array(
			'menuId' 	=> 'general',
			'id' 		=> 'choose_container_class',
		    'type' 		=> 'select',
		    'label' 	=> esc_html__( 'Choose Container class', 'reuse-builder' ),
		    'param' 	=> 'select',
		    'multiple' 	=> false,
		    'clearable' => false,
		    'options' 	=> array(
		      	'container' 	=> esc_html__('Container', 'reuse-builder'),
		      	'container-fluid' 		=> esc_html__('Container Fluid', 'reuse-builder'),
		    ),
		    'value' 	=> 'container',
		);

		$fields[] = array(
			'menuId' 	=> 'general',
			'id' 		=> 'choose_container_nopadding_class',
		    'type' 		=> 'select',
		    'label' 	=> esc_html__( 'Choose Container No Padding Class', 'reuse-builder' ),
		    'param' 	=> 'select',
		    'multiple' 	=> false,
		    'clearable' => false,
		    'options' 	=> array(
		      	'category-no-padding' 	=> esc_html__('Full Width Without Padding', 'reuse-builder'),
		      	'category-padding' 		=> esc_html__('Full Width With Padding', 'reuse-builder'),
		    ),
		    'value' 	=> 'category-padding',
		);

		$fields[] = array(
			'menuId' 	=> 'header',
			'id' 		=> 'header_options_from',
		    'type' 		=> 'select',
		    'label' 	=> esc_html__( 'Use Header Options From', 'reuse-builder' ),
		    'param' 	=> 'select',
		    'multiple' 	=> false,
		    'clearable' => false,
		    'options' 	=> array(
		      	'option_panel' 	=> esc_html__('Reuse Builder Option Panel', 'reuse-builder'),
		      	'local' 		=> esc_html__('Current Page Settings', 'reuse-builder'),
		    ),
		    'value' 	=> 'option_panel',
		);


		$fields[] = array(
			'id' => 'enable_header',
			'type' => 'switch', // switchalt
			'menuId' => 'header',
			'label' => ' Display Header ',
			'param' => 'enable',
			'value' => true,
		);

		$fields[] = array(
			'menuId' 	=> 'header',
			'id' 		=> 'reuseb_choose_header',
			'type' 		=> 'selectGroup',
			'subtype' 	=> 'airbnbCb',
			'label' 	=> esc_html__('Choose Header', 'reuse-builder'),
			'param' 	=> 'thisistheparam',
			'subtitle' 	=> esc_html__('Choose Header For This Page', 'reuse-builder'),
			'options' 	=> array(
				array(
					'value' 		=> 'header-one',
					'title' 		=> esc_html__('Header One', 'reuse-builder'),
					'ionClassName' 	=> 'ion-bag',
				),
				array(
					'value' 		=> 'header-two',
					'title' 		=> esc_html__('Header Two', 'reuse-builder'),
					'ionClassName' 	=> 'ion-bag',
				),
				array(
					'value' 		=> 'header-three',
					'title' 		=> esc_html__('Header Three', 'reuse-builder'),
					'ionClassName' 	=> 'ion-bag',
				),
				array(
					'value' 		=> 'header-four',
					'title' 		=> esc_html__('Header Four', 'reuse-builder'),
					'ionClassName' 	=> 'ion-bag',
				),
			),
			'multiple' 	=> false,
			'allButton' => false,
		);


		$fields[] = array(
			'id'		=> 'header_logo',
		    'type'		=> 'imageupload',
		    'label'		=> esc_html__( 'Logo', 'reuse-builder'),
		    'param'		=> 'imageupload',
		    'multiple'	=> false,
		    'menuId' 	=> 'header',
		);

		$fields[] = array(
			'id'		=> 'header_bg_image',
		    'type'		=> 'imageupload',
		    'label'		=> esc_html__( 'Background Image', 'reuse-builder'),
		    'param'		=> 'imageupload',
		    'subtitle'	=> esc_html__( 'Background Image', 'reuse-builder'),
		    'multiple'	=> false,
		    'menuId' 	=> 'header',
		);

		$fields[] = array(
			'menuId' 				=> 'header',
			'id'					=> 'header_bg_color',
		    'type' 					=> 'colorpicker',
		    'label' 				=> esc_html__( 'Background Color', 'reuse-builder'),
		    'param' 				=> 'Color',
		    'name' 					=> 'header_bg_color',
		    'default_color' 		=> 'true',
		    'data_default_color' 	=> '#000000',
		    'palettes' 				=> 'true',
		    'hide_control' 			=> 'true',
		);

		$fields[] = array(
			'id' 			=> 'header_height',
		    'type' 			=> 'text',
		    'label' 		=> esc_html__( 'Height', 'reuse-builder' ),
		    'param' 		=> 'text',
		    'repeat' 		=> false,
		    'value' 		=> '',
		    'placeholder' 	=> esc_html__( 'Header height', 'reuse-builder' ),
		    'menuId' 		=> 'header',
		);

		$fields[] = array(
			'id' 			=> 'header_width',
		    'type' 			=> 'text',
		    'label' 		=> esc_html__( 'Width', 'reuse-builder' ),
		    'param' 		=> 'text',
		    'repeat' 		=> false,
		    'value' 		=> '',
		    'placeholder' 	=> esc_html__( 'Header width', 'reuse-builder' ),
		    'menuId' 		=> 'header',
		);



		//Footer options

		$fields[] = array(
			'menuId' 		=> 'footer',
			'id' 			=> 'footer_options_from',
		    'type' 			=> 'select',
		    'label' 		=> esc_html__( 'Use Footer Options From', 'reuse-builder'),
		    'param' 		=> 'select',
		    'multiple' 		=> false,
		    'clearable' 	=> false,
		    'options' 		=> array(
		      	'option_panel' 	=> esc_html__('Reuse Builder Option Panel', 'reuse-builder'),
		      	'local' 		=> esc_html__('Current Page Settings', 'reuse-builder'),
		    ),
		    'value' 		=> 'option_panel',
		);

		$fields[] = array(
			'menuId' 	=> 'footer',
			'id' 		=> 'enable_footer',
			'type' 		=> 'switch',
			'label' 	=> esc_html__( 'Display Footer', 'reuse-builder' ),
			'param' 	=> 'enable',
			'value' 	=> true,
		);

		$fields[] = array(
			'menuId' => 'footer',
			'id' => 'reuseb_choose_footer',
			'type' => 'selectGroup',
			'subtype' => 'airbnbCb',
			'label' => esc_html__('Choose Footer', 'reuse-builder'),
			'param' => 'thisistheparam',
			'subtitle' => esc_html__('Choose Footer For This Page', 'reuse-builder'),
			'options' => array(
				array(
					'value' => 'footer-one',
					'title' => esc_html__('footer One', 'reuse-builder'),
					'ionClassName' => 'ion-bag',
				),
				array(
					'value' => 'footer-two',
					'title' => __('Footer Two', 'reuse-builder'),
					'ionClassNaesc_htmlme' => 'ion-bag',
				),
				array(
					'value' => 'footer-three',
					'title' => esc_html__('footer Three', 'reuse-builder'),
					'ionClassName' => 'ion-bag',
				),
			),
			'multiple' => false,
			'allButton' => false,
		);

		$fields[] = array(
			'menuId' 	=> 'footer',
			'id'		=> 'footer_bg_image',
		    'type'		=> 'imageupload',
		    'label'		=> esc_html__( 'Footer Background Image', 'reuse-builder' ),
		    'param'		=> 'imageupload',
		    'multiple'	=> false,
		);

		$fields[] = array(
			'menuId' 				=> 'footer',
			'id' 					=> 'footer_bg_color',
		    'type' 					=> 'colorpicker',
		    'label' 				=> esc_html__( 'Footer Background Color', 'reuse-builder' ),
		    'param' 				=> 'Color',
		    'name' 					=> 'footer_bg_color',
		    'default_color' 		=> true,
		    'data_default_color' 	=> '#000000',
		    'palettes' 				=> true,
		    'hide_control' 			=> true,
		);

		$fields[] = array(
			'id' 			=> 'footer_height',
		    'type' 			=> 'text',
		    'label' 		=> esc_html__( 'Height', 'reuse-builder' ),
		    'param' 		=> 'text',
		    'repeat' 		=> false,
		    'value' 		=> '',
		    'placeholder' 	=> esc_html__( 'Footer height',  'reuse-builder' ),
		    'menuId' 		=> 'footer',
		);

		$fields[] = array(
			'id' 			=> 'footer_width',
		    'type' 			=> 'text',
		    'label' 		=> esc_html__( 'Footer Width', 'reuse-builder' ),
		    'param' 		=> 'text',
		    'repeat' 		=> false,
		    'value' 		=> '',
		    'placeholder' 	=> esc_html__( 'Footer width', 'reuse-builder' ),
		    'menuId' 		=> 'footer',
		);

		$fields[] = array(
			'menuId' 	=> 'footer',
			'id' 		=> 'enable_widgets',
			'type' 		=> 'switch', // switchalt
			'label'		=> esc_html__( 'Enable Widgets', 'reuse-builder' ),
			'param' 	=> 'enable',
			'value' 	=> true,
		);


		//Background options
		$fields[] = array(
			'menuId' 	=> 'background',
			'id' 		=> 'selected_layout',
		    'type' 		=> 'select',
		    'label' 	=> esc_html__( 'Choose Layout', 'reuse-builder' ),
		    'param' 	=> 'select',
		    'multiple' 	=> false,
		    'clearable' => false,
		    'options' 	=> array(
		      	'boxed' 	=> esc_html__( 'Boxed Layout', 'reuse-builder' ),
		      	'fluid' 	=> esc_html__( 'Fluid Layout', 'reuse-builder' ),
		    ),
		    'value' 	=> 'fluid',
		);

		$fields[] = array(
			'menuId' 	=> 'background',
			'id'		=> 'fluid_bg_image',
		    'type'		=> 'imageupload',
		    'label'		=> esc_html__( 'Background Image For Fluid Layout', 'reuse-builder' ),
		    'param'		=> 'imageupload',
		    'multiple'	=> false,
		);

		$fields[] = array(
			'menuId' 				=> 'background',
			'id' 					=> 'fluid_bg_color',
		    'type' 					=> 'colorpicker',
		    'label' 				=> esc_html__( 'Background Color For Fluid Layout', 'reuse-builder' ),
		    'param' 				=> 'Color',
		    'name' 					=> 'fluid_bg_color',
		    'default_color' 		=> true,
		    'data_default_color' 	=> '#000000',
		    'palettes' 				=> true,
		    'hide_control' 			=> true,
		);

		$fields[] = array(
			'menuId' 	=> 'background',
			'id'		=> 'boxed_bg_image',
		    'type'		=> 'imageupload',
		    'label'		=> esc_html__( 'Background Image For Boxed Layout', 'reuse-builder' ),
		    'param'		=> 'imageupload',
		    'multiple'	=> false,
		);

		$fields[] = array(
			'menuId' 				=> 'background',
			'id' 					=> 'boxed_bg_color',
		    'type' 					=> 'colorpicker',
		    'label' 				=> esc_html__( 'Background Color For Boxed Layout', 'reuse-builder' ),
		    'param' 				=> 'Color',
		    'name' 					=> 'boxed_bg_color',
		    'default_color' 		=> true,
		    'data_default_color' 	=> '#000000',
		    'palettes' 				=> true,
		    'hide_control' 			=> true,
		);

		$fields[] = array(
			'menuId' 	=> 'background',
			'id'		=> 'boxed_content_bg_image',
		    'type'		=> 'imageupload',
		    'label'		=> esc_html__( 'Background Image For Main Content in Boxed Layout', 'reuse-builder' ),
		    'param'		=> 'imageupload',
		    'multiple'	=> false,
		);

		$fields[] = array(
			'menuId' 				=> 'background',
			'id' 					=> 'boxed_content_bg_color',
		    'type' 					=> 'colorpicker',
		    'label' 				=> esc_html__( 'Background Color For Main Cotnent in Boxed Layout', 'reuse-builder' ),
		    'param' 				=> 'Color',
		    'name' 					=> 'boxed_content_bg_color',
		    'default_color' 		=> true,
		    'data_default_color' 	=> '#000000',
		    'palettes' 				=> true,
		    'hide_control' 			=> true,
		);


		//Banner Options

		$fields[] = array(
			'menuId' 		=> 'banner',
			'id' 			=> 'banner_options_from',
		    'type' 			=> 'select',
		    'label' 		=> esc_html__( 'User Banner Options From', 'reuse-builder' ),
		    'param' 		=> 'select',
		    'multiple' 		=> false,
		    'clearable' 	=> false,
		    'options' 		=> array(
		      	'option_panel' 	=> esc_html__( 'Reuse Builder Option Panel', 'reuse-builder' ),
		      	'local' 		=> esc_html__( 'Current Page Settings', 'reuse-builder' ),
		    ),
		    'value' 		=> 'option_panel',
		);


		$fields[] = array(
			'menuId' 	=> 'banner',
			'id' 		=> 'show_banner',
			'type' 		=> 'switch', // switchalt
			'label' 	=> esc_html__( 'Banner', 'reuse-builder' ),
			'param' 	=> 'enable',
			'value' 	=> true,
		);

		$fields[] = array(
			'menuId' 	=> 'banner',
			'id'		=> 'banner_bg_image',
		    'type'		=> 'imageupload',
		    'label'		=> esc_html__( 'Banner Image', 'reuse-builder' ),
		    'param'		=> 'imageupload',
		    'multiple'	=> false,
		);

		$fields[] = array(
			'menuId' 				=> 'banner',
			'id' 					=> 'banner_bg_color',
		    'type' 					=> 'colorpicker',
		    'label' 				=> esc_html__( 'Banner Color', 'reuse-builder' ),
		    'param' 				=> 'Color',
		    'name' 					=> 'banner_bg_color',
		    'default_color' 		=> true,
		    'data_default_color' 	=> '#000000',
		    'palettes' 				=> true,
		    'hide_control' 			=> true,
		);

		$fields[] = array(
			'menuId' 		=> 'banner',
			'id' 			=> 'banner_bg_opactiy',
		    'type' 			=> 'text',
		    'label' 		=> esc_html__( 'Banner Color Opacity', 'reuse-builder' ),
		    'param' 		=> 'text',
		    'repeat' 		=> false,
		    'value' 		=> '',
		    'placeholder' 	=> esc_html__( 'Banner Opacity', 'reuse-builder' ),
		);

		$fields[] = array(
			'menuId' 	=> 'banner',
			'id' 		=> 'show_breadcrumbs',
			'type' 		=> 'switch', // switchalt
			'label' 	=> esc_html__( 'Show BreadCrumbs', 'reuse-builder' ),
			'param' 	=> 'enable',
			'value' 	=> true,
		);

		$fields[] = array(
			'menuId' 	=> 'banner',
			'id' 		=> 'banner_content_alignment',
		    'type' 		=> 'select',
		    'label' 	=> __('Banner Content Alignment', 'reuse-builder'),
		    'param' 	=> 'select',
		    'multiple' 	=> false,
		    'clearable' => false,
		    'options' 	=> array(
		      	'left' 		=> esc_html__('Left', 'reuse-builder'),
		      	'center' 	=> esc_html__('Center', 'reuse-builder'),
		    ),
		    'value' 	=> 'center',
		);

		$fields[] = array(
			'menuId' 		=> 'banner',
			'id' 			=> 'page_title',
		    'type' 			=> 'text',
		    'label' 		=> esc_html__( 'Page Title', 'reuse-builder' ),
		    'param' 		=> 'text',
		    'repeat' 		=> false,
		    'value' 		=> '',
		    'placeholder' 	=> esc_html__( 'Page Title', 'reuse-builder' ),
		);


		// Listing Banner options
		$fields[] = array(
			'id' 		=> 'enable_listing_banner',
			'type' 		=> 'switch', // switchalt
			'menuId' 	=> 'listing',
			'label' 	=> esc_html__( 'Display Banner', 'reuse-builder' ),
			'param' 	=> 'enable',
			'value' 	=> true,
		);

		$fields[] = array(
			'menuId' 	=> 'listing',
			'id' 		=> 'selected_listing_banner',
		    'type' 		=> 'select',
		    'label' 	=> 'Set Banner As',
		    'param' 	=> 'select',
		    'multiple' 	=> false,
		    'clearable' => false,
		    'options' 	=> array(
		      	'none' 		=> esc_html__( 'Plain Header', 'reuse-builder' ),
		      	'map' 		=> esc_html__( 'Map', 'reuse-builder' ),
		      	'slider' 	=> esc_html__( 'Slider', 'reuse-builder' ),
		      	'image' 	=> esc_html__( 'Image/Color Banner', 'reuse-builder' ),
		      	'adsense' 	=> esc_html__( 'Adsense Banner', 'reuse-builder' ),
		    ),
		    'value' 	=> 'none',
		);

		$fields[] = array(
			'menuId' 	=> 'listing',
			'id' 		=> 'selected_image_banner_as',
		    'type' 		=> 'select',
		    'label' 	=> 'Set Image/Color Banner As',
		    'param' 	=> 'select',
		    'multiple' 	=> false,
		    'clearable' => false,
		    'options' 	=> array(
		      	'image' => esc_html__( 'Banner Image', 'reuse-builder' ),
		      	'color' => esc_html__( 'Banner Color', 'reuse-builder' ),
		      	'slider'=> esc_html__( 'Slider', 'reuse-builder' ),
		    ),
		    'value' 	=> 'image',
		);

		$fields[] = array(
			'menuId' 	=> 'listing',
			'id'		=> 'listing_banner_image',
		    'type'		=> 'imageupload',
		    'label'		=> esc_html__( 'Banner Image', 'reuse-builder' ),
		    'param'		=> 'imageupload',
		    'multiple'	=> false,
		);

		$fields[] = array(
			'menuId' 		=> 'listing',
			'id' 			=> 'enable_slider_shortcode',
			'type' 			=> 'text',
			'label' 		=> esc_html__( 'Slider Shortcode', 'reuse-builder' ),
			'param' 		=> 'slider_shortcode',
			'value' 		=> '',
			'placeholder'	=> esc_html__('enter your slider shortcode here...', 'reuse-builder'),
		);

		$fields[] = array(
			'menuId' 				=> 'listing',
			'id' 					=> 'listing_banner_color',
		    'type' 					=> 'colorpicker',
		    'label' 				=> esc_html__( 'Banner Color', 'reuse-builder' ),
		    'param' 				=> 'Color',
		    'name' 					=> 'listing_banner_color',
		    'default_color' 		=> true,
		    'data_default_color' 	=> '#000000',
		    'palettes' 				=> true,
		    'hide_control' 			=> true,
		);

		$fields[] = array(
			'menuId' 	=> 'listing',
			'id' 		=> 'enable_banner_text',
			'type' 		=> 'switch', // switchalt
			'label' 	=> esc_html__( 'Banner Text', 'reuse-builder' ),
			'param' 	=> 'enable',
			'value' 	=> true,
		);

		$fields[] = array(
			'menuId' 		=> 'listing',
			'id' 			=> 'listing_banner_text',
		    'type' 			=> 'text',
		    'label' 		=> esc_html__( 'Banner Text', 'reuse-builder' ),
		    'param' 		=> 'text',
		    'repeat' 		=> false,
		    'value' 		=> '',
		    'placeholder' 	=> esc_html__( 'Banner Text', 'reuse-builder' ),

		);


		//Copyright options
		$fields[] = array(
			'menuId' 	=> 'copyright',
			'id' 		=> 'copyright_options_from',
		    'type' 		=> 'select',
		    'label' 	=> esc_html__( 'Use Copyright Options From', 'reuse-builder' ),
		    'param' 	=> 'select',
		    'multiple' 	=> false,
		    'clearable' => false,
		    'options' 	=> array(
		      	'option_panel' 	=> esc_html__('Reuse Builder Option Panel', 'reuse-builder'),
		      	'local' 		=> esc_html__('Current Page Settings', 'reuse-builder'),
		    ),
		    'value' 	=> 'option_panel',
		);

		$fields[] = array(
			'menuId' 	=> 'copyright',
			'id' 		=> 'enable_copyright',
			'type' 		=> 'switch',
			'label' 	=> esc_html__( 'Display Copyright', 'reuse-builder' ),
			'param' 	=> 'enable',
			'value' 	=> true,
		);

		$fields[] = array(
			'menuId' 		=> 'copyright',
			'id' 			=> 'reuseb_choose_copyright',
			'type' 			=> 'selectGroup',
			'subtype' 		=> 'airbnbCb',
			'label' 		=> esc_html__('Choose Copyright', 'reuse-builder'),
			'param' 		=> 'thisistheparam',
			'subtitle' 		=> esc_html__('Choose Copyright For This Page', 'reuse-builder'),
			'options' 		=> array(
				array(
					'value' 		=> 'copyright-one',
					'title' 		=> esc_html__('Copyright One', 'reuse-builder'),
					'ionClassName' 	=> 'ion-bag',
				),
				array(
					'value' 		=> 'copyright-two',
					'title' 		=> esc_html__('Copyright Two', 'reuse-builder'),
					'ionClassName' 	=> 'ion-bag',
				),
				array(
					'value' 		=> 'copyright-three',
					'title' 		=> esc_html__('Copyright Three', 'reuse-builder'),
					'ionClassName' 	=> 'ion-bag',
				),
			),
			'multiple' 		=> false,
			'allButton' 	=> false,
		);

		$fields[] = array(
			'menuId' 	=> 'copyright',
			'id'		=> 'copyright_logo',
		    'type'		=> 'imageupload',
		    'label'		=> esc_html__( 'Copyright Logo', 'reuse-builder' ),
		    'param'		=> 'imageupload',
		    'multiple'	=> false,
		);

		$fields[] = array(
			'menuId' 	=> 'copyright',
			'id'		=> 'copyright_bg_image',
		    'type'		=> 'imageupload',
		    'label'		=> esc_html__( 'Copyright Background Image', 'reuse-builder' ),
		    'param'		=> 'imageupload',
		    'multiple'	=> false,
		);

		$fields[] = array(
			'menuId' 		=> 'copyright',
			'id' 			=> 'copyright_bg_color',
		    'type' 			=> 'colorpicker',
		    'label' 		=> esc_html__( 'Copyright Background Color', 'reuse-builder' ),
		    'param' 		=> 'Color',
		    'name' 			=> 'copyright_bg_color',
		    'default_color' => true,
		    'value' 		=> '#000000',
		    'palettes' 		=> true,
		    'hide_control' 	=> true,
		);

		$fields[] = array(
			'id' 			=> 'copyright_height',
		    'type' 			=> 'text',
		    'label' 		=> esc_html__( 'Height', 'reuse-builder' ),
		    'param' 		=> 'text',
		    'repeat' 		=> false,
		    'value' 		=> '',
		    'placeholder' 	=> esc_html__( 'copyright height', 'reuse-builder' ),
		    'menuId' 		=> 'copyright',
		);

		$fields[] = array(
			'id' 			=> 'copyright_width',
		    'type' 			=> 'text',
		    'label' 		=> esc_html__( 'Copyright Width', 'reuse-builder' ),
		    'param' 		=> 'text',
		    'repeat' 		=> false,
		    'value' 		=> '',
		    'placeholder' 	=> 'copyright width',
		    'menuId' 		=> 'copyright',
		);

		$fields[] = array(
			'id' 			=> 'copyright_text',
		    'type' 			=> 'textarea',
		    'label' 		=> esc_html__( 'Copyright Text', 'reuse-builder' ),
		    'param' 		=> 'text',
		    'subtitle' 		=> esc_html__( 'Insert the copyright text (Inlcuding HTML tags)', 'reuse-builder' ),
		    'placeholder' 	=> esc_html__( 'enter your text here...', 'reuse-builder' ),
		    'menuId' 		=> 'copyright',
		);

		$fields[] = array(
			'menuId' 	=> 'sidebar',
			'id' 		=> 'sidebar_options_from',
		    'type' 		=> 'select',
		    'label' 	=> esc_html__('Use Sidebar Options From','reuse-builder'),
		    'param' 	=> 'select',
		    'multiple' 	=> false,
		    'clearable' => false,
		    'options' 	=> array(
		      	'option_panel' 	=> esc_html__('Reuse Builder Option Panel', 'reuse-builder'),
		      	'local' 		=> esc_html__('Current Page Settings', 'reuse-builder'),
		    ),
		    'value' 	=> 'option_panel',
		);

		$fields[] = array(
			'menuId' 	=> 'sidebar',
			'id' 		=> 'sidebar_content_as',
		    'type' 		=> 'select',
		    'label' 	=> esc_html__('SideBar Content As','reuse-builder'),
		    'param' 	=> 'select',
		    'multiple' 	=> false,
		    'clearable' => false,
		    'options' 	=> array(
		      	'widgets' 		=> esc_html__('Widgets', 'reuse-builder'),
		      	'sidebar_menu' 	=> esc_html__('Elements Menu', 'reuse-builder'),
		    ),
		    'value' 	=> 'widgets',
		);

		global $wp_registered_sidebars;
		$widgets_areas = array();
		if(isset($wp_registered_sidebars) && is_array($wp_registered_sidebars)) :
			foreach ($wp_registered_sidebars as $key => $value) {
				$widgets_areas[$key] = $value['name'];
			}
		endif;

		$fields[] = array(
			'menuId' 	=> 'sidebar',
			'id' 		=> 'reuseb_page_widget_sidebar',
		    'type' 		=> 'select',
		    'label' 	=> esc_html__('Choose Widgets Area','reuse-builder'),
		    'param' 	=> 'select',
		    'multiple' 	=> false,
		    'clearable' => false,
		    'options' 	=> $widgets_areas,
		    'value' 	=> 'reuseb_single_page_widgets',
		);

		$fields[] = array(
			'id' 			=> 'page_sidebar',
		    'type' 			=> 'textarea',
		    'label' 		=> esc_html__( 'Element Menu Sidebar Text', 'reuse-builder' ),
		    'param' 		=> 'text',
		    'subtitle' 		=> esc_html__( 'Insert the copyright text (Inlcuding HTML tags)', 'reuse-builder' ),
		    'placeholder' 	=> esc_html__( 'enter your text here...', 'reuse-builder' ),
		    'menuId' 		=> 'sidebar',
		);


		return $fields;
	}

	public static function redq_get_all_taxonomies() {
		$restricted_taxonomies = array(
			'nav_menu',
			'link_category',
			'post_format',
		);

		$args 		= array();
		$output 	= 'objects'; // or objects
		$operator 	= 'or'; // 'and' or 'or'
		$taxonomies = get_taxonomies( $args, $output, $operator );

		$formatted_taxonomies = array();

		if ( $taxonomies ) {
			foreach ( $taxonomies  as $key => $taxonomy ) {
				if( !in_array($key, $restricted_taxonomies) ) {
						$formatted_taxonomies[$taxonomy->name] = $taxonomy->labels->singular_name;
				}
			}
		}

		return $formatted_taxonomies;
	}

	public static function redq_get_all_posts() {
		$restricted_post_types = array(
			// 'attachment',
			'page',
			'reuseb_template',
			'reuseb_taxonomy',
			'reuseb_term_metabox',
			'reuseb_metabox',
			'reuseb_post_type',
		);
		$args = array(
			'public'   => true,
		);

		$output 		= 'objects'; // 'names' or 'objects' (default: 'names')
		$operator 	= 'and'; // 'and' or 'or' (default: 'and')

		$post_types = get_post_types( $args, $output, $operator );

		$formatted_post_types = array();

		foreach($post_types as $key => $post_type) {
			if( !in_array($key, $restricted_post_types) ) {
				$formatted_post_types[$post_type->name] = $post_type->labels->singular_name;
			}
		}
		$formatted_post_types['user'] = 'User';
		return $formatted_post_types;
	}
}
