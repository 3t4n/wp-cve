jQuery(document).ready(function($){
	if ($("#mf7RepeatableOption").length > 0)
	{
		$("#addRowBtn").click(function(e){
			e.preventDefault();
			var clone = $("#mf7RepeatableOption").children(":last-child").clone();
			var btn_data = clone.find(".mf7_delete_listing_btn").data("id");
			clone.attr("data-id", parseInt(clone.attr("data-id")) + 1);
			clone.find("input").each(function(){
				var tmp_name = $(this).attr("name");
				tmp_name = tmp_name.replace(/\[[0-9]+\]/, function(match, number) {
					number = parseInt(match.substring(1, match.length - 1));
					return "[" + (number + 1) + "]";
				});
				$(this).attr("name", tmp_name);
				$(this).val("");
				$(this).removeProp("checked");
			});
			clone.find(".mf7_delete_listing_btn").attr("data-id", parseInt(btn_data) + 1);
			console.log(clone.find(".mf7_delete_listing_btn").data("id"));
			clone.appendTo('#mf7RepeatableOption');
		});
		$("#mf7RepeatableOption").on("click", ".mf7_delete_listing_btn", function(e){
			e.preventDefault();
			var data_id = $(this).attr("data-id");
			console.log($(this));
			console.log(data_id);
			if ($("#mf7RepeatableOption").find(".mf7_option_group").length == 1){
				$(".mf7_option_group[data-id='"+data_id+"']").find("input").val("");
				$(".mf7_option_group[data-id='"+data_id+"']").find("input").removeProp("checked");
			}
			else
			{
				console.log(data_id);
				$(".mf7_option_group[data-id='"+data_id+"']").remove();
			}
		});
		var expanded = false;
		var in_function = 0;
		$("#mf7RepeatableOption").on("click focus", ".mf7_fancy_select_btn", function(e){
			e.preventDefault();
			$(this).parent().children("ul").addClass("hover");
		});
		$("#mf7RepeatableOption").on("focusout", ".mf7_fancy_select_btn", function(e){
			e.preventDefault();
			$(this).parent().children("ul").removeClass("hover");
		});
		$("#mf7RepeatableOption").on( 'mouseenter', '.mf7_fancy_select ul', function() {
			$(this).addClass('hover');
		});
		// $("#mf7RepeatableOption").on( 'mouseleave', '.mf7_fancy_select ul', function() {
		// 	$(this).removeClass('hover');
		// });
	}
});
