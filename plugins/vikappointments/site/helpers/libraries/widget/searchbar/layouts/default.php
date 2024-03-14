<?php
/** 
 * @package     VikAppointments
 * @subpackage  core
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

?>

<div class="vap-config-toolbar">
	<div class="btn-toolbar" style="height: 32px;">
		<div class="btn-group pull-right input-append">
			<input type="text" id="vap-search-param" value="" placeholder="Settings Research" size="24"/>

			<button type="button" class="btn" onClick="hideSearchBar();">
				<i class="icon-remove"></i>
			</button>
		</div>
	</div>
</div>

<div class="vap-config-searchbar" style="display: none">
	<div class="vap-config-searchbar-results">
		<span class="vap-config-searchbar-stat badge"></span>
		<span class="vap-config-searchbar-control">
			<a href="javascript: void(0);" class="vap-left-arrow" onClick="goToPrevMatch();"></a>
		</span>
		<span class="vap-config-searchbar-control">
			<a href="javascript: void(0);" class="vap-right-arrow" onClick="goToNextMatch();"></a>
		</span>
	</div>
	<div class="vap-config-searchbar-gotop">
		<a href="javascript: void(0);" onClick="animateToPageTop();"><?php echo JText::translate('VAPGOTOP'); ?></a>
	</div>
</div>

<script>
	
	var searchBar = new SearchBar(false);

	jQuery(document).ready(function(){
		jQuery('#vap-search-param').on('keyup', function(event){
			
			var value = jQuery('#vap-search-param').val().toLowerCase();
			searchBar.setMatches( getParamsFromSearch(value) );
			if( searchBar.isNull() ) {
				hideSearchBar();
			} else {
				displaySearchBar();
				if( searchBar.size() > 0 && event.keyCode == 13 ) {
					goToCurrentMatch();
					jQuery('#vap-search-param').blur();
				}
			}
		});

		jQuery(window).on('scroll', debounce(
			windowScrollControl, 250
		));
		
		jQuery(document).bind('keydown', function (event) {
			if( searchBar.isNull() || jQuery('#vap-search-param').is(':focus') ) {
				return;
			}
			
			switch(event.keyCode) {
				case 37: goToPrevMatch(); break; // left arrow > prev match
				case 39: goToNextMatch(); break; // right arrow > next match
				case 13: goToNextMatch(); break; // enter > next match
				case 27: hideSearchBar(); break; // esc > hide search bar
			}
		});
	});
	
	function getParamsFromSearch(value) {
		if( value.length == 0 ) {
			return false;
		}
		
		var matches = new Array();
		
		jQuery('.adminparamcol').each(function(){
			if( jQuery(this).text().toLowerCase().indexOf(value) != -1 ) {                
				var style = jQuery(this).parent().attr('style');
				if( typeof style === 'undefined' || style.length === 0 ) {
					matches.push(jQuery(this));
				}
			}
		});
		
		return matches;
	}
	
	function displaySearchBar() {
		if( searchBar.size() == 0 ) {
			jQuery('.vap-config-searchbar-stat').text('<?php echo addslashes(JText::translate('VAPNOMATCHES')); ?>');
			jQuery('.vap-config-searchbar-control').hide();
		} else {
			jQuery('.vap-config-searchbar-stat').text('1/'+searchBar.size());
			jQuery('.vap-config-searchbar-control').show();
		}
		
		windowScrollControl(false);
		
		jQuery('.vap-config-searchbar').show();
	}
	
	function hideSearchBar() {
		jQuery('#vap-search-param').val('');
		jQuery('.vap-config-searchbar').hide();
		jQuery('.adminparamcol b').removeClass('badge vap-orange-badge');
		searchBar.clear();
	}
	
	function windowScrollControl(effect) {
		if( jQuery(window).scrollTop() <= 0 ) {
			if( effect ) {
				jQuery('.vap-config-searchbar-gotop').fadeOut();
			} else {
				jQuery('.vap-config-searchbar-gotop').hide();
			}
		} else {
			if( effect ) {
				jQuery('.vap-config-searchbar-gotop').fadeIn();
			} else {
				jQuery('.vap-config-searchbar-gotop').show();
			}
		}
	}
	
	function goToCurrentMatch() {
		if( searchBar.size() == 0 ) {
			return;
		}
		
		var elem = searchBar.getElement();
		highlightMatch(elem);
		checkMatchParent(elem);
		animateToScrollTop( elem.offset().top-200 );
	}
	
	function goToPrevMatch() {
		if( searchBar.size() == 0 ) {
			return;
		}
		
		var elem = searchBar.previous();
		highlightMatch(elem);
		checkMatchParent(elem);
		animateToScrollTop( elem.offset().top-200 );
		jQuery('.vap-config-searchbar-stat').text((searchBar.getCurrentIndex()+1)+'/'+searchBar.size());
	}
	
	function goToNextMatch() {
		if( searchBar.size() == 0 ) {
			return;
		}
		
		var elem = searchBar.next();
		highlightMatch(elem);
		checkMatchParent(elem);
		animateToScrollTop( elem.offset().top-200 );
		jQuery('.vap-config-searchbar-stat').text((searchBar.getCurrentIndex()+1)+'/'+searchBar.size());
	}
	
	function animateToScrollTop(px) {
		jQuery("html, body").stop(true, true).animate({ scrollTop: px });
	}
	
	function animateToPageTop() {
		jQuery("html, body").stop(true, true).animate({ scrollTop: 0 }).promise().done(function(){
			jQuery('#vap-search-param').focus();
		});
	}
	
	function highlightMatch(match) {
		jQuery('.adminparamcol b').removeClass('badge vap-orange-badge');
		match.children().first().addClass('badge vap-orange-badge');
	}
	
	function checkMatchParent(match) {
		var parent = match.parent();
		while( parent.length > 0 && !parent.hasClass('vaptabview') ) {
			parent = parent.parent();
		}
		
		if( parent.length > 0 && !parent.is(':visible') ) {
			changeTabView(parent.attr('id').split('vaptabview')[1]);
		}
		
	}

</script>
