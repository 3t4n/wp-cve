"use strict";
var g_wtbpCurrTour = null
,	g_wtbpTourOpenedWithTab = false
,	g_wtbpAdminTourDissmissed = false;
jQuery(document).ready(function(){
	setTimeout(function(){
		if(typeof(wtbpAdminTourData) !== 'undefined' && wtbpAdminTourData.tour) {
			jQuery('body').append( wtbpAdminTourData.html );
			wtbpAdminTourData._$ = jQuery('#woobewoo-admin-tour');
			for(var tourId in wtbpAdminTourData.tour) {
				if(wtbpAdminTourData.tour[ tourId ].points) {
					for(var pointId in wtbpAdminTourData.tour[ tourId ].points) {
						_wtbpOpenPointer(tourId, pointId);
						break;	// Open only first one
					}
				}
			}
			for(var tourId in wtbpAdminTourData.tour) {
				if(wtbpAdminTourData.tour[ tourId ].points) {
					for(var pointId in wtbpAdminTourData.tour[ tourId ].points) {
						if(wtbpAdminTourData.tour[ tourId ].points[ pointId ].sub_tab) {
							var subTab = wtbpAdminTourData.tour[ tourId ].points[ pointId ].sub_tab;
							jQuery('a[href="'+ subTab+ '"]')
								.data('tourId', tourId)
								.data('pointId', pointId);
							var tabChangeEvt = str_replace(subTab, '#', '')+ '_tabSwitch';
							jQuery(document).bind(tabChangeEvt, function(event, selector) {
								if(!g_wtbpTourOpenedWithTab && !g_wtbpAdminTourDissmissed) {
									var $clickTab = jQuery('a[href="'+ selector+ '"]');
									_wtbpOpenPointer($clickTab.data('tourId'), $clickTab.data('pointId'));
								}
							});
						}
					}
				}
			}
		}
	}, 500);
});

function _wtbpOpenPointerAndPopupTab(tourId, pointId, tab) {
	g_wtbpTourOpenedWithTab = true;
	jQuery('#wtbpPopupEditTabs').wpTabs('activate', tab);
	_wtbpOpenPointer(tourId, pointId);
	g_wtbpTourOpenedWithTab = false;
}
function _wtbpOpenPointer(tourId, pointId) {
	var pointer = wtbpAdminTourData.tour[ tourId ].points[ pointId ];
	var $content = wtbpAdminTourData._$.find('#woobewoo-'+ tourId+ '-'+ pointId);
	if(!jQuery(pointer.target) || !jQuery(pointer.target).size())
		return;
	if(g_wtbpCurrTour) {
		_wtbpTourSendNext(g_wtbpCurrTour._tourId, g_wtbpCurrTour._pointId);
		g_wtbpCurrTour.element.pointer('close');
		g_wtbpCurrTour = null;
	}
	if(pointer.sub_tab && jQuery('#wtbpPopupEditTabs').wpTabs('getActiveTab') != pointer.sub_tab) {
		return;
	}
	var options = jQuery.extend( pointer.options, {
		content: $content.find('.woobewoo-tour-content').html()
	,	pointerClass: 'wp-pointer woobewoo-pointer'
	,	close: function() {
		}
	,	buttons: function(event, t) {
			g_wtbpCurrTour = t;
			g_wtbpCurrTour._tourId = tourId;
			g_wtbpCurrTour._pointId = pointId;
			var $btnsShell = $content.find('.woobewoo-tour-btns')
			,	$closeBtn = $btnsShell.find('.close')
			,	$finishBtn = $btnsShell.find('.woobewoo-tour-finish-btn');

			if($finishBtn && $finishBtn.size()) {
				$finishBtn.click(function(e){
					e.preventDefault();
					jQuery.sendFormWtbp({
						msgElID: 'noMessages'
					,	data: {mod: 'promo', action: 'addTourFinish', tourId: tourId, pointId: pointId}
					});
					g_wtbpCurrTour.element.pointer('close');
				});
			}
			if($closeBtn && $closeBtn.size()) {
				$closeBtn.bind( 'click.pointer', function(e) {
					e.preventDefault();
					jQuery.sendFormWtbp({
						msgElID: 'noMessages'
					,	data: {mod: 'promo', action: 'closeTour', tourId: tourId, pointId: pointId}
					});
					t.element.pointer('close');
					g_wtbpAdminTourDissmissed = true;
				});
			}
			return $btnsShell;
		}
	});
	jQuery(pointer.target).pointer( options ).pointer('open');
	var minTop = 10
	,	pointerTop = parseInt(g_wtbpCurrTour.pointer.css('top'));
	if(!isNaN(pointerTop) && pointerTop < minTop) {
		g_wtbpCurrTour.pointer.css('top', minTop+ 'px');
	}
}
function _wtbpTourSendNext(tourId, pointId) {
	jQuery.sendFormWtbp({
		msgElID: 'noMessages'
	,	data: {mod: 'promo', action: 'addTourStep', tourId: tourId, pointId: pointId}
	});
}