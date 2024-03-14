"use strict";
jQuery(document).ready(function(){
	// Fallback for case if library was not loaded
	if(!jQuery.fn.jqGrid) {
		return;
	}
	var tblId = 'wtbpTableTbl',
		tableObj = jQuery('#'+ tblId),
		grid = tableObj.jqGrid({
		url: wtbpTblDataUrl
	,	datatype: 'json'
	,	autowidth: true
	,	shrinkToFit: true
	,	colNames:[toeLangWtbp('ID'), toeLangWtbp('Title'), toeLangWtbp('Shortcode')]
	,	colModel:[
			{name: 'id', index: 'id', searchoptions: {sopt: ['eq']}, width: '50', align: 'center'}
		,	{name: 'title', index: 'title', searchoptions: {sopt: ['eq']}, align: 'center'}
		,	{name: 'shortcode', index: 'shortcode', searchoptions: {sopt: ['eq']}, align: 'center'}
		]
	,	postData: {
			search: {
				text_like: jQuery('#'+ tblId+ 'SearchTxt').val()
			},
			wtbpNonce: window.wtbpNonce
		}
	,	rowNum:10
	,	rowList:[10, 20, 30, 1000]
	,	pager: '#'+ tblId+ 'Nav'
	,	sortname: 'id'
	,	viewrecords: true
	,	sortorder: 'desc'
	,	jsonReader: { repeatitems : false, id: '0' }
	,	caption: toeLangWtbp('Current PopUp')
	,	height: '100%'
	,	emptyrecords: toeLangWtbp('You have no Tables for now.')
	,	multiselect: true
	,	onSelectRow: function(rowid, e) {
			var tblId = jQuery(this).attr('id')
			,	selectedRowIds = jQuery('#'+ tblId).jqGrid ('getGridParam', 'selarrrow')
			,	totalRows = jQuery('#'+ tblId).getGridParam('reccount')
			,	totalRowsSelected = selectedRowIds.length;
			if(totalRowsSelected) {
				jQuery('#wtbpTableRemoveGroupBtn,#wtbpTableExportBtn').removeAttr('disabled');
				if(totalRowsSelected == totalRows) {
					jQuery('#cb_'+ tblId).prop('indeterminate', false);
					jQuery('#cb_'+ tblId).prop('checked', true);
				} else {
					jQuery('#cb_'+ tblId).prop('indeterminate', true);
					jQuery('#cb_'+ tblId).prop('checked', false);
				}
			} else {
				jQuery('#wtbpTableRemoveGroupBtn,#wtbpTableExportBtn').attr('disabled', 'disabled');
				jQuery('#cb_'+ tblId).prop('indeterminate', false);
				jQuery('#cb_'+ tblId).prop('checked', false);
			}
			wtbpCheckUpdate(jQuery(this).find('tr:eq('+rowid+')').find('input[type=checkbox].cbox'));
			wtbpCheckUpdate('#cb_'+ tblId);
		}
	,	beforeRequest: function() {
			jQuery('#wtbpTableTblNav_center .ui-pg-table').addClass('woobewoo-hidden');
		}
	,	gridComplete: function(a, b, c) {
			var tblId = jQuery(this).attr('id');
			jQuery('#wtbpTableRemoveGroupBtn,#wtbpTableExportBtn').attr('disabled', 'disabled');
			jQuery('#cb_'+ tblId).prop('indeterminate', false);
			jQuery('#cb_'+ tblId).prop('checked', false);
			// Custom checkbox manipulation
			wtbpInitCustomCheckRadio('#'+ jQuery(this).attr('id') );
			wtbpCheckUpdate('#cb_'+ jQuery(this).attr('id'));
			jQuery('#wtbpTableTblNav_center .ui-pg-table').removeClass('woobewoo-hidden');
		}
	,	loadComplete: function() {
			var tblId = jQuery(this).attr('id');
			if (this.p.reccount === 0) {
				jQuery(this).hide();
				jQuery('#'+ tblId+ 'EmptyMsg').removeClass('woobewoo-hidden');
			} else {
				jQuery(this).show();
				jQuery('#'+ tblId+ 'EmptyMsg').addClass('woobewoo-hidden');
			}
		}
	});
	jQuery(window).on('load resize', tableObj, function(event) {
		tableObj.jqGrid('setGridWidth', jQuery('#containerWrapper').width());
	});
	jQuery('#'+ tblId).on('change', '.cbox', function() {
		if(!jQuery('#'+ tblId+ ' .cbox:checked').length){
			jQuery('#wtbpTableRemoveGroupBtn,#wtbpTableExportBtn').attr('disabled', true);
			jQuery('#cb_'+ tblId).prop('indeterminate', false);
			tableObj.jqGrid('resetSelection');
		}
	});
	jQuery('#'+ tblId+ 'NavShell').append( jQuery('#'+ tblId+ 'Nav') );
	jQuery('#'+ tblId+ 'Nav').find('.ui-pg-selbox').insertAfter( jQuery('#'+ tblId+ 'Nav').find('.ui-paging-info') );
	jQuery('#'+ tblId+ 'Nav').find('.ui-pg-table td:first').remove();
	// Make navigation tabs to be with our additional buttons - in one row
	jQuery('#'+ tblId+ 'Nav_center').prepend( jQuery('#'+ tblId+ 'NavBtnsShell') ).css({
		'width': '80%'
	,	'white-space': 'normal'
	,	'padding-top': '8px'
	});
	jQuery('#'+ tblId+ 'SearchTxt').keyup(function(){
		var searchVal = jQuery.trim( jQuery(this).val() );
		if( true ) {
			wtbpGridDoListSearch({
				text_like: searchVal
			}, tblId);
		}
	});

	jQuery('#'+ tblId+ 'EmptyMsg').insertAfter(jQuery('#'+ tblId+ '').parent());
	jQuery('#'+ tblId+ '').jqGrid('navGrid', '#'+ tblId+ 'Nav', {edit: false, add: false, del: false});

	jQuery('#cb_'+ tblId+ '').change(function(){
		if (jQuery(this).is(':checked')) {
			jQuery('#wtbpTableRemoveGroupBtn,#wtbpTableExportBtn').prop('disabled', false);
			grid.jqGrid('resetSelection');
    		var ids = grid.getDataIDs();
    		for (var i=0, il=ids.length; i < il; i++) {
        		grid.jqGrid('setSelection',ids[i], true);
    		}
		} else {
			jQuery('#wtbpTableRemoveGroupBtn,#wtbpTableExportBtn').prop('disabled', true);
			grid.jqGrid('resetSelection');
			wtbpCheckUpdate(tableObj.find('input[type=checkbox].cbox'));
		}
	});

	tableObj.on('change', 'td input[type=checkbox].cbox', function(){
		var cbox = jQuery(this);
		grid.jqGrid('setSelection',cbox.closest('tr').index(), cbox.is(':checked'));
	});

	jQuery('#wtbpTableRemoveGroupBtn').click(function(){
		var selectedRowIds = jQuery('#wtbpTableTbl').jqGrid ('getGridParam', 'selarrrow')
		,	listIds = [];
		for(var i in selectedRowIds) {
			var rowData = jQuery('#wtbpTableTbl').jqGrid('getRowData', selectedRowIds[ i ]);
			listIds.push( rowData.id );
		}
		var popupLabel = '';
		if(listIds.length == 1) {	// In table label cell there can be some additional links
			var labelCellData = wtbpGetGridColDataById(listIds[0], 'title', 'wtbpTableTbl');
			popupLabel = jQuery(labelCellData).text();
		}
		var confirmMsg = listIds.length > 1
			? toeLangWtbp('Are you sur want to remove '+ listIds.length+ ' Tables?')
			: toeLangWtbp('Are you sure want to remove "'+ popupLabel+ '" Table?')
		if(confirm(confirmMsg)) {
			jQuery.sendFormWtbp({
				btn: this
			,	data: {mod: 'wootablepress', action: 'removeGroup', listIds: listIds, wtbpNonce: window.wtbpNonce}
			,	onSuccess: function(res) {
					if(!res.error) {
						jQuery('#wtbpTableTbl').trigger( 'reloadGrid' );
					}
				}
			});
		}
		return false;
	});
	jQuery('#wtbpTableExportBtn').on('click', function(e){
		e.preventDefault();
		
		var selectedRowIds = jQuery('#wtbpTableTbl').jqGrid('getGridParam', 'selarrrow'),
			listIds = [];
		for(var i in selectedRowIds) {
			var rowData = jQuery('#wtbpTableTbl').jqGrid('getRowData', selectedRowIds[i]);
			listIds.push( rowData.id );
		}
		if (listIds.length) {
			jQuery.sendFormWtbp({
				btn: this,
				data: {mod: 'utilities', action: 'exportGroup', listIds: listIds, wtbpNonce: window.wtbpNonce},
				onSuccess: function(res) {
					if(!res.error) {
						var blob = new Blob(
							[ res.data.tables ],
							{ type: 'text/sql' }
						);
						var fileName = 'wtbp_export.sql';
						var link = document.createElement('a');
						link.href = window.URL.createObjectURL(blob);
						link.download = fileName;
						link.click();
						
						jQuery('#wtbpTableTbl').trigger( 'reloadGrid' );
						link.remove();
					}
				}
			});
		}
		return false;
	});

	// *******  import filters START  *******
	var $importForm = jQuery('#wtbpImportForm');
	jQuery('<input type="hidden" name="wtbpNonce" value="'+window.wtbpNonce+'">').appendTo($importForm);
	var $importWnd = jQuery('#wtbpImportWnd').dialog({
		modal: true,
		autoOpen: false,
		width: 500,
		height: 250,
		buttons: {
			'Import': function() {
				$importForm.submit();
			}
		},
		create: function () {
			jQuery(this).closest('.ui-dialog').addClass('woobewoo-plugin');
		}
	});
	var $wndButtonset = $importWnd.parents('.ui-dialog:first')
		.find('.ui-dialog-buttonpane .ui-dialog-buttonset');
	$importForm.submit(function(){
		var $btn = $wndButtonset.find('button:first');
		$btn.width( $btn.width() );
		$btn.showLoaderWtbp();
		
		var url = '';
		if(typeof(ajaxurl) == 'undefined' || typeof(ajaxurl) !== 'string')
			url = WTBP_DATA.ajaxurl;
		else
			url = ajaxurl;
		
		var fd = new FormData($importForm.get(0));
		
		jQuery.ajax({
			url: url,
			data: fd,
			type: 'POST',
			processData: false,
			contentType: false,
			success: function(res) {
				if(!res.error) {
					$importWnd.dialog('close');
					jQuery('#wtbpImportInput').val('');
					jQuery('#wtbpTableTbl').trigger( 'reloadGrid' );
					$btn.html('Import')
				}
			}
		});
		return false;
	});
	jQuery('#wtbpTableImportBtn').on('click', function(e){
		e.preventDefault();
		$importWnd.dialog('open');
		return false;
	});
	// *******  import filters END  *******
	wtbpInitCustomCheckRadio('#'+ tblId+ '_cb');
});
