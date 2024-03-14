export const createValidHtml = ( inputHtml ) => {
	if ( inputHtml.length ) {

		var html = inputHtml;
		var div = document.createElement("div");
		div.innerHTML = html;
		var text = div.textContent || div.innerText || "";
		return text;
	} else {
		console.error( "Error in creating 'valid' html." );
		return '(no title can display. Error? Check console.)';
	}

};
