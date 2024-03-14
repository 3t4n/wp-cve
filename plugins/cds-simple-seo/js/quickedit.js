jQuery(document).ready(function (r) {
	if ("undefined" != typeof inlineEditPost) {
		var _ = inlineEditPost.edit;
		inlineEditPost.edit = function (t) {
			_.apply(this, arguments);
			var e = 0;
			if ("object" == typeof t && (e = parseInt(this.getId(t))), 0 < e) {
				var i = r("#edit-" + e),
					o = r("#post-" + e),
					s = r(".column-seo_title", o).text(),
					n = r(".column-seo_description", o).text();
				r(".seo_title", i).val(s), r(".seo_description", i).val(n);
				var l = r(".row-title", o).text(),
					c = r(".seo_title", i).attr("placeholder");
				r(".seo_title", i).attr("placeholder", l + c);
				var d = function (e, o) {
					r(".seo_" + e + "_count", i).text(r(".seo_" + e, i).val().length), r(".seo_" + e, i).val().length <= o ? r(".seo_" + e + "_count", i).css("color", "#70C034") : r(".seo_" + e + "_count", i).css("color", "#dd3d36"), r(".seo_" + e, i).on("keyup blur", function () {
						var t = r(this).val().length;
						r(".seo_" + e + "_count", i).text(t), t <= o ? r(".seo_" + e + "_count", i).css("color", "#70C034") : r(".seo_" + e + "_count", i).css("color", "#dd3d36")
					})
				};
				d("title", 70), d("description", 350)
			}
		}
	}
});