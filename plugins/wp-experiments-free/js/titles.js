function titleex_run_experiment() {
		try {
            var fetch = {};
            var find = 0;

            if("titlexproShouldRunExp" in window) {
                if(!window.titlexproShouldRunExp()) {
					jQuery("img.wpexpro-image").removeClass("wpexpro-image");
					wpex_show_body();
					var matches = jQuery("body").attr("class").match(/\bpostid-(\d+)\b/);
					if (matches) {
						var id = matches[1];
						var $spans = jQuery("[data-wpex-title-id='"+id+"']");
						if ($spans.length) {
							titleex_update_title(Base64.decode($spans.data("original")));
						}
					}
                    return;
				}
            }

            //continue with the experiments
			var $titles = jQuery("[data-wpex-title-id]:not([data-wpex-done])");
            for(var i = $titles.length -1; i>=0; i--) {
                var $title = jQuery($titles[i]);
                var id = $title.data("wpex-title-id");
                $title.attr("data-wpex-done", 1);
                fetch[id] = 1;
                find = 1;
            }
			if(find) {
				var id_class = document.body.className.match(/\bpostid-(\d+)\b/);
				if(id_class) {
					cur_id = id_class[1];
				} else {
					cur_id = -1;
				}

				jQuery.post(wpex.ajaxurl, {
					action: 'wpex_titles',
					id: Object.keys(fetch),
					cur_id: cur_id
				}, function(res) {
					for(var id in res.titles) {
						var $elm = jQuery("[data-wpex-title-id="+id+"]");
						var new_title = '';
						if(!res.titles[id] && $elm.data("original")) {
                            new_title = Base64.decode($elm.data("original"));
						} else {
							new_title = res.titles[id];
						}
						$elm.html(new_title);

						// Is this the post page for this
						if (jQuery("body.postid-"+id).length > 0) {
							titleex_update_title(new_title);
						}
					}
					for(var id in res.images) {
						if(res.images[id].old && res.images[id].new) {
							var $img = jQuery("img[data-wpex-post-id='" + res.images[id].old + "']");
							$img.removeAttr("srcset");
							$img.attr("src", res.images[id].new);
							$img.removeClass("wpexpro-image");
						}
					}
					jQuery("img.wpexpro-image").removeClass("wpexpro-image");
					wpex_show_body();
				}, 'json');
			} else {
				jQuery("img.wpexpro-image").removeClass("wpexpro-image");
				wpex_show_body();
			}
		} catch(err) {
			wpex_show_body();
		}
}

function titleex_update_title(new_title) {
	var $title = jQuery("title");
	var title = $title.text();
	title = title.replace(String.fromCharCode(27)+String.fromCharCode(28)+String.fromCharCode(29), new_title);
	jQuery("title").text(title);
}
var $wpex_body = jQuery("html,body");
function wpex_hide_body() {
	$wpex_body.css('visibility', 'hidden');
}

function wpex_show_body() {
	$wpex_body.css('visibility', 'visible');
}

if(wpex.hide_body) {
	wpex_hide_body();
}

jQuery(document).ready(titleex_run_experiment);

/**
*
*  Base64 encode / decode
*  http://www.webtoolkit.info/
*
**/

var Base64 = {

	// private property
	_keyStr : "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",

	// public method for encoding
	encode : function (input) {
		var output = "";
		var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
		var i = 0;

		input = Base64._utf8_encode(input);

		while (i < input.length) {

			chr1 = input.charCodeAt(i++);
			chr2 = input.charCodeAt(i++);
			chr3 = input.charCodeAt(i++);

			enc1 = chr1 >> 2;
			enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
			enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
			enc4 = chr3 & 63;

			if (isNaN(chr2)) {
				enc3 = enc4 = 64;
			} else if (isNaN(chr3)) {
				enc4 = 64;
			}

			output = output +
			this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
			this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);

		}

		return output;
	},

	// public method for decoding
	decode : function (input) {
		var output = "";
		var chr1, chr2, chr3;
		var enc1, enc2, enc3, enc4;
		var i = 0;

		input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

		while (i < input.length) {

			enc1 = this._keyStr.indexOf(input.charAt(i++));
			enc2 = this._keyStr.indexOf(input.charAt(i++));
			enc3 = this._keyStr.indexOf(input.charAt(i++));
			enc4 = this._keyStr.indexOf(input.charAt(i++));

			chr1 = (enc1 << 2) | (enc2 >> 4);
			chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
			chr3 = ((enc3 & 3) << 6) | enc4;

			output = output + String.fromCharCode(chr1);

			if (enc3 != 64) {
				output = output + String.fromCharCode(chr2);
			}
			if (enc4 != 64) {
				output = output + String.fromCharCode(chr3);
			}

		}

		output = Base64._utf8_decode(output);

		return output;

	},

	// private method for UTF-8 encoding
	_utf8_encode : function (string) {
		string = string.replace(/\r\n/g,"\n");
		var utftext = "";

		for (var n = 0; n < string.length; n++) {

			var c = string.charCodeAt(n);

			if (c < 128) {
				utftext += String.fromCharCode(c);
			}
			else if((c > 127) && (c < 2048)) {
				utftext += String.fromCharCode((c >> 6) | 192);
				utftext += String.fromCharCode((c & 63) | 128);
			}
			else {
				utftext += String.fromCharCode((c >> 12) | 224);
				utftext += String.fromCharCode(((c >> 6) & 63) | 128);
				utftext += String.fromCharCode((c & 63) | 128);
			}

		}

		return utftext;
	},

	// private method for UTF-8 decoding
	_utf8_decode : function (utftext) {
		var string = "";
		var i = 0;
		var c = c1 = c2 = 0;

		while ( i < utftext.length ) {

			c = utftext.charCodeAt(i);

			if (c < 128) {
				string += String.fromCharCode(c);
				i++;
			}
			else if((c > 191) && (c < 224)) {
				c2 = utftext.charCodeAt(i+1);
				string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
				i += 2;
			}
			else {
				c2 = utftext.charCodeAt(i+1);
				c3 = utftext.charCodeAt(i+2);
				string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
				i += 3;
			}

		}

		return string;
	}

}
