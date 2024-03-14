jQuery(document).ready(function($) {
	
	//FIX/HIDE RICH CONTENT SNIPPET PLUGIN CONFLICT  https://wordpress.org/support/topic/plugin-conflict-all-in-one-schemaorg-rich-snippets?replies=2#post-8641169
	$( '#review_metabox' ).hide();
	
	$( '#dh_ptp_tabs_container' ).show();
	$( '#dh_ptp_loading' ).hide();
   
	$( "#dh_ptp_tabs_container" ).tabs();
	// Save tab state to dh_ptp_tab
	$( "a[href='#dh_ptp_tabs_1'], a[href='#dh_ptp_tabs_2'], a[href='#dh_ptp_tabs_3']" ).on( 'click', function(){
		$( '#dh_ptp_tab' ).val($(this).attr( 'href' ));
	});
	
	if ( window.location.href.indexOf( "action=edit" ) > -1 ) {
		$( "a[href='#dh_ptp_tabs_1']" ).trigger( 'click' );
	}

	//drag and drop for columns
	$( "#wpa_loop-column" ).sortable({
		axis: "x"
	});

	//activate color pickers
	$( '.button-color' ).wpColorPicker({
		palettes: ['#1abc9c', '#2ecc71','#3498db', '#9b59b6', '#34495e', '#f1c40f', '#e67e22', '#e74c3c', '#95a5a6']
	});
	$( '.button-border-color' ).wpColorPicker({
		palettes: ['#16a085', '#27ae60','#2980b9', '#8e44ad', '#2c3e50', '#f39c12', '#d35400', '#c0392b', '#ffffff']
	});
	$( '.colorpicker-no-palettes' ).wpColorPicker();
	
	$( '.button-gradient-picker-color' ).wpColorPicker({
		palettes: ['#16a085', '#27ae60','#2980b9', '#8e44ad', '#2c3e50', '#f39c12', '#d35400', '#c0392b', '#7f8c8d'],
		change: function( event, ui ) {
			var that = $(this);
			var hexcolor = [];
			var i = 0;
			$( '.button-gradient-picker-color' ).each(function() {
				if( that.data( 'color-group' ) == $(this).data( 'color-group' ) ) {
					hexcolor[i] = $(this).wpColorPicker( 'color' );
					i++;
				}
			});
			tt_updateGradient( hexcolor[0], hexcolor[1], hexcolor[2], that.data( 'preview-id' ) );
		}
	});

   $( '.tt-gradient-picker-color-preview' ).each(function() {
		var id = '#'+$(this).attr( 'id' );
		var hexcolor = [];
		var i = 0;
		$( '.button-gradient-picker-color' ).each(function() {
			if( id == $(this).data( 'preview-id' ) ) {
				hexcolor[i] = $(this).wpColorPicker( 'color' );
				i++;
			}
		});

		tt_updateGradient( hexcolor[0], hexcolor[1], hexcolor[2], id );
	});

	//make sure that only decimal numbers are allowed to input. 
	//source: http://jqueryexamples4u.blogspot.in/2013/09/validate-input-field-allows-only-float.html
	$( '.float-input' ).keypress(function(event) {
		if ((event.which != 46 || $(this).val().indexOf( '.' ) != -1) && (event.which < 48 || event.which > 57)) {
			event.preventDefault();
		} 
	});

	//enable lightbox
	$( ".inline-lightbox" ).colorbox({
		inline:true, 
		width:"50%", 
		speed: 0, 
		fadeOut: 0
	});

	//set settings visibility based on currently selected theme
	setAdvancedDesignSettingsVisibility( '.template-selected' );
	
	/* color palettes */
	$( '.palette' ).colorpalettes({
		palettes: ['#34495e/#e74c3c', '#16a085/#ce3306', '#66317d/#0a8bb0', '#08a7bd/#a81818', '#138f6b/#46b527', '#417d83/#ee4c7e', '#27b18f/#ecce44', '#b97a68/#f37e5d', '#114d68/#9e0165', '#444588/#ee1367'],
		change: function(event, ui) {
			// Handle palette click event
			var ballon = $(this).closest( '.dh_ptp_color_palettes_container' );
			var new_colors = ui.color.split( '/' );
			
			if (new_colors.length > 1) {
				ballon.find( '.dh_ptp_color_palettes_result' ).css( 'background-color', new_colors[0]);
				ballon.find( '.dh_ptp_color_palettes_result span' ).css( 'background-color', new_colors[1]);
				ballon.find( '.dh_ptp_color_palettes_result span' ).css( 'display', 'block' );
			} else {
				ballon.find( '.dh_ptp_color_palettes_result' ).css( 'background-color', new_colors[0]);
				ballon.find( '.dh_ptp_color_palettes_result span' ).css( 'display', 'none' );
			}
			
		}
	});
	/* color palettes */
	$( '.palette.tt-palette-cp3' ).colorpalettes({
		palettes: ['#34495e/#e74c3c', '#16a085/#ce3306', '#66317d/#0a8bb0', '#08a7bd/#a81818', '#138f6b/#46b527', '#417d83/#ee4c7e', '#27b18f/#ecce44', '#b97a68/#f37e5d', '#114d68/#9e0165','#ffffff/#34495e'],
		change: function(event, ui) {
			// Handle palette click event
			var ballon = $(this).closest( '.dh_ptp_color_palettes_container' );
			var new_colors = ui.color.split( '/' );

			if (new_colors.length > 1) {
				ballon.find( '.dh_ptp_color_palettes_result' ).css( 'background-color', new_colors[0]);
				ballon.find( '.dh_ptp_color_palettes_result span' ).css( 'background-color', new_colors[1]);
				ballon.find( '.dh_ptp_color_palettes_result span' ).css( 'display', 'block' );
			} else {
				ballon.find( '.dh_ptp_color_palettes_result' ).css( 'background-color', new_colors[0]);
				ballon.find( '.dh_ptp_color_palettes_result span' ).css( 'display', 'none' );
			}
			
		}
	});
	$( '.dh_ptp_color_palettes_result' ).on( 'click', function(){
		if ($(this).parent().find( '.color-palettes' ).is( ":visible" )) {
			$(this).parent().find( '.color-palettes' ).hide();
		} else {
			$(this).parent().find( '.color-palettes' ).show();
		}
	});
	
	/* easy palette button */
	$( '.color-palette.ptp-fancy-column-color-scheme' ).easypalette({
		palettes: ['#f77fa8', '#ef5f54', '#f15477', '#a176c3', '#6b89c9', '#49a9d3', '#59b7b7', '#9ca46b', '#92d590', '#b9c869', '#79714c', '#9d7d60', '#dca562', '#ffa14f', '#ffe177'],
		change: function(event, ui) {
			// Handle palette click event
			var ballon = $(this).closest( '.dh_ptp_easy_palette_container' );
			var new_color = ui.color.toString();

			ballon.find( '.dh_ptp_palette_result' ).css( 'background-color', new_color);
		}
	});
	
	$( '.color-palette.ptp-stlyish-column-color-scheme' ).easypalette({
		palettes: ['#456366', '#696758', '#36393B', '#496449', '#3f4953', '#a64324', '#712941', '#493c45', '#742222', '#b28d19'],
		change: function(event, ui) {
			// Handle palette click event
			var ballon = $(this).closest( '.dh_ptp_easy_palette_container' );
			var new_color = ui.color.toString();

			ballon.find( '.dh_ptp_palette_result' ).css( 'background-color', new_color);
		}
	});
	
	/* Design 4 column color scheme */
	$( '.color-palette.ptp-design4-color-scheme' ).easypalette({
		palettes: ['#6baba1', '#e0a32e', '#e7603b', '#9ca780'], 
		change: function(event, ui) {
			// Handle palette click event
			var ballon = $(this).closest( '.dh_ptp_easy_palette_container' );
			var new_color = ui.color.toString();

			ballon.find( '.dh_ptp_palette_result' ).css( 'background-color', new_color);
		}
	});
	
	
	$( '.dh_ptp_palette_result' ).on( 'click', function(){
		if ($(this).parent().find( '.easy-palette' ).is( ":visible" )) {
			$(this).parent().find( '.easy-palette' ).hide();
		} else {
			$(this).parent().find( '.easy-palette' ).show();
		}
	});
	
	// Close when clicked outside 
	$( 'body' ).on( 'click', function( event ) {
		var $target = $(event.target);
		if (!$target.parents().is( ".dh_ptp_easy_palette_container" )){
			$( ".easy-palette" ).hide();
		}
		if (!$target.parents().is( ".dh_ptp_color_palettes_container" )) {
			$( ".color-palettes" ).hide();
		}
	});
	
	// Save & Preview button
	$( '#save_preview' ).on( 'click', function(event) {
		event.preventDefault();
		// Add target
		var form = $(this).closest( 'form' );
		form.prop( 'target', '_blank' );
		
		// Add preview_url parameter
		var url = $(this).attr( 'data-url' );
		if ($( '#dh_ptp_preview_url' )) {
			$( '#dh_ptp_preview_url' ).remove();
		}
		var preview_url_input = '<input type="hidden" name="dh_ptp_preview_url" id="dh_ptp_preview_url" value="' + url + '"/>';
		$(this).after(preview_url_input);
		
		// Submit form
		form.submit();
		  
		return false;
	});
	
	$( '#save' ).on( 'click', function(event) {
		event.preventDefault();
		
		// Add target
		var form = $(this).closest( 'form' );
		form.removeAttr( 'target' );

		// Remove preview url
		$( '#dh_ptp_preview_url' ).remove();
		
		// Submit form
		form.submit();
		  
		return false;
	});
	
	$( 'form' ).submit(function() {
		$(window).unbind( 'beforeunload' )
	})
	
	
	// Design 2 options
	$( '.design2-choose-your-colors' ).change(function() {
		if ($(this).is( ':checked' ) && $(this).val() == 2) {
			$( '.design2-color-scheme-1' ).addClass( 'hide' );
			$( '.design2-color-scheme-2' ).removeClass( 'hide' );
						
						
			/*************************Change *****************************************
		 *When a user picks a color scheme under 'Use a pre-built color scheme" option,
		 * change the colors selected in "Build my own color scheme" 
		 *so that if the user decides to instead "Build my own color scheme" he sees the same colors as he previously had.
		******************************************************************/
			var design2_unfeatured_plan_title_background_color = $( '#design2-unfeatured-plan-title-background-color' ).parent().parent().find( '.wp-color-result' );
			var new_color = rgb2hex ($( '.fancy-flat-column-color-scheme-value' ).css( 'background-color' ));
			$( '#design2-unfeatured-plan-title-background-color' ).val(new_color.toString());
			design2_unfeatured_plan_title_background_color.css( 'background-color', new_color.toString());
					 
					 
			var design2_featured_plan_title_background_color = $( '#design2-featured-plan-title-background-color-value' ).parent().parent().find( '.wp-color-result' );
			var new_color = rgb2hex ($( '.fancy-flat-featured-column-color-scheme-value' ).css( 'background-color' ));
			$( '#design2-featured-plan-title-background-color-value' ).val(new_color.toString());
			design2_featured_plan_title_background_color.css( 'background-color', new_color.toString());

			 
		   
			//show Most Popular Text   
			if( $( '.design2-featured-label' ).prop( 'checked' )) {
				$( '.design2-featured-label-text-item' ).show();
			} else {
				$( '.design2-featured-label-text-item' ).hide();
			}
				   
			// Featured Ribbon Text
				   
			if ($( '.design2-featured-ribbon' ).prop( 'checked' )) {
				$( '.design2-featured-ribbon-text-item' ).each(function() {
					$(this).show();
				});
			} else {
				$( '.design2-featured-ribbon-text-item' ).each(function() {
					$(this).hide();
				});
			}
			
			tt_check_change_color_hover_option_for_design2_color();	 
						
		} else {
			$( '.design2-color-scheme-1' ).removeClass( 'hide' );
			$( '.design2-color-scheme-2' ).addClass( 'hide' );
			tt_check_for_show_color_option_for_own_design2_color();
						
		}	
				
				
						 
	});
	
		
		
	// Design 3 options
	$( '.design3-choose-your-colors' ).change(function() {
		if ($(this).is( ':checked' ) && $(this).val() == 2) {
			$( '.design3-color-scheme-1' ).addClass( 'hide' );
			$( '.design3-color-scheme-2' ).removeClass( 'hide' );
						
			/*************************Change *****************************************
			* When a user picks a color scheme under 'Use a pre-built color scheme" option,
			* change the colors selected in "Build my own color scheme" 
			* so that if the user decides to instead "Build my own color scheme" he sees the same colors as he previously had.
			******************************************************************/
			var design3_unfeatured_plan_title_background_color = $( '#design3-unfeatured-plan-title-background-color-value' ).parent().parent().find( '.wp-color-result' );
			var new_color = rgb2hex ($( '.stylish-flat-column-color-scheme-value' ).css( 'background-color' ));
			$( '#design3-unfeatured-plan-title-background-color-value' ).val(new_color.toString());
			design3_unfeatured_plan_title_background_color.css( 'background-color', new_color.toString());

			var design3_featured_plan_title_background_color = $( '#design3-featured-plan-title-background-color-value' ).parent().parent().find( '.wp-color-result' );
			var new_color = rgb2hex ($( '.stylish-flat-featured-column-color-scheme-value' ).css( 'background-color' ));
			$( '#design3-featured-plan-title-background-color-value' ).val(new_color.toString());
			design3_featured_plan_title_background_color.css( 'background-color', new_color.toString());
		   
			tt_check_for_dark_or_light_color_option_for_design3_color();   
			tt_check_change_color_hover_option_for_design3_color();
		} else {
			$( '.design3-color-scheme-1' ).removeClass( 'hide' );
			$( '.design3-color-scheme-2' ).addClass( 'hide' );
		}	
		 	
	});
	
	// Comparison 1 options
	$( '.comparison1-choose-your-colors' ).change(function() {
		if ($(this).is( ':checked' ) && $(this).val() == 2) {
			$( '.comparison1-color-scheme-1' ).addClass( 'hide' );
			$( '.comparison1-color-scheme-2' ).removeClass( 'hide' );
						
			/*************************Change *****************************************
			* When a user picks a color scheme under 'Use a pre-built color scheme" option,
			* change the colors selected in "Build my own color scheme" 
			* so that if the user decides to instead "Build my own color scheme" he sees the same colors as he previously had.
			******************************************************************/
						
			var new_colors = $( '#comparison1-choose-a-theme-value' ).val().split( '/' );
			if (new_colors.length > 1) {
				var comparison1_unfeatured_plan_title_background_color = $( '#comparison1-unfeatured-plan-title-background-color-value' ).parent().parent().find( '.wp-color-result' );
				   
				comparison1_unfeatured_plan_title_background_color.css( 'background-color',new_colors[0]);
				$( '#comparison1-unfeatured-plan-title-background-color-value' ).val(new_colors[0]);
								
				var comparison1_featured_plan_title_background_color = $( '#comparison1-featured-plan-title-background-color-value' ).parent().parent().find( '.wp-color-result' );
					
				comparison1_featured_plan_title_background_color.css( 'background-color',new_colors[1]);
				$( '#comparison1-featured-plan-title-background-color-value' ).val( new_colors[1]);
				
			} else {
			
				var comparison1_unfeatured_plan_title_background_color = $( '#comparison1-unfeatured-plan-title-background-color-value' ).parent().parent().find( '.wp-color-result' );
				comparison1_unfeatured_plan_title_background_color.css( 'background-color', new_colors[0]);
				$( '#comparison1-unfeatured-plan-title-background-color-value' ).val(new_colors[0]);
				
			}
			tt_check_change_color_option_for_comparison1_color();
			
		} else {
		
			$( '.comparison1-color-scheme-1' ).removeClass( 'hide' );
			$( '.comparison1-color-scheme-2' ).addClass( 'hide' );
		}
	});
	
	   //Comparision 2
	if( !$( '#comparison2-sticky-header' ).is( ":checked" ) ) {
		$( '.comparison2-sticky-margin-top-row' ).hide();
	}
	   
	$( '#comparison2-sticky-header' ).on( 'click', function() {
		if($(this).is( ':checked' )) {
			$( '.comparison2-sticky-margin-top-row' ).show();			
		} else {
			$( '.comparison2-sticky-margin-top-row' ).hide();
		}
	});
	   
	//activate twitter bootstrap popover
	$( ".ptp-icon-help-circled" ).popover();  
	$( ".plan-title #delete-button" ).popover({
		placement:'top'
	});  
	$( ".plan-title .feature-button" ).popover({
		placement:'top'
	});
	
	//activate jquery accordion ui
	$( ".dh_ptp_accordion" ).accordion({
		icons: false,
		heightStyle: 'content',
		collapsible: true
	});

	if ( $( '.custom-css-setting-textbox' ).length > 0 ) {
		$( '.custom-css-setting-textbox' ).each( function(){
			wp.codeEditor.initialize( $(this), code_mirror)
		})
	}
	
});

// handle clicks on featured button
function buttonHandler(el) {
	
	var $ = jQuery;
	
	// toggle active button via css
	function toggleButtonClasses(el) {
		$(el).toggleClass( 'ptp-icon-star-empty' );
		$(el).toggleClass( 'ptp-icon-star' );
	}
	
	//toggle the value of our hidden input
	function setInputValue(el) {
		if( $(el).val() === "unfeatured" || $(el).val() === "" )
			$(el).val( "featured" );
		else if( $(el).val() === "featured" )
			$(el).val( "unfeatured" );
	}

	// toggles the elements class and value
	function myButtonClickHandler(el) {
		toggleButtonClasses(el);
		setInputValue(el.prev());
	}

	// use hasClass to figure out if current item is selected or not
	if (!$(el).hasClass( 'ptp-icon-star' )) {
		// if the clicked item is not featured, unfeature the currently featured item ( '.ptp-icon-star' ) by sending it to myButtonClickHandler
		myButtonClickHandler($( '.ptp-icon-star' ));
	}

	//	feature the clicked item by sending it to myButtonClickHandler
	myButtonClickHandler( $(el));

	return false;
}


// handle clicks on featured button
function templateSelectorClickedHandler(el) {
	var $ = jQuery;

	// toggle active button via css
	function toggleButtonClasses(el) {
		$(el).toggleClass( 'template-selected' );
	}
	
	//toggle the value of our hidden input
	function setInputValue(el) {
		if( $(el).val() === "not-selected" || $(el).val() === "" )
			$(el).val( "selected" );
		else if( $(el).val() === "selected" )
			$(el).val( "not-selected" );
	}

	// toggles the elements class and value
	function myButtonClickHandler(el) {
		toggleButtonClasses(el);
		setInputValue(el.find( '.template-hidden-input' ));
		setAdvancedDesignSettingsVisibility(el);
	}

	// use hasClass to figure out if current item is selected or not
	if (!$(el).hasClass( 'template-selected' )) {
		// if the clicked item is not featured, unfeature the currently featured item ( '.ptp-icon-star' ) by sending it to myButtonClickHandler
		myButtonClickHandler($( '.template-selected' ));

		//now feature the current item
		myButtonClickHandler( $(el));
		  
		//move to content tab after select templete
		$( "a[href='#dh_ptp_tabs_1']" ).trigger( 'click' );
	} else {
		//move to content tab after select templete
		$( "a[href='#dh_ptp_tabs_1']" ).trigger( 'click' );	
	}

	return false;
}

//set settings visibility 
function setAdvancedDesignSettingsVisibility(el) {
	var $ = jQuery;

	// Set default height for features description column
	$( '.features.explaination-desc' ).css( 'height', '125px' );
	
	//Disable this (optional) text for all tables except CP2
	if ( $(el).hasClass( 'template-selected' ) && $(el).attr( 'id' ) == 'comparison2-selector' ) {
		$( '.ptp-optional-price-label-toggle' ).show();
	} else {
		$( '.ptp-optional-price-label-toggle' ).hide();
	}
		
	if ($(el).attr( 'id' ) == "simple-flat-selector" ) {
		
		// Settings
		$( '#simple-flat-advanced-design-settings' ).show();
		$( '#fancy-flat-advanced-design-settings' ).hide();
		$( '#stylish-flat-advanced-design-settings' ).hide();
		$( '#design4-advanced-design-settings' ).hide();
		$( '#design5-advanced-design-settings' ).hide();		
		$( '#design6-advanced-design-settings' ).hide();		
		$( '#design7-advanced-design-settings' ).hide();
		$( '#comparison1-advanced-design-settings' ).hide();
		$( '#comparison2-advanced-design-settings' ).hide();
		$( '#comparison3-advanced-design-settings' ).hide();
		
		// Content
		$( '.ptp-standard-content' ).show();
		$( '.ptp-design4-content' ).hide();
		$( '.ptp-comparison-content' ).hide();
		$( '.planprice' ).show();
		$( '.ptp-billing-timeframe-content' ).hide(); 
		
		// Price field placeholder
		updatePlaceholder( 'e.g. $49/mo' );
		
	} else if ($(el).attr( 'id' ) == "fancy-flat-selector" ) {
	
		// Settings
		$( '#simple-flat-advanced-design-settings' ).hide();
		$( '#fancy-flat-advanced-design-settings' ).show();
		$( '#stylish-flat-advanced-design-settings' ).hide();
		$( '#design4-advanced-design-settings' ).hide();
		$( '#design5-advanced-design-settings' ).hide();				
		$( '#design6-advanced-design-settings' ).hide();
		$( '#design7-advanced-design-settings' ).hide();
		$( '#comparison1-advanced-design-settings' ).hide();
		$( '#comparison2-advanced-design-settings' ).hide();
		$( '#comparison3-advanced-design-settings' ).hide();
		
		// Content
		$( '.ptp-standard-content' ).show();
		$( '.ptp-design4-content' ).hide();
		$( '.ptp-comparison-content' ).hide();
		$( '.planprice' ).show();
		$( '.ptp-billing-timeframe-content' ).hide();
		
		// Price field placeholder
		updatePlaceholder( 'e.g. $49/mo' );
		
	} else if ($(el).attr( 'id' ) == "stylish-flat-selector" ) {
	
		// Settings
		$( '#simple-flat-advanced-design-settings' ).hide();
		$( '#fancy-flat-advanced-design-settings' ).hide();
		$( '#stylish-flat-advanced-design-settings' ).show();
		$( '#design4-advanced-design-settings' ).hide();
		$( '#design5-advanced-design-settings' ).hide();
		$( '#design6-advanced-design-settings' ).hide();
		$( '#design7-advanced-design-settings' ).hide();
		$( '#comparison1-advanced-design-settings' ).hide();
		$( '#comparison2-advanced-design-settings' ).hide();
		$( '#comparison3-advanced-design-settings' ).hide();
		
		// Content
		$( '.ptp-standard-content' ).show();
		$( '.ptp-design4-content' ).hide();
		$( '.ptp-comparison-content' ).hide();
		$( '.planprice' ).show();
		$( '.ptp-billing-timeframe-content' ).hide();
		
		// Price field placeholder
		updatePlaceholder( 'e.g. $49/mo' );
		
	} else if ($(el).attr( 'id' ) == "design4-selector" ) {
	
		// Settings
		$( '#simple-flat-advanced-design-settings' ).hide();
		$( '#fancy-flat-advanced-design-settings' ).hide();
		$( '#stylish-flat-advanced-design-settings' ).hide();
		$( '#design4-advanced-design-settings' ).show();
		$( '#design5-advanced-design-settings' ).hide();
		$( '#design6-advanced-design-settings' ).hide();
		$( '#design7-advanced-design-settings' ).hide();
		$( '#comparison1-advanced-design-settings' ).hide();
		$( '#comparison2-advanced-design-settings' ).hide();
		$( '#comparison3-advanced-design-settings' ).hide();
		
		// Content
		$( '.ptp-standard-content' ).hide();
		$( '.ptp-design4-content' ).show();
		$( '.ptp-comparison-content' ).hide();
		$( '.planprice' ).show();
		$( '.ptp-billing-timeframe-content' ).hide();
		
		// Price field placeholder
		updatePlaceholder( 'e.g. $49.99' );
		
	} if ($(el).attr( 'id' ) == "design5-selector" ) {
	
		// Settings
		$( '#simple-flat-advanced-design-settings' ).hide();
		$( '#fancy-flat-advanced-design-settings' ).hide();
		$( '#stylish-flat-advanced-design-settings' ).hide();
		$( '#design4-advanced-design-settings' ).hide();
		$( '#design5-advanced-design-settings' ).show();
		$( '#design6-advanced-design-settings' ).hide();
		$( '#design7-advanced-design-settings' ).hide();
		$( '#comparison1-advanced-design-settings' ).hide();
		$( '#comparison2-advanced-design-settings' ).hide();
		$( '#comparison3-advanced-design-settings' ).hide();
		
		// Content
		$( '.ptp-standard-content' ).show();
		$( '.ptp-design4-content' ).hide();
		$( '.ptp-comparison-content' ).hide();
		$( '.ptp-billing-timeframe-content' ).show();
		$( '.planprice' ).show();
		// Price field placeholder
		updatePlaceholder( 'e.g. $49/mo' );
		
	} if ($(el).attr( 'id' ) == "design6-selector" ) {
	
		// Settings
		$( '#simple-flat-advanced-design-settings' ).hide();
		$( '#fancy-flat-advanced-design-settings' ).hide();
		$( '#stylish-flat-advanced-design-settings' ).hide();
		$( '#design4-advanced-design-settings' ).hide();
		$( '#design5-advanced-design-settings' ).hide();
		$( '#design6-advanced-design-settings' ).show();
		$( '#design7-advanced-design-settings' ).hide();
		$( '#comparison1-advanced-design-settings' ).hide();
		$( '#comparison2-advanced-design-settings' ).hide();
		$( '#comparison3-advanced-design-settings' ).hide();
		
		// Content
		$( '.ptp-standard-content' ).show();
		$( '.ptp-design4-content' ).hide();
		$( '.ptp-comparison-content' ).hide();
		$( '.ptp-billing-timeframe-content' ).show();
		$( '.planprice' ).show();
		// Price field placeholder
		updatePlaceholder( 'e.g. $49/mo' );
		
	}  if ($(el).attr( 'id' ) == "design7-selector" ) {
	
		// Settings
		$( '#simple-flat-advanced-design-settings' ).hide();
		$( '#fancy-flat-advanced-design-settings' ).hide();
		$( '#stylish-flat-advanced-design-settings' ).hide();
		$( '#design4-advanced-design-settings' ).hide();
		$( '#design5-advanced-design-settings' ).hide();
		$( '#design6-advanced-design-settings' ).hide();
		$( '#design7-advanced-design-settings' ).show();
		$( '#comparison1-advanced-design-settings' ).hide();
		$( '#comparison2-advanced-design-settings' ).hide();
		$( '#comparison3-advanced-design-settings' ).hide();
		
		// Content
		$( '.ptp-standard-content' ).show();
		$( '.ptp-design4-content' ).hide();
		$( '.ptp-comparison-content' ).hide();
		$( '.ptp-billing-timeframe-content' ).show();
		$( '.planprice' ).show();
		// Price field placeholder
		updatePlaceholder( 'e.g. $49/mo' );
		
	} else if ($(el).attr( 'id' ) == "comparison1-selector" ) {
	
		// Settings
		$( '#simple-flat-advanced-design-settings' ).hide();
		$( '#fancy-flat-advanced-design-settings' ).hide();
		$( '#stylish-flat-advanced-design-settings' ).hide();
		$( '#design4-advanced-design-settings' ).hide();
		$( '#design5-advanced-design-settings' ).hide();
		$( '#design6-advanced-design-settings' ).hide();
		$( '#design7-advanced-design-settings' ).hide();
		$( '#comparison1-advanced-design-settings' ).show();		
		$( '#comparison2-advanced-design-settings' ).hide();
		$( '#comparison3-advanced-design-settings' ).hide();
		
		// Content
		$( '.ptp-standard-content' ).show();
		$( '.ptp-design4-content' ).hide();
		$( '.ptp-comparison-content' ).show();
		$( '.planprice' ).show();
		$( '.ptp-billing-timeframe-content' ).show();
		
		// CSS Tricks
		$( '.features.explaination-desc' ).css( 'height', 'auto' );
		
		// Price field placeholder
		updatePlaceholder( 'e.g. $49.99' );
		
	} else if ($(el).attr( 'id' ) == "comparison2-selector" ) {
	
		// Settings
		$( '#simple-flat-advanced-design-settings' ).hide();
		$( '#fancy-flat-advanced-design-settings' ).hide();
		$( '#stylish-flat-advanced-design-settings' ).hide();
		$( '#design4-advanced-design-settings' ).hide();
		$( '#design5-advanced-design-settings' ).hide();
		$( '#design6-advanced-design-settings' ).hide();
		$( '#design7-advanced-design-settings' ).hide();
		$( '#comparison1-advanced-design-settings' ).hide();
		$( '#comparison2-advanced-design-settings' ).show();
		$( '#comparison3-advanced-design-settings' ).hide();
				
		// Content
		$( '.ptp-standard-content' ).show();
		$( '.ptp-design4-content' ).hide();
		$( '.ptp-comparison-content' ).show();
		$( '.planprice' ).show();
		$( '.hide-line' ).hide();
		$( '.ptp-billing-timeframe-content' ).show();
		$( '.wpa_group-column' ).each(function(){
			$(this).find( '.ptp-billing-timeframe-content' ).first().hide();
		});
		$( '.ptp-billing-timeframe-content.explaination-desc' ).hide();
		 
		$( '.features i' ).removeClass( 'hidden' );
		// CSS Tricks
		$( '.features.explaination-desc' ).css( 'height', 'auto' );
		
		// Price field placeholder
		updatePlaceholder( 'e.g. $49.99' );
	} else if ($(el).attr( 'id' ) == "comparison3-selector" ) {
		// Settings
		$( '#simple-flat-advanced-design-settings' ).hide();
		$( '#fancy-flat-advanced-design-settings' ).hide();
		$( '#stylish-flat-advanced-design-settings' ).hide();
		$( '#design4-advanced-design-settings' ).hide();
		$( '#design5-advanced-design-settings' ).hide();
		$( '#design6-advanced-design-settings' ).hide();
		$( '#design7-advanced-design-settings' ).hide();
		$( '#comparison1-advanced-design-settings' ).hide();
		$( '#comparison2-advanced-design-settings' ).hide();
		$( '#comparison3-advanced-design-settings' ).show();
		
		// Content
		$( '.ptp-standard-content' ).show();
		$( '.ptp-design4-content' ).hide();
		$( '.ptp-comparison-content' ).show();
		$( '.planprice' ).show();
		$( '.ptp-billing-timeframe-content' ).show();
	
		// CSS Tricks
		$( '.features.explaination-desc' ).css( 'height', 'auto' );
		
		// Price field placeholder
		updatePlaceholder( 'e.g. $49.99' );
	}
}

function updatePlaceholder(placeholder_text) {
	jQuery( '.planprice' ).each(function(index) {
		jQuery( this ).attr( 'placeholder', placeholder_text);
	});
}

function rgb2hex(rgb) {
	if (/^#[0-9A-F]{6}$/i.test(rgb)) return rgb;

	rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
	function hex(x) {
		return ( "0" + parseInt(x).toString(16)).slice(-2);
	}
	return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
}


function tt_check_for_show_color_option_for_own_design2_color() {
	var $ = jQuery;
	if ( $( '.design2-choose-your-colors:checked' ).val() === "1" ) { 
		var design2_featured_label_text_item = $( '#tt_ptp_easy-design-color-table-design2' ).find( '.design2-featured-label-text-item' );
		design2_featured_label_text_item.each(function() {
			$(this).hide();
		});
					
		var design2_featured_ribbon_text_item = $( '#tt_ptp_easy-design-color-table-design2' ).find( '.design2-featured-ribbon-text-item' );
		design2_featured_ribbon_text_item.each(function() {
			$(this).hide();
		});
	}
	return false;
}

 /*************************Change *****************************************
 * When a user picks a color scheme under 'Use a pre-built color scheme" option,
 * change the colors selected in "Build my own color scheme" 
 * so that if the user decides to instead "Build my own color scheme" he sees the same colors as he previously had.
 ******************************************************************/
function tt_check_for_dark_or_light_color_option_for_design3_color() {
	var $ = jQuery;
	if($( '.tt-ptp-stylish-flat-table-color-scheme-class:checked' ).val()==="light" ) {
		   
		// color for .ptp-stylish-pricingtable .ptp-stylish-header .subline
		$( '#tt-design3-featured-most-popular-font-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', "#eeeeee" );
		$( '#tt-design3-featured-most-popular-font-color-value' ).val( "#eeeeee" );
					 
		// Plan Title Font Color
		$( '#tt-design3-featured-plan-title-font-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', "#eeeeee" );
		$( '#tt-design3-featured-plan-title-font-color-value' ).val( "#eeeeee" );
		$( '#tt-design3-unfeatured-plan-title-font-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', "#eeeeee" );
		$( '#tt-design3-unfeatured-plan-title-font-color-value' ).val( "#eeeeee" );  
		  
		//.ptp-stylish-header strong.price
		$( '#tt-design3-featured-plan-price-font-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', "#eeeeee" );
		$( '#tt-design3-featured-plan-price-font-color-value' ).val( "#eeeeee" );
					   
		$( '#tt-design3-unfeatured-plan-price-font-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', "#eeeeee" );
		$( '#tt-design3-unfeatured-plan-price-font-color-value' ).val( "#eeeeee" );
		//					   
		//.ptp-stylish-pricingtable .ptp-stylish-content .ptp-stylish-description .ptp-design3-row .has-tip
		$( '#tt-design3-featured-feature-font-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', "#313131" );
		$( '#tt-design3-featured-feature-font-color-value' ).val( "#313131" );
		$( '#tt-design3-unfeatured-feature-font-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', "#313131" );
		$( '#tt-design3-unfeatured-feature-font-color-value' ).val( "#313131" );
					   
		//.ptp-stylish-pricingtable .ptp-stylish-featured .ptp-stylish-pricing_button
		$( '#tt-design3-featured-button-font-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', "#eeeeee" );
		$( '#tt-design3-featured-button-font-color-value' ).val( "#eeeeee" );
		$( '#tt-design3-unfeatured-button-font-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', "#eeeeee" );
		$( '#tt-design3-unfeatured-button-font-color-value' ).val( "#eeeeee" ); 
			 
		// Border Color
		$( '#tt-design3-unfeatured-feature-border-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', "#bbbbbb" );
		$( '#tt-design3-unfeatured-feature-border-color-value' ).val( "#bbbbbb" );
		$( '#tt-design3-featured-feature-border-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', "#bbbbbb" );
		$( '#tt-design3-featured-feature-border-color-value' ).val( "#bbbbbb" ); 
			 
			 
		// Background Color	
		$( '#tt-design3-unfeatured-feature-background-color' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', "#ddd" );
		$( '#tt-design3-unfeatured-feature-background-color' ).val( "#ddd" );
		$( '#tt-design3-featured-feature-background-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', "#ddd" );
		$( '#tt-design3-featured-feature-background-color-value' ).val( "#ddd" );	 
					   
		//Button Color
		$( '#tt-design3-unfeatured-button-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', $( '#design3-unfeatured-plan-title-background-color-value' ).val());
		$( '#tt-design3-unfeatured-button-color-value' ).val($( '#design3-unfeatured-plan-title-background-color-value' ).val());
		$( '#tt-design3-featured-button-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', $( '#design3-featured-plan-title-background-color-value' ).val());
		$( '#tt-design3-featured-button-color-value' ).val( $( '#design3-featured-plan-title-background-color-value' ).val()); 
				 
		// ptp-stylish-bottomheader
		$( '.ptp-stylish-topheader' ).css( 'border-top', "1px solid #8a381e" ); 
					 
	} else {
						  
		// color for .ptp-stylish-pricingtable .ptp-stylish-header .subline
		$( '#tt-design3-featured-most-popular-font-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', "#d4d4d4" );
		$( '#tt-design3-featured-most-popular-font-color-value' ).val( "#d4d4d4" );
				   
		// Plan Title Font Color
		$( '#tt-design3-featured-plan-title-font-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', "#d4d4d4" );
		$( '#tt-design3-featured-plan-title-font-color-value' ).val( "#d4d4d4" );
		$( '#tt-design3-unfeatured-plan-title-font-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', "#d4d4d4" );
		$( '#tt-design3-unfeatured-plan-title-font-color-value' ).val( "#d4d4d4" );
		
		//.ptp-stylish-header strong.price
		$( '#tt-design3-featured-plan-price-font-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', "#d4d4d4" );
		$( '#tt-design3-featured-plan-price-font-color-value' ).val( "#d4d4d4" );
					   
		$( '#tt-design3-unfeatured-plan-price-font-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', "#d4d4d4" );
		$( '#tt-design3-unfeatured-plan-price-font-color-value' ).val( "##d4d4d4" );
					  
		//.ptp-stylish-pricingtable .ptp-stylish-content .ptp-stylish-description .ptp-design3-row .has-tip
		$( '#tt-design3-featured-feature-font-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', "#d4d4d4" );
		$( '#tt-design3-featured-feature-font-color-value' ).val( "#d4d4d4" );
		$( '#tt-design3-unfeatured-feature-font-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', "#d4d4d4" );
		$( '#tt-design3-unfeatured-feature-font-color-value' ).val( "#d4d4d4" );
					 
		//.ptp-stylish-pricingtable .ptp-stylish-featured .ptp-stylish-pricing_button
		$( '#tt-design3-featured-button-font-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', "#d4d4d4" );
		$( '#tt-design3-featured-button-font-color-value' ).val( "#d4d4d4" );
		$( '#tt-design3-unfeatured-button-font-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', "#d4d4d4" );
		$( '#tt-design3-unfeatured-button-font-color-value' ).val( "#d4d4d4" ); 

		// Border Color
		$( '#tt-design3-unfeatured-feature-border-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', "#191919" );
		$( '#tt-design3-unfeatured-feature-border-color-value' ).val( "#191919" );
		$( '#tt-design3-featured-feature-border-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', "#191919" );
		$( '#tt-design3-featured-feature-border-color-value' ).val( "#191919" ); 
		
		// Background Color	
		$( '#tt-design3-unfeatured-feature-background-color' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', "#121212" );
		$( '#tt-design3-unfeatured-feature-background-color' ).val( "#121212" );
		$( '#tt-design3-featured-feature-background-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', "#121212" );
		$( '#tt-design3-featured-feature-background-color-value' ).val( "#121212" ); 
		
		//Button Color
		$( '#tt-design3-unfeatured-button-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', $( '#design3-unfeatured-plan-title-background-color-value' ).val());
		$( '#tt-design3-unfeatured-button-color-value' ).val($( '#design3-unfeatured-plan-title-background-color-value' ).val());
		$( '#tt-design3-featured-button-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', $( '#design3-featured-plan-title-background-color-value' ).val());
		$( '#tt-design3-featured-button-color-value' ).val( $( '#design3-featured-plan-title-background-color-value' ).val()); 
		
	}	 
		   
	return false;
}

 /*************************Change *****************************************
 * When a user picks a color scheme under 'Use a pre-built color scheme" option,
 * change the colors selected in "Build my own color scheme" 
 * so that if the user decides to instead "Build my own color scheme" he sees the same colors as he previously had.
 ******************************************************************/
function tt_check_change_color_hover_option_for_design2_color() {
	var $ = jQuery;
	var  color_db = [
	  '#f77fa8', '#ef5f54', '#f15477', '#a176c3', '#6b89c9', '#49a9d3', '#59b7b7', '#9ca46b', '#92d590', '#b9c869', '#79714c', '#9d7d60', '#dca562', '#ffa14f', '#ffe177'
	   
	];
	var most_popular_color_db = ["#f7c2d2","#f1b3ae","#f3afbc","#d0bdde","#bac6df","#b6d7e5","#b4cfce","#dde1c7","#d6ecd5","#d8ddb6","#dfdbc3","#e4d1c2","#e7cfb5","#f3d0b0","#ffedad"]; 
	var color_hover_db=["#e55d7b","#d54b23","#d72534","#9150aa","#4c5ab2","#4676bd","#3f9ca5","#778d48","#69ba75","#8dae41","#616332","#83693a","#bf972e","#e09910","#f7bf09"];
	var color_border_db=["#cd5771","#b43d1b","#b61e2c","#7c4592","#424e9a","#446ba9","#3e8f95","#697c41","#61a86c","#799538","#52552d","#705a31","#a48227","#e57300","#d8b538"]; 
	
	var design2_unfeatured_plan_title_background_color_value= $( '#design2-unfeatured-plan-title-background-color' ).val();
	  
	for (index = 0; index < color_db.length; ++index) {
		if (design2_unfeatured_plan_title_background_color_value == color_db[index]){
				   
			$( '#tt-design2-unfeatured-button-hover-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', color_hover_db[index]);
			$( '#tt-design2-unfeatured-button-hover-color-value' ).val(color_hover_db[index]);
			
			$( '#design2-unfeatured-plan-title-background-color' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', color_hover_db[index]);
			$( '#design2-unfeatured-plan-title-background-color' ).val(color_hover_db[index]);
		   
			$( '#tt-design2-unfeatured-plan-border-background-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', color_border_db[index]);
			$( '#tt-design2-unfeatured-plan-border-background-color-value' ).val(color_border_db[index]);
			
			
		}
	}
	  
	var design2_featured_plan_title_background_color_value= $( '#design2-featured-plan-title-background-color-value' ).val();
	  
	for (index = 0; index < color_db.length; ++index) {
		if (design2_featured_plan_title_background_color_value == color_db[index]){
				   
			$( '#tt-design2-featured-button-hover-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', color_hover_db[index]);
			$( '#tt-design2-featured-button-hover-color-value' ).val(color_hover_db[index]);
			
			$( '#design2-featured-plan-title-background-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', color_hover_db[index]);
			$( '#design2-featured-plan-title-background-color-value' ).val(color_hover_db[index]);
			
			$( '#design2-featured-most-popular-label-background-color' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', most_popular_color_db[index]);
			$( '#design2-featured-most-popular-label-background-color' ).val(most_popular_color_db[index]);
			
			$( '#tt-design2-featured-ribbon-background-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', "rgba(0,0,0,0.3)" );
			$( '#tt-design2-featured-ribbon-background-color-value' ).val( "rgba(0,0,0,0.3)" );
			
			$( '#tt-design2-featured-plan-border-background-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', color_border_db[index]);
			$( '#tt-design2-featured-plan-border-background-color-value' ).val(color_border_db[index]);
		}
	}
}

 /*************************Change *****************************************
 * When a user picks a color scheme under 'Use a pre-built color scheme" option,
 * change the colors selected in "Build my own color scheme" 
 * so that if the user decides to instead "Build my own color scheme" he sees the same colors as he previously had.
 ******************************************************************/
function tt_check_change_color_option_for_comparison1_color() {
	var $ = jQuery;
	var  color_db = ['#34495e/#e74c3c', '#16a085/#ce3306', '#66317d/#0a8bb0', '#08a7bd/#a81818', '#138f6b/#46b527', '#417d83/#ee4c7e', '#27b18f/#ecce44', '#b97a68/#f37e5d', '#114d68/#9e0165', '#444588/#ee1367'];
	//Plan Title Font Color  special .ptp-plan-title h2
	var special_ptp_plan_title = ["#952e22","#7c1500","#006d96","#560000","#00970d","#9c2e64","#9ab02a","#a16043","#4c004b","#9c004d"]; 
	var ptp_plan_title=["#537597","#35ccbe","#855db6","#27d3f6","#32bba4","#60a9bc","#46ddc8","#d8a6a1","#3079a1","#6371c1"];   
	
	//Plan Title Hover Background Color .special.ptp-price-table:hover .ptp-price-holder
	var special_ptp_price_table_hover_ptp_price_holder = ["#c0392b","#a72000","#00789f","#810507","#1fa216","#c7396d","#c5bb33","#cc6b4c","#770054","#c70056"]; 
	var ptp_price_table_hover_ptp_price_holder=["#2c3e50","#0e9577","#5e266f","#009caf","#0b845d","#397275","#1fa681","#b16f5a","#09425a","#3c3a7a"]   ;
	
	//Plan Title Border Color .special.ptp-price-table .ptp-price-holder  ---- .ptp-price-holder
	var special_ptp_price_table_ptp_price_holder = ["#c0392b","#a72000","#00789f","#810507","#1fa216","#c7396d","#c5bb33","#cc6b4c","#770054","#c70056"]; 
	var ptp_price_table_ptp_price_holder=["#2c3e50","#0e9577","#5e266f","#009caf","#0b845d","#397275","#1fa681","#b16f5a","#09425a","#3c3a7a"];   
	
	//Pay Duration Background Color .special.ptp-price-table .ptp-pay-duration --.ptp-pay-duration
	var special_ptp_price_table_ptp_pay_duration = ["#c0392b","#ce3306","#0a8bb0","#810507","#1fa216","#c7396d","#c5bb33","#cc6b4c","#770054","#c70056"]; 
	var  ptp_price_table_ptp_pay_duration=["#2c3e50","#16a085","#66317d","#009caf","#0b845d","#397275","#1fa681","#b16f5a","#09425a","#3c3a7a"] ;  
	
	//Button Background Color - Button Hover Background Color  .ptp-data-holder .ptp-btn ---
	var ptp_data_holder_btn = ["#e74c3c","#ce3306","#0a8bb0","#a81818","#46b527","#ee4c7e","#ecce44","#f37e5d","#9e0165","#ee1367"]; 
	var  ptp_data_holder_btn_hover=["#c0392b","#a72000","#00789f","#810507","#1fa216","#c7396d","#c5bb33","#cc6b4c","#770054","#c70056"]   ;
	
	//Even Row Background Color -- Uneven Row Background Color
	var desc_table_ptp_data_holder_nth_child__2n_1 = ["#2c3e50","#0e9577","#5e266f","#009caf","#0b845d","#397275","#1fa681","#b16f5a","#09425a","#3c3a7a"]; 
	var  desc_table_ptp_data_holder_nth_child__2n = ["#34495e","#16a085","#66317d","#08a7bd","#138f6b","#417d83","#27b18f","#b97a68","#114d68","#444588"]   ;
	
	//Description Column Border Color
	var desc_table_border = ["#2c3e50","#0e9577","#5e266f","#009caf","#0b845d","#397275","#1fa681","#b16f5a","#09425a","#3c3a7a"]; 
   
	//Description Column hovering Color
	var desc_table_hovering = ["#18232e","#007a55","#4a0b4d","#00818d","#00693b","#255753","#0b8b5f","#9d5438","#002738","#281f58"]; 
   
	var comparison1_choose_a_theme_value= $( '#comparison1-choose-a-theme-value' ).val();
	  
	for (index = 0; index < color_db.length; ++index) {
		if (comparison1_choose_a_theme_value == color_db[index]){
			//Plan Title Font Color
			$( '#tt-comparison1-featured-plan-title-font-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', special_ptp_plan_title[index]);
			$( '#tt-comparison1-featured-plan-title-font-color-value' ).val(special_ptp_plan_title[index]);
		  
			$( '#tt-comparison1-unfeatured-plan-title-font-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', ptp_plan_title[index]);
			$( '#tt-comparison1-unfeatured-plan-title-font-color-value' ).val(ptp_plan_title[index]);
			
			//Plan Title Hover Background Color
			$( '#tt-comparison1-featured-plan-title-hover-background-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', special_ptp_price_table_hover_ptp_price_holder[index]);
			$( '#tt-comparison1-featured-plan-title-hover-background-color-value' ).val(special_ptp_price_table_hover_ptp_price_holder[index]);
		  
			$( '#tt-comparison1-unfeatured-plan-title-hover-background-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', ptp_price_table_hover_ptp_price_holder[index]);
			$( '#tt-comparison1-unfeatured-plan-title-hover-background-color-value' ).val(ptp_price_table_hover_ptp_price_holder[index]);
			
			//Plan Title Border Color
			$( '#tt-comparison1-featured-plan-border-background-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', special_ptp_price_table_ptp_price_holder[index]);
			$( '#tt-comparison1-featured-plan-border-background-color-value' ).val(special_ptp_price_table_ptp_price_holder[index]);
		  
			$( '#tt-comparison1-unfeatured-plan-border-background-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', ptp_price_table_ptp_price_holder[index]);
			$( '#tt-comparison1-unfeatured-plan-border-background-color-value' ).val(ptp_price_table_ptp_price_holder[index]);
			
			//Pay Duration Background Color
			$( '#tt-comparison1-featured-pay-duration-background-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', special_ptp_price_table_ptp_pay_duration[index]);
			$( '#tt-comparison1-featured-pay-duration-background-color-value' ).val(special_ptp_price_table_ptp_pay_duration[index]);
		  
			$( '#tt-comparison1-unfeatured-pay-duration-background-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', ptp_price_table_ptp_pay_duration[index]);
			$( '#tt-comparison1-unfeatured-pay-duration-background-color-value' ).val(ptp_price_table_ptp_pay_duration[index]);
			
			//Button Background Color
			$( '#tt-comparison1-button-background-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', ptp_data_holder_btn[index]);
			$( '#tt-comparison1-button-background-color-value' ).val(ptp_data_holder_btn[index]);
			//Button Hover Background Color
			$( '#tt-comparison1-button-hover-background-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', ptp_data_holder_btn_hover[index]);
			$( '#tt-comparison1-button-hover-background-color-value' ).val(ptp_data_holder_btn_hover[index]);
			
			//Even Row Background Color -- Uneven Row Background Color
			$( '#tt-comparison1-description-dark-background-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', desc_table_ptp_data_holder_nth_child__2n_1[index]);
			$( '#tt-comparison1-description-dark-background-color-value' ).val(desc_table_ptp_data_holder_nth_child__2n_1[index]);
		  
			$( '#tt-comparison1-description-light-background-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', desc_table_ptp_data_holder_nth_child__2n[index]);
			$( '#tt-comparison1-description-light-background-color-value' ).val(desc_table_ptp_data_holder_nth_child__2n[index]);
			
			//Description Column Border Color
			$( '#tt-comparison1-description-border-color-value' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', desc_table_border[index]);
			$( '#tt-comparison1-description-border-color-value' ).val(desc_table_border[index]);
			
			//Description Column hovering Color
			$( '#tt-comparison1-description-hover-background-color' ).parent().parent().find( '.wp-color-result' ).css( 'background-color', desc_table_hovering[index]);
			$( '#tt-comparison1-description-hover-background-color' ).val(desc_table_hovering[index]);
		  
		}
	}
}

function tt_updateGradient( val_Border, val_Top, val_Bottom, previewEl_Id ) {
	var $ = jQuery;
	$( previewEl_Id ).css( 'background','linear-gradient(to top,'+ val_Bottom+', '+ val_Top  +' )' );
		$( previewEl_Id ).css( 'border','1px solid '+ val_Border);
}

function consistent_match_column_height(el){
   var $ = jQuery;
	
   if($(el).is( ':checked' )) {
		$( '.tt-match-column-height-checkbox' ).each(function(){
			$(this).prop( 'checked', true);
		});
	} else {
		$( '.tt-match-column-height-checkbox' ).each(function(){
			  $(this).prop( 'checked', false);
		});
	}
	
}