function moreInfo(obj) {
	var el = document.getElementById(obj);
	if ( el.style.display != 'none' ) {
		el.style.display = 'none';
	}
	else {
		el.style.display = 'block';
	}
}
function whNew(newVal) {
	if (newVal == ""){ return }
	var whForm = document.fasttube;
	var dims = newVal.split(",");
	whForm['fast_tube[width]'].value = dims[0];
	whForm['fast_tube[height]'].value = dims[1];
}