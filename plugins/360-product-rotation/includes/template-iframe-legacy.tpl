<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title></title>
	<meta name="keywords" content="" />
	<meta name="description" content="" />
	<meta name="robots" content="all" />

	<style>
		body{padding:0; margin:0; background: white; overflow: hidden;}
		#flashContent{ width: {width}px; height: {height}px; border: 0; overflow: hidden; background : white;}
	</style>
	<script type="text/javascript" src="swfobject.js"></script>
	<script type="text/javascript" src="rotatetool.js"></script>
</head>

<body>

<div id="flashcontent">
</div>

<script type="text/javascript">
	if(swfobject.hasFlashPlayerVersion("9.0.115"))
	{
		var flashvars = {};
		flashvars.path = "";

		var params = {};
		params.scale = "noScale";
		params.salign = "lt";
		params.allowScriptAccess = "always";
		params.allowFullScreen = "true";

		var attributes = {};
		attributes.id = "myFlash";
		attributes.name = "myFlash";

		swfobject.embedSWF("rotateTool.swf", "flashcontent", "{width}", "{height}", "10.0.0","expressInstall.swf", flashvars, params, attributes);
	}
	else
	{
		if (typeof(RotateTool) == 'undefined') {
			alert('rotatetool.js not loaded!');
		}
		else
		{
			var jsParams    = {};
			jsParams.path   = "";
			jsParams.id     = "rotateObject";
			jsParams.target = "flashcontent";
			jsParams.targetWidth  = "{width}";
			jsParams.targetHeight = "{height}";
			RotateTool.add(jsParams);
		}
	}
</script>

</body>
</html>