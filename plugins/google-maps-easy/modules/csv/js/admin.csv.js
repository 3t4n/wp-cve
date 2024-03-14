var gmpCsvImportData = {};
jQuery(document).ready(function(){
	jQuery('#gmpCsvExportMapsBtn').click(function(){
		var delimiter = jQuery('#gmpCsvExportDelimiter').val() || ';';
		toeRedirect(createAjaxLinkGmp({
			page: 'csv'
		,	action: 'exportMaps'
		, '_wpnonce' : GMP_NONCE['gmp_nonce']
		,	withMarkers: 1
		,	delimiter: delimiter
		,	onSubmit: gmpCsvImportOnSubmit
		,	onComplete: gmpCsvImportOnComplete
		}));
		return false;
	});
	jQuery('#gmpCsvExportMarkersBtn').click(function(){
		var delimiter = jQuery('#gmpCsvExportDelimiter').val() || ';';
		toeRedirect(createAjaxLinkGmp({
			page: 'csv'
		,	action: 'exportMarkers'
		, '_wpnonce' : GMP_NONCE['gmp_nonce']
		,	delimiter: delimiter
		,	onSubmit: gmpCsvImportOnSubmit
		,	onComplete: gmpCsvImportOnComplete
		}));
		return false;
	});
	jQuery('#gmpCsvExportFiguresBtn').click(function(){
		var delimiter = jQuery('#gmpCsvExportDelimiter').val() || ';';
		toeRedirect(createAjaxLinkGmp({
			page: 'csv'
			,	action: 'exportFigures'
			, '_wpnonce' : GMP_NONCE['gmp_nonce']
			,	delimiter: delimiter
			,	onSubmit: gmpCsvImportOnSubmit
			,	onComplete: gmpCsvImportOnComplete
		}));
		return false;
	});
	jQuery('#gmpCsvExportHeatmapBtn').click(function(){
		var delimiter = jQuery('#gmpCsvExportDelimiter').val() || ';';
		toeRedirect(createAjaxLinkGmp({
			page: 'csv'
			,	action: 'exportHeatmap'
			, '_wpnonce' : GMP_NONCE['gmp_nonce']
			,	delimiter: delimiter
			,	onSubmit: gmpCsvImportOnSubmit
			,	onComplete: gmpCsvImportOnComplete
		}));
		return false;
	});
	jQuery('#gmpCsvSaveBtn').click(function () {
		jQuery('#gmpCsvForm').submit();
		return false;
	});
	jQuery('#gmpCsvForm').submit(function () {
		jQuery(this).sendFormGmp({
			btn: '#gmpCsvSaveBtn'
		});
		return false;
	});

	/*jQuery('#gmpCsvImportMarkersBtn').on('click', function () {
		jQuery('input[name="csv_import_file"]').click();
	});*/
});
function gmpCsvImportOnSubmit() {
	var msg = jQuery('#gmpCsvImportMsg');

	msg.showLoaderGmp();
	msg.removeClass('toeErrorMsg');
	msg.removeClass('toeSuccessMsg');

	// Add CSV options to request data
	gmpCsvImportData['delimiter'] = jQuery('#gmpCsvExportDelimiter').val() || ';';
	//gmpCsvImportData['overwrite_same_names'] = jQuery('#gmpCsvOverwriteSameNames').attr('checked') ? 1 : 0;
}
function gmpCsvImportOnComplete(file, res) {
	toeProcessAjaxResponseGmp(res, 'gmpCsvImportMsg');
}