/**
 * Javascript for managing the add 360 view media page
 *
 */

//reference to jQuery
y$ = $ || jQuery;


/**
 * Copy shortcode to clipboard
 */
function yCopyShortcodeToClipboard(inputId){
	var copyTextarea = document.getElementById(inputId)
	copyTextarea.focus();
	copyTextarea.select();
	try {
		var successful = document.execCommand('copy');
		copyTextarea.blur();
		var jq = $ || jQuery;
		var note = document.getElementById(inputId + '_note')
		if(note){
			jq(note).show()
      setTimeout(function(){jq(note).hide()}, 2000);

		}
	} catch (err) {
	}
}

/**
 * Launch 360 view preview in a popup window
 *
 * @param viewId
 */
function yPreview360View(cloudId,width,height){
	var $e = cloudId.split(';');
	var $accountId = $e[0];
	var $projectId = $e[1];
	var $versionNumber = $e[2];
	var url =  "https://c.y360.at/prod/"+$accountId+"/"+$projectId+"/v"+$versionNumber+"/iframe.html";
	 yPopupCenter(url, '360 Preview', width, height)

}

/**
 * @link https://stackoverflow.com/questions/4068373/center-a-popup-window-on-screen
 *
 * @param url
 * @param title
 * @param w
 * @param h
 */
function yPopupCenter(url, title, w, h) {
	// Fixes dual-screen position                         Most browsers      Firefox
	var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : window.screenX;
	var dualScreenTop = window.screenTop != undefined ? window.screenTop : window.screenY;

	var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
	var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

	var systemZoom = width / window.screen.availWidth;
	var left = (width - w) / 2 / systemZoom + dualScreenLeft
	var top = (height - h) / 2 / systemZoom + dualScreenTop
	var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w / systemZoom + ', height=' + h / systemZoom + ', top=' + top + ', left=' + left);

	// Puts focus on the newWindow
	if (window.focus) newWindow.focus();
}

/**
 * Entry point
 */
y$(document).ready(function() {
	yDisplaySelfHosted360ViewsList(selfHostedProjectsListData);
});


function yDisplaySelfHosted360ViewsList(data = []) {
	
	var output = '', cssClass, action, name, valid, dataPath, item, invalid_text, action_text;

	for(var i=0; i<data.length; i++){
		item      = data[i];
		cssClass  = '';
		action    = '';
		name      = item.name;
		valid     = (item.data && item.data.images && item.data.images.length > 0);
		dataPath  = name;

		invalid_text = '<span class="invalid">(not a 360&deg; view folder)</span>';
		action_text  = '<span class="action action_embed" data-path="'+dataPath+'">Show Shortcode</span>';

		cssClass += (valid) ? 'valid' : 'invalid';
		action   += (valid) ? action_text : invalid_text;
		output   += "<li class='"+cssClass+"'>"+name+"  "+action+"</li>\n";
	}

	// add content
	y$('ul.products_list').html(output);

	// add actions
	y$('span.action_embed').click(function(){
		var data = y$(this).data();
		var path = 'yofla360/'+data.path;

		var text = '[360 width="500" height="400" auto-height="true" src="'+path+'"]';

		//copy to clipboard
		var msg = "Copy the shortcode to clipboard and then paste into any page.\n";
		msg += "(you can modify the width/height parameter as you like)";
		window.prompt(msg, text);
	});

}
