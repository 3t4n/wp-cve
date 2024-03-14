<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
	



// ===============================================================================================
// -----------------------------------------------------------------------------------------------
// FRAMEWORK SETTINGS
// -----------------------------------------------------------------------------------------------
// ===============================================================================================
$eds_responsive_menu_settings           = array(
  'menu_title'      => 'EDS Responsive Menu',
  'menu_type'       => 'menu', // menu, submenu, options, theme, etc.
  'menu_slug'       => 'eds-responsive-menu',
  'menu_capability' => 'manage_options',
  'ajax_save'       => false,
  'show_reset_all'  => true,
  //'framework_title' => 'eDS Responsive Menu <small><a href="#" target="_blank" style="text-decoration:none;">upgrade to Pro</a></small>',
  'framework_title' => 'eDS Responsive Menu  <small><a href="https://edatastyle.com/product/eds-reponsive-menu/" target="_blank" style="color:#F00">upgrade to pro </a></small> </small>',
);

// ===============================================================================================
// -----------------------------------------------------------------------------------------------
// FRAMEWORK OPTIONS
// -----------------------------------------------------------------------------------------------
// ===============================================================================================
$eds_responsive_menu_options        = array();

function eds_load_nav(){
		$menus = get_terms('nav_menu',array('hide_empty'=>false,'orderby' => 'name'));
		$menu = array();
		foreach( $menus as $m ) {
			$menu[$m->name] = $m->name;
		}	
		return 	$menu;
	}
	
// ----------------------------------------
// a option section for options overview  -
// ----------------------------------------
$eds_responsive_menu_options[]      = array(
  'name'        => 'eds_menu_settings',
  'title'       => 'Initial Setup',
  'icon'        => 'fa fa-cog',

  // begin: fields
  'fields'      => array(
    array(
		'id'             => 'eds_choose_menu',
		'type'           => 'select',
		'title'          => 'Select Menu',
		'options'        => eds_load_nav(),
		'info'           => 'This is the menu that will be used responsive.',
		'attributes' => array(
			'style'    => 'height:35px; border-color: #93C054;'
		),
    ),
	
    // begin: a field
    array(
      'id'      => 'eds_elements_hide',
      'type'    => 'text',
      'title'   => 'Elements to Hide in Mobile ',
	  
	  'attributes' => array(
			'style'    => 'height:35px; border-color: #93C054;'
		),
	  'info'  => 'Enter the css class/ids for different elements you want to hide on mobile separeted by a comma(,). Example: .nav, #main-menu ',
    ),
	 array(
      'id'      => 'eds_menu_breakpoint',
      'type'    => 'text',
      'title'   => 'Menu Breakpoint ',
	  'default' => '720',
	  'attributes' => array(
			'style'    => 'width: 100px; height:35px; border-color: #93C054;'
		),
	  'info'  => ' Enter the width (in px) below which the responsive menu will be visible on screen',
	   'validate' => 'numeric',
    ),
	array(
	'id'      => 'menu_width',
	'type'    => 'text',
	'default' => '70',
	'title'   => 'Menu Wrapper Width',
		'attributes' => array(
			'style'    => ' width:100px; height:35px; border-color: #93C054;'
		),
	  'after' => ' <i class="eds-text-muted">%</i>',
	   'validate' => 'numeric',
    ),
	array(
	'id'      => 'max_width',
	'type'    => 'text',
	'default' => '320',
	'title'   => 'Maximum Wrapper Width',
		'attributes' => array(
			'style'    => ' width:100px; height:35px; border-color: #93C054;'
		),
	  'after' => ' <i class="eds-text-muted">px</i>',
	   'validate' => 'numeric',
    ),
	array(
	'id'      => 'min_width',
	'type'    => 'text',
	'default' => '270',
	'title'   => 'Minimum Wrapper Width',
		'attributes' => array(
			'style'    => ' width:100px; height:35px; border-color: #93C054;'
		),
	  'after' => ' <i class="eds-text-muted">px</i>',
	   'validate' => 'numeric',
    ),
	
	array(
		'type'    => 'notice',
		'class'   => 'info',
		'content' => '<strong style="padding:0px; margin:0px;" >Effect & Animation</strong>',
	),
	array(
		'id'      => 'active_font_awesome',
		'type'    => 'switcher',
		'title'   => 'Active Font Awesome Icon',
		'label'   => '<a href="https://edatastyle.com/product/eds-reponsive-menu/" target="_blank" style="color:#F00">upgrade to pro </a> How Use To Font Awesome Icon ? <a href="https://eds.edatastyle.com/_docs/responsive_menu/#!/fontawesome">docs</a> & <a href="http://fontawesome.io/icons/">
font awesome icon pack</a> ',
		'default' => false,
		 'attributes' => array(
			'disabled' => 'disabled'
			),
		),
		
	
	 array(
		  'id'      => 'choose_effect_type',
		  'type'    => 'select',
		  'title'   => 'Choose Effect for Menu',
		  'attributes' => array(
			'style'    => 'height:35px; border-color: #93C054;',
			),
		  'options' => array(
			'side_left'   => 'Menu Left Side ',
		  ),
		  'after'=>'<a href="https://edatastyle.com/product/eds-reponsive-menu/" target="_blank" style="margin-left:20px; color:#F00">upgrade to pro </a>  &nbsp;&nbsp; and get more 5+ style'
		),
		
		
		array(
		  'id'      => 'animation_speed',
		  'type'    => 'text',
		  'title'   => 'Animation Speed',
		  'default' => '200',
		  'attributes' => array(
				'style'    => 'width: 100px; height:35px; border-color: #93C054;'
			),
			 'validate' => 'numeric',
			 'info' =>'milliseconds '
		),
		
   
  ), // end: fields
);

// ----------------------------------------
// a option section for options overview  -
// ----------------------------------------
$eds_responsive_menu_options[]      = array(
  'name'        => 'eds_menu_symbol',
  'title'       => 'Menu Symbol',
  'icon'        => 'fa fa-bars',

  // begin: fields
  'fields'      => array(
   
	array(
	'id'      => 'toggle_position',
	'type'    => 'select',
	'title'   => 'Menu Symbol Position',
	'options' => array(
		'right'    => 'Right',
	
		
	),
	'info'  => 'Select menu icon position which will be displayed on the menu bar. ( toggle menu Icon )<img src="'.EDS_MENU_URI.'/assets/img/toggle-icon.png" width="20" /><br />
 <a href="https://edatastyle.com/product/eds-reponsive-menu/" target="_blank" style="color:#F00">upgrade to pro </a> get Left Position',
	),
	
	
	array(
	'id'      => 'toggle_type',
	'type'    => 'select',
	'title'   => 'Toggle Position',
		'attributes' => array(
		'style'    => 'width: 100px; height:35px; border-color: #93C054;'
	),
	'options' => array(
		'fixed'    => 'Fixed',
		'absolute'   => 'Absolute',
		
	),
	
	),
	
	
	array(
	'id'      => 'toggle_width',
	'type'    => 'text',
	'default' => '40',
	'title'   => 'Menu Symbol Width',
	 'validate' => 'numeric',
		'attributes' => array(
			'style'    => ' width:100px; height:35px; border-color: #93C054;'
		),
	  'after' => ' <i class="eds-text-muted">px</i>',
    ),
	array(
	'id'      => 'toggle_height',
	'type'    => 'text',
	'default' => '42',
	'title'   => 'Menu Symbol Height',
	 'validate' => 'numeric',
		'attributes' => array(
			'style'    => ' width:100px; height:35px; border-color: #93C054;'
		),
	  'after' => ' <i class="eds-text-muted">px</i>',
    ),
	
	array(
	'id'      => 'toggle_margin_top',
	'type'    => 'text',
	'default' => '20',
	'title'   => 'Menu Symbol margin top',
	 'validate' => 'numeric',
		'attributes' => array(
			'style'    => ' width:100px; height:35px; border-color: #93C054;'
		),
	  'after' => ' <i class="eds-text-muted">px</i>',
    ),
	array(
	'id'      => 'toggle_margin_left_right',
	'type'    => 'text',
	'default' => '16',
	'title'   => 'Menu Symbol margin left/right',
	 'validate' => 'numeric',
		'attributes' => array(
			'style'    => ' width:100px; height:35px; border-color: #93C054;'
		),
	  'after' => ' <i class="eds-text-muted">px</i>',
    ),	
	
	
	array(
	  'id'      => 'symbol_bg',
	  'type'    => 'color_picker',
	  'title'   => 'Symbol Background ',
	  'default' => '#000000',
	),
	array(
	  'id'      => 'symbol_color',
	  'type'    => 'color_picker',
	  'title'   => 'Symbol color ',
	  'default' => '#ffffff',
	),
	array(
	'id'      => 'symbol_line_height',
	'type'    => 'text',
	'default' => '3',
	'title'   => 'Symbol echo line Height',
	 'validate' => 'numeric',
		'attributes' => array(
			'style'    => ' width:100px; height:35px; border-color: #93C054;'
		),
	  'after' => ' <i class="eds-text-muted">px</i>',
    ),
   
  ), // end: fields
);


$eds_responsive_menu_options[]   = array(
  'name'     => 'eds_menu_options',
  'title'    => 'Menu',
  'icon'     => 'fa fa-tablet',
  'fields'   => array(

	
	array(
          'id'    => 'menu_wrp_bg',
          'type'  => 'background',
          'title' => 'Menu Wrapper background',
		   'default'      => array(
            'color'      => '#333333',
          ),
		  'info'  => 'You can Choose background Image , Gackground Color or empty.<a href="https://edatastyle.com/product/eds-reponsive-menu/" target="_blank" style="color:#F00">upgrade to pro </a>'
        ),
	
	 array(
		  'id'      => 'transparent',
		  'type'    => 'switcher',
		  'title'   => 'Transparent Background',
		  'default' => true,
		  
		),
	array(
	'id'      => 'opacity',
	'type'    => 'text',
	'default' => '0.7',
	'validate' => 'numeric',
	'title'   => 'Transparent Opacity',
		'attributes' => array(
			'style'    => ' width:100px; height:35px; border-color: #93C054;'
		),
	  'after' => ' <i class="eds-text-muted">min ( 0.1) and maximum (0.9)</i>',
    ),
	array(
		'type'    => 'notice',
		'class'   => 'info',
		'content' => '<strong style="padding:0px; margin:0px;" >Menu Fonts & Size Settings</strong>',
	),
	
	array(
          'id'        => 'font_family',
          'type'      => 'typography',
          'title'     => 'Menu Font family',
          'chosen'    => false,
		  'attributes' => array(
			'style'    => ' width:100px; height:35px; border-color: #93C054;',
			'disabled' => 'disabled'
		),
	  'after' => ' <a href="https://edatastyle.com/product/eds-reponsive-menu/" target="_blank" style="color:#F00">upgrade to pro </a>  get 600+ google and web regular fonts',
        ),
	array(
	'id'      => 'font_size',
	'type'    => 'text',
	'default' => '14',
	'title'   => 'Font Size',
	 'validate' => 'numeric',
		'attributes' => array(
			'style'    => ' width:100px; height:35px; border-color: #93C054;',	'disabled' => 'disabled'
		),
	  'after' => ' <i class="eds-text-muted">px</i>',
    ),	
	
	array(
	'id'      => 'line_height',
	'type'    => 'text',
	'default' => '20',
	 'validate' => 'numeric',
	'title'   => 'line Height',
		'attributes' => array(
			'style'    => ' width:100px; height:35px; border-color: #93C054;'
		),
	  'after' => ' <i class="eds-text-muted">px</i>',
    ),
	array(
	'id'      => 'seperator',
	'type'    => 'text',
	'default' => '12,0,12,20',
	'title'   => 'Menu Seperate',
		'attributes' => array(
			'style'    => ' height:35px; border-color: #93C054;'
		),
	  'info' => ' <i class="eds-text-muted">(PX) Example: (top,right,bottom,left) = (5,10,5,20) px</i>',
	 
    ),
	

	array(
	  'id'      => 'menu_bg_color',
	  'type'    => 'color_picker',
	  'title'   => 'Menu background color',
	  'default' => '#1b1b1b',
	),
	array(
	  'id'      => 'menu_bg_color_hover',
	  'type'    => 'color_picker',
	  'title'   => 'Menu background hover color',
	  'default' => '#282828',
	),
	array(
	  'id'      => 'menu_text_color',
	  'type'    => 'color_picker',
	  'title'   => 'Menu text color',
	  'default' => '#828282',
	),
	array(
	  'id'      => 'menu_text_color_hover',
	  'type'    => 'color_picker',
	  'title'   => 'Menu text hover color ',
	  'default' => '#ca3028',
	),

	array(
	  'id'      => 'boder_size',
	  'type'    => 'number',
	  'title'   => 'Menu border Size',
	  'default' => '1',
	),
	array(
	  'id'      => 'menu_boder_bottom',
	  'type'    => 'color_picker',
	  'title'   => 'Menu borders(bottom) color',
	  'default' => '#2f2f2f',
	),
	
 	
	
	 array(
		  'id'      => 'somoot_hover',
		  'type'    => 'switcher',
		  'title'   => 'Smooth hover',
		  'default' => true,
		), 
	 


  )
);

// ------------------------------
// backup                       -
// ------------------------------
$eds_responsive_menu_options[]   = array(
  'name'     => 'sub_menu_options',
  'title'    => 'Sub Menu',
  'icon'     => 'fa fa-tablet',
  'fields'   => array(
  array(
		  'id'      => 'arrows_color',
		  'type'    => 'color_picker',
		  'title'   => 'Arrow Color',
		  'default' => '#FFF',
		),
 array(
		  'id'      => 'arrows_bg',
		  'type'    => 'color_picker',
		  'title'   => 'Arrow background color',
		  'info' 	=> 'If you don\t like use bg color for arrow then just make empty'
		),
	array(
	  'id'      => 'show_sub_menu_way',
	  'type'    => 'select',
	  'title'   => 'How sub menu Show ?',
	  'attributes' => array(
		'style'    => 'height:35px; border-color: #93C054;'
		),
	  'options' => array(
		'side_'   => 'Side Shift',
	  )
	  ,
	  'after'=>'<a href="https://edatastyle.com/product/eds-reponsive-menu/" target="_blank" style="color:#F00">upgrade to pro </a> get 3 more style'
	),
		
  array(
	'id'      => 'sub_font_size',
	'type'    => 'text',
	'default' => '14',
	 'validate' => 'numeric',
	'title'   => 'Sub Menu Font Size',
		'attributes' => array(
			'style'    => ' width:100px; height:35px; border-color: #93C054;'
		),
	  'after' => ' <i class="eds-text-muted">px</i>',
    ),	
  array(
	'id'      => 'sub_line_height',
	'type'    => 'text',
	'default' => '25',
	 'validate' => 'numeric',
	'title'   => 'Sub Menu line Height',
		'attributes' => array(
			'style'    => ' width:100px; height:35px; border-color: #93C054;'
		),
	  'after' => ' <i class="eds-text-muted">px</i>',
    ),
	  array(
		'id'      => 'sub_seperator',
		'type'    => 'text',
		'default' => '7,20,7,30',
		'title'   => 'Sub menu Seperate',
			'attributes' => array(
				'style'    => '  height:35px; border-color: #93C054;'
			),
		 'info' => ' <i class="eds-text-muted">(PX) Example: (top,right,bottom,left) = (5,10,5,20) px</i>',
		),
		array(
		  'id'      => 'sub_menu_text_color',
		  'type'    => 'color_picker',
		  'title'   => 'Sub Menu text color',
		  'default' => '#eeeeee',
		),
		array(
		  'id'      => 'sub_menu_text_color_hover',
		  'type'    => 'color_picker',
		  'title'   => 'Sub Menu text hover color ',
		  'default' => '#9dabab',
		),
		
		array(
		  'id'      => 'sub_menu_bg_color',
		  'type'    => 'color_picker',
		  'title'   => 'Sub Menu background color',
		  'default' => '#2E2E2E',
		),
		array(
		  'id'      => 'sub_menu_bg_color_hover',
		  'type'    => 'color_picker',
		  'title'   => 'Sub Menu background hover color',
		  'default' => '#1a1a20',
		),
		array(
		  'id'      => 'sub_menu_boder_size',
		  'type'    => 'number',
		  'title'   => 'Sub menu border Size',
		  'default' => '1',
		),
		array(
		  'id'      => 'sub_menu_boder_bottom',
		  'type'    => 'color_picker',
		  'title'   => 'Sub Menu borders(bottom) color',
		  'default' => '#29292d',
		),

  )
);

// ------------------------------
// Addition Setup                   -
// ------------------------------

$eds_responsive_menu_options[]   = array(
  'name'     => 'eds_addition_setup',
  'title'    => 'Addition Setup ',
  'icon'     => 'fa fa-share-alt',
  'fields'   => array(

		array(
		  'id'        => 'eds_menu_logo',
		  'type'      => 'image',
		  'title'     => 'Select Logo',
		  'desc'      => 'Don\'t select any image to disable !',
		  
		),
		array(
		  'id'           => 'eds_logo_positions',
		  'type'         => 'select',
		  'title'        => 'Logo positions',
		  'options'      => array(
			'top'       => 'Top',
			'bottom'     => 'Bottom',
		  ),
		),
		array(
			'id'      => 'search_box_mode',
			'type'    => 'switcher',
			'title'   => 'Search Box',
			 'label'   => 'You want to disable Search Box ?',
			 'default' => true,
		),
		array(
		  'id'           => 'eds_search_positions',
		  'type'         => 'select',
		  'title'        => 'Search Box positions',
		  'options'      => array(
			'top'       => 'Top',
			'bottom'     => 'Bottom',
		  ),
		),
		array(
			'id'      => 'eds_social_profile',
			'type'    => 'switcher',
			'title'   => 'Social Profile !',
			'label'   => 'You want to disable Social Profile ? <a href="https://edatastyle.com/product/eds-reponsive-menu/" target="_blank" style="color:#F00">upgrade to pro </a>',
			 'default' => false,
			'attributes' => array(
				'disabled' => 'disabled'
			),
		),
		array(
		  'id'      => 'eds_social_profile_bg',
		  'type'    => 'color_picker',
		  'title'   => 'Social Profile background color',
		  'default' => '#fff',
		),
		array(
		  'id'      => 'eds_social_profile_color',
		  'type'    => 'color_picker',
		  'title'   => 'Social Profile color',
		  'default' => '#000',
		),
		array(
		  'id'           => 'eds_icon_type',
		  'type'         => 'select',
		  'title'        => 'Social Profile Icon Type',
		  'options'      => array(
			'round'       => 'Round',
			'square'     => 'Square',
		  ),
		),
		array(
		'id'      => 'eds_fb',
		'type'    => 'text',
		'default' => '',
		'title'   => 'Facebook',
			'attributes' => array(
				'style'    => ' width:100%; height:35px; border-color: #93C054;',
				'disabled' => 'disabled'
			),
			'info'=>'<a href="https://edatastyle.com/product/eds-reponsive-menu/" target="_blank" style="color:#F00">upgrade to pro </a>'
		),
		array(
		'id'      => 'eds_tw',
		'type'    => 'text',
		'default' => '',
		'title'   => 'Twitter',
			'attributes' => array(
				'style'    => ' width:100%; height:35px; border-color: #93C054;',
				'disabled' => 'disabled'
			),
			'info'=>'<a href="https://edatastyle.com/product/eds-reponsive-menu/" target="_blank" style="color:#F00">upgrade to pro </a>'
		),
		array(
		'id'      => 'eds_g_plus',
		'type'    => 'text',
		'default' => '',
		'title'   => 'Google+',
			'attributes' => array(
				'style'    => ' width:100%; height:35px; border-color: #93C054;',
				'disabled' => 'disabled'
			),
			'info'=>'<a href="https://edatastyle.com/product/eds-reponsive-menu/" target="_blank" style="color:#F00">upgrade to pro </a>'
		),
		array(
		'id'      => 'eds_lin',
		'type'    => 'text',
		'default' => '',
		'title'   => 'linkedin',
			'attributes' => array(
				'style'    => ' width:100%; height:35px; border-color: #93C054;',
				'disabled' => 'disabled'
			),
			'info'=>'<a href="https://edatastyle.com/product/eds-reponsive-menu/" target="_blank" style="color:#F00">upgrade to pro </a>'
		),
		array(
		'id'      => 'eds_ins',
		'type'    => 'text',
		'default' => '',
		'title'   => 'Instagram',
			'attributes' => array(
				'style'    => ' width:100%; height:35px; border-color: #93C054;',
				'disabled' => 'disabled'
			),
			'info'=>'<a href="https://edatastyle.com/product/eds-reponsive-menu/" target="_blank" style="color:#F00">upgrade to pro </a>'
		),
		array(
		'id'      => 'eds_pin',
		'type'    => 'text',
		'default' => '',
		'title'   => 'Pinterest',
			'attributes' => array(
				'style'    => ' width:100%; height:35px; border-color: #93C054;',
				'disabled' => 'disabled'
			),
		'info'=>'<a href="https://edatastyle.com/product/eds-reponsive-menu/" target="_blank" style="color:#F00">upgrade to pro </a>'
		),
  )
);



// ------------------------------
// backup                       -
// ------------------------------
$eds_responsive_menu_options[]   = array(
  'name'     => 'backup_section',
  'title'    => 'Backup/Import/Export',
  'icon'     => 'fa fa-shield',
  'fields'   => array(

    array(
      'type'    => 'notice',
      'class'   => 'warning',
      'content' => 'You can save your current options. Download a Backup and Import.',
    ),

    array(
      'type'    => 'backup',
    ),

  )
);
EDSFramework::instance( $eds_responsive_menu_settings, $eds_responsive_menu_options );
