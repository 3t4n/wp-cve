jQuery(document).ready(function($) {
	if($("#ssc_phone_replace").html()){
		var original = $("#ssc_phone_original").html();
		var originalParts = original.split("-");
		var regex = new RegExp("((\\(" + originalParts[0] + "\\) ?)|(" + originalParts[0] + "-))?" + originalParts[1] + "-" + originalParts[2], "g");
		var text = $("body:first").html();
		text = text.replace(regex, $("#ssc_phone_replace").html());
		$("body:first").html(text);
	}
});