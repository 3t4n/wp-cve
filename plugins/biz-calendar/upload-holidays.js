jQuery(document).ready(function($) {
	$('#holidays-upload').click(function() {
		$('#file-upload-result').html("");
		if (!$('#holidays-file').val()) {
			$('#file-upload-result').html("ファイルを選択してください");
			return false;
		}
		$('#file-upload-result').html("ファイルをアップロードしています");
		$('#holidays-upload').attr('disabled', '');
		var form = $('#biz-holidays').get(0);
		var formData = new FormData(form);
		formData.append('action', bizcalAjax.action);
		$.ajax({
			type : 'POST',
			url : bizcalAjax.ajaxurl,
			data : formData,
			dataType : 'json',
			contentType : false,
			processData : false,
			timeout : 20000,
		}).done(function(data) {
			$('#file-upload-result').html(data.message);
		}).fail(function(data) {
			$('#file-upload-result').html("");
			alert("ファイルのアップロードに失敗しました");
		}).complete(function(data) {
			$('#holidays-file').val("");
			$('#holidays-upload').removeAttr('disabled');
		});
		return false;
	});
});