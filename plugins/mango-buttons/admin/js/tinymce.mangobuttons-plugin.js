(function() {
    tinymce.create('tinymce.plugins.mangobuttons', {
        /**
         * Initializes the plugin, this will be executed after the plugin has been created.
         * This call is done before the editor instance has finished it's initialization so use the onInit event
         * of the editor instance to intercept that event.
         *
         * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
         * @param {string} url Absolute URL to where the plugin is located.
         */
        init : function(editor, url) {
	
					/*return if < tinymce4 because bindings will throw errors & break tinymce*/
					if (tinymce.majorVersion < 4) {
						
						editor.addButton('mangobuttons', {
							title: 'Add Mango Button',
							cmd: 'mb_unsupported',
							image: url + './../images/TinyMCEButton-' + MB_JS_GLOBALS.ICON_COLOR + '.png'
						});
						
						editor.addCommand('mb_unsupported', function(){
							alert("Mango Buttons only work with WordPress 3.9 and above.  Please update your WordPress installation");
							return;
						});
						
						return;
					}
						
					function getSettingsFromButton(btn){
						var btnClasses = btn.attr('class').split(/\s+/);
						
						var settings = {};
						
						var rawText = btn.html();
						
						//"reverse" our conversion from icon name to icon html
						settings.text = rawText.replace(/&nbsp;<i class="fa fa-(.*?)"><\/i>&nbsp;/g, '{{$1}}');
						
						//in case nbsp elements were removed...
						settings.text = settings.text.replace(/<i class="fa fa-(.*?)"><\/i>/g, '{{$1}}');
						
						//remove any extra &nbsp; from text
						settings.text = settings.text.replace(/&nbsp;/g, '');
						
						settings.link = btn.attr('href');
						settings.new_tab = btn.attr('target') == '_blank' ? true : false;
						settings.color = btn.css('background-color');
						
						settings.style = jQuery.grep(btnClasses, function(btnClass){
							return btnClass.indexOf('mb-style-') > -1;
						})[0].split('mb-style-')[1];
						
						settings.size = jQuery.grep(btnClasses, function(btnClass){
							return btnClass.indexOf('mb-size-') > -1;
						})[0].split('mb-size-')[1];
						
						settings.corners = jQuery.grep(btnClasses, function(btnClass){
							return btnClass.indexOf('mb-corners-') > -1;
						})[0].split('mb-corners-')[1];
						
						settings.text_style = jQuery.grep(btnClasses, function(btnClass){
							return btnClass.indexOf('mb-text-style-') > -1;
						})[0].split('mb-text-style-')[1];
						
						return settings;
					}
					
					function insertButtonIntoEditor(settings){
						
						var element = '';
						
						element += '<a href="' + settings.link + '" ';
						
						if(settings.new_tab){
							element += 'target="_blank"';
						}
						
						element += ' class="';//end opening <a> tag
						
						//element += '<span class="';
						
						element += 'mb-button' + ' ';
						element += 'mb-style-' + settings.style + ' ';
						element += 'mb-size-' + settings.size + ' ';
						element += 'mb-corners-' + settings.corners + ' ';
						element += 'mb-text-style-' + settings.text_style + ' ';
						
						element += '"';//end class definition
						
						element += ' style="background-color:' + settings.color + ';"';//open & end style definition
						
						element += '>';//end opening <span tag
						
						element += settings.text.replace(/{{(.*?)}}/g, '&nbsp;<i class="fa fa-$1"></i>&nbsp;');//add button text
						
						element += '</a>';//end span & a tags (end of button element)
						
						var activeEditor = tinymce.activeEditor;
						
						activeEditor.execCommand('mceInsertContent', 0, element);
						
					}
					
 					editor.addButton('mangobuttons', {
						title: 'Add Mango Button',
						menu_button: true,
						cmd: 'mb_command',
						image: url + './../images/TinyMCEButton-' + MB_JS_GLOBALS.ICON_COLOR + '.png'
					});
					
					editor.addCommand('mb_command', function(){
						
						if (tinymce.majorVersion < 4) {
							alert("Mango Buttons only work with WordPress 3.9 and above.  Please update your WordPress installation");
							return;
						}
						
						var btn = editor.selection.getNode();
						
						if(jQuery(btn).hasClass('mb-button')){
							//editing existing button
							editor.selection.select(btn);
							
							var button = jQuery(btn);
							
							var settings = getSettingsFromButton(button);
							
							mb.show(settings);
						}
						else if(jQuery(btn).parents('.mb-button').length > 0){
							//edit existing button (parent/grandparent/etc. of selected item)
							editor.selection.select(jQuery(btn).parents('.mb-button')[0]);
							
							var button = jQuery(btn).parents('.mb-button').first();
							
							var settings = getSettingsFromButton(button);
							
							mb.show(settings);
						}
						else{
							//creating new button
							
							mb.show();
						}
						
					});
					
					editor.on('dblClick', function(e){
						
						var elem = e.target;
						
						if(jQuery(elem).hasClass('mb-button')){
							
							//select the entire element that was double clicked
							editor.selection.select(elem);
							
							var button = jQuery(elem);
							
							var settings = getSettingsFromButton(button);
							
							mb.show(settings);
						}
						else if(jQuery(elem).parents('.mb-button').length > 0){
							//edit existing button (parent/grandparent/etc. of selected item)
							editor.selection.select(jQuery(elem).parents('.mb-button')[0]);
							
							var button = jQuery(elem).parents('.mb-button').first();
							
							var settings = getSettingsFromButton(button);
							
							mb.show(settings);
						}
					});
					
					
					/*Initializ our MB modal*/
					if(typeof mb === 'undefined'){
						mb = new MBModal(insertButtonIntoEditor);
					}
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
                longname : 'Mango Buttons Plugin',
                author : 'Phil Baylog',
                authorurl : 'https://mangobuttons.com',
                infourl : 'https://mangobuttons.com',
                version : "0.1"
            };
        }
    });
 
    // Register plugin
    tinymce.PluginManager.add( 'mangobuttons', tinymce.plugins.mangobuttons );
})();