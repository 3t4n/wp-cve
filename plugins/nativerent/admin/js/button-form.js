// Button Form lib.
(function ($) {
	$(document).ready(function () {
		$(document).on('click', '.nativerent-button-form', function (e) {
			e.preventDefault(e)

			var method = $(this).data('form-method')
			var action = $(this).data('form-action')
			var payload = JSON.parse(atob($(this).data('form-payload')))
			var target = $(this).data('form-target')
			var confirm = $(this).data('form-confirm')
			var form = document.createElement('form')

			if (confirm && !window.confirm(confirm)) {
				return false
			}

			form.method = method ? method : 'POST'
			form.action = action
			form.target = target ? target : ''

			for (var field in payload) {
				var input = document.createElement('input')
				input.type = 'hidden'
				input.name = field
				input.value = payload[field]
				form.appendChild(input)
			}

			document.body.appendChild(form)
			form.submit()
		})
	})
})(window.jQuery)
