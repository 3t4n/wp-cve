
(function textareaResize() {
	var ta = document.getElementById('pgee_edit_excrpt'), plus = document.getElementById('plus'), minus = document.getElementById('minus');

	if ( ! ta || ! plus || ! minus )
		return false;

	plus.onclick = function() {
		var h = ta.clientHeight > 1000 ? 1000 : ta.clientHeight + 40;
		ta.style.height = h + 'px';
		return false;
	}

	minus.onclick = function() {
		var h = ta.clientHeight - 30 < 100 ? 100 : ta.clientHeight - 30;
		ta.style.height = h + 'px';
		return false;
	}
})();
