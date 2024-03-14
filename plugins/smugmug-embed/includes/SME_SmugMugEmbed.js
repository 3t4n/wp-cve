/*var selectedAlbums = [];*/
var selectedImages = [];

//load selected image in admin area
jQuery().ready(function(){
   if (jQuery('#SME_selectedGalleries')){
	jQuery.ajax({
			type:"POST",
			url: "../wp-admin/admin-ajax.php",
			data: {
				action: 'SME_loadSelectedAlbums',
				nonce: SME_Ajax.nonce
				},
			success:function(data){
				jQuery('#SME_selectedGalleries').html(data.data);
			},
			error:function(data) {
				console.log(data);
			}
			
	});
   }
   	jQuery("img[data_id='sme_managed']").wrap('<div class="SME_watermark"><div class="SME_watermark-bar"></div></div>');
	jQuery(".SME_watermark-bar").append("<p id='SME_watermark-text'>SmugMug Embed Demo</p>");
   	if (!jQuery("#SME_dragwordlist").length) return;
jQuery("#SME_dragwordlist li").draggable({helper: 'clone'});
 jQuery(".SME_txtDropTarget").droppable({
    accept: "#SME_dragwordlist li",
    drop: function(ev, ui) {
      jQuery(this).insertAtCaret(ui.draggable.text());
    }
  });
});

function SME_toggleSliderSettings() {
	var SME_GallerySettings = document.getElementById("SME_GallerySettings");
if (jQuery("#GalleryOnly").is(":checked")) {
		jQuery(SME_GallerySettings).css("display","inline");
	}else {

	    jQuery(SME_GallerySettings).css("display","none");
		}
}

function doubleclick(el) {
	if (el.getAttribute("data-dblclick") == null) {
		el.setAttribute("data-dblclick", 1);
		setTimeout(function () {
			if (el.getAttribute("data-dblclick") == 1) {
				onsingle(el);
			}
			el.removeAttribute("data-dblclick");
		}, 200);
	} else {
		el.removeAttribute("data-dblclick");
		ondouble(el);
	}
}
function onsingle(el) {
	SME_ChangeState(el);
}
function ondouble(el) {
	var oldEl = document.getElementById(sessionStorage.getItem("SME_selected_folder"));
	if (oldEl && oldEl!=el) {
		SME_ChangeState(el);
	}
	SME_updateAdminFolders(el);
}

function SME_saveSelectedAlbums() {
	jQuery('.SME_loading').show();
	var jsondata = selectedAlbums;
	jQuery.ajax({
			type:"POST",
			url: "../wp-admin/admin-ajax.php",
			data: {
				action: 'saveSelectedAlbums',
				nonce: SME_Ajax.nonce,
				selectedAlbums: jsondata
				},
			success:function(data){
				SME_displayFeedback(data.data,"lightgreen");
			},
			error:function() {
				SME_displayFeedback("There was an error saving the albums. Please contact the developer","pink");
			}
	});
	jQuery('.SME_loading').hide();
}
function SME_displayFeedback(data,color) {
	jQuery( '#SME_feedback' ).html( '<p>' + data + '</p>' );
	jQuery( '#SME_feedback' ).css('backgroundColor',color).animate({'opacity':'1'},200).delay( 1500 ).animate({'opacity':'0'},2500);
}
function SME_updateAdminFolders(el) {
				jQuery('.SME_loading').show();
		jQuery.ajax({
			type:"POST",
			url: "../wp-admin/admin-ajax.php",
			data: {
				action: 'build_folder_list',
				nonce: SME_Ajax.nonce,
				nodeId: el.id,
				selectedAlbums:selectedAlbums
				},
			success:function(data){
            jQuery('#SME_GalleryChooser').html( data.data);
			jQuery.ajax({
					type:"POST",
					url: "../wp-admin/admin-ajax.php",
					data: {
						action: 'getBreadcrumbs',
						nonce: SME_Ajax.nonce,
						nodeId: el.id,
						refresh: 'true'
						},
					success:function(data){
						jQuery('#SME_breadcrumb').html( data.data);
					},
					error:function(data) {
						alert("There was an error");
					}
				});	
				jQuery('.SME_loading').hide();
			}
        });	

		
		
		
		
		
}

function SME_showSelectedGalleries() {
	var width =jQuery('#SME_hiddenGalleriesSelected').css("width");
	width=width.substring(0,width.length -2);
	if (width>0){
		jQuery('#SME_hiddenGalleriesSelected').animate({width:"0px"},"slow");
		jQuery('#SME_GalleryChooser').animate({opacity:1},"slow");
		jQuery('#SME_selectedGalleries').children().each(function () {
			if (!jQuery(this).hasClass("SME_thumbnail_album_active"))jQuery(this).remove();
		});
	}
	else
	{
		jQuery('#SME_hiddenGalleriesSelected').animate({width:"625px"},"slow");
		jQuery('#SME_GalleryChooser').animate({opacity:.1},"slow");
	}
}
function SME_ChangeLoadSub(el) {
	alert("in "+el.id);
}
////selects or deselects an image
function SME_ChangeState(el) {
	 var imgId=  el.id;
	 var type= el.getAttribute("data-type");
	 var className = "SME_thumbnail_album_active";
	 if (type=="Folder") var className = "SME_thumbnail_active";
	 if (jQuery(el).hasClass(className)){
        jQuery(el).removeClass(className);
		if (type!="Folder") 
			selectDeslectAlbum(el,"remove");
			if (jQuery(el).parent().attr("id")!="SME_selectedGalleries" && jQuery('#SME_selectedGalleries').find('#'+imgId).length) 
				jQuery('#SME_selectedGalleries').find('#'+imgId).remove();
			else 
				jQuery('#SME_picker_list').find('#'+imgId).removeClass(className);		
	 } else {
	    jQuery(el).addClass(className);
		if (type=="Folder") {
			var oldEl = document.getElementById(sessionStorage.getItem("SME_selected_folder"));
			if (oldEl && oldEl!=el) jQuery(oldEl).removeClass('SME_thumbnail_active');
			sessionStorage.setItem("SME_selected_folder", imgId);		
		} else {
			selectDeslectAlbum(el,"add");
		}
	  //  jQuery(checkEl).removeClass('uncheck').addClass('check');
	   // SME_updateCount("add",imgId);
	 //   SME_addToPreview(jQuery(el),imgId);
	 }
}
function selectDeslectAlbum(el,mode) {
	var nodeId=el.getAttribute("data-nodeid");
	var title = jQuery(el).find("a").attr("title");
	var imgUrl = jQuery("#imgid-"+nodeId).css("background-image");
	imgUrl = imgUrl.substr(5,imgUrl.length -7);
	var datakey = el.getAttribute("data-key");
	var datatype = el.getAttribute("data-type");
	var modifieddate = el.getAttribute("data-modified");
	var imageElement = {nodeID:nodeId, title:title, imgUrl:imgUrl, datakey:datakey, datatype:datatype,modifieddate:modifieddate};
	SME_updateCount(mode,imageElement,el);
}


function SME_removeSelect(el,checkEl) {
            jQuery(el).removeClass('SME_selected_album');
	    //jQuery(checkEl).removeClass('check').addClass('uncheck');
}
////add selected image to array and updates count
function alertArray(thisArray,column) {
	var thisStr = "";
	for (var i=0;i<thisArray.length;i++) {
	  thisStr = thisStr + thisArray[i][column] +"\n";
	}
  alert(thisStr);
}
function SME_updateCount(action,img,el) {
var id = el.id;
	if (action=="remove") {
	   //if (jQuery.inArray(img, selectedAlbums))
	   //alert(selectedAlbums.filter(p => p.nodeID == id));
        //selectedAlbums.splice(selectedAlbums.indexOf(selectedAlbums.filter(p => p.nodeID == id)), 1 );
		 selectedAlbums = selectedAlbums.filter(obj => obj.nodeID !=el.id);

	}
	else if (action=="add") {
	   // if (!jQuery.inArray(img, selectedAlbums))
		selectedAlbums.push(img);
	if (jQuery(el).parent().attr("id")!="SME_selectedGalleries") {
	//only call this if an image was clicked outside of the selected albums div
		jQuery.ajax({
			type:"POST",
			url: "../wp-admin/admin-ajax.php",
			data: {
				action: 'addAlbumToSelected',
				nonce: SME_Ajax.nonce,
				galleryvalue:img
				},
			success:function(data){
				jQuery('#SME_selectedGalleries').append(data.data);
			},
			error:function(data) {
				console.log(data);
			}
		});	
	}	
	}


	if (selectedAlbums.length >0){
   		document.getElementById("SME_NumberGalleriesSelected").innerHTML= selectedAlbums.length;
   		//document.getElementById("SME_Clear").style.display="block";
   		//jQuery("#SME_insert").removeAttr("disabled");
   	} else {
   		document.getElementById("SME_NumberGalleriesSelected").innerHTML="0";   	
   //		document.getElementById("SME_Clear").style.display="none";
   //		jQuery("#SME_insert").attr("disabled","disabled");
   	}
}
function SME_clearAll() {
	for (var i=0;i<selectedImages.length;i++) {
 	    var checkEl = document.getElementById(selectedImages[i]);
 	    jQuery(checkEl).parent().removeClass('selected');
	    jQuery(checkEl).children("a").removeClass('check').addClass('uncheck');
	}
	selectedImages = [];
	document.getElementById("SME_Count").innerHTML="";   	
   	document.getElementById("SME_Clear").style.display="none";
	jQuery("#SME_PreviewHolder").empty();
   	jQuery("#SME_insert").attr("disabled","disabled");	
}
function SME_selectAll() {
    var getDivId = document.getElementsByName("SME_imageDiv");
    for(var i=0; i<getDivId.length; i++) {
		if (!( jQuery(getDivId[i]).parent().hasClass("selected")))
			SME_ChangeState(getDivId[i]);
	}
	
	//selectedImages = [];
	//document.getElementById("SME_Count").innerHTML="";   	
   	//document.getElementById("SME_Clear").style.display="block";
	//jQuery("#SME_PreviewHolder").empty();
   	//jQuery("#SME_insert").attr("disabled","disabled");	
}


function SME_addToPreview(el,imgId) {

	var previewHolder = document.getElementById("SME_PreviewHolder");
	jQuery("#SME_PreviewHolder").append('<li id="li'+imgId+'" class="SME_attachment-preview" onclick="SME_removeFromPreview(this)"></li>');
	jQuery(el).children("div").clone().appendTo("#li"+imgId);
	
}


function ajaxInsert(){
	jQuery(SME_spinner).css("display","inline-block");

	jQuery('input[name=selectedImages]').val(selectedImages);
	var SME_insertForm = document.getElementById("insertForm");
	SME_insertForm = jQuery(SME_insertForm).serialize();
	jQuery.ajax({
	type:"POST",
	url: "../wp-admin/admin-ajax.php",
	data:SME_insertForm,
	success:function(data){
	jQuery("#SME_hiddenDiv").html(data);
	SME_clearAll();
		jQuery(SME_spinner).css("display","none");

	}
	});
}

function SME_removeFromPreview(el) {
        if (el.id.substr(0,2)!="li") el=document.getElementById("li"+el.id);

	var imgId = el.id.substring(2);
	var imgEl = jQuery("#"+imgId);
	 var checkEl =  jQuery(imgEl).children("a");	 
	 if (jQuery(imgEl).parent().hasClass('selected')){
             SME_removeSelect(imgEl,checkEl);
             SME_updateCount("remove",imgId);
         }
	
       jQuery(el).remove();
} 

jQuery.fn.insertAtCaret = function (myValue) {
  return this.each(function(){
  //IE support
  if (document.selection) {
    this.focus();
    sel = document.selection.createRange();
    sel.text = myValue;
    this.focus();
  }
  //MOZILLA / NETSCAPE support
  else if (this.selectionStart || this.selectionStart == '0') {
    var startPos = this.selectionStart;
    var endPos = this.selectionEnd;
    var scrollTop = this.scrollTop;
    this.value = this.value.substring(0, startPos)+ myValue+ this.value.substring(endPos,this.value.length);
    this.focus();
    this.selectionStart = startPos + myValue.length;
    this.selectionEnd = startPos + myValue.length;
    this.scrollTop = scrollTop;
  } else {
    this.value += myValue;
    this.focus();
  }
  });
};