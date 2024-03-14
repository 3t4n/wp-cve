(function( $ ) {
		
	// Plugin namee
	const pn = 'jalbum-bridge'; 
	// Element create
	const el = wp.element.createElement;
	// Translate
	const { __ } = wp.i18n;
	// Register function
	const { registerBlockType } = wp.blocks;
	// Debug?
	const DEBUG = true;
	
	const
		// Caption placements
		placements = [
					{ value: 'left top', 		label: __('Left', 'jalbum-bridge') + ' ' + __('Top', 'jalbum-bridge') },
					{ value: 'center top', 		label: __('Center', 'jalbum-bridge') + ' ' + __('Top', 'jalbum-bridge') },
					{ value: 'right top', 		label: __('Right', 'jalbum-bridge') + ' ' + __('Top', 'jalbum-bridge') },
					{ value: 'center middle', 	label: __('Center', 'jalbum-bridge') + ' ' + __('Middle', 'jalbum-bridge') },
					{ value: 'left bottom', 	label: __('Left', 'jalbum-bridge') + ' ' + __('Bottom', 'jalbum-bridge') },
					{ value: 'center bottom', 	label: __('Center', 'jalbum-bridge') + ' ' + __('Bottom', 'jalbum-bridge') },
					{ value: 'right bottom', 	label: __('Right', 'jalbum-bridge') + ' ' + __('Bottom', 'jalbum-bridge') },
				],
		
		// Caption styles
		captionStyles = [
					{ value: 'white', 			label: __('White', 'jalbum-bridge') },
					{ value: 'light', 			label: __('Light', 'jalbum-bridge') },
					{ value: 'transparent', 	label: __('Transparent', 'jalbum-bridge') },
					{ value: 'dark', 			label: __('Dark', 'jalbum-bridge') },
					{ value: 'black', 			label: __('Black', 'jalbum-bridge') },
				],

		// Default timings
		defaultTimings = {
					'crossfade':				[ 1000, 2000 ],
					'zoom':						[ 1000, 2000 ],
					'kenburns':					[    0, 4000 ],
					'stack':					[ 1500, 1500 ],
					'slide':					[ 1500, 1500 ],
					'swap':						[ 2000, 1000 ],
					'carousel':					[ 2000, 1000 ],
					'flip':						[ 2000, 1000 ],
					'book':						[ 2000, 1000 ],
					'cube':						[ 2000, 1000 ],
					'coverflow':				[ 2000, 1000 ],
				},
				
		// Default aspect ratios
		defaultArs = {
					'slideshow':				'80',
					'slider':					'80',
					'grid':						'75',
					'masonry':					'100',
					'mosaic':					'100',
					'strip':					'25',
				},
		
		// Default layouts
		defaultLayouts = {
					'slideshow':				'12',
					'grid':						'grid-4-3',
					'masonry':					'17',
					'mosaic':					'mos-1-3',
					'strip':					'6',
				},
		
		// Projector defaults
		defaults = { 
					'albumurl':					'',							// Path to album root
					'folder':					'',							// Path to start folder
					'depth':					2,							// Folder level depth to explore
					'include':					'images',					// Include folders|images
					'ordering':					'original',					// Ordering type 
					'ar':						'80',						// Aspect ratio
					'disablelinks':				false,						// Disable links to album
					'opennew':					false,						// Open album in new window?
					'skipduplicates':			true,						// Skip duplicate file (names)
					'type':						'slideshow', 				// Projector type
					'transition':				'slide', 					// Transition type
					'slideshowdelay':			2000, 						// Pause lenght
					'transitionspeed':			1000,						// Transition length
					'layout':					'12',						// Dafault layouts
					'gap':						'none',						// Gap
					'titletemplate':			'', 
					'titleplacement':			'center top',
					'titlestyle':				'white',
					'captiontemplate':			'', 
					'captionplacement':			'center bottom',
					'captionstyle':				'dark',
				},
				
		// Texts to pass
		texts = {
					'error':					__('Error'),
					'databaseAccessDenied':		__('The album\'s database file "{0}" is missing or access is denied. If the album comes from an external site ensure Cross Origin Resource Sharing (CORS) is enabled!'),
					'noSuchFolder':				__('The given folder "{0}" does not exists, or its database file is missing!'),
					'missingLibrary':			__('Missing "{0}" library!'),
					'unknownError':				__('Unknown error: {0}'),
				},
		
		// Icons
		transitionIcon = 
				el('svg', { width: 20, height: 20 },
					el('path', {
						fill: '#333',
						d: 'M17 4V1L9 4H1v12h8l8 3v-3h2V4h-2zM9 15H2V5h7v10zm7 2.56l-6-2.25V4.69l6-2.25v15.12zM18 15h-1V5h1v10z',
					})
				),
				
		captionIcon = 
				el('svg', { width: 20, height: 20 },
					el('path', {
						fill: '#333',
						d: 'M2 2v16h16V2H2zm15 15H3V3h14v14zm-4-3H7v-1h6v1zm2 2H5v-1h10v1z',
					})
				),
				
		settingsIcon = 
				el('svg', { width: 20, height: 20 },
					el('path', {
						fill: '#333',
						d: 'M8.95 3a2.5 2.5 0 00-4.9 0H2v1h2.05a2.5 2.5 0 004.9 0H18V3H8.95zM6.5 5C5.67 5 5 4.33 5 3.5S5.67 2 6.5 2 8 2.67 8 3.5 7.33 5 6.5 5zM2 9v1h9.05a2.5 2.5 0 004.9 0H18V9h-2.05a2.5 2.5 0 00-4.9 0H2zm10 .5c0-.83.67-1.5 1.5-1.5s1.5.67 1.5 1.5-.67 1.5-1.5 1.5-1.5-.67-1.5-1.5zM8.95 15a2.5 2.5 0 00-4.9 0H2v1h2.05a2.5 2.5 0 004.9 0H18v-1H8.95zM6.5 17c-.83 0-1.5-.67-1.5-1.5S5.67 14 6.5 14s1.5.67 1.5 1.5S7.33 17 6.5 17z',
					})
				),
				
		jalbumBridgeIcon =
				el('svg', { width: 24, height: 24 },
					el('g', {},
						el('path', { 
							fill: '#96C346',
							d: 'M7.61 7.22s.76-.5 1.91-.95c1.16-.45 1.98-.78 1.98-.78s.35-1.65 1.58-2.58c1.23-.92 3.09-1.02 4.25.07 1.16 1.09 1.18 2.15 1.44 3.45.26 1.3 1.91.99 2.34 2.24.54 1.58.28 4.44-.85 7.13-.46 1.1-1.73 2.87-2.6 3.78-1.3 1.38-2.47 1.74-4.11 1.56-2.98-.33-4.35-.85-6.19-2.29-2.05-1.61-2.46-2.62-2.58-3.24-.12-.61.48-1.49-.76-2.24-1.6-.97-2.72-2.99-1.51-4.94 1.54-2.48 5.1-1.21 5.1-1.21z'
						}),
						el('path', {
							fill: '#FFF',
							d: 'M4.8 12.42c-.8.1-1.84-.72-2.01-2.15-.14-1.2.69-2.22 2.13-2.36 1.44-.14 2.17.52 2.22 1.11.05.59-.19 1.02-.69 1.77-.49.76-.9 1.54-1.65 1.63zM12.99 5.83c-.41-.7-.12-2.03 1.15-2.71.99-.53 2.42-.25 3.14 1.01.71 1.26.29 2.17-.23 2.46-.52.28-1.01.24-1.9.09-.89-.15-1.78-.2-2.16-.85z'
						}),
						el('path', {
							fill: '#333',
							d: 'M6.05 16.06c2.52-.26 4.22-2.04 6.64-2.62 2.9-.7 5.06-2.14 7.47-3.81-.65 3.32-2.12 6.38-5.09 8.21-2.93 1.81-6.95.96-9.02-1.78zM5.14 8.72c1.86.1 1.43 2.69-.18 2.58-1.74-.12-1.58-2.68.18-2.58zM15.17 3.68c1.68 0 1.86 2.8 0 2.74-1.93-.06-1.67-2.74 0-2.74zM7.78 12.1c.13-.54-.9-.47-.87 0 .07.87.74.53.87 0zM13.56 9.01c-.77-.07-.55.95 0 .84.35-.07.77-.77 0-.84z'
						}),
						el('path', {
							fill: '#333',
							d: 'M1 22L1 2 4 2 4 1 0 1 0 23 4 23 4 22z'
						}),
						el('path', {
							fill: '#333',
							d: 'M20 1L20 2 23 2 23 22 20 22 20 23 24 23 24 1z'
						}),
					)
				);
 
	/****************************************
	 *
	 *		Rendering jAlbum Projector
	 *
	 */
	 
	var scriptName = 'jalbum-bridge-block.js',
	
		log = function(txt) {
				if (console && txt && DEBUG) {
					if (txt.match(/^Error\:/i)) {
						console.error(scriptName + ' ' + txt);
					} else if (txt.match(/^Warning\:/i)) {
						console.warn(scriptName + ' ' + txt);
					} else if (txt.match(/^Info\:/i)) {
						console.info(scriptName + ' ' + txt);
					} else {
						console.log(scriptName + ' ' + txt);
					}
				}
			},
		
		getDiff = function(o1, o2) {
				var i,
					diff = [];
				
				for (i in o1) {
					if (!o2.hasOwnProperty(i)) {
						diff.push(i + ': ' + 'null -> ' + o1[i]);
					} else if (typeof o1[i] === 'object') {
						diff.push(i + ': ' + isEqual(o1[i], o2[i]));
					} else if (o1[i] !== o2[i]) {
						diff.push(i + ': ' + o2[i] + ' -> ' + o1[i]); 
					}
				}
				
				// Extra values in o2?
				for (i in o2) {
					if (!o1.hasOwnProperty(i)) {
						diff.push(i + ': ' + o2[i] + ' -> null');
					}
				}
				
				return diff;
			},
	
		renderProjector = function(id, attr) {
			
				if (!id) {
					log('Error: No id!');
					return;
				}
				
				// Check if block exists: wait if not yet
				var block = jQuery('[data-block=' + id + ']');
				
				if (!block.length) {
					log('Info: No such block exists (yet)! -> Retry in 500ms');
					setTimeout(renderProjector, 500, id, attr);
					return;
				}
				
				// Avoid multiple calls within 300ms
				var renderTimeout = block.data('renderTimeout');
				
				if (renderTimeout) {
					clearTimeout(renderTimeout);
					block.data('renderTimeout', null);
					//log('Info: skipping multiple render attempts');
				}
				
				block.data('renderTimeout', setTimeout(function(id, attr) {
				
						var block = jQuery('[data-block=' + id + ']'),
							jp = block.find('.jalbum');
						
						log('Info: Rendering block ' + id);
						if (!attr) {
							log('Error: Missing attributes!');
							return;
						}
						block.data('renderTimeout', null);
						
						if (jp.length) {
							var d = jp.attr('data-jalbum');
							
							if (block.data('inited')) {
								var diff = getDiff(attr, jp.data('jalbum-lastrender'));
								if (diff.length) {
									log('Info: Rendering change(s):\n\t' + diff.join('\n\t'));
								} else {
									log('Info: No data has changed.');
									return;
								}
							} else {
								log('Info: First render -> creating jalbum-data');
								if (!jp.attr('data-jalbum-texts')) {
									jp.attr('data-jalbum-texts', JSON.stringify(texts).replace(/'/g, "&#39;"));
								}
								block.data('inited', true);
							}
							
							jp.attr('data-jalbum', JSON.stringify(attr).replace(/'/g, "&#39;"));
							jp.data('jalbum-lastrender', attr).jalbum();
						}
						
					}, 300, id, attr)
				);
			
			};
	
	/*********************************************
	 *
	 *			Registering custom block
	 *
	 */
	 
	registerBlockType( pn + '/gallery', {
		title: 				__('jAlbum Bridge', 'jalbum-bridge'),
		category: 			'embed',
		keywords:			[ __('photo', 'jalbum-bridge'), __('image', 'jalbum-bridge'), __('gallery', 'jalbum-bridge') ],
		icon: 				jalbumBridgeIcon,
		supports: 			{
								align: 						['center', 'wide', 'full'], 
							},
		className:			'jalbum-cont',
		attributes: 		{                   
								albumurl: 			{
														type: 		'string',
													},
								align:				{
														type:		'string',
														default:	'center',
													},
								folder: 			{
														type: 		'string',
														default: 	'',
													},
								depth: 				{
														type: 		'number',
														default: 	defaults['depth'],
													},
								include:			{
														type:		'string',
														default:	defaults['include'],
													},
								ordering:			{
														type:		'string',
														default:	defaults['ordering'],	
													},
								ar:					{
														type:		'string',
														default:	defaults['ar'],
													},
								disablelinks: 		{
														type: 		'boolean',
														default: 	defaults['disablelinks'],
													},
								opennew:			{
														type:		'boolean',
														default:	defaults['opennew'],
													},
								linktoindex:		{
														type: 		'boolean',
														default: 	defaults['linkToIndex'],
													},
								skipduplicates: 	{
														type: 		'boolean',
														default: 	defaults['skipduplicates'],
													},
								type:				{
														type:		'string',
														default:	defaults['type'],	
													},
								transition: 		{
														type: 		'string',
														default: 	defaults['transition'],
													},
								slideshowdelay:		{
														type:		'number',
														default:	defaults['slideshowdelay'],
													},
								transitionspeed:	{
														type:		'number',
														default:	defaults['transitionspeed'],
													},  
								layout:				{
														type: 		'string',
														default: 	defaults['layout'],
													},
								gap:				{
														type:		'string',
														default:	defaults['gap'],
													},
								titletemplate:		{
														type: 		'string',
														default: 	defaults['titletemplate'],
													},
								titleplacement:		{
														type: 		'string',
														default: 	defaults['titleplacement'],
													},
								titlestyle:			{
														type: 		'string',
														default: 	defaults['titlestyle'],
													},
								captiontemplate:	{
														type: 		'string',
														default: 	defaults['captiontemplate'],
													},
								captionplacement:	{
														type: 		'string',
														default: 	defaults['captionplacement'],
													},
								captionstyle:		{
														type: 		'string',
														default: 	defaults['captionstyle'],
													},
							},
							
		/**************************
		 *
		 *		Edit function
		 *
		 */

		edit: function( props ) {

			var attr = props.attributes,
				id = props.clientId;
				
			renderProjector(id, attr);

			return [
				
				/*********************
				 *	 The Block Element
				 */
				el(
					'div', 
					{ 
						className: 			'jalbum-block jalbum-preview align' + attr.align,
					},
					el(
						'div',
						{
							className:				'jalbum',
							'data-jalbum':			JSON.stringify(attr).replace(/'/g, "&#39;"), 	//.replace(/"([,:}])/g, "'$1").replace(/([{,:])"/g, "$1'"),
							'data-jalbum-texts':	JSON.stringify(texts).replace(/'/g, "&#39;"), 
							style:					{ paddingBottom: (attr.ar + '%') },
						},
						''
					),
				),
				
				/*********************
				 *	    Sidebar
				 */
				el(
					wp.blockEditor? wp.blockEditor.InspectorControls : wp.editor.InspectorControls,
					null,
					
					/* General panel */
					el(
						wp.components.PanelBody, {
							initialOpen: 	true,
							className:		'_jb_panel',
						},
						el(
							wp.components.TextControl,
							{
								label: 			__('URL of the album\'s root page', 'jalbum-bridge') + ':',
								placeholder:	'https://...',
								type: 			'url',
								value: 			attr.albumurl,
								onChange: 		function(newValue) {
													props.setAttributes( { albumurl: newValue } );
													renderProjector(id, attr);
												}
							},
						),
						el(
							'p',
							{
								className:		'_jb_info',
							},
							__('Read more about', 'jalbum-bridge') + ' ',
							el (
								'a',
								{
									target:			'_blank',
									href:			'http://your-site-here.com',
								},
								'jAlbum Bridge',
							),
							'!',
						),
					),
					
					/* Settings panel */
					el(
						wp.components.PanelBody, {
							title: 			__('Settings', 'jalbum-bridge'),
							icon: 			settingsIcon,
							initialOpen: 	false,
							className:		'_jb_panel',
						},
						el(
							wp.components.TextControl,
							{
								label:			__('Initial folder', 'jalbum-bridge'),
								placeholder:	__('Leave empty for the main page!', 'jalbum-bridge'),
								type: 			'text',
								value: 			attr.folder,
								onChange: 		function(newValue) {
													props.setAttributes( { folder: newValue } );
													renderProjector(id, attr);
												}
							},
						),
						el(
							wp.components.RangeControl,
							{
								label: 			__('Depth', 'jalbum-bridge') + ' (' + __('levels', 'jalbum-bridge') + ')',
								value: 			attr.depth,
								min:			1,
								max:			10,
								step:			1,
								onChange: 		function(newValue) {
													props.setAttributes( { depth: newValue } );
													renderProjector(id, attr);
												}
							}
						),
						el(
							wp.components.SelectControl,
							{
								label: 			__('Include', 'jalbum-bridge'),
								value: 			attr.include,
								options: 		[
													{ value: 'folders', 		label: __('Folders', 'jalbum-bridge') },
													{ value: 'folders,images', 	label: __('Folders', 'jalbum-bridge') + ' + ' + __('Images', 'jalbum-bridge') },
													{ value: 'images,folders', 	label: __('Images', 'jalbum-bridge') + ' + ' + __('Folders', 'jalbum-bridge') },
													{ value: 'images', 			label: __('Images', 'jalbum-bridge') },
												],

								onChange: 		function(newValue) {
													props.setAttributes( { include: newValue } );
													renderProjector(id, attr);
												}
							}
						),
						el(
							wp.components.SelectControl,
							{
								label: 			__('Ordering', 'jalbum-bridge'),
								value: 			attr.ordering,
								options: 		[
													{ value: 'original', 		label: __('Original', 'jalbum-bridge') },
													{ value: 'date', 			label: __('Date', 'jalbum-bridge') + ' ' + '(' + __('oldest first', 'jalbum-bridge') + ')' },
													{ value: 'date-reverse', 	label: __('Date', 'jalbum-bridge') + ' ' + '(' + __('newest first', 'jalbum-bridge') + ')' },
													{ value: 'name', 			label: __('File name', 'jalbum-bridge') + '(A-Z)' },
													{ value: 'name-reverse', 	label: __('File name', 'jalbum-bridge') + '(Z-A)' },
													{ value: 'size', 			label: __('File size', 'jalbum-bridge') + ' ' + '(' + __('smallest first', 'jalbum-bridge') + ')' },
													{ value: 'size-reverse', 	label: __('File size', 'jalbum-bridge') + ' ' + '(' + __('largest first', 'jalbum-bridge') + ')' },
													{ value: 'random', 			label: __('Randomized', 'jalbum-bridge') },
												],

								onChange: 		function(newValue) {
													props.setAttributes( { ordering: newValue } );
													renderProjector(id, attr);
												}
							}
						),
						el(
							wp.components.CheckboxControl,
							{
								label: 			__('Disable links to the album', 'jalbum-bridge'),
								checked: 		attr.disablelinks,
								onChange: 		function(newValue) {
													props.setAttributes( { disablelinks: newValue } );
													renderProjector(id, attr);
												}
							}
						),
						el(
							wp.components.CheckboxControl,
							{
								label: 			__('Link to main page (not images)', 'jalbum-bridge'),
								checked: 		attr.linktoindex,
								onChange: 		function(newValue) {
													props.setAttributes( { linktoindex: newValue } );
													renderProjector(id, attr);
												}
							}
						),
						el(
							wp.components.CheckboxControl,
							{
								label: 			__('Open album in new window', 'jalbum-bridge'),
								checked: 		attr.opennew,
								onChange: 		function(newValue) {
													props.setAttributes( { opennew: newValue } );
													renderProjector(id, attr);
												}
							}
						),
						el(
							wp.components.CheckboxControl,
							{
								label: 			__('Skip duplicate items', 'jalbum-bridge'),
								checked: 		attr.skipduplicates,
								onChange: 		function(newValue) {
													props.setAttributes( { skipduplicates: newValue } );
													renderProjector(id, attr);
												}
							}
						)
					),
					
					/* Type panel */
					el(
						wp.components.PanelBody, {
							title: 			__('Type', 'jalbum-bridge') + ' & ' + __('Transition', 'jalbum-bridge'),
							icon: 			transitionIcon,
							initialOpen: 	false,
							className:		'_jb_panel',
						},
						el(
							'div',
							{
								className:		'_jb_transition_icon _jb_tr_' + attr.transition + ' _jb_ty_' + attr.type,
							}
						),
						el(
							wp.components.SelectControl,
							{
								label: 			__('Type', 'jalbum-bridge'),
								value: 			attr.type,
								options: 		[
													{ value: 'slideshow', 	label: __('Slideshow', 'jalbum-bridge') },
													{ value: 'grid', 		label: __('Grid', 'jalbum-bridge') },
													{ value: 'masonry', 	label: __('Masonry', 'jalbum-bridge') },
													{ value: 'mosaic', 		label: __('Mosaic', 'jalbum-bridge') },
													{ value: 'strip', 		label: __('Strip', 'jalbum-bridge') },
												],

								onChange: 		function(newValue) {
													props.setAttributes( { type: 	newValue } );
													props.setAttributes( { ar: 		defaultArs[ newValue ] } );
													props.setAttributes( { layout: 	defaultLayouts[ newValue ] } );
													renderProjector(id, attr);
												}
							}
						),
						(attr.type === 'slideshow')? (
							el(
								'div',
								{
									className:		'_jb_slideshow_controls'
								},
								el(
									wp.components.SelectControl,
									{
										label: 			__('Transition', 'jalbum-bridge'),
										value: 			attr.transition,
										options: 		[
															{ value: 'crossfade', 	label: __('Cross fade', 'jalbum-bridge') },
															{ value: 'zoom', 		label: __('Zoom', 'jalbum-bridge') },
															{ value: 'kenburns', 	label: __('Ken Burns', 'jalbum-bridge') },
															{ value: 'stack', 		label: __('Stack', 'jalbum-bridge') },
															{ value: 'slide', 		label: __('Slide', 'jalbum-bridge') },
															{ value: 'swap', 		label: __('Swap', 'jalbum-bridge') },
															{ value: 'carousel', 	label: __('Carousel', 'jalbum-bridge') },
															{ value: 'flip', 		label: __('Flip', 'jalbum-bridge') },
															{ value: 'book', 		label: __('Book', 'jalbum-bridge') },
															{ value: 'cube', 		label: __('Cube', 'jalbum-bridge') },
															{ value: 'coverflow', 	label: __('Coverflow', 'jalbum-bridge') },
														],
		
										onChange: 		function(newValue) {
															props.setAttributes( { transition: newValue } );
															props.setAttributes( { slideshowdelay: defaultTimings[ newValue ][0] } );
															props.setAttributes( { transitionspeed: defaultTimings[ newValue ][1] } );
															renderProjector(id, attr);
														}
									}
								),
								el(
									wp.components.RangeControl,
									{
										label: 			__('Number of images', 'jalbum-bridge'),
										value: 			parseInt( attr.layout, 10 ),
										min:			3,
										max:			(attr.transition === 'carousel')? 15 : 100,
										step:			1,
										onChange: 		function(newValue) {
															props.setAttributes( { layout: (newValue + '') } );
															renderProjector(id, attr);
														}
									}
								),
								el(
									wp.components.RangeControl,
									{
										label: 			__('Pause length', 'jalbum-bridge') + ' (ms)',
										value: 			attr.slideshowdelay,
										min:			0,
										max:			10000,
										step:			100,
										onChange: 		function(newValue) {
															props.setAttributes( { slideshowdelay: newValue } );
															renderProjector(id, attr);
														}
									}
								),
								el(
									wp.components.RangeControl,
									{
										label: 			__('Transition length', 'jalbum-bridge') + ' (ms)',
										value: 			attr.transitionspeed,
										min:			100,
										max:			10000,
										step:			100,
										onChange: 		function(newValue) {
															props.setAttributes( { transitionspeed: newValue } );
															renderProjector(id, attr);
														}
									}
								),
							)
						)
						:
						(	
							el(
								'div',
								{
									className:		'_jb_grid_controls'
								},
								(attr.type === 'masonry' || attr.type === 'strip')? (
									el(
										wp.components.RangeControl,
										{
											label: 			__('Number of images', 'jalbum-bridge'),
											value: 			parseInt( attr.layout, 10 ),
											min:			(attr.type === 'strip')? 2 : 5,
											max:			(attr.type === 'strip')? 10 : 100,
											step:			1,
											onChange: 		function(newValue) {
																props.setAttributes( { layout: (newValue + '') } );
																renderProjector(id, attr);
															}
										}
									)
								)
								:
								(	
									(attr.type === 'grid')? (
										el(
											wp.components.SelectControl,
											{
												label: 			__('Grid layout', 'jalbum-bridge'),
												value: 			attr.layout,
												options: 		[
																	{ value: 'grid-2-2', 	label: '2 \xD7 2' },
																	{ value: 'grid-3-2', 	label: '3 \xD7 2' },
																	{ value: 'grid-4-2', 	label: '4 \xD7 2' },
																	{ value: 'grid-5-2', 	label: '5 \xD7 2' },
																	{ value: 'grid-3-3', 	label: '3 \xD7 3' },
																	{ value: 'grid-4-3', 	label: '4 \xD7 3' },
																	{ value: 'grid-5-3', 	label: '5 \xD7 3' },
																	{ value: 'grid-4-4', 	label: '4 \xD7 4' },
																	{ value: 'grid-5-4', 	label: '5 \xD7 4' },
																	{ value: 'grid-5-5', 	label: '5 \xD7 5' },
																],
												onChange: 		function(newValue) {
																	props.setAttributes( { layout:  newValue } );
																	renderProjector(id, attr);
																}
											}
										)
									)
									:
									(	
										el(
											wp.components.SelectControl,
											{
												label: 			__('Mosaic layout', 'jalbum-bridge'),
												value: 			attr.layout,
												options: 		[
																	{ value: 'mos-1-3', 	label: '1 + 3' },
																	{ value: 'mos-2-3', 	label: '2 + 3' },
																	{ value: 'mos-1-2-4', 	label: '1 + 2 + 4' },
																	{ value: 'mos-1-5', 	label: '1 + 5 ' + __('around') },
																],
												onChange: 		function(newValue) {
																	props.setAttributes( { layout:  newValue } );
																	renderProjector(id, attr);
																}
											}
										)
									)
								),
								el(
									wp.components.SelectControl,
									{
										label: 			__('Gap', 'jalbum-bridge'),
										value: 			attr.gap,
										options: 		[
															{ value: 'none', 	label: __('No gap') },
															{ value: 'thin', 	label: __('Thin') },
															{ value: 'small', 	label: __('Small') },
															{ value: 'medium', 	label: __('Medium') },
															{ value: 'large', 	label: __('Large') },
															{ value: 'xlarge', 	label: __('Extra large') },
														],
										onChange: 		function(newValue) {
															props.setAttributes( { gap: newValue } );
															renderProjector(id, attr);
														}
									}
								)
							)
						),
						el(
							wp.components.SelectControl,
							{
								label: 			__('Aspect ratio', 'jalbum-bridge'),
								value: 			attr.ar,
								options: 		[
													{ value: '10', 			label: '10:1 (10%)' },
													{ value: '12.5',	 	label: '8:1 (12.5%)' },
													{ value: '16.6667', 	label: '6:1 (16.7%)' },
													{ value: '20', 			label: '5:1 (20%)' },
													{ value: '25', 			label: '4:1 (25%)' },
													{ value: '33.3333', 	label: '3:1 (33%)' },
													{ value: '50', 			label: '2:1 (50%)' },
													{ value: '56.25', 		label: '16:9 (56%)' },
													{ value: '60', 			label: '5:3 (60%)' },
													{ value: '70', 			label: '10:7 (70%)' },
													{ value: '75', 			label: '4:3 (75%)' },
													{ value: '80', 			label: '5:4 (80%)' },
													{ value: '83.333', 		label: '6:5 (83%)' },
													{ value: '100', 		label: '1:1 (100%)' },
													{ value: '120', 		label: '5:6 (120%)' },
													{ value: '133.333', 	label: '4:3 (133%)' },
													{ value: '150', 		label: '3:2 (150%)' },
													{ value: '177.778', 	label: '9:16 (178%)' },
													{ value: '200', 		label: '1:2 (200%)' },
												],

								onChange: 		function(newValue) {
													props.setAttributes( { ar: newValue } );
													renderProjector(id, attr);
												}
							}
						),
					),
					
					/* Captions panel */
					el(
						wp.components.PanelBody, {
							title: 			__('Captions', 'jalbum-bridge'),
							icon: 			captionIcon,
							initialOpen: 	false,
							className:		'_jb_panel',
						},
						el(
							wp.components.SelectControl,
							{
								label: 			__('Album Title', 'jalbum-bridge'),
								value: 			attr.titletemplate,
								options: 		[
													{
														value: '',
														label: __('Empty', 'jalbum-bridge')
													},
													{ 
														value: '<h4>${title|name}</h4>', 		
														label: __('Title', 'jalbum-bridge') 
													},
													{ 
														value: '<h4 class="linkicon">${title|name}</h4>', 		
														label: __('Title', 'jalbum-bridge') + ' + ' + __('link icon', 'jalbum-bridge') 
													},
													{ 
														value: '<h4>${title|name}</h4><p class="slidein">${comment}</p>', 		
														label: __('Title', 'jalbum-bridge') + ' + ' + __('description', 'jalbum-bridge') + ' (' + __('on hover', 'jalbum-bridge') + ')' 
													},
													{ 
														value: '<h4>${title|name}</h4><p>${comment}</p>', 		
														label: __('Title', 'jalbum-bridge') + ' + ' + __('description', 'jalbum-bridge') 
													},
													{ 
														value: '<h4>${title|name}</h4><p>${comment}</p><p><a href="${albumPath}" class="button">Visit album</a></p>', 		
														label: __('Title', 'jalbum-bridge') + ' + ' + __('description', 'jalbum-bridge') + ' + ' + __('button', 'jalbum-bridge') 
													},
													{ 
														value: '<div class="slidein"><h4>${title|name}</h4><p>${comment}</p></div>', 		
														label: __('Title', 'jalbum-bridge') + ' + ' + __('description', 'jalbum-bridge') + ' (' + __('show all on hover', 'jalbum-bridge') + ')'
													},
												],

								onChange: 		function(newValue) {
													props.setAttributes( { titletemplate: newValue } );
													renderProjector(id, attr);
												}
							}
						),
						el(
							'div',
							{
								className:		'_jb_flex_row',
							},
							el(
								wp.components.SelectControl,
								{
									value: 			attr.titleplacement,
									options:		placements,
									onChange: 		function(newValue) {
														props.setAttributes( { titleplacement: newValue } );
														renderProjector(id, attr);
													}
								}
							),
							el(
								wp.components.SelectControl,
								{
									value: 			attr.titlestyle,
									options:		captionStyles,
									onChange: 		function(newValue) {
														props.setAttributes( { titlestyle: newValue } );
														renderProjector(id, attr);
													}
								}
							),
						),
						el(
							wp.components.SelectControl,
							{
								label: 			__('Image Caption', 'jalbum-bridge'),
								value: 			attr.captiontemplate,
								options: 		[
													{
														value: '',
														label: __('Empty', 'jalbum-bridge')
													},
													{ 
														value: '<h5>${title|label}</h5>', 		
														label: __('Title', 'jalbum-bridge') 
													},
													{ 
														value: '<h5>${title|label}</h5><p>${comment}</p>', 		
														label: __('Title', 'jalbum-bridge') + ' + ' + __('description', 'jalbum-bridge') 
													},
												],

								onChange: 		function(newValue) {
													props.setAttributes( { captiontemplate: newValue } );
													renderProjector(id, attr);
												}
							}
						),
						el(
							'div',
							{
								className:		'_jb_flex_row',
							},
							el(
								wp.components.SelectControl,
								{
									value: 			attr.captionplacement,
									options:		placements,
									onChange: 		function(newValue) {
														props.setAttributes( { captionplacement: newValue } );
														renderProjector(id, attr);
													}
								}
							),
							el(
								wp.components.SelectControl,
								{
									value: 			attr.captionstyle,
									options:		captionStyles,
									onChange: 		function(newValue) {
														props.setAttributes( { captionstyle: newValue } );
														renderProjector(id, attr);
													}
								}
							),
						),
					),
				),
			];
		},

		/**************************
		 *
		 *		Save function
		 *
		 */

		save: function( props ) {
			
			//log('Saving block ' + props.clientId);
			//log('\tattributes: ' + JSON.stringify(props.attributes));
			return null;
		},

	});
	
}( jQuery ));
