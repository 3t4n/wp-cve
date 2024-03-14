
(function() {
	// Load plugin specific language pack


	tinymce.create('tinymce.plugins.SpeechBubblePlugin', {
		/**
		 * Initializes the plugin, this will be executed after the plugin has been created.
		 * This call is done before the editor instance has finished it's initialization so use the onInit event
		 * of the editor instance to intercept that event.
		 *
		 * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
		 * @param {string} url Absolute URL to where the plugin is located.
		 */
                   // ed.dom.

		init : function(ed, url) {
			// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mceExample');
                            	ed.onInit.add(function() {
					ed.dom.loadCSS(url + "/css/main.css");
                                });

			ed.addCommand('mceSpeechBubble', function() {


ed.onMouseDown.add(function(ed, e) {
      var bubble_height = jQuery(e.target).height();

       
        jQuery(e.target).children('.tail').css('margin-top',bubble_height);
});

ed.onClick.add(function(ed, e) {
   
    //if (bubble_on==true)
   // return tinymce.dom.Event.cancel(e);

   if (e.target.nodeName=="IMG") {

      


        if (e.pageX) {

             var x = e.pageX - e.target.offsetLeft-10;
            var y = e.pageY - e.target.offsetTop-95;
            var height = 60;
            var width=135;
            var padding_top = 8;
        } else {//ie
            var x = e.clientX - e.target.offsetLeft-18;
            var y =  e.clientY - e.target.offsetTop-94;
            var height = 70;
            var width =  155
            var padding_top = 2;
             
        }
     

        jQuery(e.target).parent().prepend('<div class="speech-bubble" style="z-index:999999;width:'+width+';height:'+height+'px;padding:'+padding_top+'px 8px 8px 7px;position:absolute;text-align:center;color:#333;margin-left:'+x+'px;margin-top:'+y+'px;background-color:#FFF;border:1px solid #999999;-moz-border-radius: 15px;-webkit-border-radius: 15px;"><div style="background:url('+url+'/img/speech-bubble.gif) no-repeat bottom left;margin-top:58px;height:30px;width:30px;position:absolute;margin-left:0px;" class="tail">&nbsp;</div><p style="margin:0;padding:0;height:20px;font-size:10px"></p><p style="margin:0;padding:0;font-size:10px">[click to edit]</p><p style="margin:0;padding:0;font-size:10px"></p></div>');
        //if (tinymce.isIE)
          return ed.selection.collapse(ed.dom.select('#sbw')[0],false);

       
        
   }


});






    jQuery(ed.dom.select('img')).each(function(){
       var len = jQuery(this).parents("div.speech-bubble-wrapper").length;
        if (len==0) {
       var src = jQuery(this).attr("src");
       var width = jQuery(this).attr("width");
       var height = jQuery(this).attr("height");
      // jQuery(this).css({"z-index":"1"})
      
       jQuery(this).wrap('<div id="sbw" class="speech-bubble-wrapper" style="width:'+width+'px; height:'+height+'px;z-index:999998;"></div>');
      // jQuery(this).parent().after('<a href="http://www.vannybean.com/" style="font-size:10px;color:#999999">Baby Bubble By VannyBean</a>');
        }

    })



                                /*
                                ed.windowManager.open({
					file : url + '/dialog.htm',
					width : 320 + ed.getLang('SpeechBubble.delta_width', 0),
					height : 120 + ed.getLang('SpeechBubble.delta_height', 0),
					inline : 1
				}, {
					plugin_url : url, // Plugin absolute URL
					some_custom_arg : 'custom arg' // Custom argument
				});
                            */
                            
			});


			// Register example button
			ed.addButton('SpeechBubble', {
				title : 'Speech Bubble',
				cmd : 'mceSpeechBubble',
				image : url + '/img/bubble-small.gif'

			});

			// Add a node change handler, selects the button in the UI when a image is selected
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('SpeechBubble', n.nodeName == 'IMG');
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
				longname : 'SpeechBubble plugin',
				author : 'James Charlesworth',
				authorurl : 'http://www.vannybean.com',
				infourl : 'http://www.vannybean.com',
				version : "1.0"
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('SpeechBubble', tinymce.plugins.SpeechBubblePlugin);
})();

