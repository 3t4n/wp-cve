(function () {
	tinymce.PluginManager.add('formstack', function (ed, url) {
		ed.addButton('formstack', {
			title  : formstack_tinymce.button,
			type   : 'button',
			image  : url + '/stack.gif',
			onclick: function () {
				var forms = getValues();
				var bodies = [];
				if ('no_app' == forms[0].text || 'no_forms' == forms[0].text) {
					bodies.push({
						type: 'container',
						name: 'container',
						html: '<p class="formstack_tinymce_link">' + forms[0].value + '</p>'
					});
				} else {
					bodies.push({
						type  : 'listbox',
						name  : 'lists',
						label : formstack_tinymce.list_label,
						values: forms,
					});
					bodies.push({
						type : 'checkbox',
						name : 'nojquery',
						text : formstack_tinymce.nojquery
					});
					bodies.push({
						type : 'checkbox',
						name : 'nojqueryui',
						text: formstack_tinymce.nojqueryui
					});
					bodies.push({
						type: 'checkbox',
						name: 'nomodernizr',
						text: formstack_tinymce.nomodernizr
					});
					bodies.push({
						type: 'checkbox',
						name: 'nocss',
						text: formstack_tinymce.nocss
					});
					bodies.push({
						type: 'checkbox',
						name: 'nocssstrict',
						text: formstack_tinymce.nocssstrict
					});
					bodies.push({
						type: 'container',
						name: 'container',
						html: '<p class="formstack_tinymce_link">'+formstack_tinymce.clear_cache+'</p>'
					});
				}
				ed.windowManager.open({
					title   : formstack_tinymce.tinymce_title,
					body    : bodies,
					onsubmit: function (e) {
						if (typeof e.data.lists === 'undefined') {
							return;
						}
						var list = e.data.lists;
						var id, viewkey;
						var nojquery = '', nojqueryui = '', nomodernizr = '', nocss = '', nocssstrict = '';
						id = list.split('-')[0];
						viewkey = list.split('-')[1];
						if (true === e.data.nojquery) { nojquery = 'nojquery="true"'; }
						if (true === e.data.nojqueryui) { nojqueryui = 'nojqueryui="true"'; }
						if (true === e.data.nomodernizr) { nomodernizr = 'nomodernizr="true"'; }
						if (true === e.data.nocss) { nocss = 'no_style="true"'; }
						if (true === e.data.nocssstrict) { nocssstrict = 'no_style_strict="true"'; }
						var atts = nojquery+' '+nojqueryui+' '+nomodernizr+' '+nocss+' '+nocssstrict;

						var tag = '[Formstack id="' + id + '" viewkey="' + viewkey + '" '+atts+']';
						ed.insertContent(tag);
					}
				});
			}
		});
		function getValues() {
			var options = [];
			jQuery.each(formstack_forms, function (i, val) {
				options.push({'text': val.name, 'value': val.value});
			});
			return options;
		}
	});
})()
