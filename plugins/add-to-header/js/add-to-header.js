function add_clicktag(type, position)
{
	var new_data = document.getElementById('new_data');
	switch(type)
	{
		case 'css_internal':
			html = "<style type=\"text/css\">\nYOUR_CSS\n</style>\n";
			break;
		case 'css_external':
			html = "<link rel=\"stylesheet\" type=\"text/css\" href=\"YOUR_CSS\" />\n";
			break;
		case 'js_internal':
			html = "<script type=\"text/javascript\">\nYOUR_JAVASCRIPT\n</script>\n";
			break;
		case 'js_external':
			html = "<script type=\"text/javascript\" src=\"YOUR_JAVASCRIPT\" />\n";
			break;
		case 'meta':
			html = "<meta name=\"META_NAME\" content=\"META_CONTENT\" />\n";
			break;
		
		default:
			
	}

	if(position == 'first')
	{
		new_data.value = html + new_data.value;
	}
	else if(position == 'last')
	{
		new_data.value = new_data.value + html;
	}
}