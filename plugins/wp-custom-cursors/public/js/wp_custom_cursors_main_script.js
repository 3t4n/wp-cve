/*
 * WP Custom Cursors | WordPress Cursor Plugin
 * Author: Hamid Reza Sepehr
 *
 * "Open your hands if you want to be held." -Rumi
 *
 */    

(function(){ 
	window.addEventListener('DOMContentLoaded', function(event) {
		window.addEventListener('load', function(event) {
			let stopFlag = false, el1VelocityXYFlag = false, el2VelocityXYFlag = false, el1VelocityResizeFlag = false, el2VelocityResizeFlag = false, hoverElementWidth = 0, hoverElementHeight = 0, elementTop = 0, elementLeft = 0, calcTop = 0, calcLeft = 0, paddingTop = 0, paddingLeft = 0, hx = 0, hy = 0;

			let mouseData = {
				x:0, 
				y:0, 
				hx:0, 
				hy:0,
				prevX: 0,
				prevY: 0,
				prevTimeStamp: 0,
				el1ScaleX: 1,
				el1ScaleY: 1,
				el2ScaleX: 1,
				el2ScaleY: 1
			};
			let distanceX, timeDiff, velX = 1, distanceY, velY = 1, el1VelX = 1, el1VelY = 1, el2VelX = 1, el2VelY = 1;
			const getMousePosition = function(e) {
	            hx = e.clientX;
	            hy = e.clientY;
	            if (stopFlag) {
	            	hx = elementLeft;
	            	hy = elementTop;
	            }
				if (el1VelocityXYFlag || el2VelocityXYFlag) {
					distanceX = Math.sqrt( Math.pow( e.clientX - mouseData.prevX, 2 )  );
					timeDiff = e.timeStamp - mouseData.prevTimeStamp;
					velX = distanceX / timeDiff;
					velX = 1 / ( 1 + velX );

					distanceY = Math.sqrt( Math.pow( e.clientY - mouseData.prevY, 2 ) );
					velY = distanceY / timeDiff;
					velY = 1 / ( 1 + velY );
				}

				if (el1VelocityResizeFlag || el2VelocityResizeFlag) {
					distanceX = Math.sqrt( Math.pow( e.clientX - mouseData.prevX, 2 ) + Math.pow( e.clientY - mouseData.prevY, 2 )  );
					timeDiff = e.timeStamp - mouseData.prevTimeStamp;
					velX = distanceX / timeDiff;
					velX = 1 / ( 1 + velX );
				}

				if (el1VelocityXYFlag) {
					el1VelX = velX;
					el1VelY = velY;
				}

				if (el2VelocityXYFlag) {
					el2VelX = velX;
					el2VelY = velY;
				}

				if (el1VelocityResizeFlag) {
					el1VelX = velX;
					el1VelY = velX;
				}

				if (el2VelocityResizeFlag) {
					el2VelX = velX;
					el2VelY = velX;
				}

		        return { 
					x : e.clientX, 
					y : e.clientY, 
					hx:hx, 
					hy:hy,
					prevX: e.clientX,
					prevY: e.clientY,
					prevTimeStamp: e.timeStamp,
					el1ScaleX: el1VelY,
					el1ScaleY: el1VelX,
					el2ScaleX: el2VelY,
					el2ScaleY: el2VelX
				}
		    }

			

		    
			let body = document.querySelector('body'),
				frames = document.querySelectorAll('iframe');

			body.addEventListener('pointermove', function(ev) {mouseData = getMousePosition(ev)});
 
			// IFrame Stop Cursor
			if (frames.length > 0) {
				[...frames].forEach(function(frame){
					frame.addEventListener('mouseenter', function(){
						body.classList.add('iframe-hover');
					});
					frame.addEventListener('mouseleave', function(){
						body.classList.remove('iframe-hover');
					});
				});
			}

			// For Each Cursor
			[...added_cursors].forEach(function(cursor) {
				let elements = null;

				// Activate Cursor on Body
				if (cursor.activate_on == 0) {
					elements = document.querySelectorAll("body");
				} 

				// Activate Cursor on Elements
				else {
					switch (cursor.selector_type) {
						case 'tag':
							elements = document.querySelectorAll(cursor.selector_data);
					    break;

					    case 'class':
						    elements = document.querySelectorAll("." + cursor.selector_data);
					    break;

					    case 'id':
						    elements = document.querySelectorAll("#" + cursor.selector_data);
					    break;

					    case 'attribute':
						    elements = document.querySelectorAll("[" + cursor.selector_data + "]");
					    break;
					}
				}

				if (elements != null && elements.length > 0) {
					[...elements].forEach(function(element){
						createCursor(element, cursor);
					});
				}

			});

			function createCursor(element, cursor) {
				let cursorWrapper = document.createElement('div'),
					cursorEl1 = document.createElement('div'), 
					cursorEl2 = document.createElement('div'),
					mouseEntered = false;

				cursorEl1.classList.add('cursor-el1');
				cursorEl2.classList.add('cursor-el2');

				if (cursor.cursor_type == 'shape') {
					cursorWrapper.classList.add(`cursor-${cursor.cursor_shape}`);
				}

				// Mobile & Tablet Hide
				if (cursor.hide_tablet == "on") {
					cursorWrapper.classList.add('hide-tablet');						
				}

				if (cursor.hide_mobile == "on") {
					cursorWrapper.classList.add('hide-mobile');						
				}

				cursorWrapper.classList.add('wpcc-cursor');
				cursorWrapper.classList.add('no-transition');
				
				cursorWrapper.appendChild(cursorEl1);
				cursorWrapper.appendChild(cursorEl2);

				body.appendChild(cursorWrapper);

				let createdCursorId = cursor.cursor_shape.substring(8), predefinedCursor = true;
				[...created_cursors].forEach(function(createdCursor){
					if (createdCursor.cursor_id == createdCursorId) {

						predefinedCursor = false;

						let className = createdCursor.cursor_type;
						if (createdCursor.cursor_type == 'text') {
							className = createdCursor.cursor_options.normal_text_type;
						}
						cursorWrapper.setAttribute('data-cursor-type', className);

						switch(createdCursor.cursor_type){
						  case 'shape':
							cursorWrapper.style.setProperty('--fe-width', createdCursor.cursor_options.fe_width + "px");
							cursorWrapper.style.setProperty('--fe-height', createdCursor.cursor_options.fe_height + "px");
							cursorWrapper.style.setProperty('--fe-color', createdCursor.cursor_options.fe_color);
							cursorWrapper.style.setProperty('--fe-border-width', createdCursor.cursor_options.fe_border_width + "px");
							cursorWrapper.style.setProperty('--fe-border-radius', createdCursor.cursor_options.fe_radius + "px");
							cursorWrapper.style.setProperty('--fe-border-color', createdCursor.cursor_options.fe_border_color);
							cursorWrapper.style.setProperty('--fe-transition-duration', createdCursor.cursor_options.fe_duration + "ms");
							cursorWrapper.style.setProperty('--fe-transition-timing', createdCursor.cursor_options.fe_timing);
							cursorWrapper.style.setProperty('--fe-blending-mode', createdCursor.cursor_options.fe_blending);
							cursorWrapper.style.setProperty('--fe-zindex', createdCursor.cursor_options.fe_zindex);
							cursorWrapper.style.setProperty('--fe-backdrop', `${createdCursor.cursor_options.fe_backdrop}(${createdCursor.cursor_options.fe_backdrop_value})`);

							switch(createdCursor.cursor_options.fe_velocity) {
								case 'xy':
									el1VelocityXYFlag = true;
								break;
								case 'resize':
									el1VelocityResizeFlag = true;
								break;
							}

							cursorWrapper.style.setProperty('--se-width', createdCursor.cursor_options.se_width + "px");
							cursorWrapper.style.setProperty('--se-height', createdCursor.cursor_options.se_height + "px");
							cursorWrapper.style.setProperty('--se-color', createdCursor.cursor_options.se_color);
							cursorWrapper.style.setProperty('--se-border-width', createdCursor.cursor_options.se_border_width + "px");
							cursorWrapper.style.setProperty('--se-border-radius', createdCursor.cursor_options.se_radius + "px");
							cursorWrapper.style.setProperty('--se-border-color', createdCursor.cursor_options.se_border_color);
							cursorWrapper.style.setProperty('--se-transition-duration', createdCursor.cursor_options.se_duration + "ms");
							cursorWrapper.style.setProperty('--se-transition-timing', createdCursor.cursor_options.se_timing);
							cursorWrapper.style.setProperty('--se-blending-mode', createdCursor.cursor_options.se_blending);
							cursorWrapper.style.setProperty('--se-zindex', createdCursor.cursor_options.se_zindex);
							cursorWrapper.style.setProperty('--se-backdrop', `${createdCursor.cursor_options.se_backdrop}(${createdCursor.cursor_options.se_backdrop_value})`);

							switch(createdCursor.cursor_options.se_velocity) {
								case 'xy':
									el2VelocityXYFlag = true;
								break;
								case 'resize':
									el2VelocityResizeFlag = true;
								break;
							}
						  break;
						  case 'image':
						    let imageCursor = document.createElement('img');
							imageCursor.setAttribute('src', createdCursor.cursor_options.image_url);
							cursorEl1.appendChild(imageCursor);
							cursorWrapper.classList.add('cursor-image');

							let clickPointOption = createdCursor.cursor_options.click_point.split(','),
							clickPointX = ( Number(clickPointOption[0]) * Number(createdCursor.cursor_options.width) ) / 100,
							clickPointY = ( Number(clickPointOption[1]) * Number(createdCursor.cursor_options.height) ) / 100; 

							cursorWrapper.style.setProperty('--width', createdCursor.cursor_options.width + "px");
							cursorWrapper.style.setProperty('--color', createdCursor.cursor_options.color);
							cursorWrapper.style.setProperty('--radius', createdCursor.cursor_options.radius + "px");
							if (createdCursor.cursor_options.background != 'off') {
								cursorWrapper.style.setProperty('--padding', createdCursor.cursor_options.padding + "px");
								paddingTop = createdCursor.cursor_options.padding;
								paddingLeft = createdCursor.cursor_options.padding;
							}

							calcTop = ( Number(paddingTop) + clickPointY ) * -1;
							calcLeft = ( Number(paddingLeft) + clickPointX ) * -1;
							cursorWrapper.style.setProperty('--blending', createdCursor.cursor_options.blending);
						  break;
						  case 'text':
						  	if (createdCursor.cursor_options.normal_text_type == 'horizontal') {
						  		let hrDom = document.createElement('div');
						    	
						    	hrDom.innerHTML = createdCursor.cursor_options.hr_text;
						    	cursorEl1.appendChild(hrDom);
						    	cursorWrapper.classList.add('cursor-horizontal');
						    	cursorEl1.firstChild.style.setProperty('--hr-width', createdCursor.cursor_options.hr_width + "px");
						    	cursorEl1.firstChild.style.setProperty('--hr-transform', createdCursor.cursor_options.hr_transform);
						    	cursorEl1.firstChild.style.setProperty('--hr-size', createdCursor.cursor_options.hr_size + "px");
						    	cursorEl1.firstChild.style.setProperty('--hr-weight', createdCursor.cursor_options.hr_weight);
						    	cursorEl1.firstChild.style.setProperty('--bg-color', createdCursor.cursor_options.hr_bgcolor);
						    	cursorEl1.firstChild.style.setProperty('--hr-size', createdCursor.cursor_options.hr_size + "px");
						    	cursorEl1.firstChild.style.setProperty('--hr-spacing', createdCursor.cursor_options.hr_spacing + "px");
						    	cursorEl1.firstChild.style.setProperty('--hr-radius', createdCursor.cursor_options.hr_radius + "px");
						    	cursorEl1.firstChild.style.setProperty('--hr-padding', createdCursor.cursor_options.hr_padding + "px");
						    	cursorEl1.firstChild.style.setProperty('--hr-backdrop', createdCursor.cursor_options.hr_backdrop + "(" + createdCursor.cursor_options.hr_backdrop_amount + ")");
						    	cursorEl1.firstChild.style.setProperty('--hr-color', createdCursor.cursor_options.hr_color);
						    	cursorWrapper.style.setProperty('--duration', createdCursor.cursor_options.hr_duration + "ms");
						    	cursorWrapper.style.setProperty('--timing', createdCursor.cursor_options.hr_timing);
						  	}
						  	else {
							  	let svgString = `<svg viewBox="0 0 500 500"><path d="M50,250c0-110.5,89.5-200,200-200s200,89.5,200,200s-89.5,200-200,200S50,360.5,50,250" id="textcircle" fill="none"></path><text dy="25"><textPath xlink:href="#textcircle">${createdCursor.cursor_options.text}</textPath></text><circle cx="250" cy="250" r="${createdCursor.cursor_options.dot_width}" id="svg_circle_node"/></svg>`;
						    	
						    	cursorEl1.innerHTML = svgString;
						    	cursorWrapper.classList.add('cursor-text');
						    	cursorEl1.firstChild.style.setProperty('--dot-fill', createdCursor.cursor_options.dot_color);
						    	cursorEl1.firstChild.style.setProperty('--text-width', createdCursor.cursor_options.width + "px");
						    	cursorEl1.firstChild.style.setProperty('--text-transform', createdCursor.cursor_options.text_transform);
						    	cursorEl1.firstChild.style.setProperty('--font-weight', createdCursor.cursor_options.font_weight);
						    	cursorEl1.firstChild.style.setProperty('--text-color', createdCursor.cursor_options.text_color);
						    	cursorEl1.firstChild.style.setProperty('--font-size', createdCursor.cursor_options.font_size + "px");
						    	cursorEl1.firstChild.style.setProperty('--word-spacing', createdCursor.cursor_options.word_spacing + "px");
						    	cursorEl1.firstChild.style.setProperty('--animation-name', createdCursor.cursor_options.animation);
						    	cursorEl1.firstChild.style.setProperty('--animation-duration', createdCursor.cursor_options.animation_duration + "s");
						    	cursorEl1.firstChild.style.setProperty('--dot-width', createdCursor.cursor_options.dot_width + "px");
					    	}
						  break;
						}

						if (createdCursor.hover_cursors) {
							createdCursor.hover_cursors.forEach(function(hoverCursor) {
								switch (hoverCursor.hover_type) {
									case 'default':
										let defaultSelector = ['a', 'button'];
										defaultSelector.forEach(function(hoverSelector){
											let hoverElements = document.querySelectorAll(`${hoverSelector}:not(.wpcc-cursor *)`);
											[...hoverElements].forEach(function(el){
												el.addEventListener('mouseenter', function(){
													cursorWrapper.classList.add('link-hover');
												});
												el.addEventListener('mouseleave', function(){
													cursorWrapper.classList.remove('link-hover');
												});
											});
										});
									break;
									case 'snap':
										let snapSelector = [];
										if (hoverCursor.buttons == "on") {snapSelector.push('button')}
										if (hoverCursor.images == "on") {snapSelector.push('img')}
										if (hoverCursor.links == "on") {snapSelector.push('a')}
										if (hoverCursor.custom == "on") {snapSelector.push(hoverCursor.selector)}

										snapSelector.forEach(function(hoverSelector){
											let currentStyles, currentChild, currentType, currentCalcTop, currentCalcLeft, currentEl1VelXYFlag, currentEl1VelResizeFlag, currentEl2VelXYFlag, currentEl2VelResizeFlag;
											let hoverElements = document.querySelectorAll(`${hoverSelector}:not(.wpcc-cursor *)`);
											[...hoverElements].forEach(function(el){
												el.addEventListener('mouseenter', function(){

													currentEl1VelXYFlag = el1VelocityXYFlag; 
													currentEl1VelResizeFlag = el1VelocityResizeFlag;
													currentEl2VelXYFlag = el2VelocityXYFlag;
													currentEl2VelResizeFlag = el2VelocityResizeFlag;

													el1VelocityXYFlag = false;
													el1VelocityResizeFlag = false;
													el2VelocityXYFlag = false;
													el2VelocityResizeFlag = false;
													el1VelX = 1;
													el1VelY = 1;
													el2VelX = 1;
													el2VelY = 1;

													stopFlag = true;
													if (cursorWrapper.getAttribute('style')) {
														currentStyles = cursorWrapper.getAttribute('style');
														cursorWrapper.removeAttribute('style');
													}
													currentType = cursorWrapper.getAttribute('data-cursor-type');
													cursorWrapper.classList.remove(`cursor-${currentType}`);
													currentChild = cursorEl1.firstChild ? cursorEl1.removeChild(cursorEl1.firstChild) : null;

													currentCalcTop = calcTop;
													currentCalcLeft = calcLeft;
													calcTop = 0;
													calcLeft = 0;

													cursorWrapper.classList.add('cursor-snap');
										        	let elementPos = el.getBoundingClientRect();
										        	elementTop = elementPos.top - hoverCursor.padding;
										        	elementLeft = elementPos.left - hoverCursor.padding;
										        	hoverElementWidth = elementPos.width + (hoverCursor.padding * 2);
										        	hoverElementHeight = elementPos.height + (hoverCursor.padding * 2);
										        	cursorWrapper.style.setProperty('--width', hoverElementWidth + "px");
										        	cursorWrapper.style.setProperty('--height', hoverElementHeight + "px");
										        	
										        	cursorWrapper.style.setProperty('--blending', hoverCursor.blending);
										        	cursorWrapper.style.setProperty('--bgcolor', hoverCursor.bgcolor);
										        	cursorWrapper.style.setProperty('--border-color', hoverCursor.border_color);
										        	cursorWrapper.style.setProperty('--border-width', hoverCursor.border_width + "px");
										        	cursorWrapper.style.setProperty('--radius', hoverCursor.radius + "px");

										        	cursorEl1.style.top = 0;
										        	cursorEl1.style.left = 0;
												});
												el.addEventListener('mouseleave', function(){

													el1VelocityXYFlag = currentEl1VelXYFlag;
													el1VelocityResizeFlag = currentEl1VelResizeFlag;
													el2VelocityXYFlag = currentEl2VelXYFlag;
													el2VelocityResizeFlag = currentEl2VelResizeFlag;

													stopFlag = false;

													calcTop = currentCalcTop;
													calcLeft = currentCalcLeft;
													cursorWrapper.removeAttribute('style');
													if (currentStyles) {
														cursorWrapper.setAttribute('style', currentStyles);
													}
													
													cursorWrapper.classList.remove('cursor-snap');
													cursorWrapper.classList.add(`cursor-${currentType}`);
													cursorWrapper.setAttribute('data-cursor-type', currentType);
													if (currentChild) {cursorEl1.appendChild(currentChild);}
										        	cursorEl1.style.removeProperty('top');
										        	cursorEl1.style.removeProperty('left');
												});
											});
										});
									break;
									case 'shape':
										let selector = [];
										if (hoverCursor.buttons == "on") {selector.push('button')}
										if (hoverCursor.images == "on") {selector.push('img')}
										if (hoverCursor.links == "on") {selector.push('a')}
										if (hoverCursor.custom == "on") {selector.push(hoverCursor.selector)}

										selector.forEach(function(hoverSelector){
											let hoverElements = document.querySelectorAll(`${hoverSelector}:not(.wpcc-cursor *)`);
											[...hoverElements].forEach(function(el){
												let currentStyles, currentChild, currentType, currentCalcTop, currentCalcLeft, currentEl1VelXYFlag, currentEl1VelResizeFlag, currentEl2VelXYFlag, currentEl2VelResizeFlag;
												el.addEventListener('mouseenter', function(){
													currentEl1VelXYFlag = el1VelocityXYFlag; 
													currentEl1VelResizeFlag = el1VelocityResizeFlag;
													currentEl2VelXYFlag = el2VelocityXYFlag;
													currentEl2VelResizeFlag = el2VelocityResizeFlag;

													el1VelocityXYFlag = false;
													el1VelocityResizeFlag = false;
													el2VelocityXYFlag = false;
													el2VelocityResizeFlag = false;
													el1VelX = 1;
													el1VelY = 1;
													el2VelX = 1;
													el2VelY = 1;

													switch(hoverCursor.hover_fe_velocity) {
														case 'xy':
															el1VelocityXYFlag = true;
														break;
														case 'resize':
															el1VelocityResizeFlag = true;
														break;
													}

													switch(hoverCursor.hover_se_velocity) {
														case 'xy':
															el2VelocityXYFlag = true;
														break;
														case 'resize':
															el2VelocityResizeFlag = true;
														break;
													}

													if (cursorWrapper.getAttribute('style')) {
														currentStyles = cursorWrapper.getAttribute('style');
														cursorWrapper.removeAttribute('style');
													}
													currentChild = cursorEl1.firstChild ? cursorEl1.removeChild(cursorEl1.firstChild) : null;
													currentType = cursorWrapper.getAttribute('data-cursor-type');

													cursorWrapper.classList.remove(`cursor-${currentType}`);
													cursorWrapper.setAttribute('data-cursor-type', hoverCursor.hover_type);
													currentCalcTop = calcTop;
													currentCalcLeft = calcLeft;
													calcTop = 0;
													calcLeft = 0;

													cursorWrapper.classList.add(`cursor-${cursor.cursor_shape}`);

													cursorWrapper.style.setProperty('--fe-width', hoverCursor.hover_fe_width + "px");
													cursorWrapper.style.setProperty('--fe-height', hoverCursor.hover_fe_height + "px");
													cursorWrapper.style.setProperty('--fe-color', hoverCursor.hover_fe_color);
													cursorWrapper.style.setProperty('--fe-border-width', hoverCursor.hover_fe_border_width + "px");
													cursorWrapper.style.setProperty('--fe-border-radius', hoverCursor.hover_fe_radius + "px");
													cursorWrapper.style.setProperty('--fe-border-color', hoverCursor.hover_fe_border_color);
													cursorWrapper.style.setProperty('--fe-transition-duration', hoverCursor.hover_fe_duration + "ms");
													cursorWrapper.style.setProperty('--fe-transition-timing', hoverCursor.hover_fe_timing);
													cursorWrapper.style.setProperty('--fe-blending-mode', hoverCursor.hover_fe_blending);
													cursorWrapper.style.setProperty('--fe-zindex', hoverCursor.hover_fe_zindex);
													cursorWrapper.style.setProperty('--fe-backdrop', `${hoverCursor.hover_fe_backdrop}(${hoverCursor.hover_fe_backdrop_value})`);

													cursorWrapper.style.setProperty('--se-width', hoverCursor.hover_se_width + "px");
													cursorWrapper.style.setProperty('--se-height', hoverCursor.hover_se_height + "px");
													cursorWrapper.style.setProperty('--se-color', hoverCursor.hover_se_color);
													cursorWrapper.style.setProperty('--se-border-width', hoverCursor.hover_se_border_width + "px");
													cursorWrapper.style.setProperty('--se-border-radius', hoverCursor.hover_se_radius + "px");
													cursorWrapper.style.setProperty('--se-border-color', hoverCursor.hover_se_border_color);
													cursorWrapper.style.setProperty('--se-transition-duration', hoverCursor.hover_se_duration + "ms");
													cursorWrapper.style.setProperty('--se-transition-timing', hoverCursor.hover_se_timing);
													cursorWrapper.style.setProperty('--se-blending-mode', hoverCursor.hover_se_blending);
													cursorWrapper.style.setProperty('--se-zindex', hoverCursor.hover_se_zindex);
													cursorWrapper.style.setProperty('--se-backdrop', `${hoverCursor.hover_se_backdrop}(${createdCursor.cursor_options.se_backdrop_value})`);
												});
												el.addEventListener('mouseleave', function(){
													el1VelocityXYFlag = currentEl1VelXYFlag;
													el1VelocityResizeFlag = currentEl1VelResizeFlag;
													el2VelocityXYFlag = currentEl2VelXYFlag;
													el2VelocityResizeFlag = currentEl2VelResizeFlag;
													cursorWrapper.removeAttribute('style');
													if (currentStyles) {
														cursorWrapper.setAttribute('style', currentStyles);
													}
													cursorWrapper.classList.add(`cursor-${currentType}`);
													cursorWrapper.setAttribute('data-cursor-type', currentType);
													calcTop = currentCalcTop;
													calcLeft = currentCalcLeft;

													if (currentChild) {cursorEl1.appendChild(currentChild);}
												});
											});
										});
									break;
									case 'image':
										let imageSelector = [];
										if (hoverCursor.buttons == "on") {imageSelector.push('button')}
										if (hoverCursor.images == "on") {imageSelector.push('img')}
										if (hoverCursor.links == "on") {imageSelector.push('a')}
										if (hoverCursor.custom == "on") {imageSelector.push(hoverCursor.selector)}

										let imageCursor = document.createElement('img');
										imageCursor.setAttribute('src', hoverCursor.hover_image_url);

										let clickPointOption = hoverCursor.hover_click_point.split(','),
										clickPointX = ( Number(clickPointOption[0]) * Number(hoverCursor.width) ) / 100,
										clickPointY = ( Number(clickPointOption[1]) * Number(hoverCursor.height) ) / 100; 

										imageSelector.forEach(function(hoverSelector){
											let hoverElements = document.querySelectorAll(`${hoverSelector}:not(.wpcc-cursor *)`);
											let currentStyles, currentChild, currentType, currentCalcTop, currentCalcLeft, currentEl1VelXYFlag, currentEl1VelResizeFlag, currentEl2VelXYFlag, currentEl2VelResizeFlag;
											[...hoverElements].forEach(function(el){
												el.addEventListener('mouseenter', function(){
													currentEl1VelXYFlag = el1VelocityXYFlag; 
													currentEl1VelResizeFlag = el1VelocityResizeFlag;
													currentEl2VelXYFlag = el2VelocityXYFlag;
													currentEl2VelResizeFlag = el2VelocityResizeFlag;

													el1VelocityXYFlag = false;
													el1VelocityResizeFlag = false;
													el2VelocityXYFlag = false;
													el2VelocityResizeFlag = false;
													el1VelX = 1;
													el1VelY = 1;
													el2VelX = 1;
													el2VelY = 1;
													if (cursorWrapper.getAttribute('style')) {
														currentStyles = cursorWrapper.getAttribute('style');
														cursorWrapper.removeAttribute('style');
													}
													currentChild = cursorEl1.firstChild ? cursorEl1.removeChild(cursorEl1.firstChild) : null;
													cursorEl1.appendChild(imageCursor);
													currentType = cursorWrapper.getAttribute('data-cursor-type');
													
													cursorWrapper.classList.remove(`cursor-${currentType}`);
													cursorWrapper.classList.add('cursor-image');
													cursorWrapper.setAttribute('data-cursor-type', hoverCursor.hover_type);
													currentCalcTop = calcTop;
													currentCalcLeft = calcLeft;

													cursorWrapper.style.setProperty('--width', hoverCursor.width + "px");
													cursorWrapper.style.setProperty('--color', hoverCursor.color);
													cursorWrapper.style.setProperty('--radius', hoverCursor.radius + "px");
													if (hoverCursor.background != 'off') {
														cursorWrapper.style.setProperty('--padding', hoverCursor.padding + "px");
														paddingTop = hoverCursor.padding;
														paddingLeft = hoverCursor.padding;
													}

													calcTop = ( Number(paddingTop) + clickPointY ) * -1;
													calcLeft = ( Number(paddingLeft) + clickPointX ) * -1;
													cursorWrapper.style.setProperty('--blending', hoverCursor.blending);
												});
												el.addEventListener('mouseleave', function(){
													el1VelocityXYFlag = currentEl1VelXYFlag;
													el1VelocityResizeFlag = currentEl1VelResizeFlag;
													el2VelocityXYFlag = currentEl2VelXYFlag;
													el2VelocityResizeFlag = currentEl2VelResizeFlag;
													cursorEl1.removeChild(imageCursor);
													cursorWrapper.removeAttribute('style');
													if (currentStyles) {
														cursorWrapper.setAttribute('style', currentStyles);
													}
													cursorWrapper.classList.remove('cursor-image');
													cursorWrapper.classList.add(`cursor-${currentType}`);
													cursorWrapper.setAttribute('data-cursor-type', currentType);
													calcTop = currentCalcTop;
													calcLeft = currentCalcLeft;

													if (currentChild) {cursorEl1.appendChild(currentChild);}
												});
											});
										});
									break;
									case 'text':
										let textSelector = [];
										if (hoverCursor.buttons == "on") {textSelector.push('button')}
										if (hoverCursor.images == "on") {textSelector.push('img')}
										if (hoverCursor.links == "on") {textSelector.push('a')}
										if (hoverCursor.custom == "on") {textSelector.push(hoverCursor.selector)}
										
										textSelector.forEach(function(hoverSelector){
											let currentStyles, currentChild, currentType, currentCalcTop, currentCalcLeft, currentEl1VelXYFlag, currentEl1VelResizeFlag, currentEl2VelXYFlag, currentEl2VelResizeFlag;
											let hoverElements = document.querySelectorAll(`${hoverSelector}:not(.wpcc-cursor *)`);
											[...hoverElements].forEach(function(el){
												el.addEventListener('mouseenter', function(){
													currentEl1VelXYFlag = el1VelocityXYFlag; 
													currentEl1VelResizeFlag = el1VelocityResizeFlag;
													currentEl2VelXYFlag = el2VelocityXYFlag;
													currentEl2VelResizeFlag = el2VelocityResizeFlag;

													el1VelocityXYFlag = false;
													el1VelocityResizeFlag = false;
													el2VelocityXYFlag = false;
													el2VelocityResizeFlag = false;
													el1VelX = 1;
													el1VelY = 1;
													el2VelX = 1;
													el2VelY = 1;
													if (cursorWrapper.getAttribute('style')) {
														currentStyles = cursorWrapper.getAttribute('style');
														cursorWrapper.removeAttribute('style');
													}

													currentChild = cursorEl1.firstChild ? cursorEl1.removeChild(cursorEl1.firstChild) : null;
													currentType = cursorWrapper.getAttribute('data-cursor-type');
													cursorWrapper.classList.remove(`cursor-${currentType}`);
													cursorWrapper.classList.add('cursor-text');
													cursorWrapper.setAttribute('data-cursor-type', hoverCursor.hover_type);

													currentCalcTop = calcTop;
													currentCalcLeft = calcLeft;
													calcTop = 0;
													calcLeft = 0;
													
											  		let svgString = `<svg viewBox="0 0 500 500"><path d="M50,250c0-110.5,89.5-200,200-200s200,89.5,200,200s-89.5,200-200,200S50,360.5,50,250" id="textcircle" fill="none"></path><text dy="25"><textPath xlink:href="#textcircle">${hoverCursor.hover_text}</textPath></text><circle cx="250" cy="250" r="${hoverCursor.hover_dot_width}" id="svg_circle_node"/></svg>`;
												  	cursorEl1.innerHTML = svgString;
											    	cursorWrapper.style.setProperty('--dot-fill', hoverCursor.dot_color);
											    	cursorWrapper.style.setProperty('--text-width', hoverCursor.width + "px");
											    	cursorWrapper.style.setProperty('--text-transform', hoverCursor.hover_text_transform);
											    	cursorWrapper.style.setProperty('--font-weight', hoverCursor.hover_font_weight);
											    	cursorWrapper.style.setProperty('--text-color', hoverCursor.hover_text_color);
											    	cursorWrapper.style.setProperty('--font-size', hoverCursor.font_size + "px");
											    	cursorWrapper.style.setProperty('--word-spacing', hoverCursor.hover_word_spacing + "px");
											    	cursorWrapper.style.setProperty('--animation-name', hoverCursor.hover_animation);
											    	cursorWrapper.style.setProperty('--animation-duration', hoverCursor.hover_animation_duration + "s");
											    	cursorWrapper.style.setProperty('--dot-width', hoverCursor.hover_dot_width + "px");
											    	
												});
												el.addEventListener('mouseleave', function(){
													el1VelocityXYFlag = currentEl1VelXYFlag;
													el1VelocityResizeFlag = currentEl1VelResizeFlag;
													el2VelocityXYFlag = currentEl2VelXYFlag;
													el2VelocityResizeFlag = currentEl2VelResizeFlag;
													cursorWrapper.removeAttribute('style');
													if (currentStyles) {
														cursorWrapper.setAttribute('style', currentStyles);
													}
													cursorEl1.innerHTML = "";
													cursorWrapper.classList.remove('cursor-text');

													cursorWrapper.classList.add(`cursor-${currentType}`);
													cursorWrapper.setAttribute('data-cursor-type', currentType);

													if (currentChild) {cursorEl1.appendChild(currentChild);}
													
													calcTop = currentCalcTop;
													calcLeft = currentCalcLeft;
												});
											});
										});
									break;
									case 'horizontal':

										let hrSelector = [];
										if (hoverCursor.buttons == "on") {hrSelector.push('button')}
										if (hoverCursor.images == "on") {hrSelector.push('img')}
										if (hoverCursor.links == "on") {hrSelector.push('a')}
										if (hoverCursor.custom == "on") {hrSelector.push(hoverCursor.selector)}
										
										let hrContainer = document.createElement('div');
										
										hrSelector.forEach(function(hoverSelector){
											let currentStyles, currentChild, currentType, currentCalcTop, currentCalcLeft;
											let hoverElements = document.querySelectorAll(`${hoverSelector}:not(.wpcc-cursor *)`);
											[...hoverElements].forEach(function(el){
												el.addEventListener('mouseenter', function(){
													if (cursorWrapper.getAttribute('style')) {
														currentStyles = cursorWrapper.getAttribute('style');
														cursorWrapper.removeAttribute('style');
													}
													currentChild = cursorEl1.firstChild ? cursorEl1.removeChild(cursorEl1.firstChild) : null;
													currentType = cursorWrapper.getAttribute('data-cursor-type');
													currentCalcTop = calcTop;
													currentCalcLeft = calcLeft;
													calcTop = 0;
													calcLeft = 0;

													cursorWrapper.classList.add('cursor-horizontal');
								  					hrContainer.innerHTML = hoverCursor.hover_hr_text;
								  					cursorEl1.appendChild(hrContainer);

											    	cursorWrapper.style.setProperty('--hr-width', hoverCursor.hover_hr_width + "px");
											    	cursorWrapper.style.setProperty('--hr-transform', hoverCursor.hover_hr_transform);
											    	cursorWrapper.style.setProperty('--hr-size', hoverCursor.hover_hr_size + "px");
											    	cursorWrapper.style.setProperty('--hr-weight', hoverCursor.hover_hr_weight);
											    	cursorWrapper.style.setProperty('--bg-color', hoverCursor.hover_hr_bgcolor);
											    	cursorWrapper.style.setProperty('--hr-spacing', hoverCursor.hover_hr_spacing + "px");
											    	cursorWrapper.style.setProperty('--hr-radius', hoverCursor.hover_hr_radius + "px");
											    	cursorWrapper.style.setProperty('--hr-padding', hoverCursor.hover_hr_padding + "px");
											    	cursorWrapper.style.setProperty('--hr-backdrop', hoverCursor.hover_hr_backdrop + "(" + hoverCursor.hover_hr_backdrop_amount + ")");
											    	cursorWrapper.style.setProperty('--hr-color', hoverCursor.hover_hr_color);
											    	cursorWrapper.style.setProperty('--duration', hoverCursor.hover_hr_duration + "ms");
											    	cursorWrapper.style.setProperty('--timing', hoverCursor.hover_hr_timing);
											    	
												});
												el.addEventListener('mouseleave', function(){
													cursorWrapper.removeAttribute('style');
													if (currentStyles) {
														cursorWrapper.setAttribute('style', currentStyles);
													}
													cursorEl1.removeChild(hrContainer);
													cursorWrapper.classList.remove('cursor-horizontal');

													cursorWrapper.classList.add(`cursor-${currentType}`);
													cursorWrapper.setAttribute('data-cursor-type', currentType);

													calcTop = currentCalcTop;
													calcLeft = currentCalcLeft;

													if (currentChild) {cursorEl1.appendChild(currentChild);}
												});
											});
										});
									break;
								}
							});
						}
						// If no hover cursor is added
						else {
							let innerLinks = element.querySelectorAll('a');
							[...innerLinks].forEach(function(link) {
						        link.addEventListener('mouseenter', function() {
						        	cursorWrapper.classList.add('link-hover');
						        } );
						        link.addEventListener('mouseleave', function() {
						        	cursorWrapper.classList.remove('link-hover');
						        } );
						    }); 
						}
					}
				});

				if (predefinedCursor) {
					cursorWrapper.style.setProperty('--fe-width', cursor.width + "px");
					cursorWrapper.style.setProperty('--fe-height', cursor.width + "px");
					cursorWrapper.style.setProperty('--se-width', cursor.width + "px");
					cursorWrapper.style.setProperty('--se-height', cursor.width + "px");
					cursorWrapper.style.setProperty('--fe-color', cursor.color);
					cursorWrapper.style.setProperty('--se-color', cursor.color);
					cursorWrapper.style.setProperty('--fe-blending', cursor.blending_mode);
					cursorWrapper.style.setProperty('--se-blending', cursor.blending_mode);

					let innerLinks = element.querySelectorAll('a');
					[...innerLinks].forEach(function(link) {
				        link.addEventListener('mouseenter', function() {
				        	cursorWrapper.classList.add('link-hover');
				        } );
				        link.addEventListener('mouseleave', function() {
				        	cursorWrapper.classList.remove('link-hover');
				        } );
				    }); 
				}
			
				// Show Default Cursor
				if ( !Number(cursor.default_cursor) ) {
					element.classList.add('no-cursor');
				}
				else {
					element.classList.add('default-cursor');
				}

				element.addEventListener('mouseenter', function() {
					mouseEntered = true;
					cursorWrapper.classList.add('active');
					window.setTimeout(function(){
						cursorWrapper.classList.remove('no-transition');
					}, 1000);
				});

				element.addEventListener('mouseleave', function() {
					mouseEntered = false;
					cursorWrapper.classList.remove('active');
				}); 

				element.addEventListener('pointermove', function() {
					if (!mouseEntered) {
						mouseEntered = true;
						cursorWrapper.classList.add('active');

						window.setTimeout(function(){
							cursorWrapper.classList.remove('no-transition');
						}, 1000);
					}
				});
				requestAnimationFrame(renderCursor);

				function renderCursor() {
		   			cursorEl1.style.transform = `matrix(${mouseData.el1ScaleX}, 0, 0, ${mouseData.el1ScaleY}, ${mouseData.hx + calcLeft}, ${mouseData.hy + calcTop})`;
		   			cursorEl2.style.transform = `matrix(${mouseData.el2ScaleX}, 0, 0, ${mouseData.el2ScaleY}, ${mouseData.x}, ${mouseData.y})`;

					requestAnimationFrame(renderCursor);
				}

				var innerInputs = element.querySelectorAll('input[type="text"], input[type="email"], input[type="search"], input[type="number"], input[type="password"], input[type="url"], input[type="date"], input[type="range"], textarea');	
			    [...innerInputs].forEach(function(input) {
			        input.addEventListener('mouseenter', function() {
			        	cursorWrapper.classList.add('input-hover');
			        } );
			        input.addEventListener('mouseleave', function() {
			        	cursorWrapper.classList.remove('input-hover');
			        } );
			    });

			    // Mutation Observer
			    let observerOptions = {
                    childList: true,
                    subtree: true,
                },
                observer = new MutationObserver(callback);

                function callback(mutations) {
                    for (let mutation of mutations) {
                        if (mutation.type === 'childList') {
                            for(let addedNode of mutation.addedNodes) {
                            	if (addedNode.nodeType == Node.ELEMENT_NODE) {
                            		if (cursorWrapper.contains(addedNode)) {
                            			return false;
                            		}
                    				let cursorId = cursor.cursor_shape.substring(8);
									[...created_cursors].forEach(function(createdCursor){
										if (createdCursor.cursor_id == cursorId) {
											createdCursor.hover_cursors.forEach(function(hover) {
												let selector = [];
												if (hover.buttons == "on") {selector.push('button')}
												if (hover.images == "on") {selector.push('img')}
												if (hover.links == "on") {selector.push('a')}
												if (hover.custom == "on") {selector.push(hover.selector)}
												
												selector.forEach(function(selectorItem){
													if (addedNode.matches(selectorItem)) {
														switch (hover.hover_type) {
															case 'default':
																addedNode.addEventListener('mouseenter', function(){
																	cursorWrapper.classList.add('link-hover');
																});
																addedNode.addEventListener('mouseleave', function(){
																	cursorWrapper.classList.remove('link-hover');
																});
															break;
															case 'snap':
																let currentStyles, currentChild, currentType, currentCalcTop, currentCalcLeft;
																addedNode.addEventListener('mouseenter', function(){
																	stopFlag = true;
																	if (cursorWrapper.getAttribute('style')) {
																		currentStyles = cursorWrapper.getAttribute('style');
																		cursorWrapper.removeAttribute('style');
																	}
																	
																	currentType = cursorWrapper.getAttribute('data-cursor-type');
																	cursorWrapper.classList.remove(`cursor-${currentType}`);
																	currentChild = cursorEl1.firstChild ? cursorEl1.removeChild(cursorEl1.firstChild) : null;

																	currentCalcTop = calcTop;
																	currentCalcLeft = calcLeft;

																	calcTop = 0;
																	calcLeft = 0;

																	cursorWrapper.classList.add('cursor-snap');
														        	let elementPos = el.getBoundingClientRect();
														        	elementTop = elementPos.top - hoverCursor.padding;
														        	elementLeft = elementPos.left - hoverCursor.padding;
														        	hoverElementWidth = elementPos.width + (hoverCursor.padding * 2);
														        	hoverElementHeight = elementPos.height + (hoverCursor.padding * 2);
														        	cursorWrapper.style.setProperty('--width', hoverElementWidth + "px");
														        	cursorWrapper.style.setProperty('--height', hoverElementHeight + "px");
														        	

														        	cursorWrapper.style.setProperty('--blending', hoverCursor.blending);
														        	cursorWrapper.style.setProperty('--bgcolor', hoverCursor.bgcolor);
														        	cursorWrapper.style.setProperty('--border-color', hoverCursor.border_color);
														        	cursorWrapper.style.setProperty('--border-width', hoverCursor.border_width + "px");
														        	cursorWrapper.style.setProperty('--radius', hoverCursor.radius + "px");

														        	cursorEl1.style.top = 0;
														        	cursorEl1.style.left = 0;

																});
																addedNode.addEventListener('mouseleave', function(){
																	stopFlag = false;

																	calcTop = currentCalcTop;
																	calcLeft = currentCalcLeft;
																	cursorWrapper.removeAttribute('style');
																	if (currentStyles) {
																		cursorWrapper.setAttribute('style', currentStyles);
																	}

																	
																	cursorWrapper.classList.remove('cursor-snap');
																	cursorWrapper.classList.add(`cursor-${currentType}`);
																	cursorWrapper.setAttribute('data-cursor-type', currentType);
																	if (currentChild) {cursorEl1.appendChild(currentChild);}
														        	cursorEl1.style.removeProperty('top');
														        	cursorEl1.style.removeProperty('left');
																});
															break;
															case 'shape':
																let currentStylesShape, currentChildShape, currentTypeShape, currentCalcTopShape, currentCalcLeftShape;
																addedNode.addEventListener('mouseenter', function(){
																	if (cursorWrapper.getAttribute('style')) {
																		currentStylesShape = cursorWrapper.getAttribute('style');
																		cursorWrapper.removeAttribute('style');
																	}
																	
																	currentChildShape = cursorEl1.firstChild ? cursorEl1.removeChild(cursorEl1.firstChild) : null;
																	currentTypeShape = cursorWrapper.getAttribute('data-cursor-type');

																	cursorWrapper.classList.remove(`cursor-${currentTypeShape}`);
																	cursorWrapper.setAttribute('data-cursor-type', hover.hover_type);
																	currentCalcTopShape = calcTop;
																	currentCalcLeftShape = calcLeft;
																	calcTop = 0;
																	calcLeft = 0;
																	cursorWrapper.classList.add(`cursor-${cursor.cursor_shape}`);

																	cursorWrapper.style.setProperty('--fe-width', hover.hover_fe_width + "px");
																	cursorWrapper.style.setProperty('--fe-height', hover.hover_fe_height + "px");
																	cursorWrapper.style.setProperty('--fe-color', hover.hover_fe_color);
																	cursorWrapper.style.setProperty('--fe-border-width', hover.hover_fe_border_width + "px");
																	cursorWrapper.style.setProperty('--fe-border-radius', hover.hover_fe_radius + "px");
																	cursorWrapper.style.setProperty('--fe-border-color', hover.hover_fe_border_color);
																	cursorWrapper.style.setProperty('--fe-transition-duration', hover.hover_fe_duration + "ms");
																	cursorWrapper.style.setProperty('--fe-transition-timing', hover.hover_fe_timing);
																	cursorWrapper.style.setProperty('--fe-blending-mode', hover.hover_fe_blending);
																	cursorWrapper.style.setProperty('--fe-zindex', hover.hover_fe_zindex);
																	cursorWrapper.style.setProperty('--fe-backdrop', `${hover.hover_fe_backdrop}(${hover.hover_fe_backdrop_value})`);

																	cursorWrapper.style.setProperty('--se-width', hover.hover_se_width + "px");
																	cursorWrapper.style.setProperty('--se-height', hover.hover_se_height + "px");
																	cursorWrapper.style.setProperty('--se-color', hover.hover_se_color);
																	cursorWrapper.style.setProperty('--se-border-width', hover.hover_se_border_width + "px");
																	cursorWrapper.style.setProperty('--se-border-radius', hover.hover_se_radius + "px");
																	cursorWrapper.style.setProperty('--se-border-color', hover.hover_se_border_color);
																	cursorWrapper.style.setProperty('--se-transition-duration', hover.hover_se_duration + "ms");
																	cursorWrapper.style.setProperty('--se-transition-timing', hover.hover_se_timing);
																	cursorWrapper.style.setProperty('--se-blending-mode', hover.hover_se_blending);
																	cursorWrapper.style.setProperty('--se-zindex', hover.hover_se_zindex);
																	cursorWrapper.style.setProperty('--se-backdrop', `${hover.hover_se_backdrop}(${createdCursor.cursor_options.se_backdrop_value})`);
																});
																addedNode.addEventListener('mouseleave', function(){
																	cursorWrapper.removeAttribute('style');
																	if (currentStylesShape) {
																		cursorWrapper.setAttribute('style', currentStylesShape);
																	}
																	cursorWrapper.classList.add(`cursor-${currentTypeShape}`);
																	cursorWrapper.setAttribute('data-cursor-type', currentTypeShape);

																	calcTop = currentCalcTopShape;
																	calcLeft = currentCalcLeftShape;

																	if (currentChildShape) {cursorEl1.appendChild(currentChildShape);}
																});	
															break;
															case 'image':
																let imageCursor = document.createElement('img');
																imageCursor.setAttribute('src', hover.hover_image_url);
																
																let clickPointOption = hover.hover_click_point.split(','),
																clickPointX = ( Number(clickPointOption[0]) * Number(hover.width) ) / 100,
																clickPointY = ( Number(clickPointOption[1]) * Number(hover.height) ) / 100; 
																
																let currentStylesImage, currentChildImage, currentTypeImage, currentCalcTopImage, currentCalcLeftImage;
																addedNode.addEventListener('mouseenter', function(){
																	if (cursorWrapper.getAttribute('style')) {
																		currentStylesImage = cursorWrapper.getAttribute('style');
																		cursorWrapper.removeAttribute('style');
																	}
																	currentChildImage = cursorEl1.firstChild ? cursorEl1.removeChild(cursorEl1.firstChild) : null;
																	cursorEl1.appendChild(imageCursor);
																	currentTypeImage = cursorWrapper.getAttribute('data-cursor-type');
																	
																	cursorWrapper.classList.remove(`cursor-${currentTypeImage}`);
																	cursorWrapper.classList.add('cursor-image');
																	cursorWrapper.setAttribute('data-cursor-type', hover.hover_type);
																	currentCalcTopImage = calcTop;
																	currentCalcLeftImage = calcLeft;

																	cursorWrapper.style.setProperty('--width', hover.width + "px");
																	cursorWrapper.style.setProperty('--color', hover.color);
																	cursorWrapper.style.setProperty('--radius', hover.radius + "px");
																	if (hover.background != 'off') {
																		cursorWrapper.style.setProperty('--padding', hover.padding + "px");
																		paddingTop = hover.padding;
																		paddingLeft = hover.padding;
																	}
																	calcTop = ( Number(paddingTop) + clickPointY ) * -1;
																	calcLeft = ( Number(paddingLeft) + clickPointX ) * -1;
																	cursorWrapper.style.setProperty('--blending', hover.blending);
																});
																addedNode.addEventListener('mouseleave', function(){
																	cursorEl1.removeChild(imageCursor);
																	cursorWrapper.removeAttribute('style');
																	if (currentStylesImage) {
																		cursorWrapper.setAttribute('style', currentStylesImage);
																	}
																	cursorWrapper.classList.remove('cursor-image');
																	cursorWrapper.classList.add(`cursor-${currentTypeImage}`);
																	cursorWrapper.setAttribute('data-cursor-type', currentTypeImage);
																	calcTop = currentCalcTopImage;
																	calcLeft = currentCalcLeftImage;
																	if (currentChildImage) {cursorEl1.appendChild(currentChildImage);}
																});	
																
															break;
															case 'text':
																let currentStylesText, currentChildText, currentTypeText, currentCalcTopText, currentCalcLeftText;
																addedNode.addEventListener('mouseenter', function(){
																	if (cursorWrapper.getAttribute('style')) {
																		currentStylesText = cursorWrapper.getAttribute('style');
																		cursorWrapper.removeAttribute('style');
																	}
																	
																	currentChildText = cursorEl1.firstChild ? cursorEl1.removeChild(cursorEl1.firstChild) : null;
																	currentTypeText = cursorWrapper.getAttribute('data-cursor-type');
																	cursorWrapper.classList.remove(`cursor-${currentTypeText}`);
																	cursorWrapper.classList.add('cursor-text');
																	cursorWrapper.setAttribute('data-cursor-type', hover.hover_type);
																	currentCalcTopText = calcTop;
																	currentCalcLeftText = calcLeft;
																	calcTop = 0;
																	calcLeft = 0;

															  		let svgString = `<svg viewBox="0 0 500 500"><path d="M50,250c0-110.5,89.5-200,200-200s200,89.5,200,200s-89.5,200-200,200S50,360.5,50,250" id="textcircle" fill="none"></path><text dy="25"><textPath xlink:href="#textcircle">${hover.hover_text}</textPath></text><circle cx="250" cy="250" r="${hover.hover_dot_width}" id="svg_circle_node"/></svg>`;
																  	cursorEl1.innerHTML = svgString;
															    	cursorWrapper.style.setProperty('--dot-fill', hover.dot_color);
															    	cursorWrapper.style.setProperty('--text-width', hover.width + "px");
															    	cursorWrapper.style.setProperty('--text-transform', hover.hover_text_transform);
															    	cursorWrapper.style.setProperty('--font-weight', hover.hover_font_weight);
															    	cursorWrapper.style.setProperty('--text-color', hover.hover_text_color);
															    	cursorWrapper.style.setProperty('--font-size', hover.font_size + "px");
															    	cursorWrapper.style.setProperty('--word-spacing', hover.hover_word_spacing + "px");
															    	cursorWrapper.style.setProperty('--animation-name', hover.hover_animation);
															    	cursorWrapper.style.setProperty('--animation-duration', hover.hover_animation_duration + "s");
															    	cursorWrapper.style.setProperty('--dot-width', hover.hover_dot_width + "px");
															    	
																});
																addedNode.addEventListener('mouseleave', function(){
																	cursorWrapper.removeAttribute('style');
																	if (currentStylesText) {
																		cursorWrapper.setAttribute('style', currentStylesText);
																	}
																	cursorEl1.innerHTML = "";
																	cursorWrapper.classList.remove('cursor-text');
																	cursorWrapper.classList.add(`cursor-${currentTypeText}`);
																	cursorWrapper.setAttribute('data-cursor-type', currentTypeText);
																	if (currentChildText) {cursorEl1.appendChild(currentChildText);}
																	calcTop = currentCalcTopText;
																	calcLeft = currentCalcLeftText;
																});
															break;
															case 'horizontal':
																let hrContainer = document.createElement('div');
																let currentStylesHorizontal, currentChildHorizontal, currentTypeHorizontal, currentCalcTopHorizontal, currentCalcLeftHorizontal;
																addedNode.addEventListener('mouseenter', function(){
																	if (cursorWrapper.getAttribute('style')) {
																		currentStylesHorizontal = cursorWrapper.getAttribute('style');
																		cursorWrapper.removeAttribute('style');
																	}
																	currentChildHorizontal = cursorEl1.firstChild ? cursorEl1.removeChild(cursorEl1.firstChild) : null;
																	currentTypeHorizontal = cursorWrapper.getAttribute('data-cursor-type');
																	currentCalcTopHorizontal = calcTop;
																	currentCalcLeftHorizontal = calcLeft;
																	calcTop = 0;
																	calcLeft = 0;
																	cursorWrapper.classList.add('cursor-horizontal');
												  					hrContainer.innerHTML = hover.hover_hr_text;
												  					cursorEl1.appendChild(hrContainer);
															    	cursorWrapper.style.setProperty('--hr-width', hover.hover_hr_width + "px");
															    	cursorWrapper.style.setProperty('--hr-transform', hover.hover_hr_transform);
															    	cursorWrapper.style.setProperty('--hr-size', hover.hover_hr_size + "px");
															    	cursorWrapper.style.setProperty('--hr-weight', hover.hover_hr_weight);
															    	cursorWrapper.style.setProperty('--bg-color', hover.hover_hr_bgcolor);
															    	cursorWrapper.style.setProperty('--hr-spacing', hover.hover_hr_spacing + "px");
															    	cursorWrapper.style.setProperty('--hr-radius', hover.hover_hr_radius + "px");
															    	cursorWrapper.style.setProperty('--hr-padding', hover.hover_hr_padding + "px");
															    	cursorWrapper.style.setProperty('--hr-backdrop', hover.hover_hr_backdrop + "(" + hover.hover_hr_backdrop_amount + ")");
															    	cursorWrapper.style.setProperty('--hr-color', hover.hover_hr_color);
															    	cursorWrapper.style.setProperty('--duration', hover.hover_hr_duration + "ms");
															    	cursorWrapper.style.setProperty('--timing', hover.hover_hr_timing);	
																});
																addedNode.addEventListener('mouseleave', function(){
																	cursorWrapper.removeAttribute('style');
																	if (currentStylesHorizontal) {
																		cursorWrapper.setAttribute('style', currentStylesHorizontal);
																	}
																	cursorEl1.removeChild(hrContainer);
																	cursorWrapper.classList.remove('cursor-horizontal');
																	cursorWrapper.classList.add(`cursor-${currentTypeHorizontal}`);
																	cursorWrapper.setAttribute('data-cursor-type', currentTypeHorizontal);
																	calcTop = currentCalcTopHorizontal;
																	calcLeft = currentCalcLeftHorizontal;
																	if (currentChildHorizontal) {cursorEl1.appendChild(currentChildHorizontal);}
																});
															break;
														}
													}
												});
											})
										}
									});
                            	} 
                            }
                        }
                    }
                }
                observer.observe(body, observerOptions);
					
			}
		});
	});
})();



/* :) Let's meke internet BEAUTIFUL*/
/*
 _       __     __       ______                    __
| |     / /__  / /_     /_  __/_______  ____  ____/ /_  __
| | /| / / _ \/ __ \     / / / ___/ _ \/ __ \/ __  / / / /
| |/ |/ /  __/ /_/ /    / / / /  /  __/ / / / /_/ / /_/ /
|__/|__/\___/_.___/    /_/ /_/   \___/_/ /_/\__,_/\__, /
                                                 /____/
*/