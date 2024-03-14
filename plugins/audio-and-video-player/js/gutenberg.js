jQuery(function(){
	( function( blocks, element ) {
		var el 					= element.createElement,
			InspectorControls  	= ('blockEditor' in wp) ? wp.blockEditor.InspectorControls : wp.editor.InspectorControls,
			MediaUpload			= ('blockEditor' in wp) ? wp.blockEditor.MediaUpload : wp.editor.MediaUpload;

		/* Plugin Category */
		blocks.getCategories().push({slug: 'cpmp', title: 'Audio and Video Player'});

		/* ICONS */
		const iconCPMP_gallery = el('img', { width: 20, height: 20, src:  "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAV/QAAFf0BzXBRYQAAABZ0RVh0Q3JlYXRpb24gVGltZQAxMS8xMi8xOPSptAIAAAAcdEVYdFNvZnR3YXJlAEFkb2JlIEZpcmV3b3JrcyBDUzbovLKMAAAAg0lEQVQ4jeWUwQ2AIAxFW8IsbKfOItvpMNYTipGWVjEe6JGfPH6aBwjDQtBwXEvYJ0BfOqQYzCAcVx6YQu3kBTwXWJsVgW+a3YAUA5BSIET+8hNIAG7SNdxmfjWiNk92WvXQClWJbYH+8/RqOuV5FWh1UwRamqU5PESU/bqAUMj6+2B3U1goCDJYkC8AAAAASUVORK5CYII=" } );

		const iconCPMP_audio = el('img', { width: 20, height: 20, src:  "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAV/QAAFf0BzXBRYQAAABZ0RVh0Q3JlYXRpb24gVGltZQAxMS8xMi8xOPSptAIAAAAcdEVYdFNvZnR3YXJlAEFkb2JlIEZpcmV3b3JrcyBDUzbovLKMAAAAvklEQVQ4jWNkyL35n4GKgImahg0eA/9PUqOugYx5t3AaStDAfAdBht8TVDFchstQnAZaK3EyPGtRYpgQJMrAwsSI1RBshmI1cH+uDMPhfFkGST4WDDl83mVgYGBA0YFPIS4As4Ax7xZuF1IChpiBjHm3GBjzbjEcuP2NaAOQww/DQBhwnPyEwbr/McOH7/8IGoDXhcjg2P3vDILldxjad79j+PPvP1bDsBnOSG7xhculZBcOuLxNtgtxgcGfDgEQzU4dGpHbMAAAAABJRU5ErkJggg==" } );

		const iconCPMP_video = el('img', { width: 20, height: 20, src:  "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAV/QAAFf0BzXBRYQAAABZ0RVh0Q3JlYXRpb24gVGltZQAxMS8xMi8xOPSptAIAAAAcdEVYdFNvZnR3YXJlAEFkb2JlIEZpcmV3b3JrcyBDUzbovLKMAAAAYklEQVQ4jeWUsRHAMAgDRS6zZDs8TLbDyyiVK7BD4SI51NCIR1AgUCM26tgJ+wcQUCPUSNLVlaIeqNEllNbTWSKvA/K+0sDIWzDhGU2V1lPg4V0Ch+Ft9dnQ79+wYEKp92AfB1dxhqcdi5sAAAAASUVORK5CYII=" } );

		/* Shortcode generator */
		function shortcodeGenerator(attrs)
		{
            var str = '[cpm-player';
            for(var i in attrs)
            {
                str += ' '+i+'="'+(attrs[i]+'').replace(/"/g, '&quot;')+'"';
            }
            str += (('id' in attrs || 'dir' in attrs) ? ' /' : '')+']';
			return str;
		};

		function extractAttsFromShortcode(shortcode)
		{
            var parts = shortcode.match(/\[[^\]]*\]/),
                obj = {
                    'shortcode' : (parts) ? parts[0] : '',
                    'attributes': {}
                };

            if(obj.shortcode)
            {
                obj.shortcode.match(/[\w-]+=".+?"/g).forEach(function(attribute) {
                    attribute = attribute.match(/([\w-]+)="(.+?)"/);
                    obj.attributes[attribute[1]] = attribute[2];
                });
            }

            return obj;
		};

		function createNewPlayer(props, type)
		{
			var children	  = [],
				focus 	  	  = props.isSelected,
				base_opt_name = 'cpmp-skin-list-option-',
				shortcode     = props.attributes.shortcode || '',
				attrs		  = extractAttsFromShortcode(shortcode)['attributes'],
				skin		  = 'device-player-skin',
				width		  = 450,
				height		  = 300,
				autoplay	  = 'false',
				shuffle	      = 'false',
				playlist	  = 'true',
				playlist_download_links	  = 'false',
                dir           = '',
				iframe		  = '',
				skins_options = [];

			/* Extract the current skin selected */
			if(attrs)
			{
				skin 	 = attrs[ 'skin' ] || skin;
				width  	 = ('width' in attrs) ? attrs['width'] : width;
				height 	 = ('height' in attrs) ? attrs['height'] : height;
				playlist = ('playlist' in attrs) ? attrs['playlist'] : playlist;
				playlist_download_links = ('playlist_download_links' in attrs) ? attrs['playlist_download_links'] : playlist_download_links;
				autoplay = ('autoplay' in attrs) ? attrs['autoplay'] : autoplay;
				shuffle  = ('shuffle' in attrs) ? attrs['shuffle'] : shuffle;
                dir      = ('dir' in attrs) ? attrs['dir'] : dir;
                iframe   = ('iframe' in attrs) ? attrs['iframe'] : iframe;
			}

			/* Populate the skins list if it has not been populated previously */
			skins_options.push(el('option',{key: base_opt_name+0, value: ''}, 'Select a skin'));
			if(
				typeof cpmp_insert_media_player != 'undefined' &&
				typeof cpmp_insert_media_player['skins'] != 'undefined'
			)
			{
				jQuery('<span>'+cpmp_insert_media_player.skins+'</span>')
				.find('option')
				.each(
					function()
					{
						var e = jQuery(this),
							v = e.val(),
							t = e.text(),
							o = {key:base_opt_name+v, value:v};

						skins_options.push(el('option', o, t));
					}
				);
			}

			if(props.attributes.shortcode.length == 0  || props.attributes.tmp != '')
			{
				children.push(
					el(
						MediaUpload,
						{
							id 			: 'cpmp-mediaupload',
							key			: 'cpmp-mediaupload',
							title		: 'Select the '+type+' files',
							allowedTypes: type,
							multiple	: true,
							onSelect	: function(data)
							{
								var player = "",
									playlist = "\n";

								if(data.length)
								{
                                    for(var i in data)
									{
										var fileObj = data[i],
											url 	= fileObj.url,
											name 	= '';

										if(('title' in fileObj) && fileObj['title'].length) name = fileObj['title'];
										else if(('description' in fileObj) && fileObj['description'].length) name = fileObj['description'];
										else name = fileObj['filename'];
										playlist += "[cpm-item file=\""+url+"\"]"+name+"[/cpm-item]\n";
									}
								}
								player = '[cpm-player skin="device-player-skin" width="450" playlist="true" autoplay="false" shuffle="false" type="'+type+'"]'+playlist+'[/cpm-player]';
								props.setAttributes({shortcode:player.replace(/[\r\n]/g, '')});
							},
							render  	: function(obj)
							{
								return el(
									'button',
									{
										onClick: obj.open,
										class: "button-secondary",
										style:{fontSize:'18px'}
									},
									'Open Media Library'
								);
							}
						}
					),
					el(
						'div',
						{
                            key: 'cpmp_dir_label',
                            style:{fontStyle:'italic', paddingTop:'10px', paddingBottom:'10px'}
                        },
						' - or - Enter the name of the subdirectory inside the "/ wp-content / Uploads /" directory containing the media files and press "enter":'
					),
                    el('input',
						{
							type: 'text',
							key: 'cpmp_dir',
							value: dir,
                            style:{width:'100%'},
                            onKeyDown: function(evt){
                                props.setAttributes({tmp:(new Date()).valueOf()});
                                if(evt && evt.keyCode == 13){props.setAttributes({tmp:''});}
                                return true;
                            },
							onChange: function(evt){
                                var shortcode = props.attributes.shortcode,
                                    obj = extractAttsFromShortcode(shortcode);

                                obj['attributes']['dir'] = evt.target.value.replace(/"/g, '');
                                obj['attributes']['type'] = type;
                                obj['attributes']['width'] = 450;
                                if(type == 'video') obj['attributes']['height'] = 300;
                                obj['attributes']['autoplay'] = 'false';
                                obj['attributes']['shuffle']  = 'false';
                                obj['attributes']['playlist'] = 'true';

                                shortcode = shortcode.replace(obj.shortcode, shortcodeGenerator(obj['attributes'])).replace(/[\r\n]/g, '');

                                props.setAttributes({shortcode: shortcode});
							}
						}
                    )
				);
			}
			else
			{
				children.push(
					el(
						'textarea',
						{
							key		: 'cpmp-shortcode',
							style	: {width: '100%'},
							value	: props.attributes.shortcode,
							onChange : function(evt)
							{
								props.setAttributes({ shortcode : evt.target.value.replace(/[\r\n]/g, '') });
							}
						}
					)
				);

				children.push(
					el(
						'div', {className: 'cpmp-iframe-container', key:'cpmp_iframe_container'},
						el('div', {className: 'cpmp-iframe-overlay', key:'cpmp_iframe_overlay'}),
						el('iframe',
							{
								key: 'cpmp_iframe',
								src: cpmp_gutenberg_editor_config.url+encodeURIComponent(props.attributes.shortcode.replace(/width\s*=\s*['"][^'"]*['"]/i,'')),
								height: 0,
								width: 500,
								scrolling: 'no'
							}
						)
					)
				);
			}

			if(!!focus)
			{
				children.push(
					el(
						InspectorControls,
						{key: 'cpmp-inspector'},
                        el(
                        	'div',
                            {
                                key: 'cp_inspector_container',
                                style:{paddingLeft:'20px',paddingRight:'20px'}
                            },
                            [
                                el('div', {className: 'cpmp-inspector-container', key: 'cpmp-inspector-container'},
                                    el('p', {key : 'cpmp-label'}, 'Select Skin'),
                                    el('select',
                                        {
                                            key: 'cpmp-skins-list',
                                            style:{width:'100%'},
                                            onChange: function(evt)
                                            {
                                                var shortcode = props.attributes.shortcode,
                                                    obj = extractAttsFromShortcode(shortcode);

                                                obj['attributes']['skin'] = evt.target.value;

                                                shortcode = shortcode.replace(obj.shortcode, shortcodeGenerator(obj['attributes'])).replace(/[\r\n]/g, '');

                                                props.setAttributes({shortcode: shortcode});
                                            },
                                            value : skin
                                        },
                                        skins_options
                                    ),
                                    el('p', {key: 'cpmp-width-label'}, 'Player width'),
                                    el('input',
                                        {
                                            key: 'cpmp-player-width',
                                            style:{width:'100%'},
                                            type: 'text',
                                            onChange: function(evt)
                                            {
                                                var shortcode = props.attributes.shortcode,
                                                    obj = extractAttsFromShortcode(shortcode);

                                                obj['attributes']['width'] = evt.target.value.replace(/^\s+/, '').replace(/\s+$/);

                                                shortcode = shortcode.replace(obj.shortcode, shortcodeGenerator(obj['attributes'])).replace(/[\r\n]/g, '');

                                                props.setAttributes({shortcode: shortcode});
                                            },
                                            value: width
                                        }
                                    ),
                                    (type == 'audio')
                                    ? null
                                    : (
                                    [
                                        el('p', {key: 'cpmp-height-label'}, 'Player height'),
                                        el('input',
                                            {
                                                key: 'cpmp-player-height',
                                                style:{width:'100%'},
                                                type: 'text',
                                                onChange: function(evt)
                                                {
                                                    var shortcode = props.attributes.shortcode,
                                                    obj = extractAttsFromShortcode(shortcode);

                                                    obj['attributes']['height'] = evt.target.value.replace(/^\s+/, '').replace(/\s+$/);

                                                    shortcode = shortcode.replace(obj.shortcode, shortcodeGenerator(obj['attributes'])).replace(/[\r\n]/g, '');

                                                    props.setAttributes({shortcode: shortcode});
                                                },
                                                value: height
                                            }
                                        )
                                    ]
                                    ),
                                    el( 'p', {key: 'cpmp-separator'}),
                                    el(
                                        'input',
                                        {
                                            type 	: 'checkbox',
                                            key 	: 'cpmp-player-playlist',
                                            checked	: (playlist == 1 || playlist == 'true'),
                                            onChange: function(evt){
                                                var shortcode = props.attributes.shortcode,
                                                    obj = extractAttsFromShortcode(shortcode);

                                                obj['attributes']['playlist'] = evt.target.checked ? 'true' : 'false';

                                                shortcode = shortcode.replace(obj.shortcode, shortcodeGenerator(obj['attributes'])).replace(/[\r\n]/g, '');

                                                props.setAttributes({shortcode: shortcode});
                                            },
                                        },
                                    ),
                                    el(
                                        'label',
                                        {
                                            key : 'cpmp-player-playlist-label',
                                        },
                                        'Show playlist'
                                    ),
                                    el( 'p', {key: 'cpmp-separator-1'}),
                                    el(
                                        'input',
                                        {
                                            type 	: 'checkbox',
                                            key 	: 'cpmp-player-playlist-download',
                                            checked	: (playlist_download_links == 1 || playlist_download_links == 'true'),
                                            onChange: function(evt){
                                                var shortcode = props.attributes.shortcode,
                                                    obj = extractAttsFromShortcode(shortcode);

                                                obj['attributes']['playlist_download_links'] = evt.target.checked ? 'true' : 'false';

                                                shortcode = shortcode.replace(obj.shortcode, shortcodeGenerator(obj['attributes'])).replace(/[\r\n]/g, '');

                                                props.setAttributes({shortcode: shortcode});
                                            },
                                        },
                                    ),
                                    el(
                                        'label',
                                        {
                                            key : 'cpmp-player-playlist-download-label',
                                        },
                                        'Include download links in the playlist'
                                    ),
                                    el( 'p', {key: 'cpmp-separator-2'}),
                                    el(
                                        'input',
                                        {
                                            type 	: 'checkbox',
                                            key 	: 'cpmp-player-shuffle',
                                            checked	: (shuffle == 1 || shuffle == 'true'),
                                            onChange: function(evt){
                                                var shortcode = props.attributes.shortcode,
                                                    obj = extractAttsFromShortcode(shortcode);

                                                obj['attributes']['shuffle'] = evt.target.checked ? 'true' : 'false';

                                                shortcode = shortcode.replace(obj.shortcode, shortcodeGenerator(obj['attributes'])).replace(/[\r\n]/g, '');

                                                props.setAttributes({shortcode: shortcode});
                                            },
                                        },
                                    ),
                                    el(
                                        'label',
                                        {
                                            key : 'cpmp-player-shuffle-label',
                                        },
                                        'Shuffle'
                                    ),
                                    el( 'p', {key: 'cpmp-separator-3'}),
                                    el(
                                        'input',
                                        {
                                            type 	: 'checkbox',
                                            key 	: 'cpmp-player-autoplay',
                                            checked	: (autoplay == 1 || autoplay == 'true'),
                                            onChange: function(evt){
                                                var shortcode = props.attributes.shortcode,
                                                    obj = extractAttsFromShortcode(shortcode);

                                                obj['attributes']['autoplay'] = evt.target.checked ? 'true' : 'false';

                                                shortcode = shortcode.replace(obj.shortcode, shortcodeGenerator(obj['attributes'])).replace(/[\r\n]/g, '');

                                                props.setAttributes({shortcode: shortcode});
                                            },
                                        },
                                    ),
                                    el(
                                        'label',
                                        {
                                            key : 'cpmp-player-autoplay-label',
                                        },
                                        'Autoplay (Some browsers do not support autoplay)'
                                    ),
                                    el( 'p', {key: 'cpmp-separator-4'}),
									el(
										'div',
										{
											key : 'cpmp-iframe-container'
										},
										el(
											'input',
											{
												type: 'checkbox',
												key: 'cpmp-iframe',
												checked: (iframe == 1 || iframe == 'true'),
												onChange: function(evt){
													var shortcode = props.attributes.shortcode,
                                                    obj = extractAttsFromShortcode(shortcode);
													if(evt.target.checked)
														obj['attributes']['iframe'] = 1;
													else if ( 'iframe' in obj['attributes'] ) delete obj['attributes']['iframe'];

													shortcode = shortcode.replace(obj.shortcode, shortcodeGenerator(obj['attributes'])).replace(/[\r\n]/g, '');

													props.setAttributes({shortcode: shortcode});
												}
											}
										),
										el(
											'span',
											{
												key: 'cpcff_iframe_label'
											},
											'Isolate player in iframe'
										)
									),
                                    el( 'p', {key: 'cpmp-separator-5'}),
                                    el(
                                        'div',
                                        {
                                            key : 'cpmp-link-container'
                                        },
                                        el(
                                            'a',
                                            {
                                                key : 'cpmp-create-player',
                                                target:'_blank',
                                                href: 'options-general.php?page=codepeople-media-player.php'
                                            },
                                            'Go to the players gallery'
                                        )
                                    ),
                                    el(
                                        'p',
                                        {
                                            key : 'cpmp-player-warning'
                                        },
                                        'Some settings are applied only to the public player. Please, save the page modifications and visit the public page.'
                                    )
                                )
                            ]
                        )
					)
				);
			}
			return children;
		};

		/* Create new Audio Player */
		blocks.registerBlockType( 'cpmp/new-audio-player', {
			title: 'New Audio Player',
			icon: iconCPMP_audio,
			category: 'cpmp',
			supports: {
				customClassName	: false,
				className		: false,
				html			: false
			},
			attributes: {
				shortcode : {
					type 	: 'string',
					default : ''
				},
                tmp : {
					type 	: 'string',
					default : ''
				}
			},

			edit: function( props ) {
				return createNewPlayer(props, 'audio');
			},

			save: function( props ) {
				return el(element.RawHTML, null, props.attributes.shortcode);
			}
		});

		/* Create new Video Player */
		blocks.registerBlockType( 'cpmp/new-video-player', {
			title: 'New Video Player',
			icon: iconCPMP_video,
			category: 'cpmp',
			supports: {
				customClassName	: false,
				className		: false,
				html			: false
			},
			attributes: {
				shortcode : {
					type 	: 'string',
					default : ''
				},
                tmp : {
					type 	: 'string',
					default : ''
				}
			},

			edit: function( props ) {
				return createNewPlayer(props, 'video');
			},

			save: function( props ) {
				return el(element.RawHTML, null, props.attributes.shortcode);
			}
		});

		/* Insert Player From Players Gallery */
		blocks.registerBlockType( 'cpmp/from-gallery', {
			title: 'Insert Player From Gallery',
			icon: iconCPMP_gallery,
			category: 'cpmp',
			supports: {
				customClassName	: false,
				className		: false,
				html			: false
			},
			attributes: {
				id : {
					type : 'string',
					default : ''
				},
				iframe : {
					type : 'string',
					default : ''
				}
			},

			edit: function( props ) {
				var children 	  = [],
					focus 	  	  = props.isSelected,
					base_opt_name = 'cpmp-list-option-',
					ids_options	  = [],
					id   	  	  = props.attributes.id || '',
					iframe   	  = props.attributes.iframe || '',
					shortcode_attrs = { id: id};

				if ( iframe != '' ) shortcode_attrs['iframe'] = iframe;

				/* Populate the options list if it has not been populated previously */
				ids_options.push(el('option',{key: base_opt_name+0, value: ''}, 'Select a player'));
				if(
					typeof cpmp_insert_media_player != 'undefined' &&
					typeof cpmp_insert_media_player['tag'] != 'undefined'
				)
				{
					jQuery('<span>'+cpmp_insert_media_player.tag+'</span>')
					.find('option')
					.each(
						function()
						{
							var e = jQuery(this),
								v = e.val(),
								t = e.text(),
								o = {key:base_opt_name+v, value:v};

							if(typeof id == 'undefined') id = v;
							ids_options.push(el('option', o, t));
						}
					);
				}

				children.push(
					el(
						'textarea',
						{
							key		: 'cpmp-shortcode',
							type	: 'text',
							style	: { width: '100%'},
							value	: shortcodeGenerator(shortcode_attrs),
							onChange : function(evt)
							{
								var id = '',
									sc = wp.shortcode.next('codepeople-html5-media-player', evt.target.value);
								if(!sc) sc = wp.shortcode.next('cpm-player', evt.target.value);
								if(sc) id = sc.shortcode.attrs.named[ 'id' ] || '';
								props.setAttributes({ id : id });
							}
						}
					)
				);

				children.push(
					el(
						'div', {className: 'cpmp-iframe-container', key: 'cpmp_iframe_container'},
						el('div', {className: 'cpmp-iframe-overlay', key: 'cpmp_iframe_overlay'}),
						el('iframe',
							{
								key: 'cpmp_iframe',
								src: cpmp_gutenberg_editor_config.url+encodeURIComponent(shortcodeGenerator(shortcode_attrs)),
								height: 0,
								width: 500,
								scrolling: 'no'
							}
						)
					)
				);

				if(!!focus)
				{
					children.push(
						el(
							InspectorControls,
							{key: 'cpmp-inspector'},
							el(
                                'div',
                                {
                                    key: 'cp_inspector_container',
                                    style:{paddingLeft:'20px',paddingRight:'20px'}
                                },
                                [
                                    el('div', {className: 'cpmp-inspector-container', key: 'cpmp-inspector-container'},
                                        el('p', {key : 'cpmp-label'}, 'Select the Player'),
                                        el('select',
                                            {
                                                key: 'cpmp-list',
                                                style:{width:'100%'},
                                                onChange: function(evt){
                                                    props.setAttributes({id: evt.target.value});
                                                },
                                                value : id
                                            },
                                            ids_options
                                        ),
                                        el( 'p', {key: 'cpmp-separator'}),
                                        el(
                                            'div',
                                            {
                                                key : 'cpmp-link-container'
                                            },
                                            el(
                                                'a',
                                                {
                                                    key : 'cpmp-create-player',
                                                    href: 'options-general.php?page=codepeople-media-player.php'
                                                },
                                                'Create or edit players'
                                            )
                                        ),
										el( 'p', {key: 'cpmp-separator-2'}),
                                        el(
                                            'div',
                                            {
                                                key : 'cpmp-iframe-container'
                                            },
                                            el(
												'input',
												{
													type: 'checkbox',
													key: 'cpmp-iframe',
													checked: props.attributes.iframe * 1,
													onChange: function(evt){
														props.setAttributes({iframe: String( evt.target.checked ? 1 : '' ) });
													},

												}
											),
											el(
												'span',
												{
													key: 'cpcff_iframe_label'
												},
												'Isolate player in iframe'
											)
                                        )
                                    )
                                ]
                            )
						)
					);
				}
				return children;
			},

			save: function( props ) {
				var iframe = props.attributes.iframe || '',
					shortcode_attrs = { id: props.attributes.id || '' };

				if ( iframe != '' ) shortcode_attrs['iframe'] = iframe;
				return shortcodeGenerator(shortcode_attrs);
			}
		});

	} )(
		window.wp.blocks,
		window.wp.element
	);
});