jQuery(function()
	{
		try
		{
			jQuery(document).on('click', '.editor-post-save-draft,.editor-post-publish-button', function(){
				jQuery('#cprp_tags_updated_message').show();
				setTimeout(function(){
					wp.data.dispatch('core/editor').savePost();
				}, 4000);
			});
		}
		catch(err)
		{
			if('console' in window) console.info(err);
		}

	}
);