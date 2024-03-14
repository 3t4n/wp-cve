( function( blocks, element ) {
	var el 					= element.createElement,
		InspectorControls 	= ('blockEditor' in wp) ? wp.blockEditor.InspectorControls : wp.editor.InspectorControls;

	/* Plugin Category */
	blocks.getCategories().push({slug: 'eddmp', title: 'Easy Digital Downloads Music Player'});

	/* ICONS */
	const iconEDDMPP = el('img', { width: 20, height: 20, src:  "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAALEgAACxIB0t1+/AAAABZ0RVh0Q3JlYXRpb24gVGltZQAwMS8xOS8yMMr06GwAAAAcdEVYdFNvZnR3YXJlAEFkb2JlIEZpcmV3b3JrcyBDUzbovLKMAAABcElEQVRIie2VzytEURTHP3feMzM7mez8KD8WNvNGYomd0aylbKSUppFkIVs2srWQ/0CyUqZIsiCJwsrEEhuzME1TePNmrmshMvHGfY1ZKN/lued7PnXuPfeIpvYORRXlq2bxf4CWTC/JI8ND9Pf14jgOQoiP+PHJKWvrG5UDIpZFLDrwJR4MBH8HoNQLAPl8ntTVNblcjhrT5DKVcvV4Agje2nKfTjMzO8fN7R2maVIsFn8H8FnPzzZSSqSUZfM8vSLF29AbhkEwGNDyeALYdt5LOqDRok7Loqe7i8enJ9paWwBQSv/7KguYSsQZHxslVFdXUlgppQ1xBUzGJ5idmS6JvQ+XEKJk0MrJ9Q6mEnF3k/Dh8xmVAUzDvYBCkc1mKwMsr6y6mg4Oj3jIZLQARm2ofv67g7PzC2zbptMK4/f7AZBSsrmVZGFxCcdxtADip5UZscLEBqM0Nzawu7dPcnuHQqGgVVwLUKn+/karOuAVjB1yA27FmkkAAAAASUVORK5CYII=" } );

	/* Sell Downloads Shortcode */
	blocks.registerBlockType( 'eddmp/edd-music-player-playlist', {
		title: 'Easy Digital Downloads Music Player Playlist',
		icon: iconEDDMPP,
		category: 'eddmp',
		customClassName: false,
		supports:{
			customClassName: false,
			className: false
		},
		attributes: {
			shortcode : {
				type : 'string',
				source : 'text',
				default: '[eddmp-playlist downloads_ids="*" controls="track"]'
			}
		},

		edit: function( props ) {
			var children = [], focus = props.isSelected;

			children.push(
				el('textarea',
					{
						key : 'eddmp_playlist_shortcode',
						value: props.attributes.shortcode,
						onChange: function(evt){
							props.setAttributes({shortcode: evt.target.value});
						},
						style: {width:"100%", resize: "vertical"}
					}
				)
			);

			children.push(
				el(
					'div', {className: 'eddmp-iframe-container', key:'eddmp_iframe_container'},
					el('div', {className: 'eddmp-iframe-overlay', key:'eddmp_iframe_overlay'}),
					el('iframe',
						{
							key: 'eddmp_iframe',
							src: eddmp_gutenberg_editor_config.url+encodeURIComponent(props.attributes.shortcode),
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
						{
							key : 'eddmp_playlist'
						},
						el(
                            'div',
                            {
                                key: 'cp_inspector_container',
                                style:{paddingLeft:'15px',paddingRight:'15px'}
                            },
                            [
                                el(
                                    'p',
                                    {
                                        key: 'eddmp_inspector_help'

                                    },
                                    'To include specific downloads in the playlist enter their IDs in the downloads_ids attributes, separated by comma symbols (,)'
                                ),
                                el(
                                    'p',
                                    {
                                        key   : 'eddmp_inspector_more_help',
                                        style : {fontWeight: 'bold'}
                                    },
                                    'More information visiting the follwing link:'
                                ),
                                el(
                                    'a',
                                    {
                                        key		: 'eddmp_inspector_help_link',
                                        href	: 'https://wordpress.dwbooster.com/content-tools/music-player-for-easy-digital-downloads#eddmp-playlist',
                                        target	: '_blank'
                                    },
                                    'CLICK HERE'
                                ),
                            ]
                        )
					)
				);
			}
			return children;
		},

		save: function( props ) {
			return props.attributes.shortcode;
		}
	});
} )(
	window.wp.blocks,
	window.wp.element
);
