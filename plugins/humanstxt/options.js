jQuery(document).ready(function($) {

	// enable auto-grow on humans.txt textarea
	var $humanstxtEditor = $('#humanstxt_content');
	$humanstxtEditor.humansAutoGrow();

	// open external links in new tab
	$('#wpbody-content a[rel*="external"]').attr('target', '_tab');

	// register slider for variable groups
	$('#humanstxt-vars ul h5').hoverIntent({
		out: function() {},
		over: function() {
			if ( $( window ).width() > 782 ) {
				$(this).siblings('ul').slideDown();
				$(this).parent().siblings().children('ul').slideUp();
			}
		}
	});

	// register custom tooltips for variable previews
	$('#humanstxt-vars ul ul li').humansTooltip();

	// make star rating clickable if the metabox is displayed
	var $humanstxtRateIt = $('#humanstxt-metabox .text-rateit a');
	if ($humanstxtRateIt.length) {
		$('#humanstxt-metabox .star-holder, #humanstxt-metabox .text-votes').css('cursor', 'pointer').attr('title', $humanstxtRateIt.attr('title')).click(function() {
			window.location.href = $humanstxtRateIt.attr('href');
		});
	}

	// register preview button
	$('#humanstxt-editor-wrap .button-preview').each(function() {
		$(this).data('ajax-url', $(this).attr('href')).click(function() {
			var params = '&content=' + encodeURIComponent($('#humanstxt_content').val()) + '&TB_iframe=1';
			tb_show($(this).attr('title'), $(this).data('ajax-url') + params, false);
			$(this).blur();
			return false;
		});
	});

	// enable tab key support on humans.txt textarea
	// taken from /wp-admin/js/common.dev.js
	$humanstxtEditor.keydown(function(e) {
		if (e.keyCode != 9)
			return true;

		var el = e.target, selStart = el.selectionStart, selEnd = el.selectionEnd, val = el.value, scroll, sel;

		try {
			this.lastKey = 9;
		} catch(err) {}

		if (document.selection) {
			el.focus();
			sel = document.selection.createRange();
			sel.text = '\t';
		} else if (selStart >= 0) {
			scroll = this.scrollTop;
			el.value = val.substring(0, selStart).concat('\t', val.substring(selEnd));
			el.selectionStart = el.selectionEnd = selStart + 1;
			this.scrollTop = scroll;
		}

		if (e.stopPropagation)
			e.stopPropagation();
		if (e.preventDefault)
			e.preventDefault();
	});

	$humanstxtEditor.blur(function(e) {
		if (this.lastKey && 9 == this.lastKey)
			this.focus();
	});

	// hide unnecessary revision compare radio buttons
	// jQuery adaptation of /wp-includes/js/wp-list-revisions.dev.js
	var $humanstxtRevisions = $('#humanstxt-revisions');
	var $humanstxtRevisionsInputs = $humanstxtRevisions.find('input');
	if ($humanstxtRevisions.length) {
		$humanstxtRevisions.click(function() {
			var i, checkCount = 0, side;
			for (i = 0; i < $humanstxtRevisionsInputs.length; i++) {
				checkCount += $humanstxtRevisionsInputs[i].checked ? 1 : 0;
				side = $humanstxtRevisionsInputs[i].getAttribute('name');
				if (!$humanstxtRevisionsInputs[i].checked && ('left' == side && 1 > checkCount || 'right' == side && 1 < checkCount && (!$humanstxtRevisionsInputs[i-1] || !$humanstxtRevisionsInputs[i-1].checked)) && !($humanstxtRevisionsInputs[i+1] && $humanstxtRevisionsInputs[i+1].checked && 'right' == $humanstxtRevisionsInputs[i+1].getAttribute('name')))
					$humanstxtRevisionsInputs[i].style.visibility = 'hidden';
				else if ('left' == side || 'right' == side)
					$humanstxtRevisionsInputs[i].style.visibility = 'visible';
			}
		}).click();
	}

});

(function($) {

	$.fn.humansTooltip = function() {

		var isRTL = $('body').hasClass('rtl');

		// add tooltip div
		$humanstxtTooltip = $('#humansTooltip');
		if ($humanstxtTooltip.length < 1) {
			$humanstxtTooltip = $('<div id="humansTooltip"></div>').appendTo('body');
		}

		return this.each(function() {

			var $element = jQuery(this);
			var elementTitle = this.title;

			this.title = ""; // prevent default browser tooltip

			$element.hover(
				function() {
					humanstxtTooltipInterval = setInterval(function() {
						clearInterval(humanstxtTooltipInterval);
						showTooltip();
					}, 200);
				},
				function() {
					clearInterval(humanstxtTooltipInterval);
					$humanstxtTooltip.fadeOut(150);
				}
			);

			var showTooltip = function() {
				$humanstxtTooltip.html(elementTitle); // set tooltip to original title attribute
				var elementOffset = $element.offset();
				var horizontalAdjustment = isRTL ? $element.width() - $humanstxtTooltip.width() - 5 : -15;
				$humanstxtTooltip.css({
					top: (elementOffset.top - $humanstxtTooltip.height() - 15) + 'px',
					left: (elementOffset.left + horizontalAdjustment) + 'px'
				}).fadeIn(200);
			}

		});

	}

	/**
	 * MODIFIED Autogrow Textarea Plugin Version v2.0
	 * http://www.technoreply.com/autogrow-textarea-plugin-version-2-0
	 *
	 * Copyright 2011, Jevin O. Sewaruth
	 *
	 * Date: March 13, 2011
	 */
	$.fn.humansAutoGrow = function() {
		return this.each(function() {

			var colsDefault = this.cols;
			var rowsDefault = this.rows;
			var rowsAdjustment = 0;

			if ($.browser.msie) {
				if ($.browser.version < 9) {
					rowsAdjustment = $('#humanstxt').hasClass('not-wp32') ? 9 : 3;
				} else {
					rowsAdjustment = 5;
				}
				rowsDefault += rowsAdjustment;
			}

			var grow = function() {
				growByRef(this);
			}

			var growByRef = function(obj) {
				var linesCount = 0 + rowsAdjustment;
				var lines = obj.value.split('\n');

				for (var i=lines.length-1; i>=0; --i) {
					linesCount += Math.floor((lines[i].length / colsDefault) + 1);
				}

				if (linesCount >= rowsDefault)
					obj.rows = linesCount + 1;
				else
					obj.rows = rowsDefault;
			}

			this.style.height = "auto";
			this.style.overflow = "hidden";
			this.onkeyup = grow;
			this.onkeypress = grow;
			this.onfocus = grow;
			this.onblur = grow;
			growByRef(this);
		});
	};

})(jQuery);