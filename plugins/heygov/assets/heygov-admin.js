
const heyGovUploadFile = (file) => {

	return new Promise((resolve, reject) => {
		var form = new FormData()
		form.append('file', file)

		jQuery.ajax({
			method: 'POST',
			url: HeyGov.apiUrl + 'wp/v2/media',
			data: form,
			contentType: false,
			processData: false,
			beforeSend: function (xhr) {
				xhr.setRequestHeader( 'X-WP-Nonce', HeyGov.nonce );
			},
			success: resolve,
			error: reject
		});
	})
}
