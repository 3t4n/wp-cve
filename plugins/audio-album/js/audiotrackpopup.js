document.addEventListener("DOMContentLoaded", function() {

	var infoPopups = document.querySelectorAll('.info-popup');

	infoPopups.forEach(function(infoPopup) {
		infoPopup.addEventListener("click", function(event) {
			var w = event.currentTarget.dataset.width;
			var h = event.currentTarget.dataset.height;

			var left = (screen.width / 2) - (w / 2);
			var top = (screen.height / 2) - (h / 2);

			var NWin = window.open(event.currentTarget.href, '', 'scrollbars=1,width=' + w + ',height=' + h + ',top=' + top + ',left=' + left);

			if (window.focus) {
				NWin.focus();
			}

			event.preventDefault();
		});
	});

});