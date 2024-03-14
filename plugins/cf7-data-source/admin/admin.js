//
(function($){

	var form_fields = {},
        variables = {},
        current_recordset,
		codemirror;

	function esc(v)
	{
		return v.replace(/</g, '&#60;')
				.replace(/>/g, '&#62;')
				.replace(/\[/g, '&#91;')
				.replace(/\]/g, '&#93;')
				.replace(/"/g, '&#34;');
	}

    function test_recordset()
    {
        var flag,
            url = document.location.href;

        for(var i in variables)
        {
            flag = true;
            while(flag)
            {
                current_recordset = current_recordset.replace(i, variables[i]);
                flag = (current_recordset.indexOf(i) != -1);
            }
        }

        $.ajax(
            {
                'url'       : url,
                'method'    : 'post',
                'dataType'  : 'json',
                'data'      : {'cf7-recordset-test' : current_recordset},
                'success'   : function(data){
                    try
                    {
                        alert(JSON.stringify(data));
                    }
                    catch(err){if('console' in window) console.log(err);}
                }
            }
        );
    }

    function extract_vars(str)
    {
        current_recordset = str;

        var f  = str.match(/\{field\.[^\}]+\}/ig, str),
            v  = str.match(/\{var\.[^\}]+\}/ig, str),
            b = [].concat(f==null?[]:f, v==null?[]:v),
            r  = '';

        if(b.length)
        {
            for(var i in b) r += '<tr><th>'+b[i]+'</th><td><input name="'+esc(b[i])+'" value="'+( b[i] in variables ? esc(variables[b[i]]) : '')+'" type="text" /></td></tr>';
        }

        if(r.length)
        {
            $(
                '<div class="cf7-recordset-test-frame">'+
                    '<div class="cf-recordset-test-variables-container">'+
                        '<h3>Enter variables for testing the recordset</h3>'+
                        '<table border="0" style="width:100%;">'+
                            r+
                            '<tr><th></th><td align="right"><button class="button-primary cf7-recordset-test-variables-apply">Apply</button></div><button class="button-secondary cf7-recordset-test-variables-close">Close</button></td></tr>'+
                        '</table>'+
                    '</div>'+
                '</div>'
            ).appendTo('body');
        }
        else test_recordset();
    }

	function populate_datalists()
	{
		var form_editor = $('textarea[id="wpcf7-form"]');

		if(form_editor.length)
		{
			try
			{
				var	form_structure = form_editor.val(),
					types = ['text','email','tel','url','textarea','number','range','date','checkbox','radio','select'],
					result = Array.from(form_structure.matchAll(new RegExp('\\[\\s*([^\\]]+)\\]', 'g'))),
					components,
					type,
					name,
					datalist_fields = $('datalist[id="cf7-field-name"]'),
					datalist_recordsets = $('datalist[id="cf7-recordset-id"]');

				form_fields = {};
				datalist_fields.html('');
				datalist_recordsets.html('');

				for(var i in result)
				{
                    try
                    {
                        components = result[i][1].replace(/\s+/g, ' ').split(/\s/);
                        type = components[0].replace(/\*/g, '').toLowerCase();
                        name = components[1].replace(/"/g, '');
                        if(types.indexOf(type) != -1)
                        {
                            form_fields[name] = type;
                            $('<option />').attr('value', name).appendTo(datalist_fields);
                        }
                        else if(type == 'cf7-recordset')
                        {
                            components = result[i][1].match(/\sid\s*=\s*['"]([^'"]+)['"]/);
                            if(components)
                                $('<option />').attr('value', components[1]).appendTo(datalist_recordsets);
                        }
                    }catch(err){continue;}
				}

			}
			catch(err)
			{
				if(typeof console != 'undefined') console.log(err);
			}
		}
	}

	function display_datasource_section(datasource)
	{
		$('[class*="cf7-datasource-"]:not(.cf7-datasource-link):not(.cf7-datasource-recordset)').hide();
		$('.cf7-datasource-'+datasource).show();
		if(datasource == 'database') display_database_attributes();
	}

	function display_database_attributes()
	{
		$('.cf7-datasource-dns,.cf7-datasource-components').hide();
		$('.cf7-datasource-'+$('[name="cf7-database-connection"]:checked').val()).show();
		if ( 'wp' in window && 'codeEditor' in wp && typeof codemirror == 'undefined' ) {
			var editorSettings = wp.codeEditor.defaultSettings ? _.clone(wp.codeEditor.defaultSettings) : {'codemirror':{}};
			if ( typeof cf7_datasource_admin_settings != 'undefined' ) {
				if ( ! 'codemirror' in editorSettings ) {
					editorSettings['codemirror'] = {};
				}
				editorSettings['codemirror']['mode'] = cf7_datasource_admin_settings['mode'];
				editorSettings['codemirror']['height'] = 200;
				editorSettings['codemirror']['parserfile'] = cf7_datasource_admin_settings['parserfile'];
				editorSettings['codemirror']['stylesheet'] = cf7_datasource_admin_settings['stylesheet'];
				editorSettings['codemirror']['textWrapping'] = cf7_datasource_admin_settings['textWrapping'];
			}
			codemirror = wp.codeEditor.initialize( $('[name="cf7-database-query"]')[0], editorSettings );
			codemirror.codemirror.on('change', function(e){ $('[name="cf7-database-query"]').val(e.getValue()).change();});
		}
	}

	function generate_recordset_shortcode()
	{
		var shortcode = '[cf7-recordset',
			p = '.cf7-datasource-recordset',
			cf7_recordset_id = String($('[id="tag-generator-panel-cf7-recordset-id"]', p).val()).trim(),
			cf7_datasource = String($('[name="cf7-datasource"]', p).val()).trim(),
			cf7_debugging = $('[name="cf7-debugging"]:checked', p).length,
			cf7_callback  = String($('[name="cf7-callback"]', p).val()).trim(),

			// User
			cf7_user_attributes = String($('[name="cf7-user-attributes"]', p).val()).trim(),
			cf7_user_logged = $('[name="cf7-user-logged"]:checked', p).length,
			cf7_user_condition = String($('[name="cf7-user-condition"]', p).val()).trim(),

			// Post
			cf7_post_attributes = String($('[name="cf7-post-attributes"]', p).val()).trim(),
			cf7_post_condition = String($('[name="cf7-post-condition"]', p).val()).trim(),
            cf7_post_current = $('[name="cf7-post-current"]:checked', p).length,

			// Client side
			cf7_client_function = String($('[name="cf7-client-function"]', p).val()).trim(),
			cf7_client_parameters = String($('[name="cf7-client-parameters"]', p).val()).trim(),

			// Taxonomy
			cf7_taxonomy_name = String($('[name="cf7-taxonomy-name"]', p).val()).trim(),
			cf7_taxonomy_attributes = String($('[name="cf7-taxonomy-attributes"]', p).val()).trim(),
			cf7_taxonomy_condition = String($('[name="cf7-taxonomy-condition"]', p).val()).trim(),
			cf7_taxonomy_posts = String($('[name="cf7-taxonomy-posts"]', p).val()).trim(),

			// Database
			cf7_database_connection = String($('[name="cf7-database-connection"]:checked', p).val()).trim(),
			cf7_database_dns = String($('[name="cf7-database-dns"]', p).val()).trim(),
			cf7_database_engine = String($('[name="cf7-database-engine"]', p).val()).trim(),
			cf7_database_hostname = String($('[name="cf7-database-hostname"]', p).val()).trim(),
			cf7_database_database = String($('[name="cf7-database-database"]', p).val()).trim(),
			cf7_database_username = String($('[name="cf7-database-username"]', p).val()).trim(),
			cf7_database_password = String($('[name="cf7-database-password"]', p).val()).trim(),
			cf7_database_query = String($('[name="cf7-database-query"]', p).val().replace(/[\r\n]/g, ' ')).trim(),

			// CSV
			cf7_csv_url = String($('[name="cf7-csv-url"]', p).val()).trim(),
			cf7_csv_headline = $('[name="cf7-csv-headline"]:checked', p).length,
			cf7_csv_delimiter = String($('[name="cf7-csv-delimiter"]', p).val()).trim(),

			// JSON
			cf7_json_url = String($('[name="cf7-json-url"]', p).val()).trim();

		shortcode += ' id="'+esc((cf7_recordset_id != '') ? cf7_recordset_id : 'recordset'+(new Date()).valueOf())+'"';
		shortcode += ' type="'+cf7_datasource+'"';

		if(cf7_debugging) shortcode += ' debug="1"';
		if(cf7_callback.length) shortcode += ' callback="'+esc(cf7_callback)+'"';

		switch(cf7_datasource)
		{
			case 'user':
				if(cf7_user_attributes != '') shortcode += ' attributes="'+esc(cf7_user_attributes)+'"';
				if(cf7_user_logged) shortcode += ' logged="1"';
				if(cf7_user_condition != '') shortcode += ' condition="'+esc(cf7_user_condition)+'"';
			break;

			case 'post':
				if(cf7_post_attributes != '') shortcode += ' attributes="'+esc(cf7_post_attributes)+'"';
				if(cf7_post_condition != '')
                {
                    shortcode += ' condition="('+esc(cf7_post_condition);
                    if(cf7_post_current) shortcode += ') AND ID={post.id}';
                    else  shortcode += ')';
                    shortcode += '"';
                }
                else if(cf7_post_current) shortcode += ' condition="ID={post.id}"';
			break;

			case 'client':
				if(cf7_client_function != '') shortcode += ' function="'+esc(cf7_client_function)+'"';
				if(cf7_client_parameters != '') shortcode += ' parameters="'+esc(cf7_client_parameters)+'"';
            break;

			case 'taxonomy':
				if(cf7_taxonomy_name != '') shortcode += ' taxonomy="'+esc(cf7_taxonomy_name)+'"';
				if(cf7_taxonomy_attributes != '') shortcode += ' attributes="'+esc(cf7_taxonomy_attributes)+'"';
				if(cf7_taxonomy_condition != '') shortcode += ' condition="'+esc(cf7_taxonomy_condition)+'"';
				if(cf7_taxonomy_posts != '') shortcode += ' in="'+esc(cf7_taxonomy_posts)+'"';
			break;

			case 'database':
				if(cf7_database_connection == 'dns')
				{
					if(cf7_database_dns != '') shortcode += ' dns="'+esc(cf7_database_dns)+'"';
				}
				else if(cf7_database_connection == 'components')
				{
					if(cf7_database_engine != '') shortcode += ' engine="'+esc(cf7_database_engine)+'"';
					if(cf7_database_hostname != '') shortcode += ' hostname="'+esc(cf7_database_hostname)+'"';
					if(cf7_database_database != '') shortcode += ' database="'+esc(cf7_database_database)+'"';
				}
                if(cf7_database_connection != 'website')
                {
                    if(cf7_database_username != '') shortcode += ' username="'+esc(cf7_database_username)+'"';
                    if(cf7_database_password != '') shortcode += ' password="'+esc(cf7_database_password)+'"';
                }
				if(cf7_database_query != '') shortcode += ' query="'+esc(cf7_database_query)+'"';
			break;

			case 'csv':
				if(cf7_csv_url != '') shortcode += ' url="'+esc(cf7_csv_url)+'"';
				if(cf7_csv_headline) shortcode += ' headline="1"';
				if(cf7_csv_delimiter != '') shortcode += ' delimiter="'+esc(cf7_csv_delimiter)+'"';
			break;

			case 'json':
				if(cf7_json_url != '') shortcode += ' url="'+esc(cf7_json_url)+'"';
			break;
		}
		shortcode += ']';

		setTimeout( function(){ $('[name="cf7-recordset"]').val(shortcode); }, 50 );
	}

	function generate_link_shortcode()
	{
		var shortcode = '[cf7-link-field',
			p = '.cf7-datasource-link',
			cf7_recordset_id = String($('[name="cf7-recordset-id"]', p).val()).trim(),
			cf7_field_name = String($('[name="cf7-field-name"]', p).val()).trim(),
			cf7_keep_options = $('[name="cf7-field-keep-options"]', p).is(':checked') ? 1 : 0,
			cf7_attribute_value = String($('[name="cf7-attribute-value"]', p).val()).trim(),
			cf7_attribute_text  = String($('[name="cf7-attribute-text"]', p).val()).trim(),
			cf7_attribute_extra = String($('[name="cf7-attribute-extra"]', p).val()).trim(),
			cf7_limit = String($('[name="cf7-limit"]', p).val()).trim(),
			cf7_condition = String($('[name="cf7-condition"]', p).val()).trim();

		shortcode += ' recordset="'+esc(cf7_recordset_id)+'"';
		shortcode += ' field="'+esc(cf7_field_name)+'"';
		shortcode += ' value="'+esc(cf7_attribute_value)+'"';

		if(cf7_attribute_text != '') shortcode += ' text="'+esc(cf7_attribute_text)+'"';
		if(cf7_attribute_extra != '') shortcode += ' other-attributes="'+esc(cf7_attribute_extra)+'"';
		if(cf7_condition != '') shortcode += ' condition="'+esc(cf7_condition)+'"';
		if(
            cf7_limit != '' &&
            !isNaN(parseInt(cf7_limit)) &&
            parseInt(cf7_limit)
        ) shortcode += ' limit="'+parseInt(cf7_limit)+'"';

		if(cf7_keep_options) shortcode += ' keep-options';

		shortcode += ']';
		$('[name="cf7-link-field"]').val(shortcode);
	}

	$(document).on('change', '[name="cf7-datasource"]',  function(){
		display_datasource_section($(this).val());
	});

	$(document).on('change', '[name="cf7-database-connection"]',  display_database_attributes);
	$(document).on('change keyup', '.cf7-datasource-recordset :input', generate_recordset_shortcode);
	$(document).on('change keyup', '.cf7-datasource-link :input', generate_link_shortcode);
	$(document).on('change keyup', '[name="cf7-field-name"]', function(){
		var cf7_field_name = String(this.value).trim(),
			without_text_types = ['text','email','tel','url','textarea','number','range','date'],
			to_show_hide = $('[name="cf7-attribute-text"],[name="cf7-limit"]').closest('tr');

		to_show_hide.show();
		if(cf7_field_name in form_fields && without_text_types.indexOf(form_fields[cf7_field_name]) != -1)
			to_show_hide.hide();
	});
	$(document).on('mousedown', '[href*="cf7-link-field"]', function(){
		populate_datalists();
	});
    $(document).on('click', '.cf7-recordset-test', function(){
        var recordset = $('[name="cf7-recordset"]').val(),
            data = {'cf7-recordset' : recordset, 'cf7-datasource-action' : 'cf7-recordset-test'};
        extract_vars(recordset);
    });
    $(document).on('click', '.cf7-recordset-test-variables-close', function(){$(this).closest('.cf7-recordset-test-frame').remove();});
    $(document).on('click', '.cf7-recordset-test-variables-apply', function(){
        $('.cf-recordset-test-variables-container [type="text"]').each(function(){
            variables[this.name] = this.value;
        });
        test_recordset();
        $('.cf7-recordset-test-variables-close').trigger('click');
    });

    // Insert the recordset field, opens the recordset-link dialog, and populates the id of recordset field
    $(document).on(
        'mousedown',
        '[data-id="cf7-recordset"] button.insert-tag,[data-id="cf7-recordset"] input[type="button"].insert-tag',
        function()
        {
			$('[name="cf7-recordset"]').tigger('focus');
            // Global variable with the recordset id
            cf7_recordset_id = jQuery('[name="name"]:visible').val();
        }
    );

	$(document).on(
        'mouseup',
        '[data-id="cf7-recordset"] button.insert-tag,[data-id="cf7-recordset"] input[type="button"].insert-tag',
        function()
        {
            // Waits until the recorset panel closes
            setTimeout(function(){
                try
                {
                    $('#tag-generator-list').find('[href*="cf7-link-field"]').trigger('mousedown').trigger('click');
                    // Waits until the link panel opens
                    setTimeout(function(){
                        try
                        {
							if(typeof cf7_recordset_id != 'undefined')
								$('[name="cf7-recordset-id"]').val(cf7_recordset_id);
                        }
                        catch(err){}
                    },500);
                }
                catch(err){}
            }, 2000);
        }
    );

	$(document).on(
		'click',
		'.cf7-ds-predefined-query',
		function()
		{
			codemirror.codemirror.getDoc().setValue( $(this).attr('data-query') );
		}
	);
	// Update datalists
	$(document).on(
		'input',
		'.cf7-datasource-recordset [list]',
		function()
		{
			let e = $(this),
				v = new String(e.val()).split(' '),
				l = $( '#'+e.attr('list') ),
				n = v.length ? v.pop() : '';

			l.find('option').each(function(){
				let o = $(this), t = o.attr('data-value'), s;

				if(typeof t == 'undefined') {
					t = o.attr('value');
					o.attr('data-value', t);
				}
				if(n.length == 0 || t.toLowerCase().indexOf(n.toLowerCase()) == 0){
					s = String(v.join(' ')).trim();
					o.attr('value', s+(s.length ? ' ' : '')+t);
				}
				if(o.attr('value') != t) {
					o.text('...'+t);
				} else {
					o.text('');
				}
			});
		}
	);
})(jQuery)