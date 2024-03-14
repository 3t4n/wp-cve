// Docu : http://wiki.moxiecode.com/index.php/TinyMCE:Create_plugin/3.x#Creating_your_own_plugins
// And see editor_puglin_src.js in wordpress, in /wordpress/wp-includes/js/tinymce/plugins/wplink/ditor_puglin_src.js - beispiel für hier
// and wordpress/wp-includes/js/tinymce/plugins/wordpress/ditor_puglin_src.js //allgemeine verwendung - hier z.b. auhc Help und aufruf von WP_Link

(function() {
	
	var originalDimensions = new Array();
	
	
	tinymce.create('tinymce.plugins.BMoExpoTinyMCEButton', {
		url: '',
		editor: {},
		
		/**
		 * Initializes the plugin, this will be executed after the plugin has been created.
		 * This call is done before the editor instance has finished it's initialization so use the onInit event
		 * of the editor instance to intercept that event.
		 *
		 * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
		 * @param {string} url Absolute URL to where the plugin is located.
		 */
		init : function(ed, url) {
			
			
			// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mceExample'); 
			ed.addCommand('mceBMoExpo', function() {
				vp = tinymce.DOM.getViewPort();
				H = vp.h-150; //580 < (vp.h - 70) ? 580 : vp.h - 70;
				W = vp.w-100; //650 < vp.w ? 650 : vp.w;
				
				ed.windowManager.open({// siehe tinymce.com/wiki.php
				    // call content via admin-ajax, no need to know the full plugin path
					file : ajaxurl + '?action=BMoExpo_tinymce_window',//läd in das iframe per ajax html, welches von 	add_action('wp_ajax_BMoExpo_tinymce_window', array ($this, 'BMo_Expo_ajax_mce_window') ); zurückgeliefert wird;
					id: 'mceBMoExpo_window',
					width : W + 'px',
					height : H + 'px',
					resizeable: false,
					inline : 1 //sonst popup
				}, {//custom parameter
					ajax_url: ajaxurl, //wp ajaxurl
					plugin_url : url // Plugin absolute URL
				});
				
			});

			// Register example button
			ed.addButton('BMoExpo', {
				title : 'BMoExpo',
				cmd : 'mceBMoExpo',
				image : url + '/BMoExpo.png'
			});

			// Add a node change handler, selects the button in the UI when a image is selected
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('BMoExpo', n.nodeName == 'IMG');
				if ( n.nodeName == 'IMG' && ed.dom.hasClass(n, 'bmoExpo_preview') ) {
	               	stopResizing(ed, n);//by bmo, wirkt am besten hier
				}
			});
			
			// Ändere das Aussehen des shortcodes (siehe wpEditImage oder wpgallery)
			ed.onBeforeSetContent.add(function(ed, o){
				o.content = ed.wpSetShortcode(o.content);
			});
			
			ed.wpSetShortcode = function(content) {//retrun div statts shortcode - see editor_plugin from gallery
				return content.replace(/\[BMo_([^ ]*) +(id=|tags=)([^ ]*) +(.*)\]/g,function(original_content, type, selector, selection, rest_in_tag){ 
					/*regexp see https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/RegExp
					(?:x) 	Matches x but does not remember the match. These are called non-capturing parentheses. The matched substring can not be recalled from the resulting array's elements [1], ..., [n] or from the predefined RegExp object's properties $1, ..., $9.
					x(?=y) 	Matches x only if x is followed by y. For example, /Jack(?=Sprat)/ matches 'Jack' only if it is followed by 'Sprat'. /Jack(?=Sprat|Frost)/ matches 'Jack' only if it is followed by 'Sprat' or 'Frost'. However, neither 'Sprat' nor 'Frost' is part of the match results.
					x(?!y) 	Matches x only if x is not followed by y. For example, /\d+(?!\.)/ matches a number only if it is not followed by a decimal point.
					/\d+(?!\.)/.exec("3.141") matches 141 but not 3.141.
					*/
					
					var width, img, imgName;
					
					if(!type) type="scrollGallery";
					//console.log([original_content,type,selector,selection,rest_in_tag]);
					
					if(type=="scrollGallery"){
						imgName = "BMoIcons_scrollGallery_bottom.png";
						thumbPosition_arr = rest_in_tag.match(/sG_thumbPosition=([^ ]*) ?/);
						if(thumbPosition_arr){
							if ( thumbPosition_arr.length >=2 ){
								if(thumbPosition_arr[1]=="top")
									imgName = "BMoIcons_scrollGallery_top.png";
								if(thumbPosition_arr[1]=="left")
									imgName = "BMoIcons_scrollGallery_left.png";
								if(thumbPosition_arr[1]=="bottom")
									imgName = "BMoIcons_scrollGallery_bottom.png";
								if(thumbPosition_arr[1]=="right")
									imgName = "BMoIcons_scrollGallery_right.png";
								if(thumbPosition_arr[1]=="none")
									imgName = "BMoIcons_scrollGallery_none.png";
							}
						}
					}
					if(type=="scrollLightboxGallery"){
						imgName = "BMoIcons_scrollLightboxGallery_h.png";
						thumbPosition_arr = rest_in_tag.match(/slG_vertical=([^ ]*) ?/);
						if(thumbPosition_arr){
							if ( thumbPosition_arr.length >=2 ){
								if(thumbPosition_arr[1]=="0")
									imgName = "BMoIcons_scrollLightboxGallery_h.png";
								if(thumbPosition_arr[1]=="1")
									imgName = "BMoIcons_scrollLightboxGallery_v.png";
							}
						}
					}
					
					
					
					width_arr = rest_in_tag.match(/gallery_width=([0-9]*) ?/);
					if(width_arr){
						if ( width_arr.length >=2 ){
							width = width_arr[1];
							rest_in_tag = rest_in_tag.replace(width_arr[0], '');
						}else{
							width = 500;
						}
					}else{
						width = 500;
					}
					
					 
					
					img = "<img  class='bmoExpo_preview' style='width: "+( parseInt(width) )+"px' src='"+url.replace('/js/admin/tinyMCEButton','')+"/css/admin/img/"+imgName+"' align='center' valing='bottom' title='"+tinymce.DOM.encode(original_content)+"'/>";
					
					return 	img;
				});
			};
			
			// Hole den Shortcode aus geändertem Content
			ed.onPostProcess.add(function(ed, o) {
				if (o.get)
					o.content = ed.wpGetShortcodeFromContent(o.content);
			});
			
			ed.wpGetShortcodeFromContent = function(content) {//wieder shorttag aus titel holen und ersetzen
				function getAttr(s, n) {
					n = new RegExp(n + '=\"([^\"]+)\"', 'g').exec(s);
					return n ? tinymce.DOM.decode(n[1]) : '';
				};
				
				return content.replace(/(?:<p[^>]*>)*(<img[^>]+>)(?:<\/p>)*/g, function(a,im) {
					var cls = getAttr(im, 'class');

					if ( cls.indexOf('bmoExpo_preview') != -1 )
						return '<p>'+tinymce.trim(getAttr(im, 'title'))+'</p>';

					return a;
				});
			};
			
			
			//Remove all klick events - see http://code.google.com/p/tinymce-plugin-advimagescale/source/browse/trunk/editor_plugin_src.js
			
			ed.onMouseDown.addToTop(function(ed, e) {
				var target = e.target;
				//console.log("down"+e.target.nodeName );
				
				if ( target.nodeName == 'IMG' && ed.dom.hasClass(target, 'bmoExpo_preview') ) {
					//ed.selection.select(target);
					//ed.dom.setAttrib(target,'data-mce-style', 'height:'+target.offsetHeight+'px; width:'+target.offsetWidth+'px;'); // save the default height und width
					//console.log(target);
					ed.dom.events.cancel(e);
					ed.dom.events.stop(e);
					ed.plugins.wordpress._hideButtons();
					prepareImage(ed, target);
					//ed.plugins.wordpress._showButtons(e.target, 'wp_gallerybtns'); keine buttons, für Buttons siehe wpgallery tinymce plugin
					return false;
				}
				return true;
			});
			
			ed.onMouseUp.addToTop(function(ed, e) {
				var target = e.target;
				console.log("up"+e.target.nodeName );
				
				if ( target.nodeName == 'IMG' && ed.dom.hasClass(target, 'bmoExpo_preview') ) {
					 // setTimeout is necessary to allow the browser to complete the resize so we have new dimensions
					 setTimeout(function() {
					       stopResizing(ed, target);
					 }, 100);
				}
				return true;
			});
			
			
			ed.onInit.add(function(ed) {
				// iOS6 doesn't show the buttons properly on click, show them on 'touchstart'
				if ( 'ontouchstart' in window ) {
					ed.dom.events.add(ed.getBody(), 'touchstart', function(e){
						console.log("touchstart");
						var target = e.target;

						if ( target.nodeName == 'IMG' && ed.dom.hasClass(target, 'bmoExpo_preview') ) {
						//	ed.selection.select(target);
							ed.dom.events.cancel(e);
							ed.plugins.wordpress._hideButtons();
						}
					});
				}
			});
			
			// Catch editor.setContent() events via onPreProcess (because onPreProcess allows us to    
			// modify the DOM before it is inserted, unlike onSetContent)
		 	ed.onPreProcess.add(function(ed, o) {
		 		if (!o.set) return; // only 'set' operations let us modify the nodes
 				// loop in each img node and run constrainSize
                tinymce.each(ed.dom.select('img', o.node), function(currentNode) {
					if ( currentNode.nodeName == 'IMG' && ed.dom.hasClass(currentNode, 'bmoExpo_preview') ) {
	                	stopResizing(ed, currentNode);
					}
               	});
           	});
			
			
		},
		
		/**
		 * Creates control instances based in the incomming name. This method is normally not
		 * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
		 * but you sometimes need to create more complex controls like listboxes, split buttons etc then this
		 * method can be used to create those.
		 *
		 * @param {String} n Name of the control to create.
		 * @param {tinymce.ControlManager} cm Control manager to use inorder to create new control.
		 * @return {tinymce.ui.Control} New control instance or null if no control was created.
		 */
		createControl : function(n, cm) {
			return null;
		},

		/**
		 * Returns information about the plugin as a name/value array.
		 * The current keys are longname, author, authorurl, infourl and version.
		 *
		 * @return {Object} Name/value array containing information about the plugin.
		 */
		getInfo : function() {
			return {
					longname  : 'BMoExpo',
					author 	  : 'Benedikt Morschheuser',
					authorurl : 'http://www.bmo-design.de/',
					infourl   : 'http://software.bmo-design.de/',
					version   : "1.0"
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('BMoExpo', tinymce.plugins.BMoExpoTinyMCEButton);
	
	//helper funktionen gegen resizing
	function prepareImage(ed, el) {
	    var dom  = ed.dom;
        var elId = dom.getAttrib(el, 'mce_advimageresize_id');

        // is this the first time this image tag has been seen?
        if (!elId) {
            var elId = ed.id + "_" + ed.dom.uniqueId();
            dom.setAttrib(el, 'mce_advimageresize_id', elId);
            storeDimensions(ed, el);
        }

        return elId;
    }

	/**
     * Store image dimensions, pre-resize
     *
     * @param {object} el HTMLDomNode
     */
    function storeDimensions(ed, el) {
        var dom = ed.dom;
        var elId = dom.getAttrib(el, 'mce_advimageresize_id');

        // store original dimensions if this is the first resize of this element
        if (!originalDimensions[elId]) {
            originalDimensions[elId] = {width: dom.getAttrib(el, 'width', el.width), height: dom.getAttrib(el, 'height', el.height)};
        }
        return true;
    }

	
	
	function stopResizing(ed, el, e) { //bei wpgallery ist das dadurch gelöst, dass das css eine width 99% angibt und eine feste höhe
	        var dom     = ed.dom;
	        var elId    = prepareImage(ed, el); // also calls storeDimensions
	        var resized = (dom.getAttrib(el, 'width') != originalDimensions[elId].width || dom.getAttrib(el, 'height') != originalDimensions[elId].height);

	        if (!resized)
	            return; // nothing to do
	
			//reset
			dom.setAttrib(el, 'width',  originalDimensions[elId].width);
			dom.setAttrib(el, 'height', originalDimensions[elId].height);
			if (tinymce.isGecko) fixGeckoHandles(ed);
	}	
	
	/**
     * Fix gecko resize handles glitch
     */
    function fixGeckoHandles(ed) {
        ed.execCommand('mceRepaint', false);
    }
			
		
})();
