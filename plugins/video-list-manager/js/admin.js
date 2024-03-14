jQuery(document).ready(function($){
	//Set width for each element .tntVideoItem
	var tntMenuUl = $('li.toplevel_page_tnt_video_manage_page');
	tntMenuUl.find('a[href*="tnt_video_edit_page"]').css('display', 'none');
	tntMenuUl.find('a[href*="tnt_video_del_page"]').css('display', 'none');
	tntMenuUl.find('a[href*="tnt_video_cat_edit_page"]').css('display', 'none');
	tntMenuUl.find('a[href*="tnt_video_cat_del_page"]').css('display', 'none');

	var tntInfoVideoTable = '<table class="infoVideo borderDB form-table">';
	tntInfoVideoTable += '<tbody><tr valign="top">';
	tntInfoVideoTable += '<th scope="row"><label for="vTitle">Title</label></th>';
	tntInfoVideoTable += '<td><input type="text" class="required" size="50" name="vTitle[]"></td>';
	tntInfoVideoTable += '</tr>';
	tntInfoVideoTable += '<tr valign="top">';
	tntInfoVideoTable += '<th scope="row"><label for="vLink">Link</label></th>';
	tntInfoVideoTable += '<td><input type="url" class="required" size="50" name="vLink[]"></td>';
	tntInfoVideoTable += '</tr>';
	tntInfoVideoTable += '<tr valign="top">';
	tntInfoVideoTable += '<th scope="row"><label for="vStatus">Status</label></th>';
	tntInfoVideoTable += '<td>';
	tntInfoVideoTable += '<select name="vStatus[]">';
	tntInfoVideoTable += '<option value="1">Published</option>';
	tntInfoVideoTable += '<option value="0">Unpublished</option>';
	tntInfoVideoTable += '</select>';
	tntInfoVideoTable += '</td>';
	tntInfoVideoTable += '</tr>';
	tntInfoVideoTable += '<tr>';
	tntInfoVideoTable += '<th scope="row"><label for="vOrder">Order Number</label></th>';
	tntInfoVideoTable += '<td><input type="text" class="required digits" size="3" name="vOrder[]" value="100"></td>';
	tntInfoVideoTable += '</tr>';
	tntInfoVideoTable += '<tr>';
	tntInfoVideoTable += '<th scope="row"></th>';
	tntInfoVideoTable += '<td><a href="#" class="removeVideoItem button-secondary title="Remove Video Item">Remove</a></td>';
	tntInfoVideoTable += '</tr>';
	tntInfoVideoTable += '</tbody></table>';

	var tntVideoMessageError = '<p>Errors! Please check again <br />';
	tntVideoMessageError += '- Video title must be not empty <br />';
	tntVideoMessageError += '- Video link must be not empty<br />';
	tntVideoMessageError += '- Order is not empty and must be digits</p>';

	$('.addMoreVideo').click(function(e){
		e.preventDefault();
		e.stopPropagation();
		$('.infoVideoWrapper').append(tntInfoVideoTable);
	});

	$('.removeVideoItem').live('click', function(){
		$(this).parent().parent().parent().parent().remove();
	});

	$("#addVideoForm").validate();
	var validator = $("#addVideoForm").bind("invalid-form.validate", function() {
		$(".errorContainer").html(tntVideoMessageError);
		$(".errorContainer").addClass("dpb");
	}).validate({
		debug: true,
		errorElement: "em",
		errorContainer: $(".errorContainer")
	});

	$('#editVideoForm').validate({
		rules:{
			vTitle: {
				required: true
			},
			vLink: {
				required: true,
				url: true
			},
			vOrder: {
				required: true,
				digits: true
			}
		},
		messages:{
			vTitle: {
				required: "Please enter video title"
			},
			vLink: {
				required: "Please enter video link",
				url: "Must be link format"
			},
			vOrder: {
				required: "Please enter order number"
			}
		}
	});

	$('#editVideoCatForm').validate({
		rules:{
			catTitle : "required"
		},
		messages:{
			catTitle :{
				required: "Please enter category title"
			}
		}
	});

	$('#optionVideoForm').validate({
		rules:{
			videoLimit:{
				required: true,
				digits: true
			},
			videoLimitAdmin:{
				required: true,
				digits: true
			},
			videoColumn:{
				required: true,
				digits: true
			},
			videoWidth:{
				required: true,
				digits: true
			},
			videoHeight:{
				required: true,
				digits: true
			}
		}
	});

	if($("input.tntSocialFeature").is(":checked") == true)
	{
		$('.socialFeatureDetail').show();
	}
	else
	{
		$('.socialFeatureDetail').hide();	
	}

	$("input.tntSocialFeature").click(function(){
		if($(this).is(":checked"))
		{
			$(this).parent().find('.socialFeatureDetail').show();
		}
		else
		{
			$(this).parent().find('.socialFeatureDetail').hide();	
		}
	})
});	