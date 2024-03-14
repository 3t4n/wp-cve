jQuery(function(){
	(function( blocks, element ) {
		var el 		= element.createElement;

		/* Hide the button required by the classic editor */
		jQuery(function(){
			jQuery('.cpm-classic-editor').hide();
            jQuery('.cpm-gutenberg-editor').css({display:'block',width:'100%', padding:0});
            jQuery('.cpm-gutenberg-editor select').css({height:'100px'});
		});

		/* Plugin Category */
		blocks.getCategories().push({slug: 'cpgm', title: 'CP Google Maps'});

		/* ICONS */
		const iconCPGM = el('img', { width: 20, height: 20, src:  "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAiCAYAAABfqvm9AAAABGdBTUEAAK/INwWK6QAAAxFJREFUSMetlktME2EQx5dHBaE8rAgULFBoQRA1QQVBCopQg0ahajSlB0EoEWhpiw8UH0g8kAgioCcvmBhPHo0XD3jwrIlRYmLiRUAjFIOPSEzIjv8pLdIXj5ZJfkmz3+6vOzvfzK4g+I900AiGwSMnD0AzyBLWEFGgB0xFSyRUKE+ikzlZdArsS0km6QYJYW0G9IKYlWRK8Foujaa+gyU00XpWpKtmkW5YyQF+f2mrF4cOaUgRI2Xxe7DNn0wFxrUZCrJbGkXq7iDqaifqbHOny0y8NmtrphqVkqXfQL6nbCN4c1ipoL+dJqJrPkSe4Jx5yHXqTJZ+ALFLhd2pSHPKahRXJVu823aavXBezIyPZWmfSyYHnwcqSx2prFrmAtc8rK5g4ZSzBoI+ShJOE6YGfuhuJ0+21VP3/r2UlyBzwL/5mJsQ19iR2abICJY2sbB/d3KizwKwAOtu8DGvu7xuoTJFCq8PsnDkuFqJbWHzOpHvylPIx7yEN21Ul5fN6yMOYQ0qxQeDERq25ywKB/bIg03ZSuVpqbw+xMI6brFJ87kAi2KiGdtiUYwsTAWT97XlgW0bpDtytJJldme3OeLWVmxsu61Z9Nlufje2mX5ebBFV8XEs7F/aKVIwVotqz/Mw8EjdJ0gVfy46q/sJyDz7OYdT1+eq0acWoium5YUohHFXHsumwU5/E6cA/OgpLcKz6Vi23Yarylg2BzQrzcQzIdgeLw0nFmag15Sx0DujQYwKD2dh62qn9uP8LZtp7nKr6JU6hEcy01k2upbXQAaYvVepce8gPLfnp4+5NnmJsMa4o8Sc+3Op5f9dYgholWkseyYEEArw66mueuFZYn+OGQ0UGhLCQq0QYDxxDA4WIvUeTRHL3gpBhE4WGUnfrUZHuoUpSSy8HYwwgYszqq+laUsTRYSFsbBYCDJe3a0opVG9jmVfQXSwwsGGHbnUe6CYhS+EdQijBu8L/hzxnCiBhjY9LoayZfHk/HgKOgo2hIW6ClK1HkI1EJ3tVrQewkTw2ylUrYcwAnwE4yBupZP/ASesGLIiyjDFAAAAAElFTkSuQmCC" } );

		/* CP Google Maps Code */
		blocks.registerBlockType( 'cpgm/map', {
			title: 'CP Google Maps',
			icon: iconCPGM,
			category: 'cpgm',
			supports: {
				customClassName: false,
				className: false
			},

			attributes: {
				shortcode : {
					type : 'text'
				}
			},

			edit: function( props )
			{
				var children = [], postID = wp.data.select("core/editor").getCurrentPostId();
				function onChangeMap(evt)
				{
					props.setAttributes({shortcode: evt.target.value});
				};

				if(typeof props.attributes.shortcode == 'undefined')
				{
					props.attributes.shortcode = cpm_generate_shortcode();
				}

				return 	[
					el(
						'textarea',
						{
							key 	: 'cpgm-shortcode',
							onChange: onChangeMap,
							value 	: props.attributes.shortcode,
							style	: {width:"100%", resize: "vertical"}
						}
					),
					el(
						'div', {className: 'cpm-iframe-container', key: 'cpm_iframe_container'},
						el('div', {className: 'cpm-iframe-overlay', key: 'cpm_iframe_overlay'}),
						el('iframe',
							{
								key: 'cpm_store_iframe',
								src: cpm_ge_config.url+encodeURIComponent(props.attributes.shortcode)+'&post-id='+encodeURIComponent(postID),
								height: 0,
								width: 500,
								scrolling: 'no'
							}
						)
					)
				];
			},

			save: function( props ) {
				return props.attributes.shortcode || '[codepeople-post-map]';
			}
		});
	} )(
		window.wp.blocks,
		window.wp.element
	);

	// Autoupdate
	var reloading_iframes;
	function autoupdate()
	{
		var save_draft = jQuery('.editor-post-save-draft');
		if(save_draft.length) save_draft.trigger('click');
		else
		{
			var update_btn = jQuery('.editor-post-publish-button');
			if(update_btn.length) update_btn.trigger('click');
			else
			{
				try{ wp.heartbeat.connectNow(); } catch(err){}
			}
		}
		if('number' === typeof reloading_iframes)
		{
			clearTimeout(reloading_iframes);
			delete reloading_iframes;
		}
		reloading_iframes = setTimeout(function(){
			jQuery('.cpm-iframe-container iframe').each(function(){
				this.contentWindow.location.reload();
			});
		}, 4000);
	};

	jQuery(document).on('change click', '#codepeople_post_map_form :input, [id*="cpm_point"]',
		function(evt)
		{
			var t = jQuery(evt.target);
			if(evt.type == 'click' && t.is('input[type="button"]') || evt.type == 'change') autoupdate();
		}
	);
});