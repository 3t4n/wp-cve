<?php

/* ===================================
    General
   =================================== */
   FLBuilderCSS::responsive_rule(
	    array(
		    'settings'		=> $settings,
		    'setting_name'	=> 'align',
		    'enabled'  =>  ('8' !== $settings->layout && '9' !== $settings->layout  ),
		    'selector'		=> ".fl-node-$id .xpro-team-wrapper",
		    'prop'			=> 'text-align',
		)
   );

/* ===================================
    Image
   =================================== */
   FLBuilderCSS::responsive_rule(
	    array(
		    'settings'		=> $settings,
		    'setting_name'	=> 'width',
		    'selector'		=> ".fl-node-$id .xpro-team-wrapper .xpro-team-image > img",
		    'prop'			=> 'width',
		    'unit'			=> 'px',
		)
   );

   FLBuilderCSS::responsive_rule(
	    array(
		    'settings'		=> $settings,
		    'setting_name'	=> 'height',
		    'selector'		=> ".fl-node-$id .xpro-team-wrapper .xpro-team-image > img",
		    'prop'			=> 'height',
		    'unit'			=> 'px',
		)
   );

   FLBuilderCSS::rule(
	    array(
		    'selector' 	=> ".fl-node-$id .xpro-team-wrapper .xpro-team-image > img",
		    'props' 	=> array(
		        'object-fit' => $settings->object_fit,
		    ),
	    )
   );

   FLBuilderCSS::rule(
	    array(
		    'selector' 	=> ".fl-node-$id .xpro-team-layout-13::after",
		    'props' 	=> array(
		        'background-color' => $settings->shape_color,
			),
	    )
   );

   FLBuilderCSS::rule(
	    array(
		    'selector' 	=> ".fl-node-$id .xpro-team-layout-5 .xpro-team-image::before,.fl-node-$id .xpro-team-layout-12 .xpro-team-image::after",
		    'props' 	=> array(
		        'background-color' => $settings->image_overlay,
			),
	    )
   );

   FLBuilderCSS::rule(
	    array(
		    'selector' 	=> ".fl-node-$id .xpro-team-layout-13:hover::after",
		    'props' 	=> array(
		        'background-color' => $settings->shape_hcolor,
			),
	    )
   );

   FLBuilderCSS::responsive_rule(
	    array(
		    'settings'		=> $settings,
		    'setting_name'	=> 'background_hover_transition',
		    'selector'		=> ".fl-node-$id .xpro-team-wrapper .xpro-team-image img",
		    'prop'			=> 'transition-duration',
		    'unit'			=> 's',
		)
   );

   FLBuilderCSS::border_field_rule(
	    array(
		    'settings' 		=> $settings,
		    'setting_name' 	=> 'image_border',
		    'selector' 		=> ".fl-node-$id .xpro-team-wrapper .xpro-team-image > img",
	    )
   );

   FLBuilderCSS::dimension_field_rule(
	    array(
		    'settings'		=> $settings,
		    'setting_name' 	=> 'image_padding',
		    'selector' 		=> ".fl-node-$id .xpro-team-wrapper .xpro-team-image",
		    'unit'			=> 'px',
		    'props'			=> array(
			    'padding-top' 	 => 'image_padding_top',
			    'padding-right'  => 'image_padding_right',
			    'padding-bottom' => 'image_padding_bottom',
			    'padding-left' 	 => 'image_padding_left',
		    ),
	    )
   );

   FLBuilderCSS::dimension_field_rule(
	    array(
		    'settings'		=> $settings,
		    'setting_name' 	=> 'image_margin',
		    'selector' 		=> ".fl-node-$id .xpro-team-wrapper .xpro-team-image",
		    'unit'			=> 'px',
		    'props'			=> array(
			    'margin-top' 	 => 'image_margin_top',
			    'margin-right'  => 'image_margin_right',
			    'margin-bottom' => 'image_margin_bottom',
			    'margin-left' 	 => 'image_margin_left',
		    ),
	    )
   );

/* ===================================
    Content
   =================================== */
   FLBuilderCSS::responsive_rule(
	    array(
		    'settings'		=> $settings,
		    'setting_name'	=> 'content_height',
		    'selector'		=> ".fl-node-$id .xpro-team-layout-6 .xpro-team-content",
		    'prop'			=> 'height',
		    'unit'			=> 'px',
		)
   );

   FLBuilderCSS::rule(
	    array(
		    'selector' 	=> ".fl-node-$id .xpro-team-layout-6 .xpro-team-content:before",
		    'props' 	=> array(
		        'backdrop-filter' => 'blur(' .$settings->content_backdrop_blur. 'px)',
			),
	    )
   );

   FLBuilderCSS::rule(
	    array(
		    'selector' 	=> ".fl-node-$id .xpro-team-layout-6 .xpro-team-content:before",
		    'media' => 'medium',
		    'props' 	=> array(
		        'backdrop-filter' => 'blur(' .$settings->content_backdrop_blur_medium. 'px)',
			),
	    )
   );

   FLBuilderCSS::rule(
	    array(
		    'selector' 	=> ".fl-node-$id .xpro-team-layout-6 .xpro-team-content:before",
		    'media' => 'responsive',
		    'props' 	=> array(
		        'backdrop-filter' => 'blur(' .$settings->content_backdrop_blur_responsive. 'px)',
			),
	    )
   );

   FLBuilderCSS::rule(
	    array(
		    'selector' 	=> ".fl-node-$id .xpro-team-wrapper .xpro-team-content,.fl-node-$id .xpro-team-layout-9 .xpro-team-inner-content",
		    'props' 	=> array(
		        'background-color' => $settings->content_background,
			),
	    )
   );

   FLBuilderCSS::border_field_rule(
	    array(
		    'settings' 		=> $settings,
		    'setting_name' 	=> 'content_border',
		    'selector' 		=> ".fl-node-$id .xpro-team-wrapper .xpro-team-content",
	    )
   );

   FLBuilderCSS::rule(
	    array(
		    'selector' 	=> ".fl-node-$id .xpro-team-layout-9 .xpro-team-description::before",
		    'props' 	=> array(
		        'background-color' => $settings->separator_color,
			),
	    )
   );

   FLBuilderCSS::dimension_field_rule(
	    array(
		    'settings'		=> $settings,
		    'setting_name' 	=> 'content_padding',
		    'selector' 		=> ".fl-node-$id .xpro-team-wrapper .xpro-team-content,.fl-node-$id .xpro-team-layout-9 .xpro-team-description",
		    'unit'			=> 'px',
		    'props'			=> array(
			    'padding-top' 	 => 'content_padding_top',
			    'padding-right'  => 'content_padding_right',
			    'padding-bottom' => 'content_padding_bottom',
			    'padding-left' 	 => 'content_padding_left',
		    ),
	    )
   );

   // Title
   FLBuilderCSS::typography_field_rule(
	    array(
		'settings'		=> $settings,
		'setting_name' 	=> 'title_typography',
		'selector' 		=> ".fl-node-$id .xpro-team-wrapper .xpro-team-title",
		)
   );

   FLBuilderCSS::rule(
	    array(
		    'selector' 	=> ".fl-node-$id .xpro-team-wrapper .xpro-team-title",
		    'props' 	=> array(
		        'color' => $settings->title_color,
			),
	    )
   );

   FLBuilderCSS::dimension_field_rule(
	    array(
		    'settings'		=> $settings,
		    'setting_name' 	=> 'title_margin',
		    'selector' 		=> ".fl-node-$id .xpro-team-wrapper .xpro-team-title",
		    'unit'			=> 'px',
		    'props'			=> array(
			    'margin-top' 	 => 'title_margin_top',
			    'margin-right'  => 'title_margin_right',
			    'margin-bottom' => 'title_margin_bottom',
			    'margin-left' 	 => 'title_margin_left',
		    ),
	    )
   );

   // Designation
   FLBuilderCSS::typography_field_rule(
	    array(
		'settings'		=> $settings,
		'setting_name' 	=> 'designation_typography',
		'selector' 		=> ".fl-node-$id .xpro-team-wrapper .xpro-team-designation",
		)
   );

   FLBuilderCSS::rule(
	    array(
		    'selector' 	=> ".fl-node-$id .xpro-team-wrapper .xpro-team-designation",
		    'props' 	=> array(
		        'color' => $settings->designation_color,
			),
	    )
   );

   FLBuilderCSS::dimension_field_rule(
	    array(
		    'settings'		=> $settings,
		    'setting_name' 	=> 'designation_margin',
		    'selector' 		=> ".fl-node-$id .xpro-team-wrapper .xpro-team-designation",
		    'unit'			=> 'px',
		    'props'			=> array(
			    'margin-top' 	 => 'designation_margin_top',
			    'margin-right'  => 'designation_margin_right',
			    'margin-bottom' => 'designation_margin_bottom',
			    'margin-left' 	 => 'designation_margin_left',
		    ),
	    )
   );

   // Description
   FLBuilderCSS::typography_field_rule(
	    array(
		'settings'		=> $settings,
		'setting_name' 	=> 'description_typography',
		'selector' 		=> ".fl-node-$id .xpro-team-wrapper .xpro-team-description",
		)
   );

   FLBuilderCSS::rule(
	    array(
		    'selector' 	=> ".fl-node-$id .xpro-team-wrapper .xpro-team-description",
		    'props' 	=> array(
		        'color' => $settings->description_color,
			),
	    )
   );

   FLBuilderCSS::dimension_field_rule(
	    array(
		    'settings'		=> $settings,
		    'setting_name' 	=> 'description_margin',
		    'selector' 		=> ".fl-node-$id .xpro-team-wrapper .xpro-team-description",
		    'unit'			=> 'px',
		    'props'			=> array(
			    'margin-top' 	 => 'description_margin_top',
			    'margin-right'  => 'description_margin_right',
			    'margin-bottom' => 'description_margin_bottom',
			    'margin-left' 	 => 'description_margin_left',
		    ),
	    )
   );

/* ===================================
    Social
   =================================== */
   FLBuilderCSS::responsive_rule(
	    array(
		    'settings'		=> $settings,
		    'setting_name'	=> 'icon_size',
		    'selector'		=> ".fl-node-$id .xpro-team-social-list .xpro-team-social-icon > i",
		    'prop'			=> 'font-size',
		    'unit'			=> 'px',
		)
   );

   FLBuilderCSS::responsive_rule(
	    array(
		    'settings'		=> $settings,
		    'setting_name'	=> 'icon_bg_size',
		    'selector'		=> ".fl-node-$id .xpro-team-social-list .xpro-team-social-icon",
		    'prop'			=> 'width',
		    'unit'			=> 'px',
		)
   );

   FLBuilderCSS::responsive_rule(
	    array(
		    'settings'		=> $settings,
		    'setting_name'	=> 'icon_bg_size',
		    'selector'		=> ".fl-node-$id .xpro-team-social-list .xpro-team-social-icon",
		    'prop'			=> 'height',
		    'unit'			=> 'px',
		)
   );

   FLBuilderCSS::responsive_rule(
	    array(
		    'settings'		=> $settings,
		    'setting_name'	=> 'icon_bg_size',
		    'selector'		=> ".fl-node-$id .xpro-team-wrapper .xpro-team-social-list > li",
		    'prop'			=> 'margin-right',
		    'unit'			=> 'px',
		)
   );

   FLBuilderCSS::responsive_rule(
	    array(
		    'settings'		=> $settings,
		    'setting_name'	=> 'icon_bg_size',
		    'selector'		=> ".fl-node-$id .xpro-team-layout-9 .xpro-team-social-list > li, .xpro-team-layout-13 .xpro-team-social-list > li, .xpro-team-layout-15 .xpro-team-social-list > li",
		    'prop'			=> 'margin-bottom',
		    'unit'			=> 'px',
		)
   );

   FLBuilderCSS::rule(
	    array(
		    'selector' 	=> ".fl-node-$id .xpro-team-social-list .xpro-team-social-icon > i",
		    'props' 	=> array(
		        'color' => $settings->icon_color,
			),
	    )
   );

   FLBuilderCSS::rule(
	    array(
		    'selector' 	=> ".fl-node-$id .xpro-team-social-list .xpro-team-social-icon",
		    'props' 	=> array(
		        'background-color' => $settings->icon_bg,
			),
	    )
   );

   FLBuilderCSS::rule(
	    array(
		    'selector' 	=> ".fl-node-$id .xpro-team-layout-15 .xpro-team-social-list",
		    'props' 	=> array(
		        'background-color' => $settings->icon_wrapper_bg,
			),
	    )
   );

   FLBuilderCSS::rule(
	    array(
		    'selector' 	=> ".fl-node-$id .xpro-team-social-list .xpro-team-social-icon:hover > i,.fl-node-$id .xpro-team-social-list .xpro-team-social-icon:focus > i",
		    'props' 	=> array(
		        'color' => $settings->icon_hover_color,
			),
	    )
   );

   FLBuilderCSS::rule(
	    array(
		    'selector' 	=> ".fl-node-$id .xpro-team-social-list .xpro-team-social-icon:hover,.fl-node-$id .xpro-team-social-list .xpro-team-social-icon:focus",
		    'props' 	=> array(
		        'background-color' => $settings->icon_hbg,
		        'border-color' => $settings->icon_border_hover_color,
			),
	    )
   );

   FLBuilderCSS::border_field_rule(
	    array(
		    'settings' 		=> $settings,
		    'setting_name' 	=> 'icon_border',
		    'selector' 		=> ".fl-node-$id .xpro-team-social-list .xpro-team-social-icon",
	    )
   );

   // Wrapper
   FLBuilderCSS::rule(
	    array(
		    'selector' 	=> ".fl-node-$id .xpro-team-layout-8 .xpro-team-social-list,.fl-node-$id .xpro-team-layout-9 .xpro-team-social-list,.fl-node-$id .xpro-team-layout-15 .xpro-team-social-list",
		    'props' 	=> array(
		        'background-color' => $settings->icon_wrapper_background,
			),
	    )
   );

   FLBuilderCSS::border_field_rule(
	    array(
		    'settings' 		=> $settings,
		    'setting_name' 	=> 'icon_wrapper_border',
		    'selector' 		=> ".fl-node-$id .xpro-team-layout-8 .xpro-team-social-list,.fl-node-$id .xpro-team-layout-9 .xpro-team-social-list,.fl-node-$id .xpro-team-layout-15 .xpro-team-social-list",
	    )
   );

   FLBuilderCSS::dimension_field_rule(
	    array(
		    'settings'		=> $settings,
		    'setting_name' 	=> 'icon_wrapper_padding',
		    'selector' 		=> ".fl-node-$id .xpro-team-layout-8 .xpro-team-social-list,.fl-node-$id .xpro-team-layout-15 .xpro-team-social-list,.fl-node-$id .xpro-team-layout-9 .xpro-team-social-list",
		    'unit'			=> 'px',
		    'props'			=> array(
			    'padding-top' 	 => 'icon_wrapper_padding_top',
			    'padding-right'  => 'icon_wrapper_padding_right',
			    'padding-bottom' => 'icon_wrapper_padding_bottom',
			    'padding-left' 	 => 'icon_wrapper_padding_left',
		    ),
	    )
   );

   FLBuilderCSS::dimension_field_rule(
	    array(
		    'settings'		=> $settings,
		    'setting_name' 	=> 'icon_wrapper_margin',
		    'selector' 		=> ".fl-node-$id .xpro-team-layout-8 .xpro-team-social-list",
		    'unit'			=> 'px',
		    'props'			=> array(
			    'margin-top' 	 => 'icon_wrapper_margin_top',
			    'margin-right'  => 'icon_wrapper_margin_right',
			    'margin-bottom' => 'icon_wrapper_margin_bottom',
			    'margin-left' 	 => 'icon_wrapper_margin_left',
		    ),
	    )
   );

   if ( $settings->layout === '13' ){
       FLBuilderCSS::rule(
	        array(
		    'selector' 	=> ".fl-node-$id .xpro-team-layout-13::after",
		        'props' 	=> array(
		            '-webkit-mask-image' => XPRO_ADDONS_FOR_BB_URL . 'modules/xpro-team/images/team-shape-2.png',
		            'mask-image' => XPRO_ADDONS_FOR_BB_URL . 'modules/xpro-team/images/team-shape-2.png',
			    ),
	        )
        );
   }

/* ===================================
    Inline Css
   =================================== */
   for ( $i = 0; $i < count( $settings->social_icon_list ); $i++ ) {
       $item = $settings->social_icon_list[ $i ];


   FLBuilderCSS::rule(
	    array(
		    'selector' 	=> ".fl-node-$id .xpro-team-social-list .xpro-team-item-$i .xpro-team-social-icon > i",
		    'props' 	=> array(
		        'color' => $item->icon_inline_color,
			),
	    )
   );

   FLBuilderCSS::rule(
	    array(
		    'selector' 	=> ".fl-node-$id .xpro-team-social-list .xpro-team-item-$i .xpro-team-social-icon",
		    'props' 	=> array(
		        'background-color' => $item->icon_inline_bg,
		        'border-color' => $item->icon_inline_border,
			),
	    )
   );

   FLBuilderCSS::rule(
	    array(
		    'selector' 	=> ".fl-node-$id .xpro-team-social-list .xpro-team-item-$i .xpro-team-social-icon:hover > i,.fl-node-$id .xpro-team-social-list .xpro-team-item-$i .xpro-team-social-icon:focus > i ",
		    'props' 	=> array(
		        'color' => $item->icon_inline_hover_color,
			),
	    )
   );

   FLBuilderCSS::rule(
	    array(
		    'selector' 	=> ".fl-node-$id .xpro-team-social-list .xpro-team-item-$i .xpro-team-social-icon:hover,.fl-node-$id .xpro-team-social-list .xpro-team-item-$i .xpro-team-social-icon:focus",
		    'props' 	=> array(
		        'background-color' => $item->icon_inline_hover_bg,
		        'border-color' => $item->icon_inline_border_hcolor,
			),
	    )
   );

   }