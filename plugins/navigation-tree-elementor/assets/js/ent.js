( function( $ ) {
$( document ).ready(function() {

var BuildButtons = function() {
	$('#fx-el-navtree .fx-editor-trigger').each(function(){
		var id = $(this).attr('data-id');
		var iFrameDOM = $("iframe#elementor-preview-iframe").contents();

		$(this).find('.elementor-element-edit-mode').remove();

		iFrameDOM.find("*[data-id='" + id + "']").clone(true).appendTo('#fx-el-navtree .fx-editor-trigger[data-id="' + id + '"]').empty();
		var buttonList = iFrameDOM.find("*[data-id='" + id + "'] > .elementor-element-overlay").clone(true);				
				
		buttonList.appendTo('#fx-el-navtree .fx-editor-trigger[data-id="' + id + '"] .elementor-element-edit-mode');
	});			
}	

var TurnListIntoTree = function() {
	$('#fx-el-navtree li > ul').each(function(i) {
		// Find this list's parent list item.
		var parentLi = $(this).parent('li');

		// Style the list item as folder.
		parentLi.addClass('folder');

		// Temporarily remove the list from the
		// parent list item, wrap the remaining
		// text in an anchor, then reattach it.
		var subUl = $(this).remove();
		parentLi.wrapInner('<a/>').find('a').click(function() {
		    // Make the anchor toggle the leaf display.
		    subUl.toggle();
		});
		parentLi.append(subUl);
	});

	// Hide all lists except the outermost.
	$('#fx-el-navtree ul ul').hide();

	BuildButtons();
}

var UpdateNavigationList = function(panel, model, view){
	var pageID = $('#fx-el-navtree').attr('data-ent-post-id');
	var baseUrl = $('#fx-el-navtree').attr('data-ent-base-url');

	jQuery.ajax({
        type:'POST',
        data:{
        	current_page: pageID,
        	action:'naviTreeElementor_update'
        },
        url: ajaxurl,
        success: function(output) {
          $('#fx-el-navtree .inner').empty();
	 	  $('#fx-el-navtree .inner').append(output);
	 	  TurnListIntoTree();
        }
    });
}

// Init
UpdateNavigationList();

$(document).on('click', '#fx-el-navtree .eicon-chevron-right', function(event){
	$('#fx-el-navtree').addClass('closed');
	$('.eicon-chevron-left').addClass('active');
	$('.eicon-chevron-right').removeClass('active');	
});

$(document).on('click', '#fx-el-navtree .eicon-chevron-left', function(event){
	$('#fx-el-navtree').removeClass('closed');
	$('.eicon-chevron-left').removeClass('active');
	$('.eicon-chevron-right').addClass('active');

	BuildButtons();
});

$(document).on('click', '#fx-el-navtree .refresh', function(event){
	UpdateNavigationList();	
});
$(document).on('click', '#elementor-panel-saver-button-publish', function(event){
	var updateNavigationList = setTimeout(UpdateNavigationList, 2000);
});


});
} )( jQuery );
