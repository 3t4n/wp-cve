// function to get the API data
function httpGetAsync(theUrl, callback){
    var xmlHttp = new XMLHttpRequest();
	xmlHttp.onreadystatechange = function(){
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
            callback(xmlHttp.responseText);
        }
    }
	xmlHttp.open("GET", theUrl, true);
	xmlHttp.send(null);
    return;
}

// get user keys
var getUrlParameter = function getUrlParameter(sParam) {
	var sPageURL = window.location.search.substring(1),
		sURLVariables = sPageURL.split('&'),
		sParameterName,
		i;
	for (i = 0; i < sURLVariables.length; i++) {
		sParameterName = sURLVariables[i].split('=');
		if (sParameterName[0] === sParam) {
			return typeof sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
		}
	}
	return false;
};
var tenor_key = getUrlParameter('tenor');

// Display result gifs in ul
function load_gifs(gifs){
	var id, prvw, medium_img, orig;
	var elem_array = [];
	var _frag = document.createDocumentFragment();
	jQuery(gifs).each(function(id, gif){
		id = gif['id'];
		prvw = gif['media_formats'].nanogif['url'];
		medium_img = gif['media_formats'].tinygif['url'];
		orig = gif['media_formats'].gif['url'];
		var _li = newT.li({
						clss:"tenor_gif_li",
						draggable:true,
					},
					newT.img({
						clss:"gif tenor_gif",
						width:"100%",
						"src":prvw, 
						"data-id":id,
						"data-mdm-img": medium_img,
						"data-orig-img": orig
					})
				);
		_frag.appendChild(_li);
		elem_array.push(_li);
	});
	var tenor_gifsEl = jQuery('ul#tnrgifs');
	tenor_gifsEl.append(_frag);
	tenor_gifsEl.html(elem_array);
}

// callback for search
function tenorCallback_search(responsetext){
    var response_objects = JSON.parse(responsetext);
	gifs = response_objects["results"];
	load_gifs(gifs);
	return;
}

// callback for trending GIFs
function tenorCallback_trending(responsetext){
    var response_objects = JSON.parse(responsetext);
    trending_gifs = response_objects["results"];
	load_gifs(trending_gifs);
    return;
}

// Trigger search - input handler
jQuery("#tnr-searchbar-input").keypress(function(event) {
	if(event.keyCode == 13) {
		grab_data('search');
	}
});

// Trigger search - search button handler
jQuery("#tnr-search-button").on("click", function() {
	grab_data('search');
});

// selecting image
jQuery("#tnrgifs").on("click", ".tenor_gif_li", function(){
	var mdm_src = jQuery(this).find("img").attr("data-mdm-img");
	var orig_src = jQuery(this).find("img").attr("data-orig-img");
	jQuery("#tnr-gif-detail-gif").attr("src", mdm_src);
	jQuery("#tnr-gif-detail-gif").attr("data-orig-img", orig_src);
	jQuery("#tnrgifs, #tnr-footer").hide();
	jQuery("#tnr-gif-detail").show();
	jQuery("#tnr-back-button").show();
});
// back from selected image
jQuery("#tnr-back-button").on("click", function(){
	jQuery("#tnr-gif-detail").hide();
	jQuery("#tnr-back-button").hide();
	jQuery("#tnrgifs, #tnr-footer").show();
});

// Insert selected image
jQuery("#tnr-embed-button").on("click", function(){
	insertIntoEd();
});

function insertIntoEd() {
	// var htmlCode = '<div class="tenor-gif-embed" data-postid="15131615" data-share-method="host" data-width="100%" data-aspect-ratio="0.821285140562249"><a href="https://tenor.com/view/jason-bateman-why-face-emmy-emotions-gif-15131615">Jason Bateman Why GIF</a> from <a href="https://tenor.com/search/jasonbateman-gifs">Jasonbateman GIFs</a></div><script type="text/javascript" async src="https://tenor.com/embed.js"></script>';
	var orig_src = jQuery('img#tnr-gif-detail-gif').attr('data-orig-img');
	var attr_str = '<span style="display: block; width: 100%; background: #f7f7f7; position: absolute; bottom: 0px; padding: 5px 0;"><img src="https://www.gstatic.com/tenor/web/attribution/via_tenor_logo_grey.png" style="width: 106px; float: right;"></span>';
	var htmlCode = '<div class="tenor_final_gif" style="position: relative; width: fit-content; padding-bottom: 33px;"><img src="'+orig_src+'">'+attr_str+'</div><p></p>';
	
	parent.tinyMCE.activeEditor.execCommand("mceInsertRawHTML", false, htmlCode);
	parent.tinyMCE.activeEditor.selection.select(parent.tinyMCE.activeEditor.getBody(), true); // ed is the editor instance
	parent.tinyMCE.activeEditor.selection.collapse(false);
	parent.tinyMCE.activeEditor.windowManager.close(window);
}

// function to call the endpoints
function grab_data(cmd){
    var apikey = tenor_key;
    var lmt = 50;
	
    if(cmd == 'search'){
		var search_term = jQuery("#tnr-searchbar-input").val();
		if(search_term == ''){ return; }
		// using default locale of en_US
		var search_url = "https://tenor.googleapis.com/v2/search?q=" + search_term + "&key=" + apikey + "&limit=" + lmt; // https://g.tenor.com/v1
		httpGetAsync(search_url,tenorCallback_search);
	}
	
	if(cmd == 'trending'){
		var trending_url = "https://tenor.googleapis.com/v2/featured?key=" + apikey + "&limit=" + lmt;
		httpGetAsync(trending_url,tenorCallback_trending);
	}
	
	return;
}

// start the flow
grab_data('trending');

