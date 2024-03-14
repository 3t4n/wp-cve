jQuery(document).ready(function($) {
	"use strict";
	
	/* Add controls to top of Widget Edit page */
	var headingPosition = '.wrap ' + ( ( $('h1').length ) ? 'h1' : 'h2' ) + ':first-child' ; // get first tag name inside .wrap div
	var buttonPosition = ( $( headingPosition + ' a' ).length ) ? headingPosition + ' a' : ( ( $( headingPosition + '+span' ).length ) ? headingPosition + '+span' : headingPosition + '+a' ) ;
	var buttonSelector = ( $( buttonPosition ).attr('class') == 'split-page-title-action' ) ? 'page-title-action' : $( buttonPosition ).attr('class') ; // get "Add New Widget" button selector class
	$( buttonPosition ).remove(); // remove existing "Add New Widget" button
	var adminLinks = '<a href="' + ContentAd.newWidgetCall + '" class="' + buttonSelector + ' thickbox">Add New Widget</a>'
		+ '<a href="' + ContentAd.reportCall + '" class="' + buttonSelector + ' thickbox">' + ContentAd.reportName + '</a>'
		+ '<a href="' + ContentAd.settingsCall + '" class="' + buttonSelector + ' thickbox">' + ContentAd.settingsLinkText + '</a>';

	$( headingPosition ).attr('style','display:inline-block;margin-bottom:0.2em;');
	$( headingPosition ).prepend( '<img id="logo" src="' + ContentAd.pluginsUrl + '/images/ca_logo.png" alt="Content.ad" />' );
	$( headingPosition ).after( adminLinks  );
	
	// Rewrite time Last Edited dates in local time
	function addZero(n) {
		return (n < 10) ? "0" + n : n ;
	}
	function lastEdited(post) {
		var last_edited = (post) ? $( post + ' .last_edited abbr') : $('.last_edited abbr') ;
		for(var i = 0; i < last_edited.length; i++) {
			var dateStr = $(last_edited[i]).attr('title');
			var date = dateStr.split(/\s|-|:/g);
			var le = new Date(date[0], date[1], date[2], date[3], date[4], date[5]);
			var le_am_pm = (le.getHours() > 11 && le.getHours() < 24) ? 'pm' : 'am' ;
			var le_hours = (le.getHours() > 12) ? le.getHours() -12 : le.getHours() ;
			var le_text = le.getFullYear() + '/' + addZero((le.getMonth())) + '/' + addZero(le.getDate());
			le = le_text + ' ' + addZero( le_hours ) + ':' + addZero(le.getMinutes()) + ':' + addZero(le.getSeconds()) + ' ' + le_am_pm;
			$(last_edited[i]).attr('title', le).text(le_text);
		}
	}
	lastEdited();

  // Listener to update Last Edited date format when user changes the widget placement
  $('button.save').click( function() {
    var thisPost = '#' + $(this).closest('tr').attr('id').replace('edit', 'post');
    var oldValue = $( thisPost + ' .last_edited abbr').attr('title');
    var myVar = setInterval( function(){
      myTimer(thisPost, oldValue);
    }, 50);
    function myTimer(thisPost, oldValue) {
      if( oldValue !=  $( thisPost + ' .last_edited abbr').attr('title')) {
        lastEdited(thisPost);
        clearInterval(myVar);
      }
    }
  });
	
	// Loading Spinner
	var cad_loader = '<div class="loading_bg"><div class="loader"><p><img src="https://app.content.ad/Images/ajax-loader.gif" alt="" /></p><p>Loading<br />please wait</p></div></div>';
	function spinner(action, position) {
		if(position === 'iframe') {
			$('#TB_iframeContent').remove();
			$('#TB_window').append(cad_loader);
			$('.loading_bg').addClass('iframe');
		} else {
			$('body').append(cad_loader);
		}
		if(action === 'show') {
			$('.loading_bg').addClass('show');
			$('.loader').focus();
		} else if(action === 'hide') {
			$('.loading_bg').removeClass('show').removeClass('iframe');
		}
	}

	// Reload the page when thickbox is closed
	var original_tb_remove = tb_remove;
	tb_remove = function () {
		var iframe_src = $('#TB_iframeContent').attr('src');
		if(iframe_src && iframe_src.indexOf('Publisher/Widgets') > -1 && iframe_src.indexOf('Publisher/Widgets/WordpressInstallation') == -1) {
			location.reload(true);
			spinner('show', 'iframe');
			return false;
		} else {
			original_tb_remove();
		}
	};
		
	// Delete widget confirmation dialog box
	$('.row-actions .trash a.submitdelete').on('click', function(e){
        e.preventDefault();
        tb_show("Confirm Delete","#TB_inline?inlineId=deleteConfirmation_"+$(this).attr('data-postid'),null);
		$('#TB_window').delay(1000).addClass('delete-confirmation');
    });
	
    // AJAX call to delete ad widget
	$('.cad-delete').click(function(){
		var tableRow = $("tr#post-" + $(this).attr('data-postid'));
		tableRow.hide();
		$.post(
			ajaxurl,
			{
				action	: ContentAd.action,
				nonce  	: ContentAd.nonce,
				task	: 'delete',
				post_id : $(this).attr('data-postid')
			},
			function( response ){
				if( 'success' === response.status ){
					tableRow.remove();
				}
			},
			'json'
		);
		tb_remove();
    });
	
	// Cancel delete widget
    $('.cad-cancel').click(function(){
        tb_remove();
	});

	// AJAX call to activate/pause ad widget
	$('.toggle-status').on('click', function(e){
		e.preventDefault();
		var isActive = $(this).hasClass('active');
		var task = (isActive)?'pause':'activate';
		var tableRow = $(this).closest('tr');
		var activeButton = $( 'td.column-widget_active span', tableRow );
		var link = $('.row-actions a.toggle-status', tableRow);
		var post_id = $(this).data('postid');
		$.post(
			ajaxurl,
			{
				action	: ContentAd.action,
				nonce  	: ContentAd.nonce,
				task	: task,
				post_id : post_id
			},
			function( response ){
				var linkText = (isActive)?ContentAd.activateLinkTranslation:ContentAd.pauseLinkTranslation;
				var buttonText = (isActive)?ContentAd.pauseButtonTranslation:ContentAd.activateButtonTranslation;
				link.text( linkText ).attr( 'title', linkText).closest('span').attr( 'class', (isActive?'activate':'pause') );
				activeButton.text(buttonText);
				if(isActive) {
					// Pause
					link.removeClass('active contentad-active-state').addClass('paused contentad-inactive-state');
					activeButton.removeClass('active contentad-active-state').addClass('paused contentad-inactive-state');
				} else {
					// Activate
					link.removeClass('paused contentad-inactive-state').addClass('active contentad-active-state');
					activeButton.removeClass('paused contentad-inactive-state').addClass('active contentad-active-state');
				}
			},
			'json'
		);
	});
	
    $( 'tr.inline-edit-row' ).removeClass( 'inline-edit-row-page' ).addClass( 'inline-edit-row-post' );
    $( 'tr.inline-edit-row' ).removeClass( 'quick-edit-row-page' ).addClass( 'quick-edit-row-post' );

    $( 'tr.inline-edit-row fieldset.inline-edit-col-left:first' ).remove();
    $( 'tr.inline-edit-row fieldset.inline-edit-col-right:first' ).remove();
	
	if ($( '.jquery_version_good' ).text() == false) {
		$('a.editinline').on('click', function() {
			placementClick($(this));
		} );
	} else {
		$('.wp-list-table tbody').on('click', 'a.editinline', function() {
			placementClick($(this));
		} );
	}
	
	// Get wid from WP Widget Name URL
	function getURLParameter(url, name) {
		return (RegExp(name + '=' + '(.+?)(&|$)').exec(url)||[,null])[1];
	}
	
	function placementClick(thisObj) {
		var wp_wid = inlineEditPost.getId(thisObj);
		var localizedPostDiv = '#inline_' + wp_wid;
		var quick_edit_id = '#edit-' + wp_wid;
		var post_edit_id = '#post-' + wp_wid;
		var post_title = $( localizedPostDiv + ' .post_title' ).text();
		var exit_pop = $( localizedPostDiv + ' .exit_pop' ).text();
		exit_pop = (exit_pop) ? true : false;
		var mobile_exit_pop = $( localizedPostDiv + ' .mobile_exit_pop' ).text();
		mobile_exit_pop = (mobile_exit_pop) ? true : false;
		var cad_wid = $( localizedPostDiv + ' .widget_id' ).text();
		var placement = $( localizedPostDiv + ' .placement').text();
		var displayHome = $( localizedPostDiv + ' .ca_display_home').text();
		var displayCatTag = $( localizedPostDiv + ' .ca_display_cat_tag').text();
		var excCategories = $( localizedPostDiv + ' .excluded_categories').text();
		var excTags = $( localizedPostDiv + ' .excluded_tags').text();

		// Assign widget title
		$( 'tr.inline-edit-row h4.contentad-widget-title' ).html( post_title );
		
		// Assign shortcode & template tag values
		$( '.section_in_shortcode input' ).val('[contentad widget="' + cad_wid + '"]');
		$( '.section_in_function input' ).val("<?php do_action('contentad', array('tag'=>'" + cad_wid + "')); ?>");

		// Assign Placement
		$( ' #' + placement ).prop('checked', true);
		$( '.option' ).removeClass('selected');
		$( '.option.' + placement ).addClass('selected');
		$( 'input#' + placement ).prop({ 'checked': true, 'disabled': false });
		
		var home_cat = '<div class="home-cat-options"><label for="_ca_display_home"><input id="_ca_display_home" class="_ca_display_home" type="checkbox" value="1" name="_ca_display_home" />&nbsp;Display on home page</label><label for="_ca_display_cat_tag"><input id="_ca_display_cat_tag" class="_ca_display_cat_tag" type="checkbox" value="1" name="_ca_display_cat_tag" />&nbsp;Display on category and tag pages</label></div>';
		$( '.option .home-cat-options' ).remove();
		if ( 'popup_or_mobile_slidup' === placement || 'in_exit_pop' === placement  || 'in_mobile_exit_pop' === placement  ) {
			$( '.option' ).hide();
			$( '.option.' + placement + ' .ca-indent-section').append( home_cat );
			$( '.option.' + placement ).show();
		} else {
			$( '.option').show();
			$( '.option.in_widget .ca-indent-section' ).append(home_cat);
			$( '.option.popup_or_mobile_slidup, .option.in_exit_pop, .option.in_mobile_exit_pop').hide();
		}
		
		if ( 'in_shortcode' === placement || 'in_function' === placement ) {
			$( '.cat-checklist input[type=checkbox]' ).prop('disabled', true);
			$( 'textarea.contentad_exc_tags' ).prop('disabled', true).closest('tr').addClass( 'no_excludes');
		} else {
			$( '.cat-checklist input[type=checkbox]' ).prop('disabled', false);
			$( 'textarea.contentad_exc_tags' ).prop('disabled', false).closest('tr').removeClass( 'no_excludes');			
		}

		$('input[name="placement"]').bind( 'click', function() {
			$( '.option' ).removeClass('selected');
			$( '.option.' + $(this).attr('id') ).addClass('selected');
			if( $('#in_shortcode').is(':checked') || $('#in_function').is(':checked') ) {
				$( '.cat-checklist input[type=checkbox]' ).prop('disabled', true);
				$( 'textarea.contentad_exc_tags' ).prop('disabled', true);
				$( quick_edit_id ).addClass( 'no_excludes');
			} else {
				$( '.cat-checklist input[type=checkbox]' ).prop('disabled', false);
				$( 'textarea.contentad_exc_tags' ).prop('disabled', false);
				$( quick_edit_id ).removeClass( 'no_excludes');
			}
		} );

		// Assign ca_display_home
		if( '1' == displayHome ) {
			$( '._ca_display_home').attr( 'checked', 'checked' );
		} else {
			$( '._ca_display_home').removeAttr( 'checked' );
		}

		// Assign ca_display_cat_tag
		if( '1' == displayCatTag ) {
			$( '._ca_display_cat_tag').attr( 'checked', 'checked' );
		} else {
			$( '._ca_display_cat_tag').removeAttr( 'checked' );
		}

		// Assign categories
		$.each( $('#inline-edit .inline-edit-categories input[type="checkbox"]'), function() {
			if( excCategories && $.inArray( $(this).val(), excCategories.split(',') ) != -1 ) {
				$( '#in-category-' + $(this).val() ).attr('checked', 'checked');
			} else {
				$( '#in-category-' + $(this).val() ).removeAttr('checked');
			}
		} );

		// Assign tags
		if( excTags ) {
			$('.contentad_exc_tags').html( excTags );
		} else {
			$('.contentad_exc_tags').html( '' );
		}
		
		/* add Tag hints to the Exlude tags textarea */
		if($(post_edit_id).attr('data-suggest') !== 'true') {
			$('.contentad_exc_tags').focus(function(){
				$( quick_edit_id + ' .contentad_exc_tags').suggest("/wp-admin/admin-ajax.php?action=ajax-tag-search&tax=post_tag", {multiple:true, multipleSep: ","});
			});
			$(post_edit_id).attr('data-suggest','true');
		}
	}

});
