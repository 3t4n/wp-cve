<?php
/**
 * Initialize example plugin
 */
function icon_element_admin_init() {

	// Initialize Sunrise
	$admin = new Sunrise7( array(
			// Sunrise file path
			'file' => __FILE__,
			// Plugin slug (should be equal to plugin directory name)
			'slug' => 'iconelement',
			// Plugin prefix
			'prefix' => 'icon-element',
			// Plugin textdomain
			'textdomain' => 'icon-element',
			// Custom CSS assets folder
			'css' => '',
			// Custom JS assets folder
			'js' => '',
		) );

	// Prepare array with options
	$options = array(

		// Open tab: Regular fields
		array(
			'type' => 'opentab',
			'name' => __( 'Icon fonts', 'icon-element' ),
		),
		array(
			'type' => 'openflex',
		),

		// Checkbox
		array(
			'id'      => 'ie-material-design',
			'type'    => 'checkbox',
			'default' => 'yes',
			'name'    => __( 'Material Design (931 icons)', 'icon-element' ),
			'pro'	  => '',
			'preview' => 'https://material.io/resources/icons/?style=baseline'
		),

		array(
			'id'      => 'ie-metrize',
			'type'    => 'checkbox',
			'default' => 'yes',
			'name'    => __( 'Metrize (299 icons)', 'icon-element' ),
			'pro'	  => '',
			'preview' => 'http://www.alessioatzeni.com/metrize-icons/'
		),

		array(
			'id'      => 'ie-captain',
			'type'    => 'checkbox',
			'default' => 'yes',
			'name'    => __( 'Captain (374 icons)', 'icon-element' ),
			'pro'	  => '',
			'preview' => 'https://mariodelvalle.github.io/CaptainIconWeb/'
		),

		array(
			'id'      => 'ie-ionicons',
			'type'    => 'checkbox', 
			'default' => 'on',
			'name'    => __( 'Ionicons (695 icons)', 'icon-element' ),
			'pro'	  => '',
			'preview' => 'https://ionicons.com/'
		),

		array(
			'id'      => 'ie-dripicons-icons',
			'type'    => 'checkbox',
			'default' => 'on',
			'name'    => __( 'Dripicons Icons (199 icons)', 'icon-element' ),
			'pro'	  => '',
			'preview' => 'http://demo.amitjakhu.com/dripicons/'
		),

		array(
			'id'      => 'ie-simpline',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => __( 'Simpline (188 icons)', 'icon-element' ),
			'pro'	  => '',
			'preview' => 'https://github.com/thesabbir/simple-line-icons'
		),

		array(
			'id'      => 'ie-bootstrap',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => __( 'Bootstrap (1682 icons)', 'icon-element' ),
			'pro'	  => 'yes',
			'preview' => 'https://icons.getbootstrap.com/#icons'
		),

		array(
			'id'      => 'ie-wppagebuilder',
			'type'    => 'checkbox',
			'default' => 'on',
			'name'    => __( 'Wp Pagebuilder (344 icons)', 'icon-element' ),
			'pro'	  => '',
			'preview' => 'https://builder.themeum.com/wppbicon/'
		),

		array(
			'id'      => 'ie-linea-music',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => __( 'Linea Music (29 icons)', 'icon-element' ),
			'pro'	  => 'yes',
			'preview' => 'https://linea.io/'
		),

		array(
			'id'      => 'ie-boxicons-icons',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => __( 'Boxicons (490 icons)', 'icon-element' ),
			'pro'	  => 'yes',
			'preview' => 'https://github.com/atisawd/boxicons'
		),

		array(
			'id'      => 'ie-elegant-font',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => __( 'Elegant Font (359 icons)', 'icon-element' ),
			'pro'	  => 'yes',
			'preview' => 'https://www.elegantthemes.com/blog/resources/elegant-icon-font'
		),

		array(
			'id'      => 'ie-et-line',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => __( 'ET Line (99 icons)', 'icon-element' ),
			'pro'	  => 'yes',
			'preview' => 'https://www.elegantthemes.com/blog/freebie-of-the-week/free-line-style-icons'
		),

		array(
			'id'      => 'ie-gonzo-font',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => __( 'Gonzo (99 icons)', 'icon-element' ),
			'pro'	  => 'yes',
			'preview' => 'https://www.gonzodesign.nl/gonzocons/'
		),

		array(
			'id'      => 'ie-icomoon-font',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => __( 'Icomoon (490 icons)', 'icon-element' ),
			'pro'	  => 'yes',
			'preview' => 'https://icomoon.io/#preview-free'
		),

		array(
			'id'      => 'ie-iconmonstr-font',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => __( 'Iconmonstr (299 icons)', 'icon-element' ),
			'pro'	  => 'yes',
			'preview' => 'https://iconmonstr.com/'
		),

		array(
			'id'      => 'ie-io7-icon',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => __( 'iOS7 (260 icons)', 'icon-element' ),
			'pro'	  => 'yes',
			'preview' => 'http://akira-miyake.github.io/iOS7-icon-font/'
		),

		array(
			'id'      => 'ie-linea_basic',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => __( 'Linea Basic (134 icons)', 'icon-element' ),
			'pro'	  => 'yes',
			'preview' => 'https://linea.io/'
		),

		array(
			'id'      => 'ie-linea_elaboration',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => __( 'Linea Elaboration (144 icons)', 'icon-element' ),
			'pro'	  => 'yes',
			'preview' => 'https://linea.io/'
		),

		array(
			'id'      => 'ie-linea_ecommerce',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => __( 'Linea Ecommerce (84 icons)', 'icon-element' ),
			'pro'	  => 'yes',
			'preview' => 'https://linea.io/'
		),

		array(
			'id'      => 'ie-linearicons',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => __( 'Linearicons (169 icons)', 'icon-element' ),
			'pro'	  => 'yes',
			'preview' => 'https://linearicons.com/free'
		),

		array(
			'id'      => 'ie-lineawesone-regular',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => __( 'Lineawesome (1393 icons)', 'icon-element' ),
			'pro'	  => 'yes',
			'preview' => 'https://icons8.com/line-awesome'
		),

		array(
			'id'      => 'ie-lineicons',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => __( 'Lineicons (306 icons)', 'icon-element' ),
			'pro'	  => 'yes',
			'preview' => 'https://lineicons.com/'
		),

		array(
			'id'      => 'ie-mobirise',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => __( 'Mobirise (149 icons)', 'icon-element' ),
			'pro'	  => 'yes',
			'preview' => 'https://mobiriseicons.com/cheatsheet.html'
		),

		array(
			'id'      => 'ie-rivolicon',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => __( 'Rivolicon (130 icons)', 'icon-element' ),
			'pro'	  => 'yes',
			'preview' => 'https://rivolicons.hadrien.co/free/'
		),

		array(
			'id'      => 'ie-themify-icons',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => __( 'Themify-icons (351 icons)', 'icon-element' ),
			'pro'	  => 'yes',
			'preview' => 'https://themify.me/themify-icons'
		),

		array(
			'id'      => 'ie-tilda',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => __( 'Tilda (794 icons)', 'icon-element' ),
			'pro'	  => 'yes',
			'preview' => 'https://tilda.cc/free-icons/'
		),

		array(
			'id'      => 'ie-elementor',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => __( 'Elementor (386 icons)', 'icon-element' ),
			'pro'	  => '',
			'preview' => 'https://elementor.github.io/elementor-icons/'
		),

		array(
			'id'      => 'ie-vscode',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => __( 'VsCode Icon (411 icons)', 'icon-element' ),
			'pro'	  => '',
			'preview' => 'https://www.figma.com/community/file/768673354734944365'
		),

		array(
			'id'      => 'ie-tabler',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => __( 'Tabler Icon (1977 icons)', 'icon-element' ),
			'pro'	  => 'yes',
			'preview' => 'https://tablericons.com/'
		),

		array(
			'id'      => 'ie-antdesign',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => __( 'Antdesign Icon (637 icons)', 'icon-element' ),
			'pro'	  => 'yes',
			'preview' => 'https://ant.design/components/icon/'
		),

		array(
			'id'      => 'ie-chart',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => esc_html__( 'Chart (37 icons)', 'icon-element' ),
			'pro'	  => 'yes',
			'preview' => 'https://www.figma.com/file/0Gtw7eOrDnbNxPpveO183k/Chart-Icons---Outline-(Community)?node-id=275%3A6576'	
		),

		array(
			'id'      => 'ie-mapicon',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => esc_html__( 'Map Icon (174 icons)', 'icon-element' ),
			'pro'	  => 'yes',
			'preview' => 'http://map-icons.com/'	
		),

		array(
			'id'      => 'ie-devicon',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => esc_html__( 'Devicon (499 icons)', 'icon-element' ),
			'pro'	  => 'yes',
			'preview' => 'https://devicon.dev/'	
		),

		array(
			'id'      => 'ie-androidmaterial',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => esc_html__( 'Android material (432 icons)', 'icon-element' ),
			'pro'	  => 'yes',
			'preview' => 'https://www.androidicons.com/'	
		),

		array(
			'id'      => 'ie-androidholo',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => esc_html__( 'Android holo (143 icons)', 'icon-element' ),
			'pro'	  => 'yes',
			'preview' => 'https://www.androidicons.com/'	
		),

		array(
			'id'      => 'ie-evaicon',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => esc_html__( 'Evaicon (244 icons)', 'icon-element' ),
			'pro'	  => 'yes',
			'preview' => 'https://www.figma.com/file/T08qYmPlqINqGdU9ElXdXZ/Icon-Design-System---Eva-Icons-(Community)?node-id=0%3A1'	
		),

		array(
			'id'      => 'ie-iconicool',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => esc_html__( 'Iconi cool (70 icons)', 'icon-element' ),
			'pro'	  => 'yes',
			'preview' => 'https://www.figma.com/file/2jUsVzQgltQG4LXBYabU71/Iconicool-%7C-Free-Iconset-(Community)?node-id=3%3A250'	
		),

		array(
			'id'      => 'ie-elusive',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => esc_html__( 'Elusive (298 icons)', 'icon-element' ),
			'pro'	  => '',
			'preview' => 'http://elusiveicons.com/icons/'	
		),

		array(
			'id'      => 'ie-obicon',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => esc_html__( 'Obicon (107 icons)', 'icon-element' ),
			'pro'	  => '',
			'preview' => 'https://ob-cloud.github.io/obicon-iot/'	
		),

		array(
			'id'      => 'ie-webicon',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => esc_html__( 'Web icon (169 icons)', 'icon-element' ),
			'pro'	  => '',
			'preview' => 'https://github.com/thecreation/web-icons'	
		),

		array(
			'id'      => 'ie-feather',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => esc_html__( 'Feather (265 icons)', 'icon-element' ),
			'pro'	  => '',
			'preview' => 'https://feathericons.com/'	
		),

		array(
			'id'      => 'ie-elementplus',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => esc_html__( 'Element plus (243 icons)', 'icon-element' ),
			'pro'	  => 'yes',
			'preview' => 'https://element-plus.org/en-US/component/icon.html#icon-collection'	
		),

		array(
			'id'      => 'ie-iconsaxbold',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => esc_html__( 'Iconsax Bold (904 icons)', 'icon-element' ),
			'pro'	  => '',
			'preview' => 'https://iconsax.io/'	
		),

		array(
			'id'      => 'ie-tutor',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => esc_html__( 'Tutor LMS Icon (314 icons)', 'icon-element' ),
			'pro'	  => '',
			'preview' => 'https://wordpress.org/plugins/tutor/'	
		),

		array(
			'id'      => 'ie-uniconsolid',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => esc_html__( 'Unicon solid (189 icons)', 'icon-element' ),
			'pro'	  => '',
			'preview' => 'https://iconscout.com/unicons/explore/solid'	
		),

		array(
			'id'      => 'ie-uniconline',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => esc_html__( 'Unicon line (1203 icons)', 'icon-element' ),
			'pro'	  => 'yes',
			'preview' => 'https://iconscout.com/unicons/explore/line'	
		),

		array(
			'id'      => 'ie-unithinline',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => esc_html__( 'Unicon thin line (214 icons)', 'icon-element' ),
			'pro'	  => 'yes',
			'preview' => 'https://iconscout.com/unicons/explore/thinline'	
		),

		array(
			'id'      => 'ie-happyicon',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => esc_html__( 'Happy icon (646 icons)', 'icon-element' ),
			'pro'	  => '',
			'preview' => 'https://wordpress.org/plugins/happy-elementor-addons/'	
		),

		array(
			'id'      => 'ie-woocommerce',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => esc_html__( 'Woo Commerce (68 icons)', 'icon-element' ),
			'pro'	  => '',
			'preview' => 'https://rawgit.com/woothemes/woocommerce-icons/master/demo.html'	
		),

		array(
			'id'      => 'ie-buddyboss',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => esc_html__( 'Buddy Boss (1295 icons)', 'icon-element' ),
			'pro'	  => 'yes',
			'preview' => 'https://www.buddyboss.com/resources/font-cheatsheet/'	
		),

		array(
			'id'      => 'ie-detheme',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => esc_html__( 'DeTheme icon (385 icons)', 'icon-element' ),
			'pro'	  => '',
			'preview' => 'https://wordpress.org/plugins/dethemekit-for-elementor/'	
		),

		array(
			'id'      => 'ie-trademe',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => esc_html__( 'Trademe (397 icons)', 'icon-element' ),
			'pro'	  => 'yes',
			'preview' => 'https://trademe.github.io/TradeMe-IconFont/'	
		),

		array(
			'id'      => 'ie-prestashop',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => esc_html__( 'Prestashop (478 icons)', 'icon-element' ),
			'pro'	  => '',
			'preview' => 'https://build.prestashop-project.org/prestashop-icon-font/'	
		),

		array(
			'id'      => 'ie-uicons',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => esc_html__( 'Uicons (2287 icons)', 'icon-element' ),
			'pro'	  => '',
			'preview' => 'https://github.com/freepik-company/flaticon-uicons/'	
		),

		array(
			'id'      => 'ie-jquery-uicons',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => esc_html__( 'jQuery UI Icons (450 icons)', 'icon-element' ),
			'pro'	  => '',
			'preview' => 'https://api.jqueryui.com/resources/icons-list.html'	
		),

		array(
			'id'      => 'ie-xlslim',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => esc_html__( 'XL Slim (292 icons)', 'icon-element' ),
			'pro'	  => '',
			'preview' => 'https://user-images.githubusercontent.com/5102591/46199020-8f8ed700-c316-11e8-9b3a-7388f3f4e818.png/'	
		),

		array(
			'id'      => 'ie-mingcute',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => esc_html__( 'Mingcute (2327 icons)', 'icon-element' ),
			'pro'	  => 'yes',
			'preview' => 'https://www.mingcute.com/'	
		),

		array(
			'id'      => 'ie-olicons',
			'type'    => 'checkbox',
			'default' => 'yes',
			'name'    => esc_html__( 'Olicons (677 icons)', 'icon-element' ),
			'pro'	  => 'yes',
			'preview' => 'http://olicons.yemaosheji.com/index.html#sec-menu'	
		),

		array(
			'id'      => 'ie-ixsiemens',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => esc_html__( 'Siemens icon (642 icons)', 'icon-element' ),
			'pro'	  => 'yes',
			'preview' => 'https://github.com/siemens/ix-icons'	
		),

		array(
			'id'      => 'ie-fabric',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => esc_html__( 'Fabric Icon (1922 icons)', 'icon-element' ),
			'pro'	  => '',
			'preview' => 'https://uifabricicons.azurewebsites.net/'	
		),

		array(
			'id'      => 'ie-keyrune',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => esc_html__( 'Keyrune (340 icons)', 'icon-element' ),
			'pro'	  => 'yes',
			'preview' => 'https://keyrune.andrewgioia.com/icons.html'	
		),

		array(
			'id'      => 'ie-grommet',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => esc_html__( 'Grommet (200 icons)', 'icon-element' ),
			'pro'	  => '',
			'preview' => 'https://icons.grommet.io/'	
		),

		array(
			'id'      => 'ie-orchid',
			'type'    => 'checkbox',
			'default' => '',
			'name'    => esc_html__( 'Orchid (283 icons)', 'icon-element' ),
			'pro'	  => '',
			'preview' => 'https://github.com/orchidsoftware/icons'	
		),

		array(
			'type' => 'closeflex',
		),

		// Close tab: Regular fields
		array(
			'type' => 'closetab',
		),
	);

	// Add sub-menu (like Dashboard -> Settings -> Permalinks)
	$admin->add_submenu( array(
			// Settings page <title>
			'page_title' => __( 'Icon Element', 'icon-element' ),
			// Menu title, will be shown in left dashboard menu
			'menu_title' => __( 'Icon Element', 'icon-element' ),
			// Unique page slug, you can use here the slug of parent page, which you've already created
			'slug' => 'iconelement',
			// Slug of the parent page (see above)
			'parent_slug' => 'themes.php',
			// Array with options available on this page
			'options' => $options,
		) );
}

// Hook to plugins_loaded
add_action( 'plugins_loaded', 'icon_element_admin_init' );
