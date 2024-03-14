function peb_deleteRow(id) {
	document.getElementById('row'+id).innerHTML='';

	return false;
}
function peb_addMore(){

	if ( ! Date.now ) {
		Date.now = function() { return new Date().getTime(); }
	}

	var id = Date.now(),
		tbody = document.getElementById('op_table').getElementsByTagName("TBODY")[0],
		tr    = document.createElement("TR"),
		td1   = document.createElement("TD"),
		td2   = document.createElement("TD"),
		td3   = document.createElement("TD"),
		td4   = document.createElement("TD");

	tr.id = 'row'+id;
	td1.innerHTML='<input type="text" name="peb_caption[]" />';
	td2.innerHTML='<input type="text" name="peb_before[]" />';
	td3.innerHTML='<input type="text" name="peb_after[]" />';
	td4.innerHTML='<a  href="#" onclick="return peb_deleteRow(' + id + ');">' + PEB.deleteText + '</a>';

	tr.appendChild(td1);
	tr.appendChild(td2);
	tr.appendChild(td3);
	tr.appendChild(td4);

	tbody.appendChild(tr);

	return false;
}