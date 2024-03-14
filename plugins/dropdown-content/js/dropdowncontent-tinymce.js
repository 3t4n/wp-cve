(function() {
	tinymce.create('tinymce.plugins.dropdowncontent', {
		init : function(ed, url) {
			ed.addButton('dropdowncontent', {
				title : ed.getLang('dropdowncontent.insertadropdown'),
				tooltip : ed.getLang('dropdowncontent.insertacontentdropdown'),
				cmd : 'dropdowncontent',
				icon: 'dropdowncontent-icon'
			});
			ed.addCommand('dropdowncontent', function() {
				ed.windowManager.open({
					title: ed.getLang('dropdowncontent.insertadropdown'),
					body: [
						{
							type: 'textbox',
							name: 'control_name',
							label: ed.getLang('dropdowncontent.selectelementname'),
							placeholder: 'dropdown-content'
						},
						{
							type: 'textbox',
							name: 'custom_class',
							label: ed.getLang('dropdowncontent.customcssclassnames')
						},
						{
							type: 'container',
							html: ed.getLang('dropdowncontent.rememberdefault')
						}
					],
					onsubmit: function (e) {
						var row_class = [];
						var control_name = e.data.control_name;

						if ('' != control_name) {
							control_name = " name=\"" + control_name + "\"";
						}

						if (typeof e.data.custom_class !== 'undefined') {
							row_class.push(e.data.custom_class);
						}

						row_class = row_class.join(' ');
						if ('' != row_class) {
							row_class = " class=\"" + row_class + "\"";
						}

						ed.insertContent('[dropdown' + control_name + row_class + '][dropdown-option]Select one...[/dropdown-option][dropdown-option value="option1"]First option[/dropdown-option][dropdown-option value="option2"]Second Option[/dropdown-option][/dropdown]<br>[dropdown-content' + control_name + ' value="option1"]Content for the first option.[/dropdown-content]<br>[dropdown-content' + control_name + ' value="option2"]Content for the second option.[/dropdown-content]<br>');
					}
				});
			});
		},
		getInfo : function() {
			return {
				longname : 'Dropdown Content Plugin',
				author : 'Metaloha',
				authorurl : 'https://metaloha.com',
				infourl : 'https://github.com/metaloha/dropdown-content',
				version : "1.0.2"
			};
		}
	});

	tinymce.PluginManager.add( 'dropdowncontent', tinymce.plugins.dropdowncontent );
})();
