	    jQuery(document).ready(function(){
		var seoTagCloudPreviewParams = {};
		function seoTagCloudChangePreview(div){
			var query = [];
			for (var p in seoTagCloudPreviewParams) {
				query.push(encodeURIComponent(p) + "=" + encodeURIComponent(seoTagCloudPreviewParams[p]));
			}
			query.push("rnd=" + Math.random());
			query = query.join("&");
			var seoTagCloudIframe = div.find(".seo-tag-cloud-preview-iframe");
			seoTagCloudIframe.attr("src", seoTagCloudPreviewUrl + "?" + query);
			return true;
		}
		jQuery(".widgets-holder-wrap div.seo-tag-cloud .preview-link").one("click", function(e){
	    		var el = jQuery(this);
	    		var div = el.parents("div.seo-tag-cloud:visible");
			if (div.length) {
	    		var iframe = div.find(".seo-tag-cloud-preview-iframe");
	    		iframe.show();
	    		w = iframe.width();
	    		iframe.height(w + 40);
			seoTagCloudPreviewParams = {
				title: div.find(".seo-tag-cloud-title").val(),
				number: div.find(".seo-tag-cloud-number").val(),
				"text-transform": div.find("input[name='seo-tag-cloud-text-transform']:checked").val(),
				format: div.find("input[name='seo-tag-cloud-format']:checked").val(),
				target: div.find(".seo-tag-cloud-target").val(),
				"hide-credit": div.find(".seo-tag-cloud-hide-credit").is(":checked") ? 1 : 0
			};
			seoTagCloudChangePreview(div);
			div.find("input").change(function(e){
				var t = jQuery(e.target);
				var key = t.attr("name").substring("seo-tag-cloud-".length);
				seoTagCloudPreviewParams[key] = t.val();
				seoTagCloudChangePreview(div);
			});
			}
	    		return false;
	    	});
	    });
