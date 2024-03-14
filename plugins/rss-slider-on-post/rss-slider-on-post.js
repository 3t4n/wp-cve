/**
 *     Rss slider on post
 *     Copyright (C) 2011 - 2023 www.gopiplus.com
 * 
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 * 
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 * 
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */	

function scrollrssslider() {
	objrssslider.scrollTop = objrssslider.scrollTop + 1;
	rssslider_scrollPos++;
	if ((rssslider_scrollPos%rssslider_heightOfElm) == 0) {
		rssslider_numScrolls--;
		if (rssslider_numScrolls == 0) {
			objrssslider.scrollTop = '0';
			rsssliderContent();
		} else {
			if (rssslider_scrollOn == 'true') {
				rsssliderContent();
			}
		}
	} else {
		setTimeout("scrollrssslider();", 10);
	}
}

var IRNum = 0;
/*
Creates amount to show + 1 for the scrolling ability to work
scrollTop is set to top position after each creation
Otherwise the scrolling cannot happen
*/
function rsssliderContent() {
	var tmp_IR = '';

	w_IR = IRNum - parseInt(rssslider_numberOfElm);
	if (w_IR < 0) {
		w_IR = 0;
	} else {
		w_IR = w_IR%rssslider.length;
	}
	
	// Show amount of IR
	var elementsTmp_IR = parseInt(rssslider_numberOfElm) + 1;
	for (i_IR = 0; i_IR < elementsTmp_IR; i_IR++) {
		
		tmp_IR += rssslider[w_IR%rssslider.length];
		w_IR++;
	}

	objrssslider.innerHTML 	= tmp_IR;	
	IRNum 				= w_IR;
	rssslider_numScrolls 	= rssslider.length;
	objrssslider.scrollTop 	= '0';
	// start scrolling
	setTimeout("scrollrssslider();", 2000);
}

