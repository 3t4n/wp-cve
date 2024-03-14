
export const prepareFormData = ( { searchString, ajaxAction, nonce } ) => {
	let formData = new FormData();
	formData.append( 'action', ajaxAction );
	formData.append( 'security', nonce );
	formData.append( 'city', searchString );
	formData.append( 's', searchString );
	formData.append( 'search', searchString );

	return formData;
}
